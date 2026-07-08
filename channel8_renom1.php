<?php
// channel8_modern_dashboard.php (corrected & hardened)
// minimal logic changes — mostly safety checks, proper result fetching, and display fixes

error_reporting(0);
include("header_inner.php");
if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}
$Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
if (isset($Cook_Variable)) {
    $Username = base64_encode($Cook_Variable[0]);
    $Pass = base64_encode($Cook_Variable[8] ?? '');
}

// Inputs
$IMEI = isset($_REQUEST['c1']) ? $_REQUEST['c1'] : '';
$IMEI_Decode = $IMEI ? base64_decode($IMEI) : '';
$Pocket_Length = isset($_REQUEST['l']) ? $_REQUEST['l'] : '';
$FType = isset($_REQUEST['FType']) ? $_REQUEST['FType'] : '';
$Database_Name = isset($_REQUEST['Db_Name']) ? $_REQUEST['Db_Name'] : '';

// defaults
$All_Devicename = [];
$GAD_Today = $GAD_Yesterday = $GAD_Thismonth = 0;
$GAD_ThisYear = 0;
$Capacity = 0;
$Active_Power = 0;

// Fetch device basic info and fallback DB name
if ($IMEI_Decode) {
    $safeIMEI = $db->real_escape_string($IMEI_Decode);
    $q = "SELECT Device_Name, Db_Name, Capacity, Date_Of_Commission, Power_Curve, Format_Type FROM device_register WHERE IMEI = '{$safeIMEI}' LIMIT 1";
    if ($r = $db->query($q)) {
        if ($row = $r->fetch_assoc()) {
            $All_Devicename[1] = $row['Device_Name'] ?? '';
            if (!$Database_Name && !empty($row['Db_Name'])) $Database_Name = $row['Db_Name'];
            $Power_Curve_Array[$safeIMEI] = $row['Power_Curve'] ?? null;
            $Format_Type = $row['Format_Type'] ?? null;
            $Capacity = is_numeric($row['Capacity']) ? floatval($row['Capacity']) : 0;
        }
        $r->free();
    }
}

// -- Determine table names by Format Type
$Table_Name = '';
$Error_Table_Name = '';
if ($FType == 7) { $Table_Name = "device_data_f7"; $Error_Table_Name = "error_data_f7"; }
elseif ($FType == 8) { $Table_Name = "device_data_f8"; $Error_Table_Name = "error_data_f8"; }
else {
    // fallback (keep as device_data_f4 if nothing provided)
    $Table_Name = "device_data_f4";
    $Error_Table_Name = "error_data_f4";
}

