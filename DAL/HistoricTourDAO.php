<?php
require_once("Base.php");
require_once("DAOUtils.php");

class HistoricTourDAO extends DAOUtils
{
    // table names
    private string $tableName = "historic_tours";

    // get all tickets
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          id, venueID, [language], guide, [date]
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