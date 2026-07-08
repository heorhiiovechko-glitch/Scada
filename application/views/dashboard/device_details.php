<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
?>

<style>
    td{
        height: 25%;
    }
</style>

<script type="text/javascript">
    setInterval("my_function();",60000);
    function my_function(){
      $("#device_info").load("<?php echo base_url() . 'dashboard/device_details?id='.$imei.'&type='.$type;?> #device_info" );
    }

  </script>
  
<main class="main">
    <!-- Breadcrumb-->
   <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item">
            <a href="#">Admin</a>
        </li>
        <li class="breadcrumb-item active"><?php echo $device_name ?></li>
    </ol> -->
    <div class="container-fluid">
       <div id="device_info" class="animated fadeIn">
	   <div class="row">
	  <!-- <div class="card-body">-->
	   <div class="col-lg-3">
	   
									<?php
                                    $index = 1;
                                    foreach ($currentdata as $key => $val) {
                                        ?>
                                       <img class="img-fluid" src="<?php echo base_url(); ?>assets/images/box/<?php
                                        if ($val['Parent_ID'] == "green") {
                                            echo $val['Parent_ID'] . '.gif';
                                        } else {
                                            echo $val['Parent_ID'] . '.png';
                                        }
                                        ?>" style="margin-top: -2px; margin-left: 14px; margin-bottom: 3px;"/>
										<div class="text-value" style= "font-size:16px;padding:2px;">HTSC &nbsp; :
                                            <?php echo $val['HTSC_No']; ?>
										</div>
										<div class="text-value" style= "font-size:16px;padding:2px;">Name &nbsp;  :
                                            <?php echo $val['Device_Name']; ?>
										</div>
										<div class="text-value" style= "font-size:16px;padding:2px;">Speed &nbsp;  :
                                            <?php echo $val['windspeed']; ?> m/s
										</div>
										<div class="text-value" style= "font-size:16px;padding:2px;">Power &nbsp;   :
                                            <?php echo $val['power']; ?> kW
										</div>
										<?php
									}
									?>
                                    </div>
	   
                <div class="col-lg-3">
				<div class="text-value" style ="height:5px;"></div>
				<?php
                                    $index = 1;
                                    foreach ($kwhtoday as $key => $val) {
                                        ?>
				<div class="card text-white <?php echo 'bg-white'; ?> tile-box">
				<div class="card-body pb-3">
				 <div class="text-value" style ="color : black">kWh active today  </div>
				  <div class="text-value" style ="color : black"><?php echo round($val['gad'],1);?> </div>
				</div>
				</div>
				<!--<div class="card text-white <?php echo 'bg-white'; ?> tile-box">
				<div class="card-body pb-3">-->			
				 <div class="text-value" style ="color : black; font-size:18px; height:50px;">Avg.Power today 
				 <?php echo round($val['avgpower'],2); 
				 ?> kW </div>
				<!--</div>
				</div>-->
				<!--<div class="card text-white <?php echo 'bg-white'; ?> tile-box">
				<div class="card-body pb-3">-->
				 <div class="text-value" style ="color : black; font-size:18px;height:50px;">Avg.Speed today  
				 <?php echo round($val['avgwind'],2);
				 ?> m/s </div>
				<?php
									}
									?>
				 				<!--</div>
				</div>-->
				</div>
				 <div class="col-lg-3">
				<div class="text-value" style ="height:5px;"></div>
				<?php
                                    $index = 1;
                                    foreach ($kwhmonth as $key => $val) {
                                        ?>
				<div class="card text-white <?php echo 'bg-white'; ?> tile-box">
				<div class="card-body pb-3">
				 <div class="text-value" style ="color : black">kWh active month  </div>
				 <div class="text-value" style ="color : black"><?php echo round($val['gad'],1);?>  </div>
				</div>
				</div>
				<div class="text-value" style ="color : black; font-size:18px; height:50px;">Avg.Power month 
				 <?php echo round($val['avgpower'],2); 
				 ?> kW </div>
				  <div class="text-value" style ="color : black; font-size:18px;height:50px;">Avg.Speed month  
				 <?php echo round($val['avgwind'],2);
				 ?> m/s </div>
				 <?php
									}
									?>
				
				</div>
				<div class="col-lg-3">
				<div class="text-value" style ="height:5px;"></div>
				<?php
                                    $index = 1;
                                    foreach ($kwhyear as $key => $val) {
                                        ?>
				<div class="card text-white <?php echo 'bg-white'; ?> tile-box">
				<div class="card-body pb-3">
				 <div class="text-value" style ="color : black">kWh active year  </div>
				 <div class="text-value" style ="color : black"><?php echo round($val['gad'],1);?>  </div>
				</div>
				</div>
				<?php
									}
									?>
				
				</div>
				</div>
				<!--</div>-->
				<?php
                                   // $index = 1;
                                    foreach ($devicedetails as $key => $val) {
										$Statusdate=$val['Date_S'];
									}
									
       
                                        ?>
            <div class="row">
                <div class="col-md-12" >
                    <div class="card" >
                        <h5 class="card-header" style="height:35px;text-align:center;">TURBINE STATUS AND ELECTRICAL DATA -&nbsp; &nbsp; <?php echo $Statusdate;?>
                           <!-- <?php if ($limit_geo == 10) { ?>
                                <form role="form" method="post" action="<?php echo base_url() . 'dashboard/device_details?id=' . $imei . '&type=' . $type . '&limit=' . '1'; ?>">
                                    <input type="submit" name="geo_but" class="btn btn-default" style="float: right;"  value="Recent Data" />

                                </form>
                            <?php } 
							?> -->
                        </h5>
                      <!--  <div class="card-body" > -->
                            <table class="table">
                                <thead class="thead-light" >
                                    <tr style="height:35px;">
                                       	<th scope="col">Time<br/> &nbsp;</th>
                                        <th scope="col">GRPM<br/> rpm</th> 
                                        <th scope="col">RRPM<br/> rpm</th>
                                        <th scope="col">Wind<br/> m/s</th>
										<th scope="col">Status<br/> &nbsp;</th>
										<th scope="col">Power<br/> kW</th>
                                        <?php if ($type == 1 || $type == 10 || $type == 6) { ?> <th scope="col">Pitch<br/> &nbsp;</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Nacelle<br/> &nbsp;</th> <?php } ?>
										<?php if ($type == 1 || $type == 3 || $type == 7 || $type == 8 || $type == 10 || $type == 6) { ?> <th scope="col">Freq<br/> Hz</th> <?php } ?>
										 <th scope="col">R Volt<br/> V</th> 
                                        <th scope="col">Y Volt<br/> V</th>
                                        <th scope="col">B Volt<br/> V</th>
                                        <th scope="col">R Crt<br/> A</th>
                                        <th scope="col">Y Crt<br/> A</th>
                                        <th scope="col">B Crt<br/> A</th>
																		
                                        
                                    </tr>
                                </thead>
                                <tbody >
                                    <?php
                                    $index = 1;
									$green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running','M/CRunning','Power Up','FreeWheeling','FreewheelingG1', 'FreewheelingG2', 'FreeWheelingG1', 'FreeWheelingG2', 'OperateG1', 'OperateG2', 'Operate G1', 'Operate G2', 'Running G1');
									$blue_array = array('GRIDDROP', 'Grid Spike', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop');
                                    foreach ($devicedetails as $key => $val) {
                                        ?>

                                        <tr>
                                           <td> <?php echo $val['Time_S']; ?></td>
                                            <td> <?php echo $val['GRPM']; ?></td>
                                            <td> <?php echo $val['RRPM']; ?></td>
                                            <td> <?php echo $val['Windspeed']; ?></td>
										<?php
										if(in_array($val['Status'],$green_array)){
										?>
											 <td style="font-size:16px;text-align: center;color:green"> <?php echo $val['Status']; ?></td>
										<?php
										} elseif(in_array($val['Status'],$blue_array)){ 
										?>
											<td style="color:blue"> <?php echo $val['Status']; ?></td>
										<?php
										} else { 
										?>
											<td style="color:red"> <?php echo $val['Status']; ?></td>
										<?php
										}
										?>
											 <td> <?php echo $val['Power']; ?></td>
                                            <?php if ($type == 1 || $type == 10 || $type == 6) { ?><td> <?php echo $val['Pitch']; ?></td><?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['Nacelle_Position']; ?></td><?php } ?>
											<?php if ($type == 1 || $type == 3 || $type == 7 || $type == 8 || $type == 10 || $type == 6) { ?><td> <?php echo $val['Frequency']; ?></td><?php } ?>
											 <td> <?php echo $val['RPhase_Volt']; ?></td>
                                            <td> <?php echo $val['YPhase_Volt']; ?></td>
                                            <td> <?php echo $val['BPhase_Volt']; ?></td>
                                            <td> <?php echo $val['RPhase_Current']; ?></td>
                                            <td> <?php echo $val['YPhase_Current']; ?></td>
                                            <td> <?php echo $val['BPhase_Current']; ?></td>
											                                         
                                            
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                       <!-- </div> -->
                    </div>
                </div>
                <!-- /.col-->
                <div class="col-md-12">
                    <div class="card" >
                        <h5 class="card-header" style="height:35px;text-align:center;">TEMPERATURE AND PRODUCTION DATA -&nbsp; &nbsp; <?php echo $Statusdate;?>
                          <!--  <?php if ($limit_pro == 10) { ?>
                                <form role="form" method="post" action="<?php echo base_url() . 'dashboard/device_details?id=' . $imei . '&type=' . $type . '&limit=' . '1'; ?>">
                                    <input type="submit" name="pro_but" class="btn btn-default" style="float: right;"  value="Recent Data" />

                                </form>
                            <?php } ?> -->

                        </h5>
                       <!-- <div class="card-body" style="overflow-y:scroll;">-->


                            <table class="table">
                                <thead class="thead-light" style="background-color:#ffa64d">
                                    <tr>
                                       	<th scope="col">Time<br/> &nbsp;</th>
                                        <?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10) { ?> <th scope="col" >Amb Temp <br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10 || $type == 4) { ?> <th scope="col">Nacel Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 6 || $type == 10) { ?> <th scope="col">Gear Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10 || $type == 4) { ?> <th scope="col">Gen1 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 6 || $type == 10) { ?> <th scope="col">Hydr Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 6 || $type == 10) { ?> <th scope="col">Cntrl Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 6 || $type == 10 || $type == 3) { ?> <th scope="col">Bear Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 2) { ?> <th scope="col">G1 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 2) { ?> <th scope="col">G2 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 2) { ?> <th scope="col">G3 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 2) { ?> <th scope="col">G4 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 2) { ?> <th scope="col">G5 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 2) { ?> <th scope="col">G6 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 3) { ?> <th scope="col">Thyristor Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 3) { ?> <th scope="col">Main Panel Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 3) { ?> <th scope="col">Gen2 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 4) { ?> <th scope="col">Gen Bear1 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 4) { ?> <th scope="col">Gen Bear2 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 4) { ?> <th scope="col">Gear Oil Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Cntl Panel Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Gen Bear1 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Gen Bear2 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Gear Box Oil Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Windng 1 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Windng 2 Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">DE Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">DE NDE Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Nacel Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Main Bearng Temp<br/> &deg;C</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Transfr Oil Temp<br/> &deg;C</th> <?php } ?>                                       
                                        <?php if ($type == 1 || $type == 6 || $type == 10) { ?> <th scope="col">PAT Gen0<br/> kWh</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 3 || $type == 2 || $type == 6 || $type == 10 || $type == 4) { ?> <th scope="col">PAT Gen1<br/> kWh</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 3 || $type == 2 || $type == 6 || $type == 10 || $type == 4) { ?> <th scope="col">PAT Gen2<br/> kWh</th> <?php } ?>
										<?php if ($type == 3 || $type == 10) { ?> <th scope="col">Prod Total<br/> kWh</th> <?php } ?>
										<?php if ($type == 7 || $type == 8) { ?> <th scope="col">Kwh Post<br/> kWh</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Kwh Negt<br/> kWh</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">KVar Post<br/> kWh</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">KVar Negt<br/> kWh</th> <?php } ?>                                       
                                      <!--  <?php if ($type == 2 || $type == 3 || $type == 4) { ?> <th scope="col">Import Kwh</th> <?php } ?>-->
										<?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10) { ?> <th scope="col">Total Hours<br/> h</th> <?php } ?>
									   <?php if ($type == 1 || $type == 6 || $type == 10) { ?> <th scope="col">Run Hours<br/> h</th> <?php } ?>
                                        <?php if ($type == 1 || $type == 2 || $type == 3 || $type == 6 || $type == 10 || $type == 4) { ?> <th scope="col">Gen1 Hours<br/> h</th> <?php } ?>
                                        <?php if ($type == 2 || $type == 3 || $type == 10 || $type == 4) { ?> <th scope="col">Gen2 Hours<br/> h</th> <?php } ?>
										 <?php if ($type == 6 || $type == 1) { ?> <th scope="col">Line Ok<br/> h</th> <?php } ?>
										  <?php if ($type == 8) { ?> <th scope="col">Operate Hours<br/> h</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Grid failure Hours<br/> h</th> <?php } ?>
                                        <?php if ($type == 7 || $type == 8) { ?> <th scope="col">Stopped Hours<br/> h</th> <?php } ?>
                                       <!-- <?php if ($type == 10) { ?> <th scope="col">Line Hours</th> <?php } ?>-->

                                        
                                        
                                        <!--<?php if ($type == 3 || $type == 2 || $type == 4) { ?> <th scope="col">Import Kwh</th> <?php } ?>
                                        <?php if ($type == 3) { ?> <th scope="col">Import Kvarh</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">PAM Gen0</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">PAM Gen1</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">PAM Gen2</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">PATP Gen0</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">PATP Gen1</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">PATP Gen2</th> <?php } ?>                                       
                                        <?php if ($type == 6 || $type == 10) { ?> <th scope="col">Turbine Ok</th> <?php } ?>                                       
                                        <?php if ($type == 6) { ?> <th scope="col">Month Total</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Month Line Ok</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Month Turbine Ok</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Month Run</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Month Gen1</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Trip Total</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Trip Line Ok</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Trip Turbine Ok</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Trip Run</th> <?php } ?>
                                        <?php if ($type == 6) { ?> <th scope="col">Trip Gen1</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Active Total Gen Import</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Active Total Gen Export</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Reactive Total Gen Import</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Reactive Total Gen Export</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Active Gen1 Import</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Active Gen1 Export</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Reactive Gen1 Import</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Reactive Gen1 Export</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Active Gen2 Import</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Active Gen2 Export</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Reactive Gen2 Import</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">Reactive Gen2 Export</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">G1 Connected Counts</th> <?php } ?>
                                        <?php if ($type == 7) { ?> <th scope="col">G2 Connected Counts</th> <?php } ?>-->
                                       
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 1;
                                    foreach ($devicedetails as $key => $val) {
                                        ?>

                                        <tr>
                                             <td> <?php echo $val['Time_S']; ?></td>
										   <?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10) { ?> <td> <?php echo $val['Ambient_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10 || $type == 4) { ?>  <td> <?php echo $val['Nacel_Temp']; ?></td>  <?php } ?>
                                            <?php if ($type == 1 || $type == 6 || $type == 10) { ?>  <td> <?php echo $val['Gear_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10 || $type == 4) { ?> <td> <?php echo $val['Gen1_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 6 || $type == 10) { ?> <td> <?php echo $val['Hydraulic_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 6 || $type == 10) { ?>  <td> <?php echo $val['Control_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 6 || $type == 10 || $type == 3) { ?> <td> <?php echo $val['Bearing_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 2) { ?> <td> <?php echo $val['G1_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 2) { ?> <td> <?php echo $val['G2_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 2) { ?> <td> <?php echo $val['G3_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 2) { ?> <td> <?php echo $val['G4_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 2) { ?> <td> <?php echo $val['G5_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 2) { ?> <td> <?php echo $val['G6_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 3) { ?> <td> <?php echo $val['Thyristor_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 3) { ?> <td> <?php echo $val['Main_Panel_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 3) { ?> <td> <?php echo $val['Gen2_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 4) { ?> <td> <?php echo $val['Gen_Bear1_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 4) { ?> <td> <?php echo $val['Gen_Bear2_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 4) { ?> <td> <?php echo $val['Gear_Oil_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Control_Panel_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Gear_Bearing1_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Gear_Bearing2_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Gear_Box_Oil_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Gen_Winding1_Temp']; ?></td><?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Gen_Winding2_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Gen_DE_Bearing_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Gen_DE_NDE_Bearing_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?> <td> <?php echo $val['Nacelle_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?> <td> <?php echo $val['Main_Bearing_Temp']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?> <td> <?php echo $val['Transformer_Oil_Temp']; ?></td> <?php } ?>

                                            <?php if ($type == 1 || $type == 6 || $type == 10) { ?> <td> <?php echo $val['PAT_Gen0']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 3 || $type == 2 || $type == 6 || $type == 10 || $type == 4) { ?> <td> <?php echo $val['PAT_Gen1']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 3 || $type == 2 || $type == 6 || $type == 10 || $type == 4) { ?><td> <?php echo $val['PAT_Gen2']; ?></td> <?php } ?>
											<?php if ($type == 3 || $type == 10) { ?> <td> <?php echo $val['Production_Total']; ?></td> <?php } ?>
                                           <!-- <?php if ($type == 2 || $type == 3 || $type == 4) { ?> <td> <?php echo $val['Import_Kwh']; ?></td> <?php } ?>-->
										   <?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['Kwh_Positive']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['Kwh_Negative']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['KVar_Positive']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['KVar_Negative']; ?></td> <?php } ?>
										    <?php if ($type == 1 || $type == 3 || $type == 6 || $type == 10) { ?> <td> <?php echo $val['Total_Hours']; ?></td> <?php } ?>
											<?php if ($type == 1 || $type == 6 || $type == 10) { ?><td> <?php echo $val['Run_Hours']; ?></td> <?php } ?>
                                            <?php if ($type == 1 || $type == 2 || $type == 3 || $type == 6 || $type == 10 || $type == 4) { ?> <td> <?php echo $val['Gen1_Hours']; ?></td> <?php } ?>
                                            <?php if ($type == 2 || $type == 3 || $type == 10 || $type == 4) { ?> <td> <?php echo $val['Gen2_Hours']; ?></td> <?php } ?>
                                            <?php if ($type == 6 || $type == 1) { ?><td> <?php echo $val['Line_Ok']; ?></td> <?php } ?>
											<?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['Operate_Hours']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['Grid_failure_Hours']; ?></td> <?php } ?>
                                            <?php if ($type == 7 || $type == 8) { ?><td> <?php echo $val['Stopped_Hours']; ?></td> <?php } ?>
                                           <!-- <?php if ($type == 10) { ?><td> <?php echo $val['Line_Hours']; ?></td> <?php } ?>-->
                                           
                                          
                                           <!-- <?php if ($type == 3 || $type == 2 || $type == 4) { ?> <td> <?php echo $val['Import_Kwh']; ?></td> <?php } ?>
                                            <?php if ($type == 3) { ?> <td> <?php echo $val['Import_Kvarh']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['PAM_Gen0']; ?></td>  <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['PAM_Gen1']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['PAM_Gen2']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['PATP_Gen0']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['PATP_Gen1']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['PATP_Gen2']; ?></td> <?php } ?>
                                            <?php if ($type == 6 || $type == 10) { ?><td> <?php echo $val['Turbine_Ok']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Month_Total']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Month_Line_Ok']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Month_Turbine_Ok']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Month_Run']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Month_Gen1']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Trip_Total']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Trip_Line_Ok']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Trip_Turbine_Ok']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Trip_Run']; ?></td> <?php } ?>
                                            <?php if ($type == 6) { ?><td> <?php echo $val['Trip_Gen1']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Active_Total_Gen_Import']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Active_Total_Gen_Export']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Reactive_Total_Gen_Import']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Reactive_Total_Gen_Export']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Active_Gen1_Import']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Active_Gen1_Export']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Reactive_Gen1_Import']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Reactive_Gen1_Export']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Active_Gen2_Import']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Active_Gen2_Export']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Reactive_Gen2_Import']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['Reactive_Gen2_Export']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['G1_Connected_Counts']; ?></td> <?php } ?>
                                            <?php if ($type == 7) { ?><td> <?php echo $val['G2_Connected_Counts']; ?></td> <?php } ?>-->
                                            
                                            
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                       <!-- </div> -->
                    </div>
                </div>
                <!-- /.col-->

            </div> 
            <!-- /.row-->
        </div
    </div>
</main>
<!-- /page content -->
<?php $this->load->view('layout/footer'); ?>

