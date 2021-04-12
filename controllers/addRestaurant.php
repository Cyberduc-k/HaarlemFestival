<?php
require_once("services/RestaurantService.php");
require_once("models/Restaurant.php");
require_once("models/FoodType.php");

$rs = new RestaurantService;

$name = $_POST["name"];
$location = $_POST["location"];
$foodType = $_POST["foodType"];
$price = $_POST["price"];

        switch ($foodType) {
            case "French":
                $foodType = 0;
                break;
            case "Italian":
                $foodType = 1;
                break;
            case "Dutch":
                $foodType = 2;
                break;
            case "Mexican":
                $foodType = 3;
                break;
            case "Asian":
                $foodType = 4;
                break;
            default:
                $foodType = 5;
        }

$restaurant = new Restaurant();
$restaurant->setName($name);
$restaurant->setLocation($location);
$restaurant->setFoodType($foodType);
$restaurant->setPrice($price);
$rs->addRestaurant($restaurant);

header("Location: eventPage.php?event=2")

?>

