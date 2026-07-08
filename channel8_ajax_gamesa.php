<?php

	error_reporting(0);	

	include("Includes.php");

	$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	

	if(isset($Cook_Variable)){

		$Username = $Cook_Variable[0];

		$User_Type_ID = $Cook_Variable[2];

		$Account_ID = $Cook_Variable[3];

	}	

?>

<?php

$Time_Arr = range(0,24);

foreach($Time_Arr as $Time_Val){

	$Str_Len = strlen($Time_Val);

	if($Str_Len == 1){

		$Time_Val = "0".$Time_Val;

	}

	$Time_24_Array["k".$Time_Val] = '';

}

?>

 

<?

	if (isset($_REQUEST["XLS"])){

		$XLS=1;

		}else{

		$XLS=0;

		}



		$Query_IMEI = base64_decode($_REQUEST['c1']);

		$Pocket_Length = $_REQUEST['l'];

		
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

					$Title_Head = "Daily_Generation_Report";

					break;

					case 7:

					$Title_Head = "Error_Log";

					break;

					case 8:

					$Title_Head = "Daily_EB_Report";

					break;					

					case 14:

					$Title_Head = "DGR_Report";

					break;					

					case 17:

					$Title_Head = "Wind_Farm_Generation_Report";

					break;					

					case 18:

					$Title_Head = "Monthly_Generation_Report";

					break;					

					case 19:

					$Title_Head = "Yearly_Generation_Report";

					break;	
					case 20:

					$Title_Head = "Temperature_Report";

					break;
					case 26:
					$Title_Head = "Alarm_Log_Group";
					$colspan = 'colspan="3"';
					break;	
					
					case 29:
					$Title_Head = "DGR_Grouping_Report";
					$colspan = 'colspan="3"';
					break;	
					case 30:
					$Title_Head = "DGR_Individual_Report";
					break;
					case 35:
					$Title_Head = "Total_Machine_Monthly_Report";
					break;
					case 36:
					$Title_Head = "Monthly_Individual_Report";
					break;	
					case 37:
					$Title_Head = "EventLog_Report";
					break;
					case 39:
					$Title_Head = "Production_Report";
					break;	
					case 40:
					$Title_Head = "Grid_Report";
					break;
					case 49:
					$Title_Head = "Financial_Report";
					break;
					case 50:
					$Title_Head = "Financial_Group_Report";
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

					$colspan = 'colspan="3"';

					break;

					case 8:

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

