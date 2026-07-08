       <!-- 
          Alarm Log
        -->

	<?php 
error_reporting(-1);
	if ($XLS == 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->
<?php
				if($FType==10){
			?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel here</a>
			<?php
				
				}
				elseif($FType==3){
			?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel here</a>
			<?php
			
				}else{
			?>
	
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Excel here</a>
<?php
			}
			?>
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
                        <td class="tab-head-td" colspan="2"  align="center"><b>Alarm Log</b></td>
                    </tr>
                   <tr>
						<td class="tab-head-td" align="left" width="15%"><b>Customer</b></td>
						<td class="tab-head-td" align="left" width="15%"><b><? print_r($All_Devicename[1]);?></b></td>						
						
					</tr>
					<tr>					
						<td class="tab-head-td" align="left" width="10%"><b>WEG No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_WEG_No[1]);?></b></td>
						
					</tr>
					<tr>
						<td class="tab-head-td" align="left"><b>Site Location</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($Site_Location[1]);?></b></td>	               
						
					</tr>
					<tr>				
						<td class="tab-head-td" align="left"><b>LOC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   
						
					</tr>
					<tr>
						<td class="tab-head-td" align="left"><b>DOC</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($DOC[1]);?></b></td>			   
						
					</tr>
					<tr>					
						<td class="tab-head-td" align="left"><b>HTSC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       
						
					</tr>
					<tr style="border:0px"><td colspan="2" >&nbsp;</td></tr>
					<?php
					}
					?>
					<?php 
					if ($XLS == 0){
					?>
						<tr>
							<td class="tab-head-tr" colspan="3" align="left">&nbsp;&nbsp;<b>Alarm Log</b></td>
						</tr>
                        <tr>
                             <td class="tab-head-td" width="15px" align="left"><b>Date</b></td>
                             <td class="tab-head-td" width="15px" align="left"><b>Time</b></td> 
                             <td class="tab-head-td" width="60px" align="left"><b>Error Status</b></td>
				<td class="tab-head-td" width="20px" align="left"><b>Diff. Hr/Min</b></td>
                    	</tr>
					<?php 
					}
					?>
        <?php
		//echo $From;
//echo $To;
            if(isset($_REQUEST['p']) && $_REQUEST['p'] == 7){
	
				$All_Error_Date_Arr = array();
				$All_Error_Time_Arr = array();
				$All_Error_Arr = array();
				
				//$Mysql_Query_Error = "select Date_S,Time_S,Status from $Cook_Variable[7].$Table_Name where IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."')  order by Date_S desc,Time_S desc";
			$Mysql_Query_Error = "SELECT A.IMEI,A.Record_Index, A.Date_S,A.Time_S,A.Status, subtime(A.Time_S,B.Time_S) AS timedifference FROM $Cook_Variable[7].$Table_Name A Left JOIN $Cook_Variable[7].$Table_Name B  ON B.Record_Index = (select Record_Index from $Cook_Variable[7].$Table_Name where IMEI='".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') and Record_Index < A.Record_Index order by Record_Index desc limit 1) where A.IMEI = '".$IMEI."' and (A.Date_S >= '".$From_YMD."' and  A.Date_S <= '".$To_YMD."') ORDER BY A.Record_Index desc";
//echo $Mysql_Query_Error;
				if (!$Mysql_Query_Error_Result = $db->query($Mysql_Query_Error))
            {
                die($db->error);
            }

            if($Mysql_Query_Error_Result->num_rows >= 1)
            {
                while($Fetch_Error_Result = $Mysql_Query_Error_Result->fetch_array()) {
					$All_Error_Date_Arr = $Fetch_Error_Result['Date_S'];
							$All_Error_Time_Arr = $Fetch_Error_Result['Time_S'];
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
			<td class="tab-head-td1" align="left"><?=$All_Error_Time_Diff!==''?$All_Error_Time_Diff:'Nil'?></td>
			<?php 
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
			?>
						
					</table>
     
                  
            </td>
        </tr>