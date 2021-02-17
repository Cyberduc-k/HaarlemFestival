<?php
require_once("Base.php");
require_once ("DAOUtils.php");
//DAO for the passwords
class PasswordDAO extends DAOUtils
{
    // table name
    private string $resetTableName = "password_resets";

    // create new reset entry
    public function create(ResetKey $resetKey): bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->resetTableName . "
            SET
                userId=:userId, `key`=:key, expDate=:expDate";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $userId = (int)$resetKey->getUserId();
            $key = (string)$resetKey->getKey();
            $expDate = $resetKey->getExpDateString();

            // bind values
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":key", $key);
            $stmt->bindParam(":expDate", $expDate);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // Get reset key by user id
    public function getByUserId(int $id): ?PDOStatement{
        try{
            // query to read single record
            $query = "SELECT
                id, userId, `key`, expDate
            FROM " . $this->resetTableName . " WHERE userId = ? 
            ORDER BY expDate DESC
            LIMIT 1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $id);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}
?>