<?php
require_once "services/ContentService.php";

$about = $_POST['var'];
$eventID = $_POST['eventID'];
updateContent($about, $eventID);

function updateContent($aboutText, $eventID) {
    echo $aboutText;

    $cs = new ContentService();
    $content = $cs->getByEventId($eventID);

    $content->setText($aboutText);

    $cs->update($content);
}




