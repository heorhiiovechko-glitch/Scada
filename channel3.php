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
//echo date('01-m-Y') . '<br/>';
//echo date('m-t-Y 12:59:59',strtotime(now())) . '<br/>';
?>
<?php
	$lastRecd = null;
	$IMEI = $_REQUEST['c1'];

	//$Db_Name = $_REQUEST['Db'];	
	if(isset($_REQUEST['l']))
		$Pocket_Length = $_REQUEST['l'];
	else
		$Pocket_Length = '';
	$IMEI_Decode = base64_decode($IMEI);
	$FType=$_REQUEST['FType'];
	if(isset($_REQUEST['Db_Name'])) {
		$Database_Name = $_REQUEST['Db_Name'];
	}
	if ($IMEI_Decode=='865263043059086') {
		$Database_Name = 'va_victus';
	}

// Getting the customer information
			$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
				//echo $Fetch_Info;
			if (!$Fetch_Info_Result = $db->query($Fetch_Info))
            {
                die($db->error);
            }
            if($Fetch_Info_Result->num_rows >= 1)
            {
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
					 $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
					  $x++;
				}				
			}



if($Closing_Time[1]=='06:00:00' || $Closing_Time[1]=='06:30:00'){
										$GAD_Time=" and Hour(Time_S)>=6 ";
										$GD_Time=time()-21660;
}
								elseif($Closing_Time[1]=='07:00:00' || $Closing_Time[1]=='07:30:00'){
										$GAD_Time=" and Hour(Time_S)>=7 ";
										$GD_Time=time()-25200;
}								elseif($Closing_Time[1]=='08:00:00' || $Closing_Time[1]=='08:30:00'){
										$GAD_Time=" and Hour(Time_S)>=8 ";
										$GD_Time=time()-28800;
}								elseif($Closing_Time[1]=='09:00:00'){
										$GAD_Time=" and Hour(Time_S)>=9 ";
										$GD_Time=time()-32400;
}								elseif($Closing_Time[1]=='01:00:00' || $Closing_Time[1]=='01:30:00'){
										$GAD_Time=" and Hour(Time_S)>=1 ";
										$GD_Time=time()-3600;
}								elseif($Closing_Time[1]=='02:00:00' || $Closing_Time[1]=='02:30:00'){
										$GAD_Time=" and Hour(Time_S)>=2 ";
										$GD_Time=time()-7200;
}								
									else {
										$GAD_Time="";
										$GD_Time=time();
$Test_Time=date('H',$GD_Time);
}																	


$Mysql_Query_GAD="select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";
$GAD_Today = 0;
$GAD_Yesterday = 0;
$GAD_Thisweek = 0;
$GAD_Thismonth = 0;
$GAD_Previousweek = 0;
try {
    if ($Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)) {
        if ($Mysql_Query_Result_GAD->num_rows >= 1) {
            while ($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
                $GAD_Today = round($Fetch_Result_GAD['GAD_Today'], 2);
                $GAD_Yesterday = round($Fetch_Result_GAD['GAD_Yesterday'], 2);
                $GAD_Thisweek = round($Fetch_Result_GAD['GAD_Thisweek'], 2);
                $GAD_Thismonth = round($Fetch_Result_GAD['GAD_Thismonth'], 2);
                $GAD_Previousweek = round($Fetch_Result_GAD['GAD_Previousweek'], 2);
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    // daily_data may not exist in this customer database
}
	//echo $GAD_Thisweek;

$ER_Mysql_Query = "select Status as Log,Date_S,Time_S from $Database_Name.device_data_f2 where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
	if (!$ER_Mysql_QUERY_Result = $db->query($ER_Mysql_Query))
            {
                die($db->error);
            }

            if($ER_Mysql_QUERY_Result->num_rows >= 1)
            {
                $ER_Fetch_Result = $ER_Mysql_QUERY_Result->fetch_array();
		$Log_Status = $ER_Fetch_Result['Log'];	
		$Date = $ER_Fetch_Result['Date_S'];
		$Time = $ER_Fetch_Result['Time_S'];		
	}


		$No_Records = '<tr>
		<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
	</tr>';	
?> 
<?php

?>

<!doctype html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SCADA - Device View</title>

<!-- Material UI Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>

body {
    background: #f3f5f9;
    margin: 0;
    font-family: 'Roboto', sans-serif;
    color: #222;
}

/* Page Container */
.material-container {
    width: 100%;
    max-width: 100%;
    margin: 0;
    padding: 18px 0; /* Optional: remove left-right padding */
}


...
/* (To keep this file readable here I'm inserting the styling from your previous code and hybrid layout CSS)
   Full CSS block follows (including table and mobile card styles) */

/* Material Card */
.material-card {
    background: #fff;
    border-radius: 14px;
    padding: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    margin-bottom: 22px;
    transition: box-shadow .2s ease;
}
.material-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.12); }

