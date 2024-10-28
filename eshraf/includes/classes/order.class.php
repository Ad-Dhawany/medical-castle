<?php
class order{
    use queryTraits;
    public $orderID;
    public $costumerID;
    public $procStatus;
    public $discountRatio;
    public $createdDate;
    public $createdBy;
    public $paymentMethod;
    /* public $finalOrderFileURL; */
    
    public $saveDate;
    public $lastEditDate;
    public $receiveDate;
    public $lastProposalDate;
    public $firstResponseBy;
    public $firstResponseDate;
    public $lastResponseBy;
    public $lastResponseDate;
    public $confirmDate;
    public $doneBy;
    public $doneDate;
    public $cancelBy;
    public $cancelDate;
    public $username; /** == costumer name by foregin key */
    public $pharmacy; /** by foregin key */
     /**view vars */
     public $orderVersion;
     public $total;
     public $totalNet;
     public $lastProfit;
     public $avrProfit;
    const TABLE_NAME = "orders_meta";
    const VIEW_NAME = "get_order_meta";
    const VIEW_PROPS = ['orderVersion','total','totalNet','lastProfit','avrProfit'];
    function __construct(&$isSuccess = 0, $orderID=null, $costumerID=null,
                        $procStatus=null,  $discountRatio=null, $createdDate=null, $createdBy =null)
            {
                $this->orderID          = (isset($orderID) && is_numeric(trim($orderID))) ? intval(trim($orderID)) : null ;
                $this->costumerID       = (isset($costumerID) && is_numeric(trim($costumerID))) ? intval(trim($costumerID)) : null ;
                $this->procStatus       = (isset($procStatus) && is_numeric(trim($procStatus))) ? intval(trim($procStatus)) : null ;
                $this->discountRatio    = (isset($discountRatio) && is_numeric(trim($discountRatio))) ? doubleval(trim($discountRatio)) : null ;
                $this->createdDate      = (isset($createdDate)) ? $createdDate : null ;
                $this->createdBy        = (isset($createdBy) && is_numeric(trim($createdBy))) ? intval(trim($createdBy)) : null ;

                if((isset($orderID) && !isset($this->orderID)) || (isset($procStatus) && !isset($this->procStatus)) 
                || (isset($costumerID ) && (!isset($this->costumerID ) || $this->costumerID  === false))
                || (isset($discountRatio) && !isset($this->discountRatio)) || (isset($createdBy ) && !isset($this->createdBy ))){
                    $isSuccess = 0;
                }else $isSuccess = true;

            }
    /*******************************************/
    /** */
    function getOrderByID($joinGet=[]){
        $joinBy = (is_array($joinGet) && count($joinGet) > 0) ? ['costumerID', 'userID'] : [];
        /* return $this->getObjectPropsByID(self::VIEW_NAME, 'orderID', 'orderVersion', 'users',$joinBy, ...$joinGet); */
        global $conn;
        if(isset($this->orderID) && is_numeric($this->orderID)){
            $bind = [$this->orderID];
            $where = "t1.`orderID` = ? ";
            $join = '';
            $joinSelect = '';
            if(is_array($joinBy) && count($joinBy) == 2){
                $join = " INNER JOIN `users` t2 ON t1.". $joinBy[0]. " = t2.". $joinBy[1] ;
                $joinSelect = ",t2.". implode(',t2.', $joinGet);
            }
            $stmt = $conn->prepare("SELECT t1.* $joinSelect FROM `". self::VIEW_NAME. "` t1 $join WHERE $where");
            $stmt->execute($bind);
            $results = $stmt->fetchAll();
            $count  = $stmt->rowCount(); // get numper of rows in $stmt
            if ($count > 0){
                $orderVersion = $this->orderVersion ?? (($results[0]['procStatus'] > 3) ? 1 : 0);
                $i = 0;
                /* $isVersionExist = false; */
                foreach($results as $index=>$result){
                    if($result['orderVersion'] == $orderVersion){
                        /* $isVersionExist = true; */
                        $i = $index;
                    }
                }
                /* $orderVersion = ($isVersionExist)? $orderVersion : 2; */
                $keysArray = array_keys(get_class_vars(get_class($this)));
                foreach($results[$i] as $key=>$value){ /* (in_array($key ,array_keys(get_class_vars(get_class($this))))), get_class_vars($class) get puplic class props only whithout protected or private props */
                    if($value !== "" && in_array($key , $keysArray) ){ /* $value !== "" instead of !empty($value), becuase empty() func considers 0 and "0" values are empty values */
                        $this->$key = $value;
                    }
                }
                return true;
            }else{return false;}
        }else{return false;}
    }
    /*******************************************/
    /** */
    function insertOrder(&$err, ...$propsToInsert){
       $condition = $this->insertObject($err, self::TABLE_NAME, ...$propsToInsert);
       return $condition;
    }
    /*******************************************/
    /*  */
    function updateOrder(&$err,$where, ...$propsToUpdate){
        $condition = $this->updateObject($err, self::TABLE_NAME, $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
    /** */
    function getOrderPropWhere($get, $comparision = array(), $where=array(), $seperator = "AND", $orderDir ='ASC'){
        return $this->getObjectPropWhere(self::VIEW_NAME, $get, $comparision, $where, $seperator, $orderDir);
    }
    /*******************************************/
    /** */
    function getOrderPropByJoin($joinTable, $joinBy=[], $joinGet){
        return $this->getObjectPropByJoin(self::VIEW_NAME, $joinTable, $joinBy, $joinGet);
    }
    /*******************************************/
    /** */
    /* function getAvrProfit($netTotalPrice){
        if(is_numeric($netTotalPrice) && isset($this->orderID)){
            global $conn;
            $stmt = $conn->prepare("SELECT $netTotalPrice - (SELECT SUM(((SELECT `avrPayPrice` FROM `items` i WHERE i.itemID = oc.itemID) * oc.itemQty)) FROM `orders_contents` oc WHERE oc.orderID = ? AND orderVersion = 1) AS `avrProfit`");
            $stmt->execute([$this->orderID]);
            $result = $stmt->fetch();
            $count = $stmt->rowCount();
            return ($count == 1) ? $result[0] : false;
        }
    } */
    /*************************/
    /** */
    /* function getLastProfit($netTotalPrice){
        if(is_numeric($netTotalPrice) && isset($this->orderID)){
            global $conn;
            $stmt = $conn->prepare("SELECT $netTotalPrice - (SELECT SUM(((SELECT `payPrice` FROM `items` i WHERE i.itemID = oc.itemID) * oc.itemQty)) FROM `orders_contents` oc WHERE oc.orderID = ? AND orderVersion = 1) AS `lastProfit`");
            $stmt->execute([$this->orderID]);
            $result = $stmt->fetch();
            $count = $stmt->rowCount();
            return ($count == 1) ? $result[0] : false;
        }
    } */
    /*************************/

}