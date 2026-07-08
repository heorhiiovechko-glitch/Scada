<?php
include("header_inner.php");
error_reporting(0);

if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}

/* -------------------------------------------------
   DEVICE LIST (same as channel1.php)
------------------------------------------------- */
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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body { font-family:Poppins; background:#e9eef7; padding:20px; }

.table-box {
    background:#fff; padding:20px; border-radius:14px;
    box-shadow:0 8px 25px rgba(0,0,0,0.1);
}

table { width:100%; border-collapse:separate; border-spacing:0 6px; font-size:14px; }
th { background:#127678; color:#fff; padding:10px; border-radius:6px; text-align:center; }
td { padding:8px 10px; text-align:center; background:#fff; border-bottom:1px solid #eee; }
tr:hover { background:#f5f8ff; transform:scale(1.01); }

/* STATUS COLORS */
.status-run { color:#2e7d32; font-weight:700; }
.status-pause { color:#f9a825; font-weight:700; }
.status-grid { color:#1565c0; font-weight:700; }
.status-error { color:#d32f2f; font-weight:700; }
.status-unknown { color:#757575; font-weight:700; }

/* PERFORMANCE COLORS */
.perf-red { color:#d32f2f; font-weight:700; }
.perf-orange { color:#ff8f00; font-weight:700; }
.perf-green { color:#2e7d32; font-weight:700; }
.perf-purple { color:#0d47a1; font-weight:700; }

/* DEVICE SEPARATOR LINE */
.sep-row td {
    height: 2px;
    background: linear-gradient(to right, #444, #ccc, #444);
    border: none;
    padding: 0 !important;
}

.device-name {
    font-weight: 500;
    transition: transform 0.25s ease, color 0.25s ease;
    cursor: pointer;
}

.device-name:hover {
    transform: scale(1.12);
    color: #127678;
    font-weight: 600;
}

/* Animate Whole Row on Hover (Light Green Background) */
tr.data-row {
    transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
}

tr.data-row:hover {
    transform: scale(1.03);
    background-color: #e6ffe6;   /* ⭐ Light Green Background */
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    cursor: pointer;
}




</style>
</head>

<body>

<div class="table-box">
<table>
<tr>
    <th>Device</th>
    <th>HTSC</th>
    <th>Location</th>
	<th>Capacity</th>
    <th>Date</th>
	<th>Time</th>
    <th>Status</th>
    <th>Wind (m/s)</th>
    <th>Power (kW)</th>
    <th>GAD (kWh)</th>
    <th>Performance (%)</th>
</tr>

<?php
/* -------------------------------------------------
   MAIN LOOP
------------------------------------------------- */

while ($d = $res->fetch_array()) {

    $IMEI  = $d['IMEI'];
    $Device = $d['Device_Name'];
    $Format_Type = $d['Format_Type'];
    $DBname = $d['Database_Name'];
    $Pocket_Length = $d['Pocket_Length'];

    $IMEI_Decode = $IMEI;

    /* Same queries as your original file */
    if ($Format_Type == 2)
        $Q = "SELECT windspeed Power, Power, Status,date_m , time_m,
              ((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) AS G1,
              ((Gen1_Hours_Max-Gen1_Hours_Min)+(Gen2_Hours_Max-Gen2_Hours_Min)) AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";

    elseif ($Format_Type == 4)
        $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
              ((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) AS G1,
              ((Gen1_Hours_Max-Gen1_Hours_Min)+(Gen2_Hours_Max-Gen2_Hours_Min)) AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";

    elseif ($Format_Type == 1)
        $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
              (Gen2_Max-Gen2_Min) AS G1,
              (Gen1_Hours_Max-Gen1_Hours_Min) AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";

    elseif ($Format_Type == 6)
        $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
              (Gen2_Max-Gen2_Min) AS G1,
              (Gen1_Hours_Max-Gen1_Hours_Min) AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";

    elseif ($Format_Type == 10)
        $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
              (Gen1_Max-Gen1_Min) AS G1,
              (Gen1_Hours_Max-Gen1_Hours_Min) AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";

    elseif ($Format_Type == 3)
        $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
              (Gen1_Max-Gen1_Min) AS G1,
              ((Gen1_Hours_Max-Gen1_Hours_Min)+(Gen2_Hours_Max-Gen2_Hours_Min)) AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";

    elseif ($Format_Type == 7) {
        if ($DBname == "va_gwind")
            $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
                  Gen_Init_Date AS G1, Tip_Pressure AS G2
                  FROM va_gwind.device_data_f7 WHERE IMEI='$IMEI_Decode'
                  ORDER BY Record_Index DESC LIMIT 1";

        elseif ($DBname == "va_renom")
            $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
                  Active_Total_Gen_Export AS G1
                  FROM va_renom.device_data_f7 WHERE IMEI='$IMEI_Decode'
                  ORDER BY Record_Index DESC LIMIT 1";

        elseif ($DBname == "va_swami")
            $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
                 (SELECT Reactive_Total_Gen_Export FROM va_swami.device_data_f7 
                  WHERE IMEI='$IMEI_Decode' AND Date_S=CURDATE() ORDER BY Record_Index LIMIT 1) AS G1_Min,
                 (SELECT Reactive_Total_Gen_Export FROM va_swami.device_data_f7 
                  WHERE IMEI='$IMEI_Decode' AND Date_S=CURDATE() ORDER BY Record_Index DESC LIMIT 1) AS G1_Max
                  FROM va_swami.device_data_f7 WHERE IMEI='$IMEI_Decode'
                  ORDER BY Record_Index DESC LIMIT 1";

        else
            $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
                  Gen1_Max AS G1, Gen1_Hours_Max AS G2
                  FROM va_master.device_register WHERE IMEI='$IMEI_Decode'
                  ORDER BY IMEI DESC LIMIT 1";
    }

    elseif ($Format_Type == 8)
        $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
              Gen1_Max AS G1, Gen1_Hours_Max AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";

    elseif ($Format_Type == 9)
        $Q = "SELECT WindSpeed, Power, Status,date_m , time_m,
              Gen1_Max AS G1,
              (Gen1_Hours_Max-Gen2_Max) AS G2
              FROM va_master.device_register WHERE IMEI='$IMEI_Decode' ORDER BY IMEI DESC LIMIT 1";
	elseif ($Format_Type == 11)
	{
		
		
		$Q = "select Bridge2_dcv as Date_S,Bridge1_dcc as Time_S, Bridge1_dcv as WindSpeed, Bridge2_dcc as Power,status as Status,  Phase2_kvar AS G1 from va_powercon.device_data_f11 where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";
	}
			
$Result = $db->query($Q);

if ($Row = $Result->fetch_assoc()) {
    $d['Date_S'] = $Row['Date_S'];
    $d['Time_S'] = $Row['Time_S'];
}


    /* Run query */
    $r = $db->query($Q)->fetch_array();

    $Wind = number_format(floatval(str_replace("m/s", "", $r['WindSpeed'])),2);
    $Power = number_format(floatval($r['Power']),2);
    $Status = strtoupper($r['Status']);
	
	

    if ($Format_Type == 7 && $DBname == "va_swami") {
        $G1 = round(($r['G1_Max'] - $r['G1_Min']) * 1000, 2);
        $G2 = 0;
    } else {
        $G1 = floatval($r['G1']);
        $G2 = floatval($r['G2']);
    }

    if ($G1 < 0 || $G1 > 25000) $G1 = 0;
    if ($G2 < 0 || $G2 > 24) $G2 = 0;

    /* STATUS COLOR */
    $badge = "status-unknown";
    if ($Status == "RUN" || $Status == "RUNNING" || $Status == "M/C RUNNING" || $Status == "Run") 
        $badge = "status-run";
    elseif ($Status == "PAUSE" || $Status == "PAUSE : CHECKING WIND" || $Status == "Pause") 
        $badge = "status-pause";
    elseif ($Status == "GRID DROP" || $Status == "GRIDDROP") 
        $badge = "status-grid";
    else  
        $badge = "status-error";

    

// Keep values numeric
$Power = floatval($r['Power']);
$Cap   = floatval($d['capacity']);

$Perf = 0;

if ($Cap > 0)
{
    $Perf = round(($Power / $Cap) * 100, 2);

    // Optional: Limit between 0 and 100%
    if ($Perf < 0)   $Perf = 0;
    
}


    if ($Perf < 0) $perfC="perf-red";
    elseif ($Perf == 0) $perfC="perf-orange";
    elseif ($Perf <= 100) $perfC="perf-green";
    else $perfC="perf-purple";
?>
<tr class="device-separator data-row">
    <td><span class="device-name"><?= $Device ?></span></td>
    <td><?= $d['HTSC_No'] ?></td>
    <td><?= $d['Site_Location'] ?></td>
    <td><?= $d['capacity'] ?></td>
    <td><?= $d['Date_S'] ?></td>
	<td><?= $d['Time_S'] ?></td>
    <td><span class="<?= $badge ?>"><?= $Status ?></span></td>
    <td><?= $Wind ?></td>
    <td><?= $Power ?></td>
    <td><?= number_format($G1,2) ?> / <?= number_format($G2,2) ?></td>
    
	<td><?= number_format($Perf, 2) ?>%</td>
</tr>


<!-- DEVICE SEPARATOR -->
<tr class="sep-row"><td colspan="12"></td></tr>

<?php } ?>
</table>

</div>
</body>
</html>
