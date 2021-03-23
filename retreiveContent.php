<?php
require_once "services/ContentService.php";

class retreiveContent
{
    function retreive($eventID)
    {
        $cs = new ContentService();
        return $content = $cs->getByEventId($eventID);
    }

}