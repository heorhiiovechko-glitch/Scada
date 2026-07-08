          <!-- 
            Daily Generation Report
        -->
	<?php
# Time array
$Time_Arr = range(0,24);
foreach($Time_Arr as $Time_Val){
	$Str_Len = strlen($Time_Val);
	if($Str_Len == 1){
		$Time_Val = "0".$Time_Val;
	}
	$Time_24_Array["k".$Time_Val] = '';
}

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
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>
			
			<?php }?>
			</td>
		</tr>
<?php
	}
?>					
	
        <tr>
            <td width="750px">
<table width="900px" border="<?=$XLS == 1?'1':'0'?>" align="left" cellpadding="1" cellspacing="1" class="innertab1_">	
<?php
	if ($XLS == 1){//xls=1
	?>
	<tr>
							<td class="tab-head-td" colspan="12"  align="center"><b><? print_r($All_Firstname[1]) ?>   <?print_r($All_Lastname[1])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="12"  align="left"><b>Site:</b><?= implode(",",array_unique($Site_Location)) ?></td>
				
<?php 
		}
			if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr"  colspan="29" align="left">&nbsp;&nbsp;<b>Daily Generation Detail Report-ERP</b></td>
					</tr>
					<?php 
					}
					?>
	

 
	<?php
          //  if(isset($_REQUEST['p']) && $_REQUEST['p'] == 27){//if p is set

		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);


		if($Cook_Variable[2] ==3 || $Cook_Variable[2] ==2)

			$Device_Query="select Device_Name,Format_Type,Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where Parent_ID=" .$Cook_Variable[6] ."  order by Device_Order";
		elseif($Cook_Variable[2] ==4)
			$Device_Query="select Device_Name,Format_Type,Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where Account_ID='".$Account_ID."'  order by Device_Order";
		//echo $Device_Query;
