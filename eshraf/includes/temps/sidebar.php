<!-- offcanvas start -->
   <div class="row flex-nowrap side-bar-container">
        <nav id = "sidebar-nav" class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar-costum">
            <div class = "sidebar-toggler ">
                <i class="fs-4 fa-solid fa-grip-lines-vertical"></i>
            </div>
            <div class="d-flex flex-column align-items-center pt-2 text-white min-vh-100">
                <div class="sidebar-header"><a href="<?php echo $dir ?>dashboard" class="px-0 align-middle">
                    <img id="side-logo-img-text" src="<?php echo BASIC_ADDRES. "/media/site_logo/sideLogo.png"?>" alt="logo" style="display: none;"> 
                    <img id="side-logo-img-icon" src="<?php echo BASIC_ADDRES. "/media/site_logo/logo.png"?>" alt="logo"> 
                </a></div>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 px-2 align-items-center " id="menu">
                    <!-- <li class="nav-item">
                        <a href="#" class="nav-link align-middle px-0">
                            <i class="fs-4 fa fa-house"></i> <span class="ms-1 d-none ">Home</span>
                        </a>
                    </li> -->
                    <li class="my-2">
                        <a href="<?php echo $dir ?>dashboard" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-gauge-high"></i> <span class="ms-1 d-none "><?php echo lang('DASHBOARD')?></span> 
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0 align-middle">
                            <i class="fs-4 fas fa-file-invoice"></i> <span class="ms-1 d-none "><?php echo langs('ORDER')?></span></a>
                    </li>
                    <li>
                        <a href="<?php echo $dir ?>items" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-layer-group"></i> <span class="ms-1 d-none "><?php echo langs('ITEM')?></span> 
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $dir ?>posts" class="nav-link px-0 align-middle">
                            <i class="fs-4 far fa-newspaper"></i> <span class="ms-1 d-none "><?php echo langs('POST')?></span> 
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $dir ?>users" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-people-group"></i> <span class="ms-1 d-none "><?php echo langs('USER')?></span> 
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $dir ?>costumers" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-users"></i> <span class="ms-1 d-none "><?php echo langs('COSTUMER')?></span> </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown dropup pb-4">
                    <a class="nav-link fw-semibold dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="d-none"><?php echo $profileName;?></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item p-2" href="<?php echo $dir ?>users/?do=info"><?php echo lang('MY PRO'); ?></a></li>
                        <li><a class="dropdown-item p-2" href="<?php echo $dir ?>users/?do=edit"><?php echo lang('EDIT_PROFILE'); ?></a></li>
                        <li><a class="dropdown-item p-2" href="#"><?php echo lang('SETTINGS'); ?></a></li>
                        <li><a class="dropdown-item p-2 pb-lg-3" href="<?php echo $dir ?>logout.php"><?php echo lang('LOGOUT'); ?></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
   <!-- offcanvas end -->