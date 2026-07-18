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
    $PW = base64_encode($Cook_Variable[9]);
    $Account_ID = $Cook_Variable[3];
}
?>

<?php

$lastRecd = null;

$IMEI = $_REQUEST['c1'];

if(isset($_REQUEST['l']))
    $Pocket_Length = $_REQUEST['l'];
else
    $Pocket_Length = '';

$IMEI_Decode = base64_decode($IMEI);

$FType = $_REQUEST['FType'];

if(isset($_REQUEST['Db_Name'])){
    $Database_Name = $_REQUEST['Db_Name'];
}


/* ================= DEVICE INFO ================= */

$Fetch_Info = "
select 
a.Device_Name,
a.Closing_Time
from device_register a
where IMEI = '".$IMEI_Decode."'
";

if(!$Fetch_Info_Result = $db->query($Fetch_Info)){
    die($db->error);
}

if($Fetch_Info_Result->num_rows >= 1){

    $x=1;

    while($r = $Fetch_Info_Result->fetch_array()){

        $All_Devicename[$x] = $r['Device_Name'];
        $Closing_Time[$x]  = $r['Closing_Time'];

        $x++;
    }
}


/* ================= GAD ================= */

$Mysql_Query_GAD="
select
(select (Gen1_Max-Gen1_Min) from device_register where IMEI='".$IMEI_Decode."' and Date_S=curdate() limit 1) as GAD_Today,

(select (Gen1_Max-Gen1_Min) from daily_data where IMEI='".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,

(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI='".$IMEI_Decode."' 
and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) 
AND LAST_DAY(NOW())) as GAD_Thismonth
";

if(!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)){
    die($db->error);
}

if($Mysql_Query_Result_GAD->num_rows >= 1){

    while($r = $Mysql_Query_Result_GAD->fetch_array()){

        $GAD_Today     = $r['GAD_Today'];
        $GAD_Yesterday = $r['GAD_Yesterday'];
        $GAD_Thismonth = $r['GAD_Thismonth'];
    }
}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>SCADA Dashboard</title>

<!-- ================= PROFESSIONAL STYLE ================= -->

<style>

/* ===== GLOBAL ===== */

body{
    background:#f3f6fb;
    font-family:"Segoe UI",Arial,sans-serif;
    margin:0;
    color:#222;
}

/* ===== MAIN CONTAINER ===== */

#body{
    width:100% !important;
    max-width:1600px;
    margin:20px auto;
    background:#ffffff;
    padding:15px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.12);
}

/* ===== HEADER ===== */

.header-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.header-title{
    font-size:22px;
    font-weight:600;
    color:#1f3c88;
}

.device-name{
    font-size:18px;
    font-weight:600;
}

/* ===== TABLE WRAPPER ===== */

#getdata{
    overflow-x:auto;
}

/* ===== MAIN TABLE ===== */

.innertab1{
    width:100%;
    border-collapse:collapse;
    background:#fff !important;
}

/* Header */

.tab-head-tr-new th,
.tab-head-tr-new td{
    background:#1f3c88 !important;
    color:#ffffff !important;
    font-size:13px;
    padding:8px 6px;
    border:1px solid #2f55b0;
}

/* Unit Row */

.tab-head-tr-new + tr td{
    background:#edf2ff !important;
    color:#333;
    font-weight:600;
}

/* Data Cells */

.tab-head-td-new td{
    padding:6px 5px;
    font-size:12px;
    border-bottom:1px solid #e0e0e0;
}

/* Zebra */

.innertab1 tr:nth-child(even){
    background:#f8faff;
}

/* Hover */

.innertab1 tr:hover{
    background:#eaf0ff !important;
}

/* Status */

