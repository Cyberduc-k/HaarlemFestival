<?php
require_once __DIR__.'/services/EventSchedule.php';

$schedule = new EventSchedule();

// send day and event to get right schedule from database
if (isset($_POST["day"]) && isset($_POST["eventID"])) {
    $day = $_POST["day"];
    $eventID = $_POST["eventID"];
    $schedule->musicEvent($eventID, $day);
}
