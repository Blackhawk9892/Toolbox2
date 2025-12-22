<?php
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

          if(isset($_GET['find'])){
            $_SESSION['find'] = $_GET['find'];
          
          }
          
          $find = $_SESSION['find'];

         



            if (isset($_POST['submit'])) {

                $stock = strtoupper($_POST['stock']);
                $year = $_POST['year'];
                $make = ucwords($_POST['make']);
                $model = ucwords($_POST['model']);
                $trim = strtoupper($_POST['trim']);
                $reason = ucwords($_POST['reason']);
                $miles = $_POST['miles'];

                $stockS = strtoupper($_POST['stockS']);
                $yearS = $_POST['yearS'];
                $makeS = ucwords($_POST['makeS']);
                $modelS = ucwords($_POST['modelS']);
                $trimS = strtoupper($_POST['trimS']);
                $reasonS = ucwords($_POST['reasonS']);
                $milesS = $_POST['milesS'];


                $_POST['stock'] = $stock;
                $_POST['year'] = $year;
                $_POST['make'] = $make ;
                $_POST['model'] = $model;
                $_POST['trim'] = $trim;
                $_POST['reason'] = $reason;

                $_POST['stockS'] = $stockS;
                $_POST['yearS'] = $yearS;
                $_POST['makeS'] = $makeS ;
                $_POST['modelS'] = $modelS;
                $_POST['trimS'] = $trimS;
                $_POST['reasonS'] = $reasonS;
             

               
              







/*
                $required_fields = array('stock', 'make', 'model', 'reason', 'miles', 'stockS', 'makeS', 'modelS', 'reasonS', 'milesS');

                foreach ($required_fields as $fieldname) {

                    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {

                        switch ($fieldname) {
                            case "stock":
                              $fieldname = "Primary Vehicle Stock Number field is empty";
                              break;
                            case "make":
                                $fieldname = "Primary Vehicle Make field is empty";
                              break;
                            case "model":
                                $fieldname = "Primary Vehicle Model field is empty";
                              break;
                            case "reason":
                                $fieldname = "Primary Vehicle Reason field is empty";
                                break;
                            case "miles":
                                $fieldname = "Primary Vehicle Miles field is empty";
                                break;
                            case "stockS":
                                $fieldname = "Secondary Vehicle Stock Number field is empty";
                                break;
                            case "makeS":
                                $fieldname = "Secondary Vehicle Make field is empty";
                                break;
                            case "modelS":
                                $fieldname = "Secondary Vehicle Model field is empty";
                                break;
                            case "reasonS":
                                $fieldname = "Secondary Vehicle Reason field is empty";
                                break;
                            case "milesS":
                                $fieldname = "Secondary Vehicle Miles field is empty";
                                break;
                           
                          }

                        $errors[] = $fieldname;
                    }
                } */


                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                    $year = mysqli_real_escape_string($con, $year);
                    $stock = mysqli_real_escape_string($con, $stock);
                    $make = mysqli_real_escape_string($con, $make);
                    $model = mysqli_real_escape_string($con, $model);
                    $trim = mysqli_real_escape_string($con, $trim);
                    $reason = mysqli_real_escape_string($con, $reason);
                    $miles = mysqli_real_escape_string($con, $miles);

                    $yearS = mysqli_real_escape_string($con, $yearS);
                    $stockS = mysqli_real_escape_string($con, $stockS);
                    $makeS = mysqli_real_escape_string($con, $makeS);
                    $modelS = mysqli_real_escape_string($con, $modelS);
                    $trimS = mysqli_real_escape_string($con, $trimS);
                    $reasonS = mysqli_real_escape_string($con, $reasonS);
                    $milesS = mysqli_real_escape_string($con, $milesS);


                    $sql = "INSERT INTO selceted_vehicles(sv_find, sv_stock, sv_year, sv_make, sv_model, sv_trim, sv_reason, sv_miles, sv_stock_sec, sv_year_sec, sv_make_sec, sv_model_sec, sv_trim_sec, sv_reason_sec, sv_miles_sec) 
              VALUES('$find','$stock','$year','$make','$model','$trim','$reason','$miles','$stockS','$yearS','$makeS','$modelS','$trimS','$reasonS','$milesS')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error input: ' . mysqli_error($con));
                    }

               $_POST['stock'] = '';
               $_POST['year'] = '';
               $_POST['make'] = '';
                $_POST['model'] = '';
                $_POST['trim'] = '';
                $_POST['reason'] = '';
                $_POST['miles'] = '';

                $_POST['stockS'] = '';
                $_POST['yearS'] = '';
                $_POST['makeS'] = '';
                $_POST['modelS'] = '';
                $_POST['trimS'] = '';
                $_POST['reasonS'] = '';
               $_POST['milesS'] = '';


                    $_SESSION['message'] = "<h1>You have completed your memory test for today</h1>";
                    $testPage = '/toolbox/toolbox2/home.php';   // For Test
                   // $testPage = '/home.php';  // For production



  $find = $_SESSION['find'];
  header("Location: $testPage?find=$find");
  exit;
                }
            }
            
            ?>

