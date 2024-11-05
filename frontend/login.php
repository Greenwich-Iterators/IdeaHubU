<?php
include_once 'header.php';
session_start();

// include 'config.php';

error_reporting(0);

//Session variable to lockout user
if (isset($_SESSION["locked"])) {
	$difference = time() - $_SESSION["locked"];
	if ($difference > 2) {
		unset($_SESSION["locked"]);
		unset($_SESSION["login_attempts"]);
	}
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['submit'])) {

		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$sql = "SELECT * FROM members WHERE email='$email' ";
		$result = mysqli_query($conn, $sql);
		if ($result->num_rows > 0) {
			$row = mysqli_fetch_assoc($result);

			if (password_verify($password, $row['password'])) {

				$_SESSION['first_name'] = $row['first_name'];
				$_SESSION['last_name'] = $row['last_name'];
				// $_SESSION['user_name'] = $row['username'];


				$_SESSION["id"] = $row["id"];

				$_SESSION["login_attempts"] += 1;
				header("Location: welcome.php");
			} else {
				// $_SESSION["error"] =  "Sorry, we could not display any results!";
				$message[] = "Oops! Looks like the provided Email or Password is incorrect!";

				$_SESSION["login_attempts"] += 1;
			}
		} else {
			// $_SESSION["error"] =  "Sorry, we could not display any results!";
			$message[] = "Oops! Looks like the provided Email or Password is incorrect!";
		}
	}
}

?>



<div id="log-in-page">

	<div class="log-in-wrapper">

		<form id="log-in-form" method="POST" action="">

			<?php
			if (isset($message) || !empty($_SESSION['message'])) {
				foreach ($message as $message) {
					echo '<div class ="message">' . $message . '</div>';
				}
				// echo '<div class ="message">' . $_SESSION['message'] . '</div>';
			} elseif (!empty($_GET['message'])) {
				$message = $_GET['message'];
				echo '<div class ="message">' . $message . '</div>';
			}


			?>

			<h2>Member Login</h2>
			<p>Don't have an Account?<br> <a href="./signup.php"> Register here</a><br></p>

			<br>
			<?php
			if (isset($_SESSION["error"])) {
			?> <p> <?= $_SESSION["error"]; ?></p>
			<?php unset($_SESSION["error"]);
			} ?>
			<div>
				<label for="email"></label>
				<input type="text" name="email" class="log-in-inputs" placeholder="Enter email" value="<?php echo $email; ?>" required>
			</div>
			<br><br>
			<div>
				<label for="password"></label>
				<input type="password" name="password" class="log-in-inputs" id="password" placeholder="Enter password" value="<?php echo $_POST['password']; ?>" required>
				<span class="password-icon" onclick="showHidePassword()">

					<i class="fa-solid fa-eye" id="show-password"></i>
					<i class="fa-solid fa-eye-slash" id="hide-password"></i>
				</span>
			</div>



			<br><br>

			<?php
			// This will lockout the User and the login button shall disappear until time has lapsed

			if ($_SESSION["login_attempts"] > 2) {
				$_SESSION["locked"] = time();

				$message[] = 'Too many failed attempts. Please come back after a while.';
			} else {
			?>

				<button id="submit" name="submit" type="submit">Log in</button><?php
																			}
																				?>

			<!-- Here we create the form which starts the password recovery process! -->

			<?php
			if (isset($_GET["newpwd"])) {
				if ($GET["newpwd"] == "passwordupdated") {
					$message[] = "Your password was reset successfully!";
				}
			}
			?>
			<p>Forgotten your Password? <br><a href="./reset-password.php">Reset password</a></p>
		</form>


	</div>



</div>
</div>


<?php
include_once 'footer.php';
?>

<!-- Script to allow the password to be made visible -->

<script>
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