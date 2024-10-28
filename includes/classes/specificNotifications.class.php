<?php
class specificNotifications{
    use queryTraits;
    public $noteID ;
    public $launcherID ;
    public $recipientID ;
    public $type ;
    public $importance ;
    public $referenceID ;
    public $isRead ;
    public $createdDate ;
    const TABLE_NAME = "specific_notifications";
    function __construct(&$isSuccess = 0, $noteID=null, $launcherID=null, $recipientID =null,
                        $type=null, $importance=null, $referenceID=null, $isRead=null)
                {
                    $this->noteID           = (isset($noteID) && is_numeric(trim($noteID))) ? intval(trim($noteID)) : null ;
                    $this->launcherID       = (isset($launcherID) && is_numeric(trim($launcherID))) ? intval(trim($launcherID)) : null ;
                    $this->recipientID       = (isset($recipientID) && is_numeric(trim($recipientID))) ? intval(trim($recipientID)) : null ;
                    $this->type             = (isset($type)) ? strip_tags(trim($type)) : null ;
                    $this->importance       = (isset($importance)&& is_numeric(trim($importance))) ? intval(trim($importance)) : null ;
                    $this->referenceID      = (isset($referenceID) && is_numeric(trim($referenceID))) ? intval(trim($referenceID)) : null ;
                    $this->isRead           = (isset($isRead) && in_array($isRead, [0,1])) ? $isRead : null ;
                    
                    if((isset($noteID) && !isset($this->noteID)) || (isset($launcherID ) && !isset($this->launcherID ))
                        || (isset($recipientID ) && !isset($this->recipientID )) || (isset($type) && !isset($this->type))
                        || (isset($referenceID) && !isset($this->referenceID))){
                    $isSuccess = 0;
                }else $isSuccess = true;
            }
    /*******************************************/
    /** */
    function getNotificationByID(){
        return $this->getObjectPropsByID(self::TABLE_NAME, 'noteID');
    }
    /*******************************************/
    /** */
    function insertNotification(&$err, ...$propsToInsert){
        $condition = $this->insertObject($err, self::TABLE_NAME, ...$propsToInsert);
        return $condition;
     }
     /*******************************************/
     /*  */
    function updateNotification(&$err,$where, ...$propsToUpdate){
        $condition = $this->updateObject($err, self::TABLE_NAME, $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
    /* */
     function deleteNotification(&$err, ...$where){
        $condition = $this->deleteObject($err, self::TABLE_NAME, ...$where);
        return $condition;
     }
     /*********************************/
     function isNotificationExist(&$probMatch, $seperator="AND", ...$where){
        if($seperator == "OR" || $seperator == "||"){
            foreach($where as $prob){
                $cond = $this->checkItem($err, self::TABLE_NAME, $seperator, $prob);
                if($cond != 0){
                    $probMatch = $prob;
                    return $cond;
                }
            }
        }else{
            $probMatch = "Unique";
            return $this->checkItem($err,  self::TABLE_NAME, $seperator, ...$where);
        }
        return false;
    }
    /****************************/
    /** */
    function getNotificationPropWhere($get, $comparision = array(), $where=array(), $seperator = "AND", $orderDir ='ASC'){
        return $this->getObjectPropWhere(self::TABLE_NAME, $get, $comparision, $where, $seperator, $orderDir);
    }
    /*************************/
}