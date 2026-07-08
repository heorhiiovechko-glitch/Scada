<?php
//ini_set('max_execution_time', 3600);
//echo $_REQUEST['FType'] ."is format type";
	if ($XLS == 0){
?>
		<tr>
			<td colspan="4" align="left" style="font-size:small">
				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please click the below link to Download the excel Report</b><br /><br />
			<a href='channel3_ajax_selvatex.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
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
$Device_Query="select Device_Name,Format_Type,Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where IMEI='$IMEI'";
//echo $Device_Query;
		if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }

            if($Device_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				$DGR_IMEI=$Fetch_Result['IMEI'];
				$Device_Name = $Fetch_Result['Device_Name'];
				$Site_Location = $Fetch_Result['Site_Location'];
				$Format_Type = $Fetch_Result['Format_Type'];
				$Closing_Time = $Fetch_Result['Closing_Time'];
			}
		}
//echo $Format_Type;
	if ($XLS == 1){//xls=1
	?>
 <tr>
							<td class="tab-head-td" colspan="3"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="3"  align="left"><b>Site:</b><?= $Site_Location ?></td></tr>
<tr style="border:0px"><td colspan="3" >&nbsp;</td></tr>
<?php 
		}
			if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr"  colspan="3" align="left">&nbsp;&nbsp;<b>Daily Generation Detail Report</b></td>
					</tr>
					<?php 
					}
					
           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 1){
		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
	$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
				if($Device_Query_Result_Count >= 1){//record count if
		?>
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Export</b></td></tr>
<?php
							
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
													
			$Gen_Mysql_Query="select IMEI,Date_S, (SELECT RPhase_Current from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index Limit 1) as Gen1_Prod_Min,(SELECT RPhase_Current from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!=''  ORDER BY Record_Index DESC LIMIT 1) as Gen1_Prod_Max from $Cook_Variable[7].device_data_f2 where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) and ID_Number!='' ";
//echo $Gen_Mysql_Query;
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Prod_Max']-$Fetch_Result['Gen1_Prod_Min'];
									}//end while
								}
						?>
                        <tr>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              
				<td class="tab-head-td1" align="left"><?=$Device_Name?></td>
				<td class="tab-head-td1" align="left"><?=$Total_Gen[$DATE_Val] >=0 ?round($Total_Gen[$DATE_Val],2): 'Nil'?></td>                  
						</tr>
		<?php
						}
						?>
						<tr>
						<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>

						<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Total_Gen)!= '' && arraySumRecursive($Total_Gen)>=0 && arraySumRecursive($Total_Gen)<=15000*(($diff+1)*count($DGR_IMEI)))?round(arraySumRecursive($Total_Gen),2):'00' ?></b></td>
							
					</table>
         <?php //print_r($Export_C1);
				} // Mysql Record End
				else{
					echo $No_Records;
				}//ifelse end
         ?>
	<?php
	}//xls=1
	?>           
	</td>	
        </tr>