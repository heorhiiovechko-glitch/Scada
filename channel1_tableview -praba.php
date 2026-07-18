<?php
include("header_inner.php");
error_reporting(0);

if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}

// Query devices same as channel1
if ($User_Type_ID == 3 || $User_Type_ID == 2) {
    $Mysql_Query2 = "SELECT * FROM device_register WHERE Parent_ID='$Account_ID' ORDER BY Device_Order ASC";
} elseif ($User_Type_ID == 4) {
    $Mysql_Query2 = "SELECT * FROM device_register WHERE Account_ID='$Account_ID' ORDER BY Device_Order ASC";
} else {
    $Mysql_Query2 = "SELECT * FROM device_register WHERE 1=0";
}

$res = $db->query($Mysql_Query2);
?>

<!DOCTYPE html>
<html>
<head>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body {
    font-family: 'Poppins', sans-serif;
    background:#e9eef7;
    padding:20px;
}

.table-box {
    background:#ffffff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 8px 25px rgba(0,0,0,0.10);
    animation: fadeIn .5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity:0; transform:translateY(10px); }
    to   { opacity:1; transform:translateY(0); }
}

/* HEADER CENTERED */
h2 {
    font-size:24px;
    font-weight:600;
    color:#1a237e;
    margin-bottom:20px;
    text-align:center !important;
    letter-spacing:1px;
    display:block;
    width:100%;
}

/* Modern Table */
table {
    width:100%;
    border-collapse:separate;
    border-spacing:0 6px;
    font-size:14px;
}

th {
    background:#127678;
    color:#fff;
    padding:10px 8px;
    text-align:center;            /* CENTER HEADER TEXT */
    font-weight:600;
    border-radius:6px;
}

tr {
    background:#ffffff;
    transition:0.2s ease;
}

td {
    padding:8px 10px;
    border-bottom:1px solid #e3e7f1;
    font-weight:500;
    color:#333;
    text-align:center;            /* CENTER ROW TEXT */
}


/* Rounded row effect */
tr td:first-child {
    border-left:6px solid #1a73e8;
    border-radius:6px 0 0 6px;
}
tr td:last-child {
    border-radius:0 6px 6px 0;
}

/* Hover effect */
tr:hover {
    background:#f2f7ff;
    transform:scale(1.01);
}

/* ---------- STATUS TEXT COLORS ---------- */
.status-badge {
    font-size:13px;
    font-weight:700;
    text-transform:uppercase;
}

