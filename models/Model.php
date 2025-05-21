<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/Connection.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/PDOExceptionHandler.php";
class Model
{
    private $pdo;
    private $pdoExceptionHandler;
    public function __construct()
    {
        $this->pdo = (new Connection())->getConnection();
        $this->pdoExceptionHandler = new PDOExceptionHandler;
    }

    // LOGIN
    public function login(string $username, string $password): array|false
    {
        try {
            $query = "SELECT * FROM users WHERE username = :username AND password = MD5(:password)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                unset($user['password']);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erro de login: " . $e->getMessage());
            throw new Exception("Erro ao verificar credenciais");
        }
    }

    //FUNÇÃO DE CRIAR OS USÁRIOS
    public function createUser(array $data): bool
    {
        if (empty($data['orgao']) || empty($data['email']) || empty($data['password'])) {
            throw new InvalidArgumentException("Dados incompletos para cadastro");
        }
        $checkEmail = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
        $checkEmail->execute([':email' => $data['email']]);
        if ($checkEmail->fetch()) {
            throw new PDOException("Email já cadastrado", 23000);
        }
        if (!empty($data['cnpj'])) {
            $checkcnpj = $this->pdo->prepare("SELECT id FROM users WHERE cnpj = :cnpj");
            $checkcnpj->execute([':cnpj' => $data['cnpj']]);
            if ($checkcnpj->fetch()) {
                throw new PDOException("cnpj já cadastrado", 23000);
            }
        }
        $query = "INSERT INTO users (username,orgao, email, phone, cnpj, password, role, created_at) 
              VALUES (:username, :orgao, :email, :phone, :cnpj, :password, :role, NOW())";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':username' => $data['username'],
            ':orgao' => $data['orgao'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':cnpj' => $data['cnpj'] ?? null,
            ':password' => $data['password'],
            ':role' => $data['role'] ?? 'membro'
        ]);
    }

    //CONSULTA DA TABELA DE SERIAL
    public function getSerial()
    {
        $query = "SELECT 
                    NotaFiscal, 
                    DataFaturamento, 
                    Cliente, 
                    Serial, 
                    Imei, 
                    SKU, 
                    DataFinalGarantia, 
                    Status 
                  FROM tabela_seriais
                  ORDER BY DataFaturamento DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $seriais = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($seriais as &$garantia) {
            if (!isset($garantia['Status'])) {
                $garantia['Status'] = $this->calculateStatus($garantia['DataFinalGarantia']);
            }
        }
        return $seriais;
    }

    //CONSULTA DA TABELA DE GARANTIA
    public function getGarantia()
    {
        $query = "SELECT 
                Cliente, 
                SKU, 
                TempoGarantiaMeses, 
                Bateria 
              FROM tabela_garantia
              ORDER BY Cliente";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $garantias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($garantias as &$garantia) {
            $garantia['NotaFiscal'] = 'N/A';
            $garantia['DataFaturamento'] = 'N/A';
            $garantia['Serial'] = 'N/A';
            $garantia['Imei'] = 'N/A';
            $garantia['DataFinalGarantia'] = date('Y-m-d', strtotime('+' . $garantia['TempoGarantiaMeses'] . ' months'));
            $garantia['Status'] = $this->calculateStatus($garantia['DataFinalGarantia']);
        }
        return $garantias;
    }

    //FUNÇÃO PARA CALCULAR O STATUS DA GARANTIA
    private function calculateStatus($dataFinal)
    {
        $today = new DateTime();
        $finalDate = new DateTime($dataFinal);
        $interval = $today->diff($finalDate);
        if ($finalDate > $today) {
            return 'Expirada';
        } elseif ($interval->days <= 30) {
            return 'Próximo do fim';
        } else {
            return 'Ativa';
        }
    }

    //FUNÇÃO PARA CONSULTAR A GRANTIA EM RELAÇÃO AO SERIAL NUMBER
    public function consultGarantia($serialNumber)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    NotaFiscal,
                    DataFaturamento,
                    Cliente,
                    Serial,
                    Imei,
                    SKU,
                    DataFinalGarantia,
                    Status,
                    CASE 
                        WHEN DataFinalGarantia >= CURDATE() THEN 'Dentro da Garantia'
                        ELSE 'Fora da Garantia'
                    END AS SituacaoGarantia,
                    DATEDIFF(DataFinalGarantia, CURDATE()) AS DiasRestantes
                FROM tabela_seriais 
                WHERE Serial = :serial
            ");
            $stmt->bindParam(':serial', $serialNumber);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $result['DataFaturamento'] = date('d/m/Y', strtotime($result['DataFaturamento']));
                $result['DataFinalGarantia'] = date('d/m/Y', strtotime($result['DataFinalGarantia']));
                return $result;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro na consulta: " . $e->getMessage());
        }
    }

    public function getGarantiaByNota($numeroNota)
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT 
                NotaFiscal,
                DataFaturamento,
                Cliente,
                Serial,
                Imei,
                SKU,
                DataFinalGarantia,
                Status,
                CASE 
                    WHEN DataFinalGarantia >= CURDATE() THEN 'Dentro da Garantia'
                    ELSE 'Fora da Garantia'
                END AS SituacaoGarantia,
                DATEDIFF(DataFinalGarantia, CURDATE()) AS DiasRestantes
            FROM tabela_seriais 
            WHERE NotaFiscal = :nota
        ");
            $stmt->bindParam(':nota', $numeroNota);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                foreach ($results as &$result) {
                    $result['DataFaturamento'] = date('d/m/Y', strtotime($result['DataFaturamento']));
                    $result['DataFinalGarantia'] = date('d/m/Y', strtotime($result['DataFinalGarantia']));
                }
                return ['success' => true, 'data' => $results];
            }
            return ['success' => false, 'message' => 'Nenhum produto encontrado para esta nota fiscal'];
        } catch (PDOException $e) {
            throw new Exception("Erro na consulta: " . $e->getMessage());
        }
    }

    //FUNÇÃO DE COSULTA DE USUÁRIO POR ID
    public function getUserById($userId)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
