<?php
	error_reporting(0);
	
	include("header.php");
	include("Lib/config.php");
	include("Lib/dbconn.php");
	if(empty($_SESSION['user'])){
		header('Location: index.php');
		exit;
	}
?>
<?
		$Mysql_Query = "select * from DEVICE_DATA order by Record_Index desc limit 1";
		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
		$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
		if($Mysql_Record_Count>=1){
			while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){
				$Project_Version = $Fetch_Result['Project_Version'];
				$ID_Number = $Fetch_Result['ID_Number'];
				$GRPM = $Fetch_Result['GRPM'];
				$RRPM = $Fetch_Result['RRPM'];
				$WindSpeed = $Fetch_Result['WindSpeed'];
				$Pitch = $Fetch_Result['Pitch'];
				$Status = $Fetch_Result['Status'];
				$Date = $Fetch_Result['Date'];
				$Time = $Fetch_Result['Time'];
				$Power = $Fetch_Result['Power'];
				$Rphase_Volt = $Fetch_Result['Rphase_Volt'];
				$Yphase_Volt = $Fetch_Result['Yphase_Volt'];
				$Bphase_Volt = $Fetch_Result['Bphase_Volt'];
				$Rphase_Current = $Fetch_Result['Rphase_Current'];
				$Yphase_Current = $Fetch_Result['Yphase_Current'];
				$Bphase_Current = $Fetch_Result['Bphase_Current'];
				$Power_factor = $Fetch_Result['Power_factor'];
				$Frequency = $Fetch_Result['Frequency'];
				$PAT_Gen0 = $Fetch_Result['PAT_Gen0'];
				$PAT_Gen1 = $Fetch_Result['PAT_Gen1'];
				$PAT_Gen2 = $Fetch_Result['PAT_Gen2'];
				$PAM_Gen0 = $Fetch_Result['PAM_Gen0'];
				$PAM_Gen1 = $Fetch_Result['PAM_Gen1'];
				$PAM_Gen2 = $Fetch_Result['PAM_Gen2'];
				$PATP_Gen0 = $Fetch_Result['PATP_Gen0'];
				$PATP_Gen1 = $Fetch_Result['PATP_Gen1'];
				$PATP_Gen2 = $Fetch_Result['PATP_Gen2'];
				$Device_Epoch_Time = $Fetch_Result['Device_Epoch_Time'];
				$Server_Epoch_Time = $Fetch_Result['Server_Epoch_Time'];
				$Server_Date_Stamp = $Fetch_Result['Server_Date_Stamp'];
			}
		}
				
		$No_Records = '<tr>
				<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px">Records Not Found</td>
			</tr>';
?> 

