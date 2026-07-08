          <!-- 
            Daily Generation Report
        -->
	<?php


//echo $_REQUEST['FType'] ."is format type";
	if ($XLS == 0){
?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />
			<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==7 || $FType==8){?>
				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			
			<?php }?>
			</td>
		</tr>
<?php
	}
?>					
	
        <tr>
            <td width="1000px">
<table width="1000px" border="<?=$XLS == 1?'1':'0'?>" align="left" cellpadding="1" cellspacing="1" class="innertab1_">	
	<?php



	if ($XLS == 1){//xls=1


	?>

						<tr>
							<td class="tab-head-td" colspan="10"  align="center"><b><? print_r($All_Firstname[1]) ?>   <?print_r($All_Lastname[1])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="10"  align="left"><b>Site:</b><?= implode(",",array_unique($Site_Location)) ?></td>
					
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
          //  if(isset($_REQUEST['p']) && $_REQUEST['p'] == 8){//if p is set

		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
		$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
//echo $Cook_Variable[6];
//echo  $Cook_Variable[3];
		if($Cook_Variable[2] ==3 || $Cook_Variable[2] ==2)

			$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where Parent_ID=" .$Cook_Variable[6] ."  and Format_Type='2' order by Connect_Feeder DESC";
		elseif($Cook_Variable[2] ==4)
			$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where Account_ID=" .$Cook_Variable[3] ." and Format_Type='2' order by Connect_Feeder DESC";
		echo $Device_Query;
if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }
			$Device_Query_Result_Count=$Device_Query_Result->num_rows;
            if($Device_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				$DGR_IMEI[$Fetch_Result['IMEI']]=$Fetch_Result['IMEI'];
				$Device_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
				$Site_Location[$Fetch_Result['IMEI']] = $Fetch_Result['Site_Location'];
				$Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
				$Format_Type[$Fetch_Result['Format_Type']] = $Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']=='1'){
					$F1_IMEI[]=$Fetch_Result['IMEI'];
				}
				if($Fetch_Result['Format_Type']==2)
					$F2_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==3)
					$F3_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==4)
					$F4_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==6)
					$F6_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==7)
					$F7_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==8)
					$F8_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==9)
					$F9_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==10)
					$F10_IMEI[]=$Fetch_Result['IMEI'];
			}
		}
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
			}
*/
			
