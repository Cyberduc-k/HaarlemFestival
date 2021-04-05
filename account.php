<?php
if(!isset($_SESSION)) session_start();
?>
<html lang="en">
    <head>
        <title>Account</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body>
    <?php

    require_once("menubar.php");
    require_once("services/UserService.php");

    $userService = new UserService();

    echo "<h1 style='float: left'>Welcome " . $_SESSION['firstname'] . "!</h1>";
    echo "<img class='avatar' src=".$userService->getAvatarByEmail($_SESSION["email"])." alt='avatar' style='float: left; margin: 40px 0px 0px 40px'>";

    ?>
    </body>
</html>

