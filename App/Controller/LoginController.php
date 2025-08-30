<?php

namespace App\Controller;

use App\Database\Database;
use App\Repository\AdminRepository;

class LoginController
{
    private const array COOKIE_OPTIONS = [
        'expires' => 0,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict'
    ];

    private AdminRepository $adminRepository;

    public function __construct()
    {
        $db = new Database();
        $this->adminRepository = new AdminRepository($db->getConnection());
    }

    private function renderLoginPageView(): void
    {
        include __DIR__ . "/../../views/login/login.php";
    }

    public function index()
    {

    }
}