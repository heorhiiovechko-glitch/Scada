
<?php
	error_reporting(0);	
	include("Includes.php");
	$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	
	if(isset($Cook_Variable)){
		$Username = $Cook_Variable[0];
		$Account_ID = $Cook_Variable[3];
	}	
?>
<?php
$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	
//print_r($Cook_Variable);
$FType=$Format_Type=$_REQUEST['FType'];
$Time_Arr = range(0,24);
foreach($Time_Arr as $Time_Val){
	$Str_Len = strlen($Time_Val);
	if($Str_Len == 1){
		$Time_Val = "0".$Time_Val;
	}
	$Time_24_Array["k".$Time_Val] = '';
}
//echo $Cook_Variable[3];
$Query_IMEI = base64_decode($_REQUEST['c1']);
		$Pocket_Length = $_REQUEST['l'];
		
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Versatilescada</title>

<script>
function myFunction(nam) {
	alert("Submitted Successfully");
	 document.getElementById(nam).disabled = true;
}

function myFunction1(nam) {
	alert(nam);
	 
}

function myFunction2(nam) {
	//alert(nam);
	setTimeout( ()=> { document.getElementById(nam).disabled = false; } ,6000);
}
</script>

<style>
.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 10px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  margin: 2px 2px;
  cursor: pointer;
  width: 65px;
}

