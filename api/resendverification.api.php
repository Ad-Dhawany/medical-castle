<?php
session_start();
$dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
if(!isset($_SESSION['regStatus']) || $_SESSION['regStatus'] !== 0 ||
    !isset($_SESSION['trustStatus']) || $_SESSION['trustStatus'] > -1){
    exit();
}
if(isset($_SESSION['userID'])){
    $userID = (is_numeric($_SESSION['userID'])) ? intval($_SESSION['userID']) : exit();
    /** */
    if(isset($_POST['newEmail'])){
        $costumer = new costumer($e, $userID,null,$_POST['newEmail']);
        if(!$e){
            echo "خطأ : 6013";
            exit();
        }
        $isResetEmail = true;
        if((!$costumer->isMemberExist($prop, 'OR', 'email'))){
            $updateProps = ['email','verifyEmail'];
        }else{
            echo "<p class='alert alert-warning'>This Email address (". $_POST['newEmail']. ") is previously used. Try Another Address</p>";
            exit();
        }
    }else{  /** */
        $costumer = new costumer($e, $userID);
        if(!$e){ /**if object created successfully */
            echo "خطأ : 6025";
            exit();
        }
        $isResetEmail = false;
        $costumer->email = $costumer->getMemPropByProp('email', 'userID', 'userID');
        $updateProps =['verifyEmail'];
    }
    /*******/
        
    $verifyCode = rand(100, 999). "". rand(100,999);
    /* $verifyCode = str_pad($verifyCode, 6, 0); */
    $costumer->verifyEmail = $verifyCode;
    if($costumer->updateMemb($err,'userID', ...$updateProps)){
        $_SESSION['email'] = $costumer->email ;
        echo ($isResetEmail)? "<p class='alert alert-success'>". langTXT("YOU EMA ADD HAS"). ". <br> ". lang("EMAIL"). ": <b id='newEmailAddress'>". $costumer->email. "</b></p>" : "";
        $subject = "رمز التأكيد";
        $msg = "<main>
                    <section>
                        <div dir='rtl'>
                            مرحباً بك في شركة القلعة الطبية بالأسفل رمز تأكيد بريدك الإلكتروني قم بإدخاله بالحقل المطلوب أو انقر على الرابط في آخر الرسالة
                        </div>
                        <div>
                            <center><h3 dir='rtl'>رمز التأكيد: <strong>". $verifyCode. "</strong></h3></center>
                        </div>
                        <div>
                            Welcome to Medical Castle, above is verification code to verify your e-mail. Enter it the required field onto the website. or click on the link bellow. 
                        </div>
                    </section>
                </main>
                <footer>
                  <div dir='rtl'><center><a href='www.medicalcastle.ly/?verify=email&code=". $verifyCode. "'><i><b>رابط التأكيد المباشر  Verifiction  Link</b></i></a></center></div>
                  <div>
                    <p dir='rtl'>ملاحظة: يجب إدخال رابط التأكيد المباشر من نفس المتصفح الذي سجلت منه</p>
                    <p>Notice: The verification link must be entered by using the same browser that used to registration</p>
                  </div>
                </footer>";
          $headers = "From: support@medicalcastle.ly\r\n";
          $headers .= "MIME-Version: 1.0 \r\n";
          $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        if(mail($costumer->email, $subject, $msg, $headers)){
            echo "<p class='alert alert-success'>". langTXT("WE RES VER COD"). ".</p>";
            exit();
        }else{
            echo "<p class='alert alert-warning'>". langTXT("THE RES PRO MAY"). ".</p>";
            exit();
        }
    }else{
        echo "<p class='alert alert-warning'>". langTXT("THE RES PRO MAY"). ".</p>";
        exit();
    }
}else{
    exit();
}