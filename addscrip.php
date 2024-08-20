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

            if(isset($_SESSION['message'])){
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
                
 ////////////////////////////Employee Info////////////////////////////////////
// 
if(isset($_COOKIE["userId"])){
    $userId = $_COOKIE["userId"];
  
  
    
  $emp_arry = Employee($userId);
  $first = $emp_arry[0];
  $last = $emp_arry[1];      
  $position = $emp_arry[2];
  $emp_id = $emp_arry[3];
  $dealer_id = $emp_arry[4];
  $_SESSION['$dealer_id'] = $dealer_id;
  $name = $first . ' ' . $last;
  $_SESSION['name'] = $name;
  }

    //////////////////////Dealer Info/////////////////////////////////////////
    $dealer_id = $_SESSION['$dealer_id'];
    $query = "SELECT * ";
    $query .= "FROM company ";
    $query .= "WHERE comp_id    = '{$dealer_id}' ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed2: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);

    $dealer_name = $row['comp_name'];
    $dealer_address = $row['comp_address'];
    $dealer_city = $row['comp_city'];
    $dealer_state = $row['comp_state'];
    $dealer_zip = $row['comp_zip'];
    //$dealer_phone = $row['dealer_phone'];
    $dealer_group = $row['comp_group'];
        
 ////////////////////////////Employee Info////////////////////////////////////
// 
if(isset($_COOKIE["userId"])){
    $userId = $_COOKIE["userId"];
  
  
    
  $emp_arry = Employee($userId);
  $first = $emp_arry[0];
  $last = $emp_arry[1];      
  $position = $emp_arry[2];
  $emp_id = $emp_arry[3];
  $dealer_id = $emp_arry[4]; 
  $name = $first . ' ' . $last;
  $_SESSION['name'] = $name;
  }

  
    $query = "SELECT * ";
    $query .= "FROM company ";
    $query .= "WHERE comp_id    = '{$dealer_id}' ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed2: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);

    $dealer_name = $row['comp_name'];
    $dealer_address = $row['comp_address'];
    $dealer_city = $row['comp_city'];
    $dealer_state = $row['comp_state'];
    $dealer_zip = $row['comp_zip'];
    $dealer_group = $row['comp_group'];
   

////////////////////////////////////////////////////////////////////////////

            if(isset($_POST['loadScript']) || isset($_POST['submit'])){
                $_SESSION['oldDealer'] = $_POST['dealer'] ;
                unset($_SESSION['index']);
               
            }

            if(isset($_GET['index'])){
               
                $index = $_GET['index'];
                $_SESSION['index'] = $index;

                $query = "SELECT * ";
                $query .= "FROM script ";
                $query .= "WHERE script_index  = '{$index}' ";
                $query .= "ORDER BY script_order";
        
      
                $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());           
                $row = mysqli_fetch_array($result_set);

                $script_comp_num = $row['script_comp_num'];
                $script_comp_name = $row['script_comp_name'];
                $_POST['dealer'] = $script_comp_num . '-' .  $script_comp_name;
                $_POST['scrip'] = $row['script_template'];
                $_POST['order']= $row['script_order'];
             
                $_SESSION['oldDealer'] = $_POST['dealer'] ;
            }
        
           
         if(isset($_SESSION['dealer'])){
            $_POST['dealer'] = $_SESSION['dealer'];
            unset($_SESSION['dealer']);
         }
            
           
    
            
  
