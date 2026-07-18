
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
//echo date('01-m-Y') . '<br/>';
//echo date('m-t-Y 12:59:59',strtotime(now())) . '<br/>';
?>
<?
	$lastRecd = null;
	$IMEI = $_REQUEST['c1'];
	$OPCID = $_REQUEST['UID'];
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

	// Getting the customer information
			$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour,a.Db_Name as Database_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
			if (!$Fetch_Info_Result = $db->query($Fetch_Info))
            {
                die($db->error);
            }
            if($Fetch_Info_Result->num_rows >= 1)
            {
				$x = 1;
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
					  $Database_Name = $Fetch_Details_Result['Database_Name'];
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

//$Mysql_Query_GAD="select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";

//$Mysql_Query_GAD="select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";

$Mysql_Query_GAD = "select (select (Gen1_Max) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,
(select (Gen1_Max) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day)) as GAD_Yesterday,
(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S Between ((CURDATE()  - INTERVAL 7 DAY)) and curdate()) as GAD_Thisweek,
(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and month(Date_s)=month(now())) as GAD_Thismonth,
(select sum((Gen1_Max)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S Between (curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY) and (curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY)) as GAD_Previousweek";

//echo $Mysql_Query_GAD;
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
	/*$ER_Mysql_Query = "select Status as Log,Date_S,Time_S,Event_Name from $Database_Name.winwind where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
	if (!$ER_Mysql_Query_Result = $db->query($ER_Mysql_Query))
            {
                die($db->error);
            }

            if($ER_Mysql_Query_Result->num_rows >= 1)
            {
                $ER_Fetch_Result = $ER_Mysql_Query_Result->fetch_array();
		$Log_Status = $ER_Fetch_Result['Log'];	
		$Event = $ER_Fetch_Result['Event_Name'];
		}
	# Getting Alarm Statu from ERROR_DATA based on IMEI
	$ER_Status_Mysql_Query = "select Status from $Database_Name.error_data_f6 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
	$ER_Status_Mysql_Query_Result = mysql_query($ER_Status_Mysql_Query) or die(mysql_error());
	$ER_Status_Mysql_Record_Count = mysql_num_rows($ER_Status_Mysql_Query_Result);
	if($ER_Status_Mysql_Record_Count>=1){
		$ER_Status_Fetch_Result = mysql_fetch_array($ER_Status_Mysql_Query_Result);
		$Alarm_Log_Status = $ER_Status_Fetch_Result['Status'];	
	}
	

	$ER_Mysql_Query = "(select Status as Log,Date_S,Time_S from $Database_Name.device_data_f6 where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1) union (select Status as Log,Date_S,Time_S from $Database_Name.error_data_f6 where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1) order by Date_S desc,Time_S desc limit 1";
	//echo $ER_Mysql_Query;
	$ER_Mysql_Query_Result = mysql_query($ER_Mysql_Query) or die(mysql_error());
	$ER_Mysql_Record_Count = mysql_num_rows($ER_Mysql_Query_Result);
	if($ER_Mysql_Record_Count>=1){
		$ER_Fetch_Result = mysql_fetch_array($ER_Mysql_Query_Result);
		$Status = $ER_Fetch_Result['Log'];	
		$Date = $ER_Fetch_Result['Date_S'];
		$Time = $ER_Fetch_Result['Time_S'];		
	}*/
	

	$No_Records = '<tr>
		<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
	</tr>';	
?> 
<?php
				//echo $GAD_Thisweek;
			

?>

<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
 <script>   
$(document).ready(
            function() {
                setInterval(function() {
                          $('#getdata').load('channel9new.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?> #getdata');
                }, 20000);
            });
</script>
<script>   
$(document).ready(
           function() {
                setInterval(function() {
                          $('#status').load('channel9new.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?> #status');
                }, 20000);
            });
</script>



   <center>
	  <div id="body" class="clear" style="width:1345px;">
  <!--  <div class="box">-->
     <!-- <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>-->
    <!--  <div class="content">-->
         <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <td  width="40%">
              <h2>Energy from versatilescada Detailed Information!</h2>
              <p>about Status, Temperatures, Electrical, Production Figures</p>
          </td><td width="30%" style="font-weight:bold;font-size:20px;"><?=$All_Devicename[1]?></td>
		  <td align="right">
				 <iframe  src="TcpRequest.php?c1=<?=$_REQUEST['c1']?>&db=<?= $Database_Name ?>" rows="1" cols="1" style="background-color:Transparent; border:solid 0px #168A83;height:80px;width:480px;"></iframe>
		  </td>
		<?php
			//if($Account_ID=='7') {
		?>
		  
		 <?php
			//}
		?>
         <td  width="10%" align="right"><a href="dashboard.php"><img src="images/back_btn.png" height="40px" width="40px" /></a></td>
          </table> 
<div id="getdata" >

           <table border="5px solid black" cellpadding="5" cellspacing="1" width="100%" style="background-color:black;" class="innertab1">

<tr class="tab-head-tr-new">
			
							<td colspan="6" align="center">Status</td>
							<td colspan="11" align="center">Electrical</td>
							<td colspan="9" align= "center">Temperature</td>
							<td colspan="4" align= "center">Active Production</td>
							<td align="center"></td>
							
								</tr>

		
<tr class="tab-head-tr-new" align="center">


								<th>Date</th>
								<th>Time</th>
								<th>GRPM</th>
								<th>RRPM</th>
								<th width="90px">Status</th>	
								<th width="40px">Wind Spd</th>
								<th>Power</th>
								<th>R Volt</th>	
								<th>Y Volt</th>
								<th>B Volt</th>
								<th>R Cur</th>	                                            				                                                                                          
                                <th>Y Cur</th>
								<th>B Cur</th>
								<th>Oil Pressure</th>
								<th>Twist</th>
								<th>Nacelle Position</th>
								<th>Wind Direction</th>
								<th>G1_L1</th>
								<th>G1_L2</th>
								<th>G1_L3</th>
								<th>G2_L1</th>
								<th>Gear OilSump</th>
								<th>Hub Bear</th>
								<th>Outdoor</th>
								<th>G2_L2 </th>
								<th>Gearbox HSS</th>
								<th >Prod</th>
								<th >Consump</th>
								<th >Prod</th>
								<th >Consump</th>
								<th>Event</th>		
								
</tr>


<tr class="tab-head-tr-new" align="center">
								<td></td>
								<td></td>
								<td>rpm</td>
								<td>rpm</td>
								<td></td>
								<td>m/s</td>
								<td>KW</td>
								<td>V</td>
								<td>V</td>
								<td>V</td>
								<td>A</td>
								<td>A</td>
								<td>A</td>
						
								<td>Bar</td>
								<td></td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>deg</td>
								<td>Kwh</td>
								<td>Kwh</td>
								<td>KVarh</td>
								<td>KVarh</td>	
								<td></td>
						
				</tr>

<?php
//$rowColors = Array('#94b8b8','#ffffff'); 
$rowColors = Array('#e6f2ff','#e6f2ff'); 
$i= 0;

	# Getting the data from DEVICE_DATA based on IMEI
		$Mysql_Query = "select * from $Database_Name.device_data_f9 where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
	if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	 
			$Project_Version = $Fetch_Result['Project_Version'];
			$ID_Number = $Fetch_Result['ID_Number'];
			$GRPM = $Fetch_Result['GRPM'];
			$RRPM = $Fetch_Result['RRPM'];
			$WindSpeed = $Fetch_Result['Windspeed'];
			$Pitch = $Fetch_Result['Pitch'];
			$Status = $Fetch_Result['Status'];
			$Date_B = $Fetch_Result['Date_S'];
			$Time_B = $Fetch_Result['Time_S'];
			$Date_S = $Fetch_Result['Date'];
			$Time_S = $Fetch_Result['Time'];
			
			$Power = $Fetch_Result['Power'];
			$Rphase_Volt = $Fetch_Result['RPhase_Volt'];
			$Yphase_Volt = $Fetch_Result['YPhase_Volt'];
			$Bphase_Volt = $Fetch_Result['BPhase_Volt'];
			$Rphase_Current = $Fetch_Result['RPhase_Current'];
			$Yphase_Current = $Fetch_Result['YPhase_Current'];
			$Bphase_Current = $Fetch_Result['BPhase_Current'];
			$Power_factor = $Fetch_Result['Power_Factor'];
			$Frequency = $Fetch_Result['Frequency'];
			$Production_kwh = $Fetch_Result['P_Kwh'];
			$Consumption_kwh = $Fetch_Result['C_Kwh'];
			$Production_kvarh = $Fetch_Result['P_Kvarh'];
			$Consumption_kvarh = $Fetch_Result['C_Kvarh'];
			$Hyd_Pressure = $Fetch_Result['Oil_Pressure'];
			$PAM_Total = $Fetch_Result['PAM_Gen2'];
			$Twist = $Fetch_Result['Twist'];
			$Nacelle_Position = $Fetch_Result['Nacelle'];
			$Wind_Direction = $Fetch_Result['Wind_Direction'];				
			$Total = $Fetch_Result['Total_Hours'];
			$G1_L1Temp = $Fetch_Result['G1_L1Temp'];
			$G1_L2Temp = $Fetch_Result['G1_L2Temp'];
			$G1_L3Temp = $Fetch_Result['G1_L3Temp'];
			$G2_L1Temp = $Fetch_Result['G2_L1Temp'];				
			$Gear_OilSump = $Fetch_Result['Gear_OilSump'];
			$Hub_Bearing = $Fetch_Result['Hub_Bearing'];
			$Outdoor = $Fetch_Result['Outdoor'];
			$G2_L2Temp = $Fetch_Result['G2_L2Temp'];
			$Gearbox_HSS = $Fetch_Result['Gearbox_HSS'];
			$Event = $Fetch_Result['Eventlog'];
			$Date_F = $Fetch_Result['Date_F'];
			$Time_F = $Fetch_Result['Time_F'];
			
		# Removing # symbal
		$Hydraulic= str_replace('#','',$Hydraulic);
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
if($Status=='Run' || $Status=='M/C Running' || $Status=='RUN' || $Status=='OperateG1' || $Status=='OperateG2' || $Status=='OPERATING   NORMAL OPERATION' || $Status=='RUNNING G1' || $Status=='RUNNING G2') {
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
<td align="center"><?=$Power?></td>
<td align="center"><?=$Rphase_Volt?></td>
<td align="center"><?=$Yphase_Volt?></td>
<td align="center"><?=$Bphase_Volt?></td>
<td align="center"><?=$Rphase_Current?></td>
<td align="center"><?=$Yphase_Current?></td>
<td align="center"><?=$Bphase_Current?></td>

<td align="center"><?=$Hyd_Pressure?></td>
<td align="center"><?=$Twist?></td>
<td align="center"><?=$Nacelle_Position?></td>
<td align="center"><?=$Wind_Direction?></td>
<td align="center"><?=$G1_L1Temp?></td>
<td align="center"><?=$G1_L2Temp?></td>
<td align="center"><?=$G1_L3Temp?></td>
<td align="center"><?=$G2_L1Temp?></td>
<td align="center"><?=$Gear_OilSump?></td>
<td align="center"><?=$Hub_Bearing?></td>
<td align="center"><?=$Outdoor?></td>
<td align="center"><?=$G2_L2Temp?></td>
<td align="center"><?=$Gearbox_HSS?></td>
<td align="center"><?=$Production_kwh?></td>
<td align="center"><?=$Consumption_kwh?></td>
<td align="center"><?=$Production_kvarh?></td>
<td align="center"><?=$Consumption_kvarh?></td>
<td align="center"><?=$Event?></td>
</tr>
<?php
	
$MI++;
} 
}

