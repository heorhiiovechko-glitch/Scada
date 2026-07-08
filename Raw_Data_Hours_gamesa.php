		 

		 

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
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
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

						<td class="tab-head-tr" colspan="10"  align="center"><b>Hours Report&nbsp;-&nbsp; <?=$IMEI?> </b></td>

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

							<td class="tab-head-tr" colspan="10" align="left">&nbsp;&nbsp;<b>Hours Report </b></td>

						</tr>

 					<?php 

					}

					?>

        <?php

		

        if(isset($_REQUEST['p']) ){





	$Raw_Data_Query="select Gen_DE_NDE_Bearing_Temp, Nacelle_Temp, Main_Bearing_Temp, Transformer_Oil_Temp, Nacelle_Position, Cable_Twist, Tip_Pressure, Kwh_Positive, Kwh_Negative, KVar_Positive, Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";
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
							 <td class="tab-head-td" width="150px" align="left"><b>Total Hours</b></td>
			     <td class="tab-head-td" width="150px" align="left"><b>Line Hours</b></td> 
                             <td class="tab-head-td" width="150px" align="left"><b>Line Ok Hours</b></td>
			     <td class="tab-head-td" width="150px" align="left"><b>Turbine Ok Hours</b></td>                              
			    
			     <td class="tab-head-td" width="150px" align="left"><b>Run Hours</b></td>
				 <td class="tab-head-td" width="150px" align="left"><b>Couple Run Hours</b></td>
			        </tr>

                    <?php

                       while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	

                    ?>

								<tr>								

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:''?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:''?></td>                 
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Gen_DE_NDE_Bearing_Temp']!=''?$Fetch_Result['Gen_DE_NDE_Bearing_Temp']:''?></td>                
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Nacelle_Temp']!=''?$Fetch_Result['Nacelle_Temp']:''?></td>
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Transformer_Oil_Temp']!=''?$Fetch_Result['Transformer_Oil_Temp']:''?></td>                 
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Cable_Twist']!=''?$Fetch_Result['Cable_Twist']:''?></td> 
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Tip_Pressure']!=''?$Fetch_Result['Tip_Pressure']:''?></td>                  
									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Kwh_Positive']!=''?$Fetch_Result['Kwh_Positive']:''?></td>                  
                                   
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

		

		