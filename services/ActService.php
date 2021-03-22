<?php

require_once(__DIR__ . "/../models/Act.php");
require_once(__DIR__ . "/../DAL/ActDAO.php");
require_once("ServiceUtils.php");

class ActService extends ServiceUtils {
    private ActDAO $dao;

    public function __construct() {
        $this->dao = new ActDAO();
    }

    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $acts = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($acts, $this->rowToAct($row));
                }

                return $acts;
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

    public function getById(int $id): ?Act {
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToAct($row);
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

    public function getAllForEvent(int $eventId): ?array {
        try {
            $stmt = $this->dao->getForEvent($eventId);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $acts = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($acts, $this->rowToAct($row));
                }

                return $acts;
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

?>
