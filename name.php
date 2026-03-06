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



/* Create two columns/boxes that floats next to each other */
nav-item  {
  float: left;
  width: 20%;
  height: 100%; /* only for demonstration, should be removed */
  padding: 20px;
}

/* Style the list inside the menu */
nav ul {
  list-style-type: none;
  padding: 0;
}



/* Clear floats after the columns */
sectionMale::after {
  content: "";
  display: table;
  clear: both;
}

sectionFemail::after {
  content: "";
  display: table;
  clear: both;
}


}


}
</style>
</head>
<body>



<?php

require_once("includes/constants.php");
require("includes/connection.php");
require("includes/database_rows.php");
require("includes/functions.php");
require("toolbar_sales.php");




if(isset($_SESSION['errors'])){
  echo $_SESSION['errors'];
  unset($_SESSION['errors']);
}

echo "<br>";
echo "<br>";


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

if(isset($_GET['find'])){
  $_SESSION['find'] = $_GET['find'];

}

$find = $_SESSION['find'];




      $query = "SELECT * ";
      $query .= "FROM customer_data ";
      $query .= "WHERE cust_find  = '{$find}' ";

      $result_set = mysqli_query($con, $query)
              or die('Query failed emp: ' . mysqli_error($con));
      $row = mysqli_fetch_array($result_set);

      $cust_id = $row['cust_id'];
      $cust_male_photo = $row['cust_male_photo'];
      $cust_female_photo = $row['cust_female_photo'];
      $cust_points = $row['cust_points'];
      $_SESSION['cust_points'] = $row['cust_points'];
      $cust_male_name = $row['cust_male_name'];
      $cust_female_name = $row['cust_female_name'];

      

echo " <img src=\"$cust_male_photo\" width=\"300\" height=\"300\">Customer 1\n";
   echo "     \n";
   echo "\n";
   echo "\n";
   echo "    <img src=\"$cust_female_photo\" width=\"300\" height=\"300\">Customer 2";
   
   /////////////////////////////////////////////////////////////////////////////////////////////////////////

   $male = array();
   $female = array();


   $query = "SELECT * ";
   $query .= "FROM voice ";
   $query .= "ORDER BY voice_name ";


   $result_set = mysqli_query($con, $query)
           or die('Query failed emp: ' . mysqli_error($con));
 
   while($row = mysqli_fetch_array($result_set)){
   $voice_name = $row['voice_name'];
  
   $voice_gender = $row['voice_gender'];
  
     // $male[] =  " <input type=\"radio\" id=\"$voice_name\" name=\"fav_language\" value=\"$voice_name\">\n";
         //  $male[] =  "  <label for=\"$voice_name\">$voice_name</label><br>";

       if($voice_gender == 'Male'){
         $male[] =  " <input type=\"radio\" id=\"$voice_name\" name=\"Male\" value=\"$voice_name\">\n";
         $male[] =  "  <label for=\"$voice_name\">$voice_name</label><br>";
       
       }else{
        $female[] =  " <input type=\"radio\" id=\"$voice_name\" name=\"Female\" value=\"$voice_name\">\n";
        $female[] =  "  <label for=\"$voice_name\">$voice_name</label><br>";
       }
      }

      
if (isset($_POST['submit'])) {

  if(isset($_POST['Male'])){
    $Male = $_POST['Male'];
  }else{
    $errors[] = "Both a Male and Female name must be selected";
  }

  if(isset($_POST['Female'])){
    $Female = $_POST['Female'];

  }else{
    $errors[] = "Both a Male and Female name must be selected";
  }
 
  if (!empty($errors)) {
 
    foreach ($errors as $value) {
        $_SESSION['errors'] = "<div class=\"errors\">$value</div>";
       // $testPage = '/name.php';    // This is for Production
        $testPage = '/toolbox/toolbox2/name.php'; // This is for Testing
        $find = $_SESSION['find'];
       header("Location: $testPage?find=$find");
       exit;

    }
   } else {

    $malePoints = 0;
    $femalePoints = 0;
 

   if($cust_male_name == $Male){
      $malePoints = 3;
    }else{
      $malePoints = -3;
    }

 

    if($cust_female_name == $Female){
      $femalePoints = 3;
    }else{
      $femalePoints = -3;
    }
  
     $total = $malePoints + $femalePoints + $cust_points;



  mysqli_query($con, "UPDATE customer_data SET cust_points = '$total'
                  WHERE cust_id  = '$cust_id' ");


      $program = "select_vehicles.php";
      $testPage =  test_production($program);


$find = $_SESSION['find'];
header("Location: $testPage?find=$find");
exit;

  }
}
   ?>
 
   
</body>






<form action="name.php" method="post">

<br />
<br />
<h1><input   type="submit" name="submit" value="Submit"/></h1>
<br />
<br />

<sectionMale>
  <nav-item>
  <h2>Male Names </h2>
    <ul>
    <?php
              


              $result = count($male);
              $count = 0;

              while ($count < $result) {
                  $load = $male[$count];
                  echo $load;

                  $count++;
              }
              ?>
    </ul>
    </nav-item>

    <sectionFemale>
    <nav-item>

  <h2>Female Names </h2>
    <ul>
    <?php
              


              $result = count($female);
              $count = 0;

              while ($count < $result) {
                  $load = $female[$count];
                  echo $load;

                  $count++;
              }
              ?>
    </ul>
    </nav-item>


