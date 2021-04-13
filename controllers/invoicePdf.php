<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/InvoiceService.php';
require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../services/invoicePdf.php';
require_once __DIR__.'/../models/UserTypes.php';

if (!isset($_SESSION['userType'])) {
    header("Location: /login");
    exit;
}

if ($_SESSION['userType'] == UserTypes::CLIENT) {
    echo "<h1>You do not have permission to access this page";
    exit;
}

function run(int $id) {
    $is = new InvoiceService();
    $invoice = $is->getById($id);
    $pdf = generateInvoice($invoice);
    $pdf->Output('invoice.pdf', 'I');
}

?>
