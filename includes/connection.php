<?php

// Database connection settings
$host = "localhost";
$user = "root";
$password = "";
$database = "tools";

// Connect to MySQL
$con = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

// Check connection
if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

// Set character encoding
mysqli_set_charset($con, "utf8mb4");

?>