.status-run { color:#2e7d32; }       /* Green */
.status-error { color:#d32f2f; }     /* Red */
.status-pause { color:#f9a825; }     /* Yellow */
.status-grid  { color:#1565c0; }     /* Blue */
.status-unknown { color:#757575; }   /* Grey */


/* PERFORMANCE COLOR TEXT */
.perf-red { color:#d32f2f; font-weight:700; }
.perf-orange { color:#ff8f00; font-weight:700; }
.perf-green { color:#2e7d32; font-weight:700; }
.perf-normal { color:#333; font-weight:600; }
.perf-purple { color:#0d47a1; font-weight:700; } /* MORE THAN 100% */

</style>
</head>
<body>

<div class="table-box">

<table>
    <tr>
        <th>Device</th>
        <th>HTSC</th>
        <th>Location</th>
        <th>Feeder</th>
		<th>Capacity</th>
        <th>Status</th>
		<th>Wind (m/s)</th>
        <th>Power (kw)</th>
        <th>GAD (kwh)</th>
		<th>Performance (%)</th>
    </tr>

<?php
while($d = $res->fetch_array()) {

    $IMEI = $d['IMEI'];
    $loc = $d['Site_Location'];
    $feeder = $d['Connect_Feeder'];
	$Capacity = $d['Power_Curve'];
	
	/* ------------------------------
   FETCH DETAILED INFORMATION
   SAME LOGIC AS dashboard.php
--------------------------------*/

$IMEI_Enc = base64_encode($d['IMEI']);
$Device     = $d['Device_Name'];
$Format_Type = $d['Format_Type'];
$Pocket_Length = $d['Pocket_Length'];
$Capacity   = $d['Capacity'];

// Determine channel URL and table name
if ($Format_Type == 1) {
    $Channel_Url = "channel2.php?";
    $Table_Name = "device_data";
    $Error_Table_Name = "error_data";
}
elseif ($Format_Type == 2) {
    if ($Device == 'Selva Tex 250kw') {
        $Channel_Url = "channel3_selvatex.php?";
    } elseif ($Account_ID == '100215') {
        $Channel_Url = "channel3_ucal.php?";
    } else {
        $Channel_Url = "channel3.php?";
    }
    $Table_Name = "device_data_f2";
    $Error_Table_Name = "error_data_f2";
}
elseif ($Format_Type == 3) {
    $Channel_Url = "channel4.php?";
    $Table_Name = "device_data_f3";
    $Error_Table_Name = "error_data_f3";
}
elseif ($Format_Type == 4) {
    if ($Device == 'Aspire') {
        $Channel_Url = "channel9_new.php?";
    } else {
        $Channel_Url = "channel5.php?";
    }
    $Table_Name = "device_data_f4";
    $Error_Table_Name = "error_data_f4";
}
elseif ($Format_Type == 6) {
    $Channel_Url = "channel7.php?";
    $Table_Name  = "device_data_f6";
    $Error_Table_Name = "error_data_f6";
}
elseif ($Format_Type == 7) {
    if ($Device == 'ICE MAN') {
        $Channel_Url = "channel1_iceman.php?";
    } elseif ($Device == 'Aalayam S826' || $Device == 'Aalayam S824' || $Device == 'Aalayam S792') {
        $Channel_Url = "channel8_aalayam.php?";
    } else {
        $Channel_Url = "channel8.php?";
    }
    $Table_Name = "device_data_f7";
    $Error_Table_Name = "error_data_f7";
}
elseif ($Format_Type == 8) {
    $Channel_Url = "channel8.php?";
    $Table_Name = "device_data_f8";
    $Error_Table_Name = "error_data_f8";
}
elseif ($Format_Type == 9) {
    $Channel_Url = "channel9new.php?";
    $Table_Name = "device_data_f9";
    $Error_Table_Name = "error_data_f9";
}
elseif ($Format_Type == 10) {
    $Channel_Url = "channel10.php?";
    $Table_Name = "device_data_f10";
    $Error_Table_Name = "error_data_f10";
}
else {
    $Channel_Url = "channel8.php?";
    $Table_Name = "device_data_f7";
    $Error_Table_Name = "error_data_f7";
}

	
	
	
	


    // Fetch LIVE Data
    $q = $db->query("SELECT Date_S, Time_S, WindSpeed, Power, Status, capacity,
                     Gen1_Max AS G1, Gen1_Hours_Max AS G2 
                     FROM va_master.device_register 
                     WHERE IMEI='$IMEI' ORDER BY IMEI DESC LIMIT 1");

    $r = $q->fetch_array();

    $Wind  = number_format(floatval(str_replace('m/s','',$r['WindSpeed'])),2);
    $Power = number_format(floatval($r['Power']),2);
    $Status = strtoupper($r['Status']);
    $G1 = round($r['G1'],2);
    $G2 = round($r['G2'],2);

    // Status color logic
    $badgeClass = "status-unknown";
    if ($Status == "RUN" || $Status == "RUNNING" || $Status == "M/C RUNNING" || $Status == "Run") 
        $badgeClass = "status-run";
    elseif ($Status == "PAUSE" || $Status == "PAUSE : CHECKING WIND" || $Status == "Pause") 
        $badgeClass = "status-pause";
    elseif ($Status == "GRID DROP" || $Status == "GRIDDROP") 
        $badgeClass = "status-grid";
    else  
        $badgeClass = "status-error";
	
	$Capacity = floatval($d['Power_Curve']); // from device_register

if ($Capacity > 0) {
    $Performance = round(($Power / $Capacity) * 100, 2);
} else {
    $Performance = 0;
}

/* PERFORMANCE COLOR SELECTION */
if ($Performance < 0) {
    $perfClass = "perf-red";
}
elseif ($Performance == 0) {
    $perfClass = "perf-orange";
}
elseif ($Performance > 0 && $Performance <= 100) {
    $perfClass = "perf-green";
}
else { 
    // MORE THAN 100%
    $perfClass = "perf-purple";
}


?>

		

    <tr>
        <td>
    <a href="<?= $Channel_Url . 'c1=' . $IMEI_Enc . '&l=' . $Pocket_Length . '&FType=' . $Format_Type ?>"
       target="_blank"
       style="color:#0d47a1; font-weight:700; text-decoration:none;">
       <?= $d['Device_Name'] ?>
    </a>
</td>

        <td><?= $d['HTSC_No'] ?></td>
        <td><?= $loc ?></td>
        <td><?= $feeder ?></td>
		<td><?= $Capacity?></td>

        <!-- ONLY TEXT COLOR -->
        <td><span class="<?= $badgeClass ?> status-badge"><?= $Status ?></span></td>

        <td><?= $Wind ?> </td>
        <td><?= $Power ?></td>
        <td><?= $G1 ?> / <?= $G2 ?> </td>
		
		<td><span class="<?= $perfClass ?>"><?= $Performance ?>%</span></td>


    </tr>

<?php } ?>

</table>
</div>

</body>
</html>
