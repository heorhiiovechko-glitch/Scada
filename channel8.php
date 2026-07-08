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
?>
<?php
	$lastRecd = null;
	$IMEI = $_REQUEST['c1'];	
	if(isset($_REQUEST['l']))
		$Pocket_Length = $_REQUEST['l'];
	else
		$Pocket_Length = '';
	$IMEI_Decode = base64_decode($IMEI);
	$FType=$_REQUEST['FType'];
	if(isset($_REQUEST['Db_Name'])) {
		$Database_Name = $_REQUEST['Db_Name'];
	}
	
	//include("Gen_Export_Month.php");
	//include("Gen_Export_Year.php");
	if($FType==7){
	$Table_Name="device_data_f7";
	$Error_Table_Name="error_data_f7";
	}
	elseif($FType==8){
	$Table_Name="device_data_f8";
	$Error_Table_Name="error_data_f8";
	}
	elseif($FType==11){
	$Table_Name="device_data_f11";
	$Error_Table_Name="error_data_f11";
	}

	
	
	
	# Getting the data from DEVICE_DATA_F2 based on IMEI
	$Mysql_Query = "select * from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 10";//echo $Mysql_Query;

	if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }
			$Mysql_Record_Count=$Mysql_Query_Result->num_rows;
			
			
            if($Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	 
			$Project_Version = $Fetch_Result['Project_Version'];
			$ID_Number = $Fetch_Result['ID_Number'];
			$GRPM = $Fetch_Result['GRPM'];
			$RRPM = $Fetch_Result['RRPM'];
			$WindSpeed = $Fetch_Result['Windspeed'];				
			$Active_Power = $Fetch_Result['Power'];				
			$Reactive_Power = $Fetch_Result['Reactive_Power'];
			$L_N_Voltage_R = $Fetch_Result['L_N_Voltage_R'];
			$L_N_Voltage_Y = $Fetch_Result['L_N_Voltage_Y'];
			$L_N_Voltage_B = $Fetch_Result['L_N_Voltage_B'];
			$L_L_Voltage_RY = $Fetch_Result['L_L_Voltage_RY'];				
			$L_L_Voltage_YB = $Fetch_Result['L_L_Voltage_YB'];
			$L_L_Voltage_BR = $Fetch_Result['L_L_Voltage_BR'];
			$Frequency = $Fetch_Result['Frequency'];
			$Active_Total_Gen_Import = $Fetch_Result['Active_Total_Gen_Import'];
			$Active_Total_Gen_Export = $Fetch_Result['Active_Total_Gen_Export'];
			$Reactive_Total_Gen_Import = $Fetch_Result['Reactive_Total_Gen_Import'];
			$Reactive_Total_Gen_Export = $Fetch_Result['Reactive_Total_Gen_Export'];
			$Active_Gen1_Import = $Fetch_Result['Active_Gen1_Import'];
			$Active_Gen1_Export = $Fetch_Result['Active_Gen1_Export'];
			$Reactive_Gen1_Import = $Fetch_Result['Reactive_Gen1_Import'];
			$Reactive_Gen1_Export = $Fetch_Result['Reactive_Gen1_Export'];
			$Active_Gen2_Import = $Fetch_Result['Active_Gen2_Import'];
			$Active_Gen2_Export = $Fetch_Result['Active_Gen2_Export'];
			$Reactive_Gen2_Import = $Fetch_Result['Reactive_Gen2_Import'];
			$Reactive_Gen2_Export = $Fetch_Result['Reactive_Gen2_Export'];	
			$Temp_1 = $Fetch_Result['Control_Panel_Temp'];	
			$Temp_2 = $Fetch_Result['Gear_Bearing1_Temp'];				
			$Temp_3 = $Fetch_Result['Gear_Bearing2_Temp'];
			$Temp_4 = $Fetch_Result['Gear_Box_Oil_Temp'];
			$Temp_5 = $Fetch_Result['Gen_Winding1_Temp'];
			$Temp_6 = $Fetch_Result['Gen_Winding2_Temp'];
			$Temp_7 = $Fetch_Result['Gen_DE_Bearing_Temp'];				
			$Temp_8 = $Fetch_Result['Gen_DE_NDE_Bearing_Temp'];
			$Temp_9 = $Fetch_Result['Nacelle_Temp'];
			if($FType==7){
			$Temp_10 = $Fetch_Result['Main_Bearing_Temp'];
			$Temp_11 = $Fetch_Result['Transformer_Oil_Temp'];
			$Tip_Pressure = $Fetch_Result['Tip_Pressure'];
			}
			$G1_Connected_Counts = $Fetch_Result['G1_Connected_Counts'];
			$G2_Connected_Counts = $Fetch_Result['G2_Connected_Counts'];
			$Total_Hours = $Fetch_Result['Total_Hours'];
			$Operate_Hours = $Fetch_Result['Operate_Hours'];
			$Grid_Failure_Hours = $Fetch_Result['Grid_failure_Hours'];
			$Stopped_Hours = $Fetch_Result['Stopped_Hours'];
			$Gen_Init_Date = $Fetch_Result['Gen_Init_Date'];
			$Gen_Init_Time = $Fetch_Result['Gen_Init_Time'];
			$Kwh_Positive = $Fetch_Result['Kwh_Positive'];
			$Kwh_Negative = $Fetch_Result['Kwh_Negative'];
			$Kvar_Positive = $Fetch_Result['KVar_Positive'];
			$Kvar_Negative = $Fetch_Result['KVar_Negative'];
			$Min3_Wind_Speed = $Fetch_Result['Min3_Wind_Speed'];
			$Min3_Wind_Dir = $Fetch_Result['Min3_Wind_Dir'];
			$Min3_Active_Power = $Fetch_Result['Min3_Active_Power'];
			
			$Cable_Twist = $Fetch_Result['Cable_Twist'];
			$Nacelle_Position = $Fetch_Result['Nacelle_Position'];
			$Rphase_Current = $Fetch_Result['RPhase_Current'];
			$Yphase_Current = $Fetch_Result['YPhase_Current'];
			$Bphase_Current = $Fetch_Result['BPhase_Current'];
			$Power_factor = $Fetch_Result['Power_Factor'];				
			$Status = $Fetch_Result['Status'];
			
			$Date_F = $Fetch_Result['Date_S'];
			$Time_F = $Fetch_Result['Time_S'];
			//$Device_Epoch_Time = GetTimestamp($Date_F,$Time_F);
			$Date_UK = $Fetch_Result['Date'];
			$Time_UK = $Fetch_Result['time'];
		}

		# Removing # symbal
		$Status = str_replace('#','',$Status);		
		$lastRecd = str_replace('.','-',$Date_F);	
		$lastRecd_UK = str_replace('.','-',$Date_UK);	
		$WindSpeed = str_replace('m/s','',$WindSpeed);	
	}
	$No_Records = '<tr>
		<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
	</tr>';	
