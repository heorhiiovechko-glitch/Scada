		 

		 

        <!-- 

            Raw Data-oVERVIEW

        -->



	<?php 
error_reporting(0);

	if ($XLS == 0){

	?>

		<tr>

			<td colspan="5" align="left" style="font-size:small">

				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->

				&nbsp;&nbsp;&nbsp;<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel here</a>

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

						<td class="tab-head-tr" colspan="4"  align="center"><b>Parameters Report&nbsp;-&nbsp; <?=$IMEI?> </b></td>

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

					<tr style="border:0px"><td colspan="4" >&nbsp;</td></tr>

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

		

        if(isset($_REQUEST['p']) && $_REQUEST['p'] == 51){





	$Raw_Data_Query="select * from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') and Status!='' and ID_Number!=''  order by Date_S desc, Time_S desc ";

			$Mysql_Query_Result = mysql_query($Raw_Data_Query) or die(mysql_error());   

			$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);

									

										

									

			if($Mysql_Record_Count >= 1){

        ?>
<tr class="tab-head-tr-new">
								<td colspan="6" align="center">Status</td>
								<td colspan="11" align="center" width="400px;">Electrical</td>
								<td colspan="9" align= "center">Temperature</td>
								<td colspan="3" align= "center">Active Production</td>								
								<td colspan="3" align= "center">Hours</td>
								
								</tr>
                    <tr>

                             <td class="tab-head-td" width="15px" align="left"><b>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></td>
							<td class="tab-head-td" width="15px" align="left"><b>GRPM</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>RRPM</b></td>
							<td class="tab-head-td" width="15px" align="left"><b>WindSpeed</b></td> 
							<td class="tab-head-td" width="70px" align="left"><b>Status</b></td>
                             <td class="tab-head-td" width="70px" align="left"><b>Hyd. Prsr</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Nac. Post</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Power</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Reactive_Power</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Freq</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>R volt</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Y volt</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>B volt</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>R Current</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Y Current</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>B Current</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Ambt </b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Nacel </b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gear Bear</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gear oil</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gen1</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gen2</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Thyris</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Main Panel</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Temp 10</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gen1</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gen2</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Total</b></td>
  			     			<td class="tab-head-td" width="70px" align="left"><b>Total</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gen1</b></td>
							<td class="tab-head-td" width="70px" align="left"><b>Gen2</b></td>


                    </tr>

                    <?php

                       

										

                        while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){
							$Status=$Fetch_Result['Status'];
							

                    ?>

								<tr>								

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['GRPM']!=''?$Fetch_Result['GRPM']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['RRPM']!=''?$Fetch_Result['RRPM']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['WindSpeed']!=''?$Fetch_Result['WindSpeed']:'0'?></td>
									<?php
									if($Status=='Run' || $Status=='M/C Running' || $Status=='RUN' || $Status=='OperateG1' || $Status=='OperateG2' || $Status=='RUNNING G1') {
?>
                                        <td class="tab-head-td1-status" style="background-color:green; font-colour:white;"><?=$Status?></td>
                                    
<?php
}
elseif($Status=='Grid Drop' || $Status=='GridDrop') {
?>
                                
                                        <td class="tab-head-td1-status" style="background-color:blue; font-colour:white;"><?=$Status?></td>
                                   
<?php
}
else {
?>
                                        <td class="tab-head-td1-status" style="background-color:red; font-colour:white;"><?=$Status?></td>
                                 
<?php
}
?>						
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Hydraulic_Pressure']!=''?$Fetch_Result['Hydraulic_Pressure']:'0'?></td>   
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Nacelle_Position']!=''?$Fetch_Result['Nacelle_Position']:'0'?></td>  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Reactive_Power']!=''?$Fetch_Result['Reactive_Power']:'0'?></td> 
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Frequency']!=''?$Fetch_Result['Frequency']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Volt']!=''?$Fetch_Result['RPhase_Volt']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Volt']!=''?$Fetch_Result['YPhase_Volt']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Volt']!=''?$Fetch_Result['BPhase_Volt']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Current']!=''?$Fetch_Result['RPhase_Current']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Current']!=''?$Fetch_Result['YPhase_Current']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Current']!=''?$Fetch_Result['BPhase_Current']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Ambient_Temp']!=''?$Fetch_Result['Ambient_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Nacel_Temp']!=''?$Fetch_Result['Nacel_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Bearing_Temp']!=''?$Fetch_Result['Bearing_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gear_Temp']!=''?$Fetch_Result['Gear_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen1_Temp']!=''?$Fetch_Result['Gen1_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen2_Temp']!=''?$Fetch_Result['Gen2_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Thyristor_Temp']!=''?$Fetch_Result['Thyristor_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Main_Panel_Temp']!=''?$Fetch_Result['Main_Panel_Temp']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Temp10']!=''?$Fetch_Result['Temp10']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen1']!=''?$Fetch_Result['PAT_Gen1']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['PAT_Gen2']!=''?$Fetch_Result['PAT_Gen2']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Production_Total']!=''?$Fetch_Result['Production_Total']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Total_Hours']!=''?$Fetch_Result['Total_Hours']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen1_Hours']!=''?$Fetch_Result['Gen1_Hours']:'0'?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen2_Hours']!=''?$Fetch_Result['Gen2_Hours']:'0'?></td>
									
									
									
										  

                                  
				   

                                   

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

		

		