<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    font-size:13px;
    color:#333;
}

.innertab1{
    width:100%;
    border-collapse:collapse;
    margin-top:5px;
}

.tab-head-tr{
    background:#2c3e50;
    color:#fff;
    font-weight:bold;
    font-size:16px;
    text-align:center;
    padding:8px;
}

.tab-head-td{
    background:#34495e;
    color:#fff;
    padding:8px;
    border:1px solid #dcdcdc;
    font-weight:bold;
    text-align:center;
}

.tab-head-td1{
    padding:6px;
    border:1px solid #e1e1e1;
}

.tab-head-td1:nth-child(1),
.tab-head-td1:nth-child(2){
    text-align:left;
}

.tab-head-td1:not(:nth-child(1)):not(:nth-child(2)){
    text-align:right;
}

.innertab1 tr:nth-child(even){
    background:#f8f9fa;
}

.innertab1 tr:hover{
    background:#eef3ff;
}

.download-section{
    background:#f5f7fa;
    padding:10px;
    border:1px solid #ddd;
}

.download-link{
    font-weight:bold;
    color:#007bff;
    text-decoration:none;
}

.download-link:hover{
    text-decoration:underline;
}

.total-row{
    background:#ecf0f1;
    font-weight:bold;
}

</style>

<?php
ini_set('max_execution_time', 3600);


//echo $_REQUEST['FType'] ."is format type";

	if ($XLS == 0){

?>

		<tr>
<td colspan="5" align="left" class="download-section">

<b>Download Excel Report :</b>

<?php if($FType==1 || $FType==6){?>

&nbsp;&nbsp;<a class="download-link" href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1'>Click here</a>

<?php } if($FType==2){?>

&nbsp;&nbsp;<a class="download-link" href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1'>Click here</a>

<?php } if($FType==3){?>

&nbsp;&nbsp;<a class="download-link" href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1'>Click here</a>

<?php } if($FType==4){?>

&nbsp;&nbsp;<a class="download-link" href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1'>Click here</a>

<?php } if($FType==10){?>

&nbsp;&nbsp;<a class="download-link" href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1'>Click here</a>

<?php } if($FType==7 || $FType==8){?>

&nbsp;&nbsp;<a class="download-link" href='channel8_swami_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1'>Click here</a>

<?php } ?>

</td>
</tr>

<?php

	}

?>	
   <tr>

            <td height="5px">&nbsp;</td>

        </tr>

        <tr>

            <td width="100%">

                <table width="100%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">

	<?php

$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI,Db_Name from device_register where IMEI='$IMEI'";
if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }

            if($Device_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Device_Query_Result->fetch_array()) {
				  $DGR_IMEI=$Fetch_Result['IMEI'];

				$Device_Name = $Fetch_Result['Device_Name'];

				$Site_Location = $Fetch_Result['Site_Location'];

				$Format_Type = $Fetch_Result['Format_Type'];
				$Closing_Time = $Fetch_Result['Closing_Time'];	
				$Dbname = $Fetch_Result['Db_Name'];				

			}

		}
