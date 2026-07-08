<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$page = $_SERVER['PHP_SELF'];
$sec = "300";
?>
<meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'">
<style>
    #load{
        width:100%;
        height:100%;
        position:fixed;
        z-index:9999;
        background:url("https://www.creditmutuel.fr/cmne/fr/banques/webservices/nswr/images/loading.gif") no-repeat center center rgba(0,0,0,0.25)
    }

    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }
</style>
<body>
    <div id="load"></div>

    <div id="contents">
        <main class="main">
            <!-- Breadcrumb-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">
                    <a href="#">Admin</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="row">
                        <?php
                        $index = 1;
                        foreach ($response as $key => $val) {
                            ?>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card text-white <?php echo 'bg-white'; ?> tile-box">
                                    <div class="card-body pb-0">
                                        <div class="text-value" >
                                            <?php if ($val['count'] != 0) { ?>
                                                <a href="<?php echo base_url() . 'dashboard/index?status=' . $key; ?>" style ="color : <?php echo $key; ?>" target="_blank">    <?php echo $val['name'] . ' : ' . $val['count']; ?> </a>
                                            <?php } else { ?>
                                                <div class="text-value"style ="color : <?php echo $key; ?>" >
                                                    <?php echo $val['name'] . ' : ' . $val['count']; ?>

                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="text-value"style ="color : <?php echo $key; ?>" >Total WTG :
                                            <?php echo $val['total']; ?>
                                        </div>
                                    </div>
                                    <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                                        <!-- <canvas class="chart" id="card-chart<?php echo $index; ?>" height="70"></canvas> -->
                                        <img class="img-fluid" src="<?php echo base_url(); ?>assets/images/box/<?php
                                        if ($key == "green") {
                                            echo $key . '.gif';
                                        } else {
                                            echo $key . '.png';
                                        }
                                        ?>" style="margin-top: -35px; margin-left: 168px"/>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $index++;
                        }
                        ?>
                        <!-- /.col-->
                    </div>
                    <!-- /.row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Status</div>
                                <div class="card-body">

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="chart-container">
                                            <label style="  font-weight: 600;font-size: 1.5vw ">  Average Wind Speed & Power </label>
                                            <div id='loadingmessage' style='display:none'>
                                                <img class="center" src="<?php echo base_url(); ?>assets/images/box/giphy.gif" >
                                            </div>
                                            <canvas id="mycanvas" style="display: inline-block; width: 800px; height: 400px; vertical-align: top;" width="800" height="200"></canvas>
                                        </div>

                                        <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
                                        <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/chart.js/dist/Chart.min.js"></script>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="chart-container">
                                            <label style="  font-weight: 600;font-size: 1.5vw ">  Export GAD </label>
                                            <div id='loadingmessage' style='display:none'>
                                                <img class="center" src="<?php echo base_url(); ?>assets/images/box/giphy.gif" >
                                            </div>
                                            <canvas id="mygadcanvas" style="display: inline-block; width: 600px; height: 150px; vertical-align: top;" width="800" height="200"></canvas>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>





                    </div>

                </div>
            </div>
        </main>
        <aside class="aside-menu">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">
                        <i class="icon-list"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                        <i class="icon-speech"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                        <i class="icon-settings"></i>
                    </a>
                </li>
            </ul>
            <!-- Tab panes-->

        </aside>
    </div>
</body>
<?php $this->load->view('layout/footer'); ?>
<script>
    document.onreadystatechange = function () {
        var state = document.readyState
        if (state == 'interactive') {
            document.getElementById('contents').style.visibility = "hidden";
        } else if (state == 'complete') {
            setTimeout(function () {
                document.getElementById('interactive');
                document.getElementById('load').style.visibility = "hidden";
                document.getElementById('contents').style.visibility = "visible";
            }, 1000);
        }
    }
</script>
<script type='text/javascript'>
    $(document).ready(function () {

        $(function () {
            setTimeout(loadajax, 1000);
        });

        $(function () {
            setTimeout(loadgad, 1000);
        });


    })
</script>

<script type='text/javascript'>
    function loadajax() {
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>ajax/ajax_windspeed_power",
            dataType: 'json',
            success: function (data) {
                $('#loadingmessage').hide();
                //do action
                var avg_windspeed_value = JSON.parse(data['avg_windspeed_value']);
                var avg_power_value = JSON.parse(data['avg_power_value']);

                var avg = [];
                var dev = [];
                for (var i in avg_windspeed_value) {
                    dev.push(avg_windspeed_value[i].device_name);
                    avg.push(avg_windspeed_value[i].avg_windspeed);
                }
                var avgpower = [];
                // var dev = [];
                for (var i in avg_power_value) {
                    // dev.push(avg_power_value[i].device_name);
                    avgpower.push(avg_power_value[i].avg_power);
                }
                var ctx = $("#mycanvas");
                var data = {
                    labels: dev,
                    width: 320,
                    datasets: [{
                            label: 'Wind Speed',
                            data: avg,
                            backgroundColor: '#98FB98',
                            borderColor: '#98FB98',
                            pointBackgroundColor: '#7FFF00',
                            borderWidth: 1,
                            fill: false,
                            lineTension: 0.3,
                            radius: 5,
                            type: 'line',
                            yAxisID: 'A',
                        }, {
                            label: 'Power',
                            data: avgpower,
                            backgroundColor: '#FFD700',
                            borderColor: '#FFD700',
                            borderWidth: 1,
                            fill: false,
                            type: 'bar',
                            yAxisID: 'B',
                        }
                    ]
                };
                var options = {
                    responsive: true,
//            legend: {
//                display: false
//            }, 
                    tooltips: {
                        bodyFontSize: 15
                    },
                    legend: {

                        display: true,
                        labels: {
                            // This more specific font property overrides the global property
                            fontStyle: "bold",
                            fontSize: 15
                        },
                        onClick: (e) => e.stopPropagation()
                    },
                    scales: {
                        yAxes: [{
                                id: 'A',
                                type: 'linear',
                                position: 'left',
                                ticks: {
                                    fontStyle: "bold",
                                    min: 0,
                                    beginAtZero: true
                                },
                                gridLines: {
                                    color: 'transparent',
                                    zeroLineColor: 'transparent'
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Wind Speed(m/s)',
                                    fontStyle: "bold",
                                    fontSize: 15
                                }
                            }, {
                                id: 'B',
                                type: 'linear',
                                position: 'right',
                                gridLines: {
                                    color: 'transparent',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: {
                                    fontStyle: "bold",
                                    min: 0,
                                    beginAtZero: true
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Power(KW)',
                                    fontStyle: "bold",
                                    fontSize: 15
                                }
                            }],
                        xAxes: [{
                                barThickness: 15,
                                // barPercentage: 0.8,
                                ticks: {
                                    //stepSize: 1,
                                    //  min: 0,
                                    fontStyle: "bold",
                                    autoSkip: false,
                                    gridLines: {
                                        color: 'transparent',
                                        zeroLineColor: 'transparent'
                                    },
                                    beginAtZero: true
                                }
                            }]
                    }

                };
//        Chart.plugins.register({
//            
//        });


                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: options,
                    plugins: [{
                            beforeDraw: function (chartInstance, easing) {
                                var ctx = chartInstance.chart.ctx;
                                ctx.fillStyle = '#808080'; // your color here

                                var chartArea = chartInstance.chartArea;
                                ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
                            }
                        }]

                });
            }
        });
    }
