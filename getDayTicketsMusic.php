<?php

require_once("services/TicketService.php");

header("Content-Type: application/json");

$service = new TicketService();

if (isset($_POST["day"]) && isset($_POST["eventID"])) {
    $day = $_POST["day"];
    $eventType = (int)$_POST["eventID"];
    $date = "";

    switch($day){
        case "Thursday":
            $date = "07-26";
            break;
        case "Friday":
            $date = "07-27";
            break;
        case "Saturday":
            $date = "07-28";
            break;
        case "Sunday":
            $date = "07-29";
            break;
    }

    $tickets = $service->getMusicTicketsPerDay($eventType, $date);

    echo json_encode($tickets);
} else {
    echo "[]";
}

?>
