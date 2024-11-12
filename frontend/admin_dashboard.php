<?php
include_once 'header.php';
session_start();

error_reporting(0);


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
            <h3>Welcome, </h3>;
            <p>You are logged in with the following details:</p>
            <div class="login-info">
                <p>Last Login: 2024-11-05 10:30 AM <?php echo  $row['dateTime'] . "" ?>.</p>
            </div>
        </div>

        <div class="dashboard-section" id="user_details">

            <div class="user_info">
                <label for="first-Name">First Name: <?php echo "<p>" . " " .  $row['first_name'] . "" . "</p>"; ?></label>
                <label for="last-Name">Last Name: <?php echo "<p>" . " " .  $row['last_name'] . "" . "</p>"; ?></label>
                <label for="email">email:<?php echo "<p>" . " " .  $row['email'] . "" . "</p>"; ?></label>
                <label for="role">Role: <?php echo "<p>" . " " .  $row['user_role'] . "" .  "</p>"; ?></label>
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