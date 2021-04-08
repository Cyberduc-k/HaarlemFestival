<?php

class Restaurant {
    private int $id;
    private String $name;
    private String $location;
    private int $foodType;
    private int $price;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): String {
        return $this->name;
    }

    public function setName(String $name): void {
        $this->name = $name;
    }

    public function getLocation(): String {
        return $this->location;
    }

    public function setLocation(String $location): void {
        $this->location = $location;
    }

    public function getFoodType(): int {
        return $this->foodType;
    }

    public function setFoodType(int $foodType): void {
        $this->foodType = $foodType;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function setPrice(int $price): void {
        $this->price = $price;
    }
}

?>
