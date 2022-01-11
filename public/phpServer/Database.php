<?php

class Database {

    private $host = 'localhost';
    private $db_name = 'tasktracker';
    private $username = 'adrian';
    private $password = 'asdasd321';
    private $taskTable = 'tasks';

    private $conn;

    /**
     * Connection
     * @return PDO|null
     */
    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO (
                'mysql:host=' .  $this->host . ';dbname='  . $this->db_name,
                $this->username, $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

    /**
     * Install script for db
     * @return bool
     */
    public function setup()
    {
        try {
            $this->conn = new PDO (
                'mysql:host=' .  $this->host,
                $this->username, $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );


            $this->conn->exec('CREATE DATABASE IF NOT EXISTS ' . $this->db_name);
            $this->conn->exec('use ' . $this->db_name);

            $query = "CREATE TABLE IF NOT EXISTS " . $this->taskTable . " (
                `id` int(11) AUTO_INCREMENT PRIMARY KEY,
                `task` varchar(100) DEFAULT NULL,
                `owner` varchar(100) DEFAULT NULL,
                `doing` tinyint(1) DEFAULT NULL,
                `done` tinyint(1) DEFAULT NULL,
                `time` varchar(255) DEFAULT NULL
                )";

            $stmt = $this->conn->prepare($query);

            return $stmt->execute();

        } catch (PDOException $e) {
            echo json_encode(
                array(
                    'message' => 'DB ERROR: ' . $e->getMessage(),
                    'err' => true
                )
            );
            return false;
        }
    }

}