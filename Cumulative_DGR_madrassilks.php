          <!-- 
            Daily Generation Report
        -->
	<?php


//echo $_REQUEST['FType'] ."is format type";
	if ($XLS == 0){
?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />
			<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			
			<?php }?>
			</td>
		</tr>
<?php
	}
?>					
	
        <tr>
            <td width="550px">
<table width="550px" border="<?=$XLS == 1?'1':'0'?>" align="left" cellpadding="1" cellspacing="1" class="innertab1_">	
	<?php



	if ($XLS == 1){//xls=1



	?>

						<tr>
							<td class="tab-head-td" colspan="10"  align="center"><b>Cumulative Daily Generation Detail Report- <?=$_REQUEST['inputDate']?></b></td>
						</tr>
					   <tr>
							<!--<td class="tab-head-td"  colspan="10"  align="left"><b>Site:</b><?= implode(",",array_unique($Site_Location)) ?></td>-->
					
 <?php 

		}
			if ($XLS == 0){

					?>

					<tr>

						<td  class="tab-head-tr"  colspan="29" align="left">&nbsp;&nbsp;<b>Cumulative Daily Generation Detail Report- <?=$_REQUEST['inputDate']?></b></td>

					</tr>

					<?php 

					}

					?>
	<?php
          //  if(isset($_REQUEST['p']) && $_REQUEST['p'] == 34){//if p is set
		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
		$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);

		
		if($Cook_Variable[2] ==3 || $Cook_Variable[2] ==2)

			$Device_Query="select Device_Name,Format_Type,Closing_Time,Group_Name,Capacity, Connect_Feeder,Site_Location,State,IMEI from device_register where Parent_ID=" .$Cook_Variable[6] ."  order by Group_Name,Connect_Feeder DESC";
		elseif($Cook_Variable[2] ==4)
			$Device_Query="select Device_Name,Format_Type,Closing_Time,Group_Name,Capacity, Connect_Feeder,Site_Location,State,IMEI from device_register where Account_ID=" .$Cook_Variable[3] ."  order by Group_Name,Connect_Feeder DESC";
		//echo $Device_Query;
		if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }
			$Device_Query_Result_Count=$Device_Query_Result->num_rows;
            if($Device_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				$DGR_IMEI[$Fetch_Result['IMEI']]=$Fetch_Result['IMEI'];
				$Group_IMEI[$Fetch_Result['Group_Name']][$Fetch_Result['IMEI']]=$Fetch_Result['IMEI'];
				$Device_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
				$Group_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Group_Name'];
				$Company_Name[$Fetch_Result['Group_Name']] = $Fetch_Result['Group_Name'];
				$Capacity[$Fetch_Result['IMEI']] = $Fetch_Result['Capacity'];
				$Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
				$Site_Location[$Fetch_Result['IMEI']] = $Fetch_Result['Site_Location'];
				$Format_Type[$Fetch_Result['Format_Type']] = $Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']=='1'){
					$F1_IMEI[]=$Fetch_Result['IMEI'];
					$Table_Name="device_data";
				}
				if($Fetch_Result['Format_Type']==2) {
					$F2_IMEI[]=$Fetch_Result['IMEI'];
					$Table_Name="device_data_f2";
				}
				if($Fetch_Result['Format_Type']==3) {
					$F3_IMEI[]=$Fetch_Result['IMEI'];
					$Table_Name="device_data_f3";
				}
				if($Fetch_Result['Format_Type']==4)
					$F4_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==6) {
					$F6_IMEI[]=$Fetch_Result['IMEI'];
					$Table_Name="device_data_f6";
				}
				if($Fetch_Result['Format_Type']==7)
					$F7_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==8)
					$F8_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==9)
					$F9_IMEI[]=$Fetch_Result['IMEI'];
				if($Fetch_Result['Format_Type']==10) {
					$F10_IMEI[]=$Fetch_Result['IMEI'];
					$Table_Name="device_data_f10";
				}

			if($Fetch_Result['Group_Name']=='Space Textiles Pvt Ltd'){
					$Space_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='KKV Agro Powers Ltd'){
					$Kkv_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='SCM International Impex Pvt Ltd'){
					$Scm_impex_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='Madras Silks India Pvt Ltd' && $Fetch_Result['Format_Type']==2){
					$Madras_silks_F2_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='Madras Silks India Pvt Ltd' && $Fetch_Result['Format_Type']==1){
					$Madras_silks_F1_IMEI[]=$Fetch_Result['IMEI'];
				}
			
			if($Fetch_Result['Group_Name']=='SCM Garments Pvt Ltd' && $Fetch_Result['Format_Type']==3){
					$Scm_garments_F3_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='SCM Garments Pvt Ltd' && $Fetch_Result['Format_Type']==1 ){
					$Scm_garments_F1_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='SCM Garments Pvt Ltd' && $Fetch_Result['Format_Type']==2 ){
					$Scm_garments_F2_IMEI[]=$Fetch_Result['IMEI'];
				}
			
			
			if($Fetch_Result['Group_Name']=='SCM Green Power Ltd' && $Fetch_Result['Format_Type']==2){
					$Scm_green_F2_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='SCM Green Power Ltd' && $Fetch_Result['Format_Type']==1){
					$Scm_green_F1_IMEI[]=$Fetch_Result['IMEI'];
				}
			
			if($Fetch_Result['Group_Name']=='GK green Power Pvt Ltd' && $Fetch_Result['Format_Type']==2){
					$Gk_F2_IMEI[]=$Fetch_Result['IMEI'];
				}
			if($Fetch_Result['Group_Name']=='GK green Power Pvt Ltd' && $Fetch_Result['Format_Type']==1){
					$Gk_F1_IMEI[]=$Fetch_Result['IMEI'];
				}
			}
		}
			//print_r($F1_IMEI);print_r($F2_IMEI);print_r($F3_IMEI);print_r($F4_IMEI);print_r($F5_IMEI);print_r($F6_IMEI);
					
