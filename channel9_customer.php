
<?php
	include("header_inner.php");
	error_reporting(0);
	if(empty($_COOKIE[$Cook_Name])){
		header("Location: index.php");
		exit;
	}
$Mysql_Query = "select * from va_master.device_register where Device_Name='Aspire'";
	if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }
	$Mysql_Record_Count=$Mysql_Query_Result->num_rows;
            if($Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	  
					$IMEI = $Fetch_Result['IMEI'];
					$Format = $Fetch_Result['Format_Type'];
					if($Format == 1){
						$Table_Name = "device_data"; 
					} elseif($Format == 2){
						$Table_Name = "device_data_f2"; 
					} elseif($Format == 3){
						$Table_Name = "device_data_f3"; 
					} elseif($Format == 4){
						$Table_Name = "device_data_f4"; 
					} elseif($Format == 6){
						$Table_Name = "device_data_f6"; 
					} elseif($Format == 7){
						$Table_Name = "device_data_f7"; 
					} elseif($Format == 8){
						$Table_Name = "device_data_f8"; 
					} elseif($Format == 10){
						$Table_Name = "device_data_f10"; 
					}
				}
			}	
function getbgc($trcount)
{

$blue="\"background-color: #EEFAF6;\"";
$green="\"background-color: #D4F7EB;\"";
$odd=$trcount%2;
    if($odd==1){return $blue;}
    else{return $green;}   
} 

																			
	$Date_F = array();
	$Time_F = array();
	$Temp1 = array();
	$Temp2 = array();
	$Temp3 = array();
        $Temp4 = array();
	$Temp5 = array();
	$Temp6 = array();												
	$Temp7 = array();
	$Temp8 = array();
	$FM1 = array();
	$FM2 = array();
        $TankPressure = array();
	$Solenoid1 = array();
	$Solenoid2 = array();
	$Solenoid3 = array();
	$Solenoid4 = array();
	$Pump1 = array();
	$Pump2 = array();
	$Pump3 = array();
        $Input1 = array();
	$Input2= array();
	$Input3 = array();												
	$Input4 = array();
	$Input5 = array();
	$TotalEnergy = array();
	$ConsumedEnergy = array();
?>
	<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
<script>   
$(document).ready(
            function() {
                setInterval(function() {
                          $('#getdata').load('channel9_customer.php #getdata');
                }, 120000);
            });
</script>
<script>   
$(document).ready(
           function() {
                setInterval(function() {
                          $('#energy').load('channel9_customer.php #energy');
                }, 1800000);
            });
</script>


											
	       <center>
 <div id="body" class="clear" style="width:1150px;">
     <div class="box">
<form name="channel1_versatile" method="post" action="">
           
			<table border="0" cellpadding="8" cellspacing="2" width="510px">
            	<tr>
                	<td><h2>Energy from VersatileScada! &nbsp;&nbsp;</h2></td>

          </tr>
		
                  </table>   

<div id="getdata" >

            <table border="5px solid black" align="center" cellpadding="8" cellspacing="2" width="800px" style="background-color:white;">

<tr>
								<td></td>
								<td></td>
								<th colspan="3" align="center">Temperature</th>
								<td></td>
								
								</tr>
<tr>
                         <td align="center" colspan="33" height="10px"><hr size="1"></td>
                    </tr>
		<tr>
								<td align="center">Date</td>
								<td align="center">Time</td>
								<td align="center">Buffer Tank </td>
								<td align="center">Casing WM</td>
								<td align="center">Kitting WM</td>
								<td align="center">Buffer Tank Level</td>															
								</tr>
<tr>
                         <td align="center" colspan="33" height="10px"><hr size="1"></td>
                    </tr>

<tr align="center">
								<td></td>
								<td></td>
								<td>&deg;C</td>
								<td>&deg;C</td>
								<td>&deg;C</td>
								<td>%</td>
				</tr>
<tr>
                         <td align="center" colspan="33" height="10px" ><hr size="1" ></td>
                    </tr>
							
<?php
$rowColors = Array('#94b8b8','#ffffff'); 
$i= 0;
$Mysql_Query1 ="select * from va_aspire.$Table_Name where Date_S=curdate() and IMEI='".$IMEI."' order by Record_Index desc limit 10";	
	if (!$Mysql_Query_Result1 = $db->query($Mysql_Query1))
            {
                die($db->error);
            }
$Mysql_Record_Count=$Mysql_Query_Result1->num_rows;
            if($Mysql_Query_Result1->num_rows >= 1)
            {
                while($Fetch_Result1 = $Mysql_Query_Result1->fetch_array()) {											
			$Date_S = $Fetch_Result1['Date_S'];
			$Time_S = $Fetch_Result1['Time_S'];
			$Temp1 = $Fetch_Result1['Power'];
			$Temp2 = $Fetch_Result1['Windspeed'];
			$Temp3 = $Fetch_Result1['GRPM'];
			$Temp4 = $Fetch_Result1['RRPM'];
			$Temp5 = $Fetch_Result1['Nacel_Temp'];
			$Temp6 = $Fetch_Result1['Gen1_Temp'];
			$Temp7 = $Fetch_Result1['Gen2_Temp'];
			$Temp8 = $Fetch_Result1['Gen_Bear1_Temp'];
			$Total_Energy = $Fetch_Result1['Twist_Pulse'];
			//$Consumed_Energy = $Fetch_Result1['Status'];
			$Tankpressure = $Fetch_Result1['RPhase_Volt'];
echo '<tr style="background-color:'.$rowColors[$i++ % count($rowColors)].';" >';
	
echo '<td width="80px" align="center">'.$Date_S.'</td>';

 echo '<td align="center">'.$Time_S.'</td>';
?>
<td align="center"><?=$Temp8?></td>
<td align="center"><?=$Temp3?></td>
<td align="center"><?=$Temp7?></td>
<td align="center"><?=$Tankpressure?></td>
</tr>
<?php
$MI++;
} 
}

