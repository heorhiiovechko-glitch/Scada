<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$gad_gen = array();
		$finyearvalues = array();
        $config = array();
		$From_D_Epoch = $_REQUEST['yearpicker'];
		$YearArray=explode("-",$From_D_Epoch);
		//$To_D_Epoch = strtotime($_REQUEST['e_date']);
		$YearArr1= $YearArray[1];
		$YearArr= $YearArray[0];
		//$To_YMD= date("Y",$To_D_Epoch);
		//$YearArr=explode("-",$Year);
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
		//print_r($d_name);
		//print_r($From_D_Epoch);
		//print_r($basic[0]);
		$dev_name = $basic[0]['Device_Name'];
        $data["d_name"] = $d_name;
        $data["s_date"] = $From_YMD;
        $data["e_date"] = $To_YMD;
        $finyearvalues = $this->Common_model->getFinyearReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $YearArr, $YearArr1);
        // print_r($finyearvalues);die;
		$Months=array("4"=>"Apr-".$YearArr."", "5"=>"May-".$YearArr."","6"=> "Jun-".$YearArr."","7"=>"Jul-".$YearArr."" ,"8" => "Aug-".$YearArr."","9"=> "Sep-".$YearArr."", "10"=> "Oct-".$YearArr."", "11"=>"Nov-".$YearArr."", "12"=>"Dec-".$YearArr."","13"=>"Jan-".$YearArr1."","14"=>"Feb-".$YearArr1."","15"=>"Mar-".$YearArr1."");
 						//echo "Apr-".$_REQUEST['inputYear'];
		$Months_input=array("4"=>"$YearArr-04", "5"=>"$YearArr-05","6"=>"$YearArr-06","7"=>"$YearArr-07","8"=>"$YearArr-08","9"=>"$YearArr-09","10"=>"$YearArr-10","11"=>"$YearArr-11","12"=>"$YearArr-12","13"=>"$YearArr1-01","14"=>"$YearArr1-02","15"=>"$YearArr1-03");
		$CurYear = date("Y");
		$Months_arr1=array("4","5","6","7","8","9","10","11","12","1","2","3");
?>
                   <h5 class="card-header">Financial Year Generation Report <?php echo " - " . $basic[0]['Device_Name'] ?>&nbsp;from&nbsp;<?php echo $YearArr ?>&nbsp; to&nbsp;<?php echo $YearArr1; ?> &nbsp;&nbsp;Feeder <?php echo " - " . $basic[0]['Connect_Feeder'] ?>&nbsp;&nbsp;&nbsp;&nbsp;Location <?php echo " - " . $basic[0]['Site_Location'] ?>
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/finyearaction?dname=' . $d_name . '&sdate=' . $YearArr . '&edate=' . $YearArr1; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/finyear_pdf?dname=' . $d_name . '&sdate=' . $YearArr . '&edate=' . $YearArr1; ?>">

                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="overflow-y:scroll;max-height:320px;width:100%;" >
                                <thead class="thead-light" >
                                    <tr>
                                       <!-- <th scope="col" style="width:180px;">Device Name</th>-->
										<?php
											for($Count=3;$Count<=14;$Count++){
										?>
											<th scope="col" style="width:77px;"><?=$Months[$Count+1]?></th>					
										<?php
											}
										?>
				
                                        <th scope="col" style="width:70px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody >
								
								
                                   
                                        <tr style="line-height: 3px;">
                                           <!-- <th style="text-align:center;width:180px;" ><?php echo $dev_name; ?></th>-->
											 <?php
                                    foreach ($finyearvalues as $key => $val) {
										if($YearArr != $CurYear) {
											$gad_gen[$val['Month']] = $val['gad_gen'] > 0 && $val['gad_gen'] < 250000?$val['gad_gen']:'000';	
										} else {
											$gad_gen[$val['Month']] = '';
										}
											$Sum_Gen += $gad_gen[$val['Month']];
									}
									foreach($Months_arr1 as $Month_val){
                                        ?>
                                            <td style="text-align:center;width:77px;"> <?php echo $gad_gen[$Month_val]; ?></td>
											<?php
                                       // $index++;
                                    }
                                    ?>
                                            <td style="text-align:center;width:75px;"> <?php echo $Sum_Gen; ?></td>
                                           </tr>
                                       
                                </tbody>
                            </table>

                        </div>
                   