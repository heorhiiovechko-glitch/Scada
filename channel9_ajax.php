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
	$Query_IMEI = "862462031475584";
	$Connect_Feeder="";
	

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
<?
	if (isset($_REQUEST["XLS"])){$XLS=1;}else{$XLS=0;}
//echo $XLS;


        if ($XLS == 1){
                $currDate = gmdate("d_M_Y");
				
				switch($_REQUEST['p']){
					case 1:
					$Title_Head = "Temperature Report";
					$colspan = 'colspan="2"';
					break;
					case 2:
					$Title_Head = "Aspire_15Min_Report";
					$colspan = 'colspan="2"';
					break;
					case 3:
					$Title_Head = "Energy_Report";
					$colspan = 'colspan="2"';
					break;
					case 4:
					$Title_Head = "Pump_Details_Report";
					$colspan = 'colspan="2"';
					break;
					case 5:
					$Title_Head = "Energy_Report_Monthly";
					$colspan = 'colspan="2"';
					break;
					case 6:
					$Title_Head = "FlowRate_Report";
					$colspan = 'colspan="2"';
					break;
						
				}
                $fName = $Title_Head."_".$currDate.".xls";
                //$fName = urlencode($fName);
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
				if(Pselect == 12 || Pselect == 13 || Pselect == 14 || Pselect == 15 || Pselect == 34){
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
                         <td align="left" class="tab-head-frame">&nbsp;&nbsp;Start Date </td>
                         <td align="left" class="tab-head-frame">&nbsp;&nbsp;End Date </td>
                         <td align="left" class="tab-head-frame"></td>
                    </tr>
                    <tr>
                        <form name="wind" onsubmit="return Date_Valid()">
                        <td align="left" style="padding-left:5px;">
                            <select name="p" id="p" style="width:180Px; padding-left:5px" onChange="For_Service_Report_Type9(this.value)">
                                     <option value="1" <?=($_REQUEST['p'] == 1?'selected=selected' : '')?>>Temperature Report</option>
			<?php
				if($Username !='alennore') {
			?>
                    			<option value="2" <?=($_REQUEST['p'] == 2?'selected=selected' : '')?>>Temperature Report-15 Min Interval</option>
			<?php
				}
			?>
					<option value="3" <?=($_REQUEST['p'] == 3?'selected=selected' : '')?>>Energy Report</option>
			<?php
				if($Username !='alennore') {
			?>
					<option value="4" <?=($_REQUEST['p'] == 4?'selected=selected' : '')?>>Pump Details Report</option>			
					<option value="5" <?=($_REQUEST['p'] == 5?'selected=selected' : '')?>>Energy Report-Monthly</option>
					<option value="6" <?=($_REQUEST['p'] == 6?'selected=selected' : '')?>>Flow Rate Report</option>
			<?php
				}
			?>
                            </select>
                        </td>
                        <td align="left">
							<?php
								if($_REQUEST['p'] == 5){
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
										foreach($Fin_Year_Array as $key => $Year_Val){
											echo $inputYear1 = "<option value=\"".$key."\" ".($_REQUEST['inputYear1'] == $key?'selected=selected' : '').">".$Year_Val."</option>";
										}
									?>
								 </select>
							</div>
							<div id="InputMonthYearDiv" style="display:<?=$Visible2_Status?>">
								<select name="inputMonthYear" id="inputMonthYear" class="Reg_Select">
										<option value="0">---Select Month---</option>
									<?php
										foreach($Aspire_Month_Year_Array as $key => $Year_Month_Val){
											echo $inputMonthYear = "<option value=\"".$key."\" ".($_REQUEST['inputMonthYear'] == $key?'selected=selected' : '').">".$Year_Month_Val."</option>";
										}
									?>
								 </select>
							</div>
						</td>
                        <td align="left">
							<input class="inputDate1"  name="inputDate1" id="inputDate1" value="<?=$InputDate1?>" style="width:80Px;display:<?=$Visible_Status1?>" />
						</td>
                        <td align="center"><input type="hidden" name="FType" value="<?=$_REQUEST['FType']?>" /><input type="hidden" name="c1" value="<?=$_REQUEST['c1']?>" /><input type="submit" name="dateSearch" id="dateSearch" value="GO"></td>
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
							$From_D_Epoch = strtotime($_REQUEST['inputDate']);
							$To_D_Epoch = strtotime($_REQUEST['inputDate1']);
							$From_YMD= date("Y-m-d",$From_D_Epoch);
							$To_YMD= date("Y-m-d",$To_D_Epoch);
							
							$IMEI = $_REQUEST['c1'];
							$Format=$_REQUEST['FType'];
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
								
							if($_REQUEST['p'] == 1 ){
								
								$Date_Range =  getDaysInBetween($_REQUEST['inputDate'],$_REQUEST['inputDate1']);
							} else
							{
								$Date_Range =  getDaysInBetween($_REQUEST['inputDate'],$_REQUEST['inputDate1']);

							} // Else end
						} // Request P end
							$No_Records = '<tr>
								<td width="50%" class="tab-head-td" '.$colspan.' style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
							</tr>';				
    ?>
            </td>	
         </tr>
		 
		
		 <?
		 ##########################################################
		 #
		 #	Just for Report in the browser
		 #
		 ##########################################################
		 ?>
		 
		<?php
			#
			#	Temperature Report
			#
            if($_REQUEST['p'] == 1){
				include("Aspire_Temperature_Report.php");
			}
	    if($_REQUEST['p'] == 2){
				include("Aspire_Interval_Report.php");
			}
	    if($_REQUEST['p'] == 3){
				include("Aspire_Energy_Report.php");
			}
	    if($_REQUEST['p'] == 4){
				include("Aspire_Pump_Report.php");
			}
	if($_REQUEST['p'] == 5){
				include("Aspire_Energy_Monthly_Report.php");
			}
		if($_REQUEST['p'] == 6){
				include("Aspire_FlowRate_Report.php");
			}
	   	?>
		 
		         
		<?php
		} // dateSearch end	
		?>		

	</table>
</body>         
</html>