<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
<title>Upload Reply Recording</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    
</head>
<body>


<?php
  require_once("includes/connection.php");
  require("includes/database_rows.php");
  require("includes/pull_downs.php");
  require_once("toolbar_sales.php");
  require("includes/security.php");
  require("includes/datafile.php");
  require("includes/functions.php");

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
  }

    //////////////////////Dealer Info/////////////////////////////////////////
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

////////////////////Load Pull Downs///////////////////////////////////////////

  $errors = array();

 
 if(isset($_SESSION['vehicle'])){
  $i = 0;
    foreach ($_SESSION['vehicle'] as $x) {
        $i++;
        switch ($i) {
            case 1:
                $_POST['price'] = $x;
              break;
            case 2:
                $_POST['payment'] = $x;
                break;
            case 3:
                $_POST['miles'] = $x;
                break;  
            case 4:
                $_POST['preferredColors'] = $x;
                break;
            case 5:
                $_POST['avoidColors'] = $x;
                break; 
            case 6:
                $_POST['options'] = $x;
                break; 
            default:
              //code block
          }
        unset($_SESSION['vehicle']);
      }
      
  }
  

  if (isset($_POST['submit'])) {
  
  
    if(empty($_POST['scrip'])){
        $errors[] = 'Scrip is empty';
    }else{
        $scrip = ucwords($_POST['scrip']);
    }


   
  
        if(empty($_POST['options'])){
            $errors[] = 'Scrip is Options';
        }else{
            $options = ucwords($_POST['options']);
        }
    


    if(empty($_POST['gender'])){
        $errors[] = 'Gender is empty';
    }else{
        $gender = $_POST['gender'];
    }

    if(empty($_POST['typeDriver'])){
        $errors[] = 'Drive Type is empty';
    }else{      
        $typeDriver = $_POST['typeDriver'];
    }
   
    if(empty($_POST['typeVehicle'])){
        $errors[] = 'Vehicle Type is empty';
    }else{
       
        $typeVehicle =$_POST['typeVehicle'];
    }



    if(empty($_POST['price'])){
        $price = '';
    }else{
        $price = $_POST['price'];
    }

    if(empty($_POST['payment'])){
        $payment = '';
    }else{
        $payment = $_POST['payment'];
    }

    if(empty($_POST['miles'])){
        $miles = '';
    }else{
        $miles = $_POST['miles'];
    }

    if(empty($_POST['preferredColors'])){
        $preferredColors = '';
    }else{
        $preferredColors = $_POST['preferredColors'];
    }

    if(empty($_POST['avoidColors'])){
        $avoidColors = '';
    }else{
        $avoidColors = $_POST['avoidColors'];
    }




    if (!empty($errors)) {

        foreach ($errors as $value) {
            echo "<div class=\"errors\">$value</div>";
        }
    } else {
      

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  
  if (isset($_FILES['voice']) && $_FILES['voice']['error'] == UPLOAD_ERR_OK) {
        // Directory where the uploaded file will be saved
        $uploadDir = 'audio/';
      
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
            $newName = 'audio/' . date("Ymdhis") . '.mp3';
            if (move_uploaded_file($_FILES['voice']['tmp_name'], $newName)) {
                $value = 'File is valid, and was successfully uploaded.'; 
                echo "<div class=\"problem\">$value</div>";     
                }



                $sql = "INSERT INTO audio(audio_group, audio_location, audio_reply, audio_drive_type, audio_price, audio_payment, audio_vehicle_type, audio_miles, audio_options, audio_color_liked, audio_color_dislike, audio_gender) 
                VALUES('$dealer_group','$newName','$scrip','$typeDriver','$price','$payment','$typeVehicle','$miles','$options','$preferredColors','$avoidColors','$gender')";
  
  
                      if (!mysqli_query($con, $sql)) {
                          die('Error audio 169: ' . mysqli_error($con));
                      }

                     
                            
                      $_POST['name'] = '';    
                    
            } /*else {
                echo 'Possible file upload attack!';
            }*/
       
           } else {
            $value = 'No file uploaded or upload error!!';
            echo "<div class=\"errors\">$value</div>";
           }
           } else {
            $value = 'Invalid request method!';
            echo "<div class=\"errors\">$value</div>";
          }

        }
    }

    $blank = '';
    if (isset($_POST['typeDriver'])) {
        $type = $_POST['typeDriver'];
        $typeDriver_arr[] = "\n<option value=\"$type\">$type</option>\n";
    } else {
        $typeDriver_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $place = 'Primary Driver';
    $typeDriver_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Secondary Driver';
    $typeDriver_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Vehicle Driven';
    $typeDriver_arr[] = "\n<option value=\"$place\">$place</option>\n";

    /////////////////////////////////////////////////////////////////////////////////
    $blank = '';
    if (isset($_POST['gender'])) {
        $gender = $_POST['gender'];
        $gender_arr[] = "\n<option value=\"$gender\">$gender</option>\n";
    } else {
        $gender_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $place = 'Male';
    $gender_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Female';
    $gender_arr[] = "\n<option value=\"$place\">$place</option>\n";

    /////////////////////////////////////////////////////////////////////////////////
    
    $blank = '';
    if (isset($_POST['typeVehicle'])) {
        $typeVehicle = $_POST['typeVehicle'];
        $typeVehicle_arr[] = "\n<option value=\"$typeVehicle\">$typeVehicle</option>\n";
    } else {
        $typeVehicle_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $place = 'Wagon';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Convertible';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Small SUV';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Midsize SUV';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";


    $place = 'Large SUV';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Coupe';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Sedan';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Hatchback';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Regular cab Truck';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Extended Cab Truck';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Crew Cab';
    $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

    
?>

 <h1>Upload Reply Recording</h1>

   
    <form action="upload_reply.php" method="post" enctype="multipart/form-data">
    <center><a href="test1.php?find=setup" target="_blank"><h2>Page For Vehicle Setup</h2></a> </center>
    <table>

   
    
        <input type="file" name="voice" accept="voice/*">
        <br> <br>
        <div style="padding-left: 37px;">
        
        <input   type="submit" name="reload" value="Reload Page"/>
        <br> <br>

    <label for="Gender">Gender:</label>
        <select name="gender">

  
                             <?php
                                print_r($gender_arr);
                                ?>
                          
                    </select>
                                <br>
                                <br>

  
        <label for="Driver Type">Driver Type:</label>
        <select name="typeDriver">
        
                                <?php
                                print_r($typeDriver_arr);
                                ?>
                              
                    </select>
                                <br>
                                <br>

     
        <label for="Vehicle Type">Vehicle Type:</label>   
        <select name="typeVehicle">
                                <?php
                                print_r($typeVehicle_arr);
                                ?>
                    </select>
                                <br>
                    

        <tr><td>Scrip:</td><td>
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>

    <br> <br>
<h3>Anything entered blow here must be entered through Page For Vehicle Setup</h3>
       
<tr><td>Options:</td><td>     
        <input type="text" name="options" size="200" value=" <?php if (isset($_POST['options'])) echo $_POST['options'] ?>"readonly/>
        <br> <br>

   
  
   


        <tr><td>Price:</td><td>     
        <input type="number" name="price" size="10" value="<?php if (isset($_POST['price'])) echo $_POST['price'] ?>" readonly/>
        <br> <br>
        <tr><td>Payment:</td><td>     
        <input type="number" name="payment" size="10" value="<?php if (isset($_POST['payment'])) echo $_POST['payment'] ?>" readonly/>
        <br> <br>
        <tr><td>Miles:</td><td>     
        <input type="number" name="miles" size="10" value="<?php if (isset($_POST['miles'])) echo $_POST['miles'] ?>"readonly/>
        <br> <br>
        <tr><td>Preferred Colors:</td><td>     
        <input type="text" name="preferredColors" size="100" value="<?php if (isset($_POST['preferredColors'])) echo Remove_Whites($_POST['preferredColors']) ?>"readonly/>
        <br> <br>
        <tr><td>Avoid Colors:</td><td>     
        <input type="text" name="avoidColors" size="100" value="<?php if (isset($_POST['avoidColors'])) echo Remove_Whites($_POST['avoidColors']) ?>"readonly/>
        <br> <br>
       
        </td>




        <input   type="submit" name="submit" value="Upload Reply"/>
</div>
</table>

    </form>

</body>
</html>