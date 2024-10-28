<?php
class log{
    public $type;
    /** */
    function __construct($type){
        if($this->checkType($type)){
            $this->type = $type;
            return true;
        }else{
            return false;
        }
    }
    /************************/
    /** */
    protected function checkType($type){
        static $types = ['deleteMember'];
        return (in_array($type, $types));
    }
    /************************/
    /** */
    function prepareLogFile(){
        switch($this->type){
            case "deleteMember":
                if(!file_exists(BASIC_ROOT. "/eshraf/logs/deleted_members_log.txt") || filesize(BASIC_ROOT. "/eshraf/logs/deleted_members_log.txt") < 10){
                    $logFileStr = fopen(BASIC_ROOT. "/eshraf/logs/deleted_members_log.txt", "w");
                    $t = "<table class='bg-white table table-bordered'><thead><tr><th>User ID</th><th>User Name</th><th>Deleted Member ID</th><th>Deleted Member Name</th><th>Deleted Member Permission</th><th>Deleting Date</th></tr></thead><tbody>";
                    fwrite($logFileStr, $t);
                    fclose($logFileStr);
                    return true;
                }else return false;
                break;
            default:
                return false;
        }
    }
    /************************/
    /** */
    function writeLogRow($type, ...$params){
        $type = explode("//", $this->type);
        /* switch($type[0]){

        } */
        $setter = "write". $type[0]. 'Log';
        return $this->$setter(...$params);
    }
    /** */
    /** */
    function writedeleteMemberLog($deletedID, $deletedName, $deletedGroupID){
        if(!file_exists(BASIC_ROOT. "/eshraf/logs/deleted_members_log.txt") || filesize(BASIC_ROOT. "/eshraf/logs/deleted_members_log.txt") < 10){
            if(!($this->prepareLogFile())){
                return false;
            }
        }
        $logFileStr = fopen(BASIC_ROOT. "/eshraf/logs/deleted_members_log.txt", "a");
        $logWrite = "<tr><td>". $_SESSION['userID']."</td><td>". $_SESSION['username']."</td><td>". $deletedID. "</td><td>". $deletedName. "</td><td>". permissionsCONST[$deletedGroupID]. "</td><td>". date("Y-m-d | h:i a"). "</td></tr>";
        fwrite($logFileStr, $logWrite);
        fclose($logFileStr);
        return true;
    }
    /********************************/


}