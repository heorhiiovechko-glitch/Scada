<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
// echo "<pre>"; print_r($tempAna); exit;
$Fin_Year_Array = array(
			
			'2019-2020' => '2019 - 2020',
			'2020-2021' => '2020 - 2021',
			'2021-2022' => '2021 - 2022'
					);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>


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
   <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Performance Curve</li>
    </ol>-->
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="font-size:18px;">Location Performance Curve</div>
                        <div class="card-body">
                   


                            <div class="row">
                                <div class="col-md-12">
                                                                       <label style="font-size:18px;"><h5>Filter:</h5></label>
                                    <div class="col-md-6">
                                        <div style="text-align: center;">
                                            <span value="" id="try"  style="color:red;font-size:18px;"></span>

                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="button dropdown"> 
                                                <select id="pickerselector">
                                                    <option style="font-size:18px;" value="">--Select--</option>
                                                    <option style="font-size:18px;" value="date">Date</option>
                                                    <option style="font-size:18px;" value="month">Month</option>
                                                    <option style="font-size:18px;" value="year">Year</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="picker date col-md-6" id="date" >
                                            <div class="input-group mb-3">
                                                <label style="color:white;font-size:18px;"><h8>Date </h8></label>&nbsp;&nbsp;
                                                <input class="form-control start_date" style="font-size:18px;" type="text" placeholder="Date" id="start_date">
                                            </div>
                                        </div>
                                        <div class="picker month col-md-6" id="month">
                                            <div class="input-group mb-3">
                                                <!--                                                <label><h8>Month Filter </h8></label>&nbsp;&nbsp;-->
                                                <input class="date-own form-control" id="datepicker" style="width: 300px;font-size:18px;" type="text" placeholder="Month">
                                                <input type="hidden" id="monthField" value="" />
                                            </div>

                                        </div>
                                        <div class="picker year col-md-6" id="year">
                                            <div class="input-group mb-3">

                                                <input class="year-own form-control" id="yearpicker" style="width: 300px;font-size:18px;" type="text"  placeholder="Financial Year" >
                                                <input type="hidden" id="myField" value="" />
                                            </div>

                                        </div>
										<div class="text-center">
                                            <input type="button" class="btn btn-primary" onclick="getPerfCurve();" id="submit" value="Submit"  style="margin-left:50px;font-size:18px;"/>
                                        </div>

                                        <!-- <div class="col-md-6">
                                            <div class="input-group mb-4">
                                                <input class="form-control end_date" type="text" placeholder="End Date" id="end_date">
                                            </div>
                                        </div> -->
                                    </div><br>
                                   
                                </div><br><br>
                                
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header airforce-blue" id="temp0" style="font-size:18px;">WTG Performance Analysis</div>
                                                <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
                                                <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/chart.js/dist/Chart.min.js"></script>
                                                <div class="card-body">
                                                    <canvas id="myperfcanvas" style="display: inline-block; width: 650px; height: 300px; vertical-align: top;" width="800" height="300"></canvas>
                                                </div>
												<div>
												<!--<div  id="highval" style="font-size:18px;align:center;">Highest GAD</div>-->
                                            </div>
                                        </div>
                                        <!--                                        <div class="col-md-6">
                                                                                    <div class="card">
                                                                                        <div class="card-header airforce-blue" id="temp1">WTG Power Curve Analysis</div>
                                                                                        <div class="card-body">
                                                                                            <canvas id="myperfcanvastwo" style="height: 400px;"></canvas>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>-->
                                    </div>
                                    <!-- <div class="card">
                                        <div class="card-header" id="temp"></div>
                                        <div class="card-body">
                                            <div id="power-curve" style="height: 400px;"></div>
                                        </div>
                                    </div> -->
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

    var globalVal;

    document.getElementById("monthField").value = "";
    document.getElementById("myField").value = "";
	$("#start_date").attr("autocomplete", "off");
        

    $('.start_date').datepicker({
        format: 'dd-mm-yyyy',		
		autoclose: true
    });


    $('.date-own').datepicker({
        minViewMode: 1,
        format: 'yyyy-mm',
		autoclose: true
    });




    $("#datepicker").change(function () {
        $("#submit").attr("disabled", false);
         $("#try").html("");
        var date = $(this).datepicker("getDate");
        var month1 = $(this).datepicker('getDate').getMonth() + 1;
        if (month1 < 10) {
            var month1 = "0" + month1;
        }
        var year1 = $("#datepicker").datepicker('getDate').getFullYear();
        var date_val = year1 + "-" + month1;
        document.getElementById('monthField').value = date_val;
    });

    $('.year-own').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
		autoclose: true
    });


    $("#start_date").change(function () {
        $("#submit").attr("disabled", false);
        $("#try").html("");
    });

    $("#yearpicker").change(function () {
        $("#submit").attr("disabled", false);
        $("#try").html("");
        var yearpick = $("#yearpicker").datepicker('getDate').getFullYear();
        document.getElementById('myField').value = yearpick;

    });



    $('.picker').hide();
    $(function () {
        $('#pickerselector').change(function () {
            $("#submit").attr("disabled", false);
             $("#try").html("");
            $("#try").html("");
            $('.picker').hide();
            $('#' + $(this).val()).show();
        });
    });


    function getPerfCurve() {
        // console.log(PowerCurve)

        var myChart;
        var picker = document.getElementById("pickerselector");
        var selector = picker.options[picker.selectedIndex].value;
        var date_val = "";
		var date_val_next = "";

        if (selector) {
            $("#submit").attr("disabled", false);
        }

        if (selector == "date") {
            var date_val = $('#start_date').val();
        } else if (selector == "month") {
            var date_val = document.getElementById("monthField").value;
        } else if (selector == "year") {
            var date_val = document.getElementById("myField").value;
		}

        
        var startDate = document.getElementById("start_date").value;

        if (selector == "") {
            $("#try").html("Please Select a Filter");
            $("#submit").attr("disabled", true);

        } else if (date_val == "") {
            $("#try").html("Please Select a Value for filter");
            $("#submit").attr("disabled", true);

        } else {            
            $("#try").html("");
            $body = $("body");
            $body.addClass("loading");
            var x = document.getElementById("temp0");
			if (selector == "year") {
				var date_val_next = date_val++;        
				x.innerHTML = "WTG Perfomance Curve Analysis from April " + date_val_next + " to March " + date_val;
			} else {
				x.innerHTML = "WTG Perfomance Curve Analysis " + date_val;
			}
	
            $("#graph_area_temp").empty();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>ajax/ajax_perfomance_curve",
                dataType: 'json',
                data: {'date': date_val, 'selector': selector},
                success: function (data) {

                    if (data) {
                        $body.removeClass("loading");
                        // console.log(data['deviceone']);
//                    console.log(data['device_name']);
//                    console.log(data);
//                       alert(data);

                        var perf_gad = JSON.parse(data['perf_gad']);
                       
                        var gad = [];
                        var dev = [];
                    
                        for (var i in perf_gad) {
                             dev.push(perf_gad[i].device_name);
                             gad.push(perf_gad[i].gad);
                        }

                     
                        if (window.myChart != undefined) {
                            window.myChart.destroy();
                        }

                        //console.log(perf_gad);
                     // var gadmax=argmax(gad);
					  /*var max = perf_gad.reduce(function (prev, current) {
							return (prev.gad > current.gad) ? prev.gad : current.gad
						});*/
						var max = Math.max.apply(Math, perf_gad.map(function(o) { return o.gad; }))
						var maxdev = Math.max.apply(Math, perf_gad.map(function(o) { return o.device_name; }))
                        var ctx = $("#myperfcanvas");
                        window.myChart = new Chart(ctx, {
                            type: 'bar',
                            title: {
                                text: 'Export GAD'
                                        // subtext: 'Graph Sub-text'
                            },
                            data: {
                                labels: dev,
                                datasets: [{
                                        label: 'Generated Units',
                                        data: gad,
                                        backgroundColor: '#7FFF00',
                                        borderColor: '#7FFF00',
                                        borderWidth: 1,
                                    }]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    display: true
                                },
                                scales: {
                                    yAxes: [{
                                            ticks: {
                                                display: true,
                                                beginAtZero: true
                                            },
                                            gridLines: {
                                                color: 'transparent',
                                                zeroLineColor: 'transparent'
                                            },
                                            scaleLabel: {
                                                display: true,
                                                labelString: 'GAD (KWH)',
                                                fontStyle: "bold",
                                            }
                                        }],
                                    xAxes: [{
                                            barThickness: 15,
                                            ticks: {
                                                display: true,
                                                stepSize: 1,
                                                min: 0,
                                                autoSkip: false
                                            },
                                            gridLines: {
                                                color: 'transparent',
                                                zeroLineColor: 'transparent'
                                            },
                                        }]
                                }
                            },
                            plugins: [{
                                    beforeDraw: function (chartInstance, easing) {
                                        var ctx = chartInstance.chart.ctx;
                                        ctx.fillStyle = '#808080'; // your color here

                                        var chartArea = chartInstance.chartArea;
                                        ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
                                    }
                                }]
                        });


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



