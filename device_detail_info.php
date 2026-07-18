<?php
/**
 * Unified device detail page — opened from Channel1 tower links.
 */
error_reporting(0);
include('header_inner.php');
require_once __DIR__ . '/Lib/db_management_service.php';

if (empty($_COOKIE[$Cook_Name])) {
    header('Location: index.php');
    exit;
}

if (!function_exists('detail_info_h')) {
    function detail_info_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('detail_info_display_value')) {
    function detail_info_display_value($value, $fallback) {
        if ($value === null) {
            return $fallback;
        }
        $text = trim((string)$value);
        return $text !== '' ? $text : $fallback;
    }
}

if (!function_exists('detail_info_fetch_telemetry')) {
    function detail_info_fetch_telemetry($db, $device) {
        $imei = isset($device['IMEI']) ? trim((string)$device['IMEI']) : '';
        $dbName = dbmgmt_sanitize_db_name(isset($device['db_name']) ? $device['db_name'] : '');
        $formatType = (int)(isset($device['Format_Type']) ? $device['Format_Type'] : 0);
        $table = dbmgmt_raw_data_table_for_format($formatType);
        if ($imei === '' || $dbName === null || $table === null) {
            return null;
        }

        $escapedDb = $db->real_escape_string($dbName);
        $escapedTable = $db->real_escape_string($table);
        $escapedImei = $db->real_escape_string($imei);

        if ($formatType === 11) {
            $sql = "SELECT Date_S, Time_S, tag_windspd AS WindSpeed, tag_power AS Power, tag_status AS Status
                FROM `$escapedDb`.`$escapedTable`
                WHERE IMEI = '$escapedImei'
                ORDER BY Record_Index DESC LIMIT 1";
        } else {
            $sql = "SELECT Date_S, Time_S, Windspeed AS WindSpeed, Power, Status
                FROM `$escapedDb`.`$escapedTable`
                WHERE IMEI = '$escapedImei'
                ORDER BY Record_Index DESC LIMIT 1";
        }

        if (!($result = $db->query($sql)) || !$result->num_rows) {
            return null;
        }

        $row = $result->fetch_assoc();
        $windSpeed = isset($row['WindSpeed']) ? trim((string)$row['WindSpeed']) : '';
        $windSpeed = str_replace(array('m/s', ','), '', $windSpeed);
        $power = isset($row['Power']) ? trim((string)$row['Power']) : '';
        $status = isset($row['Status']) ? trim((string)$row['Status']) : '';

        return array(
            'date' => isset($row['Date_S']) ? $row['Date_S'] : '',
            'time' => isset($row['Time_S']) ? $row['Time_S'] : '',
            'wind_speed' => $windSpeed,
            'power' => $power,
            'status' => $status,
        );
    }
}

if (!function_exists('detail_info_generation_values')) {
    function detail_info_generation_values($db, $device, $telemetryTable, $formatType) {
        $imei = isset($device['IMEI']) ? trim((string)$device['IMEI']) : '';
        $dbName = dbmgmt_sanitize_db_name(isset($device['db_name']) ? $device['db_name'] : '');
        $genDaily = 0;
        $prevDayGen = 0;

        if ($imei !== '' && $dbName !== null && $telemetryTable !== null) {
            $escapedImei = $db->real_escape_string($imei);
            $escapedDb = $db->real_escape_string($dbName);
            $escapedTable = $db->real_escape_string($telemetryTable);

            if ((int)$formatType === 2 || (int)$formatType === 4) {
                $dailySql = "SELECT PAT_Gen1 FROM `$escapedDb`.`$escapedTable`
                    WHERE IMEI = '$escapedImei' AND Date_S = CURDATE()
                    ORDER BY Record_Index DESC LIMIT 1";
                if ($dailyResult = $db->query($dailySql)) {
                    if ($dailyRow = $dailyResult->fetch_assoc()) {
                        $genDaily = round((float)$dailyRow['PAT_Gen1']);
                    }
                }
            }

            try {
                $prevSql = "SELECT (Gen1_Max - Gen1_Min) AS prev_gen
                    FROM `$escapedDb`.daily_data
                    WHERE IMEI = '$escapedImei' AND Date_S = (CURDATE() - INTERVAL 1 DAY)
                    LIMIT 1";
                if ($prevResult = $db->query($prevSql)) {
                    if ($prevRow = $prevResult->fetch_assoc()) {
                        $prevDayGen = round((float)$prevRow['prev_gen']);
                    }
                }
            } catch (mysqli_sql_exception $e) {
                $prevDayGen = 0;
            }
        }

        return array('gen_daily' => $genDaily, 'prev_day_gen' => $prevDayGen);
    }
}

