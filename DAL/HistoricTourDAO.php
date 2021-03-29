<?php
require_once("Base.php");
require_once("DAOUtils.php");

class HistoricTourDAO extends DAOUtils
{
    // table names
    private string $tableName = "historic_tours";

    // get all tours
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

    public function getSchedule(): ?PDOStatement{
        try {
            $query = "SELECT A.date, 
            (SELECT COUNT(language) as Dutch FROM ". $this->tableName ." 
            WHERE language LIKE 'Dutch' AND date=A.date) as Dutch, 
            (SELECT COUNT(language) as English FROM ". $this->tableName ." 
            WHERE language LIKE 'English' AND date=A.date) as English,
            (SELECT COUNT(language) as Chinese FROM ". $this->tableName ." 
            WHERE language LIKE 'Chinese' AND date=A.date) as Chinese
            FROM (SELECT DISTINCT date FROM ". $this->tableName .") A";

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