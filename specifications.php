
<?php
/*
 * Program: specifications.php
 *
 * Receives vehicle information from show_vehicle.php
 * and displays vehicle specifications.
 *
 * Compatible with PHP 7.3, 8.2, and 8.3
 */

// Show errors during testing
error_reporting(E_ALL);
ini_set('display_errors', '1');

// -----------------------------------------------
// RECEIVE INFORMATION FROM show_vehicle.php
// -----------------------------------------------

$vehicle_name = $_POST['vehicle_name'] ?? '';
$json = $_POST['specifications_json'] ?? '';

// Check for missing information
if (trim($json) === '') {
    die('Error: No vehicle specifications received.');
}

// -----------------------------------------------
// DECODE JSON
// -----------------------------------------------

$data = json_decode($json, true);

// Check for JSON errors
if (json_last_error() !== JSON_ERROR_NONE) {
    die(
        'JSON Error: ' .
        htmlspecialchars(
            json_last_error_msg(),
            ENT_QUOTES,
            'UTF-8'
        )
    );
}

// Check decoded data
if (!is_array($data)) {
    die('Error: Invalid specifications information.');
}

// -----------------------------------------------
// FUNCTIONS
// -----------------------------------------------

// Safely display text
function safe($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Convert database field names into readable labels
function formatLabel($label) {

    $label = str_replace(
        array('_', '-'),
        ' ',
        (string)$label
    );

    return ucwords($label);
}

// Check whether array is a list
function isListArray($array) {

    if (!is_array($array)) {
        return false;
    }

    if (empty($array)) {
        return true;
    }

    return array_keys($array) ===
           range(0, count($array) - 1);
}

// Display specifications recursively
function displaySpecifications($data) {

    if (!is_array($data)) {
        echo '<p>' . safe($data) . '</p>';
        return;
    }

    foreach ($data as $key => $value) {

        // Nested sections
        if (is_array($value)) {

            // If array is a list
            if (isListArray($value)) {

                echo '<div class="spec-row">';

                echo '<div class="spec-label">' .
                     safe(formatLabel($key)) .
                     '</div>';

                echo '<div class="spec-value">';

                echo '<ul>';

                foreach ($value as $item) {

                    if (is_array($item)) {

                        echo '<li>';
                        displaySpecifications($item);
                        echo '</li>';

                    } else {

                        echo '<li>' .
                             safe($item) .
                             '</li>';
                    }
                }

                echo '</ul>';

                echo '</div>';
                echo '</div>';

            } else {

                // Section heading
                echo '<h2>' .
                     safe(formatLabel($key)) .
                     '</h2>';

                echo '<div class="spec-section">';

                displaySpecifications($value);

                echo '</div>';
            }

        } else {

            // Normal specification
            echo '<div class="spec-row">';

            echo '<div class="spec-label">' .
                 safe(formatLabel($key)) .
                 '</div>';

            echo '<div class="spec-value">' .
                 safe($value) .
                 '</div>';

            echo '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Vehicle Specifications</title>

<style>

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 20px;
    color: #333;
}

.container {
    max-width: 1100px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    color: #17365d;
    margin-bottom: 10px;
}

.vehicle-name {
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    color: #2874b8;
    margin-bottom: 25px;
}

h2 {
    background: #17365d;
    color: white;
    padding: 12px 15px;
    border-radius: 5px;
    margin-top: 25px;
    margin-bottom: 10px;
    font-size: 19px;
}

.spec-section {
    margin-bottom: 20px;
}

.spec-row {
    display: flex;
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
}

.spec-row:nth-child(even) {
    background: #f2f6fa;
}

.spec-label {
    width: 40%;
    font-weight: bold;
    color: #17365d;
    padding-right: 15px;
    overflow-wrap: anywhere;
}

.spec-value {
    width: 60%;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.spec-value ul {
    margin: 0;
    padding-left: 20px;
}

.spec-value li {
    padding: 3px 0;
}

.print-button {
    background: #237a3b;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 20px;
}

.print-button:hover {
    background: #185c2c;
}

.button-area {
    text-align: center;
}

.error {
    background: #ffe6e6;
    padding: 15px;
    border-left: 5px solid red;
}

@media (max-width: 600px) {

    .spec-row {
        display: block;
    }

    .spec-label,
    .spec-value {
        width: 100%;
    }

    .spec-label {
        margin-bottom: 5px;
    }
}

@media print {

    body {
        background: white;
        padding: 0;
    }

    .container {
        box-shadow: none;
        padding: 0;
    }

    .print-button {
        display: none;
    }

    h2 {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    .spec-row {
        break-inside: avoid;
        page-break-inside: avoid;
    }
}

</style>

</head>

<body>

<div class="container">

<h1>Vehicle Specifications</h1>

<div class="vehicle-name">
    <?php echo safe($vehicle_name); ?>
</div>

<div class="button-area">

    <button class="print-button"
            onclick="window.print()">
        Print Specifications
    </button>

</div>

<?php

// -----------------------------------------------
// DISPLAY VEHICLE SPECIFICATIONS
// -----------------------------------------------

// Some JSON records have an outer
// "specifications" section.

if (
    isset($data['specifications']) &&
    is_array($data['specifications'])
) {

    displaySpecifications(
        $data['specifications']
    );

} else {

    displaySpecifications($data);

}

?>

</div>

</body>
</html>
