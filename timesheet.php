<?php
/*
 * Timesheet PDF Generator
 * Save as: timesheet.php
 * Put it in your XAMPP htdocs folder, for example:
 * C:\xampp\htdocs\timesheet.php
 */

function pdf_escape($text) {
    $text = (string)$text;
    $text = str_replace(["\\", "(", ")", "\r", "\n"], ["\\\\", "\\(", "\\)", "", " "], $text);
    // Keep PDF text safe for the built-in Helvetica font.
    return preg_replace('/[^\x20-\x7E]/', '', $text);
}

function pdf_text($x, $y, $size, $text, $bold = false) {
    $font = $bold ? "F2" : "F1";
    return "BT /{$font} {$size} Tf {$x} {$y} Td (" . pdf_escape($text) . ") Tj ET\n";
}

function pdf_line($x1, $y1, $x2, $y2, $width = 0.5) {
    return "{$width} w {$x1} {$y1} m {$x2} {$y2} l S\n";
}

function make_pdf($data, $slots) {
    // US Letter: 612 x 792 points
    $content = "";
    $content .= "0 G\n";

    // Title
    $content .= pdf_text(250, 755, 18, "Time Sheet", true);

    // Header fields
    $content .= pdf_text(45, 725, 10, "Name:", true);
    $content .= pdf_text(82, 725, 10, $data['name']);
    $content .= pdf_line(80, 720, 390, 720);

    $content .= pdf_text(410, 725, 10, "Date:", true);
    $content .= pdf_text(445, 725, 10, $data['date']);
    $content .= pdf_line(443, 720, 565, 720);

    $content .= pdf_text(45, 700, 10, "Book:", true);
    $content .= pdf_text(78, 700, 10, $data['book']);
    $content .= pdf_line(75, 695, 230, 695);

    $content .= pdf_text(245, 700, 10, "Chapter:", true);
    $content .= pdf_text(297, 700, 10, $data['chapter']);
    $content .= pdf_line(295, 695, 405, 695);

    $content .= pdf_text(420, 700, 10, "Time:", true);
    $content .= pdf_text(453, 700, 10, $data['time']);
    $content .= pdf_line(450, 695, 565, 695);

    // Schedule
    $y = 670;
    $row_height = 27;
    foreach ($slots as $key => $label) {
        $content .= pdf_text(45, $y, 9, $label, true);

        $entry = trim($data[$key] ?? "");
        // Keep long text within the page by splitting it into two short visual lines.
        $max = 78;
        if (strlen($entry) > $max) {
            $first = substr($entry, 0, $max);
            $second = substr($entry, $max, $max);
            $content .= pdf_text(105, $y, 9, $first);
            $content .= pdf_text(105, $y - 10, 8, $second);
        } else {
            $content .= pdf_text(105, $y, 9, $entry);
        }

        $content .= pdf_line(103, $y - 4, 565, $y - 4);
        $y -= $row_height;
    }

    $objects = [];

    // 1 Catalog
    $objects[] = "<< /Type /Catalog /Pages 2 0 R >>";
    // 2 Pages
    $objects[] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
    // 3 Page
    $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] "
               . "/Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> "
               . "/Contents 4 0 R >>";
    // 4 Content stream
    $objects[] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream";
    // 5 Helvetica
    $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
    // 6 Helvetica Bold
    $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>";

    $pdf = "%PDF-1.4\n";
    $offsets = [0];

    foreach ($objects as $i => $obj) {
        $offsets[] = strlen($pdf);
        $num = $i + 1;
        $pdf .= "{$num} 0 obj\n{$obj}\nendobj\n";
    }

    $xref = strlen($pdf);
    $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
    $pdf .= "0000000000 65535 f \n";
    for ($i = 1; $i <= count($objects); $i++) {
        $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
    }

    $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
    $pdf .= "startxref\n{$xref}\n%%EOF";

    return $pdf;
}

