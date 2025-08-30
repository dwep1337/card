<?php

namespace App\Controller;

use App\Database\Database;
use App\Repository\AdminRepository;
use App\Utils\JwtUtil;

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
        include "views/login/login.php";
    }

    public function index()
    {
        if ($this->userIsAuthenticated()) {
            header('Location: /admin/dashboard');
            exit();
        }

        $this->renderLoginPageView();
    }

    private function userIsAuthenticated(): bool
    {
        if (!isset($_COOKIE['token'])) {
            return false;
        }

        $token = $_COOKIE['token'];

        if (!JwtUtil::decode($token)) {
            return false;
        }

        return true;
    }
}