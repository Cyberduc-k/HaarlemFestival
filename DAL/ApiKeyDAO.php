<?php

require_once("Base.php");
require_once("DAOUtils.php");
require_once(__DIR__ . "/../models/ApiKey.php");

// DAO for api keys
class ApiKeyDAO extends DAOUtils {
    // table name
    private string $tableName = "api_keys";
    
    // get all api keys
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          email, api_key
                      FROM " . $this->tableName;

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
       } catch (Exception $e) {
           return $this->handleNullError($e, true);
       }
    }

    // get an api key by email
    public function getByEmail(string $email): ?PDOStatement {
        try {
            $query = "SELECT
                          email, api_key
                      FROM " . $this->tableName . "
                      WHERE email LIKE :email";
            
            $stmt = Base::getInstance()->conn->prepare($query);
            
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":email", $email);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // validate if a key exists
    public function validate(ApiKey $key): ?PDOStatement {
        try {
            $query = "SELECT
                          1
                      FROM " . $this->tableName . "
                      WHERE email LIKE :email AND api_key LIKE :key";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            // cast to avoid errors
            $email = $key->getEmail();
            $api_key = $key->getApiKey();

            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":key", $api_key);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // create new API key
    public function create(ApiKey $key): bool {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                email=:email, api_key=:key";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast to avoid errors
            $email = (string)$key->getEmail();
            $key = (string)$key->getApiKey();

            // bind values
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":key", $key);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // update the Key
    public function update(ApiKey $api_key): bool {
        try {
            // update query
            $query = "UPDATE
                    " . $this->tableName . "
                SET
                    email=:email, api_key=:key
                WHERE
                    email LIKE :email";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $email = (string)$api_key->getEmail();
            $key = (string)$api_key->getApiKey();

            // bind values
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":api_key", $key);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // delete a key for a user
    public function delete(string $email): bool {
        try {
            // update query
            $query = "DELETE FROM
                    " . $this->tableName . "
                WHERE
                    email LIKE :email";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind values
            $stmt->bindParam(":email", $email);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }
}

?>
