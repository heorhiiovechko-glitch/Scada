<?php
// ini_set('max_execution_time', 7200);
include("header_inner.php");
error_reporting(0);

if (empty($_COOKIE[$Cook_Name])) {
    header("Location: index.php");
    exit;
}

// Error Status from ERROR_TYPE
$Mysql_Query = "SELECT Error, Machine_Status FROM error_type";
if (!$queryResult = $db->query($Mysql_Query)) {
    die($db->error);
}

if ($queryResult->num_rows >= 1) {
    while ($Fetch_Result = $queryResult->fetch_array()) {
        $Error_Array[$Fetch_Result['Machine_Status']][] = $Fetch_Result['Error'];
        $Machine_Status_Array[$Fetch_Result['Machine_Status']] = $Fetch_Result['Machine_Status'];
    }
}

$Audio = [];
$td = 0;
$tr = 0;
$CurrentState = "";
$Total_Power = 0;
$CurrentSite = "";
$Total_Export = 0;
$WTG_Run = 0;
$Cur_Date = date('d_m_Y');
?>
<script type="text/javascript" src="js/jq1.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script>
$(document).ready(function () {
    setInterval(function () {
        $('#getdata').load('channel1_sendanall.php #getdata');
    }, 120000);
});
</script>
<script type="text/javascript">
setInterval("scroll_func();", 120000);

function scroll_func() {
    $('#ref').load('channel1_sendanall.php #ref');
}
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript">
function showbox(x) {
    document.getElementById(x).style.display = 'block';
}
function hidebox(x) {
    document.getElementById(x).style.display = 'none';
}
</script>
<style>
.boxed-green {
    border-radius: 50px;
    background: #73AD21;
    padding: 4px 3px 0 3px;
    text-align: center;
    height: 30px;
    min-width: 150px;
    color: white;
}
.boxed-orange {
    border-radius: 50px;
    background: orange;
    padding: 4px 3px 0 3px;
    text-align: center;
    height: 30px;
    min-width: 150px;
    color: white;
}
.boxed-blue {
    border-radius: 50px;
    background: blue;
    padding: 4px 3px 0 3px;
    text-align: center;
    height: 30px;
    min-width: 150px;
    color: white;
}
.boxed-red {
    border-radius: 50px;
    background: red;
    padding: 4px 3px 0 3px;
    text-align: center;
    height: 30px;
    min-width: 150px;
    color: white;
}
.boxed-pink {
    border-radius: 50px;
    background: pink;
    padding: 4px 3px 0 3px;
    text-align: center;
    height: 30px;
    min-width: 150px;
    color: white;
}
.boxed-grey {
    border-radius: 50px;
    background: grey;
    padding: 4px 3px 0 3px;
    text-align: center;
    height: 30px;
    min-width: 150px;
    color: white;
}
.popupbox {
    position: absolute;
    background-color: white;
    color: black;
    border: 1px solid #1a1a1a;
    display: none;
    margin-left: -200px;
    margin-top: -200px;
}
</style>

