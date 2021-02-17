<?php
require_once(__DIR__ . "/../models/EditKey.php");
require_once(__DIR__ . "/../DAL/EditEmailKeyDAO.php");
require_once("ServiceUtils.php");

//Only start a session when necessary
if(!isset($_SESSION)) session_start();

class EditEmailKeyService extends ServiceUtils
{
    private EditEmailKeyDAO $dao;

    public function __construct()
    {
        $this->dao = new EditEmailKeyDAO();
    }

    //Try to get a EditEmailKey by editId
    public function getById(int $id): ?EditEmailKey{
        try{
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if($num > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToEditEmailKey($row);
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

    //Try to get a EditEmailKey by editId
    public function getByKey(String $key): ?EditEmailKey{
        try{
            $stmt = $this->dao->getByKey($key);
            $num = $stmt->rowCount();

            if($num > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToEditEmailKey($row);
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

    //Verify a key against what the key should be
    public function verifyKey(String $key, bool $isNew): bool{
        try{
            $key = $this->getByKey($key);
            if(!is_null($key)){
                if($isNew)
                    $key->setNewStatus(true);
                else
                    $key->setOldStatus(true);

                //Update status of a key, notify of both keys are OK now
                if($this->update($key)){
                    if($key->isOldStatus() == $key->isNewStatus()){
                        //Used to track if an edit should occur
                        $_SESSION["bothKeysOK"] = "1";
                    }

                    return true;
                }
                else{
                    $_SESSION["keyVerification"] = "Status of key couldnt be updated";
                }
            }
            else {
                $_SESSION["keyVerification"] = "Verification of key failed";
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

    //Update status of a key
    public function update(EditEmailKey $editEmailKey): bool{
        if($this->dao->update($editEmailKey))
            return true;

        return false;
    }
}
?>