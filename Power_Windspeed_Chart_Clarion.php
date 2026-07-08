<?php

	include("header_inner.php");
	if(empty($_COOKIE[$Cook_Name])){
		header('Location: index.php');
		exit;
	}
?>
<?php
	error_reporting(0);

	$From_D_Epoch = $_REQUEST['From'];
	$TO_D_Epoch = $_REQUEST['To'];
	//echo $_REQUEST['From'];
	//echo $_REQUEST['To'];
	$Extract_Date = date("Y-m-d",$From_D_Epoch);
	$Extract_Date_Till = date("Y-m-d",$TO_D_Epoch);
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
//echo $Extract_Date;
//echo $Extract_Date1;
if(isset($_REQUEST['c1'])){	
		$To_D_Epoch = $_REQUEST['To'];
		$To_Date = date("Y-m-d",$To_D_Epoch);
		$To_Date1=date("d.m.Y",$To_D_Epoch);
		$IMEI = base64_decode($_REQUEST['c1']);
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
			elseif($Power_Curve_Array[$IMEI] == 850){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_850;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_850;
			}
		}//end if


//Getting All Hours Calculation

		if($Format_Type==1)

		$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Max ,MIN(GREATEST(PAT_GEN2,0)) as PAT_GEN2_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Cook_Variable[7].device_data where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and PAT_GEN2 >0 group by HOUR(Time_S)";
		elseif($Format_Type==4)
		$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(PAT_GEN1,0)) as G1_Kwh_Max,MIN(GREATEST(PAT_GEN1,0)) as G1_Kwh_Min,MAX(GREATEST(PAT_GEN2,0)) as G2_Kwh_Max,MIN(GREATEST(PAT_GEN2,0)) as G2_Kwh_Min,round(AVG(GREATEST(POWER,0)),2)   as Power ,ROUND(AVG(WINDSPEED),2) as WindSpeed ,Date_S,Time_S  from $Cook_Variable[7].device_data_f4 where IMEI = '".$IMEI."' and Date_S='$Extract_Date'  and PAT_Gen1 between 0 and 10000000 and PAT_Gen2 between 0 and 10000000 group by HOUR(Time_S)";

		elseif($Format_Type==2)
		$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(PAT_GEN1,0)) as G1_Kwh_Max,MIN(GREATEST(PAT_GEN1,0)) as G1_Kwh_Min,MAX(GREATEST(PAT_GEN2,0)) as G2_Kwh_Max,MIN(GREATEST(PAT_GEN2,0)) as G2_Kwh_Min,round(AVG(GREATEST(POWER,0)),2)   as Power ,ROUND(AVG(WINDSPEED),2) as WindSpeed ,Date_S,Time_S  from $Cook_Variable[7].device_data_f2 where IMEI = '".$IMEI."' and Date_S='$Extract_Date'  and PAT_Gen1 between 0 and 10000000 and PAT_Gen2 between 0 and 10000000 group by HOUR(Time_S)";
		elseif($Format_Type==6)
		$Mysql_Query="select @a:=@a+1 serial_number,Date_S, TIME(from_unixtime(ROUND(unix_timestamp(TIME_S) / (60*10)) * 60 * 10)) as TenMinutes,MAX(GREATEST(PAT_GEN2,0)) as GEN_Max ,MIN(GREATEST(PAT_GEN2,0)) as GEN_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Cook_Variable[7].device_data_f6 , (SELECT @a:= 0) AS a where IMEI = '".$IMEI."' and /*Date_S='$Extract_Date'*/ (Date_S >= '".$Extract_Date."' and  Date_S <= '".$Extract_Date_Till."') and PAT_GEN2 between 0 and 20000000  group by Date_S,TenMinutes";
		elseif($Format_Type==7)
		$Mysql_Query="select @a:=@a+1 serial_number,Date_S, TIME(from_unixtime(ROUND(unix_timestamp(TIME_S) / (60*10)) * 60 * 10)) as TenMinutes,MAX(GREATEST(PAT_GEN2,0)) as GEN_Max ,MIN(GREATEST(PAT_GEN2,0)) as GEN_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Cook_Variable[7].device_data_f6 , (SELECT @a:= 0) AS a where IMEI = '".$IMEI."' and /*Date_S='$Extract_Date'*/ (Date_S >= '".$Extract_Date."' and  Date_S <= '".$Extract_Date_Till."') and PAT_GEN2 between 0 and 20000000  group by Date_S,TenMinutes";
		elseif($Format_Type==10)
		$Mysql_Query="select HOUR(TIME_S) as Hour ,MAX(GREATEST(Production_Total,0)) as GEN_Max ,MIN(GREATEST(Production_Total,0)) as GEN_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Cook_Variable[7].device_data_f10 where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and Production_Total between 0 and 20000000  group by HOUR(Time_S)";
		elseif($Format_Type==9)
		$Mysql_Query="select HOUR(TIME_S) as Hour ,MAX(GREATEST(P_Kwh,0)) as GEN_Max ,MIN(GREATEST(P_Kwh,0)) as GEN_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Cook_Variable[7].device_data_f9 where IMEI = '".$IMEI."' and Date_S='$Extract_Date' and P_Kwh between 0 and 20000000  group by HOUR(Time_S)";
		elseif($Format_Type==3)
		$Mysql_Query="select @a:=@a+1 serial_number,Date_S, TIME(from_unixtime(ROUND(unix_timestamp(TIME_S) / (60*10)) * 60 * 10)) as TenMinutes,MAX(GREATEST(Production_Total,0)) as GEN_Max ,MIN(GREATEST(Production_Total,0)) as GEN_Min ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed, Date_S,Time_S  from $Cook_Variable[7].device_data_f3 , (SELECT @a:= 0) AS a where IMEI = '".$IMEI."' and /*Date_S='$Extract_Date'*/ (Date_S >= '".$Extract_Date."' and  Date_S <= '".$Extract_Date_Till."') and Production_Total between 0 and 20000000  group by Date_S,TenMinutes";

		//echo $Mysql_Query;
		//echo "<hr>";
		
		if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }
			$ChartWind = array();
			$ChartPower = array();
            if($Mysql_Query_Result->num_rows >= 1)
            {
				$MI = 1;
              while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
				// echo $Format_Type;
			if($Format_Type==6 || $Format_Type==3){
				
				$ChartWind[$Fetch_Result['serial_number']] = $Fetch_Result['WindSpeed'];
				$ChartPower[$Fetch_Result['serial_number']] = $Fetch_Result['Power'];
				
				$Each_Hours_WindSpeedData_Arr_Avg[$Fetch_Result['serial_number']]=$Fetch_Result['WindSpeed'];
				
				$Each_Hours_PowerData_Arr_Avg[$Fetch_Result['serial_number']]=$Fetch_Result['Power'];
		
					
				$Each_Hours_GEN_Min_Arr[$Fetch_Result['TenMinutes']]=$Fetch_Result['GEN_Min'];
				
				$Each_Hours_GEN_Max_Arr_copy[$Fetch_Result['TenMinutes']]=$Each_Hours_GEN_Max_Arr[$Fetch_Result['TenMinutes']]=$Fetch_Result['GEN_Max'];

				}else{
				echo "No Records";
				}

				//$DET = $Fetch_Result['Device_Epoch_Time'];

				//echo $DET;
				#	Seperating One Hours values for each
				//print_r($Hours_Arr);


			}//end while
		}//end if
		//print_r($ChartWind);
		//echo "<hr>";
		//print_r($ChartPower);
		//echo "<hr>";
		//print_r($Each_Hours_G1_Kwh_Max_Arr);
		$arrayiteratorcount=0;
		if($Format_Type==10 || $Format_Type==3 || $Format_Type==7 || $Format_Type==9 || $Format_Type==6 ){
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

		}
		else{
		echo "no records";
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



		# WindSpeed and Power Combine
		$PCWP_Chart_Arr1 = array_combine($Each_Hours_WindSpeedData_Arr_Avg,$Each_Hours_PowerData_Arr_Avg);
		$PCWP_Chart_ArrCombine = array_combine($ChartWind,$ChartPower);

	//	print_r($PCWP_Chart_Arr1);
	//	echo "<hr>";
	//		print_r($PCWP_Chart_ArrCombine);
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
					//unset($PCWP_Chart_Arr1[$Final_Key]);
				}
			}
		}
	
	//print_r($PCWP_Chart_Arr1_Filter_Both);
