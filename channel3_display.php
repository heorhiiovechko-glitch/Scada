

<?php
error_reporting(0);
	include("header_inner.php");
	if(empty($_COOKIE[$Cook_Name])){
		header("Location:index.php");
		exit;
	}
?>

<?php
	$Msg="";
	$lastRecd = null;
	$IMEI = $_REQUEST['c1'];
	//$Db_Name = $_REQUEST['Db'];	
	if(isset($_REQUEST['l']))
		$Pocket_Length = $_REQUEST['l'];
	else
		$Pocket_Length = '';
	$IMEI_Decode = base64_decode($IMEI);
		$FType=$_REQUEST['FType'];
//include("Gen_Export_Day.php");
	//include("Gen_Export_Month.php");
	//include("Gen_Export_Year.php");


$ER_Mysql_Query = "(select Status as Log,Date_S,Time_S from $Database_Name.device_data_f2 where IMEI='".$IMEI_Decode."' order by Record_Index desc limit 1) union (select Status as Log,Date_S,Time_S from $Database_Name.error_data_f2 where IMEI='".$IMEI_Decode."' order by Record_Index desc limit 1) order by Date_S desc,Time_S desc limit 1";
	$ER_Mysql_Query_Result = mysql_query($ER_Mysql_Query) or die(mysql_error());
	$ER_Mysql_Record_Count = mysql_num_rows($ER_Mysql_Query_Result);
	if($ER_Mysql_Record_Count>=1){
		$ER_Fetch_Result = mysql_fetch_array($ER_Mysql_Query_Result);
		$Status = $ER_Fetch_Result['Log'];	
		$Date = $ER_Fetch_Result['Date_S'];
		$Time = $ER_Fetch_Result['Time_S'];		
	}
	



	# Getting the data from DEVICE_DATA_F2 based on IMEI
	$Mysql_Query = "select * from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
	$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
	$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
	if($Mysql_Record_Count>=1){
		while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){  
			$Project_Version = $Fetch_Result['Project_Version'];
			$ID_Number = $Fetch_Result['ID_Number'];
			$GRPM = $Fetch_Result['GRPM'];
			$RRPM = $Fetch_Result['RRPM'];
			$WindSpeed = $Fetch_Result['Windspeed'];				
			$Power = $Fetch_Result['Power'];				
			$G1_Temp = $Fetch_Result['G1_Temp'];
			$G2_Temp = $Fetch_Result['G2_Temp'];
			$G3_Temp = $Fetch_Result['G3_Temp'];
			$G4_Temp = $Fetch_Result['G4_Temp'];
			$G5_Temp = $Fetch_Result['G5_Temp'];				
			$G6_Temp = $Fetch_Result['G6_Temp'];				
			$G1_Kwh = $Fetch_Result['PAT_Gen1'];
			$G2_Kwh = $Fetch_Result['PAT_Gen2'];
			$Import_Kwh = $Fetch_Result['Import_Kwh'];
			$G1_Hours = $Fetch_Result['Gen1_Hours'];
			$G2_Hours = $Fetch_Result['Gen2_Hours'];				
			$Rphase_Volt = $Fetch_Result['RPhase_Volt'];
			$Yphase_Volt = $Fetch_Result['YPhase_Volt'];
			$Bphase_Volt = $Fetch_Result['BPhase_Volt'];
			$Rphase_Current = $Fetch_Result['RPhase_Current'];
			$Yphase_Current = $Fetch_Result['YPhase_Current'];
			$Bphase_Current = $Fetch_Result['BPhase_Current'];
			$Power_factor = $Fetch_Result['Power_Factor'];				
			//$Status = $Fetch_Result['Status'];
			//$Date = $Fetch_Result['Date_S'];
			//$Time = $Fetch_Result['Time_S'];
			$Date_F = $Fetch_Result['Date_F'];
			$Time_F = $Fetch_Result['Time_F'];
		}
		# Removing # symbal
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
			$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, b.Lastname,a.Site_Location as Site_Location,a.DOC as DOC,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
			$Fetch_Info_Result = mysql_query($Fetch_Info) or die(mysql_error());
            $Fetch_Info_Result_Count = mysql_num_rows($Fetch_Info_Result);
            if($Fetch_Info_Result_Count>=1){
                $x = 1;
                while($Fetch_Details_Result = mysql_fetch_array($Fetch_Info_Result)){
                      $All_HTSC_No[$x] = $Fetch_Details_Result['HTSC_No'];					
                      $All_LOC_No[$x] = $Fetch_Details_Result['LOC_No'];					
					  $All_WEG_No[$x] = $Fetch_Details_Result['WEG_No'];					
					  $All_Firstname[$x] = $Fetch_Details_Result['Firstname'];
					  $All_Lastname[$x] = $Fetch_Details_Result['Lastname'];
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

										
								
/*$Mysql_Query_GAD = "select (select round((max(greatest(PAT_Gen1,0))-min(greatest(PAT_Gen1,0)))+(max(greatest(PAT_Gen2,0))-min(greatest(PAT_Gen2,0))),2) from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' and Date_S=curdate() $GAD_Time) as GAD_Today,(select round((max(greatest(PAT_Gen1,0))-min(greatest(PAT_Gen1,0)))+(max(greatest(PAT_Gen2,0))-min(greatest(PAT_Gen2,0))),2) from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) $GAD_Time) as GAD_Yesterday,(select round((max(greatest(PAT_Gen1,0))-min(greatest(PAT_Gen1,0)))+(max(greatest(PAT_Gen2,0))-min(greatest(PAT_Gen2,0))),2) from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) $GAD_Time) as GAD_Thisweek,(select round((max(greatest(PAT_Gen1,0))-min(greatest(PAT_Gen1,0)))+(max(greatest(PAT_Gen2,0))-min(greatest(PAT_Gen2,0))),2) from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) $GAD_Time) as GAD_Thismonth,(select round((max(greatest(PAT_Gen1,0))-min(greatest(PAT_Gen1,0)))+(max(greatest(PAT_Gen2,0))-min(greatest(PAT_Gen2,0))),2) from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 AND YEAR( Date_S) = YEAR( curdate() ) $GAD_Time) as GAD_Previousweek";*/
$Mysql_Query_GAD="select (select (max(PAT_Gen1)-min(PAT_Gen1))+(max(PAT_Gen2)-min(PAT_Gen2)) from $Database_Name.device_data_f2 where IMEI = '".$IMEI_Decode."' and Date_S=curdate() $GAD_Time) as GAD_Today,(select ((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) from $Database_Name.daily_generation_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select (max(Gen1_Max)-min(Gen1_Min))+(max(Gen2_Max)-min(Gen2_Min)) from $Database_Name.daily_generation_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select (max(Gen1_Max)-min(Gen1_Min))+(max(Gen2_Max)-min(Gen2_Min)) from $Database_Name.daily_generation_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select (max(Gen1_Max)-min(Gen1_Min))+(max(Gen2_Max)-min(Gen2_Min)) from $Database_Name.daily_generation_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";
//echo $Mysql_Query_GAD;

	$Mysql_Query_Result_GAD = mysql_query($Mysql_Query_GAD) or die(mysql_error());
	$Mysql_Record_Count_GAD = mysql_num_rows($Mysql_Query_Result_GAD);
	if($Mysql_Record_Count_GAD >=1){
		while($Fetch_Result_GAD = mysql_fetch_array($Mysql_Query_Result_GAD)){  
			
			$GAD_Today = $Fetch_Result_GAD['GAD_Today'];
			$GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
			$GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
			$GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
			$GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];

			}
}	//echo $GAD_Thisweek;

			?>



<html>
<head>
<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
 <script type="text/javascript" src="./js/eye.js"></script>
        <script type="text/javascript" src="./js/layout.js?ver=1.0.2"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
<script src="http://code.jquery.com/jquery-1.10.2.js"></script> 
<script type="text/javascript" src="js/jquery_1.5.2.js"></script>
 <script type="text/javascript">
    setInterval("my_function();",10000);
    function my_function(){
      $('#reload').load('channel3_display.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?> #reload');
    }

  </script>

<script type="text/javascript">
  
    function EvalSound(soundobj) {
      var thissound=document.getElementById(soundobj);
      thissound.play();
    }
function showstatus() {
document.getElementById('status').style.display = 'block';
document.getElementById('temp').style.display = 'none';
document.getElementById('grid').style.display = 'none';
document.getElementById('fault').style.display = 'none';
document.getElementById('status').scrollTop -= 300;
}
function showfault() {
document.getElementById('status').style.display = 'none';
document.getElementById('temp').style.display = 'none';
document.getElementById('grid').style.display = 'none';
document.getElementById('fault').style.display = 'block';
document.getElementById('fault').scrollTop -= 300;
}

function showgrid() {
document.getElementById('grid').style.display = 'table';
document.getElementById('temp').style.display = 'none';
document.getElementById('status').style.display = 'none';
document.getElementById('fault').style.display = 'none';
}
function showtemp() {
document.getElementById('status').style.display = 'none';
document.getElementById('temp').style.display = 'table';
document.getElementById('grid').style.display = 'none';
document.getElementById('fault').style.display = 'none';
}



</script>
<script type="text/javascript">
function scrollTablesUp()
{
var divscroll = document.getElementById("fault"); 
divscroll.scrollTop -= 95;

}

function scrollTablesDown()
{
var divscroll = document.getElementById("fault"); 
divscroll.scrollTop += 95;
var divscroll = document.getElementById("status"); 
divscroll.scrollTop += 85;
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
    width: 60px;
    height: 40px;
    border: solid 1px #000000;
    border-radius: 10px;
}
.button1 {
    position:relative;
    text-align:center;
    border:4px solid black;
    background-color:white;
    width:300px;
    height:100px;
    border-radius: 10px; 
    display:none;
      
}
.scroll2 {
   position:relative;
    text-align:center;
    border:4px solid black;
    background-color:white;
    width: 292px;
    height: 92px;
    border-radius: 10px; 
    overflow: hidden;
}

.scroll {
    position:relative;
    text-align:center;
    border:4px solid black;
    background-color:white;
    width: 292px;
    height: 92px;
    border-radius: 10px; 
    display:none;
    overflow: hidden;
    
   }

</style>

</head>
<body>
</center>

<center>
	<div id="body"  style="width:1050px;">
<div class="box">
      <!--<em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>-->
      <div class="content">
    
    	<table border="0" cellpadding="0" cellspacing="0" width="100%">
      	<td  width="50%">
          <h2>Energy from <? print_r($All_Firstname[1]) ?>   <?print_r($All_Lastname[1])?> Detailed Information!</h2>
          <p>about Status, Temperatures, Electrical, Production Figures</p>
      </td>

	  <td  width="50px" align="right"><a href="channel1.php"><img src="images/back_btn.png" height="40px" width="40px" /></a></td>
      </table> 
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    		<tr>
        		<td width="50%" valign="top">
                
	
<table class="tble">
		<tr>
        		<td colspan="4">
                <div id="reload">                    
<div class="scroll" id="fault">	                       
 <table>	
                              
 <tr>
                                       <td align="left" width="20%"><b>DATE</b></td> <td align="left" width="20%"><b>TIME</b></td> <td align="left" width="20%"><b> ERROR STATUS</b></td>
                                    </tr>
              
<?php
						if($Mysql_Record_Count >= 1){
					?>
						<?php
							#
							#	Error Status from ERROR_DATA_F2
							#
							$All_Error_Date_Arr = array();
							$All_Error_Time_Arr = array();
							$All_Error_Arr = array();
							$Mysql_Query_Error = "select Date_S,Time_S,Status from $Database_Name.error_data_f2 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 10";
							$Mysql_Query_Error_Result = mysql_query($Mysql_Query_Error) or die(mysql_error());
							$Mysql_Record_Error_Count = mysql_num_rows($Mysql_Query_Error_Result);
							if($Mysql_Record_Error_Count>=1){
							
								while($Fetch_Error_Result = mysql_fetch_array($Mysql_Query_Error_Result)){						
										$All_Error_Date_Arr = date("d.m.Y",strtotime($Fetch_Error_Result['Date_S']));
										$All_Error_Time_Arr = date("H:i:s",strtotime($Fetch_Error_Result['Time_S']));
										$All_Error_Arr = $Fetch_Error_Result['Status'];
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
					?>
	</table>
</div>
<div class="scroll2" id="status">
  <table>
<?php

									if($Mysql_Record_Count >= 1){
									?>
                                  
<tr>
                                       <td width="30%" align="center" height="20px"><?=$Rphase_Volt?> V</td> <td width="30%" align="center" height="20px"><?=$Power?> KW</td>
                                    </tr>
<tr>
<td width="30%" align="center" height="20px"><?=$WindSpeed." m/s"?></td><td width="30%" align="center" height="20px"><?=$RRPM?>/<?=$GRPM?> RPM</td>
</tr>
<tr>
<td width="30%" align="center" height="20px"><?=$Date?></td><td width="30%" align="center" height="20px"><?=$Time?></td>
</tr>
<tr>
<td colspan="2" align="center" height="10px"><?=$Status?></td>
</tr>
<tr><td></td>
</tr>



<tr>
 <td colspan="2" align="center" height="20px">SYSTEM</td> 
</tr>
<tr>
<td width="30%" align="center" height="20px">PROD:</td> <td width="30%" align="center" height="20px"><?=$G1_Kwh?> KWh</td> 
</tr>
<tr>
<td width="30%" align="center" height="20px">PROD:-</td> <td width="30%" align="center" height="20px"><?=$G2_Kwh?> KWh</td> 
</tr>
<tr>
<td></td>
</tr>
<tr>
                            <td height="20px">&nbsp;</td>
                    </tr>


<tr>
 <td colspan="2" align="center" height="20px">GENERATOR G1</td> 
</tr>
<tr>
<td width="30%" align="center" height="20px">PROD:</td> <td width="30%" align="center" height="20px"><?=$G1_Kwh?> KWh</td> 
</tr>
<tr>
<td width="30%" align="center" height="20px">PROD HRS:</td> <td width="30%" align="center" height="20px"><?=$G1_Hours?> h</td> 
</tr>
<tr>
                            <td height="1px">&nbsp;</td>
                    </tr>


<tr>
 <td colspan="2" align="center" height="20px">GENERATOR G2</td> 
</tr>
<tr>
<td width="30%" align="center" height="20px">PROD:</td> <td width="30%" align="center"><?=$G2_Kwh?> KWh</td> 
</tr>
<tr>
<td width="30%" align="center" height="20px">PROD HRS:</td> <td width="30%" align="center"><?=$G2_Hours?> h</td> 
</tr>
<tr><td></td>
</tr>
<tr>
                            <td height="5px">&nbsp;</td>
                    </tr>


<?php
    }
else {
?>
<tr><td colspan="4" align="center" height="80px"> No Records</td></tr>
<?php
}

 ?>
</table>
</div>


<table class="button1" id="temp">
<?php

		if($Mysql_Record_Count >= 1){
if($Pocket_Length == 27){
												
												$Temp1_Head = "Gear Bearing DE Temp";
												
												$Temp2_Head = "G2 Winding Temp";
												$Temp3_Head = "G1 Winding Temp";
												$Temp4_Head = "Gear Oil Temp";
											}
											else{
												$Temp1_Head = "Temp 1";
												$Temp2_Head = "Temp 2";
												$Temp3_Head = "Temp 3";
												$Temp4_Head = "Temp 4";
													}											
									?>
<tr>
 <td><?=$Temp1_Head?></td> <td><?=$G2_Temp?>&deg;C</td> 
</tr>
<tr>
 <td><?=$Temp2_Head?></td> <td><?=$G4_Temp?>&deg;C</td> 
</tr>
<tr>
 <td><?=$Temp3_Head?></td> <td><?=$G5_Temp?>&deg;C</td> 
</tr>
<?php
										if($Pocket_Length != 32){
										?>
<tr>
 <td><?=$Temp4_Head?></td> <td><?=$G6_Temp?>&deg;C</td> 
</tr>


                                  						
																				<?php
										}
										?>
									
									<?php
                                      }
else {
?>
<tr><td> No Records</td></tr>
<?php
}


?>
 </table>


<table class="button1" id="grid">
<?php

		if($Mysql_Record_Count >= 1){
?>

<tr>
 <td><?=$Rphase_Volt?> V</td> <td><?=$Rphase_Current?> A</td> <td><?=$Power?> KW</td> 
</tr>
<tr>
 <td><?=$Yphase_Volt?> V</td> <td><?=$Yphase_Current?> A</td> <td><?=$Power_factor?></td> 
</tr>
<tr>
 <td><?=$Bphase_Volt?> V</td> <td><?=$Bphase_Current?> A</td> 
</tr>

<?php } 
else {
?>
<tr><td> No Records</td></tr>
<?php
}

?>


</table>
</div>
</td>
<td colspan="2">
<table>
<tr>
<td style="font-size:110%;color:black"><?=$All_Devicename[1]?>
</td>
</tr> 
<tr>
<td style="font-size:110%;color:black"><?=$Site_Location[1]?>
</td>
</tr> 

</table>
</td>


</tr>
		<form method="post" action="" name="myForm" enctype="multipart/form-data">	
<tr>
		<td> <input type="button" value="7" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="8" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="9" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="PROG" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="STATUS" name="status" id="status" class="button" onclick="EvalSound('audio1');showstatus();" > </td>
		<td> <input type="button" value="TEMP." name="temp" id="temp" class="button" onclick="EvalSound('audio1');showtemp();"> </td>
	</tr>
	<tr>
		<td> <input type="button" value="4" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="5" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="6" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="&#9650
SCROL" class="button" onclick="EvalSound('audio1');scrollTablesUp();"> </td>
		<td> <input type="button" value="GRID" name="grid" id="grid" class="button" onclick="EvalSound('audio1');showgrid();"> </td>
		<td> <input type="button" value="FAULT" class="button" name="fault" id="fault" onclick="EvalSound('audio1');showfault();"> </td>
	</tr>
	<tr>
		<td> <input type="button" value="1" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="2" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="3" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="&#9660
SCROL" class="button" onclick="EvalSound('audio1');scrollTablesDown();">  </td>
		<td> <input type="button" value="STOP/
RESET" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="START" class="button" onclick="EvalSound('audio1');"> </td>
	</tr>
	<tr>
		<td> <input type="button" value="0" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="ENTER" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="MAN." class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="&#9668 
YAW" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="STOP 
YAW" class="button" onclick="EvalSound('audio1');"> </td>
		<td> <input type="button" value="&#9658
YAW" class="button" onclick="EvalSound('audio1');"> </td>
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
                            <td width="50%" valign="top">
								<iframe  src="channel3_ajax.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>" rows="40" cols="60" style="border:solid 1px #168A83; width:500px; height:350px;"></iframe>
                            </td>
                    </tr>
</table>
</td>
</tr>
                     <tr>
                            <td height="10px">&nbsp;</td>
                    </tr>

<tr>
<td> <h2>Production Details:</h2></td>
</tr>
<tr>
<td> <b>GAD for Today:</b>&nbsp;<?=$GAD_Today > 5000 || $GAD_Today < 0  ? "Nil":$GAD_Today." Kwh" ?> </td><td></td>
</tr>
<tr>
<td> <b>GAD for Yesterday:</b>&nbsp;<?=$GAD_Yesterday > 5000 || $GAD_Yesterday < 0 ? "Nil":$GAD_Yesterday." Kwh" ?> </td><td></td>
</tr>
<tr>
<td> <b>GAD for This week:</b>&nbsp;<?=$GAD_Thisweek > 25000 || $GAD_Thisweek < 0 ? "Nil":$GAD_Thisweek." Kwh" ?> </td><td></td>
</tr>
<tr>
<td> <b>GAD for Previous week:</b>&nbsp;<?=$GAD_Previousweek > 25000 || $GAD_Previousweek < 0  ? "Nil":$GAD_Previousweek." Kwh" ?> </td><td></td>
</tr>
<tr>
<td> <b>GAD for This Month:</b>&nbsp;<?=$GAD_Thismonth > 150000 || $GAD_Thismonth < 0  ? "Nil":$GAD_Thismonth." Kwh" ?> </td><td></td>
</tr>


</table>

    <div style="width:40%">&nbsp;</div>
          <p class="hr" style="float:left">&nbsp;</p><br />
        </div>
      </div>
</div>
    
    
	</center>
</body>
</html>
</td>
</tr>

<?php
	include("footer.php");
?>