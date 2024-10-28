<!--  -->
<div id="all-notifications-cont" class="all-notifications-cont" data-notifications-control="allNotificaitons" style="display: none;">
</div>
<!--  -->
<!--  -->
  <?php
    $whatsapp = setting::getSpecificSetting("companyWhatsappNumber")["value"];
    $facebook = setting::getSpecificSetting("facebookPageLink")["value"];
    $email = setting::getSpecificSetting("emailAddress")["value"];
  ?>
  <footer dir="ltr" class="main-footer d-flex flex-wrap justify-content-between align-items-center p-3 mt-4 border-top">
    <p class="col-6 col-md-4 mb-0 ps-2 footer-element">© <?php echo date("Y") ?> Medical Castle Company, Ltd</p>
    
    <ul class="nav col-12 col-md-4 order-first order-md-0 justify-content-center" dir="auto">
      <li class="nav-item"><a href="<?php echo $dir ?>" class="nav-link px-2 footer-element"><?php echo lang("HOME") ?></a></li>
      <li class="nav-item"><a href="<?php echo $dir. "contact/" ?>" class="nav-link px-2 footer-element"><?php echo lang("CON US") ?></a></li>
      <li class="nav-item"><a href="<?php echo $dir. "profile/" ?>" class="nav-link px-2 footer-element"><?php echo lang("PROFILE") ?></a></li>
      <li class="nav-item"><a href="<?php echo $dir. "terms/" ?>" class="nav-link px-2 footer-element"><?php echo lang("TER AND CON") ?></a></li>
      <li class="nav-item"><a href="<?php echo $dir. "policy" ?>" class="nav-link px-2 footer-element"><?php echo lang("PRI POL") ?></a></li>
      <li class="nav-item"><a href="<?php echo $dir. "about" ?>" class="nav-link px-2 footer-element"><?php echo lang("ABOUT") ?></a></li>
    </ul>

    <ul class="nav col-6 col-md-4 justify-content-end list-unstyled d-flex align-items-center mb-3 mb-md-0 me-md-auto">
      <li class="ms-3"><a class="footer-element fs-5" target="blank" href="https://wa.me/<?php echo $whatsapp ?>"><i class="fa-brands fa-whatsapp"></i></a></li>
      <li class="ms-3"><a class="footer-element fs-5" target="blank" href="mailto:<?php echo $email ?>"><i class="fas fa-envelope"></i></a></li>
      <li class="ms-3"><a class="footer-element fs-5" target="blank" href="<?php echo $facebook ?>"><i class="fa-brands fa-facebook-square"></i></a></li>
    </ul>

  </footer>
  <!-- <footer class="main-footer d-flex flex-wrap justify-content-between align-items-center px-4 p-3 mt-4 border-top">
    <div class="col-md-4 d-flex align-items-center">
      <span dir="ltr" class="mb-3 mb-md-0 footer-element">© <?php echo date("Y") ?> Medical Castle Company, Ltd</span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex mx-2">
      <li class="ms-3"><a class="footer-element fs-5" target="blank" href="https://wa.me/<?php echo $whatsapp ?>"><i class="fa-brands fa-whatsapp"></i></a></li>
      <li class="ms-3"><a class="footer-element fs-5" target="blank" href="mailto:<?php echo $email ?>"><i class="fas fa-envelope"></i></a></li>
      <li class="ms-3"><a class="footer-element fs-5" target="blank" href="<?php echo $facebook ?>"><i class="fa-brands fa-facebook-square"></i></a></li>
    </ul>
  </footer> -->
<!--  -->
<script src="<?php echo $jsP; ?>jquery-3.6.0.min.js"></script>
<!-- <script src="<?php echo $jsP; ?>fontawesome-free-6.2.1-web.min.js"></script> -->
<script src="<?php echo $jsP; ?>fontawesome-free-6.4.0-web.min.js"></script>
<script src="<?php echo $jsP; ?>popper.min.js"></script>
<script src="<?php echo $jsP; ?>bootstrap.min.js"></script>
<!-- <script src="<?php echo $jsP; ?>bootstrap.esm.min.js"></script> -->
<script type="text/javascript" src="<?php echo $jsP; ?>langs.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>main.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>notifications.js"></script>
</body>
</html>