</script>
<script type='text/javascript'>
    function loadgad() {
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>ajax/ajax_gad",
            dataType: 'json',
            success: function (data) {

                var export_gad = JSON.parse(data['export_gad']);
                var gad = [];
                var dev = [];
                for (var i in export_gad) {

                    if (export_gad[i].text == "Error") {
                        dev.push(export_gad[i].device_name + "(" + export_gad[i].text + ")");
                    } else {
                        dev.push(export_gad[i].device_name);
                    }

                    gad.push(export_gad[i].gad);
                }

                var ctx = $("#mygadcanvas");
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    title: {
                        text: 'Export GAD'
                                // subtext: 'Graph Sub-text'
                    },
                    data: {
                        labels: dev,
                        datasets: [{
                                label: 'Power',
                                data: gad,
                                backgroundColor: '#1F8A1D',
                                borderColor: '#1B5A1A',
                                borderWidth: 1,
                            }]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontStyle: "bold"
                                    },
                                    gridLines: {
                                        color: 'transparent',
                                        //          zeroLineColor: 'transparent'
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'GAD (KWH)',
                                        fontStyle: "bold",
                                        fontSize: 15
                                    }
                                }],
                            xAxes: [{
                                    barThickness: 15,
                                    ticks: {
                                        fontStyle: "bold",
                                        stepSize: 1,
                                        min: 0,
                                        autoSkip: false
                                    },
                                    gridLines: {
                                        color: 'transparent',
                                        //      zeroLineColor: 'transparent'
                                    },
                                }]
                        }
                    }
                });

            }
        });
    }
</script>

