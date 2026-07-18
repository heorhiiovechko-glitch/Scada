
<?php
error_reporting(0);

include "header_inner.php";
include "gamesa_lut.php";


if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit();
}

$Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
if (isset($Cook_Variable)) {
    $Username = base64_encode($Cook_Variable[0]);
    $Pass = base64_encode($Cook_Variable[8]);
}
?>
<?php
$lastRecd = null;
$IMEI = $_REQUEST["c1"];
if (isset($_REQUEST["l"])) {
    $Pocket_Length = $_REQUEST["l"];
} else {
    $Pocket_Length = "";
}
$IMEI_Decode = base64_decode($IMEI);
$IMEI_SQL = $db->real_escape_string($IMEI_Decode);
$FType = $_REQUEST["FType"];
$Energy_Report_Url = "reports11_ajax.php?c1=" . rawurlencode($IMEI) . "&l=" . rawurlencode($Pocket_Length) . "&FType=" . rawurlencode($FType) . "&p=57";
if (isset($_REQUEST["Db_Name"])) {
    $Database_Name = $_REQUEST["Db_Name"];
} //include("Gen_Export_Month.php"); //include("Gen_Export_Year.php");
if (!isset($Database_Name) || $Database_Name == "") {
    $Database_Name = isset($Cook_Variable[7]) ? $Cook_Variable[7] : "";
}
if ($FType == 7) {
    $Table_Name = "device_data_f7";
    $Error_Table_Name = "error_data_f7";
} elseif ($FType == 8) {
    $Table_Name = "device_data_f8";
    $Error_Table_Name = "error_data_f8";
} elseif ($FType == 11) {
    $Table_Name = "device_data_f11";
    $Error_Table_Name = "error_data_f11";
}
$Mysql_Query = "
    SELECT devicedata, Device_Name
    FROM va_master.device_register
    WHERE IMEI = '" . $IMEI_SQL . "'
      AND status != ''
    LIMIT 1"; //echo $Mysql_Query;
	
