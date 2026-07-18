<?php

function dbmgmt_require_auth() {
    include_once __DIR__ . '/config.php';
    include_once __DIR__ . '/dbconn.php';
    include_once __DIR__ . '/Declaration.php';

    if (empty($_COOKIE[$Cook_Name])) {
        dbmgmt_json_response(array('ok' => false, 'error' => 'Authentication required.'), 401);
    }

    $Cook_Variable = explode('|', $_COOKIE[$Cook_Name]);
    $User_Type_ID = isset($Cook_Variable[2]) ? (string)$Cook_Variable[2] : '';
    if (!in_array($User_Type_ID, array('1', '2'), true)) {
        dbmgmt_json_response(array('ok' => false, 'error' => 'Access denied.'), 403);
    }

    return array(
        'db' => $db,
        'username' => $Cook_Variable[0],
        'user_type_id' => $User_Type_ID,
    );
}

function dbmgmt_json_response($payload, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

function dbmgmt_sanitize_db_name($name) {
    $name = strtolower(trim($name));
    if (!preg_match('/^va_[a-z0-9_]+$/', $name)) {
        return null;
    }
    if (in_array($name, array('va_master'), true)) {
        return null;
    }
    return $name;
}

function dbmgmt_username_to_db_name($username) {
    $slug = strtolower(trim($username));
    $slug = preg_replace('/[^a-z0-9_]+/', '_', $slug);
    $slug = trim(preg_replace('/_+/', '_', $slug), '_');
    if ($slug === '') {
        return null;
    }
    return dbmgmt_sanitize_db_name('va_' . $slug);
}

function dbmgmt_mysql_bin() {
    $mysql = 'mysql';
    $possible = array(
        'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysql.exe',
        'C:\\Program Files\\MySQL\\MySQL Server 8.4\\bin\\mysql.exe',
    );
    foreach ($possible as $candidate) {
        if (is_file($candidate)) {
            return $candidate;
        }
    }
    return $mysql;
}

function dbmgmt_mysqldump_bin() {
    $mysql = dbmgmt_mysql_bin();
    if (substr($mysql, -9) === 'mysql.exe') {
        $dump = substr($mysql, 0, -9) . 'mysqldump.exe';
        if (is_file($dump)) {
            return $dump;
        }
    }
    return 'mysqldump';
}

function dbmgmt_rename_database($db, $oldName, $newName) {
    if ($oldName === $newName) {
        return null;
    }
    if (!dbmgmt_database_exists($db, $oldName)) {
        return 'Current customer database was not found.';
    }
    if (dbmgmt_database_exists($db, $newName)) {
        return 'Target database name already exists.';
    }

    $escapedOld = $db->real_escape_string($oldName);
    $escapedNew = $db->real_escape_string($newName);
    if (!$db->query("CREATE DATABASE `$escapedNew` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        return 'Failed to create renamed database: ' . $db->error;
    }

    $tables = array();
    $views = array();
    $result = $db->query("SHOW FULL TABLES FROM `$escapedOld`");
    if (!$result) {
        $db->query("DROP DATABASE IF EXISTS `$escapedNew`");
        return 'Failed to read tables from current database: ' . $db->error;
    }
    while ($row = $result->fetch_array()) {
        if (isset($row[1]) && strtoupper($row[1]) === 'VIEW') {
            $views[] = $row[0];
        } else {
            $tables[] = $row[0];
        }
    }

    if (!empty($tables)) {
        $renames = array();
        foreach ($tables as $table) {
            $escapedTable = $db->real_escape_string($table);
            $renames[] = "`$escapedOld`.`$escapedTable` TO `$escapedNew`.`$escapedTable`";
        }
        foreach (array_chunk($renames, 50) as $chunk) {
            $sql = 'RENAME TABLE ' . implode(', ', $chunk);
            if (!$db->query($sql)) {
                $db->query("DROP DATABASE IF EXISTS `$escapedNew`");
                return 'Failed to move tables during rename: ' . $db->error;
            }
        }
    }

    foreach ($views as $view) {
        $escapedView = $db->real_escape_string($view);
        $createResult = $db->query("SHOW CREATE VIEW `$escapedOld`.`$escapedView`");
        if (!$createResult || !($createRow = $createResult->fetch_assoc())) {
            $db->query("DROP DATABASE IF EXISTS `$escapedNew`");
            return 'Failed to read view definition during rename: ' . $db->error;
        }
        $createSql = $createRow['Create View'];
        if (!$db->query($createSql)) {
            $db->query("DROP DATABASE IF EXISTS `$escapedNew`");
            return 'Failed to recreate view during rename: ' . $db->error;
        }
        if (!$db->query("DROP VIEW `$escapedOld`.`$escapedView`")) {
            return 'Tables moved but failed to drop old view: ' . $db->error;
        }
    }

    if (!$db->query("DROP DATABASE `$escapedOld`")) {
        return 'Tables moved but failed to drop old database: ' . $db->error;
    }

    return null;
}

function dbmgmt_customer_sql_template() {
    $path = realpath(__DIR__ . '/../gogreen/jayakrishna.sql');
    return $path && is_file($path) ? $path : null;
}

function dbmgmt_database_exists($db, $dbName) {
    $escaped = $db->real_escape_string($dbName);
    $result = $db->query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$escaped' LIMIT 1");
    return $result && $result->num_rows > 0;
}

function dbmgmt_import_sql_template($dbName) {
    $template = dbmgmt_customer_sql_template();
    if (!$template) {
        return 'Customer SQL template not found.';
    }

    $sql = file_get_contents($template);
    if ($sql === false || trim($sql) === '') {
        return 'Customer SQL template is empty or unreadable.';
    }

    $importDb = new mysqli('localhost', 'chennai_scada_app', 'ChennaiSCADA_App_2026', $dbName);
    if ($importDb->connect_errno) {
        return 'Failed to connect for template import: ' . $importDb->connect_error;
    }
    $importDb->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

    if (!$importDb->multi_query($sql)) {
        $error = $importDb->error;
        $importDb->close();
        return 'Failed to import customer database template: ' . $error;
    }

    do {
        if ($result = $importDb->store_result()) {
            $result->free();
        }
        if ($importDb->errno) {
            $error = $importDb->error;
            $importDb->close();
            return 'Failed to import customer database template: ' . $error;
        }
    } while ($importDb->more_results() && $importDb->next_result());

    $importDb->close();
    return null;
}

function dbmgmt_check_username($db, $username, $excludeAccountId = 0) {
    $username = trim($username);
    if ($username === '') {
        return array('ok' => true, 'available' => false, 'error' => 'Username is required.');
    }

    $stmt = $db->prepare('SELECT Account_ID FROM va_master.user_master WHERE Username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return array('ok' => true, 'available' => true);
    }

    $row = $result->fetch_assoc();
    if ($excludeAccountId > 0 && (int)$row['Account_ID'] === $excludeAccountId) {
        return array('ok' => true, 'available' => true);
    }

    return array('ok' => true, 'available' => false, 'error' => 'Username already exists.');
}


function dbmgmt_list_user_types($db) {
    $types = array();
    $query = 'SELECT User_Type_ID, User_Type, User_Type_Description FROM va_master.user_type ORDER BY User_Type_ID ASC';
    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $types[] = $row;
        }
    }
    return $types;
}