//print_r($Company_Name);
//print_r($DGR_IMEI);
//print_r($Scm_green_F2_IMEI);
//print_r($Madras_silks_F2_IMEI);
				$Format_Type = array_unique($Format_Type);
//print_r($Format_Type);
	
				if($Device_Query_Result_Count >= 1){//record count if
		?>				
					
					
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>S.No</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Company Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Capacity MW</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Net Generation</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Remarks</b></td>
                                            </tr>
                 			 
						<?php 
							$DGR_IMEI_Str=implode(",",$DGR_IMEI);
							
					########EB METER CALCULATION##########
							
							$MI = 1;
						//print_r($DATE_F);
							$Tot_All_Generation=0;
							$Tot_Import_LCS=0;
							$Days=0;
							$Group_Net_Gen=0;  
$datediff = abs($From_D_Epoch - $To_D_Epoch);
     $diff= floor($datediff/(60*60*24));
$Daydiff=24*($diff+1)*count($DGR_IMEI);
$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){
//foreach($Group_IMEI as $Group_Val){						

							
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time[$IMEI_Val]=="00:00:00"){
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							}
							else{
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							}					
			
						if(isset($Space_IMEI)){										
	$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,abs(PAT_Gen0) as Import_Min,Pat_Gen1 as G1_Min from $Cook_Variable[7].device_data where IMEI in (".implode(",",$Space_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
						if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {
								$Space_Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];

								$Space_G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,abs(t1.PAT_Gen0) as Import_Max,t1.Pat_Gen1 as G1_Max from $Cook_Variable[7].device_data t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Space_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {
								

								$Space_Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];

								$Space_G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								
						 }
								}
foreach($Space_IMEI as $IMEI_Val){
$Space_Import_LCS[$IMEI_Val][$DATE_Val]=round(($Space_Import_Max[$IMEI_Val][$DATE_Val]-$Space_Import_Min[$IMEI_Val][$DATE_Val]),2);

		$Space_Total_Gen[$IMEI_Val][$DATE_Val]=round(($Space_G1_Max[$IMEI_Val][$DATE_Val]-$Space_G1_Min[$IMEI_Val][$DATE_Val]),2);
		$Space_Net_Gen[$IMEI_Val][$DATE_Val]=$Space_Total_Gen[$IMEI_Val][$DATE_Val]-$Space_Import_LCS[$IMEI_Val][$DATE_Val];
}
$Space_Group_Gen=arraySumRecursive($Space_Net_Gen);
//print_r($Space_Group_Gen);
							}//endif isset


				if(isset($Kkv_IMEI)){
		$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,abs(PAT_Gen0) as Import_Min,Pat_Gen1 as G1_Min from $Cook_Variable[7].device_data where IMEI in (".implode(",",$Kkv_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
								if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {

								$Kkv_Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];

								$Kkv_G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,abs(t1.PAT_Gen0) as Import_Max,t1.Pat_Gen1 as G1_Max from $Cook_Variable[7].device_data t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Kkv_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {

								$Kkv_Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];

								$Kkv_G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								
						 }
								}
