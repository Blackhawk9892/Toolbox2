<?php
// Start the session
session_start();
?>

<!DOCTYPE html PUBLIC "//W3C//DTD XHTML 1.0 Strict//EN" "http://www.3.org/TR/xhtml1/DTD/xhtml1-strict/dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <html>
        <head>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
            <title>Photos For Dealers (Upload Multiple Photos)</title>
        </head>

        <body>
            <?php
            require_once("../includes/functions.php");
            require_once("../includes/photo_functions.php");
            require_once("../includes/constants.php");
            require_once("../includes/connection.php");
            require("../includes/database_rows.php");
                require("../includes/pull_downs.php");

            $_SESSION['reg'] = 'multiple_file_upload.php';

            echo" <h2><center>Upload Multiple Photos</center></h2>";

            if (isset($_POST['back'])) {

                header("Location: dealer_add_photo.php");
                exit;
            }

            $userId = $_COOKIE["userId"];
            $emp_arry = Employee($userId);
            $name = $emp_arry[0] . ' ' . $emp_arry[1];
            $phone = $emp_arry[3];
            $emp_position = $emp_arry[4];
            $emp_id = $emp_arry[5];
            $emp_dealer_id = $emp_arry[6];
            $emp_user_name = $emp_arry[7];
            $phoneCarrie = $emp_arry[8];

           
            $index_number = $_SESSION['index_number'];
            
            $kind = $_SESSION['kind'];

            if ($kind == "int") {
                $type = 'I';
                $num = 2;
            } else {
                $type = 'E';
                $num = 1;
            }


          
    $query = "SELECT * ";
    $query .= "FROM dealer ";
    $query .= "WHERE dealer_Id   = '{$emp_dealer_id}' ";

    $result_set = mysqli_query($con, $query)
    or die('Query failed dealer 101: ' . mysqli_error($con));
   $row = mysqli_fetch_array($result_set);


    $dealer_group = $row['dealer_group'];
    $test_dealer = $row['dealer_Id'];
    $company_name = $row['dealer_name'];
    $employee_authority = $emp_position;
  
    
   
    $query = "SELECT * ";
    $query .= "FROM dealer_group ";
    $query .= "WHERE dg_id = '{$dealer_group}' ";

    $result_set = mysqli_query($con, $query)
    or die('Query failed dealer_group 115: ' . mysqli_error($con));
   $row = mysqli_fetch_array($result_set);

   $c_name = $row['dg_name'];
   $corp_name = 'BOUCHER';

   $photo_corp = 'FolderPhoto' . '/' . $c_name;
   

    $t = check_for_photo($corp_name);
    // echo $t;

  
    $query = "SELECT * ";
    $query .= "FROM vehicles ";
    $query .= "WHERE veh_id   = '{$index_number}' ";
   
   


    $result_set = mysqli_query($con, $query)
    or die('Query failed vehicles 100: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);
  
   

        $dealer_number = $row['veh_dealer'];
        $stock_number = $row['veh_stock'];
       if(empty($row['veh_stock'])){
        $vehNumber = $row['veh_vin'];
       }else{
        $vehNumber = $row['veh_stock'];
       }
       
        $photo_veh_year = $row['veh_year'];
        $photo_veh_make = $row['veh_make'];
        $photo_veh_model = $row['veh_model'];
        $photo_veh_trim = $row['veh_trim'];
        $vehicle = $photo_veh_year . ' ' . $photo_veh_make . ' ' . $photo_veh_model . ' ' . $photo_veh_trim;
        $photo_veh_code_descrip = $row['veh_drive_type'];
       
        $veh_drive_type = strtoupper($row['veh_drive_type']);
        $veh_segment = strtoupper($row['veh_segment']);

        $photo_veh_code_descrip = $veh_drive_type . ' ' . $veh_segment;

        $photo_code_ext_color = $row['veh_color'];
        $Vcolor = explode(" ** ",$photo_code_ext_color);
        $photo_ext_color = $Vcolor[0];

       
        $photo_code_int_color = $row['veh_int'];
        $Vcolor = explode(" ** ",$photo_code_int_color);
        $photo_int_color = $Vcolor[0];

       
        $photo_status = $row['veh_photo_status'];

        $veh_build_code = $row['veh_build_code'];
        $veh_equip_ext = $row['veh_equip_ext'];
        $photo_code_ext = $veh_build_code . ' ' . $veh_equip_ext;
        $veh_equip_int = $row['veh_equip_int'];
        $photo_code_int = $veh_build_code . ' ' . $veh_equip_int;

            $save_corp = $photo_corp;
           // $photo_corp = 'Photo' . '/' . $photo_corp;
           $photo_corp =  $photo_corp;

            $_SESSION['$stock_number'] = $stock_number;
            echo 'Stock Number:  ';
            echo $stock_number;
            echo"<br />";
            echo"<br />";
            echo $photo_veh_year;
            echo '  ';
            echo $photo_veh_make;
            echo '  ';
            echo $photo_veh_model;
            echo '  ';
            echo"<br />";
            echo $photo_veh_trim;
            echo ' ... ';
            echo $photo_veh_code_descrip;
            echo '  ';
            echo"<br />";

            if ($kind == 'ext') {
                $inout = 'Exterior';
                echo 'Exterior:...';
                echo $photo_code_ext_color;
                echo ' ****';
                echo $photo_code_ext;
                $photo_code = $photo_code_ext;
                $photo_code_color = $photo_code_ext_color;
                $color = $photo_ext_color;
            }

            if ($kind == 'int') {
                $inout = 'Interior';
                echo 'Interior:...';
                echo $photo_code_int_color;
                echo ' ****';
                echo $photo_code_int;
                $photo_code = $photo_code_int;
                $photo_code_color = $photo_code_int_color;
                $color = $photo_int_color;
            }

            echo "<hr />";
            $name = array();
            $type = array();
            $size = array();
            $tmp_name = array();
            $error = array();
            $upload_errors = array(
                1 => "No errors.",
                2 => "Larger Than upload max filesixe.",
                3 => "Larger than form MAX_FILE_SIZE.",
                4 => "Partial upload.",
                6 => "To temporary directory.",
                7 => "Can't write to disk",
                8 => "File upload stopped bu extinsion."
            );


//$error = $_FILES['file_upload']['error'];
//$message = $upload_errors[$error];

            if (isset($_FILES['files'])) {

                $name[] = ($_FILES['files']['name']);
                $type[] = ($_FILES['files']['type']);
                $size[] = ($_FILES['files']['size']);
                $tmp_name[] = ($_FILES['files']['tmp_name']);
                $error[] = ($_FILES['files']['error']);
            }

           
            if (isset($_POST['submit'])) {



                if (is_dir($photo_corp)) {
   
                    chdir($photo_corp);
                } else {
    
                    mkdir("$photo_corp", 0777,true);
                    chdir($photo_corp);
                }


                $dt = time();

                $date = strftime(" %m%d%Y%H%M%S", $dt);

                $date .= $index_number;
             
                mkdir("$date", 0777);
                $upload_dir = "$date";
                $count = 0;
                $result = count($_FILES['files']['tmp_name']);

// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information

                while ($count < $result) {
                    //$photoContent = mysqli_real_escape_string(file_get_contents($_FILES["files"]["tmp_name"][$count]));
                    $photoContent = mysqli_real_escape_string($con,file_get_contents($_FILES["files"]["tmp_name"][$count]));
                    $fileName = mysqli_real_escape_string($con,$_FILES["files"]["name"][$count]); // The file name
                    $fileTmpLoc = mysqli_real_escape_string($con,$_FILES["files"]["tmp_name"][$count]); // File in the PHP tmp folder
                    $fileType = mysqli_real_escape_string($con,$_FILES["files"]["type"][$count]); // The type of file it is
                    $fileSize = mysqli_real_escape_string($con,$_FILES["files"]["size"][$count]); // File size in bytes
                    $fileErrorMsg = mysqli_real_escape_string($con,$_FILES["files"]["error"][$count]); // 0 for false... and 1 for true
                    $kaboom = explode(".", $fileName); // Split file name into an array using the dot
                    $fileExt = end($kaboom); // Now target the last array element to get the file extension
// START PHP Image Upload Error Handling --------------------------------------------------
                    if (!$fileTmpLoc) { // if file not chosen
                        $errors[] = "ERROR: Please browse for a file before clicking the upload button.";
                    } /*else if ($fileSize > 5242880) { // if file size is larger than 5 Megabytes
                        $errors[] = "ERROR: Your file was larger than 5 Megabytes in size.";
                        unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
                    }*/ else if (!preg_match("/.(gif|jpg|png)$/i", $fileName)) {

                        // This condition is only if you wish to allow uploading of specific file types    
                        $errors[] = "ERROR: Your image was not .gif, .jpg, or .png.";
                        unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
                    } else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
                        $errors[] = "ERROR: An error occured while processing the file. Try again.";
                    }


                    $count++;
                }

                if (!empty($errors)) {

                    foreach ($errors as $value) {

                        echo "<center>$value </center> \n";
                    }
                } else {



                    $count2 = 0;
                    $count = 0;
                    $result = count($_FILES['files']['tmp_name']);

                    while ($count < $result) {

                        $tmp_file = $_FILES['files']['tmp_name'][$count];
                        if ($count < 10) {
                            $newname = 'DSC0000';
                            $newname .= $count;
                        } else {
                            $newname = 'DSC000';
                            $newname .= $count;
                        }
                        $pName = $newname;
                        $newname .= '.JPG';
                        $target_file = $num . $newname;
                       
                           
                    if(move_uploaded_file($tmp_file, $upload_dir . "/" . $target_file)){

                   

 
                            $count2++;
                    

                        $count++;
                    }
                    $query3 = "SELECT * ";
                    $query3 .= "FROM same_vehicle ";
                    $query3 .= "WHERE same_make = '{$photo_veh_make}' ";
                    $query3 .= "AND same_model = '{$photo_veh_model}' ";
                    $query3 .= "AND same_trim = '{$photo_veh_trim}' ";
                    $query3 .= "AND same_vehicle_year = '{$photo_veh_year}' ";
                    $query3 .= "AND same_corp   = '{$c_name}' ";

                     $result_set3 = mysqli_query($con, $query3)
                        or die('Query failed vehicles 338: ' . mysqli_error($con));
                $row3 = mysqli_fetch_array($result_set3);

                    if(isset($row3['same_use_year'])){
                        $same_use_year = $row3['same_use_year'];
                    }
                    

                    if (!empty($same_use_year)) {
                        $photo_veh_year = $same_use_year;
                    } else {
                        $trim = 'ALL';
                        $query3 = "SELECT * ";
                        $query3 .= "FROM same_vehicle ";
                        $query3 .= "WHERE same_make = '{$photo_veh_make}' ";
                        $query3 .= "AND same_model = '{$photo_veh_model}' ";
                        $query3 .= "AND same_trim = '{$trim}' ";
                        $query3 .= "AND same_vehicle_year = '{$photo_veh_year}' ";
                        $query3 .= "AND same_corp   = '{$c_name}' ";

                        $result_set3 = mysqli_query($con, $query3)
                        or die('Query failed same_vehicle 356: ' . mysqli_error($con));
                $row3 = mysqli_fetch_array($result_set3);
                        
                if(isset($row3['same_use_year'])){
                    $same_use_year = $row3['same_use_year'];
                }
                        

                        if (!empty($same_use_year)) {
                            $photo_veh_year = $same_use_year;
                        }
                    }

                  

                   
                    
                   
                    $testType = check_for_photo($c_name);
                }
                $needPhoto = 0;
                $query = "SELECT * ";
                $query .= "FROM photo1 ";
                $query .= "WHERE corporation = '{PFD }' ";
                $query .= "AND photo_year = '{$photo_veh_year}' ";
                $query .= "AND photo_model = '{$photo_veh_model}' ";
                $query .= "AND photo_trim = '{$photo_veh_trim}' ";
                $query .= "AND photo_color = '{$photo_code_int_color}' ";
                $query .= "AND photo_descrip = '{$photo_veh_code_descrip}' ";
                $query .= "AND photo_code = '{$photo_code_int}' ";


                $result_set = mysqli_query($con, $query)
                        or die('Query failed photo1 391: ' . mysqli_error($con));
                $row = mysqli_fetch_array($result_set);

                if(isset($row['photo_year'])){
                    $photo_year = $row['photo_year'];
                }
              



            }
           
           if ($kind == "int") {
                mysqli_query($con, "UPDATE vehicles SET veh_photo_num_int = '$date'
                WHERE veh_id = '$index_number' ");
                 mysqli_query($con, "UPDATE vehicles SET veh_photo_file_int = '$photo_corp'
                WHERE veh_id = '$index_number' ");
            } else {
                mysqli_query($con, "UPDATE vehicles SET veh_photo_file_ext = '$photo_corp'
                WHERE veh_id = '$index_number' ");
                 mysqli_query($con, "UPDATE vehicles SET veh_photo_num_ext = '$date'
                WHERE veh_id = '$index_number' ");
            }
            
            $sql = "INSERT INTO photo1(photo_num, photo_year, photo_make,photo_model,photo_trim, photo_descrip, photo_drive, photo_segment, photo_color_code, photo_color, photo_code, photo_type, photo_firm, corporation) 
            VALUES('$date','$photo_veh_year','$photo_veh_make','$photo_veh_model','$photo_veh_trim','$photo_veh_code_descrip','$veh_drive_type','$veh_segment','$color','$photo_code_color','$photo_code','$kind','$c_name','$save_corp')";
  
                      if (!mysqli_query($con, $sql)) {
                          die('Error399photo: ' . mysqli_error($con));
                      }
            echo "<br/>";
            echo $count2 . " photos have been uploaded";
            echo "<br/>";
        }
            ?>


            <h3>The Max photos that may be uploaded is 20. If more then 20 photos are slected only the first 20 photos will upload.</h3>
            <div>
                <form action="" method="post" enctype="multipart/form-data">
                    <p>

                        <input type="file" name="files[]" multiple="multiple" />
                        <br />
                        <br />
                        <input type="submit" name="submit" value="Upload"  />
                        <input type="submit" name="back" value="Back to add photo"  />
                    </p>
                </form>
            </div>
          
        </body>
    </html>
