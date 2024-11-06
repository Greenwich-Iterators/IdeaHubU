<?php
if (!isset($_COOKIE['auth_token'])) {
    header("Location: login.php");
    exit();
}

$token = $_COOKIE['auth_token'];
$url = 'http://localhost:9000/api/user/verifytoken';
$options = [
    'http' => [
        'header' => "Content-type: application/json\r\nAuthorization: Bearer $token\r\n",
        'method' => 'GET'
    ]
];
$context = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === FALSE) {
    header("Location: login.php");
    exit();
}

$response = json_decode($result, true);

if (!$response['valid']) {
    header("Location: login.php");
    exit();
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Process form submission
    $ideaTitle = htmlspecialchars($_POST["ideaTitle"]);
    $ideaDescription = htmlspecialchars($_POST["ideaDescription"]);
    $message = "Form submitted successfully! Idea Title: $ideaTitle";

    // Handle file upload if a file was selected
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $target_dir = "../uploads/";
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

        if ($fileType !== "pdf") {
            $message .= " However, only PDF files are allowed for upload.";
            $uploadOk = 0;
        }

        if ($_FILES["fileToUpload"]["size"] > 4000000) {
            $message .= " The selected file is too large. Maximum size is 4MB.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            $fileHash = hash_file('md5', $_FILES["fileToUpload"]["tmp_name"]);
            $target_file = $target_dir . $fileHash . ".pdf";

            if (file_exists($target_file)) {
                $message .= " A file with the same content already exists.";
            } else {
                $existingFiles = glob($target_dir . "*.pdf");
                foreach ($existingFiles as $file) {
                    if (hash_file('md5', $file) === $fileHash) {
                        $message .= " A file with the same content already exists.";
                        $uploadOk = 0;
                        break;
                    }
                }

                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $message .= " The file has been uploaded and saved as: " . htmlspecialchars(basename($target_file));
                    } else {
                        $message .= " Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        textarea {
            width: 100%;
            height: 150px;
        }
    </style>
</head>

<body>
    <h1>Welcome, logged in user!</h1>
    <p>Your user ID is: <?php echo htmlspecialchars($response['userId']); ?></p>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <label for="ideaTitle">Idea Title:</label><br>
        <input type="text" id="ideaTitle" name="ideaTitle" required><br><br>

        <label for="ideaDescription">Idea Description:</label><br>
        <textarea id="ideaDescription" name="ideaDescription" required></textarea><br><br>

        <label for="fileToUpload">Optional: Select a PDF to upload (max 4MB):</label><br>
        <input type="file" name="fileToUpload" id="fileToUpload"><br><br>

        <input type="submit" value="Submit Idea" name="submit">
    </form>

    <?php
    if (!empty($message)) {
        echo "<p>$message</p>";
    }
    ?>
</body>

</html>
