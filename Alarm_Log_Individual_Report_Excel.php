       <!-- 
          Alarm Log
        -->
	<?php
include_once("gamesa_lut.php");

if (!function_exists('alarm_report_h')) {
	function alarm_report_h($value) {
		return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
	}
}

if (!function_exists('alarm_report_date')) {
	function alarm_report_date($value) {
		$time = strtotime($value);
		return $time ? date('d-m-Y', $time) : '';
	}
}

$Alarm_Device_Name = '';

if (isset($Device_Name) && !is_array($Device_Name) && $Device_Name != '') {
	$Alarm_Device_Name = $Device_Name;
}
elseif (isset($Device_Name) && is_array($Device_Name)) {
	if (isset($Device_Name[$IMEI]) && $Device_Name[$IMEI] != '') {
		$Alarm_Device_Name = $Device_Name[$IMEI];
	}
	elseif (isset($Device_Name[1]) && $Device_Name[1] != '') {
		$Alarm_Device_Name = $Device_Name[1];
	}
}

if ($Alarm_Device_Name == '' && isset($All_Devicename[1]) && $All_Devicename[1] != '') {
	$Alarm_Device_Name = $All_Devicename[1];
}

if ($Alarm_Device_Name == '') {
	$Alarm_Device_Name = isset($IMEI) ? $IMEI : '';
}

$Alarm_From_Display = alarm_report_date($From_YMD);
$Alarm_To_Display = alarm_report_date($To_YMD);
$Alarm_Period_Display = $Alarm_From_Display.' to '.$Alarm_To_Display;
?>
<style>

.alarm-download-cell{
    padding:10px 0 12px;
    text-align:right;
}

.alarm-download-btn{
    display:inline-block;
    padding:8px 14px;
    border:1px solid #1d4ed8;
    border-radius:6px;
    background:#1d4ed8;
    color:#fff !important;
    font-size:12px;
    font-weight:700;
    text-decoration:none;
}

.report-table{
    width:100%;
    border-collapse:collapse;
    font-family:Segoe UI, Arial, sans-serif;
    font-size:13px;
    color:#333;
}

.alarm-report-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    overflow:hidden;
    border:1px solid #d7dee8;
    border-radius:8px;
    background:#fff;
    font-family:Segoe UI, Arial, sans-serif;
    box-shadow:0 10px 24px rgba(15, 23, 42, 0.08);
}

.alarm-title-cell{
    padding:16px 18px;
    border-bottom:1px solid #d7dee8;
    background:#f8fafc;
    color:#0f172a;
}

.alarm-title-wrap{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
}

.alarm-title{
    font-size:18px;
    font-weight:700;
}

.alarm-subtitle{
    color:#475569;
    font-size:12px;
    font-weight:700;
}

.alarm-summary-cell{
    padding:12px 14px;
    border-bottom:1px solid #e2e8f0;
    background:#fff;
}

.alarm-summary-grid{
    display:grid;
    grid-template-columns:repeat(4, minmax(130px, 1fr));
    gap:10px;
}

.alarm-summary-item{
    min-height:54px;
    padding:9px 11px;
    border:1px solid #e2e8f0;
    border-radius:6px;
    background:#f8fafc;
}

.alarm-summary-label{
    display:block;
    color:#64748b;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
}

.alarm-summary-value{
    display:block;
    margin-top:4px;
    color:#0f172a;
    font-size:15px;
    font-weight:700;
}

.report-header{
    background:#1F4E78;
    color:#FFF;
    font-size:20px;
    font-weight:600;
    text-align:center;
    padding:12px;
    letter-spacing:0.5px;
}

.report-info{
    width:100%;
    border:1px solid #d8d8d8;
    border-collapse:collapse;
    margin-bottom:12px;
    font-size:13px;
}

.report-info td{
    border:1px solid #e5e5e5;
    padding:8px;
}

.report-info b{
    color:#1F4E78;
}

.table-header{
    background:#1e293b;
    color:#FFF;
    font-size:13px;
    font-weight:bold;
    text-align:center;
}

.table-header td{
    padding:10px 12px;
    border-bottom:1px solid #cbd5e1;
}

