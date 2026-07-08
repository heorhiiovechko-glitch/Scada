
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
//echo $Account_ID;
?>
<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script> 
 <script type="text/javascript">
    setInterval("my_function();",60000);
    function my_function(){
      $('#getdata').load('channel1_ch.php #getdata');
    }

  </script>

<!--<script>

// 200 seconds countdown

 function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}


//alert(countdown);
//confirm(getcookie("timer"));

	var countdown=0;
  	var counts=getCookie("timer");
  		if (counts!="" && counts>0 ) {
		countdown=counts;//alert(countdown);
   		}else{
			document.cookie = "timer=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
		        countdown = 1800;//alert(countdown);
		}

	//current timestamp
	var now   = Date.parse(new Date());

	//ready should be stored in your cookie
	var ready = Date.parse(new Date (now + countdown  * 1000)); // * 1000 to get ms


	//every 1000 ms
setInterval(function()
{


    var sec = ( ready - Date.parse(new Date()) )/1000;


	if(counts!=null && counts<=0 && sec<=0){
		document.cookie = "timer=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
		window.location.reload();
	}
	
	document.cookie="timer="+sec;
        document.getElementById("demo").innerHTML =  document.cookie;

},1000);

</script>

<script>
var seconds = 60;
function secondPassed() {
    var minutes = Math.round((seconds - 30)/60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds; 
    }
    document.getElementById('countdown').innerHTML = minutes + "&nbsp;" + ":" + "&nbsp;"+ remainingSeconds;
       
        if (seconds == 0) {
$('#getdata').load('channel1.php #getdata');
 seconds = 60;
    }
else {
        seconds--;
    }
}
 
var countdownTimer = setInterval('secondPassed()', 1000);
</script> -->
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
  <div id="body" class="clear" style="width:1285px;">

   <!-- <div class="box">-->
      <!--<em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>-->
	
     <!-- <div class="content">-->


          <form name="channel1_versatile" method="post" action="">
			<table border="0" cellpadding="2" cellspacing="2" width="100%">
            	
                	<td>
                        <table border="0" cellpadding="1" cellspacing="1" width="100%">
                            <tr>  <td width="40%">        
<!--<h2><?php  if($Account_ID!=96){?>Energy from <? }else{ echo "<img src=\"images/windmil-logo.jpg\" width=\"44\" height=\"44\" alt=\"yellow\">&nbsp;&nbsp;";  } ?><?= $Firstname." ".$Lastname?>!</h2></td>-->
<h2><?php  if($Account_ID==96){ echo "<img src=\"images/windmil-logo.jpg\" width=\"44\" height=\"44\" alt=\"yellow\">&nbsp;&nbsp;";  } elseif($Account_ID==100097) { echo "<img src=\"images/RRPL Logo_small1.jpg\" width=\"74\" height=\"74\" alt=\"yellow\">&nbsp;&nbsp;";  }else { ?>Energy from <? }?><?= $Firstname." ".$Lastname?>!</h2></td>

				    <td width="15px"><img src="images/11.jpg" width="24" height="24" alt="yellow"></td>
                                    <td width="90px">Null Wind</td>
                                    <td width="15px"><img src="images/12.jpg" width="24" height="24" alt="gren"></td>
                                    <td width="90px">WTG Run</td>
                                    <td width="15px"><img src="images/Red_jpg.jpg" width="24" height="24" alt="red"></td>
                                    <td width="100px">Error Stop</td>
                                    <td width="15px"><img src="images/Blue_jpg.jpg" width="24" height="24" alt="blue"></td>
                                    <td width="90px">Grid Drop</td>
				    <td width="15px"><img src="images/18.jpg" width="24" height="24" alt="blue"></td>
                                    <td width="90px">Key Pressed</td>
                                    <td width="15px"><img src="images/Grey_jpg.jpg" width="24" height="24" alt="yellow"></td>
                                    <td width="120px">No Communication</td>
				     <td width="300px">Gen Daily:Generation/Run Hrs/Stop Hrs</td>

				
                                  
                            </tr>
			 <!--   <tr><td>
				<?php
				if($Account_ID!=90){
				?>
      				    <p>windmill power generator color description</p>
				<?php
				}
				?>
			</td></tr>-->
                        </table>   
                    </td>
                </tr>
              <!--  <tr>
                <td height="30px"> <div id="countdown" class="timercount"></div> </td>
                </tr>-->
            	<tr>
                	<td><div id="getdata">
                    	<table border="0" cellpadding="1" cellspacing="1" width=100%>
                        	
                    	<?php				
					//print_r($Cook_Variable);
							$Date_Range = getDaysInBetween(date("d-m-Y"),date("d-m-Y"));//print_r($Date_Range);
							foreach($Date_Range as $Date_Range_Val){
							$Date_Range_Start = $Date_Range_Val[0];
							$Date_Range_End = $Date_Range_Val[1];
							}
							$WindSpeed = null;
							$Power = null;
							if($User_Type_ID ==3 || $User_Type_ID ==2)
						$Mysql_Query = "SELECT  t1.*, s.totalCount AS count 
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
							$Mysql_Query = "SELECT  t1.*, s.totalCount AS count 
								FROM  device_register AS t1 
 								 LEFT JOIN
								        (
						            SELECT Device_Index,State, COUNT(State)  totalCount
           							 FROM  device_register 
           						 WHERE  Account_ID = '".$Account_ID."'
          							  GROUP   BY State
       					 )  s ON s.State = t1.State   where t1.Account_ID = '".$Account_ID."'
