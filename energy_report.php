<?php

if(!function_exists('Energy_Report_Parse_Number')){
	function Energy_Report_Parse_Number($value){
		$value = trim(str_replace(array(',', 'm/s', 'MWh', 'kWh', '#'), '', $value));
		if($value === '' || !is_numeric($value)){
			return null;
		}
		return (float)$value;
	}
}

if(!function_exists('Energy_Report_Format_Number')){
	function Energy_Report_Format_Number($value, $decimals = 2){
		if($value === null || $value === ''){
			return '';
		}
		return number_format((float)$value, $decimals, '.', '');
	}
}

if(!function_exists('Energy_Report_SQL_List')){
	function Energy_Report_SQL_List($db, $values){
		$out = array();
		foreach($values as $value){
			if($value !== ''){
				$out[] = "'".$db->real_escape_string($value)."'";
			}
		}
		return implode(",", $out);
	}
}

if(!function_exists('Energy_Report_Limit_Hours')){
	function Energy_Report_Limit_Hours($value){
		if($value === null || $value === ''){
			return null;
		}
		$value = (float)$value;
		if($value < 0){
			return 0;
		}
		if($value > 24){
			return 24;
		}
		return $value;
	}
}

if(!function_exists('Energy_Report_Hour_Delta')){
	function Energy_Report_Hour_Delta($values){
		$Min_Value = null;
		$Max_Value = null;
		foreach($values as $value){
			$Number_Value = Energy_Report_Parse_Number($value);
			if($Number_Value === null){
				continue;
			}
			if($Max_Value === null || $Number_Value > $Max_Value){
				$Max_Value = $Number_Value;
			}
			if($Number_Value != 0 && ($Min_Value === null || $Number_Value < $Min_Value)){
				$Min_Value = $Number_Value;
			}
		}
		if($Max_Value === null || $Min_Value === null){
			return null;
		}
		$Delta_Value = $Max_Value - $Min_Value;
		if($Delta_Value == 0){
			return null;
		}
		return Energy_Report_Limit_Hours($Delta_Value);
	}
}

$Energy_Report_Data = array();
$Energy_Type11_IMEIs = array();
$Energy_Device_Names = array();
$Energy_From_Date = $db->real_escape_string(date("Y-m-d", strtotime($_REQUEST['inputDate'])));
$Energy_To_Date = $db->real_escape_string(date("Y-m-d", strtotime($_REQUEST['inputDate1'])));
$Energy_Database_Name = preg_replace('/[^A-Za-z0-9_]/', '', $Cook_Variable[7]);

if(isset($Report_Device_List) && is_array($Report_Device_List)){
	foreach($Report_Device_List as $Energy_Device_Row){
		if((int)$Energy_Device_Row['Format_Type'] != 11){
			continue;
		}
		$Energy_Device_IMEI = trim($Energy_Device_Row['IMEI']);
		if($Energy_Device_IMEI == '' || isset($Energy_Device_Names[$Energy_Device_IMEI])){
			continue;
		}
		$Energy_Type11_IMEIs[] = $Energy_Device_IMEI;
		$Energy_Device_Names[$Energy_Device_IMEI] = ($Energy_Device_Row['Device_Name'] != '' ? $Energy_Device_Row['Device_Name'] : $Energy_Device_IMEI);
	}
}

$Energy_Start_Timestamp = strtotime($_REQUEST['inputDate']);
$Energy_End_Timestamp = strtotime($_REQUEST['inputDate1']);
if($Energy_Start_Timestamp !== false && $Energy_End_Timestamp !== false){
	foreach($Energy_Type11_IMEIs as $Energy_Device_IMEI){
		for($Energy_Date_Timestamp = $Energy_Start_Timestamp; $Energy_Date_Timestamp <= $Energy_End_Timestamp; $Energy_Date_Timestamp += 86400){
			$Energy_Report_Date = date("Y-m-d", $Energy_Date_Timestamp);
			$Energy_Report_Key = $Energy_Device_IMEI."_".$Energy_Report_Date;
			$Energy_Report_Data[$Energy_Report_Key] = array(
				'device_name' => $Energy_Device_Names[$Energy_Device_IMEI],
				'date_s' => $Energy_Report_Date,
				'wind_sum' => 0,
				'wind_count' => 0,
				'produced_energy' => null,
				'producible_energy' => null,
				'total_hours_values' => array(),
				'run_hours_values' => array()
			);
		}
	}
}

