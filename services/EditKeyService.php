<?php
require_once(__DIR__ . "/../models/EditKey.php");
require_once(__DIR__ . "/../DAL/EditKeyDAO.php");
require_once("ServiceUtils.php");

//Only start a session when necessary
if(!isset($_SESSION)) session_start();

class EditKeyService extends ServiceUtils
{
    private EditKeyDAO $dao;

    public function __construct()
    {
        $this->dao = new EditKeyDAO();
    }

    //Try to get a EditKey by editId
    public function getById(int $id): ?EditKey{
        try{
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if($num > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToEditKey($row);
            }

            return null;
        }
        catch(Exception $e){
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    // Add a new edit Key
    public function create(EditKey $editKey): bool{
        try{
            if($this->dao->create($editKey))
                return true;

            return false;
        }
        catch(Exception $e){
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }
}
?>