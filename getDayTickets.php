<?php

require_once("services/TicketService.php");
require_once("services/RestaurantService.php");
require_once("models/FoodType.php");

header("Content-Type: application/json");

$ts = new TicketService();
$rs = new RestaurantService();

if (isset($_POST["day"]) && isset($_POST["eventID"])) {
    $day = $_POST["day"];
    $eventType = (int)$_POST["eventID"];
    $date = "";

    switch($day){
        case "Thursday":
            $date = "07-26";
            break;
        case "Friday":
            $date = "07-27";
            break;
        case "Saturday":
            $date = "07-28";
            break;
        case "Sunday":
            $date = "07-29";
            break;
    }

    if ($eventType == 3) {
        $tickets = $ts->getHistoricTicketsPerDay($date);
    }elseif($eventType == 2){
        $tickets = array_map(function($restaurant){
            return [
                "id" => $restaurant->getId(),
                "name" => $restaurant->getName(),
                "location" => $restaurant->getLocation(),
                "foodType" => FoodType::getType($restaurant->getFoodType()),
                "price" => $restaurant->getPrice()
            ];
        }, $rs->getAll());
    }else{
        $tickets = $ts->getMusicTicketsPerDay($eventType, $date);
    }

    echo json_encode($tickets);
} else {
    echo "[]";
}

?>
