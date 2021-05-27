<?php
require_once("../services/RestaurantService.php");
require_once("../models/Restaurant.php");
require_once("../models/FoodType.php");

session_start();
$rs = new RestaurantService;

// Retrieve user input information from form
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

// Feedback validation cookies
if ($price < 0 || !ctype_digit($price)) {
    setcookie('PriceVal', $price, time() + 1, "/");
} elseif (strpbrk($location, '1234567890') == FALSE) {
    setcookie('LocationVal', $location, time() + 1, "/");
} else {
    $restaurant = new Restaurant();
    $restaurant->setName($name);
    $restaurant->setLocation($location);
    $restaurant->setFoodType($foodType);
    $restaurant->setPrice($price);
    //$rs->addRestaurant($restaurant);

    setcookie('RestaurantAdded', $name, time() + 1, "/");
}

// Go back to edit page
header("Location: /event/food/edit");
?>

