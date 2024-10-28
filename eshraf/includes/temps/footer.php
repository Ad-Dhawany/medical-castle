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
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <div class="col-md-4 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
        <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
      </a>
      <span class="mb-3 mb-md-0 text-muted">© 2023 Medical Castle Company, Inc</span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex mx-2">
      <li class="ms-3"><a class="text-muted fs-5" target="blank" href="https://wa.me/<?php echo $whatsapp ?>"><i class="fa-brands fa-whatsapp"></i></a></li>
      <li class="ms-3"><a class="text-muted fs-5" target="blank" href="mailto:<?php echo $email ?>"><i class="fas fa-envelope"></i></a></li>
      <li class="ms-3"><a class="text-muted fs-5" target="blank" href="<?php echo $facebook ?>"><i class="fa-brands fa-facebook-square"></i></a></li>
    </ul>
  </footer>
<!--  -->
<script type="text/javascript" src="<?php echo $jsP; ?>jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>fontawesome-free-6.2.0-web.min.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>fontawesome-free-6.2.0-web-regular.min.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>popper.min.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>bootstrap.min.js"></script>
<!-- <script src="<?php echo $jsP; ?>bootstrap.esm.min.js"></script> -->
<script type="text/javascript" src="<?php echo $jsP; ?>tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>tinymce/tinymce-jquery.min.js" referrerpolicy="origin"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>langs.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>main.js"></script>
<script type="text/javascript" src="<?php echo $jsP; ?>notifications.js"></script>
</body>
</html>