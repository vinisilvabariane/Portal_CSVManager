<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/controllers/Controller.php";

$controller = new Controller();
$action = isset($_GET["action"]) ? $_GET["action"] : "";

switch ($action) {
    case "login":
        $controller->login();
        break;
    case "createUser";
        $controller->createUser();
        break;
    case "getSerial";
        $controller->getSerial();
        break;
    case "getGarantia";
        $controller->getGarantia();
        break;
    case "getGarantiaByNota";
        $controller->getGarantiaByNota();
        break;
    case "consultGarantia";
        $controller->consultGarantia();
        break;
    case "getUserProfile";
        $controller->getUserProfile();
        break;
    case "updateUserProfile";
        $controller->updateUserProfile();
        break;
    default:
        header("Content-Type: application/json");
        http_response_code(405);
        break;
}
