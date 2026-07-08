<?php
header("Access-Control-Allow-Origin: *");
header('Content-type:application/json');
include_once './config.php';
$action= isset($_REQUEST['action'])? strtolower($_REQUEST['action']):'';
switch($action)
{
    
	case 'get_list':
	$arr_res=get_list();
	break;
	
	case 'login':
	$arr_res=login();
	break;
	
	
	case 'userdata_by_id':
	$arr_res=userdata_by_id();
	break;
	
	case 'change_password':
	$arr_res=change_password();
	break;
	
	case 'forget_password':
	$arr_res=forget_password();
	break;
	
	case 'get_detail':
	$arr_res=get_detail();
	break;

	default:   
	$arr_res=array('status'=>0,'msg'=>'No web service Found.');
}

$output = json_encode($arr_res);
print_r($output); exit();


function get_detail(){
	global $db;
	error_reporting(0);	
	
	if(!checkRequired(['Account_ID','Format_Type','IMEI','Pocket_Length','Device'])){
		$output['status']=0;
		$output['message'] = 'Check parameters';
		return $output;	
	}
	$Account_ID = $_REQUEST['Account_ID'];
	$FType = $Format_Type = $_REQUEST['Format_Type'];
	$IMEI = $_REQUEST['IMEI'];
	$Pocket_Length = $_REQUEST['Pocket_Length'];
	$Device = $_REQUEST['Device'];
	
	$Mysql_Query="select Firstname,Lastname,Username,Password,Account_ID,User_Type_ID,In_Time,Out_Time,Parent_ID,Db_Name from user_master where Account_ID= '$Account_ID'";
	
	$queryResult = $db->query($Mysql_Query);
	
	if (!$queryResult || $queryResult->num_rows <= 0) {
		$output['status']=0;
		$output['message'] = 'Invalid account id.';
		return $output;		
	}
	
	
	$userdata = $queryResult->fetch_object();
	
	
	$data = [];
	$data2 = [];

	$Username = $userdata->Username;
	$Firstname = $userdata->Firstname;
	$Lastname = $userdata->Lastname;
	$User_Type_ID = $userdata->User_Type_ID;
	$Parent_ID = $userdata->Parent_ID;
	$Database_Name = $userdata->Db_Name;
	$Pass = $userdata->Password;
	
	
	
	$IMEI_Decode = base64_decode($IMEI);
	
	
	$where  = " and 1 = 1";

						
	if($Format_Type == 1){
		
		$Channel_Url = "channel2.php";
		
		$where .= " and a.Account_ID = b.Account_ID";
		$Table_Name = "device_data";
		$Error_Table_Name = "error_data"; 
	}
	elseif($Format_Type == 2){										
		if($Device=='Selva Tex 250kw') {
			$Channel_Url = "channel3_selvatex.php";
			$Table_Name ="device_data_f2";									
			$Error_Table_Name = "error_data_f2";
			

		} elseif($Account_ID=='100215') {
			$Channel_Url = "channel3_ucal.php";
			$Table_Name ="device_data_f2";									
			$Error_Table_Name = "error_data_f2";
			
		} else {
			$Channel_Url = "channel3.php";
			$Table_Name ="device_data_f2";									
			$Error_Table_Name = "error_data_f2"; 
		}
	}
	elseif($Format_Type == 3){
		$Channel_Url = "channel4.php";
		$Table_Name = "device_data_f3";									
		$Error_Table_Name = "error_data_f3";
		$where .= " and a.Account_ID = b.Account_ID";
		
	}
	elseif($Format_Type == 4){
			
			
		if($Device=='Aspire') {
			$Channel_Url = "channel9_new.php";
			$Table_Name = "device_data_f4"; 
			$Error_Table_Name = "error_data_f4"; 
		} else {
			$Channel_Url = "channel5.php";
			$Table_Name = "device_data_f4"; 
			$Error_Table_Name = "error_data_f4"; 
		} 	
	}
	elseif($Format_Type == 6){
		if($Database_Name=='va_siva')
			$Channel_Url = "channel7_old.php";
			
		elseif($Database_Name=='va_dhanalakshmi')
			$Channel_Url = "channel7_kvarh.php";
			
		else
		$Channel_Url = "channel7.php";
		
		$Table_Name = "device_data_f6"; 
		$Error_Table_Name = "error_data_f6"; 
		
		
	}
	elseif($Format_Type == 7){
		if($Device=='ICE MAN') {
			$Channel_Url = "channel1_iceman.php";
			$Table_Name ="device_data_f7";									
			$Error_Table_Name = "error_data_f7";
			
		} elseif($Device=='Aalayam S826' || $Device=='Aalayam S824' || $Device=='Aalayam S792' || $Device=='Aalayam S965') {
			$Channel_Url = "channel8_aalayam.php";
			$Table_Name = "device_data_f7";  
			$Error_Table_Name = "error_data_f7";
		} else {
			$Channel_Url = "channel8.php";
			$Table_Name = "device_data_f7"; 
			$Error_Table_Name = "error_data_f7";
		}
	}
	elseif($Format_Type == 8){
		$Channel_Url = "channel8.php";
		$Table_Name = "device_data_f8"; 
		$Error_Table_Name = "error_data_f8"; 
	}
	elseif($Format_Type == 9){
		$Channel_Url = "channel9new.php";
		$Table_Name = "device_data_f9"; 
		$Error_Table_Name = "error_data_f9"; 
		  
	}elseif($Format_Type == 10){
		$Channel_Url = "channel10.php";
		$Table_Name = "device_data_f10"; 
		$Error_Table_Name = "error_data_f10"; 
	}
	//devicedata_table
	//echo $Table_Name;
	
	$PowerANDSpeendSql = "select Power,Windspeed from devicedata_table where IMEI = '".$IMEI_Decode."'";
	if (!$PowerANDSpeendResult = $db->query($PowerANDSpeendSql))
	{
		$output['status']=0;
		$output['message'] = $db->error;
		return $output;	
	}
	
	$data = [];
	
	if($PowerANDSpeendResult->num_rows > 0){
		$PowerANDSpeendResult = $PowerANDSpeendResult->fetch_object();
		$data['Power']=$PowerANDSpeendResult->Power;
		$data['Windspeed']=$PowerANDSpeendResult->Windspeed;
	}
	
		
	// Getting the customer information
	$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name,a.Closing_Time as Closing_Hour from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$IMEI_Decode."' $where";
	if (!$Fetch_Info_Result = $db->query($Fetch_Info))
	{
		$output['status']=0;
		$output['message'] = $db->error;
		return $output;	
	}
		
	if($Fetch_Info_Result->num_rows >= 1)
	{
		$x = 1;
		while($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {
		
			$All_HTSC_No[$x] = $Fetch_Details_Result['HTSC_No'];					
			$All_LOC_No[$x] = $Fetch_Details_Result['LOC_No'];					
			$All_WEG_No[$x] = $Fetch_Details_Result['WEG_No'];					
			$All_Firstname[$x] = $Fetch_Details_Result['Firstname'];
			$All_Devicename[$x] = $Fetch_Details_Result['Device_Name'];
			$Site_Location[$x] = $Fetch_Details_Result['Site_Location'];
			$SF_No[$x] = $Fetch_Details_Result['SF_No'];
			$DOC[$x] = isset($Fetch_Details_Result['DOC']) ? $Fetch_Details_Result['DOC'] : '';
			$Date_Of_Commission = $Fetch_Details_Result['Date_Of_Commission'];
			$Capacity[$x] = $Fetch_Details_Result['Capacity'];
			$Closing_Time[$x] = $Fetch_Details_Result['Closing_Hour'];
			$Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
			$x++;
		}				
	}
	
		
	if($Closing_Time[1]=='06:00:00' || $Closing_Time[1]=='06:30:00'){
		$GAD_Time=" and Hour(Time_S)>=6 ";
		$GD_Time=time()-21660;
	} elseif($Closing_Time[1]=='07:00:00' || $Closing_Time[1]=='07:30:00'){
		$GAD_Time=" and Hour(Time_S)>=7 ";
		$GD_Time=time()-25200;
	} elseif($Closing_Time[1]=='08:00:00' || $Closing_Time[1]=='08:30:00'){
		$GAD_Time=" and Hour(Time_S)>=8 ";
		$GD_Time=time()-28800;
	} elseif($Closing_Time[1]=='09:00:00'){
		$GAD_Time=" and Hour(Time_S)>=9 ";
		$GD_Time=time()-32400;
	} elseif($Closing_Time[1]=='01:00:00' || $Closing_Time[1]=='01:30:00'){
		$GAD_Time=" and Hour(Time_S)>=1 ";
		$GD_Time=time()-3600;
	} elseif($Closing_Time[1]=='02:00:00' || $Closing_Time[1]=='02:30:00'){
		$GAD_Time=" and Hour(Time_S)>=2 ";
		$GD_Time=time()-7200;
	} else {
		$GAD_Time="";
		$GD_Time=time();
		$Test_Time=date('H',$GD_Time);
	}

	if($Format_Type == 8) {
		$Mysql_Query_GAD = "select (select Gen1_Max from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate() limit 1) as GAD_Today,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) order by Record_Index desc limit 1) as GAD_Yesterday,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) order by Record_Index desc limit 1) as GAD_Thisweek,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) order by Record_Index desc limit 1) as GAD_Thismonth,(select Gen1_Max from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 AND YEAR( Date_S) = YEAR( curdate() ) order by Record_Index desc limit 1) as GAD_Previousweek";
	}
	else {
		$Mysql_Query_GAD="select (select (Gen1_Max-Gen1_Min) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select (Gen1_Max-Gen1_Min) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";
	}
	
	//echo $Mysql_Query_GAD;
	if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD))
	{
		$output['status']=0;
		$output['message'] = $db->error;
		return $output;	
	}
	$Mysql_Query_Result_GADCount = $Mysql_Query_Result_GAD->num_rows;
	if($Mysql_Query_Result_GADCount >= 1)
	{
		while($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {
			$GAD_Today = round($Fetch_Result_GAD['GAD_Today'],2);
		$GAD_Yesterday = round($Fetch_Result_GAD['GAD_Yesterday'],2);
		$GAD_Thisweek = round($Fetch_Result_GAD['GAD_Thisweek'],2);
		$GAD_Thismonth = round($Fetch_Result_GAD['GAD_Thismonth'],2);
		$GAD_Previousweek = round($Fetch_Result_GAD['GAD_Previousweek'],2);	

		}
	}
	
	if($Format_Type == 1) {
		$Mysql_Query="select Db_Name from device_register where Account_ID= '$Account_ID' and IMEI = '$IMEI_Decode';";
		$currsts_queryResult = $db->query($Mysql_Query);
		if (!$currsts_queryResult || $currsts_queryResult->num_rows <= 0) {
			$output['status']=0;
			$output['message'] = 'Invalid account id and imei.';
			return $output;		
		}
		$currsts_userdata = $currsts_queryResult->fetch_object();
		if($currsts_userdata->Db_Name == 'va_rethnagiri') {
			$Database_Name = $currsts_userdata->Db_Name;
		}
	}
	
	if($Format_Type == 7) {
		$ER_Mysql_Query = "select Status as Log,Date_S,Time_S ,Active_Total_Gen_Export,Active_Gen1_Export,Reactive_Total_Gen_Import
		from $Database_Name.$Table_Name where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
	} else {
		$ER_Mysql_Query = "select Status as Log,Date_S,Time_S 
		from $Database_Name.$Table_Name where IMEI='".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
	}
	if (!$ER_Mysql_Query_Result = $db->query($ER_Mysql_Query))
	{
		$output['status']=0;
		$output['message'] = $db->error;
		return $output;	
	}

	if($ER_Mysql_Query_Result->num_rows >= 1)
	{
		$ER_Fetch_Result = $ER_Mysql_Query_Result->fetch_array();
		$Log_Status = $ER_Fetch_Result['Log'];	
		$Date = $ER_Fetch_Result['Date_S'];
		$Time = $ER_Fetch_Result['Time_S'];		
		
		if($Format_Type == 7){
			$GAD_Today = round($ER_Fetch_Result['Active_Total_Gen_Export']*1000,2);
			$GAD_Yesterday = round($ER_Fetch_Result['Active_Gen1_Export'],2);
			$GAD_Thismonth = round($ER_Fetch_Result['Reactive_Total_Gen_Import'],2);
		}
	}
	
	
	$data['All_Devicename'] = $All_Devicename[1];
	
	
			
	$rowColors = Array('#e6f2ff','#e6f2ff'); 
	$i= 0;

	# Getting the data from DEVICE_DATA based on IMEI
	$Mysql_Query = "select * from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status!='' order by Record_Index desc limit 10";
	if (!$Mysql_Query_Result = $db->query($Mysql_Query))
	{
		$output['status']=0;
		$output['message'] = $db->error;
		return $output;	
	}
	
	$data11 = [];
	$Mysql_Record_Count=$Mysql_Query_Result->num_rows;
	if($Mysql_Record_Count >= 1)
	{
		while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	
			//echo '<pre>'; print_r($Fetch_Result);	echo '</pre>'; continue;
			$data1 = [];
			$data1['Project_Version'] = $Fetch_Result['Project_Version'];
			$data1['ID_Number'] = $Fetch_Result['ID_Number'];
			$data1['GRPM'] = $Fetch_Result['GRPM'];
			$data1['RRPM'] = $Fetch_Result['RRPM'];
			$WindSpeed = $data1['WindSpeed'] = $Fetch_Result['Windspeed'];
			$data1['Pitch'] = $Fetch_Result['Pitch'];
			$Status = $data1['Status'] = $Fetch_Result['Status'];
			$data1['Date_S'] = $Fetch_Result['Date_S'];
			$data1['Time_S'] = $Fetch_Result['Time_S'];
			$data1['Power'] = $Fetch_Result['Power'];
			$data1['Rphase_Volt'] = $Fetch_Result['RPhase_Volt'];
			$data1['Yphase_Volt'] = $Fetch_Result['YPhase_Volt'];
			$data1['Bphase_Volt'] = $Fetch_Result['BPhase_Volt'];
			$data1['Rphase_Current'] = $Fetch_Result['RPhase_Current'];
			$data1['Yphase_Current'] = $Fetch_Result['YPhase_Current'];
			$data1['Bphase_Current'] = $Fetch_Result['BPhase_Current'];
			$data1['Power_factor'] = $Fetch_Result['Power_Factor'];
			$Frequency = $data1['Frequency'] = $Fetch_Result['Frequency'];
			$data1['PAT_Gen0'] = $Fetch_Result['PAT_Gen0'];
			$data1['PAT_Gen1'] = $Fetch_Result['PAT_Gen1'];
			$data1['PAT_Total'] = $Fetch_Result['PAT_Gen2'];
			$data1['Ambient_Temp'] = $Fetch_Result['Ambient_Temp'];
			$data1['Nacelle_Temp'] = $Fetch_Result['Nacel_Temp'];
			$data1['Gear_Temp'] = $Fetch_Result['Gear_Temp'];
			$data1['Gen1_Temp'] = $Fetch_Result['Gen1_Temp'];
			$data1['PATP_Gen1'] = $Fetch_Result['Bearing_Temp'];
			$data1['PATP_Total'] = $Fetch_Result['Control_Temp'];				
			$data1['Total'] = $Fetch_Result['Total_Hours'];
			$data1['Line_Ok'] = $Fetch_Result['Line_Ok'];
			$data1['Turbine_Ok'] = $Fetch_Result['Turbine_Ok'];
			$data1['Run'] = $Fetch_Result['Run_Hours'];
			$data1['Gen1'] = $Fetch_Result['Gen1_Hours'];				
			$Date_F = $data1['Date_F'] = $Fetch_Result['Date_F'];
			$data1['Time_F'] = $Fetch_Result['Time_F'];
			$data1['Hydraulic_Temp'] = $Fetch_Result['Hydraulic_Temp'];
	
			# Removing # symbal
			if($Frequency<40){
				$Frequency = $data1['Frequency'] = 49.85;
			}
			
	
			$data1['Status'] = str_replace('#','',$Status);		
			$data1['lastRecd ']= str_replace('.','-',$Date_F);	
			$data1['WindSpeed'] = str_replace('m/s','',$WindSpeed);	
			$data1['background-color'] = $rowColors[$i++ % count($rowColors)];	

			if($Status=='Run' || $Status=='M/C Running' || $Status=='RUN' || $Status=='OperateG1' || $Status=='OperateG2' || $Status=='OPERATING   NORMAL OPERATION') {
				$data1['color'] = 'green';	
			}elseif($Status=='Grid Drop' || $Status=='GridDrop') {
				$data1['color'] = 'blue';
			} else {
				$data1['color'] = 'red';
			}

			//$MI++;
			
			array_push($data11,$data1);
		} 
	}
	
	
	$data['current_status'] = $Log_Status;
	if($Log_Status=='Run' || $Log_Status=='M/C Running' || $Log_Status=='RUN' || $Log_Status=='OperateG1' || $Log_Status=='OperateG2' || $Log_Status=='OPERATING   NORMAL OPERATION') {
	$data['current_status_color'] = 'green';
	}elseif($Log_Status=='Grid Drop' || $Log_Status=='GridDrop') {
		$data['current_status_color'] = 'blue';
	} else {
		$data['current_status_color'] = 'red';
		
	}
	
	
	if($Mysql_Query_Result_GADCount >= 1){
		$data['GAD_for_Today'] = ($GAD_Today > 15000 || $GAD_Today < 0) ? "Nil":$GAD_Today." Kwh";
		$data['GAD_for_Yesterday'] = ($GAD_Yesterday > 15000 || $GAD_Yesterday < 0) ? "Nil":$GAD_Yesterday.($Format_Type != 7 ? " Kwh" : " Mwh");
		$data['GAD_for_This_Week'] = ($GAD_Thisweek > 100000 || $GAD_Thisweek < 0) ? "Nil":$GAD_Thisweek." Kwh";
		$data['GAD_for_Previous_Week'] = ($GAD_Previousweek > 100000 || $GAD_Previousweek < 0) ? "Nil":$GAD_Previousweek." Kwh";
		$data['GAD_for_This_month'] = ($GAD_Thismonth > 300000 || $GAD_Thismonth < 0) ? "Nil":$GAD_Thismonth.($Format_Type != 7 ? " Kwh" : " Mwh");
	} else {
		$data['GAD_for_Today'] = null;
		$data['GAD_for_Yesterday'] = null;
		$data['GAD_for_This_Week'] = null;
		$data['GAD_for_Previous_Week'] = null;
		$data['GAD_for_This_month'] = null;
	}
	

	$output['status']= 1;
	$output['message']= 'Success';
	$output['data']= $data;
	$output['powerSpeed']= $data11;
	
	$output['production']= powerGraph(
		$IMEI,
		date('m-Y')
	);
	$fromDate = strtotime(date('Y-m-d'));
	$toDate = strtotime(date('Y-m-d'));
	$powerWindSpeed = getPowerWindSpeedGraph($IMEI , $fromDate , $toDate , $Database_Name);
	$output['powerWindSpeed'] = $powerWindSpeed;
	//$output['parameter'] = [$IMEI , $fromDate , $toDate , $Database_Name];
	return $output;
}
function getPowerWindSpeedGraph($c1 , $fromDate , $toDate , $Database_Name )
{
	global $db;
	global $PCWP_Chart_Arr_600;
	global $PCWP_Chart_Val_Arr_600;
	global $PCWP_Chart_Arr_500;
	global $PCWP_Chart_Val_Arr_500;
	global $PCWP_Chart_Arr_225;
	global $PCWP_Chart_Val_Arr_225;
	global $PCWP_Chart_Arr_250;
	global $PCWP_Chart_Val_Arr_250;
	$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);
	$From_D_Epoch = $fromDate;
	//echo $fromDate;
	$Extract_Date = date("Y-m-d",$From_D_Epoch);
	$Extract_Date1=date("d.m.Y",$From_D_Epoch);
	$Hours_Arr = range(0,23);
	$All_Hours_Standard_Arr = range(0,23);
	for($H = 0; $H <= 23; $H++){
		(strlen($H) == 1?$Ex = 0: $Ex = '');
		$OneHour_Start[$H] = $Extract_Date." ".$Ex."".$H.":00:00";
		$OneHour_End[$H] = $Extract_Date." ".$Ex."".$H.":59:00";
		$Time_S[$H] = strtotime($OneHour_Start[$H])+(60*60*5.5);
		$Time_E[$H] = strtotime($OneHour_End[$H])+(60*60*5.5);
		$Epoch_OneHour_Arr[$H] = array($Time_S[$H],$Time_E[$H]);//one hour time values as start and end in an array	 
	}
