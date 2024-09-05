<?php
// Start the session
session_start();
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <title>Vehicle Test</title>
        <style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto auto auto;
  background-color: #2196F3;
  padding: 10px;
}
.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 1px;
  font-size: 18px;
  text-align: left;
}
</style>
    </head>


    <body>

    

    <?php
    require_once("includes/connection.php");
    require("includes/database_rows.php");
    require("includes/pull_downs.php");
    require_once("toolbar_sales.php");
    require("includes/security.php");
    require("includes/datafile.php");

    if(isset($_SESSION['message'])){
        $value = $_SESSION['message'];
        echo "<div class=\"errors\">$value</div>";
        unset($_SESSION['message']);
    }
    $colors_array = array("Blue","Black","Brown","Gray","Green","Indigo","Orenge","Pink","Purple","Red","White","Yellow");
    ////////////////////////////Employee Info////////////////////////////////////
//                     
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

    //////////////////////Dealer Info/////////////////////////////////////////
    $query = "SELECT * ";
    $query .= "FROM company ";
    $query .= "WHERE comp_id    = '{$dealer_id}' ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed2: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);

    $dealer_name = $row['comp_name'];
    $dealer_address = $row['comp_address'];
    $dealer_city = $row['comp_city'];
    $dealer_state = $row['comp_state'];
    $dealer_zip = $row['comp_zip'];
    //$dealer_phone = $row['dealer_phone'];
    $dealer_group = $row['comp_group'];

       
    
    if(isset($_GET['find'])){
        $_SESSION['find'] = $_GET['find'];
      
      }
      $find = $_SESSION['find'];
      
      if(isset($_POST['info'])){
        $veh_array = array();

         if(empty($_POST['price'])){
            $veh_array[] = 'None';
         }else{
            $veh_array[] = $_POST['price'];
         }

         if(empty($_POST['payment'])){
            $veh_array[] = 'None';
         }else{
            $veh_array[] = $_POST['payment'];
         }

         if(empty($_POST['miles'])){
            $veh_array[] = 'None';
         }else{
            $veh_array[] = $_POST['miles'];
         }

        
            $colorP = '';
            foreach ($colors_array as $x) {
            
              if(isset($_POST[$x])){
               $newColor = $_POST[$x];
               $colorP .= $newColor . ',';
              }
             
             }
             if(empty($colorP)){
               $veh_array[] = 'None';
            }else{
              $veh_array[] = $colorP;
            }

            $colorA = '';
            foreach ($colors_array as $x) {
               $x = $x . 'A';
              if(isset($_POST[$x])){
               $newColor = $_POST[$x];
               $colorA .= $newColor . ',';
              }
              
             }
             if(empty($colorA)){
               $veh_array[] = 'None';
            }else{
              $veh_array[] = $colorA;
            }
         
$equip = '';
        $query = "SELECT * ";
        $query .= "FROM code_equip ";
        $query .= "WHERE equip_group_num  = '{$dealer_group}' ";
        $query .= "ORDER BY equip_list  ASC ";
    
        $result_set = mysqli_query($con, $query)
                or die('Query failed98: ' . mysqli_error($con));
    
        while ($row = mysqli_fetch_array($result_set)) {
            $equip_index = $row['equip_index'];
         
            if(isset($_POST[$equip_index])){
            
                $equip .= $_POST[$equip_index] ;
                $equip .= ", ";
            }
        }
       
        if(empty($equip)){
            $veh_array[] = 'none';
         }else{
            $veh_array[] = $equip;
         }
         echo " Color: " . $colorA;
         echo " Equip: " . $equip;

        
         $_SESSION['vehicle'] = $veh_array;
         $value = "Information has been sent to Upload Reply Recording page. Close this page and reload  Upload Reply Recording page.";
         echo "<div class=\"errors\">$value</div>";

      }
      if($find != "setup"){
            $query = "SELECT * ";
            $query .= "FROM customer_data ";
            $query .= "WHERE cust_find  = '{$find}' ";
      
            $result_set = mysqli_query($con, $query)
                    or die('Query failed emp: ' . mysqli_error($con));
            $row = mysqli_fetch_array($result_set);
      
        $cust_male_name = $row['cust_male_name'];
        $cust_male_voice = $row['cust_male_voice'];
        $cust_male_photo = $row['cust_male_photo'];
      
         $cust_female_name = $row['cust_female_name'];
        $cust_female_voice = $row['cust_female_voice'];
        $cust_female_photo = $row['cust_female_photo'];
      
        $cust_points = $row['cust_points'];
         $cust_id = $row['cust_id'];
         $cust_find = $row['cust_find'];
         $cust_primary_user = $row['cust_primary_user'];
         $cust_primary = $row['cust_primary'];
         $cust_secondary = $row['cust_secondary'];

        $_POST['make'] = 'USED';
        $_POST['model'] = 'USED';
    
    }

    


