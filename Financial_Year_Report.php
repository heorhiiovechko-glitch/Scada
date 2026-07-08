	<?php
	$Year=$_REQUEST['inputYear1'];
//echo $Year;
		$YearArr=explode("-",$Year);
	
$Year_Start_dmy = "01-04-".$YearArr[0];
	$Year_End_dmy= "31-03-".$YearArr[1];
	$Year_Start=date("Y-m-d",strtotime($Year_Start_dmy));
	$Year_End=date("Y-m-d",strtotime($Year_End_dmy));
//echo $Year_End_dmy;
	$From_Year_D_Epoch = strtotime($Year_Start)+(60*60*5.5);
	$To_Year_D_Epoch = strtotime($Year_End." 23:59:59")+(60*60*5.5);
	
	
		if ($XLS == 0){
?>
		<tr>

			<td colspan="5" align="left" style="font-size:small">

				<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please click the below link to Download the excel Report</b><br /><br />

			<?php if($FType==1 || $FType==6){?>

				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='channel2_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==2){?>

				<a href='channel3_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>



			<?php  }if($FType==3){?>

				<a href='channel4_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==4){?>

				<a href='channel5_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			<?php  }if($FType==10){?>

				<a href='channel10_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			

			<?php }if($FType==7 || $FType==8){?>

				<a href='channel8_ajax.php?<?=$_SERVER['QUERY_STRING']?>&XLS=1' style='text-decoration:underline;font-weight:bold;'>Click here</a>

			

			<?php }?>

			</td>

		</tr>

<?php
	}
	?>
 
  <tr>
            <td width="100%">
                   <table width="90%" border="<?=$XLS == 1?"1":"0"?>" align="left" cellpadding="1" cellspacing="1" class="innertab1">	
				   <?php

$Device_Query="select Device_Name,Format_Type,hour(Closing_Time) as Closing_Time, Connect_Feeder,Site_Location,State,IMEI from device_register where IMEI='$IMEI'";
//echo $Device_Query;
		if (!$Device_Query_Result = $db->query($Device_Query))
            {
                die($db->error);
            }

            if($Device_Query_Result->num_rows >= 1)
            {
              while($Fetch_Result = $Device_Query_Result->fetch_array()) {

				$DGR_IMEI=$Fetch_Result['IMEI'];

				$Device_Name = $Fetch_Result['Device_Name'];

				$Site_Location = $Fetch_Result['Site_Location'];

				$Format_Type = $Fetch_Result['Format_Type'];
				$Closing_Time = $Fetch_Result['Closing_Time'];

				

			}

		}
	if ($XLS == 1){//xls=1
	?>

 <tr>

						<td  class="tab-head-tr"  colspan="14" align="left">&nbsp;&nbsp;<b></b></td>

					</tr>
					<tr>

		<td class="tab-head-td" colspan="14"  align="center"><b>Year Generation Detail Report- <?php echo $Year;?></b></td>

						</tr>

					   <tr>

							
<tr style="border:0px"><td colspan="14" >&nbsp;</td></tr>

<?php 

		}
			if ($XLS == 0){

					?>

					<tr>

						<td  class="tab-head-tr"  colspan="14" align="left">&nbsp;&nbsp;<b>Year Generation Detail Report -<?php echo $Year;?></b></td>

					</tr>

					<?php 

					}
		

		$Months=array("4"=>"Apr-".$YearArr[0]."", "5"=>"May-".$YearArr[0]."","6"=> "Jun-".$YearArr[0]."","7"=>"Jul-".$YearArr[0]."" ,"8" => "Aug-".$YearArr[0]."","9"=> "Sep-".$YearArr[0]."", "10"=> "Oct-".$YearArr[0]."", "11"=>"Nov-".$YearArr[0]."", "12"=>"Dec-".$YearArr[0]."","13"=>"Jan-".$YearArr[1]."","14"=>"Feb-".$YearArr[1]."","15"=>"Mar-".$YearArr[1]."");
 						//echo "Apr-".$_REQUEST['inputYear'];
		$Months_input=array("4"=>"$YearArr[0]-04", "5"=>"$YearArr[0]-05","6"=>"$YearArr[0]-06","7"=>"$YearArr[0]-07","8"=>"$YearArr[0]-08","9"=>"$YearArr[0]-09","10"=>"$YearArr[0]-10","11"=>"$YearArr[0]-11","12"=>"$YearArr[0]-12","13"=>"$YearArr[1]-01","14"=>"$YearArr[1]-02","15"=>"$YearArr[1]-03");
			?>
					<tr>			
						 <td class="tab-head-td" align="left"><b>Device Name</b></td>
		<?php
		for($Count=3;$Count<=14;$Count++){
			?>
						<td class="tab-head-td" align="left"><?=$Months[$Count+1]?></td>
						
						
		<?php
		}
				?>
				<td class="tab-head-td" align="left">Total</td>		
				</tr>
                     

