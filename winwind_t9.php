<?php
error_reporting(0);
include("header_inner.php");
if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}
$Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
if (isset($Cook_Variable)) {
    $Username   = base64_encode($Cook_Variable[0]);
    $Pass       = base64_encode($Cook_Variable[8]);
    $PW         = base64_encode($Cook_Variable[9]);
    $Account_ID = $Cook_Variable[3];
}

// Request and variable handling
$lastRecd      = null;
$IMEI          = $_REQUEST['c1'];
$OPCID         = $_REQUEST['UID'];
$Pocket_Length = isset($_REQUEST['l']) ? $_REQUEST['l'] : '';
$IMEI_Decode   = base64_decode($IMEI);
$FType         = $_REQUEST['FType'];
if (isset($_REQUEST['Db_Name'])) {
    $Database_Name = $_REQUEST['Db_Name'];
}

// Getting the customer information
$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour,a.Db_Name as Database_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
if (!$Fetch_Info_Result = $db->query($Fetch_Info)) {
    die($db->error);
}
if ($Fetch_Info_Result->num_rows >= 1) {
    $x = 1;
    while ($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {
        $All_HTSC_No[$x]    = $Fetch_Details_Result['HTSC_No'];
        $All_LOC_No[$x]     = $Fetch_Details_Result['LOC_No'];
        $All_WEG_No[$x]     = $Fetch_Details_Result['WEG_No'];
        $All_Firstname[$x]  = $Fetch_Details_Result['Firstname'];
        $All_Devicename[$x] = $Fetch_Details_Result['Device_Name'];
        $Site_Location[$x]  = $Fetch_Details_Result['Site_Location'];
        $SF_No[$x]          = $Fetch_Details_Result['SF_No'];
        $DOC[$x]            = $Fetch_Details_Result['DOC'];
        $Date_Of_Commission = $Fetch_Details_Result['Date_Of_Commission'];
        $Capacity[$x]       = $Fetch_Details_Result['Capacity'];
        $Closing_Time[$x]   = $Fetch_Details_Result['Closing_Hour'];
        $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
        $Database_Name      = $Fetch_Details_Result['Database_Name'];
        $x++;
    }
}

// Closing time based adjustments
if ($Closing_Time[1] == '06:00:00' || $Closing_Time[1] == '06:30:00') {
    $GAD_Time = " and Hour(Time_S)>=6 ";
    $GD_Time  = time() - 21660;
} elseif ($Closing_Time[1] == '07:00:00' || $Closing_Time[1] == '07:30:00') {
    $GAD_Time = " and Hour(Time_S)>=7 ";
    $GD_Time  = time() - 25200;
} elseif ($Closing_Time[1] == '08:00:00' || $Closing_Time[1] == '08:30:00') {
    $GAD_Time = " and Hour(Time_S)>=8 ";
    $GD_Time  = time() - 28800;
} elseif ($Closing_Time[1] == '09:00:00') {
    $GAD_Time = " and Hour(Time_S)>=9 ";
    $GD_Time  = time() - 32400;
} elseif ($Closing_Time[1] == '01:00:00' || $Closing_Time[1] == '01:30:00') {
    $GAD_Time = " and Hour(Time_S)>=1 ";
    $GD_Time  = time() - 3600;
} elseif ($Closing_Time[1] == '02:00:00' || $Closing_Time[1] == '02:30:00') {
    $GAD_Time = " and Hour(Time_S)>=2 ";
    $GD_Time  = time() - 7200;
} else {
    $GAD_Time = "";
    $GD_Time  = time();
    $Test_Time = date('H', $GD_Time);
}

// GAD query (unchanged)
$Mysql_Query_GAD = "select (select (Gen1_Max) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,
(select (Gen1_Max) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day)) as GAD_Yesterday,
(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S Between ((CURDATE()  - INTERVAL 7 DAY)) and curdate()) as GAD_Thisweek,
(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and month(Date_s)=month(now())) as GAD_Thismonth,
(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S Between (curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY) and (curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY)) as GAD_Previousweek";

if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)) {
    die($db->error);
}

