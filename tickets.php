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
        <?php if ($eventName == "Food") { ?>
            <h1>Reservations</h1>
        <?php } else { ?>
            <h1>Tickets</h1>
        <?php } ?>

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

        <?php if ($eventName == "Jazz") { ?>
            <span class="row1">Single Ticket</span>
            <span class="row2">All-Access for this day</span>
            <span class="row3">All-Access for Thu, Fri, Sat</span>

            <span class="val1">€ 10,00 - 15,00</span>
            <span class="val2">€ 35,-</span>
            <span class="val3">€ 80,-</span>
        <?php } else if ($eventName == "Dance") { ?>
            <span class="row1">Single Ticket</span>
            <span class="row2">All-Access for this day</span>
            <span class="row3">All-Access for Fri, Sat, Sun</span>

            <span class="val1">€ 60,00 - 110,00</span>
            <span class="val2">€ 125,-</span>
            <span class="val3">€ 250,-</span>
        <?php } else if ($eventName == "Historic") { ?>
            <span class="row1">Single Ticket</span>
            <span class="row2">Family Ticket (max 4 persons)</span>

            <span class="val1">€ 17,50</span>
            <span class="val2">€ 60,-</span>
        <?php } else if ($eventName == "Food") { ?>
            <span class="row1">Wuuuuuuut Single Ticket</span>
            <span class="row2">Family Ticket (max 4 persons)</span>

            <span class="val1">€ 17,50</span>
            <span class="val2">€ 60,-</span>
        <?php } ?>
    </header>

    <section>
        <nav id="days">
            <ul>
                <?php if ($eventName != "Dance"){ ?>
                    <li id="thursday">
                        <a onclick="dayTickets(this, 'Thursday')">
                            Thursday
                        </a>
                    </li>
                <?php } ?>
                <li id="friday" >
                    <a onclick="dayTickets(this, 'Friday')">
                        Friday
                    </a>
                </li>
                <li id="saturday">
                    <a onclick="dayTickets(this, 'Saturday')">
                        Saturday
                    </a>
                </li>
                <li id="sunday">
                    <a onclick="dayTickets(this, 'Sunday')">
                        Sunday
                    </a>
                </li>
            </ul>
        </nav>

        <article id="tickets">
        </article>

        <form id="options" method="post" action="addToCart.php?eventId=<?php echo $eventID; ?>,next=tickets.php?event=<?php echo $eventID; ?>">
            <fieldset>
                <?php if ($eventName == "Food") {?>
                    <label for="ticketType">Time:</label>
                <?php } else { ?>
                    <label for="ticketType">Type:</label>
                <?php } ?>
                <select name="ticketType" onchange="changeTicketType(this)">
                    <?php
                        if ($eventName == "Historic") {
                            echo '<option value="0">Single Ticket</option>';
                            echo '<option value="3">Family Ticket</option>';
                        } elseif ($eventName == "Food") {
                            echo '<option value="17:00:00">17:00</option>';
                            echo '<option value="18:00:00">18:00</option>';
                            echo '<option value="19:00:00">19:00</option>';
                        } else {
                            echo '<option value="0">Single Ticket</option>';
                            echo '<option value="1">1 Day All-Access</option>';
                            echo '<option value="2">3 Day All-Access</option>';
                        }
                    ?>
                </select>

                <label for="amount">Amount:</label>
                <input type="number" name="amount" min="1" value="1" />

                <span class="price" id="price">€ __.__</span>
                <label class="price">Price:</label>
                
                <input id="ticketId" type="hidden" name="ticketId" required />
                <?php if ($eventName == "Food") { ?>
                    <input id="time" type="hidden" name="time" required />
                    <input id="day" type="hidden" name="day" required />
                <?php } ?>
            </fieldset>

            <input id="addToCart" type="submit" name="addToCart" value="Add to cart" />
            <input id="addToProgramme" type="submit" name="addToProgramme" value="Add to programme" />
        </form>
    </section>

    <script>
        const section = document.getElementById("tickets");
        const ticketId = document.getElementById("ticketId");
        const price = document.getElementById("price");
        let ticketType = 0;
        let tickets = [];
        let filtered = [];

        function changeEvent(input) {
            const value = input.value;

            location.assign(`tickets.php?event=${value}`);
        }

        function dayTickets(self, day) {
            const eventID = <?php echo $eventID; ?>;
            const body = new FormData();

            for (const el of document.querySelectorAll("#days>ul>li")) {
                el.classList.remove("active");
            }

            self.parentElement.classList.add("active");
            location.hash = day;

            body.append("day", day);
            body.append("eventID", eventID);

            fetch("getDayTicketsMusic.php", {
                method: "POST",
                body,
            }).then(async (res) => {
                tickets = (await res.json()) || [];
                console.log(tickets);
                renderTickets();
            });
        }

        function renderTickets() {
            const eventName = "<?php echo $eventName ?>";

            section.innerHTML = "";
            if (eventName != "Food") {
                filterTickets();
            } else {
                filtered = tickets;
            }

            if (eventName === "Historic"){
                filtered.forEach((ticket, idx) => {
                    section.insertAdjacentHTML("beforeend", `
                    <div class="ticket" onclick="selectTicket(this, ${ticket.id}, ${idx})">
                        <span class="name">${ticket.language}</span>
                        <span class="location">${ticket.guide}</span>
                        <span class="time">${ticket.date}</span>
                        <span class="stock">${ticket.price == 0 ? '' : `${ticket.inStock} Left`}</span>
                    </div>
                `);
                });
            }else if (eventName === "Food"){
                filtered.forEach((ticket, idx) => {
                    section.insertAdjacentHTML("beforeend", `
                    <div class="ticket" onclick="selectTicket(this, ${ticket.id}, ${idx})">
                        <span class="name">${ticket.name}</span>
                        <span class="location">${ticket.location}</span>
                        <span class="time">${ticket.foodType}</span>
                    </div>
                `);
                });
            }else{
                filtered.forEach((ticket, idx) => {
                    section.insertAdjacentHTML("beforeend", `
                    <div class="ticket" onclick="selectTicket(this, ${ticket.id}, ${idx})">
                        <span class="name">${ticket.name}</span>
                        <span class="location">${ticket.location}</span>
                        <span class="time">${ticket.startTime.slice(0, -3)} - ${ticket.endTime.slice(0, -3)}</span>
                        <span class="stock">${ticket.price == 0 ? '' : `${ticket.inStock} Left`}</span>
                    </div>
                `);
                });
            }


        }


        function selectTicket(self, id, idx) {
            const ticketPrice = filtered[idx].price;

            if (ticketPrice == Math.floor(ticketPrice))
                price.innerText = `€ ${ticketPrice},-`;
            else
                price.innerText = `€ ${ticketPrice.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;

            ticketId.value = id;

            for (const el of document.querySelectorAll(".ticket")) {
                el.classList.remove("selected");
            }

            self.classList.add("selected");

            <?php if ($eventName == "Food") { ?>
                document.getElementById("day").value = location.hash.substring(1);
            <?php } ?>
        }

        function filterTickets() {
            filtered = tickets.filter(val => val.ticketType == ticketType);
        }

        function changeTicketType(self) {
            ticketType = parseInt(self.value);
            renderTickets();
        }

        if (location.hash !== "") {
            const day = location.hash.substring(1);
            const x = document.getElementById(day.toLowerCase());

            dayTickets(x.firstElementChild, day);
        } else {
            const day = "<?php if ($eventName != "Dance") echo "Thursday"; else echo "Friday"; ?>";
            const x = document.getElementById(day.toLowerCase());

            dayTickets(x.firstElementChild, day);
        }
    </script>
</body>
<?php
require_once ("footer.php");
?>
</html>
