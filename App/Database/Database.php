<?php

namespace App\Database;

use PDO, PDOException;

class Database
{
    private PDO $connection;
    private string $host;
    private string $user;
    private string $password;
    private string $database;

    public function __construct()
    {
        $this->loadEnvConnection();
        $this->connect();
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function connect() : void
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            die("Database connection fail: " . $e->getMessage());
        }
    }

    private function loadEnvConnection(): void
    {
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->database = $_ENV['DB_DATABASE'];
    }
}