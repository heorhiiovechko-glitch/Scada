<?php
// channel5_channel2style.php
// Channel2 style UI + latest 10 records from device_data_f4
error_reporting(0);
include("header_inner.php");
if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}
$Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
if (isset($Cook_Variable)) {
    $Username = $Cook_Variable[0];
    $User = base64_encode($Cook_Variable[0]);
    $Pass = base64_encode($Cook_Variable[8]);
    $User_Type_ID = isset($Cook_Variable[2]) ? $Cook_Variable[2] : '';
    $Account_ID = isset($Cook_Variable[3]) ? $Cook_Variable[3] : '';
}

$IMEI = isset($_REQUEST['c1']) ? $_REQUEST['c1'] : '';
$IMEI_Decode = $IMEI ? base64_decode($IMEI) : '';
$Pocket_Length = isset($_REQUEST['l']) ? $_REQUEST['l'] : '';
$FType = isset($_REQUEST['FType']) ? $_REQUEST['FType'] : '';
$Database_Name = isset($_REQUEST['Db_Name']) ? $_REQUEST['Db_Name'] : '';

// defaults / placeholders
$All_Devicename = array();
$GAD_Today = $GAD_Yesterday = $GAD_Thismonth = '';
// try find device info to get db name and device name
if ($IMEI_Decode) {
    $safeIMEI = $db->real_escape_string($IMEI_Decode);
    $q = "SELECT Device_Name, Db_Name, Capacity, Date_Of_Commission FROM device_register WHERE IMEI = '{$safeIMEI}' LIMIT 1";
    if ($r = $db->query($q)) {
        if ($row = $r->fetch_assoc()) {
            $All_Devicename[1] = $row['Device_Name'];
            if (!$Database_Name && !empty($row['Db_Name'])) $Database_Name = $row['Db_Name'];
        }
        $r->free();
    }
}



$Mysql_Query_GAD="select (select ((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) from device_register where IMEI = '".$IMEI_Decode."' and Date_S=curdate()) as GAD_Today,(select ((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S=(curdate()-interval 1 day) limit 1) as GAD_Yesterday,(select sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_ADD(CURDATE(), INTERVAL 1-DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 7-DAYOFWEEK(CURDATE()) DAY) limit 1) as GAD_Thisweek,(select sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and Date_S BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) AND LAST_DAY(NOW()) limit 1) as GAD_Thismonth,(select sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) from daily_data where IMEI = '".$IMEI_Decode."' and WEEK (Date_S) = WEEK(curdate() ) - 1 and Month(Date_S)=month(curdate()) AND YEAR( Date_S) = YEAR( curdate() ) limit 1) as GAD_Previousweek";
//echo $Mysql_Query_GAD;
	if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD))
            {
                die($db->error);
            }

            if($Mysql_Query_Result_GAD->num_rows >= 1)
            {
                while($Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array()) {	$GAD_Today = $Fetch_Result_GAD['GAD_Today'];
			$GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
			$GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
			$GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
			$GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];

			}
}

// Helper: build SQL to fetch last N records safely
function fetch_latest_records($db, $dbName, $imeiDecoded, $limit = 10) {
    $safeDB = preg_replace('/[^a-zA-Z0-9_]/', '', $dbName);
    $safeIMEI = $db->real_escape_string($imeiDecoded);
    $sql = "SELECT * FROM `{$safeDB}`.device_data_f4 WHERE IMEI='{$safeIMEI}' AND Status != '' ORDER BY Record_Index DESC LIMIT " . intval($limit);
    $rows = [];
    if ($res = $db->query($sql)) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $res->free();
    }
    return $rows;
}

// If DB present, fetch latest 10 records
$latestRows = [];
if ($Database_Name && $IMEI_Decode) {
    $latestRows = fetch_latest_records($db, $Database_Name, $IMEI_Decode, 10);
}

// Fallbacks
$All_Devicename[1] = isset($All_Devicename[1]) ? $All_Devicename[1] : 'Unknown Device';

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Channel5</title>

