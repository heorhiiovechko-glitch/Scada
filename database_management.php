<?php
include("header_inner.php");
error_reporting(0);

if (empty($_COOKIE[$Cook_Name])) {
    header("Location: index.php");
    exit;
}

$allowedTypes = array('1', '2');
if (!in_array((string)$User_Type_ID, $allowedTypes, true)) {
    header("Location: dashboard.php");
    exit;
}

$databases = array();
$schemaQuery = "SELECT SCHEMA_NAME
    FROM information_schema.SCHEMATA
    WHERE SCHEMA_NAME LIKE 'va\\_%'
    ORDER BY SCHEMA_NAME";
if ($schemaResult = $db->query($schemaQuery)) {
    while ($row = $schemaResult->fetch_assoc()) {
        $schema = $row['SCHEMA_NAME'];
        $escaped = $db->real_escape_string($schema);
        $stats = array('name' => $schema, 'tables' => 0, 'rows' => 0, 'size_mb' => 0, 'devices' => 0);
        $tableStatsQuery = "SELECT COUNT(*) AS table_count,
                COALESCE(SUM(TABLE_ROWS), 0) AS row_count,
                COALESCE(ROUND(SUM(DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2), 0) AS size_mb
            FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$escaped'";
        if ($tableStats = $db->query($tableStatsQuery)) {
            $ts = $tableStats->fetch_assoc();
            $stats['tables'] = (int)$ts['table_count'];
            $stats['rows'] = (int)$ts['row_count'];
            $stats['size_mb'] = (float)$ts['size_mb'];
        }
        $deviceQuery = "SELECT COUNT(*) AS device_count FROM va_master.device_register WHERE db_name = '$escaped'";
        if ($deviceResult = $db->query($deviceQuery)) {
            $stats['devices'] = (int)$deviceResult->fetch_assoc()['device_count'];
        }
        $databases[] = $stats;
    }
}

$totalDevices = 0;
if ($deviceResult = $db->query("SELECT COUNT(*) AS c FROM va_master.device_register")) {
    $totalDevices = (int)$deviceResult->fetch_assoc()['c'];
}
$userCount = 0;
if ($userResult = $db->query("SELECT COUNT(*) AS c FROM va_master.user_master")) {
    $userCount = (int)$userResult->fetch_assoc()['c'];
}
$masterPurpose = array(
    'va_master' => 'Registry, users, alerts, gateway config',
    'va_muthu' => 'PrabaGamesa / Muthu customer telemetry',
    'va_aalayam' => 'Aalayam customer telemetry',
    'va_sangeeth' => 'Sangeeth customer telemetry',
    'va_va_muthu' => 'Vembaiyan customer telemetry',
    'va_windops_test' => 'WindOps test / format 11 sandbox',
    'va_test_master' => 'Test master registry',
    'va_test_cust' => 'Test customer telemetry',
);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Database Management - SCADA</title>
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
.hero-actions,.nav-actions,.panel-toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.btn{display:inline-flex;align-items:center;gap:8px;min-height:36px;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1px solid transparent;cursor:pointer;transition:transform .15s ease,box-shadow .15s ease}
.btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.08);text-decoration:none}
.btn-primary{background:#e3f2fd;border-color:#90caf9;color:#0d47a1}
.btn-secondary{background:#f4fbf8;border-color:#b7e0d4;color:#0b755c}
.btn-accent{background:#fff3e0;border-color:#ffcc80;color:#e65100}
.btn-danger{background:#ffebee;border-color:#ef9a9a;color:#c62828}
.btn-muted{background:#f5f5f5;border-color:#ddd;color:#555}
.btn.active{box-shadow:inset 0 0 0 2px #1565c0}
.summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px}
.summary-card{background:var(--surface);border-radius:12px;padding:16px;box-shadow:var(--shadow)}
.summary-card .label{font-size:12px;color:#666;text-transform:uppercase;letter-spacing:.04em}
.summary-card .value{margin-top:8px;font-size:28px;font-weight:700}
.panel{margin-bottom:16px;overflow:hidden}
.panel-head{padding:14px 18px;border-bottom:1px solid #e8edf3;font-weight:600;color:#333;display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
.section{display:none}
.section.active{display:block}
.data-table{width:100%;border-collapse:collapse;font-size:14px}
.data-table th,.data-table td{padding:12px 14px;border-bottom:1px solid #eef2f7;text-align:left;vertical-align:middle}
.data-table th{background:#f8fafc;font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#666}
.data-table tr:hover td{background:#fafcff}
.data-table tr.clickable{cursor:pointer}
.badge{display:inline-block;padding:3px 8px;border-radius:999px;font-size:12px;font-weight:600;background:#e8f5e9;color:#2e7d32}
.badge-master{background:#e3f2fd;color:#1565c0}
.badge-test{background:#fff3e0;color:#ef6c00}
.badge-on{background:#e8f5e9;color:#2e7d32}
.badge-off{background:#ffebee;color:#c62828}
.badge-live-online{background:#e3f2fd;color:#1565c0}
.badge-live-offline{background:#eceff1;color:#607d8b}
.badge-live-nodata{background:#fff3e0;color:#ef6c00}
.badge-wtg-green{background:#43a047;color:#fff}
.badge-wtg-orange{background:#fb8c00;color:#fff}
.badge-wtg-red{background:#e53935;color:#fff}
.badge-wtg-blue{background:#1e88e5;color:#fff}
.badge-wtg-pink{background:#d81b60;color:#fff}
.badge-wtg-grey{background:#78909c;color:#fff}
.badge-wtg-stopped{background:#ffebee;color:#c62828}
[class*="badge-wtg-"]{padding:5px 12px;border-radius:6px;font-size:12px;letter-spacing:.01em}
.device-status-inline{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.device-status-time{font-size:12px;color:#555;white-space:nowrap;line-height:1.4}
.device-status-time.is-empty{color:#999;font-style:italic}
.row-actions{display:flex;gap:6px;flex-wrap:nowrap;align-items:center}
.row-actions .btn{min-height:32px;min-width:32px;padding:6px 8px;font-size:14px;justify-content:center}
.btn-icon-only span{display:none}
.btn-refresh-device-status.is-loading{opacity:.7;pointer-events:none}
.btn-refresh-device-status.is-loading i{animation:spinRefresh .8s linear infinite}
@keyframes spinRefresh{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
.note{margin-top:16px;padding:14px 16px;border-radius:10px;background:#fff8e1;border:1px solid #ffe082;color:#6d4c00;font-size:13px}
.modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-backdrop.open{display:flex}
.modal{background:#fff;border-radius:14px;width:100%;max-width:560px;max-height:90vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.2)}
.modal-head{padding:16px 18px;border-bottom:1px solid #e8edf3;display:flex;justify-content:space-between;align-items:center}
.modal-head h2{margin:0;font-size:18px;color:#0d47a1}
.modal-body{padding:18px}
.modal-foot{padding:14px 18px;border-top:1px solid #e8edf3;display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-grid .full{grid-column:1 / -1}
.field label{display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:6px}
.field input,.field select,.field textarea{width:100%;padding:10px 12px;border:1px solid #d7dee8;border-radius:8px;font-size:14px}
.toggle-field{display:flex;align-items:center;gap:12px;min-height:42px}
.toggle-switch{display:inline-flex;align-items:center;gap:10px;padding:0;border:none;background:transparent;cursor:pointer;font:inherit;color:inherit}
.toggle-switch:focus-visible{outline:2px solid #90caf9;outline-offset:2px;border-radius:999px}
.toggle-track{position:relative;width:46px;height:26px;border-radius:999px;background:#cfd8dc;transition:background .2s ease;flex-shrink:0}
.toggle-thumb{position:absolute;top:3px;left:3px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.2);transition:transform .2s ease}
.toggle-switch.is-on .toggle-track{background:#43a047}
.toggle-switch.is-on .toggle-thumb{transform:translateX(20px)}
.toggle-label{font-size:14px;font-weight:600;color:#555;min-width:28px}
.toggle-hint{font-size:12px;color:#666;margin-top:4px}
.alert{padding:10px 12px;border-radius:8px;font-size:13px;margin-bottom:12px;display:none}
.alert.show{display:block}
.alert-error{background:#ffebee;color:#b71c1c;border:1px solid #ef9a9a}
.alert-success{background:#e8f5e9;color:#1b5e20;border:1px solid #a5d6a7}
.empty{padding:24px;text-align:center;color:#666}
@media (max-width:720px){
    .form-grid{grid-template-columns:1fr}
    .data-table thead{display:none}
    .data-table tr{display:block;margin-bottom:12px;border-bottom:1px solid #eef2f7}
    .data-table td{display:block;padding:8px 14px}
    .data-table td:before{content:attr(data-label);display:block;font-size:11px;color:#777;text-transform:uppercase;margin-bottom:4px}
}
</style>
</head>
<body>
<div class="page-wrap">
    <div class="hero">
        <div>
            <h1>Database Management</h1>
            <p>Manage SCADA databases, customers, and devices for <?= htmlspecialchars($Firstname . ' ' . $Lastname) ?></p>
        </div>
        <div class="hero-actions">
            <a class="btn btn-secondary" href="dashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        </div>
    </div>

    <div class="panel" style="padding:14px 18px;margin-bottom:16px">
        <div class="nav-actions">
            <button type="button" class="btn btn-muted active" data-section="section-index"><i class="fa-solid fa-database"></i> Database Index</button>
            <button type="button" class="btn btn-secondary" data-section="section-customers"><i class="fa-solid fa-users"></i> View Customer List</button>
            <button type="button" class="btn btn-accent" data-section="section-devices"><i class="fa-solid fa-fan"></i> View Device List</button>
        </div>
    </div>

    <div id="global-alert" class="alert"></div>

    <div id="section-index" class="section active">
        <div class="summary-grid">
            <div class="summary-card"><div class="label">Databases</div><div class="value"><?= count($databases) ?></div></div>
            <div class="summary-card"><div class="label">Registered Devices</div><div class="value"><?= $totalDevices ?></div></div>
            <div class="summary-card"><div class="label">Users</div><div class="value"><?= $userCount ?></div></div>
            <div class="summary-card"><div class="label">Master DB</div><div class="value" style="font-size:18px;">va_master</div></div>
        </div>
        <div class="panel">
            <div class="panel-head">Database Index</div>
            <table class="data-table">
                <thead>
                    <tr><th>Database</th><th>Purpose</th><th>Tables</th><th>Est. Rows</th><th>Size (MB)</th><th>Devices</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php foreach ($databases as $dbInfo):
                    $name = $dbInfo['name'];
                    $purpose = isset($masterPurpose[$name]) ? $masterPurpose[$name] : 'Customer / telemetry database';
                    $badgeClass = 'badge' . ($name === 'va_master' ? ' badge-master' : (strpos($name, 'test') !== false ? ' badge-test' : ''));
                ?>
                    <tr>
                        <td data-label="Database"><span class="<?= $badgeClass ?>"><?= htmlspecialchars($name) ?></span></td>
                        <td data-label="Purpose"><?= htmlspecialchars($purpose) ?></td>
                        <td data-label="Tables"><?= number_format($dbInfo['tables']) ?></td>
                        <td data-label="Est. Rows"><?= number_format($dbInfo['rows']) ?></td>
                        <td data-label="Size (MB)"><?= number_format($dbInfo['size_mb'], 2) ?></td>
                        <td data-label="Devices"><?= number_format($dbInfo['devices']) ?></td>
                        <td data-label="Actions">
                            <?php if ($name !== 'va_master'): ?>
                            <div class="row-actions">
                                <button type="button" class="btn btn-danger btn-icon-only btn-delete-database" data-db="<?= htmlspecialchars($name) ?>" title="Delete database" aria-label="Delete database"><i class="fa-solid fa-trash"></i><span>Delete</span></button>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="section-customers" class="section">
        <div class="panel">
            <div class="panel-head">
                <span>Registered Customers</span>
                <div class="panel-toolbar">
                    <button type="button" class="btn btn-primary" id="btn-add-customer"><i class="fa-solid fa-user-plus"></i> Add Customer</button>
                </div>
            </div>
            <div id="customers-table-wrap"><div class="empty">Loading customers...</div></div>
        </div>
    </div>

    <div id="section-devices" class="section">
        <div class="panel">
            <div class="panel-head">
                <span>Registered Devices</span>
                <div class="panel-toolbar">
                    <button type="button" class="btn btn-primary" id="btn-add-device"><i class="fa-solid fa-plus"></i> Add Device</button>
                </div>
            </div>
            <div id="devices-table-wrap"><div class="empty">Loading devices...</div></div>
        </div>
    </div>

    <div class="note">
        Deleting a customer or database from the index removes linked <strong>user_master</strong> records, devices, and drops the customer database.
        <strong>va_master</strong> cannot be deleted. Device status uses <strong>Status = 1</strong> for running and <strong>Status = 0</strong> for stopped.
    </div>
</div>

<div class="modal-backdrop" id="customer-modal">
    <div class="modal">
        <div class="modal-head">
            <h2 id="customer-modal-title">Add Customer</h2>
            <button type="button" class="btn btn-muted" data-close-modal="customer-modal">Close</button>
        </div>
        <div class="modal-body">
            <div id="customer-modal-alert" class="alert"></div>
            <form id="customer-form" class="form-grid">
                <input type="hidden" name="account_id" id="customer-account-id" value="">
                <div class="field"><label>First Name *</label><input type="text" name="firstname" id="customer-firstname" required></div>
                <div class="field"><label>Last Name</label><input type="text" name="lastname" id="customer-lastname"></div>
                <div class="field"><label>Username *</label><input type="text" name="username" id="customer-username" required autocomplete="off"><div id="customer-username-hint" style="font-size:12px;color:#666;margin-top:4px;"></div></div>
                <div class="field"><label>Password</label><input type="password" name="password" id="customer-password" placeholder="Scada@2026"></div>
                <div class="field"><label>Email</label><input type="email" name="email" id="customer-email"></div>
                <div class="field"><label>Phone</label><input type="text" name="phone" id="customer-phone"></div>
                <div class="field"><label>Role *</label><select name="user_type_id" id="customer-user-type" required></select></div>
                <div class="field full"><label>Database Name *</label><input type="text" name="db_name" id="customer-db-name" placeholder="Auto-generated from username" required readonly></div>
                <div class="field full" id="customer-db-hint" style="grid-column:1/-1;font-size:12px;color:#666;margin-top:-4px;">Database name is created as <strong>va_</strong> + username. It updates when you change the username (including on edit).</div>
            </form>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-muted" data-close-modal="customer-modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="customer-save-btn">Save Customer</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="device-modal">
    <div class="modal">
        <div class="modal-head">
            <h2 id="device-modal-title">Device Settings</h2>
            <button type="button" class="btn btn-muted" data-close-modal="device-modal">Close</button>
        </div>
        <div class="modal-body">
            <div id="device-modal-alert" class="alert"></div>
            <form id="device-form" class="form-grid">
                <input type="hidden" name="device_index" id="device-index" value="">
                <input type="hidden" id="device-mode" value="edit">
                <div class="field full"><label>Device Name *</label><input type="text" name="device_name" id="device-name" required></div>
                <div class="field"><label>IMEI *</label><input type="text" name="imei" id="device-imei" required inputmode="numeric" autocomplete="off"><div id="device-imei-hint" style="font-size:12px;color:#666;margin-top:4px;"></div></div>
                <div class="field"><label>Format Type</label><select name="format_type" id="device-format-type"></select></div>
                <div class="field"><label>Customer Account</label><select name="account_id" id="device-account-id"></select></div>
                <div class="field"><label>Database</label><input type="text" name="db_name" id="device-db-name" placeholder="Auto-set from customer account" required readonly></div><div class="field full" id="device-db-hint" style="grid-column:1/-1;font-size:12px;color:#666;margin-top:-4px;">Database is set automatically from the selected customer account.</div>
                <div class="field"><label>Status</label><select name="status" id="device-status"><option value="1">Running</option><option value="0">Stopped</option></select></div>
                <div class="field"><label>SIM No</label><input type="text" name="sim_no" id="device-sim-no"></div>
                <div class="field"><label>State</label><input type="text" name="state" id="device-state"></div>
                <div class="field"><label>Site Location</label><input type="text" name="site_location" id="device-site-location"></div>
                <div class="field"><label>Device Order</label><input type="number" name="device_order" id="device-order"></div>
                <div class="field"><label>SF No — SMS alerts</label>
                    <div class="toggle-field">
                        <button type="button" class="toggle-switch" id="device-sf-toggle" role="switch" aria-checked="false" aria-labelledby="device-sf-toggle-label">
                            <span class="toggle-track" aria-hidden="true"><span class="toggle-thumb"></span></span>
                            <span class="toggle-label" id="device-sf-toggle-label">Off</span>
                        </button>
                        <input type="hidden" name="sf_no" id="device-sf-no" value="0">
                    </div>
                    <div class="toggle-hint">When on, status changes send SMS to Phone No 1–3.</div>
                </div>
                <div class="field"><label>Phone No 1</label><input type="text" name="phone_no_1" id="device-phone-no-1" maxlength="10" inputmode="numeric" placeholder="10-digit mobile for SMS"></div>
                <div class="field"><label>Phone No 2</label><input type="text" name="phone_no_2" id="device-phone-no-2" maxlength="10" inputmode="numeric" placeholder="Optional"></div>
                <div class="field"><label>Phone No 3</label><input type="text" name="phone_no_3" id="device-phone-no-3" maxlength="10" inputmode="numeric" placeholder="Optional"></div>
                <div class="field full" style="grid-column:1/-1;font-size:12px;color:#666;margin-top:-4px;">Enter 10-digit mobile numbers without +91.</div>
            </form>
        </div>
        <div class="modal-foot" id="device-modal-foot">
            <button type="button" class="btn btn-danger" id="device-stop-btn">Stop Device</button>
            <button type="button" class="btn btn-secondary" id="device-start-btn">Start Device</button>
            <button type="button" class="btn btn-primary" id="device-save-btn">Save Settings</button>
        </div>
    </div>
</div>

<script>
const API = 'database_management_api.php';
let customerOptions = [];
let userTypes = [];
let formatTypes = [];
let deviceStatusTimer = null;
const DEVICE_STATUS_REFRESH_MS = 60000;

function showAlert(el, message, type) {
    el.textContent = message;
    el.className = 'alert show alert-' + type;
}

function hideAlert(el) {
    el.className = 'alert';
    el.textContent = '';
}

function api(action, data) {
    const body = new URLSearchParams(Object.assign({ action }, data || {}));
    return fetch(API, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString(),
        credentials: 'same-origin'
    }).then(r => r.json());
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
}

function switchSection(sectionId) {
    document.querySelectorAll('.section').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('[data-section]').forEach(btn => btn.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    document.querySelector('[data-section="' + sectionId + '"]').classList.add('active');
    if (sectionId === 'section-customers') { ensureCustomerMetaLoaded(() => {}); loadCustomers(); }
    if (sectionId === 'section-devices') {
        loadDevices();
    } else {
        stopDeviceStatusAutoRefresh();
    }
}

function renderCustomers(customers) {
    const wrap = document.getElementById('customers-table-wrap');
    if (!customers.length) {
        wrap.innerHTML = '<div class="empty">No customers registered yet.</div>';
        return;
    }
    let rows = customers.map(c => `
        <tr>
            <td data-label="ID">${escapeHtml(c.Account_ID)}</td>
            <td data-label="Name">${escapeHtml(c.Firstname + ' ' + c.Lastname)}</td>
            <td data-label="Username">${escapeHtml(c.Username)}</td>
            <td data-label="Password">${escapeHtml(c.Password || '')}</td>
            <td data-label="Database"><span class="badge">${escapeHtml(c.Db_Name)}</span></td>
            <td data-label="Role">${escapeHtml(c.User_Type || c.User_Type_ID)}</td>
            <td data-label="Email">${escapeHtml(c.E_Mail)}</td>
            <td data-label="Devices">${escapeHtml(c.device_count)}</td>
            <td data-label="Actions">
                <div class="row-actions">
                    <button type="button" class="btn btn-secondary btn-icon-only btn-edit-customer" data-id="${c.Account_ID}" title="Edit customer" aria-label="Edit customer"><i class="fa-solid fa-pen"></i><span>Edit</span></button>
                    <button type="button" class="btn btn-danger btn-icon-only btn-delete-customer" data-id="${c.Account_ID}" data-name="${escapeHtml(c.Firstname + ' ' + c.Lastname)}" data-db="${escapeHtml(c.Db_Name)}" title="Delete customer" aria-label="Delete customer"><i class="fa-solid fa-trash"></i><span>Delete</span></button>
                </div>
            </td>
        </tr>
    `).join('');
    wrap.innerHTML = `<table class="data-table"><thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Password</th><th>Database</th><th>Role</th><th>Email</th><th>Devices</th><th>Actions</th></tr></thead><tbody>${rows}</tbody></table>`;
}


function liveStateBadgeClass(liveState) {
    if (liveState === 'Online') return 'badge-live-online';
    if (liveState === 'Offline') return 'badge-live-offline';
    return 'badge-live-nodata';
}

function deviceStatusBadgeClass(status) {
    const legend = String(status.legend_class || '').toLowerCase();
    const map = {
        green: 'badge-wtg-green',
        orange: 'badge-wtg-orange',
        red: 'badge-wtg-red',
        blue: 'badge-wtg-blue',
        pink: 'badge-wtg-pink',
        grey: 'badge-wtg-grey',
        stopped: 'badge-wtg-stopped'
    };
    if (map[legend]) {
        return map[legend];
    }
    if (Number(status.status) !== 1) {
        return 'badge-wtg-stopped';
    }
    return 'badge-wtg-grey';
}

function formatLastSubmission(status) {
    const stamp = String(status.last_update || '').trim();
    if (stamp) {
        return stamp;
    }
    const datePart = String(status.last_date || '').trim();
    const timePart = String(status.last_time || '').trim();
    if (datePart || timePart) {
        return (datePart + ' ' + timePart).trim();
    }
    return '';
}

function deviceStatusLabel(status) {
    if (status.legend_name) {
        return status.legend_name;
    }
    return status.status_label
        || status.telemetry_status
        || (Number(status.status) === 1 ? 'Running' : 'Stopped');
}

function applyDeviceStatus(status) {
    const badge = document.getElementById('device-status-badge-' + status.device_index);
    const timeEl = document.getElementById('device-status-time-' + status.device_index);
    if (!badge || !timeEl) return;

    const label = deviceStatusLabel(status);
    const lastSubmission = formatLastSubmission(status);
    const telemetry = String(status.telemetry_status || status.status_label || '').trim();

    badge.className = 'badge ' + deviceStatusBadgeClass(status);
    badge.textContent = label;
    if (telemetry && telemetry !== label) {
        badge.title = telemetry;
    } else {
        badge.removeAttribute('title');
    }

    if (lastSubmission) {
        timeEl.textContent = lastSubmission;
        timeEl.classList.remove('is-empty');
        timeEl.title = 'Most recent submission';
    } else {
        timeEl.textContent = 'No submission yet';
        timeEl.classList.add('is-empty');
        timeEl.removeAttribute('title');
    }
}

function refreshDeviceStatus(deviceIndex, button) {
    if (button) {
        button.classList.add('is-loading');
    }
    return api('get_device_status', { device_index: deviceIndex }).then(res => {
        if (!res.ok) throw new Error(res.error || 'Failed to refresh device status');
        applyDeviceStatus(res.device_status);
        return res.device_status;
    }).catch(err => {
        const timeEl = document.getElementById('device-status-time-' + deviceIndex);
        if (timeEl) {
            timeEl.textContent = err.message;
            timeEl.classList.add('is-empty');
        }
    }).finally(() => {
        if (button) button.classList.remove('is-loading');
    });
}

function refreshAllDeviceStatuses() {
    const section = document.getElementById('section-devices');
    if (!section || !section.classList.contains('active')) return;
    return api('list_device_statuses').then(res => {
        if (!res.ok) throw new Error(res.error || 'Failed to refresh device statuses');
        (res.statuses || []).forEach(applyDeviceStatus);
    }).catch(err => {
        showAlert(document.getElementById('global-alert'), err.message, 'error');
    });
}

function startDeviceStatusAutoRefresh() {
    stopDeviceStatusAutoRefresh();
    refreshAllDeviceStatuses();
    deviceStatusTimer = setInterval(refreshAllDeviceStatuses, DEVICE_STATUS_REFRESH_MS);
}

function stopDeviceStatusAutoRefresh() {
    if (deviceStatusTimer) {
        clearInterval(deviceStatusTimer);
        deviceStatusTimer = null;
    }
}
function renderDevices(devices) {
    const wrap = document.getElementById('devices-table-wrap');
    if (!devices.length) {
        wrap.innerHTML = '<div class="empty">No devices registered.</div>';
        return;
    }
    let rows = devices.map(d => `
        <tr class="clickable" data-device-index="${d.Device_Index}" data-view-url="${escapeHtml(d.raw_data_url || '')}" title="Open live raw data">
            <td data-label="Device">${escapeHtml(d.Device_Name)}</td>
            <td data-label="IMEI">${escapeHtml(d.IMEI)}</td>
            <td data-label="Format">${escapeHtml(d.Format_Type)}</td>
            <td data-label="Database">${escapeHtml(d.db_name)}</td>
            <td data-label="Customer">${escapeHtml((d.Firstname || '') + ' ' + (d.Lastname || ''))}</td>
            <td data-label="Status" class="device-status-cell" data-device-index="${d.Device_Index}">
                <div class="device-status-inline">
                    <span class="badge badge-wtg-grey" id="device-status-badge-${d.Device_Index}">Loading...</span>
                    <span class="device-status-time is-empty" id="device-status-time-${d.Device_Index}">Checking...</span>
                </div>
            </td>
            <td data-label="Actions">
                <div class="row-actions">
                    <button type="button" class="btn btn-secondary btn-icon-only btn-edit-device" data-id="${d.Device_Index}" title="Edit device" aria-label="Edit device"><i class="fa-solid fa-pen"></i><span>Edit</span></button>
                    <button type="button" class="btn btn-danger btn-icon-only btn-remove-device" data-id="${d.Device_Index}" data-name="${escapeHtml(d.Device_Name)}" data-imei="${escapeHtml(d.IMEI)}" title="Remove device" aria-label="Remove device"><i class="fa-solid fa-trash"></i><span>Remove</span></button>
                    <button type="button" class="btn btn-muted btn-icon-only btn-refresh-device-status" data-id="${d.Device_Index}" title="Refresh status" aria-label="Refresh status"><i class="fa-solid fa-rotate"></i><span>Refresh</span></button>
                </div>
            </td>
        </tr>
    `).join('');
    wrap.innerHTML = `<table class="data-table"><thead><tr><th>Device</th><th>IMEI</th><th>Format</th><th>Database</th><th>Customer</th><th>Status</th><th>Actions</th></tr></thead><tbody>${rows}</tbody></table>`;
}

function loadCustomers() {
    api('list_customers').then(res => {
        if (!res.ok) throw new Error(res.error || 'Failed to load customers');
        renderCustomers(res.customers || []);
    }).catch(err => {
        document.getElementById('customers-table-wrap').innerHTML = '<div class="empty">' + escapeHtml(err.message) + '</div>';
    });
}

function loadDevices() {
    Promise.all([
        api('list_devices'),
        api('list_customer_options'),
        api('list_format_types')
    ]).then(([devicesRes, customersRes, formatsRes]) => {
        if (!devicesRes.ok) throw new Error(devicesRes.error || 'Failed to load devices');
        customerOptions = customersRes.customers || [];
        formatTypes = formatsRes.format_types || [];
        renderDevices(devicesRes.devices || []);
        startDeviceStatusAutoRefresh();
    }).catch(err => {
        document.getElementById('devices-table-wrap').innerHTML = '<div class="empty">' + escapeHtml(err.message) + '</div>';
    });
}

function openModal(id) {
    document.getElementById(id).classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

function customerDbFromUsername(username) {
    const slug = String(username || '').trim().toLowerCase().replace(/[^a-z0-9_]+/g, '_').replace(/_+/g, '_').replace(/^_|_$/g, '');
    return slug ? ('va_' + slug) : '';
}

function syncCustomerDbFromUsername() {
    document.getElementById('customer-db-name').value = customerDbFromUsername(document.getElementById('customer-username').value);
}


function ensureCustomerMetaLoaded(callback) {
    if (userTypes.length) {
        callback();
        return;
    }
    api('list_user_types').then(res => {
        if (!res.ok) throw new Error(res.error || 'Failed to load roles');
        userTypes = res.user_types || [];
        callback();
    }).catch(err => showAlert(document.getElementById('global-alert'), err.message, 'error'));
}

function fillUserTypeSelect(select, selectedValue) {
    if (!userTypes.length) {
        select.innerHTML = '<option value="4">Customer</option>';
        return;
    }
    fillSelect(select, userTypes, selectedValue || 4, 'User_Type_ID', opt => opt.User_Type + (opt.User_Type_Description ? ' - ' + opt.User_Type_Description : ''));
}
function openCustomerModal(customer) {
    hideAlert(document.getElementById('customer-modal-alert'));
    const isAdd = !customer;
    document.getElementById('customer-modal-title').textContent = isAdd ? 'Add Customer' : 'Edit Customer';
    document.getElementById('customer-account-id').value = customer ? customer.Account_ID : '';
    document.getElementById('customer-firstname').value = customer ? customer.Firstname : '';
    document.getElementById('customer-lastname').value = customer ? customer.Lastname : '';
    document.getElementById('customer-username').value = customer ? customer.Username : '';
    document.getElementById('customer-password').value = '';
    document.getElementById('customer-email').value = customer ? customer.E_Mail : '';
    document.getElementById('customer-phone').value = customer ? customer.Phone : '';
    fillUserTypeSelect(document.getElementById('customer-user-type'), customer ? customer.User_Type_ID : 4);
    document.getElementById('customer-db-name').readOnly = true;
    document.getElementById('customer-db-hint').style.display = 'block';
    document.getElementById('customer-username-hint').textContent = '';
    syncCustomerDbFromUsername();
    if (customer) {
        scheduleUsernameAvailabilityCheck();
    }
    openModal('customer-modal');
}

function fillSelect(select, options, selectedValue, valueKey, labelBuilder) {
    select.innerHTML = options.map(opt => {
        const value = opt[valueKey];
        const label = labelBuilder(opt);
        const selected = String(value) === String(selectedValue) ? ' selected' : '';
        return `<option value="${escapeHtml(value)}"${selected}>${escapeHtml(label)}</option>`;
    }).join('');
}

function fillCustomerAccountSelect(select, options, selectedValue) {
    if (!options.length) {
        select.innerHTML = '<option value="">No customers available</option>';
        return;
    }
    select.innerHTML = options.map(opt => {
        const selected = String(opt.Account_ID) === String(selectedValue) ? ' selected' : '';
        return `<option value="${escapeHtml(opt.Account_ID)}" data-db-name="${escapeHtml(opt.Db_Name || '')}"${selected}>${escapeHtml(opt.Firstname + ' ' + opt.Lastname + ' (' + opt.Db_Name + ')')}</option>`;
    }).join('');
}

function setDeviceSfToggle(value) {
    const on = value === true || value === 1 || value === '1';
    document.getElementById('device-sf-no').value = on ? '1' : '0';
    const toggle = document.getElementById('device-sf-toggle');
    toggle.classList.toggle('is-on', on);
    toggle.setAttribute('aria-checked', on ? 'true' : 'false');
    document.getElementById('device-sf-toggle-label').textContent = on ? 'On' : 'Off';
}

function openDeviceModal(device, mode) {
    hideAlert(document.getElementById('device-modal-alert'));
    const isAdd = mode === 'add' || !device;
    document.getElementById('device-mode').value = isAdd ? 'add' : 'edit';
    document.getElementById('device-modal-title').textContent = isAdd ? 'Add Device' : ('Device Settings - ' + device.Device_Name);
    document.getElementById('device-index').value = isAdd ? '' : device.Device_Index;
    document.getElementById('device-name').value = isAdd ? '' : (device.Device_Name || '');
    document.getElementById('device-imei').value = isAdd ? '' : (device.IMEI || '');
    document.getElementById('device-imei').readOnly = !isAdd;
    const imeiHint = document.getElementById('device-imei-hint');
    if (imeiHint) {
        imeiHint.textContent = isAdd ? '' : 'IMEI cannot be changed after registration.';
        imeiHint.style.color = '#666';
    }
    if (isAdd) {
        scheduleDeviceImeiCheck();
    }
    document.getElementById('device-sim-no').value = isAdd ? '' : (device.SIM_No || '');
    document.getElementById('device-state').value = isAdd ? '' : (device.State || '');
    document.getElementById('device-site-location').value = isAdd ? '' : (device.Site_Location || '');
    document.getElementById('device-order').value = isAdd ? 1000 : (device.Device_Order || 1000);
    setDeviceSfToggle(isAdd ? false : device.SF_No);
    document.getElementById('device-phone-no-1').value = isAdd ? '' : (device.Phone_No_1 || '');
    document.getElementById('device-phone-no-2').value = isAdd ? '' : (device.Phone_No_2 || '');
    document.getElementById('device-phone-no-3').value = isAdd ? '' : (device.Phone_No_3 || '');
    document.getElementById('device-status').value = isAdd ? '1' : String(device.Status == 1 ? 1 : 0);
    fillSelect(document.getElementById('device-format-type'), formatTypes, isAdd ? 1 : device.Format_Type, 'type_id', opt => 'Format ' + opt.type_id + ' - ' + opt.type_name);
    fillCustomerAccountSelect(document.getElementById('device-account-id'), customerOptions, isAdd ? (customerOptions[0] ? customerOptions[0].Account_ID : '') : device.Account_ID);
    syncDeviceDbFromAccount();
    document.getElementById('device-start-btn').style.display = isAdd ? 'none' : '';
    document.getElementById('device-stop-btn').style.display = isAdd ? 'none' : '';
    document.getElementById('device-save-btn').textContent = isAdd ? 'Create Device' : 'Save Settings';
    openModal('device-modal');
}

function syncDeviceDbFromAccount() {
    const select = document.getElementById('device-account-id');
    const option = select.selectedOptions[0];
    const dbField = document.getElementById('device-db-name');
    if (option && option.dataset.dbName) {
        dbField.value = option.dataset.dbName;
    } else {
        dbField.value = '';
    }
}

function loadDeviceForEdit(deviceIndex) {
    api('get_device', { device_index: deviceIndex }).then(res => {
        if (!res.ok) throw new Error(res.error || 'Failed to load device');
        openDeviceModal(res.device, 'edit');
    }).catch(err => showAlert(document.getElementById('global-alert'), err.message, 'error'));
}

function ensureDeviceMetaLoaded(callback) {
    if (formatTypes.length && customerOptions.length) {
        callback();
        return;
    }
    Promise.all([api('list_customer_options'), api('list_format_types')]).then(([customersRes, formatsRes]) => {
        customerOptions = customersRes.customers || [];
        formatTypes = formatsRes.format_types || [];
        callback();
    }).catch(err => showAlert(document.getElementById('global-alert'), err.message, 'error'));
}

document.querySelectorAll('[data-section]').forEach(btn => {
    btn.addEventListener('click', () => switchSection(btn.getAttribute('data-section')));
});

document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', () => closeModal(btn.getAttribute('data-close-modal')));
});

document.getElementById('btn-add-customer').addEventListener('click', () => ensureCustomerMetaLoaded(() => openCustomerModal(null)));
document.getElementById('customer-username').addEventListener('input', () => {
    syncCustomerDbFromUsername();
    scheduleUsernameAvailabilityCheck();
});

let usernameCheckTimer = null;
function scheduleUsernameAvailabilityCheck() {
    clearTimeout(usernameCheckTimer);
    usernameCheckTimer = setTimeout(checkCustomerUsernameAvailable, 300);
}

function checkCustomerUsernameAvailable() {
    const hint = document.getElementById('customer-username-hint');
    const username = document.getElementById('customer-username').value.trim();
    const accountId = document.getElementById('customer-account-id').value;
    if (!username) {
        hint.textContent = '';
        hint.style.color = '#666';
        return Promise.resolve(true);
    }
    return api('check_username', { username, account_id: accountId }).then(res => {
        if (!res.ok) throw new Error(res.error || 'Username check failed');
        if (res.available) {
            hint.textContent = 'Username is available.';
            hint.style.color = '#2e7d32';
            return true;
        }
        hint.textContent = res.error || 'Username already exists.';
        hint.style.color = '#c62828';
        return false;
    }).catch(err => {
        hint.textContent = err.message;
        hint.style.color = '#c62828';
        return false;
    });
}

document.getElementById('customer-save-btn').addEventListener('click', () => {
    const alertEl = document.getElementById('customer-modal-alert');
    hideAlert(alertEl);
    const accountId = document.getElementById('customer-account-id').value;
    const payload = {
        firstname: document.getElementById('customer-firstname').value.trim(),
        lastname: document.getElementById('customer-lastname').value.trim(),
        username: document.getElementById('customer-username').value.trim(),
        password: document.getElementById('customer-password').value.trim(),
        email: document.getElementById('customer-email').value.trim(),
        phone: document.getElementById('customer-phone').value.trim(),
        db_name: document.getElementById('customer-db-name').value.trim(),
        user_type_id: document.getElementById('customer-user-type').value
    };
    const action = accountId ? 'update_customer' : 'create_customer';
    if (accountId) payload.account_id = accountId;
    checkCustomerUsernameAvailable().then(available => {
        if (!available) {
            showAlert(alertEl, 'Username already exists. Choose a different username.', 'error');
            return;
        }
        return api(action, payload).then(res => {
            if (!res.ok) throw new Error(res.error || 'Save failed');
            closeModal('customer-modal');
            showAlert(document.getElementById('global-alert'), res.message, 'success');
            loadCustomers();
        });
    }).catch(err => showAlert(alertEl, err.message, 'error'));
});

document.getElementById('btn-add-device').addEventListener('click', () => {
    ensureDeviceMetaLoaded(() => openDeviceModal(null, 'add'));
});

let imeiCheckTimer = null;
function scheduleDeviceImeiCheck() {
    clearTimeout(imeiCheckTimer);
    imeiCheckTimer = setTimeout(checkDeviceImeiAvailable, 300);
}

function checkDeviceImeiAvailable() {
    const hint = document.getElementById('device-imei-hint');
    const mode = document.getElementById('device-mode').value;
    const imei = document.getElementById('device-imei').value.trim();
    const deviceIndex = document.getElementById('device-index').value;
    if (mode !== 'add') {
        if (hint) {
            hint.textContent = 'IMEI cannot be changed after registration.';
            hint.style.color = '#666';
        }
        return Promise.resolve(true);
    }
    if (!imei) {
        if (hint) {
            hint.textContent = '';
            hint.style.color = '#666';
        }
        return Promise.resolve(true);
    }
    return api('check_imei', { imei, device_index: deviceIndex }).then(res => {
        if (!res.ok) throw new Error(res.error || 'IMEI check failed');
        if (res.available) {
            hint.textContent = 'IMEI is available.';
            hint.style.color = '#2e7d32';
            return true;
        }
        hint.textContent = res.error || 'IMEI already registered.';
        hint.style.color = '#c62828';
        return false;
    }).catch(err => {
        hint.textContent = err.message;
        hint.style.color = '#c62828';
        return false;
    });
}

document.getElementById('device-imei').addEventListener('input', () => {
    if (document.getElementById('device-mode').value === 'add') {
        scheduleDeviceImeiCheck();
    }
});

document.querySelector('#section-index .data-table tbody').addEventListener('click', e => {
    const deleteBtn = e.target.closest('.btn-delete-database');
    if (!deleteBtn) return;
    const dbName = deleteBtn.dataset.db;
    if (!confirm('Delete database "' + dbName + '" and remove all linked customers and devices? This cannot be undone.')) return;
    api('delete_database', { db_name: dbName }).then(res => {
        if (!res.ok) throw new Error(res.error || 'Delete failed');
        showAlert(document.getElementById('global-alert'), res.message, 'success');
        window.location.reload();
    }).catch(err => showAlert(document.getElementById('global-alert'), err.message, 'error'));
});
document.getElementById('device-account-id').addEventListener('change', syncDeviceDbFromAccount);

document.getElementById('customers-table-wrap').addEventListener('click', e => {
    const editBtn = e.target.closest('.btn-edit-customer');
    const deleteBtn = e.target.closest('.btn-delete-customer');
    if (editBtn) {
        api('list_customers').then(res => {
            const customer = (res.customers || []).find(c => String(c.Account_ID) === editBtn.dataset.id);
            if (customer) ensureCustomerMetaLoaded(() => openCustomerModal(customer));
        });
    }
    if (deleteBtn) {
        const name = deleteBtn.dataset.name;
        const dbName = deleteBtn.dataset.db;
        if (!confirm('Delete customer "' + name + '" and drop database "' + dbName + '"? This cannot be undone.')) return;
        api('delete_customer', { account_id: deleteBtn.dataset.id }).then(res => {
            if (!res.ok) throw new Error(res.error || 'Delete failed');
            showAlert(document.getElementById('global-alert'), res.message, 'success');
            loadCustomers();
        }).catch(err => showAlert(document.getElementById('global-alert'), err.message, 'error'));
    }
});

document.getElementById('devices-table-wrap').addEventListener('click', e => {
    const refreshBtn = e.target.closest('.btn-refresh-device-status');
    if (refreshBtn) {
        e.stopPropagation();
        refreshDeviceStatus(refreshBtn.dataset.id, refreshBtn);
        return;
    }
    const editBtn = e.target.closest('.btn-edit-device');
    const removeBtn = e.target.closest('.btn-remove-device');
    if (editBtn) {
        e.stopPropagation();
        ensureDeviceMetaLoaded(() => loadDeviceForEdit(editBtn.dataset.id));
        return;
    }
    if (removeBtn) {
        e.stopPropagation();
        const name = removeBtn.dataset.name;
        const imei = removeBtn.dataset.imei;
        if (!confirm('Remove device "' + name + '" (IMEI ' + imei + ')?')) return;
        api('delete_device', { device_index: removeBtn.dataset.id }).then(res => {
            if (!res.ok) throw new Error(res.error || 'Remove failed');
            showAlert(document.getElementById('global-alert'), res.message, 'success');
            loadDevices();
        }).catch(err => showAlert(document.getElementById('global-alert'), err.message, 'error'));
        return;
    }
    const row = e.target.closest('tr[data-view-url]');
    if (row && row.dataset.viewUrl) {
        window.open(row.dataset.viewUrl, '_blank');
    }
});

function saveDevice() {
    const alertEl = document.getElementById('device-modal-alert');
    hideAlert(alertEl);
    const mode = document.getElementById('device-mode').value;
    const payload = {
        device_name: document.getElementById('device-name').value.trim(),
        format_type: document.getElementById('device-format-type').value,
        account_id: document.getElementById('device-account-id').value,
        db_name: document.getElementById('device-db-name').value.trim(),
        status: document.getElementById('device-status').value,
        sim_no: document.getElementById('device-sim-no').value.trim(),
        state: document.getElementById('device-state').value.trim(),
        site_location: document.getElementById('device-site-location').value.trim(),
        device_order: document.getElementById('device-order').value,
        sf_no: document.getElementById('device-sf-no').value.trim(),
        phone_no_1: document.getElementById('device-phone-no-1').value.trim(),
        phone_no_2: document.getElementById('device-phone-no-2').value.trim(),
        phone_no_3: document.getElementById('device-phone-no-3').value.trim()
    };
    if (mode === 'add') {
        payload.imei = document.getElementById('device-imei').value.trim();
    } else {
        payload.device_index = document.getElementById('device-index').value;
    }
    const action = mode === 'add' ? 'create_device' : 'update_device';
    const submit = () => api(action, payload).then(res => {
        if (!res.ok) throw new Error(res.error || 'Save failed');
        showAlert(document.getElementById('global-alert'), res.message, 'success');
        closeModal('device-modal');
        loadDevices();
    });

    if (mode === 'add') {
        checkDeviceImeiAvailable().then(available => {
            if (!available) {
                showAlert(alertEl, document.getElementById('device-imei-hint').textContent || 'This IMEI is already registered.', 'error');
                return;
            }
            return submit();
        }).catch(err => showAlert(alertEl, err.message, 'error'));
        return;
    }

    submit().catch(err => showAlert(alertEl, err.message, 'error'));
}

document.getElementById('device-sf-toggle').addEventListener('click', () => {
    const hidden = document.getElementById('device-sf-no');
    setDeviceSfToggle(hidden.value !== '1');
});
document.getElementById('device-save-btn').addEventListener('click', saveDevice);
document.getElementById('device-start-btn').addEventListener('click', () => {
    document.getElementById('device-status').value = '1';
    api('toggle_device', { device_index: document.getElementById('device-index').value, status: 1 })
        .then(res => {
            if (!res.ok) throw new Error(res.error || 'Start failed');
            showAlert(document.getElementById('device-modal-alert'), res.message, 'success');
        }).catch(err => showAlert(document.getElementById('device-modal-alert'), err.message, 'error'));
});
document.getElementById('device-stop-btn').addEventListener('click', () => {
    document.getElementById('device-status').value = '0';
    api('toggle_device', { device_index: document.getElementById('device-index').value, status: 0 })
        .then(res => {
            if (!res.ok) throw new Error(res.error || 'Stop failed');
            showAlert(document.getElementById('device-modal-alert'), res.message, 'success');
        }).catch(err => showAlert(document.getElementById('device-modal-alert'), err.message, 'error'));
});
</script>
</body>
</html>
