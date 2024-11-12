<?php
include_once 'header.php';
session_start();

// include 'config.php';
// require 'subscribe.php';


error_reporting(1)

?>



<div id="home-page">


	<div class="intro-container">
		<div class="slideshow">
			<div>

				<img src="./images/dl.beatsnoop.com-3000-krLozN7WF1.jpg" alt="">
			</div>

			<div>

				<img src="./images/dl.beatsnoop.com-3000-QkMV8fRcyY.jpg" alt="">
			</div>
			<div>

				<img src="./images/dl.beatsnoop.com-3000-JBGzcL9KiV.jpg" alt="">
			</div>
			<div>

				<img src="./images/dl.beatsnoop.com-3000-AYMWHz3ro9.jpg" alt="">
			</div>
			<div>

				<img src="./images/dl.beatsnoop.com-3000-VtYI4kBE9R.jpg" alt="">
			</div>

		</div>
		<div id='quick-signup'>
			<button class="sign-in"><a href="signup.php">Register</a></button>|

			<button class="log-in"><a href="login.php">Sign In</a></button>

		</div>

	</div>


	<div class="main-welcome-section">
		<h1>Welcome to the University Idea Hub <span id="copyright">&copy;</span></h1>

		<p>At the University Idea Hub we believe that every faculty has a unique voice and a story to tell. Our Idea Hub is a celebration of creativity, diversity, and innovation within our university community. This platform is designed to empower faculty to contribute their ideas, articles, artwork, and experiences, ensuring that our Idea Hub reflects the vibrant tapestry of our campus life.</p>

		<h4>Inspiring Ideas, <span>Empowering Minds!</span></h4>

	</div>

	<div class="main-intro">


		<div class="intro-section" id="mission">
			<h2>The Mission</h2>

			<p class="intro-message">
				Our mission is to provide an inclusive space where faculty can share their thoughts, insights, and creative works. We strive to foster a collaborative environment that encourages self-expression, critical thinking, and engagement among faculty from all disciplines. By amplifying faculty voices, we aim to create an Idea Hub that inspires, informs, and entertains.
			</p>

			<button>
				<a href="./about.php#our-mission">Learn More</a>
			</button>



		</div>

		<div class="images" id="mission">
			<img src="./images/dl.beatsnoop.com-3000-95upnm8qLk.jpg" alt="">
		</div>


		<div class="intro-section" id="vision">
			<h2>The Vision</h2>

			<p class="intro-message">
				We envision a Idea Hub that not only showcases faculty contributions but also serves as a catalyst for dialogue and connection within our university community. We aim to cultivate a culture of creativity and collaboration, where every department feels valued and empowered to share their perspectives. Our vision is to create a platform that celebrates the richness of our university experience and encourages continuous growth and exploration.


			</p>

			<button>
				<a href="./about.php#our-vision">Learn More</a>
			</button>


		</div>

		<div class="images" id="vision">
			<img src="./images/dl.beatsnoop.com-3000-k7zefIyEB5.jpg" alt="">
		</div>

		<div class="intro-section" id="submission-guidelines">
			<h2>Submission Guidelines</h2>
			<p class="intro-message">Want to submit an idea? Here's how to:
				<br>


			</p>
			<ul>
				<li><strong>Format:</strong> Please submit your contributions as a PDF document. </li>
				<li>
					<strong>Word Limit:</strong> Articles should be between 500-1,500 words; poetry pieces should be no more than 40 lines; artwork should be accompanied by a brief description.
				</li>
				<li>
					<strong>Deadline:</strong> All submissions for the upcoming issue will be shared way ahead of time.
				</li>
			</ul>

			<button>
				<a href="./submissions.php#main-submission-guidelines">Learn More</a>
			</button>

		</div>

		<div class="images" id="submission-guidelines">
			<img src="./images/low_quality-8FL4XoYN9i.jpg" alt="">
		</div>



	</div>

	<div class="intro-section homepage_ideas">

		<div class="intro-section ideas_wrapper">

			<div id="ideas_img">
				<img src="./images/dl.beatsnoop.com-3000-oEnUSq0Zlw.jpg" alt="">
			</div>

			<div class="idea-tiles" id="most-popular-idea">

				<h2>Most Popular Idea</h2>
				<p class="intro-message">
					What Our Contributors Say:

				<ul>
					<li><em>“Implement Flexible Working Hours.”</em> </li>

				</ul>


				</p>
			</div>
			<div class="idea-tiles" id="most-viewed-idea">
				<h2>Most Viewed Ideas</h2>
				<p class="intro-message">


				<ul>
					<li>Free Library Coffee Corners</em></li>
					<li><em>Online Mental Health Support </em></li>
					<li><em>Paperless Administration System </em></li>
				</ul>


				</p>
			</div>

			<div class="idea-tiles" id="latest-ideas">

				<h2>Latest Ideas</h2>
				<p class="intro-message">


				<ul>
					<li><em>Interactive Virtual Campus Tours</em></li>
					<li><em>AI-Powered Course Suggestions</em></li>
					<li><em>Department Collaboration Workshops</em></li>

				</ul>


				</p>
			</div>

			<div class="idea-tiles" id="latest-comments">

				<h2>Latest Comments</h2>
				<p class="intro-message">
					What Our Contributors Say:

				<ul>
					<li><em>"Flexible hours are a great idea!" </em></li>
					<li><em>"Mental health support is much needed."</em></li>
					<li><em>"Let's make it paperless!" </em></li>

				</ul>


				</p>
			</div>



		</div>

		<button>
			<a href="#">Learn More</a>
		</button>

	</div>

	<div class="showcase">
		<h1> <span>Discover </span>New <span>ways</span> to <span>share </span>ideas</h1>
	</div>








	<!-- <div class="video-banner">

		<video autoplay loop muted class="foreverLoop">
			<source src="./videos/banner.mp4" type="video/mp4">
			Your browser does not support the video tag.
		</video>
		<div class="video-banner-text">

			<p>Immerse yourself in a deep knowledge base, with endless possibilities.</p>
		</div>


	</div>

 -->


	<div class="motivations">
		<h3>“We always know more than we can say, and we will always say more than we can write down.” </h3>
		<p> - Dave Snowden
		</p>

		<div id="photo">

			<img src="./images/dave-snowden.jpg" alt="">
		</div>


	</div>

	<!-- THE CONTACT/SUBSCRIBE SECTION-->
	<div id="home-contact-section">

		<p>sign up for our Newsletter today!</p>


		<?php
		if (isset($message)) {
			foreach ($message as $message) {
				echo '<div class ="message">' . $message . '</div>';
			}
		}
		?>



		<form method="post" id="newsletter-form">
			<label for="newsletter-email">
				<p>Please provide Your email below</p>
			</label>
			<input type="text" name="newsletter-email" id="newsletter" placeholder="" required>

			<button id="subscribe-btn" name="subscribe-submit" required>Subscribe
			</button>

			<div class="disclaimer">Your data is kept private and never shared with any third parties. </div>


		</form>




		<!------- THE CONTACT US SECTION ON HOME PAGE------------------------>

		<div id="contactUs-icons">



			<a href="contact.php#mainform" class="fa fa-envelope fa-2x">

			</a>

			<a href="https://www.instagram.com/amaka_gym/" class="fab fa-instagram fa-2x">

			</a>
			<a href="https://m.facebook.com/CFZambia/" class="fab fa-facebook fa-2x">

			</a>
			<a href="https://api.whatsapp.com/send?phone=260976082868" class="fab fa-whatsapp fa-2x">

			</a>

		</div>



	</div>

	<!-- THE COOKIES MODAL------->

	<div class="cookie-consent-modal-container">

		<div class="cookie-content">
			<h4>This website uses Cookies</h4>

			<p>We use cookies for the following purposes: <br>

				<span>Essential Cookies:</span> These cookies are necessary for the website to function properly. They enable basic features such as page navigation and access to secure areas of the website. <br>
				<span>Analytical/Performance Cookies:</span> These cookies allow us to analyze website traffic and track user behavior to improve our website's performance and user experience. <br>
				<span>Functionality Cookies:</span> These cookies enable enhanced functionality and personalization, such as remembering your preferences and settings. <br>
				<span>Advertising/Targeting Cookies:</span> These cookies are used to deliver relevant advertisements to you based on your interests and browsing behavior.
			</p>

			<div class="cookie-buttons-wrapper">

				<button class="btn" id="accept"><span>Accept</span></button>

				<button class="btn" id="cancel"><span>Cancel</span></button>

			</div>
		</div>


	</div>

</div>
</div>

<?php

include_once 'footer.php';
?>