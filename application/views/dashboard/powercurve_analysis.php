<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
// echo "<pre>"; print_r($tempAna); exit;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />


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
    .airforce-blue {
        color: #fff;
        background-color: #517fa4;
    }
</style>
<main class="main">
    <!-- Breadcrumb-->
   <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Power Curve</li>
    </ol> -->
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="font-size:18px;">Location Power Curve</div>
                        <div class="card-body">
                            <div class="row">
                             
                                            <div class="picker date col-md-3" id="date" >
                                                <label style="font-size:18px;"><h8>Start Date  </h8></label>&nbsp;&nbsp;&nbsp;
                                                <input class="form-control start_date" type="date" placeholder="Start Date" id="start_date">
                                            </div>
                                            <div class="picker date col-md-3" id="date" >
                                                <label style="font-size:18px;"><h8>End Date  </h8></label>&nbsp;&nbsp;
                                                <input class="form-control end_date" type="date" placeholder="End Date" id="end_date">
                                            </div>
                                         <div class="picker device col-md-3" >
                                    <label style="font-size:18px;"><h8>Device List:</h8></label>&nbsp;&nbsp;&nbsp;
                                    <select name="devicename" id="devicename" >
                                        <option selected="selected" style="font-size:18px;">Choose Device Name</option>
                                        <?php foreach ($powCurve['deviceList'] as $key => $value) { ?>
                                            <option style="font-size:18px;" value="<?= $value['Device_Name'] . "x" . $value['IMEI']; ?>"><?= $value['Device_Name']; ?></option>
                                        <?php }
                                        ?>
                                    </select> 
                                </div>

								<div class="col-md-3">
                                    <br/> &nbsp;&nbsp;&nbsp;
                                    <input type="button" class="btn btn-primary" onclick="getPowerCurve();" id="submit" value="Submit"  style="margin-left:50px;margin-right:50px;font-size:18px;"/>
                                </div>

                                      </div>
								
								<div style="text-align: center;">
                                                <span value="" id="vali_value"  style="color:red;font-size:18px;"></span>

                                            </div>
								<div style="text-align: center;">
                                                <span value="" id="vali_date"  style="color:red;font-size:18px;"></span>
                                            </div>
								<div style="text-align: center;">
                                            <span value="" id="dev_count"  style="color:red;font-size:18px;"></span>

                                        </div>			
											
									<br/>	
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header airforce-blue" id="temp0" style="font-size:18px;">WTG Power Curve Analysis</div>
                                                <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
                                                <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/chart.js/dist/Chart.min.js"></script>
                                                <div class="card-body">
                                                    <canvas id="mypowercanvas" style="height: 380px;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header airforce-blue" id="temp1" style="font-size:18px;">WTG Power Curve Analysis</div>
                                                <div class="card-body">
                                                    <canvas id="mypowercanvastwo" style="height: 380px;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
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
                                                $(document).ready(function () {

                                                  //  $("#start_date").datepicker({dateFormat: "yyyy-mm-dd'"});
                                                   // $("#end_date").datepicker({dateFormat: "yyyy-mm-dd'"});
                                                    $("#submit").attr("disabled", true);
                                                    $("#end_date").change(function () {
                                                        var startDate = document.getElementById("start_date").value;
                                                        if (startDate) {
                                                            $("#vali_value").html("");
                                                            var endDate = document.getElementById("end_date").value;
                                                            if ((Date.parse(endDate) < Date.parse(startDate))) {
                                                                $("#vali_date").html("End date should be greater than Start date");
                                                            } else {
                                                                $("#submit").attr("disabled", false);
                                                                $("#vali_date").html("");
                                                            }
                                                        } else {
                                                            $("#vali_value").html("Please Enter Start date");
                                                        }

                                                    });
                                                    $("#start_date").change(function () {
                                                        $("#vali_value").html("");
                                                        var endDate = document.getElementById("end_date").value;
                                                        if (endDate) {
                                                            if ((Date.parse(endDate) < Date.parse(startDate))) {
                                                                $("#vali_date").html("End date should be greater than Start date");
                                                            } else {
                                                                $("#submit").attr("disabled", false);
                                                                $("#vali_date").html("");
                                                            }
                                                        } else {
                                                            $("#vali_date").html("Please Enter End date");
                                                            $("#submit").attr("disabled", true);
                                                        }
                                                    });
													$('select[name="devicename"]').on('change', function () {
															var devicename = $(this).val();
															// alert(devicename);
														if (devicename != "Choose Device Name") {
															 $("#dev_count").html("");
                                                                $("#submit").attr("disabled", false);
														} else {
															 $("#dev_count").html("");
                                                                $("#submit").attr("disabled", true);
														}
													 });
                                                   /* $('.checkbox_check').on('change', function () {
                                                        if ($('.checkbox_check:checked')) {
                                                            var checkedNum = $('input[name="device_name[]"]:checked').length;
                                                            if (checkedNum > 2) {
                                                                $("#dev_count").html("Only two device can be selected");
                                                                $("input[name='device_name[]':checkbox").prop('checked', false);
                                                                $("#submit").attr("disabled", true);
                                                            } else if (checkedNum == 2) {
                                                                $("#dev_count").html("");
                                                                $("#submit").attr("disabled", false);
                                                            }
                                                        }
                                                    });*/
//                                                    $('#submit').click(function () {
//
//                                                        var checkedNum = $('input[name="device_name[]"]:checked').length;
//                                                        //  alert(checkedNum);
//                                                        if (checkedNum < 2) {
//                                                            $("#dev_count").html("Please select atleast two device");
//                                                            $("#submit").attr("disabled", true);
//                                                        } else {
//                                                            $("#dev_count").html("");
//                                                            $("#submit").attr("disabled", false);
//                                                        }
//
//                                                    });

                                                });
