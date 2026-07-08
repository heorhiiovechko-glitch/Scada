<?php
ini_set('max_execution_time', 3600);

if (!function_exists('dgr_escape_value')) {
	function dgr_escape_value($db, $value) {
		return method_exists($db, 'real_escape_string') ? $db->real_escape_string($value) : addslashes($value);
	}
}

if (!function_exists('dgr_sql_list')) {
	function dgr_sql_list($db, $values) {
		$out = array();
		foreach($values as $value) {
			if($value !== '' && $value !== null) {
				$out[] = "'".dgr_escape_value($db, $value)."'";
			}
		}
		return implode(',', array_unique($out));
	}
}

if (!function_exists('dgr_num')) {
	function dgr_num($value) {
		if($value === '' || $value === null || !is_numeric($value)) {
			return 0;
		}
		return (float)$value;
	}
}

if (!function_exists('dgr_extract_number')) {
	function dgr_extract_number($value) {
		if($value === '' || $value === null) {
			return null;
		}

		if(is_numeric($value)) {
			return (float)$value;
		}

		if(preg_match('/-?[0-9]+(?:\.[0-9]+)?/', (string)$value, $match)) {
			return (float)$match[0];
		}

		return null;
	}
}

if (!function_exists('dgr_diff')) {
	function dgr_diff($max, $min) {
		return dgr_num($max) - dgr_num($min);
	}
}

if (!function_exists('dgr_valid_range')) {
	function dgr_valid_range($value, $max) {
		$value = dgr_num($value);
		return ($value >= 0 && $value <= $max) ? $value : 0;
	}
}

if (!function_exists('dgr_sum_values')) {
	function dgr_sum_values($values) {
		$total = 0;
		if(is_array($values)) {
			foreach($values as $value) {
				$total += dgr_num($value);
			}
		}
		return $total;
	}
}

if (!function_exists('dgr_avg_values')) {
	function dgr_avg_values($values) {
		$total = 0;
		$count = 0;
		if(is_array($values)) {
			foreach($values as $value) {
				if($value !== '' && $value !== null && is_numeric($value)) {
					$total += (float)$value;
					$count++;
				}
			}
		}
		return $count > 0 ? ($total / $count) : 0;
	}
}

if (!function_exists('dgr_efficiency')) {
	function dgr_efficiency($produced, $producible) {
		$produced = dgr_num($produced);
		$producible = dgr_num($producible);

		return $producible > 0 ? (($produced / $producible) * 100) : 0;
	}
}

if (!function_exists('dgr_format_no_round')) {
	function dgr_format_no_round($value, $decimals = 2) {
		if($value === '' || $value === null || !is_numeric($value)) {
			return '000';
		}

		$factor = pow(10, $decimals);
		$negative = ((float)$value) < 0;
		$truncated = floor(abs((float)$value) * $factor) / $factor;

		return ($negative ? '-' : '').number_format($truncated, $decimals, '.', '');
	}
}

if (!function_exists('dgr_format_generation_value')) {
	function dgr_format_generation_value($value, $is_nil, $decimals = 2) {
		return $is_nil ? 'NIL' : dgr_format_no_round($value, $decimals);
	}
}


//echo $_REQUEST['FType'] ."is format type";

	if ($XLS == 0){
?>

		<tr>

			<td colspan="5" align="left" style="font-size:small">

				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please click the below link to Download the excel Report</b><br /><br />

			<?php if($FType==1 || $FType==6){?>

				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==2){?>

				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>



			<?php  }if($FType==3){?>

				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==4){?>

				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==10){?>

				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			

			<?php }if($FType==7 || $FType==8){?>

				<a href='channel8_renom_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			

			<?php }?>

			</td>

		</tr>

<?php

	}