/* Header Title */
.page-title { font-size: 26px; font-weight: 700; color: #333; margin-bottom: 6px; }
.page-sub { font-size: 14px; color: #666; }

/* Table Styling */
.table-container { overflow-x: auto; border-radius: 12px; background: #fff; padding: 8px; }
table { border-collapse: collapse; width: 100%; min-width: 900px; }
table th { background: #e8eef5; padding: 10px; font-weight: 600; font-size: 13px; text-transform: uppercase; color: #444; border-bottom: 1px solid #ddd; position: sticky; top: 0; z-index: 5; }
table td { padding: 9px; font-size: 14px; border-bottom: 1px solid #eee; background: #fff; text-align: center; }

/* Two column grid */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.gad-center-box { display: flex !important; justify-content: center; }

/* Mobile card styles (hybrid layout) */
.desktop-table { display: block; }
.mobile-cards { display: none; }

@media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-cards { display: block; padding: 12px; }
    .data-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.10);
        font-family: 'Roboto', sans-serif;
    }
    .card-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 14px;
    }
    .card-key { font-weight: 600; color: #444; }
    .card-value { font-weight: 500; color: #111; }
}

/* Status chips */
.chip { padding: 5px 10px; border-radius: 18px; font-size: 12px; font-weight: 600; color: #fff; display: inline-block; }
.chip.green { background: #4caf50; } .chip.red { background: #f44336; } .chip.blue { background: #2196f3; }

.tcp-frame-box { display: flex; justify-content: center; align-items: center; width: 100%; }
.tcp-frame-box iframe { width: 350px !important; height: 60px !important; min-width: 350px !important; border: none; overflow: hidden !important; display: block; scrollbar-width: none; }
.tcp-frame-box iframe::-webkit-scrollbar { display: none; }
.gad-row {
    display: flex;
    justify-content: center !important;
    gap: 40px;
    flex-wrap: wrap;
    text-align: center;
    width: 100%;
}

.gad-row span { font-size: 18px; font-weight: 700; color: #222; }

/* FIX: Prevent charts and report iframes from shrinking */
.material-card iframe.responsive-iframe {
    width: 100% !important;
    min-width: 100% !important;
    height: 360px;
}

/* FIX: Ensure charts + reports do not inherit flex/grid squeeze */
.material-card {
    width: 100% !important;
    box-sizing: border-box;
}

/* FIX: Prevent grid-2 from collapsing if screen width > 768px */
.grid-2 {
    grid-template-columns: 1fr 1fr !important;
}

/* FIX: Force iframe container to allow natural width */
.grid-2 .material-card {
    min-width: 100% !important;
}

/* MOBILE FIX to prevent ultra-shrink */
@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr !important;
    }
    .material-card iframe.responsive-iframe {
        height: 300px !important;
    }
}

.gad-flex-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tcp-icon-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #1976d2;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    box-shadow: 0 3px 8px rgba(0,0,0,0.25);
}

.tcp-icon-btn:hover {
    background: #0d47a1;
}

.tcp-icon-btn .material-icons {
    color: white;
    font-size: 26px;
}

/* Modal Background */
.tcp-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.55);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Modal Box */
.tcp-modal-content {
    width: 420px;
    height: 120px;
    background: white;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 5px 12px rgba(0,0,0,0.25);
}

.tcp-modal-content iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.close-tcp-btn {
    position: absolute;
    top: 20px;
    right: 22px;
    font-size: 30px;
    color: white;
    cursor: pointer;
}

/* ===== FORCE LANDSCAPE ON MOBILE ===== */

./* Rotate Warning Box */
.rotate-warning{
    display:none;
    position:fixed;
    inset:0;
    background:#0f172a;
    color:#ffffff;
    z-index:99999;

    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;
}

/* Inner Box */
.rotate-box{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:25px;
    padding:30px;
}

/* Big Icon */
.rotate-icon{
    font-size:120px;   /* Icon size */
    line-height:1;
}

/* Big Text */
.rotate-text{
    font-size:26px;    /* Text size */
    font-weight:600;
    line-height:1.4;
    max-width:320px;
}


/* Show warning in portrait mode */
@media screen and (max-width:900px) and (orientation:portrait){
    .rotate-warning{
        display:flex;   /* Show in portrait */
    }

    body{
        overflow:hidden;
    }
}






</style>

<script>
// Auto refresh sections (preserve original behavior)
$(function(){
    function refresh(){
        $("#getdata").load(location.href + " #getdata > *");
        $("#status").load(location.href + " #status > *");
    }
    setInterval(refresh, 20000);
});

// Disable right click
document.addEventListener('contextmenu', event => event.preventDefault());

// Disable common inspect keys
document.onkeydown = function(e) {
    if (e.keyCode == 123) { return false; }
    if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 67 || e.keyCode == 74)) { return false; }
    if (e.ctrlKey && e.keyCode == 85) { return false; }
};
</script>

