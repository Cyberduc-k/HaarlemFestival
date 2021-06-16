<?php
use Mollie\Api\MollieApiClient;
require_once __DIR__.'/../libs/Mollie/src/MollieApiClient.php';
require_once __DIR__.'/../libs/Mollie/functions.php';


$mollie = new MollieApiClient();
$mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");


require_once __DIR__.'/../services/InvoiceService.php';
require_once __DIR__.'/../services/TicketService.php';
require_once __DIR__.'/../models/Invoice.php';


//$ps = new PaymentService();
//$ts = new TicketService();
//$tickets = $ts->getAllForCart($_SESSION['userId']);
//$orderId = $_GET['order_id'];
//$invoiceService = new InvoiceService();

$invoice = new Invoice();
$invoice->setUserId($_SESSION["userId"]);
$invoice->setUserAddress("");
$invoice->setUserPhone("");
$invoice->setTax(0.21);
$invoice->setDate(new DateTime());
$invoice->setDueDate((new DateTime())->add(new DateInterval("P14D")));

$invoiceService->create($invoice);
$ts->moveCartToInvoice($_SESSION['userId'], $invoice->getId());

require __DIR__.'/../webhook.php';