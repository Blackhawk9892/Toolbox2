<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
// Start the session
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <title>Equipment Codes  </title>

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
        

////////////Set New or Used///////////////////////////////////////

    



    
        $_POST['make'] = 'USED';
        $_POST['model'] = 'USED';
        $_POST['photo'] = 'Both';
       
       
    


    if (isset($_POST['make'])) {
        $_SESSION['make'] = $_POST['make'];
    }
    if (isset($_POST['model'])) {
        $_SESSION['model'] = $_POST['model'];
    }

    ////////////////////Vehicle Selected///////////////////////////////////

    if (isset($_GET['ID'])) {
        $id = $_GET['ID'];
        $_SESSION['id'] = $id;
        $query = "SELECT * ";
        $query .= "FROM code_equip ";
        $query .= "WHERE equip_index    = '{$id}' ";

        $result_set = mysqli_query($con, $query)
                or die('Query failed emp: ' . mysqli_error($con));
        $row = mysqli_fetch_array($result_set);


        $equip_category = $row['equip_category'];
        $_SESSION['cate'] = $equip_category;
        $_POST['cate'] = $equip_category;

        $equip_list = $row['equip_list'];
        $_SESSION['equip'] = $equip_list;
        $_POST['equip'] = $equip_list;

        $equip_code = $row['equip_code'];
        $_SESSION['code'] = $equip_code;
        $_POST['code'] = $equip_list;

        $equip_make = $row['equip_make'];
        $_SESSION['make'] = $equip_make;
        $_POST['make'] = $equip_make;

        $equip_model = $row['equip_model'];
        $_SESSION['model'] = $equip_model;
        $_POST['model'] = $equip_model;

        $equip_code = $row['equip_code'];
        $_SESSION['code'] = $equip_code;
        $_POST['code'] = $equip_code;
    }


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

