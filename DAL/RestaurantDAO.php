<?php
require_once("Base.php");
require_once("DAOUtils.php");

// DAO for the images
class RestaurantDAO extends DAOUtils
{
    private string $tableName = "restaurants";

    // get all restaurants
    public function getAll(): ?PDOStatement
    {
        try {
            $query = "SELECT
                          id, name, location, foodType
                      FROM " . $this->tableName;

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