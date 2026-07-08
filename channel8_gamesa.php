
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
	# Getting Alarm Statu from ERROR_DATA based on IMEI
	/*$ER_Status_Mysql_Query = "select Status,IMEI from $Database_Name.$Error_Table_Name where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";//echo $ER_Mysql_Query;
	$ER_Status_Mysql_Query_Result = mysql_query($ER_Status_Mysql_Query) or die(mysql_error());
	$ER_Status_Mysql_Record_Count = mysql_num_rows($ER_Status_Mysql_Query_Result);
	if($ER_Status_Mysql_Record_Count>=1){
		$ER_Status_Fetch_Result = mysql_fetch_array($ER_Status_Mysql_Query_Result);
		$Error_Log_Status = $ER_Status_Fetch_Result['Status'];	
	}
$ER_Mysql_Query = "(select Status as Log,Date_S,Time_S from $Database_Name.$Table_Name where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1) union (select Status as Log,Date_S,Time_S from $Database_Name.$Error_Table_Name where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1) order by Date_S desc,Time_S desc limit 1";
	$ER_Mysql_Query_Result = mysql_query($ER_Mysql_Query) or die(mysql_error());
	$ER_Mysql_Record_Count = mysql_num_rows($ER_Mysql_Query_Result);
	if($ER_Mysql_Record_Count>=1){
		$ER_Fetch_Result = mysql_fetch_array($ER_Mysql_Query_Result);
		$Status = $ER_Fetch_Result['Log'];	
		$Date_F = $ER_Fetch_Result['Date_S'];
		$Time_F = $ER_Fetch_Result['Time_S'];
		$Device_Epoch_Time = GetTimestamp($Date_F,$Time_F);		
	}*/
	
	# Getting the data from DEVICE_DATA_F2 based on IMEI
	$Mysql_Query = "select * from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";//echo $Mysql_Query;
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

<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
<script src="http://code.jquery.com/jquery-1.10.2.js"></script> 

 <script type="text/javascript">
    setInterval("my_function();",300000);
    function my_function(){
      $('#reload').load('channel8_gamesa.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?> #reload');
    }

  </script>



  <center>
	  <div id="body" class="clear" style="width:1100px;">
    <div class="box">
     <!-- <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>-->
      <div class="content">
         <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <td  width="50%">
              <h2>Energy From VersatileScada Detailed Information!</h2>
              <p>about Status, Temperatures, Electrical, Production Figures</p>
          </td>
         <td  width="50%" align="right"

<a href="channel.php"> 

<img src="images/back_btn.png" height="40px" width="40px" /></a></td>
          </table> 
		<table style="border:0px solid yellowgreen;" cellpadding="0" cellspacing="0" width="100%">
