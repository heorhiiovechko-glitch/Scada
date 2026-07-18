
<?php
error_reporting(0);	
include("header_inner.php");
	if(empty($_COOKIE[$Cook_Name])){
		header('Location: index.php');
		exit;
	}
header('Content-type: text/html; charset=utf-8');
?>
<?
	$lastRecd = null;
	$IMEI = $_REQUEST['c1'];
	if(isset($_REQUEST['l']))
		$Pocket_Length = $_REQUEST['l'];
	else
		$Pocket_Length = '';
	$IMEI_Decode = base64_decode($IMEI);
	$Param_Query="select * from va_victory.parameters where Account_ID=" .$Cook_Variable[3] ." limit 1";//echo $Param_Query; 
	$Param_Query_Result = mysql_query($Param_Query) or die(mysql_error());
	$Param_Record_Count = mysql_num_rows($Param_Query_Result);
	if($Param_Record_Count>=1){
		$Param_Fetch_Result = mysql_fetch_array($Param_Query_Result);
		$Electrical=$Param_Fetch_Result["Electrical"];
		$Status1=$Param_Fetch_Result["Status"];
		$Production=$Param_Fetch_Result["Production"];
		$Hour_Production=$Param_Fetch_Result["Hour_Production"];
		$Temperature=$Param_Fetch_Result["Temperature"];
		$Alarm_Log=$Param_Fetch_Result["Alarm_Log"];
	}
$ER_Mysql_Query = "(select Status as Log,Record_Index,Date_F,Time_F from va_victory.device_data_f6 where IMEI='".$IMEI_Decode."' order by Record_Index desc limit 1) union (select Error as Log,Record_Index,Date_F,Time_F from va_victory.error_data_f6 where IMEI='".$IMEI_Decode."' order by Record_Index desc limit 1) order by Date_F desc,Time_F desc limit 1";
	$ER_Mysql_Query_Result = mysql_query($ER_Mysql_Query) or die(mysql_error());
	$ER_Mysql_Record_Count = mysql_num_rows($ER_Mysql_Query_Result);
	if($ER_Mysql_Record_Count>=1){
		$ER_Fetch_Result = mysql_fetch_array($ER_Mysql_Query_Result);
		$Status = $ER_Fetch_Result['Log'];	
	}
	

	# Getting the data from DEVICE_DATA based on IMEI
	$Mysql_Query = "select * from va_victory.device_data_f6 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
	$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
	$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
	if($Mysql_Record_Count>=1){
		while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){  
			$Project_Version = $Fetch_Result['Project_Version'];
			$ID_Number = $Fetch_Result['ID_Number'];
			$GRPM = $Fetch_Result['GRPM'];
			$RRPM = $Fetch_Result['RRPM'];
			$WindSpeed = $Fetch_Result['WindSpeed'];
			$Pitch = $Fetch_Result['Pitch'];
			
			$Date = $Fetch_Result['Date'];
			$Time = $Fetch_Result['Time'];
			$Power = $Fetch_Result['Power'];
			$Rphase_Volt = $Fetch_Result['Rphase_Volt'];
			$Yphase_Volt = $Fetch_Result['Yphase_Volt'];
			$Bphase_Volt = $Fetch_Result['Bphase_Volt'];
			$Rphase_Current = $Fetch_Result['Rphase_Current'];
			$Yphase_Current = $Fetch_Result['Yphase_Current'];
			$Bphase_Current = $Fetch_Result['Bphase_Current'];
			$Power_factor = $Fetch_Result['Power_factor'];
			$Frequency = $Fetch_Result['Frequency'];
			$PAT_Gen0 = $Fetch_Result['PAT_Gen0'];
			$PAT_Gen1 = $Fetch_Result['PAT_Gen1'];
			$PAT_Total = $Fetch_Result['PAT_Gen2'];
			$PAM_Gen0 = $Fetch_Result['PAM_Gen0'];
			$PAM_Gen1 = $Fetch_Result['PAM_Gen1'];
			$PAM_Total = $Fetch_Result['PAM_Gen2'];
			$PATP_Gen0 = $Fetch_Result['PATP_Gen0'];
			$PATP_Gen1 = $Fetch_Result['PATP_Gen1'];
			$PATP_Total = $Fetch_Result['PATP_Gen2'];				
			$Total = $Fetch_Result['Total'];
			$Line_Ok = $Fetch_Result['Line_Ok'];
			$Turbine_Ok = $Fetch_Result['Turbine_Ok'];
			$Run = $Fetch_Result['Run'];
			$Gen1 = $Fetch_Result['Gen1'];				
			$Month_Total = $Fetch_Result['Month_Total'];
			$Month_Line_Ok = $Fetch_Result['Month_Line_Ok'];
			$Month_Turbine_Ok = $Fetch_Result['Month_Turbine_Ok'];
			$Month_Run = $Fetch_Result['Month_Run'];
			$Month_Gen1 = $Fetch_Result['Month_Gen1'];				
			$Trip_Total = $Fetch_Result['Trip_Total'];
			$Trip_Line_Ok = $Fetch_Result['Trip_Line_Ok'];
			$Trip_Turbine_Ok = $Fetch_Result['Trip_Turbine_Ok'];
			
			$Trip_Run = $Fetch_Result['Trip_Run'];
			$Trip_Run=str_replace('#','',$Trip_Run);
			
			$Trip_Gen1 = $Fetch_Result['Trip_Gen1'];
			$Trip_Gen1=str_replace('#','',$Trip_Gen1);				
									
			$Date_F = $Fetch_Result['Date_F'];
			$Time_F = $Fetch_Result['Time_F'];
			$Ambient= $Fetch_Result['Ambient'];
			$Nacelle= $Fetch_Result['Nacelle'];
			$Bearing=$Fetch_Result['Bearing'];
			$Gear = $Fetch_Result['Gear'];
			$Gen1_Temp = $Fetch_Result['Gen1_Temp'];
			$Controller= $Fetch_Result['Controller'];
			$Hydraulic= $Fetch_Result['Hydraulic'];
		}

		# Removing # symbal
		$Hydraulic= str_replace('#','',$Hydraulic);
		$Status = str_replace('#','',$Status);		
		$lastRecd = str_replace('.','-',$Date_F);	
		$WindSpeed = str_replace('m/s','',$WindSpeed);	
	}
	$No_Records = '<tr>
		<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
	</tr>';	