<div style="padding-left: 37px; padding-right: 37px; ">
            <form action="select_vehicles.php" method="post">

                <h3 style="color:DodgerBlue;"> Based on the description of the vehicle the customer is interested in.
                     Select two vehicles from your lot. The primary vehicle would be the first vehicle shown.
                      If the customer is not interested in the primary vehicle, the secondary vehicle would be your backup vehicle.

</h3>
<h2 style="color:Tomato;"> DO NOT SELECT A VEHICLE FROM A DIFFERENT LOT.</h2>

                <h2>Primary Vehicle </h2>
                    <table>
                        
                        <tr><td>Stock Number:</td><td>
                                <input type="text" name="stock" size="20" value="<?php if (isset($_POST['stock'])) echo $_POST['stock'] ?>"	/>
                        <tr><td>Year:</td><td>
                                <input type="number" name="year" size="4" value="<?php if (isset($_POST['year'])) echo $_POST['year'] ?>"	/>

                        <tr><td>Make:</td><td>
                                <input type="text" name="make" size="50" value="<?php if (isset($_POST['make'])) echo $_POST['make'] ?>"	/>

                        <tr><td>Model:</td><td>
                                <input type="text" name="model" size="50" value="<?php if (isset($_POST['model'])) echo $_POST['model'] ?>"	/>

                        <tr><td>Trim:</td><td>
                                <input type="text" name="trim" size="30" value="<?php if (isset($_POST['trim'])) echo $_POST['trim'] ?>"	/>

                        <tr><td>Miles:</td><td>
                                <input type="number" name="miles" size="8" value="<?php if (isset($_POST['miles'])) echo $_POST['miles'] ?>"	/>

                        <tr><td>Reason:</td><td>
                                <input type="text" name="reason" size="200" value="<?php if (isset($_POST['reason'])) echo $_POST['reason'] ?>"	/>

                    </table>    

            <h2>Secondary Vehicle </h2>
                    <table>
                        
                        <tr><td>Stock Number:</td><td>
                                <input type="text" name="stockS" size="20" value="<?php if (isset($_POST['stockS'])) echo $_POST['stockS'] ?>"	/>
                        
                        <tr><td>Year:</td><td>
                                <input type="number" name="yearS" size="4" value="<?php if (isset($_POST['yearS'])) echo $_POST['yearS'] ?>"	/>
                        <tr><td>Make:</td><td>
                                <input type="text" name="makeS" size="50" value="<?php if (isset($_POST['makeS'])) echo $_POST['makeS'] ?>"	/>

                        <tr><td>Model:</td><td>
                                <input type="text" name="modelS" size="50" value="<?php if (isset($_POST['modelS'])) echo $_POST['modelS'] ?>"	/>

                        <tr><td>Trim:</td><td>
                                <input type="text" name="trimS" size="30" value="<?php if (isset($_POST['trimS'])) echo $_POST['trimS'] ?>"	/>

                        <tr><td>Miles:</td><td>
                                <input type="number" name="milesS" size="8" value="<?php if (isset($_POST['milesS'])) echo $_POST['milesS'] ?>"	/>

                        <tr><td>Reason:</td><td>
                                <input type="text" name="reasonS" size="200" value="<?php if (isset($_POST['reasonS'])) echo $_POST['reasonS'] ?>"	/>



                    </table>    

                <br />
                <br />			

               
                    <input type="submit" name="submit" value="Submit"/>
                    
                    
                    </div> 

        </div>
    </body>
</html>
