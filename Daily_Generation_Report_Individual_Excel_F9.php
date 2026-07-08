<?php
ini_set('max_execution_time', 3600);
error_reporting(-1);

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

			<?php  }if($FType==4){?>

				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==9){
				if($Cook_Variable[0]!='tirumala') {?>
				<a href='channel9new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
				<?php }
				}if($FType==10){?>

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

$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI,Db_Name from device_register where IMEI='$IMEI'";
if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }

            if($Device_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				  $DGR_IMEI=$Fetch_Result['IMEI'];

				$Device_Name = $Fetch_Result['Device_Name'];

				$Site_Location = $Fetch_Result['Site_Location'];

				$Format_Type = $Fetch_Result['Format_Type'];
				$Closing_Time = $Fetch_Result['Closing_Time'];	
				$Dbname = $Fetch_Result['Db_Name'];				

			}

		}
//echo $Format_Type;
if ($XLS == 1){//xls=1
	?>
 <tr>
							<td class="tab-head-td" colspan="13"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="13"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<tr style="border:0px"><td colspan="6" >&nbsp;</td></tr>
<?php 
		}
			if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr"  colspan="13" align="left">&nbsp;&nbsp;<b>Daily Generation Detail Report</b></td>
					</tr>
					<?php 
					}
					?>
	<?php
           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 10){//if p is set
		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
	$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
				
				if($Device_Query_Result->num_rows >= 1){//record count if
		?>
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<!--<td class="tab-head-td" align="center" width="16px;"><b>Import</b></td>-->
			<td class="tab-head-td" align="center" width="16px;"><b>Export</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Total Hrs</b></td>  
                 	<td class="tab-head-td" align="center" width="16px;"><b>Working Hrs</b></td> 

						<td class="tab-head-td" align="center" width="16px;"><b>WTG Ok Hrs</b></td>
						<td class="tab-head-td" align="center" width="16px;"><b>Grid Hrs</b></td>
				
                    </tr>
						<?php 
							$MI = 1;
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

							
							if($Format_Type== 9){
			$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }
//echo $Gen_Mysql_Query;
            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=6000?$Total_Gen[$DATE_Val]:'0';
								$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'];
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];
								$Gen2[$DATE_Val]=$Gen2[$DATE_Val]>'24' && $Gen2[$DATE_Val]<'50'?'24':$Gen2[$DATE_Val];								
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								$Array_Gen1[$DATE_Val]=$Gen1[$DATE_Val]>0 && $Gen1[$DATE_Val]<=25?$Gen1[$DATE_Val]:'0';
								$Array_Gen2[$DATE_Val]=$Gen2[$DATE_Val]>0 && $Gen2[$DATE_Val]<=25?$Gen2[$DATE_Val]:'0';
								
								/*$Gen_Mysql_Query_fromTable ="select P_Kwh,C_Kwh,Prev_WP3000OK_Timer,G1_P_Kwh, G2_P_Kwh,Gen2_Hours,Gen1_Hours,WP3000OK_Timer,GridOk_Timer from ".$Dbname.".device_data_f9  where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
								
								//echo $Gen_Mysql_Query_fromTable ;
								
								if (!$Gen_Mysql_Query_Result_fromTable = $db->query($Gen_Mysql_Query_fromTable))
								{
									die($db->error);
								}
								$G1KWH = "0";
								$G2KWH = "0";
								$G1HR = "0";
								$G2HR = "0";
								$GRIDOK = "0";$SYSOK = "0";
								if($Gen_Mysql_Query_Result_fromTable->num_rows >= 1)
								{
									while($Fetch_Result_fromTable = $Gen_Mysql_Query_Result_fromTable->fetch_array()) {
										
								$G1KWH = $Fetch_Result_fromTable['G1_P_Kwh'];
								$G2KWH = $Fetch_Result_fromTable['G2_P_Kwh'];
								$G1HR = $Fetch_Result_fromTable['Gen1_Hours'];
								$G2HR = $Fetch_Result_fromTable['Gen2_Hours'];
								$GRIDOK = $Fetch_Result_fromTable['GridOk_Timer'];
								$SYSOK = $Fetch_Result_fromTable['WP3000OK_Timer'];
										
									}
								}	*/
								$Run_Hours[$DATE_Val]=$Run[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								
								
				}
				
			}
			
							}//endif isset

							
						
						}//end foreach

						

						foreach($Date_Array as $DATE_Val){

							?>

                        <tr>

                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              

				<td class="tab-head-td1" align="left"><?=$Device_Name?></td>
				
				<td class="tab-head-td1" align="left"><?=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(15000*($diff+1)))?round($Total_Gen[$DATE_Val],2): '000'?></td>                  
			
                        <td class="tab-head-td1" align="left"><?=($Run_Hours[$DATE_Val] >=0 && $Run_Hours[$DATE_Val] <=24)?round($Run_Hours[$DATE_Val],2) : '000'?></td>      

                        <td class="tab-head-td1" align="left"><?=($Gen1[$DATE_Val] != '' && $Gen1[$DATE_Val] >=0 && $Gen1[$DATE_Val] <=24)?round($Gen1[$DATE_Val],2) : '000'?></td>   
                        <td class="tab-head-td1" align="left"><?=($Gen2[$DATE_Val] != '' && $Gen2[$DATE_Val] >=0 && $Gen2[$DATE_Val]<=24)?round($Gen2[$DATE_Val],2) : '000'?></td>
						 
					 <?php
			
				}
			?>
                        </tr>
						

							<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>
							
							<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($Array_Gen)>0? round(arraySumRecursive($Array_Gen),2):'000' ?></b></td>
							
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Array_Run)>=0) ? round(arraySumRecursive($Array_Run),2):'000'?></b></td>
							
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Array_Gen1)>=0) ? round(arraySumRecursive($Array_Gen1),2):'000'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Array_Gen2)>=0) ? round(arraySumRecursive($Array_Gen2),2):'000'?></b></td>
							
							
	</tr>



					</table>

         <?php 
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