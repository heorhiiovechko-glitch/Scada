		 

		 

        <!-- 

            Raw Data-Grid

        -->



	<?php 
error_reporting(0);

	if ($XLS == 0){

	?>

		<tr>

			<td colspan="5" align="left" style="font-size:small">

				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->

				<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			
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

						<td class="tab-head-tr" colspan="13"  align="center"><b>Grid Report&nbsp;-&nbsp; <?=$IMEI?> </b></td>

					</tr>

					<tr>

						<td class="tab-head-td" colspan="2" align="left" width="25%"><b>Customer</b></td>

						<td class="tab-head-td" colspan="3" align="left" width="25%"><b><? print_r($All_Devicename[1]);?></b></td>                      

						<td class="tab-head-td" colspan="2" align="left" width="25%"><b>WEG No</b></td>

						<td class="tab-head-td" colspan="3" align="left" width="25%"><b><? print_r($All_WEG_No[1]);?></b></td>
						<td class="tab-head-td" colspan="3" align="left">&nbsp;</b></td>
					

					</tr>

					<tr>

						<td class="tab-head-td" colspan="2" align="left"><b>Site Location</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($Site_Location[1]);?></b></td>                     

						<td class="tab-head-td" colspan="2" align="left"><b>LOC No</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   

						<td class="tab-head-td" colspan="3" align="left">&nbsp;</b></td>

					</tr>

					<tr>

						<td class="tab-head-td" colspan="2" align="left"><b>DOC</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($DOC[1]);?></b></td>                      

						<td class="tab-head-td" colspan="2" align="left"><b>HTSC No</b></td>

						<td class="tab-head-td" colspan="3" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       

					

					</tr>

					<tr style="border:0px"><td colspan="13" >&nbsp;</td></tr>

					<?php

					}

					?>

 					<?php 

					if ($XLS == 0){

					?>

						<tr>

							<td class="tab-head-tr" colspan="13" align="left">&nbsp;&nbsp;<b>Grid</b></td>

						</tr>

 					<?php 

					}

					?>

        <?php

		

        if(isset($_REQUEST['p']) ){





	$Raw_Data_Query="select RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Power,PAM_Gen1,PATP_Gen0,PATP_Gen1,PATP_Gen2,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') and ID_Number!=''   order by Date_S desc, Time_S desc ";

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
			     <td class="tab-head-td" width="150px" align="left"><b>R Volt</b></td> 
                             <td class="tab-head-td" width="150px" align="left"><b>Y Volt</b></td>
			     <td class="tab-head-td" width="150px" align="left"><b>B Volt</b></td> 
                             <td class="tab-head-td" width="150px" align="left"><b>R Current</b></td>
			    
			     <td class="tab-head-td" width="150px" align="left"><b>Y Current</b></td>
			 <td class="tab-head-td" width="150px" align="left"><b>B Current</b></td>
			  <td class="tab-head-td" width="150px" align="left"><b>Power</b></td>
			 <td class="tab-head-td" width="150px" align="left"><b>Oil Pressure</b></td>
			 <td class="tab-head-td" width="150px" align="left"><b>Twist</b></td>
			 <td class="tab-head-td" width="150px" align="left"><b>Nacelle Position</b></td>
			 <td class="tab-head-td" width="150px" align="left"><b>Wind Direction</b></td>
			
  			     			     


                    </tr>

                    <?php
							while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {		
							?>

								<tr>								

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Volt']!=''?$Fetch_Result['RPhase_Volt']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Volt']!=''?$Fetch_Result['YPhase_Volt']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Volt']!=''?$Fetch_Result['BPhase_Volt']:'0'?></td>
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['RPhase_Current']!=''?$Fetch_Result['RPhase_Current']:'0'?></td>                 

                                    
				    <td class="tab-head-td1" align="left"><?=$Fetch_Result['YPhase_Current']!=''?$Fetch_Result['YPhase_Current']:'0'?></td>                  

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['BPhase_Current']!=''?$Fetch_Result['BPhase_Current']:'0'?></td>                  
								<td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>                  

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['PAM_Gen1']!=''?$Fetch_Result['PAM_Gen1']:'0'?></td>                  
						<td class="tab-head-td1" align="left"><?=$Fetch_Result['PATP_Gen0']!=''?$Fetch_Result['PATP_Gen0']:'0'?></td>                  
						<td class="tab-head-td1" align="left"><?=$Fetch_Result['PATP_Gen1']!=''?$Fetch_Result['PATP_Gen1']:'0'?></td>
<td class="tab-head-td1" align="left"><?=$Fetch_Result['PATP_Gen2']!=''?$Fetch_Result['PATP_Gen2']:'0'?></td>                  						
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

		

		