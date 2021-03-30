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
            $error->setMessage($e->getMessage);
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }
}
