<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
// echo "<pre>"; print_r($tempAna); exit;
 $feeders = $this->Common_model->get_feeder_list();
 $Fin_Year_Array = array(
			
			'2019-2020' => '2019 - 2020',
			'2020-2021' => '2020 - 2021',
			'2021-2022' => '2021 - 2022'
					);
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
    
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
					<table style="overflow-x:scroll;max-width:1350px;">
					<tr><td>
						<form role="form" method="post">
                                         <table class="table " width="100%" border='0' cellpadding="1" cellspacing="1">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">Reports</th>
														<th class="dev text-center">Device List</th>
														<th class="feed text-center">Feeder List</th>
                                                        <th class="picker text-center">Start Date</th>
                                                        <th class="picker text-center">End Date</th>
														<th class="fin text-center">Year</th>
														<th class="text-center">Action</th>
														
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                        <select name="preport" id="preport" style="width:180Px; padding-left:5px">
															<option value="1">Power Vs Wind Speed</option>
															<option value="2" >Overview Report</option>
															<option value="3" >Temperature Report</option>
															<option value="4" >Production Report</option>
															<option value="5" >Grid Report</option> 
															<option value="6" >Stop Hours Group Report</option> 
															<option value="7" >Alarm Log</option>
															<option value="8">Alarm Log Group</option>
															<option value="9">DGR Individual Report</option>								
															<option value="10" >DGR Grouping Report</option>							
															<!--<option value="11" >Daily EB Slot Reading</option> 	-->							
															<option value="12">Financial Year Report</option> 	
															<option value="13">Power and Windspeed curves</option> 
														</select>
                                                        </td>
														<td class="dev text-center">
															<select name="devicename" id="devicename" >
															<!--<option  style="font-size:18px;">Choose Device Name</option>-->
																<?php 
																foreach ($reports['deviceList'] as $key => $value) {
																?>
																<option style="font-size:18px;" value="<?= $value['IMEI']; ?>"><?= $value['Device_Name']; ?></option>													 
															<?php }?>
															</select>
														</td>
														<td class="feed text-center">
															<select name="feedername" id="feedername" >
																<?php 
																foreach ($feeders as $key => $value) {
																?>
																<option style="font-size:18px;" value="<?= $value['Connect_Feeder']; ?>"><?= $value['Connect_Feeder']; ?></option>													 
															<?php }?>
															</select>
														</td>
                                                        <td class="picker date">
															<input class="form-control start_date" type="text" placeholder="Start Date" id="start_date">
															<input type="hidden" id="s_date" name="s_date" value="" />
                                                            <input type="hidden" id="d_name" name="d_name" value="" />
                                                        </td>
                                                        <td class="picker date">
                                                            <input class="form-control end_date" type="text" placeholder="End Date" id="end_date">
															<input type="hidden" id="e_date" name="e_date" value="" />
                                                        </td>
														<td class="fin year">
															<select  id="yearpicker" name="yearpicker">
															<!--<input type="hidden" id="y_date" name="y_date" value="" /><?=$Fin_Year_Array?>-->
															<?php
																foreach($Fin_Year_Array as $key => $Year_Val){
															?>
																	<option style="font-size:18px;" value="<?=$Year_Val?>"><?=$Year_Val?></option>
															<?php
																}
															?>
															</select>
														</td>
                                                        <td class="text-center" >
                                                            <input type="submit" id="submit" name="submit" class="btn btn-primary" style="float: right;"  value="Go" />
                                                        </td>
                                                    </tr>
													<div style="text-align: center;">
                                                        <span value="" id="vali_value"  style="color:red"></span>
													</div>
							<!--						 <tr >
                         <td align="center" colspan="5" height="10px"><hr size="1"></td>
                    </tr>-->
													</tbody>
                                            </table>
                                        </form>
										</td></tr>
										<tr>
										<td>
										<?php
                    
						if(isset($_REQUEST['preport'])){
							// Adding 5.5 hours for the search
							$From_D_Epoch = strtotime($_REQUEST['s_date']);
							$To_D_Epoch = strtotime($_REQUEST['e_date']);
							$From_YMD= date("Y-m-d",$From_D_Epoch);
							$To_YMD= date("Y-m-d",$To_D_Epoch);
							//echo $From_YMD;
							$d_name = $_REQUEST['devicename'];
							$fd_name = $_REQUEST['feedername'];
							//echo $d_name;
							$imei = $_REQUEST['imei'];
							//echo $imei;
							$prep = $_REQUEST['preport'];
							$Fin_Year = $_REQUEST['y_date'];
							//$IMEI = base64_decode($_REQUEST['c1']);
							//$basic = $this->Common_model->getbasicInfo($d_name);
							//print_r($basic);
						}
						if($prep == 1){
							include("pw_report.php");
						}
						if($prep == 2){
							include("overview_report.php");
						}
						if($prep == 3){
							include("temp_report.php");
						}
						if($prep == 4){
							include("prod_report.php");
						}
						if($prep == 5){
							include("grid_report.php");
						}
						if($prep == 6){
							include("stophrs_report.php");
						}
						if($prep == 7){
							include("alarm_report.php");
						}
						if($prep == 8){
							include("alarm_group_report.php");
						}
						if($prep == 9){
							include("dgr_individual_report.php");
						}
						if($prep == 10){
							include("dgr_group_report.php");
						}
						if($prep == 11){
							//include("daily_ebslot_report.php");
						}
						if($prep == 12){
							include("finyear_report.php");
						}
						if($prep == 13){
							$d_name = $_REQUEST['devicename'];
							$From_D_Epoch = strtotime($_REQUEST['s_date']);
							$To_D_Epoch = strtotime($_REQUEST['e_date']);
							$From_YMD= date("Y-m-d",$From_D_Epoch);
							$To_YMD= date("Y-m-d",$To_D_Epoch);
							//include("pw_curve.php");
							 echo "<script> window.open('pw_curve?d_name=$d_name&s_date=$From_YMD&e_date=$To_YMD','_blank') </script>";
							// header("location:http://64.202.189.237/dashboard/pw_curve?pop=yes");
							// $this->load->view('dashboard/pw_curve','');
							}
		?>
							</table>
                            </div>
                        </div>
                   </div>
           </div>
    </div>
