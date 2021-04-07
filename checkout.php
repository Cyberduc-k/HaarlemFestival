<?php


// TODO: checkout.php vervangen, maar heb hem aangehouden als makkelijke test voor de API calls
if (!isset($_SESSION)) session_start();

require_once("services/PaymentService.php");


if(isset($_POST["submitButton"])) {
    database_write();
}
?>


<html lang="en">
<form name="testForm" action="checkout.php" method="post">
    <input type="submit" name="submitButton">
</form>
</html>
