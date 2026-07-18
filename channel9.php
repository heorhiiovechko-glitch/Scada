<meta http-equiv="refresh" content="240" />
<?php
	include("header_inner.php");
	if(empty($_COOKIE[$Cook_Name])){
		header('Location: index.php');
		exit;
	}
?>
<?
	$lastRecd = null;
	$IMEI = $_REQUEST['c1'];
	if(isset($_REQUEST['l']))
		$Pocket_Length = $_REQUEST['l'];
	else
		$Pocket_Length = '';
	$IMEI_Decode = base64_decode($IMEI);

	# Getting the data from DEVICE_DATA based on IMEI
	$Mysql_Query = "select * from $Database_Name.device_data_f9 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
	$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
	$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
	if($Mysql_Record_Count>=1){
		while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){  
			$Project_Version = $Fetch_Result['Project_Version'];
			$Model_Name = $Fetch_Result['Model_Name'];
			$Power = $Fetch_Result['PV_Instant_Power'];
			$AC_Output_Voltage = $Fetch_Result['AC_Output_Voltage'];
			$AC_Input_Voltage = $Fetch_Result['AC_Input_Voltage'];
			$Frequency = $Fetch_Result['Frequency'];
			$Daily_Generated_Units = $Fetch_Result['Daily_Generated_Units'];
			$Date = $Fetch_Result['Date'];
			$Time = $Fetch_Result['Time'];
			$Load_Level = $Fetch_Result['Load_Level'];
			$Temperature = $Fetch_Result['Temperature'];
			$PV_Voltage = $Fetch_Result['PV_Voltage'];
			$PV_Charging_Current = $Fetch_Result['PV_Charging_Current'];
			$Battery_Present_Voltage = $Fetch_Result['Battery_Present_Voltage'];
			$Battery_Charged_Level = $Fetch_Result['Battery_Charged_Level'];
			$Rated_Battery_Voltage = $Fetch_Result['Rated_Battery_Voltage'];
			$Rated_Output_Voltage = $Fetch_Result['Rated_Output_Voltage'];
			$Date_F = $Fetch_Result['DATE_F'];
			$Time_F = $Fetch_Result['TIME_F'];

		}
		$lastRecd = str_replace('.','-',$Date_F);	
		}
	$No_Records = '<tr>
		<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
	</tr>';	
?> 
			<?php
			// Getting the customer information
			$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."'";
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


<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 

  <center>
	  <div id="body" class="clear" style="width:1000px;">
    <div class="box">
      <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>
      <div class="content">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <td  width="50%">
          <h2>Energy from <?= $Firstname." ".$Lastname?> Detailed Information!</h2>
          <p>about Status, Temperatures, Electrical, Production Figures</p>
      </td>
     <td  width="50%" align="right"><a href="dashboard.php"><img src="images/back_btn.png" height="40px" width="40px" /></a></td>
      </table> 
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
    		<tr>
        		<td width="50%" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <!-- 
                            Status
                        -->
                        <tr>




                            <td width="100%" valign="top">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                	<tr>
                                        <td class="tab-head-tr" style="font-size:11px;" colspan="2">&nbsp;&nbsp;&nbsp;Logged Time : <?=$lastRecd == ''?' --------No Data--------' : $lastRecd." ".$Time_F?></td>
                                    </tr>
                                    <tr >
                                        <td colspan="2" height="10px">&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="tab-head-tr" style="font-size:18px;">&nbsp;&nbsp;Status</td>
                                    </tr>
									<?php
									if($Mysql_Record_Count >= 1){
									?>
                                    <tr>
                                        <td width="50%" class="tab-head-td" style="font-size:16px;">Customer Name</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$All_Devicename[1]?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td" style="font-size:16px;">Project Version</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Project_Version?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Model</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Model_Name?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Power</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Power?> Watts</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;" >AC Output Voltage</td>
                                        <td class="tab-head-td1" style="font-size:16px;" ><?=$AC_Output_Voltage?> Volts</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">AC Input Voltage</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$AC_Input_Voltage?> Volts</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Frequency</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Frequency?> Hz</td>
                                    </tr>
                                   <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Daily Generated Units </td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Daily_Generated_Units?> Kwh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Date</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Date_F?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Time</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Time_F?></td>
                                    </tr>
  				 <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Load Level</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Load_Level?> %</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Temperature</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Temperature?> <sup>o</sup>c</td>
                                    </tr>
   					<tr>
                                        <td class="tab-head-td" style="font-size:16px;">PV Voltage</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$PV_Voltage?> Volts</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">PV Charging Current</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$PV_Charging_Current?> A</td>
                                    </tr>
   					<tr>
                                        <td class="tab-head-td" style="font-size:16px;">Battery Present Voltage</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Battery_Present_Voltage?> Volts</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Battery_Charged_Level</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Battery_Charged_Level?> %</td>
                                    </tr>
					<tr>
                                        <td class="tab-head-td" style="font-size:16px;">Rated Battery Voltage</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Rated_Battery_Voltage?> Volts</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td" style="font-size:16px;">Rated Output Voltage</td>
                                        <td class="tab-head-td1" style="font-size:16px;"><?=$Rated_Output_Voltage?> Volts</td>
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
                            <td height="10px">&nbsp;</td>
                         </tr>   
                    </table>      
                 </td>
                 <td width="50%" valign="top">
                 <?php
				 /*******
				 	Right side tab
				 ***/
				 ?>
                         
          
          <div style="width:100%">&nbsp;</div>

          <p class="hr" style="float:left">&nbsp;</p><br />
        </div>
      </div>
    
    </div>
	</center>
  
<?php
	include("footer.php");
?>