</main>
<?php  $this->load->view('layout/footer'); ?>
<script type="text/javascript">
    $(document).ready(function () {
		$('.fin').hide();
		$('.feed').hide();
        $("#start_date").attr("autocomplete", "off");
        $("#end_date").attr("autocomplete", "off");
		$("#yearpicker").attr("autocomplete", "off");
        $("#end_date").datepicker({});
        $("#submit").attr("disabled", true);
		
		/*$("#yearpicker").change(function () {
        $("#submit").attr("disabled", false);
        $("#try").html("");
        var yearpick = $("#yearpicker").datepicker('getDate').getFullYear();
        document.getElementById('myField').value = yearpick;

    });*/
		 $(function () {
        $('#preport').change(function () {
			var preport = document.getElementById("preport").value;
			if(preport == "12") {
            $("#submit").attr("disabled", true);
             $("#vali_value").html("");
            $('.picker').hide();
			$('.feed').hide();
			$('.fin').show();
			$('.dev').show();			
			$("#submit").attr("disabled", false);
           // $('#' + $(this).val()).show();
			} else if(preport == "6" || preport == "8" || preport == "10") {
            $("#submit").attr("disabled", true);
             $("#vali_value").html("");
            $('.dev').hide();
			$('.feed').hide();
			$('.fin').hide();
			$('.picker').show();
			} /*else if(preport == "10") {
            $("#submit").attr("disabled", true);
             $("#vali_value").html("");
            $('.dev').hide();
			$('.feed').show();
			$('.fin').hide();
			$('.picker').show();
			}*/ else {
				$("#vali_value").html("");
				$('.picker').show();
				$('.dev').show();
				$('.fin').hide();
				$('.feed').hide();
			}
        });
    });
		$("#devicename").change(function () {
				var devicename = document.getElementById("devicename").value;
				//var x = document.getElementById("devicename").selectedIndex;
				//var dev = document.getElementsByTagName("option")[x].value;
				// alert(devicename);
				if (devicename != "Choose Device Name") {
					$("#vali_value").html("");
                    $("#submit").attr("disabled", false);
				} else {
				    $("#vali_value").html("Please Select Device");
                    $("#submit").attr("disabled", true);
				}
		});
        $("#end_date").change(function () {
			//var devicename = document.getElementById("devicename").value;
            var startDate = document.getElementById("start_date").value;
            if (startDate) {
                $("#vali_value").html("");
                var endDate = document.getElementById("end_date").value;
                document.getElementById('e_date').value = endDate;
                if ((Date.parse(endDate) < Date.parse(startDate))) {
                    $("#vali_value").html("End date should be greater than Start date");
                } else {
                    $("#submit").attr("disabled", false);
                    $("#vali_value").html("");
                }
            } else {
                $("#vali_value").html("Please Enter Start date");
            }

        });
        $("#start_date").change(function () {
            var startDate = document.getElementById("start_date").value;
            document.getElementById('s_date').value = startDate;
            $("#vali_value").html("");
            var endDate = document.getElementById("end_date").value;
            if (endDate) {
                if ((Date.parse(endDate) < Date.parse(startDate))) {
                    $("#vali_value").html("End date should be greater than Start date");
                } else {
                    $("#submit").attr("disabled", false);
                    $("#vali_value").html("");
                }
            } else {
                $("#vali_value").html("Please Enter End date");
                $("#submit").attr("disabled", true);
            }
        });
        
		 /*$("#yearpicker").change(function () {
            var yearDate = document.getElementById("yearpicker").value;
            document.getElementById('y_date').value = yearDate;
            $("#vali_value").html("");
            if (yearDate) {
                $("#submit").attr("disabled", false);
                    $("#vali_value").html("");
            } else {
                $("#vali_value").html("Please Enter Financial Year");
                $("#submit").attr("disabled", true);
            }
        });*/
		
        /*$("#submit").click(function (e) {          

            var checkedNum = $('select[name="devicename"]').length;
            if (checkedNum) {

                $("#submit").submit(); // Submit the form

            } else {
                 e.preventDefault();
                $("#vali_value").html("Please select one device");
                $("#submit").attr("disabled", true);
            }
        });*/
    });
</script>
<script type="text/javascript">
    $('.start_date').datepicker({
    orientation: "bottom",
    autoclose: true
});

$('.end_date').datepicker({
    orientation: "bottom",
    autoclose: true
});

 $('.year-own').datepicker({
        format: "yyyy",
        //viewMode: "years",
		changeMonth: false,
		changeYear: true,
        minViewMode: "years",
		weekStart: 1,
		orientation: "bottom",
		yearRange: '2019:',
		autoclose: true
    });

</script>