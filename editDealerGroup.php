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
        require_once("includes/constants.php");
        require("includes/connection.php");
        require("includes/database_rows.php");
        require("toolbar_sales.php");

          echo "<center><h1>Edit Dealer Group</h1></center>";

           if (isset($_POST['back'])) {
            header("Location: select_dealer_group.php");
            exit;
        }

      ///////////////////////////////////////////////////////////////////////      

            if (isset($_GET['group'])) {
              $group = $_GET['group'];

               
        $query = "SELECT * ";
        $query .= "FROM dealer_group ";
        $query .= "WHERE dg_id = '{$group}' ";    
        $query .= "ORDER BY dg_name";


        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $_SESSION['dg_id'] = $row['dg_id']; 
            
            $dg_name = $row['dg_name'];
            $dg_address = $row['dg_address'];
            $dg_city = $row['dg_city'];
            $dg_zip = $row['dg_zip'];
            $dg_state = $row['dg_state'];
            $dg_premium_pkg = $row['dg_premium_pkg'];
          
            $_SESSION['dg_name'] = $dg_name; 
           
             $_POST['dealer_group'] = $dg_name;
             $_POST['address'] = $dg_address;
             $_POST['city'] = $dg_city;
             $_POST['state'] = $dg_zip;
             $_POST['zip'] = $dg_state;
             $_POST['premium'] = $dg_premium_pkg;
           

            }
       } 

       if (isset($_SESSION['dg_id'])) {     
        $dg_id = $_SESSION['dg_id'];
       }

            if(isset($_POST['delete'])){
               $id = $_SESSION['dg_id'];
            

              mysqli_query($con, "UPDATE dealer_group SET dg_delete = 'Yes'
                                  WHERE dg_id = '$id' ");

                    $dg_name = $_POST['dg_name'];
                    $_POST['dealer_group'] = '';
                     $_POST['dg_name'] = '';
                    $_POST['address'] = '';
                    $_POST['city'] = '';
                    $_POST['state'] = '';
                    $_POST['zip'] = '';
                    $_POST['premium'] = '';
             


              
           $value = 'Dealer Group ' . $dg_name . ' has been deleted form the system';
            echo "<div class=\"problem\">$value</div>";

            }

            if (isset($_POST['clear'])) {

                    $_POST['dealer_group'] = '';
                 
                    $_POST['address'] = '';
                    $_POST['city'] = '';
                    $_POST['state'] = '';
                    $_POST['zip'] = '';
                    $_POST['premium'] = '';
            }

          


            if (isset($_POST['submit'])) {
  
                    $dealer_group = ucwords($_POST['dealer_group']) ;
                    
                    $address = ucwords($_POST['address']);
                    $city = ucwords($_POST['city']);
                    $state = ucwords($_POST['state']);
                    $zip = ucwords($_POST['zip']);
                    $premium = ucwords($_POST['premium']);
               

                    

                $required_fields = array('dealer_group', 'city', 'state','zip','premium');

                foreach ($required_fields as $fieldname) {

                    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                        $errors[] = 'This field may not be empty: ' . $fieldname;
                    }
                }

        if(isset($_SESSION['dg_id'])){
            $id = $_SESSION['dg_id'];
        }else{
            $errors[] = 'Dealer Group id was not found';
        }



                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                  
                   
                    

                    /////////////////////////////////////////////Dealer Group/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE dealer_group SET dg_name = '$dealer_group'
                                         WHERE dg_id = '$id' ");
                 
                    /////////////////////////////////////////////Address/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE dealer_group SET dg_address = '$address'
                                         WHERE dg_id = '$id' ");
                   
                    /////////////////////////////////////////////City/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE dealer_group SET dg_city = '$city'
                                         WHERE dg_id = '$id' ");
                    /////////////////////////////////////////////Zip/////////////////////////////////////////////////////////////////


                    mysqli_query($con, "UPDATE dealer_group SET dg_zip = '$zip'
                                         WHERE dg_id = '$id' ");
                    /////////////////////////////////////////////State/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE dealer_group SET dg_state = '$state'
                                         WHERE dg_id = '$id' ");
                                         
                   //////////////////////////////////////Premium Package/////////////////////////////////////////////////////////////////

                    mysqli_query($con, "UPDATE dealer_group SET dg_premium_pkg = '$premium '
                                         WHERE dg_id = '$id' ");
 
                  
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////






                  $_POST['dealer_group'] = '';
               
                    $_POST['address'] = '';
                    $_POST['city'] = '';
                    $_POST['state'] = '';
                    $_POST['zip'] = '';
                    $_POST['premium'] = '';
                   

                  
                   


                    echo "<div class=\"errors\">Dealer Group $dealer_group has been updated</div>";
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
 


        
          
            ?>


            <br>
            <br>
            <form action="editDealerGroup.php" method="post">

                <center>
                     <table>
                        <h2>Premium Package</h2>

  <input type="radio" id="Yes" name="premium" value="y">
  <label for="Yes">Yes</label><br>
  <input type="radio" id="No" name="premium" value="n">
  <label for="No">No</label><br>
                        
                        <tr><td>Dealer Group Name:</td><td>
                                <input type="text" name="dealer_group" size="50" value="<?php if (isset($_POST['dealer_group'])) echo $_POST['dealer_group'] ?>"	/>

                        <tr><td>Address:</td><td>
                                <input type="text" name="address" size="75" value="<?php if (isset($_POST['address'])) echo $_POST['address'] ?>"	/>

                        <tr><td>City:</td><td>
                                <input type="text" name="city" size="50" value="<?php if (isset($_POST['city'])) echo $_POST['city'] ?>"	/>

                        <tr><td>State:</td><td>
                                <input type="text" name="state" size="2" value="<?php if (isset($_POST['state'])) echo $_POST['state'] ?>"	/>

                        <tr><td>Zip:</td><td>
                                <input type="text" name="zip" size="20" value="<?php if (isset($_POST['zip'])) echo $_POST['zip'] ?>"	/>

                                



                

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
