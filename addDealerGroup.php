<!DOCTYPE html>
<!--
Add Dealer to stock tag program
-->
<html>
    <head>
        <title>Add Dealer Group</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
    </head>
    <body>
        <div>
            <?php
          
            require("../includes/connection.php");
             require("../toolbar/toolbar_dashboard.php");

            if (isset($_POST['submit'])) {

                $dealer_group = ucwords($_POST['dealer_group']);
                $address = ucwords($_POST['address']);
                $city = ucwords($_POST['city']);
                $state = strtoupper($_POST['state']);
                $zip = $_POST['zip'];








                $required_fields = array('dealer_group', 'address', 'city', 'zip');

                foreach ($required_fields as $fieldname) {

                    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                        $errors[] = 'This field is empty: ' . $fieldname;
                    }
                }


                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                    $dealer_group = mysqli_real_escape_string($con, $dealer_group);
                    $address = mysqli_real_escape_string($con, $address);
                    $city = mysqli_real_escape_string($con, $city);
                    $state = mysqli_real_escape_string($con, $state);
                    $zip = mysqli_real_escape_string($con, $zip);


                    $sql = "INSERT INTO dealer_group(dg_name, dg_address, dg_city, dg_state, dg_zip) 
              VALUES('$dealer_group','$address','$city','$state','$zip')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error input: ' . mysqli_error($con));
                    }

                    $_POST['dealer_group'] = '';
                    $_POST['address'] = '';
                    $_POST['city'] = '';
                    $_POST['state'] = '';
                    $_POST['zip'] = '';


                    echo"<h1>Dealer group $dealer_group has been added</h1>";
                }
            }
            
            ?>

            
            <form action="addDealerGroup.php" method="post">

                
                    <table>
                        
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
           

                <br />
                <br />			

               
                    <input type="submit" name="submit" value="Submit"/>
                    
                    
                

        </div>
    </body>
</html>
