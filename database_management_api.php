<?php
require_once __DIR__ . '/Lib/db_management_service.php';

$auth = dbmgmt_require_auth();
$db = $auth['db'];

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$input = $_POST;
if (empty($input) && ($raw = file_get_contents('php://input'))) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = $decoded;
    }
}

switch ($action) {
    case 'list_customers':
        dbmgmt_json_response(array('ok' => true, 'customers' => dbmgmt_list_customers($db)));
        break;

    case 'list_devices':
        dbmgmt_json_response(array('ok' => true, 'devices' => dbmgmt_list_devices($db)));
        break;

    case 'list_customer_options':
        dbmgmt_json_response(array('ok' => true, 'customers' => dbmgmt_list_customer_options($db)));
        break;

    case 'list_format_types':
        dbmgmt_json_response(array('ok' => true, 'format_types' => dbmgmt_format_types($db)));
        break;

    case 'list_user_types':
        dbmgmt_json_response(array('ok' => true, 'user_types' => dbmgmt_list_user_types($db)));
        break;

    case 'get_device_status':
        $deviceIndex = (int)(isset($input['device_index']) ? $input['device_index'] : 0);
        dbmgmt_json_response(dbmgmt_get_device_status($db, $deviceIndex));
        break;

    case 'list_device_statuses':
        dbmgmt_json_response(array('ok' => true, 'statuses' => dbmgmt_list_device_statuses($db)));
        break;

    case 'create_customer':
        dbmgmt_json_response(dbmgmt_create_customer($db, $input));
        break;

    case 'check_username':
        $username = isset($input['username']) ? $input['username'] : '';
        $accountId = (int)(isset($input['account_id']) ? $input['account_id'] : 0);
        dbmgmt_json_response(dbmgmt_check_username($db, $username, $accountId));
        break;

    case 'check_imei':
        $imei = isset($input['imei']) ? $input['imei'] : '';
        $deviceIndex = (int)(isset($input['device_index']) ? $input['device_index'] : 0);
        dbmgmt_json_response(dbmgmt_check_imei($db, $imei, $deviceIndex));
        break;

    case 'update_customer':
        dbmgmt_json_response(dbmgmt_update_customer($db, $input));
        break;

    case 'delete_customer':
        $accountId = (int)(isset($input['account_id']) ? $input['account_id'] : 0);
        dbmgmt_json_response(dbmgmt_delete_customer($db, $accountId));
        break;

    case 'delete_database':
        $dbName = isset($input['db_name']) ? $input['db_name'] : '';
        dbmgmt_json_response(dbmgmt_delete_database($db, $dbName));
        break;

    case 'get_device':
        $deviceIndex = (int)(isset($input['device_index']) ? $input['device_index'] : 0);
        $device = dbmgmt_get_device($db, $deviceIndex);
        if (!$device) {
            dbmgmt_json_response(array('ok' => false, 'error' => 'Device not found.'), 404);
        }
        dbmgmt_json_response(array('ok' => true, 'device' => $device));
        break;

    case 'get_device_raw_data':
        $device = dbmgmt_resolve_device_for_raw_data($db, $input);
        if (!$device) {
            dbmgmt_json_response(array('ok' => false, 'error' => 'Device not found.'), 404);
        }
        $date = isset($input['date']) ? trim($input['date']) : date('Y-m-d');
        $limit = isset($input['limit']) ? (int)$input['limit'] : 100;
        dbmgmt_json_response(dbmgmt_fetch_device_raw_data($db, $device, $date, $limit));
        break;

    case 'get_device_raw_data_file':
        $device = dbmgmt_resolve_device_for_raw_data($db, $input);
        if (!$device) {
            dbmgmt_json_response(array('ok' => false, 'error' => 'Device not found.'), 404);
        }
        $date = isset($input['date']) ? trim($input['date']) : date('Y-m-d');
        $limit = isset($input['limit']) ? (int)$input['limit'] : 500;
        dbmgmt_json_response(dbmgmt_fetch_device_raw_data_file($device, $date, $limit));
        break;

    case 'update_device':
        dbmgmt_json_response(dbmgmt_update_device($db, $input));
        break;

    case 'create_device':
        dbmgmt_json_response(dbmgmt_create_device($db, $input));
        break;

    case 'delete_device':
        $deviceIndex = (int)(isset($input['device_index']) ? $input['device_index'] : 0);
        dbmgmt_json_response(dbmgmt_delete_device($db, $deviceIndex));
        break;

    case 'toggle_device':
        $deviceIndex = (int)(isset($input['device_index']) ? $input['device_index'] : 0);
        $status = !empty($input['status']);
        dbmgmt_json_response(dbmgmt_toggle_device($db, $deviceIndex, $status));
        break;

    default:
        dbmgmt_json_response(array('ok' => false, 'error' => 'Unknown action.'), 400);
}
