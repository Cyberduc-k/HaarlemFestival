<html lang="en">
<head>
    <title>Create an invoice</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/createInvoice.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <section class="content">
        <h1>Create a new invoice</h1>
        <form id="form" method="post">
            <fieldset>
                <label for="user">For User:</label>
                <input id="userInput" name="user" type="text" list="userList" required />
                <datalist id="userList">
                    <?php
                        foreach ($users as $user) {
                            echo '<option value="' . $user->getId() . '">' . $user->getFullName() . '</option>';
                        }
                    ?>
                </datalist>
            </fieldset>

            <fieldset>
                <label for="userAddress">User Address:</label>
                <input name="userAddress" type="text" required />
            </fieldset>

            <fieldset>
                <label for="userPhone">User Phone Number:</label>
                <input name="userPhone" type="tel" required />
            </fieldset>

            <fieldset>
                <table id="ticketTable" border="1">
                    <thead>
                        <th>Ticket</th>
                        <th>Count</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
                <datalist id="ticketList">
                    <?php
                        foreach ($tickets as $ticket) {
                            echo '<option value="' . $ticket->getId() . '">' . $tService->getDescription($ticket) . '</option>';
                        }
                    ?>
                </datalist>
                <input onclick="addTicket()" type="button" value="Add Ticket" />
            </fieldset>

            <fieldset>
                <label for="tax">Tax:</label>
                <select name="tax" required>
                    <option value="9" selected>9%</option>
                    <option value="21">21%</option>
                </select>
            </fieldset>

            <fieldset>
                <label for="dueDate">Due Date:</label>
                <input name="dueDate" type="date" required />
            </fieldset>

            <input type="submit" value="Create Invoice" />
        </form>
    </section>

    <script>
        const ticketTable = document.getElementById("ticketTable").children[1];
        let ticketIds = 0;

        addTicket();

        function addTicket() {
            const row = document.createElement("tr");
            const c1 = document.createElement("td");
            const c2 = document.createElement("td");
            const c3 = document.createElement("td");
            const ticket = document.createElement("input");
            const count = document.createElement("input");
            const remove = document.createElement("input");

            ticket.type = "text";
            ticket.id = `ticket${ticketIds}`;
            ticket.name = `ticketId[${ticketIds}]`;
            ticket.required = true;
            ticket.setAttribute("list", "ticketList");

            count.type = "number";
            count.min = "1";
            count.value = "1";
            count.name = `ticketCount[${ticketIds}]`;
            count.required = true;

            ticketIds++;
            remove.type = "button";
            remove.value = "Remove";

            remove.addEventListener("click", () => {
                ticketTable.removeChild(row);

                if (ticketTable.children.length == 0)
                    addTicket();
            });

            c1.appendChild(ticket);
            c2.appendChild(count);
            c3.appendChild(remove);
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            ticketTable.appendChild(row);
        }
    </script>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
