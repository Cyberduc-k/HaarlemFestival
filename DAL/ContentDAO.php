<?php

require_once("Base.php");
require_once("DAOUtils.php");

class ContentDAO extends DAOUtils {
    private string $tableName = "content_pages";

    public function getAll(): ?PDOStatement {
        try {
            $query = "SELECT id, eventId, header, text, imagePath
            FROM " . $this->tableName ;

            $stmt = Base::getInstance()->conn->prepare($query);

            Base::getInstance()->conn->beginTransaction();

            $stmt->execute();

            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    public function getByEventId(int $id): ?PDOStatement {
        try {
            // query to read single record
            $query = "SELECT
                id, eventId, header, text, imagePath
            FROM
                " . $this->tableName . " WHERE eventId = ?";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $id);

            // execute query
            $stmt->execute();

            // If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // update content
    public function update(Content $content): bool {
        try {
            // update query
            // eventid ook updaten?
            $query = "UPDATE
                    " . $this->tableName . "
                SET
                    header=:header, text=:text, imagePath=:imagePath
                WHERE
                    id = :id";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $id = (int)$content->getId();
            $header = (string)$content->getHeader();
            $text = (string)$content->getText();
            $imagePath = (string)$content->getImagePath();

            // bind values
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":header", $header);
            $stmt->bindParam(":text", $text);
            $stmt->bindParam(":imagePath", $imagePath);

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