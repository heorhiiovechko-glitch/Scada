<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$alarmvalues = array();
        $config = array();
		$From_D_Epoch = strtotime($_REQUEST['s_date']);
		$To_D_Epoch = strtotime($_REQUEST['e_date']);
		$From_YMD= date("Y-m-d",$From_D_Epoch);
		$To_YMD= date("Y-m-d",$To_D_Epoch);
		//echo $From_YMD;
		$d_name = $_REQUEST['devicename'];
		//$imei = $_REQUEST['imei'];
        $newdata = array(
              'd_name' => $d_name,
              's_date' => $From_YMD,
              'e_date' => $To_YMD
        );
        $this->session->set_userdata($newdata);
        $basic = $this->Common_model->getbasicInfoimei($d_name);
		//print_r($imei);
		//print_r($basic[0]['IMEI']);
		//print_r($basic[0]);
		$dev_name = $basic[0]['Device_Name'];
        $data["d_name"] = $d_name;
        $data["s_date"] = $From_YMD;
        $data["e_date"] = $To_YMD;
        $alarmvalues = $this->Common_model->getalarmReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $From_YMD, $To_YMD);
        // print_r($data);die;
?>
                   <h5 class="card-header">Alarm Log Report <?php echo " - " . $basic[0]['Device_Name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Feeder <?php echo " - " . $basic[0]['Connect_Feeder'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Location <?php echo " - " . $basic[0]['Site_Location'] ?>
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/alarmaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/alarm_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">

                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow-y:scroll;max-height:320px;width:100%;" >
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" style="width:200px;">#</th>
                                        <th scope="col" style="width:200px;">Date</th> 
                                        <th scope="col" style="width:200px;">Time</th>
                                        <th scope="col" style="width:420px;">Error Status</th>
                                    </tr>
                                </thead>
                                <tbody >
								
                                    <?php
                                    $index = 1;
                                    foreach ($alarmvalues as $key => $val) {										
                                        ?>
                                        <tr style="line-height: 3px;">
                                            <th style="text-align:center;width:200px;" ><?php echo $index; ?></th>
                                            <td style="text-align:center;width:200px;"> <?php echo $val['Date_S']; ?></td>
                                            <td style="text-align:center;width:200px;"> <?php echo $val['Time_S']; ?></td>
                                            <td style="text-align:center;width:400px;"> <?php echo substr($val['Status'],0,61); ?></td>
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                   