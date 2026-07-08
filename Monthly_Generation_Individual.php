          <!-- 
            Daily Generation Report
        -->
	<?php


//echo $_REQUEST['FType'] ."is format type";
	if ($XLS == 0){
?>
		<tr>			<td colspan="5" align="center" style="font-size:small">				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->			<?php if($FType==1 || $FType==6){?>				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==2){?>				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==3){?>				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==4){?>				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  } if($FType==7 || $FType==8){?>				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==10){?>				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>						<?php }?>			</td>		</tr>
<?php
	}
?>					
	
       <tr>
            <td height="5px">&nbsp;</td>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">
	<?php
$Device_Query="select Device_Name,Format_Type,Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where IMEI='$IMEI'";if (!$Device_Query_Result = $db->query($Device_Query))            {                die($db->error);            }            if($Device_Query_Result->num_rows >= 1)            {              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				$DGR_IMEI=$Fetch_Result['IMEI'];
				$Device_Name = $Fetch_Result['Device_Name'];
				$Site_Location = $Fetch_Result['Site_Location'];
				$Format_Type = $Fetch_Result['Format_Type'];
				$Closing_Time = $Fetch_Result['Closing_Time'];
				
			}
		}	if ($XLS == 1){//xls=1
	?> <tr>
							<td class="tab-head-td" colspan="10"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="10"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<tr style="border:0px"><td colspan="6" >&nbsp;</td></tr>

<?php 
		}
			if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr"  colspan="29" align="left">&nbsp;&nbsp;<b>Daily Generation Detail Report</b></td>
					</tr>
					<?php 
					}
					?>
	<?php
           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 36){//if p is set

		$DGR_Start_Datemonth=$_REQUEST['inputMonthYear'] ;
		$MonthYearArr=explode("-",$DGR_Start_Datemonth);
	
    	$Month = date('m', strtotime($MonthYearArr[0]));
  
	  	$Year = date('Y', strtotime($MonthYearArr[1]));

	
    	$Total_Days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
		$DGR_Start_Date="01-".$_REQUEST['inputMonthYear'];
//echo $DGR_Start_Date;
		$End_Date=date(t);
		  $DGR_End_Date=$End_Date."-".$_REQUEST['inputMonthYear'];
//echo  $DGR_End_Date;
		$From_D_Epoch = strtotime($DGR_Start_Date);
							$To_D_Epoch = strtotime($DGR_End_Date);
	
          		//print_r($F1_IMEI);print_r($F2_IMEI);print_r($F3_IMEI);print_r($F4_IMEI);print_r($F5_IMEI);print_r($F6_IMEI);
		/*if($Cook_Variable[2] ==3 || $Cook_Variable[2] ==2)
			$EB_Query="select T1.* from EB_CLOSING_TIME T1  ,  USER_MASTER T2 WHERE T1.Account_ID=T2.Account_ID and T2.Parent_ID=" .$Cook_Variable[6] ;
		elseif($Cook_Variable[2] ==4)
			$EB_Query="select * from EB_CLOSING_TIME where Account_ID=" .$Cook_Variable[3];
			$EB_Query_Result = mysql_query($EB_Query) or die(mysql_error());
	           	$EB_Query_Result_Count = mysql_num_rows($EB_Query_Result);echo $EB_Query_Result_Count;

			if($EB_Query_Result_Count>=1){
			while($Fetch_Result = mysql_fetch_array($EB_Query_Result )){
				$Closing_Hour=$Fetch_Result['Closing_Hour'];

			}
			}*/

			
//print_r($Format_Type);
//print_r($IMEI_DGR);
				//$Format_Type = array_unique($Format_Type);
//print_r($F1_IMEI);
	
				if($Device_Query_Result->num_rows >= 1){//record count if
		?>
					
						
						
						
						<!-- <tr>
						<td class="tab-head-td" align="center"><b></b></td>
			
						<td class="tab-head-td" align="center"><b></b></td>                                                              
                       				 <td class="tab-head-td" colspan="2" align="center" ><b>LCS</td> 
                        
                       				
                        
                       				<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						  
 <td class="tab-head-td" colspan="15" align="center"><b>EB Meter Data</b></td>  
<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>  
						<td class="tab-head-td" align="center"><b></b></td>                                                        
<td class="tab-head-td" align="center"><b></b></td>  
						<td class="tab-head-td" align="center"><b></b></td>                                                        
                    </tr>	-->		
					
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Import</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Export</b></td>
<?php
			if($Cook_Variable[3] == 100079 || $Cook_Variable[3] == 100081 || $Cook_Variable[3] == 100082 || $Cook_Variable[3] == 100084 || $Cook_Variable[3] == 100088) {
?>
			 <td class="tab-head-td" align="center" width="16px;"><b>Avg.Windspeed</td> 
<?php
}
?>
                        <!-- <td class="tab-head-td" align="center" width="16px;"><b>Exp C1</td> 
                        <td class="tab-head-td" align="center" width="16px;"><b>Exp C2</b></td>    
                        <td class="tab-head-td" align="center" width="16px;"><b>Exp C3</td> 
                        <td class="tab-head-td" align="center" width="16px;"><b>Exp C4</b></td>  

                        <td class="tab-head-td" align="center" width="16px;"><b>Exp C5</b></td>
                         <td class="tab-head-td" align="center" width="16px;"><b>Imp C1</td> 
                        <td class="tab-head-td" align="center" width="16px;"><b>Imp C2</b></td>    
                        <td class="tab-head-td" align="center" width="16px;"><b>Imp C3</td> 
                        <td class="tab-head-td" align="center" width="16px;"><b>Imp C4</b></td> 
                        <td class="tab-head-td" align="center" width="16px;"><b>Imp C5</b></td>       
                        <td class="tab-head-td" align="center" width="16px;"><b>Imp(Rkvah)</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>Exp(Rkvah) </b></td>   
	                <td class="tab-head-td" align="center" width="16px;"><b>Imp(Kvarh)</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>Exp(Kvarh) </b></td>   
                        <td class="tab-head-td" align="center"  width="16px;"><b>%Kvarh</b></td> -->   
			<td class="tab-head-td" align="center" width="16px;"><b>Total Hrs</b></td>  
                     	<td class="tab-head-td" align="center" width="16px;"><b>Run Hrs</b></td>                               
                        <td class="tab-head-td" align="center" width="16px;"><b>GD Hrs</b></td> 
			<td class="tab-head-td" align="center" width="16px;"><b>BD Hrs</b></td>                                    
                        <td class="tab-head-td" align="center" width="16px;"><b>Lull Hrs</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>GA %</b></td> 
                    </tr>
                 			  <?php 

							/*if($Cook_Variable[2] ==3 || $Cook_Variable[2] ==2)
							$Closing_Time_Query="select Hour(Closing_Hour) as Closing_Hour  from eb_data_closing_time where Parent_ID=" .$Cook_Variable[6];
							elseif($Cook_Variable[2] ==4)
							$Closing_Time_Query="select Hour(Closing_Hour) as Closing_Hour from eb_data_closing_time where  Account_ID=" .$Cook_Variable[3];
							//echo 	$Closing_Time_Query;
							$Closing_Time_Query_Result = mysql_query($Closing_Time_Query) or die(mysql_error());
							$Closing_Time_Record_Count = mysql_num_rows($Closing_Time_Query_Result);
							if($Closing_Time_Record_Count>=1){
								while($Fetch_Result= mysql_fetch_array($Closing_Time_Query_Result)){
									$Closing_Time= $Fetch_Result['Closing_Hour'];
								
								}//endwhile
								
							}else
								$Closing_Time= "00:00:00";*/
					?>


						<?php 
							//$DGR_IMEI_Str=implode(",",$DGR_IMEI);
							//if(in_array(6,$Format_Type))
							/*$EB_Data_Query="select Record_Index, IMEI,Export_Kwh_6to9,Export_Kwh_18to21,Export_Kwh_21to22,Export_Kwh_22to5,Export_Kwh_5to6_9to18,
Import_Kwh_6to9,Import_Kwh_18to21,Import_Kwh_21to22,Import_Kwh_22to5,Import_Kwh_5to6_9to18,Import_Rkvah,
Export_Rkvah,Export_Kvarh,Import_Kvarh,Import_Rkvah,Remarks, Date from va_victory_2015.eb_meter_reading  where IMEI = ".$DGR_IMEI." and Date between '".date("Y-m-d",strtotime($DGR_Start_Date.' -1 day'))."' and  '".date("Y-m-d",strtotime(  $DGR_End_Date))."' ";
					//	echo $EB_Data_Query;
						$EB_Data_Query_Result = mysql_query($EB_Data_Query) or die(mysql_error());
						$EB_Data_Record_Count = mysql_num_rows($EB_Data_Query_Result);
						if($EB_Data_Record_Count>=1){
							while($Fetch_Result= mysql_fetch_array($EB_Data_Query_Result)){
								$EB_Date=date("d.m.Y",strtotime($Fetch_Result['Date']));
								$Export_Kwh_6to9[$EB_Date]= $Fetch_Result['Export_Kwh_6to9'];
								$Export_Kwh_18to21[$EB_Date]= $Fetch_Result['Export_Kwh_18to21'];
								$Export_Kwh_21to22[$EB_Date]= $Fetch_Result['Export_Kwh_21to22'];
								$Export_Kwh_22to5[$EB_Date]= $Fetch_Result['Export_Kwh_22to5'];
								$Export_Kwh_5to6_9to18[$EB_Date]= $Fetch_Result['Export_Kwh_5to6_9to18'];
								$Import_Kwh_6to9[$EB_Date]= $Fetch_Result['Import_Kwh_6to9'];						
								$Import_Kwh_18to21[$EB_Date]= $Fetch_Result['Import_Kwh_18to21'];
								$Import_Kwh_21to22[$EB_Date]= $Fetch_Result['Import_Kwh_21to22'];
								$Import_Kwh_22to5[$EB_Date]= $Fetch_Result['Import_Kwh_22to5'];
								$Import_Kwh_5to6_9to18[$EB_Date]= $Fetch_Result['Import_Kwh_5to6_9to18'];
								$Import_Rkvah[$EB_Date]= $Fetch_Result['Import_Rkvah'];
								$Export_Rkvah[$EB_Date]= $Fetch_Result['Export_Rkvah'];					
								$Import_Kvarh[$EB_Date]= $Fetch_Result['Import_Rkvah'];
								$Export_Kvarh[$EB_Date]= $Fetch_Result['Export_Rkvah'];					
								
								$Remarks[$EB_Date]= $Fetch_Result['Remarks'];
								$Kvarh_Percent[$EB_Date] =round(($Import_Kvarh[$EB_Date] / ($Export_Kwh[$EB_Date]-$Import_Kwh[$EB_Date])) * 100 ,2); 

							}
						}*/
//print_r($Export_Kwh);print_r($Import_Kwh);print_r($Export_Kwh_6to9);

							########EB METER CALCULATION##########
							
							$MI = 1;
						//print_r($DATE_F);
							$Tot_All_Generation=0;
							$Tot_BD_Hours=0;
							$Tot_GD_Hours=0;
							$Tot_Maint_Hours=0;
							$Tot_Lull_Hours=0;
							$Tot_Import_LCS=0;
							$Tot_Run_Hours=0;
							$Days=0; 

$datediff = abs($From_D_Epoch - $To_D_Epoch);
     $diff= floor($datediff/(60*60*24));
$Daydiff=24*($diff+1);
 
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){
						
							//echo $DATE_Val;
							//$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time=="00:00:00" || $Closing_Time=="0" ){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							}
							elseif($Closing_Time>="10:00:00" || $Closing_Time=="10"){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
							//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}
							else{
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							}
							//echo $DATE_Val;
														
							if($Format_Type== 1){
							$Gen_Mysql_Query="select IMEI,Date_S, Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(PAT_GEN0))-min(ABS(PAT_GEN0))  as Gen0 ,max(Run_Hours)-min(Run_Hours) as Run,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned))   as Gen1 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Import_LCS[$DATE_Val]=$Fetch_Result['Gen0'];
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$GD_Hours[$DATE_Val] =24*3600-(($BD_Hours[$DATE_Val]*3600)+($Lull_Hours[$DATE_Val]*3600)+$Run_Hours[$DATE_Val]*3600);
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
							if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
									}//end while
								}
							}//endif isset
							if($Format_Type== 2){
								//$Gen_Mysql_Query="select IMEI,Date_S,(max(G1_Kwh)-min(G1_Kwh))+(max(G2_Kwh)-min(G2_Kwh)) as Total_Gen, max(ABS(Import_Kwh))-min(ABS(Import_Kwh)) as Import_LCS , (max(G1_Hours)-min(G1_Hours)) + (max(G2_Hours)-min(G2_Hours)) as Run from device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_F= '".$Date_dmy."' OR  Date_F='". $Yester_dmy ."')   and (case when (Date_F='$Date_dmy') then  (cast(Time_F as time))>='$Closing_Time' else cast(Time_F as time)<'$Closing_Time' end) ";
							//	echo $Gen_Mysql_Query;
					$Gen_Mysql_Query="select IMEI,Date_S, Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(Import_Kwh))-min(ABS(Import_Kwh))  as Gen0 ,(max(Gen1_Hours)-min(Gen1_Hours)) + (max(Gen2_Hours)-min(Gen2_Hours)) as Run from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
								$Total_Gen[$DATE_Val]=($Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'])+($Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min']);
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen0'];
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
	if($Cook_Variable[3]=='54' || $Cook_Variable[3] == 100079 || $Cook_Variable[3] == 100081 || $Cook_Variable[3] == 100082 || $Cook_Variable[3] == 100084 || $Cook_Variable[3] == 100088 ) {

	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
		if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))            {                die($db->error);            }                while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {
					$Error_Type[$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
}
	# For GD Hours
else if($Error_Type[$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
}

	}//ENDWHILE							

							
}			
								$Lull_Hours[$DATE_Val]= (24) - (($Run_Hours[$DATE_Val]) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);
								
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								
								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
							
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
									}//end while
								}
							}//endif isset
							if($Format_Type== 3){
								$Gen_Mysql_Query="select IMEI,Date_S,ROUND(AVG(Windspeed),2) as WindSpeed, (SELECT Production_Total from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT Production_Total from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT abs(Import_Kwh) from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT abs(Import_Kwh) from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, (max(Gen1_Hours)-min(Gen1_Hours)) + (max(Gen2_Hours)-min(Gen2_Hours)) as Run from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
								//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;

								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$Lull_Hours[$DATE_Val]= (24 * 3600) - (($Run_Hours[$DATE_Val]* 3600) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);
								$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								
								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
									}//end while
								}
							}//endif isset
							if($Format_Type== 5){
								$Gen_Mysql_Query="select IMEI,Date_F, (max(P20)-min(P20))+(max(P21)-min(G2_Kwh)) as Total_Gen, max(ABS(P22))-min(ABS(P22)) as Import_LCS , (max(P23)-min(P23)) + (max(P24)-min(P24)) as Run from device_data_f4 where IMEI = ".$DGR_IMEI."  and (Date_F= '".$Date_dmy."' OR  Date_F='". $Yester_dmy ."')   and (case when (Date_F='$Date_dmy') then  (cast(Time_F as time))>='$Closing_Time' else cast(Time_F as time)<'$Closing_Time' end) ";
								//echo $Gen_Mysql_Query;			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Total_Gen[$DATE_Val]=$Fetch_Result['Total_Gen'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_LCS'];
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];

								
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;

								
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$Lull_Hours[$DATE_Val]= (24 * 3600) - (($Run_Hours[$DATE_Val]* 3600) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val] + $MA_Hours[$DATE_Val]);
								$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								
								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
							
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
									}//end while
								}
							}//endif isset
							if($Format_Type== 6){
								$Gen_Mysql_Query=" select IMEI,Date_S,Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(PAT_GEN0))-min(ABS(PAT_GEN0))  as Gen0 ,max(Run_Hours)-min(Run_Hours) as Run,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned))   as Gen1,max(Total_Hours)-min(Total_Hours) as Total,max(Line_Ok)-min(Line_Ok)   as Line_Ok,max(Turbine_Ok)-min(Turbine_Ok)  as Turbine_Ok from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
								//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Import_LCS[$DATE_Val]=$Fetch_Result['Gen0'];
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$GD_Hours[$DATE_Val] =24*3600-(($BD_Hours[$DATE_Val]*3600)+($Lull_Hours[$DATE_Val]*3600)+$Run_Hours[$DATE_Val]*3600);
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
							if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
									}//end while
								}

							}//endif isset
							if($Format_Type== 7){
					//$Gen_Mysql_Query=" select IMEI,Date_S,Date,ROUND(AVG(Windspeed),2) as WindSpeed,Kwh_Positive,Kwh_Negative,Operate_Hours,Stopped_Hours,Grid_Failure_Hours,Total_Hours from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1";
	$Gen_Mysql_Query=" select IMEI,Date_S,Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT Kwh_Positive from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT Kwh_Positive from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT Kwh_Negative from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT Kwh_Negative from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max,max(Operate_Hours)-min(Operate_Hours) as Run,max(Stopped_Hours)-min(Stopped_Hours) as BD_Hours,max(Grid_Failure_Hours)-min(Grid_Failure_Hours) as GD_Hours from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
							if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								//$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								//$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Run_Hours'];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = $Fetch_Result['BD_Hours'];
								$GD_Hours[$DATE_Val] = $Fetch_Result['GD_Hours'];
								$Lull_Hours[$DATE_Val]= (24) - (($Run_Hours[$DATE_Val]) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								//$MA_Percent[$DATE_Val]=( ( ( (24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) - $Stopped_Hours[$DATE_Val] )/ ((24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) * 100 );
								$GA_Percent[$DATE_Val]=((24 * 3600) - $GD_Hours[$DATE_Val]) /(24*3600) * 100 ;
							if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
									}//end while
								}
							}//endif isset
							if($Format_Type== 8){
					$Gen_Mysql_Query=" select IMEI,Date_S,Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT Kwh_Positive from $Cook_Variable[7].device_data_f8 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT Kwh_Positive from $Cook_Variable[7].device_data_f8 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT Kwh_Negative from $Cook_Variable[7].device_data_f8 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT Kwh_Negative from $Cook_Variable[7].device_data_f8 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max,max(Operate_Hours)-min(Operate_Hours) as Run,max(Stopped_Hours)-min(Stopped_Hours) as BD_Hours,max(Grid_Failure_Hours)-min(Grid_Failure_Hours) as GD_Hours from $Cook_Variable[7].device_data_f8 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
								if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								//$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								//$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Run_Hours'];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = $Fetch_Result['BD_Hours'];
								$GD_Hours[$DATE_Val] = $Fetch_Result['GD_Hours'];
								$Lull_Hours[$DATE_Val]= (24) - (($Run_Hours[$DATE_Val]) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
				$MA_Percent[$DATE_Val]=( ( ( (24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) - $Stopped_Hours[$DATE_Val] )/ ((24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) * 100 );
								$GA_Percent[$DATE_Val]=((24 * 3600) - $GD_Hours[$DATE_Val]) /(24*3600) * 100 ;
							if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
									}//end while
								}
						
							}//endif isset
							if($Format_Type== 10){
								//$Gen_Mysql_Query=" select IMEI,DATE_F,  max(Production_Total)-min(Production_Total) as Total_Gen, max(ABS(Gen0))-min(ABS(Gen0)) as Import_LCS , max(Run_Hours)-min(Run_Hours) as Run,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as Gen1 from device_data_f10 where IMEI = ".$DGR_IMEI."  and (DATE_F= '".$Date_Stamp."' OR  DATE_F='". $Yester_Stamp ."')   and (case when (DATE_F='$Date_Stamp') then  (cast(TIME_F as time))>='$Closing_Time' else cast(TIME_F as time)<'$Closing_Time' end) ";
								//echo $Gen_Mysql_Query;
					$Gen_Mysql_Query="select IMEI,Date_S,ROUND(AVG(Windspeed),2) as WindSpeed, (SELECT Production_Total from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT Production_Total from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT abs(PAT_Gen0) from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT abs(PAT_Gen0) from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max,max(Run_Hours)-min(Run_Hours) as Run,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as Gen1 from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$GD_Hours[$DATE_Val] =24*3600-(($BD_Hours[$DATE_Val]*3600)+($Lull_Hours[$DATE_Val]*3600)+$Run_Hours[$DATE_Val]*3600);
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
							if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
									}//end while
								}
							}//endif isset
						}//end foreach
						
						foreach($Date_Array as $DATE_Val){
							
							$Yesterday=date("d.m.Y",strtotime($DATE_Val)-86400);
							/*if($Export_Kwh_6to9[$DATE_Val])
							$Export_C1[$DATE_Val]=($Export_Kwh_6to9[$DATE_Val]-$Export_Kwh_6to9[$Yesterday])*$EB_IMEI;
							if($Export_Kwh_18to21[$DATE_Val])
							$Export_C2[$DATE_Val]=($Export_Kwh_18to21[$DATE_Val]-$Export_Kwh_18to21[$Yesterday])*$EB_IMEI;
							if($Export_Kwh_21to22[$DATE_Val])
							$Export_C3[$DATE_Val]=($Export_Kwh_21to22[$DATE_Val]-$Export_Kwh_21to22[$Yesterday])*$EB_IMEI;
							if($Export_Kwh_22to5[$DATE_Val])
							$Export_C4[$DATE_Val]=($Export_Kwh_22to5[$DATE_Val]-$Export_Kwh_22to5[$Yesterday])*$EB_IMEI;	
							if($Export_Kwh_5to6_9to18[$DATE_Val])
							$Export_C5[$DATE_Val]=($Export_Kwh_5to6_9to18[$DATE_Val]-$Export_Kwh_5to6_9to18[$Yesterday])*$EB_IMEI;
							if($Import_Kwh_6to9[$DATE_Val])
							$Import_C1[$DATE_Val]=($Import_Kwh_6to9[$DATE_Val]-$Import_Kwh_6to9[$Yesterday])*$EB_IMEI;
							if($Import_Kwh_18to21[$DATE_Val])
							$Import_C2[$DATE_Val]=($Import_Kwh_18to21[$DATE_Val]-$Import_Kwh_18to21[$Yesterday])*$EB_IMEI;
							if($Import_Kwh_21to22[$DATE_Val])
							$Import_C3[$DATE_Val]=($Import_Kwh_21to22[$DATE_Val]-$Import_Kwh_21to22[$Yesterday])*$EB_IMEI;
							if($Import_Kwh_22to5[$DATE_Val])
							$Import_C4[$DATE_Val]=($Import_Kwh_22to5[$DATE_Val]-$Import_Kwh_22to5[$Yesterday])*$EB_IMEI;
							if($Import_Kwh_5to6_9to18[$DATE_Val])
							$Import_C5[$DATE_Val]=($Import_Kwh_5to6_9to18[$DATE_Val]-$Import_Kwh_5to6_9to18[$Yesterday])*$EB_IMEI;
							if($Import_Rkvah[$DATE_Val])
							$Import_Rkvah_Curr[$DATE_Val]=( $Import_Rkvah[$DATE_Val]-$Import_Rkvah[$Yesterday])*$EB_IMEI;
							if($Export_Rkvah[$DATE_Val])
							$Export_Rkvah_Curr[$DATE_Val]= ($Export_Rkvah[$DATE_Val]-$Export_Rkvah[$Yesterday])*$EB_IMEI;
							if($Import_Kvarh[$DATE_Val])
							$Import_Kvarh_Curr[$DATE_Val]= ($Import_Kvarh[$DATE_Val]-$Import_Kvarh[$Yesterday])*$EB_IMEI;
							if($Export_Kvarh[$DATE_Val])
							$Export_Kvarh_Curr[$DATE_Val]= ($Export_Kvarh[$DATE_Val]-$Export_Kvarh[$Yesterday])*$EB_IMEI;*/

							
						?>
<?php
?>
                        <tr>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              
				<td class="tab-head-td1" align="left"><?=$Device_Name?></td>
              			<td class="tab-head-td1" align="left"><?=($Import_LCS[$DATE_Val]!= '' && $Import_LCS[$DATE_Val]>=0)?round($Import_LCS[$DATE_Val],2): 'NIL'?></td>                  

				<td class="tab-head-td1" align="left"><?=($Total_Gen[$DATE_Val]!= '' && $Total_Gen[$DATE_Val] >=0)?round($Total_Gen[$DATE_Val],2) : 'NIL'?></td>                 
				
<?php
			if($Cook_Variable[3] == 100079 || $Cook_Variable[3] == 100081 || $Cook_Variable[3] == 100082 || $Cook_Variable[3] == 100084 || $Cook_Variable[3] == 100088) {
?>
			 <td class="tab-head-td1" align="left"><?=($Windspeed[$DATE_Val]!= '' && $Windspeed[$DATE_Val] >=0)?round($Windspeed[$DATE_Val],2) : 'Nil'?></td>  	
<?php
}
?>
		
              			<!--<td class="tab-head-td1" align="left"><?=($Gen2[$DATE_Val]!= '' && $Gen2[$DATE_Val] >=0)?round($Gen2[$DATE_Val],2) : 'NIL'?></td>              					
                                      
              			 <td class="tab-head-td1" align="left"><?=$BD_Hours != ''?$BD_Hours : '0'?></td>              
                        	<td class="tab-head-td1" align="left"><?=($GD_Hours != '' && $GD_Hours >= 0)?$GD_Hours : '0'?></td>
              			
              			<td class="tab-head-td1" align="left"><?=$Lull_Hours != ''?$Lull_Hours : '0'?></td>
				<td class="tab-head-td1" align="left">24</td>
				<td class="tab-head-td" align="left"><b><?= round($MA_Percent,2) ?></b></td>
				<td class="tab-head-td" align="left"><b><?= round($GA_Percent,2) ?></b></td>


<td class="tab-head-td1" align="left">24</td> --> 
                     	<td class="tab-head-td1" align="left">24</td>                               
                        <td class="tab-head-td1" align="left"><?=($Run_Hours[$DATE_Val] != '' && $Run_Hours[$DATE_Val] >=0 && $Run_Hours[$DATE_Val] <=24)?$Run_Hours[$DATE_Val] : 'Nil'?></td>                               
                        <td class="tab-head-td1" align="left"><?=($GD_Hours[$DATE_Val] != '' && $GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=24)?round($GD_Hours[$DATE_Val],2) : 'Nil'?></td> 
			<td class="tab-head-td1" align="left"><?=($BD_Hours[$DATE_Val] != '' && $BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?round($BD_Hours[$DATE_Val],2) : 'Nil'?></td>                                     
                        <td class="tab-head-td1" align="left"><?=($Lull_Hours[$DATE_Val] != '' && $Lull_Hours[$DATE_Val] >=0 && $Lull_Hours[$DATE_Val] <=24)?round($Lull_Hours[$DATE_Val],2) : 'Nil'?></td>   
                        <td class="tab-head-td1" align="left"><?=$GA_Percent[$DATE_Val] != ''?round($GA_Percent[$DATE_Val],2) : '0'?></td>
							
                        </tr>
						<?php
								}

							
						?>
							<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Import_LCS)>=0)?round(arraySumRecursive($Import_LCS),2):'Nil'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Total_Gen)>=0)?round(arraySumRecursive($Total_Gen),2):'Nil'?></b></td>
