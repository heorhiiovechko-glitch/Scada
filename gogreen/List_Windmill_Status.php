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
?>

<style>
.Device_Status_Red{
	padding:2px 2px 2px 5px;
	font-size:11px;
	text-align:left;
	background-color:red;
	width:30px;
}
.Device_Status_Red1{
	padding:2px 2px 2px 5px;
	font-size:11px;
	text-align:left;
	background-color:red;
	width:30px;
}
.Device_Status_Blue{
	padding:2px 2px 2px 5px;
	font-size:11px;
	text-align:left;
	background-color:blue;
	width:30px;
}
.Device_Status_Green{
	padding:2px 2px 2px 5px;
	font-size:11px;
	text-align:left;
	background-color:green;
	width:30px;
}
.Device_Status_Orange{
	padding:2px 2px 2px 5px;
	font-size:11px;
	text-align:left;
	background-color:orange;
	width:30px;
}
.Device_Status_Grey{
	padding:2px 2px 2px 5px;
	font-size:11px;
	text-align:left;
	background-color:grey;
	width:30px;
}
</style>

<?php
	if (isset($_REQUEST["XLS"])){$XLS=1;}else{$XLS=0;}
		include("Header.php");
		$Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));
		$Title_Head1 = "'$Title_Head'";
		$Submit_Txt = $Title_Head."_Submit";
		$List_Page = str_replace('Add','List',$Title_Head);
		$Edit_Page = str_replace('List','Edit',$Title_Head);
		$View_Page = str_replace('List','View',$Title_Head);
		if(empty($_COOKIE[$Cook_Name])){
			header("Location: index.php");
			exit;
		}
		// Search variable Declaration	- Dont Touch
		//$Form_Fields[] = array('1','1','Filter','search','','','E|N|0','','Filter_box','','');
		//Submit Button - Dont Touch
		//$Form_Fields[] = array('1','5','',$Submit_Txt,'Search','','','','submit_but','','');
		$Form_Fields = array();
	
		switch($Cook_Variable[2]){
			case 1:
				$Icons_Control = "D|E|V|X|P";		
				break;
			case 2:
				$Icons_Control = "|E||X|P";		
				break;
			case 4:
				$Icons_Control = "||V|X|P";		
				break;
			case 5:
				$Icons_Control = "||V|X|P";		
				break;
			case 7:
				$Icons_Control = "||V|X|P";		
				break;
		}
  
	############################################
	#
	# variable Declaration
	#
	############################################
 	
	$Table_Name = "device_register";
	$Order_By = "Device_Index desc"; // Column name to be order and which order (asc or desc)
	$Records_Per_Page = 100;
	$Pagination_No_Count = 20;
	$Edit_ID_Column = "Device_Index"; // Unique value should be give for whatever query you do in that table
	$Db_Column = "db_name";
	// Search Area Customization	
	$Search_Fields = array('IMEI','SIM_No','Phone_No','Status','Date_Stamp');

	//List Fields
	$List_Fields[] = array('Device_Name','Device_Name','','','');
	$List_Fields[] = array('Date','Date','','','');
	$List_Fields[] = array('Time','Time','','','');
	$List_Fields[] = array('GAD','PAT_Gen1','','','');
	$List_Fields[] = array('Status','Status','','',$Status_Array);
		

	###############################################     End      #########################################
 
	#
	#	Error Status from ERROR_TYPE
	#
	$Mysql_Query = "select * from error_type";
	$Mysql_Query_Result = mysqli_query($db,$Mysql_Query) or die(mysqli_connect_error());
	$Mysql_Record_Count = mysqli_num_rows($Mysql_Query_Result);
	if($Mysql_Record_Count>=1){
		while($Fetch_Result = mysqli_fetch_array($Mysql_Query_Result)){
			$Error_Array[$Fetch_Result['Machine_Status']][] = $Fetch_Result['Error'];
			$Machine_Status_Array[$Fetch_Result['Machine_Status']] = $Fetch_Result['Machine_Status'];
		}
	}



	
	// List_Fileds Parse and made array 
	foreach($List_Fields as $Fields){
		$List_Fields_Col[] = $Fields[1];
	}	
	$Colspan_Count = count($List_Fields_Col)+2;
	//Filter columns
	$Mysql_Select_Column = join(',',$List_Fields_Col);
	if($_REQUEST['Sortto'])
		$Order_By = $_REQUEST['Sortby']." ".$_REQUEST['Sortto'];
	else
		$Order_By = $Order_By;

		
		
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
	$Submit_Pos = count($Form_Fields) - 1;
	if( $Submit_Pos >= 0 && ( (isset($_REQUEST[$Form_Fields[$Submit_Pos][3]])) || (isset($_REQUEST['SearchD'])) ) ){
		$v =  0;
		$Record_Count == 0;
		foreach($Search_Fields as $Search){
			if(!empty($_REQUEST['search']))
				$Where_Querys.= $Search." like '%".$_REQUEST['search']."%' or ";
			$v++;	
		}
		$Where_Query= substr("where ".$Where_Querys."",0,-3);
		
		// Dropdown Search Script- AutoFetch
		if(isset($_REQUEST['SearchD'])){
			$SearchD1 = explode('&',$_SERVER['QUERY_STRING']);
			$SearchD1 = explode('=',$SearchD1[1]);
			$Where_Query= "where ".$SearchD1[0]." = '".urldecode($SearchD1[1])."'";
		}	

		// Dropdown Search Script
		if(isset($_REQUEST['search'])){
			$SearchD1 = explode('&',$_SERVER['QUERY_STRING']);
			foreach($SearchD1 as $SearchD2){
				$Sub_Omit = strpos($SearchD2,$Submit_Txt)."O";
				//for excel report
				if($SearchD2 == 'XLS=1'){
					$SearchD2 = '';
				}
				if($Sub_Omit != "0O"){
					$SearchD3 = explode('=',$SearchD2);
					// Search Textbox script
					if($SearchD3[0] == 'search'){
						if(!empty($Where_Querys))
							$Where_Querys = "(".substr($Where_Querys,0,-3).") ";
					}
					if(($SearchD3[1] != '0') && ($SearchD3[0] != 'search')){
						// For Calender searchj script
						foreach($Form_Fields as $Forms){
						
							//For Calender Search
							if( ($SearchD3[0] == $Forms[3]) && ($Forms[9] == 'Cal')){
								if(!empty($_REQUEST[$Forms[3]])){
									$SearchD3[0] = "Date(".$Forms[3].")";
									$SearchD3[1] = date("Y-m-d",strtotime($_REQUEST[$Forms[3]]));
								}
							}		
							
							//For Calender Search with Time
							if( ($SearchD3[0] == $Forms[3]) && ($Forms[9] == 'Cal_T')){
								if( (($Forms[11] == 'F') || ($Forms[11] == 'T')) && (!empty($_REQUEST[$Forms[3]])) ){
									$Diff_Date_Que = "DATE_FORMAT($Forms[12],'%Y-%m-%d %H:%i' ) between ";
									$Diff_Date_Que1 .= "'".date("Y-m-d H:i",strtotime($_REQUEST[$Forms[3]])). "' and ";
									$SearchD3[0] = "";
									$SearchD3[1] = "";
								}
								else{
									if(!empty($_REQUEST[$Forms[3]])){
										$SearchD3[0] = "DATE_FORMAT($Forms[3],'%Y-%m-%d %H:%i' )";
										$SearchD3[1] = date("Y-m-d H:i",strtotime($_REQUEST[$Forms[3]]));
									}	
								}	
							}		
							
						}
						//Value should not empty	
						if(!empty($SearchD3[1])){
								if($SearchD3[0] != 'Page' && $SearchD3[0] != 'Sortby' && $SearchD3[0] != 'Sortto' && $SearchD3[0] != 'P_ID'){
									$Cond_Para .= " and ".$SearchD3[0]." = '".$SearchD3[1]."' ";
								}	
						}	
					}	
				}
			}
			
	
			// Date Difference Query 
			if( (!empty($Diff_Date_Que)) && (!empty($Diff_Date_Que1)) ){
				if(!empty($Cond_Para))
					$And = " and ";
					$Diff_Date_Querys = $And ."".substr($Diff_Date_Que." ".$Diff_Date_Que1,0,-4);
			}
			// Fields is empty	
			if(empty($Where_Querys))
				$Cond_Para = substr($Cond_Para,4)." ".$Diff_Date_Querys;
				
			$Where_Query_Combind =  $Where_Querys." ".$Cond_Para;
			$Where_Query= "where ".$Where_Query_Combind."";
			
		}	
		
			// Query For Search	
			if(trim($Where_Query) == 'where')
				$Where_Query = '';
			$Query_To_Mysql_Search = "SELECT * FROM $Table_Name $Where_Query order by $Order_By";
			/*$Query_To_Mysql_Result = mysql_query($Query_To_Mysql_Search);
			if(!$Query_To_Mysql_Result){
				echo $Err_Msg = Message_Display("Mysql Error :");
				echo $Err_Msg = "<div class='error_text1'>".mysql_error()."</div>";
			}*/
	}
	
	// For Delete	
	if(isset($_REQUEST['OptionDelete'])){
		$Edit_Req[]=explode('&Edit=',$_SERVER['QUERY_STRING']);
			
		for($D = 1;$D <= count($Edit_Req[0])-1; $D++){
			$Delete_Para[] = $Edit_Req[0][$D].",";
		}
		$Delete_Paras = substr(join ('',$Delete_Para),0,-1);
		$Delete_Query = "Delete from $Table_Name where $Edit_ID_Column in ($Delete_Paras)";
		$_GET['msg'] = Message_Display(Query_Executer($Delete_Query));
		$Title_Head1 = str_replace('_',' ',$Title_Head);
		Update_Query_Executer($Cook_Variable[1],''.$Title_Head1.' <b>'.$Delete_Paras.'</b> Deleted Successfully');
	}
	
	// For Edit
	if(isset($_REQUEST['OptionEdit'])){
		$Edit_Req =$_REQUEST['Edit'];
		header("Location: Edit_Device.php?Edit=".$Edit_Req."&Edit_Column=".$Edit_ID_Column."");
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
                                           <input type="hidden" name="P_ID" id="P_ID" value="<?=$_REQUEST['P_ID']?>" />
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
                <?
					if(isset($_REQUEST['P_ID'])){
						$P_ID = $_REQUEST['P_ID'];
				?>
                <tr>
                	<td align="center">
                        <table border="0" cellpadding="3" cellspacing="1" width="980" height="40px" align="left" class="info_tab">
                            <tr>
                                <td width="120px;" style="padding-left:10px" class="info_tb"><b>Account Name :</b> </td>
                                <td width="250px;" align="left" class="info_tb"><?=$Firstname_Lastname_Array[$P_ID];?> (<?=$Username_Array[$P_ID];?>)</td>
                                <td width="80px;" align="center" class="info_tb"><a href="List_User.php?P_ID=<?=$P_ID?>">List User</a></td>
                                <td width="500px" align="right">
                                <?
									echo "<a href='Add_User.php?P_ID=".$_REQUEST['P_ID']."' title='Add User'><img src='./images/user-add-48-Icon.jpg' height='20px' width='20px' title='Add User'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
									echo "<a href='Add_Device.php?P_ID=".$_REQUEST['P_ID']."' title='Add Vehicle'><img src='./images/icon_question.gif' height='20px' width='20px' title='Add Vehicle'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
									echo "<a href='javascript:alert(\"Under Process...Coming Soon\")' title='Features'><img src='./images/icon_features_over.gif' height='20px' width='20px' title='Features'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
									echo "<a href='List_User.php?P_ID=".$_REQUEST['P_ID']."' title='Detail View'><img src='./images/detail_icon.gif' height='20px' width='20px' title='Detail View'></a>";
				
								?>
                                </td>
                            </tr>   
                        </table>    
                    </td>
                </tr>
                <?
					}
				?>
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
          <?
if($XLS == 0){
?>
      <table border="0" cellpadding="1" cellspacing="1" width="900" align="center">
<?php
}
?>
 <?
if($XLS == 1){
?>
      <table border="1" cellpadding="1" cellspacing="1" width="900" align="center">
<?php
}
?>
				<?php
					include('ps_pagination.php');
$Db_Name_Column = $Db_Column.",";
					if($Edit_ID_Column)
						$Edit_Column = $Edit_ID_Column.",";
						
					if($Query_To_Mysql_Search)
						$Select_Mysql = $Query_To_Mysql_Search;
					else	
						$Select_Mysql = "SELECT Device_Index,IMEI,Device_Name,Format_Type,Account_ID,Parent_ID,db_name,Closing_Time FROM $Table_Name order by db_name, Device_Name";
//echo $Select_Mysql;
					$Total_Records = mysqli_num_rows (mysqli_query($db,$Select_Mysql));
					$Pager = new PS_Pagination($db, $Select_Mysql, $Records_Per_Page, $Pagination_No_Count, "");
					$Record_Count = mysqli_num_rows ($Pager->paginate());
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
					<td align="right" colspan="<?=$Colspan_Count?>"><div class="total_admin">Total Records : <?=$Total_Records?><br /></div></td>	
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
								 $List_Head_Url1 = ''.$List_Page.'.php?Page='.$_REQUEST['Page'].'&Sortby='.$Fields[1].'&Sortto='.$Sortto.'&P_ID='.$_REQUEST['P_ID'].'';
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
					?>
                    <?
					if($XLS == 0){
					?>
                    
					<!-- <td class="admin-menu-heading"><div align="left"><a href="" title="Sort"><strong>Options</strong>&nbsp;</a></div></td> -->
                    <?
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
						$Result = mysqli_query($db,$Select_Mysql);
						while($Fetch_Result = mysqli_fetch_array($Result)){
							
							($Record_Column%2 == 1 ? $Row_Cls = 'Grey_Bg' : $Row_Cls = 'White_Bg');
						$IMEI = $Fetch_Result['IMEI'];
						$Db_Name = $Fetch_Result['db_name'];
						$Closing_Time = $Fetch_Result['Closing_Time'];
	###########################################################################
	
						
						$Format_Type = $Fetch_Result['Format_Type'];
						
						if($Format_Type == 1){
										$Channel_Url = "channel2.php?";
										$Table_Name = "device_data"; 
										$Error_Table_Name = "error_data"; 
									}elseif($Format_Type == 2){
										$Channel_Url = "channel3_new.php?";
										$Table_Name ="device_data_f2";									
										$Error_Table_Name = "error_data_f2"; 
									}elseif($Format_Type == 3){
										$Channel_Url = "channel4.php?";
										$Table_Name = "device_data_f3";									
										$Error_Table_Name = "error_data_f3";
									}elseif($Format_Type == 4){
										$Channel_Url = "channel9_new.php?";
										$Table_Name = "device_data_f4"; 
										$Error_Table_Name = "error_data_f4"; 
									}elseif($Format_Type == 6){
										if($Parent_ID==30 && $User_Type_ID ==4)
										$Channel_Url = "channel11.php?";
										else
										$Channel_Url = "channel7.php?";
										$Table_Name = "device_data_f6"; 
										$Error_Table_Name = "error_data_f6"; 
									}elseif($Format_Type == 7){
										$Channel_Url = "channel8.php?";
										$Table_Name = "device_data_f7"; 
										$Error_Table_Name = "error_data_f7"; 
									}elseif($Format_Type == 8){
										$Channel_Url = "channel8.php?";
										$Table_Name = "device_data_f8"; 
										$Error_Table_Name = "error_data_f8";  
									}elseif($Format_Type == 10){
										$Channel_Url = "channel10.php?";
										$Table_Name = "device_data_f10"; 
										 $Error_Table_Name = "error_data_f10"; 
									}
								
						#
						#	Alarm Log from ERROR_DATA
						#
						$ED_Mysql_Query = "select * from $Db_Name.$Error_Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";
						$ED_Mysql_Query_Result = mysqli_query($db,$ED_Mysql_Query) or die(mysqli_connect_error());
						$ED_Mysql_Record_Count = mysqli_num_rows($ED_Mysql_Query_Result);
						if($ED_Mysql_Record_Count>=1){
							$ED_Fetch_Result = mysqli_fetch_array($ED_Mysql_Query_Result);
							$ED_Error_Array[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Status'];
							$ED_Error_Date[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Date_S'];
							$ED_Error_Time[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Time_S'];

$ED_Device_Epoch_Time_Array[$ED_Fetch_Result['IMEI']] = GetTimestamp($ED_Error_Date[$ED_Fetch_Result['IMEI']],$ED_Error_Time[$ED_Fetch_Result['IMEI']]); 

						}

							if($Closing_Time=='06:00:00' || $Closing_Time=='06:30:00'){
										$GAD_Time=" and Hour(Time_S)>=6 ";
										$GD_Time=time()-21660;
}
								elseif($Closing_Time=='07:00:00' || $Closing_Time=='07:30:00'){
										$GAD_Time=" and Hour(Time_S)>=7 ";
										$GD_Time=time()-25200;
}								elseif($Closing_Time=='08:00:00' || $Closing_Time=='08:30:00'){
										$GAD_Time=" and Hour(Time_S)>=8 ";
										$GD_Time=time()-28800;
}								elseif($Closing_Time=='09:00:00'){
										$GAD_Time=" and Hour(Time_S)>=9 ";
										$GD_Time=time()-32400;
}								elseif($Closing_Time=='01:00:00' || $Closing_Time=='01:30:00'){
										$GAD_Time=" and Hour(Time_S)>=1 ";
										$GD_Time=time()-3600;
}								elseif($Closing_Time=='02:00:00' || $Closing_Time=='02:30:00'){
										$GAD_Time=" and Hour(Time_S)>=2 ";
										$GD_Time=time()-7200;
}								else {
										$GAD_Time="";
										$GD_Time=time();
$Test_Time=date('H',$GD_Time);
}
		if($Format_Type==2 || $Format_Type==4)
		$Mysql_Query1 ="select Date_S,Time_S,Status  ,(select round((max(PAT_Gen1)-min(PAT_Gen1))+(max(PAT_Gen2)-min(PAT_Gen2)),2) from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() $GAD_Time) as G1 from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";	
		elseif($Format_Type==6 || $Format_Type==1)
		$Mysql_Query1="select Date_S,Time_S,Status,(select max(PAT_Gen2)-min(PAT_Gen2) from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() $GAD_Time) as G1  from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";
		elseif($Format_Type==10 || $Format_Type==3)
		$Mysql_Query1 ="select Date_S,Time_S,Status,(select max(Production_Total)-min(Production_Total) from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() $GAD_Time) as G1 from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";
		elseif($Format_Type==7 || $Format_Type==8)
		$Mysql_Query1 ="select Status,Date_S,Time_S,Kwh_Positive G1 from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() order by Record_Index desc limit 1";
									
					
						//$Mysql_Query1 = "select Date_S,Time_S,Status from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";			
	//echo $Mysql_Query1;
						$Mysql_Query_Result1 = mysqli_query($db,$Mysql_Query1) or die(mysqli_connect_error());									
						$Mysql_Record_Count1 = mysqli_num_rows($Mysql_Query_Result1);
						if($Mysql_Record_Count1 >= 0){
							$Fetch_Result1 = mysqli_fetch_array($Mysql_Query_Result1); 											
							
							$Status1 = trim($Fetch_Result1['Status']);
							$Status = strtolower($Status1);
							$Date_F = $Fetch_Result1['Date_S'];
										$Time_F = $Fetch_Result1['Time_S'];	
							$GAD = $Fetch_Result1['G1'];	
							if($Format_Type == 2 || $Format_Type == 4){								
								if($GAD > 6000 || $GAD < 0)
									$Device_Status_Class1 = "Device_Status_Red1";
								else
									$Device_Status_Class1 = "Device_Status_Green";
							}
							if($Format_Type == 1  ||  $Format_Type == 6){								
								if($GAD > 25000 || $GAD < 0)
									$Device_Status_Class1 = "Device_Status_Red1";
								else
									$Device_Status_Class1 = "Device_Status_Green";
							}
							if($Format_Type == 7  ||  $Format_Type == 8){								
								if($GAD > 25000 || $GAD < 0)
								$Device_Status_Class1 = "Device_Status_Red1";
							else
									$Device_Status_Class1 = "Device_Status_Green";
							}
							if($Format_Type == 3  ||  $Format_Type == 10){	
								if($GAD > 25000 || $GAD < 0)
								$Device_Status_Class1 = "Device_Status_Red1";
							else
									$Device_Status_Class1 = "Device_Status_Green";
							}
							
									

$Device_Epoch_Time=GetTimestamp($Date_F,$Time_F);

							if(!empty($Device_Epoch_Time) && !empty($ED_Device_Epoch_Time_Array[$IMEI])){
								$Diff_Error_Status = $Device_Epoch_Time  - $ED_Device_Epoch_Time_Array[$IMEI];
								if(0 > $Diff_Error_Status){
									$Status = $ED_Error_Array[$IMEI];
								}
							}
							# More than 3 hours not working
							$Req_Time = time()+(60*60*5.5);
							$ReqTime_Diff = $Req_Time - $Device_Epoch_Time;
							$ReqTime_Diff_err = $Req_Time - $EDbn_Device_Epoch_Time_Array[$IMEI];
							if($ReqTime_Diff >= 900 && $ReqTime_Diff_err >= 900 && (in_array($Status,$Error_Array['Green']) && !in_array($Status,$Error_Array['Blue']))){
								$Device_Status_Class = "Device_Status_Grey";
								$Tower_Img = '<img src="./images/grey.gif" width="20px" height="20px" alt="Yellow Tower">';
							}
							else
							{
								# Depence upon the Status Tower image
								if(in_array($Status,$Error_Array['Green'])){
									if($Power == '000' || $Power == '0'){
										$Device_Status_Class = "Device_Status_Orange";
										$Tower_Img = '<img src="./images/orange_tower.jpg" width="20px" height="20px" alt="Orange Tower">';
									}
									else{
										$Device_Status_Class = "Device_Status_Green";
										$Tower_Img = '<img src="./images/green_tower.jpg" width="20px" height="20px" alt="Orange Tower">';
									}	
								}	
								elseif(in_array($Status,$Error_Array['Orange'])){
									$Device_Status_Class = "Device_Status_Orange";
									$Tower_Img = '<img src="./images/orange_tower.jpg" width="20px" height="20px" alt="Orange Tower">';
								}	
								elseif(in_array($Status,$Error_Array['Blue'])){
									$Device_Status_Class = "Device_Status_Blue";
									$Tower_Img = '<img src="./images/blue_tower.jpg" width="20px" height="20px" alt="Blue Tower">';
								}
								elseif(in_array($Status,$Error_Array['Pink'])){
									$Device_Status_Class = "Device_Status_Pink";
									$Tower_Img = '<img src="./images/pink_tower.jpg" width="20px" height="20px" alt="Blue Tower">';
								}
								else{											
									$Device_Status_Class = "Device_Status_Red";
									$Tower_Img = '<img src="./images/red_tower1.jpg" width="20px" height="20px" alt="Red Tower">';
								}	
							}
						}
	
							$Date="";
							$Time="";
							$Date_e = strtotime($ED_Error_Date[$IMEI]);
							$Time_e = strtotime($ED_Error_Time[$IMEI]);
							$Date_G = strtotime($Date_F);
							$Time_G = strtotime($Time_F);

	if(in_array($Status,$Error_Array['Green'])){
if ($Date_G < $Date_e) {
$Date = date('d/m/Y', $Date_e);
							$Time = date('H:i:s', $Time_e);
$Date_YMD = date('d_m_Y', $Date_e);
							$Time_YMD = date('H:i:s', $Time_e);							

} else {

							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);
					$Date_YMD = date('d_m_Y', $Date_G);
							$Time_YMD = date('H:i:s', $Time_G);
}							
								
							}
elseif(in_array($Status,$Error_Array['Blue']) || in_array($Status,$Error_Array['Pink'])) {

$Date = date('d/m/Y', $Date_e);
							$Time = date('H:i:s', $Time_e);							
$Date_YMD = date('d_m_Y', $Date_e);
							$Time_YMD = date('H:i:s', $Time_e);
							
						
							}

else {
if ($Date_G < $Date_e) {
$Date = date('d/m/Y', $Date_e);
$Time = date('H:i:s', $Time_e);
$Date_YMD = date('d_m_Y', $Date_e);
							$Time_YMD = date('H:i:s', $Time_e);							
}
elseif ($Date_G == $Date_e){
$Date = ($Time_G < $Time_e)?date('d/m/Y', $Date_e):date('d/m/Y', $Date_G);
$Time = ($Time_G < $Time_e)?date('H:i:s', $Time_e):date('H:i:s', $Time_G);
$Date_YMD = ($Time_G < $Time_e)?date('d_m_Y', $Date_e):date('Y-m-d', $Date_G);
$Time_YMD = ($Time_G < $Time_e)?date('H:i:s', $Time_e):date('H:i:s', $Time_G);							
							
}
else {
							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);
				$Date_YMD = date('d_m_Y', $Date_G);
							$Time_YMD = date('H:i:s', $Time_G);
}				
	
							
							}



	#############################################
							
							$Excel_Output.= "<tr height='30'>";
								echo "<td style='font-size:12px;' style='padding-right:15px;'>$SNo</td>";	
							foreach($List_Fields as $Fields){

									if($Fields[0] == 'Device_Name'){
										echo "<td class='Row_Td' >";
											echo $Fetch_Result['Device_Name'];
										echo "</td>";
									}
									elseif($Fields[0] == 'Date'){
										echo "<td class='Row_Td' align='center'>";
											echo $Date;
										echo "</td>";
									}
									elseif($Fields[0] == 'Time'){
										echo "<td class='Row_Td' align='center'>";
											echo $Time;
										echo "</td>";
									}
									elseif($Fields[0] == 'GAD'){
										echo "<td class='$Device_Status_Class1' align='center'>";
											echo $GAD;
										echo "</td>";
									}
									elseif($Fields[0] == 'Status'){
										echo "<td class='$Device_Status_Class' align='center'>";
											echo $Status;
										echo "</td>";
									}
									
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
					while($Fetch_Result = mysqli_fetch_array($Result)){
						
						($Record_Column%2 == 1 ? $Row_Cls = 'Grey_Bg' : $Row_Cls = 'White_Bg');
	
						$IMEI = $Fetch_Result['IMEI'];
						$Db_Name = $Fetch_Result['db_name'];
	$Device_Name = $Fetch_Result['Device_Name'];
	$Closing_Time = $Fetch_Result['Closing_Time'];
	//print_r($IMEI);
						
						$Format_Type = $Fetch_Result['Format_Type'];
						
						if($Format_Type == 1){
										$Channel_Url = "channel2.php?";
										$Table_Name = "device_data"; 
										$Error_Table_Name = "error_data"; 
									}elseif($Format_Type == 2){
										$Channel_Url = "channel3_new.php?";
										$Table_Name ="device_data_f2";									
										$Error_Table_Name = "error_data_f2"; 
									}elseif($Format_Type == 3){
										$Channel_Url = "channel4.php?";
										$Table_Name = "device_data_f3";									
										$Error_Table_Name = "error_data_f3";
									}elseif($Format_Type == 4){
										$Channel_Url = "channel9_new.php?";
										$Table_Name = "device_data_f4"; 
										$Error_Table_Name = "error_data_f4"; 
									}elseif($Format_Type == 6){
										if($Parent_ID==30 && $User_Type_ID ==4)
										$Channel_Url = "channel11.php?";
										else
										$Channel_Url = "channel7.php?";
										$Table_Name = "device_data_f6"; 
										$Error_Table_Name = "error_data_f6"; 
									}elseif($Format_Type == 7){
										$Channel_Url = "channel8.php?";
										$Table_Name = "device_data_f7"; 
										$Error_Table_Name = "error_data_f7"; 
									}elseif($Format_Type == 8){
										$Channel_Url = "channel8.php?";
										$Table_Name = "device_data_f8"; 
										$Error_Table_Name = "error_data_f8";  
									}elseif($Format_Type == 10){
										$Channel_Url = "channel10.php?";
										$Table_Name = "device_data_f10"; 
										 $Error_Table_Name = "error_data_f10"; 
									}	
						#
						#	Alarm Log from ERROR_DATA
						#
						$ED_Mysql_Query = "select * from $Db_Name.$Error_Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";
						//echo $ED_Mysql_Query;
	$ED_Mysql_Query_Result = mysqli_query($db,$ED_Mysql_Query) or die(mysqli_connect_error());
						$ED_Mysql_Record_Count = mysqli_num_rows($ED_Mysql_Query_Result);
						if($ED_Mysql_Record_Count>=1){
							$ED_Fetch_Result = mysqli_fetch_array($ED_Mysql_Query_Result);
							$ED_Error_Array[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Status'];
							$ED_Error_Date[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Date_S'];
							$ED_Error_Time[$ED_Fetch_Result['IMEI']] = $ED_Fetch_Result['Time_S'];

$ED_Device_Epoch_Time_Array[$ED_Fetch_Result['IMEI']] = GetTimestamp($ED_Error_Date[$ED_Fetch_Result['IMEI']],$ED_Error_Time[$ED_Fetch_Result['IMEI']]); 


						}
					if($Closing_Time=='06:00:00' || $Closing_Time=='06:30:00'){
										$GAD_Time=" and Hour(Time_S)>=6 ";
										$GD_Time=time()-21660;
}
								elseif($Closing_Time=='07:00:00' || $Closing_Time=='07:30:00'){
										$GAD_Time=" and Hour(Time_S)>=7 ";
										$GD_Time=time()-25200;
}								elseif($Closing_Time=='08:00:00' || $Closing_Time=='08:30:00'){
										$GAD_Time=" and Hour(Time_S)>=8 ";
										$GD_Time=time()-28800;
}								elseif($Closing_Time=='09:00:00'){
										$GAD_Time=" and Hour(Time_S)>=9 ";
										$GD_Time=time()-32400;
}								elseif($Closing_Time=='01:00:00' || $Closing_Time=='01:30:00'){
										$GAD_Time=" and Hour(Time_S)>=1 ";
										$GD_Time=time()-3600;
}								elseif($Closing_Time=='02:00:00' || $Closing_Time=='02:30:00'){
										$GAD_Time=" and Hour(Time_S)>=2 ";
										$GD_Time=time()-7200;
}								else {
										$GAD_Time="";
										$GD_Time=time();
$Test_Time=date('H',$GD_Time);
}
		if($Format_Type==2 || $Format_Type==4)
		$Mysql_Query1 ="select Date_S,Time_S,Status  ,(select round((max(PAT_Gen1)-min(PAT_Gen1))+(max(PAT_Gen2)-min(PAT_Gen2)),2) from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() $GAD_Time) as G1 from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";	
		elseif($Format_Type==6 || $Format_Type==1)
		$Mysql_Query1="select Date_S,Time_S,Status,(select max(PAT_Gen2)-min(PAT_Gen2) from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() $GAD_Time) as G1  from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";
		elseif($Format_Type==10 || $Format_Type==3)
		$Mysql_Query1 ="select Date_S,Time_S,Status,(select max(Production_Total)-min(Production_Total) from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() $GAD_Time) as G1 from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";
		elseif($Format_Type==7 || $Format_Type==8)
		$Mysql_Query1 ="select Status,Date_S,Time_S,Kwh_Positive G1 from $Db_Name.$Table_Name where IMEI = '".$IMEI."' and Date_S=curdate() order by Record_Index desc limit 1";
									
						
						//$Mysql_Query1 = "select Date_S,Time_S,Status from $Db_Name.$Table_Name where IMEI = '".$IMEI."' order by Record_Index desc limit 1";			
						//echo $Mysql_Query1;
			$Mysql_Query_Result1 = mysqli_query($db,$Mysql_Query1) or die(mysqli_connect_error());									
						$Mysql_Record_Count1 = mysqli_num_rows($Mysql_Query_Result1);
						if($Mysql_Record_Count1 >= 0){
							$Fetch_Result1 = mysqli_fetch_array($Mysql_Query_Result1); 											
							$Status1 = trim($Fetch_Result1['Status']);
							$Status = strtolower($Status1);
							$Date_F = $Fetch_Result1['Date_S'];
							$Time_F = $Fetch_Result1['Time_S'];	
							$GAD = $Fetch_Result1['G1'];	
							if($Format_Type == 2 || $Format_Type == 4){								
								if($GAD > 6000 || $GAD < 0)
									$Device_Status_Class1 = "Device_Status_Red1";	
								else
									$Device_Status_Class1 = "Device_Status_Green";								
							}
							if($Format_Type == 1  ||  $Format_Type == 6){								
								if($GAD > 25000 || $GAD < 0)
									$Device_Status_Class1 = "Device_Status_Red1";
							else
									$Device_Status_Class1 = "Device_Status_Green";
							}
							if($Format_Type == 7  ||  $Format_Type == 8){								
								if($GAD > 25000 || $GAD < 0)
								$Device_Status_Class1 = "Device_Status_Red1";
							else
									$Device_Status_Class1 = "Device_Status_Green";
							}
							if($Format_Type == 3  ||  $Format_Type == 10){	
								if($GAD > 25000 || $GAD < 0)
								$Device_Status_Class1 = "Device_Status_Red1";
							else
									$Device_Status_Class1 = "Device_Status_Green";
							}
							
							$Device_Epoch_Time=GetTimestamp($Date_F,$Time_F);
							if(!empty($Device_Epoch_Time) && !empty($ED_Device_Epoch_Time_Array[$IMEI])){
								$Diff_Error_Status = $Device_Epoch_Time  - $ED_Device_Epoch_Time_Array[$IMEI];
								if(0 > $Diff_Error_Status){
									$Status = $ED_Error_Array[$IMEI];
								}
							}
							# More than 3 hours not working
							$Req_Time = time()+(60*60*3);
							$ReqTime_Diff = $Req_Time - $Device_Epoch_Time;
										$ReqTime_Diff_err = $Req_Time - $ED_Device_Epoch_Time_Array[$IMEI];				
							if($ReqTime_Diff >= 3600 && $ReqTime_Diff_err >= 3600 && (in_array($Status,$Error_Array['Green']) && !in_array($Status,$Error_Array['Blue']))){
								$Device_Status_Class = "Device_Status_Grey";
								$Tower_Img = '<img src="./images/grey.gif" width="20px" height="20px" alt="Yellow Tower">';
							}
							else
							{
								# Depence upon the Status Tower image
								if(in_array($Status,$Error_Array['Green'])){
									if($Power == '000' || $Power == '0'){
										$Device_Status_Class = "Device_Status_Orange";
										$Tower_Img = '<img src="./images/orange_tower.jpg" width="20px" height="20px" alt="Orange Tower">';
									}
									else{
										$Device_Status_Class = "Device_Status_Green";
										$Tower_Img = '<img src="./images/green_tower.jpg" width="20px" height="20px" alt="Orange Tower">';
									}	
								}	
								elseif(in_array($Status,$Error_Array['Orange'])){
									$Device_Status_Class = "Device_Status_Orange";
									$Tower_Img = '<img src="./images/orange_tower.jpg" width="20px" height="20px" alt="Orange Tower">';
								}	
								elseif(in_array($Status,$Error_Array['Blue'])){
									$Device_Status_Class = "Device_Status_Blue";
									$Tower_Img = '<img src="./images/blue_tower.jpg" width="20px" height="20px" alt="Blue Tower">';
								}
								elseif(in_array($Status,$Error_Array['Pink'])){
									$Device_Status_Class = "Device_Status_Pink";
									$Tower_Img = '<img src="./images/pink_tower.jpg" width="20px" height="20px" alt="Blue Tower">';
								}
								else{											
									$Device_Status_Class = "Device_Status_Red";
									$Tower_Img = '<img src="./images/red_tower1.jpg" width="20px" height="20px" alt="Red Tower">';
								}	
							}
						}

							$Date="";
							$Time="";
							$Date_e = strtotime($ED_Error_Date[$IMEI]);
							$Time_e = strtotime($ED_Error_Time[$IMEI]);
							$Date_G = strtotime($Date_F);
							$Time_G = strtotime($Time_F);

	if(in_array($Status,$Error_Array['Green'])){
if ($Date_G < $Date_e) {
$Date = date('d/m/Y', $Date_e);
							$Time = date('H:i:s', $Time_e);
$Date_YMD = date('d_m_Y', $Date_e);
							$Time_YMD = date('H:i:s', $Time_e);							

} else {

							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);
					$Date_YMD = date('d_m_Y', $Date_G);
							$Time_YMD = date('H:i:s', $Time_G);
}							
								
							}
elseif(in_array($Status,$Error_Array['Blue']) || in_array($Status,$Error_Array['Pink'])) {

$Date = date('d/m/Y', $Date_e);
							$Time = date('H:i:s', $Time_e);							
$Date_YMD = date('d_m_Y', $Date_e);
							$Time_YMD = date('H:i:s', $Time_e);
							
						
							}

else {
if ($Date_G < $Date_e) {
$Date = date('d/m/Y', $Date_e);
$Time = date('H:i:s', $Time_e);
$Date_YMD = date('d_m_Y', $Date_e);
							$Time_YMD = date('H:i:s', $Time_e);							
}
elseif ($Date_G == $Date_e){
$Date = ($Time_G < $Time_e)?date('d/m/Y', $Date_e):date('d/m/Y', $Date_G);
$Time = ($Time_G < $Time_e)?date('H:i:s', $Time_e):date('H:i:s', $Time_G);
$Date_YMD = ($Time_G < $Time_e)?date('d_m_Y', $Date_e):date('Y-m-d', $Date_G);
$Time_YMD = ($Time_G < $Time_e)?date('H:i:s', $Time_e):date('H:i:s', $Time_G);							
							
}
else {
							$Date = date('d/m/Y', $Date_G);
							$Time = date('H:i:s', $Time_G);
				$Date_YMD = date('d_m_Y', $Date_G);
							$Time_YMD = date('H:i:s', $Time_G);
}				
	
							
							}


	#############################################

						echo $Display_List[] = "<tr class='$Row_Cls' height='25'>";
							echo "<td class='Row_Td' style='padding-right:5px;' width='20px'>$SNo</td>";	
							echo "<td class='Row_Td' width='30px'><input type='checkbox' name='Edit' id='Edit' value='$Fetch_Result[$Edit_ID_Column]' onclick='setChecks(this)' ".($Fetch_Result[$Edit_ID_Column] == $_REQUEST['Edit']? 'Checked = Checked' : '')."></td>";	
							foreach($List_Fields as $Fields){
if($Fields[0] == 'Device_Name'){
										echo "<td class='Row_Td'>";
		echo '<a href="http://35.209.166.171/versatilescada.in/gogreen/Show_Raw_Data.php?c1='.$IMEI.'&db='.$Db_Name.'&FType='.$Format_Type.'" target="_blank";>'.$Device_Name.'</a>';

										echo "</td>";
									}
									elseif($Fields[0] == 'Date'){
										echo "<td class='Row_Td'>";
											echo $Date;
										echo "</td>";
									}
									elseif($Fields[0] == 'Time'){
										echo "<td class='Row_Td'>";
											echo $Time;
										echo "</td>";
									}
									elseif($Fields[0] == 'GAD'){
										echo "<td class='$Device_Status_Class1'>";
											echo $GAD;
										echo "</td>";
									}
									elseif($Fields[0] == 'Status'){
										echo "<td class='$Device_Status_Class' align='center'>";
											echo $Status;
										echo "</td>";
									}									
							}								
							
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
