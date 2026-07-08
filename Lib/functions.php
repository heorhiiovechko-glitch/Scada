<?php

function sendSMS($number,$message) {
	$url = 'http://bhashsms.com/api/sendmsg.php';
	$customer = 'success';
	$key = '123456';
        $sender = 'BSHSMS';
	$request = 'http://bhashsms.com/api/sendmsg.php?user=success&pass=123456&sender=BSHSMS&phone=9790832323&text=hi&priority=ndnd&stype=normal';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $request);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
$results = explode("\n", $response);

    return $results[4];
	//return split(',',$response);
}


    function arraySumRecursive($array)
    {
        $total = 0;
        foreach(new recursiveIteratorIterator( new recursiveArrayIterator($array)) as $num)
        {
            $total += $num;
        }
        return $total;
    }

	# Get all dates between two dates using php code:
	function getAllDatesBetweenTwoDates($First,$Last,$Step = '+1 day', $Format = 'd.m.Y')
	{
   	    $Dates = array();
    		$Current = strtotime($First);
    		$Last = strtotime($Last);

   		 while( $Current <= $Last ) { 

       		 	$Dates[] = date($Format, $Current);
       			$Current = strtotime($Step, $Current);
   	 }

   	 return $Dates;
	}

	// Date Difference
	function dateDiffer ($d1, $d2) {
	// Return the number of days between the two dates:

	  return round(abs(strtotime($d1)-strtotime($d2))/86400);

	}  // end function dateDiff

	$Counter_Total = array('MzU5NzcxMDM0NjUxODcw');

function GetUnixTimestamp($Date, $Time){
  $day   = substr($Date,0,2);
  $month = substr($Date,3,2);
  $year  = substr($Date,6,4);

  $hour  = substr($Time,0,2);
  $mins  = substr($Time,3,2);
  $secs  = substr($Time,6,2);

  $timestamp = gmmktime($hour,$mins,$secs,$month,$day,$year);
  return $timestamp;
}

function GetTimestamp($Date, $Time){
  $day   = substr($Date,8,2);
  $month = substr($Date,5,2);
  $year  = substr($Date,0,4);

  $hour  = substr($Time,0,2);
  $mins  = substr($Time,3,2);
  $secs  = substr($Time,6,2);

  $timestamp = gmmktime($hour,$mins,$secs,$month,$day,$year);
  return $timestamp;
}

