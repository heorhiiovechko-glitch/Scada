<?php
// new_responsive_material_dashboard.php
// Full responsive Material UI rewrite (Option A)
// NOTE: Backend logic preserved from original file; UI replaced with responsive grid/cards

include("header_inner.php");
include_once("./Lib/db_management_service.php");
error_reporting(0);
if (empty($_COOKIE[$Cook_Name])) {
    header("Location: index.php");
    exit;
}

/* ---------------------------
   Preserve original server-side logic & queries
   (I kept the SQL logic and variable names exactly as in your file)
   --------------------------- */

// ----- Load error_type lookup (normalized, substring-aware) -----
$errorMap = dbmgmt_load_error_type_map($db);

// Initialize counters and arrays
$Audio = array();
$td = 0;
$tr = 0;
$CurrentState = "";
$Total_Power = 0;
$CurrentSite = "";
$Total_Export = 0;
$Total_Previous_Day_Generation = 0;
$Total_MTD_Generation = 0;
$Total_Previous_Day_Energy_Produced = 0;
$Total_Previous_Day_Energy_Producible = 0;
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
if ($User_Type_ID == 1 || $User_Type_ID == 2) {
    $Mysql_Query2 = "SELECT  t1.*, s.totalCount AS count 
        FROM  device_register AS t1 
         LEFT JOIN
                (
                SELECT State, COUNT(State)  totalCount
                 FROM  device_register 
                  GROUP   BY State
     )  s ON s.State = t1.State
ORDER   BY count desc,State desc, Device_Order asc";
} elseif ($User_Type_ID == 3 || $User_Type_ID == 2) {
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
<style>
/* Shared layout (matches database_management.php) */
:root{
    --primary:#1976d2;
    --surface:#ffffff;
    --muted:#d9d9d9;
    --shadow:0 6px 20px rgba(32,33,36,0.08);
    --card-shadow:0 6px 20px rgba(32,33,36,0.08);
    --chip-radius:18px;
    --gap:14px;
}

*{box-sizing:border-box}
body{
    font-family:'Roboto',sans-serif;
    background:var(--muted);
    margin:0;
    color:#111;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
}

.page-wrap{
    max-width:1200px;
    margin:0 auto;
    padding:16px;
    padding-bottom:56px;
}

.hero,.panel{
    background:var(--surface);
    border-radius:12px;
    box-shadow:var(--shadow);
}

.hero{
    padding:18px 20px;
    display:flex;
    flex-wrap:wrap;
    gap:14px;
    align-items:center;
    justify-content:space-between;
    margin-bottom:16px;
}

.hero-brand{
    display:flex;
    gap:14px;
    align-items:center;
}

.hero-dashboard-icon{
    width:48px;
    height:48px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    border-radius:10px;
    background:linear-gradient(135deg, #e8f1fb 0%, #d4e4f7 100%);
    border:1px solid #b6cfe8;
    color:#0d47a1;
}

.hero-dashboard-icon i{
    font-size:26px;
    line-height:1;
}

.hero h1{
    margin:0;
    font-size:22px;
    color:#0d47a1;
}

.hero p{
    margin:6px 0 0;
    color:#555;
    font-size:14px;
}

.hero-actions,.nav-actions,.panel-toolbar{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}

.btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    min-height:36px;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    border:1px solid transparent;
    cursor:pointer;
    font-family:inherit;
    transition:transform .15s ease,box-shadow .15s ease;
}

.btn:hover{
    transform:translateY(-1px);
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    text-decoration:none;
}

.btn-primary{background:#e3f2fd;border-color:#90caf9;color:#0d47a1}
.btn-secondary{background:#f4fbf8;border-color:#b7e0d4;color:#0b755c}
.btn-accent{background:#fff3e0;border-color:#ffcc80;color:#e65100}
.btn-danger{background:#ffebee;border-color:#ef9a9a;color:#c62828}
.btn-muted{background:#f5f5f5;border-color:#ddd;color:#555}
.btn.active{box-shadow:inset 0 0 0 2px #1565c0;background:#e3f2fd;border-color:#90caf9;color:#0d47a1}

.btn-sign-out i{
    font-size:15px;
    line-height:1;
}

.panel{
    margin-bottom:16px;
    overflow:hidden;
}

.panel-legend{
    padding:12px 16px;
}

/* Status legend badges */
.status-legend{
    display:flex;
    gap:8px;
    align-items:center;
    flex-wrap:wrap;
}

.status-badge{
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:4px 10px 4px 4px;
    border-radius:999px;
    font-size:11px;
    font-weight:600;
    letter-spacing:0.01em;
    color:#fff;
    border:1px solid rgba(255,255,255,0.28);
    box-shadow:0 1px 6px rgba(15,23,42,0.1);
    transition:transform .2s ease, box-shadow .2s ease;
}

.status-badge:hover{
    transform:translateY(-1px);
    box-shadow:0 4px 12px rgba(15,23,42,0.15);
}

.status-badge-icon{
    width:24px;
    height:24px;
    border-radius:50%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:rgba(255,255,255,0.24);
    font-size:11px;
    flex-shrink:0;
    box-shadow:inset 0 1px 0 rgba(255,255,255,0.35);
}

.status-badge--orange{
    background:linear-gradient(135deg,#ffb74d 0%,#f57c00 55%,#ef6c00 100%);
}

.status-badge--green{
    background:linear-gradient(135deg,#66bb6a 0%,#2e7d32 55%,#1b5e20 100%);
}

.status-badge--green .status-badge-icon i{
    animation:legendFanSpin 2.4s linear infinite;
}

@keyframes legendFanSpin{
    from{transform:rotate(0deg)}
    to{transform:rotate(360deg)}
}

.status-badge--red{
    background:linear-gradient(135deg,#ef5350 0%,#c62828 55%,#b71c1c 100%);
}

.status-badge--blue{
    background:linear-gradient(135deg,#42a5f5 0%,#1565c0 55%,#0d47a1 100%);
}

.status-badge--pink{
    background:linear-gradient(135deg,#f48fb1 0%,#ec407a 55%,#c2185b 100%);
}

.status-badge--grey{
    background:linear-gradient(135deg,#eceff1 0%,#b0bec5 55%,#78909c 100%);
    color:#263238;
    border-color:rgba(255,255,255,0.5);
}

.status-badge--grey .status-badge-icon{
    background:rgba(255,255,255,0.55);
    color:#455a64;
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
.chip.gray{ color: #616161; }
.chip.grey{ color: #616161; background:#eceff1; padding:4px 10px; border-radius:12px; font-size:12px; }
.chip.green{ color: #ffffff; background:#2e7d32; padding:4px 10px; border-radius:12px; font-size:12px; }
.chip.orange{ color: #ffffff; background:#ef6c00; padding:4px 10px; border-radius:12px; font-size:12px; }
.chip.red{ color: #ffffff; background:#c62828; padding:4px 10px; border-radius:12px; font-size:12px; }
.chip.blue{ color: #ffffff; background:#1565c0; padding:4px 10px; border-radius:12px; font-size:12px; }
.device-status-badge{ margin-bottom:8px; }

/* grid of turbine cards  color: #1565c0;*/
.cards-wrapper{
    margin-top:14px;
    margin-bottom:24px;
}

#cards-area{
    display:flex;
    flex-direction:column;
    gap:16px;
    align-items:stretch;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(260px,1fr));
    gap:16px;
    width:100%;
}

.device-list-panel{
    padding:0;
    overflow:hidden;
}

.device-list-table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

.device-list-table th{
    background:#f8fafc;
    color:#666;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.04em;
    padding:12px 14px;
    border-bottom:1px solid #e8edf3;
    text-align:left;
}

.device-list-table td{
    padding:12px 14px;
    border-bottom:1px solid #eef2f7;
    vertical-align:middle;
}

.device-list-table tbody tr:hover td{
    background:#fafcff;
}

.device-list-table .device-name-link{
    color:#0d47a1;
    font-weight:600;
    text-decoration:none;
}

.device-list-table .device-name-link:hover{
    text-decoration:underline;
}

.list-status{
    display:inline-block;
    padding:4px 10px;
    border-radius:6px;
    font-size:12px;
    font-weight:600;
}

.list-status.green{background:#e8f5e9;color:#2e7d32}
.list-status.orange{background:#fff3e0;color:#ef6c00}
.list-status.red{background:#ffebee;color:#c62828}
.list-status.blue{background:#e3f2fd;color:#1565c0}
.list-status.pink{background:#fce4ec;color:#c2185b}
.list-status.grey,.list-status.stopped{background:#eceff1;color:#607d8b}

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
.card.device-card{ cursor:pointer; }
.card.device-card:active{ transform: translateY(-2px); }

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
    .hero{flex-direction:column;align-items:flex-start}
    .hero-actions{width:100%}
    .status-legend{margin-left:0}
}

.kpi-summary-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(130px,1fr));
    gap:12px;
    width:100%;
    order:-1;
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
    border-top:3px solid #0f766e;
}

.summary-card.wind .summary-value{
    color:#0f766e;
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

@media(max-width:768px){
    .kpi-summary-grid{
        grid-template-columns:repeat(auto-fit,minmax(110px,1fr));
        gap:8px;
    }

    .grid{
        grid-template-columns:repeat(auto-fill, minmax(240px,1fr));
        gap:10px;
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

</style>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    function applyDashboardViewMode() {
        var mode = localStorage.getItem('dashboardView') || 'grid';
        var gridView = document.getElementById('gridView');
        var listView = document.getElementById('listView');
        var btn = document.getElementById('viewToggleBtn');
        if (!gridView || !listView || !btn) return;

        if (mode === 'list') {
            gridView.style.display = 'none';
            listView.style.display = '';
            btn.innerHTML = '<i class="fa-solid fa-grip"></i> Grid View';
            btn.title = 'Switch to grid view';
        } else {
            gridView.style.display = '';
            listView.style.display = 'none';
            btn.innerHTML = '<i class="fa-solid fa-list"></i> List View';
            btn.title = 'Switch to list view';
        }
        btn.className = 'btn btn-primary';
    }

    window.applyDashboardViewMode = applyDashboardViewMode;

    $('#viewToggleBtn').on('click', function() {
        var mode = localStorage.getItem('dashboardView') || 'grid';
        localStorage.setItem('dashboardView', mode === 'list' ? 'grid' : 'list');
        applyDashboardViewMode();
    });

    applyDashboardViewMode();

    function refreshData(){
        $("#cards-area").load(location.pathname + " #cards-area > *", function(){
            updateLastUpdatedTime();
            applyDashboardViewMode();
            var audio = document.getElementById('ctrlaudio');
            if(audio && audio.paused && audio.dataset.autoplay == "1"){
                try{ }catch(e){}
            }
        });
    }

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

<div class="page-wrap">

    <div class="hero">
        <div class="hero-brand">
            <span class="hero-dashboard-icon" aria-hidden="true">
                <i class="fa-solid fa-gauge-high"></i>
            </span>
            <div>
                <h1>Wind Farm Dashboard</h1>
                <p>
                    <?= htmlspecialchars($Firstname . " " . $Lastname) ?> &mdash;
                    Last updated: <span id="lastUpdated" style="font-weight:600;color:#111;"><?= date('d-m-Y H:i:s') ?></span>
                </p>
            </div>
        </div>
        <div class="hero-actions">
            <button type="button" class="btn btn-primary" id="viewToggleBtn" title="Switch to list view">
                <i class="fa-solid fa-list"></i> List View
            </button>
            <?php if ((string)$User_Type_ID === '1' || (string)$User_Type_ID === '2') { ?>
            <a class="btn btn-primary" href="database_management.php" title="Database Management">
                <i class="fa-solid fa-database"></i> Database
            </a>
            <?php } ?>
        </div>
    </div>

    <div class="panel panel-legend">
        <div class="status-legend" aria-label="Device status legend">
            <span class="status-badge status-badge--orange">
                <span class="status-badge-icon"><i class="fa-solid fa-wind"></i></span>
                Null Wind
            </span>
            <span class="status-badge status-badge--green">
                <span class="status-badge-icon"><i class="fa-solid fa-fan"></i></span>
                WTG Run
            </span>
            <span class="status-badge status-badge--red">
                <span class="status-badge-icon"><i class="fa-solid fa-circle-stop"></i></span>
                Error Stop
            </span>
            <span class="status-badge status-badge--blue">
                <span class="status-badge-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                Service
            </span>
            <span class="status-badge status-badge--pink">
                <span class="status-badge-icon"><i class="fa-solid fa-bolt"></i></span>
                Impact
            </span>
            <span class="status-badge status-badge--grey">
                <span class="status-badge-icon"><i class="fa-solid fa-tower-broadcast"></i></span>
                No Communication
            </span>
        </div>
    </div>

    <!-- Cards area (this div will be AJAX-updated by refreshing the inner content) -->
    <div class="cards-wrapper">
        <div id="cards-area">
            <?php $listRowsHtml = ''; ?>
            <div id="gridView" class="device-grid-view">
            <div class="grid">
                <?php
                /* ---------------------------
                   Original device loop is here.
                   I preserved all data processing and variables, but replaced the heavy nested tables
                   with Material-style cards.
                   --------------------------- */

                if ($queryResult2->num_rows >= 1) {
                    // reset counters that were used originally
                    $td = 0;

                    // iterate devices (original loop contents preserved)
                    while ($Fetch_Result = $queryResult2->fetch_array()) {
                        $registerStatus = isset($Fetch_Result['Register_Status'])
                            ? (int)$Fetch_Result['Register_Status']
                            : (isset($Fetch_Result['Status']) && is_numeric($Fetch_Result['Status']) ? (int)$Fetch_Result['Status'] : 1);
                        if ($registerStatus !== 1) {
                            continue;
                        }

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
                        $Device_Db = $Fetch_Result['db_name'];

                        if ($Format_Type == 11) 
						{
                            $Channel_Url = "channel11_type11.php?";
                            $Table_Name = "device_data_f11";
                            $Error_Table_Name = "error_data_f11";
                        } elseif ($Format_Type == 7) {
                            $Channel_Url = "channel7.php?";
                            $Table_Name = "device_data_f7";
                            $Error_Table_Name = "error_data_f7";
                        } elseif ($Format_Type == 1) {
                            $Table_Name = "device_data";
                            $Channel_Url = "channel2.php?";
                        } elseif ($Format_Type == 2) {
                            $Table_Name = "device_data_f2";
                            $Channel_Url = "channel3.php?";
                        } elseif ($Format_Type == 3) {
                            $Table_Name = "device_data_f3";
                            $Channel_Url = "channel4.php?";
                        } elseif ($Format_Type == 4) {
                            $Table_Name = "device_data_f4";
                            $Channel_Url = "channel5.php?";
                        } elseif ($Format_Type == 6) {
                            $Table_Name = "device_data_f6";
                            $Channel_Url = "channel7.php?";
                        } else {
                            $Table_Name = "device_data_f" . $Format_Type;
                            $Channel_Url = "channel.php?";
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
                                $Mysql_Query1 = "select date_s as Date_S,time_s as Time_S, Bridge1_dcv as WindSpeed, Bridge2_dcc as Power,status as Status, Phase2_kvar AS G1, Phase2_kva AS Current_Month_Generation, Phase3_kva AS Previous_Day_Energy_Produced, Cabinet2_temp AS Previous_Day_Generation, Cabinet2_temp AS Previous_Day_Energy_Producible from va_powercon.device_data_f11 where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";

								
							} else {
                                $Mysql_Query1 = "select Date_S,Time_S, tag_windspd as WindSpeed, tag_power as Power, tag_status as Status from $Device_Db.$Table_Name where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";
                            }
						}
                        else
                            $Mysql_Query1 = "select Date_S,Time_S,Windspeed as WindSpeed,Power,Status from $Device_Db.$Table_Name where IMEI = '" . $IMEI_Decode . "' order by Record_Index desc limit 1";
						
						//echo $Mysql_Query1;
						
                        if (!$queryResult1 = $db->query($Mysql_Query1)) {
                            continue;
                        }

                        if ($queryResult1->num_rows > 0) {
                            $Fetch_Result1 = $queryResult1->fetch_array();
                        } else {
                            $Fetch_Result1 = array(
                                'WindSpeed' => '',
                                'Power' => '',
                                'Status' => 'No data',
                                'Date_S' => '',
                                'Time_S' => '',
                                'devicedata' => ''
                            );
                        }

                        // Fall back to live cache on device_register when history row is missing.
                        if ((empty($Fetch_Result1['Date_S']) || empty($Fetch_Result1['Time_S'])) && !empty($Fetch_Result['date_s'])) {
                            $Fetch_Result1['Date_S'] = $Fetch_Result['date_s'];
                            $Fetch_Result1['Time_S'] = $Fetch_Result['time_s'];
                        }
                        if ((empty($Fetch_Result1['WindSpeed']) || $Fetch_Result1['WindSpeed'] === '0') && !empty($Fetch_Result['windspeed'])) {
                            $Fetch_Result1['WindSpeed'] = $Fetch_Result['windspeed'];
                        }
                        if ((empty($Fetch_Result1['Power']) || $Fetch_Result1['Power'] === '0') && !empty($Fetch_Result['power'])) {
                            $Fetch_Result1['Power'] = $Fetch_Result['power'];
                        }
                        if ((empty($Fetch_Result1['Status']) || $Fetch_Result1['Status'] === 'No data') && !empty($Fetch_Result['status'])) {
                            $Fetch_Result1['Status'] = $Fetch_Result['status'];
                        }

                            $devicedata = explode(',', isset($Fetch_Result1['devicedata']) ? $Fetch_Result1['devicedata'] : (isset($Fetch_Result['devicedata']) ? $Fetch_Result['devicedata'] : ''));
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
                            $PowerRaw = isset($Fetch_Result1['Power']) ? $Fetch_Result1['Power'] : null;
                            $PowerVal = dbmgmt_parse_power_value($PowerRaw);
                            $Power = ($PowerVal !== null ? number_format($PowerVal, 2) : '0.00');
                            $Status1 = isset($Fetch_Result1['Status']) ? trim($Fetch_Result1['Status']) : '';
                            $Status = strtolower($Status1);

                            
							$G1 = isset($Fetch_Result1['G1']) ? ($Format_Type!=7 ? ($Fetch_Result1['G1'] * 1000) : $Fetch_Result1['G1']*1000) : 0;
							$Previous_Day_Generation = isset($Fetch_Result1['Previous_Day_Generation']) ? ($Fetch_Result1['Previous_Day_Generation'] * 1000) : 0;
							$Current_Month_Generation = isset($Fetch_Result1['Current_Month_Generation']) ? ($Fetch_Result1['Current_Month_Generation'] * 1000) : 0;
							$Previous_Day_Energy_Produced = isset($Fetch_Result1['Previous_Day_Energy_Produced']) ? ($Fetch_Result1['Previous_Day_Energy_Produced'] * 1000) : 0;
							$Previous_Day_Energy_Producible = isset($Fetch_Result1['Previous_Day_Energy_Producible']) ? ($Fetch_Result1['Previous_Day_Energy_Producible'] * 1000) : 0;
							$G2 = isset($Fetch_Result1['G2']) ? round($Fetch_Result1['G2']) : 0;
							
							

                            /*if ($G1 > 25000 || $G1 < 0) {
                                $G1 = '0';
                            }*/
                            if ($G2 > 24 || $G2 < 0) {
                                $G2 = '0';
                            }

                            $PrevDayGen = ($Previous_Day_Generation > 0 ? round($Previous_Day_Generation) : 0);
                            if ($PrevDayGen <= 0 && !empty($Device_Db)) {
                                try {
                                    $prevDaySql = "SELECT (Gen1_Max - Gen1_Min) AS prev_gen FROM ".$Device_Db.".daily_data WHERE IMEI = '".$db->real_escape_string($IMEI_Decode)."' AND Date_S = (CURDATE() - INTERVAL 1 DAY) LIMIT 1";
                                    if ($prevDayResult = $db->query($prevDaySql)) {
                                        if ($prevDayRow = $prevDayResult->fetch_array()) {
                                            $PrevDayGen = round((float)$prevDayRow['prev_gen']);
                                        }
                                    }
                                } catch (mysqli_sql_exception $e) {
                                    $PrevDayGen = 0;
                                }
                            }

                          
							
							

                            $Total_Export += $G1;
                            $Total_Previous_Day_Generation += $Previous_Day_Generation;
                            $Total_MTD_Generation += $Current_Month_Generation;
                            $Total_Previous_Day_Energy_Produced += $Previous_Day_Energy_Produced;
                            $Total_Previous_Day_Energy_Producible += $Previous_Day_Energy_Producible;
                            $Total_Power += floatval(str_replace(',', '', $Power));
                            $Date_F = isset($Fetch_Result1['Date_S']) ? $Fetch_Result1['Date_S'] : '';
                            $Time_F = isset($Fetch_Result1['Time_S']) ? $Fetch_Result1['Time_S'] : '';

                            $lastSeenEpoch = null;
                            if ($Date_F !== '' && $Time_F !== '') {
                                $lastSeenEpoch = GetTimestamp($Date_F, $Time_F);
                                if (!$lastSeenEpoch) {
                                    $lastSeenEpoch = null;
                                }
                            }

                            $legend = dbmgmt_classify_device_legend(
                                $registerStatus,
                                $lastSeenEpoch,
                                $Status1,
                                $PowerRaw,
                                $errorMap
                            );
                            $chipClass = $legend['legend_class'];
                            $statusLabel = $legend['legend_name'];

                            $towerImages = array(
                                'green' => array('src' => './images/6.gif', 'alt' => 'Green Tower', 'animated' => true),
                                'orange' => array('src' => './images/7.gif', 'alt' => 'Orange Tower', 'animated' => true),
                                'blue' => array('src' => './images/Blue_jpg.jpg', 'alt' => 'Blue Tower', 'animated' => false),
                                'pink' => array('src' => './images/18.jpg', 'alt' => 'Pink Tower', 'animated' => false),
                                'red' => array('src' => './images/Red_jpg.jpg', 'alt' => 'Red Tower', 'animated' => false),
                                'grey' => array('src' => './images/Grey_jpg.jpg', 'alt' => 'Grey Tower', 'animated' => false),
                                'stopped' => array('src' => './images/Grey_jpg.jpg', 'alt' => 'Stopped Tower', 'animated' => false),
                            );
                            $tower = isset($towerImages[$chipClass]) ? $towerImages[$chipClass] : $towerImages['red'];
                            $towerSize = $tower['animated'] ? '59px' : '59px';
                            $towerHeight = $tower['animated'] ? '108px' : '108';
                            $Tower_Img = '<img src="' . $tower['src'] . '" width="' . $towerSize . '" height="' . $towerHeight . '" alt="' . htmlspecialchars($tower['alt']) . '">';

                            if ($chipClass === 'green' || $chipClass === 'orange') {
                                $WTG_Run++;
                            }
                            if (in_array($chipClass, array('blue', 'pink', 'red'), true)) {
                                $Audio[] = $WEGno[$IMEI_Decode];
                            }

                            // Compute Date and Time string to display (preserved logic)
                            $Date_G = $Date_F ? strtotime($Date_F) : false;
                            $Time_G = $Time_F ? strtotime($Time_F) : false;
                            $Date = ($Date_G !== false) ? date('d/m/Y', $Date_G) : '-';
                            $Time = ($Time_G !== false) ? date('H:i:s', $Time_G) : '-';

                            // Build device detail URL for tower link
                            $channelHref = "device_detail_info.php?c1=" . $IMEI . "&l=" . $Pocket_Length . "&FType=" . $Format_Type;
                            $canOpenRawData = ($User_Type_ID == 1 || $User_Type_ID == 2);
                            $rawDataUrl = $canOpenRawData && !empty($Fetch_Result['Device_Index'])
                                ? ('device_raw_data.php?device_index=' . (int)$Fetch_Result['Device_Index'])
                                : '';

                            $capVal = floatval($Capacity);
                            $perfPct = ($capVal > 0 && $PowerVal !== null) ? round(($PowerVal / $capVal) * 100, 2) : 0;
                            if ($perfPct < 0) { $perfPct = 0; }

                            $listRowsHtml .= '<tr>'
                                . '<td><a class="device-name-link" href="' . htmlspecialchars($channelHref) . '">' . htmlspecialchars($Device_Name[$IMEI_Decode]) . '</a></td>'
                                . '<td>' . htmlspecialchars($HTSCno) . '</td>'
                                . '<td>' . htmlspecialchars($Site_Location[$IMEI_Decode]) . '</td>'
                                . '<td>' . htmlspecialchars($Capacity) . '</td>'
                                . '<td>' . htmlspecialchars($Date) . '</td>'
                                . '<td>' . htmlspecialchars($Time) . '</td>'
                                . '<td><span class="list-status ' . htmlspecialchars($chipClass) . '">' . htmlspecialchars($statusLabel) . '</span></td>'
                                . '<td>' . htmlspecialchars($WindSpeed) . '</td>'
                                . '<td>' . htmlspecialchars($Power) . '</td>'
                                . '<td>' . ($G1 != '' ? htmlspecialchars($G1) : '0') . '</td>'
                                . '<td>' . ($PrevDayGen != '' ? htmlspecialchars($PrevDayGen) : '0') . '</td>'
                                . '<td>' . number_format($perfPct, 2) . '%</td>'
                                . '</tr>';
                            ?>

                            <!-- Card: single device -->
                            <div class="card" role="article" aria-label="<?= htmlspecialchars($Device_Name[$IMEI_Decode]) ?>">

    <div class="device-row">

        <!-- LEFT SIDE : Tower Image -->
        <div class="left-img">
            <?php if ($Account_ID != '100146') { ?>
                <a href="<?= htmlspecialchars($channelHref) ?>"
                   title="Open device details — IMEI: <?= htmlspecialchars($IMEI_Decode) ?>"
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
                    <td class="value"><?= ($G1 != '' ? htmlspecialchars($G1) : '0') ?> kwh</td>
                </tr>
                <tr>
                    <td class="label">Prev.Day Gen</td>
                    <td class="value"><?= ($PrevDayGen != '' ? htmlspecialchars($PrevDayGen) : '0') ?> kWh</td>
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
            </div> <!-- end gridView -->

            <div id="listView" class="device-list-view" style="display:none;">
                <div class="panel device-list-panel">
                    <table class="device-list-table">
                        <thead>
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
                                <th>Gen.Daily (kWh)</th>
                                <th>Prev.Day Gen (kWh)</th>
                                <th>Performance (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($listRowsHtml !== '') { echo $listRowsHtml; } else { ?>
                            <tr><td colspan="12" style="text-align:center;padding:24px;color:#666;">Machine not yet Installed...</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="kpi-summary-grid">
                <div class="summary-card wind">
                    <div class="summary-title">Avg WindSpeed</div>
                    <div class="summary-value">
                        <?= number_format(($WindSpeed_Count > 0 ? $Total_WindSpeed / $WindSpeed_Count : 0), 1) ?> <span>m/s</span>
                    </div>
                </div>

                <div class="summary-card power">
                    <div class="summary-title">Current Power</div>
                    <div class="summary-value">
                        <?= number_format($Total_Power,2) ?> <span>kW</span>
                    </div>
                </div>

                <div class="summary-card generation">
                    <div class="summary-title">Current Day Generation</div>
                    <div class="summary-value">
                        <?= $Total_Export ?> <span>kWh</span>
                    </div>
                </div>

                <div class="summary-card previous-generation">
                    <div class="summary-title">Previous Day Generation</div>
                    <div class="summary-value">
                        <?= $Total_Previous_Day_Generation ?> <span>kWh</span>
                    </div>
                </div>

                <div class="summary-card generation">
                    <div class="summary-title">Current MTD</div>
                    <div class="summary-value">
                        <?= $Total_MTD_Generation ?> <span>kWh</span>
                    </div>
                </div>

                <div class="summary-card power">
                    <div class="summary-title">Previous Day PLF</div>
                    <div class="summary-value">
                        <?= number_format((($Total_Previous_Day_Generation / (2000 * 12 * 24)) * 100), 2) ?> <span>%</span>
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

</div> <!-- end page-wrap -->

<?php include("footer.php"); ?>
