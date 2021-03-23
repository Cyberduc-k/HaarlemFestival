<?php
require_once "services/ContentService.php";
require_once "retreiveContent.php";

$about = $_POST['var']; // doorgestuurd van js
$eventID = $_POST['eventID'];
updateContent($about, $eventID);

function updateContent($aboutText, $eventID) {
    $rc = new retreiveContent();
    $cs = new ContentService();

    // getting current content
    $content =  $rc->retreive($eventID);

    // updating current content with new content
    $content->setText($aboutText);
    $cs->update($content);
}




