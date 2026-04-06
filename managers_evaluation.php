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

          echo "<center><h1>Manager's Evaluation</h1></center>";

      
          

        
          
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

          $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_id = '{$emp_id}' ";
      

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        $row = mysqli_fetch_array($result_set); 

            $emp_assigned_man_num = $row['emp_assigned_man_num'];
            $emp_assigned_man_name = $row['emp_assigned_man_name'];
            $emp_emp_points = $row['emp_emp_points'];


         if (isset($_POST['Training'])) {
                     $query = "SELECT * ";
                     $query .= "FROM employee ";
                     $query .= "WHERE emp_dealer_id = '{$dealer_id}' ";
      
       
                     $result_set = mysqli_query($con, $query)
                       or die('Query failed: ' . mysql_error());

             while ($row = mysqli_fetch_array($result_set)) {

                  $empPoints = $row['emp_emp_points'];
                  if($empPoints == 0){
                    continue;
                  }
                  $emp_point_array[] = $empPoints;
            }
                    
                    shuffle($emp_point_array);
                                     
                    $points = $emp_point_array[0];
                    $emp_emp_points = $points;
                     mysqli_query($con, "UPDATE employee SET emp_emp_points = '{$points}'
                                         WHERE emp_id = '$emp_id' ");
                    $value = "You received a random selection of points based on all the other salespeople. Your manager received 0 points.";
                     echo "<div class=\"errors\">$value</div>";


        }
         

         echo " <center><h3>Please evaluate $emp_assigned_man_name training this week. </h3>";


            ////////////////////////////////////Submit//////////////////////////////////////////////////////////////////////////
        

             if(isset($_POST['submit'])){
                    $scrip = '';
          /*
                if(isset($_POST['scrip'])){                   
                     $scrip .= replace_apostrophes($_POST['scrip']) ; 
                    $scrip .= "<br>" ;     
                }*/
          
                $quantity = 0;
                if($_POST['enthusiastic'] > 0){
                    $quantity =  $_POST['enthusiastic'] + $quantity ;
                    $scrip .= "  Was the instructor enthusiastic: " . $_POST['enthusiastic'] ;
                    $scrip .= "<br>" ; 
                }else{
                    $quantity = 0;
                }

                if($_POST['overall'] > 0){
                    $quantity = $_POST['overall'] + $quantity;
                    $scrip .= " Overall, how would you rate the training instructor: " . $_POST['overall'] ;
                    $scrip .= "<br>" ; 
                    
                }

                 if($_POST['quality'] > 0){
                    $quantity = $_POST['quality'] + $quantity;
                    $scrip .= " How would you specifically rate the teaching quality of the instructor: " . $_POST['quality'] ;
                    $scrip .= "<br>" ; 
                }

                 if($_POST['topic'] > 0){
                    $quantity = $_POST['topic'] + $quantity;
                    $scrip .= " Was the instructor knowledgeable on the topic: " . $_POST['topic'];
                    $scrip .= "<br>" ; 
                }

                 if($_POST['understand'] > 0){
                    $quantity = $_POST['understand'] + $quantity;
                    $scrip .= " Was the instructor easy to understand; " . $_POST['understand'];
                    $scrip .= "<br>" ; 
                }

                  if($_POST['material'] > 0){
                    $quantity = $_POST['material'] + $quantity;
                    $scrip .= " Did the instructor provide contextual examples of how to put the training material into practice: " . $_POST['material'];
                    $scrip .= "<br>" ; 
                }

                 if($_POST['organized'] > 0){
                    $quantity = $_POST['organized'] + $quantity;
                    $scrip .= " Was the instructor prepared and organized: " . $_POST['organized'];
                    $scrip .= "<br>" ; 
                }

                 if($_POST['clear'] > 0){
                    $quantity = $_POST['clear'] + $quantity;
                    $scrip .= " Did the instructor provide clear instructions throughout the lesson: " . $_POST['clear'];
                   // $scrip .= replace_apostrophes($_POST['scrip']);
                  //  $scrip .= "<br>" ; 
                 }

                 if($_POST['feedback'] > 0){
                    $quantity = $_POST['feedback'] + $quantity;
                    $scrip .= "  Feedback on this specific instructor and what they could have done more effectively: " . $_POST['feedback'] . '/';
                    $scrip .= replace_apostrophes($_POST['scrip']);
                    $scrip .= "<br>" ; 
                }else{
                     $scrip .= ' No other Improvements ';
                     $scrip .= "<br>" ; 
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
                  
                
                $find = "Employee Evaluation";
                  $date = date("Y-m-d");

               
            
                    $sql = "INSERT INTO employee_notes(notes_emp_num, notes_emp_name, notes_date, notes_manager_num, notes_manager_name, notes_improvement, notes_points, notes_find) 
              VALUES('$emp_id','$name','$date','$emp_assigned_man_num','$emp_assigned_man_name','$scrip','$quantity','$find')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error employee 138: ' . mysqli_error($con));
                    }

                     /////////////////////////////////////////////Update Points/////////////////////////////////////////////////////////////////
                  
                    mysqli_query($con, "UPDATE employee SET emp_emp_points = '$quantity'
                                         WHERE emp_id = '$emp_id' ");
                  

                    $value = "Record has been update points add: " . $quantity  ;
                    echo "<div class=\"errors\">$value</div>";

                    $_POST['scrip'] = '';
                    $_POST['quantity'] = 0;

                    
                   
                   
         
        }


       }
   
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
?>

 
            <form action="managers_evaluation.php" method="post">



            
           
          <br />
        
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>
                <br />
                <br />
                         <h3><label for="quantity">Overall, how would you rate the training instructor? (between 0 and 10):</label>
                         <input type="number" id="overall" name="overall" value=1 min="0" max="10">
               
                         <label for="quantity">How would you specifically rate the teaching quality of the instructor?(between 0 and 10):</label>
                         <input type="number" id="quality" name="quality" value=1 min="0" max="10">
                          <br />
                         <label for="quantity">Was the instructor knowledgeable on the topic?(between 0 and 10):</label>
                         <input type="number" id="topic" name="topic" value=1 min="0" max="10">
                           <br />
                         <label for="quantity">Was the instructor enthusiastic?(between 0 and 10):</label>
                         <input type="number" id="enthusiastic" name="enthusiastic" value=1 min="0" max="10">
                            <br />
                         <label for="quantity">Was the instructor easy to understand?(between 0 and 10):</label>
                         <input type="number" id="understand" name="understand" value=1 min="0" max="10">
                           <br />
                         <label for="quantity">Did the instructor provide contextual examples of how to put the training material into practice?(between 0 and 10):</label>
                         <input type="number" id="material" name="material" value=1 min="0" max="10">

 <br />
                         <label for="quantity">Was the instructor prepared and organized? (between 0 and 10):</label>
                         <input type="number" id="organized" name="organized" value=1 min="0" max="10">
  <br />
                          <label for="quantity">Did the instructor provide clear instructions throughout the lesson?(between 0 and 10):</label>
                         <input type="number" id="clear" name="clear" value=1 min="0" max="10">
<br />
                          <label for="quantity">Do you have any other feedback on this specific instructor and what they could have done more effectively?(between 0 and 10):</label>
                         <input type="number" id="feedback" name="feedback" value=1 min="0" max="10"></h3>
                          <br />
                          <br />
                         <?php
                       // $checkDay = date('l');
                       $checkDay = "Saturday";
                      // $emp_emp_points = 0;
              
                        if($checkDay == "Saturday" and $emp_emp_points == 0){

                                echo "  <input type=\"submit\" name=\"Training\" value=\"No Training\"/>";
                        }
                        echo "<br>";
                        echo "<br>";
                         if($emp_emp_points == 0){

                                echo "  <input type=\"submit\" name=\"submit\" value=\"Submit\"/>";
                        }
                     
                     
                       ?>
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

