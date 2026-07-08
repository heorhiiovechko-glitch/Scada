<?php
/* Channel10.php — Channel4 theme applied, GAD centered, TCP modal on right.
   Original logic preserved. CSS/layout adjusted to remove left gap and match Channel4 look.
*/
error_reporting(0);
include("header_inner.php");
if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}
$Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
if (isset($Cook_Variable)) {
    $Username = base64_encode($Cook_Variable[0]);
    $Pass = base64_encode($Cook_Variable[8]);
}

// --- Begin original logic variables
$lastRecd = null;
$IMEI = $_REQUEST['c1'];
if (isset($_REQUEST['l'])) $Pocket_Length = $_REQUEST['l']; else $Pocket_Length = '';
$IMEI_Decode = base64_decode($IMEI);
$FType = $_REQUEST['FType'];
if (isset($_REQUEST['Db_Name'])) {
    $Database_Name = $_REQUEST['Db_Name'];
}

// Fetch device / customer info (preserved)
$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour,a.Db_Name as Database_Name from device_register a,user_master b where a.Account_ID = b.ACCOUNT_ID and IMEI = '".$IMEI_Decode."'";
if (!$Fetch_Info_Result = $db->query($Fetch_Info)) { die($db->error); }
if ($Fetch_Info_Result->num_rows >= 1) {
    $x = 1;
    while ($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {
        $All_HTSC_No[$x] = $Fetch_Details_Result['HTSC_No'];
        $All_LOC_No[$x] = $Fetch_Details_Result['LOC_No'];
        $All_WEG_No[$x] = $Fetch_Details_Result['WEG_No'];
        $All_Firstname[$x] = $Fetch_Details_Result['Firstname'];
        $All_Devicename[$x] = $Fetch_Details_Result['Device_Name'];
        $Site_Location[$x] = $Fetch_Details_Result['Site_Location'];
        $SF_No[$x] = $Fetch_Details_Result['SF_No'];
        $DOC[$x] = $Fetch_Details_Result['DOC'];
        $Date_Of_Commission = $Fetch_Details_Result['Date_Of_Commission'];
        $Closing_Time[$x] = $Fetch_Details_Result['Closing_Hour'];
        $Capacity[$x] = $Fetch_Details_Result['Capacity'];
        $Database_Name = $Fetch_Details_Result['Database_Name'];
        $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
        $x++;
    }
}

// Closing time based GAD time logic (preserved)
if ($Closing_Time[1] == '06:00:00' || $Closing_Time[1] == '06:30:00') {
    $GAD_Time = " and Hour(Time_S)>=6 ";
    $GD_Time = time() - 21660;
} elseif ($Closing_Time[1] == '07:00:00' || $Closing_Time[1] == '07:30:00') {
    $GAD_Time = " and Hour(Time_S)>=7 ";
    $GD_Time = time() - 25200;
} elseif ($Closing_Time[1] == '08:00:00' || $Closing_Time[1] == '08:30:00') {
    $GAD_Time = " and Hour(Time_S)>=8 ";
    $GD_Time = time() - 28800;
} elseif ($Closing_Time[1] == '09:00:00') {
    $GAD_Time = " and Hour(Time_S)>=9 ";
    $GD_Time = time() - 32400;
} elseif ($Closing_Time[1] == '01:00:00' || $Closing_Time[1] == '01:30:00') {
    $GAD_Time = " and Hour(Time_S)>=1 ";
    $GD_Time = time() - 3600;
} elseif ($Closing_Time[1] == '02:00:00' || $Closing_Time[1] == '02:30:00') {
    $GAD_Time = " and Hour(Time_S)>=2 ";
    $GD_Time = time() - 7200;
} else {
    $GAD_Time = "";
    $GD_Time = time();
    $Test_Time = date('H', $GD_Time);
}

// GAD query (preserved)
$Mysql_Query_GAD = "select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";

if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)) { die($db->error); }
if ($Mysql_Query_Result_GAD->num_rows >= 1) {
    while ($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
        $GAD_Today = $Fetch_Result_GAD['GAD_Today'];
        $GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
        $GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
        $GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
        $GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];
    }
}

// Latest status (preserved)
$ER_Mysql_Query = "select Status as Log,Date_S,Time_S from $Database_Name.device_data_f10 where IMEI='".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 1";
if (!$ER_Mysql_Query_Result = $db->query($ER_Mysql_Query)) { die($db->error); }
if ($ER_Mysql_Query_Result->num_rows >= 1) {
    $ER_Fetch_Result = $ER_Mysql_Query_Result->fetch_array();
    $Status = $ER_Fetch_Result['Log'];
    $Date = $ER_Fetch_Result['Date_S'];
    $Time = $ER_Fetch_Result['Time_S'];
}

