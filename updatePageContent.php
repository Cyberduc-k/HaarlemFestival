<?php
require_once __DIR__.'/services/ContentService.php';
require_once __DIR__.'/services/RetrieveContent.php';

$about = $_POST['var']; // doorgestuurd van js
$eventID = $_POST['eventID'];
updateContent($about, $eventID);

function updateContent($aboutText, $eventID) {
    $rc = new RetrieveContent();
    $cs = new ContentService();

    // getting current content
    $content =  $rc->retrieve($eventID);

    // updating current content with new content
    $content->setText($aboutText);
    $cs->update($content);
}