ORDER   BY count desc,State desc,Device_Order asc";
//echo $Mysql_Query;
							if (!$queryResult2 = $db->query($Mysql_Query))
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
									
									#
									#	Alarm Log from ERROR_DATA
									#
									
								/*	$ED_Mysql_Query = "select Status,IMEI,Date_S,Time_S from $Database_Name.$Error_Table_Name where IMEI = '".$IMEI_Decode."'  order by Record_Index desc limit 1";//echo $ED_Mysql_Query;
									$ED_Mysql_Query_Result = mysql_query($ED_Mysql_Query) or die(mysql_error());
									$ED_Mysql_Record_Count = mysql_num_rows($ED_Mysql_Query_Result);
									if($ED_Mysql_Record_Count>=1){
										$ED_Fetch_Result = mysql_fetch_array($ED_Mysql_Query_Result);
										$ED_Error_Array[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Status'];
										$ED_Error_Date[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Date_S'];
										$ED_Error_Time[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Time_S'];

$ED_Device_Epoch_Time_Array[$ED_Fetch_Result['IMEI']] = GetTimestamp($ED_Error_Date[$ED_Fetch_Result['IMEI']],$ED_Error_Time[$ED_Fetch_Result['IMEI']]); 
									
									}*/





/*$ER_Mysql_Query = "(select Status as Log,Date_S,Time_S,IMEI from $Database_Name.$Table_Name where IMEI='".$IMEI_Decode."' group by IMEI order by Record_Index desc limit 1) union (select Status as Log,Date_S,Time_S,IMEI from $Database_Name.$Error_Table_Name where IMEI='".$IMEI_Decode."' group by IMEI order by Record_Index desc limit 1) order by Date_S desc,Time_S desc ";
	$ER_Mysql_Query_Result = mysql_query($ER_Mysql_Query) or die(mysql_error());
	$ER_Mysql_Record_Count = mysql_num_rows($ER_Mysql_Query_Result);
	if($ER_Mysql_Record_Count>=1){
		$ER_Fetch_Result = mysql_fetch_array($ER_Mysql_Query_Result);
		$Status[$ER_Fetch_Result['IMEI']] = $ER_Fetch_Result['Log'];
		$Date_Latest[$ER_Fetch_Result['IMEI']] = $ER_Fetch_Result['Date_S'];
		$Time_Latest[$ER_Fetch_Result['IMEI']] = $ER_Fetch_Result['Time_S'];			
	}*/



									

								/*if($Account_ID==200){
										$GAD_Time=" and Hour(Time_S)>=6 ";
										$GD_Time=time()-21660;
}
								elseif($Account_ID==100063){
										$GAD_Time=" and Hour(Time_S)>=7 ";
										$GD_Time=time()-25200;
}
									else {
										$GAD_Time="";
										$GD_Time=time();
$Test_Time=date('H',$GD_Time);
}*/
						if($Closing_Time[$IMEI_Decode]=='06:00:00' || $Closing_Time[$IMEI_Decode]=='06:30:00'){
										$GAD_Time=" and Hour(Time_S)>=6 ";
										$GD_Time=time()-21660;
									$Test_Time=date('H',$GD_Time);
}
								elseif($Closing_Time[$IMEI_Decode]=='07:00:00' || $Closing_Time[$IMEI_Decode]=='07:30:00'){
										$GAD_Time=" and Hour(Time_S)>=7 ";
										$GD_Time=time()-25200;
										$Test_Time=date('H',$GD_Time);
}								elseif($Closing_Time[$IMEI_Decode]=='08:00:00' || $Closing_Time[$IMEI_Decode]=='08:30:00'){
										$GAD_Time=" and Hour(Time_S)>=8 ";
										$GD_Time=time()-28800;
										$Test_Time=date('H',$GD_Time);
}								elseif($Closing_Time[$IMEI_Decode]=='09:00:00'){
										$GAD_Time=" and Hour(Time_S)>=9 ";
										$GD_Time=time()-32400;
}								elseif($Closing_Time[$IMEI_Decode]=='01:00:00' || $Closing_Time[$IMEI_Decode]=='01:30:00'){
										$GAD_Time=" and Hour(Time_S)>=1 ";
										$GD_Time=time()-3600;
}								elseif($Closing_Time[$IMEI_Decode]=='02:00:00' || $Closing_Time[$IMEI_Decode]=='02:30:00'){
										$GAD_Time=" and Hour(Time_S)>=2 ";
										$GD_Time=time()-7200;
}								elseif($Closing_Time[$IMEI_Decode]=='04:00:00' || $Closing_Time[$IMEI_Decode]=='04:30:00'){
										$GAD_Time=" and Hour(Time_S)>=4 ";
										$GD_Time=time()-7200;
}								/*elseif($Closing_Time[$IMEI_Decode]=='20:00:00' || $Closing_Time[$IMEI_Decode]=='20:40:00' || $Closing_Time[$IMEI_Decode]=='20:20:00'){
										$GAD_Time=" and Hour(Time_S)>=20 ";
										$GD_Time=time()-72000;
										
}								elseif($Closing_Time[$IMEI_Decode]=='22:00:00' || $Closing_Time[$IMEI_Decode]=='22:30:00'){
										$GAD_Time=" and Hour(Time_S)>=22 ";
										$GD_Time=time()-79200;
}								elseif($Closing_Time[$IMEI_Decode]=='23:00:00' || $Closing_Time[$IMEI_Decode]=='23:30:00'){
										$GAD_Time=" and Hour(Time_S)>=23 ";
										$GD_Time=time()-82800;
}*/								
					
									else {
										$GAD_Time="";
										$GD_Time=time();

}
//echo $Test_Time."</br>";
							$DATE_Val=date("Y-m-d");
							$Time_Val=date("H:i:s");
							$Date_dmy=date("d.m.Y",strtotime($DATE_Val));
							if($Closing_Time[$IMEI_Decode]=="00:00:00" || $Closing_Time[$IMEI_Decode]=="0" ){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=$Date_Stamp;
							$Yester_dmy=$Date_dmy;
							}
							/*elseif($Closing_Time[$IMEI_Val]>="10:00:00" || $Closing_Time[$IMEI_Val]=="10"){
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val));
							//$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}*/
							else{
							$Date_Stamp=date("Y-m-d",strtotime($DATE_Val));
							$Yester_Stamp=date("Y-m-d",strtotime($DATE_Val)-86400);
							$Yester_dmy=date("d.m.Y",strtotime($DATE_Val)-86400);
							}
						
