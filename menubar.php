<?php

require_once ("models/UserTypes.php");
require_once ("models/Event.php");
require_once ("services/EventService.php");

$es = new EventService();
$events = $es->getAll();

// Only start a session when necessary
if (!isset($_SESSION)) session_start();

// A function to add class=active to the appropriate menu bar item
function getActiveString(string $page): string {
    if ($page.".php" == basename($_SERVER['PHP_SELF']))
        return "class='active'";

    return "";
}

// A function to add class=active to the appropriate menu bar item
function getActiveStringEvent(int $event): string {
    if (isset($_GET["event"]) && (int)$_GET["event"] == $event)
        return getActiveString("eventPage");

    return "";
}

// Show the menubar
echo '<header id="menubar">';
echo '<a href="./" id="logo">' . file_get_contents('css/Logo.svg') . '</a>';
echo '<ul><li><a href="" class="icon instagram"></a></li><li><a href="" class="icon facebook"></a></li>';

if (isset($_SESSION['userType'])) {
    echo '<li><a href="account.php" ' . getActiveString("account") . '>Account</a></li></ul>';
} else {
    echo '<li><a href="login.php" ' . getActiveString("login") . '>Login</a></li></ul>';
}

echo '<ul><li><a '.getActiveString("index").' href="./">Home</a></li>';

foreach ($events as $ev) {
    //get the event name and page name
    $en = ucfirst($ev->getName());
    $eid = $ev->getId();

    //create working menuitem for each event
    echo "<li><a " . getActiveStringEvent($eid) . " 
    href='eventPage.php?event=$eid'>$en</a></li>";
}

echo '<li><a ' . getActiveString("tickets") . ' href="tickets.php?event=2">Tickets</a></li>';
echo '<li><a href="contact.php">Contact</a></li>';

// first validate if user is logged in, only then allow access
if (isset($_SESSION['userType'])) {
    
    switch ((int)$_SESSION['userType']) {
        // There is no break so that the "lower" admin links will always show
        case 2:
            echo "<li><a ".getActiveString("create"). " href='create.php'>Create user</a></li>";
            echo "<li><a ".getActiveString("addDeleteEventPage"). " href='addDeleteEventPage.php'>Add Page</a></li>";

        case 1:
            echo "<li><a ".getActiveString("viewUsers")." href='viewUsers.php'>View users</a></li>";
            echo "<li><a ".getActiveString("export")." href='export.php'>Export data</a></li>";
            echo "<li><a ".getActiveString("viewApiKeys")." href='viewApiKeys.php'>Manage API keys</a></li>";
            break;
    }
    
    echo "<li class='right'><a href='logout.php'>Logout</a></li>";
}

echo "</ul></header>";
    
?>
