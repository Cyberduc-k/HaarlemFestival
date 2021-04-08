<?php

require_once(__DIR__ . "/../models/Ticket.php");
require_once(__DIR__ . "/../models/TicketType.php");
require_once(__DIR__ . "/../models/TicketWithCount.php");
require_once(__DIR__ . "/../models/EventType.php");
require_once(__DIR__ . "/../DAL/TicketDAO.php");
require_once("ActService.php");
require_once("VenueService.php");
require_once("ReservationService.php");
require_once("HistoricTourService.php");
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
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    // get all tickets for the given event type
    public function getAllForEvent(int $eventType): ?array {
        try {
            $stmt = $this->dao->getAllForEvent($eventType);
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
            $error->setMessage($e->getMessage());
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
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    // get all ticets for a given invoice.
    // NOTE: this returns an array of TicketWithCount
    public function getAllForInvoice(int $invoiceId): ?array {
        try {
            $stmt = $this->dao->getAllForInvoice($invoiceId);
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
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function addToCart(int $userId, int $ticketId, int $count) {
        try {
            return $this->dao->addToCart($userId, $ticketId, $count);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function addToSchedule(int $userId, int $ticketId, int $count) {
        try {
            return $this->dao->addToSchedule($userId, $ticketId, $count);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function getDescription(Ticket $ticket): string {
        try {
            switch ($ticket->getEventType()) {
                case EventType::Dance:
                case EventType::Jazz:
                    switch ($ticket->getType()) {
                        case TicketType::SingleDay:
                            if ($ticket->getEventType() == EventType::Dance)
                                return "Dance Single Day Ticket";
                            else
                                return "Jazz Single Day Ticket";
                        case TicketType::ThreeDay:
                            if ($ticket->getEventType() == EventType::Dance)
                                return "Dance Three Day Ticket";
                            else
                                return "Jazz Three Day Ticket";
                        case TicketType::Normal:
                            $service = new ActService();
                            $musicians = $service->getMusiciansForAct($ticket->getEventId()) or [];
                            $description = "";

                            for ($i = 0; $i < count($musicians); $i++) {
                                if ($i != 0) {
                                    $description .= " & ";
                                }

                                $description .= $musicians[$i]->getName();
                            }

                            return $description;
                        default: die();
                    }
                case EventType::Historic:
                    $service = new HistoricTourService();
                    $tour = $service->getById($ticket->getEventId());

                    return "Historic Tour By " . $tour->getGuide();
                case EventType::Food:
                    return "Restaurant reservation";
                default: die();
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return "";
        }
    }

    public function getLocation(Ticket $ticket): ?string {
        try {
            switch ($ticket->getEventType()) {
                case EventType::Dance:
                case EventType::Jazz:
                    $service = new ActService();
                    $act = $service->getById($ticket->getEventId());

                    return $act->getLocation();
                case EventType::Historic:
                    $service = new VenueService();
                    $venue = $service->getForHistoricTour($ticket->getEventId());

                    return $venue->getName();
                case EventType::Food:
                    $service = new ReservationService();
                    return "Haarlem"; // @TODO: pull from databse
                default: die();
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    //Get these specific columns from all tickets
    public function getColumns(array $args): ?array
    {
        try{
            $stmt = $this->dao->getColumns($args);
            $num = $stmt->rowCount();

            if($num > 0){
                $tickets = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($tickets, $row);
                }

                return $tickets;
            }

            return null;
        }
        catch (Exception $e){
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            //Return an empty stmt
            return null;
        }
    }

    public function getStartDate(Ticket $ticket): ?DateTime {
        try {
            switch ($ticket->getEventType()) {
                case EventType::Dance:
                case EventType::Jazz:
                    $service = new ActService();
                    $act = $service->getById($ticket->getEventId());
                    $date = $act->getDate();
                    $time = $act->getStartTime();

                    return $date->add(new DateInterval($time->format("\P\TH\Hi\Ms\S")));
                case EventType::Historic:
                    $service = new HistoricTourService();
                    $tour = $service->getById($ticket->getEventId());

                    return $tour->getDate();
                case EventType::Food:
                    break;
                default: die();
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function getEndDate(Ticket $ticket): ?DateTime {
        try {
            switch ($ticket->getEventType()) {
                case EventType::Dance:
                case EventType::Jazz:
                    $service = new ActService();
                    $act = $service->getById($ticket->getEventId());
                    $date = $act->getDate();
                    $time = $act->getEndTime();

                    return $date->add(new DateInterval($time->format("\P\TH\Hi\Ms\S")));
                case EventType::Historic:
                    $service = new HistoricTourService();
                    $tour = $service->getById($ticket->getEventId());

                    return $tour->getDate()->add(new DateInterval("PT1H"));
                case EventType::Food:
                    break;
                default: die();
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function getMusicTicketsPerDay($eventId, $date) {
        try {
            $stmt = $this->dao->getTicketsMusicPerDay($eventId, $date);
            $tickets = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($tickets, $row);
            }

            return $tickets;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function getHistoricTicketsPerDay($date)
    {
        try {
            $stmt = $this->dao->getTicketsForHistoricPerDay($date);
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
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }
}

?>
