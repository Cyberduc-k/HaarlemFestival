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
    $paymentService->updateCartStatus($orderId);


    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        $ticketService->updateTicketAmount($orderId);
    } elseif ($payment->isOpen()) {
         echo "open";
    } elseif ($payment->isPending()) {
        http_send_status(200 );
        database_update($orderId, $payment->status);
    } elseif ($payment->isFailed()) {
        echo "failed";
    } elseif ($payment->isExpired()) {
        echo "expired";
    } elseif ($payment->isCanceled()) {
        echo "canceled";
    } elseif ($payment->hasRefunds()) {
        echo "refunds";
    } elseif ($payment->hasChargebacks()) {
        echo "chargeback";
    }
} catch (ApiException $e) {
    echo "API call failed: " . \htmlspecialchars($e->getMessage());
}
