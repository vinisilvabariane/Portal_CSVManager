<?php
class PDOExceptionHandler
{
    public function PDOExceptionHandler($e)
    {
        if ($e instanceof PDOException) {
            $errorData = [
                'message' => $e->getMessage(),
                'pdoCode' => $e->getCode(),
                'sqlCode' => $e->errorInfo[1],
            ];

            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($errorData, JSON_UNESCAPED_UNICODE);
        }
    }
}
