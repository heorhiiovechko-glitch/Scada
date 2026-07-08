
<?php

include("header.php");
?>
<?php
			include("Includes.php");           	
	if(empty($_COOKIE[$Cook_Name])){
		header("Location: index.php");
		exit;
	}


$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	

	  	if(isset($Cook_Variable)){
			$Username = $Cook_Variable[0];
			$User_Type_ID = $Cook_Variable[2];
			$Account_ID = $Cook_Variable[3];
			
$Message="";		

if ($_POST['Submit']) {

    $Current_Password = $_POST['Current_Password'];
    $New_Password = $_POST['New_Password'];
    $Confirm_Password = $_POST['Confirm_Password'];
	$Sql = "SELECT * FROM user_master WHERE Username = '$Username'";
      $Result = mysql_query($Sql);
	$Num_Rows = mysql_num_rows($Result);
	$Rows = mysql_fetch_array($Result);

	$User = $Rows['Username'];
        $Password = $Rows['Password'];
	$Firstname = $Rows['Firstname'];
        $Lastname = $Rows['Lastname'];

	//$Email = $Rows['E_Mail'];
        
     
 if($Current_Password == $Password) {   

$Sql="UPDATE user_master SET Password ='$New_Password' WHERE Username = '$Username'"; 

 mysql_query($Sql);

      $Message = "Password Changed";
	
	/* $To = $Email;

        $Subject = "YOUR PASSWORD HAS BEEN CHANGED";

        $Msg = "<p>Hello $Firstname $Lastname. You've received this E-Mail
        because you have requested a PASSWORD CHANGE. Your new password is $New_Password.";

        $From = "generation@pioneerwincon.com";

        $Headers = "From: $From";

        //Mails the username and unencrypted password to the user
        mail($To,$Subject,$Msg,$Headers); */


} 
else {
$Message = "Current Password is not correct";
}
 }				
/*mysql_query($sql);
header("Location:Change_Password.php?msg=updated");
}*/



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Change Password</TITLE>
<script language="javascript" type="text/javascript">
function validate()
{

var formName=document.frm;

if(formName.Current_Password.value == "")
{
document.getElementById("Current_Password_label").innerHTML='Please Enter Current Password';
formName.Current_Password.focus();
return false;
}
else
{
document.getElementById("Current_Password_label").innerHTML='';
}


if(formName.New_Password.value == "")
{
document.getElementById("New_Password_label").innerHTML='Please Enter New Password';
formName.New_Password.focus();
return false;
}
else
{
document.getElementById("New_Password_label").innerHTML='';
}


if(formName.Confirm_Password.value == "")
{
document.getElementById("Confirm_Password_label").innerHTML='Enter ConfirmPassword';
formName.Confirm_Password.focus();
return false;
}
else
{
document.getElementById("Confirm_Password_label").innerHTML='';
}


if(formName.New_Password.value != formName.Confirm_Password.value)
{
document.getElementById("Confirm_Password_label").innerHTML='Passwords Mismatch';
formName.Confirm_Password.focus()
return false;
}
else
{
document.getElementById("Confirm_Password_label").innerHTML='';
}
}
</script>
<style type="text/css">
<!--
.style1 {font-weight: bold}
.style7 {
color: #FF6666;
font-size: 24px;
}
.style9 {
color: #FF6666;
font-weight: bold;
}
.style12 {
color: #666666;
font-weight: bold;
}
.style14 {color: #CC0033; font-weight: bold; }
-->
</style>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
</HEAD>
<BODY>

<form method="post" name="frm" id="frm" onSubmit="return validate();">
<table width="50%" align="center" border="0" cellspacing="3" cellpadding="3">
<tr>
<td colspan="2" align="center"></td>
</tr><tr><td colspan="2" align="center"></td></tr>
<tr><td colspan="2" align="center"></td></tr><tr><td colspan="2" align="center"></td></tr><tr><td colspan="2" align="center"></td>
</tr><tr><td colspan="2" align="center"></td>
</tr><tr><td colspan="2" align="center"></td>
</tr>

<tr bgcolor="#666666">
<td colspan="2"><span class="style7">Change Password</span></td>
</tr>



<tr height="50px">
<td width="100%" align="left" bgcolor="#CCCCCC"><span class="style14">Current Password:</span></td>
<td align="center" bgcolor="#CCCCCC"><input type="password" name="Current_Password" id="Current_Password" size="20" autocomplete="off"/>&nbsp;&nbsp;&nbsp; <label id="Current_Password_label" class="level_msg"></td>
</tr>

<tr height="50px">
<td width="100%" align="left" bgcolor="#CCCCCC"><span class="style14">New Password:</span></td>
<td align="center" bgcolor="#CCCCCC"><input type="password" name="New_Password" id="New_Password" size="20" autocomplete="off"/>&nbsp;&nbsp;&nbsp; <label id="New_Password_label" class="level_msg"></td>
</tr>
<tr height="50px">
<td width="100%" align="left" bgcolor="#CCCCCC"><span class="style14">Confirm New Password:</span></td>
<td align="center" bgcolor="#CCCCCC"><input type="password" name="Confirm_Password" id="Confirm_Password" size="20" autocomplete="off">&nbsp;&nbsp;&nbsp; <label id="Confirm_Password_label" class="level_msg"></td>
</tr>

<tr height="50px" bgcolor="#666666">
<td width="100%" colspan="2" align="center"><input type="Submit" name="Submit" value="Submit"/></td>
</tr>

</table>
<?php } ?>
<a href="channel1.php">Back</a>
</form>
<br>
<span class="style12"> <?php if(isset($Message)) { echo $Message; }  ?> </span>


</BODY>
</HTML>

<?php		
	// Include footer
	include("footer.php");
?>