?>
</table>
</div>
       <!-- <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
              
                    <div id="status">
                             <table width="50%" border="0" align="center" cellpadding="1" cellspacing="1" class="innertab1">	
                                	<tr>
                                        <td colspan="2" class="tab-head-tr-new">&nbsp;&nbsp;Current Status</td>
                                    </tr>
                                   
                                    
									<?php

										if($Mysql_Query_Result->num_rows >= 1){
									?>
                                   
                                  <?php
if($Log_Status=='Run' || $Log_Status=='M/C Running' || $Log_Status=='RUN' || $Log_Status=='OperateG1' || $Log_Status=='OperateG2' || $Log_Status=='RUNNING G1' || $Log_Status=='RUNNING G2') {
?>
                                  <tr>
                                        <td class="tab-head-td-new" align="center">Machine Status </td>
                                        <td class="tab-head-td1-status" style="background-color:green"><?=$Log_Status?></td>
                                    </tr>
<?php
}
elseif($Log_Status=='Grid Drop' || $Log_Status=='GridDrop') {
?>
                                  <tr>
                                        <td class="tab-head-td-new">Machine Status </td>
                                        <td class="tab-head-td1-status" style="background-color:blue"><?=$Log_Status?></td>
                                    </tr>
<?php
}
else {
?>
                                  <tr>
                                        <td class="tab-head-td-new">Machine Status </td>
                                        <td class="tab-head-td1-status" style="background-color:red"><?=$Log_Status?></td>
                                    </tr>
<?php
}
?>
									<tr>
                                        <td width="50%" class="tab-head-td-new">Event Status </td>
                                        <td class="tab-head-td1-new"><?=$Event?></td>
                                    </tr> 
			                            
				  <?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?>  
                                </table>
</div>-->

 <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
						
                           <table width="50%" border='0' align="center" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr>
                                        <td colspan="2" class="tab-head-tr-new">&nbsp;&nbsp;GAD Details</td>
                                    </tr>
                                    <?php

						if($Mysql_Query_Result->num_rows >= 1){
				   ?>
                                    <tr>
                                        <td width="50%" class="tab-head-td-new">GAD for Today</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Today > 30000 || $GAD_Today < 0 ? "Nil":$GAD_Today." Kwh" ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td-new">GAD for Yesterday</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Yesterday > 30000 || $GAD_Yesterday < 0 ? "Nil":$GAD_Yesterday." Kwh" ?></td>
                                    </tr>
					 <!--<tr>
                                        <td class="tab-head-td-new">GAD for This Week</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Thisweek > 300000 || $GAD_Thisweek < 0 ? "Nil":$GAD_Thisweek." Kwh" ?> </td>
                                    </tr>
			 <tr>
                                        <td class="tab-head-td-new">GAD for Previous Week</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Previousweek > 300000 || $GAD_Previousweek < 0 ? "Nil":$GAD_Previousweek." Kwh" ?> </td>
                                    </tr>
<tr>
                                        <td class="tab-head-td-new">GAD for This Month</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Thismonth > 900000 || $GAD_Thismonth < 0 ? "Nil":$GAD_Thismonth." Kwh" ?> </td>
                                    </tr> -->
                       
                                     <?php
                                      }
					else{
						echo $No_Records;
					}
					?>  
                                </table>
						

                            
<tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
                                               
                                 <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>

 <!--<?php
if($Cook_Variable[3]!='7')
					{
						?>

			<tr align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%">

<td  width="100%" valign="top" align="center" colspan="4">
					 <iframe  src="Power_Windspeed_chart_Monthly_iframe.php?c1=<?=$_REQUEST['c1']?>&Year=<?=date('m-Y')?>&l=<?=$_REQUEST['l']?>" rows="40" cols="60" style="background-color:#f6f6f6; border:solid 1px #168A83; width:1050px; height:350px;"></iframe>
                            </td>
                    
                            
                    </tr>
                     <tr>
                            <td height="20px">&nbsp;</td>
                    </tr>
					
                                     			</table>
                 
               </tr>
<tr align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <td  width="100%" valign="top" align="center" colspan="4">
					 <iframe  src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>" rows="40" cols="60" style="border:solid 1px #168A83; width:1050px; height:300px;"></iframe>
                            </td>
<tr>
                            <td height="20px">&nbsp;</td>
                    </tr>
</table>
</tr>
<?php
					}
					?>-->

<tr align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <td  width="100%" valign="top" align="center" colspan="4">
					 <iframe  src="channel9new_ajax.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>" rows="40" cols="60" style="border:solid 1px #168A83; width:1050px; height:300px;"></iframe>
                            </td>
</table>
</tr>
 <tr>
                            <td height="20px">&nbsp;</td>
                    </tr>
			



          </table>          
          
          <div style="width:100%">&nbsp;</div>


          <p class="hr" style="float:left">&nbsp;</p><br />
     <!--   </div>
      </div> -->
    
    </div>
	</center>
  
<?php
	include("footer.php");
?>
