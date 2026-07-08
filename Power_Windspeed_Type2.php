  <!-- 
            Power Vs WInd Speed
        -->
<?php 
	if ($XLS == 0 && $Mysql_Record_Count > 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
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
                        <td class="tab-head-td" colspan="4" align="center"><b>Power Vs WInd Speed</b></td>
                    </tr>
                    <tr>
						<td class="tab-head-td" align="left" width="15%"><b>Customer</b></td>
						<td class="tab-head-td" align="left" width="15%"><b><? print_r($All_Devicename[1]);?></b></td>
						<td class="tab-head-td" align="left" width="10%"><b>WEG No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_WEG_No[1]);?></b></td>					
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
						<td  class="tab-head-tr"  colspan="5" align="left">&nbsp;&nbsp;<b>Power Vs WInd Speed</b></td>
					</tr>
					<?php 
					}
					?>
					
        <?php
		
        if(isset($_REQUEST['p']) && $_REQUEST['p'] == 4){


	$Pow_Windspeed_Query="select Power,Windspeed,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and Date_S between '".$From_YMD."' and '".$To_YMD."' and ID_Number!=''   order by Date_S desc, Time_S desc ";
			$Mysql_Query_Result = mysql_query($Pow_Windspeed_Query) or die(mysql_error());   
			$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
									
										
									
			if($Mysql_Record_Count >= 1){
        ?>
                    <tr>
                        <td class="tab-head-td" width="80px" align="left"><b>Date</b></td>
                        <td class="tab-head-td" width="80px" align="left"><b>Time</b></td>
                        <td class="tab-head-td" width="90px" align="left"><b>Power</b> kw</td>
                        <td class="tab-head-td" width="90px" align="left"><b>Wind Speed</b> m/s</td>
                       
                    </tr>
                    <?php
                       
										
                        while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){
							
                    ?>
								<tr>								
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 								
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>                 
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Windspeed']!=''?$Fetch_Result['Windspeed']:'0'?></td>
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