// --- Fetch last record from device data
$lastRecd = null;
$Date_F = $Time_F = $Status = '';
if ($Database_Name && $IMEI_Decode) {
    $safeDB = preg_replace('/[^a-zA-Z0-9_]/', '', $Database_Name);
    $safeIMEI = $db->real_escape_string($IMEI_Decode);

    $mysql = "SELECT * FROM `{$safeDB}`.`{$Table_Name}` WHERE IMEI = '{$safeIMEI}' AND Status != '' ORDER BY Record_Index DESC LIMIT 1";
    $res = $db->query($mysql);
    if (!$res) {
        // try main DB if that fails
        $mysql = "SELECT * FROM `{$Table_Name}` WHERE IMEI = '{$safeIMEI}' AND Status != '' ORDER BY Record_Index DESC LIMIT 1";
        $res = $db->query($mysql);
    }
    if ($res && $res->num_rows >= 1) {
        $Fetch_Result = $res->fetch_assoc();
        // map fields (use null coalescing to avoid undefined notices)
        $Project_Version = $Fetch_Result['Project_Version'] ?? '';
        $ID_Number = $Fetch_Result['ID_Number'] ?? '';
        $GRPM = $Fetch_Result['GRPM'] ?? '';
        $RRPM = $Fetch_Result['RRPM'] ?? '';
        $WindSpeed = isset($Fetch_Result['Windspeed']) ? str_replace('m/s','',$Fetch_Result['Windspeed']) : '';
        $Active_Power = is_numeric($Fetch_Result['Power']) ? floatval($Fetch_Result['Power']) : 0;
        $Reactive_Power = $Fetch_Result['Reactive_Power'] ?? '';
        $L_N_Voltage_R = $Fetch_Result['L_N_Voltage_R'] ?? '';
        $L_N_Voltage_Y = $Fetch_Result['L_N_Voltage_Y'] ?? '';
        $L_N_Voltage_B = $Fetch_Result['L_N_Voltage_B'] ?? '';
        $L_L_Voltage_RY = $Fetch_Result['L_L_Voltage_RY'] ?? '';
        $L_L_Voltage_YB = $Fetch_Result['L_L_Voltage_YB'] ?? '';
        $L_L_Voltage_BR = $Fetch_Result['L_L_Voltage_BR'] ?? '';
        $Frequency = $Fetch_Result['Frequency'] ?? '';
        $Energy_Current_Hour = $Fetch_Result['Active_Total_Gen_Import'] ?? 0;
        $Energy_Current_Day = isset($Fetch_Result['Active_Total_Gen_Export']) ? ($Fetch_Result['Active_Total_Gen_Export']*1000) : 0;
        $Energy_Current_Month = $Fetch_Result['Reactive_Total_Gen_Import'] ?? 0;
        $Energy_Current_Year = $Fetch_Result['Reactive_Total_Gen_Export'] ?? 0;
        $Energy_Previous_Hour = $Fetch_Result['Active_Gen1_Import'] ?? 0;
        $Energy_Previous_Day = $Fetch_Result['Active_Gen1_Export'] ?? 0;
        $Reactive_Gen1_Import = $Fetch_Result['Reactive_Gen1_Import'] ?? 0;
        $Reactive_Gen1_Export = $Fetch_Result['Reactive_Gen1_Export'] ?? 0;
        $Active_Gen2_Import = $Fetch_Result['Active_Gen2_Import'] ?? 0;
        $Active_Gen2_Export = isset($Fetch_Result['Active_Gen2_Export']) ? ($Fetch_Result['Active_Gen2_Export']*1000) : 0;
        $Reactive_Gen2_Import = $Fetch_Result['Reactive_Gen2_Import'] ?? 0;
        $Reactive_Gen2_Export = $Fetch_Result['Reactive_Gen2_Export'] ?? 0;
        $Gear_Box_Oil_Temp = $Fetch_Result['Control_Panel_Temp'] ?? 0;
        $Gear_Box_Bearing_Temp = $Fetch_Result['Gear_Bearing1_Temp'] ?? 0;
        $Gen_DE_Bearing_Temp = $Fetch_Result['Gear_Bearing2_Temp'] ?? 0;
        $Gen_NDE_Bearing_Temp = $Fetch_Result['Gear_Box_Oil_Temp'] ?? 0;
        $Nacelle_Temp = $Fetch_Result['Gen_Winding1_Temp'] ?? 0;
        $Ambient_Temp = $Fetch_Result['Gen_Winding2_Temp'] ?? 0;
        $Hu_Oil = $Fetch_Result['Gen_DE_Bearing_Temp'] ?? 0;
        $Winding_Txf_2 = $Fetch_Result['Gen_DE_NDE_Bearing_Temp'] ?? 0;
        $Winding_Txf_1 = $Fetch_Result['Nacelle_Temp'] ?? 0;
        $G1_Connected_Counts = $Fetch_Result['G1_Connected_Counts'] ?? 0;
        $G2_Connected_Counts = $Fetch_Result['G2_Connected_Counts'] ?? 0;
        $C_Rotor_Phase_3_Temp = $Fetch_Result['Total_Hours'] ?? 0;
        $Gen_Init_Date = $Fetch_Result['Gen_Init_Date'] ?? '';
        $Gen_Init_Time = $Fetch_Result['Gen_Init_Time'] ?? '';
        $Kwh_Positive = $Fetch_Result['Kwh_Positive'] ?? 0;
        $Bus_Bar_Temp = $Fetch_Result['Kwh_Negative'] ?? 0;
        $C_Rotor_Phase_1_Temp = $Fetch_Result['KVar_Positive'] ?? 0;
        $Bus_Bar_Temp = $Fetch_Result['KVar_Negative'] ?? $Bus_Bar_Temp;
        $Total_Hours = $Fetch_Result['Grid_failure_Hours'] ?? 0;
        $No_Service_Hours = $Fetch_Result['Stopped_Hours'] ?? 0;
        $LineOk_Hours = $Fetch_Result['Min3_Wind_Speed'] ?? 0;
        $TuriOk_Hours = $Fetch_Result['Min3_Wind_Dir'] ?? 0;
        $Run_Hours = $Fetch_Result['Min3_Active_Power'] ?? 0;
        $Min3_Active_Power = $Fetch_Result['Min3_Active_Power'] ?? 0;
        $T4_Temp = $Fetch_Result['Cable_Twist'] ?? 0;
        $Rotor_Inductor_Temp = $Fetch_Result['Nacelle_Position'] ?? 0;
        $Rphase_Current = $Fetch_Result['Rphase_Current'] ?? 0;
        $Yphase_Current = $Fetch_Result['Yphase_Current'] ?? 0;
        $Bphase_Current = $Fetch_Result['Bphase_Current'] ?? 0;
        $Power_factor = $Fetch_Result['Power_Factor'] ?? 0;
        $Status = str_replace('#','',$Fetch_Result['Status'] ?? '');
        $Date_F = $Fetch_Result['Date'] ?? '';
        $Time_F = $Fetch_Result['time'] ?? '';
        $lastRecd = str_replace('.','-',$Date_F);
    }
}

