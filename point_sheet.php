<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<!--
Add Dealer to stock tag program
-->
<html>
    <head>
        <title>Add Employee</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
    
        <div>
            <?php
            require("includes/connection.php");
            require("includes/functions.php");
            require("toolbar_sales.php");
            require("includes/database_rows.php");

          echo "<center><h1>Manager's Work Sheet</h1></center>";

      
          
          
           if (isset($_POST['back'])) {
            header("Location: employee_points.php");
            exit;
        }

         if(isset($_GET['employee'])){
          $_SESSION['employee'] = $_GET['employee'];
          $employee = $_SESSION['employee'];
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


            ////////////////////////////////////Submit//////////////////////////////////////////////////////////////////////////
        

             if(isset($_POST['submit'])){
                    $scrip = '';
          /*
                if(isset($_POST['scrip'])){                   
                   $scrip .= 'No other Improvements';              
                }else{
                    $scrip .= replace_apostrophes($_POST['scrip']); 
                                     
                }
          */
                $quantity = 0;
                if($_POST['seminar'] > 0){
                    $quantity =  $_POST['seminar'] + $quantity ;
                    $scrip .= " Went to sales seminar or training: " . $_POST['seminar'];
                }else{
                    $quantity = 0;
                }

                if($_POST['promotion'] > 0){
                    $quantity = $_POST['promotion'] + $quantity;
                    $scrip .= " Self promotion: " . $_POST['promotion'];
                }

                 if($_POST['book'] > 0){
                    $quantity = $_POST['book'] + $quantity;
                    $scrip .= " Reading books to inprove sales career: " . $_POST['book'];
                }

                 if($_POST['day'] > 0){
                    $quantity = $_POST['day'] + $quantity;
                    $scrip .= " Did this program more then one time a day: " . $_POST['day'];
                }

                 if($_POST['voice'] > 0){
                    $quantity = $_POST['voice'] + $quantity;
                    $scrip .= " Improved voice inflection; " . $_POST['voice'];
                }

                  if($_POST['product'] > 0){
                    $quantity = $_POST['product'] + $quantity;
                    $scrip .= " Improved product knowledge: " . $_POST['product'];
                }

                 if($_POST['vehicle'] > 0){
                    $quantity = $_POST['vehicle'] + $quantity;
                    $scrip .= " Good selection of vehicle: " . $_POST['vehicle'];
                }

                 if($_POST['drive'] > 0){
                    $quantity = $_POST['Drive'] + $quantity;
                    $scrip .= " Go on test drive with customer: " . $_POST['drive'];
                    $scrip .= replace_apostrophes($_POST['scrip']);
                 }

                 if($_POST['other'] > 0){
                    $quantity = $_POST['other'] + $quantity;
                    $scrip .= " Other points have been added: " . $_POST['other'];
                    $scrip .= replace_apostrophes($_POST['scrip']);
                }else{
                     $scrip .= ' No other Improvements ';
                }
      
        
        if (isset($errors)) {

       foreach ($errors as $value) {
                            echo "<div class=\"errors\">$value</div>";
                        }      
  
        }else{
                  if(isset($_COOKIE["userId"])){
            $userId = $_COOKIE["userId"];
        
         

        $emp_arry = Employee($userId);
        $mFirst = $emp_arry[0];
        $mLast = $emp_arry[1];      
        $mPosition = $emp_arry[2];
        $mEmp_id = $emp_arry[3];
        $mDealer_id = $emp_arry[4];
       
    }
        $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_id = '{$mEmp_id}' ";
       

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        $row = mysqli_fetch_array($result_set); 

            $emp_assigned_man_num = $row['emp_assigned_man_num'];
            $emp_assigned_man_name = $row['emp_assigned_man_name'];    
                  
                
                  $find = $_SESSION['find'];
                  $date = date("Y-m-d");

               
            
                    $sql = "INSERT INTO employee_notes(notes_emp_num, notes_emp_name, notes_date, notes_manager_num, notes_manager_name, notes_improvement, notes_points, notes_find) 
              VALUES('$emp_id','$name','$date','$emp_assigned_man_num','$emp_assigned_man_name','$scrip','$quantity','$find')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error employee 138: ' . mysqli_error($con));
                    }

                     /////////////////////////////////////////////Update Points/////////////////////////////////////////////////////////////////
                    $employee = $_SESSION['employee'];
                    mysqli_query($con, "UPDATE employee SET emp_emp_points = '$quantity'
                                         WHERE emp_id = '$employee' ");
                  

                    $value = "Record has been update points add: " . $quantity  ;
                    echo "<div class=\"errors\">$value</div>";

                    $_POST['scrip'] = '';
                    $_POST['quantity'] = 0;

                    
                   
                   
         
        }


       }
     $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_id = '{$employee}' ";
       

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        $row = mysqli_fetch_array($result_set); 

            $emp_first_name = $row['emp_first_name'];
            $emp_last_name = $row['emp_last_name'];
            $empName = $emp_first_name . ' ' . $emp_last_name;

         echo " <center><h3>Enter anything $empName is doing to improve their career. </h3>";
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
?>

 
            <form action="point_sheet.php" method="post">



            
           
          <br />
        
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>
                <br />
                <br />
                         <h3><label for="quantity">Self promotion write description below (between 0 and 20):</label>
                         <input type="number" id="promotion" name="promotion" value=1 min="0" max="20"></h3>
                <br /><h3>
                         <label for="quantity">Reading books to improve sales Careers (between 0 and 10):</label>
                         <input type="number" id="book" name="book" value=1 min="0" max="10">
                          <br />
                         <label for="quantity">Did this program more than one time a day (between 0 and 10):</label>
                         <input type="number" id="day" name="day" value=1 min="0" max="10">
                           <br />
                         <label for="quantity">Went to sales seminars or training  (between 0 and 10):</label>
                         <input type="number" id="seminar" name="seminar" value=1 min="0" max="10">
                            <br />
                         <label for="quantity">Improved voice inflection  (between 0 and 10):</label>
                         <input type="number" id="voice" name="voice" value=1 min="0" max="10">
                           <br />
                         <label for="quantity">Improved product knowledge  (between 0 and 10):</label>
                         <input type="number" id="product" name="product" value=1 min="0" max="10">

 <br />
                         <label for="quantity">Good selection of vehicles  (between 0 and 10):</label>
                         <input type="number" id="vehicle" name="vehicle" value=1 min="0" max="10">
  <br />
                          <label for="quantity">Go with customer on test Drives (between 0 and 10):</label>
                         <input type="number" id="drive" name="drive" value=1 min="0" max="10">
<br />
                          <label for="quantity">Other write description above (between 0 and 10):</label>
                         <input type="number" id="other" name="other" value=1 min="0" max="10"></h3>
                          <br />
                        
          <br />
                <br />
                    <input type="submit" name="submit" value="Submit"/>
                    <br />
                     <br />
                
                    <input type="submit" name="back" value="Back"/>
                    <br />


         
                   <?php
  /*         
echo "</center>";
           echo "<h1>Recording</h1></center>";

                $result = count($rows);
               
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
                    */
                ?>

