<?php

class Musician {
    private int $id;
    private String $name;
    private String $info;
    private String $socials;
    private bool $isJazz;

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

    public function getInfo(): String {
        return $this->info;
    }

    public function setInfo(String $info): void {
        $this->info = $info;
    }

    public function getSocials(): String {
        return $this->socials;
    }

    public function setSocials(String $socials): void {
        $this->socials = $socials;
    }

    public function getIsJazz(): bool {
        return $this->isJazz;
    }

    public function setIsJazz(bool $isJazz): void {
        $this->isJazz = $isJazz;
    }
}

?>
