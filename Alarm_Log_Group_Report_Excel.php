       <!-- 
          Alarm Log
        -->
	<?php
include_once("gamesa_lut.php");
?>
<style>

.report-table{
    width:100%;
    border-collapse:collapse;
    font-family:Segoe UI, Arial, sans-serif;
    font-size:13px;
    color:#333;
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
    background:#2F75B5;
    color:#FFF;
    font-size:13px;
    font-weight:bold;
    text-align:center;
}

.table-header td{
    padding:10px;
    border:1px solid #d5d5d5;
}

.table-row td{
    padding:8px;
    border:1px solid #e5e5e5;
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

</style>
	<?php 
	if ($XLS == 0){
	?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->

			<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==7 || $FType==8){?>
				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==9){?>
				<a href='channel9new_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
			<?php }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			
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
                <table width="100%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">	
					<?php 
					if ($XLS == 1){
					?>
					<tr>
    <td class="tab-head-tr" colspan="4" align="center">
        <h3 style="margin:5px;">Alarm Log Report</h3>
    </td>
</tr>

<tr>
    <td colspan="4">

        <table width="100%" border="0" cellpadding="4" cellspacing="0">

            <tr>
                <td width="18%"><b>Customer</b></td>
                <td width="32%"><?= $All_Devicename[1] ?></td>

                <td width="18%"><b>WEG No</b></td>
                <td><?= $All_WEG_No[1] ?></td>
            </tr>

            <tr>
                <td><b>Site Location</b></td>
                <td><?= $Site_Location[1] ?></td>

                <td><b>LOC No</b></td>
                <td><?= $All_LOC_No[1] ?></td>
            </tr>

            <tr>
                <td><b>DOC</b></td>
                <td><?= $DOC[1] ?></td>

                <td><b>HTSC No</b></td>
                <td><?= $All_HTSC_No[1] ?></td>
            </tr>

            <tr>
                <td><b>Report From</b></td>
                <td><?= date('d-m-Y',strtotime($From_YMD)); ?></td>

                <td><b>Report To</b></td>
                <td><?= date('d-m-Y',strtotime($To_YMD)); ?></td>
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
						<tr class="table-header">

						<td class="slno">Sl.No</td>

						<td class="date">Date</td>

						<td class="time">Time</td>

						<td>Status / Alarm Description</td>

						</tr>
					<?php 
					}
					?>
        <?php
		
            if(isset($_REQUEST['p'])){
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

<td class="slno"><?=$SlNo?></td>

<td class="date"><?=$All_Error_Date_Arr?></td>

<td class="time"><?=$All_Error_Time_Arr?></td>

<td class="<?=$Class?>"><?=$All_Error_Arr?></td>

</tr>

<?php
$SlNo++;
?>
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