?>	
<?php if ($XLS == 0){ ?>
<style>
	.dgr-report-table {
		width: 100%;
		overflow: hidden;
		background: #ffffff;
		border: 1px solid #d9e2ec;
		border-collapse: separate;
		border-radius: 8px;
		border-spacing: 0;
		box-shadow: 0 12px 30px rgba(31, 41, 55, 0.06);
		color: #1f2937;
		font-family: "Segoe UI", Tahoma, Arial, sans-serif;
		font-size: 12px;
	}

	.dgr-report-table .tab-head-tr,
	.dgr-report-table .tab-head-td {
		background: #103c6f !important;
		border: 1px solid #0f335e !important;
		color: #ffffff !important;
		font-weight: 700;
		letter-spacing: 0.02em;
		padding: 9px 10px !important;
		text-align: center;
	}

	.dgr-report-table .dgr-title {
		background: #174f8f !important;
		font-size: 13px;
		text-align: left;
	}

	.dgr-report-table .tab-head-td1 {
		background: #ffffff;
		border-bottom: 1px solid #e5edf5;
		color: #1f2937;
		padding: 8px 10px !important;
	}

	.dgr-report-table tr:nth-child(even) .tab-head-td1 {
		background: #f8fafc;
	}

	.dgr-report-table tr:hover .tab-head-td1 {
		background: #eef7f4;
	}

	.dgr-report-table .dgr-num {
		font-variant-numeric: tabular-nums;
		text-align: right;
		white-space: nowrap;
	}

	.dgr-report-table .dgr-total .tab-head-td1 {
		background: #eef2f7 !important;
		border-top: 2px solid #cbd5e1;
		font-weight: 700;
	}
</style>
<?php } ?>
   <tr>

            <td height="5px">&nbsp;</td>

        </tr>

        <tr>

            <td width="100%">

                <table width="100%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1 dgr-report-table">

	<?php
$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI,Db_Name from device_register where IMEI='$IMEI'";
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
				$Dbname = $Fetch_Result['Db_Name'];				

			}

		}
