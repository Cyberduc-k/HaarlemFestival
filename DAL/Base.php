<?php

// Base class for all database connections in the DAO's
class Base{
    private static $instance = null;

    // Credentials
    private String $host = "185.224.138.70";
    private String $db_name = "u844582952_hf";
    private String $username = "u844582952_hf_user";
    private String $password = "HaarlemFestival2021";
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
