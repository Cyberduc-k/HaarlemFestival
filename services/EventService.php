<?php
require_once(__DIR__ . "/../models/Event.php");
require_once(__DIR__ . "/../DAL/EventDAO.php");
require_once("ServiceUtils.php");

class EventService extends ServiceUtils
{
    private EventDAO $dao;

    public function __construct() {
        $this->dao = new EventDAO();
    }

    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $events = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($tickets, $this->rowToEvent($row));
                }

                return $events;
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