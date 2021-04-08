<?php

require_once(__DIR__ . "/../DAL/ReservationDAO.php");
require_once(__DIR__ . "/../models/Reservation.php");
require_once ("ServiceUtils.php");

class ReservationService extends ServiceUtils {
    private ReservationDAO $dao;

    public function __construct() {
        $this->dao = new ReservationDAO();
    }

    public function reservationService(Reservation $reservation)
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