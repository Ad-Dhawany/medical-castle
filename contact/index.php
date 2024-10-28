<?php
ob_start();
session_start();
$pageTitle = "Contact Us";
$dir = "../";
$isLoggedIn = false;
if (isset($_SESSION['username'], $_SESSION['userID'], $_SESSION['groupID']) && $_SESSION['groupID'] > -1 && $_SESSION['groupID'] < 4) {
    $isLoggedIn = true;
}
include $dir . "init.php";
$whatsapp = setting::getSpecificSetting("companyWhatsappNumber")["value"];
$facebook = setting::getSpecificSetting("facebookPageLink")["value"];
$email = setting::getSpecificSetting("emailAddress")["value"];
?>
<main class="" dir="ltr">
    <section class="contact py-5 bg-light" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>Get in touch</h4>
                    <hr>
                </div>
                <div class="col-md-6">
                    <div class="address">

                        <h5>Address:</h5>
                        <!-- <ul class="list-unstyled">
                            <li> T-Mobile Customer Relations</li>
                            <li> PO Box 37380</li>
                            <li> Albuquerque, NM 87176-7380</li>
                        </ul> -->
                        <address class="">92 Main-East Street, Brak Wadi Al-Shattie, Libya</address>
                    </div>
                    <div class="email">
                        <h5>Email:</h5>
                        <ul class="list-unstyled">
                            <li> support@medicalcastle.ly</li>
                            <li> medicinecastle.ly@gmail.com</li>
                        </ul>
                    </div>
                    <div class="phone">
                        <h5>Phone:</h5>
                        <ul class="list-unstyled">
                            <li> +218 92-5235556</li>
                        </ul>
                    </div>
                    <hr>
                    <div class="social">
                        <ul class="list-inline list-unstyled nav align-items-center">
                            <li class="ms-3"><a class="footer-element fs-5" target="blank" href="https://wa.me/<?php echo $whatsapp ?>"><i class="fa-brands fa-whatsapp"></i></a></li>
                            <li class="ms-3"><a class="footer-element fs-5" target="blank" href="mailto:<?php echo $email ?>"><i class="fas fa-envelope"></i></a></li>
                            <li class="ms-3"><a class="footer-element fs-5" target="blank" href="<?php echo $facebook ?>"><i class="fa-brands fa-facebook-square"></i></a></li>
                        </ul>
                    </div>
                    <hr>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="row my-1">
                                    <div class="form-group col-md-6">
                                        <input type="text" id="fullname" name="Full Name" placeholder="Full Name" class="form-control" required="required" data-no-asterisk="1">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Email" required="required" data-no-asterisk="1">
                                    </div>
                                </div>
                                <div class="row my-1">
                                    <div class="form-group col-md-6">
                                        <input type="text" id="phone" name="phone" placeholder="Phone Number" class="form-control" required="required" required="required" data-no-asterisk="1">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" id="organization" name="organization" placeholder="Organization or Pharmacy" class="form-control" required="required" data-no-asterisk="1">
                                    </div>
                                    <div class="form-group col-md-12 my-1">
                                        <textarea id="comment" name="comment" cols="40" rows="5" placeholder="Your Message" class="form-control" required="required" data-no-asterisk="1"></textarea>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-12 col-md-4 col-lg-2">
                                        <button type="button" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
</main>
<?php
include $dir . $tempsP . "footer.php";
ob_end_flush();
