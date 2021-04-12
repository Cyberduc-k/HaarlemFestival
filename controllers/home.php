<?php

require_once __DIR__.'/../services/EventService.php';
require_once __DIR__.'/../services/RetrieveContent.php';
require_once __DIR__.'/../services/ContentService.php';

$es = new EventService();
$rc = new RetrieveContent();
$cs = new ContentService();

$events = $es->getAll();

require __DIR__.'/../views/home.php';

?>
