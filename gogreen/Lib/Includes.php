<?php
	include_once("Connection.php");
	include_once("Functions.php");
	include_once("Arrays.php");
	include_once("Declaration.php");

	$Jvalid_Arr = array();
	$Jvalid_Type_Arr = array();

	if (!isset($_GET['Sortto'])) { $_GET['Sortto'] = ''; }
	if (!isset($_GET['Sortby'])) { $_GET['Sortby'] = ''; }
	if (!isset($_GET['msg'])) { $_GET['msg'] = ''; }
	if (!isset($_REQUEST['Page'])) { $_REQUEST['Page'] = ''; }
	if (!defined('Sortto')) { define('Sortto', 'Sortto'); }
	if (!defined('Sortby')) { define('Sortby', 'Sortby'); }
	
?>