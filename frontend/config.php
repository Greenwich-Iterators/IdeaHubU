<?php

$server = "localhost";
$user = "root";
$pass = "";
$database = "unideahub";

$conn = mysqli_connect($server, $user, $pass, $database);

if (!$conn) {
    die("Connection Failed:" . mysqli_connect_error());
}
