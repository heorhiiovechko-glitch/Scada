	<?php 		
		$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	
	  	if(isset($Cook_Variable)){
			$Username = $Cook_Variable[0];
			$Account_ID = $Cook_Variable[3];
		}
	// Getting the customer information
	$Fetch_Info1 = "select *  from device_register where Account_ID = ".$Account_ID."";
	$Fetch_Info_Result1 = mysql_query($Fetch_Info1) or die(mysql_error());
	$Fetch_Info_Result_Count1 = mysql_num_rows($Fetch_Info_Result1);
	if($Fetch_Info_Result_Count1>=1){
		while($Fetch_Details_Result1 = mysql_fetch_array($Fetch_Info_Result1)){
			  $WFG_HTSC_No[$Fetch_Details_Result1['IMEI']] = $Fetch_Details_Result1['HTSC_No'];
			  $WFG_Devicename[$Fetch_Details_Result1['IMEI']] = $Fetch_Details_Result1['Device_Name'];
			  $WFG_IMEI[$Fetch_Details_Result1['IMEI']] = $Fetch_Details_Result1['IMEI'];
			  $WFG_KEY[$Fetch_Details_Result1['IMEI']] = $Fetch_Details_Result1['Device_Index'];
		}				
	}
	
	$_REQUEST['inputDate'] = "01-01-".$_REQUEST['inputYear'];
	$_REQUEST['inputDate1'] = "31-12-".$_REQUEST['inputYear'];

	?>
 
 <!-- 
            DGR for PIONEER Report
        -->
	<?php
	if ($XLS == 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			</td>
		</tr>
	<?php
	}
	?>
	
	<?php
	if ($XLS == 1){
	?>
 
  <tr>
            <td width="100%">
        <?php
			if($Mysql_Record_Count >= 1){
        ?>
                   <table width="90%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                    <tr>
                        <td class="tab-head-td" colspan="<?=$Fetch_Info_Result_Count1+2?>"  align="center" style="text-align:center;"><b>WindFarm Generation Detail</b></td>
                    </tr>                           
					<tr>
                        <td class="tab-head-td" width="12px" align="left"><b>Name</b></td>
						<td class="tab-head-td" width="12px" align="left" colspan="<?=$Fetch_Info_Result_Count1?>"><b><? print_r($All_Devicename[1]);?></b></td>
                        <td class="tab-head-td" width="12px" align="left">&nbsp;</td>					
                   </tr>
				    <tr>
						<td class="tab-head-td" align="left" width="100px"><b>Site Location</b></td>
						<td class="tab-head-td" align="left" colspan="<?=$Fetch_Info_Result_Count1?>"><b><? echo $Site_Location[1]; ?></b></td>
                        <td class="tab-head-td" width="12px" align="left">&nbsp;</td>											
                   </tr>
					<tr style="border:0px"><td colspan="<?=$Fetch_Info_Result_Count1+1?>">&nbsp;</td></tr>
                    <tr>
						<td class="tab-head-td" align="left"><b>Date</b></td>
					<?php
						foreach($WFG_HTSC_No as $WFG_HTSC_No_Val){
							echo '<td class="tab-head-td" align="left">'.$WFG_HTSC_No_Val.'</td>';
						}	
						echo '<td class="tab-head-td" align="left"><b>Total</b></td>';
					?>					
                    </tr>
                    <tr>
						<td class="tab-head-td" align="center"><b></b></td>
					<?php
						foreach($WFG_Devicename as $WFG_Devicename_Val){
							echo '<td class="tab-head-td" align="left">'.$WFG_Devicename_Val.'</td>';
						}	
						echo '<td class="tab-head-td" align="left"></td>';
					?>					
                    </tr>
						<?php 	
						
						$MI = 1;
						$Date_Range =  getDaysInBetween($_REQUEST['inputDate'],$_REQUEST['inputDate1']);
						foreach($Date_Range as $Date_Range_Val){
$Date_Range_Val_Final1 = date("Y-m-d",$Date_Range_Val);
							$Current_Epoch =  strtotime(date("d-m-Y H:i:s"));
							if($Date_Range_Val[0] <= $Current_Epoch){
							
								$DRVF = date("dmY",$Date_Range_Val[0]);
								$Query_Month_Val = date("m",$Date_Range_Val[0]);
								$Query_Month_Val_Arr[] = date("m",$Date_Range_Val[0]);
								$All_Date[$DRVF] = date("d.m.Y",$Date_Range_Val[0]);
								$All_Date1[$DRVF] = date("Y-m-d",$Date_Range_Val[0]);
							?>
							<!--<tr>
								<td class="tab-head-td1" align="left"><?=$All_Date[$DRVF]?></td>-->
								<?php
									$IC = 1;
									foreach($WFG_HTSC_No as $Key => $WFG_HTSC_No_Val){
										$IMEI = $Key;
										$Key = $WFG_KEY;	
										// Getting 24 hours data
										$DRVF = date("dmY",$Date_Range_Val[0])."".$IC;
										$Mysql_Query1[$DRVF] = "select DISTINCT(SUBSTRING(Time_F,1,2)) AS Time_24 ,Time_F, Date_F,PAT_Gen2,PAM_Gen2,PATP_Gen2,Total,Line_Ok,Turbine_Ok,Run,Gen1,Record_Index from va_apswind.$Table_Name where IMEI = '".$IMEI."' and Date_F = '".$Date_Range_Val_Final1."' and PAT_Gen2 != '' group by Time_24 order by Time_24 asc";echo $Mysql_Query1[$DRVF];
										$Mysql_Query_Result1[$DRVF] = mysql_query($Mysql_Query1[$DRVF]) or die(mysql_error());   
										$Mysql_Record_Count1[$DRVF] = mysql_num_rows($Mysql_Query_Result1[$DRVF]);
										$MI = 1;
										if($Mysql_Record_Count1[$DRVF]>=1){
											while($Fetch_Result1[$DRVF] = mysql_fetch_array($Mysql_Query_Result1[$DRVF])){
												$Fetch_Result2 = $Fetch_Result1[$DRVF];
												$PAT_Total_24[$DRVF]["k".$Fetch_Result2['Time_24']] = $Fetch_Result2['Record_Index'];
												$All_Date_Arr[$DRVF] = $Fetch_Result2['Date_F'];
												$MI++;
											}
										}


										//echo "24 TIme Output ===> ";print_r($Time_24_Array);
										//echo "<br /><br />";
										//echo "DB Output ===> ";print_r($PAT_Total_24[$DRVF]);
										//echo "<br /><br />";
										$PAT_Total_24_Merge[$DRVF] = array_merge($Time_24_Array,$PAT_Total_24[$DRVF]);
										//echo "Merge Output ===> ";print_r($PAT_Total_24_Merge[$DRVF]);
										//echo "<br /><hr><br />";	
										#
										#	Getting all the data from DB
										#
										$Mysql_Query2[$DRVF] = "select Time_F, Date_F,PAT_Gen2,PAM_Gen2,PATP_Gen2,Total,Line_Ok,Turbine_Ok,Run,Gen1,Record_Index from va_apswind.$Table_Name where IMEI = '".$IMEI."' and Date_F = '".$Date_Range_Val_Final1."' and PAT_Gen2 != '' order by Record_Index asc";// limit 31,18";
echo $Mysql_Query2[$DRVF];
										$Mysql_Query_Result2[$DRVF] = mysql_query($Mysql_Query2[$DRVF]) or die(mysql_error());   
										$Mysql_Record_Count2[$DRVF] = mysql_num_rows($Mysql_Query_Result2[$DRVF]);
										if($Mysql_Record_Count2[$DRVF]>=1){
											$MI = 1;
											while($Fetch_Result_WFG[$DRVF] = mysql_fetch_array($Mysql_Query_Result2[$DRVF])){
												$Fetch_Result3 = $Fetch_Result_WFG[$DRVF];
												$Time_First_Seg = substr($Fetch_Result3['Time_F'],0,2);
												$PAT_Total[$DRVF][$MI] = $Fetch_Result3['PAT_Gen2'];
												$PAT_Total_RI[$DRVF][$MI] = $Fetch_Result3['Record_Index'];
												$PAT_Total_Record_Index[$DRVF][$Fetch_Result3['Record_Index']]  = $MI;
												$MI++;
											}
										}
										$Total_Count_DB[$DRVF] = count($PAT_Total_Record_Index[$DRVF]);
										//echo "Pat Total Output ===> ";print_r($PAT_Total[$DRVF]);
										//echo "<br /><hr><br />";

										//sort($Time_24_Array);
										$MI = 1;
										$PAT_Total_24_Merge_Prev = '';	
										$DRVF = date("dmY",$Date_Range_Val[0])."".$IC;;
										foreach($PAT_Total_24_Merge[$DRVF] as $PAT_Total_24_Merge_Key => $PAT_Total_24_Merge_Val){
										
											#
											#	For records which is not available in Table
											#
											if( $PAT_Total_24_Merge_Val == ''){
												$PAT_Total_24_Merge_Val = $PAT_Total_24_Merge_Prev;
											}
											$PAT_Total_24_Merge_Prev = $PAT_Total_24_Merge_Val;
											if($PAT_Total_24_Merge_Prev == ''){
												$PAT_Total_24_Merge_Prev = $PAT_Total_RI[$DRVF][1];
											}	
											//Only for first value
											if($PAT_Total_24_Merge_Key == 'k00' && $PAT_Total_24_Merge_Val == ''){
												$PAT_Total_24_Merge_Val = $PAT_Total_RI[$DRVF][1];
											}
											#
											#	Previous Value
											#
											$PAT_Total_Previous1[$DRVF] = $PAT_Total_Record_Index[$DRVF][$PAT_Total_24_Merge_Val];
											$PTP = $PAT_Total_Previous1[$DRVF];
											if($PTP == 1)
												$PTP = 1;
											else
												$PTP = $PAT_Total_Previous1[$DRVF] - 1;
											# 24th data calculation	
											if($PAT_Total_24_Merge_Key == 'k24'){
												$PTP = $Total_Count_DB[$DRVF] - 1;
											}
												
											$PAT_Total_Previous[$DRVF] = $PAT_Total[$DRVF][$PTP];
											#
											#	Current Value && Fetching PAT_Gen2
											#
											$PAT_Total_Current1[$DRVF] = $PAT_Total_Record_Index[$DRVF][$PAT_Total_24_Merge_Val];
											# 24th data calculation	
											if($PAT_Total_24_Merge_Key == 'k24'){
												$PAT_Total_Current1[$DRVF] = $Total_Count_DB[$DRVF];
											}
											$PAT_Total_Current[$DRVF] = $PAT_Total[$DRVF][$PAT_Total_Current1[$DRVF]];
											
											#
											#	Next Value && Fetching PAT_Gen2
											#
											$PAT_Total_Next1[$DRVF] = $PAT_Total_Current1[$DRVF] + 1;
											# 24th data calculation	
											if($PAT_Total_24_Merge_Key == 'k24'){
												$PAT_Total_Next1[$DRVF] = $Total_Count_DB[$DRVF];
											}
											$PAT_Total_24_Merge_Key." => ".$PAT_Total_Next[$DRVF] = $PAT_Total[$DRVF][$PAT_Total_Next1[$DRVF]];
											
											#
											#	Combining all the data
											#	
											$PAT_Total_EB_Final[$DRVF][$PAT_Total_24_Merge_Key] = array('Prev' => $PAT_Total_Previous[$DRVF],'Curr' => $PAT_Total_Current[$DRVF],'Next' => $PAT_Total_Next[$DRVF]);
										}

										//echo "Pat EB Total Output ===> ";print_r($PAT_Total_EB_Final[$DRVF]);
										
										//echo "<br /><br />";
										
											// Calculation
											$PT_EB_Fl[$DRVF] = $PAT_Total_EB_Final[$DRVF];
											$PAT_Total_06_09[$DRVF] =  (($PT_EB_Fl[$DRVF]['k09']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k09']['Prev'] : $PT_EB_Fl[$DRVF]['k09']['Curr']) - ($PT_EB_Fl[$DRVF]['k06']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k06']['Prev'] : $PT_EB_Fl[$DRVF]['k06']['Curr']));
											$PAT_Total_09_18[$DRVF] =  (($PT_EB_Fl[$DRVF]['k18']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k18']['Prev'] : $PT_EB_Fl[$DRVF]['k18']['Curr']) - ($PT_EB_Fl[$DRVF]['k09']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k09']['Prev'] : $PT_EB_Fl[$DRVF]['k09']['Curr']));
											$PAT_Total_05_06[$DRVF] =  (($PT_EB_Fl[$DRVF]['k06']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k06']['Prev'] : $PT_EB_Fl[$DRVF]['k06']['Curr']) - ($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']));
											$PAT_Total_18_21[$DRVF] =  (($PT_EB_Fl[$DRVF]['k21']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k21']['Prev'] : $PT_EB_Fl[$DRVF]['k21']['Curr']) - ($PT_EB_Fl[$DRVF]['k18']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k18']['Prev'] : $PT_EB_Fl[$DRVF]['k18']['Curr']));
											$PAT_Total_21_22[$DRVF] =  (($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']) - ($PT_EB_Fl[$DRVF]['k21']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k21']['Prev'] : $PT_EB_Fl[$DRVF]['k21']['Curr']));
											$PAT_Total_22_05_left[$DRVF] =  (($PT_EB_Fl[$DRVF]['k24']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k24']['Prev'] : $PT_EB_Fl[$DRVF]['k24']['Curr']) - ($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']));
											$PAT_Total_22_05_Right[$DRVF] =  (($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']) - ($PT_EB_Fl[$DRVF]['k00']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k00']['Prev'] : $PT_EB_Fl[$DRVF]['k00']['Curr']));
											$PAT_Total_22_05[$DRVF] =  $PAT_Total_22_05_left[$DRVF] + $PAT_Total_22_05_Right[$DRVF];


											//Total(Daily)
											$Total_Daily[$DRVF] = $PAT_Total_06_09[$DRVF] + $PAT_Total_09_18[$DRVF] + $PAT_Total_05_06[$DRVF] +  $PAT_Total_18_21[$DRVF] + $PAT_Total_21_22[$DRVF] + $PAT_Total_22_05[$DRVF];
											
											// Cumulative Value		
											$Total_Daily_CT[$IMEI] += $Total_Daily[$DRVF];
											$Total_Daily_CT_Month[$Query_Month_Val][$IC] += $Total_Daily[$DRVF];
									?>
							<?php		
										//echo '<td class="tab-head-td" align="left">'.round($Total_Daily[$DRVF],1).'&nbsp;</td>';
										$MI++;
										$IC++;
									}
								?>	
							<!--</tr>-->
						<?php
							}
						}//Current Epoch End	
					?>					
					<?php
						$Query_Month_Val_Arr1 = array_unique($Query_Month_Val_Arr);
						foreach($Query_Month_Val_Arr1 as $Query_Month_Val){
							echo "<tr>";
							$IC = 1;
							echo "<td class='tab-head-td' align='left'>".$month1_array[$Query_Month_Val]."-".$_REQUEST['inputYear']."</td>";
							foreach($Total_Daily_CT_Month[$Query_Month_Val] as $Total_Daily_CT_Month_Val){
								echo '<td class="tab-head-td" align="left">'.round($Total_Daily_CT_Month_Val,1).'</td>';
								$IC++;
							}
							$Total_Daily_Right_Final[$DRVF] = array_sum($Total_Daily_CT_Month[$Query_Month_Val]);
							echo '<td class="tab-head-td" align="left">'.round($Total_Daily_Right_Final[$DRVF],1).'&nbsp;</td>';
							echo "</tr>";
						}	
					?>							
  					<?php
						echo "<tr>";
						echo '<td class="tab-head-td" align="left"><b>Total</b></td>';
						//$Total_Daily[$DRVF]
						foreach($WFG_HTSC_No as $Key => $WFG_HTSC_No_Val){
							echo '<td class="tab-head-td" align="left">'.round($Total_Daily_CT[$Key],1).'&nbsp;</td>';
						}	
						echo '<td class="tab-head-td" align="left"></td>';
						echo "</tr>";
					?>		
               </table>
         <?php 
		}
		else{
			echo $No_Records;
		}
	}
		 
         ?>
           </td>
           </tr>           
 