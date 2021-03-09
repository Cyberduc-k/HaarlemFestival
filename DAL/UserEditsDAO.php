<?php

require_once ("Base.php");
require_once ("DAOUtils.php");

class UserEditsDAO extends DAOUtils {
    // table name
    private string $stashTable = "user_edits";
    private string $keyTable = "edit_verifications";
    private string $emailKeyTable = "edit_email_verifications";

    // create new stash entry
    public function createEditStashAndEditKey(EditStash $stash, EditKey $editKey): bool {
        try {
            // query to insert stash
            $stashQuery = "INSERT INTO
                " . $this->stashTable . "
            SET
                stashId=:stashId, userId=:userId, firstname=:firstname, lastname=:lastname, password=:password, 
                salt=:salt, email=:email, usertype=:usertype, expDate=:expDate";

            // query to insert key
            $keyQuery = "INSERT INTO
                " . $this->keyTable . "
            SET
                stashId=:stashId, `key`=:key, expDate=:expDate";

            // prepare query
            $stashStmt = Base::getInstance()->conn->prepare($stashQuery);
            $keyStmt = Base::getInstance()->conn->prepare($keyQuery);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $userId = (int)$stash->getId();
            $password = (string)$stash->getPassword();
            $salt = (string)$stash->getSalt();
            $lastname = (string)$stash->getLastname();
            $email = (string)$stash->getEmail();
            $usertype = (int)$stash->getUsertype();
            $firstname = (string)$stash->getFirstname();
            $expDate = $stash->getExpDateString();

            $stashId = (int)$editKey->getId();
            $key = (string)$editKey->getKey();
            $keyExpDate = $editKey->getExpDateString();

            // bind values
            $stashStmt->bindParam(":stashId", $stashId);
            $stashStmt->bindParam(":userId", $userId);
            $stashStmt->bindParam(":firstname", $firstname);
            $stashStmt->bindParam(":lastname", $lastname);
            $stashStmt->bindParam(":password", $password);
            $stashStmt->bindParam(":salt", $salt);
            $stashStmt->bindParam(":email", $email);
            $stashStmt->bindParam(":usertype", $usertype);
            $stashStmt->bindParam(":expDate", $expDate);

            $keyStmt->bindParam(":stashId", $stashId);
            $keyStmt->bindParam(":key", $key);
            $keyStmt->bindParam(":expDate", $keyExpDate);

            // execute query
            $stashStmt->execute();
            $keyStmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // create new stash entry
    public function createEditStashAndEditEmailKey(EditStash $stash, EditEmailKey $editEmailKey): bool {
        try {
            // query to insert stash
            $stashQuery = "INSERT INTO
                " . $this->stashTable . "
            SET
                stashId=:stashId, userId=:userId, firstname=:firstname, lastname=:lastname, password=:password, 
                salt=:salt, email=:email, usertype=:usertype, expDate=:expDate";

            // query to insert key
            $keyQuery = "INSERT INTO
                " . $this->emailKeyTable . "
            SET
                stashId=:stashId, oldEmail=:oldEmail, oldKey=:oldKey, oldStatus=:oldStatus, 
                newEmail=:newEmail, newKey=:newKey, newStatus=:newStatus, expDate=:expDate";

            // prepare query
            $stashStmt = Base::getInstance()->conn->prepare($stashQuery);
            $keyStmt = Base::getInstance()->conn->prepare($keyQuery);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $userId = (int)$stash->getId();
            $password = (string)$stash->getPassword();
            $salt = (string)$stash->getSalt();
            $email = (string)$stash->getEmail();
            $usertype = (int)$stash->getUsertype();
            $firstname = (string)$stash->getFirstname();
            $expDate = $stash->getExpDateString();
            $lastname = (string)$stash->getLastname();

            $stashId = (int)$editEmailKey->getId();
            $oldEmail = (string)$editEmailKey->getOldEmail();
            $oldKey = (string)$editEmailKey->getOldKey();
            $oldStatus = (string)$editEmailKey->isOldStatus();
            $newEmail = (string)$editEmailKey->getNewEmail();
            $newKey = (string)$editEmailKey->getNewKey();
            $newStatus = (string)$editEmailKey->isNewStatus();
            $keyExpDate = $editEmailKey->getExpDateString();

            // bind values
            $stashStmt->bindParam(":stashId", $stashId);
            $stashStmt->bindParam(":userId", $userId);
            $stashStmt->bindParam(":firstname", $firstname);
            $stashStmt->bindParam(":lastname", $lastname);
            $stashStmt->bindParam(":password", $password);
            $stashStmt->bindParam(":salt", $salt);
            $stashStmt->bindParam(":email", $email);
            $stashStmt->bindParam(":usertype", $usertype);
            $stashStmt->bindParam(":expDate", $expDate);

            $keyStmt->bindParam(":stashId", $stashId);
            $keyStmt->bindParam(":oldKey", $oldKey);
            $keyStmt->bindParam(":oldStatus", $oldStatus);
            $keyStmt->bindParam(":oldEmail", $oldEmail);
            $keyStmt->bindParam(":newKey", $newKey);
            $keyStmt->bindParam(":newStatus", $newStatus);
            $keyStmt->bindParam(":newEmail", $newEmail);
            $keyStmt->bindParam(":expDate", $keyExpDate);

            // execute query
            $stashStmt->execute();
            $keyStmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // get a stash for a user
    public function getById(int $id): ?PDOStatement {
        try {
            // query to read single record, the most recent one
            $query = "SELECT
                stashId, userId, firstname, lastname, password, salt, email, usertype, expDate
            FROM
                " . $this->stashTable . " WHERE stashid = ? 
                ORDER BY expDate DESC LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $id);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    public function getNextId(): ?PDOStatement {
        try {
            // query to read single record
            $query = "SELECT
                stashId
            FROM " . $this->stashTable . " 
            ORDER BY expDate DESC
            LIMIT 1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}

?>
