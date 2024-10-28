<?php
class broadNotificationsReaders{
    use queryTraits;
    public $ID ;
    public $noteID ;
    public $readerID ;
    public $readDate ;
    const TABLE_NAME = "broad_notifications_readers";
    function __construct(&$isSuccess = 0, $ID=null, $noteID=null, $readerID=null, $readDate=null)
                {
                    $this->ID           = (isset($ID) && is_numeric(trim($ID))) ? intval(trim($ID)) : null ;
                    $this->noteID       = (isset($noteID) && is_numeric(trim($noteID))) ? intval(trim($noteID)) : null ;
                    $this->readerID     = (isset($readerID) && is_numeric(trim($readerID))) ? intval(trim($readerID)) : null ;
                    $this->readDate     = (isset($readDate)) ?  strip_tags($readDate) : null ;
                    
                    if((isset($noteID) && !isset($this->noteID)) || (isset($ID ) && !isset($this->ID ))
                        || (isset($readerID) && !isset($this->readerID)) || (isset($readDate) && !isset($this->readDate))){
                    $isSuccess = 0;
                }else $isSuccess = true;
            }
    /*******************************************/
    /** */
    function getNotificationReaderByID(){
        return $this->getObjectPropsByID(self::TABLE_NAME, 'noteID');
    }
    /*******************************************/
    /** */
    function insertNotificationReader(&$err, ...$propsToInsert){
        $condition = $this->insertObject($err, self::TABLE_NAME, ...$propsToInsert);
        return $condition;
     }
     /*******************************************/
     /*  */
    function updateNotificationReader(&$err,$where, ...$propsToUpdate){
        $condition = $this->updateObject($err, self::TABLE_NAME, $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
    /* */
     function deleteNotificationReader(&$err, ...$where){
        $condition = $this->deleteObject($err, self::TABLE_NAME, ...$where);
        return $condition;
     }
     /*********************************/
     function isNotificationReaderExist(&$probMatch, $seperator="AND", ...$where){
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
}