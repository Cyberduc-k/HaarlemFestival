<?php

class Musician {
    private int $id;
    private String $name;
    private int $eventId;

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

    public function getEventId(): int {
        return $this->eventId;
    }

    public function setEventId(int $eventId): void {
        $this->eventId = $eventId;
    }
}

?>
