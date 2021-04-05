<?php

class Image {
    private int $id;
    private int $contentPage;

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
}

?>
