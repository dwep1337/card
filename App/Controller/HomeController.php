<?php

namespace App\Controller;

class HomeController
{
    public function index(): void {
        include 'views/home/home.php';
    }
}