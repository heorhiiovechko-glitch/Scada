<?php
	ob_start();
	//error_reporting(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
			<title>Powercon</title>
<link rel="stylesheet" type="text/css" href="./css/Style.css" />
<link rel="stylesheet" type="text/css" href="./css/but.css" />
<meta charset="UTF-8">

</head>
<body>
<?php
	include("Includes.php");
?>
<div class="page">
  <div class="header clear">
    <div class="header-area">
      <span class="header-text_">
      <?php
		if(isset($_COOKIE[$Cook_Name]))
			$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	

	  	if(isset($Cook_Variable)){
			$Username = $Cook_Variable[0];
			$Account_ID = $Cook_Variable[3];
	  ?>
	  
      	<table border="0" cellpadding="0" cellspacing="0" align="right" style="margin-right:100px; height:50px">
        	<tr>
            	<td align="left" style="color:white;" >Welcome <?=$Username?> !!!</td>
            </tr>
            <tr>
            	<td><a href="logout.php" style="color:white;">Logout</a></td>
            </tr>
         </table>       
       <?php
	   	}


	   ?>  
      </span>
    </div>
  </div>