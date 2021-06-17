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
                          id, ticketType, eventType, eventId, price, inStock
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
                          id, ticketType, eventType, eventId, price, inStock
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

    // get all tickets for a user's cart
    public function getAllForCart(int $userId): ?PDOStatement {
        try {
            $query = "SELECT
                          id, ticketType, eventId, eventType, price, inStock, nTickets
                      FROM tickets
                      JOIN `cart` ON `cart`.ticketId = tickets.id
                      WHERE `cart`.userId = :userId";

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

    public function getAllForOverview(int $userId): ?PDOStatement {
        try {
            $query = "SELECT
                          T.id, ticketType, eventId, eventType, price, inStock, nTickets
                      FROM tickets AS T
                      JOIN `invoice_ticket` AS IT ON IT.ticketId = T.id
                      JOIN `invoices` AS I ON I.id = IT.invoiceId
                      WHERE I.userId = :userId";


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

    // get all tickets for a invoice
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

    public function addToCart(int $userId, int $ticketId, int $count): bool {
        try {
            $query = "INSERT INTO cart
                      SET userId = :userId, ticketId = :ticketId, nTickets = :count
                      ON DUPLICATE KEY UPDATE nTickets = nTickets + :count";

            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->bindParam(":count", $count);
            $stmt->execute();

            Base::getInstance()->conn->commit();
//
//
//            $query = "UPDATE tickets
//            SET inStock = inStock - :count WHERE id = :ticketId";
//
//            $stmt = Base::getInstance()->conn->prepare($query);
//            Base::getInstance()->conn->beginTransaction();
//
//            $stmt->bindParam(":ticketId", $ticketId);
//            $stmt->bindParam(":count", $count);
//            $stmt->execute();
//
//            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function deleteFromCart(int $userId, int $ticketId): bool {
        try {
            $query = "DELETE FROM cart WHERE userId = :userId AND ticketId = :ticketId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function updateCart(int $userId, int $ticketId, int $count): bool{
        try {
            $query = "UPDATE cart
            SET nTickets = :count 
            WHERE userId = :userId AND ticketId = :ticketId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->bindParam(":count", $count);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function removeFromStock(int $ticketId, int $amount): bool {
        try {
            $query = "UPDATE tickets
            SET inStock = inStock -:amount  WHERE id = :ticketId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->bindParam(":amount", $amount);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function addBackToStock(int $ticketId): bool {
        try {
            $query = "UPDATE tickets
            SET inStock = instock +1 WHERE id = :ticketId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function cancelTicketOrder(int $ticketId, int $amount): bool {
        try {
            $query = "UPDATE tickets SET inStock = inStock + :amount WHERE id = :ticketId;";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->bindParam(":amount", $amount);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function addToSchedule(int $userId, int $ticketId, int $count): bool {
        try {
            $query = "INSERT INTO schedule
                      SET userId = :userId, ticketId = :ticketId, nTickets = :count";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->bindParam(":count", $count);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }
    
    public function moveCartToInvoice(int $userId, int $invoiceId): bool {
        try {
            $tickets = $this->getAllForCart($userId);
            $query = "INSERT INTO `invoice_ticket` SET invoiceId = :invoiceId, ticketId = :ticketId, nTickets = :nTickets";
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();
            $stmt->bindParam(":invoiceId", $invoiceId);

            while ($row = $tickets->fetch(PDO::FETCH_ASSOC)) {
                $stmt->bindParam(":ticketId", $row["id"]);
                $stmt->bindParam(":nTickets", $row["nTickets"]);
                $stmt->execute();
            }

            Base::getInstance()->conn->commit();

            $query = "DELETE FROM `cart` WHERE userId = :userId";
            $stmt = Base::getInstance()->conn->prepare($query);
            
            Base::getInstance()->conn->beginTransaction();
            
            $stmt->bindParam(":userId", $userId);
            $stmt->execute();
            
            Base::getInstance()->conn->commit();
            
            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // check if this ticket belongs to user
    public function belongsToUser(int $ticketId, int $userId): ?PDOStatement {
        try {
            $query = "SELECT * FROM `invoices` AS I "
                   . "JOIN `invoice_ticket` AS IT ON IT.invoiceId = I.id "
                   . "WHERE I.userId = :userId AND IT.ticketId = :ticketId";
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // get specific columns only
    public function getColumns(array $args): ?PDOStatement
    {
        try{
            //Extract the key value pairs and add them to the query
            $selectStatement = "";
            extract($args);
            foreach($args as $key => $value){
                if(!empty($value)){
                    $selectStatement.=$value.", ";
                }
            }

            //Remove the last comma and space
            $selectStatement = substr($selectStatement, 0, -2);

            // get all query
            $query = "SELECT " . $selectStatement . " FROM " . $this->tableName;

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // create a new ticket
    public function create(Ticket $ticket): bool {
        try {
            $query = "INSERT INTO " . $this->tableName . "
                      SET
                          `ticketType` = :ticketType,
                          `eventType` = :eventType,
                          `eventId` = :eventId,
                          `price` = :price,
                          `inStock` = :inStock";

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

            $ticket->setId((int)Base::getInstance()->conn->lastInsertId());

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            var_dump($e);
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

            $query = "SELECT musicians.name, acts.startTime, acts.endTime, acts.location, inStock, tickets.id, tickets.ticketType, tickets.price
                            FROM `act_musician` 
                            JOIN musicians ON musicians.id=musicianId
                            JOIN acts ON acts.id=actId
                            JOIN tickets ON acts.id=tickets.eventId
                            WHERE acts.eventId = :eventId AND acts.date LIKE :day AND (eventType = 3 OR eventType = 0)
                            ORDER BY acts.startTime";

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

            $query = "SELECT language, guide, inStock, tickets.id, tickets.ticketType, tickets.price, date FROM `tickets` 
                            JOIN historic_tours ON historic_tours.id = tickets.eventId
                            WHERE tickets.eventType = 2 AND historic_tours.date LIKE :day
                            ORDER BY date";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

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
