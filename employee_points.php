<?php
$_SESSION['page'] = 'select_employee.php';
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
    <head>
        <title>Select Employee For Authorization</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    </head>


    <body>


        <?php
        require_once("includes/constants.php");
        require("includes/connection.php");
        require("includes/database_rows.php");
      
        require("toolbar_sales.php");

      echo "<center><h1>Employee Points</h1></center>";

        require("includes/security.php");

        if(isset($_POST["back"])){
            
        }

        $save_field_2 = '';

        if(isset($_COOKIE["userId"])){
            $userId = $_COOKIE["userId"];
        
         

        $emp_arry = Employee($userId);
        $first = $emp_arry[0];
        $last = $emp_arry[1];      
        $position = $emp_arry[2];
        $emp_id = $emp_arry[3];
        $dealer_id = $emp_arry[4];
    }

if(isset($_GET['company'])){
    if($_GET['company']){
  $_SESSION['company'] = $_GET['company'];

    }
}
 if(isset($_SESSION['company'])){
    $dealer_id = $_SESSION['company'];
 }


        if (isset($_SESSION['massage'])) {
            $mess = $_SESSION['massage'];
            echo "<center><h2>$mess</h2></center>";
            unset($_SESSION['massage']);
    }


        $bid_satus = 'sold';
        $rows[] = "\n<table width='100%'><tr><td width = 200px>Employee</td><td width = 40px>Authorization</tr></table></div>\n";

        $rows[] = "\n<div id=\"$bid_satus\"><table><tr><td width = 300px>Employee Name </td> <td width = 200px >Position </td>  <td width = 400px >Company</td>  <td width = 200px >Dealer Group</td> <td width = 200px >Manager</td> <td width = 200px >Last Evaluation date</td> <td width = 200px >Evaluation By</td></tr></table></div>\n";
        

        if(isset($dealer_id)){

        
        $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_dealer_id = '{$dealer_id}' ";
       /* if($position != 'PFD'){
            $query .= "WHERE emp_dealer_id = '{$dealer_id}' ";
        } */       
        $query .= "ORDER BY emp_dealer_group, emp_first_name, emp_last_name";


        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $emp_id = $row['emp_id'];      
            $demp_first_name = $row['emp_first_name'];
            $emp_last_name = $row['emp_last_name'];
            $emp_position = $row['emp_position'];
            $emp_dealer_name = $row['emp_dealer_name'];
            $emp_dealer_group = $row['emp_dealer_group'];
            $emp_assigned_man_name = $row['emp_assigned_man_name'];
            $emp_evaluation_date = $row['emp_evaluation_date'];
            
            

            $name = $demp_first_name . ' ' .  $emp_last_name;

           if($emp_position == 'Sales'){
            $bid_satus = 'offer';
           }else{
            //$bid_satus = 'photos';
            continue;
           }
           
            $rows[] = "\n<div id=\"$bid_satus\"><a href=points.php?employee=$emp_id><table><tr><td width = 300px>$name</td> <td width = 200px>$emp_position</td> <td width = 400px>$emp_dealer_name</td> <td width = 200px>$emp_dealer_group</td> <td width = 200px>$emp_assigned_man_name</td><td>$emp_evaluation_date</td></tr></table></a> </div>";
           
       }
    }   
        ?>	
        

        
            <form action="employee_points.php" method="post">


<div id="content">
                <?php
              


                $result = count($rows);
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
                ?>


        </div>

    </form>											
</body>
</html>
