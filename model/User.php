<?php

namespace bagy94\model;
use bagy94\utility\Application;
use bagy94\utility\db\Db;
use bagy94\utility\RegexUtility;

require_once "Model.php";

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 3.4.2017.
 * Time: 22:50
 */
class User extends Model
{
    const QUERY_INIT_BY_ID = "SELECT * FROM  `users` WHERE  `user_id` = ?";
    const QUERY_INIT_BY_USER_NAME = "SELECT * FROM `users` WHERE `user_name`= ?";
    const QUERY_INIT_BY_EMAIL = "SELECT * FROM `users` WHERE `email`= ?";

    public static $QUERY_INSERT ="INSERT INTO `users` VALUES 
(DEFAULT,:var_user_name,:var_email,:var_password,:var_password_hash,:var_name,:var_surname,:var_birthday,:var_gender,:var_number_of_wrong_log_in,:var_log_in_type,:var_activation_hash,:var_activation_hash_created_at,:var_activation_hash_activated_at,:var_type_id,:var_created_at,:var_deleted)";

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
    public static $tNumberOfLogIns = "number_of_wrong_log_in";
    public static $tLogInType = "log_in_type";
    public static $tActivationHash = "activation_hash";
    public static $tActivationHashCreatedAt = "activation_hash_created_at";
    public static $tActivationHashActivatedAt = "activation_hash_activated_at";
    public static $tTypeId = "type_id";

    protected $user_id,$user_name,$email,$password,$password_hash,$name,$surname,$birthday,
            $gender,$number_of_wrong_log_in="0",$log_in_type,$activation_hash,$activation_hash_created_at,
            $activation_hash_activated_at=NULL,$type_id=3;


    private $errors=[];


    /**
     * User constructor.
     * @param int|null $id
     * @param array $data
     */
    public function __construct($id = NULL, array $data = array())
    {
        parent::__construct($id,$data);
    }

    /**
     * Create instance of user by user-name or return null if user-name doesn't exist
     * @param $userName
     * @return User|null
     */
    public static function initByUserName($userName){
        return self::initBy(self::QUERY_INIT_BY_USER_NAME,[$userName]);
    }

    /**
     * Create instance of user by email or return null if email doesn't exist
     * @param $email
     * @return User|null
     */
    public static function initByEmail($email){
        return self::initBy(self::QUERY_INIT_BY_EMAIL,[$email]);
    }


    public function registration(){
        if(!isset($this->type_id)){
            $this->type_id = "3";
        }
        if(!isset($this->password_hash)){
            $this->password_hash = hash("sha256",$this->getPassword());
        }
        if(!isset($this->activation_hash)){
            $this->setActivationHash(hash("md5",$this->getPassword()));
            $this->setActivationHashCreatedAt(Application::appTimeStamp());
        }
        if(!isset($this->created_at)){
            $this->created_at = Application::appTimeStamp();
        }
        $userData = $this->toParams([self::$tId]);
        //print_r($userData);
        if($this->connect(self::$QUERY_INSERT,$userData)->prepare()->runQuery()){
            $this->setUserId($this->connection->lastId());
        }
        return $this->getUserId();
    }

    /**
     * @return bool
     */
    public function hasErrors(){
        return count($this->errors);
    }
    /**
     * Check is correct:
     * Name
     * Surname
     * Username
     * Password
     * Email
     * Gender
     * Birthday
     * @return bool
     */
    public function isRegistrationCorrect(){
        //Check Name
        if(!isset($this->name))
            array_push($this->errors,"Ime nije uneseno");
        else if($this->name[0] !== strtoupper($this->name[0]))
            array_push($this->errors,"Ime mora započinjati velikim slovom");
        //Check Surname
        if(!isset($this->surname))
            array_push($this->errors,"Prezime nije uneseno");
        else if($this->surname[0] !== strtoupper($this->surname[0]))
            array_push($this->errors,"Preyimeme mora započinjati velikim slovom");
        //Check Email
        if(!isset($this->email))
            array_push($this->errors,"Email nije unesen");
        else if(!RegexUtility::checkEmail($this->email))
            array_push($this->errors,"Format email ne valja");
        //Check Username
        if(!isset($this->user_name))
            array_push($this->errors,"Korisničko ime nije uneseno");
        elseif (strlen($this->user_name)<6)
            array_push($this->errors,"Korisničko ime mora biti više od 6 znakova");
        elseif (!RegexUtility::checkUserName($this->user_name))
            array_push($this->errors,"Korisničko ime mora sadržavati velika slova i brojeve");
        //Check Password
        if(!isset($this->password))
            array_push($this->errors,"Loznika nije unesena");
        elseif(!RegexUtility::checkPassword($this->password))
            array_push($this->errors,"Lozinka mora sadržavati više od 6 znakova.\\n Mora imati 2 velika slova, 2 broja i 2 specijalna znaka.");

        if (!isset($this->gender))
            array_push($this->errors,"Spol nije unesen");

        if (!isset($this->birthday))
            array_push($this->errors,"Datum rođenja nije unesen");
        elseif (!RegexUtility::isBirthdayFormat($this->birthday))
            array_push($this->errors,"Datum rođenja nije u zadanom formatu");
        else
            $this->setBirthday(Application::dateFormat($this->getBirthday()));
        return !count($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
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