<?php

class Act {
    private int $id;
    private int $eventId;
    private DateTime $date;
    private DateTime $startTime;
    private Datetime $endTime;
    private string $location;
    private string $imagePath;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
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

    public function getStartTime(): DateTime {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): void {
        $this->startTime = $startTime;
    }

    public function getEndTime(): Datetime {
        return $this->endTime;
    }

    public function setEndTime(Datetime $endTime): void {
        $this->endTime = $endTime;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function setLocation(string $location): void {
        $this->location = $location;
    }

    public function getImagePath(): string {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): void {
        $this->imagePath = $imagePath;
    }
}

?>