//echo $Format_Type;
if ($XLS == 1){//xls=1
	?>
 <tr>
							<td class="tab-head-td dgr-title" colspan="7"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="7"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<tr style="border:0px"><td colspan="7" >&nbsp;</td></tr>
<?php 
		}
			if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr dgr-title"  colspan="7" align="left">&nbsp;&nbsp;<b>Daily Generation Detail Report</b></td>
					</tr>
					<?php 
					}
					?>
	<?php
           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 10){//if p is set
		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
	$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
				
				if($Device_Query_Result->num_rows >= 1){//record count if
		?>
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Total kWh</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Total Hrs</b></td>  
                 	<td class="tab-head-td" align="center" width="16px;"><b>Run Hrs</b></td>                               
			<td class="tab-head-td" align="center" width="16px;"><b>Avg Windspeed</b></td>                                    
			<td class="tab-head-td" align="center" width="16px;"><b>Efficiency %</b></td>                                    
                      <!-- <td class="tab-head-td" align="center" width="16px;"><b>Lull Hrs</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>GA %</b></td> 
						 <td class="tab-head-td" align="center" width="16px;"><b>MA %</b></td> -->
						 
                    </tr>
						<?php 
							$MI = 1;
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
							$Daydiff=24*($diff+1);
 
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							$Date_Meta = array();
							$Date_Stamp_List = array();
							$Date_On_List = array();
							$Daily_Data_By_Date = array();
							$F7_Data_By_Date = array();
							$F11_Data_By_Date = array();
							$F11_Wind_By_Date = array();

							foreach($Date_Array as $DATE_Val){
								$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
								if($Closing_Time=="00:00:00" || $Closing_Time=="0" ){
									$Date_St=date("Y-m-d",strtotime($DATE_Val));
									$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
									$Yester_Stamp=$Date_Stamp;
								}
								elseif($Closing_Time>="10:00:00" || $Closing_Time=="10"){
									$Date_St=date("Y-m-d",strtotime($DATE_Val)-86400);
									$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
									$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
								}
								else{
									$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
									$Date_St=date("Y-m-d",strtotime($DATE_Val));
									$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
								}

								$DateeOn = date("j-n-y", strtotime($Date_Stamp));
								$Date_Meta[$DATE_Val] = array(
									"Date_St" => $Date_St,
									"Date_Stamp" => $Date_Stamp,
									"Yester_Stamp" => $Yester_Stamp,
									"DateeOn" => $DateeOn
								);
								$Date_Stamp_List[$Date_Stamp] = $Date_Stamp;
								$Date_On_List[$DateeOn] = $DateeOn;

								$Energy_Kwh[$DATE_Val] = 0;
								$Produced_Energy[$DATE_Val] = 0;
								$Producible_Energy[$DATE_Val] = 0;
								$Efficiency[$DATE_Val] = 0;
								$Total_hrs[$DATE_Val] = 0;
								$Run_Hours[$DATE_Val] = 0;
								$Line_Hours[$DATE_Val] = 0;
								$Import_LCS[$DATE_Val] = 0;
								$Avg_Windspeed[$DATE_Val] = 0;
								$Energy_NIL[$DATE_Val] = false;
								$Array_Energy_Kwh[$DATE_Val] = 0;
								$Array_Produced_Energy[$DATE_Val] = 0;
								$Array_Producible_Energy[$DATE_Val] = 0;
								$Array_Efficiency[$DATE_Val] = 0;
								$Array_Total_Hours[$DATE_Val] = 0;
								$Array_Run[$DATE_Val] = 0;
								$Array_Line[$DATE_Val] = 0;
								$Array_Import[$DATE_Val] = 0;
								$Array_Avg_Windspeed[$DATE_Val] = 0;
							}

							$Date_In_SQL = dgr_sql_list($db, array_values($Date_Stamp_List));
							$Date_On_In_SQL = dgr_sql_list($db, array_values($Date_On_List));
							$DGR_IMEI_SQL = dgr_escape_value($db, $DGR_IMEI);

							if($Date_In_SQL != "" && $Format_Type != 7 && $Format_Type != 11){
								$Daily_Query = "
									SELECT
										IMEI,
										Date_S,
										Windspeed,
										Gen1_Min,
										Gen1_Max,
										Gen2_Min,
										Gen2_Max,
										Run_Min,
										Run_Max,
										Gen1H_Min,
										Gen1H_Max,
										Gen2H_Min,
										Gen2H_Max,
										Line_Min,
										Line_Max,
										ABS(Import_Min) AS Import_Min,
										ABS(Import_Max) AS Import_Max
									FROM daily_data
									WHERE IMEI = '".$DGR_IMEI_SQL."'
									  AND Date_S IN (".$Date_In_SQL.")
									ORDER BY Date_S";
								if (!$Daily_Query_Result = $db->query($Daily_Query))
								{
									die($db->error);
								}
								while($Daily_Row = $Daily_Query_Result->fetch_array()) {
									$Daily_Data_By_Date[$Daily_Row['Date_S']] = $Daily_Row;
								}
							}

							if($Date_In_SQL != "" && $Format_Type == 7){
								$F7_Query = "
									SELECT
										Date_S,
										AVG(CASE WHEN Windspeed != '' THEN Windspeed + 0 END) AS Avg_Windspeed,
										MAX(CASE WHEN Active_Total_Gen_Export != '' THEN Active_Total_Gen_Export + 0 END) * 1000 AS Energy_Kwh,
										MIN(CASE WHEN Min3_Active_Power != '' AND Min3_Active_Power > '0' THEN Min3_Active_Power + 0 END) AS Run_Min,
										MAX(CASE WHEN Min3_Active_Power != '' AND Min3_Active_Power > '0' THEN Min3_Active_Power + 0 END) AS Run_Max,
										MIN(CASE WHEN Min3_Wind_Speed != '' THEN Min3_Wind_Speed + 0 END) AS Line_Min,
										MAX(CASE WHEN Min3_Wind_Speed != '' THEN Min3_Wind_Speed + 0 END) AS Line_Max,
										MIN(CASE WHEN Stopped_Hours != '' THEN Stopped_Hours + 0 END) AS Import_Min,
										MAX(CASE WHEN Stopped_Hours != '' THEN Stopped_Hours + 0 END) AS Import_Max,
										MIN(CASE WHEN Grid_failure_Hours != '' AND Grid_failure_Hours > '0' THEN Grid_failure_Hours + 0 END) AS Total_hrs_Min,
										MAX(CASE WHEN Grid_failure_Hours != '' AND Grid_failure_Hours > '0' THEN Grid_failure_Hours + 0 END) AS Total_hrs_Max
									FROM va_renom.device_data_f7
									WHERE IMEI = '".$DGR_IMEI_SQL."'
									  AND Date_S IN (".$Date_In_SQL.")
									GROUP BY Date_S
									ORDER BY Date_S";
								if (!$F7_Query_Result = $db->query($F7_Query))
								{
									die($db->error);
								}
								while($F7_Row = $F7_Query_Result->fetch_array()) {
									$F7_Data_By_Date[$F7_Row['Date_S']] = $F7_Row;
								}
							}

							if($Date_In_SQL != "" && $Format_Type == 11){
								$F11_Query = "
									SELECT
										Date_S,
										0 AS Avg_Windspeed,
										MAX(CASE WHEN Phase2_kvar != '' THEN Phase2_kvar + 0 END) AS Produced_Energy,
										MAX(CASE WHEN Bridge21_temp != '' THEN Bridge21_temp + 0 END) AS Producible_Energy,
										MAX(CASE WHEN Bridge21_temp != '' THEN Bridge21_temp + 0 END) * 1000 AS Energy_Kwh,
										MIN(CASE WHEN Dummy22 != '' AND (Dummy22 + 0) > 0 THEN Dummy22 + 0 END) AS Run_Min,
										MAX(CASE WHEN Dummy22 != '' THEN Dummy22 + 0 END) AS Run_Max,
										MIN(CASE WHEN Dummy14 != '' AND (Dummy14 + 0) > 0 THEN Dummy14 + 0 END) AS Total_hrs_Min,
										MAX(CASE WHEN Dummy14 != '' THEN Dummy14 + 0 END) AS Total_hrs_Max,
										MIN(CASE WHEN dummy17 != '' AND (dummy17 + 0) > 0 THEN dummy17 + 0 END) AS Line_Min,
										MAX(CASE WHEN dummy17 != '' THEN dummy17 + 0 END) AS Line_Max,
										MIN(CASE WHEN dummy16 != '' AND (dummy16 + 0) > 0 THEN dummy16 + 0 END) AS Import_Min,
										MAX(CASE WHEN dummy16 != '' THEN dummy16 + 0 END) AS Import_Max
									FROM va_powercon.device_data_f11
									WHERE IMEI = '".$DGR_IMEI_SQL."'
									  AND Date_S IN (".$Date_In_SQL.")
									GROUP BY Date_S
									ORDER BY Date_S";
								if (!$F11_Query_Result = $db->query($F11_Query))
								{
									die($db->error);
								}
								while($F11_Row = $F11_Query_Result->fetch_array()) {
									$F11_Data_By_Date[$F11_Row['Date_S']] = $F11_Row;
								}

								$F11_Wind_Query = "
									SELECT
										Date_S,
										Bridge1_dcv
									FROM va_powercon.device_data_f11
									WHERE IMEI = '".$DGR_IMEI_SQL."'
									  AND Date_S IN (".$Date_In_SQL.")
									  AND Bridge1_dcv != ''
									ORDER BY Date_S";
								if (!$F11_Wind_Query_Result = $db->query($F11_Wind_Query))
								{
									die($db->error);
								}
								while($F11_Wind_Row = $F11_Wind_Query_Result->fetch_array()) {
									$Wind_Date = $F11_Wind_Row['Date_S'];
									$Wind_Value = dgr_extract_number($F11_Wind_Row['Bridge1_dcv']);

									if($Wind_Value !== null && $Wind_Value > 0) {
										if(!isset($F11_Wind_By_Date[$Wind_Date])) {
											$F11_Wind_By_Date[$Wind_Date] = array("sum" => 0, "count" => 0);
										}

										$F11_Wind_By_Date[$Wind_Date]["sum"] += $Wind_Value;
										$F11_Wind_By_Date[$Wind_Date]["count"]++;
									}
								}
							}

							foreach($Date_Array as $DATE_Val){
								$Date_Stamp = $Date_Meta[$DATE_Val]["Date_Stamp"];
								$Fetch_Result = array();

								if(isset($Daily_Data_By_Date[$Date_Stamp])) {
									$Fetch_Result = $Daily_Data_By_Date[$Date_Stamp];

									if($Format_Type == 2 || $Format_Type == 4) {
										$Energy_Kwh[$DATE_Val] = dgr_diff($Fetch_Result['Gen1_Max'], $Fetch_Result['Gen1_Min']) + dgr_diff($Fetch_Result['Gen2_Max'], $Fetch_Result['Gen2_Min']);
										$Run_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Gen1H_Max'], $Fetch_Result['Gen1H_Min']) + dgr_diff($Fetch_Result['Gen2H_Max'], $Fetch_Result['Gen2H_Min']);
									}
									elseif($Format_Type == 3) {
										$Energy_Kwh[$DATE_Val] = dgr_diff($Fetch_Result['Gen1_Max'], $Fetch_Result['Gen1_Min']);
										$Run_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Gen1H_Max'], $Fetch_Result['Gen1H_Min']) + dgr_diff($Fetch_Result['Gen2H_Max'], $Fetch_Result['Gen2H_Min']);
									}
									elseif($Format_Type == 8) {
										$Energy_Kwh[$DATE_Val] = dgr_num($Fetch_Result['Gen1_Max']);
										$Run_Hours[$DATE_Val] = dgr_num($Fetch_Result['Run_Max']);
									}
									elseif($Format_Type == 10) {
										$Energy_Kwh[$DATE_Val] = dgr_diff($Fetch_Result['Gen1_Max'], $Fetch_Result['Gen1_Min']);
										$Run_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Gen1H_Max'], $Fetch_Result['Gen1H_Min']) + dgr_diff($Fetch_Result['Gen2H_Max'], $Fetch_Result['Gen2H_Min']);
									}
									else {
										$Energy_Kwh[$DATE_Val] = dgr_diff($Fetch_Result['Gen1_Max'], $Fetch_Result['Gen1_Min']);
										$Run_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Gen1H_Max'], $Fetch_Result['Gen1H_Min']);
									}

									$Produced_Energy[$DATE_Val] = $Energy_Kwh[$DATE_Val];
									$Producible_Energy[$DATE_Val] = 0;
									$Total_hrs[$DATE_Val] = 24;
									$Line_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Line_Max'], $Fetch_Result['Line_Min']);
									$Import_LCS[$DATE_Val] = ($Format_Type == 8) ? dgr_num($Fetch_Result['Import_Max']) : dgr_diff($Fetch_Result['Import_Max'], $Fetch_Result['Import_Min']);
									$Avg_Windspeed[$DATE_Val] = dgr_num($Fetch_Result['Windspeed']);
								}
								elseif($Format_Type == 7 && isset($F7_Data_By_Date[$Date_Stamp])) {
									$Fetch_Result = $F7_Data_By_Date[$Date_Stamp];
									$Energy_Kwh[$DATE_Val] = dgr_num($Fetch_Result['Energy_Kwh']);
									$Produced_Energy[$DATE_Val] = $Energy_Kwh[$DATE_Val];
									$Producible_Energy[$DATE_Val] = 0;
									$Run_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Run_Max'], $Fetch_Result['Run_Min']);
									$Total_hrs[$DATE_Val] = dgr_diff($Fetch_Result['Total_hrs_Max'], $Fetch_Result['Total_hrs_Min']);
									$Line_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Line_Max'], $Fetch_Result['Line_Min']);
									$Import_LCS[$DATE_Val] = dgr_diff($Fetch_Result['Import_Max'], $Fetch_Result['Import_Min']);
									$Avg_Windspeed[$DATE_Val] = dgr_num($Fetch_Result['Avg_Windspeed']);
								}
								elseif($Format_Type == 11 && isset($F11_Data_By_Date[$Date_Stamp])) {
									$Fetch_Result = $F11_Data_By_Date[$Date_Stamp];
									$Energy_Kwh[$DATE_Val] = dgr_num($Fetch_Result['Energy_Kwh']);
									$Produced_Energy[$DATE_Val] = dgr_num($Fetch_Result['Produced_Energy']);
									$Producible_Energy[$DATE_Val] = dgr_num($Fetch_Result['Producible_Energy']);
									$Run_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Run_Max'], $Fetch_Result['Run_Min']);
									$Total_hrs[$DATE_Val] = dgr_diff($Fetch_Result['Total_hrs_Max'], $Fetch_Result['Total_hrs_Min']);
									$Line_Hours[$DATE_Val] = dgr_diff($Fetch_Result['Line_Max'], $Fetch_Result['Line_Min']);
									$Import_LCS[$DATE_Val] = dgr_diff($Fetch_Result['Import_Max'], $Fetch_Result['Import_Min']);
									if(isset($F11_Wind_By_Date[$Date_Stamp]) && $F11_Wind_By_Date[$Date_Stamp]["count"] > 0) {
										$Avg_Windspeed[$DATE_Val] = $F11_Wind_By_Date[$Date_Stamp]["sum"] / $F11_Wind_By_Date[$Date_Stamp]["count"];
									}
									else {
										$Avg_Windspeed[$DATE_Val] = dgr_num($Fetch_Result['Avg_Windspeed']);
									}
								}

								$Energy_Kwh[$DATE_Val] = $Energy_Kwh[$DATE_Val] >= 0 ? $Energy_Kwh[$DATE_Val] : 0;
								if($Energy_Kwh[$DATE_Val] > 60000) {
									$Energy_NIL[$DATE_Val] = true;
									$Energy_Kwh[$DATE_Val] = 0;
									$Produced_Energy[$DATE_Val] = 0;
									$Producible_Energy[$DATE_Val] = 0;
								}
								$Efficiency[$DATE_Val] = dgr_efficiency($Produced_Energy[$DATE_Val], $Producible_Energy[$DATE_Val]);
								$Total_hrs[$DATE_Val] = ($Total_hrs[$DATE_Val] == 25) ? 24 : dgr_valid_range($Total_hrs[$DATE_Val], 25);
								$Run_Hours[$DATE_Val] = ($Run_Hours[$DATE_Val] == 25) ? 24 : dgr_valid_range($Run_Hours[$DATE_Val], 25);
								$Line_Hours[$DATE_Val] = ($Line_Hours[$DATE_Val] == 25) ? 24 : dgr_valid_range($Line_Hours[$DATE_Val], 25);
								$Import_LCS[$DATE_Val] = ($Import_LCS[$DATE_Val] == 25) ? 24 : dgr_valid_range($Import_LCS[$DATE_Val], 25);
								$Avg_Windspeed[$DATE_Val] = $Avg_Windspeed[$DATE_Val] >= 0 ? $Avg_Windspeed[$DATE_Val] : 0;

								$Array_Energy_Kwh[$DATE_Val] = $Energy_Kwh[$DATE_Val];
								$Array_Produced_Energy[$DATE_Val] = $Produced_Energy[$DATE_Val];
								$Array_Producible_Energy[$DATE_Val] = $Producible_Energy[$DATE_Val];
								$Array_Efficiency[$DATE_Val] = $Efficiency[$DATE_Val];
								$Array_Total_Hours[$DATE_Val] = $Total_hrs[$DATE_Val];
								$Array_Run[$DATE_Val] = $Run_Hours[$DATE_Val];
								$Array_Line[$DATE_Val] = $Line_Hours[$DATE_Val];
								$Array_Import[$DATE_Val] = $Import_LCS[$DATE_Val];
								$Array_Avg_Windspeed[$DATE_Val] = $Avg_Windspeed[$DATE_Val];
							}

							if(false){
							foreach($Date_Array as $DATE_Val){
							
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time=="00:00:00" || $Closing_Time=="0" ){
								$Date_St=date("Y-m-d",strtotime($DATE_Val));
								$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
								$Yester_Stamp=$Date_Stamp;
								$Yester_dmy=$Date_dmy;
							}
							elseif($Closing_Time>="10:00:00" || $Closing_Time=="10"){
								$Date_St=date("Y-m-d",strtotime($DATE_Val)-86400);
								$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
								$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
								//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}
							else{
								$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
								$Date_St=date("Y-m-d",strtotime($DATE_Val));
								$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
								$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							}
							
							//echo $DATE_Val;

							if($Format_Type== 1){
				
				$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
//echo $Gen_Mysql_Query;
					if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
								
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'500'?'24':$Gen1[$DATE_Val];	
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Gen1[$DATE_Val]);
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								//$Loss_GF = $Total_Gen[$DATE_Val]/(
									}//end while

								}

							}//endif isset

				if($Format_Type== 2){

						$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {							 
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen1[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run_Hours[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
								$Total_Gen[$DATE_Val]=$Total_Gen1[$DATE_Val]+$Gen2[$DATE_Val];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=6000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]>'24' && $Run_Hours[$DATE_Val]<'500'?'24':$Run_Hours[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								

	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }

while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {	
	$Error_Type[$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$DATE_Val]=($BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?$BD_Hours[$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$DATE_Val]=($GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=25)?$GD_Hours[$DATE_Val] : '0';
}

	}//ENDWHILE			
}//end while

								}	

							$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Lull_Hours[$DATE_Val]= (24 * 3600) - (($Run_Hours[$DATE_Val]* 3600) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);

								$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';				
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;						
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
								

											}//endif isset

							if($Format_Type== 3){

								

							$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";								//echo $Gen_Mysql_Query;//echo $Gen_Mysql_Query;
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {						
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=18000?$Total_Gen[$DATE_Val]:'0';
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);

							$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]>'24' && $Run_Hours[$DATE_Val]<'500'?'24':$Run_Hours[$DATE_Val];
							$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
							
	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }

