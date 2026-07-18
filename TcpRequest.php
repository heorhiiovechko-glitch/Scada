<?php
error_reporting(0);
include("Includes.php");

$Cook_Variable = explode("|", isset($_COOKIE[$Cook_Name]) ? $_COOKIE[$Cook_Name] : "");
if (isset($Cook_Variable)) {
    $Username = isset($Cook_Variable[0]) ? $Cook_Variable[0] : "";
    $User_Type_ID = isset($Cook_Variable[2]) ? $Cook_Variable[2] : "";
    $Account_ID = isset($Cook_Variable[3]) ? $Cook_Variable[3] : "";
}

if (!function_exists('tcp_h')) {
    function tcp_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('tcp_identifier')) {
    function tcp_identifier($value) {
        $value = (string)$value;
        return preg_match('/^[A-Za-z0-9_]+$/', $value) ? $value : '';
    }
}

if (!function_exists('tcp_command_state')) {
    function tcp_command_state($command) {
        $command = strtoupper(trim((string)$command));
        if ($command == 'START') {
            return '0';
        }
        if ($command == 'STOP' || $command == 'EMERG') {
            return '1';
        }
        if ($command == 'RESET') {
            return '4';
        }
        if ($command == 'QUICK') {
            return '2';
        }
        if ($command == 'PAUSE') {
            return '3';
        }
        return null;
    }
}

if (!function_exists('tcp_command_payload')) {
    function tcp_command_payload($command) {
        $command = strtoupper(trim((string)$command));
        if ($command == 'START') {
            return '$CFG<Start>';
        }
        if ($command == 'STOP' || $command == 'EMERG') {
            return '$CFG<Pause>';
        }
        if ($command == 'RESET') {
            return '$CFG<Reset>';
        }
        if ($command == 'QUICK') {
            return '$CFG<Quick>';
        }
        if ($command == 'PAUSE') {
            return '$CFG<Brake>';
        }
        return null;
    }
}

if (!function_exists('tcp_command_from_request')) {
    function tcp_command_from_request() {
        if (isset($_REQUEST['cmd']) && trim($_REQUEST['cmd']) != '') {
            return strtoupper(trim($_REQUEST['cmd']));
        }
        if (isset($_POST['all_button1'])) {
            return 'START';
        }
        if (isset($_POST['all_button2'])) {
            return 'STOP';
        }
        if (isset($_POST['all_button4'])) {
            return 'PAUSE';
        }
        if (isset($_POST['button1'])) {
            return 'START';
        }
        if (isset($_POST['button2'])) {
            return strtoupper(trim($_POST['button2'])) == 'EMERG' ? 'EMERG' : 'STOP';
        }
        if (isset($_POST['button3'])) {
            return 'QUICK';
        }
        if (isset($_POST['button5'])) {
            return 'RESET';
        }
        if (isset($_POST['button4'])) {
            return 'PAUSE';
        }
        return '';
    }
}

if (!function_exists('tcp_is_all_request')) {
    function tcp_is_all_request() {
        return (
            (isset($_REQUEST['all']) && $_REQUEST['all'] == '1') ||
            isset($_POST['all_button1']) ||
            isset($_POST['all_button2']) ||
            isset($_POST['all_button4'])
        );
    }
}

if (!function_exists('tcp_send_json')) {
    function tcp_send_json($ok, $message, $extra) {
        header('Content-Type: application/json');
        echo json_encode(array_merge(array(
            'ok' => $ok,
            'message' => $message
        ), $extra));
        exit();
    }
}

if (!function_exists('tcp_write_command')) {
    function tcp_write_command($db, $database, $imei, $command) {
        $state = tcp_command_state($command);
        if ($state === null) {
            return array(false, 'Invalid command');
        }

        $database = tcp_identifier($database);
        if ($database == '') {
            return array(false, 'Database name missing');
        }

        $imei_sql = $db->real_escape_string($imei);
        $state_sql = $db->real_escape_string($state);
        $currentdate = date("Y-m-d H:i:s");
        $currentdate_sql = $db->real_escape_string($currentdate);

        $count_query = "SELECT Count(*) as tot FROM ".$database.".device_status WHERE IMEI = '".$imei_sql."'";
        if (!($count_result = $db->query($count_query))) {
            return array(false, $db->error);
        }

        $count_row = $count_result->fetch_array();
        $total = isset($count_row['tot']) ? (int)$count_row['tot'] : 0;

        if ($total == 0) {
            $write_query = "INSERT INTO ".$database.".device_status (IMEI, machine_state, machine_state_Z, Timestamp) VALUES ('".$imei_sql."', '".$state_sql."', '".$state_sql."', '".$currentdate_sql."')";
        }
        else {
            $write_query = "UPDATE ".$database.".device_status SET machine_state = '".$state_sql."', machine_state_Z = '".$state_sql."', Timestamp = '".$currentdate_sql."' WHERE IMEI = '".$imei_sql."'";
        }

        if (!($write_result = $db->query($write_query))) {
            return array(false, $db->error);
        }

        $payload = tcp_command_payload($command);
        $message = $payload !== null
            ? ($payload.' queued for TCP client at '.$currentdate.' (sent on next device packet)')
            : (strtoupper($command).' command submitted at '.$currentdate);

        return array(true, $message, $payload);
    }
}

