<html lang="en">
<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/account.css">
    <link rel="stylesheet" type="text/css" href="/css/tickets.css">
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main>
        <article class="content">
            Thank you for your order.<br>
            The current status of your order is: <?php echo $status; ?>
        </article>

        <article class="content">
            <?php
                foreach ($tickets as $twc) {
                    $ticket = $twc->ticket;
                    $start = $ts->getStartDate($ticket);
                    $startDate = $start->format('d-m-Y H:i');
                    $name = $ts->getDescription($ticket);
                    $location = $ts->getLocation($ticket);
                    $amount = $twc->count;
            ?>
                <div class="ticket">
                    <span class="name"><?= $name ?></span>
                    <span class="location"><?= $location ?></span>
                    <span class="time"><?= $startDate ?> </span>
                    <span class="numOfTickets"><?=$amount?> </span>
                </div>
            <?php } ?>

            <a href="/account">Return to my account"</a>
        </article>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
