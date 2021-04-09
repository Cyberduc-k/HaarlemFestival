<?php

require_once(__DIR__ . "/../DAL/ReservationDAO.php");
require_once(__DIR__ . "/../models/Reservation.php");
require_once ("ServiceUtils.php");

class ReservationService extends ServiceUtils {
    private ReservationDAO $dao;

    public function __construct() {
        $this->dao = new ReservationDAO();
    }

    public function getById(int $id): ?Reservation {
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToReservation($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function addReservation(Reservation $reservation)
    {
        try {

            if ($this->dao->addReservation($reservation))
                return true;

            return false;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }
}