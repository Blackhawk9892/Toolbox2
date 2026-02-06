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

          echo "<center><h1>Edit Employee</h1></center>";

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
                $emp_email = $emp_arry[7];
                       
                $_SESSION['emp'] = $emp_arry[0] . ' ' . $emp_arry[1];

                $_POST['first'] = $first;
                $_POST['last'] = $last;      
                $_POST['position'] = $position;
                $_POST['email'] = $emp_email;
                
            }
          
            if(isset($_POST['delete'])){
               $id = $_SESSION['id'];
            $emp = $_SESSION['emp'];

               mysqli_query($con, "DELETE FROM employee 
               WHERE emp_id = '$id' ");

             
               $_POST['first'] = '';
               $_POST['last'] = '';      
               $_POST['position'] = '';
               $_POST['email'] = '';


              
           $value = 'Employee ' . $emp . ' has been deleted form the system';
            echo "<div class=\"problem\">$value</div>";

            }

            if (isset($_POST['clear'])) {

                $_POST['dealer'] = '';
                $_POST['userName'] = '';
                $_POST['first'] = '';
                $_POST['last'] = '';
                $_POST['position'] = '';
                $_POST['email'] = '';
                $_POST['manager'] = '';
                $_POST['password'] = '';
                $_POST['password2'] = '';
            }

          


            if (isset($_POST['submit'])) {


                $first = ucwords($_POST['first']);
                $last = ucwords($_POST['last']);
                $position = $_POST['position'];
                $email = $_POST['email']; 

                if(isset($_POST['manager'])){

                  $manager = $_POST['manager'];
                  $d = explode("-",$manager);
                  $managerId = $d[0];
                  $managerName = $d[1];  
                }
                    

                $required_fields = array('first', 'last', 'position','email');

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
                     /////////////////////////////////////////////emp_email/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE employee SET emp_email = '$email'
                                         WHERE emp_id = '$id' ");
                    /////////////////////////////////////////////emp_position/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE employee SET emp_position = '$position'
                                         WHERE emp_id = '$id' ");
                    /////////////////////////////////////////////emp_assigned_man_num/////////////////////////////////////////////////////////////////


                    mysqli_query($con, "UPDATE employee SET emp_assigned_man_num = '$managerId'
                                         WHERE emp_id = '$id' ");
                    /////////////////////////////////////////////emp_position/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE employee SET emp_assigned_man_name = '$managerName'
                                         WHERE emp_id = '$id' ");
                    /////////////////////////////////////////////emp_email/////////////////////////////////////////////////////////////////






                    $_POST['first'] = '';
                    $_POST['last'] = '';
                    $_POST['position'] = '';
                    $_POST['manager'] = '';
                    $_POST['email'] = '';
                  
                   


                    echo "<div class=\"errors\">Employee $first $last  has been updated</div>";
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

              $place = 'Corporate';
            $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
           
            if ($emp_position = 'PFD') {
                $place = 'PFD';
                $position_arr[] = "\n<option value=\"$place\">$place</option>\n";
            }
 


        
             $blank = '';
            if (isset($_POST['manager'])) {
                $manager = $_POST['manager'];
                $manager_arr[] = "\n<option value=\"$manager\">$manager</option>\n";
            }
                $position_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
          
            if(isset($dealer_id)){ 
            $empType = "manager";
            $query = "SELECT * ";
            $query .= "FROM employee "; 
            $query .= "WHERE emp_dealer_id = '{$dealer_id}' ";
            $query .= "AND emp_position = '{$empType}' ";
            $query .= "ORDER BY emp_first_name, emp_last_name";

            $result_set = mysqli_query($con, $query)
                    or die('Query failed: ' . mysqli_error($con));

            while ($row = mysqli_fetch_array($result_set)) {
                $emp_id = $row['emp_id'];
                $emp_first_name = $row['emp_first_name'];
                $emp_last_name = $row['emp_last_name'];
                $manager = $emp_id . '-' . $emp_first_name . ' ' . $emp_last_name;
                $manager_arr[] = "\n<option value=\"$manager\">$manager</option>\n";
            }
        }
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

                          <tr><td>Enter your email:</td><td>
                        <input type="email" id="email" name="email" size="50" value="<?php if (isset($_POST['last'])) echo $_POST['email'] ?>"	/>       

                        <tr><td>Position:</td><td>

                                <select name="position">
                                    <?php
                                    print_r($position_arr);
                                    ?>
                                </select>

                                
                              <tr><td>Manager:</td><td>
                
                            <select name="manager">
                                <?php
                                print_r($manager_arr);
                                ?>
                             </select>



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
                                         <br />
                                        <br />
                                         <br />
                                        <br />
                                        <input type="submit" name="delete" value="Delete"/>

                                    </center> 

                                    </div>
                                    </body>
                                    </html>
