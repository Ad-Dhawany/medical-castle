<?php
ob_start();
session_start();
const allowedExt = ['jpg', 'jpeg', 'png'];
$postsDirPath = $_SERVER['DOCUMENT_ROOT']. "/MedicalCastle/media/uploaded/posts";
$postsHttpPath = "http://". $_SERVER['SERVER_NAME']. "/MedicalCastle/media/uploaded/posts";
$pageTitle = 'Posts';
$dir = "../";
    $isLoggedIn = false;
if(!isset($_SESSION['username'], $_SESSION['userID'], $_SESSION['groupID'], $_SESSION['regStatus'], $_SESSION['trustStatus'])
    || $_SESSION['groupID'] < 1 ||$_SESSION['groupID'] > 3 || $_SESSION['regStatus'] < 1 || $_SESSION['trustStatus'] < 0){
    header("location: ../");
    exit();
}
    require_once $dir."init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    if($do == 'manage'){
        //Start Manage page
        $posts = stat_getObjects::getPosts();
        if(is_array($posts)){
        ?>
        <main class="" data-page-title="<?php echo lang('POS MAN') ?>">
            <h1 class="text-center"><?php echo lang("POS MAN") ?></h1>
            <div class="p-3">
                <section class="">
                    <div class="table-responsive">
                        <table class="main-table manage text-center table table-bordered">
                            <thead class="fw-bold">
                                <tr>
                                    <td><?php echo lang('ID')?></td>
                                    <td><?php echo lang('PUBLISHER')?></td>
                                    <td><?php echo lang('POS TIT')?></td>
                                    <td><?php echo lang('POS CON')?></td>
                                    <td><?php echo lang('PUB DAT')?></td>
                                    <td><?php echo lang("UPD DAT")?></td>
                                    <td><?php echo lang("CONTROL")?></td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($posts) && !empty($posts)){
                                $deleteSetting = setting::getSpecificSetting('minPermissionToDeletePost')['value'];
                                /* $trTitleArr = ['3'=>'COS SUB NEW ORD', '4'=>'COS SUB NEW PRO']; */
                                foreach($posts as $post){
                                    $controllers = "";
                                    if($_SESSION['groupID'] > 2 || $_SESSION['userID'] == $post->userID){
                                       $controllers .= "<li><a id='' class='dropdown-item' href='./?do=edit&ID=". $post->postID. "'><i class='fas fa-pen-to-square'></i> ". lang("EDIT"). "</a></li>";
                                    }
                                    if($_SESSION['groupID'] >= $deleteSetting || $_SESSION['userID'] == $post->userID){
                                        $controllers .= "<li><a id='' class='dropdown-item' data-get-element-id='". $post->postID. "' data-bs-toggle='modal' data-bs-target='#deletingComfirm'><i class='fas fa-pen-to-square'></i> ". lang("DELETE"). "</a></li>";
                                    }
                                    $createField = date("Y-m-d | h:i a",strtotime($post->createdDate));
                                    $updateField = ($post->updatedDate != $post->createdDate)? date("Y-m-d | h:i a",strtotime($post->updatedDate)) : "0000";
                                    /* if(str_word_count($post->postContent, 0,$arAlphabet) > 10){
                                        $contArray = explode(" ",$post->postContent, 11);
                                        array_pop($contArray);
                                        $contentField = implode(" ", $contArray). "...";
                                    }else{
                                        $contentField = $post->postContent;
                                    } */
                                    $contentField = fnc::wordsAfterCountEtc(strip_tags($post->postContent));
                                    $titleField = fnc::wordsAfterCountEtc($post->postTitle, 5);
                                    $optionClass = (empty($controllers)) ? "disabled" : "";
                                    echo "<tr class='' title=''>
                                            <td><b>".$post->postID."</b></td>
                                            <td title='". $post->fullname. "'><a href='".$dir. "users/?do=info&ID=". $post->userID. "' target='blank' class='link link-dark'><b>". $post->username. "</a></b></td>
                                            <td title='". $post->postTitle. "'><a href='./?do=view&id=". $post->postID. "' target='blank' class='link link-dark'><b>".$titleField. "</b></td>
                                            <td title='". strip_tags($post->postContent). "'>". $contentField. "</td>
                                            <td>". $createField. "</td>
                                            <td>". $updateField. "</td>
                                            <td>
                                                <div class='btn-group'>
                                                    <button type='button' class='btn btn-secondary dropdown-toggle $optionClass' data-bs-toggle='dropdown' aria-expanded='false'>". lang("OPTIONS"). "</button>
                                                    <ul class='dropdown-menu dropdown-menu-dark'>
                                                        $controllers
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr> ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <a class="btn btn-secondary" href="../"><i class="fa fa-arrow-left"></i> <?php echo lang('BACK'); ?></a>
                <a class="btn btn-primary" href = "?do=add"><i class="fa fa-plus"></i> <?php echo lang('NEW POS') ?></a>
            </div>

            <!-- Start Modal -->
            <div class="modal fade" id="deletingComfirm" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deletingComfirm" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo lang("SEL REP MET") ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div> -->
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            <div class="">
                                <h5 class="col-form-label"> <?php echo lang("ARE YOU SUR") ?> ? </h5>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo lang("CANCEL") ?></button>
                            <form action="./?do=delete" method="post">
                                <input type="text" class="url important" name='post' value="">
                                <input type="hidden" name='ID' value="" data-put-id-in='value' style="display: none;">
                                <input type='submit' class="btn btn-danger" value="<?php echo lang("CONFIRM") ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Modal -->
        <?php
        }else{?>
            <section class="p-2 d-grid justify-content-center">
                <h1 class="text-center"><?php echo lang("POS MAN") ?></h1>
                <div class="my-5 px-5 py-2 form-container-ver1">
                    <h4 class="text-center mb-3"><b><i><?php echo langTXT("YOU HAV NO POS") ?> !!</i></b></h4>
                    <div class="d-flex justify-content-around my-1">
                        <a class="btn btn-secondary" href="../"><i class="fa fa-home"></i> <?php echo lang('DASHBOARD'); ?></a>
                        <a class="btn btn-primary" href = "?do=add"><i class="fa fa-plus"></i> <?php echo lang('NEW POS') ?></a>
                    </div>
                </div>
            </section>

        <?php
        }
        echo "</main>";
    }elseif($do == 'add'){
        //Start Edit page
        $mainTitle = lang('ADD NEW POS');
        $formHref = "./?do=insert";
        include "postEditor.php";
    }elseif($do == 'insert'){
        //Start Insert methodology
        if(isset($_POST['title'], $_POST['content'], $_POST['token'])){
            if(fnc::isNotSpam()){
                $post = new posts($is, null, $_SESSION['userID'],null,$_POST['title'],$_POST['content']);
                if(!$is){
                    fnc::redirectHome('خطأ: 6140', 'back',0);
                    exit();
                }
                $newPostID = $post->insertPost($er, 'userID','postTitle','postContent');
                if($newPostID > 0){
                    $post->postID = $newPostID;
                    $successMsg = langTXT("POS HAS PUB SUC");
                    if(isset($_FILES['images']) && $_FILES['images']['error'][0] == 0){
                        $imageName = $_FILES['images']['name'];
                        $imageTemp = $_FILES['images']['tmp_name'];
                        $imageType = $_FILES['images']['type'];
                        $imageSize = $_FILES['images']['size'];
                        $partFileName = str_pad($_SESSION['userID'], 3, "0", STR_PAD_LEFT);
                        $partFolderName = str_pad($newPostID,3,"0", STR_PAD_LEFT);
                        $fullSizeDirName = $postsDirPath. "/org_size/". $partFolderName ;
                        $reSizedDirName = $postsDirPath. "/". $partFolderName ;
                        $reSizedHttpName = $postsHttpPath. "/". $partFolderName ;
                        for($i=0; $i < count($imageName); $i++){
                            $fileNameArr = explode(".", $imageName[$i]);
                            $extension = strtolower(end($fileNameArr));
                            if(!in_array($extension, allowedExt)){ /* if extension is not in allowed extensions redirect back */
                                echo "<div class = 'alert alert-danger mx-4'>Invalid Image Format (". $imageName[$i]. ") </div>";
                            }elseif($imageSize[$i] >  5242880){ /* if file size more than 5MB prevent uploading process */
                                echo "<div class = 'alert alert-danger mx-4'>The Image Size should be less than 5MB (". $imageName[$i]. ") </div>";
                            }else{ /* if everything ok then done rename, uploading and updating processes */
                                $newName = date("Ymd").  date("Hi"). $partFileName. rand(10,99). rand(10,99). rand(10,99);
                                $largeSizedName = $newName. ".". $extension;
                                $miniSizedName = $newName. "_mini". ".". $extension;
                                $miniReSizedFilePath = $reSizedDirName. "/". basename($miniSizedName);
                                $fullSizeFilePath = $fullSizeDirName. "/". basename($largeSizedName);
                                $reSizedFilePath = $reSizedDirName. "/". basename($largeSizedName);
                                $reSizedHttpPath = $reSizedHttpName. "/". basename($largeSizedName);
                                // $fullSizeDirName = dirname($fullSizeFilePath);
                                // $reSizedDirName = dirname($reSizedFilePath);
                                if( ($post->insertPostAttachment($reSizedHttpPath)) ){
                                    if(!is_dir($fullSizeDirName)){
                                        mkdir($fullSizeDirName,0755, true);
                                    }
                                    if(!is_dir($reSizedDirName)){
                                        mkdir($reSizedDirName,0755, true);
                                    }
                                    move_uploaded_file($imageTemp[$i], $fullSizeFilePath);
                                    fnc::ak_img_resize($fullSizeFilePath, $reSizedFilePath, 720, 720, $extension);
                                    fnc::ak_img_resize($fullSizeFilePath, $miniReSizedFilePath, 240, 240, $extension);
                                    echo "<div class = 'alert alert-success mx-4'>(". $imageName[$i]. ") is uploaded successfully.</div>";
                                }
                            }
                        }
                        (glob($fullSizeDirName. "/*")) ? array_map('unlink', glob($fullSizeDirName. "/*")) : "empty" ; /* to delete All fullSized files */
                    }
                    fnc::redirectHome($successMsg,'./?do=manage',3,'success');
                }
            }else{
                exit();
            }
        }else{
            fnc::redirectHome('خطأ: 6052', 'back',0);
            exit();
        }
    }elseif($do == 'edit'){
        //Start Edit page
        $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : fnc::redirectHome("خطأ: 6058",'back',0);
        $post = new posts($is,$ID);
        if($post->getPostByID()){
            $mainTitle = lang('EDI POS');
            $formHref = "./?do=update";
            $postTitle = $post->postTitle;
            $postContents = $post->postContent;
            include "postEditor.php";
        }
    }elseif($do == 'update'){
        //Start Update methodology
        if(isset($_POST['ID'],$_POST['title'], $_POST['content'], $_POST['token'])){
            if(fnc::isNotSpam(10)){
                $post = new posts($is, $_POST['ID'], $_SESSION['userID'],null,$_POST['title'],$_POST['content']);
                if(!$is){
                    fnc::redirectHome('خطأ: 6074', 'back',0);
                    exit();
                }
                if(($post->updatePost($er, 'postID', 'userID','postTitle','postContent'))){
                    $successMsg = langTXT("POS HAS UPD SUC");
                    fnc::redirectHome($successMsg,'./?do=manage',2,'success');
                }
            }else{
                exit();
            }
        }else{
            fnc::redirectHome('خطأ: 6085', 'back',0);
            exit();
        }
    }elseif($do == 'delete'){
        //start delete page
        if(isset($_POST['ID'], $_POST['post']) && empty($_POST['post'])){
            $ID = intval($_POST['ID']);
            $post = new posts($is, $ID);
            if(!$is){
                fnc::redirectHome('خطأ: 6190', 'back',0);
                exit();
            }else{
                if($post->deletePost($err, 'postID')){
                    $postAttachmentsDirName = $postsDirPath. "/". str_pad($ID,3,"0", STR_PAD_LEFT) ;
                    (glob($postAttachmentsDirName. "/*")) ? array_map('unlink', glob($postAttachmentsDirName. "/*")) : "empty" ; /* to delete All fullSized files */
                    $successMsg = lang("POST"). ": <b>$ID</b> ". lang("HAS DEL SUC");
                    fnc::redirectHome($successMsg,'back',4,'success');
                }else{
                    fnc::redirectHome('خطأ: 6197', 'back',2);
                    exit();
                }
            }
        }
    }elseif($do == "view"){
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
            <!-- <section class="container">
                <div class="">
                    <a href="" class="btn btn-lg btn-primery">Back</a>
                    <a href="" class="btn btn-lg btn-primery">Edit</a>
                </div>
            </section> -->
        </main>
        
     <?php
    }else{exit();}
include $dir. $tempsP. "footer.php";