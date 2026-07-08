       <!-- 
          Alarm Log
        -->

	<?php 
	if ($XLS == 0){
?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->
			<?php if($FType==1 || $FType==6){?>
				<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  }if($FType==2){?>
				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>

			<?php  }if($FType==3){?>
				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  }if($FType==4){?>
				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  } if($FType==7 || $FType==8){?>
				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php  }if($FType==10){?>
				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
			<?php }?>

			</td>
		</tr>
<?php
	}
?>					
	
        <tr>
            <td width="450px">
<table width="600px" border="<?=$XLS == 1?'1':'0'?>" align="left" cellpadding="1" cellspacing="1" class="innertab1_">	
	
 
	<?php
         

		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
		if($Cook_Variable[2] ==3 || $Cook_Variable[2] ==2)

			$Device_Query="select Device_Name,Format_Type, Connect_Feeder,Site_Location,State,IMEI,Closing_Time from device_register where Parent_ID=" .$Cook_Variable[6] ."  order by Device_Order";
		elseif($Cook_Variable[2] ==4)
			$Device_Query="select Device_Name,Format_Type, Connect_Feeder,Site_Location,State,IMEI,Closing_Time from device_register where Account_ID=" .$Account_ID ."  order by Device_Order";
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
				$Site_Location[$Fetch_Result['IMEI']] = $Fetch_Result['Site_Location'];
				$Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
				$Format_Type[$Fetch_Result['Format_Type']] = $Fetch_Result['IMEI'];
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
							$Format_Type = array_unique($Format_Type);
							//print_r($Closing_Time);

	
				
		?>
					
<?php



	if ($XLS == 1){//xls=1



	?>						
<tr>
							<td class="tab-head-td" colspan="10"  align="center"><b><? print_r($All_Firstname[1]) ?>   <?print_r($All_Lastname[1])?> - Stop Hours Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="10"  align="left"><b>Site:</b><?= implode(",",array_unique($Site_Location)) ?></td>
						
			<?php
}
?>
<?php



	if ($XLS == 0){



	?>
<tr style="border:0px"><td >&nbsp;</td></tr>
<?php
}
?>
 					
                    <tr height="50px">
			     
                             <td class="tab-head-td" width="10px" align="left"><b>Date</b></td>
                            <td class="tab-head-td" align="center" width="20px;"><b>WTG Name</b></td>
                             <td class="tab-head-td" width="20px" align="left"><b>Stop Hours</b></td>
                    	</tr>
					
        <?php
						
							
							
		
           // if(isset($_REQUEST['p'])){
$DGR_IMEI_Str=implode(",",$DGR_IMEI);
$MI = 1;

$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							
								foreach($Date_Array as $DATE_Val){

							
							//echo $DATE_Val;
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							if($Cook_Variable[3]==200 || $Cook_Variable[3]==100077 || $Cook_Variable[3]==100079){
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Closing_Time="06:00:00";							
							}
							elseif($Cook_Variable[3]==100063 || $Cook_Variable[3]==100125 || $Cook_Variable[3]==100142 || $Cook_Variable[3]==100145) {
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Closing_Time="07:00:00";							
							}
							elseif($Cook_Variable[3]==100070 || $Cook_Variable[3]==100120 ) {
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Closing_Time="08:00:00";							
							}
							elseif($Cook_Variable[3]==100054) {
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Closing_Time="19:00:00";							
							}
							else{
							$Yester_Stamp=$Date_Stamp;
							$Closing_Time="00:00:00";							
							}
							/*foreach($DGR_IMEI as $IMEI_Val){
							if($Closing_Time[$IMEI_Val]=="00:00:00" || $Closing_Time[$IMEI_Val]=="0" || $Closing_Time[$IMEI_Val]=="00:10:00" || $Closing_Time[$IMEI_Val]=="00:30:00" ){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							$Closing_Time="00:00:00";
							}
							elseif($Closing_Time[$IMEI_Val]>="10:00:00" || $Closing_Time[$IMEI_Val]=="10"){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Closing_Time="20:00:00";
							//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}
							else{
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							$Closing_Time="07:00:00";
							}
							}*/
							
							//echo $DATE_Val;

		if(isset($F2_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S,((max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours))) as G2_Hours from $Cook_Variable[7].device_data_f2 where IMEI in (".implode(",",$F2_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI ";
//echo $Gen_Mysql_Query;			
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {	
			$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);					
			
//echo $G2_Hours;
//echo $Date_S;
//print_r($GD_Hours);
}
}
}
		if(isset($F4_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S,((max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours))) as G2_Hours from $Cook_Variable[7].device_data_f4 where IMEI in (".implode(",",$F4_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI ";
//echo $Gen_Mysql_Query;			
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {			
				$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);					
			
//echo $G2_Hours;
//echo $Date_S;
//print_r($GD_Hours);
}
}
}