<?php
			if($Cook_Variable[3] == 100079 || $Cook_Variable[3] == 100081 || $Cook_Variable[3] == 100082 || $Cook_Variable[3] == 100084 || $Cook_Variable[3] == 100088) {
?>
			<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($Windspeed) >=0 ? round(arraySumRecursive($Windspeed),2) : 'Nil'?></b></td>
<?php
}
?>
					
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Run_Hours)<=$Daydiff && arraySumRecursive($Run_Hours)>=0) ? round(arraySumRecursive($Run_Hours),2):'0'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($GD_Hours)<=$Daydiff && arraySumRecursive($GD_Hours)>=0) ? round(arraySumRecursive($GD_Hours),2):'0'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($BD_Hours)<=$Daydiff && arraySumRecursive($BD_Hours)>=0) ? round(arraySumRecursive($BD_Hours),2):'0'?></b></td>							
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Lull_Hours)<=$Daydiff && arraySumRecursive($Lull_Hours)>=0) ? round(arraySumRecursive($Lull_Hours),2):'0'?></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
							
							<!-- <td class="tab-head-td1" align="left"><b><?=$Tot_Lull_Hours?></b></td>
							<td class="tab-head-td1" align="left"><b><?= 24*$Days ?></b></td>
							<td class="tab-head-td" align="left"><b></b></td>
							<td class="tab-head-td" align="left"><b></b></td>
							<td class="tab-head-td" align="left"><b></b></td> -->
						</tr>

					</table>
         <?php //print_r($Export_C1);
				} // Mysql Record End
				else{
					echo $No_Records;
				}//ifelse end
		//	}//if p is set
         ?>
	<?php
	}//xls=1
	?>            </td>	
        </tr>