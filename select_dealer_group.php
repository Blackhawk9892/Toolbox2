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
       // require("includes/pull_downs.php");
        require("toolbar_sales.php");

      echo "<center><h1>Dealer Group Maintenance</h1></center>";

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
        if (isset($_SESSION['massage'])) {
            $mess = $_SESSION['massage'];
            echo "<center><h2>$mess</h2></center>";
            unset($_SESSION['massage']);
    }


        $bid_satus = 'sold';
        $rows[] = "\n<table width='100%'><tr><td width = 200px>Dealer Group</td><td width = 40px></tr></table></div>\n";

        $rows[] = "\n<div id=\"$bid_satus\"><a href=addDealerGroup.php?group=new><table><tr><td width = 300px>Enter A New Dealer Group </td> <td>Premium Package </td></tr></table></a></div>\n";
        

        if(isset($dealer_id)){

        
        $query = "SELECT * ";
        $query .= "FROM dealer_group ";       
        $query .= "ORDER BY dg_name";


        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $dg_id = $row['dg_id'];      
            $dg_name = $row['dg_name'];
            $dg_premium_pkg = $row['dg_premium_pkg'];
            $dg_premium_pkg = strtoupper($dg_premium_pkg);
          

           if($dg_premium_pkg == 'Y'){
            $package = "Yes";
            $bid_satus = 'offer';
           }else{
            $package = "No";
            $bid_satus = 'photos';
           }
           
            $rows[] = "\n<div id=\"$bid_satus\"><a href=editDealerGroup.php?group=$dg_id><table><tr><td width = 300px>$dg_name</td> <td>$package</td></tr></table></a> </div>";
           
       }
    }   
        ?>	
        

        
            <form action="select_ealer_group.php" method="post">


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
