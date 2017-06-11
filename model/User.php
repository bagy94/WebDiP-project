<?php

namespace bagy94\model;
use bagy94\utility\Application;
use bagy94\utility\RegexUtility;
use bagy94\utility\Router;
use bagy94\model\Configuration;
require_once "Model.php";

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 3.4.2017.
 * Time: 22:50
 */
class User extends Model
{
    public static $QUERY_INIT_BY_ID = "SELECT * FROM  `users` WHERE  `user_id` = ?";
    const QUERY_INIT_BY_USER_NAME = "SELECT * FROM `users` WHERE `user_name`= ?";
    const QUERY_INIT_BY_EMAIL = "SELECT * FROM `users` WHERE `email`= ?";
    const QUERY_INIT_BY_ACTIVATION = "SELECT * FROM `users` WHERE `activation_hash` = ?";
    const QUERY_INIT_BY_LOG_IN_CODE = "SELECT * FROM `users` WHERE `log_in_code` = ?";



    /***
     * Query for insert new user. All data can be inserted except log_in_code and his end time.
     * @var string
     */
    public static $QUERY_INSERT ="INSERT INTO `users` VALUES 
                                  (DEFAULT,:var_user_name,:var_email,:var_password,:var_password_hash,:var_name,:var_surname,:var_birthday,:var_gender,:var_number_of_wrong_log_in,:var_log_in_type,NULL,NULL,:var_activation_hash,:var_activation_hash_created_at,:var_activation_hash_activated_at,:var_type_id,:var_points,:var_created_at,:var_deleted)";

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
    public static $tPoints = "points";
    public static $tLogInCode = "log_in_code";
    public static $tLogInCodeEndsOn = "log_in_code_ends_on";

    protected $user_id,$user_name,$email,$password,$password_hash,$name,$surname,$birthday,
            $gender,$number_of_wrong_log_in="0",$log_in_type,$activation_hash,$activation_hash_created_at,
            $points=0,$activation_hash_activated_at=NULL,$type_id=3,$log_in_code,$log_in_code_ends_on;
    private $errors=[];



    /**
     * User constructor.
     * @inheritdoc
     */
    public function __construct($id = NULL,  $data =NULL)
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

    /**
     * Create instance of user by activation code or return null if code doesn't exist
     * @param $act
     * @return User|null
     */
    public static function checkActivationCode($act)
    {
        return self::initBy(self::QUERY_INIT_BY_ACTIVATION,[$act]);
    }

    /**
     * Function which does registration.
     * Hashes password.
     * Create hash for activation.
     * Insert user in db.
     * @return mixed
     */
    public function registration(){
        if(!isset($this->type_id)){
            $this->type_id = "3";
        }
        if(!isset($this->password_hash)){
            $this->password_hash = hash("sha256",$this->getPassword());
        }
        if(!isset($this->activation_hash)){
            $this->createNewActivation(FALSE);
        }
        if(!isset($this->created_at)){
            $this->created_at = Application::appTimeStamp();
        }
        $userData = $this->toParamsExclude([self::$tId,self::$tLogInCode,self::$tLogInCodeEndsOn]);
        //print_r($userData);
        if($this->connect(self::$QUERY_INSERT,$userData)->prepare()->runQuery()){
            $this->setUserId($this->connection->lastId());
        }
        return $this->getUserId();
    }

    /**
     * Test function for converting user to params
     * @return array
     */
    public function testParams(){
        return $this->toParamsExclude([self::$tId]);
    }

    /**
     * Return if registration has errors.
     * @return bool
     */
    public function hasErrors(){
        return count($this->errors);
    }
    /**
     * Server side data check upon user registration.
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
        if(empty($this->name))
            array_push($this->errors,"Ime nije uneseno");
        else if($this->name[0] !== strtoupper($this->name[0]))
            array_push($this->errors,"Ime mora započinjati velikim slovom");
        //Check Surname
        if(empty($this->surname))
            array_push($this->errors,"Prezime nije uneseno");
        else if($this->surname[0] !== strtoupper($this->surname[0]))
            array_push($this->errors,"Preyimeme mora započinjati velikim slovom");


        //Check Email
        if(empty($this->email))
            array_push($this->errors,"Email nije unesen");
        else if(!RegexUtility::checkEmail($this->email))
            array_push($this->errors,"Format email ne valja");
        else if (self::initByEmail($this->getEmail()) !== NULL)
            array_push($this->errors,"Email postoji");


        //Check User name
        if(empty($this->user_name))
            array_push($this->errors,"Korisničko ime nije uneseno");
        elseif (strlen($this->user_name)<6)
            array_push($this->errors,"Korisničko ime mora biti više od 6 znakova");
        elseif (!RegexUtility::checkUserName($this->user_name))
            array_push($this->errors,"Korisničko ime mora sadržavati velika slova i brojeve");
        else if(self::initByUserName($this->user_name) !== NULL)
            array_push($this->errors,"Korisničko ime postoji");

        //Check Password
        if(empty($this->password))
            array_push($this->errors,"Loznika nije unesena");
        elseif(!RegexUtility::checkPassword($this->password))
            array_push($this->errors,"Lozinka mora sadržavati više od 6 znakova.\\n Mora imati 2 velika slova, 2 broja i 2 specijalna znaka.");

        //Check gender
        if (empty($this->gender))
            array_push($this->errors,"Spol nije unesen");


        //Check birthday
        if (empty($this->birthday))
            array_push($this->errors,"Datum rođenja nije unesen");
        elseif (!RegexUtility::isBirthdayFormat($this->birthday))
            array_push($this->errors,"Datum rođenja nije u zadanom formatu");
        else
            $this->setBirthday(Application::dateFormat($this->getBirthday()));
        return !count($this->errors);
    }

    /**
     * Send html mail to user.
     * @param $subject
     * @param $content
     * @return bool
     */
    function sendMail($subject, $content){
        $header = "From: wellness@no-reply.foi.hr \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .="Content-type: text/html; charset=UTF-8";
        return mail($this->getEmail(), $subject, $content,$header);
    }

