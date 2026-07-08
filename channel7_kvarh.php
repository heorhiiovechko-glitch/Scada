<?php
error_reporting(0);
include("header_inner.php");
if(empty($_COOKIE[$Cook_Name])){
    header("Location:index.php");
    exit;
}
$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);
if(isset($Cook_Variable)){
    $Username = base64_encode($Cook_Variable[0]);
    $Pass = base64_encode($Cook_Variable[8]);
}

/*
 NOTE: I kept all backend logic, queries and variables exactly as in your original file.
 The changes below are purely presentational: modern CSS, responsive layout, cleaner markup
 while keeping the PHP logic intact.
*/

$lastRecd = null;
$IMEI = $_REQUEST['c1'];
if(isset($_REQUEST['l']))
    $Pocket_Length = $_REQUEST['l'];
else
    $Pocket_Length = '';
$IMEI_Decode = base64_decode($IMEI);
$FType = $_REQUEST['FType'];
if(isset($_REQUEST['Db_Name'])) {
    $Database_Name = $_REQUEST['Db_Name'];
}

if($Database_Name == "va_sarvesh") {
    $Database_Name = "va_sendan";
}

// Getting the customer information (kept unchanged)
$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour,a.Db_Name as Database_Name from device_register a,user_master b where IMEI = '".$IMEI_Decode."'";
if (!$Fetch_Info_Result = $db->query($Fetch_Info)) { die($db->error); }
if($Fetch_Info_Result->num_rows >= 1) {
    $x=1;
    while($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {
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
        $Database_Name = $Fetch_Details_Result['Database_Name'];
        $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
        $x++;
    }
}

// GAD time logic (preserved)
if($Closing_Time[1]=='06:00:00' || $Closing_Time[1]=='06:30:00'){
    $GAD_Time=" and Hour(Time_S)>=6 ";
    $GD_Time=time()-21660;
} elseif($Closing_Time[1]=='07:00:00' || $Closing_Time[1]=='07:30:00'){
    $GAD_Time=" and Hour(Time_S)>=7 ";
    $GD_Time=time()-25200;
} elseif($Closing_Time[1]=='08:00:00' || $Closing_Time[1]=='08:30:00'){
    $GAD_Time=" and Hour(Time_S)>=8 ";
    $GD_Time=time()-28800;
} elseif($Closing_Time[1]=='09:00:00'){
    $GAD_Time=" and Hour(Time_S)>=9 ";
    $GD_Time=time()-32400;
} elseif($Closing_Time[1]=='01:00:00' || $Closing_Time[1]=='01:30:00'){
    $GAD_Time=" and Hour(Time_S)>=1 ";
    $GD_Time=time()-3600;
} elseif($Closing_Time[1]=='02:00:00' || $Closing_Time[1]=='02:30:00'){
    $GAD_Time=" and Hour(Time_S)>=2 ";
    $GD_Time=time()-7200;
} else {
    $GAD_Time="";
    $GD_Time=time();
    $Test_Time=date('H',$GD_Time);
}

$Mysql_Query_GAD = "select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and db_name = '".$Database_Name."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and db_name = '".$Database_Name."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and db_name = '".$Database_Name."'  and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and db_name = '".$Database_Name."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and db_name = '".$Database_Name."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";
if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)) { die($db->error); }
if($Mysql_Query_Result_GAD->num_rows >= 1) {
    while($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
        $GAD_Today = $Fetch_Result_GAD['GAD_Today'];
        $GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
        $GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
        $GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
        $GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];
    }
}

$ER_Mysql_Query = "select Status as Log,Date_S,Time_S from $Database_Name.device_data_f6 where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
if (!$ER_Mysql_Query_Result = $db->query($ER_Mysql_Query)) { die($db->error); }
if($ER_Mysql_Query_Result->num_rows >= 1) {
    $ER_Fetch_Result = $ER_Mysql_Query_Result->fetch_array();
    $Log_Status = $ER_Fetch_Result['Log'];
}

