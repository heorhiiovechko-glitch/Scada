<?php

	include("header_inner.php");
	if(empty($_COOKIE[$Cook_Name])){
		header('Location: index.php');
		exit;
	}
?>
<?php
	error_reporting(0);
//echo $_REQUEST['FType'];
	$From_D_Epoch = $_REQUEST['From'];
	//echo $_REQUEST['From'];
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

if(isset($_REQUEST['c1'])){	
		$To_D_Epoch = $_REQUEST['To'];
		$IMEI = base64_decode($_REQUEST['c1']);
		$PCWP_Chart_Arr1 = array();
		
		#	Getting IMEI 
		$Mysql_Query = "select * from device_register where IMEI = '$IMEI'";
		if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
				  $Power_Curve_Array[$Fetch_Result['IMEI']] =  $Fetch_Result['Power_Curve'];
				$Format_Type= $Fetch_Result['Format_Type'];
			}//end while
		}//endif
		//echo $Mysql_Query;

		# Assign Power Curve Array Related to IMEI
		if(isset($IMEI)){
			$PCWP_Chart_Arr = array();
			if($Power_Curve_Array[$IMEI] == 750){
				$PCWP_Chart_Arr = $PCWP_Chart_Arr_750;
				$PCWP_Chart_Val_Arr = $PCWP_Chart_Val_Arr_750;
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
/*		$NG1 = $Extract_Date." 00:00:00";
		$NG2 = $Extract_Date." 04:59:00";
		$NG3 = $Extract_Date." 22:00:00";
		$NG4 = $Extract_Date." 23:59:00";
		$All_Hours_0to4_59_1 = strtotime($NG1)+(60*60*5.5);
		$All_Hours_0to4_59_2 =strtotime($NG2)+(60*60*5.5);
		$All_Hours_22to23_59_1 = strtotime($NG3)+(60*60*5.5);
		$All_Hours_22to23_59_2 = strtotime($NG4)+(60*60*5.5);*/


if($Format_Type==4)

$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(PAT_Gen1) as Gen1_Max,MIN(PAT_Gen1) as Gen1_Min,MAX(PAT_Gen2) as Gen2_Max,MIN(PAT_Gen2) as Gen2_Min,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed from $Cook_Variable[7].device_data_f4 where IMEI = '".$IMEI."' and Date_S='".$Extract_Date."' group by HOUR(Time_S)";


elseif($Format_Type==2)
$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(PAT_Gen1) as Gen1_Max,MIN(PAT_Gen1) as Gen1_Min,MAX(PAT_Gen2) as Gen2_Max,MIN(PAT_Gen2) as Gen2_Min,round(AVG(GREATEST(Power,0)),2)   as Power ,ROUND(AVG(Windspeed),2) as WindSpeed from $Cook_Variable[7].device_data_f2 where IMEI = '".$IMEI."' and Date_S='".$Extract_Date."'  and PAT_Gen1 between 1 and 1000000 and PAT_Gen2 between 1 and 1000000 group by HOUR(Time_S)";

elseif($Format_Type==7)
$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(Active_Total_Gen_Export,0)) as Gen1_Max,MIN(GREATEST(Active_Total_Gen_Export,0)) as Gen1_Min,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed from $Cook_Variable[7].device_data_f7 where IMEI = '".$IMEI."' and Date_S='".$Extract_Date."'   group by HOUR(Time_S)";
elseif($Format_Type==8) {
if ($Username=='spectrum') {
$Mysql_Query="select HOUR(Time) as Hour ,MAX(GREATEST(Active_Total_Gen_Export,0)) as Gen1_Max,MIN(GREATEST(Active_Total_Gen_Export,0)) as Gen1_Min,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed from $Cook_Variable[7].device_data_f8 where IMEI = '".$IMEI."' and Date='".$Extract_Date."'   group by HOUR(Time)";
} else {
$Mysql_Query="select HOUR(Time_S) as Hour ,MAX(GREATEST(Active_Total_Gen_Export,0)) as Gen1_Max,MIN(GREATEST(Active_Total_Gen_Export,0)) as Gen1_Min,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed from $Cook_Variable[7].device_data_f8 where IMEI = '".$IMEI."' and Date_S='".$Extract_Date."'   group by HOUR(Time_S)";
}
}
if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
				$MI = 1;
              while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {			
			
				$Each_Hours_WindSpeedData_Arr_Avg[$Fetch_Result['Hour']]=$Fetch_Result['WindSpeed'];
				
				$Each_Hours_PowerData_Arr_Avg[$Fetch_Result['Hour']]=$Fetch_Result['Power'];
				if($Format_Type==4){
				$Each_Hours_Gen1_Min_Arr[$Fetch_Result['Hour']]=$Fetch_Result['Gen1_Min'];
				
				$Each_Hours_Gen2_Min_Arr[$Fetch_Result['Hour']]=$Fetch_Result['Gen2_Min'];
				
				$Each_Hours_Gen1_Max_Arr_copy[$Fetch_Result['Hour']]=$Each_Hours_Gen1_Max_Arr[$Fetch_Result['Hour']]=$Fetch_Result['Gen1_Max'];

				$Each_Hours_Gen2_Max_Arr_copy[$Fetch_Result['Hour']]=$Each_Hours_Gen2_Max_Arr[$Fetch_Result['Hour']]=$Fetch_Result['Gen2_Max'];
				}elseif($Format_Type==7 || $Format_Type==8){
				$Each_Hours_Gen1_Min_Arr[$Fetch_Result['Hour']]=$Fetch_Result['Gen1_Min'];
				

				
				$Each_Hours_Gen1_Max_Arr_copy[$Fetch_Result['Hour']]=$Each_Hours_Gen1_Max_Arr[$Fetch_Result['Hour']]=$Fetch_Result['Gen1_Max'];

				}else{
				echo "No Records";
				}

				//$DET = $Fetch_Result['Device_Epoch_Time'];

				//echo $DET;
				#	Seperating One Hours values for each
				//print_r($Hours_Arr);
			

			}//end while
		}//end if
		//print_r($Each_Hours_PAT_GEN2_Max_Arr);
		//print_r($Each_Hours_Gen1_Max_Arr);
		//print_r($Each_Hours_Gen2_Max_Arr);
		$arrayiteratorcount=0;
		if($Format_Type==4 || $Format_Type==7 || $Format_Type==8){
			
			$Gen1_Min_Value=0;
			$Gen2_Min_value=0;
			foreach($Each_Hours_Gen1_Max_Arr as $Gen1_Max_Key => $Gen1_Max_Value){
		
				if($arrayiteratorcount==0)
					$Each_Hours_Gen1_Arr[$Gen1_Max_Key]=$Gen1_Max_Value-$Each_Hours_Gen1_Min_Arr[$Gen1_Max_Key];
				else
					$Each_Hours_Gen1_Arr[$Gen1_Max_Key]=$Gen1_Max_Value-$Gen1_Min_Value;
				$Gen1_Min_Value=$Gen1_Max_Value;
				$arrayiteratorcount++;
			}//end foreach
			$arrayiteratorcount=0;
			if($Format_Type==4){
			foreach($Each_Hours_Gen2_Max_Arr as $Gen2_Max_Key => $Gen2_Max_Value){
		
				if($arrayiteratorcount==0)
					$Each_Hours_Gen2_Arr[$Gen2_Max_Key]=$Gen2_Max_Value-$Each_Hours_Gen2_Min_Arr[$Gen2_Max_Key];
				else
					$Each_Hours_Gen2_Arr[$Gen2_Max_Key]=$Gen2_Max_Value-$Gen2_Min_Value;
					$Gen2_Min_Value=$Gen2_Max_Value;
				$arrayiteratorcount++;
			}//endforeach
					
			$Each_Hours_Generation_Arr = array();
			for($i=0;$i<count($Each_Hours_Gen1_Arr);$i++) {
			 $Each_Hours_Generation_Arr[$i] = round($Each_Hours_Gen1_Arr[$i]+$Each_Hours_Gen2_Arr[$i]);
			}
			}//end if format type 4
			elseif( $Format_Type==7 || $Format_Type==8){
			$Each_Hours_Generation_Arr=$Each_Hours_Gen1_Arr;
			}
		}
		else{echo "no records";}
	//	print_r($Each_Hours_Gen1_Arr);
	//	print_r($Each_Hours_Gen2_Arr);

		//print_r($Each_Hours_Generation_Arr);
		$MaxValKey=array_search(max($Each_Hours_Generation_Arr),$Each_Hours_Generation_Arr);

		if($Each_Hours_Generation_Arr[$MaxValKey]>4000 && $Each_Hours_Generation_Arr[$MaxValKey]<0 && $Format_Type==4)
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
		$PCWP_Chart_Arr1 = array_combine($Each_Hours_WindSpeedData_Arr_Avg,$Each_Hours_PowerData_Arr_Avg);
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
$Each_Hours_Generation_Arr_Sum=array_sum($Each_Hours_Generation_Arr);
		//echo $Each_Hours_Generation_Arr_Sum."<hr>";

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
        chart.draw(view, options);
      }
    </script>



    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart); 