?> 
			<?php
			// Getting the customer information
			$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
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
					  $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
					  $x++;
				}				
			}

$Mysql_Query_GAD = "select (select Gen1_Max from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate() limit 1) as GAD_Today,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) order by Record_Index desc limit 1) as GAD_Yesterday,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) order by Record_Index desc limit 1) as GAD_Thisweek,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) order by Record_Index desc limit 1) as GAD_Thismonth,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 AND YEAR( Date_S) = YEAR( curdate() ) order by Record_Index desc limit 1) as GAD_Previousweek";
	if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD))
            {
                die($db->error);
            }

            if($Mysql_Query_Result_GAD->num_rows >= 1)
            {
                while($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {  
			
			$GAD_Today = $Fetch_Result_GAD['GAD_Today'];
			$GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
			$GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
			$GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
			$GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];

			}
}
	//echo $GAD_Thisweek;

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
    .desktop-table { 
        display: block;   /* ✅ Show table */
        overflow-x: auto; /* ✅ Allow horizontal scroll */
    }
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
        $('#recent-rows').load('<?= 'channel8.php?c1=' . urlencode($_REQUEST['c1']) . '&l=' . urlencode($_REQUEST['l']) ?> #recent-rows > *', function(){
            var rows = $('#recent-rows tr').length;
            $('#live-count').text(rows + ' rows');
        });
        $('.device-header').load('<?= 'channel8.php?c1=' . urlencode($_REQUEST['c1']) . '&l=' . urlencode($_REQUEST['l']) ?> .device-header > *');
    }
    setTimeout(refreshParts, 2500);
    setInterval(refreshParts, 20000);
})(jQuery);
</script>

<?php  ?>

<!-- END INJECTED channel3.php CONTENT -->

    <!-- TOP CARD -->
	<div class="material-card">
        <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div style="font-size:20px; font-weight:600;">
                <?= htmlspecialchars($All_Devicename[1]) ?>               
            </div>
            
        </div>
    </div>
    <div class="material-card gad-flex-box">

    <!-- GAD VALUES -->
    <div class="gad-row">
        <span>GAD Today : <?= $GAD_Today ?> Kwh</span>
        <span>GAD Yesterday : <?= $GAD_Yesterday ?> Kwh</span>
        <span>GAD Month : <?= $GAD_Thismonth ?> Kwh</span>
    </div>

    <!-- TCP ICON BUTTON -->
    <div class="tcp-icon-btn" onclick="openTCPModal()">
        <span class="material-icons">bolt</span>
    </div>

</div>

		
		<!-- TOP TCP REQUEST FRAME -->


    <!-- LIVE DATA TABLE -->

