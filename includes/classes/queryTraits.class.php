<?php
trait queryTraits{/* */
    /* */
    function getQueryStrAndArray($props=array(), &$query, &$bind, $seperator = ",", $comparision=null){
        $bind = array();
        $query = "";
        if(is_array($comparision) && count($comparision) == count($props)){
            foreach($props as $i=>$prop){
                if(isset($this->$prop)){
                   $bind[] = $this->$prop;
                   $query .= "`$prop`". $comparision[$i]. "? $seperator ";
                }else{
                    return false;
                }
             }
             $query = rtrim($query, "$seperator ");
        }elseif($props[0] === "ALL"){
            $reflClass = new ReflectionClass(__CLASS__);
            $constsArray = array_keys($reflClass->getConstants());
            $viewProps = (in_array('VIEW_PROPS', $constsArray)) ? self::VIEW_PROPS : [];
            foreach($this as $key => $val){
                if(!in_array($key, $viewProps)){
                    if(isset($val) && !empty($val)){
                       $bind[] = $val;
                       $query .= "$key =? $seperator ";
                    }
                }
             }
             $query = rtrim($query, "$seperator ");
        }else{
            $query = implode("=? $seperator ", $props);
            $query = str_pad($query, strlen($query) + 2, "=?");
            foreach($props as $prop){
                if(isset($this->$prop)){
                    $bind[] =   $this->$prop ;
                }else{
                    return false;
                }
            }
        }
        return true;
    }
    /********************************/
    /** */
    function checkItem(&$err, $table, $seperator="AND" ,...$where){
        $err = null;
        global $conn;
        if($this->getQueryStrAndArray($where, $query, $bind, $seperator) === false){/* لتجهيز جملة الاستعلام بعد الوير وتجهز القيم في مصفوفة*/
            $err = "there is property doesn't set";
            return false;
        }
        $stmtc = $conn->prepare("SELECT * FROM $table WHERE $query");
        $stmtc->execute($bind);
        $countc = $stmtc->rowCount();
        return $countc;
    }
    /********************************************/
    /** */
    function getObjectPropsByID($table, $IDpropName, $anotherProp = null){
        global $conn;
        if(isset($this->$IDpropName)/*  && is_numeric($this->$IDpropName) */){
            $bind = [$this->$IDpropName];
            $where = "$IDpropName = ? ";
            if(isset($anotherProp)){
                if(isset($this->$anotherProp)){
                    $bind[] = $this->$anotherProp;
                    $where .= " AND $anotherProp = ? ";
                }else{
                    return false;
                }
            }
            $stmt = $conn->prepare("SELECT * FROM `$table` WHERE $where");
            $stmt->execute($bind);
            $result = $stmt->fetch(2);
            $count  = $stmt->rowCount(); // get numper of rows in $stmt
            if ($count == 1){
                $keysArray = array_keys(get_class_vars(get_class($this)));
                foreach($result as $key=>$value){ /* (in_array($key ,array_keys(get_class_vars(get_class($this))))), get_class_vars($class) get puplic class props only whithout protected or private props */
                    if($value !== "" && in_array($key , $keysArray) ){ /* $value !== "" instead of !empty($value), becuase empty() func consider 0 and "0" values are empty values */
                        $this->$key = $value;
                    }
                }
                return true;
            }else{return false;}
        }else{return false;}
    }
    /***************************/
    /* */
    function updateObject(&$err, $table ,$where, ...$propsToUpdate){
        $err = null;
        global $conn;
        if($this->getQueryStrAndArray($propsToUpdate, $query, $bind) === false){
            $err = "there is property doesn't set";
            return false;
        }
        $bind[] = $this->$where;
        $stmt2 = $conn->prepare("UPDATE $table SET $query WHERE $where = ?");
                       $stmt2->execute($bind);
                       $count2 = $stmt2->rowCount();
                       if($count2 > 0){
                          return true;
                       }else{
                        $err = "It may be the update operation has interrupted";
                        return false;
                       }
     }
    /***************************************/
    /** */
    function insertObject(&$err, $table, ...$propsToInsert){
        $err = null;
        global $conn;
        $values = "";
        $query = "";
        if($propsToInsert[0] === "ALL"){
            $reflClass = new ReflectionClass(__CLASS__);
            $constsArray = array_keys($reflClass->getConstants());
            $viewProps = (in_array('VIEW_PROPS', $constsArray)) ? self::VIEW_PROPS : [];
            foreach($this as $key => $val){
                if(!in_array($key, $viewProps)){
                    if(isset($val) && !empty($val)){
                        $bind[] = $val;
                        $query .= "$key , ";
                        $values .= "?, ";
                    }
                }
            }
            $values = substr_replace($values, "" ,-2, 1);
            $query = substr_replace($query, "" ,-2, 1);
        }else{
            $query = implode(", ", $propsToInsert);
            /*  $query = str_pad($query, strlen($query) + 2, "=?"); */ 
            foreach($propsToInsert as $prop){
                    $values .= "?, ";
                if(isset($this->$prop)){
                    $bind[] =   $this->$prop ;
                }else{
                    $err = '$this->'. $prop ." doesn't set";
                    return false;
                }
            }
            $values = substr_replace($values, "" ,-2, 1);
        }
        $stmt = $conn->prepare("INSERT INTO $table($query) VALUES($values)");
        $stmt->execute($bind);
        $count  = $stmt->rowCount(); // get numper of rows in $stmt
        if ($count > 0){
            $stmt2 =  $conn->prepare("SELECT LAST_INSERT_ID()");
            $stmt2->execute();
            $result = $stmt2->fetch();
            return (count($result) > 0) ? $result[0] : true;
        }else{
            $err = "It may be the insert operation has interrupted";
            return false;
        }
    }
    /**********************************/
    /*  */
    function deleteObject(&$err, $table ,...$where){
        $err = null;
        $resetAutoIncrementClasses = ["item", "orderItem", "postAttachment"];
        global $conn;
        if($this->getQueryStrAndArray($where, $query, $bind, "AND") === false){
            $err = "there is property doesn't set";
            return false;
        }
        $stmt = $conn->prepare("DELETE FROM $table WHERE $query");
        $stmt->execute($bind);
        $count = $stmt->rowCount();
        if(in_array(get_class($this), $resetAutoIncrementClasses)){
            $stmt2 = $conn->prepare("ALTER TABLE `". $table. "` AUTO_INCREMENT 1");
            $stmt2->execute();
        }
        $err = "It may be the Delete operation has interrupted";
        return ($count > 0) ? true : false;
    }
    /**********************************/
    /*  */
    function getObjectPropByProp($get, $where, $by, $table){
        global $conn;
        $stmt = $conn->prepare("SELECT $get FROM $table where $where = ?");
        $stmt->execute([$this->$by]);
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        return ($count == 1) ? $result[0] : false;
    }
    /**********************************/
    /** */
    /* */
    function checkUniqueValueInTable(&$err, $table, $whereAND, ...$whereOR){
        $err = null;
        global $conn;
        if($this->getQueryStrAndArray($whereOR, $query, $bind, "OR") === false){/* لتجهيز جملة الاستعلام بعد الوير وتجهز القيم في مصفوفة*/
            $err = "there is property doesn't set";
            return false;
        }
        array_unshift($bind, $this->$whereAND);
        $stmt = $conn->prepare("SELECT * FROM $table WHERE $whereAND != ? AND ($query)");
        $stmt->execute($bind);
        $count = $stmt->rowCount();
        $err = "It may be the Check operation has interrupted";
        return ($count > 0) ? true : false;
    }
    /**********************************/
    /** */
    function getObjectPropWhere($table, $get, $comparision = array(), $where=array(), $seperator = "AND", $orderDir ='ASC'){
        global $conn;
        if($this->getQueryStrAndArray($where, $query, $bind, $seperator, $comparision) === false){
            return false;
        }
        $stmt = $conn->prepare("SELECT `$get` FROM `$table` WHERE $query ORDER BY $get $orderDir");
        $stmt->execute($bind);
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        return ($count > 0) ? $result[0] : false;
    }
    /**********************************/
    /** */
    function verifyObjectProbWhereProp($table, $verify, $where){
        global $conn;
        $stmt = $conn->prepare("SELECT `$verify` FROM `$table` WHERE `$where` = ?");
        $stmt->execute([$this->$where]);
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count == 1){
            return ($result[0] == $this->$verify) ? true : false;
        }else{
            return false;
        }
    }
    /**********************************/
}