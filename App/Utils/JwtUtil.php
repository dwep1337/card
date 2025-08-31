<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtUtil
{
    final public static function encode(array $data, int $expiresIn): string{
        $jwtPayload = [
            'iat' => time(),
            'exp' => time() + $expiresIn, // Token expires in 1 hour
            'data' => $data
        ];
        return JWT::encode($jwtPayload, $_ENV['JWT_SECRET'], 'HS256');
    }

    final public static function decode($token): object | bool
    {
        try {
            return JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}