$No_Records = '<tr><td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td></tr>';
// --- End original logic variables

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SCADA - Device View</title>

<!-- Channel4 / Material-like Styling (applied site-wide look) -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

<style>
/* Reset + base */
html,body{height:100%; margin:0; padding:0; font-family:'Roboto',sans-serif; background:#f3f5f9; color:#222;}
.material-container {
    width:100% !important;
    max-width:100% !important;
    margin:0 !important;
    padding:0 !important;
    box-sizing:border-box !important;
}


body {
    margin:0 !important;
    padding:0 !important;
    width:100% !important;
    overflow-x:auto !important;
}

.material-card {
    padding:0 !important;
    margin:0 !important;
    border-radius:0 !important;
}


/* Header bar (top single row) */
.header-bar {
    display:flex;
    align-items:center;
    justify-content:space-between;
    background:#018f8a; /* teal from Channel4 */
    color:#fff;
    padding:8px 18px;
    height:48px;
}
.header-left { display:flex; align-items:center; gap:14px; }
.device-name { font-weight:700; font-size:18px; color:#fff; margin-left:0; }

/* GAD area center */
.header-center { flex:1; display:flex; justify-content:center; align-items:center; }
.gad-row { display:flex; gap:56px; align-items:center; font-weight:700; color:#0d3742; background:#f3f7fa; padding:12px 22px; width:100%; justify-content:center; }
.gad-row span { font-size:16px; color:#0b2b2a; }

/* TCP button on right */
.header-right { position:relative; display:flex; align-items:center; gap:12px; }
.tcp-icon-btn { width:44px; height:44px; border-radius:50%; background:#1976d2; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 3px 8px rgba(0,0,0,0.12); }
.tcp-icon-btn .material-icons { color:#fff; font-size:22px; }

/* Table styles matching Channel4 */
.table-wrap { padding:0 0 10px 0; }
.table { width:100%; border-collapse:collapse; font-size:14px; background:#fff; }
.table thead th { background:#e6eef0; padding:10px 8px; font-weight:700; color:#0b3b3b; text-transform:uppercase; border-bottom:1px solid #d8e2e6; position:sticky; top:0; z-index:3; }
.table tbody td { padding:10px 8px; border-bottom:1px solid #f0f4f6; color:#111; }

/* table row color */
.table tbody tr:nth-child(odd) td { background:#fff; }
.table tbody tr:nth-child(even) td { background:#f7fbfe; }

.table {
    width:100% !important;
    table-layout:auto !important;
    white-space:nowrap !important;
}

.table-wrap {
    overflow-x:auto !important;
}


/* GAD details card (centered block below table) */
.gad-details { width:100%; margin:16px 0 0 0; background:#fff; border:1px solid #dbeff0; box-shadow:none; }
.gad-details .gad-title { background:#0d9b97; color:#fff; padding:10px 16px; font-weight:700; text-align:center; }
.gad-details table { width:100%; border-collapse:collapse; }
.gad-details td { padding:12px 18px; border-bottom:1px solid #eaf5f6; }

/* iframe responsive */
.responsive-iframe { width:100%; border:none; height:320px; }

/* small screens adapt */
@media (max-width: 980px) {
    .material-container { padding:6px; max-width:100%; }
    .gad-row { gap:18px; padding:8px; font-size:14px; }
    .tcp-icon-btn { width:40px; height:40px; }
    .device-name { font-size:16px; }
}

.grid-2 {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}


/* ensure no unwanted left margin from header_inner */
.header-inner-reset { margin:0 !important; padding:0 !important; }
</style>

<!-- jQuery for auto refresh and modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function openTCPModal(){
    $('#tcpModal').fadeIn(150);
}
function closeTCPModal(){
    $('#tcpModal').fadeOut(100);
}
$(function(){
    // auto refresh table & status areas (preserve behaviour)
    setInterval(function(){
        $('#getdata').load(location.href + " #getdata > *");
        $('#status').load(location.href + " #status > *");
    }, 20000);
    // prevent right-click / quick inspect if you still want it (optional)
    // document.addEventListener('contextmenu', event => event.preventDefault());
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
<!-- TOP SECTION FIXED: DEVICE NAME OUTSIDE COLOR BAR -->
<div style="width:100%; padding:12px 18px; background:#f3f5f9; color:#000; font-size:20px; font-weight:700;">
    <?= htmlspecialchars($All_Devicename[1]) ?>
<!-- REPORTS (CENTERED) -->
        <div style="font-size:20px; font-weight:600; text-align:left;">
            <a style="font-size:25px; font-weight:600; text-decoration:none;" 
               href="channel10_ajax.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>">
               Analytics Reports
            </a>
        </div>
</div>

<!-- GAD + TCP in teal bar -->
<div class="material-card header-bar header-inner-reset" style="margin-top:0 !important; border-radius:0 !important;">

    <div class="header-center" style="width:100%;">
        <div class="gad-row" aria-live="polite">
            <span>GAD Today : <?= is_numeric($GAD_Today)?(round($GAD_Today,2)." Kwh"):"Nil" ?></span>
            <span>GAD Yesterday : <?= is_numeric($GAD_Yesterday)?(round($GAD_Yesterday,2)." Kwh"):"Nil" ?></span>
            <span>GAD Month : <?= is_numeric($GAD_Thismonth)?(round($GAD_Thismonth,2)." Kwh"):"Nil" ?></span>
        </div>
    </div>

    <div class="header-right">
        <div class="tcp-icon-btn" onclick="openTCPModal()" title="TCP Request">
            <i class="material-icons">bolt</i>
        </div>
    </div>

</div>


    <!-- Live Data Table -->
    <div class="material-card table-wrap">
        <div id="getdata">
            <table class="table" aria-describedby="live-data">
                <thead>
                    <tr>
                        <th>Date</th><th>Time</th><th>GRPM</th><th>RRPM</th><th>Wind Spd</th><th>Status</th>
                        <th>Power</th><th>R Volt</th><th>Y Volt</th><th>B Volt</th>
                        <th>R Cur</th><th>Y Cur</th><th>B Cur</th><th>Power Fact</th><th>Freq</th>
                        <th>Ambt</th><th>Nacel</th><th>Gear</th><th>Gen1</th><th>Gen2</th>
                        <th>Bear</th><th>Cntrl</th><th>Hydr</th><th>Gen0 Kwh</th><th>Gen1 Kwh</th>
                        <th>Gen2 Kwh</th><th>Total Kwh</th><th>Total Hrs</th><th>Gen1 Hrs</th><th>Gen2 Hrs</th><th>Run Hrs</th>
                    </tr>
                </thead>
                <tbody>
<?php
// preserve query & rows (unchanged)
// row color array matches previous behavior
$rowColors = Array('#e6f2ff','#ffffff');
$i = 0;
$Mysql_Query = "select * from $Database_Name.device_data_f10 where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
if (!$Mysql_Query_Result = $db->query($Mysql_Query)) { die($db->error); }

if ($Mysql_Query_Result->num_rows >= 1) {
    while ($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
        $Project_Version = $Fetch_Result['Project_Version'];
        $ID_Number = $Fetch_Result['ID_Number'];
        $GRPM = $Fetch_Result['GRPM'];
        $RRPM = $Fetch_Result['RRPM'];
        $WindSpeed = $Fetch_Result['Windspeed'];
        $Pitch = $Fetch_Result['Pitch'];
        $Status1 = $Fetch_Result['Status'];
        $Date_S = $Fetch_Result['Date_S'];
        $Time_S = $Fetch_Result['Time_S'];
        $Power = $Fetch_Result['Power'];
        $Rphase_Volt = $Fetch_Result['RPhase_Volt'];
        $Yphase_Volt = $Fetch_Result['YPhase_Volt'];
        $Bphase_Volt = $Fetch_Result['BPhase_Volt'];
        $Rphase_Current = $Fetch_Result['RPhase_Current'];
        $Yphase_Current = $Fetch_Result['YPhase_Current'];
        $Bphase_Current = $Fetch_Result['BPhase_Current'];
        $Power_factor = $Fetch_Result['Power_Factor'];
        $Frequency = $Fetch_Result['Frequency'];
        $Gen0 = $Fetch_Result['PAT_Gen0'];
        $Gen1 = $Fetch_Result['PAT_Gen1'];
        $Gen2 = $Fetch_Result['PAT_Gen2'];
        $Production_Total = $Fetch_Result['Production_Total'];
        $Line_Ok = $Fetch_Result['Line_Ok'];
        $Line = $Fetch_Result['Line_Hours'];
        $Turbine_Ok = $Fetch_Result['Turbine_Ok'];
        $Run_Hours = $Fetch_Result['Run_Hours'];
        $Total_Hours  = $Fetch_Result['Total_Hours'];
        $Ambient = $Fetch_Result['Ambient_Temp'];
        $Hydraulic_Oil_Temp = $Fetch_Result['Hydraulic_Temp'];
        $Gear_Box_Oil_Temp = $Fetch_Result['Gear_Temp'];
        $Gen_Winding1_Temp = $Fetch_Result['Gen1_Temp'];
        $Gen_Winding2_Temp = $Fetch_Result['Gen2_Temp'];
        $Gen1_Hours = $Fetch_Result['Gen1_Hours'];
        $Gen2_Hours= $Fetch_Result['Gen2_Hours'];
        $Date_F = $Fetch_Result['Date_F'];
        $Time_F = $Fetch_Result['Time_F'];
        $Nacel = $Fetch_Result['Nacel_Temp'];
        $Bearing = $Fetch_Result['Bearing_Temp'];
        $Control = $Fetch_Result['Control_Temp'];

        if (preg_match('![^a-z0-9]!i', $Gen1_Hours)) $Gen1_Hours="Nil";
        if (preg_match('![^a-z0-9]!i', $Gen2_Hours)) $Gen2_Hours="Nil";
        if (preg_match('![^a-z0-9]!i', $Run_Hours)) $Run_Hours="Nil";
        if ($Frequency < 40) $Frequency = 49.85;
        $Status1 = str_replace('#','',$Status1);
        $lastRecd = $Date_F;
        $WindSpeed = str_replace('m/s','',$WindSpeed);

        echo '<tr style="background-color:'.$rowColors[$i++ % count($rowColors)].';">';
        echo '<td align="center">'.htmlspecialchars($Date_S).'</td>';
        echo '<td align="center">'.htmlspecialchars($Time_S).'</td>';
        echo '<td align="center">'.htmlspecialchars($GRPM).'</td>';
        echo '<td align="center">'.htmlspecialchars($RRPM).'</td>';
        echo '<td align="center">'.htmlspecialchars($WindSpeed).'</td>';
        // Status color
        $statusColor = 'red';
        if (in_array($Status1, ['Run','M/C Running','RUN','OperateG1','OperateG2','OPERATING   NORMAL OPERATION','Running G1'])) $statusColor = 'green';
        elseif (in_array($Status1, ['Grid Drop','GridDrop'])) $statusColor = 'blue';
        echo '<td align="center" style="color:'.$statusColor.'">'.htmlspecialchars($Status1).'</td>';
        echo '<td align="center">'.htmlspecialchars($Power).'</td>';
        echo '<td align="center">'.htmlspecialchars($Rphase_Volt).'</td>';
        echo '<td align="center">'.htmlspecialchars($Yphase_Volt).'</td>';
        echo '<td align="center">'.htmlspecialchars($Bphase_Volt).'</td>';
        echo '<td align="center">'.htmlspecialchars($Rphase_Current).'</td>';
        echo '<td align="center">'.htmlspecialchars($Yphase_Current).'</td>';
        echo '<td align="center">'.htmlspecialchars($Bphase_Current).'</td>';
        echo '<td align="center">'.htmlspecialchars($Power_factor).'</td>';
        echo '<td align="center">'.htmlspecialchars($Frequency).'</td>';
        echo '<td align="center">'.htmlspecialchars($Ambient).'</td>';
        echo '<td align="center">'.htmlspecialchars($Nacel).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gear_Box_Oil_Temp).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gen_Winding1_Temp).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gen_Winding2_Temp).'</td>';
        echo '<td align="center">'.htmlspecialchars($Bearing).'</td>';
        echo '<td align="center">'.htmlspecialchars($Control).'</td>';
        echo '<td align="center">'.htmlspecialchars($Hydraulic_Oil_Temp).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gen0).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gen1).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gen2).'</td>';
        echo '<td align="center">'.htmlspecialchars($Production_Total).'</td>';
        echo '<td align="center">'.htmlspecialchars($Total_Hours).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gen1_Hours).'</td>';
        echo '<td align="center">'.htmlspecialchars($Gen2_Hours).'</td>';
        echo '<td align="center">'.htmlspecialchars($Run_Hours).'</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="31" style="text-align:center;padding:20px;">No Records Found</td></tr>';
}
?>
                </tbody>
            </table>
        </div>
    </div>

    

    <!-- CHARTS -->
    <div class="material-card">
        <div class="section-header">Power vs WindSpeed Chart</div>

        <iframe class="responsive-iframe"
                src="Power_Windspeed_chart_Monthly_iframe.php?c1=<?=$_REQUEST['c1']?>&Year=<?=date('m-Y')?>&l=<?=$_REQUEST['l']?>"></iframe>

            <div class="material-card">
    <div class="section-header">Daily Generation Report</div>
    <iframe class="responsive-iframe"
            src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?>&FType=<?= $_REQUEST['FType'] ?>"></iframe>
    </div>
    </div>


</div>


<?php  ?>

<!-- TCP Modal -->
<div id="tcpModal" style="display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;">
    <div style="width:420px;height:120px;background:#fff;border-radius:8px;padding:10px;position:relative;margin:auto;top:12%;">
        <div style="position:absolute;right:10px;top:6px;cursor:pointer;font-size:18px;color:#333;" onclick="closeTCPModal()">×</div>
        <iframe src="TcpRequest.php?c1=<?= urlencode($_REQUEST['c1']) ?>&db=<?= urlencode($Database_Name) ?>" style="width:100%;height:100%;border:0;"></iframe>
    </div>
</div>

</body>
</html>
