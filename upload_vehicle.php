
<!DOCTYPE html>
<html lang="en">

<head>
<title>Upload Photo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    

    
</head>
<body>


<?php
  require_once("includes/constants.php");
  require("includes/connection.php");
  require("toolbar_sales.php");
  $errors = array();

  if (isset($_POST['submit'])) {
  
    if(empty($_POST['name'])){
        $errors[] = ' Name is empty';
    }else{
        $name = ucwords($_POST['name']);

        $query = "SELECT * ";
        $query .= "FROM veh_voice ";
        $query .= "WHERE voice_vehicle   = '{$name}' ";

        $result_set = mysqli_query($con, $query)
                or die('Query failed scrip: ' . mysqli_error($con));
        $row = mysqli_fetch_array($result_set);      
     
         if(isset($row)){
            $errors[] = 'Use a different name ' . $name . ' has already been used.';
         }
        
        
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////


  
    if(empty($_POST['stock'])){
        $errors[] = ' Name is empty';
    }else{
        $stock = strtoupper($_POST['stock']);

        $query = "SELECT * ";
        $query .= "FROM veh_voice ";
        $query .= "WHERE veh_voice_location_stock   = '{$stock}' ";

        $result_set = mysqli_query($con, $query)
                or die('Query failed scrip: ' . mysqli_error($con));
        $row = mysqli_fetch_array($result_set);      
     
         if(isset($row)){
            $errors[] = 'Use a different name ' . $name . ' has already been used.';
         }
        
        
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////
    if(empty($_POST['gender'])){
        $errors[] = 'Gender is empty';
    }else{
        $gender = $_POST['gender'];
    }

     if(empty($_POST['newused'])){
        $errors[] = 'Gender is empty';
    }else{
        $newused = $_POST['newused'];
    }

     

    if (!empty($errors)) {

        foreach ($errors as $value) {
            echo "<div class=\"errors\">$value</div>";
        }
    } else {

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['veh_voice']) && $_FILES['veh_voice']['error'] == UPLOAD_ERR_OK) {
        // Directory where the uploaded file will be saved
        $uploadDir = 'veh_voice/';
        
        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get the file information
        $uploadFile = $uploadDir . basename($_FILES['veh_voice']['name']);
      
        // Check if the file is an image
        $fileType = mime_content_type($_FILES['veh_voice']['tmp_name']);
        if (strpos($fileType, 'audio') === false) {
            echo 'File is not an veh_voice!';
        } else {
            // Move the uploaded file to the target directory
            $newName = 'veh_voice/' . date("Ymdhis") . '.mp3';
            if (move_uploaded_file($_FILES['veh_voice']['tmp_name'], $newName)) {
                $value = 'File is valid, and was successfully uploaded.'; 
                echo "<div class=\"problem\">$value</div>";     
                }
        }
////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['veh_voice_stock']) && $_FILES['veh_voice_stock']['error'] == UPLOAD_ERR_OK) {
        // Directory where the uploaded file will be saved
        $uploadDir = 'veh_voice/';
        
        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get the file information
        $uploadFile = $uploadDir . basename($_FILES['veh_voice']['name']);
      
        // Check if the file is an image
        $fileType = mime_content_type($_FILES['veh_voice']['tmp_name']);
        if (strpos($fileType, 'audio') === false) {
            echo 'File is not an veh_voice!';
        } else {
            // Move the uploaded file to the target directory
            $newName = 'veh_voice/' . date("Ymdhis") . '.mp3';
            if (move_uploaded_file($_FILES['veh_voice']['tmp_name'], $newName)) {
                $value = 'File is valid, and was successfully uploaded.'; 
                echo "<div class=\"problem\">$value</div>";     
                }

////////////////////////////////////////////////////////////////////////////////////////////////////////////
     
                $sql = "INSERT INTO veh_voice(veh_voice_location, veh_voice_name, veh_voice_stock_num, veh_voice_gender) 
                VALUES('$newName','$name','$gender')";
  
  
                      if (!mysqli_query($con, $sql)) {
                          die('Error veh_voice 90: ' . mysqli_error($con));
                      }

                     
                            
                      $_POST['name'] = '';    
                    
            } /*else {
                echo 'Possible file upload attack!';
            }*/
       
           } else {
        echo 'No file uploaded or upload error!';
           }
           } else {
           echo 'Invalid request method!';
          }

        }
    }
    }
  }
     $blank = '';
    if (isset($_POST['newused'])) {
        $newused = $_POST['newused'];
        $newused_arr[] = "\n<option value=\"$newused\">$newused</option>\n";
    } else {
        $newused_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $place = 'New';
    $newused_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    $place = 'Used';
    $newused_arr[] = "\n<option value=\"$place\">$place</option>\n";

///////////////////////////////////////////////////////////////////////////////////////////

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
?>

 <h1>Upload Name Recording</h1>
    <form action="upload_vehicle.php" method="post" enctype="multipart/form-data">
        
        <input type="file" name="veh_voice" accept="veh_voice/*">

        <tr><td>Vehicle:</td><td>
        <input type="text" name="name" size="30" value="<?php if (isset($_POST['name'])) echo $_POST['name'] ?>" />

        
        <input type="file" name="veh_voice_stock" accept="veh_voice/*">


 <tr><td>Stock Number:</td><td>
        <input type="text" name="stock" size="15" value="<?php if (isset($_POST['stock'])) echo $_POST['stock'] ?>" />
<br>
<br>
         <td>New Used Vehicle:</td>
        <select name="newused">
                                <?php
                                print_r($newused_arr);
                                ?>
                    </select>
                                <br>
                                <br>



        <td>Gender:</td>
        <select name="gender">
                                <?php
                                print_r($gender_arr);
                                ?>
                    </select>
                                <br>
                                <br>
        <input   type="submit" name="submit" value="Upload Vehicle"/>
        
    </form>

</body>
</html>