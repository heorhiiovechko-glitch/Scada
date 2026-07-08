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
$Message="";		
if (isset($_POST['Submit'])) {

    $Current_IMEI = $_POST['Current_IMEI'];
    $New_IMEI = $_POST['New_IMEI'];

$CIMEI_Query = mysqli_query($db,"select distinct IMEI,db_name from DEVICE_REGISTER  where IMEI = '$Current_IMEI'");
  $C_IMEI = mysqli_fetch_array($CIMEI_Query);
	$Db_Name=$C_IMEI['db_name'];
if ($C_IMEI == 0)
{
$Message="Current IMEI does not exists";
}
else {

$Query = mysqli_query($db,"select distinct IMEI from DEVICE_REGISTER  where IMEI = '".$New_IMEI."'");
     $Value = mysqli_fetch_array($Query);
   
if ($Value == 0) 
{

$Sql="SELECT TABLE_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE COLUMN_NAME IN ('IMEI')
      AND TABLE_SCHEMA='".$Db_Name."'";
$Result = mysqli_query($db,$Sql);
//echo $Db_Name;
while($Table = mysqli_fetch_array($Result))
{
$IMEI_Table = $Table[0];  
    $Update_IMEI_1=mysqli_query($db,"UPDATE device_register SET IMEI = '".$New_IMEI."' WHERE IMEI = '".$Current_IMEI."'");
if($Update_IMEI_1){
$Update_IMEI=mysqli_query($db,"UPDATE $Db_Name.$IMEI_Table  SET $Db_Name.$IMEI_Table.IMEI = '".$New_IMEI."' WHERE $Db_Name.$IMEI_Table.IMEI = '".$Current_IMEI."'");
//echo $Update_IMEI;
}
if($Update_IMEI){
$Update_IMEI_2=mysqli_query($db,"UPDATE va_master.daily_data  SET va_master.daily_data.IMEI = '".$New_IMEI."' WHERE va_master.daily_data.IMEI = '".$Current_IMEI."'");
//echo $Update_IMEI;
}
$Message = "IMEI Changed";
/*$Dir='/tmp/Versatile_Logs/WTWMD_Logs/';
if ($Open = opendir($Dir)) { 
    while (false !== ($FileName = readdir($Open))) { 
$Current_IMEI = $_POST['Current_IMEI'];
    $New_IMEI = $_POST['New_IMEI'];
        $New_Name = str_replace($Current_IMEI,$New_IMEI,$FileName);
        rename($Dir . $FileName, $Dir . $New_Name);
    }
    closedir($Open);
}*/

}
}
else {
$Message = "New IMEI already exists";
}

}
}




?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Change Modem</TITLE>
<script language="javascript" type="text/javascript">
function validate()
{

var formName=document.frm;

if(formName.Current_IMEI.value == "")
{
document.getElementById("Current_IMEI_label").innerHTML='Please Enter current IMEI';
formName.Current_IMEI.focus();
return false;
}
else
{
document.getElementById("Current_IMEI_label").innerHTML='';
}


if(formName.New_IMEI.value == "")
{
document.getElementById("New_IMEI_label").innerHTML='Please Enter New IMEI';
formName.New_IMEI.focus();
return false;
}
else
{
document.getElementById("New_IMEI_label").innerHTML='';
}

if(formName.Current_IMEI.value == formName.New_IMEI.value)
{
document.getElementById("New_IMEI_label").innerHTML='IMEI should not be same';
formName.New_IMEI.focus()
return false;
}
else
{
document.getElementById("New_IMEI_label").innerHTML='';
}

}
</script>
<style type="text/css">
<!--
.style7 {
color: #FF6666;
font-size: 24px;
}
.style12 {
color: red;
font-weight: bold;
text-align: center;
}
.style14 {color: #CC0033; font-weight: bold; }
-->
</style>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
</HEAD>
<BODY>

<form action="" method="post" name="frm" id="frm" onSubmit="return validate();">
<table width="50%" align="center" border="0" cellspacing="3" cellpadding="3">
<tr>
<td colspan="2" align="center"></td>
</tr><tr><td colspan="2" align="center"></td></tr>
<tr><td colspan="2" align="center"></td></tr><tr><td colspan="2" align="center"></td></tr><tr><td colspan="2" align="center"></td>
</tr><tr><td colspan="2" align="center"></td>
</tr><tr><td colspan="2" align="center"></td>
</tr>

<tr bgcolor="#666666">
<td colspan="2" align="center"><span class="style7">Change IMEI</span></td>
</tr>



<tr height="50px">
<td width="100%" align="left" bgcolor="#CCCCCC"><span class="style14">Old IMEI:</span></td>
<td align="center" bgcolor="#CCCCCC"><input type="text" name="Current_IMEI" id="Current_IMEI" size="20" autocomplete="off"/>&nbsp;&nbsp;&nbsp; <label id="Current_IMEI_label" class="level_msg"></td>
</tr>

<tr height="50px">
<td width="100%" align="left" bgcolor="#CCCCCC"><span class="style14">New IMEI:</span></td>
<td align="center" bgcolor="#CCCCCC"><input type="text" name="New_IMEI" id="New_IMEI" size="20" autocomplete="off"/>&nbsp;&nbsp;&nbsp; <label id="New_IMEI_label" class="level_msg"></td>
</tr>
<tr height="50px" bgcolor="#666666">
<td width="100%" colspan="2" align="center"><input type="Submit" name="Submit" value="Update IMEI"/></td>
</tr>

</table>
</form>
<br>
<span class="style12"> <?php if(isset($Message)) { echo $Message; } ?> </span>

</BODY>
</HTML>




<?php		
	// Include footer
	include("Footer.php");
?>
 
