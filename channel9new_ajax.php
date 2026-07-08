<?php
error_reporting(0);
include("Includes.php");
?>

<?php
$Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);

if (isset($Cook_Variable)) {
    $Username    = $Cook_Variable[0];
    $Account_ID  = $Cook_Variable[3];
}

$Query_IMEI      = base64_decode($_REQUEST['c1']);
$Pocket_Length   = $_REQUEST['l'];
$FType           = $_REQUEST['FType'];
$Connect_Feeder  = $_REQUEST['Feeder'];

# Time array
$Time_Arr = range(0, 24);
foreach ($Time_Arr as $Time_Val) {
    $Str_Len = strlen($Time_Val);
    if ($Str_Len == 1) {
        $Time_Val = "0" . $Time_Val;
    }
    $Time_24_Array["k" . $Time_Val] = '';
}
?>

<style>

/* ===== CHANGE THEME TO BLUE SCADA ===== */

/* Header (Reports / Start Date / End Date) */
.tab-head-frame{
    background:#1f3c88 !important;
    color:#ffffff !important;
    border:1px solid #2f55b0 !important;
}

/* Table structure */
.innertab1{
    border-collapse:collapse;
}

/* Table rows */
.innertab1 td{
    border-bottom:1px solid #e0e0e0;
    font-size:13px;
}

/* Alternate row color (if used later) */
.innertab1 tr:nth-child(even){
    background:#f8faff;
}

/* Hover effect */
.innertab1 tr:hover{
    background:#eaf0ff;
}

/* Dropdown */
select{
    border:1px solid #ccc;
    border-radius:4px;
    padding:4px;
}

/* Date inputs */
.inputDate, .inputDate1{
    border:1px solid #ccc;
    border-radius:4px;
    padding:4px;
}

/* GO button */
input[type="submit"]{
    background:#1f3c88;
    color:#fff;
    border:none;
    padding:5px 12px;
    border-radius:4px;
    cursor:pointer;
    font-weight:600;
}

input[type="submit"]:hover{
    background:#2f55b0;
}

/* Page background (optional match) */
body{
    background:#f3f6fb;
}

</style>



<?php
# Getting the customer information

$Fetch_Info = "
    SELECT 
        a.HTSC_No, a.LOC_No, a.WEG_No, a.Group_Name,
        b.Firstname, b.Lastname,
        a.Site_Location AS Site_Location,
        a.SF_No AS SF_No, 
        a.Capacity AS Capacity, 
        a.Date_Of_Commission AS Date_Of_Commission,
        a.Connect_Feeder AS Connect_Feeder,
        a.Device_Name AS Device_Name
    FROM 
        device_register a, user_master b
    WHERE 
        a.Account_ID = b.Account_ID 
    AND 
        IMEI = '" . $Query_IMEI . "'
";

if (!$Fetch_Info_Result = $db->query($Fetch_Info)) {
    die($db->error);
}

if ($Fetch_Info_Result->num_rows >= 1) {
    $x = 1;
    while ($Fetch_Details_Result = $Fetch_Info_Result->fetch_array()) {

        $All_REGACC_IMEI     = $Fetch_Details_Result['IMEI'];
        $All_HTSC_No[$x]     = $Fetch_Details_Result['HTSC_No'];
        $All_LOC_No[$x]      = $Fetch_Details_Result['LOC_No'];
        $All_WEG_No[$x]      = $Fetch_Details_Result['WEG_No'];
        $Connect_Feeder[$x]  = $Fetch_Details_Result['Connect_Feeder'];
        $All_Firstname[$x]   = $Fetch_Details_Result['Firstname'];
        $All_Lastname[$x]    = $Fetch_Details_Result['Lastname'];
        $All_Devicename[$x]  = $Fetch_Details_Result['Device_Name'];
        $All_groupname[$x]   = $Fetch_Details_Result['Group_Name'];
        $Site_Location[$x]   = $Fetch_Details_Result['Site_Location'];
        $DOC[$x]             = $Fetch_Details_Result['DOC'];
        $SF_No[$x]           = $Fetch_Details_Result['SF_No'];
        $Date_Of_Commission  = $Fetch_Details_Result['Date_Of_Commission'];
        $Capacity[$x]        = $Fetch_Details_Result['Capacity'];
        $Connect_Feeder[$x]  = $Fetch_Details_Result['Connect_Feeder'];

        $x++;
    }
}
?>

