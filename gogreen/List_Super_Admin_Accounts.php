<?php
    error_reporting(0);

    if (isset($_REQUEST["XLS"])) { $XLS = 1; } else { $XLS = 0; }

    include("Header.php");
	
	

    $Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));
    $Title_Head1 = "'$Title_Head'";
    $Submit_Txt = $Title_Head."_Submit";
    $List_Page = str_replace('Add','List',$Title_Head);
    $Edit_Page = str_replace('List','Edit',$Title_Head);
    $View_Page = str_replace('List','View',$Title_Head);

    if (empty($_COOKIE[$Cook_Name])) {
        header("Location: index.php");
        exit;
    }

    // ===============================
    //   UI ENHANCEMENT CSS (ONLY)
    // ===============================
?>
<style>
/* MAIN WRAPPER */

/* FULL WIDTH – ALLOWS ENTIRE PAGE TO EXTEND */
html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
}

#admin_div {
    background: #f4f6f9;
    padding: 15px 20px;
    width: 100% !important;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

/* Page Title */
.headings {
    font-size: 22px;
    font-weight: 700;
    color: #003366;
    margin-bottom: 15px;
}

/* FULL-WIDTH SEARCH PANEL */
.List_Tab {
    width: 100% !important;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    padding: 16px;
    box-sizing: border-box;
}

/* Search Field Labels */
#admin_fields1 {
    font-weight: 600;
    color: #333;
    padding-bottom: 4px;
    font-size: 13px;
}

/* Inputs */
input[type="text"], select {
    width: 260px;
    padding: 8px 10px;
    font-size: 13px;
    border: 1px solid #c8c8c8;
    border-radius: 6px;
    transition: 0.25s ease;
}

/* Buttons */
.submit_but,
.submbg_top input[type=submit] {
    background: #003d7a;
    padding: 9px 22px;
    color: white;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    cursor: pointer;
}

/* Full width result table */
.full-width-table {
    width: 100% !important;
    border-spacing: 0 6px !important;
    border-collapse: separate !important;
}

/* Table header */
.admin-menu-heading {
    background: #003d7a !important;
    color: white !important;   
    padding: 8px;
    border-radius: 5px;
    font-size: 13px;
}

/* Table row */
.Row_Td {
    background: white;
    padding: 10px;
    font-size: 13px;
    border-bottom: 1px solid #e2e2e2;
}

