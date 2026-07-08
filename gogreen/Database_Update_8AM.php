<?php
ini_set('max_execution_time', 1800);
include("./Lib/config.php");
	include("./Lib/dbconn.php");
	
error_reporting(-1);



$Mysql_Query = "SELECT Distinct IMEI,Account_ID,Format_Type,Device_Name,hour(Closing_Time) as Closing_Time,db_name from va_master.device_register where db_name in ('va_mtk','va_shakthimurugan','va_newvision','va_spacetech','va_yuva','va_selvam','va_tngas','va_guru','va_jayakrishna','va_lucky','va_goyalgas') order by db_name,Format_Type";
//echo $Mysql_Query ;
	if (!$Mysql_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Result->num_rows >= 1)
            {
              while($row = $Mysql_Result->fetch_array()) {
				$POC_IMEI[$row['IMEI']]=$row['IMEI'];
				$Device_Name[$row["IMEI"]]=$row["Device_Name"];
				$DB_Name[$row["IMEI"]]=$row["db_name"];
				$Account_ID[$row["IMEI"]]=$row["Account_ID"];
				$Format_Type[$row["IMEI"]]=$row["Format_Type"];
				$Closing_Time[$row["IMEI"]]=$row["Closing_Time"];
			}
		}
		
//$IMEI_Str=implode(",",$POC_IMEI);

//print_r($IMEI_Str);
//print_r($DB_Name);
							
							

