       <!-- 
          Event Log
        -->

	<?php 
	if ($XLS == 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />
	<?php 
		if($FType==1 || $FType==6){
			?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  
			}
			if($FType==2)
			{
				?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php 
			} if($FType==3){
				?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  
			} if($FType==7 || $FType==8){
				?>
				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==9){
				if($Cook_Variable[0]!='tirumala') {?>
				<a href='channel9new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
				<?php }
				}if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			
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
                        <td class="tab-head-td" colspan="2"  align="center"><b>Event Log</b></td>
                    </tr>
                   <tr>
						<td class="tab-head-td" align="left" width="15%"><b>Customer</b></td>
						<td class="tab-head-td" align="left" width="15%"><b><? print_r($All_Devicename[1]);?></b></td>						
						<td class="tab-head-td" align="left" ><b>&nbsp;</b></td>
					</tr>
					<tr>					
						<td class="tab-head-td" align="left" width="10%"><b>WEG No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_WEG_No[1]);?></b></td>
						<td class="tab-head-td" align="left" ><b>&nbsp;</b></td>
					</tr>
					<tr>
						<td class="tab-head-td" align="left"><b>Site Location</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($Site_Location[1]);?></b></td>	               
						<td class="tab-head-td" align="left" ><b>&nbsp;</b></td>
					</tr>
					<tr>				
						<td class="tab-head-td" align="left"><b>LOC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_LOC_No[1]);?></b></td>                   
						<td class="tab-head-td" align="left" ><b>&nbsp;</b></td>
					</tr>
					<tr>
						<td class="tab-head-td" align="left"><b>DOC</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($DOC[1]);?></b></td>			   
						<td class="tab-head-td" align="left" ><b>&nbsp;</b></td>
					</tr>
					<tr>					
						<td class="tab-head-td" align="left"><b>HTSC No</b></td>
						<td class="tab-head-td" align="left"><b><? print_r($All_HTSC_No[1]);?></b></td>       
						<td class="tab-head-td" align="left" ><b>&nbsp;</b></td>
					</tr>
					<tr style="border:0px"><td colspan="2" >&nbsp;</td></tr>
					<?php
					}
					?>
					<?php 
					if ($XLS == 0){
					?>
						<tr>
							<td class="tab-head-tr" colspan="3" align="left">&nbsp;&nbsp;<b>Event Log</b></td>
						</tr>
                        <tr>
                             <td class="tab-head-td" width="15px" align="left"><b>Date</b></td>
                             <td class="tab-head-td" width="15px" align="left"><b>Time</b></td> 
                             <td class="tab-head-td" width="70px" align="left"><b>Status</b></td>
                    	</tr>
					<?php 
					}
					?>
        <?php
		
            if(isset($_REQUEST['p'])){
				$All_Event_Date_Arr = array();
				$All_Event_Time_Arr = array();
				$All_Event_Arr = array();
				
				$Mysql_Query_Event = "select Date_S,Time_S,Status from $Cook_Variable[7].$Table_Name where IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."')  and Status not in ('Run','M/C Running','RUN','M/CRunning','RUN..') order by Date_S desc,Time_S desc";
//echo $Mysql_Query_Event;
			if (!$Mysql_Query_Event_Result = $db->query($Mysql_Query_Event))
            {
                die($db->error);
            }

            if($Mysql_Query_Event_Result->num_rows >= 1)
            {
                while($Fetch_Event_Result = $Mysql_Query_Event_Result->fetch_array()) {	
							$All_Event_Date_Arr = date("d.m.Y",strtotime($Fetch_Event_Result['Date_S']));
							$All_Event_Time_Arr = $Fetch_Event_Result['Time_S'];
							$All_Event_Arr = $Fetch_Event_Result['Status'];
					?>
                    <tr>                       
                        <td class="tab-head-td1" align="left" width="20%"><?=$All_Event_Date_Arr!=''?$All_Event_Date_Arr:'0'?></td>                                    
                        <td class="tab-head-td1" align="left" width="20%"><?=$All_Event_Time_Arr!=''?$All_Event_Time_Arr:'0'?></td>                  
                        <td class="tab-head-td1" align="left"><?=$All_Event_Arr!=''?$All_Event_Arr:'0'?></td>  
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