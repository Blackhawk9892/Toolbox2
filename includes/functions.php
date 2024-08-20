	



<?php

//================================================START FUNCTION================================================================

function autority_insert_into($field1, $field2 = 'emty') {  // START FUNCTION
    require("connection.php");
    // $con = require("includes/connection.php");
    $query = "SELECT * ";
    $query .= "FROM customer ";
    $query .= "WHERE user_name = '{$field1}' ";
    if ($field2 != 'emty') { //start if
        $query .= "AND hash_password = '{$field2}'  ";
    }// end if

    $query .= " LIMIT 1";
    $result_set = mysql_query($query, $con)
            or die('Query failed: ' . mysql_error());


    $row = mysql_fetch_array($result_set);
    echo"test";
    print_r($row);
    $custId = $row['customer_number'];
    $hash_password = $row['hash_password'];
    $user_name = $row[user_name];


    $expire = time() + 60 * 60 * 24;
    setcookie("user", $custId, $expire);
    setcookie("venNum", $hash_password, $expire);
    setcookie("autVen", $user_name, $expire);



    $_SESSION['user'] = $custId;
    $_SESSION['venNum'] = $hash_password;
    $_SESSION['autVen'] = $user_name;


    return $custId;
}

// END FUNCTION autority_insert_into
//***********************************************END FUNCTION*************************************************************
//==============================================START FUNCTION============================================================
/*
  This function is to check if there is a file
 */

function check_for_file($field1, $field2 = 'emty') {// starte for function check for file
    require("connection.php");
    //$con = require("connection.php");

    $query = "SELECT * ";
    $query .= "FROM customer ";
    $query .= "WHERE user_name = '{$field1}' ";
    if ($field2 != 'emty') { //start if
        $query .= "AND hash_password = '{$field2}'  ";
    }// end if
    echo"<br />";
    echo $con;

    $result_set = mysql_query($query, $con)
            or die('Query failed: ' . mysql_error());


    //To check if there is no user name of file or that the password matches


    if (mysql_num_rows($result_set) >= 1) { // start if
        return true;
    }     // END IF
    else { // start else																   
        return false;
    }   // END ELSE

    return true;
}

//END OF FUNCTION check_for_file 
//************************************************END FUCTION**********************************************************
//===============================================START FUCTION==========================================================							
function same_record($field1, $field2) {// starte for function check for file
    require("connection.php");
    // $con = require("includes/connection.php");
    $query = "SELECT * ";
    $query .= "FROM Private_Forum ";
    $query .= "WHERE pv_invite_num = '{$field1}' ";


    $result_set = mysql_query($query, $con);




    while ($row = mysql_fetch_array($result_set)) { // start while
        $pv_question_num = $row['pv_question_num'];

        //To check if there is no user name of file or that the password matches

        if ($field2 == $pv_question_num) { // start if
            return true;
        }     // END IF
    }

    return false;
}

//END OF FUNCTION check_for_file 
//*****************************************************END FUNCTION*******************************************************
//=====================================================START FUNCTION======================================================						
/*
  This function close data base
 */
function confirm_query($rusult_set) { // start function confirm query
    if (!$rusult_set) { // start if
        die("Data base query failed...." . mysql_error());
    }// END OF IF
}

// END OF FUNCTION confirm_query 

/*
  Clean data before sending to data base
 */

function mysql_prep($value) {// start function mysql prep
    $magic_quotes_active = get_magic_quotes_gpc();
    $new_enough_php = function_exists("mysql_real_escape_string");

    if ($new_enough_php) { // start if 1
        If ($magic_quotes_active) {//start if 2
            $value = stripslashes($value);
        } //end if 2
    } //end if 1
    else { // start else
        if ($magic_quotes_active) { // start if
            $value = addslashes($value);
        }// end if
    } // end else
    return $value;
}

// end function mysql prep*/
//******************************************************END FUNCTION***************************************************							
//=====================================================START FUNCTION==================================================

