<?php
ob_start();
session_start();
$pageTitle = "HOME";
$dir = "../";
$isLoggedIn = false;
if (isset($_SESSION['username'], $_SESSION['userID'], $_SESSION['groupID']) && $_SESSION['groupID'] > -1 && $_SESSION['groupID'] < 4) {
    $isLoggedIn = true;
}
include $dir. "init.php";
$aboutUsPost = new settingPost($is, null, "about_us_text_". $langArray["lang-html"]); // get about paragraph
$cond = $aboutUsPost->getSettingPostByID("setPostName");
if($cond == false){
    $aboutUsPost->setPostName = "about_us_text_en";
    $aboutUsPost->getSettingPostByID("setPostName");
}
$companyAddress = new settingPost($is, null, "company_address_". $langArray["lang-html"]); // get address grom database
$cond = $companyAddress->getSettingPostByID("setPostName");
if($cond == false){
    $companyAddress->setPostName = "company_address_en";
    $companyAddress->getSettingPostByID("setPostName");
}
?>
<main class="" data-page-title="<?php echo lang("ABO US") ?>">
    <h1 class="text-center my-2 about-us-subject"><?php echo lang("MED CAS COM") ?></h1>
    <section class="container my-4 px-2">
        <div class="general-text-cont about-text-cont fs-5">
            <?php echo $aboutUsPost->setPostContent ?>
        </div>
    </section>
    <hr>
    <section class="container">
        <h3 class=""><?php echo lang("COM ADD") ?></h3>
        <address class=""><span class=""><?php echo lang("ADDRESS") ?></span>: <span class=""><?php echo $companyAddress->setPostContent ?></span></address>
        <div class="border border-2 m-2">
            <img src="../media/general_images/address-map.jpg" width="100%" alt="Company address" class="">
        </div>
    </section>
</main>
<?php
include $dir . $tempsP . "footer.php";
ob_end_flush();