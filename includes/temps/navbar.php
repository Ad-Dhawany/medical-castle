<nav id = "top-navbar"class="navbar navbar-expand-md navbar-costum">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $dir ?>"><img src="<?php echo BASIC_ADDRES. "/media/site_logo/navLogo_mini.png"?>" alt="logo"></a>
    <input type="hidden" id="is-registred" class="d-none" value="<?php echo ($isLoggedIn) ? 1 : 0 ?>">
  <!-- start notif -->
   <?php if($isLoggedIn){?>
    <div id="notif-main-container" class="position-relative order-md-last ms-auto">
        <div class="notif-icon" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span id="unreads-count-badge" class="unreads-count badge rounded-pill bg-danger"></span>
        </div>
        <ul class="notifications dropdown-menu" id="notificationsListCont">
          <h5 class="notifcations-title"><?php echo lang("NOTIFICATIONS") ?> - <span id="notif-count" class="unreads-count" title="<?php echo lang("UNR NOT") ?>"></span></h5>
          <div id="notificationsList">
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
     <?php }?>
      <!-- end notif -->
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav py-2">
        <li class="nav-item">
         <a class="nav-link fw-semibold"  data-link-name="" href="<?php echo $dir ?>"><?php echo lang('HOME');?></a>
        </li>
        <li class="nav-item" title="<?php echo $unregistredsArray['title'] ?>">
          <a class="nav-link fw-semibold <?php echo $unregistredsArray['disabled-class'] ?>" data-link-name="posts" href="<?php echo $dir ?>news/" <?php echo $unregistredsArray['disabled-attr'] ?>><?php echo lang('NEWS');?></a>
        </li>
        <li class="nav-item" title="<?php echo $unregistredsArray['title'] ?>">
          <a class="nav-link fw-semibold <?php echo $unregistredsArray['disabled-class'] ?>" data-link-name="orders" href="<?php echo $dir ?>orders/" <?php echo $unregistredsArray['disabled-attr'] ?>><?php echo langs('ORDER');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" data-link-name="contact" href="<?php echo $dir ?>contact/"><?php echo lang('CON US');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" data-link-name="about" href="<?php echo $dir ?>about/"><?php echo lang('ABOUT');?></a>
        </li>
      </ul>
      <?php if($isLoggedIn){?>
      <ul class="navbar-nav py-2 ms-auto">
        <li class="nav-item dropdown">
        <a class="nav-link fw-semibold dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $profileName;?>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item p-2" href="<?php echo $dir ?>profile/"><?php echo lang('MY PRO'); ?></a></li>
              <li><a class="dropdown-item p-2" href="<?php echo $dir ?>profile/?do=edit"><?php echo lang('EDIT_PROFILE'); ?></a></li>
              <li><a class="dropdown-item p-2" href="#"><?php echo lang('SETTINGS'); ?></a></li>
              <li><a class="dropdown-item p-2 pb-lg-3" href="<?php echo $dir ?>logout.php"><?php echo lang('LOGOUT'); ?></a></li>
            </ul>
          </li>
        </ul>
        <?php }?>
      <ul class='navbar-nav <?php echo (!$isLoggedIn)? "ms-auto" : ""?>'>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link fw-semibold dropdown-toggle" id="langaugesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-globe fs-4"></i> <span class=""><?php echo $langArray['dropdown'] ?></span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="langaugesDropdownMenu">
            <li><a href="<?php echo BASIC_ADDRES?>/lang.php?lang=en" class="dropdown-item">English</a></li>
            <li><a href="<?php echo BASIC_ADDRES?>/lang.php?lang=ar" class="dropdown-item">العربية</a></li>
          </ul>
        </li>
      </ul>
    </div>
    
  </div>
</nav>