// --- Fetch customer/device register info (site, name etc.)
if ($IMEI_Decode) {
    $Fetch_Info = "SELECT a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location, a.SF_No, a.Capacity, a.Date_Of_Commission, a.Connect_Feeder, a.Device_Name
                   FROM device_register a
                   LEFT JOIN user_master b ON a.Account_ID = b.Account_ID
                   WHERE IMEI = '".$db->real_escape_string($IMEI_Decode)."' LIMIT 1";
    if ($Fetch_Info_Result = $db->query($Fetch_Info)) {
        if ($Fetch_Details_Result = $Fetch_Info_Result->fetch_assoc()) {
            $All_HTSC_No = $Fetch_Details_Result['HTSC_No'] ?? '';
            $All_LOC_No = $Fetch_Details_Result['LOC_No'] ?? '';
            $All_WEG_No = $Fetch_Details_Result['WEG_No'] ?? '';
            $All_Firstname = $Fetch_Details_Result['Firstname'] ?? '';
            $All_Devicename[1] = $Fetch_Details_Result['Device_Name'] ?? ($All_Devicename[1] ?? 'Unknown Device');
            $Site_Location = $Fetch_Details_Result['Site_Location'] ?? '';
            $SF_No = $Fetch_Details_Result['SF_No'] ?? '';
            $Date_Of_Commission = $Fetch_Details_Result['Date_Of_Commission'] ?? '';
            $Capacity = is_numeric($Fetch_Details_Result['Capacity']) ? floatval($Fetch_Details_Result['Capacity']) : $Capacity;
            $Connect_Feeder = $Fetch_Details_Result['Connect_Feeder'] ?? '';
        }
        $Fetch_Info_Result->free();
    }
}

/* ============================
   Machine Performance Calculation (small circular gauge)
   ============================ */
