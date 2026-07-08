
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
<?
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
}								/*elseif($Closing_Time[1]=='20:00:00' || $Closing_Time[1]=='20:40:00' || $Closing_Time[1]=='20:20:00'){
										$GAD_Time=" and Hour(Time_S)>=20 ";
										$GD_Time=time()-72000;
										
}								elseif($Closing_Time[1]=='22:00:00' || $Closing_Time[1]=='22:30:00'){
										$GAD_Time=" and Hour(Time_S)>=22 ";
										$GD_Time=time()-79200;
}								elseif($Closing_Time[1]=='23:00:00' || $Closing_Time[1]=='23:30:00'){
										$GAD_Time=" and Hour(Time_S)>=23 ";
										$GD_Time=time()-82800;
}*/												
									else {
										$GAD_Time="";
										$GD_Time=time();
$Test_Time=date('H',$GD_Time);
}																	

/*$Mysql_Query_GAD = "select (select if(Production_Total>0,max(Production_Total),'')-if(Production_Total>0,min(Production_Total),'') from $Database_Name.device_data_f3 where IMEI = '".$IMEI_Decode."' and Date_S=curdate() $GAD_Time) as GAD_Today,(select if(Production_Total>0,max(Production_Total),'')-if(Production_Total>0,min(Production_Total),'') from $Database_Name.device_data_f3 where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) $GAD_Time) as GAD_Yesterday,(select if(Production_Total>0,max(Production_Total),'')-if(Production_Total>0,min(Production_Total),'') from $Database_Name.device_data_f3 where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) $GAD_Time) as GAD_Thisweek,(select if(Production_Total>0,max(Production_Total),'')-if(Production_Total>0,min(Production_Total),'') from $Database_Name.device_data_f3 where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) $GAD_Time) as GAD_Thismonth,(select if(Production_Total>0,max(Production_Total),'')-if(Production_Total>0,min(Production_Total),'') from $Database_Name.device_data_f3 where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 AND YEAR( Date_S) = YEAR( curdate() ) $GAD_Time) as GAD_Previousweek";*/
$Mysql_Query_GAD="select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";
//echo $Mysql_Query_GAD;
if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD))
            {
                die($db->error);
            }

            if($Mysql_Query_Result_GAD->num_rows >= 1)
            {
                while($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
			$GAD_Today = round($Fetch_Result_GAD['GAD_Today'],2);
			$GAD_Yesterday = round($Fetch_Result_GAD['GAD_Yesterday'],2);
			$GAD_Thisweek = round($Fetch_Result_GAD['GAD_Thisweek'],2);
			$GAD_Thismonth = round($Fetch_Result_GAD['GAD_Thismonth'],2);
			$GAD_Previousweek = round($Fetch_Result_GAD['GAD_Previousweek'],2);				
			}
}
	//echo $GAD_Thisweek;

$ER_Mysql_Query = "select Status as Log,Date_S,Time_S from $Database_Name.device_data_f3 where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
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
/*$ER_Mysql_Query = "(select Status as Log,Date_S,Time_S from $Database_Name.device_data_f3 where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1) union (select Status as Log,Date_S,Time_S from $Database_Name.error_data_f3 where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1) order by Date_S desc,Time_S desc limit 1";
	$ER_Mysql_Query_RESULT = mysql_query($ER_Mysql_Query) or die(mysql_error());
	$ER_Mysql_RECORD_COUNT = mysql_num_rows($ER_Mysql_Query_RESULT);
	if($ER_Mysql_RECORD_COUNT>=1){
		$ER_Fetch_Result = mysql_fetch_array($ER_Mysql_Query_RESULT);
		$Status = $ER_Fetch_Result['Log'];	
		$Date = $ER_Fetch_Result['Date_S'];
		$Time = $ER_Fetch_Result['Time_S'];		
	}*/



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


/* Material Card */
.material-card {
    background: #fff;
    border-radius: 14px;
    padding: 16px;  /* Replace with padding:16px 0; if you want zero left-right space */
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    margin-bottom: 22px;
    transition: box-shadow .2s ease;
}


.material-card:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,0.12);
}

/* Header Title */
.page-title {
    font-size: 26px;
    font-weight: 700;
    color: #333;
    margin-bottom: 6px;
}

/* Subtext */
.page-sub {
    font-size: 14px;
    color: #666;
}

/* Back Button */
.back-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid #dedede;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
}

.back-btn img {
    width: 26px;
}

/* Table Styling */
.table-container {
    overflow-x: auto;
    border-radius: 12px;
    background: #fff;
}

table {
    border-collapse: collapse;
    width: 100%;
}

table th {
    background: #e8eef5;
    padding: 10px;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    color: #444;
    border-bottom: 1px solid #ddd;
    position: sticky;
    top: 0;
    z-index: 5;
}

table td {
    padding: 9px;
    font-size: 14px;
    border-bottom: 1px solid #eee;
    background: #fff;
}

/* Status Chips */
.chip {
    padding: 5px 10px;
    border-radius: 18px;
    font-size: 12px;
    font-weight: 600;
    color: #fff;
    display: inline-block;
}

