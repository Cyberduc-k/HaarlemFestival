<?php
require_once("Base.php");
require_once("DAOUtils.php");

// DAO for the images
class ReservationDAO extends DAOUtils
{
    private string $tableName = "reservations";

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
