<?php
	
	####################################
	#
	#	// For Text fields
	#	$Form_Fields[]= array($Element_Type,$Field_Type,$Field_Heading,$Field_Name,$Field_Value,$Manditary,$JValid,$Extra,$Class_Name,$Calender,$Editor,$Diff,$Diff_Column_Name);
	#	// Example Data
	#	$Form_Fields[]= array(1,1,Username,User_Name,'',*,E|N,'Txt_box','Style="color:red;"','Cal','E','F|T','Hid');
	#	  $Form_Fields[] = array('1','1','Filter','search','','','E|N|0','','Filter_box','','');
	#	$Element_Type 1 => 'text','password','radio',checkbox','submit','button','hidden','file','textarea'
	#	$Element_Type 2 => Dropdown - Select
	
	#	$Field_Type 1 => 'text',
	#	$Field_Type 2 => 'password',
	#	$Field_Type 3 => 'radio',
	#	$Field_Type 4 => 'checkbox',
	#	$Field_Type 5 => 'submit',
	#	$Field_Type 6 => 'button',
	#	$Field_Type 7 => 'hidden',
	#	$Field_Type 8 => 'file'
	
	#	$Manditary = '*' or ''
	#	$JValid = 'E|N' or 'E' or 'N'
	#	$Extra = anything you can add for that field ex : style='color:#33333' or onlick = jfunc()
	#	$Calender = 'Cal' or 'Cal_T' [Cal - Calender without time  ---  Cal_T - Calender with time ]
	#	$Editor = 'E' or '' [ Html text Editor  ]
	#	$Combo_Title = Dropdown Title
	#	$Match = which value we want to match and will selected too.
	#	$Diff = 'F' or 'T'  [F - From Date to search ] [T - To Date to search ]
	#	$Diff_Column_Name = which field should to search the date difference data
	#	
	#	// List fields
	#	$List_Fields[]= array($Field_Heading,$Field_Name,$Content_Limit,$Date_Format,$Array_Display);

	#   $Date_Format = 1 - 2010-01-01 10:10:10 (yyyy-mm-dd hh:ii:ss)
	#   $Date_Format = 2 - 01-01-2010 10:10:10 (yyyy-mm-dd hh:ii:ss)
	#   $Date_Format = 3 - 01-01-2010 (yyyy-mm-dd)
	#   $Date_Format = 4 - 10:10:10 (hh:ii:ss)
	#
	#	// For Select Event
	#	$Form_Fields[$No]= array($Element_Type,$Field_Type,$Field_Heading,$Field_Name,$Field_Value,$Manditary,$JValid,$Class_Name,$Extra,$Combo_Title,$Match);
	#	// For Select Example
	# 	$Form_Fields[] = array('1','3','Status','Sel',$Status_Array,'','1','','selectcls','onchange="Filter_Func(this.id,this.value,'.$Title_Head1.')"','-----Status-----','');
	#
	#
	################################################

	
		include("Header.php");
		$Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));
		
		$Submit_Txt = $Title_Head."_Submit";
		$List_Page = str_replace('Add','List',$Title_Head);
		if(empty($_COOKIE[$Cook_Name])){
			header("Location: index.php");
			exit;
		}
			
 # Getting IMEI
		$Mysql_Query = "select Device_Name,IMEI from device_register order by IMEI";
		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());
		$Mysql_Record_Count = mysql_num_rows($Mysql_Query_Result);
		if($Mysql_Record_Count>=1){
			while($Fetch_Result = mysql_fetch_array($Mysql_Query_Result)){
				$ALLIMEI_Array[$Fetch_Result['IMEI']] = $Fetch_Result['IMEI'];
				$ALLDevice_Array[$Fetch_Result['IMEI']] = $Fetch_Result['Device_Name'];
			}
		}
	
	############################################
	#
	# variable Declaration
	#
	############################################
 	
	$Records_Per_Page = 100;
	$Pagination_No_Count = 10;
	$Edit_ID_Column = "Record_Index"; 

	$Duplicate_Column = "";
	
	//Text boxes
	$Form_Fields[] = array('2','2','Device_Name','Device_Name',$ALLDevice_Array,'*','1','selectcls','','----- Select Device-----','');
	
	
	
	$Form_Fields[] = array('1','1','Date','Date','','*','E|N|0','','txtbox','Cal','');
	//$Form_Fields[] = array('1','7','IMEI','IMEI','$ALLIMEI_Array','*','1','selectcls','','----- Select IMEI-----','');
	
		//Submit Button
	$Form_Fields[] = array('1','5','',$Submit_Txt,'Get Raw Data','','','','submit_but','');

	// Join query - 
	$Query_To_Mysql_Join = "";
	
	###############################################     End      #########################################
 	
	
			
		
	// Javascript Validation	
	foreach($Form_Fields as $Forms){
		if($Forms[5] == '*'){
			$Jvalid_Arr[$Forms[3]].=$Forms[3].",";
			$Jvalid_Type_Arr[$Forms[6]].=$Forms[6].",";
		}
	}
	$Jvalid_Arr_Join = substr(join('',$Jvalid_Arr),0,-1);
	$Jvalid_Type_Arr_Join = substr(join('',$Jvalid_Type_Arr),0,-1);
	
	
	// After submit
	$Submit_Pos =count($Form_Fields) - 1;
	if(isset($_REQUEST[$Form_Fields[$Submit_Pos][3]])){
		$v =  0;
		$Record_Count == 0;
		foreach($Form_Fields as $Forms){
			
			// Make a Query Fields and Values
			if($Forms[1] != 5 && $Forms[1] != 6 && $Forms[1] != 8){
				$Forms_Field_Val[$Forms[3]] = $_REQUEST[$Forms[3]];
				$Query_Left.= $Forms[3].",";
				if($Forms[3] == 'Device_Name'){
					$IMEI=$_REQUEST[$Forms[3]];
				}
				if($Forms[9] == 'Cal_T'){
					$_REQUEST[$Forms[3]] =  date("Y-m-d H:i",strtotime($_REQUEST[$Forms[3]]));
					$Date=$_REQUEST[$Forms[3]];
				}
				if($Forms[9] == 'Cal'){
					$_REQUEST[$Forms[3]] =  date("Y-m-d",strtotime($_REQUEST[$Forms[3]]));
					$Date=$_REQUEST[$Forms[3]];
				}
				$Query_Right.= "'".$_REQUEST[$Forms[3]]."'".",";
			}	
			$v++;	
		}
		
			

		if(isset($_REQUEST[$Forms[3]])){
			$Device_Query="select Format_Type,db_name,IMEI from device_register where IMEI='$IMEI'";
			$Resultset=mysql_query($Device_Query);
			$Row_Num=mysql_num_rows($Resultset);
			if($Row_Num>=1){
			while($Fetch_Result = mysql_fetch_array($Resultset)){
			$Format_Type=$Fetch_Result['Format_Type'];
			$db_name=$Fetch_Result['db_name'];
			}
			}//echo $Format_Type." ".$Device_Query;
				if($Format_Type == 1){
					$Table_Name = "device_data"; 
			
				$Date = date("Y-m-d", strtotime($Date));
$Query_Date="Date_S='$Date'";														
				}elseif($Format_Type == 2){
					$Table_Name ="device_data_f2";

					$Date = date("Y-m-d", strtotime($Date));					$Query_Date="Date_S='$Date'";														
				}elseif($Format_Type == 3){
					$Table_Name = "device_data_f3";
					$Date = date("Y-m-d", strtotime($Date));			
					$Query_Date="Date_S='$Date'";										
				}elseif($Format_Type == 5){
					$Table_Name = "device_data_f5"; 
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";					
				}elseif($Format_Type == 6){
					$Table_Name = "device_data_f6"; 
					$Date = date("Y-m-d", strtotime($Date));
					$Query_Date="Date_S='$Date'";					
				}elseif($Format_Type == 7){
					$Table_Name = "device_data_f7";
					$Query_Date="Date_S='$Date'";	 
				}elseif($Format_Type == 9){
					$Table_Name = "device_data_f9";
					$Query_Date="Date_S='$Date'";	 
				}elseif($Format_Type == 10){
					$Table_Name = "device_data_f10"; 
					$Query_Date="Date_S='$Date'";	
				}
?>


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


		//echo 	$Mysql_Query;
		$Raw_Resultset=mysql_query($Mysql_Query);
		$Raw_Row_Num=mysql_num_rows($Raw_Resultset);//echo  $Raw_Row_Num;
			if($Raw_Row_Num>=1){
 ?>
	
		
		
	// For Delete	
	if(isset($_REQUEST['OptionDelete'])){
		$Edit_Req[]=explode('&Edit=',$_SERVER['QUERY_STRING']);
			
		for($D = 1;$D <= count($Edit_Req[0])-1; $D++){
			$Delete_Para[] = $Edit_Req[0][$D].",";
		}
		$Delete_Paras = substr(join ('',$Delete_Para),0,-1);
		$Delete_Query = "Delete from $Table_Name where $Edit_ID_Column in ($Delete_Paras)";
		$_GET['msg'] = Message_Display(Query_Executer($Delete_Query));
		$Delete_Query1 = "Delete from device_register where $Edit_ID_Column in ($Delete_Paras)";
		$_GET['msg'] = Message_Display(Query_Executer($Delete_Query1));
		$Title_Head1 = str_replace('_',' ',$Title_Head);
		Update_Query_Executer($Cook_Variable[1],''.$Title_Head1.' <b>'.$Delete_Paras.'</b> Deleted Successfully');
	}
	
	// For Edit
	if(isset($_REQUEST['OptionEdit'])){
		$Edit_Req =$_REQUEST['Edit'];
		header("Location: Edit_User.php?Edit=".$Edit_Req."&Edit_Column=".$Edit_ID_Column."");
		exit;
	}	

	// For View
	if(isset($_REQUEST['OptionView'])){
		$View_Req =$_REQUEST['Edit'];
		header("Location: ".$View_Page.".php?View=".$View_Req."&Edit_Column=".$Edit_ID_Column."");
		exit;
	}
	
?>

<?
if($XLS == 0){
?>
<div id="admin_div">
		<div id="admin_left">
		<p class="headings"><?=$Title_Head?></p>
			<table border="0" cellpadding="0" cellspacing="0" width="985" height="70" align="left" class="List_Tab">
				<tr>
					<td width="990" valign="top">
					<? 
						// Top Search Area					
					echo "<form action=\"\" name=\"".$Title_Head."\" onsubmit=\"return FormValid('$Jvalid_Arr_Join','$Jvalid_Type_Arr_Join')\">"; 
					?>
							<table border="0" cellpadding="0" cellspacing="0" width="980" height="40px" align="left">
								<tr><br />
									<td width="10"></td>
                                    <td valign="middle">
                                    	<table border="0" cellpadding="0" cellspacing="0">
										<?			
											foreach($Form_Fields as $Forms){
												if(empty($Forms[4]))
													$Forms[4] = $_REQUEST[$Forms[3]];
													// For Text boxes
													if( ($Forms[1] != 5 && $Forms[1] != 6) && ($Forms[1] != 3) ){
												?>
                                        	<tr>
												<td align="left" width="300px"><div style="float:left ">
														<div id="admin_fields1"><?=($Forms[2] != ''?$Forms[2] : $Forms[3])?><?=($Forms[5] == '*'?$star : '')?></div>
                                                    
												<?
                                                    // For Calendar Enabled Field
                                                if($Forms[9] == 'Cal'){
                                                ?>
												<script language="JavaScript" src="js/calendar_us.js"></script>
												<link rel="stylesheet" href="css/calendar.css">
												<?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
													<div class="Top_Cal"><script language="JavaScript">
													new tcal ({
														// form name
														'formname': '<?=$Title_Head?>',
														// input name
														'controlname': '<?=$Forms[3]?>'
													});
													</script></div><div class="date_format_top">( Format : mm/dd/yyyy )</div>
                                               <?
                                                }
                                                    // For Calendar Enabled Field
                                                elseif($Forms[9] == 'Cal_T'){
                                                ?>
                                                <script language="JavaScript" src="js/dcal.js"></script>
                                                <link rel="stylesheet" href="css/dcal.css">
                                                <?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
                                                <div class="Top_Cal"><input type="button"  class="cal_st" onClick="displayCalendar(document.forms[0].<?=$Forms[3]?>,'dd-mm-yyyy hh:ii',this,true)"></div>
                                                <div class="date_format_top">( Format : dd/mm/yyyy hh:mm)</div>
                                               <?
                                                }
												else{?>
													<?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
												<?
                                                }
                                                ?>
                                                    <?
														// Search Fields text
														if($Forms[2] == 'Filter'){
													?>
                                                    <div class="Search_Hint_txt">( Searches: <?=join(',',$Search_Fields)?>)</div>
                                                    <?
														}
                                                    ?>	
													<?=J_Mes($Forms[3]);?>
												</div></td>
                                                
                                                <?
											   		}
													// For Submit boxes
													elseif($Forms[1] == 5 || $Forms[1] == 6){
												?>
												<td align="left" valign="top">
													<div class="submbg_top"><?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?><div class="submbg1"></div><div style="clear:both"></div>
												</td></tr>
															
											   <? }
											   // For select Box 
											   elseif($Forms[1] == 3){
											   			// Auto Filter - when you select values from dropdown will give you result withput hit the sumbit button
											   			$Filter_Pos = strpos($Forms[9],'onchange="Filter_Func')."O";
														if($Filter_Pos == '0O'){
													?>						
												<tr>
                                                	<td colspan="2">
		                                                <div style="margin:10px 0 0 0;">
															<?=Func_Select_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[9],$Forms[10],$SearchD1[1]);?>
															<?=J_Mes($Forms[3]);?>
														</div>
                                                	</td>
                                                </tr>
                                                		<?
														}
														else{
															// Dropdown Filter
														?>
							
												<tr>
                                                	<td colspan="2">
		                                                <div style="margin:10px 0 0 0;">
															<?=Func_Select_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[9],$Forms[10],$_REQUEST[$Forms[3]]);?>
															<?=J_Mes($Forms[3]);?>
                                                	</td>
                                                </tr>
                                                		<?
														}
														?>
							
											   <? } ?>
												
										   <? } ?> 
                                           </td>
                                           </tr>
                                           </table>
                                        </td>   
									</form>	
                                    <?
									
                                    ###########  Top Icons ######################
									?>
                                    
						            <form name="List_Form">
                                        <td>
                                        		<table border="0" cellpadding="0" cellspacing="0" width="260px" height="50px" align="right">
                                                	<tr>	
                                                    	<? 
														if(isset($Icons_Control)){
																$Icons_Controls = explode('|',$Icons_Control);
														?>
                                                        <?
														 if($Icons_Controls[0] == 'D'){
														?>
                                                    	<td class="icon-bor" align="center" width="60px"><input type="submit" name="OptionDelete" value="" class="delete-icon" onclick="return Confirm_Message('You are about to delete the Record',this.form)" title="Delete" /><div style="margin:2px 0 0 0;"><div class="icon-txt">Delete</div></div></td>
														<?
														}
														 if($Icons_Controls[1] == 'E'){
														?>
                                                    	<td class="icon-bor" align="center" width="60px"><input type="submit" name="OptionEdit" value="" class="edit-icon" onclick="return anyCheck(this.form)" title="Edit" /><div style="margin:2px 0 0 0;"><div class="icon-txt">Edit</div></div></td>
														<?
														}
														 if($Icons_Controls[2] == 'V'){
														?>
                                                    	<td class="icon-bor" align="center" width="60px"><input type="submit" name="OptionView" value="" class="view-icon" onclick="return anyCheck(this.form)" title="View" /><div style="margin:2px 0 0 0;"><div class="icon-txt">View</div></div></td>
														<?
														}
														 if($Icons_Controls[3] == 'X'){
														?>
                                                    	<td class="icon-bor" align="center" width="60px"><a name="OptionExcel" href="<?=$Current_Url = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['PHP_SELF']."?XLS=1&".$_SERVER['QUERY_STRING'].""?>" class="excel-icon" title="Excel" /><div class="icon-txt"><img src="./images/icon-48-article-add.png" height="32" width="32" /><div style="margin:2px 0 0 0;">Excel</div></div></a></td>
														<?
														}
														 if($Icons_Controls[4] == 'P'){
														?>
                                                    	<td class="icon-bor" align="center" width="60px"><a name="OptionExcel" href="javascript:window.print()" class="excel-icon" title="Print" /><div class="icon-txt"><img src="./images/icon-48-print.png" height="32" width="32" /><div style="margin:2px 0 0 0;">Print</div></div></a></td>
													<?
														}
													}	
													?>                                                    	
                                                    </tr>
                                                </table>
                                        </td>
								</tr>
							</table>
					</td>
				</tr>
			</table>
		</div>
		<div style="clear:both"></div>
</div>

<?
} // XLS == 0 end
?>

<?
	if ($XLS == 1){
		$currDate = gmdate("d_M_Y");  
	 	$fName = $Title_Head."_".$currDate.".xls";
		$fName = urlencode($fName);
		header("Content-Type: application/vnd.ms-excel");
		header("Content-disposition: attachment;filename=$fName");
	}
?>
<?
if($XLS == 0){
?>	
<div id="admin_div"  style="border:0px solid red;">
			<div style="padding-left:0px;"><span class="msg"><?=$_GET['msg']?></span>
				<p class="headings" style="padding-left:10px;"></p>
<?
} // XLS == 0 end
?>			
                <table border="0" cellpadding="1" cellspacing="1" width="985" align="center">
				<?php
					include('ps_pagination.php');
					if($Edit_ID_Column)
						$Edit_Column = $Edit_ID_Column.",";
					if($Query_To_Mysql_Search)
						$Select_Mysql = $Query_To_Mysql_Search;
					else	
						$Select_Mysql = "SELECT * FROM $Table_Name where Parent_ID != '0' order by $Order_By";

					$Total_Records = mysql_num_rows (mysql_query($Select_Mysql));
					$Pager = new PS_Pagination($conn, $Select_Mysql, $Records_Per_Page, $Pagination_No_Count, "");
					$Record_Count = mysql_num_rows ($Pager->paginate());
					if($Record_Count){
						if($_GET[Sortto] == 'desc'){
							$Sortto = 'asc';
							$arrows = '<img src="images/down.gif" id="cdarrow" height="3" width="5" alt="Sort" class="arrow-img" />';
						}
						else{
							$Sortto = 'desc';
							$arrows = '<img src="images/up.gif" id="cdarrow" height="3" width="5" alt="Sort" class="arrow-img" />';
						}	
						
						if(!$_REQUEST['Page'])
							$_REQUEST['Page'] = 1;
				?>
				<?
                if($XLS == 0){
                ?>                
				<tr>
					<td align="right" colspan="<?=$Colspan_Count+2?>"><div class="total_admin">Total Records : <?=$Total_Records?><br /></div></td>	
				</tr>
				<?
                } // XLS == 0 end
                ?>
                <?
					if($XLS == 1){
						echo $Excel_Output_Head.="<tr><td colspan=".$Colspan_Count."><br><center><h3>$Title_Head On ".date('d-m-Y H:i:s')."</h3></center></td></tr>";
					}
				?>		

				<tr bgcolor="#F0F0F0" height="25px">
					<td class="admin-menu-heading">#</td>
                    <? if($XLS == 0){ ?>
					<td class="admin-menu-heading"></td>
                    <? } ?>
					<?
						foreach($List_Fields as $Fields){
							if($XLS == 0){
								 $List_Head_Url1 = ''.$List_Page.'.php?Page='.$_REQUEST['Page'].'&Sortby='.$Fields[1].'&Sortto='.$Sortto.'';
								if($_REQUEST['From']){
									 $List_Head_Url = $List_Head_Url1."&From=".$_REQUEST['From']."&To=".$_REQUEST['To']."&search=".$_REQUEST['search'];
								}
								else{
									  $List_Head_Url = $List_Head_Url1;
								}	  
								echo '<td class="admin-menu-heading"><div align="left"><a href="'.$List_Head_Url.'" title="Sort"><strong>'.$Fields[0].'</strong>&nbsp;'.($Fields[1] == $_REQUEST['Sortby']?$arrows : '').'</a></div></td>';
							}
							if($XLS == 1){
								echo '<td class="admin-menu-heading" style="text-align:left;" align="left"><div align="left"><strong>'.$Fields[0].'</strong></div></td>';
							}
						}
						if($XLS == 0){
						echo '<td class="admin-menu-heading"><div align="left"><a href="#" title="Options"><strong>Options</strong></a></div></td>';
						}
					?>
			  	</tr>
				<tr>
					<td colspan="<?=$Colspan_Count?>">&nbsp;</td>
				</tr>
                
                <?php
					 // For excel Report
 					$SNo = 1;
					$Record_Column = 0;
					if($XLS == 1){
						$Result = mysql_query($Select_Mysql);
						while($Fetch_Result = mysql_fetch_array($Result)){
							
							($Record_Column%2 == 1 ? $Row_Cls = 'Grey_Bg' : $Row_Cls = 'White_Bg');
							
							$Excel_Output.= "<tr height='30'>";
								echo "<td style='font-size:12px;' style='padding-right:15px;'>$SNo</td>";	
								foreach($List_Fields as $Fields){
										if($Fields[4]){
											$Fetch_Result[$Fields[1]] = $Fields[4][$Fetch_Result[$Fields[1]]];
										}	
										if($Fields[1] == 'IMEI'){
											$Fetch_Result[$Fields[1]] = $Fetch_Result[$Fields[1]]."&nbsp;";
										}	
										echo "<td style='font-size:12px;text-align:left'>";
										$Date_Val = Date_Format_Func($Fetch_Result[$Fields[1]],$Fields[3]);
										if(isset($Fields[2])){
											if($Fields[3] != ''){
												echo $Date_Val;
											}
											else{
												echo $Fetch_Result[$Fields[1]];
											}
										}
												
											//echo ($Fields[2]?$Fetch_Result[$Fields[1]] : (($Fields[3]?$Date_Val : $Fetch_Result[$Fields[1]])) );
										echo "</td>";	
								}
								echo "</tr>";
							
							$SNo++;
							$Record_Column++;
						}
						echo "<tr><td colspan=".$Colspan_Count." align='center' style='font-size:12px;'><br /><br /><<< Reports End >></td></tr>";				
					}				
				?>
                
				<?php
					if($XLS == 0){

					// Currently display Records
					$Result = $Pager->paginate();
					while($Fetch_Result = mysql_fetch_array($Result)){
						
						($Record_Column%2 == 1 ? $Row_Cls = 'Grey_Bg' : $Row_Cls = 'White_Bg');
						
						echo $Display_List[] = "<tr class='$Row_Cls' height='25'>";
							echo "<td class='Row_Td' style='padding-right:5px;' width='20px'>$SNo</td>";	
							echo "<td class='Row_Td' width='30px'><input type='checkbox' name='Edit' id='Edit' value='$Fetch_Result[$Edit_ID_Column]' onclick='setChecks(this)' ".($Fetch_Result[$Edit_ID_Column] == $_REQUEST['Edit']? 'Checked = Checked' : '')."></td>";	
							foreach($List_Fields as $Fields){
									echo "<td class='Row_Td'>";
										if($Fields[4]){
											$Fetch_Result[$Fields[1]] = $Fields[4][$Fetch_Result[$Fields[1]]];
										}	
										echo ($Fields[2]?substr($Fetch_Result[$Fields[1]],0,$Fields[2])." ..." : (($Fields[3]?Date_Format_Func($Fetch_Result[$Fields[1]],$Fields[3]) : $Fetch_Result[$Fields[1]])) );
									echo "</td>";	
							}								
							//add_user_icon.png								
							//user-add-48-Icon.jpg
							echo "<td style='font-size:12px;text-align:left'>";
							//echo "<a href='Add_User.php?P_ID=".$Fetch_Result['Account_ID']."' title='Add User'><img src='./images/user-add-48-Icon.jpg' height='20px' width='20px' title='Add User'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<a href='Add_Device.php?P_ID=".$Fetch_Result['Account_ID']."&P_ID1=".$Fetch_Result['Parent_ID']."' title='Add Device'><img src='./images/icon_question.gif' height='20px' width='20px' title='Add Device'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
							//echo "<a href='javascript:alert(\"Under Process...Coming Soon\")' title='Features'><img src='./images/icon_features_over.gif' height='20px' width='20px' title='Features'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<a href='List_User.php?P_ID=".$Fetch_Result['Account_ID']."' title='Detail View'><img src='./images/detail_icon.gif' height='20px' width='20px' title='Detail View'></a>";  
							echo "</td>";
							echo "</tr>";
						
						$SNo++;
						$Record_Column++;
					}
					
					echo "<tr>
						<td colspan=\"$Colspan_Count\"><br /><br />";
						echo "<div class='Page_align'>".$Pager->renderFullNav()."</div>";
					echo "	</td>
					</tr>";	
					} // Excel End				
				?>					
				<?php
				}
				else{
					echo "<tr>
							<td colspan='$Colspan_Count'>".error_report("Records Not Found")."</td>
						</tr>";
				}				
				?>
			</table>
            </form>
			<br />
</div> 
		</div>		
				<div style="clear:both"></div>
		</div>
  
<?php if($XLS == 0) include("Footer.php"); ?>	

<?php 
if($XLS == 1){
	echo $Excel_Output;
}
?>