if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }

            if($Device_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				$DGR_IMEI[$Fetch_Result['IMEI']]=$Fetch_Result['IMEI'];
				$Device_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
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
			//print_r($F1_IMEI);print_r($F2_IMEI);print_r($F3_IMEI);print_r($F4_IMEI);print_r($F5_IMEI);print_r($F6_IMEI);
					
//print_r($Format_Type);
//print_r($IMEI_DGR);
				//$Format_Type = array_unique($Format_Type);
	
				if($Device_Query_Result->num_rows >= 1) {//record count if
		?>
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Peak Hour Production</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Total LCS</b></td>
                        <td class="tab-head-td" align="center" width="16px;"><b>Run Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>BD Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Reason</b></td>                                                                                                       
                        <td class="tab-head-td" align="center" width="16px;"><b>Maintenanace Hrs</b></td> 
			<td class="tab-head-td" align="center" width="16px;"><b>Reason</b></td>                                                                                                       
			<td class="tab-head-td" align="center" width="16px;"><b>GD Hrs</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Reason</b></td>                                                                                                                                          
                        <td class="tab-head-td" align="center" width="16px;"><b>Lull Hrs</b></td>   
                        
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
//echo count($DGR_IMEI);
//echo $diff;
  
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){
foreach($DGR_IMEI as $IMEI_Val){

							
							//echo $DATE_Val;
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time[$IMEI_Val]=="00:00:00" || $Closing_Time[$IMEI_Val]=="0" ){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							}
							elseif($Closing_Time[$IMEI_Val]>="10:00:00" || $Closing_Time[$IMEI_Val]=="10"){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
							//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}
							else{
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							}
							if($Format[$IMEI_Val]=='1') {
							
$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,Pat_Gen2 as G1_Min,Run_Hours as Run_Min,Gen1_Hours as G1H_Min,Line_Ok as Line_Min from $Cook_Variable[7].device_data where IMEI in (".implode(",",$F1_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and ID_Number!=''  group by IMEI order by Record_Index asc";
//echo $Gen_Mysql_Query_Min;
						if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {
								$G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								$Line_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Line_Min'];
								$Run_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Run_Min'];
								$Gen1H_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1H_Min'];
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen2 as G1_Max,t1.Run_Hours as Run_Max,t1.Line_Ok as Line_Max,t1.Gen1_Hours as G1H_Max from $Cook_Variable[7].device_data t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$F1_IMEI).") and ID_Number!=''  group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
//echo $Gen_Mysql_Query_Max;								
if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {
								$G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								$Run_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Run_Max'];
								$Gen1H_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1H_Max'];
								$Line_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Line_Max'];
								 }
								}

$Total_Gen[$IMEI_Val][$DATE_Val]=$G1_Max[$IMEI_Val][$DATE_Val]-$G1_Min[$IMEI_Val][$DATE_Val];
		
		$Run[$IMEI_Val][$DATE_Val]=$Run_Max[$IMEI_Val][$DATE_Val]-$Run_Min[$IMEI_Val][$DATE_Val];

		$Gen1[$IMEI_Val][$DATE_Val]=$Gen1H_Max[$IMEI_Val][$DATE_Val]-$Gen1H_Min[$IMEI_Val][$DATE_Val];
		$Gen1[$IMEI_Val][$DATE_Val]=$Gen1[$IMEI_Val][$DATE_Val]>'24' && $Gen1[$IMEI_Val][$DATE_Val]<'50' ?'24':$Gen1[$IMEI_Val][$DATE_Val];
		$Lull_Hours[$IMEI_Val][$DATE_Val]=$Run[$IMEI_Val][$DATE_Val]-$Gen1[$IMEI_Val][$DATE_Val];
		if($Lull_Hours[$IMEI_Val][$DATE_Val]==(-1))
		$Lull_Hours[$IMEI_Val][$DATE_Val]=0;
		$Run_Hours[$IMEI_Val][$DATE_Val]=$Gen1[$IMEI_Val][$DATE_Val];
		//print_r($Run_Hours['868324025110367']);
		$Run_Hours[$IMEI_Val][$DATE_Val]=($Run_Hours[$IMEI_Val][$DATE_Val] >=0 && $Run_Hours[$IMEI_Val][$DATE_Val] <=24)?$Run_Hours[$IMEI_Val][$DATE_Val] : '0';
		$GD_Hours[$IMEI_Val][$DATE_Val] = 24-($Line_Max[$IMEI_Val][$DATE_Val]-$Line_Min[$IMEI_Val][$DATE_Val]);
		$GD_Hours[$IMEI_Val][$DATE_Val] = $GD_Hours[$IMEI_Val][$DATE_Val] >='0'?$GD_Hours[$IMEI_Val][$DATE_Val] : '0';
		$GD_Hours[$IMEI_Val][$DATE_Val]=($GD_Hours[$IMEI_Val][$DATE_Val] >=0 && $GD_Hours[$IMEI_Val][$DATE_Val] <=24)?$GD_Hours[$IMEI_Val][$DATE_Val] : '0';
		$Lull_Hours[$IMEI_Val][$DATE_Val]=($Lull_Hours[$IMEI_Val][$DATE_Val] >=0 && $Lull_Hours[$IMEI_Val][$DATE_Val] <=24)?$Lull_Hours[$IMEI_Val][$DATE_Val] : '0';
		$BD_Hours[$IMEI_Val][$DATE_Val]=24-($GD_Hours[$IMEI_Val][$DATE_Val]+$Lull_Hours[$IMEI_Val][$DATE_Val]+$Run_Hours[$IMEI_Val][$DATE_Val]);
		$BD_Hours[$IMEI_Val][$DATE_Val]=($BD_Hours[$IMEI_Val][$DATE_Val] >=0 && $BD_Hours[$IMEI_Val][$DATE_Val] <=24)?$BD_Hours[$IMEI_Val][$DATE_Val] : '0';
		$GA_Percent[$IMEI_Val][$DATE_Val]=((24 - $GD_Hours[$IMEI_Val][$DATE_Val]) / 24) * 100 ;
		
							}//endif isset
							//if(isset($F2_IMEI)){
						if($Format[$IMEI_Val]=='2') {
							
$Gen_Mysql_Query="select IMEI,Date_S,  max(PAT_GEN2)-min(PAT_GEN2) as Peak_Gen2,max(PAT_GEN1)-min(PAT_GEN1) as Peak_Gen1 from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$F2_IMEI).")  and (Date_S= '".$Date_Stamp."') and (Time_S between '18:00:00' and '21:59:00') and ID_Number!=''  group by IMEI ";//echo $Gen_Mysql_Query;
								if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Peak_Gen[$Fetch_Result['IMEI']][$DATE_Val]=($Fetch_Result['Peak_Gen1']+$Fetch_Result['Peak_Gen2']);


	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI in (".implode(",",$F2_IMEI).")  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) group by IMEI,Error_Type";
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
}
	# For GD Hours
else if($Error_Type[$POC_Fetch_Result['IMEI']][$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$POC_Fetch_Result['IMEI']][$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
}

	}//ENDWHILE							
					

									}//end while
								}

