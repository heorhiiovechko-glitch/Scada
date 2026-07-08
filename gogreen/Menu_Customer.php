<?php if(isset($Cook_Variable[1])){	?>
<div id="globalNav" style="float:left">
<div class="gblNavigation" id="gblNavigation" style="border:solid 0px red;">

	<?php
		if(isset($_COOKIE[$Cook_Name])){
			echo "<div align=\"right\" class=\"textconTOP\">Welcome, <span style='color:#F25151'>". $Cook_Variable[0] ."</span> &nbsp;&nbsp;[ <a href='Logout.php?cmd=logout' class=\"logout\">logout</a> ]</div>";
		}
	?>

	<?php
	
		######################################################
		#
		#		Menu Declaration 
		#
		#######################################################	
		
		$Menus_Array = array ('User_Type');
		$Sub_Menus_Array = array ('A-L');
		
		//Extra menu Declaration
		//$Extra_Menu_Array = array ("List_Summary.php | List Summary");
	?>


	 <table class="Nav" border="0" cellpadding="0" cellspacing="0">
	 	<tr>
			<td class="home-btn" height="25" width="28">
				<a href="home.php" alt="Home" title="Home" style="width: 28px; height: 25px; display: block;"></a>
			</td>
			
			<?php
				$M = 0;
				foreach ($Menus_Array as $Menus){
					$Menuss = str_replace('_',' ',$Menus);
			?>
        
	     <td class="NavText" onmouseover="mopen('m<?=$M?>')" onmouseout="mclosetime()">
		      <a href="#" class="NavLink"><?=$Menuss?></a>
			   <div id="m<?=$M?>">
			        <table align="left" border="0" cellpadding="0" cellspacing="0">
        				<tr>
					         <td colspan="2" id="mgDd">
						          <table align="left" border="0" cellpadding="0" cellspacing="0">
           								<tr>
								            <td><img src="images/IMG_dropdown_tab-left.gif" alt="" height="27" width="4"></td>
								           <td style="background-image: url(images/IMG_dropdown_tab-bg.gif);" class="PopupTab">
												<a href="#" class="popNavLink"><?=$Menuss?></a>
											</td>
								            <td><img src="images/IMG_dropdown_tab-right.gif" alt="" height="27" width="4"></td>
							           </tr>
								 </table>
							 </td>
			    	    </tr>
			        	<tr>
							 <td id="mgPopup" width="5">&nbsp;</td>
							 <td>
								  <table id="mgDdRht" border="0" cellpadding="0" cellspacing="0" width="210">
									<tr>
										<td colspan="3" height="10"></td>
									</tr>
									<tr>
										<td>
											<table border="0" cellpadding="2" cellspacing="2" width="270px;">
												<tr>
														<? 
                                                            $Option = explode('-',$Sub_Menus_Array[$M]);
                                                            if($Option[0] == 'A'){
                                                        ?>
													<td width="135"><a href="Add_<?=$Menus?>.php" class="PopupLinks">Add <?=$Menuss?></a></td>
                                                    	<?
															}
															else{
														?>
															<td width="135">&nbsp;</td>
                                                        <?	
															}
														?>
													<td class="Dotts" width="10"></td>
														<?
                                                            $Option = explode('-',$Sub_Menus_Array[$M]);
                                                            if($Option[1] == 'L'){
                                                        ?>
													<td width="135"><a href="List_<?=$Menus?>.php" class="PopupLinks">List <?=$Menuss?></a></td>
                                                    	<?
															}
															else{
														?>
															<td width="135">&nbsp;</td>
                                                        <?	
															}
														?>
												</tr>
                                                
                                                 <?
												
                                                /*
                                                		For Extra Menus Array
                                                */
                                                ?>
														<? 
														if(!empty($Extra_Menu_Array[$M])){
															foreach($Extra_Menu_Array as $Menu_Key => $Extra_Menu){
																$Menu_Item = explode('|',$Extra_Menu);
                                                        ?>
												<tr>
													<td width="135"><a href="<?=$Menu_Item[0]?>" class="PopupLinks"><?=$Menu_Item[1];?></a></td><td class="Dotts" width="10"></td>
												</tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                </tr>
                                                        <?	
															}
														}	
														?>
											</table>
										</td>			
								   </tr>
								   <tr>
										<td colspan="5" height="10"></td>
								   </tr>
								</table>
							</td>
						</tr>
					</table>			
				</div>	
		 </td>
		 
		 
		 <td><img src="images/navSep.gif" alt=""></td>
         
		<?
			$M++;
		} // Menus end
		?>
		
     </tr>
	</table>
		<div style="clear:both"></div>
  </div>
  </div>
<?php 	
}
else{
?>
	<div id="menu_middle">
	 <ul>
	</ul>	
	</div>
<?php } ?>	