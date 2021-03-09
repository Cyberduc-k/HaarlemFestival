<?php

// Functions commonly used in all the DAO's
require_once(__DIR__ . "/../models/ErrorLog.php");
require_once(__DIR__ . "/../services/ErrorService.php");

class DAOUtils {
    // Handle an error and return null, isInTransaction is for future implementation
    protected function handleNullError(Exception $e, bool $isInTransaction) {
        if($isInTransaction) $this->rollback();
            $this->handle($e);

        return null;
    }

    // Handle an error and return false
    protected function handleFalseError(Exception $e, bool $isInTransaction): bool {
        if($isInTransaction) $this->rollback();
            $this->handle($e);

        // Return false
        return false;
    }

    // Roll the current transaction back, used for multiple queries in UserEditsDAO->createEditStashAndEditKey
    private function rollback() {
        if (Base::getInstance()->conn->inTransaction()) {
            // There was an error while performing a transaction so rollback
            Base::getInstance()->conn->rollback();
        }
    }

    // Log error
    private function handle(Exception $e) {
        $error = new ErrorLog();
        $error->setMessage($e->getMessage());
        $error->setStackTrace($e->getTraceAsString());

        ErrorService::getInstance()->create($error);
    }
}

?>
