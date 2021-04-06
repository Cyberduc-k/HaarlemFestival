<?php

require_once ("services/EventService.php");

$es = new EventService();

if (isset($_GET["event"])) {
    $eventID = $_GET["event"];
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
</body>
</html>
