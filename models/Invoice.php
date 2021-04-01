<?php

class Invoice {
    private int $id;
    private int $userId;
    private string $userAddress;
    private string $userPhone;
    private float $tax;
    private DateTime $date;
    private DateTime $dueDate;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function getUserAddress(): string {
        return $this->userAddress;
    }

    public function setUserAddress(string $userAddress): void {
        $this->userAddress = $userAddress;
    }

    public function getUserPhone(): string {
        return $this->userPhone;
    }

    public function setUserPhone(string $userPhone): void {
        $this->userPhone = $userPhone;
    }

    public function getTax(): float {
        return $this->tax;
    }

    public function setTax(float $tax): void {
        $this->tax = $tax;
    }

    public function getDate(): DateTime {
        return $this->date;
    }

    public function setDate(DateTime $date): void {
        $this->date = $date;
    }

    public function getDueDate(): DateTime {
        return $this->dueDate;
    }

    public function setDueDate(DateTime $dueDate): void {
        $this->dueDate = $dueDate;
    }
}

?>
