<?php
require_once("ServiceUtils.php");
require_once(__DIR__ . "/../DAL/HistoricTourDAO.php");
require_once(__DIR__ . "/../models/HistoricTour.php");

class HistoricTourService extends ServiceUtils {
    private HistoricTourDAO $dao;

    public function __construct(){
        $this->dao = new HistoricTourDAO();
    }

    public function getById(int $id): ?HistoricTour {
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToHistoricTour($row);
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

    // Get all the tours from database
    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $tours = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($tours, $row);
                }

                return $tours;
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    public function getSchedule(): ?array{
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $schedule = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($schedule, $this->rowToTourSchedule($row));
                }

                return $schedule;
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage);
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

}
