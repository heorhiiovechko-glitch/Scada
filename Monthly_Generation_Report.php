	<?php
 ini_set("max_execution_time", 3600);
 $Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
 if (isset($Cook_Variable)) {
     $Username = $Cook_Variable[0];
     $Account_ID = $Cook_Variable[3];
     $Firstname = $Cook_Variable[4];
     $Lastname = $Cook_Variable[5];
	 $Query_IMEI = base64_decode($_REQUEST['c1']);
 }
 $FType = $_REQUEST["FType"]; //echo $FType;
 // Getting the customer information
 if ($Cook_Variable[2] == 4) {
     $Fetch_Info1 =
         "select * from device_register where Account_ID = " .
         $Account_ID .
         " order by Device_Order";
 }
 if ($Cook_Variable[2] == 3) {
     $Fetch_Info1 =
         "select * from device_register where Parent_ID = " .
         $Account_ID .
         " order by Device_Order";
 }

 echo $Fetch_Info1;
 if (!($Fetch_Info_Result1 = $db->query($Fetch_Info1))) {
     die($db->error);
 }
 if ($Fetch_Info_Result1->num_rows >= 1) {
     $x = 1;
     while ($Fetch_Details_Result1 = $Fetch_Info_Result1->fetch_array()) {
         $WFG_HTSC_No[$Fetch_Details_Result1["IMEI"]] =
             $Fetch_Details_Result1["HTSC_No"];
         $WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] =
             $Fetch_Details_Result1["Format_Type"];
         $WFG_Devicename[$Fetch_Details_Result1["IMEI"]] =
             $Fetch_Details_Result1["Device_Name"];
         $WFG_IMEI[$Fetch_Details_Result1["IMEI"]] =
             $Fetch_Details_Result1["IMEI"];
         $WFG_KEY[$Fetch_Details_Result1["IMEI"]] =
             $Fetch_Details_Result1["Device_Index"];
         $WFG_Site_Location[] = $Fetch_Details_Result1["Site_Location"];
         if ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 1) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 2) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data_f2";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 3) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data_f3";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 4) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data_f4";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 6) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data_f6";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 7) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data_f7";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 8) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data_f8";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 9) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] = "device_data_f9";
         } elseif ($WFG_Format_Type[$Fetch_Details_Result1["IMEI"]] == 10) {
             $WFG_Table_Name[$Fetch_Details_Result1["IMEI"]] =
                 "device_data_f10";
         }
     }
 }
 //if($Username=='lucky') {
 //$Year_Start_dmy = "01-04-" . $_REQUEST["inputYear"];
 //$Year_End_dmy = "31-03-" . ($_REQUEST["inputYear"] + 1);
 $Year_Start_dmy = "01-04-" . ($_REQUEST["inputYear"] - 1);
 $Year_End_dmy = "31-03-" . ($_REQUEST["inputYear"]);
 $Year_Start = date("Y-m-d", strtotime($Year_Start_dmy));
 $Year_End = date("Y-m-d", strtotime($Year_End_dmy));
 //echo $Year_End;
 $From_Year_D_Epoch = strtotime($Year_Start) + 60 * 60 * 5.5;
 $To_Year_D_Epoch = strtotime($Year_End . " 23:59:59") + 60 * 60 * 5.5;

//} else {
/*$Year_Start = "01-01-".$_REQUEST['inputYear'];
	$Year_End= "31-12-".$_REQUEST['inputYear'];
	$From_Year_D_Epoch = strtotime($Year_Start)+(60*60*5.5);
	$To_Year_D_Epoch = strtotime($Year_End." 23:59:59")+(60*60*5.5);
	}*/
/*$DGR_Start_Datemonth=$_REQUEST['inputMonthYear'] ;
		$MonthYearArr=explode("-",$DGR_Start_Datemonth);
	
    	$Month = date('m', strtotime($MonthYearArr[0]));
  
	  	$Year = date('Y', strtotime($MonthYearArr[1]));

	
    	$Total_Days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
		$DGR_Start_Date="01-".$_REQUEST['inputMonthYear'];
//echo $DGR_Start_Date;
		$End_Date=date(t);
		  $DGR_End_Date=$End_Date."-".$_REQUEST['inputMonthYear'];
//echo  $DGR_End_Date;
		$From_D_Epoch = strtotime($DGR_Start_Date);
							$To_D_Epoch = strtotime($DGR_End_Date);*/
