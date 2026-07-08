<?php
include_once("Connection.php");

######################################
#
#	Name 	 : User Type Array
#	Purpose	 : Basic Type of Users
#
############################################

$Utype_Array= array( 
				1 => 'User',
				2 => 'Client',
				3 => 'Admin'
			);


######################################
#
#	Name 	 : Format Type Array
#	Purpose	 : Basic Type of Users
#
############################################

$Ftype_Array= array( 
				1 => 'Format 1',
				2 => 'Format 2',				
				3 => 'Format 3',
				4 => 'Format 4',
				5 => 'Format 5',				
				6 => 'Format 6',
				7 => 'Format 7',
				8 => 'Format 8',				
				9 => 'Format 9',
				10 => 'Format 10',
				11 => 'Format 11',				
				12 => 'Format 12',
				13 => 'Format 13',
				14 => 'Format 14',				
				15 => 'Format 15'				
			);
			
######################################
#
#	Name 	 : Staus Array
#	Purpose	 : User staus Active or InActive
#
############################################

$Status_Array = array( 
				1 => 'Active',
				2 => 'Not Active'
			);

$Status_Array1 = array( 
				'Enabled' => 'Enabled',
				'Disabled' => 'Disabled'
			);


######################################
#
#	Name 	 : Form Elements Array
#	Purpose	 : Form element type 
#
############################################

$Form_Element_Array = array( 
				1 => 'text',
				2 => 'password',
				3 => 'radio',
				4 => 'checkbox',
				5 => 'submit',
				6 => 'button',
				7 => 'hidden',
				8 => 'file',
			);

######################################
#
#	Name 	 : Date Format Array
#
############################################

$Date_Format_Array = array(
				1 => 'Y-m-d H:i:s',
				2 => 'd-m-Y H:i:s',
				3 => 'Y-m-d',
				4 => 'H:i:s',
				5 => 'd-m-Y',
				6 => 'd-M-Y'
			);
			
######################################
#
#	Gender Array
#
############################################

$Gender_Array = array(
				1 => 'Male',
				2 => 'Female'
			);


######################################
#
#	Data_PocketLength_Array
#
############################################

	//Data Format Array
	$Data_PocketLength_Array = array(
		//'38' => 'Packet 38 => Type 1',
		'39' => 'Packet 39 => Type 1',
		'32' => 'Packet 26 => Type 2',
		'31' => 'Packet 31 => Type 2',
		'57' => 'Packet 57 => Type 3',
		'38' => 'Packet 38 => Type 5',
		'55' => 'Packet 55 => Type 6',
		'34' => 'Packet 34 => Type 4',
		'67' => 'Packet 67 => Type 7',
		'64' => 'Packet 64 => Type 8',
		'78' => 'Packet 78 => Type 9',
		'42' => 'Packet 42 => Type 10',
		'85' => 'Packet 85 => Type 11'
	);
	
######################################
#
#	//Data Format Array
#
############################################

	
	$Data_Format_Array = array(
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',				
		'6' => '6',
		'7' => '7',
		'8' => '8',				
		'9' => '9',
		'10' => '10',
		'11' => '11',				
		'12' => '12',
		'13' => '13',
		'14' => '14',				
		'15' => '15'
	);
######################################
#
#	Power Curve Type
#
############################################

	//Data Format Array
	$Power_Curve_Array = array(
		'600' => '600 KW',
		'500' => '500 KW',
		'250' => '250 KW',
		'225' => '225 KW',
		'350' => '350 KW',
		'750' => '750 KW',
		'850' => '850 KW',
		'1250' => '1250 KW'
	);


$State_Array = array(
		'Tamil Nadu' => 'Tamil Nadu',
		'Gujarat' => 'Gujarat',
		'Karnataka' => 'Karnataka',
		'Andhra Pradesh' => 'Andhra Pradesh',
		'Madhya Pradesh' => 'Madhya Pradesh',
		'Maharashtra' => 'Maharashtra',
		'West Bengal' => 'West Bengal'

		
	);
######################################
#
#	Name 	 : Form Elements Array
#	Purpose	 : Form element type 
#
############################################

$day_array = array( 

				1 => '1',

				2 => '2',

				3 => '3',

				4 => '4',

				5 => '5',

				6 => '6',

				7 => '7',

				8 => '8',

				9 => '9',

				10 => '10',

				11 => '11',

				12 => '12',

				13 => '13',

				14 => '14',

				15 => '15',

				16 => '16',

				17 => '17',

				18 => '18',

				19 => '19',

				20 => '20',

				21 => '21',

				22 => '22',

				23 => '23',

				24 => '24',

				25 => '25',

				26 => '26',

				27 => '27',

				28 => '28',

				29 => '29',

				30 => '30',

				31 => '31',

			);

			

$month_array = array( 

				1 => 'Jan',

				2 => 'Feb',

				3 => 'Mar',

				4 => 'Apr',

				5 => 'May',

				6 => 'Jun',

				7 => 'Jul',

				8 => 'Aug',

				9 => 'Sep',

				10 => 'Oct',

				11 => 'Nov',

				12 => 'Dec',

			);



for($i=date('Y'); $i<= date('Y')+1; $i++)

	$year_array["$i"] = "$i";


	//Data Format Array
	$Contract_Status_Array = array(
		1 => 'Warranty',
		2 => 'Extented Warranty',
		3 => 'Extended O&M',
		4 => 'CAOMC',
		5 => 'AOMC',
		6 => 'AMC',
		7 => 'Mini CAOMC'
	);
	$Hour24_Array=array( 

				"01:00:00" => '01:00:00',

				"02:00:00" => "02:00:00",

				"03:00:00" => "03:00:00",

				"04:00:00" => "04:00:00",

				"05:00:00" => "05:00:00",

				"06:00:00" => "06:00:00",

				"07:00:00" => "07:00:00",

				"08:00:00" => "08:00:00",

				"09:00:00" => "09:00:00",

				"10:00:00" => "10:00:00",

				"11:00:00" => "11:00:00",

				"12:00:00" => "12:00:00",
				
				"13:00:00" => '13:00:00',

				"14:00:00" => "14:00:00",

				"15:00:00" => "15:00:00",

				"16:00:00" => "16:00:00",

				"17:00:00" => "17:00:00",

				"18:00:00" => "18:00:00",

				"19:00:00" => "19:00:00",

				"20:00:00" => "20:00:00",

				"21:00:00" => "21:00:00",

				"22:00:00" => "22:00:00",

				"23:00:00" => "23:00:00",

				"00:00:00" => "00:00:00"
			);

?>