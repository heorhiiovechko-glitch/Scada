
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

// GAD query
/*$Mysql_Query_GAD = "select 
(select (Gen1_Max) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,
(select (Gen1_Max) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day)) as GAD_Yesterday,
(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and month(Date_s)=month(now())) as GAD_Thismonth";

if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)) {
    die($db->error);
}*/

if ($Mysql_Query_Result_GAD->num_rows >= 1) {
    while ($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
        $GAD_Today        = $Fetch_Result_GAD['GAD_Today'];
        $GAD_Yesterday    = $Fetch_Result_GAD['GAD_Yesterday'];
        $GAD_Thismonth    = $Fetch_Result_GAD['GAD_Thismonth'];
    }
}

$No_Records = '<tr><td colspan="2" align="center">Records Not Found</td></tr>';
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>VersatileSCADA - Detailed Information</title>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ================= PROFESSIONAL SCADA STYLE ================= -->

<style>

body{
    background:#f3f6fb;
    font-family:"Segoe UI",Arial,sans-serif;
    margin:0;
    color:#222;
}

.container-fluid{
    width:100%;
    max-width:1600px;
    margin:20px auto;
    background:#ffffff;
    padding:15px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.12);
}

.device-name{
    font-size:20px;
    font-weight:600;
    color:#1f3c88;
}

.innertab1{
    width:100%;
    border-collapse:collapse;
}

.innertab1 th{
    background:#1f3c88;
    color:#ffffff;
    font-size:13px;
    padding:8px;
    border:1px solid #2f55b0;
}

.innertab1 td{
    padding:6px;
    font-size:12px;
    border-bottom:1px solid #e0e0e0;
}

.innertab1 tbody tr:nth-child(even){
    background:#f8faff;
}

.innertab1 tbody tr:hover{
    background:#eaf0ff;
}

.gad-table{
    width:100%;
    border-collapse:collapse;
}

.gad-table th{
    background:#1f3c88;
    color:#fff;
    padding:8px;
}

.gad-table td{
    padding:8px;
    border-bottom:1px solid #ddd;
}

iframe{
    width:100%;
    border:1px solid #ddd;
    border-radius:8px;
}

</style>

</head>

<body>

<div class="container-fluid">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">

<div class="device-name">
<?= isset($All_Devicename[1]) ? $All_Devicename[1] : '' ?>
</div>

<div>
<a href="channel1.php" style="text-decoration:none;font-weight:600;">← Back</a>
</div>

</div>

<div id="getdata">

<table class="innertab1">

<thead>

<tr>
<th colspan="6">Status</th>
<th colspan="8">Electrical</th>
<th colspan="9">Temperature</th>
<th colspan="3">Active Production</th>
<th></th>
</tr>

<tr>
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
<th>G1_L1</th>
<th>G1_L2</th>
<th>G1_L3</th>
<th>G2_L1</th>
<th>Gear Oil</th>

<th>Outdoor</th>
<th>G2_L2</th>
<th>Gearbox HSS</th>
<th>Day</th>
<th>Month</th>
<th>Year</th>
<th>Event</th>
</tr>

</thead>

<tbody>

<?php

$rowColors = array('#ffffff','#f8faff');
$i = 0;

$Mysql_Query = "select * from $Database_Name.device_data_f9 where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";

if (!$Mysql_Query_Result = $db->query($Mysql_Query)) {
    die($db->error);
}

if ($Mysql_Query_Result->num_rows >= 1) {

while ($Fetch_Result = $Mysql_Query_Result->fetch_array()) {

$Status = str_replace('#', '', $Fetch_Result['Status']);
$Tot_Prod_KW = $Fetch_Result['GBearing_NDE'];
$Tot_Consumd_KW = $Fetch_Result['Nacel'];
$Tot_Prod_KVAR = $Fetch_Result['HSS_Outer'];
$Tot_Consumd_KVAR = $Fetch_Result['C_Kvarh'];

echo '<tr>';

echo '<td>'.$Fetch_Result['Date'].'</td>';
echo '<td>'.$Fetch_Result['Time'].'</td>';
echo '<td>'.$Fetch_Result['GRPM'].'</td>';
echo '<td>'.$Fetch_Result['RRPM'].'</td>';
echo '<td>'.$Status.'</td>';
echo '<td>'.$Fetch_Result['Windspeed'].'</td>';


echo '<td>'.$Fetch_Result['Power'].'</td>';
echo '<td>'.$Fetch_Result['RPhase_Volt'].'</td>';
echo '<td>'.$Fetch_Result['YPhase_Volt'].'</td>';
echo '<td>'.$Fetch_Result['BPhase_Volt'].'</td>';
echo '<td>'.$Fetch_Result['RPhase_Current'].'</td>';
echo '<td>'.$Fetch_Result['YPhase_Current'].'</td>';
echo '<td>'.$Fetch_Result['BPhase_Current'].'</td>';

echo '<td>'.$Fetch_Result['Twist'].'</td>';
echo '<td>'.$Fetch_Result['Nacelle'].'</td>';
echo '<td>'.$Fetch_Result['Wind_Direction'].'</td>';
echo '<td>'.$Fetch_Result['Wind_Vane'].'</td>';
echo '<td>'.$Fetch_Result['G1_L1Temp'].'</td>';

echo '<td>'.$Fetch_Result['G2L3'].'</td>';
echo '<td>'.$Fetch_Result['Outdoor'].'</td>';
echo '<td>'.$Fetch_Result['G1_L3Temp'].'</td>';

echo '<td>'.$Fetch_Result['P_Kwh'].'</td>';
echo '<td>'.$Fetch_Result['C_Kwh'].'</td>';
echo '<td>'.$Fetch_Result['P_Kvarh'].'</td>';

echo '<td>'.$Fetch_Result['Eventlog'].'</td>';

echo '</tr>';

}

}

?>

</tbody>

</table>

</div>

<br>

<div style="
margin-top:10px;
padding:10px;
background:#edf2ff;
border-radius:6px;
font-size:13px;
display:flex;
justify-content:center;
gap:40px;
font-weight:600;
">

<span>Today: <b><?=$GAD_Today?> kWh</b></span>

<span>Total Produced KW: <b><?=$Tot_Prod_KW?></b></span>

<span>Total Consumed KW: <b><?=$Tot_Consumd_KW?></b></span>

<span>Total Produced KVAR: <b><?=$Tot_Prod_KVAR?></b></span>

<span>Total Consumed KVAR: <b><?=$Tot_Consumd_KVAR?></b></span>

</div>

<br>

<iframe src="channel9new_ajax.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>" height="320"></iframe>

</div>

<script>

setInterval(function(){

$('#getdata').load('channel9_allayam.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?> #getdata');

},20000);

</script>

</body>
</html>
```