?>

<?php if ($XLS == 0) { ?>
		<tr>
			<td colspan="5" align="center" style="font-size:small">
				<!--<b>&nbsp;Please click the below link to Download the excel Report</b><br /><br />-->
			<?php
   if ($FType == 1 || $FType == 6) { ?>
				<a href='channel2_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php }
   if ($FType == 2) { ?>
				<a href='channel3_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>

			<?php }
   if ($FType == 3) { ?>
				<a href='channel4_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php }
   if ($FType == 4) { ?>
				<a href='channel5_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php }
   if ($FType == 7 || $FType == 8) { ?>
				<a href='channel8_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			<?php }
   if ($FType == 10) { ?>
				<a href='channel10_ajax.php?<?= $_SERVER[
        "QUERY_STRING"
    ] ?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here to download</a>
			
			<?php }
   ?>

			</td>
		</tr>
<?php } ?>
 
  <tr>
            <td width="100%">
                   <table width="90%" border="<?= $XLS == 1
                       ? "1"
                       : "0" ?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                  
<?php if ($XLS == 1) { ?>
  <tr>
                        <td class="tab-head-td" colspan="<?= $Fetch_Info_Result_Count1 +
                            2 ?>"  align="center" style="text-align:center;"><b><?= $Firstname .
    " " .
    $Lastname ?> - Monthly Generation Detail</b></td>
                    </tr>                           
			
				    <tr>
						<td class="tab-head-td" align="left" width="100px"><b>Site Location</b></td>
						<td class="tab-head-td" align="left" colspan="<?= $Fetch_Info_Result_Count1 ?>"><b><? echo  implode(",",array_unique($WFG_Site_Location)); ?></b></td>
                        <td class="tab-head-td" width="12px" align="left">&nbsp;</td>											
                   </tr>
<?php } ?>
<?php if ($XLS == 0) { ?>

		<tr style="border:0px"><td colspan="<?= $Fetch_Info_Result_Count1 +
      1 ?>">&nbsp;</td></tr>
<?php } ?>
<?php
if ($Fetch_Info_Result1->num_rows >= 1) { ?>
        
 		      <tr>
						<td class="tab-head-td" align="left"><b>Month</b></td>
					<?php
     /*foreach ($WFG_HTSC_No as $WFG_HTSC_No_Val) {
         echo '<td class="tab-head-td" align="left">' .
             $WFG_HTSC_No_Val .
             "</td>";
     }*/
     echo '<td class="tab-head-td" align="left">'.$Query_IMEI."</td>";
     //echo '<td class="tab-head-td" align="left"><b>Total</b></td>';
     ?>					
                    </tr>
                    <tr>
						<td class="tab-head-td" align="center"><b></b></td>
					<?php
     /*foreach ($WFG_Devicename as $WFG_Devicename_Val) {
         echo '<td class="tab-head-td" align="left">' .
             $WFG_Devicename_Val .
             "</td>";
     }*/
     echo '<td class="tab-head-td" align="left"></td>';
     ?>					
                    </tr>

<?php
/*
foreach ($WFG_IMEI as $IMEI) {
	$Monthly_Generation_Query = "";
    if ($WFG_Format_Type[$IMEI] == 1 || $WFG_Format_Type[$IMEI] == 6) {
        $Monthly_Generation_Query =
            "select month(Date_S) as Month,sum(Gen1_Max-Gen1_Min) as GAM_G1 from daily_data where  IMEI='$IMEI'and (Date_S between '" .
            $Year_Start .
            "' and '" .
            $Year_End .
            "') group by month(Date_S)";
			echo "1";
    }
    else if ($WFG_Format_Type[$IMEI] == 2 || $WFG_Format_Type[$IMEI] == 4) {
        $Monthly_Generation_Query =
            "SELECT 
    month(Date_S) as Month,
    MAX(Gen1_Max) as MAX_SUM,
    MIN(Gen1_Min) as MIN_SUM
FROM daily_data
WHERE IMEI='$IMEI'
AND Date_S BETWEEN '$Year_Start' AND '$Year_End'
GROUP BY month(Date_S)";
echo "2";
    }
    else if ($WFG_Format_Type[$IMEI] == 10 || $WFG_Format_Type[$IMEI] == 3) {
        $Monthly_Generation_Query =
            "select month(Date_S) as Month,sum(Gen1_Max-Gen1_Min) as GAM_G1 from daily_data where  IMEI='$IMEI'and (Date_S between '" .
            $Year_Start .
            "' and '" .
            $Year_End .
            "') group by month(Date_S)";
			echo "3";
    }
    else if ($WFG_Format_Type[$IMEI] == 7 || $WFG_Format_Type[$IMEI] == 8) {
        $Monthly_Generation_Query =
            "select month(Date_S) as Month,sum(Gen1_Max) as GAM_G1 from daily_data where  IMEI='$IMEI'and (Date_S between '" .
            $Year_Start .
            "' and '" .
            $Year_End .
            "') group by month(Date_S)";
			echo "4";
    } echo $Monthly_Generation_Query;
    if (!($Monthly_Generation_Result = $db->query($Monthly_Generation_Query))) {
        die($db->error);
    }
    if ($Monthly_Generation_Result->num_rows >= 1) {
        while (
            $Monthly_Generation_Fetch_Details = $Monthly_Generation_Result->fetch_array()
        ) {
            $Monthly_Generation["$IMEI"][
                $Monthly_Generation_Fetch_Details["Month"]
            ] = $Monthly_Generation_Fetch_Details["GAM_G1"];
            $Monthly_Generation["$IMEI"][
                $Monthly_Generation_Fetch_Details["Month"]
            ] =
                $Monthly_Generation["$IMEI"][
                    $Monthly_Generation_Fetch_Details["Month"]
                ] <= "400000" &&
                $Monthly_Generation["$IMEI"][
                    $Monthly_Generation_Fetch_Details["Month"]
                ] > "0"
                    ? round(
                        $Monthly_Generation["$IMEI"][
                            $Monthly_Generation_Fetch_Details["Month"]
                        ],
                        1
                    )
                    : "NIL";
            $Month_Num["$IMEI"][$Monthly_Generation_Fetch_Details["Month"]] =
                $Monthly_Generation_Fetch_Details["Month"];
            $Total_Gen["$IMEI"] += $Monthly_Generation_Fetch_Details["GAM_G1"];
            $Total_Gen["$IMEI"] =
                $Total_Gen["$IMEI"] > "0" ? $Total_Gen["$IMEI"] : "Nil";
        } //echo "<td class='tab-head-td' align='left'>".$Total_Gen."</td></tr>";
    }
} //print_r($Month_Num);
*/

    if ($WFG_Format_Type[$Query_IMEI] == 1 || $WFG_Format_Type[$Query_IMEI] == 6) {
        $Monthly_Generation_Query =
            "select month(Date_S) as Month,sum(Gen1_Max-Gen1_Min) as GAM_G1 from daily_data where  IMEI='$Query_IMEI'and (Date_S between '" .
            $Year_Start .
            "' and '" .
            $Year_End .
            "') group by month(Date_S)";
			echo "1";
    }
    else if ($WFG_Format_Type[$Query_IMEI] == 2 || $WFG_Format_Type[$Query_IMEI] == 4) {
        $Monthly_Generation_Query =
            /*"SELECT 
			month(Date_S) as Month,
			MAX(Gen1_Max) as MAX_SUM,
			MIN(Gen1_Min) as MIN_SUM
			FROM daily_data
			WHERE IMEI='$IMEI'
			AND Date_S BETWEEN '$Year_Start' AND '$Year_End'
			GROUP BY month(Date_S)";*/
            "select month(Date_S) as Month,sum(Gen1_Max-Gen1_Min) as GAM_G1 from daily_data where  IMEI='$Query_IMEI'and (Date_S between '" .
            $Year_Start .
            "' and '" .
            $Year_End .
            "') group by month(Date_S)";
			echo "2";
    }
    else if ($WFG_Format_Type[$Query_IMEI] == 10 || $WFG_Format_Type[$Query_IMEI] == 3) {
        $Monthly_Generation_Query =
            "select month(Date_S) as Month,sum(Gen1_Max-Gen1_Min) as GAM_G1 from daily_data where  IMEI='$Query_IMEI'and (Date_S between '" .
            $Year_Start .
            "' and '" .
            $Year_End .
            "') group by month(Date_S)";
			echo "3";
    }
    else if ($WFG_Format_Type[$Query_IMEI] == 7 || $WFG_Format_Type[$Query_IMEI] == 8) {
        $Monthly_Generation_Query =
            "select month(Date_S) as Month,sum(Gen1_Max) as GAM_G1 from daily_data where  IMEI='$Query_IMEI'and (Date_S between '" .
            $Year_Start .
            "' and '" .
            $Year_End .
            "') group by month(Date_S)";
			echo "4";
    } echo $Monthly_Generation_Query;
    if (!($Monthly_Generation_Result = $db->query($Monthly_Generation_Query))) {
        die($db->error);
    }
    if ($Monthly_Generation_Result->num_rows >= 1) {
        while ($Monthly_Generation_Fetch_Details = $Monthly_Generation_Result->fetch_array()) {
            $Monthly_Generation["$Query_IMEI"][$Monthly_Generation_Fetch_Details["Month"]] = $Monthly_Generation_Fetch_Details["GAM_G1"];
            $Monthly_Generation["$Query_IMEI"][$Monthly_Generation_Fetch_Details["Month"]] = $Monthly_Generation["$Query_IMEI"][$Monthly_Generation_Fetch_Details["Month"]] <= "400000" &&
                $Monthly_Generation["$Query_IMEI"][
                    $Monthly_Generation_Fetch_Details["Month"]
                ] > "0"
                    ? round(
                        $Monthly_Generation["$Query_IMEI"][
                            $Monthly_Generation_Fetch_Details["Month"]
                        ],
                        1
                    )
                    : "NIL";
            $Month_Num["$Query_IMEI"][$Monthly_Generation_Fetch_Details["Month"]] =
                $Monthly_Generation_Fetch_Details["Month"];
            $Total_Gen["$Query_IMEI"] += $Monthly_Generation_Fetch_Details["GAM_G1"];
            $Total_Gen["$Query_IMEI"] =
                $Total_Gen["$Query_IMEI"] > "0" ? $Total_Gen["$Query_IMEI"] : "Nil";
        } //echo "<td class='tab-head-td' align='left'>".$Total_Gen."</td></tr>";
    }


$Months = [
    "1" => "Jan-" . ($_REQUEST["inputYear"] + 1) . "",
    "2" => "Feb-" . ($_REQUEST["inputYear"] + 1) . "",
    "3" => "Mar-" . ($_REQUEST["inputYear"] + 1) . "",
    "4" => "Apr-" . $_REQUEST["inputYear"] . "",
    "5" => "May-" . $_REQUEST["inputYear"] . "",
    "6" => "Jun-" . $_REQUEST["inputYear"] . "",
    "7" => "Jul-" . $_REQUEST["inputYear"] . "",
    "8" => "Aug-" . $_REQUEST["inputYear"] . "",
    "9" => "Sep-" . $_REQUEST["inputYear"] . "",
    "10" => "Oct-" . $_REQUEST["inputYear"] . "",
    "11" => "Nov-" . $_REQUEST["inputYear"] . "",
    "12" => "Dec-" . $_REQUEST["inputYear"] . "",
];
$All_IMEI_Total_Gen = 0;
for ($Count = 0; $Count <= 11; $Count++) {
    echo '<tr><td class="tab-head-td" align="left">' .
        $Months[$Count + 1] .
        "</td>";
    /*foreach ($WFG_IMEI as $IMEI_Val1) {
        $All_IMEI_Total_Gen += $Monthly_Generation[$IMEI_Val1][$Count + 1];
        echo '<td class="tab-head-td" align="left">' .
            $Monthly_Generation[$IMEI_Val1][$Count + 1] .
            "</td>";
    }*/
    echo '<td class="tab-head-td" align="left">' .$Monthly_Generation[$Query_IMEI][$Count + 1]."</td>";

    //echo '<td class="tab-head-td" align="left">' .$All_IMEI_Total_Gen ."</td>";
    $All_IMEI_Total_Gen = 0;
    echo "</tr>";
}
echo '<td class="tab-head-td" align="left"><b>Total</b></td>';
/*foreach ($WFG_IMEI as $IMEI_Val1) {
    echo '<td class="tab-head-td" align="left">' .
        $Total_Gen["$IMEI_Val1"] .
        "</td>";
}*/
echo '<td class="tab-head-td" align="left">'.$Total_Gen["$Query_IMEI"]."</td>";
echo "</tr>"; //	echo "<td class='tab-head-td' align='left'>".$MonthName."</td>";
}
echo "</table>"; //}
 ?>
