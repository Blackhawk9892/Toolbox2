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
            header("Location: employee_points.php");
            exit;
        }

       

      ///////////////////////////////////////////////////////////////////////      

            if (isset($_GET['employee'])) {
                //$employee = Unencrypt_Get($_GET['employee']);
                $employee = $_GET['employee'];
                 $_SESSION['id'] = $employee;
                $emp_arry = Employee($employee);
                $first = $emp_arry[0];
                $last = $emp_arry[1]; 
                $_SESSION['employeeName'] = $first . ' ' . $last;   
                $position = $emp_arry[2];
                $emp_id = $emp_arry[3];
                $_SESSION['employeeNum'] = $emp_id;
              
                $dealer_id = $emp_arry[4];
                $_SESSION['emp_id'] = $emp_id;     
            }



             if (isset($_POST['history'])) {

                

            header("Location: history.php");
            exit;
        }

           $employeeName = $_SESSION['employeeName'];
           echo "<center><h1>$employeeName</h1></center>";
          $employee = $_SESSION['id'];

    /*      $d = strtotime("now");
echo "Date is today" . date("Y-m-d", $d) . "<br>";

$d = strtotime("-7 days");
echo "Date is -5 " . date("Y-m-d", $d) . "<br>";
           */
if(empty($_POST['today'])){
    $d = strtotime("now");
    $_POST['today'] = date("Y-m-d", $d);
    
}

if(empty($_POST['fromday'])){
    $d = strtotime("-7 days");
    $_POST['fromday'] = date("Y-m-d", $d);
    $_POST['submit'] = "Submit" ;
  
   
}

          $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_id = '{$employee}' ";
     

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        $row = mysqli_fetch_array($result_set);// start while

            $emp_evaluation_date = $row['emp_evaluation_date'];

            $sevenDays = strtotime("$emp_evaluation_date");
        /////////////////////////Submit////////////////////////////////////////////////////
            if (isset($_POST['submit'])) {

                    if (empty($_POST['fromday'])) {
                     $errors[] = "From Date is empty";
                    }else{
                        $fromDate = $_POST['fromday'];
                    }

                    if (empty($_POST['today'])) {
                     $errors[] = "To Date is empty";
                    }else{
                        $toDate = $_POST['today'];
                    }


             if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {

            

        //////////////////////////////////////////////////////////////////////////////////
          
          $employeeId = $_SESSION['id'];
           $totalPoints = 0; 
           $saveDay = '';
           $saveType = '';
           $timesPoints = 0;
           $timesWithoutPoints = 0;


        $query = "SELECT * ";
        $query .= "FROM customer_data ";
        $query .= "WHERE cust_salesperson_num = '{$employeeId}' ";
        $query .= "ORDER BY cust_date, cust_type";

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $cust_date = $row['cust_date'];      
            $cust_id = $row['cust_id'];
            $cust_points = $row['cust_points'];
            $cust_find = $row['cust_find'];
            $cust_type = $row['cust_type'];


            
            

            $str = $cust_date;
              $day = (explode(" ",$str));
                $fileDay = $day[0];



               if($fileDay < $fromDate  or $fileDay > $toDate ){
               
                continue;

               }

              if($saveDay == $fileDay and $saveType == $cust_type){
                 $bid_satus = 'photos';
                 $pointDoNotCount = 'Points Do Not Count for total points only first time for a day adds point ' . $cust_points;
                  $rows[] = "\n<div id=\"$bid_satus\"><a href=work_sheet.php?find=$cust_find><table><tr><td width = 300px>$cust_date</td> <td>$pointDoNotCount</td></tr><td> Point For: $cust_type</td></tr></table></a> </div>";
                  $timesWithoutPoints++;
              }else{
                 $saveDay = $fileDay;
                 $saveType = $cust_type;
                 $bid_satus = 'sold';
                 $pointCount = 'Points Count for total points ' . $cust_points;
                 $rows[] = "\n<div id=\"$bid_satus\"><a href=work_sheet.php?find=$cust_find><table><tr><td width = 300px>$cust_date</td> <td>$pointCount</td></tr><td> Point For: $cust_type</td></tr></table></a> </div>";
                 $totalPoints = $totalPoints + $cust_points;
                 $timesPoints++;
              }

              
        }

    }
          
}
            ////////////////////////////////////////////////////////////////////////////////
            
           
            
          
            ?>


            <br>
            <br>
            <form action="points.php" method="post">

           <center>
            <label for="fromday">From Date:</label>
            <input type="date" id="fromday" name="fromday" value="<?php if (isset($_POST['fromday'])) echo $_POST['fromday'] ?>" />

           <label for="today">To Date:</label>
           <input type="date" id="today" name="today"  value="<?php if (isset($_POST['today'])) echo $_POST['today'] ?>">

          <br />
                <br />
                    <input type="submit" name="submit" value="Submit"/>
                    <br />
                     <br />
                
                    <input type="submit" name="back" value="Back"/>
                    <br />

 <br />
                     <br />
                
                    <input type="submit" name="history" value="Employee History"/>
                    <br />
 <br />
                   
       
       
                <?php
           
            $showPoints = "Total points: " . $totalPoints . " for Dates From: " . $fromDate .  " To: " . $toDate;

          echo "<h1>$showPoints</h1>";

           $showPoints = "Number of time with points: " . $timesPoints . " Number of times without points: " . $timesWithoutPoints ;

           echo "<h1>$showPoints</h1></center>";
            if(isset($rows)){
                $result = count($rows);
               
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
            }else{
                echo "<h1>None Found</h1>";
            }
                ?>


       

                                 
                                    </div>
                                    </body>
                                    </html>
