<?php


class Config {

    private static $key_openssl_encrypt = '';
    private static $key_geocode_maps = '';

    public static function getKeyOpensslEncrypt() {

        return self::$key_openssl_encrypt;
    }



    public static function setKeyOpensslEncrypt($key_openssl_encrypt) {

        self::$key_openssl_encrypt = $key_openssl_encrypt;

        return self::$key_openssl_encrypt;
    }



    public static function getKeyGeocodeMaps() {

        return self::$key_geocode_maps;
    }

    public static function setKeyGeocodeMaps($key_geocode_maps) {

        self::$key_geocode_maps = $key_geocode_maps;

        return self::$key_geocode_maps;
    }
}