if (!function_exists('tcp_account_device_imeis')) {
    function tcp_account_device_imeis($db, $account_id, $user_type_id) {
        $account_id = trim((string)$account_id);
        $user_type_id = (int)$user_type_id;
        if ($account_id == '') {
            return array(false, 'Account missing', array());
        }

        $account_sql = $db->real_escape_string($account_id);
        if ($user_type_id == 3 || $user_type_id == 2) {
            $device_query = "SELECT IMEI FROM va_master.device_register WHERE Parent_ID = '".$account_sql."' ORDER BY Device_Order ASC, Device_Name ASC";
        }
        elseif ($user_type_id == 4) {
            $device_query = "SELECT IMEI FROM va_master.device_register WHERE Account_ID = '".$account_sql."' ORDER BY Device_Order ASC, Device_Name ASC";
        }
        else {
            return array(false, 'User type not allowed for all turbine command', array());
        }

        if (!($device_result = $db->query($device_query))) {
            return array(false, $db->error, array());
        }

        $imeis = array();
        while ($device_row = $device_result->fetch_array()) {
            if (isset($device_row['IMEI']) && trim($device_row['IMEI']) != '') {
                $imeis[] = trim($device_row['IMEI']);
            }
        }

        if (count($imeis) == 0) {
            return array(false, 'No turbines found for this account', array());
        }

        return array(true, '', $imeis);
    }
}

if (!function_exists('tcp_write_all_commands')) {
    function tcp_write_all_commands($db, $database, $imeis, $command) {
        $success_count = 0;
        $failed_count = 0;
        $last_error = '';

        foreach ($imeis as $imei) {
            $write_result = tcp_write_command($db, $database, $imei, $command);
            if ($write_result[0]) {
                $success_count++;
            }
            else {
                $failed_count++;
                $last_error = $write_result[1];
            }
        }

        if ($failed_count > 0) {
            return array(false, strtoupper($command).' command submitted to '.$success_count.' turbines, '.$failed_count.' failed'.($last_error != '' ? ': '.$last_error : ''), $success_count, $failed_count);
        }

        return array(true, strtoupper($command).' command submitted to all '.$success_count.' turbines', $success_count, 0);
    }
}

$IMEI = isset($_REQUEST['c1']) ? $_REQUEST['c1'] : '';
$Database_Name = isset($_REQUEST['db']) && $_REQUEST['db'] != '' ? $_REQUEST['db'] : (isset($Cook_Variable[7]) ? $Cook_Variable[7] : '');
$IMEI_Decode = base64_decode($IMEI);
$Requested_Command = tcp_command_from_request();
$Command_Result = null;
$All_Command_Count = 0;

if ($Requested_Command != '') {
    if (tcp_is_all_request()) {
        $device_list = tcp_account_device_imeis($db, $Account_ID, $User_Type_ID);
        if ($device_list[0]) {
            $all_result = tcp_write_all_commands($db, $Database_Name, $device_list[2], $Requested_Command);
            $Command_Result = array($all_result[0], $all_result[1]);
            $All_Command_Count = $all_result[2];
        }
        else {
            $Command_Result = array(false, $device_list[1]);
        }
    }
    else {
        $Command_Result = tcp_write_command($db, $Database_Name, $IMEI_Decode, $Requested_Command);
        $All_Command_Count = $Command_Result[0] ? 1 : 0;
    }

    if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == '1') {
        tcp_send_json($Command_Result[0], $Command_Result[1], array(
            'command' => strtoupper($Requested_Command),
            'cfg_payload' => tcp_command_payload($Requested_Command),
            'imei' => $IMEI_Decode,
            'all' => tcp_is_all_request(),
            'count' => $All_Command_Count
        ));
    }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Versatilescada</title>
<style>
body {
    margin: 0;
    padding: 12px;
    background: #f8fafc;
    color: #0f172a;
    font-family: Arial, Helvetica, sans-serif;
}

.tcp-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: flex-end;
}

.button {
    border: none;
    border-radius: 4px;
    color: white;
    padding: 10px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    cursor: pointer;
    min-width: 72px;
}

.button-start { background-color: #16a34a; }
.button-stop { background-color: #dc2626; }
.button-pause { background-color: #f58108; }
.button-reset { background-color: #008CBA; }

.tcp-message {
    margin: 0 0 10px;
    padding: 9px 10px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 700;
}

.tcp-message.ok {
    border: 1px solid #86efac;
    background: #dcfce7;
    color: #166534;
}

.tcp-message.error {
    border: 1px solid #fecaca;
    background: #fee2e2;
    color: #991b1b;
}
</style>
</head>
<body align="right">
<?php if (is_array($Command_Result)) { ?>
    <div class="tcp-message <?= $Command_Result[0] ? 'ok' : 'error' ?>"><?= tcp_h($Command_Result[1]) ?></div>
<?php } ?>
<form method="POST">
    <div class="tcp-actions">
        <input type="submit" name="button1" class="button button-start" value="Start" />
        <input type="submit" name="button2" class="button button-stop" value="Stop" />
        <input type="submit" name="button4" class="button button-pause" value="Pause" />
        <input type="submit" name="button3" class="button button-reset" value="Quick" />
        <input type="submit" name="button5" class="button button-reset" value="Reset" />
        <input type="submit" name="button2" class="button button-stop" value="Emerg" />
        <input type="submit" name="all_button1" class="button button-start" value="Start All" />
        <input type="submit" name="all_button2" class="button button-stop" value="Stop All" />
        <input type="submit" name="all_button4" class="button button-pause" value="Pause All" />
    </div>
</form>
</body>
</html>