//print_r($Closing_Time)."</br>";	
		
/*if($Time_Val < '08:00:00') { 															
		if($Format_Type==2 )
		$Mysql_Query1 ="select Date_S,Time_S, Windspeed as WindSpeed,Power ,Status, G4_Temp,G6_Temp,(select round((max(PAT_GEN1)-min(PAT_GEN1))+(max(PAT_GEN2)-min(PAT_GEN2)),2) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))<='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))>'$Closing_Time[$IMEI_Decode]' end)) as G1 , (select (max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))<='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))>'$Closing_Time[$IMEI_Decode]' end)) as G2 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";	
		elseif($Format_Type==4)
		$Mysql_Query1 ="select Date_S,Time_S,Windspeed as WindSpeed,Power,Status,(select round((max(PAT_Gen1)-min(PAT_Gen1))+(max(PAT_Gen2)-min(PAT_Gen2)),2) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Decode]' end)) as G1 , (select round((max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)),0) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))>='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))<'$Closing_Time[$IMEI_Decode]' end)) as G2  from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";	
		elseif($Format_Type==6 || $Format_Type==1)
		$Mysql_Query1="select Power,Date_S,Time_S,Windspeed as WindSpeed,Status,(select max(PAT_Gen2)-min(PAT_Gen2) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and  (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))<='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))>'$Closing_Time[$IMEI_Decode]' end) and PAT_Gen2 >'0' ) as G1,(select MAX(Run_Hours)-min(Run_Hours) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))<='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))>'$Closing_Time' end) and PAT_Gen2 >'0' and Run_Hours > '10' ) as G2   from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
		elseif($Format_Type==10)
		$Mysql_Query1 ="select Power,Date_S,Time_S,Windspeed as WindSpeed,Status,(select max(Production_Total)-min(Production_Total) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))<='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))>'$Closing_Time[$IMEI_Decode]' end) and Production_Total > '0' ) as G1,(select MAX(Line_Hours)-min(Line_Hours) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and (Date_S= '".$Date_Stamp."' OR  Date_S='". $Yester_Stamp ."')   and (case when (Date_S='$Date_Stamp') then  hour((cast(Time_S as time)))<='$Closing_Time[$IMEI_Decode]' else hour(cast(Time_S as time))>'$Closing_Time[$IMEI_Decode]' end) and Production_Total >'0' ) as G2   from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
		elseif($Format_Type==3)
		$Mysql_Query1 ="select  (select Date_S from $Database_Name.$Table_Name   where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as  Date_S,(select Time_S from $Database_Name.$Table_Name   where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as  Time_S, (select Windspeed from $Database_Name.$Table_Name   where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as WindSpeed,(select Power from $Database_Name.$Table_Name  where  IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as Power,(select Status from $Database_Name.$Table_Name  where  IMEI = '".$IMEI_Decode."' and Status!= '' order by Record_Index desc limit 1) as Status  ,(select max(Production_Total)-min(Production_Total) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Production_Total > '0' and ID_Number!=''  $GAD_Time) as G1, (select MAX(Total_Hours)-min(Total_Hours) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and  ID_Number!=''  $GAD_Time) as G2 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."'   and Date_S=curdate() order by Record_Index desc limit 1";
		elseif($Format_Type==7)
		$Mysql_Query1 ="select Windspeed as WindSpeed,Power,Status,Date_S,Time_S ,(select Kwh_Positive from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and PAT_Gen2 >'0' and ID_Number!=''  and Status !='' order by Record_Index desc) as G1,(select Operate_Hours from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and PAT_Gen2 >'0' and ID_Number!=''  and Status !='' order by Record_Index desc) as G2 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Status !='' order by Record_Index desc limit 1";
		elseif($Format_Type==8)
		$Mysql_Query1 ="select Windspeed as WindSpeed,Power,Status,Date_S,Time_S,Kwh_Positive G1,Operate_Hours as G2 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Status !='' order by Record_Index desc limit 1";
		else
		$Mysql_Query1 ="select Date_S,Time_S,WindSpeed,Power,Status from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
} else { 
		if($Format_Type==2 )
		$Mysql_Query1 ="select Date_S,Time_S, Windspeed as WindSpeed,Power ,Status, G4_Temp,G6_Temp,(select round((max(PAT_Gen1)-min(PAT_Gen1))+(max(PAT_Gen2)-min(PAT_Gen2)),2) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and ID_Number!='' $GAD_Time) as G1 , (select (max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and ID_Number!='' $GAD_Time) as G2,(select round((max(RPhase_Current)-min(RPhase_Current)),2) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and ID_Number!=''  and Date_S=curdate() $GAD_Time) as G3 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";	
		elseif($Format_Type==4)
		$Mysql_Query1 ="select Date_S,Time_S,Windspeed as WindSpeed,Power,Status,(select round((max(PAT_Gen1)-min(PAT_Gen1))+(max(PAT_Gen2)-min(PAT_Gen2)),2) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate()  and ID_Number!='' $GAD_Time) as G1 , (select round((max(Gen1_Hours)-min(Gen1_Hours))+(max(Gen2_Hours)-min(Gen2_Hours)),0) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and ID_Number!='' $GAD_Time) as G2  from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";	
		elseif($Format_Type==6 || $Format_Type==1)
		$Mysql_Query1="select Power,Date_S,Time_S,Windspeed as WindSpeed,Status,(select max(PAT_Gen2)-min(PAT_Gen2) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and PAT_Gen2 >'0' and ID_Number!='' $GAD_Time) as G1,(select MAX(Run_Hours)-min(Run_Hours) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and PAT_Gen2 >'0' and Run_Hours > '10' and ID_Number!='' $GAD_Time) as G2   from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
		elseif($Format_Type==10)
		$Mysql_Query1 ="select Power,Date_S,Time_S,Windspeed as WindSpeed,Status,(select max(Production_Total)-min(Production_Total) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Production_Total > '0' and ID_Number!='' $GAD_Time) as G1,(select MAX(Line_Hours)-min(Line_Hours) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Production_Total >'0' and ID_Number!='' $GAD_Time) as G2   from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Status !='' order by Record_Index desc limit 1";
		elseif($Format_Type==3)
		$Mysql_Query1 ="select  (select Date_S from $Database_Name.$Table_Name   where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as  Date_S,(select Time_S from $Database_Name.$Table_Name   where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as  Time_S, (select Windspeed from $Database_Name.$Table_Name   where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as WindSpeed,(select Power from $Database_Name.$Table_Name  where  IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1) as Power,(select Status from $Database_Name.$Table_Name  where  IMEI = '".$IMEI_Decode."' and Status!= '' order by Record_Index desc limit 1) as Status  ,(select max(Production_Total)-min(Production_Total) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Production_Total > '0' and ID_Number!=''  $GAD_Time) as G1, (select MAX(Total_Hours)-min(Total_Hours) from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and  ID_Number!=''  $GAD_Time) as G2 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."'   and Date_S=curdate() order by Record_Index desc limit 1";
		elseif($Format_Type==7)
		$Mysql_Query1 ="select Windspeed as WindSpeed,Power,Status,Date_S,Time_S ,(select Kwh_Positive from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and ID_Number!=''  and Status !='' order by Record_Index desc) as G1,(select Operate_Hours from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and ID_Number!=''  and Status !='' order by Record_Index desc) as G2 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Status !='' order by Record_Index desc limit 1";
		elseif($Format_Type==8)
		$Mysql_Query1 ="select Windspeed as WindSpeed,Power,Status,Date_S,Time_S ,(select Kwh_Positive from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and ID_Number!=''  and Status !='' order by Record_Index desc) as G1,(select Operate_Hours from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and ID_Number!=''  and Status !='' order by Record_Index desc) as G2 from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' and Date_S=curdate() and Status !='' order by Record_Index desc limit 1";
		else 
		$Mysql_Query1 ="select Date_S,Time_S,WindSpeed,Power,Status from $Database_Name.$Table_Name where IMEI = '".$IMEI_Decode."' order by Record_Index desc limit 1";
									//echo $Mysql_Query1;	
		}*/
									//echo $Mysql_Query1;	
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
//echo $G2."</br>";
//echo $GD_Hours."</br>";	
//echo $Date_F."</br>";
//echo $Time_F."</br>";			
										
										$Device_Epoch_Time=GetTimestamp($Date_F,$Time_F);

										if(!empty($Device_Epoch_Time)){
											$Diff_Error_Status = $Device_Epoch_Time;
											/*if(0 > $Diff_Error_Status){
												$Status = $ED_Error_Array[$IMEI_Decode];
											}*/
										}//echo $Diff_Error_Status."     ".$Status." imei ".$IMEI."<br>";
										# More than 5 hours not working
										$Req_Time = time()+(60*60*5.5);
										//echo $Status;
										$ReqTime_Diff = $Req_Time - $Device_Epoch_Time;
										//$ReqTime_Diff_err = $Req_Time - $ED_Device_Epoch_Time_Array[$IMEI_Decode];				
