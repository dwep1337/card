<?php

namespace App\Controller;

use App\Database\Database;
use App\Repository\AdminRepository;
use App\Utils\JwtUtil;
use Exception;


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

    private function getValidatedInput(): array
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->sendJsonResponse(true, 'Invalid username or password', 400);
            }

            if (empty($input['username']) || empty($input['password'])) {
                $this->sendJsonResponse(true, 'Invalid username or password', 401);
            }
            return $input;
        }
        catch (Exception $e) {
            $this->sendJsonResponse(true, 'Invalid username or password', 400);
            exit();
        }
    }

    private function authenticateUser(string $username, string $password): array
    {
        $admin = $this->adminRepository->findAdminByName($username);

        if (!$admin || !password_verify($password, $admin['password'])) {
            $this->sendJsonResponse(true, 'Invalid username or password', 401);
            exit();
        }

        return $admin;
    }

    private function setAuthCookie(array $admin): string
    {
        $token = JwtUtil::encode([
            'username' => $admin['nome'],
            'id' => $admin['id']
        ], 3600); // 1 hour

        $options = self::COOKIE_OPTIONS;
        $options['expires'] = time() + 3600;

        setcookie('token', $token, $options);

        return $token;
    }

    private function sendJsonResponse(bool $error, string $message, int $statusCode = 200, array $additionalData = []): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['error' => $error, 'message' => $message, ...$additionalData], JSON_THROW_ON_ERROR);
    }

    public function login()
    {
        if ($this->userIsAuthenticated()) {
            header('Location: /admin/dashboard');
            exit();
        }

        try {
            $data = $this->getValidatedInput();
            $admin = $this->authenticateUser($data['username'], $data['password']);

            $token = $this->setAuthCookie($admin);
            $this->sendJsonResponse(false, 'Login successful', 200, ['token' => $token]);
            exit();
        }
        catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo $e->getMessage();
            exit();
        }
    }
}