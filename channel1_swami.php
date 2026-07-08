<?php
// new_responsive_material_channel1.php
// Full responsive Material UI rewrite (Option A)
// NOTE: Backend logic preserved from original file; UI replaced with responsive grid/cards

include("header_inner.php");
error_reporting(0);
if (empty($_COOKIE[$Cook_Name])) {
    header("Location: index.php");
    exit;
}

/* ---------------------------
   Preserve original server-side logic & queries
   (I kept the SQL logic and variable names exactly as in your file)
   --------------------------- */

// ----- Load error_type table into arrays -----
$Mysql_Query = "select * from error_type";
if (!$queryResult = $db->query($Mysql_Query)) {
    die($db->error);
}
if ($queryResult->num_rows >= 1) {
    while ($Fetch_Result2 = $queryResult->fetch_array()) {
        $Error_Array[$Fetch_Result2['Machine_Status']][] = $Fetch_Result2['Error'];
        $Machine_Status_Array[$Fetch_Result2['Machine_Status']] = $Fetch_Result2['Machine_Status'];
    }
}

// Initialize counters and arrays
$Audio = array();
$td = 0;
$tr = 0;
$CurrentState = "";
$Total_Power = 0;
$CurrentSite = "";
$Total_Export = 0;
$WTG_Run = 0;

/* The main device query stays exactly the same as your original; I'm using it below.
   We'll iterate through devices and render them into Material-style cards. */

// We'll fetch devices (same logic as original) - keep same conditional branches
$Date_Range = getDaysInBetween(date("d-m-Y"), date("d-m-Y"));
foreach ($Date_Range as $Date_Range_Val) {
    $Date_Range_Start = $Date_Range_Val[0];
    $Date_Range_End = $Date_Range_Val[1];
}

// Build the device query depending on user type
if ($User_Type_ID == 3 || $User_Type_ID == 2) {
    $Mysql_Query2 = "SELECT  t1.*, s.totalCount AS count 
        FROM  device_register AS t1 
         LEFT JOIN
                (
                SELECT Device_Index,State, COUNT(State)  totalCount
                 FROM  device_register 
                 WHERE  Parent_ID = '" . $Account_ID . "'
                  GROUP   BY State
     )  s ON s.State = t1.State   where t1.Parent_ID = '" . $Account_ID . "'
ORDER   BY count desc,State desc, Device_Order asc";
} elseif ($User_Type_ID == 4) {
    $Mysql_Query2 = "SELECT  t1.*, s.totalCount AS count 
        FROM  device_register AS t1 
         LEFT JOIN
                (
                SELECT Device_Index,State, COUNT(State)  totalCount
                 FROM  device_register 
                 WHERE  Account_ID = '" . $Account_ID . "'
                  GROUP   BY State
     )  s ON s.State = t1.State   where t1.Account_ID = '" . $Account_ID . "'
ORDER   BY count desc,State desc,Device_Order asc";
} else {
    // Fallback: fetch nothing (mirrors behavior of original when not matching user types)
    $Mysql_Query2 = "SELECT  t1.*, 0 AS count FROM device_register AS t1 WHERE 1=0";
}

if (!$queryResult2 = $db->query($Mysql_Query2)) {
    die($db->error);
}
$Mysql_Record_Count = $queryResult2->num_rows;

/* ---------------------------
   FRONTEND: Material UI style HTML + CSS + JS
   --------------------------- */
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SCADA Dashboard - Material UI Style</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<!-- Use a single jQuery include -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="" crossorigin="anonymous"></script>

<style>
/* Material-like base */
:root{
    --primary:#1976d2;
    --surface:#ffffff;
    --muted:#f5f7fa;
    --card-shadow: 0 6px 20px rgba(32,33,36,0.08);
    --chip-radius: 18px;
    --gap:14px;
}

*{box-sizing:border-box}
body{
    font-family: 'Roboto', sans-serif;
    background: var(--muted);
    margin:0;
    color:#111;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
}

/* Page wrapper responsive */
.page-wrapper{
    width:100%;
    max-width:100%;
    margin:0;
    padding:0 4px;  /* little breathing room */
}