//print_r($Epoch_OneHour_Arr);
$firstvalind=0;
 $zerovalind=0;
$lastvalind=0;
$firstvalcount=0;
//if cookie is set

if(isset($c1)){	
		$To_D_Epoch = $toDate;
		$IMEI = base64_decode($c1);
		$PCWP_Chart_Arr1 = array();
		
		#	Getting IMEI 
		$Mysql_Query = "select * from device_register where IMEI = '$IMEI'";//echo $Mysql_Query;
	if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	
				$Power_Curve_Array[$Fetch_Result['IMEI']] =  $Fetch_Result['Power_Curve'];
				$Format_Type= $Fetch_Result['Format_Type'];
				$Device_Name=$Fetch_Result['Device_Name'];
			}//end while
		}//endif
		//echo $Mysql_Query;

		# Assign Power Curve Array Related to IMEI
		if(isset($IMEI)){
			$PCWP_Chart_Arr = array();
			if($Power_Curve_Array[$IMEI] == 600){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_600;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_600;
			}
			elseif($Power_Curve_Array[$IMEI] == 500){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_500;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_500;
			}
			elseif($Power_Curve_Array[$IMEI] == 225){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_225;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_225;
			}
			elseif($Power_Curve_Array[$IMEI] == 250){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_250;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_250;
			}
		}//end if


