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
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div>
            <?php
            require("includes/connection.php");
           
            require("toolbar_sales.php");
            require("includes/database_rows.php");
           // require("../includes/pull_downs.php");
           // require("../includes/security.php");

           if (isset($_POST['back'])) {
            header("Location: select_employee.php");
            exit;
        }

      ///////////////////////////////////////////////////////////////////////      

            if (isset($_GET['employee'])) {
                //$employee = Unencrypt_Get($_GET['employee']);
                $employee = $_GET['employee'];
                 $_SESSION['id'] = $employee;
                $emp_arry = Employee($employee);
                $first = $emp_arry[0];
                $last = $emp_arry[1];      
                $position = $emp_arry[2];
                $emp_id = $emp_arry[3];
                $dealer_id = $emp_arry[4];
                       
                $_SESSION['emp'] = $emp_arry[0] . ' ' . $emp_arry[1];

                $_POST['first'] = $first;
                $_POST['last'] = $last;      
                $_POST['position'] = $position;
                
            }
          
            if(isset($_POST['delete'])){
               $id = $_SESSION['id'];
            $emp = $_SESSION['emp'];

               mysqli_query($con, "DELETE FROM employee 
               WHERE emp_id = '$id' ");

             
               $_POST['first'] = '';
               $_POST['last'] = '';      
               $_POST['position'] = '';


              
           $value = 'Employee ' . $emp . ' has been deleted form the system';
            echo "<div class=\"problem\">$value</div>";

            }

            if (isset($_POST['clear'])) {

                $_POST['dealer'] = '';
                $_POST['userName'] = '';
                $_POST['first'] = '';
                $_POST['last'] = '';
                $_POST['position'] = '';
              
                $_POST['password'] = '';
                $_POST['password2'] = '';
            }

          


            if (isset($_POST['submit'])) {


                $first = ucwords($_POST['first']);
                $last = ucwords($_POST['last']);
                $position = $_POST['position'];
               
            

                $required_fields = array('first', 'last', 'position');

                foreach ($required_fields as $fieldname) {

                    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                        $errors[] = 'This field may not be empty: ' . $fieldname;
                    }
                }

              
        if(isset($_SESSION['id'])){
            $id = $_SESSION['id'];
        }else{
            $errors[] = 'Employee id was not found';
        }



                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                  
                   
                    

                    /////////////////////////////////////////////emp_first_name/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE employee SET emp_first_name = '$first'
                                         WHERE emp_id = '$id' ");
                 
                    /////////////////////////////////////////////emp_last_name/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE employee SET emp_last_name = '$last'
                                         WHERE emp_id = '$id' ");
                    /////////////////////////////////////////////emp_position/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE employee SET emp_position = '$position'
                                         WHERE emp_id = '$id' ");
                    /////////////////////////////////////////////emp_email/////////////////////////////////////////////////////////////////






                    $_POST['first'] = '';
                    $_POST['last'] = '';
                    $_POST['position'] = '';
                   


                    echo "<div class=\"errors\">Employee $first $last  has been added</div>";
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
           
            if ($emp_position = 'pfd') {
                $place = 'Sales Tool Box';
                $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
            }
          //  $place = 'Corporate';
          //  $position_arr[] = "\n<option value=\"$place\">$place</option>\n";

        

            ?>


            <br>
            <br>
            <form action="editEmployee.php" method="post">

                <center>
                    <table>


                        <tr><td>First Name:</td><td>
                                <input type="text" name="first" size="50" value="<?php if (isset($_POST['first'])) echo $_POST['first'] ?>"	/>

                        <tr><td>Last Name:</td><td>
                                <input type="text" name="last" size="50" value="<?php if (isset($_POST['last'])) echo $_POST['last'] ?>"	/>

                        <tr><td>Position:</td><td>

                                <select name="position">
                                    <?php
                                    print_r($position_arr);
                                    ?>



                                            </table>    
                                            </center> 

                                            <br />
                                            <br />			

                                    <center>
                                       
                                        <input type="submit" name="submit" value="Up Date"/>
                                        <br />
                                        <br />
                                        <input type="submit" name="back" value="Back"/>
                                        <br />
                                        <br />
                                        <input type="submit" name="delete" value="Delete"/>

                                    </center> 

                                    </div>
                                    </body>
                                    </html>