if(count($Energy_Type11_IMEIs) >= 1 && $Energy_Database_Name != ""){
	$Energy_IMEI_List_SQL = Energy_Report_SQL_List($db, $Energy_Type11_IMEIs);
	$Energy_Report_Query = "select IMEI,Date_S,Time_S,Bridge1_dcv AS Avg_Wind_Speed,Phase3_kva AS Produced_Energy,Cabinet2_temp AS Producible_Energy,Dummy14 AS Total_Hours_Source,Dummy22 AS Run_Hours_Source,Record_Index from ".$Energy_Database_Name.".device_data_f11 where IMEI IN (".$Energy_IMEI_List_SQL.") and Date_S >= '".$Energy_From_Date."' and Date_S <= '".$Energy_To_Date."' order by field(IMEI,".$Energy_IMEI_List_SQL."), Date_S asc, Record_Index asc";
	if (!$Energy_Report_Result = $db->query($Energy_Report_Query))
	{
		die($db->error);
	}
	if($Energy_Report_Result->num_rows >= 1)
	{
		while($Energy_Report_Row = $Energy_Report_Result->fetch_array()) {
			$Energy_Report_IMEI = $Energy_Report_Row['IMEI'];
			$Energy_Report_Date = $Energy_Report_Row['Date_S'];
			$Energy_Report_Key = $Energy_Report_IMEI."_".$Energy_Report_Date;

			if(!isset($Energy_Report_Data[$Energy_Report_Key])){
				$Energy_Report_Data[$Energy_Report_Key] = array(
					'device_name' => $Energy_Device_Names[$Energy_Report_IMEI],
					'date_s' => $Energy_Report_Date,
					'wind_sum' => 0,
					'wind_count' => 0,
					'produced_energy' => null,
					'producible_energy' => null,
					'total_hours_values' => array(),
					'run_hours_values' => array()
				);
			}

			$Energy_Wind_Speed = Energy_Report_Parse_Number($Energy_Report_Row['Avg_Wind_Speed']);
			if($Energy_Wind_Speed !== null && $Energy_Wind_Speed > 0){
				$Energy_Report_Data[$Energy_Report_Key]['wind_sum'] += $Energy_Wind_Speed;
				$Energy_Report_Data[$Energy_Report_Key]['wind_count']++;
			}

			$Energy_Produced = Energy_Report_Parse_Number($Energy_Report_Row['Produced_Energy']);
			if($Energy_Produced !== null){
				$Energy_Report_Data[$Energy_Report_Key]['produced_energy'] = $Energy_Produced * 1000;
			}

			$Energy_Producible = Energy_Report_Parse_Number($Energy_Report_Row['Producible_Energy']);
			if($Energy_Producible !== null){
				$Energy_Report_Data[$Energy_Report_Key]['producible_energy'] = $Energy_Producible * 1000;
			}

			$Energy_Report_Data[$Energy_Report_Key]['total_hours_values'][] = $Energy_Report_Row['Total_Hours_Source'];
			$Energy_Report_Data[$Energy_Report_Key]['run_hours_values'][] = $Energy_Report_Row['Run_Hours_Source'];
		}
	}
}

