<?php
session_start();

// Se não existir, cria dados de teste
if (!isset($_SESSION['teste'])) {
    $_SESSION['teste'] = [
        'user_id' => 123,
        'user_role' => 'admin',
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo '<p style="color: green;">Dados de teste adicionados à sessão!</p>';
}

// Mostra conteúdo
echo '<h3>Conteúdo da Sessão:</h3>';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// Opção para destruir
if (isset($_GET['destroy'])) {
    session_destroy();
    header('Location: teste_sessao.php');
    exit;
}
?>

<a href="teste_sessao.php?destroy=1">Destruir Sessão</a>