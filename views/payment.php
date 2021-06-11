<html lang="en">
<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/payment.css">
    <link rel="stylesheet" type="text/css" href="/css/innerNav.css">
</head>
<body>
<?php require __DIR__ . '/menubar.php'; //TODO: CSS?>
<main>
    <?php if(count($tickets) >=1){ ?>
    <article id="tickets">
        Thank you for your order.<br>
        Here is an overview of your ordered items:

        <?php
        $total = 0;
        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            $start = $ts->getStartDate($ticket);
            $startDate = $start->format('d-m-Y H:i');
            $name = $ts->getDescription($ticket);
            $location = $ts->getLocation($ticket);
            $price = $ticket->getPrice();
            $amount = $twc->count;
            $ticketPrice = $amount * $price;
            $total += $ticketPrice;
            $exVAT = ($total / 121) * 100;
            $VAT = ($total - $exVAT);
            ?>
            <div class="ticket">
                <span class="name"><?= $name ?></span>
                <span class="location"><?= $location ?></span>
                <span class="time"><?= $startDate ?> </span>
                <span class="numOfTickets"><?= $amount ?> x </span>
                <span class="price">€<?= number_format($price, 2, ".", ",") ?> =</span>
                <span class="ticketPrice">€<?= number_format($ticketPrice, 2, ",", ".")?></span>
            </div>
        <?php } ?>
        <div class="amountcol">
            <ul class="price-amounts">
                <li>€<?= number_format($exVAT, 2, ".", ",") ?> </li>
                <li>€<?= number_format($VAT, 2, ".", ",") ?> </li>
                <br>
                <li>€<?= number_format($total, 2, ".", ","); ?></li>
            </ul>
        </div>

        <div class="priceCol">
            <ul class="pricetype">
                <li>Subtotal:</li>
                <li>VAT:</li>
                <br>
                <li>Total:</li>
            </ul>
        </div>
        <?php } ?>
        <div id="buttonContainer">
        <button onclick="window.location.href='/account'" id="returnButton" name="returnButton">Return to my account
        </button>
        </div>
    </article>
</main>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>




