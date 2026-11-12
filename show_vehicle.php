<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Customer Interduction</title>
  <style>
            body  {
                background-image: url("photos/black backround");
                height: 900px;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
            }
            <style>
.center {
  margin: auto;
 width: 10%;
  border: 1px solid #73AD21;

}
        </style>
</head>
<body>

<?php
require("includes/constants.php");
require("includes/connection.php");


$_SESSION['video'] = "home";

require("includes/database_rows.php");

if(empty($_COOKIE["userId"]) or (!isset($_COOKIE["userId"]))){
  $_SESSION['message'] = "Not a valid employee";

 header("Location: index.php");
  exit;
}

require("toolbar_sales.php");

if(isset($_SESSION['message'])){
  $message = $_SESSION['message'];
  echo "<h2>$message</h2>";
  unset($_SESSION['message']);
}




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
          if(isset($_GET["stock"])){
             $_SESSION["stock"] = $_GET["stock"];
            }

          if(isset($_SESSION["stock"])){
            $stock = $_SESSION["stock"];
            }

$query = "SELECT * ";
$query .= "FROM vehicle ";
$query .= "WHERE vehicle_stock_num   = '{$stock}' ";


$result_set = mysqli_query($con, $query)
        or die('Query failed emp: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);

$vehicle_year = $row['vehicle_year'];
$vehicle_make = $row['vehicle_make'];
$vehicle_model = $row['vehicle_model'];
$vehicle_trim = $row['vehicle_trim'];

$vehicle_description = $vehicle_year . ' ' . $vehicle_make . ' ' . $vehicle_model . ' ' . $vehicle_trim ;

            echo " <center>  \n";
            echo "          <h1>Show Vehicle Stock Number $stock</h1>\n";
            echo "</center>\n";

            echo "<h1>Vehicle Description:</h1>\n";
            echo "<h2>$vehicle_description</h2>\n";

            if(isset($row['vehicle_miles'])){
              $vehicle_miles = $row['vehicle_miles'];
              echo "<h3>Vehicle Miles: $vehicle_miles</h3>\n";
            }

             if(isset($row['vehicle_engine'])){
              $vehicle_engine = $row['vehicle_engine'];
              echo "<h3>Vehicle Engine: $vehicle_engine</h3>\n";
            }

             if(isset($row['vehicle_drive'])){
              $vehicle_drive = $row['vehicle_drive'];
              echo "<h3>Vehicle drivetrain: $vehicle_drive</h3>\n";
            }

             if(isset($row['vehicle_transmission'])){
              $vehicle_transmission = $row['vehicle_transmission'];
              echo "<h3>Vehicle Transmission: $vehicle_transmission</h3>\n";
            }

             if(isset($row['vehicle_color'])){
              $vehicle_color = $row['vehicle_color'];
              echo "<h3>Vehicle Color: $vehicle_color</h3>\n";
            }

           

             if(isset($row['vehicle_interior'])){
              $vehicle_interior = $row['vehicle_interior'];
              echo "<h3>Interior: $vehicle_interior</h3>\n";
            }
echo "<h2> Vehicle Options:</h2>\n";
            for ($x = 1; $x <= 16; $x++) {
              $vOption = "vehicle_option" . $x ;
                      
              if(empty($row[$vOption])){
                continue;
              }else{
                $vehicle_option = $row[$vOption];
                echo "<h3> $vehicle_option</h3>\n";
              }

}

       $query = "SELECT * ";
$query .= "FROM specifications ";
$query .= "WHERE spec_year   = '{$vehicle_year}' ";
$query .= "AND spec_make   = '{$vehicle_make}' ";
$query .= "AND spec_model   = '{$vehicle_model}' ";
$query .= "AND spec_trim   = '{$vehicle_trim}' ";


$result_set = mysqli_query($con, $query)
        or die('Query failed emp: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);
if(isset($row)){
 
////////////////////////////////////////Awards Section
   $json = $row['spec_awards'] ?? '';

echo '<form action="awards.php"
            method="POST"
            target="_blank">';

echo '<input type="hidden"
             name="awards_json"
             value="' .
             htmlspecialchars(
                 $json,
                 ENT_QUOTES,
                 'UTF-8'
             ) .
             '">';

echo '<button type="submit">
        View Awards and Safety Ratings
      </button>';

echo '</form>';
/////////////////////////////////////////Specifications Section
$vehicle_name = trim(
    $vehicle_year . ' ' .
    $vehicle_make . ' ' .
    $vehicle_model . ' ' .
    $vehicle_trim
);

// Get specifications from database
$json = $row['spec_specif'] ?? '';

// Only display button if specifications exist
if (!empty($json)) {

    // Start form
    echo '<form action="specifications.php"
                method="POST"
                target="_blank">';

    // Send vehicle name
    echo '<input type="hidden"
                 name="vehicle_name"
                 value="' .
                 htmlspecialchars(
                     $vehicle_name,
                     ENT_QUOTES,
                     'UTF-8'
                 ) .
                 '">';

    // Send specifications JSON
    echo '<input type="hidden"
                 name="specifications_json"
                 value="' .
                 htmlspecialchars(
                     $json,
                     ENT_QUOTES,
                     'UTF-8'
                 ) .
                 '">';

    // Display button
    echo '<button type="submit">
            View Vehicle Specifications
          </button>';

    // End form
    echo '</form>';
}
/////////////////////////////////////////////////

/////////////////////////////////////////////////
// TOP 5 COMPETITION SECTION
/////////////////////////////////////////////////

// Build selected vehicle name
$vehicle_name = trim(
    $vehicle_year . ' ' .
    $vehicle_make . ' ' .
    $vehicle_model . ' ' .
    $vehicle_trim
);

// Get competition information from database
$json = $row['spec_competition'] ?? '';

// Display button if competition information exists
if (!empty($json)) {

    echo '<form action="top_5_copetition.php"
                method="POST"
                target="_blank">';

    // Send selected vehicle name
    echo '<input type="hidden"
                 name="vehicle_name"
                 value="' .
                 htmlspecialchars(
                     $vehicle_name,
                     ENT_QUOTES,
                     'UTF-8'
                 ) .
                 '">';

    // Send competition JSON
    echo '<input type="hidden"
                 name="competition_json"
                 value="' .
                 htmlspecialchars(
                     $json,
                     ENT_QUOTES,
                     'UTF-8'
                 ) .
                 '">';

    // Display button
    echo '<button type="submit">
            View Top 5 Competitors
          </button>';

    echo '</form>';
}

/////////////////////////////////////////////////
// END TOP 5 COMPETITION SECTION
/////////////////////////////////////////////////

}else{
 $url = "vehicle_research.php?" .
                          "year="  . urlencode($vehicle_year) .
                          "&make=" . urlencode($vehicle_make) .
                          "&model=" . urlencode($vehicle_model) .
                          "&trim=" . urlencode($vehicle_trim);

                       echo'<a href="' . $url . '" target="_blank"><h2>Research Vehicle</h2></a>';

}

  ?>


</div>
</body>