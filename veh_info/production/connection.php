
	<?php 
	require_once("constants.php");
        
        
  $database = 'pfdstock_tools'; 
  /*
  echo   'DB_SERVER_HOST: ' . DB_SERVER_HOST . '<br>';
  echo   'DB_USER: ' . DB_USER . '<br>';
  echo   'DB_PASS: ' . DB_PASS . '<br>';
  echo   'database: ' . $database . '<br>';
*/
$con = mysqli_connect(DB_SERVER_HOST,DB_USER,DB_PASS,$database);
//$con = mysqli_connect(DB_SERVER_HOST,DB_USER,DB_PASS,DB_SERVER);

if (!$con)
  {
  die('Could not connect: ' . mysqli_error());
  }

mysqli_select_db($con,'stocktaginfo');


  return $con
?>