<?php
/* =====================================================
   DAILY GENERATION REPORT
   Date → Device → Values + Excel + PDF
===================================================== */

error_reporting(0);
ob_start();

/* ================= AUTH ================= */

if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}

$db = $GLOBALS['db'];


/* ================= INPUT ================= */

$Start = $_REQUEST['inputDate']  ?? date('Y-m-d');
$End   = $_REQUEST['inputDate1'] ?? date('Y-m-d');
$XLS   = $_REQUEST['XLS']        ?? 0;


/* Normalize Dates */
$Start = date("Y-m-d", strtotime($Start));
$End   = date("Y-m-d", strtotime($End));


/* ================= DEVICE LIST ================= */

$Device_Name = [];
$DGR_IMEI    = [];


/* Admin / Parent */
if ($Cook_Variable[2] == 2 || $Cook_Variable[2] == 3) {

    $sql = "
        SELECT IMEI, Device_Name
        FROM device_register
        WHERE Parent_ID = '{$Cook_Variable[6]}'
        ORDER BY Device_Order
    ";

}
/* Account */
elseif ($Cook_Variable[2] == 4) {

    $sql = "
        SELECT IMEI, Device_Name
        FROM device_register
        WHERE Account_ID = '{$Account_ID}'
        ORDER BY Device_Order
    ";

}
/* Super Admin */
else {

    $sql = "
        SELECT IMEI, Device_Name
        FROM device_register
        ORDER BY Device_Order
    ";
}


$res = $db->query($sql);

while ($r = $res->fetch_assoc()) {

    $DGR_IMEI[] = $r['IMEI'];
    $Device_Name[$r['IMEI']] = $r['Device_Name'];
}


/* Safety */
if (empty($DGR_IMEI)) {
    die("No Devices Found!");
}


/* ================= DATE RANGE ================= */

function getDates($start, $end)
{
    $arr = [];

    while (strtotime($start) <= strtotime($end)) {

        $arr[] = $start;
        $start = date("Y-m-d", strtotime($start . "+1 day"));
    }

    return $arr;
}

$Date_Array = getDates($Start, $End);


/* ================= INIT ARRAYS ================= */

$Array_Gen  = [];
$Array_Run  = [];
$Array_GD   = [];
$Array_BD   = [];
$Array_Lull = [];
$GA_Percent = [];


/* ================= LOAD DATA ================= */

$imei_list = "'" . implode("','", $DGR_IMEI) . "'";


$sql = "
    SELECT *
    FROM daily_data
    WHERE IMEI IN ($imei_list)
    AND Date_S BETWEEN '$Start' AND '$End'
";

$res = $db->query($sql);

if (!$res) {
    die($db->error);
}


while ($r = $res->fetch_assoc()) {

    $imei = $r['IMEI'];
    $date = $r['Date_S'];

    /* GENERATION */
    $gen = $r['Gen1_Max'] - $r['Gen1_Min'];
    if ($gen < 0) $gen = 0;
    $Array_Gen[$imei][$date] = round($gen, 2);

    /* RUN */
    $run = $r['Run_Max'] - $r['Run_Min'];
    if ($run < 0)  $run = 0;
    if ($run > 24) $run = 24;
    $Array_Run[$imei][$date] = round($run, 2);

    /* GRID DOWN */
    $gd = 24 - ($r['Line_Max'] - $r['Line_Min']);
    if ($gd < 0)  $gd = 0;
    if ($gd > 24) $gd = 24;
    $Array_GD[$imei][$date] = round($gd, 2);

    /* BREAKDOWN */
    $bd = 24 - ($run + $gd);
    if ($bd < 0) $bd = 0;
    $Array_BD[$imei][$date] = round($bd, 2);

    /* LULL */
    $lull = 24 - ($run + $gd + $bd);
    if ($lull < 0) $lull = 0;
    $Array_Lull[$imei][$date] = round($lull, 2);

    /* GA % */
    $GA_Percent[$imei][$date] =
        round(((24 - $gd) / 24) * 100, 2);
}


/* ================= EXCEL OUTPUT ================= */

