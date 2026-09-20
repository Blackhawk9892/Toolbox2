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
          
          require("includes/connection.php");
          require("includes/database_rows.php");
          require("toolbar_sales.php");
            echo " <center>  \n";
            echo "          <h1>Add Dealer Group </h1>\n";
            echo "</center>\n";
          

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

                <center> 
                    <table>
                     
                        
                        <tr><td>Year:</td><td>
                                <input type="number" name="year" size="4" value="<?php if (isset($_POST['year'])) echo $_POST['year'] ?>"	/>

                        <tr><td>Make:</td><td>
                                <input type="text" name="make" size="30" value="<?php if (isset($_POST['make'])) echo $_POST['make'] ?>"	/>

                        <tr><td>Model:</td><td>
                                <input type="text" name="model" size="30" value="<?php if (isset($_POST['model'])) echo $_POST['model'] ?>"	/>

                        <tr><td>Trim:</td><td>
                                <input type="text" name="trim" size="30" value="<?php if (isset($_POST['trim'])) echo $_POST['trim'] ?>"	/>

                         <tr><td>Exterior:</td><td>
                                <input type="text" name="exterior" size="30" value="<?php if (isset($_POST['exterior'])) echo $_POST['exterior'] ?>"	/>

                        <tr><td>Interior Type / Color:</td><td>
                                <input type="text" name="interior" size="30" value="<?php if (isset($_POST['interior'])) echo $_POST['interior'] ?>"	/>
                        
                        

                        <tr><td>Option 1:</td><td>
                                <input type="text" name="option1" size="20" value="<?php if (isset($_POST['option1'])) echo $_POST['option1'] ?>"	/>  

                        <tr><td>Option 2:</td><td>
                                <input type="text" name="option2" size="20" value="<?php if (isset($_POST['option2'])) echo $_POST['option2'] ?>"	/> 
                                
                        <tr><td>Option 3:</td><td>
                                <input type="text" name="option3" size="20" value="<?php if (isset($_POST['option3'])) echo $_POST['option3'] ?>"	/>  
                                
                        <tr><td>Option 4:</td><td>
                                <input type="text" name="option4" size="20" value="<?php if (isset($_POST['option4'])) echo $_POST['option4'] ?>"	/>  
                                   
                        <tr><td>Option 5:</td><td>
                                <input type="text" name="option5" size="20" value="<?php if (isset($_POST['option5'])) echo $_POST['option5'] ?>"	/> 

                        <tr><td>Option 6:</td><td>
                                <input type="text" name="option6" size="20" value="<?php if (isset($_POST['option6'])) echo $_POST['option6'] ?>"	/> 
                                
                        <tr><td>Option 7:</td><td>
                                <input type="text" name="option7" size="20" value="<?php if (isset($_POST['option1'])) echo $_POST['option1'] ?>"	/>  
                        
                        <tr><td>Option 8:</td><td>
                                <input type="text" name="option8" size="20" value="<?php if (isset($_POST['option8'])) echo $_POST['option8'] ?>"	/>  
                                
                        <tr><td>Option 9:</td><td>
                                <input type="text" name="option9" size="20" value="<?php if (isset($_POST['option9'])) echo $_POST['option9'] ?>"	/>  

                        <tr><td>Option 10:</td><td>
                                <input type="text" name="option10" size="20" value="<?php if (isset($_POST['option10'])) echo $_POST['option10'] ?>"	/> 
                        
                        <tr><td>Option 11:</td><td>
                                <input type="text" name="option11" size="20" value="<?php if (isset($_POST['option11'])) echo $_POST['option11'] ?>"	/> 
                                
                        <tr><td>Option 12:</td><td>
                                <input type="text" name="option12" size="20" value="<?php if (isset($_POST['option12'])) echo $_POST['option12'] ?>"	/>  
                                
                        <tr><td>Option 13:</td><td>
                                <input type="text" name="option13" size="20" value="<?php if (isset($_POST['option13'])) echo $_POST['option13'] ?>"	/>  

                        <tr><td>Option 14:</td><td>
                                <input type="text" name="option14" size="20" value="<?php if (isset($_POST['option14'])) echo $_POST['option14'] ?>"	/>  

                        <tr><td>Option 15:</td><td>
                                <input type="text" name="option15" size="20" value="<?php if (isset($_POST['option15'])) echo $_POST['option15'] ?>"	/>  

                        <tr><td>Option 16:</td><td>
                                <input type="text" name="option16" size="20" value="<?php if (isset($_POST['option16'])) echo $_POST['option16'] ?>"	/>  
                                
                                
                                
                                 
                                
                                                 
                                



                    </table>    
           
           
                <br />
                <br />			

               
                    <input type="submit" name="submit" value="Submit"/>
                    
                     </center> 
                

        </div>
    </body>
</html>