////////////////////Load Pull Downs///////////////////////////////////////////
       
       
            if (isset($_POST['clear'])) {

              
                $_POST['scrip'] = '';
                $_POST['order'] = '';
                unset($_SESSION['index']);
            }

            if (isset($_POST['update'])) {

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

                if(isset($_POST['recording'])){
                    $recording = $_POST['recording'];
                }else{
                    $errors[] = 'Must select a recording for this scrip';
                }

                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {

                $index = $_SESSION['index'];

                $scrip = $_POST['scrip'];
                $order = $_POST['order'];
                $recording = $_POST['recording'];

                mysqli_query($con, "UPDATE script SET script_template = '$scrip'
                  WHERE script_index  = '$index' ");
                 

                mysqli_query($con, "UPDATE script SET script_order = '$order'
                  WHERE script_index  = '$index' ");
                   

                mysqli_query($con, "UPDATE script SET script_audio = '$recording'
                  WHERE script_index  = '$index' ");

                $name = $_SESSION['name'];
              
                mysqli_query($con, "UPDATE script SET script_changed = '$name'
                  WHERE script_index  = '$index' ");
                
                $value = "Record order number " . $order . " has been changed";
                echo "<div class=\"problem\">$value</div>";

              $_POST['recording'] = '';
              $_POST['scrip'] = '';
              $_POST['order'] = '';
              unset($_SESSION['index']);
            }
        }

            if (isset($_POST['delete'])) {

                $index = $_SESSION['index'];

                mysqli_query($con, "DELETE FROM script WHERE script_index  = '$index' ");
                  
              
                $value = "Record number " . $index . " has been deleted";
                echo "<div class=\"problem\">$value</div>";

                $_POST['recording'] = '';
                $_POST['scrip'] = '';
                $_POST['order'] = '';
                unset($_SESSION['index']);
            }


            $blank = '';
            if (isset($_POST['dealer'])) {
                $dealer_arr[] = "\n<option value=\"$_POST[dealer]\">$_POST[dealer]</option>\n";
            } else {
                $dealer_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
            }

            $query = "SELECT * ";
            $query .= "FROM company ";
           
            if($position != 'PFD'){
            $query .= "WHERE comp_id   = '{$dealer_id}' ";
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
 

            echo $dealer_group;

            if (isset($_POST['submit'])) {

              
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

                if(isset($_POST['recording'])){
                    $recording = $_POST['recording'];
                }else{
                    $errors[] = 'Must select a recording for this scrip';
                }
               
                $id = explode("-",$dealer);
               
                $compNum = $id[0];
                $company = $id[1];
                


                  
              

                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                   
                  

                    $sql = "INSERT INTO script(script_group, script_comp_num, script_comp_name, script_template, script_order, script_changed, script_audio ) 
              VALUES('$dealer_group','$compNum','$company','$scrip','$order','$name','$recording')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error employee 161: ' . mysqli_error($con));
                    }


                    echo "<div class=\"problem\">Script order number $order has been added to $company</div>";

                   // $_POST['dealer'] = '';
                    $_POST['scrip'] = '';
                    $_POST['order'] = '';
                  
                 

                   

                   
                   
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
           
            
            if(isset($_POST['dealer'])){
                $dealer = $_POST['dealer'];
                $id = explode("-",$dealer);
               
                $compNum = $id[0];
                //$company = $id[1];
                $bid_satus = 'photos';

                $query = "SELECT * ";
            $query .= "FROM script ";
            $query .= "WHERE script_comp_num = '{$compNum}' ";
            $query .= "ORDER BY script_order";
    
  
            $result_set = mysqli_query($con, $query)
                    or die('Query failed: ' . mysql_error());
    
            while ($row = mysqli_fetch_array($result_set)) { // start while
    
                $script_index = $row['script_index'];      
                $script_comp_num = $row['script_comp_num'];
                $script_comp_name = $row['script_comp_name'];
                $script_template = $row['script_template'];
                $script_order = $row['script_order'];
    
  
               if($bid_satus == 'photos'){
                $bid_satus = 'trade';
               }else{
                $bid_satus = 'photos';
               }
               
$File = "<td width = 1%><a  href=addscrip.php?index=$script_index>Edit</td>";
$Order = "<td width = 1%>$script_order</td>";
$Temp = "<td width = 6%>$script_template </td>";




       
      //  $bid_satus = 'green';
        $rows[] = "\n<div id=\"$bid_satus\"><table width='100%'><tr>$File $Order $Temp </tr></table></div>\n";
               
            } // end while

            }
            

            ?>


            <br>
            <br>
            <form action="addscrip.php" method="post">

        


       


                <center>
                    <table>
<?php

print( "<tr><td>Company Name:</td><td>\n");
print( "<select name=\"dealer\">");
print_r($dealer_arr);

?>
 <div style="padding-left: 37px;">
         <input type="submit" name="loadScript" value="Load Script for company"/>              
         <table>        
                        <tr><td>Scrip:</td><td>
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>
                      
                       
                        <h5 style="color:red;">When first entering a Scrip Order number an interval of 10 is recommended. If later a script needs to be inserted in front of this scrip it can be done without renumbering this scrip. </h5>
                     
                        <tr><td>Scrip Order:</td><td>
                                <input type="number" name="order" size="30" value="<?php if (isset($_POST['order'])) echo $_POST['order'] ?>" />

                      

                                 <?php

                    
        ?>
                       
                   
                    <h2>Select the recording to be used with this scrip</h2>
                    <input type="radio" id="none" name="recording" value="None">
                    <label for="none">None</label>
                    <input type="radio" id="Vehicle Driven" name="recording" value="Vehicle Driven">
                    <label for="Vehicle Driven">How vehicle will be used</label>
                    <input type="radio" id="primaryname" name="recording" value="PrimaryName">
                    <label for="primaryname">Primary User Name</label>
                    <input type="radio" id="primaryrequest" name="recording" value="PrimaryRequest">
                    <label for="primaryrequest">Primary Request</label>
                    <input type="radio" id="secondaryrequest" name="recording" value="SecondaryRequest">
                    <label for="secondaryrequest">Secondary Request</label><br><br>
                   
                    
                   
               
<?php if(isset($_SESSION['index'])){  ?>
               <br />
                    <input type="submit" name="update" value="Update"/>
                <br />
                <br />
                    <input type="submit" name="delete" value="Delete"/>
                <br />
<?php }else{ ?> 
                 <br />
                    <input type="submit" name="submit" value="Submit"/>
<?php } ?> 
                <br />
                    <br />
                    <input type="submit" name="clear" value="Clear"/>

                </center> 
              
                <?php
           if(isset($_POST['dealer'])){

                $result = count($rows);
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
            }
                ?>
</table> 
        </div>
        
    </body>
</html>
