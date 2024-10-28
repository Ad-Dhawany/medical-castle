<main class="my-5">
    <h1 class="text-center m-3"><?php echo $mainTitle ?? "" ?></h1>
    <section class="container">
        <div class="row offset-md-1 col-md-10">
            <form class="" action="<?php echo $formHref ?? "" ?>" enctype="multipart/form-data" method="post">
                <div class="col-md-4">
                    <label for="post-title" class="form-label"><?php echo lang("POS TIT")?></label>
                    <input type="text" name="title" class="form-control" id="post-title" value="<?php echo $postTitle ?? ""?>" placeholder="Post Title" data-no-asterisk="1" required>
                </div>
                <div class="my-3">
                    <label for="post-content" class="form-label"><?php echo lang("POS CON")?></label>
                    <textarea name="content" class="form-control" id="post-content" data-tiny-editor="1"><?php echo $postContents ?? ""?></textarea>
                </div>
                <div class="" style="display: none;">
                    <input type="hidden" name="token" value="<?php echo fnc::setAntiSpam() ?>" style="display: none;">
                    <input type="hidden" name="ID" value="<?php echo $ID ?? ""?>" style="display: none;">
                </div>
                <!-- -->
                <div class="form-label" data-visibility='switcher' data-vis-Id="add-images">
                    <label class="switcher-text"><?php echo /* lang("ADD IMA") */ langTXT("ATT SOM IMA TO") ?> <i class="fas fa-caret-down"></i></label>
                </div>
                <div class="add-images is-hidden my-3" data-visibility="target" data-target-vis-Id="add-images" style="display: none;">
                        <!-- <h5 class="modal-title" id=""><?php echo langTXT("ATT SOM IMA TO") ?></h5> -->
                    <div class="">
                        <div class="custom-file">
                            <input type="file" class="form-control" id="customFileLangHTML" name="images[]" multiple>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="">
                    <input type="submit" class="btn btn-primary" value="<?php echo lang("PUBLISH")?>">
                </div>

            </form>
        </div>
    </section>
    
</main>