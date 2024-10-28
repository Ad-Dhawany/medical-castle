<?php
ob_start();
    session_start();
    $pageTitle = 'News';
    $dir = "../";
    $isLoggedIn = false;
    // const costGroupID = 0;
    /* to verify that the person is user and has admin permissions */
    if(!isset($_SESSION['username']) || !isset($_SESSION['userID']) || !isset($_SESSION['groupID'])){
        header('location: ../sign/');
        exit();
    }else{
        $isLoggedIn = true;
    }
    include $dir. "init.php";
     /* this page actually is a collects of pages and $do is the param to select the required page */
    $id = $_GET['post'] ?? 'all'; /* do by default is all page */
    if($id == 'all'){
        ?>
        <main class="" data-page-title="<?php echo lang('NEWS') ?>">
            <section class="container" >
                <h1 class="text-center"><?php echo lang("COM NEW") ?></h1>
                <div id="posts-main-cont" class="posts-main-cont d-grid justify-content-center">
                    <!-- <div class="card post-cont">
                        <div class="post-images-cont d-flex flex-wrap">
                            <div class="tab-content col-12 col-md-9 order-1 order-md-2">
                                <div class="main-img-cont" >
                                    <img id="main-img" class="animate" src="http://localhost/MedicalCastle/media/uploaded/posts/002/202303121056001992873.jpg" />
                                </div>
                            </div>
                            <div class="col-12 col-md-3 order-2 order-md-1 main-thumps-container">
                                <div id="thumps-nav-up" data-nav-opr="-" class="thumps-nav-btn"><i class="fas fa-chevron-up"></i></div>
                                <div id="thumps-nav-down" data-nav-opr="+" class="thumps-nav-btn"><i class="fas fa-chevron-down"></i></div>
                                <div class="position-relative thumps-list-cont" data-nav-target="">
                                    <ul class="thumps-list nav nav-tabs d-flex d-md-block" data-img-target-id="main-img">
                                        <li class="thump-cont col-3 col-md-12">
                                            <img src="http://localhost/MedicalCastle/media/uploaded/posts/003/202303121150001513440.jpg" />
                                        </li>
                                        <li class="thump-cont col-3 col-md-12">
                                            <img src="http://localhost/MedicalCastle/media/uploaded/posts/002/202303121056001404072.jpg" />
                                        </li>
                                        <li class="thump-cont col-3 col-md-12">
                                            <img src="http://localhost/MedicalCastle/media/uploaded/posts/002/202303121056001992873.jpg" />
                                        </li>
                                        <li class="thump-cont col-3 col-md-12">
                                            <img src="http://localhost/MedicalCastle/media/uploaded/posts/002/202303121056001404072.jpg" />
                                        </li>
                                        <li class="thump-cont col-3 col-md-12">
                                            <img src="http://localhost/MedicalCastle/media/uploaded/posts/003/202303121150001513440.jpg" />
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="details">
                            <h3 class="product-title">men's shoes fashion</h3>
                            <p class="product-description">Suspendisse quos? Tempus cras iure temporibus? Eu laudantium cubilia
                                sem sem! Repudiandae et! Massa senectus enim minim sociosqu delectus posuere.</p>
                        </div>
                    </div> -->
                </div>
            </section>
        </main>
     <?php
    }else{
     
    }
    include $dir. $tempsP. "footer.php";
ob_end_flush();