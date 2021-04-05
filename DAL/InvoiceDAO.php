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
                          id, userId, userAddress, userPhone, tax, date, dueDate
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

    // get a single invoice by id
    public function getById(int $id): ?PDOStatement {
        try {
            $query = "SELECT
                          id, userId, userAddress, userPhone, tax, date, dueDate
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
}

?>
