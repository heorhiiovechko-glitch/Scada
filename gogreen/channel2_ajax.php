<style>td {font-size:11px;}</style>
	<?php
		ob_start();
		session_start();
        error_reporting(0);
        include("Lib/config.php");
        include("Lib/dbconn.php");
		include("header1.php");
        if(empty($_SESSION['user'])){
            header('Location: index.php');
            exit;
        }
        
    ?>
	<?php
        if($_REQUEST['inputDate']){
            $InputDate = $_REQUEST['inputDate'];
            //$InputTime = $_REQUEST['inputTime'];
        }	
        else{
            $InputDate = date("d-m-Y");	
            //$InputTime = "00:00";	
        }
            
        if($_REQUEST['inputDate1']){
            $InputDate1 = $_REQUEST['inputDate1'];
            //$InputTime1 = $_REQUEST['inputTime1'];
        }	
        else{
            $InputDate1 = date("d-m-Y");
            //$InputTime1 = "23:59";	
        }	
    ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Versatilescada</title>
		<script type="text/javascript" src="js/jq1.js"></script>
        <script type="text/javascript" src="js/jscript.js"></script>
        <script type="text/javascript" src="./js/datepicker.js"></script>
        <script type="text/javascript" src="./js/eye.js"></script>
        <script type="text/javascript" src="./js/layout.js?ver=1.0.2"></script>
         <link rel="stylesheet" type="text/css" href="./css/style1.css" />
         <link rel="stylesheet" href="./css/datepicker.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="./css/Style.css" />
        <link rel="stylesheet" type="text/css" href="./css/but.css" />

