<?php /* UI Enhancements */ ?>
<style>
.report-container{font-family:Arial;background:#fff;padding:10px;}
.grid-table{width:100%;border-collapse:collapse;font-size:14px;}
.grid-table th{background:#0a3d62;color:#fff;padding:6px;border:1px solid #ccc;}
.grid-table td{padding:6px;border:1px solid #ccc;}
.grid-table tr:nth-child(even){background:#f2f8ff;}
.export-buttons{margin:10px 0;}
.export-buttons button{padding:6px 10px;margin-right:6px;border-radius:4px;border:none;cursor:pointer;}
.export-csv{background:#1abc9c;color:#fff;}
.export-pdf{background:#e74c3c;color:#fff;}
.export-print{background:#3498db;color:#fff;}
</style>
<div class="report-container">
<div class="export-buttons">
<button class="export-csv" onclick="exportCSV()">Export CSV</button>
<button class="export-pdf" onclick="exportPDF()">Export PDF</button>
<button class="export-print" onclick="window.print()">Print</button>
</div>
<script>
function exportCSV(){alert('Implement CSV export');}
function exportPDF(){alert('Implement PDF export');}
</script>
		 

		 

        <!-- 

            Raw Data-Grid

        -->



	<?php 
error_reporting(0);

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
			<?php  }if($FType==9){
				if($Cook_Variable[0]!='tirumala') {?>
				<a href='channel9new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
				<?php }
				}if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
			<?php }	if($FType==11){?>
				<a href='channel11_new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
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

					if ($XLS == 1){

					?>

					<tr>

						<td class="tab-head-tr" colspan="10"  align="center"><b>Grid Report&nbsp;-&nbsp; <?=$IMEI?> </b></td>

					</tr>

					<tr>

						<td class="tab-head-td" colspan="2" align="left" width="25%"><b>Customer</b></td>

						<td class="tab-head-td" colspan="3" align="left" width="25%"><b><? print_r($All_Devicename[1]);?></b></td>                      

						<td class="tab-head-td" colspan="2" align="left" width="25%"><b>WEG No</b></td>

						<td class="tab-head-td" colspan="3" align="left" width="25%"><b><? print_r($All_WEG_No[1]);?></b></td>

					

					</tr>

					<tr>

						<td class="tab-head-td" colspan="2" align="left"><b>Site Location</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($Site_Location[1]);?></b></td>                     

						<td class="tab-head-td" colspan="2" align="left"><b>LOC No</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   

						

					</tr>

					<tr>

						<td class="tab-head-td" colspan="2" align="left"><b>DOC</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($DOC[1]);?></b></td>                      

						<td class="tab-head-td" colspan="2" align="left"><b>HTSC No</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       

					

					</tr>

					<tr style="border:0px"><td colspan="10" >&nbsp;</td></tr>

					<?php

					}

					?>

 					<?php 

					if ($XLS == 0){

					?>

						<tr>

							<td class="tab-head-tr" colspan="10" align="left">&nbsp;&nbsp;<b>Grid</b></td>

						</tr>

 					<?php 

					}

					?>

        <?php

		

        if(isset($_REQUEST['p']) ){

			if($FType==7 || $FType==8) 
			{
				if($All_Devicename[1]=='KP Tex2') 
				{
				$Raw_Data_Query="select L_N_Voltage_R, L_N_Voltage_Y, L_N_Voltage_B, L_L_Voltage_RY, L_L_Voltage_YB, L_L_Voltage_BR, RPhase_Current, YPhase_Current, BPhase_Current, Power, Reactive_Power,Power_Factor,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_UK."' and  Date_S <= '".$To_UK."') and ID_Number!=''  order by Date_S desc, Time_S desc ";
				} 
				else if(($All_Devicename[1]=='AIKI 01') || ($All_Devicename[1]=='AIKI 02') )
				{
				$Raw_Data_Query="select L_N_Voltage_R, L_N_Voltage_Y, L_N_Voltage_B, L_L_Voltage_RY, L_L_Voltage_YB, L_L_Voltage_BR, RPhase_Current, YPhase_Current, BPhase_Current, Power, Reactive_Power,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_UK."' and  Date_S <= '".$To_UK."') and ID_Number!=''  order by Date_S desc, Time_S desc ";
				} 
				else 
				{
					$Raw_Data_Query="select L_N_Voltage_R,L_N_Voltage_Y,L_N_Voltage_B,L_L_Voltage_RY,L_L_Voltage_YB,L_L_Voltage_BR,RPhase_Current,YPhase_Current,BPhase_Current,Power,Reactive_Power,Power_Factor,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_UK."' and  Date_S <= '".$To_UK."') and ID_Number!=''  order by Date_S desc, Time_S desc ";
				}
			}	
			else if($FType==11) 
			{
				//Record_Index, Message_ID, IMEI, Date_F, Time_F, Project_Version, ID_Number, tag_grpm, tag_rrpm, tag_windspd, tag_status, tag_pitch, tag_date, tag_time, tag_power, tag_react, tag_warning, tag_prod_kwh, tag_cons_kwh, tag_prod_kvarh, tag_cons_kvarh, tag_yaw_dir, tag_yaw_angle, tag_r_vol, tag_y_vol, tag_b_vol, tag_r_curr, tag_y_curr, tag_b_curr, tag_freq, tag_pow_factor, tag_small_g_kwh, tag_big_g_kwh, tag_pow_10_mins, tag_wind_10_mins, tag_gw_temp, tag_gv_temp, tag_gu_temp, tag_thy_temp, tag_amb_temp, tag_gearbox_temp, tag_nacl_temp, tag_gearbox_hss_temp, tag_cap_pnl_temp, tag_small_gw_temp, tag_small_gv_temp, tag_small_gu_temp, tag_hub_bear_temp, tag_fluid_couple_temp, tag_gen_de_bear_temp, tag_gen_nde_bear_temp, tag_hss_otr_temp, tag_intrnl_temp, tag_gad_kwh, tag_gam_kwh, tag_gay_kwh, tag_total_hrs, tag_access_hrs, tag_work_hrs, tag_wtg_ok_hrs, tag_grid_ok_hrs, tag_mac_tdy_avblty, tag_mac_mth_avblty, tag_mac_yr_avblty, tag_grid_tdy_avblty, tag_grid_mth_avblty, tag_grid_yr_avblty, tag_capcty_tdy_avblty, tag_capcty_mth_avblty, tag_capcty_yr_avblty, Log_Val, Acc_Val, Dummy1, Dummy2, Dummy3, Dummy4, Dummy5, Dummy6, Dummy7, Dummy8, Dummy9, Dummy10, Dummy11, Dummy12, Dummy13, Dummy14, Dummy15, Dummy16, Date_S, Time_S
				$Raw_Data_Query="select tag_r_vol as RPhase_Volt,tag_y_vol as YPhase_Volt, tag_b_vol as BPhase_Volt, tag_r_curr as RPhase_Current,tag_y_curr as YPhase_Current, tag_b_curr as BPhase_Current,tag_power as Power,tag_pow_factor as Power_Factor,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') and ID_Number!=''   order by Date_S desc, Time_S desc ";
			}
			else 
			{
				$Raw_Data_Query="select RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Power,Power_Factor,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') and ID_Number!=''   order by Date_S desc, Time_S desc ";
			}
	
		if (!$Mysql_Query_Result = $db->query($Raw_Data_Query))
            {
                die($db->error);
            }
            if($Mysql_Query_Result->num_rows >= 1)
            {  
        ?>

                    <tr>
							 <td class="tab-head-td" width="150px" align="left"><b>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
                             <td class="tab-head-td" width="20px" align="left"><b>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></td>
							
						<?php 
							if($FType==7 || $FType==8) 
							{
								if($All_Devicename[1]=='KP Tex2') 
								{
								
									?>
										 <td class="tab-head-td" width="150px" align="left"><b>Grid Voltage</b></td> 
										 <td class="tab-head-td" width="150px" align="left"><b>Stator Voltage</b></td>
										<td class="tab-head-td" width="150px" align="left"><b>Bus Voltage</b></td> 
										 <td class="tab-head-td" width="150px" align="left"><b>Grid Reactive Power</b></td>
										<td class="tab-head-td" width="150px" align="left"><b>Stator Active Power</b></td>
										<td class="tab-head-td" width="150px" align="left"><b>Stator Reactive Power</b></td>
										 <td class="tab-head-td" width="150px" align="left"><b>Grid Frequency</b></td>
										 <td class="tab-head-td" width="150px" align="left"><b>Stator Frequency</b></td>
										
									<?php 
								} 
								else if(($All_Devicename[1]=='AIKI 01') || ($All_Devicename[1]=='AIKI 02') )
								{
										?>
									 <td class="tab-head-td" width="150px" align="left"><b>LN Volt R</b></td> 
									 <td class="tab-head-td" width="150px" align="left"><b>LN Volt y</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>LN Volt B</b></td> 
									 <td class="tab-head-td" width="150px" align="left"><b>LL Volt RY</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>LL Volt YB</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>LL Volt BR</b></td>
									 <td class="tab-head-td" width="150px" align="left"><b>R Current</b></td>
									 <td class="tab-head-td" width="150px" align="left"><b>Y Current</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>B Current</b></td>
									  <td class="tab-head-td" width="150px" align="left"><b>Power</b></td>
									  <td class="tab-head-td" width="150px" align="left"><b>Reactive Power</b></td>
									 
								<?php 
								}
								else 
								{
										?>
									 <td class="tab-head-td" width="150px" align="left"><b>LN Volt R</b></td> 
									 <td class="tab-head-td" width="150px" align="left"><b>LN Volt y</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>LN Volt B</b></td> 
									 <td class="tab-head-td" width="150px" align="left"><b>LL Volt RY</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>LL Volt YB</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>LL Volt BR</b></td>
									 <td class="tab-head-td" width="150px" align="left"><b>R Current</b></td>
									 <td class="tab-head-td" width="150px" align="left"><b>Y Current</b></td>
									<td class="tab-head-td" width="150px" align="left"><b>B Current</b></td>
									  <td class="tab-head-td" width="150px" align="left"><b>Power</b></td>
									  <td class="tab-head-td" width="150px" align="left"><b>Reactive Power</b></td>
									 <td class="tab-head-td" width="150px" align="left"><b>Power Factor</b></td>
								<?php 
								}
							} 
							else 
							{
						?>
							 <td class="tab-head-td" width="150px" align="left"><b>R Volt</b></td> 
                             <td class="tab-head-td" width="150px" align="left"><b>Y Volt</b></td>
							 <td class="tab-head-td" width="150px" align="left"><b>B Volt</b></td> 
                             <td class="tab-head-td" width="150px" align="left"><b>R Current</b></td>		    
							 <td class="tab-head-td" width="150px" align="left"><b>Y Current</b></td>
							 <td class="tab-head-td" width="150px" align="left"><b>B Current</b></td>
							 <td class="tab-head-td" width="150px" align="left"><b>Power</b></td>
							 <td class="tab-head-td" width="150px" align="left"><b>Power Factor</b></td>
						<?php
							}
						?>
                    </tr>

                    <?php
						while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {		
                    ?>

								<tr>								

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td> 
							<?php 
							if($FType==7 || $FType==8) {
								if($All_Devicename[1]=='KP Tex2') 
								{
							?>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_Y']!=''?$Fetch_Result['L_N_Voltage_Y']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_B']!=''?$Fetch_Result['L_N_Voltage_B']:'0'?></td>
									 <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_RY']!=''?$Fetch_Result['L_L_Voltage_RY']:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_YB']!=''?$Fetch_Result['L_L_Voltage_YB']:'0'?></td>  
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_BR']!=''?$Fetch_Result['L_L_Voltage_BR']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Current']!=''?$Fetch_Result['RPhase_Current']:'0'?></td>  				
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Current']!=''?$Fetch_Result['YPhase_Current']:'0'?></td>  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Current']!=''?$Fetch_Result['BPhase_Current']:'0'?></td>                  
                                    
							<?php
								} 
								else if(($All_Devicename[1]=='AIKI 01') || ($All_Devicename[1]=='AIKI 02') )
								{
							?>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_R']!=''?$Fetch_Result['L_N_Voltage_R']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_Y']!=''?$Fetch_Result['L_N_Voltage_Y']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_B']!=''?$Fetch_Result['L_N_Voltage_B']:'0'?></td>
									 <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_RY']!=''?$Fetch_Result['L_L_Voltage_RY']:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_YB']!=''?$Fetch_Result['L_L_Voltage_YB']:'0'?></td>  
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_BR']!=''?$Fetch_Result['L_L_Voltage_BR']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Current']!=''?$Fetch_Result['RPhase_Current']:'0'?></td>  				
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Current']!=''?$Fetch_Result['YPhase_Current']:'0'?></td>  
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Current']!=''?$Fetch_Result['BPhase_Current']:'0'?></td>                  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>                  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Reactive_Power']!=''?$Fetch_Result['Reactive_Power']:'0'?></td>                  
                                            
                                    
							<?php
								}
								else {
								?>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_R']!=''?$Fetch_Result['L_N_Voltage_R']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_Y']!=''?$Fetch_Result['L_N_Voltage_Y']:'0'?></td>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_B']!=''?$Fetch_Result['L_N_Voltage_B']:'0'?></td>
									 <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_RY']!=''?$Fetch_Result['L_L_Voltage_RY']:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_YB']!=''?$Fetch_Result['L_L_Voltage_YB']:'0'?></td>  
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_BR']!=''?$Fetch_Result['L_L_Voltage_BR']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Current']!=''?$Fetch_Result['RPhase_Current']:'0'?></td>  				
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Current']!=''?$Fetch_Result['YPhase_Current']:'0'?></td>  
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Current']!=''?$Fetch_Result['BPhase_Current']:'0'?></td>                  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>                  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Reactive_Power']!=''?$Fetch_Result['Reactive_Power']:'0'?></td>                  
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Power_Factor']!=''?$Fetch_Result['Power_Factor']:'0'?></td>
							<?php 
								}
							} else {
							?>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Volt']!=''?$Fetch_Result['RPhase_Volt']:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Volt']!=''?$Fetch_Result['YPhase_Volt']:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Volt']!=''?$Fetch_Result['BPhase_Volt']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Current']!=''?$Fetch_Result['RPhase_Current']:'0'?></td> 
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Current']!=''?$Fetch_Result['YPhase_Current']:'0'?></td>  
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Current']!=''?$Fetch_Result['BPhase_Current']:'0'?></td>                  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>                  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Power_Factor']!=''?$Fetch_Result['Power_Factor']:'0'?></td>                  
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

		}

         ?>

			</td>

        </tr>

		

		</div>