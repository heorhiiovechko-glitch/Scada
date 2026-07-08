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
		$Form_Fields[] = array('1','1','Filter','search','','','E|N|0','','Filter_box','','');
		//Submit Button - Dont Touch
		$Form_Fields[] = array('1','5','',$Submit_Txt,'Search','','','','submit_but','','');
	
		switch($Cook_Variable[2]){
			case 1:
				$Icons_Control = "D|E|V|X|P";		
				break;
			case 2:
				$Icons_Control = "|E|V|X|P";		
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
	$Pagination_No_Count = 15;
	$Edit_ID_Column = "Device_Index"; // Unique value should be give for whatever query you do in that table

	// Text box Search Option
	//$Form_Fields[] = array('1','1','Date Stamp','Hid','','','E|N|0','','Filter_box','Cal','','','');
	//$Form_Fields[] = array('1','1','To Date','Hid','','','E|N|0','','Filter_box','Cal_T','','','');
	
	// From Date to To Date Search Declaration
	//$Form_Fields[] = array('1','1','From Date','Hid','','','E|N|0','','Filter_box','Cal_T','','F','Hid');
	//$Form_Fields[] = array('1','1','To Date','To_Date','','','E|N|0','','Filter_box','Cal_T','','T','Hid');
	
	
	// Drop down search Option
	//$Form_Fields[] = array('1','3','Status','Sel',$Status_Array,'','1','','Filter_Sel','','-----Status-----','');
	
	// Automatic Drop down search Option
	//$Form_Fields[] = array('1','3','Status','Gender',$Gender_Array,'','1','','Filter_Sel1','onchange="Filter_Func(this.id,this.value,'.$Title_Head1.')"','-----Gender-----','');

		
	// Search Area Customization	
	$Search_Fields = array('IMEI','SIM_No','Date_Stamp');

	//List Fields
	$List_Fields[] = array('Added On','Date_Stamp','','6','');
	$List_Fields[] = array('IMEI','IMEI','','','');
	$List_Fields[] = array('UID','UID','','','');
	$List_Fields[] = array('Format','Format_Type','','',$Data_Format_Array);
	$List_Fields[] = array('Name','Device_Name','','','');
	$List_Fields[] = array('HTSC No','HTSC_No','','','');
	$List_Fields[] = array('SF No','SF_No','','','');
	$List_Fields[] = array('DB Name','Db_Name','','','');
	
		

	###############################################     End      #########################################
 	
	
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
	$Submit_Pos =count($Form_Fields) - 1;
	if( (isset($_REQUEST[$Form_Fields[$Submit_Pos][3]])) || (isset($_REQUEST['SearchD'])) ){
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
			$Query_To_Mysql_Result = mysqli_query($db,$Query_To_Mysql_Search);
			if(!$Query_To_Mysql_Result){
				echo $Err_Msg = Message_Display("Mysql Error :");
				echo $Err_Msg = "<div class='error_text1'>".mysqli_connect_error()."</div>";
			}
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
		header("Location: ".$Edit_Page.".php?Edit=".$Edit_Req."&Edit_Column=".$Edit_ID_Column."");
		exit;
	}	

	// For View
	if(isset($_REQUEST['OptionView'])){
		$View_Req =$_REQUEST['Edit'];
		header("Location: ".$View_Page.".php?View=".$View_Req."&Edit_Column=".$Edit_ID_Column."");
		exit;
	}
	
?>

<?php
/* ===========================
   STYLING: full-width, modern and minimal inline CSS
   (Only visual changes — PHP logic kept intact)
   =========================== */
?>
<style>
    :root{
        --primary:#1565c0;
        --accent:#1e88e5;
        --muted:#f4f6f9;
        --card:#ffffff;
        --text:#203040;
        --heading:#0b3b66;
    }
    html,body{
        margin:0;
        padding:0;
        width:100%;
        font-family: "Segoe UI",-apple-system,BlinkMacSystemFont,"Helvetica Neue",Arial, sans-serif;
        background:var(--muted);
        color:var(--text);
        -webkit-font-smoothing:antialiased;
    }

    /* page container */
    #admin_div{
        width:100%;
        box-sizing:border-box;
        padding:18px 22px;
    }

    .top-row{
        display:flex;
        gap:12px;
        align-items:center;
        justify-content:space-between;
        margin-bottom:14px;
    }

    .page-title{
        font-size:20px;
        color:var(--heading);
        font-weight:700;
        margin:0;
    }

    .top-controls{
        display:flex;
        gap:10px;
        align-items:center;
    }

    .search-panel{
        background:var(--card);
        border-radius:10px;
        box-shadow:0 6px 18px rgba(10,20,40,0.06);
        padding:14px;
        display:flex;
        gap:12px;
        align-items:center;
        flex-wrap:wrap;
        width:100%;
        box-sizing:border-box;
    }

    .filter-field{
        display:flex;
        flex-direction:column;
        gap:6px;
        min-width:220px;
    }
    .filter-field label{
        font-weight:600;
        color:#334155;
        font-size:13px;
    }

    input[type="text"], select{
        padding:8px 10px;
        border-radius:8px;
        border:1px solid #d0d6df;
        font-size:14px;
        background:#fff;
        box-sizing:border-box;
    }

    .submit_but, .submbg_top input[type=submit], .btn{
        background:var(--primary);
        color:white;
        border:none;
        padding:9px 14px;
        border-radius:8px;
        cursor:pointer;
        font-weight:600;
        box-shadow:0 6px 14px rgba(21,101,192,0.12);
    }
    .submit_but:hover, .submbg_top input[type=submit]:hover, .btn:hover { filter:brightness(.95); }

    /* icons row */
    .icons-bar{
        display:flex;
        gap:12px;
        align-items:center;
    }
    .icon-bor{
        text-align:center;
        padding:8px;
        background:transparent;
        border-radius:8px;
        display:flex;
        flex-direction:column;
        gap:6px;
        align-items:center;
        justify-content:center;
    }
    .icon-bor img{ display:block; }

    .icon-txt{
        font-size:12px;
        color:var(--heading);
        font-weight:600;
    }

    /* table styles (full width) */
    .full-width-table{
        width:100% !important;
        border-collapse:separate !important;
        border-spacing:0 8px;
        margin-top:18px;
    }

    .admin-menu-heading{
        background:var(--accent);
        color:#fff !important;
        padding:10px 12px;
        border-radius:6px;
        font-size:13px;
        font-weight:700;
        text-align:left;
    }

    .Row_Td{
        background: #ffffff;
        padding:10px 12px;
        font-size:14px;
        color:var(--text);
        border-bottom:1px solid #e6eef8;
        vertical-align:middle;
    }

    .White_Bg{ background:#fff; border-radius:6px; }
    .Grey_Bg{ background:#f8fbff; border-radius:6px; }

    .White_Bg:hover td, .Grey_Bg:hover td{
        background:#eef6ff !important;
    }

    .total_admin{
        font-size:14px;
        color:#273444;
        font-weight:700;
        text-align:right;
    }

    .Page_align{ text-align:center; margin-top:10px;}
    .Page_align a{ background:var(--primary); color:white; padding:8px 12px; border-radius:6px; text-decoration:none; margin:4px; display:inline-block; }

    /* small responsive tweaks */
    @media (max-width:900px){
        .filter-field{ min-width:100%; }
        .icons-bar{ flex-wrap:wrap; justify-content:flex-end; }
        .admin-menu-heading{ font-size:12px; padding:8px; }
        .Row_Td{ font-size:13px; padding:8px; }
    }

    /* remove forced widths from inlined tables */
    table[width], table[align], table[border] { width:100% !important; max-width:none !important; }
	
</style>

<?php if($XLS == 0){ ?>
<div id="admin_div">
		<div class="top-row">
			<h1 class="page-title"><?=$Title_Head?></h1>

			<div class="top-controls">
				<!-- show welcome + logout if available (non-logic / display only) -->
				<?php if(isset($Cook_Variable[0])): ?>
					
				<?php endif; ?>
			</div>
		</div>

		<!-- Search + controls -->
		<div class="search-panel List_Tab" role="region" aria-label="Search Panel">

			<?php 
				// Top Search Area
				echo "<form action=\"\" name=\"".$Title_Head."\" onsubmit=\"return FormValid('$Jvalid_Arr_Join','$Jvalid_Type_Arr_Join')\">"; 
			?>

			<?php
				// We'll render every form element into a simple card-like layout without changing logic
				foreach($Form_Fields as $Forms){
					if(empty($Forms[4])) $Forms[4] = $_REQUEST[$Forms[3]];
					// Text / simple controls (not selects or submit)
					if( ($Forms[1] != 5 && $Forms[1] != 6) && ($Forms[1] != 3) ){
			?>
						<div class="filter-field" style="margin-right:8px;">
							<label><?=($Forms[2] != ''?$Forms[2] : $Forms[3])?><?=($Forms[5] == '*'?$star : '')?></label>
							<?php
								// Calendar Enabled Field
								if($Forms[9] == 'Cal'){
							?>
								<script language="JavaScript" src="js/calendar_us.js"></script>
								<link rel="stylesheet" href="css/calendar.css">
								<?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
							<?php
								}
								elseif($Forms[9] == 'Cal_T'){
							?>
								<script language="JavaScript" src="js/dcal.js"></script>
								<link rel="stylesheet" href="css/dcal.css">
								<?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
							<?php
								}
								else{
									// Render regular field
									echo Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);
								}
							?>
							<?php if ($Forms[2] == 'Filter') { ?>
								<div style="font-size:12px;color:#6b7280;margin-top:6px;">( Searches: <?=join(',',$Search_Fields)?> )</div>
							<?php } ?>
							<?=J_Mes($Forms[3]);?>
						</div>

			<?php
					}
					// Submit button area
					elseif($Forms[1] == 5 || $Forms[1] == 6){
			?>
						<div style="display:flex;align-items:center;">
							<?=Func_Forms_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[7]);?>
						</div>
			<?php
					}
					// For select boxes that are not handled above
					elseif($Forms[1] == 3){
						$Filter_Pos = strpos($Forms[9],'onchange="Filter_Func')."O";
						if($Filter_Pos == '0O'){
							// simple select
			?>
							<div class="filter-field">
								<label><?=($Forms[2] != ''?$Forms[2] : $Forms[3])?></label>
								<?=Func_Select_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[9],$Forms[10],$SearchD1[1]);?>
							</div>
			<?php
						} else {
			?>
							<div class="filter-field">
								<label><?=($Forms[2] != ''?$Forms[2] : $Forms[3])?></label>
								<?=Func_Select_Element($Forms[0],$Forms[1],$Forms[3],$Forms[4],$Forms[8],$Forms[9],$Forms[10],$_REQUEST[$Forms[3]]);?>
							</div>
			<?php
						}
					}
				}
			?>

			<input type="hidden" name="P_ID" id="P_ID" value="<?=$_REQUEST['P_ID']?>" />
			</form>

			<!-- icons -->
			<div class="icons-bar" style="margin-left:auto;">
				<form name="List_Form" method="post" style="margin:0;">
				<table cellpadding="0" cellspacing="0" style="border:0;background:transparent;">
					<tr>
						<? 
						if(isset($Icons_Control)){
							$Icons_Controls = explode('|',$Icons_Control);
						?>
							<? if($Icons_Controls[0] == 'D'){ ?>
								<td class="icon-bor"><input type="submit" name="OptionDelete" value="" class="delete-icon" onclick="return Confirm_Message('You are about to delete the Record',this.form)" title="Delete" /><div class="icon-txt">Delete</div></td>
							<? } ?>
							<? if($Icons_Controls[1] == 'E'){ ?>
								<td class="icon-bor"><input type="submit" name="OptionEdit" value="" class="edit-icon" onclick="return anyCheck(this.form)" title="Edit" /><div class="icon-txt">Edit</div></td>
							<? } ?>
							<? if($Icons_Controls[2] == 'V'){ ?>
								<td class="icon-bor"><input type="submit" name="OptionView" value="" class="view-icon" onclick="return anyCheck(this.form)" title="View" /><div class="icon-txt">View</div></td>
							<? } ?>
							<? if($Icons_Controls[3] == 'X'){ ?>
								<td class="icon-bor"><a href="<?="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?XLS=1&".$_SERVER['QUERY_STRING']?>" title="Excel"><img src="./images/icon-48-article-add.png" height="28" /></a><div class="icon-txt">Excel</div></td>
							<? } ?>
							<? if($Icons_Controls[4] == 'P'){ ?>
								<td class="icon-bor"><a href="javascript:window.print();" title="Print"><img src="./images/icon-48-print.png" height="28" /></a><div class="icon-txt">Print</div></td>
							<? } ?>
						<? } ?>
					</tr>
				</table>
				</form>
			</div>

		</div> <!-- end search-panel -->

		<?php
			if(isset($_REQUEST['P_ID'])){
				$P_ID = $_REQUEST['P_ID'];
		?>
		<div style="margin-top:14px;">
			<div style="background:linear-gradient(90deg,#fff,#f8fbff);padding:12px;border-radius:10px;display:flex;align-items:center;justify-content:space-between;">
				<div style="display:flex;gap:10px;align-items:center;">
					<strong style="color:#334155">Account Name:</strong>
					<div style="color:#0b3b66;font-weight:700;"><?=$Firstname_Lastname_Array[$P_ID];?> (<?=$Username_Array[$P_ID];?>)</div>
				</div>
				<div style="display:flex;gap:10px;align-items:center">
					<a href="List_User.php?P_ID=<?=$P_ID?>" style="text-decoration:none;color:var(--primary);font-weight:600;">List User</a>
					<div>
						<?= "<a href='Add_User.php?P_ID=".$_REQUEST['P_ID']."' title='Add User'><img src='./images/user-add-48-Icon.jpg' height='20' width='20' title='Add User'></a>" ?>&nbsp;
						<?= "<a href='Add_Device.php?P_ID=".$_REQUEST['P_ID']."' title='Add Vehicle'><img src='./images/icon_question.gif' height='20' width='20' title='Add Vehicle'></a>" ?>&nbsp;
						<?= "<a href='List_User.php?P_ID=".$_REQUEST['P_ID']."' title='Detail View'><img src='./images/detail_icon.gif' height='20' width='20' title='Detail View'></a>" ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>

<?php } // XLS == 0 end ?>

