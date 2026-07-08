<?php

	include("header_inner.php");
	if(empty($_COOKIE[$Cook_Name])){
		header('Location: index.php');
		exit;
	}
?>
<?php
	error_reporting(-1);

	$From_D_Epoch = $_REQUEST['From'];
	//$DB_Name = $_REQUEST['DB'];
	//echo $_REQUEST['From'];
	$Extract_Date = date("Y-m-d",$From_D_Epoch);
	$From_Date = date("Y-m-d",$From_D_Epoch);
	$Extract_Date1=date("d-M-y",$From_D_Epoch);
	$From_Type1=date("d-M-y",$From_D_Epoch);
	$Extract_Date2=date("y/m/d",$From_D_Epoch);
	$Extract_Date6=date("j-n-y",$From_D_Epoch);
	$From_Type6=date("j-n-y",$From_D_Epoch);
	$Extract_Date10=date("y-M-d",$From_D_Epoch);
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
//echo $Extract_Date2;
//print_r($Epoch_OneHour_Arr);
$firstvalind=0;
 $zerovalind=0;
$lastvalind=0;
$firstvalcount=0;
//if cookie is set

if(isset($_REQUEST['c1'])){	
		$To_D_Epoch = $_REQUEST['To'];
		$To_Date = date("Y-m-d",$To_D_Epoch);
		$To_Type1=date("d-M-y",$To_D_Epoch);
		$To_Type6=date("j-n-y",$To_D_Epoch);
		$IMEI = base64_decode($_REQUEST['c1']);
		
		
		
		#	Getting IMEI 
		$Mysql_Query = "select * from device_register where IMEI = '$IMEI' group by IMEI";
		//echo $Mysql_Query;
		if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	
				$Capacity =  "500";
				$Format_Type= $Fetch_Result['Format_Type'];
				$Device_Name=$Fetch_Result['Device_Name'];
			}//end while
		}//endif
		//echo $Mysql_Query;

		 $dataWindSpeed = array();
                            $dataPower = array();
                            $dataVar = array();
                            $refCurve500 = array();
                            $refCurve600 = array();
							$refCurve850 = array();
                            $refCurve = array();
                          
						 if(empty($refCurve500))
                            {
                                $refCurveSelQuery = "SELECT WIND, POWER FROM powercurveref_500kw;";
                              if (!$refCurveSelQuery_Result = $db->query($refCurveSelQuery))
            {
                die($db->error);
            }

            if($refCurveSelQuery_Result->num_rows >= 1)
            {
				$iter = 1;
              while($refCurveSelFetchResult = $refCurveSelQuery_Result->fetch_array()) 
			  {	
				                        $windSpeed = $refCurveSelFetchResult['WIND'];
                                        $windSpeed = str_replace('m/s','',$windSpeed);
                                        $windSpeed != ''? $windSpeed = number_format($windSpeed,2) : $windSpeed = '0.00';
                                        if($windSpeed < 27.00)
                                        {
                                            $genPower = number_format($refCurveSelFetchResult['POWER'], 2);
                                            $refCurve500[] = "[$windSpeed, $genPower]";
                                        }
              }
            }
                            }

                            if(empty($refCurve600))
                            {
                                $refCurveSelQuery = "SELECT WIND, POWER FROM powercurveref_600kw;";
                                  if (!$refCurveSelQuery_Result = $db->query($refCurveSelQuery))
            {
                die($db->error);
            }

            if($refCurveSelQuery_Result->num_rows >= 1)
            {
				$iter = 1;
              while($refCurveSelFetchResult = $refCurveSelQuery_Result->fetch_array()) {
                                        $windSpeed = $refCurveSelFetchResult['WIND'];
                                        $windSpeed = str_replace('m/s','',$windSpeed);
                                        $windSpeed != ''? $windSpeed = number_format($windSpeed,2) : $windSpeed = '0.00';
                                        if($windSpeed < 27.00)
                                        {
                                            $genPower = number_format($refCurveSelFetchResult['POWER'], 2);
                                            $refCurve600[] = "[$windSpeed, $genPower]";
                                        }
                                    }
                                }
                            }

                            //echo $Capacity;
                            if($Capacity == 500)
                            {
                                if(!empty($refCurve500))
                                {
                                    $refCurve = $refCurve500;
                                }
                                else
                                {
                                    echo("reference curve for 500kw is empty!!");
                                    die();
                                }
                            }
                            elseif($Capacity == 600)
                            {
                                if(!empty($refCurve600))
                                {
                                    $refCurve = $refCurve600;
                                }
                                else
                                {
                                    echo("reference curve for 600kw is empty!!");
                                    die();
                                }
                            }
							elseif($Capacity == 850)
                            {
                                if(!empty($refCurve850))
                                {
                                    $refCurve = $refCurve850;
                                }
                                else
                                {
                                    echo("reference curve for 850kw is empty!!");
                                    die();
                                }
                            }
                            else
                            {
                                echo("unknown capacity specified : $capacity");
                                die();
                            }
