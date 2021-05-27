<?php

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;
use function _PhpScoperd8ff184be637\database_update;

try {
    require_once("libs/Mollie/initialize.php");
    require_once("services/PaymentService.php");
    require_once("services/TicketService.php");

    $mollie = new MollieApiClient();
    $mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");

    $paymentService = new PaymentService();
    $ticketService = new TicketService();

    // Retrieve the payment's current state.
    $payment = $mollie->payments->get($_POST["id"]);
    $orderId = $payment->metadata->order_id;

    // Update the order in the database.
    database_update($orderId, $payment->status);


    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        $paymentService->updatePaymentStatus($orderId, $payment->status);
        http_send_status(200);
        database_update($orderId, $payment->status);
    } elseif ($payment->isOpen()) {
        echo "open";
    } elseif ($payment->isPending()) {
        $paymentService->updatePaymentStatus($orderId, $payment->status);
        http_send_status(201);
        database_update($orderId, $payment->status);
    } elseif ($payment->isFailed()) {
        $paymentService->updatePaymentStatus($orderId, $payment->status);
        http_send_status(201);
        database_update($orderId, $payment->status);
    } elseif ($payment->isExpired()) {
        $paymentService->updatePaymentStatus($orderId, $payment->status);
        http_send_status(201);
        database_update($orderId, $payment->status);
    } elseif ($payment->isCanceled()) {
        $paymentService->updatePaymentStatus($orderId, $payment->status);
        http_send_status(200);
        database_update($orderId, $payment->status);
    } elseif ($payment->hasRefunds()) {
        $paymentService->updatePaymentStatus($orderId, $payment->status);
        http_send_status(201);
        database_update($orderId, $payment->status);
    } elseif ($payment->hasChargebacks()) {
        $paymentService->updatePaymentStatus($orderId, $payment->status);
        http_send_status(201);
        database_update($orderId, $payment->status);
    }
} catch (ApiException $e) {
    echo "API call failed: " . \htmlspecialchars($e->getMessage());
}
