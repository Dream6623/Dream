<?php

namespace App\Http\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * 接口鉴权
 */
class JwtService
{
    //受保护的密钥
    protected static $key = '****';

    /**
     * 创建token
     */
    public static function CreateToken()
    {
        $token = [
            'iss' => 'http://work.org',
            'aud' => 'http://work.com',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 3600
        ];

        $jwt = JWT::encode($token, self::$key, "HS256");
        return $jwt;
    }

    /**
     * 解密token
     */
    public static function decrypt($token)
    {
        $info = JWT::decode($token, new Key(self::$key, "HS256"));
        return $info;
    }
}
