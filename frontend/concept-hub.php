<?php
session_start();
include_once 'header.php';

if (!isset($_SESSION['first_name']) && (isset($_POST['idea-search-btn']))) {
    echo "<script>
    alert('You need to be Signed in to leave a comment.\\nPlease Log in or Sign up.');
    window.location.href='login.php';
    </script>";
}

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

// Get all ideas from API
$ideasContext = stream_context_create([
    'http' => [
        'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
        'method' => 'GET',

    ]
]);

$ideasResult = @file_get_contents(
    "http://localhost:9000/api/idea/all",
    false,
    $ideasContext
);

$ideasResponse = json_decode($ideasResult, true);

?>



<div id="ideas-page">
    <div class="ideas-card">
        <?php
        if (isset($ideasResponse['success']) && $ideasResponse['success'] && isset($ideasResponse['ideas'])) {
            foreach ($ideasResponse['ideas'] as $idea) {
                ?>
                <div class="idea-card">
                    <h3><?php echo htmlspecialchars($idea['ideaTitle']); ?></h3>
                    <p><?php echo htmlspecialchars($idea['ideaDescription']); ?></p>
                    <p>Posted by:
                        <?php
                        if (isset($idea['anonymousPost']) && $idea['anonymousPost']) {
                            echo "Anonymous";
                        } elseif (isset($idea['userId']) && isset($idea['userId']['firstname']) && isset($idea['userId']['lastname'])) {
                            echo htmlspecialchars($idea['userId']['firstname'] . ' ' . $idea['userId']['lastname']);
                        } else {
                            echo "Unknown";
                        }
                        ?>
                    </p>
                    <p>Created at: <?php echo date('Y-m-d H:i:s', strtotime($idea['createdAt'])); ?></p>
                    <p>Likes: <?php echo count($idea['userLikes']); ?></p>
                    <p>Dislikes: <?php echo count($idea['userDislikes']); ?></p>
                </div>
                <?php
            }
        } else {
            echo "<p>No ideas found or there was an error fetching the ideas.</p>";
        }
        ?>
    </div>

    <!-- <div class="search-container">
        <form class='search-form' method="post">


            <input type="text" name="q" placeholder="Search ideas" required>

            <select id="categoryFilter">
                <option value="">All Categories</option>
                <option value="teaching">Teaching</option>
                <option value="technology">Technology</option>
                <option value="health">Health</option>
                <option value="recreation">Recreation</option>

            </select>
            <select id="timeFilter">
                <option value="all">All Time</option>
                <option value="month">This Month</option>
            </select>

            <button class="search-btn" type="submit" name="idea-search-btn"></button>
        </form>

        <?php

        // if (isset($_POST['idea-search-btn'])) {
        


        //     $connection = new mysqli("localhost", "root",  "", "amaka gym");
        //     $q = $connection->real_escape_string($_POST['q']);
        
        //     $column = $connection->real_escape_string($_POST['column']);
        
        //     if ($column == "" || ($column != "idea_title" && $column != "idea_description" && $column != "idea_Category"))
        //         $column = "idea_title";
        
        //     $sql = $connection->query(query: "SELECT idea_title FROM ideas WHERE $column LIKE '%$q%'");
        //     $result = mysqli_num_rows($sql);
        //     if ($result > 0) {
        //         while ($data = mysqli_fetch_assoc($sql))
        //             echo  $data['idea_title'] . " <br>";
        //     } else
        //         echo "<br>" . "No results found!";
        // }
        
        ?>
    </div> -->
    <div class="title-text">
        <h3>Welcome to the University Concept Hub page</h3>
        <p> A place where creativity is cultivated! Discover the most popular projects, look into the most popular
            concepts, and keep up with the most recent contributions here. You can interact with fascinating ideas,
            express your opinions, and work together with other students, teachers, and business owners here. Comment,
            vote, and share your thoughts on the concepts that motivate you to join the discussion. Let's collaborate to
            create, innovate, and transform because we at the Idea Hub think that every idea has the power to change the
            world.</p>
    </div>



    <!-- Ideas Tabs Section -->
    <div class="tabs">
        <button onclick="showIdeas('mostPopular')">Most Popular</button>
        <button onclick="showIdeas('mostLiked')">Most Liked</button>
        <button onclick="showIdeas('mostViewed')">Most Viewed</button>

        <!-- Button to Show Original View -->
        <button class="show-original" onclick="showOriginalView()">Reset View</button>
    </div>

    <!-- Ideas Section -->
    <div id="ideasContainer">
        <div class="category" id="teachingCategory">
            <h2>Teaching </h2>
            <div class="ideas-grid">
                <div class="idea-card">
                    <h3>Add longer breaks between classes</h3>
                    <p>🔖 Teaching, Quality</p>
                    <p>👍 45 👀 120 💬 12</p>
                </div>
                <div class="idea-card">
                    <h3>Add longer breaks between classes</h3>
                    <p>🔖 Teaching, Quality</p>
                    <p>👍 45 👀 120 💬 12</p>
                </div>
                <div class="idea-card">
                    <h3>Add longer breaks between classes</h3>
                    <p>🔖 Teaching, Quality</p>
                    <p>👍 45 👀 120 💬 12</p>
                </div>
                <!-- More Idea Cards -->
            </div>
        </div>
        <div class="category" id="technologyCategory">
            <h2>Technology </h2>
            <div class="ideas-grid">
                <div class="idea-card">
                    <h3>Increase the WiFi range</h3>
                    <p>🔖 Technology, Quality</p>
                    <p>👍 475 👀 1200 💬 12</p>
                </div>
                <div class="idea-card">
                    <h3>Increase the WiFi range</h3>
                    <p>🔖 Technology, Quality</p>
                    <p>👍 475 👀 1200 💬 12</p>
                </div>
                <div class="idea-card">
                    <h3>Increase the WiFi range</h3>
                    <p>🔖 Technology, Quality</p>
                    <p>👍 475 👀 1200 💬 12</p>
                </div>
                <!-- More Idea Cards -->
            </div>
        </div>
        <div class="category" id="healthCategory">
            <h2>Health </h2>
            <div class="ideas-grid">
                <div class="idea-card">
                    <h3>Water stations to be increased</h3>
                    <p>🔖 Water, Quality</p>
                    <p>👍 88 👀 136 💬 12</p>
                </div>
                <div class="idea-card">
                    <h3>Water stations to be increased</h3>
                    <p>🔖 Water, Quality</p>
                    <p>👍 88 👀 136 💬 12</p>
                </div>
                <div class="idea-card">
                    <h3>Water stations to be increased</h3>
                    <p>🔖 Water, Quality</p>
                    <p>👍 88 👀 136 💬 12</p>
                </div>
                <!-- More Idea Cards -->
            </div>
        </div>
        <div class="category" id="recreationCategory">
            <h2>Recreation </h2>
            <div class="ideas-grid">
                <div class="idea-card">
                    <h3>Add more sitting benches on the loan</h3>
                    <p>🔖 Environment, Quality</p>
                    <p>👍 93 👀 188 💬 12</p>
                </div>
                <div class="idea-card">
                    <h3>Add more sitting benches on the loan</h3>
                    <p>🔖 Environment, Quality</p>
                    <p>👍 203 👀 1208 💬 71</p>
                </div>
                <div class="idea-card">
                    <h3>Add more sitting benches on the loan</h3>
                    <p>🔖 Environment, Quality</p>
                    <p>👍 193 👀 198 💬 42</p>
                </div>
                <!-- More Idea Cards -->
            </div>
        </div>
        <!-- Add more categories as needed -->
    </div>

    <!-- Sidebar (Optional) -->
    <aside>
        <h3>Top Contributors</h3>
        <ul>
            <li>User 1 (45 ideas)</li>
            <li>User 2 (30 ideas)</li>
        </ul>
        <h3>Categories</h3>
        <ul>
            <li>Teaching</li>
            <li>Technology</li>
            <li>Facilities</li>
        </ul>
        <p>Days until submission closes: <span id="closureCountdown">10</span></p>
    </aside>


    <!-- Load More Button -->
    <button class="load-more" onclick="loadMoreIdeas()">Load More Ideas...</button>



</div>




<script src="./js/showIdeaTabs.js"></script>
<?php
include_once 'footer.php';
?>