<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtUtil
{
    private string $JWT_SECRET;
    private int $JWT_EXPIRATION_TIME = 3600; // 1 hour


    public function __construct()
    {
        $this->JWT_SECRET = $_ENV['JWT_SECRET'];
    }

    final public static function encode(array $data): string{
        $jwtPayload = [
            'iat' => time(),
            'exp' => time() + self::$JWT_EXPIRATION_TIME,
            'data' => $data
        ];
        return JWT::encode($jwtPayload,  self::$JWT_SECRET, 'HS256');
    }

    final public static function decode($token): object | bool
    {
        try {
            return JWT::decode($token, new Key(self::$JWT_SECRET, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}