?>


		</table>

</div>
<tr height="2px"><td>&nbsp;</td></tr>
<div id="energy">
<table border="1" cellpadding="0" cellspacing="0" width="50%" align="center" >

<tr>
                             <td class="tab-head-frame" width="150px" align="left"><b>Date</b></td> 
                             <td class="tab-head-frame" width="200px" align="left"><b>Consumed Energy</b></td>
			     <td class="tab-head-frame" width="200px" align="left"><b>Saved Energy</b></td>
			    <td class="tab-head-frame" width="200px" align="left"><b>Heat Delivered</b></td>
                    </tr>
<?php
		$DGR_End = strtotime("today");
		$DGR_Start = strtotime("-1 week +1 day",$DGR_End);
			$DGR_Start_Date = date("Y-m-d",$DGR_Start); 
			$DGR_End_Date = date("Y-m-d",$DGR_End);  	
	//echo $DGR_Start_Date;
	//echo $DGR_End_Date;				
			$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
						foreach($Date_Array as $DATE_Val){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
			//echo $Date_Stamp;
$Pump_Query="select Date_S,PAT_Gen1,count(PAT_Gen1) as Minutes from va_aspire.$Table_Name where  Date_S = '".$Date_Stamp."' and IMEI='".$IMEI."' and PAT_Gen1='1'";
		if (!$Pump_Query_Result = $db->query($Pump_Query))
            {
                die($db->error);
            }
//$Mysql_Record_Count=$Pump_Query_Result->num_rows;
            if($Pump_Query_Result->num_rows >= 1)
            {
                while($Pump_Fetch_Result = $Pump_Query_Result->fetch_array()) {	
				$Date_S[$DATE_Val]=$Pump_Fetch_Result['Date_S'];
				$Hours[$DATE_Val]=round(($Pump_Fetch_Result['Minutes']/60),2);
				$Constant1[$DATE_Val]=round(($Hours[$DATE_Val]*26.3787069),2);
				$Constant2[$DATE_Val]=round(($Constant1[$DATE_Val]/0.95),2);				
						}
					}
 
$Raw_Data_Query="select Date_S,max(Twist_Pulse)-min(Twist_Pulse) as Consumed_Energy from va_aspire.$Table_Name where Date_S = '".$Date_Stamp."' and IMEI='".$IMEI."'";if (!$Raw_Data_Query_Result = $db->query($Raw_Data_Query))
            {
                die($db->error);
            }
$Mysql_Record_Count=$Raw_Data_Query_Result->num_rows;
            if($Raw_Data_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Raw_Data_Query_Result->fetch_array()) {	
				$Consumed_Energy_Max=$Fetch_Result['Max'];
			}
			}
				//$Date_S[$DATE_Val]=$Fetch_Result['Date_S'];
				if($Date_Stamp=="2017-09-18") {
				 $Consumed_Energy[$DATE_Val]="214.3";
				} else {
				$Consumed_Energy[$DATE_Val]=round($Fetch_Result['Consumed_Energy'],2);
				}
			
	?>
			<tr>								
                                    <td class="tab-head-td1" align="left"><?=$DATE_Val!=''?$DATE_Val:'0'?></td> 
                                    <td class="tab-head-td1" align="left"><?=$Consumed_Energy[$DATE_Val]!='' && $Consumed_Energy[$DATE_Val] >='0'?$Consumed_Energy[$DATE_Val]:'0'?> Kwh</td>                 
                                    <td class="tab-head-td1" align="left"><?=($Constant2[$DATE_Val]-$Consumed_Energy[$DATE_Val]) >='0'?$Constant2[$DATE_Val]-$Consumed_Energy[$DATE_Val]:'NIL'?> Kwh </td>  
				<td class="tab-head-td1" align="left"><?=$Constant2[$DATE_Val] !=' ' ?$Constant2[$DATE_Val]:'0'?> Kwh</td>                
				    
								</tr>
			<?php
			}				
						
                    ?>
</table>
</div>
<tr height="2px"><td>&nbsp;</td></tr>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <td  width="100%"  align="center" colspan="4">
					 <iframe  src="channel9_ajax.php?c1=<?=$IMEI?>&FType=<?=$Format?>" rows="40" cols="60" style="border:solid 1px #168A83; width:1100px; height:350px;"></iframe>
                            </td>
</table>

	

		 			</form>

</div>
</div>
</center>

<?php


	//include("footer.php");
?>