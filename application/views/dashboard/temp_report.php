<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$tempvalues = array();
        $config = array();
		$From_D_Epoch = strtotime($_REQUEST['s_date']);
		$To_D_Epoch = strtotime($_REQUEST['e_date']);
		$From_YMD= date("Y-m-d",$From_D_Epoch);
		$To_YMD= date("Y-m-d",$To_D_Epoch);
		//echo $From_YMD;
		$d_name = $_REQUEST['devicename'];
        $newdata = array(
              'd_name' => $d_name,
              's_date' => $From_YMD,
              'e_date' => $To_YMD
        );
        $this->session->set_userdata($newdata);
        $basic = $this->Common_model->getbasicInfoimei($d_name);
		//print_r($basic);
        $data["d_name"] = $d_name;
        $data["s_date"] = $From_YMD;
        $data["e_date"] = $To_YMD;
        $tempvalues = $this->Common_model->gettempReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $From_YMD, $To_YMD);
        // print_r($data);die;
?>
                   <h5 class="card-header">Temperature Report <?php echo " - " . $basic[0]['Device_Name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Feeder <?php echo " - " . $basic[0]['Connect_Feeder'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Location <?php echo " - " . $basic[0]['Site_Location'] ?>
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/tempaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/temp_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow:auto;max-height:320px;" >
                                <thead class="thead-light">
                                    <tr>
                                        <th style="text-align:center;width:50px;">#</th>
                                        <th style="text-align:center;width:100px;">Date</th>
										<th style="text-align:center;width:100px;">Time</th>	
										<?php
										if($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
										?>										
										<th style="text-align:center;width:110px;">Ambient</th>
										<th style="text-align:center;width:110px;">Hydraulic</th>                                        
                                        <th style="text-align:center;width:100px;">Gear</th>
										<th style="text-align:center;width:100px;">Gen1</th>
										<th style="text-align:center;width:110px;">Nacel</th>
										<th style="text-align:center;width:120px;">Control</th>                                        
                                        <th style="text-align:center;width:120px;">Bearing</th>
										<?php
										} elseif($basic[0]['Format_Type'] == 2) {
										?>										
										<th style="text-align:center;width:120px;">Gen1</th>
										<th style="text-align:center;width:120px;">Gear Oil</th>
										<th style="text-align:center;width:120px;">Gen2</th>
										<th style="text-align:center;width:120px;">Bearing</th>
										<th style="text-align:center;width:120px;">Gear Box</th>
										<th style="text-align:center;width:150px;">Main Bearing</th>
										<?php
										} elseif($basic[0]['Format_Type'] == 3) {
										?>
										<th style="text-align:center;width:90px;">Thyristor</th>
										<th style="text-align:center;width:80px;">Ambient</th>                                        
                                        <th style="text-align:center;width:100px;">Main Panel</th>
										<th style="text-align:center;width:80px;">Gen1</th>
										<th style="text-align:center;width:80px;">Gen2</th>
										<th style="text-align:center;width:100px;">Bearing</th>
										<th style="text-align:center;width:80px;">Gear</th>
										<th style="text-align:center;width:80px;">Nacel</th>
										<th style="text-align:center;width:80px;">Temp</th>                                        
                                        
										<?php
										} elseif($basic[0]['Format_Type'] == 4) {
										?>										
										<th style="text-align:center;width:130px;">Nacel</th>
										<th style="text-align:center;width:120px;">Gen1</th>
										<th style="text-align:center;width:120px;">Gen2</th>
										<th style="text-align:center;width:130px;">Gen Bear1</th>
										<th style="text-align:center;width:130px;">Gen Bear2</th>
										<th style="text-align:center;width:150px;">Gear Oil</th>
										<?php
										} elseif($basic[0]['Format_Type'] == 7 || $basic[0]['Format_Type'] == 8) {
										?>										
										<th style="text-align:center;width:80px;">Nacel</th>
										<th style="text-align:center;width:90px;">Cntl Panel</th>
										<th style="text-align:center;width:90px;">Gear Bearing1</th>
										<th style="text-align:center;width:90px;">Gear Bearing2</th>
										<th style="text-align:center;width:90px;">Gear Box Oil</th>
										<th style="text-align:center;width:90px;">Gen Winding 1</th>
										<th style="text-align:center;width:90px;">Gen Winding 2</th>
										<th style="text-align:center;width:80px;">Gen DE</th>
										<th style="text-align:center;width:80px;">Gen DE NDE</th>
										<?php
										} elseif($basic[0]['Format_Type'] == 10) {
										?>										
										<th style="text-align:center;width:90px;">Ambient</th>
										<th style="text-align:center;width:100px;">Hydraulic</th>                                        
                                        <th style="text-align:center;width:90px;">Gear</th>
										<th style="text-align:center;width:90px;">Gen1</th>
										<th style="text-align:center;width:90px;">Gen2</th>
										<th style="text-align:center;width:100px;">Nacel</th>
										<th style="text-align:center;width:100px;">Control</th>                                        
                                        <th style="text-align:center;width:100px;">Bearing</th>
										<?php
										} 
										?>
										
                                    </tr>
                                </thead>
                                <tbody >
								
								
								    <?php
                                    $index = 1;
                                    foreach ($tempvalues as $key => $val) {										
                                        ?>
                                        <tr style="line-height:3px;">
                                            <th style="text-align:center;width:50px;" ><?php echo $index; ?></th>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['Date_S']; ?></td>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['Time_S']; ?></td>
											<?php
										if($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
											?>
                                            <td style="text-align:center;width:110px;"> <?php echo $val['Ambient_Temp']; ?></td>
											<td style="text-align:center;width:110px;"> <?php echo $val['Hydraulic_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Gear_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Gen1_Temp']; ?></td>
											<td style="text-align:center;width:110px;"> <?php echo $val['Nacel_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['Control_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['Bearing_Temp']; ?></td>
											<?php
										} elseif($basic[0]['Format_Type'] == 2) {
											?>
                                            <td style="text-align:center;width:120px;"> <?php echo $val['G1_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['G2_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['G3_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['G4_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['G5_Temp']; ?></td>
											<td style="text-align:center;width:150px;"> <?php echo $val['G6_Temp']; ?></td>
											<?php
										} elseif($basic[0]['Format_Type'] == 3) {
											?>
                                            <td style="text-align:center;width:90px;"> <?php echo $val['Thyristor_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Ambient_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Main_Panel_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Gen1_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Gen2_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Bearing_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Gear_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Nacel_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Temp10']; ?></td>
											
											<?php
										} elseif($basic[0]['Format_Type'] == 4) {
											?>
                                            <td style="text-align:center;width:130px;"> <?php echo $val['Nacel_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['Gen1_Temp']; ?></td>
											<td style="text-align:center;width:120px;"> <?php echo $val['Gen2_Temp']; ?></td>
											<td style="text-align:center;width:130px;"> <?php echo $val['Gen_Bear1_Temp']; ?></td>
											<td style="text-align:center;width:130px;"> <?php echo $val['Gen_Bear2_Temp']; ?></td>
											<td style="text-align:center;width:130px;"> <?php echo $val['Gear_Oil_Temp']; ?></td>
											<?php
										} elseif($basic[0]['Format_Type'] == 7 || $basic[0]['Format_Type'] == 8) {
											?>
                                            <td style="text-align:center;width:80px;"> <?php echo $val['Nacel_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Control_Panel_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gear_Bearing1_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gear_Bearing2_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gear_Box_Oil_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gen_Winding1_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gen_Winding2_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Gen_DE_Bearing_Temp']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Gen_DE_NDE_Bearing_Temp']; ?></td>
											
											<?php
										} elseif($basic[0]['Format_Type'] == 10) {
											?>
                                            <td style="text-align:center;width:90px;"> <?php echo $val['Ambient_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Hydraulic_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gear_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gen1_Temp']; ?></td>
											<td style="text-align:center;width:90px;"> <?php echo $val['Gen2_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Nacel_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Control_Temp']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['Bearing_Temp']; ?></td>
											<?php
										}
											?>
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                   