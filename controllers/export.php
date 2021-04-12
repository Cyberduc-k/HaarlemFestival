<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../models/UserTypes.php';

if (!isset($_SESSION['userType'])) {
    header("Location: /login");
    exit;
}

if ($_SESSION['userType'] != UserTypes::SUPERADMIN) {
    echo "<h1>You do not have permission to access this page";
    exit;
}

require __DIR__.'/../views/export.php';

?>
