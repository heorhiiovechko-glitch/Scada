<!-- 
    Raw Data- Quarter Hourly Production
-->
<?php 
error_reporting(0);

	if ($XLS == 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
			<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  } if($FType==7 || $FType==8){?>
				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  }if($FType==9){
				if($Cook_Variable[0]!='tirumala') {?>
				<a href='channel9new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
				<?php }
				}if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
			<?php } if($FType==11){?>
				<a href='channel11_new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
			<?php } ?>
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
         

		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
		if($Cook_Variable[2] ==3 || $Cook_Variable[2] ==2)
			$Device_Query="select Device_Name,Format_Type, Connect_Feeder,Site_Location,State,IMEI,Closing_Time from device_register where Parent_ID=" .$Cook_Variable[6] ."  order by Device_Order";
		elseif($Cook_Variable[2] ==4)
			$Device_Query="select Device_Name,Format_Type, Connect_Feeder,Site_Location,State,IMEI,Closing_Time from device_register where Account_ID=" .$Account_ID ."  order by Device_Order";
		//echo $Device_Query;
	if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }

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
							$Format_Type = array_unique($Format_Type);
							//print_r($Closing_Time);

	$DGR_IMEI_Str=implode(",",$DGR_IMEI);
$MI = 1;
				
		?>

					<?php 

					if ($XLS == 1){

					?>

					<tr>

						<td class="tab-head-tr" colspan="6"  align="center"><b>15 min Production Report&nbsp;-&nbsp; <?=$IMEI?> </b></td>

					</tr>

					<tr>

						<td class="tab-head-td" align="left" width="25%"><b>Customer</b></td>

						<td class="tab-head-td" align="left" width="25%"><b><? print_r($All_Devicename[1]);?></b></td>                      

						<td class="tab-head-td" align="left" width="25%"><b>WEG No</b></td>

						<td class="tab-head-td" align="left" width="25%"><b><? print_r($All_WEG_No[1]);?></b></td>

					

					</tr>

					<tr>

						<td class="tab-head-td" align="left"><b>Site Location</b></td>

						<td class="tab-head-td" align="left"><b><? print_r($Site_Location[1]);?></b></td>                     

						<td class="tab-head-td" align="left"><b>LOC No</b></td>

						<td class="tab-head-td" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   

						

					</tr>

					<tr>

						<td class="tab-head-td" align="left"><b>DOC</b></td>

						<td class="tab-head-td" align="left"><b><? print_r($DOC[1]);?></b></td>                      

						<td class="tab-head-td" align="left"><b>HTSC No</b></td>

						<td class="tab-head-td" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       

					

					</tr>

					<tr style="border:0px"><td colspan="6" >&nbsp;</td></tr>

					<?php

					}

					?>

 					<?php 

					if ($XLS == 0){

					?>

						<tr>

							<td class="tab-head-tr" colspan="6" align="left">&nbsp;&nbsp;<b>15 min Production Details</b></td>

						</tr>

 					<?php 

					}

					?>
					

        <?php

		if($FType==1)  {
			$Raw_Data_Query="select PAT_Gen0,PAT_Gen1,PAT_Gen2,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI in (".implode(",",$F1_IMEI).") and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by IMEI,Date_S desc, Time_S desc ";
		}
		if($FType==6)  {
			$Raw_Data_Query="select PAT_Gen0,PAT_Gen1,PAT_Gen2,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI in (".implode(",",$F6_IMEI).") and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by IMEI,Date_S desc, Time_S desc ";
		} if($FType==2 || $FType==4) {
			$Raw_Data_Query="select PAT_Gen1,PAT_Gen2,Import_Kwh,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";
		} if($FType==3) {
			$Raw_Data_Query="select PAT_Gen1,PAT_Gen2,Production_Total,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";
		}  if($FType==7 || $FType==8) 
		{
			if($All_Devicename[1]=='AIKI 01' || $All_Devicename[1]=='AIKI 02')
			{
				$Raw_Data_Query="select Active_Total_Gen_Import,Active_Gen2_Import,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_UK."' and  Date_S <= '".$To_UK."') order by Date_S desc, Time_S desc ";
			}
			else
			{
			$Raw_Data_Query="select Kwh_Positive,Kwh_Negative,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_UK."' and  Date_S <= '".$To_UK."') order by Date_S desc, Time_S desc ";
			}
		} if($FType==9) {
			$Raw_Data_Query="select P_Kwh, C_Kwh, P_Kvarh, C_Kvarh, Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";
		} if($FType==10) {
			$Raw_Data_Query="select PAT_Gen0,PAT_Gen1,PAT_Gen2,Production_Total,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";
		}
		
		if($FType==11) {
			$Raw_Data_Query="select tag_gad_kwh as PAT_Gen0,tag_gam_kwh as PAT_Gen1,tag_gay_kwh as PAT_Gen2,tag_prod_kwh as Production_Total,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";
		}
			
            if (!$Mysql_Query_Result = $db->query($Raw_Data_Query))
            {
                die($db->error);
            }



            if($Mysql_Query_Result->num_rows >= 1)
            {        ?>

                    <tr>

                             <td class="tab-head-td" width="70px" align="left"><b>Date&nbsp;&nbsp;&nbsp;</b></td> 
                             <td class="tab-head-td" width="170px" align="left"><b>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></td>
                             <td class="tab-head-td" width="170px" align="left"><b>Production Kwh</b></td>
                    </tr>

                    <?php

                        // $Fetch_Result = $Mysql_Query_Result->fetch_array();
                        $Fetch_Result = $Mysql_Query_Result->fetch_all(MYSQLI_ASSOC);

                        $data = [];

                        foreach ($Fetch_Result as $record) {
							if($record["PAT_Gen2"] && $record["PAT_Gen2"] != 0){
							
								// Combine date and time to create a DateTime object
								$dateTime = new DateTime($record["Date_S"] . " " . $record["Time_S"]);
								
								// Calculate the interval timestamp by rounding the time to the nearest 15 minutes
								$intervalTimestamp = $dateTime->format("Y-m-d H:") . floor($dateTime->format("i") / 15) * 15 . ":00";
								
								// Create a DateTime object for the start interval
								$startIntervalDateTime = new DateTime($intervalTimestamp);
								
								// Calculate the end interval by adding 15 minutes to the start interval
								$endIntervalDateTime = clone $startIntervalDateTime;
								$endIntervalDateTime->modify('+15 minutes');
								
								// Format the interval for the additional key
								$intervalSlot = $startIntervalDateTime->format("H:i") . " To " . $endIntervalDateTime->format("H:i") . " Hrs";
								
								// Add the interval slot to the record
								$record["time"] = $intervalSlot;
								$record["date"] = $record["Date_S"];
								
								// Create the interval if it doesn't exist
								if (!isset($data[$intervalTimestamp])) {
									$data[$intervalTimestamp] = [];
								}
                            
								// Add the record to the corresponding interval
								$data[$intervalTimestamp][] = $record;
							}
                        }
						
						foreach($data as $key => $value) {		
                    ?>

								<tr>								

                                    <td class="tab-head-td1" align="left"><?=$value[0]['date']?></td> 
                                    <td class="tab-head-td1" align="left"><?=$value[0]['time']?></td>
							<?php
								if($FType==1 || $FType==6)  {
									$length = count($value);
									$kwh = $value[0]['PAT_Gen2'] - $value[$length-1]['PAT_Gen2'];
							?>
                                    <td class="tab-head-td1" align="left"><?=$kwh??'0'?></td>
							<?php 
								}  if($FType==2 || $FType==4)  {
							?>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen1']!=''?$Fetch_Result['PAT_Gen1']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen2']!=''?$Fetch_Result['PAT_Gen2']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Import_Kwh']!=''?$Fetch_Result['Import_Kwh']:'0'?></td>    
                            <?php 
								} if($FType==3)  {
							?>      
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen1']!=''?$Fetch_Result['PAT_Gen1']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen2']!=''?$Fetch_Result['PAT_Gen2']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Production_Total']!=''?$Fetch_Result['Production_Total']:'0'?></td>
							<?php 
								} if($FType==7 || $FType==8) 
								{
									if($All_Devicename[1]=='KP Tex2')
										{
							?>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['G2_Connected_Counts']!=''?$Fetch_Result['G2_Connected_Counts']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen_Init_Date']!=''?$Fetch_Result['Gen_Init_Date']:'0'?></td> 
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen_Winding2_Temp']!=''?$Fetch_Result['Gen_Winding2_Temp']:'0'?></td> 
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen_DE_Bearing_Temp']!=''?$Fetch_Result['Gen_DE_Bearing_Temp']:'0'?></td> 
							<?php 
								} 
								else if($All_Devicename[1]=='AIKI 01' || $All_Devicename[1]=='AIKI 02')
										{
							?>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Active_Total_Gen_Import']!=''?$Fetch_Result['Active_Total_Gen_Import']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Active_Gen2_Import']!=''?$Fetch_Result['Active_Gen2_Import']:'0'?></td> 
									
							<?php 
								} 
								else {
									?>	
											<td class="tab-head-td1" align="left"><?=$Fetch_Result['Kwh_Positive']!=''?$Fetch_Result['Kwh_Positive']:'0'?></td>
											<td class="tab-head-td1" align="left"><?=$Fetch_Result['Kwh_Negative']!=''?$Fetch_Result['Kwh_Negative']:'0'?></td> 
									 <?php 
									}
								} 
								if($FType==9)  {
							?>  
									 <td class="tab-head-td1" align="left"><?=$Fetch_Result['P_Kwh']!=''?$Fetch_Result['P_Kwh']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['C_Kwh']!=''?$Fetch_Result['C_Kwh']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['P_Kvarh']!=''?$Fetch_Result['P_Kvarh']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['C_Kvarh']!=''?$Fetch_Result['C_Kvarh']:'0'?></td>
							<?php
								} if($FType==10 || $FType==11)  {
							?>  
									 <td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen0']!=''?$Fetch_Result['PAT_Gen0']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen1']!=''?$Fetch_Result['PAT_Gen1']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen2']!=''?$Fetch_Result['PAT_Gen2']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Production_Total']!=''?$Fetch_Result['Production_Total']:'0'?></td>
							<?php
								}
							?>
								</tr>

                    <?php

							

						}

                    ?>

                </table>

         <?php

			}

			else{

				echo $No_Records;

			}

		

         ?>

			</td>

        </tr>

		

		