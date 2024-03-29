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
        // query om alle records op te halen
        try {
            $query = "SELECT
                          id, name, location, foodType, price
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

    public function getById(int $id): ?PDOStatement
    {
        try {
            $query = "SELECT
                          id, name, location, foodType, price
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

    public function addRestaurant(Restaurant $restaurant): ?bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                name = :name, location = :location, foodType = :foodType, price = :price";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $name = (string)$restaurant->getName();
            $location = (string)$restaurant->getLocation();
            $foodType = (string)$restaurant->getFoodType();
            $price = (int)$restaurant->getPrice();

            // bind values
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":location", $location);
            $stmt->bindParam(":foodType", $foodType);
            $stmt->bindParam(":price", $price);

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