<?php
require_once ("EventSchedule.php");

$schedule = new EventSchedule();

if (isset($_POST["day"]) && isset($_POST["eventID"])) {
    $day = $_POST["day"];
    $eventID = $_POST["eventID"];

    $schedule->musicEvent($eventID, $day);
}
