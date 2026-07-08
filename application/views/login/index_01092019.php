<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Versatile Scada">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Login | Versatile Scada</title>
    <!-- Icons-->
    <link href="<?php echo base_url()?>assets/vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="<?php echo base_url()?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Bootstrap ID
      gtag('config', 'UA-118965717-5');
    </script>
  </head>
  <body class="app flex-row align-items-center">
    <div class="container">
        <form action="<?= base_url().'login/loginprocess'; ?>" method="post" data-toggle="validator" role="form" autocomplete="off">
          <div class="row justify-content-center">
                <div class="col-md-4">
                  <div class="card-group">
                    <div class="card p-4">
                      <div class="card-body">
                        <h1>Login</h1>
                        <?php
                            if(!empty($message)){
                        ?>
                            <script>
                                setTimeout(function() {
                                    $("#error_msg").hide();
                                }, 2000);
                            </script>
                            <span id="error_msg" class="error_msg" > <?php echo $message; ?></span>
                        <?php }?>
                        <p class="text-muted">Sign In to your account</p>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <i class="icon-user"></i>
                            </span>
                          </div>
                          <input class="form-control" type="text" name="username" placeholder="Username">
                        </div>
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <i class="icon-lock"></i>
                            </span>
                          </div>
                          <input class="form-control" name="password" type="password" placeholder="Password">
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <button class="btn btn-primary px-4" type="submit">Login</button>
                          </div>
                          <div class="col-6 text-right">
                            <button class="btn btn-link px-0" type="button">Forgot password?</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
      </form>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?php echo base_url()?>assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url()?>assets/vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?php echo base_url()?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url()?>assets/vendors/pace-progress/pace.min.js"></script>
    <script src="<?php echo base_url()?>assets/vendors/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script src="<?php echo base_url()?>assets/vendors/@coreui/coreui/dist/js/coreui.min.js"></script>
  </body>
</html>
   


