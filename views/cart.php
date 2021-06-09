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
            $price = number_format($ticket->getPrice(),2);
            $total += ($price * $amount);
            $exVAT = number_format((($total/121)*100), 2);
            $VAT = number_format(($total-$exVAT),2);
            ?>

            <div class="ticket">
                <span class="name"><?= $name ?></span>
                <span class="location"><?= $location ?></span>
                <span class="time"><?= $startDate ?> </span>
                <span class="numOfTickets">(<?= $amount ?>x)</span>
                <span class="price">€<?= $price ?></span>


                <form id="changeTicketAmount" name="changeTicketAmount" method="post"
                      action="/controllers/cart.php?ticketId=<?= $ticket->getId() ?>&next=cart">
                    <input id="addTicketButton" type="submit" name="addTicketButton" value="+">
                    <input id="removeTicketButton" type="submit" name="removeTicketButton" value="-">
                </form>

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
            Price (ex. VAT):    €<?= $exVAT ?>
                <br>
            VAT:                €<?= $VAT ?>
                <br>
            Total price:        €<?= $total ?>
            <form id="/agreement" method="post">
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
