<!DOCTYPE html>
<?php
// Start the session
session_start();
?>
<html>
    <head>
        <title>Load Test</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
    </head>
    <body>
        <div>
            <?php
        
        
          require("toolbar_sales.php");

        
if(isset($_POST['setType'])){
  $_SESSION['setType'] = $_POST['setType'];
   header("Location: interduction.php");
          exit;
}
                /////////////////////////////////////////////////////////////////////////////////

                $blank = '';
                if (isset($_POST['setType'])) {
                    $setType = $_POST['typeScrip'];
                    $setType_arr[] = "\n<option value=\"$typeScrip\">$typeScrip</option>\n";
                    $setType_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                } else {
                    $setType_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
                }
               
            
                $place = 'sales';
                $setType_arr[] = "\n<option value=\"$place\">$place</option>\n";
                
                $place = 'phone';
                $setType_arr[] = "\n<option value=\"$place\">$place</option>\n";
            
                $place = 'callback';
                $setType_arr[] = "\n<option value=\"$place\">$place</option>\n";
                
                ####################################################################################################






               
            
            ?>

            
            <form action="load_test.php" method="post">

                
                    <table>
                      <select>
     <br>
                              <tr><td>Type of Scrip:</td><td>  
                                <select name="setType">
                                <?php
                                print_r($setType_arr);
                                ?> 

                               <br> </select>
                                <br>
                                <br>

                    </table>    
           

                <br />
                <br />			

               
                    <input type="submit" name="submit" value="Submit"/>
                    
                    
                

        </div>
    </body>
</html>

