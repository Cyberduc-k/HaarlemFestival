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
echo '<header id="menubar">';
echo '<img src="css/Logo.png" />';
echo '<ul><li><a href="" class="icon instagram"></a></li><li><a href="" class="icon facebook"></a></li>';

if (isset($_SESSION['userType'])) {
    echo '<li><a href="account.php">Account</a></li></ul>';
} else {
    echo '<li><a href="login.php">Login</a></li></ul>';
}

echo '<ul><li><a '.getActiveString("home").' href="home.php">Home</a></li>';

foreach ($events as $ev) {
    //get the event name and page name
    $en = ucfirst($ev->getName());
    $eid = $ev->getId();

    //create working menuitem for each event
    echo "<li><a ".getActiveString("eventPage")." 
    href='eventPage.php?event=$eid'>$en</a></li>";
}

echo '<li><a href="ticketPage.php">Tickets</a></li>';
echo '<li><a href="contact.php">Contact</a></li>';

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

echo "</ul></header>";
    
?>
