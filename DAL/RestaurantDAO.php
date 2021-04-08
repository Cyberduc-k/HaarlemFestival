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

    public function addRestaurant(Restaurant $restaurant): ?bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                name = :name, location = :location, foodType = :foodType";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $name = (string)$restaurant->getName();
            $location = (string)$restaurant->getLocation();
            $foodType = (string)$restaurant->getFoodType();

            // bind values
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":location", $location);
            $stmt->bindParam(":foodType", $foodType);

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