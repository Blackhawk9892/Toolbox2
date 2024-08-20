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
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div>
            <?php
            require_once("includes/constants.php");
            require("includes/connection.php");
            require("includes/database_rows.php");
         
            require("toolbar_sales.php");
           
        

            if (isset($_POST['back'])) {
                $_SESSION['dealer'] = $_SESSION['oldDealer'];

               header("Location: addscrip.php");
                exit;

            }
          
           if(isset($_GET['script'])){
            $script = $_GET['script'];
            $_SESSION['script'] = $_GET['script'];

            $query = "SELECT * ";
            $query .= "FROM script ";
            $query .= "WHERE script_index   = '{$script}' ";
    
            $result_set = mysqli_query($con, $query)
                    or die('Query failed scrip: ' . mysqli_error($con));
            $row = mysqli_fetch_array($result_set);
    
            
            $script_comp_num = $script;
            $script_comp_name = $row['script_comp_name'];
            
            $_POST['dealer'] = $_SESSION['script'] . '-' . $script_comp_name;
            
            $_POST['scrip'] = $row['script_template'];
            $_POST['order'] = $row['script_order'];
           }
            
           $userId = $_COOKIE["userId"];
              $query = "SELECT * ";
        $query .= "FROM employee ";
        $query .= "WHERE emp_id   = '{$userId}' ";

        $result_set = mysqli_query($con, $query)
                or die('Query failed emp: ' . mysqli_error($con));
        $row = mysqli_fetch_array($result_set);


        $emp_position = $row['emp_position'];
        $dealer_id = $row['emp_dealer_id'];
    
            
        if(isset($_COOKIE["userId"])){
            $userId = $_COOKIE["userId"];
        
         

        $emp_arry = Employee($userId);
        $first = $emp_arry[0];
        $last = $emp_arry[1];      
        $position = $emp_arry[2];
        $emp_id = $emp_arry[3];
        $dealer_id = $emp_arry[4];
        $_SESSION['name'] = $first . ' ' . $last;
        }

          


            $blank = '';
            if (isset($_POST['dealer'])) {
                $dealer_arr[] = "\n<option value=\"$_POST[dealer]\">$_POST[dealer]</option>\n";
            } else {
                $dealer_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
            }

            $query = "SELECT * ";
            $query .= "FROM company ";
           
            if($emp_position != 'PFD'){
            $query .= "WHERE comp_id   = '{$emp_dealer_id}' ";
            }
            $query .= "ORDER BY comp_name";

            $result_set = mysqli_query($con, $query)
                    or die('Query failed: ' . mysqli_error($con));

            while ($row = mysqli_fetch_array($result_set)) {
                $comp_id = $row['comp_id'];
                $comp_name = $row['comp_name'];
                $dealer = $comp_id . '-' . $comp_name;
                
                $dealer_arr[] = "\n<option value=\"$dealer\">$dealer</option>\n";
               
            }
 
            if(isset($_POST['delete'])){

                $id = $_SESSION['script'];

             mysqli_query($con, "DELETE FROM script 
             WHERE script_index = '$id' ");

             $oldDealer = $_SESSION['oldDealer'];

             $id = explode("-",$oldDealer);

             $compNum = $id[0];
             $company = $id[1];

             $_SESSION['message'] = "<div class=\"problem\">Script order number $order has been deleted from $company</div>";

             $_POST['dealer'] = '';
             $_POST['scrip'] = '';
             $_POST['order'] = '';

                $_SESSION['dealer'] = $_SESSION['oldDealer'];

            header("Location: addscrip.php");
             exit;
            }


            if (isset($_POST['edit'])) {

              
                if(isset($_SESSION['name'])){
                    $name = $_SESSION['name'];
                }else{
                    $errors[] = 'The name of the person entering or changing is record is empty';
                }



                if(isset($_POST['dealer'])){
                    $dealer = $_POST['dealer'];
                }else{
                    $errors[] = 'A Company was not selected';
                }

                
                if(isset($_POST['scrip'])){
                    $scrip = $_POST['scrip'];
                }else{
                    $errors[] = 'Scrip is empty';
                }

                   
                if(isset($_POST['order'])){
                    $order = $_POST['order'];
                }else{
                    $errors[] = 'Order is empty';
                }

             
               
                


                  
              

                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                   
                   $id = $_SESSION['script'];
 /////////////////////////////////////////////Script/////////////////////////////////////////////////////////////////

 mysqli_query($con, "UPDATE script SET script_template = '$scrip'
 WHERE script_index = '$id' ");

/////////////////////////////////////////////eOrder/////////////////////////////////////////////////////////////////

mysqli_query($con, "UPDATE script SET script_order = '$order'
 WHERE script_index = '$id' ");
/////////////////////////////////////////////eChanged By/////////////////////////////////////////////////////////////////

mysqli_query($con, "UPDATE script SET script_changed = '$name'
 WHERE script_index = '$id' ");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                  $oldDealer = $_SESSION['oldDealer'];

                  $id = explode("-",$oldDealer);
               
                  $compNum = $id[0];
                  $company = $id[1];

                    $_SESSION['message'] = "<div class=\"problem\">Script order number $order has been edited to $company</div>";

                    $_POST['dealer'] = '';
                    $_POST['scrip'] = '';
                    $_POST['order'] = '';
                  
                    $_SESSION['dealer'] = $_SESSION['oldDealer'];

                    header("Location: addscrip.php");
                     exit;
     

                   

                   
                   
                }
            }
            ////////////////////////////////////////////////////////////////////////////////

            $blank = '';
            if (isset($_POST['position'])) {
                $position = $_POST['position'];
                $position_arr[] = "\n<option value=\"$position\">$position</option>\n";
            } else {
                $position_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
            }
            $place = 'Sales';
            $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
           
            $place = 'Manager';
            $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
           
          
            if($emp_position = 'pfd'){
            $place = 'Sales Tool Box';
            $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
            }
           
           
            ?>


            <br>
            <br>
            <form action="editscrip.php" method="post">

        


       


                <center>
                    <table>

         
                       <tr><td>Company Name:</td><td>
                       <input type="text" name="dealer" size="60" value="<?php if (isset($_POST['dealer'])) echo $_POST['dealer'] ?>" readonly />
                            
                        <tr><td>Scrip:</td><td>
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>
                      
                       
                        <h5 style="color:red;">When first entering a Scrip Order number an interval of 10 is recommended. If later a script needs to be inserted in front of this scrip it can be done without renumbering this scrip. </h5>
                     
                        <tr><td>Scrip Order:</td><td>
                                <input type="number" name="order" size="30" value="<?php if (isset($_POST['order'])) echo $_POST['order'] ?>" />

                      

                                 <?php

                    
        ?>
                    </table>    
                

             
                <br />
                <br />
                    <input type="submit" name="edit" value="Edit"/>
                    <br />                   
                    <br />
                    <input type="submit" name="delete" value="Delete"/>
                    <br />                   
                    <br />
                    <input type="submit" name="back" value="Back"/>

                </center> 
              
           

        </div>
      
    </body>
</html>
