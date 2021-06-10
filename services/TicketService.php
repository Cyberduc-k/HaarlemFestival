<?php

require_once(__DIR__ . "/../models/Ticket.php");
require_once(__DIR__ . "/../models/TicketType.php");
require_once(__DIR__ . "/../models/TicketWithCount.php");
require_once(__DIR__ . "/../models/EventType.php");
require_once(__DIR__ . "/../models/Language.php");
require_once(__DIR__ . "/../services/ReservationService.php");
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

    // get all tickets for a given user.
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

    // get all tickets for a given user's cart.
    // NOTE: this returns an array of TicketWithCount
    public function getAllForCart(int $userId): ?array {
        try {
            $stmt = $this->dao->getAllForCart($userId);
            $tickets = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($tickets, $this->rowToTicketWithCount($row));
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

    public function getAllForOverview(int $userId): ?array {
        try {
            $stmt = $this->dao->getAllForOverview($userId);
            $tickets = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                array_push($tickets, $this->rowToTicketWithCount($row));
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

    // get all ticets for a given invoice.
    // NOTE: this returns an array of TicketWithCount
    public function getAllForInvoice(int $invoiceId): ?array {
        try {
            $stmt = $this->dao->getAllForInvoice($invoiceId);
            $tickets = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($tickets, $this->rowToTicketWithCount($row));
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

    public function addToCart(int $userId, int $ticketId, int $count): bool {
        try {
            return $this->dao->addToCart($userId, $ticketId, $count);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }

    public function deleteFromCart(int $userId, int $ticketId): bool {
        try {
            return $this->dao->deleteFromCart($userId, $ticketId);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }

    public function updateCart(int $userId, int $ticketId, int $count): bool{
        try {
            return $this->dao->updateCart($userId, $ticketId, $count);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }

    public function removeFromStock(int $ticketId): bool {
        try {
            return $this->dao->removeFromStock($ticketId);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
            return false;
        }
    }

    public function addBackToStock(int $ticketId): bool {
        try {
            return $this->dao->addBackToStock($ticketId);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }

    public function cancelTicketOrder(int $ticketId, int $amount): bool {
        try {
            return $this->dao->cancelTicketOrder($ticketId, $amount);
        }catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }

    public function update(Ticket $ticket): bool{
        try {
            return $this->dao->update($ticket);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error ->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
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
    
    public function moveCartToInvoice(int $userId, int $invoiceId): bool {
        try {
            return $this->dao->moveCartToInvoice($userId, $invoiceId);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }

    public function getStock(int $ticketId): ?PDOStatement {
        try {
            return $this->dao->getById($ticketId);
        }catch (Exception $e) {
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
                    $rs = new ReservationService();
                    $restaurant = $rs->getReservationRestaurant($ticket->getEventId());
                    return $restaurant->getName();
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
                    $rs = new ReservationService();
                    $restaurant = $rs->getReservationRestaurant($ticket->getEventId());
                    return $restaurant->getLocation();
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
                    $rs = new ReservationService();
                    $reservation = $rs->getById($ticket->getEventId());
                    return $reservation->getReservationTime();
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
                    $rs = new ReservationService();
                    $reservation = $rs->getById($ticket->getEventId());
                    return $reservation->getReservationTime()->add(new DateInterval("PT1H"));
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
            $tickets = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $row['language'] = Language::getLanguage((int)$row['language']);
                $row['date'] = (new DateTime($row['date']))->format('H:i');
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

    public function create(Ticket $ticket)
    {
        try {

            if ($this->dao->create($ticket))
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

?>
