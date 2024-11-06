<?php
$uploadOk = 1;
$fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

// Check if file is a PDF based on extension
if (isset($_POST["submit"])) {
    if ($fileType !== "pdf") {
        echo "File does not have a PDF extension.";
        $uploadOk = 0;
    }
}

// Check file size (4MB limit)
if ($_FILES["fileToUpload"]["size"] > 4000000) {
    echo "Sorry, your file is too large. Maximum size is 4MB.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    // Prepare the file data
    $fileData = file_get_contents($_FILES["fileToUpload"]["tmp_name"]);

    // Prepare the request
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW',
            ],
            'content' => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\n" .
                "Content-Disposition: form-data; name=\"file\"; filename=\"" . $_FILES["fileToUpload"]["name"] . "\"\r\n" .
                "Content-Type: " . $_FILES["fileToUpload"]["type"] . "\r\n\r\n" .
                $fileData . "\r\n" .
                "------WebKitFormBoundary7MA4YWxkTrZu0gW--\r\n"
        ]
    ];

    $context = stream_context_create($options);

    // Send the request
    $response = file_get_contents("http://localhost:9000/api/uploadthing", false, $context);

    // Check for errors
    if ($response === FALSE) {
        echo "Upload failed: " . error_get_last()['message'];
    } else {
        $httpCode = $http_response_header[0];
        if (strpos($httpCode, "200") !== false) {
            echo "The file " . htmlspecialchars($_FILES["fileToUpload"]["name"]) . " has been uploaded successfully.";
        } else {
            echo "Upload failed. Server responded with: " . $httpCode;
        }
    }
}
?>