<?php
    error_reporting(0);
    include("Includes.php");

    $Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);

    if (isset($_REQUEST['c1'])) {
        $MonthYear = $_REQUEST['Year'];
        $MonthYearArr = explode("-", $MonthYear);

        $Month = $MonthYearArr[0];
        $Year  = $MonthYearArr[1];

        $Total_Days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);

        $IMEI = base64_decode($_REQUEST['c1']);
        $PCWP_Chart_Arr1 = array();

        // Fetch device info
        $Mysql_Query = "SELECT IMEI, Power_Curve, Format_Type, Device_Name 
                        FROM device_register WHERE IMEI = '$IMEI'";
        $Mysql_Query_Result = $db->query($Mysql_Query);
        if ($Mysql_Query_Result->num_rows >= 1) {
            while ($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
                $Power_Curve_Array[$Fetch_Result['IMEI']] = $Fetch_Result['Power_Curve'];
                $Format_Type = $Fetch_Result['Format_Type'];
                $Device_Name = $Fetch_Result['Device_Name'];
            }
        }

        // Assign power curve  
        if (isset($IMEI)) {
            if ($Power_Curve_Array[$IMEI] == 600) { $PCWP_Chart_Arr = $PCWP_Chart_Arr_600; }
            elseif ($Power_Curve_Array[$IMEI] == 500) { $PCWP_Chart_Arr = $PCWP_Chart_Arr_500; }
            elseif ($Power_Curve_Array[$IMEI] == 225) { $PCWP_Chart_Arr = $PCWP_Chart_Arr_225; }
            elseif ($Power_Curve_Array[$IMEI] == 250) { $PCWP_Chart_Arr = $PCWP_Chart_Arr_250; }
            elseif ($Power_Curve_Array[$IMEI] == 750) { $PCWP_Chart_Arr = $PCWP_Chart_Arr_750; }
        }

        // Fetch daily generation
        if ($Format_Type == 1 || $Format_Type == 6)
            $Mysql_Query = "SELECT Day(Date_S) AS Day, Gen1_Max AS PAT_GEN2_Max, Gen1_Min AS PAT_GEN2_Min,
                            Power, WindSpeed, Date_S
                            FROM daily_data
                            WHERE IMEI='$IMEI' AND Month(Date_S)=$Month AND Year(Date_S)=$Year
                            GROUP BY Day(Date_S)";

        elseif ($Format_Type == 2)
            $Mysql_Query = "SELECT Day(Date_S) AS Day, Gen1_Max AS G1_Kwh_Max, Gen1_Min AS G1_Kwh_Min,
                            Gen2_Max AS G2_Kwh_Max, Gen2_Min AS G2_Kwh_Min,
                            Power, WindSpeed, Date_S
                            FROM daily_data
                            WHERE IMEI='$IMEI' AND Month(Date_S)=$Month AND Year(Date_S)=$Year
                            GROUP BY Day(Date_S)";

        elseif ($Format_Type == 7)
            $Mysql_Query = "SELECT Day(Date_S) AS Day, Gen1_Max AS PAT_GEN1_Max, Gen1_Min AS PAT_GEN1_Min,
                            Power, WindSpeed, Date_S
                            FROM daily_data
                            WHERE IMEI='$IMEI' AND Month(Date_S)=$Month AND Year(Date_S)=$Year
                            GROUP BY Day(Date_S)";

        elseif ($Format_Type == 3 || $Format_Type == 10)
            $Mysql_Query = "SELECT Day(Date_S) AS Day, Gen1_Max AS GEN_Max, Gen1_Min AS GEN_Min,
                            Power, WindSpeed, Date_S
                            FROM daily_data
                            WHERE IMEI='$IMEI' AND Month(Date_S)=$Month AND Year(Date_S)=$Year
                            GROUP BY Day(Date_S)";

        $Mysql_Query_Result = $db->query($Mysql_Query);

        if ($Mysql_Query_Result->num_rows >= 1) {
            while ($Fetch_Result = $Mysql_Query_Result->fetch_array()) {
                $Each_Days_WindSpeedData_Arr_Avg[$Fetch_Result['Day']] = $Fetch_Result['WindSpeed'];
                $Each_Days_PowerData_Arr_Avg[$Fetch_Result['Day']] = $Fetch_Result['Power'];

                if ($Format_Type == 1 || $Format_Type == 6) {
                    $Each_Days_PAT_GEN2_Min_Arr[$Fetch_Result['Day']] = $Fetch_Result['PAT_GEN2_Min'];
                    $Each_Days_PAT_GEN2_Max_Arr[$Fetch_Result['Day']] = $Fetch_Result['PAT_GEN2_Max'];
                } elseif ($Format_Type == 2) {
                    $Each_Days_G1_Kwh_Min_Arr[$Fetch_Result['Day']] = $Fetch_Result['G1_Kwh_Min'];
                    $Each_Days_G1_Kwh_Max_Arr[$Fetch_Result['Day']] = $Fetch_Result['G1_Kwh_Max'];
                    $Each_Days_G2_Kwh_Min_Arr[$Fetch_Result['Day']] = $Fetch_Result['G2_Kwh_Min'];
                    $Each_Days_G2_Kwh_Max_Arr[$Fetch_Result['Day']] = $Fetch_Result['G2_Kwh_Max'];
                } elseif ($Format_Type == 7) {
                    $Each_Days_GEN_Min_Arr[$Fetch_Result['Day']] = $Fetch_Result['PAT_GEN1_Min'];
                    $Each_Days_GEN_Max_Arr[$Fetch_Result['Day']] = $Fetch_Result['PAT_GEN1_Max'];
                } elseif ($Format_Type == 3 || $Format_Type == 10) {
                    $Each_Days_GEN_Min_Arr[$Fetch_Result['Day']] = $Fetch_Result['GEN_Min'];
                    $Each_Days_GEN_Max_Arr[$Fetch_Result['Day']] = $Fetch_Result['GEN_Max'];
                }
            }
        }

        // Generation build
        if ($Format_Type == 1 || $Format_Type == 6) {
            foreach ($Each_Days_PAT_GEN2_Max_Arr as $d => $v) {
                $Each_Days_Generation_Arr[$d] = $v - $Each_Days_PAT_GEN2_Min_Arr[$d];
                if ($Each_Days_Generation_Arr[$d] > 12000 || $Each_Days_Generation_Arr[$d] < 0)
                    $Each_Days_Generation_Arr[$d] = 0;
            }
        } elseif ($Format_Type == 2) {
            foreach ($Each_Days_G1_Kwh_Max_Arr as $d => $v) {
                $Each_Days_G1_Kwh_Arr[$d] = $v - $Each_Days_G1_Kwh_Min_Arr[$d];
            }
            foreach ($Each_Days_G2_Kwh_Max_Arr as $d => $v) {
                $Each_Days_G2_Kwh_Arr[$d] = $v - $Each_Days_G2_Kwh_Min_Arr[$d];
            }
            for ($i = 0; $i <= 31; $i++) {
                $Each_Days_Generation_Arr[$i] = round($Each_Days_G1_Kwh_Arr[$i] + $Each_Days_G2_Kwh_Arr[$i]);
                if ($Each_Days_Generation_Arr[$i] > 10000 || $Each_Days_Generation_Arr[$i] < 0)
                    $Each_Days_Generation_Arr[$i] = 0;
            }
        } else {
            foreach ($Each_Days_GEN_Max_Arr as $d => $v) {
                $Each_Days_Generation_Arr[$d] = $v - $Each_Days_GEN_Min_Arr[$d];
                if ($Each_Days_Generation_Arr[$d] > 15000 || $Each_Days_Generation_Arr[$d] < 0)
                    $Each_Days_Generation_Arr[$d] = 0;
            }
        }

        $Each_Days_Generation_Arr_Sum = array_sum($Each_Days_Generation_Arr);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
	
	<script>