//////////////////////////////////////////Submit/////////////////////////////////////////
if(isset($_POST['submit'])){

 
   if($cust_primary == 0){
      $errors[] = "The primary customer information is incomplete";
   }

   if($cust_secondary == 0){
      $errors[] = "The secondary customer information is incomplete";
   }
   if(isset($_POST['typeVehicle'])){
    $typeVehicle = $_POST['typeVehicle'];
  }else{
    $errors = 'A Vehicle Type must be selected';
  }

   
   if (!empty($errors)) {

      foreach ($errors as $value) {
          echo "<div class=\"errors\">$value</div>";
      }
  } else {

    if(isset($_POST['price'])){
      $price = $_POST['price'];
    }else{
      $price = '';
    }

    if(isset($_POST['payment'])){
      $payment = $_POST['payment'];
    }else{
      $payment = '';
    }

    if(isset($_POST['miles'])){
      $miles = $_POST['miles'];
    }else{
      $miles = '';
    }
   
   $query = "SELECT * ";
   $query .= "FROM audio ";
   $query .= "WHERE audio_id = '{$cust_primary}' ";
  

   $result_set = mysqli_query($con, $query)
           or die('Query failed408: ' . mysqli_error($con));

   $row = mysqli_fetch_array($result_set);
       $audio_reply = $row['audio_reply'];
       $optionsPrim = explode(",",$audio_reply);
       $audio_options1 = $row['audio_options'];

       $audio_price = $row['audio_price'];
       $audio_payment = $row['audio_payment'];
       $audio_miles = $row['audio_miles'];

       $audio_color_liked = $row['audio_color_liked'];
       $colorPrefer = explode(",",$audio_color_liked);

       $audio_color_dislike = $row['audio_color_dislike'];
       $colorAvoid = explode(",",$audio_reply);

       $audio_vehicle_type = $row['audio_vehicle_type'];


/////////////////////////////////////////////////////////////////////////////////////////


       $query = "SELECT * ";
       $query .= "FROM audio ";
       $query .= "WHERE audio_id = '{$cust_secondary}' ";
      
    
       $result_set = mysqli_query($con, $query)
               or die('Query failed408: ' . mysqli_error($con));
    
       $row = mysqli_fetch_array($result_set);
           $audio_reply2 = $row['audio_reply'];
           $audio_options2 = $row['audio_options'];
           $optionsSecond = explode(",",$audio_reply);
           foreach ($optionsSecond as $x) {
            if($x == "None"){
               continue;
            }else{
               $optionsPrim[] = $x;
            }
          }

          $colorP = '';
          foreach ($colors_array as $x) {
          
            if(isset($_POST[$x])){
             $newColor = $_POST[$x];
             $colorP .= $newColor . ',';
            }
           
           }
           if(empty($colorP)){
             $veh_array[] = 'None';
          }else{
            $veh_array[] = $colorP;
          }
     
          $colorA = '';
          foreach ($colors_array as $x) {
             $x = $x . 'A';
            if(isset($_POST[$x])){
             $newColor = $_POST[$x];
             $colorA .= $newColor . ',';
            }
            
           }
           if(empty($colorA)){
             $veh_array[] = 'None';
          }else{
            $veh_array[] = $colorA;
          }
       

          $equip = '';
          $query = "SELECT * ";
          $query .= "FROM code_equip ";
          $query .= "WHERE equip_group_num  = '{$dealer_group}' ";
          $query .= "ORDER BY equip_list  ASC ";
      
          $result_set = mysqli_query($con, $query)
                  or die('Query failed98: ' . mysqli_error($con));
      
          while ($row = mysqli_fetch_array($result_set)) {
              $x = $row['equip_index'];

              

              $test = strchr($audio_color_liked,$x);

              if(!empty($test)){
               $match = strchr($colorP,$x);
               if(empty($match)){
                 $count2--;
               }else{
                 $count2++;
               }
                 }
 
                 $test1 = strchr($colorP,$x);
 
                 if(!empty($test1)){
               
                  $match1 = strchr($audio_color_liked,$x);
                  if(empty($match1)){
                    $count3--;
                  }
                    }
              
          }
         echo "Equipment: " . $equip . "<br>";
          if(empty($equip)){
              $veh_array[] = 'none';
           }else{
              $veh_array[] = $equip;
           }
         print_r($veh_array);
          
          if($audio_options1 == 'NONE,'){
            $options = '';
          }else{
            $options = $audio_options1;
          }

          if($audio_options1 == 'NONE,'){
           
          }else{
            $options .= $audio_options1;
          }


          echo 'Vehilce Type: ' . $typeVehicle . ' ### ' . $audio_vehicle_type . '<br>';
          echo 'Price: ' . $price . ' ### ' . $audio_price . '<br>';
          echo 'Payment: ' . $payment . ' ### ' . $audio_payment . '<br>';
          echo 'Miles: ' . $miles . ' ### ' . $audio_miles . '<br>';
          echo 'Prefer Colors: ' . $colorP . ' ### ' . $audio_color_liked . '<br>';
          echo 'Avoid Colors: ' . $colorA . ' ### ' . $audio_color_dislike . '<br>';
          echo 'Equipment: ' . $equip . ' ### ' .  $options . '<br>';

          $count = 0;

          if($typeVehicle == $audio_vehicle_type){
            $count++;
          }else{
            $count--;
          }

          if($price == $audio_price){
            $count++;
          }else{
            $count--;
          }

          if($payment == $audio_payment){
            $count++;
          }else{
            $count--;
          }

          if($miles == $audio_miles ){
            $count++;
          }else{
            $count--;
          }

  $count3 = 0;      
  $count2 = 0;
          foreach ($colors_array as $x) {
            $test = strchr($audio_color_liked,$x);

             if(!empty($test)){
              $match = strchr($colorP,$x);
              if(empty($match)){
                $count2--;
              }else{
                $count2++;
              }
                }

                $test1 = strchr($colorP,$x);

                if(!empty($test1)){
              
                 $match1 = strchr($audio_color_liked,$x);
                 if(empty($match1)){
                   $count3--;
                 }
                   }
         
          }
          echo "Count: " . $count2 . "<br>";
          echo "Count: " . $count3 . "<br>";
          $preferCount = $count2 + $count3;
          echo "Prefer Count: " . $preferCount;
          echo "<br> <br>";

          $count3 = 0;      
          $count2 = 0;
                  foreach ($colors_array as $x) {
                    $test = strchr($audio_color_dislike,$x);
        
                     if(!empty($test)){
                      
                      $match = strchr($colorP,$x);
                      if(empty($match)){
                        $count2--;
                      }else{
                        $count2++;
                      }
                        }
        
                        $test1 = strchr($colorP,$x);
        
                        if(!empty($test1)){
                         
                         
                         $match1 = strchr($audio_color_liked,$x);
                         if(empty($match1)){
                           $count3--;
                         }
                           }
                 
                  }
                  echo "Count: " . $count2 . "<br>";
                  echo "Count: " . $count3 . "<br>";
                  $avoidCount = $count2 + $count3;
                  echo "Avoid Count: " . $avoidCount;
                  echo "<br> <br>";


    }
}


  /////////////////////////////////////////////////////////////////////////////////

  $blank = '';
  if (isset($_POST['typeVehicle'])) {
      $typeVehicle = $_POST['typeVehicle'];
      $typeVehicle_arr[] = "\n<option value=\"$typeVehicle\">$typeVehicle</option>\n";
  } else {
      $typeVehicle_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
  }
  $place = 'Wagon';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
  
  $place = 'Convertible';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

  $place = 'Small SUV';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
  
  $place = 'Midsize SUV';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";


  $place = 'Large SUV';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
  
  $place = 'Coupe';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

  $place = 'Sedan';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
  
  $place = 'Hatchback';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

  $place = 'Regular cab Truck';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

  $place = 'Extended Cab Truck';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";
  
  $place = 'Crew Cab';
  $typeVehicle_arr[] = "\n<option value=\"$place\">$place</option>\n";

