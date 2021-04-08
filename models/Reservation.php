<?php

class Reservation {
    private int $id;
    private int $restaurantId;
    private String $name;
    private DateTime $reservationTime;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getRestaurantId(): int {
        return $this->restaurantId;
    }

    public function setRestaurantId(int $restaurantId): void {
        $this->restaurantId = $restaurantId;
    }

    public function getName(): String {
        return $this->name;
    }

    public function setName(String $name): void {
        $this->name = $name;
    }

    public function getReservationTime(): DateTime {
        return $this->reservationTime;
    }

    public function setReservationTime(DateTime $reservationTime): void {
        $this->reservationTime = $reservationTime;
    }
}

?>