.status-green{color:#2e7d32;font-weight:600;}
.status-blue{color:#1565c0;font-weight:600;}
.status-red{color:#c62828;font-weight:600;}

/* Links */

a{
    color:#1f3c88;
    text-decoration:none;
    font-weight:500;
}

a:hover{
    text-decoration:underline;
}

/* Iframe */

iframe{
    width:100% !important;
    border-radius:8px;
    border:1px solid #ddd;
}

/* Mobile */

@media(max-width:768px){

    #body{
        padding:10px;
    }

    .innertab1{
        font-size:11px;
    }

}

/* ===== BACK BUTTON BOX ===== */

/* ===== BACK BUTTON TEXT STYLE ===== */

.back-btn{
    width:60px;
    height:36px;
    display:flex;
    align-items:center;
    justify-content:center;
    border:1px solid #d0d7e5;
    border-radius:6px;
    background:#ffffff;
    color:#1f3c88;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    box-shadow:0 2px 4px rgba(0,0,0,0.08);
    transition:all 0.2s ease;
}

.back-btn:hover{
    background:#1f3c88;
    border-color:#1f3c88;
    color:#ffffff;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    // Refresh interval (in milliseconds)
    var refreshTime = 20000; // 20 seconds

    setInterval(function(){

        $("#getdata").load(
            window.location.href + " #getdata > *"
        );

    }, refreshTime);

});
</script>

</head>

<body>


<div id="body" class="clear">


<!-- ================= HEADER ================= -->

<div class="header-bar">


<div class="device-name">
<?=$All_Devicename[1]?>
</div>

<div>
    <a href="dashboard.php" class="back-btn" title="Go Back">
        ← Back
    </a>
</div>
</div>


<!-- ================= DATA ================= -->

<div id="getdata">


<table class="innertab1">


<tr class="tab-head-tr-new">

<td colspan="6" align="center">Status</td>
<td colspan="11" align="center">Electrical</td>
<td colspan="9" align="center">Temperature</td>
<td colspan="4" align="center">Active Production</td>
<td></td>

</tr>


<tr class="tab-head-tr-new" align="center">

<th>Date</th>
<th>Time</th>
<th>GRPM</th>
<th>RRPM</th>
<th>Status</th>
<th>Wind</th>

<th>Power</th>
<th>R Volt</th>
<th>Y Volt</th>
<th>B Volt</th>
<th>R Cur</th>
<th>Y Cur</th>
<th>B Cur</th>

<th>Twist</th>
<th>Yaw</th>
<th>Wind Dir</th>
<th>Offset</th>

<th>G1_L1</th>
<th>G1_L2</th>
<th>G1_L3</th>
<th>G2</th>

<th>Gear</th>
<th>Nacelle</th>
<th>Outdoor</th>
<th>Thyristor</th>

<th>HSS</th>
<th>Prod</th>
<th>Cons</th>
<th>Prod</th>
<th>Cons</th>

<th>Event</th>

</tr>


<?php

$rowColors = ['#ffffff','#f9fbff'];
$i=0;


/* ================= DATA QUERY (UNCHANGED) ================= */

$Mysql_Query = "
select * from $Database_Name.device_data_f7
where IMEI='".$IMEI_Decode."'
and Status!=''
order by Record_Index desc
limit 10
";

if(!$Mysql_Query_Result = $db->query($Mysql_Query)){
    die($db->error);
}


if($Mysql_Query_Result->num_rows >= 1){

while($r = $Mysql_Query_Result->fetch_array()){


$Status = str_replace('#','',$r['Status']);

$cls='status-red';

if ($Status == 'CUT-IN G1-G2' || $Status == 'CUT-IN G2-G1' || $Status == 'RUNNING G1' || $Status == 'RUNNING G2' || $Status == 'CUT-IN G2' || $Status == 'CUT-IN G1') {
    $cls='status-green';
}
elseif ($Status == 'FREE-WHEELING G1' || $Status == 'FREE-WHEELING G2' || $Status == 'FREE-WHEELING G2-G' || $Status == 'FREE-WHEELING G1-G') {
	$cls='status-orange';
}
elseif($Status=='Grid Drop' || $Status=='GridDrop'){
    $cls='status-blue';
}else{
	$cls='status-red';
}


 

echo '<tr class="tab-head-td-new" style="background:'.$rowColors[$i++%2].'">';

echo "<td>{$r['Date_S']}</td>";
echo "<td>{$r['Time_S']}</td>";
echo "<td>{$r['GRPM']}</td>";
echo "<td>{$r['RRPM']}</td>";
echo "<td class='$cls'>$Status</td>";
echo "<td>{$r['Windspeed']}</td>";
echo "<td>{$r['Power']}</td>";
echo "<td>{$r['L_N_Voltage_Y']}</td>";
echo "<td>{$r['L_N_Voltage_B']}</td>";
echo "<td>{$r['L_L_Voltage_RY']}</td>";
echo "<td>{$r['L_L_Voltage_YB']}</td>";
echo "<td>{$r['L_L_Voltage_BR']}</td>";
echo "<td>{$r['RPhase_Current']}</td>";

echo "<td>{$r['Active_Total_Gen_Import']}</td>";
echo "<td>{$r['Active_Total_Gen_Export']}</td>";
echo "<td>{$r['Reactive_Total_Gen_Import']}</td>";
echo "<td>{$r['Reactive_Total_Gen_Export']}</td>";
echo "<td>{$r['Active_Gen1_Import']}</td>";
echo "<td>{$r['Active_Gen1_Export']}</td>";
echo "<td>{$r['Reactive_Gen1_Import']}</td>";
echo "<td>{$r['Reactive_Gen1_Export']}</td>";

echo "<td>{$r['Active_Gen2_Import']}</td>";
echo "<td>{$r['Active_Gen2_Export']}</td>";
echo "<td>{$r['Reactive_Gen2_Import']}</td>";
echo "<td>{$r['Reactive_Gen2_Export']}</td>";
echo "<td>{$r['G1_Connected_Counts']}</td>";
echo "<td>{$r['Kwh_Positive']}</td>";
echo "<td>{$r['Kwh_Negative']}</td>";
echo "<td>{$r['KVar_Positive']}</td>";
echo "<td>{$r['KVar_Negative']}</td>";
echo "<td>{$r['Min3_Wind_Dir']}</td>";

echo "</tr>";

}

}else{

echo "<tr><td colspan='31' align='center'>No Records Found</td></tr>";

}

?>

</table>

</div>


<!-- ================= GAD ================= -->

<br>

<table width="50%" align="center" class="innertab1">

<tr class="tab-head-tr-new">
<td colspan="2">Generation Summary</td>
</tr>

<tr>
<td>Today</td>
<td><?=$GAD_Today?> kWh</td>
</tr>


</table>


<br>


<!-- ================= CHART ================= -->

<iframe
src="channel8_ajax.php?c1=<?=$IMEI?>&l=<?=$Pocket_Length?>&FType=<?=$FType?>"
height="320">
</iframe>


</div>


<?php ?>

</body>
</html>