<center>
<div id="body" class="clear" style="width:99%;">
<form name="channel1_versatile" method="post" action="">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td width="1240px">
                <div id="getdata">
                    <table border="0" cellpadding="5" cellspacing="10" width="100%">
                        <?php
                        $Mysql_Query = "SELECT t1.IMEI, t1.Format_Type, t1.State, t1.Site_Location, t1.Account_ID, t1.Parent_ID, t1.Device_Order, t1.Device_Index,
                                               t1.HTSC_No, t1.Closing_Time, t1.WEG_No, t1.Device_Name, t1.Pocket_Length, t1.db_name, t1.Power_Curve,
                                               t1.Connect_Feeder, t1.Region, s.totalCount AS count
                                        FROM device_register AS t1
                                        LEFT JOIN (
                                            SELECT Device_Index, State, COUNT(State) totalCount
                                            FROM device_register
                                            WHERE Parent_ID='100253'
                                            GROUP BY Region
                                        ) s ON s.State = t1.State
                                        WHERE t1.Parent_ID='100253'
                                        GROUP BY IMEI
                                        ORDER BY Region DESC, db_name ASC, device_order ASC";

                        if (!$queryResult = $db->query($Mysql_Query)) {
                            die($db->error);
                        }

                        if ($queryResult->num_rows >= 1) {
                            while ($Fetch_Result = $queryResult->fetch_array()) {
                                $IMEI_Org = $Fetch_Result['IMEI'];
                                $DeviceName[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
                                $Closing_Time[$Fetch_Result['IMEI']] = $Fetch_Result['Closing_Time'];
                                $HTSCno[$Fetch_Result['IMEI']] = $Fetch_Result['HTSC_No'];
                                $IMEI_Encode = base64_encode($Fetch_Result['IMEI']);
                                $WEGno[$Fetch_Result['IMEI']] = $Fetch_Result['WEG_No'];
                                $WindFarm_No[$Fetch_Result['IMEI']] = $Fetch_Result['WindFarm_No'];
                                $State[$Fetch_Result['IMEI']] = $Fetch_Result['State'];
                                $Region[$Fetch_Result['IMEI']] = $Fetch_Result['Region'];
                                $Site_Location[$Fetch_Result['IMEI']] = $Fetch_Result['Site_Location'];
                                $Device_Name[$Fetch_Result['IMEI']] = substr($Fetch_Result['Device_Name'], 0, 18);
                                $Turbine[$Fetch_Result['IMEI']] = $Fetch_Result['IMEI'];
                                $Format_Type = $Fetch_Result['Format_Type'];
                                $Pocket_Length = $Fetch_Result['Pocket_Length'];
                                $Connect_Feeder[$Fetch_Result['Site_Location']] = $Fetch_Result['Connect_Feeder'];
                                $Feeder[$Fetch_Result['IMEI']] = $Fetch_Result['Connect_Feeder'];
                                $Capacity = $Fetch_Result['Capacity'];

                                $IMEI = base64_decode($IMEI_Encode);
                                $DB_Mysql_Query = "SELECT IMEI, db_name, Account_ID FROM device_register WHERE IMEI = '" . $IMEI . "'";
                                if (!$DBqueryResult = $db->query($DB_Mysql_Query)) {
                                    die($db->error);
                                }
                                if ($DBqueryResult->num_rows >= 1) {
                                    $DB_Fetch_Result = $DBqueryResult->fetch_array();
                                    $DB_Name[$DB_Fetch_Result['IMEI']] = $DB_Fetch_Result['db_name'];
                                    $ACC[$DB_Fetch_Result['IMEI']] = $DB_Fetch_Result['Account_ID'];
                                }

                                // QUERY based on Format_Type
                                if ($Format_Type == 2 || $Format_Type == 4) {
                                    $Mysql_Query1 = "SELECT Date_S, Time_S, Status, ((Gen1_Max - Gen1_Min) + (Gen2_Max - Gen2_Min)) AS G1,
                                                           ((Gen1_Hours_Max - Gen1_Hours_Min) + (Gen2_Hours_Max - Gen2_Hours_Min)) AS G2
                                                     FROM device_register WHERE IMEI = '" . $IMEI . "' LIMIT 1";
                                }
                                elseif ($Format_Type == 6 || $Format_Type == 1) {
                                    $Mysql_Query1 = "SELECT Date_S, Time_S, Status, (Gen2_Max - Gen2_Min) AS G1,
                                                           (Gen1_Hours_Max - Gen1_Hours_Min) AS G2
                                                     FROM device_register WHERE IMEI = '" . $IMEI . "' LIMIT 1";
                                }
                                elseif ($Format_Type == 10) {
                                    $Mysql_Query1 = "SELECT Date_S, Time_S, Status, (Gen1_Max - Gen1_Min) AS G1,
                                                           (Gen1_Hours_Max - Gen1_Hours_Min) AS G2
                                                     FROM device_register WHERE IMEI = '" . $IMEI . "' LIMIT 1";
                                }
                                elseif ($Format_Type == 3) {
                                    $Mysql_Query1 = "SELECT Date_S, Time_S, Status, (Gen1_Max - Gen1_Min) AS G1,
                                                           ((Gen1_Hours_Max - Gen1_Hours_Min) + (Gen2_Hours_Max - Gen2_Hours_Min)) AS G2
                                                     FROM device_register WHERE IMEI = '" . $IMEI . "' LIMIT 1";
                                }
                                elseif ($Format_Type == 7 || $Format_Type == 8) {
                                    $Mysql_Query1 = "SELECT Date_S, Time_S, Status, Gen1_Max AS G1, Gen1_Hours_Max AS G2
                                                     FROM device_register WHERE IMEI = '" . $IMEI . "' LIMIT 1";
                                }
                                elseif ($Format_Type == 9 || $Format_Type == 11) {
                                    $Mysql_Query1 = "SELECT Date_S, Time_S, Status, Gen1_Max AS G1,
                                                           (Gen1_Hours_Max - Gen2_Max) AS G2
                                                     FROM va_master.device_register WHERE IMEI = '" . $IMEI . "' LIMIT 1";
                                }
                                else {
                                    $Mysql_Query1 = "SELECT Date_S, Time_S, Status
                                                     FROM {$DB_Name[$IMEI]}.{$Table_Name}
                                                     WHERE IMEI = '" . $IMEI . "' AND Status != ''
                                                     ORDER BY Record_Index DESC LIMIT 1";
                                }

                                if (!$queryResult1 = $db->query($Mysql_Query1)) {
                                    die($db->error);
                                }

                                if ($queryResult1->num_rows >= 0) {
                                    $Fetch_Result1 = $queryResult1->fetch_array();
                                    $Status1 = trim($Fetch_Result1['Status']);
                                    $Status = strtolower($Status1);
                                    $Date_S = $Fetch_Result1['Date_S'];
                                    $Time_S = $Fetch_Result1['Time_S'];
                                    $G1 = $Fetch_Result1['G1'];
                                    $G2 = $Fetch_Result1['G2'];
                                    $Device_Epoch_Time = GetTimestamp($Date_S, $Time_S);

                                    if (!empty($Device_Epoch_Time)) {
                                        $Diff_Error_Status = $Device_Epoch_Time;
                                    }

                                    $Req_Time = time() + (60 * 60 * 5.5);
                                    $ReqTime_Diff = $Req_Time - $Device_Epoch_Time;

                                    if ($ReqTime_Diff >= 900 && (in_array($Status, $Error_Array['Green']) && !in_array($Status, $Error_Array['Blue']))) {
                                        $Tower_Img = '<img src="./images/Grey_jpg.jpg" width="69px" height="98px">';
                                        $Div_Img = "<div class='boxed-grey'>{$Device_Name[$IMEI]}</div>";
                                    }
                                    else {
                                        if (in_array($Status, $Error_Array['Green'])) {
                                            $WTG_Run++;
                                            $Div_Img = "<div class='boxed-green'>{$Device_Name[$IMEI]}</div>";
                                            $Tower_Img = '<img src="./images/6.gif" width="69px" height="98px">';
                                        }
                                        elseif (in_array($Status, $Error_Array['Orange'])) {
                                            $Div_Img = "<div class='boxed-orange'>{$Device_Name[$IMEI]}</div>";
                                            $Tower_Img = '<img src="./images/7.gif" width="69px" height="98px">';
                                        }
                                        elseif (in_array($Status, $Error_Array['Blue'])) {
                                            $Div_Img = "<div class='boxed-blue'>{$Device_Name[$IMEI]}</div>";
                                            $Tower_Img = '<img src="./images/Blue_jpg.jpg" width="69px" height="98px">';
                                            $Audio[] = $WEGno[$IMEI];
                                        }
                                        elseif (in_array($Status, $Error_Array['Pink'])) {
                                            $Div_Img = "<div class='boxed-pink'>{$Device_Name[$IMEI]}</div>";
                                            $Tower_Img = '<img src="./images/18.jpg" width="69px" height="98px">';
                                            $Audio[] = $WEGno[$IMEI];
                                        }
                                        else {
                                            $Div_Img = "<div class='boxed-red'>{$Device_Name[$IMEI]}</div>";
                                            $Tower_Img = '<img src="./images/Red_jpg.jpg" width="69px" height="98px">';
                                            $Audio[] = $WEGno[$IMEI];
                                        }
                                    }
                                }

                                $Date_G = strtotime($Date_S);
                                $Time_G = strtotime($Time_S);
                                $Date = date('d/m/Y', $Date_G);
                                $Time = date('H:i:s', $Time_G);
                                $PreviousState = $CurrentState;

                                if ($CurrentState == "" || $CurrentState != $Region[$IMEI]) {
    $td = 0;
    echo "<tr>
            <td colspan='15' style='
                background-color: #f5f5f5;
                border: 2px solid #ccc;
                padding: 8px;
                font-size: 15px;
                font-weight: bold;
                text-align: left;
                border-radius: 20px;
            '>
                " . htmlspecialchars(substr($Region[$IMEI], 0, 15)) . "
            </td>
          </tr>
          <tr>";
    $CurrentState = $Region[$IMEI];
}

                                ?>
                                <td>
                                    <table border="0" cellpadding="10" cellspacing="5">
                                        <tr>
                                            <td align="left">
                                                <a href=" " onclick="return false" style="cursor:pointer; color:#333; text-decoration:none;"
                                                   title="<?= $DeviceName[$IMEI] ?> &nbsp;<?= $Date_S ?>,<?= $Time_S ?>,&nbsp;<?= $Site_Location[$IMEI] ?>,&nbsp;<?= $G1 ?>, &nbsp;<?= $G2 ?>">
                                                   <?= $Div_Img ?>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <?php
                                $td++;
                                if ($td == 8) {
                                    echo "</tr><tr>";
                                    $td = 0;
                                }
                            }
                        }
                        else {
                            echo "<br /><br /><br /><h2>Machine not yet Installed...</h2><br /><br /><br /><br />";
                        }
                        ?>
                    </table>
                </div>
            </td>
        </tr>
    </table>

<?php if (($_COOKIE['timer'] <= 1700 && $_COOKIE['timer'] >= 1550)) { ?>
<script>
$(function () {
    var audio = document.getElementById('ctrlaudio');
    var songNames = document.getElementById('hdnSongNames').value;
    var lstsongNames = songNames.split(',');
    var curPlaying = 0;

    audio.addEventListener('ended', function () {
        var urls = audio.getElementsByTagName('source');
        if (urls[0].src.indexOf(lstsongNames[lstsongNames.length - 1]) == -1) {
            urls[0].src = urls[0].src.replace(lstsongNames[curPlaying], lstsongNames[++curPlaying]);
            audio.load();
            audio.play();
        }
    });
});
</script>
<?php
function arrayPrefix(&$value, $key) {
    $value = "Music/$value.wav";
}
array_walk($Audio, "arrayPrefix");
$Audio_Str = implode(",", $Audio);
?>
<audio id="ctrlaudio" autoplay runat="server">
    <source src="<?= $Audio[0] ?>"></source>
    Your browser does not support the audio tag.
</audio>
<input type="hidden" name="hdnSongNames" id="hdnSongNames" value="<?= $Audio_Str ?>">
<?php } ?>
</form>
</div>
</center>
