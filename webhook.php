<?php
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;

require_once ("libs/Mollie/initialize.php");
require_once ("libs/Mollie/src/MollieApiClient.php");
require_once ("services/PaymentService.php");
require_once ("services/InvoiceService.php");
require_once ("services/TicketService.php");

try {

    $mollie = new MollieApiClient();
    $mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");


    $ps = new PaymentService();
    $us = new UserService();
    $is = new InvoiceService();

    var_dump($_POST);

//    $user = $us->getById($_SESSION['userId']);
    $payment = $mollie->payments->get($_POST['id']);
    $orderId = $payment->metadata->order_id;
    $status = $payment->status;
    $mailer = MailService::getInstance();


    database_write($orderId, $status);


    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        $subject = "Thank you for your order";
        $body = "test for now";
//        $mailer->sendMail($user->getEmail(), $subject, $body);
    } elseif ($payment->isOpen()) {
        return http_response_code(200);
    } elseif ($payment->isPending()) {
        return http_response_code(200);
    } elseif ($payment->isFailed()) {
        return http_response_code(200);
    } elseif ($payment->isExpired()) {
        $subject = "Your order " . $orderId . " has expired";
        $body = "The order you placed for your Haarlem Festival tickets has expired.";
//        $mailer->sendMail($user->getEmail(), $subject, $body);
        return http_response_code(200);
    } elseif ($payment->isCanceled()) {
        $subject = "Your order " . $orderId . " has been canceled";
        $body = "Your order for your Haarlem Festival tickets has been canceled";
//        $mailer->sendMail($user->getEmail(), $subject, $body);
//        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->hasRefunds()) {
        $subject = "Your order " . $orderId . "has been refunded";
        $body = "Your order " . $orderId . "Has succesfully been refunded";
//        $mailer->sendMail($user->getEmail(), $subject, $body);
//        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->hasChargebacks()) {
//        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    }
} catch (ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
?>

