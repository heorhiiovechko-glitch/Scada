<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
// echo "<pre>"; print_r($tempAna); exit;
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>-->


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
    .container {

        min-height:100px !important;
    }

</style>


<main class="main">
    <!-- Breadcrumb-->
    <!--<ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Temperature</li>
    </ol>-->
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="font-size:18px;">Location Temperature Analysis</div>

                       <!-- <div class="alert alert-info col-md-12" style="align: center;">
                            <strong>Info! Please select only two devices.</strong>
                        </div>-->

                        <div class="card-body">
                           <span value="" id="try"  style="color:red"></span>
                            <div class="row">
                                <div class="picker date col-md-3" id="date" >
                                    <label style="font-size:18px;"><h8>Select Date:</h8></label>&nbsp;&nbsp;&nbsp;
                                    <input class="form-control start_date" type="date" placeholder="Start Date" id="start_date">
                                </div>

                                <div class="picker device col-md-3" >
                                    <label style="font-size:18px;"><h8>Device List:</h8></label>&nbsp;&nbsp;&nbsp;
                                    <select name="devicename" id="devicename" >
                                        <option selected="selected" style="font-size:18px;">Choose Device Name</option>
                                        <?php foreach ($tempAna['deviceList'] as $key => $value) { ?>
                                            <option style="font-size:18px;" value="<?= $value['Format_Type'] . "x" . $value['Device_Name'] . "x" . $value['IMEI']; ?>"><?= $value['Device_Name']; ?></option>
                                        <?php }
                                        ?>
                                    </select> 
                                </div>
                              <!--  <div class="picker temp col-md-3" id="device" >
                                    <label style="font-size:18px;"><strong>Temperature List:</strong></label>&nbsp;&nbsp;&nbsp;
                                    <select name="temperature" id="temperature">
                                                                              <option selected="selected">Choose Temperature</option>
                                        
                                                                                <option value="Gear"> Gear </option>
                                                                                <option value="Bearing"> Bearing </option>
                                                                                <option value="Gen1"> Gen1 </option>
                                                                                <option value="Gen2"> Gen2 </option>
                                                                                <option value="Hydraulic"> Hydraulic </option>
                                                                                <option value="Control"> Control </option>
                                                                                <option value="Ambiant"> Ambiant </option>
                                                                                <option value="Nacel"> Nacel </option>


                                    </select> 

                                </div>-->

									<div class="col-md-3">
                                    <br/> &nbsp;&nbsp;&nbsp;
                                    <input type="button" class="btn btn-primary" onclick="getPerfCurve();" id="submit" value="Submit"  style="margin-left:50px;margin-right:50px;font-size:18px;"/>
                                </div>
                               
                            </div>  <br/>  
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header airforce-blue" id="temp0" style="font-size:18px;">Temperature Analysis</div>
                                            <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
                                           <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/chart.js/dist/Chart.min.js"></script>
                                            <div class="card-body">
                                               <!-- <div id='loadingmessage' style='display:none'>
                                                    <img class="center" src="<?php echo base_url(); ?>assets/images/box/giphy.gif">
                                                </div>-->
                                                <canvas id="mytempcanvas" style="height: 380px;"></canvas>
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
                                      
										/*$(document).ready(function () {
											 $("#submit").attr("disabled", true);
                                                    $("#start_date").change(function () {
                                                        var startDate = document.getElementById("start_date").value;
                                                        if (startDate) {
                                                            
                                                            $("#submit").attr("disabled", false);
                                                             $("#try").html("");
                                                            
                                                        } else {
                                                            $("#try").html("Please Enter Start date");
                                                        }

                                                    });
                                            $('select[name="devicename"]').on('change', function () {
															var devicename = $(this).val();
															// alert(devicename);
														if (devicename != "Choose Device Name") {
															 $("#try").html("");
                                                                $("#submit").attr("disabled", false);
														} else {
															 $("#try").html("");
                                                                $("#submit").attr("disabled", true);
														}
													 });
                                                   });*/
</script>

