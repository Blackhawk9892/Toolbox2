<?php

///////////////////////////////Dealer Group////////////////////////////////////////////////

function DealerGroup($group = 'empty') {
    $rows = array();
    $blank = '';
    if ($group == 'empty') {
        $rows[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $rows[] = "\n<option value=\"$group\">$group</option>\n";
    }
    require("includes/connection.php");

    $query = "SELECT * ";
    $query .= "FROM dealer_group ";
    $query .= "ORDER BY dg_name ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed1: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) {

        $dg_id = $row['dg_id'];
        $dg_name = $row['dg_name'];

        $idName = $dg_name . ' ' . $dg_id;
        $rows[] = "\n<option value=\"$idName\">$idName</option>\n";
    }
    return $rows;
}

//////////////////////////////Dealer/////////////////////////////////////////////////////
function Dealer($group, $dealer = 'empty') {
    $blank = '';
    if ($dealer == 'empty') {
        $dealer_arr[] = "\n<option value=\"$_POST[dealer]\">$_POST[dealer]</option>\n";
    } else {
        $dealer_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }

    $query = "SELECT * ";
    $query .= "FROM dealer ";
    $query .= "ORDER BY dealer_name";

    $result_set = mysqli_query($con, $query)
            or die('Query failed3: ' . mysqli_error($con));

    while ($row = mysqli_fetch_array($result_set)) {
        $dealer_Id = $row['dealer_Id'];
        $dealer_name = $row['dealer_name'];
        $dealer = $dealer_Id . '-' . $dealer_name;
        $dealer_arr[] = "\n<option value=\"$dealer\">$dealer</option>\n";
    }
    return $dealer_arr;
}

/////////////////////////////Job Access///////////////////////////////////////////////////
function JobAccess($group = 'empty') {
    $blank = '';
    if ($group == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$group\">$group</option>\n";
    }
    $place = 'Block';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
   // $place = 'View';
   // $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Update';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";

    return $data_arr;
}

//////////////////////////////Exterior Color//////////////////////////////////////////////////

function ExtColor($c_name,$model, $color = 'empty') {
     require("connection.php");
    $blank = '';
    if ($color == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$color\">$color</option>\n";
    }
    
     $query = "SELECT * ";
    $query .= "FROM colors_ext ";
    $query .= "WHERE ext_corp = '{$c_name}' ";
    $query .= "AND ext_model = '{$model}' ";
    $query .= "ORDER BY ext_color_code ";
    

    $result_set = mysqli_query($con, $query)
            or die('Query 92 pd: ' . mysqli_error($con));

    while ($row = mysqli_fetch_array($result_set)) {
        $ext_color_code = $row['ext_color_code'];
        $ext_color = $row['ext_color'];
        $color = $ext_color_code . ' ** ' . $ext_color;
     
        $data_arr[] = "\n<option value=\"$color\">$color</option>\n";
    }
   
    return $data_arr;
}

///////////////////////////Interior /////////////////////////////////////////////////////
function interior($c_name,$model,$int = 'empty') {
     require("connection.php");
    $blank = '';
    if ($int == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$int\">$int</option>\n";
    }
     $query = "SELECT * ";
    $query .= "FROM colors_int ";
    $query .= "WHERE int_corp = '{$c_name}' ";
    $query .= "AND int_model = '{$model}' ";
    $query .= "ORDER BY int_color_code";

    $result_set = mysqli_query($con, $query)
            or die('Query 120 pd: ' . mysqli_error($con));

    while ($row = mysqli_fetch_array($result_set)) {
       
        $int_color_code = $row['int_color_code'];
        $int_color = $row['int_color'];
        $color = $int_color_code . ' ** ' . $int_color;
        $data_arr[] = "\n<option value=\"$color\">$color</option>\n";
    }
   

    return $data_arr;
}

////////////////////////////Yes or No////////////////////////////////////////////////////
function YesNo($yn = 'empty') {
    $blank = '';
    if ($yn == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$yn\">$yn</option>\n";
    }
    $place = 'Yes';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'No';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";


    return $data_arr;
}

