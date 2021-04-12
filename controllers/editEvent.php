<?php

require_once __DIR__.'/../services/ContentService.php';
require_once __DIR__.'/../services/EventService.php';
require_once __DIR__.'/../services/RetrieveContent.php';
require_once __DIR__.'/../services/ImageService.php';

function run(string $name) {
    $es = new EventService();
    $event = $es->getByName($name);

    if (!isset($event)) {
        require __DIR__.'/../views/404.php';
        return;
    }

    $rc = new RetrieveContent();
    $is = new ImageService();
    $content = $rc->retrieve($event->getId());

    if (isset($_POST['img'])) {
        $cs = new ContentService();
        $img = (int)$_POST['img'];
        $cs->insertImage($content->getId(), $img);
    }

    $eventName = ucfirst($name);
    $images = $is->getByContentPageId($content->getId());

    require __DIR__.'/../views/editEvent.php';
}

?>
