<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
    td{
        height: 25%;
    }

    .page {
        display: inline-block;
        padding: 5px 18px;
        margin-right: 4px;
        border-radius: 3px;
        border: solid 1px #c0c0c0;
        background: #e9e9e9;
        box-shadow: inset 0px 1px 0px rgba(255,255,255, .8), 0px 1px 3px rgba(0,0,0, .1);
        font-size: .875em;
        font-weight: bold;
        text-decoration: none;
        color: #717171;
        text-shadow: 0px 1px 0px rgba(255,255,255, 1);
    }

</style>


<main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active"><?php echo $d_name ?></li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Power VS Wind speed Report <?php echo " - " . $d_name ?>
                        </h5>
                        <div class="card-body" style="overflow-y:scroll;">
                            <div class="col-md-12">
                                <?php if($links){ ?>
                                <div class="row">

                                    <p style="font-size: 22px;" class="page"><?php echo $links; ?></p>

                                </div>
                                <?php } ?>
                                <div class="row">


                                    <form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/pwaction?dname=' . $d_name . '&sdate=' . $s_date . '&edate=' . $e_date; ?>">

                                        <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/generate_pdf?dname=' . $d_name . '&sdate=' . $s_date . '&edate=' . $e_date; ?>">

                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            </div><br/><br/>
                            <table class="table table-responsive-sm table-hover table-outline mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Date</th> 
                                        <th scope="col">Time</th>
                                        <th scope="col">Wind speed</th>
                                        <th scope="col">Power</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 1;
                                    foreach ($pwreport as $key => $val) {
                                        ?>

                                        <tr>
                                            <th scope="row"><?php echo $index; ?></th>
                                            <td> <?php echo $val['Date_S']; ?></td>
                                            <td> <?php echo $val['Time_S']; ?></td>
                                            <td> <?php echo $val['Windspeed']; ?></td>
                                            <td> <?php echo $val['Power']; ?></td>
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


            </div>
            <!-- /.row-->
        </div>
    </div>
</main>
<!-- /page content -->
<?php $this->load->view('layout/footer'); ?>




