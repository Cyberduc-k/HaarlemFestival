<html lang="en">
<head>
    <title>Tickets</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/tickets.css" />
    <script src="/js/hash.js"></script>
</head>
<body class="<?php echo $event->getColour(); ?>" >
    <?php
    if (!isset($_SESSION)) session_start();
    if (isset($_SESSION['addToCart'])) { ?>
        <div class="popup">
            <?php echo $_SESSION['addToCart']; ?>
        </div>
    <?php unset($_SESSION['addToCart']); } ?>

    <?php require_once __DIR__.'/menubar.php'; ?>

    <main>
        <header id="header">
            <?php if ($name == "food") { ?>
                <h1>Reservations</h1>
            <?php } else { ?>
                <h1>Tickets</h1>
            <?php } ?>

            <fieldset>
                <label for="event">Event:</label>
                <select name="event" onchange="changeEvent(this)">
                    <?php
                        foreach ($events as $ev) {
                            echo '<option value="'.$ev->getName().'"';
                            if ($ev->getId() == $event->getId()) echo ' selected';
                            echo '>'.ucfirst($ev->getName()).'</option>';
                        }
                    ?>
                </select>
            </fieldset>

            <?php if ($name != "food") { ?>
                <span class="prices">Prices</span>
            <?php } ?>

            <a class="cart" href="/cart">Cart</a>

            <?php if ($name == "jazz") { ?>
                <span class="row1">Single Ticket</span>
                <span class="row2">All-Access for this day</span>
                <span class="row3">All-Access for Thu, Fri, Sat</span>

                <span class="val1">€ 10,00 - 15,00</span>
                <span class="val2">€ 35,-</span>
                <span class="val3">€ 80,-</span>
            <?php } else if ($name == "dance") { ?>
                <span class="row1">Single Ticket</span>
                <span class="row2">All-Access for this day</span>
                <span class="row3">All-Access for Fri, Sat, Sun</span>

                <span class="val1">€ 60,00 - 110,00</span>
                <span class="val2">€ 125,-</span>
                <span class="val3">€ 250,-</span>
            <?php } else if ($name == "historic") { ?>
                <span class="row1">Single Ticket</span>
                <span class="row2">Family Ticket (max 4 persons)</span>

                <span class="val1">€ 17,50</span>
                <span class="val2">€ 60,-</span>
            <?php } ?>
        </header>

        <section>
            <nav id="days">
                <ul>
                    <?php if ($name != "dance"){ ?>
                        <li id="thursday">
                            <a onclick="setDay(this, 'thursday')">
                                Thursday
                            </a>
                        </li>
                    <?php } ?>
                    <li id="friday" >
                        <a onclick="setDay(this, 'friday')">
                            Friday
                        </a>
                    </li>
                    <li id="saturday">
                        <a onclick="setDay(this, 'saturday')">
                            Saturday
                        </a>
                    </li>
                    <li id="sunday">
                        <a onclick="setDay(this, 'sunday')">
                            Sunday
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="formField greed">
                <p><?php if(isset($_SESSION['addToCartSuccess'])) {echo $_SESSION['addToCartSuccess']; }
                    unset($_SESSION['addToCartSuccess']); ?></p>
            </div>

            <article id="tickets">
            </article>
        </section>



        <form id="options" method="post" action="/addToCart.php?eventId=<?php echo $event->getId(); ?>&next=tickets/<?php echo $name; ?>">
            <fieldset>
                <?php if ($name == "food") {?>
                    <label for="ticketType">Time:</label>
                <?php } else { ?>
                    <label for="ticketType">Type:</label>
                <?php } ?>

                <select name="ticketType" onchange="changeTicketType(this)">
                    <?php
                        if ($name == "historic") {
                            echo '<option value="0">Single Ticket</option>';
                            echo '<option value="3">Family Ticket</option>';
                        } elseif ($name == "food") {
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

                <?php if ($name == "food") {?>
                    <label for="comment">Comments(allergies):</label>
                    <input type="text" name="comment" />
                <?php } ?>

                <span class="price" id="price">€ __.__</span>
                <label class="price">Price:</label>
                
                <input id="ticketId" type="hidden" name="ticketId" required />
                <input id="inStock" type="hidden" name="inStock" required />

                <?php if ($name == "food") { ?>
                    <input id="day" type="hidden" name="day" required />
                <?php } ?>
            </fieldset>

            <div class="formField red">
                <p><?php if (isset($_SESSION['addToCartError'])) { echo $_SESSION['addToCartError']; }
                    unset($_SESSION['addToCartError']); ?></p>
            </div>

            <input id="addToCart" type="submit" name="addToCart" value="Add to cart" />
            <input id="addToProgramme" type="submit" name="addToProgramme" value="Add to programme" />
        </form>
    </main>

    <script>
        const section = document.getElementById("tickets");
        const ticketId = document.getElementById("ticketId");
        const inStock = document.getElementById("inStock");
        const price = document.getElementById("price");
        let ticketType = 0;
        let tickets = [];
        let filtered = [];

        function setDay(self, day) {
            const eventID = <?php echo $event->getId(); ?>;
            const body = new FormData();

            for (const el of document.querySelectorAll("#days>ul>li")) {
                el.classList.remove("active");
            }

            self.parentElement.classList.add("active");
            setHash("day", day);

            body.append("day", day);
            body.append("eventID", eventID);

            fetch("/getDayTickets.php", {
                method: "POST",
                body,
            }).then(async (res) => {
                tickets = (await res.json()) || [];
                renderTickets();
            });
        }

        function renderTickets() {
            const eventName = "<?php echo $name; ?>";

            section.innerHTML = "";
            if (eventName != "food") {
                filterTickets();
            } else {
                filtered = tickets;
            }

            if (eventName === "historic") {
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
            } else if (eventName === "food") {
                filtered.forEach((ticket, idx) => {
                    section.insertAdjacentHTML("beforeend", `
                        <div class="ticket" onclick="selectTicket(this, ${ticket.id}, ${idx})">
                            <span class="name">${ticket.name}</span>
                            <span class="location">${ticket.location}</span>
                            <span class="time">${ticket.foodType}</span>
                        </div>
                    `);
                });
            } else {
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
            inStock.value = filtered[idx].inStock;

            for (const el of document.querySelectorAll(".ticket")) {
                el.classList.remove("selected");
            }

            self.classList.add("selected");

            <?php if ($name == "food") { ?>
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

        function changeEvent(select) {
            const event = select.value;

            location.replace(`/tickets/${event}`);
        }

        switch (getHash("day")) {
            case undefined:
                const day = "<?php if ($name != "dance") echo "thursday"; else echo "friday"; ?>";
                const x = document.getElementById(day);

                setDay(x.firstElementChild, day);
                break;
            default:
                const day2 = getHash("day");
                const x2 = document.getElementById(day2);

                setDay(x2.firstElementChild, day2);
                break;
        }
    </script>

    <?php require_once __DIR__.'/footer.php'; ?>
</body>
</html>
