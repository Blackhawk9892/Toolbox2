<?php
session_start();
?>

<!DOCTYPE html>
<!--
Add Dealer to stock tag program
-->
<html>
    <head>
        <title>Vehicle Entry</title>
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

          if(isset($_COOKIE["userId"])){
           $userId = $_COOKIE["userId"];
 
           $emp_arry = Employee($userId);
           $first = $emp_arry[0];
           $last = $emp_arry[1];      
           $position = $emp_arry[2];
           $emp_id = $emp_arry[3];
           $dealer_id = $emp_arry[4];
           $name = $first . ' ' . $last;
        }

         $query = "SELECT * ";
                $query .= "FROM company ";
                $query .= "WHERE comp_id   = '{$dealer_id}' ";
                


                $result_set = mysqli_query($con, $query)
                 or die('Query failed: ' . mysqli_error($con));
                $row = mysqli_fetch_array($result_set);

                 $comp_group = $row['comp_group'];

          if(isset($_GET["stock"])){
             $_SESSION["stock"] = $_GET["stock"];
            }

          if(isset($_SESSION["stock"])){
            $stock = $_SESSION["stock"];
            }


            echo " <center>  \n";
            echo "          <h1>Vehicle Entry </h1>\n";
            echo "          <h1>Stock Number $stock</h1>\n";
            echo "</center>\n";
          

            if (isset($_POST['submit'])) {

                $year = $_POST['year'];
                $make = strtoupper($_POST['make']);
                $model = strtoupper($_POST['model']);
                $trim = strtoupper($_POST['trim']);

                if(empty($_POST['miles'])){
                     $miles = ' ';  
                }else{
                     $miles = strtoupper($_POST['miles']);
                }

                if(empty($_POST['engine'])){
                     $engine = ' ';  
                }else{
                     $engine = strtoupper($_POST['engine']);
                }

                  if(empty($_POST['drive'])){
                     $drive = ' ';  
                }else{
                     $drive = strtoupper($_POST['drive']);
                }

                if(empty($_POST['exterior'])){
                     $exterior = ' ';  
                }else{
                     $exterior = strtoupper($_POST['exterior']);
                }
                
                if(empty($_POST['interior'])){
                        $interior = ' ';
                }else{
                        $interior = strtoupper($_POST['interior']);  
                }
                
                if(empty($_POST['option1'])){
                       $option1 = ' ';
                }else{
                        $option1 = strtoupper($_POST['option1']);
                }

                if(empty($_POST['option2'])){
                       $option2 = ' ';
                }else{
                        $option2 = strtoupper($_POST['option2']);
                }

                if(empty($_POST['option3'])){
                       $option3 = ' ';
                }else{
                        $option3 = strtoupper($_POST['option3']);
                }

                if(empty($_POST['option4'])){
                       $option4 = ' ';
                }else{
                        $option4 = strtoupper($_POST['option4']);
                }

                if(empty($_POST['option5'])){
                       $option5 = ' ';
                }else{
                        $option5 = strtoupper($_POST['option5']);
                }

                if(empty($_POST['option6'])){
                       $option6 = ' ';
                }else{
                        $option6 = strtoupper($_POST['option6']);
                }

                if(empty($_POST['option7'])){
                       $option7 = ' ';
                }else{
                        $option7 = strtoupper($_POST['option7']);
                }

                if(empty($_POST['option8'])){
                       $option8 = ' ';
                }else{
                        $option8 = strtoupper($_POST['option8']);
                }

                if(empty($_POST['option9'])){
                       $option9 = ' ';
                }else{
                        $option9 = strtoupper($_POST['option9']);
                }

                if(empty($_POST['option10'])){
                       $option10 = ' ';
                }else{
                        $option10 = strtoupper($_POST['option10']);
                }

                if(empty($_POST['option11'])){
                       $option11 = ' ';
                }else{
                        $option11 = strtoupper($_POST['option11']);
                }

                if(empty($_POST['option12'])){
                       $option12 = ' ';
                }else{
                        $option12 = strtoupper($_POST['option12']);
                }

                if(empty($_POST['option13'])){
                       $option13 = ' ';
                }else{
                        $option13 = strtoupper($_POST['option13']);
                }

                if(empty($_POST['option14'])){
                       $option14 = ' ';
                }else{
                        $option14 = strtoupper($_POST['option14']);
                }

                if(empty($_POST['option15'])){
                       $option15 = ' ';
                }else{
                        $option15 = strtoupper($_POST['option12']);
                }

                if(empty($_POST['option16'])){
                       $option16 = ' ';
                }else{
                        $option16 = strtoupper($_POST['option16']);
                }
                
                 
       








                $required_fields = array('year', 'make', 'model', 'trim');

                foreach ($required_fields as $fieldname) {

                    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                        $errors[] = 'This field is empty: ' . $fieldname;
                    }
                }

                $query = "SELECT * ";
                $query .= "FROM specifications ";
                $query .= "WHERE spec_year   = '{$year}' ";
                $query .= "AND spec_make   = '{$make}' ";
                $query .= "AND spec_model   = '{$model}' ";
                $query .= "AND spec_trim   = '{$trim}' ";


                $result_set = mysqli_query($con, $query)
                 or die('Query failed: ' . mysqli_error($con));
                $row = mysqli_fetch_array($result_set);
                if (empty($row)) {
                       $errors[] = "Run research_vehicles then start over";
                       $url = "research.php?" .
                          "year="  . urlencode($year) .
                          "&make=" . urlencode($make) .
                          "&model=" . urlencode($model) .
                          "&trim=" . urlencode($trim);

                       $errors[] = '<a href="' . $url . '" target="_blank">Research Vehicle</a>';
                    
                }



  

                if (!empty($errors)) {

                    foreach ($errors as $value) {
                        echo "<div class=\"errors\">$value</div>";
                    }
                } else {
                   
                   


                    $sql = "INSERT INTO vehicle(vehicle_dealer_group, vehicl_company,
                     vehicle_stock_num, vehicle_year, vehicle_make,
                     vehicle_model, vehicle_trim, vehicle_miles, vehicle_engine, vehicle_drive, vehicle_interior, vehicle_color,
                     vehicle_option1, vehicle_option2, vehicle_option3, vehicle_option4,
                     vehicle_option5, vehicle_option6, vehicle_option7, vehicle_option8,
                     vehicle_option9, vehicle_option10, vehicle_option11, vehicle_option12,
                     vehicle_option13, vehicle_option14, vehicle_option15, vehicle_option16) 

              VALUES('$comp_group','$dealer_id','$stock','$year','$make','$model','$trim','$miles','$engine','$drive','$interior','$exterior',
              '$option1','$option2','$option3','$option4','$option5','$option6','$option7','$option8',
              '$option9','$option10','$option11','$option12','$option13','$option14','$option15','$option16')";


                    if (!mysqli_query($con, $sql)) {
                        die('Error input: ' . mysqli_error($con));
                    }

                   
                 header("Location: show_vehicle.php?stock=$stock");
                        exit;
                   
                }
            }
            
            ?>

            
            <form action="vehicle_entry.php" method="post">

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

                         <tr><td>Miles:</td><td>
                                <input type="number" name="miles" size="11" value="<?php if (isset($_POST['miles'])) echo $_POST['miles'] ?>"	/>

                         <tr><td>Engine:</td><td>
                                <input type="text" name="enginer" size="30" value="<?php if (isset($_POST['enginer'])) echo $_POST['enginer'] ?>"	/>

                        <tr><td>Drive Type:</td><td>
                                <input type="text" name="drive" size="30" value="<?php if (isset($_POST['drive'])) echo $_POST['drive'] ?>"	/>

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
                                <input type="text" name="option7" size="20" value="<?php if (isset($_POST['option7'])) echo $_POST['option7'] ?>"	/>  
                        
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
