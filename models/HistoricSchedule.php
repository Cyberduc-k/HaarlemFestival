<?php


class HistoricSchedule
{
    private string $date;
    private int $nDutchTours;
    private int $nEnglishTours;
    private int $nChineseTours;


    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getNDutchTours(): int
    {
        return $this->nDutchTours;
    }

    public function setNDutchTours(int $nDutchTours): void
    {
        $this->nDutchTours = $nDutchTours;
    }

    public function getNEnglishTours(): int
    {
        return $this->nEnglishTours;
    }

    public function setNEnglishTours(int $nEnglishTours): void
    {
        $this->nEnglishTours = $nEnglishTours;
    }

    public function getNChineseTours(): int
    {
        return $this->nChineseTours;
    }

    public function setNChineseTours(int $nChineseTours): void
    {
        $this->nChineseTours = $nChineseTours;
    }

}