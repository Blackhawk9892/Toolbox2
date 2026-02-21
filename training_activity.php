

<?php

require_once("../includes/functions.php");

require_once("../includes/constants.php");
require_once("../includes/connection.php");
require("../includes/database_rows.php");

//require_once( "../print/fpdf.php" );

if (isset($_GET['type'])) {
    $type = $_GET['type'];
}


$userId = $_COOKIE["userId"];

/*
$emp_arry = Employee($userId);
$name = $emp_arry[0] . ' ' . $emp_arry[1];
$emp_id = $emp_arry[5];
$emp_dealer_id = $emp_arry[6];
*/
$query = "SELECT * ";
$query .= "FROM employee ";
$query .= "WHERE emp_id = '{$userId}' ";


$result_set = mysqli_query($con, $query)
        or die('Query failed vehicles: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);
$emp_dealer_group = $row['emp_dealer_group'];

$query = "SELECT * ";
$query .= "FROM dealer_group ";
$query .= "WHERE dg_id  = '{$emp_dealer_group}' ";


$result_set = mysqli_query($con, $query)
        or die('Query failed vehicles: ' . mysqli_error($con));
       $row = mysqli_fetch_array($result_set);
       $dg_name = $row['dg_name'];

echo "<h1>$dg_name</h1>";

$query = "SELECT * ";
$query .= "FROM company ";
$query .= "WHERE comp_group    = '{$emp_dealer_group}' ";
$query .= "ORDER BY  comp_name";

$result_set = mysqli_query($con, $query)
        or die('Query failed vehicles 87: ' . mysqli_error($con));
while ($row = mysqli_fetch_array($result_set)) {
       

    $comp_id = $row['comp_id'];
    $comp_name = $row['comp_name'];
    $comp_id_array[] = $comp_id;
    $comp_name_array[] = $comp_name;
}
print_r($comp_id_array);
echo "<br>";
print_r($comp_name_array);
$countCompany = 0;
/*
foreach ($comp_id_array as $value) {
    $companyName = $comp_name_array[$countCompany];
$position = 'Manager';
$query = "SELECT * ";
$query .= "FROM employee ";
$query .= "WHERE emp_dealer_group = '{$emp_dealer_group}' ";
$query .= "AND emp_position = '{$position}'  ";
$query .= "ORDER BY  emp_last_name, emp_first_name";

$result_set = mysqli_query($con, $query)
        or die('Query failed vehicles 87: ' . mysqli_error($con));
while ($row = mysqli_fetch_array($result_set)) {
       

    $emp_id = $row['emp_id'];
    $emp_first_name = $row['emp_first_name'];
    $emp_last_name = $row['emp_last_name'];
    $managers_name_array[] = $emp_first_name . ' ' . $emp_last_name;
  }
}
$position = 'Sales';
$query = "SELECT * ";
$query .= "FROM employee ";
$query .= "WHERE emp_dealer_group = '{$emp_dealer_group}' ";
$query .= "AND emp_position = '{$position}' ";
$query .= "ORDER BY  emp_last_name, emp_first_name";

$result_set = mysqli_query($con, $query)
        or die('Query failed vehicles 87: ' . mysqli_error($con));
while ($row = mysqli_fetch_array($result_set)) {
       

    $emp_id = $row['emp_id'];
    $emp_first_name = $row['emp_first_name'];
    $emp_last_name = $row['emp_last_name'];
    $sales_name_array[] = $emp_first_name . ' ' . $emp_last_name;
    $emp_id_array[] = $emp_id;
}
  */  
$points = 0;
$count = 0;
$saveSalesnumber = 0;
$saveDate = '';
$counttimes = 0;
$countPoints = 0;
//print_r($emp_id_array);

 foreach ($emp_id_array as $value) {
            $companyName = $comp_name_array[$count];
         echo "<h2>$companyName</h2>";

          $query = "SELECT * ";
          $query .= "FROM customer_data ";
          $query .= "WHERE cust_company = '{$value}' ";
          $query .= "ORDER BY  cust_salesperson_name";
         
           $result_set = mysqli_query($con, $query)
            or die('Query failed vehicles 87: ' . mysqli_error($con));
          while ($row = mysqli_fetch_array($result_set)) {

             $cust_salesperson_num = $row['cust_salesperson_num'];
                if($saveSalesnumber != $cust_salesperson_num){
                    $saveSalesnumber = $cust_salesperson_num;
                     $query1 = "SELECT * ";
                     $query1 .= "FROM employee ";
                     $query1 .= "WHERE emp_id = '{$cust_salesperson_num}' ";

                      $result_set = mysqli_query($con, $query)
                      or die('Query failed vehicles 87: ' . mysqli_error($con));
                      $row = mysqli_fetch_array($result_set);
                        $emp_assigned_man_name = $row['emp_assigned_man_name'];
                        echo "<h3>$emp_assigned_man_name</h3>";
                }
            $cust_points = $row['cust_points'];
            $cust_date = $row['cust_date'];
            $d = strtotime($cust_date);
             $fileDate = date("Y-m-d", $d);
             if($saveDate == $fileDate){
               
                $counttimes++;
             }else{
                $points = $points + $cust_points;
                $countPoints++;
             }

            
            
            
           
          $count++;
 }
 echo "Salesperson: " . $cust_salesperson_name . " Points: " . $points . " Number of days that count:" . $countPoints ." Times that do not count: " . $counttimes ."<br>";
          }$counttimes

/*

$pdf = new FPDF('P', 'mm', 'A4');


$pdf->AddPage();
$pdf->SetTextColor($headerColour[0], $headerColour[1], $headerColour[2]);
$pdf->SetFont('Arial', '', 17);
$pdf->Cell(0, 15, $reportName, 0, 0, 'C');


 // Create the table
 
$pdf->SetDrawColor($tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2]);
$pdf->Ln(12);

// Create the table header row
$pdf->SetFont('Arial', 'B', 12);

// "PRODUCT" cell
$pdf->SetTextColor($tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2]);
$pdf->SetFillColor($tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2]);
$pdf->Cell(36, 8, " Status", 1, 0, 'L', true);

// Remaining header cells
$pdf->SetTextColor($tableHeaderTopTextColour[0], $tableHeaderTopTextColour[1], $tableHeaderTopTextColour[2]);
$pdf->SetFillColor($tableHeaderTopFillColour[0], $tableHeaderTopFillColour[1], $tableHeaderTopFillColour[2]);

for ($i = 0; $i < count($columnLabels); $i++) {
    $pdf->Cell(36, 8, $columnLabels[$i], 1, 0, 'C', true);
}

$pdf->Ln(8);

// Create the table data rows

$fill = false;
$row = 0;
$x = 0;
foreach ($data as $dataRow) {

    // Create the left header cell
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor($tableHeaderLeftTextColour[0], $tableHeaderLeftTextColour[1], $tableHeaderLeftTextColour[2]);
    $pdf->SetFillColor($tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2]);
    $pdf->Cell(36, 8, " " . $rowLabels[$row], 1, 0, 'L', $fill);

    // Create the data cells
    $pdf->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
    $pdf->SetFillColor($tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2]);
    $pdf->SetFont('Arial', '', 10);

   
    //  Create the table
     
    for ($i = 0; $i < count($columnLabels); $i++) {
        $pdf->Cell(36, 8, $dataRow[$i], 1, 0, 'C', $fill);
    }
    $x++;
    if ($x == 29) {
        $x = 0;
        $pdf->AddPage();
        $pdf->SetTextColor($headerColour[0], $headerColour[1], $headerColour[2]);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 25, $reportName, 0, 0, 'C');



        $pdf->SetDrawColor($tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2]);
        $pdf->Ln(12);

// Create the table header row
        $pdf->SetFont('Arial', 'B', 12);

// "PRODUCT" cell
        $pdf->SetTextColor($tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2]);
        $pdf->SetFillColor($tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2]);
        $pdf->Cell(46, 8, " Status", 1, 0, 'L', true);

// Remaining header cells
        $pdf->SetTextColor($tableHeaderTopTextColour[0], $tableHeaderTopTextColour[1], $tableHeaderTopTextColour[2]);
        $pdf->SetFillColor($tableHeaderTopFillColour[0], $tableHeaderTopFillColour[1], $tableHeaderTopFillColour[2]);
        for ($i = 0; $i < count($columnLabels); $i++) {
            $pdf->Cell(36, 8, $columnLabels[$i], 1, 0, 'C', true);
        }

//$pdf->Ln( 1 );
    }

    $row++;
    //$fill = !$fill;
    $pdf->Ln(8);
}


 // Create the chart


// Compute the X scale
$xScale = count($rowLabels) / ( $chartWidth - 40 );

// Compute the Y scale

$maxTotal = 0;
/*
  foreach ($data as $dataRow) {
  $totalSales = 0;
  foreach ($dataRow as $dataCell)
  $totalSales += $dataCell;
  $maxTotal = ( $totalSales > $maxTotal ) ? $totalSales : $maxTotal;
  } */

/* * *
  Serve the PDF
 * * */

//$pdf->Output("report.pdf", "D");
?>