if ($Mysql_Query_Result_GAD->num_rows >= 1) {
    while ($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
        $GAD_Today        = $Fetch_Result_GAD['GAD_Today'];
        $GAD_Yesterday    = $Fetch_Result_GAD['GAD_Yesterday'];
        $GAD_Thisweek     = $Fetch_Result_GAD['GAD_Thisweek'];
        $GAD_Thismonth    = $Fetch_Result_GAD['GAD_Thismonth'];
        $GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];
    }
}

// No records default
$No_Records = '<tr>\n\t\t<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>\n\t</tr>';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VersatileSCADA - Detailed Information</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Local JS/CSS (kept as original) -->
    <script type="text/javascript" src="js/jq1.js"></script>
    <script type="text/javascript" src="js/jscript.js"></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>

    <style>
        /* Minor visual adjustments while keeping original styling intent */
        .innertab1 th, .innertab1 td { white-space:nowrap; }
        .page-header { margin: 20px 0; }
        .device-name { font-weight:700; font-size:1.25rem; }
        .gad-table td { vertical-align: middle; }
    </style>
</head>
<body>

<script>
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

<div class="container-fluid py-3">
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="page-header">Energy from VersatileSCADA - Detailed Information</h2>
            <p class="mb-0">About Status, Temperatures, Electrical, Production Figures</p>
        </div>
        <div class="col-md-4 text-md-start text-center device-name">
            <?= isset($All_Devicename[1]) ? $All_Devicename[1] : '' ?>
        </div>
        <div class="col-md-2 text-end">
            <iframe src="TcpRequest.php?c1=<?= $_REQUEST['c1'] ?>&db=<?= $Database_Name ?>" style="background-color:transparent; border:0; height:80px; width:100%; max-width:480px;"></iframe>
            <div class="mt-2 text-end"><a href="dashboard.php" class="btn btn-sm btn-outline-secondary"><img src="images/back_btn.png" height="24" width="24" alt="Back"></a></div>
        </div>
    </div>

    <div id="getdata">

        <!-- First data table (responsive) -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-striped innertab1">
                <thead class="table-dark text-center">
                    <tr>
                        <th colspan="6">Status</th>
                        <th colspan="8">Electrical</th>
                        <th colspan="9">Temperature</th>
                        <th colspan="3">Active Production</th>
                        <th></th>
                    </tr>
                    <tr class="text-center">
                        <th>Date</th>
                        <th>Time</th>
                        <th>GRPM</th>
                        <th>RRPM</th>
                        <th style="width:90px;">Status</th>
                        <th style="width:40px;">Wind Spd</th>
						<th>Pitch</th>
                        <th>Power</th>
                        <th>R Volt</th>
                        <th>Y Volt</th>
                        <th>B Volt</th>
                        <th>R Cur</th>
                        <th>Y Cur</th>
                        <th>B Cur</th>                        
                        <th>G1_L1</th>
                        <th>G1_L2</th>
                        <th>G1_L3</th>
                        <th>G2_L1</th>
                        <th>Gear OilSump</th>
                        <th>Hub Bear</th>
                        <th>Outdoor</th>
                        <th>G2_L2</th>
                        <th>Gearbox HSS</th>
                        <th>Day</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Event</th>
                    </tr>
                    <tr class="text-center small">
                        <th></th>
                        <th></th>
                        <th>rpm</th>
                        <th>rpm</th>
                        <th></th>
                        <th>m/s</th>
                        <th>KW</th>
                        <th>V</th>
                        <th>V</th>
                        <th>V</th>
                        <th>A</th>
                        <th>A</th>
                        <th>A</th>
                        <th></th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>deg</th>
                        <th>Kwh</th>
                        <th>Kwh</th>
                        <th>Kwh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php
$rowColors = array('#e6f2ff', '#e6f2ff');
$i = 0;

// Getting the data from DEVICE_DATA based on IMEI (unchanged query)
$Mysql_Query = "select * from $Database_Name.device_data_f9 where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
if (!$Mysql_Query_Result = $db->query($Mysql_Query)) {
    die($db->error);
}

