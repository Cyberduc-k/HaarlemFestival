<?php if(!isset($_SESSION)) session_start(); ?>

<html lang="en">
    <head>
        <title>Home</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <link type="text/css" rel="stylesheet" href="css/home.css" />
    </head>
        <?php
        require_once ("menubar.php");
        require_once ("services/EventService.php");

        $es = new EventService();
        $events = $es->getAll();

            echo "<header id='header'><h1>Haarlem Festival</h1></header>";
            ?>
    <body>
        <section id="event">
            <?php

            foreach ($events as $ev)
            {
                $en = ucfirst($ev->getName());
                $eid = $ev->getId();

                echo "<article><h2 class='events'>$en</h2>";
                echo "<a class='events' id='eventMore' href='/eventPage.php?event=$eid'>More...</a></article>";
            }

            ?>
        </section>
    </body>
    <?php
    require_once ("footer.php");
    ?>
</html>
