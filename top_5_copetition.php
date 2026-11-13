<?php
/* top_5_copetition.php - PHP 7.3+ */
$vehicle_name = $_POST['vehicle_name'] ?? '';
$json = $_POST['competition_json'] ?? '';
function competitionEscape($value) {
    return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
$error = '';
$competitors = [];
if (!is_string($json) || trim($json) === '') {
    $error = 'No competition information received. Open this report from show_vehicle.php.';
} else {
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = 'JSON error: ' . json_last_error_msg();
    } elseif (!is_array($data)) {
        $error = 'Competition information must be a JSON array.';
    } else {
        foreach ($data as $item) {
            if (is_array($item) && isset($item['vehicle']) && is_string($item['vehicle'])) {
                $competitors[] = $item;
            }
        }
        if (!$competitors) $error = 'No competitor records found.';
    }
}
function competitionList($items) {
    if (!is_array($items) || !$items) {
        echo '<p>Information not available.</p>';
        return;
    }
    echo '<ul>';
    foreach ($items as $item) {
        if (is_scalar($item)) echo '<li>' . competitionEscape($item) . '</li>';
    }
    echo '</ul>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Top 5 Competitors</title>
<style>
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; background: #f4f6f9; color: #263342; margin: 0; padding: 20px; line-height: 1.55; }
.container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 8px; }
h1 { text-align: center; color: #17365d; margin: 0; }
.vehicle-name { text-align: center; font-size: 22px; color: #2874b8; font-weight: bold; }
button { background: #237a3b; color: white; border: 0; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; }
.toolbar { text-align: center; margin: 20px 0; }
.competitor { border: 1px solid #d6dfe8; border-radius: 8px; margin: 24px 0; overflow: hidden; }
h2 { background: #17365d; color: white; padding: 14px 18px; margin: 0; font-size: 21px; }
.content { padding: 18px; }
h3 { color: #17365d; margin: 0 0 10px; font-size: 17px; }
.specs { background: #f2f6fa; padding: 16px; border-radius: 5px; margin-bottom: 16px; }
.advantages { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.selected, .rival { padding: 16px; border-radius: 5px; }
.selected { background: #eef7ef; border-left: 4px solid #237a3b; }
.rival { background: #eef3fa; border-left: 4px solid #2874b8; }
ul { margin: 0; padding-left: 22px; } li { margin: 5px 0; overflow-wrap: anywhere; }
.error { padding: 16px; background: #fff1f1; border-left: 4px solid #b42318; }
.note { color: #596575; font-size: 14px; }
@media(max-width: 650px) { body { padding: 8px; } .container { padding: 14px; } .advantages { grid-template-columns: 1fr; } }
@media print { body { background: white; padding: 0; } .container { padding: 0; } .toolbar { display: none; } .competitor { break-inside: avoid; page-break-inside: avoid; } h2 { print-color-adjust: exact; -webkit-print-color-adjust: exact; } }
</style>
</head>
<body>
<main class="container">
<h1>Top 5 Competitors</h1>
<p class="vehicle-name"><?php echo competitionEscape($vehicle_name); ?></p>
<?php if ($error !== ''): ?>
<p class="error"><?php echo competitionEscape($error); ?></p>
<?php else: ?>
<div class="toolbar"><button type="button" onclick="window.print()">Print Competition Report</button></div>
<p class="note">Comparisons are displayed from saved research. Verify specifications, model-year availability and claims before using them with customers.</p>
<?php foreach (array_slice($competitors, 0, 5) as $index => $competitor): ?>
<section class="competitor">
<h2><?php echo $index + 1; ?>. <?php echo competitionEscape($competitor['vehicle']); ?></h2>
<div class="content">
<div class="specs">
<h3>Competitor Specifications</h3>
<?php competitionList($competitor['specifications'] ?? []); ?>
</div>
<div class="advantages">
<div class="selected">
<h3>Selected Vehicle Advantages</h3>
<?php competitionList($competitor['selected_vehicle_advantages'] ?? []); ?>
</div>
<div class="rival">
<h3>Competitor Advantages</h3>
<?php competitionList($competitor['competitor_advantages'] ?? []); ?>
</div>
</div>
</div>
</section>
<?php endforeach; ?>
<?php endif; ?>
</main>
</body>
</html>
