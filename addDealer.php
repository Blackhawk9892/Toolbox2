<!DOCTYPE html>
<!--
Add Dealer to stock tag program
-->

<html>
    <head>
        <title>Add Dealer</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
    </head>
    <body>
        <div>
            <?php
             require("includes/connection.php");
             require("includes/pull_downs.php");
             require("toolbar_sales.php");
   


            if (isset($_POST['clear'])) {

                $_POST['dGroup'] = '';
                $_POST['dealer'] = '';
                $_POST['address'] = '';
                $_POST['city'] = '';
                $_POST['state'] = '';
                $_POST['zip'] = '';
                $_POST['email'] = '';
                $_POST['phone'] = '';
                $_POST['billing'] = '';
                $_POST['send'] = '';
            }


           

            if (isset($_POST['submit'])) {

                if (isset($_POST['dGroup'])) {
                    $dg = $_POST['dGroup'];
                    $g = explode(" ", $dg);
                    $count = count($g);
                    $c = $count - 1;
                    $dealerGroup = $g[$c];
                } else {
                    $errors[] = 'A dealer group must be selected';
                }

                if (isset($_POST['dealer'])) {
                    $dealer = ucwords($_POST['dealer']);
                } else {
                    $errors[] = "Must select dealer.";
                }

                if (isset($_POST['address'])) {
                    $address = ucwords($_POST['address']);
                } else {
                    $errors[] = "Addres is empty.";
                }

                if (isset($_POST['city'])) {
                    $city = ucwords($_POST['city']);
                } else {
                    $errors[] = "City is empty.";
                }

                if (isset($_POST['state'])) {
                    $state = strtoupper($_POST['state']);
                } else {
                    $errors[] = "Statey is empty.";
                }

                if (isset($_POST['zip'])) {
                    $zip = $_POST['zip'];
                } else {
                    $errors[] = "Zip is empty.";
                }

                if (isset($_POST['email'])) {
                    $email = $_POST['email'];
                } else {
                    $errors[] = "Email is empty.";
                }

                
                if (isset($_POST['phone'])) {
                    $phone = $_POST['phone'];
                } else {
                    $errors[] = "Phone is empty.";
                }
              
                if (isset($_POST['billing'])) {
                    $billing = $_POST['billing'];
                } else {
                    $errors[] = "Must select use Email or Address for billing.";
                }


                if (isset($_POST['send'])) {
                    $send = $_POST['send'];
                } else {
                    $errors[] = "Must select Corporate or Company.";
                }
 
                    if (!empty($errors)) {

                        foreach ($errors as $value) {
                            echo "<div class=\"errors\">$value</div>";
                        }
                    } else {
                        $dealer = mysqli_real_escape_string($con, $dealer);
                        $address = mysqli_real_escape_string($con, $address);
                        $city = mysqli_real_escape_string($con, $city);
                        $state = mysqli_real_escape_string($con, $state);
                        $zip = mysqli_real_escape_string($con, $zip);                         
                        $email = mysqli_real_escape_string($con, $email);
                        $phone = mysqli_real_escape_string($con, $phone);
                        $billing = mysqli_real_escape_string($con, $billing);
                        $send = mysqli_real_escape_string($con, $send);

                        $sql = "INSERT INTO company(comp_group, comp_name, comp_address, comp_city, comp_state, comp_zip, comp_phone, comp_email, comp_billing_type, comp_send ) 
                        VALUES('$dealerGroup','$dealer','$address','$city','$state','$zip','$phone','$email','$billing','$send')";


                        if (!mysqli_query($con, $sql)) {
                            die('Error input: ' . mysqli_error($con));
                        }

                        $_POST['dGroup'] = '';
                        $_POST['dealer'] = '';
                        $_POST['address'] = '';
                        $_POST['city'] = '';
                        $_POST['state'] = '';
                        $_POST['zip'] = '';
                        $_POST['email'] = '';
                        $_POST['phone'] = '';
                        $_POST['billing'] = '';
                        $_POST['send'] = '';
                      

                        echo"<h1>Dealer $dealer has been added</h1>";
                    }
                }
                if (isset($_POST['dGroup'])) {
                    $group = $_POST['dGroup'];
                } else {
                    $group = 'none';
                }
                require("includes/database_rows.php");
                //require("database_rows.php");
                $dealer_group_arr[] = DealerGroup($group);
                ?>
            <center><h2>Add Dealer</h2></center>
               
                <br>
                <br>
                <form action="addDealer.php" method="post">

                    <center>
                        <table>
                            <?php
                            print( "<tr><td>Dealer Group:</td><td>\n");
                            print( "<select name=\"dGroup\">");

                           // print_r($dealer_group_arr);
                            ?>
                            <tr><td>Dealer Name:</td><td>
                                    <input type="text" name="dealer" size="50" value="<?php if (isset($_POST['dealer'])) echo $_POST['dealer'] ?>"	/>

                            <tr><td>Address:</td><td>
                                    <input type="text" name="address" size="75" value="<?php if (isset($_POST['address'])) echo $_POST['address'] ?>"	/>

                            <tr><td>City:</td><td>
                                    <input type="text" name="city" size="50" value="<?php if (isset($_POST['city'])) echo $_POST['city'] ?>"	/>

                            <tr><td>State:</td><td>
                                    <input type="text" name="state" size="2" value="<?php if (isset($_POST['state'])) echo $_POST['state'] ?>"	/>

                            <tr><td>Zip:</td><td>
                                    <input type="text" name="zip" size="20" value="<?php if (isset($_POST['zip'])) echo $_POST['zip'] ?>"	/>

                           
                            <tr><td> Email:</td><td>
                                    <input type="text" name="email" size="150" value="<?php if (isset($_POST['email'])) echo $_POST['email'] ?>" />
                            
                            <tr><td>Phone Number:</td><td>
                                    <input type="text" name="phone" size="15" value="<?php if (isset($_POST['phone'])) echo $_POST['phone'] ?>" />

                            <tr><td>Use Email or Address for Billing:</td><td>
                                    <input type="radio" id="email" name="billing" value="email">
                                    <label for="email">Email</label><br>
                                    <input type="radio" id="address" name="billing" value="address">
                                    <label for="address">Address</label><br>

                                    <br>

                            <tr><td>Send Bill to Corporate or Company:</td><td>
                                    <input type="radio" id="corporate" name="send" value="corporate">
                                    <label for="corporate">Corporate</label><br>
                                    <input type="radio" id="company" name="send" value="company">
                                    <label for="company">Company</label><br>

                      
                                <?php
                   

////////////////////////////////////////////////////////////////////////////////////////////         
                                ?>
                  

                    </table>    
                </center> 

                <br />
                <br />			

                <center>
                    <input type="submit" name="submit" value="Submit"/>
                   
                    
                </center> 

        </div>
    </body>
</html>
