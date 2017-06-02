<?php

namespace bagy94\model;
use bagy94\utility\db\Db;

require_once "Model.php";

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 3.4.2017.
 * Time: 22:50
 */
class User extends Model
{
    const QUERY_INIT_BY_ID = "SELECT * FROM  `users` WHERE  `user_id` = :varUserId";
    const QUERY_INIT_BY_USER_NAME = "SELECT * FROM `users` WHERE `user_name`= :varUserName";
    const Query_INIT_BY_EMAIL = "SELECT * FROM `users` WHERE `email`= :varEmail";

    public static $QUERRY_INSERT = "INSERT INTO `users` VALUES 
                                    (DEFAULT,
                                    :varUserName,
                                    :varEmail,
                                    :varPassword,
                                    :varPasswordHash,
                                    :varName,
                                    :varSurname,
                                    :varBirthday,
                                    :varGender,
                                    0,
                                    :varLogInType,
                                    :varActivationHash,
                                    :varActHashCreatedAt,
                                    '',
                                    :varTypeId,
                                    :varCreatedAt,
                                    0)";


    public static $t = "users";
    public static $tId = "user_id";
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

    private $user_id,$user_name,$email,$password,$password_hash,$name,$surname,$birthday,
            $gender,$number_of_wrong_log_in,$log_in_type,$activation_hash,$activation_hash_created_at,
            $activation_hash_activated_at,$type_id;




    public function __construct($id = NULL, array $data = array())
    {
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



    function save($columns = array())
    {
        // TODO: Implement save() method.
    }

    function init($constraint = NULL)
    {
        // TODO: Implement init() method.
    }

    public function initByUserName($userName){
        $db = new Db(self::QUERY_INIT_BY_USER_NAME,[":varUserName"=>$userName],Db::getInstance());
        if ($db->runQuery() && $db->getStm()->rowCount()){
            $user = $db->getStm()->fetchObject(__CLASS__);
        }else{
            $user = NULL;
        }
        $db->disconnect();
        return user;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return User
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     * @return User
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * @param mixed $password_hash
     * @return User
     */
    public function setPasswordHash($password_hash)
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumberOfWrongLogIn()
    {
        return $this->number_of_wrong_log_in;
    }

    /**
     * @param mixed $number_of_wrong_log_in
     * @return User
     */
    public function setNumberOfWrongLogIn($number_of_wrong_log_in)
    {
        $this->number_of_wrong_log_in = $number_of_wrong_log_in;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogInType()
    {
        return $this->log_in_type;
    }

    /**
     * @param mixed $log_in_type
     * @return User
     */
    public function setLogInType($log_in_type)
    {
        $this->log_in_type = $log_in_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationHash()
    {
        return $this->activation_hash;
    }

    /**
     * @param mixed $activation_hash
     * @return User
     */
    public function setActivationHash($activation_hash)
    {
        $this->activation_hash = $activation_hash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationHashCreatedAt()
    {
        return $this->activation_hash_created_at;
    }

    /**
     * @param mixed $activation_hash_created_at
     * @return User
     */
    public function setActivationHashCreatedAt($activation_hash_created_at)
    {
        $this->activation_hash_created_at = $activation_hash_created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationHashActivatedAt()
    {
        return $this->activation_hash_activated_at;
    }

    /**
     * @param mixed $activation_hash_activated_at
     * @return User
     */
    public function setActivationHashActivatedAt($activation_hash_activated_at)
    {
        $this->activation_hash_activated_at = $activation_hash_activated_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * @param mixed $type_id
     * @return User
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
        return $this;
    }




}