</script> 

<script type="text/javascript">

   /*$('.start_date').datepicker({
        orientation: "bottom",
        autoclose: true
    });
    $('.end_date').datepicker({
        orientation: "bottom",
        autoclose: true
    });*/
	var myChart;
    function getPowerCurve() {
        // console.log(PowerCurve)
        //var date_val = $('#start_date').val();
        //var enddate_val = $('#end_date').val();
       // var device_name = [];
	  /* $('select[name="devicename"]').on('change', function () {
			var devicename = $(this).val();
	   });
        $(':checkbox:checked').each(function (i) {
            device_name[i] = $(this).val();
        });*/
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;
	   // var dev = document.getElementsById("devicename").value;
	    var x = document.getElementById("devicename").selectedIndex;
        var dev = document.getElementsByTagName("option")[x].value;
       

          var devpart = dev.split("x");
        
       /* var checkedNum = $('input[name="device_name[]"]:checked').length;
      //  alert(checkedNum);
        if (checkedNum < 2) {
            $("#dev_count").html("Please select atleast two device");
            $("#submit").attr("disabled", true);
        }*/ 
		if (dev == "Choose Device Name") {
				$("#dev_count").html("Please select a device");
                $("#submit").attr("disabled", true);
		} else if (!startDate || !endDate) {
            $("#vali_value").html("Please Enter Start date and End date");
            $("#submit").attr("disabled", true);

        } else {
            $("#dev_count").html("");
            //$body = $("body");
            //$body.addClass("loading");
            var z = document.getElementById("temp0");
            z.innerHTML = "WTG Power Curve Analysis " + devpart[0] + " from  " + startDate + " to " + endDate;
            var y = document.getElementById("temp1");
            y.innerHTML = "WTG Power Curve Analysis " + devpart[0] + " from  " + startDate + " to " + endDate;
            $("#graph_area_temp").empty();
			  var myChart;         
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>ajax/ajax_power_curve_deviceone",
                dataType: 'json',
                data: {'dev': dev, 'date': startDate, 'end_date': endDate},
                success: function (data) {
					//console.log(data['trm']);
						//alert(dev);
                    if (data) {						
                        //$body.removeClass("loading");
                        var powerone = JSON.parse(data['deviceone']);
                        var capacityone = JSON.parse(data['deviceonecapacity']);
						var poweronedot = JSON.parse(data['deviceonedot']);
						var capacityonedot = JSON.parse(data['deviceonecapacitydot']);
                     // alert(data['deviceone']);
                        var windref = [];
                        var wind = [];
                        var powerref = [];
                        var power = [];
						var winddot = [];
						var powerdot = [];
						var windrefdot = [];
						var powerrefdot = [];
						var chartData = [];
						var chartDatadot = [];
						var chartDataref = [];
						var chartDatarefdot = [];
						var inc = 2;
                        for (var i in powerone) {
                            wind.push(powerone[i].Windspeed);
                            power.push(powerone[i].Power);	
							chartData.push({x:powerone[i].Windspeed,y:powerone[i].Power});
						}
                        for (var i in capacityone) {
                            windref.push(capacityone[i].Windspeed);
                            powerref.push(capacityone[i].Power);
							chartDataref.push({x:capacityone[i].Windspeed,y:capacityone[i].Power});
                        }
						for (var i in poweronedot) {
                            winddot.push(poweronedot[i].Windspeed);
                            powerdot.push(poweronedot[i].Power);
							chartDatadot.push({x:poweronedot[i].Windspeed,y:poweronedot[i].Power});							
						}
						for (var i in capacityonedot) {
                            windrefdot.push(capacityonedot[i].Windspeed);
                            powerrefdot.push(capacityonedot[i].Power);
							chartDatarefdot.push({x:capacityonedot[i].Windspeed,y:capacityonedot[i].Power});
                        }
						
						
						//for(j = 0; j < power.length; j++)
							/*for (var j = power.length; j--;) 
							{
								if ( power[j] < power[j-1] ) {
									//power.splice(j,1);
									power[j] = power[j]+power[j-1];
									//j--;							
								}
								
							}*/
                       //alert(windrefdot);
						//console.log(powerref);
                        
						//$('#mypowercanvas').remove();
						//$('#mypowercanvas1').append('<canvas id="#mypowercanvas"></canvas>');
						var ctx = $("#mypowercanvas");
                        var data = {
                       labels:['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'],
                           // labels: wind,
							//width: 320,
                            datasets: [{
                                    label: 'Power Curve',
                                    data: chartData,										
                                    backgroundColor: '#1D761A',
                                    borderColor: '#1D761A',
                                    pointBackgroundColor: '#1D761A',
                                    borderWidth: 4,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'A',
									//xAxisID: 'D',
									
                                }, {
                                    label: 'Reference Curve',
                                    data: chartDataref,
                                    backgroundColor: '#FFD700',
                                    borderColor: '#FFD700',
                                    borderWidth: 5,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									xAxisID: 'C',
									
                                }
                            ]
                        };
                        var options = {
                            scaleBeginAtZero: true,
                            responsive: true,
							maintainAspectRatio: false,
//            legend: {
//                display: false
//            },
							legend: {
								labels: {
									fontColor: 'black',
									fontSize: 15,								
									
								}
							},
							 tooltips: {
									mode: 'nearest',
									intersect: true,
							},
                            scales: {
                                /*yAxes: [{
                                        id: 'A',
                                        type: 'linear',
                                        position: 'left',
                                        ticks: {
                                            display: false,
											min: 0,
											max: 600,
											fontColor: 'green',
											fontStyle: "bold",
                                            beginAtZero: true
                                        },
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        scaleLabel: {
                                            display: false,
                                            labelString: 'Power Curve',
                                            fontStyle: "bold",
											fontColor: 'black',
											fontSize: 15,
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
											display: true,
                                            min: 0,
											fontColor: 'black',
											fontStyle: "bold",
                                            beginAtZero: true
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Power',
                                            fontStyle: "bold",
											fontColor: 'black',
											fontSize: 15,
                                        }
                                    }],
                                xAxes: [{
										id: 'C',
                                       type: 'linear',
                                        position: 'bottom',
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        ticks: {
                                            min: 0,
											max: 25,
											autoSkip: true,
											maxTickLimits: 15,
											fontColor: 'black',
											
											fontStyle: 'bold',
                                            beginAtZero: true
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Windspeed',
                                            fontStyle: "bold",
											fontColor: 'black',
											fontSize: 15,
                                        }
                                    }, {
                                        id: 'D',
                                        type: 'linear',
                                        position: 'top',
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        ticks: {
                                           min: 0,
											max: 22,
											autoSkip: true,
											maxTickLimits: 15,
                                            beginAtZero: true,
											fontColor: 'green',
											fontStyle: "bold",
											display: false,
                                        },
                                        scaleLabel: {
                                            display: false,
                                           // labelString: 'Wind',
                                            //fontStyle: "bold",
                                        }
									//	 barThickness: 15,
                                        // barPercentage: 0.8,
                                        //ticks: {
											//min: 0,
											//max: 25,
											//autoSkip: true,
											//maxTickLimits: 15,
                                            //beginAtZero: true
                                        //},
                                       // gridLines: {
                                         //   color: 'transparent',
                                           // zeroLineColor: 'transparent'
                                       // },
                                      
                                    }]*/
									 yAxes: [{
										 type: 'linear',
										 position: 'right',
                                        ticks: {
                                            display: true,
                                            min: 0,
											fontColor: 'black',
											fontStyle: "bold",
                                            beginAtZero: true
                                        },
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Power',
                                            fontStyle: "bold",
											fontSize: 16
                                        }
                                    }],
                                xAxes: [{
                                        ticks: {
                                            display: true,
                                            stepSize: 1,
                                            min: 0,
                                            autoSkip: true,
											maxTickLimits: 15,
											fontColor: 'black',
											fontStyle: "bold",
                                            //beginAtZero: true
											
                                        },
										id: 'C',
                                       type: 'linear',
                                        position: 'bottom',
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent',
											display: false,
                                        },
                                        
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
										scaleLabel: {
                                            display: true,
                                            labelString: 'Windspeed',
                                            fontStyle: "bold",
											fontSize: 16
                                        }
                                        
                                    }]
                            }

                        };
                         /*var meta = myChart && myChart.data && myChart.data.datasets[0]._meta;
         for (let i in meta) {
            if (meta[i].controller) meta[i].controller.chart.destroy();
         }*/
		if (window.myChart) window.myChart.destroy();
		 window.myChart = new Chart(ctx, {
                            type: 'line',
                            data: data,
                            options: options,
                        });
								// myChart.clear();
					
                      var ctx = $("#mypowercanvastwo");
                        var datatwo = {
                           //labels:['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'],
						   labels: windrefdot,
                            width: 320,
                            datasets: [{
                                    label: 'Power Curve',
                                    data: chartDatadot,
                                    backgroundColor: '#1D761A',
                                   borderColor: '#1D761A',
                                   pointBackgroundColor: '#1D761A',
                                   borderWidth: 2,
                                    fill: false,
									//lineTension: 0.3,
                                    radius: 2.5,
									type: 'line',
									showLine: false,
								   //borderDash: [10,5],
                                   // yAxisID: 'A',
									xAxisID: 'D',
									fontSize: 15,
									fontColor: 'black',
									fontStyle: 'bold',
                                }, {
                                    label: 'Reference Curve',
									showTooltips: false,
                                    data: chartDatarefdot,
                                    backgroundColor: '#FFD700',
                                    borderColor: '#FFD700',
                                    borderWidth: 1,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 3,
									type: 'line',
                                   // yAxisID: 'B',
									//xAxisID: 'C',
									fontSize: 15,
									fontStyle: 'bold',
									fontColor: 'black',
                                }
                            ]
                        };
                        var optionstwo = {
                            scaleBeginAtZero: true,
                            responsive: true,
							maintainAspectRatio: false,
//            legend: {
//                display: false
//            },
                            legend: {
								labels: {
									fontColor: 'black',
									fontSize: 15,								
									
								}
							},
							tooltips: {
									mode: 'nearest',
									intersect: true,
							},
							scales: {
                                /*yAxes: [{
                                        id: 'A',
                                        type: 'linear',
                                        position: 'left',
                                        ticks: {
                                            display: false,
											fontColor: 'green',
											fontStyle: 'bold',
											min: 0,
											max: 600
                                        },
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        scaleLabel: {
                                            display: false,
											fontColor: 'black',
                                            labelString: 'Power Curve',
                                            fontStyle: "bold",
											fontSize: 15,
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
                                            min: 0,
											max: 600,
											fontColor: 'black',
											fontStyle: 'bold',
                                            beginAtZero: true
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Power',
											fontColor: 'black',
											fontSize: 15,
                                            fontStyle: "bold",
                                        }
                                    }],
                                xAxes: [{
                                    id: 'C',
                                       type: 'linear',
                                        position: 'bottom',
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        ticks: {
                                            min: 0,
											max: 25,
											autoSkip: true,
											maxTickLimits: 15,
                                            beginAtZero: true,
											fontColor: 'black',
											
											fontStyle: 'bold',
											
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Windspeed',
                                            fontStyle: "bold",
											fontColor: 'black',
											fontSize: 15,
                                        }
                                    }, {
                                        id: 'D',
                                        type: 'linear',
                                        position: 'top',
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        ticks: {
                                           min: 0,
											max: 22,
											autoSkip: true,
											maxTickLimits: 15,
                                            beginAtZero: true,
											fontColor: 'green',
											fontStyle: 'bold',
											display: false,
                                        },
                                        scaleLabel: {
                                            display: false,
                                           // labelString: 'Wind',
                                            //fontStyle: "bold",
                                        }
									
                                    }]*/
									yAxes: [{
										 type: 'linear',
										 position: 'right',
                                        ticks: {
                                            display: true,
                                            min: 0,
											fontColor: 'black',
											fontStyle: "bold",
                                            beginAtZero: true
                                        },
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Power',
                                            fontStyle: "bold",
											fontSize: 16
                                        }
                                    }],
                                xAxes: [{
                                        ticks: {
                                            display: true,
                                            stepSize: 1,
                                            min: 0,
                                            autoSkip: true,
											maxTickLimits: 15,
											fontColor: 'black',
											fontStyle: "bold",
                                            //beginAtZero: true
											
                                        },
                                         id: 'D',
                                        type: 'linear',
                                        position: 'bottom',
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent',
											display:false
                                        },
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
										scaleLabel: {
                                            display: true,
                                            labelString: 'Windspeed',
                                            fontStyle: "bold",
											fontSize: 16
                                        }
                                        
                                    }]
                            }

                        };
                if (window.myChart1) window.myChart1.destroy();
		 window.myChart1 = new Chart(ctx, {
                            type: 'line',
                            data: datatwo,
                            options: optionstwo,
                        });
                        // console.log(power);

                    } else if (data.session == 'expired') {
                        alert('session expired');
                        windows.reload();
                    } else {
                        alert(data.invalid);
                    }
                }
            });
        }
    }










</script>