function dbmgmt_resolve_user_type_id($db, $userTypeId) {
    $userTypeId = (int)$userTypeId;
    if ($userTypeId <= 0) {
        return 4;
    }
    $stmt = $db->prepare('SELECT User_Type_ID FROM va_master.user_type WHERE User_Type_ID = ? LIMIT 1');
    $stmt->bind_param('i', $userTypeId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows ? $userTypeId : 4;
}
function dbmgmt_list_customers($db) {
    $customers = array();
    $query = "SELECT u.Account_ID, u.Firstname, u.Lastname, u.Username, u.Password, u.E_Mail, u.Phone, u.Db_Name,
                     u.User_Type_ID, u.AdminAccess, u.Valid_Till, u.Date_Stamp,
                     ut.User_Type,
                     (SELECT COUNT(*) FROM va_master.device_register d WHERE d.Account_ID = u.Account_ID) AS device_count
              FROM va_master.user_master u
              LEFT JOIN va_master.user_type ut ON ut.User_Type_ID = u.User_Type_ID
              WHERE u.Db_Name <> 'va_master'
              ORDER BY u.Account_ID ASC";
    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    }
    return $customers;
}



function dbmgmt_get_device_by_imei($db, $imei) {
    $imei = trim((string)$imei);
    if ($imei === '' || !ctype_digit($imei)) {
        return null;
    }
    $stmt = $db->prepare('SELECT * FROM va_master.device_register WHERE IMEI = ? LIMIT 1');
    $stmt->bind_param('s', $imei);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows ? dbmgmt_normalize_device_row($result->fetch_assoc()) : null;
}

function dbmgmt_register_status($device) {
    if (isset($device['Register_Status'])) {
        return (int)$device['Register_Status'];
    }
    if (isset($device['Status']) && is_numeric($device['Status'])) {
        return (int)$device['Status'];
    }
    return 0;
}

function dbmgmt_normalize_device_row($row) {
    $row['Status'] = dbmgmt_register_status($row);
    return $row;
}

function dbmgmt_check_imei($db, $imei, $excludeDeviceIndex = 0) {
    $imei = trim((string)$imei);
    if ($imei === '') {
        return array('ok' => true, 'available' => false, 'error' => 'IMEI is required.');
    }
    if (!ctype_digit($imei)) {
        return array('ok' => true, 'available' => false, 'error' => 'IMEI must contain digits only.');
    }

    $existing = dbmgmt_get_device_by_imei($db, $imei);
    if (!$existing) {
        return array('ok' => true, 'available' => true);
    }

    if ($excludeDeviceIndex > 0 && (int)$existing['Device_Index'] === (int)$excludeDeviceIndex) {
        return array('ok' => true, 'available' => true);
    }

    $deviceName = isset($existing['Device_Name']) ? trim((string)$existing['Device_Name']) : '';
    $error = $deviceName !== ''
        ? 'IMEI already registered to device "' . $deviceName . '".'
        : 'IMEI already registered.';

    return array(
        'ok' => true,
        'available' => false,
        'error' => $error,
        'device_index' => (int)$existing['Device_Index'],
        'device_name' => $deviceName,
    );
}

function dbmgmt_resolve_device_for_raw_data($db, $input) {
    $deviceIndex = (int)(isset($input['device_index']) ? $input['device_index'] : 0);
    if ($deviceIndex > 0) {
        $device = dbmgmt_get_device($db, $deviceIndex);
        if ($device) {
            return $device;
        }
    }

    $imei = trim(isset($input['imei']) ? $input['imei'] : (isset($input['c1']) ? $input['c1'] : ''));
    if ($imei !== '') {
        return dbmgmt_get_device_by_imei($db, $imei);
    }

    return null;
}

function dbmgmt_raw_data_table_for_format($formatType) {
    $formatType = (int)$formatType;
    if ($formatType === 1) {
        return 'device_data';
    }
    if ($formatType >= 2 && $formatType <= 11) {
        return 'device_data_f' . $formatType;
    }
    return null;
}

function dbmgmt_raw_data_columns($formatType) {
    switch ((int)$formatType) {
        case 1:
        case 6:
            return array(
                array('key' => 'Date_S', 'label' => 'Date'),
                array('key' => 'Time_S', 'label' => 'Time'),
                array('key' => 'PAT_Gen1', 'label' => 'PAT Gen1'),
                array('key' => 'PAT_Gen2', 'label' => 'PAT Gen2'),
                array('key' => 'Run_Hours', 'label' => 'Run Hours'),
                array('key' => 'Status', 'label' => 'Status'),
            );
        case 2:
        case 4:
            return array(
                array('key' => 'Date_S', 'label' => 'Date'),
                array('key' => 'Time_S', 'label' => 'Time'),
                array('key' => 'Windspeed', 'label' => 'Wind Speed'),
                array('key' => 'Power', 'label' => 'Power'),
                array('key' => 'PAT_Gen1', 'label' => 'PAT Gen1'),
                array('key' => 'PAT_Gen2', 'label' => 'PAT Gen2'),
                array('key' => 'Status', 'label' => 'Status'),
            );
        case 3:
            return array(
                array('key' => 'Date_S', 'label' => 'Date'),
                array('key' => 'Time_S', 'label' => 'Time'),
                array('key' => 'Production_Total', 'label' => 'Production'),
                array('key' => 'Gen1_Hours', 'label' => 'Gen1 Hours'),
                array('key' => 'Gen2_Hours', 'label' => 'Gen2 Hours'),
                array('key' => 'Status', 'label' => 'Status'),
            );
        case 7:
        case 8:
            return array(
                array('key' => 'Date_S', 'label' => 'Date'),
                array('key' => 'Time_S', 'label' => 'Time'),
                array('key' => 'Windspeed', 'label' => 'Wind Speed'),
                array('key' => 'Power', 'label' => 'Power'),
                array('key' => 'GRPM', 'label' => 'GRPM'),
                array('key' => 'RRPM', 'label' => 'RRPM'),
                array('key' => 'Status', 'label' => 'Status'),
            );
        case 10:
            return array(
                array('key' => 'Date_S', 'label' => 'Date'),
                array('key' => 'Time_S', 'label' => 'Time'),
                array('key' => 'Production_Total', 'label' => 'Production'),
                array('key' => 'Line_Hours', 'label' => 'Line Hours'),
                array('key' => 'Run_Hours', 'label' => 'Run Hours'),
                array('key' => 'Status', 'label' => 'Status'),
            );
        case 11:
            return array(
                array('key' => 'Date_S', 'label' => 'Date'),
                array('key' => 'Time_S', 'label' => 'Time'),
                array('key' => 'tag_windspd', 'label' => 'Wind Speed'),
                array('key' => 'tag_power', 'label' => 'Power'),
                array('key' => 'tag_status', 'label' => 'Status'),
            );
        default:
            return array(
                array('key' => 'Date_S', 'label' => 'Date'),
                array('key' => 'Time_S', 'label' => 'Time'),
                array('key' => 'Status', 'label' => 'Status'),
            );
    }
}


