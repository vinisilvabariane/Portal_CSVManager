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
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
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
        // Gera o hash seguro da senha
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username,orgao, email, phone, cnpj, password, role, created_at) 
              VALUES (:username, :orgao, :email, :phone, :cnpj, :password, :role, NOW())";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':username' => $data['username'],
            ':orgao' => $data['orgao'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?? null,
            ':cnpj' => $data['cnpj'] ?? null,
            ':password' => $hashedPassword,
            ':role' => $data['role'] ?? 'membro'
        ]);
    }

    //CONSULTA DA TABELA DE SERIAL
    public function getSerial()
    {
        $query = "SELECT 
                    s.NotaFiscal, 
                    s.DataFaturamento, 
                    s.Cliente, 
                    s.Serial, 
                    s.Imei, 
                    s.SKU, 
                    g.TempoGarantiaMeses,
                    DATE_ADD(s.DataFaturamento, INTERVAL g.TempoGarantiaMeses MONTH) AS DataFinalGarantia
                  FROM tabela_seriais s
                  INNER JOIN tabela_garantia g ON s.SKU = g.SKU
                  ORDER BY s.DataFaturamento DESC";
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
            $garantia['DataFinalGarantia'] = date('Y-m-d', strtotime('+' . $garantia['TempoGarantiaMeses'] . ' months'));
        }
        return $garantias;
    }

    //FUNÇÃO PARA CALCULAR O STATUS DA GARANTIA
    private function calculateStatus($dataFinal)
    {
        $today = new DateTime();
        $finalDate = new DateTime($dataFinal);
        $interval = $today->diff($finalDate);
        if ($finalDate < $today) {
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
                    s.NotaFiscal,
                    s.DataFaturamento,
                    s.Cliente,
                    s.Serial,
                    s.Imei,
                    s.SKU,
                    g.TempoGarantiaMeses,
                    DATE_ADD(s.DataFaturamento, INTERVAL g.TempoGarantiaMeses MONTH) AS DataFinalGarantiaCalculada,
                    DATEDIFF(DATE_ADD(s.DataFaturamento, INTERVAL g.TempoGarantiaMeses MONTH), CURDATE()) AS DiasRestantes
                FROM tabela_seriais s
                INNER JOIN tabela_garantia g ON s.SKU = g.SKU
                WHERE s.Serial = :serial
            ");
            $stmt->bindParam(':serial', $serialNumber);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $result['DataFaturamento'] = date('d/m/Y', strtotime($result['DataFaturamento']));
                $result['DataFinalGarantia'] = date('d/m/Y', strtotime($result['DataFinalGarantiaCalculada']));
                $dataFinal = DateTime::createFromFormat('d/m/Y', $result['DataFinalGarantia']);
                $hoje = new DateTime();
                $result['SituacaoGarantia'] = ($dataFinal && $dataFinal >= $hoje) ? 'Dentro da Garantia' : 'Fora da Garantia';
                unset($result['DataFinalGarantiaCalculada']);
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
                s.NotaFiscal,
                s.DataFaturamento,
                s.Cliente,
                s.Serial,
                s.Imei,
                s.SKU,
                g.TempoGarantiaMeses,
                DATE_ADD(s.DataFaturamento, INTERVAL g.TempoGarantiaMeses MONTH) AS DataFinalGarantiaCalculada,
                DATEDIFF(DATE_ADD(s.DataFaturamento, INTERVAL g.TempoGarantiaMeses MONTH), CURDATE()) AS DiasRestantes
            FROM tabela_seriais s
            INNER JOIN tabela_garantia g ON s.SKU = g.SKU
            WHERE s.NotaFiscal = :nota
        ");
            $stmt->bindParam(':nota', $numeroNota);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                foreach ($results as &$result) {
                    $result['DataFaturamento'] = date('d/m/Y', strtotime($result['DataFaturamento']));
                    $result['DataFinalGarantia'] = date('d/m/Y', strtotime($result['DataFinalGarantiaCalculada']));
                    $dataFinal = DateTime::createFromFormat('d/m/Y', $result['DataFinalGarantia']);
                    $hoje = new DateTime();
                    $result['SituacaoGarantia'] = ($dataFinal && $dataFinal >= $hoje) ? 'Dentro da Garantia' : 'Fora da Garantia';
                    unset($result['DataFinalGarantiaCalculada']);
                }
                return ['success' => true, 'data' => $results];
            }
            return ['success' => false, 'message' => 'Nenhum produto encontrado para esta nota fiscal'];
        } catch (PDOException $e) {
            throw new Exception("Erro na consulta: " . $e->getMessage());
        }
    }

    //FUNÇÃO DE UPDATE DO PERFIL
    public function updateProfile($userId, $email, $phone, $cnpj, $orgao)
    {
        try {
            if (!is_numeric($userId) || $userId <= 0) {
                throw new Exception("ID de usuário inválido");
            }
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $userId]);
            if ($stmt->rowCount() > 0) {
                throw new Exception("Este email já está em uso por outro usuário");
            }
            if (!empty($cnpj)) {
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE cnpj = ? AND id != ?");
                $stmt->execute([$cnpj, $userId]);
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Este CNPJ já está em uso por outro usuário");
                }
            }
            $query = "UPDATE users SET 
                email = :email, 
                phone = :phone, 
                cnpj = :cnpj, 
                orgao = :orgao, 
                created_at = NOW() 
                WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':email' => $email,
                ':phone' => $phone,
                ':cnpj' => $cnpj,
                ':orgao' => $orgao,
                ':id' => $userId
            ]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum dado foi alterado - verifique se os dados são diferentes dos atuais");
            }
            return ['success' => true, 'message' => 'Perfil atualizado com sucesso'];
        } catch (PDOException $e) {
            error_log("Erro no Model ao atualizar perfil: " . $e->getMessage());
            throw new Exception("Erro ao atualizar perfil: " . $e->getMessage());
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

    //FUNÇÃO PARA INSERIR O CSV DE GARANTIAS
    public function inserirGarantia($cliente, $sku, $tempoGarantia, $bateria)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tabela_garantia 
            (Cliente, SKU, TempoGarantiaMeses, Bateria)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                TempoGarantiaMeses = VALUES(TempoGarantiaMeses), 
                Bateria = VALUES(Bateria)");
            $success = $stmt->execute([
                $cliente,
                $sku,
                $tempoGarantia,
                $bateria
            ]);
            if (!$success) {
                return $stmt->errorInfo();
            }
            return $success;
        } catch (PDOException $e) {
            error_log("Erro ao inserir garantia: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    //FUNÇÃO PARA INSERIR O CSV DE SERIAIS
    public function inserirSerial($notaFiscal, $dataFaturamento, $cliente, $serial, $imei, $sku)
    {
        try {
            $sql = "INSERT INTO tabela_seriais 
            (NotaFiscal, DataFaturamento, Cliente, Serial, Imei, SKU)
            VALUES (:notaFiscal, :dataFaturamento, :cliente, :serial, :imei, :sku)
            ON DUPLICATE KEY UPDATE 
                DataFaturamento = VALUES(DataFaturamento),
                Cliente = VALUES(Cliente),
                Imei = VALUES(Imei),
                SKU = VALUES(SKU)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':notaFiscal', $notaFiscal);
            $stmt->bindParam(':dataFaturamento', $dataFaturamento);
            $stmt->bindParam(':cliente', $cliente);
            $stmt->bindParam(':serial', $serial);
            $stmt->bindParam(':imei', $imei);
            $stmt->bindParam(':sku', $sku);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao inserir serial: " . $e->getMessage());
            return false;
        }
    }

    public function deleteGarantia($sku)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM tabela_garantia WHERE SKU = :sku");
            $sku = htmlspecialchars(strip_tags($sku));
            $stmt->bindParam(":sku", $sku);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao deletar garantia: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSerial($sku)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM tabela_seriais WHERE SKU = :sku");
            $sku = htmlspecialchars(strip_tags($sku));
            $stmt->bindParam(":sku", $sku);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao deletar serial: " . $e->getMessage());
            return false;
        }
    }
}
