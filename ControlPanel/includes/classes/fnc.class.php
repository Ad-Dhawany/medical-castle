<?php
class fnc {
/* GetTitle function to insert Title of current page into header */
    static function getTitle(){
        global $pageTitle;
        if(isset($pageTitle))
            return $pageTitle;
        else
            return 'Control Panel';
    }
    //Redirect function, redirect to home page
    static function redirectHome($errorMsg, $url = null, $second = 4, $class = 'danger'){
        global $dir;
        if($url == null){
            $url = (isset($dir))? $dir. "dashboard/" : "dashboard/";
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
        echo "<div class='fa-pull-right my-3'><a href='". $url. "' class='btn btn-secondary' autofocus>Back</a> <a href='". ((isset($dir))? $dir. "dashboard/" : "dashboard/"). "' class='btn btn-secondary'>DashBoard</a></div>";
        echo "</div>";
        header("refresh:$second;url=$url");
        exit();
    }
    /*Check Items Function
    **Function to check if item exist in DataBase
    */
    static function checkitem($select , $from, $value){
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
    static function countItems($item, $table, $where = 1){
        global $conn;
        $stmtn = $conn->prepare("SELECT COUNT($item) FROM $table WHERE $where");
        $stmtn->execute();
        return $stmtn->fetchColumn();
    }
    /*
    **
    **
    */
    static function getLatest($select, $table,$order, $limit = 5){
        global $conn;
        $stmtGet = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        $stmtGet->execute();
        $result = $stmtGet->fetchAll();
        return $result;
    }
    /****************************/
    /*  */
    static function prettyPhone($phone){
        if(!preg_match("/^[+0]{1}+[- 0-9]{9,15}$/",$phone)){ return false;}
        if(strpos($phone, "00218") === 0 || strpos($phone, "+218") === 0){
            $newNumb = str_replace(["00218", "+218"], "0", $phone);
            $newNumb = substr_replace($newNumb, "-",3,0);
            $newNumb = substr_replace($newNumb, "-",7,0);
            return $newNumb;
        }elseif(strpos($phone, "00") === 0){
            $newNumb = substr_replace($phone, "+", 0, 2);
        }
        $newNumb = substr_replace($newNumb, " ",4,0);
        $newNumb = substr_replace($newNumb, "-",7,0);
        return $newNumb;
    }
    /*****************************/
    /** */
    static function isArabicStr($string, $incSpace = true ,$incNum = true ,$justAr = true){
        $ar = "ءإاأبتثجحخدذرزسشصضطظعغفقكلمنهويةى";
        $symb = ($incSpace) ? ". " : ".";
        $num = "0123456789";
        $arrayLitters = str_split($ar, 2); /** استخرج من السلسة النصية مصفوفة من الحروف العربية فقط */
        $arraySymb = str_split($symb, 1); /** استخرج من السلسة النصية مصفوفة من الرموز فقط */
        $arrayNum = str_split($num, 1); /** استخرج من السلسة النصية مصفوفة من الأرقام فقط */
     
           str_replace($arrayLitters, "",$string, $count);
              $c = (isset($count)) ? $count * 2 : 0;
  
           str_replace($arraySymb, "",$string, $count2);
           $c += (isset($count2)) ? $count2 : 0;
        if($incNum === true){
           str_replace($arrayNum, "",$string, $count3);
           $c += (isset($count3)) ? $count3 : 0;
        }
        if($c < 2){
           return false;
        }elseif($justAr === true && strlen($string) != $c){
           return false;
        }else{
           return true;
        }
  
     }
    /*******************************/
    /** Temp just for expirement*/
    static function prePrint($string){
        echo "<pre>";
            print_r($string);
        echo '</pre>';
    }
}