<?php

if (!isset($_SESSION['userType'])) session_start();

require_once __DIR__.'/../services/ContentService.php';
require_once __DIR__.'/../services/EventService.php';
require_once __DIR__.'/../models/UserTypes.php';

if ($_SESSION['userType'] != UserTypes::SUPERADMIN) {
    echo '<h1>You do not have permmission to access this page</h1>';
    exit;
}

$es = new EventService();

if ($_POST) {
    $cs = new ContentService();

    if (isset($_POST["name"]) && isset($_POST["colour"]) && isset($_POST["header"]) && isset($_POST["text"])) {
        $name = strtolower(htmlspecialchars($_POST["name"]));
        $colour = htmlspecialchars($_POST["colour"]);

        $event = new Event();
        $event->setName($name);
        $event->setColour($colour);

        $es->addEvent($event);

        $eventID = $es->getIdByName($event->getName());

        if ($eventID != 0) {
            $content = new Content();
            $content->setEventId($eventID);
            $content->setHeader(htmlspecialchars($_POST["header"]));
            $content->setText(htmlspecialchars($_POST["text"]));

            $cs->addContentPage($content);
        }

        header("Location: /event/".$name);
    } else if (isset($_POST['delete']) && $_POST['Event'] != 0) {
        $eventId = htmlspecialchars($_POST["Event"]);

        $cs->deleteByEventId($eventId);
        $es->delete($eventId);
    } else {
        header("Location: /event/add");
        exit;
    }
} else {
    $events = $es->getAll();

    require __DIR__.'/../views/addDeleteEvent.php';
}

?>