function check_email_address($email) {
    // First, we check that there's one @ symbol, 
    // and that the lengths are right.
    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
        // Email invalid because wrong number of characters 
        // in one section or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);

    for ($i = 0; $i < sizeof($local_array); $i++) {
        if
        (!preg_match("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
                        $local_array[$i])) {
            return false;
        }
    }
    // Check if domain is IP. If not, 
    // it should be valid domain name
    if (!preg_match("^\[?[0-9\.]+\]?$", $email_array[1])) {
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if
            (!preg_match("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
?([A-Za-z0-9]+))$",
                            $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}

//*****************************************************END FUNCTION*************************************************************
//====================================================START FUNCTION============================================================


function VALIDATE_USPHONE($phonenumber, $useareacode = true) {
    if (preg_match("/^[ ]*[(]{0,1}[ ]*[0-9]{3,3}[ ]*[)]{0,1}[-]{0,1}[ ]*[0-9]{3,3}[ ]*[-]{0,1}[ ]*[0-9]{4,4}[ ]*$/", $phonenumber) || (preg_match("/^[ ]*[0-9]{3,3}[ ]*[-]{0,1}[ ]*[0-9]{4,4}[ ]*$/", $phonenumber) && !$useareacode))
        return preg_match("[^0-9]", "", $phonenumber);
    return false;
}

//*****************************************************END FUNCTION*************************************************************
//====================================================START FUNCTION=============================================================


function validateUSAZip($zip_code) {
    if (preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $zip_code))
        return true;
    else
        return false;
}

function showOptionsDrop($array) {
    $string = '';
    foreach ($array as $k => $v) {
        $string .= '<option value="' . $k . '"' . $s . '>' . $v . '</option>' . "\n";
    }
    return $string;
}

//******************************************************END FUNCTION**************************************************************
//=====================================================START FUNCTION=============================================================


function has_spaces($text) {
    if (preg_match(" ", $text)) {
        return(true);
    }
    return(false);
}

//*****************************************************END FUNCTION***************************************************************
//====================================================START FUNCTION===============================================================


function validateDate($date) {
    if (preg_match('/^[0-9]{4}?$/', $date))
        return true;
    else
        return false;
}

//***************************************************END FUNCTION******************************************************************
//====================================================START FUNCTION===============================================================


function validateMonth($date) {
    if (preg_match('/^[0-9]{2}?$/', $date))
        return true;
    else
        return false;
}

//****************************************************END FUNCTION*****************************************************************
//====================================================START FUNCTION===============================================================


function validateYear($year) {
    if (preg_match('/^[0-9]{4}?$/', $year))
        return true;
    else
        return false;
}

//****************************************************END FUNCTION*****************************************************************
//====================================================START FUNCTION===============================================================


function validateNum($number) {
    if (preg_match('/^[0-9]{1,}?$/', $number))
        return true;
    else
        return false;
}

//****************************************************END FUNCTION*****************************************************************
//==================================================START FUNCTION=================================================================


function stopCode($var) {
    $match = false;

    if (preg_match("/&/", $var)) {
        $match = true;
    }

    if (preg_match("/</", $var)) {
        $match = true;
    }

    /* if  (preg_match(/\/ , $var) )
      {return(true);}

      if  (preg_match("///}", $var) )
      {return(true);} */

    if (preg_match("/%/", $var)) {
        $match = true;
    }

    if (preg_match("/\)/", $var)) {
        $match = true;
    }

    if (preg_match("/#/", $var)) {
        $match = true;
    }

    if (preg_match("/\@/", $var)) {
        $match = true;
    }

    if (preg_match("/!/", $var)) {
        $match = true;
    }

    if (preg_match("/\`/", $var)) {
        $match = true;
    }

    if (preg_match("/~/", $var)) {
        $match = true;
    }

    if (preg_match("/=/", $var)) {
        $match = true;
    }

    if (preg_match("/\*/", $var)) {
        $match = true;
    }

    if (preg_match("/\(/", $var)) {
        $match = true;
    }

    if (preg_match("/:/", $var)) {
        $match = true;
    }

    if (preg_match("/;/", $var)) {
        $match = true;
    }

    if (preg_match("/\|/", $var)) {
        return(true);
    }

    if (preg_match("/\?/", $var)) {
        $match = true;
    }

    if (preg_match("/\{/", $var)) {
        $match = true;
    }



    if (preg_match("/\^/", $var)) {
        $match = true;
        ;
    }

    if (preg_match("/'/", $var)) {
        $match = true;
    }




    return($match);
}

//************************************************************END FUNCTION**************************************************************
//===========================================================START FUNCTION============================================================


function phone_number($sPhone) {
    $sPhone = preg_replace("[^0-9]", '', $sPhone);
    if (strlen($sPhone) != 10)
        return(False);
    $sArea = substr($sPhone, 0, 3);
    $sPrefix = substr($sPhone, 3, 3);
    $sNumber = substr($sPhone, 6, 4);
    $sPhone = "(" . $sArea . ")" . $sPrefix . "-" . $sNumber;
    return($sPhone);
}

//*********************************************************END FUNCTION***************************************************************
//========================================================START FUNCTION============================================================== 
// For getting name of program

function program_name($Program) {
    if ($Program == 'start_ventor.php') {
        $Name = "Vendor";
    }

    if ($Program == 'customer_vehicle.php') {
        $Name = "Customer Vehicle";
    }

    if ($Program == 'select_customer_vehicle.php') {
        $Name = "Select Member Vehicle";
    }

    if ($Program == 'part_request_form.php') {
        $Name = "Part Request Form";
    }

    if ($Program == 'part_request.php') {
        $Name = "Select Vehicle for Request";
    }

    if ($Program == 'select_employee.php') {
        $Name = "Select Employee For Authorization";
    }

    if ($Program == 'empl_auth.php') {
        $Name = "Employee Authorization";
    }

    if ($Program == 'select_offer_bid.php') {
        $Name = "Select Vehicle for Part Request";
    }

    if ($Program == 'offer_bid_form.php') {
        $Name = "Respond To Bid";
    }

    if ($Program == 'sold_bid.php ') {
        $Name = "Buy Part";
    }

    if ($Program == 'sell_reg.php') {
        $Name = "Vendor Registration";
    }

    if ($Program == 'select_request_bid.php') {
        $Name = "Part Request";
    }
    if ($Program == 'request_bid_form.php') {
        $Name = "Make A Bid On Part";
    }

    if ($Program == 'select_question_bid.php') {
        $Name = "Answer Questions or Accept Order";
    }

    if ($Program == 'select_question_bid.php') {
        $Name = "Answer Questions or Accept Order";
    }

    if ($Program == 'club_reg.php') {
        $Name = "Club Registration ";
    }
    if ($Program == 'clubs_select_event.php') {
        $Name = "Select Event ";
    }
    if ($Program == 'club_event_form.php') {
        $Name = "Club Event Form ";
    }

    if ($Program == 'verify_refund.php') {
        $Name = "Verify Refund ";
    }

    if ($Program == 'select_return.php') {
        $Name = "Select For Refund To Customer ";
    }
    return($Name);
}

//*************************************************************END FUNCTION*****************************************************
//============================================================START FUNCTION====================================================
// function to for valid state and make it capital letters.

function state_validate($State) {
    switch ($State) {
        case 'AL':
            $st = "AL";
            break;
        case 'AK':
            $st = "AK";
            break;
        case 'AZ':
            $st = "AZ";
            break;
        case 'CA':
            $st = "CA";
            break;
        case 'CO':
            $st = "CO";
            break;
        case 'CT':
            $st = "CT";
            break;
        case 'DE':
            $st = "DE";
            break;
        case 'DC':
            $st = "DC";
            break;
        case 'FL':
            $st = "FL";
            break;
        case 'GA':
            $st = "GA";
            break;
        case 'HI':
            $st = "HI";
            break;
        case 'ID':
            $st = "ID";
            break;
        case 'IL':
            $st = "IL";
            break;
        case 'IN':
            $st = "IN";
            break;
        case 'IA':
            $st = "IA";
            break;
        case 'KS':
            $st = "KS";
            break;
        case 'CO':
            $st = "CO";
            break;
        case 'KY':
            $st = "KY";
            break;
        case 'LA':
            $st = "LA";
            break;
        case 'ME':
            $st = "ME";
            break;
        case 'MD':
            $st = "MD";
            break;
        case 'MA':
            $st = "MA";
            break;
        case 'MI':
            $st = "MI";
            break;
        case 'MN':
            $st = "MN";
            break;
        case 'MS':
            $st = "MS";
            break;
        case 'MO':
            $st = "MO";
            break;
        case 'MT':
            $st = "MT";
            break;
        case 'NE':
            $st = "NE";
            break;
        case 'NV':
            $st = "NV";
            break;
        case 'NY':
            $st = "NY";
            break;
        case 'NC':
            $st = "NC";
            break;
        case 'ND':
            $st = "ND";
            break;
        case 'OH':
            $st = "OH";
            break;
        case 'OK':
            $st = "OK";
            break;
        case 'OR':
            $st = "OR";
            break;
        case 'PA':
            $st = "PA";
            break;
        case 'RI':
            $st = "RI";
            break;
        case 'SC':
            $st = "SC";
            break;
        case 'SD':
            $st = "SD";
            break;
        case 'TN':
            $st = "TN";
            break;
        case 'TX':
            $st = "TX";
            break;
        case 'UT':
            $st = "UT";
            break;
        case 'VT':
            $st = "VT";
            break;
        case 'VA':
            $st = "VA";
            break;
        case 'WA':
            $st = "WA";
            break;
        case 'WV':
            $st = "WV";
            break;
        case 'WI':
            $st = "WI";
            break;
        case 'WY':
            $st = "WY";
            break;
        case 'al':
            $st = "AL";
            break;
        case 'ak':
            $st = "AK";
            break;
        case 'az':
            $st = "AZ";
            break;
        case 'ca':
            $st = "CA";
            break;
        case 'co':
            $st = "CO";
            break;
        case 'ct':
            $st = "CT";
            break;
        case 'de':
            $st = "DE";
            break;
        case 'dc':
            $st = "DC";
            break;
        case 'fl':
            $st = "FL";
            break;
        case 'ga':
            $st = "GA";
            break;
        case 'hi':
            $st = "HI";
            break;
        case 'id':
            $st = "ID";
            break;
        case 'il':
            $st = "IL";
            break;
        case 'in':
            $st = "IN";
            break;
        case 'ia':
            $st = "IA";
            break;
        case 'ks':
            $st = "KS";
            break;
        case 'co':
            $st = "CO";
            break;
        case 'ky':
            $st = "KY";
            break;
        case 'la':
            $st = "LA";
            break;
        case 'me':
            $st = "ME";
            break;
        case 'md':
            $st = "MD";
            break;
        case 'ma':
            $st = "MA";
            break;
        case 'mi':
            $st = "MI";
            break;
        case 'mn':
            $st = "MN";
            break;
        case 'ms':
            $st = "MS";
            break;
        case 'mo':
            $st = "MO";
            break;
        case 'mt':
            $st = "MT";
            break;
        case 'ne':
            $st = "NE";
            break;
        case 'nv':
            $st = "NV";
            break;
        case 'ny':
            $st = "NY";
            break;
        case 'nc':
            $st = "NC";
            break;
        case 'nd':
            $st = "ND";
            break;
        case 'oh':
            $st = "OH";
            break;
        case 'ok':
            $st = "OK";
            break;
        case 'or':
            $st = "OR";
            break;
        case 'pa':
            $st = "PA";
            break;
        case 'ri':
            $st = "RI";
            break;
        case 'sc':
            $st = "SC";
            break;
        case 'sd':
            $st = "SD";
            break;
        case 'tn':
            $st = "TN";
            break;
        case 'tx':
            $st = "TX";
            break;
        case 'ut':
            $st = "UT";
            break;
        case 'vt':
            $st = "VT";
            break;
        case 'va':
            $st = "VA";
            break;
        case 'wa':
            $st = "WA";
            break;
        case 'wv':
            $st = "WV";
            break;
        case 'wi':
            $st = "WI";
            break;
        case 'wy':
            $st = "WY";
            break;

        default:
            $st = "not";
    }
    return($st);
}

//*****************************************************************END FUNCTION*****************************************************
//================================================================START FUNCTION===================================================


function autority_level($CustID, $type) {  // START FUNCTION
    require("connection.php");
    //  $con = require("includes/connection.php");
    $query = "SELECT * ";
    $query .= "FROM customer ";
    $query .= "WHERE customer_id = '{$CustID}' ";


    $query .= " LIMIT 1";
    $result_set = mysql_query($query, $con)
            or die('Query failed: ' . mysql_error());


    $row = mysql_fetch_array($result_set);

    $auterized_ventor = $row['auterized_ventor'];

    $auterizied_club = $row['auterizied_club'];



    // Checking is vendor auterizied 

    If ($type == "vendor") {
        $level = $auterized_ventor;
    }

    // Checking is club auterizied 

    If ($type == "club") {
        $level = $auterizied_club;
    }


    return($level);
}

//**********************************************************************END FUNCTION****************************************************
//=====================================================================START FUNCTION====================================================


function set_session($vehicle) {
    $_SESSION['vehicle'] = $vehicle;
}

//*******************************************************************END FUNCTION********************************************************
//==================================================================START FUNCTION=======================================================

/* 				
  function check_for_photo()
  {
  $testType = 'start: ';
  require("connection.php");
  // $con = require("connection.php");
  $query = "SELECT * ";
  $query .= "FROM photo_request ";



  $result_set = mysql_query($query, $con)
  or die ('Query failed: ' . mysql_error());

  while($row = mysql_fetch_array($result_set))
  { // start while


  $photo_veh_year = $row['photo_veh_year'];

  $photo_veh_model  = $row['photo_veh_model'];
  $index_number = $row['index_number'];
  $photo_code_ext_color = $row['photo_code_ext_color'];
  $photo_code_int_color  = $row['photo_code_int_color'];
  $photo_code_ext = $row['photo_code_ext'];
  $photo_code_int  = $row['photo_code_int'];
  $photo_status  = $row['photo_status'];
  $dealer_number  = $row['dealer_number'];
  $photo_veh_trim   = $row['photo_veh_trim'];
  $photo_veh_code_descrip   = $row['photo_veh_code_descrip'];
  $photo_corp   = $row['photo_corp'];

  if($photo_status == 'compl'){
  continue;
  }

  if($photo_status == 'delete'){

  $from = 'delete';
  removeAdd($index_number, $from);
  continue;
  }

  if($photo_status == 'uploaded'){
  continue;
  }
  $e = 0;
  $i = 0;
  $query = "SELECT * ";
  $query .= "FROM photo1 ";
  $query .= "WHERE corporation = '{$photo_corp}' " ;
  $query .= "AND photo_year = '{$photo_veh_year}' " ;
  $query .= "AND photo_model = '{$photo_veh_model}' " ;
  $query .= "AND photo_trim = '{$photo_veh_trim}' " ;
  $query .= "AND photo_color = '{$photo_code_ext_color}' " ;

  $query .= "AND photo_descrip = '{$photo_veh_code_descrip}' " ;
  $query .= "AND photo_code = '{$photo_code_ext}' " ;

  $result_set1 = mysql_query($query, $con)
  or die ('Query failed: ' . mysql_error());

  $row1 = mysql_fetch_array($result_set1);
  $photo_type  = $row1['photo_type'];
  if(!empty($photo_type)){
  $photo_type  = $row1['photo_type'];
  $testType .= 'Int: ';
  $testType .= $photo_type;
  $testType .= "<br />	";
  $e = 1;
  $photo_num1  = $row1['photo_num'];

  }



  $query = "SELECT * ";
  $query .= "FROM photo1 ";
  $query .= "WHERE corporation = '{$photo_corp}' " ;
  $query .= "AND photo_year = '{$photo_veh_year}' " ;
  $query .= "AND photo_model = '{$photo_veh_model}' " ;
  $query .= "AND photo_trim = '{$photo_veh_trim}' " ;
  $query .= "AND photo_color = '{$photo_code_int_color}' " ;
  $query .= "AND photo_descrip = '{$photo_veh_code_descrip}' " ;
  $query .= "AND photo_code = '{$photo_code_int}' " ;

  $result_set2 = mysql_query($query, $con)
  or die ('Query failed: ' . mysql_error());

  $row2 = mysql_fetch_array($result_set2);
  $photo_type  = $row2['photo_type'];
  if(!empty($photo_type)){

  $testType .= 'Int: ';
  $testType .= $photo_type;
  $testType .= "<br />	";
  $i = 1;
  $photo_num2  = $row2['photo_num'];

  }


  if($i == 1 && $e == 1){

  mysql_query("UPDATE photo_request SET photo_status = 'compl'
  WHERE index_number = '$index_number' ");

  mysql_query("UPDATE photo_request SET photo_num_ext  = '$photo_num1'  WHERE index_number = '{$index_number }' ");

  mysql_query("UPDATE photo_request SET photo_num_int  = '$photo_num2'  WHERE index_number = '{$index_number }' ");
  }else{

  if($i == 1 ){

  mysql_query("UPDATE photo_request SET photo_status = 'ext'
  WHERE index_number = '$index_number' ");

  mysql_query("UPDATE photo_request SET photo_num_int  = '$photo_num2'  WHERE index_number = '{$index_number }' ");
  }


  if($e == 1){

  mysql_query("UPDATE photo_request SET photo_status = 'int'
  WHERE index_number = '$index_number' ");

  mysql_query("UPDATE photo_request SET photo_num_ext  = '$photo_num1'  WHERE index_number = '{$index_number }' ");
  }
  }
  }
  return $testType;
  } */
//**********************************************************************END FUNCTION******************************************************************	
// copy directory----------------------------------------------------------------------

function copy_directory($source, $destination) {
    chdir('C:\xampp\htdocs\dealer');
    if (is_dir($source)) {
        @mkdir($destination);
        $directory = dir($source);
        while (FALSE !== ( $readdirectory = $directory->read() )) {
            if ($readdirectory == '.' || $readdirectory == '..') {
                continue;
            }
            $PathDir = $source . '/' . $readdirectory;
            if (is_dir($PathDir)) {
                copy_directory($PathDir, $destination . '/' . $readdirectory);
                continue;
            }
            copy($PathDir, $destination . '/' . $readdirectory);
        }

        $directory->close();
    } else {
        copy($source, $destination);
    }
}

// end copy directory******************************************************************

function deleteDirectory($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dirPath . "/" . $object) == "dir") {
                    deleteDirectory($dirPath . "/" . $object);
                } else {
                    unlink($dirPath . "/" . $object);
                }
            }
        }
        reset($objects);
        rmdir($dirPath);
    }
}

