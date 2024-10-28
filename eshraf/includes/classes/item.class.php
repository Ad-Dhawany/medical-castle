<?php /* require_once('../../confiq.php'); */
class item{
    use queryTraits;
    public $itemID;
    public $itemNum ;
    public $itemName ;
    public $Qty;
    public $payPrice;
    public $avrPayPrice;
    public $salePrice;
    public $profitRatio;
    public $avrProfitRatio;
    public $visibility;
    
    public $addDate;
    public $addBy;
    public $expDate1;
    public $expDate2;
    public $lastPublishBy;
    public $lastPublishDate;
    public $lastTrashBy;
    public $lastTrashDate;
    public $lastHideBy;
    public $lastHideDate;
    public $lastEditBy;
    public $lastEditDate;

    function __construct(&$errMode = 0, $itemID=null, $itemNum=null,
                        $itemName =null, $Qty=null, $payPrice=null,
                        $avrPayPrice=null, $salePrice=null, $profitRatio=null,
                        $avrProfitRatio =null, $visibility=null, $addDate=null,
                        $addBy=null, $expDate1=null, $expDate2= null)
            {
                $this->itemID          = (isset($itemID) && is_numeric(strip_tags($itemID))) ? intval(strip_tags($itemID)) : null ;
                $this->itemNum         = (isset($itemNum) && is_numeric(trim(strip_tags($itemNum)))) ? intval(strip_tags($itemNum)) : null ;
                $this->itemName        = (isset($itemName)) ? trim(strip_tags($itemName )) : null ;
                $this->Qty             = (isset($Qty) && is_numeric(strip_tags($Qty))) ? doubleval(strip_tags($Qty)) : null ;
                $this->payPrice        = (isset($payPrice) && is_numeric(strip_tags($payPrice))) ? doubleval(strip_tags($payPrice)) : null ;
                $this->avrPayPrice     = (isset($avrPayPrice) && is_numeric(strip_tags($avrPayPrice))) ? doubleval(strip_tags($avrPayPrice)) : null ;
                $this->salePrice       = (isset($salePrice) && is_numeric(strip_tags($salePrice))) ? doubleval(strip_tags($salePrice)) : null ;
                $this->profitRatio     = (isset($profitRatio) && is_numeric(strip_tags($profitRatio))) ? doubleval(strip_tags($profitRatio)) : null ;
                $this->avrProfitRatio  = (isset($avrProfitRatio) && is_numeric(strip_tags($avrProfitRatio))) ? doubleval(strip_tags($avrProfitRatio)) : null ;
                $this->visibility      = (isset($visibility) && is_numeric(strip_tags($visibility))) ? intval(strip_tags($visibility)) : null ;
                $this->addDate         = (isset($addDate)) ? $addDate : null ;
                $this->addBy          = (isset($addBy) && is_numeric(strip_tags($addBy))) ? intval(strip_tags($addBy)) : null ;
                $this->expDate1         = (isset($expDate1)) ? $expDate1 : null ;
                $this->expDate2         = (isset($expDate2)) ? $expDate2 : null ;

                if((isset($itemID) && !isset($this->itemID)) || (isset($salePrice) && !isset($this->salePrice))
                || (isset($Qty) && !isset($this->Qty)) || (isset($itemNum ) && (!isset($this->itemNum ) || $this->itemNum  === false))
                || (isset($itemName ) && !isset($this->itemName )) || (isset($payPrice) && !isset($this->payPrice))
                || (isset($avrPayPrice) && !isset($this->avrPayPrice)) || (isset($visibility) && !isset($this->visibility))
                || (isset($avrProfitRatio) && !isset($this->avrProfitRatio)) || (isset($profitRatio) && !isset($this->profitRatio))){
                    $errMode = 0;
                }else $errMode = true;

            }
        /*******************/
        /** */
    function getItemByID(){
        global $conn;
        if(isset($this->itemID) && is_numeric($this->itemID)){
            $itemID=$this->itemID;
            $stmt = $conn->prepare("SELECT * FROM items WHERE itemID=?");
            $stmt->execute(array($itemID));
            $result = $stmt->fetch();
            $count  = $stmt->rowCount(); // get numper of rows in $stmt
            if ($count == 1){
               // $this->itemID           = $result['itemID'];
                $this->itemNum        = $result['itemNum'];
                $this->itemName       = $result['itemName'];
                $this->Qty            = $result['Qty'];
                $this->payPrice       = $result['payPrice'];
                $this->avrPayPrice    = $result['avrPayPrice'];
                $this->salePrice      = $result['salePrice'];
                $this->profitRatio    = $result['profitRatio'];
                $this->avrProfitRatio = $result['avrProfitRatio'];
                $this->visibility     = $result['visibility'];

                $this->addDate        = $result['addDate'];
                $this->addBy        = $result['addBy'];
                $this->expDate1       = $result['expDate1'];
                $this->expDate2       = $result['expDate2'];
                $this->lastPublishBy   = $result['lastPublishBy'];
                $this->lastPublishDate = $result['lastPublishDate'];
                $this->lastTrashBy    = $result['lastTrashBy'];
                $this->lastTrashDate  = $result['lastTrashDate'];
                $this->lastHideBy     = $result['lastHideBy'];
                $this->lastHideDate   = $result['lastHideDate'];
                $this->lastEditBy     = $result['lastEditBy'];
                $this->lastEditDate   = $result['lastEditDate'];
                return true;
            }else{return false;}
        }else{return false;}
    }
    /***************************/
    /*  */
    function updateItem(&$err,$where, ...$propsToUpdate){
        if(isset($this->avrPayPrice) && $this->avrPayPrice == 0){
            $this->avrPayPrice = 0.001; /** because the avrProfitRatio eq. is salePrice/avrPayPrice . so the avrPayPrice shouldn't be absolute zero 0.00*/
        }
        $condition = $this->updateObject($err, "items", $where, ...$propsToUpdate);
        return $condition;
     }
     /****************************************/
     /** */
     function insertItem(&$err, ...$propsToInsert){
        if(isset($this->avrPayPrice) && $this->avrPayPrice == 0){
            $this->avrPayPrice = 0.001;
        }
        $condition = $this->insertObject($err, "items", ...$propsToInsert);
        return $condition;
     }
     /*********************************/
     /* */
     function deleteItem(&$err, ...$where){
        $condition = $this->deleteObject($err, 'items', ...$where);
        return $condition;
     }
     /*********************************/
     /* */
     function getItemPropByProp($get, $where, $by){
        return $this->getObjectPropByProp($get, $where, $by, "items");
     }
     /*********************************/
     /** Getters & Setters Area*/
     /**************************/
    /** */
    function isItemExist(&$probMatch, $seperator="AND", ...$where){
        foreach($where as $prob){
            $cond = $this->checkItem($err, 'items', $seperator, $prob);
            if($cond != 0){
                $probMatch = $prob;
                return $cond;
            }
        }
        return false;
    }
    /****************************/
    /** */
    function isThereUniqueProbInAnotherItem(&$probMatch , $whereAND, ...$whereOR){
        foreach($whereOR as $prob){
            $cond = $this->checkUniqueValueInTable($err , 'items', $whereAND, $prob);
            if($cond != 0){
                $probMatch = $prob;
                return $cond;
            }
        }
        return false;
    }
    /****************************/
    function getItemPropWhere($get, $comparision = array(), $where=array(), $seperator = "AND"){
        return $this->getObjectPropWhere('items', $get, $comparision, $where, $seperator);
    }
}