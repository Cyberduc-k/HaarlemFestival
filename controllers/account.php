<?php

if(!isset($_SESSION)) session_start();

require_once __DIR__.'/../services/UserService.php';

$userService = new UserService();
$avatar = $userService->getAvatarByEmail($_SESSION['email']);
$firstName = $_SESSION['firstname'];

require __DIR__.'/../views/account.php';

?>