foreach($Kkv_IMEI as $IMEI_Val){
$Kkv_Import_LCS[$IMEI_Val][$DATE_Val]=round(($Kkv_Import_Max[$IMEI_Val][$DATE_Val]-$Kkv_Import_Min[$IMEI_Val][$DATE_Val]),2);

		$Kkv_Total_Gen[$IMEI_Val][$DATE_Val]=round(($Kkv_G1_Max[$IMEI_Val][$DATE_Val]-$Kkv_G1_Min[$IMEI_Val][$DATE_Val]),2);
		$Kkv_Net_Gen[$IMEI_Val][$DATE_Val]=$Kkv_Total_Gen[$IMEI_Val][$DATE_Val]-$Kkv_Import_LCS[$IMEI_Val][$DATE_Val];
}
$Kkv_Group_Gen=arraySumRecursive($Kkv_Net_Gen);
//print_r($Kkv_Group_Gen);
							}//endif isset
							
					if(isset($Madras_silks_F1_IMEI)){
if(isset($Madras_silks_F1_IMEI)){
			$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,abs(PAT_Gen0) as Import_Min,Pat_Gen1 as G1_Min from $Cook_Variable[7].device_data where IMEI in (".implode(",",$Madras_silks_F1_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
			//echo $Gen_Mysql_Query_Min;
					if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {

								$Madras_silks_Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];

								$Madras_silks_G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,abs(t1.PAT_Gen0) as Import_Max,t1.Pat_Gen1 as G1_Max from $Cook_Variable[7].device_data t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Madras_silks_F1_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {
								$Madras_silks_Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];

								$Madras_silks_G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								
						 }
								}
foreach($Madras_silks_F1_IMEI as $IMEI_Val){
$Madras_silks_Import_LCS[$IMEI_Val][$DATE_Val]=round(($Madras_silks_Import_Max[$IMEI_Val][$DATE_Val]-$Madras_silks_Import_Min[$IMEI_Val][$DATE_Val]),2);

		$Madras_silks_Total_Gen[$IMEI_Val][$DATE_Val]=round(($Madras_silks_G1_Max[$IMEI_Val][$DATE_Val]-$Madras_silks_G1_Min[$IMEI_Val][$DATE_Val]),2);
		$Madras_silks_Net_Gen[$IMEI_Val][$DATE_Val]=$Madras_silks_Total_Gen[$IMEI_Val][$DATE_Val]-$Madras_silks_Import_LCS[$IMEI_Val][$DATE_Val];
}
$Madras_silks_Group_Gen=arraySumRecursive($Madras_silks_Net_Gen);
//print_r($Madras_silks_Group_Gen);
}
if(isset($Madras_silks_F2_IMEI)){
			$Gen_Mysql_Query_Min_type2="SELECT IMEI,Date_S,PAT_Gen2 as G2_Min,Pat_Gen1 as G1_Min,abs(Import_Kwh) as Import_Min from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$Madras_silks_F2_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
//echo $Gen_Mysql_Query_Min_type2;
								if (!$Gen_Mysql_Query_Result_Min_type2 = $db->query($Gen_Mysql_Query_Min_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min_type2->num_rows >= 1)
            {
                while($Fetch_Result_Min_type2 = $Gen_Mysql_Query_Result_Min_type2->fetch_array()) {
								$Madras_silks_G2_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G2_Min'];

								$Madras_silks_G1_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G1_Min'];
								$Madras_silks_Import_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['Import_Min'];
								 }
								}
	$Gen_Mysql_Query_Max_type2="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen2 as G2_Max,t1.Pat_Gen1 as G1_Max,abs(t1.Import_Kwh) as Import_Max from $Cook_Variable[7].device_data_f2 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f2 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Madras_silks_F2_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max_type2 = $db->query($Gen_Mysql_Query_Max_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max_type2->num_rows >= 1)
            {
                while($Fetch_Result_Max_type2 = $Gen_Mysql_Query_Result_Max_type2->fetch_array()) {

								$Madras_silks_G2_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G2_Max'];
								$Madras_silks_Import_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['Import_Max'];
								$Madras_silks_G1_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G1_Max'];
								
								 }
								}


		
		
foreach($Madras_silks_F2_IMEI as $IMEI_Val){
$Madras_silks_Import_LCS_type2[$IMEI_Val][$DATE_Val]=round(($Madras_silks_Import_Max_type2[$IMEI_Val][$DATE_Val]-$Madras_silks_Import_Min_type2[$IMEI_Val][$DATE_Val]),2);

		$Madras_silks_Total_Gen_type2[$IMEI_Val][$DATE_Val]=round((($Madras_silks_G1_Max_type2[$IMEI_Val][$DATE_Val]-$Madras_silks_G1_Min_type2[$IMEI_Val][$DATE_Val])+($Madras_silks_G2_Max_type2[$IMEI_Val][$DATE_Val]-$Madras_silks_G2_Min_type2[$IMEI_Val][$DATE_Val])),2);
		$Madras_silks_Net_Gen_type2[$IMEI_Val][$DATE_Val]=$Madras_silks_Total_Gen_type2[$IMEI_Val][$DATE_Val]-$Madras_silks_Import_LCS_type2[$IMEI_Val][$DATE_Val];
}
$Madras_silks_Group_Gen_type2=arraySumRecursive($Madras_silks_Net_Gen_type2);
//print_r($Scm_impex_Group_Gen);

								
							}
								}//endif isset
							if(isset($Scm_garments_F1_IMEI)){
if(isset($Scm_garments_F3_IMEI)) {
			$Gen_Mysql_Query_Min_type3="SELECT IMEI,Date_S,Production_Total as G1_Min,abs(Import_Kwh) as Import_Min from $Cook_Variable[7].device_data_f3 where IMEI in (".implode(",",$Scm_garments_F3_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
//echo $Gen_Mysql_Query_Min_type3;
								if (!$Gen_Mysql_Query_Result_Min_type3 = $db->query($Gen_Mysql_Query_Min_type3))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min_type3->num_rows >= 1)
            {
                while($Fetch_Result_Min_type3 = $Gen_Mysql_Query_Result_Min_type3->fetch_array()) {

								$Scm_garments_G1_Min_type3[$Fetch_Result_Min_type3['IMEI']][$DATE_Val]=$Fetch_Result_Min_type3['G1_Min'];
								$Scm_garments_Import_Min_type3[$Fetch_Result_Min_type3['IMEI']][$DATE_Val]=$Fetch_Result_Min_type3['Import_Min'];
								 }
								}
	$Gen_Mysql_Query_Max_type3="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Production_Total as G1_Max,abs(t1.Import_Kwh) as Import_Max from $Cook_Variable[7].device_data_f3 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f3 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Scm_garments_F3_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
	//echo $Gen_Mysql_Query_Max_type3;
							if (!$Gen_Mysql_Query_Result_Max_type3 = $db->query($Gen_Mysql_Query_Max_type3))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max_type3->num_rows >= 1)
            {
                while($Fetch_Result_Max_type3 = $Gen_Mysql_Query_Result_Max_type3->fetch_array()) {
								$Scm_garments_Import_Max_type3[$Fetch_Result_Max_type3['IMEI']][$DATE_Val]=$Fetch_Result_Max_type3['Import_Max'];
								$Scm_garments_G1_Max_type3[$Fetch_Result_Max_type3['IMEI']][$DATE_Val]=$Fetch_Result_Max_type3['G1_Max'];
								
								 }
								}
foreach($Scm_garments_F3_IMEI as $IMEI_Val){
$Scm_garments_Import_LCS_type3[$IMEI_Val][$DATE_Val]=round(($Scm_garments_Import_Max_type3[$IMEI_Val][$DATE_Val]-$Scm_garments_Import_Min_type3[$IMEI_Val][$DATE_Val]),2);

		$Scm_garments_Total_Gen_type3[$IMEI_Val][$DATE_Val]=round(($Scm_garments_G1_Max_type3[$IMEI_Val][$DATE_Val]-$Scm_garments_G1_Min_type3[$IMEI_Val][$DATE_Val]),2);
		$Scm_garments_Net_Gen_type3[$IMEI_Val][$DATE_Val]=$Scm_garments_Total_Gen_type3[$IMEI_Val][$DATE_Val]-$Scm_garments_Import_LCS_type3[$IMEI_Val][$DATE_Val];
}
$Scm_garments_Group_Gen_type3=arraySumRecursive($Scm_garments_Net_Gen_type3);
//print_r($Scm_garments_Group_Gen);	
}
if(isset($Scm_garments_F1_IMEI)) {	
	$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,abs(PAT_Gen0) as Import_Min,Pat_Gen1 as G1_Min from $Cook_Variable[7].device_data where IMEI in (".implode(",",$Scm_garments_F1_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
			//echo $Gen_Mysql_Query_Min;
					if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {

								$Scm_garments_Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];

								$Scm_garments_G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,abs(t1.PAT_Gen0) as Import_Max,t1.Pat_Gen1 as G1_Max from $Cook_Variable[7].device_data t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Scm_garments_F1_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
			//echo $Gen_Mysql_Query_Max;
					if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {

								$Scm_garments_Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];

								$Scm_garments_G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								
						 }
								}
