<?php

/*
 * Database Updates.
 * 
 */

//require("calculations.php");
////////////////////////////////////////////////////////////////////////////////
function TimeUpdate($dealer, $veh_id, $status, $newStatus) {
    require("connection.php");
    date_default_timezone_set('America/Detroit');

    $query = "SELECT * ";
    $query .= "FROM time ";
    $query .= "WHERE t_dealer_id = '{$dealer}' ";
    $query .= "AND t_vehicle_id = '{$veh_id}' ";
    $query .= "AND t_status = '{$status}' ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed time: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);
    $t_id = $row['t_id'];

    $start = $row['t_start_time'];
    $end = date("Y-m-d H:i:s");

    $calculatedTime_array = calculateTime($start, $end);

    $days = $calculatedTime_array['Days'];
    $hours = $calculatedTime_array['Hours'];
    $minutes = $calculatedTime_array['Minutes'];

    mysqli_query($con, "UPDATE time SET t_end_time = '{$end}'
                                         WHERE t_id = '{$t_id}' ");

    mysqli_query($con, "UPDATE time SET t_days = '{$days}'
                                         WHERE t_id = '{$t_id}' ");

    mysqli_query($con, "UPDATE time SET t_hours = '{$hours}'
                                         WHERE t_id = '{$t_id}' ");

    mysqli_query($con, "UPDATE time SET t_minutes = '{$minutes}'
                                         WHERE t_id = '{$t_id}' ");

    mysqli_query($con, "UPDATE vehicles SET veh_status = '{$newStatus}'
                                         WHERE veh_id = '{$veh_id}' ");

    $sql = "INSERT INTO time(t_dealer_id,t_vehicle_id,t_status)
                  VALUES('$dealer','$veh_id','$newStatus')";

    if (!mysqli_query($con, $sql)) {
        die('Error input vehicles: ' . mysqli_error($con));
    }
}

//*********************************************************** */

function vehicleProblem($fieldname) {
switch ($fieldname) {
    case "drive":
        $fieldname = "Drive Type";
      break;
    case "trans":
        $fieldname = "Transmission";
      break;
    case "hc":
        $fieldname = "Engine heating/cooling system problem";
      break;                    
    case "shifting":
        $fieldname = "Shifting problem";
      break;
    case "vibration":
        $fieldname = "Abnormal noise or vibration";
      break;
    case "cases":
        $fieldname = "Visible cracks or damage to cases or housings";
      break;
    case "starting":
        $fieldname = "Starting/charging/ignition system problem";
      break;
    case "device":
        $fieldname = "Engine";
      break;
    case "oil":
        $fieldname = "Signs of excessive oil consumption";
      break;
    case "noise":
        $fieldname = "Abnomal noise";
      break;
    case "block":
        $fieldname = "Signs of crackd block or head, or blown head gasket or inoperative turbo";
      break;
    case "module":
        $fieldname = "Electrical control module problem";
      break;
    case "smoke":
        $fieldname = "Abnormal exhaust or engine smoke";
      break;
    case "original":
        $fieldname = "Chaned or modified from original manufacturer specification";
      break;
    case "fluid":
        $fieldname = "Fluid levels low";
      break;
    case "leaks":
        $fieldname = "Known or visible leaks, excluding minor seepage";
      break;
    case "problem":
        $fieldname = "Gauge or device indicatin a system problem";
      break;
    case "gauge":
        $fieldname = "Gauge, dash lights or device inoperative";
      break;
    case "unibody":
        $fieldname = "Corrective welds / Knowleedge or evidence of repair to strut tower / floor pan /frame / or structural";
      break;
    case "frame":
        $fieldname = "Evidence or knowledge of frame repair or replacement";
      break;
    case "abs":
        $fieldname = "ABS Brake System problem";
      break;
    case "cruise":
        $fieldname = "Cruise control problem";
      break;
    case "air":
        $fieldname = " Air conditioning system problem";
      break;
    case "heat":
        $fieldname = " Interior cabin heating system problem";
      break;
    case "accessory":
        $fieldname = " Power accessory problem";
      break;
    case "sound":
        $fieldname = " Sound system problem";
      break;
    case "nav":
        $fieldname = " Navigation system problem";
      break;
    case "enter":
        $fieldname = "Entertainment system problem";
      break;
    case "spare":
        $fieldname = "Spare tire / jack missing";
      break;
    case "parking":
        $fieldname = "Parking Brake";
      break;
    case "service":
        $fieldname = "Service Brake";
      break;
    case "bumpers":
        $fieldname = "Bumpers";
      break;
    case "doors":
        $fieldname = "Doors, Hoods, and Trunk Lid";
      break;
    case "emission":
        $fieldname = "Emission Equipment";
      break;
    case "exhaust":
        $fieldname = "Exhaust System";
      break;
    case "firewall":
        $fieldname = "Fenders, Firewall And Floor Pan";
      break;
    case "structural":
        $fieldname = "Frame or Structural Portion of Unibody";
      break;
    case "fuel":
        $fieldname = "Fuel System";
      break;
    case "horn":
        $fieldname = "Horn";
      break;
    case "seats":
        $fieldname = "Restraining Devices and Seats - Air bags/belts";
      break;
    case "odometer":
        $fieldname = "Speedometer and Odometer";
      break;
    case "steering":
        $fieldname = "Steering Components";
      break;
    case "suspension":
        $fieldname = "Suspension";
      break;
    case "tires":
        $fieldname = "Tires and Wheels";
      break;
    case "windows":
        $fieldname = "Windshield, Windows and Mirrors";
      break;
      case "wipers":
        $fieldname = "Windshield Defroster, Wipers, and Washers";
      break;
      case "cert":
        $fieldname = "Needed to certify vehicle";
      break;
      case "recomme":
        $fieldname = "Work recommended by mechanic";
      break;
      

    default:
     
  }
  return $fieldname;
}

////////////////////////Add Vehicles to database///////////////////////////

function addVehicles($vhicleType) {
  require("../includes/connection.php");

  $userId = $_COOKIE["userId"];
           
  $emp_arry = Employee($userId);
  $name = $emp_arry[0] . ' ' . $emp_arry[1];
  $emp_id = $emp_arry[5];
  $emp_dealer_id = $emp_arry[6];

  $query = "SELECT * ";
  $query .= "FROM dealer ";
  $query .= "WHERE dealer_Id    = '{$emp_dealer_id}' ";

  $result_set = mysqli_query($con, $query)
          or die('Query failed2: ' . mysql_error());
  $row = mysqli_fetch_array($result_set);

  $dealer_name = $row['dealer_name'];

  $count = 0;

  $query = "SELECT * ";
  $query .= "FROM excel_file ";
  


  $result_set = mysqli_query($con, $query)
          or die('Query failed PhotoManagement 75: ' . mysqli_error($con));
  while($row = mysqli_fetch_array($result_set)){
  
  $photos = $row['col6'];
  $vehicle = $row['col9'];
  $vin = $row['col11'];
  $stock = $row['col10'];
  $class = $row['col12'];
  $body = $row['col18'];
  $color = $row['col19'];
  $disposition = $row['col20'];
  $age= $row['col17'];
  $price = $row['col21'];        
  $miles = $row['col28'];
  
  $d=strtotime("+2 Months");
  $deleteDate =  date("Y-m-d", $d);

  if($vin == 'VIN'){
     continue;
  } else{
     
  $p = str_split($price);

  $vhiclePrice = '';

foreach ($p as $x) {

  if($x == "$"){
      continue;
  }
  if($x == ","){
      continue;
  }
  $vhiclePrice .= $x;
}

$m = str_split($miles);
$vhicleMiles = '';
foreach ($m as $x) {

if($x == ","){
    continue;
}
$vhicleMiles .= $x;
}

     $query1 = "SELECT * ";
     $query1 .= "FROM vehicles ";
     $query1 .= "WHERE veh_vin  = '{$vin}' ";
     $query1 .= "AND veh_dealer  = '{$emp_dealer_id}' ";
     $query1 .= "AND veh_new_used  = '{$vhicleType}' ";
 
 
     $result_set1 = mysqli_query($con, $query1)
             or die('Query failed: ' . mysqli_error($con));
 
     $row1 = mysqli_fetch_array($result_set1);
     if(isset($row1['veh_id'])){
         $veh_id = $row1['veh_id'];

         if($disposition == 'Wholesale'){
             $status = 'WS';
             mysqli_query($con, "UPDATE vehicles SET veh_status = '$status'
             WHERE veh_id = '$veh_id' ");
              mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
              WHERE veh_id = '$veh_id' ");
         }
        
      
        $veh_price = $row1['veh_price'];
         if($veh_price == 0){
             mysqli_query($con, "UPDATE vehicles SET veh_price = '$vhiclePrice'
             WHERE veh_id = '$veh_id' ");
         }
         $veh_miles = $row1['veh_miles'];
         if($veh_miles == 0){
             mysqli_query($con, "UPDATE vehicles SET veh_miles = '$vhicleMiles'
             WHERE veh_id = '$veh_id' ");
         }
        
         continue;
     }
 } 

 $veh = explode(" ",$vehicle);

 if(isset($veh[0])){
     $year = $veh[0];
 }

 if(isset($veh[1])){
     $make = $veh[1];
 }
 
 if(isset($veh[2])){
     $model = $veh[2];
 }

 if(isset($veh[3])){
     $trim = $veh[3];
 }

 $cl = explode(",",$class);
 $type = $cl[0];

 if(empty($body)){
     $body = $type;
 }

if($vhicleType == 'used'){
$status = 'Waiting';
}else{
$status = 'Service';
}
$count++;

$sql = "INSERT INTO vehicles(veh_dealer, veh_vin, veh_emp_id, veh_year, veh_make, veh_model, veh_owner, veh_location, veh_sales_name, veh_new_used, veh_status, veh_stock, veh_miles, veh_price, veh_color, veh_vehicle_type) 
VALUES('$emp_dealer_id','$vin','$emp_id','$year','$make','$model','$dealer_name','$dealer_name','$name','$vhicleType','$status','$stock','$vhicleMiles','$vhiclePrice','$color','$body')";


if (!mysqli_query($con, $sql)) {
die('Error input veh 313: ' . mysqli_error($con));
}

//////////////////////////////Add record to time //////////////////////////////////////////////////////
$query2 = "SELECT * ";
$query2 .= "FROM vehicles ";
$query2 .= "WHERE veh_vin  = '{$vin}' ";
$query2 .= "AND veh_dealer  = '{$emp_dealer_id}' ";
$query2 .= "AND veh_new_used  = '{$vhicleType}' ";


$result_set2 = mysqli_query($con, $query2)
        or die('Query failed: ' . mysqli_error($con));

$row2 = mysqli_fetch_array($result_set2);
$veh_id = $row2['veh_id'];


$status_arr = array($status ,'Photos','Plates','Detail','Total');

    foreach ($status_arr as $x) {
       

    $sql = "INSERT INTO time(t_dealer_id,t_vehicle_id,t_status) 
                                     VALUES('$emp_dealer_id','$veh_id','$x')";


    if (!mysqli_query($con, $sql)) {
        die('Error input vehicles: ' . mysqli_error($con));
    }
}

//////////////////////////////////////////////////////////////////////////////////////


}
return $count;

}


function removeVehicle($vhicleType) {
  
  require("../includes/connection.php");

  $userId = $_COOKIE["userId"];
           
  $emp_arry = Employee($userId);
  $name = $emp_arry[0] . ' ' . $emp_arry[1];
  $emp_id = $emp_arry[5];
  $emp_dealer_id = $emp_arry[6];

  $query = "SELECT * ";
  $query .= "FROM dealer ";
  $query .= "WHERE dealer_Id    = '{$emp_dealer_id}' ";

  $result_set = mysqli_query($con, $query)
          or die('Query failed2: ' . mysql_error());
  $row = mysqli_fetch_array($result_set);

  $dealer_name = $row['dealer_name'];

  $count = 0 ;

  ///////////////////////////////////////////check if vehicle is not in inventory//////////////////////////

$query = "SELECT * ";
$query .= "FROM vehicles ";
$query .= "WHERE veh_dealer  = '{$emp_dealer_id}' ";
$query .= "AND veh_new_used  = '{$vhicleType}' ";



$result_set = mysqli_query($con, $query)
        or die('Query failed: ' . mysqli_error($con));

        while($row = mysqli_fetch_array($result_set)){
            $veh_id = $row['veh_id'];
            $veh_vin = $row['veh_vin'];
            $veh_stock = $row['veh_stock'];
            $veh_year = $row['veh_year'];
            $veh_make = $row['veh_make'];
            $veh_model = $row['veh_model'];
            $veh_status = strtoupper($row['veh_status']);

         
        

            $veh = $veh_year . '  ' . $veh_make . ' ' . $veh_model ;
            
            $query1 = "SELECT * ";
            $query1 .= "FROM excel_file ";
            $query1 .= "WHERE col11  = '{$veh_vin}' ";
            

            $result_set1 = mysqli_query($con, $query1)
                    or die('Query failed PhotoManagement 277: ' . mysqli_error($con));
            $row1 = mysqli_fetch_array($result_set1);

            if(isset($row1)){         
            $col11 = $row1['col11'];
            echo 'Not empty: ' . $col11 . '<br>';
            continue;
            }else{
              echo 'EMPTY: ' . $veh_vin . '<br>';
                
              $count++;
              $d=strtotime("+2 Months");
              $deleteDate =  date("Y-m-d", $d);
   
                  switch ($veh_status) {
                   case "SOLD":
                      $vehStatus = 'Delivered';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");  
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");                
                     break;
                   case "LOT":
                       $vehStatus = 'WS';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");
                     break;
                   case "SERVICE":
                       $vehStatus = 'WS';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");
                     break;
                     case "WAITING":
                       $vehStatus = 'WS';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");
                     break; case "WRITER":
                       $vehStatus = 'WS';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");
                     break;
                     case "CHECK IN":
                       $vehStatus = 'WS';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");
                     break;
                     case "MECHANIC":
                       $vehStatus = 'WS';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");
                     break;
                     case "PRICE":
                       $vehStatus = 'WS';
                       mysqli_query($con, "UPDATE vehicles SET veh_status = '$vehStatus'
                       WHERE veh_id = '$veh_id' ");
                        mysqli_query($con, "UPDATE vehicles SET veh_delete = '$deleteDate'
                        WHERE veh_id = '$veh_id' ");
                     break;
   
                   default:
                     echo  $veh_vin . ' STATUS IS: ' . $veh_status . '<br>';
                 }
            }

  
           
            }
            return $count;
         }
 
                
      