////////////////////////Year //////////////////////////////////////////////////

function db_year($c_name, $year = 'empty') {
    require("connection.php");
    if ($year == 'empty') {
        $blank = "";
        $year_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $year_arr[] = "\n<option value=\"$year\">$year</option>\n";
    }
    if (isset($_SESSION['corp'])) {
        $c_name = $_SESSION['corp'];
    }
    $query = "SELECT * ";
    $query .= "FROM year ";
    $query .= "WHERE corp = '{$c_name}' ";
    $query .= "ORDER BY year DESC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 275: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) { // start while	
        $year = $row['year'];

        $year_arr[] = "\n<option value=\"$year\">$year</option>\n";
    }
    return $year_arr;
}

////////////////////////////// Model ////////////////////////////////////////

function db_model($corp_id, $make, $model) {
  
    require("connection.php");
    if ($model == 'empty') {
        $blank = "";
        $model_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $model_arr[] = "\n<option value=\"$model\">$model</option>\n";
    }
    if (isset($_SESSION['corp'])) {
        $c_name = $_SESSION['corp'];
    }

    $query = "SELECT * ";
    $query .= "FROM model ";
    $query .= "WHERE model_make  = '{$make}' ";
    $query .= "AND model_corp_id   = '{$corp_id}' ";
    $query .= "ORDER BY model ASC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 405: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) { // start while	
        $models = $row['model'];
        $model_arr[] = "\n<option value=\"$models\">$models</option>\n";
    }
    return $model_arr;
}

///////////////////////////// Make ////////////////////////////////////////////

function db_make($company_number, $make = 'empty') {
    require("connection.php");
    $blank = '';
    if ($make == 'empty') {
        $blank = "";
        $make_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $make_arr[] = "\n<option value=\"$make\">$make</option>\n";
        $make_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    if (isset($_SESSION['comp'])) {
        $company_number = $_SESSION['comp'];
    }

    $query = "SELECT * ";
    $query .= "FROM make ";
    $query .= "WHERE dealer_id  = '{$company_number}' ";
    $query .= "ORDER BY make ASC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 432: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) { // start while	
        $makes = $row['make'];
        $make_arr[] = "\n<option value=\"$makes\">$makes</option>\n";
    }
    return $make_arr;
}

/////////////////////Build Code****************************REBUILD

function db_buildcode($c_name, $model, $buildCode = 'empty') {
    require("connection.php");
    if ($buildCode == 'empty') {
        $blank = "";
        $build_bcode_arr[] = "\n<option value=\"$blank \">$blank </option>\n";
    } else {
        $build_bcode_arr[] = "\n<option value=\"$buildCode\">$buildCode</option>\n";
        $build_bcode_arr[] = "\n<option value=\"$blank \">$blank </option>\n";
    }
    $query = "SELECT * ";
    $query .= "FROM build ";
    $query .= "WHERE build_model  = '{$model}' ";
    $query .= "AND build_corp   = '{$c_name}' ";
    $query .= "ORDER BY build_bcode  ASC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 483: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) { // start while	
        //	$build_trim    = $row['build_trim'];
        $build_bcode = $row['build_bcode'];
        $build_code = $row['build_code'];
        $build_inside = $row['build_inside'];
        $build_descrip = $row['build_descrip'];
        $build_status = $row['build_status'];



        $build_bcode_arr[] = "\n<option value=\"$build_bcode\">$build_bcode </option>\n";
    }
    return $build_bcode_arr;
}

////////////////////////////Employee//////////////////////////////////////////////////

