<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo "<pre>"; print_r($tempAna); exit;
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
.searchable-container.btn.active span.glyphicon {
    opacity: 1;
}

.searchable-container .bizcontent input[type="checkbox"] {
    position: absolute;
    clip: rect(0,0,0,0);
    pointer-events: none;
}
.temp-btn-margin {
    margin-right: 20px;
}
</style>
<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Temperature</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Location Temperature Analysis</div>
                        <div class="card-body">
                            <div class="row">
							
							        
							        <div class="input-group "  style="width:20%;float:left;background-color:#2f353ae3;margin-top:-5px;height:65px;padding:17px;">
									    <label><font color="white">Enter Date:</font></label>&nbsp;&nbsp;&nbsp;
                                        <input class="form-control start_date" type="date" placeholder="Start Date" id="start_date">
                                    </div>
									<tr>
                                <div style="width:80%;padding-left:10px;background-color:#2f353ae3;margin-top:-5px;height:65px;">
                                    <div style="padding:20px;">
                                        <td><input type="button" class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Gear_Temp','Gear');" value="Gear" style="font-weight:900;" /></td>
                                        <td><input type="button" class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Bearing_Temp','Bearing');" value="Bearing" style="font-weight:900;" /></td>
                                        <td><input type="button" class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Gen1_Temp','Gen1');" value="Gen1" style="font-weight:900;" />
									   <td><input type="button"  class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Gen2_Temp','Gen2');" value="Gen2" style="font-weight:900;" />
                                        <td><input type="button" class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Hydaulic_Temp','Hydraulic');" value="Hydraulic" style="font-weight:900;" />
                                        <td><input type="button" class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Control_Temp','Control');" value="Control" style="font-weight:900;" />
										<td><input type="button" class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Ambiant_Temp','Ambiant');" value="Ambiant" style="font-weight:900;" />
										<td><input type="button" class="btn btn-default temp-btn-margin" onclick="getTempAnalysis('Nacel_Temp','Nacel');" value="Nacel" style="font-weight:900;" />
									
                                    </div>
									
                                </div>
							 	
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    
                                    <div class="form-group">
                                        <label style="font-weight:900;">Select Device List:</label>
                                        <!-- <select multiple class="form-control" name="device_name" placeholder="Choose anything" data-allow-clear="1"> -->
                                           <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">All Type>></button> 
										   
										   <div id="demo" class="collapse"> 
											
												<div class="row">
												<?php 
												foreach ($tempAna['deviceList'] as $key => $value) {
												?>
												<!-- <option value="<?php echo $value['Device_Name '];?>">
													<?php echo $value['Device_Name'];?>
												</option> -->
												<div class="searchable-container items " style="width:14%;">
													<div class="info-block block-info clearfix">
														<div class="square-box pull-left">
															<span class="glyphicon glyphicon-tags glyphicon-lg"></span>
														</div>
														<div data-toggle="buttons" class="btn-group bizmoduleselect">
															<label class="check btn btn-default">
																<div class="bizcontent">
																	<input type="checkbox" id="input_<?php echo $key;?>" name="device_name[]" autocomplete="off" value="<?php echo $value['Device_Name'];?>">
																	<span class="glyphicon glyphicon-ok glyphicon-lg"></span>
																	<div>
																		<?php echo $value['Device_Name'];?>
																	</div>
																</div>
															</label>
														</div>
													</div>
												</div>
												<?php }?>
											<!-- </select> -->
											</div>
										</div>
                                    </div>
									
                                </div>
                                <div class="col-md-12">
								
                                    <div class="card">
                                        <div class="card-header" id="temp">Temperature</div>
                                        <div class="card-body">
                                            <div id="power-curve1" style="height: 400px;"></div>
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
<?php  $this->load->view('layout/footer'); ?>
<script type="text/javascript">
$data['export_temp'] = json_encode($export_temp);
$('.start_date').datepicker({
    orientation: "bottom",
    autoclose: true
});

