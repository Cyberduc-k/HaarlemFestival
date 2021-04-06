<?php

require_once ("services/EventService.php");

$es = new EventService();

if (isset($_GET["event"])) {
    $eventID = (int)$_GET["event"];
    $event = $es->getById($eventID);
} else {
    header("Location: ./");
}

$eventName = ucfirst($event->getName());

?>

<html lang="en">
<head>
    <title>Tickets</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body class="<?php echo $event->getColour(); ?>" >
    <?php require_once("menubar.php"); ?>

    <header id="header">
        <h1>Tickets</h1>

        <fieldset>
            <label for="event">Event:</label>
            <select name="event" onchange="changeEvent(this)">
                <?php
                    $events = $es->getAll();

                    foreach ($events as $ev) {
                        echo '<option value="' . $ev->getId() . '"';
                        if ($ev->getId() == $eventID) echo ' selected';
                        echo '>' . ucfirst($ev->getName()) . '</option>';
                    }
                ?>
            </select>
        </fieldset>
    </header>

    <script>
        function changeEvent(input) {
            const value = input.value;

            location.assign(`tickets.php?event=${value}`);
        }
    </script>
</body>
</html>
