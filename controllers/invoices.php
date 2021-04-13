<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/InvoiceService.php';
require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/UserTypes.php';

if (!isset($_SESSION['userType'])) {
    header("Location: /login");
    exit;
}

if ($_SESSION['userType'] == UserTypes::CLIENT) {
    echo "<h1>You do not have permission to access this page";
    exit;
}

$is = new InvoiceService();
$us = new UserService();
$invoices = $is->getAll();

require __DIR__.'/../views/invoices.php';

?>
