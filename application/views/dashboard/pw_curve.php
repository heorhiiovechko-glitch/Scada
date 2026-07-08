<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo "<pre>"; print_r($tempAna); exit;
		//$data = array();
		//$pwgraphvalues = array();
       // $config = array();
		$From_Epoch = strtotime($_REQUEST['s_date']);
		$To_Epoch = strtotime($_REQUEST['e_date']);
		$From= date("Y-m-d",$From_Epoch);
		$To= date("Y-m-d",$To_Epoch);
		//echo $From_YMD;
		$dname = $_REQUEST['d_name'];
		$basics = $this->Common_model->getbasicInfoimei($dname);
		//print_r($imei);
		//print_r($basic[0]['IMEI']);
		//print_r($basic[0]);
		$name = $basics[0]['Device_Name'];
		$loc = $basics[0]['Site_Location'];
		$feeder = $basics[0]['Connect_Feeder'];
		//print_r($pwgraphvalues);
       
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/chart.js/dist/Chart.min.js"></script>
<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/jsPDF/dist/jspdf.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>               
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>-->
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>}

<style>
.airforce-blue {
        color: #fff;
        background-color: #517fa4;
    }
</style>
<script type='text/javascript'>
    function getpdf(){
		var HTML_Width = $(".htmlpdf").width();
    var HTML_Height = $(".htmlpdf").height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas($(".htmlpdf")[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save("Your_PDF_Name.pdf");
        //$(".container-fluid").hide();
    });
	}
</script>
<main class="main">
<div class="container-fluid">
        <div class="animated fadeIn">
        
				             <div class="col-md-10">
                                            <input type='button'  value='Export PDF' onclick='getpdf();'>
                                       
                                    </div>   <br/>  
<div class ="htmlpdf">				
<div class="card-header airforce-blue" style="font-size:18px;"><?php echo $name ." - " . $From ?>&nbsp;&nbsp;&nbsp;&nbsp;Feeder <?php echo " - " . $feeder ?>&nbsp;&nbsp;&nbsp;&nbsp;Location <?php echo " - " . $loc ?></div>					
                        <div class="card-body" >
                            <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
											<div class="card-header airforce-blue" id="temp0" style="font-size:18px;">Windspeed Graph </div>
											                              
                                                <div class="card-body">
                                                    <canvas id="mypowercanvas" style="width:550px;height: 350px;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header airforce-blue" id="temp1" style="font-size:18px;">Generation Graph </div>
                                                <div class="card-body">
                                                    <canvas id="mypowercanvastwo" style="width:550px;height:350px;"></canvas>												
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                  
                                </div>
								 <div class="col-md-7">
                                    <!--<div class="row"> -->                                      
                                            <div class="card">
											<div class="card-header airforce-blue" id="temp2" style="font-size:18px;">Power Windspeed Graph </div>
                                                <div class="card-body">
                                                    <canvas id="mypowercanvasthree" style="width:650px;height: 350px;"></canvas>
                                                </div>
                                            </div>                                       
										</div>
										</div>
										</div>
                               <!-- </div> --> 
</main>		
<?php $this->load->view('layout/footer'); ?>						
                   
