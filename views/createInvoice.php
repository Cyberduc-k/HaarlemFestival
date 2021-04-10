<?php if (!isset($_SESSION)) session_start(); ?>

<html lang="en">
<head>
    <title>Create an invoice</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/createInvoice.css" />
</head>
</html>

<?php

require_once("models/Invoice.php");
require_once("models/EventType.php");
require_once("services/InvoiceService.php");
require_once("services/UserService.php");
require_once("services/TicketService.php");
require_once("services/PaymentService.php");
// require_once("validate.php");
require_once("menubar.php");

if ($_POST) {
    $pService = new PaymentService();
    $tService = new TicketService();
    $service = new InvoiceService();
    $invoice = new Invoice();

    $invoice->setUserId((int)$_POST["user"]);
    $invoice->setUserAddress($_POST["userAddress"]);
    $invoice->setUserPhone($_POST["userPhone"]);
    $invoice->setTax((float)$_POST["tax"] / 100.0);
    $invoice->setDate(new DateTime());
    $invoice->setDueDate(new DateTime($_POST["dueDate"]));
    $invoiceAmount = 0;

    $ticketIds = $_POST["ticketId"];
    $ticketCounts = $_POST["ticketCount"];

    if ($service->create($invoice)) {
        for ($i = 0; $i < count($ticketIds); $i++) {
            // $invoiceAmount += $tService->getPrice((int)$ticketIds[$i]);
            if (!$service->addTicket($invoice->getId(), (int)$ticketIds[$i], (int)$ticketCounts[$i])) {
                header("Location: createInvoice.php");
                exit;
            }
        }
        // $pService->createPayment($invoice->getUserId(), $invoiceAmount);
        echo "Succesfully created invoice";
    } else {
        header("Location: createInvoice.php");
        exit;
    }
} else {
?>
    <section class="content">
        <h1>Create a new invoice</h1>
        <form id="form" action="createInvoice.php" method="post">
            <fieldset>
                <label for="user">For User:</label>
                <input id="userInput" name="user" type="text" list="userList" required />
                <datalist id="userList">
                    <?php
        
                    $service = new UserService();
                    $users = $service->getAll();

                    if ($users == null) $users = [];

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

                    $service = new TicketService();
                    $tickets = $service->getAll();

                    foreach ($tickets as $ticket) {
                        echo '<option value="' . $ticket->getId() . '">' . $service->getDescription($ticket) . '</option>';
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
<?php } ?>
</body>
</html>
