<?php

if (!isset($_SESSION)) session_start();

require_once("models/Ticket.php");
require_once("services/TicketService.php");
require_once("models/Reservation.php");
require_once("services/ReservationService.php");
require_once("services/RestaurantService.php");

if (!isset($_SESSION['userId'])) {
    header("Location: /login");
    exit;
}

$eventID = $_GET["eventId"];

// Clear any error message
unset($_SESSION['addToCartError']);

// get redirect link
if (!empty($_GET['next'])) {
    $next = $_GET['next'];
} else {
    $next = "/tickets/food";
}

// check if all post variables are set
if (empty($_POST['ticketId']) || empty($_POST['amount']) || !isset($_POST['ticketType'])) {
    $_SESSION['addToCartError'] = "Not all data is filled in";
    header("Location: $next");
    exit;
}

// ensure the amount is > 0
if ((int)$_POST['amount'] <= 0) {
    $_SESSION['addToCartError'] = "Cannot add a negative amount of tickets to cart";
    header("Location: $next");
    exit;
}

$ts = new TicketService();
$userId = (int)$_SESSION["userId"];
$ticketId = (int)$_POST["ticketId"];
$count = (int)$_POST["amount"];
$maxTickets = (int) $_POST["inStock"];

// in case we order a food ticket also create a new reservation
if ($eventID == 2) {
    $rests = new RestaurantService();
    $reses = new ReservationService();

    $restaurant = $rests->getById($ticketId);

    $reservation = new Reservation();
    $reservation->setName($_SESSION["firstname"]);
    $reservation->setRestaurantId($ticketId);
    $reservation->setReservationTime(new DateTime($_POST["ticketType"]));
    $reservation->setComment($_POST["comment"]);

    $reses->addReservation($reservation);

    $reservationTicket = new Ticket();
    $reservationTicket->setEventId($reservation->getId());
    $reservationTicket->setEventType(1);
    $reservationTicket->setInStock(1);
    $reservationTicket->setPrice($restaurant->getPrice());
    $reservationTicket->setType(0);

    $ts->create($reservationTicket);

    $ticketId = $reservationTicket->getId();
}

if (isset($_POST["addToCart"])) {

    $ts->addToCart($userId, $ticketId, $count);
    $_SESSION['addToCart'] = "Added to cart";
    if($count > $maxTickets && $eventID != 2) {
        $_SESSION['addToCartError'] = "Cannot add more tickets than available";
        header("Location: $next");
        exit;
    } else {
        $_SESSION['addToCartSuccess'] = "Ticket(s) added to cart!";
        $ts->addToCart($userId, $ticketId, $count);
    }
} else if (isset($_POST["addToProgramme"])) {
    $ts->addToSchedule($userId, $ticketId, $count);
    $_SESSION['addToCart'] = "Added to programme";
}
header("Location: $next");

?>
