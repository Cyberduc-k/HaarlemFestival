<?php

require_once(__DIR__ . "/../models/ResetKey.php");
require_once(__DIR__ . "/../DAL/PasswordDAO.php");
require_once("PasswordHasher.php");
require_once("ServiceUtils.php");

// Only start a session when necessary
if (!isset($_SESSION)) session_start();

class PasswordService extends ServiceUtils {
    private PasswordDAO $dao;

    public function __construct() {
        $this->dao = new PasswordDAO();
    }

    // Try to get a ResetKey by UserId
    public function getById(int $id): ?ResetKey {
        try {
            $stmt = $this->dao->getByUserId($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToResetKey($row);
            }

            return null;
        } catch (Exception) {
            return null;
        }
    }

    // Add a new reset Key
    public function create(ResetKey $resetKey): bool {
        try {
            if ($this->dao->create($resetKey))
                return true;

            return false;
        } catch (Exception $e) {
            $_SESSION["passwordError"] = "Could not generate reset link".$e;
            return false;
        }
    }
}

?>
