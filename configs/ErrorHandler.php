<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/helpers/SlackAPI.php");

class ErrorHandler
{
    private $api;
    private $channel;

    public function __construct()
    {
        $this->api = new SlackAPI();
        date_default_timezone_set('America/Sao_Paulo');
    }

    private function createErrorMessage(int $errno, string $errstr, string $errfile, int $errline, array $trace): string
    {
        // Verifica se $_SESSION está definida antes de usá-la
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'N/A';

        $errorInfo = " *Error Alert:* \n";
        $errorInfo .= "```\n";
        $errorInfo .= "Date: " . date("d/m/Y H:i:s") . "\n";
        $errorInfo .= "Usuário: {$username}\n";
        $errorInfo .= "Message: {$errstr}\n";
        $errorInfo .= "Error Code: {$errno}\n";
        $errorInfo .= "File: {$errfile}\n";
        $errorInfo .= "Line: {$errline}\n";
        $errorInfo .= "```";
        $errorInfo .= $this->getFormattedTrace($trace);
        return $errorInfo;
    }

    private function createPdoExceptionMessage(PDOException $exception): string
    {
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'N/A';

        $errorInfo = " *Error Alert:* \n";
        $errorInfo .= "```\n";
        $errorInfo .= "Date: " . date("d/m/Y H:i:s") . "\n";
        $errorInfo .= "Usuário: {$username}\n";
        $errorInfo .= "Message: " . $exception->getMessage() . "\n";
        $errorInfo .= "Error Code: " . $exception->getCode() . "\n";
        $errorInfo .= "File: " . $exception->getFile() . "\n";
        $errorInfo .= "Line: " . $exception->getLine() . "\n";
        $errorInfo .= "```";

        // Adiciona informações adicionais
        $errorInfo .= $this->collectServerInfo();
        return $errorInfo;
    }

    private function getFormattedTrace(array $trace): string
    {
        $formattedTrace = " *Trace:* \n```\n";
        foreach ($trace as $index => $traceInfo) {
            $file = $traceInfo['file'] ?? 'N/A';
            $line = $traceInfo['line'] ?? 'N/A';
            $formattedTrace .= "#{$index}: {$file} ({$line})\n";
        }
        $formattedTrace .= "\n```";
        return $formattedTrace;
    }

    private function collectRequestInfo(): string
    {
        $requestInfo = " *FormData:* \n```\n";
        $requestInfo .= json_encode($_POST);
        $requestInfo .= "\n```";
        return $requestInfo;
    }

    private function collectServerInfo(): string
    {
        $serverInfo = " *Server:* \n```\n";
        $serverInfo .= "- IP do cliente: {$_SERVER['REMOTE_ADDR']};\n";
        $serverInfo .= "- User Agent do navegador do cliente: {$_SERVER['HTTP_USER_AGENT']};\n";
        $serverInfo .= "- Porta usada pelo cliente: {$_SERVER['REMOTE_PORT']};\n";
        $serverInfo .= "- Query da URL: {$_SERVER['QUERY_STRING']};\n";
        $serverInfo .= "- URL da página de origem da requisição: {$_SERVER['HTTP_REFERER']};\n";
        $serverInfo .= "- Host do servidor do cliente: {$_SERVER['HTTP_HOST']};\n";
        $serverInfo .= "- Cabeçalho de conexão do cliente: {$_SERVER['HTTP_CONNECTION']};\n";
        $serverInfo .= "- ID da Sessão: {$_COOKIE['PHPSESSID']};\n";
        $serverInfo .= "\n```";
        return $serverInfo;
    }

    public function errorHandler(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $this->handleError($errno, $errstr, $errfile, $errline);
    }

    public function exceptionHandler(Throwable $exception): void
    {
        $this->handleError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    private function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $trace = debug_backtrace();
        array_shift($trace);
        $message = $this->createErrorMessage($errno, $errstr, $errfile, $errline, $trace);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST) $message .= $this->collectRequestInfo();
        $message .= $this->collectServerInfo();
        $this->sendErrorAlert($message);
    }

    public function pdoExceptionHandler(PDOException $exception): void
    {
        $errorData = [
            'message' => $exception->getMessage(),
            'pdoCode' => $exception->getCode(),
            'sqlCode' => $exception->errorInfo[1],
        ];

        // Verificar se é um erro de registro duplicado
        $isDuplicateEntryError = $exception->errorInfo[1] == 1062;

        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($errorData, JSON_UNESCAPED_UNICODE);
        if (!$isDuplicateEntryError) {
            $message = $this->createPdoExceptionMessage($exception);
            $this->sendErrorAlert($message);
        }
    }

    private function sendErrorAlert(string $message): void
    {
        switch ($_SERVER["HTTP_HOST"]) {
            case '10.1.5.20':
                $this->channel = 'error-alert-prod';
                break;
            default:
                $this->channel = 'vinicius-bariane';
                break;
        }

        $this->api->send($this->channel, $message);
    }
}
