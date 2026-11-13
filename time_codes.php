<?php
function pdf_escape($text) {
    $text = str_replace(["\\","(",")","\r","\n"], ["\\\\","\\(","\\)",""," "], (string)$text);
    return preg_replace('/[^\x20-\x7E]/', '', $text);
}
function pdf_text($x,$y,$size,$text,$bold=false) {
    $font = $bold ? "F2" : "F1";
    return "BT /$font $size Tf $x $y Td (" . pdf_escape($text) . ") Tj ET\n";
}
function pdf_line($x1,$y1,$x2,$y2,$w=0.5) {
    return "$w w $x1 $y1 m $x2 $y2 l S\n";
}
function make_pdf($codes,$lotCodes,$name,$date,$notes) {
    $c = "0 G\n";
    $c .= pdf_text(245,755,18,"Time Codes",true);
    $c .= pdf_text(45,725,10,"Employee:",true);
    $c .= pdf_text(100,725,10,$name);
    $c .= pdf_line(98,720,360,720);
    $c .= pdf_text(390,725,10,"Date:",true);
    $c .= pdf_text(425,725,10,$date);
    $c .= pdf_line(423,720,565,720);

    $c .= pdf_text(45,690,11,"Description",true);
    $c .= pdf_text(440,690,11,"Code",true);
    $c .= pdf_line(45,684,565,684,0.8);

    $y=660;
    foreach($codes as $d=>$code){
        $c .= pdf_text(45,$y,10,$d);
        $c .= pdf_text(445,$y,10,$code,true);
        $c .= pdf_line(45,$y-5,565,$y-5);
        $y-=25;
    }

    $c .= pdf_text(45,$y-5,10,"Watch The Lot - customer codes",true);
    $y-=30;
    foreach($lotCodes as $d=>$code){
        $c .= pdf_text(65,$y,10,$d);
        $c .= pdf_text(445,$y,10,$code,true);
        $c .= pdf_line(65,$y-5,565,$y-5);
        $y-=24;
    }

    $y-=8;
    $c .= pdf_text(45,$y,10,"Notes:",true);
    $y-=18;
    $wrapped = wordwrap(trim($notes), 85, "\n");
    foreach(explode("\n",$wrapped) as $line){
        if($y<45) break;
        $c .= pdf_text(45,$y,9,$line);
        $y-=14;
    }

    $objs=[];
    $objs[]="<< /Type /Catalog /Pages 2 0 R >>";
    $objs[]="<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
    $objs[]="<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> /Contents 4 0 R >>";
    $objs[]="<< /Length ".strlen($c)." >>\nstream\n".$c."endstream";
    $objs[]="<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
    $objs[]="<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>";

    $pdf="%PDF-1.4\n";
    $offset=[0];
    foreach($objs as $i=>$obj){
        $offset[]=strlen($pdf);
        $n=$i+1;
        $pdf.="$n 0 obj\n$obj\nendobj\n";
    }
    $xref=strlen($pdf);
    $pdf.="xref\n0 ".(count($objs)+1)."\n";
    $pdf.="0000000000 65535 f \n";
    for($i=1;$i<=count($objs);$i++){
        $pdf.=sprintf("%010d 00000 n \n",$offset[$i]);
    }
    $pdf.="trailer\n<< /Size ".(count($objs)+1)." /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
    return $pdf;
}

$codes=[
"Meeting"=>"MT",
"Lot Walk"=>"LW",
"Lot Work"=>"LotW",
"Working with customer"=>"WC",
"Getting ready for a delivery"=>"GT",
"Delivery"=>"DL",
"Product knowledge"=>"PK",
"Calling service customers"=>"CS",
"Calling orphan customers"=>"CO",
"Marketing planning"=>"MP",
"Burning Time"=>"BT",
"Lunch"=>"LU",
"Customer problem"=>"HC",
"Calling customers"=>"CC",
"Calling active prospects"=>"AP",
"Watch The Lot"=>"LOT"
];

$lotCodes=[
"Service customer"=>"sc",
"Here for someone else"=>"se",
"Just looking"=>"jl",
"Good up"=>"gu"
];

if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["create_pdf"])){
    $name=trim($_POST["employee_name"]??"");
    $date=trim($_POST["date"]??"");
    $notes=trim($_POST["notes"]??"");
    $pdf=make_pdf($codes,$lotCodes,$name,$date,$notes);
    $safe=preg_replace('/[^A-Za-z0-9_-]+/','_',$name);
    if(!$safe) $safe="time_codes";
    header("Content-Type: application/pdf");
    header('Content-Disposition: attachment; filename="'.$safe.'_time_codes.pdf"');
    header("Content-Length: ".strlen($pdf));
    echo $pdf;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Time Codes</title>
<style>
body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0;padding:30px 15px;color:#222}
.wrap{max-width:900px;margin:auto;background:#fff;padding:30px 36px;border:1px solid #ccc;box-shadow:0 3px 14px rgba(0,0,0,.12)}
h1{text-align:center;margin-top:0}
.header{display:grid;grid-template-columns:1fr 220px;gap:20px;margin-bottom:24px}
label{font-weight:bold;display:block;margin-bottom:5px}
input,textarea{width:100%;padding:9px;border:1px solid #aaa;border-radius:4px;font-size:15px;box-sizing:border-box}
table{width:100%;border-collapse:collapse;margin-bottom:20px}
th,td{border:1px solid #aaa;padding:9px 10px;text-align:left}
th{background:#f0f0f0}
.code{width:110px;text-align:center;font-weight:bold}
.note{background:#f7f7f7;border-left:4px solid #666;padding:12px;margin:18px 0}
textarea{min-height:100px}
.buttons{text-align:center;margin-top:24px}
button{border:0;border-radius:5px;padding:12px 22px;font-size:16px;cursor:pointer;margin:0 6px}
.pdf{background:#1f6feb;color:white}
.clear{background:#e5e7eb}
@media(max-width:650px){.wrap{padding:20px 15px}.header{grid-template-columns:1fr}}
</style>
</head>
<body>
<form method="post" class="wrap">
<h1>Time Codes</h1>

<div class="header">
<div>
<label for="employee_name">Employee Name</label>
<input type="text" id="employee_name" name="employee_name">
</div>
<div>
<label for="date">Date</label>
<input type="date" id="date" name="date">
</div>
</div>

<table>
<thead><tr><th>Description</th><th>Code</th></tr></thead>
<tbody>
<?php foreach($codes as $d=>$code): ?>
<tr>
<td><?=htmlspecialchars($d)?></td>
<td class="code"><?=htmlspecialchars($code)?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="note">
<strong>Watch The Lot:</strong> When watching the lot, enter a code for each customer you talk to.
</div>

<table>
<thead><tr><th>Customer Type</th><th>Code</th></tr></thead>
<tbody>
<?php foreach($lotCodes as $d=>$code): ?>
<tr>
<td><?=htmlspecialchars($d)?></td>
<td class="code"><?=htmlspecialchars($code)?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<label for="notes">Entered Codes / Notes</label>
<textarea id="notes" name="notes" placeholder="Example: 9:00 MT, 9:30 LW, 10:00 WC..."></textarea>

<div class="buttons">
<button type="submit" name="create_pdf" value="1" class="pdf">Create PDF</button>
<button type="reset" class="clear">Clear</button>
</div>
</form>
</body>
</html>
