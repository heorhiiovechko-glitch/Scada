<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$page = $_SERVER['PHP_SELF'];
//$sec = "30";
?>
<!--<meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'">-->

<script type="text/javascript">
    setInterval("my_function();",60000);
    function my_function(){
      $("#dash_info").load("<?php echo base_url() . 'dashboard/index'?> #dash_info" );
    }

  </script>


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
    <!--<div id="load"></div>

    <div id="contents">-->
        <main class="main">
            <!-- Breadcrumb-->
            <!--<ol class="breadcrumb">-->
                
                <tr ><td>&nbsp; </td ></tr>
            <!--</ol>-->
            <div  class="container-fluid">
                <div class="animated fadeIn">
				<tr><td>
                    <div id="dash_info" class="row">
					
                        <?php
						$index = 1;
                        foreach ($response as $key => $val) {
                            ?>
                            <div class="col-sm-4 col-lg-3">
							
                                <div class="card text-white <?php echo 'bg-white'; ?> tile-box">
                                    <div class="card-body pb-0">
                                        <div class="text-value" >
                                            <?php if ($val['count'] != 0) { ?>
                                                <a href="<?php echo base_url() . 'dashboard/index?status=' . $key; ?>" style ="color : <?php echo $key; ?>" >    <?php echo $val['name'] . ' : ' . $val['count']; ?> </a>
                                            <?php } else { ?>
                                                <div class="text-value"style ="color : <?php echo $key; ?>" >
                                                    <?php echo $val['name'] . ' : ' . $val['count']; ?>

                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="text-value"style ="color : <?php echo $key; ?>" >Total WTG :
                                            <?php echo $val['total']; ?>
                                        </div>
										<!--<div class="text-value"style ="color : <?php echo $key; ?>" >
                                            <?php echo date("H:i:s"); ?>
                                        </div>-->
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
                    </div> </td></tr>
                    <!-- /.row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!--<div class="card-header">Status</div>-->
								<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
                                <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/chart.js/dist/Chart.min.js"></script>
                                <div class="card-body">
										<label style="  font-weight: 600;font-size: 1.5vw ">  Average Wind Speed & GAD </label>
                                            <!--<div id='loadingmessage' style='display:none'>
                                                <img class="center" src="<?php echo base_url(); ?>assets/images/box/giphy.gif">
                                            </div>-->
                                            <canvas id="mycanvas"  width="800" height="200"></canvas>
										
                                </div>
                            </div>
                        </div>





                    </div>

                </div>
            </div>
        </main>
       <!-- <aside class="aside-menu">
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
           
        </aside>-->
    <!--</div>-->
</body>
<?php $this->load->view('layout/footer'); ?>
<!--<script>
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
</script>-->
<script type='text/javascript'>
    $(document).ready(function () {

        $(function () {
            setTimeout(loadajax, 300);
        });

//        $(function () {
//            setTimeout(loadgad, 1000);
//        });


    })
</script>

<script type='text/javascript'>
    function loadajax() {
       // $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>ajax/ajax_windspeed_power",
            dataType: 'json',
            success: function (data) {
                //$('#loadingmessage').hide();
                //do action
                var avg_windspeed_value = JSON.parse(data['avg_windspeed_value']);
                var gad_value = JSON.parse(data['gad_value']);

                var avg = [];
                var dev = [];
                for (var i in avg_windspeed_value) {
                    dev.push(avg_windspeed_value[i].device_name);
                    avg.push(avg_windspeed_value[i].avg_windspeed);
                }
                var curtgad = [];
                // var dev = [];
                for (var i in gad_value) {
                    // dev.push(avg_power_value[i].device_name);
                    curtgad.push(gad_value[i].gad);
                }
                var ctx = $("#mycanvas");
              var data = {
            labels: dev,
            //width: 10,
            datasets: [{
                    label: 'WindSpeed',
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
                    label: 'GAD',
                    data: curtgad,
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
			maintainAspectRatio: true,
			//scaleBeginAtZero: true,
            legend: {
               
                display: true,
                labels: {
                    // This more specific font property overrides the global property
                    fontStyle: "bold",
                    fontSize: 15,
                },
                // onClick: (e) => e.stopPropagation()
            },
			tooltips: {
									//mode: 'nearest',
									//intersect: false,
									position: 'nearest',
									fontSize: 24,
									titleFontSize: 24,
									bodyFontSize: 24,
									bodyAlign: 'center',
									xPadding: 12,
									yPadding: 12,
									caretSize: 10,
									yAlign: "bottom"
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
                            labelString: 'GAD(Kwh)',
                            fontStyle: "bold",
                            fontSize: 15
                        }
                    }],
                xAxes: [{
                        //barThickness: 15,
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

				if (window.myChart) window.myChart.destroy();
                window.myChart = new Chart(ctx, {
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

