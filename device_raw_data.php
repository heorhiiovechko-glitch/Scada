<?php
include("header_inner.php");
require_once __DIR__ . '/Lib/db_management_service.php';
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

$devices = dbmgmt_list_devices($db);
$databases = array();
foreach ($devices as $d) {
    $dbName = isset($d['db_name']) ? trim((string)$d['db_name']) : '';
    if ($dbName !== '' && !in_array($dbName, $databases, true)) {
        $databases[] = $dbName;
    }
}
sort($databases, SORT_STRING);

$device = dbmgmt_resolve_device_for_raw_data($db, $_GET);
$pageError = null;
$payload = null;

$selectedDb = '';
if ($device && !empty($device['db_name'])) {
    $selectedDb = trim((string)$device['db_name']);
} elseif (!empty($databases)) {
    $selectedDb = $databases[0];
}

if (!$device) {
    $pageError = 'Device not found.';
} else {
    $date = isset($_GET['date']) ? trim($_GET['date']) : dbmgmt_default_raw_data_date($device);
    $payload = dbmgmt_fetch_device_raw_data($db, $device, $date, 100);
    if (!$payload['ok']) {
        $pageError = $payload['error'];
        $payload = null;
    }
}

$devicesJson = array();
foreach ($devices as $d) {
    $devicesJson[] = array(
        'Device_Index' => (int)$d['Device_Index'],
        'Device_Name' => $d['Device_Name'],
        'IMEI' => $d['IMEI'],
        'db_name' => isset($d['db_name']) ? $d['db_name'] : '',
    );
}

