<?php

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;

//if(!isset($_SESSION)) session_start();
//
//if (!isset($_SESSION['userId'])) {
//    header("Location: /login");
//    exit;
//}
//
//if(!isset($_POST['id'])){
//    exit;
//}

require_once __DIR__.'/../libs/Mollie/functions.php';
require_once __DIR__.'/../services/InvoiceService.php';
require_once __DIR__.'/../services/TicketService.php';
require_once __DIR__.'/../models/Invoice.php';
require_once __DIR__.'/../libs/Mollie/functions.php';




require __DIR__.'/../webhook.php';