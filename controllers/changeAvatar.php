<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/User.php';

if (!isset($_SESSION['email'])) {
    header("Location: /login");
    exit;
}

$userService = new UserService();

if ($_POST) {
    // Clear any exiting error message
    unset($_SESSION["avatarError"]);

    if (
        isset($_SESSION["userId"]) &&
        isset($_SESSION["userType"]) &&
        !empty($_FILES["avatar"]["name"])
    ) {
        if ($userService->setAvatar((int)$_SESSION["userId"], $_FILES["avatar"])) {
            echo "<h2>Succesfully changed avatar</h2>";
        } else{
            header("Location: /user/change_avatar");
            exit;
        }
    } else {
        $_SESSION["avatarError"] = "Please select an image first";
        header("Location: /user/change_avatar");
        exit;
    }
} else {
    $image = $userService->getAvatarByEmail($_SESSION['email']);

    require __DIR__.'/../views/changeAvatar.php';
}
