<?php

require_once ("services/EventService.php");
require_once ("services/RestaurantService.php");
require_once ("models/Restaurant.php");
require_once ("EventSchedule.php");;
require_once "retreiveContent.php";
require_once "services/ContentService.php";

$schedule = new EventSchedule();
$es = new EventService();
$rs = new RestaurantService();

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
        switch ((int)$_SESSION['userType']){
            case 2:
                echo "<a id='editPageBtn' href='editEventPage.php?event=".$eventID."'>Edit Page</a>";
                break;
        }
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
            <a href="tickets.php?event=<?php echo $eventID; ?>">
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

    <?php
    // Restaurant list
    if ($eventName == "Food") { ?>
    <article id ="restaurantList">
        <table>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>FoodType</th>
            </tr>
            <?php
            $restaurants = $rs->getAll();
            foreach ($restaurants as $restaurant) { ?>
            <tr>
                <td> <?php echo $restaurant->getName(); ?> </td>
                <td> <?php echo $restaurant->getLocation(); ?> </td>
                <td> <?php echo $restaurant->getFoodType(); ?> </td>
            </tr>
            <?php } ?>
        </table>
        <form action="addRestaurant.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <p>
                    Add a restaurant:
                </p>
                <p>
                    <input name="name" type="text" required>
                    <input name="location" type="text" required>
                    <input name="foodType" type="text" required>
                    <input type="submit" name="submit" value="Upload" required>
                </p>
            </fieldset>
        </form>
    </article>
    <?php } ?>

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

        <?php if ($eventName == "Food") { ?>
        document.getElementById("restaurantsNav").className = "active";
        y.id = "";
        location.hash = "restaurants";
        y.id = "schedule";
        <?php } else { ?>
        document.getElementById("scheduleNav").className = "active";
        y.id = "";
        location.hash = "schedule";
        y.id = "schedule";
        <?php } ?>
    }

    function hideSchedule() {
        const x = document.getElementById("schedule");
        const y = document.getElementById("about");

        x.style.display = "none";
        y.style.display = "block";

        document.getElementById("aboutNav").className = "active";

        <?php if ($eventName == "Food") { ?>
        document.getElementById("restaurantsNav").className = "";
        <?php } else { ?>
        document.getElementById("scheduleNav").className = "";
        <?php } ?>

        y.id = "";
        location.hash = "about";
        y.id = "about";
    }

    const prevPage = location.hash;

    if (prevPage.length == 0)
        hideSchedule();
    else {
        switch (prevPage) {
            case "#schedule": hideAbout(); break;
            case "#restaurants": hideAbout(); break;
            default: hideSchedule();
        }
    }
</script>

</body>
</html>
