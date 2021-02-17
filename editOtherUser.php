<?php
//A bit of weird code here. I need to set the Session var userIdToEdit, so i do that in this separate script so it can be properly set when the
//edit button was pressed on the view users page
require_once ("models/UserTypes.php");
require_once ("services/UserService.php");
if($_POST){
    session_start();
    if(!empty($_POST["id"]) &&
        isset($_SESSION["userId"]) &&
        isset($_SESSION["userType"]))
    {
        $userType = (new UserService())->getTypeById((int)(htmlentities($_POST["id"])));

        //Make sure the logged in in user has permission
        if((int)$_SESSION["userType"] >= UserTypes::ADMIN && !is_null($userType) &&
            $userType < (int)$_SESSION["userType"])
        {
            $_SESSION["userIdToEdit"] = (string)htmlentities($_POST["id"]);
            unset($_SESSION["editError"]);
            header("Location: edit.php");
        }
        else
            echo "<h2>Insufficient permissions</h2>";
    }
    else
        echo "<h2>Not all info was filled in</h2>";
}
?>