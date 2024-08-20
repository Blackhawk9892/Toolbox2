
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

  if (isset($_POST['submit'])) {

    if(empty($_POST['gender'])){
        $errors[] = 'Gender is empty';
    }else{
        $gender = $_POST['gender'];
    }


    if (!empty($errors)) {

        foreach ($errors as $value) {
            echo "<div class=\"errors\">$value</div>";
        }
    } else {
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        // Directory where the uploaded file will be saved
        $uploadDir = 'images/';
        
        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get the file information
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
      
        // Check if the file is an image
        $fileType = mime_content_type($_FILES['photo']['tmp_name']);
        if (strpos($fileType, 'image') === false) {
            echo 'File is not an image!';
        } else {
            // Move the uploaded file to the target directory
            $newName = 'images/' . date("Ymdhis") . '.jpg';
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $newName)) {
                $value = 'File is valid, and was successfully uploaded.'; 
                echo "<div class=\"problem\">$value</div>"; 
               
                }
                $sql = "INSERT INTO photos(photo_location, photo_gender) 
                VALUES('$newName','$gender')";
  
  
                      if (!mysqli_query($con, $sql)) {
                          die('Error employee 159: ' . mysqli_error($con));
                      }

                      $_POST['gender'] = ''; 
                      
                     /* if($_POST['printPhoto'] == 'yes'){
                        header("Location: photoPDF.php?photeName=$newName");
                        exit;
                            
                                         

            } else {
                echo 'Possible file upload attack!';
            }*/
        }
           } else {
        echo 'No file uploaded or upload error!';
           }
           } else {
           echo 'Invalid request method!';
          }


    }
}

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

 <h1>Upload Photo</h1>
    <form action="upload_photo.php" method="post" enctype="multipart/form-data">

        <input type="file" name="photo" accept="image/*">

        <td>Gender:</td>
        <select name="gender">
                                <?php
                                print_r($gender_arr);
                                ?>
                    </select>
               <!--     <p>Print photos name for download:</p>
        <input type="radio" id="yes" name="printPhoto" value="Yes">
  <label for="yes">Do print photos name</label>
  <input type="radio" id="no" name="printPhoto" value="No" checked>
  <label for="no">Do NOT print photos name</label><br> -->

                                <br>
                                <br>
        <input   type="submit" name="submit" value="Upload Photo"/>
        
    </form>

</body>
</html>