<?php

require_once ("models/UserTypes.php");
require_once ("models/Event.php");
require_once ("services/EventService.php");

$es = new EventService();
$events = $es->getAll();

// Only start a session when necessary
if (!isset($_SESSION)) session_start();

// A function to add class=active to the appropriate menu bar item
function getActiveString(String $page): String {
    if ($page.".php" == basename($_SERVER['PHP_SELF']))
        return "class='active'";

    return "";
}

// Show the menubar
echo "<ul>";
echo "<li><a ".getActiveString("home")." href='home.php'>Home</a></li>";

foreach ($events as $event)
{
    //get the event name and page name
    $eventName = ucfirst($event->getName());
    $eventID = $event->getId();
    $eventPage = $event->getName()."Page";

    //create working menuitem for each event
    echo "<li><a ".getActiveString("eventPage")." 
    href='eventPage.php?event=$eventID'>$eventName</a></li>";
}

// first validate if user is logged in, only then allow access
if (isset($_SESSION['userType'])) {
    echo "<li><a ".getActiveString("edit")." href='edit.php?type=own'>Edit my information</a></li>";
    echo "<li><a ".getActiveString("changeAvatar")." href='changeAvatar.php'>Change Avatar</a></li>";
    
    switch ((int)$_SESSION['userType']) {
        // There is no break so that the "lower" admin links will always show
        case 2:
            echo "<li><a ".getActiveString("create"). " href='create.php'>Create user</a></li>";
        case 1:
            echo "<li><a ".getActiveString("viewUsers")." href='viewUsers.php'>View users</a></li>";
            break;
    }
    
    echo "<li class='right'><a href='logout.php'>Logout</a></li>";
}

echo "</ul>";
    
?>
