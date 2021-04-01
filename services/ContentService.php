<?php

require_once(__DIR__ . "/../DAL/ContentDAO.php");
require_once(__DIR__ . "/../models/Content.php");
require_once ("ServiceUtils.php");

class ContentService extends ServiceUtils {
    private ContentDAO $dao;

    public function __construct() {
        $this->dao = new ContentDAO();
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

    // Try to get content by eventId
    public function getByEventId(int $id): ?Content {
        try {
            $stmt = $this->dao->getByEventId($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToContent($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty content
            return null;
        }
    }

    // Update all columns of content
    public function update(Content $content): bool {
        if ($this->dao->update($content))
            return true;

        return false;
    }

    public function addContentPage(Content $content)
    {
        try {

            if ($this->dao->addContentPage($content))
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

    public function deleteByEventId(int $eventId): bool {
        if ($this->dao->deleteByEventId($eventId))
            return true;

        return false;
    }
}
?>
