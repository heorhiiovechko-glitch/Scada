		 
		 
        <!-- 
            Power Vs WInd Speed
        -->

	<?php 
	if ($XLS == 0){
	?>
		<tr>			<td colspan="5" align="center" style="font-size:small">				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />--><?php if($FType==1 || $FType==6){?>				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==2){?>				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==3){?>				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==4){?>				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  } if($FType==7 || $FType==8){?>				<a href='channel8_sendan_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>			<?php  }if($FType==9){				if($Cook_Variable[0]!='tirumala') {?>				<a href='channel9new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>							<?php }				}if($FType==10){?>				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>						<?php }?>			</td>		</tr>
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
						<td class="tab-head-tr" colspan="4"  align="center"><b>Power Windspeed Report &nbsp;-&nbsp; <?=$IMEI?> </b></td>
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
							<td class="tab-head-tr" colspan="4" align="left">&nbsp;&nbsp;<b>Power Vs WInd Speed</b></td>
						</tr>
 					<?php 
					}
					?>
        <?php
		
        if(isset($_REQUEST['p']) && ($_REQUEST['p'] == 1 || $_REQUEST['p'] == 56)){

 if(isset($_REQUEST['p']) && ($_REQUEST['p'] == 1 )){
	$Pow_Windspeed_Query="select Power,Windspeed,Date_S,Time_S from $Cook_Variable[7].$Table_Name where  IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc, Time_S desc ";	//echo $Pow_Windspeed_Query; } elseif(isset($_REQUEST['p']) && ($_REQUEST['p'] == 56)){	 	 $Pow_Windspeed_Query="select @a:=@a+1 serial_number,Date_S, TIME(from_unixtime(ROUND(unix_timestamp(TIME_S) / (60*10)) * 60 * 10)) as TenMinutes, ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WindSpeed),2) as Wind_Speed  from $Cook_Variable[7].device_data_f7 , (SELECT @a:= 0) AS a where IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') and Active_Total_Gen_Import between 0 and 20000000  group by Date_S,TenMinutes";	//echo $Pow_Windspeed_Query; }
			if (!$Mysql_Query_Result = $db->query($Pow_Windspeed_Query))            {                die($db->error);            }            if($Mysql_Query_Result->num_rows >= 1)            {              			  
        ?>
                    <tr>
                        <td class="tab-head-td" width="80px" align="left"><b>Date</b></td>
                        <td class="tab-head-td" width="80px" align="left"><b>Time</b></td>
                        <td class="tab-head-td" width="90px" align="left"><b>Power</b> kw</td>
                        <td class="tab-head-td" width="90px" align="left"><b>Wind Speed</b> m/s</td>
                       
                    </tr>
                    <?php
                       
										
                        while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
							
                    ?>
								<tr>								
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td> 								        <?phpif(($_REQUEST['p'] == 56)){ ?>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['TenMinutes']!=''?$Fetch_Result['TenMinutes']:'0'?></td>         <?php }else{ ?>									<td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>          <?php} ?>
                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?></td>                         <?phpif(($_REQUEST['p'] == 56)){ ?>                                    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Wind_Speed']!=''?$Fetch_Result['Wind_Speed']:'0'?></td>        <?php }else{ ?>									 <td class="tab-head-td1" align="left"><?=$Fetch_Result['Windspeed']!=''?$Fetch_Result['Windspeed']:'0'?></td>         <?php} ?>
                                   
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
		
		