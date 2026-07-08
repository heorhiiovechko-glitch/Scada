<?php
ini_set('max_execution_time', 3600);


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

			

			<?php }if($FType==7 || $FType==8){?>

				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			

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

$Device_Query="select Device_Name,Format_Type,Closing_Time, HTSC_No,Connect_Feeder,Site_Location,State,IMEI from device_register where IMEI='$IMEI'";

		$Device_Query_Result = mysql_query($Device_Query) or die(mysql_error());

            	$Device_Query_Result_Count = mysql_num_rows($Device_Query_Result);//echo $Device_Query_Result_Count;

		if($Device_Query_Result_Count>=1){

			while($Fetch_Result = mysql_fetch_array($Device_Query_Result )){

				$DGR_IMEI=$Fetch_Result['IMEI'];

				$Device_Name = $Fetch_Result['Device_Name'];

				$Site_Location = $Fetch_Result['Site_Location'];

				$Format_Type = $Fetch_Result['Format_Type'];
				$Closing_Time = $Fetch_Result['Closing_Time'];
				$HTSC = $Fetch_Result['HTSC_No'];

				

			}

		}

		

//echo $Format_Type;



	if ($XLS == 1){//xls=1







	?>

 <tr>

							<td class="tab-head-td" colspan="13"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Daily Generation Detail Report</b></td>

						</tr>
			 <tr>

<td class="tab-head-td"  colspan="6"  align="left"><b>Name:</b><?= $Device_Name ?></td>
<td class="tab-head-td"  colspan="6"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<td class="tab-head-td"  align="left"></td>


					   <tr>

							<td class="tab-head-td"  colspan="13"  align="left"><b>HTSC:</b><?=$HTSC ?></td>
<tr style="border:0px"><td colspan="13" >&nbsp;</td></tr>

<?php 

		}
			if ($XLS == 0){

					?>

					<tr>

						<td  class="tab-head-tr"  colspan="29" align="left">&nbsp;&nbsp;<b>Generation Detail Report</b></td>
					
					</tr>
 <tr>

<td class="tab-head-td"  colspan="6"  align="left"><b>Name:</b><?= $Device_Name ?></td>
<td class="tab-head-td"  colspan="6"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<td class="tab-head-td"  align="left"></td>


					   <tr>

							<td class="tab-head-td"  colspan="13"  align="left"><b>HTSC:</b><?=$HTSC ?></td>
<tr style="border:0px"><td colspan="13" >&nbsp;</td></tr>
					<?php 

					}

					?>

	<?php

           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 52){//if p is set



		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;

		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
	$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
				if($Device_Query_Result_Count >= 1){//record count if

		?>
					
                    <tr height="50px">

			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<!--<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>-->
			<td class="tab-head-td" align="center" width="16px;"><b>Total Exp</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Total Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Gen G Exp</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Gen G Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Gen g Exp</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Gen g Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Import</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>R Power</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>GAD</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>GD Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Lull Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Stop Hrs</b></td>

                    </tr>

                 			 
						<?php 

							
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

							$Gen_Mysql_Query="select IMEI,Date_S, Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(PAT_GEN0))-min(ABS(PAT_GEN0))  as Gen0 ,max(Run_Hours)-min(Run_Hours) as Run,max(Line_Ok)-min(Line_Ok) as Line_Ok,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned))   as Gen1 from $Cook_Variable[7].device_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0'";
//echo $Gen_Mysql_Query;

								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){

								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen0'];

								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								$Total_Gen[$DATE_Val]=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(16000*($diff+1)))?$Total_Gen[$DATE_Val]:'00';
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];

								$Run[$DATE_Val]=$Fetch_Result['Run'];

								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];

								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];

								if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];

								//$BD_Hours[$DATE_Val] = Sec2Time($BD_Hours[$DATE_Val],'m');

								//$BD_Hours[$DATE_Val] = $BD_Hours[$DATE_Val] != '0.0'?$BD_Hours[$DATE_Val] : 0;

								//$GD_Hours[$DATE_Val] =24*3600-(($BD_Hours[$DATE_Val]*3600)+($Lull_Hours[$DATE_Val]*3600)+$Run_Hours[$DATE_Val]*3600);

								//$GD_Hours[$DATE_Val] = Sec2Time($GD_Hours[$DATE_Val],'m');

								$GD_Hours[$DATE_Val] = 24-$Fetch_Result['Line_Ok'];

								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;

								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] >='0'?$GD_Hours[$DATE_Val] : '0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;

							if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 

								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);							
										}//end while

								}

							}//endif isset

							if($Format_Type== 2){

								//$Gen_Mysql_Query="select IMEI,Date_S,(max(G1_Kwh)-min(G1_Kwh))+(max(G2_Kwh)-min(G2_Kwh)) as Total_Gen, max(ABS(Import_Kwh))-min(ABS(Import_Kwh)) as Import_LCS , (max(G1_Hours)-min(G1_Hours)) + (max(G2_Hours)-min(G2_Hours)) as Run from device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_F= '".$Date_dmy."' OR  Date_F='". $Yester_dmy ."')   and (case when (Date_F='$Date_dmy') then  (cast(Time_F as time))>='$Closing_Time' else cast(Time_F as time)<'$Closing_Time' end) ";

							//	echo $Gen_Mysql_Query;
					$Gen_Mysql_Query="select IMEI,Date_S, Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(Import_Kwh))-min(ABS(Import_Kwh))  as Gen0 ,(max(Gen1_Hours)-min(Gen1_Hours)) + (max(Gen2_Hours)-min(Gen2_Hours)) as Run from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!='' ";
//echo $Gen_Mysql_Query;

								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){
								
								$Total_Gen[$DATE_Val]=($Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'])+($Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min']);
								$Total_Gen[$DATE_Val]=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(6000*($diff+1)))?$Total_Gen[$DATE_Val]:'00';
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen0'];

								$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];
							$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]=='25' || $Run_Hours[$DATE_Val]=='26'?'24':$Run_Hours[$DATE_Val];

								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];

	if($Cook_Variable[7]=='va_lucky'  || $Cook_Variable[7] == 'va_mtk' || $Cook_Variable[7] =='va_sunland'  || $Cook_Variable[7] == 'va_sivasakthi' || $Cook_Variable[7] =='va_psg' || $Cook_Variable[7] == 'va_intech' || $Cook_Variable[7] == 'va_nak' || $Cook_Variable[7] == 'va_loyal' || $Cook_Variable[7] =='va_goyalgas') {

	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
		$POC_Mysql_Query_Result = mysql_query($POC_Mysql_Query) or die(mysql_error());
		$POC_Mysql_Record_Count = mysql_num_rows($POC_Mysql_Query_Result);
			while($POC_Fetch_Result = mysql_fetch_array($POC_Mysql_Query_Result)){
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
								$Gen_Query="select IMEI,Date_S,PAT_Gen1,PAT_Gen2,Gen1_Hours,Gen2_Hours,Total_Hours,Import_Kwh,Import_Kvarh from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  order by Record_Index desc limit 1";
								//echo $Gen_Mysql_Query;
								$Gen_Query_Result = mysql_query($Gen_Query) or die(mysql_error());   
								$Gen_Record_Count= mysql_num_rows($Gen_Query_Result);//echo $Mysql_Record_Count;
								if($Gen_Record_Count>=1){
								while($Gen_Result = mysql_fetch_array($Gen_Query_Result)){
								$PAT_Gen1[$DATE_Val]=$Gen_Result['PAT_Gen1'];
								$PAT_Gen2[$DATE_Val]=$Gen_Result['PAT_Gen2'];
								$Gen1H[$DATE_Val]=$Gen_Result['Gen1_Hours'];
								$Gen2H[$DATE_Val]=$Gen_Result['Gen2_Hours'];
								$Imp_Kwh[$DATE_Val]=$Gen_Result['Import_Kwh'];
								$Imp_Kvarh[$DATE_Val]=$Gen_Result['Import_Kvarh'];
								$TotalH[$DATE_Val]=$Gen_Result['Total_Hours'];
								}
								}
								$Gen_Mysql_Query="select IMEI,Date_S,(SELECT Production_Total from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT Production_Total from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT abs(Import_Kwh) from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT abs(Import_Kwh) from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, (max(Gen1_Hours)-min(Gen1_Hours)) + (max(Gen2_Hours)-min(Gen2_Hours)) as Run from $Cook_Variable[7].device_data_f3 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!='' ";

								//echo $Gen_Mysql_Query;

								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){
								$Gen1_Prod[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max'];
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
							$Total_Gen[$DATE_Val]=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(18000*($diff+1)))?$Total_Gen[$DATE_Val]:'00';
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];

								$Run_Hours[$DATE_Val]=$Fetch_Result['Run'];

							$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]>'24' && $Run_Hours[$DATE_Val]<'50'?'24':$Run_Hours[$DATE_Val];
					$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