////////////////////Load Pull Downs///////////////////////////////////////////
/*
    if (isset($_POST['make'])) {
        $make = $_POST['make'];
        $make_arr[] = db_make($dealer_id, $make);
    } else {
        $make = 'empty';
        $make_arr[] = db_make($dealer_id, $make);
    }

    if (empty($_POST['model'])) {
        $model = 'empty';
        $model_arr[] = db_model($dealer_group, $make, $model);
    } else {
        $model = $_POST['model'];
        $model_arr[] = db_model($dealer_group, $make, $model);
    }
    $code = $_SESSION['code'];
    if (empty($_POST['photo'])) {
        $photo = 'empty';
        $photo_arr[] = PhotoUsage($photo, $code);
    } else {
        $photo = $_POST['photo'];
        $photo_arr[] = PhotoUsage($photo, $code);
    }


    if (isset($_POST['equip'])) {
        if (isset($_POST['equipment'])) {
            $equipment = $_POST['equipment'];
            $_SESSION['equipment'] = $equipment;
            $_POST['cate'] = $equipment;
            $_SESSION['first'] = 1;
        }
    }
*/
///////////////////////Add A Feature/////////////////////////////////////////// 
///////////////////////Check Data//////////////////////////////////////////////
    if (isset($_POST['submit'])) {
        $errors = array();


        $equip = strtoupper($_POST['equip']);
       
        $cate = strtoupper($_POST['cate']);
     //   $code = strtoupper($_POST['code']);
     //   $make = strtoupper($_POST['make']);
      //  $model = strtoupper($_POST['model']);
      //  $photo = strtoupper($_POST['photo']);

        $required_fields = array('equip', 'cate');

        $count = 0;
        foreach ($required_fields as $fieldname) {

            if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                switch ($required_fields[$count]) {
                    case "equip":
                      $field = 'Description';
                      break;
                    case "cate":
                        $field = 'Category';
                      break;
                   
                    default:
                     
                  }
                $errors[] = 'This field is mandatory:  ' . $field;
            }
            $count++;
        } // end foreach

        $checkEquip = Check_Data($equip);
        if($checkEquip == 'pass'){ 
            
        $query = "SELECT * ";
        $query .= "FROM code_equip ";
        $query .= "WHERE equip_group_num = '{$dealer_group}' ";
        $query .= "AND equip_make  = '{$make}' ";
        $query .= "AND equip_model  = '{$model}' ";
        $query .= "AND equip_list = '{$equip}' ";


        $result_set = mysqli_query($con, $query)
                or die('Query failed179: ' . mysqli_error($con));
     
        $row = mysqli_fetch_array($result_set);



        if (isset($row['equip_corp'])) {
            $equip_corp = $row['equip_corp'];
        }
        if (isset($row['equip_list'])) {
            $equip_list = $row['equip_list'];
        }

        if (!empty($equip_list)) {
            $errors[] = 'Equipment: ' . $equip_list . ',  in dealer group ' . $equip_corp . ' has already been used. ';
        }
    }else{
        $errors[] = "Equipment Code has one or more of the following in it: !@#$%^&*';=+?><" . '"`~';
    }
    $checkCode = Check_Data($code);
    if($checkCode == 'pass'){ 
        $query = "SELECT * ";
        $query .= "FROM code_equip ";
        $query .= "WHERE equip_group_num = '{$dealer_group}' ";
        $query .= "AND equip_make  = '{$make}' ";
        $query .= "AND equip_model  = '{$model}' ";
        $query .= "AND equip_code = '{$code}' ";

        $result_set = mysqli_query($con, $query)
                or die('Query failed202: ' . mysql_error());
        $row = mysqli_fetch_array($result_set);

        if (isset($row['equip_code'])) {
            $equip_code = $row['equip_code'];
        }

        if (!empty($equip_code)) {
            $errors[] = 'Equipment Code: ' . $equip_code . ',  in dealer group ' . $equip_corp . ' has already been used. For Make ' . $make . ' and ' . 'Model ' . $model;
        }
    }else{
        $errors[] = "Description has one or more of the following in it: !@#$%^&*';=+?><" . '"`~';
    }
 
        if (!empty($errors)) {
            foreach ($errors as $value) {
                echo "<div class=\"errors\">$value</div>";
            } // end foreach
        } else {
//////////////////////////////////Write Data////////////////////////////////////


$code = Remove_Whites($code);

$equip = Remove_Whites($equip);


        
            $sql = "INSERT INTO code_equip(equip_group_num,equip_make,equip_model ,equip_code ,equip_corp, equip_list, equip_category,equip_type) 
            VALUES('$dealer_group','USED','USED','$code','$dealer_name','$equip','$cate','$photo')";

            if (!mysqli_query($con, $sql)) {
                die('Error input vehicles: ' . mysqli_error($con));
            }
           
            $_SESSION['message'] = "Record has been ADD successfully to: " . $equip . ' to category: ' . $cate;

       
            $_SESSION['equip'] = '';
            $_POST['equip'] = '';
            $_SESSION['cate'] = '';
            $_POST['cate'] = '';
            $_SESSION['id'] = '';
           


            
           header("Location: equipment_setup.php");
            exit;
        }
    }