//////////////////////////////////////////////////////////////////////////////////////////

if($find == "setup"){
    echo "<center><h1>Setup For Reply Recording</h1></center>";
 }else{
    echo "<center><h1>Vehicle Test </h1></center>";
 }


    ?>



    <br />
    <br />
    <form action="test1.php" method="post">




        <div style="padding-left: 37px;">
         

            <table>	
            <label for="Vehicle Type">Vehicle Type:</label>   
        <select name="typeVehicle">
                                <?php
                                print_r($typeVehicle_arr);
                                ?>
                    </select>
                              
            

            <tr><td>Price:</td><td>
                        <input type="number" name="price"  value="<?php if (isset($_POST['price'])) echo $_POST['price'] ?>"	/>


            <tr><td>Payment:</td><td>
                        <input type="number" name="payment" size="10" value="<?php if (isset($_POST['payment'])) echo $_POST['payment'] ?>"	/>


            <tr><td>Miles:</td><td>
                        <input type="number" name="miles" value="<?php if (isset($_POST['miles'])) echo $_POST['miles'] ?>"	/>

          

        
</td>

           

                         
                        <br>
            </table>
            
            <h3>Selcect Preferred Colors:</h3>

            <input type="checkbox" id="Blue" name="Blue" value="Blue">
            <label for="Blue">Blue</label>

            <input type="checkbox" id="Black" name="Black" value="Black">
            <label for="Black">Black</label>

            <input type="checkbox" id="Brown" name="Brown" value="Brown">
            <label for="Brown">Brown</label>

            <input type="checkbox" id="Gray" name="Gray" value="Gray">
            <label for="Gray">Gray</label>

            <input type="checkbox" id="Green" name="Green" value="Green">
            <label for="Green">Green</label>

            <input type="checkbox" id="Indigo" name="Indigo" value="Indigo">
            <label for="Indigo">Indigo</label>

            <input type="checkbox" id="Orenge" name="Orenge" value="Orenge">
            <label for="Orenge">Orenge</label>

            <input type="checkbox" id="Pink" name="Pink" value="Pink">
            <label for="Pink">Pink</label>

            <input type="checkbox" id="Purple" name="Purple" value="Purple">
            <label for="Purple">Purple</label>

            <input type="checkbox" id="Red" name="Red" value="Red">
            <label for="Red">Red</label>

            <input type="checkbox" id="White" name="White" value="White">
            <label for="White">White</label>

            <input type="checkbox" id="Yellow" name="Yellow" value="Yellow">
            <label for="Yellow">Yellow</label>



