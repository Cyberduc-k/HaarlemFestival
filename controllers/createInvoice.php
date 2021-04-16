<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/InvoiceService.php';
require_once __DIR__.'/../services/TicketService.php';
require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/EventType.php';
require_once __DIR__.'/../models/Invoice.php';
require_once __DIR__.'/../models/UserTypes.php';

if (!isset($_SESSION['userType'])) {
    header("Location: /login");
    exit;
}

if ($_SESSION['userType'] == UserTypes::CLIENT) {
    echo "<h1>You do not have permission to access this page";
    exit;
}

$tService = new TicketService();

if ($_POST) {
    // unset any previous error
    unset($_SESSION['createInvoiceError']);

    if (!empty($_POST['user']) && !empty($_POST['userAddress']) &&
        !empty($_POST['userPhone']) && !empty($_POST['tax']) &&
        !empty($_POST['dueDate']) && !empty($_POST['ticketId']) &&
        !empty($_POST['ticketCount'])
    ) {
        $iService = new InvoiceService();
        $invoice = new Invoice();

        $invoice->setUserId((int)htmlentities($_POST["user"]));
        $invoice->setUserAddress(htmlentities($_POST["userAddress"]));
        $invoice->setUserPhone(htmlentities($_POST["userPhone"]));
        $invoice->setTax((float)htmlentities($_POST["tax"]) / 100.0);
        $invoice->setDate(new DateTime());
        $invoice->setDueDate(new DateTime(htmlentities($_POST["dueDate"])));

        $ticketIds = $_POST["ticketId"];
        $ticketCounts = $_POST["ticketCount"];

        if ($iService->create($invoice)) {
            for ($i = 0; $i < count($ticketIds); $i++) {
                // make sure the ticket count > 0
                if ($ticketCounts[$i] <= 0) {
                    $_SESSION['createInvoiceError'] = "Cannot add a negative number of tickets";
                    header("Location: /invoice/create");
                    exit;
                }

                if (!$iService->addTicket($invoice->getId(), (int)$ticketIds[$i], (int)$ticketCounts[$i])) {
                    $_SESSION['createInvoiceError'] = "Could not add a ticket to the invoice";
                    header("Location: /invoice/create");
                    exit;
                }
            }

            echo "Succesfully created invoice";
        } else {
            $_SESSION['createInvoiceError'] = "Failed to create invoice";
            header("Location: /invoice/create");
            exit;
        }
    } else {
        $_SESSION['createInvoiceError'] = "Not all data is filled in";
        header("Location: /invoice/create");
        exit;
    }
} else {
    $uService = new UserService();
    $users = $uService->getAll();
    $tickets = $tService->getAll();

    if (is_null($users)) $users = [];
    if (is_null($tickets)) $tickets = [];

    require __DIR__.'/../views/createInvoice.php';
}

?>
