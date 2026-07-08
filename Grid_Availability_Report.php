          <!-- 
            Daily Generation Report
        -->
	<?php


//echo $_REQUEST['FType'] ."is format type";
	if ($XLS == 0){
?>
		<tr>
			<td colspan="5" align="left" style="font-size:small">
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please click the below link to Download the excel Report</b><br /><br />
			<?php if($FType==1 || $FType==6){?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			
			<?php }?>
			</td>
		</tr>
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
$Device_Query="select Device_Name,Format_Type, Connect_Feeder,Site_Location,State,IMEI from device_register where IMEI='$IMEI'";if (!$Device_Query_Result = $db->query($Device_Query))            {                die($db->error);            }            if($Device_Query_Result->num_rows >= 1)            {              while($Fetch_Result = $Device_Query_Result->fetch_array()) {				  $DGR_IMEI=$Fetch_Result['IMEI'];
				$Device_Name = $Fetch_Result['Device_Name'];
				$Site_Location = $Fetch_Result['Site_Location'];
				$Format_Type = $Fetch_Result['Format_Type'];
				
			}
		}
		
//echo $Format_Type;

	if ($XLS == 1){//xls=1



	?>
 <tr>
							<td class="tab-head-td" colspan="10"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Grid Availability Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="10"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<tr style="border:0px"><td colspan="6" >&nbsp;</td></tr>

<?php
}
?>
<?php
if ($XLS == 0){
?>
					<tr style="border:0px"><td colspan="12" >&nbsp;</td></tr>
<?php
}
?>

			
	<?php
           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 24){//if p is set

		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
	
          		//print_r($F1_IMEI);print_r($F2_IMEI);print_r($F3_IMEI);print_r($F4_IMEI);print_r($F5_IMEI);print_r($F6_IMEI);
		

			
//print_r($Format_Type);
//print_r($IMEI_DGR);
				//$Format_Type = array_unique($Format_Type);
//print_r($F1_IMEI);
	
				if($Device_Query_Result->num_rows >= 1) {//record count if
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
			<td class="tab-head-td" align="center" width="16px;"><b>Grid Drop Hrs</b></td>     
                       	<td class="tab-head-td" align="center" width="16px;"><b>GA %</b></td> 
			</tr>
                 			  <?php 

							
								$Closing_Time= "00:00:00";
					?>


						<?php 
							//$DGR_IMEI_Str=implode(",",$DGR_IMEI);
							
							//if(in_array(6,$Format_Type))
							/*$EB_Data_Query="select Record_Index, IMEI,Export_Kwh_6to9,Export_Kwh_18to21,Export_Kwh_21to22,Export_Kwh_22to5,Export_Kwh_5to6_9to18,
Import_Kwh_6to9,Import_Kwh_18to21,Import_Kwh_21to22,Import_Kwh_22to5,Import_Kwh_5to6_9to18,Import_Rkvah,
Export_Rkvah,Export_Kvarh,Import_Kvarh,Import_Rkvah,Remarks, Date from va_victory.eb_meter_reading  where IMEI = ".$DGR_IMEI." and Date between '".date("Y-m-d",strtotime($DGR_Start_Date.' -1 day'))."' and  '".date("Y-m-d",strtotime(  $DGR_End_Date))."' ";
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
							$Tot_Gen0=0;
							$Tot_Gen1_Prod=0;
							$Tot_Gen2=0;
							$Tot_Line_Hours=0;
							$Tot_Total_Hours=0;
							$Tot_Turbine_Hours=0;
							$Tot_Gen1_Hours=0;
							$Tot_Run_Hours=0;
							$Tot_GD_Hours=0;
							$Days=0;  
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){

							
							//echo $DATE_Val;
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							$Date_Type1=date("d-M-y",strtotime($DATE_Val));
							$Date_Type6=date("j-n-y",strtotime($DATE_Val));
							if($Closing_Time=="00:00:00"){
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							$Yester_Type1=$Date_Type1;
							$Yester_Type6=$Date_Type6;
							}
							else{
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							$Yester_Type1=date("d-M-y",strtotime($DATE_Val)+86400);
							$Yester_Type6=date("j-n-y",strtotime($DATE_Val)+86400);
							}
							/*echo $DATE_Val;
							echo $Date_dmy;
echo $Yester_dmy;
echo $Yester_Type1;
echo $Date_Type1;*/



							
							if($Format_Type== 1){
							$Gen_Mysql_Query="select IMEI,Date_S,max(cast(Run_Hours as  unsigned))-min(cast(Run_Hours as unsigned))    as Run,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned)) as Gen1 from va_mtk.device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$GD_Hours[$DATE_Val] =24*3600-(($BD_Hours[$DATE_Val]*3600)+($Lull_Hours[$DATE_Val]*3600)+$Run_Hours[$DATE_Val]*3600);
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$Date[$DATE_Val]=$Fetch_Result['Date_S'];
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
									}//end while
								}
							}//endif isset
							if($Format_Type== 2){
								$Gen_Mysql_Query="select IMEI,Date_S, (max(G1_Hours)-min(G1_Hours)) + (max(G2_Hours)-min(G2_Hours)) as Run from va_mtk.device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
							//	echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];							
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$Date[$DATE_Val]=$Fetch_Result['Date_S'];
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								
								
								
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
							
									}//end while
								}
							}//endif isset
							if($Format_Type== 3){
								$Gen_Mysql_Query="select IMEI,Date_S, (max(Gen1_Hours)-min(Gen1_Hours)) + (max(Gen2_Hours)-min(Gen2_Hours)) as Run from va_mtk.device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
								//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];

								
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$Date[$DATE_Val]=$Fetch_Result['Date_S'];
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
									}//end while
								}
							}//endif isset
							if($Format_Type== 5){
								$Gen_Mysql_Query="select IMEI,Date_S, (max(P20)-min(P20))+(max(P21)-min(G2_Kwh)) as Total_Gen, max(ABS(P22))-min(ABS(P22)) as Import_LCS , (max(P23)-min(P23)) + (max(P24)-min(P24)) as Run from device_data_f4 where IMEI = ".$DGR_IMEI."  and (Date_F= '".$Date_dmy."' OR  Date_F='". $Yester_dmy ."')   and (case when (Date_F='$Date_dmy') then  (cast(Time_F as time))>='$Closing_Time' else cast(Time_F as time)<'$Closing_Time' end) ";
								//echo $Gen_Mysql_Query;			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Total_Gen[$DATE_Val]=$Fetch_Result['Total_Gen'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_LCS'];
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];
								$Date[$DATE_Val]=$Fetch_Result['Date_S'];
								
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
								$Gen_Mysql_Query=" select IMEI,Date_S,max(Run_Hours)-min(Run_Hours) as Run,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned))   as Gen1,max(Total_Hours)-min(Total_Hours) as Total from va_mtk.device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
								//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {					$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
								$Total[$DATE_Val]=$Fetch_Result['Total'];
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$GD_Hours[$DATE_Val] =24*3600-(($BD_Hours[$DATE_Val]*3600)+($Lull_Hours[$DATE_Val]*3600)+$Run_Hours[$DATE_Val]*3600);
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$Date[$DATE_Val]=$Fetch_Result['Date_S'];
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
									}//end while
								}

							}//endif isset
							if(isset($F7_IMEI)){
								
							}//endif isset
							if(isset($F8_IMEI)){
								
							}//endif isset
							if(isset($F9_IMEI)){
								
							}//endif isset
							if($Format_Type== 10){
								$Gen_Mysql_Query=" select IMEI,Date_S, max(Run_Hours)-min(Run_Hours) as Run,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as Gen1 from va_mtk.device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)";
								//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
								$Run[$DATE_Val]=$Fetch_Result['Run'];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');
								$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;
								$GD_Hours[$DATE_Val] =24*3600-(($BD_Hours[$DATE_Val]*3600)+($Lull_Hours[$DATE_Val]*3600)+$Run_Hours[$DATE_Val]*3600);
								$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');
								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;
								$Date[$DATE_Val]=$Fetch_Result['Date_S'];
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
									}//end while
								}
							}//endif isset
						}//end foreach
						
						foreach($Date_Array as $DATE_Val){
							
							$Yesterday=date("d.m.Y",strtotime($DATE_Val)-86400);
													?>
<?php
?>
                        <tr>
                       		<td class="tab-head-td1" align="left"><?=$Date[$DATE_Val] != ''?$Date[$DATE_Val] : '0'?> </td>              
				<td class="tab-head-td1" align="left"><?=$Device_Name?></td>
              			 <td class="tab-head-td1" align="left"><?=($GD_Hours[$DATE_Val] != '' && $GD_Hours[$DATE_Val]<=24 && $GD_Hours[$DATE_Val]>=0)?$GD_Hours[$DATE_Val] : 'Nil'?></td> 
                       <td class="tab-head-td1" align="left"><?=$GA_Percent[$DATE_Val] != ''?round($GA_Percent[$DATE_Val],2) : '0'?></td> 
							
                        </tr>
						<?php
								}

							
						?>
							<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($GD_Hours)?></b></td>
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
	}
//}//xls=1
	?>            </td>	
        </tr>