<?php
include("header.php");
include("Includes.php");

if(empty($_COOKIE[$Cook_Name])){
    header("Location:index.php");
    exit;
}

$Cook_Variable = explode("|",$_COOKIE[$Cook_Name]);	
$Username = $Cook_Variable[0];

$Message = "";

if(isset($_POST['Submit'])) {

    $Current_Password = $_POST['Current_Password'];
    $New_Password = $_POST['New_Password'];
    $Confirm_Password = $_POST['Confirm_Password'];

    $Sql = "SELECT * FROM user_master WHERE Username = '$Username'";
    $Result = mysql_query($Sql);
    $Rows = mysql_fetch_array($Result);

    $Password = $Rows['Password'];

    if($Current_Password == $Password) {

        mysql_query("UPDATE user_master SET Password ='$New_Password' WHERE Username = '$Username'");
        $Message = "Password Changed Successfully";

    } else {
        $Message = "Current Password is Incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>

<style>
body {
    font-family: "Segoe UI", sans-serif;
    margin: 0;
    padding: 0;

    /* NEW HEADER-COLOR MATCHED ANIMATED BACKGROUND */
    background: linear-gradient(135deg, 
        #001A33, 
        #003366, 
        #005599, 
        #0077CC
    );
    
    background-size: 400% 400%;
    animation: headerGradient 12s ease infinite;
}

/* Animation for smooth light → dark → light flow */
@keyframes headerGradient {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}



/* Card container */
.card {
    width: 380px;
    margin: 80px auto;
    background: #fff;
    border-radius: 18px;
    padding: 30px 25px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.15);
    animation: fadeIn 0.8s ease-out;
}

/* Title */
.card h2 {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    background: linear-gradient(45deg, #007bff, #00c6ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

    /* NEW FIXES */
    white-space: normal;      /* Allows line wrapping */
    word-break: break-word;   /* Breaks long text */
    line-height: 1.2;         /* Better spacing */
    padding: 0 10px;          /* Prevents touching edges */
}

/* Animated Fade In */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Floating label container */
.field {
    position: relative;
    margin-top: 28px;
}

.field input {
    width: 100%;
    padding: 12px 10px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 8px;
    outline: none;
    transition: 0.3s;
}

.field input:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0,123,255,0.4);
}

/* Floating Label */
.field label {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: #fff;
    padding: 0px 5px;
    color: #777;
    pointer-events: none;
    transition: 0.3s;
}

.field input:focus + label,
.field input:not(:placeholder-shown) + label {
    top: -8px;
    left: 9px;
    font-size: 12px;
    color: #007bff;
}

/* Buttons */
.btn-submit {
    width: 100%;
    margin-top: 28px;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 17px;
    font-weight: bold;
    background: linear-gradient(45deg, #007bff, #00c6ff);
    color: #fff;
    cursor: pointer;
    transition: 0.3s ease;
}

.btn-submit:hover {
    transform: scale(1.03);
}

/* Shake animation for error */
.shake {
    animation: shakeAnim 0.4s ease;
}

@keyframes shakeAnim {
    0%,100% { transform: translateX(0); }
    25% { transform: translateX(-6px); }
    50% { transform: translateX(6px); }
    75% { transform: translateX(-4px); }
}

/* Message */
.msg {
    margin-top: 15px;
    text-align: center;
    font-weight: bold;
    color: #ff4444;
}

.msg.success {
    color: #00a600;
}

/* Back link */
a {
    text-decoration: none;
    display: block;
    margin-top: 15px;
    text-align: center;
    font-weight: bold;
    color: #fff;
}

.back-btn {
    display: inline-block;
    padding: 8px 20px;
    margin-top: 20px;

    /* TEXT ALWAYS WHITE */
    color: #ffffff !important;
    font-weight: bold;
    font-size: 15px;
    text-decoration: none;

    /* Background box */
    background-color: #003366;  /* Header color */
    border-radius: 8px;

    /* Animation */
    transition: 0.3s ease-in-out;
}

.back-btn:hover {
    background-color: #005599; /* Lighter header shade */
    color: #ffffff !important; /* Keep text white on hover */
    transform: scale(1.05);
}

</style>

<script>
function validate() {
    let form = document.frm;
    let error = false;

    // reset animation
    document.querySelector(".card").classList.remove("shake");

    if (form.Current_Password.value.trim() == "") {
        error = true;
    }
    if (form.New_Password.value.trim() == "") {
        error = true;
    }
    if (form.Confirm_Password.value.trim() == "") {
        error = true;
    }
    if (form.New_Password.value != form.Confirm_Password.value) {
        error = true;
    }

    if (error) {
        document.querySelector(".card").classList.add("shake");
        return false;
    }
    return true;
}


// Disable right click
document.addEventListener('contextmenu', event => event.preventDefault());

// Disable common inspect keys
document.onkeydown = function(e) {
    if (e.keyCode == 123) { // F12
        return false;
    }
    if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 67 || e.keyCode == 74)) {
        return false;
    }
    if (e.ctrlKey && e.keyCode == 85) { // Ctrl+U
        return false;
    }
};

</script>

</head>
<body>

<form method="post" name="frm" id="frm" onsubmit="return validate();">

<div class="card">
    <h2>Change Password</h2>

    <div class="field">
        <input type="password" name="Current_Password" placeholder=" " autocomplete="off">
        <label>Current Password</label>
    </div>

    <div class="field">
        <input type="password" name="New_Password" placeholder=" " autocomplete="off">
        <label>New Password</label>
    </div>

    <div class="field">
        <input type="password" name="Confirm_Password" placeholder=" " autocomplete="off">
        <label>Confirm Password</label>
    </div>

    <button type="submit" name="Submit" class="btn-submit">Update Password</button>

    <?php if($Message != "") { ?>
        <div class="msg <?= ($Message=='Password Changed Successfully')?'success':'' ?>">
            <?= $Message ?>
        </div>
    <?php } ?>

</div>

</form>

<a href="channel1.php" class="back-btn">Back</a>


<?php include("footer.php"); ?>
</body>
</html>