foreach($Scm_garments_F1_IMEI as $IMEI_Val){
$Scm_garments_Import_LCS[$IMEI_Val][$DATE_Val]=round(($Scm_garments_Import_Max[$IMEI_Val][$DATE_Val]-$Scm_garments_Import_Min[$IMEI_Val][$DATE_Val]),2);

		$Scm_garments_Total_Gen[$IMEI_Val][$DATE_Val]=round(($Scm_garments_G1_Max[$IMEI_Val][$DATE_Val]-$Scm_garments_G1_Min[$IMEI_Val][$DATE_Val]),2);
		$Scm_garments_Net_Gen[$IMEI_Val][$DATE_Val]=$Scm_garments_Total_Gen[$IMEI_Val][$DATE_Val]-$Scm_garments_Import_LCS[$IMEI_Val][$DATE_Val];
}
$Scm_garments_Group_Gen_type1=arraySumRecursive($Scm_garments_Net_Gen);
//print_r($Scm_garments_Group_Gen);
							}
if(isset($Scm_garments_F2_IMEI)){
			$Gen_Mysql_Query_Min_type2="SELECT IMEI,Date_S,PAT_Gen2 as G2_Min,Pat_Gen1 as G1_Min,abs(Import_Kwh) as Import_Min from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$Scm_garments_F2_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
									if (!$Gen_Mysql_Query_Result_Min_type2 = $db->query($Gen_Mysql_Query_Min_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min_type2->num_rows >= 1)
            {
                while($Fetch_Result_Min_type2 = $Gen_Mysql_Query_Result_Min_type2->fetch_array()) {
								$Scm_garments_G2_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G2_Min'];

								$Scm_garments_G1_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G1_Min'];
								$Scm_garments_Import_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['Import_Min'];
								 }
								}
	$Gen_Mysql_Query_Max_type2="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen2 as G2_Max,t1.Pat_Gen1 as G1_Max,abs(t1.Import_Kwh) as Import_Max from $Cook_Variable[7].device_data_f2 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f2 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Scm_garments_F2_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max_type2 = $db->query($Gen_Mysql_Query_Max_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max_type2->num_rows >= 1)
            {
                while($Fetch_Result_Max_type2 = $Gen_Mysql_Query_Result_Max_type2->fetch_array()) {

								$Scm_garments_G2_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G2_Max'];
								$Scm_garments_Import_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['Import_Max'];
								$Scm_garments_G1_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G1_Max'];
								
								 }
								}


		
		
foreach($Scm_garments_F2_IMEI as $IMEI_Val){
$Scm_garments_Import_LCS_type2[$IMEI_Val][$DATE_Val]=round(($Scm_garments_Import_Max_type2[$IMEI_Val][$DATE_Val]-$Scm_garments_Import_Min_type2[$IMEI_Val][$DATE_Val]),2);

		$Scm_garments_Total_Gen_type2[$IMEI_Val][$DATE_Val]=round((($Scm_garments_G1_Max_type2[$IMEI_Val][$DATE_Val]-$Scm_garments_G1_Min_type2[$IMEI_Val][$DATE_Val])+($Scm_garments_G2_Max_type2[$IMEI_Val][$DATE_Val]-$Scm_garments_G2_Min_type2[$IMEI_Val][$DATE_Val])),2);
		$Scm_garments_Net_Gen_type2[$IMEI_Val][$DATE_Val]=$Scm_garments_Total_Gen_type2[$IMEI_Val][$DATE_Val]-$Scm_garments_Import_LCS_type2[$IMEI_Val][$DATE_Val];
}
$Scm_garments_Group_Gen_type2=arraySumRecursive($Scm_garments_Net_Gen_type2);
//print_r($Scm_impex_Group_Gen);

								
							}
						
								
							}  // endif isset

							if(isset($Scm_green_F1_IMEI)){
if(isset($Scm_green_F1_IMEI)){		
	$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,abs(PAT_Gen0) as Import_Min,Pat_Gen1 as G1_Min from $Cook_Variable[7].device_data where IMEI in (".implode(",",$Scm_green_F1_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
								if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {

								$Scm_green_Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];

								$Scm_green_G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,abs(t1.PAT_Gen0) as Import_Max,t1.Pat_Gen1 as G1_Max from $Cook_Variable[7].device_data t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Scm_green_F1_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {

								$Scm_green_Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];

								$Scm_green_G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								
						 }
								}
foreach($Scm_green_F1_IMEI as $IMEI_Val){
$Scm_green_Import_LCS[$IMEI_Val][$DATE_Val]=round(($Scm_green_Import_Max[$IMEI_Val][$DATE_Val]-$Scm_green_Import_Min[$IMEI_Val][$DATE_Val]),2);

		$Scm_green_Total_Gen[$IMEI_Val][$DATE_Val]=round(($Scm_green_G1_Max[$IMEI_Val][$DATE_Val]-$Scm_green_G1_Min[$IMEI_Val][$DATE_Val]),2);
		$Scm_green_Net_Gen[$IMEI_Val][$DATE_Val]=$Scm_green_Total_Gen[$IMEI_Val][$DATE_Val]-$Scm_green_Import_LCS[$IMEI_Val][$DATE_Val];
}
$Scm_green_Group_Gen=arraySumRecursive($Scm_green_Net_Gen);
//print_r($Scm_green_Group_Gen);
}
if(isset($Scm_green_F2_IMEI)){
			$Gen_Mysql_Query_Min_type2="SELECT IMEI,Date_S,PAT_Gen2 as G2_Min,Pat_Gen1 as G1_Min,abs(Import_Kwh) as Import_Min from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$Scm_green_F2_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
								if (!$Gen_Mysql_Query_Result_Min_type2 = $db->query($Gen_Mysql_Query_Min_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min_type2->num_rows >= 1)
            {
                while($Fetch_Result_Min_type2 = $Gen_Mysql_Query_Result_Min_type2->fetch_array()) {
								$Scm_green_G2_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G2_Min'];

								$Scm_green_G1_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G1_Min'];
								$Scm_green_Import_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['Import_Min'];
								 }
								}
	$Gen_Mysql_Query_Max_type2="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen2 as G2_Max,t1.Pat_Gen1 as G1_Max,abs(t1.Import_Kwh) as Import_Max from $Cook_Variable[7].device_data_f2 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f2 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Scm_green_F2_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max_type2 = $db->query($Gen_Mysql_Query_Max_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max_type2->num_rows >= 1)
            {
                while($Fetch_Result_Max_type2 = $Gen_Mysql_Query_Result_Max_type2->fetch_array()) {

								$Scm_green_G2_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G2_Max'];
								$Scm_green_Import_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['Import_Max'];
								$Scm_green_G1_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G1_Max'];
								
								 }
								}


		
		
foreach($Scm_green_F2_IMEI as $IMEI_Val){
$Scm_green_Import_LCS_type2[$IMEI_Val][$DATE_Val]=round(($Scm_green_Import_Max_type2[$IMEI_Val][$DATE_Val]-$Scm_green_Import_Min_type2[$IMEI_Val][$DATE_Val]),2);

		$Scm_green_Total_Gen_type2[$IMEI_Val][$DATE_Val]=round((($Scm_green_G1_Max_type2[$IMEI_Val][$DATE_Val]-$Scm_green_G1_Min_type2[$IMEI_Val][$DATE_Val])+($Scm_green_G2_Max_type2[$IMEI_Val][$DATE_Val]-$Scm_green_G2_Min_type2[$IMEI_Val][$DATE_Val])),2);
		$Scm_green_Net_Gen_type2[$IMEI_Val][$DATE_Val]=$Scm_green_Total_Gen_type2[$IMEI_Val][$DATE_Val]-$Scm_green_Import_LCS_type2[$IMEI_Val][$DATE_Val];
}
$Scm_green_Group_Gen_type2=arraySumRecursive($Scm_green_Net_Gen_type2);
//print_r($Scm_impex_Group_Gen);

								
							}

							}//endif isset



					if(isset($Scm_impex_IMEI)){
			$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,PAT_Gen2 as G2_Min,Pat_Gen1 as G1_Min,abs(Import_Kwh) as Import_Min from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$Scm_impex_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
								if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {

								$Scm_impex_G2_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G2_Min'];

								$Scm_impex_G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								$Scm_impex_Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen2 as G2_Max,t1.Pat_Gen1 as G1_Max,abs(t1.Import_Kwh) as Import_Max from $Cook_Variable[7].device_data_f2 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f2 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Scm_impex_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {
								$Scm_impex_G2_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G2_Max'];
								$Scm_impex_Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];
								$Scm_impex_G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								
								 }
								}


		
		
