<?php


//////////////////////////// Remove white space /////////////////////////////////////

function Remove_Whites($field) {
    
           
    $str = bin2hex($field);
    
    $chunk = chunk_split($str,2,".");
    
   
    $arryWord = explode(".",$chunk);
 
   
   
    $count = count($arryWord);
    $newWord ='';
    $test = 0;
    foreach ($arryWord as $word) {
     
     if($word == '20' and $test == 0){
        continue;
     }else{
          $newWord .= $word;
          $test = 1;
     }
       
   }
   
   
   /////////////////////////////////////////////////////////////////

   $field = strrev($newWord);
   
   
   //$str = bin2hex($field);
   
    $chunk = chunk_split($field,2,".");
   
   
    $arryWord = explode(".",$chunk);
   
   
   
    $count = count($arryWord);
    $newWord ='';
    $test = 0;
    foreach ($arryWord as $word) {
    
     if($word == '02' and $test == 0){
        continue;
     }else{
          $newWord .= $word;
          $test = 1;
     }
       
   }
   
   $newWord = strrev($newWord);
     $wordNew = hex2bin($newWord);
    
   
              
             return $wordNew;
 }


             //////////////////////////// Payments //////////////////////////////////////


function Payments($loan, $inter, $months, $down) {
  // $paymentInfo - array();
   $salePrice = $loan;
   $percentage = $down / 100;
   $downPayment = $loan * $percentage;                
   $downPayment = round($downPayment);
   $loan = $loan - $downPayment;

     //  $inter = 6;
       // $months = $_POST['months']
$interest = $inter /100/12;//10 is the interest rate
//$months = 360; //60 months term
//$loan = 100000;// total loan amount

$monthly_payment = $loan * $interest / (1-(pow((1+$interest),-$months)));
$monthly_payment = round($monthly_payment);
$total_payable = $monthly_payment * $months;
$total_interest = ($total_payable - $loan);

$paymentInfo[] = $monthly_payment;
$paymentInfo[] = $total_payable;
$paymentInfo[] = $total_interest;
$paymentInfo[] = ($total_interest / $months);
$paymentInfo[] = $downPayment;
$paymentInfo[] = $salePrice;
$paymentInfo[] = $loan;
$paymentInfo[] = $inter;
$paymentInfo[] = $interest;


return $paymentInfo;

}

function clear_table() {

  $con = require_once("../includes/connection.php");
  $d = 'd';
 mysqli_query($con, "DELETE FROM excel_file WHERE excel_delete = '$d' " );

}
?>