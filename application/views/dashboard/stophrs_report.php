<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$stophrvalues = array();
        $config = array();
		$From_D_Epoch = strtotime($_REQUEST['s_date']);
		$To_D_Epoch = strtotime($_REQUEST['e_date']);
		$From_YMD= date("Y-m-d",$From_D_Epoch);
		$To_YMD= date("Y-m-d",$To_D_Epoch);
		//echo $From_YMD;
		//$d_name = $_REQUEST['devicename'];
        $newdata = array(
             // 'd_name' => $d_name,
              's_date' => $From_YMD,
              'e_date' => $To_YMD
        );
        $this->session->set_userdata($newdata);
        //$basic = $this->Common_model->getbasicInfo($d_name);
		$typelist = $this->Common_model->getDeviceList('', 1);
		//$data["d_name"] = $d_name;
        $data["s_date"] = $From_YMD;
        $data["e_date"] = $To_YMD;
        $stophrvalues = $this->Common_model->getstophrsReport($typelist,$From_YMD, $To_YMD);
		/*foreach ($typelist as $list) {
			$Region = $list->Region;
		}*/
		//print_r($State);
         //print_r($stophrvalues);
			
	?>
					<h5 class="card-header">Stop Hours Group Report  </h5>
	
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/stophrsaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/stophrs_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">

                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow-y:scroll;max-height:320px;width:100%;" >
                                <thead class="thead-light" >
                                    <tr>
                                        <th scope="col" style="width:100px;">#</th>
                                        <th scope="col" style="width:100px;">Date</th> 
                                        <th scope="col" style="width:300px;">Device Name</th>
                                        <th scope="col" style="width:200px;">Stop Hours</th>
										<th scope="col" style="width:320px;"></th>
                                    </tr>
                                </thead>
                                <tbody >
								
                                    <?php
                                    $index = 1;
                                    foreach ($stophrvalues as $key => $val) {		
										$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
										$stophrs = 24 - $val['Run'];
										$stophrs = $stophrs > 24 || $stophrs < 0 ? '000':$stophrs;
                                        ?>
                                        <tr style="line-height: 3px;">
                                            <th style="text-align:center;width:100px;" ><?php echo $index; ?></th>
                                            <td style="text-align:center;width:100px;"> <?php echo $val['Date_S']; ?></td>
                                            <td style="text-align:center;width:300px;"> <?php echo $dev_name; ?></td>
                                            <td style="text-align:center;width:200px;"> <?php echo $stophrs; ?></td>
											<td style="text-align:center;width:320px;"> </td>
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                   