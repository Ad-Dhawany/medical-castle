<nav id = "top-navbar"class="navbar navbar-expand-md navbar-costum">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $dir. "/dashboard" ?>"><img src="<?php echo BASIC_ADDRES. "/media/site_logo/navLogo.png"?>" alt="logo"></a>
  <!--  -->
    <div id="notif-main-container" class="position-relative order-md-last ms-auto">
        <div class="notif-icon" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span id="unreads-count-badge" class="unreads-count badge rounded-pill bg-danger"></span>
        </div>
        <ul class="notifications dropdown-menu" id="notificationsListCont">
          <h5 class="notifcations-title"><?php echo lang("NOTIFICATIONS") ?> - <span id="notif-count" class="unreads-count" title="<?php echo lang("UNR NOT") ?>"></span></h5>
          <div id="notificationsList" data-notifications-control="navNotificaitons">
            <!-- <div class="notifications-item">
              <div class="text" title="">
                  <h6 class="notif-msg"><i class='dot'>.</i> <i>moshref</i> completed the order No. 2</h6>
                  <div class="notif-footer">
                    <p class="notif-pharmacy" title="Pharmacy Name">السلامة الدولية</p>
                    <p class="notif-date-time"><span id="notif-date">yesterday </span> at <span id="notif-time">10:22</span></p>
                  </div>
              </div>
            </div>
            <div class="notifications-item">
              <div class="text" title="">
                  <h6 class="notif-msg"><i class='dot'>.</i> <i>moshref</i> completed the order No. 2</h6>
                  <div class="notif-footer">
                    <p class="notif-pharmacy" title="Pharmacy Name">ليبيا</p>
                    <p class="notif-date-time"><span id="notif-date">today </span> at <span id="notif-time">10:22</span></p>
                  </div>
              </div>
            </div> -->
          </div>
        </ul>
      </div>
      <!--  -->
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav py-2">
        <li class="nav-item">
         <a class="nav-link fw-semibold"  data-link-name="dashboard" href="<?php echo $dir. "/dashboard" ?>"><?php echo lang('DASHBOARD');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" data-link-name="orders" href="<?php echo $dir ?>orders/"><?php echo langs('ORDER');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" data-link-name="items" href="<?php echo $dir ?>items"><?php echo langs('ITEM');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" data-link-name="posts" href="<?php echo $dir ?>posts/"><?php echo lang('POSTS');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" data-link-name="users" href="<?php echo $dir ?>users/"><?php echo langs('USER');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" data-link-name="costumers" href="<?php echo $dir ?>costumers"><?php echo langs('COSTUMER');?></a>
        </li>
      </ul>
      <ul class="navbar-nav py-2 ms-auto">
        <li class="nav-item dropdown">
        <a class="nav-link fw-semibold dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $profileName;?>
        </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item p-2" href="<?php echo $dir ?>users/?do=info"><?php echo lang('MY PRO'); ?></a></li>
            <li><a class="dropdown-item p-2" href="<?php echo $dir ?>users/?do=edit"><?php echo lang('EDIT_PROFILE'); ?></a></li>
            <li><a class="dropdown-item p-2" href="#"><?php echo lang('SETTINGS'); ?></a></li>
            <li><a class="dropdown-item p-2 pb-lg-3" href="<?php echo $dir ?>logout.php"><?php echo lang('LOGOUT'); ?></a></li>
          </ul>
        </li>
      </ul>
      
    </div>
    
  </div>
</nav>