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

          echo "<center><h1>Manager's Training Plan</h1></center>";

             $d = strtotime("now");
    $today = date("Y-m-d", $d);
      
          
          
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
        if(isset($_SESSION['find'])){
            $find = $_SESSION['find'];
        }
        

       
        $employeeNum = $_SESSION['employeeNum'];
        $query = "SELECT * ";
        $query .= "FROM evaluation ";
        $query .= "WHERE eval_empl_num = '{$employeeNum}' ";
        $query .= "ORDER BY eval_index";

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $eval_manger_name = $row['eval_manger_name'];
            $eval_manager_notes = $row['eval_manager_notes'];    
            $eval_empl_name = $row['eval_empl_name'];
            $eval_date_manager = $row['eval_date_manager'];
           
           

            
        

           $bid_satus = 'photos';


             $rows[] = "\n<div id=\"$bid_satus\"><table><tr><td>Manger Name: $eval_manger_name</td></tr> <td>Employee Name: $eval_empl_name</td></tr> <td>Date: $eval_date_manager</td></tr></table> </div>";

             
             $rows[] = "<h3>$eval_manager_notes</h3>";

        }

            if(isset($_SESSION['employeeName'])){
            $employee = $_SESSION['employeeName'];
            echo "<center><h1>For Employee: $employee</h1></center>";
            }

            ////////////////////////////////////Submit//////////////////////////////////////////////////////////////////////////
        

             if(isset($_POST['submit'])){
              
            
             if(isset($_POST['notes'])){
                $notes = $_POST['notes'];
             }else{
                $errors[] = "Notes are empty";
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
        $query .= "WHERE emp_id = '{$emp_id}' ";
       

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        $row = mysqli_fetch_array($result_set); 

            $emp_dealer_id = $row['emp_dealer_id'];
            $emp_dealer_name = $row['emp_dealer_name'];
            $emp_first_name = $row['emp_first_name'];
            $emp_last_name = $row['emp_last_name']; 
            $emp_name =  $emp_first_name . ' ' .  $emp_last_name;
                  
                
                  $find = $_SESSION['find'];
                  $date = date("Y-m-d");
                $employee = $_SESSION['employeeName'];

                 $employeeNum = $_SESSION['employeeNum'];
            
                    $sql = "INSERT INTO evaluation(eval_dealer_id, eval_dealer_name, eval_date_manager, eval_manager_notes, eval_manger_name, eval_manger_num, eval_empl_num, eval_empl_name) 
              VALUES('$emp_dealer_id','$emp_dealer_name','$today','$notes','$name','$mEmp_id','$employeeNum','$employee')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error employee 172: ' . mysqli_error($con));
                    }


                    $vDate = date("Y/m/d");
                      mysqli_query($con, "UPDATE employee SET emp_evaluation_date = '$vDate'
                  WHERE emp_id   = '$employeeNum' ");

                    mysqli_query($con, "UPDATE employee SET emp_evaluation_manager = '$emp_name'
                  WHERE emp_id   = '$employeeNum' ");

                     
                    $_SESSION['massage'] = "Record has been updated for " . $employee;

                      header("Location: employee_points.php");
                       exit;
                     
                    


                     /////////////////////////////////////////////Update Points/////////////////////////////////////////////////////////////////

                   
                  

                   
                    
                   
                   
         
        }


       }
     


      

?>

 
            <form action="training_plan.php" method="post">


 <center>
            <h3>Enter what you are going to do this week to develop this employees’ skills. </h3>
           
          <br />
        
                        <textarea rows="6" cols="150" name="notes" wrap="wrap " >
                          <?php if (isset($_POST['notes'])) echo $_POST['notes'] ?>
                        </textarea>
                <br />
              
          <br />
                <br />
                    <input type="submit" name="submit" value="Submit"/>
                    <br />
                     <br />
                
                    <input type="submit" name="back" value="Back"/>
                    <br />


         
                   <?php
           
echo "</center>";
           echo "<h1>Previous Notes</h1></center>";
            if(isset($rows)){
                $result = count($rows);
               
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
            }else{
                echo "<h2>No Notes</h2>";
            }
                ?>

