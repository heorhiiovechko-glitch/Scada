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
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Export GAD</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                
                
                <div class="col-md-12" style="background-color:#ecf1f09e;">
                    <div class="card"  >
        
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card" style="height: 400px;">
                                        <div class="card-header">Export GAD </div>
                                        <div class="card-body">


                                            <div id='loadingmessage' style='display:none'>
                                                <img class="center" src="<?php echo base_url(); ?>assets/images/box/giphy.gif">
                                            </div>
                                            <canvas id="mygadcanvas" style="display: inline-block; width: 600px; height: 150px; vertical-align: top;" width="800" height="200"></canvas>

                                            <!-- /.row-->
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
<script type='text/javascript'>
    $(document).ready(function () {

        $(function () {
            setTimeout(loadgad, 1000);
        });
    })
</script>
<script type='text/javascript'>
    function loadgad() {
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>ajax/ajax_gad",
            dataType: 'json',
            success: function (data) {
                  $('#loadingmessage').hide();
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