<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$prodvalues = array();
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
        $prodvalues = $this->Common_model->getprodReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $From_YMD, $To_YMD);
        // print_r($data);die;
?>
                   <h5 class="card-header">Production Report <?php echo " - " . $basic[0]['Device_Name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Feeder <?php echo " - " . $basic[0]['Connect_Feeder'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Location <?php echo " - " . $basic[0]['Site_Location'] ?>
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/prodaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/prod_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow:auto;max-height:320px;" >
                                <thead class="thead-light" >
                                    <tr >
                                        <th style="text-align:center;width:160px;">#</th>
                                        <th style="text-align:center;width:160px;">Date</th>
										<th style="text-align:center;width:160px;">Time</th>	
										<?php
										if($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
										?>										
										<th style="text-align:center;width:170px;">PAT Gen0</th>
										<th style="text-align:center;width:170px;">PAT Gen1</th>                                        
                                        <th style="text-align:center;width:180px;">Net Total</th>
										<?php
										}
										elseif($basic[0]['Format_Type'] == 2 || $basic[0]['Format_Type'] == 4) {
										?>										
										<th style="text-align:center;width:170px;">PAT Gen1</th>
										<th style="text-align:center;width:170px;">PAT Gen2</th>                                        
                                        <th style="text-align:center;width:180px;">Import Kwh</th>
										<?php
										}
										elseif($basic[0]['Format_Type'] == 3 || $basic[0]['Format_Type'] == 10)   {
										?>
										<th style="text-align:center;width:170px;">PAT Gen1</th>
										<th style="text-align:center;width:170px;">PAT Gen2</th>                                        
                                        <th style="text-align:center;width:180px;">Production Total</th>
										<?php
										} 
										else {
										?>
										<th style="text-align:center;width:170px;">Kwh Positive</th>
										<th style="text-align:center;width:170px;">Kwh Negative</th>   
										<th style="text-align:center;width:170px;">KVar Positive</th>
                                        <?php
										} 
										?>
                                    </tr>
                                </thead>
                                <tbody >
								
								
                                    <?php
                                    $index = 1;
                                    foreach ($prodvalues as $key => $val) {										
                                        ?>
                                        <tr style="line-height:3px;">
                                            <th style="text-align:center;width:160px;" ><?php echo $index; ?></th>
                                            <td style="text-align:center;width:160px;"> <?php echo $val['Date_S']; ?></td>
                                            <td style="text-align:center;width:160px;"> <?php echo $val['Time_S']; ?></td>
											<?php
										if($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
											?>
                                            <td style="text-align:center;width:170px;"> <?php echo $val['PAT_Gen0']; ?></td>
											<td style="text-align:center;width:170px;"> <?php echo $val['PAT_Gen1']; ?></td>
											<td style="text-align:center;width:180px;"> <?php echo $val['PAT_Gen2']; ?></td>
											<?php
										} elseif($basic[0]['Format_Type'] == 2 || $basic[0]['Format_Type'] == 4) {
											?>
											<td style="text-align:center;width:170px;"> <?php echo $val['PAT_Gen1']; ?></td>
											<td style="text-align:center;width:170px;"> <?php echo $val['PAT_Gen2']; ?></td>
											<td style="text-align:center;width:180px;"> <?php echo $val['Import_Kwh']; ?></td>
										<?php
										} elseif($basic[0]['Format_Type'] == 3 || $basic[0]['Format_Type'] == 10)   {
										?>
                                            <td style="text-align:center;width:170px;"> <?php echo $val['PAT_Gen1']; ?></td>
											<td style="text-align:center;width:170px;"> <?php echo $val['PAT_Gen2']; ?></td>
											<td style="text-align:center;width:180px;"> <?php echo $val['Production_Total']; ?></td>
										<?php
										} else {
											?>
											<td style="text-align:center;width:170px;"> <?php echo $val['Kwh_Positive']; ?></td>
											<td style="text-align:center;width:170px;"> <?php echo $val['Kwh_Negative']; ?></td>
											<td style="text-align:center;width:180px;"> <?php echo $val['KVar_Positive']; ?></td>
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
                   