<?php
if($XLS == 0){
?>
<div id="admin_div" style="padding-top:18px;">
			<div style="padding-left:0px;"><span class="msg"><?=$_GET['msg']?></span>
				<p class="headings" style="padding-left:0px;"></p>
<?php } // XLS == 0 end ?>			

    <table class="full-width-table" cellpadding="1" cellspacing="1" aria-describedby="device-list">
    <?php
        include('ps_pagination.php');
        if($Edit_ID_Column) $Edit_Column = $Edit_ID_Column.",";
        if($Query_To_Mysql_Search) $Select_Mysql = $Query_To_Mysql_Search;
        else $Select_Mysql = "SELECT $Edit_Column $Mysql_Select_Column FROM $Table_Name order by Db_Name";

        $Total_Records = mysqli_num_rows (mysqli_query($db,$Select_Mysql));
        $Pager = new PS_Pagination($db, $Select_Mysql, $Records_Per_Page, $Pagination_No_Count, "");
        $Record_Count = mysqli_num_rows ($Pager->paginate());
        if($Record_Count){
            if($_GET[Sortto] == 'desc'){
                $Sortto = 'asc';
                $arrows = '<img src="images/down.gif" id="cdarrow" height="3" width="5" alt="Sort" class="arrow-img" />';
            } else {
                $Sortto = 'desc';
                $arrows = '<img src="images/up.gif" id="cdarrow" height="3" width="5" alt="Sort" class="arrow-img" />';
            }

            if(!$_REQUEST['Page']) $_REQUEST['Page'] = 1;
    ?>

    

    <?php
        if($XLS == 1){
            echo $Excel_Output_Head.="<tr><td colspan=".$Colspan_Count."><br><center><h3>$Title_Head On ".date('d-m-Y H:i:s')."</h3></center></td></tr>";
        }
    ?>		

    <tr bgcolor="#F0F0F0" height="25px">
        <td class="admin-menu-heading">#</td>
        <? if($XLS == 0){ ?>
        <td class="admin-menu-heading"></td>
        <? } ?>
        <?php
            foreach($List_Fields as $Fields){
                if($XLS == 0){
                    $List_Head_Url1 = ''.$List_Page.'.php?Page='.$_REQUEST['Page'].'&Sortby='.$Fields[1].'&Sortto='.$Sortto.'&P_ID='.$_REQUEST['P_ID'].'';
                    if($_REQUEST['From']){
                        $List_Head_Url = $List_Head_Url1."&From=".$_REQUEST['From']."&To=".$_REQUEST['To']."&search=".$_REQUEST['search'];
                    } else {
                        $List_Head_Url = $List_Head_Url1;
                    }
                    echo '<td class="admin-menu-heading"><div style="text-align:left;"><a href="'.$List_Head_Url.'" title="Sort" style="color:#fff;text-decoration:none;"><strong>'.$Fields[0].'</strong>&nbsp;'.($Fields[1] == $_REQUEST['Sortby']?$arrows : '').'</a></div></td>';
                }
                if($XLS == 1){
                    echo '<td class="admin-menu-heading" style="text-align:left;" align="left"><div align="left"><strong>'.$Fields[0].'</strong></div></td>';
                }
            }
        ?>
    </tr>
    <tr><td colspan="<?=$Colspan_Count?>">&nbsp;</td></tr>

    <?php
        // Excel output or display rows
        $SNo = 1;
        $Record_Column = 0;
        if($XLS == 1){
            $Result = mysqli_query($db,$Select_Mysql);
            while($Fetch_Result = mysqli_fetch_array($Result)){
                ($Record_Column%2 == 1 ? $Row_Cls = 'Grey_Bg' : $Row_Cls = 'White_Bg');
                echo "<tr class='$Row_Cls' height='30'>";
                echo "<td class='Row_Td' style='width:40px;'>$SNo</td>";
                foreach($List_Fields as $Fields){
                    if($Fields[0] == 'Added On'){
                        echo "<td class='Row_Td'>".date("d-M-Y",strtotime($Fetch_Result['Date_Stamp']))."</td>";
                    } elseif($Fields[0] == 'IMEI'){
                        echo "<td class='Row_Td'>&nbsp;".$Fetch_Result['IMEI']."</td>";
                    } elseif($Fields[0] == 'UID'){
                        echo "<td class='Row_Td'>&nbsp;".$Fetch_Result['UID']."</td>";
                    } elseif($Fields[0] == 'Format'){
                        echo "<td class='Row_Td'>".$Data_Format_Array[$Fetch_Result['Format_Type']]."</td>";
                    } elseif($Fields[0] == 'Name'){
                        echo "<td class='Row_Td'>&nbsp;".$Fetch_Result['Device_Name']."</td>";
                    } elseif($Fields[0] == 'HTSC No'){
                        echo "<td class='Row_Td'>".$Fetch_Result['HTSC_No']."</td>";
                    } elseif($Fields[0] == 'SF No'){
                        echo "<td class='Row_Td'>".$Fetch_Result['SF_No']."</td>";
                    } elseif($Fields[0] == 'DB Name'){
                        echo "<td class='Row_Td'>".$Fetch_Result['Db_Name']."</td>";
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
                echo "<tr class='$Row_Cls' height='25'>";
                echo "<td class='Row_Td' style='width:40px;'>$SNo</td>";
                echo "<td class='Row_Td' style='width:30px;'><input type='checkbox' name='Edit' id='Edit' value='$Fetch_Result[$Edit_ID_Column]' onclick='setChecks(this)' ".($Fetch_Result[$Edit_ID_Column] == $_REQUEST['Edit']? 'Checked = Checked' : '')."></td>";
                foreach($List_Fields as $Fields){
                    if($Fields[0] == 'Added On'){
                        echo "<td class='Row_Td'>".date("d-M-Y",strtotime($Fetch_Result['Date_Stamp']))."</td>";
                    } elseif($Fields[0] == 'IMEI'){
                        echo "<td class='Row_Td'>".$Fetch_Result['IMEI']."</td>";
                    } elseif($Fields[0] == 'UID'){
                        echo "<td class='Row_Td' align='left'>&nbsp;".$Fetch_Result['UID']."</td>";
                    } elseif($Fields[0] == 'Format'){
                        echo "<td class='Row_Td'>".$Data_Format_Array[$Fetch_Result['Format_Type']]."</td>";
                    } elseif($Fields[0] == 'Name'){
                        echo "<td class='Row_Td' align='left'>&nbsp;".$Fetch_Result['Device_Name']."</td>";
                    } elseif($Fields[0] == 'HTSC No'){
                        echo "<td class='Row_Td'>".$Fetch_Result['HTSC_No']."</td>";
                    } elseif($Fields[0] == 'SF No'){
                        echo "<td class='Row_Td'>".$Fetch_Result['SF_No']."</td>";
                    } elseif($Fields[0] == 'DB Name'){
                        echo "<td class='Row_Td' align='left'>".$Fetch_Result['Db_Name']."</td>";
                    }
                }
                echo "</tr>";
                $SNo++;
                $Record_Column++;
            }

            echo "<tr><td colspan=\"$Colspan_Count\"><br /><br />";
            echo "<div class='Page_align'>".$Pager->renderFullNav()."</div>";
            echo "</td></tr>";
        } // Excel End
    ?>
    <?php
        } else {
            echo "<tr><td colspan='$Colspan_Count'>".error_report("Records Not Found")."</td></tr>";
        }
    ?>
    </table>

<?php if($XLS == 0) include("Footer.php"); ?>	

<?php 
if($XLS == 1){
	echo $Excel_Output;
}
?>
