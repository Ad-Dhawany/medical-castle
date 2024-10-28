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
    static function redirectHome($errorMsg, $url = null, $second = 3, $class = 'danger'){
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
    /*
    **sum total of items function
    **
    */
    static function sumTotal($item, $table, $where = 1){
        global $conn;
        $stmtn = $conn->prepare("SELECT SUM($item) FROM $table WHERE $where");
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
    /** */
    static function setAntiSpam(){
        $_SESSION['spam_token'] = rand(300,999). substr(time(), 6,4). rand(100,999);
        return $_SESSION['spam_token'];
    }
    /******/
    /** */
    static function isNotSpam($seconds = 15){
        $saved = substr($_SESSION['spam_token'], 3, 4);
        $new = substr(time(), 6,4);
        return ($new - $saved > $seconds) ? true : false;
    }
    /*******************************/
    /** Temp just for expirement*/
    static function prePrint($string){
        echo "<pre>";
            print_r($string);
        echo '</pre>';
    }
    /****************************/
    /* this function is to resize images it is a copy from https://www.adamkhoury.com/PHP/video/Image-Resize-Function-Tutorial-jpg-gif-png-Change-Size */
    // Function for resizing jpg, gif, or png image files
    static function ak_img_resize($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $scale_ratio = $w_orig / $h_orig;
        if (($w / $h) > $scale_ratio) {
            $w = $h * $scale_ratio;
        } else {
            $h = $w / $scale_ratio;
        }
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif"){ 
        $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
        $img = imagecreatefrompng($target);
        } else { 
        $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        imagejpeg($tci, $newcopy, 90);
    }
    /****/
    /************************************/
    /** */
    static function wordsAfterCountEtc($fullString, $maxWords = 10){
        global $arAlphabet;
        if(str_word_count($fullString, 0,$arAlphabet) > $maxWords){
            $contArray = explode(" ",$fullString, ($maxWords + 1));
            array_pop($contArray);
            $retString = implode(" ", $contArray). "...";
        }else{
            $retString = $fullString;
        }
        return $retString;
    }
    /************************************/
    /*************************************************************/
}