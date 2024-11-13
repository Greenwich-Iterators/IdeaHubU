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


    <div class="title-text">
        <h3>Welcome to the University Concept Hub page</h3>
        <p> A place where creativity is cultivated! Discover the most popular projects, look into the most popular
            concepts, and keep up with the most recent contributions here. You can interact with fascinating ideas,
            express your opinions, and work together with other students, teachers, and business owners here. Comment,
            vote, and share your thoughts on the concepts that motivate you to join the discussion. Let's collaborate to
            create, innovate, and transform because we at the Idea Hub think that every idea has the power to change the
            world.</p>
    </div>

    <div>
        <p>Newest Ideas</p>
    </div>

    <?php

    // ... (previous code remains the same)
    
    // Pagination
    $ideasPerPage = 5;
    $totalIdeas = count($ideasResponse['ideas']);
    $totalPages = ceil($totalIdeas / $ideasPerPage);
    $currentPage = isset($_GET['page']) ? max(1, min($_GET['page'], $totalPages)) : 1;
    $startIndex = ($currentPage - 1) * $ideasPerPage;
    if (isset($ideasResponse['success']) && $ideasResponse['success'] && isset($ideasResponse['ideas'])) {
        // Start the single .newest-ideas container
        echo '<div class="newest-ideas">';

        // Slice the ideas array to get only the ideas for the current page
        $currentIdeas = array_slice($ideasResponse['ideas'], $startIndex, $ideasPerPage);

        foreach ($currentIdeas as $idea) {
            ?>
            <div class="newest-idea-card">
                <h3><?php echo htmlspecialchars($idea['ideaTitle']); ?></h3>
                <p><?php echo htmlspecialchars($idea['ideaDescription']); ?></p>
                <p>Posted by:
                    <?php
                    if (isset($idea['userId']) && isset($idea['userId']['firstname']) && isset($idea['userId']['lastname'])) {
                        echo htmlspecialchars($idea['userId']['firstname'] . ' ' . $idea['userId']['lastname']);
                    } else {
                        echo "Anonymous";
                    }
                    ?>
                </p>
                <p>Created at: <?php echo date('Y-m-d H:i:s', strtotime($idea['createdAt'])); ?></p>
                <p id="likeCount-<?php echo $idea['_id']; ?>">Likes: <?php echo count($idea['userLikes']); ?></p>
                <p id="dislikeCount-<?php echo $idea['_id']; ?>">Dislikes: <?php echo count($idea['userDislikes']); ?></p>

                <!-- Like and Dislike Buttons -->
                <div class="like-buttonz">
                    <button class="like-button" onclick="likeIdea('<?php echo $idea['_id']; ?>')"><i
                            class="fa-regular fa-thumbs-up"></i></button>
                    <button class="dislike-button" onclick="dislikeIdea('<?php echo $idea['_id']; ?>')"><i
                            class="fa-regular fa-thumbs-down"></i></button>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <div class="comments-list">
                        <?php
                        if (isset($idea['comments'])) {
                            foreach ($idea['comments'] as $comment) {
                                echo "<p><strong>" . htmlspecialchars($comment['username']) . ":</strong> " . htmlspecialchars($comment['text']) . "</p>";
                            }
                        }
                        ?>
                    </div>
                    <textarea class="comment-input" placeholder="Add a comment..."></textarea>
                    <button class="idea-card-submit-btn" onclick="addComment('<?php echo $idea['_id']; ?>')">Submit
                        Comment</button>
                </div>
            </div>
            <?php
        }

        // Close the single .newest-ideas container
        echo '</div>';

        // Pagination controls
        echo '<div class="pagination">';
        echo '<p>Page ' . $currentPage . ' of ' . $totalPages . '</p>';
        if ($currentPage > 1) {
            echo '<a href="?page=1" style="color: black; text-decoration: none; margin: 0 5px;">First</a>';
            echo '<a href="?page=' . ($currentPage - 1) . '" style="color: black; text-decoration: none; margin: 0 5px;">Previous</a>';
        }

        // Display page numbers
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                echo '<span style="font-weight: bold; margin: 0 5px; display: inline-block; width: 30px; height: 30px; line-height: 30px; text-align: center; border-radius: 50%; border: 1px solid black;">' . $i . '</span>';
            } else {
                echo '<a href="?page=' . $i . '" style="color: black; text-decoration: none; margin: 0 5px; display: inline-block; width: 30px; height: 30px; line-height: 30px; text-align: center; border-radius: 50%; border: 1px solid black;">' . $i . '</a>';
            }
        }
        
        if ($currentPage < $totalPages) {
            echo '<a href="?page=' . ($currentPage + 1) . '" style="color: black; text-decoration: none; margin: 0 5px; display: inline-block; padding: 5px 10px; border-radius: 15px; border: 1px solid black;">Next</a>';
            echo '<a href="?page=' . $totalPages . '" style="color: black; text-decoration: none; margin: 0 5px; display: inline-block; padding: 5px 10px; border-radius: 15px; border: 1px solid black;">Last</a>';
        }
        echo '</div>';

    } else {
        echo "<p>No ideas found or there was an error fetching the ideas.</p>";
    }
    ?>



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