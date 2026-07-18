<?php
	include("Header.php");
	$Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));
	$Submit_Txt = $Title_Head."_Submit";
	$List_Page = str_replace('Add','List',$Title_Head);
	
	if(empty($_COOKIE[$Cook_Name])){
		header("Location: index.php");
		exit;
	}
	$IMEI = $_REQUEST['c1'];
	
	$db_name = $_REQUEST['db'];	
	//$IMEI_Decode = base64_decode($IMEI);
	$Format_Type=$_REQUEST['FType'];
$Date = date("Y-m-d");
				if($Format_Type == 1){
					$Table_Name = "device_data"; 
					$Date = date("Y-m-d");
					$Query_Date="Date_S='$Date'";														
				}elseif($Format_Type == 2){
					$Table_Name ="device_data_f2";
					$Date = date("Y-m-d");
					$Query_Date="Date_S='$Date'";														
				}elseif($Format_Type == 3){
					$Table_Name = "device_data_f3";
					$Date = date("Y-m-d");
					$Query_Date="Date_S='$Date'";										
				}elseif($Format_Type == 4){
					$Table_Name = "device_data_f4"; 
					$Date = date("Y-m-d");
					$Query_Date="Date_S='$Date'";					
				}elseif($Format_Type == 6){
					$Table_Name = "device_data_f6"; 
					$Date = date("Y-m-d");
					$Query_Date="Date_S='$Date'";					
				}elseif($Format_Type == 7){
					$Table_Name = "device_data_f7";
					$Query_Date="Date_S='$Date'";	 
				}elseif($Format_Type == 8){
					$Table_Name = "device_data_f8";
					$Query_Date="Date_S='$Date'";	 
				}elseif($Format_Type == 10){
					$Table_Name = "device_data_f10"; 
					$Query_Date="Date_S='$Date'";	
				}elseif($Format_Type == 11){
					$Table_Name = "device_data_f11";
					$Query_Date="Date_S='$Date'";
				}
?>


<?php

