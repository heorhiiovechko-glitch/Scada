<?php
	include("./Lib/Declaration.php");

{
		setcookie($Cook_Name,"",time()-360);
		header("Location: index.php");
		exit;
}
?>