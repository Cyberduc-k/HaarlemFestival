<?php

require_once("Base.php");
require_once("DAOUtils.php");

// DAO for the images
class ImageDAO extends DAOUtils
{
    private string $tableName = "images";

    // get all venues
    public function getAll(): ?PDOStatement
    {
        try {
            $query = "SELECT
                          id, contentPageId
                      FROM " . $this->tableName;

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get a single image by id
    public function getById(int $id): ?PDOStatement
    {
        try {
            $query = "SELECT
                          id, contentPageId
                      FROM " . $this->tableName . "
                      WHERE id = :id";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":id", $id);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    public function getByContentPageId(int $contentPageId): ?PDOStatement
    {
        try {
            $query = "SELECT
                          id, contentPageId
                      FROM " . $this->tableName . "
                      WHERE contentPageId = :contentPageId";

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->bindParam(":contentPageId", $contentPageId);
            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    public function addImage(Image $image): ?bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                id=:id, contentPageId=:contentPageId";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $id = (int)$image->getId();
            $contentPageId = (int)$image->getContentPage();

            // bind values
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":contentPageId", $contentPageId);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }
}

?>
