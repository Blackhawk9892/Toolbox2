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
            require("includes/functions.php");
         
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



////////////////////////////////////////////////////////////////////////////

            if(isset($_POST['loadScript']) || isset($_POST['submit'])){
                $_SESSION['oldDealer'] = $_POST['dealer'] ;
                unset($_SESSION['index']);
               
            }

            if(isset($_GET['index'])){
               
                $index = $_GET['index'];
                $_SESSION['index'] = $index;

                $query = "SELECT * ";
                $query .= "FROM objections ";

                $query .= "WHERE obj_index  = '{$index}' ";
                $query .= "ORDER BY obj_order";
        
      
                $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());           
                $row = mysqli_fetch_array($result_set);
            

                $obj_corporate_name  = $row['obj_corporate_name'];
                $obj_corporate_number = $row['obj_corporate_number'];
                $_POST['dealer'] = $obj_corporate_number. '-' .  $obj_corporate_name;
                $_POST['scrip'] = $row['obj_script'];
                $_POST['typeScrip'] = $row['obj_type_script'];
                $_POST['order']= $row['obj_order'];
                $_POST['tone']= $row['obj_tone'];
                $_POST['recording']= $row['obj_order'];
                
                
                $_SESSION['oldDealer'] = $_POST['dealer'] ;
            }
        
         
            
           
    
            
  
