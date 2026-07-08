<?php
    ob_start();
    include("Includes.php");
?>
<?php
    if (empty($_COOKIE[$Cook_Name])) {
        header("Location: index.php");
        exit;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>SCADA</title>
	<meta charset="UTF-8">

    <!-- Existing CSS Files -->
    <link rel="stylesheet" type="text/css" href="./css/Style.css" />
    <link rel="stylesheet" type="text/css" href="./css/but.css" />

    <!-- Existing JS -->
    <script language="javascript" type="text/javascript" src="js/jscript.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script type="text/javascript" src="js/marquee.js"></script>

    <!-- NEW: Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- NEW: Header modern styling -->
    <style>
        .header-new {
            background: #008E8E;   /* Your teal color */
            padding: 12px 20px;
            color: #fff;
            width: 100%;
        }

        .header-container {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .header-logo {
            font-size: 24px;
            font-weight: 600;
            color: #7ccb5d;
            text-decoration: none;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .header-right a {
            text-decoration: none;
        }

        .header-icon {
            font-size: 20px;
            color: #fff;
            transition: 0.2s;
        }

        .header-icon:hover {
            transform: scale(1.2);
        }

        .logout-icon {
            color: #ff6b6b !important;
        }
		
		.header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #0f8b8d;

    padding: 10px 40px 10px 20px; 
    /* 👆 right padding increased → pushes icons LEFT */
}
.header-left {
    color: white;
    font-size: 18px;
    white-space: nowrap;
}



.header-right {
    display: flex;
    gap: 15px;
    align-items: center;

    margin-right: 40px;   /* 👈 moves all icons slightly to the LEFT */
}

    </style>

</head>

<body>

<div class="page">

    <!-- 🔥 FINAL HEADER (PHP logic unchanged) -->
    <div class="header-new">
        <div class="header-container">

            <!-- LEFT SIDE (SPACER TO PUSH ITEMS RIGHT) -->
            <div style="flex:1;"></div>

            <!-- RIGHT SIDE -->
            <div class="header-right">

                <?php
                $Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);

                if (isset($Cook_Variable)) {
                    $Username = $Cook_Variable[0];
                    $Account_ID = $Cook_Variable[3];
                    $Firstname = $Cook_Variable[4];
                    $Lastname = $Cook_Variable[5];
                    $User_Type_ID = $Cook_Variable[2];
                    $Parent_ID = $Cook_Variable[6];
                    $Database_Name = $Cook_Variable[7];
                ?>

                <!-- Welcome Text -->
                <span style="font-size:15px; font-weight:500;">
                    Welcome <?= htmlspecialchars($Username) ?> !
                </span>
				
				<!-- NEW ICON FOR TABLE VIEW -->
				<img src="images/listview.png" 
				onclick="location.href='channel1_tableview.php';" 					 
					 style="width:30px; height:30px; cursor:pointer;" 
					 title="List View">
					 
				<img src="images/gridview.png"
				 onclick="location.href='channel1.php';" 
				 style="width:30px; height:30px; cursor:pointer; margin-left:10px;"
				 title="Grid View">
				 
				


                <!-- Change Password Icon -->
                <?php if ($Username != 'krishnan') { ?>
                <a href="Change_Password.php" title="Change Password">
                    <i class="fa-solid fa-key header-icon"></i>
                </a>
                <?php } ?>

                <!-- Logout Icon -->
                <a href="logout.php" title="Logout">
                    <i class="fa-solid fa-power-off header-icon logout-icon"></i>
                </a>

                <?php } ?>

            </div>

        </div>
    </div>
    <!-- END FINAL HEADER -->
