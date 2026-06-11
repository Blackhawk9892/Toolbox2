
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>New Employee Password </title>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
 
		 <?php
		 
$_SESSION['page'] = 'new_password_manager.php';
require("includes/connection.php");
        
require("includes/database_rows.php");
require("includes/pull_downs.php");
require("includes/security.php");
require("toolbar_sales.php");

$_SESSION['reg']='new_password_manager.php';	
			
       
        if(isset($_COOKIE["userId"])){
            $userId = $_COOKIE["userId"];
        
         

        $emp_arry = Employee($userId);
        $first = $emp_arry[0];
        $last = $emp_arry[1];      
        $position = $emp_arry[2];
        $emp_id = $emp_arry[3];
        $dealer_id = $emp_arry[4];
		$emp_user_name = $emp_arry[5];
      //  $emp_group = $emp_arry[6];
        
        }

									
print "<link rel=\"stylesheet\" type=\"text/css\" href=\"stylesheets/main.css\" /> ";	

    if($position == 'pfd'){
		$emp_position = 'manager';
	}

    
    $blank = "";
            if(isset($_POST['user_name'])){
	$year_arr[] = "\n<option value=\"$_POST[user_name]\">$_POST[user_name]</option>\n";
        
	$query = "SELECT * ";
	$query .= "FROM employee ";
	$query .= "WHERE emp_id = '{$userId}' ";

   
	$result_set = mysqli_query($con, $query)
	or die('Query failed employee 57: ' . mysqli_error($con));
	
	$row = mysqli_fetch_array($result_set);
	$User_Name = $row['emp_user_name'];
	$emp_dealer_id = $row['emp_dealer_id'];
	
	$query = "SELECT * ";
	$query .= "FROM company ";
	$query .= "WHERE comp_id  = '{$dealer_id}' ";

   
	$result_set = mysqli_query($con, $query)
	or die('Query failed dealer 69: ' . mysqli_error($con));
	
	$row = mysqli_fetch_array($result_set);
	$company_name = $row['comp_name'];
	
        
         echo"<center><h3>$company_name</h3></center>";
        // echo"<center><h3>$name</h3></center>";
	 }else{
	 $year_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
	 }
	
	$query = "SELECT * ";
	$query .= "FROM employee ";
	$query .= "WHERE emp_dealer_id = '{$dealer_id}' " ;												
	$query .= "ORDER BY emp_user_name ASC ";
												 
												
				                
	$result_set = mysqli_query($con, $query)
	or die('Query failed employee 89: ' . mysqli_error($con));
	    while($row = mysqli_fetch_array($result_set))
	    { // start while	
	    $nameUser   = $row['emp_user_name'];
																			
	    $user_arr[] = "\n<option value=\"$nameUser\">$nameUser</option>\n"; 
																															
	    }
$errors = array();	
			
			 											 
			
    if(isset($_POST['submit'])){
	$user_name = $_POST['user_name'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
		
        $required_fields = array('user_name', 'password1', 'password2');
	
            foreach($required_fields as $fieldname){
		if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])){
		    if($fieldname == 'code1'){
			$errors[] = 'This field is empty: Equipment Group';
		    }else{
		          $errors[] = 'This field is empty: '.$fieldname;
		    }
		}
            }
		
	
																	
																				
	    if (!empty($errors)){
		foreach($errors as $value){                              
		echo "<center><h3>$value</h3></center> \n"; 
		}  //end for 4			                             
            }else{																	
	        $password = $password1;
		$hash_password = sha1(sha1($password));

		mysqli_query($con,"UPDATE employee SET emp_password = '$hash_password'
		WHERE emp_user_name  = '$user_name' ");


$value = ' The password for User Name ' . $user_name . ' password has been changed';
echo "<div class=\"problem\">$value</div>";


$query = "SELECT * ";
	$query .= "FROM employee ";
	$query .= "WHERE emp_user_name = '{$user_name}' ";
   
   
	$result_set = mysqli_query($con, $query)
	or die('Query failed employee 146: ' . mysqli_error($con));
	
	$row = mysqli_fetch_array($result_set);
	$emp_first_name = $row['emp_first_name'];
	$emp_last_name = $row['emp_last_name'];

	$name = $emp_first_name . ' ' . $emp_last_name;


	$value = ' The password for employee ' . $name . ' password has been changed';
     echo "<div class=\"problem\">$value</div>";
																				
                                          
																	
																	 
																	 
			}//end else				         

	}		
	?>
<center>
<br />
<br />
<center><h2>Change Employee Password</h2></center>



	<table>
	
<form action='new_password_manager.php' method='post'>

<?php  
   print( "<tr><td>User Name::</td><td>\n" );
    print( "<select name=\"user_name\">" );
    print_r($user_arr);
 ?>


<tr><td>Password:</td><td>
<input type='password' name='password1' size='30' value='' />

<tr><td> Re-enter password:</td><td>
<input type='password' name='password2' size='30' value=''/>

</table>
<input type='submit' name='submit' value='Submit'/>



		 
	
 

</body>

</html>