function dbmgmt_default_raw_data_date($device) {
    if (!empty($device['date_s'])) {
        return $device['date_s'];
    }
    return date('Y-m-d');
}
function dbmgmt_fetch_device_raw_data($db, $device, $date = null, $limit = 100) {
    $imei = trim((string)$device['IMEI']);
    $dbName = dbmgmt_sanitize_db_name(isset($device['db_name']) ? $device['db_name'] : '');
    $formatType = (int)(isset($device['Format_Type']) ? $device['Format_Type'] : 0);
    $table = dbmgmt_raw_data_table_for_format($formatType);

    if ($imei === '' || $dbName === null || $table === null) {
        return array('ok' => false, 'error' => 'Device is missing a valid database or format type.');
    }

    if (!dbmgmt_database_exists($db, $dbName)) {
        return array('ok' => false, 'error' => 'Customer database was not found.');
    }

    $date = $date ? $date : date('Y-m-d');
    $limit = max(1, min(500, (int)$limit));
    $columns = dbmgmt_raw_data_columns($formatType);
    $selectCols = array('Record_Index');
    foreach ($columns as $column) {
        if (!in_array($column['key'], $selectCols, true)) {
            $selectCols[] = $column['key'];
        }
    }

    $escapedDb = $db->real_escape_string($dbName);
    $escapedTable = $db->real_escape_string($table);
    $colSql = '`' . implode('`,`', $selectCols) . '`';
    $sql = "SELECT $colSql FROM `$escapedDb`.`$escapedTable` WHERE IMEI = ? AND Date_S = ? ORDER BY Record_Index DESC LIMIT ?";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return array('ok' => false, 'error' => 'Failed to prepare raw data query: ' . $db->error);
    }
    $stmt->bind_param('ssi', $imei, $date, $limit);
    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => 'Failed to load raw data: ' . $db->error);
    }

    $result = $stmt->get_result();
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    $latest = count($rows) ? $rows[0] : null;
    $summary = array(
        'record_count' => count($rows),
        'date' => $date,
        'latest_date' => $latest ? $latest['Date_S'] : (isset($device['date_s']) ? $device['date_s'] : ''),
        'latest_time' => $latest ? $latest['Time_S'] : (isset($device['time_s']) ? $device['time_s'] : ''),
        'device_status' => dbmgmt_register_status($device),
    );

    if ($latest) {
        foreach (array('Windspeed', 'tag_windspd') as $windKey) {
            if (isset($latest[$windKey])) {
                $summary['wind_speed'] = $latest[$windKey];
                break;
            }
        }
        foreach (array('Power', 'tag_power') as $powerKey) {
            if (isset($latest[$powerKey])) {
                $summary['power'] = $latest[$powerKey];
                break;
            }
        }
        foreach (array('Status', 'tag_status') as $statusKey) {
            if (isset($latest[$statusKey])) {
                $summary['status_text'] = $latest[$statusKey];
                break;
            }
        }
    }

    return array(
        'ok' => true,
        'device' => array(
            'Device_Index' => $device['Device_Index'],
            'Device_Name' => $device['Device_Name'],
            'IMEI' => $device['IMEI'],
            'Format_Type' => $formatType,
            'db_name' => $dbName,
            'Status' => dbmgmt_register_status($device),
        ),
        'columns' => $columns,
        'rows' => $rows,
        'summary' => $summary,
        'source' => 'database',
    );
}

function dbmgmt_event_log_root() {
    static $root = null;
    if ($root !== null) {
        return $root;
    }
    $candidates = array(
        'C:\\inetpub\\wwwroot\\versatile_log\\EventLog',
        'C:\\inetpub\\wwwroot \\versatile_log\\EventLog',
    );
    foreach ($candidates as $candidate) {
        if (is_dir($candidate)) {
            $root = rtrim($candidate, '\\/');
            return $root;
        }
    }
    return null;
}

function dbmgmt_event_log_date_folder($date) {
    $date = trim((string)$date);
    if ($date === '') {
        return null;
    }
    $ts = strtotime($date);
    if ($ts === false) {
        return null;
    }
    return date('d_m_Y', $ts);
}

function dbmgmt_device_event_log_files($device, $date) {
    $imei = isset($device['IMEI']) ? trim((string)$device['IMEI']) : '';
    $formatType = (int)(isset($device['Format_Type']) ? $device['Format_Type'] : 0);
    $dateFolder = dbmgmt_event_log_date_folder($date);
    $logRoot = dbmgmt_event_log_root();

    if ($imei === '' || !ctype_digit($imei) || $dateFolder === null || $logRoot === null) {
        return array();
    }

    $paths = array();
    $dayDir = $logRoot . DIRECTORY_SEPARATOR . $dateFolder;
    if ($formatType > 0) {
        $typeDir = $dayDir . DIRECTORY_SEPARATOR . 'Type' . $formatType;
        $mainFile = $typeDir . DIRECTORY_SEPARATOR . $imei . '.txt';
        if (is_file($mainFile)) {
            $paths[] = $mainFile;
        }
        if (is_dir($typeDir)) {
            $rotated = glob($typeDir . DIRECTORY_SEPARATOR . '_*.txt');
            if (is_array($rotated)) {
                sort($rotated, SORT_STRING);
                foreach ($rotated as $filePath) {
                    if (is_file($filePath) && !in_array($filePath, $paths, true)) {
                        $paths[] = $filePath;
                    }
                }
            }
        }
    }

    $unknownFile = $dayDir . DIRECTORY_SEPARATOR . $imei . '_UNKNOWN.txt';
    if (is_file($unknownFile)) {
        $paths[] = $unknownFile;
    }

    return array_values(array_unique($paths));
}

function dbmgmt_convert_event_log_timestamp($raw) {
    $raw = trim((string)$raw);
    if ($raw === '') {
        return '';
    }

    $formats = array(
        'n/j/Y g:i:s A P',
        'm/d/Y g:i:s A P',
        'n/j/Y g:i:s A O',
        'm/d/Y g:i:s A O',
        'n/j/Y H:i:s P',
        'm/d/Y H:i:s P',
    );

    foreach ($formats as $format) {
        $dt = DateTime::createFromFormat($format, $raw);
        if ($dt instanceof DateTime) {
            return $dt->format('Y-m-d H:i:s');
        }
    }

    $ts = strtotime($raw);
    if ($ts !== false) {
        return date('Y-m-d H:i:s', $ts);
    }

    return '';
}