.White_Bg { background: #ffffff; }
.Grey_Bg  { background: #f1f5ff; }

.White_Bg:hover td,
.Grey_Bg:hover td {
    background: #e7f0ff !important;
}

/* Pagination */
.Page_align {
    text-align: center;
    margin-top: 20px;
}

.Page_align a {
    background: #003d7a;
    padding: 8px 14px;
    color: white !important;
    border-radius: 5px;
    margin: 3px;
    text-decoration: none;
}

/* Remove any forced center alignment from tables */
table {
    margin: 0 !important;
    width: 100% !important;
}

/* FORCE HEADER TEXT TO WHITE, BOLD, AND BIGGER */
.admin-menu-heading,
.admin-menu-heading a,
.admin-menu-heading a:link,
.admin-menu-heading a:visited,
.admin-menu-heading a:hover {
    color: #ffffff !important;       /* Pure white */
    font-weight: 700 !important;     /* Bold */
    font-size: 16px !important;      /* Bigger text */
    text-decoration: none !important;
}

/* TOP USERBAR FULL-WIDTH ALIGNMENT */
.top-userbar {
    width: 100%;
    display: flex;
    justify-content: flex-end;   /* pushes content to right edge */
    align-items: center;
    padding: 8px 20px;           /* spacing from edges */
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

/* Username text */
.top-userbar span {
    color: #003366;
    font-weight: 600;
    margin-right: 15px;
}

/* Logout link */
.top-userbar a {
    color: #d90000;              /* red logout link, change as needed */
    font-weight: 700;
    text-decoration: none;
}
.top-userbar a:hover {
    text-decoration: underline;
}






</style>



<?php
// ==========================================================
// FORM FIELD ENGINE (+Search fields) — UNCHANGED LOGIC
// ==========================================================

$Form_Fields[] = array('1','1','Filter','search','','','E|N|0','','Filter_box','','');
$Form_Fields[] = array('1','5','',$Submit_Txt,'Search','','','','submit_but','','');

switch ($Cook_Variable[2]) {
    case 1: $Icons_Control = "D|E|V|X|P"; break;
    case 2: $Icons_Control = "D|E|V|X|P"; break;
    default: $Icons_Control = "||V|X|P"; break;
}

$Table_Name = "user_master";
$Order_By = "Account_ID desc";
$Records_Per_Page = 100;
$Pagination_No_Count = 10;
$Edit_ID_Column = "Account_ID";

$Search_Fields = array('Firstname','Lastname','Username','E_Mail');

/* List fields */
$List_Fields[] = array('Firstname','Firstname','','','');
$List_Fields[] = array('Username','Username','','','');
$List_Fields[] = array('Password','Password','','','');
$List_Fields[] = array('User Type','User_Type_ID','','',$User_Type_Array);
$List_Fields[] = array('Date','Date_Stamp','','6','');

/* Build list columns */
foreach($List_Fields as $Fields) {
    $List_Fields_Col[] = $Fields[1];
}
$Colspan_Count = count($List_Fields_Col) + 2;

// ================= SEARCH + VALIDATION (UNCHANGED) =================
foreach($Form_Fields as $Forms){
    if($Forms[5] == '*'){
        $Jvalid_Arr[$Forms[3]] .= $Forms[3] . ",";
        $Jvalid_Type_Arr[$Forms[6]] .= $Forms[6] . ",";
    }
}

$Jvalid_Arr_Join = substr(join('',$Jvalid_Arr),0,-1);
$Jvalid_Type_Arr_Join = substr(join('',$Jvalid_Type_Arr),0,-1);

?>
<?php if($XLS == 0){ ?>
<div id="admin_div">
    <div id="admin_left">
        <p class="headings"><?=$Title_Head?></p>

        <!-- SEARCH PANEL -->
        <table class="List_Tab" cellpadding="0" cellspacing="0" width="100%">

            <tr>
                <td valign="top">
                    <?php
                    echo "<form action=\"\" name=\"".$Title_Head."\" onsubmit=\"return FormValid('$Jvalid_Arr_Join','$Jvalid_Type_Arr_Join')\">";
                    ?>

                    <table width="100%" cellpadding="4" cellspacing="0">
                    <tr>
                        <td>

                        <?php foreach ($Form_Fields as $Forms) { ?>

                        <?php
                        if (empty($Forms[4]))
                            $Forms[4] = $_REQUEST[$Forms[3]];
                        ?>

                        <?php if ($Forms[1] != 5 && $Forms[1] != 6 && $Forms[1] != 3) { ?>
                            <!-- TEXTBOX FIELD -->
                            <div style="margin-bottom:14px;">
                                <div id="admin_fields1">
                                    <?=($Forms[2] != '' ? $Forms[2] : $Forms[3])?>
                                    <?=($Forms[5] == '*' ? $star : '')?>
                                </div>

                                <?php
                                // Calendar type
                                if ($Forms[9] == 'Cal') {
                                ?>
                                    <script src="js/calendar_us.js"></script>
                                    <link rel="stylesheet" href="css/calendar.css">
                                    <?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>

                                    <div class="Top_Cal">
                                        <script>
                                            new tcal({
                                                'formname': '<?=$Title_Head?>',
                                                'controlname': '<?=$Forms[3]?>'
                                            });
                                        </script>
                                    </div>
                                    <div class="date_format_top">(Format: mm/dd/yyyy)</div>

                                <?php
                                }
                                elseif ($Forms[9] == 'Cal_T') {
                                ?>
                                    <script src="js/dcal.js"></script>
                                    <link rel="stylesheet" href="css/dcal.css">

                                    <?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>

                                    <div class="Top_Cal">
                                        <input type="button" class="cal_st"
                                        onclick="displayCalendar(document.forms[0].<?=$Forms[3]?>,'dd-mm-yyyy hh:ii',this,true)">
                                    </div>
                                    <div class="date_format_top">(Format: dd/mm/yyyy hh:mm)</div>

                                <?php
                                } else {
                                ?>
                                    <?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
                                <?php } ?>

                                <?php if ($Forms[2] == 'Filter') { ?>
                                    <div class="Search_Hint_txt">(Searches: <?=join(',', $Search_Fields)?>)</div>
                                <?php } ?>

                                <?=J_Mes($Forms[3]);?>
                            </div>

                        <?php } elseif ($Forms[1] == 5 || $Forms[1] == 6) { ?>
                            <!-- SUBMIT BUTTON -->
                            <div class="submbg_top" style="margin-top:10px;">
                                <?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
                            </div>

                        <?php } elseif ($Forms[1] == 3) { ?>
                            <!-- SELECT DROPDOWN -->
                            <div style="margin-bottom:14px;">
                                <?=Func_Select_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],
                                    $Forms[8],$Forms[9],$Forms[10],$_REQUEST[$Forms[3]]);?>
                                <?=J_Mes($Forms[3]);?>
                            </div>
                        <?php } ?>

                        <?php } // end loop ?>
                        </td>
                    </tr>
                    </table>

                    </form>
                </td>

                <!-- TOP ICONS -->
                <td style="width:260px;" valign="top" align="right">

                    <table cellpadding="0" cellspacing="0" width="240">
                        <tr>

                        <?php
                        if (isset($Icons_Control)) {
                            $Icons_Controls = explode("|", $Icons_Control);

                            // DELETE
                            if ($Icons_Controls[0] == "D") {
                        ?>
                            <td class="icon-bor">
                                <input type="submit" name="OptionDelete" value=""
                                class="delete-icon"
                                onclick="return Confirm_Message('Delete selected records?',this.form)">
                                <div class="icon-txt">Delete</div>
                            </td>
                        <?php } ?>

                        <?php
                            // EDIT
                            if ($Icons_Controls[1] == "E") {
                        ?>
                            <td class="icon-bor">
                                <input type="submit" name="OptionEdit" value=""
                                class="edit-icon"
                                onclick="return anyCheck(this.form)">
                                <div class="icon-txt">Edit</div>
                            </td>
                        <?php } ?>

                        <?php
                            // VIEW
                            if ($Icons_Controls[2] == "V") {
                        ?>
                            <td class="icon-bor">
                                <input type="submit" name="OptionView" value=""
                                class="view-icon"
                                onclick="return anyCheck(this.form)">
                                <div class="icon-txt">View</div>
                            </td>
                        <?php } ?>

                        <?php
                            // EXCEL
                            if ($Icons_Controls[3] == "X") {
                        ?>
                            <td class="icon-bor">
                                <a href="<?="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?XLS=1&".$_SERVER['QUERY_STRING']?>"
                                   class="excel-icon">
                                    <img src="./images/icon-48-article-add.png" height="28">
                                </a>
                                <div class="icon-txt">Excel</div>
                            </td>
                        <?php } ?>

                        <?php
                            // PRINT
                            if ($Icons_Controls[4] == "P") {
                        ?>
                            <td class="icon-bor">
                                <a href="javascript:window.print();" class="excel-icon">
                                    <img src="./images/icon-48-print.png" height="28">
                                </a>
                                <div class="icon-txt">Print</div>
                            </td>
                        <?php } ?>

                        <?php } // end icon loop ?>

                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
</div>
<?php } // XLS == 0 ?>

<?php
//--------------------------------------------
// LIST TABLE OUTPUT
//--------------------------------------------

if($XLS == 0){
?>
<div id="admin_div">

    <span class="msg"><?=$_GET['msg']?></span>

    <table class="full-width-table" cellpadding="1" cellspacing="1">

        <?php
        include("ps_pagination.php");

        if ($Query_To_Mysql_Search)
            $Select_Mysql = $Query_To_Mysql_Search;
        else
            $Select_Mysql = "SELECT * FROM $Table_Name WHERE Parent_ID != '0' ORDER BY $Order_By";

        $Total_Records = mysqli_num_rows(mysqli_query($db, $Select_Mysql));

        $Pager = new PS_Pagination($db, $Select_Mysql, $Records_Per_Page, $Pagination_No_Count, "");

        $Record_Count = mysqli_num_rows($Pager->paginate());

        if($Record_Count){
            // Sorting arrow control
            if($_GET['Sortto'] == 'desc') {
                $Sortto = 'asc';
                $arrows = '<img src="images/down.gif" height="6">';
            } else {
                $Sortto = 'desc';
                $arrows = '<img src="images/up.gif" height="6">';
            }
        ?>

        <!-- TOTAL RECORDS -->
        <tr>
            <td colspan="<?=$Colspan_Count+1?>" align="right">
                <div class="total_admin">Total Records : <?=$Total_Records?></div>
            </td>
        </tr>

        <!-- TABLE HEADER -->
        <tr bgcolor="#F0F0F0" height="28">
            <td class="admin-menu-heading">#</td>
            <td class="admin-menu-heading"></td>

            <?php foreach($List_Fields as $Fields): ?>
                <?php
                    $List_Url = $List_Page.".php?Page=".$_REQUEST['Page']."&Sortby=".$Fields[1]."&Sortto=".$Sortto;
                ?>
                <td class="admin-menu-heading">
                    <a href="<?=$List_Url?>" style="font-weight:bold;">
                        <?=$Fields[0]?>
                        <?=( $_REQUEST['Sortby']==$Fields[1] ? $arrows : '' )?>
                    </a>
                </td>
            <?php endforeach; ?>

            <td class="admin-menu-heading">Options</td>
        </tr>

        <!-- SPACER -->
        <tr><td colspan="<?=$Colspan_Count?>">&nbsp;</td></tr>

        <?php
        // TABLE ROW LOOP
        $SNo = 1;
        $Result = $Pager->paginate();
        $Row_Column = 0;

        while($Fetch_Result = mysqli_fetch_array($Result)){
            $Row_Cls = ($Row_Column % 2 == 1 ? 'Grey_Bg' : 'White_Bg');
        ?>

        <tr class="<?=$Row_Cls?>" height="28">

            <!-- SERIAL NUMBER -->
            <td class="Row_Td"><?=$SNo?></td>

            <!-- CHECKBOX -->
            <td class="Row_Td">
                <input type="checkbox" name="Edit" value="<?=$Fetch_Result[$Edit_ID_Column]?>"
                       onclick="setChecks(this)"
                       <?=($Fetch_Result[$Edit_ID_Column] == $_REQUEST['Edit'] ? 'checked' : '')?>>
            </td>

            <!-- TABLE DATA -->
            <?php foreach($List_Fields as $Fields): ?>
                <td class="Row_Td">
                    <?php
                        if($Fields[4]) {
                            $Value = $Fields[4][$Fetch_Result[$Fields[1]]];
                        } else {
                            $Value = $Fetch_Result[$Fields[1]];
                        }

                        if($Fields[3])   // Date format
                            echo Date_Format_Func($Value, $Fields[3]);
                        elseif($Fields[2])   // Limit
                            echo substr($Value, 0, $Fields[2])."...";
                        else
                            echo $Value;
                    ?>
                </td>
            <?php endforeach; ?>

            <!-- OPTION ICONS -->
            <td class="Row_Td">
                <a href="Add_Device.php?P_ID=<?=$Fetch_Result['Account_ID']?>&P_ID1=<?=$Fetch_Result['Parent_ID']?>"
                   title="Add Device">
                    <img src="./images/icon_question.gif" height="20">
                </a>

                &nbsp;&nbsp;

                <a href="List_User.php?P_ID=<?=$Fetch_Result['Account_ID']?>&P_ID1=<?=$Fetch_Result['Parent_ID']?>"
                   title="Details">
                    <img src="./images/detail_icon.gif" height="20">
                </a>
            </td>

        </tr>

        <?php
            $SNo++;
            $Row_Column++;
        } // end while
        ?>

        <!-- PAGINATION -->
        <tr>
            <td colspan="<?=$Colspan_Count?>" align="center">
                <div class="Page_align">
                    <?=$Pager->renderFullNav();?>
                </div>
                <br><br>
            </td>
        </tr>

        <?php
        } else {
            echo "<tr><td colspan='$Colspan_Count'>".error_report("Records Not Found")."</td></tr>";
        }
        ?>
    </table>
</div>

<?php } // if XLS == 0 ?>

<!-- FOOTER -->
<?php if($XLS == 0) include("Footer.php"); ?>

<!-- EXCEL OUTPUT -->
<?php 
if($XLS == 1){
    echo $Excel_Output;
}
?>