.button2 {background-color: #008CBA;} /* Blue */
.button3 {background-color: #f44336;} /* Red */ 
.button4 {background-color: #f58108;} /* Gray */ 
.button5 {background-color: #555555;} /* Black */



input[type=text],input[type=password], select {
  width: 35%;
  padding: 12px 12px;
  margin: 6px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 20%;
  background-color: #4CAF50;
  color: white;
  padding: 12px 12px;
  margin: 6px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

</style>

</head>
<body align="right">
<div >
				<form  name="Form1" method="post" onsubmit="return">
				
					<select name="MO" id="MO" type="select">
						<option value="0" <?=($_REQUEST['MO'] == 0?'selected=selected' : '')?> >--Select Command--</option>
						<option value="1" <?=($_REQUEST['MO'] == 1?'selected=selected' : '')?> >START</option>
						<option value="2" <?=($_REQUEST['MO'] == 2?'selected=selected' : '')?> >PAUSE</option>
						<option value="3" <?=($_REQUEST['MO'] == 3?'selected=selected' : '')?> >STOP</option>	
						<option value="4" <?=($_REQUEST['MO'] == 4?'selected=selected' : '')?> >RESET</option>							
					</select>
					<input type="password" name="txtAuthentication" id="txtAuthentication" placeholder="Authentication" />
					<input type="submit" name="btnSubmit" id="btnSubmit" class="button" value="Submit" />
							
							
							<!--<input type="submit" name="myBtn" id="myBtn" class="button button2"
							value="Reset" />	
					<input type="submit" name="button1" id="button1" class="button"
							value="Start"/>
					  
					<input type="submit" name="button4" id="button4" class="button button4"
							value="Pause" />
							
					<input type="submit" name="button2" id="button2" class="button button3"
							value="Stop" />
							
							
					<input type="submit" name="button3" id="button3" class="button button2"
							value="Reset" />-->
							
					
					</div>		
						
													
				</form>
				
		
				<?php
				
				if (!isset($_POST["btnSubmit"])) {
					return;    
				} 
				
				if($_REQUEST['MO'] == 0){
					echo '<script type="text/javascript">myFunction1("Select The Command ");</script>';
					return;
				}
				
				$IMEI = $_REQUEST['c1'];
				
				$Database_Name = $_REQUEST['db'];
				//echo $Database_Name;  onclick="setTimeout(myFunction(), 3000);"
				$IMEI_Decode = base64_decode($IMEI);
				$Passwrd = $_REQUEST['txtAuthentication'];
				
				if($_REQUEST['MO'] > 0 && $Passwrd == '' ){
					echo '<script type="text/javascript">myFunction1("Enter The Authentication Password");</script>';
					return;//
				}
				
				//echo '<script type="text/javascript">myFunction1("Button Click ");</script>';
				
				//echo '<script type="text/javascript">myFunction1("'.$Passwrd.'");</script>';
			
				$MysqlQuery1 = "SELECT IMEI,db_name1 FROM va_master.device_register Where IMEI ='".$IMEI_Decode."'";
				//echo '<script type="text/javascript">myFunction1("'.$MysqlQuery1.'");</script>';
				
				if (!$Mysql_Query_Result1 = $db->query($MysqlQuery1) )
				{
					//echo '<script type="text/javascript">myFunction1("err1");</script>';
					die($db->error);
				}
				
				
				if($Mysql_Query_Result1->num_rows >= 0)
				{
					$Fetch_Result1 = $Mysql_Query_Result1->fetch_array(); 
					$PasswrdFromTable = $Fetch_Result1['db_name1'];
				}
				
				//$_SERVER["REQUEST_METHOD"] == "POST" && 
				
				if($Passwrd != '' && $PasswrdFromTable != $Passwrd){
					echo '<script type="text/javascript">myFunction1("Invalid Password");</script>';
					return;//
				}
			
				if($Passwrd != '' && $PasswrdFromTable == $Passwrd){
						
						if(isset($_POST['btnSubmit']) && $_REQUEST['btnSubmit']!='') {
							
							$var1 = "btnSubmit";
							$VarIndex=-1;
							if($_REQUEST['MO'] == 1)
							{
								$VarIndex = 0;
							}
							else if($_REQUEST['MO'] == 2)
							{
								$VarIndex = 1;
							}
							else if($_REQUEST['MO'] == 3)
							{
								$VarIndex = 3;
							}
							else if($_REQUEST['MO'] == 4)
							{
								$VarIndex = 2;
							}
							else 
							{
								$VarIndex = -1;
							}
							//echo '<script type="text/javascript">myFunction1("'.$VarIndex.'");</script>';
							$currentdate = date("Y-m-d H:i:s");	
							$Mysql_Query = "SELECT Count(*) as tot FROM ".$Database_Name.".device_status where IMEI = '".$IMEI_Decode."'";
							//echo $Mysql_Query;
							if (!$Mysql_Query_Result = $db->query($Mysql_Query) )
							{
								die($db->error);
							}
							
							//echo '<script type="text/javascript">myFunction1("'.$VarIndex.'");</script>';
							
							if($Mysql_Query_Result->num_rows >= 0 && $VarIndex >= 0)
							{
								
								$Fetch_Result = $Mysql_Query_Result->fetch_array(); 
								$tot = $Fetch_Result['tot'];
								//echo $tot;
								if($tot == "0")
								{
									$Mysql_Query = "Insert into ".$Database_Name.".device_status (IMEI, machine_state, machine_state_Z, Timestamp ) Values('".$IMEI_Decode."', '".$VarIndex."','".$VarIndex."', '".$currentdate."')";
									
											if (!$Mysql_Query_Result = $db->query($Mysql_Query))
											{
												die($db->error);
											}
											//echo "Reset Command Submitted for execution";
											echo '<script type="text/javascript">myFunction("'.$var1.'");</script>';
											echo '<script type="text/javascript">myFunction2("'.$var1.'");</script>';
								}
								elseif($tot > "0")
								{
									$Mysql_Query = "Update ".$Database_Name.".device_status SET machine_state= '".$VarIndex."' ,machine_state_Z = '".$VarIndex."' , Timestamp = '".$currentdate."' Where IMEI='".$IMEI_Decode."'";
									if (!$Mysql_Query_Result = $db->query($Mysql_Query))
											{
												die($db->error);
											}
											
											//echo "Reset Command Submitted for execution";
											echo '<script type="text/javascript">myFunction("'.$var1.'");</script>';
											echo '<script type="text/javascript">myFunction2("'.$var1.'");</script>';
								}
							}
							
							
							
						}
					}
			
			
			
				?>
				
				
				
	</body>
</html>