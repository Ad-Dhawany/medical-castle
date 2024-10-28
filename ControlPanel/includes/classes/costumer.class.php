<?php
class costumer extends subscriber{
    protected $groupID = costGroupID;
    public $pharmacy;

    function __construct(&$errMode = 0, $userID=null, $username=null, $email=null, $phone=null,$fullname=null, $groupID = null,$trustStatus=null, $regDate=null, $regStatus=null,$password=null, $pharmacy=null){
        
        $this->userID      = (isset($userID) && is_numeric(strip_tags($userID))) ? intval(strip_tags($userID)) : null ;
        $this->username    = (isset($username)) ? strip_tags($username) : null ;
        $this->email       = (isset($email) && filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) ? filter_var($email, FILTER_SANITIZE_EMAIL) : null ;
        //$this->phone       = (isset($phone) && filter_var($phone ,FILTER_SANITIZE_NUMBER_INT) == $phone) ? filter_var($phone ,FILTER_SANITIZE_NUMBER_INT) : null ;
        $this->phone       = (isset($phone)) ? $this->preparePhone($phone, "number") : null ;
        $this->fullname    = (isset($fullname)) ? strip_tags($fullname) : null ;
        $this->groupID     = costGroupID;
        $this->trustStatus = (isset($trustStatus) && is_numeric(strip_tags($trustStatus))) ? intval(strip_tags($trustStatus)) : null ;
        $this->regDate     = (isset($regDate)) ? $regDate : null ;
        $this->regStatus   = (isset($regStatus) && is_numeric(strip_tags($regStatus))) ? intval(strip_tags($regStatus)) : null ;
        $this->password    = (isset($password)) ? $password : null ;
        $this->pharmacy    = (isset($pharmacy)) ? strip_tags($pharmacy) : null ;
            if((isset($userID) && !isset($this->userID)) || (isset($username) && !isset($this->username))
            || (isset($email) && !isset($this->email)) || (isset($phone) && (!isset($this->phone) || $this->phone === false))
            || (isset($fullname) && !isset($this->fullname)) || (isset($pharmacy) && !isset($this->pharmacy))
            || (isset($trustStatus) && !isset($this->trustStatus)) || (isset($regStatus) && !isset($this->regStatus))){
                $errMode = 0;
            }else $errMode = true;

    }


    /** OVERRIDE */
    function getMemberByID(){
        global $conn;
        if(isset($this->userID) && is_numeric($this->userID)){
            $userID=$this->userID;
            $stmt = $conn->prepare("SELECT * FROM users WHERE userID=? AND groupID = ". costGroupID);
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
                $this->pharmacy       = $result['pharmacy'];
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
}