if ($Mysql_Query_Result->num_rows >= 1) {
    while ($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
        $Project_Version    = $Fetch_Result['Project_Version'];
        $ID_Number          = $Fetch_Result['ID_Number'];
        $GRPM               = $Fetch_Result['GRPM'];
        $RRPM               = $Fetch_Result['RRPM'];
        $WindSpeed          = $Fetch_Result['Windspeed'];
        $Pitch              = $Fetch_Result['Pitch'];
        $Status             = $Fetch_Result['Status'];
        $Date_B             = $Fetch_Result['Date_S'];
        $Time_B             = $Fetch_Result['Time_S'];
        $Date_S             = $Fetch_Result['Date'];
        $Time_S             = $Fetch_Result['Time'];
        $Power              = $Fetch_Result['Power'];
        $Rphase_Volt        = $Fetch_Result['RPhase_Volt'];
        $Yphase_Volt        = $Fetch_Result['YPhase_Volt'];
        $Bphase_Volt        = $Fetch_Result['BPhase_Volt'];
        $Rphase_Current     = $Fetch_Result['RPhase_Current'];
        $Yphase_Current     = $Fetch_Result['YPhase_Current'];
        $Bphase_Current     = $Fetch_Result['BPhase_Current'];
        $Power_factor       = $Fetch_Result['Power_Factor'];
        $Frequency          = $Fetch_Result['Frequency'];
        $Production_kwh     = $Fetch_Result['P_Kwh'];
        $Consumption_kwh    = $Fetch_Result['C_Kwh'];
        $Production_kvarh   = $Fetch_Result['P_Kvarh'];
        $Consumption_kvarh  = $Fetch_Result['C_Kvarh'];
        $Hyd_Pressure       = $Fetch_Result['Oil_Pressure'];
        $PAM_Total          = $Fetch_Result['PAM_Gen2'];
        $Wind_Direction     = $Fetch_Result['Wind_Direction'];
        $Total              = $Fetch_Result['Total_Hours'];
        $G1_L1Temp          = $Fetch_Result['G1_L1Temp'];
        $G1_L2Temp          = $Fetch_Result['G1_L2Temp'];
        $G1_L3Temp          = $Fetch_Result['G1_L3Temp'];
        $G2_L1Temp          = $Fetch_Result['G2_L1Temp'];
        $Gear_OilSump       = $Fetch_Result['Gear_OilSump'];
        $Hub_Bearing        = $Fetch_Result['Hub_Bearing'];
        $Outdoor            = $Fetch_Result['Outdoor'];
        $G2_L2Temp          = $Fetch_Result['G2_L2Temp'];
        $Gearbox_HSS        = $Fetch_Result['Gearbox_HSS'];
        $Event              = $Fetch_Result['Eventlog'];
        $Date_F             = $Fetch_Result['Date_F'];
        $Time_F             = $Fetch_Result['Time_F'];

        // Removing # symbol
        $Hydraulic = str_replace('#', '', $Hydraulic);
        $Status    = str_replace('#', '', $Status);
        $lastRecd  = str_replace('.', '-', $Date_F);
        $WindSpeed = str_replace('m/s', '', $WindSpeed);

        echo '<tr style="background-color:'.$rowColors[$i++ % count($rowColors)].';">';
        echo '<td class="text-center">'.$Date_S.'</td>';
        echo '<td class="text-center">'.$Time_S.'</td>';
        echo '<td class="text-center">'.$GRPM.'</td>';
        echo '<td class="text-center">'.$RRPM.'</td>';

        // Status color logic (unchanged)
        if ($Status == 'CUT-IN G1-G2' || $Status == 'CUT-IN G2-G1' || $Status == 'RUNNING G1' || $Status == 'RUNNING G2') 
		{
            echo '<td class="text-center" style="color:green">'.$Status.'</td>';
        } elseif ($Status == 'FREE-WHEELING G1' || $Status == 'FREE-WHEELING G2' || $Status == 'FREE-WHEELING G2-G' || $Status == 'FREE-WHEELING G1-G') {
            echo '<td class="text-center" style="color:orange">'.$Status.'</td>';
		} elseif ($Status == 'Grid Drop' || $Status == 'GridDrop') {
            echo '<td class="text-center" style="color:blue">'.$Status.'</td>';
        } else {
            echo '<td class="text-center" style="color:red">'.$Status.'</td>';
        }

        echo '<td class="text-center">'.$WindSpeed.'</td>';
        echo '<td class="text-center">'.$Power.'</td>';
        echo '<td class="text-center">'.$Rphase_Volt.'</td>';
        echo '<td class="text-center">'.$Yphase_Volt.'</td>';
        echo '<td class="text-center">'.$Bphase_Volt.'</td>';
        echo '<td class="text-center">'.$Rphase_Current.'</td>';
        echo '<td class="text-center">'.$Yphase_Current.'</td>';
        echo '<td class="text-center">'.$Bphase_Current.'</td>';
        echo '<td class="text-center">'.$Pitch.'</td>';
        echo '<td class="text-center">'.$G1_L1Temp.'</td>';
        echo '<td class="text-center">'.$G1_L2Temp.'</td>';
        echo '<td class="text-center">'.$G1_L3Temp.'</td>';
        echo '<td class="text-center">'.$G2_L1Temp.'</td>';
        echo '<td class="text-center">'.$Gear_OilSump.'</td>';
        echo '<td class="text-center">'.$Hub_Bearing.'</td>';
        echo '<td class="text-center">'.$Outdoor.'</td>';
        echo '<td class="text-center">'.$G2_L2Temp.'</td>';
        echo '<td class="text-center">'.$Gearbox_HSS.'</td>';
        echo '<td class="text-center">'.$Production_kwh.'</td>';
        echo '<td class="text-center">'.$Production_kvarh.'</td>';
        echo '<td class="text-center">'.$Consumption_kvarh.'</td>';
        echo '<td class="text-center">'.$Event.'</td>';
        echo '</tr>';

        $MI++;
    }
}
?>
                </tbody>
            </table>
        </div>

        <div class="mb-3"></div>

        <!-- Second data table -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-striped innertab1">
                <thead class="table-dark text-center">
                    <tr>
                        <th colspan="7">Status</th>
                        <th colspan="8">Electrical</th>
                        <th></th>
                    </tr>
                    <tr class="text-center">
                        <th>Date</th>
                        <th>Time</th>
                        <th>GRPM</th>
                        <th>RRPM</th>
                        <th style="width:90px;">Status</th>
                        <th style="width:90px;">Event</th>
                        <th style="width:40px;">Wind Spd</th>
                        <th>Power</th>
                        <th>Pitch Angle 1</th>
                        <th>Pitch Angle 2</th>
                        <th>Pitch Angle 3</th>
                        <th>Battery 1</th>
                        <th>Battery 2</th>
                        <th>Battery 3</th>
                        <th>Twist</th>
                        <th>Nacelle Position</th>
                    </tr>
                    <tr class="text-center small">
                        <th></th>
                        <th></th>
                        <th>rpm</th>
                        <th>rpm</th>
                        <th></th>
                        <th></th>
                        <th>m/s</th>
                        <th>KW</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php
