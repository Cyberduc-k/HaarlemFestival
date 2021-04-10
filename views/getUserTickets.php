<?php
if(!isset($_SESSION)) session_start();
require_once("services/TicketService.php");

header("Content-Type: application/json");
$service = new TicketService();

$tickets = $service->getAllForCart(9);

foreach ($tickets as $twc){
    echo $twc->getId();
}


print_r(array_values($tickets));