$refreshSeconds = 30;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Raw Data View</title>
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
.hero-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.btn{display:inline-flex;align-items:center;gap:8px;min-height:36px;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1px solid transparent;cursor:pointer;transition:transform .15s ease,box-shadow .15s ease}
.btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.08);text-decoration:none}
.btn-primary{background:#e3f2fd;border-color:#90caf9;color:#0d47a1}
.btn-secondary{background:#f4fbf8;border-color:#b7e0d4;color:#0b755c}
.btn-muted{background:#f5f5f5;border-color:#ddd;color:#555}
.summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px}
.summary-card{background:var(--surface);border-radius:12px;padding:16px;box-shadow:var(--shadow)}
.summary-card .label{font-size:12px;color:#666;text-transform:uppercase;letter-spacing:.04em}
.summary-card .value{margin-top:8px;font-size:16px;font-weight:600;word-break:break-word}
.panel{margin-bottom:16px;overflow:hidden}
.device-select-panel{padding:14px 18px}
.device-select-row{display:flex;flex-wrap:wrap;gap:16px 24px;align-items:center}
.device-select-row .field-inline select{min-width:180px}
.device-select-row #device-select{min-width:280px;max-width:100%}
.panel-head{padding:14px 18px;border-bottom:1px solid #e8edf3;font-weight:600;color:#333;display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
.panel-toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.data-table{width:100%;border-collapse:collapse;font-size:14px}
.data-table th,.data-table td{padding:12px 14px;border-bottom:1px solid #eef2f7;text-align:left;vertical-align:middle}
.data-table th{background:#f8fafc;font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#666}
.data-table tr:hover td{background:#fafcff}
.badge{display:inline-block;padding:3px 8px;border-radius:999px;font-size:12px;font-weight:600;background:#e8f5e9;color:#2e7d32}
.badge-off{background:#ffebee;color:#c62828}
.badge-live{background:#e3f2fd;color:#1565c0}
.alert{padding:10px 12px;border-radius:8px;font-size:13px;margin-bottom:12px}
.alert-error{background:#ffebee;color:#b71c1c;border:1px solid #ef9a9a}
.empty{padding:24px;text-align:center;color:#666}
.note{margin-top:16px;padding:14px 16px;border-radius:10px;background:#fff8e1;border:1px solid #ffe082;color:#6d4c00;font-size:13px}
.field label{display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:6px}
.field-inline{display:flex;align-items:center;gap:10px}
.field-inline label{display:inline;margin:0;white-space:nowrap}
.field-inline input,.field-inline select{width:auto;min-width:160px}
.field select,.field input{padding:10px 12px;border:1px solid #d7dee8;border-radius:8px;font-size:14px;background:#fff}
.panel-title{font-weight:600;color:#333}
.source-toggle{display:inline-flex;align-items:center;gap:10px;padding:4px 2px}
.source-toggle-label{font-size:13px;font-weight:600;color:#888;transition:color .2s ease}
.source-toggle-label.is-active.is-database{color:#1565c0}
.source-toggle-label.is-active.is-file{color:#c62828}
.toggle-switch{display:inline-flex;align-items:center;padding:0;border:none;background:transparent;cursor:pointer}
.toggle-switch:focus-visible{outline:2px solid #90caf9;outline-offset:2px;border-radius:999px}
.toggle-track{position:relative;width:46px;height:26px;border-radius:999px;background:#cfd8dc;transition:background .2s ease;flex-shrink:0}
.toggle-thumb{position:absolute;top:3px;left:3px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.2);transition:transform .2s ease}
.toggle-switch.is-database .toggle-track{background:#1565c0}
.toggle-switch.is-file .toggle-track{background:#c62828}
.toggle-switch.is-file .toggle-thumb{transform:translateX(20px)}
.data-table td.data-cell{font-family:Consolas,"Courier New",monospace;font-size:12px;white-space:pre-wrap;word-break:break-word}
@media (max-width:720px){
    .device-select-row{flex-direction:column;align-items:stretch}
    .field-inline{flex-wrap:wrap}
    .device-select-row .field-inline select,.device-select-row #device-select{min-width:100%;width:100%}
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
            <h1 id="hero-title">Raw Data View</h1>
            <p>Live raw telemetry for <?= htmlspecialchars($Firstname . ' ' . $Lastname) ?></p>
        </div>
        <div class="hero-actions">
            <a class="btn btn-secondary" href="database_management.php"><i class="fa-solid fa-database"></i> Database Management</a>
            <a class="btn btn-primary" href="dashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        </div>
    </div>

    <div class="panel device-select-panel">
        <div class="device-select-row">
            <div class="field field-inline">
                <label for="db-select">Database</label>
                <select id="db-select">
                    <?php if (empty($databases)): ?>
                        <option value="">No databases</option>
                    <?php else: foreach ($databases as $dbName): ?>
                        <option value="<?= htmlspecialchars($dbName) ?>"<?= ($selectedDb === $dbName) ? ' selected' : '' ?>><?= htmlspecialchars($dbName) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="field field-inline">
                <label for="device-select">Device</label>
                <select id="device-select">
                    <?php
                    $hasDeviceOption = false;
                    if (!empty($devices) && $selectedDb !== ''):
                        foreach ($devices as $d):
                            if (trim((string)$d['db_name']) !== $selectedDb) {
                                continue;
                            }
                            $hasDeviceOption = true;
                    ?>
                        <option value="<?= (int)$d['Device_Index'] ?>"<?= ($device && (int)$device['Device_Index'] === (int)$d['Device_Index']) ? ' selected' : '' ?>>
                            <?= htmlspecialchars($d['Device_Name'] . ' — ' . $d['IMEI']) ?>
                        </option>
                    <?php
                        endforeach;
                    endif;
                    if (!$hasDeviceOption):
                    ?>
                        <option value="">No devices in this database</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>

    <?php if ($pageError): ?>
        <div class="alert alert-error"><?= htmlspecialchars($pageError) ?></div>
    <?php else: ?>
        <div class="summary-grid" id="summary-grid">
            <div class="summary-card"><div class="label">IMEI</div><div class="value" id="summary-imei"><?= htmlspecialchars($payload['device']['IMEI']) ?></div></div>
            <div class="summary-card"><div class="label">Database</div><div class="value" id="summary-db"><span class="badge"><?= htmlspecialchars($payload['device']['db_name']) ?></span></div></div>
            <div class="summary-card"><div class="label">Format Type</div><div class="value" id="summary-format"><?= (int)$payload['device']['Format_Type'] ?></div></div>
            <div class="summary-card"><div class="label">Records</div><div class="value" id="summary-count"><?= (int)$payload['summary']['record_count'] ?></div></div>
            <div class="summary-card"><div class="label">Latest Update</div><div class="value" id="summary-latest"><?= htmlspecialchars(trim($payload['summary']['latest_date'] . ' ' . $payload['summary']['latest_time'])) ?></div></div>
            <div class="summary-card"><div class="label">Live Status</div><div class="value" id="summary-status"><span class="badge badge-live">Refreshing every <?= (int)$refreshSeconds ?>s</span></div></div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Raw Data</span>
                <div class="panel-toolbar">
                    <div class="field field-inline">
                        <label for="raw-date">Date</label>
                        <input type="date" id="raw-date" value="<?= htmlspecialchars($payload['summary']['date']) ?>">
                    </div>
                    <button type="button" class="btn btn-muted" id="btn-refresh"><i class="fa-solid fa-rotate"></i> Refresh</button>
                    <span class="source-toggle" aria-label="Toggle data source">
                        <span class="source-toggle-label is-active is-database" id="source-label-db">Database</span>
                        <button type="button" class="toggle-switch is-database" id="source-toggle" aria-pressed="false" title="Switch between database and log file data">
                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        </button>
                        <span class="source-toggle-label" id="source-label-file">File Data</span>
                    </span>
                </div>
            </div>
            <div id="raw-source-note" class="note" style="display:none;margin:0;border-radius:0;border-left:0;border-right:0;border-top:0"></div>
            <div id="raw-table-wrap">
                <?php if (empty($payload['rows'])): ?>
                    <div class="empty">No raw data received for this device on the selected date.</div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php foreach ($payload['columns'] as $column): ?>
                                    <th><?= htmlspecialchars($column['label']) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($payload['rows'] as $row): ?>
                            <tr>
                                <td data-label="#"><?= htmlspecialchars($row['Record_Index']) ?></td>
                                <?php foreach ($payload['columns'] as $column): ?>
                                    <td data-label="<?= htmlspecialchars($column['label']) ?>"><?= htmlspecialchars(isset($row[$column['key']]) ? $row[$column['key']] : '') ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="note" id="raw-footer-note">
            <span id="raw-footer-db">Incoming device packets for the selected date are shown newest first. This page refreshes automatically every <?= (int)$refreshSeconds ?> seconds.</span>
            <span id="raw-footer-file" style="display:none">Log file lines are shown newest first with the exact file content in the data column. This page refreshes automatically every <?= (int)$refreshSeconds ?> seconds.</span>
        </div>
    <?php endif; ?>
</div>

<script>
const ALL_DEVICES = <?= json_encode($devicesJson, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const CURRENT_DEVICE_INDEX = <?= ($device ? (int)$device['Device_Index'] : 0) ?>;

function populateDeviceOptions(dbName, selectedIndex) {
    const select = document.getElementById('device-select');
    const filtered = ALL_DEVICES.filter(function(d) { return d.db_name === dbName; });
    select.innerHTML = '';
    if (!filtered.length) {
        select.innerHTML = '<option value="">No devices in this database</option>';
        return;
    }
    filtered.forEach(function(d) {
        const opt = document.createElement('option');
        opt.value = d.Device_Index;
        opt.textContent = d.Device_Name + ' — ' + d.IMEI;
        if (selectedIndex && Number(selectedIndex) === d.Device_Index) {
            opt.selected = true;
        }
        select.appendChild(opt);
    });
    if (!select.value && filtered.length) {
        select.value = String(filtered[0].Device_Index);
    }
}

function navigateToDevice(deviceIndex) {
    if (!deviceIndex) return;
    window.location.href = 'device_raw_data.php?device_index=' + encodeURIComponent(deviceIndex);
}

(function() {
    const dbSelect = document.getElementById('db-select');
    const deviceSelect = document.getElementById('device-select');
    let dbChanging = false;

    populateDeviceOptions(dbSelect.value, CURRENT_DEVICE_INDEX);

    dbSelect.addEventListener('change', function() {
        dbChanging = true;
        populateDeviceOptions(this.value, null);
        navigateToDevice(deviceSelect.value);
    });

    deviceSelect.addEventListener('change', function() {
        if (dbChanging) {
            dbChanging = false;
            return;
        }
        navigateToDevice(this.value);
    });
})();
</script>

<?php if (!$pageError): ?>
<script>
const API = 'database_management_api.php';
let deviceIndex = <?= (int)$payload['device']['Device_Index'] ?>;
const REFRESH_MS = <?= (int)$refreshSeconds * 1000 ?>;
let dataSource = 'database';

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
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

function setDataSource(source) {
    dataSource = source === 'file' ? 'file' : 'database';
    const toggle = document.getElementById('source-toggle');
    const labelDb = document.getElementById('source-label-db');
    const labelFile = document.getElementById('source-label-file');
    const footerDb = document.getElementById('raw-footer-db');
    const footerFile = document.getElementById('raw-footer-file');
    const isFile = dataSource === 'file';
    if (toggle) {
        toggle.classList.toggle('is-file', isFile);
        toggle.classList.toggle('is-database', !isFile);
        toggle.setAttribute('aria-pressed', isFile ? 'true' : 'false');
    }
    if (labelDb) {
        labelDb.classList.toggle('is-active', !isFile);
        labelDb.classList.toggle('is-database', !isFile);
        labelDb.classList.remove('is-file');
    }
    if (labelFile) {
        labelFile.classList.toggle('is-active', isFile);
        labelFile.classList.toggle('is-file', isFile);
        labelFile.classList.remove('is-database');
    }
    if (footerDb) footerDb.style.display = isFile ? 'none' : '';
    if (footerFile) footerFile.style.display = isFile ? '' : 'none';
}

setDataSource('database');

function renderDatabaseTable(payload) {
    const wrap = document.getElementById('raw-table-wrap');
    const note = document.getElementById('raw-source-note');
    if (note) {
        note.style.display = 'none';
        note.textContent = '';
    }
    if (!payload.rows.length) {
        wrap.innerHTML = '<div class="empty">No raw data received for this device on the selected date.</div>';
        return;
    }
    const head = payload.columns.map(c => '<th>' + escapeHtml(c.label) + '</th>').join('');
    const rows = payload.rows.map(row => {
        const cells = payload.columns.map(c => '<td data-label="' + escapeHtml(c.label) + '">' + escapeHtml(row[c.key]) + '</td>').join('');
        return '<tr><td data-label="#">' + escapeHtml(row.Record_Index) + '</td>' + cells + '</tr>';
    }).join('');
    wrap.innerHTML = '<table class="data-table"><thead><tr><th>#</th>' + head + '</tr></thead><tbody>' + rows + '</tbody></table>';
}

function renderFileTable(payload) {
    const wrap = document.getElementById('raw-table-wrap');
    const note = document.getElementById('raw-source-note');
    if (note) {
        if (payload.summary && payload.summary.log_file) {
            note.style.display = 'block';
            note.textContent = 'Log file: ' + payload.summary.log_file;
        } else {
            note.style.display = 'none';
            note.textContent = '';
        }
    }
    if (!payload.rows.length) {
        wrap.innerHTML = '<div class="empty">No log file data found for this device on the selected date.</div>';
        return;
    }
    const rows = payload.rows.map(row => {
        return '<tr><td data-label="#">' + escapeHtml(row.line_no) + '</td><td class="data-cell" data-label="data">' + escapeHtml(row.data) + '</td></tr>';
    }).join('');
    wrap.innerHTML = '<table class="data-table"><thead><tr><th>#</th><th>data</th></tr></thead><tbody>' + rows + '</tbody></table>';
}

function renderTable(payload) {
    if (payload.source === 'file' || dataSource === 'file') {
        renderFileTable(payload);
    } else {
        renderDatabaseTable(payload);
    }
}

function updateSummary(payload) {
    document.getElementById('summary-imei').textContent = payload.device.IMEI;
    document.getElementById('summary-db').innerHTML = '<span class="badge">' + escapeHtml(payload.device.db_name) + '</span>';
    document.getElementById('summary-format').textContent = payload.device.Format_Type;
    document.getElementById('summary-count').textContent = payload.summary.record_count;
    document.getElementById('summary-latest').textContent = (payload.summary.latest_date + ' ' + payload.summary.latest_time).trim();
    const dbSelect = document.getElementById('db-select');
    if (dbSelect && payload.device.db_name && dbSelect.value !== payload.device.db_name) {
        dbSelect.value = payload.device.db_name;
        populateDeviceOptions(payload.device.db_name, payload.device.Device_Index);
    }
}

function loadRawData() {
    deviceIndex = document.getElementById('device-select').value;
    const date = document.getElementById('raw-date').value;
    const action = dataSource === 'file' ? 'get_device_raw_data_file' : 'get_device_raw_data';
    const limit = dataSource === 'file' ? 500 : 100;
    return api(action, { device_index: deviceIndex, date, limit }).then(res => {
        if (!res.ok) throw new Error(res.error || 'Failed to load raw data');
        renderTable(res);
        updateSummary(res);
    }).catch(err => {
        document.getElementById('summary-status').innerHTML = '<span class="badge badge-off">' + escapeHtml(err.message) + '</span>';
    });
}

document.getElementById('source-toggle').addEventListener('click', function() {
    setDataSource(dataSource === 'file' ? 'database' : 'file');
    loadRawData();
});

document.getElementById('btn-refresh').addEventListener('click', loadRawData);
document.getElementById('raw-date').addEventListener('change', loadRawData);
setInterval(loadRawData, REFRESH_MS);
</script>
<?php endif; ?>
</body>
</html>