function EmployeeRow($type, $dealer, $sales) {
    require("connection.php");
    require_once("../classes/person.class.php");
  //  $data_arr[] = "\n<option value=\"\"></option>\n";

    $blank = "";
    if ($sales == 'empty') {
        
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$sales\">$sales</option>\n";
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }

    $query = "SELECT * ";
    $query .= "FROM employee ";
    $query .= "WHERE emp_position = '{$type}' ";
    $query .= "AND emp_dealer_id = '{$dealer}' ";
    $query .= "ORDER BY emp_first_name  DESC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed: ' . mysqli_error($con));

    while ($row = mysqli_fetch_array($result_set)) {
        $emp_id = $row['emp_id'];
        $first = $row['emp_first_name'];
        $last = $row['emp_last_name'];
        $email = $row['emp_email'];
        $phone = $row['emp_phone'];



        $obj = new Person($first, $last, $email, $phone);
        $data = $obj->first;
        $first = $obj->unencrypt($data);
        $data = $obj->last;
        $last = $obj->unencrypt($data);

        $person = $first . ' ' . $last . '-' . $emp_id;
      

        $data_arr[] = "\n<option value=\"$person\">$person</option>\n";
    }

    return $data_arr;
}

////////////////////Trim ////////////////////////////////////////////

function db_trim($c_name, $model, $trim = 'empty') {
    require("connection.php");
    if ($trim == 'empty') {
        $blank = "";
        $trim_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $trim_arr[] = "\n<option value=\"$trim\">$trim</option>\n";
        $trim_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $query = "SELECT * ";
    $query .= "FROM trims ";
    $query .= "WHERE trim_model  = '{$model}' ";
    $query .= "AND trim_corp   = '{$c_name}' ";
    $query .= "ORDER BY trim_level ASC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 458: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) { // start while	
        $trim = $row['trim_level'];
        $trim_arr[] = "\n<option value=\"$trim\">$trim</option>\n";
    }
    return $trim_arr;
}

