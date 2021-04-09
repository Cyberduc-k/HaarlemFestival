<?php

require_once(__DIR__ . "/../DAL/RestaurantDAO.php");
require_once(__DIR__ . "/../models/Restaurant.php");
require_once ("ServiceUtils.php");

class RestaurantService extends ServiceUtils
{
    private RestaurantDAO $dao;

    public function __construct()
    {
        $this->dao = new RestaurantDAO();
    }

    // Get all the content from database
    public function getAll(): ?array
    {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $contents = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($contents, $this->rowToRestaurant($row));
                }

                return $contents;
            }

            return array();
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    public function getById(int $id): ?Restaurant {
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToRestaurant($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function addRestaurant(Restaurant $restaurant)
    {
        try {

            if ($this->dao->addRestaurant($restaurant))
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