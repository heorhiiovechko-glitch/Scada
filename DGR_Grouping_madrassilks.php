          <!-- 
            Daily Generation Report
        -->
	<?php
//error_reporting(-1);
$Mysql_Query = "select Group_Name,IMEI,Account_ID,Parent_ID from device_register where IMEI = '".$IMEI."'";
	if (!$Device_Query_Result = $db->query($Mysql_Query))            {                die($db->error);            }            if($Device_Query_Result->num_rows >= 1)            {              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
			$Group_Name = $Fetch_Result['Group_Name'];
			}
}
//echo $Group_Name;
	

		
//echo $_REQUEST['FType'] ."is format type";
	if ($XLS == 0){

if($Group_Name == '')
                  echo " ";

else {
?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">				<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />			<?php if($FType==1 || $FType==6){?>				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>			<?php  }if($FType==2){?>				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>			<?php  }if($FType==3){?>				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>			<?php  }if($FType==4){?>				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>			<?php  }if($FType==7 || $FType==8){?>				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>			<?php  }if($FType==10){?>				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>						<?php }?>			</td>
		</tr>
<?php
	}	}
?>					
	
       <tr>
            <td height="5px">&nbsp;</td>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">
	<?php



	if ($XLS == 1){//xls=1



	?>
 <tr>
							<td class="tab-head-td" colspan="10"  align="center"><b> <?=$Group_Name?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="10"  align="left"><b>Site:</b><?= implode(",",array_unique($Site_Location)) ?></td>
<tr style="border:0px"><td colspan="6" >&nbsp;</td></tr>

<?php 
		}
			if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr"  colspan="29" align="left">&nbsp;&nbsp;<b>DGR Report-<?=$Group_Name?></b></td>
					</tr>
					<?php 
					}
					?>
	<?php
if($Group_Name == '')
                  echo "No Groups Found";

else {

           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 33){//if p is set

		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date; 
		$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);

			$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,HTSC_No,LOC_No,capacity,State,IMEI,Group_Name from device_register where Group_Name='".$Group_Name."' order by Connect_Feeder DESC";			//echo $Device_Query;if (!$Device_Query_Result = $db->query($Device_Query))            {                die($db->error);            }            if($Device_Query_Result->num_rows >= 1)            {              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				$DGR_IMEI[$Fetch_Result['IMEI']]=$Fetch_Result['IMEI'];
				$Device_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
				$HTSC_No[$Fetch_Result['IMEI']] = $Fetch_Result['HTSC_No'];
				$Loc_No[$Fetch_Result['IMEI']] = $Fetch_Result['LOC_No'];
				$Capacity[$Fetch_Result['IMEI']] = $Fetch_Result['Capacity'];
				//$Sum_Capacity[$Fetch_Result['IMEI']] = round(($Fetch_Result['capacity']/1000),2);
				$Make[$Fetch_Result['IMEI']] = $Fetch_Result['Y1'];
				$Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
				$Site_Location[$Fetch_Result['IMEI']] = $Fetch_Result['Site_Location'];
				$Format[$Fetch_Result['IMEI']] = $Fetch_Result['Format_Type'];
				if($Fetch_Result['Format_Type']=='1'){
					$F1_IMEI[]=$Fetch_Result['IMEI'];
				}
				if($Fetch_Result['Format_Type']==2)
					$F2_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==3)
					$F3_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==4)
					$F4_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==6)
					$F6_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==7)
					$F7_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==8)
					$F8_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==9)
					$F9_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==10)
					$F10_IMEI[]=$Fetch_Result['IMEI'];
			}
		}
			
		
			
//print_r($Format_Type);
//print_r($IMEI_DGR);
				$Format_Type = array_unique($Format_Type);
