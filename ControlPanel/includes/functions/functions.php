<?php
/* GetTitle function to insert Title of current page into header */
    function getTitle(){
        global $pageTitle;
        if(isset($pageTitle))
            return $pageTitle;
        else
            return 'Control Panel';
    }
    //Redirect function, redirect to home page
    function redirectHome($errorMsg, $url = null, $second = 4, $class = 'danger'){
        if($url == null){
            $url = 'index.php';
            $link = 'Home Page';
        }elseif($url == 'back'){
            $url = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '../';
            $link =  'Previous Page';
        }else{
            $link = 'Another Page';
        }
        echo "<div class='container'>";
        echo  "<div class='alert alert-$class text-center'>$errorMsg.</div>";
        echo  "<div class='alert alert-info text-center'>You will be redirected to $link after $second seconds.</div>";
        echo "</div>";
        header("refresh:$second;url=$url");
        exit();
    }
    /*Check Items Function
    **Function to check if item exist in DataBase
    */
    function checkitem($select , $from, $value){
        global $conn;
        $stmtc = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
        $stmtc->execute(array($value));
        $countc = $stmtc->rowCount();
        return $countc;
    }
    /*
    **count number of items function
    **
    */
    function countItems($item, $table){
        global $conn;
        $stmtn = $conn->prepare("SELECT COUNT($item) FROM $table");
        $stmtn->execute();
        return $stmtn->fetchColumn();
    }
    /*
    **
    **
    */
    function getLatest($select, $table,$order, $limit = 5){
        global $conn;
        $stmtGet = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        $stmtGet->execute();
        $result = $stmtGet->fetchAll();
        return $result;
    }
?>