</head>
<body>
    <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
        <tr>
            <td valign="top">
                <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                    <tr class="tab-head-tr">
                         <td align="left" class="right-tab" width="200px">&nbsp;&nbsp;Production</td>
                         <td align="left" class="right-tab">&nbsp;&nbsp;Start Date</td>
                         <td align="left" class="right-tab">&nbsp;&nbsp;End Date</td>
                         <td align="left" class="right-tab">&nbsp;&nbsp;</td>
                    </tr>
                    <tr >
                        <form name="wind">
                        <td align="left" style="padding-left:5px;">
                            <select name="p" id="p" style="width:180Px; padding-left:5px">
                                <option value="1" <?=($_REQUEST['p'] == 1?'selected=selected' : '')?>>Production Active Total</option>
                                <option value="2" <?=($_REQUEST['p'] == 2?'selected=selected' : '')?>>Production Active Month</option>
                                <option value="3" <?=($_REQUEST['p'] == 3?'selected=selected' : '')?>>Production Active Trip</option>
                            </select>
                        </td>
                        <td align="left"><input class="inputDate" name="inputDate" id="inputDate" value="<?=$InputDate?>" style="width:80px;" /></td>
                        <td align="left"><input class="inputDate1"  name="inputDate1" id="inputDate1" value="<?=$InputDate1?>" style="width:80Px;" /></td>
                        <td align="center"><input type="hidden" name="c1" value="<?=$_REQUEST['c1']?>" /><input type="submit" name="dateSearch" id="dateSearch" value="Search"></td>
                    </tr>
                    <tr >
                         <td align="center" colspan="4" height="10px"><hr size="1"></td>
                    </tr>
                 </table>
                          </form>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                    if(isset($_REQUEST['dateSearch'])){
                        //include("channel2_ajax.php");

            // for date search - saranya
        if(isset($_REQUEST['p'])){
            $From_D =date("d.m.Y",strtotime($_REQUEST['inputDate']));
            $To_D = date("d.m.Y",strtotime($_REQUEST['inputDate1']));		
    		$IMEI = base64_decode($_REQUEST['c1']);
            $Mysql_Query = "select * from DEVICE_DATA where IMEI = '".$IMEI."' and (Date_F between '".$From_D."' and '".$To_D."') order by Record_Index asc";
            $Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
            $Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
            if($Mysql_Record_Count>=1){
                $M = 1;
                while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){

                        $All_Date_Arr[$M] = $Fetch_Result['Date_F'];
                        $All_Time_Arr[$M] = $Fetch_Result['Time_F'];
                        $All_PAT_Gen0_Arr[$M] = $Fetch_Result['PAT_Gen0'];
                        $All_PAT_Gen1_Arr[$M] = $Fetch_Result['PAT_Gen1'];
                        $All_PAT_Gen2_Arr[$M] = $Fetch_Result['PAT_Gen2'];
                        $All_PAM_Gen0_Arr[$M] = $Fetch_Result['PAM_Gen0'];
                        $All_PAM_Gen1_Arr[$M] = $Fetch_Result['PAM_Gen1'];
                        $All_PAM_Gen2_Arr[$M] = $Fetch_Result['PAM_Gen2'];
                        $All_PATP_Gen0_Arr[$M] = $Fetch_Result['PATP_Gen0'];
                        $All_PATP_Gen1_Arr[$M] = $Fetch_Result['PATP_Gen1'];
                        $All_PATP_Gen2_Arr[$M] = $Fetch_Result['PATP_Gen2'];
                        
                    if($M == 1){
                        $F_PAT_Gen0 = $Fetch_Result['PAT_Gen0'];
                        $F_PAT_Gen1 = $Fetch_Result['PAT_Gen1'];
                        $F_PAT_Gen2 = $Fetch_Result['PAT_Gen2'];
                        $F_PAM_Gen0 = $Fetch_Result['PAM_Gen0'];
                        $F_PAM_Gen1 = $Fetch_Result['PAM_Gen1'];
                        $F_PAM_Gen2 = $Fetch_Result['PAM_Gen2'];
                        $F_PATP_Gen0 = $Fetch_Result['PATP_Gen0'];
                        $F_PATP_Gen1 = $Fetch_Result['PATP_Gen1'];
                        $F_PATP_Gen2 = $Fetch_Result['PATP_Gen2'];
                    }	
                    if($M == $Mysql_Record_Count){
                        $L_PAT_Gen0 = $Fetch_Result['PAT_Gen0'];
                        $L_PAT_Gen1 = $Fetch_Result['PAT_Gen1'];
                        $L_PAT_Gen2 = $Fetch_Result['PAT_Gen2'];
                        $L_PAM_Gen0 = $Fetch_Result['PAM_Gen0'];
                        $L_PAM_Gen1 = $Fetch_Result['PAM_Gen1'];
                        $L_PAM_Gen2 = $Fetch_Result['PAM_Gen2'];
                        $L_PATP_Gen0 = $Fetch_Result['PATP_Gen0'];
                        $L_PATP_Gen1 = $Fetch_Result['PATP_Gen1'];
                        $L_PATP_Gen2 = $Fetch_Result['PATP_Gen2'];
                    }	
                    $M++;
                }
            }		
        }				
    ?>

        <!-- 
            Production Active Total
        -->
        <tr>
            <td height="20px">&nbsp;</td>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                    <tr class="tab-head-tr">
                        <td colspan="5" align="left">&nbsp;&nbsp;Production Details</td>
                    </tr>
        <?php
            if(isset($_REQUEST['p']) && $_REQUEST['p'] == 1){
        ?>
                    <tr>
                        <td class="tab-head-td" colspan="5">Production Active Total</td>
                    </tr>
                    <tr>
                        <td class="tab-head-td" width="80px">Date</td>
                        <td class="tab-head-td" width="80px">Time</td>
                        <td class="tab-head-td" width="90px">Gen0</td>
                        <td class="tab-head-td" width="90px">Gen1</td>
                        <td class="tab-head-td" width="90px">Gen2</td>
                    </tr>
                    <?php
                        $MI = 1;
                        foreach($All_PAT_Gen0_Arr as $All_PAT_Gen0){
                    ?>
                    <tr>
                        <td class="tab-head-td1"><?=$All_Date_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_Time_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PAT_Gen0_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PAT_Gen1_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PAT_Gen2_Arr[$MI]?></td>
                    </tr>
                    <?php
                        $MI++;
                    }
                    ?>
                    <tr>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">Total</td>
                        <td class="tab-head-td">Total</td>
                        <td class="tab-head-td">Total</td>
                    </tr>
                    <tr>
                        <?php
                            if(isset($L_PAT_Gen0) && isset($F_PAT_Gen0)){
                                $L_PAT_Gen0 = str_replace('-','',$L_PAT_Gen0);
                                $F_PAT_Gen0 = str_replace('-','',$F_PAT_Gen0);
                                $PAT_Gen0 = $L_PAT_Gen0 - $F_PAT_Gen0;
                                $PAT_Gen0 = "-".$PAT_Gen0;
                            }
                            else{
                                $PAT_Gen0 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td"><?=$PAT_Gen0?></td>
                        <?php
                            if(isset($L_PAT_Gen1) && isset($F_PAT_Gen1)){
                                $L_PAT_Gen1 = str_replace('-','',$L_PAT_Gen1);
                                $F_PAT_Gen1 = str_replace('-','',$F_PAT_Gen1);
                                $PAT_Gen1 = $L_PAT_Gen1 - $F_PAT_Gen1;
                                $PAT_Gen1 = "-".$PAT_Gen1;
                            }
                            else{
                                $PAT_Gen1 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td"><?=$PAT_Gen1?></td>
                        <?php
                            if(isset($L_PAT_Gen2) && isset($F_PAT_Gen2)){
                                $L_PAT_Gen2 = str_replace('-','',$L_PAT_Gen2);
                                $F_PAT_Gen2 = str_replace('-','',$F_PAT_Gen2);
                                $PAT_Gen2 = $L_PAT_Gen2 - $F_PAT_Gen2;
                                $PAT_Gen2 = "-".$PAT_Gen2;
                            }
                            else{
                                $PAT_Gen2 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td"><?=$PAT_Gen2?></td>
                    </tr>
                </table>
         <?php
         }
         ?>
            </td>	
         </tr>
        <!-- 
            Production Active Month
        -->

        <tr>
            <td width="100%">
        <?php
            if(isset($_REQUEST['p']) && $_REQUEST['p'] == 2){
        ?>
                <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                    <tr>
                        <td class="tab-head-td" colspan="5">Production Active Month</td>
                    </tr>
                    <tr>
                        <td class="tab-head-td" width="80px">Date</td>
                        <td class="tab-head-td" width="80px">Time</td>
                        <td class="tab-head-td" width="90px">Gen0</td>
                        <td class="tab-head-td" width="90px">Gen1</td>
                        <td class="tab-head-td" width="90px">Gen2</td>
                    </tr>
                    <?php
                        $MI = 1;
                        foreach($All_PAM_Gen0_Arr as $All_PAM_Gen0){
                    ?>
                    <tr>
                        <td class="tab-head-td1"><?=$All_Date_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_Time_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PAM_Gen0_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PAM_Gen1_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PAM_Gen2_Arr[$MI]?></td>
                    </tr>
                    <?php
                        $MI++;
                    }
                    ?>
                    <tr>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">Total</td>
                        <td class="tab-head-td">Total</td>
                        <td class="tab-head-td">Total</td>
                    </tr>
                    <tr>
                        <?php
                            if(isset($L_PAM_Gen0) && isset($F_PAM_Gen0)){
                                $L_PAM_Gen0 = str_replace('-','',$L_PAM_Gen0);
                                $F_PAM_Gen0 = str_replace('-','',$F_PAM_Gen0);
                                $PAM_Gen0 = $L_PAM_Gen0 - $F_PAM_Gen0;
                                $PAM_Gen0 = "-".$PAM_Gen0;
                            }
                            else{
                                $PAM_Gen0 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td"><?=$PAM_Gen0?></td>
                        <?php
                            if(isset($L_PAM_Gen1) && isset($F_PAM_Gen1)){
                                $L_PAM_Gen1 = str_replace('-','',$L_PAM_Gen1);
                                $F_PAM_Gen1 = str_replace('-','',$F_PAM_Gen1);
                                $PAM_Gen1 = $L_PAM_Gen1 - $F_PAM_Gen1;
                                $PAM_Gen1 = "-".$PAM_Gen1;
                            }
                            else{
                                $PAM_Gen1 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td"><?=$PAM_Gen1?></td>
                        <?php
                            if(isset($L_PAM_Gen2) && isset($F_PAM_Gen2)){
                                $L_PAM_Gen2 = str_replace('-','',$L_PAM_Gen2);
                                $F_PAM_Gen2 = str_replace('-','',$F_PAM_Gen2);
                                $PAM_Gen2 = $L_PAM_Gen2 - $F_PAM_Gen2;
                                $PAM_Gen2 = "-".$PAM_Gen2;
                            }
                            else{
                                $PAM_Gen2 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td"><?=$PAM_Gen2?></td>
                    </tr>
                </table>
         <?php
         }
         ?>
            </td>	
         </tr>
        <!-- 
            Production Active Trip
        -->

        <tr>
            <td width="100%">
        <?php
            if(isset($_REQUEST['p']) && $_REQUEST['p'] == 3){
        ?>
                <table width="100%" border='0' align="left" cellpadding="1" cellspacing="1" class="innertab1">	
                    <tr>
                        <td class="tab-head-td" colspan="5">Production Active Trip</td>
                    </tr>
                   <tr>
                        <td class="tab-head-td" width="80px">Date</td>
                        <td class="tab-head-td" width="80px">Time</td>
                        <td class="tab-head-td" width="90px">Gen0</td>
                        <td class="tab-head-td" width="90px">Gen1</td>
                        <td class="tab-head-td" width="90px">Gen2</td>
                    </tr>
                    <?php
                        $MI = 1;
                        foreach($All_PATP_Gen0_Arr as $All_PATP_Gen0){
                    ?>
                    <tr>
                        <td class="tab-head-td1"><?=$All_Date_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_Time_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PATP_Gen0_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PATP_Gen1_Arr[$MI]?></td>
                        <td class="tab-head-td1"><?=$All_PATP_Gen2_Arr[$MI]?></td>
                    </tr>
                    <?php
                        $MI++;
                    }
                    ?>
                    <tr>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">Total</td>
                        <td class="tab-head-td">Total</td>
                        <td class="tab-head-td">Total</td>
                    </tr>
                    <tr>
                        <?php
                            if(isset($L_PATP_Gen0) && isset($F_PATP_Gen0)){
                                $L_PATP_Gen0 = str_replace('-','',$L_PATP_Gen0);
                                $F_PATP_Gen0 = str_replace('-','',$F_PATP_Gen0);
                                $PATP_Gen0 = $L_PATP_Gen0 - $F_PATP_Gen0;
                                $PATP_Gen0 = "-".$PATP_Gen0;
                            }
                            else{
                                $PATP_Gen0 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td">&nbsp;</td>
                        <td class="tab-head-td"><?=$PATP_Gen0?></td>
                        <?php
                            if(isset($L_PATP_Gen1) && isset($F_PATP_Gen1)){
                                $L_PATP_Gen1 = str_replace('-','',$L_PATP_Gen1);
                                $F_PATP_Gen1 = str_replace('-','',$F_PATP_Gen1);
                                $PATP_Gen1 = $L_PATP_Gen1 - $F_PATP_Gen1;
                                $PATP_Gen1 = "-".$PATP_Gen1;
                            }
                            else{
                                $PATP_Gen1 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td"><?=$PATP_Gen1?></td>
                        <?php
                            if(isset($L_PATP_Gen2) && isset($F_PATP_Gen2)){
                                $L_PATP_Gen2 = str_replace('-','',$L_PATP_Gen2);
                                $F_PATP_Gen2 = str_replace('-','',$F_PATP_Gen2);
                                $PATP_Gen2 = $L_PATP_Gen2 - $F_PATP_Gen2;
                                $PATP_Gen2 = "-".$PATP_Gen2;
                            }
                            else{
                                $PATP_Gen2 = 'NA';
                            }
                                
                        ?>	
                        <td class="tab-head-td"><?=$PATP_Gen2?></td>
                    </tr>
                </table>
         <?php
         }
         ?>
            </td>	
         </tr>
                <?php		
                    }
                ?>
            </td>
        </tr>
    </table>
</body>         
</html>