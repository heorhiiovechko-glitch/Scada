<?php
$db = new mysqli("localhost:3306", "vscada","vscada600038","va_aspire");
$Mysql_Query1 ="select * from device_data_f8 where IMEI='868714041148617' and Date_S=curdate() order by Record_Index desc limit 1";	
	if (!$Mysql_Query_Result1 = $db->query($Mysql_Query1))
            {
                die($db->error);
            }
$Mysql_Record_Count1=$Mysql_Query_Result1->num_rows;
            if($Mysql_Query_Result1->num_rows >= 1)
            {
                while($Fetch_Result1 = $Mysql_Query_Result1->fetch_array()) {										
			$Date_S = $Fetch_Result1['Date_S'];
			$Time_S = $Fetch_Result1['Time_S'];
			$Temp4 = $Fetch_Result1['Date'];
			$Temp5 = $Fetch_Result1['Time'];
			$Temp6 = $Fetch_Result1['Power'];
			$Temp3 = $Fetch_Result1['Windspeed'];
			$Temp2 = "NA";
			$Temp1 = "NA";
			$Temp7 = $Fetch_Result1['Reactive_Power'];
			$Temp8 = $Fetch_Result1['L_N_Voltage_R'];
			$Temp9 = $Fetch_Result1['L_N_Voltage_Y'];
			$Temp10 = $Fetch_Result1['L_N_Voltage_B'];
			$Temp11 = $Fetch_Result1['L_L_Voltage_RY'];
			$Temp12 = $Fetch_Result1['L_L_Voltage_YB'];
			$Temp13 = $Fetch_Result1['L_L_Voltage_BR'];
			$Temp14 = $Fetch_Result1['RPhase_Current'];
			$Temp15 = $Fetch_Result1['YPhase_Current'];
			$Temp16 = $Fetch_Result1['BPhase_Current'];
			$Temp17 = round($Fetch_Result1['Power_Factor'],0);
			$Temp18 = round($Fetch_Result1['Frequency'],0);
			$Temp19 = $Fetch_Result1['Active_Total_Gen_Import'];
			$Temp20 = $Fetch_Result1['Active_Total_Gen_Export'];
			
			/*$Energy = $Fetch_Result1['Min3_Wind_Speed'];
			$FR_Min =$Fetch_Result1['Min3_Wind_Dir'];*/
			$FR_Sec =$Fetch_Result1['Min3_Active_Power']."l/s";
			$ZNTP = round($Fetch_Result1['Gen_DE_Bearing_Temp'],0)=='0'?'OFF':'ON';
			$ZNACleaning = round($Fetch_Result1['Gen_DE_NDE_Bearing_Temp'],0)=='0'?'OFF':'ON';
			$ZNSD = round($Fetch_Result1['Nacelle_Temp'],0)=='0'?'OFF':'ON';
			$ZSD1 = round($Fetch_Result1['Nacelle_Position'],0)=='0'?'OFF':'ON';
			$ZSD2 = round($Fetch_Result1['Cable_Twist'],0)=='0'?'OFF':'ON';
			$ZACleaning = round($Fetch_Result1['Kwh_Positive'],0)=='0'?'OFF':'ON';
			$Alkali1 = round($Fetch_Result1['Kwh_Negative'],0)=='0'?'OFF':'ON';
			$Alkali2 = round($Fetch_Result1['KVar_Positive'],0)=='0'?'OFF':'ON';
			$Cir1pump = round($Fetch_Result1['KVar_Negative'],0)=='0'?'OFF':'ON';
			$Cir2pump = round($Fetch_Result1['Operate_Hours'],0)=='0' || $Cir1pump=='ON'?'OFF':'ON';
			/*$Water_Pump_OP = round($Fetch_Result1['Reactive_Gen1_Import'],0)=='0'?'OFF':'ON';*/
			
	}
}
			
 header("Content-type: image/png");
    $imgPath = 'plating_schematic.png';
	$image = imagecreatefrompng($imgPath);	
	if($ZNTP=='ON') {
	$ZNTP_image='Plating_Pump_On.png';
	} else {
	$ZNTP_image='Plating_Pump_Off.png';
	}
	if($ZNACleaning=='ON') {
	$ZNACleaning_image='Plating_Pump_On.png';
	} else {
	$ZNACleaning_image='Plating_Pump_Off.png';
	}
	if($ZNSD=='ON') {
	$ZNSD_image='Plating_Pump_On.png';
	} else {
	$ZNSD_image='Plating_Pump_Off.png';
	}
	if($ZSD1=='ON') {
	$ZSD1_image='Plating_Pump_On.png';
	} else {
	$ZSD1_image='Plating_Pump_Off.png';
	}
	if($ZSD2=='ON') {
	$ZSD2_image='Plating_Pump_On.png';
	} else {
	$ZSD2_image='Plating_Pump_Off.png';
	}
	if($ZACleaning=='ON') {
	$ZACleaning_image='Plating_Pump_On.png';
	} else {
	$ZACleaning_image='Plating_Pump_Off.png';
	}
	if($Alkali1=='ON') {
	$Alkali1_image='Plating_Pump_On.png';
	} else {
	$Alkali1_image='Plating_Pump_Off.png';
	}
	if($Alkali2=='ON') {
	$Alkali2_image='Plating_Pump_On.png';
	} else {
	$Alkali2_image='Plating_Pump_Off.png';
	}
	if($Cir1pump=='ON') {
	$Cir1pump_image='Plating_WaterPump_On.png';
	} else {
	$Cir1pump_image='Plating_WaterPump_Off.png';
	}
	if($Cir2pump=='ON') {
	$Cir2pump_image='Plating_WaterPump_On.png';
	} else {
	$Cir2pump_image='Plating_WaterPump_Off.png';
	}
	$Waterpump_image='Plating_WaterPump_On.png';
	$ZNTPimg = imagecreatefrompng($ZNTP_image);
	$ZNACleaningimg = imagecreatefrompng($ZNACleaning_image);
	$ZNSDimg = imagecreatefrompng($ZNSD_image);
	$ZSD1img = imagecreatefrompng($ZSD1_image);
	$ZSD2img = imagecreatefrompng($ZSD2_image);
	$ZACleaningimg = imagecreatefrompng($ZACleaning_image);
	$Alkali1img = imagecreatefrompng($Alkali1_image);
	$Alkali2img = imagecreatefrompng($Alkali2_image);	
	$Cir1pumpimg = imagecreatefrompng($Cir1pump_image);
	$Cir2pumpimg = imagecreatefrompng($Cir2pump_image);
	$Waterpumpimg = imagecreatefrompng($Waterpump_image);
	$color1 = imagecolorallocate($image, 0, 0, 0);
	$color = imagecolorallocate($image, 255, 0, 0);	
    $string = $Temp1;
    $fontSize = 13;
	$fontSize1 = 3;
    $x = 965;
    $y = 198;
    imagestring($image,$fontSize, $x, $y, $string, $color);
    $string1 = $Temp2;
    $x1 = 965;
    $y1 = 108;
    imagestring($image, $fontSize, $x1, $y1, $string1, $color);
    $string2 = $Temp3;
    $x2 = 749;
    $y2 = 199;
    imagestring($image, $fontSize, $x2, $y2, $string2, $color);
    $string3 = $Temp4;
    $x3 = 749;
    $y3 = 110;
    imagestring($image, $fontSize, $x3, $y3, $string3, $color);
    $string4 = $Temp5;
    $x4 = 500;
    $y4 = 199;
    imagestring($image, $fontSize, $x4, $y4, $string4, $color);
    $string5 = $Temp6;
    $x5 = 500;
    $y5 = 110;
    imagestring($image, $fontSize, $x5, $y5, $string5, $color);
    $string6 = $Temp7;
    $x6 = 500;
    $y6 = 376;
    imagestring($image, $fontSize, $x6, $y6, $string6, $color);
    $string7 = $Temp8;
    $x7 = 500;
    $y7 = 288;
    imagestring($image, $fontSize, $x7, $y7, $string7, $color);
    $string8 = $Temp9;
    $x8 = 748;
    $y8 = 376;
    imagestring($image, $fontSize, $x8, $y8, $string8, $color);
    $string9 = $Temp10;
    $x9 = 748;
    $y9 = 290;
    imagestring($image, $fontSize, $x9, $y9, $string9, $color);
    $string10 = $Temp11;
    $x10 = 965;
    $y10 = 377;
    imagestring($image, $fontSize, $x10, $y10, $string10, $color);
    $string11 = $Temp12;
    $x11 = 965;
    $y11 = 290;
    imagestring($image, $fontSize, $x11, $y11, $string11, $color);
	$string12 = $Temp13;
    $x12 = 500;
    $y12 = 554;
	imagestring($image, $fontSize, $x12, $y12, $string12, $color);
	$string13 = $Temp14;
    $x13 = 500;
    $y13 = 464;
    imagestring($image, $fontSize, $x13, $y13, $string13, $color);
	$string14 = $Temp15;
    $x14 = 748;
    $y14 = 554;
    imagestring($image, $fontSize, $x14, $y14, $string14, $color);
	$string15 = $Temp16;
    $x15 = 748;
    $y15 = 464;
    imagestring($image, $fontSize, $x15, $y15, $string15, $color);
	$string16 = $Temp17;
    $x16 = 242;
    $y16 = 164;
    imagestring($image, $fontSize, $x16, $y16, $string16, $color);
	$string17 = $Temp18;
    $x17 = 329;
    $y17 = 212;
    imagestring($image, $fontSize, $x17, $y17, $string17, $color);
	$string18 = $Temp20;
    $x18 = 70;
    $y18 = 473;
    imagestring($image, $fontSize, $x18, $y18, $string18, $color);
	$string19 = $Temp19;
    $x19 = 160;
    $y19 = 552;
    imagestring($image, $fontSize, $x19, $y19, $string19, $color);
	$string20 = $Temp5;
    $x20 = 565;
    $y20 = 228;
    imagestring($image, $fontSize, $x20, $y20, $string20, $color);
	$string21 = $Temp3;
    $x21 = 799;
    $y21 = 230;
    imagestring($image, $fontSize, $x21, $y21, $string21, $color);
	$string22 = $Temp1;
    $x22 = 1025;
    $y22 = 228;
    imagestring($image, $fontSize, $x22, $y22, $string22, $color);
	$string23 = $Temp7;
    $x23 = 564;
    $y23 = 405;
    imagestring($image, $fontSize, $x23, $y23, $string23, $color);
	$string24 = $Temp9;
    $x24 = 799;
    $y24 = 405;
    imagestring($image, $fontSize, $x24, $y24, $string24, $color);
	$string25 = $Temp11;
    $x25 = 1025;
    $y25 = 405;
    imagestring($image, $fontSize, $x25, $y25, $string25, $color);
	$string26 = $Temp13;
    $x26 = 565;
    $y26 = 570;
    imagestring($image, $fontSize, $x26, $y26, $string26, $color);
	$string27 = $Temp15;
    $x27 = 799;
    $y27 = 570;
    imagestring($image, $fontSize, $x27, $y27, $string27, $color);
	$string30 = $FR_Sec;
    $x30 = 228;
    $y30 = 239;
    imagestring($image, $fontSize, $x30, $y30, $string30, $color);
	$string31 = "outlet";
    $x31 = 50;
    $y31 = 461;
    imagestring($image, $fontSize1, $x31, $y31, $string31, $color1);
	$string32 = "Inlet";
    $x32 = 136;
    $y32 = 543;
    imagestring($image, $fontSize1, $x32, $y32, $string32, $color1);
	imagecopy($image, $ZNSDimg, 470, 224, 0, 0, imagesx($ZNSDimg), imagesy($ZNSDimg));
	imagecopy($image, $ZNACleaningimg, 705, 222, 0, 0, imagesx($ZNACleaningimg), imagesy($ZNACleaningimg));
	imagecopy($image, $ZNTPimg, 932, 220, 0, 0, imagesx($ZNTPimg), imagesy($ZNTPimg));
	imagecopy($image, $ZSD1img, 471, 400, 0, 0, imagesx($ZSD1img), imagesy($ZSD1img));
	imagecopy($image, $ZSD2img,705, 400, 0, 0, imagesx($ZSD2img), imagesy($ZSD2img));
	imagecopy($image, $ZACleaningimg, 927, 396, 0, 0, imagesx($ZACleaningimg), imagesy($ZACleaningimg));
	imagecopy($image, $Alkali1img, 470, 575, 0, 0, imagesx($Alkali1img), imagesy($Alkali1img));
	imagecopy($image, $Alkali2img, 705, 575, 0, 0, imagesx($Alkali2img), imagesy($Alkali2img));
	imagecopy($image, $Cir1pumpimg, 172, 185, 0, 0, imagesx($Cir1pumpimg), imagesy($Cir1pumpimg));
	imagecopy($image, $Cir2pumpimg, 172, 210, 0, 0, imagesx($Cir2pumpimg), imagesy($Cir2pumpimg)); 
	imagecopy($image, $Waterpumpimg, 88, 599, 0, 0, imagesx($Waterpumpimg), imagesy($Waterpumpimg));
    imagepng($image);
?>