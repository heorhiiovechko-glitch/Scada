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
            justify-content: space-between;
            width: 100%;
        }

        .header-left-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-wind-icon {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.12);
            display: block;
        }

        .header-brand-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        .header-brand-link:hover .header-wind-icon {
            transform: scale(1.04);
            border-color: rgba(255, 255, 255, 0.55);
        }

        .header-brand-title {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.02em;
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

        .btn-sign-out {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .btn-sign-out:hover {
            transform: translateY(-1px);
            background: rgba(79, 209, 255, 0.22);
            border-color: rgba(144, 202, 249, 0.55);
            color: #e3f2fd;
        }

        .btn-sign-out i {
            font-size: 17px;
            line-height: 1;
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

            <!-- LEFT SIDE: wind farm brand -->
            <a href="dashboard.php" class="header-left-brand header-brand-link" title="Dashboard">
                <img src="images/wind1.jpg" alt="" class="header-wind-icon">
                <span class="header-brand-title">SCADA</span>
            </a>

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

                <!-- Change Password Icon -->
                <?php if ($Username != 'krishnan') { ?>
                <a href="Change_Password.php" title="Change Password">
                    <i class="fa-solid fa-key header-icon"></i>
                </a>
                <?php } ?>

                <!-- Sign Out (icon only) -->
                <a href="logout.php" title="Sign Out" class="btn-sign-out" aria-label="Sign Out">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>

                <?php } ?>

            </div>

        </div>
    </div>
    <!-- END FINAL HEADER -->
