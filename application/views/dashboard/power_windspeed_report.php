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
    <!--<ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Park View</li>
    </ol>-->
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12" style="background-color:#ecf1f09e;">
                    <div class="card">
                        <div class="card-header"> Reports</div>
                        <div class="card-body">
                            <div class="row">


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><h5> Select Device List:</h5></label>
                                        <div class="alert alert-info">
                                            <strong>Info!</strong> Please select only one devices.
                                        </div>
                                        <div style="text-align: center;">
                                            <span value="" id="dev_count"  style="color:red"></span>

                                        </div>

                                        <div class="row">
                                            <?php
                                            foreach ($reports['deviceList'] as $key => $value) {
                                                ?>
                                                <div class="searchable-container items " style="width:14%;" >
                                                    <div class="info-block block-info clearfix">
                                                        <div class="square-box pull-left">
                                                            <span class="glyphicon glyphicon-tags glyphicon-lg"></span>
                                                        </div>
                                                        <div data-toggle="buttons" class="btn-group bizmoduleselect" >
                                                            <label class="check btn btn-default" style="width:200px;text-overflow: ellipsis;overflow-wrap: break-word;word-wrap: break-word;">


                                                                <div class="bizcontent" >
                                                                    <fieldset id="checkArray">
                                                                        <input type="checkbox" class="checkbox_check" id="input_<?php echo $key; ?>" name="device_name[]" autocomplete="off" value="<?php echo $value['Device_Name']; ?>"  >
                                                                    </fieldset>
                                                                    <span class="glyphicon glyphicon-ok glyphicon-lg"></span>
                                                                    <div style="font-weight: 900;padding:2px 2px 2px 2px;margin:2px 2px 2px 2px;">
                                                                        <?php echo $value['Device_Name']; ?>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                        </div>
                                        <!--                                        </div>-->
                                    </div>
                                </div><br/><br/><br/>

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header" id="temp">Power VS Wind speed Report</div>
                                        <div class="card-body">

                                            <form role="form" method="post" action="<?php echo base_url() . 'dashboard/pw_reportData' ?>">
                                                <table class="table table-responsive-sm table-hover table-outline mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-center">Sl.No</th>
                                                            <th class="text-center">Start Date</th>
                                                            <th class="text-center">End Date</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center"># </td>
                                                            <td class="text-center">

                                                                <input class="form-control start_date" type="text" placeholder="Start Date" id="start_date">
                                                                <input type="hidden" id="s_date" name="s_date" value="" />
                                                                <input type="hidden" id="d_name" name="d_name" value="" />

                                                            </td>
                                                    <div style="text-align: center;">
                                                        <span value="" id="vali_value"  style="color:red"></span>

                                                    </div>
                                                    <td class="text-center">
                                                        <input class="form-control end_date" type="text" placeholder="End Date" id="end_date">
                                                        <input type="hidden" id="e_date" name="e_date" value="" />

                                                    </td>
                                                    <div style="text-align: center;">
                                                        <span value="" id="vali_date"  style="color:red"></span>

                                                    </div>
                                                    <td class="text-center">

                                                        <input type="submit" id="submit" name="submit" class="btn btn-primary" style="float: right;"  value="Go" />
                                                    </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </form>

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


        $("#start_date").attr("autocomplete", "off");
        $("#end_date").attr("autocomplete", "off");

        $("#end_date").datepicker({});


        $("#submit").attr("disabled", true);
        $("#end_date").change(function () {
            var startDate = document.getElementById("start_date").value;
            if (startDate) {
                $("#vali_value").html("");
                var endDate = document.getElementById("end_date").value;
                document.getElementById('e_date').value = endDate;
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
            var startDate = document.getElementById("start_date").value;
            document.getElementById('s_date').value = startDate;
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


        $('.checkbox_check').on('change', function () {


            var device_name = "";
            $(':checkbox:checked').each(function (i) {
                device_name = $(this).val();
                document.getElementById('d_name').value = device_name;
            });
            //alert(device_name);


            var $inputs = $('input[name="device_name[]"]:checkbox');
            if ($(this).is(':checked')) {
                //   $("#dev_count").html("Only one device can be selected");
                var startDate = document.getElementById("start_date").value;
                var endDate = document.getElementById("end_date").value;
                if (startDate && endDate) {
                    $("#submit").attr("disabled", false);
                } else {
                    $("#vali_date").html("Please Select date");
                    $("#submit").attr("disabled", true);
                }
                $inputs.not(this).prop('disabled', true); // <-- disable all but checked one
            } else {
                // $("#dev_count").html("");


                $("#submit").attr("disabled", true);
                $inputs.prop('disabled', false); // <--
            }
        })


        $("#submit").click(function (e) {
           

            var checkedNum = $('input[name="device_name[]"]:checked').length;
            if (checkedNum) {

                $("#submit").submit(); // Submit the form

            } else {
                 e.preventDefault();
                $("#dev_count").html("Please select one device");
                $("#submit").attr("disabled", true);
            }
        });



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
</script>