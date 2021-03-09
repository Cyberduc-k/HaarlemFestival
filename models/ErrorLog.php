<?php

// Model to hold information of an error
class ErrorLog {
    private int $id;
    private string $message;
    private String $stackTrace;

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
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void {
        $this->message = $message;
    }

    /**
     * @return String
     */
    public function getStackTrace(): string {
        return $this->stackTrace;
    }

    /**
     * @param String $stackTrace
     */
    public function setStackTrace(string $stackTrace): void {
        $this->stackTrace = $stackTrace;
    }
}

?>
