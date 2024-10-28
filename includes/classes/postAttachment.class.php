<?php
class postAttachment{
    use queryTraits;
    public $ID ;
    public $postID ;
    public $filePath ;
    public $addDate ;
    const TABLE_NAME = "post_attachment";
    function __construct(&$isSuccess = 0, $ID=null, $postID=null, $filePath=null, $addDate=null)
                {
                    $this->ID           = (isset($ID) && is_numeric(trim($ID))) ? intval(trim($ID)) : null ;
                    $this->postID       = (isset($postID) && is_numeric(trim($postID))) ? intval(trim($postID)) : null ;
                    $this->filePath     = (isset($filePath)) ? strip_tags(trim($filePath)) : null ;
                    $this->addDate      = (isset($addDate)) ? strip_tags($addDate) : null ;
                    
                    if((isset($ID) && !isset($this->ID)) || (isset($postID ) && !isset($this->postID ))
                        || (isset($filePath) && !isset($this->filePath))){
                    $isSuccess = 0;
                }else $isSuccess = true;
            }
    /*******************************************/
    /** */
    function getPostAttachmentByID(){
        return $this->getObjectPropsByID(self::TABLE_NAME, 'ID');
    }
    /*******************************************/
    /** */
    function insertPostAttachment(&$err, ...$propsToInsert){
        $condition = $this->insertObject($err, self::TABLE_NAME, ...$propsToInsert);
        return $condition;
     }
     /*******************************************/
     /*  */
    function updatePostAttachment(&$err,$where, ...$propsToUpdate){
        $condition = $this->updateObject($err, self::TABLE_NAME, $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
    /* */
     function deletePostAttachment(&$err, ...$where){
        $condition = $this->deleteObject($err, self::TABLE_NAME, ...$where);
        return $condition;
     }
     /*********************************/
     function isAttachmentExist(&$probMatch, $seperator="AND", ...$where){
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
    function getPostAttachmentPropWhere($get, $comparision = array(), $where=array(), $seperator = "AND", $orderDir ='ASC'){
        return $this->getObjectPropWhere(self::TABLE_NAME, $get, $comparision, $where, $seperator, $orderDir);
    }
    /*************************/
}