<script type="text/javascript">
	window.onload = function () {		
	if (window.myChart) window.myChart.destroy();
	if (window.myChart1) window.myChart1.destroy();
	if (window.myChart2) window.myChart2.destroy();
	var dev_name = <?php echo json_decode($dev_name); ?>;	
	var sdate = <?php echo json_decode($sdate); ?>;			
	var pwgraph = <?php echo json_decode($pwgraphval); ?>;
	var pwhourgraph = <?php echo json_decode($pwhourgraphval); ?>;
	//alert (dev_name);
	var on = " dated on ";
	var wind = [];
	var power = [];
	var time =[];
	var gad = [];
	var hour = [];
	var windhour = [];
	var powerhour = [];
	
    for (var i in pwgraph) {
        				 time.push(pwgraph[i].Hour);
						 wind.push(pwgraph[i].WindSpeed);
						 power.push(pwgraph[i].Power);
						 gad.push(pwgraph[i].GAD);													 
				  } 
	for (var j in pwhourgraph) {
        				 windhour.push(pwhourgraph[j].Windspeed);
						 powerhour.push(pwhourgraph[j].Power);
						 }
					//alert(wind);
                    var ctx = $("#mypowercanvas");
                 		var data = {
                      // labels:['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'],
					  labels: time,
                            datasets: [{
                                    label: 'Windspeed',
                                    data: wind,										
                                    backgroundColor: '#1D761A',
                                    borderColor: '#1D761A',
                                    pointBackgroundColor: '#1D761A',
                                    borderWidth: 4,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',                                    
                                }
                            ]
                        };
                        var options = {
                            scaleBeginAtZero: true,
                            responsive: true,
							maintainAspectRatio: false,
							legend: {
								display: false,
								labels: {
									fontColor: 'black',
									fontSize: 15,
								}
							},
						    scales: {
                               	 yAxes: [{
										 type: 'linear',
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
                                            labelString: 'Windspeed (m/s)',
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
                                            zeroLineColor: 'transparent',
											display: false,
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
						//if (window.myChart) window.myChart.destroy();
		 window.myChart = new Chart(ctx, {
                            type: 'line',
                            data: data,
                            options: options,
                        });
		var ctx = $("#mypowercanvastwo");
                 		var datatwo = {
                       //labels:['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'],
						labels: time,
                            datasets: [{
                                    label: 'Generation',
                                    data: gad,										
                                    backgroundColor: '#1D761A',
                                    borderColor: '#1D761A',
                                    pointBackgroundColor: '#1D761A',
                                    borderWidth: 4,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'bar',                                    
                                }
                            ]
                        };
                        var optionstwo = {
                            scaleBeginAtZero: true,
                            responsive: true,
							maintainAspectRatio: false,
							legend: {
								display: false,
								labels: {
									fontColor: 'black',
									fontSize: 15,
									
								}
							},
						    scales: {
                               	 yAxes: [{
										 type: 'linear',
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
                                            labelString: 'Generation (kWh)',
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
                                            zeroLineColor: 'transparent',
											display: false,
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
						
		 window.myChart1 = new Chart(ctx, {
                            type: 'bar',
                            data: datatwo,
                            options: optionstwo,
                        });
	
			var ctx = $("#mypowercanvasthree");
                 		var datathree = {
                       //labels:['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'],
						labels: windhour,
                            datasets: [{
                                    label: 'Power VS Windspeed',
                                    data: powerhour,										
                                    backgroundColor: '#1D761A',
                                    borderColor: '#1D761A',
                                    pointBackgroundColor: '#1D761A',
                                    borderWidth: 4,
                                    fill: false,
                                    lineTension: 0.5,
                                    radius: 1,
                                    type: 'line',                                    
                                }
                            ]
                        };
                        var optionsthree = {
                            scaleBeginAtZero: true,
                            responsive: true,
							maintainAspectRatio: false,
							legend: {
								display: false,
								labels: {
									fontColor: 'black',
									fontSize: 15,
									
								}
							},
						    scales: {
                               	 yAxes: [{
										 type: 'linear',
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
                                            labelString: 'Power (kW)',
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
                                            zeroLineColor: 'transparent',
											display: false,
                                        },
                                        
                                        gridLines: {
                                            color: 'transparent',
                                            zeroLineColor: 'transparent'
                                        },
										scaleLabel: {
                                            display: true,
                                            labelString: 'Windspeed (m/s)',
                                            fontStyle: "bold",
											fontSize: 16
                                        }
                                        
                                    }]
                            }

                        };
						
		 window.myChart2 = new Chart(ctx, {
                            type: 'line',
                            data: datathree,
                            options: optionsthree,
                        });
	
	};
</script>