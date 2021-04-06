<?php

require_once ("services/EventService.php");
require_once ("EventSchedule.php");
require_once "retreiveContent.php";
require_once "services/ContentService.php";

$schedule = new EventSchedule();
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

// content halen uit db
$rc = new retrieveContent();
$content =  $rc->retrieve($eventID);

?>

<html lang="en">
<head>
    <title><?php echo $eventName; ?></title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/eventPage.css" />
</head>
<body class="<?php echo $event->getColour(); ?>" >
<?php
    require_once ("menubar.php");

    if (isset($_SESSION["userType"])) {
        echo "<a href='editEventPage.php?event=".$eventID."'>Edit Page</a>";
    }

    $image = $content->getImagePath();

    echo <<<END
        <header id="header" style="background-image: url('$image')">
            <h1>$eventName</h1>
        </header>
    END;
?>

<nav>
    <ul>
        <li id="aboutNav">
            <a onclick="hideSchedule()">
                About
            </a>
        </li>
        <?php
        if ($eventName == "Food"){ ?>
        <li id="restaurantsNav">
            <a onclick="hideAbout()">
                Restaurants
            </a>
        </li>
        <?php } else{ ?>
        <li id="scheduleNav">
            <a onclick="hideAbout()">
                Schedule
            </a>
        </li>
        <?php
        }
        ?>
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
            $header = $content->getHeader();

            echo <<<END
                <header>
                    <h2>$header</h2>
                </header>
            END;

            echo $content->getText();

        ?>
    </article>
</section>

<section id="schedule">
        <?php if ($eventName == "Jazz" || $eventName == "Dance"){
            ?>
        <nav id="days">
            <ul>
                <?php if ($eventName == "Jazz"){ ?>
                <li>
                    <a onclick="daySchedule('Thursday')">
                        Thursday
                    </a>
                </li>
                <?php } ?>
                <li>
                    <a onclick="daySchedule('Friday')">
                        Friday
                    </a>
                </li>
                <li>
                    <a onclick="daySchedule('Saturday')">
                        Saturday
                    </a>
                </li>
                <li>
                    <a onclick="daySchedule('Sunday')">
                        Sunday
                    </a>
                </li>
            </ul>
        </nav>
    <?php
        }
    ?>

    <script>
        function hideDays(){
            const x = document.getElementById("days");

            x.style.display = "none";
        }

        function daySchedule(day) {
            const eventID = `<?php echo $eventID ?>`;
            console.log(day);

            const body = new FormData();

            body.append("day", day);
            body.append("eventID", eventID);

            fetch("getDayScheduleMusic.php", {
                method: "POST",
                body,
            }).then(async (res) => {
                document.getElementById("daySchedule").innerHTML = await res.text();
            });
        }
    </script>

    <header>
        <?php
            if ($eventName == "Food")
            { ?>
            <h2>
                Restaurants
            </h2>
        <?php
            }else{ ?>
                <h2>
                    Schedule
                </h2>
        <?php
            }
        ?>

    </header>

    <article id="daySchedule">

        <?php

            switch ($eventName){
                case "Food":
                    break;
                case "Historic":
                    $schedule->getHistoricSchedule();
                    break;
                case "Jazz":
                    $schedule->musicEvent($eventID, "Thursday");
                    break;
                case "Dance":
                    $schedule->musicEvent($eventID, "Friday");
                    break;
            }
        ?>

    </article>
</section>

<script>
    function hideAbout() {
        const x = document.getElementById("about");
        const y = document.getElementById("schedule");

        x.style.display = "none";
        y.style.display = "block";

        document.getElementById("aboutNav").className = "";
        document.getElementById("scheduleNav").className = "active";
        location.hash = "schedule";
    }

    function hideSchedule() {
        const x = document.getElementById("schedule");
        const y = document.getElementById("about");

        x.style.display = "none";
        y.style.display = "block";

        document.getElementById("aboutNav").className = "active";
        document.getElementById("scheduleNav").className = "";
        location.hash = "about";
    }

    const prevPage = location.hash;

    if (prevPage.length == 0)
        hideSchedule();
    else {
        switch (prevPage) {
            case "#schedule": hideAbout(); break;
            default: hideSchedule();
        }
    }
</script>

</body>
</html>
