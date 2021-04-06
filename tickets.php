<?php

require_once ("services/EventService.php");

$es = new EventService();

if (isset($_GET["event"])) {
    $eventID = (int)$_GET["event"];
    $event = $es->getById($eventID);
} else {
    header("Location: ./");
}

$eventName = ucfirst($event->getName());

?>

<html lang="en">
<head>
    <title>Tickets</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/tickets.css" />
</head>
<body class="<?php echo $event->getColour(); ?>" >
    <?php require_once("menubar.php"); ?>

    <header id="header">
        <h1>Tickets</h1>

        <fieldset>
            <label for="event">Event:</label>
            <select name="event" onchange="changeEvent(this)">
                <?php
                    $events = $es->getAll();

                    foreach ($events as $ev) {
                        echo '<option value="' . $ev->getId() . '"';
                        if ($ev->getId() == $eventID) echo ' selected';
                        echo '>' . ucfirst($ev->getName()) . '</option>';
                    }
                ?>
            </select>
        </fieldset>

        <span class="prices">Prices</span>
        <a class="cart" href="cart.php">Cart</a>

        <span class="row1">Single Ticket</span>
        <span class="row2">All-Access for this day</span>
        <span class="row3">All-Access for Thu, Fri, Sat</span>

        <span class="val1">10,- - 15,-</span>
        <span class="val2">35,-</span>
        <span class="val3">80,-</span>
    </header>

    <section id="tickets">
        <?php if ($eventName == "Jazz" || $eventName == "Dance"){ ?>
            <nav id="days">
                <ul>
                    <?php if ($eventName == "Jazz"){ ?>
                        <li class="active">
                            <a onclick="dayTickets(this, 'Thursday')">
                                Thursday
                            </a>
                        </li>
                    <?php } ?>
                    <li <?php if ($eventName == "Dance") echo 'class="active"'; ?> >
                        <a onclick="dayTickets(this, 'Friday')">
                            Friday
                        </a>
                    </li>
                    <li>
                        <a onclick="dayTickets(this, 'Saturday')">
                            Saturday
                        </a>
                    </li>
                    <li>
                        <a onclick="dayTickets(this, 'Sunday')">
                            Sunday
                        </a>
                    </li>
                </ul>
            </nav>
        <?php } ?>
    </section>

    <script>
        function changeEvent(input) {
            const value = input.value;

            location.assign(`tickets.php?event=${value}`);
        }

        function dayTickets(self, day) {
            const eventID = `<?php echo $eventID ?>`;
            const body = new FormData();

            for (const el of document.querySelectorAll("#days>ul>li")) {
                el.classList.remove("active");
            }

            self.parentElement.classList.add("active");
        }
    </script>
</body>
</html>
