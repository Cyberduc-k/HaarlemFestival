<?php

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;
use function _PhpScoperd8ff184be637\database_update;

try {
    require_once("libs/Mollie/initialize.php");
    require_once("services/PaymentService.php");

    $mollie = new MollieApiClient();
    $mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");


     // Retrieve the payment's current state.

    $payment = $mollie->payments->get($_POST["id"]);
    $orderId = $payment->metadata->order_id;


    // Update the order in the database.
    database_update($orderId, $payment->status);


    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        echo "paid";
    } elseif ($payment->isOpen()) {
        /*
         * The payment is open.
         */
        echo "open";
    } elseif ($payment->isPending()) {
        /*
         * The payment is pending.
         */
        http_send_status(200 );
        database_update($orderId, $payment->status);
    } elseif ($payment->isFailed()) {
        /*
         * The payment has failed.
         */
        echo "failed";
    } elseif ($payment->isExpired()) {
        /*
         * The payment is expired.
         */
        echo "expired";
    } elseif ($payment->isCanceled()) {
        /*
         * The payment has been canceled.
         */
        echo "canceled";
    } elseif ($payment->hasRefunds()) {
        /*
         * The payment has been (partially) refunded.
         * The status of the payment is still "paid"
         */
        echo "refunds";
    } elseif ($payment->hasChargebacks()) {
        /*
         * The payment has been (partially) charged back.
         * The status of the payment is still "paid"
         */

        echo "chargeback";
    }
} catch (ApiException $e) {
    echo "API call failed: " . \htmlspecialchars($e->getMessage());
}
