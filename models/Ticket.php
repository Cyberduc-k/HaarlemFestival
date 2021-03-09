<?php

require_once("EventType.php");

class Ticket {
    private int $id;
    private EventType $event;
    private DateTime $date;
    private float $price;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getEvent(): EventType {
        return $this->event;
    }

    public function setEvent(EventType $event): void {
        $this->event = $event;
    }

    public function getDate(): DateTime {
        return $this->date;
    }

    public function setDate(DateTime $date): void {
        $this->date = $date;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }
}

?>
