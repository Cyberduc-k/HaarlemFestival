<?php

require_once(__DIR__ . "/../DAL/ImageDAO.php");
require_once(__DIR__ . "/../models/Image.php");
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
                    array_push($contents, $this->rowToImage($row));
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
    public function getByContentPageId(int $contentPageId): ?array {
        try {
            $stmt = $this->dao->getByContentPageId($contentPageId);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $images = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($images, $this->rowToImage($row));
                }

                return $images;
            }

            return [];
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
