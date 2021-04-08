<?php
if(!isset($_SESSION)) session_start();

require_once("services/EventService.php");
require_once("services/UserService.php");
require_once("services/TicketService.php");

if(isset($_SESSION["userId"])) {

    $ts = new TicketService();
    $es = new EventService();
    $us = new UserService();

    $ticketsWithCount = new TicketWithCount();


} else {
    header("Location: ./");
}

?>

<html lang="en">
<head>
    <title>Cart</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="css/account.css">
</head>
<body>
<?php require_once("menubar.php") ?>

<section>
    <article id="tickets">
    </article>
</section>

</script>
</body>
</html>
