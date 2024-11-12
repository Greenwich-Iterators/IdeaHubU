<?php
include_once 'header.php';
session_start();

error_reporting(0);
if (!isset($_COOKIE['auth_token'])) {
	header("Location: login.php");
	exit();
}

$token = $_COOKIE['auth_token'];
$verifytoken_url = 'http://localhost:9000/api/user/verifytoken';
$options = [
	'http' => [
		'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
		'method' => 'GET'
	]
];
$context = stream_context_create($options);
$result = @file_get_contents($verifytoken_url, false, $context);

if ($result === FALSE) {
	header("Location: login.php");
	exit();
}

$response = json_decode($result, true);

if (!$response['valid']) {
	header("Location: login.php");
	exit();
}


// Idea Submission
$message = '';
$vmessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
	// Process form submission
	$ideaTitle = htmlspecialchars($_POST["title"]);
	$ideaDescription = htmlspecialchars($_POST["description"]);
	$ideaCategory = htmlspecialchars($_POST["category"]);
	$anonymousPost = isset($_POST["anonymousPost"]) ? true : false;
	$message = "Form submitted successfully! Idea Title: $ideaTitle";

	$uploadOk = 1;

	// Prepare data for POST request
	$data = [
		'ideaTitle' => $ideaTitle,
		'ideaDescription' => $ideaDescription,
		'userId' => $response['userId'],
		'anonymousPost' => $anonymousPost,
		'categoryId' => $ideaCategory,
		'filename' => ''
	];

	// Handle file upload if a file was selected
	if (!empty($_FILES["fileToUpload"]["name"])) {
		$target_dir = "../uploads/";
		$uploadOk = 1;
		$fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

		if ($fileType !== "pdf") {
			$vmessage .= " However, only PDF files are allowed for upload.";
			$uploadOk = 0;
		}

		if ($_FILES["fileToUpload"]["size"] > 4000000) {
			$vmessage .= " The selected file is too large. Maximum size is 4MB.";
			$uploadOk = 0;
		}

		if ($uploadOk == 1) {
			$fileHash = hash_file('md5', $_FILES["fileToUpload"]["tmp_name"]);
			$target_file = $target_dir . $fileHash . ".pdf";
			error_log(print_r($fileHash));

			if (file_exists($target_file)) {
				$vmessage .= " A file with the same content already exists.";
			} else {
				$existingFiles = glob($target_dir . "*.pdf");
				foreach ($existingFiles as $file) {
					if (hash_file('md5', $file) === $fileHash) {
						$vmessage .= " A file with the same content already exists.";
						$uploadOk = 0;
						break;
					}
				}

				if ($uploadOk == 1) {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
						$vmessage .= " The file has been uploaded and saved as: " . htmlspecialchars(basename($target_file));
					} else {
						$vmessage .= " Sorry, there was an error uploading your file.";
						$uploadOk = 0;
					}
				}
			}
			$data["filename"] = $fileHash;
		}
	}


	// Make POST request
	$ideaResult = @file_get_contents(
		"http://localhost:9000/api/idea/add",
		false,
		stream_context_create([
			'http' => [
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
				'method' => 'POST',
				'content' => json_encode($data)
			]
		])
	);

	$ideaResponse = json_decode($ideaResult, true);
	if ($ideaResponse && isset($ideaResponse['success']) && $ideaResponse['success']) {
		$message .= " Your idea has been successfully submitted.";
	} else {
		$message .= " There was an error submitting your idea. Please try again.";
	}
}

// Get Categories

$categoriesResult = @file_get_contents(
	"http://localhost:9000/api/category/all",
	false,
	stream_context_create([
		'http' => [
			'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
			'method' => 'GET'
		]
	])
);

$categoriesResponse = json_decode($categoriesResult, true);

if ($categoriesResponse && isset($categoriesResponse['success']) && $categoriesResponse['success']) {
	$categories = $categoriesResponse['categories'];
}

?>

