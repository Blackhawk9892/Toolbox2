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

if($_GET['id']){
  $id = $_GET['id'];

}

$query = "SELECT * ";
$query .= "FROM script ";
$query .= "WHERE script_index  = '{$id}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed emp: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);

$script_template = $row['script_template'];

echo "<h3>$script_template <?h3>"

  ?>

<br>

</body>