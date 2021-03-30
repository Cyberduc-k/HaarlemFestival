<?php

require_once(__DIR__ . "/../models/Venue.php");
require_once(__DIR__ . "/../DAL/VenueDAO.php");
require_once("ServiceUtils.php");

class VenueService extends ServiceUtils {
    private VenueDAO $dao;

    public function __construct() {
        $this->dao = new VenueDAO();
    }

    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $venues = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($venues, $this->rowToVenue($row));
                }

                return $venues;
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

    public function getForHistoricTour(int $tourId): ?Venue {
        try {
            $stmt = $this->dao->getForHistoricTour($tourId);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToVenue($row);
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
}

?>
