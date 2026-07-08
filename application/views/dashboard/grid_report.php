<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$gridvalues = array();
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
        $gridvalues = $this->Common_model->getgridReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $From_YMD, $To_YMD);
        // print_r($data);die;
?>
                   <h5 class="card-header">Grid Report <?php echo " - " . $basic[0]['Device_Name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Feeder <?php echo " - " . $basic[0]['Connect_Feeder'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Location <?php echo " - " . $basic[0]['Site_Location'] ?>
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/gridaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/grid_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">

                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow-y:scroll;max-height:320px;width:100%;" >
                                <thead class="thead-light" >
                                    <tr>
                                        <th scope="col" style="width:50px;">#</th>
                                        <th scope="col" style="width:100px;">Date</th> 
                                        <th scope="col" style="width:80px;">Time</th>
                                        <th scope="col" style="width:100px;">R Volt</th>
                                        <th scope="col" style="width:100px;">Y Volt</th>
										<th scope="col" style="width:100px;">B Volt</th>
                                        <th scope="col" style="width:100px;">R Current</th>
										<th scope="col" style="width:100px;">Y Current</th>
										<th scope="col" style="width:100px;">B Current</th>
										<th scope="col" style="width:80px;">Power</th>
										<th scope="col" style="width:110px;">Power Factor</th>
                                    </tr>
                                </thead>
                                <tbody >
								
                                    <?php
                                    $index = 1;
                                    foreach ($gridvalues as $key => $val) {										
                                        ?>
                                        <tr style="line-height: 3px;">
                                            <th style="text-align:center;width:50px;" ><?php echo $index; ?></th>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['Date_S']; ?></td>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['Time_S']; ?></td>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['RPhase_Volt']; ?></td>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['YPhase_Volt']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['BPhase_Volt']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['RPhase_Current']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['YPhase_Current']; ?></td>
											<td style="text-align:center;width:100px;"> <?php echo $val['BPhase_Current']; ?></td>
											<td style="text-align:center;width:80px;"> <?php echo $val['Power']; ?></td>
											<td style="text-align:center;width:110px;"> <?php echo $val['Power_Factor']; ?></td>
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                   