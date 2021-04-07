<?php

if (!isset($_SESSION)) session_start();

require_once("services/TicketService.php");

if (isset($_SESSION["userId"])) {
    $service = new TicketService();
    $userId = (int)$_SESSION["userId"];
    $ticketId = (int)$_POST["ticketId"];
    $count = (int)$_POST["amount"];

    if (isset($_POST["addToCart"])) {
        $service->addToCart($userId, $ticketId, $count);
    } else if (isset($_POST["addToProgramme"])) {
        $service->addToSchedule($userId, $ticketId, $count);
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
