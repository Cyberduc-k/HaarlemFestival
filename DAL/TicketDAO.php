<?php

require_once("Base.php");
require_once("DAOUtils.php");
require_once(__DIR__ . "/../models/Ticket.php");

// DAO for the tickets
class TicketDAO extends DAOUtils {
    // table names
    private string $tableName = "tickets";

    // get all tickets
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          id, ticketType, eventType, eventId, price, inStock
                      FROM " . $this->tableName;

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
       } catch (Exception $e) {
           return $this->handleNullError($e, true);
       }
    }

    // get all tickets that match these args
    public function getWithArgs(array $args): ?PDOStatement {
        try {
            $query = "SELECT
                          id, ticketType, eventType, eventId, price, inStock
                      FROM " . $this->tableName . "
                      WHERE ";

            extract($args);

            foreach ($args as $key => $value) {
                if (!empty($value)) {
                    $query .= "`$key` LIKE '$value' AND ";
                }
            }

            $query .= "TRUE";
            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get a single ticket by id
    public function getById(int $id): ?PDOStatement {
        try {
            $query = "SELECT
                          id, ticketType, eventType, eventId, date, price, inStock
                      FROM " . $this->tableName . "
                      WHERE id = :id";
            
            $stmt = Base::getInstance()->conn->prepare($query);
            
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":id", $id);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get all tickets for the given event type
    public function getAllForEvent(int $eventType): ?PDOStatement {
        try {
            $query = "SELECT
                          id, ticketType, eventType, eventId, date, price, inStock
                      FROM " . $this->tableName . "
                      WHERE eventType = :eventType";
            
            $stmt = Base::getInstance()->conn->prepare($query);
            
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":eventType", $eventType);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get all tickets for a user
    public function getAllForUser(int $userId): ?PDOStatement {
        try {
            $query = "SELECT
                          tickets.id, ticketType, eventId, eventType, price, inStock, nTickets
                      FROM tickets
                      JOIN `schedule` ON `schedule`.ticketId = tickets.id
                      WHERE `schedule`.userId = :userId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":userId", $userId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get all tickets for a user
    public function getAllForInvoice(int $invoiceId): ?PDOStatement {
        try {
            $query = "SELECT
                          T.id, ticketType, eventId, eventType, price, inStock, I.nTickets
                      FROM tickets as T
                      JOIN invoice_ticket as I ON I.ticketId = T.id
                      WHERE I.invoiceId = :invoiceId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":invoiceId", $invoiceId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // create a new ticket
    public function create(Ticket $ticket): bool {
        try {
            $query = "INSERT INTO " . $this->tableName . "
                      SET
                          ticketType=:ticketType
                          eventType=:eventType,
                          eventId=:eventId,
                          price=:price,
                          inStock=:inStock";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid type errors
            $ticketType = (int)$ticket->getType();
            $eventType = (int)$ticket->getEventType();
            $eventId = (int)$ticket->getEventId();
            $price = (float)$ticket->getPrice();
            $inStock = (int)$ticket->getInStock();

            // bind values
            $stmt->bindParam(":ticketType", $ticketType);
            $stmt->bindParam(":eventType", $eventType);
            $stmt->bindParam(":eventId", $eventId);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":inStock", $inStock);

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // update a ticket
    public function update(Ticket $ticket): bool {
        try {
            $query = "UPDATE " . $this->tableName . "
                      SET
                          ticketType=:ticketType
                          eventType=:eventType,
                          eventId=:eventId,
                          price=:price,
                          inStock=:inStock
                      WHERE
                          id = :id";
            
            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid type errors
            $id = (int)$ticket->getId();
            $ticketType = (int)$ticket->getType();
            $eventType = (int)$ticket->getEventType();
            $eventId = (int)$ticket->getEventId();
            $price = (float)$ticket->getPrice();
            $inStock = (int)$ticket->getInStock();

            // bind values
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":ticketType", $ticketType);
            $stmt->bindParam(":eventType", $eventType);
            $stmt->bindParam(":eventId", $eventId);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":inStock", $inStock);

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // delete a ticket
    public function delete(int $id): bool {
        try {
            $query = "DELETE FROM " . $this->tableName . "
                      WHERE id = :id";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":id", $id);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function getTicketsMusicPerDay(int $eventId, $date): PDOStatement {
        try {
            $day = "%".$date;

            $query = "SELECT musicians.name, acts.startTime, acts.endTime, acts.location, inStock 
                            FROM `act_musician` 
                            JOIN musicians ON musicians.id=musicianId
                            JOIN acts ON acts.id=actId
                            JOIN tickets ON acts.eventId=tickets.eventId
                            WHERE acts.eventId = :eventId AND acts.date LIKE :day";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":eventId", $eventId);
            $stmt->bindParam(":day", $day);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    public function getTicketsForHistoricPerDay($date): PDOStatement {
        try {
            $day = "%".$date."%";

            $query = "SELECT language, guide, inStock FROM `tickets` 
                            JOIN events ON eventId=events.id
                            JOIN historic_tours ON events.id=historic_tours.eventId
                            WHERE historic_tours.date LIKE :day";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":eventId", $eventId);
            $stmt->bindParam(":day", $day);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}

?>
