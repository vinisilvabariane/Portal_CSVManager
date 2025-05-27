<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/controllers/Controller.php";

$controller = new Controller();
$action = isset($_GET["action"]) ? $_GET["action"] : "";

switch ($action) {
    // USER CASES
    case "login":
        $controller->login();
        break;
    case "createUser";
        $controller->createUser();
        break;
    case "getUserProfile";
        $controller->getUserProfile();
        break;
    case "updateUserProfile";
        $controller->updateUserProfile();
        break;
    // SERIAL CASES
    case "getSerial";
        $controller->getSerial();
        break;
    case "uploadSerialCSV";
        $controller->uploadSerialCSV();
        break;
    case "deleteSerial";
        $controller->deleteSerial();
        break;
    // GARANTIA CASES
    case "getGarantia";
        $controller->getGarantia();
        break;
    case "uploadGarantiasCSV";
        $controller->uploadGarantiasCSV();
        break;
    case "deleteGarantia";
        $controller->deleteGarantia();
        break;
    case "consultGarantia";
        $controller->consultGarantia();
        break;
    case "getGarantiaByNota";
        $controller->getGarantiaByNota();
        break;
    //DEFAULT CASE
    default:
        header("Content-Type: application/json");
        http_response_code(405);
        break;
}
