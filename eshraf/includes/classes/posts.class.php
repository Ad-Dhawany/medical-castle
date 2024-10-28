<?php
class posts{
    use queryTraits;
    public $postID ;
    public $userID ; /**publisher id */
    public $recipientsGroup ;
    public $postTitle ;
    public $postContent ;
    public $createdDate ;
    public $updatedDate ;

    /* view props */
    public $username; /**publisher name */
    public $fullname; /**publisher full-name */
    public $filePath; /**publisher full-name */

    const TABLE_NAME = "posts";
    const VIEW_NAME = "get_posts";
    const VIEW_PROPS = ['username', 'fullname', 'filePath'];
    
    function __construct(&$isSuccess = 0, $postID=null, $userID=null,
                        $recipientsGroup =null, $postTitle=null, $postContent=null,
                        $createdDate=null, $updatedDate =null,
                        $username=null, $fullname=null, $filePath=null)
                {
                    $this->postID               = (isset($postID) && is_numeric(trim($postID))) ? intval(trim($postID)) : null ;
                    $this->userID               = (isset($userID) && is_numeric(trim($userID))) ? intval(trim($userID)) : null ;
                    $this->recipientsGroup      = (isset($recipientsGroup) && is_numeric(trim($recipientsGroup))) ? intval(trim($recipientsGroup)) : null ;
                    $this->postTitle            = (isset($postTitle)) ? trim(strip_tags($postTitle)) : null ;
                    $this->postContent          = (isset($postContent)) ? $postContent : null ;
                    $this->createdDate          = (isset($createdDate)) ? $createdDate : null ;
                    $this->updatedDate          = (isset($updatedDate)) ? $updatedDate : null ;
                    $this->username            = (isset($username)) ? trim(strip_tags($username)) : null ;
                    $this->fullname            = (isset($fullname)) ? trim(strip_tags($fullname)) : null ;
                    $this->filePath            = (isset($filePath)) ? strip_tags($filePath) : null ;
                    
                    if((isset($postID) && !isset($this->postID)) || (isset($userID ) && (!isset($this->userID )
                        || $this->userID  === false)) || (isset($recipientsGroup ) && !isset($this->recipientsGroup )) 
                        || (isset($postContent) && !isset($this->postContent)) || (isset($updatedDate) && !isset($this->updatedDate))
                        || (isset($username) && !isset($this->username)) || (isset($fullname) && !isset($this->fullname))){
                    $isSuccess = 0;
                }else $isSuccess = true;

            }
    /*******************************************/
    /** */
    function getPostByID(){
        return $this->getObjectPropsByID(self::VIEW_NAME, 'postID');
    }
    /*******************************************/
    /** */
    function insertPost(&$err, ...$propsToInsert){
        $condition = $this->insertObject($err, self::TABLE_NAME, ...$propsToInsert);
        return $condition;
     }
     /*******************************************/
     /*  */
    function updatePost(&$err,$where, ...$propsToUpdate){
        $condition = $this->updateObject($err, self::TABLE_NAME, $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
    /* */
     function deletePost(&$err, ...$where){
        global $conn;
        $condition = $this->deleteObject($err, self::TABLE_NAME, ...$where);
        $stmt2 = $conn->prepare("ALTER TABLE `post_attachment` AUTO_INCREMENT 1");
        $stmt2->execute();
        return $condition;
     }
     /*********************************/
     function isPostExist(&$probMatch, $seperator="AND", ...$where){
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
    function getPostPropWhere($get, $comparision = array(), $where=array(), $seperator = "AND", $orderDir ='ASC'){
        return $this->getObjectPropWhere(self::VIEW_NAME, $get, $comparision, $where, $seperator, $orderDir);
    }
    /*************************/
    /* */
    function insertPostAttachment($filePath){
        global $conn;
        $stmt = $conn->prepare("INSERT INTO `post_attachment`(`postID`, `filePath`) VALUES (? , ?)");
        $stmt->execute([$this->postID, $filePath]);
        $count  = $stmt->rowCount(); // get numper of rows in $stmt
        if ($count > 0){
            $stmt2 =  $conn->prepare("SELECT LAST_INSERT_ID()");
            $stmt2->execute();
            $result = $stmt2->fetch();
            return (count($result) > 0) ? $result[0] : true;
        }else{
            // $err = "It may be the insert operation has interrupted";
            return false;
        }
    }
    /*******************************/
}