<h3>Selcect Avoid Colors:</h3>

<input type="checkbox" id="BlueA" name="BlueA" value="Blue">
<label for="BlueA">Blue</label>

<input type="checkbox" id="BlackA" name="BlackA" value="Black">
<label for="BlackA">Black</label>

<input type="checkbox" id="BrownA" name="BrownA" value="Brown">
<label for="BrownA">Brown</label>

<input type="checkbox" id="GrayA" name="GrayA" value="Gray">
<label for="GrayA">Gray</label>

<input type="checkbox" id="GreenA" name="GreenA" value="Green">
<label for="GreenA">Green</label>

<input type="checkbox" id="IndigoA" name="IndigoA" value="Indigo">
<label for="IndigoA">Indigo</label>

<input type="checkbox" id="OrengeA" name="OrengeA" value="Orenge">
<label for="OrengeA">Orenge</label>

<input type="checkbox" id="PinkA" name="PinkA" value="Pink">
<label for="PinkA">Pink</label>

<input type="checkbox" id="PurpleA" name="PurpleA" value="Purple">
<label for="PurpleA">Purple</label>

<input type="checkbox" id="RedA" name="RedA" value="Red">
<label for="RedA">Red</label>

<input type="checkbox" id="WhiteA" name="WhiteA" value="White">
<label for="WhiteA">White</label>

