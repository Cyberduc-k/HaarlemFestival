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
    $rs = new RestaurantService();
    $rc = new RetrieveContent();
    $event = $es->getByName($name);

    if (is_null($event)) {
        require __DIR__.'/../views/404.php';
        return;
    }

    $eventName = ucfirst($name);
    $content = $rc->retrieve($event->getId());
    $img = $rc->retrieveImage($content->getId());
    $image = !is_null($img) ? "uploads/uploadedIMG/".$img->getId()."-".$img->getName() : "";

    require __DIR__.'/../views/event.php';
}

?>
