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
table, th, td {
  border: 1px solid;
}
</style>
</head>
<body>

<?php
$_SESSION['video'] = "home";

require("includes/database_rows.php");

if(empty($_COOKIE["userId"]) or (!isset($_COOKIE["userId"]))){
  $_SESSION['message'] = "Not a valid employee";

 header("Location: index.php");
  exit;
}

require("toolbar_sales.php");

if(isset($_SESSION['message'])){
  $message = $_SESSION['message'];
  echo "<h2>$message</h2>";
  unset($_SESSION['message']);
}




if(isset($_COOKIE["userId"])){
  $userId = $_COOKIE["userId"];
 
$emp_arry = Employee($userId);
$first = $emp_arry[0];
$last = $emp_arry[1];      
$position = $emp_arry[2];
$emp_id = $emp_arry[3];
$dealer_id = $emp_arry[4];
$name = $first . ' ' . $last;
}

$company = "BOUCHER";

$investment_1 = "2,000";
$investment_2 = "3,000";
$investment_4 = "4,000";

$retal36_1 = "820";
$retal36_2 = "790";
$retal36_3 = "750";


$retal48_1 = "660";
$retal48_2 = "630";
$retal48_3 = "600";

$retal54_1 = "610";
$retal54_2 = "580";
$retal54_3 = "550";


/////////////////////////////////////////////////////////////////////////////////////////////



  ?>


</body>