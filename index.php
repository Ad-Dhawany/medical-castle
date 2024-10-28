<?php
ob_start();
session_start();
$pageTitle = "HOME";
$dir = "";
$isLoggedIn = false;
if (isset($_SESSION['username'], $_SESSION['userID'], $_SESSION['groupID']) && $_SESSION['groupID'] > -1 && $_SESSION['groupID'] < 4) {
    $isLoggedIn = true;
}
include $dir . "init.php";
 /** start verification page */
if (isset($_GET['verify']) && $_GET['verify'] == 'email') {
    if(/* !isset($_SESSION['regStatus']) || $_SESSION['regStatus'] !== 0
                || */ !isset($_SESSION['trustStatus']) || $_SESSION['trustStatus'] > -1){
                header("location: ./");
                exit();
            }
    if (isset($_GET['code'])) {
        $errorMsg = "Incorrect verification code !";
        $code = (is_numeric($_GET['code'])) ? trim(strip_tags($_GET['code'])) : fnc::redirectHome($errorMsg, 'back');
        $costumer = new costumer($e, $_SESSION['userID']);
        $costumer->verifyEmail = $code;
        if ($costumer->CheckEmailVerification()) {
            $costumer->trustStatus  = 0;
            $costumer->regStatus    = 1;
            $costumer->lastActiveBy = $_SESSION['userID'];
            $costumer->lastActiveDate = date("Y-m-d H:i");
            $cond = $costumer->updateMemb($err, 'userID', 'trustStatus', 'regStatus', 'lastActiveBy', 'lastActiveDate');
            if ($cond) {
                $_SESSION['trustStatus'] = 0;
                $_SESSION['regStatus'] = 1;
                $successMsg = langTXT("CON VER DON");
                fnc::redirectHome($successMsg, './', 3, 'success');
            } else {
                fnc::redirectHome('خطأ: 7036', 'back', 3);
            }
        } else {
            fnc::redirectHome($errorMsg, 'back');
        }
    } else {
        $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
?>
<main class="container overflow-hidden" data-page-title="<?php echo  lang('VER EMA'); ?>">
    <section class="col-md-8 offset-md-2 my-4 p-2 form-container-ver1">
        <form class="" action="" method="GET">
            <input type="hidden" name="verify" value="email">
            <div class="form-group form-group-lg">
                <label class="form-label">
                    <?php echo langTXT("WE SEN VER") . " <b id='emailAddress'>($email)</b>. " . langTXT("CHE YOU MAI") ?>.
                </label>
            </div>
            <div class="form-group form-group-lg col-8 offset-2 col-md-6 offset-md-3">
                <label for=""><?php echo lang("VER COD") ?>:</label>
                <div class="input-field">
                    <input type="text" class="form-control" name="code" required="required">
                </div>
                <div class="my-2">
                    <input type="submit" class="btn btn-primary offset-8 col-4 offset-lg-9 col-lg-3"
                        value="<?php echo lang("VERIFY") ?>">
                </div>
            </div>
            <div class="form-group verify-email-choices d-flex justify-content-between">
                <div class="">
                    <a id="resend-link" class="link point-none" data-bs-toggle="modal"
                        data-bs-target="#alertModal"><?php echo lang("RES COD AGA") ?></a><span
                        class="resend-available"> (available in <span id="resend-timer">60</span>s)</span>
                </div>
                <div class="">
                    <a id="reset-email-link" class="link" data-bs-toggle="modal"
                        data-bs-target="#staticResetEmailModal"><?php echo langTXT("IT IS NOT MY") ?></a>
                </div>
            </div>
        </form>
    </section>
</main>

<!-- Modal -->
<div class="modal fade" id="staticResetEmailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticResetEmailModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo lang("RES EMA ADD") ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="">
                        <h5 class="col-form-label">
                            <?php echo lang("IF THI") . " <b>\"$email\"</b> " . langTXT("IS NOT YOU EMA") ?> </h5>
                    </div>
                    <div class="input-field">
                        <input type="email" id="newEmail" class="w-100 form-control"
                            placeholder="Enter your e-mail address" required="required">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="new-email-submit" data-bs-dismiss="modal" data-bs-toggle="modal"
                    data-bs-target="#alertModal" class="btn btn-primary"><?php echo lang("VERIFY") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal like alert -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">Alert</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="alert-modal-body" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo lang("OK") ?></button>
            </div>
        </div>
    </div>
</div>


<?php
    }
} else {
    /** Start Home Page (Main Page) */
    if (isset($_SESSION['trustStatus']) && $_SESSION['trustStatus'] == -1) {
        header("location: ./?verify=email");
        exit();
    }
    $posts = stat_getObjects::getPosts(1, 12);
    $tempNewsImg = "http://". $_SERVER['SERVER_NAME']. "/MedicalCastle/media/uploaded/posts/news_tmp/news_mini.webp";
    ?>
<header class="home-header">
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <!-- <div class="simple-overlay"></div> -->
                <img src="./media/home_carousel/slider_1.jpg" alt="" class="d-block w-100">

                <div class="container">
                    <div class="carousel-caption top-50 opacity-75 text-start">
                        <h1><?php echo langTXT("VAR OF MED FRO") ?>.</h1>
                        <!-- <p>Some representative placeholder content for the first slide of the carousel.</p> -->
                        <!-- <p><a class="btn btn-lg btn-primary" href="#">Sign up today</a></p> -->
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <!-- <div class="simple-overlay"></div> -->
                <img src="./media/home_carousel/slider_2.webp" alt="" class="d-block w-100">

                <div class="container">
                    <div class="carousel-caption top-50 opacity-75">
                        <h1><?php echo langTXT("WE OFF VAR OF") ?>.</h1>
                        <!-- <p>Some representative placeholder content for the second slide of the carousel.</p> -->
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <!-- <div class="simple-overlay"></div> -->
                <img src="./media/home_carousel/slider_3.jpg" alt="" class="d-block w-100">

                <div class="container">
                    <div class="carousel-caption top-50 opacity-75 text-end">
                        <h1><?php echo langTXT("WE PRO THE MOS") ?>.</h1>
                        <!-- <p>Some representative placeholder content for the third slide of this carousel.</p> -->
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</header>
<main class="my-5" data-page-title="<?php echo lang("HOME") ?>">
    <section class="general-text-cont brief-about-company container my-2">
        <h3 class="company-name"><b><?php echo lang("MED CAS COM") ?></b></h3>
        <p class=""><?php echo langTXT("MED CAS COM IS")?></p>
    </section>
    <section class="container marketing my-2">
        <hr>
        <!-- Three columns of text below the carousel -->
        <div class="row text-center">
            <div class="col-lg-4">
                <i class="fas fa-truck-arrow-right fs-1"></i>
                <h3><?php echo lang("SHI SER") ?></h3>
                <p><?php echo langTXT("SHI AND DEL SER") ?></p>
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-4">
                <i class="fas fa-tag fs-1"></i>
                <h3><?php echo lang("BES OFF") ?></h3>
                <p><?php echo langTXT("THE PRI OF ALL") ?></p>
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-4">
                <i class="far fa-credit-card fs-1"></i>
                <h3><?php echo lang("ONL PAY") ?></h3>
                <p><?php echo langTXT("MUL AND SEC PAY") ?></p>
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
    </section>
    <section class="container-lg news my-2">
        <hr>
        <h3 class="text-center my-2"><?php echo lang("NEW AND EVE") ?></h3>
        <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 my-2">
         <?php foreach($posts as $post){
            $imgSrc = $post->filePath ?? $tempNewsImg;
            $titleField = fnc::wordsAfterCountEtc($post->postTitle, 8);
            $contentField = fnc::wordsAfterCountEtc(strip_tags($post->postContent), 17) ;
            echo "<div class='col text-center post-container'>
                       <a class='post-img-cont mb-2". $unregistredsArray['disabled-class']. "' href='". $dir. "news/?id=". $post->postID. "'>
                           <img src='". $imgSrc. "' alt='$titleField' class='post-img'>
                       </a>
                       <a class='fw-bold fs-6 link link-dark' href='../news/?id=". $post->postID. "' title='". $post->postTitle. "'>". $titleField. "</a>
                       <p class='' title='". strip_tags($post->postContent). "'>". $contentField. "</p>
                   </div>";
         }
         ?>
        </div>
    </section>
</main>
<?php
}
include $dir . $tempsP . "footer.php";
ob_end_flush();