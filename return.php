<?php
if(!isset($_SESSION)) session_start();

if(isset($_SESSION["userId"])) {

    require_once ("services/TicketService.php");
    require_once ("services/InvoiceService.php");
    require_once ("models/Invoice.php");
    require_once ("libs/Mollie/functions.php");

    $service = new PaymentService();
    $status = database_read($_GET["order_id"]);
    $ticketService = new TicketService();
    $ticket = $ticketService->getAllForCart($_SESSION['userId']);

} else {
    header("Location: ./");
}
?>
<html lang="en">
<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/account.css">
    <link rel="stylesheet" type="text/css" href="css/tickets.css">
</head>
<body>
<section>
    <article>
        <div>
Thank you for your order.
The current status of your order is:
            <?= $status ?>
        </div>
    </article>

        <article id="tickets">
            <?php
                $ticketService = new TicketService();
                $tickets = $ticketService->getAllForCart($_SESSION["userId"]);
                $invoiceService = new InvoiceService();
                $invoice = new Invoice();

                $invoice->setUserId($_SESSION["userId"]);
                $invoice->setUserAddress("");
                $invoice->setUserPhone("");
                $invoice->setTax(0.21);
                $invoice->setDate(new DateTime());
                $invoice->setDueDate((new DateTime())->add(new DateInterval("P14D")));
                
                $invoiceService->create($invoice);

                foreach ($tickets as $twc) {
                    $ticket = $twc->ticket;
                    $start = $ticketService->getStartDate($ticket);
                    $startDate = $start->format('d-m-Y H:i');
                    $name = $ticketService->getDescription($ticket);
                    $location = $ticketService->getLocation($ticket);
                    $amount = $twc->count;
                    ?>
                    <div class="ticket">
                        <span class="name"><?= $name ?></span>
                        <span class="location"><?= $location ?></span>
                        <span class="time"><?= $startDate ?> </span>
                        <span class="numOfTickets"><?=$amount?> </span>
                    </div>
                    <?php
                }
                
                $ticketService->moveCartToInvoice($_SESSION["userId"], $invoice->getId());
            ?>
        <form name="overview" action="account.php" method="post">
            <input id ="returnButton" type="submit" name="returnButton" value="return to my account">
        </form>

    </article>
</section>
</body>
</html>