// Ensure $Capacity and $Active_Power are numeric and avoid division by zero.
$Machine_Performance = 0.0;
if (!empty($Capacity) && is_numeric($Capacity) && $Capacity > 0) {
    $Machine_Performance = ($Active_Power / $Capacity) * 100.0;
}
// clamp between 0 and 100 for display
$Machine_Performance = floatval($Machine_Performance);
if ($Machine_Performance < 0) $Machine_Performance = 0;
if ($Machine_Performance > 100) $Machine_Performance = 100;

// Prepare SVG circle math (radius and circumference)
$g_radius = 36; // radius of circle
$g_circumference = 2 * pi() * $g_radius;
$g_offset = $g_circumference * (1 - ($Machine_Performance / 100.0));

// Determine color
if ($Machine_Performance >= 80) {
    $g_color = '#20c997'; // green
} elseif ($Machine_Performance >= 40) {
    $g_color = '#ffb703'; // orange
} else {
    $g_color = '#e63946'; // red
}

/* ============================
   FETCH LAST 10 RECORDS
   ============================ */
$Last10 = [];

if ($Database_Name && $IMEI_Decode) {

    $safeDB = preg_replace('/[^a-zA-Z0-9_]/', '', $Database_Name);
    $safeIMEI = $db->real_escape_string($IMEI_Decode);

    // Try from device DB
    $sql10 = "SELECT Date, time, Windspeed, Power, Status , GRPM, RRPM , Reactive_Power , L_N_Voltage_R , L_N_Voltage_Y, L_N_Voltage_B , Rphase_Current , Yphase_Current, Bphase_Current , Frequency, Active_Total_Gen_Import, Active_Total_Gen_Export,Reactive_Total_Gen_Import ,Reactive_Total_Gen_Export , Active_Gen1_Import, Active_Gen1_Export, Active_Gen2_Import, Active_Gen2_Export, Reactive_Gen2_Import, Reactive_Gen2_Export, Control_Panel_Temp, Gear_Bearing1_Temp, Gear_Bearing2_Temp, Gear_Box_Oil_Temp ,Gen_Winding1_Temp ,Gen_Winding2_Temp ,Gen_DE_Bearing_Temp, Grid_failure_Hours, Stopped_Hours, Min3_Wind_Speed, Min3_Wind_Dir, Min3_Active_Power
              FROM `{$safeDB}`.`{$Table_Name}`
              WHERE IMEI = '{$safeIMEI}'
              ORDER BY Record_Index DESC
              LIMIT 10";
    $res10 = $db->query($sql10);

    // If device DB fails → try main DB
    if (!$res10 || $res10->num_rows == 0) {
        $sql10 = "SELECT Date, time, Windspeed, Power, Status , GRPM, RRPM, Reactive_Power , L_N_Voltage_R , L_N_Voltage_Y, L_N_Voltage_B , Rphase_Current , Yphase_Current, Bphase_Current , Frequency,  Active_Total_Gen_Import, Active_Total_Gen_Export,Reactive_Total_Gen_Import ,Reactive_Total_Gen_Export , Active_Gen1_Import, Active_Gen1_Export, Active_Gen2_Import, Active_Gen2_Export, Reactive_Gen2_Import, Reactive_Gen2_Export, Control_Panel_Temp, Gear_Bearing1_Temp, Gear_Bearing2_Temp, Gear_Box_Oil_Temp ,Gen_Winding1_Temp ,Gen_Winding2_Temp ,Gen_DE_Bearing_Temp, Grid_failure_Hours, Stopped_Hours, Min3_Wind_Speed, Min3_Wind_Dir, Min3_Active_Power
                  FROM `{$Table_Name}`
                  WHERE IMEI = '{$safeIMEI}'
                  ORDER BY Record_Index DESC
                  LIMIT 10";
        $res10 = $db->query($sql10);
    }

    if ($res10 && $res10->num_rows > 0) {
        while ($row = $res10->fetch_assoc()) {
            $Last10[] = $row;
        }
        // free result if possible
        if (is_object($res10)) $res10->free();
    }
}

