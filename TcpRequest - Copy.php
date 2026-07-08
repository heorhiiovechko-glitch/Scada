
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
</style>

</head>
<body align="right" >
<div style="width:100%">
				<form  method="POST" >
					<input type="submit" name="button1" id="button1" class="button"
							value="Start"/>
					  
					<input type="submit" name="button4" id="button4" class="button button4"
							value="Pause" />
							
					<input type="submit" name="button2" id="button2" class="button button3"
							value="Stop" />
							
							
					<input type="submit" name="button3" id="button3" class="button button2"
							value="Reset" />
							
					
					</div>		
						<!--<input type="submit" name="myBtn" id="myBtn" class="button button2"
							value="Reset" />	-->
													
				</form>
				
		
				<?php
				
				$IMEI = $_REQUEST['c1'];
				
				$Database_Name = $_REQUEST['db'];
				//echo $Database_Name;  onclick="setTimeout(myFunction(), 3000);"
				$IMEI_Decode = base64_decode($IMEI);
				if($_SERVER["REQUEST_METHOD"] == "POST" ){
						
						if(isset($_POST['myBtn'])  && $_REQUEST['myBtn']!='') {
							$var1 = "myBtn";
							//echo date('h:i:s');
							  echo '<script type="text/javascript">myFunction("'.$var1.'");</script>';
							  echo '<script type="text/javascript">myFunction2("'.$var1.'");</script>';//{$var1}
						}
						if(isset($_POST['button1'])  && $_REQUEST['button1']!='') {
							 $var1 = "button1";
									$currentdate = 		date("Y-m-d H:i:s");	
									$Mysql_Query = "SELECT Count(*) as tot FROM ".$Database_Name.".device_status where IMEI = '".$IMEI_Decode."'";
									//echo $Mysql_Query;
									if (!$Mysql_Query_Result = $db->query($Mysql_Query))
									{
										die($db->error);
									}
									
									if($Mysql_Query_Result->num_rows >= 0)
									{
										
										$Fetch_Result = $Mysql_Query_Result->fetch_array(); 
										$tot = $Fetch_Result['tot'];
										//echo $tot;
										if($tot == "0")
										{
											$Mysql_Query = "Insert into ".$Database_Name.".device_status (IMEI, machine_state, machine_state_Z, Timestamp ) Values('".$IMEI_Decode."', '0','0', '".$currentdate."')";
											
													if (!$Mysql_Query_Result = $db->query($Mysql_Query))
													{
														die($db->error);
													}
													//echo "Start Command Submitted for execution";
													echo '<script type="text/javascript">myFunction("'.$var1.'");</script>';
													echo '<script type="text/javascript">myFunction2("'.$var1.'");</script>';
										
										}
										elseif($tot > "0")
										{
											$Mysql_Query = "Update ".$Database_Name.".device_status SET machine_state= '0' ,machine_state_Z = '0' , Timestamp = '".$currentdate."' Where IMEI='".$IMEI_Decode."'";
											if (!$Mysql_Query_Result = $db->query($Mysql_Query))
													{
														die($db->error);
													}
													
													//echo "Start Command Submitted for execution";
													echo '<script type="text/javascript">myFunction("'.$var1.'");</script>';
													echo '<script type="text/javascript">myFunction2("'.$var1.'");</script>';
										}
									}
									
									
								
						}
						
						
						if(isset($_POST['button2']) && $_REQUEST['button2']!='') {
							$var1 = "button2";
									$currentdate = 		date("Y-m-d H:i:s");	
									$Mysql_Query = "SELECT Count(*) as tot FROM ".$Database_Name.".device_status where IMEI = '".$IMEI_Decode."'";
									//echo $Mysql_Query;
									if (!$Mysql_Query_Result = $db->query($Mysql_Query))
									{
										die($db->error);
									}
									
									if($Mysql_Query_Result->num_rows >= 0)
									{
										
										$Fetch_Result = $Mysql_Query_Result->fetch_array(); 
										$tot = $Fetch_Result['tot'];
										//echo $tot;
										if($tot == "0")
										{
											$Mysql_Query = "Insert into ".$Database_Name.".device_status (IMEI, machine_state, machine_state_Z, Timestamp ) Values('".$IMEI_Decode."', '1','1', '".$currentdate."')";
											
													if (!$Mysql_Query_Result = $db->query($Mysql_Query))
													{
														die($db->error);
													}
													//echo "Stop Command Submitted for execution";
													echo '<script type="text/javascript">myFunction("'.$var1.'");</script>';
													echo '<script type="text/javascript">myFunction2("'.$var1.'");</script>';
										
										}
										elseif($tot > "0")
										{
											$Mysql_Query = "Update ".$Database_Name.".device_status SET machine_state= '1' ,machine_state_Z = '1' , Timestamp = '".$currentdate."' Where IMEI='".$IMEI_Decode."'";
											if (!$Mysql_Query_Result = $db->query($Mysql_Query))
													{
														die($db->error);
													}
													
													//echo "Stop Command Submitted for execution";
													echo '<script type="text/javascript">myFunction("'.$var1.'");</script>';
													echo '<script type="text/javascript">myFunction2("'.$var1.'");</script>';
										}
									}		
									
								
						}
						
						if(isset($_POST['button3']) && $_REQUEST['button3']!='') {
							
									$var1 = "button3";	
									$currentdate = 		date("Y-m-d H:i:s");	
									$Mysql_Query = "SELECT Count(*) as tot FROM ".$Database_Name.".device_status where IMEI = '".$IMEI_Decode."'";
									//echo $Mysql_Query;
									if (!$Mysql_Query_Result = $db->query($Mysql_Query))
									{
										die($db->error);
									}
									
									if($Mysql_Query_Result->num_rows >= 0)
									{
										
										$Fetch_Result = $Mysql_Query_Result->fetch_array(); 
										$tot = $Fetch_Result['tot'];
										//echo $tot;
										if($tot == "0")
										{
											$Mysql_Query = "Insert into ".$Database_Name.".device_status (IMEI, machine_state, machine_state_Z, Timestamp ) Values('".$IMEI_Decode."', '2','2', '".$currentdate."')";
											
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
											$Mysql_Query = "Update ".$Database_Name.".device_status SET machine_state= '2' ,machine_state_Z = '2' , Timestamp = '".$currentdate."' Where IMEI='".$IMEI_Decode."'";
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
						
						if(isset($_POST['button4']) && $_REQUEST['button4']!='') {
							
									$var1 = "button4";	
									$currentdate = 		date("Y-m-d H:i:s");	
									$Mysql_Query = "SELECT Count(*) as tot FROM ".$Database_Name.".device_status where IMEI = '".$IMEI_Decode."'";
									//echo $Mysql_Query;
									if (!$Mysql_Query_Result = $db->query($Mysql_Query))
									{
										die($db->error);
									}
									
									if($Mysql_Query_Result->num_rows >= 0)
									{
										
										$Fetch_Result = $Mysql_Query_Result->fetch_array(); 
										$tot = $Fetch_Result['tot'];
										//echo $tot;
										if($tot == "0")
										{
											$Mysql_Query = "Insert into ".$Database_Name.".device_status (IMEI, machine_state, machine_state_Z, Timestamp ) Values('".$IMEI_Decode."', '3','3', '".$currentdate."')";
											
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
											$Mysql_Query = "Update ".$Database_Name.".device_status SET machine_state= '3' ,machine_state_Z = '3' , Timestamp = '".$currentdate."' Where IMEI='".$IMEI_Decode."'";
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
					//return;
					  
				?>
				
				
				
	</body>
</html>