//print_r($Format_Type);
//print_r($IMEI_DGR);
				$Format_Type = array_unique($Format_Type);
	
				if($Device_Query_Result_Count >= 1){//record count if
		?>
						
						<!-- <tr style="border:0px"><td colspan="6" >&nbsp;</td></tr>
						<tr>
						<td class="tab-head-td" align="center"><b></b></td>
			
						<td class="tab-head-td" align="center"><b></b></td>                                                              
                       				 <td class="tab-head-td" colspan="2" align="center" ><b>LCS</td> 
                        
                       				 <td class="tab-head-td" colspan="10" align="center"><b>EB Meter Data</b></td> 
                        
                       				<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>                               
						<td class="tab-head-td" align="center"><b></b></td>  
						<td class="tab-head-td" align="center"><b></b></td>                                                          
                    </tr> -->			
					
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Export</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Import</b></td>
                        <td class="tab-head-td" align="center" width="16px;"><b>Avg.Windspeed</td> 
                       
			<td class="tab-head-td" align="center" width="16px;"><b>Total Hrs</b></td>  
                     	<td class="tab-head-td" align="center" width="16px;"><b>Run Hrs</b></td>                               
                        <td class="tab-head-td" align="center" width="16px;"><b>GD Hrs</b></td> 
 
<?php
if(isset($F2_IMEI)){
?>
			<td class="tab-head-td" align="center" width="16px;"><b>BD Hrs</b></td> 
<?php
}
?>                                   
                         <td class="tab-head-td" align="center" width="16px;"><b>Lull Hrs</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>GA %</b></td> 
			
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
$Daydiff=24*($diff+1)*count($DGR_IMEI);
 
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){
$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time[$IMEI_Val]=="00:00:00" || $Closing_Time[$IMEI_Val]=="0" ){
							$Date_St=date("Y-m-d",strtotime($DATE_Val));
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							}
							elseif($Closing_Time[$IMEI_Val]>="10:00:00" || $Closing_Time[$IMEI_Val]=="10"){
							$Date_St=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
							//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}
							else{
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Date_St=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							}//echo $DATE_Val;
							

							if(isset($F2_IMEI)){
							$Gen_Mysql_Query="select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F2_IMEI).")  and (Date_S= '".$Date_Stamp."')";
				//echo $Gen_Mysql_Query;
								if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
							$Windspeed[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Windspeed'];
								$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';
								$Total_Gen1[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Gen2[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run_Hours[$Fetch_Result['IMEI']][$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen1[$Fetch_Result['IMEI']][$DATE_Val]+$Gen2[$Fetch_Result['IMEI']][$DATE_Val];
								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=6000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';
								$Run_Hours[$Fetch_Result['IMEI']][$DATE_Val]=$Run_Hours[$Fetch_Result['IMEI']][$DATE_Val]>'24' && $Run_Hours[$Fetch_Result['IMEI']][$DATE_Val]<'50'?'24':$Run_Hours[$Fetch_Result['IMEI']][$DATE_Val];
								$Array_Run[$Fetch_Result['IMEI']][$DATE_Val]=$Run_Hours[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Run_Hours[$Fetch_Result['IMEI']][$DATE_Val]<=25?$Run_Hours[$DATE_Val][$Fetch_Result['IMEI']]:'0';
								

	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI in (".implode(",",$F2_IMEI).")  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI,Error_Type";
//echo $POC_Mysql_Query;
		if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }
                while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {			$Error_Type[$POC_Fetch_Result['IMEI']][$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$POC_Fetch_Result['IMEI']][$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val]=($BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] >=0 && $BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] <=24)?$BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$POC_Fetch_Result['IMEI']][$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val]=($GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] >=0 && $GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] <=24)?$GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] : '0';
}
}
								 }
								}

		$Array_GD[$IMEI_Val][$DATE_Val]=$GD_Hours[$IMEI_Val][$DATE_Val]>0 && $GD_Hours[$IMEI_Val][$DATE_Val]<=25?$GD_Hours[$IMEI_Val][$DATE_Val]:'0';
		$Array_BD[$IMEI_Val][$DATE_Val]=$BD_Hours[$IMEI_Val][$DATE_Val]>0 && $BD_Hours[$IMEI_Val][$DATE_Val]<=25?$BD_Hours[$IMEI_Val][$DATE_Val]:'0';								
								$Lull_Hours[$IMEI_Val][$DATE_Val]= (24 * 3600) - (($Run_Hours[$IMEI_Val][$DATE_Val]* 3600) +$BD_Hours[$IMEI_Val][$DATE_Val] + $GD_Hours[$IMEI_Val][$DATE_Val]);

								$Lull_Hours[$IMEI_Val][$DATE_Val] = Sec2Time($Lull_Hours[$IMEI_Val][$DATE_Val],'m');
								if($Lull_Hours[$IMEI_Val][$DATE_Val]==(-1))
								$Lull_Hours[$IMEI_Val][$DATE_Val]=0;
								$Array_Lull[$IMEI_Val][$DATE_Val]=$Lull_Hours[$IMEI_Val][$DATE_Val]>0 && $Lull_Hours[$IMEI_Val][$DATE_Val]<=25?$Lull_Hours[$IMEI_Val][$DATE_Val]:'0';				
								$MA_Percent[$IMEI_Val][$DATE_Val]=(((24-$GD_Hours[$IMEI_Val][$DATE_Val])-($BD_Hours[$IMEI_Val][$DATE_Val])) / (24 - $GD_Hours[$IMEI_Val][$DATE_Val])) *100;
								$GA_Percent[$IMEI_Val][$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;						

								$Loss_Due_To_GD[$IMEI_Val][$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$IMEI_Val][$DATE_Val]) * $GD_Hours[$IMEI_Val][$DATE_Val];

								$Loss_Due_To_BD[$IMEI_Val][$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$IMEI_Val][$DATE_Val]) * $BD_Hours[$IMEI_Val][$DATE_Val];
								
							}//endif isset
													}//end foreach
						
						foreach($Date_Array as $DATE_Val){
							foreach($DGR_IMEI as $IMEI_Val){
						
							?>
                        <tr>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              
				<td class="tab-head-td1" align="left"><?=$Device_Name[$IMEI_Val]?></td>
              			<td class="tab-head-td1" align="left"><?=($Total_Gen[$IMEI_Val][$DATE_Val] != '' && $Total_Gen[$IMEI_Val][$DATE_Val] >=0)?round($Total_Gen[$IMEI_Val][$DATE_Val],2): '000'?></td>                  

				<td class="tab-head-td1" align="left"><?=($Import_LCS[$IMEI_Val][$DATE_Val]!= '' && $Import_LCS[$IMEI_Val][$DATE_Val] >=0)?round($Import_LCS[$IMEI_Val][$DATE_Val],2) : '000'?></td>                 
              		<td class="tab-head-td1" align="left"><?=($Windspeed[$IMEI_Val][$DATE_Val]!= '' && $Windspeed[$IMEI_Val][$DATE_Val] >=0)?round($Windspeed[$IMEI_Val][$DATE_Val],2) : '000'?></td>  	
			<td class="tab-head-td1" align="left">24</td>  
                     	<td class="tab-head-td1" align="left"><?=($Run_Hours[$IMEI_Val][$DATE_Val] != '' && $Run_Hours[$IMEI_Val][$DATE_Val] >=0 && $Run_Hours[$IMEI_Val][$DATE_Val] <=24)?round($Run_Hours[$IMEI_Val][$DATE_Val],2) : '000'?></td>                               
                        <td class="tab-head-td1" align="left"><?=($GD_Hours[$IMEI_Val][$DATE_Val] != '' && $GD_Hours[$IMEI_Val][$DATE_Val] >=0 && $GD_Hours[$IMEI_Val][$DATE_Val] <=24)?round($GD_Hours[$IMEI_Val][$DATE_Val],2) : '000'?></td> 
<?php
if(isset($F2_IMEI)){
?>			
			<td class="tab-head-td1" align="left"><?=($BD_Hours[$IMEI_Val][$DATE_Val] != '' && $BD_Hours[$IMEI_Val][$DATE_Val] >=0 && $BD_Hours[$IMEI_Val][$DATE_Val] <=24)?round($BD_Hours[$IMEI_Val][$DATE_Val],2) : '000'?></td>                                     
<?php
}
?>
			<td class="tab-head-td1" align="left"><?=($Lull_Hours[$IMEI_Val][$DATE_Val] != '' && $Lull_Hours[$IMEI_Val][$DATE_Val] >=0 && $Lull_Hours[$IMEI_Val][$DATE_Val] <=24)?round($Lull_Hours[$IMEI_Val][$DATE_Val],2) : '000'?></td>   
                        <td class="tab-head-td1" align="left"><?=$GA_Percent[$IMEI_Val][$DATE_Val] != ''?round($GA_Percent[$IMEI_Val][$DATE_Val],2) : '000'?></td>
			
							
                        </tr>
						<?php
								}

							}
						?>
							<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($Array_Gen)>0? round(arraySumRecursive($Array_Gen),2):'000' ?></b></td>
							<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($Array_Import)>0? arraySumRecursive($Array_Import):'000'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($Windspeed) >=0 ? round(arraySumRecursive($Windspeed),2) : '000'?></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Array_Run)>=0) ? arraySumRecursive($Array_Run):'000'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Array_GD)>=0) ? arraySumRecursive($Array_GD):'000'?></b></td>
							<?php
if(isset($F2_IMEI)){
?>	
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Array_BD)>=0) ? arraySumRecursive($Array_BD):'000'?></b></td>
							<?php
}
?>
							<!--<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($BD_Hours)<=$Daydiff && arraySumRecursive($BD_Hours)>=0) ? arraySumRecursive($BD_Hours):'0'?></b></td>-->
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Array_Lull)>=0) ? arraySumRecursive($Array_Lull):'000'?></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
						
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
	//}//xls=1
	?>            </td>	
        </tr>