$imeiEncoded = isset($_REQUEST['c1']) ? trim((string)$_REQUEST['c1']) : '';
if ($imeiEncoded === '') {
    header('Location: dashboard.php');
    exit;
}

$imei = base64_decode($imeiEncoded);
$device = dbmgmt_get_device_by_imei($db, $imei);
if (!$device) {
    header('Location: dashboard.php');
    exit;
}

$databaseName = dbmgmt_sanitize_db_name(
    isset($_REQUEST['Db_Name']) && trim((string)$_REQUEST['Db_Name']) !== ''
        ? $_REQUEST['Db_Name']
        : (isset($device['db_name']) ? $device['db_name'] : '')
);
if ($databaseName === null) {
    $databaseName = isset($device['db_name']) ? trim((string)$device['db_name']) : '';
}

$formatType = (int)(isset($_REQUEST['FType']) && (int)$_REQUEST['FType'] > 0
    ? $_REQUEST['FType']
    : (isset($device['Format_Type']) ? $device['Format_Type'] : 0));
$pocketLength = isset($_REQUEST['l']) && trim((string)$_REQUEST['l']) !== ''
    ? trim((string)$_REQUEST['l'])
    : detail_info_display_value(isset($device['Pocket_Length']) ? $device['Pocket_Length'] : '', '-');

$customerName = '';
$accountSql = "SELECT Firstname, Lastname FROM va_master.user_master WHERE Account_ID = ? LIMIT 1";
if ($accountStmt = $db->prepare($accountSql)) {
    $accountId = isset($device['Account_ID']) ? (string)$device['Account_ID'] : '';
    $accountStmt->bind_param('s', $accountId);
    if ($accountStmt->execute()) {
        $accountResult = $accountStmt->get_result();
        if ($accountResult && $accountRow = $accountResult->fetch_assoc()) {
            $customerName = trim($accountRow['Firstname'] . ' ' . $accountRow['Lastname']);
        }
    }
}

$telemetry = detail_info_fetch_telemetry($db, $device);
$telemetryTable = dbmgmt_raw_data_table_for_format($formatType);
$generation = detail_info_generation_values($db, $device, $telemetryTable, $formatType);
$errorMap = dbmgmt_load_error_type_map($db);
$registerStatus = dbmgmt_register_status($device);

$dateF = $telemetry ? $telemetry['date'] : (isset($device['date_s']) ? $device['date_s'] : '');
$timeF = $telemetry ? $telemetry['time'] : (isset($device['time_s']) ? $device['time_s'] : '');
if (($dateF === '' || $timeF === '') && !empty($device['date_f'])) {
    $dateF = $device['date_f'];
    $timeF = isset($device['time_f']) ? $device['time_f'] : $timeF;
}

$windSpeedRaw = $telemetry && $telemetry['wind_speed'] !== ''
    ? $telemetry['wind_speed']
    : (isset($device['windspeed']) ? $device['windspeed'] : '');
$windSpeedRaw = trim(str_replace(array('m/s', ','), '', (string)$windSpeedRaw));
$windSpeed = ($windSpeedRaw !== '' && is_numeric($windSpeedRaw))
    ? number_format((float)$windSpeedRaw, 2)
    : '0.00';

