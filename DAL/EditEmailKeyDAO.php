<?php
require_once("Base.php");
require_once ("DAOUtils.php");

//DAO for the edit email keys
class EditEmailKeyDAO extends DAOUtils
{
    // table name
    private string $tableName = "edit_email_verifications";

    //Get an edit email key by id
    public function getById(int $id): ?PDOStatement{
        try{
            // query to read single record
            $query = "SELECT
                stashId, oldKey, oldEmail, oldStatus, newKey, newEmail, newStatus, expDate
            FROM " . $this->tableName . " WHERE stashId = ? 
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

    //Get an edit email key by a possible old or new key
    public function getByKey(String $key): ?PDOStatement{
        try{
            // query to read single record
            $query = "SELECT
                stashId, oldKey, oldEmail, oldStatus, newKey, newEmail, newStatus, expDate
            FROM " . $this->tableName . " WHERE oldKey=:key OR newKey=:key
            ORDER BY expDate DESC
            LIMIT 1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(":key", $key);

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

    // update the Key
    public function update(EditEmailKey $editEmailKey): bool{
        try{
            // update query
            $query = "UPDATE
                    " . $this->tableName . "
                SET
                    oldEmail=:oldEmail, oldKey=:oldKey, oldStatus=:oldStatus, 
                    newEmail=:newEmail, newKey=:newKey, newStatus=:newStatus, expDate=:expDate
                WHERE
                    stashId = :stashId";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $stashId = (int)$editEmailKey->getId();
            $oldStatus = (string)$editEmailKey->isOldStatus();
            $oldEmail = (string)$editEmailKey->getOldEmail();
            $oldKey = (string)$editEmailKey->getOldKey();
            $newEmail = (string)$editEmailKey->getNewEmail();
            $newKey = (string)$editEmailKey->getNewKey();
            $newStatus = (string)$editEmailKey->isNewStatus();
            $keyExpDate = $editEmailKey->getExpDateString();

            // bind values
            $stmt->bindParam(":stashId", $stashId);
            $stmt->bindParam(":oldStatus", $oldStatus);
            $stmt->bindParam(":oldEmail", $oldEmail);
            $stmt->bindParam(":newKey", $newKey);
            $stmt->bindParam(":newStatus", $newStatus);
            $stmt->bindParam(":oldKey", $oldKey);
            $stmt->bindParam(":newEmail", $newEmail);
            $stmt->bindParam(":expDate", $keyExpDate);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}
?>