if($Format_Type == 1 ||$Format_Type == 6 ){
		$Query="select Date_S,max(PAT_Gen2) as Gen2_Max,min(PAT_Gen2) as Gen2_Min,IMEI,max(Run_Hours) as Hours_Max,min(Run_Hours) as Hours_Min from $db_name.$Table_Name WHERE IMEI='$IMEI' and $Query_Date limit 1 ";
}
if($Format_Type == 2){
		$Query="select Date_S,max(PAT_Gen1) as Gen1_Max,min(PAT_Gen1) as Gen1_Min,max(PAT_Gen2) as Gen2_Max,min(PAT_Gen2) as Gen2_Min,IMEI,max(Gen1_Hours) as Hours_Max,min(Gen1_Hours) as Hours_Min from $db_name.device_data_f2 WHERE IMEI='$IMEI' and   $Query_Date limit 1 ";
}
if($Format_Type == 3){
		$Query="select Date_S,max(Production_Total) as PT_Max,min(Production_Total) as PT_Min,IMEI,max(Total_Hours) as Hours_Max,min(Total_Hours) as Hours_Min from $db_name.$Table_Name WHERE IMEI='$IMEI' and   $Query_Date  limit 1";
}
if($Format_Type == 10){
		$Query="select Date_S,max(Production_Total) as PT_Max,min(Production_Total) as PT_Min,IMEI,max(Line_Hours) as Hours_Max,min(Line_Hours) as Hours_Min from $db_name.$Table_Name WHERE IMEI='$IMEI' and   $Query_Date  limit 1";
}
if($Format_Type == 11){
		$Query="select Date_S,max(tag_power) as PT_Max,min(tag_power) as PT_Min,IMEI,max(tag_windspd) as Hours_Max,min(tag_windspd) as Hours_Min from $db_name.$Table_Name WHERE IMEI='$IMEI' and   $Query_Date  limit 1";
}
if($Format_Type == 7 || $Format_Type == 8){
		$Query="select Date_S,max(Windspeed) as PT_Max,min(Windspeed) as PT_Min,IMEI,max(Power) as Hours_Max,min(Power) as Hours_Min from $db_name.$Table_Name WHERE IMEI='$IMEI' and   $Query_Date  limit 1";
}
if($Format_Type == 4){
		$Query="select Date_S,max(PAT_Gen1) as Gen1_Max,min(PAT_Gen1) as Gen1_Min,max(PAT_Gen2) as Gen2_Max,min(PAT_Gen2) as Gen2_Min,IMEI,max(Gen1_Hours) as Hours_Max,min(Gen1_Hours) as Hours_Min from $db_name.device_data_f4 WHERE IMEI='$IMEI' and   $Query_Date limit 1 ";
}

		//echo 	$Query;
		$Resultset=mysqli_query($db,$Query);
		$Row_Num=mysqli_num_rows($Resultset);//echo  $Raw_Row_Num;
			if($Row_Num>=1){
 ?>
<table border="0" cellpadding="4" cellspacing="1">

                    <tr>
			<td class="headings" width="80px" align="left">Date</td>

                        <td class="headings" width="80px" align="left">GEN1 Max</td>

                        <td class="headings" width="80px" align="left">GEN1 Min</td>

                        <td class="headings" width="90px" align="left">GEN2 Max</td>

                        <td class="headings" width="90px" align="left">GEN2 Min</td>

                        <td class="headings" width="80px" align="left">Production Total Max</td>

                        <td class="headings" width="80px" align="left">Production Total Min</td>
						<td class="headings" width="80px" align="left">Hours Max</td>

                        <td class="headings" width="80px" align="left">Hours Min</td>

</tr>
<?php
while($Result = mysqli_fetch_array($Resultset)){

			?>
			<tr><td class='Row_Td' align="center"><?=$Result['Date_S']?></td><td class='Row_Td' align="center"><?=$Result['Gen1_Max']?></td><td class='Row_Td' align="center"><?=$Result['Gen1_Min']?></td><td class='Row_Td' align="center"><?=$Result['Gen2_Max']?></td><td class='Row_Td' align="center"><?=$Result['Gen2_Min']?></td><td class='Row_Td' align="center"><?=$Result['PT_Max']?></td><td class='Row_Td' align="center"><?=$Result['PT_Min']?></td><td class='Row_Td' align="center"><?=$Result['Hours_Max']?></td><td class='Row_Td' align="center"><?=$Result['Hours_Min']?></td></tr>
			<?php
			
 
}
}
?>
</table>
<?php

