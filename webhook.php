<?php
use Mollie\Api\Exceptions\ApiException;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

require_once ("libs/Mollie/initialize.php");
require_once ("services/InvoiceService.php");
require_once ("services/TicketService.php");
require_once ("services/PaymentService.php");
require_once ("services/invoicePdf.php");
require_once ("models/Invoice.php");

try {
    $ps = new PaymentService();
    $us = new UserService();
    $is = new InvoiceService();
    $mailer = MailService::getInstance();

    $payment = $mollie->payments->get($_POST['id']);
    $orderId = $payment->metadata->order_id;
    $status = $payment->status;
    $userId = $ps->getUserId($orderId);
    $user = $us->getById($userId);
    $invoice = $is->getForOrder($userId, $orderId);


    database_write($orderId, $status);


    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        $subject = "Thank you for your order";
        $body = "test";
        $pdf = generateInvoice($invoice);
        $mailer->sendMailWithInvoice($user->getEmail(), $subject, $body, $pdf);
    } elseif ($payment->isOpen()) {
        return http_response_code(200);
    } elseif ($payment->isPending()) {
        return http_response_code(200);
    } elseif ($payment->isFailed()) {
        return http_response_code(200);
    } elseif ($payment->isExpired()) {
        $subject = "Your order " . $orderId . " has expired";
        $body = "The order you placed for your Haarlem Festival tickets has expired.";
        return http_response_code(200);
    } elseif ($payment->isCanceled()) {
        $subject = "Your order " . $orderId . " has been canceled";
        $body = "Your order for your Haarlem Festival tickets has been canceled";
        return http_response_code(200);
    } elseif ($payment->hasRefunds()) {
        $subject = "Your order " . $orderId . "has been refunded";
        $body = "Your order " . $orderId . "Has succesfully been refunded";
        return http_response_code(200);
    } elseif ($payment->hasChargebacks()) {
        return http_response_code(200);
    }
} catch (ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
?>