<script type="text/javascript">

    function getPerfCurve() {

        var startDate = document.getElementById("start_date").value;
       // var temp = document.getElementById("temperature").value;
       
		var x = document.getElementById("devicename").selectedIndex;
        var dev = document.getElementsByTagName("option")[x].value;
          var devpart = dev.split("x");
        //   var data_parts = dev.split("x");
        if (startDate && dev != "Choose Device Name") {
            $("#try").html("");
			 //alert(dev);
			 var z = document.getElementById("temp0");
            z.innerHTML = "Temperature Analysis " + devpart[1] + " " + startDate;
            
          $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>ajax/ajax_tempgraph",
                dataType: 'json',
                data: {'start_Date': startDate, 'dev': dev},
                success: function (data) { 
					//alert(dev);
                    var tempval = JSON.parse(data['temp_graph']);
					//console.log(data['FType']);
                    var amptemp = [];
					var naceltemp = [];
					var geartemp = [];
					var gen1temp = [];
					var gen2temp = [];
					var controltemp = [];
					var beartemp = [];
					var hydrtemp = [];
					var thyrtemp = [];
					var mainpaneltemp = [];
                    var time = [];
					var FType = data['FType'];
                  
					for (var i in tempval) {
						if(FType==1 || FType==6) {
                             amptemp.push(tempval[i].Ambient_Temp);
							 naceltemp.push(tempval[i].Nacel_Temp);
							 geartemp.push(tempval[i].Gear_Temp);
							 gen1temp.push(tempval[i].Gen1_Temp);
							 controltemp.push(tempval[i].Control_Temp);
							 beartemp.push(tempval[i].Bearing_Temp);
							 hydrtemp.push(tempval[i].Hydraulic_Temp);
							time.push(tempval[i].Time_S);
                        } else {
					         amptemp.push(tempval[i].Ambient_Temp);
							 naceltemp.push(tempval[i].Nacel_Temp);
							 geartemp.push(tempval[i].Gear_Temp);
							 gen1temp.push(tempval[i].Gen1_Temp);
							 gen2temp.push(tempval[i].Gen2_Temp);
							 beartemp.push(tempval[i].Bearing_Temp);
							 thyrtemp.push(tempval[i].Thyristor_Temp);
							 mainpaneltemp.push(tempval[i].Main_Panel_Temp);
							time.push(tempval[i].Time_S);
                        }
				  } 
						

                    //console.log(time);
					//alert(dev);
					//var myChart=null;
					//myChart.destroy();
                    var ctx = $("#mytempcanvas");
                   if(FType==1 || FType==6) {
					   var data = {
                            labels: time,
							//width: 320,
							datasets: [{
                                    label: 'Ambient(45)',
                                    data: amptemp,										
                                    backgroundColor: '#1D761A',
                                    borderColor: '#1D761A',
                                    pointBackgroundColor: '#1D761A',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'A',
									//xAxisID: 'D',
									
                                }, {
                                    label: 'Nacel(50)',
                                    data: naceltemp,
                                    backgroundColor: '#FFD700',
                                    borderColor: '#FFD700',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                }, {
                                    label: 'Gear(86)',
                                    data: geartemp,
                                    backgroundColor: '#ff4d4d',
                                    borderColor: '#ff4d4d',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                },{
                                    label: 'Gen1(150)',
                                    data: gen1temp,
                                    backgroundColor: '#1f1f60',
                                    borderColor: '#1f1f60',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                },{
                                    label: 'Bearing(95)',
                                    data: beartemp,
                                    backgroundColor: '#995c00',
                                    borderColor: '#995c00',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                },{
                                    label: 'Hydraulic(66)',
                                    data: hydrtemp,
                                    backgroundColor: '#1affff',
                                    borderColor: '#1affff',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
								},{
                                    label: 'Control(50)',
                                    data: controltemp,
                                    backgroundColor: '#ff99c2',
                                    borderColor: '#ff99c2',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
								}
                            ]
					   };
                       
					} else {
						var data = {
                            labels: time,
							//width: 320,
						 datasets: [{
                                    label: 'Ambient',
                                    data: amptemp,										
                                    backgroundColor: '#1D761A',
                                    borderColor: '#1D761A',
                                    pointBackgroundColor: '#1D761A',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'A',
									//xAxisID: 'D',
									
                                }, {
                                    label: 'Nacel',
                                    data: naceltemp,
                                    backgroundColor: '#FFD700',
                                    borderColor: '#FFD700',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                }, {
                                    label: 'Gear',
                                    data: geartemp,
                                    backgroundColor: '#ff4d4d',
                                    borderColor: '#ff4d4d',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                },{
                                    label: 'Gen1',
                                    data: gen1temp,
                                    backgroundColor: '#1f1f60',
                                    borderColor: '#1f1f60',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                },{
                                    label: 'Gen2',
                                    data: gen2temp,
                                    backgroundColor: '#e68a00',
                                    borderColor: '#e68a00',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
									
                                },{
                                    label: 'Bearing',
                                    data: beartemp,
                                    backgroundColor: '#995c00',
                                    borderColor: '#995c00',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
								},{
                                    label: 'Thyristor',
                                    data: thyrtemp,
                                    backgroundColor: '#ff99c2',
                                    borderColor: '#ff99c2',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
								},{
                                    label: 'Main Panel',
                                    data: mainpaneltemp,
                                    backgroundColor: '#1affff',
                                    borderColor: '#1affff',
                                    borderWidth: 2,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',
                                    //yAxisID: 'B',
									//xAxisID: 'C',
								}
                            ]
                        };
					}
		  
						 var options = {
                            scaleBeginAtZero: true,
                            responsive: true,
							maintainAspectRatio: false,
							legend: {
								labels: {
									fontColor: 'black',
									fontSize: 15,								
									
								}
							},
                            scales: {
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
                                            labelString: 'Temperature',
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
                                        
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
										scaleLabel: {
                                            display: true,
                                            labelString: 'Time',
                                            fontStyle: "bold",
											fontSize: 16
                                        }
                                        
                                    }]
									}

                        };
                        if (window.myChart) window.myChart.destroy();
		 window.myChart = new Chart(ctx, {
                            type: 'line',
                            data: data,
                            options: options,
                        });
						
						
                }
            });

        } else {
            
            if (startDate == "") {
                $("#try").html("Please Select a Date");
            } else if (dev == "Choose Device Name") {
                $("#try").html("Please Select a device");
            } 
        }
    }
</script>
























