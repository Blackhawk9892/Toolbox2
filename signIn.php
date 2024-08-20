<?php

session_start();

?>	

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sign In </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
            body  {
                background-image: url("images/backround.jpg");
                height: 800px;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
            }
        </style>
       
    
    
    </head>
    <body>
   
        <?php
   
        require_once("includes/connection.php");
        require("includes/security.php");


        if(!isset($_SESSION['endTime'])){
            $_SESSION['endTime'] = 0;
           }
           if(!isset($_SESSION['atempts'])){
            $_SESSION['atempts'] = 0;
           }

     

        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            echo "<div class=\"errors\">$message</div>";
            unset($_SESSION['message']);
        }

        $missing = 0;
        if (isset($_POST['sign'])) {

            if (empty($_POST['userName'])) {
                $errors[] = 'User Name is empty';
                $missing = 1;
            }else{
                $userName = $_POST['userName'];
                $dataCheck = Check_Data($userName);
            }
            if (empty($_POST['password'])) {
                $errors[] = 'password is empty';
                $missing = 1;
            }

         
           
            if ($dataCheck == 'fail') {
                $errors[] = 'No symbols may be used. Only alphanumeric value may be used in username';
                $missing = 1;
            }

            // $password = $_POST['userName'];
            $password = sha1(sha1($_POST['password']));
            $userName = $_POST['userName'];
          
            $dataCheck = Check_Data($userName);
            if ($missing == 0) {
                $query = "SELECT * ";
                $query .= "FROM employee ";
                $query .= "WHERE emp_user_name   = '{$userName}' ";

   
                $result_set = mysqli_query($con, $query)
                        or die('Query failed emp 88: ' . mysqli_error($con));
                $row = mysqli_fetch_array($result_set);

                if(empty($row)){
                    $errors[] = 'Not a valid entry for user name';
                }else{ 
                    
                    $emp_id = $row['emp_id'];                    
                    $emp_dealer_id = $row['emp_dealer_id'];
                    $emp_user_name = $row['emp_user_name'];
                    $emp_password = $row['emp_password'];
                    $emp_position = $row['emp_position'];
                
         
           
            if ($password != $emp_password) {
                $errors[] = 'Password is Incorrect';
            
            }
         
        }
}

   $_SESSION['startTime'] =  date("h:i");
   $_SESSION['atempts'] = 1 + $_SESSION['atempts']; 

   if($_SESSION['atempts'] >= 3){
   
    if($_SESSION['startTime'] >=  $_SESSION['endTime']){    
        $_SESSION['atempts'] = 0;
       }
    $d=strtotime("+5 minutes");
    $_SESSION['endTime'] =  date("h:i", $d); 
    }
   

  
   if($_SESSION['atempts'] >= 3){
      if($_SESSION['startTime'] <  $_SESSION['endTime']){
     
        $errors[] = 'You my try again after ' .  $_SESSION['endTime'] .'. If you try before this time it will increase the time that you can try again.';
    }
   }

   

            if (!empty($errors)) {

                foreach ($errors as $value) {
                    echo"<center><h4 style='color:red;'>$value </h4> </center>";
              
                }
            } else {
                $pass = '';
                for ($x = 0; $x <= 10; $x++) {
                    $pass .= rand(1, 100);
                }

          $_SESSION['endTime'] = 0;
         $_SESSION['atempts'] = 0;

                $set = time() + 28800;
                           
                setcookie("pass", $pass, $set);

                setcookie("userId", $emp_id, $set);
                $set = time() + 28800;
                setcookie("dealerId", $emp_dealer_id, $set);
                $set = time() + 28800;
                setcookie("position", $emp_position, $set);

                $userId = $_COOKIE["userId"];
                
             //   $sql = ' UPDATE employee SET emp_pass=' . $pass . ' WHERE emp_id=' . $emp_id;


        /*        if (mysqli_query($con, $sql)) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . mysqli_error($con);
                }

                $_POST['userName'] = '';
                $_POST['password'] = '';
          */   
          $positionPage = 'interduction.php';
        
                $_POST['userName'] = '';
                $_POST['password'] = '';

                header("Location: $positionPage");
                exit;
            }
        }
        ?>

        <form action="signIn.php" method="post">
            <div class="container-fluid">     
                <div class="row">
                    <div class="col-sm-3 text-warning" style="background-color:black;">
                        <br />
                        <br />
                        <h2 style="color:yellow;"class="center">Sales Tool Box</h2>
                        <br />
                        <br />
                        <h2 style="color:yellow;" class="center">Sign In</h2>
                        <table>
                            <br />
                          
                            <br />
                            <br />
                            <tr><td style="color:yellow;">User Name:</td><td>
                                    <input type="text" name="userName" value="" />

                            <tr><td style="color:yellow;">Password:</td><td>
                                    <input type="password" name="password" value="" />

                        </table>

                        <br />

                        <input   type="submit" name="sign" value="Sign In"/>


                        <br />
                        <br />
                        <br />
                        <br />
                        <a style="color:yellow;" class="nav-link" " href="index.php"> Cancel</></a>
                        <a style="color:yellow;" class="nav-link"  href="#">First Time User</a>
                        <a style="color:yellow;" class="nav-link" href="#">Forgot Password</a>
                        <a style="color:yellow;" class="nav-link" href="#">Forgot User Name</a>
                        <a style="color:yellow;" class="nav-link" href="#">Privacy Policy</a>
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />

                    </div>
                    <div class="col-sm-9 ">
                        <img src="../images/Shelby.jpg"  width="1600" height="1000"> 
                    </div>
                </div>
            </div>

    </body>
    
</html>