$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,PAT_Gen2 as G2_Min,Pat_Gen1 as G1_Min,Gen1_Hours as G1H_Min,Gen2_Hours as G2H_Min from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$F2_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and ID_Number!=''  group by IMEI order by Record_Index asc";
//echo $Gen_Mysql_Query_Min;
								if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {
								$G2_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G2_Min'];

								$G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								//$Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];
								$Gen2H_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G2H_Min'];
								$Gen1H_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1H_Min'];
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen2 as G2_Max,t1.Pat_Gen1 as G1_Max,t1.Gen1_Hours as G1H_Max,t1.Gen2_Hours as G2H_Max from $Cook_Variable[7].device_data_f2 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f2 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$F2_IMEI).") and ID_Number!=''  group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
	//echo $Gen_Mysql_Query_Max;
	if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {
								$G2_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G2_Max'];
								//$Import_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Import_Max'];
								$G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								$Gen2H_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G2H_Max'];
								$Gen1H_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1H_Max'];
								
								 }
								}

$Total_Gen[$IMEI_Val][$DATE_Val]=($G1_Max[$IMEI_Val][$DATE_Val]-$G1_Min[$IMEI_Val][$DATE_Val])+($G2_Max[$IMEI_Val][$DATE_Val]-$G2_Min[$IMEI_Val][$DATE_Val]);
		
		$Run_Hours[$IMEI_Val][$DATE_Val]=($Gen1H_Max[$IMEI_Val][$DATE_Val]-$Gen1H_Min[$IMEI_Val][$DATE_Val])+($Gen2H_Max[$IMEI_Val][$DATE_Val]-$Gen2H_Min[$IMEI_Val][$DATE_Val]);
		$Run_Hours[$IMEI_Val][$DATE_Val]=$Run_Hours[$IMEI_Val][$DATE_Val]>'24' && $Run_Hours[$IMEI_Val][$DATE_Val]<'50'?'24':$Run_Hours[$IMEI_Val][$DATE_Val];
		$Run_Hours[$IMEI_Val][$DATE_Val]=($Run_Hours[$IMEI_Val][$DATE_Val] >=0 && $Run_Hours[$IMEI_Val][$DATE_Val] <=24)?$Run_Hours[$IMEI_Val][$DATE_Val] : '0';
		
		$Lull_Hours[$IMEI_Val][$DATE_Val]= (24) - (($Run_Hours[$IMEI_Val][$DATE_Val]) +$BD_Hours[$IMEI_Val][$DATE_Val] + $GD_Hours[$IMEI_Val][$DATE_Val]);
		if($Lull_Hours[$IMEI_Val][$DATE_Val]==(-1))
		$Lull_Hours[$IMEI_Val][$DATE_Val]=0;
	$Lull_Hours[$IMEI_Val][$DATE_Val]=($Lull_Hours[$IMEI_Val][$DATE_Val] >=0 && $Lull_Hours[$IMEI_Val][$DATE_Val] <=24)?$Lull_Hours[$IMEI_Val][$DATE_Val] : '0';
		
							}//endif isset
							//if(isset($F3_IMEI)){
						
					if($Format[$IMEI_Val]=='6') {
								
$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,Pat_Gen1 as G1_Min,Run_Hours as Run_Min,Gen1_Hours as G1H_Min,Line_Ok as Line_Min from $Cook_Variable[7].device_data_f6 where IMEI in (".implode(",",$F6_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and ID_Number!=''  group by IMEI order by Record_Index asc";
		//echo $Gen_Mysql_Query_Min;
		if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {

								$G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								$Line_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Line_Min'];
								$Run_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Run_Min'];
								$Gen1H_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1H_Min'];
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Pat_Gen1 as G1_Max,t1.Run_Hours as Run_Max,t1.Gen1_Hours as G1H_Max,t1.Line_Ok as Line_Max from $Cook_Variable[7].device_data_f6 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f6 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$F6_IMEI).") and ID_Number!=''  group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
		//echo $Gen_Mysql_Query_Max;
		if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {

								$G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								$Run_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Run_Max'];
								$Gen1H_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1H_Max'];
								$Line_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Line_Max'];
								 }
								}
$Total_Gen[$IMEI_Val][$DATE_Val]=$G1_Max[$IMEI_Val][$DATE_Val]-$G1_Min[$IMEI_Val][$DATE_Val];
		
		$Run[$IMEI_Val][$DATE_Val]=$Run_Max[$IMEI_Val][$DATE_Val]-$Run_Min[$IMEI_Val][$DATE_Val];

		$Gen1[$IMEI_Val][$DATE_Val]=$Gen1H_Max[$IMEI_Val][$DATE_Val]-$Gen1H_Min[$IMEI_Val][$DATE_Val];
		$Gen1[$IMEI_Val][$DATE_Val]=$Gen1[$IMEI_Val][$DATE_Val]>'24' && $Gen1[$IMEI_Val][$DATE_Val]<'50' ?'24':$Gen1[$IMEI_Val][$DATE_Val];
		$Lull_Hours[$IMEI_Val][$DATE_Val]=$Run[$IMEI_Val][$DATE_Val]-$Gen1[$IMEI_Val][$DATE_Val];
		if($Lull_Hours[$IMEI_Val][$DATE_Val]==(-1))
		$Lull_Hours[$IMEI_Val][$DATE_Val]=0;
		$Lull_Hours[$IMEI_Val][$DATE_Val]=($Lull_Hours[$IMEI_Val][$DATE_Val] >=0 && $Lull_Hours[$IMEI_Val][$DATE_Val] <=24)?$Lull_Hours[$IMEI_Val][$DATE_Val] : '0';
		$Run_Hours[$IMEI_Val][$DATE_Val]=$Gen1[$IMEI_Val][$DATE_Val];
		$Run_Hours[$IMEI_Val][$DATE_Val]=($Run_Hours[$IMEI_Val][$DATE_Val] >=0 && $Run_Hours[$IMEI_Val][$DATE_Val] <=24)?$Run_Hours[$IMEI_Val][$DATE_Val] : '0';
		
		$GD_Hours[$IMEI_Val][$DATE_Val] = 24-($Line_Max[$IMEI_Val][$DATE_Val]-$Line_Min[$IMEI_Val][$DATE_Val]);
		$GD_Hours[$IMEI_Val][$DATE_Val] = $GD_Hours[$IMEI_Val][$DATE_Val] >='0'?$GD_Hours[$IMEI_Val][$DATE_Val] : '0';
		$GD_Hours[$IMEI_Val][$DATE_Val]=($GD_Hours[$IMEI_Val][$DATE_Val] >=0 && $GD_Hours[$IMEI_Val][$DATE_Val] <=24)?$GD_Hours[$IMEI_Val][$DATE_Val] : '0';
		$GA_Percent[$IMEI_Val][$DATE_Val]=((24 - $GD_Hours[$IMEI_Val][$DATE_Val]) / 24) * 100 ;
		$BD_Hours[$IMEI_Val][$DATE_Val]=24-($GD_Hours[$IMEI_Val][$DATE_Val]+$Lull_Hours[$IMEI_Val][$DATE_Val]+$Run_Hours[$IMEI_Val][$DATE_Val]);



							}//endif isset
							
						//if(isset($F10_IMEI)){
					if($Format[$IMEI_Val]=='10') {
								
$Gen_Mysql_Query_Min="SELECT IMEI,Date_S,Production_Total as G1_Min,Gen1_Hours as G1H_Min,Gen2_Hours as G2H_Min,Run_Hours as Run_Min from $Cook_Variable[7].device_data_f10 where IMEI in (".implode(",",$F10_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and ID_Number!=''  group by IMEI order by Record_Index asc";
		//echo $Gen_Mysql_Query_Min;
								if (!$Gen_Mysql_Query_Result_Min = $db->query($Gen_Mysql_Query_Min))
            {
                die($db->error);
            }
            if($Gen_Mysql_Query_Result_Min->num_rows >= 1)
            {
                while($Fetch_Result_Min = $Gen_Mysql_Query_Result_Min->fetch_array()) {
								$Run_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Run_Min'];
								$G1_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1_Min'];
								//$Import_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['Import_Min'];
								$Gen2H_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G2H_Min'];
								$Gen1H_Min[$Fetch_Result_Min['IMEI']][$DATE_Val]=$Fetch_Result_Min['G1H_Min'];
								 }
								}
	$Gen_Mysql_Query_Max="SELECT t1.IMEI,t1.Date_S,t1.Record_Index as RI,t1.Production_Total as G1_Max,t1.Run_Hours as Run_Max,t1.Gen1_Hours as G1H_Max,t1.Gen2_Hours as G2H_Max from $Cook_Variable[7].device_data_f10 t1 inner join (select td.IMEI,max(td.Record_Index) as RI from $Cook_Variable[7].device_data_f10 td where (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) and IMEI in (".implode(",",$F10_IMEI).") and ID_Number!=''  group by td.IMEI) tx on tx.IMEI=t1.IMEI and tx.RI=t1.Record_Index";
//echo $Gen_Mysql_Query_Max;
								if (!$Gen_Mysql_Query_Result_Max = $db->query($Gen_Mysql_Query_Max))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result_Max->num_rows >= 1)
            {
                while($Fetch_Result_Max = $Gen_Mysql_Query_Result_Max->fetch_array()) {
								$G1_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1_Max'];
								$Gen2H_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G2H_Max'];
								$Gen1H_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['G1H_Max'];
								$Run_Max[$Fetch_Result_Max['IMEI']][$DATE_Val]=$Fetch_Result_Max['Run_Max'];
								 }
								}

$Total_Gen[$IMEI_Val][$DATE_Val]=$G1_Max[$IMEI_Val][$DATE_Val]-$G1_Min[$IMEI_Val][$DATE_Val];
		
		$Run[$IMEI_Val][$DATE_Val]=$Run_Max[$IMEI_Val][$DATE_Val]-$Run_Min[$IMEI_Val][$DATE_Val];

		$Gen1[$IMEI_Val][$DATE_Val]=($Gen1H_Max[$IMEI_Val][$DATE_Val]-$Gen1H_Min[$IMEI_Val][$DATE_Val])+($Gen2H_Max[$IMEI_Val][$DATE_Val]-$Gen2H_Min[$IMEI_Val][$DATE_Val]);
		$Gen1[$IMEI_Val][$DATE_Val]=$Gen1[$IMEI_Val][$DATE_Val]>'24' && $Gen1[$IMEI_Val][$DATE_Val]<'50' ?'24':$Gen1[$IMEI_Val][$DATE_Val];
		$Lull_Hours[$IMEI_Val][$DATE_Val]=$Run[$IMEI_Val][$DATE_Val]-$Gen1[$IMEI_Val][$DATE_Val];
		if($Lull_Hours[$IMEI_Val][$DATE_Val]==(-1))
		$Lull_Hours[$IMEI_Val][$DATE_Val]=0;
		$Lull_Hours[$IMEI_Val][$DATE_Val]=($Lull_Hours[$IMEI_Val][$DATE_Val] >=0 && $Lull_Hours[$IMEI_Val][$DATE_Val] <=24)?$Lull_Hours[$IMEI_Val][$DATE_Val] : '0';
		$Run_Hours[$IMEI_Val][$DATE_Val]=$Gen1[$IMEI_Val][$DATE_Val];
		$Run_Hours[$IMEI_Val][$DATE_Val]=($Run_Hours[$IMEI_Val][$DATE_Val] >=0 && $Run_Hours[$IMEI_Val][$DATE_Val] <=24)?$Run_Hours[$IMEI_Val][$DATE_Val] : '0';
		$BD_Hours[$IMEI_Val][$DATE_Val] = Sec2Time($BD_Hours[$IMEI_Val][$DATE_Val],'m');
		$BD_Hours[$IMEI_Val][$DATE_Val] = $BD_Hours[$IMEI_Val][$DATE_Val] != '0.0'?$BD_Hours[$IMEI_Val][$DATE_Val] : 0;
		$GD_Hours[$IMEI_Val][$DATE_Val] =24*3600-(($BD_Hours[$IMEI_Val][$DATE_Val]*3600)+($Lull_Hours[$IMEI_Val][$DATE_Val]*3600)+$Run_Hours[$IMEI_Val][$DATE_Val]*3600);
		$GD_Hours[$IMEI_Val][$DATE_Val] = Sec2Time($GD_Hours[$IMEI_Val][$DATE_Val],'m');
		$GD_Hours[$IMEI_Val][$DATE_Val] = $GD_Hours[$IMEI_Val][$DATE_Val] != '0.0'?$GD_Hours[$IMEI_Val][$DATE_Val] : 0;
		$GD_Hours[$IMEI_Val][$DATE_Val]=($GD_Hours[$IMEI_Val][$DATE_Val] >=0 && $GD_Hours[$IMEI_Val][$DATE_Val] <=24)?$GD_Hours[$IMEI_Val][$DATE_Val] : '0';
		$GA_Percent[$IMEI_Val][$DATE_Val]=((24 - $GD_Hours[$IMEI_Val][$DATE_Val]) / 24) * 100 ;
							}//endif isset
						}//end foreach
}
						foreach($Date_Array as $DATE_Val){
							foreach($DGR_IMEI as $IMEI_Val){
														
						?>
                        <tr>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              
				<td class="tab-head-td1" align="left"><?=$Device_Name[$IMEI_Val]?></td>
				<td class="tab-head-td1" align="left">0</td>
<?php
if($Format[$IMEI_Val]=='2' || $Format[$IMEI_Val]=='8' || $Format[$IMEI_Val]=='10' || $Format[$IMEI_Val]=='4') {
?>
              			<td class="tab-head-td1" align="left"><?=($Total_Gen[$IMEI_Val][$DATE_Val] >=0 && $Total_Gen[$IMEI_Val][$DATE_Val] <=(6000*($diff+1)))?round($Total_Gen[$IMEI_Val][$DATE_Val],2): '0'?></td>                  
<?php
} elseif($Format[$IMEI_Val]=='3' || $Format[$IMEI_Val]=='7' || $Format[$IMEI_Val]=='4') {
?>
              			<td class="tab-head-td1" align="left"><?=($Total_Gen[$IMEI_Val][$DATE_Val] >=0 && $Total_Gen[$IMEI_Val][$DATE_Val] <=(18000*($diff+1)))?round($Total_Gen[$IMEI_Val][$DATE_Val],2): '0'?></td>                  
<?php
} else {  
?>
				<td class="tab-head-td1" align="left"><?=($Total_Gen[$IMEI_Val][$DATE_Val] >=0 && $Total_Gen[$IMEI_Val][$DATE_Val] <=(16000*($diff+1)))?round($Total_Gen[$IMEI_Val][$DATE_Val],2): '0'?></td>                  
<?php
}
			
?>
		<!--<td class="tab-head-td1" align="left"><?=($Peak_Gen[$IMEI_Val][$DATE_Val] >=0 && $Peak_Gen[$IMEI_Val][$DATE_Val] <=(16000*($diff+1)))?round($Peak_Gen[$IMEI_Val][$DATE_Val],2): '0'?></td>-->
				
              		
  			<td class="tab-head-td1" align="left"><?=($Run_Hours[$IMEI_Val][$DATE_Val] >=0 && $Run_Hours[$IMEI_Val][$DATE_Val] <=24)?$Run_Hours[$IMEI_Val][$DATE_Val] : '0'?></td>                               
                        <td class="tab-head-td1" align="left"><?=($BD_Hours[$IMEI_Val][$DATE_Val] >=0 && $BD_Hours[$IMEI_Val][$DATE_Val] <=24)?$BD_Hours[$IMEI_Val][$DATE_Val] : '0'?></td>                                     
			<td class="tab-head-td1" align="left"></td> 
			<td class="tab-head-td1" align="left">0</td>                                     
			<td class="tab-head-td1" align="left"></td>                                     
			                                    
			<td class="tab-head-td1" align="left"><?=($GD_Hours[$IMEI_Val][$DATE_Val] >=0 && $GD_Hours[$IMEI_Val][$DATE_Val] <=24)?$GD_Hours[$IMEI_Val][$DATE_Val] : '0'?></td> 
			<td class="tab-head-td1" align="left"></td>                                                             
			<td class="tab-head-td1" align="left"><?=($Lull_Hours[$IMEI_Val][$DATE_Val] >=0 && $Lull_Hours[$IMEI_Val][$DATE_Val] <=24)?$Lull_Hours[$IMEI_Val][$DATE_Val] : '0'?></td>   
                       				
                        </tr>
						<?php
								}

							}
						?>
							<td class="tab-head-td1" align="left"><b>Total</b></td>                 
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b>0</b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Total_Gen)!= '' && arraySumRecursive($Total_Gen)>=0 && arraySumRecursive($Total_Gen)<=15000*(($diff+1)*count($DGR_IMEI)))?round(arraySumRecursive($Total_Gen),2):'0' ?></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Run_Hours)<=$Daydiff && arraySumRecursive($Run_Hours)>=0) ? arraySumRecursive($Run_Hours):'0'?></b></td>
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($BD_Hours)<=$Daydiff && arraySumRecursive($BD_Hours)>=0) ? arraySumRecursive($BD_Hours):'0'?></b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
							<td class="tab-head-td1" align="left"><b>0</b></td>
							<td class="tab-head-td1" align="left"><b></b></td>
						
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($GD_Hours)<=$Daydiff && arraySumRecursive($GD_Hours)>=0) ? arraySumRecursive($GD_Hours):'0'?></b></td>							
							<td class="tab-head-td1" align="left"><b></b></td>							
							<td class="tab-head-td1" align="left"><b><?=(arraySumRecursive($Lull_Hours)<=$Daydiff && arraySumRecursive($Lull_Hours)>=0) ? arraySumRecursive($Lull_Hours):'0'?></b></td>
							
							
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