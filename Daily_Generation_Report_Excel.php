          <!-- 
            Daily Generation Report
        -->
		
		<?php
error_reporting(E_ALL);

include "header_inner.php";      // or config.php
include "db_connect.php";        // whichever creates $db

$XLS   = $_REQUEST['XLS'] ?? 0;
$FType = $_REQUEST['FType'] ?? 0;
$IMEI  = $_REQUEST['IMEI'] ?? '';
?>
		
<!-- ===== REPORT UI STYLE START ===== -->
<style>
.innertab1_{
    width:100%;
    border-collapse:collapse;
    font-family:Arial,Helvetica,sans-serif;
    font-size:13px;
    background:#fff;
}

.tab-head-tr{
    background:linear-gradient(to right,#0f4c81,#1e88e5);
    color:#fff;
    font-size:16px;
    padding:10px;
}

.tab-head-td{
    background:#2f80ed;
    color:#fff;
    font-weight:bold;
    text-align:center;
    padding:7px 5px;
    border:1px solid #cfd8dc;
    white-space:nowrap;
}

.tab-head-td1{
    padding:6px 5px;
    border:1px solid #e0e0e0;
    text-align:center;
    background:#fafafa;
}

.innertab1_ tr:nth-child(even) .tab-head-td1{
    background:#f1f7ff;
}

.innertab1_ tr:hover .tab-head-td1{
    background:#e3f2fd;
}

.innertab1_ tr:last-child{
    background:#fff3cd;
    font-weight:bold;
}

.download-box{
    background:#e8f5e9;
    border:1px solid #4caf50;
    padding:15px;
    border-radius:6px;
    margin:10px 0;
    text-align:center;
}

.download-box a{
    background:#4caf50;
    color:#fff;
    padding:8px 18px;
    text-decoration:none;
    border-radius:4px;
    font-weight:bold;
}

.download-box a:hover{
    background:#388e3c;
}

.report-title{
    font-size:18px;
    font-weight:bold;
    color:#0f4c81;
    padding-bottom:6px;
}
</style>
<!-- ===== REPORT UI STYLE END ===== -->

	<?php //echo $_REQUEST['FType'] ."is format type";
 if ($XLS == 0) { ?>
		<tr>
			<td colspan="5" class="download-box">
    
			<?php
   if ($FType == 1 || $FType == 6) { ?>
				<a href='channel2_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Here Excel Report</a>
			<?php }
   if ($FType == 2) { ?>
				<a href='channel3_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Here Excel Report</a>

			<?php }
   if ($FType == 3) { ?>
				<a href='channel4_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Here Excel Report</a>
			<?php }
   if ($FType == 4) { ?>
				<a href='channel5_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Here Excel Report</a>
			<?php }
   if ($FType == 7 || $FType == 8) { ?>
				<a href='channel8_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Here Excel Report</a>
			<?php }
   if ($FType == 10) { ?>
				<a href='channel10_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Here Excel Report</a>
			<?php }
   if ($FType == 11) { ?>
				<a href='channel11_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Download Here Excel Report</a>
			
			<?php }
   ?>
			</td>
		</tr>
<?php } ?>					
	
        <tr>
            <td width="850px">
<table width="950px" border="<?= $XLS == 1
    ? "1"
    : "0" ?>" align="left" cellpadding="1" cellspacing="1" class="innertab1_">	
	<?php
 if ($XLS == 1) {<?php
     //xls=1
     ?>

						<tr>
							<td class="tab-head-td" colspan="10"  align="center"><b><? print_r($All_Firstname[1]) ?>   <?print_r($All_Lastname[1])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="10"  align="left"><b>Site:</b><?= implode(
           ",",
           array_unique($Site_Location)
       ) ?></td>
					
 <?php }
 if ($XLS == 0) { ?>

					<tr>

						<td class="tab-head-tr" colspan="29" align="center">&nbsp;&nbsp;<b>Daily Generation Detail Report</b></td>

					</tr>

					<?php }
 ?>
	<?php
 //  if(isset($_REQUEST['p'])){//if p is set

 $DGR_Start_Date = $_REQUEST["inputDate"]; //echo $DGR_Start_Date;
 $DGR_End_Date = $_REQUEST["inputDate1"]; //echo  $DGR_End_Date;
 $From_D_Epoch = strtotime($_REQUEST["inputDate"]);
 $To_D_Epoch = strtotime($_REQUEST["inputDate1"]);

 if ($Cook_Variable[2] == 3 || $Cook_Variable[2] == 2) {
     $Device_Query =
         "select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI,Db_Name from device_register where Parent_ID=" .
         $Cook_Variable[6] .
         "  order by Device_Order";
 } elseif ($Cook_Variable[2] == 4) {
     $Device_Query =
         "select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI,Db_Name from device_register where Account_ID='" .
         $Account_ID .
         "'  order by Device_Order";
 }
 //echo $Device_Query;
 if (!($Device_Query_Result = $db->query($Device_Query))) {
     die($db->error);
 }
 $Device_Query_Result_Count = $Device_Query_Result->num_rows;
 if ($Device_Query_Result->num_rows >= 1) {
     while ($Fetch_Result = $Device_Query_Result->fetch_array()) {
         $DGR_IMEI[$Fetch_Result["IMEI"]] = $Fetch_Result["IMEI"];
         $Device_Name[$Fetch_Result["IMEI"]] = $Fetch_Result["Device_Name"];
         $Closing_Time[$Fetch_Result["IMEI"]] = $Fetch_Result["Closing_Time"];
         $Site_Location[$Fetch_Result["IMEI"]] = $Fetch_Result["Site_Location"];
         $Format[$Fetch_Result["IMEI"]] = $Fetch_Result["Format_Type"];
         $Dbname[$Fetch_Result["IMEI"]] = $Fetch_Result["Db_Name"];
         if ($Fetch_Result["Format_Type"] == "1") {
             $F1_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 2) {
             $F2_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 3) {
             $F3_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 4) {
             $F4_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 6) {
             $F6_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 7) {
             $F7_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 8) {
             $F8_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 9) {
             $F9_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 10) {
             $F10_IMEI[] = $Fetch_Result["IMEI"];
         }
         if ($Fetch_Result["Format_Type"] == 11) {
             $F11_IMEI[] = $Fetch_Result["IMEI"];
         }
     }
 }

 if ($Device_Query_Result_Count >= 1) {<?php
     //record count if
     ?>
			        <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Export</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Import</b></td>
                         
			<td class="tab-head-td" align="center" width="16px;"><b>Total Hrs</b></td>  
                     	<td class="tab-head-td" align="center" width="16px;"><b>Run Hrs</b></td> 
					<td class="tab-head-td" align="center" width="16px;"><b>GD Hrs</b></td> 
					  
			<td class="tab-head-td" align="center" width="16px;"><b>BD Hrs</b></td>                                    
                        <td class="tab-head-td" align="center" width="16px;"><b>Lull Hrs</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>GA %</b></td> 
			<?php if ($Cook_Variable[7] = "va_mtk") { ?>
				<td class="tab-head-td" align="center" width="16px;"><b>Loss by GF</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>Loss by MF</b></td> 
				<?php } ?>
				
                    </tr>
                 			 <?php
                     $MI = 1;
                     //print_r($DATE_F);
                     $Tot_All_Generation = 0;
                     $Tot_BD_Hours = 0;
                     $Tot_GD_Hours = 0;
                     $Tot_Maint_Hours = 0;
                     $Tot_Lull_Hours = 0;
                     $Tot_Import_LCS = 0;
                     $Tot_Run_Hours = 0;
                     $Days = 0;
                     $datediff = abs($From_D_Epoch - $To_D_Epoch);
                     $diff = floor($datediff / (60 * 60 * 24));
                     $Daydiff = 24 * ($diff + 1) * count($DGR_IMEI);
                     //print_r($diff);
                     //print_r($Daydiff);

                     $Date_Array = getAllDatesBetweenTwoDates(
                         $DGR_Start_Date,
                         $DGR_End_Date
                     ); //print_r($Date_Array);
                     foreach ($Date_Array as $DATE_Val) {
                         foreach ($DGR_IMEI as $IMEI_Val) {
                             $Date_dmy = date("d.m.Y", strtotime($DATE_Val));
                             if (
                                 $Closing_Time[$IMEI_Val] == "00:00:00" ||
                                 $Closing_Time[$IMEI_Val] == "0"
                             ) {
                                 $Date_St = date("Y-m-d", strtotime($DATE_Val));
                                 $Date_Stamp = date(
                                     "Y-m-d",
                                     strtotime($DATE_Val)
                                 );
                                 $Yester_Stamp = $Date_Stamp;
                                 $Yester_dmy = $Date_dmy;
                             } elseif (
                                 $Closing_Time[$IMEI_Val] >= "10:00:00" ||
                                 $Closing_Time[$IMEI_Val] == "10"
                             ) {
                                 $Date_St = date(
                                     "Y-m-d",
                                     strtotime($DATE_Val) - 86400
                                 );
                                 $Date_Stamp = date(
                                     "Y-m-d",
                                     strtotime($DATE_Val)
                                 );
                                 $Yester_Stamp = date(
                                     "Y-m-d",
                                     strtotime($DATE_Val)
                                 );
                                 //$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
                             } else {
                                 $Date_Stamp = date(
                                     "Y-m-d",
                                     strtotime($DATE_Val)
                                 );
                                 $Date_St = date("Y-m-d", strtotime($DATE_Val));
                                 $Yester_Stamp = date(
                                     "Y-m-d",
                                     strtotime($DATE_Val) + 86400
                                 );
                                 $Yester_dmy = date(
                                     "d.m.Y",
                                     strtotime($DATE_Val) + 86400
                                 );
                             }
                             //echo $DATE_Val;

                             //echo $Date_Stamp;
                             //print_r($Error_Type);
                             //print_r($BD_Hours);
                             //print_r($GD_Hours);
                             //if(isset($F1_IMEI)){
                             if ($Format[$IMEI_Val] == "1") {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F1_IMEI) .
                                     ")  and (Date_S= '" .
                                     $Date_Stamp .
                                     "')";
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         $Import_LCS[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Import_Max"] -
                                             $Fetch_Result["Import_Min"];
                                         $Array_Import[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 500
                                                 ? $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Total_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1_Max"] -
                                             $Fetch_Result["Gen1_Min"];
                                         $Array_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 15000
                                                 ? $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Run_Max"] -
                                             $Fetch_Result["Run_Min"];
                                         $Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "100"
                                                 ? "24"
                                                 : $Run[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ];
                                         $Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1H_Max"] -
                                             $Fetch_Result["Gen1H_Min"];
                                         $Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "100"
                                                 ? "24"
                                                 : $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ];
                                         $Lull_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] -
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         if (
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] == -1
                                         ) {
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = 0;
                                         }
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $Array_Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             24 -
                                             ($Fetch_Result["Line_Max"] -
                                                 $Fetch_Result["Line_Min"]);
                                         $Array_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 24) *
                                             100;
                                         $Array_Lull[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Loss_Due_To_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $BD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             24 -
                                             ($GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] +
                                                 $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] +
                                                 $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ]);
                                         $Loss_Due_To_BD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $MA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] -
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 (24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val])) *
                                             100;
                                     } //end while
                                 }
                             } //endif isset

                             //if(isset($F2_IMEI)){
                             if ($Format[$IMEI_Val] == "2") {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F2_IMEI) .
                                     ")  and (Date_S= '" .
                                     $Date_Stamp .
                                     "')";
                                 //echo $Gen_Mysql_Query;
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         $Import_LCS[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Import_Max"] -
                                             $Fetch_Result["Import_Min"];
                                         $Array_Import[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 500
                                                 ? $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Total_Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1_Max"] -
                                             $Fetch_Result["Gen1_Min"];
                                         $Gen2[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen2_Max"] -
                                             $Fetch_Result["Gen2_Min"];
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1H_Max"] -
                                             $Fetch_Result["Gen1H_Min"] +
                                             ($Fetch_Result["Gen2H_Max"] -
                                                 $Fetch_Result["Gen2H_Min"]);
                                         $Total_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] +
                                             $Gen2[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $Array_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 6000
                                                 ? $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "500"
                                                 ? "24"
                                                 : $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val];
                                         $Array_Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Run_Hours[$DATE_Val][
                                                     $Fetch_Result["IMEI"]
                                                 ]
                                                 : "0";

                                         $POC_Mysql_Query =
                                             "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI in (" .
                                             implode(",", $F2_IMEI) .
                                             ")  and (Date_S= '" .
                                             $Date_St .
                                             "' OR  Date_S='" .
                                             $Yester_Stamp .
                                             "')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI,Error_Type";
                                         //echo $POC_Mysql_Query;
                                         if (
                                             !($POC_Mysql_Query_Result = $db->query(
                                                 $POC_Mysql_Query
                                             ))
                                         ) {
                                             die($db->error);
                                         }
                                         while (
                                             $POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()
                                         ) {
                                             $Error_Type[
                                                 $POC_Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 $POC_Fetch_Result[
                                                     "Error_Type"
                                                 ];

                                             # For BD Hours

                                             if (
                                                 $Error_Type[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] == "BD Hours"
                                             ) {
                                                 //echo $POC_Fetch_Result['Diff'];
                                                 $BD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] = round(
                                                     $POC_Fetch_Result["Diff"] /
                                                         3600,
                                                     1
                                                 );
                                                 $BD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] =
                                                     $BD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] >= 0 &&
                                                     $BD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] <= 24
                                                         ? $BD_Hours[
                                                             $POC_Fetch_Result[
                                                                 "IMEI"
                                                             ]
                                                         ][$DATE_Val]
                                                         : "0";
                                             }
                                             # For GD Hours
                                             elseif (
                                                 $Error_Type[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] == "GD Hours"
                                             ) {
                                                 //echo $POC_Fetch_Result['Diff'];
                                                 $GD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] = round(
                                                     $POC_Fetch_Result["Diff"] /
                                                         3600,
                                                     1
                                                 );
                                                 $GD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] =
                                                     $GD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] >= 0 &&
                                                     $GD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] <= 24
                                                         ? $GD_Hours[
                                                             $POC_Fetch_Result[
                                                                 "IMEI"
                                                             ]
                                                         ][$DATE_Val]
                                                         : "0";
                                             }
                                         }
                                     }
                                 }

                                 $Array_GD[$IMEI_Val][$DATE_Val] =
                                     $GD_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $GD_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $GD_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $Array_BD[$IMEI_Val][$DATE_Val] =
                                     $BD_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $BD_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $BD_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $Lull_Hours[$IMEI_Val][$DATE_Val] =
                                     24 * 3600 -
                                     ($Run_Hours[$IMEI_Val][$DATE_Val] * 3600 +
                                         $BD_Hours[$IMEI_Val][$DATE_Val] +
                                         $GD_Hours[$IMEI_Val][$DATE_Val]);

                                 $Lull_Hours[$IMEI_Val][$DATE_Val] = Sec2Time(
                                     $Lull_Hours[$IMEI_Val][$DATE_Val],
                                     "m"
                                 );
                                 if ($Lull_Hours[$IMEI_Val][$DATE_Val] == -1) {
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] = 0;
                                 }
                                 $Array_Lull[$IMEI_Val][$DATE_Val] =
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $Lull_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $MA_Percent[$IMEI_Val][$DATE_Val] =
                                     ((24 -
                                         $GD_Hours[$IMEI_Val][$DATE_Val] -
                                         $BD_Hours[$IMEI_Val][$DATE_Val]) /
                                         (24 -
                                             $GD_Hours[$IMEI_Val][$DATE_Val])) *
                                     100;
                                 $GA_Percent[$IMEI_Val][$DATE_Val] =
                                     ((24 - $GD_Hours[$DATE_Val]) / 24) * 100;

                                 $Loss_Due_To_GD[$IMEI_Val][$DATE_Val] =
                                     ($Total_Gen[$DATE_Val] /
                                         $Run_Hours[$IMEI_Val][$DATE_Val]) *
                                     $GD_Hours[$IMEI_Val][$DATE_Val];

                                 $Loss_Due_To_BD[$IMEI_Val][$DATE_Val] =
                                     ($Total_Gen[$DATE_Val] /
                                         $Run_Hours[$IMEI_Val][$DATE_Val]) *
                                     $BD_Hours[$IMEI_Val][$DATE_Val];
                                 //}//end while
                             } //endif isset
                             //if(isset($F3_IMEI)){
                             if ($Format[$IMEI_Val] == "3") {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F3_IMEI) .
                                     ") and (Date_S= '" .
                                     $Date_Stamp .
                                     "')"; //echo $Gen_Mysql_Query;
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         $Total_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1_Max"] -
                                             $Fetch_Result["Gen1_Min"];
                                         $Array_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 18000
                                                 ? $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Import_LCS[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Import_Max"] -
                                             $Fetch_Result["Import_Min"];
                                         $Array_Import[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 500
                                                 ? $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1H_Max"] -
                                             $Fetch_Result["Gen1H_Min"] +
                                             ($Fetch_Result["Gen2H_Max"] -
                                                 $Fetch_Result["Gen2H_Min"]);

                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "500"
                                                 ? "24"
                                                 : $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val];
                                         $Array_Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";

                                         $POC_Mysql_Query =
                                             "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI in (" .
                                             implode(",", $F3_IMEI) .
                                             ")  and (Date_S= '" .
                                             $Date_St .
                                             "' OR  Date_S='" .
                                             $Yester_Stamp .
                                             "')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI,Error_Type";
                                         //echo $POC_Mysql_Query;
                                         if (
                                             !($POC_Mysql_Query_Result = $db->query(
                                                 $POC_Mysql_Query
                                             ))
                                         ) {
                                             die($db->error);
                                         }
                                         while (
                                             $POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()
                                         ) {
                                             $Error_Type[
                                                 $POC_Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 $POC_Fetch_Result[
                                                     "Error_Type"
                                                 ];

                                             # For BD Hours

                                             if (
                                                 $Error_Type[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] == "BD Hours"
                                             ) {
                                                 //echo $POC_Fetch_Result['Diff'];
                                                 $BD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] = round(
                                                     $POC_Fetch_Result["Diff"] /
                                                         3600,
                                                     1
                                                 );
                                                 $BD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] =
                                                     $BD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] >= 0 &&
                                                     $BD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] <= 24
                                                         ? $BD_Hours[
                                                             $POC_Fetch_Result[
                                                                 "IMEI"
                                                             ]
                                                         ][$DATE_Val]
                                                         : "0";
                                             }
                                             # For GD Hours
                                             elseif (
                                                 $Error_Type[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] == "GD Hours"
                                             ) {
                                                 //echo $POC_Fetch_Result['Diff'];
                                                 $GD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] = round(
                                                     $POC_Fetch_Result["Diff"] /
                                                         3600,
                                                     1
                                                 );
                                                 $GD_Hours[
                                                     $POC_Fetch_Result["IMEI"]
                                                 ][$DATE_Val] =
                                                     $GD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] >= 0 &&
                                                     $GD_Hours[
                                                         $POC_Fetch_Result[
                                                             "IMEI"
                                                         ]
                                                     ][$DATE_Val] <= 24
                                                         ? $GD_Hours[
                                                             $POC_Fetch_Result[
                                                                 "IMEI"
                                                             ]
                                                         ][$DATE_Val]
                                                         : "0";
                                             }
                                         } //ENDWHILE
                                     }
                                 }
                                 $Array_GD[$IMEI_Val][$DATE_Val] =
                                     $GD_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $GD_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $GD_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $Array_BD[$IMEI_Val][$DATE_Val] =
                                     $BD_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $BD_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $BD_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $Lull_Hours[$IMEI_Val][$DATE_Val] =
                                     24 * 3600 -
                                     ($Run_Hours[$IMEI_Val][$DATE_Val] * 3600 +
                                         $BD_Hours[$IMEI_Val][$DATE_Val] +
                                         $GD_Hours[$IMEI_Val][$DATE_Val]);

                                 $Lull_Hours[$IMEI_Val][$DATE_Val] = Sec2Time(
                                     $Lull_Hours[$IMEI_Val][$DATE_Val],
                                     "m"
                                 );

                                 if ($Lull_Hours[$IMEI_Val][$DATE_Val] == -1) {
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] = 0;
                                 }
                                 $Array_Lull[$IMEI_Val][$DATE_Val] =
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $Lull_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";

                                 $MA_Percent[$IMEI_Val][$DATE_Val] =
                                     ((24 -
                                         $GD_Hours[$IMEI_Val][$DATE_Val] -
                                         $BD_Hours[$IMEI_Val][$DATE_Val]) /
                                         (24 -
                                             $GD_Hours[$IMEI_Val][$DATE_Val])) *
                                     100;

                                 $GA_Percent[$IMEI_Val][$DATE_Val] =
                                     ((24 - $GD_Hours[$IMEI_Val][$DATE_Val]) /
                                         24) *
                                     100;

                                 $Loss_Due_To_GD[$IMEI_Val][$DATE_Val] =
                                     ($Total_Gen[$IMEI_Val][$DATE_Val] /
                                         $Run_Hours[$IMEI_Val][$DATE_Val]) *
                                     $GD_Hours[$IMEI_Val][$DATE_Val];

                                 $Loss_Due_To_BD[$IMEI_Val][$DATE_Val] =
                                     ($Total_Gen[$DATE_Val] /
                                         $Run_Hours[$IMEI_Val][$DATE_Val]) *
                                     $BD_Hours[$IMEI_Val][$DATE_Val];
                             } //endif isset
                             if ($Format[$IMEI_Val] == "4") {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F4_IMEI) .
                                     ")  and (Date_S= '" .
                                     $Date_Stamp .
                                     "')";
                                 //echo $Gen_Mysql_Query;
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         $Import_LCS[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Import_Max"] -
                                             $Fetch_Result["Import_Min"];
                                         $Array_Import[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 500
                                                 ? $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Total_Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1_Max"] -
                                             $Fetch_Result["Gen1_Min"];
                                         $Gen2[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen2_Max"] -
                                             $Fetch_Result["Gen2_Min"];
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1H_Max"] -
                                             $Fetch_Result["Gen1H_Min"] +
                                             ($Fetch_Result["Gen2H_Max"] -
                                                 $Fetch_Result["Gen2H_Min"]);
                                         $Total_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] +
                                             $Gen2[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $Array_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 15000
                                                 ? $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "50"
                                                 ? "24"
                                                 : $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val];
                                         $Array_Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Run_Hours[$DATE_Val][
                                                     $Fetch_Result["IMEI"]
                                                 ]
                                                 : "0";

                                         /*$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI in (".implode(",",$F4_IMEI).")  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI,Error_Type";
//echo $POC_Mysql_Query;
		if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }
                while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {
					$Error_Type[$POC_Fetch_Result['IMEI']][$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$POC_Fetch_Result['IMEI']][$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val]=($BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] >=0 && $BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] <=24)?$BD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$POC_Fetch_Result['IMEI']][$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val]=($GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] >=0 && $GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] <=24)?$GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] : '0';
}
}*/
                                     }
                                 }

                                 $Array_GD[$IMEI_Val][$DATE_Val] =
                                     $GD_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $GD_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $GD_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $Array_BD[$IMEI_Val][$DATE_Val] =
                                     $BD_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $BD_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $BD_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $Lull_Hours[$IMEI_Val][$DATE_Val] =
                                     24 * 3600 -
                                     ($Run_Hours[$IMEI_Val][$DATE_Val] * 3600 +
                                         $BD_Hours[$IMEI_Val][$DATE_Val] +
                                         $GD_Hours[$IMEI_Val][$DATE_Val]);

                                 $Lull_Hours[$IMEI_Val][$DATE_Val] = Sec2Time(
                                     $Lull_Hours[$IMEI_Val][$DATE_Val],
                                     "m"
                                 );
                                 if ($Lull_Hours[$IMEI_Val][$DATE_Val] == -1) {
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] = 0;
                                 }
                                 $Array_Lull[$IMEI_Val][$DATE_Val] =
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] > 0 &&
                                     $Lull_Hours[$IMEI_Val][$DATE_Val] <= 25
                                         ? $Lull_Hours[$IMEI_Val][$DATE_Val]
                                         : "0";
                                 $MA_Percent[$IMEI_Val][$DATE_Val] =
                                     ((24 -
                                         $GD_Hours[$IMEI_Val][$DATE_Val] -
                                         $BD_Hours[$IMEI_Val][$DATE_Val]) /
                                         (24 -
                                             $GD_Hours[$IMEI_Val][$DATE_Val])) *
                                     100;
                                 $GA_Percent[$IMEI_Val][$DATE_Val] =
                                     ((24 - $GD_Hours[$DATE_Val]) / 24) * 100;

                                 $Loss_Due_To_GD[$IMEI_Val][$DATE_Val] =
                                     ($Total_Gen[$DATE_Val] /
                                         $Run_Hours[$IMEI_Val][$DATE_Val]) *
                                     $GD_Hours[$IMEI_Val][$DATE_Val];

                                 $Loss_Due_To_BD[$IMEI_Val][$DATE_Val] =
                                     ($Total_Gen[$DATE_Val] /
                                         $Run_Hours[$IMEI_Val][$DATE_Val]) *
                                     $BD_Hours[$IMEI_Val][$DATE_Val];
                                 //}//end while
                             } //endif isset

                             //if(isset($F6_IMEI)){
                             if ($Format[$IMEI_Val] == "6") {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F6_IMEI) .
                                     ")  and (Date_S= '" .
                                     $Date_Stamp .
                                     "')";
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         $Import_LCS[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Import_Max"] -
                                             $Fetch_Result["Import_Min"];
                                         $Array_Import[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 500
                                                 ? $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Total_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1_Max"] -
                                             $Fetch_Result["Gen1_Min"];
                                         $Array_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 15000
                                                 ? $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Run_Max"] -
                                             $Fetch_Result["Run_Min"];
                                         $Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "500"
                                                 ? "24"
                                                 : $Run[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ];
                                         $Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1H_Max"] -
                                             $Fetch_Result["Gen1H_Min"];
                                         $Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "50"
                                                 ? "24"
                                                 : $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ];
                                         $Lull_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] -
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         if (
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] == -1
                                         ) {
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = 0;
                                         }
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $Array_Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             24 -
                                             ($Fetch_Result["Line_Max"] -
                                                 $Fetch_Result["Line_Min"]);
                                         $Array_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 24) *
                                             100;
                                         $Array_Lull[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Loss_Due_To_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $BD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             24 -
                                             ($GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] +
                                                 $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] +
                                                 $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ]);
                                         $Loss_Due_To_BD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $MA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] -
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 (24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val])) *
                                             100;
                                         $Array_BD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                     } //end while
                                 }
                             } //endif isset

                             if ($Format[$IMEI_Val] == 7) {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F7_IMEI) .
                                     ")  and (Date_S= '" .
                                     $Date_Stamp .
                                     "')";
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         if (
                                             $Dbname[$IMEI_Val] != "va_aalayam"
                                         ) {
                                             $Windspeed[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = $Fetch_Result["WindSpeed"];
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = $Fetch_Result["Import_Max"];
                                             $Array_Import[
                                                 $Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 500
                                                     ? $Import_LCS[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = $Fetch_Result["Gen1_Max"];
                                             $Array_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 15000
                                                     ? $Total_Gen[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = $Fetch_Result["Run_Max"];
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = $Fetch_Result["Gen1H_Max"];
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ] > "24" &&
                                                 $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ] < "50"
                                                     ? "24"
                                                     : $Gen1[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val];
                                             $Array_Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = 24 - $Fetch_Result["Line_Max"];
                                             $Array_BD[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $BD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Array_GD[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $GA_Percent[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 ((24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) /
                                                     24) *
                                                 100;
                                             $Loss_Due_To_GD[
                                                 $Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 ($Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] /
                                                     $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) *
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val];
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 24 -
                                                 ($GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] +
                                                     $BD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val] +
                                                     $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]);
                                             $Array_Lull[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $Lull_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Loss_Due_To_BD[
                                                 $Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 ($Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] /
                                                     $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) *
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val];
                                             $MA_Percent[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 ((24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val] -
                                                     $BD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) /
                                                     (24 -
                                                         $GD_Hours[
                                                             $Fetch_Result[
                                                                 "IMEI"
                                                             ]
                                                         ][$DATE_Val])) *
                                                 100;
                                         } else {
                                             $Windspeed[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = $Fetch_Result["WindSpeed"];
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Fetch_Result["Import_Max"] -
                                                 $Fetch_Result["Import_Min"];
                                             $Array_Import[
                                                 $Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 500
                                                     ? $Import_LCS[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Fetch_Result["Gen1_Max"] -
                                                 $Fetch_Result["Gen1_Min"];
                                             $Array_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 15000
                                                     ? $Total_Gen[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Fetch_Result["Run_Max"] -
                                                 $Fetch_Result["Run_Min"];
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Fetch_Result["Gen1H_Max"] -
                                                 $Fetch_Result["Gen1H_Min"];
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ] > "24" &&
                                                 $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ] < "50"
                                                     ? "24"
                                                     : $Gen1[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val];
                                             $Array_Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 24 -
                                                 ($Fetch_Result["Line_Max"] -
                                                     $Fetch_Result["Line_Min"]);
                                             $Array_BD[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $BD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Array_GD[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $GA_Percent[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 ((24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) /
                                                     24) *
                                                 100;
                                             $Loss_Due_To_GD[
                                                 $Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 ($Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] /
                                                     $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) *
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val];
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 24 -
                                                 ($GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] +
                                                     $BD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val] +
                                                     $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]);
                                             $Array_Lull[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] > 0 &&
                                                 $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] <= 25
                                                     ? $Lull_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]
                                                     : "0";
                                             $Loss_Due_To_BD[
                                                 $Fetch_Result["IMEI"]
                                             ][$DATE_Val] =
                                                 ($Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] /
                                                     $Run_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) *
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val];
                                             $MA_Percent[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] =
                                                 ((24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val] -
                                                     $BD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val]) /
                                                     (24 -
                                                         $GD_Hours[
                                                             $Fetch_Result[
                                                                 "IMEI"
                                                             ]
                                                         ][$DATE_Val])) *
                                                 100;
                                         }
                                     }
                                 }
                             } //endif isset

                             if ($Format[$IMEI_Val] == 8) {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F8_IMEI) .
                                     ")  and (Date_S= '" .
                                     $Date_Stamp .
                                     "')";
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         $Windspeed[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] = $Fetch_Result["WindSpeed"];
                                         $Import_LCS[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] = $Fetch_Result["Import_Max"];
                                         $Array_Import[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 500
                                                 ? $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Total_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] = $Fetch_Result["Gen1_Max"];
                                         $Array_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 15000
                                                 ? $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] = $Fetch_Result["Run_Max"];
                                         $BD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] = $Fetch_Result["Gen1H_Max"];
                                         $Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "50"
                                                 ? "24"
                                                 : $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ];
                                         $Array_Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] = 24 - $Fetch_Result["Line_Max"];
                                         $Array_BD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Array_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 24) *
                                             100;
                                         $Loss_Due_To_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $Lull_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             24 -
                                             ($GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] +
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] +
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]);
                                         $Array_Lull[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Loss_Due_To_BD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $MA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] -
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 (24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val])) *
                                             100;
                                     }
                                 }
                             } //endif isset

                             if ($Format[$IMEI_Val] == "10") {
                                 $Gen_Mysql_Query =
                                     "select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI in (" .
                                     implode(",", $F10_IMEI) .
                                     ")  and (Date_S= '" .
                                     $Date_Stamp .
                                     "')";
                                 //echo $Gen_Mysql_Query;
                                 if (
                                     !($Gen_Mysql_Query_Result = $db->query(
                                         $Gen_Mysql_Query
                                     ))
                                 ) {
                                     die($db->error);
                                 }
                                 if ($Gen_Mysql_Query_Result->num_rows >= 1) {
                                     while (
                                         $Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()
                                     ) {
                                         $Import_LCS[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Import_Max"] -
                                             $Fetch_Result["Import_Min"];
                                         $Array_Import[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Import_LCS[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 500
                                                 ? $Import_LCS[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Total_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1_Max"] -
                                             $Fetch_Result["Gen1_Min"];
                                         $Array_Gen[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 6000
                                                 ? $Total_Gen[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Run_Max"] -
                                             $Fetch_Result["Run_Min"];
                                         $Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "100"
                                                 ? "24"
                                                 : $Run[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ];
                                         $Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Fetch_Result["Gen1H_Max"] -
                                             $Fetch_Result["Gen1H_Min"] +
                                             ($Fetch_Result["Gen2H_Max"] -
                                                 $Fetch_Result["Gen2H_Min"]);
                                         $Gen1[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > "24" &&
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] < "50"
                                                 ? "24"
                                                 : $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ];
                                         $Lull_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] -
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         if (
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] == -1
                                         ) {
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] = 0;
                                         }
                                         $Run_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Gen1[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $Array_Run[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Run_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             24 -
                                             ($Fetch_Result["Line_Max"] -
                                                 $Fetch_Result["Line_Min"]);
                                         $Array_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $GA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 24) *
                                             100;
                                         $Array_Lull[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $Lull_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                         $Loss_Due_To_GD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $BD_Hours[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             24 -
                                             ($GD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] +
                                                 $Lull_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] +
                                                 $Gen1[$Fetch_Result["IMEI"]][
                                                     $DATE_Val
                                                 ]);
                                         $Loss_Due_To_BD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ($Total_Gen[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] /
                                                 $Run_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) *
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ];
                                         $MA_Percent[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             ((24 -
                                                 $GD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val] -
                                                 $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]) /
                                                 (24 -
                                                     $GD_Hours[
                                                         $Fetch_Result["IMEI"]
                                                     ][$DATE_Val])) *
                                             100;
                                         $Array_BD[$Fetch_Result["IMEI"]][
                                             $DATE_Val
                                         ] =
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] > 0 &&
                                             $BD_Hours[$Fetch_Result["IMEI"]][
                                                 $DATE_Val
                                             ] <= 25
                                                 ? $BD_Hours[
                                                     $Fetch_Result["IMEI"]
                                                 ][$DATE_Val]
                                                 : "0";
                                     } //end while
                                 }
                             } //endif isset
                         } //end foreach
                     }
                     foreach ($Date_Array as $DATE_Val) {
                         foreach ($DGR_IMEI as $IMEI_Val) {
                             $Yesterday = date(
                                 "d.m.Y",
                                 strtotime($DATE_Val) - 86400
                             );
                             /*if($Export_Kwh_6to9[$IMEI_Val][$DATE_Val])
							$Export_C1[$IMEI_Val][$DATE_Val]=($Export_Kwh_6to9[$IMEI_Val][$DATE_Val]-$Export_Kwh_6to9[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Export_Kwh_18to21[$IMEI_Val][$DATE_Val])
							$Export_C2[$IMEI_Val][$DATE_Val]=($Export_Kwh_18to21[$IMEI_Val][$DATE_Val]-$Export_Kwh_18to21[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Export_Kwh_21to22[$IMEI_Val][$DATE_Val])
							$Export_C3[$IMEI_Val][$DATE_Val]=($Export_Kwh_21to22[$IMEI_Val][$DATE_Val]-$Export_Kwh_21to22[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Export_Kwh_22to5[$IMEI_Val][$DATE_Val])
							$Export_C4[$IMEI_Val][$DATE_Val]=($Export_Kwh_22to5[$IMEI_Val][$DATE_Val]-$Export_Kwh_22to5[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];	
							if($Export_Kwh_5to6_9to18[$IMEI_Val][$DATE_Val])
							$Export_C5[$IMEI_Val][$DATE_Val]=($Export_Kwh_5to6_9to18[$IMEI_Val][$DATE_Val]-$Export_Kwh_5to6_9to18[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Import_Kwh_6to9[$IMEI_Val][$DATE_Val])
							$Import_C1[$IMEI_Val][$DATE_Val]=($Import_Kwh_6to9[$IMEI_Val][$DATE_Val]-$Import_Kwh_6to9[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Import_Kwh_18to21[$IMEI_Val][$DATE_Val])
							$Import_C2[$IMEI_Val][$DATE_Val]=($Import_Kwh_18to21[$IMEI_Val][$DATE_Val]-$Import_Kwh_18to21[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Import_Kwh_21to22[$IMEI_Val][$DATE_Val])
							$Import_C3[$IMEI_Val][$DATE_Val]=($Import_Kwh_21to22[$IMEI_Val][$DATE_Val]-$Import_Kwh_21to22[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Import_Kwh_22to5[$IMEI_Val][$DATE_Val])
							$Import_C4[$IMEI_Val][$DATE_Val]=($Import_Kwh_22to5[$IMEI_Val][$DATE_Val]-$Import_Kwh_22to5[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Import_Kwh_5to6_9to18[$IMEI_Val][$DATE_Val])
							$Import_C5[$IMEI_Val][$DATE_Val]=($Import_Kwh_5to6_9to18[$IMEI_Val][$DATE_Val]-$Import_Kwh_5to6_9to18[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Import_Rkvah[$IMEI_Val][$DATE_Val])
							$Import_Rkvah_Curr[$IMEI_Val][$DATE_Val]=( $Import_Rkvah[$IMEI_Val][$DATE_Val]-$Import_Rkvah[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Export_Rkvah[$IMEI_Val][$DATE_Val])
							$Export_Rkvah_Curr[$IMEI_Val][$DATE_Val]= ($Export_Rkvah[$IMEI_Val][$DATE_Val]-$Export_Rkvah[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Import_Kvarh[$IMEI_Val][$DATE_Val])
							$Import_Kvarh_Curr[$IMEI_Val][$DATE_Val]= ($Import_Kvarh[$IMEI_Val][$DATE_Val]-$Import_Kvarh[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val];
							if($Export_Kvarh[$IMEI_Val][$DATE_Val])
							$Export_Kvarh_Curr[$IMEI_Val][$DATE_Val]= ($Export_Kvarh[$IMEI_Val][$DATE_Val]-$Export_Kvarh[$IMEI_Val][$Yesterday])*$EB_IMEI[$IMEI_Val]; */
                             ?>
                        <tr>
                       		<td class="tab-head-td1" align="left"><?= $DATE_Val != ""
                             ? $DATE_Val
                             : "0" ?> </td>              
				<td class="tab-head-td1" align="left"><?= $Device_Name[$IMEI_Val] ?></td>
<?php if ($Format[$IMEI_Val] == "2" || $Format[$IMEI_Val] == "10") { ?>
              			<td class="tab-head-td1" align="left"><?= $Total_Gen[$IMEI_Val][
                     $DATE_Val
                 ] >= 0 &&
                 $Total_Gen[$IMEI_Val][$DATE_Val] <= 6000 * ($diff + 1)
                     ? round($Total_Gen[$IMEI_Val][$DATE_Val], 2)
                     : "000" ?></td>                  
<?php } elseif ($Format[$IMEI_Val] == "3") { ?>
              			<td class="tab-head-td1" align="left"><?= $Total_Gen[$IMEI_Val][
                     $DATE_Val
                 ] >= 0 &&
                 $Total_Gen[$IMEI_Val][$DATE_Val] <= 18000 * ($diff + 1)
                     ? round($Total_Gen[$IMEI_Val][$DATE_Val], 2)
                     : "000" ?></td>                  
<?php } else { ?>
				<td class="tab-head-td1" align="left"><?= $Total_Gen[$IMEI_Val][$DATE_Val] >=
        0 && $Total_Gen[$IMEI_Val][$DATE_Val] <= 15000 * ($diff + 1)
        ? round($Total_Gen[$IMEI_Val][$DATE_Val], 2)
        : "000" ?></td>                  
<?php } ?>
				<td class="tab-head-td1" align="left"><?= ($Import_LCS[$IMEI_Val][$DATE_Val] !=
        "" &&
        $Import_LCS[$IMEI_Val][$DATE_Val] >= 0) ||
    $Import_LCS[$IMEI_Val][$DATE_Val] == 0
        ? round($Import_LCS[$IMEI_Val][$DATE_Val], 2)
        : "000" ?></td>                 
              		
			<td class="tab-head-td1" align="left">24</td>  
                     	<td class="tab-head-td1" align="left"><?= $Run_Hours[
                          $IMEI_Val
                      ][$DATE_Val] >= 0 &&
                      $Run_Hours[$IMEI_Val][$DATE_Val] <= 24
                          ? round($Run_Hours[$IMEI_Val][$DATE_Val], 2)
                          : "000" ?></td>                               
                        <td class="tab-head-td1" align="left"><?= $GD_Hours[
                            $IMEI_Val
                        ][$DATE_Val] >= 0 &&
                        $GD_Hours[$IMEI_Val][$DATE_Val] <= 24
                            ? round($GD_Hours[$IMEI_Val][$DATE_Val], 2)
                            : "000" ?></td> 
			<td class="tab-head-td1" align="left"><?= $BD_Hours[$IMEI_Val][$DATE_Val] >=
       0 && $BD_Hours[$IMEI_Val][$DATE_Val] <= 24
       ? round($BD_Hours[$IMEI_Val][$DATE_Val], 2)
       : "000" ?></td>                                     
                        <td class="tab-head-td1" align="left"><?= $Lull_Hours[
                            $IMEI_Val
                        ][$DATE_Val] >= 0 &&
                        $Lull_Hours[$IMEI_Val][$DATE_Val] <= 24
                            ? round($Lull_Hours[$IMEI_Val][$DATE_Val], 2)
                            : "000" ?></td>   
                        <td class="tab-head-td1" align="left"><?= $GA_Percent[
                            $IMEI_Val
                        ][$DATE_Val] != ""
                            ? round($GA_Percent[$IMEI_Val][$DATE_Val], 2)
                            : "000" ?></td>
			<?php if ($Cook_Variable[7] = "va_mtk") { ?>
						<td class="tab-head-td1" align="left"><?= $Loss_Due_To_GD[$IMEI_Val][
          $DATE_Val
      ] > 0 && $Loss_Due_To_GD[$IMEI_Val][$DATE_Val] <= 10000
          ? round($Loss_Due_To_GD[$IMEI_Val][$DATE_Val], 2)
          : "000" ?></td>
						<td class="tab-head-td1" align="left"><?= $Loss_Due_To_BD[$IMEI_Val][
          $DATE_Val
      ] > 0 && $Loss_Due_To_BD[$IMEI_Val][$DATE_Val] <= 10000
          ? round($Loss_Due_To_BD[$IMEI_Val][$DATE_Val], 2)
          : "000" ?></td>
						<?php } ?>
			<!--<td class="tab-head-td1" align="left"><?= $MA_Percent[$IMEI_Val][
       $DATE_Val
   ] != ""
       ? round($MA_Percent[$IMEI_Val][$DATE_Val], 2)
       : "0" ?></td>   
			<td class="tab-head-td1" align="left"><?= $BD_Hours[$IMEI_Val][$DATE_Val] != ""
       ? $BD_Hours[$IMEI_Val][$DATE_Val]
       : "0" ?></td>    
			<td class="tab-head-td1" align="left"><?= $Loss_Due_To_GD[$IMEI_Val][
       $DATE_Val
   ] != ""
       ? round($Loss_Due_To_GD[$IMEI_Val][$DATE_Val], 2)
       : "0" ?></td> 
			<td class="tab-head-td1" align="left"><?= $Loss_Due_To_BD[$IMEI_Val][
       $DATE_Val
   ] != ""
       ? round($Loss_Due_To_BD[$IMEI_Val][$DATE_Val], 2)
       : "0" ?></td>     
                        <td class="tab-head-td1" align="left"><?= $Remarks[
                            $IMEI_Val
                        ][$DATE_Val] != ""
                            ? $Remarks[$IMEI_Val][$DATE_Val]
                            : "Nil" ?></td> -->
							
                        </tr>
						<?php
                         }
                     }
                     ?>
							<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>

						<td class="tab-head-td1" align="left"><b><?= arraySumRecursive($Array_Gen) > 0
          ? round(arraySumRecursive($Array_Gen), 2)
          : "000" ?></b></td>
							<td class="tab-head-td1" align="left"><b><?= arraySumRecursive($Array_Import) >
       0
           ? round(arraySumRecursive($Array_Import), 2)
           : "000" ?></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b><?= arraySumRecursive($Array_Run) >= 0
           ? round(arraySumRecursive($Array_Run), 2)
           : "000" ?></b></td>
							<td class="tab-head-td1" align="left"><b><?= arraySumRecursive($Array_GD) >= 0
           ? round(arraySumRecursive($Array_GD), 2)
           : "000" ?></b></td>
							<td class="tab-head-td1" align="left"><b><?= arraySumRecursive($Array_BD) >= 0
           ? round(arraySumRecursive($Array_BD), 2)
           : "000" ?></b></td>
							<!--<td class="tab-head-td1" align="left"><b><?= arraySumRecursive($BD_Hours) <=
           $Daydiff && arraySumRecursive($BD_Hours) >= 0
           ? round(arraySumRecursive($BD_Hours), 2)
           : "0" ?></b></td>-->
							<td class="tab-head-td1" align="left"><b><?= arraySumRecursive($Array_Lull) >= 0
           ? round(arraySumRecursive($Array_Lull), 2)
           : "000" ?></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
						</tr>

					</table>
         <?php //print_r($Export_C1);

     }
 // Mysql Record End
 else {echo $No_Records;}

//	}//if p is set
//ifelse end
?>
	<?php
//}//xls=1
?>            </td>	
        </tr>