</head>

<body>

<div class="rotate-warning">
    <div class="rotate-box">

        <!-- Icon -->
        <span class="material-icons rotate-icon">screen_rotation</span>

        <!-- Text -->
        <div class="rotate-text">
            Please rotate your phone to<br>
            Landscape mode for better viewing
        </div>

    </div>
</div>

<div class="material-container">

<!-- BEGIN INJECTED channel3.php CONTENT -->

<div class="page-container">

<script>
// Collapsible & periodic AJAX refresh (keeps original intent)
document.querySelectorAll('.collapsible-header').forEach(header => {
    header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        content.style.display = (content.style.display === 'block') ? 'none' : 'block';
    });
});
(function(){
    if (window.innerWidth > 900) {
        document.querySelectorAll('.collapsible .collapsible-content').forEach(c => c.style.display = 'block');
    }
})();
(function($){
    function refreshParts(){
        $('#recent-rows').load('<?= 'channel3.php?c1=' . urlencode($_REQUEST['c1']) . '&l=' . urlencode($_REQUEST['l']) ?> #recent-rows > *', function(){
            var rows = $('#recent-rows tr').length;
            $('#live-count').text(rows + ' rows');
        });
        $('.device-header').load('<?= 'channel3.php?c1=' . urlencode($_REQUEST['c1']) . '&l=' . urlencode($_REQUEST['l']) ?> .device-header > *');
    }
    setTimeout(refreshParts, 2500);
    setInterval(refreshParts, 20000);
})(jQuery);
</script>

<script>
function updateRotateWarning(){
    const box = document.querySelector('.rotate-warning');

    if(window.innerWidth < 900 && window.innerHeight > window.innerWidth){
        // Portrait
        box.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }else{
        // Landscape / Desktop
        box.style.display = 'none';
        document.body.style.overflow = '';
    }
}

window.addEventListener('load', updateRotateWarning);
window.addEventListener('resize', updateRotateWarning);
window.addEventListener('orientationchange', updateRotateWarning);
</script>


<!-- END INJECTED channel3.php CONTENT -->

   
	<!-- TOP HEADER (RESTORED OLD STYLE) -->
<div class="material-card gad-flex-box">

    <!-- DEVICE NAME -->
    <div style="font-size:20px; font-weight:600; min-width:180px;">
        <?= htmlspecialchars($All_Devicename[1]) ?>
    </div>

    <!-- GAD VALUES (CENTER) -->
    <div class="gad-center-box">
        <div class="gad-row">
            <span>GAD Today : <?= $GAD_Today ?> Kwh</span>
            <span>GAD Yesterday : <?= $GAD_Yesterday ?> Kwh</span>
            <span>GAD Month : <?= $GAD_Thismonth ?> Kwh</span>
        </div>
    </div>

    <!-- RIGHT BUTTONS -->
    <div style="display:flex; gap:12px; align-items:center; margin-left:auto;">

        <!-- REPORTS BUTTON -->
        <a style="
            font-size:15px;
            font-weight:600;
            text-decoration:none;
            padding:7px 16px;
            border:1px solid #555;
            background:#f5f5f5;
            color:#000;
            border-radius:3px;
            display:inline-block;
        "
        href="channel3_ajax.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?>&FType=<?= $_REQUEST['FType'] ?>">
            Reports
        </a>

        <!-- REMOTE BUTTON -->
        <button
            style="
                font-size:15px;
                font-weight:600;
                padding:7px 16px;
                border:1px solid #555;
                background:#f5f5f5;
                color:#000;
                border-radius:3px;
                cursor:pointer;
            "
            onclick="openTCPModal()">
            Remote
        </button>

        <button type="button" id="btnTcpStart" class="tcp-inline-btn tcp-inline-btn--start"
                onclick="sendTCPCommand('START', this)"
                title="Post $CFG&lt;Start&gt; to TCP client">
            Start ($CFG&lt;Start&gt;)
        </button>

        <button type="button" id="btnTcpStop" class="tcp-inline-btn tcp-inline-btn--stop"
                onclick="sendTCPCommand('STOP', this)"
                title="Post $CFG&lt;Pause&gt; to TCP client">
            Stop ($CFG&lt;Pause&gt;)
        </button>

        <span id="tcpCommandFeedback" class="tcp-inline-feedback" aria-live="polite"></span>

    </div>