///////////////////////////////Update A Feature/////////////////////////////////

    if (isset($_POST['update'])) {
      
        $id = $_SESSION['id'];

        $equip = strtoupper($_POST['equip']);
        $cate = strtoupper($_POST['cate']);
      
        // $_POST['price'] = 0;
        // $price = strtoupper($_POST['price']);
        $required_fields = array('equip', 'cate');

        $count = 0;
        foreach ($required_fields as $fieldname) {

            if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                switch ($required_fields[$count]) {
                    case "equip":
                      $field = 'Description';
                      break;
                    case "cate":
                        $field = 'Category';
                      break;
                  
                    default:
                     
                  }
                $errors[] = 'This field is mandatory:  ' . $field;
            }
            $count++;
        } // end foreach
        $checkCode = Check_Data($code);
         if($checkCode == 'fail'){
        $errors[] = "Equipment Code has one or more of the following in it: !@#$%^&*()';:=+-_?/>.,< " . '"`~';
    }

    $checkEquip = Check_Data($equip);
         if($checkEquip == 'fail'){
        $errors[] = "Description has one or more of the following in it: !@#$%^&*()';:=+-_?/>.,< " . '"`~';
    }
        
        if (!empty($errors)) {
            foreach ($errors as $value) {
                echo "<div class=\"errors\">$value</div>";
            } // end foreach
        } else {

            $code = Remove_Whites($code);

            $equip = Remove_Whites($equip);

        mysqli_query($con, "UPDATE code_equip SET equip_list = '$equip'
                                         WHERE equip_index = '$id' ");


        mysqli_query($con, "UPDATE code_equip SET equip_category = '$cate'
                                         WHERE equip_index = '$id' ");
    
         if($cate == 'BUILD CODE'){
             $type = ' Build Code: ';
             $location = '';
         }else{
             $type = ' Code: ';
             $location = ' in Category: ' . $cate;
         }
        $_SESSION['message'] = "Record has been update successfully to " . $type . $location ;
        echo "<div class=\"errors\">$value</div>";

        $_SESSION['equip'] = '';
        $_POST['equip'] = '';
        $_SESSION['cate'] = '';
        $_POST['cate'] = '';
        $_SESSION['id'] = '';
      


        
        header("Location: equipment_setup.php");
        exit;

    }
    }
    ///////////////////////////Delete A Feature/////////////////////////////////

    if (isset($_POST['delete'])) {
        $id = $_SESSION['id'];
        $sql = "DELETE FROM code_equip WHERE equip_index='$id'";

        if ($con->query($sql) === TRUE) {
            $_SESSION['message'] = "Record deleted successfully ";
            echo "<div class=\"errors\">$value</div>";
            $_SESSION['equip'] = '';
            $_POST['equip'] = '';
            $_SESSION['cate'] = '';
            $_POST['cate'] = '';
            $_SESSION['id'] = '';
            $_POST['code'] = '';
            $_SESSION['code'] = '';

         
            header("Location: equipment_setup.php");
            exit;
        }
    }
    ////////////////////////////Clear All Fields////////////////////////////////

    if (isset($_POST['clear'])) {
        $_SESSION['equip'] = '';
        $_POST['equip'] = '';
        $_SESSION['cate'] = '';
        $_POST['cate'] = '';
        $_SESSION['id'] = '';
        $_POST['code'] = '';
        $_SESSION['code'] = '';
        $_POST['model'] = '';
        $_SESSION['model'] = '';
        $_POST['make'] = '';
        $_SESSION['make'] = '';
     
        header("Location: equipment_setup.php");
    }

///////////////////////Pull Down For Category///////////////////////////////////  
    $blank = '';
    if (isset($_POST['cate'])) {
        $cate = $_POST['cate'];
        $cate_arr[] = "\n<option value=\"$cate\">$cate</option>\n";
    } else {
        $cate = $blank;
        $cate_arr[] = "\n<option value=\"$blank\">$blank</option>\n";
    }
    if ($cate != 'BUILD CODE') {
        $fill = 'COMFORT';
        $cate_arr[] = "\n<option value=\"$fill\">$fill</option>\n";
        $fill = 'PERFORMANCE';
        $cate_arr[] = "\n<option value=\"$fill\">$fill</option>\n";
        $fill = 'SAFETY';
        $cate_arr[] = "\n<option value=\"$fill\">$fill</option>\n";
        $fill = 'STYLE';
        $cate_arr[] = "\n<option value=\"$fill\">$fill</option>\n";
    } else {
        $fill = 'BUILD CODE';
        $cate_arr[] = "\n<option value=\"$fill\">$fill</option>\n";
    }

////////////////////////Make Featur Rows////////////////////////////////////////
    $page = "equipment_setup.php";
    

    if ($cate == 'BUILD CODE') {
        $bid_satus = 'black';
        $equip_arr[] = "\n<div id=\"$bid_satus\"><a >"
                . "</a>"
                . "<table >"
                . "<tr><td  width = 200px><a style=\"color:yellow;\" href=\"#\">Category</a></td>"
                . "<td  width = 300px><a style=\"color:yellow;\" href=\"#\">Vehicle</a></td>"
                . "<td  width = 100px><a style=\"color:yellow;\" href=\"#\">Build Code</a></td>"
                . "<td  width = 700px><a style=\"color:yellow;\" href=\"#\">Description</a></td>"
                . "</table></div>\n";


        if (isset($_POST['make']) && isset($_POST['model'])) {
            $make = $_POST['make'];
            $model = $_POST['model'];
            $cate = $cate;

            $query = "SELECT * ";
            $query .= "FROM code_equip ";
            $query .= "WHERE equip_group_num  = '{$dealer_group}' ";
            $query .= "AND equip_make  = '{$make}' ";
            $query .= "AND equip_model  = '{$model}' ";
            $query .= "AND equip_category  = '{$cate}' ";
            $query .= "ORDER BY equip_list  ASC ";



            $result_set = mysqli_query($con, $query)
                    or die('Query failed408: ' . mysqli_error($con));

            while ($row = mysqli_fetch_array($result_set)) {
                $equip_index = $row['equip_index'];
                $equip_list = $row['equip_list'];
                $equip_category = $row['equip_category'];
                $equip_code = $row['equip_code'];
                $equip_make = $row['equip_make'];
                $equip_model = $row['equip_model'];
                $vehicle = $equip_make . ' ' . $equip_model;
               
                $bid_satus = 'Offer';
                $equip_arr[] = "\n<div id=\"$bid_satus\"><a href=$page?ID=$equip_index>"
                        . "<table >"
                        . "<tr><td style=\"color:black;\" width = 200px>$equip_category</td>"
                        . "<td style=\"color:black;\" width = 300px>$vehicle</td>"
                        . "<td style=\"color:black;\" width = 100px>$equip_code</td>"
                        . "<td style=\"color:black;\" width = 700px>$equip_list</td>"
                        . "</table></div>\n";
            }
        }
    } else {
        $equip_arr[] = "<div class=\"container-fluid\">\n";
        $equip_arr[] = "\n";
        $equip_arr[] = "<div class=\"row\">\n";
        $equip_arr[] = "<div class=\"col-sm-2 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
        $equip_arr[] = "COMFORT\n";
        $equip_arr[] = "</div>\n";
        $equip_arr[] = "<div class=\"col-sm-2 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
        $equip_arr[] = "PERFORMANCE\n";
        $equip_arr[] = "</div>\n";
        $equip_arr[] = "<div class=\"col-sm-2 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
        $equip_arr[] = "SAFETY\n";
        $equip_arr[] = "</div>\n";
        $equip_arr[] = "<div class=\"col-sm-2 col-md-3\" style=\"background-color:black;color:yellow;\">\n";
        $equip_arr[] = "STYLE\n";
        $equip_arr[] = "</div>\n";
        $equip_arr[] = "</div>\n";
        $equip_arr[] = "</div>\n";



        if (isset($_SESSION['make'])) {
            if ($_SESSION['make'] == 'empty') {
                unset($_SESSION['make']);
            }
        }

        if (isset($_SESSION['model'])) {
            if ($_SESSION['model'] == 'empty') {
                unset($_SESSION['model']);
            }
        }

        $comfort = 0;
        $performance = 0;
        $safety = 0;
        $style = 0;

        if (isset($_SESSION['make']) && isset($_SESSION['model'])) {
            $make = $_SESSION['make'];
            $model = $_SESSION['model'];

            $query = "SELECT * ";
            $query .= "FROM code_equip ";
            $query .= "WHERE equip_group_num  = '{$dealer_group}' ";
            $query .= "AND equip_make  = '{$make}' ";
            $query .= "AND equip_model  = '{$model}' ";
            $query .= "ORDER BY equip_list  ASC ";

            // $query .= "ORDER BY equip_category, equip_list  ASC ";

            $result_set = mysqli_query($con, $query)
                    or die('Query failed408: ' . mysqli_error($con));

            while ($row = mysqli_fetch_array($result_set)) {
                $equip_index = $row['equip_index'];
                $equip_list = $row['equip_list'];
                $equip_category = $row['equip_category'];
                $equip_code = $row['equip_code'];
                    
                if($equip_category == 'BUILD CODE'){
                    continue;
                }

                if (empty($equip_index)) {
                    continue;
                }


                /* if ($equip_category != $save_equip_category) {



                  $_SESSION['firstl'] = 'set';
                  $save_equip_category = $equip_category;
                  } */
                switch ($equip_category) {
                    case "COMFORT":
                        $comfort = 1;
                        $equipComfort_arr[] = " <h4><a href=$page?ID=$equip_index>$equip_list</a></h4>";
                        break;
                    case "PERFORMANCE":
                        $performance = 1;
                        $equipPerformance_arr[] = " <h4><a href=$page?ID=$equip_index>$equip_list</a></h4>";
                        break;
                    case "SAFETY":
                        $safety = 1;
                        $equipSafety_arr[] = " <h4><a href=$page?ID=$equip_index>$equip_list</a></h4>";
                        break;
                    case "STYLE":
                        $style = 1;
                        $equipStyle_arr[] = " <h4><a href=$page?ID=$equip_index>$equip_list</a></h4>";
                        break;
                    default:
                        echo "Category was not found for: " . $equip_list . "!";
                }
            }
            $c = 'None';
            if ($comfort == 0) {

                $equipComfort_arr[] = " <h4><a href=#?ID=$c>$c</a></h4>";
            }
            $comfort = count($equipComfort_arr);
            if ($performance == 0) {

                $equipPerformance_arr[] = " <h4><a href=#?ID=$c>$c</a></h4>";
            }
            $performance = count($equipPerformance_arr);
            if ($safety == 0) {

                $equipSafety_arr[] = " <h4><a href=#?ID=$c>$c</a></h4>";
            }
            $safety = count($equipSafety_arr);
            if ($style == 0) {

                $equipStyle_arr[] = " <h4><a href=#?ID=$c>$c</a></h4>";
            }
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
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $equipComfort_arr[$x];
                    $equip_arr[] = "</div>\n";
                } else {
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $blank;
                    $equip_arr[] = "</div>\n";
                }
                if ($x <= $performance) {
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $equipPerformance_arr[$x];
                    $equip_arr[] = "</div>\n";
                } else {
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $blank;
                    $equip_arr[] = "</div>\n";
                }
                if ($x <= $safety) {
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $equipSafety_arr[$x];
                    $equip_arr[] = "</div>\n";
                } else {
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $blank;
                    $equip_arr[] = "</div>\n";
                }
                if ($x <= $style) {
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $equipStyle_arr[$x];
                    $equip_arr[] = "</div>\n";
                } else {
                    $equip_arr[] = "<div class=\"col-sm-3 col-md-3\" style=\"background-color:yellow;color:black;\">\n";
                    $equip_arr[] = $blank;
                    $equip_arr[] = "</div>\n";
                }
                $equip_arr[] = "</div>\n";
                $equip_arr[] = "</div>\n";
            }
        }
    }
    /*
    if ($_SESSION['code'] == 'used') {
        echo "<center><h3>Equipment Codes Used</h3></center>";
    }

    if ($_SESSION['code'] == 'new') {
        echo "<center><h3>Equipment Codes New</h3></center>";
    }

    if ($_SESSION['code'] == 'build') {
        echo "<center><h3>Build Codes For New Vehicles</h3></center>";
        if(isset($_POST['make']) and isset($_POST['model'])){
         $make = strtoupper($_POST['make']);
         $model = strtoupper($_POST['model']);
         if(empty($_POST['build'])){
             $build = 'empty';
         }else{
            $build = $_POST['build']; 
         }
       $build_arr = Build_Code($dealer_group,$make, $model,$build);
        }
        
        
    }
    require_once("../toolbar/toolbar_sales.php");
*/
    ?>



    <br />
    <br />
    <form action="equipment_setup.php" method="post">




        <div style="padding-left: 37px;">
            <table>	
<?php


print( "<tr><td>Category:</td><td>\n");
print( "<select name=\"cate\" id=\"cate\">");
print_r($cate_arr);
print( "</select>");

?>

               


                <tr><td>Description:</td><td>
                        <textarea rows="3" cols="50" name="equip" wrap="wrap " >
<?php if (isset($_POST['equip'])) echo $_POST['equip'] ?>
                        </textarea>
<?php


?>

                         
                        <br>
            </table>
            </br>
            <input type="submit" name="submit" value="Add a feature"/>
            <input type="submit" name="update" value="Update"/>
            <input type="submit" name="delete" value="Delete"/>
            <input type="submit" name="clear" value="Clear all fields"/>
        </div> 

<?php
$result = count($equip_arr);
$count = 0;
while ($count <= $result) {
    $load = @$equip_arr[$count];
    echo $load;

    $count++;
}
?>			

    </div>


</body>
</html>