$slots = [
    't0900' => '9:00 AM',
    't0930' => '9:30 AM',
    't1000' => '10:00 AM',
    't1030' => '10:30 AM',
    't1100' => '11:00 AM',
    't1130' => '11:30 AM',
    't1230' => '12:30 PM',
    't0100' => '1:00 PM',
    't0130' => '1:30 PM',
    't0200' => '2:00 PM',
    't0230' => '2:30 PM',
    't0300' => '3:00 PM',
    't0330' => '3:30 PM',
    't0400' => '4:00 PM',
    't0430' => '4:30 PM',
    't0500' => '5:00 PM',
    't0530' => '5:30 PM',
    't0600' => '6:00 PM',
    't0630' => '6:30 PM',
    't0700' => '7:00 PM',
    't0730' => '7:30 PM',
    't0800' => '8:00 PM',
    't0830' => '8:30 PM'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_pdf'])) {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'date' => trim($_POST['date'] ?? ''),
        'book' => trim($_POST['book'] ?? ''),
        'chapter' => trim($_POST['chapter'] ?? ''),
        'time' => trim($_POST['time'] ?? '')
    ];

    foreach ($slots as $key => $label) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    $pdf = make_pdf($data, $slots);

    $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $data['name']);
    if ($safeName === '' || $safeName === null) {
        $safeName = 'timesheet';
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $safeName . '_timesheet.pdf"');
    header('Content-Length: ' . strlen($pdf));
    echo $pdf;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Time Sheet</title>
<style>
    * { box-sizing: border-box; }

    body {
        margin: 0;
        padding: 30px 15px;
        background: #f2f4f7;
        font-family: Arial, Helvetica, sans-serif;
        color: #222;
    }

    .sheet {
        max-width: 900px;
        margin: auto;
        background: white;
        padding: 32px 38px;
        border: 1px solid #ccc;
        box-shadow: 0 3px 14px rgba(0,0,0,.12);
    }

    h1 {
        margin: 0 0 28px;
        text-align: center;
        font-size: 28px;
    }

    .top-row {
        display: grid;
        grid-template-columns: 1fr 190px;
        gap: 20px;
        margin-bottom: 16px;
    }

    .second-row {
        display: grid;
        grid-template-columns: 1fr 180px 170px;
        gap: 20px;
        margin-bottom: 24px;
    }

    label {
        font-weight: bold;
        font-size: 14px;
    }

    .field {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .field input {
        width: 100%;
        border: none;
        border-bottom: 1px solid #333;
        padding: 7px 5px;
        font-size: 15px;
        outline: none;
    }

    .schedule-row {
        display: grid;
        grid-template-columns: 85px 1fr;
        gap: 12px;
        align-items: center;
        margin-bottom: 8px;
    }

    .schedule-row label {
        text-align: right;
        white-space: nowrap;
    }

    .schedule-row input {
        width: 100%;
        height: 32px;
        padding: 5px 8px;
        border: none;
        border-bottom: 1px solid #777;
        font-size: 15px;
        outline: none;
    }

    .buttons {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 28px;
    }

    button {
        border: 0;
        border-radius: 5px;
        padding: 12px 22px;
        font-size: 16px;
        cursor: pointer;
    }

    .pdf-button {
        background: #1f6feb;
        color: white;
    }

    .clear-button {
        background: #e5e7eb;
        color: #111;
    }

    @media (max-width: 650px) {
        .sheet { padding: 22px 16px; }
        .top-row, .second-row { grid-template-columns: 1fr; }
        .schedule-row { grid-template-columns: 72px 1fr; }
    }

    @media print {
        body { background: white; padding: 0; }
        .sheet { box-shadow: none; border: none; max-width: none; }
        .buttons { display: none; }
    }
</style>
</head>
<body>

<form method="post" class="sheet">
    <h1>Time Sheet</h1>

    <div class="top-row">
        <div class="field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name">
        </div>

        <div class="field">
            <label for="date">Date</label>
            <input type="date" id="date" name="date">
        </div>
    </div>

    <div class="second-row">
        <div class="field">
            <label for="book">Book</label>
            <input type="text" id="book" name="book">
        </div>

        <div class="field">
            <label for="chapter">Chapter</label>
            <input type="text" id="chapter" name="chapter">
        </div>

        <div class="field">
            <label for="time">Time</label>
            <input type="text" id="time" name="time">
        </div>
    </div>

    <?php foreach ($slots as $key => $label): ?>
        <div class="schedule-row">
            <label for="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></label>
            <input
                type="text"
                id="<?= htmlspecialchars($key) ?>"
                name="<?= htmlspecialchars($key) ?>"
                maxlength="156"
            >
        </div>
    <?php endforeach; ?>

    <div class="buttons">
        <button type="submit" name="create_pdf" value="1" class="pdf-button">
            Create PDF
        </button>

        <button type="reset" class="clear-button">
            Clear Form
        </button>
    </div>
</form>

</body>
</html>
