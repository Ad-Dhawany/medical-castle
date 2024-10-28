<?php
class setting{
    protected static function getQuery(&$result, $where = 1){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM setting WHERE $where");
            $stmt->execute();
            $result = $stmt->fetchAll(2);
            $count  = $stmt->rowCount();
            if($count <= 0){
                return false;
            }else{
                return true;
            }
    }
    static function getAllSetting(){
        global $conn;
        $setting=[];
        $where = 1;
        if(self::getQuery($rows, $where)){
            // $e="";
            foreach($rows as $row){
                $setting[$row['setName']] = $row['value'];
            }
            return $setting;
        }else return false;
    }
    /****************************/
    /** */
    static function getSpecificSetting($setName){
        $where = 'setName = "'. $setName.'"';
        if(self::getQuery($value, $where)){
            return $value[0];
        }else{
            return false;
        }
    }
    /****************************/
}