//Getting All Hours Calculation

if($Format_Type==1)

$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Max ,MIN(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Database_Name.device_data where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and PAT_GEN2 >0 group by HOUR(Time_S)";
elseif($Format_Type==4)
$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(PAT_GEN1,0)) as G1_Kwh_Max,MIN(GREATEST(PAT_GEN1,0)) as G1_Kwh_Min,MAX(GREATEST(PAT_GEN2,0)) as G2_Kwh_Max,MIN(GREATEST(PAT_GEN2,0)) as G2_Kwh_Min,round(AVG(GREATEST(POWER,0)),2)   as Power ,ROUND(AVG(WINDSPEED),2) as WindSpeed ,Date_S,Time_S  from $Database_Name.device_data_f4 where IMEI = '".$IMEI."' and Date_S='$Extract_Date'  and PAT_Gen1 between 0 and 10000000 and PAT_Gen2 between 0 and 10000000 group by HOUR(Time_S)";

elseif($Format_Type==2)
$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(PAT_GEN1,0)) as G1_Kwh_Max,MIN(GREATEST(PAT_GEN1,0)) as G1_Kwh_Min,MAX(GREATEST(PAT_GEN2,0)) as G2_Kwh_Max,MIN(GREATEST(PAT_GEN2,0)) as G2_Kwh_Min,round(AVG(GREATEST(POWER,0)),2)   as Power ,ROUND(AVG(WINDSPEED),2) as WindSpeed ,Date_S,Time_S  from $Database_Name.device_data_f2 where IMEI = '".$IMEI."' and Date_S='$Extract_Date'  and PAT_Gen1 between 0 and 10000000 and PAT_Gen2 between 0 and 10000000 group by HOUR(Time_S)";
elseif($Format_Type==6)
$Mysql_Query="select HOUR(TIME_S) as Hour ,MAX(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Max ,MIN(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Database_Name.device_data_f6 where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and PAT_GEN2 between 0 and 20000000  group by HOUR(Time_S)";
// elseif($Format_Type==7)
// $Mysql_Query="select HOUR(TIME_S) as Hour ,MAX(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Max ,MIN(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Database_Name.device_data_f7 where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and PAT_GEN2 between 0 and 20000000  group by HOUR(Time_S)";
elseif($Format_Type==10)
$Mysql_Query="select HOUR(TIME_S) as Hour ,MAX(GREATEST(Production_Total,0)) as GEN_Max ,MIN(GREATEST(Production_Total,0)) as GEN_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Database_Name.device_data_f10 where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and Production_Total between 0 and 20000000  group by HOUR(Time_S)";
elseif($Format_Type==3)
$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(Production_Total,0)) as GEN_Max ,MIN(GREATEST(Production_Total,0)) as GEN_Min ,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed, Date_S as Date_S,Time_S as Time_S  from $Database_Name.device_data_f3 where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and Production_Total between 0 and 20000000  group by HOUR(Time_S)";

		//echo $Mysql_Query;
		if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
				$MI = 1;
				$nu = 0;
              while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
				
				$Each_Hours_WindSpeedData_Arr_Avg[$nu]['x'] = $Fetch_Result['Hour'];
				$Each_Hours_WindSpeedData_Arr_Avg[$nu]['y'] = $Fetch_Result['WindSpeed'];
				$Each_Hours_WindSpeedData_Arr_Avg_old[$Fetch_Result['Hour']]=$Fetch_Result['WindSpeed'];
				
				$Each_Hours_PowerData_Arr_Avg[$Fetch_Result['Hour']]=$Fetch_Result['Power'];
				if($Format_Type==1 || $Format_Type==6){
				$Each_Hours_PAT_GEN2_Min_Arr[$Fetch_Result['Hour']]=$Fetch_Result['PAT_GEN2_Min'];
				
				$Each_Hours_PAT_GEN2_Max_Arr_copy[$Fetch_Result['Hour']]=$Each_Hours_PAT_GEN2_Max_Arr[$Fetch_Result['Hour']]=$Fetch_Result['PAT_GEN2_Max'];
				}elseif($Format_Type==2){
				$Each_Hours_G1_Kwh_Min_Arr[$Fetch_Result['Hour']]=$Fetch_Result['G1_Kwh_Min'];
				
				$Each_Hours_G2_Kwh_Min_Arr[$Fetch_Result['Hour']]=$Fetch_Result['G2_Kwh_Min'];
				
				$Each_Hours_G1_Kwh_Max_Arr_copy[$Fetch_Result['Hour']]=$Each_Hours_G1_Kwh_Max_Arr[$Fetch_Result['Hour']]=$Fetch_Result['G1_Kwh_Max'];

				$Each_Hours_G2_Kwh_Max_Arr_copy[$Fetch_Result['Hour']]=$Each_Hours_G2_Kwh_Max_Arr[$Fetch_Result['Hour']]=$Fetch_Result['G2_Kwh_Max'];
				}elseif($Format_Type==10 || $Format_Type==3){
				$Each_Hours_GEN_Min_Arr[$Fetch_Result['Hour']]=$Fetch_Result['GEN_Min'];
				
				$Each_Hours_GEN_Max_Arr_copy[$Fetch_Result['Hour']]=$Each_Hours_GEN_Max_Arr[$Fetch_Result['Hour']]=$Fetch_Result['GEN_Max'];

				}else{
				//echo "No Records";
				}

				//$DET = $Fetch_Result['Device_Epoch_Time'];

				//echo $DET;
				#	Seperating One Hours values for each
				//print_r($Hours_Arr);

				$nu++;
			}//end while
		}//end if
		//print_r($Each_Hours_PAT_GEN2_Max_Arr);
		//print_r($Each_Hours_G1_Kwh_Max_Arr);
		//print_r($Each_Hours_G1_Kwh_Max_Arr);
		$arrayiteratorcount=0;
		if($Format_Type==1 || $Format_Type==6){
			$PAT_GEN2_Min_Value=0;
			foreach($Each_Hours_PAT_GEN2_Max_Arr as $PAT_GEN2_Max_Key => $PAT_GEN2_Max_Value){
		
				if($arrayiteratorcount==0)
					$Each_Hours_Generation_Arr[$PAT_GEN2_Max_Key]=$PAT_GEN2_Max_Value-$Each_Hours_PAT_GEN2_Min_Arr[$PAT_GEN2_Max_Key];
				else
					$Each_Hours_Generation_Arr[$PAT_GEN2_Max_Key]=$PAT_GEN2_Max_Value-$PAT_GEN2_Min_Value;
				$PAT_GEN2_Min_Value=$PAT_GEN2_Max_Value;
				$arrayiteratorcount++;
if($Each_Hours_Generation_Arr[$PAT_GEN2_Max_Key]>2000){
$Each_Hours_Generation_Arr[$PAT_GEN2_Max_Key]=0;
}
			}//end foreach

		}elseif($Format_Type==10 || $Format_Type==3 ){
			$GEN_Min_Value=0;
			foreach($Each_Hours_GEN_Max_Arr as $GEN_Max_Key => $GEN_Max_Value){
		
				if($arrayiteratorcount==0)
					$Each_Hours_Generation_Arr[$GEN_Max_Key]=$GEN_Max_Value-$Each_Hours_GEN_Min_Arr[$GEN_Max_Key];
				else
					$Each_Hours_Generation_Arr[$GEN_Max_Key]=$GEN_Max_Value-$GEN_Min_Value;
				$GEN_Min_Value=$GEN_Max_Value;
				$arrayiteratorcount++;
if($Each_Hours_Generation_Arr[$GEN_Max_Key]>2000){
$Each_Hours_Generation_Arr[$GEN_Max_Key]=0;
}

			}//end foreach

		}elseif($Format_Type==2 || $Format_Type==4){
			
			$G1_Kwh_Min_Value=0;
			$G2_Kwh_Min_value=0;
			foreach($Each_Hours_G1_Kwh_Max_Arr as $G1_Kwh_Max_Key => $G1_Kwh_Max_Value){
		
				if($arrayiteratorcount==0)
					$Each_Hours_G1_Kwh_Arr[$G1_Kwh_Max_Key]=$G1_Kwh_Max_Value-$Each_Hours_G1_Kwh_Min_Arr[$G1_Kwh_Max_Key];
				else
					$Each_Hours_G1_Kwh_Arr[$G1_Kwh_Max_Key]=$G1_Kwh_Max_Value-$G1_Kwh_Min_Value;
				$G1_Kwh_Min_Value=$G1_Kwh_Max_Value;
				$arrayiteratorcount++;
			}//end foreach
			$arrayiteratorcount=0;
			foreach($Each_Hours_G2_Kwh_Max_Arr as $G2_Kwh_Max_Key => $G2_Kwh_Max_Value){
		
				if($arrayiteratorcount==0)
					$Each_Hours_G2_Kwh_Arr[$G2_Kwh_Max_Key]=$G2_Kwh_Max_Value-$Each_Hours_G2_Kwh_Min_Arr[$G2_Kwh_Max_Key];
				else
					$Each_Hours_G2_Kwh_Arr[$G2_Kwh_Max_Key]=$G2_Kwh_Max_Value-$G2_Kwh_Min_Value;
					$G2_Kwh_Min_Value=$G2_Kwh_Max_Value;
				$arrayiteratorcount++;
			}//endforeach		
			$Each_Hours_Generation_Arr = array();
			for($i=0;$i<count($Each_Hours_G1_Kwh_Arr);$i++) {
			 $Each_Hours_Generation_Arr[$i] = round($Each_Hours_G1_Kwh_Arr[$i]+$Each_Hours_G2_Kwh_Arr[$i]);
if($Each_Hours_Generation_Arr[$i]>2000){
$Each_Hours_Generation_Arr[$i]=0;
}

		}
		}
		else{
		//echo "no records";
		}
		//print_r($Each_Hours_G1_Kwh_Arr);echo "<br>";
		//print_r($Each_Hours_G2_Kwh_Arr);echo "<br>";
	
		//rint_r($Each_Hours_Generation_Arr);
		$MaxValKey=array_search(max($Each_Hours_Generation_Arr),$Each_Hours_Generation_Arr);

		if($Each_Hours_Generation_Arr[$MaxValKey]>2000 && $Each_Hours_Generation_Arr[$MaxValKey]<0 && $Format_Type==1)
				$Each_Hours_Generation_Arr[$MaxValKey]=	$Each_Hours_Generation_Arr[$MaxValKey+1];
		if($Each_Hours_Generation_Arr[$MaxValKey]>1000 && $Each_Hours_Generation_Arr[$MaxValKey]<0  && $Format_Type==2)
				$Each_Hours_Generation_Arr[$MaxValKey]=	$Each_Hours_Generation_Arr[$MaxValKey+1];	
	