// Disable right click
document.addEventListener('contextmenu', event => event.preventDefault());

// Disable common inspect keys
document.onkeydown = function(e) {
    if (e.keyCode == 123) { // F12
        return false;
    }
    if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 67 || e.keyCode == 74)) {
        return false;
    }
    if (e.ctrlKey && e.keyCode == 85) { // Ctrl+U
        return false;
    }
};
</script>

    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #eef2f6;
            font-family: 'Roboto', sans-serif;
        }

        .chart-container {
            background: #ffffff;
            width: 95%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .chart-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #222;
            letter-spacing: 0.5px;
        }

        #chart_kwh {
            width: 100%;
            height: 380px;
        }
    </style>

    <script>
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Day', 'Generation kWh'],
                <?php
                    $count = count($Each_Days_Generation_Arr);
                    $i = 0;
                    foreach ($Each_Days_Generation_Arr as $day => $val) {
                        echo "[{$day}, {$val}]";
                        echo ($i < $count - 1) ? "," : "";
                        $i++;
                    }
                ?>
            ]);

            var options = {
                width: '100%',
                height: 350,
                backgroundColor: '#ffffff',

                colors: ['#ffc107'],

                chartArea: {
                    left: 60,
                    right: 20,
                    top: 20,
                    bottom: 60,
                    backgroundColor: '#efefef',
                    width: '85%',
                    height: '70%'
                },

                legend: 'none',

                hAxis: {
                    title: 'Date',
                    titleTextStyle: { color: '#d62828', bold: true },
                    textStyle: { fontSize: 12 },
                    gridlines: { color: '#ccc' },
                    viewWindowMode: "explicit",
                    viewWindow: { min: 1, max: 31 }
                },

                vAxis: {
                    title: 'Generation (kWh)',
                    titleTextStyle: { color: '#d62828', bold: true },
                    gridlines: { color: '#ddd' },
                    minValue: 0
                }
            };

			var chartContainer = document.getElementById('chart_kwh');
            
			if (data.getNumberOfRows() === 0) {
				// Option 1: Hide the chart container element
				chartContainer.style.display = 'none';
				console.log("Chart hidden as data is empty.");
			} else {
				// Option 2: Draw the chart only if data exists
				chartContainer.style.display = 'block'; // Ensure it's visible if it was hidden before
				//var options = {
				//title: 'My Chart',
				// Add other chart options here
			};

			var chart = new google.visualization.ColumnChart(chartContainer);
			chart.draw(data, options);
			console.log("Chart drawn with data.");
            //var chart = new google.visualization.ColumnChart(document.getElementById('chart_kwh'));
			//chart.draw(data, options);
        }
    </script>
</head>

<body>

<div class="chart-container">

    <div class="chart-title">
        Generation Graph for <?= date("F", mktime(0, 0, 0, $Month, 10)) ?>
        — Export: <?= $Each_Days_Generation_Arr_Sum ?> kWh
    </div>

    <div id="chart_kwh"></div>

</div>

</body>
</html>
