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
$Total_Previous_Day_Generation = 0;
$Total_WindSpeed = 0;
$WindSpeed_Count = 0;
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
//echo  $Mysql_Query2;
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

.dashboard-report-link{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:36px;
    padding:8px 14px;
    border-radius:8px;
    background:#0f8f6f;
    color:#fff;
    font-size:13px;
    font-weight:700;
    text-decoration:none;
    box-shadow:0 4px 12px rgba(15,143,111,.22);
    transition:background .15s ease, transform .15s ease;
}

.dashboard-report-link:hover{
    background:#0b755c;
    color:#fff;
    text-decoration:none;
    transform:translateY(-1px);
}

.summary-report-row{
    margin:0 0 8px;
}

.plant-summary-panel .dashboard-report-link{
    width:100%;
    min-height:34px;
    padding:7px 10px;
    box-sizing:border-box;
    box-shadow:0 2px 8px rgba(15,143,111,.18);
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

#cards-area{
    display:grid;
    grid-template-columns:minmax(190px, 230px) minmax(0, 1fr);
    gap:12px;
    align-items:start;
}

.grid{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(260px,1fr));
    gap:16px;
    grid-column:2;
    grid-row:1;
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
    color: #229988;
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
.kpi-summary-row{
    display:grid;
    grid-template-columns:1fr;
    gap:6px;
    margin:8px 0 12px;
}

.plant-summary-panel{
    grid-column:1;
    grid-row:1;
    background:#fff;
    border-radius:8px;
    padding:8px;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

.plant-summary-divider{
    margin:0 0 8px;
    border:0;
    border-top:1px solid #d9d9d9;
}

.plant-summary-title{
    text-align:center;
    margin:0 0 8px;
    color:#1565c0;
    font-size:18px;
    font-weight:700;
    line-height:1.2;
}

.summary-card{
    background:#fff;
    border-radius:6px;
    padding:6px 8px;
    min-height:58px;
    box-shadow:0 1px 5px rgba(0,0,0,.08);
    text-align:center;
    transition:.2s;
}

.summary-card:hover{
    transform:translateY(-2px);
}

.summary-title{
    font-size:10px;
    font-weight:600;
    color:#666;
    margin-bottom:4px;
    text-transform:uppercase;
    letter-spacing:0;
}

.summary-value{
    font-size:18px;
    font-weight:700;
    line-height:1;
}

.summary-value span{
    font-size:11px;
    color:#777;
}

.summary-card.wind{
    border-top:3px solid #9c27b0;
}

.summary-card.power{
    border-top:3px solid #2196f3;
}

.summary-card.generation{
    border-top:3px solid #4caf50;
}

.summary-card.previous-generation{
    border-top:3px solid #00acc1;
}

.summary-card.running{
    border-top:3px solid #ff9800;
}

@media(max-width:1200px){
    #cards-area{
        grid-template-columns:minmax(180px, 210px) minmax(0, 1fr);
    }
}

@media(max-width:768px){
    #cards-area{
        grid-template-columns:1fr;
    }

    .plant-summary-panel{
        grid-column:1;
        grid-row:1;
    }

    .grid{
        grid-column:1;
        grid-row:2;
    }
}

.summary-card.power .summary-value{
    color:#2196f3;
}

.summary-card.generation .summary-value{
    color:#4caf50;
}

.summary-card.previous-generation .summary-value{
    color:#00acc1;
}

.summary-card.running .summary-value{
    color:#ff9800;
}



</style>

