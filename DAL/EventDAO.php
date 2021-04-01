<?php
require_once("Base.php");
require_once("DAOUtils.php");
require_once(__DIR__ . "/../models/Event.php");

class EventDAO extends DAOUtils
{
    // table names
    private string $tableName = "events";

    // get all tickets
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          id, name, colour
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

    public function getById(int $id): PDOStatement{
        try {
            $query = "SELECT
                          id, name, colour
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

    public function addEvent(Event $event): bool{
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                name=:name, colour=:colour";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $name = (string)$event->getName();
            $colour = (string)$event->getColour();


            // bind values
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":colour", $colour);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function nameExists(String $name): ?PDOStatement {
        try {
            // query to check if it exists
            $query = "SELECT 1 FROM " . $this->tableName . " WHERE name LIKE ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare( $query );
            Base::getInstance()->conn->beginTransaction();

            // bind name of type to be retrieved
            $stmt->bindParam(1, $name);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    public function getIdByName(string $name): ?PDOStatement
    {
        try {
            $query = "SELECT
                          id
                      FROM " . $this->tableName . "
                      WHERE name = :name";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":name", $name);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}