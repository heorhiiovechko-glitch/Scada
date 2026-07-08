<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$alarmgrpvalues = array();
        $config = array();
		$From_D_Epoch = strtotime($_REQUEST['s_date']);
		$To_D_Epoch = strtotime($_REQUEST['e_date']);
		$From_YMD= date("Y-m-d",$From_D_Epoch);
		$To_YMD= date("Y-m-d",$To_D_Epoch);
		//echo $From_YMD;
		//$d_name = $_REQUEST['feedername'];
        $newdata = array(
              'd_name' => $d_name,
              's_date' => $From_YMD,
              'e_date' => $To_YMD
        );
        $this->session->set_userdata($newdata);
		/*$feeders = $this->Common_model->get_feeder_list();
		foreach ($feeders as $key => $value) {
			$State = $value['State'];
		}*/
		$typelist = $this->Common_model->getDeviceList('', 1);
		//print_r($basic);
        $data["d_name"] = $d_name;
        $data["s_date"] = $From_YMD;
        $data["e_date"] = $To_YMD;
        $alarmgrpvalues = $this->Common_model->getalarmgrpReport($typelist,$From_YMD, $To_YMD);
         //print_r($alarmgrpvalues);
?>
                   <h5 class="card-header">Alarm Log Group Report 
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/alarmgrpaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/alarmgrp_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">

                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow-y:scroll;max-height:320px;width:100%;" >
                                <thead class="thead-light" >
                                    <tr>
                                        <th scope="col" style="width:80px;">#</th>
                                        <th scope="col" style="width:150px;">Date</th> 
                                        <th scope="col" style="width:150px;">Time</th>
										<th scope="col" style="width:210px;">Device Name</th>
                                        <th scope="col" style="width:420px;">Error Status</th>
                                    </tr>
                                </thead>
                                <tbody >
								
                                    <?php
                                    $index = 1;
                                    foreach ($alarmgrpvalues as $key => $val) {		
										$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
                                        ?>
                                        <tr style="line-height: 3px;">
                                            <th style="text-align:center;width:80px;" ><?php echo $index; ?></th>
                                            <td style="text-align:center;width:150px;"> <?php echo $val['Date_S']; ?></td>
                                            <td style="text-align:center;width:150px;"> <?php echo $val['Time_S']; ?></td>
											<td style="text-align:center;width:210px;"> <?php echo $dev_name; ?></td>
                                            <td style="text-align:center;width:420px;"> <?php echo substr($val['Status'],0,61); ?></td>
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                   