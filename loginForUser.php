<?php

require_once ("models/User.php");
require_once ("services/UserService.php");

if ($_POST) {
    if (!isset($_SESSION)) session_start();

    if (!empty($_POST["id"]) && isset($_SESSION["userType"])) {
        $userService = new UserService();
        $userType = $userService->getTypeById((int)(htmlentities($_POST["id"])));

        // Make sure the logged in in user has a higher usertype
        if (
            (int)$_SESSION["userType"] >= UserTypes::ADMIN && !is_null($userType) &&
            $userType < (int)$_SESSION["userType"]
        ) {
            $user = $userService->getById((int)(htmlentities($_POST["id"])));
            
            if (!is_null($user)) {
                // Log in as that user by setting all the session variables used to track if a user is logged in
                session_destroy();
                session_start();
                $_SESSION["firstname"] = $user->getFirstname();
                $_SESSION["userId"] = $user->getId();
                $_SESSION["userType"] = $user->getUsertype();
                $_SESSION["email"] = $user->getEmail();
                header("Location: home.php");
            } else {
                echo "<h2>We cannot find a user with that id</h2>";
            }
        } else {
            echo "<h2>Insufficient permissions</h2>";
        }
    } else {
        echo "<h2>Not all info was filled in</h2>";
    }
}

?>