if(isset($F1_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S,(max(Run_Hours)-min(Run_Hours)) as G2_Hours from $Cook_Variable[7].device_data where IMEI in (".implode(",",$F1_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI ";
			//echo $Gen_Mysql_Query;
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
					$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);
}
}
}
if(isset($F3_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as G2_Hours from $Cook_Variable[7].device_data_f3 where IMEI in (".implode(",",$F3_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI ";
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
					$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);
}
}
}
if(isset($F6_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S, max(Run_Hours)-min(Run_Hours) as G2_Hours from $Cook_Variable[7].device_data_f6 where IMEI in (".implode(",",$F6_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI ";
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
					$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);
			
}
}
}
if(isset($F7_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S, Operate_Hours as G2_Hours from $Cook_Variable[7].device_data_f7 where IMEI in (".implode(",",$F7_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI order by Record_Index desc ";
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
					$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);
			
}
}
}
if(isset($F8_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S, Operate_Hours as G2_Hours from $Cook_Variable[7].device_data_f8 where IMEI in (".implode(",",$F8_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI order by Record_Index desc ";
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
					$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);
			
}
}
}

if(isset($F10_IMEI)){
			$Gen_Mysql_Query="select IMEI,Date_S,max(Run_Hours)-min(Run_Hours) as Run,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as G2_Hours from $Cook_Variable[7].device_data_f10 where IMEI in (".implode(",",$F10_IMEI).") and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by IMEI ";
			if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {              
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
					$Date_S[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Date_S'];
			$Run[$Fetch_Result['IMEI']][$DATE_Val]=$Fetch_Result['Run'];
			$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round($Fetch_Result['G2_Hours']);
			$Lull_Hours[$Fetch_Result['IMEI']][$DATE_Val]=$Run[$Fetch_Result['IMEI']][$DATE_Val]-$G2_Hours[$Fetch_Result['IMEI']][$DATE_Val];
								if($Lull_Hours[$Fetch_Result['IMEI']][$DATE_Val]==(-1))
								$Lull_Hours[$Fetch_Result['IMEI']][$DATE_Val]=0;
			
			//$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600))/3600);
			$GD_Hours[$Fetch_Result['IMEI']][$DATE_Val]=round((86400-(($G2_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600)+($Lull_Hours[$Fetch_Result['IMEI']][$DATE_Val]*3600)))/3600);
}
}
}
}
											
				

foreach($Date_Array as $DATE_Val){
							foreach($DGR_IMEI as $IMEI_Val){
?>
 <tr>
                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              
				<td class="tab-head-td1" align="left"><?=$Device_Name[$IMEI_Val]?></td>
              			<td class="tab-head-td1" align="left"><?=$GD_Hours[$IMEI_Val][$DATE_Val] > 23 || $GD_Hours[$IMEI_Val][$DATE_Val] < 0 ?'0':$GD_Hours[$IMEI_Val][$DATE_Val] ?></td>                 

					</tr>	
<?php
}

}
?>



</tr>

	


						
	
						
					</table>
<?php
//}
?>
     
                  
            </td>
        </tr>