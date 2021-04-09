<?php

require_once ("services/EventService.php");
require_once ("services/RestaurantService.php");
require_once ("models/Restaurant.php");
require_once ("EventSchedule.php");
require_once ("models/FoodType.php");
require_once ("models/UserTypes.php");
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
        case USERTYPES::VOLUNTEER:
        case UserTypes::SUPERADMIN:
            echo "<a id='editPageBtn' href='editEventPage.php?event=".$eventID."'>Edit Page</a>";
            break;
    }
}

$img = $rc->retrieveImage($content->getId());
$image = "";
if (!empty($img)){
    $image = "uploads/uploadedIMG/".$img->getId()."-".$img->getName();
}


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
                    <a id="thursday" onclick="daySchedule(this, 'Thursday')">
                        Thursday
                    </a>
                </li>
                <?php } ?>
                <li>
                    <a id="friday" onclick="daySchedule(this, 'Friday')">
                        Friday
                    </a>
                </li>
                <li>
                    <a id="saturday" onclick="daySchedule(this, 'Saturday')">
                        Saturday
                    </a>
                </li>
                <li>
                    <a id="sunday" onclick="daySchedule(this, 'Sunday')">
                        Sunday
                    </a>
                </li>
            </ul>
        </nav>
        <?php
    }
    ?>

    <script>
        const hash = location.hash.length === 0 ? [] : location.hash.substring(1).split(',');
        const pairs = {};

        for (const part of hash) {
            if (part.length === 0) continue;
            const pair = part.split('=');

            pairs[pair[0]] = pair[1];
        }

        function updateHash() {
            let hash = "#";

            for (const key in pairs) {
                if (hash.length !== 1) hash += ',';
                hash += `${key}=${pairs[key]}`;
            }

            location.hash = hash;
        }

        function daySchedule(self, day) {
            const eventID = `<?php echo $eventID ?>`;
            const body = new FormData();

            for (const child of self.parentElement.parentElement.children) {
                child.classList.remove("active");
            }

            self.parentElement.classList.add("active");
            pairs.day = day;
            updateHash();

            body.append("day", day);
            body.append("eventID", eventID);

            fetch("getDayScheduleMusic.php", {
                method: "POST",
                body,
            }).then(async (res) => {
                const daySchedule = document.getElementById("daySchedule");
                const header = daySchedule.firstElementChild;

                daySchedule.removeChild(header);
                daySchedule.innerHTML = await res.text();
                daySchedule.prepend(header);
            });
        }

        <?php

        if ($eventName == "Jazz" || $eventName == "Dance")
            echo 'if (pairs.day) daySchedule(document.getElementById(pairs.day.toLowerCase()), pairs.day);';

        ?>
    </script>

    <?php
    // Restaurant list
    if ($eventName == "Food") { ?>
        <article id ="restaurantList">
            <header>
                <h2>Restaurants</h2>
            </header>
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
                        <td> <?php echo FoodType::getType($restaurant->getFoodType()); ?> </td>
                    </tr>
                <?php } ?>
            </table>
            <br>
            <?php if (isset($_SESSION["userType"])) {
                switch ((int)$_SESSION['userType']){
                    case 2:
                        ?>
                        <form action="addRestaurant.php" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <p>
                                    Add a restaurant:
                                </p>
                                <p>
                                    <label> Name: </label>
                                    <input name="name" type="text" required>
                                    <br><br><label> Location: </label>
                                    <input name="location" type="text" required>
                                    <br><br><label> Price: </label>
                                    <input name="price" type="text" required>
                                    <br><br><label> Food type (French, Dutch, etc): </label>
                                    <input name="foodType" type="text" required>
                                    <br><br><input type="submit" name="submit" value="submit" required>
                                </p>
                            </fieldset>
                        </form>
                        <?php
                        break;
                }
            }
            ?>
        </article>
    <?php } ?>

    <article id="daySchedule">
        <?php if ($eventName != "Food"){ ?>
        <header>
            <h2>Schedule</h2>
        </header>
        <?php }

            // get right schedule per event
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
        pairs.tab = "restaurants";
        <?php } else { ?>
        document.getElementById("scheduleNav").className = "active";
        pairs.tab = "schedule";
        <?php } ?>

        updateHash();
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

        pairs.tab = "about";
        updateHash();
    }

    switch (pairs.tab) {
        case "schedule": hideAbout(); break;
        case "restaurants": hideAbout(); break;
        default: hideSchedule();
    }
</script>
<?php
require_once ("footer.php");
?>
</body>
</html>