$Energy_Report_Title_Style = "background:#0f8f6f !important;color:#ffffff !important;font-weight:bold;";
$Energy_Report_Head_Style = "background:#103c6f !important;color:#ffffff !important;font-weight:bold;";
$Energy_Report_Cell_Style = "text-align:center !important;";
$Energy_Excel_Params = $_GET;
$Energy_Excel_Params['XLS'] = 1;
$Energy_Excel_Url = $_SERVER['PHP_SELF']."?".http_build_query($Energy_Excel_Params);
?>
<?php if($XLS == 0){ ?>
<style>
	.energy-report-actions{
		display:flex;
		justify-content:center;
		gap:10px;
		margin:0 0 10px;
	}
	.energy-report-actions a,
	.energy-report-actions button{
		min-height:32px;
		padding:7px 14px;
		border:0;
		border-radius:4px;
		background:#0f8f6f;
		color:#ffffff !important;
		font:700 12px "Segoe UI", Tahoma, Arial, sans-serif;
		text-decoration:none;
		cursor:pointer;
	}
	.energy-report-actions button{
		background:#103c6f;
	}
	.energy-report-table td{
		text-align:center !important;
	}
	@media print{
		.energy-report-actions{
			display:none !important;
		}
	}
</style>
<div class="energy-report-actions">
	<a href="<?=htmlspecialchars($Energy_Excel_Url)?>">Download Excel</a>
	<button type="button" onclick="window.print()">Download PDF</button>
</div>
<?php } ?>
<table width="100%" border="1" align="center" cellpadding="4" cellspacing="0" class="innertab1 energy-report-table">
	<tr>
		<td align="center" class="tab-head-frame" colspan="10" style="<?=$Energy_Report_Title_Style?><?=$Energy_Report_Cell_Style?>"><b>Daily Generation Report</b></td>
	</tr>
	<tr>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Device Name</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Date</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Avg Wind Speed</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Produced Energy</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Producible Energy</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Efficiency (%)</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Availability</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Total Hours</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Run Hours</td>
		<td align="center" class="tab-head-frame" style="<?=$Energy_Report_Head_Style?><?=$Energy_Report_Cell_Style?>">Down Hours</td>
	</tr>
	<?php
	if(count($Energy_Report_Data) >= 1){
		$Energy_Row_Count = 1;
		foreach($Energy_Report_Data as $Energy_Report_Day){
			$Energy_Avg_Wind = ($Energy_Report_Day['wind_count'] > 0 ? ($Energy_Report_Day['wind_sum'] / $Energy_Report_Day['wind_count']) : null);
			$Energy_Produced_Value = $Energy_Report_Day['produced_energy'];
			$Energy_Producible_Value = $Energy_Report_Day['producible_energy'];
			$Energy_Efficiency = null;
			if($Energy_Produced_Value !== null && $Energy_Producible_Value !== null && $Energy_Producible_Value != 0){
				$Energy_Efficiency = ($Energy_Produced_Value / $Energy_Producible_Value) * 100;
			}
			$Energy_Total_Hours = Energy_Report_Hour_Delta($Energy_Report_Day['total_hours_values']);
			$Energy_Run_Hours = Energy_Report_Hour_Delta($Energy_Report_Day['run_hours_values']);
			$Energy_Availability = null;
			if($Energy_Total_Hours !== null){
				$Energy_Availability = ($Energy_Total_Hours / 24) * 100;
			}
			$Energy_Down_Hours = null;
			if($Energy_Run_Hours !== null){
				$Energy_Down_Hours = Energy_Report_Limit_Hours(24 - $Energy_Run_Hours);
			}
			$Energy_Row_Class = ($Energy_Row_Count % 2 == 0 ? 'tab-td-even' : 'tab-td-odd');
			?>
			<tr>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=htmlspecialchars($Energy_Report_Day['device_name'])?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=date("d-m-Y", strtotime($Energy_Report_Day['date_s']))?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Avg_Wind)?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Produced_Value)?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Producible_Value)?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Efficiency)?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Availability)?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Total_Hours)?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Run_Hours)?></td>
				<td align="center" class="<?=$Energy_Row_Class?>"><?=Energy_Report_Format_Number($Energy_Down_Hours)?></td>
			</tr>
			<?php
			$Energy_Row_Count++;
		}
	}else{
		?>
		<tr>
			<td width="50%" class="tab-head-td" colspan="10" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
		</tr>
		<?php
	}
	?>
</table>
