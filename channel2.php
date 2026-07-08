<?php
error_reporting(0);
include("header_inner.php");
if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}
$Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
if (isset($Cook_Variable)) {
    $Username = base64_encode($Cook_Variable[0]);
    $Pass     = base64_encode($Cook_Variable[8]);
}

// keep original server-side logic unchanged (DB queries, variables)
$lastRecd = null;
$IMEI = $_REQUEST['c1'];
if (isset($_REQUEST['l']))
    $Pocket_Length = $_REQUEST['l'];
else
    $Pocket_Length = '';
$IMEI_Decode = base64_decode($IMEI);
$FType = $_REQUEST['FType'];
if (isset($_REQUEST['Db_Name'])) {
    $Database_Name = $_REQUEST['Db_Name'];
}

// Fetch customer information
$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour,a.Db_Name as Database_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
if (!$Fetch_Info_Result = $db->query($Fetch_Info)) {
    die($db->error);
}
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
        $Capacity[$x] = $Fetch_Details_Result['Capacity'];
        $Closing_Time[$x] = $Fetch_Details_Result['Closing_Hour'];
        $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
        $Database_Name = $Fetch_Details_Result['Database_Name'];
        $x++;
    }
}

// Determine GAD time window (preserve logic)
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

// GAD queries (preserve original)
$Mysql_Query_GAD = "select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";
if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)) {
    die($db->error);
}
if ($Mysql_Query_Result_GAD->num_rows >= 1) {
    while ($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
        $GAD_Today = $Fetch_Result_GAD['GAD_Today'];
        $GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
        $GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
        $GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
        $GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];
    }
}

// Latest status
$ER_Mysql_Query = "select Status as Log,Date_S,Time_S from $Database_Name.device_data where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
if (!$ER_Mysql_Query_Result = $db->query($ER_Mysql_Query)) {
    die($db->error);
}
if ($ER_Mysql_Query_Result->num_rows >= 1) {
    $ER_Fetch_Result = $ER_Mysql_Query_Result->fetch_array();
    $Log_Status = $ER_Fetch_Result['Log'];
    $Date = $ER_Fetch_Result['Date_S'];
    $Time = $ER_Fetch_Result['Time_S'];
}

// No records HTML fallback
$No_Records = '<tr><td colspan="2" class="tab-head-td" style="padding:10px; text-align:center;">Records Not Found</td></tr>';

// Include responsive CSS and minimal JS
?>