<?php
// Fetch latest 10 rows again for the unified view (used by both desktop and mobile)
//$Mysql_Query = "select * from $Database_Name.device_data_f8 where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
//if (!$Mysql_Query_Result = $db->query($Mysql_Query)) { die($db->error); }
?>

<div id="getdata">

<!-- DESKTOP TABLE VIEW -->
<div class="desktop-table table-container">
<table>
    <thead>
      <tr>
    <th rowspan="2">Date</th>
    <th rowspan="2">Time</th>
    <th rowspan="2">GRPM</th>
    <th rowspan="2">RRPM</th>
    <th rowspan="2">Status</th>
    <th rowspan="2">Wind</th>
    <th rowspan="2">Power</th>

    <!-- Voltage Group -->
    <th colspan="3" style="text-align:center;">Voltage</th>

    <!-- Current Group -->
    <th colspan="3" style="text-align:center;">Current</th>

    <th rowspan="2">Freq</th>
	<th colspan="2" style="text-align:center;">Total</th>
    

    <!-- Produced Units Group -->
    <th colspan="2" style="text-align:center;">Today</th>

    
	
	<th colspan="2" style="text-align:center;">3 Min Avg</th>
	
	
	
	<th colspan="4" style="text-align:center;">Hours</th>
	 
    
    
</tr>

<tr>
    <!-- Voltage Subheaders -->
    <th>R</th>
    <th>Y</th>
    <th>B</th>

    <!-- Current Subheaders -->
    <th>R</th>
    <th>Y</th>
    <th>B</th>
	
	<th>Imp Kwh</th>
    <th>Exp Kwh</th>

    <!-- Produced Units Subheader -->
    <th>Imp Kwh</th>
	<!-- Consumed Units Subheader -->
    <th>Exp Kwh</th>
	
	<th>wind</th>
    <th>Power</th>
	
	<th rowspan="2">Total</th>
	<th rowspan="2">Operate</th>
	<th rowspan="2">B D </th>
	<th rowspan="2">G D</th>
	
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

                
				
				
			if ($Status == 'CUT-IN G1-G2' || $Status == 'CUT-IN G2-G1' || $Status == 'RUNNING G1' || $Status == 'RUNNING G2' || $Status == 'CUT-IN G2' || $Status == 'CUT-IN G1') {
				$statusColor='green';
			}
			elseif ($Status == 'FREE-WHEELING G1' || $Status == 'FREE-WHEELING G2' || $Status == 'FREE-WHEELING G2-G' || $Status == 'FREE-WHEELING G1-G') {
				$statusColor='orange';
			}
			elseif($Status=='Grid Drop' || $Status=='GridDrop'){
				$statusColor='blue';
			}else{
				$statusColor='red';
				}


                echo "<tr style='background:$bg'>";

                echo "<td align='center'>{$row['Date_S']}</td>";
                echo "<td align='center'>{$row['Time_S']}</td>";
                echo "<td align='center'>{$row['GRPM']}</td>";
                echo "<td align='center'>{$row['RRPM']}</td>";
                echo "<td align='center' style='color:$statusColor;font-weight:600;'>$Status</td>";
                echo "<td align='center'>".str_replace('m/s','',$row['Windspeed'])."</td>";
                echo "<td align='center'>{$row['Power']}</td>";
                echo "<td align='center'>{$row['L_N_Voltage_R']}</td>";
                echo "<td align='center'>{$row['L_N_Voltage_Y']}</td>";
                echo "<td align='center'>{$row['L_N_Voltage_B']}</td>";
                echo "<td align='center'>{$row['RPhase_Current']}</td>";
                echo "<td align='center'>{$row['YPhase_Current']}</td>";
                echo "<td align='center'>{$row['BPhase_Current']}</td>";
				echo "<td align='center'>{$row['Frequency']}</td>";				
				echo "<td align='center'>{$row['Active_Total_Gen_Import']}</td>";
				echo "<td align='center'>{$row['Active_Total_Gen_Export']}</td>";
				echo "<td align='center'>{$row['Kwh_Negative']}</td>";				
				echo "<td align='center'>{$row['Kwh_Positive']}</td>";				
				echo "<td align='center'>{$row['Min3_Wind_Speed']}</td>";
				echo "<td align='center'>{$row['Min3_Active_Power']}</td>";
				echo "<td align='center'>{$row['Total_Hours']}</td>";
				echo "<td align='center'>{$row['Operate_Hours']}</td>";
				echo "<td align='center'>{$row['Stopped_Hours']}</td>";
				echo "<td align='center'>{$row['Grid_failure_Hours']}</td>";
				
				
				

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='19' style='padding:15px; text-align:center;'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>
</div>



</div> <!-- end getdata -->

   

        <div class="material-card">
            <iframe class="responsive-iframe"
                    src="channel8_ajax.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>"></iframe>
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
