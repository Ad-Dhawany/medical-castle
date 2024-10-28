<?php
class settingPost{
    use queryTraits;
    public $setPostID ;
    public $setPostName ; /**publisher id */
    public $setPostTybe ;
    public $setPostContent ;
    public $setPostContent_2 ;
    public $lastUpdatedBy ;
    public $createdDate ;
    public $updatedDate ;

    /* view peops */
   /*  public $username; //publisher name 
    public $fullname; //publisher full-name 
    public $filePath; //publisher full-name  */

    const TABLE_NAME = "setting_posts";
   /*  const VIEW_NAME = "get_posts";
    const VIEW_PROPS = ['username', 'fullname', 'filePath']; */
    
    function __construct(&$isSuccess = 0, $setPostID=null, $setPostName=null,
                        $setPostTybe =null, $setPostContent=null, $setPostContent_2=null,
                        $lastUpdatedBy=null, $createdDate=null, $updatedDate =null,
                        )
                {
                    $this->setPostID                = (isset($setPostID) && is_numeric(trim($setPostID))) ? intval(trim($setPostID)) : null ;
                    $this->setPostName              = (isset($setPostName))? trim(strip_tags($setPostName)) : null ;
                    $this->setPostTybe              = (isset($setPostTybe))? trim(strip_tags($setPostTybe)) : null ;
                    $this->setPostContent           = (isset($setPostContent)) ? $setPostContent : null ;
                    $this->setPostContent_2         = (isset($setPostContent_2)) ? $setPostContent_2 : null ;
                    $this->lastUpdatedBy            = (isset($lastUpdatedBy) && is_numeric(trim($lastUpdatedBy))) ? intval(trim($lastUpdatedBy)) : null ;
                    $this->createdDate              = (isset($createdDate)) ? $createdDate : null ;
                    $this->updatedDate              = (isset($updatedDate)) ? $updatedDate : null ;
                    
                    if((isset($setPostID) && !isset($this->setPostID)) || (isset($setPostName ) && (!isset($this->setPostName )
                        || $this->setPostName  === false)) || (isset($setPostTybe ) && !isset($this->setPostTybe )) 
                        || (isset($setPostContent_2) && !isset($this->setPostContent_2)) || (isset($updatedDate) && !isset($this->updatedDate))
                        || (isset($setPostID) && !isset($this->setPostID))){
                    $isSuccess = 0;
                }else $isSuccess = true;

            }
    /*******************************************/
    /** */
    function getSettingPostByID($getterUniqueProp = 'setPostID'){
        return $this->getObjectPropsByID(self::TABLE_NAME, $getterUniqueProp);
    }
    /*******************************************/
    /** */
    function insertSettingPost(&$err, ...$propsToInsert){
        $condition = $this->insertObject($err, self::TABLE_NAME, ...$propsToInsert);
        return $condition;
     }
     /*******************************************/
     /*  */
    function updateSettingPost(&$err,$where, ...$propsToUpdate){
        $condition = $this->updateObject($err, self::TABLE_NAME, $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
    /* */
     function deleteSettingPost(&$err, ...$where){
        global $conn;
        $condition = $this->deleteObject($err, self::TABLE_NAME, ...$where);
        return $condition;
     }
     /*********************************/
     /* function isSettingPostExist(&$probMatch, $seperator="AND", ...$where){
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
    } */
    /****************************/
    /** */
    function getSettingPostPropWhere($get, $comparision = array(), $where=array(), $seperator = "AND", $orderDir ='ASC'){
        return $this->getObjectPropWhere(self::TABLE_NAME, $get, $comparision, $where, $seperator, $orderDir);
    }
    /*******************************/
}