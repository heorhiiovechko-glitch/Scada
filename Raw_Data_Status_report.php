		 

		 

        <!-- 

            Raw Data-Status

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

						<td class="tab-head-tr" colspan="4"  align="center"><b>Status Report&nbsp;-&nbsp; <?=$IMEI?> </b></td>

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

							<td class="tab-head-tr" colspan="4" align="left">&nbsp;&nbsp;<b>Status</b></td>

						</tr>

 					<?php 

					}

					?>

        <?php

		

        if($_REQUEST['p'] == 42){





	$Raw_Data_Query="select Status,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";
if (!$Mysql_Query_Result = $db->query($Raw_Data_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {              ?>

                    <tr>

                             <td class="tab-head-td" width="15px" align="left"><b>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b></td>
			     <td class="tab-head-td" width="15px" align="left"><b>Status</b></td> 
                                


                    </tr>

                    <?php
while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {								

                    ?>

								<tr>								

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>                 

                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Status']!=''?$Fetch_Result['Status']:'0'?></td> 

                                  
                                   

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

		

		