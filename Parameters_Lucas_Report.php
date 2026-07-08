		 

		 

        <!-- 

            Temperature

        -->



	<?php 
error_reporting(0);

	if ($XLS == 0){

	?>

		<tr>

			<td colspan="5" align="left" style="font-size:small">

				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->

				&nbsp;&nbsp;&nbsp;<a href='channel9_lucas_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel here</a>

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

						<td class="tab-head-tr" colspan="18"  align="center"><b>Lucas Parameters Report&nbsp;-&nbsp;  </b></td>

					</tr>

					<tr>

						<td class="tab-head-td" align="left" colspan="3" width="25%"><b>Customer</b></td>

						<td class="tab-head-td" align="left" colspan="3" width="25%"><b>Lucas</b></td>                      

						<td colspan="12" >&nbsp;</td>

					</tr>

					<tr style="border:0px"><td colspan="18" >&nbsp;</td></tr>

					<?php

					}

					?>

 					<?php 

					if ($XLS == 0){

					?>

						<tr>

							<td class="tab-head-tr" colspan="18" align="left">&nbsp;&nbsp;<b>Parameters Report</b></td>

						</tr>

 					<?php 

					}

					?>

        <?php

		

        if(isset($_REQUEST['p']) && $_REQUEST['p'] == 1){





	$Raw_Data_Query="select Date_S,Time_S,RRPM,GRPM,Windspeed,Date,time,Power,Reactive_Power,L_N_Voltage_R,L_N_Voltage_Y,L_N_Voltage_B,L_L_Voltage_RY,L_L_Voltage_YB,Min3_Wind_Dir,Min3_Active_Power from va_aspire.$Table_Name where IMEI='".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Record_Index desc ";
//echo $Raw_Data_Query;
			if (!$Mysql_Result = $db->query($Raw_Data_Query))
            {
                die($db->error);
            }
            $Row_Count= $Mysql_Result->num_rows;
			if($Row_Count>=1){
				
                       	

        ?>
				<tr>
                        <td class="tab-head-tr" width="175px" align="center"><b></b></td> 
                        <td class="tab-head-tr" align="center"><b></b></td>
						<td class="tab-head-tr" colspan="12" align="center"><b>Temperature</b></td>
						<td class="tab-head-tr" colspan="3" align="center"><b>Flow Rate</b></td>
						<td class="tab-head-tr" align="center"><b>Heat Delivered</b></td> 
				</tr>
				<tr>
                        <td class="tab-head-td" align="center"><b></b></td> 
                        <td class="tab-head-td" align="center"><b></b></td>
						<td class="tab-head-td" colspan="2" align="center"><b>Passivation Tank</b></td>
						<td class="tab-head-td" colspan="2" align="center"><b>Phospating Tank</b></td>
						<td class="tab-head-td" colspan="2" align="center"><b>Degreasing Tank</b></td> 
                        <td class="tab-head-td" colspan="2" align="center"><b>Stripping Tank </b></td>
						<td class="tab-head-td" colspan="2" align="center"><b>Water Source Pump</b></td>
						<td class="tab-head-td" colspan="2" align="center"><b>Circulation Pump</b></td>
						<td class="tab-head-td" align="center"><b></b></td> 
                        <td class="tab-head-td" align="center"><b></b></td>
						<td class="tab-head-td" align="center"><b></b></td> 
                        <td class="tab-head-td" align="center"><b></b></td>
				</tr>
				<tr>
                 <td class="tab-head-td" width="175px" align="left"><b>Date&nbsp;&nbsp;</b></td> 
                 <td class="tab-head-td" width="70px" align="left"><b>Time&nbsp;&nbsp;&nbsp; </b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Outlet</b></td>
				 <td class="tab-head-td" width="70px" align="left"><b>Inlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Outlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Inlet</b></td> 
                 <td class="tab-head-td" width="70px" align="left"><b>Outlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Inlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Outlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Inlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Outlet</b></td>
  			     <td class="tab-head-td" width="70px" align="left"><b>Inlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Outlet</b></td>
  			     <td class="tab-head-td" width="70px" align="left"><b>Inlet</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>LPM</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>LPS</b></td> 
                 <td class="tab-head-td" width="70px" align="left"><b>Kg/S</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Kw</b></td>
			       
  	          </tr>
                    <?php

                         while($Fetch_Result = $Mysql_Result->fetch_array()) {
							$FR_LPM=$Fetch_Result['Min3_Wind_Dir'];
							$FR_Kg=round((($FR_LPM*0.97)/60),2);
							//$Heat_Delivered=round((($T11-$T12)*4.186*$FR_Kg),2);
                    ?>
						<tr>								
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['RRPM']!=''?$Fetch_Result['RRPM']:'0'?></td> 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['GRPM']!=''?$Fetch_Result['GRPM']:'0'?></td>
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Windspeed']!=''?$Fetch_Result['Windspeed']:'0'?></td>
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Date']!=''?$Fetch_Result['Date']:'0'?></td>                  
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Time']!=''?$Fetch_Result['Time']:'0'?></td>                 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td> 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Reactive_Power']!=''?$Fetch_Result['Reactive_Power']:'0'?></td> 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_R']!=''?$Fetch_Result['L_N_Voltage_R']:'0'?></td>                                 
						    <td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_Y']!=''?$Fetch_Result['L_N_Voltage_Y']:'0'?></td>
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['L_N_Voltage_B']!=''?$Fetch_Result['L_N_Voltage_B']:'0'?></td> 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_RY']!=''?$Fetch_Result['L_L_Voltage_RY']:'0'?></td>                 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['L_L_Voltage_YB']!=''?$Fetch_Result['L_L_Voltage_YB']:'0'?></td>
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Min3_Wind_Dir']!=''?$Fetch_Result['Min3_Wind_Dir']:'0'?></td> 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Min3_Active_Power']!=''?$Fetch_Result['Min3_Active_Power']:'0'?></td>                 
							<td class="tab-head-td1" align="left"><?=$Fetch_Result['Min3_Wind_Dir']!=''?round((($Fetch_Result['Min3_Wind_Dir']*0.97)/60),2):'0'?></td>                                 
						   <td class="tab-head-td1" align="left"><?=round((abs($Fetch_Result['L_L_Voltage_RY']-$Fetch_Result['L_L_Voltage_YB'])*4.186*$FR_Kg),2)?></td>
						
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
