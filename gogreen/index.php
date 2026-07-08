<?php
ob_start();
error_reporting(0);
include("Lib/Includes.php");

/* ---------------------------------
   COOKIE CHECK – CONDITIONAL REDIRECT
---------------------------------- 
if (!empty($_COOKIE[$Cook_Name])) {
    $Cook_Variable = explode("|", $_COOKIE[$Cook_Name]);
    $Logged_Username = $Cook_Variable[0] ?? '';

    if ($Logged_Username === 'nextgenscada') {
        header("Location: Home.php");
        exit;
    }
}
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?=$Title?></title>

<link rel="stylesheet" type="text/css" href="css/Style.css">
<script type="text/javascript" src="js/jscript.js"></script>
<script type="text/javascript" src="js/jq1.js"></script>

<script>
function setFocus() {
    document.login_form.uname.select();
    document.login_form.uname.focus();
}
</script>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: "Segoe UI", sans-serif;
    background: #d9d9d9;
}

.login-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 100px;
}

.login-card {
    width: 420px;
    background: #ffffff;
    border-radius: 12px;
    padding: 18px 20px 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    animation: fade-in 0.5s ease;
}

@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.login-card h1 {
    text-align: center;
    color: #333;
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 5px;
}

.login-description {
    text-align: center;
    margin: 5px 0 10px;
    color: #555;
    font-size: 18px;
}

#lock {
    width: 60px;
    height: 60px;
    background: url('images/lock.png') no-repeat center;
    background-size: contain;
    margin: 0 auto 15px;
}

.field {
    width: 85%;
    margin: 15px auto;
    padding: 12px;
    border-radius: 10px;
    background: #f3f3f3;
    border: 1px solid #cccccc;
}

.field label {
    display: block;
    text-align: center;
    margin-bottom: 6px;
    font-size: 15px;
    font-weight: bold;
    color: #333;
}

.field input {
    width: 75%;
    margin: 0 auto;
    display: block;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #bfbfbf;
    background: #ffffff;
    color: #333;
    font-size: 16px;
}

.login-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    margin-top: 28px;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
    color: #fff;
    background: #003366;
    transition: 0.3s ease;
}

.login-btn:hover {
    background: #005599;
    transform: scale(1.04);
}

.msg {
    display: block;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #cc0000;
}
</style>

</head>

<body onload="setFocus()">

<?php
/* ---------------------------------
   FORM FIELD DEFINITIONS
---------------------------------- */
$Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));

$Form_Fields[] = array('1','1','User Name','uname','','*','E|N|0','','');
$Form_Fields[] = array('1','2','Password','upass','','*','E|N|0','','');
$Form_Fields[] = array('1','5','','Login_Submit','Login','','','','');

foreach($Form_Fields as $Forms){
    if($Forms[5] == '*'){
        $Jvalid_Arr[$Forms[3]] .= $Forms[3].",";
        $Jvalid_Type_Arr[$Forms[6]] .= $Forms[6].",";
    }
}

$Jvalid_Arr_Join = substr(join('',$Jvalid_Arr),0,-1);
$Jvalid_Type_Arr_Join = substr(join('',$Jvalid_Type_Arr),0,-1);

/* ---------------------------------
   LOGIN PROCESS
---------------------------------- */
if (isset($_REQUEST['Login_Submit']) || ($_REQUEST['dologin'] == 'Login')) {

    $Username = $_REQUEST['uname'];
    $Password = $_REQUEST['upass'];

    $Login_Sql = "SELECT * FROM user_master 
                  WHERE Username='$Username' AND Password='$Password'";
    $Login_Run = mysqli_query($db, $Login_Sql);

    if (mysqli_num_rows($Login_Run) >= 1) {

        $Login_Result = mysqli_fetch_array($Login_Run);
        $Random = rand(0,99999);

        $Cook_Variable =
            $Login_Result['Username']."|".
            $Random."|".
            $Login_Result['User_Type_ID']."|".
            $Login_Result['Account_ID']."|".
            $Login_Result['Db_Name']."|".
            $Login_Result['Parent_ID'];

        setcookie($Cook_Name, $Cook_Variable, time()+86400);

        // ✅ Redirect ONLY for nextgenscada
        if ($Login_Result['Username'] === 'nextgenscada') {
            header("Location: Home.php");
            exit;
        }

        // All other users remain on this page

    } else {
        $war_msg = error_report("Oops! Username or Password wrong");
    }
}
?>

<div class="login-wrapper">
    <div class="login-card">

        <h1><?=$Site_Heading?></h1>
        <p class="login-description">Please enter your login credentials</p>

        <div id="lock"></div>

        <?php if ($war_msg) { ?>
            <span class="msg"><?=$war_msg?></span>
        <?php } ?>

        <form method="post" name="login_form"
              onsubmit="return FormValid('<?=$Jvalid_Arr_Join?>','<?=$Jvalid_Type_Arr_Join?>');">

            <div class="field">
                <label>User Name</label>
                <input type="text" name="uname" autocomplete="off">
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="upass" autocomplete="off">
            </div>

            <button type="submit" name="Login_Submit" class="login-btn">
                Login
            </button>

        </form>

    </div>
</div>

</body>
</html>
