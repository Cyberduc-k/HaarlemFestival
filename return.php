<?php
if(!isset($_SESSION)) session_start();

if(isset($_SESSION["userId"])) {

    require_once ("services/TicketService.php");
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

            foreach ($tickets as $twc) {
                $ticket = $twc->ticket;
                $start = $ticketService->getStartDate($ticket);
                $startDate = $start->format('d-m-y H:i');
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
            ?>
        <form name="overview" action="account.php" method="post">
            <input id ="returnButton" type="submit" name="returnButton" value="return to my account">
        </form>

    </article>
</section>
</body>
</html>
