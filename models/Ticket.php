<?php

class Ticket {
    private int $id;
    private int $type;
    private int $eventType;
    private int $eventId;
    private float $price;
    private int $inStock;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getType(): int {
        return $this->type;
    }

    public function setType(int $type): void {
        $this->type = $type;
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
