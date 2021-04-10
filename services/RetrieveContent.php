<?php

require_once __DIR__.'/ContentService.php';

class RetrieveContent {
    private ContentService $cs;

    public function __construct() {
        $this->cs = new ContentService();
    }

    function retrieve($eventID) {
        return $this->cs->getByEventId($eventID);
    }

    function retrieveImage($contentId): ?Image {
        return $this->cs->getImageForContent($contentId);
    }
}

?>
