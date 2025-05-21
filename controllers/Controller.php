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
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Usuário não autenticado");
            }
            $userId = $_SESSION['user_id'];
            $userModel = new Model();
            $userData = $userModel->getUserById($userId);
            if (!$userData) {
                throw new Exception("Usuário não encontrado");
            }
            echo json_encode([
                'success' => true,
                'data' => $userData
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
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
