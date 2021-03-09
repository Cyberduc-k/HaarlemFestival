<?php

// Base class for all database connections in the DAO's
class Base{
    private static $instance = null;

    // Credentials
    private String $host = "bramsierhuis.nl.mysql";
    private String $db_name = "bramsierhuis_nl_workinghours";
    private String $username = "bramsierhuis_nl_workinghours";
    private String $password = "_#Br4manne1";
    public ?PDO $conn = null;

    // Make connection on construction
    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    // Singleton
    public static function getInstance() {
        if (self::$instance == null)
        {
            self::$instance = new Base();
        }

        return self::$instance;
    }
}

?>
