
<?php
//ini_set('max_execution_time', 7200);
	include("header_inner.php");
	error_reporting(0);
	if(empty($_COOKIE[$Cook_Name])){
		header("Location: index.php");
		exit;
	}
	//print_r($Cook_Variable);
	#
	#	Error Status from ERROR_TYPE
	#
	$Mysql_Query = "select Error,Machine_Status from error_type";
	if (!$queryResult = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($queryResult->num_rows >= 1)
            {
                while($Fetch_Result = $queryResult->fetch_array()) {
			$Error_Array[$Fetch_Result['Machine_Status']][] = $Fetch_Result['Error'];
			$Machine_Status_Array[$Fetch_Result['Machine_Status']] = $Fetch_Result['Machine_Status'];
		}
	}
	
	//include("Gen_Export_Day_Type1.php");
	//echo "Value---".$G1_G2_Total_Final;
		$Audio=array();
		$td=0;
		$tr=0;
		$CurrentState="";
		$Total_Power=0;
		$CurrentSite="";
		$Total_Export=0;
		$WTG_Run=0;
$Cur_Date=date('d_m_Y');

?>
<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
<script>
   
$(document).ready(
            function() {
                setInterval(function() {
                          $('#getdata').load('channel1_all.php #getdata');
                }, 120000);
            });
</script>
 <script type="text/javascript">
 
 setInterval("scroll_func();",120000);
    function scroll_func(){
      $('#ref').load('channel1_all.php #ref');
    }


  </script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript">
function showbox(x){
document.getElementById(x).style.display = 'block';


}
function hidebox(x)
{
document.getElementById(x).style.display = 'none';
}
        </script>
<style> 
.boxed-green {
    border-radius: 50px;
    background: #73AD21;
    padding: 4px 3px 0 3px;
    
    text-align:center;
vertical-align: text-top;
height: 30px; 
    min-width: 150px;
   color:white;
}
.boxed-orange {
    border-radius: 50px;
    background: orange;
     padding: 4px 3px 0 3px;
    
    text-align:center;
vertical-align: text-top;
height: 30px; 
    min-width: 150px;
   color:white;
}
.boxed-blue {
    border-radius: 50px;
    background: blue;
    padding: 4px 3px 0 3px;
    
    text-align:center;
vertical-align: text-top;
height: 30px; 
    min-width: 150px;
   color:white;

   
}
.boxed-red {
    border-radius: 50px;
    background: red;
   padding: 4px 3px 0 3px;
    
    text-align:center;
vertical-align: text-top;
height: 30px; 
    min-width: 150px;
color:white;
   
}
.boxed-pink {
    border-radius: 50px;
    background: Pink;
   padding: 4px 3px 0 3px;
    
    text-align:center;
vertical-align: text-top;
height: 30px; 
    min-width: 150px;
color:white;
   
}
.boxed-grey {
    border-radius: 50px;
    background: grey;
     padding: 4px 3px 0 3px;
    
    text-align:center;
vertical-align: text-top;
height: 30px; 
    min-width: 150px;
   color:white;
   
}


.popupbox { 
position: absolute; 
background-color: white;
color: black; 
border: 1px solid #1a1a1a; 
display:none;
 

margin-left:-200px;   /* negative half of width above */
margin-top:-200px;   /* negative half of height above */


  
    
} 
</style>

      <center>
  <div id="body" class="clear" style="width:99%;">

   <!-- <div class="box">
      <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>
	
      <div class="content">-->


          <form name="channel1_versatile" method="post" action="">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>

                	<td width="100px">

                       <div id="getdata">
                    	<table border="0" cellpadding="0" cellspacing="0" width=100%>
                        	
                    	<?php				
					//print_r($Cook_Variable);
//echo $Account_ID;
							
							$WindSpeed = null;
							$Power = null;
							
						$Mysql_Query = "SELECT  t1.IMEI,t1.Format_Type,t1.State,t1.Site_Location,t1.Account_ID,t1.Parent_ID,t1.Device_Order,t1.Device_Index,
										t1.HTSC_No,t1.Closing_Time,t1.WEG_No,t1.Device_Name,t1.Pocket_Length,t1.db_name,t1.Power_Curve,t1.Connect_Feeder,t1.Region, s.totalCount AS count 
								FROM device_register AS t1 
 								 LEFT JOIN
								        (
						            SELECT Device_Index,State, COUNT(State)  totalCount
           							 FROM  device_register where Device_Index not in ('582') GROUP   BY Region
       					 )  s ON s.State = t1.State where t1.Device_Index not in ('582') group by IMEI   
ORDER   BY Region desc,db_name asc,device_order asc"; 
	//$Mysql_Query = "SELECT  IMEI,Format_Type,State,Site_Location,Account_ID,Parent_ID,Device_Order,Device_Index,HTSC_No,WEG_No,Device_Name,Pocket_Length,db_name,Power_Curve,Connect_Feeder FROM  device_register where device_index not in ('550','551','552','601') group by IMEI ORDER   BY State desc,db_name asc,device_order asc";
					//echo $Mysql_Query;
													
							if (!$queryResult = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($queryResult->num_rows >= 1)
            {
                while($Fetch_Result = $queryResult->fetch_array()) {
									$IMEI_Org = $Fetch_Result['IMEI'];	
									$DeviceName[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
									$Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
									$HTSCno[$Fetch_Result['IMEI']] = $Fetch_Result['HTSC_No'];
									$IMEI_Encode = base64_encode($Fetch_Result['IMEI']);
									$WEGno[$Fetch_Result['IMEI']]=$Fetch_Result['WEG_No'];
									$WindFarm_No[$Fetch_Result['IMEI']]=$Fetch_Result['WindFarm_No'];
									$State[$Fetch_Result['IMEI']]=$Fetch_Result['State'];//echo $State;
									$Region[$Fetch_Result['IMEI']]=$Fetch_Result['Region'];
									$Site_Location[$Fetch_Result['IMEI']]=$Fetch_Result['Site_Location'];//echo $Site_Location;
									$Device_Name[$Fetch_Result['IMEI']] = substr($Fetch_Result['Device_Name'],0,18);
									$Turbine[$Fetch_Result['IMEI']] = $Fetch_Result['IMEI'];
									//$DB_Name[$Fetch_Result['IMEI']] = $Fetch_Result['db_name'];
									$Format_Type = $Fetch_Result['Format_Type'];
									$Pocket_Length = $Fetch_Result['Pocket_Length'];							
									$Connect_Feeder[$Fetch_Result['Site_Location']]=$Fetch_Result['Connect_Feeder'];
									$Feeder[$Fetch_Result['IMEI']] = $Fetch_Result['Connect_Feeder'];
									$Capacity = $Fetch_Result['Capacity'];									
									if($Format_Type == 1){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type1/$IMEI_Org.txt";
										$Table_Name = "device_data"; 
										$Error_Table_Name = "error_data"; 
									}elseif($Format_Type == 2){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type2/$IMEI_Org.txt";
										$Table_Name ="device_data_f2";									
										$Error_Table_Name = "error_data_f2"; 
									}elseif($Format_Type == 3){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type3/$IMEI_Org.txt";
										$Table_Name = "device_data_f3";									
										$Error_Table_Name = "error_data_f3";
									}elseif($Format_Type == 4){
										$Channel_Url =  "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type4/$IMEI_Org.txt";
										$Table_Name = "device_data_f4";									
										$Error_Table_Name = "error_data_f4";
									}elseif($Format_Type == 5){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type5/$IMEI_Org.txt";
										$Table_Name = "device_data_f5"; 
										$Error_Table_Name = "error_data_f5"; 
									}elseif($Format_Type == 6){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type6/$IMEI_Org.txt";
										$Table_Name = "device_data_f6"; 
										$Error_Table_Name = "error_data_f6"; 
									}elseif($Format_Type == 7){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type7/$IMEI_Org.txt";
										$Table_Name = "device_data_f7"; 
										$Error_Table_Name = "error_data_f7"; 
									}elseif($Format_Type == 8){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type8/$IMEI_Org.txt";
										$Table_Name = "device_data_f8"; 
										 $Error_Table_Name = "error_data_f8";
									}elseif($Format_Type == 9){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type9/$IMEI_Org.txt";
										$Table_Name = "device_data_f9"; 
										 $Error_Table_Name = "error_data_f9";
									}elseif($Format_Type == 10){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type10/$IMEI_Org.txt";
										$Table_Name = "device_data_f10"; 
										 $Error_Table_Name = "error_data_f10"; 
									}elseif($Format_Type == 11){
										$Channel_Url = "http://103.139.69.46:90/versatile_log/EventLog/$Cur_Date/Type11/$IMEI_Org.txt";
										$Table_Name = "device_data_f11"; 
										 $Error_Table_Name = "error_data_f11"; 
									}
									
//print_r($IMEI);


									#	Encode IMEI
									$IMEI = base64_decode($IMEI_Encode);
//echo $IMEI;
									$DB_Mysql_Query = "select IMEI,db_name,Account_ID from device_register where IMEI = '".$IMEI."'";
									if (!$DBqueryResult = $db->query($DB_Mysql_Query))
            {
                die($db->error);
            }

            if($DBqueryResult->num_rows >= 1)
            {
               $DB_Fetch_Result = $DBqueryResult->fetch_array();
										$DB_Name[$DB_Fetch_Result['IMEI']] = $DB_Fetch_Result['db_name'];
										$ACC[$DB_Fetch_Result['IMEI']] = $DB_Fetch_Result['Account_ID'];
																			
									}


		if($Format_Type==2 || $Format_Type==4)
		$Mysql_Query1 ="select Date_S,Time_S,Status,((Gen1_Max - Gen1_Min)+(Gen2_Max - Gen2_Min)) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from device_register where IMEI = '".$IMEI."' limit 1";	
		elseif($Format_Type==6 || $Format_Type==1)
		$Mysql_Query1="select Date_S,Time_S,Status,(Gen2_Max - Gen2_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from device_register where IMEI = '".$IMEI."'  limit 1";
		elseif($Format_Type==10)
		$Mysql_Query1 ="select Date_S,Time_S,Status,(Gen1_Max - Gen1_Min) as G1, (Gen1_Hours_Max - Gen1_Hours_Min) as G2 from device_register where IMEI = '".$IMEI."'  limit 1";
		elseif($Format_Type==3)
		$Mysql_Query1 ="select Date_S,Time_S,Status,(Gen1_Max - Gen1_Min) as G1, ((Gen1_Hours_Max - Gen1_Hours_Min)+(Gen2_Hours_Max - Gen2_Hours_Min)) as G2 from device_register where IMEI = '".$IMEI."'  limit 1";
		elseif($Format_Type==7)
		$Mysql_Query1 ="select Date_S,Time_S,Status,Gen1_Max as G1, Gen1_Hours_Max as G2 from device_register where IMEI = '".$IMEI."' limit 1";
		elseif($Format_Type==8)
		$Mysql_Query1 ="select Date_S,Time_S,Status,Gen1_Max as G1, Gen1_Hours_Max as G2 from device_register where IMEI = '".$IMEI."' limit 1";
		elseif($Format_Type==9)
		$Mysql_Query1="select Date_S,Time_S, Status, Gen1_Max as G1, (Gen1_Hours_Max-Gen2_Max) as G2 from va_master.device_register where IMEI = '".$IMEI."' limit 1";
		elseif($Format_Type==11)
		$Mysql_Query1="select Date_S,Time_S, Status, Gen1_Max as G1, (Gen1_Hours_Max-Gen2_Max) as G2 from va_master.device_register where IMEI = '".$IMEI."' limit 1";
		else 
		$Mysql_Query1 ="select Date_S,Time_S,Status from $DB_Name[$IMEI].$Table_Name where IMEI = '".$IMEI."' and Status!='' order by Record_Index desc limit 1";
									
			if (!$queryResult1 = $db->query($Mysql_Query1))
            {
                die($db->error);
            }

            if($queryResult1->num_rows >= 0)
            {
               $Fetch_Result1 = $queryResult1->fetch_array();							
									 																	
										
										$Status1 = trim($Fetch_Result1['Status']);
										$Status = strtolower($Status1);//echo $Status;
										
										$Date_S = $Fetch_Result1['Date_S'];
										$Time_S = $Fetch_Result1['Time_S'];	
										$G1 = $Fetch_Result1['G1'];		
										$G2 = $Fetch_Result1['G2'];		
											
										$Device_Epoch_Time=GetTimestamp($Date_S,$Time_S);
//print_r($ED_Device_Epoch_Time_Array[$IMEI]);


										if(!empty($Device_Epoch_Time)){
											$Diff_Error_Status = $Device_Epoch_Time;
//echo $Diff_Error_Status;
											/*if(0 > $Diff_Error_Status){
												$Status = $ED_Error_Array[$IMEI];
											}*/
										} //echo $Diff_Error_Status."     ".$Status." imei ".$IMEI."<br>";
										# More than 5 min not working
										$Req_Time = time()+(60*60*5.5);
										//echo $Status;
										$ReqTime_Diff = $Req_Time - $Device_Epoch_Time; //echo $ReqTime_Diff;
										//$ReqTime_Diff_Err = $Req_Time - $ED_Device_Epoch_Time_Array[$IMEI];
//print_r($Device_Name[$IMEI]);									# Checking data for 5 mins delay
										
						if($ReqTime_Diff >= 900 && (in_array($Status,$Error_Array['Green']) && !in_array($Status,$Error_Array['Blue']))){
											$Tower_Img = '<img src="./images/Grey_jpg.jpg" width="69px" height="98px" alt="brown Tower">';
											$Div_Img = "<div class='boxed-grey'>".$Device_Name[$IMEI]."</div>";
										}
										else
										{
											# Depence upon the Status Tower image
											
											if(in_array($Status,$Error_Array['Green'])){
												
												
													$WTG_Run++;
													

$Div_Img = "<div class='boxed-green'>".$Device_Name[$IMEI]."</div>";
$Tower_Img = '<img src="./images/6.gif" width="69px" height="98px" alt="Orange Tower">';

											}	
											elseif(in_array($Status,$Error_Array['Orange'])){
												$Div_Img = "<div class='boxed-orange'>".$Device_Name[$IMEI]."</div>";
												$Tower_Img = '<img src="./images/7.gif" width="69px" height="98px" alt="Orange Tower">';
												
												
											}	
											elseif(in_array($Status,$Error_Array['Blue'])){
												$Div_Img = "<div class='boxed-blue'>".$Device_Name[$IMEI]."</div>";
												$Tower_Img = '<img src="./images/Blue_jpg.jpg" width="69px" height="98px" alt="Blue Tower">';
												$Audio[]=$WEGno[$IMEI];
											
											}
											elseif(in_array($Status,$Error_Array['Pink'])){
												$Div_Img = "<div class='boxed-pink'>".$Device_Name[$IMEI]."</div>";
												$Tower_Img = '<img src="./images/18.jpg" width="69px" height="98px" alt="Pink Tower">';
												$Audio[]=$WEGno[$IMEI];
											
											}
											else{											
												$Div_Img = "<div class='boxed-red'>".$Device_Name[$IMEI]."</div>";
												$Tower_Img = '<img src="./images/Red_jpg.jpg" width="69px" height="98px" alt="Red Tower">';
												$Audio[]=$WEGno[$IMEI];
											}	
										}
									}
									
	
							$Date="";
							$Time="";
							//$Date_e = strtotime($ED_Error_Date[$IMEI]);
							//$Time_e = strtotime($ED_Error_Time[$IMEI]);
							$Date_G = strtotime($Date_S);
							$Time_G = strtotime($Time_S);

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
	
	
			$PreviousState=$CurrentState;
							
							if($CurrentState=="" || $CurrentState!=$Region["$IMEI"]){
							$td=0;	
							?>

							<tr>
						<td valign="top" align="left"  style="font-size:15px; font-weight:bold"; ><?= substr($Region["$IMEI"],0,15) ?></td>

							</tr>

 <tr>
							 <?php
							$CurrentState=$Region["$IMEI"];
							 } 
							?> 
						<td><table border=0>
                  
<tr> <td "10px"></td>&nbsp;</tr>
		
					
                                 
						<table border="0" cellpadding="0" cellspacing="0" >
                                            <tr>
	                                            <td align="left"><a href="<?=$Channel_Url?>" border="0" style="cursor:pointer; color:#333333; text-decoration:none;" target="_blank"; title="<?=$DeviceName[$IMEI]?> &nbsp;<?=$Date_S?>,<?=$Time_S?>,&nbsp;<?=$Site_Location[$IMEI]?>,&nbsp;<?=$G1?>, &nbsp;<?=$G2?>"><?=$Div_Img?></a></td>
                                            </tr>

					</table>
						
                                            
                            <?php
					$td++;
					
					if($td==8){
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
 <?php if(($_COOKIE['timer']<=1700 && $_COOKIE['timer']>=1550)    ) { ?> 
<script>
$(function () {
           //Find the audio control on the page
           var audio = document.getElementById('ctrlaudio');//alert(audio);
           //songNames holds the comma separated name of songs
           var songNames = document.getElementById('hdnSongNames').value;
           var lstsongNames = songNames.split(',');
           var curPlaying = 0;//alert(lstsongNames.length);
           // Attaches an event ended and it gets fired when current playing song get ended
           audio.addEventListener('ended', function() {
               var urls = audio.getElementsByTagName('source');
               // Checks whether last song is already run
               if (urls[0].src.indexOf(lstsongNames[lstsongNames.length - 1]) == -1) {
                   //replaces the src of audio song to the next song from the list
                   urls[0].src = urls[0].src.replace(lstsongNames[curPlaying], lstsongNames[++curPlaying]);
                   //Loads the audio song
                   audio.load();
                   //Plays the audio song
                   audio.play();
                   }
		
           });
       });
</script>

		<?php


			function arrayPrefix(&$value,$key) {
  			$value="Music/$value.wav";
			}
			array_walk($Audio,"arrayPrefix");

			$Audio_Str=implode(",",$Audio);
		?>
		

		 			</form>

<audio id="ctrlaudio" autoplay  runat="server" >
              <source src="<?= $Audio[0] ?>"></source> Your browser does not support the audio tag.
</audio>
<input type=hidden name=hdnSongNames id=hdnSongNames value="<?= $Audio_Str ?>">
<?php } ?>

        <br /> <br />
       </div>
    </div>
      </div>

	</center>
<!--<div  id="ref"   class="marquee" ><marquee behavior="scroll" scrollamount=1  direction="left" ><h3>WTG Run: <?=$WTG_Run."/".$Mysql_Record_Count ?></h3></marquee></div>-->

<?php


	//include("footer.php");
?>