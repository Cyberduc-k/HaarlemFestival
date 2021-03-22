<?php
require_once "services/ContentService.php";

$about = $_POST['var'];
updateContent($about);

function updateContent($aboutText) {
    echo $aboutText;

    $cs = new ContentService();
    $content = $cs->getByEventId(3);

    $content->setText($aboutText);

    $cs->update($content);
}




