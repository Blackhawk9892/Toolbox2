
	<?php 
	//require_once("constants.php");
  date_default_timezone_set('America/Chicago'); 
        
 $con = mysqli_connect("45.41.235.33:3306","pfdealer_Dumouchel","Dumouchel-9892","pfdealer_boucher");
 // $con = mysqli_connect("45.41.235.101:3306","pfdealer_Dumouchel","Dumouchel-9892","pfdealer_auto_dealer");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();      
}     
?>