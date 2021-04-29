<?php


namespace App\Http\Commons;


class HashCode
{
    private static $encryptMethod = "AES-256-CBC";
    private static $secret_key = 'mediaserver';
    private static $secret_iv = 'mediasecret';
    private static $length = 16;


    static function encrypt($string)
    {
//        $output = false;
//        // hash
//        $key = self::getKey();
//        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
//        $iv = self::getIV();
//        $output = openssl_encrypt($string, self::$encryptMethod, $key, 0, $iv);
//
//        $output = base64_encode($output);
//        if (strlen($output) >= 32)
//            return substr($output, 32);
//        else return self::encrypt($output . now());


        $seed = self::generateRandomString() . $string;
        return substr(hash('sha256', $seed), 0, 32);
    }

    private static function generateRandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < self::$length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    static function decrypt($string)
    {
        $key = self::getKey();
        $iv = self::getIV();
        return openssl_decrypt(base64_decode($string), self::$encryptMethod, $key, 0, $iv);
    }

    private static function getKey()
    {
        return hash('sha256', self::$secret_key);
    }

    private static function getIV()
    {
        return substr(hash('sha256', self::$secret_iv), 0, self::$length);
    }

}
