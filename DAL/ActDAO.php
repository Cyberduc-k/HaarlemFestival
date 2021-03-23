<?php

require_once("Base.php");
require_once("DAOUtils.php");
require_once(__DIR__ . "/../models/Act.php");

// DAO for acts
class ActDAO extends DAOUtils {
    // table names
    private string $tableName = "acts";
    
    // get all acts
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          id, eventId, date, startTime, endTime, location, imagePath
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

    // get all acts that match these args
    public function getWithArgs(array $args): ?PDOStatement {
        try {
            $query = "SELECT
                          id, eventId, date, startTime, endTime, location, imagePath
                      FROM " . $this->tableName . "
                      WHERE ";

            extract($args);

            foreach ($args as $key => $value) {
                if (!empty($value)) {
                    $query .= "`$key` LIKE `$value' AND ";
                }
            }

            $query .= "TRUE";
            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get a single act by id
    public function getById(int $id): ?PDOStatement {
        try {
            $query = "SELECT
                          id, eventId, date, startTime, endTime, location, imagePath
                      FROM " . $this->tableName . "
                      WHERE id = :id";
            
            $stmt = Base::getInstance()->conn->prepare($query);
            
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":id", $id);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get all acts for the given event
    public function getForEvent(int $eventId): ?PDOStatement {
        try {
            $query = "SELECT
                          id, eventId, date, startTime, endTime, location, imagePath
                      FROM " . $this->tableName . "
                      WHERE eventId = :eventId";
            
            $stmt = Base::getInstance()->conn->prepare($query);
            
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":eventId", $eventId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}

?>