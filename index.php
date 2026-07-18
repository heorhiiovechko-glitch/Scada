<?php
	ob_start();
	include("includes.php");

	if(isset($_POST['signin_submit'])){
		$username=$_POST['username'];
		$pass=$_POST['password'];
		$Mysql_Query="SELECT Firstname,Lastname,Username,Password,Account_ID,User_Type_ID,In_Time,Out_Time,Parent_ID,Db_Name FROM user_master WHERE Username= '$username' AND Password= '$pass'";

		if (!$queryResult = $db->query($Mysql_Query)) {
			die($db->error);
		}

		if($queryResult->num_rows > 0) {
			$result = $queryResult->fetch_array();
			$Random = rand(0,99999);
			$Cook_Variable = $result['Username']."|".$Random."|".$result['User_Type_ID']."|".$result['Account_ID']."|".$result['Firstname']."|".$result['Lastname']."|".$result['Parent_ID']."|".$result['Db_Name']."|".$result['Password'];
			setcookie($Cook_Name, $Cook_Variable, time()+86400);

			switch ($username) {
				case 'nextgenscada': header("Location: channel1_all.php"); break;	
				case 'power2mw': header("Location: channel1_shanmugam.php"); break;					
		        case 'suspend':header("Location: account_suspend.php"); break;							
				default: header("Location: dashboard.php"); break;
			}
			exit;
		} else {
			$Msg = "<span style='color:#ff4d4d;'>Username or Password is incorrect</span>";
		}
	}  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SCADA - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <style>
    body {
        background: linear-gradient(135deg, #06121f, #0b2c45, #134e6f);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f5f7fa;
        min-height: 100vh;
    }

    .login-container {
        margin-top: 9%;
        padding: 40px;
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(0, 255, 255, 0.12);
        border-radius: 20px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(12px);
        animation: fadeInUp 1s ease-in-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        font-size: 28px;
        margin-bottom: 25px;
        text-align: center;
        font-weight: bold;
        color: #4fd1ff;
        text-shadow: 0 0 12px rgba(79, 209, 255, 0.6);
    }

    .form-group label {
        color: #d6e6f2;
        font-weight: 600;
    }

    .form-control {
        border-radius: 30px;
        padding: 12px 20px;
        background-color: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.08);
        height: 46px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #4fd1ff;
        box-shadow: 0 0 12px rgba(79, 209, 255, 0.5);
        background-color: rgba(255,255,255,0.12);
    }

    .form-control::placeholder {
        color: #cfd8dc;
    }

    .btn-login {
        background: linear-gradient(45deg, #00bcd4, #2196f3);
        border: none;
        border-radius: 30px;
        width: 100%;
        padding: 12px;
        font-weight: bold;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-login:hover {
        background: linear-gradient(45deg, #2196f3, #00bcd4);
        box-shadow: 0 0 18px rgba(33, 150, 243, 0.7);
        transform: scale(1.03);
    }

    .contact-info {
        margin-top: 25px;
        font-size: 13.5px;
        color: #d9e3ea;
        line-height: 1.8;
        text-align: center;
    }

    .contact-info b {
        color: #4fd1ff;
    }

    .text-danger {
        color: #ff6b6b !important;
        margin-top: 5px;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 col-md-offset-4 login-container">
                <div class="login-header">SCADA Login</div>
                <?php if(isset($Msg)) echo $Msg; ?>
                <form method="post" action="" onsubmit="return valForm();" name="form1">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
                        <div id="uname" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                        <div id="upass" class="text-danger"></div>
                    </div>
                    <button type="submit" class="btn btn-login" name="signin_submit">Sign In</button>
                </form>

                <div class="contact-info">
                    
                    <p><b>Service Support:</b> 98849 40814 / 97910 40814</p>
                    
                </div>
				
                

            </div>
        </div>
    </div>

    <script>
        function valForm() {
            let username = document.getElementById('username').value.trim();
            let password = document.getElementById('password').value.trim();
            let valid = true;

            if (username === '') {
                document.getElementById('uname').innerHTML = 'Please enter your username';
                valid = false;
            } else {
                document.getElementById('uname').innerHTML = '';
            }

            if (password === '') {
                document.getElementById('upass').innerHTML = 'Please enter your password';
                valid = false;
            } else {
                document.getElementById('upass').innerHTML = '';
            }

            return valid;
        }
    </script>
</body>
</html>
