<?php
namespace _PhpScoperd8ff184be637;

use PaymentService;

require_once(__DIR__ . "/../Mollie/vendor/autoload.php");
require_once(__DIR__ . "/../../services/PaymentService.php");

/*
 * NOTE: The examples are using a text file as a database.
 * Please use a real database like MySQL in production code.
 */
function database_read($orderId): string
{
    $service = new PaymentService();
    $status = $service->getStatusByOrderId($orderId);

    return $status ? $status : "unknown order";
}

function database_write(): bool
{
    $service = new PaymentService();
    return $service->createPayment();

}

function database_update($orderId, $status): bool
{
    $service = new PaymentService();
    return $service->updatePaymentStatus($orderId, $status);
}
