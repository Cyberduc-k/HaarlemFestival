<?php
require_once(__DIR__ . "/../DAL/ErrorDAO.php");
require_once(__DIR__ . "/../models/ErrorLog.php");
class ErrorService
{
    private static ?ErrorService $instance = null;
    private ErrorDAO $dao;

    private function __construct()
    {
        $this->dao = new ErrorDAO();
    }

    // Singleton
    public static function getInstance(): ?ErrorService
    {
        if (self::$instance == null)
        {
            self::$instance = new ErrorService();
        }

        return self::$instance;
    }

    // Add a new Error to the db
    public function create(ErrorLog $error): bool{
        try{
            if($this->dao->create($error))
                return true;

            return false;
        }
        catch(Exception $e){
            return false;
        }
    }
}
?>