<?php

include("Header.php");
if(empty($_COOKIE[$Cook_Name])){
		header("Location: index.php");
		exit;
	}
			$Format_Type=$_REQUEST['Format_Type'];
				$RI=$_REQUEST['Record_Index'];
//print_r($RI);
$IMEI_STR=implode(",",$_REQUEST['Record_Index']);
//var_dump($IMEI_STR);
				$db_name=$_REQUEST['db_name'];	
				$Date=$_REQUEST['Date'];									
				if($Format_Type == 1){
					$Table_Name = "device_data"; 			
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";														
				}elseif($Format_Type == 2){
					$Table_Name ="device_data_f2";
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";														
				}elseif($Format_Type == 3){
					$Table_Name = "device_data_f3";
					$Date = date("Y-m-d", strtotime($Date));			
					$Query_Date="Date_S='$Date'";										
				}elseif($Format_Type == 4){
					$Table_Name = "device_data_f4"; 
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";					
				}elseif($Format_Type == 6){
					$Table_Name = "device_data_f6"; 
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";					
				}elseif($Format_Type == 7){
					$Table_Name = "device_data_f7";
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";	 
				}elseif($Format_Type == 9){
					$Table_Name = "device_data_f9";
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";	 
				}elseif($Format_Type == 10){
					$Table_Name = "device_data_f10";
					$Date = date("Y-m-d", strtotime($Date)); 
					$Query_Date="Date_S='$Date'";	
				}

 
	$Mysql_Query="delete from va_aspire.$Table_Name where Record_Index IN ($IMEI_STR) and $Query_Date";
			//echo $Mysql_Query;
$Query_Result=mysqli_query($db,$Mysql_Query);
				
if(!$Query_Result){
echo "Not deleted";
//print_r($RI);
}
if($Query_Result) {
echo "record deleted";
   						
//print_r($RI);
}
						




?>