foreach($Scm_impex_IMEI as $IMEI_Val){
$Scm_impex__Import_LCS[$IMEI_Val][$DATE_Val]=round(($Scm_impex_Import_Max[$IMEI_Val][$DATE_Val]-$Scm_impex_Import_Min[$IMEI_Val][$DATE_Val]),2);

		$Scm_impex_Total_Gen[$IMEI_Val][$DATE_Val]=round((($Scm_impex_G1_Max[$IMEI_Val][$DATE_Val]-$Scm_impex_G1_Min[$IMEI_Val][$DATE_Val])+($Scm_impex_G2_Max[$IMEI_Val][$DATE_Val]-$Scm_impex_G2_Min[$IMEI_Val][$DATE_Val])),2);
		$Scm_impex_Net_Gen[$IMEI_Val][$DATE_Val]=$Scm_impex_Total_Gen[$IMEI_Val][$DATE_Val]-$Scm_impex_Import_LCS[$IMEI_Val][$DATE_Val];
}
$Scm_impex_Group_Gen=arraySumRecursive($Scm_impex_Net_Gen);
//print_r($Scm_impex_Group_Gen);

								
							}//endif isset

							if(isset($Gk_F1_IMEI)){
if(isset($GK_F1_IMEI)) {
			$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,abs(PAT_Gen0) as Import_Min,Pat_Gen1 as G1_Min from $Cook_Variable[7].device_data where IMEI in (".implode(",",$GK_F1_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
								if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {

								$Gk_Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];

								$Gk_G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,abs(t1.PAT_Gen0) as Import_Max,t1.Pat_Gen1 as G1_Max from $Cook_Variable[7].device_data t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$Gk_F1_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {

								$Gk_Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];

								$Gk_G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								
						 }
								}
foreach($Gk_F1_IMEI as $IMEI_Val){
$Gk_Import_LCS[$IMEI_Val][$DATE_Val]=round(($Gk_Import_Max[$IMEI_Val][$DATE_Val]-$Gk_Import_Min[$IMEI_Val][$DATE_Val]),2);

		$Gk_Total_Gen[$IMEI_Val][$DATE_Val]=round(($Gk_G1_Max[$IMEI_Val][$DATE_Val]-$Gk_G1_Min[$IMEI_Val][$DATE_Val]),2);
		$Gk_Net_Gen[$IMEI_Val][$DATE_Val]=$Gk_Total_Gen[$IMEI_Val][$DATE_Val]-$Gk_Import_LCS[$IMEI_Val][$DATE_Val];
}
$Gk_Group_Gen=arraySumRecursive($Gk_Net_Gen);
//print_r($Gk_Group_Gen);
}
if(isset($GK_F2_IMEI)){
			$Gen_Mysql_Query_Min_type2="SELECT IMEI,Date_S,PAT_Gen2 as G2_Min,Pat_Gen1 as G1_Min,abs(Import_Kwh) as Import_Min from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$GK_F2_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI order by Record_Index asc";
//echo $Gen_Mysql_Query_Min_type2;
								if (!$Gen_Mysql_Query_Result_Min_type2 = $db->query($Gen_Mysql_Query_Min_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min_type2->num_rows >= 1)
            {
                while($Fetch_Result_Min_type2 = $Gen_Mysql_Query_Result_Min_type2->fetch_array()) {
								
								$GK_G2_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G2_Min'];

								$GK_G1_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['G1_Min'];
								$GK_Import_Min_type2[$Fetch_Result_Min_type2['IMEI']][$DATE_Val]=$Fetch_Result_Min_type2['Import_Min'];
								 }
								}
	$Gen_Mysql_Query_Max_type2="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen2 as G2_Max,t1.Pat_Gen1 as G1_Max,abs(t1.Import_Kwh) as Import_Max from $Cook_Variable[7].device_data_f2 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f2 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$GK_F2_IMEI).") group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
								if (!$Gen_Mysql_Query_Result_Max_type2 = $db->query($Gen_Mysql_Query_Max_type2))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Max_type2->num_rows >= 1)
            {
                while($Fetch_Result_Max_type2 = $Gen_Mysql_Query_Result_Max_type2->fetch_array()) {

								$GK_G2_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G2_Max'];
								$GK_Import_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['Import_Max'];
								$GK_G1_Max_type2[$Fetch_Result_Max_type2['IMEI']][$DATE_Val]=$Fetch_Result_Max_type2['G1_Max'];
								
								 }
								}


		
		
