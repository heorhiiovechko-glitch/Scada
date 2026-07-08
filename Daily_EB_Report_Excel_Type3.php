           <!-- 
            Daily EB Bill Report
        -->
		<style>
/* ===== PROFESSIONAL REPORT STYLE ===== */

.report-box{
    width:95%;
    margin:20px auto;
    background:#fff;
    font-family:Segoe UI, Arial, sans-serif;
    padding:20px;
    border-radius:8px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.report-title{
    text-align:center;
    font-size:20px;
    font-weight:600;
    margin-bottom:15px;
    color:#2c3e50;
}

.download-btn{
    display:inline-block;
    padding:7px 15px;
    background:#1f6ed4;
    color:#fff;
    border-radius:4px;
    text-decoration:none;
    font-size:14px;
}

.download-btn:hover{
    background:#154fa3;
}

.report-table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

.report-table th,
.report-table td{
    border:1px solid #dcdcdc;
    padding:6px 8px;
    text-align:center;
}

.report-table th{
    background:#f2f4f7;
    font-weight:600;
}

.report-table tr:nth-child(even){
    background:#fafafa;
}

.info-row td{
    border:none!important;
    text-align:left;
    padding:3px 5px;
}

.total-row{
    background:#e8f0ff;
    font-weight:bold;
}
/* Clean High-Visibility Download Button */

.download-btn{
    display:inline-block;
    padding:10px 22px;
    background:#0d6efd;        /* Strong Blue */
    color:#ffffff !important; /* Force White Text */
    font-size:14px;
    font-weight:600;
    border-radius:5px;
    text-decoration:none !important;
    border:1px solid #0b5ed7;
    box-shadow:0 2px 4px rgba(0,0,0,0.15);
    transition:all 0.2s ease-in-out;
}

/* Hover Effect */
.download-btn:hover{
    background:#084298;
    border-color:#084298;
    color:#ffffff !important;
    box-shadow:0 3px 6px rgba(0,0,0,0.2);
}

/* Active Click */
.download-btn:active{
    transform:scale(0.97);
}
</style>
	<?php
	if ($XLS == 0){
	?>
		<tr>
		<div class="report-box">

<div class="report-title">
    Daily EB Slot Report
</div>
			<td colspan="5" align="center" style="font-size:small">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->
			<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel</a>
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel</a>
			<?php  } if($FType==7 || $FType==8){?>
				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel</a>
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel</a>
			
			<?php }?>

			</td>
		</tr>
	<?php
	}
	?>
	
	
 
  <tr>
            <td width="100%">
                   <table width="100%"
       border="<?=$XLS == 1?"1":"0"?>"
       align="center"
       cellpadding="1"
       cellspacing="0"
       class="innertab1 report-table">
                  
