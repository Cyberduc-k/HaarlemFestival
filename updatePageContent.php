<?php
require_once "services/ContentService.php";
require_once "retreiveContent.php";

$about = htmlspecialchars($_POST['var']); // doorgestuurd van js
$eventID = htmlspecialchars($_POST['eventID']);
updateContent($about, $eventID);

function updateContent($aboutText, $eventID) {
    $rc = new retrieveContent();
    $cs = new ContentService();

    // getting current content
    $content =  $rc->retrieve($eventID);

    // updating current content with new content
    $content->setText($aboutText);
    $cs->update($content);
}




