<?php
namespace _PhpScoperd8ff184be637;

use PaymentService;

require_once(__DIR__ . "/../Mollie/vendor/autoload.php");
require_once(__DIR__ . "/../../services/PaymentService.php");


function database_read($orderId): string
{
    $service = new PaymentService();
    $status = $service->getStatusByOrderId($orderId);

    return $status ? $status : "unknown order";
}

function database_write(int $userId, float $amount): bool
{
    $service = new PaymentService();
    return $service->createPayment($userId, $amount);

}

function database_update($orderId, $status): bool
{
    $service = new PaymentService();
    return $service->updatePaymentStatus($orderId, $status);
}

