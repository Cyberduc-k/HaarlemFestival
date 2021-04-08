<?php
require_once("Base.php");
require_once("DAOUtils.php");

// DAO for the images
class ReservationDAO extends DAOUtils
{
    private string $tableName = "images";

    public function addImage(Image $image): ?bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                id = :id, contentPageId = :contentPageId, name = :name";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $id = (int)$image->getId();
            $contentPageId = (int)$image->getContentPage();
            $name = (string)$image->getName();

            // bind values
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":contentPageId", $contentPageId);
            $stmt->bindParam(":name", $name);

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