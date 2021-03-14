<?php

require_once("Base.php");
require_once("DAOUtils.php");
require_once("../models/Ticket.php");

// DAO for the tickets
class TicketDAO extends DAOUtils {
    // table names
    private string $tableName = "tickets";

    // get all tickets
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          id, eventType, eventId, date, price, inStock
                      FROM " . $this->tableName . "
                      WHERE ";

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
                          id, eventType, eventId, date, price, inStock
                      FROM " . $this->tableName . "
                      WHERE ";

            extract($args);

            foreach ($args as $key => $value) {
                if (!empty($value)) {
                    if ($key == "date") {
                        $query .= "DATE(date) = '";
                    } else {
                        $query .= "`$key`";
                        $query .= " LIKE '";
                    }

                    $query .= $value . "' AND ";
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
                          id, eventType, eventId, date, price, inStock
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

    // create a new ticket
    public function create(Ticket $ticket): bool {
        try {
            $query = "INSERT INTO " . $this->tableName . "
                      SET
                          eventType=:eventType,
                          eventId=:eventId,
                          date=:date,
                          price=:price,
                          inStock=:inStock";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid type errors
            $eventType = (int)$ticket->getEventType();
            $eventId = (int)$ticket->getEventId();
            $date = $ticket->getDate();
            $price = (float)$ticket->getPrice();
            $inStock = (int)$ticket->getInStock();

            // bind values
            $stmt->bindParam(":eventType", $eventType);
            $stmt->bindParam(":eventId", $eventId);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":inStock", $inStock);

            $stmt->execute();

            Base::getInstance()->conn->comit();

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
                          eventType=:eventType,
                          eventId=:eventId,
                          date=:date,
                          price=:price,
                          inStock=:inStock
                      WHERE
                          id = :id";
            
            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid type errors
            $id = (int)$ticket->getId();
            $eventType = (int)$ticket->getEventType();
            $eventId = (int)$ticket->getEventId();
            $date = $ticket->getDate();
            $price = (float)$ticket->getPrice();
            $inStock = (int)$ticket->getInStock();

            // bind values
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":eventType", $eventType);
            $stmt->bindParam(":eventId", $eventId);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":inStock", $inStock);

            $stmt->execute();

            Base::getInstance()->conn->comit();

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
}

?>
