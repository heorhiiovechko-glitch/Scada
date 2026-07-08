  <!-- 
          Error Log
        -->
<?php 
	if ($XLS == 0 && $Mysql_Record_Count > 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel here</a>
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
                        <td class="tab-head-td" colspan="3"  align="center"><b>Error Log</b></td>
                    </tr>
                   <tr>
						<td class="tab-head-td" align="left" width="15%"><b>Customer</b></td>
						<td class="tab-head-td" align="left" width="15%"><b><? print_r($All_Devicename[1]);?></b></td>						
						<td>&nbsp;</td>
					</tr>
					<tr>					
						<td class="tab-head-td" align="left" width="10%"><b><?=$WTG_Label?></b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_WEG_No[1]);?></b></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="tab-head-td" align="left"><b>Site Location</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($Site_Location[1]);?></b></td>	               
						<td>&nbsp;</td>
					</tr>
					<tr>				
						<td class="tab-head-td" align="left"><b>LOC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="tab-head-td" align="left"><b>DOC</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($DOC[1]);?></b></td>			   
						<td>&nbsp;</td>
					</tr>
					<tr>					
						<td class="tab-head-td" align="left"><b>HTSC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       
						<td>&nbsp;</td>
					</tr>
					<tr style="border:0px"><td colspan="3" >&nbsp;</td></tr>
					<?php
					}
					?>
					<?php 
					if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr"  colspan="5" align="left">&nbsp;&nbsp;<b>Error Log Report</b></td>
					</tr>
					<?php 
					}
					?>
					
                    <tr>
                        <td class="tab-head-td" width="15px" align="left"><b>Date</b></td>
                         <td class="tab-head-td" width="15px" align="left"><b>Time</b></td> 
                        <td class="tab-head-td" width="60px" align="left"><b>Status</b></td>
			<td class="tab-head-td" width="20px" align="left"><b>Diff. Hr/Min</b></td>
                    </tr>
					<?php
						if($Mysql_Record_Count >= 1){
					?>
						<?php
							#
							#	Error Status from ERROR_DATA_F2
							#
							$All_Error_Date_Arr = array();
							$All_Error_Time_Arr = array();
							$All_Error_Arr = array();
							
							//$Mysql_Query_Error = "select Date_S,Time_S,Status from $Cook_Variable[7].error_data_f2 where IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc,Time_S desc";
			$Mysql_Query_Error = "SELECT A.IMEI,A.Record_Index, A.Date_F,A.Time_F,A.Status, subtime(A.Time_F,B.Time_F) AS timedifference FROM $Cook_Variable[7].device_data_f2 A Left JOIN $Cook_Variable[7].device_data_f2 B  ON B.Record_Index = (select Record_Index from $Cook_Variable[7].device_data_f2 where IMEI='".$IMEI."' and (Date_F >= '".$From."' and  Date_F <= '".$To."') and Record_Index < A.Record_Index order by Record_Index desc limit 1) where A.IMEI = '".$IMEI."' and (A.Date_F >= '".$From."' and  A.Date_F <= '".$To."') ORDER BY A.Record_Index desc";
			//echo $Mysql_Query_Error;if (!$Mysql_Query_Error_Result = $db->query($Mysql_Query_Error))            {                die($db->error);            }            if($Mysql_Query_Error_Result->num_rows >= 1)            {                while($Fetch_Error_Result = $Mysql_Query_Error_Result->fetch_array()) {					
										$All_Error_Date_Arr = date("d.m.Y",strtotime($Fetch_Error_Result['Date_F']));
										$All_Error_Time_Arr = date("H:i:s",strtotime($Fetch_Error_Result['Time_F']));
										$All_Error_Arr = $Fetch_Error_Result['Status'];
										$All_Error_Time_Diff = $Fetch_Error_Result['timedifference'];
								?>
								<tr>                       
									<td class="tab-head-td1" align="left" width="20%"><?=$All_Error_Date_Arr!=''?$All_Error_Date_Arr:'0'?></td>                                    
									<td class="tab-head-td1" align="left" width="20%"><?=$All_Error_Time_Arr!=''?$All_Error_Time_Arr:'0'?></td>                  
									<td class="tab-head-td1" align="left"><?=$All_Error_Arr!=''?$All_Error_Arr:'0'?></td> 
								<?php  
			if($All_Error_Arr=='M/C Running' || $All_Error_Arr=='RUN' ) {
			?>
			<td class="tab-head-td1" align="left" width="20%"><?=$All_Error_Time_Diff!=''?$All_Error_Time_Diff:'0'?></td>                  
			<?php 
			//echo $All_Error_Time_Diff;
				} else {
			?>
			<td class="tab-head-td1" align="left"></td>
			<?php
			}
			?> 
							   </tr>
						<?php
							
							$MI++;
						
								}
							}	
							else{
								echo $No_Records;
							}
						}	
						else{
							echo $No_Records;
						}
					?>
						
                </table>
                  
</td>
</tr>