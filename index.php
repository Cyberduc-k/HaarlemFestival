<?php if(!isset($_SESSION)) session_start(); ?>

<html lang="en">
    <head>
        <title>Home</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <link type="text/css" rel="stylesheet" href="css/home.css" />
    </head>
    <body>
        <?php
        require_once ("menubar.php");
        require_once ("services/EventService.php");

        $es = new EventService();
        $events = $es->getAll();

            echo "<header id='header'><h1>Haarlem Festival</h1></header>";

            foreach ($events as $ev)
            {
                $en = ucfirst($ev->getName());
                $eid = $ev->getId();

                echo "<h3>$en</h3>";
                echo "<a id='eventMore' href='/eventPage.php?event=$eid'>More...</a>";
            }

        ?>
    </body>
    <?php
    require_once ("footer.php");
    ?>
</html>
