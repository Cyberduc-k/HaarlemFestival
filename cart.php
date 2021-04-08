<?php
if (!isset($_SESSION)) session_start();
require_once("services/TicketService.php");

if(isset($_SESSION["userId"])) {
$ts = new TicketService();
$userId = $_SESSION["userId"];

$tickets = $ts->getAllForCart($userId);
$ticketService = new TicketService();

$amount = 0;

foreach ($tickets as $twc) {
    $ticket = $twc->ticket;
    $price = $ticket->getPrice();
    $amount += $price;
}

if (isset($_POST['agreeButton'])) {
    require_once("services/PaymentService.php");
    $ps = new PaymentService();
    $ps->createPayment($userId, $amount,);
}
} else {
    header("Location: ./");
}
?>

<html lang="en">
<head>
    <title>Cart</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="css/tickets.css"/>
</head>
<body>
<section>

    <?php require_once("menubar.php"); ?>

    <article id="tickets">
        <?php
        $ticketService = new TicketService();
        $tickets = $ticketService->getAllForCart($userId);

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

        <form id="agreement" method="post" action="cart.php" onload="renderTickets(this)">
            <input id ="agreeButton" type="submit" name="agreeButton" value="Proceed to payment">
        </form>
    </article>
</section>
</body>
</html>