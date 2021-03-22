<?php

require_once(__DIR__ . "/../models/Ticket.php");
require_once(__DIR__ . "/../models/TicketWithCount.php");
require_once(__DIR__ . "/../DAL/TicketDAO.php");
require_once("ActService.php");
require_once("ServiceUtils.php");

class TicketService extends ServiceUtils {
    private TicketDAO $dao;

    public function __construct() {
        $this->dao = new TicketDAO();
    }

    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $tickets = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($tickets, $this->rowToTicket($row));
                }

                return $tickets;
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

    // get all ticets for a given user.
    // NOTE: this returns an array of TicketWithCount
    public function getAllForUser(int $userId): ?array {
        try {
            $stmt = $this->dao->getAllForUser($userId);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $tickets = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($tickets, $this->rowToTicketWithCount($row));
                }

                return $tickets;
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

    public function getLocation(Ticket $ticket): string {
        try {
            switch ($ticket->getEventType()) {
                case EventType::Dance:
                case EventType::Jazz:
                    $service = new ActService();
                    $act = $service->getById($ticket->getEventId());

                    return $act->getLocation();
                case EventType::Historic:
                    return "Haarlem"; // @TODO: pull from database
                    break;
                case EventType::Food:
                    return "Haarlem"; // @TODO: pull from databse
                    break;
                default: die();
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage);
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function getStartDate(Ticket $ticket): DateTime {
        try {
            switch ($ticket->getEventType()) {
                case EventType::Dance:
                case EventType::Jazz:
                    $service = new ActService();
                    $act = $service->getById($ticket->getEventId());

                    return $act->getStartTime();
                case EventType::Historic:
                    break;
                case EventType::Food:
                    break;
                default: die();
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage);
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function getEndDate(Ticket $ticket): DateTime {
        try {
            switch ($ticket->getEventType()) {
                case EventType::Dance:
                case EventType::Jazz:
                    $service = new ActService();
                    $act = $service->getById($ticket->getEventId());

                    return $act->getEndTime();
                case EventType::Historic:
                    break;
                case EventType::Food:
                    break;
                default: die();
            }
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