function getTempAnalysis(TempName, title) {
    console.log(TempName)
    var date_val = $('#start_date').val();

    if (date_val == '') {
        alert('Please select date');
        return false;
    }

    // $.each($("select[name='device_name:selected']"), function() {
        // device_name.push($(this).val());
    // });

     //$.each($("select[name='device_name']").select2('data'), function(key, value) {
        // device_name.push(value.id);
     //});
    console.log(device_name);
    var device_name = [];
        $(':checkbox:checked').each(function(i){
          device_name[i] = $(this).val();
        });
        console.log(device_name);

    if (device_name == '') {
        alert('Please select device');
        return false;
    }
	
	if (TempName == 'device_name') {
	    alert('No Temprature');
        return false;
    }
	
    $body = $("body");
    $body.addClass("loading");
    $("#graph_area_temp").empty();
    $("#temp").html(title);
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url(); ?>ajax/ajax_temp_analysis",
        dataType: 'json',
        data: {'device_name': device_name, 'date': date_val, 'temp_name': TempName },
        success: function(data) {

            if (data) {
                $body.removeClass("loading");
                console.log(data);
                // if ($('#graph_area_temp').length) {
                // Morris.Area({
                //     element: 'graph_area_temp',
                //     data: data.valid,
                //     xkey: 'hours',
                //     ykeys: ['green', 'red', 'blue', 'gray'],
                //     lineColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
                //     labels: ['Green', 'Red', 'Blue', 'Gray'],
                //     pointSize: 2,
                //     hideHover: 'auto',
                //     parseTime: false,
                //     resize: true
                // });

                const dataSource = {
                    "chart": {
                        "caption": "",
                        "yaxisname": "Temperature",
                        "xaxisname": date_val,
                        "yAxisMaxValue": "100",
                        "yAxisMinValue": "0",
						"paletteColors": "#1aaf5d,#ff0000,#0000ff,#ee82ee,#ffa500,#000000,#FF00CC,#000033",
				        "subcaption": "",
                        "showhovereffect": "",
                        "numbersuffix": "",
                        "drawcrossline": "1",
						"legendIconScale": "1",
						"outCnvBaseFont":"Verdana",
						"outCnvBaseFontSize": "14",
						//"outCnvBaseFontColor": "#633563",
						//"baseFontSize": "12",
                        // "plottooltext": "<b>$dataValue</b> on $seriesName",
                        "theme": "fusion"
                    },
                    "categories": [{
                        "category": [{
                                "label": "1"
                            },
                            {
                                "label": "2"
                            },
                            {
                                "label": "3"
                            },
                            {
                                "label": "4"
                            },
                            {
                                "label": "5"
                            },
                            {
                                "label": "6"
                            },
                            {
                                "label": "7"
                            },
                            {
                                "label": "8"
                            },
                            {
                                "label": "9"
                            },
                            {
                                "label": "10"
                            },
                            {
                                "label": "11"
                            },
                            {
                                "label": "12"
                            },
                            {
                                "label": "13"
                            },
                            {
                                "label": "14"
                            },
                            {
                                "label": "15"
                            },
                            {
                                "label": "16"
                            },
                            {
                                "label": "17"
                            },
                            {
                                "label": "18"
                            },
                            {
                                "label": "19"
                            },
                            {
                                "label": "20"
                            },
                            {
                                "label": "21"
                            },
                            {
                                "label": "22"
                            },
                            {
                                "label": "23"
                            },
                            {
                                "label": "24"
                            }
                        ]
                    }],
                    "dataset": data.dataValue
                };

                FusionCharts.ready(function() {
                    var myChart = new FusionCharts({
                        type: "msline",
                        renderAt: "power-curve1",
					    width: "100%",
                        height: "100%",
                        dataFormat: "json",
                        dataSource
                    }).render();
                });
                // }
            } else if (data.session == 'expired') {
                alert('session expired');
                windows.reload();
            } else {
                alert(data.invalid);
            }
	

        }
    });

}

$(function() {
    $('#search').on('keyup', function() {
        var pattern = $(this).val();
        $('.searchable-container .items').hide();
        $('.searchable-container .items').filter(function() {
            return $(this).text().match(new RegExp(pattern, 'i'));
        }).show();
    });


});
</script>