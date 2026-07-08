<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$region_list = $this->Common_model->get_region_site_list();
foreach ($region_list as $list) {
    $dev_name = $list['Device_Name'];
    $list['Device_Name'] = ['Device_Name' => $dev_name, 'IMEI' => $list['IMEI'], 'Format_Type' => $list['Format_Type']];
    $menu[$list['Region']][$list['Site_Location']][] = $list['Device_Name'];
}
//echo '<pre>';print_r($menu);exit;
//if (!empty($menu)) {
//    foreach ($menu as $key => $sub) {
//
//        foreach ($sub as $key1 => $val) {
//            foreach ($val as $device) {
//                echo '<pre>';print_r($device);exit;
//            }
//        }
//    }
//}
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
        <title>SCADA</title>
        <!-- Icons-->
        <!--<link href="<?php echo base_url(); ?>assets/vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">-->
        <!--<link href="<?php echo base_url(); ?>assets/vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">-->
        <!--<link href="<?php echo base_url(); ?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">-->
        <!--<link href="<?php echo base_url(); ?>assets/vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">-->
        <link href="<?php echo base_url(); ?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- bootstrap-datetimepicker -->
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />-->
        <!-- Bootstrap Select2 -->
        <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />-->
        <!-- Main styles for this application-->
        <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
        <!--<link href="<?php echo base_url(); ?>assets/css/select2-bootstrap4.min.css" rel="stylesheet">-->
        <!--<link href="<?php echo base_url(); ?>assets/vendors/pace-progress/css/pace.min.css" rel="stylesheet">-->
        <!-- Global site tag (gtag.js) - Google Analytics-->
        <!--<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>-->
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
    <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
        <header class="app-header navbar">
            <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img class="navbar-brand-full" src="<?php echo base_url(); ?>assets/images/Ver.jpg" width="125" height="50" alt="CoreUI Logo">
                <img class="navbar-brand-minimized" src="<?php echo base_url(); ?>assets/images/brand/sygnet.svg" width="30" height="30" alt="CoreUI Logo">
            </a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="navbar-toggler-icon" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?php echo base_url() . 'logout'; ?>">
                            <i class="fa fa-lock"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </header>
        <div class="modal"></div>
        <div class="app-body">
            <div class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link">
                                <img class="img-avatar" src="<?php echo base_url(); ?>assets/images/user.png" alt="" width="35" height="35">
                                <span>Welcome, </span>
                                <span><?php echo $this->session->userdata('username'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url() . 'dashboard'; ?>">
                                <i class="nav-icon icon-drop"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" target="_blank" href="<?php echo base_url() . 'dashboard/park_view'; ?>">
                                <i class="nav-icon icon-pencil"></i> Park View</a>
                        </li>
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="nav-icon icon-puzzle"></i> Region Wise
                            </a>
                            <ul class="nav-dropdown-items">
                                <?php
                                if (!empty($menu)) {
                                    foreach ($menu as $key => $sub) {
                                        ?>
                                        <li class="nav-item nav-dropdown">
                                            <a class="nav-link nav-dropdown-toggle" href="#">
                                              <!-- <i class="nav-icon icon-puzzle"></i> --><i class="fa fa-puzzle-piece"></i> <?php echo $key; ?>
                                            </a>
                                            <ul class="nav-dropdown-items child_menu">
                                                <?php foreach ($sub as $key1 => $val) { ?>
                                                    <li class="nav-item nav-dropdown">
                                                        <a class="nav-link nav-dropdown-toggle" href="#">
                                                          <!-- <i class="nav-icon icon-puzzle"></i> --><i class="fa fa-thermometer-empty"></i> <?php echo $key1; ?>
                                                        </a>
                                                        <ul class="nav-dropdown-items child_sub_menu">
                                                            <?php foreach ($val as $device) { ?>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" target="_blank" href="<?php echo base_url() . 'dashboard/device_details?id=' . $device['IMEI'] . '&type=' . $device['Format_Type']; ?>"  target="_blank">
                                                                        <i class="fa fa-bar-chart"></i> <?php echo $device['Device_Name']; ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                <?php } ?> 
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="nav-icon icon-cursor"></i> Analytics</a>
                            <ul class="nav-dropdown-items">
                                <li class="nav-item">
                                    <a class="nav-link" target="_blank" href="<?php echo base_url() . 'dashboard/temp_analysis'; ?>">
                                        <i class="fa fa-area-chart"></i> Temperature</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" target="_blank" href="<?php echo base_url() . 'dashboard/powercurve_analysis'; ?>">
                                        <i class="fa fa-area-chart"></i> Power Curve</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" target="_blank" href="<?php echo base_url() . 'dashboard/performance_analysis'; ?>">
                                        <i class="fa fa-area-chart"></i> Performance</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#">
                                <i class="nav-icon icon-cursor"></i> Reports</a>
                            <ul class="nav-dropdown-items">
                                <li class="nav-item">
                                    <a class="nav-link" target="_blank" href="<?php echo base_url() . 'dashboard/power_windspeed_report'; ?>">
                                        <i class="fa fa-area-chart"></i> Power VS Wind speed</a>
                                </li>     
                            </ul>
                            <ul class="nav-dropdown-items">
                                <li class="nav-item">
                                    <a class="nav-link" target="_blank" href="<?php echo base_url() . 'dashboard/performance_report'; ?>">
                                        <i class="fa fa-area-chart"></i> Performance Report</a>
                                </li>     
                            </ul>
                        </li>
                        <li class="divider"></li>
                        <li class="nav-divider"></li>
                        <li class="nav-item px-3 d-compact-none d-minimized-none">
                            <div class="text-uppercase mb-1">
                                <small>
                                    <b>Avg Wind Speed</b>
                                </small>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted"><?php echo $this->session->userdata('avgWindSpeedSum') . 'm/s'; ?></small>
                        </li>

                        <li class="nav-item px-3 d-compact-none d-minimized-none">
                            <div class="text-uppercase mb-1">
                                <small>
                                    <b>Total Power</b>
                                </small>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted"><?php echo $this->session->userdata('powerSpeedSum') . 'MW'; ?></small>
                        </li>

                        <li class="nav-item px-3 mb-3 d-compact-none d-minimized-none">
                            <div class="text-uppercase mb-1">
                                <small>
                                    <b>Total Export Today</b>
                                </small>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted"><?php echo $this->session->userdata('patGenSum') . 'Kwh'; ?></small>
                        </li>
                        <!--<li class="nav-item">
                          <a class="nav-link" href="charts.html">
                            <i class="nav-icon icon-pie-chart"></i> Avg Wind Speed: <?php echo $this->session->userdata('avgWindSpeedSum') . 'm/s'; ?></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="charts.html">
                            <i class="nav-icon icon-pie-chart"></i> Total Power: <?php echo $this->session->userdata('powerSpeedSum') . 'MW'; ?></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="charts.html">
                            <i class="nav-icon icon-pie-chart"></i> Total Export Today: <?php echo $this->session->userdata('patGenSum') . 'Kwh'; ?></a>
                        </li>-->
                    </ul>
                </nav>
                <button class="sidebar-minimizer brand-minimizer" type="button"></button>
            </div>