//*********************************************************************************
// Remove record from photo_request and put in compl_photo

function removeAdd($index, $from) {

    require("connection.php");
    //$con = require("connection.php");
    $query = "SELECT * ";
    $query .= "FROM photo_request ";
    $query .= "WHERE index_number  = $index ";

    $result_set = mysql_query($query, $con)
            or die('Query failed 1100 functions: ' . mysql_error() . $query);

    $row = mysql_fetch_array($result_set);

    $index_number = $row['index_number'];
    $dealer_number = $row['dealer_number'];
    $stock_number = $row['stock_number'];
    $photo_veh_year = $row['photo_veh_year'];
    $photo_veh_make = $row['photo_veh_make'];
    $photo_veh_model = $row['photo_veh_model'];
    $photo_veh_trim = $row['photo_veh_trim'];
    $photo_veh_code_descrip = $row['photo_veh_code_descrip'];
    $photo_code_ext_color = $row['photo_code_ext_color'];
    $photo_code_int_color = $row['photo_code_int_color'];
    $photo_code_ext = $row['photo_code_ext'];
    $photo_code_int = $row['photo_code_int'];
    $photo_num_ext = $row['photo_num_ext'];
    $photo_num_int = $row['photo_num_int'];
    $photo_status = $row['photo_status'];
    $photo_corp = $row['photo_corp'];
    $photo_all_code = $row['photo_all_code'];
    $photo_requested_by = $row['photo_requested_by'];
    $photo_num_ext = $row['photo_num_ext'];
    $photo_num_int = $row['photo_num_int'];
    $photo_vin = $row['photo_vin'];

    $sql = "INSERT INTO compl_photo(compl_dealer_num, compl_stock_num, compl_veh_year, compl_veh_make, compl_veh_model, compl_veh_trim, compl_veh_code_descrip, compl_code_ext_color, compl_code_int_color, compl_code_ext, compl_code_int, compl_status, compl_requested_by, compl_all_code, compl_num_ext, compl_num_int, compl_corp, compl_vin) 
    VALUES('$dealer_number','$stock_number','$photo_veh_year','$photo_veh_make','$photo_veh_model','$photo_veh_trim','$photo_veh_code_descrip','$photo_code_ext_color','$photo_code_int_color','$photo_code_ext','$photo_code_int','$from','$photo_requested_by','$photo_all_code','$photo_num_ext','$photo_num_int','$photo_corp', '$photo_vin')";

    if (!mysql_query($sql, $con)) {
        die('Error 1131 function: ' . mysql_error());
    }


    mysql_query("DELETE FROM photo_request         
        WHERE index_number = '$index_number' ");
}

//*********************************************************************************
// Remove record from photo_request and put in compl_photo

function removeAddVideo($index, $from) {

    require("connection.php");
    //$con = require("connection.php");
    $query = "SELECT * ";

    $query .= "FROM video_request ";
    $query .= "WHERE vr_index  = $index ";


    $result_set = mysql_query($query, $con)
            or die('Query failed removeAddVideon: ' . mysql_error());

    $row = mysql_fetch_array($result_set);

    $vr_index = $row['vr_index'];
    $vr_dealer = $row['vr_dealer'];
    $vr_stock = $row['vr_stock'];
    $vr_year = $row['vr_year'];
    $vr_make = $row['vr_make'];
    $vr_model = $row['vr_model'];
    $vr_trim = $row['vr_trim'];
    $vr_descrip = $row['vr_descrip'];
    $vr_code_ext_color = $row['vr_code_ext_color'];
    $vr_code_int_color = $row['vr_code_int_color'];
    $vr_code_ext = $row['vr_code_ext'];
    $vr_code_int = $row['vr_code_int'];
    $vr_num_ext = $row['vr_num_ext'];
    $vr_num_int = $row['vr_num_int'];
    $vr_status = $row['vr_status'];
    $vr_corp = $row['vr_corp'];
    $vr_all_code = $row['vr_all_code'];
    $vr_all_used_code = $row['vr_all_used_code'];
    $vr_requested_by = $row['vr_requested_by'];
    $vr_ext_color = $row['vr_ext_color'];
    $vr_int_color = $row['vr_int_color'];
    $vr_vin = $row['vr_vin'];


    $query = "SELECT * ";
    $query .= "FROM compl_video ";
    $query .= "WHERE v_compl_dealer_num  = '{$vr_dealer}' ";
    $query .= "AND v_compl_stock_num = '{$vr_stock}' ";

    $result_set = mysql_query($query, $con)
            or die('Query failed comple_video: ' . mysql_error());

    $row = mysql_fetch_array($result_set);
    $v_compl_stock_num = $row['v_compl_stock_num'];

    if (empty($v_compl_stock_num)) {
        $sql = "INSERT INTO compl_video(v_compl_dealer_num, v_compl_stock_num, v_compl_veh_year, v_compl_veh_make, v_compl_veh_model, v_compl_veh_trim, v_compl_veh_code_descrip, v_compl_code_ext_color, v_compl_code_int_color, v_compl_code_ext, v_compl_code_int, v_compl_status, v_compl_requested_by, v_compl_all_code, v_compl_used_code, v_compl_num_ext, v_compl_num_int, v_compl_corp, v_compl_vin) 
          VALUES('$vr_dealer','$vr_stock','$vr_year','$vr_make','$vr_model','$vr_trim','$vr_descrip','$vr_code_ext_color','$vr_code_int_color','$vr_code_ext','$vr_code_int','$from','$vr_requested_by','$vr_all_code','$vr_all_used_code','$vr_num_ext','$vr_num_int','$vr_corp', '$vr_vin')";

        if (!mysql_query($sql, $con)) {
            die('Error: ' . mysql_error());
        }
    }
    mysql_query("DELETE FROM video_request         
 WHERE vr_index = '$vr_index' ");
}

//end of function
//************************************************************************

function check_field($field1, $field2) {  // START FUNCTION
    require("connection.php");
    // $con = require("connection.php");
    //$con = require("includes/connection.php");
    $query = "SELECT * ";
    $query .= "FROM customer ";
    $query .= "WHERE $field1 = '{$field2}' ";



    $result_set = mysql_query($query, $con)
            or die('Query failed: ' . mysql_error());
    $row = mysql_fetch_array($result_set);
    $user_name = $row['user_name'];
    $corp_name = $row['corp_name'];


    if ($field1 == 'user_name') {
        if (empty($user_name)) {
            return false;
        } else {
            return true;
        }
    }

    if ($field1 == 'corp_name') {
        if (empty($corp_name)) {
            return false;
        } else {
            return true;
        }
    }
}

//**************************************************************************************************
/*
  function download($tmpCompany, $slash) {

  if(extension_loaded('zip')){ // Start if for checking ZIP extension is available


  $zip = new ZipArchive(); // Load zip library

  $zip_name = time().".zip"; // Zip name
  if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){ // Start if

  // Opening zip file to load files
  exit("cannot open <$filename>\n");

  }





  // Checking files are selected


  $dir_array = scandir($tmpCompany);

  foreach($dir_array as $files){ // if for stock number

  if($files == '.'){
  continue;
  }

  if($files == '..'){
  continue;
  }
  echo  $tmpCompany;
  echo "<br />";
  echo $files;
  echo "<br />";
  $file_folder = $tmpCompany . $slash . $files . $slash ;
  echo $file_folder;
  echo "<br />";
  echo "<hr />";

  $photo_array = scandir($file_folder);



  foreach($photo_array as $file){ // start foreach for photo

  if($file == '.'){
  continue;
  }

  if($file == '..'){
  continue;
  }



  $zip->addFile($file_folder.$file,$files . $slash . $file); // Adding files into zip
  }// End foreach for photo
  $zip->close();

  }// End foreach for stock number



  if(file_exists($zip_name)){




  // push to download the zip
  header("Cache-Control: public");
  header("Content-Description: File Transfer");
  header('Content-type: application/zip');
  header('Content-Disposition: attachment; filename="'.$zip_name.'"');
  header("Content-Transfer-Encoding: binary");



  readfile($zip_name);


  // remove zip file is exists in temp path
  unlink($zip_name);


  $zip->close();




  }




  }else{
  echo "* You dont have ZIP extension *";
  } // End if else for checking ZIP extension is available

  }//end function download */

//**********************************************************************************************************************
//Activity Report

function activity_report($type) {
    require_once("constants.php");
    require_once("connection.php");

    $add = 0;
    $request = 0;
    $have = 0;
    $remove = 0;
    $resummit = 0;

    $user_id = $_COOKIE["userId"];
    $date = date("Y/m/d");

    $query = "SELECT * ";
    $query .= "FROM activity ";
    $query .= "WHERE act_user   = '{$user_id}' ";
    $query .= "AND act_date = '{$date}' ";

    $result_set = mysqli_query($con, $query)
    or die('Query failed activity_report 1280: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);

    $act_index = $row['act_index'];
    $act_user = $row['act_user'];
    $act_date = $row['act_date'];
    $act_request = $row['act_request'];
    $act_add = $row['act_add'];
    $act_have = $row['act_have'];
    $act_remove = $row['act_remove'];
    $act_resummit = $row['act_resummit'];

    if (empty($act_index)) {

        if ($type == 'request') {
            $request = 1;
        }
        if ($type == 'add') {
            $add = 1;
        }
        if ($type == 'have') {
            $have = 1;
        }
        if ($type == 'remove') {
            $remove = 1;
        }
        if ($type == 'resummit') {
            $resummit = 1;
        }
        $sql = "INSERT INTO activity(act_user, act_date, act_request, act_add, act_have, act_remove, act_resummit) 
          VALUES('$user_id','$date','$request','$add','$have','$remove','$resummit')";

        if (!mysql_query($sql)) {
            die('Error2: ' . mysql_error());
        }
    } else {

        if ($type == 'request') {
            $request = $act_request + 1;
            mysql_query("UPDATE activity SET act_request = '$request'        
             WHERE act_index = '$act_index' ");
        }
        if ($type == 'add') {
            $add = $act_add + 1;
            mysql_query("UPDATE activity SET act_add = '$add'        
             WHERE act_index = '$act_index' ");
        }
        if ($type == 'have') {
            $have = $act_have + 1;
            mysql_query("UPDATE activity SET act_have = '$have'        
             WHERE act_index = '$act_index' ");
        }
        if ($type == 'remove') {
            $remove = $act_remove + 1;
            mysql_query("UPDATE activity SET act_remove = '$remove'        
             WHERE act_index = '$act_index' ");
        }
        if ($type == 'resummit') {
            $resummit = $act_resummit + 1;
            mysql_query("UPDATE activity SET act_resummit = '$resummit'        
             WHERE act_index = '$act_index' ");
        }
    }
}

//********************************************************************************************************************
//                                                check if month is valid

function valid_month($month) {
    switch ($month) {

        case '01':
            return true;
            break;

        case '02':
            return true;
            break;

        case '03':
            return true;
            break;

        case '04':
            return true;
            break;

        case '05':
            return true;
            break;

        case '06':
            return true;
            break;

        case '07':
            return true;
            break;

        case '08':
            return true;
            break;

        case '09':
            return true;
            break;

        case '10':
            return true;
            break;

        case '11':
            return true;
            break;

        case '12':
            return true;
            break;

        default:
            return false;
    }
}

//**************************************************************************************************************
//                                Check valid day


function valid_day($day) {
    switch ($day) {

        case '01':
            return true;
            break;

        case '02':
            return true;
            break;

        case '03':
            return true;
            break;

        case '04':
            return true;
            break;

        case '05':
            return true;
            break;

        case '06':
            return true;
            break;

        case '07':
            return true;
            break;

        case '08':
            return true;
            break;

        case '09':
            return true;
            break;

        case '10':
            return true;
            break;

        case '11':
            return true;
            break;

        case '12':
            return true;
            break;

        case '13':
            return true;
            break;

        case '14':
            return true;
            break;

        case '15':
            return true;
            break;

        case '16':
            return true;
            break;

        case '17':
            return true;
            break;

        case '18':
            return true;
            break;

        case '19':
            return true;
            break;

        case '20':
            return true;
            break;

        case '21':
            return true;
            break;

        case '22':
            return true;
            break;

        case '23':
            return true;
            break;

        case '24':
            return true;
            break;

        case '25':
            return true;
            break;

        case '26':
            return true;
            break;

        case '27':
            return true;
            break;

        case '28':
            return true;
            break;

        case '29':
            return true;
            break;

        case '30':
            return true;
            break;

        case '31':
            return true;
            break;

        default:
            return false;
    }
}

//**************************************************************************************************************
//                                Check valid day


function equipment2($type, $model) {
    require_once("constants.php");
    require_once("connection.php");
    $line_array = array();
    $category_array = array("COMFORT", "PERFORMANCE", "SAFTY", "STYLE");

    $c = 0;
    while ($c <= 3) {
        $category = $category_array[$c];
        $line_array[] = "<hr />";
        $line_array[] = "<center><h2>$category</h2></center>";
        $line_array[] = "<table style=\"width:100%\">";
        $line_array[] = "<tr>";
        $c++;
        $x = 0;
        //$line_array[] = "<tr>";
        $query = "SELECT * ";
        $query .= "FROM equipment ";
        $query .= "WHERE equipment_type   = '{$type}' ";
        $query .= "AND equipment_model   = '{$model}' ";
        $query .= "AND equipment_category   = '{$category}' ";
        $query .= "ORDER BY equipment_category_order, equipment_name ";


        $result_set = mysql_query($query, $con)
                or die('Query failed1: ' . mysql_error());
        while ($row = mysql_fetch_array($result_set)) {

            $equipment_name = $row['equipment_name'];
            $equipment_code = $row['equipment_code'];
            $equipment_category = $row['equipment_category'];
            $x++;

            $row1 = "<td><h4><input type=\"checkbox\" name= \"$equipment_code\" value= $equipment_code />$equipment_name</h4></td>";

            $row2 = "<td><h4><input type=\"checkbox\" name=\"$equipment_code\" value=\"$equipment_code\" checked />$equipment_name</h4></td>";


            if (empty($_POST[$equipment_code])) {

                $line_array[] = $row1;
            } else {

                $line_array[] = $row2;
            }
            if ($x == 3) {
                $x = 0;

                $line_array[] = "</tr>";
            }
        }

        $line_array[] = "</table>";
    }
    return $line_array;
}

//*****************************************************************************

function cents($total) {

    $costArry = explode(".", $total);

    if (isset($costArry['1'])) {
        $cost1 = $costArry['1'];
        if ($cost1 < 10) {
            $total = $total . '0';
        }
    } else {
        $total = $total . '.00';
    }
    return $total;
}

//******************************************************************************

function removeWhiteSpace($field) {
    $field = chunk_split($field, 1, ".");
    $fieldArray = explode(".", $field);
    $newField = '';

    foreach ($fieldArray as $value) {

        if ($value == ' ') {
            continue;
        } else {
            $newField .= $value;
        }
    }

    return $newField;
}

//******************************************************************************

function text($employee, $message, $name, $type, $company_number) {


    require_once("constants.php");
    require_once("connection.php");
    $query = "SELECT * ";
    $query .= "FROM text ";
    $query .= "WHERE text_index   = '{$employee}' ";


    $result_set = mysql_query($query)
            or die('Query failed: ' . mysql_error());
    $row = mysql_fetch_array($result_set);

    $text_employee_name = $row['text_employee_name'];
    $text_phone = $row['text_phone'];
    $text_department = $row['text_department'];




    mail("$text_phone", "", "$message", "From: $name \r\n");


    if ($text_department == 'Shop') {
        $nowTime = date('YmdHis');
        $sql = "INSERT INTO test_screen_message(sm_sent_by, sm_name, sm_for, sm_type, sm_message, sm_time, sm_company) 
                     VALUES('$customer_number','$name','$text_employee_name','$type', '$message','$nowTime','$company_number')";

        if (!mysql_query($sql, $con)) { // start if
            die('Error: ' . mysql_error());
        }
    }
}

//*******************************************US Currency***************************************************************************************

function currency($price) {

    $c = 0;
    $p = explode(".", $price);
    $c = count($p);

    if (!empty($price)) {

        if ($c != 2) {
            return 'Cents need to be added.';
        }
    }
    $price_array = str_split("$price");


    $result = count($price_array);
    $i = 0;
    while ($i < $result) {

        $n = $price_array[$i];


        switch ($n) {
            case ".":
                break;

            case "1":
                break;

            case "2":
                break;

            case "3":
                break;

            case "4":
                break;

            case "5":
                break;

            case "6":
                break;

            case "7":
                break;

            case "8":
                break;

            case "9":
                break;

            case "0":
                break;

            case "":
                break;

            default:

                return 'Only Currency is accepted.  A ' . '( ' . $n . ' )' . ' has been entered.';
        }

        $i++;
    }


    return 'good';
}

//*******************************************************Differance in Time*****************************************************************************																													

function diff_time($startTime) {


    date_default_timezone_set("America/Chicago");




    $sp = explode(" ", $startTime);
    $sDate = $sp[0];

    $sTime = $sp[1];
    $sT = explode(":", $sTime);
    $sHour = $sT[0];

    $sMin = $sT[1];

    $sSec = $sT[2];

    //************************ 

    $today = date("Y-m-d H:i:s");

    $tsp = explode(" ", $today);

    $nDate = $tsp[0];


    $nTime = $tsp[1];
    $nT = explode(":", $nTime);
    $nHour = $nT[0];

    $nMin = $nT[1];

    $nSec = $nT[2];

    $date1 = date_create($sDate);
    $date2 = date_create($nDate);
    $diff = date_diff($date1, $date2);


    $totalDays = $diff->format("%a ");
    $testDay = 0;
    $testHour = 0;
    $testMin = 0;


    $dSec = $nSec - $sSec;


    if ($dSec < 0) {
        $nSec = $nSec + 60;
        $dSec = $nSec - $sSec;

        $testMin = 1;
    }

    $dMin = $nMin - $sMin;


    if ($dMin < 0) {
        $nMin = $nMin + 60;
        $testHour = 1;
        $dMin = $nMin - $sMin;
    }


    $dHour = $nHour - $sHour;

    if ($dHour < 0) {
        $nHour = $nHour + 24;
        $dHour = $nHour - $sHour;
        $testDay = 1;
    }

    $totalDays = $totalDays - $testDay;
    $dHour = $dHour - $testHour;
    $dMin = $dMin - $testMin;

    $howLong = 'Days: ' . $totalDays . ' Hours: ' . $dHour . ' Minutes: ' . $dMin . ' Seconds: ' . $dSec;
    return $howLong;
}

//****************************************                 *********************************************
function check_working($startTime, $empNum, $dateOfWeek) {

  $pass = '';


    
    
    $query1 = "SELECT * ";
    $query1 .= "FROM time_off ";
    $query1 .= "WHERE off_emp_num  = '{$empNum}' ";

    $result_set1 = mysql_query($query1)
            or die('Query failed: ' . mysql_error());
    while ($row1 = mysql_fetch_array($result_set1)) {


        $off_from = $row1['off_from'];
        $off_to = $row1['off_to'];
        $off_employee = $row1['off_employee'];



        if ($dateOfWeek >= $off_from && $dateOfWeek <= $off_to) {
            $pass = 'n';
            break;
        }
    }
     
    return $pass;
}


?>


