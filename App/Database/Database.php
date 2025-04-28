<?php

namespace App\Database;

use PDO, PDOException;

class Database
{
    //TODO : Add environment variables for database connection
    private PDO $connection;
    private string $host = 'localhost';
    private string $user = 'root';
    private string $password = '';
    private string $database = 'securecard';

    public function __construct()
    {
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
}