<?php

require_once("Base.php");
require_once("DAOUtils.php");
require_once("../models/Venue.php");

// DAO for the venues
class VenueDAO extends DAOUtils {
    private string $tableName = "venues";

    // get all venues
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          id, name
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

    // get a single venue by id
    public function getById(int $id): ?PDOStatement {
        try {
            $query = "SELECT
                          id, name
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

    // get a single venue for the given historic tour
    public function getForHistoricTour(int $tourId): ?PDOStatement {
        try {
            $query = "SELECT
                          V.id, V.name
                      FROM " . $this->tableName . " as V
                      JOIN historic_tours as H ON H.venueID = V.id
                      WHERE H.id = :tourId";
            
            $stmt = Base::getInstance()->conn->prepare($query);
            
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":tourId", $tourId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}

?>
