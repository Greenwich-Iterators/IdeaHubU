<?php
include_once 'header.php';
session_start();

error_reporting(1);

if (isset($_POST['submit'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	if (!empty($email) && !empty($password)) {
		// Prepare data for API call
		$data = array(
			'email' => $email,
			'password' => $password
		);

		$url = 'http://localhost:9000/api/user/login';

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
			$result = json_decode($response, true);
			$httpCode = $http_response_header[0];

			if (strpos($httpCode, '200') !== false) {
				// Login successful
				$_SESSION['token'] = $result['token'];
				$_SESSION['user_id'] = $result['userId'];
				$_SESSION['username'] = $result['username'];
				$_SESSION['role'] = $result['role'];
				$_SESSION['last_login'] = $result['lastLogin'];
				$_SESSION['first_login'] = $result['firstLogin'];

				// Save the token in the cookie
				$cookie_name = "auth_token";
				$cookie_value = $result['token'];
				$cookie_expiry = time() + (8 * 3600); // 8 hours, matching the token expiry
				$cookie_path = "/"; // The cookie will be available within the entire domain
				$cookie_domain = ""; // Empty string means the current domain
				$cookie_secure = true; // Send only over HTTPS
				$cookie_httponly = true; // Make the cookie accessible only through the HTTP protocol

				setcookie($cookie_name, $cookie_value, $cookie_expiry, $cookie_path, $cookie_domain, $cookie_secure, $cookie_httponly);
				// Redirect to welcome page
				header("Location: welcome.php");
				exit();
			} elseif (strpos($httpCode, '401') !== false) {
				// Invalid credentials
				$message[] = "Invalid credentials. Please try again.";
			} elseif (strpos($httpCode, '500') !== false) {
				// Server error
				$message[] = "Server error. Please try again later.";
			} else {
				// Unexpected error
				$message[] = "An unexpected error occurred. Please try again.";
			}
		}
	} else {
		$message[] = 'Please enter both email and password.';
	}
}
?>

<div id="log-in-page">
	<div class="log-in-wrapper">
		<form id="log-in-form" method="POST">
			<?php
			if (isset($message)) {
				foreach ($message as $msg) {
					echo '<div class="message">' . $msg . '</div>';
				}
			}
			?>
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

			<div>
				<label for="email"></label>
				<input type="email" name="email" class="log-in-inputs" placeholder="Enter email"
					value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
			</div>
			<br><br>
			<div>
				<label for="password"></label>
				<input type="password" name="password" class="log-in-inputs" id="password" placeholder="Enter password"
					required>
				<span class="password-icon" onclick="showHidePassword()">
					<i class="fa-solid fa-eye" id="show-password"></i>
					<i class="fa-solid fa-eye-slash" id="hide-password"></i>
				</span>
			</div>

			<br><br>

			<button id="submit" name="submit" type="submit">Log in</button>

			<p>Forgotten your Password? <br><a href="./reset-password.php">Reset password</a></p>
		</form>
	</div>
</div>

<?php
include_once 'footer.php';
?>

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