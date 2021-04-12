<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/User.php';

// Handle form submission
if ($_POST) {
    // Clear any exiting error message
    unset($_SESSION["createError"]);

    if (
        !empty($_POST["firstname"]) &&
        !empty($_POST["lastname"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["password"]) &&
        isset($_SESSION["userType"])
    ) {
        // Make sure the logged in user is a superadmin
        if ((int)$_SESSION["userType"] == UserTypes::SUPERADMIN) {
            try {
                // Create the new user
                $user = new User();

                $user->setFirstname((string)htmlentities($_POST["firstname"]));
                $user->setLastname((string)htmlentities($_POST["lastname"]));
                $user->setEmail((string)htmlentities($_POST["email"]));
                $user->setPassword((string)htmlentities($_POST["password"]));
                $user->setUsertype((int)htmlentities($_POST["usertype"]));

                $userService = new UserService();

                if ($userService->create($user)) {
                    echo "Succesfully created user";
                } else {
                    header("Location: /user/create");
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION["createError"] = "An error occured, try again please".$e;
                header("Location: /user/create");
                exit;
            }
        }
    } else {
        $_SESSION["editError"] = "Not all information was filled in";
        header("Location: /user/create");
        exit;
    }
} else {
    require __DIR__.'/../views/createUser.php';
}

?>
