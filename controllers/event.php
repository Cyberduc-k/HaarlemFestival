<?php

require_once __DIR__.'/../services/ContentService.php';
require_once __DIR__.'/../services/EventService.php';
require_once __DIR__.'/../services/EventSchedule.php';
require_once __DIR__.'/../services/RetrieveContent.php';
require_once __DIR__.'/../services/RestaurantService.php';
require_once __DIR__.'/../models/FoodType.php';
require_once __DIR__.'/../models/Restaurant.php';
require_once __DIR__.'/../models/UserTypes.php';

function run(string $name) {
    $schedule = new EventSchedule();
    $es = new EventService();
    $rc = new RetrieveContent();
    $event = $es->getByName($name);

    if (!isset($event)) {
        require __DIR__.'/../views/404.php';
        return;
    }

    $eventName = ucfirst($name);
    $content = $rc->retrieve($event->getId());
    $img = $rc->retrieveImage($content->getId());
    $image = !is_null($img) ? "uploads/uploadedIMG/".$img->getId()."-".$img->getName() : "";

    if ($name == "food") {
        $rs = new RestaurantService();
        $restaurants = $rs->getAll();
    }

    require __DIR__.'/../views/event.php';
}

function isTabActive(string $name): string {
    if ($_GET['tab'] == $name)
        return "class='active'";

    return "";
}

function isDayActive(string $name): string {
    if ($_GET['day'] == $name)
        return "class='active'";

    return "";
}

?>
