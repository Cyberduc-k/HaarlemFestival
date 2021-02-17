<?php
require_once(__DIR__ . "/../models/EditStash.php");
require_once(__DIR__ . "/../models/EditKey.php");
require_once(__DIR__ . "/../DAL/UserEditsDAO.php");
require_once(__DIR__ . "/../services/UserService.php");
require_once("ServiceUtils.php");

//Only start a session when necessary
if(!isset($_SESSION)) session_start();

class UserEditsService extends ServiceUtils
{
    private UserEditsDAO $dao;

    public function __construct()
    {
        $this->dao = new UserEditsDAO();
    }

    // Add a new stash
    public function createEditStashAndEditKey(EditStash $stash, EditKey $editKey): bool{
        try{
            if($this->dao->createEditStashAndEditKey($stash, $editKey))
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

    // Add a new stash
    public function createEditStashAndEditEmailKey(EditStash $stash, EditEmailKey $editEmailKey): bool{
        try{
            if($this->dao->createEditStashAndEditEmailKey($stash, $editEmailKey))
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

    // Add a new stash
    public function performEdit(int $id): bool{
        try{
            $stash = $this->getById($id);
            if(!is_null($stash)){
                $userService = new UserService();
                if($userService->update($stash))
                    return true;
            }
        }
        catch(Exception $e){
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
        }

        return false;
    }

    //Try to get a stash by id
    public function getById(int $id): ?EditStash{
        try{
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if($num > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToEditStash($row);
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

    //Get next available id
    public function getNextId(): ?int{
        try{
            $stmt = $this->dao->getNextId();
            $num = $stmt->rowCount();

            if($num > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return (int)$row["stashId"] + 1;
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
}
?>