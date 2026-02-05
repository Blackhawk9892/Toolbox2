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
        <title>Add Dealer Group</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
    </head>
    <body>
        <div>
            <?php
          
          require("includes/connection.php");
          require("includes/pull_downs.php");
          require("toolbar_sales.php");


            if (isset($_POST['back'])) {
            header("Location: points.php");
            exit;
        }


        if(isset($_SESSION['emp_id'])){
            $emp_id = $_SESSION['emp_id'];
        }

    

      $saveDate = '';
        $query = "SELECT * ";
        $query .= "FROM employee_notes ";
        $query .= "WHERE notes_emp_num = '{$emp_id}' ";
       

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

       while ($row = mysqli_fetch_array($result_set)) { 

               $notes_emp_name = $row['notes_emp_name'];
               $manager = $row['notes_manager_name'];
               $notes_improvement = $row['notes_improvement'];
               $notes_date = $row['notes_date'];
               $notes_points = $row['notes_points'];
              

                $d = strtotime($notes_date);
                $notes_date = date("m/d/Y", $d);

                if(isset($notes_improvement)){
            
                    if($notes_date == $saveDate){
                        
                        echo "<h3 style='border: 2px solid DodgerBlue;'>Manager: $manager ***  Employee: $notes_emp_name *** Date: $notes_date </h3>";
                        
                    }else{
                        $saveDate = $notes_date;
                        echo "<h3 style='border: 2px solid DodgerBlue;'>Manager: $manager ***  Employee: $notes_emp_name *** Points add by Manager: $notes_points *** Date: $notes_date </h3>";
                    }                                       
               
                        echo "<p> $notes_improvement</p>";
                }
              
       }

              
                  
            ?>

            
            <form action="history.php" method="post">

                
                  

                <br />
                <br />			

               
                    <input type="submit" name="submit" value="Submit"/>
                    
                    <br />
                <br />			

               
                    <input type="submit" name="back" value="Back"/>
                     
                

        </div>
    </body>
</html>
