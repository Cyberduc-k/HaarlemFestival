<?php
//Model used to track whether an email edit is valid or not
class EditEmailKey
{
    private const KEY_LENGTH = 32;

    private int $id;
    private String $oldEmail;
    private String $oldKey;
    private bool $oldStatus;
    private String $newEmail;
    private String $newKey;
    private bool $newStatus;
    private DateTime $expDate;

    /**
     * @return String
     */
    public function getExpDateString(): String
    {
        return $this->getExpDate()->format('Y-m-d H:i:s');
    }

    /**
     * @param DateTime $expDate
     */
    public function setExpDate(DateTime $expDate): void
    {
        $this->expDate = $expDate;
    }

    //Fill fields for this object based on an id
    public function fillForId(int $id, String $oldMail, String $newMail): bool{
        try{
            //Expire date 1 hour from now
            $expDate = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime("+1 hours")));

            //Randomly genereated keys
            $oldKey = bin2hex(random_bytes(self::KEY_LENGTH));
            $newKey = bin2hex(random_bytes(self::KEY_LENGTH));

            //Set values
            $this->setId($id);
            $this->setOldEmail($oldMail);
            $this->setOldKey($oldKey);
            $this->setOldStatus(false);
            $this->setNewEmail($newMail);
            $this->setNewKey($newKey);
            $this->setNewStatus(false);
            $this->setExpDate($expDate);

            return true;
        }
        catch(Exception $e){
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            //Return an empty stmt
            return false;
        }
    }

    //Get the reset link for this ResetKey for the new mail
    public function getNewLink(): ?String{
        if(!empty($this->getId()) && !empty($this->getNewKey()) && !empty($this->getExpDateString()))
            return "bramsierhuis.nl/PHP/confirmEditHandler.php?type=new&editId=".$this->getId()."&key=".$this->getNewKey();

        return null;
    }

    //Get the reset link for this ResetKey for the old mail
    public function getOldLink(): ?String{
        if(!empty($this->getId()) && !empty($this->getOldKey()) && !empty($this->getExpDateString()))
            return "bramsierhuis.nl/PHP/confirmEditHandler.php?type=old&editId=".$this->getId()."&key=".$this->getOldKey();

        return null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getOldEmail(): string
    {
        return $this->oldEmail;
    }

    /**
     * @param String $oldEmail
     */
    public function setOldEmail(string $oldEmail): void
    {
        $this->oldEmail = $oldEmail;
    }

    /**
     * @return String
     */
    public function getOldKey(): string
    {
        return $this->oldKey;
    }

    /**
     * @param String $oldKey
     */
    public function setOldKey(string $oldKey): void
    {
        $this->oldKey = $oldKey;
    }

    /**
     * @return bool
     */
    public function isOldStatus(): bool
    {
        return $this->oldStatus;
    }

    /**
     * @param bool $oldStatus
     */
    public function setOldStatus(bool $oldStatus): void
    {
        $this->oldStatus = $oldStatus;
    }

    /**
     * @return String
     */
    public function getNewEmail(): string
    {
        return $this->newEmail;
    }

    /**
     * @param String $newEmail
     */
    public function setNewEmail(string $newEmail): void
    {
        $this->newEmail = $newEmail;
    }

    /**
     * @return String
     */
    public function getNewKey(): string
    {
        return $this->newKey;
    }

    /**
     * @param String $newKey
     */
    public function setNewKey(string $newKey): void
    {
        $this->newKey = $newKey;
    }

    /**
     * @return bool
     */
    public function isNewStatus(): bool
    {
        return $this->newStatus;
    }

    /**
     * @param bool $newStatus
     */
    public function setNewStatus(bool $newStatus): void
    {
        $this->newStatus = $newStatus;
    }

    /**
     * @return DateTime
     */
    public function getExpDate(): DateTime
    {
        return $this->expDate;
    }
}
?>