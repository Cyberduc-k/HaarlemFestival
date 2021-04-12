<html lang="en">
<head>
    <title>Cart</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <link type="text/css" rel="stylesheet" href="/css/tickets.css"/>
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <main>
        <section id="tickets">
            <?php
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
            <?php } ?>

            <form id="agreement" method="post">
                <input id ="agreeButton" type="submit" name="agreeButton" value="Proceed to payment">
            </form>
        </section>
    </main>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
