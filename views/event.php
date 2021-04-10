<html lang="en">
<head>
    <title><?php echo $eventName; ?></title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/eventPage.css" />
</head>
<body class="<?php echo $event->getColour(); ?>">
    <?php require __DIR__.'/menubar.php'; ?>
    
    <header id="header" style="background-image: url('')">
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
            <li id="aboutNav">
                <a onclick="showAbout()">About</a>
            </li>
            <?php if ($name == "food") { ?>
                <li id="restaurantsNav">
                    <a onclick="showRestaurants()">Restaurants</a>
                </li>
            <?php } else { ?>
                <li id="scheduleNav">
                    <a onclick="showSchedule()">Schedule</a>
                </li>
            <?php } ?>
            <li id="ticketsHav">
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

    <?php
        if ($name == "Food") {
        } else {
        }
    ?>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html>
