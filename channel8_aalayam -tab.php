<?php
error_reporting(0);
include("header_inner.php");

if(empty($_COOKIE[$Cook_Name])){
    header("Location:index.php");
    exit;
}

/* REQUEST */

$IMEI  = isset($_GET['c1']) ? $_GET['c1'] : '';
$l     = isset($_GET['l']) ? $_GET['l'] : '';
$FType = isset($_GET['FType']) ? $_GET['FType'] : '';

$IMEI_Decode = $IMEI ? base64_decode($IMEI) : '';

if(!$IMEI_Decode){
    die("IMEI Missing");
}

/* DEVICE */

$DeviceName="Unknown";

$dq = $db->query("SELECT Device_Name FROM device_register WHERE IMEI='$IMEI_Decode'");
if($dq && $dq->num_rows){
    $DeviceName = $dq->fetch_assoc()['Device_Name'];
}


/* GET DATABASE NAME */

$dbRow = $db->query("SELECT Db_Name FROM device_register WHERE IMEI='$IMEI_Decode'")->fetch_assoc();
$Database_Name = $dbRow['Db_Name'];


/* LAST 10 RECORDS */

$rows = [];

$sql = "SELECT *
        FROM $Database_Name.device_data_f7
        WHERE IMEI='$IMEI_Decode'
        AND Status!=''
        ORDER BY Record_Index DESC
        LIMIT 10";

$res = $db->query($sql);

if($res){
    while($r = $res->fetch_assoc()){
        $rows[] = $r;
    }
}


/* GAD */

$GAD_Today=0;
$GAD_Yesterday=0;

$gad = $db->query("

SELECT
(SELECT (Gen1_Max-Gen1_Min)
 FROM device_register
 WHERE IMEI='$IMEI_Decode'
 AND Date_S=CURDATE()) AS Today,

(SELECT (Gen1_Max-Gen1_Min)
 FROM daily_data
 WHERE IMEI='$IMEI_Decode'
 AND Date_S=CURDATE()-INTERVAL 1 DAY
 LIMIT 1) AS Yesterday

");

if($gad && $gad->num_rows){
    $g = $gad->fetch_assoc();
    $GAD_Today = $g['Today'];
    $GAD_Yesterday = $g['Yesterday'];
}

?>

<!DOCTYPE html>
<html>
<head>

<title>SCADA Dashboard</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#f2f4f8;
}

.container{
    width:100%;
}

/* HEADER */

.header{
    background:#0b7285;
    color:#fff;
    padding:15px 25px;

    display:flex;
    justify-content:space-between;
}

/* MAIN */

.main{
    display:flex;
    min-height:100vh;
}

/* LEFT */

.left{
    width:220px;
    background:#fff;
    border-right:1px solid #ddd;
}

/* TABS */

.tab-btn{
    width:100%;
    padding:14px;

    border:none;
    background:#fff;

    text-align:left;
    font-weight:600;

    border-bottom:1px solid #eee;
    cursor:pointer;
}

.tab-btn.active{
    background:#0b7285;
    color:#fff;
}

/* RIGHT */

.right{
    flex:1;
    padding:20px;
}

/* CARD */

