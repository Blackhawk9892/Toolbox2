
	<?php 
	require_once("constants.php");


	date_default_timezone_set('America/Chicago');
 $password = '';
 $database = 'toolbox';
 echo $database;
	 $con = mysqli_connect(DB_SERVER_HOST,DB_USER,$password,$database);
        // $con = mysqli_connect('localhost','root,);
if (!$con)
  {
  die('Could not connect : ' . mysqli_error());
  }
 


  return $con
?>