.chip.green { background: #4caf50; }
.chip.red { background: #f44336; }
.chip.blue { background: #2196f3; }

/* Section Title */
.section-header {
    font-size: 18px;
    font-weight: 600;
    padding: 8px 0;
    margin-bottom: 10px;
    color: #333;
}

/* Iframes */
.responsive-iframe {
    width: 100%;
    height: 360px;
    border: 1px solid #d5e0ee;
    border-radius: 12px;
}

/* Two column grid */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
.gad-center-box {
    display: flex !important;
    justify-content: center;
}



@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
    .responsive-iframe { height: 260px; }
}

.tcp-frame-box {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.tcp-frame-box iframe {
    width: 350px !important;
    height: 60px !important;
    min-width: 350px !important;
    border: none;
    overflow: hidden !important;
    display: block;
    scrollbar-width: none; /* Firefox */
}

/* Hide scrollbars in WebKit */
.tcp-frame-box iframe::-webkit-scrollbar {
    display: none;
}


.gad-row span {
    font-size: 18px;
    font-weight: 700;
    color: #222;
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

.gad-flex-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.gad-center-box {
    flex: 1;
    display: flex;
    justify-content: center;
}

.gad-center-box .gad-row {
    display: flex;
    justify-content: center;
    gap: 40px;
}


/* Remove large gap after table */
.table-container {
    margin-bottom: 8px !important;
}

/* Reduce card spacing */
.material-card {
    margin-bottom: 10px !important;
}

/* Reduce iframe spacing */
.responsive-iframe {
    margin-top: 5px !important;
}


</style>

<script>
// Auto refresh sections
$(function(){
    function refresh(){
        // Load only inner content for #getdata and #status to preserve layout container
        $("#getdata").load(location.href + " #getdata > *");
        $("#status").load(location.href + " #status > *");
    }
    setInterval(refresh, 20000);
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

<!-- TOP CARD -->
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
        href="channel4_ajax.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?>&FType=<?= $_REQUEST['FType'] ?>">
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

    </div>

</div>




    <!-- LIVE DATA TABLE -->
    

        <div class="material-card" style="padding:10px; margin-bottom:8px;">
    <div id="getdata" class="table-container">

            <table>
                <thead>
                    <tr>
                        <th>Date</th><th>Time</th><th>GRPM</th><th>RRPM</th><th>Status</th>
                        <th>Wind</th><th>Hyd</th><th>Nac</th><th>Power</th>
                        <th>R Volt</th><th>Y Volt</th><th>B Volt</th>
                        <th>R Cur</th><th>Y Cur</th><th>B Cur</th>
                        <th>PF</th><th>Freq</th>
                        <th>Amb</th><th>Nac</th><th>Gear Bearing</th><th>Gear Oil</th>
                        <th>G1°</th><th>G2°</th><th>Thyris</th><th>Main</th>
                        <th>G1</th><th>G2</th><th>Total</th>
                        <th>Hrs</th><th>G1H</th><th>G2H</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $rowColors = Array('#e6f2ff','#e6f2ff');
                    $i= 0;

    	# Getting the data from DEVICE_DATA_F2 based on IMEI
    	$Mysql_Query = "select * from $Database_Name.device_data_f3 where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 10";//echo $Mysql_Query;
    	if (!$Mysql_Query_Result = $db->query($Mysql_Query))
                {
                    die($db->error);
                }

                if($Mysql_Query_Result->num_rows >= 1)
                {
                    while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	
    			$Message_ID = $Fetch_Result['Message_ID'];
    			$IMEI = $Fetch_Result['IMEI'];
    			$Date_F = $Fetch_Result['Date_F'];
    			$Time_F = $Fetch_Result['Time_F'];
    			$Date_S = $Fetch_Result['Date_S'];
    			$Time_S = $Fetch_Result['Time_S'];
    			$Project_Version = $Fetch_Result['Project_Version'];
    			$ID_Number = $Fetch_Result['ID_Number'];		
    			$WindSpeed = $Fetch_Result['Windspeed'];
    			$GRPM = $Fetch_Result['GRPM'];		
    			$RRPM = $Fetch_Result['RRPM'];				
    			$Active_Power = $Fetch_Result['Power']; 
    			$Rphase_Volt = $Fetch_Result['RPhase_Volt'];
    			$Yphase_Volt = $Fetch_Result['YPhase_Volt'];
    			$Bphase_Volt = $Fetch_Result['BPhase_Volt'];
    			$Rphase_Current = $Fetch_Result['RPhase_Current'];
    			$Yphase_Current = $Fetch_Result['YPhase_Current'];
    			$Bphase_Current = $Fetch_Result['BPhase_Current'];
    			$Power_Factor = $Fetch_Result['Power_Factor'];				
    			$Status = $Fetch_Result['Status'];			
    			$Frequency = $Fetch_Result['Frequency'];
    			$Date = $Fetch_Result['Date'];
    			$Time = $Fetch_Result['Time'];																						
    			$Reactive_Power = $Fetch_Result['Reactive_Power'];
    			$Ambient_Temp = $Fetch_Result['Ambient_Temp'];
    			$Total_Hours = $Fetch_Result['Total_Hours'];
    			$Production_Total = $Fetch_Result['Production_Total'];
    			$Gen1_Production = $Fetch_Result['PAT_Gen1'];
    			$Gen2_Production = $Fetch_Result['PAT_Gen2'];
    			$Gen1_Hours = $Fetch_Result['Gen1_Hours'];
    			$Gen2_Hours = $Fetch_Result['Gen2_Hours'];
    			$Hyd = $Fetch_Result['Hydraulic_Pressure'];
    			$Nacelle_Position = $Fetch_Result['Nacelle_Position'];
    			$Thyristor_Temp = $Fetch_Result['Thyristor_Temp'];
    			$Main_Panel_Temp = $Fetch_Result['Main_Panel_Temp'];
    			$Gen1_Temp = $Fetch_Result['Gen1_Temp'];
    			$Gen2_Temp = $Fetch_Result['Gen2_Temp'];
    			$Gear_Bearing_Temp = $Fetch_Result['Bearing_Temp'];
    			$Gear_Oil_Temp = $Fetch_Result['Gear_Temp'];		
    			$Nacelle_Temp = $Fetch_Result['Nacel_Temp'];				
    			$Temp9 = $Fetch_Result['Temp9']; 
    			$Temp10 = $Fetch_Result['Temp10'];
    			$Import_Kwh = $Fetch_Result['Import_Kwh'];
    			$Import_Kvarh = $Fetch_Result['Import_Kvarh'];
    			$Yaw_CW_Counts = $Fetch_Result['Yaw_CW_Counts'];
    			$Yaw_CCW_Counts = $Fetch_Result['Yaw_CCW_Counts'];
    			
    		# Removing # symbal
    		$Status = str_replace('#','',$Status);		
    		$lastRecd = str_replace('.','-',$Date_F);	
    		$WindSpeed = str_replace('m/s','',$WindSpeed);	
    	
    		echo '<tr style="background-color:'.$rowColors[$i++ % count($rowColors)].';" class="tab-head-td-new" >';
    	
    	echo '<td  align="center">'.$Date_S.'</td>';
    	
    	 echo '<td align="center">'.$Time_S.'</td>';
    	?>
    	<td align="center"><?=$GRPM?></td>
    	<td align="center"><?=$RRPM?></td>
    	<?php
    								  //echo $Status;
    	if($Status=='Run' || $Status=='M/C Running' || $Status=='RUN' || $Status=='OperateG1' || $Status=='OperateG2' || $Status=='OPERATING   NORMAL OPERATION') {
    	?>
    	                                  
    	                                        <td align="center" style="color:green"><?=$Status?></td>
    	                                   
    	<?php
    	}
    	elseif($Status=='Grid Drop' || $Status=='GridDrop') {
    	?>
    	                                 <td align="center" style="color:blue"><?=$Status?></td>
    	<?php
    	}
    	else {
    	?>
    	                                  <td align="center" style="color:red"><?=$Status?></td>
    	<?php
    	}
    	?>
    	<!--<td align="center"><?=$Status?></td>-->
    	<td align="center"><?=$WindSpeed?></td>
    	<td align="center"><?=$Hyd?></td>
    	<td align="center"><?=$Nacelle_Position?></td>
    	<td align="center"><?=$Active_Power?></td>
    	<td align="center"><?=$Rphase_Volt?></td>
    	<td align="center"><?=$Yphase_Volt?></td>
    	<td align="center"><?=$Bphase_Volt?></td>
    	<td align="center"><?=$Rphase_Current?></td>
    	<td align="center"><?=$Yphase_Current?></td>
    	<td align="center"><?=$Bphase_Current?></td>
    	<td align="center"><?=$Power_Factor?></td>
    	<td align="center"><?=$Frequency?></td>
    	<td align="center"><?=$Ambient_Temp?></td>
    	<td align="center"><?=$Nacelle_Temp?></td>
    	<td align="center"><?=$Gear_Bearing_Temp?></td>
    	<td align="center"><?=$Gear_Oil_Temp?></td>
    	<td align="center"><?=$Gen1_Temp?></td>
    	<td align="center"><?=$Gen2_Temp?></td>
    	<td align="center"><?=$Thyristor_Temp?></td>
    	<td align="center"><?=$Main_Panel_Temp?></td>
    	<td align="center"><?=$Gen1_Production?></td>
    	<td align="center"><?=$Gen2_Production?></td>
    	<td align="center"><?=$Production_Total?></td>
    	<td align="center"><?=$Total_Hours?></td>
    	<td align="center"><?=$Gen1_Hours?></td>
    	<td align="center"><?=$Gen2_Hours?></td>
    	
    	</tr>
    	<?php
    	$MI++;
    	} 
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
    </div>

   

    <div class="material-card">
        <div class="section-header">Daily Generation Report</div>
        <iframe class="responsive-iframe"
                src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>"></iframe>
    </div>

</div>


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


<?php
    
?>

</body>
</html>

