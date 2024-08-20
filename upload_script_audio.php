<?php
// Start the session
session_start();
?>
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

  if(isset($_GET['index'])){
    $_SESSION['index'] = $_GET['index'];
  }

  if(isset( $_SESSION['index'])){

    $index = $_SESSION['index'];

    $query = "SELECT * ";
    $query .= "FROM script ";
    $query .= "WHERE script_index   = '{$index}' ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed scrip: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set); 
    
    $script_template = $row['script_template'];
    $script_order = $row['script_order'];
    
  }

  if (isset($_POST['submit'])) {
  
    if(empty($_POST['name'])){
        $errors[] = ' Name is empty';
    }else{
        $name = ucwords($_POST['name']);

        $query = "SELECT * ";
        $query .= "FROM voice ";
        $query .= "WHERE voice_name   = '{$name}' ";

        $result_set = mysqli_query($con, $query)
                or die('Query failed scrip: ' . mysqli_error($con));
        $row = mysqli_fetch_array($result_set);      
     
         if(isset($row)){
            $errors[] = 'Use a different name ' . $name . ' has already been used.';
         }
        
        
    }

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
    if (isset($_FILES['voice']) && $_FILES['voice']['error'] == UPLOAD_ERR_OK) {
        // Directory where the uploaded file will be saved
        $uploadDir = 'voice/';
        
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

     
                $sql = "INSERT INTO voice(voice_location, voice_name, voice_gender) 
                VALUES('$newName','$name','$gender')";
  
  
                      if (!mysqli_query($con, $sql)) {
                          die('Error voice_name 68: ' . mysqli_error($con));
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
    $script_template = $row['script_template'];
    $script_order = $row['script_order'];
    echo "<h2>Order:   $script_order</h2>";
    echo "<h2>Question Asked:   $script_template </h2>";

?>

 <h1>Upload Customer Reply Recording</h1>
    <form action="upload_name.php" method="post" enctype="multipart/form-data">
        
        <input type="file" name="voice" accept="voice/*">

        
    </form>

</body>
</html>