$powerRaw = $telemetry && $telemetry['power'] !== ''
    ? $telemetry['power']
    : (isset($device['power']) ? $device['power'] : '');
$powerVal = dbmgmt_parse_power_value($powerRaw);
$power = ($powerVal !== null ? number_format($powerVal, 2) : '0.00');

$statusText = $telemetry && $telemetry['status'] !== ''
    ? $telemetry['status']
    : detail_info_display_value(isset($device['status']) ? $device['status'] : '', 'No data');

$lastSeenEpoch = null;
if ($dateF !== '' && $timeF !== '') {
    $lastSeenEpoch = GetTimestamp($dateF, $timeF);
    if (!$lastSeenEpoch) {
        $lastSeenEpoch = null;
    }
}

$legend = dbmgmt_classify_device_legend(
    $registerStatus,
    $lastSeenEpoch,
    $statusText,
    $powerRaw,
    $errorMap
);

$deviceName = detail_info_display_value(isset($device['Device_Name']) ? $device['Device_Name'] : '', 'Device');
$canOpenRawData = ($User_Type_ID == 1 || $User_Type_ID == 2) && !empty($device['Device_Index']);
$rawDataUrl = $canOpenRawData
    ? ('device_raw_data.php?device_index=' . (int)$device['Device_Index'])
    : '';

$lastUpdateDisplay = '-';
if ($dateF !== '' && $timeF !== '') {
    $dateTs = strtotime($dateF);
    $timeTs = strtotime($timeF);
    $lastUpdateDisplay = ($dateTs ? date('d/m/Y', $dateTs) : $dateF) . ' • ' . ($timeTs ? date('H:i:s', $timeTs) : $timeF);
}

