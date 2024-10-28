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
            foreach($this as $key => $val){
                if(isset($val) && !empty($val)){
                   $bind[] = $val;
                   $query .= "$key =? $seperator ";
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
            foreach($this as $key => $val){
                if(isset($val) && !empty($val)){
                   $bind[] = $val;
                   $query .= "$key , ";
                   $values .= "?, ";
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
        if ($count == 1){
            return true;
        }else{
            $err = "It may be the insert operation has interrupted";
            return false;
        }
    }
    /**********************************/
    /*  */
    function deleteObject(&$err, $table ,...$where){
        $err = null;
        global $conn;
        if($this->getQueryStrAndArray($where, $query, $bind, "AND") === false){
            $err = "there is property doesn't set";
            return false;
        }
        $stmt = $conn->prepare("DELETE FROM $table WHERE $query");
        $stmt->execute($bind);
        $count = $stmt->rowCount();
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
    function getObjectPropWhere($table, $get, $seperator = "AND", $comparision = array(), $where=array()){
        global $conn;
        if($this->getQueryStrAndArray($where, $query, $bind, $seperator, $comparision) === false){
            return false;
        }
        $stmt = $conn->prepare("SELECT `$get` FROM `$table` WHERE $query");
        $stmt->execute($bind);
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        return ($count == 1) ? $result[0] : false;
    }
    /**********************************/
}