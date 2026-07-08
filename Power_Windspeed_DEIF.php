  <!-- 
            Power Vs WInd Speed
        -->
<?php 
	if ($XLS == 0){
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
                        <td class="tab-head-td" colspan="5" align="center"><b>Power Vs Wind Speed</b></td>
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
						<td  class="tab-head-tr"  colspan="6" align="left">&nbsp;&nbsp;<b>Power Vs Wind Speed</b></td>
					</tr>
					<?php 
					}
					?>
					
        <?php
$From_D_Epoch_Type7 = strtotime($_REQUEST['inputDate'])+(60*60*5.5);
$To_D_Epoch_Type7 = strtotime($_REQUEST['inputDate1'])+(60*60*5.5);
$From_Type7= date("Y-m-d",$From_D_Epoch_Type7);							
$To_Type7= date("Y-m-d",$To_D_Epoch_Type7);										

										

$Power_Windspeed_Query="select   distinct Time_S,Date_S,Power,Windspeed,Nacelle_Position,Status from $Cook_Variable[7].$Table_Name  where IMEI = '".$IMEI."' and (Date_S >= '".$From_Type7."' and  Date_S <= '".$To_Type7."') order by Date_S, Time_S";//echo"$Power_Windspeed_Query";
			if (!$Mysql_Query_Result = $db->query($Power_Windspeed_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
              
        ?>
                    <tr>
                        <td class="tab-head-td" width="80px" align="left"><b>Date</b></td>
                        <td class="tab-head-td" width="80px" align="left"><b>Time</b></td>
                        <td class="tab-head-td" width="90px" align="left"><b>Wind Speed</b></td>
                        <td class="tab-head-td" width="90px" align="left"><b>Power</b></td>
                        <td class="tab-head-td" width="90px" align="left"><b>Nacelle Position</b></td>
                        <td class="tab-head-td" width="90px" align="left"><b>Status</b></td>
                    </tr>
                    <?php
                        	
			while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
				if($Fetch_Result['Power'] != ''){
                    ?>
                    <tr>                     
                        <td class="tab-head-td1" align="left"><?=$Fetch_Result['Date_S']!=''?$Fetch_Result['Date_S']:'0'?></td>                     
                        <td class="tab-head-td1" align="left"><?=$Fetch_Result['Time_S']!=''?$Fetch_Result['Time_S']:'0'?></td>                     
                                    
                        <td class="tab-head-td1" align="left"><?=$Fetch_Result['Windspeed']!=''?$Fetch_Result['Windspeed']:'0'?>m/s</td>

		  <td class="tab-head-td1" align="left"><?=$Fetch_Result['Power']!=''?$Fetch_Result['Power']:'0'?>kw</td>    

		    <td class="tab-head-td1" align="left"><?=$Fetch_Result['Nacelle_Position']!=''?$Fetch_Result['Nacelle_Position']:'0'?></td>    

		   <td class="tab-head-td1" align="left"><?=$Fetch_Result['Status']!=''?$Fetch_Result['Status']:'0'?></td>    
                
                    </tr>
                    <?php
					}
                        
                    }
                    ?>
                  
                </table>
         <?php
         }
		 else{
			echo $No_Records;
		  }	 	 
		 
         ?>
          </td>
         </tr>