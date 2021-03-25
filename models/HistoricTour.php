<?php

class HistoricTour {
    private int $id;
    private String $guide;
    private DateTime $date;
    private int $language;
    private string $venue;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getGuide(): String {
        return $this->guide;
    }

    public function setGuide(String $guide): void {
        $this->guide = $guide;
    }

    public function getDate(): DateTime {
        return $this->date;
    }

    public function setDate(DateTime $date): void {
        $this->date = $date;
    }

    public function getLanguage(): int {
        return $this->language;
    }

    public function setLanguage(int $language): void {
        $this->language = $language;
    }

    public function getVenue(): string
    {
        return $this->venue;
    }

    public function setVenue(string $venue): void
    {
        $this->venue = $venue;
    }


}

?>
