<?php

//**************************************************************************************************

function downloadphoto($tmpCompany, $slash, $photoFile) {

    if (extension_loaded('zip')) { // Start if for checking ZIP extension is available
        $zip = new ZipArchive(); // Load zip library

        $zip_name = time() . ".zip"; // Zip name
        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) { // Start if
            // Opening zip file to load files
            exit("cannot open <$filename>\n");
        }





// Checking files are selected


        $dir_array = scandir($tmpCompany);


        $file_folder = $tmpCompany . $slash . $photoFile . $slash;


        $photo_array = scandir($file_folder);



        foreach ($photo_array as $file) { // start foreach for photo
            if ($file == '.') {
                continue;
            }

            if ($file == '..') {
                continue;
            }

            // $zip->addFile($file_folder.$file,$files . $slash . $file); // Adding files into zip
            $zip->addFile($file_folder . $file, $photoFile . $slash . $file); // Adding files into zip
        }// End foreach for photo
        $zip->close();

        // }// End foreach for stock number



        if (file_exists($zip_name)) {




// push to download the zip          
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_name . '"');
            header("Content-Transfer-Encoding: binary");



            readfile($zip_name);


// remove zip file is exists in temp path                             
            unlink($zip_name);


            //	$zip->close();
        }
    } else {
        echo "* You dont have ZIP extension 75*";
    } // End if else for checking ZIP extension is available
}

//end function download					

//**************************************************************************************************************************************