//print_r($F1_IMEI);
	
				 if($Device_Query_Result->num_rows >= 1){//record count if
		?>                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<!--<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>-->
			<td class="tab-head-td" align="center" width="16px;"><b>Place</td>
			<td class="tab-head-td" align="center" width="16px;"><b>Loc No</td>  
			<td class="tab-head-td" align="center" width="16px;"><b>HTSC No</td> 
			<td class="tab-head-td" align="center" width="16px;"><b>Make</td>
			<td class="tab-head-td" align="center" width="16px;"><b>Capacity</td>  
			<td class="tab-head-td" align="center" width="16px;"><b>TNEB Import</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WEG'S Export</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>NET Generation</b></td>
			
                                           </tr>
                 			 
						<?php 
							$MI = 1;
						//print_r($DATE_F);
							$Tot_All_Generation=0;
							$Tot_BD_Hours=0;
							$Tot_GD_Hours=0;
							$Tot_Maint_Hours=0;
							$Tot_Lull_Hours=0;
							$Tot_Import_LCS=0;
							$Tot_Run_Hours=0;
							$Days=0;
$datediff = abs($From_D_Epoch - $To_D_Epoch);
     $diff= floor($datediff/(60*60*24));
$Daydiff=24*($diff+1)*count($DGR_IMEI);
  
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){
foreach($DGR_IMEI as $IMEI_Val){

							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));							if($Closing_Time[$IMEI_Val]=="00:00:00" || $Closing_Time[$IMEI_Val]=="0" ){							$Date_St=date("Y-m-d",strtotime($DATE_Val));							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));							$Yester_Stamp=$Date_Stamp;							$Yester_dmy=$Date_dmy;							}							elseif($Closing_Time[$IMEI_Val]>="10:00:00" || $Closing_Time[$IMEI_Val]=="10"){							$Date_St=date("Y-m-d",strtotime($DATE_Val)-86400);							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));							//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);							}							else{							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));							$Date_St=date("Y-m-d",strtotime($DATE_Val));							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);							}							
						if($Format[$IMEI_Val]=='1') {	$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F1_IMEI).")  and (Date_S= '".$Date_Stamp."')";	//echo $Gen_Mysql_Query;	if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {									$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=15000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];									}//end while								}							}//endif isset							//if(isset($F2_IMEI)){						if($Format[$IMEI_Val]=='2') {												$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F2_IMEI).")  and (Date_S= '".$Date_Stamp."')";				//echo $Gen_Mysql_Query;							if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {							$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Total_Gen1[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Gen2[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen1[$Fetch_Result['IMEI']][$DATE_Val]+$Gen2[$Fetch_Result['IMEI']][$DATE_Val];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=6000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];								}								}							}//endif isset							//if(isset($F3_IMEI)){						if($Format[$IMEI_Val]=='3') {				$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F3_IMEI).") and (Date_S= '".$Date_Stamp."')";								//echo $Gen_Mysql_Query;				if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=18000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];								}								}								}//endif isset								if($Format[$IMEI_Val]=='4') {												$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F4_IMEI).")  and (Date_S= '".$Date_Stamp."')";				//echo $Gen_Mysql_Query;							if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {								$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Total_Gen1[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Gen2[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen1[$Fetch_Result['IMEI']][$DATE_Val]+$Gen2[$Fetch_Result['IMEI']][$DATE_Val];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=6000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];								 }								}							}//endif isset												//if(isset($F6_IMEI)){						if($Format[$IMEI_Val]=='6') {$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F6_IMEI).")  and (Date_S= '".$Date_Stamp."')";	if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {								$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=15000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];								}//end while								}							}//endif isset														if($Format_Type== 7){if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {								$Windspeed[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['WindSpeed'];								$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=18000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								 $Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];								 }								}																}//endif isset														if($Format_Type== 8){								$Gen_Mysql_Query="select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F8_IMEI).")  and (Date_S= '".$Date_Stamp."')";if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {								$Windspeed[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['WindSpeed'];								$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=18000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								 $Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];								 }								}																}//endif isset													if($Format[$IMEI_Val]=='10') {$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (".implode(",",$F10_IMEI).")  and (Date_S= '".$Date_Stamp."')";					//echo $Gen_Mysql_Query;if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))            {                die($db->error);            }            if($Gen_Mysql_Query_Result->num_rows >= 1)            {                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {								$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];								$Array_Import[$Fetch_Result['IMEI']][$DATE_Val]=$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]<=500?$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];								$Array_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]>0 && $Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]<=6000?$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]:'0';								$Net_Gen[$Fetch_Result['IMEI']][$DATE_Val]=$Total_Gen[$Fetch_Result['IMEI']][$DATE_Val]-$Import_LCS[$Fetch_Result['IMEI']][$DATE_Val];								}//end while								}							}//endif isset						
						}//end foreach
						}
						
						foreach($Date_Array as $DATE_Val){
							foreach($DGR_IMEI as $IMEI_Val){
						?>

                        <tr>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              
				<!--<td class="tab-head-td1" align="left"><?=$Device_Name[$IMEI_Val]?></td>-->
				<td class="tab-head-td1" align="left"><?=$Site_Location[$IMEI_Val]?></td>
				<td class="tab-head-td1" align="left"><?=$Loc_No[$IMEI_Val]?></td>
				<td class="tab-head-td1" align="left"><?=$HTSC_No[$IMEI_Val]?></td>
				<td class="tab-head-td1" align="left"><?=$Make[$IMEI_Val]?></td>
				<td class="tab-head-td1" align="left"><?=round(($Capacity[$IMEI_Val]/1000),1)?></td>
				<td class="tab-head-td1" align="left"><?=($Import_LCS[$IMEI_Val][$DATE_Val]!= '' && $Import_LCS[$IMEI_Val][$DATE_Val] >=0 || $Import_LCS[$IMEI_Val][$DATE_Val] ==0)?round($Import_LCS[$IMEI_Val][$DATE_Val],2) : '000'?></td>                 
<?phpif($Format[$IMEI_Val]=='2' || $Format[$IMEI_Val]=='10') {?>              			<td class="tab-head-td1" align="left"><?=($Total_Gen[$IMEI_Val][$DATE_Val] >=0 && $Total_Gen[$IMEI_Val][$DATE_Val] <=(6000*($diff+1)))?round($Total_Gen[$IMEI_Val][$DATE_Val],2): '000'?></td>                  <?php} elseif($Format[$IMEI_Val]=='3' || $Format[$IMEI_Val]=='7' || $Format[$IMEI_Val]=='8') {?>              			<td class="tab-head-td1" align="left"><?=($Total_Gen[$IMEI_Val][$DATE_Val] >=0 && $Total_Gen[$IMEI_Val][$DATE_Val] <=(18000*($diff+1)))?round($Total_Gen[$IMEI_Val][$DATE_Val],2): '000'?></td>                  <?php} else {  ?>				<td class="tab-head-td1" align="left"><?=($Total_Gen[$IMEI_Val][$DATE_Val] >=0 && $Total_Gen[$IMEI_Val][$DATE_Val] <=(15000*($diff+1)))?round($Total_Gen[$IMEI_Val][$DATE_Val],2): '000'?></td>                  <?php}?>								<td class="tab-head-td1" align="left"><?=($Net_Gen[$IMEI_Val][$DATE_Val] != '' && $Net_Gen[$IMEI_Val][$DATE_Val] >=0)?round($Net_Gen[$IMEI_Val][$DATE_Val],2): '000'?></td>                  

				
              		                        </tr>
						<?php
								}

							}
						?>
							<td class="tab-head-td1" colspan='5' align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($Array_Import)>0? arraySumRecursive($Array_Import):'000'?></b></td>							<td class="tab-head-td1" align="left"><b><?=arraySumRecursive($Array_Gen)>0? round(arraySumRecursive($Array_Gen),2):'000' ?></b></td>													
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Net_Gen)>=0)?round(arraySumRecursive($Net_Gen),2):'000'?></b></td>
							
						</tr>

					</table>
         <?php //print_r($Export_C1);
				} // Mysql Record End
				else{
					echo $No_Records;
				}//ifelse end
		//	}//if p is set
}
         ?>
	<?php
	}//xls=1
	?>            </td>	
        </tr>