<?php
  $firstChars = substr($_SESSION['fullname'],0,1) ;
  $decider = strpos($_SESSION['fullname']," ",strpos($_SESSION['fullname']," ")+1);
  $lastName = substr($_SESSION['fullname'],strpos($_SESSION['fullname']," ")) ;
  if($decider > 0){
    $secondChar = substr($_SESSION['fullname'],strpos($_SESSION['fullname']," "),2) ;
    $firstChars = "$firstChars. $secondChar" ;
    $lastName = substr($_SESSION['fullname'], $decider);
  }
  $profileName = "$firstChars. $lastName";
?>
<nav class="navbar navbar-dark navbar-expand-lg">
  <div class="container">
  <!-- <a class="nav-link fw-semibold navbar-brand active" aria-current="page" href="#"><?php echo lang('HOME');?></a> -->
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
         <a class="nav-link fw-semibold active" aria-current="page" href="<?php echo $dir ?>dashboard"><?php echo lang('HOME');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" href="<?php echo $dir ?>categories"><?php echo lang('CATEGORIES');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" href="#"><?php echo langs('ITEM');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" href="<?php echo $dir ?>members"><?php echo langs('MEMBER');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold" href="<?php echo $dir ?>costumers"><?php echo langs('COSTUMER');?></a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
        <a class="nav-link fw-semibold dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $profileName;?>
        </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item p-2" href="<?php echo $dir ?>members/?do=edit"><?php echo lang('EDIT_PROFILE'); ?></a></li>
            <li><a class="dropdown-item p-2" href="#"><?php echo lang('SETTINGS'); ?></a></li>
            <li><a class="dropdown-item p-2 pb-lg-3" href="<?php echo $dir ?>logout.php"><?php echo lang('LOGOUT'); ?></a></li>
          </ul>
        </li>
        </ul>
    </div>
  </div>
</nav>