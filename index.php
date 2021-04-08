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
        require_once ("retreiveContent.php");
        require_once ("services/ContentService.php");

        $es = new EventService();
        $rc = new retrieveContent();
        $cs = new ContentService();

        $events = $es->getAll();

            echo "<header id='header'><h1>Haarlem Festival</h1></header>";
            ?>
    <body>
        <section id="event">
            <?php

            foreach ($events as $ev)
            {
                ?>
            <article>
                <?php
                $en = ucfirst($ev->getName());
                $eid = $ev->getId();

                $content = $cs->getByEventId($eid);

                $img = $rc->retrieveImage($content->getId());

                echo "<h2 class='events'>$en</h2>";

                if (!empty($img)){
                    $id = $img->getId();
                    $name = $img->getName();

                    echo "<img id='eventImg' src='uploads/uploadedIMG/$id-$name'/>";
                }

                echo "<a class='events' id='eventMore' href='/eventPage.php?event=$eid'>More...</a>";
                ?>
            </article>
                <?php
            }
            ?>

        </section>
    </body>
    <?php
    require_once ("footer.php");
    ?>
</html>
