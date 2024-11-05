<?php
// Check if the auth_token cookie exists
if (!isset($_COOKIE['auth_token'])) {
    // Redirect to login page if the auth_token cookie is not set
    header("Location: login.php");
    exit();
}

// Access the token from the cookie
$token = $_COOKIE['auth_token'];

// Verify the token with the backend
$url = 'http://localhost:9000/api/user/verifytoken'; // Adjust this URL to match your backend URL
$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
        'method'  => 'GET'
    ]
];
$context = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === FALSE) {
    // Error occurred or token is invalid
    header("Location: login.php");
    exit();
}

$response = json_decode($result, true);

if (!$response['valid']) {
    // Token is invalid or expired
    header("Location: login.php");
    exit();
}

// Token is valid, continue with the welcome page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, logged in user!</h1>
    <p>Your user ID is: <?php echo htmlspecialchars($response['userId']); ?></p>
</body>
</html>
