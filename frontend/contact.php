<?php
include_once 'header.php';
// require './PHPMailer/inquiryMailer.php';
session_start();
error_reporting(0);
?>




<div id="contact-page">

    <div class="page-content-wrapper">
        <div id="contactpage-hdr">

            <h1><span>Contact</span> us</h1>

        </div>
        <div class="contactpage">

            <div id="alert-area">

                <!--alert messages start-->
                <?php echo $alert; ?>
                <!--alert messages end-->
            </div>


            <form id="contact-us-form" method="post" action="">

                <div id="contact-us-hdr">
                    <h3>Got an <span>inquiry?</span></h3>
                    <p>Let us hear from you! <br>
                        Send us a <span>message</span> and we can figure out how to make it <span>workout</span> for you!</p>


                </div>

                <div class="inputBox-container">
                    <div class="inputBox">
                        <input name="name" type="text" minlength="5" maxlength="40" placeholder="" required>
                        <span>Your Name</span>
                    </div>

                    <div class="inputBox">
                        <input name="email" type="email" placeholder="" required>
                        <span>Email</span>
                    </div>

                    <div class="inputBox">
                        <input name="phone" type="tel" required>
                        <span>Phone Number [ eg. 260123456789]</span>
                    </div>


                    <div class="inputBox">
                        <input name="subject" type="text" placeholder="" required>
                        <span>Subject</span>
                    </div>

                    <div class="inputBox" id="message-body">
                        <textarea name="message" placeholder="" rows="5" id="" maxlength="1000" required></textarea>
                        <span>Type your message here...</span>
                    </div>
                </div>


                <div class="buttonz">

                    <button type="submit" name="submit" id="submit-btn"><span>Send Message</span></button>

                    <button type="reset" name="reset" id="reset-btn"><span>Clear Form</span></button>
                </div>



                <button class="open-privacy-policy" onclick="openPrivacyPolicy()">
                    <p>We respect your privacy and don't sell your information to third parties. <span> Read our Privacy Policy</span></p>
                </button>
            </form>


            <div class="contact-info">



                <div class="contactitems">
                    <i class="far fa-clock"></i>
                    <h4>Business hours</h4>

                    <p>
                        05hrs to 20hrs [Weekdays]<br>
                        07hrs to 16hrs [Weekends & Holidays]<br>

                    </p>


                </div>



                <div class="contactitems">
                    <i class="fas fa-phone-volume"></i>
                    <h4>Call us</h4>

                    <a href="tel:#">+260 123 456789</a> <br>


                    <a href="tel:#">+260 123 456789</a>



                </div>


                <div class="contactitems">
                    <i class="fas fa-envelope"></i>

                    <h4>Email Us</h4>

                    <a href="Contact.php">
                        <p>info@unideahub.org</p>
                    </a>



                </div>

                <div class="contactitems">
                    <i class="fa fa-comments"></i>

                    <h4>Follow our Social Community</h4>

                    <div id="contactUs-icons">


                        <a href="#" class="fab fa-instagram fa-2x">

                        </a>
                        <a href="#" class="fab fa-facebook fa-2x">

                        </a>
                        <a href="#" class="fab fa-whatsapp fa-2x">

                        </a>

                    </div>



                </div>



            </div>

            <div class="location">
                <i class="fa-solid fa-diamond-turn-right"></i>
                <h4>Pay us a visit</h4>


                <p> The University Idea Hub |<span>+ 260 123 456789</span> | C<span>3</span>, Platinum street, Leopards Hill Rd
                    Lusaka.
                </p>
                <div class="google-pin">

                    <iframe style="filter: invert(90%) hue-rotate(180deg)" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15384.841864209553!2d28.277830123645963!3d-15.419187878269145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1940f350abae6451%3A0x95dacb9f087c1fe7!2sZambia%20Centre%20for%20Accountancy%20Studies%20(ZCAS)!5e0!3m2!1sen!2szm!4v1731343291894!5m2!1sen!2szm" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

            </div>






        </div>



        <div class="privacy-policy-container" id="privacy-policy-container">




            <a href="https://www.privacypolicygenerator.info/live.php?token=ByDaGPG2WJwrwcRhA3rCQk81SB1wr0IK">
                <div class="privacy-policy-body"><button class="close-privacy-policy" id="close" onclick="closePrivacyPolicy()">Close Privacy</button>
            </a>


        </div>
    </div>

</div>






</div>
</div>





<?php
include_once 'footer.php';
?>