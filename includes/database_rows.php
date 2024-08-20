<?php

/*
 * A group of functions to crate row from databases
 *  
 */

/////////////////////////////////////////////////////////////////////////////

function VehicleRows($dealer, $type, $page, $search, $status, $empSsc='none') {
    require("connection.php");
    //require("calculations.php");



    $data_arr = array();
    $userId = $_COOKIE["userId"];

    $query = "SELECT * ";
    $query .= "FROM employee ";
    $query .= "WHERE emp_id   = '{$userId}' ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed emp: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);


    $emp_id = $row['emp_id'];
    $emp_dealer_id = $row['emp_dealer_id'];
    $emp_position = $row['emp_position'];

    $query = "SELECT * ";
    $query .= "FROM dealer ";
    $query .= "WHERE dealer_Id   = '{$emp_dealer_id}' ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed dealer: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);

    $dealer_timezone = $row['dealer_timezone'];

    
    date_default_timezone_set('America/Chicago');
 
    $bid_satus = 'black';
    echo "\n<div id=\"$bid_satus\"><a >"
    . "</a>"
    . "<table >"
    . "<tr><td  width = 200px><a style=\"color:yellow;\" href=\"#\">VIN</a></td>"
    . "<td  width = 200px><a style=\"color:yellow;\" href=\"#\">Stock Number</a></td>"
    . "<td  width = 500px><a style=\"color:yellow;\" href=\"#\">Vehicle</a></td>"
    . "<td  width = 200px><a style=\"color:yellow;\" href=\"#\">Time In Department</a></td>"
    . "<td  width = 200px><a style=\"color:yellow;\" href=\"#\">Miles</a></td>" 
    . "<td  width = 300px><a style=\"color:yellow;\" href=\"#\">Inspection Type</a></td>"
    . "<td  width = 300px><a style=\"color:yellow;\" href=\"#\">Service Writer</a></td>"
    . "</table></div>\n";

    $query = "SELECT * ";
    $query .= "FROM vehicles ";
    $query .= "WHERE veh_dealer = '{$dealer}' ";

    if ($type != 'none') {
        $query .= "AND veh_new_used = '{$type}' ";
       
    }
   
    if ($empSsc != 'none') {
        $query .= "AND veh_ssc = '{$empSsc}' ";
       
    }
    if($search == 'Mechanic'){
        $query .= "AND veh_status = '{$search}' "; 
    }else{
        $query .= "AND veh_status = '{$status}' ";
    }
    
    if ($search != 'Mechanic') {
    if ($search != 'none') {
        $query .= "AND veh_vin LIKE '%$search%' ";
    }
}
    $query .= "ORDER BY veh_date_time ASC";


  
    $result_set = mysqli_query($con, $query)
            or die('Query failed vehicles: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);

    $result_set = mysqli_query($con, $query)
            or die('Query failed emp 88: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) {

        $veh_id = $row['veh_id'];
        $veh_stock = $row['veh_stock'];
        $veh_vin = $row['veh_vin'];
        $veh_year = $row['veh_year'];
        $veh_make = $row['veh_make'];
        $veh_model = $row['veh_model'];
        $veh_trim = $row['veh_trim'];
        $veh_miles = $row['veh_miles'];
        $veh_date_time = $row['veh_date_time'];
        $veh_mechanic = $row['veh_mechanic'];
        $veh_appoval = $row['veh_appoval'];
        $veh_ssc = $row['veh_ssc'];
        $veh_insp = $row['veh_insp'];
        $insp = explode("-",$veh_insp);
        $veh_insp = $insp[0];
        $ssc_arry = explode("-",$veh_ssc);
        $veh_ssc = $ssc_arry[0];
       
        if($status == 'Mechanic'){
           $mech = explode("*",$veh_mechanic);
           $mechanicNumber = $mech[1];
           if($mechanicNumber != $emp_id){
            continue;
           
        }
   }


        $vehicle = $veh_year . ' ' . $veh_make . ' ' . $veh_model . ' ' . $veh_trim;

        if ($search != 'Mechanic') {
            if($veh_insp == 'LOANER'){
                $status = 'Service'; 
            }
        
        $query1 = "SELECT * ";
        $query1 .= "FROM time ";
        $query1 .= "WHERE t_dealer_id = '{$dealer}' ";
        $query1 .= "AND t_vehicle_id = '{$veh_id}' ";
        $query1 .= "AND t_status = '{$status}' ";
        $query1 .= "ORDER BY t_id  DESC ";


        $result_set1 = mysqli_query($con, $query1)
                or die('Query failed time: ' . mysqli_error($con));
        $row1 = mysqli_fetch_array($result_set1);

      
        $start = $row1['t_start_time'];
       
        $end = date("Y-m-d H:i:s");



        $calculatedTime_array = calculateTime($start, $end);


        $days = $calculatedTime_array['Days'];

        $hours = $calculatedTime_array['Hours'];
        $minutes = $calculatedTime_array['Minutes'];


        // $time = 'Years: ' . $year . ' Months: ' . $month . ' Days: ' . $days . ' Hours: ' . $hours . ' Minutes: ' . $minutes . ' Seconds: ' . $seconds;
        //$time = $year . ' Years ' . $month . ' Months ' . $days . ' Days ' . $hours . ' Hours ' . $minutes . ' Minutes ' . $seconds . ' Seconds ';
        $time = $days . ' Days ' . $hours . ' Hours ' . $minutes . ' Minutes ';
      
        if($veh_appoval == 'done'){
            $bid_satus = 'green';
        }else{
            if($type == 'used'){
                $bid_satus = 'Offer';
            }else{
                $bid_satus = 'green';
            }

            
            
        }
       

        $data_arr[] = "\n<div id=\"$bid_satus\"><a href=$page?ID=$veh_id>"
                . "<table >"
                . "<tr><td style=\"color:black;\" width = 200px>$veh_vin</td>"
                . "<td style=\"color:black;\" width = 200px>$veh_stock</td>"
                . "<td style=\"color:black;\" width = 500px>$vehicle</td>"
                . "<td style=\"color:black;\" width = 200px>$time </td></a>"
                . "<td style=\"color:black;\" width = 200px>$veh_miles </td></a>"             
                . "<td style=\"color:black;\" width = 300px>$veh_insp</td></a>"
                . "<td style=\"color:black;\" width = 300px>$veh_ssc</td></a>"
                . "</table></div>\n";
         } else{
            $bid_satus = 'Offer';

        $data_arr[] = "\n<div id=\"$bid_satus\"><a href=$page?ID=$veh_id>"
                . "<table >"
                . "<tr><td style=\"color:black;\" width = 200px>$veh_vin</td>"
                . "<td style=\"color:black;\" width = 200px>$veh_stock</td>"
                . "<td style=\"color:black;\" width = 500px>$vehicle</td></a>"
                . "</table></div>\n";
         }
    }
    return $data_arr;

}
 
function Employee($emp) {
    require("connection.php");
 

    $query = "SELECT * ";
    $query .= "FROM employee ";
    $query .= "WHERE emp_id  = '{$emp}' ";


    $result_set = mysqli_query($con, $query)
            or die('Query failed: ' . mysqli_error($con));

    $row = mysqli_fetch_array($result_set);

    $emp_id = $row['emp_id'];
    $emp_dealer_id = $row['emp_dealer_id'];
    $first = $row['emp_first_name'];
    $last = $row['emp_last_name'];
    $emp_position = $row['emp_position'];
    $emp_user_name = $row['emp_user_name'];
   



  /*  $obj = new Person($first, $last, $email, $phone);
    $data = $obj->first;
    $first = $obj->unencrypt($data);
    $data = $obj->last;
    $last = $obj->unencrypt($data);
    $data = $obj->email;
    $email = $obj->unencrypt($data);
    $data = $obj->phone;
    $phone = $obj->unencrypt($data);
    $phoneCarrie = $emp_phone_provider;*/
                    //  0      1      2             3         4               5                     
    $data_arr = array($first, $last, $emp_position, $emp_id, $emp_dealer_id, $emp_user_name);

    return $data_arr;
}



function readData($fileName, $fieldName, $index_number) {
    require("connection.php");

    $query = "SELECT * ";
    $query .= "FROM $fileName ";
    $query .= "WHERE $fieldName  = '{$index_number}' ";


    $result_set = mysqli_query($con, $query)
            or die('Query failed 179: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);
    return $row;
}
 
function equipment($dealer_group, $code) {
    require("connection.php");
    $equipComfort_arr[] = '';
    $equipPerformance_arr[] = '';
    $equipSafety_arr[] = '';
    $equipStyle_arr[] = '';

    $save_equip_category = '';
    $equip_arr[] = "<div class=\"container-fluid\">\n";
    $equip_arr[] = "\n";
    $equip_arr[] = "<div class=\"row\">\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "COMFORT\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "PERFORMANCE\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "SAFETY\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "STYLE\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "</div>\n";

    $used = 'USED';

  

    $query = "SELECT * ";
    $query .= "FROM code_equip ";
    $query .= "WHERE equip_group_num  = '{$dealer_group}' ";
    $query .= "AND equip_make  = '{$used}' ";
    $query .= "AND equip_model  = '{$used}' ";
    $query .= "ORDER BY equip_category, equip_list  ASC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed3: ' . mysql_error());

    while ($row = mysqli_fetch_array($result_set)) {
        $equip_index = $row['equip_index'];
        $equip_list = $row['equip_list'];
        $equip_category = $row['equip_category'];
        $equip_code = $row['equip_code'];


        if ($equip_category != $save_equip_category) {



            $_SESSION['firstl'] = 'set';
            $save_equip_category = $equip_category;
        }
        
        $codeArray = explode(" ",$code);
       
        if (in_array($equip_code, $codeArray)) {
            $codeFound = 'checked';           
        } else {
           $codeFound = 'no';
        }
        
        switch ($equip_category) {
            case "COMFORT":
                $equipComfort_arr[] = " <h4><input type=\"checkbox\" name=\"equip[]\" value=\"$equip_code\" $codeFound> $equip_list - $equip_code </h4>";
                break;
            case "PERFORMANCE":
                $equipPerformance_arr[] = " <h4><input type=\"checkbox\" name=\"equip[]\" value=\"$equip_code\" $codeFound> $equip_list - $equip_code </h4>";
                break;
            case "SAFETY":
                $equipSafety_arr[] = " <h4><input type=\"checkbox\" name=\"equip[]\" value=\"$equip_code\" $codeFound> $equip_list - $equip_code </h4>";
                break;
            case "STYLE":
                $equipStyle_arr[] = " <h4><input type=\"checkbox\" name=\"equip[]\" value=\"$equip_code\" $codeFound> $equip_list - $equip_code </h4>";
                break;
            default:
        }
    }

    $comfort = count($equipComfort_arr);
    $performance = count($equipPerformance_arr);
    $safety = count($equipSafety_arr);
    $style = count($equipStyle_arr);
    $number = $comfort;
    if ($number < $performance) {
        $number = $performance;
    }
    if ($number < $safety) {
        $number = $safety;
    }
    if ($number < $style) {
        $number = $style;
    }
    $num = $number - 1;
    $comfort = $comfort - 1;
    $performance = $performance - 1;
    $safety = $safety - 1;
    $style = $style - 1;
    $blank = '';
    for ($x = 0; $x <= $number; $x++) {

        $equip_arr[] = "<div class=\"container-fluid\">\n";
        $equip_arr[] = "\n";
        $equip_arr[] = "<div class=\"row\">\n";

        if ($x <= $comfort) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $equipComfort_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        if ($x <= $performance) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $equipPerformance_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        if ($x <= $safety) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $equipSafety_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        if ($x <= $style) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $equipStyle_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow; color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        $equip_arr[] = "</div>\n";
        $equip_arr[] = "</div>\n";
    }



    return $equip_arr;
}

///////////////////////////////////////////////////////////////////////////////

function DisplayOptions($id) {
    require("connection.php");


    $query = "SELECT * ";
    $query .= "FROM  vehicles ";
    $query .= "WHERE veh_id = '{$id}' ";
    $query .= "ORDER BY veh_date_time  DESC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 97: ' . mysqli_error($con));

    $row = mysqli_fetch_array($result_set); // start while
    $used_index = $row['veh_id'];
    $used_dealer_num = $row['veh_dealer'];
    $used_vin = $row['veh_vin'];
    $_SESSION['vin'] = $used_vin;
    $used_salesperson = $row['veh_sales_name'];
    $_SESSION['salesperson'] = $used_salesperson;
    $used_miles = $row['veh_miles'];
    $_SESSION['miles'] = $used_miles;
    $used_year = $row['veh_year'];
    $used_make = $row['veh_make'];
    $used_model = $row['veh_model'];
    $used_trim = $row['veh_trim'];
    $used_engine = $row['veh_engine'];
    $_SESSION['engine'] = $used_engine;
    $used_trans = $row['veh_trans'];
    $_SESSION['trans'] = $used_trans;
    $used_type = $row['veh_drive_type'];
    $_SESSION['type'] = $used_type;
    $used_color_ext = $row['veh_color'];
    $_SESSION['ext'] = $used_color_ext;
    $used_color_int = $row['veh_int'];
    $_SESSION['int'] = $used_color_int;
    $used_first = $row['veh_first'];
    $_SESSION['first'] = $used_first;
    $used_last = $row['veh_last'];
    $_SESSION['last'] = $used_last;

    $used_payoff = $row['veh_payoff'];
    $_SESSION['payoff'] = $used_payoff;

    $used_equip = $row['veh_equip'];

    $veh = $used_year . ' ' . $used_make . ' ' . $used_model . ' ' . $used_trim;

    $_SESSION['veh'] = $veh;


    $equip_arr[] = "<div class=\"container-fluid\">\n";
    $equip_arr[] = "\n";
    $equip_arr[] = "<div class=\"row\">\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "COMFORT\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "PERFORMANCE\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "SAFETY\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
    $equip_arr[] = "STYLE\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "</div>\n";
    $equip_arr[] = "</div>\n";
    $i = 0;
    $e = explode(" ", $used_equip);
    $count = count($e);
    foreach ($e as $value) {
        $i++;
        if ($i >= $count) {
            break;
        }

        $makeModel = 'USED';
        $query = "SELECT * ";
        $query .= "FROM  code_equip ";
        $query .= "WHERE equip_make = '{$makeModel}' ";
        $query .= "AND equip_model = '{$makeModel}' ";
        $query .= "AND equip_code = '{$value}' ";
        $query .= "ORDER BY equip_list  DESC ";

        $result_set = mysqli_query($con, $query)
                or die('Query failed 87: ' . mysqli_error($con));

        $row = mysqli_fetch_array($result_set); // start while
        if (isset($row['equip_list'])) {
            $equip_list = $row['equip_list'];
        }
        if (isset($row['equip_category'])) {
            $equip_category = $row['equip_category'];
        }
        switch ($equip_category) {
            case "COMFORT":
                $equipComfort_arr[] = " <h4>$equip_list</h4>";
                break;
            case "PERFORMANCE":
                $equipPerformance_arr[] = " <h4>$equip_list</h4>";
                break;
            case "SAFETY":
                $equipSafety_arr[] = " <h4>$equip_list</h4>";
                break;
            case "STYLE":
                $equipStyle_arr[] = " <h4>$equip_list</h4>";
                break;
            default:
                echo "Category was not found for: " . $equip_list . "!";
        }
    }

    $_SESSION['veh'] = $used_year . ' ' . $used_make . ' ' . $used_model . ' ' . $used_trim;

    $_SESSION['vin'] = $used_vin;



//////////////////////////////////////////////////////////////////////////////////////////////////

    if (isset($equipComfort_arr)) {
        $comfort = count($equipComfort_arr);
    } else {
        $comfort = 0;
    }
    if (isset($equipPerformance_arr)) {
        $performance = count($equipPerformance_arr);
    } else {
        $performance = 0;
    }
    if (isset($equipSafety_arr)) {
        $safety = count($equipSafety_arr);
    } else {
        $safety = 0;
    }
    if (isset($equipStyle_arr)) {
        $style = count($equipStyle_arr);
    } else {
        $style = 0;
    }
    $number = $comfort;
    if ($number < $performance) {
        $number = $performance;
    }
    if ($number < $safety) {
        $number = $safety;
    }
    if ($number < $style) {
        $number = $style;
    }
    $num = $number - 1;
    $comfort = $comfort - 1;
    $performance = $performance - 1;
    $safety = $safety - 1;
    $style = $style - 1;
    $blank = '';
    for ($x = 0; $x <= $number; $x++) {

        $equip_arr[] = "<div class=\"container-fluid\">\n";
        $equip_arr[] = "\n";
        $equip_arr[] = "<div class=\"row\">\n";

        if ($x <= $comfort) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:white;color:black;\">\n";
            $equip_arr[] = $equipComfort_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:white;color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        if ($x <= $performance) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:white;color:black;\">\n";
            $equip_arr[] = $equipPerformance_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:white;color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        if ($x <= $safety) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:white;color:black;\">\n";
            $equip_arr[] = $equipSafety_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:white;color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        if ($x <= $style) {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:white;color:black;\">\n";
            $equip_arr[] = $equipStyle_arr[$x];
            $equip_arr[] = "</div>\n";
        } else {
            $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:White;color:black;\">\n";
            $equip_arr[] = $blank;
            $equip_arr[] = "</div>\n";
        }
        $equip_arr[] = "</div>\n";
        $equip_arr[] = "</div>\n";
    }
    return $equip_arr;
}
