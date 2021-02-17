<?php
require_once("Base.php");
require_once ("DAOUtils.php");
//DAO for the users
class UserDAO extends DAOUtils
{
    // table names
    private string $tableName = "users";
    private string $avatarTableName = "avatars";

    // get all users
    public function getAll(): ?PDOStatement
    {
        try{
            // get all query
            $query = "SELECT
                id, firstname, lastname, password, salt, email, register_date, usertype
            FROM
                " . $this->tableName;

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get all users that match these args
    public function getWithArgs(array $args): ?PDOStatement
    {
        try{
            // get all query
            $query = "SELECT
                id, firstname, lastname, password, salt, email, register_date, usertype
            FROM
                " . $this->tableName . "
                WHERE ";

            //Extract the key value pairs and add them to the query
            extract($args);
            foreach($args as $key => $value){
                if(!empty($value)){
                    //register date has to be converted to date from datetime and uses = instead of LIKE
                    if($key == "registerDate"){
                        $query .= "DATE(register_date) = '";
                    }
                    else{
                        $query .= "`".$key."`";
                        $query .= " LIKE '";
                    }
                    $query .= $value."' AND ";
                }
            }
            //To cancel out the last AND and avoid errors when there are no arguments.
            //This is cheaper then trying to figure out when not to add AND
            $query .= "TRUE";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get a single user by id
    public function getById(int $id): ?PDOStatement
    {
        try{
            // query to read single record
            $query = "SELECT
                id, firstname, lastname, password, salt, email, register_date, usertype
            FROM
                " . $this->tableName . " WHERE id = ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $id);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get a users type by id
    public function getTypeById(int $id): ?PDOStatement
    {
        try{
            // query to read single record
            $query = "SELECT
                usertype
            FROM
                " . $this->tableName . " WHERE id = ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $id);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get a single user by email
    public function getByEmail(string $email): ?PDOStatement
    {
        try {
            // query to read single record
            $query = "SELECT
                id, firstname, lastname, password, salt, email, register_date, usertype
            FROM
                " . $this->tableName . " WHERE email = ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $email);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        } catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get a single user by name
    public function getByName(string $firstname, string $lastname): ?PDOStatement
    {
        try{
            // query to read single record
            $query = "SELECT
                id, firstname, lastname, password, salt, email, register_date, usertype
            FROM
                " . $this->tableName . " WHERE firstname LIKE ? AND lastname LIKE ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind name of type to be retrieved
            $stmt->bindParam(1, $firstname);
            $stmt->bindParam(2, $lastname);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // get all users by register date
    public function getByRegisterDate(DateTime $registerDate): ?PDOStatement
    {
        try{
            // query to read necessary amount of records
            $query = "SELECT
                id, firstname, lastname, password, salt, email, register_date, usertype
            FROM
                " . $this->tableName . " WHERE register_date = ?";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $registerDate);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // create new user
    public function create(User $user): bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->tableName . "
            SET
                firstname=:firstname, lastname=:lastname, password=:password, 
                salt=:salt, email=:email, usertype=:usertype";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $firstname = (string)$user->getFirstname();
            $lastname = (string)$user->getLastname();
            $password = (string)$user->getPassword();
            $salt = (string)$user->getSalt();
            $email = (string)$user->getEmail();
            $usertype = (int)$user->getUsertype();

            // bind values
            $stmt->bindParam(":firstname", $firstname);
            $stmt->bindParam(":lastname", $lastname);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":salt", $salt);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":usertype", $usertype);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // check if a user is registered with this mail
    public function mailExists(String $email): ?PDOStatement{
        try{
            // query to check if it exists
            $query = "SELECT 1 FROM " . $this->tableName . " WHERE email LIKE ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare( $query );
            Base::getInstance()->conn->beginTransaction();

            // bind name of type to be retrieved
            $stmt->bindParam(1, $email);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // check if a user is registered with this id
    public function idExists(int $id): ?PDOStatement{
        try{
            // query to check if id exists
            $query = "SELECT 1 FROM " . $this->tableName . " WHERE id = ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare( $query );
            Base::getInstance()->conn->beginTransaction();

            // bind name of type to be retrieved
            $stmt->bindParam(1, $id);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // update the User
    public function update(User $user): bool{
        try{
            // update query
            $query = "UPDATE
                    " . $this->tableName . "
                SET
                    firstname=:firstname, lastname=:lastname, password=:password, 
                    salt=:salt, email=:email, usertype=:usertype
                WHERE
                    id = :id";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // cast references into variables to avoid error
            $id = (int)$user->getId();
            $lastname = (string)$user->getLastname();
            $password = (string)$user->getPassword();
            $salt = (string)$user->getSalt();
            $email = (string)$user->getEmail();
            $usertype = (int)$user->getUsertype();
            $firstname = (string)$user->getFirstname();

            // bind values
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":firstname", $firstname);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":salt", $salt);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":usertype", $usertype);
            $stmt->bindParam(":lastname", $lastname);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        }
        catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // delete a User
    public function delete(int $id): bool{
        try{
            // update query
            $query = "DELETE FROM
                    " . $this->tableName . "
                WHERE
                    id = :id";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind values
            $stmt->bindParam(":id", $id);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        }
        catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }

    // Get a profile picture, im using email instead of id to find an excuse to use JOINS lol
    public function getAvatarByEmail(string $email): ?PDOStatement{
        try{
            // query to read single record
            $query = "SELECT avatar
            FROM " . $this->avatarTableName . " 
            INNER JOIN ".$this->tableName." ON " . $this->tableName.".id = ".$this->avatarTableName . ".id
                 AND ".$this->tableName.".email LIKE ? LIMIT 0,1";

            // prepare query statement
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind id of type to be retrieved
            $stmt->bindParam(1, $email);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return $stmt;
        }
        catch (Exception $e) {
            return $this->handleNullError($e, true);
        }
    }

    // Set avatar of user
    public function setAvatar(int $id, string $avatar): bool
    {
        try {
            // query to insert record
            $query = "INSERT INTO
                " . $this->avatarTableName . "
            SET
                id=:id, avatar=:avatar
            ON DUPLICATE KEY UPDATE avatar=:avatar";

            // prepare query
            $stmt = Base::getInstance()->conn->prepare($query);
            Base::getInstance()->conn->beginTransaction();

            // bind values
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":avatar", $avatar);

            // execute query
            $stmt->execute();

            //If we get tot this point there are no errors so we can commit
            Base::getInstance()->conn->commit();

            return true;
        } catch (Exception $e) {
            return $this->handleFalseError($e, true);
        }
    }
}
?>