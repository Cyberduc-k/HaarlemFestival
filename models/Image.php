<?php

class Image {
    private int $id;
    private EventType $contentPage;
    private int $number;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getContentPage(): String {
        return $this->contentPage;
    }

    public function setContentPage(EventType $contentPage): void {
        $this->contentPage = $contentPage;
    }

    public function getNumber(): String {
        return $this->number;
    }

    public function setNumber(String $number): void {
        $this->number = $number;
    }

}

?>
