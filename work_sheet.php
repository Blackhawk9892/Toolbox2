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
                if(isset($_POST['scrip'])){
                    $scrip .= replace_apostrophes($_POST['scrip']);
                   // $scrip = replace_apostrophes($_POST['scrip']);
                }else{
                    $scrip = 'No Improvements';
                }

                if(isset($_POST['seminar'])){
                    $quantity = $_POST['seminar'];
                    $scrip .= "<br> Went to sales seminar or training.";
                }else{
                    $quantity = 0;
                }

                 if(isset($_POST['book'])){
                    $quantity = $_POST['book'] + $quantity;
                    $scrip .= "<br> Reading books to inprove sales career.";
                }

                 if(isset($_POST['day'])){
                    $quantity = $_POST['day'] + $quantity;
                    $scrip .= "<br> Did this program more then one time a day.";
                }

                 if(isset($_POST['voice'])){
                    $quantity = $_POST['voice'] + $quantity;
                    $scrip .= "<br> Improved voice inflection.";
                }

                  if(isset($_POST['product'])){
                    $quantity = $_POST['product'] + $quantity;
                    $scrip .= "<br> Improved product knowledge.";
                }

                 if(isset($_POST['other'])){
                    $quantity = $_POST['other'] + $quantity;

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


?>

 
            <form action="work_sheet.php" method="post">


 <center>
            <h3>Enter anything the salesperson is doing to improve their career. </h3>
          
        
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>
                <br />
                <br />
                         <label for="quantity">Reading Books To Inprove Sales Career (between 0 and 3):</label>
                         <input type="number" id="book" name="book" value=0 min="0" max="3">
                          <br />
                         <label for="quantity">Did this program more then one time a day (between 0 and 3):</label>
                         <input type="number" id="day" name="day" value=0 min="0" max="3">
                           <br />
                         <label for="quantity">Went To Sales Seminar Or Training  (between 0 and 3):</label>
                         <input type="number" id="seminar" name="seminar" value=0 min="0" max="3">
                            <br />
                         <label for="quantity">Improved Voice Inflection  (between 0 and 5):</label>
                         <input type="number" id="voice" name="voice" value=0 min="0" max="5">
                           <br />
                         <label for="quantity">Improved Product Knowledge  (between 0 and 3):</label>
                         <input type="number" id="product" name="product" value=0 min="0" max="3">

                          <br />
                         <label for="quantity">Other Writen In Description Above (between 0 and 3):</label>
                         <input type="number" id="other" name="other" value=0 min="0" max="3">
          <br />
                <br />
                    <input type="submit" name="submit" value="Submit"/>
                    <br />
                     <br />
                
                    <input type="submit" name="back" value="Back"/>
                    <br />
</center>
                   <?php
           
          

           echo "<h1>Recording</h1></center>";

                $result = count($rows);
               
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
                ?>

