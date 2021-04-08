<?php

if (!isset($_SESSION)) session_start();

require_once("models/Ticket.php");
require_once("services/TicketService.php");
require_once("models/Reservation.php");
require_once("services/ReservationService.php");
require_once("services/RestaurantService.php");

$eventID = $_GET["eventID"];

if (isset($_SESSION["userId"])) {
    $ts = new TicketService();
    $userId = (int)$_SESSION["userId"];
    $ticketId = (int)$_POST["ticketId"];
    $count = (int)$_POST["amount"];

    if ($eventID == 2) {
        $rests = new RestaurantService();
        $reses = new ReservationService();

        $restaurant = $rests->getById($ticketId);

        $reservation = new Reservation();
        $reservation->setName($_SESSION["firstname"]);
        $reservation->setRestaurantId($ticketId);
        $reservation->setReservationTime(new DateTime($_POST("ticketType")));

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
    } else if (isset($_POST["addToProgramme"])) {
        $ts->addToSchedule($userId, $ticketId, $count);
    }

    if (isset($_GET["next"])) {
        header("Location: " . $_GET["next"]);
    } else {
        header("Location: tickets.php?event=2");
    }
} else {
    header("Location: login.php");
}

?>
