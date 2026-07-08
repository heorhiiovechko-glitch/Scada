<?php
 ob_start(); 
 error_reporting(0); 
 include("Lib/Includes.php"); 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$Title?></title>

<?php if($XLS != 1){ ?>
<link rel="stylesheet" type="text/css" href="css/Style.css">
<script type="text/javascript" src="js/admin-menu1.js"></script>
<script type="text/javascript" src="js/admin-menu2.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script type="text/javascript" src="js/jq1.js"></script>

<style>
/* ============================
   FULL WIDTH UI FIX
   ============================ */

/* Remove all spacing */
html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
}

/* Full width wrapper */
#wrap {
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Banner full width */
#banner {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 10px 0;
    display: flex;
    align-items: center;
    background: #eef3ff;
    border-bottom: 1px solid #d0d7ec;
}

/* Banner pieces */
.top-left,
.top-right,
.top-middle {
    flex: 1;
}

.top-middle {
    text-align: center;
}

.logo-inner {
    height: 55px;
}

/* The main table that was previously 1000px */
.fullwidth-table {
    width: 100% !important;
    border: solid 1px #D0D7EC;
    border-top: 0;
    border-bottom: 0;
}

</style>

</head>
<body>

<?php
$query = $_SERVER['QUERY_STRING'];
$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);
?>

<div id="wrap">

    <!-- FULL WIDTH BANNER -->
    <div id="banner">
        <div class="top-left"></div>

        <div class="top-middle">
            <div class="website-name-inner"><?=$Title?></div>
        </div>

        <div class="top-right">
            <img src="images/<?=$Logo_Name?>" 
                 alt="<?=$Website_Name?>" 
                 class="logo-inner">
        </div>
    </div>

    <!-- MENU (unchanged PHP selection logic) -->
    <?php
        switch($Cook_Variable[2]) {
            case 1: include("Menu_Owner.php"); break;
            case 2: include("Menu_Super_Admin.php"); break;
            case 3: include("Menu_Admin.php"); break;
            case 4: include("Menu_Customer.php"); break;
            case 5: include("Menu_Demo.php"); break;
        }
    ?>

    <!-- FULL WIDTH MAIN AREA -->
    <table class="fullwidth-table" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top" style="height:400px;">
<?php } ?>
