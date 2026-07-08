<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo "<pre>"; print_r($tempAna); exit;
error_reporting(0);
?>
<style>
    .searchable-container{margin:20px 0 0 0}
    .searchable-container label.btn-default.active{background-color:#007ba7;color:#FFF}
    .searchable-container label.btn-default{width:90%;border:1px solid #efefef;margin:5px; box-shadow:5px 8px 8px 0 #ccc;}
    .searchable-container label .bizcontent{width:100%;}
    .searchable-container .btn-group{width:90%}
    .searchable-container .btn span.glyphicon{
        opacity: 0;
    }
    .searchable-container .btn.active span.glyphicon {
        opacity: 1;
    }

    .searchable-container .bizcontent input[type="checkbox"] {
        position: absolute;
        clip: rect(0,0,0,0);
        pointer-events: none;
    }
</style>
<main class="main">
    <!-- Breadcrumb-->
   <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Performance Trending Chart</li>
    </ol>-->
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="font-size:18px;"> Heat Map - Today</div>
                        <div class="card-body">
                            
                                        
                                            <div class="row">
											
											<?php $x = 1; ?>
                                                <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
                                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
												
                                                <?php foreach ($devicelist_perf as $deviceName => $deviceArrVal) { ?>
                                                    <?php $id = "chartContainer" . $x; ?> 
													
                                                    <?php if (!empty($deviceArrVal)) { ?>
													<div class="card-body" style="width: 650px;height:20px; "> 
													<canvas id=<?php echo $id; ?>  style="width: 650px;height:40px; "></canvas>
                                                    <?php } ?>
													</div>
													
                                                        <?php
                                                        $x++;
                                                    }
                                                    ?>
													</div>
                                           
                                       
                                 
								 </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>
            <!-- /.row-->
        </div>
    </div>
</main>
<?php $this->load->view('layout/footer'); ?>

<script type="text/javascript">
     window.onload = function () {
	if (window.myChart) window.myChart.destroy();
    var device_perflist = <?php echo json_decode($device_perfomance); ?>;
    var index = 1;
// console.log(device_perflist);
// var arr_data1 = ['0', '1', '2', 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
    for (var i in device_perflist) {
        for (var j in device_perflist[i]) {
            //   console.log(device_perflist[i][j]);
            var dev = [];
            var colour = [];
            var devperf = [];
            var sta = [];
            var yvalue = [];
            // console.log(device_perflist[i][j]);
            for (var k in device_perflist[i][j]) {
                //  console.log(device_perflist[i]);
                dev.push(device_perflist[i][j][k].device_name);
                colour.push(device_perflist[i][j][k].colour);
                devperf.push(device_perflist[i][j][k].Time_S);
                sta.push(device_perflist[i][j][k].Status);
                yvalue.push(device_perflist[i][j][k].y);
            }

            //console.log(device_perflist[i]);
            //console.log(colour);
            //var ctx = document.getElementById('chartContainer' + index).getContext('2d');
			var ctx = $("#chartContainer" + index);
			//if (window.myChart) window.myChart.destroy();
            window.myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: devperf, // list of races,
                    datasets: [{
                            label: 'Device Perfomance',
                            data: yvalue,
                            backgroundColor: colour,
                            pointBackgroundColor: colour,
                            pointBorderColor: colour,
                            borderColor: colour,
                            borderWidth: 1,
                            fill: true,
                            // lineTension: 0,
                            // pointStyle:circle,
                            pointRadius: 4,
                            radius: 0,
                        }/*,

                        {
                            label: 'Device Perfomance',
                            data: [],
                            backgroundColor: '#808080',
                            pointBackgroundColor: '#808080',
                            pointBorderColor: '#808080',
                            borderColor: colour,
                            borderWidth: 1,
                            fill: true,
                            // lineTension: 0,
                            // pointStyle:circle,
                            pointRadius: 10,
                            radius: 5,
                        }*/
                    ]
                },
                options: {
					responsive: true,
					maintainAspectRatio: true,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'nearest',
						intersect: true,
						
                    },
                    scales: {

                        yAxes: [{
                                ticks: {
                                    max: 0,
                                    min: 0,
                                    display: false,
									beginAtZero: true,
                                },
                                gridLines: {
                                    color: 'transparent',
                                    zeroLineColor: 'transparent'
                                },
                            }],
                        xAxes: [{
                                type: 'time',
								//display: false,
                                //   data: devperf,
                                // backgroundColor: colour,
                                time: {
                                    parser: 'm:s.SSS',
                                    unit: 'seconds',
                                    unitStepSize: 60,
                                    min: '0',
                                    max: '24',
									//display: true,
                                    //data:devperf,
                                    displayFormats: {
                                        'seconds': 'm'
                                    }
                                },
								ticks: {
                                    
                                    display: false,
									//beginAtZero: true,
                                },
								
                                gridLines: {
                                    color: 'transparent',
                                    //zeroLineColor: 'transparent'
                                },
                            }]
                    }

                }
            });
            i++;
            j++, index++;
        }
    }
  };
</script>