function check_for_photo($c_name) {

    $X = '';
    $a = '';
    $testType = 'start: ';
    $vehNew = 'new';
    require("connection.php");
    
    // $con = require("connection.php");
    $query = "SELECT * ";
    $query .= "FROM vehicles ";
    $query .= "WHERE veh_new_used = '{$vehNew}' ";



    $result_set = mysqli_query($con, $query)
    or die('Query failed check_for_photo 97: ' . mysqli_error($con));

    while ($row = mysqli_fetch_array($result_set)) { // start while
       
        $stock_number = $row['veh_stock'];
        $photo_veh_year = $row['veh_year'];
        $photo_veh_make = $row['veh_make'];
        $photo_veh_model = $row['veh_model'];
        $index_number = $row['veh_id'];

       
        $photo_code_ext_color = $row['veh_color'];
        $Vcolor = explode(" ** ",$photo_code_ext_color);
        $photo_ext_color = $Vcolor[0];

       
        $photo_code_int_color = $row['veh_int'];
        $Vcolor = explode(" ** ",$photo_code_int_color);
        $photo_int_color = $Vcolor[0];

        $veh_build_code = $row['veh_build_code'];
        $veh_equip_ext = $row['veh_equip_ext'];
        $photo_code_ext = $veh_build_code . ' ' . $veh_equip_ext;

        $veh_build_code = $row['veh_build_code'];
        $veh_equip_int = $row['veh_equip_int'];
        $photo_code_int = $veh_build_code . ' ' . $veh_equip_int;

        $photo_status = $row['veh_photo_status'];
        $dealer_number = $row['veh_dealer'];
        $photo_veh_trim = $row['veh_trim'];

        $veh_drive_type = $row['veh_drive_type'];
        $veh_segment = $row['veh_segment'];

        $photo_veh_code_descrip = $veh_drive_type . ' ' . $veh_segment;

        if ($photo_status == 'compl') {
            continue;
        }

        if ($photo_status == 'delete') {

            $from = 'delete';
            removeAdd($index_number, $from);
            continue;
        }

        if ($photo_status == 'uploaded') {
            continue;
        }
        if ($photo_status == 'sold') {
            continue;
        }

        $e = 0;
        $i = 0;


        $query2 = "SELECT * ";
        $query2 .= "FROM same_vehicle ";
        $query2 .= "WHERE same_make = '{$photo_veh_make}' ";
        $query2 .= "AND same_model = '{$photo_veh_model}' ";
        $query2 .= "AND same_trim = '{$photo_veh_trim}' ";
        $query2 .= "AND same_vehicle_year = '{$photo_veh_year}' ";
        $query2 .= "AND same_corp = '{$c_name}' ";
        
        $result_set2 = mysqli_query($con, $query2)
        or die('Query check_for_photo 156: ' . mysqli_error($con));

        $row2 = mysqli_fetch_array($result_set2);

        if (!empty($row2['same_use_year'])) {
            $same_use_year = $row2['same_use_year'];
            $photo_veh_year = $same_use_year;
        } else {
            $trim = 'ALL';
            $query3 = "SELECT * ";
            $query3 .= "FROM same_vehicle ";
            $query3 .= "WHERE same_make = '{$photo_veh_make}' ";
            $query3 .= "AND same_model = '{$photo_veh_model}' ";
            $query3 .= "AND same_trim = '{$trim}' ";
            $query3 .= "AND same_vehicle_year = '{$photo_veh_year}' ";
            $query3 .= "AND same_corp = '{$c_name}' ";
           
            $result_set3 = mysqli_query($con, $query3)
            or die('Query check_for_photo 175: ' . mysqli_error($con));

            $row3 = mysqli_fetch_array($result_set3);

            if (!empty($row3['same_use_year'])) {
                $same_use_year = $row3['same_use_year'];
                $photo_veh_year = $same_use_year;
            }
        }


        $photo_code_ext .= ' ';
       

        $query1 = "SELECT * ";
        $query1 .= "FROM photo1 ";
        $query1 .= "WHERE photo_firm = '{$c_name}' ";
        $query1 .= "AND photo_year = '{$photo_veh_year}' ";
        $query1 .= "AND photo_model = '{$photo_veh_model}' ";
        $query1 .= "AND photo_trim = '{$photo_veh_trim}' ";
        $query1 .= "AND photo_color_code = '{$photo_ext_color}' ";

        $query1 .= "AND photo_descrip = '{$photo_veh_code_descrip}' ";
        $query1 .= "AND photo_code = '{$photo_code_ext}' ";
   
        $result_set1 = mysqli_query($con, $query1)
        or die('Query check_for_photo 156: ' . mysqli_error($con));

        $row1 = mysqli_fetch_array($result_set1);

        if (!empty($row1['photo_type'])) {
            $photo_type = $row1['photo_type'];
            $testType .= 'Int: ';
            $testType .= $photo_type;
            $testType .= "<br />	";
            $e = 1;
            $photo_num1 = $row1['photo_num'];
            $file_num1 = $row1['corporation'];
            //$file_num1 = $photo_corp;
        }


        $photo_code_int .= ' ';
        $query4 = "SELECT * ";
        $query4 .= "FROM photo1 ";
        $query4 .= "WHERE photo_firm = '{$c_name}' ";
        $query4 .= "AND photo_year = '{$photo_veh_year}' ";
        $query4 .= "AND photo_model = '{$photo_veh_model}' ";
        $query4 .= "AND photo_trim = '{$photo_veh_trim}' ";
        $query4 .= "AND photo_color_code = '{$photo_int_color}' ";
        $query4 .= "AND photo_descrip = '{$photo_veh_code_descrip}' ";
        $query4 .= "AND photo_code = '{$photo_code_int}' ";

        $result_set4 = mysqli_query($con, $query4)
        or die('Query check_for_photo 231: ' . mysqli_error($con));

        $row4 = mysqli_fetch_array($result_set4);
        
        if (!empty($row4['photo_type'])) {
            $photo_type = $row4['photo_type'];
            $testType .= 'Int: ';
            $testType .= $photo_type;
            $testType .= "<br />	";
            $i = 1;
            $photo_num2 = $row4['photo_num'];
            $file_num2 = $row4['corporation'];
            //$file_num2 = $photo_corp;
        }


        if ($i == 1 && $e == 1) {
            require("calculations.php");
            $status = 'Photos';
            updateTime($status,$index_number);

            mysqli_query($con,"UPDATE vehicles SET veh_photo_status = 'compl'        
                                    WHERE veh_id = '$index_number' ");

            mysqli_query($con,"UPDATE vehicles SET veh_photo_num_ext  = '$photo_num1'  WHERE veh_id = '{$index_number }' ");

            mysqli_query($con,"UPDATE vehicles SET veh_photo_num_int  = '$photo_num2'  WHERE veh_id = '{$index_number }' ");

            mysqli_query($con,"UPDATE vehicles SET veh_photo_file_ext  = '$file_num1'  WHERE veh_id = '{$index_number }' ");

            mysqli_query($con,"UPDATE vehicles SET veh_photo_file_int  = '$file_num2'  WHERE veh_id = '{$index_number }' ");
        } else {

            if ($i == 1) {

                mysqli_query($con,"UPDATE vehicles SET veh_photo_status = 'ext'        
                                    WHERE veh_id = '$index_number' ");

                mysqli_query($con,"UPDATE vehicles SET veh_photo_num_int  = '$photo_num2'  WHERE veh_id = '{$index_number }' ");
                mysqli_query($con,"UPDATE vehicles SET veh_photo_file_int  = '$file_num2'  WHERE veh_id = '{$index_number }' ");
            }


            if ($e == 1) {

                mysqli_query($con,"UPDATE vehicles SET veh_photo_status = 'int'        
                                    WHERE veh_id = '$index_number' ");

                mysqli_query($con,"UPDATE vehicles SET photo_num_ext  = '$photo_num1'  WHERE veh_id = '{$index_number }' ");
                mysqli_query($con,"UPDATE vehicles SET veh_photo_file_ext  = '$file_num1'  WHERE veh_id = '{$index_number }' ");
            }
        }
    }
    // pfd_check_for_photo();		 
    return;
}

//***************************************************************************************************************************************************************


function check_for_video($c_name) {
    $testfun = '';
    $a = '';

    $testType = 'start: ';
    require("connection.php");
    // $con = require("connection.php");
    $query = "SELECT * ";
    $query .= "FROM video_request ";
    $query .= "WHERE vr_firm = '{$c_name}' ";



    $result_set = mysql_query($query, $con)
            or die('Query failed: ' . mysql_error());

    while ($row = mysql_fetch_array($result_set)) { // start while
        $vr_index = $row['vr_index'];
        $vr_stock = $row['vr_stock'];
        $vr_year = $row['vr_year'];
        $vr_make = $row['vr_make'];
        $vr_model = $row['vr_model'];
        $vr_trim = $row['vr_trim'];
        $vr_descrip = $row['vr_descrip'];

        $vr_ext_color = $row['vr_ext_color'];
        $vr_code_ext_color = $row['vr_code_ext_color'];
        $vr_int_color = $row['vr_int_color'];
        $vr_code_int_color = $row['vr_code_int_color'];
        $vr_file_ext = $row['vr_file_ext'];
        $vr_code_ext = $row['vr_code_ext'];



        $vr_file_int = $row['vr_file_int'];
        $vr_code_int = $row['vr_code_int'];
        $vr_status = $row['vr_status'];
        $vr_all_code = $row['vr_all_code'];
        $vr_corp = $row['vr_corp'];
        $vr_not_stock = $row['vr_not_stock'];
        $vr_vin = $row['vr_vin'];

        if ($vr_status == 'compl') {
            continue;
        }
        if ($vr_status == 'done') {
            continue;
        }

        if ($vr_status == 'delete') {

            $from = 'delete';
            removeAdd($index_number, $from);
            continue;
        }

        if ($vr_status == 'uploaded') {
            continue;
        }
        $e = 0;
        $i = 0;
        $vr_type = '';


        $query = "SELECT * ";
        $query .= "FROM same_vehicle ";
        $query .= "WHERE same_make = '{$vr_make}' ";
        $query .= "AND same_model = '{$vr_model}' ";
        $query .= "AND same_trim = '{$vr_trim}' ";
        $query .= "AND same_vehicle_year = '{$vr_year}' ";
        $result_set3 = mysql_query($query, $con)
                or die('Query failed: ' . mysql_error());

        $row3 = mysql_fetch_array($result_set3);

        $same_use_year = $row3['same_use_year'];

        if (!empty($same_use_year)) {
            $photo_veh_year = $same_use_year;
        } else {
            $trim = 'ALL';
            $query = "SELECT * ";
            $query .= "FROM same_vehicle ";
            $query .= "WHERE same_make = '{$vr_make}' ";
            $query .= "AND same_model = '{$vr_model}' ";
            $query .= "AND same_trim = '{$vr_trim}' ";
            $query .= "AND same_vehicle_year = '{$vr_year}' ";
            $result_set3 = mysql_query($query, $con)
                    or die('Query failed: ' . mysql_error());

            $row3 = mysql_fetch_array($result_set3);

            $same_use_year = $row3['same_use_year'];

            if (!empty($same_use_year)) {
                $vr_year = $same_use_year;
            }
        }

        $type = 'ext';

        $query = "SELECT * ";
        $query .= "FROM video1 ";


        $query .= "WHERE video1_firm = '{$c_name}' ";
        $query .= "AND video1_year = '{$vr_year}' ";
        $query .= "AND video1_model = '{$vr_model}' ";
        $query .= "AND video1_trim = '{$vr_trim}' ";
        $query .= "AND video1_color_code = '{$vr_ext_color}' ";
        $query .= "AND video1_type = '{$type}' ";

        $query .= "AND video1_descrip = '{$vr_descrip}' ";
        $query .= "AND video1_code = '{$vr_code_ext}' ";
        //$query .= "AND video1_have = 'Y' " ;

        $result_set1 = mysql_query($query, $con)
                or die('Query failed: ' . mysql_error());

        $row1 = mysql_fetch_array($result_set1);

        $video1_type = $row1['video1_type'];
        $photo_num1 = $row1['video1_num'];

        if ($video1_type == 'ext') {

            $e = 1;
            $photo_num1 = $row1['video1_num'];
            $file_num1 = $row1['video1_corporation'];
        }


        $type = 'int';
        $query2 = "SELECT * ";
        $query2 .= "FROM video1 ";
        $query2 .= "WHERE video1_firm = '{$c_name}' ";
        $query2 .= "AND video1_year = '{$vr_year}' ";
        $query2 .= "AND video1_model = '{$vr_model}' ";
        $query2 .= "AND video1_trim = '{$vr_trim}' ";
        $query2 .= "AND video1_color = '{$vr_code_int_color}' ";
        $query2 .= "AND video1_type = '{$type}' ";
        $query2 .= "AND video1_descrip = '{$vr_descrip}' ";
        $query2 .= "AND video1_code = '{$vr_code_int}' ";
        // $query .= "AND video1_have = 'Y' " ;


        $result_set2 = mysql_query($query2, $con)
                or die('Query failed2: ' . mysql_error());

        $row2 = mysql_fetch_array($result_set2);
        $video1_type = $row2['video1_type'];


        if ($video1_type == 'int') {

            $i = 1;
            $photo_num2 = $row2['video1_num'];
            $file_num2 = $row2['video1_corporation'];
        }

        if ($i == 1 && $e == 1) {

            mysql_query("UPDATE video_request SET vr_status = 'compl'        
                                    WHERE vr_index = '$vr_index' ");
            if (!empty($photo_num1)) {
                mysql_query("UPDATE video_request SET vr_num_ext  = '$photo_num1'  WHERE vr_index = '{$vr_index}' ");
            }
            if (!empty($photo_num2)) {
                mysql_query("UPDATE video_request SET vr_num_int  = '$photo_num2'  WHERE vr_index = '{$vr_index}' ");
            }
            if (!empty($file_num1)) {
                mysql_query("UPDATE video_request SET vr_file_ext  = '$file_num1'  WHERE vr_index = '{$vr_index}' ");
            }
            if (!empty($file_num2)) {
                mysql_query("UPDATE video_request SET vr_file_int  = '$file_num2'  WHERE vr_index = '{$vr_index}' ");
            }
        } else {


            if ($i == 1) {

                mysql_query("UPDATE video_request SET vr_status = 'ext'        
                                    WHERE vr_index = '$vr_index' ");

                mysql_query("UPDATE video_request SET vr_num_int  = '$photo_num2'  WHERE vr_index = '{$vr_index}' ");




                mysql_query("UPDATE video_request SET vr_file_int  = '$file_num2' 
																	  WHERE vr_index = '{$vr_index}' ");

                $testfun .= $file_num2;
                $testfun .= "<br />";
            }


            if ($e == 1) {

                mysql_query("UPDATE video_request SET vr_status = 'int'        
                                    WHERE vr_index = '$vr_index' ");

                mysql_query("UPDATE video_request SET vr_num_ext  = '$photo_num1'  WHERE vr_index = '{$vr_index}' ");


                mysql_query("UPDATE video_request SET vr_file_ext  = '$file_num1'  WHERE vr_index = '{$vr_index}' ");
            }
            $testfun .= "<hr />";
        }
    }

    return;
}

//**************************************************************************************************
//**************************************************************************************************

function download($tmpCompany, $slash) {

   

    if (extension_loaded('zip')) { // Start if for checking ZIP extension is available
        $zip = new ZipArchive(); // Load zip library

        $zip_name = time() . ".zip"; // Zip name
        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) { // Start if
            // Opening zip file to load files
            exit("cannot open <$filename>\n");
        }

      


// Checking files are selected

          
        $dir_array = scandir($tmpCompany);

        foreach ($dir_array as $files) { // if for stock number
            if ($files == '.') {
                continue;
            }

            if ($files == '..') {
                continue;
            }

            $file_folder = $tmpCompany . $slash . $files . $slash;


            $photo_array = scandir($file_folder);



            foreach ($photo_array as $file) { // start foreach for photo
              
                if ($file == '.') {
                    continue;
                }

                if ($file == '..') {
                    continue;
                }
              
              

                $zip->addFile($file_folder . $file, $files . $slash . $file); // Adding files into zip
            }// End foreach for photo
            $zip->close();
        }// End foreach for stock number

        

        if (file_exists($zip_name)) {
          



// push to download the zip          
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_name . '"');
            header("Content-Transfer-Encoding: binary");



            readfile($zip_name);


// remove zip file is exists in temp path                             
            unlink($zip_name);


            $zip->close();
        }
    } else {
        
        echo "ZIP extension is NOT available";
    } // End if else for checking ZIP extension is available
   
}

//end function download					

//**************************************************************************************************               
function download2($tmpCompany, $slash) {

    if (extension_loaded('zip')) { // Start if for checking ZIP extension is available
        $zip = new ZipArchive(); // Load zip library

        $zip_name = time() . ".zip"; // Zip name
        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) { // Start if
            // Opening zip file to load files
            exit("cannot open <$filename>\n");
        }





// Checking files are selected


        $dir_array = scandir($tmpCompany);

        foreach ($dir_array as $files) { // if for stock number
            if ($files == '.') {
                continue;
            }

            if ($files == '..') {
                continue;
            }
            //	echo  $tmpCompany;
            //echo "<br />";
            //	echo $files;
            //echo "<br />";
            $file_folder = $tmpCompany . $slash . $files . $slash;
            // echo $file_folder;
            //	echo "<br />";
            //	echo "<hr />";

            $photo_array = scandir($file_folder);



            foreach ($photo_array as $file) { // start foreach for photo
                if ($file == '.') {
                    continue;
                }

                if ($file == '..') {
                    continue;
                }



                $zip->addFile($file_folder . $file, $files . $slash . $file); // Adding files into zip
            }// End foreach for photo
            $close1 = $zip->close();
        }// End foreach for stock number



        if (file_exists($zip_name)) {




// push to download the zip          
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-Type: application/octet-stream');

            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_name . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            //header('Content-Length: ' . filesize($zip_name));




            readfile($zip_name);


// remove zip file is exists in temp path                             
            unlink($zip_name);


            $close2 = $zip->close();
        }
    } else {
        echo "* You dont have ZIP extension 690*";
    } // End if else for checking ZIP extension is available
}

//end function download					

//************************************************************************************************************
//                                    function to remove duplcate records in photo1

function duplcate() {
    $a = '';
// require_once("constants.php");
    require_once("connection.php");
    //$con = require("connection.php");   

    $query = "SELECT * ";
    $query .= "FROM photo1 ";
    $query .= "WHERE corporation   = 'PFD' ";
    $query .= "AND photo_have = 'Y' ";


    $result_set = mysql_query($query, $con)
            or die('Query failed: ' . mysql_error());

    while ($row = mysql_fetch_array($result_set)) {


        $photo_year = $row['photo_year'];
        $photo_make = $row['photo_make'];
        $photo_model = $row['photo_model'];
        $photo_trim = $row['photo_trim'];
        $photo_descrip = $row['photo_descrip'];
        $photo_color = $row['photo_color'];
        $photo_code = $row['photo_code'];
        $photo_type = $row['photo_type'];


        $query = "SELECT * ";
        $query .= "FROM photo1 ";
        $query .= "WHERE corporation   = 'PFD' ";
        $query .= "AND photo_year   = '{$photo_year}' ";
        $query .= "AND photo_make = '{$photo_make}' ";
        $query .= "AND photo_model = '{$photo_model}' ";
        $query .= "AND photo_trim = '{$photo_trim}' ";
        $query .= "AND photo_descrip = '{$photo_descrip}' ";
        $query .= "AND photo_color = '{$photo_color}' ";
        $query .= "AND photo_code = '{$photo_code}' ";
        $query .= "AND photo_type = '{$photo_type}' ";


        $result_set1 = mysql_query($query, $con)
                or die('Query failed: ' . mysql_error());

        while ($row1 = mysql_fetch_array($result_set1)) {

            $photo_number = $row1['photo_number'];
            $photo_have = $row1['photo_have'];


            if ($photo_have == 'Y') {

                continue;
            }




            mysql_query("UPDATE photo1 SET photo_have = 'C'        
                                           WHERE photo_number = '$photo_number' ");
        }
    }

    return $a;
}

//**************************************************************************************************

function downloadonephoto($photo, $folder) {

    if (extension_loaded('zip')) { // Start if for checking ZIP extension is available
        $zip = new ZipArchive(); // Load zip library

        $zip_name = time() . ".zip"; // Zip name
        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) { // Start if
            // Opening zip file to load files
            exit("cannot open <$filename>\n");
        }


        $zip->addFile($folder, $photo); // Adding files into zip

        $close1 = $zip->close();





        if (file_exists($zip_name)) {




// push to download the zip          
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-Type: application/octet-stream');

            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_name . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            //header('Content-Length: ' . filesize($zip_name));




            readfile($zip_name);


// remove zip file is exists in temp path                             
            unlink($zip_name);


            $close2 = $zip->close();
        }
    } else {
        echo "* You dont have ZIP extension 817*";
    } // End if else for checking ZIP extension is available
}

//end function download	

//************************************************************************************************************																									   
//**************************************************************************************************

function printerphoto($photo, $folder, $vehicle) {

    if (extension_loaded('zip')) { // Start if for checking ZIP extension is available
        $zip = new ZipArchive(); // Load zip library

        $zip_name = $vehicle . ".zip"; // Zip name
        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) { // Start if
            // Opening zip file to load files
            exit("cannot open <$filename>\n");
        }


        $zip->addFile($folder, $photo); // Adding files into zip

        $close1 = $zip->close();


        $test = '';


        if (file_exists($zip_name)) {

            $test = $zip_name;


// push to download the zip          
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-Type: application/octet-stream');

            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_name . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            //header('Content-Length: ' . filesize($zip_name));




            readfile($zip_name);


// remove zip file is exists in temp path                             
            unlink($zip_name);


            $close2 = $zip->close();
        }
    } else {
        $test = "* You dont have ZIP extension 876*";
    } // End if else for checking ZIP extension is available
    return $test;
}

//end function download	

//************************************************************************************************************																									   
//**************************************************************************************************

function check_compl_video($c_name) {

    require("connection.php");


    $query = "SELECT * ";
    $query .= "FROM video_request ";
    $query .= "WHERE vr_firm = '{$c_name}' ";




    $result_set = mysql_query($query, $con)
            or die('query failed video_request: ' . mysql_error());

    while ($row = mysql_fetch_array($result_set)) { // start while
        $vr_index = $row['vr_index'];
        $vr_year = $row['vr_year'];
        $vr_make = $row['vr_make'];
        $vr_model = $row['vr_model'];
        $vr_trim = $row['vr_trim'];
        $vr_descrip = $row['vr_descrip'];
        $vr_ext_color = $row['vr_ext_color'];
        $vr_int_color = $row['vr_int_color'];
        $vr_all_used_code = $row['vr_all_used_code'];
        $vr_status = $row['vr_status'];



        $query = "SELECT * ";
        $query .= "FROM same_vehicle ";
        $query .= "WHERE same_make = '{$vr_make}' ";
        $query .= "AND same_model = '{$vr_model}' ";
        $query .= "AND same_trim = '{$vr_trim}' ";
        $query .= "AND same_vehicle_year = '{$vr_year}' ";
        $result_set3 = mysql_query($query, $con)
                or die('failed same_vehicle 1: ' . mysql_error());

        $row3 = mysql_fetch_array($result_set3);

        $same_use_year = $row3['same_use_year'];

        if (!empty($same_use_year)) {
            $photo_veh_year = $same_use_year;
        } else {
            $trim = 'ALL';
            $query = "SELECT * ";
            $query .= "FROM same_vehicle ";
            $query .= "WHERE same_make = '{$vr_make}' ";
            $query .= "AND same_model = '{$vr_model}' ";
            $query .= "AND same_trim = '{$vr_trim}' ";
            $query .= "AND same_vehicle_year = '{$vr_year}' ";
            $result_set3 = mysql_query($query, $con)
                    or die('failed same_vehicle 2: ' . mysql_error());

            $row3 = mysql_fetch_array($result_set3);

            $same_use_year = $row3['same_use_year'];

            if (!empty($same_use_year)) {
                $vr_year = $same_use_year;
            }
        }



        $query2 = "SELECT * ";
        $query2 .= "FROM vihicle_video ";
        $query2 .= "WHERE vv_corp = '{$c_name}' ";
        $query2 .= "AND vv_year = '{$vr_year}' ";
        $query2 .= "AND vv_make = '{$vr_make}' ";
        $query2 .= "AND vv_model = '{$vr_model}' ";
        $query2 .= "AND vv_trim = '{$vr_trim}' ";
        $query2 .= "AND vv_desrcip = '{$vr_descrip}' ";
        $query2 .= "AND vv_color_int_code = '{$vr_int_color}' ";
        $query2 .= "AND vv_color_ext_code = '{$vr_ext_color}' ";
        $query2 .= "AND vv_codes = '{$vr_all_used_code}' ";



        $result_set2 = mysql_query($query2, $con)
                or die('failed vihicle_video: ' . mysql_error());

        $row2 = mysql_fetch_array($result_set2);



        if (isset($row2['vv_video_num'])) {
            $vv_video_num = $row2['vv_video_num'];
            $vv_company = $row2['vv_company'];

            mysql_query("UPDATE video_request SET vr_status = 'have'        
                                          WHERE vr_index = '$vr_index' ");

            mysql_query("UPDATE video_request SET vr_compl_num = '$vv_video_num'        
                                          WHERE vr_index = '$vr_index' ");

            mysql_query("UPDATE video_request SET 	vr_corp = '$vv_company'        
                                          WHERE vr_index = '$vr_index' ");
        }

        //	return $test;
    }
}

//**************************************************************************************************************************************
//                                                   Set Same Year for search

function search_year($c_name, $photo_veh_year = 'N', $photo_veh_make = 'N', $photo_veh_model = 'N') {

    require("connection.php");

    $query = "SELECT * ";
    $query .= "FROM same_vehicle ";
    $query .= "WHERE same_corp = '{$c_name}' ";
    $query .= "AND same_vehicle_year = '{$photo_veh_year}' ";
    $query .= "AND same_make = '{$photo_veh_make}' ";
    $query .= "AND same_model = '{$photo_veh_model}' ";
    /* 	
      if($photo_veh_trim != 'N'){
      $query .= "AND same_trim = '{$photo_veh_trim}' " ;
      } */

      $result_set = mysqli_query($con, $query)
      or die('Query failed search_year 1008: ' . mysqli_error($con));

      $row = mysqli_fetch_array($result_set);

    $same_use_year = $row['same_use_year'];

    if (!empty($same_use_year)) {
        $year = $same_use_year;
    } else {
        $year = 'no';
    }

    return $year;
}

?>