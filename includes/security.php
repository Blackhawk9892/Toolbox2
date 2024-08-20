<?php

/*
 * This is for security
 */
////////////////////Encrypt Data Going Into Table///////////////////////////////

 function Data_In($data) {
    $ALPHA_1 = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
        "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b",
        "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s",
        "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "'", "/", "-",
        "_", "@", ".", " ");

    $ALPHA_2 = array("q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "a", "s",
        "d", "f", "g", "h", "j", "k", "l", "z", "x", "c", "v", "b", "n", "m", "1", "2",
        "3", "4", "5", "6", "7", "8", "9", "0", "*", "Q", "W", "E", "R", "T", "Y", "U", "I",
        "O", "P", "A", "S", "D", "F", "G", "H", "J", "K", "L", "Z", "X", "C", "V", "B", "N", "M", "^", "_",
        "_", ".", "@", "=");

    $newWord = '';
    $count = strlen($data);
    $word = str_split($data);
    foreach ($word as $value) {
        $arrayKey = array_search($value, $ALPHA_1);
        $newWord .= $ALPHA_2[$arrayKey];
    }
   

    return $newWord;
}
////////////////////Unecrypt Data Comming From A Table//////////////////////////

function Data_Out($data) {
    $ALPHA_1 = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L",
        "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b",
        "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s",
        "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "'", "/", "-",
        "_", "@", ".", " ");

    $ALPHA_2 = array("q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "a", "s",
        "d", "f", "g", "h", "j", "k", "l", "z", "x", "c", "v", "b", "n", "m", "1", "2",
        "3", "4", "5", "6", "7", "8", "9", "0", "*", "Q", "W", "E", "R", "T", "Y", "U", "I",
        "O", "P", "A", "S", "D", "F", "G", "H", "J", "K", "L", "Z", "X", "C", "V", "B", "N", "M", "^", "_",
        "_", ".", "@", "=");

    $newWord = '';
    $count = strlen($data);   
    $word = str_split($data);
    foreach ($word as $value) {
        $arrayKey = array_search($value, $ALPHA_2);
        $newWord .= $ALPHA_1[$arrayKey];
    }
    return $newWord;
}
//////////////////////////Puts emcrypt data in order////////////////////////////

function Data_Order($dealer) {  

   require("connection.php");
    require_once("../classes/person.class.php");
    
$sql = "DELETE FROM data_order WHERE do_delete='$dealer'";
$con->query($sql);


    $query = "SELECT * ";
    $query .= "FROM employee ";
    $query .= "WHERE emp_dealer_id  = '{$dealer}' ";

    $result_set = mysqli_query($con, $query)
            or die('employee: 72 ' . mysqli_error($con));

while ($row = mysqli_fetch_array($result_set)){
    $emp_id = $row['emp_id'];
    $emp_dealer_id = $row['emp_dealer_id'];
    $first = $row['emp_first_name'];
    $last = $row['emp_last_name'];
    $emp_position = $row['emp_position'];
    $email = $row['emp_email'];
    $phone = $row['emp_phone'];
    $emp_user_name = $row['emp_user_name'];
    $emp_phone_provider = $row['emp_phone_provider'];



    $obj = new Person($first, $last, $email, $phone);
    $data = $obj->first;
    $first = $obj->unencrypt($data);
    $data = $obj->last;
    $last = $obj->unencrypt($data);
    $data = $obj->email;
    $email = $obj->unencrypt($data);
    $data = $obj->phone;
    $phone = $obj->unencrypt($data);
    $phoneCarrie = $phone . '@' . $emp_phone_provider;
    $data_delete = 'y';
    $name = $first . ' ' . $last;
    
         //$sql = "INSERT INTO data_order(do_id,do_field_1,do_field-2,do_delete) 
           // VALUES('$emp_id','$first','$last','$data_delete')";
         $sql = "INSERT INTO data_order(do_emp_id,do_field_1,do_field_2,do_delete) 
            VALUES('$emp_id','$name','$emp_position','$dealer')";

            if (!mysqli_query($con, $sql)) {
                die('Error 100: ' . mysqli_error($con));
            }
    }
}
//////////////////////////emcrypt get////////////////////////////

function Encrypt_Get($data) {
    $data_arr = str_split($data);
    $count = count($data_arr);
    $r = rand(10,100);
   $newWord =   Data_In($r);
    $newWord .= Data_In($data);
    $r = rand(10,10000);
    $newWord .= Data_In($r);
    $newWord .= '~';
    $G = str_split($count);
    foreach ($G as $value) {
    $newWord .= Data_In($value);
    }
 return $newWord;   
}
//////////////////////////unemcrypt get////////////////////////////

function Unencrypt_Get($data) {
    $i=0;
    $x=0;
    $newWord = '';
    $c =explode("~",$data);
    $num = $c[0];
    $count = $c[1];
    $count = Data_Out($count);
    $G = str_split($num);
    foreach ($G as $value) {
      $i++;
      if( $i <= $count){
          continue;
      }
      $x++;
      if( $x <= $count){
          
          $test = $x . ' <= ' . $count;
         $newWord .= $value;
      }
        
}
   $newWord = Data_Out($newWord);
    
    
 return $newWord;   
}
function Check_Data($data) {
    
    $check1 = similar_text("!@#$%^*';=+?><",$data);
    $check2 = similar_text('`~',$data);
    $check = $check1 + $check2;
    if($check > 0){
        $newWord = 'fail';
    }else{
        $newWord = 'pass';
    }
    return $newWord; 
}

function Check_Vehicle_Year($year) {
    $nextYear = date("Y") + 2;
    if($year > 1900 and $year < $nextYear){
        $vehYear = 'pass';
    }else{
        $vehYear = 'fail';
    }
    return $vehYear;
}

function Check_Numbers($number) {
    
    if($number > 1 and $number < 999999999){
        $vehNum = 'pass';
    }else{
        $vehNum = 'fail';
    }
    return $vehNum;
}

