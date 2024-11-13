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

// Get all users from the api
$user_url = 'http://localhost:9000/api/user/all';
$options = [
    'http' => [
        'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
        'method' => 'GET'
    ]
];

$context = stream_context_create($options);

$user_result = @file_get_contents($user_url, false, $context);

$users_response = json_decode($user_result, true);

$users = $users_response['users'];

error_log(print_r($users, true));

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

<div id="manager-dashboard-page">
    <div class="dashboard-wrapper">

        <div class="welcome-header">
            <h3>Welcome, </h3>;
            <p>You are logged in with the following details:</p>
            <div class="login-info">
                <p>Last Login: 2024-11-05 10:30 AM <?php echo $row['dateTime'] . "" ?>.</p>
            </div>
        </div>

        <div class="dashboard-section" id="user_details">

            <div class="user_info">
                <label for="first-Name">First Name:
                    <?php echo "<p>" . " " . $row['first_name'] . "" . "</p>"; ?></label>
                <label for="last-Name">Last Name: <?php echo "<p>" . " " . $row['last_name'] . "" . "</p>"; ?></label>
                <label for="email">email:<?php echo "<p>" . " " . $row['email'] . "" . "</p>"; ?></label>
                <label for="role">Role: <?php echo "<p>" . " " . $row['user_role'] . "" . "</p>"; ?></label>
            </div>
        </div>

        <!-- The Dashboard Navigation Menu -->
        <nav>
            <div class="nav-links" id="manager-nav">
                <a href="#" onclick="showSection('manageCategories')"><i class="fas fa-tags"></i> Manage Categories</a>
                <a href="#" onclick="showSection('viewIdeas')"><i class="fas fa-lightbulb"></i> View Ideas</a>
                <a href="#" onclick="showSection('commentsReports')"><i class="fas fa-comments"></i> Comments and Reports</a>
                <a href="#" onclick="showSection('userManagement')"><i class="fas fa-user-cog"></i> User Management</a>
                <a href="#" onclick="showSection('exportData')"><i class="fas fa-file-export"></i> Export Data</a>
                <a href="#" onclick="showSection('exceptionReports')"><i class="fas fa-exclamation-circle"></i> Exception Reports</a>
                <a href="#" onclick="showSection('statistics')"><i class="fas fa-chart-bar"></i> Statistics</a>
                <a href="#" onclick="showSection('systemSettings')"><i class="fas fa-cogs"></i> System Settings</a>
            </div>
        </nav>

        <!-- Sections for each menu item -->
        <div id="manageCategories" class="content-section">Content for Manage Categories</div>
        <div id="viewIdeas" class="content-section">Content for View Ideas</div>
        <div id="commentsReports" class="content-section">Content for Comments and Reports</div>
        <div id="userManagement" class="content-section">Content for User Management</div>
        <div id="exportData" class="content-section">Content for Export Data</div>
        <div id="exceptionReports" class="content-section">Content for Exception Reports</div>
        <div id="statistics" class="content-section">Content for Statistics</div>
        <div id="systemSettings" class="content-section">Content for System Settings</div>




    </div>
</div>

<?php
include_once 'footer.php';
?>

<!-- Script to Hide and show different Tabs on the dashboard -->
<script>
    function showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.content-section').forEach((section) => {
            section.style.display = 'none';
        });

        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';
    }
</script>