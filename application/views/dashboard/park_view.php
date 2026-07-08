<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
?>

<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script src=<?php echo base_url(); ?>assets/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    setInterval("my_function();",60000);
    function my_function(){
      $("#park_info").load("<?php echo base_url() . 'dashboard/park_view'?> #park_info" );
    }

  </script>


<main class="main">
   <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol> -->

    <div class="container-fluid" >


        <div id="park_info" class="card card-inverser" style="width: 100%;background-color: #F7F7F7">
           <!-- <div class="card-body">-->
                <table class="table table-responsive-sm table-hover table-outline mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#<br/> &nbsp;</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Device <br/>Name</th> 
                             <th scope="col" style="font-size:18px;text-align: center;">Status<br/> &nbsp;</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Date <br/>& Time</th>
                            <th scope="col" style="font-size:18px;text-align: center;">WindSpeed<br/> m/s</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Power<br/> kW</th>
                            <th scope="col" style="font-size:18px;text-align: center;">GRPM<br/> rpm</th>
                            <th scope="col" style="font-size:18px;text-align: center;">RRPM<br/> rpm</th>
                           
                            <th scope="col" style="font-size:18px;text-align: center;">HTSC <br/>NO</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Site<br/> &nbsp;</th>
                            <th scope="col" style="font-size:18px;text-align: center;">Region<br/> &nbsp;</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 1;
                           foreach ($parkview as $key => $val) {
                            ?>

                            <tr>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $index; ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['Device_Name'];  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center; color:<?php echo $val['Parent_ID']; ?>"><?php echo $val['status'];  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['date_s'] . " " . $val['time_s'];;  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['windspeed'];  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['power'];  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['grpm'];  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['rrpm'];  ?></th>
                                
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['HTSC_No'];  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['Site_Location'];  ?></th>
                                <th scope="row" style="font-size:18px;text-align: center;"><?php echo $val['Region'];  ?></th>
                            </tr>
                            <?php
                            $index++;
                           }
                        ?>
                    </tbody>
                    
                   
                    
                  <!--  <tbody>
                        <?php
                         if(isset($leftparkview)){
                           foreach ($leftparkview as $key => $val) {
                            ?>
                        

                            <tr>
                                <th scope="row"><?php echo $index; ?></th>
                                <th scope="row"><?php echo $val['device_name'];  ?></th>
                                <th scope="row"><?php echo $val['status'];  ?></th>
                                <th scope="row"><?php echo $val['Date_S'] . " " . $val['Time_S'];  ?></th>
                                <th scope="row"><?php echo $val['windpeed'];  ?></th>
                                <th scope="row"><?php echo $val['power'];  ?></th>
                                <th scope="row"><?php echo $val['grpm'];  ?></th>
                                <th scope="row"><?php echo $val['rrpm'];  ?></th>
                                
                                <th scope="row"><?php echo $val['htsc'];  ?></th>
                                <th scope="row"><?php echo $val['site'];  ?></th>
                                <th scope="row"><?php echo $val['region'];  ?></th>
                            </tr>
                            <?php
                            $index++;
                           }
                         }
                        ?>
                    </tbody>-->
                    
                    
                    
                    
                    
                </table>
           <!-- </div>-->

        </div>

    </div>









    <!--</div>-->
</body>
</main>
<?php $this->load->view('layout/footer'); ?>