foreach($GK_F2_IMEI as $IMEI_Val){
$GK_Import_LCS_type2[$IMEI_Val][$DATE_Val]=round(($GK_Import_Max_type2[$IMEI_Val][$DATE_Val]-$GK_Import_Min_type2[$IMEI_Val][$DATE_Val]),2);

		$GK_Total_Gen_type2[$IMEI_Val][$DATE_Val]=round((($GK_G1_Max_type2[$IMEI_Val][$DATE_Val]-$GK_G1_Min_type2[$IMEI_Val][$DATE_Val])+($GK_G2_Max_type2[$IMEI_Val][$DATE_Val]-$GK_G2_Min_type2[$IMEI_Val][$DATE_Val])),2);
		$GK_Net_Gen_type2[$IMEI_Val][$DATE_Val]=$GK_Total_Gen_type2[$IMEI_Val][$DATE_Val]-$GK_Import_LCS_type2[$IMEI_Val][$DATE_Val];
}
$GK_Group_Gen_type2=arraySumRecursive($GK_Net_Gen_type2);
//print_r($Scm_impex_Group_Gen);

								
							}

								
							}   //endif isset

													}//end foreach
//}
						
							//foreach($Company_Name as $Group_Val){
						
							//foreach($Group_IMEI as $IMEI_Val){
							
	



						?>
                        <tr>
                       		<td class="tab-head-td1" align="left">1</td>              
				<td class="tab-head-td1" align="left">The Madras Silks India Pvt Ltd</td>
				<td class="tab-head-td1" align="left">0.75</td>
              			<td class="tab-head-td1" align="left"><?=($Madras_silks_Group_Gen)+($Madras_silks_Group_Gen_type2)?></td>                  			
				<td class="tab-head-td1" align="left"></td>		
