<?php if(!isset($_SESSION)) session_start(); ?>

<html lang="en">
    <head>
        <title>Home</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <?php
        
        $page = "home";
        
        require_once ("validate.php");
        require_once ("menubar.php");
        require_once ("services/UserService.php");

        $userType = (int) $_SESSION['userType'];
        $userService = new UserService();

        // Show a basic welcome message and the users avatar
        echo "<h1 style='float: left'>Welcome to the ".UserTypes::getType($userType)."'s area, " . $_SESSION['firstname'] . "!</h1>";
        echo "<img class='avatar' src=".$userService->getAvatarByEmail($_SESSION["email"])." alt='avatar' style='float: left; margin: 40px 0px 0px 40px'>";
        
        ?>
    </body>
</html>
