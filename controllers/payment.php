<?php

if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: /login");
    exit;
}

if (!isset($_GET['order_id'])) {
    echo '<h1>Oops. Something went wrong</h1>';
    exit;
}

require_once __DIR__.'/../services/InvoiceService.php';
require_once __DIR__.'/../services/TicketService.php';
require_once __DIR__.'/../models/Invoice.php';
require_once __DIR__.'/../libs/Mollie/functions.php';

$ps = new PaymentService();
$ts = new TicketService();
$tickets = $ts->getAllForOverview($_SESSION['userId']);
$orderId = $_GET['order_id'];
$invoiceService = new InvoiceService();


require __DIR__.'/../views/payment.php';

?>