// ------------------
// GAD logic: detect where daily_data table lives and compute safe values
// ------------------
    
$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
			if (!$Fetch_Info_Result = $db->query($Fetch_Info))
            {
                die($db->error);
            }
            if($Fetch_Info_Result->num_rows >= 1)
            {
				$x=1;
                while($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) 
				{
                      $All_HTSC_No[$x] = $Fetch_Details_Result['HTSC_No'];					
                      $All_LOC_No[$x] = $Fetch_Details_Result['LOC_No'];					
					  $All_WEG_No[$x] = $Fetch_Details_Result['WEG_No'];					
					  $All_Firstname[$x] = $Fetch_Details_Result['Firstname'];
					  $All_Devicename[$x] = $Fetch_Details_Result['Device_Name'];
					  $Site_Location[$x] = $Fetch_Details_Result['Site_Location'];
					  $SF_No[$x] = $Fetch_Details_Result['SF_No'];
					  $DOC[$x] = $Fetch_Details_Result['DOC'];
					  $Date_Of_Commission = $Fetch_Details_Result['Date_Of_Commission'];
					  $Capacity[$x] = $Fetch_Details_Result['Capacity'];
					  $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
					  $x++;
				}				
			}
    // GAD SQL: Gen1 + Gen2, safe summation; use SELECT ... AS
    $Mysql_Query_GAD = "select (select Gen1_Max from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate() limit 1) as GAD_Today,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) order by Record_Index desc limit 1) as GAD_Yesterday,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) order by Record_Index desc limit 1) as GAD_Thisweek,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) order by Record_Index desc limit 1) as GAD_Thismonth,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 AND YEAR( Date_S) = YEAR( curdate() ) order by Record_Index desc limit 1) as GAD_Previousweek";
//echo $Mysql_Query_GAD;
	if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD))
            {
                die($db->error);
            }

            if($Mysql_Query_Result_GAD->num_rows >= 1)
            {
                while($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) 
				{  			
					$GAD_Today = $Fetch_Result_GAD['GAD_Today'];
					$GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
					$GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
					$GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
					$GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];
				}
}
    
    
    