////////////////////Load Pull Downs///////////////////////////////////////////
       
       
            if (isset($_POST['clear'])) {
                $_POST['dealer'] = '';
                $_POST['type'] = '';
                $_POST['scrip'] = '';
                $_POST['order'] = '';
                $_POST['tone'] = '';
                $_POST['typeScrip'] = '';
                $_POST['voiceScript'] = '';
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

                if(isset($_POST['tone'])){
                    $Tone = $_POST['tone'];
                }else{
                    $errors[] = 'Tone Of Voice is empty';
                }

                 if(isset($_POST['typeScrip'])){
                    $typeScrip = $_POST['typeScrip'];
                }else{
                    $errors[] = 'Scrip Type was not selected';
                }

               if(isset($_POST['voiceScript'])){
                    $voiceScript = $_POST['voiceScript'];
                }else{
                    $errors[] = 'Voice Type is empty';
                }

                

                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {

                $index = $_SESSION['index'];
               $typeScrip = $_POST['typeScrip'];
                $scrip = $_POST['scrip'];
                $order = $_POST['order'];
                $tone = $_POST['tone'];
               
              
                $scrip = replace_apostrophes($scrip); // Replace apostrophes with * for putting into SQL
                mysqli_query($con, "UPDATE objections SET obj_script = '$scrip'
                  WHERE obj_index  = '$index' ");
                 

                mysqli_query($con, "UPDATE objections SET obj_order = '$order'
                  WHERE obj_index  = '$index' ");

               mysqli_query($con, "UPDATE objections SET obj_tone = '$tone'
                  WHERE obj_index  = '$index' ");

                $voiceScript = replace_apostrophes($voiceScript);  // Replace apostrophes with * for putting into SQL
                mysqli_query($con, "UPDATE objections SET obj_voice_type = '$voiceScript'
                  WHERE obj_index  = '$index' ");


                $name = $_SESSION['name'];
              
                mysqli_query($con, "UPDATE objections SET obj_changed = '$name'
                  WHERE obj_index  = '$index' ");

                


                $value = "Record order number " . $order . " has been changed";
                echo "<div class=\"problem\">$value</div>";


              $_POST['dealer'] = '';
              $_POST['recording'] = '';
              $_POST['scrip'] = '';
              $_POST['order'] = '';
              $_POST['tone'] = '';
              $_POST['typeScrip'] = '';
              $_POST['voiceScript'] = '';
              unset($_SESSION['index']);
            }
        }

            if (isset($_POST['delete'])) {

                $index = $_SESSION['index'];

                mysqli_query($con, "DELETE FROM objections  WHERE obj_index   = '$index' ");
                  
              
                $value = "Record number " . $index . " has been deleted";
                echo "<div class=\"problem\">$value</div>";
                 $_POST['dealer'] = '';
                $_POST['recording'] = '';
                $_POST['scrip'] = '';
                $_POST['order'] = '';
                $_POST['tone'] = '';
                $_POST['typeScrip'] = '';
                unset($_SESSION['index']);
            }


            $blank = '';
 if (isset($_POST['dealer'])) {
        $dealer = $_POST['dealer'];
        $dealer_arr[] = "\n<option value=\"$dealer\">$dealer</option>\n";
        $dealer_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $dealer_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }

   $query = "SELECT * ";
    $query .= "FROM dealer_group ";


    $result_set = mysqli_query($con, $query)
            or die('Query failed 90: ' . mysqli_error($con));

            while ($row = mysqli_fetch_array($result_set)) {

    $dg_id = $row['dg_id'];
    $dg_name = $row['dg_name'];
    
     $dealer = $dg_id . '-' . $dg_name ;
   
    $dealer_arr[] = "\n<option value=\"$dealer\">$dealer</option>\n";
    }

          

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

                if(isset($_POST['typeScrip'])){
                    $typeScrip = $_POST['typeScrip'];
                }else{
                    $errors[] = 'Scrip Type was not selected';
                }

               
                
                if(isset($_POST['scrip'])){
                    $scrip = $_POST['scrip'];
                    $scrip = replace_apostrophes($scrip); // Replace apostrophes with * for putting into SQL
                
                }else{
                    $errors[] = 'Scrip is empty';
                }

                   
                if(isset($_POST['order'])){
                    $order = $_POST['order'];
                }else{
                    $errors[] = 'Order is empty';
                }

               
                if(isset($_POST['tone'])){
                    $tone = $_POST['tone'];
                }else{
                    $errors[] = 'Tone Of Voice is empty';
                }

                 if(isset($_POST['voiceScript'])){
                    $voiceScript = $_POST['voiceScript'];
                    //$voiceScript = replace_apostrophes($voiceScript); // Replace apostrophes with * for putting into SQL

                }else{
                    $errors[] = 'Voice Type is empty';
                }
               
                $id = explode("-",$dealer);
               
                $compNum = $id[0];
                $company = $id[1];
                


                  
              

                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['voice']) && $_FILES['voice']['error'] == UPLOAD_ERR_OK) {
        // Directory where the uploaded file will be saved
        $uploadDir = 'voice/';
        }

        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get the file information
        $uploadFile = $uploadDir . basename($_FILES['voice']['name']);
      
        // Check if the file is an image
        $fileType = mime_content_type($_FILES['voice']['tmp_name']);
        if (strpos($fileType, 'audio') === false) {
            echo 'File is not an voice!';
        } else {
            // Move the uploaded file to the target directory
            $newName = 'voice/' . date("Ymdhis") . '.mp3';
            if (move_uploaded_file($_FILES['voice']['tmp_name'], $newName)) {
                $value = 'File is valid, and was successfully uploaded.'; 
                echo "<div class=\"problem\">$value</div>";     
                }

     
              

                   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                  
                    $name = $_SESSION['name'];
                
                    $sql = "INSERT INTO objections(obj_corporate_number,obj_corporate_name,obj_type_script,obj_script,obj_tone,obj_audio,obj_order,obj_changed,obj_voice_type) 
              VALUES('$compNum','$company','$typeScrip','$scrip','$tone','$newName','$order','$name','$voiceScript')";
                

                    if (!mysqli_query($con, $sql)) {
                        die('Error objections 377 : ' . mysqli_error($con));
                    }


                    echo "<div class=\"problem\">Script order number $order has been added to $company</div>";

                    $_POST['dealer'] = '';
                    $_POST['scrip'] = '';
                    $_POST['order'] = '';
                    $_POST['tone'] = '';
                    $_POST['typeScrip'] = '';
                    $_POST['voiceScript'] = '';

                   
                     }
                }
            }
        }
            ////////////////////////////////////////////////////////////////////////////////
          
           
           
            
            if(isset($_POST['dealer'])){
                $dealer = $_POST['dealer'];
                $id = explode("-",$dealer);
               
                $compNum = $id[0];
                $typeScrip = $_POST['typeScrip'];
         
                $bid_satus = 'photos';

                $query = "SELECT * ";
            $query .= "FROM objections ";
            $query .= "WHERE obj_corporate_number = '{$compNum}' ";    
            $query .= "AND obj_type_script = '{$typeScrip}' ";
            $query .= "ORDER BY  obj_order";
     
            $result_set = mysqli_query($con, $query)
                    or die('Query failed: ' . mysql_error());
  
            while ($row = mysqli_fetch_array($result_set)) { // start while
           
                $script_index = $row['obj_index'];      
                $script_comp_num = $row['obj_corporate_number'];
                $script_comp_name = $row['obj_corporate_name'];
                $script_type = $row['obj_type_script'];
                $script_type = replace_star($script_type); // Replace star with ' for taking out of SQL
                $script_template = $row['obj_script'];
                $script_template = replace_star($script_template); // Replace star with ' for taking out of SQL
                $script_tone = $row['obj_tone'];
                $script_order = $row['obj_order'];
               
    
  
               if($bid_satus == 'photos'){
                $bid_satus = 'trade';
               }else{
                $bid_satus = 'photos';
               }
               
$File = "<td width = 1%><a  href=objscript.php?index=$script_index>Edit</td>";
$Type = "<td width = 1%>$script_type</td>";
$Order = "<td width = 1%>$script_order</td>";
$Tone = "<td width = 1%>$script_tone</td>";
$Temp = "<td width = 6%>$script_template </td>";




       
        $bid_satus = 'green';
        $rows[] = "\n<div id=\"$bid_satus\"><table width='100%'><tr>$File $Type $Order $Tone $Temp </tr></table></div>\n";
               
            } // end while

           
            }
             /////////////////////////////////////////////////////////////////////////////////

    $blank = '';
    if (isset($_POST['tone'])) {
        $tone = $_POST['tone'];
        $tone_arr[] = "\n<option value=\"$tone\">$tone</option>\n";
        $tone_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $tone_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }

    $place = 'Confidence';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Confused';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Curiosity';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Excitement';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Jokingly';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Informational';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";


    $place = 'Surprise';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";

     $place = 'Sincere';
    $tone_arr[] = "\n<option value=\"$place\">$place</option>\n";


     /////////////////////////////////////////////////////////////////////////////////

                $blank = '';
                if (isset($_POST['typeScrip'])) {
                    $typeScrip = $_POST['typeScrip'];
                    $typeScrip_arr[] = "\n<option value=\"$typeScrip\">$typeScrip</option>\n";
                    $typeScrip_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                } else {
                    $typeScrip_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                }
               
            
            
                $description = "Benjamin Franklin";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";
                
               
                $description = "Taking it down to the ridiculous";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";
            
                $description = "Need to talk to somebody";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";

                
                $description = "Payments is to high";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";

               
                $description = "I don't have time right now";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";

             
                $description = "The price is too high";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";

          
                $description = "Need to go to other dealers";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";

                
                $description = "Not enough for my trade-in";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";         
    
               
    
    ///////////////////////////////////////Voice for script////////////////////////////////////////////////////////////
                        
                $blank = '';
                if (isset($_POST['voiceScript'])) {
                    $voiceScript = $_POST['voiceScript'];
                    $voiceScript_arr[] = "\n<option value=\"$voiceScript\">$voiceScript</option>\n";
                    $voiceScript_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                } else {
                    $voiceScript_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                }
               
            
            
                $description = "Starting";
                $voiceScript_arr[] = "\n<option value=\"$description\">$description</option>\n";
                
               
                $description = "Second";
                $voiceScript_arr[] = "\n<option value=\"$description\">$description</option>\n";
            
                $description = "Third";
                $voiceScript_arr[] = "\n<option value=\"$description\">$description</option>\n";

                
                $description = "Fourth";
                $voiceScript_arr[] = "\n<option value=\"$description\">$description</option>\n";

               
                $description = "Fifth";
                $voiceScript_arr[] = "\n<option value=\"$description\">$description</option>\n";

             
                $description = "Sixth";
                $typeScrip_arr[] = "\n<option value=\"$description\">$description</option>\n";

          

    ///////////////////////////////////////////////////////////////////////////////////////////////////

                $blank = '';
                if (isset($_POST['recording'])) {
                    $recordType = $_POST['recording'];
                    $recordType_arr[] = "\n<option value=\"$recordType\">$recordType</option>\n";
                    $recordType_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                } else {
                    $recordType_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                }
                $place = 'Vehicle';
                $recordType_arr[] = "\n<option value=\"$place\">$place</option>\n";
            
                $place = 'real_estate';
                $recordType_arr[] = "\n<option value=\"$place\">$place</option>\n";
                
                $place = 'retail';
                $recordType_arr[] = "\n<option value=\"$place\">$place</option>\n";
            
                $place = 'Phone';
                $recordType_arr[] = "\n<option value=\"$place\">$place</option>\n";


               $place = 'business_to_business';
                $recordType_arr[] = "\n<option value=\"$place\">$place</option>\n";
               
            
                
   

            ?>
