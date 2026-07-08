		 

		 

        <!-- 

            Temperature Interval-15min

        -->



	<?php 
error_reporting(0);

	if ($XLS == 0){

	?>

		<tr>

			<td colspan="5" align="left" style="font-size:small">

				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->

				&nbsp;&nbsp;&nbsp;<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel here</a>

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

						<td class="tab-head-tr" colspan="25"  align="center"><b>Parameters Report&nbsp;-&nbsp;1 HR <?=$IMEI?> </b></td>

					</tr>

					<tr>

						<td class="tab-head-td" colspan="5" align="left" width="25%"><b>Customer</b></td>

						<td class="tab-head-td" colspan="5" align="left" width="25%"><b><? print_r($All_Devicename[1]);?></b></td>                      

						<td class="tab-head-td" colspan="5" align="left" width="25%"><b>WEG No</b></td>

						<td class="tab-head-td" colspan="5" align="left" width="25%"><b><? print_r($All_WEG_No[1]);?></b></td>
						<td class="tab-head-td" colspan="5" align="left" width="25%"><b>&nbsp;</b></td>

					

					</tr>

					<tr>

						<td class="tab-head-td" colspan="5" align="left"><b>Site Location</b></td>

						<td class="tab-head-td" colspan="5" align="left"><b><? print_r($Site_Location[1]);?></b></td>                     

						<td class="tab-head-td" colspan="5" align="left"><b>LOC No</b></td>

						<td class="tab-head-td" colspan="5" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   
						<td class="tab-head-td" colspan="5" align="left" width="25%"><b>&nbsp;</b></td>
						

					</tr>

					<tr>

						<td class="tab-head-td" colspan="5" align="left"><b>DOC</b></td>

						<td class="tab-head-td" colspan="5" align="left"><b><? print_r($DOC[1]);?></b></td>                      

						<td class="tab-head-td" colspan="5" align="left"><b>HTSC No</b></td>

						<td class="tab-head-td" colspan="5" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       
						<td class="tab-head-td" colspan="5" align="left" width="25%"><b>&nbsp;</b></td>
					

					</tr>

					<tr style="border:0px"><td colspan="25" >&nbsp;</td></tr>

					<?php

					}

					?>

 					<?php 

					if ($XLS == 0){

					?>

						<tr>

							<td class="tab-head-tr" colspan="35" align="left">&nbsp;&nbsp;<b>Parameters</b></td>

						</tr>

 					<?php 

					}

					?>

        <?php

		

        if($_REQUEST['p'] == 49){





	$Raw_Data_Query="select sec_to_time(time_to_sec(Time_S)- time_to_sec(Time_S)%(15*60)) AS Time_diff,GRPM,RRPM,WindSpeed,Status,Pitch,Power,Ambient_Temp,Hydraulic_Temp,Gear_Temp,Gen1_Temp,Nacel_Temp,Control_Temp,Bearing_Temp,PAT_Gen0,PAT_Gen1,PAT_Gen2,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Power_Factor,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') and Status!=''and ID_Number!=''   group by Date_S,Time_diff order by Record_Index desc ";

	//echo $Raw_Data_Query;
		$Mysql_Query_Result = mysql_query($Raw_Data_Query) or die(mysql_error());   

			$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);

									
if($Mysql_Record_Count >= 1){

        ?>

                    <tr>

                             <td class="tab-head-td" width="15px" align="left"><b>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></td>
			     <td class="tab-head-td" width="15px" align="left"><b>GRPM</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>RRPM</b></td>
			     <td class="tab-head-td" width="15px" align="left"><b>WindSpeed</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>Pitch</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Power</b></td>
			     <td class="tab-head-td" width="70px" align="left"><b>Status</b></td>
				 <td class="tab-head-td" width="150px" align="left"><b>R Volt</b></td> 
                             <td class="tab-head-td" width="150px" align="left"><b>Y Volt</b></td>
			     <td class="tab-head-td" width="150px" align="left"><b>B Volt</b></td> 
                             <td class="tab-head-td" width="150px" align="left"><b>R Current</b></td>			    
			     <td class="tab-head-td" width="150px" align="left"><b>Y Current</b></td>
			 <td class="tab-head-td" width="150px" align="left"><b>B Current</b></td>			 
			 <td class="tab-head-td" width="150px" align="left"><b>Power Factor</b></td>
			 <td class="tab-head-td" width="15px" align="left"><b>Ambient Temp</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>Hydraulic Temp</b></td>
			     <td class="tab-head-td" width="15px" align="left"><b>Gear Temp</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>Gen1 Temp</b></td>			    
			     <td class="tab-head-td" width="70px" align="left"><b>Nacel Temp</b></td>
			 <td class="tab-head-td" width="70px" align="left"><b>Control Temp</b></td>
			<td class="tab-head-td" width="70px" align="left"><b>Bearing Temp</b></td>
				 <td class="tab-head-td" width="170px" align="left"><b>PAT Gen0</b></td>
                             <td class="tab-head-td" width="170px" align="left"><b>PAT Gen1</b></td>
			     <td class="tab-head-td" width="170px" align="left"><b>Net Total</b></td> 
  			     			     


                    </tr>

                    <?php

                       

										

                        while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){

							

                    ?>

								<tr>								

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['GRPM']!=''?$Fetch_Result['GRPM']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['RRPM']!=''?$Fetch_Result['RRPM']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['WindSpeed']!=''?$Fetch_Result['WindSpeed']:'0'?></td>
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Pitch']!=''?$Fetch_Result['Pitch']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Status']!=''?$Fetch_Result['Status']:'0'?></td>  
<td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Volt']!=''?$Fetch_Result['RPhase_Volt']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Volt']!=''?$Fetch_Result['YPhase_Volt']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Volt']!=''?$Fetch_Result['BPhase_Volt']:'0'?></td>
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Current']!=''?$Fetch_Result['RPhase_Current']:'0'?></td>                 

                                    
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Current']!=''?$Fetch_Result['YPhase_Current']:'0'?></td>                  

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Current']!=''?$Fetch_Result['BPhase_Current']:'0'?></td>                  
								

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Power_Factor']!=''?$Fetch_Result['Power_Factor']:'0'?></td>   					
 <td class="tab-head-td1" align="left"><?=$Fetch_Result['Ambient_Temp']!=''?$Fetch_Result['Ambient_Temp']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Hydraulic_Temp']!=''?$Fetch_Result['Hydraulic_Temp']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Gear_Temp']!=''?$Fetch_Result['Gear_Temp']:'0'?></td>
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen1_Temp']!=''?$Fetch_Result['Gen1_Temp']:'0'?></td>                 

                                    
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Nacel_Temp']!=''?$Fetch_Result['Nacel_Temp']:'0'?></td>                  

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Control_Temp']!=''?$Fetch_Result['Control_Temp']:'0'?></td>                  
				<td class="tab-head-td1" align="left"><?=$Fetch_Result['Bearing_Temp']!=''?$Fetch_Result['Bearing_Temp']:'0'?></td> 
 <td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen0']!=''?$Fetch_Result['PAT_Gen0']:'0'?></td>

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen1']!=''?$Fetch_Result['PAT_Gen1']:'0'?></td>
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen2']!=''?$Fetch_Result['PAT_Gen2']:'0'?></td>                 
				

                                   

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

		

		