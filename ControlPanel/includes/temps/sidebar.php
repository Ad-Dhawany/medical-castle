<!-- offcanvas start -->
   <div class="row flex-nowrap">
        <nav id = "sidebar-nav" class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar-costum">
            <div class = "sidebar-toggler p-2 py-3 ">
                <i class="fs-4 fa-solid fa-grip-lines-vertical"></i>
            </div>
            <div class="d-flex flex-column align-items-center  px-3 pt-2 text-white min-vh-100">
                <div><a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none ">Menu</span>
                </a></div>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center " id="menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link align-middle px-0">
                            <i class="fs-4 fa fa-house"></i> <span class="ms-1 d-none ">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $dir ?>dashboard" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-gauge-high"></i> <span class="ms-1 d-none "><?php echo lang('HOME')?></span> 
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-receipt"></i> <span class="ms-1 d-none "><?php echo langs('ORDER')?></span></a>
                    </li>
                    <li>
                        <a href="<?php echo $dir ?>items" class="nav-link px-0 align-middle">
                            <i class="fs-4 fa fa-layer-group"></i> <span class="ms-1 d-none "><?php echo langs('ITEM')?></span> 
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
                <div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="mdo.png" alt="hugenerd" width="30" height="30" class="rounded-circle">
                        <span class="d-none  mx-1">loser</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
   <!-- offcanvas end -->