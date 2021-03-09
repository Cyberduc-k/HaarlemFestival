<?php

require_once("Base.php");
require_once ("DAOUtils.php");

// DAO for the edit keys
class EditKeyDAO extends DAOUtils {
    // table name
    private string $tableName = "edit_verifications";

    // create new edit key entry
    function create(EditKey $editKey): bool {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                stashId=:stashId, `key`=:key, expDate=:expDate";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $stashId = (int)$editKey->getId();
            $key = (string)$editKey->getKey();
            $expDate = $editKey->getExpDateString();

            // bind values
            $stmt->bindParam(":stashId", $stashId);
            $stmt->bindParam(":key", $key);
            $stmt->bindParam(":expDate", $expDate);

            // execute query
            $stmt->execute();
            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // get an edit key by id
    public function getById(int $id): ?PDOStatement {
        try {
            // query to read single record
            $query = "SELECT
                stashId, `key`, expDate
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

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}

?>
