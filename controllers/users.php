<?php

if (!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/UserService.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserTypes.php';

if (!isset($_SESSION['userType'])) {
    header("Location: /login");
    exit;
}

if ($_SESSION['userType'] == UserTypes::CLIENT) {
    echo "<h1>You do not have permission to access this page";
    exit;
}

const VALID_KEYS = ['firstname', 'lastname', 'email', 'registerDate'];

$userService = new UserService();

if (!empty($_POST['firstname']) || !empty($_POST['lastname']) ||
    !empty($_POST['email']) || !empty($_POST['registerDate'])
) {
    $args = [];

    foreach ($_POST as $key => $value) {
        if (in_array($key, VALID_KEYS))
            $args[htmlentities((string)$key)] = htmlentities((string)$value);
    }

    $users = $userService->getWithArgs($args);
} else {
    $users = $userService->getAll();
}

if (is_null($users)) $users = [];

require __DIR__.'/../views/users.php';
