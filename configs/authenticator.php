<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/PortalMultiGarantia/configs/SessionValidator.php");
$sessionValidator = new SessionValidator();
$sessionValidator->sessionValidate();
