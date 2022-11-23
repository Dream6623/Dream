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
    protected static $key = 'Dream';

    /**
     * 创建token
     */
    public static function CreateToken($u_id)
    {
        $token = [
            'iss' => 'http://work.org',
            'aud' => 'http://work.com',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 84600,
            'u_id' => $u_id
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
