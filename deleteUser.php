<?php
require_once ("models/User.php");
require_once ("services/UserService.php");
//Used to delete a user
if($_POST){
    if(!isset($_SESSION)) session_start();

    if(!empty($_POST["id"]) &&
        isset($_SESSION["userType"]))
    {
        $userService = new UserService();
        $userType = $userService->getTypeById((int)(htmlentities($_POST["id"])));

        //Make sure the logged in in user has a higher usertype and that it is a superadmin
        if((int)$_SESSION["userType"] == UserTypes::SUPERADMIN && !is_null($userType) &&
            $userType < (int)$_SESSION["userType"])
        {
            //Delete the user
            if($userService->delete((int)(htmlentities($_POST["id"])))){
                echo "<h2>User successfully deleted! <a href='viewUsers.php'>View list</a>";
            }
            else
                echo "<h2>We couldnt delete the user, sorry</h2>";
        }
        else
            echo "<h2>Insufficient permissions</h2>";
    }
    else
        echo "<h2>Not all info was filled in</h2>";
}
?>