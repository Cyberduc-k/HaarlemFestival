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
}