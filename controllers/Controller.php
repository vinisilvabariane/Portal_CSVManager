<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/models/Model.php";

class Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new Model();
    }
    public function login(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"] ?? "");
            $password = trim($_POST["password"] ?? "");
            if (empty($username) || empty($password)) {
                $this->sendErrorResponse(400, "Username e password são obrigatórios.");
                return;
            }
            try {
                $user = $this->model->login($username, $password);
                if ($user) {
                    session_start();
                    $_SESSION["logado"] = true;
                    $_SESSION["user_id"] = $user['id'];
                    $_SESSION["username"] = $user['username'];
                    $_SESSION["user_email"] = $user['email'];
                    $_SESSION["user_role"] = $user['role'];
                    $_SESSION["user_orgao"] = $user['orgao'] ?? null;
                    echo json_encode([
                        "status" => "success",
                        "message" => "Login realizado com sucesso.",
                        "redirect" => "/PortalMultiGarantia/views/dashboard.php"
                    ]);
                } else {
                    $this->sendErrorResponse(401, "Credenciais inválidas.");
                }
            } catch (Exception $e) {
                $this->sendErrorResponse(500, "Erro interno no servidor: " . $e->getMessage());
            }
        } else {
            $this->sendErrorResponse(405, "Método não permitido. Use POST.");
        }
    }

    public function createUser(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->sendErrorResponse(405, "Método não permitido. Use POST.");
            return;
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendErrorResponse(400, "JSON inválido");
            return;
        }
        if (empty($data['orgao']) || empty($data['email']) || empty($data['password'])) {
            $this->sendErrorResponse(400, "Dados incompletos para cadastro. Nome, email e senha são obrigatórios.");
            return;
        }
        try {
            $success = $this->model->createUser($data);
            if ($success) {
                http_response_code(201);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuário cadastrado com sucesso!'
                ]);
            } else {
                $this->sendErrorResponse(500, "Falha ao cadastrar usuário.");
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->sendErrorResponse(409, "Email ou cnpj já cadastrado.");
            } else {
                $this->sendErrorResponse(500, "Erro no servidor: " . $e->getMessage());
            }
        }
    }

    public function consultGarantia()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Método não permitido']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $serialNumber = trim($data['serialNumber'] ?? '');
        if (empty($serialNumber)) {
            echo json_encode(['error' => 'Número serial é obrigatório']);
            exit;
        }
        try {
            $warrantyData = $this->model->consultGarantia($serialNumber);
            if ($warrantyData) {
                echo json_encode([
                    'success' => true,
                    'data' => $warrantyData
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Nenhum produto encontrado com este número serial'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getSerial()
    {
        try {
            $Model = new Model();
            $seriais = $Model->getSerial();
            header('Content-Type: application/json');
            echo json_encode($seriais);
        } catch (Exception $e) {
            header("Content-Type: application/json");
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getGarantia()
    {
        try {
            $garantiaModel = new Model();
            $garantias = $garantiaModel->getGarantia();
            header('Content-Type: application/json');
            echo json_encode($garantias);
        } catch (Exception $e) {
            header("Content-Type: application/json");
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getGarantiaByNota()
    {
        try {
            $garantiaModel = new Model();
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $numeroNota = $data['numeroNota'] ?? '';
            $garantias = $garantiaModel->getGarantiaByNota($numeroNota);
            header('Content-Type: application/json');
            echo json_encode($garantias);
        } catch (Exception $e) {
            header("Content-Type: application/json");
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getUserProfile()
    {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Usuário não autenticado");
            }
            $userId = $_SESSION['user_id'];
            $userModel = new Model();
            $userData = $userModel->getUserById($userId);
            if (!$userData) {
                throw new Exception("Usuário não encontrado");
            }
            $responseData = [
                'name' => $userData['name'] ?? null,
                'role' => $userData['role'] ?? null,
                'email' => $userData['email'] ?? null,
                'phone' => $userData['phone'] ?? null,
                'cnpj' => $userData['cnpj'] ?? null,
                'created_at' => $userData['created_at'] ?? null,
                'orgao' => $userData['orgao'] ?? null
            ];
            echo json_encode([
                'success' => true,
                'data' => $responseData
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function updateUserProfile()
    {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Usuário não autenticado");
            }
            $userId = $_SESSION['user_id'];
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                throw new Exception("Dados inválidos");
            }
            $email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $phone = preg_replace('/[^0-9]/', '', $input['phone'] ?? '');
            $cnpj = preg_replace('/[^0-9]/', '', $input['cnpj'] ?? '');
            $orgao = filter_var($input['orgao'] ?? '', FILTER_SANITIZE_STRING);
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido");
            }
            if (empty($orgao)) {
                throw new Exception("Órgão é obrigatório");
            }
            if (!empty($cnpj) && strlen($cnpj) != 14) {
                throw new Exception("CNPJ deve conter 14 dígitos");
            }
            $userModel = new Model();
            $result = $userModel->updateProfile($userId, $email, $phone, $cnpj, $orgao);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_cnpj'] = $cnpj;
            $_SESSION['user_orgao'] = $orgao;
            echo json_encode([
                'success' => true,
                'message' => 'Perfil atualizado com sucesso',
                'data' => [
                    'email' => $email,
                    'phone' => $phone,
                    'cnpj' => $cnpj,
                    'orgao' => $orgao
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function uploadGarantiasCSV()
    {
        if (headers_sent()) {
            error_log("Headers já enviados!");
        }
        ob_clean();
        header('Content-Type: application/json');
        try {
            if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Erro no envio do arquivo. Código: ' . ($_FILES['csvFile']['error'] ?? 'arquivo não enviado'));
            }
            $csvFile = $_FILES['csvFile']['tmp_name'];
            if (!file_exists($csvFile)) {
                throw new Exception('Arquivo temporário não encontrado');
            }
            $handle = fopen($csvFile, 'r');
            if ($handle === false) {
                throw new Exception('Erro ao abrir o arquivo CSV');
            }
            fgetcsv($handle, 0, ';');
            require_once $_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/models/Model.php";
            $model = new Model();
            $importados = 0;
            $erros = 0;
            $errosDetalhes = [];
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                if (count($data) >= 4) {
                    $cliente = trim($data[0]);
                    $sku = trim($data[1]);
                    $tempo = intval($data[2]);
                    $bateria = intval($data[3]);
                    $result = $model->inserirGarantia($cliente, $sku, $tempo, $bateria);
                    if ($result === true) {
                        $importados++;
                    } else {
                        $erros++;
                        $errosDetalhes[] = [
                            'linha' => $data,
                            'erro' => $result
                        ];
                    }
                }
            }
            fclose($handle);
            $response = [
                'success' => $importados > 0,
                'message' => $importados > 0
                    ? "Dados importados com sucesso! ($importados registros)"
                    : "Nenhum registro foi importado.",
                'importados' => $importados,
                'erros' => $erros,
                'detalhes' => $errosDetalhes ?? []
            ];
            echo json_encode($response);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function uploadSerialCSV()
    {
        header('Content-Type: application/json');
        try {
            if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Erro no envio do arquivo. Código: ' . ($_FILES['csvFile']['error'] ?? 'arquivo não enviado'));
            }
            $csvFile = $_FILES['csvFile']['tmp_name'];
            if (!file_exists($csvFile)) {
                throw new Exception('Arquivo temporário não encontrado');
            }
            $handle = fopen($csvFile, 'r');
            if ($handle === false) {
                throw new Exception('Erro ao abrir o arquivo CSV');
            }
            $header = fgetcsv($handle, 0, ';');
            $required = ['NotaFiscal', 'DataFaturamento', 'Cliente', 'Serial', 'Imei', 'SKU'];
            foreach ($required as $col) {
                if (!in_array($col, $header)) {
                    throw new Exception("Coluna obrigatória ausente: $col");
                }
            }
            $importados = 0;
            $erros = 0;
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                if (count($data) >= 6) {
                    $row = array_combine($header, $data);
                    $nota = trim($row['NotaFiscal']);
                    $dataFat = trim($row['DataFaturamento']);
                    $cliente = trim($row['Cliente']);
                    $serial = trim($row['Serial']);
                    $imei = trim($row['Imei']);
                    $sku = trim($row['SKU']);
                    if ($this->model->inserirSerial($nota, $dataFat, $cliente, $serial, $imei, $sku)) {
                        $importados++;
                    } else {
                        $erros++;
                    }
                }
            }
            fclose($handle);
            $response = [
                'success' => true,
                'message' => "Dados importados com sucesso! ($importados registros)",
                'importados' => $importados,
                'erros' => $erros
            ];
            echo json_encode($response);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function deleteGarantia()
    {
        header('Content-Type: application/json');
        try {
            $sku = $_POST['sku'] ?? null;
            if (!$sku) {
                throw new Exception("SKU não fornecido");
            }
            $result = $this->model->deleteGarantia($sku);
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Registro deletado com sucesso'
                ]);
            } else {
                throw new Exception("Registro não encontrado ou não pôde ser deletado");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao deletar registro: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteSerial()
    {
        header('Content-Type: application/json');
        try {
            $sku = $_POST['sku'] ?? null;
            if (!$sku) {
                throw new Exception("SKU não fornecido");
            }
            $result = $this->model->deleteSerial($sku);
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Registro deletado com sucesso'
                ]);
            } else {
                throw new Exception("Registro não encontrado ou não pôde ser deletado");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao deletar registro: ' . $e->getMessage()
            ]);
        }
    }

    private function sendErrorResponse(int $statusCode, string $message): void
    {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode([
            "status" => "error",
            "message" => $message
        ]);
        exit;
    }
}