<?php
	if ($XLS == 1){
	?>
  <tr>
                        <td class="tab-head-td" colspan="12"  align="center"><b>Daily EB Tariff Report for the IMEI</b></td>
                    </tr>         
                   
					<tr>
                        <td class="tab-head-td" width="12px" align="left"><b>Customer</b></td>
						<td class="tab-head-td" width="12px" align="left"><b><? print_r($All_Devicename[1]);?></b></td>
                        <td class="tab-head-td" colspan="8" width="36px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td" width="12px" align="left"><b>WEG No</b></td>
						<td class="tab-head-td" width="12px" align="left"><b><? print_r($All_WEG_No[1]);?></b></td>
					
                   </tr>
                    <tr>
						<td class="tab-head-td" align="left"><b>Site Location</b></td>
						<td class="tab-head-td" align="left"><b><? echo $Site_Location[1]; ?></b></td>
                        <td class="tab-head-td" colspan="8" width="36px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td" align="left"><b>LOC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   
						
                   </tr>
                    <tr>
						<td class="tab-head-td" align="left"><b>DOC</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($DOC[1]);?></b></td>
                        <td class="tab-head-td" colspan="8" width="36px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td" align="left"><b>HTSC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       
						
                   </tr>
				    <tr>
						<td class="tab-head-td" align="left"><b>Feeder</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($Connect_Feeder[1]);?></b></td>
                        <td class="tab-head-td" colspan="8" width="36px" align="left"><b>&nbsp;</b></td>                       
                        <td class="tab-head-td" align="left"><b>Capacity</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($Capacity[1]);?></td>       
						
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
if(isset($_REQUEST['p']) && $_REQUEST['p'] == 6){

			//if($Mysql_Record_Count >= 1){
if($Fetch_Info_Result_Count >= 1){
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
								if($PAT_Total_24_Merge_Key == 'k24' && $Date_Range_Val_Final1!=date("Y-m-d") ){
									$PTP = $Total_Count_DB[$DRVF] - 1;
								}
							//echo $Date_Range_Val_Final1;
							
									
								$PAT_Total_Previous[$DRVF] = $PAT_Total[$DRVF][$PTP];
								#
								#	Current Value && Fetching PAT_Gen2
								#
								$PAT_Total_Current1[$DRVF] = $PAT_Total_Record_Index[$DRVF][$PAT_Total_24_Merge_Val];
								# 24th data calculation	
								if($PAT_Total_24_Merge_Key == 'k24'  && $Date_Range_Val_Final1!=date("Y-m-d")){
									$PAT_Total_Current1[$DRVF] = $Total_Count_DB[$DRVF];
								}
								$PAT_Total_Current[$DRVF] = $PAT_Total[$DRVF][$PAT_Total_Current1[$DRVF]];
								
								#
								#	Next Value && Fetching PAT_Gen2
								#
								$PAT_Total_Next1[$DRVF] = $PAT_Total_Current1[$DRVF] + 1;
								# 24th data calculation	
								if($PAT_Total_24_Merge_Key == 'k24' ){
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
								$PAT_Total_06_10[$DRVF] =  (($PT_EB_Fl[$DRVF]['k10']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k10']['Prev'] : $PT_EB_Fl[$DRVF]['k10']['Curr']) - ($PT_EB_Fl[$DRVF]['k06']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k06']['Prev'] : $PT_EB_Fl[$DRVF]['k06']['Curr']));//6to10
								if($PAT_Total_06_10[$DRVF] <0)
								{
									$PAT_Total_06_10[$DRVF] = 0;
								}

								$PAT_Total_10_18[$DRVF] =  (($PT_EB_Fl[$DRVF]['k18']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k18']['Prev'] : $PT_EB_Fl[$DRVF]['k18']['Curr']) - ($PT_EB_Fl[$DRVF]['k10']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k10']['Prev'] : $PT_EB_Fl[$DRVF]['k10']['Curr']));//
								if($PAT_Total_10_18[$DRVF] < 0)
								{
									$PAT_Total_10_18[$DRVF] = 0;
								}

								$PAT_Total_05_06[$DRVF] =  (($PT_EB_Fl[$DRVF]['k06']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k06']['Prev'] : $PT_EB_Fl[$DRVF]['k06']['Curr']) - ($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']));
								if($PAT_Total_05_06[$DRVF] < 0)
								{
									$PAT_Total_05_06[$DRVF] = 0;
								}

								$PAT_Total_18_22[$DRVF] =  (($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']) - ($PT_EB_Fl[$DRVF]['k18']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k18']['Prev'] : $PT_EB_Fl[$DRVF]['k18']['Curr']));//6pmto10pm
								if($PAT_Total_18_22[$DRVF] < 0)
								{
									$PAT_Total_18_22[$DRVF] = 0;
								}

								$PAT_Total_21_22[$DRVF] =  (($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']) - ($PT_EB_Fl[$DRVF]['k21']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k21']['Prev'] : $PT_EB_Fl[$DRVF]['k21']['Curr']));
								if($PAT_Total_21_22[$DRVF] < 0)
								{
									$PAT_Total_21_22[$DRVF] =0;
								}

								//$PAT_Total_22_24_left[$DRVF] =  (($PT_EB_Fl[$DRVF]['k24']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k24']['Prev'] : $PT_EB_Fl[$DRVF]['k24']['Curr']) - ($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']));
								

								//$PAT_Total_00_05_Right[$DRVF] =  (($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']) - ($PT_EB_Fl[$DRVF]['k00']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k00']['Prev'] : $PT_EB_Fl[$DRVF]['k00']['Curr']));


	$PAT_Total_00_05[$DRVF] =  (($PT_EB_Fl[$DRVF]['k05']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k05']['Prev'] : $PT_EB_Fl[$DRVF]['k05']['Curr']) - ($PT_EB_Fl[$DRVF]['k00']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k00']['Prev'] : $PT_EB_Fl[$DRVF]['k00']['Curr']));
	
		$PAT_Total_22_24[$DRVF] =  (($PT_EB_Fl[$DRVF]['k24']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k24']['Prev'] : $PT_EB_Fl[$DRVF]['k24']['Curr']) - ($PT_EB_Fl[$DRVF]['k22']['Curr'] == ''?$PT_EB_Fl[$DRVF]['k22']['Prev'] : $PT_EB_Fl[$DRVF]['k22']['Curr']));
	

								//Total(Daily)	06.00-10.00 (C1)	,18.00-22.00(C2)	,10.00-18.00(C4)	
								//,5.00 - 6.00( C4),	0.00 -5.00+22.00-24.00 (C5)
								$Total_Daily[$DRVF] = $PAT_Total_06_10[$DRVF] + $PAT_Total_18_22[$DRVF] + $PAT_Total_10_18[$DRVF]+ $PAT_Total_05_06[$DRVF]+   $PAT_Total_00_05[$DRVF] +   $PAT_Total_22_24[$DRVF];
								
								
								
								//Other Peak Hours 
								$Other_Peak_Hours[$DRVF] = $PAT_Total_06_10[$DRVF] + $PAT_Total_10_18[$DRVF] + $PAT_Total_05_06[$DRVF];
								
								//Peak Hours
								//$Peak_Hours[$DRVF] = $PAT_Total_18_22[$DRVF] + $PAT_Total_21_22[$DRVF];

								//Night Hours
								//$Night_Hours[$DRVF] = $PAT_Total_22_05[$DRVF];
								
								// Cumulative Value		
								$PAT_Total_06_10_CT += $PAT_Total_06_10[$DRVF];
								$PAT_Total_10_18_CT += $PAT_Total_10_18[$DRVF];
								$PAT_Total_05_06_CT += $PAT_Total_05_06[$DRVF];
								$PAT_Total_18_22_CT += $PAT_Total_18_22[$DRVF];
								$PAT_Total_22_24_CT += $PAT_Total_22_24[$DRVF];
								$PAT_Total_00_05_CT += $PAT_Total_00_05[$DRVF];
								$Total_Daily_CT += $Total_Daily[$DRVF];
								$Other_Peak_Hours_CT += $Other_Peak_Hours[$DRVF];
								//$Peak_Hours_CT += $Peak_Hours[$DRVF];
								//$Night_Hours_CT += $PAT_Total_22_05[$DRVF];
								
								
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
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_06_10[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_18_22[$DRVF],1)?></td>
							<!--<td class="tab-head-td1" align="left"><?=round($PAT_Total_21_22[$DRVF],1)?></td>-->
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_10_18[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_05_06[$DRVF],1,1)?></td>
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_00_05[$DRVF],1)?></td>
							<td class="tab-head-td1" align="left"><?=round($PAT_Total_22_24[$DRVF],1)?></td>
							
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
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_06_10_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_18_22_CT,1)?></b></td>                        
							<!--<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_21_22_CT,1)?></b></td>-->
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_10_18_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_05_06_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_00_05_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($PAT_Total_22_24_CT,1)?></b></td>
							
							<td class="tab-head-td1" align="left"><b><?=round($Total_Daily_CT,1)?></b></td>                        
							<!--<td class="tab-head-td1" align="left"><b><?=round($Total_Daily_Cumulative_Final,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($Other_Peak_Hours_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($Peak_Hours_CT,1)?></b></td>
							<td class="tab-head-td1" align="left"><b><?=round($Night_Hours_CT,1)?></b></td>-->
						</tr>
                </table>
				</div>
         <?php 
		}
		else{
			echo $No_Records;
		}
	}
		 
         ?>
           </td>
           </tr>           
 