$No_Records = '<tr>\n\t\t<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>\n\t</tr>';

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Device View - Styled</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
:root{--teal:#018f8a;--accent:#0d9b97;--muted:#f3f5f9;--card:#ffffff;--text:#123;}
*{box-sizing:border-box}
body{font-family:Roboto, Arial, sans-serif;background:var(--muted);color:var(--text);margin:0;padding:0}
.container{width:100% !important;max-width:100% !important;margin:0 !important;padding:0 !important;}
.top-bar{display:flex;align-items:center;justify-content:space-between;background:var(--teal);color:#fff;padding:10px 18px;border-radius:4px}
.device-title{font-weight:700;font-size:18px}
.gad-row{display:flex;gap:28px;justify-content:center;align-items:center;background: #f6fbfb;padding:10px;border-radius:4px;margin-top:12px}
.gad-row span{font-weight:700;color:#0b3b3b}
.grid{display:grid;grid-template-columns:1fr;gap:14px;margin-top:14px}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.card{background:var(--card);border-radius:0;box-shadow:none;padding:10px;width:100%;margin:0 !important;}
.table-wrap{overflow:auto;border-radius:0;width:100%;margin:0;padding:0;}
.table{width:100%;border-collapse:collapse;font-size:13px;white-space:normal !important;word-wrap:break-word !important;}
.table thead th{position:sticky;top:0;background:#e9f3f3;padding:10px;font-weight:700;border-bottom:1px solid #e3eef0;color:#094}
.table tbody td {
    padding:10px 8px;
    border-bottom:1px solid #f0f4f6;
}


.status-green{color:green;font-weight:700}
.status-red{color:red;font-weight:700}
.status-blue{color:blue;font-weight:700}


.small-muted{font-size:12px;color:#6b7a7a}
.section-title{font-weight:700;padding:6px 10px;border-bottom:1px solid #eef4f5;margin-bottom:8px}
.iframe{width:100%;height:300px;border:0;border-radius:6px}
.responsive-iframe {
    width: 100% !important;
    height: 520px !important;   /* Make chart big like screenshot */
    border: 0;
}
.material-card {
    padding: 0 !important;
    margin: 0 !important;
    border-radius: 0;
    background: #fff;
}

/* TCP button on right */
.header-right { position:relative; display:flex; align-items:center; gap:12px; }
.tcp-icon-btn { width:44px; height:44px; border-radius:50%; background:#1976d2; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 3px 8px rgba(0,0,0,0.12); }
.tcp-icon-btn .material-icons { color:#fff; font-size:22px; }

/* Table styles matching Channel4 */
.table-wrap { padding:0 0 10px 0; }
.table { width:100%; border-collapse:collapse; font-size:14px; background:#fff; }
.table thead th { background:#e6eef0; padding:10px 8px; font-weight:700; color:#0b3b3b; text-transform:uppercase; border-bottom:1px solid #d8e2e6; position:sticky; top:0; z-index:3; }

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



@media (max-width:980px){.grid-2{grid-template-columns:1fr}.gad-row{flex-direction:column;gap:8px}}
</style>
</head>
<body>

<<!-- Combined Header Row (NO BACKGROUND) -->
<div style="
    width:100%;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 12px;
    background:none !important;
">

    <!-- LEFT — DEVICE NAME -->
    <div style="font-size:20px;font-weight:700;color:#000;">
        <?= htmlspecialchars($All_Devicename[1]) ?>
    </div>

    <!-- CENTER — GAD DETAILS -->
    <div style="
    display:flex;
    gap:35px;
    font-size:32px;
    font-weight:800;
    color:#000;
    justify-content:center;
    align-items:center;
    flex:1;
    line-height:1.3;
">
    <span>GAD Today : <?= is_numeric($GAD_Today)?round($GAD_Today,2)." Kwh":"Nil" ?></span>
    <span>GAD Yesterday : <?= is_numeric($GAD_Yesterday)?round($GAD_Yesterday,2)." Kwh":"Nil" ?></span>
    <span>GAD Month : <?= is_numeric($GAD_Thismonth)?round($GAD_Thismonth,2)." Kwh":"Nil" ?></span>
</div>

    <!-- RIGHT — TCP BUTTON -->
    <div onclick="openTCPModal()" style="
        width:40px;
        height:40px;
        border-radius:50%;
        background:#1976d2;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        box-shadow:0 3px 8px rgba(0,0,0,0.18);
        flex-shrink:0;
    ">
        <i class="material-icons" style="color:#fff;font-size:20px;">BOT</i>
    </div>

</div>



    <div class="grid">
        <div class="card table-card">
            
            <div class="table-wrap" id="getdata">
                <table class="table" aria-describedby="live-data">
                    <thead>
                        <tr>
                            <th>Date</th><th>Time</th><th>GRPM</th><th>RRPM</th><th>Wind Spd</th><th>Status</th>
                            <th>Power</th><th>R Volt</th><th>Y Volt</th><th>B Volt</th>
                            <th>R Cur</th><th>Y Cur</th><th>B Cur</th>
                            <?php if ($Cook_Variable[3]!='100133') { ?>
                                <th>Kvar</th><th>Freq</th><th>Ambt</th><th>Nacel</th><th>Gear</th><th>Gen1</th>
                                <th>Bear</th><th>Cntrl</th><th>Hydr</th><th>Imp Kwh</th><th>Gen1 Kwh</th><th>Total Kwh</th>
								<th>Imp Kvarh</th><th>Gen1 Kvarh</th><th>Total Kvarh</th>
                                <th>Total Hrs</th><th>Gen1 Hrs</th><th>Run Hrs</th><th>Line Ok</th>
                            <?php } else { ?>
                                <th>Oil Pressure</th><th>Twist</th><th>Nacelle</th><th>Wind Dir</th><th>Gear box</th>
                                <th>GW Cool</th><th>Hydr</th><th>Non drive Bear</th><th>3rr Bear</th><th>Gen Wind</th>
                                <th>Gear oil temp</th><th>Gear oil pipe</th><th>Ambt</th><th>Production</th><th>Consumption</th>
                                <th>Production</th><th>Consumption</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Row coloring preserved logically
                    $rowColors = array('#fff','#f9fcfc');
                    $i = 0;
                    $Mysql_Query = "select * from $Database_Name.device_data_f6 where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 10";
                    if (!$Mysql_Query_Result = $db->query($Mysql_Query)) { die($db->error); }
                    if($Mysql_Query_Result->num_rows >= 1) {
                        while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
                            // keep all variables unchanged
                            $GRPM = $Fetch_Result['GRPM'];
                            $RRPM = $Fetch_Result['RRPM'];
                            $WindSpeed = str_replace('m/s','',$Fetch_Result['Windspeed']);
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
                            $PAT_Gen0 = $Fetch_Result['PAT_Gen0'];
                            $PAT_Gen1 = $Fetch_Result['PAT_Gen1'];
                            $PAT_Total = $Fetch_Result['PAT_Gen2'];
							$PATP_Gen0 = $Fetch_Result['PAM_Gen0'];
							$PATP_Gen1 = $Fetch_Result['PAM_Gen1'];
							$PATP_Total = $Fetch_Result['PAM_Gen2'];
                            $Total = $Fetch_Result['Total_Hours'];
                            $Line_Ok = $Fetch_Result['Line_Ok'];
                            $Run = str_replace('#','',$Fetch_Result['Run_Hours']);
                            $Gen1 = $Fetch_Result['Gen1_Hours'];
                            $Ambient = $Fetch_Result['Ambient_Temp'];
                            $Nacelle = $Fetch_Result['Nacel_Temp'];
                            $Gear = $Fetch_Result['Gear_Temp'];
                            $Gen1_Temp = $Fetch_Result['Gen1_Temp'];
                            $Controller = $Fetch_Result['Control_Temp'];
                            $Hydraulic = str_replace('#','',$Fetch_Result['Hydraulic_Temp']);

                            echo '<tr style="background:'. $rowColors[$i++ % 2] .';">';
                            echo '<td>'.htmlspecialchars($Date_S).'</td>';
                            echo '<td>'.htmlspecialchars($Time_S).'</td>';
                            echo '<td>'.htmlspecialchars($GRPM).'</td>';
                            echo '<td>'.htmlspecialchars($RRPM).'</td>';

                            // Status color logic preserved
                            $statusClass = 'status-red';
                            if(in_array($Status,array('Run','M/C Running','RUN','OperateG1','OperateG2','OPERATING   NORMAL OPERATION','RUNNING G1'))) $statusClass='status-green';
                            elseif(in_array($Status,array('Grid Drop','GridDrop'))) $statusClass='status-blue';

                           echo '<td>'.htmlspecialchars($WindSpeed).'</td>';
							echo '<td class="'. $statusClass .'">'.htmlspecialchars($Status).'</td>';

                            echo '<td>'.htmlspecialchars($Power).'</td>';
                            echo '<td>'.htmlspecialchars($Rphase_Volt).'</td>';
                            echo '<td>'.htmlspecialchars($Yphase_Volt).'</td>';
                            echo '<td>'.htmlspecialchars($Bphase_Volt).'</td>';
                            echo '<td>'.htmlspecialchars($Rphase_Current).'</td>';
                            echo '<td>'.htmlspecialchars($Yphase_Current).'</td>';
                            echo '<td>'.htmlspecialchars($Bphase_Current).'</td>';

                            if ($Cook_Variable[3] != '100133') {
                                echo '<td>'.htmlspecialchars($Power_factor).'</td>';
                                echo '<td>'.htmlspecialchars($Frequency).'</td>';
                                echo '<td>'.htmlspecialchars($Ambient).'</td>';
                                echo '<td>'.htmlspecialchars($Nacelle).'</td>';
                                echo '<td>'.htmlspecialchars($Gear).'</td>';
                                echo '<td>'.htmlspecialchars($Gen1_Temp).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['Bearing_Temp']).'</td>';
                                echo '<td>'.htmlspecialchars($Controller).'</td>';
                                echo '<td>'.htmlspecialchars($Hydraulic).'</td>';
                                echo '<td>'.htmlspecialchars($PAT_Gen0).'</td>';
                                echo '<td>'.htmlspecialchars($PAT_Gen1).'</td>';
                                echo '<td>'.htmlspecialchars($PAT_Total).'</td>';
								echo '<td>'.htmlspecialchars($PATP_Gen0).'</td>';
                                echo '<td>'.htmlspecialchars($PATP_Gen1).'</td>';
                                echo '<td>'.htmlspecialchars($PATP_Total).'</td>';
                                echo '<td>'.htmlspecialchars($Total).'</td>';
                                echo '<td>'.htmlspecialchars($Gen1).'</td>';
                                echo '<td>'.htmlspecialchars($Run).'</td>';
                                echo '<td>'.htmlspecialchars($Line_Ok).'</td>';
                            } else {
                                // keep original columns for special account
                                echo '<td>'.htmlspecialchars($Fetch_Result['PAM_Gen1']).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['PATP_Gen0']).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['PATP_Gen1']).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['PATP_Gen2']).'</td>';
                                echo '<td>'.htmlspecialchars($Line_Ok).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['Turbine_Ok']).'</td>';
                                echo '<td>'.htmlspecialchars($Run).'</td>';
                                echo '<td>'.htmlspecialchars($Gen1).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['Month_Total']).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['Month_Line_Ok']).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['Month_Turbine_Ok']).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['Month_Run']).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['Month_Gen1']).'</td>';
                                echo '<td>'.htmlspecialchars($PAT_Gen0).'</td>';
                                echo '<td>'.htmlspecialchars($PAT_Gen1).'</td>';
                                echo '<td>'.htmlspecialchars($PAT_Total).'</td>';
                                echo '<td>'.htmlspecialchars($Fetch_Result['PAM_Gen0']).'</td>';
                                echo '</tr>';
                            }

                        }
                    } else {
                        echo '<tr><td colspan="30" style="text-align:center;padding:20px;">No Records Found</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

		<<div class="material-card" style="margin-top:20px;">
    <div class="section-header">Power vs WindSpeed Chart</div>

    <iframe class="responsive-iframe"
            src="Power_Windspeed_chart_Monthly_iframe.php?c1=<?= $_REQUEST['c1'] ?>&Year=<?= date('m-Y') ?>&l=<?= $_REQUEST['l'] ?>">
    </iframe>
</div>

	
        <div class="grid-2">
            <div class="card">
                <div class="section-title">Daily Generation Report</div>
                <iframe src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?php echo urlencode($_REQUEST['c1']); ?>&l=<?php echo urlencode($_REQUEST['l']); ?>&FType=<?php echo urlencode($_REQUEST['FType']); ?>" class="iframe"></iframe>
            </div>

            <div class="card">
                <div class="section-title">More Reports</div>
                <iframe src="channel2_ajax.php?c1=<?php echo urlencode($_REQUEST['c1']); ?>&l=<?php echo urlencode($_REQUEST['l']); ?>&FType=<?php echo urlencode($_REQUEST['FType']); ?>" class="iframe"></iframe>
            </div>

        </div>

        
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// preserve original auto-refresh behaviour (but load only necessary sections)
setInterval(function(){ $('#getdata').load(location.href + ' #getdata > *'); $('#status').load(location.href + ' #status > *'); }, 20000);
</script>

<!-- TCP Modal -->
<div id="tcpModal" style="display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;">
    <div style="width:420px;height:120px;background:#fff;border-radius:8px;padding:10px;position:relative;margin:auto;top:12%;">
        <div style="position:absolute;right:10px;top:6px;cursor:pointer;font-size:18px;color:#333;" onclick="closeTCPModal()">×</div>
        <iframe src="TcpRequest.php?c1=<?= urlencode($_REQUEST['c1']) ?>&db=<?= urlencode($Database_Name) ?>" style="width:100%;height:100%;border:0;"></iframe>
    </div>
</div>

<script>
function openTCPModal() {
    // Show modal container
    document.getElementById("tcpModal").style.display = "flex";

    // Load iframe URL dynamically to avoid caching
    document.querySelector("#tcpModal iframe").src =
        "TcpRequest.php?c1=<?= urlencode($_REQUEST['c1']) ?>&db=<?= urlencode($Database_Name) ?>";
}

function closeTCPModal() {
    document.getElementById("tcpModal").style.display = "none";
}
</script>


<?php include("footer.php"); ?>
</body>
</html>
