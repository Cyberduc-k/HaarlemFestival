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
                $events = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($events, $this->rowToEvent($row));
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

    // Get all events as array
    public function getAllAsArray(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $events = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($events, $row);
                }

                return $events;
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

    public function getById(int $id): ?Event{
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToEvent($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty event
            return null;
        }
    }

    public function getByIdAsArray(int $id): ?Array{
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty event
            return null;
        }
    }

    public function addEvent(Event $event): bool{
        try {
            // Check if there already is an event with this name
            if ($this->nameExists($event->getName())){
                $_SESSION["createError"] = "There is already an event with this name";
                return false;
            }

            if ($this->dao->addEvent($event))
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

    public function nameExists(String $name): bool {
        try {
            $stmt = $this->dao->nameExists($name);
            $num = $stmt->rowCount();

            // Rowcount is 1 when a record with that mail has been found
            return ($num == 1);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }

    public function getIdByName(string $name): ?int {
        try {
            $stmt = $this->dao->getIdByName($name);
            $num = $stmt->rowCount();

            // Return id if set, else return 0
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int)$row["id"];
            } else {
                return 0;
            }
        } catch(Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    public function delete(int $id): bool {
        if ($this->dao->delete($id))
            return true;

        return false;
    }
}