<!-- Simple JS: live refresh and audio handling -->
<script>
$(document).ready(function(){

    // Live AJAX refresh every 40 seconds (replaces your old setInterval code)
    function refreshData(){
    $("#cards-area").load(location.pathname + " #cards-area > *", function(){
        updateLastUpdatedTime();
        var audio = document.getElementById('ctrlaudio');
        if(audio && audio.paused && audio.dataset.autoplay == "1"){ 
            try{ //audio.play(); 
			}catch(e){} 
        }
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

function updateLastUpdatedTime() {
    let now = new Date();

    let dd   = String(now.getDate()).padStart(2, '0');
    let mm   = String(now.getMonth() + 1).padStart(2, '0');
    let yyyy = now.getFullYear();

    let hh   = String(now.getHours()).padStart(2, '0');
    let min  = String(now.getMinutes()).padStart(2, '0');
    let sec  = String(now.getSeconds()).padStart(2, '0');

    let formatted = dd + "-" + mm + "-" + yyyy + " " + hh + ":" + min + ":" + sec;

    document.getElementById("lastUpdated").innerText = formatted;
}

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


<div style="display:flex; gap:10px; align-items:center; margin-top:10px; flex-wrap:wrap;">
        

        <div style="display:flex; gap:8px; align-items:center; font-size:14px; justify-content:flex-start;">

            <span style="color:#666;">Last updated:</span>
           <span id="lastUpdated" style="font-weight:600; color:#111;">
    <?= date('d-m-Y H:i:s') ?>
</span>


        </div>
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
                        
                        if ($Format_Type == 11) 
						{
                            $Channel_Url = "channel11_type11.php?";
                            $Table_Name = "device_data_f11";
                            $Error_Table_Name = "error_data_f11";
                        }

                        // decode for querying history
                        $IMEI_Decode = base64_decode($IMEI);
                        $From_Mon_D_Epoch = "01-" . date("m-Y");
                        $From_Mon_D_Epoch = strtotime($From_Mon_D_Epoch) + (60 * 60 * 5.5);
                        $To_Mon_D_Epoch = date("d-m-Y");
                        $To_Mon_D_Epoch = strtotime($To_Mon_D_Epoch . " 23:59:59") + (60 * 60 * 5.5);

                        // choose the correct latest-record query (preserved)
                       if ($Format_Type == 11){
							if ($Database_Name == 'va_powercon') 
							{
                                $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, Bridge1_dcv as WindSpeed, Bridge2_dcc as Power,status as Status, Phase2_kvar AS G1, Cabinet2_temp AS Previous_Day_Generation from va_powercon.device_data_f11 where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";

								
							}
						}
                        else
                            $Mysql_Query1 = "select Date_S,Time_S,WindSpeed,Power,Status from $Database_Name.$Table_Name where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";
						
						//echo $Mysql_Query1;
						
                        if (!$queryResult1 = $db->query($Mysql_Query1)) {
                            die($db->error);
                        }

                        if ($queryResult1->num_rows >= 0) {
                            $Fetch_Result1 = $queryResult1->fetch_array();
                            

                            $devicedata = explode(',', $Fetch_Result1['devicedata']);
                            $G4_Temp = isset($devicedata[13]) ? $devicedata[13] : '';
                            $G6_Temp = isset($devicedata[15]) ? $devicedata[15] : '';
                            $G3 = isset($devicedata[19]) ? $devicedata[19] : '';
                            $Gvarh = isset($devicedata[27]) ? $devicedata[27] : '';
                            $GRPM = isset($devicedata[6]) ? $devicedata[6] : '';
                            $RRPM = isset($devicedata[7]) ? $devicedata[7] : '';
                            $WindSpeed_Raw = isset($Fetch_Result1['WindSpeed']) ? $Fetch_Result1['WindSpeed'] : null;
                            $WindSpeed_Raw = trim(str_replace(array('m/s', ','), '', $WindSpeed_Raw));
                            if ($WindSpeed_Raw !== '' && is_numeric($WindSpeed_Raw)) {
                                $Total_WindSpeed += (float)$WindSpeed_Raw;
                                $WindSpeed_Count++;
                                $WindSpeed = number_format((float)$WindSpeed_Raw, 2);
                            } else {
                                $WindSpeed = '0.00';
                            }
                            $Power = isset($Fetch_Result1['Power']) ? $Fetch_Result1['Power'] : null;
                            $Power = ($Power != '' ? number_format($Power, 2) : '0.00');
                            $Status1 = trim($Fetch_Result1['Status']);
                            $Status = strtolower($Status1);

                            
							$G1= ($Format_Type!=7? ($Fetch_Result1['G1'] * 1000) : $Fetch_Result1['G1']*1000);
							$Previous_Day_Generation = isset($Fetch_Result1['Previous_Day_Generation']) ? ($Fetch_Result1['Previous_Day_Generation'] * 1000) : 0;
							$G2= round($Fetch_Result1['G2']);
							
							

                            /*if ($G1 > 25000 || $G1 < 0) {
                                $G1 = '0';
                            }*/
                            if ($G2 > 24 || $G2 < 0) {
                                $G2 = '0';
                            }

                          
							
							

                            $Total_Export += $G1;
                            $Total_Previous_Day_Generation += $Previous_Day_Generation;
                            $Total_Power += floatval(str_replace(',', '', $Power));
                            $Date_F = isset($Fetch_Result1['Date_S']) ? $Fetch_Result1['Date_S'] : '';
                            $Time_F = isset($Fetch_Result1['Time_S']) ? $Fetch_Result1['Time_S'] : '';

                            $Device_Epoch_Time = GetTimestamp($Date_F, $Time_F);

                            if (!empty($Device_Epoch_Time)) {
                                $Diff_Error_Status = $Device_Epoch_Time;
                            }

                            $Req_Time = time() + (60 * 60 * 5.5);
                            $ReqTime_Diff = $Req_Time - $Device_Epoch_Time;
$ReqTime_Diff = 1500;
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
                            $channelHref = $Channel_Url . "c1=" . urlencode($IMEI) . "&l=" . urlencode($Pocket_Length) . "&FType=" . urlencode($Format_Type) . "&Db_Name=" . urlencode(isset($Database_Name) ? $Database_Name : "");
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
			<div class="plant-summary-panel">
			<hr class="plant-summary-divider">

<h2 class="plant-summary-title">
    Overall Plant Summary
</h2>

<div class="summary-report-row">
    <a class="dashboard-report-link" href="reports11_ajax.php?FType=11" target="_blank" title="Open reports">
        Reports
    </a>
</div>

<div class="kpi-summary-row">

	<div class="summary-card wind">
        <div class="summary-title">Avg WindSpeed</div>
        <div class="summary-value">
            <?= number_format(($WindSpeed_Count > 0 ? $Total_WindSpeed / $WindSpeed_Count : 0), 1) ?> <span>m/s</span>
        </div>
    </div>

    <div class="summary-card power">
        <div class="summary-title">Total Instantaneous Power</div>
        <div class="summary-value">
            <?= number_format($Total_Power,2) ?> <span>kW</span>
        </div>
    </div>

    <div class="summary-card generation">
        <div class="summary-title">Today's Generation</div>
        <div class="summary-value">
            <?= number_format($Total_Export,2) ?> <span>kWh</span>
        </div>
    </div>

    <div class="summary-card previous-generation">
        <div class="summary-title">Previous Day Generation</div>
        <div class="summary-value">
            <?= number_format($Total_Previous_Day_Generation,2) ?> <span>kWh</span>
        </div>
    </div>
	
	<div class="summary-card power">
        <div class="summary-title">PLF</div>
        <div class="summary-value">
            <?= number_format($Total_Power/24000,2) *100?> <span>%</span>
        </div>
    </div>

    <div class="summary-card running">
        <div class="summary-title">WTG Running</div>
        <div class="summary-value">
            <?= $WTG_Run ?>
            <span>/ <?= $Mysql_Record_Count ?></span>
        </div>
    </div>

</div>
			</div>
        </div> <!-- end cards-area -->
    </div> <!-- end cards-wrapper -->
	

    <!-- Audio + audio list logic (preserved, but simplified) -Disabled theAudio Section -->
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
	<!--
        <audio id="ctrlaudio" controls autoplay data-autoplay="1" style="display:none;">
           <source src="<?= htmlspecialchars($Audio[0]) ?>" type="audio/wav">
            Your browser does not support the audio element.
        </audio> -->
        <input type="hidden" id="hdnSongNames" value="<?= htmlspecialchars($Audio_Str) ?>">
       <script>
            // Basic autoplay playlist rotation (keeps your original idea)
           /* (function () {
                var audio = document.getElementById('ctrlaudio');
                var songNames = document.getElementById('hdnSongNames').value.split(',');
                var cur = 0;
                if (!audio) return;
                audio.addEventListener('ended', function () {
                    cur = (cur + 1) % songNames.length;
                    audio.src = songNames[cur];
                    audio.load();
                    //audio.play();
                });*/
            })();
        </script>
    <?php } ?>


    

    <?php if ($Format_Type != 9) { ?>
        <p style="margin-top:12px; color:#444;">Please click any windmill to see detailed information</p>
    <?php } ?>

</div> <!-- end page-wrapper -->

<?php

?>
</body>
</html>
