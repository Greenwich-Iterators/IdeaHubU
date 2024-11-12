<?php
include_once 'header.php';
session_start();

error_reporting(1);

if (isset($_POST['submit'])) {
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$cPassword = $_POST['cPassword'];

	if (!empty($firstname) && !empty($lastname) && !empty($username) && !empty($password) && !is_numeric($firstname) && !is_numeric($lastname) && !is_numeric($username)) {
		if ($password == $cPassword) {
			// Prepare data for API call
			$data = array(
				'firstname' => $firstname,
				'lastname' => $lastname,
				'username' => $username,
				'email' => $email,
				'password' => $password
			);

			$url = 'http://localhost:9000/api/user/register';

			// Prepare the options for the HTTP stream
			$options = [
				'http' => [
					'method' => 'POST',
					'header' => 'Content-Type: application/json',
					'content' => json_encode($data)
				]
			];

			// Create a stream context
			$context = stream_context_create($options);

			// Send the request
			$response = @file_get_contents($url, false, $context);

			if ($response === FALSE) {
				$message[] = "Error connecting to the server. Please try again later.";
			} else {
				$httpCode = $http_response_header[0];
				if (strpos($httpCode, '201') !== false) {
					$result = json_decode($response, true);
					if (isset($result['success']) && $result['success']) {
						$message[] = "Registration successful! Please log in.";
						// You might want to redirect to login page here
						// header("Location: login.php");
					} else {
						$message[] = "Registration failed. " . ($result['message'] ?? "Please try again.");
					}
				} else {
					$message[] = "Error connecting to the server. Please try again later.";
				}
			}
		} else {
			$message[] = 'Oops! Looks like your passwords do not match!';
		}
	} else {
		$message[] = 'Oops! Looks like you have not entered all the information.';
	}
}
?>

<!-- Rest of your HTML code remains the same -->
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



			<div><label for="firstname"></label>
				<input type="text" name="firstname" class="signup-inputs" placeholder="First Name"
					value="<?php echo $firstname; ?>" required minlength="4" maxlength="30">
			</div><br><br>

			<div><label for="lastname"></label>
				<input type="text" name="lastname" class="signup-inputs" placeholder="Last Name"
					value="<?php echo $lastname; ?>" required maxlength="30">
			</div><br><br>

			<div><label for="username"></label>
				<input type="text" name="username" class="signup-inputs" placeholder="Username "
					value="<?php echo $username; ?>" required maxlength="30">
			</div><br><br>


			<div><label for="email"></label>
				<input type="text" name="email" class="signup-inputs" placeholder="Enter email" type="email"
					value="<?php echo $email; ?>" required>
			</div><br><br>

			<!-- Select Department -->

			<div><label for="password"></label>
				<input type="password" id="password" name="password" min-length="" class="signup-inputs"
					placeholder="Enter password" value="<?php echo $_POST['password']; ?>" required minlength="6"
					maxlength="30">
				<span class="password-icon" onclick="showHidePassword()">

					<i class="fa-solid fa-eye" id="show-password"></i>
					<i class="fa-solid fa-eye-slash" id="hide-password"></i>
				</span>
			</div><br><br>

			<div><label for="cPassword"></label>
				<input type="password" id="cPassword" name="cPassword" class="signup-inputs"
					placeholder="Confirm password" value="<?php echo $_POST['cPassword']; ?>" required minlength="6"
					maxlength="30">
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