<input type="checkbox" id="YellowA" name="YellowA" value="Yellow">
<label for="YellowA">Yellow</label>


          


           

           
            </br>
            <?php
             if($find == "setup"){
                echo "<input type=\"submit\" name=\"info\" value=\"Send Info Back\"/>";
             }else{
                echo "<input type=\"submit\" name=\"submit\" value=\"Submit Answer\"/>";         
             }
            ?>
          
          
        
            </br>
            </br>
        </div> 

<?php
/*
$result = count($equip_arr);
$count = 0;
while ($count <= $result) {
    $load = @$equip_arr[$count];
    echo $load;

    $count++;
}*/
?>			

    </div>

    <div class="grid-container">
  <div class="grid-item"> COMFORT</div>
  <div class="grid-item">PERFORMANCE</div>
  <div class="grid-item">SAFETY</div>  
  <div class="grid-item"> STYLE</div>
  
  <div class="grid-item">
  <input type="checkbox" id="1033" name="1033" value="A/C DUAL-ZONE">
  <label for="1033"> A/C DUAL-ZONE</label><br>

  <input type="checkbox" id="1020" name="1020" value="ADJUSTABLE PEDALS">
  <label for="1020"> ADJUSTABLE PEDALS</label><br>

  <input type="checkbox" id="1026" name="1026" value="AM/FM RADIO: SIRIUSXM">
  <label for="1026">AM/FM RADIO: SIRIUSXM</label><br>


  <input type="checkbox" id="1018" name="1018" value="APPLE CARPLAY/ANDROID AUTO">
  <label for="1018">APPLE CARPLAY/ANDROID AUTO</label><br>

  <input type="checkbox" id="1034" name="1034" value="AUTOMATIC TEMPERATURE CONTROL">
  <label for="1034">AUTOMATIC TEMPERATURE CONTROL</label><br>

   <input type="checkbox" id="1063" name="1063" value="FOLDING SEATS">
  <label for="1063">FOLDING SEATS</label><br>

  <input type="checkbox" id="1031" name="1031" value="GARAGE DOOR TRANSMITTER">
  <label for="1031">GARAGE DOOR TRANSMITTER</label><br>

  <input type="checkbox" id="1064" name="1064" value="HANDS-FREE STEERING">
  <label for="1064">HANDS-FREE STEERING</label><br>

  <input type="checkbox" id="1010" name="1010" value="HEATED AND COOLED SEATS">
  <label for="1010">HEATED AND COOLED SEATS</label><br>

  <input type="checkbox" id="1009" name="1009" value="HEATED SEATS">
  <label for="1009">HEATED SEATS</label><br>

  <input type="checkbox" id="1014" name="1014" value="HEATED STEERING WHEEL">
  <label for="1014">HEATED STEERING WHEEL</label><br>

   <input type="checkbox" id="1030" name="1030" value="KEYLESS ENTRY">
  <label for="1030">KEYLESS ENTRY</label><br>

  <input type="checkbox" id="1007" name="1007" value="LEATHER SEATS">
  <label for="1007">LEATHER SEATS</label><br>

  <input type="checkbox" id="1021" name="1021" value="MEMORY SEAT">
  <label for="1021">MEMORY SEAT</label><br>

  <input type="checkbox" id="1065" name="1065" value="MOONROOF">
  <label for="1065">MOONROOF</label><br>

  <input type="checkbox" id="1012" name="1012" value="NAVIGATION SYSTEM">
  <label for="1012">NAVIGATION SYSTEM</label><br>

  <input type="checkbox" id="1036" name="1036" value="POWER DRIVER SEAT">
  <label for="1036">POWER DRIVER SEAT</label><br>

  <input type="checkbox" id="1035" name="1035" value="POWER PASSENGER SEAT">
  <label for="1035">POWER PASSENGER SEAT</label><br>

  <input type="checkbox" id="1062" name="1062" value="POWER TAILGATE">
  <label for=1062">POWER TAILGATE</label><br>

   <input type="checkbox" id="1038" name="1038" value="POWER WINDOW MIRRORS LOCKS">
  <label for="1038">POWER WINDOW MIRRORS LOCKS</label><br>

  <input type="checkbox" id="1050" name="1050" value="REAR CAPTAINS CHAIRS">
  <label for="1050">REAR CAPTAINS CHAIRS</label><br>

  <input type="checkbox" id="1051" name="1051" value="REAR DVD/TV">
  <label for="1051">REAR DVD/TV</label><br>

  <input type="checkbox" id="1015" name="1015" value="REMOTE START">
  <label for="1015">REMOTE START</label><br>

  <input type="checkbox" id=1041" name="1041" value="TAILGATE STEP">
  <label for="1041">TAILGATE STEP</label><br>

  <input type="checkbox" id="1017" name="1017" value="THIRD-ROW SEATING">
  <label for="1017">THIRD-ROW SEATING</label><br>

  <input type="checkbox" id="1039" name="1039" value="TWIN PANEL MOONROOF">
  <label for="1039">TWIN PANEL MOONROOF</label><br>


  </div>
  
  <div class="grid-item">


 <input type="checkbox" id="1068" name="1068" value="GAS ENGINE">
  <label for="1068">GAS ENGINE</label><br>

  <input type="checkbox" id="1056" name="1056" value="DIESEL">
  <label for="1056">DIESEL</label><br>

  <input type="checkbox" id="1058" name="1058" value="ELECTRIC VEHICLE">
  <label for="1058">ELECTRIC VEHICLE</label><br>

  <input type="checkbox" id="1057" name="1057" value="HYBRID">
  <label for="1057">HYBRID</label><br>
  <input type="checkbox" id="1066" name="1066" value="2 WHEEL DRIVE">
  <label for="1066">2 WHEEL DRIVE</label><br>

  <input type="checkbox" id="1046" name="1046" value="4 WHEEL DRIVE">
  <label for="1046">4 WHEEL DRIVE</label><br>

  <input type="checkbox" id="ALL WHEEL DRIVE" name="ALL WHEEL DRIVE" value="ALL WHEEL DRIVE">
  <label for="ALL WHEEL DRIVE">ALL WHEEL DRIVE</label><br>

  <input type="checkbox" id="1047" name="1047" value="AUTOMATIC TRANSMISSION">
  <label for="1047">AUTOMATIC TRANSMISSION</label><br>

  <input type="checkbox" id="1048" name="1048" value="MANUAL TRANSMISSION">
  <label for="1048">MANUAL TRANSMISSION</label><br>

  <input type="checkbox" id="1025" name="1025" value="ADAPTIVE SUSPENSION">
  <label for="1025">ADAPTIVE SUSPENSION</label><br>

  <input type="checkbox" id="1049" name="1049" value="BED LINER">
  <label for="1049">BED LINER</label><br>

  <input type="checkbox" id="1040" name="1040" value="SPRAYIN  BEDLINER">
  <label for="1040">SPRAYIN  BEDLINER</label><br>

  <input type="checkbox" id="1024" name="1024" value="STABILITY CONTROL">
  <label for="1024">STABILITY CONTROL</label><br>

  <input type="checkbox" id="1029" name="1029" value="TRACTION CONTROL">
  <label for="1029">TRACTION CONTROL</label><br>

  
  <input type="checkbox" id="1045" name="1045" value="TRAILER TOW PACKAGE">
  <label for="1045">TRAILER TOW PACKAGE</label><br>

  <input type="checkbox" id="1043" name="1043" value="TURBOCHARGED">
  <label for="1043">TURBOCHARGED</label><br>


  </div>  
  <div class="grid-item">
   <input type="checkbox" id="1022" name="1022" value="ACTIVE CRUISE CONTROL">
  <label for="1022">ACTIVE CRUISE CONTROL</label><br>
  
  <input type="checkbox" id="1061" name="1061" value="AUTO HIGH-BEAM HEADLIGHTS">
  <label for="1061">AUTO HIGH-BEAM HEADLIGHTS</label><br>

  <input type="checkbox" id="1011" name="1011" value="BACKUP CAMERA">
  <label for="1011">BACKUP CAMERA</label><br>

  <input type="checkbox" id="1019" name="1019" value="CAMERA 360 DEGREE">
  <label for="1019">CAMERA 360 DEGREE</label><br>

  <input type="checkbox" id="1016" name="1016" value="BLIND SPOT MONITORING">
  <label for="1016">BLIND SPOT MONITORING</label><br>

  <input type="checkbox" id="1013" name="1013" value="BLUETOOTH">
  <label for="BLUETOOTH">BLUETOOTH</label><br> 

  <input type="checkbox" id="1032" name="1032" value="FOG LIGHTS">
  <label for="1032">FOG LIGHTS</label><br>

  <input type="checkbox" id="1042" name="1042" value="PRIVACY GLASS">
  <label for="1042">PRIVACY GLASS</label><br>

  <input type="checkbox" id="1052" name="1052" value="RUNNING BOARDS">
  <label for="1052">RUNNING BOARDS</label><br>

  <input type="checkbox" id="1054" name="1054" value="THEFT DETERRENT / ALARM">
  <label for="1054">THEFT DETERRENT / ALARM</label><br>

  <input type="checkbox" id="1053" name="1053" value="THEFT RECOVERY SYSTEM">
  <label for="1053">THEFT RECOVERY SYSTEM</label><br>

  </div>
  <div class="grid-item">
  
   <input type="checkbox" id="1027" name="1027" value="ALLOY WHEELS">
  <label for="1027">ALLOY WHEELS</label><br>

  <input type="checkbox" id="1055" name="1055" value="CHROME WHEELS">
  <label for="1055">CHROME WHEELS</label><br>

  <input type="checkbox" id="1059" name="1059" value="HOOD SCOOP">
  <label for="1059">HOOD SCOOP</label><br>
  
  <input type="checkbox" id="1023" name="1023" value="PAINTED ALLOY WHEELS">
  <label for="1023">PAINTED ALLOY WHEELS</label><br>

  <input type="checkbox" id="1044" name="1044" value="ROOF RACK">
  <label for="1044">ROOF RACK</label><br>

  <input type="checkbox" id="1067" name="1067" value="NONE">
  <label for="1067">NONE</label><br>
  
  
</div>

</body>
</html>