if ($XLS == 1) {

    if (ob_get_length()) ob_end_clean();

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=Daily_Report.xls");
    header("Cache-Control: no-store, no-cache");
    header("Pragma: no-cache");

    echo "<table border='1'>";

} else {
?>

<!DOCTYPE html>
<html>
<head>

<title>Daily Generation Report</title>

<style>

body{
    font-family:Arial;
    font-size:12px;
}

table{
    border-collapse:collapse;
    width:100%;
}

td,th{
    border:1px solid #444;
    padding:5px;
}

.head{
    background:#dcdcdc;
    font-weight:bold;
    text-align:center;
}

.total{
    background:#eee;
    font-weight:bold;
}

button{
    padding:6px 12px;
    cursor:pointer;
}

</style>


<!-- PDF LIBRARIES (FIXED) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.min.js"></script>


</head>

<body>

<h3>Daily Generation Detailed Report</h3>

<p>

<a href="?inputDate=<?=$Start?>&inputDate1=<?=$End?>&XLS=1">
Download Excel
</a>

&nbsp;&nbsp;

<button onclick="exportPDF()">Download PDF</button>

</p>


<!-- PDF AREA -->
<div id="reportArea">

<table>

<?php } ?>


<!-- HEADER -->

<tr class="head">

    <th>Date</th>
    <th>Device Name</th>
    <th>Gen</th>
    <th>Run</th>
    <th>GD</th>
    <th>BD</th>
    <th>Lull</th>
    <th>GA %</th>

</tr>


<!-- DATA -->

<?php

foreach ($Date_Array as $d) {

    foreach ($DGR_IMEI as $i) {

        $GEN  = $Array_Gen[$i][$d]  ?? 0;
        $RUN  = $Array_Run[$i][$d]  ?? 0;
        $GD   = $Array_GD[$i][$d]   ?? 0;
        $BD   = $Array_BD[$i][$d]   ?? 0;
        $LULL = $Array_Lull[$i][$d] ?? 0;
        $GA   = $GA_Percent[$i][$d] ?? 0;
?>

<tr>

    <td><?=date("d-m-Y", strtotime($d))?></td>
    <td><?=$Device_Name[$i]?></td>

    <td><?=$GEN?></td>
    <td><?=$RUN?></td>
    <td><?=$GD?></td>
    <td><?=$BD?></td>
    <td><?=$LULL?></td>
    <td><?=$GA?></td>

</tr>

<?php
    }
}
?>


<!-- TOTAL -->

<tr class="total">

<td colspan="2"><b>Total</b></td>

<td><?=array_sum(array_map('array_sum', $Array_Gen))?></td>
<td><?=array_sum(array_map('array_sum', $Array_Run))?></td>
<td><?=array_sum(array_map('array_sum', $Array_GD))?></td>
<td><?=array_sum(array_map('array_sum', $Array_BD))?></td>
<td><?=array_sum(array_map('array_sum', $Array_Lull))?></td>
<td></td>

</tr>


<?php

if ($XLS == 1) {

    echo "</table>";
    exit;

} else {
?>

</table>
</div>


<!-- PDF SCRIPT (FIXED + SAFE) -->





<script>

function exportPDF()
{
    var report = document.getElementById("reportArea");

    if(!report)
    {
        alert("Report area not found!");
        return;
    }

    html2canvas(report, {
        scale: 2,
        useCORS: true,
        backgroundColor: "#ffffff"
    }).then(function(canvas){

        var imgData = canvas.toDataURL("image/png");

        var pdf = new jsPDF('l','mm','a4'); // Landscape

        var pageWidth  = 297;
        var pageHeight = 210;

        var imgWidth = pageWidth;
        var imgHeight = (canvas.height * imgWidth) / canvas.width;

        var heightLeft = imgHeight;
        var position = 0;

        pdf.addImage(imgData,'PNG',0,position,imgWidth,imgHeight);

        heightLeft -= pageHeight;

        while(heightLeft > 0)
        {
            position = heightLeft - imgHeight;

            pdf.addPage();
            pdf.addImage(imgData,'PNG',0,position,imgWidth,imgHeight);

            heightLeft -= pageHeight;
        }

        pdf.save("Daily_Generation_Report.pdf");

    }).catch(function(err){

        console.log(err);
        alert("PDF Export Failed. Check Console.");

    });
}

</script>




</body>
</html>

<?php } ?>