</tr>
	<tr>
                       		<td class="tab-head-td1" align="left">2</td>              
				<td class="tab-head-td1" align="left">The KKV Agro Powers</td>
				<td class="tab-head-td1" align="left">2.0</td>
              			<td class="tab-head-td1" align="left"><?=$Kkv_Group_Gen?></td>                  			
				<td class="tab-head-td1" align="left"></td>		
</tr>
	<tr>
                       		<td class="tab-head-td1" align="left">3</td>              
				<td class="tab-head-td1" align="left">SCM International Impex Pvt Ltd</td>
				<td class="tab-head-td1" align="left">1.0</td>
              			<td class="tab-head-td1" align="left"><?=$Scm_impex_Group_Gen?></td>                  			
				<td class="tab-head-td1" align="left"></td>		
</tr>
	<tr>
                       		<td class="tab-head-td1" align="left">4</td>              
				<td class="tab-head-td1" align="left">SCM Green power Pvt Ltd</td>
				<td class="tab-head-td1" align="left">1.25</td>
              			<td class="tab-head-td1" align="left"><?=($Scm_green_Group_Gen)+($Scm_green_Group_Gen_type2)?></td>                  			
				<td class="tab-head-td1" align="left"></td>		
</tr>
	<tr>
                       		<td class="tab-head-td1" align="left">5</td>              
				<td class="tab-head-td1" align="left">Space Textiles Pvt Ltd</td>
				<td class="tab-head-td1" align="left">2.0</td>
              			<td class="tab-head-td1" align="left"><?=$Space_Group_Gen?></td>                  			
				<td class="tab-head-td1" align="left"></td>		