<center><h1>Add and Maintain Objections</h1></center>

            <br>
            <br>
           

<form action="objscript.php" method="post" enctype="multipart/form-data">
 <div style="padding-left: 37px;">
  
    <input type="file" name="voice" accept="voice/*">
 
         <input type="submit" name="loadScript" value="Load Script for company"/>   

         <table>   
         <?php

print( "<tr><td>Corporate Name:</td><td>\n");
print( "<select name=\"dealer\">");
print_r($dealer_arr);

?>

<select>
     <br>
                              <tr><td>Type of Close:</td><td>  
                                <select name="typeScrip">
                                <?php
                                print_r($typeScrip_arr);
                                ?> 

                                </select>

                                  <br>
                              <tr><td>Voice Type:</td><td>  
                                <select name="voiceScript">
                                <?php
                                print_r($voiceScript_arr);
                                ?> 

                                </select>
                
                                <br>

                        <tr><td>Scrip:</td><td>
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>
                      
                       
                      
                     
                        <tr><td>Scrip Order:</td><td>
                                <input type="number" name="order" size="30" value="<?php if (isset($_POST['order'])) echo $_POST['order'] ?>" />

                      
                                </table>
                       
     <br>
   
                                <label for="tone">Tone Of Voice:</label>   
        <select name="tone">
                                <?php
                                print_r($tone_arr);
                                ?>
                    </select>
                                <br>
                                <br>

              
                    
                    
               
               
<?php if(isset($_SESSION['index'])){  ?>
 <h3 style="color:Tomato;">Audio will not be updated </h3>
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
           if(isset($_POST['loadScript'])){

                $result = count($rows);
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
            }
                ?>
 
        </div>
        
    </body>
</html>
