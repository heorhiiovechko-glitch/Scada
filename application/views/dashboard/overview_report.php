<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$overviewvalues = array();
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
        $overviewvalues = $this->Common_model->getoverviewReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $From_YMD, $To_YMD);
        // print_r($data);die;
?>
                   <h5 class="card-header">Overview Report <?php echo " - " . $basic[0]['Device_Name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Feeder <?php echo " - " . $basic[0]['Connect_Feeder'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Location <?php echo " - " . $basic[0]['Site_Location'] ?>
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/overviewaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/overview_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow:auto;max-height:320px;" >
                                <thead class="thead-light" >
                                    <tr >
                                        <th style="text-align:center;width:50px;">#</th>
                                        <th style="text-align:center;width:100px;">Date</th>
										<th style="text-align:center;width:80px;">Time</th>										
										<th style="text-align:center;width:80px;">GRPM rpm</th>
										<th style="text-align:center;width:80px;">RRPM rpm</th>                                        
                                        <th style="text-align:center;width:80px;">Wind m/s</th>
										<?php
										if($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6 || $basic[0]['Format_Type'] == 10) {
										?>
										<th style="text-align:center;width:50px;">Pitch</th>
										<?php
										}
										?>
                                        <th style="text-align:center;width:80px;">Power kW</th>
										<th style="text-align:center;width:400px;">Status</th>
										<?php
										if($basic[0]['Format_Type'] == 3 || $basic[0]['Format_Type'] == 2 || $basic[0]['Format_Type'] == 4 || $basic[0]['Format_Type'] == 7 || $basic[0]['Format_Type'] == 8) {
										?>
										<th style="text-align:center;width:55px;">&nbsp;</th>
										<?php
										}
										?>
                                    </tr>
                                </thead>
                                <tbody >
								
                                    <?php
                                    $index = 1;
                                    foreach ($overviewvalues as $key => $val) {										
                                        ?>
                                        <tr style="line-height:3px;">
                                            <th style="text-align:center;width:50px;" ><?php echo $index; ?></th>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['Date_S']; ?></td>
                                            <td style="text-align:center;width:80px;"> <?php echo $val['Time_S']; ?></td>
                                            <td style="text-align:center;width:80px;"> <?php echo $val['GRPM']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['RRPM']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Windspeed']; ?></td>
											<?php
										if($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6 || $basic[0]['Format_Type'] == 10) {
											?>
											<td style="text-align:center;width:50px;"> <?php echo $val['Pitch']; ?></td>
											<?php
										}
											?>
                                            <td style="text-align:center;width:80px;"> <?php echo $val['Power']; ?></td>
											<td style="text-align:center;width:400px;"> <?php echo substr($val['Status'],0,55); ?></td>
											<?php
										if($basic[0]['Format_Type'] == 3 || $basic[0]['Format_Type'] == 2 || $basic[0]['Format_Type'] == 4 || $basic[0]['Format_Type'] == 7 || $basic[0]['Format_Type'] == 8) {
											?>
											<td style="text-align:center;width:50px;"> <?php echo ""; ?></td>
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
                   