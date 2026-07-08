<?php
include("header_inner.php");
error_reporting(0);

// Redirect if not authenticated
if (empty($_COOKIE[$Cook_Name])) {
    header("Location: index.php");
    exit;
}

// Utility function to get background color
function getRowStyle($index)
{
    return ($index % 2 === 0) ? 'background-color: #D4F7EB;' : 'background-color: #EEFAF6;';
}

// Get table name based on format type
function getTableName($format)
{
    $tableMapping = [
        1 => 'device_data',
        2 => 'device_data_f2',
        3 => 'device_data_f3',
        4 => 'device_data_f4',
        6 => 'device_data_f6',
        7 => 'device_data_f7',
        8 => 'device_data_f8',
        10 => 'device_data_f10',
    ];

    return $tableMapping[$format] ?? null;
}

// Database fetch: Get device details
function getDeviceDetails($db)
{
    $query = "SELECT * FROM va_master.device_register WHERE Device_Name='Aspire'";
    $result = $db->query($query);

    if (!$result) {
        die("Database error: " . $db->error);
    }

    return $result->fetch_assoc();
}

// Database fetch: Get live data
function getLiveData($db, $tableName, $imei)
{
    $query = "SELECT * FROM va_aspire.$tableName WHERE Date_S = CURDATE() AND IMEI = ? ORDER BY Record_Index DESC LIMIT 10";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $imei);
    $stmt->execute();

    $result = $stmt->get_result();
    if (!$result) {
        die("Database error: " . $db->error);
    }

    return $result;
}

$deviceDetails = getDeviceDetails($db);
$tableName = getTableName($deviceDetails['Format_Type']);
$imei = $deviceDetails['IMEI'];
$liveData = getLiveData($db, $tableName, $imei);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VersatileScada</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Periodically update live data
            setInterval(function () {
                $('#getdata').load('fetch_live_data.php');
            }, 120000);

            // Periodically update energy data
            setInterval(function () {
                $('#energy').load('fetch_energy_data.php');
            }, 3600000);
        });
    </script>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <nav>
            <a href="channel9_new.php" target="_blank" class="sidebar-link">Ashok Leyland</a>
            <a href="channel9_new_wheels.php" target="_blank" class="sidebar-link">Wheels India</a>
            <a href="channel9_new_ekit1.php" target="_blank" class="sidebar-link">Energy KIT 1</a>
            <a href="channel9_new_ekit2.php" target="_blank" class="sidebar-link">Energy KIT 2</a>
        </nav>
    </aside>

    <main class="content">
        <h2>Energy from VersatileScada</h2>
        <section id="getdata">
            <h3>Live Data</h3>
            <table>
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Temperature 1</th>
                    <th>Temperature 2</th>
                    <th>Flow Rate</th>
                    <th>Energy</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rowIndex = 0;
                while ($row = $liveData->fetch_assoc()) {
                    echo "<tr style='" . getRowStyle($rowIndex++) . "'>";
                    echo "<td>{$row['Date_S']}</td>";
                    echo "<td>{$row['Time_S']}</td>";
                    echo "<td>{$row['Temp1']}</td>";
                    echo "<td>{$row['Temp2']}</td>";
                    echo "<td>{$row['Flow_Rate']}</td>";
                    echo "<td>{$row['Energy']}</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </section>

        <section id="energy">
            <h3>Energy Data</h3>
            <!-- AJAX content for energy data -->
        </section>
    </main>
</div>
</body>
</html>
