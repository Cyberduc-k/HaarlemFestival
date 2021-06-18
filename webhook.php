<?php
use Mollie\Api\Exceptions\ApiException;

require_once ("libs/Mollie/initialize.php");
require_once ("services/InvoiceService.php");
require_once ("services/TicketService.php");
require_once ("services/PaymentService.php");
require_once ("services/invoicePdf.php");
require_once ("ticketPdf.php");
require_once ("models/Invoice.php");

try {
    $ps = new PaymentService();
    $us = new UserService();
    $is = new InvoiceService();
    $ts = new TicketService();
    $mailer = MailService::getInstance();

    $payment = $mollie->payments->get($_POST['id']);
    $orderId = $payment->metadata->order_id;
    $status = $payment->status;

    $userId = $ps->getUserId($orderId);
    $user = $us->getById($userId);
    $tickets = $ts->getAllForCart($userId);

    database_write($orderId, $status);


    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        // generate the tickets
        $ticketPdf = generateTickets($user);

        // set invoice details
        $invoice = new Invoice();
        $invoice->setUserId($userId);
        $invoice->setUserAddress("");
        $invoice->setUserPhone("");
        $invoice->setTax(0.21);
        $invoice->setDate(new DateTime());
        $invoice->setDueDate((new DateTime())->add(new DateInterval("P14D")));

        // create invoice
        $is->create($invoice);
        // move contents of the cart to the invoice table
        $ts->moveCartToInvoice($userId, $invoice->getId());
        // generate invoice PDF from moved items
        $invoicePdf = generateInvoice($invoice);

        // remove tickets from stock
        foreach ($tickets as $twc){
            $ticket = $twc->ticket;
            $amount = $twc->count;

            $ts->removeFromStock($ticket->getId(), $amount);
        }

        // send mail thanking customer containing the tickets and invoice as attachment
        $subject = "Thank you for your order";
        $body = "Your order has been succesfully placed. \n\nAttached to this e-mail are your invoice and tickets.";

        $mailer->sendMailWithInvoice($user->getEmail(), $subject, $body, $ticketPdf, $invoicePdf);
        return http_response_code(200);
    } elseif ($payment->isOpen()) {
        $subject = "Your order is still open";
        $body = "Your order with order number " . $orderId . " is currently still open \n Please try placing a new order.";
        return http_response_code(200);
    } elseif ($payment->isPending()) {
        //inform the user about the pending order
        $subject = "Your order is still pending";
        $body = "Your order with order number " . $orderId . " is being processed. \nWe will notify you when the order has succeeded.";

        $mailer->sendMail($user->getEmail(), $subject, $body);
        return http_response_code(200);
    } elseif ($payment->isFailed()) {
        // inform the user about the failed order
        $subject = "Your order has failed";
        $body = "Your order with " . $orderId . " has failed to be completed";

        $mailer->sendMail($user->getEmail(), $subject, $body);
        return http_response_code(200);
    } elseif ($payment->isExpired()) {
        // inform the user about the expired order
        $subject = "Your order has expired";
        $body = "The order with order number ". $orderId ." you placed for your Haarlem Festival tickets has expired.";

        $mailer->sendMail($user->getEmail(), $subject, $body);
        return http_response_code(200);
    } elseif ($payment->isCanceled()) {
        $subject = "Your order has been canceled";
        $body = "Your order for your Haarlem Festival tickets has been canceled";

        $mailer->sendMail($user->getEmail(), $subject, $body);
        // delete the order
        $ps->deleteOrder($orderId);
        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            $ticketId = $ticket->getId();

            // clear the cart
            $ts->deleteFromCart($userId, $ticketId);
        }
        return http_response_code(200);
    } elseif ($payment->hasRefunds()) {
        // inform the user about the refunded order
        $subject = "Your order has been refunded";
        $body = "Your order " . $orderId . "Has succesfully been refunded";

        $mailer->sendMail($user->getEmail(), $subject, $body);
        return http_response_code(200);
    } elseif ($payment->hasChargebacks()) {
        $subject = "Your order with order has been charged back";
        $body = "Your order with order number " . $orderId . "has been charged back by your card issuer.";

        $mailer->sendMail($user->getEmail(), $subject, $body);
        return http_response_code(200);
    }
} catch (ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
?>

