<?php

require_once __DIR__.'/../services/EventService.php';

function run(string $name) {
    $es = new EventService();
    $event = $es->getByName($name);

    if (!isset($event)) {
        require __DIR__.'/../views/404.php';
        return;
    }

    $eventName = ucfirst($name);
    $events = $es->getAll();

    require __DIR__.'/../views/tickets.php';
}

?>
