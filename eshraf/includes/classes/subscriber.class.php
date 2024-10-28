<?php /* require_once('../../confiq.php'); */
class subscriber{
    use queryTraits;
    public $userID;
    public $username;
    protected $password;
    public $email;
    public $phone;
    public $fullname;
    public $pharmacy;
    protected $groupID;
    public $trustStatus;
    public $regDate;
    public $regStatus;
    /***/
    public $lastActiveBy;
    public $lastActiveDate;
    public $lastTrushBy;
    public $lastTrushDate;
    public $lastPendBy;
    public $lastPendDate;
    public $lastEditBy;
    public $lastEditDate;

    function __construct(&$errMode = 0, $userID=null, $username=null, $email=null, $phone=null,$fullname=null,$groupID=null, $trustStatus=null, $regDate=null, $regStatus=null,$password=null){
        
            $this->userID      = (isset($userID) && is_numeric(strip_tags($userID))) ? intval(strip_tags($userID)) : null ;
            $this->username    = (isset($username)) ? strip_tags($username) : null ;
            $this->email       = (isset($email) && filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) ? filter_var($email, FILTER_SANITIZE_EMAIL) : null ;
            //$this->phone       = (isset($phone) && filter_var($phone ,FILTER_SANITIZE_NUMBER_INT) == $phone) ? filter_var($phone ,FILTER_SANITIZE_NUMBER_INT) : null ;
            $this->phone       = (isset($phone)) ? $this->preparePhone($phone, "number") : null ;
            $this->fullname    = (isset($fullname)) ? strip_tags($fullname) : null ;
            $this->groupID     = (isset($groupID) && is_numeric(strip_tags($groupID))) ? intval(strip_tags($groupID)) : null ;
            $this->trustStatus = (isset($trustStatus) && is_numeric(strip_tags($trustStatus))) ? intval(strip_tags($trustStatus)) : null ;
            $this->regDate     = (isset($regDate)) ? $regDate : null ;
            $this->regStatus   = (isset($regStatus) && is_numeric(strip_tags($regStatus))) ? intval(strip_tags($regStatus)) : null ;
            $this->password    = (isset($password)) ? $password : null ;

            if((isset($userID) && !isset($this->userID)) || (isset($username) && !isset($this->username))
            || (isset($email) && !isset($this->email)) || (isset($phone) && (!isset($this->phone) || $this->phone === false))
            || (isset($fullname) && !isset($this->fullname)) || (isset($groupID) && !isset($this->groupID))
            || (isset($trustStatus) && !isset($this->trustStatus)) || (isset($regStatus) && !isset($this->regStatus))){
                $errMode = 0;
            }else $errMode = true;

        }
        /*******************/
        /** */
    function getMemberByID(){
        global $conn;
        if(isset($this->userID) && is_numeric($this->userID)){
            $userID=$this->userID;
            $stmt = $conn->prepare("SELECT * FROM users WHERE userID=?");
            $stmt->execute(array($userID));
            $result = $stmt->fetch();
            $count  = $stmt->rowCount(); // get numper of rows in $stmt
            if ($count == 1){
               // $this->userID           = $result['userID'];
                $this->username       = $result['username'];
                $this->email          = $result['email'];
                $this->phone          = $result['phone'];
                $this->fullname       = $result['fullname'];
                $this->groupID        = $result['groupID'];
                /* $this->pharmacy       = $result['pharmacy']; */
                $this->regDate        = $result['regDate'];
                $this->regStatus      = $result['regStatus'];
                $this->lastActiveBy   = $result['lastActiveBy'];
                $this->lastActiveDate = $result['lastActiveDate'];
                $this->lastTrushBy    = $result['lastTrushBy'];
                $this->lastTrushDate  = $result['lastTrushDate'];
                $this->lastPendBy     = $result['lastPendBy'];
                $this->lastPendDate   = $result['lastPendDate'];
                $this->lastEditBy     = $result['lastEditBy'];
                $this->lastEditDate   = $result['lastEditDate'];
                return true;
            }else{return false;}
        }else{return false;}
    }
    /***************************/
    /** */
    function membLoign($username= null, $password = null, &$errorMsg , $case = null , $just=null){
        global $conn;
        $username = (isset($username) && !empty($username)) ? strip_tags($username) : $this->username ;
        $password = (isset($password) && !empty($password)) ? $password : $this->password ;
        $where = "username";
        $inPhoneCase = str_replace(" ","-",$username);
        if(isset($case) && !empty($case)){
            $where = $case;
            $username = $this->$case ?? $username;
        }elseif(filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $where = "email";
        }elseif(filter_var($inPhoneCase ,FILTER_SANITIZE_NUMBER_INT) == $inPhoneCase){
            $where = "phone";
            if($this->preparePhone($username)){
                $username = $this->phone;
            }else{
                $errorMsg = "Invalid Phone Number";
                return false;
            }
            
        }elseif(!fnc::isArabicStr($username, false)){
            if(!preg_match("/^[a-zA-Z]+([a-zA-Z'.0-9]){3,24}$/",$username)){
                return false;
            }
        }

        if($where == "username"){$username = strtolower($username); }
        $stmt = $conn->prepare("SELECT * FROM users WHERE $where = ?");//$stmt: متغير جملة الإستعلام prepare: تحضير جكلة الإستعلام قبل ارسالها لتجنب الأخطاء
        $stmt->execute(array($username));
        $result = $stmt->fetch();
        $count  = $stmt->rowCount(); // get numper of rows in $stmt
         if ($count == 1){
            if($result['groupID'] > 0){
                if(password_verify($password, $result['password'])){
                    if(!isset($just) || empty($just)){
                        $this->userID       = $result['userID'] ;
                        $this->username     = $result['username'];
                        $this->email        = $result['email'];
                        $this->phone        = $result['phone'];
                        $this->fullname     = $result['fullname'];
                        $this->groupID      = $result['groupID'];
                        $this->regDate      = $result['regDate'];
                        $this->regStatus    = $result['regStatus'];
                        $this->trustStatus  = $result['trustStatus'];
                    }
                    return true;
                }else {
                    $errorMsg = "Wrong username or password";
                     return false;
                    }
            }else {
                $errorMsg = "You have no permission to accec here";
                return false;
            }
        }else {
            $errorMsg = "Wrong username or password";
            return false;
        }
    }
    /*******************************/
    /*  */
    function preparePhone($num = null , $inSuccRet = true){
        if(!isset($num) || empty($num)){
            if(isset($this->phone) && !empty($this->phone)){
                $num = $this->phone;
            }else { return false; }
        }
            if(!preg_match("/^[+0]{1}+[- 0-9]{9,15}$/",$num)){ return false;}
            $num = str_replace(["-", " "],"",$num);
            if(strpos($num, "00") === 0 && strlen($num) == 14){
                $this->phone = $num;
               /* return true; */
            }elseif(strpos($num, "+") === 0 && strlen($num) == 13){
               $num = str_replace("+","00",$num,$c);
               if($c == 1){
                    $this->phone = $num;
                    /* return true; */
               }else{ return false; }
            }elseif(strpos($num, "09") === 0 && strlen($num) == 10){
               $num = substr_replace($num, "00218",0,1);
               $this->phone = $num;
               /* return true; */
            }else return false;
            $inSuccRet = ($inSuccRet !== true) ? $num : true;
            return $inSuccRet;
    }
    /*******************************/
    /*  */
    function updateMemb(&$err,$where, ...$propsToUpdate){
        $condition = $this->namesToLowerCase()->updateObject($err, "users", $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
     /** */
     function insertMemb(&$err, ...$propsToInsert){
        $condition = $this->namesToLowerCase()->insertObject($err, "users", ...$propsToInsert);
        return $condition;
     }
     /*********************************/
     /* */
     function deleteMemb(&$err, ...$where){
        $condition = $this->deleteObject($err, 'users', ...$where);
        return $condition;
     }
     /*********************************/
     /* */
     function getMemPropByProp($get, $where, $by){
        return $this->getObjectPropByProp($get, $where, $by, "users");
     }
     /*********************************/
     /** Getters & Setters Area*/
     /** */
    function setMembPassword($password = null, $hash = false){
        if(!isset($password) || empty($password)){
            $password = $this->password;
        }
        if($hash == true){
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
        $this->password = $password;
    }
    /***************************/
    /** */
    function getMembPassword(){
        return (isset($this->password)) ? $this->password : false;
    }
    /***************************/
    /** */
    function isMemberExist(&$probMatch, $seperator="AND", ...$where){
        foreach($where as $prob){
            $cond = $this->checkItem($err, 'users', $seperator, $prob);
            if($cond != 0){
                $probMatch = $prob;
                return $cond;
            }
        }
        return false;
    }
    /****************************/
    /* */
    function getGroupID(){
        return $this->groupID ;
    }
    /****************************/
    /** */
    function isThereUniqueProbInAnotherMemb(&$probMatch , $whereAND, ...$whereOR){
        foreach($whereOR as $prob){
            $cond = $this->checkUniqueValueInTable($err , 'users', $whereAND, $prob);
            if($cond != 0){
                $probMatch = $prob;
                return $cond;
            }
        }
        return false;
    }
    /****************************/
    /**لضمان تسجيل الأسماء في حالة الأحرف الصغيرة دائما في قاعدة البيانات */
    protected function namesToLowerCase(){
        if(isset($this->username)){
            $this->username = strtolower($this->username);
        }
        if(isset($this->fullname)){
            $this->fullname = strtolower($this->fullname);
        }
        if(isset($this->pharmacy)){
            $this->pharmacy = strtolower($this->pharmacy);
        }
        return $this;
    }
    /******************************/
}