<div id="idea-page">
	<div class="idea-wrapper">



		<!-- The Submissions Guidelines Section -->
		<div id="main-submission-guidelines">
			<h1>Submission Guidelines</h1>
			<p>Want to submit an idea? Here's how to:
				<br>


			</p>
			<ul>
				<li><strong>Format:</strong> Please submit your contributions as a Word document or PDF. Artwork should
					be in high-resolution JPEG or PNG format.</li>

				<li>
					<strong>Word Limit:</strong> Articles should be between 500-1,500 words; poetry pieces should be no
					more than 40 lines; artwork should be accompanied by a brief description.
				</li>
				<li>
					<strong>Deadline:</strong> All submissions for the upcoming issue will be shared way ahead of time.
				</li>
				<li>
					<strong>Terms and Conditions:</strong> Before making any submission you will need to accept and
					agree to our Terms and Conditions. <br>
					You can view our Terms and Conditions by clicking on the below link.
				</li>
			</ul>
			<button id="termsAndConditions-btn" onclick=hideShowTerms()> <span> View our Terms and Conditions
					here</span></button>

		</div>


		<!-- The Submissions Form -->
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="idea-form" method="POST" enctype="multipart/form-data">
			<?php
			// ...
			
			// Display the error messages
			if (isset($message)) {
				foreach ($message as $msg) {
					echo '<div class="message">' . $msg . '</div>';
				}
			}

			// ...
			?>

			<h2>Submit your Idea</h2>

			<div>

			</div>
			<br><br>
			<label for=" title">Idea Title</label>
			<input type="text" id="title" name="title" required>
			<label for="category">Idea Category</label>
			<div>
				<select id="category" name="category">
					<?php
					if (isset($categories) && is_array($categories)) {
						foreach ($categories as $category) {
							echo '<option value="' . htmlspecialchars($category['_id']) . '">' . htmlspecialchars($category['name']) . '</option>';
						}
					} else {
						echo '<option value="">No categories available</option>';
					}
					?>
				</select>
			</div>
			<label for="description">Description</label>
			<textarea id="description" name="description" rows="4" required></textarea>
			<label for="fileToUpload">Optional: Select a PDF to upload (max 4MB):</label><br>
			<input type="file" name="fileToUpload" id="fileToUpload"><br><br>



			<br><br>

			<div class="terms-consent">
				<label>
					<input type="checkbox" id="acceptTerms">
					<p>I have read and accept the
						Terms and Conditions</p>

				</label>

			</div>
			<br>

			<button id="submitIdea" name="submit" type="submit">Submit Idea</button>


		</form>


		<!-The Terms and Conditions- -->


			<div id="terms-container" class="hide-Terms">
				<div class="terms-wrapper">
					<div class="terms-content">
						<h1>Terms and Conditions</h1>
						<p>Last updated: November 05, 2024</p>
						<p>Please read these terms and conditions carefully before using Our Service.</p>
						<h3>Interpretation and Definitions</h3>
						<h3>Interpretation</h3>
						<p>The words of which the initial letter is capitalized have meanings defined under the
							following conditions. The following definitions shall have the same meaning regardless of
							whether they appear in singular or in plural.</p>
						<p>The words of which the initial letter is capitalized have meanings defined under the
							following conditions. The following definitions shall have the same meaning regardless of
							whether they appear in singular or in plural.</p>
						<h3>Definitions</h3>
						<p>For the purposes of these Terms and Conditions:</p>
						<ul>
							<li>
								<p><strong>Affiliate</strong> means an entity that controls, is controlled by or is
									under common control with a party, where &quot;control&quot; means ownership of 50%
									or more of the shares, equity interest or other securities entitled to vote for
									election of directors or other managing authority.</p>
								<p><strong>Affiliate</strong> means an entity that controls, is controlled by or is
									under common control with a party, where &quot;control&quot; means ownership of 50%
									or more of the shares, equity interest or other securities entitled to vote for
									election of directors or other managing authority.</p>
							</li>
							<li>
								<p><strong>Country</strong> refers to: Zambia</p>
							</li>
							<li>
								<p><strong>Company</strong> (referred to as either &quot;the Company&quot;,
									&quot;We&quot;, &quot;Us&quot; or &quot;Our&quot; in this Agreement) refers to
									University, Lusaka.</p>
								<p><strong>Company</strong> (referred to as either &quot;the Company&quot;,
									&quot;We&quot;, &quot;Us&quot; or &quot;Our&quot; in this Agreement) refers to
									University, Lusaka.</p>
							</li>
							<li>
								<p><strong>Device</strong> means any device that can access the Service such as a
									computer, a cellphone or a digital tablet.</p>
								<p><strong>Device</strong> means any device that can access the Service such as a
									computer, a cellphone or a digital tablet.</p>
							</li>
							<li>
								<p><strong>Service</strong> refers to the Website.</p>
							</li>
							<li>
								<p><strong>Terms and Conditions</strong> (also referred as &quot;Terms&quot;) mean these
									Terms and Conditions that form the entire agreement between You and the Company
									regarding the use of the Service. This Terms and Conditions agreement has been
									created with the help of the <a
										href="https://www.termsfeed.com/terms-conditions-generator/"
										target="_blank">Terms and Conditions Generator</a>.</p>
								<p><strong>Terms and Conditions</strong> (also referred as &quot;Terms&quot;) mean these
									Terms and Conditions that form the entire agreement between You and the Company
									regarding the use of the Service. This Terms and Conditions agreement has been
									created with the help of the <a
										href="https://www.termsfeed.com/terms-conditions-generator/"
										target="_blank">Terms and Conditions Generator</a>.</p>
							</li>
							<li>
								<p><strong>Third-party Social Media Service</strong> means any services or content
									(including data, information, products or services) provided by a third-party that
									may be displayed, included or made available by the Service.</p>
								<p><strong>Third-party Social Media Service</strong> means any services or content
									(including data, information, products or services) provided by a third-party that
									may be displayed, included or made available by the Service.</p>
							</li>
							<li>
								<p><strong>Website</strong> refers to Concept Hub, accessible from [Concept Hub](Concept
									Hub)</p>
								<p><strong>Website</strong> refers to Concept Hub, accessible from [Concept Hub](Concept
									Hub)</p>
							</li>
							<li>
								<p><strong>You</strong> means the individual accessing or using the Service, or the
									company, or other legal entity on behalf of which such individual is accessing or
									using the Service, as applicable.</p>
								<p><strong>You</strong> means the individual accessing or using the Service, or the
									company, or other legal entity on behalf of which such individual is accessing or
									using the Service, as applicable.</p>
							</li>
						</ul>
						<h2>Acknowledgment</h2>
						<p>These are the Terms and Conditions governing the use of this Service and the agreement that
							operates between You and the Company. These Terms and Conditions set out the rights and
							obligations of all users regarding the use of the Service.</p>
						<p>Your access to and use of the Service is conditioned on Your acceptance of and compliance
							with these Terms and Conditions. These Terms and Conditions apply to all visitors, users and
							others who access or use the Service.</p>
						<p>By accessing or using the Service You agree to be bound by these Terms and Conditions. If You
							disagree with any part of these Terms and Conditions then You may not access the Service.
						</p>
						<p>You represent that you are over the age of 18. The Company does not permit those under 18 to
							use the Service.</p>
						<p>Your access to and use of the Service is also conditioned on Your acceptance of and
							compliance with the Privacy Policy of the Company. Our Privacy Policy describes Our policies
							and procedures on the collection, use and disclosure of Your personal information when You
							use the Application or the Website and tells You about Your privacy rights and how the law
							protects You. Please read Our Privacy Policy carefully before using Our Service.</p>
						<p>These are the Terms and Conditions governing the use of this Service and the agreement that
							operates between You and the Company. These Terms and Conditions set out the rights and
							obligations of all users regarding the use of the Service.</p>
						<p>Your access to and use of the Service is conditioned on Your acceptance of and compliance
							with these Terms and Conditions. These Terms and Conditions apply to all visitors, users and
							others who access or use the Service.</p>
						<p>By accessing or using the Service You agree to be bound by these Terms and Conditions. If You
							disagree with any part of these Terms and Conditions then You may not access the Service.
						</p>
						<p>You represent that you are over the age of 18. The Company does not permit those under 18 to
							use the Service.</p>
						<p>Your access to and use of the Service is also conditioned on Your acceptance of and
							compliance with the Privacy Policy of the Company. Our Privacy Policy describes Our policies
							and procedures on the collection, use and disclosure of Your personal information when You
							use the Application or the Website and tells You about Your privacy rights and how the law
							protects You. Please read Our Privacy Policy carefully before using Our Service.</p>
						<h2>Links to Other Websites</h2>
						<p>Our Service may contain links to third-party web sites or services that are not owned or
							controlled by the Company.</p>
						<p>The Company has no control over, and assumes no responsibility for, the content, privacy
							policies, or practices of any third party web sites or services. You further acknowledge and
							agree that the Company shall not be responsible or liable, directly or indirectly, for any
							damage or loss caused or alleged to be caused by or in connection with the use of or
							reliance on any such content, goods or services available on or through any such web sites
							or services.</p>
						<p>We strongly advise You to read the terms and conditions and privacy policies of any
							third-party web sites or services that You visit.</p>
						<p>Our Service may contain links to third-party web sites or services that are not owned or
							controlled by the Company.</p>
						<p>The Company has no control over, and assumes no responsibility for, the content, privacy
							policies, or practices of any third party web sites or services. You further acknowledge and
							agree that the Company shall not be responsible or liable, directly or indirectly, for any
							damage or loss caused or alleged to be caused by or in connection with the use of or
							reliance on any such content, goods or services available on or through any such web sites
							or services.</p>
						<p>We strongly advise You to read the terms and conditions and privacy policies of any
							third-party web sites or services that You visit.</p>
						<h2>Termination</h2>
						<p>We may terminate or suspend Your access immediately, without prior notice or liability, for
							any reason whatsoever, including without limitation if You breach these Terms and
							Conditions.</p>
						<p>We may terminate or suspend Your access immediately, without prior notice or liability, for
							any reason whatsoever, including without limitation if You breach these Terms and
							Conditions.</p>
						<p>Upon termination, Your right to use the Service will cease immediately.</p>
						<h2>Limitation of Liability</h2>
						<p>Notwithstanding any damages that You might incur, the entire liability of the Company and any
							of its suppliers under any provision of this Terms and Your exclusive remedy for all of the
							foregoing shall be limited to the amount actually paid by You through the Service or 100 USD
							if You haven't purchased anything through the Service.</p>
						<p>To the maximum extent permitted by applicable law, in no event shall the Company or its
							suppliers be liable for any special, incidental, indirect, or consequential damages
							whatsoever (including, but not limited to, damages for loss of profits, loss of data or
							other information, for business interruption, for personal injury, loss of privacy arising
							out of or in any way related to the use of or inability to use the Service, third-party
							software and/or third-party hardware used with the Service, or otherwise in connection with
							any provision of this Terms), even if the Company or any supplier has been advised of the
							possibility of such damages and even if the remedy fails of its essential purpose.</p>
						<p>Some states do not allow the exclusion of implied warranties or limitation of liability for
							incidental or consequential damages, which means that some of the above limitations may not
							apply. In these states, each party's liability will be limited to the greatest extent
							permitted by law.</p>
						<p>Notwithstanding any damages that You might incur, the entire liability of the Company and any
							of its suppliers under any provision of this Terms and Your exclusive remedy for all of the
							foregoing shall be limited to the amount actually paid by You through the Service or 100 USD
							if You haven't purchased anything through the Service.</p>
						<p>To the maximum extent permitted by applicable law, in no event shall the Company or its
							suppliers be liable for any special, incidental, indirect, or consequential damages
							whatsoever (including, but not limited to, damages for loss of profits, loss of data or
							other information, for business interruption, for personal injury, loss of privacy arising
							out of or in any way related to the use of or inability to use the Service, third-party
							software and/or third-party hardware used with the Service, or otherwise in connection with
							any provision of this Terms), even if the Company or any supplier has been advised of the
							possibility of such damages and even if the remedy fails of its essential purpose.</p>
						<p>Some states do not allow the exclusion of implied warranties or limitation of liability for
							incidental or consequential damages, which means that some of the above limitations may not
							apply. In these states, each party's liability will be limited to the greatest extent
							permitted by law.</p>
						<h2>&quot;AS IS&quot; and &quot;AS AVAILABLE&quot; Disclaimer</h2>
						<p>The Service is provided to You &quot;AS IS&quot; and &quot;AS AVAILABLE&quot; and with all
							faults and defects without warranty of any kind. To the maximum extent permitted under
							applicable law, the Company, on its own behalf and on behalf of its Affiliates and its and
							their respective licensors and service providers, expressly disclaims all warranties,
							whether express, implied, statutory or otherwise, with respect to the Service, including all
							implied warranties of merchantability, fitness for a particular purpose, title and
							non-infringement, and warranties that may arise out of course of dealing, course of
							performance, usage or trade practice. Without limitation to the foregoing, the Company
							provides no warranty or undertaking, and makes no representation of any kind that the
							Service will meet Your requirements, achieve any intended results, be compatible or work
							with any other software, applications, systems or services, operate without interruption,
							meet any performance or reliability standards or be error free or that any errors or defects
							can or will be corrected.</p>
						<p>Without limiting the foregoing, neither the Company nor any of the company's provider makes
							any representation or warranty of any kind, express or implied: (i) as to the operation or
							availability of the Service, or the information, content, and materials or products included
							thereon; (ii) that the Service will be uninterrupted or error-free; (iii) as to the
							accuracy, reliability, or currency of any information or content provided through the
							Service; or (iv) that the Service, its servers, the content, or e-mails sent from or on
							behalf of the Company are free of viruses, scripts, trojan horses, worms, malware, timebombs
							or other harmful components.</p>
						<p>Some jurisdictions do not allow the exclusion of certain types of warranties or limitations
							on applicable statutory rights of a consumer, so some or all of the above exclusions and
							limitations may not apply to You. But in such a case the exclusions and limitations set
							forth in this section shall be applied to the greatest extent enforceable under applicable
							law.</p>
						<p>The Service is provided to You &quot;AS IS&quot; and &quot;AS AVAILABLE&quot; and with all
							faults and defects without warranty of any kind. To the maximum extent permitted under
							applicable law, the Company, on its own behalf and on behalf of its Affiliates and its and
							their respective licensors and service providers, expressly disclaims all warranties,
							whether express, implied, statutory or otherwise, with respect to the Service, including all
							implied warranties of merchantability, fitness for a particular purpose, title and
							non-infringement, and warranties that may arise out of course of dealing, course of
							performance, usage or trade practice. Without limitation to the foregoing, the Company
							provides no warranty or undertaking, and makes no representation of any kind that the
							Service will meet Your requirements, achieve any intended results, be compatible or work
							with any other software, applications, systems or services, operate without interruption,
							meet any performance or reliability standards or be error free or that any errors or defects
							can or will be corrected.</p>
						<p>Without limiting the foregoing, neither the Company nor any of the company's provider makes
							any representation or warranty of any kind, express or implied: (i) as to the operation or
							availability of the Service, or the information, content, and materials or products included
							thereon; (ii) that the Service will be uninterrupted or error-free; (iii) as to the
							accuracy, reliability, or currency of any information or content provided through the
							Service; or (iv) that the Service, its servers, the content, or e-mails sent from or on
							behalf of the Company are free of viruses, scripts, trojan horses, worms, malware, timebombs
							or other harmful components.</p>
						<p>Some jurisdictions do not allow the exclusion of certain types of warranties or limitations
							on applicable statutory rights of a consumer, so some or all of the above exclusions and
							limitations may not apply to You. But in such a case the exclusions and limitations set
							forth in this section shall be applied to the greatest extent enforceable under applicable
							law.</p>
						<h2>Governing Law</h2>
						<p>The laws of the Country, excluding its conflicts of law rules, shall govern this Terms and
							Your use of the Service. Your use of the Application may also be subject to other local,
							state, national, or international laws.</p>
						<p>The laws of the Country, excluding its conflicts of law rules, shall govern this Terms and
							Your use of the Service. Your use of the Application may also be subject to other local,
							state, national, or international laws.</p>
						<h2>Disputes Resolution</h2>
						<p>If You have any concern or dispute about the Service, You agree to first try to resolve the
							dispute informally by contacting the Company.</p>
						<p>If You have any concern or dispute about the Service, You agree to first try to resolve the
							dispute informally by contacting the Company.</p>
						<h2>For European Union (EU) Users</h2>
						<p>If You are a European Union consumer, you will benefit from any mandatory provisions of the
							law of the country in which You are resident.</p>
						<p>If You are a European Union consumer, you will benefit from any mandatory provisions of the
							law of the country in which You are resident.</p>
						<h2>United States Legal Compliance</h2>
						<p>You represent and warrant that (i) You are not located in a country that is subject to the
							United States government embargo, or that has been designated by the United States
							government as a &quot;terrorist supporting&quot; country, and (ii) You are not listed on any
							United States government list of prohibited or restricted parties.</p>
						<p>You represent and warrant that (i) You are not located in a country that is subject to the
							United States government embargo, or that has been designated by the United States
							government as a &quot;terrorist supporting&quot; country, and (ii) You are not listed on any
							United States government list of prohibited or restricted parties.</p>
						<h2>Severability and Waiver</h2>
						<h3>Severability</h3>
						<p>If any provision of these Terms is held to be unenforceable or invalid, such provision will
							be changed and interpreted to accomplish the objectives of such provision to the greatest
							extent possible under applicable law and the remaining provisions will continue in full
							force and effect.</p>
						<p>If any provision of these Terms is held to be unenforceable or invalid, such provision will
							be changed and interpreted to accomplish the objectives of such provision to the greatest
							extent possible under applicable law and the remaining provisions will continue in full
							force and effect.</p>
						<h3>Waiver</h3>
						<p>Except as provided herein, the failure to exercise a right or to require performance of an
							obligation under these Terms shall not affect a party's ability to exercise such right or
							require such performance at any time thereafter nor shall the waiver of a breach constitute
							a waiver of any subsequent breach.</p>
						<p>Except as provided herein, the failure to exercise a right or to require performance of an
							obligation under these Terms shall not affect a party's ability to exercise such right or
							require such performance at any time thereafter nor shall the waiver of a breach constitute
							a waiver of any subsequent breach.</p>
						<h2>Translation Interpretation</h2>
						<p>These Terms and Conditions may have been translated if We have made them available to You on
							our Service.
						<p>These Terms and Conditions may have been translated if We have made them available to You on
							our Service.
							You agree that the original English text shall prevail in the case of a dispute.</p>
						<h2>Changes to These Terms and Conditions</h2>
						<p>We reserve the right, at Our sole discretion, to modify or replace these Terms at any time.
							If a revision is material We will make reasonable efforts to provide at least 30 days'
							notice prior to any new terms taking effect. What constitutes a material change will be
							determined at Our sole discretion.</p>
						<p>By continuing to access or use Our Service after those revisions become effective, You agree
							to be bound by the revised terms. If You do not agree to the new terms, in whole or in part,
							please stop using the website and the Service.</p>
						<p>We reserve the right, at Our sole discretion, to modify or replace these Terms at any time.
							If a revision is material We will make reasonable efforts to provide at least 30 days'
							notice prior to any new terms taking effect. What constitutes a material change will be
							determined at Our sole discretion.</p>
						<p>By continuing to access or use Our Service after those revisions become effective, You agree
							to be bound by the revised terms. If You do not agree to the new terms, in whole or in part,
							please stop using the website and the Service.</p>
						<h2>Contact Us</h2>
						<p>If you have any questions about these Terms and Conditions, You can contact us:</p>
						<ul>
							<li>By phone number: 408.996.1010</li>
						</ul>
					</div>

				</div>
				<button id="closeTerms" onclick=hideShowTerms()> <span> Close this Window</span></button>
			</div>


	</div>


