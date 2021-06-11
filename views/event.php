<html lang="en">
<head>
    <title><?php echo $eventName; ?></title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/eventPage.css" />
    <script src="/js/hash.js"></script>
</head>
<body class="<?php echo $event->getColour(); ?>">
    <?php require __DIR__.'/menubar.php'; ?>
    
    <main>
        <header id="header" style="background-image: url('<?php echo $image; ?>')">
            <?php
                if (isset($_SESSION['userType']))
                    switch ($_SESSION['userType']) {
                        case UserTypes::VOLUNTEER:
                        case UserTypes::SUPERADMIN:
                            echo '<a id="editPageBtn" href="/event/'.$name.'/edit">Edit Page</a>';
                    }
            ?>
            <h1><?php echo $eventName; ?></h1>
        </header>

        <nav>
            <ul>
                <li>
                    <a id="about-tab" onclick="setTab(this, 'about')">About</a>
                </li>
                <?php if ($name == "food") { ?>
                    <li>
                        <a id="restaurants-tab" onclick="setTab(this, 'restaurants')">Restaurants</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a id="schedule-tab" onclick="setTab(this, 'schedule')">Schedule</a>
                    </li>
                <?php } ?>
                <li>
                    <a href="/tickets/<?php echo $name; ?>">Tickets</a>
                </li>
            </ul>
        </nav>

        <section id="about">
            <article>
                <header>
                    <h2><?php echo $content->getHeader(); ?></h2>
                </header>

                <?php echo $content->getText(); ?>
            </article>
        </section>

        <?php if ($name != "food") { ?>
            <section id="schedule">
                <?php if ($name == "jazz" || $name == "dance") { ?>
                    <nav id="days">
                        <ul>
                            <?php if ($name == "jazz") { ?>
                                <li>
                                    <a id="thursday" onclick="setDay(this, 'thursday')">Thursday</a>
                                </li>
                            <?php } ?>
                            <li>
                                <a id="friday" onclick="setDay(this, 'friday')">Friday</a>
                            </li>
                            <li>
                                <a id="saturday" onclick="setDay(this, 'saturday')">Saturday</a>
                            </li>
                            <li>
                                <a id="sunday" onclick="setDay(this, 'sunday')">Sunday</a>
                            </li>
                        </ul>
                    </nav>
                <?php } ?>

                <article id="daySchedule">
                    <header>
                        <h2>Schedule</h2>
                    </header>

                    <?php
                        if ($name == "historic") {
                            $schedule->getHistoricSchedule();
                        }
                    ?>
                </article>
            </section>

            <script>const eventID = <?php echo $event->getId(); ?></script>
            <script src="/js/event-schedule.js"></script>

            <script>
                function initDay() {
                    <?php if (in_array($name, ['jazz', 'dance'])) { ?>
                        switch (getHash("day")) {
                            case undefined:
                                const d = document.getElementById("thursday");
                                if (d) setDay(d, "thursday");
                                else setDay(document.getElementById("friday"), "friday");
                                break;
                            default:
                                setDay(document.getElementById(getHash("day")), getHash("day"));
                                break;
                        }
                    <?php } ?>
                }
            </script>
        <?php } else { ?>
            <section id="restaurants">
                <article>
                    <header>
                        <h2>Restaurants</h2>
                    </header>

                    <table id="addRestaurant">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($restaurants as $restaurant) { ?>
                                <tr>
                                    <td id="td"><?php echo $restaurant->getName(); ?></td>
                                    <td id="td"><?php echo $restaurant->getLocation(); ?></td>
                                    <td id="td"><?php echo FoodType::getType($restaurant->getFoodType()); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </article>
            </section>
        <?php } ?>
    </main>

    <script src="/js/event.js"></script>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
