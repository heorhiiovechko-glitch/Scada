           <!-- 
            Daily EB Bill Report
        -->
	<?php
	if ($XLS == 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->
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
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
			<?php }?>

			</td>
		</tr>
	<?php
	}
	?>
	
	
 
  <tr>
            <td width="100%">
                   <table  border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                   
<?php
	if ($XLS == 1){
	?>
 <tr>
                        <td class="tab-head-td" colspan="12"    align="center"><b>Daily EB Slot Report-LCS</b></td>
                    </tr>         
                   
					<tr>
                        <td class="tab-head-td"  width="4cm"  width="80px" align="left"><b>Customer</b></td>
						<td class="tab-head-td" width="100px" align="left"><b><? print_r($All_Devicename[1]);?></b></td>
                        <td class="tab-head-td" colspan="9" width="300px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td"  width="100px" align="left"><b>WTG No</b></td>
						<td class="tab-head-td" width="100px" align="left"><b><? print_r($All_WEG_No[1]);?></b></td>

                   </tr>
                    <tr>
						<td class="tab-head-td"   align="left"><b>Site Location</b></td>
						<td class="tab-head-td" align="left"><b><? echo $Site_Location[1]; ?></b></td>
                        <td class="tab-head-td" colspan="9" width="36px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td" align="left"><b>LOC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   

                   </tr>
                    <tr>
						<td class="tab-head-td"   align="left"><b>DOC</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($DOC[1]);?></b></td>
                        <td class="tab-head-td" colspan="9" width="36px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td" align="left"><b>HTSC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       

                   </tr>
				    <tr>
						<td class="tab-head-td"     align="left"><b>Feeder</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($DOC[1]);?></b></td>
                        <td class="tab-head-td" colspan="9" width="36px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td" align="left"><b>Capacity</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($Capacity[1]);?></b></td>       
						
                   </tr>
<?php
}
?>
<?php
if ($XLS == 0){
?>

					<tr style="border:0px"><td colspan="8" >&nbsp;</td></tr>
<?php
}
?>
                   <?php
		
        if(isset($_REQUEST['p']) && $_REQUEST['p'] == 6){
			//if($Mysql_Record_Count >= 1){
//if($Fetch_Info_Result_Count >= 1){        
?>
        
                     <tr>                       
						<td class="tab-head-td"  align="center"  colspan="4"><b></b></td>
						<td class="tab-head-td"  align="center"  colspan="2"><b>&nbsp;&nbsp;LCS</b></td>
						<td class="tab-head-td"  align="center"  colspan="2"><b></b></td>
			
                   </tr>                

                     <tr>
					<td class="tab-head-td" align="left"><b>Date</b></td>

                         <td class="tab-head-td" align="left"><b>06.00-10.00 (C1)</b></td> 

                        <td class="tab-head-td" align="left"><b>18.00-22.00(C2)</b></td>   

						<!--<td class="tab-head-td" align="left"><b>21.00-22.00 (C3)</b></td> -->                            

                        <td class="tab-head-td" align="left"><b>10.00-18.00(C4)</b></td>   

                        <td class="tab-head-td" align="left"><b>5.00 - 6.00( C4)</b></td>                                  

                        <td class="tab-head-td" align="left"><b>0.00 -5.00(C5)</b></td>    
						
 				<td class="tab-head-td" align="left"> <b>22.00-24.00 (C5)</b> </td>
 
                        <td class="tab-head-td" align="left"><b>Total(Daily)</b></td>   

                    <!--    <td class="tab-head-td" align="left"><b>Cumulative</b></td> 

                        <td class="tab-head-td" align="left"><b>Other Peak Hours (C1+C4)</b></td>   

						<td class="tab-head-td" align="left"><b>Peak Hours (C2+C3)</b></td> 

                        <td class="tab-head-td" align="left"><b>Night Hours</b></td>	-->	        
                    </tr>

					 <?php

						foreach($Date_Range as $Date_Range_Val){

							$DRVF = $Date_Range_Val[0];

							//sort($Time_24_Array);

							$MI = 1;

							$PAT_Total_24_Merge_Prev = '';	

							$DRVF = date("dmY",$Date_Range_Val[0]);

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
								$PAT_Total_06_09[$DRVF] =  (($PT_EB_Fl[$DRVF]['k10']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k10']['Prev'] : $PT_EB_Fl[$DRVF]['k10']['Curr']) - ($PT_EB_Fl[$DRVF]['k06']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k06']['Prev'] : $PT_EB_Fl[$DRVF]['k06']['Curr']));//6to10

								$PAT_Total_09_18[$DRVF] =  (($PT_EB_Fl[$DRVF]['k18']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k18']['Prev'] : $PT_EB_Fl[$DRVF]['k18']['Curr']) - ($PT_EB_Fl[$DRVF]['k10']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k10']['Prev'] : $PT_EB_Fl[$DRVF]['k10']['Curr']));//

								$PAT_Total_05_06[$DRVF] =  (($PT_EB_Fl[$DRVF]['k06']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k06']['Prev'] : $PT_EB_Fl[$DRVF]['k06']['Curr']) - ($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']));

								$PAT_Total_18_21[$DRVF] =  (($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']) - ($PT_EB_Fl[$DRVF]['k18']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k18']['Prev'] : $PT_EB_Fl[$DRVF]['k18']['Curr']));//6pmto10pm

								$PAT_Total_21_22[$DRVF] =  (($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']) - ($PT_EB_Fl[$DRVF]['k21']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k21']['Prev'] : $PT_EB_Fl[$DRVF]['k21']['Curr']));

								$PAT_Total_22_05_left[$DRVF] =  (($PT_EB_Fl[$DRVF]['k24']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k24']['Prev'] : $PT_EB_Fl[$DRVF]['k24']['Curr']) - ($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']));

								$PAT_Total_22_05_Right[$DRVF] =  (($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']) - ($PT_EB_Fl[$DRVF]['k00']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k00']['Prev'] : $PT_EB_Fl[$DRVF]['k00']['Curr']));


	$PAT_Total_00_05[$DRVF] =  (($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']) - ($PT_EB_Fl[$DRVF]['k00']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k00']['Prev'] : $PT_EB_Fl[$DRVF]['k00']['Curr']));
	
		$PAT_Total_22_24[$DRVF] =  (($PT_EB_Fl[$DRVF]['k24']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k24']['Prev'] : $PT_EB_Fl[$DRVF]['k24']['Curr']) - ($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']));
	

								//Total(Daily)	06.00-10.00 (C1)	,18.00-22.00(C2)	,10.00-18.00(C4)	
								//,5.00 - 6.00( C4),	0.00 -5.00+22.00-24.00 (C5)
								$Total_Daily[$DRVF] = $PAT_Total_06_09[$DRVF] + $PAT_Total_18_21[$DRVF] + $PAT_Total_09_18[$DRVF]+ $PAT_Total_05_06[$DRVF]+   $PAT_Total_00_05[$DRVF] +   $PAT_Total_22_24[$DRVF];
								
								

								

								

								//Other Peak Hours 

								//$Other_Peak_Hours[$DRVF] = $PAT_Total_09_18[$DRVF] + $PAT_Total_21_22[$DRVF] + $PAT_Total_05_06[$DRVF];
								$Other_Peak_Hours[$DRVF] = $PAT_Total_06_09[$DRVF] + $PAT_Total_09_18[$DRVF] + $PAT_Total_05_06[$DRVF];
								

								//Peak Hours

								//$Peak_Hours[$DRVF] = $PAT_Total_06_09[$DRVF] + $PAT_Total_18_21[$DRVF];
								$Peak_Hours[$DRVF] = $PAT_Total_18_21[$DRVF] + $PAT_Total_21_22[$DRVF];


								//Night Hours

								$Night_Hours[$DRVF] = $PAT_Total_22_05[$DRVF];

								

								// Cumulative Value		

								$PAT_Total_06_09_CT += $PAT_Total_06_09[$DRVF];

								$PAT_Total_09_18_CT += $PAT_Total_09_18[$DRVF];

								$PAT_Total_05_06_CT += $PAT_Total_05_06[$DRVF];

								$PAT_Total_18_21_CT += $PAT_Total_18_21[$DRVF];

								$PAT_Total_21_22_CT += $PAT_Total_21_22[$DRVF];

								$PAT_Total_22_05_CT += $PAT_Total_22_05[$DRVF];

								$Total_Daily_CT += $Total_Daily[$DRVF];

								$Other_Peak_Hours_CT += $Other_Peak_Hours[$DRVF];

								$Peak_Hours_CT += $Peak_Hours[$DRVF];

								$Night_Hours_CT += $PAT_Total_22_05[$DRVF];

								

								

				?>

						<?php

							$MI++;

						} 
						

						?>

						<?php

						$Cum = 0;

						foreach($Date_Range as $Date_Range_Val){

							$DRVF = date("dmY",$Date_Range_Val[0]);

							$Total_Daily_Cumulative += $Total_Daily[$DRVF];

						?>

							<tr>
							<td class="tab-head-td1"     align="left"><?=$All_Date_Arr[$DRVF]?></td>			
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_06_09[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_18_21[$DRVF],1)?></td>
							<!--<td class="tab-head-td1" align="left"><?=round($PAT_Total_21_22[$DRVF],1)?></td>-->
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_09_18[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_05_06[$DRVF],1,1)?></td>
							
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_22_24[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_00_05[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($Total_Daily[$DRVF],1)?></td>
							<!--<td class="tab-head-td1" align="left"><?=round($Total_Daily_Cumulative,1)?></td>
							<td class="tab-head-td1" align="left"><?=round($Other_Peak_Hours[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($Peak_Hours[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($Night_Hours[$DRVF],1)?></td>-->
						</tr>

						<?php

							$Cum++;

						}

						$Total_Daily_Cumulative_Final += $Total_Daily_Cumulative;

						?>

						 <tr>

							<td class="tab-head-td1"   align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_06_09_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_18_21_CT,1)?></b></td>                        
							<!--<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_21_22_CT,1)?></b></td>-->
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_09_18_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_05_06_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_22_24_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_00_05_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($Total_Daily_CT,1)?></b></td>                        
							<!--<td class="tab-head-td1" align="left"><b><?=round($Total_Daily_Cumulative_Final,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($Other_Peak_Hours_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($Peak_Hours_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($Night_Hours_CT,1)?></b></td>-->

						</tr>
 </table>
         <?php 
		/*}
		else{
			echo $No_Records;
		}*/
	}
		 
         ?>
           </td>
           </tr>
           
     
<?php

?>