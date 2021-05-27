<html lang="en">
<head>
    <title>Cart</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <link type="text/css" rel="stylesheet" href="/css/tickets.css"/>
    <link type="text/css" rel="stylesheet" href="/css/innerNav.css"/>
</head>
<body>
<?php require __DIR__ . '/menubar.php'; ?>

<main>
    <nav>
        <ul>
            <li>
                <a href='/programme'>Programme</a>
            </li>
            <li>
                <a href='/user/edit'>Edit my information</a>
            </li>
            <li>
                <a href='/user/change_avatar'>Change Avatar</a>
            </li>
            <li class="active">
                <a href="/cart">Cart</a>
            </li>
        </ul>
    </nav>

    <section id="tickets">
        <?php
        $total = 0;
        foreach ($tickets as $twc) {
            $ticket = $twc->ticket;
            $start = $ticketService->getStartDate($ticket);
            $startDate = $start->format('d-m-y H:i');
            $name = $ticketService->getDescription($ticket);
            $location = $ticketService->getLocation($ticket);
            $amount = $twc->count;
            $price = $ticket->getPrice();
            $total += ($price * $amount);
            ?>

            <div class="ticket">
                <span class="name"><?= $name ?></span>
                <span class="location"><?= $location ?></span>
                <span class="time"><?= $startDate ?> </span>
                <?php if ($ticket->getEventType() != 1) { ?>
                    <span class="numOfTickets"><?= $amount ?> </span>
                <?php } ?>
                <span class="price">€<?= $price ?></span>

                <?php if ($ticket->getEventType() != 1) { ?>
                    <form id="changeTicketAmount" name="changeTicketAmount" method="post"
                          action="/controllers/cart.php?ticketId=<?= $ticket->getId() ?>&next=cart">
                        <input id="addTicketButton" type="submit" name="addTicketButton" value="+">
                        <input id="removeTicketButton" type="submit" name="removeTicketButton" value="-">
                    </form>
                <?php } ?>

                <form id="deleteTicketsFromCart" name="deleteTicketsFromCart" method="post"
                      action="/deleteFromCart.php?ticketId=<?= $ticket->getId() ?>&next=cart">
                    <label for="deleteTicketsButton"> </label>
                    <input id="deleteTicketsButton" name="deleteTicketsButton" value="Delete"
                           type="submit"
                           onclick="return confirm('You are about to delete your ticket(s) from this cart. Continue?')">
                </form>

            </div>
        <?php }
        if (count($tickets) >= 1) { ?>
            Total price: €<?= $total ?>
            <form id="agreement" method="post">
                <input id="agreeButton" type="submit" name="agreeButton" value="Proceed to payment">
            </form>
        <?php } else {
            unset($_SESSION['cartTicketsRemoved'])
            ?>
            <article class="content">
                It looks like your cart is empty.
            </article> <?php
        } ?>

        <div class="formField red">
            <p><?php if (isset($_SESSION['cartTicketsRemoved'])) {
                    echo $_SESSION['cartTicketsRemoved'];
                }
                unset($_SESSION['cartTicketsRemoved']); ?></p>
        </div>
    </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
