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
</head>
<body>



<?php

require_once("includes/constants.php");
require("includes/connection.php");



// loop 1
$query = "SELECT * ";
$query .= "FROM company ";


$result_set = mysqli_query($con, $query)
        or die('Query failed emp: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $comp_id  = $row['comp_id'];
  $employee_array = array();
  $manager_array = array();

       // loop 2
           $query2 = "SELECT * ";
           $query2 .= "FROM employee ";
           $query2 .= "WHERE emp_dealer_id   = '{$comp_id}' ";


           $result_set2 = mysqli_query($con, $query2)
             or die('Query failed emp: ' . mysqli_error($con));
           while($row2 = mysqli_fetch_array($result_set2)){
             $emp_id = $row2['emp_id'];
             $emp_position = $row2['emp_position'];
             $emp_position = $row2['emp_position'];

             if($emp_position == 'Sales'){
                $employee_array[] = $emp_id;
             }

              if($emp_position == 'Manager'){
                $manager_array[] = $emp_id;
             }
     }
     $arrayCount = count($manager_array) - 1;
     shuffle($employee_array);
     shuffle($manager_array);
    $count = 0;
     foreach ($employee_array as $value) {

            $manager_id = $manager_array[$count];
        

            if($count == $arrayCount){
                $count = 0;
            }else{
                $count++;
            }

             $query2 = "SELECT * ";
           $query2 .= "FROM employee ";
           $query2 .= "WHERE emp_id   = '{$manager_id}' ";


           $result_set2 = mysqli_query($con, $query2)
             or die('Query failed emp: ' . mysqli_error($con));
           $row2 = mysqli_fetch_array($result_set2);

             $emp_first_name = $row2['emp_first_name'];
             $emp_last_name = $row2['emp_last_name'];
             $managerName = $emp_first_name . ' ' . $emp_last_name;

             mysqli_query($con, "UPDATE employee SET emp_assigned_man_num = '$manager_id'
              WHERE emp_id  = '$value' ");

             mysqli_query($con, "UPDATE employee SET emp_assigned_man_name = '$managerName'
              WHERE emp_id  = '$value' "); 
              
       
     }




} 

 ?>