<?php

// Script to check if a user has access to a page
require_once ("models/UserTypes.php");

if (!isset($_SESSION)) session_start();

if (
    isset( $_SESSION['userType']) && isset( $_SESSION['email']) &&
    isset($_SESSION['firstname']) && isset( $_SESSION['userId'])
) {
    $userType = (int) $_SESSION['userType'];

    // Verify if the access level of the current page is the same or lower as that of the user
    switch ($userType) {
        case 2: break;
        case 1:
            if (!(UserTypes::ACCESSLEVELS[basename($_SERVER['PHP_SELF'])] <= UserTypes::VOLUNTEER)) {
                invalidUser("Superadmin");
            }

            break;
        default:
            if (!(UserTypes::ACCESSLEVELS[basename($_SERVER['PHP_SELF'])] == UserTypes::CLIENT)) {
                invalidUser("Volunteer");
            }

            break;
    }
} else {
    invalidUser("Client");
}

// Show a message to the user based on what level of user he should be
function invalidUser(String $minimumUserLevel) {
    echo "This is a ".$minimumUserLevel."s only page! Please <a href='logout.php'>Login</a> as a ".$minimumUserLevel." first";
    exit;
}

?>
