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

function database_write(string $orderId, string $paymentId, string $status, string $userId): bool
{
    $service = new PaymentService();
    return $service->updatePaymentStatus($orderId, $paymentId, $status, $userId);

}

//function database_update($orderId, $status): bool
//{
//    $service = new PaymentService();
//    return $service->updatePaymentStatus($orderId, $status);
//}

