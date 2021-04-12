<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/PasswordService.php';
require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/ResetKey.php';
require_once __DIR__.'/../models/User.php';

// Form has been filled in
if ($_POST) {
    unset($_SESSION["createPasswordError"]);
    
    // Session vars are secure, so no need to check a key again
    if (!empty($_SESSION["idToResetPassword"]) &&
        !empty($_POST["newPassword"]) &&
        !empty($_POST["verifyPassword"])
    ) {
        $userService = new UserService();

        // Change the password
        if (
            $userService->changePassword((int)htmlentities($_SESSION["idToResetPassword"]),
            (string)htmlentities($_POST["newPassword"]),
            (string)htmlentities($_POST["verifyPassword"]))
        ) {
            echo "Password changed! <a href='/login'>Login</a>";
        } else {
            echo "Something went wrong here";
        }
    } else {
        if (!empty($_POST["resetLink"]))
            echo "Something went wrong, <a href='".htmlentities((string) $_POST["resetLink"])."> Try again </a>";
        else
            echo "Something went wrong, please use your link again";
    }
} else {
    require __DIR__.'/../views/resetPasswordHandler.php';
}

?>
