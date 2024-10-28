<?php
class allNotifications{
    /* use queryTraits; */
    public $noteID ;
    public $launcherID ;
    public $recipientID ;
    public $type ;
    public $importance ;
    public $referenceID ;
    public $createdDate ;
    public $noteGroup ;
    public $launcherName ;
    public $launcherPharmacy ;
    public $recipientName ;
    public $isRead ;
    const TABLE_NAME = "all_notifications";
    function __construct(&$isSuccess = 0,  $noteID=null, $launcherID=null, $recipientID =null,
                        $type=null, $importance=null,$referenceID=null, $createdDate = null,
                        $noteGroup = null, $launcherName = null, $launcherPharmacy = null, $recipientName, $isRead = null)
                {
                    $this->noteID           = (isset($noteID) && is_numeric(trim($noteID))) ? intval(trim($noteID)) : null ;
                    $this->launcherID       = (isset($launcherID) && is_numeric(trim($launcherID))) ? intval(trim($launcherID)) : null ;
                    $this->recipientID      = (isset($recipientID) && is_numeric(trim($recipientID))) ? intval(trim($recipientID)) : null ;
                    $this->type             = (isset($type)) ? strip_tags(trim($type)) : null ;
                    $this->importance       = (isset($importance)&& is_numeric(trim($importance))) ? intval(trim($importance)) : null ;
                    $this->referenceID      = (isset($referenceID) && is_numeric(trim($referenceID))) ? intval(trim($referenceID)) : null ;
                    $this->isRead           = (isset($isRead) && in_array($isRead, [0,1])) ? $isRead : null ;
                    $this->isRead = $isRead ?? null;
                    $this->createdDate = $createdDate ?? null;
                    $this->noteGroup = $noteGroup ?? null;
                    $this->launcherName = $launcherName ?? null;
                    $this->launcherPharmacy = $launcherPharmacy ?? null;
                    $this->recipientName = $recipientName ?? null;
                    
                    if((isset($noteID) && !isset($this->noteID)) || (isset($launcherID ) && !isset($this->launcherID ))
                        || (isset($recipientID ) && !isset($this->recipientID )) || (isset($type) && !isset($this->type))
                        || (isset($referenceID) && !isset($this->referenceID))){
                    $isSuccess = 0;
                }else $isSuccess = true;
            }
    /*******************************************/
    /** */
    function getNotificitionLink(){
        $typeArr = explode("//", $this->type);
        if($typeArr[0] == 'order'){
            $link = BASIC_ADDRES. "/eshraf/orders/?do=info&ID=". $this->referenceID;
        }elseif($typeArr[0] == 'costumer'){
            if(strpos($this->type, "delete")){
                $link = BASIC_ADDRES. "/eshraf/logs/?log=delmem";
            }else{
                $link = BASIC_ADDRES. "/eshraf/costumers/?do=info&ID=". $this->referenceID;
            }
        }elseif($typeArr[0] == 'user'){
            if(strpos($this->type, "delete")){
                $link = BASIC_ADDRES. "/eshraf/logs/?log=delmem";
            }else{
                $link = BASIC_ADDRES. "/eshraf/users/?do=info&ID=". $this->referenceID;
            }
        }
        return $link;
    }
    /*******************************************/
    /** */
    function getNotificationMsg(){
        $typeArr = explode("//", $this->type);
        /* switch($typeArr[0]){

        } */
        $getter = "get". $typeArr[0]. 'NotificationMsg';
        return $this->$getter($typeArr);
    }
    /*************************/
    /** */
    /** */
    function getorderNotificationMsg($typeArr){
        $notifMsg = "<i>". $this->launcherName. "</i> ". langNotify($this->type). " ". $this->referenceID ;
        return $notifMsg;
    }
    /** */
    function getcostumerNotificationMsg($typeArr){
        if(strpos($this->type, "user") > -1 && strpos($this->type, "delete") < 1){
            $user = new costumer($e, $this->referenceID);
            $username = $user->getMemPropByProp("username", "userID", "userID") ;
            $notifMsg = "<i>". $this->launcherName. "</i> ". langNotify($this->type). " <b style='color:#000;'>$username</b>" ;
        }else{
            $notifMsg = "<i>". $this->launcherName. "</i> ". langNotify($this->type)/* . " <span style='color:#381effb0;'>(". $this->referenceID.")</span>" */ ;
        }
        return $notifMsg;
    }
    /*********/
    /** */
    function getuserNotificationMsg($typeArr){
        if($this->launcherID == $this->referenceID){
            // $notiType = str_replace("user", "costumer", $this->type);
            $notifMsg = "<i>". $this->launcherName. "</i> ". langNotify($this->type) ;
        }else{
            $user = new member($e, $this->referenceID);
            $username = $user->getMemPropByProp("username", "userID", "userID") ;
            /* $usernameHTML = " <b style='color:#000;'>$username</b>";
            if(strpos($this->type, "delete") < 1){
                $user = new member($e, $this->referenceID);
                $username = $user->getMemPropByProp("username", "userID", "userID") ;
                $usernameHTML = " <b style='color:#000;'>$username</b>";
            }else{
                $usernameHTML = "";
            } */
            $notifMsg = "<i>". $this->launcherName. "</i> ". langNotify($this->type)/* langNotify(str_replace("user", "user//user", $this->type)) */. $usernameHTML ;
        }
        return $notifMsg;
    }
    /*********/
}