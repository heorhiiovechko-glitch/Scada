<?php
ini_set('max_execution_time', 3600);


//echo $_REQUEST['FType'] ."is format type";

	if ($XLS == 0){

?>

		<tr>

			<td colspan="5" align="center" style="font-size:small">
				<b>&nbsp;&nbsp;&nbsp;&nbsp;Please click the below link to Download the excel Report</b><br /><br />
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

            <td height="5px">&nbsp;</td>

        </tr>

        <tr>

            <td width="100%">

                <table width="100%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">

	<?php

$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where IMEI='$IMEI'";

		$Device_Query_Result = mysql_query($Device_Query) or die(mysql_error());

            	$Device_Query_Result_Count = mysql_num_rows($Device_Query_Result);//echo $Device_Query_Result_Count;

		if($Device_Query_Result_Count>=1){

			while($Fetch_Result = mysql_fetch_array($Device_Query_Result )){

				$DGR_IMEI=$Fetch_Result['IMEI'];

				$Device_Name = $Fetch_Result['Device_Name'];

				$Site_Location = $Fetch_Result['Site_Location'];

				$Format_Type = $Fetch_Result['Format_Type'];
				$Closing_Time = $Fetch_Result['Closing_Time'];

				

			}

		}

		

//echo $Format_Type;



	if ($XLS == 1){//xls=1







	?>

 <tr>

							<td class="tab-head-td" colspan="5"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Daily Generation Hour Detail Report</b></td>

						</tr>

					   <tr>

							<td class="tab-head-td"  colspan="5"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<tr style="border:0px"><td colspan="5" >&nbsp;</td></tr>

<?php 

		}
			if ($XLS == 0){

					?>

					<tr>

						<td  class="tab-head-tr"  colspan="5" align="left">&nbsp;&nbsp;<b>Daily Generation Hour Detail Report</b></td>

					</tr>

					<?php 

					}

					?>

	<?php

           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 50){//if p is set



		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;

		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
	$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
				if($Device_Query_Result_Count >= 1){//record count if

		?>

                    <tr height="50px">
			<td class="tab-head-td" align="center" ><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" ><b>Gen Date</b></td>
			 <td class="tab-head-td"  align="left" ><b>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></td>
				<td class="tab-head-td" align="center" ><b>Import</b></td>
			<td class="tab-head-td" align="center" ><b>Export</b></td>

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
						
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time=="00:00:00" || $Closing_Time=="0" ){
							$Date_St=date("Y-m-d",strtotime($DATE_Val));
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							}
							elseif($Closing_Time>="10:00:00" || $Closing_Time=="10"){
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
							}
							//echo $DATE_Val;

							if($Format_Type== 6){

								$Gen_Mysql_Query="select sec_to_time(time_to_sec(Time_S)- time_to_sec(Time_S)%(60*60)) AS Time_diff,IMEI,Date_S,hour(Time_S) as Time_S,min(PAT_Gen2) as Gen1_Min,max(PAT_Gen2) as Gen1_Max,min(abs(PAT_Gen0)) as Import_Min,max(abs(PAT_Gen0)) as Import_Max from $Cook_Variable[7].device_data_f6 where IMEI = ".$DGR_IMEI."   and (Date_S= '".$Date_Stamp."') group by Date_S,Time_Diff order by Record_Index asc";
								$Gen_Mysql_Query_Result = mysql_query($Gen_Mysql_Query) or die(mysql_error());   

								$Gen_Mysql_Record_Count= mysql_num_rows($Gen_Mysql_Query_Result);//echo $Mysql_Record_Count;

								if($Gen_Mysql_Record_Count>=1){

								while($Fetch_Result = mysql_fetch_array($Gen_Mysql_Query_Result)){
									
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Import_LCS[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Total_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Array_Gen+=$Total_Gen[$DATE_Val];
								$Array_Import+=$Import_LCS[$DATE_Val];
								?>

                        <tr>
							<td class="tab-head-td1" align="left"><?=$Device_Name?></td>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>  
								<td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>                 
              			<td class="tab-head-td1" align="left"><?=($Import_LCS[$DATE_Val]>=0)?round($Import_LCS[$DATE_Val],2): '000'?></td>                  
				<td class="tab-head-td1" align="left"><?=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(16000*($diff+1)))?round($Total_Gen[$DATE_Val],2): '000'?></td>                  

                        </tr>
						<?php
									}//end while

								}


							}//endif isset

							
						}//end foreach

						
						?>

							<td class="tab-head-td1" align="left"><b>Total</b></td>  
							<td class="tab-head-td1" align="left"><b></b></td>	
							<td class="tab-head-td1" align="left"><b></b></td>							
							<td class="tab-head-td1" align="left"><b><?=$Array_Import>0? round($Array_Import,2):'000'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=$Array_Gen>0? round($Array_Gen,2):'000' ?></b></td>
							
				
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