<?php
include("Header.php");
$Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));
$Submit_Txt = $Title_Head."_Submit";
$List_Page = str_replace('Add','List',$Title_Head);

if(empty($_COOKIE[$Cook_Name])){
    header("Location: index.php");
    exit;
}
?>

<?php
$Message = "";		
if (isset($_POST['Submit'])) {

    $Current_IMEI = $_POST['Current_IMEI'];
    $New_IMEI = $_POST['New_IMEI'];

    $CIMEI_Query = mysqli_query($db,"select distinct IMEI,db_name from DEVICE_REGISTER where IMEI = '$Current_IMEI'");
    $C_IMEI = mysqli_fetch_array($CIMEI_Query);
    $Db_Name = $C_IMEI['db_name'];

    if ($C_IMEI == 0) {
        $Message="Current IMEI does not exist";
    } else {

        $Query = mysqli_query($db,"select distinct IMEI from DEVICE_REGISTER where IMEI = '".$New_IMEI."'");
        $Value = mysqli_fetch_array($Query);

        if ($Value == 0) {

            $Sql="SELECT TABLE_NAME 
                  FROM INFORMATION_SCHEMA.COLUMNS
                  WHERE COLUMN_NAME IN ('IMEI')
                  AND TABLE_SCHEMA='".$Db_Name."'";

            $Result = mysqli_query($db,$Sql);

            while($Table = mysqli_fetch_array($Result)) {
                $IMEI_Table = $Table[0];  

                $Update_IMEI_1 = mysqli_query($db,
                    "UPDATE device_register SET IMEI = '".$New_IMEI."' 
                     WHERE IMEI = '".$Current_IMEI."'"
                );

                if ($Update_IMEI_1) {
                    $Update_IMEI = mysqli_query($db,
                        "UPDATE $Db_Name.$IMEI_Table  
                         SET $Db_Name.$IMEI_Table.IMEI = '".$New_IMEI."' 
                         WHERE $Db_Name.$IMEI_Table.IMEI = '".$Current_IMEI."'"
                    );
                }

                if($Update_IMEI){
                    mysqli_query($db,
                        "UPDATE va_master.daily_data  
                         SET IMEI = '".$New_IMEI."' 
                         WHERE IMEI = '".$Current_IMEI."'"
                    );
                }

                $Message = "IMEI Updated Successfully!";
            }

        } else {
            $Message = "New IMEI already exists";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Change IMEI</title>

<style>
/* PAGE BACKGROUND */
body {
    margin: 0;
    padding: 0;
    background: #f0f3f7;
    font-family: "Segoe UI", sans-serif;
}

/* CARD */
.form-card {
    width: 450px;
    background: #ffffff;
    margin: 60px auto;
    padding: 30px 35px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

/* HEADER */
.form-title {
    font-size: 24px;
    font-weight: 700;
    color: #003d7a;
    text-align: center;
    margin-bottom: 25px;
}

/* LABEL */
.form-label {
    font-weight: 600;
    color: #003366;
    margin-bottom: 8px;
    display: block;
}

/* INPUT */
.form-input {
    width: 100%;
    padding: 12px 10px;
    border-radius: 8px;
    border: 1px solid #bfc7d1;
    background: #f9fafc;
    font-size: 15px;
    transition: 0.2s;
}
.form-input:focus {
    background: #fff;
    border-color: #0066cc;
    box-shadow: 0 0 4px rgba(0,102,204,0.4);
    outline: none;
}

/* BUTTON */
.submit-btn {
    width: 100%;
    background: #003d7a;
    color: #fff;
    padding: 13px;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 20px;
    transition: 0.25s;
}
.submit-btn:hover {
    background: #0055b3;
}

/* ERROR + SUCCESS MESSAGE */
.msg {
    text-align: center;
    font-size: 17px;
    font-weight: 700;
    margin-top: 15px;
}
.msg.error { color: #cc0000; }
.msg.success { color: green; }

/* Validation small labels */
.level_msg {
    font-size: 13px;
    color: red;
}
</style>


<script>
function validate() {

var f = document.frm;

if(f.Current_IMEI.value == ""){
    document.getElementById("Current_IMEI_label").innerHTML='Please enter current IMEI';
    f.Current_IMEI.focus();
    return false;
} else { document.getElementById("Current_IMEI_label").innerHTML=''; }

if(f.New_IMEI.value == ""){
    document.getElementById("New_IMEI_label").innerHTML='Please enter new IMEI';
    f.New_IMEI.focus();
    return false;
} else { document.getElementById("New_IMEI_label").innerHTML=''; }

if(f.Current_IMEI.value == f.New_IMEI.value){
    document.getElementById("New_IMEI_label").innerHTML='IMEI should not be same';
    f.New_IMEI.focus();
    return false;
}

return true;
}
</script>

</head>

<body>

<div class="form-card">
    <div class="form-title">Change IMEI</div>

    <form action="" method="post" name="frm" onsubmit="return validate();">

        <label class="form-label">Old IMEI:</label>
        <input type="text" name="Current_IMEI" id="Current_IMEI" class="form-input" autocomplete="off">
        <label id="Current_IMEI_label" class="level_msg"></label>

        <br><br>

        <label class="form-label">New IMEI:</label>
        <input type="text" name="New_IMEI" id="New_IMEI" class="form-input" autocomplete="off">
        <label id="New_IMEI_label" class="level_msg"></label>

        <button type="submit" name="Submit" class="submit-btn">Update IMEI</button>

    </form>

    <?php if(isset($Message) && $Message!=""){ ?>
        <div class="msg <?=($Message=='IMEI Updated Successfully!'?'success':'error')?>"><?=$Message?></div>
    <?php } ?>

</div>

</body>
</html>

<?php include("Footer.php"); ?>