while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {	
	$Error_Type[$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$DATE_Val]=($BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?$BD_Hours[$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$DATE_Val]=($GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=25)?$GD_Hours[$DATE_Val] : '0';
}

	}//ENDWHILE			
$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Lull_Hours[$DATE_Val]= 24 - ($Run_Hours[$DATE_Val] +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);

								//$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');

								if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								

								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;

								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];

									}//end while

								}
								}//endif isset

							if($Format_Type== 4){

						$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {							 
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen1[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run_Hours[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
								$Total_Gen[$DATE_Val]=$Total_Gen1[$DATE_Val]+$Gen2[$DATE_Val];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]>'24' && $Run_Hours[$DATE_Val]<'50'?'24':$Run_Hours[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								

/*$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }

while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {	
	$Error_Type[$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$DATE_Val]=($BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?$BD_Hours[$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$DATE_Val]=($GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=25)?$GD_Hours[$DATE_Val] : '0';
}

	}  */ //ENDWHILE			
}//end while

								}	

							$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Lull_Hours[$DATE_Val]= (24 * 3600) - (($Run_Hours[$DATE_Val]* 3600) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);

								$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';				
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;						
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
								

											}//endif isset

				
							if($Format_Type== 6){

								$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {						
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Gen1[$DATE_Val]);
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';																
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
									}//end while

								}


							}//endif isset
						if($Format_Type== 7){


//$Mysql_Query = "select * from va_renom.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' and Date='".date("j-n-y")."' order by Record_Index desc limit 1";echo $Mysql_Query;
$date__stamp=date_create($Date_Stamp);
$DateeOn = date_format($date__stamp,"j-n-y");
//echo $Date_Stamp."@".$DateeOn.";";

$Gen_Mysql_Query="Select IMEI,Date,Windspeed,(Select Min(Active_Total_Gen_Export) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Active_Total_Gen_Export != '') as Gen1_Min,(Select (Max(Active_Total_Gen_Export)*1000) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Active_Total_Gen_Export != '') as Gen1_Max,(Select Min(Min3_Active_Power) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Min3_Active_Power > '0' and Min3_Active_Power != '') as Run_Min,(Select Max(Min3_Active_Power) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Min3_Active_Power > '0' and Min3_Active_Power != '') as Run_Max,(Select Min(Min3_Wind_Dir) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Min3_Wind_Dir != '') as Gen1H_Min,(Select Max(Min3_Wind_Dir) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Min3_Wind_Dir != '') as Gen1H_Max,(Select Min(Min3_Wind_Speed) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Min3_Wind_Speed != '') as Line_Min,(Select Max(Min3_Wind_Speed) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Min3_Wind_Speed != '') as Line_Max,(Select Min(Stopped_Hours) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Stopped_Hours != '') as Import_Min,(Select Max(Stopped_Hours) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Stopped_Hours != '') as Import_Max,(Select Min(Grid_failure_Hours) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Grid_failure_Hours != '' and Grid_failure_Hours > '0'  ) as Total_hrs_Min,(Select Max(Grid_failure_Hours) from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Date='".$DateeOn."' and Grid_failure_Hours != ''  and Grid_failure_Hours > 0) as Total_hrs_Max  from va_renom.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."')  order by Date_S desc limit 1";

								//$Gen_Mysql_Query="select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
								
							//	echo $Gen_Mysql_Query;
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }
//echo $Gen_Mysql_Query;
            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
               /* while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=50?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']*1000;
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=150000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$BD_Hours[$DATE_Val]=$Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$Line_Hours[$DATE_Val] =$Fetch_Result['Line_Max']-$Fetch_Result['Line_Min'];
								//$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								//$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$Array_Line[$DATE_Val]=$Line_Hours[$DATE_Val]>0 && $Line_Hours[$DATE_Val]<=25?$Line_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Lull_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$BD_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';
								 							
								 }*/
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
						
							$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max'] - $Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
									//echo $Array_Import[$DATE_Val];
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=20000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run_Max'] - $Fetch_Result['Run_Min'];
								$Total_hrs[$DATE_Val]=$Fetch_Result['Total_hrs_Max'] - $Fetch_Result['Total_hrs_Min'];
								$Line_Hours[$DATE_Val]=$Fetch_Result['Line_Max'] - $Fetch_Result['Line_Min'];
								//echo $Total_hrs[$DATE_Val]."@".$Run_Hours[$DATE_Val].";";
								$BD_Hours[$DATE_Val]=$Fetch_Result['Gen1H_Max'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$Array_Line[$DATE_Val]=$Line_Hours[$DATE_Val]>0 && $Line_Hours[$DATE_Val]<=25?$Line_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Lull_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$BD_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								 }		 
								}
									
							}//endif isset

							if($Format_Type== 8){

					$Gen_Mysql_Query="select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run_Max'];
								$BD_Hours[$DATE_Val]=$Fetch_Result['Gen1H_Max'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Lull_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$BD_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								 }
								}
									
							}//endif isset		

							if($Format_Type== 10){
									$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
				
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
							
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=6000?$Total_Gen[$DATE_Val]:'0';
								$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
								$Gen1[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Gen1[$DATE_Val]);							
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';
				}
			}
			
							}//endif isset
							
							
							if($Format_Type== 11){
								//Run Hours - Dummy 22
								//Line Ok Hrs - Dummy17
								//No Service hrs - Dummy16

								$Gen_Mysql_Query = " 
												SELECT
													IMEI,
													Date_S,
													MIN(dummy22) AS Run_Min,
													MAX(dummy22) AS Run_Max,
													MIN(dummy14) AS Total_hrs_Min,
													MAX(dummy14) AS Total_hrs_Max,
													MIN(Phase3_kw) AS Gen1_Min, 
													MAX(Phase3_kw) AS Gen1_Max,
													MIN(dummy19) AS Gen1H_Min,
													MAX(dummy19) AS Gen1H_Max,
													MIN(dummy17) AS Line_Min,
													MAX(dummy17) AS Line_Max,
													MIN(dummy16) AS Import_Min,
													MAX(dummy16) AS Import_Max,
													Phase3_kva AS Yest_Energy
												FROM va_powercon.device_data_f11
												WHERE IMEI = ".$DGR_IMEI."
												  AND Date_S = '".$Date_Stamp."'
												";
			//echo $Gen_Mysql_Query;
			
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
					$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
					$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=25?$Import_LCS[$DATE_Val]:'0';

					$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
					$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=6000?$Total_Gen[$DATE_Val]:'0';
					$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
					$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
					$Gen1[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
					$Total_hrs[$DATE_Val]=$Fetch_Result['Total_hrs_Max'] - $Fetch_Result['Total_hrs_Min'];
					$Line_Hours[$DATE_Val]=$Fetch_Result['Line_Max'] - $Fetch_Result['Line_Min'];

					$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
					$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
					if($Lull_Hours[$DATE_Val]==(-1))
					$Lull_Hours[$DATE_Val]=0;
					$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
					$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
					$Array_Line[$DATE_Val]=$Line_Hours[$DATE_Val]>0 && $Line_Hours[$DATE_Val]<=25?$Line_Hours[$DATE_Val]:'0';
					$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
					$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
					$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
					$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
					$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
					$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Gen1[$DATE_Val]);							
					$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
					//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
					$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
					$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';
					$YestEnergy[$DATE_Val] = $Fetch_Result['Yest_Energy'];
					$Array_YestEnergy[$DATE_Val] = $YestEnergy[$DATE_Val];
				}
			}
	}//endif isset
}//end foreach
							}

						foreach($Date_Array as $DATE_Val){
							?>
                        <tr>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              
							<td class="tab-head-td1" align="left"><?=$Device_Name?></td>
							
							<td class="tab-head-td1 dgr-num"><?=dgr_format_generation_value($Energy_Kwh[$DATE_Val], $Energy_NIL[$DATE_Val], 2)?> </td>

							<td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Total_hrs[$DATE_Val], 2)?></td>

							<td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Run_Hours[$DATE_Val], 2)?></td>                               
							
							<td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Avg_Windspeed[$DATE_Val], 2)?></td> 

							<td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Efficiency[$DATE_Val], 2)?></td> 
                        </tr>
						<?php
							}
						?>

						<tr class="dgr-total">
							<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_sum_values($Array_Energy_Kwh), 2)?></b></td>
							<td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_sum_values($Array_Total_Hours), 2)?></b></td>
							<td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_sum_values($Array_Run), 2)?></b></td>
							<td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_avg_values($Array_Avg_Windspeed), 2)?></b></td>
							<td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_efficiency(dgr_sum_values($Array_Produced_Energy), dgr_sum_values($Array_Producible_Energy)), 2)?></b></td>
							
	</tr>



					</table>

         <?php 
				} // Mysql Record End

				else{

					echo $No_Records;

				}//ifelse end

		//	}//if p is set

         ?>

	<?php

	}//xls=1

	?>            </td>	

        </tr>
