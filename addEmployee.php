<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<!--
Add Dealer to stock tag program
-->
<html>
    <head>
        <title>Add Employee</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div>
            <?php
            require_once("includes/constants.php");
            require("includes/connection.php");
            require("includes/database_rows.php");
         
            require("toolbar_sales.php");
           
            if (isset($_POST['back'])) {
                header("Location: select_employee.php");
                exit;
            }
            
        
            
 /////////////////////////////////////////////////////////////////////////////////        
            
        if(isset($_COOKIE["userId"])){
            $userId = $_COOKIE["userId"];
        
         

        $emp_arry = Employee($userId);
        $first = $emp_arry[0];
        $last = $emp_arry[1];      
        $position = $emp_arry[2];
        $emp_id = $emp_arry[3];
        $dealer_id = $emp_arry[4];
        }

            if (isset($_POST['clear'])) {

                $_POST['dealer'] = '';
                $_POST['userName'] = '';
                $_POST['first'] = '';
                $_POST['last'] = '';
                $_POST['position'] = '';
                $_POST['email'] = '';
                $_POST['phone'] = '';
                $_POST['password'] = '';
                $_POST['password2'] = '';
            }


            $blank = '';
            if (isset($_POST['dealer'])) {
                $dealer_arr[] = "\n<option value=\"$_POST[dealer]\">$_POST[dealer]</option>\n";
            } else {
                $dealer_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
            }

            $query = "SELECT * ";
            $query .= "FROM company ";
           
            if($emp_position != 'PFD'){
            $query .= "WHERE comp_id   = '{$position}' ";
            }
            $query .= "ORDER BY comp_name";

            $result_set = mysqli_query($con, $query)
                    or die('Query failed: ' . mysqli_error($con));

            while ($row = mysqli_fetch_array($result_set)) {
                $comp_id = $row['comp_id'];
                $comp_name = $row['comp_name'];
                $dealer = $comp_id . '-' . $comp_name;
                
                $dealer_arr[] = "\n<option value=\"$dealer\">$dealer</option>\n";
               
            }
 



            if (isset($_POST['submit'])) {

                $dealer = $_POST['dealer'];
                $userName = $_POST['userName'];
                $first = ucwords($_POST['first']);
                $last = ucwords($_POST['last']);
                $position = $_POST['position'];
               
                $password = $_POST['password'];
                $password2 = $_POST['password2'];
                $d = explode("-",$dealer);
                $userName = $userName . $d[0];
                $id = $d[0];
                $company = $d[1];

                $required_fields = array('dealer', 'userName', 'first', 'last', 'position','password', 'password2');

                foreach ($required_fields as $fieldname) {

                    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                        $errors[] = 'This field may not be empty: ' . $fieldname;
                    }
                }

                if ($password != $password2) {
                    $errors[] = 'Password and Confirm Password are not the same';
                }
                
               
           
                $query = "SELECT * ";
            $query .= "FROM employee ";
            $query .= "WHERE emp_user_name = '{$userName}' ";
            
            
            $result_set = mysqli_query($con, $query)
                    or die('Query failed: ' . mysqli_error($con));

            $row = mysqli_fetch_array($result_set);
             
               if(isset($row['emp_id'])){
                   $errors[] = 'This user name is being used select a different user name';
               }
                

                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                   
                    $password = sha1(sha1($_POST['password']));

                    $sql = "INSERT INTO employee(emp_dealer_id, emp_dealer_name, emp_user_name, emp_password, emp_first_name, emp_last_name, emp_position, emp_manage_num) 
              VALUES('$id','$company','$userName','$password','$first','$last','$position','$emp_id')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error employee 152: ' . mysqli_error($con));
                    }


                   

                    $_POST['dealer'] = '';
                    $_POST['userName'] = '';
                    $_POST['first'] = '';
                    $_POST['last'] = '';
                    $_POST['position'] = '';
                   
                    $_POST['password'] = '';
                    $_POST['password2'] = '';
                 

                   

                    echo "<div class=\"problem\">Employee $first $last  has been added</div>";
                    echo "<div class=\"problem\">Your username is $userName please write it down.</div>";
                }
            }
            ////////////////////////////////////////////////////////////////////////////////

            $blank = '';
            if (isset($_POST['position'])) {
                $position = $_POST['position'];
                $position_arr[] = "\n<option value=\"$position\">$position</option>\n";
            } else {
                $position_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
            }
            $place = 'Sales';
            $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
           
            $place = 'Manager';
            $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
           
          
            if($emp_position = 'pfd'){
            $place = 'Sales Tool Box';
            $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
            }
            
     


            ?>


            <br>
            <br>
            <form action="addEmployee.php" method="post">

                <center>
                    <table>
<?php

print( "<tr><td>Dealer:</td><td>\n");
print( "<select name=\"dealer\">");
print_r($dealer_arr);

?>
                        <tr><td>User Name:</td><td>
                                <input type="text" name="userName" size="30" value="<?php if (isset($_POST['userName'])) echo $_POST['userName'] ?>" />
                        <tr><td>First Name:</td><td>
                                <input type="text" name="first" size="50" value="<?php if (isset($_POST['first'])) echo $_POST['first'] ?>"	/>

                        <tr><td>Last Name:</td><td>
                                <input type="text" name="last" size="50" value="<?php if (isset($_POST['last'])) echo $_POST['last'] ?>"	/>

                        <tr><td>Position:</td><td>
                
                            <select name="position">
                                <?php
                                print_r($position_arr);
                                ?>


                     
                        <tr><td>Password:</td><td>
                                <input type="text" name="password" size="30" value="<?php if (isset($_POST['password'])) echo $_POST['password'] ?>" />

                        <tr><td>Confirm Password:</td><td>
                                <input type="text" name="password2" size="30" value="<?php if (isset($_POST['password2'])) echo $_POST['password2'] ?>" />

                                 <?php
                     
        ?>
                    </table>    
                </center> 

              
                <br />			
 <center>

             
                <br />
                <br />
                    <input type="submit" name="submit" value="Submit"/>
                    <br />
                     <br />
                    <input type="submit" name="back" value="Back"/>
                    <br />
                    <br />
                    <input type="submit" name="clear" value="Clear"/>

                </center> 

        </div>
    </body>
</html>
