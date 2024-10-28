<!-- offcanvas start -->
   <div class="row flex-nowrap side-bar-container">
        <nav id = "sidebar-nav" class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar-costum">
            <div class = "sidebar-toggler ">
                <i class="fs-4 fa-solid fa-grip-lines-vertical"></i>
            </div>
            <div class="d-flex flex-column align-items-center pt-2 text-white min-vh-100">
            <div class="sidebar-header"><a href="<?php echo $dir ?>home" class="px-0 align-middle">
                    <img id="side-logo-img-text" src="<?php echo BASIC_ADDRES. "/media/site_logo/sideLogo_mini.png"?>" alt="logo" style="display: none;"> 
                    <img id="side-logo-img-icon" src="<?php echo BASIC_ADDRES. "/media/site_logo/logo_thump.png"?>" alt="logo"> 
                </a></div>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 px-2 align-items-center " id="menu">
                    <!-- <li class="nav-item">
                        <a href="#" class="nav-link align-middle px-0">
                            <i class="fs-4 fa fa-house"></i> <span class="ms-1 d-none sidebar-link-text">Home</span>
                        </a>
                    </li> -->
                    <li class="my-2">
                        <a href="<?php echo $dir ?>home" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-house"></i> <span class="ms-1 d-none sidebar-link-text"><?php echo lang('HOME')?></span> 
                        </a>
                    </li>
                    <li title="<?php echo $unregistredsArray['title'] ?>">
                        <a href="<?php echo $dir ?>orders/" class="nav-link px-0 align-middle <?php echo $unregistredsArray['disabled-class'] ?>" <?php echo $unregistredsArray['disabled-attr'] ?>>
                            <i class="fs-4 fas fa-file-invoice"></i> <span class="ms-1 d-none sidebar-link-text"><?php echo langs('ORDER')?></span></a>
                    </li>
                    <!-- <li>
                        <a href="<?php echo $dir ?>items" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-layer-group"></i> <span class="ms-1 d-none sidebar-link-text"><?php echo langs('ITEM')?></span> 
                        </a>
                    </li> -->
                    <li title="<?php echo $unregistredsArray['title'] ?>">
                        <a href="<?php echo $dir ?>news/" class="nav-link px-0 align-middle <?php echo $unregistredsArray['disabled-class'] ?>" <?php echo $unregistredsArray['disabled-attr'] ?>>
                            <i class="fs-4 far fa-newspaper"></i> <span class="ms-1 d-none sidebar-link-text"><?php echo lang('NEWS')?></span> 
                        </a>
                    </li>
                    <li class="py-1" title="<?php echo $unregistredsArray['title'] ?>">
                        <a href="<?php echo $dir ?>profile" class="nav-link px-0 align-middle <?php echo $unregistredsArray['disabled-class'] ?>" <?php echo $unregistredsArray['disabled-attr'] ?>>
                            <i class="fs-4 far fa-user"></i> <span class="ms-1 d-none sidebar-link-text"><?php echo langs('MY PRO')?></span> 
                        </a>
                    </li>
                </ul>
                <hr>
                <?php if($isLoggedIn){ ?>
                    <div class="dropdown dropup pb-1">
                        <a class="nav-link fw-semibold dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none sidebar-link-text"><?php echo $profileName;?></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item p-2" href="<?php echo $dir ?>profile/"><?php echo lang('MY PRO'); ?></a></li>
                            <li><a class="dropdown-item p-2" href="<?php echo $dir ?>profile/?do=edit"><?php echo lang('EDIT_PROFILE'); ?></a></li>
                            <!-- <li><a class="dropdown-item p-2" href="#"><?php echo lang('SETTINGS'); ?></a></li> -->
                            <li><a class="dropdown-item p-2 pb-lg-3" href="<?php echo $dir ?>logout.php"><?php echo lang('LOGOUT'); ?></a></li>
                        </ul>
                    </div>
                <?php }else{ ?>
                    <div class="">
                        <a href="<?php echo $dir ?>sign/" class="link px-0 align-middle" title="<?php echo langTXT("FOR PHA OWN ONL")?>">
                            <span class="ms-1 d-none sidebar-link-text"><?php echo langs('LOG OR REG')?></span> 
                        </a>
                    </div>
                <?php } ?>
                <div class="pb-1" dir="ltr">
                    <a id="hide-sidebar" class="nav-link fw-bold" role="button" title="<?php echo lang("HID SID")?>"><i class="fa-solid fa-angles-left"></i> <span class="d-none fs-8 sidebar-link-text"><?php echo lang("HID SID")?></span></a>
                </div>
            </div>
        </nav>
    </div>
   <!-- offcanvas end -->