<title>Pioneerscada</title>

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

				if(Pselect == 16){

					if((Check_From_Date - Check_To_Date) != 0){

						document.getElementById('date_valid_div').innerHTML = 'Should not select more than one day';

						return false;

					}

				}

				else if(Pselect == 18){

					var inputYear = document.getElementById('inputYear').value;

					if(inputYear == 0){

						document.getElementById('date_valid_div').innerHTML = 'Please select the Year';

						return false;	

					}		

				}

				else if(Pselect == 19 || Pselect == 20){

					var inputYear1 = document.getElementById('inputYear1').value;

					if(inputYear1 == 0){

						document.getElementById('date_valid_div').innerHTML = 'Please select the Year';

						return false;	

					}		

				}
				else if(Pselect == 49){
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

<body>

    <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	

    <?php

							$FType=$_REQUEST['FType'];

							if($FType==7){

							$Table_Name="device_data_f7";

							$Error_Table_Name="error_data_f7";

							}elseif($FType==8){

							$Table_Name="device_data_f8";

							$Error_Table_Name="error_data_f8";

							}else{

							$Table_Name="device_data_f2";	

							$Error_Table_Name="error_data_f2";

							}

    if ($XLS == 0){

	?>

        <tr>

            <td valign="top">

                <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	

                    <tr>

                         <td align="left" class="tab-head-frame" width="200px">&nbsp;&nbsp;Production</td>

                         <td align="left" class="tab-head-frame">&nbsp;&nbsp;Start Date</td>

                         <td align="left" class="tab-head-frame">&nbsp;&nbsp;End Date</td>

                         <td align="left" class="tab-head-frame">						

						</td>

                    </tr>

                    <tr >

                        <form name="wind" onsubmit="return Date_Valid()">

                        <td align="left" style="padding-left:5px;">

                          <select name="p" id="p" style="width:180Px; padding-left:5px" onChange="For_Service_Report(this.value)">
								<!--<option value="1" <?=($_REQUEST['p'] == 1?'selected=selected' : '')?>>Power Vs Wind Speed - 1 Min</option>-->
								<option value="2" <?=($_REQUEST['p'] == 2?'selected=selected' : '')?>>Overview Report</option>
								<option value="3" <?=($_REQUEST['p'] == 3?'selected=selected' : '')?>>Temperature Report</option>
								<option value="4" <?=($_REQUEST['p'] == 4?'selected=selected' : '')?>>Production Report</option> 
								<option value="5" <?=($_REQUEST['p'] == 5?'selected=selected' : '')?>>Grid Report</option> 
								<option value="6" <?=($_REQUEST['p'] == 6?'selected=selected' : '')?>>Hours Report</option> 
								<!--<option value="7" <?=($_REQUEST['p'] == 7?'selected=selected' : '')?>>Stop Hours Report</option> 
								<option value="8" <?=($_REQUEST['p'] == 8?'selected=selected' : '')?>>Alarm Log Individual</option>
								<option value="9" <?=($_REQUEST['p'] == 9?'selected=selected' : '')?>>Alarm Log Group</option>
								<option value="10" <?=($_REQUEST['p'] == 10?'selected=selected' : '')?>>Daily Generation Individual Report</option>-->
								
								
								
							  </select>

                        </td>

                         <td align="left">

							<?php

								if($_REQUEST['p'] == 12){

									$Visible_Status = "block";

									$Visible_Status1 = "none";

									$Visible1_Status = "none";
									$Visible2_Status = "none";

								}

								elseif($_REQUEST['p'] == 13 || $_REQUEST['p'] == 20 || $_REQUEST['p'] == 49 || $_REQUEST['p'] == 50){

									$Visible1_Status = "block";

									$Visible_Status1 = "none";
									
									$Visible_Status = "none";
									$Visible2_Status = "none";
								}
								elseif($_REQUEST['p'] == 35 || $_REQUEST['p'] == 36){
									$Visible_Status = "none";
									$Visible_Status1  = "none";
									$Visible1_Status = "none";
									$Visible2_Status = "block";
								}
								

								else{

									$Visible_Status = "none";

									$Visible_Status1 = "block";

									$Visible1_Status = "none";
									$Visible2_Status = "none";

								}

							?>

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

										foreach($YearMonth_Array as $key => $YearMonth_Val){

											echo $inputYear1 = "<option value=\"".$key."\" ".($_REQUEST['inputYear1'] == $key?'selected=selected' : '').">".$YearMonth_Val."</option>";

										}

									?>

								 </select>

							</div>
						<div id="InputMonthYearDiv" style="display:<?=$Visible2_Status?>">
								<select name="inputMonthYear" id="inputMonthYear" class="Reg_Select">
										<option value="0">---Select Month---</option>
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

                        <td align="center"><input type="hidden" name="c1" value="<?=$_REQUEST['c1']?>" />

						<input type="hidden" name="l" value="<?=$_REQUEST['l']?>" />

						<input type="hidden" name="FType" value="<?=$_REQUEST['FType']?>" />

						<input type="submit" name="dateSearch" id="dateSearch" value="Go"></td>

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

                        //include("channel2_ajax.php");



							// for date search 

						if(isset($_REQUEST['p'])){

							$From_D_Epoch = strtotime($_REQUEST['inputDate'])+(60*60*5.5);

							$To_D_Epoch = strtotime($_REQUEST['inputDate1'])+(60*60*5.5);
							$From_D_Epoch_UK = strtotime($_REQUEST['inputDate'])-(60*60*4.5);

							$To_D_Epoch_UK = strtotime($_REQUEST['inputDate1'])-(60*60*4.5);
							$From_UK= date("Y-m-d",$From_D_Epoch);
							$To_UK= date("Y-m-d",$To_D_Epoch);
							$From_YMD= date("Y-m-d",$From_D_Epoch);
							$To_YMD= date("Y-m-d",$To_D_Epoch);
							
							$IMEI = base64_decode($_REQUEST['c1']);

							$WTG_Label = "WTG No";

								

							#	EB Report

							#

							if( $_REQUEST['p'] == 16){							

								// Date Calculation

								$Date_Range =  getDaysInBetween($_REQUEST['inputDate'],$_REQUEST['inputDate1']);

								foreach($Date_Range as $Date_Range_Val){

									$Date_Range_Val_Final = $Date_Range_Val[0];

									$Date_Range_Val_Final1 = date("Y-m-d",$Date_Range_Val_Final);

									// Getting 24 hours data

									$Date_Range_Val_Final = date("dmY",$Date_Range_Val[0]);
								
									$Mysql_Query1[$Date_Range_Val_Final] = "select DISTINCT(SUBSTRING(Time_S,1,2)) AS Time_24 ,Time_S,Date_S,Kwh_Positive,Record_Index from $Cook_Variable[7].$Table_Name where IMEI = '".$IMEI."' and Date_S = '".$Date_Range_Val_Final1."' and (Kwh_Positive != '') group by Time_24 order by Time_24 asc";
								
//print_r($Mysql_Query1);
									if (!$Mysql_Query_Result1[$Date_Range_Val_Final] = $db->query($Mysql_Query1[$Date_Range_Val_Final]))
            {
                die($db->error);
            }
			$Mysql_Record_Count1[$Date_Range_Val_Final] = $Mysql_Query_Result1[$Date_Range_Val_Final]->num_rows;
			$MI = 1;
            if($Mysql_Query_Result1[$Date_Range_Val_Final]->num_rows >= 1)
            {
                while($Fetch_Result1[$Date_Range_Val_Final] = $Mysql_Query_Result1[$Date_Range_Val_Final]->fetch_array()) {
											$Fetch_Result2 = $Fetch_Result1[$Date_Range_Val_Final];

											$PAT_Total_24[$Date_Range_Val_Final]["k".$Fetch_Result2['Time_24']] = $Fetch_Result2['Record_Index'];

											$All_Date_Arr[$Date_Range_Val_Final] = $Fetch_Result2['Date_S'];

											$MI++;

										}

									}
			$PAT_Total_24_Merge[$Date_Range_Val_Final] = array_merge($Time_24_Array,$PAT_Total_24[$Date_Range_Val_Final]);

									//echo "Merge Output ===> ";print_r($PAT_Total_24_Merge[$Date_Range_Val_Final]);

									//echo "<br /><hr><br />";	
									#

									#	Getting all the data from DB

									#
									$Mysql_Query[$Date_Range_Val_Final] = "select Time_S, Date_S,Kwh_Positive,Record_Index from $Cook_Variable[7].$Table_Name where IMEI = '".$IMEI."' and Date_S = '".$Date_Range_Val_Final1."' and (Kwh_Positive != '') order by Record_Index asc";// limit 31,18";
					if (!$Mysql_Query_Result[$Date_Range_Val_Final] = $db->query($Mysql_Query[$Date_Range_Val_Final]))
            {
                die($db->error);
            }
			$Mysql_Record_Count[$Date_Range_Val_Final] = $Mysql_Query_Result[$Date_Range_Val_Final]->num_rows;
			$MI = 1;
            if($Mysql_Query_Result[$Date_Range_Val_Final]->num_rows >= 1)
            {
                while($Fetch_Result[$Date_Range_Val_Final] = $Mysql_Query_Result[$Date_Range_Val_Final]->fetch_array()) {
											$Fetch_Result3 = $Fetch_Result[$Date_Range_Val_Final];

											$Time_First_Seg = substr($Fetch_Result3['Time_S'],0,2);

											$PAT_Total[$Date_Range_Val_Final][$MI]/*["k".$Time_First_Seg]*/ = $Fetch_Result3['Kwh_Positive'];

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

							#

							else

							{
								
							$Mysql_Query = "select * from $Cook_Variable[7].$Table_Name where IMEI = '".$IMEI."' and (Date_S >= '".$From."' and  Date_S <= '".$To."') group by IMEI,Date_S,Time_S order by Record_Index desc";
								if (!$Mysql_Query_Result = $db->query($Mysql_Query))
            {
                die($db->error);
            }
			$Mysql_Record_Count = $Mysql_Query_Result->num_rows;
            if($Mysql_Query_Result->num_rows >= 1)
            {
				$MI = 1;
                while($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
										$All_Date_Arr[$MI] = $Fetch_Result['Date_S'];

										$All_Time_Arr[$MI] = $Fetch_Result['Time_S'];

										$All_G1_Kwh_Arr[$MI] = $Fetch_Result['G1_Kwh'];

										$All_G2_Kwh_Arr[$MI] = $Fetch_Result['G2_Kwh'];

										$All_G1_Hours_Arr[$MI] = $Fetch_Result['G1_Hours'];

										$All_G2_Hours_Arr[$MI] = $Fetch_Result['G2_Hours'];

										$All_Import_Kwh_Arr[$MI] = $Fetch_Result['Import_Kwh'];

										$All_Power_Arr[$MI] = $Fetch_Result['Power'];  

										$All_Wind_Speed_Arr[$MI] = $Fetch_Result['Windspeed'];

										$All_Status_Arr[$MI] = $Fetch_Result['Status'];
										$All_G1_Temp_Arr[$MI] = $Fetch_Result['G1_Temp'];
										$All_G2_Temp_Arr[$MI] = $Fetch_Result['G2_Temp'];
										$All_G3_Temp_Arr[$MI] = $Fetch_Result['G3_Temp'];
										$All_G4_Temp_Arr[$MI] = $Fetch_Result['G4_Temp'];
										$All_G5_Temp_Arr[$MI] = $Fetch_Result['G5_Temp'];
										$All_G6_Temp_Arr[$MI] = $Fetch_Result['G6_Temp'];
										
										

										//Total_Export hours..

										$Total_Hours_Report[$MI] = $All_G1_Kwh_Arr[$MI] + $All_G2_Kwh_Arr[$MI];

											

										if($Fetch_Result['Import_Kwh'] == '0000000.0')

											$Fetch_Result['Import_Kwh'] = '';

										if($MI == 1){

											$F_G1_Kwh = $Fetch_Result['G1_Kwh'];

											$F_G2_Kwh  = $Fetch_Result['G2_Kwh'];

											$F_G1_Hours  = $Fetch_Result['G1_Hours'];

											$F_G2_Hours  = $Fetch_Result['G2_Hours'];

											$F_Import_Kwh  = $Fetch_Result['Import_Kwh'];						

										}	

										if($MI == $Mysql_Record_Count){

											$L_G1_Kwh  = $Fetch_Result['G1_Kwh'];

											$L_G2_Kwh  = $Fetch_Result['G2_Kwh'];

											$L_G1_Hours  = $Fetch_Result['G1_Hours'];

											$L_G2_Hours  = $Fetch_Result['G2_Hours'];

											$L_Import_Kwh  = $Fetch_Result['Import_Kwh'];

										}	

											

											$MI++;

											//print_r($Fetch_Result);exit;



									}									

								}	

								$Status = str_replace('#','',$Status);



								$Excel_G1_Export = $L_G1_Kwh - $F_G1_Kwh;

								$Excel_G2_Export = $L_G2_Kwh - $F_G2_Kwh;



								$Total_Export = $Excel_G1_Export + $Excel_G2_Export;



								$Excel_G1_Hours = $L_G1_Hours - $F_G1_Hours;

								$Excel_G2_Hours = $L_G2_Hours - $F_G2_Hours;

								$Grid_Drop_Hours = 24 - ($Excel_G1_Hours + $Excel_G2_Hours);

							}			

						}

			

		$No_Records = '<tr>

								<td width="50%" class="tab-head-td" '.$colspan.' style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>

							</tr>';				

    ?>

            </td>	

         </tr>

		 

			<?php

			// Getting the customer information

			$Fetch_Info = "select a.HTSC_No,a.LOC_No,a.WEG_No,b.Firstname, a.Site_Location as Site_Location,a.SF_No as SF_No, a.Capacity as Capacity, a.Date_Of_Commission as Date_Of_Commission,a.Connect_Feeder as Connect_Feeder,a.Device_Name as Device_Name  from device_register a,user_master b where a.Account_ID = b.Account_ID and IMEI = '".$Query_IMEI."'";//echo $Fetch_Info;
if (!$Fetch_Info_Result = $db->query($Fetch_Info))
            {
                die($db->error);
            }
            if($Fetch_Info_Result->num_rows >= 1)
            {
				$x=1;
                while($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {
					$All_HTSC_No[$x] = $Fetch_Details_Result['HTSC_No'];					

                      $All_LOC_No[$x] = $Fetch_Details_Result['LOC_No'];					

					  $All_WEG_No[$x] = $Fetch_Details_Result['WEG_No'];					

					  $All_Firstname[$x] = $Fetch_Details_Result['Firstname'];

					  $All_Devicename[$x] = $Fetch_Details_Result['Device_Name'];

					  $Site_Location[$x] = $Fetch_Details_Result['Site_Location'];

					  $SF_No[$x] = $Fetch_Details_Result['SF_No'];

					  $DOC[$x] = $Fetch_Details_Result['DOC'];

					  $Date_Of_Commission = $Fetch_Details_Result['Date_Of_Commission'];

					  $Capacity[$x] = $Fetch_Details_Result['Capacity'];

					  $Connect_Feeder[$x] = $Fetch_Details_Result['Connect_Feeder'];

					  $x++;

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
					include("Raw_Data_Hours_gamesa.php");
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
						include("Error_Log_Report_Excel_Type7_Lucky.php");
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
				if($_REQUEST['p'] == 37){
					include("Eventlog_report.php");
				}	
			?>
			

		<?php

		} // dateSearch end	

		?>		



	</table>

</body>         

</html>