//power values stored in $Each_Hours_PowerData_Arr[$HA_Val][].power values -ve values marked as zero . windspeed values in $Each_Hours_WindSpeedData_Arr[$HA_Val][]


		//print_r($Each_Hours_WindSpeedData_Arr);
		//echo $Mysql_Record_Count;	
		/*echo "<hr>";
		print_r($Each_Hours_PowerData_Arr);
		//echo "<hr>";exit;*/
		//print_r($All_Hours_Standard_Arr);
		$WindSpeed_Total = '';


//print_r($Each_Hours_WindSpeedData_Arr);


		
		//print_r($Each_Hours_WindSpeedData_Arr_Avg);
		//print_r($Each_Hours_PowerData_Arr_Avg);

		# WindSpeed and Power Combine
		$PCWP_Chart_Arr1 = array_combine($Each_Hours_WindSpeedData_Arr_Avg_old,$Each_Hours_PowerData_Arr_Avg);
		//print_r($PCWP_Chart_Arr);
		//echo "<hr>";
		//print_r($Each_Hours_WindSpeedData_Arr_Avg);
		//echo "<hr>";
		//print_r($Each_Hours_PowerData_Arr_Avg);
		//echo "<hr>";
		//print_r($PCWP_Chart_Arr1);
		//echo "<hr>";

		foreach($PCWP_Chart_Arr as $PWC_Key => $PWC_Val){
			$PCWP_Chart_Arr1_Filter[$PWC_Key] = array(0,0);
			foreach($PCWP_Chart_Arr1 as $PWC_Key1 => $PWC_Val1)	{
				if($PCWP_Chart_Val_Arr[$PWC_Key][0] <= $PWC_Key1 && $PCWP_Chart_Val_Arr[$PWC_Key][1] >= $PWC_Key1){
					$PCWP_Chart_Arr1_Filter_Both[$PWC_Key][] = array($PWC_Key1, $PWC_Val1);
					$PCWP_Chart_Arr1_Filter_Bef[$PWC_Key] = array($PWC_Key1, $PWC_Val1);
				}
			}	
		}
		
		#	For Delete the lowest value with in the .5 range
		foreach($PCWP_Chart_Arr1_Filter_Both as $PCAFB_Key => $PCAFB_Val){
			for($C = 0;$C <= count($PCWP_Chart_Arr1_Filter_Both[$PCAFB_Key]); $C++){
				if(isset($PCWP_Chart_Arr1_Filter_Both[$PCAFB_Key][$C][1]) && isset($PCWP_Chart_Arr1_Filter_Both[$PCAFB_Key][$C+1][1])){
					$First_Val = $PCWP_Chart_Arr1_Filter_Both[$PCAFB_Key][$C][1];
					$Sec_Val = $PCWP_Chart_Arr1_Filter_Both[$PCAFB_Key][$C+1][1];
					$First_Key = $PCWP_Chart_Arr1_Filter_Both[$PCAFB_Key][$C][0];
					$Sec_Key = $PCWP_Chart_Arr1_Filter_Both[$PCAFB_Key][$C+1][0];
					$First_Val < $Sec_Val?$Final_Key = $First_Key : $Final_Key = $Sec_Key;
					unset($PCWP_Chart_Arr1[$Final_Key]);
				}
			}
		}
	
	
		//print_r($PCWP_Chart_Arr1);
		//echo "<hr>";
		
		foreach($PCWP_Chart_Arr as $PWC_Key => $PWC_Val){
			
			$PCWP_Chart_Arr1_Filter[$PWC_Key] = array(0,0);
			//echo $PWC_Key1."----".round($PWC_Key1)."<br />";
			$PWC_Key1 = round($PWC_Key1);
			foreach($PCWP_Chart_Arr1 as $PWC_Key1 => $PWC_Val1)	{
				if($PCWP_Chart_Val_Arr[$PWC_Key][0] <= $PWC_Key1 && $PCWP_Chart_Val_Arr[$PWC_Key][1] >= $PWC_Key1){
					//echo $PCWP_Chart_Val_Arr[$PWC_Key][0]." <= ".$PWC_Key1." && ".$PCWP_Chart_Val_Arr[$PWC_Key][1]." >= ".$PWC_Key1."<br />";
					//$PCWP_Chart_Arr_Filter[$PWC_Key] = $PWC_Val;
					//echo $PWC_Key1." ".$PWC_Val1."<br />";
					$PCWP_Chart_Arr1_Filter[$PWC_Key] = array($PWC_Key1, $PWC_Val1);
				}
			}	
		}
		$PCWP_Chart_Arr_Filter = $PCWP_Chart_Arr;



}//end if cookie

//print_r($Each_Hours_G2Kwh_Arr_Diff);echo "<br>";
//print_r($Each_Hours_G1Kwh_Arr_Diff);echo "<br>";

//print_r($Final_Gen_Arr);
//echo "<hr>";

//print_r($PCWP_Chart_Arr1_Filter);

$newValue = [];
foreach($PCWP_Chart_Arr1_Filter as $newkey => $Nvalue){
	$newkey = number_format($newkey,1);
	$newValue[$newkey] = $Nvalue;
}

$Each_Hours_Generation_Arr_Sum=array_sum($Each_Hours_Generation_Arr);
$data['windspeed'] = $Each_Hours_WindSpeedData_Arr_Avg;
$data['generation'] = $Each_Hours_Generation_Arr;
$data['powerkw'] = (object)$PCWP_Chart_Arr1_Filter;
		//echo $Each_Hours_Generation_Arr_Sum."<hr>";