//echo "<hr>";
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

			$Cur_Count1 = count($PCWP_Chart_Arr_Filter);
		//	echo $Cur_Count1;

}//end if cookie

//print_r($Each_Hours_G2Kwh_Arr_Diff);echo "<br>";
//print_r($Each_Hours_G1Kwh_Arr_Diff);echo "<br>";


$Each_Hours_Generation_Arr_Sum=array_sum($Each_Hours_Generation_Arr);
		//echo $Each_Hours_Generation_Arr_Sum."<hr>";

	
		
			
		
?>


<?php
if($Format_Type==1 )
$Filename="channel2.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==2)
$Filename="channel3.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==6)
$Filename="channel7.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==10)
$Filename="channel10.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==3)
$Filename="channel4.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==4)
$Filename="channel5.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==7)
{
	if($Database_Name=='va_renom')
		$Filename="channel8_renom1.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
	else
	    $Filename="channel3.php";
}
elseif($Format_Type==9)
$Filename="channel9new.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;

else
$Filename="channel3.php";

//echo $Filename;
?>

<html>
  <head>
    <script type="text/javascript" src="./js/chartjs.js"></script>
	<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
  google.charts.load('current', {packages: ['corechart']});  
</script>

    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
	  
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data =new google.visualization.DataTable();
	data.addColumn('number', 'Time');
   	data.addColumn('number', 'WindSpeed');
	
         data.addRows([
              

		  <?php
			$C = 0;
			
			$Cur_Count = count($Each_Hours_WindSpeedData_Arr_Avg);
			
			foreach($Each_Hours_WindSpeedData_Arr_Avg as $EHWA_Key => $EHWA_Val){
				
				//$temp=(int)$temp; 
		  ?>
				[ parseInt(<?= $EHWA_Key ?>)+1 , parseInt(<?= $EHWA_Val ?>) ] <?=($C == ($Cur_Count-1)?'' : ',')?>
		  <?php
				$C++;
			}
		  ?>
		 
        ]);
      var formatter = new google.visualization.NumberFormat();
   
      formatter.format(data, 0);
      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1, 1]);
	
        var options = {curveType: "function",width: 900, height: 400,
        pointSize: 4,focusTarget: 'datum',
          title: ' Wind Speed Graph Detail Dated on  <?=date("d-m-Y",strtotime($Extract_Date))?> ',
           titleTextStyle:  {color: 'black', fontName:'Times New Roman', fontSize: 18},
	series: {0: { lineWidth: 2},1: {color: 'red',lineWidth: 0,pointSize: 5}},
		        legend: 'none',
          backgroundColor:{stroke:'#999',strokeWidth:3},
          hAxis: {title: 'Time - Hrs', titleTextStyle: {color: 'red'}, maxValue:24, minValue:1  ,titleTextStyle: {color: 'red',fontSize:18,  fontName:'Times New Roman'}, gridlines:{count:24},viewWindowMode:"explicit", viewWindow: {min:1,max:24}},
          vAxis: {title: 'Wind Speed (m/s)', titleTextStyle: {color: 'red'},logScale:'false',   titleTextStyle: {color: 'red', fontSize:18,  fontName:'Times New Roman'},minValue:0,viewWindowMode:"explicit", viewWindow: {min:0}}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_wind'));
        //chart.draw(view, options);
      }
    </script>






 <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
	  google.charts.load('current', {packages: ['corechart']}); 
      google.setOnLoadCallback(drawChart);
      function drawChart() {

			 var data = google.visualization.arrayToDataTable([['Wind Speed',  'Measured Curve', "Reference Curve"],
			 
			 	     <?php 
		   $Cur_Count = count($refArray);
		   if($Format_Type==3) {
		   $refArray = array(0,0,0,0,19.5,53.1,97.4,155.3,244.6,349.2,462.2,564.5,640.5,696.3,729.8,745.5,750,750,750,750,750,750,750,750);
		   } else {
			$refArray = array(0,0,0,24,67,123,200,291,366,426,466,487,495,500,500,500,500,500,500,500,500,500,500);
		   }
		   foreach($refArray as $PCA1F_Key => $PCA1F_Val){ 
           ?>
		  
		  [<?= $PCA1F_Key ?>, null,<?= $PCA1F_Val ?>]<?=','?>
          
          <?php 
		  }
		   ?>
		   
		  <?php
			$C = 0;
			$Cur_Count = count($PCWP_Chart_ArrCombine);
	
				//,850.0,850.0); 
				//$Cur_Count = count($refArray);
			foreach($PCWP_Chart_ArrCombine as $PCA1F_Key => $PCA1F_Val){ 
           ?>
		  
		  [<?= $PCA1F_Key ?>, <?= $PCA1F_Val ?>,null]<?=($C == ($Cur_Count-1)?'' : ',')?>
          
          <?php 
		  }
		   ?>
		   
	
		   
		  
		
        ]);
		
	
	   

        var options = {curveType: "function",width: 1024, height: 768,
        pointSize: 4,focusTarget: 'datum',
          title: ' Power curve Day Detail Dated  From  <?=date("d-m-Y",strtotime($Extract_Date))?> to <?=date("d-m-Y",strtotime($Extract_Date_Till))?>', 
	 backgroundColor:{stroke:'#999',strokeWidth:3},
	 series: {1: { color: 'Red',lineWidth: 2,pointSize: 0},0: {color: 'green',lineWidth: 0,pointSize: 2}},
	  //series: { 0: { pointShape: 'circle' } }  ,
	  titleTextStyle:  {color: 'black', fontName:'Times New Roman', fontSize: 18},
          hAxis: {title: 'Wind Speed (m/s)', titleTextStyle: {color: 'red',fontSize:18,  fontName:'Times New Roman'}, gridlines:{count:25},minValue:1,viewWindowMode:"explicit", viewWindow: {min:1,max:25}},
          vAxis: {title: 'Power - KW',direction:1, titleTextStyle: {color: 'red', fontSize:18,  fontName:'Times New Roman'},logScale:'false' ,minValue:0,viewWindowMode:"explicit", viewWindow: {min:0}}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  <script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/StackBlur.js"></script>
<script type="text/javascript" src="./js/html2canvas.js"></script>
<script type="text/javascript" src="./js/FileSaver.js"></script>
<script type="text/javascript" src="./js/jspdf.js"></script>
<script type="text/javascript" src="./js/jspdf.plugin.addimage.js"></script> 

    <script type="text/javascript">

function export_PDF(chartContainer,chartContainer1,chartContainer2, imgContainer) {
		var device=document.getElementById('Owner_Name').value+" - "+document.getElementById('Device_Name').value;

         //main Div Hide
              var el = document.getElementById('chart_wind');
             el.parentNode.removeChild( el );  
 		var el1 = document.getElementById('chart_kwh');
             el1.parentNode.removeChild( el1 );  
		var el2 = document.getElementById('chart_div');
             el2.parentNode.removeChild( el2 ); 
        //Chart to Image
//document.getElementById('output').innerHTML = el;
          var doc = chartContainer.ownerDocument;
          var img = doc.createElement('img');
          img.src = getImgData(chartContainer);
 imgContainer.appendChild(img);

 /*  while (imgContainer.firstChild) {
               imgContainer.removeChild(imgContainer.firstChild);
           }*/
 var doc1 = chartContainer1.ownerDocument;
          var img1 = doc1.createElement('img');
          img1.src = getImgData(chartContainer1);
 imgContainer.appendChild(img1);

var doc2 = chartContainer2.ownerDocument;
          var img2 = doc2.createElement('img');
          img2.src = getImgData(chartContainer2);
 imgContainer.appendChild(img2);
       

            

             /*      var divElements = document.getElementById('expotPdfDiv').innerHTML;
                                         //Get the HTML of whole page
                                         var oldPage = document.body.innerHTML;

                                         //Reset the page's HTML with div's HTML only
                                         document.body.innerHTML =
                                           "<html><head><title></title></head><body>" +
                                           divElements + "</body>";*/

//convert whole html page to canvas
 
                             html2canvas(document.body, {
                                     onrendered: function(canvas) {
                                       
  // canvas is the final rendered <canvas> element
                                        
 var myImage = canvas.toDataURL("image/JPEG").slice('data:image/jpeg;base64,'.length);

 // Convert the data to binary form
                                         myImage = atob(myImage)

//new object of jspdf and save image to pdf.

                                         var doc = new jsPDF("portrait", "mm", "a4");
										 // Optional - set properties on the document
										 doc.setProperties({
										 	title: 'Power and Windspeed graph',
										 	subject: 'Power and windspeed curves',		
										 	author: '',
										 	keywords: '',
										 	creator: 'Versatilescada'
										 });
										 doc.setFontSize(30);
										 doc.setFont("helvetica");
										 doc.setFontType("bold");
										 doc.text(20, 20,device);
										 doc.setFontSize(20);
										 doc.setFont("helvetica");
										 doc.setFontType("normal");
										 doc.text(20, 30,"Power and Windspeed Graph" );
                                         doc.addImage(myImage, 'JPEG', 20, 40, 200, 180);
                                         doc.save('Windspeed_graph.pdf');
                                       

                                     }
                                 });


}

function getImgData(chartContainer)
  {


     var chartArea = chartContainer.getElementsByTagName('svg')[0].parentNode;
     var svg = chartArea.innerHTML;
     var doc = chartContainer.ownerDocument;
     var canvas = doc.createElement('canvas');
     canvas.setAttribute('width', chartArea.offsetWidth);
     canvas.setAttribute('height', chartArea.offsetHeight);


     canvas.setAttribute(
         'style',
         'position: absolute; ' +
         'top: ' + (-chartArea.offsetHeight * 2) + 'px;' +
         'left: ' + (-chartArea.offsetWidth * 2) + 'px;');
     doc.body.appendChild(canvas);
     canvg(canvas, svg);

     var imgData = canvas.toDataURL("image/JPEG");
    var data = canvas.toDataURL('image/JPEG').slice('data:image/JPEG;base64,'.length);
             
// Convert the data to binary form
             data = atob(data)


     canvas.parentNode.removeChild(canvas);



     return imgData;
  }

</script>
 

 </head>
<body>
<div id="body" class="container" style="width:1000px;">
    <div class="box">
      <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>
      <div class="content">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <td  width="50%">
          <h2>Energy from versatilescada Detailed Information!</h2>
          <p>about Status, Temperatures, Electrical, Production Figures</p>
      </td>
	  <td width="30%"><h2><?=$Device_Name?></h2></td>

     <td  width="20%" align="right"><a href="<?=  $Filename  ?>"><img src="images/back_btn.png" height="40px" width="40px" /></a></td>
      </table>

	  <div id="expotPdfDiv" >
			<div id="output">


						<table border="0" cellpadding="0" cellspacing="0" width="100%" hieght="auto">
							<tr>
								<td align=right>
									<input type=hidden name="Device_Name" id="Device_Name" value="<?= $Device_Name?>"><input type=hidden name="Owner_Name" id="Owner_Name" value="<?= $Firstname.' '.$Lastname?>"><button Onclick=" export_PDF(document.getElementById('chart_div'),document.getElementById('chart_kwh'),document.getElementById('chart_wind'), document.getElementById('chart_image2')); "> Download PDF</button>
								</td>
							<tr>
								<td>
									<?php
									#If Record Is there
									if(count($PCWP_Chart_Arr) > 0){
									?>
									<div id="chart_div" style="width: 950px; height: 900px;"></div>
									<?php
									}
									else{
									echo "<br /><br /><br /><br /><br /><br /><br /><br /><center><h3>Records Not Found...</h3></center><br /><br /><br /><br /><br /><br />";
									}
									?> <div id="chart_image2"></div>
								</td>
							</tr>

						</table>         

		  </div>
          

        </div>
      
	  </div>
    </div>
   </div>

<?php
	include("footer.php");
?>
</body>
</html>