</div>

<?php
include_once 'footer.php';
?>

<script>
	// To Show/Hide the Password
	function showHidePassword() {
		const x = document.getElementById("password");
		const z = document.getElementById("show-password");
		const y = document.getElementById("hide-password");

		if (x.type === 'password') {
			x.type = 'text';
			y.style.display = "block";
			z.style.display = "none";
		} else {
			x.type = 'password';
			y.style.display = "none";
			z.style.display = "block";
		}
	}
</script>

<script type="text/javascript" src="./js/lightbox-plus-jquery.min.js"></script>

<script>
	// To enable the Submit Idea button after the Terms and Conditions are read

	$(function () {
		$(function () {
			var ideaButton = $('#submitIdea');
			ideaButton.attr('disabled', 'disabled')
			$('#acceptTerms').change(function (e) {
				$('#acceptTerms').change(function (e) {
					if (this.checked) {
						ideaButton.removeAttr('disabled');
						ideaButton.addClass('submitIdeaStyle')
					} else {
						ideaButton.attr('disabled', 'disabled');
						ideaButton.removeClass('submitIdeaStyle')
					}
				})
			})
</script>


<script>
			// Script for the Hidden Terms and Conditions
			let termsCons = document.querySelector('#terms-container');

			function hideShowTerms() {
				termsCons.classList.toggle("hide-Terms")
			}
</script>