<?php
	
			foreach($Months_input as $Month_input_val){	
	
	if($Format_Type==2 || $Format_Type==4)
		$Monthly_Generation_Query="select month(Date_S) as Month, sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as GAM_G1 from daily_data where  IMEI='$IMEI'and substr(Date_S,1,7)='".$Month_input_val."' group by month(Date_S)";
	elseif($Format_Type==7 || $Format_Type==8)
		$Monthly_Generation_Query="select month(Date_S) as Month, sum(Gen1_Max) as GAM_G1 from daily_data where  IMEI='$IMEI'and substr(Date_S,1,7)='".$Month_input_val."' group by month(Date_S)";
	else
		$Monthly_Generation_Query="select month(Date_S) as Month, sum(Gen1_Max-Gen1_Min) as GAM_G1 from daily_data where  IMEI='$IMEI'and substr(Date_S,1,7)='".$Month_input_val."' group by month(Date_S)";
	//echo $Monthly_Generation_Query;
			if (!$Monthly_Generation_Result = $db->query($Monthly_Generation_Query))
            {
                die($db->error);
            }
              while($Monthly_Generation_Fetch_Details = $Monthly_Generation_Result->fetch_array()) {
					$Monthly_Generation[$Monthly_Generation_Fetch_Details['Month']]=$Monthly_Generation_Fetch_Details['GAM_G1'];
		$Monthly_Generation[$Monthly_Generation_Fetch_Details['Month']]= substr(strval($Monthly_Generation[$Monthly_Generation_Fetch_Details['Month']]), 0, 1) != "-" && $Monthly_Generation[$Monthly_Generation_Fetch_Details['Month']] < '250000'? round($Monthly_Generation[$Monthly_Generation_Fetch_Details['Month']],0):'';
					$Month_Num[$Monthly_Generation_Fetch_Details['Month']]=$Monthly_Generation_Fetch_Details['Month'];
					$Total_Gen+=$Monthly_Generation[$Monthly_Generation_Fetch_Details['Month']];
					$Total_Gen=substr(strval($Total_Gen), 0, 1) != "-" && $Total_Gen<='6500000' ?round($Total_Gen,0):'000';
					}
		//}
	}
//print_r($Month_Num);
//print_r($Monthly_Generation);
					//echo "<td class='tab-head-td' align='left'>".$Total_Gen."</td></tr>";
			
					$Months_arr=array("Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","Jan","Feb","Mar");
					$Months_arr1=array("4","5","6","7","8","9","10","11","12","1","2","3");
			
			?>
							<tr>
						<td class="tab-head-td" align="left"><b><?=$Device_Name?></b></td>
						
					<?php
						//foreach($DGR_IMEI_Fin_Year as $IMEI_Val1){							
						foreach($Months_arr1 as $Month_val){
						//foreach($Months_input as $Month_input_val){
							//echo '<td class="tab-head-td" align="left">'.$Monthly_Generation.'</td>';
							?>
							<td class="tab-head-td" align="left"><?=$Monthly_Generation[$Month_val]?></td>
							<?php
							}
						echo '<td class="tab-head-td" align="left">'.round($Total_Gen,0).'</td>';
						
						echo "</tr>";						
					//}
					?>					
                   
				<?php 
						/*echo '<tr><td class="tab-head-td" align="left"><b>Month Total</b></td>';
						
					foreach($Months_arr as $Month_val){
						//foreach($DGR_IMEI_Fin_Year as $IMEI_Val1){
						$All_IMEI_Total_Gen+=$Monthly_Generation[$IMEI_Val1][$Month_val];
						$All_IMEI_Month_Gen+=$Total_Gen[$IMEI_Val1];
						//}
					echo '<td class="tab-head-td" align="left">'.round($All_IMEI_Total_Gen,0).'</td>';	
					
					$All_IMEI_Total_Gen=0;
					}
					echo '<td class="tab-head-td" align="left">'.round($All_IMEI_Month_Gen,0).'</td>';
					echo "</tr>";*/
						
				

echo "</table>";
//}
?>