function dbmgmt_normalize_event_log_line($line) {
    $line = (string)$line;
    if ($line === '') {
        return $line;
    }

    return preg_replace_callback(
        '/\[(\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2}:\d{2}(?:\s+(?:AM|PM))?(?:\s+[+-]\d{2}:?\d{2})?)\]/i',
        function ($matches) {
            $converted = dbmgmt_convert_event_log_timestamp($matches[1]);
            if ($converted === '') {
                return $matches[0];
            }
            return '[' . $converted . ']';
        },
        $line
    );
}

function dbmgmt_split_normalized_timestamp($normalized) {
    $normalized = trim((string)$normalized);
    if ($normalized === '') {
        return array('', '');
    }
    $parts = explode(' ', $normalized, 2);
    return array(
        isset($parts[0]) ? $parts[0] : '',
        isset($parts[1]) ? $parts[1] : '',
    );
}

function dbmgmt_read_event_log_lines($filePaths, $limit) {
    $lines = array();
    foreach ($filePaths as $filePath) {
        if (!is_readable($filePath)) {
            continue;
        }
        $handle = @fopen($filePath, 'rb');
        if (!$handle) {
            continue;
        }
        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");
            if (trim($line) === '') {
                continue;
            }
            $lines[] = $line;
        }
        fclose($handle);
    }

    if (count($lines) > $limit) {
        $lines = array_slice($lines, -$limit);
    }

    $rows = array();
    $rowNumber = 0;
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $rowNumber++;
        $rows[] = array(
            'line_no' => $rowNumber,
            'data' => dbmgmt_normalize_event_log_line($lines[$i]),
        );
    }

    return $rows;
}

function dbmgmt_fetch_device_raw_data_file($device, $date = null, $limit = 500) {
    $imei = trim((string)$device['IMEI']);
    $formatType = (int)(isset($device['Format_Type']) ? $device['Format_Type'] : 0);
    $dbName = dbmgmt_sanitize_db_name(isset($device['db_name']) ? $device['db_name'] : '');

    if ($imei === '' || !ctype_digit($imei)) {
        return array('ok' => false, 'error' => 'Device IMEI is invalid.');
    }

    $logRoot = dbmgmt_event_log_root();
    if ($logRoot === null) {
        return array('ok' => false, 'error' => 'Event log folder was not found on the server.');
    }

    $date = $date ? $date : date('Y-m-d');
    $limit = max(1, min(2000, (int)$limit));
    $filePaths = dbmgmt_device_event_log_files($device, $date);
    $rows = dbmgmt_read_event_log_lines($filePaths, $limit);

    $logFileLabel = count($filePaths) ? $filePaths[0] : (
        $logRoot . DIRECTORY_SEPARATOR . dbmgmt_event_log_date_folder($date) .
        DIRECTORY_SEPARATOR . ($formatType > 0 ? ('Type' . $formatType . DIRECTORY_SEPARATOR . $imei . '.txt') : ($imei . '_UNKNOWN.txt'))
    );

    $latestLine = count($rows) ? $rows[0]['data'] : '';
    $latestDate = '';
    $latestTime = '';
    if ($latestLine !== '' && preg_match('/\]\s*\[(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})\]/', $latestLine, $matches)) {
        list($latestDate, $latestTime) = dbmgmt_split_normalized_timestamp($matches[1]);
    } elseif ($latestLine !== '' && preg_match('/\[(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})\]/', $latestLine, $matches)) {
        list($latestDate, $latestTime) = dbmgmt_split_normalized_timestamp($matches[1]);
    }

    return array(
        'ok' => true,
        'source' => 'file',
        'device' => array(
            'Device_Index' => $device['Device_Index'],
            'Device_Name' => $device['Device_Name'],
            'IMEI' => $device['IMEI'],
            'Format_Type' => $formatType,
            'db_name' => $dbName !== null ? $dbName : '',
            'Status' => dbmgmt_register_status($device),
        ),
        'columns' => array(
            array('key' => 'line_no', 'label' => '#'),
            array('key' => 'data', 'label' => 'data'),
        ),
        'rows' => $rows,
        'summary' => array(
            'record_count' => count($rows),
            'date' => $date,
            'latest_date' => $latestDate,
            'latest_time' => $latestTime,
            'device_status' => dbmgmt_register_status($device),
            'log_file' => $logFileLabel,
            'log_files' => $filePaths,
        ),
    );
}

function dbmgmt_device_raw_data_url($device) {
    $imei = isset($device['IMEI']) ? trim((string)$device['IMEI']) : '';
    $dbName = isset($device['db_name']) ? trim((string)$device['db_name']) : '';
    $formatType = (int)(isset($device['Format_Type']) ? $device['Format_Type'] : 0);
    if ($imei === '' || $dbName === '') {
        return null;
    }
    if (isset($device['Device_Index']) && (int)$device['Device_Index'] > 0) {
        return 'device_raw_data.php?device_index=' . (int)$device['Device_Index'];
    }
    return 'device_raw_data.php?' . http_build_query(array(
        'imei' => $imei,
        'db' => $dbName,
        'format' => $formatType,
    ));
}
function dbmgmt_list_devices($db) {
    $devices = array();
    $query = "SELECT d.*, u.Firstname, u.Lastname, u.Username
              FROM va_master.device_register d
              LEFT JOIN va_master.user_master u ON u.Account_ID = d.Account_ID
              ORDER BY d.Device_Name ASC, d.IMEI ASC";
    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $row = dbmgmt_normalize_device_row($row);
            $row['raw_data_url'] = dbmgmt_device_raw_data_url($row);
            $devices[] = $row;
        }
    }
    return $devices;
}


function dbmgmt_get_account_db_name($db, $accountId) {
    $accountId = (int)$accountId;
    if ($accountId <= 0) {
        return null;
    }
    $stmt = $db->prepare('SELECT Db_Name FROM va_master.user_master WHERE Account_ID = ? LIMIT 1');
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->num_rows) {
        return null;
    }
    return dbmgmt_sanitize_db_name($result->fetch_assoc()['Db_Name']);
}
function dbmgmt_list_customer_options($db) {
    $options = array();
    $query = "SELECT Account_ID, Firstname, Lastname, Username, Db_Name
              FROM va_master.user_master
              WHERE User_Type_ID = 4
                AND Db_Name <> 'va_master'
              ORDER BY Firstname, Lastname";
    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $options[] = $row;
        }
    }
    return $options;
}

