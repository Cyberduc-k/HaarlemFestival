<?php

require_once __DIR__.'/../models/UserTypes.php';
require_once __DIR__.'/../models/Event.php';
require_once __DIR__.'/../services/EventService.php';

$es = new EventService();
$events = $es->getAll();

// Only start a session when necessary
if (!isset($_SESSION)) session_start();

// A function to add class=active to the appropriate menu bar item
function getActiveString(string $page): string {
    $parsed_url = parse_url($_SERVER['REQUEST_URI']);
    $path = $parsed_url['path'] ?? "";

    if (preg_match_all('/(\*)/', $page, $matches, PREG_PATTERN_ORDER)) {
        $replacements = [];

        foreach ($matches[1] as $part) {
            $replacements[] = '([^/]+)';
        }

        $matcher = '/^'.str_replace('/', '\/', str_replace($matches[0], $replacements, $page)).'$/';

        if (preg_match($matcher, $path))
            return "class='active'";
    }

    if ($path === $page)
        return "class='active'";

    return "";
}

// Show the menubar
echo '<header id="menubar">';
echo '<a href="/" id="logo">' . file_get_contents('css/Logo.svg') . '</a>';
echo '<ul><li><a href="" class="icon instagram"></a></li><li><a href="" class="icon facebook"></a></li>';

if (isset($_SESSION['userType'])) {
    echo '<li><a href="/account" ' . getActiveString("/account") . '>Account</a></li></ul>';
} else {
    echo '<li><a href="/login" ' . getActiveString("/login") . '>Login</a></li></ul>';
}

echo '<ul><li><a '.getActiveString("/").' href="/">Home</a></li>';

foreach ($events as $ev) {
    //get the event name and page name
    $eln = $ev->getName();
    $en = ucfirst($eln);

    //create working menuitem for each event
    echo '<li><a ' . getActiveString("/event/$eln") . ' href="/event/'.$eln.'">'.$en.'</a></li>';
}

if (isset($name) && isset($event))
    echo '<li><a ' . getActiveString("/tickets/*") . ' href="/tickets/'.$name.'">Tickets</a></li>';
else
    echo '<li><a href="/tickets/food">Tickets</a></li>';

// first validate if user is logged in, only then allow access
if (isset($_SESSION['userType'])) {
    switch ((int)$_SESSION['userType']) {
        // There is no break so that the "lower" admin links will always show
        case UserTypes::SUPERADMIN:
            echo "<li><a ".getActiveString("/user/create"). " href='/user/create'>Create user</a></li>";
            echo "<li><a ".getActiveString("/event/add"). " href='/event/add'>Add Page</a></li>";
            echo "<li><a ".getActiveString("/api/keys")." href='/api/keys'>Manage API keys</a></li>";
            echo "<li><a ".getActiveString("/export")." href='/export'>Export data</a></li>";

        case UserTypes::VOLUNTEER:
            echo "<li><a ".getActiveString("/invoice/create"). " href='/invoice/create'>Create Invoice</a></li>";
            echo "<li><a ".getActiveString("/users")." href='/users'>View users</a></li>";
            break;
    }
    
    echo "<li class='right'><a href='/logout.php'>Logout</a></li>";
}

echo "</ul></header>";
    
?>
