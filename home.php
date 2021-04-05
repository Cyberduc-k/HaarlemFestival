<?php if(!isset($_SESSION)) session_start(); ?>

<html lang="en">
    <head>
        <title>Home</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <?php
        require_once ("menubar.php");
        require_once ("services/EventService.php");

        $es = new EventService();
        $events = $es->getAll();

            echo "<h1>Haarlem Festival</h1>";

            foreach ($events as $ev)
            {
                $en = ucfirst($ev->getName());
                $eid = $ev->getId();

                echo "<h3>$en</h3>";
                echo "<a href='/eventPage.php?event=$eid'>More...</a>";
            }

        ?>
    </body>
</html>
