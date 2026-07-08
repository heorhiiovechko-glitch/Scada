<?php
	error_reporting(0);
	include("Includes.php");
?>
<?php

$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);
if(isset($Cook_Variable)){
		$Username = $Cook_Variable[0];
		$Account_ID = $Cook_Variable[3];
	}
	$Query_IMEI = base64_decode($_REQUEST['c1']);
	$Pocket_Length = $_REQUEST['l'];
	$FType=$_REQUEST['FType'];
	$Connect_Feeder=$_REQUEST['Feeder'];
	
# Time array
$Time_Arr = range(0,24);
foreach($Time_Arr as $Time_Val){
	$Str_Len = strlen($Time_Val);
	if($Str_Len == 1){
		$Time_Val = "0".$Time_Val;
	}
	$Time_24_Array["k".$Time_Val] = '';
}
?>
<?php
		// Getting the customer information
		$Fetch_Info = "select a.Group_Name,b.Firstname from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$Query_IMEI."'";//echo $Fetch_Info;
		if (!$Fetch_Info_Result = $db->query($Fetch_Info))
            {
                die($db->error);
            }
            if($Fetch_Info_Result->num_rows >= 1)
            {
				$x=1;
                while($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {
		
				  $All_groupname[$x] =  $Fetch_Details_Result['Group_Name'];
				  $x++;
			}				
		}
		?>

<?
	if (isset($_REQUEST["XLS"])){$XLS=1;}else{$XLS=0;}

		
		        if ($XLS == 1){
                $currDate = gmdate("d_M_Y");
				
				switch($_REQUEST['p']){
					case 1:
					$Title_Head = "Power_Vs_Wind_Speed";
					$colspan = 'colspan="2"';
					break;
					case 2:
					$Title_Head = "Overview_Report";
					break;	
					case 3:
					$Title_Head = "Temperature_Report";
					break;
					case 4:
					$Title_Head = "Production_Report";
					break;
					case 5:
					$Title_Head = "Grid_Report";
					break;
					case 6:
					$Title_Head = "Daily_EB_Slot_Reading";
					break;
					case 7:
					$Title_Head = "Stop_Hours_Report";
					break;
					case 8:
					$Title_Head = "DGR_Individual_Report";
					break;					
					case 9:
					$Title_Head = "Daily_Generation_Report";
					$colspan = 'colspan="2"';
					break;
					case 10:
					$Title_Head = "Monthly_Generation_Report";
					break;
					case 11:
					$Title_Head = "Financial_Report";
					break;
					case 12:
					$Title_Head = "Financial_Group_Report";
					break;
					case 13:
					$Title_Head = "Alarm_Log_Group";
					$colspan = 'colspan="3"';
					break;
					case 24:
					$Title_Head = "Grid_Availability_Report";
					break;	
					case 25:
					$Title_Head = "Alarm_Log_Individual";
					$colspan = 'colspan="3"';
					break;
					case 26:
					$Title_Head = "Alarm_Log_Group";
					$colspan = 'colspan="3"';
					break;
					case 28:
					$Title_Head = "DGR_ERP_Report";
					$colspan = 'colspan="3"';
					break;	
					case 29:
					$Title_Head = "DGR_Grouping_Report";
					$colspan = 'colspan="3"';
					break;
					case 32:
					$Title_Head = "DGR_Individual_Report";
					break;
					case 35:
					$Title_Head = "Total_Machine_Monthly_Report";
					break;
					case 36:
					$Title_Head = "Monthly_Individual_Report";
					break;																																
					case 38:
					$Title_Head = "EventLog_Report";
					break;
																				
				}
                $fName = $Title_Head."_".$currDate.".xls";
                $fName = urlencode($fName);
                header("Content-Type: application/vnd.ms-excel");
                header("Content-disposition: attachment;filename=$fName");
        }
        if ($XLS == 0){
				switch($_REQUEST['p']){
					case 1:
					$colspan = 'colspan="2"';
					break;
					case 2:
					$colspan = 'colspan="2"';
					break;
					case 3:
					$colspan = 'colspan="2"';
					break;
					case 4:
					$colspan = 'colspan="2"';
					break;
					case 5:
					$colspan = 'colspan="2"';
					break;
					case 6:
					$colspan = 'colspan="2"';
					break;
					case 7:
					$colspan = 'colspan="2"';
					break;
					case 8:
					$colspan = 'colspan="2"';
					break;
					case 9:
					$colspan = 'colspan="2"';
					break;
					case 10:
					$colspan = 'colspan="3"';
					break;
					case 11:
					$colspan = 'colspan="2"';
					break;
					case 12:
					$colspan = 'colspan="2"';
					break;
					case 13:
					$colspan = 'colspan="2"';
					break;
					case 14:
					$colspan = 'colspan="2"';
					break;
					case 15:
					$colspan = 'colspan="2"';
					break;
					case 16:
					$colspan = 'colspan="2"';
					break;
					case 17:
					$colspan = 'colspan="2"';
					break;
				}
        }
?>
	<?php
        if($_REQUEST['inputDate']){
            $InputDate = $_REQUEST['inputDate'];
            //$InputTime = $_REQUEST['inputTime'];
        }	
        else{
            $InputDate = date("d-m-Y");	
            //$InputTime = "00:00";	
        }
            
        if($_REQUEST['inputDate1']){
            $InputDate1 = $_REQUEST['inputDate1'];
            //$InputTime1 = $_REQUEST['inputTime1'];
        }	
        else{
            $InputDate1 = date("d-m-Y");
            //$InputTime1 = "23:59";	
        }	
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Versatilescada</title>
		<script type="text/javascript" src="js/jq1.js"></script>
        <script type="text/javascript" src="js/jscript.js"></script>
        <script type="text/javascript" src="./js/datepicker.js"></script>
        <script type="text/javascript" src="./js/eye.js"></script>
        <script type="text/javascript" src="./js/layout.js?ver=1.0.2"></script>
    <?php
    if ($XLS == 0){	
	?>
        
         <link rel="stylesheet" href="./css/datepicker.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="./css/Style.css" />
     <?php
	 }
	 ?>   
        <script>
			function Date_Valid(){
			
				Current_Date1 = new Date(<?=date('Y, m-1, d')?>);
				Pselect = document.getElementById('p').value;
				From_Date = document.getElementById('inputDate').value;
				From_Date_Arr = From_Date.split('-');
				From_Date1 = new Date(From_Date_Arr[2], From_Date_Arr[1]-1, From_Date_Arr[0]);
				
				To_Date = document.getElementById('inputDate1').value;
				To_Date_Arr = To_Date.split('-');
				To_Date1 = new Date(To_Date_Arr[2], To_Date_Arr[1]-1, To_Date_Arr[0]);
				

				var Check_Current_Date = Current_Date1.getTime();
				var Check_From_Date = From_Date1.getTime();
				var Check_To_Date = To_Date1.getTime();
				if(Pselect == 12 || Pselect == 13 || Pselect == 14 || Pselect == 15 || Pselect==20 ||  Pselect==19){
					if((Check_From_Date - Check_To_Date) != 0){
						document.getElementById('date_valid_div').innerHTML = 'Should not select more than one day';
						return false;
					}
				}
				else if(Pselect == 17){
					var inputYear = document.getElementById('inputYear').value;
					if(inputYear == 0){
						document.getElementById('date_valid_div').innerHTML = 'Please select the Year';
						return false;	
					}		
				}
				else if(Pselect == 39){
					var inputYear1 = document.getElementById('inputYear1').value;
					if(inputYear1 == 0){
						document.getElementById('date_valid_div').innerHTML = 'Please select the Year';
						return false;	
					}		
				}
				if(Check_Current_Date <  Check_From_Date){
					document.getElementById('date_valid_div').innerHTML = 'Please check the Start date';
					return false;
				}
				if(Check_Current_Date <  Check_To_Date){
					document.getElementById('date_valid_div').innerHTML = 'Please check the End date';
					return false;
				}
				else{
					return true;
				}
			}
		</script>

</head>
<style>
/* Change ONLY the header color */
.tab-head-frame {
    background-color: #0f4da2 !important; /* New Color */
    color: white !important;
    font-weight: bold;
}

.tab-head-td {
    background-color: #0f4da2 !important;
    color: white !important;
    font-weight: bold;
}

/* If your Style.css has old colors, we override them */
.innertab1 .tab-head-frame,
.innertab1 .tab-head-td {
    background-color: #0f4da2 !important;
    color: white !important;
}

/* ===== Professional UI Layer (SAFE) ===== */

body {
    background: #f4f6fb;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
}

/* Main outer card */
.innertab1 {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    padding: 10px;
}

/* Header cells */
.tab-head-frame,
.tab-head-td {
    background: linear-gradient(135deg, #0f4da2, #1976d2) !important;
    color: #ffffff !important;
    font-size: 14px;
    letter-spacing: 0.3px;
}

/* Inputs and dropdowns */
select,
input[type="text"],
input[type="submit"] {
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* Buttons */
input[type="submit"] {
    background: #1976d2;
    color: white;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

input[type="submit"]:hover {
    background: #0f4da2;
}

/* Horizontal line */
hr {
    border: none;
    height: 1px;
    background: #e0e0e0;
}

/* Improve spacing */
td {
    padding: 6px;
}

/* Error message */
#date_valid_div {
    font-weight: 600;
    margin-top: 8px;
}

</style>
<body>
    <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
    <?php
    if ($XLS == 0){
	?>
        <tr>
            <td valign="top">
                <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	

                    <tr>
                         <td align="left" class="tab-head-frame" width="200px">&nbsp;&nbsp;Reports</td>
                         <td align="left" class="tab-head-frame">&nbsp;&nbsp;Start Date</td>
                         <td align="left" class="tab-head-frame">&nbsp;&nbsp;End Date</td>
                         <td align="left" class="tab-head-frame"></td>
                    </tr>
                    <tr >
                        <form name="wind" onsubmit="return Date_Valid()">
                        <td align="left" style="padding-left:5px;"> 
                            <select name="p" id="p" style="width:180Px; padding-left:5px" onChange="For_Service_Report_Type1(this.value)">
								<option value="1" <?=($_REQUEST['p'] == 1?'selected=selected' : '')?>>Power Vs Wind Speed</option>
								<option value="2" <?=($_REQUEST['p'] == 2?'selected=selected' : '')?>>Overview Report</option>
								<option value="3" <?=($_REQUEST['p'] == 3?'selected=selected' : '')?>>Temperature Report</option>
								<option value="4" <?=($_REQUEST['p'] == 4?'selected=selected' : '')?>>Production Report</option> 
								<option value="5" <?=($_REQUEST['p'] == 5?'selected=selected' : '')?>>Grid Report</option> 
								<option value="6" <?=($_REQUEST['p'] == 6?'selected=selected' : '')?>>Daily EB Slot Reading</option> 
								<option value="7" <?=($_REQUEST['p'] == 7?'selected=selected' : '')?>>Stop Hours Report</option> 
								<option value="8" <?=($_REQUEST['p'] == 8?'selected=selected' : '')?>>Alarm Log Individual</option>
								<?php
								
									if($Cook_Variable[3]!="100235") {
										?>
								<option value="9" <?=($_REQUEST['p'] == 9?'selected=selected' : '')?>>Alarm Log Group</option>
								<?php
									}
								?>
								<option value="10" <?=($_REQUEST['p'] == 10?'selected=selected' : '')?>>Daily Generation Individual Report</option>
								<?php
								
									if($Cook_Variable[3]!="100077") {
										if($All_groupname[1]!= NULL || $All_groupname[1]!='' /*"smt" || $All_groupname[1]=="angalakshmi" */) {										
								?>	
										<option value="29" <?=($_REQUEST['p'] == 29?'selected=selected' : '')?>>DGR Grouping Report</option>
								<?php
								} else {
									if($Cook_Variable[3]!="100235") {
								?>
								<option value="11"  <?=($_REQUEST['p'] == 11?'selected=selected' : '')?>>Daily Generation Group Report</option>
								<?php 
								}
								}
								}
								?>
								<option value="12" <?=($_REQUEST['p'] == 12?'selected=selected' : '')?>>Monthly Generation Report</option> 
								<option value="13" <?=($_REQUEST['p'] == 13?'selected=selected' : '')?>>Financial Year Individual Report</option> 								
								<!--<option value="14" <?=($_REQUEST['p'] == 14?'selected=selected' : '')?>>Financial Year Report</option> -->
								<option value="19" <?=($_REQUEST['p'] == 19?'selected=selected' : '')?>>Power and Windspeed curves</option>
								<?php
									if($Cook_Variable[3]=="54") {
								?>	
										<option value="24" <?=($_REQUEST['p'] == 24?'selected=selected' : '')?>>Grid Availability Report</option>
										<option value="28" <?=($_REQUEST['p'] == 28?'selected=selected' : '')?>>DGR-ERP Report</option> 
								<?php
									}	if($Cook_Variable[3]=="100077") {
								?>
										<option value="33" <?=($_REQUEST['p'] == 33?'selected=selected' : '')?>><?=$All_groupname[1]?> Report</option>
										<option value="34" <?=($_REQUEST['p'] == 34?'selected=selected' : '')?>>Cumulative DGR Report</option>
								<?php
									}
								?>
																
						    </select>
                        </td>
                        <td align="left">
							<?php
								/*if($_REQUEST['p'] != 17){
									$Visible_Status = "none";
									$Visible_Status1 = "block";
								}
								else{
									$Visible_Status = "block";
									$Visible_Status1 = "none";
								}*/
							?>
						<?php
								if($_REQUEST['p'] == 27){
									$Visible1_Status = "none";
									$Visible_Status1 = "none";
									$Visible_Status = "none";
									$Visible2_Status = "block";
								}
								elseif($_REQUEST['p'] == 18){
									$Visible_Status = "block";
									$Visible_Status1  = "none";
									$Visible1_Status = "none";
									$Visible2_Status = "none";
								}
								elseif($_REQUEST['p'] == 35 || $_REQUEST['p'] == 36){
									$Visible_Status = "none";
									$Visible_Status1  = "none";
									$Visible1_Status = "none";
									$Visible2_Status = "block";
								}
								elseif($_REQUEST['p'] == 13 || $_REQUEST['p'] == 40){
									$Visible1_Status = "block";
									$Visible_Status1 = "none";
									$Visible_Status = "none";
									$Visible2_Status = "none";
								}
								else{
									$Visible_Status = "none";
									$Visible_Status1 = "block";
									$Visible1_Status = "none";
									$Visible2_Status = "none";
								}
							?>
							<!--<input class="inputDate" name="inputDate" id="inputDate" value="<?=$InputDate?>" style="width:80px;display:<?=$Visible_Status1?>" />
							<div id="InputYearDiv" style="display:<?=$Visible_Status?>">
								<select name="inputYear" id="inputYear" class="Reg_Select">
									 <option value="0">---Select Year---</option>
									<?php
										foreach($Year_Array as $key => $Years){
											echo $inputYear = "<option value=\"".$Years."\" ".($_REQUEST['inputYear'] == $Years?'selected=selected' : '').">".$Years."</option>";
										}
									?>
								 </select>
							</div>-->

							<input class="inputDate" name="inputDate" id="inputDate" value="<?=$InputDate?>" style="width:80px;display:<?=$Visible_Status1?>" />
							<div id="InputYearDiv" style="display:<?=$Visible_Status?>">
								<select name="inputYear" id="inputYear" class="Reg_Select">
									 <option value="0">---Select Year---</option>
									<?php
										foreach($Year_Array as $key => $Years){
											echo $inputYear = "<option value=\"".$Years."\" ".($_REQUEST['inputYear'] == $Years?'selected=selected' : '').">".$Years."</option>";
										}
									?>
								 </select>
							</div>

							<div id="InputYearDiv1" style="display:<?=$Visible1_Status?>">
								<select name="inputYear1" id="inputYear1" class="Reg_Select">
									 <option value="0">---Select Year---</option>
									<?php
										foreach($Fin_Year_Array as $key => $Year_Val){
											echo $inputYear1 = "<option value=\"".$key."\" ".($_REQUEST['inputYear1'] == $key?'selected=selected' : '').">".$Year_Val."</option>";
										}
									?>
								 </select>
							</div>
							<div id="InputMonthYearDiv" style="display:<?=$Visible2_Status?>">
								<select name="inputMonthYear" id="inputMonthYear" class="Reg_Select">
									<?php
										foreach($Month_Year_Array as $key => $Year_Month_Val){
											echo $inputMonthYear = "<option value=\"".$key."\" ".($_REQUEST['inputMonthYear'] == $key?'selected=selected' : '').">".$Year_Month_Val."</option>";
										}
									?>
								 </select>
							</div>

						</td>
                        <td align="left">
							<input class="inputDate1"  name="inputDate1" id="inputDate1" value="<?=$InputDate1?>" style="width:80Px;display:<?=$Visible_Status1?>" />
						</td>
                        <td align="center"><input type="hidden" name="l" value="<?=$_REQUEST['l']?>" /><input type="hidden" name="FType" value="<?=$_REQUEST['FType']?>" /><input type="hidden" name="c1" value="<?=$_REQUEST['c1']?>" /><input type="submit" name="dateSearch" id="dateSearch" value="GO"></td>
                    </tr>
                    <tr >
                         <td align="center" colspan="4" height="10px"><hr size="1"></td>
                    </tr>
                 </table>
                          </form>
            </td>
        </tr>
	<?php
	}
	?>        
        <tr>
            <td>
            	<div id="date_valid_div" style="color:red"></div>
                <?php
                    if(isset($_REQUEST['dateSearch'])){
						// After click submit button
						if(isset($_REQUEST['p'])){
							// Adding 5.5 hours for the search
							$From_D_Epoch = strtotime($_REQUEST['inputDate'])+(60*60*5.5);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1'])+(60*60*5.5);
							$From= date("Y-m-d",$From_D_Epoch);
							$To= date("Y-m-d",$To_D_Epoch);
							$From_Error= date("d.m.Y",$From_D_Epoch);
							$To_Error= date("d.m.Y",$To_D_Epoch);
							$IMEI = base64_decode($_REQUEST['c1']);
							#
							#	Daily Generation Report
							#

							if($Pocket_Length==50){
							$Table_Name="device_table_f6";
							$Error_Table="error_data_f6";
							}elseif($Pocket_Length==37){
							$Table_Name="device_data_f10";
							$Error_Table="error_data_f10";
							}
							else
							{							
							$Table_Name="device_data";
							$Error_Table="error_data";
							}	
							if( $_REQUEST['p'] == 6){
								
								// Date Calculation
								$Date_Range =  getDaysInBetween($_REQUEST['inputDate'],$_REQUEST['inputDate1']);
								foreach($Date_Range as $Date_Range_Val){
									$Date_Range_Val_Final = $Date_Range_Val[0];
									$Date_Range_Val_Final1 = date("Y-m-d",$Date_Range_Val_Final);
									//echo $Date_Range_Val_Final1;
									// Getting 24 hours data
									$Date_Range_Val_Final = date("dmY",$Date_Range_Val[0]);
	$Mysql_Query1[$Date_Range_Val_Final] = "select DISTINCT(SUBSTRING(Time_S,1,2)) AS Time_24,Time_S, Date_S,PAT_Gen2,Record_Index from $Cook_Variable[7].$Table_Name where IMEI = '".$IMEI."' and Date_S = '".$Date_Range_Val_Final1."' and PAT_Gen2 != '' and PAT_Gen2 >'1' group by Time_24 order by Time_24 asc";//echo $Mysql_Query1[$Date_Range_Val_Final] ;
	if (!$Mysql_Query_Result1[$Date_Range_Val_Final] = $db->query($Mysql_Query1[$Date_Range_Val_Final]))
            {
                die($db->error);
            }
			$Mysql_Record_Count1[$Date_Range_Val_Final] = $Mysql_Query_Result1[$Date_Range_Val_Final]->num_rows;
			$MI = 1;
            if($Mysql_Query_Result1[$Date_Range_Val_Final]->num_rows >= 1)
            {
                while($Fetch_Result1[$Date_Range_Val_Final] = $Mysql_Query_Result1[$Date_Range_Val_Final]->fetch_array()) {										$Fetch_Result2 = $Fetch_Result1[$Date_Range_Val_Final];
											$PAT_Total_24[$Date_Range_Val_Final]["k".$Fetch_Result2['Time_24']] = $Fetch_Result2['Record_Index'];
											$All_Date_Arr[$Date_Range_Val_Final] = $Fetch_Result2['Date_S'];
											$MI++;
										}
									}


									//echo "24 TIme Output ===> ";print_r($Time_24_Array);
									//echo "<br /><br />";
									//echo "DB Output ===> ";print_r($PAT_Total_24[$Date_Range_Val_Final]);
									//echo "<br /><br />";
									$PAT_Total_24_Merge[$Date_Range_Val_Final] = array_merge($Time_24_Array,$PAT_Total_24[$Date_Range_Val_Final]);
									//echo "Merge Output ===> ";print_r($PAT_Total_24_Merge[$Date_Range_Val_Final]);
									//echo "<br /><hr><br />";	

									#
									#	Getting all the data from DB
									#
$Mysql_Query[$Date_Range_Val_Final] = "select Time_S, Date_S,PAT_Gen2,Record_Index from $Cook_Variable[7].$Table_Name where IMEI = '".$IMEI."' and Date_S = '".$Date_Range_Val_Final1."' and PAT_Gen2 != '' and PAT_Gen2 >'1' order by Record_Index asc";// limit 31,18";
	if (!$Mysql_Query_Result[$Date_Range_Val_Final] = $db->query($Mysql_Query[$Date_Range_Val_Final]))
            {
                die($db->error);
            }
			$Mysql_Record_Count[$Date_Range_Val_Final] = $Mysql_Query_Result[$Date_Range_Val_Final]->num_rows;
			if($Mysql_Query_Result[$Date_Range_Val_Final]->num_rows >= 1)
            {
				$MI = 1;
                while($Fetch_Result[$Date_Range_Val_Final] = $Mysql_Query_Result[$Date_Range_Val_Final]->fetch_array()) {							$Fetch_Result3 = $Fetch_Result[$Date_Range_Val_Final];
											$Time_First_Seg = substr($Fetch_Result3['Time_S'],0,2);
											$PAT_Total[$Date_Range_Val_Final][$MI]/*["k".$Time_First_Seg]*/ = $Fetch_Result3['PAT_Gen2'];
											$PAT_Total_RI[$Date_Range_Val_Final][$MI] = $Fetch_Result3['Record_Index'];
											$PAT_Total_Record_Index[$Date_Range_Val_Final][$Fetch_Result3['Record_Index']]  = $MI;
											$MI++;
										}
										
									}
									$Total_Count_DB[$Date_Range_Val_Final] = count($PAT_Total_Record_Index[$Date_Range_Val_Final]);
									//echo "Pat Total Output ===> ";print_r($PAT_Total[$Date_Range_Val_Final]);
									//echo "<br /><hr><br />";
								} // foreach end	
								
							} // EB Report else
							
								
							
							#power and windspeed curves

       							 elseif($_REQUEST['p'] == 19){
       							 ?>
   							 <script>Open_PowerWindspeedCurve('<?=$_REQUEST['c1']?>','<?=$From_D_Epoch?>','<?=$To_D_Epoch?>','<?=$Pocket_Length?>')</script> 
							 <?php  
        } 
							elseif($_REQUEST['p'] == 27){
       							 ?>
   							 <script>Open_PowerWindspeedCurveMonth('<?=$_REQUEST['c1']?>','<?=$_REQUEST['inputMonthYear']?>','<?=$Pocket_Length?>')</script> 
							 <?php  
        } 
							//Power Curve else
							else
							{
								// All the reports except Daily report and EB report
								$From_D_Epoch = strtotime($_REQUEST['inputDate'])+(60*60*5.5);
								$To_D_Epoch = strtotime($_REQUEST['inputDate1'])+(60*60*5.5);
								$IMEI = base64_decode($_REQUEST['c1']);
								$From_YMD= date("Y-m-d",$From_D_Epoch);
							$To_YMD= date("Y-m-d",$To_D_Epoch);
							
								
									

							} // Else end
						} // Request P end
							$No_Records = '<tr>
								<td width="50%" class="tab-head-td" '.$colspan.' style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
							</tr>';				
    ?>
            </td>	
         </tr>
		 
		<?php
		// Getting the customer information
		$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname,b.Lastname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$Query_IMEI."'";
		//echo $Fetch_Info;
		if (!$Fetch_Info_Result = $db->query($Fetch_Info))
            {
                die($db->error);
            }
			$Fetch_Info_Result_Count = $Fetch_Info_Result->num_rows;
            if($Fetch_Info_Result->num_rows >= 1)
            {
				$x=1;
                while($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {
					$All_REGACC_IMEI = $Fetch_Details_Result['IMEI'];					
				  $All_HTSC_No[$x] = $Fetch_Details_Result['HTSC_No'];					
				  $All_LOC_No[$x] = $Fetch_Details_Result['LOC_No'];					
				  $All_WEG_No[$x] = $Fetch_Details_Result['WEG_No'];					
				  $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];					
				  $All_Firstname[$x] = $Fetch_Details_Result['Firstname'];
				  $All_Lastname[$x] = $Fetch_Details_Result['Lastname'];
				  $All_Devicename[$x] =  $Fetch_Details_Result['Device_Name'];
				  $Site_Location[$x] = $Fetch_Details_Result['Site_Location'];
				  $DOC[$x] = $Fetch_Details_Result['DOC'];
				  $SF_No[$x] = $Fetch_Details_Result['SF_No'];
				  $Date_Of_Commission = $Fetch_Details_Result['Date_Of_Commission'];
				  $Capacity[$x] = $Fetch_Details_Result['Capacity'];
				  $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];
				  $x++;
			}				
		}
		?>
		    
		 <?
		 ##########################################################
		 #
		 #	Excel Reports
		 #
		 ##########################################################
		 ?>
			<?php
			#
			#	Power Vs Wind Speed Excel 
			#
            if($_REQUEST['p'] == 1){
				include("Power_Vs_Wind_Speed_Excel.php");
			}	
			?>
			<?php
				#
				#	Overview Report
				#
				if($_REQUEST['p'] == 2){
					include("Raw_Data_Overview.php");
					
				}	
			?>
			<?php
				#
				#	Temperature Report
				#
				if($_REQUEST['p'] == 3){
					include("Raw_Data_Temperature.php");
					
				}	
			?>
			<?php
				#
				#	Production Report
				#
				if($_REQUEST['p'] == 4){
					include("Raw_Data_Production.php");
					
				}	
			?>
			<?php
				#
				#	Grid Report
				#
				if($_REQUEST['p'] == 5){
					include("Raw_Data_Grid.php");
					
				}	
			?>
			<?php
				#
				#	EB Report Report
				#
				if($_REQUEST['p'] == 6){
					if ($Cook_Variable[3] == 100235) {
						include("Daily_EB_Report_Excel_Type10_jayavilas.php");
					} else {
					include("Daily_EB_Report_Excel_Type10.php");
					}
				}	
			?>
			<?php
				#
				#	Stop Hours Report
				#
				if($_REQUEST['p'] == 7){
					include("Stop_Hours_Report.php");
				}	
			?>
			<?php
				#
				#	Alarm Log Report Individual
				#
				if($_REQUEST['p'] == 8){
					include("Alarm_Log_Individual_Report_Excel.php");			
					
				}	
			?>
			<?php
				#
				#	Alarm Log Group Report
				#
				if($_REQUEST['p'] == 9){
					if ($Cook_Variable[6] == 100079 || $Cook_Variable[3] == 100081 || $Cook_Variable[3] == 100082 || $Cook_Variable[3] == 100084 ||  $Cook_Variable[3] == 100088 ) {
						include("Alarm_Log_Report_Excel_Lucky.php");
					} else {
						include("Alarm_Log_Group_Report_Excel.php");
					}
					
				}	
			?>
			<?php
				#
				#	DGR Ind Report
				#
				if($_REQUEST['p'] == 10){
					include("Daily_Generation_Report_Individual_Excel.php");
				}	
			?>
			
			<?php
				#
				#	Daily Generation Report
				#
				if($_REQUEST['p'] == 11){
					if ($Cook_Variable[3] == 100079 || $Cook_Variable[3] == 100081 || $Cook_Variable[3] == 100082 || $Cook_Variable[3] == 100084 || $Cook_Variable[3] == 100088 ) {

						include("Daily_Generation_Report_Excel_lucky.php");
					} 
					else {
					include("Daily_Generation_Report_Excel.php");
				}
				}	
			?>
			<?php
				#
				#	Daily Generation Report-Grouping
				#
				if($_REQUEST['p'] == 29){
					include("Daily_Generation_Report_Grouping_Excel.php");
				}	
			?>
			<?php
				#
				#	Monthly Generation Report
				#
				if($_REQUEST['p'] == 12){
						include("Monthly_Generation_Report.php");		
					
				}		
			?>
			<?php
				#
				#	Financial Report
				#
				if($_REQUEST['p'] == 13){
					include("Financial_Year_Report.php");
				}	
			?>
			<?php
				#
				#	DGR ERP Report MTK
				#
				if($_REQUEST['p'] == 28){
					include("DGR_ERP_MTK.php");
					
				}	
			?>
			<?php
				#
				#	Grid Availability Report
				#
				if($_REQUEST['p'] == 24){
					include("Grid_Availability_Report.php");
				}	
			?>

			<?php
				#
				#	EventLog Report
				#
				if($_REQUEST['p'] == 38){
					include("Eventlog_report.php");
				}	
			?>
			
         
		<?php
		} // dateSearch end	
		?>		

	</table>
</body>         
</html>