<?php
include_once 'header.php';
session_start();

error_reporting(0);

// Is User Logged In
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

$lastlogin_result = @file_get_contents(
    "http://localhost:9000/api/user/lastlogin",
    false,
    stream_context_create([
        'http' => [
            'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
            'method' => 'GET',
            "content" => json_encode([
                'userId' => $response['userId']
            ])
        ]
    ])
);

$lastlogin_response = json_decode($lastlogin_result, true);
$lastlogin = date('Y-m-d h:i A', strtotime($lastlogin_response['lastLogin']));
// $user_id = $_SESSION['id'];

// This code will send the user to the login page to login before accessing this page
// if (!isset($_SESSION['first_name'])) {


//     $_SESSION['message'] = 'You must be logged in as a user for you to access this page!<br>
//     Please Log in or Sign up.';
//     header('location:login.php');
//     exit;
// }

// Make a connection to the database 
// $sql = "SELECT * FROM members WHERE id='{$_SESSION['id']}' ";
// $result = mysqli_query($conn, $sql);
// $row = mysqli_fetch_assoc($result);

// 
?>

<div id="admin-dashboard-page">
    <div class="dashboard-wrapper">

        <div class="welcome-header">
            <h3>Welcome, <?php echo $response['firstname']; ?></h3>;
            <p>You are logged in with the following details:</p>
            <div class="login-info">
                <p>Last Login: <?php echo $lastlogin . "" ?>.</p>
            </div>
        </div>

        <div class="dashboard-section" id="user_details">

            <div class="user_info">
                <label for="first-Name">First Name:
                    <?php echo "<p>" . "  " . $response['firstname'] . "" . "</p>"; ?></label>
                <label for="last-Name">Last Name:
                    <?php echo "<p>" . "  " . $response['lastname'] . "" . "</p>"; ?></label>
                <label for="email">email:<?php echo "<p>" . "  " . $response['email'] . "" . "</p>"; ?></label>
                <label for="role">Role: <?php echo "<p>" . "  " . $response['userRole'] . "" . "</p>"; ?></label>
            </div>
        </div>

        <!-- The Dashboard Navigation Menu -->
        <nav>
            <div class="nav-links" id="admin-nav">
                <a href="#"><i class="fas fa-home"></i> Home</a>
                <a href="#"><i class="fas fa-user-cog"></i> User Management</a>
                <a href="#"><i class="fas fa-cogs"></i> System Settings</a>
                <a href="#"><i class="fas fa-users"></i> View Staff</a>
                <a href="#"><i class="fas fa-calendar-alt"></i> Closure Dates</a>
                <a href="#"><i class="fas fa-chart-line"></i> Usage Reports</a>
                <a href="#"><i class="fas fa-exclamation-circle"></i> Exception Reports</a>
            </div>


        </nav>


    </div>
</div>

<?php
include_once 'footer.php';
?>