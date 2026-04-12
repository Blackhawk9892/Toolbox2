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
        <style>
   table, th, td {
      border: 1px solid green;
   }
</style>
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
                $_SESSION["id"] =  $emp_id ; 
            }

             $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_id = '{$emp_id}' ";
       

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        $row = mysqli_fetch_array($result_set); 

            $emp_dealer_group = $row['emp_dealer_group'];


            if(empty($_POST['date'])){
                 $d = strtotime("now");
                 $_POST['day'] = date("Y-m-d", $d); 
                 $_POST['month'] = date("Y/m", $d); 
                  $_POST['year'] = date("Y", $d); 
            }else{
                //$today = $_POST['monthDay'];
                    $today_array = explode("-",$_POST['day']);
                    $_POST['month'] = $today_array[0] . '/' . $today_array[1];
                    $_POST['year'] = $today_array[0] ;
            }

            
    if(isset($_POST['submit'])){

          // DELETE FROM total_points WHERE total_emp_id='{$emp_id}';
            if(isset($_SESSION["id"])){
                $id = $_SESSION["id"];
                mysqli_query($con, "DELETE FROM total_points WHERE total_delete = '$id' ");
                                         
            }
            if(isset($_POST['day'])){
                $day = $_POST['day'];
            }else{
                $errors[] = "Date is empty";
            }

            if(isset($_POST['month'])){
                $month = $_POST['month'];
            }else{
                $errors[] = "Month is empty";
            }

            if(isset($_POST['year'])){
                $year = $_POST['year'];
            }else{
                $errors[] = "Year is empty";
            }

             if(isset($_POST['report_type'])){
                $report_type = $_POST['report_type'];
            }else{
                $errors[] = "Type of report is empty";
            }

             if(isset($_POST['month_year'])){
                $month_year = $_POST['month_year'];
            }else{
                $errors[] = "Report by Month or Year is empty";
            }
         
        if (!empty($errors)) {

            foreach ($errors as $value) {
            echo "<div class=\"errors\">$value</div>";
                    }
        } else {

        $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_position = '{$report_type}' ";
        

        if($position == 'Corporate' or $position == "PFD"){
            $query .= "AND emp_dealer_group = '{$emp_dealer_group}' ";
        }else{
            $query .= "AND emp_dealer_id = '{$dealer_id}' ";
        }
        
       

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while($row = mysqli_fetch_array($result_set)){
                 $emp_id = $row['emp_id'];
                 $emp_position = $row['emp_position'];
                 $emp_dealer_name = $row['emp_dealer_name'];
                if($report_type != $emp_position){
                    continue;
                }

   ///////////////////////////////////////////////////////////////////////  
  

     $total = 0;
         $query1 = "SELECT * ";
         $query1 .= "FROM points ";
         $query1 .= "WHERE points_sales_num = {$emp_id} ";
         
        if($month_year == 'Month'){
            $query1 .= "AND points_year_month = '{$month}' ";
        }else{
            $query1 .= "AND points_year = '{$year}' ";
        }
     
       

       
        $result_set1 = mysqli_query($con, $query1)
                or die('Query failed: ' . mysql_error());

        while($row1 = mysqli_fetch_array($result_set1)){
                $points_dealer_group = $row1['points_dealer_group'];
                $points_sales_name = $row1['points_sales_name'];
                $points_man_num = $row1['points_man_num'];
                $points_manager = $row1['points_manager'];
                $points_total = $row1['points_total'];
               
                if($report_type == "Manager"){
                    $total = $total + $points_manager;
                   
                }else{
                    $total = $total + $points_total;
                }                                                     

        } 

              $sql = "INSERT INTO total_points(total_group, total_dealer_id, total_emp_id, total_dealer, total_name, total_position, total_points, total_delete) 
              VALUES('$emp_dealer_group','$dealer_id','$emp_id','$emp_dealer_name','$points_sales_name','$emp_position','$total','$id')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error total_points 138: ' . mysqli_error($con));
                    }



        }
    }
 }

?>

  <center>
            <form action="totalPoints.php" method="post">

                <h3>Type of report </h3>
                <h4> 
                 <input type="radio" id="Sales" name="report_type" value="Sales" checked>
                 <label for="Sales">Sales</label>
                 <input type="radio" id="Manager" name="report_type" value="Manager">
                 <label for="Manager">Manager</label><br></h4>

                  <h3>Report by Month or Year</h3>
                <h4> 
                 <input type="radio" id="Month" name="month_year" value="Month" checked>
                 <label for="Month">Month</label>
                 <input type="radio" id="Year" name="month_year" value="Year">
                 <label for="Year">Year</label><br></h4>

           <label for="day">Date:</label>
           <input type="date" id="day" name="day"  value="<?php if (isset($_POST['day'])) echo $_POST['day'] ?>">

           <tr><td>Month:</td><td>
           <input type="text" name="month" size="7" value="<?php if (isset($_POST['month'])) echo $_POST['month'] ?>"	/>
           
           <tr><td>Year:</td><td>
           <input type="text" name="year" size="4" value="<?php if (isset($_POST['year'])) echo $_POST['year'] ?>"	/>
          <br />
                <br />
                    <input type="submit" name="date" value="Set Date"/>

  <br />
                <br />
              

           

          <br />
              
                      <br />
                    <input type="submit" name="submit" value="Submit"/>
                     <br />
                        <br />
                  
                
                    <input type="submit" name="back" value="Back"/>
                    <br />
                     <br />
                      <br />

<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($month_year == "Month"){
    $monthOrYear = $month;
}else{
    $monthOrYear = $year;
}
echo '<h1>This report is for ' . $report_type . ' ran by ' . $month_year . ' of ' . $monthOrYear . '</h1>';

echo "<h2>";
    echo "<table>\n";
        echo "  <tr>\n";
        echo "    <th> Dealer.......................... </th>\n";
        echo "    <th> Emplyee Name..................... </th>\n";
        echo "    <th> Position......................... </th>\n";
        echo "    <th> Points........................... </th>\n";
        echo "  </tr>\n";

       $id =  $_SESSION["id"];
         $query = "SELECT * ";
         $query .= "FROM total_points ";
         $query .= "WHERE total_delete = {$id} ";
         $query .= "ORDER BY total_points DESC";
       
       

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while($row = mysqli_fetch_array($result_set)){
                $total_dealer = $row['total_dealer'];
                $total_name = $row['total_name'];
                $total_position = $row['total_position'];
                $total_points = $row['total_points'];

             echo "  <tr>\n";
             echo "    <td>$total_dealer</td>\n";
             echo "    <td>$total_name</td>\n";
             echo "    <td>$total_position</td>\n";
             echo "    <td>$total_points</td>\n";
             echo "  </tr>\n";
        
        }
        echo "</table>";
        echo "</h2>";

 ?>
 </center>