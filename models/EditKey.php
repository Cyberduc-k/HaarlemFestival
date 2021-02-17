<?php
//Model used to track whether an edit is valid or not
class EditKey
{
    private const KEY_LENGTH = 32;

    private int $id;
    private String $key;
    private DateTime $expDate;

    //Fill fields for this object
    public function fillForId(int $id): bool{
        try{
            //Expire date 1 hour from now
            $expDate = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime("+1 hours")));

            //Random key
            $key = bin2hex(random_bytes(self::KEY_LENGTH));

            //Set values
            $this->setId($id);
            $this->setExpDate($expDate);
            $this->setKey($key);

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

    //Get the reset link for this ResetKey
    public function getLink(): ?String{
        if(!empty($this->getId()) && !empty($this->getKey()) && !empty($this->getExpDateString()))
            return "bramsierhuis.nl/PHP/confirmEditHandler.php?editId=".$this->getId()."&key=".$this->getKey();

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
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param String $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return DateTime
     */
    public function getExpDate(): DateTime
    {
        return $this->expDate;
    }
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
}
?>