</tr>
	<tr>
                       		<td class="tab-head-td1" align="left">6</td>              
				<td class="tab-head-td1" align="left">GK Green Pvt Ltd</td>
				<td class="tab-head-td1" align="left">2.0</td>
              			<td class="tab-head-td1" align="left"><?=($Gk_Group_Gen)+($GK_Group_Gen_type2)?></td>                  			
				<td class="tab-head-td1" align="left"></td>		
</tr>
	<tr>
                       		<td class="tab-head-td1" align="left">7</td>              
				<td class="tab-head-td1" align="left">SCM Garments Pvt Ltd</td>
				<td class="tab-head-td1" align="left">3.75</td>
              			<td class="tab-head-td1" align="left"><?=($Scm_garments_Group_Gen_type3)+($Scm_garments_Group_Gen_type1)+($Scm_garments_Group_Gen_type2)?></td>                  			
				<td class="tab-head-td1" align="left"></td>		
</tr>
						<?php
								//}

							
//}
						?>
<tr>
							<td class="tab-head-td1" colspan='2' align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b>12.75</b></td>
							<td class="tab-head-td1" align="left"><b><?=($Madras_silks_Group_Gen)+($Madras_silks_Group_Gen_type2)+$Kkv_Group_Gen+$Scm_impex_Group_Gen+($Scm_green_Group_Gen)+($Scm_green_Group_Gen_type2)+$Space_Group_Gen+($Gk_Group_Gen)+($GK_Group_Gen_type2)+($Scm_garments_Group_Gen_type3)+($Scm_garments_Group_Gen_type1)+($Scm_garments_Group_Gen_type2)?></b></td>
							<td class="tab-head-td1" align="left"></td>		
													</tr>

					</table>
         <?php //print_r($Export_C1);
				} // Mysql Record End
				else{
					echo $No_Records;
				}//ifelse end
		//	}//if p is set
         ?>
	<?php
	//}//xls=1
	?>            </td>	
        </tr>