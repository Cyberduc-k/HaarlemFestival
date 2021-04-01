<!DOCTYPE html>
<hmtl lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"   content="width=device-width">
        <title>Document</title>
    </head>
    <body>
    <h2>Add event page</h2>
    <form method="post">
        <label>Event Name<input type="text" name="name"/></label>
        <br/>
        <label>Colour<input type="text" name="colour"/></label>
        <br/>
        <label>Header<input type="text" name="header"/></label>
        <br/>
        <label>Text<input type="text" name="text"/></label>
        <br/>
        <input type="submit" value="Add event page"/>
    </form>
    <?php
    require_once ("services/EventService.php");
    require_once ("services/ContentService.php");

    $es = new EventService();
    $cs = new ContentService();

    if(isset($_POST["name"]) && isset($_POST["colour"]) && isset($_POST["header"]) && isset($_POST["text"]))
    {
        $name = strtolower(htmlspecialchars($_POST["name"]));
        $colour = htmlspecialchars($_POST["colour"]);

        $event = new Event();
        $event->setName($name);
        $event->setColour($colour);

        $es->addEvent($event);

        $eventID = $es->getIdByName($event->getName());

        if($eventID != 0)
        {
            $content = new Content();
            $content->setEventId($eventID);
            $content->setHeader(htmlspecialchars($_POST["header"]));
            $content->setText(htmlspecialchars($_POST["text"]));

            $cs->addContentPage($content);
        }
    }
    ?>
    <br/>
    <h2>Delete Event page</h2>
    <br/>
    <form method="POST" >
        <label for="Event"> Event : </label>
        <select id="cmbEvent" name="Event"
                onchange="document.getElementById('selected_text').value=this.options[this.selectedIndex].text">
            <option value="0">Select Event</option>
            <?php
                $events = $es->getAll();

                foreach ($events as $event)
                {
                    $id = $event->getId();
                    $name = $event->getName();

                    echo "<option value=$id>$name</option>";
                }
            ?>
        </select>
        <input type="hidden" name="selected_text" id="selected_text" value="" />
        <input type="submit" name="delete" value="Delete"/>
        <?php
            if(isset($_POST["delete"]) && $_POST["Event"] != 0)
            {
                $eventId = htmlspecialchars($_POST["Event"]);

                $cs->deleteByEventId($eventId);
                $es->delete($eventId);
            }
        ?>
    </form>
    </body>
</hmtl>