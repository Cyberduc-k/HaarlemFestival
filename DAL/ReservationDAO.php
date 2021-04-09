<?php
require_once("Base.php");
require_once("DAOUtils.php");

// DAO for the images
class ReservationDAO extends DAOUtils
{
    private string $tableName = "reservations";

    public function getById(int $id): ?PDOStatement
    {
        try {
            $query = "SELECT
                          id, restaurantId, name, reservationTime
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

    public function getReservationRestaurant(int $id): ?PDOStatement
    {
        try {
            $query = "SELECT R.id, R.name, R.location, R.price, R.foodType
            FROM restaurants as R
            JOIN reservations ON reservations.restaurantId = R.id
            WHERE reservations.id = :id";

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

    public function addReservation(Reservation $reservation): ?bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                restaurantId = :restaurantId, name = :name, reservationTime = :reservationTime";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $restaurantId = (int)$reservation->getRestaurantId();
            $name = (string)$reservation->getName();
            $reservationTime = $reservation->getReservationTime()->format("Y-m-d H:i:s");

            // bind values
            $stmt->bindParam(":restaurantId", $restaurantId);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":reservationTime", $reservationTime);

            // execute query
            $stmt->execute();

            $reservation->setId((int)base::getInstance()->conn->lastInsertId());

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }
}
