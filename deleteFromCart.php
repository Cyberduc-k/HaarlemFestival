<?php

if (!isset($_SESSION)) session_start();

require_once("models/Ticket.php");
require_once("services/TicketService.php");
require_once("models/Reservation.php");
require_once("services/ReservationService.php");
require_once("services/RestaurantService.php");

require_once __DIR__ . '/services/TicketService.php';
require_once __DIR__ . '/services/PaymentService.php';

if (!isset($_SESSION['userId'])) {
    header("Location: /login");
    exit;
}

$userId = $_SESSION['userId'];
$ticketService = new TicketService();
$tickets = $ticketService->getAllForCart($userId);
$ticketId = (int) $_GET["ticketId"];
$amount = (int) $_GET["amount"];

// Clear any error message
unset($_SESSION['addToCartError']);

// get redirect link
if (!empty($_GET['next'])) {
    $next = $_GET['next'];
} else {
    $next = "/tickets/food";
}

if (isset($_POST['deleteTicketsButton'])) {
    foreach ($tickets as $twc) {
        $ticket = $twc->ticket;
        if ($ticket->getId() == $ticketId) {
            $amount = $twc->count;
            $ticketService->deleteFromCart($userId, $ticketId);
            $_SESSION['cartTicketsRemoved'] = "removed tickets";
        }
    }
}
echo $ticketId;
header("Location: $next");
exit;
?>