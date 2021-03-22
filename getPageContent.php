<?php
require_once "services/ContentService.php";


if (isset($_POST['myContent'])) {

    updateContent($_POST['myContent']);

    function updateContent($aboutText) {
        echo $aboutText;

        $cs = new ContentService();
        $content = $cs->getByEventId(3);

        $content->setText($aboutText);

        $cs->update($content);
    }

}


