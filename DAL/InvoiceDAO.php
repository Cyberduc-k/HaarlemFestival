<?php

require_once("Base.php");
require_once("DAOUtils.php");

// DAO for invoices
class InvoiceDAO extends DAOUtils {
    // table names
    private string $tableName = "invoices";

    // get all invoices
    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT
                          id, userId, userAddress, userPhone, tax, `date`, dueDate
                      FROM " . $this->tableName . "
                      ORDER BY `date` DESC";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
       } catch (Exception $e) {
           return $this->handleNullError($e, true);
       }
    }

    // get a single invoice by id
    public function getById(int $id): ?PDOStatement {
        try {
            $query = "SELECT
                          id, userId, userAddress, userPhone, tax, `date`, dueDate
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
    public function create(Invoice $invoice): bool {
        try {
            $query = "INSERT INTO " . $this->tableName . "
                      SET userId = :userId,
                          userAddress = :userAddress,
                          userPhone = :userPhone,
                          tax = :tax,
                          `date` = :date,
                          dueDate = :dueDate";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid type errors
            $userId = (int)$invoice->getUserId();
            $userAddress = (string)$invoice->getUserAddress();
            $userPhone = (string)$invoice->getUserPhone();
            $tax = (float)$invoice->getTax();
            $date = $invoice->getDate()->format("Y-m-d H:i:s");
            $dueDate = $invoice->getDueDate()->format("Y-m-d H:i:s");

            // bind values
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":userAddress", $userAddress);
            $stmt->bindParam(":userPhone", $userPhone);
            $stmt->bindParam(":tax", $tax);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":dueDate", $dueDate);

            $stmt->execute();
            $invoice->setId((int)Base::getInstance()->conn->lastInsertId());

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // add a ticket to an invoice
    public function addTicket(int $invoiceId, int $ticketId, int $count): bool {
        try {
            $query = "INSERT INTO `invoice_ticket`
                      SET invoiceId = :invoiceId,
                          ticketId = :ticketId,
                          nTickets = :nTickets";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":invoiceId", $invoiceId);
            $stmt->bindParam(":ticketId", $ticketId);
            $stmt->bindParam(":nTickets", $count);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    public function getForOrder(int $userId, string $orderId): ?PDOStatement{
        try {
            $query = "SELECT i.id, i.userId, i.userAddress, i.userPhone, i.tax, i.`date`, i.dueDate 
            FROM " . $this->tableName . " AS i 
            JOIN orders AS o on o.userId = i.userId
            WHERE i.userId = :userId AND o.id = :orderId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":orderId", $orderId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }
}

?>