<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>

  <center>
	  <div id="body" class="clear" style="width:1000px;">
    <div class="box">
      <em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em>
      <div class="content">
          <h2>Energy from versatilescada Detailed Information!</h2>
          <p>about Status, Temperatures, Electrical, Production Figures</p>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
    		<tr>
        		<td width="50%" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <!-- 
                            Status
                        -->
                        <tr>
                            <td width="100%" valign="top">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr class="tab-head-tr">
                                        <td colspan="2">&nbsp;&nbsp;Status</td>
                                    </tr>
									<?php

										if($Mysql_Record_Count >= 1){
									?>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Project Version</td>
                                        <td class="tab-head-td1"><?=$Project_Version?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">ID Number</td>
                                        <td class="tab-head-td1"><?=$ID_Number?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">GRPM</td>
                                        <td class="tab-head-td1"><?=$GRPM?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">RRPM</td>
                                        <td class="tab-head-td1"><?=$RRPM?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Wind Speed</td>
                                        <td class="tab-head-td1"><?=$WindSpeed." m/s"?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Pitch</td>
                                        <td class="tab-head-td1"><?=$Pitch?> Deg</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Status </td>
                                        <td class="tab-head-td1"><?=$Status?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Date</td>
                                        <td class="tab-head-td1"><?=$Date?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Time</td>
                                        <td class="tab-head-td1"><?=$Time?></td>
                                    </tr>
									  <?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?>  
                                </table>
                            </td>	
                  		</tr>
                     
                         <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
                        <!-- 
                            Electrical
                        -->
                        <tr>
                            <td width="100%">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr class="tab-head-tr">
                                        <td colspan="2">&nbsp;&nbsp;Electrical</td>
                                    </tr>
                                    <?php

										if($Mysql_Record_Count >= 1){
									?>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Power</td>
                                        <td class="tab-head-td1"><?=$Power?> KW</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Rphase Volt</td>
                                        <td class="tab-head-td1"><?=$Rphase_Volt?> V</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Yphase Volt</td>
                                        <td class="tab-head-td1"><?=$Yphase_Volt?> V</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Bphase Volt</td>
                                        <td class="tab-head-td1"><?=$Bphase_Volt?> V</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Rphase Current</td>
                                        <td class="tab-head-td1"><?=$Rphase_Current?> A</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Yphase Current</td>
                                        <td class="tab-head-td1"><?=$Yphase_Current?> A</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Bphase Current</td>
                                        <td class="tab-head-td1"><?=$Bphase_Current?> A</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Power factor</td>
                                        <td class="tab-head-td1"><?=$Power_factor?></td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Frequency</td>
                                        <td class="tab-head-td1"><?=$Frequency?> Hz</td>
                                    </tr>
									  <?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?>  
                                </table>
                            </td>	
                         </tr>
                         <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
        
                        <!-- 
                            Production Active Total
                        -->
        
                        <tr>
                            <td width="100%">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr class="tab-head-tr">
                                        <td colspan="2">&nbsp;&nbsp;Production Details</td>
                                    </tr>
                                    <?php

										if($Mysql_Record_Count >= 1){
									?>
                                    <tr>
                                        <td width="50%" class="tab-head-td" colspan="2">Production Active Total</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Gen0 </td>
                                        <td class="tab-head-td1"><?=$PAT_Gen0?> KWh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Gen1</td>
                                        <td class="tab-head-td1"><?=$PAT_Gen1?> KWh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Gen2</td>
                                        <td class="tab-head-td1"><?=$PAT_Gen2?> KWh</td>
                                    </tr>
                                </table>
                            </td>	
                         </tr>
                         <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
                         
                        <!-- 
                            Production Active Month
                        -->
        
                        <tr>
                            <td width="100%">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr>
                                        <td width="50%" class="tab-head-td" colspan="2">Production Active Month</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Gen0 </td>
                                        <td class="tab-head-td1"><?=$PAM_Gen0?> KWh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Gen1</td>
                                        <td class="tab-head-td1"><?=$PAM_Gen1?> KWh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Gen2</td>
                                        <td class="tab-head-td1"><?=$PAM_Gen2?> KWh</td>
                                    </tr>
                                </table>
                            </td>	
                         </tr>
                         <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
                         
                        <!-- 
                            Production Active Trip
                        -->
        
                        <tr>
                            <td width="100%">
                                <table width="95%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                                    <tr>
                                        <td width="50%" class="tab-head-td" colspan="2">Production Active Trip</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="tab-head-td">Gen0 </td>
                                        <td class="tab-head-td1"><?=$PATP_Gen0?> KWh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Gen1</td>
                                        <td class="tab-head-td1"><?=$PATP_Gen1?> KWh</td>
                                    </tr>
                                    <tr>
                                        <td class="tab-head-td">Gen2</td>
                                        <td class="tab-head-td1"><?=$PATP_Gen2?> KWh</td>
                                    </tr>
									  <?php
                                      }
                                      else{
                                        echo $No_Records;
                                      }
                                      ?>  
                                </table>
                            </td>	
                         </tr>
                         <tr>
                            <td height="10px">&nbsp;</td>
                         </tr>
                    </table>      
                 </td>
                 <td valign="top">
                 <?php
				 /*******
				 	Right side tab
				 ***/
				 ?>
                        <iframe src="channel2_ajax.php?c1=<?=$_REQUEST['c1']?>" height="300px" width="100%" style="border:solid 1px #168A83"></iframe>
                 </td>
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
