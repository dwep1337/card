<?php

namespace App\Controller;

class DashboardController
{
    public function index()
    {
        include "views/dashboard/dashboard.php";
    }

    public function logout(): void
    {
        if (isset($_COOKIE['token'])) {
            unset($_COOKIE['token']);
            setcookie('token', '', time() - 3600, '/');
        }
        http_response_code(200);
    }
}