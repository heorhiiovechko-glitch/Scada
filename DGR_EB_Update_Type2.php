  <!-- 
            DGR EB Update
        -->
<?php
		$Status = NULL;
		$Date = date("Y-m-d",strtotime($_REQUEST['inputDate']));
		$Query_IMEI = base64_decode($_REQUEST['c1']);
		
		$Mysql_Query = "select * from $Cook_Variable[7].eb_update_data where IMEI = '$Query_IMEI' and Date_Stamp = '$Date'";
		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
		$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
		if($Mysql_Record_Count == 1){
				$Status = "Already Updated";
			while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){
				$EB1 = $Fetch_Result['EB1'];
				$EB2 = $Fetch_Result['EB2'];
				$EB3 = $Fetch_Result['EB3'];
				$Remarks = $Fetch_Result['Remarks'];
			}
		}
	if(isset($_POST['dgr-report'])){
		$EB1=$_REQUEST['EB1'];
		$EB2=$_REQUEST['EB2'];
		$EB3=$_REQUEST['EB3'];
		$Remarks=$_REQUEST['Remarks'];
		
		if($Mysql_Record_Count == 0){
			$Insert_Query ="Insert into $Cook_Variable[7].eb_update_data (IMEI,EB1,EB2,EB3, Remarks,Date_Stamp) values ('$Query_IMEI','$EB1','$EB2','$EB3','$Remarks','$Date')";
			$Insert_Result = mysql_query($Insert_Query)  or die (mysql_error());
			if($Insert_Result)
				$Msg = "EB data Inserted Successfully";
		}
		else{
				/*$Update_Query ="Update EB_UPDATE_DATA set EB1 = '$EB1',EB2 = '$EB2',EB3 = '$EB3' where IMEI = '$Query_IMEI' and Date_Stamp = '$Date'";
				$Update_Result = mysql_query($Update_Query)  or die (mysql_error());
				if($Update_Result)
					$Msg = "EB data Updated Successfully";*/
				$Status = "";
		}
	}  
?>
		
        <tr>
            <td width="100%">
        <?php
		
            if(isset($_REQUEST['p']) && $_REQUEST['p'] == 13){
        ?>
			<center><?php if(isset($Msg)) echo "<div class='service-out'>".$Msg."</div>"; ?></center>
			<form method="post">
                <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                    <tr height="30px;">
                        <td class="tab-head-td" colspan="2" align="left"><b>DGR Report</b>&nbsp;&nbsp;&nbsp;<?=$_REQUEST['inputDate']?></td>
                    </tr>
                   <tr>
                        <td class="tab-head-td" align="left" width="50%"><b>Generation (kwh) - EB</b></td>
                        <td class="tab-head-td" width="80px" align="left"><input class="server-report-txtarea1" name="EB1" id="EB1" value="<?=$EB1?>" <?=$EB1 != ''?"readonly='readonly'": ''?> /></td>
                   </tr>
                   <tr>
                        <td class="tab-head-td" width="80px" align="left"><b>Import (kwh) - EB</b></td>
                        <td class="tab-head-td" width="80px" align="left"><input class="server-report-txtarea1" name="EB2" id="EB2" value="<?=$EB2?>" <?=$EB2 != ''?"readonly='readonly'": ''?> /></td>
                   </tr>
                   <tr>
                        <td class="tab-head-td" width="80px" align="left"><b>RKVAH Import EB ( Units ) - EB</b></td>
                        <td class="tab-head-td" width="80px" align="left"><input class="server-report-txtarea1" name="EB3" id="EB2" value="<?=$EB3?>" <?=$EB3 != ''?"readonly='readonly'": ''?> /></td>
                   </tr>
                   <tr>
                        <td class="tab-head-td" width="80px" align="left"><b>Remarks</b></td>
                        <td class="tab-head-td" width="80px" align="left"><textarea class="server-report-txtarea" name="Remarks" id="Remarks" <?=$Remarks != ''?"readonly='readonly'": ''?> style="height:80px;" /><?=$Remarks?></textarea></td>
                   </tr>
                   <tr>
						<td class="tab-head-td-sr-inner" colspan="2" align="center">
						   <?php
							if($Status == NULL){
						   ?>
							<input type="submit" name="dgr-report" id="dgr-report" value="Update Data >>>" style="height:30px;">
						   <?php
						   }
						   ?>
						</td>
                   </tr>
                </table>
			</form>	
         <?php
	 	 }
		
         ?>
         </td>
         </tr>