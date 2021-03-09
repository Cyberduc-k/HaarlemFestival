<?php

class Schedule {
    private int $userId;
    private int $ticketId;
    private int $nTickets;

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $id): void {
        $this->userId = $id;
    }

    public function getTicketId(): int {
        return $this->ticketId;
    }

    public function setTicketId(int $id): void {
        $this->ticketId = $id;
    }

    public function getNTickets(): int {
        return $this->nTickets;
    }

    public function setNTickets(int $n): void {
        $this->nTickets = $n;
    }
}

?>
