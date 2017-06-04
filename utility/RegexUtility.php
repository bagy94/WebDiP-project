<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 03.06.17.
 * Time: 15:53
 */
namespace bagy94\utility;
class RegexUtility
{
    private static $email = "/^([\w\d\_\-\.\?]+)@{1}([\w\d]+\.){1,5}\w+$/";
    private static $specChars = "/[\.\(\)\{\}\'\!\#\“\\/]+/";
    private static $containNumbers = "/([0-9]{1}){number}/";
    private static $uppercase = "/([A-Z]{1}){1}/";
    private static $dateFormat = "/^\d{2}\.\d{2}\.\d{4}\.?$/";

    public static function haveSpecialChars($value,$numberOfTimes=1)
    {
        $reg = str_replace("var",$numberOfTimes,self::$specChars);
        return preg_match($reg,$value);
    }

    public static function checkEmail($email)
    {
        return preg_match(self::$email,$email);
    }

    public static function haveNumber($value,$numberOfTimes = 1)
    {
        $reg = str_replace("number",$numberOfTimes,self::$containNumbers);
        return preg_match($reg,$value);
    }

    public static function haveUppercase($value)
    {
        return preg_match(self::$uppercase,$value);
    }

    public static function isBirthdayFormat($value){return preg_match(self::$dateFormat,$value);}

    public static function checkPassword($password)
    {
        return self::haveSpecialChars($password,2)
            && self::haveNumber($password,2)
            && self::haveUppercase($password);
    }

    public static function checkUserName($username)
    {
        return self::haveSpecialChars($username)
            && self::haveNumber($username);
    }

}