<style>
/* Responsive wrapper similar to Channel3.php (mobile-first) */
.container-fluid-custom{
    max-width:100%;
    margin:0 auto;
    padding:16px;
}
.header-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
}
.device-title{
    font-size:1.25rem;
    font-weight:700;
}
.btn-back img{width:40px;height:40px}
.table-responsive{overflow-x:auto;margin-top:12px}
.innertab1{width:100%;border-collapse:collapse;}
.innertab1 th,.innertab1 td{padding:8px;text-align:center;border:1px solid #ddd;font-size:0.95rem}
.tab-head-tr-new{background:#d9edf7 !important;color:#003f5c;font-weight:700;}
.tab-head-td-new{padding:8px;font-weight:600}
.tab-head-td1-status{padding:8px;color:#fff}
.gad-card, .status-card{background:#fff;padding:12px;border:1px solid #e1e1e1;border-radius:8px}
.card-row{display:flex;gap:12px;flex-wrap:wrap;margin-top:12px}
.card-col{flex:1 1 250px}
.iframe-full{width:100%;border:1px solid #168A83;border-radius:6px}
/* smaller devices */
@media (max-width:800px){
    .device-title{font-size:1rem}
    .innertab1 th,.innertab1 td{padding:6px;font-size:0.85rem}
}

/* TCP POPUP MODAL */
.tcp-modal {
    display:none;
    position:fixed;
    z-index:9999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.55);
}

.tcp-modal-content {
    background:#fff;
    width:360px;
    max-width:95%;
    margin:80px auto;
    padding:16px;
    border-radius:12px;
    animation:pop 0.3s ease-out;
}

@keyframes pop {
    0% {transform:scale(0.8); opacity:0;}
    100% {transform:scale(1); opacity:1;}
}

.tcp-close {
    float:right;
    font-size:24px;
    cursor:pointer;
    font-weight:bold;
}
/* HEADER MAIN LAYOUT */
.header-main{
    display:flex;
    align-items:center;
    gap:15px;
    width:100%;
}

/* LEFT */
.header-left{
    font-size:20px;
    font-weight:600;
    min-width:180px;
}

/* CENTER */
.header-center{
    flex:1;
    display:flex;
    justify-content:center;
    gap:30px;
    font-weight:700;
    font-size:16px;
    flex-wrap:wrap;
    text-align:center;
}

/* RIGHT */
.header-right{
    display:flex;
    gap:12px;
    align-items:center;
    margin-left:auto;
}

/* HEADER BUTTON */
.btn-header{
    font-size:15px;
    font-weight:600;
    padding:7px 16px;
    border:1px solid #555;
    background:#f5f5f5;
    color:#000;
    border-radius:3px;
    cursor:pointer;
    text-decoration:none;
}

.btn-header:hover{
    background:#e6e6e6;
}


</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Periodically refresh table and status - preserved with safer selectors
$(function(){
    setInterval(function(){
        // load only inner contents to avoid reloading whole page
        $('#getdata').load(window.location.pathname + '?c1=<?=urlencode($_REQUEST['c1'])?>&l=<?=urlencode($_REQUEST['l'])?> #getdata');
    },20000);
    setInterval(function(){
        $('#status').load(window.location.pathname + '?c1=<?=urlencode($_REQUEST['c1'])?>&l=<?=urlencode($_REQUEST['l'])?> #status');
    },20000);
});
</script>

<script>
$(document).ready(function(){

    // OPEN TCP MODAL
    $("#tcpIcon").on("click", function(){
        $("#tcpModal").fadeIn(200);
    });

    // CLOSE MODAL
    $(".tcp-close").on("click", function(){
        $("#tcpModal").fadeOut(200);
    });

    // CLICK OUTSIDE TO CLOSE
    $(document).on("click", function(e){
        if($(e.target).closest(".tcp-modal-content").length === 0 &&
           !$(e.target).closest("#tcpIcon").length){
            $("#tcpModal").fadeOut(200);
        }
    });

});
</script>


<div class="container-fluid-custom">
    <!-- TOP HEADER (RESTORED OLD LAYOUT) -->
<div class="material-card header-main">

    <!-- DEVICE NAME -->
    <div class="header-left">
        <?= htmlspecialchars($All_Devicename[1]) ?>
    </div>

    <!-- GAD VALUES -->
    <div class="header-center">
        <span>GAD Today : <?= is_numeric($GAD_Today) ? round($GAD_Today,2)." Kwh" : "Nil" ?></span>
        <span>GAD Yesterday : <?= is_numeric($GAD_Yesterday) ? round($GAD_Yesterday,2)." Kwh" : "Nil" ?></span>
        <span>GAD Month : <?= is_numeric($GAD_Thismonth) ? round($GAD_Thismonth,2)." Kwh" : "Nil" ?></span>
    </div>

    <!-- RIGHT BUTTONS -->
    <div class="header-right">

        <!-- REPORTS -->
        <a class="btn-header"
           href="channel2_ajax.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?>&FType=<?= $_REQUEST['FType'] ?>">
            Reports
        </a>

        <!-- REMOTE -->
        <button class="btn-header" id="tcpIcon">
            Remote
        </button>

    </div>

</div>


    <div id="getdata" class="table-responsive">
        <table class="innertab1" role="table">
            <thead>
                <tr class="tab-head-tr-new">
                    <td colspan="6">Status</td>
                    <td colspan="9">Electrical</td>
                    <td colspan="7">Temperature</td>
                    <td colspan="3">Active Production</td>
                    <td colspan="4">Hours</td>
                </tr>
                <tr class="tab-head-tr-new">
                    <th>Date</th>
                    <th>Time</th>
                    <th>GRPM</th>
                    <th>RRPM</th>
                    <th>Status</th>
                    <th>Wind Spd</th>
                    <th>Power</th>
                    <th>R Volt</th>
                    <th>Y Volt</th>
                    <th>B Volt</th>
                    <th>R Cur</th>
                    <th>Y Cur</th>
                    <th>B Cur</th>
                    <th>Power Fact</th>
                    <th>Freq</th>
                    <th>Ambt</th>
                    <th>Nacel</th>
                    <th>Gear</th>
                    <th>Gen1</th>
                    <th>Bear</th>
                    <th>Cntrl</th>
                    <th>Hydr</th>
                    <th>Gen1</th>
                    <th>Gen2</th>
                    <th>Total</th>
                    <th>Total</th>
                    <th>Gen1</th>
                    <th>Run</th>
                    <th>Line Ok</th>
                </tr>
            </thead>
            <tbody>
<?php
$rowColors = array('transparent','#ffffff');
$i = 0;
$Mysql_Query = "select * from $Database_Name.device_data where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
if (!$Mysql_Query_Result = $db->query($Mysql_Query)) {
    die($db->error);
}
$Mysql_Record_Count = $Mysql_Query_Result->num_rows;
if ($Mysql_Query_Result->num_rows >= 1) {
    while ($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
        $GRPM = $Fetch_Result['GRPM'];
        $RRPM = $Fetch_Result['RRPM'];
        $WindSpeed = $Fetch_Result['Windspeed'];
        $Status = str_replace('#','',$Fetch_Result['Status']);
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
        if ($Frequency < 40) $Frequency = 49.85;
        $PAT_Gen0 = $Fetch_Result['PAT_Gen0'];
        $PAT_Gen1 = $Fetch_Result['PAT_Gen1'];
        $PAT_Total = $Fetch_Result['PAT_Gen2'];
        $Ambient_Temp = $Fetch_Result['Ambient_Temp'];
        $Nacelle_Temp = $Fetch_Result['Nacel_Temp'];
        $Gear_Temp = $Fetch_Result['Gear_Temp'];
        $Gen1_Temp = $Fetch_Result['Gen1_Temp'];
        $PATP_Gen1 = $Fetch_Result['Bearing_Temp'];
        $PATP_Total = $Fetch_Result['Control_Temp'];
        $Hydraulic_Temp = $Fetch_Result['Hydraulic_Temp'];
        $Total = $Fetch_Result['Total_Hours'];
        $Line_Ok = $Fetch_Result['Line_Ok'];
        $Turbine_Ok = $Fetch_Result['Turbine_Ok'];
        $Run = $Fetch_Result['Run_Hours'];
        $Gen1 = $Fetch_Result['Gen1_Hours'];

        echo '<tr style="background-color:'. $rowColors[$i++ % count($rowColors)] .';">';
        echo '<td>'.htmlspecialchars($Date_S).'</td>';
        echo '<td>'.htmlspecialchars($Time_S).'</td>';
        echo '<td>'.htmlspecialchars($GRPM).'</td>';
        echo '<td>'.htmlspecialchars($RRPM).'</td>';

        // Status color mapping
        $statusColor = 'red';
        $upStates = array('Run','M/C Running','RUN','OperateG1','OperateG2','OPERATING   NORMAL OPERATION');
        $blueStates = array('Grid Drop','GridDrop');
        if (in_array($Status, $upStates)) $statusColor = 'green';
        elseif (in_array($Status, $blueStates)) $statusColor = 'blue';

        echo '<td style="color:'. $statusColor .';">'.htmlspecialchars($Status).'</td>';
        echo '<td>'.htmlspecialchars(str_replace('m/s','',$WindSpeed)).'</td>';
        echo '<td>'.htmlspecialchars($Power).'</td>';
        echo '<td>'.htmlspecialchars($Rphase_Volt).'</td>';
        echo '<td>'.htmlspecialchars($Yphase_Volt).'</td>';
        echo '<td>'.htmlspecialchars($Bphase_Volt).'</td>';
        echo '<td>'.htmlspecialchars($Rphase_Current).'</td>';
        echo '<td>'.htmlspecialchars($Yphase_Current).'</td>';
        echo '<td>'.htmlspecialchars($Bphase_Current).'</td>';
        echo '<td>'.htmlspecialchars($Power_factor).'</td>';
        echo '<td>'.htmlspecialchars($Frequency).'</td>';
        echo '<td>'.htmlspecialchars($Ambient_Temp).'</td>';
        echo '<td>'.htmlspecialchars($Nacelle_Temp).'</td>';
        echo '<td>'.htmlspecialchars($Gear_Temp).'</td>';
        echo '<td>'.htmlspecialchars($Gen1_Temp).'</td>';
        echo '<td>'.htmlspecialchars($PATP_Gen1).'</td>';
        echo '<td>'.htmlspecialchars($PATP_Total).'</td>';
        echo '<td>'.htmlspecialchars($Hydraulic_Temp).'</td>';
        echo '<td>'.htmlspecialchars($PAT_Gen0).'</td>';
        echo '<td>'.htmlspecialchars($PAT_Gen1).'</td>';
        echo '<td>'.htmlspecialchars($PAT_Total).'</td>';
        echo '<td>'.htmlspecialchars($Total).'</td>';
        echo '<td>'.htmlspecialchars($Gen1).'</td>';
        echo '<td>'.htmlspecialchars($Run).'</td>';
        echo '<td>'.htmlspecialchars($Line_Ok).'</td>';
        echo '</tr>';
    }
} else {
    echo $No_Records;
}
?>
            </tbody>
        </table>
    </div>

   
    <div style="margin-top:16px;">
        <iframe class="iframe-full" src="Power_Windspeed_chart_Monthly_iframe.php?c1=<?=urlencode($_REQUEST['c1'])?>&Year=<?=date('m-Y')?>&l=<?=urlencode($_REQUEST['l'])?>" style="height:350px;"></iframe>
		
		<?php if ($Cook_Variable[0] != 'jjwind' && $Cook_Variable[0] != 'shanmugam') { ?>
    <div style="margin-top:16px;">
        <iframe class="iframe-full" src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?=urlencode($_REQUEST['c1'])?>&l=<?=urlencode($_REQUEST['l'])?>&FType=<?=urlencode($_REQUEST['FType'])?>" style="height:300px;"></iframe>
    </div>

    
    <?php } ?>
    </div>

    

    <div style="height:20px;"></div>

</div>

<!-- TCP POPUP MODAL -->
<div id="tcpModal" class="tcp-modal">
    <div class="tcp-modal-content">
        <span class="tcp-close">&times;</span>

        <!-- LOAD TCP REQUEST FORM -->
        <iframe id="tcpFrame" src="TcpRequest.php?c1=<?=urlencode($_REQUEST['c1'])?>&db=<?=htmlspecialchars($Database_Name)?>"
                style="width:100%;height:280px;border:0;"></iframe>
    </div>
</div>


<?php  ?>