$rowColors = array('#e6f2ff', '#e6f2ff');
$i = 0;

// Getting the data from DEVICE_DATA based on IMEI (2nd query)
$Mysql_Query1 = "select * from $Database_Name.device_data_f9 where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
if (!$Mysql_Query_Result1 = $db->query($Mysql_Query1)) {
    die($db->error);
}

if ($Mysql_Query_Result1->num_rows >= 1) {
    while ($Fetch_Result1 = $Mysql_Query_Result1->fetch_array()) {
        $GRPM1            = $Fetch_Result1['GRPM'];
        $RRPM1            = $Fetch_Result1['RRPM'];
        $WindSpeed1       = $Fetch_Result1['Windspeed'];
        $Status1          = $Fetch_Result1['DataScan_Count'];
        $Date_S1          = $Fetch_Result1['Date'];
        $Time_S1          = $Fetch_Result1['Time'];
        $Power1           = $Fetch_Result1['Power'];
        $PA1              = $Fetch_Result1['Prev_P_Kwh'];
        $PA2              = $Fetch_Result1['Prev_C_Kwh'];
        $PA3              = $Fetch_Result1['Prev_P_Kvarh'];
        $devicedata       = explode(' ', $Fetch_Result1['OPC_ID']);
        $B1               = isset($devicedata[0]) ? $devicedata[0] : '';
        $B2               = isset($devicedata[1]) ? $devicedata[1] : '';
        $B3               = isset($devicedata[2]) ? $devicedata[2] : '';
        $Twist            = $Fetch_Result1['Twist'];
        $Nacelle_Position = $Fetch_Result1['Nacelle'];
        $Event1           = $Fetch_Result1['Eventlog'];

        // Removing # symbol
        $Status1   = str_replace('#', '', $Status1);
        $WindSpeed1 = str_replace('m/s', '', $WindSpeed1);

        echo '<tr style="background-color:'.$rowColors[$i++ % count($rowColors)].';">';
        echo '<td class="text-center">'.$Date_S1.'</td>';
        echo '<td class="text-center">'.$Time_S1.'</td>';
        echo '<td class="text-center">'.$GRPM1.'</td>';
        echo '<td class="text-center">'.$RRPM1.'</td>';

        if ($Status1 == 'System OK') {
            echo '<td class="text-center" style="color:green">'.$Status1.'</td>';
        } elseif ($Status1 == 'WARNING' || $Status1 == 'WARNING') {
            echo '<td class="text-center" style="color:orange">'.$Status1.'</td>';
        } else {
            echo '<td class="text-center" style="color:red">'.$Status1.'</td>';
        }

        echo '<td class="text-center">'.$Event1.'</td>';
        echo '<td class="text-center">'.$WindSpeed1.'</td>';
        echo '<td class="text-center">'.$Power1.'</td>';
        echo '<td class="text-center">'.$PA1.'</td>';
        echo '<td class="text-center">'.$PA2.'</td>';
        echo '<td class="text-center">'.$PA3.'</td>';
        echo '<td class="text-center">'.$B1.'</td>';
        echo '<td class="text-center">'.$B2.'</td>';
        echo '<td class="text-center">'.$B3.'</td>';
        echo '<td class="text-center">'.$Twist.'</td>';
        echo '<td class="text-center">'.$Nacelle_Position.'</td>';
        echo '</tr>';

        $MI++;
    }
}
?>
                </tbody>
            </table>
        </div>

        <!-- GAD Details -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <table class="table table-bordered gad-table">
                    <thead class="table-secondary">
                        <tr><th colspan="2">GAD Details</th></tr>
                    </thead>
                    <tbody>
