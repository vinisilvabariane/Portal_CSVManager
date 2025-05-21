<?php
class SessionValidator
{
    public function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function sessionValidate()
    {
        $this->startSession();
        if (!isset($_SESSION["logado"])) $this->logout();
    }

    public function logout()
    {
        $this->startSession();

        // Resetar a sessão
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), "", 0, "/");

        if (session_status() === PHP_SESSION_ACTIVE) session_regenerate_id(true);

        // Definir cabeçalhos para evitar o cache e fazer o redirecionamento
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: /PortalMultiGarantia/views/login/");
        exit();
    }
}
