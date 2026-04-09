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
            
            require("toolbar_sales.php");
            require("includes/database_rows.php");

          echo "<center><h1> Employee Points</h1></center>";

           if (isset($_POST['back'])) {
            header("Location: home.php");
            exit;
        }

       

      ///////////////////////////////////////////////////////////////////////      

            if(isset($_COOKIE["userId"])){
          $userId = $_COOKIE["userId"];
                              
                $emp_arry = Employee( $userId);
                $first = $emp_arry[0];
                $last = $emp_arry[1];    
                $position = $emp_arry[2];
                $emp_id = $emp_arry[3];                          
                $dealer_id = $emp_arry[4];
                   
            }

            if(empty($_POST['today'])){
                 $d = strtotime("now");
                 $_POST['today'] = date("Y-m-d", $d); 
                 $_POST['month'] = date("Y/m", $d);  
            }else{
                $today = $_POST['today'];
                    $today_array = explode("-",$_POST['today']);
                    $_POST['month'] = $today_array[0] . '/' . $today_array[1];
            }

            
        
    
?>

  <center>
            <form action="totalPoints.php" method="post">

           <label for="Today">To Date:</label>
           <input type="date" id="today" name="today"  value="<?php if (isset($_POST['today'])) echo $_POST['today'] ?>">

           <tr><td>Month:</td><td>
           <input type="text" name="dealer" size="7" value="<?php if (isset($_POST['month'])) echo $_POST['month'] ?>"	/>

          <br />
                <br />
                    <input type="submit" name="submit" value="Submit"/>
                    <br />
                     <br />
                
                    <input type="submit" name="back" value="Back"/>
                    <br />
</center>