//echo $Mysql_Query;
if (!($Mysql_Query_Result = $db->query($Mysql_Query))) {
    die($db->error);
}
$Mysql_Record_Count = $Mysql_Query_Result->num_rows;
if ($Mysql_Query_Result->num_rows >= 1) {
    if ($Fetch_Result = $Mysql_Query_Result->fetch_assoc()) {
        $All_Devicename[1] = $Fetch_Result["Device_Name"];
        $devicedata = explode(",", $Fetch_Result["devicedata"]);
        $Project_Version = $devicedata[4];
        $ID_Number = $devicedata[5];
        $panel1_bv = $devicedata[6]; // RRPM
        $panel2_bv = $devicedata[7]; //GRPM
        $bridge1_dcv = $devicedata[8]; //Wind Speed
        $bridge2_dcv = $devicedata[9]; //Date Stamp
        $bridge1_dca = $devicedata[10]; //Tme Stamp
        $bridge2_dca = $devicedata[11]; //Power
        $panel_dc1kw = $devicedata[12]; //Reactive Power
        $panel_dc2kw = $devicedata[13]; // VRMS Voltage
        $Status = $devicedata[14]; /// Status
        $panel_totalkw = $devicedata[15]; // Cur Line OK Hr
        $r_vol = $devicedata[16]; //Cur Ambt OK Hr
        $y_vol = $devicedata[17]; //Cur Turb OK Hr
        $b_vol = $devicedata[18]; //Prev Line OK Hr
        $r_curr = $devicedata[19]; //Prev Ambt OK Hr
        $y_curr = $devicedata[20]; //Prev Turb OK Hr
        $b_curr = $devicedata[21];
        //Total Produced Energy Mwh
        $freq = $devicedata[22];
        // Total Produible Energy Mwh
        $phase1_kw = $devicedata[23]; // Pressure Bar
        $phase1_kvar = $devicedata[24]; // Pitch Angle
        $phase1_kva = $devicedata[25]; //Frequency
        $phase2_kw = $devicedata[26]; // Energy Produced Current Hour
        $phase2_kvar = $devicedata[27]; // Energy Produced Current Day
        $phase2_kva = $devicedata[28]; //Energy Produced Current Month
        $phase3_kw = $devicedata[29];  //Energy Produced Current Year
        $phase3_kvar = $devicedata[30]; // Energy Produced Previous Hour
        $phase3_kva = $devicedata[31]; // Energy Produced Previous Day
        $bridge11_temp = $devicedata[32]; // Energy Produced Previous Month
        $bridge12_temp = $devicedata[33]; // Energy Produced Previous Year
        $bridge13_temp = $devicedata[34]; //Energy Producible Current Hour
        $bridge21_temp = $devicedata[35]; //Energy Producible Current Day
        $bridge22_temp = $devicedata[36]; //Energy Producible Current Month
        $bridge23_temp = $devicedata[37]; //Energy Producible Current Year
        $cabinet1_temp = $devicedata[38]; //Energy Producible Previous  Hour
        $cabinet2_temp = $devicedata[39]; //Energy Producible Previous Day
        $cabinet3_temp = $devicedata[40]; //Energy Producible Previous Month
        $inductive1_temp = $devicedata[41]; //Energy Producible Previous Year
        $inductive2_temp = $devicedata[42];
        $ambient_temp = $devicedata[43];
        $ccu_temp = $devicedata[44];
        $cooling_input_temp = $devicedata[45];
        $cooling_output_temp = $devicedata[46];
        $active1_energy = $devicedata[47];
        $active2_energy = $devicedata[48];
        $active_total = $devicedata[49];
        $dc1_energy = $devicedata[50];
        $dc2_energy = $devicedata[51];
        $dc_total = $devicedata[52];
        $reactive1_energy = $devicedata[53];
        $reactive2_energy = $devicedata[54];
        $reactive_total = $devicedata[55];
        $capacitive1_energy = $devicedata[56];
        $capacitive2_energy = $devicedata[57];
        $capacitive_total = $devicedata[58];
        $scb1 = $devicedata[59];
        $scb2 = $devicedata[60];
        $scb3 = $devicedata[61];
        $scb4 = $devicedata[62];
        $scb5 = $devicedata[63];
        $scb6 = $devicedata[64];
        $scb7 = $devicedata[65];
        $scb8 = $devicedata[66];
        $scb9 = $devicedata[67];
        $scb10 = $devicedata[68];
        $dummy9 = $devicedata[69];
        $dummy10 = $devicedata[70];
        $dummy11 = $devicedata[71];
        $dummy12 = $devicedata[72];
        $dummy13 = $devicedata[73];
        $dummy14 = $devicedata[74]; // Total Hours
        $dummy15 = $devicedata[75]; // Line  Hours
        $dummy16 = $devicedata[76]; // NoService  Hours
        $dummy17 = $devicedata[77]; // Line Ok  Hours
        $dummy18 = $devicedata[78]; // Ambient Ok  Hours
        $dummy19 = $devicedata[79]; // Turbine Ok Hours
        $dummy20 = $devicedata[80]; // Conn Star
        $dummy21 = $devicedata[81]; // Delta Cnxn
        $dummy22 = $devicedata[82]; // Run
        $dummy23 = $devicedata[83]; // Coupled
        $Date_F = $devicedata[2];
        $Time_F = $devicedata[3]; //$Device_Epoch_Time = GetTimestamp($Date_F,$Time_F);
    } # Removing # symbal
    $Status = str_replace("#", "", $Status);
    $lastRecd = str_replace(".", "-", $Date_F);
    $WindSpeed = str_replace("m/s", "", $WindSpeed);
	
	$StatusCode = intval(str_replace("#", "", $Status));

if (isset($Gamesa_LUT[$StatusCode])) {
    $StatusText = $Gamesa_LUT[$StatusCode];
} else {
    $StatusText = "Unknown Status (" . $StatusCode . ")";
}
}

$StatusCode = intval(str_replace("#", "", $Status));

if (
    stripos($Status, 'RUN') !== false ||
    stripos($Status, 'STOP') !== false ||
    stripos($Status, 'PAUSE') !== false ||
    stripos($Status, 'RESET') !== false
) {
    // Normal status
    $DisplayStatus = $Status;
    $StatusClass = (stripos($Status, 'RUN') !== false) ? 'status-run' : 'status-stop';
} else {
    // Alarm/Error Code
    if (isset($Gamesa_LUT[$StatusCode])) {
        $DisplayStatus = $Gamesa_LUT[$StatusCode];
    } else {
        $DisplayStatus = "Unknown Error ($StatusCode)";
    }

    $StatusClass = 'status-alarm';
}
$No_Records = '<tr>
		<td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
	</tr>';
?> 

<style>
body{
    margin:0;
    background:#0f1418;
    color:#eef6f8;
    font-family:'Segoe UI',Tahoma,sans-serif;
}

