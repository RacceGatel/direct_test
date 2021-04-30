<?php

class Database
{
    private $host;
    private $name;
    private $user;
    private $password;

    public $conn;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->name = $_ENV['DB_NAME'];
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    public function Connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'].';charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

        } catch (PDOException $ex) {
            throw $ex;
        }

        return $this->conn;
    }

    public function closeConnect() {
        if (!is_null($this->conn)) {
            $this->conn->close();
        }
    }
}