.table-row td{
    padding:8px 12px;
    border-bottom:1px solid #e5e7eb;
    vertical-align:top;
}

.table-row:nth-child(even){
    background:#F8FAFC;
}

.table-row:hover{
    background:#EEF5FF;
}

.slno{
    text-align:center;
    width:60px;
    font-weight:bold;
}

.date{
    width:110px;
    text-align:center;
}

.time{
    width:90px;
    text-align:center;
}

.status{
    line-height:22px;
    color:#222;
}

.run{
    color:#0B8A2A;
    font-weight:bold;
}

.stop{
    color:#D00000;
    font-weight:bold;
}

.pause{
    color:#F39C12;
    font-weight:bold;
}

.grid{
    color:#0066CC;
    font-weight:bold;
}

.alarm-empty-cell{
    padding:16px;
    border:1px solid #d7dee8;
    background:#fff7ed;
    color:#9a3412;
    font-size:13px;
    font-weight:700;
    text-align:center;
}

@media (max-width:760px){
    .alarm-summary-grid{
        grid-template-columns:repeat(2, minmax(130px, 1fr));
    }

    .table-header td,
    .table-row td{
        padding:8px;
        font-size:11px;
    }
}

</style>
	<?php 
	if ($XLS == 0){
	?>
		<tr>
			<td colspan="5" class="alarm-download-cell">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->

			<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' class="alarm-download-btn">Download Excel</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' class="alarm-download-btn">Download Excel</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' class="alarm-download-btn">Download Excel</a>
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' class="alarm-download-btn">Download Excel</a>

			<?php  }if($FType==7 || $FType==8){?>
				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' class="alarm-download-btn">Download Excel</a>
			<?php  }if($FType==9){?>
				<a href='channel9new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' class="alarm-download-btn">Download Excel</a>
			
			<?php }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' class="alarm-download-btn">Download Excel</a>
			
			<?php }?>

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
                <table width="100%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1 alarm-report-table">	
					<?php 
					if ($XLS == 1){
					?>
					<tr>
    <td class="tab-head-tr" colspan="4" align="center">
        <h3 style="margin:5px;">Alarm Log Report - <?= alarm_report_h($Alarm_Device_Name) ?></h3>
    </td>
</tr>

<tr>
    <td colspan="4">

        <table width="100%" border="0" cellpadding="4" cellspacing="0">

            <tr>
                <td width="18%"><b>Device Name</b></td>
                <td width="32%"><?= alarm_report_h($Alarm_Device_Name) ?></td>

                <td width="18%"><b>WEG No</b></td>
                <td><?= alarm_report_h(isset($All_WEG_No[1]) ? $All_WEG_No[1] : '') ?></td>
            </tr>

            <tr>
                <td><b>Site Location</b></td>
                <td><?= alarm_report_h(isset($Site_Location[1]) ? $Site_Location[1] : '') ?></td>

                <td><b>LOC No</b></td>
                <td><?= alarm_report_h(isset($All_LOC_No[1]) ? $All_LOC_No[1] : '') ?></td>
            </tr>

            <tr>
                <td><b>DOC</b></td>
                <td><?= alarm_report_h(isset($DOC[1]) ? $DOC[1] : '') ?></td>

                <td><b>HTSC No</b></td>
                <td><?= alarm_report_h(isset($All_HTSC_No[1]) ? $All_HTSC_No[1] : '') ?></td>
            </tr>

            <tr>
                <td><b>Period From</b></td>
                <td><?= alarm_report_h($Alarm_From_Display); ?></td>

                <td><b>Period To</b></td>
                <td><?= alarm_report_h($Alarm_To_Display); ?></td>
            </tr>

        </table>

    </td>
</tr>

<tr><td colspan="4">&nbsp;</td></tr>
					<?php
					}
					?>
					<?php 
					if ($XLS == 0){
					?>
						<tr>
							<td class="alarm-title-cell" colspan="4">
								<div class="alarm-title-wrap">
									<span class="alarm-title">Alarm Log Report</span>
									<span class="alarm-subtitle">Device Name: <?= alarm_report_h($Alarm_Device_Name) ?></span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="alarm-summary-cell">
								<div class="alarm-summary-grid">
									<div class="alarm-summary-item">
										<span class="alarm-summary-label">Device Name</span>
										<span class="alarm-summary-value"><?= alarm_report_h($Alarm_Device_Name) ?></span>
									</div>
									<div class="alarm-summary-item">
										<span class="alarm-summary-label">Period</span>
										<span class="alarm-summary-value"><?= alarm_report_h($Alarm_Period_Display) ?></span>
									</div>
									<div class="alarm-summary-item">
										<span class="alarm-summary-label">From Date</span>
										<span class="alarm-summary-value"><?= alarm_report_h($Alarm_From_Display) ?></span>
									</div>
									<div class="alarm-summary-item">
										<span class="alarm-summary-label">To Date</span>
										<span class="alarm-summary-value"><?= alarm_report_h($Alarm_To_Display) ?></span>
									</div>
								</div>
							</td>
						</tr>
					<?php 
					}
					?>
						<tr class="table-header">

						<td class="slno">Sl.No</td>

						<td class="date">Date</td>

						<td class="time">Time</td>

						<td>Status / Alarm Description</td>

						</tr>
        <?php
		
            if(isset($_REQUEST['p'])){
				if(!isset($SlNo) || $SlNo == '') {
					$SlNo = 1;
				}
				if(!isset($MI) || $MI == '') {
					$MI = 1;
				}
				$All_Error_Date_Arr = array();
				$All_Error_Time_Arr = array();
				$All_Error_Arr = array();
				
				$Mysql_Query_Error = "select Date_S,Time_S,status from $Cook_Variable[7].current_status where IMEI = '".$IMEI."' and (Date_S >= '".$From_YMD."' and  Date_S <= '".$To_YMD."') order by Date_S desc,Time_S desc";
//echo $Mysql_Query_Error;
				if (!$Mysql_Query_Error_Result = $db->query($Mysql_Query_Error))
            {
                die($db->error);
            }

            if($Mysql_Query_Error_Result->num_rows >= 1)
            {
                while($Fetch_Error_Result = $Mysql_Query_Error_Result->fetch_array()) {					
							$All_Error_Date_Arr = date("d.m.Y",strtotime($Fetch_Error_Result['Date_S']));
							$All_Error_Time_Arr = $Fetch_Error_Result['Time_S'];
							$status = trim($Fetch_Error_Result['status']);

							if ($status == "RUN" || $status == "GridDrop")
							{
								$All_Error_Arr = $status;
							}
							elseif ($status == "PAUSE" || $status == "Stop")
							{
								$All_Error_Arr = $status;
							}
							else
							{
								$codes = explode(';', $status);

								$AlarmNames = array();

								foreach ($codes as $code)
								{
									$code = trim($code);

									if ($code == "")
										continue;

									if (isset($Gamesa_LUT[$code]))
									{
										$AlarmNames[] = $Gamesa_LUT[$code];
									}
									else
									{
										$AlarmNames[] = "Unknown Alarm ($code)";
									}
								}

								$All_Error_Arr = implode(", ", $AlarmNames);
							}
					?>
                    <?php

$Class="status";

if($status=="RUN")
    $Class="run";

elseif($status=="GridDrop")
    $Class="grid";

elseif($status=="PAUSE")
    $Class="pause";

elseif($status=="Stop")
    $Class="stop";
	
else
    $Class="stop";

?>

<tr class="table-row">

<td class="slno"><?=alarm_report_h($SlNo)?></td>

<td class="date"><?=alarm_report_h($All_Error_Date_Arr)?></td>

<td class="time"><?=alarm_report_h($All_Error_Time_Arr)?></td>

<td class="<?=$Class?>"><?=alarm_report_h($All_Error_Arr)?></td>

</tr>

<?php
$SlNo++;
?>
			<?php
				$MI++;
					}
				}	
				else{
					?>
					<tr>
						<td colspan="4" class="alarm-empty-cell"><?= alarm_report_h(isset($No_Records) ? $No_Records : 'No records found') ?></td>
					</tr>
					<?php
				}
			}	
			?>
						
					</table>
     
                  
            </td>
        </tr>