if($Cook_Variable[7]=='va_nagammalmills') {

	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
		$POC_Mysql_Query_Result = mysql_query($POC_Mysql_Query) or die(mysql_error());
		$POC_Mysql_Record_Count = mysql_num_rows($POC_Mysql_Query_Result);
			while($POC_Fetch_Result = mysql_fetch_array($POC_Mysql_Query_Result)){
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

								$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');

								if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

							
									}//end while

								}

							}//endif isset

							if($Format_Type== 5){

								$Gen_Mysql_Query="select IMEI,Date_F, (max(P20)-min(P20))+(max(P21)-min(G2_Kwh)) as Total_Gen, max(ABS(P22))-min(ABS(P22)) as Import_LCS , (max(P23)-min(P23)) + (max(P24)-min(P24)) as Run from device_data_f4 where IMEI = ".$DGR_IMEI."  and (Date_F= '".$Date_dmy."' OR  Date_F='". $Yester_dmy ."')   and (case when (Date_F='$Date_dmy') then  (cast(Time_F as time))>='$Closing_Time' else cast(Time_F as time)<'$Closing_Time' end) ";

								//echo $Gen_Mysql_Query;			

								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){

								$Total_Gen[$DATE_Val]=$Fetch_Result['Total_Gen'];

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

								$Gen_Mysql_Query=" select IMEI,Date_S,Date,ROUND(AVG(Windspeed),2) as WindSpeed,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT PAT_Gen1 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(PAT_GEN0))-min(ABS(PAT_GEN0))  as Gen0 ,max(Run_Hours)-min(Run_Hours) as Run,max(Line_Ok)-min(Line_Ok) as Line_Ok,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned))   as Gen1,max(Total_Hours)-min(Total_Hours) as Total,max(Line_Ok)-min(Line_Ok)   as Line_Ok,max(Turbine_Ok)-min(Turbine_Ok)  as Turbine_Ok from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!='' ";

								//echo $Gen_Mysql_Query;

								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){

								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen0'];

								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								$Total_Gen[$DATE_Val]=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(16000*($diff+1)))?$Total_Gen[$DATE_Val]:'00';
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];

								$Run[$DATE_Val]=$Fetch_Result['Run'];

								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
						$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];

								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];

								if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];

								$GD_Hours[$DATE_Val] = 24-$Fetch_Result['Line_Ok'];

								$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;

								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] >='0'?$GD_Hours[$DATE_Val] : '0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;

							if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 

								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);							
									}//end while

								}


							}//endif isset

							if($Format_Type== 7){

					$Gen_Mysql_Query=" select IMEI,Date_S,Date,Kwh_Positive,Kwh_Negative,Operate_Hours,Stopped_Hours,Grid_Failure_Hours,Total_Hours from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1";

								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){

								$Total_Gen[$DATE_Val]=$Fetch_Result['Kwh_Positive'];

								$Import_LCS[$DATE_Val]=$Fetch_Result['Kwh_Negative'];

								//$Run[$DATE_Val]=$Fetch_Result['Run'];

								$Gen1[$DATE_Val]=$Fetch_Result['Operate_Hours'];
				$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];
								//$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];

								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];

								if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];

								$BD_Hours[$DATE_Val] = $Fetch_Result['Stopped_Hours'];

								$GD_Hours[$DATE_Val] = $Fetch_Result['Grid_Failure_Hours'];

								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;

								$MA_Percent[$DATE_Val]=( ( ( (24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) - $Stopped_Hours[$DATE_Val] )/ ((24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) * 100 );

								$GA_Percent[$DATE_Val]=((24 * 3600) - $GD_Hours[$DATE_Val]) /(24*3600) * 100 ;

							if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];

									}//end while

								}
					$Gen_Mysql_Query_Windspeed=" select IMEI,Date_S,Date,ROUND(AVG(Windspeed),2) as WindSpeed from $Cook_Variable[7].device_data_f7 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1";

					//echo $Gen_Mysql_Query_Windspeed;
					 			$Gen_Mysql_Query_Result_Windspeed = mysql_query($Gen_Mysql_Query_Windspeed) or die(mysql_error());   

								$Gen_Mysql_Record_Count_Windspeed= mysql_num_rows($Gen_Mysql_Query_Result_Windspeed);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count_Windspeed>=1){

								 while($Fetch_Result_Windspeed = mysql_fetch_array($Gen_Mysql_Query_Result_Windspeed)){

								$Windspeed[$DATE_Val]=$Fetch_Result_Windspeed['WindSpeed'];
									}
								}

					

							}//endif isset

							if($Format_Type== 8){

					$Gen_Mysql_Query=" select IMEI,Date_S,Date,Kwh_Positive,Kwh_Negative,Operate_Hours,Stopped_Hours,Grid_failure_Hours,Total_Hours from $Cook_Variable[7].device_data_f8 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1";

					//echo $Gen_Mysql_Query;
					 			$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){

								$Total_Gen[$DATE_Val]=$Fetch_Result['Kwh_Positive'];

								$Import_LCS[$DATE_Val]=$Fetch_Result['Kwh_Negative'];

								$Run[$DATE_Val]=$Fetch_Result['Run'];

								$Gen1[$DATE_Val]=$Fetch_Result['Operate_Hours'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];
								

								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];

								if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];

								$BD_Hours[$DATE_Val] = $Fetch_Result['Stopped_Hours'];

								$GD_Hours[$DATE_Val] = $Fetch_Result['Grid_failure_Hours'];

								$GD_Hours[$DATE_Val] = $GD_Hours[$DATE_Val] != '0.0'?$GD_Hours[$DATE_Val] : 0;

								$MA_Percent[$DATE_Val]=( ( ( (24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) - $Stopped_Hours[$DATE_Val] )/ ((24 * 3600) - $Grid_Failure_Hours[$DATE_Val]) * 100 );

								$GA_Percent[$DATE_Val]=((24 * 3600) - $GD_Hours[$DATE_Val]) /(24*3600) * 100 ;

							if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;

								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];

									}//end while

								}
					$Gen_Mysql_Query_Windspeed=" select IMEI,Date_S,Date,ROUND(AVG(Windspeed),2) as WindSpeed from $Cook_Variable[7].device_data_f8 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end)  and ID_Number!='' ORDER BY Record_Index DESC LIMIT 1";

					//echo $Gen_Mysql_Query_Windspeed;
					 			$Gen_Mysql_Query_Result_Windspeed = mysql_query($Gen_Mysql_Query_Windspeed) or die(mysql_error());   

								$Gen_Mysql_Record_Count_Windspeed= mysql_num_rows($Gen_Mysql_Query_Result_Windspeed);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count_Windspeed>=1){

								 while($Fetch_Result_Windspeed = mysql_fetch_array($Gen_Mysql_Query_Result_Windspeed)){

								$Windspeed[$DATE_Val]=$Fetch_Result_Windspeed['WindSpeed'];
									}
								}

					

						

							}//endif isset

							if($Format_Type== 10){

								//$Gen_Mysql_Query=" select IMEI,DATE_F,  max(Production_Total)-min(Production_Total) as Total_Gen, max(ABS(Gen0))-min(ABS(Gen0)) as Import_LCS , max(Run_Hours)-min(Run_Hours) as Run,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as Gen1 from device_data_f10 where IMEI = ".$DGR_IMEI."  and (DATE_F= '".$Date_Stamp."' OR  DATE_F='". $Yester_Stamp ."')   and (case when (DATE_F='$Date_Stamp') then  (cast(TIME_F as time))>='$Closing_Time' else cast(TIME_F as time)<'$Closing_Time' end) ";

								//echo $Gen_Mysql_Query;

					$Gen_Mysql_Query="select IMEI,Date_S,ROUND(AVG(Windspeed),2) as WindSpeed, (SELECT Production_Total from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT Production_Total from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max,(SELECT abs(PAT_Gen0) from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT abs(PAT_Gen0) from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0' ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max,max(Run_Hours)-min(Run_Hours) as Run,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as Gen1 from $Cook_Variable[7].device_data_f10 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and PAT_Gen2 > '0'";

								

								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								 while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){

								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
								$Total_Gen[$DATE_Val]=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(6000*($diff+1)))?$Total_Gen[$DATE_Val]:'00';
								$Import_LCS[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];

								$Run[$DATE_Val]=$Fetch_Result['Run'];

								$Gen1[$DATE_Val]=$Fetch_Result['Gen1'];
				$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];
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

				<!--<td class="tab-head-td1" align="left"><?=$Device_Name?></td>-->
				<td class="tab-head-td1" align="left"><?=($Gen1_Prod[$DATE_Val]>=0)?round($Gen1_Prod[$DATE_Val],2): 'NIL'?></td>  
               <td class="tab-head-td1" align="left"><?=($TotalH[$DATE_Val]>=0)?round($TotalH[$DATE_Val],2): 'NIL'?></td>  				
			   <td class="tab-head-td1" align="left"><?=($PAT_Gen1[$DATE_Val]>=0)?round($PAT_Gen1[$DATE_Val],2): 'NIL'?></td> 
				<td class="tab-head-td1" align="left"><?=($Gen1H[$DATE_Val]>=0)?round($Gen1H[$DATE_Val],2): 'NIL'?></td> 
				<td class="tab-head-td1" align="left"><?=($PAT_Gen2[$DATE_Val]>=0)?round($PAT_Gen2[$DATE_Val],2): 'NIL'?></td>  
				<td class="tab-head-td1" align="left"><?=($Gen2H[$DATE_Val]>=0)?round($Gen2H[$DATE_Val],2): 'NIL'?></td> 
              	<td class="tab-head-td1" align="left"><?=round($Imp_Kwh[$DATE_Val],2)?></td>    
				<td class="tab-head-td1" align="left"><?=round($Imp_Kvarh[$DATE_Val],2)?></td>        
