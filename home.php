<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Customer Interduction</title>
  <style>
            body  {
                background-image: url("photos/black backround");
                height: 900px;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
            }
            <style>
.center {
  margin: auto;
 width: 10%;
  border: 1px solid #73AD21;

}
        </style>
</head>
<body>

<?php


require("includes/database_rows.php");
require("toolbar_sales.php");

if(isset($_COOKIE["userId"])){
  $userId = $_COOKIE["userId"];
 
$emp_arry = Employee($userId);
$first = $emp_arry[0];
$last = $emp_arry[1];      
$position = $emp_arry[2];
$emp_id = $emp_arry[3];
$dealer_id = $emp_arry[4];
$name = $first . ' ' . $last;
}else{
  header("Location: index.php");
  exit;
  $_SESSION['message'] = "Invalid user";
}

  ?>

<br>
<div class="center">
<img src="photos/delivery.webp" alt="Customer Delivery" width="100%" height="100%">
</div>
</body>