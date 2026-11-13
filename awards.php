
<?php
/*
 * Program: awards.php
 *
 * Displays vehicle awards and safety ratings.
 * Supports both original and newer JSON formats.
 *
 * Compatible with PHP 7.3, 8.2 and 8.3.
 */

// -----------------------------------------------
// RECEIVE INFORMATION FROM show_vehicle.php
// -----------------------------------------------

$json = $_POST['awards_json'] ?? '';
$vehicle_name = $_POST['vehicle_name'] ?? '';

$error = '';
$awards = [];
$safety = [];
$notes = [];

// -----------------------------------------------
// DECODE JSON
// -----------------------------------------------

if (!is_string($json) || trim($json) === '') {

    $error = 'No awards information received.';

} else {

    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {

        $error = 'JSON Error: ' .
                 json_last_error_msg();

    } elseif (!is_array($data)) {

        $error = 'Invalid awards information.';

    } else {

        $awards = $data['awards'] ?? [];
        $safety = $data['safety_ratings'] ?? [];
        $notes = $data['notes'] ?? [];
    }
}

// -----------------------------------------------
// FUNCTIONS
// -----------------------------------------------

// Safely display text
function safe($value) {

    if (!is_scalar($value)) {
        return '';
    }

    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Convert field names into readable labels
function formatLabel($label) {

    return ucwords(
        str_replace(
            ['_', '-'],
            ' ',
            (string)$label
        )
    );
}

// Display notes as text or a list
function displayNotes($notes) {

    if (is_array($notes)) {

        if (empty($notes)) {
            return;
        }

        echo '<ul class="notes-list">';

        foreach ($notes as $note) {

            if (is_scalar($note)) {

                echo '<li>' .
                     safe($note) .
                     '</li>';
            }
        }

        echo '</ul>';

    } elseif (is_scalar($notes) &&
              trim((string)$notes) !== '') {

        echo '<p class="details">' .
             nl2br(safe($notes)) .
             '</p>';
    }
}

// Display ratings in either JSON format
function displayRatings($ratings, $agency = '') {

    // NEW FORMAT:
    // ratings contains individual rating values

    if (is_array($ratings)) {

        if (empty($ratings)) {

            echo '<p>No ratings available.</p>';
            return;
        }

        echo '<table class="ratings-table">';

        echo '<thead>
                <tr>
                    <th>Safety Test</th>
                    <th>Rating</th>
                </tr>
              </thead>';

        echo '<tbody>';

        foreach ($ratings as $test => $value) {

            if (!is_scalar($value)) {
                continue;
            }

            // Display NHTSA numeric ratings as stars
            if (
                stripos($agency, 'NHTSA') !== false &&
                is_numeric($value) &&
                $value >= 1 &&
                $value <= 5
            ) {

                $value = $value . ' out of 5 stars';
            }

            echo '<tr>';

            echo '<td>' .
                 safe(formatLabel($test)) .
                 '</td>';

            echo '<td class="rating-value">' .
                 safe($value) .
                 '</td>';

            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';

    } elseif (is_scalar($ratings)) {

        // ORIGINAL FORMAT:
        // rating contains a single text value

        $parts = explode(';', (string)$ratings);

        if (count($parts) > 1) {

            echo '<ul class="rating-list">';

            foreach ($parts as $part) {

                $part = trim($part);

                if ($part !== '') {

                    echo '<li>' .
                         safe($part) .
                         '</li>';
                }
            }

            echo '</ul>';

        } else {

            echo '<p class="rating-value">' .
                 safe($ratings) .
                 '</p>';
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

<title>Vehicle Awards and Safety Ratings</title>

<style>

* {
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f6f9;
    color: #333;
    margin: 0;
    padding: 20px;
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
    margin-top: 30px;
}

.record {
    padding: 18px;
    margin: 12px 0;
    border-left: 5px solid #2c6db2;
    background: #f7f9fc;
    border-radius: 4px;
}

.record:nth-of-type(even) {
    background: #eaf1f8;
}

.organization {
    font-size: 21px;
    font-weight: bold;
    color: #17365d;
    margin-bottom: 15px;
}

.award-title {
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 10px;
}

.details {
    font-size: 15px;
    line-height: 1.7;
    margin-top: 12px;
}

.ratings-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: white;
}

.ratings-table th {
    background: #2874b8;
    color: white;
    text-align: left;
    padding: 12px;
}

.ratings-table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.ratings-table tr:nth-child(even) {
    background: #f0f4f8;
}

.rating-value {
    font-weight: bold;
    color: #17365d;
}

.rating-list,
.notes-list {
    padding-left: 25px;
    line-height: 1.7;
}

.rating-list li,
.notes-list li {
    margin-bottom: 5px;
}

.print-button {
    background: #237a3b;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.print-button:hover {
    background: #185c2c;
}

.empty {
    padding: 15px;
    color: #666;
}

.error {
    background: #ffe6e6;
    padding: 15px;
    border-left: 5px solid #b42318;
}

.research-note {
    font-size: 13px;
    color: #666;
    margin-top: 25px;
}

@media (max-width: 600px) {

    body {
        padding: 8px;
    }

    .container {
        padding: 12px;
    }

    .ratings-table td,
    .ratings-table th {
        padding: 8px;
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

    .record {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    h2,
    .ratings-table th {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}

</style>

</head>

<body>

<div class="container">

<h1>Vehicle Awards and Safety Ratings</h1>

<?php if ($vehicle_name !== ''): ?>

    <div class="vehicle-name">
        <?php echo safe($vehicle_name); ?>
    </div>

<?php endif; ?>


<?php if ($error !== ''): ?>

    <div class="error">
        <?php echo safe($error); ?>
    </div>

<?php else: ?>


<button class="print-button"
        onclick="window.print()">

    Print Report

</button>


<!-- ===========================================
     AWARDS SECTION
     =========================================== -->

<h2>Awards and Recognition</h2>

<?php if (!is_array($awards) || empty($awards)): ?>

    <p class="empty">
        No awards information available.
    </p>

<?php else: ?>

    <?php foreach ($awards as $award): ?>

        <div class="record">

        <?php if (is_array($award)): ?>

            <div class="organization">

                <?php
                echo safe(
                    $award['organization'] ??
                    $award['agency'] ??
                    'Award Organization'
                );
                ?>

            </div>

            <div class="award-title">

                <?php
                echo safe(
                    $award['award'] ??
                    $award['title'] ??
                    $award['name'] ??
                    ''
                );
                ?>

            </div>

            <?php
            displayNotes(
                $award['details'] ?? ''
            );

            displayNotes(
                $award['notes'] ?? ''
            );
            ?>

        <?php else: ?>

            <?php echo safe($award); ?>

        <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>


<!-- ===========================================
     SAFETY RATINGS SECTION
     =========================================== -->

<h2>Safety Ratings and Reviews</h2>

<?php if (!is_array($safety) || empty($safety)): ?>

    <p class="empty">
        No safety ratings information available.
    </p>

<?php else: ?>

    <?php foreach ($safety as $rating): ?>

        <?php
        if (!is_array($rating)) {
            continue;
        }

        // Support organization OR agency
        $agency = $rating['organization'] ??
                  $rating['agency'] ??
                  'Safety Organization';

        // Support rating OR ratings
        $ratingData = $rating['ratings'] ??
                      $rating['rating'] ??
                      '';
        ?>

        <div class="record">

            <div class="organization">

                <?php echo safe($agency); ?>

            </div>

            <?php
            displayRatings(
                $ratingData,
                is_string($agency) ? $agency : ''
            );
            ?>

            <?php
            // Support details AND notes

            displayNotes(
                $rating['details'] ?? ''
            );

            displayNotes(
                $rating['notes'] ?? ''
            );
            ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>


<!-- ===========================================
     ADDITIONAL NOTES SECTION
     =========================================== -->

<?php if (!empty($notes)): ?>

    <h2>Additional Vehicle Notes</h2>

    <div class="record">

        <?php displayNotes($notes); ?>

    </div>

<?php endif; ?>


<p class="research-note">

    Information displayed from saved vehicle research.
    Verify awards and safety ratings with the original
    organizations before presenting them to customers.

</p>

<?php endif; ?>

</div>

</body>
</html>
