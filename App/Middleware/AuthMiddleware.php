<?php

namespace App\Middleware;

use App\Utils\JwtUtil;

class AuthMiddleware
{
    public function handle(): bool
    {
        $token = $this->getTokenFromCookie();

        if (!isset($token)) {
            $this->redirectToLogin();
            return false;
        }

        try {
            if (!JwtUtil::decode($token)) {
                $this->redirectToLogin();
                return false;
            }
        } catch (\Exception $e) {
            $this->redirectToLogin();
            return false;
        }

        return true;
    }

    private function getTokenFromCookie(): ?string
    {
        return $_COOKIE['token'] ?? null;
    }

    private function clearCookie()
    {
        setcookie('token', '', time() - 3600, '/');
    }

    private function redirectToLogin(): void
    {
        http_response_code(401);
        $this->clearCookie();
        header('Location: /login');
        exit;
    }
}
