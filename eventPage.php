<html lang="en">
<head>
    <?php
    require_once ("menubar.php");
    require_once ("services/EventService.php");

    $es = new EventService();

    if (isset($_GET["event"]))
    {
        $eventID = $_GET["event"];
        $event = $es->getById($eventID);
    }
    else{
        header("Location: home.php");
    }

    $eventName = ucfirst($event->getName());

        echo "<title>$eventName</title>"
    ?>

    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php

    echo "<header>
    <h1>$eventName</h1>
        </header>";
?>


<nav>
    <ul>
        <li>
            <a onclick="hideSchedule()">
                About
            </a>
        </li>
        <li>
            <a onclick="hideAbout()">
                Schedule
            </a>
        </li>
        <li>
            <a href="home.php">
                Tickets
            </a>
        </li>
    </ul>
</nav>

<section id="about">
    <article>
        <?php
        require_once "retreiveContent.php";
        require_once "services/ContentService.php";

        // content halen uit db
        $rc = new retrieveContent();
        $content =  $rc->retrieve($eventID);
        $header = $content->getHeader();

        echo " <header>
            <h3>$header</h3>
            </header>";

        echo $content->getText();

        ?>
    </article>
</section>

<section id="schedule">
    <article>
        <header>
            <h3>
                Schedule
            </h3>
        </header>

        <?php
            require_once ("EventSchedule.php");

            $schedule = new EventSchedule();

            switch ($eventName){
                case "Food":
                    break;
                case "Historic":
                    $schedule->getHistoricSchedule();
                    break;
                case "Jazz":
                case "Dance":
                    $schedule->getMusicEventSchedule($eventID);
                    break;
            }

        ?>

    </article>
</section>

<script>
    function hideAbout() {
        var x = document.getElementById("about");
        var y = document.getElementById("schedule");

        x.style.display = "none";
        y.style.display = "block";
    }

    function hideSchedule() {
        var x = document.getElementById("schedule");
        var y = document.getElementById("about");

        x.style.display = "none";
        y.style.display = "block";
    }

    hideSchedule();
</script>

</body>
</html>