//echo $Format_Type;
if ($XLS == 1){//xls=1
	?>
 <tr>
							<td class="tab-head-td" colspan="13"  align="center"><b><? print_r($Cook_Variable[4]) ?>   <?print_r($Cook_Variable[5])?> - Daily Generation Detail Report</b></td>
						</tr>
					   <tr>
							<td class="tab-head-td"  colspan="13"  align="left"><b>Site:</b><?= $Site_Location ?></td>
<tr style="border:0px"><td colspan="6" >&nbsp;</td></tr>
<?php 
		}
			if ($XLS == 0){
					?>
					<tr>
						<td  class="tab-head-tr"  colspan="13" align="left">&nbsp;&nbsp;<b>Daily Generation Detail Report</b></td>
					</tr>
					<?php 
					}
					?>
	<?php
           if(isset($_REQUEST['p']) && $_REQUEST['p'] == 10){//if p is set
		$DGR_Start_Date=$_REQUEST['inputDate'] ;//echo $DGR_Start_Date;
		  $DGR_End_Date=$_REQUEST['inputDate1'];//echo  $DGR_End_Date;
	$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
				
				if($Device_Query_Result->num_rows >= 1){//record count if
		?>
                    <tr height="50px">
			<td class="tab-head-td" align="center" width="16px;"><b>Gen Date</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>WTG Name</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Energy Current Day Kwh</b></td>
			<td class="tab-head-td" align="center" width="16px;"><b>Total Hrs</b></td>  
                 	<td class="tab-head-td" align="center" width="16px;"><b>Run Hrs</b></td>                               
                        <td class="tab-head-td" align="center" width="16px;"><b>Line Ok Hrs</b></td> 
			<td class="tab-head-td" align="center" width="16px;"><b>No Service Hrs</b></td>                                    
                      <!-- <td class="tab-head-td" align="center" width="16px;"><b>Lull Hrs</b></td>   
                        <td class="tab-head-td" align="center" width="16px;"><b>GA %</b></td> 
						 <td class="tab-head-td" align="center" width="16px;"><b>MA %</b></td> -->
						 
                    </tr>
						<?php 
							$MI = 1;
							$Tot_All_Generation=0;
							$Tot_BD_Hours=0;
							$Tot_GD_Hours=0;
							$Tot_Maint_Hours=0;
							$Tot_Lull_Hours=0;
							$Tot_Import_LCS=0;
							$Tot_Run_Hours=0;
							$Days=0; 

$datediff = abs($From_D_Epoch - $To_D_Epoch);
     $diff= floor($datediff/(60*60*24));
$Daydiff=24*($diff+1);
 
							$Date_Array = getAllDatesBetweenTwoDates($DGR_Start_Date, $DGR_End_Date);//print_r($Date_Array);
							foreach($Date_Array as $DATE_Val){
							
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time=="00:00:00" || $Closing_Time=="0" ){
							$Date_St=date("Y-m-d",strtotime($DATE_Val));
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							}
							elseif($Closing_Time>="10:00:00" || $Closing_Time=="10"){
							$Date_St=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
							//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}
							else{
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Date_St=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)+86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)+86400);
							}
							
							//echo $DATE_Val;

							if($Format_Type== 1){
				
				$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
//echo $Gen_Mysql_Query;
					if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
								
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'500'?'24':$Gen1[$DATE_Val];	
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Gen1[$DATE_Val]);
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								//$Loss_GF = $Total_Gen[$DATE_Val]/(
									}//end while

								}

							}//endif isset

				if($Format_Type== 2){

						$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {							 
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen1[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run_Hours[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
								$Total_Gen[$DATE_Val]=$Total_Gen1[$DATE_Val]+$Gen2[$DATE_Val];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=6000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]>'24' && $Run_Hours[$DATE_Val]<'500'?'24':$Run_Hours[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								

	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }

while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {	
	$Error_Type[$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$DATE_Val]=($BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?$BD_Hours[$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$DATE_Val]=($GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=25)?$GD_Hours[$DATE_Val] : '0';
}

	}//ENDWHILE			
}//end while

								}	

							$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Lull_Hours[$DATE_Val]= (24 * 3600) - (($Run_Hours[$DATE_Val]* 3600) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);

								$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';				
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;						
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
								

											}//endif isset

							if($Format_Type== 3){

								

							$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";								//echo $Gen_Mysql_Query;//echo $Gen_Mysql_Query;
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {						
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=18000?$Total_Gen[$DATE_Val]:'0';
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);

							$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]>'24' && $Run_Hours[$DATE_Val]<'500'?'24':$Run_Hours[$DATE_Val];
							$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
							
	$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }

while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {	
	$Error_Type[$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$DATE_Val]=($BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?$BD_Hours[$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$DATE_Val]=($GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=25)?$GD_Hours[$DATE_Val] : '0';
}

	}//ENDWHILE			
