<!DOCTYPE html>
<hmtl lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"   content="width=device-width">
        <title>Document</title>
    </head>
    <body>
    <a href="../index.php">
        Back
    </a>
    <h2>Create account</h2>
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
        $event = new Event(htmlspecialchars($_POST["name"]), htmlspecialchars($_POST["colour"]));
        $es->addEvent($event);

        $eventID = $es->getIdByName($event->getName());

        if($eventID != 0)
        {
            $cs->addContent();
        }
    }
    ?>
    </body>
</hmtl>