//print_r($Each_Hours_WindSpeedData_Arr_Avg);
	return $data;
}
function powerGraph(
	$IMEI,
	$MonthYear
){
	global $db;
	global $PCWP_Chart_Arr_600;
	global $PCWP_Chart_Val_Arr_600;
	global $PCWP_Chart_Arr_500;
	global $PCWP_Chart_Val_Arr_500;
	global $PCWP_Chart_Arr_225;
	global $PCWP_Chart_Val_Arr_225;
	global $PCWP_Chart_Arr_250;
	global $PCWP_Chart_Val_Arr_250;
	$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	
	

	$firstvalind=0;
	$zerovalind=0;
	$lastvalind=0;
	$firstvalcount=0;
	//echo $IMEI;
	$IMEI = base64_decode($IMEI);

	if($IMEI){
		
		$MonthYearArr=explode("-",$MonthYear);
    	$Month = $MonthYearArr[0];
	  	$Year = $MonthYearArr[1];
		
    	$Total_Days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);	

		$PCWP_Chart_Arr1 = array();
		
		#	Getting IMEI 
		$Mysql_Query = "select IMEI,Power_Curve,Format_Type,Device_Name from device_register where IMEI = '$IMEI'";//echo $Mysql_Query;
		if (!$Mysql_Query_Result = $db->query($Mysql_Query))
		{ 
			 return [];
		}
	

		if($Mysql_Query_Result->num_rows >= 1)
		{
			while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
				$Power_Curve_Array[$Fetch_Result['IMEI']] =  $Fetch_Result['Power_Curve'];
				$Format_Type= $Fetch_Result['Format_Type'];
				$Device_Name=$Fetch_Result['Device_Name'];
			}
		}
		
		
		# Assign Power Curve Array Related to IMEI
		if(isset($IMEI)){
			$PCWP_Chart_Arr = array();
			if($Power_Curve_Array[$IMEI] == 600){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_600;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_600;
			}
			elseif($Power_Curve_Array[$IMEI] == 500){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_500;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_500;
			}
			elseif($Power_Curve_Array[$IMEI] == 225){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_225;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_225;
			}
			elseif($Power_Curve_Array[$IMEI] == 250){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_250;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_250;
			}
		}
		




		if($Format_Type==1 || $Format_Type==6)	{	
			$Mysql_Query="select Day(Date_S) as Day,Gen1_Max as PAT_GEN2_Max  ,Gen1_Min as PAT_GEN2_Min ,power, windspeed as WindSpeed, Date_S  from daily_data where IMEI = '".$IMEI."' and month(Date_S)=".$MonthYearArr[0]." and Year(Date_S)=".$MonthYearArr[1]." group by day(Date_S)";
		}elseif($Format_Type==2 || $Format_Type==4){
			$Mysql_Query="select Day(Date_S) as Day,Gen1_Max as G1_Kwh_Max,Gen1_Min as G1_Kwh_Min,Gen2_Max as G2_Kwh_Max  ,Gen2_Min as G2_Kwh_Min ,Power, Windspeed as WindSpeed, Date_S  from daily_data where IMEI = '".$IMEI."' and month(Date_S)=".$MonthYearArr[0]." and Year(Date_S)=".$MonthYearArr[1]." group by day(Date_S)";
		}elseif($Format_Type==7 || $Format_Type==8){
			$Mysql_Query="select Day(Date_S) as Day,Gen1_Max as PAT_GEN1_Max,Gen2_Min as PAT_GEN2_Min ,Power, Windspeed as WindSpeed, Date_S  from daily_data 	where IMEI = '".$IMEI."' and month(Date_S)=".$MonthYearArr[0]." and Year(Date_S)=".$MonthYearArr[1]." group by day(Date_S)";
		}elseif($Format_Type==10){
			$Mysql_Query="select Day(Date_S) as Day,Gen1_Max as GEN_Max ,Gen1_Min as GEN_Min ,Power, Windspeed as WindSpeed, Date_S  from daily_data where IMEI = '".$IMEI."' and month(Date_S)=".$MonthYearArr[0]." and Year(Date_S)=".$MonthYearArr[1]."   group by day(Date_S)";
		}elseif($Format_Type==3){
			$Mysql_Query="select Day(Date_S) as Day,Gen1_Max as GEN_Max ,Gen1_Min as GEN_Min  ,Power, Windspeed as WindSpeed, Date_S  from daily_data where IMEI = '".$IMEI."' and month(Date_S)=".$MonthYearArr[0]." and Year(Date_S)=".$MonthYearArr[1]."  group by day(Date_S)";
		}

		//echo $Mysql_Query;
		if (!$Mysql_Query_Result = $db->query($Mysql_Query))
		{
			return [];
		}

		if($Mysql_Query_Result->num_rows >= 1)
		{
			//print_r($PCWP_Chart_Arr); print_r($PCWP_Chart_Val_Arr);
			while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
				$Each_Days_WindSpeedData_Arr_Avg[$Fetch_Result['Day']]=$Fetch_Result['WindSpeed'];
			
				$Each_Days_PowerData_Arr_Avg[$Fetch_Result['Day']]=$Fetch_Result['Power'];
				if($Format_Type==1 || $Format_Type==6){
					$Each_Days_PAT_GEN2_Min_Arr[$Fetch_Result['Day']]=$Fetch_Result['PAT_GEN2_Min'];
			
					$Each_Days_PAT_GEN2_Max_Arr_copy[$Fetch_Result['Day']]=$Each_Days_PAT_GEN2_Max_Arr[$Fetch_Result['Day']]=$Fetch_Result['PAT_GEN2_Max'];
				}elseif($Format_Type==2){
					$Each_Days_G1_Kwh_Min_Arr[$Fetch_Result['Day']]=$Fetch_Result['G1_Kwh_Min'];
			
					$Each_Days_G2_Kwh_Min_Arr[$Fetch_Result['Day']]=$Fetch_Result['G2_Kwh_Min'];
			
					$Each_Days_G1_Kwh_Max_Arr_copy[$Fetch_Result['Day']]=$Each_Days_G1_Kwh_Max_Arr[$Fetch_Result['Day']]=$Fetch_Result['G1_Kwh_Max'];

					$Each_Days_G2_Kwh_Max_Arr_copy[$Fetch_Result['Day']]=$Each_Days_G2_Kwh_Max_Arr[$Fetch_Result['Day']]=$Fetch_Result['G2_Kwh_Max'];
				}elseif($Format_Type==10 || $Format_Type==3){
					$Each_Days_GEN_Min_Arr[$Fetch_Result['Day']]=$Fetch_Result['GEN_Min'];
			
					$Each_Days_GEN_Max_Arr_copy[$Fetch_Result['Day']]=$Each_Days_GEN_Max_Arr[$Fetch_Result['Day']]=$Fetch_Result['GEN_Max'];

				}else{
				
				}
			}//end while
		}//end if
		$Curdate_Query="select (Gen1_Max - Gen1_Min) as PAT_GEN2 from current_data where IMEI = '".$IMEI."' and Date_S=curdate()";
		if (!$Curdate_Query_Result = $db->query($Curdate_Query))
		{
			return [];
		}

		if($Curdate_Query_Result->num_rows >= 1)
		{
			while($Fetch_Result = $Curdate_Query_Result->fetch_array()) {
				$Cur_Days_Generation_Arr=$Fetch_Result['PAT_GEN2'];
			}
		}		
//print_r($curday)	;		
				
		$arrayiteratorcount=0;
		if($Format_Type==1 || $Format_Type==6){
			$PAT_GEN2_Min_Value=0;
			foreach($Each_Days_PAT_GEN2_Max_Arr as $PAT_GEN2_Max_Key => $PAT_GEN2_Max_Value){
				$Each_Days_Generation_Arr[$PAT_GEN2_Max_Key]=$PAT_GEN2_Max_Value-$Each_Days_PAT_GEN2_Min_Arr[$PAT_GEN2_Max_Key];
						
				if($Each_Days_Generation_Arr[$PAT_GEN2_Max_Key]>12000 || $Each_Days_Generation_Arr[$PAT_GEN2_Max_Key] < 0){
					$Each_Days_Generation_Arr[$PAT_GEN2_Max_Key]=0;

				}
	
			}//end foreach
			//array_push($Each_Days_Generation_Arr,$Cur_Days_Generation_Arr);

		}elseif($Format_Type==10 || $Format_Type==3 ){
			$GEN_Min_Value=0;
			foreach($Each_Days_GEN_Max_Arr as $GEN_Max_Key => $GEN_Max_Value){
	
				$Each_Days_Generation_Arr[$GEN_Max_Key]=$GEN_Max_Value-$Each_Days_GEN_Min_Arr[$GEN_Max_Key];
			
				if($Each_Days_Generation_Arr[$GEN_Max_Key]>15000 || $Each_Days_Generation_Arr[$GEN_Max_Key] < 0 ){
					$Each_Days_Generation_Arr[$GEN_Max_Key]=0;
				}
			}//end foreach

		}elseif($Format_Type==2){
		
			$G1_Kwh_Min_Value=0;
			$G2_Kwh_Min_value=0;
			foreach($Each_Days_G1_Kwh_Max_Arr as $G1_Kwh_Max_Key => $G1_Kwh_Max_Value){
	
				$Each_Days_G1_Kwh_Arr[$G1_Kwh_Max_Key]=$G1_Kwh_Max_Value-$Each_Days_G1_Kwh_Min_Arr[$G1_Kwh_Max_Key];
			
			}//end foreach
			$arrayiteratorcount=0;
			foreach($Each_Days_G2_Kwh_Max_Arr as $G2_Kwh_Max_Key => $G2_Kwh_Max_Value){
	
				$Each_Days_G2_Kwh_Arr[$G2_Kwh_Max_Key]=$G2_Kwh_Max_Value-$Each_Days_G2_Kwh_Min_Arr[$G2_Kwh_Max_Key];
			
			}//endforeach		
			$Each_Days_Generation_Arr = array();
			for($i=0;$i<=31;$i++) {
				$Each_Days_Generation_Arr[$i] = round($Each_Days_G1_Kwh_Arr[$i]+$Each_Days_G2_Kwh_Arr[$i]);
				if($Each_Days_Generation_Arr[$i]>7000 || $Each_Days_Generation_Arr[$i] < 0){
					$Each_Days_Generation_Arr[$i]=0;
				}
			}

		}
		else{
			//
		}

		//print_r($Each_Days_Generation_Arr);
		$MaxValKey=array_search(max($Each_Days_Generation_Arr),$Each_Days_Generation_Arr);

		if($Each_Days_Generation_Arr[$MaxValKey]>2000 && $Each_Days_Generation_Arr[$MaxValKey]<0 && $Format_Type==1)
			$Each_Days_Generation_Arr[$MaxValKey]=	$Each_Days_Generation_Arr[$MaxValKey+1];
		if($Each_Days_Generation_Arr[$MaxValKey]>1000 && $Each_Days_Generation_Arr[$MaxValKey]<0  && $Format_Type==2)
			$Each_Days_Generation_Arr[$MaxValKey]=	$Each_Days_Generation_Arr[$MaxValKey+1];	

		$WindSpeed_Total = '';

	}//end if cookie
	