//Getting All Hours Calculation

if($Format_Type==1)
$Mysql_Query="select Power,Windspeed, Date,Time  from $Cook_Variable[7].device_data where IMEI = '".$IMEI."' and STR_TO_DATE(Date,'%d-%M-%y') between STR_TO_DATE('".$From_Type1."','%d-%M-%y') and STR_TO_DATE('".$To_Type1."','%d-%M-%y') order by STR_TO_DATE(Date,'%d-%M-%y') desc,Time desc";
elseif($Format_Type==2)
$Mysql_Query="select Power,Windspeed, Date,Time from $Cook_Variable[7].device_data_f2 where IMEI = '".$IMEI."' and Date_S='$Extract_Date2' order by Record_Index asc";
elseif($Format_Type==6)
$Mysql_Query="select Power,Windspeed, Date,Time from $Cook_Variable[7].device_data_f6 where IMEI = '".$IMEI."' and STR_TO_DATE(Date,'%e-%c-%y') between STR_TO_DATE('".$From_Type6."','%e-%c-%y') and STR_TO_DATE('".$To_Type6."','%e-%c-%y')  order by STR_TO_DATE(Date,'%e-%c-%y') desc,hour(Time_Format(Replace(Time,'-',':'),'%k:%i:%s')) desc,minute(Time_Format(Replace(Time,'-',':'),'%k:%i:%s')) desc";
elseif($Format_Type==10)
$Mysql_Query="select Power,Windspeed, Date,Time from $Cook_Variable[7].device_data_f10 where IMEI = '".$IMEI."' and Date='$Extract_Date10' order by Record_Index asc";
elseif($Format_Type==3)
$Mysql_Query="select Power,Windspeed, Date,Time from $Cook_Variable[7].device_data_f3 where IMEI = '".$IMEI."' and Date='$Extract_Date3' order by Record_Index asc";
		//echo $Mysql_Query;
		if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }

            if($Mysql_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {	
				
									$windSpeed = $Fetch_Result['Windspeed'];
                                    $windSpeed = str_replace('m/s','',$windSpeed);
                                    $windSpeed != ''? $windSpeed = number_format($windSpeed,2) : $windSpeed = '0.00';
                                    $dataWindSpeed[] = $windSpeed;
                                    $genPower = number_format($Fetch_Result['Power'],2);
                                    $dataPower[] = $genPower;
                                    $totalPower = $genPower;
                                    $dataVar[] = "[$windSpeed, $genPower]";

			}//end while
		}//end if
									 $dataWindSpeed = array_filter($dataWindSpeed, function($x) { return $x !== ''; });
			    $avgWindSpeed = number_format(array_sum($dataWindSpeed) / count($dataWindSpeed), 2);

}//end if cookie

?>

