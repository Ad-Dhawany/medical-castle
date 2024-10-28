<?php
class orderItem{
    use queryTraits;
    public $ID ;
    public $orderID ;
    public $itemID ;
    public $itemPrice ;
    public $itemQty ;
    public $itemExpDate ;
    public $orderVersion ;
    public $insertDate ;
    public $itemName ;
    public $itemNum ;
    /** View Vars */
    public $totalItemPrice ;

    const TABLE_NAME = "orders_contents";
    const VIEW_NAME = "get_order_content";
    const VIEW_PROPS = ['totalItemPrice'];
    
    function __construct(&$isSuccess = 0, $ID=null, $orderID=null,
                        $itemID =null, $itemPrice=null, $itemQty=null,
                        $itemExpDate=null, $orderVersion =null,
                        $insertDate=null, $totalItemPrice=null)
                {
                    $this->ID               = (isset($ID) && is_numeric(trim($ID))) ? intval(trim($ID)) : null ;
                    $this->orderID          = (isset($orderID) && is_numeric(trim($orderID))) ? intval(trim($orderID)) : null ;
                    $this->itemID           = (isset($itemID) && is_numeric(trim($itemID))) ? intval(trim($itemID)) : null ;
                    $this->itemPrice        = (isset($itemPrice) && is_numeric(trim($itemPrice))) ? doubleval(trim($itemPrice)) : null ;
                    $this->itemQty          = (isset($itemQty) && is_numeric(trim($itemQty))) ? doubleval(trim($itemQty)) : null ;
                    $this->itemExpDate      = (isset($itemExpDate)) ? trim(strip_tags($itemExpDate)) : null ;
                    $this->orderVersion     = (isset($orderVersion) && is_numeric(trim($orderVersion))) ? intval(trim($orderVersion)) : null ;
                    $this->insertDate       = (isset($insertDate)) ? trim(strip_tags($insertDate)) : null ;
                    $this->totalItemPrice   = (isset($totalItemPrice) && is_numeric(trim($totalItemPrice))) ? doubleval(trim($totalItemPrice)) : null ;
                    
                    if((isset($ID) && !isset($this->ID)) || (isset($orderID ) && (!isset($this->orderID )
                        || $this->orderID  === false)) || (isset($itemID ) && !isset($this->itemID )) 
                        || (isset($itemPrice) && !isset($this->itemPrice)) || (isset($itemQty) && !isset($this->itemQty))
                        || (isset($orderVersion) && !isset($this->orderVersion)) || (isset($totalItemPrice) && !isset($this->totalItemPrice))){
                    $isSuccess = 0;
                }else $isSuccess = true;

            }
    /*******************************************/
    /** */
    function getOrderItemByID(){
        return $this->getObjectPropsByID(self::TABLE_NAME, 'ID');
    }
    /*******************************************/
    /** */
    function insertOrderItem(&$err, ...$propsToInsert){
        $condition = $this->insertObject($err, self::TABLE_NAME, ...$propsToInsert);
        return $condition;
     }
     /*******************************************/
     /*  */
    function updateOrderItem(&$err,$where, ...$propsToUpdate){
        $condition = $this->updateObject($err, self::TABLE_NAME, $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
    /* */
     function deleteOrderItem(&$err, ...$where){
        $condition = $this->deleteObject($err, self::TABLE_NAME, ...$where);
        return $condition;
     }
     /*********************************/
     function isOrderItemExist(&$probMatch, $seperator="AND", ...$where){
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
    function getOrderItemPropWhere($get, $comparision = array(), $where=array(), $seperator = "AND", $orderDir ='ASC'){
        return $this->getObjectPropWhere(self::VIEW_NAME, $get, $comparision, $where, $seperator, $orderDir);
    }
    /*************************/
    /* */
    function getItemPrice($discRatio){
        if(is_numeric($discRatio) && $this->orderVersion == 1){
            return number_format((doubleval($this->itemPrice) * (1 - doubleval($discRatio) / 100)), 2);
        }else{
            return number_format((doubleval($this->itemPrice)), 2);
        }
    }
    /*******************************/
    /** */
    function getTotalItemPrice($discRatio){
            return $this->getItemPrice($discRatio) * $this->itemQty;
    }
    /*******************************/
    // /** */
    // function setViewPropsNull(&$viewPropsValues = []){
    //     if(isset($viewProps) && is_array($viewProps)){
    //         foreach($viewProps as $prop){
    //             $viewPropsValues[$prop]= $this->$prop;
    //             $this->$prop = null;
    //         }
    //     }
    // }
    // /*****/
    // /** */
    // function getViewPropsValues($viewPropsValues){
    //     if(is_array($viewPropsValues)){
    //         foreach($viewPropsValues as $key=>$value){
    //             $this->$key = $value;
    //         }
    //     }
    // }
    /*******************************/
}