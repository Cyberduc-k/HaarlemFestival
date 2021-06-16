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
$tickets = $ts->getAllForCart($_SESSION['userId']);
$orderId = $_GET['order_id'];
$invoiceService = new InvoiceService();

//$invoice = new Invoice();
//$invoice->setUserId($_SESSION["userId"]);
//$invoice->setUserAddress("");
//$invoice->setUserPhone("");
//$invoice->setTax(0.21);
//$invoice->setDate(new DateTime());
//$invoice->setDueDate((new DateTime())->add(new DateInterval("P14D")));
//
//$invoiceService->create($invoice);
//$ts->moveCartToInvoice($_SESSION['userId'], $invoice->getId());

require __DIR__.'/../views/payment.php';

?>
