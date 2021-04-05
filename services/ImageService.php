<?php

require_once(__DIR__ . "/../DAL/ContentDAO.php");
require_once(__DIR__ . "/../models/Content.php");
require_once ("ServiceUtils.php");

class ImageService extends ServiceUtils {
    private ImageDAO $dao;

    public function __construct() {
        $this->dao = new ImageDAO();
    }

    // Get all the content from database
    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $contents = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($contents, $row);
                }

                return $contents;
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

    // Try to get image by Id
    public function getById(int $id): ?Image {
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToImage($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty image
            return null;
        }
    }

    // Try to get image by contentPageId
    public function getByContentPageId(int $contentPageId): ?Image {
        try {
            $stmt = $this->dao->getByContentPageId($contentPageId);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToImage($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty image
            return null;
        }
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
?>