<center>
	  <div id="body" class="clear" style="width:1000px;">
    <div class="box">
      <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>
      <div class="content">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <td  width="50%">
          <h2>Energy from Versatile SCADA Detailed Information!</h2>
          <p>about Status, Temperatures, Electrical, Production Figures</p>
      </td>
	  <td width="20%">
	  <h2><?=$Device_Name?></h2>
	  </td>
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

else
$Filename="channel3.php";

?>

     <td  width="30%" align="right"><a href="<?=  $Filename  ?>"><img src="images/back_btn.png" height="40px" width="40px" /></a></td>
      </table> 
    		<tr><td height="20%"><h3 align="center"><?=$From_Date?> To <?=$To_Date?></h3></td></tr><tr><td>
		<div id="PowerCurve" style="width:800px; height: 470px;"></div>
	 </td>
               </tr>
<!--<tr><td>
		<div id="PowerCurveRef" style="width:800px; height: 470px;"></div>
	 </td>
               </tr>-->			
          </table>         
          
          <div style="width:100%">&nbsp;</div>

          <p class="hr" style="float:left">&nbsp;</p><br />
        </div>
      </div>
    
    </div>
	</center>
	

    <script type="text/javascript" src="./js/chartjs.js"></script>
	<script src="./js/highcharts.js"></script>
	<script src="./js/exporting.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>    
  <script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script>
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/StackBlur.js"></script>
<script type="text/javascript" src="./js/html2canvas.js"></script>
<script type="text/javascript" src="./js/FileSaver.js"></script>
<script type="text/javascript" src="./js/jspdf.js"></script>
<script type="text/javascript" src="./js/jspdf.plugin.addimage.js"></script> 

 <script src="./js/jquery-1.10.2.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/jquery.metisMenu.js"></script>
    <script src="./js/pace.js"></script>
    <script src="./js/siminta.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <!-- Page-Level Plugin Scripts-->
    <script src="./js/jquery.dataTables.js"></script>
    <script src="./js/dataTables.bootstrap.js"></script>
 <script>
  google.charts.load('current', {packages: ['corechart']});  