?> 
			<?php
			// Getting the customer information
			$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.DOC as DOC,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
			$Fetch_Info_Result = mysql_query($Fetch_Info) or die(mysql_error());
            $Fetch_Info_Result_Count = mysql_num_rows($Fetch_Info_Result);
            if($Fetch_Info_Result_Count>=1){
                $x = 1;
                while($Fetch_Details_Result = mysql_fetch_array($Fetch_Info_Result)){
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
			?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>

 <script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
 <script type="text/javascript">
    setInterval("my_function();",20000);
    function my_function(){
      $('#reload').load('channel11.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?> #reload');
    }

  </script>

<script type="text/javascript">
function EvalSound(soundobj) {
      var thissound=document.getElementById(soundobj);
      thissound.play();
    }
function showmenu() {
document.getElementById('menu').style.display = 'table';
document.getElementById('overview').style.display = 'none';
document.getElementById('prod').style.display = 'none';
document.getElementById('hour').style.display = 'none';
document.getElementById('elec').style.display = 'none';
document.getElementById('temp').style.display = 'none';
document.getElementById('log').style.display = 'none';

}
function showstatus() {
document.getElementById('overview').style.display = 'table';
document.getElementById('menu').style.display = 'none';
document.getElementById('prod').style.display = 'none';
document.getElementById('hour').style.display = 'none';
document.getElementById('elec').style.display = 'none';
document.getElementById('temp').style.display = 'none';
document.getElementById('log').style.display = 'none';
}
function showprod() {
document.getElementById('overview').style.display = 'none';
document.getElementById('menu').style.display = 'none';
document.getElementById('prod').style.display = 'block';
document.getElementById('hour').style.display = 'none';
document.getElementById('elec').style.display = 'none';
document.getElementById('temp').style.display = 'none';
document.getElementById('log').style.display = 'none';
document.getElementById('prod').scrollTop -= 500;
}
function showhour() {
document.getElementById('overview').style.display = 'none';
document.getElementById('menu').style.display = 'none';
document.getElementById('prod').style.display = 'none';
document.getElementById('hour').style.display = 'block';
document.getElementById('elec').style.display = 'none';
document.getElementById('log').style.display = 'none';
document.getElementById('temp').style.display = 'none';
document.getElementById('hour').scrollTop -= 500;
}
function showelec() {
document.getElementById('overview').style.display = 'none';
document.getElementById('prod').style.display = 'none';
document.getElementById('menu').style.display = 'none';
document.getElementById('hour').style.display = 'none';
document.getElementById('elec').style.display = 'table';
document.getElementById('temp').style.display = 'none';
document.getElementById('log').style.display = 'none';
}
function showtemp() {
document.getElementById('overview').style.display = 'none';
document.getElementById('prod').style.display = 'none';
document.getElementById('menu').style.display = 'none';
document.getElementById('hour').style.display = 'none';
document.getElementById('elec').style.display = 'none';
document.getElementById('temp').style.display = 'table';
document.getElementById('log').style.display = 'none';
}
function showlog() {
document.getElementById('overview').style.display = 'none';
document.getElementById('menu').style.display = 'none';
document.getElementById('prod').style.display = 'none';
document.getElementById('hour').style.display = 'none';
document.getElementById('elec').style.display = 'none';
document.getElementById('temp').style.display = 'none';
document.getElementById('log').style.display = 'block';
document.getElementById('log').scrollTop -= 500;

}

function scrollTablesDown()
{
var divscroll = document.getElementById("prod"); 
divscroll.scrollTop += 85;
var divscroll = document.getElementById("hour"); 
divscroll.scrollTop += 97;
var divscroll = document.getElementById("log"); 
divscroll.scrollTop += 95;
}
function scrollTablesUp()
{
var divscroll = document.getElementById("log"); 
divscroll.scrollTop -= 90;
}

</script>



<style type="text/css">
.tble{
    text-align:center;
    border:1px solid black;
    border-spacing:10px;
    padding:15px;
    background-color: #76923C;
    width:50%;
    height:10%; 
}
.button {
    width: 50px;
    height: 40px;
    border: solid 1px #000000;
    border-radius: 10px;
}
.button1 {
    text-align:center;
    border:4px solid black;
    background-color:white;
    width:400px;
    height:100px;
    border-radius: 10px; 
    display:none;            
}
.button2 {
    text-align:center;
    border:4px solid black;
    background-color:white;
    width:400px;
    height:100px;
    border-radius: 10px; 
}

.scroll {
    text-align:center;
    border:4px solid black;
    background-color:white;
    width:390px;
    height:90px;
    border-radius: 10px; 
    display:none;
    overflow: hidden;
   padding:1px;
    border-spacing:7px;
   }

</style>
</html>
<body>
  <center>
	  <div id="body" class="clear" style="width:1200px;">
    <div class="box">
      <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>
      <div class="content">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <td  width="60%">
          <h2>Energy from <?= $Firstname." ".$Lastname?> Detailed Information </h2>
          <p>about Status, Temperatures, Electrical, Production Figures</p>
      </td>
     <td  width="40%" align="right"><a href="dashboard.php"><img src="images/back_btn.png" height="40px" width="40px" /></a></td>
      </table> 
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
    		<tr>
        		<td width="60%" valign="top">
                      <table class="tble">
		<tr>
        		<td colspan="11">
                         <div id="reload">                               
	                       <table class="button1" id="menu">	
                              
               
<tr>
<td> <b> MENU </b></td><td></td><td><b>SELECT___</b></td>
</tr>
<tr>
<td align="left"><b>1: OVERVIEW</b></td><td align="left"><b>2: PROD.CNT.</b></td><td align="left"><b>3: HOUR CNT.</b><td>
</tr>
<tr>
<td align="left"><b>4: AVAILABIL.</b></td><td align="left"><b>5: EL.DATA</b></td><td align="left"><b>6: TEMP.</b><td>
</tr>
<tr>
<td align="left"><b>7: REM.CNTRL.</b></td><td align="left"><b>8: LOG</b></td><td align="left"><b>9: ALARM LOG</b><td>
</tr>

</table>

<table class="button2" id="overview">
<?php 
if($Status1==1){
if($Mysql_Record_Count >= 1){
									?>

<tr>
<td align="left" colspan="2"><b>1: OVERVIEW</b></td><td align="left"><?=$Date?></td><td></td><td><?=$Time?></td>
</tr>
<tr><td align="left"><b><?=$Status?></b></td>
</tr> 
<tr><td align="left" Width="20%"><b>Power</b></td><td align="left" Width="20%"><b>Gen.</b></td><td align="left" Width="20%"><b>Rotor</b></td><td align="left" Width="20%"><b>Wind</b></td><td align="left" Width="20%"><b>Pitch</b></td> </tr>
<tr><td align="left" Width="20%"><?=$Power?> KW</td><td align="left" Width="20%"><?=$GRPM?>rpm</td> <td align="left" Width="20%"><?=$RRPM?>rpm</td><td align="left" Width="20%"><?=$WindSpeed?> m/s </td><td align="left" Width="20%"><?=$Pitch?> Deg</td> </tr>
<?php
}
else {
?>
<tr><td> No Records</td></tr>
<?php
}
}
?>

</table>


<div class="scroll" id="prod">
<table>

<?php
if($Production==1){ 
if($Mysql_Record_Count >= 1){
									?>

<tr>
<td align="left" colspan="3" height="20px"><b>2: PRODUCTION Active TOTAL</b></td><td></td><td><?=$Date?></td>
</tr>
<tr>
<td align="left" Width="10%" height="20px"><b>Gen0:<b></td><td align="left" Width="30%" height="20px"><?=$PAT_Gen0?> KWh</td>
<td align="left" Width="10%" height="20px"><b>Prod:</b></td><td align="left" Width="30%" height="20px"><?=$PAT_Total?> KWh</td>
</tr> 
<tr><td align="left" height="20px"><b>Gen1:</b></td><td align="left" height="20px"><?=$PAT_Gen1?> KWh</td></tr>
<tr>
<td height="20px"></td>
</tr>
<?php
						#	Expect the below array 
						if($Pocket_Length != 32){
						?>
<td align="left" colspan="3" height="20px"><b>2B: PRODUCTION Active MONTH</b></td><td height="20px"></td><td height="20px"><?=$Date?></td>
</tr>
<tr>
<td align="left" Width="20%" height="20px"><b>Gen0:<b></td><td align="left" Width="20%" height="20px"><?=$PAM_Gen0?> KWh</td>
<td align="left" Width="20%" height="20px"><b>Prod:</b></td><td align="left" Width="20%" height="20px"><?=$PAM_Total?> KWh</td>
</tr> 
<tr><td align="left" height="20px"><b>Gen1:</b></td><td align="left" height="20px"><?=$PAM_Gen1?> KWh</td></tr>
<tr>
<td height="20px"></td>
</tr>
<tr>
<tr>
<td align="left" colspan="3" height="20px"><b>2D: PRODUCTION Active TRIP</b></td><td height="20px"></td><td height="20px"><?=$Date?></td>
</tr>
<tr>
<td align="left" Width="20%" height="20px"><b>Gen0:<b></td><td align="left" Width="20%" height="20px"><?=$PATP_Gen0?> KWh</td>
<td align="left" Width="20%" height="20px"><b>Prod:</b></td><td align="left" Width="20%" height="20px"><?=$PATP_Total?> KWh</td>
</tr> 
<tr><td align="left" height="20px"><b>Gen1:</b></td><td align="left" height="20px"><?=$PATP_Gen1?> KWh</td>
</tr>
<tr>
<td height="15px"></td>
</tr>

<?php
}
}
else {
?>
<tr><td> No Records</td></tr>
<?php
}
}
?>

</table>
</div>

<div class="scroll" id="hour">
<table>

<?php 
if($Hour_Production==1){
if($Mysql_Record_Count >= 1){
									?>

<tr>
<td align="left" colspan="3" height="20px"><b>3: HOURCOUNTERS TOTAL</b></td><td height="20px"></td><td height="20px"><?=$Date?></td>
</tr>
<tr>
<td align="left" Width="20%" height="20px"><b>Total:<b></td><td align="left" Width="20%" height="20px"><?=$Total?> h</td>
<td align="left" Width="20%" height="20px"><b>Run:</b></td><td align="left" Width="20%" height="20px"><?=$Run?> h</td>
</tr> 
<tr><td align="left" Width="20%" height="20px"><b>Line Ok:</b></td><td align="left" Width="20%" height="20px"><?=$Line_Ok?> h</td><td align="left" Width="20%" height="20px"><b>Gen1:</b></td><td align="left" Width="20%" height="20px"><?=$Gen1?> h</td></tr>
<tr><td align="left" Width="30%" height="20px"><b>Turbine Ok:</b></td><td align="left" Width="10%" height="20px"><?=$Turbine_Ok?> h</td>
</tr>
<tr><td height="5px"></td></tr>
<?php if($Pocket_Length != 32){ ?>
<tr>
<td align="left" colspan="3" height="20px"><b>3B: HOURCOUNTERS MONTH</b></td><td height="20px"></td><td height="20px"><?=$Date?></td>
</tr>
<tr>
<td align="left" Width="20%" height="20px"><b>Total:<b></td><td align="left" Width="20%" height="20px"><?=$Month_Total?> h</td>
<td align="left" Width="20%" height="20px"><b>Run:</b></td><td align="left" Width="20%" height="20px"><?=$Month_Run?> h</td>
</tr> 
<tr><td align="left" Width="20%" height="20px"><b>Line Ok:</b></td><td align="left" Width="20%" height="20px"><?=$Month_Line_Ok?> h</td><td align="left" Width="20%" height="20px"><b>Gen1:</b></td><td align="left" Width="20%" height="20px"><?=$Month_Gen1?> h</td></tr>
<tr><td align="left" Width="30%" height="20px"><b>Turbine Ok:</b></td><td align="left" Width="10%" height="20px"><?=$Month_Turbine_Ok?> h</td>
</tr>
<tr><td></td></tr>

<tr>
<td align="left" colspan="3" height="20px"><b>3D: HOURCOUNTERS TRIP</b></td><td></td><td><?=$Date?></td>
</tr>
<tr>
<td align="left" Width="20%" height="20px"><b>Total:<b></td><td align="left" Width="20%" height="20px"><?=$Trip_Total?> h</td>
<td align="left" Width="20%" height="20px"><b>Run:</b></td><td align="left" Width="20%" height="20px"><?=$Trip_Run?> h</td>
</tr> 
<tr><td align="left" Width="20%" height="20px"><b>Line Ok:</b></td><td align="left" Width="20%" height="20px"><?=$Trip_Line_Ok?> h</td><td align="left" Width="20%" height="20px"><b>Gen1:</b></td><td align="left" Width="20%" height="20px"><?=$Trip_Gen1?> h</td></tr>
<tr><td align="left" Width="30%" height="20px"><b>Turbine Ok:</b></td><td align="left" Width="10%" height="20px"><?=$Trip_Turbine_Ok?> h</td>
</tr>

<?php
}
}
else {
?>
<tr><td> No Records</td></tr>
<?php
}
}
?>

</table>
</div>

<table class="button1" id="elec">
<?php 
if($Electrical==1){
if($Mysql_Record_Count >= 1){
									?>

<tr>
<td align="left" colspan="2"><b>5: ELECTRICAL DATA</b></td><td></td><td align="left"><b>Voltage</b></td><td align="left"><b>Current</b></td>
</tr>
<tr>
<td align="left" Width="20%"><b>Power:<b></td><td align="left" Width="20%"><?=$Power?> KW</td>
<td align="left" Width="20%"><b>-L1- :</b></td><td align="left" Width="20%"><?=$Rphase_Volt?> V</td><td align="left" Width="20%"><?=$Rphase_Current?> A</td>
</tr> 
<tr><td align="left" Width="20%"><b>CosPhi:</b></td><td align="left" Width="20%"><?=$Power_factor?></td>
<td align="left" Width="20%"><b>-L2- :</b></td><td align="left" Width="20%"><?=$Yphase_Volt?> V</td><td align="left" Width="20%"><?=$Yphase_Current?> A</td>
</tr>
<tr><td align="left" Width="20%"><b>Freq:</b></td><td align="left" Width="20%"><?=$Frequency?> Hz</td>
<td align="left" Width="20%"><b>-L3- :</b></td><td align="left" Width="20%"><?=$Bphase_Volt?> V</td><td align="left" Width="20%"><?=$Bphase_Current?> A</td>
</tr>
<?php
}
else {
?>
<tr><td> No Records</td></tr>
<?php
}
}
?>

</table>

<table class="button1" id="temp">
<?php 
if($Mysql_Record_Count >= 1 && $Pocket_Length==50){
									?>

<tr>
<td align="left" colspan="2"><b>6: TEMPERATURES</b></td><td></td><td><?=$Date?></td><td></td><td><?=$Time?></td>
</tr>
<tr>
<td align="left" Width="20%"><b>Ambi:<b></td><td align="left" Width="20%"><?=$Ambient?>&deg;C </td>
<td align="left" Width="20%"><b>Nacel:</b></td><td align="left" Width="20%"><?=$Nacelle?>&deg;C</td>
<td align="left" Width="20%"><b>Gear:</b></td><td align="left" Width="20%"><?=$Gear?>&deg;C</td>
</tr><tr><td align="left" Width="20%"><b>Gen1:</b></td><td align="left" Width="20%"><?=$Gen1_Temp?>&deg;C</td>
<td align="left" Width="20%"><b>Cntrl:</b></td><td align="left" Width="20%"><?=$Controller?>&deg;C</td>
<td align="left" Width="20%"><b>Bear:</b></td><td align="left" Width="20%"><?=$Bearing?>&deg;C</td>
</tr>
<tr><td></td><td></td><td align="left" Width="20%"><b>Hydr.</b></td><td align="left" Width="20%"><?=$Hydraulic?>&deg;C</td>
</tr>
<?php
}
else {
?>
<tr><td> No Records</td></tr>
<?php
}
?>

</table>

<div class="scroll" id="log">
<table>
<tr>
<?php if($Alarm_Log==1){ ?>
 <td align="left" width="20%" height="20px"><b>DATE</b></td> <td align="left" width="20%" height="20px"><b>TIME</b></td> <td align="left" width="20%" height="20px"><b> ERROR STATUS</b></td>
                                    </tr>
              
<?php
						if($Mysql_Record_Count >= 1){
					?>
						<?php
							#
							#	Error Status from ERROR_DATA
							#
							$All_Error_Date_Arr = array();
							$All_Error_Time_Arr = array();
							$All_Error_Arr = array();
							$Mysql_Query_Error = "select * from va_victory.error_data_f6 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 10";
							$Mysql_Query_Error_Result = mysql_query($Mysql_Query_Error) or die(mysql_error());
							$Mysql_Record_Error_Count = mysql_num_rows($Mysql_Query_Error_Result);
							if($Mysql_Record_Error_Count>=1){
							
								while($Fetch_Error_Result = mysql_fetch_array($Mysql_Query_Error_Result)){						
										$All_Error_Date_Arr = date("d.m.Y",strtotime($Fetch_Error_Result['Date_F']));
										$All_Error_Time_Arr = date("H:i:s",strtotime($Fetch_Error_Result['Time_F']));
										$All_Error_Arr = $Fetch_Error_Result['Error'];
								?>
								<tr>                       
									<td align="left" width="20%" height="20px"><?=$All_Error_Date_Arr?></td>                                    
									<td align="left" width="20%" height="20px"><?=$All_Error_Time_Arr?></td>                  
									<td align="left" width="20%" height="20px"><?=$All_Error_Arr?></td>  
							   </tr>
						<?php
							$MI++;
								}
							}	
							else{
								echo $No_Records;
							}
						}	
						else{
							echo $No_Records;
					}
	}
					?>
</table>
</div>
</div>
</td>
<td colspan="3">
<table>
<tr>
<td style="font-size:120%;color:black"><?=$All_Devicename[1]?>
</td>
</tr> 
<tr>
<td style="font-size:110%;color:black"><?=$Site_Location[1]?>
</td>
</tr> 
</table>
</td>
</tr>


<form method="post" action="" enctype="multipart/form-data">

<tr>
		<td> <input type="button" value="7" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="8" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="9" class="button" onclick="EvalSound('audio1');showlog();"> </td>
		<td> <input type="button" value="ESC" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="RUN" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="PAUSE" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
</tr>
	<tr>
		<td> <input type="button" value="4" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="5" class="button" onclick="EvalSound('audio1');showelec();"> </td>
		<td> <input type="button" value="6" class="button" onclick="EvalSound('audio1');showtemp();"> </td>
		<td> <input type="button" value="CE" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="YAW
CW" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="YAW
STOP" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="&#9650" class="button" onclick="scrollTablesUp();EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
						
	</tr>
	<tr>
		<td> <input type="button" value="1" class="button" onclick="EvalSound('audio1');showstatus();"> </td>
		<td> <input type="button" value="2" class="button" onclick="EvalSound('audio1');showprod();"> </td>
		<td> <input type="button" value="3" class="button" onclick="EvalSound('audio1');showhour();"> </td>
		<td> <input type="button" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="YAW
CCW" class="button"> </td>
		<td> <input type="button" value="FUNC" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="&#9668" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="MENU" class="button" onclick="EvalSound('audio1');showmenu();"> </td>
		<td> <input type="button" value="&#9658" class="button" onclick="EvalSound('audio1');"> </td>
					
	</tr>
	<tr>
		<td> <input type="button" value="*" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="0" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="-" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="ENTER" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="*" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="#" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="&#9660" class="button" onclick="EvalSound('audio1');scrollTablesDown();"> </td>
		<td> <input type="hidden" value="" class="button" onclick="EvalSound('audio1');"> </td>
				
	</tr>

</form>
<audio id="audio1" src="Music/Bleep2.mp3" hidden="true" controls preload="auto" autobuffer>
</audio>

</table>
</td>
                 <td valign="top">
                 <?php
				 /*******
				 	Right side tab
				 ***/
				 ?>
                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  	<tr>
                            <td width="40%" valign="top">
                            <iframe src="channel2_ajax.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>" height="350px" width="500px" style="border:solid 1px #168A83"></iframe>
                            </td>
                    </tr>
</table>
</td>
</tr>
                     <tr>
                            <td height="20px">&nbsp;</td>
                    </tr>

		                </table>
          
          <div style="width:100%">&nbsp;</div>

          <p class="hr" style="float:left">&nbsp;</p><br />
        </div>
      </div>
    
    </div>
	</center>
  </body>
</html> 

<?php
	include("footer.php");
?>