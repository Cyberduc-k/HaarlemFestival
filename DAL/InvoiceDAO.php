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
                          id, userId, userAddress, userPhone, subtotal, tax, date, dueDate
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
                          id, userId, userAddress, userPhone, subtotal, tax, date, dueDate
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
}

?>
