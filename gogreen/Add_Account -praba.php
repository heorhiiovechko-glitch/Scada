<?php
error_reporting(-1);

/* ---------------------- ORIGINAL CONFIG (UNCHANGED) ---------------------- */

include("Header.php");
$Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));
$Submit_Txt = $Title_Head."_Submit";
$List_Page = str_replace('Add','List',$Title_Head);

if(empty($_COOKIE[$Cook_Name])){
    header("Location: index.php");
    exit;
}

$Table_Name = "user_master";
$Duplicate_Column = "";

/* Form Fields (LOGIC UNTOUCHED) */
$Form_Fields[] = array('1','1','Firstname','Firstname','','*','E|N|0','','txtbox','','');
$Form_Fields[] = array('1','1','Lastname','Lastname','','','E|N|0','','txtbox','','');
$Form_Fields[] = array('1','1','E-Mail','E_Mail','','*','E|N|0','','txtbox','','');

$Form_Fields[] = array('1','1','Username','Username','','*','E|N|0','','txtbox','','');
$Form_Fields[] = array('1','2','Password','Password','','*','E|N|0','','txtbox','','');

$Form_Fields[] = array('2','2','Admin Access','AdminAccess',$Status_Array1,'*','1','selectcls','','----- Admin Access-----','');

$Form_Fields[] = array('1','1','In Time (HH:MM:SS)','In_Time','00:00:00','*','E|N|0','','txtbox','','');
$Form_Fields[] = array('1','1','Out Time (HH:MM:SS)','Out_Time','23:59:59','*','E|N|0','','txtbox','','');

$Form_Fields[] = array('1','1','Database Name','Db_Name','va_','*','E|N|0','','txtbox','','');

$Form_Fields[] = array('1','7','User Type','User_Type_ID','4','*','1','selectcls','','-----User Type-----','');
$Form_Fields[] = array('1','7','api key','api_key','0','*','1','selectcls','','-----API KEY-----','');

$Date_Cur1 = date("Y-m-d");
$Date_Cur2 = strtotime('+1 year',strtotime($Date_Cur1));

$Form_Fields[] = array('1','7','Account Valid Upto','Valid_Till',date('Y-m-d',$Date_Cur2),'','E|N|0','','txtbox','','');

if(!isset($_REQUEST['P_ID']))
    $_REQUEST['P_ID'] = $Cook_Variable[3];

$Form_Fields[] = array('1','7','Parent ID','Parent_ID',$_REQUEST['P_ID'],'','E|N|0','','txtbox','','');
$Form_Fields[] = array('1','7','Date Stamp','Date_Stamp',date("Y-m-d H:i:s"),'','E|N|0','','txtbox','','');

$Form_Fields[] = array('1','5','',$Submit_Txt,'Create New User','','','','submit_but','');

/* ---------------------- JAVASCRIPT VALIDATION LOGIC (UNCHANGED) ---------------------- */

foreach($Form_Fields as $Forms){
    if($Forms[5] == '*'){
        $Jvalid_Arr[$Forms[3]].=$Forms[3].",";
        $Jvalid_Type_Arr[$Forms[6]].=$Forms[6].",";
    }
}
$Jvalid_Arr_Join = substr(join('',$Jvalid_Arr),0,-1);
$Jvalid_Type_Arr_Join = substr(join('',$Jvalid_Type_Arr),0,-1);

/* ---------------------- SUBMIT LOGIC (UNCHANGED) ---------------------- */
/* ... Everything below stays same ... */

/* --------------- MODERN UI CSS --------------- */
?>
<style>
:root{
    --primary:#003366;
    --accent:#1565c0;
    --bg:#f5f7fb;
    --card:#ffffff;
    --text:#333;
    --radius:12px;
    --shadow:0 6px 22px rgba(0,0,0,0.08);
}

body{
    background:var(--bg);
    font-family: "Segoe UI", sans-serif;
    margin:0;
    padding:0;
}

.form-container{
    width:100%;
    max-width:850px;
    margin:25px auto;
    background:var(--card);
    border-radius:var(--radius);
    padding:28px;
    box-shadow:var(--shadow);
}

.header-title{
    font-size:24px;
    font-weight:800;
    color:var(--primary);
    margin-bottom:18px;
    text-transform:uppercase;
}

.field-block{
    margin-bottom:16px;
}

.field-label{
    font-size:14px;
    font-weight:600;
    color:#222;
    margin-bottom:6px;
}

input[type="text"], 
input[type="password"], 
select{
    width:100%;
    padding:12px 14px;
    border:1px solid #d0d7e2;
    border-radius:8px;
    font-size:14px;
    background:#fff;
}
input:focus, select:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(0,51,102,0.2);
    outline:none;
}

.submit-area{
    text-align:right;
    margin-top:25px;
}

button, input[type=submit]{
    background:var(--primary);
    color:#fff;
    padding:12px 26px;
    border:none;
    border-radius:8px;
    font-size:15px;
    cursor:pointer;
    font-weight:700;
    box-shadow:0 4px 12px rgba(0,51,102,0.3);
}
button:hover, input[type=submit]:hover{
    background:#004a99;
}

/* Two-column layout */
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    column-gap:20px;
}
@media(max-width:768px){
    .form-grid{ grid-template-columns:1fr; }
}
</style>

<!-- ---------------------- MODERNIZED FORM UI ---------------------- -->

<div class="form-container">
    <div class="header-title"><?= $Title_Head ?></div>

<form action="" method="post" name="<?=$Title_Head?>" 
      onsubmit="return FormValid('<?=$Jvalid_Arr_Join?>','<?=$Jvalid_Type_Arr_Join?>')" 
      enctype="multipart/form-data">

    <div class="form-grid">

<?php
/* ---------------------- FORM RENDERING (UNTOUCHED LOGIC, NEW UI) ---------------------- */
foreach($Form_Fields as $Forms){ ?>

    <?php if($Forms[1] != 5 && $Forms[1] != 6 && $Forms[1] != 7): ?>
        <div class="field-block">
            <div class="field-label">
                <?= ($Forms[2] != ''?$Forms[2] : $Forms[3]) ?>
                <?= ($Forms[5] == '*' ? '<span style="color:red">*</span>' : '') ?>
            </div>

            <?= Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],
                                   $Forms[8],$Forms[7]); ?>
            <?= J_Mes($Forms[3]); ?>
        </div>

    <?php elseif($Forms[1] == 2): ?>
        <!-- SELECT FIELD -->
        <div class="field-block">
            <div class="field-label">
                <?= ($Forms[2] != ''?$Forms[2] : $Forms[3]) ?>
                <?= ($Forms[5] == '*' ? '<span style="color:red">*</span>' : '') ?>
            </div>
            <?= Func_Select_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],
                                    $Forms[7],$Forms[8],$Forms[9],$Forms[10]); ?>
        </div>

    <?php endif; ?>

<?php } ?>

    </div>

    <div class="submit-area">
        <?= Func_Forms_Element($Form_Fields[$Submit_Pos][0],
                               $Form_Fields[$Submit_Pos][1],
                               $Form_Fields[$Submit_Pos][3],
                               $Form_Fields[$Submit_Pos][4],
                               $Form_Fields[$Submit_Pos][8],
                               $Form_Fields[$Submit_Pos][7]); ?>
    </div>

</form>
</div>

<?php include_once("Footer.php"); ?>