<?php
if (isset($_REQUEST["XLS"])) {
    $XLS = 1;
} else {
    $XLS = 0;
}

if ($XLS == 1) {

    $currDate = gmdate("d_M_Y");

    switch ($_REQUEST['p']) {

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

        case 28:
            $Title_Head = "DGR_ERP_Report";
            $colspan = 'colspan="3"';
            break;

        case 29:
            $Title_Head = "DGR_Grouping_Report";
            $colspan = 'colspan="3"';
            break;

        case 33:
            $Title_Head = "" . $All_groupname[1] . "";
            break;

        case 34:
            $Title_Head = "Cumulative_DGR_Report";
            break;

        case 35:
            $Title_Head = "Total_Machine_Monthly_Report";
            break;

        case 36:
            $Title_Head = "Monthly_Individual_Report";
            break;

        case 38:
            $Title_Head = "Invoice_Report";
            break;

        case 40:
            $Title_Head = "EventLog_Report";
            break;

        case 41:
            $Title_Head = "GRPM_Report";
            break;

        case 42:
            $Title_Head = "Status_Report";
            break;

        case 43:
            $Title_Head = "Pitch_Report";
            break;
    }

    $fName = $Title_Head . "_" . $currDate . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment;filename=$fName");
}

# When XLS = 0 (normal view)
if ($XLS == 0) {

    switch ($_REQUEST['p']) {

        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
            $colspan = 'colspan="2"';
            break;

        case 10:
            $colspan = 'colspan="3"';
            break;

        case 11:
        case 12:
        case 13:
        case 14:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
        case 20:
        case 21:
        case 22:
            $colspan = 'colspan="2"';
            break;
    }
}
?>

<?php
if ($_REQUEST['inputDate']) {
    $InputDate = $_REQUEST['inputDate'];
} else {
    $InputDate = date("d-m-Y");
}

