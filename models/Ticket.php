<?php

class Ticket {
    private int $id;
    private int $eventType;
    private int $eventId;
    private DateTime $date;
    private float $price;
    private int $inStock;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getEventType(): int {
        return $this->eventType;
    }

    public function setEventType(int $eventType): void {
        $this->eventType = $eventType;
    }

    public function getEventId(): int {
        return $this->eventId;
    }

    public function setEventId(int $eventId): void {
        $this->eventId = $eventId;
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

    public function getInStock(): int {
        return $this->inStock;
    }

    public function setInStock(int $inStock): void {
        $this->inStock = $inStock;
    }
}

?>
