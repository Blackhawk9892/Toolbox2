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
        <style>
.container {
  display: flex;
  background-color: DodgerBlue;
}

.container div {
  background-color: #f1f1f1;
  margin: 10px;
  padding: 20px;
  font-size: 24px;
}
</style>
        <div>
            <?php
            require("includes/connection.php");
            require("includes/functions.php");
            require("toolbar_sales.php");
            require("includes/database_rows.php");

          echo "<center><h1>Manager's Work Sheet</h1></center>";

      
          
          
           if (isset($_POST['back'])) {
            header("Location: points.php");
            exit;
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



       if(isset($_GET['find'])){
           $_SESSION['find'] = $_GET['find'];

        }
        
        $find = $_SESSION['find'];

       
        
        $query = "SELECT * ";
        $query .= "FROM recording ";
        $query .= "WHERE record_cust_data = '{$find}' ";
        $query .= "ORDER BY record_index";

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $record_empl_name = $row['record_empl_name'];
            $record_empl_num = $row['record_empl_num'];    
            $record_script = $row['record_script'];
            $record_vioce = $row['record_vioce'];
            $record_tone = $row['record_tone'];
            $_SESSION['employee'] = $record_empl_name;

            
        

           $bid_satus = 'photos';


             $rows[] = "\n<div id=\"$bid_satus\"><table><tr><td>$record_empl_name</td></tr> <td>$record_script</td></tr> <td>Tone to be used: $record_tone</td></tr></table> </div>";
                   
             $rows[] =" <audio controls>\n";
             $rows[] = "  <source src=\" $record_vioce \" type=\"audio/mpeg\">\n";
             $rows[] = "      Your browser does not support the audio element.\n";
  
             $rows[] = "      </audio>\n";

        }

            if(isset($_SESSION['employee'])){
            $employee = $_SESSION['employee'];
            echo "<center><h1>For Employee: $record_empl_name</h1></center>";
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
          
                $quantity = 0;
                if($_POST['seminar'] > 0){
                    $quantity =  $_POST['seminar'] + $quantity ;
                    $scrip .= " Went to sales seminar or training: " . $_POST['seminar'];
                }else{
                    $quantity = 0;
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
      */
        
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

                     
                      $query = "SELECT * ";
                      $query .= "FROM customer_data ";
                      $query .= "WHERE cust_find = '{$find}' ";
                     
                      $result_set = mysqli_query($con, $query)
                         or die('Query failed: ' . mysql_error());

                      $row = mysqli_fetch_array($result_set); // start while

                      $cust_points = $row['cust_points'];
                      $id = $row['cust_id']; 
                    

                      $newPoints = $cust_points + $quantity;

                     /////////////////////////////////////////////Update Points/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE customer_data SET cust_points = '$newPoints'
                                         WHERE cust_id = '$id' ");
                  

                    $value = "Record has been update points add: " . $quantity  . " Total points: " . $newPoints;
                    echo "<div class=\"errors\">$value</div>";

                    $_POST['scrip'] = '';
                    $_POST['quantity'] = 0;

                    
                   
                   
         
        }


       }
     


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $query = "SELECT * ";
        $query .= "FROM selceted_vehicles ";
        $query .= "WHERE sv_find = '{$find}' ";
      
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        $row = mysqli_fetch_array($result_set);

            $sv_stock = $row['sv_stock'];
            $sv_year = $row['sv_year'];    
            $sv_make = $row['sv_make'];
            $sv_model = $row['sv_model'];
            $sv_trim = $row['sv_trim'];
            $sv_reason = $row['sv_reason'];
            $sv_miles = $row['sv_miles'];
            $vechicle1 = $sv_year . ' ' . $sv_make . ' ' . $sv_model . ' ' . $sv_trim ;
               $sv_stock_sec = $row['sv_stock_sec'];
            $sv_year_sec = $row['sv_year_sec'];    
            $sv_make_sec = $row['sv_make_sec'];
            $sv_model_sec = $row['sv_model_sec'];
            $sv_trim_sec = $row['sv_trim_sec'];
            $sv_reason_sec = $row['sv_reason_sec'];
            $sv_miles_sec = $row['sv_miles_sec'];
   
             $vechicle2 = $sv_year_sec . ' ' . $sv_make_sec . ' ' . $sv_model_sec . ' ' . $sv_trim_sec ;

             $query1 = "SELECT * ";
             $query1 .= "FROM reason ";
             $query1 .= "WHERE reason_find= '{$find}' ";
      
              $result_set1 = mysqli_query($con, $query1)
                or die('Query failed: ' . mysql_error());

             $row1 = mysqli_fetch_array($result_set1);

            $reason_primary = $row1['reason_primary'];
            $reason_secondary = $row1['reason_secondary'];    
           
    echo "<div class=container>";
     
         echo "<div>";
               echo  "<h3>Primary Driver</h3>";
               echo "<p>$reason_primary</p>";
                echo  "<h3>Secondary Driver</h3>";
                echo "<p>$reason_secondary</p>";


         echo "</div>";
         
    echo "</div>";

     echo "<div class=container>";
     
        echo "<div>";
            echo "<h3>First Vehicle</h3>";
            echo "<h4>Stock Number: $sv_stock</h4>";
             echo "<h4>Vehicle: $vechicle1</h4>";
            echo "<h4>Miles: $sv_miles</h4>";
            echo "<h4>Reason: $sv_reason</h4>";
        echo "</div>";

        echo "<div>";
         echo "<h3>Second Vehicle</h3>";
           echo "<h4>Stock Number: $sv_stock_sec</h4>";
             echo "<h4>Vehicle: $vechicle2</h4>";
            echo "<h4>Miles: $sv_miles_sec</h4>";
            echo "<h4>Reason: $sv_reason_sec</h4>";
         echo "</div>";

      
         
    echo "</div>";

?>

 
            <form action="work_sheet.php" method="post">


 <center>
          
                        
          <br />
                
                     <br />
                
                    <input type="submit" name="back" value="Back"/>
                    <br />


         
                   <?php
           
echo "</center>";
           echo "<h1>Recording</h1></center>";

                $result = count($rows);
               
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
                ?>