$registerLabel = ((int)$registerStatus === 1) ? 'Active' : 'Stopped';
$imeiEncodedSafe = detail_info_h($imeiEncoded);
$databaseNameSafe = detail_info_h($databaseName);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Device Detail Info - SCADA</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{--surface:#fff;--muted:#d9d9d9;--shadow:0 6px 20px rgba(32,33,36,0.08)}
*{box-sizing:border-box}
body{font-family:'Roboto',sans-serif;background:var(--muted);margin:0;color:#111}
.page-wrap{max-width:1200px;margin:0 auto;padding:16px}
.hero,.panel{background:var(--surface);border-radius:12px;box-shadow:var(--shadow)}
.hero{padding:18px 20px;display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;margin-bottom:16px}
.hero h1{margin:0;font-size:22px;color:#0d47a1}
.hero p{margin:6px 0 0;color:#555;font-size:14px}
.hero-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-end}
.btn{display:inline-flex;align-items:center;gap:8px;min-height:36px;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1px solid transparent;cursor:pointer;transition:transform .15s ease,box-shadow .15s ease}
.btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.08);text-decoration:none}
.btn:disabled{opacity:.55;cursor:not-allowed;transform:none;box-shadow:none}
.btn-secondary{background:#f4fbf8;border-color:#b7e0d4;color:#0b755c}
.btn-start{background:#e8f5e9;border-color:#a5d6a7;color:#1b5e20}
.btn-pause{background:#fff3e0;border-color:#ffcc80;color:#e65100}
.btn-quick{background:#e3f2fd;border-color:#90caf9;color:#0d47a1}
.btn-reset{background:#ede7f6;border-color:#b39ddb;color:#4527a0}
.summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px}
.summary-card{background:var(--surface);border-radius:12px;padding:16px;box-shadow:var(--shadow)}
.summary-card .label{font-size:12px;color:#666;text-transform:uppercase;letter-spacing:.04em}
.summary-card .value{margin-top:8px;font-size:24px;font-weight:700;line-height:1.2}
.panel{margin-bottom:16px;overflow:hidden}
.panel-head{padding:14px 18px;border-bottom:1px solid #e8edf3;font-weight:600;color:#333}
.detail-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:0}
.detail-item{padding:14px 18px;border-bottom:1px solid #eef2f7;border-right:1px solid #eef2f7}
.detail-item:nth-child(odd){background:#fafcff}
.detail-item .label{display:block;font-size:12px;color:#666;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px}
.detail-item .value{font-size:15px;font-weight:500;color:#111;word-break:break-word}
.badge-wtg-green{background:#43a047;color:#fff}
.badge-wtg-orange{background:#fb8c00;color:#fff}
.badge-wtg-red{background:#e53935;color:#fff}
.badge-wtg-blue{background:#1e88e5;color:#fff}
.badge-wtg-pink{background:#d81b60;color:#fff}
.badge-wtg-grey{background:#78909c;color:#fff}
.badge-wtg-stopped{background:#ffebee;color:#c62828;border:1px solid #ef9a9a}
[class*="badge-wtg-"]{display:inline-block;padding:6px 12px;border-radius:6px;font-size:13px;font-weight:600}
.alert{padding:10px 12px;border-radius:8px;font-size:13px;margin-bottom:16px;display:none}
.alert.show{display:block}
.alert-success{background:#e8f5e9;color:#1b5e20;border:1px solid #a5d6a7}
.alert-error{background:#ffebee;color:#b71c1c;border:1px solid #ef9a9a}
.note{margin-top:0;padding:14px 16px;border-radius:10px;background:#fff8e1;border:1px solid #ffe082;color:#6d4c00;font-size:13px}
.device-title{font-size:16px;font-weight:600;color:#333;margin-top:4px}
@media (max-width:720px){
    .hero{align-items:flex-start}
    .hero-actions{width:100%;justify-content:flex-start}
    .detail-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>
<div class="page-wrap">
    <div class="hero">
        <div>
            <h1>Device Detail Info</h1>
            <p>Live device profile and remote control for <?= detail_info_h($deviceName) ?></p>
            <?php if ($customerName !== '') { ?>
                <div class="device-title">Customer: <?= detail_info_h($customerName) ?></div>
            <?php } ?>
        </div>
        <div class="hero-actions">
            <a class="btn btn-secondary" href="dashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
            <?php if ($rawDataUrl !== '') { ?>
                <a class="btn btn-secondary" href="<?= detail_info_h($rawDataUrl) ?>"><i class="fa-solid fa-table"></i> Raw Data</a>
            <?php } ?>
            <button type="button" class="btn btn-start" id="btn-detail-start" onclick="sendDetailCommand('START', this)">
                <i class="fa-solid fa-play"></i> Start
            </button>
            <button type="button" class="btn btn-pause" id="btn-detail-pause" onclick="sendDetailCommand('STOP', this)">
                <i class="fa-solid fa-pause"></i> Pause
            </button>
            <button type="button" class="btn btn-quick" id="btn-detail-quick" onclick="sendDetailCommand('QUICK', this)">
                <i class="fa-solid fa-bolt"></i> Quick
            </button>
            <button type="button" class="btn btn-reset" id="btn-detail-reset" onclick="sendDetailCommand('RESET', this)">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </button>
        </div>
    </div>

    <div id="detail-alert" class="alert" aria-live="polite"></div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="label">WTG Status</div>
            <div class="value">
                <span class="badge-wtg-<?= detail_info_h($legend['legend_class']) ?>"><?= detail_info_h($legend['legend_name']) ?></span>
            </div>
        </div>
        <div class="summary-card">
            <div class="label">Last Update</div>
            <div class="value" style="font-size:18px;"><?= detail_info_h($lastUpdateDisplay) ?></div>
        </div>
        <div class="summary-card">
            <div class="label">Wind Speed</div>
            <div class="value"><?= detail_info_h($windSpeed) ?> <span style="font-size:14px;font-weight:500;">m/s</span></div>
        </div>
        <div class="summary-card">
            <div class="label">Power</div>
            <div class="value"><?= detail_info_h($power) ?> <span style="font-size:14px;font-weight:500;">kW</span></div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">Device Information</div>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="label">Device Name</span>
                <span class="value"><?= detail_info_h($deviceName) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">IMEI</span>
                <span class="value"><?= detail_info_h($imei) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Format Type</span>
                <span class="value"><?= detail_info_h($formatType) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Database</span>
                <span class="value"><?= detail_info_h($databaseName) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">HTSC No</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['HTSC_No']) ? $device['HTSC_No'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">WEG No</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['WEG_No']) ? $device['WEG_No'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">LOC</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['Site_Location']) ? $device['Site_Location'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Site Location</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['Site_Location']) ? $device['Site_Location'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Capacity</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['Capacity']) ? $device['Capacity'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">SF No</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['SF_No']) ? $device['SF_No'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Connect Feeder</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['Connect_Feeder']) ? $device['Connect_Feeder'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Date of Commission</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['Date_Of_Commission']) ? $device['Date_Of_Commission'] : '', '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Pocket Length</span>
                <span class="value"><?= detail_info_h($pocketLength) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Register Status</span>
                <span class="value"><?= detail_info_h($registerLabel) ?></span>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">Live Telemetry</div>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="label">Telemetry Status</span>
                <span class="value"><?= detail_info_h($statusText) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Last Packet Date</span>
                <span class="value"><?= detail_info_h(detail_info_display_value($dateF, '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Last Packet Time</span>
                <span class="value"><?= detail_info_h(detail_info_display_value($timeF, '-')) ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Gen. Daily</span>
                <span class="value"><?= detail_info_h($generation['gen_daily']) ?> kWh</span>
            </div>
            <div class="detail-item">
                <span class="label">Prev. Day Gen</span>
                <span class="value"><?= detail_info_h($generation['prev_day_gen']) ?> kWh</span>
            </div>
            <div class="detail-item">
                <span class="label">State</span>
                <span class="value"><?= detail_info_h(detail_info_display_value(isset($device['State']) ? $device['State'] : '', '-')) ?></span>
            </div>
        </div>
    </div>

    <div class="note">
        <strong>Remote commands:</strong> Start → <code>$CFG&lt;Start&gt;</code>,
        Pause → <code>$CFG&lt;Pause&gt;</code>,
        Quick → <code>$CFG&lt;Quick&gt;</code>,
        Reset → <code>$CFG&lt;Reset&gt;</code>.
        Commands are queued in the database and delivered when this device sends its next TCP packet to ChennaiSCADA.
    </div>
</div>

<script>
function showDetailAlert(message, type) {
    var alertEl = document.getElementById('detail-alert');
    if (!alertEl) {
        return;
    }
    alertEl.textContent = message || '';
    alertEl.className = 'alert show' + (type ? (' alert-' + type) : '');
}

function sendDetailCommand(command, button) {
    if (!command) {
        return false;
    }

    showDetailAlert('Posting TCP command...', '');

    if (button) {
        button.disabled = true;
    }

    var request = new XMLHttpRequest();
    var body =
        'c1=<?= $imeiEncodedSafe ?>' +
        '&db=<?= $databaseNameSafe ?>' +
        '&ajax=1&cmd=' + encodeURIComponent(command);

    request.open('POST', 'TcpRequest.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onreadystatechange = function() {
        if (request.readyState !== 4) {
            return;
        }
        if (button) {
            button.disabled = false;
        }
        try {
            var response = JSON.parse(request.responseText);
            if (request.status >= 200 && request.status < 300 && response.ok) {
                showDetailAlert(response.message || 'Command queued.', 'success');
            } else {
                showDetailAlert((response && response.message) ? response.message : 'Command failed.', 'error');
            }
        } catch (e) {
            showDetailAlert('Unexpected response from server.', 'error');
        }
    };
    request.send(body);
    return false;
}
</script>
</body>
</html>
