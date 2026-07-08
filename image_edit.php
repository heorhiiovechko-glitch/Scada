<?php
$db = new mysqli("localhost:3306", "vscada","vscada600038","va_aspire");
//mysql_select_db();
$Mysql_Query1 ="select RRPM,GRPM,Windspeed,Date,time,Power,Reactive_Power,L_N_Voltage_R,L_N_Voltage_Y,L_N_Voltage_B,L_L_Voltage_RY,L_L_Voltage_YB,Min3_Wind_Speed,Min3_Wind_Dir,Min3_Active_Power,Date_S,Time_S,L_L_Voltage_BR,RPhase_Current,YPhase_Current,BPhase_Current,Power_Factor,Frequency,Active_Total_Gen_Import,Active_Total_Gen_Export,Reactive_Total_Gen_Import,Reactive_Total_Gen_Export,Active_Gen1_Import,Active_Gen1_Export,Reactive_Gen1_Import,Reactive_Gen1_Export,Active_Gen2_Export from device_data_f8 where /*IMEI='862462035397941'*/ IMEI='868324027712897' and Date_S=curdate() order by Record_Index desc limit 1";	
																						
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
			$Temp5 = $Fetch_Result1['time'];
			$Temp6 = $Fetch_Result1['Power'];
			$Temp3 = $Fetch_Result1['Windspeed'];
			$Temp2 = $Fetch_Result1['GRPM'];
			$Temp1 = $Fetch_Result1['RRPM'];
			$Temp7 = $Fetch_Result1['Reactive_Power'];
			$Temp8 = $Fetch_Result1['L_N_Voltage_R'];
			$Temp9 = $Fetch_Result1['L_N_Voltage_Y'];
			$Temp10 = $Fetch_Result1['L_N_Voltage_B'];
			$Temp11 = $Fetch_Result1['L_L_Voltage_RY'];
			$Temp12 = $Fetch_Result1['L_L_Voltage_YB'];
			$Energy = $Fetch_Result1['Min3_Wind_Speed'];
			$FR_Min =$Fetch_Result1['Min3_Wind_Dir'];
			$FR_Sec =$Fetch_Result1['Min3_Active_Power']." l/s";
			$KW64_OP = round($Fetch_Result1['L_L_Voltage_BR'],0)=='0'?'OFF':'ON';
			$KW14_OP = round($Fetch_Result1['RPhase_Current'],0)=='0'?'OFF':'ON';
			$Pass_Pump_OP = round($Fetch_Result1['YPhase_Current'],0)=='0'?'OFF':'ON';
			$Phas_Pump_OP = round($Fetch_Result1['BPhase_Current'],0)=='0'?'OFF':'ON';
			$Degr_Pump_OP = round($Fetch_Result1['Power_Factor'],0)=='0'?'OFF':'ON';
			$Strip_Pump_OP = round($Fetch_Result1['Frequency'],0)=='0'?'OFF':'ON';
			$Cir1_Pump_OP = round($Fetch_Result1['Active_Total_Gen_Import'],0)=='0'?'OFF':'ON';
			$Cir2_Pump_OP = round($Fetch_Result1['Active_Total_Gen_Export'],0)=='0'?'OFF':'ON';
			$Heater1_OP = round($Fetch_Result1['Reactive_Total_Gen_Import'],0)=='0'?'OFF':'ON';
			$Heater2_OP = round($Fetch_Result1['Reactive_Total_Gen_Export'],0)=='0'?'OFF':'ON';
			$Heater3_OP = round($Fetch_Result1['Active_Gen1_Import'],0)=='0'?'OFF':'ON';
			$Heater4_OP = round($Fetch_Result1['Active_Gen2_Export'],0)=='0'?'OFF':'ON';
			$Water_Pump_OP = round($Fetch_Result1['Reactive_Gen1_Import'],0)=='0'?'OFF':'ON';
			
	}
}
			
 header("Content-type: image/png");
    $imgPath = 'php_new.png';
	$image = imagecreatefrompng($imgPath);	
	if($Pass_Pump_OP=='ON') {
	$Pass_image='Circulation Pump On Pass.png';
	} else {
	$Pass_image='Circulation Pump Off Pass_new.png';
	}
	if($Phas_Pump_OP=='ON') {
	$Phas_image='Circulation Pump On Pass.png';
	} else {
	$Phas_image='Circulation Pump Off Pass_new.png';
	}
	if($Degr_Pump_OP=='ON') {
	$Degr_image='Circulation Pump On Pass.png';
	} else {
	$Degr_image='Circulation Pump Off Pass_new.png';
	}
	if($Strip_Pump_OP=='ON') {
	$Strip_image='Circulation Pump On Pass.png';
	} else {
	$Strip_image='Circulation Pump Off Pass_new.png';
	}
	if($Water_Pump_OP=='ON') {
	$Water_image='Circulation Pump On.png';
	} else {
	$Water_image='Circulation Pump Off.png';
	}
	if($Cir1_Pump_OP=='ON') {
	$Cir1_image='Circulation Pump On.png';
	} else {
	$Cir1_image='Circulation Pump Off.png';
	 }
	 if($Cir2_Pump_OP=='ON') {
	$Cir2_image='Circulation Pump On.png';
	} else {
	$Cir2_image='Circulation Pump Off.png';
	 }
	$Passimg = imagecreatefrompng($Pass_image);
	$Phasimg = imagecreatefrompng($Phas_image);
	$Degrimg = imagecreatefrompng($Degr_image);
	$Stripimg = imagecreatefrompng($Strip_image);
	$Waterimg = imagecreatefrompng($Water_image);
	$Cir1img = imagecreatefrompng($Cir1_image);
	$Cir2img = imagecreatefrompng($Cir2_image);
	$color = imagecolorallocate($image, 255, 0, 0);	
    $string = $Temp7;
    $fontSize = 13;
    $x = 805;
    $y = 9;
    imagestring($image,$fontSize, $x, $y, $string, $color);
    $string1 = $Temp8;
    $x1 = 830;
    $y1 = 93;
    imagestring($image, $fontSize, $x1, $y1, $string1, $color);
    $string2 = $Temp5;
    $x2 = 808;
    $y2 = 178;
    imagestring($image, $fontSize, $x2, $y2, $string2, $color);
    $string3 = $Temp6;
    $x3 = 833;
    $y3 = 261;
    imagestring($image, $fontSize, $x3, $y3, $string3, $color);
    $string4 = $Temp3;
    $x4 = 809;
    $y4 = 344;
    imagestring($image, $fontSize, $x4, $y4, $string4, $color);
    $string5 = $Temp4;
    $x5 = 835;
    $y5 = 429;
    imagestring($image, $fontSize, $x5, $y5, $string5, $color);
    $string6 = $Temp1;
    $x6 = 813;
    $y6 = 520;
    imagestring($image, $fontSize, $x6, $y6, $string6, $color);
    $string7 = $Temp2;
    $x7 = 842;
    $y7 = 604;
    imagestring($image, $fontSize, $x7, $y7, $string7, $color);
    $string8 = $Temp11;
    $x8 = 445;
    $y8 = 109;
    imagestring($image, $fontSize, $x8, $y8, $string8, $color);
    $string9 = $Temp12;
    $x9 = 327;
    $y9 = 309;
    imagestring($image, $fontSize, $x9, $y9, $string9, $color);
    $string10 = $Temp10;
    $x10 = 573;
    $y10 = 512;
    imagestring($image, $fontSize, $x10, $y10, $string10, $color);
    $string11 = $Temp9;
    $x11 = 573;
    $y11 = 565;
    imagestring($image, $fontSize, $x11, $y11, $string11, $color);
	$string12 = $FR_Sec;
    $x12 = 276;
    $y12 = 383;
    imagestring($image, $fontSize, $x12, $y12, $string12, $color);
	$string13 = $Date_S;
    $x13 = 40;
    $y13 = 20;
    imagestring($image, $fontSize, $x13, $y13, $string13, $color);
	$string14 = $Time_S;
    $x14 = 150;
    $y14 = 20;
    imagestring($image, $fontSize, $x14, $y14, $string14, $color);
	imagecopy($image, $Stripimg, 778, 103, 0, 0, imagesx($Stripimg), imagesy($Stripimg));
	imagecopy($image, $Degrimg, 779, 271, 0, 0, imagesx($Degrimg), imagesy($Degrimg));
	imagecopy($image, $Phasimg, 781, 433, 0, 0, imagesx($Phasimg), imagesy($Phasimg));
	imagecopy($image, $Passimg, 788, 612, 0, 0, imagesx($Passimg), imagesy($Passimg));
	imagecopy($image, $Waterimg,427, 539, 0, 0, imagesx($Waterimg), imagesy($Waterimg));
	imagecopy($image, $Cir1img, 208, 286, 0, 0, imagesx($Cir1img), imagesy($Cir1img));
	imagecopy($image, $Cir2img, 206, 354, 0, 0, imagesx($Cir2img), imagesy($Cir2img));
    imagepng($image);
?>