// Helper: small function to choose status class
function status_class($s) {
    $s = trim($s);
    $greens = ['Run','M/C Running','RUN','OperateG1','OperateG2','Operate_G1','Operate_G2','FreeWheelingG2','FreeWheelingG1','Operate G1','Operate G2'];
    $blues = ['Grid Drop','GridDrop'];
    if (in_array($s, $greens)) return 'status-green';
    if (in_array($s, $blues)) return 'status-blue';
    if ($s === '') return '';
    return 'status-red';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Device Dashboard — <?= htmlspecialchars($All_Devicename[1] ?? 'Device') ?></title>

<!-- Material style minimal CSS -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#f3f6fb;
  --card:#ffffff;
  --muted:#8b98a6;
  --accent:#0a66c2;
  --success:#20c997;
  --danger:#e63946;
  --info:#1e90ff;
  --surface-border: #e6eef6;
}
*{box-sizing:border-box;font-family:Inter, Arial, Helvetica, sans-serif}
body{margin:0;background:var(--bg);color:#123;line-height:1.35}
.container {
    width:100%;
    max-width:100%;
    margin:0;
    padding:0;
}

.header-row {display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}
.header-left {display:flex;align-items:center;gap:12px}
.header-title {font-size:20px;font-weight:700;color:var(--accent)}
.header-sub {color:var(--muted);font-size:13px}
.grid {
    display:grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap:18px;
}
@media(max-width:1200px){
    .grid { grid-template-columns:1fr 1fr; }
}
@media(max-width:700px){
    .grid { grid-template-columns:1fr; }
}

.card {background:var(--card);border-radius:12px;padding:18px;border:1px solid var(--surface-border);box-shadow:0 6px 18px rgba(15,30,60,0.04)}
.card .card-title {font-weight:700;color:#0b4561;margin-bottom:12px}
.row {display:flex;gap:10px;align-items:center}
.col {display:flex;flex-direction:column;gap:8px}

/* mini table styling */
.table {width:100%;border-collapse:collapse}
.table td {padding:8px 10px;border-bottom:1px dashed #eef4fb;background:transparent}
.table .label {width:48%;color:var(--muted);font-weight:600}
.table .value {width:52%;font-weight:700;color:#0b3b3b}

/* status badges */
.badge {display:inline-block;padding:6px 10px;border-radius:999px;font-weight:700;color:#fff;font-size:13px}
.status-green {background:var(--success)}
.status-blue {background:var(--info)}
.status-red {background:var(--danger)}

/* small utilities */
.kv {font-size:14px;color:#234; font-weight:700}
.small {font-size:13px;color:var(--muted)}
.panel-row {display:flex;gap:12px;flex-wrap:wrap}

/* iframe styling */
.iframe-sm {border:0;height:64px;width:420px;background:transparent;border-radius:8px}
.actions {display:flex;gap:10px;align-items:center}
.btn {padding:8px 12px;border-radius:8px;background:var(--accent);color:#fff;border:0;cursor:pointer;font-weight:700}
.btn-ghost {background:transparent;border:1px solid var(--surface-border);color:var(--accent)}
.export {font-size:13px;color:var(--muted)}

/* chart placeholder */
.chart-placeholder {height:300px;border-radius:8px;background:linear-gradient(90deg,#f7fbff,#eef6ff);display:flex;align-items:center;justify-content:center;color:var(--muted);font-weight:700}


/* responsive tweak for header */
.header-right {display:flex;align-items:center;gap:12px}
.header-capacity {font-size:13px;color:#244;color:var(--muted);font-weight:700}

/* small responsive table fix */
.table thead td {font-weight:700}

/* --- HORIZONTAL PERFORMANCE GAUGE --- */
.h-gauge-box {
    width: 220px;
    background: #e6eef6;
    border-radius: 12px;
    height: 16px;
    position: relative;
    overflow: hidden;
    border: 1px solid #d5e2ee;
}

.h-gauge-fill {
    height: 100%;
    border-radius: 12px;
    transition: width 0.6s ease;
}

.h-gauge-text {
    font-size: 14px;
    font-weight: 800;
    margin-top: 4px;
    text-align: center;
    color: #0b3b3b;
}


</style>
</head>
<body>

<div class="container">

    <div class="header-row">
        <div class="header-left">
            <div>
                <div class="header-title">
                    <?= htmlspecialchars($All_Devicename[1] ?? 'Unknown Device') ?>
                </div>
                <div class="header-sub">
                   <?php if (!empty($Capacity)): ?>
                       <?= htmlspecialchars(number_format($Capacity,2)) ?> kW
                    <?php endif; ?>
                </div>
            </div>
            <!-- Gauge placed adjacent to title -->
            <div style="margin-left:10px;">
    <div class="h-gauge-box">
        <div class="h-gauge-fill" 
             style="width: <?= $Machine_Performance ?>%; 
                    background: <?= $g_color ?>;">
        </div>
    </div>
    <div class="h-gauge-text"><?= htmlspecialchars("Preformance ") ?><?= number_format($Machine_Performance,1) ?>%</div>
</div>

        </div>

    <!-- TCP ICON -->
    <div class="header-right" style="display:flex;align-items:center;gap:14px;">

        <!-- TCP Request Icon -->
        <div id="openTcpModal" 
             style="cursor:pointer;
                    background:none;
                    width:42px;height:42px;
                    border-radius:8px;
                    display:flex;
                    align-items:center;
                    justify-content:center;">
            <img src="images/tcp_icon.png" width="22" alt="TCP">
        </div>

    </div>

    </div>

    <div class="grid">

        <!-- LEFT COLUMN -->
        <div>

<!-- LAST 10 RECORDS TABLE -->
<div class="card" style="margin-top:18px">
    

    <table class="table" style="width:100%;text-align:left;">
       <thead>

<!-- ===================== TOP HEADER ROW ===================== -->
<tr style="font-weight:700;background:#f0f5ff;text-align:center;">
    <td rowspan="2">DATE</td>
    <td rowspan="2">TIME</td>
    <td rowspan="2">GRPM</td>
    <td rowspan="2">RRPM</td>
    
    <td rowspan="2">WIND</td>
    <td rowspan="2">POWER</td>
	<td rowspan="2">KVAR</td>
	<td rowspan="2">STATUS</td>
    <!-- Voltage Main Header (3 columns) -->
    <td colspan="3">VOLTAGE</td>

    <!-- Current Main Header (3 columns) -->
    <td colspan="3">Amps</td>

    <td rowspan="2">FREQ</td>
	
	<td colspan="4">Current</td>
	
	<td colspan="6">Previous</td>

	<td colspan="6">Temperatures</td>
	
	<td colspan="6">Hours</td>
    

    
</tr>

<!-- ===================== SUB HEADER ROW ===================== -->
<tr style="background:#f8fbff;text-align:center;">
    <!-- VOLTAGE -->
    <td>R</td>
    <td>Y</td>
    <td>B</td>

    <!-- CURRENT -->
    <td>R</td>
    <td>Y</td>
    <td>B</td>
	
	<!-- Produced Energy -->
	 <td>Hour Kwh</td>
    <td>Day Kwh</td>
    <td>Month  Kwh</td>
	<td>Year Mwh</td>
	
	
	<!-- Produced Energy -->
	 <td>Hour Kwh</td>
    <td>Day Kwh</td>
    <td>Month Kwh</td>
	<td>Year Mwh</td>
	
	<!-- Temperarure -->
	<td>G.OIL</td>
    <td>G.BEAR</td>
    <td>G.DE</td>
    <td>G.NDE</td>
    <td>NACL</td>
    <td>AMB</td>
    <td>H.OIL</td>
	
	<td>TOTAL</td>
    <td>NO SRV</td>
    <td>LINE OK</td>
    <td>TUR OK</td>
    <td>RUN</td>
</tr>

</thead>

        <tbody>
        <?php if (!empty($Last10)): ?>
            <?php foreach ($Last10 as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['Date'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['time'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['GRPM'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['RRPM'] ?? '') ?></td>
                    <td><?= htmlspecialchars(str_replace("m/s","",$r['Windspeed'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($r['Power'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Reactive_Power'] ?? '') ?></td>
                    <td><span class="badge <?= status_class($r['Status'] ?? '') ?>"><?= htmlspecialchars($r['Status'] ?? '') ?></span></td>
                    <td><?= htmlspecialchars($r['L_N_Voltage_R'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['L_N_Voltage_Y'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['L_N_Voltage_B'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Rphase_Current'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Yphase_Current'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Bphase_Current'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Frequency'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Active_Total_Gen_Import'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Active_Total_Gen_Export'] *1000 ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Reactive_Total_Gen_Import']*1000 ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Reactive_Total_Gen_Export'] ?? '') ?></td>
                    
                    <td><?= htmlspecialchars($r['Active_Gen2_Import'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Active_Gen2_Export'] *1000 ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Reactive_Gen2_Import']*1000 ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Reactive_Gen2_Export'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Control_Panel_Temp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Gear_Bearing1_Temp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Gear_Bearing2_Temp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Gear_Box_Oil_Temp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Gen_Winding1_Temp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Gen_Winding2_Temp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Gen_DE_Bearing_Temp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Grid_failure_Hours'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Stopped_Hours'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Min3_Wind_Speed'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Min3_Wind_Dir'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['Min3_Active_Power'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="37" style="text-align:center;color:gray;padding:12px;">
                    No Records Found
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

            <!-- GAD DETAILS CARD -->
            <div class="card" style="margin-top:14px">
                <div class="card-title">GAD Details</div>
                <table class="table">
                    <tr><td class="label">GAD for Today</td><td class="value"><?= number_format($GAD_Today,2) ?> kWh</td></tr>
                    <tr><td class="label">GAD for Yesterday</td><td class="value"><?= number_format($GAD_Yesterday,2) ?> kWh</td></tr>
                    <tr><td class="label">GAD for Current Month</td><td class="value"><?= number_format($GAD_Thismonth,2) ?> kWh</td></tr>
                    <tr><td class="label">GAD for Current Year</td><td class="value"><?= number_format($GAD_ThisYear,2) ?> kWh</td></tr>
                </table>
            </div>

        </div> <!-- end left column -->

       

    </div> <!-- end grid -->

    <!-- bottom iframes -->
    <div style="margin-top:18px" class="card">
        
        <div style="display:flex;flex-direction:column;gap:12px">
            <iframe style="width:100%;height:300px;border:1px solid #dbeaf6;border-radius:8px" src="channel8_renom1_ajax.php?c1=<?=urlencode($_REQUEST['c1'] ?? '')?>&l=<?=urlencode($_REQUEST['l'] ?? '')?>&FType=<?=urlencode($_REQUEST['FType'] ?? '')?>"></iframe>

            <div style="display:flex;gap:8px;flex-wrap:wrap">
                <iframe style="width:49%;height:300px;border:1px solid #dbeaf6;border-radius:8px" src="Power_Windspeed_chart_Monthly_iframe.php?c1=<?=urlencode($_REQUEST['c1'] ?? '')?>&Year=<?=date('m-Y')?>&l=<?=urlencode($_REQUEST['l'] ?? '')?>"></iframe>
                <iframe style="width:49%;height:300px;border:1px solid #dbeaf6;border-radius:8px" src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?=urlencode($_REQUEST['c1'] ?? '')?>&l=<?=urlencode($_REQUEST['l'] ?? '')?>&FType=<?=urlencode($_REQUEST['FType'] ?? '')?>"></iframe>
            </div>
        </div>
    </div>

</div> <!-- container -->

<!-- TCP REQUEST MODAL -->
<div id="tcpModal" 
     style="display:none;
            position:fixed;
            top:0;left:0;
            width:100%;height:100%;
            background:rgba(0,0,0,0.45);
            z-index:9999;
            padding-top:90px;">

    <div style="margin:auto;
                background:white;
                width:380px;
                border-radius:12px;
                padding:22px;
                box-shadow:0 8px 30px rgba(0,0,0,0.25);">

        <h3 style="margin-top:0;color:#0a66c2;">TCP Request</h3>

        <iframe src="TcpRequest.php?c1=<?=urlencode($_REQUEST['c1'] ?? '')?>&db=<?=urlencode($Database_Name ?? '')?>"
                style="width:100%;height:260px;border:0;"></iframe>

        <button id="closeTcpModal"
                style="margin-top:12px;
                       width:100%;
                       padding:10px;
                       background:#0a66c2;
                       border:0;
                       color:white;
                       border-radius:8px;
                       font-size:15px;
                       cursor:pointer;">
            Close
        </button>

    </div>
</div>

<script>
document.getElementById("openTcpModal").onclick = function() {
    document.getElementById("tcpModal").style.display = "block";
};
document.getElementById("closeTcpModal").onclick = function() {
    document.getElementById("tcpModal").style.display = "none";
};

// Disable right click
document.addEventListener('contextmenu', event => event.preventDefault());

// Disable common inspect keys
document.onkeydown = function(e) {
    if (e.keyCode == 123) { // F12
        return false;
    }
    if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 67 || e.keyCode == 74)) {
        return false;
    }
    if (e.ctrlKey && e.keyCode == 85) { // Ctrl+U
        return false;
    }
};
</script>

<?php include("footer.php"); ?>

</body>
</html>
