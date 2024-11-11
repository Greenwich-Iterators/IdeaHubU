<!-- For the scroll button to top -->


<a href="#" class="to-top">
    <div class="fas fa-chevron-up"></div>
</a>




</div>

<!-- Script to Make the Navibar on Desktops float at top -->
<script>
    window.onscroll = function() {
        myFunction()
    };

    var navigationbar = document.getElementById("sidebar-nav");
    var sticky = navigationbar.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= sticky) {
            navigationbar.classList.add("sticky")
        } else {
            navigationbar.classList.remove("sticky");
        }
    }
</script>


<!--Script To close Side Menu on "Click outside"-->

<script type="text/javascript">
    const toggler = document.getElementById('toggleSwitch');
    const sidebar = document.getElementById('sidebar-nav');
    const body = document.querySelector("body");


    // This function below triggers the On-click outside the Navbar to close it
    document.onclick = function(e) {


        if (e.target.id !== 'sidebar' && e.target.id !== 'toggleSwitch' && e.target.id !== 'submenuBtn') {
            toggler.classList.remove('active')
            sidebar.classList.remove('active')
            body.style.position = "relative";
        }


    }

    // This function Allows for the Navbar to ONLY close when the Open or Close Button is clicked on

    toggler.onclick = function() {
        toggler.classList.toggle('active')
        sidebar.classList.toggle('active')
        body.style.position = "fixed";
    }
</script>


<!--Script for the Payments Form--->
<script type="text/javascript">
    function openForm() {
        document.getElementById("modal-container").classList.add('show');
    }

    function closeForm() {
        window.location.href = 'trainers.php';
    }
</script>

<!--Script for Gallery Lightbox-->

<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'disableScrolling': true
    })
</script>

<!-- Script for Back to Top Button -->
<script src="js/toTop-button.js">
</script>


<!--Function for clicking on the video slider-->

<!-- This one below directly is for the Gallery -->

<script type="text/javascript">
    function videoUrl(videos) {
        document.getElementById('video-content-slider').src = videos;
        document.getElementsByClassName('video-slider-text');
    }
</script>

<!-- 
<script type="text/javascript">
    function videoUrl(vids) {
        document.getElementById('video-slider').src = vids;
        document.getElementById('video-slider-text');
    }
</script> -->

<!-- Script for the privacy modal-->

<script>
    function openPrivacyPolicy() {
        document.getElementById("privacy-policy-container").classList.add('show');

    }

    function closePrivacyPolicy() {
        document.getElementById("privacy-policy-container").classList.remove('show');
    }
</script>

<!-- Script for the Cookies Modal-->

<script>
    let cookieModal = document.querySelector(".cookie-consent-modal-container");
    let acceptCookieBtn = document.querySelector(".btn#accept");
    let cancelCookieBtn = document.querySelector(".btn#cancel");

    acceptCookieBtn.addEventListener("click", function() {
        cookieModal.classList.remove("show")
        localStorage.setItem("cookieAccepted", "yes")
    })

    cancelCookieBtn.addEventListener("click", function() {
        cookieModal.classList.remove("show")
    })

    setTimeout(function() {
        let cookieAccepted = localStorage.getItem("cookieAccepted")
        if (cookieAccepted != "yes") {
            cookieModal.classList.add("show")
        }

    }, 2000);
</script>



<script>
    document.getElementById("active-page").innerHTML =
        "The full URL of this page is:<br>" + window.location.pathname;
</script>

<!-- Script for the Submenu -->

<script>
    let submenuNav = document.querySelector("#login-sub");
    let isShow = true;

    function showHideSubmenu() {
        if (isShow) {
            submenuNav.style.display = "flex";
            isShow = false;
        } else {
            submenuNav.style.display = "none";
            isShow = true;
        }
    }
</script>




</body>
<footer id="footer">
    <div class="footer-wrapper">

        <div class="all-rights">

            <div class="reserved">
                <p> <span>The University Idea Hub</span> Copyright 2024 <br> All Rights Reserved
                </p>

            </div>




        </div>



        <div id='footer-icons'>
            <p>Our Social links</p>

            <ul>


                <li><a href="https://www.instagram.com/amaka_gym/"> <img src="./images/icons/instagram.png" alt=""></a></li>

                <li><a href="https://www.facebook.com/CFZambia/"> <img src="./images/icons/facebook.png" alt=""></a></li>


                <li><a href="https://api.whatsapp.com/send?phone=260976082868"> <img src="./images/icons/whatsapp.png" alt=""></a>
                </li>


            </ul>
        </div>

        <div id="footer-contacts">
            The University Idea Hub |<span>+ 260 123 456789</span> | C<span>3</span>, Platinum street, Leopards Hill Rd
            Lusaka.
        </div>


        <div class="useful-links">
            <p> Useful links</p>
            <ul>
                <li><a href="login.php">Register</a> </li>
                <li><a href="signup.php">Sign-Up</a> </li>
                <li><a href="concepts.php">Concept hub</a> </li>
                <li><a href="contact.php"> Contact Us </a></li>
                <li><a href="information.php#pricing">FAQs</a></li>
            </ul>
        </div>

        <div class="web-designer">
            <a href="#">Website
                by <span>2024 Group-2 Iterators</span>
            </a>
        </div>
    </div>

</footer>

</html>