var x=$Each_Hours_GKwh_Arr.length 

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Time', 'Generation kwh'],
		  <?php
			$C1 = 0;
			
			$Cur_Count1 = count($Each_Hours_Generation_Arr);
			foreach($Each_Hours_Generation_Arr as $EHGA_Key1 => $EHGA_Val1){
				
				//$temp=(int)$temp; 
		  ?>
				[ parseInt(<?= $EHGA_Key1 ?>+1) ,  parseInt(<?= $EHGA_Val1 ?>) ] <?=($C1 == ($Cur_Count1-1)?'' : ',')?>
		  <?php
				$C1++;
			}
		  ?>


        ]);

        var options = {curveType: "function",width: 900, height: 400,
       			 pointSize: 4,focusTarget: 'datum', colors: ['#007111'],
          		 title: 'Generation Graph Dated on  <?=date("d-m-Y",strtotime($Extract_Date)) ?>-------------Export - <?= $Each_Hours_Generation_Arr_Sum?>kwh',
         		 titleTextStyle:  {color: 'black', fontName:'Times New Roman', fontSize: 18},
          	 	  legend: 'none',
			 backgroundColor:{stroke:'#999',strokeWidth:3},
          		 hAxis: {title: 'Time  (Hrs)', titleTextStyle: {color: 'red'}, maxValue:24, minValue:1  ,titleTextStyle: {color: 'red',fontSize:18,  fontName:'Times New Roman'}, gridlines:{count:24}, viewWindowMode:"explicit", viewWindow: {min:1,max:24}},
          		 vAxis: {title: 'Generation (kwh)', titleTextStyle: {color: 'red'},logScale:'false',   titleTextStyle: {color: 'red', fontSize:18,  fontName:'Times New Roman'},minValue:0,viewWindowMode:"explicit", viewWindow: {min:0}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_kwh'));
        chart.draw(data, options);
      }
    </script>


 <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Wind Speed', 'Reference Curve', 'Measured Curve'],
		  <?php
			$C = 0;
			$Cur_Count = count($PCWP_Chart_Arr_Filter);

			$currentval=0;
			foreach($PCWP_Chart_Arr1_Filter as $PCA1F_Key => $PCA1F_Val){
			
				if(($PCWP_Chart_Arr1_Filter[$PCA1F_Key][1])==0){
				//echo current(current($PCWP_Chart_Arr1_Filter));
				//echo "before next". $PCA1F_Key."<br>";
				if(!(is_null($PCWP_Chart_Arr1_Filter))){
				$currentval=next(next($PCWP_Chart_Arr1_Filter));//echo $PCA1F_Key;
}
				if($currentval!=0 && $firstvalcount==0){
				$zerovalind= $PCA1F_Key;
				// echo "after next". $PCA1F_Key."<br>";
				$firstvalcount++;
				}				
				//echo key($PCWP_Chart_Arr1_Filter))."<br>";
				//$zerovalind= $PCA1F_Key;
				
				}
				if( $PCWP_Chart_Arr1_Filter[$PCA1F_Key][1]!=0 ){
					foreach( $PCA1F_Val as $wind => $power){
						
						$lastvalind= $PCA1F_Key;
					}
				}
				
			}
			$firstvalind=$zerovalind+1;

			

			foreach($PCWP_Chart_Arr_Filter as $PCAF_Key => $PCAF_Val){
				if($PCAF_Key<=$zerovalind){$temp=0;
				}
				if($PCAF_Key<=$lastvalind   &&  $PCAF_Key>$zerovalind){
					if($PCWP_Chart_Arr1_Filter[$PCAF_Key][1]==0)
					continue;
					else
					 $temp=$PCWP_Chart_Arr1_Filter[$PCAF_Key][1]; //echo "inside curve $temp and key is $PCAF_Key<br>";
				}
				if($PCAF_Key>$lastvalind)
 				$temp=null;
			
				//echo "$PCAF_Key is $temp <br>";
			


		  ?>
				[<?=$PCAF_Key?>,  parseInt(<?= $PCAF_Val ?>), parseInt(<?= $temp ?>)]<?=($C == ($Cur_Count-1)?'' : ',')?>
		  <?php
				$C++;
			}  
		  ?>
		  <?php
			/*foreach($PCWP_Chart_Arr as $PCAF_Key => $PCAF_Val){
		  ?>
				['<?=$PCAF_Key?>',  <?=$PCAF_Val?>,  <?=$PCAF_Val?>],
		  <?php
			}*/
		  ?>
        ]);
	
	
        var options = {curveType: "function",width: 900, height: 400,
        pointSize: 4,focusTarget: 'datum',
          title: ' Power curve Day Detail Dated on <?=date("d-m-Y",strtotime($Extract_Date))?>', 
	 backgroundColor:{stroke:'#999',strokeWidth:3},
	  titleTextStyle:  {color: 'black', fontName:'Times New Roman', fontSize: 18},
          hAxis: {title: 'Wind Speed (m/s)', titleTextStyle: {color: 'red',fontSize:18,  fontName:'Times New Roman'}, gridlines:{count:25},minValue:1,viewWindowMode:"explicit", viewWindow: {min:1,max:25}},
          vAxis: {title: 'Power - KW',direction:1, titleTextStyle: {color: 'red', fontSize:18,  fontName:'Times New Roman'},logScale:'false' ,minValue:0,viewWindowMode:"explicit", viewWindow: {min:0}}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

  </head>
  <center>
	  <div id="body" class="clear" style="width:1000px;">
    <div class="box">
      <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>
      <div class="content">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <td  width="50%">
          <h2>Energy from versatilescada Detailed Information!</h2>
          <p>about Status, Temperatures, Electrical, Production Figures</p>
      </td>
<?php
if($Format_Type==1)
$Filename="channel1.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==2)
$Filename="channel3.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
elseif($Format_Type==7 || $Format_Type==8)
$Filename="channel8.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;
else
$Filename="channel5.php?c1=".$_REQUEST['c1']."&l=".$_REQUEST['l']."&FType=".$Format_Type;

?>
     <td  width="50%" align="right"><a href="<?=  $Filename  ?>"><img src="images/back_btn.png" height="40px" width="40px" /></a></td>
      </table> 
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
    		<tr>
	<?php			
		#If Record Is there
		//if(count($PCWP_Chart_Arr) > 0){
	?>			
		<div id="chart_wind" style="width:600px; height: 400px;"></div>
	  <?php    /*
		}
		else{
			echo "<br /><br /><br /><br /><br /><br /><br /><br /><center><h3>Records Not Found...</h3></center><br /><br /><br /><br /><br /><br />";
		}*/
	  ?>
               </tr>


		<tr>
	<?php			
		#If Record Is there
		if(count($PCWP_Chart_Arr) > 0){
	?>			
		<div id="chart_kwh" style="width: 600px; height: 400px;"></div>
	  <?php
		}
		else{
			echo "<br /><br /><br /><br /><br /><br /><br /><br /><center><h3>Records Not Found...</h3></center><br /><br /><br /><br /><br /><br />";
		}
	  ?>
               </tr>

		<tr>
	<?php			
		#If Record Is there
		if(count($PCWP_Chart_Arr) > 0){
	?>			
		<div id="chart_div" style="width: 950px; height: 500px;"></div>
	  <?php
		}
		else{
			echo "<br /><br /><br /><br /><br /><br /><br /><br /><center><h3>Records Not Found...</h3></center><br /><br /><br /><br /><br /><br />";
		}
	  ?>
               </tr>

          </table>          
          
          <div style="width:100%">&nbsp;</div>

          <p class="hr" style="float:left">&nbsp;</p><br />
        </div>
      </div>
    
    </div>
	</center>
<?php
	include("footer.php");
?>