/* Top bar card */
.top-card{
    background:var(--surface);
    border-radius:12px;
    padding:14px;
    box-shadow:var(--card-shadow);
    display:flex;
    gap:12px;
    align-items:center;
    flex-wrap:wrap;
}

/* Title */
.page-title{
    display:flex;
    gap:10px;
    align-items:center;
    font-weight:600;
    font-size:20px;
}

/* Status legend */
.status-legend{
    display:flex;
    gap:10px;
    margin-left:auto;
    align-items:center;
    flex-wrap:wrap;
}

.legend-item{
    display:flex;
    gap:8px;
    align-items:center;
    font-size:14px;
    color:#333;
}

/* Material chips */
.chip{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:6px 10px;
    border-radius:var(--chip-radius);
    font-weight:500;
    font-size:13px;
}

/* color chips (text colored, no background) */
.chip.green{ color: #ffffff; }
.chip.orange{ color: #ffffff; }
.chip.red{ color: #ffffff; }
.chip.blue{ color: #ffffff; }
.chip.gray{ color: #616161; }

/* grid of turbine cards  color: #1565c0;*/
.cards-wrapper{
    margin-top:14px;
}

.grid{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(260px,1fr));
    gap:16px;
}

/* single turbine card */
.card{
    background:var(--surface);
    border-radius:12px;
    padding:12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transition: transform .16s ease, box-shadow .16s ease;
    display:flex;
    flex-direction:column;
    gap:8px;
}

.card:hover{ transform: translateY(-6px); box-shadow: 0 10px 28px rgba(0,0,0,0.10); }

/* tower image area */
.tower-area{ text-align:center; }
.tower-area img{ max-width:80px; height:auto; display:inline-block; }

/* device meta */
.device-meta{ display:flex; gap:8px; justify-content:space-between; align-items:center; }
.device-meta .dev-name{ font-weight:600; color:#222; font-size:15px; }

/* info table style */
.info-table{ width:100%; border-collapse:collapse; font-size:13px; color:#333; }
.info-table td{ padding:6px 4px; vertical-align:top; }
.info-table .label{ font-weight:700; width:40%; color:#444; }
.info-table .value{ font-weight:500; color:#111; }

/* small helper for footer numbers */
.card-stats{ display:flex; gap:10px; justify-content:space-between; align-items:center; margin-top:6px; font-size:13px; color:#333; }

/* marquee banner (responsive) */
.banner{
    margin-top:14px;
    background: linear-gradient(90deg,#e3f2fd, #fff);
    border-radius:10px;
    padding:10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    overflow:hidden;
}

/* responsive tweaks */
@media (max-width:720px){
    .status-legend{ margin-left:0; }
    .page-title{ font-size:18px; }
}
.device-row {
    display: flex;
    gap: 14px;
}

.left-img {
    width: 90px;
    text-align: center;
}

.left-img img {
    width: 70px;
    height: auto;
}

.datetime {
    font-size: 12px;
    color: #666;
    margin-top: 6px;
}

.right-values {
    flex: 1;
}

.right-values .dev-name {
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 4px;
}

.chip.pink {
    background-color: #ff4db8;   /* Bright pink */
    color: #fff;
    border: 2px solid #d6008a;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}


</style>

<!-- Simple JS: live refresh and audio handling -->
<script>
$(document).ready(function(){

    // Live AJAX refresh every 40 seconds (replaces your old setInterval code)
    function refreshData(){
        $("#cards-area").load(location.pathname + " #cards-area > *", function(){
            // After data reload, you could re-run any client-side initialization here
            // For example, start audio if audio elements exist
            var audio = document.getElementById('ctrlaudio');
            if(audio && audio.paused && audio.dataset.autoplay == "1"){ try{ audio.play(); }catch(e){} }
        });
    }

    // initial refresh not required, but set interval
    setInterval(refreshData, 40000);

});

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

</head>
<body>

<div class="page-wrapper">

    <!-- Top card with title and legend -->
    <div class="top-card">
        <div class="page-title">
            <?php
                
            ?>
            <span style="font-size:22px; font-weight:700; color:#298;">
    <?= htmlspecialchars($Firstname . " " . $Lastname) ?>
</span>
<div style="margin-left:auto; display:flex; gap:8px; align-items:center; font-size:14px;">
            <span style="color:#666;">Last updated:</span>
            <span style="font-weight:600; color:#111;"><?= date('d-m-Y H:i:s') ?></span>
        </div>
        </div>

        
         <div class="status-legend" aria-hidden="true">
    <div class="legend-item">
        <span class="chip orange"><img src="images/11.jpg" style="width:18px;height:18px" alt=""> Null Wind</span>
    </div>

    <div class="legend-item">
        <span class="chip green"><img src="images/12.jpg" style="width:18px;height:18px" alt=""> WTG Run</span>
    </div>

    <div class="legend-item">
        <span class="chip red"><img src="images/Red_jpg.jpg" style="width:18px;height:18px" alt=""> Error Stop</span>
    </div>

    <div class="legend-item">
        <span class="chip blue"><img src="images/Blue_jpg.jpg" style="width:18px;height:18px" alt=""> Grid Drop</span>
    </div>

    <div class="legend-item">
    <span class="chip pink">
        <img src="images/18.jpg" style="width:18px;height:18px" alt=""> Key Pressed
    </span>
</div>


    <div class="legend-item">
        <span class="chip grey"><img src="images/Grey_jpg.jpg" style="width:18px;height:18px" alt=""> No Communication</span>
    </div>
</div>
    </div>

    <!-- Controls card (optional behavior: Full screen button) -->
    

    <!-- Cards area (this div will be AJAX-updated by refreshing the inner content) -->
    <div class="cards-wrapper">
        <div id="cards-area">
            <div class="grid">
                <?php
                /* ---------------------------
                   Original device loop is here.
                   I preserved all data processing and variables, but replaced the heavy nested tables
                   with Material-style cards. Please verify images exist.
                   --------------------------- */

                if ($queryResult2->num_rows >= 1) {
                    // reset counters that were used originally
                    $td = 0;

                    // iterate devices (original loop contents preserved)
                    while ($Fetch_Result = $queryResult2->fetch_array()) {

                        $IMEI = base64_encode($Fetch_Result['IMEI']);
                        $HTSCno = $Fetch_Result['HTSC_No'];
                        $WEGno[$Fetch_Result['IMEI']] = $Fetch_Result['WEG_No'];
                        $State[$Fetch_Result['IMEI']] = $Fetch_Result['State'];
                        $Site_Location[$Fetch_Result['IMEI']] = $Fetch_Result['Site_Location'];
                        $Device_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
                        $Device = $Fetch_Result['Device_Name'];
                        $Format_Type = $Fetch_Result['Format_Type'];
                        $Pocket_Length = $Fetch_Result['Pocket_Length'];
                        $Connect_Feeder[$Fetch_Result['Site_Location']] = $Fetch_Result['Connect_Feeder'];
                        $Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
                        $Capacity = $Fetch_Result['Capacity'];

                        // Determine channel URL and table names based on Format_Type (preserved)
                        if ($Format_Type == 1) {
                            $Channel_Url = "channel2.php?";
                            $Table_Name = "device_data";
                            $Error_Table_Name = "error_data";
                        } elseif ($Format_Type == 2) {
                            if ($Device == 'Selva Tex 250kw') {
                                $Channel_Url = "channel3_selvatex.php?";
                                $Table_Name = "device_data_f2";
                                $Error_Table_Name = "error_data_f2";
                            } elseif ($Account_ID == '100215') {
                                $Channel_Url = "channel3_ucal.php?";
                                $Table_Name = "device_data_f2";
                                $Error_Table_Name = "error_data_f2";
                            } else {
                                $Channel_Url = "channel3.php?";
                                $Table_Name = "device_data_f2";
                                $Error_Table_Name = "error_data_f2";
                            }
                        } elseif ($Format_Type == 3) {
                            $Channel_Url = "channel4.php?";
                            $Table_Name = "device_data_f3";
                            $Error_Table_Name = "error_data_f3";
                        } elseif ($Format_Type == 4) {
                            if ($Device == 'Aspire') {
                                $Channel_Url = "channel9_new.php?";
                                $Table_Name = "device_data_f4";
                                $Error_Table_Name = "error_data_f4";
                            } else {
                                $Channel_Url = "channel5.php?";
                                $Table_Name = "device_data_f4";
                                $Error_Table_Name = "error_data_f4";
                            }
                        } elseif ($Format_Type == 6) {
                            if ($Database_Name == 'va_siva')
                                $Channel_Url = "channel7_old.php?";
                            elseif ($Database_Name == 'va_dhanalakshmi')
                                $Channel_Url = "channel7_kvarh.php?";
                            else
                                $Channel_Url = "channel7.php?";
                            $Table_Name = "device_data_f6";
                            $Error_Table_Name = "error_data_f6";
                        } elseif ($Format_Type == 7) {
                            if ($Device == 'ICE MAN') {
                                $Channel_Url = "channel1_iceman.php?";
                                $Table_Name = "device_data_f7";
                                $Error_Table_Name = "error_data_f7";
                            } elseif ($Device == 'Aalayam S826' || $Device == 'Aalayam S824' || $Device == 'Aalayam S792') {
                                $Channel_Url = "channel8_aalayam.php?";
                                $Table_Name = "device_data_f7";
                                $Error_Table_Name = "error_data_f7";
                            } elseif ($Device == 'KP Tex2' || $Device == 'SCM Green Power PVT LTD/SKTM 2') {
                                $Channel_Url = "channel8_gamesa.php?";
                                $Table_Name = "device_data_f7";
                                $Error_Table_Name = "error_data_f7";
                            } elseif ($Database_Name == 'va_renom') {
                                $Channel_Url = "channel8_renom1.php?";
                                $Table_Name = "device_data_f7";
                                $Error_Table_Name = "error_data_f7";
                            } elseif ($Database_Name == 'va_swami') {
                                $Channel_Url = "channel8_swami.php?";
                                $Table_Name = "device_data_f7";
                                $Error_Table_Name = "error_data_f7";
                            } else {
                                $Channel_Url = "channel8.php?";
                                $Table_Name = "device_data_f7";
                                $Error_Table_Name = "error_data_f7";
                            }
                        } elseif ($Format_Type == 8) {
                            $Channel_Url = "channel8.php?";
                            $Table_Name = "device_data_f8";
                            $Error_Table_Name = "error_data_f8";
                        } elseif ($Format_Type == 9) {
                            $Channel_Url = "channel9new.php?";
                            $Table_Name = "device_data_f9";
                            $Error_Table_Name = "error_data_f9";
                        } elseif ($Format_Type == 10) {
                            $Channel_Url = "channel10.php?";
                            $Table_Name = "device_data_f10";
                            $Error_Table_Name = "error_data_f10";
                        } else {
                            $Channel_Url = "channel8.php?";
                            $Table_Name = "device_data_f7";
                            $Error_Table_Name = "error_data_f7";
                        }

                        // decode for querying history
                        $IMEI_Decode = base64_decode($IMEI);
                        $From_Mon_D_Epoch = "01-" . date("m-Y");
                        $From_Mon_D_Epoch = strtotime($From_Mon_D_Epoch) + (60 * 60 * 5.5);
                        $To_Mon_D_Epoch = date("d-m-Y");
                        $To_Mon_D_Epoch = strtotime($To_Mon_D_Epoch . " 23:59:59") + (60 * 60 * 5.5);

                        // choose the correct latest-record query (preserved)
                        if ($Format_Type == 2)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, devicedata,((Gen1_Max - Gen1_Min)+(Gen2_Max - Gen2_Min)) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        elseif ($Format_Type == 4)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, ((Gen1_Max - Gen1_Min)+(Gen2_Max - Gen2_Min)) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        elseif ($Format_Type == 1)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen2_Max - Gen2_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        elseif ($Format_Type == 6)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, devicedata, (Gen2_Max - Gen2_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2  from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        elseif ($Format_Type == 10)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        elseif ($Format_Type == 3)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        elseif ($Format_Type == 7) {
                            if ($Database_Name == 'va_aalayam') {
                                $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                            } elseif ($Database_Name == 'va_gwind') {
                                $Mysql_Query1 = "select Date_S,Time_S, windspeed as WindSpeed, Power, Status, Gen_Init_Date as G1, Tip_Pressure as G2 from va_gwind.device_data_f7 where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";
                            } elseif ($Database_Name == 'va_renom') {
                                $Mysql_Query1 = "select Date_S,Time_S, windspeed as WindSpeed, Power, Status, Active_Total_Gen_Export as G1 from va_renom.device_data_f7 where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";
                            } elseif ($Database_Name == 'va_swami') {
                                $Mysql_Query1 = "select Date_S,Time_S, windspeed as WindSpeed, Power, Status, (SELECT Reactive_Total_Gen_Export from va_swami.device_data_f7 where IMEI='" . $IMEI_Decode . "'  and Date_S= curdate() ORDER BY Record_Index Limit 1) as G1_Min,(SELECT Reactive_Total_Gen_Export from va_swami.device_data_f7 where IMEI='" . $IMEI_Decode . "'  and Date_S= curdate() ORDER BY Record_Index desc Limit 1) as G1_Max from va_swami.device_data_f7 where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";
                            } else {
                                $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, Gen1_Hours_Max as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                            }
                        } elseif ($Format_Type == 8)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, Gen1_Hours_Max as G2 from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        elseif ($Format_Type == 9)
                            $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, (Gen1_Hours_Max-Gen2_Max) as G2, Gen2_Hours_Max as Stop from va_master.device_register where IMEI = '" . $IMEI_Decode . "' order by IMEI desc limit 1";
                        else
                            $Mysql_Query1 = "select Date_S,Time_S,WindSpeed,Power,Status from $Database_Name.$Table_Name where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";

                        if (!$queryResult1 = $db->query($Mysql_Query1)) {
                            die($db->error);
                        }

                        if ($queryResult1->num_rows >= 0) {
                            $Fetch_Result1 = $queryResult1->fetch_array();
                            if ($Format_Type == 7) {
                                $G1 = round(($Fetch_Result1['G1_Max'] - $Fetch_Result1['G1_Min']) * 1000, 2);
                            } else {
                                $G1 = ($Format_Type != 7 ? round($Fetch_Result1['G1']) : $Fetch_Result1['G1'] * 1000);
                            }

                            $devicedata = explode(',', $Fetch_Result1['devicedata']);
                            $G4_Temp = isset($devicedata[13]) ? $devicedata[13] : '';
                            $G6_Temp = isset($devicedata[15]) ? $devicedata[15] : '';
                            $G3 = isset($devicedata[19]) ? $devicedata[19] : '';
                            $Gvarh = isset($devicedata[27]) ? $devicedata[27] : '';
                            $GRPM = isset($devicedata[6]) ? $devicedata[6] : '';
                            $RRPM = isset($devicedata[7]) ? $devicedata[7] : '';
                            $WindSpeed = isset($Fetch_Result1['WindSpeed']) ? $Fetch_Result1['WindSpeed'] : null;
                            $WindSpeed = str_replace('m/s', '', $WindSpeed);
                            $WindSpeed = ($WindSpeed != '' ? number_format($WindSpeed, 2) : '0.00');
                            $Power = isset($Fetch_Result1['Power']) ? $Fetch_Result1['Power'] : null;
                            $Power = ($Power != '' ? number_format($Power, 2) : '0.00');
                            $Status1 = trim($Fetch_Result1['Status']);
                            $Status = strtolower($Status1);

                            if ($Device_Name[$IMEI_Decode] == 'Selvam 11-750kw' && $Status == 'emergency line fault') {
                                $Status = 'run';
                            }

                            if ($G1 > 25000 || $G1 < 0) {
                                $G1 = '0';
                            }
                            if ($G2 > 24 || $G2 < 0) {
                                $G2 = '0';
                            }

                            if ($Format_Type == 9) {
                                $Stop = isset($Fetch_Result1['Stop']) ? $Fetch_Result1['Stop'] : 0;
                            }

                            $Total_Export += $G1;
                            $Total_Power += floatval(str_replace(',', '', $Power));
                            $Date_F = isset($Fetch_Result1['Date_S']) ? $Fetch_Result1['Date_S'] : '';
                            $Time_F = isset($Fetch_Result1['Time_S']) ? $Fetch_Result1['Time_S'] : '';

                            $Device_Epoch_Time = GetTimestamp($Date_F, $Time_F);

                            if (!empty($Device_Epoch_Time)) {
                                $Diff_Error_Status = $Device_Epoch_Time;
                            }

                            $Req_Time = time() + (60 * 60 * 5.5);
                            $ReqTime_Diff = $Req_Time - $Device_Epoch_Time;

                            // Decide tower image based on status & error arrays (existing logic preserved)
                            if ($ReqTime_Diff >= 1800 && (in_array($Status, $Error_Array['Green']) && !in_array($Status, $Error_Array['Blue']))) {
                                $Tower_Img = '<img src="./images/Grey_jpg.jpg" width="59px" height="108" alt="brown Tower">';
                            } else {
                                if (in_array($Status, $Error_Array['Green'])) {
                                    if ($Power == '000' || $Power == '0' || $Power < 0) {
                                        $WTG_Run++;
                                        $Tower_Img = '<img src="./images/7.gif" width="59px" height="108px" alt="Orange Tower">';
                                    } else {
                                        $WTG_Run++;
                                        $Tower_Img = '<img src="./images/6.gif" width="59px" height="108px" alt="Green Tower">';
                                    }
                                } elseif (in_array($Status, $Error_Array['Orange'])) {
                                    $Tower_Img = '<img src="./images/7.gif" width="59px" height="108px" alt="Orange Tower">';
                                } elseif (in_array($Status, $Error_Array['Blue'])) {
                                    $Tower_Img = '<img src="./images/Blue_jpg.jpg" width="59px" height="108px" alt="Blue Tower">';
                                    $Audio[] = $WEGno[$IMEI_Decode];
                                } elseif (in_array($Status, $Error_Array['Pink'])) {
                                    $Tower_Img = '<img src="./images/18.jpg" width="59px" height="108px" alt="Pink Tower">';
                                    $Audio[] = $WEGno[$IMEI_Decode];
                                } else {
                                    $Tower_Img = '<img src="./images/Red_jpg.jpg" width="59px" height="108px" alt="Red Tower">';
                                    $Audio[] = $WEGno[$IMEI_Decode];
                                }
                            }

                            // Compute Date and Time string to display (preserved logic)
                            $Date_G = strtotime($Date_F);
                            $Time_G = strtotime($Time_F);
                            if (in_array($Status, $Error_Array['Green'])) {
                                $Date = date('d/m/Y', $Date_G);
                                $Time = date('H:i:s', $Time_G);
                            } elseif (in_array($Status, $Error_Array['Blue']) || in_array($Status, $Error_Array['Pink'])) {
                                $Date = date('d/m/Y', $Date_G);
                                $Time = date('H:i:s', $Time_G);
                            } else {
                                $Date = date('d/m/Y', $Date_G);
                                $Time = date('H:i:s', $Time_G);
                            }

                            // ---- Determine chip color class for the textual status label ----
                            $status_upper = strtoupper($Status);
                            $chipClass = 'gray';
                            if ($status_upper === 'RUN' || $status_upper === 'RUNNING') $chipClass = 'green';
                            elseif ($status_upper === 'PAUSE' || $status_upper === 'PAUSED') $chipClass = 'orange';
                            elseif ($status_upper === 'ERROR' || $status_upper === 'ERROR STOP' || $status_upper === 'ERROR_STOP') $chipClass = 'red';
                            elseif ($status_upper === 'GRIDDROP' || $status_upper === 'GRID DROP' || in_array($Status, $Error_Array['Blue'])) $chipClass = 'blue';

                            // Build Channel detail URL for anchor (preserve existing link)
                            $channelHref = $Channel_Url . "c1=" . $IMEI . "&l=" . $Pocket_Length . "&FType=" . $Format_Type;
                            ?>

                            <!-- Card: single device -->
                            <div class="card" role="article" aria-label="<?= htmlspecialchars($Device_Name[$IMEI_Decode]) ?>">

    <div class="device-row">

        <!-- LEFT SIDE : Tower Image -->
        <div class="left-img">
            <?php if ($Account_ID != '100146') { ?>
                <a href="<?= htmlspecialchars($channelHref) ?>"
                   title="IMEI: <?= htmlspecialchars($IMEI_Decode) ?>"
                   target="_blank"
                   style="text-decoration:none; color:inherit;">
                    <?= $Tower_Img ?>
                </a>
            <?php } else { echo $Tower_Img; } ?>

            <div class="datetime">
                <?= $Date ?> • <?= $Time ?>
            </div>
        </div>

        <!-- RIGHT SIDE : All Device Values -->
        <div class="right-values">

            <!-- Device Name -->
            <div class="dev-name"><?= htmlspecialchars($Device_Name[$IMEI_Decode]) ?></div>

           

            <table class="info-table">
                <tr>
                    <td class="label">HTSC No</td>
                    <td class="value"><?= htmlspecialchars($HTSCno) ?></td>
                </tr>
                <tr>
                    <td class="label">Speed</td>
                    <td class="value"><?= htmlspecialchars($WindSpeed) ?> m/s</td>
                </tr>
                <tr>
                    <td class="label">Power</td>
                    <td class="value"><?= htmlspecialchars($Power) ?> kW</td>
                </tr>
                <tr>
                    <td class="label">LOC</td>
                    <td class="value"><?= htmlspecialchars($Site_Location[$IMEI_Decode]) ?></td>
                </tr>
                <tr>
                    <td class="label">Gen.Daily</td>
                    <td class="value">
                        <?= ($G1 != '' ? htmlspecialchars($G1) : '0') ?> kwh /
                        <?= ($G2 != '' ? htmlspecialchars($G2) : '0') ?> h
                    </td>
                </tr>
            </table>

        </div>
    </div>

</div>

                            <!-- end device card -->

                            <?php
                            // increment and continue (keeps original layout flow semantics)
                            $td++;
                            if ($td == 6) {
                                $td = 0;
                            }
                        } // end if queryResult1
                    } // end while devices
                } else {
                    // no devices
                    ?>
                    <div style="grid-column:1/-1; padding:40px; background:#fff; border-radius:12px; box-shadow:var(--card-shadow); text-align:center;">
                        <h2 style="margin:0;">Machine not yet Installed...</h2>
                    </div>
                    <?php
                }
                ?>
            </div> <!-- end grid -->
        </div> <!-- end cards-area -->
    </div> <!-- end cards-wrapper -->

    <!-- Audio + audio list logic (preserved, but simplified) -->
    <?php
    if (!empty($Audio)) {
        // convert to file paths
        function arrayPrefix2(&$value, $key)
        {
            $value = "Music/" . $value . ".wav";
        }
        array_walk($Audio, "arrayPrefix2");
        $Audio_Str = implode(",", $Audio);
    ?>
        <audio id="ctrlaudio" controls autoplay data-autoplay="1" style="display:none;">
            <source src="<?= htmlspecialchars($Audio[0]) ?>" type="audio/wav">
            Your browser does not support the audio element.
        </audio>
        <input type="hidden" id="hdnSongNames" value="<?= htmlspecialchars($Audio_Str) ?>">
        <script>
            // Basic autoplay playlist rotation (keeps your original idea)
            (function () {
                var audio = document.getElementById('ctrlaudio');
                var songNames = document.getElementById('hdnSongNames').value.split(',');
                var cur = 0;
                if (!audio) return;
                audio.addEventListener('ended', function () {
                    cur = (cur + 1) % songNames.length;
                    audio.src = songNames[cur];
                    audio.load();
                    audio.play();
                });
            })();
        </script>
    <?php } ?>

    <!-- Bottom banner (marquee replacement) -->
    <div class="banner" role="region" aria-label="summary banner">
        <div style="white-space:nowrap; overflow:hidden;">
            <div id="ticker" style="display:inline-block; padding-left:100%; animation: scroll-left 22s linear infinite;">
                <strong>Total Power:</strong> <?= htmlspecialchars($Total_Power) ?> KW,
                <strong>Total Export:</strong> <?= htmlspecialchars($Total_Export) ?> kWh,
                <strong>WTG Run:</strong> <?= htmlspecialchars($WTG_Run) . "/" . htmlspecialchars($Mysql_Record_Count) ?>
            </div>
        </div>
    </div>

    <style>
        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
    </style>

    <?php if ($Format_Type != 9) { ?>
        <p style="margin-top:12px; color:#444;">Please click any windmill power generator to see detailed information.</p>
    <?php } ?>

</div> <!-- end page-wrapper -->

<?php
include("footer.php");
?>
</body>
</html>