.card{
    background:#fff;
    padding:15px;
    border-radius:6px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

th,td{
    border:1px solid #ddd;
    padding:7px;
    text-align:center;
}

th{
    background:#0b7285;
    color:#fff;
}

.tab-content{
    display:none;
}

.tab-content.active{
    display:block;
}

</style>

</head>

<body>


<div class="container">

<!-- HEADER -->

<div class="header">

    <b><?=$DeviceName?></b>

    <div>
        Today: <?=$GAD_Today?> |
        Yesterday: <?=$GAD_Yesterday?>
    </div>

</div>


<!-- MAIN -->

<div class="main">


<!-- LEFT -->

<div class="left">

<button class="tab-btn active" onclick="openTab('overview')">Overview</button>
<button class="tab-btn" onclick="openTab('electrical')">Electrical</button>
<button class="tab-btn" onclick="openTab('temperature')">Temperature</button>
<button class="tab-btn" onclick="openTab('production')">Production</button>
<button class="tab-btn" onclick="openTab('counters')">Counters</button>

</div>


<!-- RIGHT -->

<div class="right">


<!-- ================= OVERVIEW ================= -->

<div id="overview" class="tab-content active">

<div class="card">

<h3>Overview (Last 10)</h3>

<table>
<tr>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Wind</th>
<th>Power</th>
</tr>

<?php foreach($rows as $r){ ?>

<tr>
<td><?=$r['Date_S']?></td>
<td><?=$r['Time_S']?></td>
<td><?=$r['Status']?></td>
<td><?=$r['Windspeed']?></td>
<td><?=$r['Power']?></td>
</tr>

<?php } ?>

</table>

</div>

</div>


<!-- ================= ELECTRICAL ================= -->

<div id="electrical" class="tab-content">

<div class="card">

<h3>Electrical (Last 10)</h3>

<table>

<tr>
<th>R Volt</th>
<th>Y Volt</th>
<th>B Volt</th>
<th>R Cur</th>
<th>Y Cur</th>
<th>B Cur</th>
</tr>

<?php foreach($rows as $r){ ?>

<tr>
<td><?=$r['L_N_Voltage_R']?></td>
<td><?=$r['L_N_Voltage_Y']?></td>
<td><?=$r['L_N_Voltage_B']?></td>
<td><?=$r['RPhase_Current']?></td>
<td><?=$r['YPhase_Current']?></td>
<td><?=$r['BPhase_Current']?></td>
</tr>

<?php } ?>

</table>

</div>

</div>


<!-- ================= TEMPERATURE ================= -->

<div id="temperature" class="tab-content">

<div class="card">

<h3>Temperature (Last 10)</h3>

<table>

<tr>
<th>Panel</th>
<th>Gear1</th>
<th>Gear2</th>
<th>Oil</th>
<th>Nacelle</th>
</tr>

<?php foreach($rows as $r){ ?>

<tr>
<td><?=$r['Control_Panel_Temp']?></td>
<td><?=$r['Gear_Bearing1_Temp']?></td>
<td><?=$r['Gear_Bearing2_Temp']?></td>
<td><?=$r['Gear_Box_Oil_Temp']?></td>
<td><?=$r['Nacelle_Temp']?></td>
</tr>

<?php } ?>

</table>

</div>

</div>


<!-- ================= PRODUCTION ================= -->

<div id="production" class="tab-content">

<div class="card">

<h3>Production (Last 10)</h3>

<table>

<tr>
<th>Gen1</th>
<th>Gen2</th>
<th>Total</th>
</tr>

<?php foreach($rows as $r){ ?>

<tr>
<td><?=$r['Active_Gen1_Import']?></td>
<td><?=$r['Active_Gen2_Import']?></td>
<td><?=$r['Active_Total_Gen_Import']?></td>
</tr>

<?php } ?>

</table>

</div>

</div>


<!-- ================= COUNTERS ================= -->

<div id="counters" class="tab-content">

<div class="card">

<h3>Counters (Last 10)</h3>

<table>

<tr>
<th>G1 Count</th>
<th>G2 Count</th>
<th>Total Hrs</th>
<th>Operate</th>
</tr>

<?php foreach($rows as $r){ ?>

<tr>
<td><?=$r['G1_Connected_Counts']?></td>
<td><?=$r['G2_Connected_Counts']?></td>
<td><?=$r['Total_Hours']?></td>
<td><?=$r['Operate_Hours']?></td>
</tr>

<?php } ?>

</table>

</div>

</div>


</div><!-- RIGHT -->


</div><!-- MAIN -->

</div><!-- CONTAINER -->


<script>

/* TAB CONTROL */

function openTab(id){

    let tabs = document.querySelectorAll('.tab-content');
    let btns = document.querySelectorAll('.tab-btn');

    tabs.forEach(t=>t.classList.remove('active'));
    btns.forEach(b=>b.classList.remove('active'));

    document.getElementById(id).classList.add('active');
    event.target.classList.add('active');
}

</script>

</body>
</html>
