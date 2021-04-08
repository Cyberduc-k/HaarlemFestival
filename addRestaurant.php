<?php
require_once ("services/RestaurantService.php");
require_once ("models/Restaurant.php");

$rs = new RestaurantService;

$name = $_POST["name"];
$location = $_POST["location"];
$foodType = $_POST["foodType"];

$restaurant = new Restaurant();
$restaurant->setName($name);
$restaurant->setLocation($location);
$restaurant->setFoodType($foodType);
$rs->addRestaurant($restaurant);

?>