function dbmgmt_get_customer($db, $accountId) {
    $stmt = $db->prepare("SELECT Account_ID, Firstname, Lastname, Username, E_Mail, Phone, Db_Name, User_Type_ID
                          FROM va_master.user_master
                          WHERE Account_ID = ? AND Db_Name <> 'va_master' LIMIT 1");
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows ? $result->fetch_assoc() : null;
}

function dbmgmt_create_customer($db, $input) {
    $firstname = trim(isset($input['firstname']) ? $input['firstname'] : '');
    $lastname = trim(isset($input['lastname']) ? $input['lastname'] : '');
    $username = trim(isset($input['username']) ? $input['username'] : '');
    $password = trim(isset($input['password']) ? $input['password'] : 'Scada@2026');
    $email = trim(isset($input['email']) ? $input['email'] : '');
    $phone = trim(isset($input['phone']) ? $input['phone'] : '');
    $dbName = dbmgmt_sanitize_db_name(isset($input['db_name']) ? $input['db_name'] : '');
    if ($dbName === null) {
        $dbName = dbmgmt_username_to_db_name($username);
    }

    if ($firstname === '' || $username === '' || $dbName === null) {
        return array('ok' => false, 'error' => 'First name, username, and a valid database name (va_*) are required.');
    }

    $usernameCheck = dbmgmt_check_username($db, $username);
    if (!$usernameCheck['available']) {
        return array('ok' => false, 'error' => isset($usernameCheck['error']) ? $usernameCheck['error'] : 'Username is not available.');
    }

    if (dbmgmt_database_exists($db, $dbName)) {
        return array('ok' => false, 'error' => 'Database already exists.');
    }

    $dupDb = $db->prepare('SELECT Account_ID FROM va_master.user_master WHERE Db_Name = ? LIMIT 1');
    $dupDb->bind_param('s', $dbName);
    $dupDb->execute();
    if ($dupDb->get_result()->num_rows > 0) {
        return array('ok' => false, 'error' => 'A customer with this database name already exists.');
    }

    $userType = dbmgmt_resolve_user_type_id($db, isset($input['user_type_id']) ? $input['user_type_id'] : 4);
    $adminAccess = 'Enabled';
    $inTime = '00:00:00';
    $outTime = '23:59:59';
    $parentId = 0;
    $validTill = date('Y-m-d', strtotime('+5 years'));
    $apiKey = '0';

    $insert = $db->prepare("INSERT INTO va_master.user_master
        (Firstname, Lastname, Username, Password, E_Mail, Phone, User_Type_ID, AdminAccess, In_Time, Out_Time, Parent_ID, Db_Name, Valid_Till, api_key)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param(
        'ssssssisssisss',
        $firstname,
        $lastname,
        $username,
        $password,
        $email,
        $phone,
        $userType,
        $adminAccess,
        $inTime,
        $outTime,
        $parentId,
        $dbName,
        $validTill,
        $apiKey
    );

    if (!$insert->execute()) {
        return array('ok' => false, 'error' => 'Failed to create customer record: ' . $db->error);
    }

    $escapedDb = $db->real_escape_string($dbName);
    if (!$db->query("CREATE DATABASE `$escapedDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        $accountId = $insert->insert_id;
        $db->query('DELETE FROM va_master.user_master WHERE Account_ID = ' . (int)$accountId);
        return array('ok' => false, 'error' => 'Failed to create database: ' . $db->error);
    }

    $importError = dbmgmt_import_sql_template($dbName);
    if ($importError !== null) {
        $accountId = $insert->insert_id;
        $db->query('DELETE FROM va_master.user_master WHERE Account_ID = ' . (int)$accountId);
        $db->query("DROP DATABASE IF EXISTS `$escapedDb`");
        return array('ok' => false, 'error' => $importError);
    }

    return array('ok' => true, 'message' => 'Customer created successfully.', 'account_id' => $insert->insert_id);
}

function dbmgmt_update_customer($db, $input) {
    $accountId = (int)(isset($input['account_id']) ? $input['account_id'] : 0);
    if ($accountId <= 0) {
        return array('ok' => false, 'error' => 'Invalid customer.');
    }

    $existing = dbmgmt_get_customer($db, $accountId);
    if (!$existing) {
        return array('ok' => false, 'error' => 'Customer not found.');
    }

    $firstname = trim(isset($input['firstname']) ? $input['firstname'] : $existing['Firstname']);
    $lastname = trim(isset($input['lastname']) ? $input['lastname'] : $existing['Lastname']);
    $username = trim(isset($input['username']) ? $input['username'] : $existing['Username']);
    $email = trim(isset($input['email']) ? $input['email'] : $existing['E_Mail']);
    $phone = trim(isset($input['phone']) ? $input['phone'] : $existing['Phone']);
    $password = trim(isset($input['password']) ? $input['password'] : '');
    $userType = dbmgmt_resolve_user_type_id($db, isset($input['user_type_id']) ? $input['user_type_id'] : $existing['User_Type_ID']);

    $newDbName = dbmgmt_sanitize_db_name(isset($input['db_name']) ? $input['db_name'] : '');
    if ($newDbName === null) {
        $newDbName = dbmgmt_username_to_db_name($username);
    }
    if ($newDbName === null) {
        return array('ok' => false, 'error' => 'Username must produce a valid database name (va_*).');
    }

    $oldDbName = $existing['Db_Name'];

    $checkUser = dbmgmt_check_username($db, $username, $accountId);
    if (!$checkUser['available']) {
        return array('ok' => false, 'error' => isset($checkUser['error']) ? $checkUser['error'] : 'Username already exists.');
    }

    if ($newDbName !== $oldDbName) {
        $dupDb = $db->prepare('SELECT Account_ID FROM va_master.user_master WHERE Db_Name = ? AND Account_ID <> ? LIMIT 1');
        $dupDb->bind_param('si', $newDbName, $accountId);
        $dupDb->execute();
        if ($dupDb->get_result()->num_rows > 0) {
            return array('ok' => false, 'error' => 'A customer with this database name already exists.');
        }

        $renameError = dbmgmt_rename_database($db, $oldDbName, $newDbName);
        if ($renameError !== null) {
            return array('ok' => false, 'error' => $renameError);
        }

        $escapedNew = $db->real_escape_string($newDbName);
        $db->query('UPDATE va_master.device_register SET db_name = \'' . $escapedNew . '\' WHERE Account_ID = ' . (int)$accountId . " OR db_name = '" . $db->real_escape_string($oldDbName) . "'");
    }

    if ($password !== '') {
        $stmt = $db->prepare("UPDATE va_master.user_master
            SET Firstname = ?, Lastname = ?, Username = ?, Password = ?, E_Mail = ?, Phone = ?, Db_Name = ?, User_Type_ID = ?
            WHERE Account_ID = ? AND Db_Name <> 'va_master'");
        $stmt->bind_param('sssssssii', $firstname, $lastname, $username, $password, $email, $phone, $newDbName, $userType, $accountId);
    } else {
        $stmt = $db->prepare("UPDATE va_master.user_master
            SET Firstname = ?, Lastname = ?, Username = ?, E_Mail = ?, Phone = ?, Db_Name = ?, User_Type_ID = ?
            WHERE Account_ID = ? AND Db_Name <> 'va_master'");
        $stmt->bind_param('ssssssii', $firstname, $lastname, $username, $email, $phone, $newDbName, $userType, $accountId);
    }

    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => 'Failed to update customer: ' . $db->error);
    }

    return array('ok' => true, 'message' => 'Customer updated successfully.');
}

function dbmgmt_delete_customer($db, $accountId) {
    $customer = dbmgmt_get_customer($db, $accountId);
    if (!$customer) {
        return array('ok' => false, 'error' => 'Customer not found or cannot be deleted.');
    }

    $dbName = $customer['Db_Name'];
    $escapedDb = $db->real_escape_string($dbName);

    $db->query('DELETE FROM va_master.device_register WHERE Account_ID = ' . (int)$accountId . " OR db_name = '$escapedDb'");
    $db->query('DELETE FROM va_master.user_master WHERE Account_ID = ' . (int)$accountId);

    if ($dbName !== 'va_master' && dbmgmt_database_exists($db, $dbName)) {
        if (!$db->query("DROP DATABASE `$escapedDb`")) {
            return array('ok' => false, 'error' => 'Customer record removed, but database drop failed: ' . $db->error);
        }
    }

    return array('ok' => true, 'message' => 'Customer and database deleted successfully.');
}

function dbmgmt_delete_database($db, $dbName) {
    $dbName = dbmgmt_sanitize_db_name($dbName);
    if ($dbName === null) {
        return array('ok' => false, 'error' => 'This database cannot be deleted.');
    }

    if (!dbmgmt_database_exists($db, $dbName)) {
        return array('ok' => false, 'error' => 'Database not found.');
    }

    $escapedDb = $db->real_escape_string($dbName);
    $db->query("DELETE FROM va_master.device_register WHERE db_name = '$escapedDb'");
    $db->query("DELETE FROM va_master.user_master WHERE Db_Name = '$escapedDb'");

    if (!$db->query("DROP DATABASE `$escapedDb`")) {
        return array('ok' => false, 'error' => 'Failed to drop database: ' . $db->error);
    }

    return array('ok' => true, 'message' => 'Database deleted successfully.');
}


function dbmgmt_device_last_seen_epoch($device) {
    $date = isset($device['date_s']) ? trim((string)$device['date_s']) : '';
    $time = isset($device['time_s']) ? trim((string)$device['time_s']) : '';
    if ($date === '' || $time === '') {
        return null;
    }
    // Match dashboard.php: device timestamps are IST wall-clock values stored as Y-m-d H:i:s.
    if (!function_exists('GetTimestamp')) {
        include_once __DIR__ . '/functions.php';
    }
    $epoch = GetTimestamp($date, $time);
    return ($epoch !== false && $epoch !== null && (int)$epoch > 0) ? (int)$epoch : null;
}

function dbmgmt_now_epoch() {
    // Same IST adjustment used in dashboard.php ($Req_Time = time() + 5.5h).
    return time() + (int)(60 * 60 * 5.5);
}

function dbmgmt_device_age_seconds($lastSeenEpoch) {
    if ($lastSeenEpoch === null) {
        return PHP_INT_MAX;
    }
    return dbmgmt_now_epoch() - $lastSeenEpoch;
}

function dbmgmt_device_live_state($lastSeenEpoch) {
    if ($lastSeenEpoch === null) {
        return 'No data';
    }
    $age = dbmgmt_device_age_seconds($lastSeenEpoch);
    if ($age <= 900) {
        return 'Online';
    }
    return 'Offline';
}

function dbmgmt_fetch_latest_telemetry_status($db, $device) {
    $imei = isset($device['IMEI']) ? trim((string)$device['IMEI']) : '';
    $dbName = dbmgmt_sanitize_db_name(isset($device['db_name']) ? $device['db_name'] : '');
    $formatType = (int)(isset($device['Format_Type']) ? $device['Format_Type'] : 0);
    $table = dbmgmt_raw_data_table_for_format($formatType);
    if ($imei === '' || $dbName === null || $table === null) {
        return null;
    }

    $escapedDb = $db->real_escape_string($dbName);
    $escapedTable = $db->real_escape_string($table);
    $sql = "SELECT * FROM `$escapedDb`.`$escapedTable` WHERE IMEI = ? ORDER BY Record_Index DESC LIMIT 1";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('s', $imei);
    if (!$stmt->execute()) {
        return null;
    }
    $result = $stmt->get_result();
    if (!$result || !$result->num_rows) {
        return null;
    }
    $row = $result->fetch_assoc();
    $statusText = null;
    $power = null;
    foreach (array('Status', 'tag_status') as $statusKey) {
        if (isset($row[$statusKey]) && trim((string)$row[$statusKey]) !== '') {
            $statusText = trim((string)$row[$statusKey]);
            break;
        }
    }
    foreach (array('Power', 'power') as $powerKey) {
        if (isset($row[$powerKey]) && trim((string)$row[$powerKey]) !== '') {
            $power = trim((string)$row[$powerKey]);
            break;
        }
    }
    return array(
        'status_text' => $statusText,
        'power' => $power,
        'date' => isset($row['Date_S']) ? $row['Date_S'] : null,
        'time' => isset($row['Time_S']) ? $row['Time_S'] : null,
    );
}

function dbmgmt_load_error_type_map($db) {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = array();
    $result = $db->query('SELECT Machine_Status, Error FROM va_master.error_type');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $category = $row['Machine_Status'];
            if (!isset($cache[$category])) {
                $cache[$category] = array();
            }
            $cache[$category][] = strtolower(trim((string)$row['Error']));
        }
    }
    return $cache;
}

function dbmgmt_status_in_error_category($status, $category, $errorMap) {
    $needle = strtolower(trim((string)$status));
    if ($needle === '' || !isset($errorMap[$category])) {
        return false;
    }
    foreach ($errorMap[$category] as $pattern) {
        if ($pattern === '' ) {
            continue;
        }
        if ($needle === $pattern || strpos($needle, $pattern) !== false) {
            return true;
        }
    }
    return false;
}

function dbmgmt_parse_power_value($power) {
    if ($power === null || $power === '') {
        return null;
    }
    $normalized = str_replace(',', '', (string)$power);
    if (!is_numeric($normalized)) {
        return null;
    }
    return (float)$normalized;
}

function dbmgmt_classify_device_legend($registerStatus, $lastSeenEpoch, $telemetryStatus, $power, $errorMap) {
    if ((int)$registerStatus !== 1) {
        return array('legend_class' => 'stopped', 'legend_name' => 'Stopped');
    }

    $age = dbmgmt_device_age_seconds($lastSeenEpoch);
    if ($age >= 1800) {
        return array('legend_class' => 'grey', 'legend_name' => 'No Communication');
    }

    $status = trim((string)$telemetryStatus);
    if ($status === '') {
        return array('legend_class' => 'grey', 'legend_name' => 'No Communication');
    }

    $statusUpper = strtoupper($status);
    $powerVal = dbmgmt_parse_power_value($power);

    if ($statusUpper === 'RUN' || $statusUpper === 'RUNNING' || $statusUpper === 'M/C RUNNING') {
        if ($powerVal !== null && $powerVal <= 0) {
            return array('legend_class' => 'orange', 'legend_name' => 'Null Wind');
        }
        return array('legend_class' => 'green', 'legend_name' => 'WTG Run');
    }
    if ($statusUpper === 'PAUSE' || $statusUpper === 'PAUSED') {
        return array('legend_class' => 'orange', 'legend_name' => 'Null Wind');
    }
    if ($statusUpper === 'ERROR' || $statusUpper === 'ERROR STOP' || $statusUpper === 'ERROR_STOP') {
        return array('legend_class' => 'red', 'legend_name' => 'Error Stop');
    }
    if ($statusUpper === 'GRIDDROP' || $statusUpper === 'GRID DROP') {
        return array('legend_class' => 'blue', 'legend_name' => 'Service');
    }
    if (dbmgmt_status_in_error_category($status, 'Green', $errorMap)) {
        if ($powerVal !== null && $powerVal <= 0) {
            return array('legend_class' => 'orange', 'legend_name' => 'Null Wind');
        }
        return array('legend_class' => 'green', 'legend_name' => 'WTG Run');
    }
    if (dbmgmt_status_in_error_category($status, 'Orange', $errorMap)) {
        return array('legend_class' => 'orange', 'legend_name' => 'Null Wind');
    }
    if (dbmgmt_status_in_error_category($status, 'Blue', $errorMap)) {
        return array('legend_class' => 'blue', 'legend_name' => 'Service');
    }
    if (dbmgmt_status_in_error_category($status, 'Pink', $errorMap)) {
        return array('legend_class' => 'pink', 'legend_name' => 'Impact');
    }

    return array('legend_class' => 'red', 'legend_name' => 'Error Stop');
}

function dbmgmt_device_status_display_label($registerStatus, $liveState, $telemetryStatus) {
    if ((int)$registerStatus !== 1) {
        return 'Stopped';
    }
    if ($telemetryStatus !== null && $telemetryStatus !== '') {
        return $telemetryStatus;
    }
    if ($liveState === 'No data') {
        return 'No data';
    }
    if ($liveState === 'Offline') {
        return 'Offline';
    }
    if ($liveState === 'Online') {
        return 'Online';
    }
    return 'Running';
}

function dbmgmt_build_device_status($db, $device) {
    $registerStatus = dbmgmt_register_status($device);
    $lastSeenEpoch = dbmgmt_device_last_seen_epoch($device);
    $lastDate = isset($device['date_s']) ? trim((string)$device['date_s']) : '';
    $lastTime = isset($device['time_s']) ? trim((string)$device['time_s']) : '';
    $telemetry = dbmgmt_fetch_latest_telemetry_status($db, $device);
    $liveState = dbmgmt_device_live_state($lastSeenEpoch);
    $telemetryStatus = $telemetry ? $telemetry['status_text'] : null;
    $telemetryPower = $telemetry ? $telemetry['power'] : null;
    $legend = dbmgmt_classify_device_legend(
        $registerStatus,
        $lastSeenEpoch,
        $telemetryStatus,
        $telemetryPower,
        dbmgmt_load_error_type_map($db)
    );

    return array(
        'device_index' => (int)$device['Device_Index'],
        'status' => $registerStatus,
        'status_label' => dbmgmt_device_status_display_label($registerStatus, $liveState, $telemetryStatus),
        'legend_class' => $legend['legend_class'],
        'legend_name' => $legend['legend_name'],
        'live_state' => $liveState,
        'last_date' => $lastDate,
        'last_time' => $lastTime,
        'last_update' => trim($lastDate . ' ' . $lastTime),
        'telemetry_status' => $telemetryStatus,
        'telemetry_power' => $telemetryPower,
        'checked_at' => date('Y-m-d H:i:s'),
    );
}

function dbmgmt_get_device_status($db, $deviceIndex) {
    $deviceIndex = (int)$deviceIndex;
    $device = dbmgmt_get_device($db, $deviceIndex);
    if (!$device) {
        return array('ok' => false, 'error' => 'Device not found.');
    }
    return array('ok' => true, 'device_status' => dbmgmt_build_device_status($db, $device));
}

function dbmgmt_list_device_statuses($db) {
    $statuses = array();
    $devices = dbmgmt_list_devices($db);
    foreach ($devices as $device) {
        $fresh = dbmgmt_get_device($db, (int)$device['Device_Index']);
        if ($fresh) {
            $statuses[] = dbmgmt_build_device_status($db, $fresh);
        }
    }
    return $statuses;
}
function dbmgmt_get_device($db, $deviceIndex) {
    $stmt = $db->prepare('SELECT * FROM va_master.device_register WHERE Device_Index = ? LIMIT 1');
    $stmt->bind_param('i', $deviceIndex);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows ? dbmgmt_normalize_device_row($result->fetch_assoc()) : null;
}

function dbmgmt_normalize_phone_field($value) {
    $phone = trim((string)$value);
    if ($phone === '') {
        return null;
    }
    if (!ctype_digit($phone) || strlen($phone) !== 10) {
        return false;
    }
    return $phone;
}

function dbmgmt_update_device($db, $input) {
    $deviceIndex = (int)(isset($input['device_index']) ? $input['device_index'] : 0);
    $device = dbmgmt_get_device($db, $deviceIndex);
    if (!$device) {
        return array('ok' => false, 'error' => 'Device not found.');
    }

    $deviceName = trim(isset($input['device_name']) ? $input['device_name'] : $device['Device_Name']);
    $formatType = (int)(isset($input['format_type']) ? $input['format_type'] : $device['Format_Type']);
    $accountId = (int)(isset($input['account_id']) ? $input['account_id'] : $device['Account_ID']);
    $dbName = dbmgmt_get_account_db_name($db, $accountId);
    if ($dbName === null) {
        $dbName = dbmgmt_sanitize_db_name(isset($input['db_name']) ? $input['db_name'] : $device['db_name']);
    }
    $status = (int)(isset($input['status']) ? $input['status'] : dbmgmt_register_status($device));
    $simNo = trim(isset($input['sim_no']) ? $input['sim_no'] : $device['SIM_No']);
    $state = trim(isset($input['state']) ? $input['state'] : $device['State']);
    $siteLocation = trim(isset($input['site_location']) ? $input['site_location'] : $device['Site_Location']);
    $deviceOrder = (int)(isset($input['device_order']) ? $input['device_order'] : $device['Device_Order']);
    $sfNo = trim(isset($input['sf_no']) ? $input['sf_no'] : $device['SF_No']);
    $phoneNo1 = dbmgmt_normalize_phone_field(isset($input['phone_no_1']) ? $input['phone_no_1'] : $device['Phone_No_1']);
    $phoneNo2 = dbmgmt_normalize_phone_field(isset($input['phone_no_2']) ? $input['phone_no_2'] : $device['Phone_No_2']);
    $phoneNo3 = dbmgmt_normalize_phone_field(isset($input['phone_no_3']) ? $input['phone_no_3'] : $device['Phone_No_3']);
    if ($phoneNo1 === null) { $phoneNo1 = ''; }
    if ($phoneNo2 === null) { $phoneNo2 = ''; }
    if ($phoneNo3 === null) { $phoneNo3 = ''; }

    if ($deviceName === '' || $dbName === null) {
        return array('ok' => false, 'error' => 'Device name and valid database name are required.');
    }
    if ($phoneNo1 === false || $phoneNo2 === false || $phoneNo3 === false) {
        return array('ok' => false, 'error' => 'Phone numbers must be exactly 10 digits (or leave blank).');
    }

    $stmt = $db->prepare("UPDATE va_master.device_register
        SET Device_Name = ?, Format_Type = ?, Account_ID = ?, db_name = ?, Register_Status = ?, SIM_No = ?, State = ?, Site_Location = ?, Device_Order = ?, SF_No = ?, Phone_No_1 = ?, Phone_No_2 = ?, Phone_No_3 = ?
        WHERE Device_Index = ?");
    $stmt->bind_param(
        'siisisssissssi',
        $deviceName,
        $formatType,
        $accountId,
        $dbName,
        $status,
        $simNo,
        $state,
        $siteLocation,
        $deviceOrder,
        $sfNo,
        $phoneNo1,
        $phoneNo2,
        $phoneNo3,
        $deviceIndex
    );

    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => 'Failed to update device: ' . $db->error);
    }

    return array('ok' => true, 'message' => 'Device settings saved successfully.');
}

