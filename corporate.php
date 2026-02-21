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

      echo "<center><h1>Employee Points by Company</h1></center>";

        require("includes/security.php");

        if(isset($_POST["Points"])){
             header("Location: training_activity.php");
                exit;
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
        $dealer_group = $emp_arry[6];
    }
        if (isset($_SESSION['massage'])) {
            $mess = $_SESSION['massage'];
            echo "<center><h2>$mess</h2></center>";
            unset($_SESSION['massage']);
    }


        $bid_satus = 'sold';
        $rows[] = "\n<table width='100%'><tr><td width = 200px>Employee</td><td width = 40px>Authorization</tr></table></div>\n";

        $rows[] = "\n<div id=\"$bid_satus\"><table><tr><td width = 300px>Company</td> <td width = 200px >Dealer Group</td>  </tr></table></div>\n";
        

        if(isset($dealer_id)){

        
        $query = "SELECT * ";
        $query .= "FROM company ";
        if($position != "PFD"){
            $query .= "WHERE comp_group = '{$dealer_group}' ";
        }
        
     
        $query .= "ORDER BY comp_name";


        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $comp_id = $row['comp_id'];      
            $comp_group = $row['comp_group'];
            $comp_name = $row['comp_name'];
            

           
         
           $bid_satus = 'offer';
            $rows[] = "\n<div id=\"$bid_satus\"><a href=employee_points.php?company=$comp_id><table><tr><td width = 300px>$comp_name</td> <td width = 200px>$comp_group</td> </tr></table></a> </div>";
           
       }
    }   
        ?>	
        

        
            <form action="corporate.php" method="post">
 <br />
                     <br />
                    <input type="submit" name="back" value="Back"/>
                    <br />
                    <br />
                    <input type="submit" name="Points" value="Points"/>

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
