<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
?>

<script type="text/javascript">
    setInterval("my_function();",60000);
    function my_function(){
      $("#status_info").load("<?php echo base_url() . 'dashboard/index?status='.$color; ?> #status_info" );
    }

  </script>
  
<style>
    .table-borderless td,
    .table-borderless th {
        border: 0.5;
    }
    body {
       
    }
</style>

<main class="main">
    <!-- Breadcrumb-->
   <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>-->
<!--<tr><td>&nbsp;</td></tr>-->

    <div class="container-fluid" >


        <div id="status_info" class="card card-inverser" style="width: 100%;background-color: #F7F7F7">
            
                <table class="table table-responsive-sm table-hover table-outline mb-0">
                    <thead class="thead-light">
					
                        <tr>
                            <th scope="col" >#<br/> &nbsp;</th></th>
                            <th scope="col" style="font-size:18px;text-align: center;">Device <br/> Name</th> 
							<th scope="col" style="font-size:18px;text-align: center;">Status<br/> &nbsp;</th> 
                            <th scope="col" style="font-size:18px;text-align: center;">Date <br/> & Time</th>                           
							<th scope="col" style="font-size:18px;text-align: center;">WindSpeed<br/> m/s</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Power<br/> kW</th>
                            <th scope="col" style="font-size:18px;text-align: center;">GAD<br/> kWh</th>
                            <th scope="col" style="font-size:18px;text-align: center;">HTSC <br/>NO</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Site<br/> &nbsp;</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Region<br/> &nbsp;</th>

                        </tr>
                    </thead>
                    <tbody >
                        <?php
                        $index = 1;
                        foreach ($status_det as $key => $val) {
                           
                            ?>

                            <tr>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $index; ?></th>
                                <td style="font-size:18px;text-align: center;"><a href="<?php echo base_url() . 'dashboard/device_details?id=' . $val['imei'] . '&type=' . $val['type']; ?>"  target="_blank"> <?php echo $val['device_name']; ?> </a></td>
                                <td style="font-size:18px; text-align: center;font-weight:bold; color:<?php echo $color; ?>"> <?php echo $val['status_val']; ?></td> 
								<td style="font-size:18px;text-align: center;"> <?php echo $val['Date_S'] . " " . $val['Time_S']; ?></td>
                                <td style="font-size:18px;text-align: center;"> <?php echo $val['wind_speed']; ?></td>
                                <td style="font-size:18px;text-align: center;"> <?php echo $val['power']; ?></td>
                                <td style="font-size:18px;text-align: center;"> <?php echo $val['gad']; ?></td>
                                <td style="font-size:18px;text-align: center;"> <?php echo $val['htsc']; ?></td>
                                <td style="font-size:18px;text-align: center;"> <?php echo $val['site']; ?></td>
                                <td style="font-size:18px;text-align: center;"> <?php echo $val['region']; ?></td>

                            </tr>
                            <?php
                            $index++;
                        }
                        ?>
                    </tbody>
                </table>
            

        </div>

    </div>

</main>







    <!--</div>-->
</body>
<?php $this->load->view('layout/footer'); ?>