foreach($POC_IMEI as $IMEI_Val){
							$Date_Stamp=date("Y-m-d",strtotime("-1 days"));
							//$Date_Stamp="2016-07-19";
							if($Closing_Time[$IMEI_Val]=="00:00:00"){
							$Yester_Stamp=$Date_Stamp;
							
							}
							else{
							$Yester_Stamp=date("Y-m-d",strtotime("-1 days")+86400);
							//$Yester_Stamp="2016-07-20";
							
							}

if($Format_Type[$IMEI_Val]==1) {
$Data_Query =  "select Day(Date_S) as Day,Date_S,(SELECT PAT_Gen1 from $DB_Name[$IMEI_Val].device_data where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen1_Min,(SELECT PAT_Gen1 from $DB_Name[$IMEI_Val].device_data where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Max,(SELECT PAT_Gen2 from $DB_Name[$IMEI_Val].device_data where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $DB_Name[$IMEI_Val].device_data where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(PAT_GEN0))-min(ABS(PAT_GEN0))  as Gen0 ,max(Run_Hours)-min(Run_Hours) as Run,max(Line_Ok)-min(Line_Ok) as Line_Ok,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned))   as Gen1,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed from $DB_Name[$IMEI_Val].device_data where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end)";
if (!$Data_Query_Result = $db->query($Data_Query))
            {
                die($db->error);
            }
                while($row = $Data_Query_Result->fetch_array()) {	
 $Day=$row['Day'];
 $Date_S=$row['Date_S'];
 $Gen1_Min=$row['Gen1_Min'];
 $Gen1_Max=$row['Gen1_Max'];
 $Gen2_Min=$row['Gen2_Min'];
 $Gen2_Max=$row['Gen2_Max'];
 $Power=$row['Power'];
 $WindSpeed=$row['WindSpeed'];
 $Gen0=$row['Gen0'];
 $Run=$row['Run'];
 $Gen1=$row['Gen1'];
   $Line_Ok=$row['Line_Ok'];				
//print_r($row['Run']);	
}
if ($Data_Query_Result){
  $Insert_Query="Insert into $DB_Name[$IMEI_Val].daily_generation_data (IMEI,Windspeed,Power,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Run_Hours,Gen1_Hours,Line_Ok,Gen0,Date_S) values('$IMEI_Val','$WindSpeed','$Power','$Gen1_Min','$Gen1_Max','$Gen2_Min','$Gen2_Max','$Run','$Gen1','$Line_Ok','$Gen0','$Date_S')";
//echo $Insert_Query;
$Insert_Query_Result = $db->query($Insert_Query);
}
   												



}
elseif($Format_Type[$IMEI_Val]==2) {
$Data_Query =  "select Day(Date_S) as Day,Date_S,(SELECT PAT_Gen1 from $DB_Name[$IMEI_Val].device_data_f2 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen1_Min,(SELECT PAT_Gen1 from $DB_Name[$IMEI_Val].device_data_f2 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Max,(SELECT PAT_Gen2 from $DB_Name[$IMEI_Val].device_data_f2 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $DB_Name[$IMEI_Val].device_data_f2 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(Import_Kwh))-min(ABS(Import_Kwh))  as Gen0 ,(max(Gen1_Hours)-min(Gen1_Hours)) + (max(Gen2_Hours)-min(Gen2_Hours)) as Run,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed from $DB_Name[$IMEI_Val].device_data_f2 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end)";
if (!$Data_Query_Result = $db->query($Data_Query))
            {
                die($db->error);
            }
                while($row = $Data_Query_Result->fetch_array()) {	 $Day=$row['Day'];
 $Date_S=$row['Date_S'];
 $Gen1_Min=$row['Gen1_Min'];
 $Gen1_Max=$row['Gen1_Max'];
 $Gen2_Min=$row['Gen2_Min'];
 $Gen2_Max=$row['Gen2_Max'];
 $Power=$row['Power'];
 $WindSpeed=$row['WindSpeed'];
 $Gen0=$row['Gen0'];
 $Run=$row['Run'];
// $Gen1=$row['Gen1'];
 
 
 				
//print_r($row['Run']);	
}

	if ($Data_Query_Result){
  $Insert_Query="Insert into $DB_Name[$IMEI_Val].daily_generation_data (IMEI,Windspeed,Power,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Run_Hours,Gen1_Hours,Gen0,Date_S) values('$IMEI_Val','$WindSpeed','$Power','$Gen1_Min','$Gen1_Max','$Gen2_Min','$Gen2_Max','$Run','0','$Gen0','$Date_S')";
$Insert_Query_Result = $db->query($Insert_Query);
}			



}
elseif($Format_Type[$IMEI_Val]==3) {
$Data_Query =  "select Day(Date_S) as Day,Date_S,(SELECT Production_Total from $DB_Name[$IMEI_Val].device_data_f3 where IMEI='".$IMEI_Val."' and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen1_Min,(SELECT Production_Total from $DB_Name[$IMEI_Val].device_data_f3 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Max,(SELECT abs(Import_Kwh) from $DB_Name[$IMEI_Val].device_data_f3 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT abs(Import_Kwh) from $DB_Name[$IMEI_Val].device_data_f3 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, (max(Gen1_Hours)-min(Gen1_Hours)) + (max(Gen2_Hours)-min(Gen2_Hours)) as Run,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed from $DB_Name[$IMEI_Val].device_data_f3 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end)";
if (!$Data_Query_Result = $db->query($Data_Query))
            {
                die($db->error);
            }
                while($row = $Data_Query_Result->fetch_array()) {	
 $Day=$row['Day'];
 $Date_S=$row['Date_S'];
 $Gen1_Min=$row['Gen1_Min'];
 $Gen1_Max=$row['Gen1_Max'];
 $Gen2_Min=$row['Gen2_Min'];
 $Gen2_Max=$row['Gen2_Max'];
 $Power=$row['Power'];
 $WindSpeed=$row['WindSpeed'];
 //$Gen0=$row['Gen0'];
 $Run=$row['Run'];
//$Gen1=$row['Gen1'];
 
 
 				
//print_r($row['Run']);	
}
if ($Data_Query_Result){
  $Insert_Query="insert into $DB_Name[$IMEI_Val].daily_generation_data (IMEI,Windspeed,Power,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Run_Hours,Gen1_Hours,Gen0,Date_S) values('$IMEI_Val','$WindSpeed','$Power','$Gen1_Min','$Gen1_Max','$Gen2_Min','$Gen2_Max','$Run','0','0','$Date_S')";
echo $Insert_Query;
$Insert_Query_Result = $db->query($Insert_Query);
}
    												



}
elseif($Format_Type[$IMEI_Val]==6) {
$Data_Query =  "select Day(Date_S) as Day,Date_S,(SELECT PAT_Gen1 from $DB_Name[$IMEI_Val].device_data_f6 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen1_Min,(SELECT PAT_Gen1 from $DB_Name[$IMEI_Val].device_data_f6 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Max,(SELECT PAT_Gen2 from $DB_Name[$IMEI_Val].device_data_f6 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen2_Min,(SELECT PAT_Gen2 from $DB_Name[$IMEI_Val].device_data_f6 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen2_Max, max(ABS(PAT_GEN0))-min(ABS(PAT_GEN0))  as Gen0 ,max(Run_Hours)-min(Run_Hours) as Run,max(Line_Ok)-min(Line_Ok) as Line_Ok,max(cast(Gen1_Hours  as unsigned))-min(cast(Gen1_Hours as unsigned))   as Gen1,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed from $DB_Name[$IMEI_Val].device_data_f6 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end)";
if (!$Data_Query_Result = $db->query($Data_Query))
            {
                die($db->error);
            }
                while($row = $Data_Query_Result->fetch_array()) {	
 $Day=$row['Day'];
 $Date_S=$row['Date_S'];
 $Gen1_Min=$row['Gen1_Min'];
 $Gen1_Max=$row['Gen1_Max'];
 $Gen2_Min=$row['Gen2_Min'];
 $Gen2_Max=$row['Gen2_Max'];
 $Power=$row['Power'];
 $WindSpeed=$row['WindSpeed'];
 $Gen0=$row['Gen0'];
 $Run=$row['Run'];
 $Gen1=$row['Gen1'];
 $Line_Ok=$row['Line_Ok'];
 
 				
//print_r($row['Run']);	
}
if ($Data_Query_Result){
  $Insert_Query="Insert into $DB_Name[$IMEI_Val].daily_generation_data (IMEI,Windspeed,Power,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Run_Hours,Gen1_Hours,Line_Ok,Gen0,Date_S) values('$IMEI_Val','$WindSpeed','$Power','$Gen1_Min','$Gen1_Max','$Gen2_Min','$Gen2_Max','$Run','$Gen1','$Line_Ok','$Gen0','$Date_S')";
$Insert_Query_Result = $db->query($Insert_Query);
}

    												



}
elseif($Format_Type[$IMEI_Val]==10) {
$Data_Query =  "select Day(Date_S) as Day,Date_S,(SELECT Production_Total from $DB_Name[$IMEI_Val].device_data_f10 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index Limit 1) as Gen1_Min,(SELECT Production_Total from $DB_Name[$IMEI_Val].device_data_f10 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end) ORDER BY Record_Index DESC LIMIT 1) as Gen1_Max,max(ABS(PAT_Gen0))-min(ABS(PAT_Gen0))  as Gen0 ,max(Run_Hours)-min(Run_Hours) as Run,(max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) as Gen1,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed from $DB_Name[$IMEI_Val].device_data_f10 where IMEI='".$IMEI_Val."'  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Val]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Val]' end)";
if (!$Data_Query_Result = $db->query($Data_Query))
            {
                die($db->error);
            }
                while($row = $Data_Query_Result->fetch_array()) {	
 $Day=$row['Day'];
 $Date_S=$row['Date_S'];
 $Gen1_Min=$row['Gen1_Min'];
 $Gen1_Max=$row['Gen1_Max'];
// $Gen1_Min=$row['Gen2_Min'];
 //$Gen2_Max=$row['Gen2_Max'];
 $Power=$row['Power'];
 $WindSpeed=$row['WindSpeed'];
 $Gen0=$row['Gen0'];
 $Run=$row['Run'];
 $Gen1=$row['Gen1'];
 
 
 				
//print_r($row['Run']);	
}

    	if ($Data_Query_Result){
  $Insert_Query="Insert into $DB_Name[$IMEI_Val].daily_generation_data (IMEI,Windspeed,Power,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Run_Hours,Gen1_Hours,Gen0,Date_S) values('$IMEI_Val','$WindSpeed','$Power','$Gen1_Min','$Gen1_Max','0','0','$Run','$Gen1','$Gen0','$Date_S')";
$Insert_Query_Result = $db->query($Insert_Query);
}
											



}



}
						

?>