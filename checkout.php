<?php
// TODO: checkout.php vervangen, maar heb hem aangehouden als makkelijke test voor de API calls

require_once("services/PaymentService.php");
require_once("validate.php");


if(isset($_POST["submitButton"])) {
    database_write();
}
?>


<html lang="en">
<head>
    <title>Check out</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/tickets.css" />
</head>
<form name="ServiceAgreement" action="checkout.php" method="post">
    <input type="submit" name="submitButton">
</form>
</html>