<table style="border:0px solid yellowgreen;" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="100%">
<div id="reload">
<table style="border:0px solid yellowgreen;" cellpadding="0" cellspacing="0" width="100%">

    		<tr>
        		<td width="35%" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <!-- 
                            Status
                        -->
                        <tr>
                            <td width="100%" valign="top">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                      
				    <tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Overview</td>
                                    </tr>
									<?php

										if($Mysql_Query_Result->num_rows >= 1){
									?>
                                   <!-- <tr>
                                        <td width="50%" class="tab-head-td">Customer Name</td>
                                        <td class="tab-head-td1"><?=$All_Devicename[1]?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Project Version</td>
                                        <td class="tab-head-td1"><?=$Project_Version?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">ID Number</td>
                                        <td class="tab-head-td1"><?=$ID_Number?></td>
                                    </tr>-->
                                    <tr>
                                        <td width="50%" class="tab-head-td">GRPM</td>
                                        <td class="tab-head-td1"><?=$GRPM?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">RRPM</td>
                                        <td class="tab-head-td1"><?=$RRPM?></td>
                                    </tr>
                                   
                                    <tr>
                                        <td class="tab-head-td">Wind Speed</td>
                                        <td class="tab-head-td1"><?=$WindSpeed." m/s"?></td>
                                    </tr>
                                  <?php
if($Status=='Run' || $Status=='M/C Running' || $Status=='RUN' || $Status=='OperateG1' || $Status=='OperateG2' || $Status=='Operate_G1' || $Status=='Operate_G2' || $Status=='FreeWheelingG2' || $Status=='FreeWheelingG1' || $Status=='Operate G1' || $Status=='Operate G2' ) {
?>
                                  <tr>
                                        <td class="tab-head-td-new">Status </td>
                                        <td class="tab-head-td1-status" style="background-color:green"><?=$Status?></td>
                                    </tr>
<?php
}
elseif($Status=='Grid Drop' || $Status=='GridDrop') {
?>
                                  <tr>
                                        <td class="tab-head-td-new">Status </td>
                                        <td class="tab-head-td1-status" style="background-color:blue"><?=$Status?></td>
                                    </tr>
<?php
}
else {
?>
                                  <tr>
                                        <td class="tab-head-td-new">Status </td>
                                        <td class="tab-head-td1-status" style="background-color:red"><?=$Status?></td>
                                    </tr>
<?php
}
?>
    <tr>
                                        <td class="tab-head-td">Date</td>
                                        <td class="tab-head-td1"><?=$Date_UK?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Time</td>
                                        <td class="tab-head-td1"><?=$Time_UK?></td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Instant Power</td>
                                        <td class="tab-head-td1"><?=$Active_Power?></td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Pressure</td>
                                        <td class="tab-head-td1"><?=$Reactive_Power?></td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Pitch</td>
                                        <td class="tab-head-td1"><?=$Power_factor?></td>
                                    </tr>
									  <?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?>  
  <!--<tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Forecasting Data</td>
                                    </tr>
                                    <?php

						if($Mysql_Query_Result->num_rows >= 1){
				   ?>
                                    <tr> 
                                        <td width="50%" class="tab-head-td">Avg1 Wind Speed </td>
                                        <td class="tab-head-td1"><?=$Min3_Wind_Speed?> </td>
                                    </tr>
                                     <tr>
                                        <td class="tab-head-td">Avg1 Wind Dir</td>
                                        <td class="tab-head-td1"><?=$Min3_Wind_Dir?> </td>
                                    </tr>
					 <tr>
                                        <td class="tab-head-td">Avg1 Active Power </td>
                                        <td class="tab-head-td1"><?=$Min3_Active_Power ?> </td>
                                    </tr>
<?php
					if($FType==7){
					?>
                                    <tr>
                                        <td width="50%" class="tab-head-td"> </td>
                                        <td class="tab-head-td1"> </td>
                                    </tr>
				
					<?php
					}
					?>

                                   
                                     <?php
                                      }
					else{
						echo $No_Records;
					}
					?>  -->

                                </table>
                            </td>	
                  		</tr>
                     
                         <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
 <tr>
                           <td width="50%">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                     <tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Energy (Current)</td>
                                    </tr>
                                    <?php

						if($Mysql_Query_Result->num_rows >= 1){
				
				   ?>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Current Hour (Kwh) </td>
                                        <td class="tab-head-td1"><?=$Active_Total_Gen_Import?> Kwh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Current Day (Kwh)</td>
                                        <td class="tab-head-td1"><?=$Active_Total_Gen_Export?> Kwh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Current Month (Mwh)</td>
                                        <td class="tab-head-td1"><?=$Reactive_Total_Gen_Import?> Mwh</td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Current Year (Gwh)</td>
                                        <td class="tab-head-td1"><?=$Reactive_Total_Gen_Export?> Gwh</td>
                                    </tr>
                                    
<?php

}
								else{
									echo $No_Records;
								}
					?>  
                                </table>
                            </td>
                    </tr>
                     <tr>
                            <td height="20px">&nbsp;</td>
                    </tr>
                                                          <tr>
                            <td>
                             <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Energy (Previous)</td>
                                    </tr>
                                    <?php

						if($Mysql_Query_Result->num_rows >= 1){
					
				   ?>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Previous Hour (Kwh) </td>
                                        <td class="tab-head-td1"><?=$Active_Gen1_Import?> Kwh</td>
                                    </tr>
                                   <tr>
                                        <td width="50%" class="tab-head-td">Previous Day (Kwh) </td>
                                        <td class="tab-head-td1"><?=$Active_Gen1_Export?> Kwh</td>
                                    </tr>
									<tr>
                                        <td width="50%" class="tab-head-td">Previous Month (Mwh) </td>
                                        <td class="tab-head-td1"><?=$Reactive_Gen1_Import?> Mwh</td>
                                    </tr>
									<!--<tr>
                                        <td width="50%" class="tab-head-td">Previous Year (Mwh) </td>
                                        <td class="tab-head-td1"><?=$Reactive_Gen1_Export?> Mwh</td>
                                    </tr>-->
                                     
                                     <?php
                                      }
				
								else{
									echo $No_Records;
								}
					?>  
                                </table>
                            </td>
                    </tr>
                <tr>
                            <td height="10px">&nbsp;</td>
                    </tr>    


 <tr>
                            <td>
                             <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                   <!-- <tr>
                                        <td colspan="2" class="tab-head-tr-new">&nbsp;&nbsp;GAD Details</td>
                                    </tr>-->
                                    <?php

						if($Mysql_Query_Result->num_rows >= 1){
				   ?>
                                   <!-- <tr>
                                        <td width="50%" class="tab-head-td-new">GAD for Today</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Today > 15000 || $GAD_Today < 0 ? "Nil":$GAD_Today." Kwh" ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td-new">GAD for Yesterday</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Yesterday > 15000 || $GAD_Yesterday < 0 ? "Nil":$GAD_Yesterday." Kwh" ?></td>
                                    </tr>
					 <!--<tr>
                                        <td class="tab-head-td-new">GAD for This Week</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Thisweek > 100000 || $GAD_Thisweek < 0 ? "Nil":$GAD_Thisweek." Kwh" ?> </td>
                                    </tr>
			<tr>
                                        <td class="tab-head-td-new">GAD for Previous Week</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Previousweek > 100000 || $GAD_Previousweek < 0 ? "Nil":$GAD_Previousweek." Kwh" ?> </td>
                                    </tr>-->
<tr>
                                        <td class="tab-head-td-new">GAD for This Month</td>
                                        <td class="tab-head-td1-new"><?=$GAD_Thismonth > 300000 || $GAD_Thismonth < 0 ? "Nil":$GAD_Thismonth." Kwh" ?> </td>
                                    </tr>
									
                                     <?php
                                      }
					else{
						echo $No_Records;
					}
					?>  
                                </table>
                            </td>
                    </tr>
<tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
<tr>
<td>
  </td>
</tr>

                                               
 <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
           <tr>
                            <td>
                             
                                                            </td>
                    </tr>

    		    </tr>
                   
		<!-- <td>
                                                         </td>-->
                    </tr>
                    



                    </table>      
                 </td>
                 <td valign="top">
                 <?php
				 /*******
				 	Right side tab
				 ***/
				 ?>

                        <!-- 
                            Electrical
                        -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                           <td width="50%">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                     <tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Electrical</td>
                                    </tr>
                                    <?php
						if($Mysql_Query_Result->num_rows >= 1){
				?>
 				
                                 <tr>
                                        <td width="50%" class="tab-head-td">Grid Voltage</td>
                                        <td class="tab-head-td1"><?=$L_N_Voltage_R?> V</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Stator Voltage</td>
                                        <td class="tab-head-td1"><?=$L_N_Voltage_Y?> V</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Bus Voltage</td>
                                        <td class="tab-head-td1"><?=$L_N_Voltage_B?> V</td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Grid Active Power</td>
                                        <td class="tab-head-td1"><?=$L_L_Voltage_RY?> Kw</td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Grid Reactive Power</td>
                                        <td class="tab-head-td1"><?=$L_L_Voltage_YB?> Kw</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Stator Active Power</td>
                                        <td class="tab-head-td1"><?=$L_L_Voltage_BR?> Kw</td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Stator Reactive Power</td>
                                        <td class="tab-head-td1"><?=$Rphase_Current?> Kw</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Grid Frequency</td>
                                        <td class="tab-head-td1"><?=$Frequency ?> </td>
                                    </tr>
									
                                  
					 
					<?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?> 
									  <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
						 
<tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Hours</td>
                                    </tr>	
<?php
					if($Mysql_Query_Result->num_rows >= 1){
				?>
		               	    
				    <tr>
                                        <td class="tab-head-td">Total Hours</td>
                                        <td class="tab-head-td1"><?=$Grid_Failure_Hours?> h</td>
                                    </tr> 
				    
				   <tr>
                                        <td class="tab-head-td">No Service Hours</td>
                                        <td class="tab-head-td1"><?=$Stopped_Hours?> h</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Line Ok Hours</td>
                                        <td class="tab-head-td1"><?=$Min3_Wind_Speed?> h</td>
                                    </tr>
									
									<tr>
                                        <td class="tab-head-td">Turbine Ok Hours</td>
                                        <td class="tab-head-td1"><?=$Min3_Wind_Dir?> h</td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Run Hours</td>
                                        <td class="tab-head-td1"><?=$Min3_Active_Power?> h</td>
                                    </tr>
									
									<tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
									
					<?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?> 									
                                <tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Energy Producible (Current)</td>
                                    </tr>	
<?php
					if($Mysql_Query_Result->num_rows >= 1){
				?>
		               	    
				    <tr>
                                        <td width="50%" class="tab-head-td">Current Hour (Kwh) </td>
                                        <td class="tab-head-td1"><?=$Active_Gen2_Import?> Kwh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Current Day (Kwh)</td>
                                        <td class="tab-head-td1"><?=$Active_Gen2_Export?> Kwh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Current Month (Mwh)</td>
                                        <td class="tab-head-td1"><?=$Reactive_Gen2_Import?> Mwh</td>
                                    </tr>
									<tr>
                                        <td class="tab-head-td">Current Year (Gwh)</td>
                                        <td class="tab-head-td1"><?=$Reactive_Gen2_Export?> Gwh</td>
                                    </tr>
                                    
					<?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?> 									
                       
								</table>
                            </td>
			
			<td width="50%">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr>
                                        <td colspan="2" class="tab-head-tr">&nbsp;&nbsp;Temperatures</td>
                                    </tr>
                                    <?php
					if($Mysql_Query_Result->num_rows >= 1){					
					?>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Gear_Oil_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_1?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Gear_Box_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_2?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">SOC_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_3?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">CS_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_4?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Nacelle_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_5?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Atmos_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_6?> &deg;C</td>
                                    </tr>
                                     <tr>
                                        <td width="50%" class="tab-head-td">Hydraulic_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_7?> &deg;C</td>
                                    </tr>
                                   <tr>
                                        <td width="50%" class="tab-head-td">Winding_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_8?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Control_Module_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_9?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Radiator_Top_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_10?> &deg;C</td>
                                    </tr>
									 <tr>
                                        <td width="50%" class="tab-head-td">Radiator_Lower_Temp</td>
                                        <td class="tab-head-td1"><?=$Temp_11?> &deg;C</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Transformer_Phase1_Temp</td>
                                        <td class="tab-head-td1"><?=$Cable_Twist?> &deg;C</td>
                                    </tr>                                   
                                     <tr>
                                        <td width="50%" class="tab-head-td">Transformer_Phase2_Temp</td>
                                        <td class="tab-head-td1"><?=$Tip_Pressure?> &deg;C</td>
                                    </tr>                                   
                                    <tr>
                                        <td width="50%" class="tab-head-td">Transformer_Phase3_Temp</td>
                                        <td class="tab-head-td1"><?=$Kwh_Positive?> &deg;C</td>
                                    </tr>                                   
                                   <tr>
                                        <td width="50%" class="tab-head-td">Busbar_Temp</td>
                                        <td class="tab-head-td1"><?=$Nacelle_Position?> &deg;C</td>
                                    </tr>
									<tr>
                                        <td width="50%" class="tab-head-td">Control_Cabinet_Temp</td>
                                        <td class="tab-head-td1"><?=$Kwh_Negative?> &deg;C</td>
                                    </tr>
									<tr>
                                        <td width="50%" class="tab-head-td">Power_Cabinet_Temp</td>
                                        <td class="tab-head-td1"><?=$Kvar_Positive?> &deg;C</td>
                                    </tr>
									<tr>
                                        <td width="50%" class="tab-head-td">LTK_Cabinet_Temp</td>
                                        <td class="tab-head-td1"><?=$Kvar_Negative?> &deg;C</td>
                                    </tr>
									<tr>
                                        <td width="50%" class="tab-head-td">Gen_Rings_Temp</td>
                                        <td class="tab-head-td1"><?=$Total_Hours?> &deg;C</td>
                                    </tr>

									 <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
									<tr>
                                        <td colspan="2" class="tab-head-tr-new">&nbsp;&nbsp;GAD Details</td>
                                    </tr>
									<tr>
                                        <td width="50%" class="tab-head-td-new">Total Consumption</td>
                                        <td class="tab-head-td1-new"><?=$Temp_6?> Gwh</td>
                                    </tr>
									<tr>
                                        <td width="50%" class="tab-head-td-new">Total Export</td>
                                        <td class="tab-head-td1-new"><?=$Temp_7?> Gwh</td>
                                    </tr>
                       
					<?php

					
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?> 
 
 
                                </table>
                            </td>	
                         </tr>
                        

</tr>
</table>
</td>
</tr>
</table>
</div>
</td>
</tr>
</table>
<tr align="center">

                         	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <td  width="100%" valign="top" align="center" colspan="4">
					 <iframe  src="channel8_ajax_gamesa.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>" rows="40" cols="60" style="border:solid 1px #168A83; width:1050px; height:300px;"></iframe>
                            </td>
</table>
</tr>
 <tr>
                            <td height="20px">&nbsp;</td>
                    </tr>
		<!--	<tr align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%">

<td  width="100%" valign="top" align="center" colspan="4">
					 <iframe  src="Power_Windspeed_chart_Monthly_iframe_pioneer.php?c1=<?=$_REQUEST['c1']?>&Year=<?=date('m-Y')?>&l=<?=$_REQUEST['l']?>" rows="40" cols="60" style="background-color:#f6f6f6; border:solid 1px #168A83; width:1050px; height:300px;"></iframe>
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
					 <iframe  src="Daily_Generation_Report_Individual_Excel_iframe_pioneer.php?c1=<?=$_REQUEST['c1']?>&l=<?=$_REQUEST['l']?>&FType=<?=$_REQUEST['FType']?>" rows="40" cols="60" style="border:solid 1px #168A83; width:1050px; height:300px;"></iframe>
                            </td>
<tr>
                            <td height="20px">&nbsp;</td>
                    </tr>
</table>
</tr>-->



          </table>          
          
          <div style="width:100%">&nbsp;</div>


          <p class="hr" style="float:left">&nbsp;</p><br />
        </div>
      </div>
    
    </div>
	</center>
  
<?php
	include("footer.php");
?>