function UnixTimestamp($Date_Time){
  $day   = substr($Date_Time,0,2);
  $month = substr($Date_Time,3,2);
  $year  = substr($Date_Time,6,4);

  $hour  = substr($Date_Time,11,2);
  $mins  = substr($Date_Time,15,2);
  $secs  = substr($Date_Time,18,2);

  $timestamp = gmmktime($hour,$mins,$secs,$month,$day,$year);
  return $timestamp;
}



	function getDaysInBetween($start, $end) {
	 // Vars
	 $day = 86400; // Day in seconds
	 $format = 'Y-m-d'; // Output format (see PHP date funciton)
	 $sTime = strtotime($start); // Start as time
	 $eTime = strtotime($end); // End as time
	 $numDays = round(($eTime - $sTime) / $day) + 1;
	 $days = array();

	 // Get days
	 for ($d = 0; $d < $numDays; $d++) {
	  $days_in = date($format, ($sTime + ($d * $day)));
		$From_D_Epoch = strtotime($days_in)+(60*60*5.5);
		$To_D_Epoch = strtotime($days_in." 23:59:59")+(60*60*5.5);

	  $days[$From_D_Epoch] = array($From_D_Epoch,$To_D_Epoch);
	 }

	 // Return days
	 return $days;
	} 
	
	
	#
	# 	Seconds to TIme conversion
	#	
		function Sec2Time($time,$Out){

			global $sec_to_time_val;

		  if(is_numeric($time)){

			$sec_to_time_val = array(

			  "years" => 0, "days" => 0, "hours" => 0,

			  "minutes" => 0, "seconds" => 0,

			);

			if($time >= 31556926){

			  $sec_to_time_val["years"] = floor($time/31556926);

			  $time = ($time%31556926);

			}

			if($time >= 86400){

			  $sec_to_time_val["days"] = floor($time/86400);

			  $time = ($time%86400);

			}

			if($time >= 3600){
				$Hou = floor($time/3600);
				if(strlen($Hou) == 1)
					 $Hou =  "0".$Hou;
			  $sec_to_time_val["hours"] = $Hou;

			  $time = ($time%3600);

			}

			if($time >= 60){
				$Min = floor($time/60);
				if(strlen($Min) == 1)
					 $Min =  "0".$Min;
			  $sec_to_time_val["minutes"] = $Min;

			  $time = ($time%60);

			}

			$sec_to_time_val["seconds"] = floor($time);

			(array) $sec_to_time_val;

		  }else{

			return (bool) FALSE;

		  }	
			if($Out == 'f')
				return "".$sec_to_time_val['hours'] ." : ".$sec_to_time_val['minutes']." : ".$sec_to_time_val['seconds']."" ;
			elseif($Out == 'm')	
				return "".$sec_to_time_val['hours'] .".".$sec_to_time_val['minutes']."" ;

		}


	#
	#	Reference Curve - 359771034651870
	#
	$PCWP_Chart_Arr_500 = array(		
			'0' => '0',
			'0.5' => '0',
			'1.0' => '0',
			'1.5' => '0',
			'2.0' => '0',
			'2.5' => '0',
			'3' => '12',
			'3.5' => '34',
			'4' => '56',
			'4.5' => '78',
			'5' => '100',
			'5.5' => '122',
			'6' => '144',
			'6.5' => '166',
			'7' => '188',
			'7.5' => '210',
			'8' => '232',
			'8.5' => '254',
			'9' => '276',
			'9.5' => '298',
			'10' => '320',
			'10.5' => '342',
			'11' => '364',
			'11.5' => '386',
			'12' => '408',
			'12.5' => '430',
			'13' => '452',
			'13.5' => '474',
			'14' => '496',
			'14.5' => '500',
			'15' => '500',
			'15.5' => '500',
			'16' => '500',
			'16.5' => '500',
			'17' => '500',
			'17.5' => '500',
			'18' => '500',
			'18.5' => '500',
			'19' => '500',
			'19.5' => '500',
			'20' => '500',
			'20.5' => '500',
			'21' => '500',
			'21.5' => '500',
			'22' => '500',
			'22.5' => '500',
			'23' => '500',
			'23.5' => '500',
			'24' => '500',
			'24.5' => '500',
			'25' => '500'
			);

			
	$PCWP_Chart_Val_Arr_500 = array(		
			'0' =>  array(0.0,0.00),
			'0.5' => array(0.0,0.00),
			'1.0' => array(0.0,0.00),
			'1.5' => array(0.0,0.00),
			'2.0' => array(0.0,0.00),
			'2.5' => array(0.1,2.99),
			'3' => array(3.0,3.50),
			'3.5' => array(3.51,3.99),
			'4' => array(4.0,4.50),
			'4.5' => array(4.51,4.99),
			'5' => array(5.0,5.50),
			'5.5' => array(5.51,5.99),
			'6' => array(6.0,6.50),
			'6.5' => array(6.51,6.99),
			'7' => array(7.0,7.50),
			'7.5' => array(7.51,7.99),
			'8' => array(8.0,8.50),
			'8.5' => array(8.51,8.99),
			'9' => array(9.0,9.50),
			'9.5' => array(9.51,9.99),
			'10' => array(10.0,10.50),
			'10.5' => array(10.51,10.99),
			'11' => array(11.0,11.50),
			'11.5' => array(11.51,11.99),
			'12' => array(12.0,12.50),
			'12.5' => array(12.51,12.99),
			'13' => array(13.0,13.50),
			'13.5' => array(13.51,13.99),
			'14' => array(14.0,14.50),
			'14.5' => array(14.51,14.99),
			'15' => array(15,15.50),
			'15.5' => array(15.51,15.99),
			'16' => array(16,16.50),
			'16.5' => array(16.51,16.99),
			'17' => array(17,17.50),
			'17.5' => array(17.51,17.99),
			'18' => array(18,18.50),
			'18.5' => array(18.51,18.99),
			'19' => array(19,19.50),
			'19.5' => array(19.51,19.99),
			'20' => array(20,20.50),
			'20.5' => array(20.51,20.99),
			'21' => array(21,21.50),
			'21.5' => array(21.51,21.99),
			'22' => array(22,22.50),
			'22.5' => array(22.51,22.99),
			'23' => array(23,23.50),
			'23.5' => array(23.51,23.99),
			'24' => array(24,24.50),
			'24.5' => array(24.51,24.99),
			'25' => array(25,25.50)
			);
			
	#
	#	Reference Curve - 600KW
	#
	$PCWP_Chart_Arr_600 = array(		
			'0' => '0',
			'0.5' => '0',
			'1.0' => '0',
			'1.5' => '0',
			'2.0' => '0',
			'2.5' => '0',
			'3' => '12',
			'3.5' => '38',
			'4' => '64',
			'4.5' => '90',
			'5' => '116',
			'5.5' => '142',
			'6' => '168',
			'6.5' => '194',
			'7' => '220',
			'7.5' => '246',
			'8' => '272',
			'8.5' => '298',
			'9' => '324',
			'9.5' => '350',
			'10' => '376',
			'10.5' => '402',
			'11' => '428',
			'11.5' => '454',
			'12' => '480',
			'12.5' => '506',
			'13' => '532',
			'13.5' => '558',
			'14' => '584',
			'14.5' => '600',
			'15' => '600',
			'15.5' => '600',
			'16' => '600',
			'16.5' => '600',
			'17' => '600',
			'17.5' => '600',
			'18' => '600',
			'18.5' => '600',
			'19' => '600',
			'19.5' => '600',
			'20' => '600',
			'20.5' => '600',
			'21' => '600',
			'21.5' => '600',
			'22' => '600',
			'22.5' => '600',
			'23' => '600',
			'23.5' => '600',
			'24' => '600',
			'24.5' => '600',
			'25' => '600'
			);

			
	$PCWP_Chart_Val_Arr_600 = array(		
			'0' =>  array(0.0,0.00),
			'0.5' => array(0.0,0.00),
			'1.0' => array(0.0,0.00),
			'1.5' => array(0.0,0.00),
			'2.0' => array(0.0,0.00),
			'2.5' => array(0.1,2.99),
			'3' => array(3.0,3.50),
			'3.5' => array(3.51,3.99),
			'4' => array(4.0,4.50),
			'4.5' => array(4.51,4.99),
			'5' => array(5.0,5.50),
			'5.5' => array(5.51,5.99),
			'6' => array(6.0,6.50),
			'6.5' => array(6.51,6.99),
			'7' => array(7.0,7.50),
			'7.5' => array(7.51,7.99),
			'8' => array(8.0,8.50),
			'8.5' => array(8.51,8.99),
			'9' => array(9.0,9.50),
			'9.5' => array(9.51,9.99),
			'10' => array(10.0,10.50),
			'10.5' => array(10.51,10.99),
			'11' => array(11.0,11.50),
			'11.5' => array(11.51,11.99),
			'12' => array(12.0,12.50),
			'12.5' => array(12.51,12.99),
			'13' => array(13.0,13.50),
			'13.5' => array(13.51,13.99),
			'14' => array(14.0,14.50),
			'14.5' => array(14.51,14.99),
			'15' => array(15,15.50),
			'15.5' => array(15.51,15.99),
			'16' => array(16,16.50),
			'16.5' => array(16.51,16.99),
			'17' => array(17,17.50),
			'17.5' => array(17.51,17.99),
			'18' => array(18,18.50),
			'18.5' => array(18.51,18.99),
			'19' => array(19,19.50),
			'19.5' => array(19.51,19.99),
			'20' => array(20,20.50),
			'20.5' => array(20.51,20.99),
			'21' => array(21,21.50),
			'21.5' => array(21.51,21.99),
			'22' => array(22,22.50),
			'22.5' => array(22.51,22.99),
			'23' => array(23,23.50),
			'23.5' => array(23.51,23.99),
			'24' => array(24,24.50),
			'24.5' => array(24.51,24.99),
			'25' => array(25,25.50)
			);
			
			
	#
	#	Reference Curve - 225KW
	#
	$PCWP_Chart_Arr_225 = array(		
			'0' => '0',
			'1' => '0',
			'2' => '0',
			'3' => '4',
			'4' => '9',
			'5' => '19.5',
			'6' => '32.8',
			'7' => '58.8',
			'8' => '87.6',
			'9' => '118.9',
			'10' => '149.6',
			'11' => '171.6',
			'12' => '189.2',
			'13' => '206',
			'14' => '217',
			'15' => '230',
			'16' => '234',
			'17' => '230',
			'18' => '229',
			'19' => '225',
			'20' => '215',
			'21' => '212',
			'22' => '210',
			'23' => '210',
			'24' => '210',
			'25' => '210'
			);	
			
	$PCWP_Chart_Val_Arr_225 = array(		
			'0' => array(0.0,0.0),
			'1' => array(1.0,1.99),
			'2' => array(2.0,2.99),
			'3' => array(3.0,3.99),
			'4' => array(4.0,4.99),
			'5' => array(5.0,5.99),
			'6' => array(6.0,6.99),
			'7' => array(7.0,7.99),
			'8' => array(8.0,8.99),
			'9' => array(9.0,9.99),
			'10' => array(10.0,10.99),
			'11' => array(11.0,11.99),
			'12' => array(12.0,12.99),
			'13' => array(13.0,13.99),
			'14' => array(14.0,14.99),
			'15' => array(15,15.99),
			'16' => array(16,16.99),
			'17' => array(17,17.99),
			'18' => array(18,18.99),
			'19' => array(19,19.99),
			'20' => array(20,20.99),
			'21' => array(21,21.99),
			'22' => array(22,22.99),
			'23' => array(23,23.99),
			'24' => array(24,24.99),
			'25' => array(25,25.99)
			);
			
			
			
	#
	#	Reference Curve - 250KW - Type 2 - 27 Pocket
	#
	$PCWP_Chart_Arr_250 = array(		
			'0' => '0.0',
			'1' => '0.0',
			'2' => '0.0',
			'3' => '0.25',
			'4' => '8.65',
			'5' => '17.89',
			'6' => '30.44',
			'7' => '52.75',
			'8' => '85.53',
			'9' => '118.07',
			'10' => '152.66',
			'11' => '178.05',
			'12' => '203.05',
			'13' => '228.38',
			'14' => '246.28',
			'15' => '250.85',
			'16' => '250.54',
			'17' => '251.36',
			'18' => '246.59',
			'19' => '245.85',
			'20' => '243.88',
			'21' => '244.60',
			'22' => '239.11',
			'23' => '234.05',
			'24' => '234.05',
			'25' => '234.05'
			);	
			
	$PCWP_Chart_Val_Arr_250 = array(		
			'0' => array(0.0,0.0),
			'1' => array(1.0,1.99),
			'2' => array(2.0,2.99),
			'3' => array(3.0,3.99),
			'4' => array(4.0,4.99),
			'5' => array(5.0,5.99),
			'6' => array(6.0,6.99),
			'7' => array(7.0,7.99),
			'8' => array(8.0,8.99),
			'9' => array(9.0,9.99),
			'10' => array(10.0,10.99),
			'11' => array(11.0,11.99),
			'12' => array(12.0,12.99),
			'13' => array(13.0,13.99),
			'14' => array(14.0,14.99),
			'15' => array(15,15.99),
			'16' => array(16,16.99),
			'17' => array(17,17.99),
			'18' => array(18,18.99),
			'19' => array(19,19.99),
			'20' => array(20,20.99),
			'21' => array(21,21.99),
			'22' => array(22,22.99),
			'23' => array(23,23.99),
			'24' => array(24,24.99),
			'25' => array(25,25.99)
			);
	
	
	#
	#	Reference Curve - 850KW - Type 2 - 27 Pocket
	#
	
	
	
	$PCWP_Chart_Arr_850 = array(		
			'0' => '0.0',
			'1' => '0.0',
			'2' => '0.0',
			'3' => '7.60',
			'4' => '27.70',
			'5' => '72.60',
			'6' => '136.90',
			'7' => '225.40',
			'8' => '343.80',
			'9' => '489.80',
			'10' => '646.20',
			'11' => '742.60',
			'12' => '798.30',
			'13' => '823.30',
			'14' => '840.60',
			'15' => '846.40',
			'16' => '846.70',
			'17' => '850.0',
			'18' => '850.0',
			'19' => '850.0',
			'20' => '850.0',
			'21' => '850.0',
			'22' => '850.0',
			'23' => '850.0',
			'24' => '850.0',
			'25' => '850.0'
			);	
			
	$PCWP_Chart_Val_Arr_850 = array(		
			'0' => array(0.0,0.0),
			'1' => array(1.0,1.99),
			'2' => array(2.0,2.99),
			'3' => array(3.0,3.99),
			'4' => array(4.0,4.99),
			'5' => array(5.0,5.99),
			'6' => array(6.0,6.99),
			'7' => array(7.0,7.99),
			'8' => array(8.0,8.99),
			'9' => array(9.0,9.99),
			'10' => array(10.0,10.99),
			'11' => array(11.0,11.99),
			'12' => array(12.0,12.99),
			'13' => array(13.0,13.99),
			'14' => array(14.0,14.99),
			'15' => array(15,15.99),
			'16' => array(16,16.99),
			'17' => array(17,17.99),
			'18' => array(18,18.99),
			'19' => array(19,19.99),
			'20' => array(20,20.99),
			'21' => array(21,21.99),
			'22' => array(22,22.99),
			'23' => array(23,23.99),
			'24' => array(24,24.99),
			'25' => array(25,25.99)
			);
			
			
			
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

	//Type
	$IMC_Type_Array = array(
		'Lts' => 'Lts',
		'Kg' => 'Kg',
		'No\'s' => 'No\'s'
	);

	//Satis
	$Satis_Array = array(
		'Satisfactory' => 'Satisfactory',
		'Not Satisfactory' => 'Not Satisfactory'
	);

	//Chargable
	$Chargeable_Array = array(
		'Chargeable' => 'Chargeable',
		'Non - Chargeable' => 'Non - Chargeable'
	);

	
	/*
	
	Gert Date Difference
	
	*/
	
