<?php

namespace bagy94\webdip\wellness\model;
require_once "Model.php";

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 3.4.2017.
 * Time: 22:50
 */
class User extends Model
{
    const ADMIN = 1;
    const MODERATOR = 2;
    const REGULAR = 3;

    public static $tUserName = "user_name";
    public static $tEmail = "email";
    public static $tPassword = "password";
    public static $tPasswordHash = "password_hash";
    public static $tName = "name";
    public static $tSurname = "surname";
    public static $tBirthday = "birthday";
    public static $tGender = "gender";
    public static $tNumberOfLogIns = "number_of_worng_log_in";
    public static $tLogInType = "log_in_type";
    public static $tActivationHash = "activation_hash";
    public static $tActivationHashCreatedAt = "activation_hash_created_at";
    public static $tActivationHashActivatedAt = "activation_hash_activated_at";
    public static $tTypeId = "type_id";

    public function __construct($id = NULL, array $data = array())
    {
        self::$tId = "user_id";
        self::$t = "users";
        parent::__construct($id, $data);
    }

    function getColumns()
    {
        return [
            self::$tUserName,
            self::$tEmail,
            self::$tPassword,
            self::$tPasswordHash,
            self::$tName,
            self::$tSurname,
            self::$tBirthday,
            self::$tGender,
            self::$tNumberOfLogIns,
            self::$tLogInType,
            self::$tActivationHash,
            self::$tActivationHashCreatedAt,
            self::$tActivationHashActivatedAt,
            self::$tTypeId
        ];
    }

    function tags($columns=NULL)
    {
        return [
            self::$tUserName=>"username",
            self::$tEmail=>"email",
            self::$tPassword=>"password",
            self::$tPasswordHash=>"passwordhashed",
            self::$tName=>"name",
            self::$tSurname=>"lastname",
            self::$tBirthday=>"birthday",
            self::$tGender=>"gender",
            self::$tNumberOfLogIns=>"wornglogin",
            self::$tLogInType=>"typelogin",
            self::$tActivationHash=>"activation",
            self::$tActivationHashCreatedAt=>"actcreatedat",
            self::$tActivationHashActivatedAt=>"actactivated",
            self::$tTypeId=>"typeuser"
        ];
    }

    function isAdmin(){
        return (int)$this->get(self::$tTypeId) === self::ADMIN;
    }
    function isModerator(){
        return (int)$this->get(self::$tTypeId) === self::MODERATOR;
    }
    function isRegular(){
        return (int)$this->get(self::$tTypeId) === self::REGULAR;
    }


}