function dbmgmt_create_device($db, $input) {
    $imei = trim(isset($input['imei']) ? $input['imei'] : '');
    $deviceName = trim(isset($input['device_name']) ? $input['device_name'] : '');
    $formatType = (int)(isset($input['format_type']) ? $input['format_type'] : 1);
    $accountId = (int)(isset($input['account_id']) ? $input['account_id'] : 0);
    $dbName = dbmgmt_get_account_db_name($db, $accountId);
    if ($dbName === null) {
        $dbName = dbmgmt_sanitize_db_name(isset($input['db_name']) ? $input['db_name'] : '');
    }
    $status = (int)(isset($input['status']) ? $input['status'] : 1);
    $simNo = trim(isset($input['sim_no']) ? $input['sim_no'] : '');
    $state = trim(isset($input['state']) ? $input['state'] : '');
    $siteLocation = trim(isset($input['site_location']) ? $input['site_location'] : '');
    $deviceOrder = (int)(isset($input['device_order']) ? $input['device_order'] : 1000);
    $sfNo = trim(isset($input['sf_no']) ? $input['sf_no'] : '');
    $phoneNo1 = dbmgmt_normalize_phone_field(isset($input['phone_no_1']) ? $input['phone_no_1'] : '');
    $phoneNo2 = dbmgmt_normalize_phone_field(isset($input['phone_no_2']) ? $input['phone_no_2'] : '');
    $phoneNo3 = dbmgmt_normalize_phone_field(isset($input['phone_no_3']) ? $input['phone_no_3'] : '');
    if ($phoneNo1 === null) { $phoneNo1 = ''; }
    if ($phoneNo2 === null) { $phoneNo2 = ''; }
    if ($phoneNo3 === null) { $phoneNo3 = ''; }

    if ($imei === '' || !ctype_digit($imei)) {
        return array('ok' => false, 'error' => 'A valid numeric IMEI is required.');
    }
    if ($deviceName === '' || $dbName === null) {
        return array('ok' => false, 'error' => 'Device name and valid database name are required.');
    }
    if ($phoneNo1 === false || $phoneNo2 === false || $phoneNo3 === false) {
        return array('ok' => false, 'error' => 'Phone numbers must be exactly 10 digits (or leave blank).');
    }

    $imeiCheck = dbmgmt_check_imei($db, $imei);
    if (empty($imeiCheck['available'])) {
        return array('ok' => false, 'error' => isset($imeiCheck['error']) ? $imeiCheck['error'] : 'IMEI already registered.');
    }

    $parentId = 0;
    $gmtDrift = '0';
    $powerCurve = 0;
    $pocketLength = 0;

    $stmt = $db->prepare("INSERT INTO va_master.device_register
        (IMEI, Device_Name, Account_ID, Parent_ID, GMT_Drift, Register_Status, Format_Type, Power_Curve, Pocket_Length, SIM_No, State, Site_Location, Device_Order, SF_No, Phone_No_1, Phone_No_2, Phone_No_3, db_name)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        'ssiisiiiisssisssss',
        $imei,
        $deviceName,
        $accountId,
        $parentId,
        $gmtDrift,
        $status,
        $formatType,
        $powerCurve,
        $pocketLength,
        $simNo,
        $state,
        $siteLocation,
        $deviceOrder,
        $sfNo,
        $phoneNo1,
        $phoneNo2,
        $phoneNo3,
        $dbName
    );

    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => 'Failed to create device: ' . $db->error);
    }

    return array('ok' => true, 'message' => 'Device created successfully.', 'device_index' => $stmt->insert_id);
}

