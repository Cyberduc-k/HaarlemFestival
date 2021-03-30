<?php

class Image {
    private int $id;
    private int $contentPage;
    private int $number;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getContentPage(): int {
        return $this->contentPage;
    }

    public function setContentPage(int $contentPage): void {
        $this->contentPage = $contentPage;
    }

    public function getNumber(): int {
        return $this->number;
    }

    public function setNumber(int $number): void {
        $this->number = $number;
    }
}

?>
