<html lang="en">
<head>
    <title>Cart</title>
    <link type="text/css" rel="stylesheet" href="/css/cart.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css"/>
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
    <?php
    if (count($tickets) >= 1) { ?>
        <section>
            <article id="tickets">
                <fieldset>
                    <?php
                    $total = 0;
                    foreach ($tickets

                             as $twc) {
                        $ticket = $twc->ticket;
                        $start = $ticketService->getStartDate($ticket);
                        $startDate = $start->format('d-m-y H:i');
                        $name = $ticketService->getDescription($ticket);
                        $location = $ticketService->getLocation($ticket);
                        $amount = $twc->count;
                        $price = $ticket->getPrice();
                        $ticketPrice = $amount * $price;
                        $total += $ticketPrice;
                        $exVAT = ($total / 121) * 100;
                        $VAT = ($total - $exVAT);
//                $exVAT = number_format((($total / 121) * 100), 2, ".", ",");
//                $VAT = number_format(($total - $exVAT), 2, ".", ",");
                        ?>
                        <div class="ticket">
                            <span class="name"><?= $name ?></span>
                            <span class="location"><?= $location ?></span>
                            <span class="time"><?= $startDate ?> </span>
                            <span class="price">€<?= number_format($price, 2, ".", ",") ?></span>

                            <span class="numOfTickets">
                    <form id="changeTicketAmount" name="changeTicketAmount" method="post"
                          action="/controllers/cart.php?ticketId=<?= $ticket->getId() ?>&next=cart">
                        <input id="removeTicketButton" type="submit" name="removeTicketButton" value="-">

                        <?= $amount ?>
                        <input id="addTicketButton" type="submit" name="addTicketButton" value="+">
                    </form>
                    </span>
                            <form id="deleteTicketsFromCart"
                                  name="deleteTicketsFromCart" method="post"
                                  action="/deleteFromCart.php?ticketId=<?= $ticket->getId() ?>&next=cart">
                                <label for="deleteTicketsButton"> </label>
                                <input id="deleteTicketsButton" name="deleteTicketsButton" value=""
                                       type="submit"
                                       onclick="return confirm('You are about to delete your ticket(s) from this cart. Continue?')">
                            </form>
                        </div>
                    <?php } ?>
                </fieldset>
            </article>


            <form id="agreement" name="agreement" method="post">
                <div class="amountcol">
                    <ul class="price-amounts">
                        <li>€<?= number_format($exVAT, 2, ".", ",") ?> </li>
                        <li>€<?= number_format($VAT, 2, ".", ",") ?> </li>
                        <br>
                        <li>€<?= number_format($total, 2, ".", ","); ?></li>
                        <input id="agreeButton" type="submit" name="agreeButton" value="Proceed to payment">

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

            </form>
        </section>
    <?php } else {
        unset($_SESSION['cartTicketsRemoved'])
        ?>
        <article class="emptyContent">
            It looks like your cart is empty.
        </article> <?php
    } ?>

    <div class="formField red">
        <p><?php if (isset($_SESSION['cartTicketsRemoved'])) {
                echo $_SESSION['cartTicketsRemoved'];
            }
            unset($_SESSION['cartTicketsRemoved']); ?></p>
    </div>
</main>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
