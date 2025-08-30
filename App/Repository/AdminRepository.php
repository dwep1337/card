<?php

namespace App\Repository;

use PDO;

class AdminRepository
{
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function findAdminByName($name) {
        $stmt = $this->db->prepare(/** @lang text */ "SELECT * FROM admins WHERE nome = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }
}