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
$user_result = @file_get_contents(
    'http://localhost:9000/api/user/all',
    false,
    stream_context_create([
        'http' => [
            'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
            'method' => 'GET'
        ]
    ])
);

$users_response = json_decode($user_result, true);

$users = $users_response['users'];

function getDepartmentName($departmentId)
{
    global $token;
    $department_result = @file_get_contents(
        "http://localhost:9000/api/department/name",
        false,
        stream_context_create([
            'http' => [
                'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
                'method' => 'GET',
                "content" => json_encode([
                    'departmentId' => $departmentId
                ])
            ]
        ])
    );
    $department_response = json_decode($department_result, true);
    error_log(print_r($department_response, true));
    return $department_response["departmentName"];
}

// Last login
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
error_log(print_r($users, true));
// error_log(print_r($lastlogin, true));

// Categories Array
$categories = [];

// Get all categories from the api

$category_result = @file_get_contents(
    'http://localhost:9000/api/category/all',
    false,
    stream_context_create([
        'http' => [
            'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
            'method' => 'GET'
        ]
    ])
);

$categories_response = json_decode($category_result, true);

if ($categories_response && isset($categories_response['success']) && $categories_response['success']) {
    $categories = $categories_response['categories'];
}

// error_log(print_r($categories, true));
function addCategory($name)
{
    global $token;
    $category_result = @file_get_contents(
        "http://localhost:9000/api/category/add",
        false,
        stream_context_create([
            'http' => [
                'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
                'method' => 'POST',
                "content" => json_encode([
                    'name' => $name
                ])
            ]
        ])
    );
    $category_response = json_decode($category_result, true);
    if ($category_response['success']) {
        // append to $category array
        array_push($categories, [
            'id' => $category_response['categoryId'],
            'name' => $name
        ]);

        echo '<div class="alert alert-success">Category Added Successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Failed to Add Category!</div>';
    }
}

function removeCategory($id)
{
    global $categories;
    // Remove category from $categories array
    foreach ($categories as $key => $category) {
        if ($category['id'] == $id) {
            unset($categories[$key]);
            break;
        }
    }
}
?>

<div id="manager-dashboard-page">
    <div class="dashboard-wrapper">

        <div class="welcome-header">
            <h3>Welcome, <?php echo $response['firstname']; ?> </h3>;
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
            <div class="nav-links" id="manager-nav">
                <a href="#" onclick="showSection('manageCategories')"><i class="fas fa-tags"></i> Manage Categories</a>
                <a href="#" onclick="showSection('viewIdeas')"><i class="fas fa-lightbulb"></i> View Ideas</a>
                <a href="#" onclick="showSection('commentsReports')"><i class="fas fa-comments"></i> Comments and
                    Reports</a>
                <a href="#" onclick="showSection('userManagement')"><i class="fas fa-user-cog"></i> User Management</a>
                <a href="#" onclick="showSection('exportData')"><i class="fas fa-file-export"></i> Export Data</a>
                <a href="#" onclick="showSection('exceptionReports')"><i class="fas fa-exclamation-circle"></i>
                    Exception Reports</a>
                <a href="#" onclick="showSection('statistics')"><i class="fas fa-chart-bar"></i> Statistics</a>
                <a href="#" onclick="showSection('systemSettings')"><i class="fas fa-cogs"></i> System Settings</a>
            </div>
        </nav>

        <!-- Sections for each menu item -->
        <div id="manageCategories" class="content-section"><span>Content for Manage Categories</span></div>
        <div id="viewIdeas" class="content-section"><span>Content for View Ideas</span></div>
        <div id="commentsReports" class="content-section"><span>Content for Comments and Reports</span></div>
        <div id="userManagement" class="content-section"><span> User Management</span>
            <br>

            <div class="content-section-contents">

                <!-- 
                <button class="btn" onclick="addUser()">Enable User</button>
                <button class="btn" onclick="removeUser()">Disable User</button> -->

                <!-- Display the list of users -->
                <?php
                // The Array where users are coming from
                

                // Check if there are users in the array Coming from Niza
                if (!empty($users)) {
                    echo '<table border="1" cellpadding="10">';
                    echo '<tr><th>First Name</th><th>Last Name</th><th>Username</th><th>Email</th><th>Role</th><th>Department</th></tr>';

                    // Loop through each user and display their information
                    foreach ($users as $user) {
                        echo '<tr>';

                        echo '<td>' . htmlspecialchars($user['firstname']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['lastname']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['role']) . '</td>';
                        echo '<td>' . htmlspecialchars(getDepartmentName($user['departmentId'])) . '</td>';
                        echo '<td>';
                        echo '<button onclick="enableUser(\'' . htmlspecialchars($user['_id']) . '\')">Enable</button> ';
                        echo '<button onclick="disableUser(\'' . htmlspecialchars($user['_id']) . '\')">Disable</button>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo '<p>No users found.</p>';
                }
                ?>
            </div>
            </span>
        </div>
        <div id="exportData" class="content-section"><span>Content for Export Data</span></div>
        <div id="exceptionReports" class="content-section"><span>Content for Exception Reports</span></div>
        <div id="statistics" class="content-section"><span>Content for Statistics</span></div>
        <div id="systemSettings" class="content-section"><span>Content for System Settings</span></div>


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

    // Button functionality for User Management
    function addUser() {
        alert("Add User functionality coming soon!");
        // Add your code here to implement the add user functionality
    }

    function removeUser() {
        alert("Remove User functionality coming soon!");
        // Add your code here to implement the remove user functionality
    }
</script>