//////////////////////////Drive Type//////////////////////////////////////////////////////
function DriveType($drive = 'empty') {
    $blank = '';
    if ($drive == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$drive\">$drive</option>\n";
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $place = 'Gas Engine';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Diesel Engine';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Hybird';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Electric Motor';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";

    return $data_arr;
}

//////////////////////////Vehicle Type//////////////////////////////////////////////////////
function VehicleType($vType = 'empty') {
    $blank = '';
    if ($vType == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$vType\">$vType</option>\n";
         $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    if ($vType != 'New Vehicle') {
        $place = 'New Vehicle';
        $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
        $place = 'Auction Buy';
        $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
        $place = 'Personal Buy';
        $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
        $place = 'Trade In';
        $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    }

    return $data_arr;
}

//////////////////////////////Drivetrain//////////////////////////////////////////////////
function Drivetrain($drivetrain = 'empty') {
    $blank = '';
    if ($drivetrain == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$drivetrain\">$drivetrain</option>\n";
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }

    $place = 'ALL-WHEEL DRIVE';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'FOUR-WHEEL DRIVE';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'FRONT-WHEEL DRIVE';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'REAR-WHEEL DRIVE';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";


    return $data_arr;
}

/////////////////////////////Body Type///////////////////////////////////////////////////
function BodyType($type = 'empty') {
    $blank = '';
    if ($type == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$type\">$type</option>\n";
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $place = 'Coupe';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Sedan';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Convertible';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Sport Utility Vehicle 4 Door';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Sport Utility Vehicle 2 Door';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Van';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Minivan';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Short Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Short Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Short Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Long Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended CabLong Box ';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Long Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $place = 'Truck Reqular Cab Dually Short Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Short Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Short Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Long Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Long Box ';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Long Box';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";

    $data_arr[] = "\n<option value=\"#\">Chassis Reqular Cab</option>\n";

    $place = 'Truck Reqular Cab Dually Chassis 142';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Chassis 145';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Reqular Cab Dually Chassis 148';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Chassis 160';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Reqular Cab Dually Chassis 164';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Chassis 168';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Reqular Cab Dually Chassis 169';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Chassis 176';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";    
    $place = 'Truck Reqular Cab Dually Chassis 179';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Chassis 192';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Reqular Cab Dually Chassis 193';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Chassis 203';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Reqular Cab Dually Chassis 205';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";

    
    $data_arr[] = "\n<option value=\"#\">Chassis Extended Cab</option>\n";

    $place = 'Truck Extended Cab Dually Chassis 142';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Chassis 145';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Extended Cab Dually Chassis 148';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Chassis 160';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Extended Cab Dually Chassis 164';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Chassis 168';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Extended Cab Dually Chassis 169';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Chassis 176';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";    
    $place = 'Truck Extended Cab Dually Chassis 179';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Chassis 192';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Extended Cab Dually Chassis 193';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Chassis 203';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Extended Cab Dually Chassis 205';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";


    
    $data_arr[] = "\n<option value=\"#\">Chassis Crew Cab</option>\n";

    $place = 'Truck Crew Cab Dually Chassis 142';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis 145';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Crew Cab Dually Chassis 148';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis 160';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Crew Cab Dually Chassis 164';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis 168';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Crew Cab Dually Chassis 169';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis 176';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";    
    $place = 'Truck Crew Cab Dually Chassis 179';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis 192';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";   
    $place = 'Truck Crew Cab Dually Chassis 193';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis 203';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis 205';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    
    
    



    $place = 'Truck Extended Dually Chassis ';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Truck Crew Cab Dually Chassis';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";




    return $data_arr;
}

////////////////////////////Transmisssion////////////////////////////////////////////////
function Transmission($trans) {
    $blank = '';
    if ($trans == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$trans\">$trans</option>\n";
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $place = 'Automatic Transmission';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Manual Transmission';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'CVT Transmission';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";


    return $data_arr;
}

////////////////////////////////////////////////////////////////////////////////
function PhotoUsage($type,$code) {
    $blank = '';
    if ($type == 'empty') {
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$type\">$type</option>\n";
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    if ($code != 'build') {
    $place = 'Both';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Exterior';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Interior';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    $place = 'Not Used';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";
    }else{
       $place = 'Both';
    $data_arr[] = "\n<option value=\"$place\">$place</option>\n";  
    }



    return $data_arr;
}

////////////////////Trim ////////////////////////////////////////////

function customer_trade($employee, $customer) {
    require("connection.php");
    if ($customer == 'empty') {
       
        $customer_arr[] = "\n<option value=\"\"></option>\n";
    } else {
        $customer_arr[] = "\n<option value=\"$customer\">$customer</option>\n";
        $customer_arr[] = "\n<option value=\"\"></option>\n";
    }
    $query = "SELECT * ";
    $query .= "FROM vehicles ";
    $query .= "WHERE veh_emp_id  = '{$employee}' ";
    $query .= "And veh_status  = 'Trade' ";
    $query .= "ORDER BY veh_date_time  ASC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 491: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) { // start while	
        $veh_first = $row['veh_first'];
        $veh_last = $row['veh_last'];
        if(empty($veh_last)){
            continue;
        }
      
        $name = $veh_last . ' ' . $veh_first;

        $customer_arr[] = "\n<option value=\"$name\">$name</option>\n";
    }
    return $customer_arr;
}
///////////////////Build Code////////////////////////////////////////////

function Build_Code($groupNumer,$make, $model,$code) {
    require("connection.php");
    $blank = "";
     $category = 'BUILD CODE';
    if ($code == 'empty') { 
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    } else {
        $data_arr[] = "\n<option value=\"$code\">$code</option>\n";
        $data_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    $query = "SELECT * ";
    $query .= "FROM code_equip ";
    $query .= "WHERE equip_group_num  = '{$groupNumer}' ";
    $query .= "AND equip_make   = '{$make}' ";
    $query .= "AND equip_model   = '{$model}' ";
    $query .= "AND equip_category   = '{$category}' ";
    $query .= "ORDER BY equip_code ASC ";

    $result_set = mysqli_query($con, $query)
            or die('Query failed 535: ' . mysqli_error($con));
    while ($row = mysqli_fetch_array($result_set)) { // start while	
        $equip_code = $row['equip_code'];
        $data_arr[] = "\n<option value=\"$equip_code\">$equip_code</option>\n";
    }
    return $data_arr;
}