if($Format_Type == 1 ||$Format_Type == 6 ){
		$Mysql_Query="select Record_Index,Date_S,Time_S,Date_F,Time_F,Gen1_Hours,Run_Hours,PAT_Gen0,PAT_Gen1,PAT_Gen2,IMEI from $db_name.$Table_Name WHERE IMEI='$IMEI' and   $Query_Date  ";
}
if($Format_Type == 2){
		$Mysql_Query="select Record_Index,Date_S,Time_S,Date_F,Time_F,Gen1_Hours,Gen2_Hours,Import_Kwh,PAT_Gen1,PAT_Gen2,IMEI from $db_name.device_data_f2 WHERE IMEI='$IMEI' and   $Query_Date  ";
}
if($Format_Type == 3){
		$Mysql_Query="select Record_Index,Date_S,Time_S,Date_F,Time_F,Gen1_Hours,Gen2_Hours,Import_Kwh,Production_Total,IMEI from $db_name.device_data_f3 WHERE IMEI='$IMEI' and   $Query_Date  ";
}
if($Format_Type == 10){
		$Mysql_Query="select Record_Index,Date_S,Time_S,Date_F,Time_F,PAT_Gen0,Gen1_Hours,Gen2_Hours,Line_Hours,Run_Hours,Production_Total,IMEI from $db_name.device_data_f10 WHERE IMEI='$IMEI' and   $Query_Date order by Time_S ";
}
if($Format_Type == 7 || $Format_Type == 8){
		$Mysql_Query="select Record_Index,Date_S,Time_S,Date_F,Time_F,Windspeed,Power,Status,IMEI from $db_name.$Table_Name WHERE IMEI='$IMEI' and $Query_Date order by Record_Index desc";
}
if($Format_Type == 11){
		$Mysql_Query="select Record_Index,Date_S,Time_S,tag_windspd,tag_power,tag_status,IMEI from $db_name.$Table_Name WHERE IMEI='$IMEI' and $Query_Date order by Record_Index desc";
}
if($Format_Type == 4){
		$Mysql_Query="select Record_Index,Date_S,Time_S,Date_F,Time_F,Gen1_Hours,Gen2_Hours,Import_Kwh,PAT_Gen1,PAT_Gen2,IMEI from $db_name.device_data_f4 WHERE IMEI='$IMEI' and   $Query_Date  ";
}

		//echo 	$Mysql_Query;
		$Raw_Resultset=mysqli_query($db,$Mysql_Query);
		$Raw_Row_Num=mysqli_num_rows($Raw_Resultset);//echo  $Raw_Row_Num;
			if($Raw_Row_Num>=1){
 ?>
<table border="1" cellpadding="4" cellspacing="1">

                    <tr>

                        <td class="headings" width="80px" align="left"></td>

                        <td class="headings" width="80px" align="left">Date_S</td>

                        <td class="headings" width="90px" align="left">Time_S</td>

                        <td class="headings" width="90px" align="left">PAT_Gen0</td>

                        <td class="headings" width="80px" align="left">PAT_Gen1</td>

                        <td class="headings" width="80px" align="left">PAT_Gen2</td>

                        <td class="headings" width="90px" align="left">Gen1_Hours</td>

                        <td class="headings" width="90px" align="left">Gen2_Hours</td>

                       <td class="headings" width="80px" align="left">Run_Hours</td>

                        <td class="headings" width="80px" align="left">Production_Total</td>

                        <td class="headings" width="90px" align="left">Line_Hours</td>

                        <td class="headings" width="90px" align="left">Import_Kwh</td>

                       

                    </tr>

                    <?php

       
			echo "<form   method=\"post\" action=\"Delete_Raw_Data.php\">";
			while($Fetch_Result = mysqli_fetch_array($Raw_Resultset)){

			?>
			<tr>
			<td align="left" class="Row_Td"><input type="checkbox" name=Record_Index[] id=Record_Index value="<?=$Fetch_Result['Record_Index']?>"/></td>
			<td class="Row_Td"><?=$Fetch_Result['Date_S']?></td><td class='Row_Td'><?=$Fetch_Result['Time_S']?></td>
			<td class="Row_Td"><?=$Fetch_Result['PAT_Gen0']?></td><td class='Row_Td'><?=$Fetch_Result['PAT_Gen1']?></td>
			<td class="Row_Td"><?=$Fetch_Result['PAT_Gen2']?></td><td class='Row_Td'><?=$Fetch_Result['Gen1_Hours']?></td>
			<td class="Row_Td"><?=$Fetch_Result['Gen2_Hours']?></td><td class='Row_Td'><?=$Fetch_Result['Run_Hours']?></td>
			<td class="Row_Td"><?=$Fetch_Result['Production_Total']?></td><td class='Row_Td'><?=$Fetch_Result['Line_Hours']?></td>
			<td class="Row_Td"><?=$Fetch_Result['Import_Kwh']?></td>
			<td><input type="hidden"  name=Format_Type id=Format_Type value="<?=$Format_Type?>"></td>
			<td><input type="hidden"  name=db_name id=db_name value="<?=$db_name?>"></td>
			<td><input type="hidden" id=Date  name=Date value="<?=$Date?>"></td>
			</tr>
			<?php
			
 
}
?>
			<input type="submit"  value="DELETE RAW DATA" name=Submit>
			</form>
			<?php
			}else
			echo "Records Not found";	
			exit;
			?>
			</table>	
					
					
		
	

				
				 <div style="clear:both"></div>
</div>	  
					<p>&nbsp;</p>
				

<?php if (!empty($_REQUEST['live'])) { ?>
<script>setTimeout(function(){ location.reload(); }, 30000);</script>
<?php } ?>
<?php include_once("Footer.php"); ?>

