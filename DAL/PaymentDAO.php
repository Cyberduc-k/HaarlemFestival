<?php
require_once("Base.php");
require_once("DAOUtils.php");

class PaymentDAO extends DAOUtils
{
    private string $tableName = "orders";

    function newPayment(string $orderId, string $status, int $userId): ?bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                id=:orderId, status=:status, userId=:userId";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind values
            $stmt->bindParam(":orderId", $orderId);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":userId", $userId);

            // execute query
            $stmt->execute();

            // If we get to this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    function updateStatus(string $orderId, string $paymentId, string $status, string $userId): ?bool
    {
        try {
            $query = "INSERT INTO
              " . $this->tableName . "
              SET
              id=:orderId, paymentId=:paymentId, status=:status, userId=:userId
              ON DUPLICATE KEY UPDATE
              status=:status";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind parameters
            $stmt->bindParam(":orderId", $orderId);
            $stmt->bindParam(":paymentId", $paymentId);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam("userId", $userId);

            // execute query
            $stmt->execute();

            // If we get to this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    function getPaymentId(string $orderId): ?PDOStatement {
        try {
            $query = "SELECT
            paymentId
            FROM "
            . $this->tableName . "
            WHERE id=:orderId";

            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":orderId", $orderId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e ) {
            return $this->handleNullError($e, true);
        }
    }

    function getStatusByOrderId($orderId): ?PDOStatement
    {
        try {
            $query = "SELECT 
                id, status
            FROM
                " . $this->tableName . "
            WHERE 
                id=:id LIMIT 0,1";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind values
            $stmt->bindParam(":id", $orderId);

            // execute query
            $stmt->execute();

            // If we get to this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    function orderIdExists(string $orderId): ?PDOStatement {
        try {
            $query = "SELECT
                1
            FROM
                " . $this->tableName . "
            WHERE
                id = :orderId LIMIT 0,1";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind value
            $stmt->bindParam(":orderId", $orderId);
            // execute query
            $stmt->execute();

            // If we get to this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    function getOrdersFromUser(int $userId): ?PDOStatement {
        try {
            $query = "SELECT id, status, userId
            FROM " . $this->tableName . "
            WHERE userId = :userId";

            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":userId", $userId);
            $stmt->execute();

            Base::getInstance()->conn->commit();
            return $stmt;

        }catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

}