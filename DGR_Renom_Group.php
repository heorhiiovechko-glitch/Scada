    foreach($DGR_IMEI as $IMEI_Val) {
        $Format_Type = isset($Format[$IMEI_Val]) ? (int)$Format[$IMEI_Val] : 0;
        if($Format_Type != $DGR_Format_Type) {
            continue;
        }
        $IMEI_SQL = dgr_escape_value($db, $IMEI_Val);
        $Date_Meta = array();
        $Date_Stamp_List = array();
        $Daily_Data_By_Date = array();
        $F11_Data_By_Date = array();
        $F11_Wind_By_Date = array();

        foreach($Date_Array as $DATE_Val) {
            $Date_Meta[$DATE_Val] = dgr_date_meta_for_closing($DATE_Val, isset($Closing_Time[$IMEI_Val]) ? $Closing_Time[$IMEI_Val] : 0);
            $Date_Stamp_List[$Date_Meta[$DATE_Val]['Date_Stamp']] = $Date_Meta[$DATE_Val]['Date_Stamp'];

            $Energy_Kwh[$IMEI_Val][$DATE_Val] = 0;
            $Produced_Energy[$IMEI_Val][$DATE_Val] = 0;
            $Producible_Energy[$IMEI_Val][$DATE_Val] = 0;
            $Efficiency[$IMEI_Val][$DATE_Val] = 0;
            $Total_hrs[$IMEI_Val][$DATE_Val] = 0;
            $Run_Hours[$IMEI_Val][$DATE_Val] = 0;
            $Avg_Windspeed[$IMEI_Val][$DATE_Val] = 0;
            $Energy_NIL[$IMEI_Val][$DATE_Val] = false;
        }

        $Date_In_SQL = dgr_sql_list($db, array_values($Date_Stamp_List));

        if($Date_In_SQL != "" && $Format_Type == 11) {
            $F11_Query = "
                SELECT
                    Date_S,
                    0 AS Avg_Windspeed,
                    MAX(CASE WHEN Phase2_kvar != '' THEN Phase2_kvar + 0 END) AS Produced_Energy,
                    MAX(CASE WHEN Bridge21_temp != '' THEN Bridge21_temp + 0 END) AS Producible_Energy,
                    MAX(CASE WHEN Bridge21_temp != '' THEN Bridge21_temp + 0 END) * 1000 AS Energy_Kwh,
                    MIN(CASE WHEN Dummy22 != '' AND (Dummy22 + 0) > 0 THEN Dummy22 + 0 END) AS Run_Min,
                    MAX(CASE WHEN Dummy22 != '' THEN Dummy22 + 0 END) AS Run_Max,
                    MIN(CASE WHEN Dummy14 != '' AND (Dummy14 + 0) > 0 THEN Dummy14 + 0 END) AS Total_hrs_Min,
                    MAX(CASE WHEN Dummy14 != '' THEN Dummy14 + 0 END) AS Total_hrs_Max,
                    MIN(CASE WHEN dummy17 != '' AND (dummy17 + 0) > 0 THEN dummy17 + 0 END) AS Line_Min,
                    MAX(CASE WHEN dummy17 != '' THEN dummy17 + 0 END) AS Line_Max,
                    MIN(CASE WHEN dummy16 != '' AND (dummy16 + 0) > 0 THEN dummy16 + 0 END) AS Import_Min,
                    MAX(CASE WHEN dummy16 != '' THEN dummy16 + 0 END) AS Import_Max
                FROM ".$DGR_Table_Name."
                WHERE IMEI = '".$IMEI_SQL."'
                  AND Date_S IN (".$Date_In_SQL.")
                GROUP BY Date_S
                ORDER BY Date_S";
            $F11_Query_Result = dgr_query_result($db, $F11_Query, $DGR_Query_Errors);
            if($F11_Query_Result) {
                while($F11_Row = $F11_Query_Result->fetch_array()) {
                    $F11_Data_By_Date[$F11_Row['Date_S']] = $F11_Row;
                }
            }

            $F11_Wind_Query = "
                SELECT
                    Date_S,
                    Bridge1_dcv
                FROM ".$DGR_Table_Name."
                WHERE IMEI = '".$IMEI_SQL."'
                  AND Date_S IN (".$Date_In_SQL.")
                  AND Bridge1_dcv != ''
                ORDER BY Date_S";
            $F11_Wind_Query_Result = dgr_query_result($db, $F11_Wind_Query, $DGR_Query_Errors);
            if($F11_Wind_Query_Result) {
                while($F11_Wind_Row = $F11_Wind_Query_Result->fetch_array()) {
                    $Wind_Date = $F11_Wind_Row['Date_S'];
                    $Wind_Value = dgr_extract_number($F11_Wind_Row['Bridge1_dcv']);
                    if($Wind_Value !== null && $Wind_Value > 0) {
                        if(!isset($F11_Wind_By_Date[$Wind_Date])) {
                            $F11_Wind_By_Date[$Wind_Date] = array('sum' => 0, 'count' => 0);
                        }
                        $F11_Wind_By_Date[$Wind_Date]['sum'] += $Wind_Value;
                        $F11_Wind_By_Date[$Wind_Date]['count']++;
                    }
                }
            }
        }

        foreach($Date_Array as $DATE_Val) {
            $Date_Stamp = $Date_Meta[$DATE_Val]['Date_Stamp'];

            if($Format_Type == 11 && isset($F11_Data_By_Date[$Date_Stamp])) {
                $Fetch_Result = $F11_Data_By_Date[$Date_Stamp];
                $Energy_Kwh[$IMEI_Val][$DATE_Val] = dgr_num($Fetch_Result['Energy_Kwh']);
                $Produced_Energy[$IMEI_Val][$DATE_Val] = dgr_num($Fetch_Result['Produced_Energy']);
                $Producible_Energy[$IMEI_Val][$DATE_Val] = dgr_num($Fetch_Result['Producible_Energy']);
                $Run_Hours[$IMEI_Val][$DATE_Val] = dgr_diff($Fetch_Result['Run_Max'], $Fetch_Result['Run_Min']);
                $Total_hrs[$IMEI_Val][$DATE_Val] = dgr_diff($Fetch_Result['Total_hrs_Max'], $Fetch_Result['Total_hrs_Min']);
                if(isset($F11_Wind_By_Date[$Date_Stamp]) && $F11_Wind_By_Date[$Date_Stamp]['count'] > 0) {
                    $Avg_Windspeed[$IMEI_Val][$DATE_Val] = $F11_Wind_By_Date[$Date_Stamp]['sum'] / $F11_Wind_By_Date[$Date_Stamp]['count'];
                }
                else {
                    $Avg_Windspeed[$IMEI_Val][$DATE_Val] = dgr_num($Fetch_Result['Avg_Windspeed']);
                }
            }

            $Energy_Kwh[$IMEI_Val][$DATE_Val] = $Energy_Kwh[$IMEI_Val][$DATE_Val] >= 0 ? $Energy_Kwh[$IMEI_Val][$DATE_Val] : 0;
            if($Energy_Kwh[$IMEI_Val][$DATE_Val] > 60000) {
                $Energy_NIL[$IMEI_Val][$DATE_Val] = true;
                $Energy_Kwh[$IMEI_Val][$DATE_Val] = 0;
                $Produced_Energy[$IMEI_Val][$DATE_Val] = 0;
                $Producible_Energy[$IMEI_Val][$DATE_Val] = 0;
            }

            $Efficiency[$IMEI_Val][$DATE_Val] = dgr_efficiency($Produced_Energy[$IMEI_Val][$DATE_Val], $Producible_Energy[$IMEI_Val][$DATE_Val]);
            $Total_hrs[$IMEI_Val][$DATE_Val] = ($Total_hrs[$IMEI_Val][$DATE_Val] == 25) ? 24 : dgr_valid_range($Total_hrs[$IMEI_Val][$DATE_Val], 25);
            $Run_Hours[$IMEI_Val][$DATE_Val] = ($Run_Hours[$IMEI_Val][$DATE_Val] == 25) ? 24 : dgr_valid_range($Run_Hours[$IMEI_Val][$DATE_Val], 25);
            $Avg_Windspeed[$IMEI_Val][$DATE_Val] = $Avg_Windspeed[$IMEI_Val][$DATE_Val] >= 0 ? $Avg_Windspeed[$IMEI_Val][$DATE_Val] : 0;

            $All_Energy_Kwh[] = $Energy_Kwh[$IMEI_Val][$DATE_Val];
            $All_Produced_Energy[] = $Produced_Energy[$IMEI_Val][$DATE_Val];
            $All_Producible_Energy[] = $Producible_Energy[$IMEI_Val][$DATE_Val];
            $All_Total_Hours[] = $Total_hrs[$IMEI_Val][$DATE_Val];
            $All_Run_Hours[] = $Run_Hours[$IMEI_Val][$DATE_Val];
            $All_Avg_Windspeed[] = $Avg_Windspeed[$IMEI_Val][$DATE_Val];
        }
    }

    foreach($Date_Array as $DATE_Val) {
        foreach($DGR_IMEI as $IMEI_Val) {
?>
            <tr>
                <td class="tab-head-td1" align="left"><?=dgr_h($DATE_Val)?></td>
                <td class="tab-head-td1" align="left"><?=dgr_h(isset($Device_Name[$IMEI_Val]) ? $Device_Name[$IMEI_Val] : $IMEI_Val)?></td>
                <td class="tab-head-td1 dgr-num"><?=dgr_format_generation_value($Energy_Kwh[$IMEI_Val][$DATE_Val], $Energy_NIL[$IMEI_Val][$DATE_Val], 2)?></td>
                <td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Total_hrs[$IMEI_Val][$DATE_Val], 2)?></td>
                <td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Run_Hours[$IMEI_Val][$DATE_Val], 2)?></td>
                <td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Avg_Windspeed[$IMEI_Val][$DATE_Val], 2)?></td>
                <td class="tab-head-td1 dgr-num"><?=dgr_format_no_round($Efficiency[$IMEI_Val][$DATE_Val], 2)?></td>
            </tr>
<?php
        }
    }
?>
            <tr class="dgr-total">
                <td class="tab-head-td1" align="left"><b>Total</b></td>
                <td class="tab-head-td1" align="left"><b></b></td>
                <td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_sum_values($All_Energy_Kwh), 2)?></b></td>
                <td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_sum_values($All_Total_Hours), 2)?></b></td>
                <td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_sum_values($All_Run_Hours), 2)?></b></td>
                <td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_avg_values($All_Avg_Windspeed), 2)?></b></td>
                <td class="tab-head-td1 dgr-num"><b><?=dgr_format_no_round(dgr_efficiency(dgr_sum_values($All_Produced_Energy), dgr_sum_values($All_Producible_Energy)), 2)?></b></td>
            </tr>
<?php
}
else {
    echo isset($No_Records) ? $No_Records : '<tr><td class="tab-head-td1" colspan="7" align="center">No Records Found</td></tr>';
}
?>
        </table>
    </td>
</tr>
