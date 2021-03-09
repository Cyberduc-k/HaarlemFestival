<?php

class user {
    private int $id;
    private String $firstname;
    private String $lastname;
    private String $password;
    private String $salt;
    private String $email;
    private DateTime $registerDate;
    private int $usertype;

    // Used to check if a specific var has been set for this specific object
    // Used in UserService->editUser()
    public function isSet(String $varName): bool {
        return isset(get_object_vars($this)[$varName]);
    }

    /**
     * @return String
     */
    public function getFullName(): string {
        return $this->firstname." ".$this->lastname;
    }

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
     * @return String
     */
    public function getFirstname(): string {
        return $this->firstname;
    }

    /**
     * @param String $firstname
     */
    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    /**
     * @return String
     */
    public function getLastname(): string {
        return $this->lastname;
    }

    /**
     * @param String $lastname
     */
    public function setLastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    /**
     * @return String
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param String $password
     */
    public function setPassword(string $password): void {
        $this->password = $password;
    }

    /**
     * @return String
     */
    public function getSalt(): string {
        return $this->salt;
    }

    /**
     * @param String $salt
     */
    public function setSalt(string $salt): void {
        $this->salt = $salt;
    }

    /**
     * @return String
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param String $email
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /**
     * @return DateTime
     */
    public function getRegisterDate(): DateTime {
        return $this->registerDate;
    }

    /**
     * @param DateTime $registerDate
     */
    public function setRegisterDate(DateTime $registerDate): void {
        $this->registerDate = $registerDate;
    }

    /**
     * @return int
     */
    public function getUsertype(): int {
        return $this->usertype;
    }

    /**
     * @param int $usertype
     */
    public function setUsertype(int $usertype): void {
        $this->usertype = $usertype;
    }
}

?>
