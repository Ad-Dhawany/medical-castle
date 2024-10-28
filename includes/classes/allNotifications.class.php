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
        }
        if($typeArr[0] == 'costumer'){
            $link = BASIC_ADDRES. "/eshraf/costumer/?do=info&ID=". $this->referenceID;
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
        $notifMsg = /* "<i>". langTH("ADMINISTRATION"). "</i> ".  */langNotifyCost($this->type). " ". $this->referenceID ;
        return $notifMsg;
    }
    /** */
    function getcostumerNotificationMsg($typeArr){
        $notifMsg = /* "<i>". langTH("ADMINISTRATION"). "</i> ".  */langNotifyCost($this->type)/* . " <span style='color:#381effb0;'>(". $this->referenceID.")</span>" */ ;
        return $notifMsg;
    }
    /*********/
}