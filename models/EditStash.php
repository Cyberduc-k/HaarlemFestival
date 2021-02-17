<?php
//Model to hold information of an user edit
class EditStash extends User
{
    private int $stashId;
    private DateTime $expDate;

    //Generate an Expire date for this stash
    private function generateExpDate(): bool{
        try{
            $this->setExpDate(DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime("+1 hours"))));
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

    //Fill this parent(stash) with the values of a child(user)
    public function generateFromUser(User $user, int $stashId): bool{
        try {
            $this->setId($user->getId());
            $this->setFirstname($user->getFirstname());
            $this->setLastname($user->getLastname());
            $this->setEmail($user->getEmail());
            $this->setPassword($user->getPassword());
            $this->setSalt($user->getSalt());
            $this->setUsertype($user->getUsertype());
            $this->setStashId($stashId);

            if (!$this->generateExpDate())
                return false;

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

    /**
     * @return int
     */
    public function getStashId(): int
    {
        return $this->stashId;
    }

    /**
     * @param int $stashId
     */
    public function setStashId(int $stashId): void
    {
        $this->stashId = $stashId;
    }

    /**
     * @return DateTime
     */
    public function getExpDate(): DateTime
    {
        return $this->expDate;
    }

    /**
     * @param DateTime $expDate
     */
    public function setExpDate(DateTime $expDate): void
    {
        $this->expDate = $expDate;
    }

    public function getExpDateString(): String
    {
        return $this->getExpDate()->format('Y-m-d H:i:s');
    }
}
?>