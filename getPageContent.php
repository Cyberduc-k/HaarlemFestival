<?php
require_once "services/ContentService.php";

function updateContent() {
    $cs = new ContentService();
    $content = $cs->getByEventId(3);

    $aboutText = $_POST["myContent"];
    $content->setText($aboutText);

    $cs->update($content);
    return true;
}