    /**
     * Return end of activation link as virtual time timestamp
     * @param $duration
     * @return false|int
     */
    public function activationLinkEndsOn($duration)
    {
        return strtotime("$duration hours",strtotime($this->getActivationHashCreatedAt()));
    }

    /**
     * Update log in code if user have 2 steps log in
     * @return bool
     */
    function saveLogInCode(){
        return $this->update([self::$tLogInCode,self::$tLogInCodeEndsOn]);
    }

    /**
     * If password isn't correct update wrong attempt.
     * @return bool
     */
    public function wrongLogIn()
    {
        $foo = intval($this->getNumberOfWrongLogIn());
        $this->setNumberOfWrongLogIn(++$foo);
        return $this->update([self::$tNumberOfLogIns]);
    }

    /**
     * When user log in successfully it reset number of wrong log in and update it
     * @return bool
     */
    public function resetLogInErrors()
    {
        $this->setNumberOfWrongLogIn(0);
        return $this->update([self::$tNumberOfLogIns]);
    }

    /**
     * Function which generates new password and if param is true it makes update in db
     * @param bool $updateInDb
     * @return bool|mixed
     */
    public function renewPassword($updateInDb = TRUE)
    {
        $this->setPasswordHash(hash("sha256",$this->getUserName()));
        $this->setPassword(substr($this->getPasswordHash(),1,10));
        return $updateInDb?$this->update([self::$tPassword,self::$tPasswordHash]):$this->getPasswordHash();
    }

    /**
     * Function creates new hash for activation and save timestamp for activation created at.
     * If param is true it will update values in database.
     * @param bool $update
     * @return bool|int
     */
    public function createNewActivation($update=TRUE)
    {
        $this->setActivationHash(hash("md5",$this->getUserName().time()));
        $this->setActivationHashCreatedAt(Application::appTimeStamp());

        if($update){
            return $this->update([self::$tActivationHash,self::$tActivationHashCreatedAt]);
        }
        return 1;
    }

    /**
     * Activate user account. Update activation_hash_activated_at.
     * @return bool
     */
    public function activate()
    {
        $this->setActivationHashActivatedAt(Application::appTimeStamp());
        return $this->update([self::$tActivationHashActivatedAt]);
    }

    /**
     * Check if password is same as one in database.
     * @param $password
     * @return bool
     */
    public function isPasswordCorrect($password)
    {
        return !strcmp($this->getPassword(),$password);
    }

    /**
     * Check if user is blocked.
     * User is blocked if he tries to login with wrong password too many time(depends on sys_conf).
     * @return bool
     */
    public function isUserAccountBlocked()
    {
        return $this->getNumberOfWrongLogIn() >= Configuration::Instance()->getMaxLogin();
    }

    /**
     * Check if user has activate account.
     * User can activate account by clicking on link which he gets on mail upon registration.
     * @return bool
     */
    public function isActivated()
    {
        return isset($this->activation_hash_activated_at) && $this->activation_hash_activated_at !== "";
    }

    /***
     * Getters and setters.
     */

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
    /**
     * @param $points
     * @return User
     */
    public function setPoints($points){
        $this->points = $points;
        return $this;
    }
    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }
    /**
     * @return mixed
     */
    public function getLogInCode()
    {
        return $this->log_in_code;
    }
    /**
     * @param mixed $log_in_code
     * @return User
     */
    public function setLogInCode($log_in_code)
    {
        $this->log_in_code = $log_in_code;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getLogInCodeEndsOn()
    {
        return $this->log_in_code_ends_on;
    }
    /**
     * @param mixed $log_in_code_ends_on
     * @return User
     */
    public function setLogInCodeEndsOn($log_in_code_ends_on)
    {
        $this->log_in_code_ends_on = $log_in_code_ends_on;
        return $this;
    }


}