$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Lull_Hours[$DATE_Val]= 24 - ($Run_Hours[$DATE_Val] +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);

								//$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');

								if($Lull_Hours[$DATE_Val]==(-1))

								$Lull_Hours[$DATE_Val]=0;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								

								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;

								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];

									}//end while

								}
								}//endif isset

							if($Format_Type== 4){

						$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {							 
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen1[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Gen2[$DATE_Val]=$Fetch_Result['Gen2_Max']-$Fetch_Result['Gen2_Min'];
								$Run_Hours[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
								$Total_Gen[$DATE_Val]=$Total_Gen1[$DATE_Val]+$Gen2[$DATE_Val];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Run_Hours[$DATE_Val]>'24' && $Run_Hours[$DATE_Val]<'50'?'24':$Run_Hours[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								

/*$POC_Mysql_Query = "select IMEI,Date_S,Error_Type,Time_Diff,sum(Time_Diff) as Diff from $Cook_Variable[7].pocket_time_calc where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_St."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_St') then  hour((cast(Time_S as time)))>='$Closing_Time' else hour(cast(Time_S as time))<'$Closing_Time' end) group by Error_Type";
//echo $POC_Mysql_Query;
if (!$POC_Mysql_Query_Result = $db->query($POC_Mysql_Query))
            {
                die($db->error);
            }

while($POC_Fetch_Result = $POC_Mysql_Query_Result->fetch_array()) {	
	$Error_Type[$DATE_Val] = $POC_Fetch_Result['Error_Type'];
					
	# For BD Hours
									
if($Error_Type[$DATE_Val] == 'BD Hours'){
//echo $POC_Fetch_Result['Diff'];
$BD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$BD_Hours[$DATE_Val]=($BD_Hours[$DATE_Val] >=0 && $BD_Hours[$DATE_Val] <=24)?$BD_Hours[$DATE_Val] : '0';
}
	# For GD Hours
else if($Error_Type[$DATE_Val] == 'GD Hours'){
//echo $POC_Fetch_Result['Diff'];
$GD_Hours[$DATE_Val] = round(($POC_Fetch_Result['Diff']/3600),1);
$GD_Hours[$DATE_Val]=($GD_Hours[$DATE_Val] >=0 && $GD_Hours[$DATE_Val] <=25)?$GD_Hours[$DATE_Val] : '0';
}

	}  */ //ENDWHILE			
}//end while

								}	

							$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								$Lull_Hours[$DATE_Val]= (24 * 3600) - (($Run_Hours[$DATE_Val]* 3600) +$BD_Hours[$DATE_Val] + $GD_Hours[$DATE_Val]);

								$Lull_Hours[$DATE_Val] = Sec2Time($Lull_Hours[$DATE_Val],'m');
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';				
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;						
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];

								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val];
								

											}//endif isset

				
							if($Format_Type== 6){

								$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {						
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
								$Gen1[$DATE_Val]=$Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Gen1[$DATE_Val]);
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';																
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
									}//end while

								}


							}//endif isset
						if($Format_Type== 7){


//$Mysql_Query = "select * from va_swami.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' and Date='".date("j-n-y")."' order by Record_Index desc limit 1";echo $Mysql_Query;
$date__stamp=date_create($Date_Stamp);
$DateeOn = date_format($date__stamp,"j-n-y");
//echo $Date_Stamp."@".$DateeOn.";";
/*
$Gen_Mysql_Query="Select IMEI,Date,Windspeed,(Select Min(Active_Total_Gen_Export) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Active_Total_Gen_Export != '') as Gen1_Min,(Select (Active_Total_Gen_Export)*1000 from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Active_Total_Gen_Export != '' order by record_index desc limit 1) as Gen1_Max,(Select Min(Min3_Active_Power) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Min3_Active_Power > '0' and Min3_Active_Power != '') as Run_Min,(Select Max(Min3_Active_Power) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and  (Date_S= '".$Date_Stamp."') and Min3_Active_Power > '0' and Min3_Active_Power != '') as Run_Max,(Select Min(Min3_Wind_Dir) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Min3_Wind_Dir != '') as Gen1H_Min,(Select Max(Min3_Wind_Dir) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Min3_Wind_Dir != '') as Gen1H_Max,(Select Min(Min3_Wind_Speed) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Min3_Wind_Speed != '') as Line_Min,(Select Max(Min3_Wind_Speed) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Min3_Wind_Speed != '') as Line_Max,(Select Min(Stopped_Hours) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Stopped_Hours != '') as Import_Min,(Select Max(Stopped_Hours) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Stopped_Hours != '') as Import_Max,(Select Min(Grid_failure_Hours) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Grid_failure_Hours != '' and Grid_failure_Hours > '0'  ) as Total_hrs_Min,(Select Max(Grid_failure_Hours) from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."') and Grid_failure_Hours != ''  and Grid_failure_Hours > 0) as Total_hrs_Max  from va_swami.device_data_f7 Where IMEI = '".$DGR_IMEI."' and   (Date_S= '".$Date_Stamp."')  order by Date_S desc limit 1";*/

$Gen_Mysql_Query="Select IMEI,Date,Windspeed,

(Select Min(Reactive_Total_Gen_Export) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Reactive_Total_Gen_Export!='') as Gen1_Min,

(Select (Reactive_Total_Gen_Export) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Reactive_Total_Gen_Export!='' 
 order by record_index desc limit 1) as Gen1_Max,

(Select Min(Min3_Active_Power) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Min3_Active_Power>'0' 
 and Min3_Active_Power!='') as Run_Min,

(Select Max(Min3_Active_Power) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Min3_Active_Power>'0' 
 and Min3_Active_Power!='') as Run_Max,

(Select Min(Min3_Wind_Dir) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Min3_Wind_Dir!='') as Gen1H_Min,

(Select Max(Min3_Wind_Dir) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Min3_Wind_Dir!='') as Gen1H_Max,

(Select Min(Min3_Wind_Speed) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Min3_Wind_Speed!='') as Line_Min,

(Select Max(Min3_Wind_Speed) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Min3_Wind_Speed!='') as Line_Max,

(Select Min(Stopped_Hours) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Stopped_Hours!='') as Import_Min,

(Select Max(Stopped_Hours) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Stopped_Hours!='') as Import_Max,

(Select Min(Grid_failure_Hours) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Grid_failure_Hours!='' 
 and Grid_failure_Hours>'0') as Total_hrs_Min,

(Select Max(Grid_failure_Hours) 
 from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' 
 and Date_S='".$Date_Stamp."' 
 and Grid_failure_Hours!='' 
 and Grid_failure_Hours>0) as Total_hrs_Max

from va_swami.device_data_f7

Where IMEI='".$DGR_IMEI."' 
and Date_S='".$Date_Stamp."'  

order by Date_S desc 
limit 1";





/*$Gen_Mysql_Query="Select IMEI,Date,Windspeed,
(Select Min(Reactive_Total_Gen_Export) from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' and Date_S='".$Date_Stamp."' 
 and Reactive_Total_Gen_Export!='') as Gen1_Min,

(Select Max(Reactive_Total_Gen_Export) from va_swami.device_data_f7 
 Where IMEI='".$DGR_IMEI."' and Date_S='".$Date_Stamp."' 
 and Reactive_Total_Gen_Export!='' 
 order by record_index desc limit 1) as Gen1_Max

from va_swami.device_data_f7 
Where IMEI='".$DGR_IMEI."' 
and Date_S='".$Date_Stamp."'  
order by Date_S desc limit 1";

$result = $db->query($Gen_Mysql_Query);

if($result && $result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    $Gen1_Min = $row['Gen1_Min'];
    $Gen1_Max = $row['Gen1_Max'];
	
	
    echo $row['Gen1_Min']."&nbsp;&nbsp;&nbsp;&nbsp;".$row['Gen1_Max']."<br>";
    
	$Generation = ($Gen1_Max - $Gen1_Min)*1000;
//echo "Generation : ".$Generation." kWh ";
}*/


								//$Gen_Mysql_Query="select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
								
								//echo $Gen_Mysql_Query;
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }
//echo $Gen_Mysql_Query;
            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
               /* while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=50?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']*1000;
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=150000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$BD_Hours[$DATE_Val]=$Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$Line_Hours[$DATE_Val] =$Fetch_Result['Line_Max']-$Fetch_Result['Line_Min'];
								//$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								//$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$Array_Line[$DATE_Val]=$Line_Hours[$DATE_Val]>0 && $Line_Hours[$DATE_Val]<=25?$Line_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Lull_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$BD_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';
								 							
								 }*/
				while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
						
							$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max'] - $Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
									//echo $Array_Import[$DATE_Val];
								$Total_Gen[$DATE_Val]=($Fetch_Result['Gen1_Max']- $Fetch_Result['Gen1_Min'])*1000;
								if ($Total_Gen[$DATE_Val]>=18000)
									$Total_Gen[$DATE_Val] =0;
									
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=20000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run_Max'] - $Fetch_Result['Run_Min'];
								$Total_hrs[$DATE_Val]=$Fetch_Result['Total_hrs_Max'] - $Fetch_Result['Total_hrs_Min'];
								$Line_Hours[$DATE_Val]=$Fetch_Result['Line_Max'] - $Fetch_Result['Line_Min'];
								//echo $Total_hrs[$DATE_Val]."@".$Run_Hours[$DATE_Val].";";
								$BD_Hours[$DATE_Val]=$Fetch_Result['Gen1H_Max'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$Array_Line[$DATE_Val]=$Line_Hours[$DATE_Val]>0 && $Line_Hours[$DATE_Val]<=25?$Line_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Lull_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$BD_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								 }		 
								}
									
							}//endif isset

							if($Format_Type== 8){

					$Gen_Mysql_Query="select IMEI,Date_S,Windspeed,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Windspeed[$DATE_Val]=$Fetch_Result['WindSpeed'];
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=15000?$Total_Gen[$DATE_Val]:'0';
								$Run_Hours[$DATE_Val]=$Fetch_Result['Run_Max'];
								$BD_Hours[$DATE_Val]=$Fetch_Result['Gen1H_Max'];
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$Lull_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$BD_Hours[$DATE_Val]+$Run_Hours[$DATE_Val]);
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';								
								 }
								}
									
							}//endif isset		

							if($Format_Type== 10){
									$Gen_Mysql_Query="select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI = ".$DGR_IMEI."  and (Date_S= '".$Date_Stamp."')";
if (!$Gen_Mysql_Query_Result = $db->query($Gen_Mysql_Query))
            {
                die($db->error);
            }

            if($Gen_Mysql_Query_Result->num_rows >= 1)
            {
				
                while($Fetch_Result = $Gen_Mysql_Query_Result->fetch_array()) {
								$Import_LCS[$DATE_Val]=$Fetch_Result['Import_Max']-$Fetch_Result['Import_Min'];
								$Array_Import[$DATE_Val]=$Import_LCS[$DATE_Val]>0 && $Import_LCS[$DATE_Val]<=500?$Import_LCS[$DATE_Val]:'0';
							
								$Total_Gen[$DATE_Val]=$Fetch_Result['Gen1_Max']-$Fetch_Result['Gen1_Min'];
								$Array_Gen[$DATE_Val]=$Total_Gen[$DATE_Val]>0 && $Total_Gen[$DATE_Val]<=6000?$Total_Gen[$DATE_Val]:'0';
								$Run[$DATE_Val]=$Fetch_Result['Run_Max']-$Fetch_Result['Run_Min'];
								$Run[$DATE_Val]=$Run[$DATE_Val]>'24' && $Run[$DATE_Val]<'500'?'24':$Run[$DATE_Val];
								$Gen1[$DATE_Val]=($Fetch_Result['Gen1H_Max']-$Fetch_Result['Gen1H_Min'])+($Fetch_Result['Gen2H_Max']-$Fetch_Result['Gen2H_Min']);
								$Gen1[$DATE_Val]=$Gen1[$DATE_Val]>'24' && $Gen1[$DATE_Val]<'50'?'24':$Gen1[$DATE_Val];	
								$Lull_Hours[$DATE_Val]=$Run[$DATE_Val]-$Gen1[$DATE_Val];
								if($Lull_Hours[$DATE_Val]==(-1))
								$Lull_Hours[$DATE_Val]=0;
								$Run_Hours[$DATE_Val]=$Gen1[$DATE_Val];
								$Array_Run[$DATE_Val]=$Run_Hours[$DATE_Val]>0 && $Run_Hours[$DATE_Val]<=25?$Run_Hours[$DATE_Val]:'0';
								$GD_Hours[$DATE_Val] = 24-($Fetch_Result['Line_Max']-$Fetch_Result['Line_Min']);
								$Array_GD[$DATE_Val]=$GD_Hours[$DATE_Val]>0 && $GD_Hours[$DATE_Val]<=25?$GD_Hours[$DATE_Val]:'0';
								$GA_Percent[$DATE_Val]=((24 - $GD_Hours[$DATE_Val]) / 24) * 100 ;
								$Array_Lull[$DATE_Val]=$Lull_Hours[$DATE_Val]>0 && $Lull_Hours[$DATE_Val]<=25?$Lull_Hours[$DATE_Val]:'0';
								$Loss_Due_To_GD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $GD_Hours[$DATE_Val];
								$BD_Hours[$DATE_Val]=24-($GD_Hours[$DATE_Val]+$Lull_Hours[$DATE_Val]+$Gen1[$DATE_Val]);							
								$Loss_Due_To_BD[$DATE_Val] = ($Total_Gen[$DATE_Val]/$Run_Hours[$DATE_Val]) * $BD_Hours[$DATE_Val]; 
								//$MA_Percent[$DATE_Val]=(((24-$GD_Hours[$DATE_Val])-($BD_Hours[$DATE_Val])) / (24 - $GD_Hours[$DATE_Val])) *100;
								$MA_Percent[$DATE_Val]=((24-$BD_Hours[$DATE_Val]) / 24 ) *100;
								$Array_BD[$DATE_Val]=$BD_Hours[$DATE_Val]>0 && $BD_Hours[$DATE_Val]<=25?$BD_Hours[$DATE_Val]:'0';
				}
			}
			
							}//endif isset

						}//end foreach

						

						foreach($Date_Array as $DATE_Val){

							?>

                        <tr>

                       		<td class="tab-head-td1" align="left"><?=$DATE_Val != ''?$DATE_Val : '0'?> </td>              

				<td class="tab-head-td1" align="left"><?=$Device_Name?></td>
				
				<td class="tab-head-td1" align="left"><?=($Total_Gen[$DATE_Val] >=0 && $Total_Gen[$DATE_Val] <=(150000*($diff+1)))?round($Total_Gen[$DATE_Val],2): '000'?> </td>                  
				
              	<td class="tab-head-td1" align="left"><?=($Total_hrs[$DATE_Val] >=0 && $Total_hrs[$DATE_Val] <=24)? round($Total_hrs[$DATE_Val],2) : (($Total_hrs[$DATE_Val] == 25)? '24' : '000')?></td>

                <td class="tab-head-td1" align="left"><?=($Run_Hours[$DATE_Val] >=0 && $Run_Hours[$DATE_Val] <=25)?round($Run_Hours[$DATE_Val],2) : (($Run_Hours[$DATE_Val] == 25)? '24' : '000')?></td>                               
                
				<td class="tab-head-td1" align="left"><?=($Line_Hours[$DATE_Val] >=0 && $Line_Hours[$DATE_Val] <=25)?round($Line_Hours[$DATE_Val],2) : (($Line_Hours[$DATE_Val] == 25)? '24' : '000')?></td> 
				
				<td class="tab-head-td1" align="left"><?=($Import_LCS[$DATE_Val] >=0 && $Import_LCS[$DATE_Val] <=25)?round($Import_LCS[$DATE_Val],2) : (($Import_LCS[$DATE_Val] == 25)? '24' : '000')?></td> 
                      
					
                        </tr>
						<?php

								}



							

						?>
<tr class="total-row">

<td class="tab-head-td1"><b>Total</b></td>
<td class="tab-head-td1"></td>

<td class="tab-head-td1"><b><?=(arraySumRecursive($Array_Gen)>=0)? arraySumRecursive($Array_Gen):'000' ?></b></td>

<td class="tab-head-td1"><b><?=(arraySumRecursive($Array_Import)>=0)? arraySumRecursive($Array_Import):'000'?></b></td>

<td class="tab-head-td1"><b><?=(arraySumRecursive($Array_Run)>=0) ? arraySumRecursive($Array_Run):'000'?></b></td>

<td class="tab-head-td1"><b><?=(arraySumRecursive($Array_Line)>=0) ? arraySumRecursive($Array_Line):'000'?></b></td>

<td class="tab-head-td1"><b><?=(arraySumRecursive($Array_BD)>=0) ? arraySumRecursive($Array_BD):'000'?></b></td>

</tr>



					</table>

         <?php 
				} // Mysql Record End

				else{

					echo $No_Records;

				}//ifelse end

		//	}//if p is set

         ?>

	<?php

	}//xls=1

	?>            </td>	

        </tr>