if ($_REQUEST['inputDate1']) {
    $InputDate1 = $_REQUEST['inputDate1'];
} else {
    $InputDate1 = date("d-m-Y");
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

    <?php if ($XLS == 0) { ?>
        <link rel="stylesheet" href="./css/datepicker.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="./css/Style.css" />
    <?php } ?>   

    <script>
        function Date_Valid() {

            Current_Date1 = new Date(<?= date('Y, m-1, d') ?>);

            Pselect = document.getElementById('p').value;

            From_Date = document.getElementById('inputDate').value;
            From_Date_Arr = From_Date.split('-');
            From_Date1 = new Date(From_Date_Arr[2], From_Date_Arr[1] - 1, From_Date_Arr[0]);

            To_Date = document.getElementById('inputDate1').value;
            To_Date_Arr = To_Date.split('-');
            To_Date1 = new Date(To_Date_Arr[2], To_Date_Arr[1] - 1, To_Date_Arr[0]);

            var Check_Current_Date = Current_Date1.getTime();
            var Check_From_Date = From_Date1.getTime();
            var Check_To_Date = To_Date1.getTime();

            // Reports restricted to single day
            if (Pselect == 12 || Pselect == 13 || Pselect == 14 || Pselect == 15 || Pselect == 34) {
                if ((Check_From_Date - Check_To_Date) != 0) {
                    document.getElementById('date_valid_div').innerHTML = 'Should not select more than one day';
                    return false;
                }
            }

            else if (Pselect == 17) {
                var inputYear = document.getElementById('inputYear').value;
                if (inputYear == 0) {
                    document.getElementById('date_valid_div').innerHTML = 'Please select the Year';
                    return false;
                }
            }

            else if (Pselect == 45) {
                var inputYear1 = document.getElementById('inputYear1').value;
                if (inputYear1 == 0) {
                    document.getElementById('date_valid_div').innerHTML = 'Please select the Year';
                    return false;
                }
            }

            if (Check_Current_Date < Check_From_Date) {
                document.getElementById('date_valid_div').innerHTML = 'Please check the Start date';
                return false;
            }

            if (Check_Current_Date < Check_To_Date) {
                document.getElementById('date_valid_div').innerHTML = 'Please check the End date';
                return false;
            }

            return true;
        }
    </script>

</head>
<body>
    <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1" class="innertab1">

    <?php if ($XLS == 0) { ?>
        <tr>
            <td valign="top">
                <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1" class="innertab1">

                    <tr>
                        <td align="left" class="tab-head-frame" width="200px">&nbsp;&nbsp;Reports</td>
                        <td align="left" class="tab-head-frame">&nbsp;&nbsp;Start Date</td>
                        <td align="left" class="tab-head-frame">&nbsp;&nbsp;End Date</td>
                        <td align="left" class="tab-head-frame"></td>
                    </tr>

                    <tr>
                        <form name="wind" onsubmit="return Date_Valid()">
                            <td align="left" style="padding-left:5px;">

                                <select name="p" id="p" style="width:180px; padding-left:5px"
                                        onChange="For_Service_Report_Type6(this.value)">

                                    <option value="1"  <?= ($_REQUEST['p'] == 1 ? 'selected=selected' : '') ?>>Power Vs Wind Speed</option>
                                    <option value="2"  <?= ($_REQUEST['p'] == 2 ? 'selected=selected' : '') ?>>Overview Report</option>
                                    <option value="3"  <?= ($_REQUEST['p'] == 3 ? 'selected=selected' : '') ?>>Temperature Report</option>
                                    <option value="4"  <?= ($_REQUEST['p'] == 4 ? 'selected=selected' : '') ?>>Production Report</option>
                                    <option value="5"  <?= ($_REQUEST['p'] == 5 ? 'selected=selected' : '') ?>>Grid Report</option>

                                    <!-- Disabled legacy options
                                    <option value="6" <?= ($_REQUEST['p'] == 6 ? 'selected=selected' : '') ?>>Daily EB Slot Reading</option>
                                    <option value="29" <?= ($_REQUEST['p'] == 29 ? 'selected=selected' : '') ?>>DGR Grouping Report</option>
                                    <option value="19" <?= ($_REQUEST['p'] == 19 ? 'selected=selected' : '') ?>>Power and Windspeed curves</option>
                                    <option value="7" <?= ($_REQUEST['p'] == 7 ? 'selected=selected' : '') ?>>Stop Hours Report</option>
                                    <option value="8" <?= ($_REQUEST['p'] == 8 ? 'selected=selected' : '') ?>>Alarm Log Individual</option>
                                    -->

                                    <option value="9"  <?= ($_REQUEST['p'] == 9 ? 'selected=selected' : '') ?>>Alarm Log Report</option>
                                    <option value="14" <?= ($_REQUEST['p'] == 14 ? 'selected=selected' : '') ?>>Error Log Report</option>
                                    <option value="19" <?= ($_REQUEST['p'] == 19 ? 'selected=selected' : '') ?>>Power and Windspeed curves</option>
                                    <option value="10" <?= ($_REQUEST['p'] == 10 ? 'selected=selected' : '') ?>>Daily Generation Individual Report</option>

                                </select>
                            </td>

                            <td align="left">

                                <?php
                                // Visibility logic for date / year / month dropdowns

                                if ($_REQUEST['p'] == 20 || $_REQUEST['p'] == 27 || $_REQUEST['p'] == 37 || $_REQUEST['p'] == 38) {
                                    $Visible1_Status = "none";
                                    $Visible_Status1 = "none";
                                    $Visible_Status  = "none";
                                    $Visible2_Status = "block";
                                }
                                elseif ($_REQUEST['p'] == 12) {
                                    $Visible_Status  = "block";
                                    $Visible_Status1 = "none";
                                    $Visible1_Status = "none";
                                    $Visible2_Status = "none";
                                }
                                elseif ($_REQUEST['p'] == 35 || $_REQUEST['p'] == 36) {
                                    $Visible_Status  = "none";
                                    $Visible_Status1 = "none";
                                    $Visible1_Status = "none";
                                    $Visible2_Status = "block";
                                }
                                elseif ($_REQUEST['p'] == 13) {
                                    $Visible1_Status = "block";
                                    $Visible_Status1 = "none";
                                    $Visible_Status  = "none";
                                    $Visible2_Status = "none";
                                }
                                else {
                                    $Visible_Status  = "none";
                                    $Visible_Status1 = "block";
                                    $Visible1_Status = "none";
                                    $Visible2_Status = "none";
                                }
                                ?>

                                <input class="inputDate" name="inputDate" id="inputDate"
                                       value="<?= $InputDate ?>"
                                       style="width:80px; display:<?= $Visible_Status1 ?>" />

                                <div id="InputYearDiv" style="display:<?= $Visible_Status ?>">
                                    <select name="inputYear" id="inputYear" class="Reg_Select">
                                        <option value="0">---Select Year---</option>
                                        <?php
                                        foreach ($Year_Array as $key => $Years) {
                                            echo "<option value=\"$Years\" "
                                               . ($_REQUEST['inputYear'] == $Years ? 'selected=selected' : '')
                                               . ">$Years</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div id="InputYearDiv1" style="display:<?= $Visible1_Status ?>">
                                    <select name="inputYear1" id="inputYear1" class="Reg_Select">
                                        <option value="0">---Select Year---</option>
                                        <?php
                                        foreach ($Fin_Year_Array as $key => $Year_Val) {
                                            echo "<option value=\"$key\" "
                                               . ($_REQUEST['inputYear1'] == $key ? 'selected=selected' : '')
                                               . ">$Year_Val</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div id="InputMonthYearDiv" style="display:<?= $Visible2_Status ?>">
                                    <select name="inputMonthYear" id="inputMonthYear" class="Reg_Select">
                                        <option value="0">---Select Month---</option>
                                        <?php
                                        foreach ($Month_Year_Array as $key => $Year_Month_Val) {
                                            echo "<option value=\"$key\" "
                                               . ($_REQUEST['inputMonthYear'] == $key ? 'selected=selected' : '')
                                               . ">$Year_Month_Val</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                            </td>

                            <td align="left">
                                <input class="inputDate1" name="inputDate1" id="inputDate1"
                                       value="<?= $InputDate1 ?>"
                                       style="width:80px; display:<?= $Visible_Status1 ?>" />
                            </td>

                            <td align="center">
                                <input type="hidden" name="l" value="<?= $_REQUEST['l'] ?>" />
                                <input type="hidden" name="FType" value="<?= $_REQUEST['FType'] ?>" />
                                <input type="hidden" name="c1" value="<?= $_REQUEST['c1'] ?>" />
                                <input type="submit" name="dateSearch" id="dateSearch" value="GO" />
                            </td>
                        </form>
                    </tr>

                    <tr>
                        <td align="center" colspan="4" height="10px">
                            <hr size="1">
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    <?php } ?>


<tr>
    <td>
        <div id="date_valid_div" style="color:red"></div>

        <?php
        if (isset($_REQUEST['dateSearch'])) {

            // After clicking submit
            if (isset($_REQUEST['p'])) {

                // Convert dates
                $From_D_Epoch = strtotime($_REQUEST['inputDate']);
                $To_D_Epoch   = strtotime($_REQUEST['inputDate1']);

                $From       = date("d.m.Y", $From_D_Epoch);
                $To         = date("d.m.Y", $To_D_Epoch);
                $From_Error = $From;
                $To_Error   = $To;

                $From_YMD = date("Y-m-d", $From_D_Epoch);
                $To_YMD   = date("Y-m-d", $To_D_Epoch);

                $IMEI = base64_decode($_REQUEST['c1']);

                //
                // Select correct table based on FType
                //
                if ($FType == 6) {
                    $Table_Name = "device_data_f6";
                    $Error_Table = "error_data_f6";
                } elseif ($FType == 10) {
                    $Table_Name = "device_data_f10";
                    $Error_Table = "error_data_f10";
                } elseif ($FType == 9) {
                    $Table_Name = "device_data_f9";
                    $Error_Table = "error_data_f9";
                } else {
                    $Table_Name = "device_data";
                    $Error_Table = "error_data";
                }

                //
                // EB REPORT (p = 6)
                //
                if ($_REQUEST['p'] == 6) {

                    $Date_Range = getDaysInBetween($_REQUEST['inputDate'], $_REQUEST['inputDate1']);

                    foreach ($Date_Range as $Date_Range_Val) {

                        $Date_Epoch      = $Date_Range_Val[0];
                        $Date_YMD        = date("Y-m-d", $Date_Epoch);
                        $Date_DMYYYY     = date("dmY", $Date_Epoch);

                        // Query for hourly data
                        $Mysql_Query1[$Date_DMYYYY] = "
                            SELECT DISTINCT(SUBSTRING(Time_S,1,2)) AS Time_24,
                                   Time_S, Date_S, PAT_Gen2, Record_Index
                            FROM $Cook_Variable[7].$Table_Name
                            WHERE IMEI = '$IMEI'
                              AND Date_S = '$Date_YMD'
                              AND PAT_Gen2 != ''
                              AND PAT_Gen2 > '1'
                            GROUP BY Time_24
                            ORDER BY Time_24 ASC
                        ";

                        if (!$Mysql_Query_Result1[$Date_DMYYYY] = $db->query($Mysql_Query1[$Date_DMYYYY])) {
                            die($db->error);
                        }

                        $Mysql_Record_Count1[$Date_DMYYYY] = $Mysql_Query_Result1[$Date_DMYYYY]->num_rows;

                        $MI = 1;

                        if ($Mysql_Query_Result1[$Date_DMYYYY]->num_rows >= 1) {

                            while ($Fetch_Result1[$Date_DMYYYY] = $Mysql_Query_Result1[$Date_DMYYYY]->fetch_array()) {

                                $Fetch_Result2 = $Fetch_Result1[$Date_DMYYYY];

                                $PAT_Total_24[$Date_DMYYYY]["k" . $Fetch_Result2['Time_24']] =
                                    $Fetch_Result2['Record_Index'];

                                $All_Date_Arr[$Date_DMYYYY] = $Fetch_Result2['Date_S'];

                                $MI++;
                            }
                        }

                        // Merge blank array + actual result for 24-hour alignment
                        $PAT_Total_24_Merge[$Date_DMYYYY] =
                            array_merge($Time_24_Array, $PAT_Total_24[$Date_DMYYYY]);

                        //
                        // Get all DB records for the day
                        //
                        $Mysql_Query[$Date_DMYYYY] = "
                            SELECT Time_S, Date_S, PAT_Gen2, Record_Index
                            FROM $Cook_Variable[7].$Table_Name
                            WHERE IMEI = '$IMEI'
                              AND Date_S = '$Date_YMD'
                              AND PAT_Gen2 != ''
                              AND PAT_Gen2 > '1'
                            ORDER BY Record_Index ASC
                        ";

                        if (!$Mysql_Query_Result[$Date_DMYYYY] = $db->query($Mysql_Query[$Date_DMYYYY])) {
                            die($db->error);
                        }

                        $Mysql_Record_Count[$Date_DMYYYY] =
                            $Mysql_Query_Result[$Date_DMYYYY]->num_rows;

                        $MI = 1;

                        if ($Mysql_Query_Result[$Date_DMYYYY]->num_rows >= 1) {

                            while ($Fetch_Result[$Date_DMYYYY] = $Mysql_Query_Result[$Date_DMYYYY]->fetch_array()) {

                                $Fetch_Result3 = $Fetch_Result[$Date_DMYYYY];

                                $PAT_Total[$Date_DMYYYY][$MI] = $Fetch_Result3['PAT_Gen2'];

                                $PAT_Total_RI[$Date_DMYYYY][$MI] = $Fetch_Result3['Record_Index'];

                                $PAT_Total_Record_Index[$Date_DMYYYY][$Fetch_Result3['Record_Index']] = $MI;

                                $MI++;
                            }
                        }

                        $Total_Count_DB[$Date_DMYYYY] =
                            count($PAT_Total_Record_Index[$Date_DMYYYY]);
                    }
                }

                //
                // EB Meter Reading page (p = 20)
                //
                elseif ($_REQUEST['p'] == 20) {

                    $From_D_Epoch = strtotime($_REQUEST['inputDate']) + (60 * 60 * 5.5);
                    $To_D_Epoch   = strtotime($_REQUEST['inputDate1']) + (60 * 60 * 5.5);

                    $File_Type = 'All_Hours';
                    ?>

                    <script>
                        New_Win_Open2(
                            '<?= $_REQUEST['c1'] ?>',
                            'EB_Meter_Reading.php',
                            '<?= $_REQUEST['inputMonthYear'] ?>',
                            '<?= $FType ?>'
                        );
                    </script>
                    <?php
                }

                //
                // Power & Windspeed curves
                //
                elseif ($_REQUEST['p'] == 19) {
                    ?>
                    <script>
                        Open_PowerWindspeedCurve(
                            '<?= $_REQUEST['c1'] ?>',
                            '<?= $From_D_Epoch ?>',
                            '<?= $To_D_Epoch ?>',
                            '<?= $Pocket_Length ?>'
                        );
                    </script>
                    <?php
                }

                //
                // Monthly wind/power curve (p = 27)
                //
                elseif ($_REQUEST['p'] == 27) {
                    ?>
                    <script>
                        Open_PowerWindspeedCurveMonth(
                            '<?= $_REQUEST['c1'] ?>',
                            '<?= $_REQUEST['inputMonthYear'] ?>',
                            '<?= $Pocket_Length ?>'
                        );
                    </script>
                    <?php
                }

                //
                // Reference curve
                //
                elseif ($_REQUEST['p'] == 47) {
                    ?>
                    <script>
                        Open_PowerWindspeedRefCurve(
                            '<?= $_REQUEST['c1'] ?>',
                            '<?= $From_D_Epoch ?>',
                            '<?= $To_D_Epoch ?>',
                            '<?= $Pocket_Length ?>'
                        );
                    </script>
                    <?php
                }

                //
                // Invoice upload (p = 37)
                //
                elseif ($_REQUEST['p'] == 37) {
                    ?>
                    <script>
                        New_Win_Open3(
                            '<?= $_REQUEST['c1'] ?>',
                            'Invoice_Upload.php',
                            '<?= $_REQUEST['inputMonthYear'] ?>',
                            '<?= $Pocket_Length ?>'
                        );
                    </script>
                    <?php
                }

                //
                // Other reports (default flow)
                //
                else {
                    $From_D_Epoch = strtotime($_REQUEST['inputDate']) + (60 * 60 * 5.5);
                    $To_D_Epoch   = strtotime($_REQUEST['inputDate1']) + (60 * 60 * 5.5);

                    $From_YMD = date("Y-m-d", $From_D_Epoch);
                    $To_YMD   = date("Y-m-d", $To_D_Epoch);

                    $Mysql_Query = "
                        SELECT *
                        FROM $Cook_Variable[7].$Table_Name
                        WHERE IMEI = '$IMEI'
                          AND (Date_S >= '$From_YMD' AND Date_S <= '$To_YMD')
                        GROUP BY IMEI, Date_S, Time_S
                        ORDER BY Record_Index DESC
                    ";
                }
            }

            // Default display when no rows found
            $No_Records = '
                <tr>
                    <td width="50%" class="tab-head-td" ' . $colspan . ' style="padding:10px 0 10px 10px;">
                        <center>Records Not Found</center>
                    </td>
                </tr>';
        }
        ?>
    </td>
</tr>



<?php
##########################################################
#   Excel & HTML Report Includes
##########################################################

# Power Vs Wind Speed Excel
if ($_REQUEST['p'] == 1) {
    include("Power_Vs_Wind_Speed_Excel.php");
}

# Overview Report
if ($_REQUEST['p'] == 2) {
    include("Raw_Data_Overview.php");
}

# Temperature Report
if ($_REQUEST['p'] == 3) {
    include("Raw_Data_Temperature.php");
}

# Production Report
if ($_REQUEST['p'] == 4) {
    include("Raw_Data_Production.php");
}

# Grid Report
if ($_REQUEST['p'] == 5) {
    include("Raw_Data_Grid.php");
}

# EB Daily Report
if ($_REQUEST['p'] == 6) {
    include("Daily_EB_Report_Excel.php");
}

# Stop Hours Report
if ($_REQUEST['p'] == 7) {
    include("Stop_Hours_Report.php");
}

# Alarm Log Individual
if ($_REQUEST['p'] == 8) {
    include("Alarm_Log_Individual_Report_Excel.php");
}

# Alarm Log Group / Lucky variation
if ($_REQUEST['p'] == 9) {

    if (
        $Cook_Variable[6] == 100079 ||
        $Cook_Variable[3] == 100081 ||
        $Cook_Variable[3] == 100082 ||
        $Cook_Variable[3] == 100084 ||
        $Cook_Variable[3] == 100088
    ) {
        include("Alarm_Log_Report_Excel_Lucky.php");
    } else {
        include("Alarm_Log_Group_Report_Excel.php");
    }
}

# Daily Generation Individual (F9)
if ($_REQUEST['p'] == 10) {
    include("Daily_Generation_Report_Individual_Excel_F9.php");
}

# Daily Generation Report
if ($_REQUEST['p'] == 11) {

    if (
        $Cook_Variable[3] == 100079 ||
        $Cook_Variable[3] == 100081 ||
        $Cook_Variable[3] == 100082 ||
        $Cook_Variable[3] == 100084 ||
        $Cook_Variable[3] == 100088
    ) {
        include("Daily_Generation_Report_Excel_lucky.php");
    } else {
        include("Daily_Generation_Report_Excel.php");
    }
}

# DGR Grouping
if ($_REQUEST['p'] == 29) {
    include("Daily_Generation_Report_Grouping_Excel.php");
}

# Monthly Generation Report
if ($_REQUEST['p'] == 12) {
    include("Monthly_Generation_Report.php");
}

# Financial Report
if ($_REQUEST['p'] == 13) {
    include("Financial_Year_Report.php");
}

# DGR Grouping (Madras Silks)
if ($_REQUEST['p'] == 33) {
    include("DGR_Grouping_madrassilks.php");
}

# Cumulative DGR (Madras Silks)
if ($_REQUEST['p'] == 34) {
    include("Cumulative_DGR_madrassilks.php");
}

# DGR ERP Report (MTK)
if ($_REQUEST['p'] == 28) {
    include("DGR_ERP_MTK.php");
}

# Grid Availability
if ($_REQUEST['p'] == 24) {
    include("Grid_Availability_Report.php");
}

# Event Log
if ($_REQUEST['p'] == 14) {
    include("Eventlog_report.php");
}

# GRPM Report
if ($_REQUEST['p'] == 41) {
    include("Raw_Data_Grpm_report.php");
}

# Status Report
if ($_REQUEST['p'] == 42) {
    include("Raw_Data_Status_report.php");
}

# Pitch Report
if ($_REQUEST['p'] == 43) {
    include("Raw_Data_Pitch_report.php");
}

?>
    </table>
</body>
</html>