<?php
if($Format_Type=='2' || $Format_Type=='8' || $Format_Type=='10') {
?>
              			<td class="tab-head-td1" align="left"><?=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(6000*($diff+1)))?round($Total_Gen[$DATE_Val],2): 'Nil'?></td>                  
<?php
} elseif($Format_Type=='3' || $Format_Type=='7' || $Format_Type=='4') {
?>
              			<td class="tab-head-td1" align="left"><?=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(18000*($diff+1)))?round($Total_Gen[$DATE_Val],2): 'Nil'?></td>                  
<?php
} else {  
?>
				<td class="tab-head-td1" align="left"><?=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(16000*($diff+1)))?round($Total_Gen[$DATE_Val],2): 'Nil'?></td>                  
<?php
}
?>
			<td class="tab-head-td1" align="left"><?=($GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=24)?round($GD_Hours[$DATE_Val],2) : 'Nil'?></td> 
			 <td class="tab-head-td1" align="left"><?=($Lull_Hours[$DATE_Val] >=0 && $Lull_Hours[$DATE_Val] <=24)?round($Lull_Hours[$DATE_Val],2) : 'Nil'?></td>   
			 <td class="tab-head-td1" align="left"><?=($BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?round($BD_Hours[$DATE_Val],2) : 'Nil'?></td>                                     
                       	 </tr>
						 
						<?php

								}



							

						?>



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