<?php
if ($Mysql_Query_Result->num_rows >= 1) {
?>
                        <tr>
                            <td class="fw-bold">GAD for Today</td>
                            <td><?= $GAD_Today > 30000 || $GAD_Today < 0 ? "Nil" : $GAD_Today . " Kwh" ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">GAD for Yesterday</td>
                            <td><?= $GAD_Yesterday > 30000 || $GAD_Yesterday < 0 ? "Nil" : $GAD_Yesterday . " Kwh" ?></td>
                        </tr>
<?php
} else {
    echo $No_Records;
}
?>
                    </tbody>
                </table>
            </div>
        </div>
		</div> <!-- /#getdata -->
        <div class="text-center my-3">
            <iframe src="channel9new_ajax.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?>&FType=<?= $_REQUEST['FType'] ?>" style="border:1px solid #168A83; width:100%; max-width:1050px; height:300px;"></iframe>
        </div>

    

</div> <!-- /.container-fluid -->

<!-- Bootstrap 5 JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto-refresh JS (kept functionality but using jQuery already loaded) -->
<script>
$(document).ready(function() {
    setInterval(function() {
        $('#getdata').load('channel9new.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?> #getdata');
    }, 20000);

    setInterval(function() {
        $('#status').load('channel9new.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?> #status');
    }, 20000);
});
</script>

<?php include("footer.php"); ?>

</body>
</html>