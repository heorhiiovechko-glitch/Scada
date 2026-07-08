
<?php
	include("header_inner.php");
	error_reporting(0);
	if(empty($_COOKIE[$Cook_Name])){
		header("Location: index.php");
		exit;
	}
	//print_r($Cook_Variable);
//echo $Database_Name;
	#
	#	Error Status from ERROR_TYPE
	#
	$Mysql_Query = "select * from error_type";
	if (!$queryResult = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($queryResult->num_rows >= 1)
            {
                while($Fetch_Result2 = $queryResult->fetch_array()) {
		
			$Error_Array[$Fetch_Result2['Machine_Status']][] = $Fetch_Result2['Error'];
			$Machine_Status_Array[$Fetch_Result2['Machine_Status']] = $Fetch_Result2['Machine_Status'];
		}
	}
	
		$Audio=array();
		$td=0;
		$tr=0;
		$CurrentState="";
		$Total_Power=0;
		$CurrentSite="";
		$Total_Export=0;
		$WTG_Run=0;
//echo $Account_ID;
?>
 <script type="text/javascript">
    setInterval("my_function();",60000);
    function my_function(){
      $('#getdata').load('channel1_psrwind.php #getdata');
    }

  </script>


<script language="javascript"
type="text/javascript"
src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js">
 
</script>
 
 
<script type="text/javascript" language="javascript">
 
     $(function() {
 
            $(this).bind("contextmenu", function(e) {
 
                e.preventDefault();
 
            });
 
        }); 
</script>
<style type="text/css">
.timercount {
background-image: -webkit-gradient(linear, left top, left bottom, from(#000), color-stop(.5, rgba(0, 100, 0, .5)), to(#000));
background-image: -moz-linear-gradient(top, #000, rgba(0, 100, 0, .5) 50%, #000);
-webkit-transition: opacity 1000ms ease;
-moz-transition: opacity 1000ms ease;background-color: #0f0;
border-radius: 4px;
-moz-border-radius: 4px;
position: absolute;
-webkit-box-shadow: 0 0 20px #0f0;
-moz-box-shadow: 0 0 20px #0f0;
-webkit-transition: all 400ms ease;
-moz-transition: all 400ms ease;
font-size: 20px;
color: lime;
width:60px;text-align:center;
padding: 10px;
position: relative;
height:20px;
text-shadow: 0 0 20px rgb(200, 20, 0);
}
</style>

     <center> 
  <body  style="background-color:#f5f5ef;">
  
			<table border="0" cellpadding="3" cellspacing="3" width="100%" >
            	<tr>
                	<td>
                        <table border="0" cellpadding="2" cellspacing="2" width="100%">
                            <tr>  <td width="40%" align="left;">        

<h2><?php  if($Account_ID==96){ echo "<img src=\"images/windmil-logo.jpg\" width=\"44\" height=\"44\" alt=\"yellow\">&nbsp;&nbsp;";  } elseif($Account_ID==100097) { echo "<img src=\"images/RRPL Logo_small1.jpg\" width=\"74\" height=\"74\" alt=\"yellow\">&nbsp;&nbsp;";  }else { ?>Energy from <? }?><?= $Firstname." ".$Lastname?>!</h2></td>

				    <td width="15px"><img src="images/new_orange.gif" width="24" height="24" alt="yellow"></td>
                                    <td width="90px">Null Wind</td>
                                    <td width="15px"><img src="images/new_green.gif" width="24" height="24" alt="gren"></td>
                                    <td width="90px">WTG Run</td>
                                    <td width="15px"><img src="images/new_red.png" width="24" height="24" alt="red"></td>
                                    <td width="100px">Error Stop</td>
                                    <td width="15px"><img src="images/new_blue.png" width="24" height="24" alt="blue"></td>
                                    <td width="90px">Grid Drop</td>
				    <td width="15px"><img src="images/new_pink.png" width="24" height="24" alt="pink"></td>
                                    <td width="90px">Key Pressed</td>
                                    <td width="15px"><img src="images/new_grey.png" width="24" height="24" alt="yellow"></td>
                                    <td width="120px">No Communication</td>
				     <td width="300px">Daily Prod:Generation/Run Hrs/Stop Hrs</td>			
                                  
                            </tr>
			
                        </table>   
                    </td>
                </tr>
             
            	<tr>
                	<td><div id="getdata">
                    	<table border="0" cellpadding="1" cellspacing="1" width="100%">
                        	
                    	<?php				
					//print_r($Cook_Variable);
							
							$WindSpeed = null;
							$Power = null;
							if($User_Type_ID ==3 || $User_Type_ID ==2)
						$Mysql_Query2 = "SELECT  t1.*, s.totalCount AS count 
								FROM  device_register AS t1 
 								 LEFT JOIN
								        (
						            SELECT Device_Index,State, COUNT(State)  totalCount
           							 FROM  device_register 
           						 WHERE  Parent_ID = '".$Account_ID."'
          							  GROUP   BY State
       					 )  s ON s.State = t1.State   where t1.Parent_ID = '".$Account_ID."'
ORDER   BY count desc,State desc, Device_Order asc";
						elseif($User_Type_ID ==4)
							$Mysql_Query2 = "SELECT  t1.*, s.totalCount AS count 
								FROM  device_register AS t1 
 								 LEFT JOIN
								        (
						            SELECT Device_Index,State, COUNT(State)  totalCount
           							 FROM  device_register 
           						 WHERE  Account_ID = '".$Account_ID."'
          							  GROUP   BY State
       					 )  s ON s.State = t1.State   where t1.Account_ID = '".$Account_ID."'
ORDER   BY count desc,State desc,Device_Order asc";
//echo $Mysql_Query2;
					if (!$queryResult2 = $db->query($Mysql_Query2))
            {
                die($db->error);
            }
			$Mysql_Record_Count = $queryResult2->num_rows;
            if($queryResult2->num_rows >= 1)
            {
                while($Fetch_Result = $queryResult2->fetch_array()) {
		
									$IMEI = base64_encode($Fetch_Result['IMEI']);							
									//setcookie($IMEI_Timer, $Temer, time()+86400);
									
									$HTSCno = $Fetch_Result['HTSC_No'];									
									$WEGno[$Fetch_Result['IMEI']]=$Fetch_Result['WEG_No'];
									$State[$Fetch_Result['IMEI']]=$Fetch_Result['State'];//echo $State;
									$Site_Location[$Fetch_Result['IMEI']]=$Fetch_Result['Site_Location'];//echo $Site_Location;
									$Device_Name[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
									$Device = $Fetch_Result['Device_Name'];
									$Format_Type = $Fetch_Result['Format_Type'];
									$Pocket_Length = $Fetch_Result['Pocket_Length'];							
									$Connect_Feeder[$Fetch_Result['Site_Location']]=$Fetch_Result['Connect_Feeder'];
									$Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
									$Capacity = $Fetch_Result['Capacity'];									
									if($Format_Type == 1){
										$Channel_Url = "channel2.php?";
										$Table_Name = "device_data"; 
										$Error_Table_Name = "error_data"; 
									}elseif($Format_Type == 2){										
										if($Device=='Selva Tex 250kw') {
										$Channel_Url = "channel3_selvatex.php?";
										$Table_Name ="device_data_f2";									
										$Error_Table_Name = "error_data_f2";
										} elseif($Account_ID=='100215') {
										$Channel_Url = "channel3_ucal.php?";
										$Table_Name ="device_data_f2";									
										$Error_Table_Name = "error_data_f2";
										} else {
										$Channel_Url = "channel3.php?";
										$Table_Name ="device_data_f2";									
										$Error_Table_Name = "error_data_f2";  
										}
									}elseif($Format_Type == 3){
										$Channel_Url = "channel4.php?";
										$Table_Name = "device_data_f3";									
										$Error_Table_Name = "error_data_f3";
									}elseif($Format_Type == 4){
										if($Device=='Aspire') {
										$Channel_Url = "channel9_new.php?";
										$Table_Name = "device_data_f4"; 
										$Error_Table_Name = "error_data_f4"; 
										} else {
										$Channel_Url = "channel5.php?";
										$Table_Name = "device_data_f4"; 
										$Error_Table_Name = "error_data_f4"; 
										} 	
									}elseif($Format_Type == 6){
										if($Database_Name=='va_siva')
										$Channel_Url = "channel7_old.php?";
										elseif($Database_Name=='va_dhanalakshmi')
										$Channel_Url = "channel7_kvarh.php?";
										else
										$Channel_Url = "channel7.php?";
										$Table_Name = "device_data_f6"; 
										$Error_Table_Name = "error_data_f6"; 
									}elseif($Format_Type == 7){
										if($Device=='ICE MAN') {
										$Channel_Url = "channel1_iceman.php?";
										$Table_Name ="device_data_f7";									
										$Error_Table_Name = "error_data_f7";
										} else {
										$Channel_Url = "channel8.php?";
										$Table_Name = "device_data_f7"; 
										$Error_Table_Name = "error_data_f7"; 
										}
									}elseif($Format_Type == 8){
										$Channel_Url = "channel8.php?";
										$Table_Name = "device_data_f8"; 
										$Error_Table_Name = "error_data_f8";  
									}elseif($Format_Type == 10){
										$Channel_Url = "channel10.php?";
										$Table_Name = "device_data_f10"; 
										 $Error_Table_Name = "error_data_f10"; 
									}

									#	Encode IMEI
									$IMEI_Decode = base64_decode($IMEI);
									$From_Mon_D_Epoch="01-".date("m-Y");
									$From_Mon_D_Epoch= strtotime($From_Mon_D_Epoch)+(60*60*5.5);
									$To_Mon_D_Epoch=date("d-m-Y");
									$To_Mon_D_Epoch=strtotime($To_Mon_D_Epoch." 23:59:59")+(60*60*5.5);
									$From_Date="01-01-".(date("Y"));
									$To_Date="01-12-".date("Y");				
									$From_Year_D_Epoch = strtotime($From_Date)+(60*60*5.5);
									$To_Year_D_Epoch = strtotime($To_Date." 23:59:59")+(60*60*5.5);
									
										$GD_Time=time();

						
//print_r($Closing_Time)."</br>";	
//echo $Test_Time."</br>";																	
		if($Format_Type==2 )
		$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, devicedata,((Gen1_Max - Gen1_Min)+(Gen2_Max - Gen2_Min)) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";	
		elseif($Format_Type==4)
		$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, ((Gen1_Max - Gen1_Min)+(Gen2_Max - Gen2_Min)) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";	
		elseif($Format_Type==1)
		$Mysql_Query1="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen2_Max - Gen2_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
		elseif($Format_Type==6)
		$Mysql_Query1="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, devicedata, (Gen2_Max - Gen2_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2  from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
		elseif($Format_Type==10)
		$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
		elseif($Format_Type==3)
		$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, (Gen1_Max - Gen1_Min) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
		elseif($Format_Type==7)
		$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, Gen1_Hours_Max as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
		elseif($Format_Type==8)
		$Mysql_Query1 ="select date_s as Date_S,time_s as Time_S, windspeed as WindSpeed, power as Power,status as Status, Gen1_Max as G1, Gen1_Hours_Max as G2 from va_master.device_register where IMEI = '".$IMEI_Decode."' order by IMEI desc limit 1";
		else 
		$Mysql_Query1 ="select Date_S,Time_S,WindSpeed,Power,Status from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
									//echo $Mysql_Query1;	
						if (!$queryResult1 = $db->query($Mysql_Query1))
            {
                die($db->error);
            }

            if($queryResult1->num_rows >= 0)
            {
               $Fetch_Result1 = $queryResult1->fetch_array();							
									 										
										$devicedata = explode(',', $Fetch_Result1['devicedata']);
										$G4_Temp=$devicedata[13];
										$G6_Temp=$devicedata[15];			
										$G3= $devicedata[19];
										$Gvarh = $devicedata[27];
										//$Daily_Generated_Units=$Fetch_Result1['Daily_Generated_Units'];
										$WindSpeed = $Fetch_Result1['WindSpeed'];
										$WindSpeed = str_replace('m/s','',$WindSpeed);	
										$WindSpeed != ''? $WindSpeed = number_format($WindSpeed,2) : $WindSpeed = '0.00';			 									
										$Power = $Fetch_Result1['Power'];	
										
										$Power != ''? $Power = number_format($Power,2) : $Power = '0.00';
										$Status1 = trim($Fetch_Result1['Status']);
										$Status = strtolower($Status1);
											
										if($Device_Name[$IMEI_Decode]=='Selvam 11-750kw' && $Status=='emergency line fault') {
											$Status='run';
										}
//echo $Status;
										$G1= round($Fetch_Result1['G1']);
										$G2= round($Fetch_Result1['G2']);
										if($G1 >18000 || $G1<0) {
											$G1='0';
										}
										if($G2 >24 || $G2<0) {
											$G2='0';
										}
										//$G2_Limit=$G2*3600;
										$Total_Export+=$G1;//echo $Total_Export;
										$Total_Power+=$Power;
										$Date_F = $Fetch_Result1['Date_S'];
										$Time_F = $Fetch_Result1['Time_S'];
										$GD_Hours=date('H',$GD_Time-($G2*3600));
				
										$Device_Epoch_Time=GetTimestamp($Date_F,$Time_F);

										if(!empty($Device_Epoch_Time)){
											$Diff_Error_Status = $Device_Epoch_Time;
											
										}
										# More than 5 hours not working
										$Req_Time = time()+(60*60*5.5);
										//echo $Status;
										$ReqTime_Diff = $Req_Time - $Device_Epoch_Time;
										if($ReqTime_Diff >= 1800 && (in_array($Status,$Error_Array['Green']) && !in_array($Status,$Error_Array['Blue']))){
											$Tower_Img = '<img src="./images/new_grey.png" width="70px" height="50px" alt="brown Tower">';
										}
										else
										{
											# Depence upon the Status Tower image
											
											if(in_array($Status,$Error_Array['Green'])){
												
												if($Power == '000' || $Power == '0' || $Power < 0){
													//$Tower_Img = '<img src="./images/7.jpg" width="69px" height="98px" alt="Orange Tower">';

													$WTG_Run++;

$Tower_Img = '<img src="./images/new_orange.gif" width="70px" height="50px" alt="orange Tower">';
													

													

												}
												else{
													$WTG_Run++;
													
$Tower_Img = '<img src="./images/new_green.gif" width="70px" height="50px" alt="Green Tower">';

												}	
											}	
											elseif(in_array($Status,$Error_Array['Orange'])){
												$Tower_Img = '<img src="./images/new_orange.gif" width="70px" height="50px" alt="Orange Tower">';
												
												
												
											}	
											elseif(in_array($Status,$Error_Array['Blue'])){
												$Tower_Img = '<img src="./images/new_blue.png" width="70px" height="50px" alt="Blue Tower">';
												$Audio[]=$WEGno[$IMEI_Decode];
											
											}
											elseif(in_array($Status,$Error_Array['Pink'])){
												$Tower_Img = '<img src="./images/new_pink.png" width="70px" height="50px" alt="Pink Tower">';
												$Audio[]=$WEGno[$IMEI_Decode];
											
											}
											else{											
												$Tower_Img = '<img src="./images/new_red.png" width="70px" height="50px" alt="Red Tower">';
												$Audio[]=$WEGno[$IMEI_Decode];
											}	
										}
									}
									
							$PreviousState=$CurrentState;
							
							if($CurrentState=="" || $CurrentState!=$State["$IMEI_Decode"]){
							$td=0;	
							?>
							<tr>
	
								<td valign="top"  align="left;"><h2><?= $State["$IMEI_Decode"] ?></h2></td>

							</tr>
							<?php
							
							$CurrentState=$State["$IMEI_Decode"];
							 }
							?> 
							 
							
                                    			  <td style="width:80px;"><table border=0 >
                                   
						<?php
							if($Account_ID=='100079' || $Account_ID=='100081' || $Account_ID=='100082' || $Account_ID=='100084' || $Account_ID=='100077' ) {

							//if($CurrentSite=="" || $CurrentSite!=$Site_Location["$IMEI_Decode"] ){
							$Feeder_List=implode(",",$Connect_Feeder[$Site_Location["$IMEI_Decode"]]);
						?>
							
	
								<tr><td valign="top" style="width:80px;"><h3><?= $Site_Location["$IMEI_Decode"]?></h3></td></tr>
							
<?php
							$CurrentSite=$Site_Location["$IMEI_Decode"];
							//}else{
							//echo "<tr><td valign=\"top\" ><h3>&nbsp;</h3></td></tr>";
							//}
						} else {
							if($CurrentSite=="" || $CurrentSite!=$Site_Location["$IMEI_Decode"] ){
							$Feeder_List=implode(",",$Connect_Feeder[$Site_Location["$IMEI_Decode"]]);
						?>
							
	
								<tr><td valign="top" style="width:80px;"><h3><?= $Site_Location["$IMEI_Decode"]?></h3></td></tr>
							
<?php
							$CurrentSite=$Site_Location["$IMEI_Decode"];
							}else{
							echo "<tr><td valign=\"top\" ><h3>&nbsp;</h3></td></tr>";
							}
						} 
						?>

						<?php
							$Date="";
							$Time="";
						//	$Date_e = strtotime($ED_Error_Date[$IMEI_Decode]);
						//	$Time_e = strtotime($ED_Error_Time[$IMEI_Decode]);
							$Date_G = strtotime($Date_F);
							$Time_G = strtotime($Time_F);

if(in_array($Status,$Error_Array['Green'])){
							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);
}							
								
							
elseif(in_array($Status,$Error_Array['Blue']) || in_array($Status,$Error_Array['Pink'])) {

$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);							

							
						
							}

else {

							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);
				
	
							
							}



							?>
					<td>
                                   <?= $Connect_Feeder[$Site_Location["$IMEI_Decode"]]?>
                                        <table border="0" cellpadding="2" cellspacing="2" style="background-color:white;border:solid 1px #d6d6c2;" width="310px" height="150px">
                                            <tr>
	                                            <td colspan="2" align="left" style="font-size:16px;font-weight:bold;"><?= $Device_Name[$IMEI_Decode]?> </td></tr>
												<tr><td rowspan="2">
												<?php if ($Account_ID!='100146') {?> <a href="<?=$Channel_Url?>c1=<?=$IMEI?>&l=<?=$Pocket_Length?>&FType=<?=$Format_Type?>" border="0" style="cursor:pointer; color:#333333; text-decoration:none;" target="_blank"; title="IMEI :<?=$IMEI_Decode?>"> <?php } ?> <?=$Tower_Img?></a></td> &nbsp;
												<td style="font-size:16px;text-align:right;"><?=$Date?></td>
                                            </tr>	
									<tr>
										<td style="font-size:16px;text-align:right;"><?=$Time?></td>
									</tr>
												
						
                                                   <?php
							if($Device_Name[$IMEI_Decode]=='ICE MAN'){
							
							?>	<tr>
								<td class="tower-txt" width="30%"  style="font-size:14px;">HTSC No</td>
			                                            	<td class="tower-txt"style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp; <?=$HTSCno?></td>			                                 </tr>
								<!--<tr>
								<td class="tower-txt" width="30%">DateTime</td>
			                                            	<td class="tower-txt">: <?=$Date?>&nbsp;&nbsp;<?=$Time?></td>			                                
															</tr>-->
														 <tr style="background-color:#f5f5ef;">
                                                        	<td class="tower-txt"  width="30%"  style="font-size:14px;">Total KW</td>
			                                            	<td class="tower-txt" style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp; <?=$G4_Temp?> kW</td>
                                                        </tr>
							
                                                    	
							<?php
							} elseif($Device_Name[$IMEI_Decode]=='Selva Tex TTG 250KW'){
							
							?>	<tr>
								<td class="tower-txt" width="50%"  style="font-size:14px;">HTSC No</td>
			                                            	<td class="tower-txt" style="font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp; <?=$HTSCno?></td>			                                 </tr>
							
									<!--<tr>
								<td class="tower-txt" width="30%">DateTime</td>
			                                            	<td class="tower-txt">: <?=$Date?>&nbsp;&nbsp;<?=$Time?></td>			                                
															</tr>-->
															<tr style="background-color:#f5f5ef;">
                                                        	<td class="tower-txt"  width="50%"  style="font-size:14px;">Total KW</td>
			                                            	<td class="tower-txt" style="font-size:14px;text-align:right;font-weight:bold;" >&nbsp;&nbsp; <?=$G6_Temp?> kW</td>
                                                        </tr>
														 <tr>
                                                        	<td class="tower-txt"  width="30%"  style="font-size:14px;">GAD</td>
			                                            	<td class="tower-txt" style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp; <?=$G3?> kWh</td>
                                                        </tr>
														<tr style="background-color:#f5f5ef;">
                                                            <td class="tower-txt" width="30%"  style="font-size:14px;">Status</td>
                                                            <td class="tower-txt" style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp; <?=$Status?></td> 
                                                         </tr> 
							
                                                    	
							<?php
							}
							else {
							?>
														 <tr >
								<td class="tower-txt"  style="font-size:14px;" >HTSC No</td>
			                                            	<td class="tower-txt" style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp; <?=$HTSCno?></td>			                                
															</tr>
					
									<!--<tr>
								<td class="tower-txt" width="30%">DateTime</td>
			                                            	<td class="tower-txt">: <?=$Date?>&nbsp;&nbsp;<?=$Time?></td>	-->		                                
															
							<tr >
                                                        	<td class="tower-txt"   style="font-size:14px;">Speed</td>
			                                            	<td class="tower-txt"style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp; <?=$WindSpeed?> m/s</td>
                                                        </tr>
							
                                                    	<tr>
                                                            <td class="tower-txt"  style="font-size:14px;" >Power</td>
                                                            <td class="tower-txt" style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp; <?=$Power?> kW</td> 
                                                         </tr>
                                                    	
							
                                                    	<tr>
                                                            <td class="tower-txt"  style="font-size:14px;">Daily&nbsp;Production</td>
								       <td class="tower-txt" align="right" style="font-size:14px;text-align:right;font-weight:bold;">&nbsp;&nbsp;
								 <? 
								echo $G1 != ''?$G1 : '0';
         							echo " kWh/";
         						        echo $G2 != ''?$G2 : '0';
       								echo "h/";
							        echo $GD_Hours != ''?$GD_Hours : '0';
								echo "h";
       														
							?></td> 
								
                                                         </tr>
														 <?php
							}
							?>
							
                                                                          
                                                              
                                            </tr>
                                        </table>
                                    </td></tr>
 				</table>
                                    </td>
                            <?php
					$td++;
					
					if($td==5){
					echo "</tr><tr>";
					$td=0;
						}
					
								}
							}
							else{
								echo "<br /><br /><br /><h2>Machine not yet Installed...</h2><br /><br /><br /><br />";
							}
					?>
                         </table></div>   
                    </td>
                </tr>
            </table>
 
       <!-- <?php if($Format_Type != 9){?>  <p >Please click to any windmill power generator to see the detailed information</p><?php } ?> <br /> <br />-->
     
<div  id="ref"   class="marquee" ><marquee behavior="scroll" scrollamount=1  direction="left" ><h3>Total Power: <?= $Total_Power?>KW,  Total Export: <?= $Total_Export?>kwh, WTG Run: <?=$WTG_Run."/".$Mysql_Record_Count ?></h3></marquee></div>
      </body>
	
</center>
<?php


	//include("footer.php");
?>