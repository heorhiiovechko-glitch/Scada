<?php
// --- GAD Details Query --- //
$Mysql_Query_GAD = "SELECT 
    (SELECT Gen1_Max FROM device_register WHERE IMEI = '".$IMEI_Decode."' AND Date_S = CURDATE()) AS GAD_Today,
    (SELECT Gen1_Max FROM daily_data WHERE IMEI = '".$IMEI_Decode."' AND Date_S = (CURDATE() - INTERVAL 1 DAY)) AS GAD_Yesterday,
    (SELECT SUM(Gen1_Max) FROM daily_data WHERE IMEI = '".$IMEI_Decode."' AND Date_S BETWEEN (CURDATE() - INTERVAL 7 DAY) AND CURDATE()) AS GAD_Thisweek,
    (SELECT SUM(Gen1_Max) FROM daily_data WHERE IMEI = '".$IMEI_Decode."' AND MONTH(Date_s) = MONTH(NOW())) AS GAD_Thismonth,
    (SELECT SUM(Gen1_Max) FROM daily_data WHERE IMEI = '".$IMEI_Decode."' AND Date_S BETWEEN (CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY) AND (CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY)) AS GAD_Previousweek";

if (!$Mysql_Query_Result_GAD = $db->query($Mysql_Query_GAD)) {
    die($db->error);
}

if($Mysql_Query_Result_GAD->num_rows >= 1) {
    $Fetch_Result_GAD = $Mysql_Query_Result_GAD->fetch_array();
    $GAD_Today = $Fetch_Result_GAD['GAD_Today'];
    $GAD_Yesterday = $Fetch_Result_GAD['GAD_Yesterday'];
    $GAD_Thisweek = $Fetch_Result_GAD['GAD_Thisweek'];
    $GAD_Thismonth = $Fetch_Result_GAD['GAD_Thismonth'];
    $GAD_Previousweek = $Fetch_Result_GAD['GAD_Previousweek'];
} else {
    $GAD_Today = $GAD_Yesterday = $GAD_Thisweek = $GAD_Thismonth = $GAD_Previousweek = 0;
}

// --- GAD Report Table --- //
$No_Records = '<tr>
    <td width="50%" class="tab-head-td" colspan="2" style="padding:10px 0 10px 10px;"><center>Records Not Found</center></td>
</tr>';
?>

<table width="50%" border='0' align="center" cellpadding="1" cellspacing="1" class="innertab1">    
    <tr>
        <td colspan="2" class="tab-head-tr-new">&nbsp;&nbsp;GAD Details</td>
    </tr>
    <?php if($Mysql_Query_Result_GAD->num_rows >= 1) { ?>
        <tr>
            <td width="50%" class="tab-head-td-new">GAD for Today</td>
            <td class="tab-head-td1-new"><?= $GAD_Today > 30000 || $GAD_Today < 0 ? "Nil" : $GAD_Today." Kwh" ?> </td>
        </tr>
        <tr>
            <td class="tab-head-td-new">GAD for Yesterday</td>
            <td class="tab-head-td1-new"><?= $GAD_Yesterday > 30000 || $GAD_Yesterday < 0 ? "Nil" : $GAD_Yesterday." Kwh" ?></td>
        </tr>
        <tr>
            <td class="tab-head-td-new">GAD for This Week</td>
            <td class="tab-head-td1-new"><?= $GAD_Thisweek > 300000 || $GAD_Thisweek < 0 ? "Nil" : $GAD_Thisweek." Kwh" ?> </td>
        </tr>
        <tr>
            <td class="tab-head-td-new">GAD for Previous Week</td>
            <td class="tab-head-td1-new"><?= $GAD_Previousweek > 300000 || $GAD_Previousweek < 0 ? "Nil" : $GAD_Previousweek." Kwh" ?> </td>
        </tr>
        <tr>
            <td class="tab-head-td-new">GAD for This Month</td>
            <td class="tab-head-td1-new"><?= $GAD_Thismonth > 900000 || $GAD_Thismonth < 0 ? "Nil" : $GAD_Thismonth." Kwh" ?> </td>
        </tr>
    <?php } else {
        echo $No_Records;
    } ?>
</table>
