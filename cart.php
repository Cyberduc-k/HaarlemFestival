<?php
if (!isset($_SESSION)) session_start();
require_once("services/TicketService.php");


if(isset($_GET["userId"])) {
    $ts = new TicketService();
    $tickets = $ts->getAllForUser(9);

    $amount = 0;

    for ($i = 0; $i <= count($tickets) - 1; $i++) {
        $ticket = $tickets[$i]->ticket;
        $num = $tickets[$i]->count;
        $amount += $ticket->getPrice();
    }

    if (isset($_POST['agreeButton'])) {
        require_once("services/PaymentService.php");
        $ps = new PaymentService();
        $ps->createPayment($_GET['userId'], $amount,);
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

<?php require_once("menubar.php"); ?>

<article id="tickets">
</article>
<section>
</section>

<script>
    const section = document.getElementById("tickets");
</script>


<form id="agreement" method="post" action="cart.php">
    <button name="agreeButton" type="submit"> proceed to payment </button>
</form>

</body>
</html>