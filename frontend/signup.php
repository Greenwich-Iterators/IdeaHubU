<?php
include_once 'header.php';
session_start();

//if (!isset($_SESSION['first_name'])) {
//	header("Location: login.php");
//}

include 'config.php';
require './PHPMailer/Mail.php';

error_reporting(1);




if (isset($_POST['submit'])) {
	$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
	$lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
	$userName = mysqli_real_escape_string($conn, $_POST['userName']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$cPassword = mysqli_real_escape_string($conn, $_POST['cPassword']);
	$image = $_FILES['photo']['name'];
	$image_size = $_FILES['photo']['size'];
	$image_tmp_name = $_FILES['photo']['tmp_name'];
	$image_folder = 'profile_pics/' . $image;


	//Checks to see if firstname, Last name and passwords are correct
	$body = json_encode([
		"firstName"=> $firstName, 
		"lastName" => $lastName, 
		"password" => $password
	]);
	$url = 'https://localhost:9000/api/users/register';

	// Init curl
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
	$response = curl_exec($curl);

	echo $response . PHP_EOL;


	if (!empty($firstName) && !empty($lastName) && !empty($userName) && !empty($password) && !is_numeric($firstName) && !is_numeric($lastName) && !is_numeric($userName)) {


		if ($password == $cPassword) {
			$sql = "SELECT * FROM members WHERE email='$email'";
			$newPwdHash = password_hash($password, PASSWORD_DEFAULT);
			$result = mysqli_query($conn, $sql);

			//writing data to the database

			// if (empty($password) && empty($cPassword)) {
			// 	$message[] = 'Oops! Looks like you did not enter any paswords.';
			// }
			if (!$result->num_rows > 0) {
				$sql = " INSERT INTO members (first_name,last_name,email,password,username,photo)
        		VALUES('$firstName','$lastName','$email','$newPwdHash', '$userName','$photo')";
				$result = mysqli_query($conn, $sql);
				if ($result) {


					$message[] = 'Congratulations! You have successfully registered to Amaka Zambia Gym.<br>We have emailed your username to the email address you provided.<br>You many now proceed to login to your profile.';

					$firstName = "";
					$lastName = "";
					$email = "";
					$userName = "";
					$_POST['password'] = "";
					$_POST['cPassword'] = "";
					$_POST['photo'];

					header('location:login.php');
				} else {

					$message[] = 'Oops! Looks like we are unable to register you at this time. Please try again.';

					if ($image_size > 200000) {
						$message[] = 'Image used is too large to upload!';
					} else {
						move_uploaded_file($image_tmp_name, $image_folder);
					}
				}
			} else {
				$message[] = 'Oops! Looks like that username is already taken.<br> Please create a different username.';
			}
		} else {


			$message[] = 'Oops! Looks like your passwords do not match!';
		}
	} else {
		$message[] = 'Oops! Looks like you have not entered all the information.';
	}
}

?>



<div id="sign-up-page">

	<div class="sign-up-wrapper">

		<form id="signup-form" method="POST">

			<?php
			if (isset($message)) {
				foreach ($message as $message) {
					echo '<div class ="message">' . $message . '</div>';
				}
			}
			?>

			<h2>Member Registration</h2>
			<p>Already a registered member? <br> <a href="./login.php">Login here</a></p>

			<br>
			<!-- 
			<div class="avatar-image">
				<img src="images/>">

			</div>
			<br> -->



			<div><label for="firstName"></label>
				<input type="text" name="firstName" class="signup-inputs" placeholder="First Name" value="<?php echo $firstName; ?>" required minlength="4" maxlength="30">
			</div><br><br>

			<div><label for="lastName"></label>
				<input type="text" name="lastName" class="signup-inputs" placeholder="Last Name" value="<?php echo $lastName; ?>" required maxlength="30">
			</div><br><br>

			<div><label for="userName"></label>
				<input type="text" name="userName" class="signup-inputs" placeholder="Username " value="<?php echo $userName; ?>" required maxlength="30">
			</div><br><br>


			<div><label for="email"></label>
				<input type="text" name="email" class="signup-inputs" placeholder="Enter email" type="email" value="<?php echo $email; ?>" required>
			</div><br><br>

			<div><label for="password"></label>
				<input type="password" id="password" name="password" min-length="" class="signup-inputs" placeholder="Enter password" value="<?php echo $_POST['password']; ?>" required minlength="6" maxlength="30">
				<span class="password-icon" onclick="showHidePassword()">

					<i class="fa-solid fa-eye" id="show-password"></i>
					<i class="fa-solid fa-eye-slash" id="hide-password"></i>
				</span>
			</div><br><br>

			<div><label for="cPassword"></label>
				<input type="password" id="cPassword" name="cPassword" class="signup-inputs" placeholder="Confirm password" value="<?php echo $_POST['cPassword']; ?>" required minlength="6" maxlength="30">
				<span class="password-icon" onclick="showHidePassword2()">

					<i class="fa-solid fa-eye" id="show-Cpassword"></i>
					<i class="fa-solid fa-eye-slash" id="hide-Cpassword"></i>
				</span>
			</div><br><br>

			<button id="submit" name="submit" type="submit" required>Register</button>

			<?php

			if (isset($_GET['newpwd'])) {
				if ($_GET['newpwd'] == "passwordupdated") {
					echo "<script>alert('Your password has been reset successfully!') </script>";
				}
			}
			?>


		</form>


	</div>



</div>
</div>



<?php
include_once 'footer.php';
?>

<!-- Script to allow the passwords to be made visible or hidden-->

<script>
	function showHidePassword() {
		const w = document.getElementById("password");
		const y = document.getElementById("show-password");
		const z = document.getElementById("hide-password");

		if (w.type === 'password') {
			w.type = 'text';
			y.style.display = "block";
			z.style.display = "none";
		} else {
			w.type = 'password';
			y.style.display = "none";
			z.style.display = "block";
		}
	}

	function showHidePassword2() {

		const x = document.getElementById("cPassword");
		const y = document.getElementById("show-Cpassword");
		const z = document.getElementById("hide-Cpassword");

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