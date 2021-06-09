<html lang="en">
<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/account.css">
    <link rel="stylesheet" type="text/css" href="/css/tickets.css">
</head>
<body>
<?php require __DIR__ . '/menubar.php'; //TODO: CSS?>
<main>
    <article class="content">
        Thank you for your order.<br>
        Here is an overview of your ordered items:

        <?php
        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            $start = $ts->getStartDate($ticket);
            $startDate = $start->format('d-m-Y H:i');
            $name = $ts->getDescription($ticket);
            $location = $ts->getLocation($ticket);
            $price = $ticket->getPrice();
            $amount = $twc->count;
            ?>
            <div class="ticket">
                <span class="name"><?= $name ?></span>
                <span class="location"><?= $location ?></span>
                <span class="time"><?= $startDate ?> </span>
                <span class="numOfTickets"><?= $amount ?> </span>
                <span class="price">€<?= $price ?></span>
            </div>
        <?php } ?>

        <button onclick="window.location.href='/account'">Return to my account</button>
    </article>
</main>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>

<?php
use Mollie\Api\Exceptions\ApiException;
require_once ("libs/Mollie/initialize.php");
require_once ("libs/Mollie/src/MollieApiClient.php");
require_once ("services/PaymentService.php");
require_once ("services/MailService.php");
try {

    $mollie = new Mollie\Api\MollieApiClient();
    $mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");

    $ps = new PaymentService();
    $us = new UserService();
    $is = new InvoiceService();

    $user = $us->getById($_SESSION['userId']);

    $paymentId = $ps->getPaymentId($_GET['order_id']);

    $payment =$mollie->payments->get($paymentId);
    $orderId = $payment->metadata->order_id;
    $status = $payment->status;


    if ($payment->isPaid() && ! $payment->hasRefunds() && ! $payment->hasChargebacks()) {
        $subject = "Thank you for your order";
        $body = "test for now";
        $mailer = MailService::getInstance();
        $mailer->sendMail($user->getEmail(), $subject, $body);
        database_write($orderId, $paymentId, $status);
    } elseif ($payment->isOpen()) {
        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->isPending()) {
        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->isFailed()) {
        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->isExpired()) {
        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->isCanceled()) {
        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->hasRefunds()) {
        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    } elseif ($payment->hasChargebacks()) {
        database_write($orderId, $paymentId, $status);
        return http_response_code(200);
    }
} catch (ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
?>