//echo '<pre>'; print_r($Each_Days_Generation_Arr); echo '</pre>';

	$Each_Days_Generation_Arr_Sum=array_sum($Each_Days_Generation_Arr);
	
	$C1 = 0;
	$return = [];
	if($Each_Days_Generation_Arr){
		foreach($Each_Days_Generation_Arr as $key => $value){
			array_push(
				$return,
				[
					'date'=>$key,
					'value'=>$value,
				]
			);
		}
	}
	$output['data'] = $return;
	$output['Each_Days_Generation_Arr_Sum'] = ($Each_Days_Generation_Arr_Sum) ? $Each_Days_Generation_Arr_Sum : 0;
			
	return $output;
    
}

function getbgc($trcount)
{

	$blue="#EEFAF6";
	$green="D4F7EB";
	$odd=$trcount%2;
    if($odd==1){return $blue;}
    else{return $green;}   
} 
function get_list(){
	global $db;
	error_reporting(0);	
	//$output['dd'] = '234234';
	
	if(!checkRequired(['Account_ID'])){
		$output['status']=0;
		$output['message'] = 'Acount Id required';
		return $output;	
	}
	$Account_ID = $_REQUEST['Account_ID'];
	
	$Mysql_Query="select Firstname,Lastname,Username,Password,Account_ID,User_Type_ID,In_Time,Out_Time,Parent_ID,Db_Name from user_master where Account_ID= '$Account_ID'";
	
	$queryResult = $db->query($Mysql_Query);
	
	if (!$queryResult || $queryResult->num_rows <= 0) {
		$output['status']=0;
		$output['message'] = 'Invalid account id.';
		return $output;		
	}
	
	
	$userdata = $queryResult->fetch_object();
	
	
	$data = [];
	$data2 = [];
	
	
	$Mysql_Query = "select * from error_type";
	if (!$queryResult = $db->query($Mysql_Query))
	{
		$output['status']=0;
		$output['message'] = $db->error;
		return $output;	
	}

	if($queryResult->num_rows >= 1)
	{
		while($Fetch_Result2 = $queryResult->fetch_array()) {
		
			$Error_Array[$Fetch_Result2['Machine_Status']][] = $Fetch_Result2['Error'];
			$Machine_Status_Array[$Fetch_Result2['Machine_Status']] = $Fetch_Result2['Machine_Status'];
		}
	}	
	
	
	$Audio=array();
	$td=0;
	$tr=0;
	$CurrentState="";
	$Total_Power=0;
	$CurrentSite="";
	$Total_Export=0;
	$WTG_Run=0;
	
	
	$Date_Range = getDaysInBetween(date("d-m-Y"),date("d-m-Y"));//
	
	foreach($Date_Range as $Date_Range_Val){
		$Date_Range_Start = $Date_Range_Val[0];
		$Date_Range_End = $Date_Range_Val[1];
	}
	$WindSpeed = null;
	$Power = null;
	
	$Username = $userdata->Username;
	$Firstname = $userdata->Firstname;
	$Lastname = $userdata->Lastname;
	$User_Type_ID = $userdata->User_Type_ID;
	$Parent_ID = $userdata->Parent_ID;
	$Database_Name = $userdata->Db_Name;



	
	if($userdata->User_Type_ID ==3 || $userdata->User_Type_ID ==2)
		$Mysql_Query2 = "SELECT t1.*, s.totalCount AS count FROM device_register AS t1 LEFT JOIN ( SELECT Device_Index,State, COUNT(State) totalCount FROM device_register WHERE Parent_ID = '".$Account_ID."' GROUP BY State ) s ON s.State = t1.State where t1.Parent_ID = '".$Account_ID."' ORDER BY count desc,State desc, Device_Order asc";
	elseif($User_Type_ID ==4)
		$Mysql_Query2 = "SELECT t1.*, s.totalCount AS count FROM device_register AS t1 LEFT JOIN ( SELECT Device_Index,State, COUNT(State) totalCount FROM device_register WHERE Account_ID = '".$Account_ID."' GROUP BY State ) s ON s.State = t1.State where t1.Account_ID = '".$Account_ID."' ORDER BY count desc,State desc,Device_Order asc";

	if (!$queryResult2 = $db->query($Mysql_Query2))
	{
		$output['status']=0;
		$output['message'] = $db->error;
		return $output;	
		
	}
		//echo $Mysql_Query2;
	$Mysql_Record_Count = $queryResult2->num_rows;
	if($queryResult2->num_rows >= 1)
	{
		while($Fetch_Result = $queryResult2->fetch_array()) {
				//echo '<pre>'; print_r($Fetch_Result); echo '</pre>'; continue;
		
			$IMEI = base64_encode($Fetch_Result['IMEI']);	
			//echo '<pre>'; print_r($Fetch_Result); continue;
								//setcookie($IMEI_Timer, $Temer, time()+86400);
								
			$HTSCno = $Fetch_Result['HTSC_No'];									
			$WEGno[$Fetch_Result['IMEI']]=$Fetch_Result['WEG_No'];
			$State[$Fetch_Result['IMEI']]=$Fetch_Result['State'];//echo $State;
			$Site_Location[$Fetch_Result['IMEI']]=$Fetch_Result['Site_Location'];//echo $Site_Location;
			$Device_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
			$Device = $Fetch_Result['Device_Name'];
			$Format_Type = $Fetch_Result['Format_Type']; 
			$Pocket_Length = $Fetch_Result['Pocket_Length'];							
			$Connect_Feeder[$Fetch_Result['Site_Location']]=$Fetch_Result['Connect_Feeder'];
			$Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
			$Capacity = $Fetch_Result['capacity'];	

			
			if($Format_Type == 1){
				$Channel_Url = "channel2.php?";
				$Table_Name = "device_data"; 
				$Error_Table_Name = "error_data"; 
			}elseif($Format_Type == 2){										
				if($Device=='Selva Tex 250kw') {
				$Channel_Url = "channel3_selvatex.php?";
				$Table_Name ="device_data_f2";									
				$Error_Table_Name = "error_data_f2";
				} elseif($Account_ID=='100215') {
				$Channel_Url = "channel3_ucal.php?";
				$Table_Name ="device_data_f2";									
				$Error_Table_Name = "error_data_f2";
				} else {
				$Channel_Url = "channel3.php?";
				$Table_Name ="device_data_f2";									
				$Error_Table_Name = "error_data_f2";  
				}
			}elseif($Format_Type == 3){
				$Channel_Url = "channel4.php?";
				$Table_Name = "device_data_f3";									
				$Error_Table_Name = "error_data_f3";
			}elseif($Format_Type == 4){
				if($Device=='Aspire') {
				$Channel_Url = "channel9_new.php?";
				$Table_Name = "device_data_f4"; 
				$Error_Table_Name = "error_data_f4"; 
				} else {
				$Channel_Url = "channel5.php?";
				$Table_Name = "device_data_f4"; 
				$Error_Table_Name = "error_data_f4"; 
				} 	
			}elseif($Format_Type == 6){
				if($Database_Name=='va_siva')
				$Channel_Url = "channel7_old.php?";
				elseif($Database_Name=='va_dhanalakshmi')
				$Channel_Url = "channel7_kvarh.php?";
				else
				$Channel_Url = "channel7.php?";
				$Table_Name = "device_data_f6"; 
				$Error_Table_Name = "error_data_f6"; 
			}elseif($Format_Type == 7){
				if($Device=='ICE MAN') {
				$Channel_Url = "channel1_iceman.php?";
				$Table_Name ="device_data_f7";									
				$Error_Table_Name = "error_data_f7";
				} elseif($Device=='Aalayam S826' || $Device=='Aalayam S824' || $Device=='Aalayam S792') {
				$Channel_Url = "channel8_aalayam.php?";
				$Table_Name = "device_data_f7"; 
				$Error_Table_Name = "error_data_f7";
				} else {
				$Channel_Url = "channel8.php?";
				$Table_Name = "device_data_f7"; 
				$Error_Table_Name = "error_data_f7"; 
				}
			}elseif($Format_Type == 8){
				$Channel_Url = "channel8.php?";
				$Table_Name = "device_data_f8"; 
				$Error_Table_Name = "error_data_f8";  
			}elseif($Format_Type == 9){
				$Channel_Url = "channel9.php?";
				$Table_Name = "device_data_f9"; 
				$Error_Table_Name = "error_data_f9";  
			}elseif($Format_Type == 10){
				$Channel_Url = "channel10.php?";
				$Table_Name = "device_data_f10"; 
				 $Error_Table_Name = "error_data_f10"; 
			}

			#	Encode IMEI
			$IMEI_Decode = base64_decode($IMEI);
			$From_Mon_D_Epoch="01-".date("m-Y");
			$From_Mon_D_Epoch= strtotime($From_Mon_D_Epoch)+(60*60*5.5);
			$To_Mon_D_Epoch=date("d-m-Y");
			$To_Mon_D_Epoch=strtotime($To_Mon_D_Epoch." 23:59:59")+(60*60*5.5);
			$From_Date="01-01-".(date("Y"));
			$To_Date="01-12-".date("Y");				
			$From_Year_D_Epoch = strtotime($From_Date)+(60*60*5.5);
			$To_Year_D_Epoch = strtotime($To_Date." 23:59:59")+(60*60*5.5);
			
			#
			#	Alarm Log from ERROR_DATA
			#
								
								
			if($Closing_Time[$IMEI_Decode]=='06:00:00' || $Closing_Time[$IMEI_Decode]=='06:30:00'){
				$GAD_Time=" and Hour(Time_S)>=6 ";
				$GD_Time=time()-21660;
			}
			elseif($Closing_Time[$IMEI_Decode]=='07:00:00' || $Closing_Time[$IMEI_Decode]=='07:30:00'){
				$GAD_Time=" and Hour(Time_S)>=7 ";
				$GD_Time=time()-25200;
			}
			elseif($Closing_Time[$IMEI_Decode]=='08:00:00' || $Closing_Time[$IMEI_Decode]=='08:30:00'){
				$GAD_Time=" and Hour(Time_S)>=8 ";
				$GD_Time=time()-28800;
			}
			elseif($Closing_Time[$IMEI_Decode]=='09:00:00'){
				$GAD_Time=" and Hour(Time_S)>=9 ";
				$GD_Time=time()-32400;
			}
			elseif($Closing_Time[$IMEI_Decode]=='01:00:00' || $Closing_Time[$IMEI_Decode]=='01:30:00'){
				$GAD_Time=" and Hour(Time_S)>=1 ";
				$GD_Time=time()-3600;
			}
			elseif($Closing_Time[$IMEI_Decode]=='02:00:00' || $Closing_Time[$IMEI_Decode]=='02:30:00'){
				$GAD_Time=" and Hour(Time_S)>=2 ";
				$GD_Time=time()-7200;
			}
			elseif($Closing_Time[$IMEI_Decode]=='04:00:00' || $Closing_Time[$IMEI_Decode]=='04:30:00'){
				$GAD_Time=" and Hour(Time_S)>=4 ";
				$GD_Time=time()-7200;
			}
			else {
				$GAD_Time="";
				$GD_Time=time();
				$Test_Time=date('H',$GD_Time);
			}
					
		//echo $Format_Type;
			if($Format_Type==2)
				$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, devicedata,((Gen1_Max - Gen1_Min)+(Gen2_Max - Gen2_Min)) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";	
			elseif($Format_Type==4)
				$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, ((Gen1_Max - Gen1_Min)+(Gen2_Max - Gen2_Min)) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";	
			elseif($Format_Type==1)
				$Mysql_Query1="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen2_Max - Gen2_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
			elseif($Format_Type==6)
				$Mysql_Query1="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, devicedata, (Gen2_Max - Gen2_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2  from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
			elseif($Format_Type==10)
				$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
			elseif($Format_Type==3)
				$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
			elseif($Format_Type==7){
				//if($Database_Name=='va_aalayam') {
				//	$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, //(Gen1_Max - Gen1_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
				//} else {
				//	$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, Gen1_Hours_Max as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
				//}
				if($Database_Name=='va_aalayam') {
				$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
				} elseif($Database_Name=='va_gwind') {
					$Mysql_Query1 ="select Date_S,Time_S, windspeed as WindSpeed, Power, Status, Gen_Init_Date as G1, Tip_Pressure as G2 from va_gwind.device_data_f7 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
				} 
				elseif($Database_Name=='va_sendan') {
					$Mysql_Query1 ="select Date_S,Time_S, windspeed as WindSpeed, Power, Status, Active_Total_Gen_Export as G1 from va_sendan.device_data_f7 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
				} 
				elseif($Database_Name=='va_renom') {
					$Mysql_Query1 ="select Date_S,Time_S, windspeed as WindSpeed, Power, Status, Active_Total_Gen_Export as G1 from va_renom.device_data_f7 where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
				}
				else {
					$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, Gen1_Hours_Max as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
				}
			} 
			elseif($Format_Type==8)
			$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, Gen1_Hours_Max as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
			elseif($Format_Type==9)
			$Mysql_Query1="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, (Gen1_Hours_Max-Gen2_Max) as G2, Gen2_Hours_Max as Stop from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
			else 
			$Mysql_Query1 ="select Date_S,Time_S,WindSpeed,Power,Status from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
			//echo $Mysql_Query1;
				if($Format_Type==9){
					//echo $Mysql_Query1; die();
				}
			//echo $Format_Type;	
			//die;
			if (!$queryResult1 = $db->query($Mysql_Query1))
			{
				$output['status']=0;
				$output['message'] = $db->error;
				return $output;	
			}

			if($queryResult1->num_rows >= 0)
			{
				$Fetch_Result1 = $queryResult1->fetch_array();	
				//echo '<pre>'; print_r($Fetch_Result1); continue;
				//echo '<pre>'; print_r($Fetch_Result1); echo '</pre>'; continue;
				
				if(!isset($Fetch_Result1['devicedata']) || empty($Fetch_Result1['devicedata'])){
					//continue;
				}
									 										
				$devicedata = explode(',', $Fetch_Result1['devicedata']);
				$G4_Temp=$devicedata[13];
				$G6_Temp=$devicedata[15];			
				$G3= $devicedata[19];
				$Gvarh = $devicedata[27];
				//$Daily_Generated_Units=$Fetch_Result1['Daily_Generated_Units'];
				$WindSpeed = $Fetch_Result1['WindSpeed'];
				$WindSpeed = str_replace('m/s','',$WindSpeed);	
				$WindSpeed != ''? $WindSpeed = number_format($WindSpeed,2) : $WindSpeed = '0.00';			 									
				$Power = $Fetch_Result1['Power'];	
				
				$Power != ''? $Power = number_format($Power,2) : $Power = '0.00';
				$Status1 = trim($Fetch_Result1['Status']);
				$Status = strtolower($Status1);
					
				if($Device_Name[$IMEI_Decode]=='Selvam 11-750kw' && $Status=='emergency line fault') {
					$Status='run';
				}
//echo $Status;
				$G1= ($Format_Type!=7? round($Fetch_Result1['G1']) : $Fetch_Result1['G1']*1000) ;
				// $G1= round($Fetch_Result1['G1']);
				$G2= round($Fetch_Result1['G2']);
				if($G1 >18000 || $G1<0) {
					$G1='0';
				}
				if($G2 >24 || $G2<0) {
					$G2='0';
				}
										//$G2_Limit=$G2*3600;
				$Total_Export+=$G1;//echo $Total_Export;
				$Total_Power+=$Power;
				$Date_F = $Fetch_Result1['Date_S'];
				$Time_F = $Fetch_Result1['Time_S'];
				$GD_Hours=date('H',$GD_Time-($G2*3600));
				//echo $G2."</br>";
				//echo $GD_Hours."</br>";	
				//echo $Date_F."</br>";
				//echo $Time_F."</br>";			
										
				$Device_Epoch_Time=GetTimestamp($Date_F,$Time_F);

				if(!empty($Device_Epoch_Time)){
					$Diff_Error_Status = $Device_Epoch_Time;
					
				}
				//echo $Diff_Error_Status."     ".$Status." imei ".$IMEI."<br>";
				# More than 5 hours not working
				$Req_Time = time()+(60*60*5.5);
				//echo $Status;
				$ReqTime_Diff = $Req_Time - $Device_Epoch_Time;
				//$ReqTime_Diff_err = $Req_Time - $ED_Device_Epoch_Time_Array[$IMEI_Decode];				
				// echo $ReqTime_Diff;
				# Checking data for 5 hours delay


				//echo $ED_Device_Time_Array[$IMEI_Decode] ."</br>";
				//echo $Device_Epoch_Time ."</br>";
				//echo $ReqTime_Diff."</br>";
				//echo $ReqTime_Diff_err."</br>";
				//echo $Status."</br>";
				if($ReqTime_Diff >= 1800 && (in_array($Status,$Error_Array['Green']) && !in_array($Status,$Error_Array['Blue']))){
					$Tower_Img = MAIN_URL.'/images/Grey_jpg.jpg';
				}
				else
				{
					# Depence upon the Status Tower image

					if(in_array($Status,$Error_Array['Green'])){

						if($Power == '000' || $Power == '0' || $Power < 0){
							//$Tower_Img = '<img src="./images/7.jpg" width="69px" height="98px" alt="Orange Tower">';

							$WTG_Run++;

							$Tower_Img = MAIN_URL.'images/7.gif';


						}
						else{
							$WTG_Run++;
									
							$Tower_Img = MAIN_URL.'images/6.gif';

						}	
					}	
					elseif(in_array($Status,$Error_Array['Orange'])){
						$Tower_Img = MAIN_URL.'images/7.gif';
												
												
												
					}	
					elseif(in_array($Status,$Error_Array['Blue'])){
						$Tower_Img = MAIN_URL.'images/Blue_jpg.jpg';
						$Audio[]=$WEGno[$IMEI_Decode];
											
					}
					elseif(in_array($Status,$Error_Array['Pink'])){
						$Tower_Img = MAIN_URL.'images/18.jpg';
						$Audio[]=$WEGno[$IMEI_Decode];
											
					}
					else{											
						$Tower_Img = MAIN_URL.'images/Red_jpg.jpg';
						$Audio[]=$WEGno[$IMEI_Decode];
					}	
				}
			}
									
									

			$PreviousState=$CurrentState;
			
			$echo = '';
			$single = [];
							
			if($CurrentState=="" || $CurrentState!=$State["$IMEI_Decode"]){
				$td=0;	
				
				
							
				$CurrentState=$State["$IMEI_Decode"];
				
			}
			 
			 
			$single['CurrentState'] = $CurrentState;
			$single['Power'] = $Power;
			
			 
			 
			if($Account_ID=='100079' || $Account_ID=='100081' || $Account_ID=='100082' || $Account_ID=='100084' || $Account_ID=='100077' ) {

							//if($CurrentSite=="" || $CurrentSite!=$Site_Location["$IMEI_Decode"] ){
				//$Feeder_List=implode(",",$Connect_Feeder[$Site_Location["$IMEI_Decode"]]);
				$echo .= '<tr><td valign="top" style="width:80px;"><h3>'.$Site_Location["$IMEI_Decode"].'</h3></td></tr>';
				$CurrentSite=$Site_Location["$IMEI_Decode"];
				$single['CurrentSite'] = $CurrentSite;
						
			} else {
				if($CurrentSite=="" || $CurrentSite!=$Site_Location["$IMEI_Decode"] ){
					//$Feeder_List=implode(",",$Connect_Feeder[$Site_Location["$IMEI_Decode"]]);
					//echo '<pre>'; print_r($Connect_Feeder[$Site_Location["$IMEI_Decode"]]); continue;
					$echo .= '<tr><td valign="top" style="width:80px;"><h3>'.$Site_Location["$IMEI_Decode"].'</h3></td></tr>';
					$CurrentSite=$Site_Location["$IMEI_Decode"];
					$single['CurrentSite'] = $CurrentSite;
				}else{
					$single['CurrentSite'] = '';
				}
			} 
			
			
			$Date="";
			$Time="";
			//	$Date_e = strtotime($ED_Error_Date[$IMEI_Decode]);
			//	$Time_e = strtotime($ED_Error_Time[$IMEI_Decode]);
			$Date_G = strtotime($Date_F);
			$Time_G = strtotime($Time_F);

			if(in_array($Status,$Error_Array['Green'])){
				$Date = date('d/m/Y', $Date_G);
				$Time = date('H:i:s', $Time_G);
			}							
								
							
			elseif(in_array($Status,$Error_Array['Blue']) || in_array($Status,$Error_Array['Pink'])) {

				$Date = date('d/m/Y', $Date_G);
				$Time = date('H:i:s', $Time_G);							

							
						
			}

			else {

				$Date = date('d/m/Y', $Date_G);
				$Time = date('H:i:s', $Time_G);
				
	
							
			}
			
			$single['Connect_Feeder'] = $Connect_Feeder[$Site_Location["$IMEI_Decode"]];
			$single['Date'] = $Date;
			$single['Channel_Url'] = $Channel_Url;
			$single['IMEI'] = $IMEI;
			$single['Pocket_Length'] = $Pocket_Length;
			$single['Format_Type'] = $Format_Type;
			$single['IMEI_Decode'] = $IMEI_Decode;
			$single['Tower_Img'] = $Tower_Img;
			$single['Time'] = $Time;
			$single['show_detail'] = false;
			
			if ($Account_ID!='100146') {
				$single['show_detail'] = true;
				//$echo .= '<a href="'.$Channel_Url.'c1='.$IMEI.'&l=<'.$Pocket_Length.'&FType='.$Format_Type.'" border="0" style="cursor:pointer; color:#333333; text-decoration:none;" target="_blank"; title="IMEI :'.$IMEI_Decode.'">'; 
			}
												
			$single['Device_Name'] = $Device_Name[$IMEI_Decode];								
			$single['G4_Temp'] = $G4_Temp ? $G4_Temp : '';								
			$single['HTSCno'] = $HTSCno ? $HTSCno : '';								
			$single['G6_Temp'] = $G6_Temp ? $G6_Temp : '';								
			$single['G3'] = $G3 ? $G3 : '';								
										
															
			$single['Status'] = $Status;								
			$single['WindSpeed'] = $WindSpeed;								
			$single['PV_Instant_Power'] = (isset($PV_Instant_Power) && $PV_Instant_Power!='')?$PV_Instant_Power:$Power;								
			$single['Format_Type'] = $Format_Type;								
			$single['Voltage'] = isset($Voltage) ? $Voltage : '';								
						
							
								
			if($Format_Type == 1 || $Format_Type == 2 || $Format_Type == 6 || $Format_Type == 10 || $Format_Type == 3 || $Format_Type == 7 || $Format_Type == 8 || $Format_Type == 4 || $Format_Type == 9){
				if($Format_Type == 2){								
					if($G1 > 6000 || $G1 < 0)
						$G1 = 0;
									
					if($G2 > 24 || $G2< 0)
						$G2 = 0;
				}
				if($Format_Type == 1  ||  $Format_Type == 6){								
					if($G1 > 25000 || $G1 < 0)
						$G1 = 0;
									
					if($G2 > 24 || $G2< 0)
						$G2 = 0;
				}
				if($Format_Type == 7  ||  $Format_Type == 8){								
					if($G1 > 25000 || $G1 < 0)
						$G1 = 0;
									
					if($G2 > 24 || $G2< 0)
						$G2 = 0;
				}
				if($Format_Type == 3  ||  $Format_Type == 10){	
					if($G2 > 24 || $G2< 0)
						$G2 = 0;
					}								
				if($GD_Hours > "23" || $GD_Hours < "00" || $G2 > $Test_Time){
					$GD_Hours = "00";
				}
				
				$single['G1'] = $G1 != ''?$G1 : '0';
				$single['G2'] = $G2 != ''?$G2 : '0';
				$single['GD_Hours'] = $GD_Hours != ''?$GD_Hours : '0';
							
				if($Format_Type == 7 || $Format_Type == 9) {
					$middle = $single['G2'];
					$single['G2'] = $single['GD_Hours'];
					$single['GD_Hours'] = $middle;
				}
								
							
			}
			
		
						
			array_push($data,$single);
			
			
		}
		
	}
	$output['status']= 1;
	$output['message']= 'Success';
	$output['CurrentState']= $CurrentState;
	$output['Total_Power']= $Total_Power;
	$output['Total_Export']= $Total_Export;
	$output['WTG_Run']= $WTG_Run;
	$output['Mysql_Record_Count']= $Mysql_Record_Count;
	$output['data']= $data;
	
	return $output;
}

