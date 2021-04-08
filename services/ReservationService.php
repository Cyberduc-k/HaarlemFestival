<?php

require_once(__DIR__ . "/../DAL/ImageDAO.php");
require_once(__DIR__ . "/../models/Image.php");
require_once ("ServiceUtils.php");

class ReservationService extends ServiceUtils {
    private ImageDAO $dao;

    public function __construct() {
        $this->dao = new ImageDAO();
    }

    public function addImage(Image $image)
    {
        try {

            if ($this->dao->addImage($image))
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
}