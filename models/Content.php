<?php

class Content {
    private int $id;
    private int $eventId;
    private String $header;
    private String $text;
    private String $imagePath;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getEventId(): int {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId(int $eventId): void {
        $this->eventId = $eventId;
    }

    /**
     * @return String
     */
    public function getHeader(): string {
        return $this->header;
    }

    /**
     * @param String $header
     */
    public function setHeader(string $header): void {
        $this->header = $header;
    }

    /**
     * @return String
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @param String $text
     */
    public function setText(string $text): void {
        $this->text = $text;
    }

    /**
     * @return String
     */
    public function getImagePath(): string {
        return $this->imagePath;
    }

    /**
     * @param String $imagePath
     */
    public function setImagePath(string $imagePath): void {
        $this->imagePath = $imagePath;
    }
}

?>
