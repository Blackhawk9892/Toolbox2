<?php
// Enable error reporting and display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

function sanitize_input($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Function to sanitize output data
function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Handle video selection securely
$video = isset($_GET['video']) ? $_GET['video'] : 'none';

// Output HTML content with proper encoding
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
  <style>
    body  {
      background-image: url("images/backround.jpg");
      height: 850px;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      position: relative;
    }
  
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2" style="background-color:black;">
      <a style="color:yellow;" class="nav-link" href="emailform.php"><h4>Contact Us</h4></a>
    </div>
    <div class="col-sm-3" style="background-color:black;"></div>
    <div class="col-sm-5" style="background-color:black;">
      <h3 style="color:yellow;">Sales Tool Box</h3>
    </div>
    <div class="col-sm-2" style="background-color:black;">
      <a style="color:yellow;" class="nav-link" href="signIn.php"><h4>Sign In</h4></a>
    </div>
  </div>
</div>
<br>

<div class="row">
  <div class="col-sm-4">
    <div class="col-sm-4">
      <br>
      
    

 
</div>

</body>
</html>