function dbmgmt_delete_device($db, $deviceIndex) {
    $device = dbmgmt_get_device($db, $deviceIndex);
    if (!$device) {
        return array('ok' => false, 'error' => 'Device not found.');
    }

    $stmt = $db->prepare('DELETE FROM va_master.device_register WHERE Device_Index = ?');
    $stmt->bind_param('i', $deviceIndex);
    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => 'Failed to remove device: ' . $db->error);
    }

    return array('ok' => true, 'message' => 'Device removed successfully.');
}

function dbmgmt_toggle_device($db, $deviceIndex, $status) {
    $device = dbmgmt_get_device($db, $deviceIndex);
    if (!$device) {
        return array('ok' => false, 'error' => 'Device not found.');
    }

    $status = $status ? 1 : 0;
    $stmt = $db->prepare('UPDATE va_master.device_register SET Register_Status = ? WHERE Device_Index = ?');
    $stmt->bind_param('ii', $status, $deviceIndex);
    if (!$stmt->execute()) {
        return array('ok' => false, 'error' => 'Failed to update device status: ' . $db->error);
    }

    return array(
        'ok' => true,
        'message' => $status ? 'Device started.' : 'Device stopped.',
        'status' => $status,
    );
}

function dbmgmt_format_types($db) {
    $types = array();
    $result = $db->query('SELECT type_id, type_name FROM va_master.device_type_master WHERE is_active = 1 ORDER BY type_id');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $types[] = $row;
        }
    }
    if (empty($types)) {
        for ($i = 1; $i <= 11; $i++) {
            $types[] = array('type_id' => $i, 'type_name' => 'Format ' . $i);
        }
    }
    return $types;
}
