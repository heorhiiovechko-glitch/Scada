<?php


######################################################
#
#		Variable Declaration Start
#
#######################################################


	$Public_Base_Url = "http://103.38.50.83:8080";
	//$Administration_Home="http://110.234.152.165/Fasttrack";
	$Administration_Home = $Public_Base_Url . "/gogreen/Home.php";
	$Cook_Name = "WindUser";
	$Title = "WindMill - Administration";
	$Site_Heading = "Welcome to Versatile SCADA Administration";
	$Footer_Link = $Public_Base_Url;
	$Website_Name = "WindMill";
	$Tmp_Upload = "Tmp_Upload";
	$Img_Big_Prefix = "big_";
	$Img_Thumb_Prefix = "thumb_";
	$Logo_Name = "Ver.jpg";
	$Serial_Logs_File_Path = "C:\Program Files\Apache Software Foundation\Apache2.2\htdocs\versatilescada.com\gogreen\Logs\Serial_Logs";
	$Serial_Logs_File_Path_Raw = "C:\inetpub\wwwroot\versatile_log\Serial_Logs";
	//$Serial_Logs_File_Path_Raw = "C:\wamp\www\Versatile_1.0.2\bmw\Serial_Logs";
	$WTWMD_Logs_File_Path = "C:\Program Files\Apache Software Foundation\Apache2.2\htdocs\versatilescada.com\gogreen\Logs\WTWMD_Logs";
	$WTSYS_Logs_File_Path = "C:\Program Files\Apache Software Foundation\Apache2.2\htdocs\versatilescada.com\gogreen\Logs\WTSYS_Logs";

	$Serial_Logs_File_Path_Link = $Public_Base_Url . "/gogreen/Logs/Serial_Logs";
	$WTWMD_Logs_File_Path_Link = $Public_Base_Url . "/gogreen/Logs/WTWMD_Logs";
	$WTSYS_Logs_File_Path_Link = $Public_Base_Url . "/gogreen/Logs/WTSYS_Logs";
	


######################################################
#
#		Admin Account
#
#######################################################


		/*$Mysql_Query = "select * from user_master where User_Type_ID = 4 and AdminAccess = 'Enabled'";
		$Mysql_Query_Result = mysqli_query($Mysql_Query);
		if (!$Mysql_Query_Result)
            {
                die("Connection failed: " . mysqli_connect_error());
            }

            if(mysqli_num_rows($Mysql_Query_Result) >= 1)
            {
                while($Fetch_Result = mysqli_fetch_array($Mysql_Query_Result)) {
				$Firstname_Admin_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['Firstname']." ".$Fetch_Result['Lastname'];
				$Firstname_ByID_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['Firstname'];
				$Lastname_Array[$Fetch_Result['Lastname']] = $Fetch_Result['Lastname'];
			}
		}*/

######################################################
#
#		USER MASTER Table
#
#######################################################


		/*$Mysql_Query = "select * from user_master";
		$Mysql_Query_Result = mysqli_query($Mysql_Query);
		if (!$Mysql_Query_Result)
            {
                die("Connection failed: " . mysqli_connect_error());
            }

            if(mysqli_num_rows($Mysql_Query_Result) >= 1)
            {
                while($Fetch_Result = mysqli_fetch_array($Mysql_Query_Result)) {
					$Firstname_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['Firstname'];
				$Lastname_Array[$Fetch_Result['Lastname']] = $Fetch_Result['Lastname'];
				$Firstname_Lastname_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['Firstname']." ".$Fetch_Result['Lastname'];
				$Username_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['Username'];
				$Password_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['Password'];
				$UserTypeID_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['User_Type_ID'];
				$ParentID_Array[$Fetch_Result['Account_ID']] = $Fetch_Result['Parent_ID'];
			}
		}*/


######################################################
#
#		DEVICE TYPE
#
#######################################################


		/* $Mysql_Query = "select * from device_type";
		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
		$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
		if($Mysql_Record_Count>=1){
			while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){
				$Device_Type_Array[$Fetch_Result['Device_Type_ID']] = $Fetch_Result['Device_Type'];
			}
		}*/

?>