#body{
    width:98%;
    max-width:1800px;
    margin:auto;
}

.scada-command-panel{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
    margin:8px 0 12px;
    padding:10px 12px;
    background:linear-gradient(180deg,#182128 0%,#11191f 100%);
    border:1px solid #2a3843;
    border-left:4px solid #22d3ee;
    border-radius:8px;
    box-shadow:0 12px 30px rgba(0,0,0,.25),inset 0 1px 0 rgba(255,255,255,.05);
}

.scada-panel-title{
    min-width:230px;
}

.scada-eyebrow{
    color:#7fd8e8;
    font-size:10px;
    font-weight:800;
    letter-spacing:0;
    text-transform:uppercase;
}

.scada-status-line{
    display:flex;
    align-items:center;
    gap:8px;
    margin-top:3px;
    color:#f8fbfc;
    font-size:18px;
    font-weight:800;
    line-height:1.15;
}

.status-light{
    width:11px;
    height:11px;
    border-radius:50%;
    background:#9aa8b0;
    box-shadow:0 0 0 4px rgba(154,168,176,.12),0 0 12px currentColor;
    flex:0 0 auto;
}

.status-light.status-run{ background:#23c96f; color:#23c96f; }
.status-light.status-stop{ background:#f04438; color:#f04438; }
.status-light.status-alarm{ background:#f5a524; color:#f5a524; }

.scada-meta{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:4px;
    color:#90a3ad;
    font-size:11px;
    font-weight:600;
}

.scada-actions{
    display:flex;
    align-items:center;
    justify-content:flex-end;
    gap:8px;
    flex:1 1 520px;
    flex-wrap:wrap;
}

.scada-command-feedback{
    flex:1 1 100%;
    min-height:18px;
    color:#90a3ad;
    font-size:11px;
    font-weight:700;
    text-align:right;
}

.scada-command-feedback.ok{ color:#23c96f; }
.scada-command-feedback.error{ color:#f04438; }

.kpi-row{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
    gap:8px;
    margin:8px 0;
}

.kpi-card{
    position:relative;
    overflow:hidden;
    min-height:54px;
    padding:8px 10px 8px 12px;
    background:#151d24;
    border:1px solid #26323b;
    border-radius:8px;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.04);
}

.kpi-card::before{
    content:'';
    position:absolute;
    inset:0 auto 0 0;
    width:3px;
    background:#22d3ee;
}

.kpi-title{
    color:#8ea1ac;
    font-size:10px;
    font-weight:600;
    text-transform:uppercase;
}

.kpi-value{
    margin-top:4px;
    color:#f6fbfc;
    font-size:17px;
    font-weight:700;
    overflow-wrap:anywhere;
    line-height:1.2;
}

.status-run{ color:#23c96f; }
.status-stop{ color:#f04438; }
.status-alarm{ color:#f5a524; }

.command-btn{
    min-height:40px;
    border:1px solid #34434d;
    border-radius:8px;
    padding:7px 10px 7px 8px;
    color:#edf7f9;
    font-size:12px;
    font-weight:700;
    cursor:pointer;
    background:linear-gradient(180deg,#1b252c 0%,#121a20 100%);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.06),0 6px 16px rgba(0,0,0,.18);
    transition:transform .16s ease,border-color .16s ease,background .16s ease;
}

.command-btn:hover{
    transform:translateY(-1px);
    border-color:#5b7280;
    background:linear-gradient(180deg,#22303a 0%,#151f26 100%);
}

.command-btn:disabled{
    cursor:wait;
    opacity:.7;
    transform:none;
}

.command-btn:focus-visible,
.close-tcp-btn:focus-visible{
    outline:2px solid #22d3ee;
    outline-offset:2px;
}

.command-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
}

.cmd-icon{
    position:relative;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:25px;
    height:25px;
    border-radius:6px;
    background:rgba(255,255,255,.08);
    color:#fff;
    flex:0 0 25px;
}

.cmd-icon::before,
.cmd-icon::after{
    display:block;
}

.cmd-icon--start::before{
    content:'';
    width:0;
    height:0;
    margin-left:2px;
    border-top:7px solid transparent;
    border-bottom:7px solid transparent;
    border-left:11px solid currentColor;
}



.cmd-icon--emerg::before{
    content:'';
    width:12px;
    height:12px;
    border-radius:2px;
    background:currentColor;
}
.cmd-icon--pause::before,
.cmd-icon--pause::after{
    content:'';
    width:4px;
    height:14px;
    border-radius:2px;
    background:currentColor;
}

.cmd-icon--stop::before{
    content:'';
    width:12px;
    height:12px;
    border-radius:2px;
    background:currentColor;
}

.cmd-icon--pause{
    gap:4px;
}

.cmd-icon--reset::before{
    content:'';
    width:13px;
    height:13px;
    border:2px solid currentColor;
    border-right-color:transparent;
    border-radius:50%;
}

.cmd-icon--reset::after{
    content:'';
    position:absolute;
    right:5px;
    top:5px;
    width:0;
    height:0;
    border-top:4px solid transparent;
    border-bottom:4px solid transparent;
    border-left:6px solid currentColor;
    transform:rotate(-28deg);
}

.cmd-icon--back::before{
    content:'';
    position:absolute;
    left:7px;
    width:10px;
    height:10px;
    border-left:3px solid currentColor;
    border-bottom:3px solid currentColor;
    transform:rotate(45deg);
}

.cmd-icon--back::after{
    content:'';
    width:14px;
    height:3px;
    margin-left:4px;
    border-radius:2px;
    background:currentColor;
}

.cmd-icon--report::before{
    content:'';
    position:absolute;
    left:6px;
    bottom:6px;
    width:3px;
    height:8px;
    border-radius:2px;
    background:currentColor;
    box-shadow:5px -4px 0 currentColor,10px -1px 0 currentColor;
}

.command-btn--start{ border-color:rgba(35,201,111,.45); }
.command-btn--start .cmd-icon{ background:#168b53; }
.command-btn--start:hover{ border-color:#23c96f; }

.command-btn--stop{ border-color:rgba(240,68,56,.5); }
.command-btn--stop .cmd-icon{ background:#b42318; }
.command-btn--stop:hover{ border-color:#f04438; }

command-btn--emerg{ border-color:rgba(240,68,56,.5); }
.command-btn--emerg .cmd-icon{ background:#b42318; }
.command-btn--emerg:hover{ border-color:#f04438; }

.command-btn--pause{ border-color:rgba(245,165,36,.5); }
.command-btn--pause .cmd-icon{ background:#a86207; }
.command-btn--pause:hover{ border-color:#f5a524; }

.command-btn--reset{ border-color:rgba(34,211,238,.5); }
.command-btn--reset .cmd-icon{ background:#16758f; }
.command-btn--reset:hover{ border-color:#22d3ee; }

.command-btn--report{ border-color:rgba(45,212,191,.5); }
.command-btn--report .cmd-icon{ background:#0f8f6f; }
.command-btn--report:hover{ border-color:#2dd4bf; }

.command-btn--back{ border-color:rgba(148,163,184,.5); }
.command-btn--back .cmd-icon{ background:#465865; }
.command-btn--back:hover{ border-color:#cbd5e1; }

.cmd-label{
    line-height:1;
    text-transform:uppercase;
    letter-spacing:0;
}

.tab-content{
    margin-top:12px;
}

.tcp-modal{
    display:none;
    position:fixed;
    inset:0;
    z-index:9999;
    align-items:center;
    justify-content:center;
    padding:18px;
    background:rgba(6,10,12,.76);
    backdrop-filter:blur(3px);
}

.tcp-modal-content{
    width:min(720px,92vw);
    height:min(520px,86vh);
    display:flex;
    flex-direction:column;
    background:#11191f;
    border:1px solid #2f404c;
    border-top:4px solid #22d3ee;
    border-radius:8px;
    overflow:hidden;
    box-shadow:0 28px 80px rgba(0,0,0,.45);
}

.tcp-modal[data-command="start"] .tcp-modal-content{ border-top-color:#23c96f; }
.tcp-modal[data-command="stop"] .tcp-modal-content{ border-top-color:#f04438; }
.tcp-modal[data-command="pause"] .tcp-modal-content{ border-top-color:#f5a524; }
.tcp-modal[data-command="reset"] .tcp-modal-content{ border-top-color:#22d3ee; }

.tcp-modal-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    min-height:54px;
    padding:10px 14px;
    background:#182128;
    border-bottom:1px solid #2a3843;
}

.tcp-modal-kicker{
    color:#7fd8e8;
    font-size:10px;
    font-weight:800;
    text-transform:uppercase;
}

.tcp-modal-title{
    color:#f8fbfc;
    font-size:16px;
    font-weight:800;
    line-height:1.2;
}

.tcp-modal-content iframe{
    width:100%;
    height:100%;
    flex:1 1 auto;
    border:0;
    background:#fff;
}

.close-tcp-btn{
    width:34px;
    height:34px;
    border:1px solid #3c4d58;
    border-radius:8px;
    color:#edf7f9;
    background:#11191f;
    font-size:24px;
    line-height:30px;
    cursor:pointer;
}

.close-tcp-btn:hover{
    border-color:#f04438;
    color:#fff;
    background:#7f1d1d;
}

@media(max-width:768px){
    .scada-command-panel{
        align-items:stretch;
    }

    .scada-panel-title,
    .scada-actions{
        width:100%;
        flex-basis:100%;
    }

    .scada-actions{
        justify-content:flex-start;
    }

    .command-btn{
        flex:1 1 135px;
        justify-content:center;
    }

    .kpi-row{
        grid-template-columns:repeat(auto-fit,minmax(135px,1fr));
    }
}
</style>
<script>
function openTab(tabName)
{
    document.querySelectorAll('.tab-content').forEach(function(tab) {
        tab.classList.remove('active');
    });

    var selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }

    document.querySelectorAll('.tab-btn').forEach(function(button) {
        button.classList.remove('active');
    });

    var selectedButton = document.getElementById('btn_' + tabName);
    if (selectedButton) {
        selectedButton.classList.add('active');
    }
}
</script>

<div class="scada-command-panel" role="region" aria-label="SCADA command controls">
    <div class="scada-panel-title">
        
        <div class="scada-status-line">
            <span class="status-light <?= $StatusClass ?>" aria-hidden="true"></span>
            <span><?= htmlspecialchars($DisplayStatus) ?></span>
        </div>
        <div class="scada-meta">
            <span><?= htmlspecialchars(isset($All_Devicename[1]) ? $All_Devicename[1] : 'Device') ?></span>
            <span>Last update: <?= htmlspecialchars($Date_F) ?> <?= htmlspecialchars($Time_F) ?></span>
        </div>
    </div>

    <div class="scada-actions">
        <button type="button" class="command-btn command-btn--start" onclick="sendTCPCommand('START', this)" title="Send START TCP command" aria-label="Send START TCP command">
            <span class="cmd-icon cmd-icon--start" aria-hidden="true"></span>
            <span class="cmd-label">Start</span>
        </button>

        <button type="button" class="command-btn command-btn--stop" onclick="sendTCPCommand('STOP', this)" title="Send STOP TCP command" aria-label="Send STOP TCP command">
            <span class="cmd-icon cmd-icon--stop" aria-hidden="true"></span>
            <span class="cmd-label">Stop</span>
        </button>

        <button type="button" class="command-btn command-btn--pause" onclick="tcp_modal('PAUSE')" title="Send PAUSE TCP command" aria-label="Send PAUSE TCP command">
            <span class="cmd-icon cmd-icon--pause" aria-hidden="true"></span>
            <span class="cmd-label">Pause</span>
        </button>

        <button type="button" class="command-btn command-btn--start" onclick="sendTCPCommand('START', this, true)" title="Send START command to all turbines" aria-label="Send START command to all turbines">
            <span class="cmd-icon cmd-icon--start" aria-hidden="true"></span>
            <span class="cmd-label">Start All</span>
        </button>

        <button type="button" class="command-btn command-btn--stop" onclick="sendTCPCommand('STOP', this, true)" title="Send STOP command to all turbines" aria-label="Send STOP command to all turbines">
            <span class="cmd-icon cmd-icon--stop" aria-hidden="true"></span>
            <span class="cmd-label">Stop All</span>
        </button>

        <button type="button" class="command-btn command-btn--pause" onclick="sendTCPCommand('PAUSE', this, true)" title="Send PAUSE command to all turbines" aria-label="Send PAUSE command to all turbines">
            <span class="cmd-icon cmd-icon--pause" aria-hidden="true"></span>
            <span class="cmd-label">Pause All</span>
        </button>

        <button type="button" class="command-btn command-btn--reset" onclick="tcp_modal('RESET')" title="Send RESET TCP command" aria-label="Send RESET TCP command">
            <span class="cmd-icon cmd-icon--reset" aria-hidden="true"></span>
            <span class="cmd-label">Reset</span>
        </button>
		
		<button type="button" class="command-btn command-btn--emerg" onclick="tcp_modal('EMERG')" title="Send STOP TCP command" aria-label="Send EMERG TCP command">
            <span class="cmd-icon cmd-icon--stop" aria-hidden="true"></span>
            <span class="cmd-label">EMERG</span>
        </button>

        <button type="button" class="command-btn command-btn--report" onclick="window.open('<?= htmlspecialchars($Energy_Report_Url, ENT_QUOTES) ?>','_blank')" title="Open Energy Report" aria-label="Open Energy Report">
            <span class="cmd-icon cmd-icon--report" aria-hidden="true"></span>
            <span class="cmd-label">Energy Report</span>
        </button>

        <button type="button" class="command-btn command-btn--back" onclick="window.location.href='dashboard.php'" title="Back to Channel 1" aria-label="Back to Channel 1">
            <span class="cmd-icon cmd-icon--back" aria-hidden="true"></span>
            <span class="cmd-label">Back</span>
        </button>

        <div id="tcpCommandFeedback" class="scada-command-feedback" aria-live="polite"></div>
    </div>
</div>
<div class="kpi-row">
<div class="kpi-card"><div class="kpi-title">Wind Speed</div><div class="kpi-value"><?= $bridge1_dcv ?> m/s</div></div>
<div class="kpi-card"><div class="kpi-title">Power</div><div class="kpi-value"><?= $bridge2_dca ?> KW</div></div>
<div class="kpi-card"><div class="kpi-title">Pitch Angle</div><div class="kpi-value"><?= $phase1_kvar ?></div></div>
<div class="kpi-card"><div class="kpi-title">GRPM</div><div class="kpi-value"><?= $panel2_bv ?></div></div>
<div class="kpi-card"><div class="kpi-title">Generation</div><div class="kpi-value"><?= $phase2_kvar ?> MWH</div></div>
</div>


<script>
function openTCPModal()
{
    showTCPModal("");
}

function closeTCPModal()
{
    var modal = document.getElementById("tcpModal");

    if (!modal) {
        return;
    }

    modal.style.display = "none";
    modal.removeAttribute("data-command");
}

function showTCPModal(command)
{
    var modal = document.getElementById("tcpModal");
    var title = document.getElementById("tcpModalTitle");

    if (!modal) {
        return;
    }

    if (command) {
        modal.setAttribute("data-command", command.toLowerCase());
    }

    if (title) {
        title.textContent = command ? command.toUpperCase() + " Command" : "SCADA Command Console";
    }
    
    modal.style.display = "flex";
}

function setTCPCommandFeedback(message, status)
{
    var feedback = document.getElementById("tcpCommandFeedback");

    if (!feedback) {
        return;
    }

    feedback.className = "scada-command-feedback";
    if (status) {
        feedback.className += " " + status;
    }
    feedback.textContent = message;
}

function sendTCPCommand(command, button, allDevices)
{
    if (!command) {
        return false;
    }

    var targetText = allDevices ? " to all turbines" : "";
    setTCPCommandFeedback("Sending " + command + " command" + targetText + "...", "");

    if (button) {
        button.disabled = true;
    }

    var request = new XMLHttpRequest();
    var body =
        "c1=<?= urlencode($_REQUEST['c1']) ?>" +
        "&db=<?= urlencode($Database_Name) ?>" +
        "&ajax=1" +
        "&cmd=" + encodeURIComponent(command) +
        (allDevices ? "&all=1" : "");

    request.open("POST", "TcpRequest.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
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
                setTCPCommandFeedback(response.message || (command + " command submitted" + targetText), "ok");
            }
            else {
                setTCPCommandFeedback(response.message || (command + " command failed" + targetText), "error");
            }
        }
        catch (error) {
            setTCPCommandFeedback(command + " command failed" + targetText, "error");
        }
    };
    request.send(body);

    return false;
}

function tcp_modal(command)
{
    var frame = document.getElementById("tcpModalFrame");

    showTCPModal(command);

    if (frame) {
        frame.src =
            "TcpRequest.php?c1=<?= urlencode($_REQUEST['c1']) ?>" +
            "&db=<?= urlencode($Database_Name) ?>" +
            "&cmd=" + encodeURIComponent(command);
    }
}

document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        closeTCPModal();
    }
});

document.addEventListener("click", function(event) {
    if (event.target && event.target.id === "tcpModal") {
        closeTCPModal();
    }
});
</script>

<div id="live" class="tab-content active">

<div class="kpi-row">

<div class="kpi-card">
    <div class="kpi-title">Date</div>
    <div class="kpi-value"><?= $Date_F ?></div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Time</div>
    <div class="kpi-value"><?= $Time_F ?></div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Status</div>

    <div class="kpi-value <?= $StatusClass ?>">
        <?= htmlspecialchars($DisplayStatus) ?>
    </div>
</div>

<div class="kpi-card"><div class="kpi-title">Active Alarm</div><div class="kpi-value"><?= $dummy20 ?></div></div>

<div class="kpi-card">
    <div class="kpi-title">Wind Speed</div>
    <div class="kpi-value"><?= $bridge1_dcv ?> m/s</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Pitch Angle</div>
    <div class="kpi-value"><?= $phase1_kvar ?></div>
</div>

<div class="kpi-card">
    <div class="kpi-title">GRPM</div>
    <div class="kpi-value"><?= $panel2_bv ?></div>
</div>

<div class="kpi-card">
    <div class="kpi-title">RRPM</div>
    <div class="kpi-value"><?= $panel1_bv ?></div>
</div>

<div class="kpi-card">
    <div class="kpi-title">VRMS</div>
    <div class="kpi-value"><?= $panel_dc2kw ?> V</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Hyd Pressure</div>
    <div class="kpi-value"><?= $phase1_kw ?> bar</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Power</div>
    <div class="kpi-value"><?= $bridge2_dca ?> KW</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Reactive Power</div>
    <div class="kpi-value"><?= $panel_dc1kw ?> KVAR</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Frequency</div>
    <div class="kpi-value"><?= $phase1_kva ?> Hz</div>
</div>

</div>

</div>

<div id="temp" class="tab-content">

<div class="kpi-row">

<div class="kpi-card">
    <div class="kpi-title">Gear Box Oil</div>
    <div class="kpi-value"><?= $inductive2_temp ?>°C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Gear Box Bearing</div>
    <div class="kpi-value"><?= $ambient_temp ?>°C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Generator DE Bearing</div>
    <div class="kpi-value"><?= $ccu_temp ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Generator NDE Bearing</div>
    <div class="kpi-value"><?= $cooling_input_temp ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Nacelle</div>
    <div class="kpi-value"><?= $cooling_output_temp ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">External Ambient</div>
    <div class="kpi-value"><?= $active1_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">TXF Winding 1</div>
    <div class="kpi-value"><?= $active2_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">TXF Winding 2</div>
    <div class="kpi-value"><?= $active_total ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">TXF Winding 3</div>
    <div class="kpi-value"><?= $dc1_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Hydraulic Oil</div>
    <div class="kpi-value"><?= $dc2_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Generator Winding</div>
    <div class="kpi-value"><?= $dc_total ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Control Cabinet</div>
    <div class="kpi-value"><?= $reactive1_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Generator Rings</div>
    <div class="kpi-value"><?= $reactive2_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Upper Radiator</div>
    <div class="kpi-value"><?= $reactive_total ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Lower Radiator</div>
    <div class="kpi-value"><?= $capacitive1_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Bus Bar</div>
    <div class="kpi-value"><?= $capacitive2_energy ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Rotor Phase 3</div>
    <div class="kpi-value"><?= $scb5 ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">CCU Card</div>
    <div class="kpi-value"><?= $scb6 ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Temperature 19</div>
    <div class="kpi-value"><?= $scb7 ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Grid Converter</div>
    <div class="kpi-value"><?= $scb8 ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Temperature 21</div>
    <div class="kpi-value"><?= $scb9 ?> °C</div>
</div>

<div class="kpi-card">
    <div class="kpi-title">Temperature 22</div>
    <div class="kpi-value"><?= $scb10 ?> °C</div>
</div>

</div>

</div>

<div id="produciable" class="tab-content">

<div class="kpi-row">

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Current Hour</div>
<div class="kpi-value"><?= $bridge13_temp ?> KWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Current Day</div>
<div class="kpi-value"><?= $bridge21_temp ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Current Month</div>
<div class="kpi-value"><?= $bridge22_temp ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Current Year</div>
<div class="kpi-value"><?= $bridge23_temp ?> MWH</div>
</div>

</div>

<div class="kpi-row">

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Previous Hour</div>
<div class="kpi-value"><?= $cabinet1_temp ?> KWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Previous Day</div>
<div class="kpi-value"><?= $cabinet2_temp ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Previous Month</div>
<div class="kpi-value"><?= $cabinet3_temp ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Producible Energy - Previous Year</div>
<div class="kpi-value"><?= $inductive1_temp ?> MWH</div>
</div>

</div>

</div>


<div id="prod" class="tab-content">

<div class="kpi-row">

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Current Hour</div>
<div class="kpi-value"><?= $phase2_kw ?> KWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Current Day</div>
<div class="kpi-value"><?= $phase2_kvar ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Current Month</div>
<div class="kpi-value"><?= $phase2_kva ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Current Year</div>
<div class="kpi-value"><?= $phase3_kw ?> MWH</div>
</div>

</div>

<div class="kpi-row">

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Previous Hour</div>
<div class="kpi-value"><?= $phase3_kvar ?> KWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Previous Day</div>
<div class="kpi-value"><?= $phase3_kva ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Previous Month</div>
<div class="kpi-value"><?= $bridge11_temp ?> MWH</div>
</div>

<div class="kpi-card">
<div class="kpi-title">Produced Energy - Previous Year</div>
<div class="kpi-value"><?= $bridge12_temp ?> MWH</div>
</div>

</div>

</div>

<div id="hour" class="tab-content">

<div class="kpi-row">

<div class="kpi-card">
<div class="kpi-title">Time/Home Counter - Current Line OK Hours</div>
<div class="kpi-value"><?= $panel_totalkw ?></div>
</div>

<div class="kpi-card">
<div class="kpi-title">Time/Home Counter - Current Ambient OK Hours</div>
<div class="kpi-value"><?= $r_vol ?></div>
</div>

<div class="kpi-card">
<div class="kpi-title">Time/Home Counter - Current Turbine OK Hours</div>
<div class="kpi-value"><?= $y_vol ?></div>
</div>

</div>

<div class="kpi-row">

<div class="kpi-card">
<div class="kpi-title">Time/Home Counter - Previous Line OK Hours</div>
<div class="kpi-value"><?= $b_vol ?></div>
</div>

<div class="kpi-card">
<div class="kpi-title">Time/Home Counter - Previous Ambient OK Hours</div>
<div class="kpi-value"><?= $r_curr ?></div>
</div>

<div class="kpi-card">
<div class="kpi-title">Time/Home Counter - Previous Turbine OK Hours</div>
<div class="kpi-value"><?= $y_curr ?></div>
</div>

</div>

</div>


<div id="time_counters" class="tab-content">

<div class="kpi-row">

<div class="kpi-card"><div class="kpi-title">Total Hours</div><div class="kpi-value"><?= $dummy14 ?></div></div>

<div class="kpi-card"><div class="kpi-title">Line Hours</div><div class="kpi-value"><?= $dummy15 ?></div></div>

<div class="kpi-card"><div class="kpi-title">No Service Hours</div><div class="kpi-value"><?= $dummy16 ?></div></div>

<div class="kpi-card"><div class="kpi-title">Line OK Hours</div><div class="kpi-value"><?= $dummy17 ?></div></div>

<div class="kpi-card"><div class="kpi-title">Ambient OK Hours</div><div class="kpi-value"><?= $dummy18 ?></div></div>

<div class="kpi-card"><div class="kpi-title">Turbine OK Hours</div><div class="kpi-value"><?= $dummy19 ?></div></div>


<div class="kpi-card"><div class="kpi-title">Delta Cnxn</div><div class="kpi-value"><?= $dummy21 ?></div></div>

<div class="kpi-card"><div class="kpi-title">Run Hours</div><div class="kpi-value"><?= $dummy22 ?></div></div>

<div class="kpi-card"><div class="kpi-title">Coupled Hours</div><div class="kpi-value"><?= $dummy23 ?></div></div>

</div>

</div>




<!-- TCP MODAL POPUP -->
<div id="tcpModal" class="tcp-modal" role="dialog" aria-modal="true" aria-labelledby="tcpModalTitle">
    <div class="tcp-modal-content">
        <div class="tcp-modal-header">
            <div>
                <div class="tcp-modal-kicker">TCP Request</div>
                <div id="tcpModalTitle" class="tcp-modal-title">SCADA Command Console</div>
            </div>
            <button type="button" class="close-tcp-btn" onclick="closeTCPModal()" aria-label="Close TCP command modal">&times;</button>
        </div>

        <iframe
            id="tcpModalFrame"
            src="TcpRequest.php?c1=<?= urlencode($_REQUEST['c1']) ?>&db=<?= urlencode($Database_Name) ?>" 
            title="TCP Request">
        </iframe>
    </div>
</div>