</script>
  
	<script type="text/javascript">
        Highcharts.chart('PowerCurve', {
            chart: {
                type: 'scatter',
                zoomType: 'xy'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                title: {
                    enabled: true,
                    text: 'WindSpeed (m/s)'
                },
                startOnTick: true,
                endOnTick: true,
                showLastLabel: true
            },
            yAxis: {
                title: {
                    text: 'Power (kw)'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
                borderWidth: 1
            },
            
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<b>{series.name}</b><br>',
                        pointFormat: '{point.x} (m/s), {point.y} (kw)'
                    }
                }
            },
            series: [{
                name: 'WindSpeed',
                //color: 'rgba(223, 83, 83, .5)',
                color: 'green',
               
               data: [<?php echo join($dataVar, ',') ?>]
                }, {
                name: 'Capacity',
                //color: 'rgba(119, 152, 191, .5)',
                color: '#FAF619',
                /*
                data: [[0,0],[0.1,0],[0.2,0],[0.3,0],[0.4,0],[0.5,0],[0.6,0],[0.7,0],[0.8,0],[0.9,0],[1.0,0],[1.1,0],[1.2,0],[1.3,0],[1.4,0],[1.5,0],[1.6,0],[1.7,0],[1.8,0],[1.9,0],[2.0,0],[2.1,0],[2.2,0],[2.3,0],[2.4,0],[2.5,0],[2.6,2.4],[2.7,4.8],[2.8,7.2],[2.9,9.6],[3,12.0],[3.1,17.2],[3.2,22.4],[3.3,27.6],[3.4,32.8],[3.5,38.0],[3.6,43.2],[3.7,48.4],[3.8,53.6],[3.9,55.8],[4,64.0],[4.1,69.2],[4.2,74.4],[4.3,79.6],[4.4,84.8],[4.5,90.0],[4.6,95.2],[4.7,100.4],[4.8,105.6],[4.9,110.8],[5,116.0],[5.1,121.2],[5.2,126.4],[5.3,131.6],[5.4,136.8],[5.5,142.0],[5.6,147.2],[5.7,152.4],[5.8,157.6],[5.9,162.8],[6,168],[6.1,173.2],[6.2,178.4],[6.3,183.6],[6.4,188.8],[6.5,194],[6.6,199.2],[6.7,204.4],[6.8,209.6],[6.9,214.8],[7,220],[7.1,225.2],[7.2,230.4],[7.3,235.6],[7.4,240.8],[7.5,246.0],[7.6,251.2],[7.7,256.4],[7.8,261.6],[7.9,266.8],[8,272.0],[8.1,277.2],[8.2,282.4],[8.3,287.6],[8.4,292.8],[8.5,298.0],[8.6,303.2],[8.7,308.4],[8.8,313.6],[8.9,318.8],[9,324.0],[9.1,329.2],[9.2,334.4],[9.3,339.6],[9.4,344.8],[9.5,350],[9.6,355.2],[9.7,360.4],[9.8,365.6],[9.9,370.2],[10,376],[10.1,381.2],[10.2,386.4],[10.3,391.6],[10.4,396.8],[10.5,402.0],[10.6,407.2],[10.7,412.4],[10.8,417.6],[10.9,422.8],[11,428],[11.1,433.2],[11.2,438.4],[11.3,443.6],[11.4,448.8],[11.5,454],[11.6,459.2],[11.7,464.4],[11.8,469.6],[11.9,474.8],[12,480],[12.1,485.2],[12.2,490.4],[12.3,495.6],[12.4,500.8],[12.5,506],[12.6,511.2],[12.7,516.4],[12.8,521.6],[12.9,526.8],[13,532],[13.1,537.2],[13.2,542.4],[13.3,547.6],[13.4,552.8],[13.5,558],[13.6,563.2],[13.7,568.4],[13.8,573.6],[13.9,578.8],[14,584],[14.1,587.2],[14.2,590.4],[14.3,593.6],[14.4,596.8],[14.5,600],[14.6,600],[14.7,600],[14.8,600],[14.9,600],[15,600],[15.1,600],[15.2,600],[15.3,600],[15.4,600],[15.5,600],[15.6,600],[15.7,600],[15.8,600],[15.9,600],[16,600],[16.1,600],[16.2,600],[16.3,600],[16.4,600],[16.5,600],[16.6,600],[16.7,600],[16.8,600],[16.9,600],[17,600],[17.1,600],[17.2,600],[17.3,600],[17.4,600],[17.5,600],[17.6,600],[17.7,600],[17.8,600],[17.9,600],[18,600],[18.1,600],[18.2,600],[18.3,600],[18.4,600],[18.5,600],[18.6,600],[18.7,600],[18.8,600],[18.9,600],[19,600],[19.1,600],[19.2,600],[19.3,600],[19.4,600],[19.5,600],[19.6,600],[19.7,600],[19.8,600],[19.9,600],[20,600],[20.1,600],[20.2,600],[20.3,600],[20.4,600],[20.5,600],[20.6,600],[20.7,600],[20.8,600],[20.9,600],[21,600],[21.1,600],[21.2,600],[21.3,600],[21.4,600],[21.5,600],[21.6,600],[21.7,600],[21.8,600],[21.9,600],[22,600],[22.1,600],[22.2,600],[22.3,600],[22.4,600],[22.5,600],[22.6,600],[22.7,600],[22.8,600],[22.9,600],[23,600],[23.1,600],[23.2,600],[23.3,600],[23.4,600],[23.5,600],[23.6,600],[23.7,600],[23.8,600],[23.9,600],[24,600],[24.1,600],[24.2,600],[24.3,600],[24.4,600],[24.5,600],[24.6,600],[24.7,600],[24.8,600],[24.9,600],[25,600]]
                */
                data: [<?php echo join($refCurve, ',') ?>]
            }]
        });
	</script>
   
  
<?php
	include("footer.php");
?>gggggg