<html lang="en">
    <head>
        <title>Home</title>
        <link type="text/css" rel="stylesheet" href="/css/style.css" />
        <link type="text/css" rel="stylesheet" href="/css/home.css" />
    </head>
    <body>
        <?php require __DIR__.'/menubar.php'; ?>

        <main>
            <header id="header">
                <h1>Haarlem Festival</h1>
            </header>

            <section id="event">
                <?php foreach ($events as $ev) { ?>
                    <article>
                        <?php
                            $eventName = $ev->getName();
                            $en = ucfirst($eventName);
                            $eid = $ev->getId();

                            $content = $cs->getByEventId($eid);

                            $img = $rc->retrieveImage($content->getId());

                            echo "<h2 class='events'>$en</h2>";

                            if (!empty($img)){
                                $id = $img->getId();
                                $name = $img->getName();

                                echo "<img id='eventImg' src='uploads/uploadedIMG/$id-$name'/>";
                            }

                            echo "<a class='events' id='eventMore' href='/event/$eventName'>More...</a>";
                        ?>
                    </article>
                <?php } ?>
            </section>
        </main>

        <?php require_once __DIR__.'/footer.php'; ?>
    </body>
</html>
