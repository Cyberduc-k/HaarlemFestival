<?php
require_once("Base.php");
require_once(__DIR__ . "/../models/ErrorLog.php");
//DAO for the errors
class ErrorDAO
{
    // table name
    private string $tableName = "errors";

    // create new error
    public function create(ErrorLog $error): bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                message=:message, stackTrace=:stackTrace";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $message = (string)$error->getMessage();
            $stackTrace = (string)$error->getStackTrace();

            // bind values
            $stmt->bindParam(":message", $message);
            $stmt->bindParam(":stackTrace", $stackTrace);

            // execute query
            $stmt->execute();
            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        }
        //Uses own rollback code to avoid an stack overflow, cant call ErrorService here
        catch (Exception $e) {
            if (Base::getInstance()->conn->inTransaction()) {
                //There was an error while performing a transaction so rollback
                Base::getInstance()->conn->rollback();
            }
            echo "Transaction was rolled back while logging error".$e;
        }

        return false;
    }
}
?>