function change_password(){
	global $db;
	
	if(!checkRequired(['current_password','new_password','confirm_password','Account_ID'])){
		$output['status']=0;
		$output['message'] = 'check parameters';
		return $output;	
	}
	$current_password = $_REQUEST['current_password'];
	$new_password = $_REQUEST['new_password'];
	$confirm_password = $_REQUEST['confirm_password'];
	$Account_ID = $_REQUEST['Account_ID'];
	
	if($new_password != $confirm_password){
		$output['status']=0;
		$output['message'] = 'New password and confirm password not matched';
		return $output;
	}
	
	$Mysql_Query="select Password from user_master where Account_ID= '$Account_ID'";
	
	$queryResult = $db->query($Mysql_Query);
	
	if (!$queryResult || $queryResult->num_rows <= 0) {
		$output['status']=0;
		$output['message'] = 'Invalid login details';
		return $output;		
	}
	
	
	$userdata = $queryResult->fetch_object();
	
	//$output['userdata'] = $userdata;
	
	if($userdata->Password != $current_password){
		$output['status']=0;
		$output['message'] = 'Current Password is not correct';
		return $output;	
	}
	
	
	$Sql="UPDATE user_master SET Password ='$new_password' WHERE Account_ID = '$Account_ID'"; 

	$db->query($Sql);
				
	$output['status']= 1;
	$output['message']= 'Password hans been changed successfully.';
	return $output;
}