function get_date_diff($start, $end, $type)
{
        $sdate = strtotime($start);
        $edate = strtotime($end);
        $timeshift = "";

        $time = $edate - $sdate;
        if($time>=0 && $time<=59) {
                // Seconds
                $timeshift = $time.' seconds ';

        } elseif($time>=60 && $time<=3599) {
                // Minutes + Seconds
                $pmin = ($edate - $sdate) / 60;
                $premin = explode('.', $pmin);

                $presec = $pmin-$premin[0];
                $sec = $presec*60;

                $timeshift = $premin[0].' min '.round($sec,0).' sec '."<b>ago</b>";

        } elseif($time>=3600 && $time<=86399) {
                // Hours + Minutes
                $phour = ($edate - $sdate) / 3600;
                $prehour = explode('.',$phour);

                $premin = $phour-$prehour[0];
                $min = explode('.',$premin*60);

                $presec = '0.'.$min[1];
                $sec = $presec*60;

                //$timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec '."<b>ago</b>";
				$prehour[0] = strlen($prehour[0]) == 1?$prehour[0] = "0".$prehour[0]  : $prehour[0];
				$min[0] = strlen($min[0]) == 1?$min[0] = "0".$min[0]  : $min[0];
				$timeshift = $prehour[0].':'.$min[0];

        } elseif($time>=86400) {
                // Days + Hours + Minutes
                $pday = ($edate - $sdate) / 86400;
                $preday = explode('.',$pday);

                $phour = $pday-$preday[0];
                $prehour = explode('.',$phour*24); 

                $premin = ($phour*24)-$prehour[0];
                $min = explode('.',$premin*60);

                $presec = '0.'.$min[1];
                $sec = $presec*60;
               // $timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec '."<b>ago</b>";
				$prehour[0] = strlen($prehour[0]) == 1?$prehour[0] = "0".$prehour[0]  : $prehour[0];
				$min[0] = strlen($min[0]) == 1?$min[0] = "0".$min[0]  : $min[0];
                $timeshift = $preday[0].' days '.$prehour[0].':'.$min[0];



        }
        return $timeshift;
}

	#####################################################
	#
	# 	Log File Creation - 17-01-2012 
	#
	#####################################################

	function File_Creation($Data,$Path,$File_Type,$Write_Type){
		if($Write_Type)
		$handle = fopen($Path, $Write_Type);
		else
		$handle = fopen($Path, 'a+');
		//if($handle)echo "file opened";
		chmod($Path, 0777);
		$Log_File_Read = file($Path);
		$Log_File_Read_Count = count($Log_File_Read);
		if(($Log_File_Read_Count%2) == 0)
			$Log_File_Count = ($Log_File_Read_Count/2)+1;
			
		//$Test_DATA = "$Log_File_Count - ".date("d-m-Y H:i:s")." $Data";
		$Test_DATA = $Data;
		if(!fwrite($handle, "$Test_DATA")) die("couldn't write to file. : Check the Folder permisson for (".$Path.")");
		else
			return "<div id='error_text'><div class='Db_Error'>".$File_Type." written done.</div></div>";
	}




/*function Check_Exist($Tab_Name,$Duplicate_Columns_Final,$Duplicate_Column){
		$Mysql_Query = "select * from $Tab_Name where $Duplicate_Columns_Final";//echo $Mysql_Query ;
		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
		$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
		if($Mysql_Record_Count>=1){
			return $Mysql_Record_Count;
		}
		else 
		return 0;
}*/


?>