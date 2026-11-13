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

//////////////////////////////////////////Add Points??????????????????????????????????????????????????????????????????
$year = date("Y");
$yearMonth = date("Y/m");
 $d = strtotime("last Sunday");
$lastSunday =  date("Y/m/d", $d);

$points = 0;
          $query = "SELECT * ";
           $query .= "FROM employee ";
 


           $result_set = mysqli_query($con, $query)
             or die('Query failed emp: ' . mysqli_error($con));

           while($row = mysqli_fetch_array($result_set)){
             $emp_dealer_id = $row['emp_dealer_id'];
             $emp_dealer_group = $row['emp_dealer_group'];
             $emp_id = $row['emp_id'];
             $emp_position = $row['emp_position'];
             $emp_first_name = $row['emp_first_name'];
             $emp_last_name = $row['emp_last_name'];
             $empName = $emp_first_name . '  ' . $emp_last_name;
             $emp_assigned_man_name = $row['emp_assigned_man_name'];
             $emp_assigned_man_num = $row['emp_assigned_man_num'];
             $emp_man_points = $row['emp_man_points'];
             $emp_emp_points = $row['emp_emp_points'];
             $emp_evaluation_manager = $row['emp_evaluation_manager'];
             


             

             if($emp_position == 'PFD'){
                continue;
             }

              if($emp_position == 'GM'){
                continue;
             }
              if($emp_position == 'Corporate'){
                continue;
             }

              if($emp_position == 'Manager'){
                $managerPoints = 0;
              $countDiv = 0;
                  $query3 = "SELECT * ";
           $query3 .= "FROM employee ";
           $query3 .= "WHERE emp_assigned_man_num   = '{$emp_id}' ";
        

           $result_set3 = mysqli_query($con, $query3)
             or die('Query failed emp 68: ' . mysqli_error($con));

           while($row3 = mysqli_fetch_array($result_set3)){
            $emp_point_date = $row3['emp_point_date'];            
             $emp_man_points = $row3['emp_man_points'];
             $managerPoints = $managerPoints + $emp_man_points;
              $countDiv++;
           }
          
             $managerPoints = $managerPoints / $countDiv;

             $sql = "INSERT INTO points(points_dealer_group,  points_dealer_id, points_sales_name, points_sales_num, points_man_name, points_man_num, points_position, points_year_month, points_year, points_manager) 
              VALUES('$emp_dealer_group','$emp_dealer_id','$empName','$emp_id','$emp_assigned_man_name','$emp_assigned_man_num','$emp_position','$yearMonth','$year','$managerPoints')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error reset_manager 87: ' . mysqli_error($con));
                    }
         }
              
             if($emp_position == 'Sales'){
                

                
                $d = strtotime("last Sunday");
                $lastSunday =  date("Y/m/d", $d);

      /////////////////////////////////////////////get data for customer_data//////////////////////////////////////
      $saveDate = ' ';
                 $query2 = "SELECT * ";
           $query2 .= "FROM customer_data ";
           $query2 .= "WHERE cust_salesperson_num   = '{$emp_id}' ";
           $query2 .= "ORDER BY cust_date  ASC ";


           $result_set2 = mysqli_query($con, $query2)
             or die('Query failed emp: ' . mysqli_error($con));

           while($row2 = mysqli_fetch_array($result_set2)){
             $cust_date = $row2['cust_date'];
  

              $d = strtotime($cust_date);
               $cust_date = date("Y/m/d", $d);


              if($saveDate != $cust_date ){
                $saveDate = $cust_date;

             $d = strtotime("last Sunday");
               $lastSunday =  date("Y/m/d", $d);

              if($cust_date > $lastSunday){
                  $cust_points = $row2['cust_points'];
                  $points = $points + $cust_points;
              }

             }

           }

              if($emp_point_date == "0000-00-00"){
                $emp_emp_points = 0;
              }
              $total = $points + $emp_emp_points;
              $sql = "INSERT INTO points(points_dealer_group,  points_dealer_id, points_sales_name, points_sales_num, points_man_name, points_man_num, points_position, points_year_month, points_year, points_system, points_employee, points_total) 
              VALUES('$emp_dealer_group','$emp_dealer_id','$empName','$emp_id','$emp_assigned_man_name','$emp_assigned_man_num','$emp_position','$yearMonth','$year','$points','$emp_emp_points','$total')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error reset_manager 128: ' . mysqli_error($con));
                    }
                    }

                     mysqli_query($con, "UPDATE employee SET emp_emp_points = '0'
                                         WHERE emp_id = '$emp_id' ");

                    mysqli_query($con, "UPDATE employee SET emp_man_points = '0'
                                         WHERE emp_id = '$emp_id' ");

                    mysqli_query($con, "UPDATE employee SET emp_point_date = '0000-00-00'
                                         WHERE emp_id = '$emp_id' ");

              $points = 0;
        }

     
/////////////////////////////////////////////////////////////////////////////////////////////////////

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

             $query3 = "SELECT * ";
           $query3 .= "FROM employee ";
           $query3 .= "WHERE emp_id   = '{$manager_id}' ";


           $result_set3 = mysqli_query($con, $query3)
             or die('Query failed emp: ' . mysqli_error($con));
           $row3 = mysqli_fetch_array($result_set3);

             $emp_first_name = $row3['emp_first_name'];
             $emp_last_name = $row3['emp_last_name'];
             $managerName = $emp_first_name . ' ' . $emp_last_name;

             mysqli_query($con, "UPDATE employee SET emp_assigned_man_num = '$manager_id'
              WHERE emp_id  = '$value' ");

             mysqli_query($con, "UPDATE employee SET emp_assigned_man_name = '$managerName'
              WHERE emp_id  = '$value' "); 
              
       
     }




} 

 ?>