</div>

<style>
.tcp-inline-btn{
    font-size:15px;
    font-weight:600;
    padding:7px 16px;
    border:none;
    border-radius:3px;
    color:#fff;
    cursor:pointer;
}
.tcp-inline-btn:disabled{ opacity:0.55; cursor:not-allowed; }
.tcp-inline-btn--start{ background:#16a34a; }
.tcp-inline-btn--stop{ background:#dc2626; }
.tcp-inline-feedback{
    display:inline-block;
    font-size:13px;
    font-weight:600;
    margin-left:6px;
}
.tcp-inline-feedback.ok{ color:#166534; }
.tcp-inline-feedback.error{ color:#991b1b; }
</style>

<script>
function setTCPCommandFeedback(message, status) {
    var feedback = document.getElementById('tcpCommandFeedback');
    if (!feedback) {
        return;
    }
    feedback.className = 'tcp-inline-feedback' + (status ? (' ' + status) : '');
    feedback.textContent = message || '';
}

function sendTCPCommand(command, button) {
    if (!command) {
        return false;
    }

    setTCPCommandFeedback('Posting TCP command...', '');

    if (button) {
        button.disabled = true;
    }

    var request = new XMLHttpRequest();
    var body =
        'c1=<?= urlencode($_REQUEST['c1']) ?>' +
        '&db=<?= urlencode($Database_Name) ?>' +
        '&ajax=1&cmd=' + encodeURIComponent(command);

    request.open('POST', 'TcpRequest.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onreadystatechange = function() {
        if (request.readyState !== 4) {
            return;
        }
        if (button) {
            button.disabled = false;
        }
        try {
            var response = JSON.parse(request.responseText);
            if (request.status >= 200 && request.status < 300 && response.ok) {
                var msg = response.message;
                if (response.cfg_payload) {
                    msg = response.cfg_payload + ' queued for TCP client.';
                }
                setTCPCommandFeedback(msg || (command + ' command submitted.'), 'ok');
            } else {
                setTCPCommandFeedback(response.message || (command + ' command failed.'), 'error');
            }
        } catch (error) {
            setTCPCommandFeedback(command + ' command failed.', 'error');
        }
    };
    request.send(body);
    return false;
}
</script>

		
		<!-- TOP TCP REQUEST FRAME -->


    <!-- LIVE DATA TABLE -->

<?php
// Fetch latest 10 rows again for the unified view (used by both desktop and mobile)
$Mysql_Query = "select * from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
if (!$Mysql_Query_Result = $db->query($Mysql_Query)) { die($db->error); }
?>

<div id="getdata">

<!-- DESKTOP TABLE VIEW -->
<div class="desktop-table table-container">
<table>
    <thead>
        <tr>
            <th>Date</th><th>Time</th><th>GRPM</th><th>RRPM</th><th>Status</th>
            <th>Wind</th><th>Power</th><th>R V</th><th>Y V</th><th>B V</th>
            <th>R A</th><th>Y A</th><th>B A</th><th>PF</th>
            <th>G1 Kwh</th><th>G2 Kwh</th><th>Imp</th>
            <th>G1 Hrs</th><th>G2 Hrs</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $rowColors = ['#ffffff','#f5f7fb'];
        $i = 0;

        if($Mysql_Query_Result->num_rows >= 1) {
            // reset pointer to start just in case
            mysqli_data_seek($Mysql_Query_Result, 0);
            while($row = $Mysql_Query_Result->fetch_array()) {
                $bg = $rowColors[$i++ % 2];
                $Status = str_replace('#','',$row['Status']);

                $statusColor = "red";
                if(in_array($Status, ['Run','M/C Running','RUN','OperateG1','OperateG2','OPERATING   NORMAL OPERATION']))
                    $statusColor = "green";
                else if(in_array($Status, ['Grid Drop','GridDrop']))
                    $statusColor = "blue";

                echo "<tr style='background:$bg'>";

                echo "<td align='center'>{$row['Date_S']}</td>";
                echo "<td align='center'>{$row['Time_S']}</td>";
                echo "<td align='center'>{$row['GRPM']}</td>";
                echo "<td align='center'>{$row['RRPM']}</td>";
                echo "<td align='center' style='color:$statusColor;font-weight:600;'>$Status</td>";

                echo "<td align='center'>".str_replace('m/s','',$row['Windspeed'])."</td>";
                echo "<td align='center'>{$row['Power']}</td>";

                echo "<td align='center'>{$row['RPhase_Volt']}</td>";
                echo "<td align='center'>{$row['YPhase_Volt']}</td>";
                echo "<td align='center'>{$row['BPhase_Volt']}</td>";

                echo "<td align='center'>{$row['RPhase_Current']}</td>";
                echo "<td align='center'>{$row['YPhase_Current']}</td>";
                echo "<td align='center'>{$row['BPhase_Current']}</td>";

                echo "<td align='center'>{$row['Power_Factor']}</td>";
                echo "<td align='center'>{$row['PAT_Gen1']}</td>";
                echo "<td align='center'>{$row['PAT_Gen2']}</td>";
                echo "<td align='center'>{$row['Import_Kwh']}</td>";
                echo "<td align='center'>{$row['Gen1_Hours']}</td>";
                echo "<td align='center'>{$row['Gen2_Hours']}</td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='19' style='padding:15px; text-align:center;'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>

<!-- MOBILE CARD VIEW -->
<div class="mobile-cards">
    <?php
    if($Mysql_Query_Result->num_rows >= 1) {
        mysqli_data_seek($Mysql_Query_Result, 0); // reset pointer
        while($row = $Mysql_Query_Result->fetch_array()) {
            $Status = str_replace('#','',$row['Status']);

            echo '<div class="data-card">';

            $fields = [
                "Date" => $row['Date_S'],
                "Time" => $row['Time_S'],
                "GRPM" => $row['GRPM'],
                "RRPM" => $row['RRPM'],
                "Status" => $Status,
                "Wind (m/s)" => str_replace('m/s','',$row['Windspeed']),
                "Power" => $row['Power'],
                "R Volt" => $row['RPhase_Volt'],
                "Y Volt" => $row['YPhase_Volt'],
                "B Volt" => $row['BPhase_Volt'],
                "R Amp" => $row['RPhase_Current'],
                "Y Amp" => $row['YPhase_Current'],
                "B Amp" => $row['BPhase_Current'],
                "PF" => $row['Power_Factor'],
                "G1 Kwh" => $row['PAT_Gen1'],
                "G2 Kwh" => $row['PAT_Gen2'],
                "Import" => $row['Import_Kwh'],
                "G1 Hours" => $row['Gen1_Hours'],
                "G2 Hours" => $row['Gen2_Hours']
            ];

            foreach($fields as $key => $val) {
                echo "
                <div class='card-row'>
                    <div class='card-key'>$key</div>
                    <div class='card-value'>$val</div>
                </div>";
            }

            echo "</div>";
        }
    } else {
        echo '<div class="data-card">No records found</div>';
    }
    ?>
</div>

</div> <!-- end getdata -->

    <!-- CHARTS -->
    <div class="material-card">
        <div class="section-header">Power vs WindSpeed Chart
        <iframe class="responsive-iframe"
                src="Power_Windspeed_chart_Monthly_iframe.php?c1=<?=$_REQUEST['c1']?>&Year=<?=date('m-Y')?>&l=<?=$_REQUEST['l']?>"></iframe>
		</div>
		<div class="section-header">Daily Generation Report
            <iframe class="responsive-iframe"
                    src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>"></iframe>
        </div>
	</div>

</div>

<?php
    
?>

<!-- TCP MODAL POPUP -->
<div id="tcpModal" class="tcp-modal">
    <div class="close-tcp-btn" onclick="closeTCPModal()">×</div>

    <div class="tcp-modal-content">
        <iframe 
            src="TcpRequest.php?c1=<?= urlencode($_REQUEST['c1']) ?>&db=<?= urlencode($Database_Name) ?>" 
            title="TCP Request">
        </iframe>
    </div>
</div>

<script>
function openTCPModal() {
    document.getElementById("tcpModal").style.display = "flex";
}
function closeTCPModal() {
    document.getElementById("tcpModal").style.display = "none";
}
</script>

</body>
</html>
