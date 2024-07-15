<?php

namespace App\Security;

use Config;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuthenticator {

    /*--- ENCODE JWT ---*/

    public static function encodeJwt() {

        include_once('../Config.php');

        $payload = [
            'iss' => 'faismoiunrprix',
            'aud' => 'faismoiunprix',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 7200
        ];

        



        $jwt = JWT::encode($payload, Config::getKeyOpensslEncrypt(), 'HS256');

        return $jwt;
    }







    /*--- DECODE JWT ---*/

    public static function decodeJwt($jwt) {

        include_once('../Config.php');

        $decoded = JWT::decode($jwt, new Key(Config::getKeyOpensslEncrypt(), 'HS256'));

        return $decoded;
    }
}