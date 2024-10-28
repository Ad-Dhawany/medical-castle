<?php
class stat_memb{
    static function getMembers($where = "1", $class='member'){
        global $conn;
        $members=[];
        $stmt = $conn->prepare("SELECT * FROM users WHERE $where");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count  = $stmt->rowCount();
        if($count > 0){
            $e="";
            foreach($rows as $row){
                $members[] = new $class($e, $row['userID'], $row['username'], $row['email'], $row['phone'], $row['fullname'], $row['groupID'], $row['trustStatus'],$row['regDate'], $row['regStatus'],null,$row['pharmacy']);
            }
            return $members;
        }else return false;
    }
}