<!-- Channel2-like styles -->
<style>
:root{
  --teal:#018f8a; --muted:#f6fbfc; --header:#e6f3f7; --odd:#ffffff; --even:#f7fbfe;
  --text:#0b3b3b;
}
*{box-sizing:border-box;font-family:Arial, Helvetica, sans-serif}
body{margin:0;background:var(--muted);color:var(--text);font-size:14px}
.page-wrapper{width:100%;max-width:1400px;margin:12px auto;padding:0 12px}
.header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 0;
    background:transparent !important;
}


.device-title{
    font-size:20px;
    font-weight:700;
    background:none !important;
}

.gad-row{
    display:flex;
    gap:25px;
    align-items:center;
    padding:0;
    margin:0;
    background:none !important;
}

.gad-row div{
    text-align:center;
    background:none !important;
}

.badge{
    background:none !important;
    padding:0;
    font-weight:700;
    color:#024;
}

.small-muted{
    font-size:13px;
}

.tcp-btn{
    width:36px;
    height:36px;
    border-radius:50%;
    background:#1976d2;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    border:none;
}

.header-right-controls{
    display:flex;
    align-items:center;
    gap:10px;
    background:none !important;
}


.card{background:#fff;padding:12px;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,0.06);margin-bottom:12px}
.table-wrap{overflow:auto}
.table{width:100%;border-collapse:collapse;background:#fff}
.table thead th{position:sticky;top:0;background:var(--header);padding:10px 8px;font-weight:700;color:var(--text);border-bottom:1px solid #d8e2e6}
.table tbody td{padding:9px 8px;border-bottom:1px solid #f0f4f6;white-space:nowrap}
.table tbody tr:nth-child(odd) td{background:var(--odd)}
.table tbody tr:nth-child(even) td{background:var(--even)}


.status-green{
    color:#0a8a00;
    font-weight:700;
}

.status-blue{
    color:#0066cc;
    font-weight:700;
}

.status-red{
    color:#cc0000;
    font-weight:700;
}

/* STANDARD HEADER LAYOUT */
.header-main{
    display:flex;
    align-items:center;
    gap:16px;
    width:100%;
}

/* LEFT */
.header-left{
    font-size:20px;
    font-weight:700;
    min-width:180px;
}

/* CENTER */
.header-center{
    flex:1;
    display:flex;
    justify-content:center;
    gap:28px;
    font-weight:700;
    text-align:center;
    flex-wrap:wrap;
}

/* RIGHT */
.header-right{
    display:flex;
    gap:12px;
    align-items:center;
    margin-left:auto;
}

/* HEADER BUTTON STYLE */
.btn-header{
    font-size:14px;
    font-weight:600;
    padding:6px 14px;
    border:1px solid #555;
    background:#f5f5f5;
    color:#000;
    border-radius:3px;
    cursor:pointer;
    text-decoration:none;
}

.btn-header:hover{
    background:#e6e6e6;
}

/* Remove big gaps between sections */
.card {
    margin-bottom: 10px !important;
}

.iframe-card {
    background:#fff;
    padding:10px;
    border-radius:6px;
    box-shadow:0 2px 6px rgba(0,0,0,0.06);
    margin-top:8px;
}

.iframe-card iframe {
    width:100% !important;
    height:300px;
    border:1px solid #168A83;
    display:block;
}





.sticky-left{display:flex;flex-direction:column;gap:8px}
@media(max-width:900px){.header{flex-direction:column;align-items:flex-start}.gad-row{flex-direction:column}}
</style>
</head>
<body>
<div class="page-wrapper">

 <!-- TOP HEADER (STANDARD LAYOUT) -->
<div class="header header-main">

    <!-- DEVICE NAME -->
    <div class="header-left">
        <?=htmlspecialchars($All_Devicename[1])?>
    </div>

    <!-- GAD VALUES -->
    <div class="header-center">
        <span>GAD Today : <?=is_numeric($GAD_Today)?round($GAD_Today,2).' kWh':'—'?></span>
        <span>GAD Yesterday : <?=is_numeric($GAD_Yesterday)?round($GAD_Yesterday,2).' kWh':'—'?></span>
        <span>GAD Month : <?=is_numeric($GAD_Thismonth)?round($GAD_Thismonth,2).' kWh':'—'?></span>
    </div>

    <!-- RIGHT BUTTONS -->
    <div class="header-right">

        <!-- REPORTS -->
        <a class="btn-header"
           href="channel5_ajax.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?>&FType=<?= $_REQUEST['FType'] ?>">
            Reports
        </a>

        <!-- REMOTE -->
        <button class="btn-header" onclick="openTCPModal()">
            Remote
        </button>

    </div>

</div>


</div>


  <!-- main card: latest 10 rows -->
  <div class="card">
     

      <div id="records" class="table-wrap">
          <table class="table" role="table" aria-label="Latest records">
              <thead>
                  <tr>
                      <th>Date</th>
                      <th>Time</th>
                      <th>GRPM</th>
                      <th>RRPM</th>
                      <th>Wind Spd</th>
                      <th>Status</th>
                      <th>Power</th>
                      <th>R Volt</th>
                      <th>Y Volt</th>
                      <th>B Volt</th>
                      <th>R Cur</th>
                      <th>Y Cur</th>
                      <th>B Cur</th>
					  <th>Imp Kwh</th>
                      <th>Gen1 Kwh</th>
					  <th>Gen2 Kwh</th>
                      <th>Total Kwh</th>
					  <th>G1 Hrs</th>
					  <th>G2 Hrs</th>
                  </tr>
              </thead>
              <tbody>
              <?php
              if (!empty($latestRows) && is_array($latestRows)) {
                  foreach ($latestRows as $r) {
                      // sanitize / extract expected fields (fallbacks)
                      $Date_S = isset($r['Date_S']) ? htmlspecialchars($r['Date_S']) : '—';
                      $Time_S = isset($r['Time_S']) ? htmlspecialchars($r['Time_S']) : '—';
                      $GRPM = isset($r['GRPM']) ? htmlspecialchars($r['GRPM']) : '—';
                      $RRPM = isset($r['RRPM']) ? htmlspecialchars($r['RRPM']) : '—';
                      $Windspeed = isset($r['Windspeed']) ? htmlspecialchars(str_replace('m/s','',$r['Windspeed'])) : '—';
                      $Status = isset($r['Status']) ? htmlspecialchars(str_replace('#','',$r['Status'])) : '—';
                      $Power = isset($r['Power']) ? htmlspecialchars($r['Power']) : '—';
                      $RPhase_V = isset($r['RPhase_Volt']) ? htmlspecialchars($r['RPhase_Volt']) : '—';
                      $YPhase_V = isset($r['YPhase_Volt']) ? htmlspecialchars($r['YPhase_Volt']) : '—';
                      $BPhase_V = isset($r['BPhase_Volt']) ? htmlspecialchars($r['BPhase_Volt']) : '—';
                      $RPhase_C = isset($r['RPhase_Current']) ? htmlspecialchars($r['RPhase_Current']) : '—';
                      $YPhase_C = isset($r['YPhase_Current']) ? htmlspecialchars($r['YPhase_Current']) : '—';
                      $BPhase_C = isset($r['BPhase_Current']) ? htmlspecialchars($r['BPhase_Current']) : '—';
					  $Import_Kwh = isset($r['Import_Kwh']) ? htmlspecialchars($r['Import_Kwh']) : '—';
                      $PAT_Gen1 = isset($r['PAT_Gen1']) ? htmlspecialchars($r['PAT_Gen1']) : '—';
					  $PAT_Gen2 = isset($r['PAT_Gen2']) ? htmlspecialchars($r['PAT_Gen2']) : '—';
                      $PAT_Total = isset($r['PAT_Gen2']) ? htmlspecialchars($r['PAT_Gen2']) : (isset($r['PAT_Total'])?htmlspecialchars($r['PAT_Total']):'—');
					  $PH_Gen1 = isset($r['Gen1_Hours']) ? htmlspecialchars($r['Gen1_Hours']) : '—';
					  $PH_Gen2 = isset($r['Gen2_Hours']) ? htmlspecialchars($r['Gen2_Hours']) : '—';
                      // status class
                      $statusClass = 'status-red';
                      if (in_array($Status, ['Run','M/C Running','RUN','OperateG1','OperateG2','FreeWheelingG1','FreeWheelingG2'])) $statusClass='status-green';
                      elseif (in_array($Status, ['Grid Drop','GridDrop'])) $statusClass='status-blue';
                      echo "<tr>
                              <td>{$Date_S}</td>
                              <td>{$Time_S}</td>
                              <td>{$GRPM}</td>
                              <td>{$RRPM}</td>
                              <td>{$Windspeed}</td>
                              <td><span class='{$statusClass}'>".$Status."</span></td>
                              <td>{$Power}</td>
                              <td>{$RPhase_V}</td>
                              <td>{$YPhase_V}</td>
                              <td>{$BPhase_V}</td>
                              <td>{$RPhase_C}</td>
                              <td>{$YPhase_C}</td>
                              <td>{$BPhase_C}</td>
							  <td>{$Import_Kwh}</td>
                              <td>{$PAT_Gen1}</td>
							  <td>{$PAT_Gen2}</td>
                              <td>{$PAT_Total}</td>
							  <td>{$PH_Gen1}</td>
							  <td>{$PH_Gen2}</td>
                          </tr>";
                  }
              } else {
                  echo '<tr><td colspan="15" style="text-align:center;padding:18px">No Records Found</td></tr>';
              }
              ?>
              </tbody>
          </table>
      </div>
  </div>

 



<!-- POWER vs WINDSPEED -->
<div class="card iframe-card">
    <iframe
        src="Power_Windspeed_chart_Monthly_iframe.php?c1=<?= $_REQUEST['c1'] ?>&Year=<?= date('m-Y') ?>&l=<?= $_REQUEST['l'] ?>">
    </iframe>
</div>

<!-- DAILY GENERATION -->
<div class="card iframe-card">
    <iframe
        src="Daily_Generation_Report_Individual_Excel_iframe.php?c1=<?= $_REQUEST['c1'] ?>&l=<?= $_REQUEST['l'] ?>&FType=<?= $_REQUEST['FType'] ?>">
    </iframe>
</div>




<script>
function openTCPModal(){
    var c1 = encodeURIComponent("<?php echo isset($_REQUEST['c1'])?$_REQUEST['c1']:''; ?>");
    var db = encodeURIComponent("<?php echo isset($Database_Name)?$Database_Name:''; ?>");
    document.getElementById('tcpIframe').src = "TcpRequest.php?c1=" + c1 + "&db=" + db;
    document.getElementById('tcpModal').style.display = 'flex';
}
function closeTCPModal(){
    document.getElementById('tcpModal').style.display = 'none';
    document.getElementById('tcpIframe').src = '';
}

// auto-refresh latest rows container (20 seconds)
setInterval(function(){
    // simple reload of the whole records area by re-requesting this page fragment - easiest approach
    // We will request current URL with ajax and extract #records -- fallback to full-page reload on failure
    var xhr = new XMLHttpRequest();
    var url = window.location.href;
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){
            try {
                var temp = document.createElement('div');
                temp.innerHTML = xhr.responseText;
                var newRecords = temp.querySelector('#records');
                if(newRecords){
                    document.getElementById('records').innerHTML = newRecords.innerHTML;
                    return;
                }
            } catch(e){}
            // fallback: do full reload
            // location.reload();
        }
    };
    xhr.send();
}, 20000);
</script>
</body>
</html>