// echo $ReqTime_Diff;
										# Checking data for 5 hours delay
										/*if($Format_Type==9)										$Tower_Img = '<img src="./images/solar_power.jpg" alt="Solar Power">';

										else*/

//echo $ED_Device_Time_Array[$IMEI_Decode] ."</br>";
//echo $Device_Epoch_Time ."</br>";
//echo $ReqTime_Diff."</br>";
//echo $ReqTime_Diff_err."</br>";
//echo $Status."</br>";
										if($ReqTime_Diff >= 900 && (in_array($Status,$Error_Array['Green']) && !in_array($Status,$Error_Array['Blue']))){
											$Tower_Img = '<img src="./images/Grey_jpg.jpg" width="59px" height="108px" alt="brown Tower">';
										}
										else
										{
											# Depence upon the Status Tower image
											
											if(in_array($Status,$Error_Array['Green'])){
												
												if($Power == '000' || $Power == '0' || $Power < 0){
													//$Tower_Img = '<img src="./images/7.jpg" width="69px" height="98px" alt="Orange Tower">';

													$WTG_Run++;

$Tower_Img = '<img src="./images/7.gif" width="59px" height="108px" alt="orange Tower">';
													

													

												}
												else{
													$WTG_Run++;
													
$Tower_Img = '<img src="./images/6.gif" width="59px" height="108px" alt="Green Tower">';

												}	
											}	
											elseif(in_array($Status,$Error_Array['Orange'])){
												$Tower_Img = '<img src="./images/7.gif" width="59px" height="108px" alt="Orange Tower">';
												
												
												
											}	
											elseif(in_array($Status,$Error_Array['Blue'])){
												$Tower_Img = '<img src="./images/Blue_jpg.jpg" width="59px" height="108px" alt="Blue Tower">';
												$Audio[]=$WEGno[$IMEI_Decode];
											
											}
											elseif(in_array($Status,$Error_Array['Pink'])){
												$Tower_Img = '<img src="./images/18.jpg" width="59px" height="108px" alt="Pink Tower">';
												$Audio[]=$WEGno[$IMEI_Decode];
											
											}
											else{											
												$Tower_Img = '<img src="./images/Red_jpg.jpg" width="59px" height="108px" alt="Red Tower">';
												$Audio[]=$WEGno[$IMEI_Decode];
											}	
										}
									}
									
									

							$PreviousState=$CurrentState;
							
							if($CurrentState=="" || $CurrentState!=$State["$IMEI_Decode"]){
							$td=0;	
							?>
							<tr>
	
								<td valign="top"  colspan=2><h2><?= $State["$IMEI_Decode"] ?></h2></td>

							</tr><tr>
							<?php
							
							$CurrentState=$State["$IMEI_Decode"];
							 }
							?> 
							 
							<?php
							
			                      /* if($PreviousState==$CurrentState && $CurrentSite!=$Site_Location["$IMEI_Decode"] && $CurrentSite!="" && $td<5 && $td!=0){ 
							$td++;
							$PreviousState="";//echo $td;
							//echo "<table><tr><td></td></tr></table></td>";
							
							//else 
							//echo "<td>";
							}  */      
						?> 	
                                    			  <td><table border=0 >
                                   
						<?php
							if($Account_ID=='100079' || $Account_ID=='100081' || $Account_ID=='100082' || $Account_ID=='100084' || $Account_ID=='100077' ) {

							//if($CurrentSite=="" || $CurrentSite!=$Site_Location["$IMEI_Decode"] ){
							$Feeder_List=implode(",",$Connect_Feeder[$Site_Location["$IMEI_Decode"]]);
						?>
							
	
								<tr><td valign="top" ><h3><?= $Site_Location["$IMEI_Decode"]?></h3></td></tr>
							
<?php
							$CurrentSite=$Site_Location["$IMEI_Decode"];
							//}else{
							//echo "<tr><td valign=\"top\" ><h3>&nbsp;</h3></td></tr>";
							//}
						} else {
							if($CurrentSite=="" || $CurrentSite!=$Site_Location["$IMEI_Decode"] ){
							$Feeder_List=implode(",",$Connect_Feeder[$Site_Location["$IMEI_Decode"]]);
						?>
							
	
								<tr><td valign="top" ><h3><?= $Site_Location["$IMEI_Decode"]?></h3></td></tr>
							
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
							//$Date_e = strtotime($ED_Error_Date[$IMEI_Decode]);
							//$Time_e = strtotime($ED_Error_Time[$IMEI_Decode]);
							$Date_G = strtotime($Date_F);
							$Time_G = strtotime($Time_F);

							/*if(!in_array($Status,$Error_Array['Green']) && $ReqTime_Diff < 3600 ){
							$Date = date('d/m/Y', $Date_e);
							$Time = date('H:i:s', $Time_e);
							
								
							}
if(in_array($Status,$Error_Array['Green']) && $ReqTime_Diff < 3600){

							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);							
								
							}
if(in_array($Status,$Error_Array['Blue'])) {

$Date = date('d/m/Y', $Date_e);
							$Time = date('H:i:s', $Time_e);							
							}*/


							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);
	

							?>
					<td>
                                   <?= $Connect_Feeder[$Site_Location["$IMEI_Decode"]]?>
                                         <table border="0" cellpadding="0" cellspacing="0" style="border:solid 2px #009999;" width="206px" height="260px">
                                            <tr>
	                                            <td align="center"><?= $Date?><a href="<?=$Channel_Url?>c1=<?=$IMEI?>&l=<?=$Pocket_Length?>&FType=<?=$Format_Type?>" border="0" style="cursor:pointer; color:#333333; text-decoration:none;" target="_blank"; title="IMEI :<?=$IMEI_Decode?>"><?=$Tower_Img?></a><?= $Time?></td>
                                            <tr>
					
						
                                            <tr>
                                            	<td>
                                                	<table border="0" cellpadding="0" cellspacing="5" width="100%" style="background-color:#b3ecff; font-size:10px">
						
                                                    <?php
							if($Device_Name[$IMEI_Decode]=='ICE MAN'){
							
							?>	<tr>
								<td class="tower-txt" width="30%">HTSC No</td>
			                                            	<td class="tower-txt">: <?=$HTSCno?></td>			                                 </tr>
							<tr>
                                                            <td class="tower-txt" width="30%" >Name</td>
                                                            <td class="tower-txt"  >: <?=$Device_Name[$IMEI_Decode]?></td> 
                                                         </tr> 
														 <tr>
                                                        	<td class="tower-txt"  width="30%">Total KW</td>
			                                            	<td class="tower-txt">: <?=$G4_Temp?> kW</td>
                                                        </tr>
							
                                                    	
							<?php
							} elseif($Device_Name[$IMEI_Decode]=='Selva Tex 250kw'){
							
							?>	<tr>
								<td class="tower-txt" width="30%">HTSC No</td>
			                                            	<td class="tower-txt">: <?=$HTSCno?></td>			                                 </tr>
							<tr>
                                                            <td class="tower-txt" width="30%" >Name</td>
                                                            <td class="tower-txt"  >: <?=$Device_Name[$IMEI_Decode]?></td> 
                                                         </tr> 
														 <tr>
                                                        	<td class="tower-txt"  width="30%">Total KW</td>
			                                            	<td class="tower-txt">: <?=$G6_Temp?> kW</td>
                                                        </tr>
														 <tr>
                                                        	<td class="tower-txt"  width="30%">GAD</td>
			                                            	<td class="tower-txt">: <?=$G3?> kWh</td>
                                                        </tr>
														<tr>
                                                            <td class="tower-txt" width="30%" >Status</td>
                                                            <td class="tower-txt"  >: <?=$Status?></td> 
                                                         </tr> 
							
                                                    	
							<?php
							}
							else {
							?>
														 <tr>
								<td class="tower-txt" width="30%">HTSC No</td>
			                                            	<td class="tower-txt">: <?=$HTSCno?></td>			                                 </tr>
							<tr>
                                                        	<td class="tower-txt"  width="30%">Speed</td>
			                                            	<td class="tower-txt">: <?=$WindSpeed?> m/s</td>
                                                        </tr>
							
                                                    	<tr>
                                                            <td class="tower-txt" width="30%" >Power</td>
                                                            <td class="tower-txt">: <?=$PV_Instant_Power!=''?$PV_Instant_Power:$Power?> KW</td> 
                                                         </tr>
                                                    	<tr>
                                                            <td class="tower-txt" width="30%" >Name</td>
                                                            <td class="tower-txt"  >: <?=$Device_Name[$IMEI_Decode]?></td> 
                                                         </tr> 
							<?php
							if($Format_Type==9){
							
							?><tr>
                                                        	<td class="tower-txt"  width="30%"  style="font-size:14px;">Output Voltage</td>
			                                            	<td class="tower-txt" style="font-size:14px;">: <?=$Voltage?> Volts</td>
                                                        </tr>
                                                    	
                                                    	 
							<?
							}
							?>
                                                    	<tr>
                                                            <td class="tower-txt"  width="30%">Gen.Daily</td>
								<?
		if($Format_Type == 1 || $Format_Type == 2 || $Format_Type == 6 || $Format_Type == 10 || $Format_Type == 3 || $Format_Type == 7 || $Format_Type == 8 || $Format_Type == 4 ){
								if($Format_Type == 2){								
								if($G1 > 6000 || $G1 < 0)
								$G1 = 0;
												
								if($G2 > 24 || $G2< 0)
								$G2 = 0;
								}
								if($Format_Type == 1  ||  $Format_Type == 6){								
								if($G1 > 25000 || $G1 < 0)
								$G1 = 0;
												
								if($G2 > 24 || $G2< 0)
								$G2 = 0;
								}
								if($Format_Type == 7  ||  $Format_Type == 8){								
								if($G1 > 25000 || $G1 < 0)
								$G1 = 0;
												
								if($G2 > 24 || $G2< 0)
								$G2 = 0;
								}
								if($Format_Type == 3  ||  $Format_Type == 10){	
								if($G2 > 24 || $G2< 0)
								$G2 = 0;
								}								
								if($GD_Hours > "23" || $GD_Hours < "00" || $G2 > $Test_Time){
								$GD_Hours = "00";
								}
								?>
                                                            <td class="tower-txt">:
								 <? 
								echo $G1 != ''?$G1 : '0';
         							echo " kwh/";
         						        echo $G2 != ''?$G2 : '0';
       								echo "h/";
							        echo $GD_Hours != ''?$GD_Hours : '0';
								echo "h";
       														
							?></td> 
								<?
								}elseif($Format_Type == 9){
								?>
								

                                                            <td class="tower-txt" style="font-size:14px;">: <?=$Daily_Generated_Units?> Kwh</td> 
                                                         
								<?
								}									
								?>						
								
                                                         </tr>
														 <?php
							}
							?>
							
                                                   <!-- 	<tr>
                                                            <td class="tower-txt" <?php	if($Account_ID==90){?> style="font-size:14px;" <?php } ?>>Gen.Monthly</td>
								<?
								if($Format_Type==2 ){
								?>
                                                            <td class="tower-txt" >: 
								 <?=round($GA_Month[$IMEI_Decode],2)?> kwh / <?=round($GA_Month_HR[$IMEI_Decode],2)?> h</td> 
								<?
								}elseif($Format_Type == 6 || $Format_Type == 1  || $Format_Type == 9){
															
								if($GA_Month[$IMEI_Decode] > 400000 || $GA_Month[$IMEI_Decode] < 0)
								$GA_Month = 0;
												
								if($GA_Month_HR[$IMEI_Decode] > 744 || $GA_Month_HR[$IMEI_Decode]< 0)
								$GA_Month_HR = 0;
								
								?>
                                                            <td class="tower-txt" <?php	if($Account_ID==90){?> style="font-size:14px;" <?php } ?>>:
								 <? 
								echo round($GA_Month[$IMEI_Decode],2) != ''? $GA_Month[$IMEI_Decode] : '0';
         							echo " kwh /";
         						        echo round($GA_Month_HR[$IMEI_Decode],2) != ''?$GA_Month_HR[$IMEI_Decode] : '0';
       								echo " h";
							?></td> 
								<?
								}elseif($Format_Type == 10 ){
								?>
                                                           <td class="tower-txt" >: 
							 <?=round($GAM,2)?> kwh / <?=round($GAM_HR,2)?> h</td>						
								<? } ?>
                                                         </tr>
							
                                                    	<tr>
                                                            <td class="tower-txt" <?php	if($Account_ID==90){?> style="font-size:14px;" <?php } ?>>Gen.Yearly</td>
								<?
								if( $Format_Type==2){
								?>
                                                            <td class="tower-txt">: 
								 <?= round($GA_Year[$IMEI_Decode],2)?> kwh / <?= round($GA_Year_HR[$IMEI_Decode],2)?> h</td> 
								<?
								
								}elseif($Format_Type == 1 || $Format_Type == 6 || $Format_Type == 9  ){
															
								if($GA_Year[$IMEI_Decode] > 1500000 || $GA_Year[$IMEI_Decode] < 0)
								$GA_Year = 0;
												
								if($GA_Year_HR[$IMEI_Decode] > 8760 || $GA_Year_HR[$IMEI_Decode]< 0)
								$GA_Year_HR = 0;
								
								?>
                                                            <td class="tower-txt" <?php	if($Account_ID==90){?> style="font-size:14px;" <?php } ?>>:
								 <? 
								echo round($GA_Year[$IMEI_Decode],2) != ''?$GA_Year[$IMEI_Decode] : '0';
         							echo " kwh /";
         						        echo round($GA_Year_HR[$IMEI_Decode],2) != ''?$GA_Year_HR[$IMEI_Decode] : '0';
       								echo " h";							?></td> 
								<?
								}
								if( $Format_Type==10){
								?>
                                                            <td class="tower-txt">: 
								 <?= round($GAY,2)?> kwh / <?= round($GAY_HR,2)?> h</td> 
								<?
								
								}									
								?>						
								
                                                         </tr>-->
                                                   
                                                          
                                                    </table>
                                                </td>           
                                            </tr>
                                        </table>
                                    </td></tr>
 				</table>
                                    </td>
                            <?php
					$td++;
					
					if($td==5 ){
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

        <?php if($Format_Type != 9){?>  <p >Please click to any windmill power generator to see the detailed information</p><?php } ?> <br /> <br />
      <!-- </div>
    </div>-->
<div  id="ref"   class="marquee" ><marquee behavior="scroll" scrollamount=1  direction="left" ><h3>Total Power: <?= $Total_Power?>KW,  Total Export: <?= $Total_Export?>kwh, WTG Run: <?=$WTG_Run."/".$Mysql_Record_Count ?></h3></marquee></div>
      </body>
	</center>

<?php


	//include("footer.php");
?>