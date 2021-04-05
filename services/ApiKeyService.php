<?php

require_once(__DIR__ . "/../models/ApiKey.php");
require_once(__DIR__ . "/../DAL/ApiKeyDAO.php");
require_once(__DIR__ . "/../services/ServiceUtils.php");

// Only start a session when necessary
if(!isset($_SESSION)) session_start();

class ApiKeyService extends ServiceUtils {
    private ApiKeyDAO $dao;

    public function __construct() {
        $this->dao = new ApiKeyDAO();
    }

    // Get all keys
    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $keys = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($keys, $this->rowToApiKey($row));
                }

                return $keys;
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    // Try to get a key by email
    public function getByEmail(string $email): ?ApiKey {
        try {
            $stmt = $this->dao->getByEmail($email);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToApiKey($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty user
            return null;
        }
    }

    // Create a new key
    public function create(ApiKey $key): bool{
        try {
            if ($this->dao->create($key))
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

    // Update a key
    public function update(ApiKey $key): bool {
        if ($this->dao->update($key))
            return true;

        return false;
    }

    // Delete a key
    public function delete(string $email): bool {
        if ($this->dao->delete($email))
            return true;

        return false;
    }

    // Validate a key
    public function validate(ApiKey $key): bool {
        try {
            $stmt = $this->dao->validate($key);
            $num = $stmt->rowCount();

            return ($num == 1);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return false
            return false;
        }
    }
}
?>