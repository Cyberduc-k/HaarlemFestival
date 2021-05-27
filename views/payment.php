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
            Thank you for your order.<br>
            Here is an overview of your ordered items:
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