function forget_password(){
	global $db;
	
	if(!checkRequired(['E_Mail'])){
		$output['status']=0;
		$output['message'] = 'check parameters';
		return $output;	
	}
	$E_Mail = $_REQUEST['E_Mail'];
	
	
	$Mysql_Query="select * from user_master where E_Mail= '$E_Mail'";
	
	$queryResult = $db->query($Mysql_Query);
	
	if (!$queryResult || $queryResult->num_rows <= 0) {
		$output['status']=0;
		$output['message'] = 'Email not exist.';
		return $output;		
	}
	
	
	$userdata = $queryResult->fetch_object();
	
	//$output['userdata'] = $userdata;
	
	
	
	//$Sql="UPDATE user_master SET Password ='$new_password' WHERE Account_ID = '$Account_ID'"; 

	//$db->query($Sql);
				
	$output['status']= 1;
	$output['message']= 'Recovery email has been sent.';
	return $output;
}

function login(){
	global $db;
	
	if(!checkRequired(['username','password'])){
		$output['status']=0;
		$output['message'] = 'check parameters';
		return $output;	
	}
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	
	$Mysql_Query="select Firstname,Lastname,Username,Password,Account_ID,User_Type_ID,In_Time,Out_Time,Parent_ID,Db_Name from user_master where Username= '$username' and Password= '$password'";
	
	$queryResult = $db->query($Mysql_Query);
	
	if (!$queryResult || $queryResult->num_rows <= 0) {
		$output['status']=0;
		$output['message'] = 'Invalid login details';
		return $output;		
	}
	
	
	$userdata = $queryResult->fetch_object();
	
	//$output['userdata'] = $userdata;
	
	
	
				
	$output['status']= 1;
	$output['message']= 'Success';
	$output['data']= $userdata;
	return $output;
}

function userdata_by_id(){
	global $db;
	
	if(!checkRequired(['Account_ID'])){
		$output['status']=0;
		$output['message'] = 'check parameters';
		return $output;	
	}
	$Account_ID = $_REQUEST['Account_ID'];
	
	$Mysql_Query="select Firstname,Lastname,Username,Password,Account_ID,E_Mail,User_Type_ID,In_Time,Out_Time,Parent_ID,Db_Name from user_master where Account_ID= '$Account_ID'";
	
	$queryResult = $db->query($Mysql_Query);
	
	if (!$queryResult || $queryResult->num_rows <= 0) {
		$output['status']=0;
		$output['message'] = 'Invalid id';
		return $output;		
	}
	
	
	$userdata = $queryResult->fetch_object();
	
	//$output['userdata'] = $userdata;
	
	
	
				
	$output['status']= 1;
	$output['message']= 'Success';
	$output['data']= $userdata;
	return $output;
}


function checkRequired($array){
	$return = true;
	if(!empty($array)){
		foreach($array as $key => $value){
			if(!isset($_REQUEST[$value]) || $_REQUEST[$value]==NULL){
				$return = false;
				break;
			}
		}
	} else {
		$return = false;
	}
	return $return;
}
?>
