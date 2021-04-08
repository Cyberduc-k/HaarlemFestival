<?php
require_once "services/ContentService.php";

class retrieveContent
{
    private ContentService $cs;

    public function __construct()
    {
        $this->cs = new ContentService();
    }

    function retrieve($eventID)
    {
        return $content = $this->cs->getByEventId($eventID);
    }

    function retrieveImage($contentId): ?Image{
        return $this->cs->getImageForContent($contentId);
    }

}