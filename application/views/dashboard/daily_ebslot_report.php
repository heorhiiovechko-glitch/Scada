<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
				
        $data = array();
		$ebslotvalues = array();
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
        $ebslotvalues = $this->Common_model->getebslotReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $From_YMD, $To_YMD);
		
         print_r($ebslotvalues);
?>
                   <h5 class="card-header">Daily EB Slot Report <?php echo " - " . $basic[0]['Device_Name'] ?>
                        </h5>
                        <div class="card-body" >
                            
                                <div class="row">
                                    &nbsp;&nbsp;<form target="_blank" method="post" action="<?php echo base_url() . 'excel_export/ebslotaction?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">
                                    <input type="submit" name="export" class="btn btn-success" value="Export Excel" />
                                    </form>
                                    &nbsp;&nbsp;
                                    <div class="col-md-10">
                                        <form target="_blank" method="post" action="<?php echo base_url() . 'export_pdf/ebslot_pdf?dname=' . $d_name . '&sdate=' . $From_YMD . '&edate=' . $To_YMD; ?>">

                                            <input type="submit" name="export_pdf" class="btn btn-info" value="Export Pdf" />
                                        </form>
                                    </div>
                                </div>
                            <br/>
                            <table class="table" style="display:block;overflow-y:scroll;max-height:520px;width:100%;" >
                                <thead class="thead-light" style="position: fixed;">
                                    <tr>
                                        <th scope="col" style="width:100px;">Date</th> 
                                        <th scope="col" style="width:80px;">6:00-9:00 (C1)</th>
                                        <th scope="col" style="width:100px;">18.00-21.00 (C2)</th>
										<th scope="col" style="width:100px;">21.00-22.00 (C3)</th>
										<th scope="col" style="width:100px;">9.00-18.00 (C4)</th>
										<th scope="col" style="width:100px;">5.00-6.00 (C4)</th>
										<th scope="col" style="width:100px;">22.00-24.00 (C5)</th>
										<th scope="col" style="width:100px;">0.00-5.00 (C5)</th>
										<th scope="col" style="width:80px;">Total(Daily)</th>
										<th scope="col" style="width:80px;">Cumulative</th>
										<th scope="col" style="width:100px;">Other Peak Hours (C3+C4)</th>
										<th scope="col" style="width:80px;">Peak Hours (C1+C2)</th>
										<th scope="col" style="width:80px;">Night Hours(C5)</th>
                                    </tr>
                                </thead>
                                <tbody >
								
								<tr><td colspan="11" >&nbsp;</td></tr>
                                    <?php
									$index = 1;
									//print_r($basic[0]['Format_Type']);
                                    foreach ($ebslotvalues as $key => $val) {					
										if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
												//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
												$Import_LCS = $val['Import_Max']- $val['Import_Min'];
												$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
												$Total_Gen = $val['Gen1_Max']-$val['Gen1_Min'];
												$Total_Gen = $Total_Gen>0 && $Total_Gen<=15000?$Total_Gen:'0';
												$Run = $val['Run_Max']-$val['Run_Min'];
												$Gen1 = $val['Gen1H_Max']-$val['Gen1H_Min'];
												$Gen1=$Gen1>'24' && $Gen1<'50'?'24':$Gen1;	
												$Lull_Hours=$Run-$Gen1;
												if($Lull_Hours==(-1))
												$Lull_Hours=0;
												$Run_Hours=$Gen1;
												$Run_Hours=$Run_Hours>0 && $Run_Hours<=25?$Run_Hours:'0';
												$GD_Hours = 24-($val['Line_Max']-$val['Line_Min']);
												$GD_Hours=$GD_Hours>0 && $GD_Hours<=25?$GD_Hours:'0';
												$GA_Percent=((24 - $GD_Hours) / 24) * 100 ;
												$Lull_Hours=$Lull_Hours>0 && $Lull_Hours<=25?$Lull_Hours:'0';
												$Loss_Due_To_GD = ($Total_Gen/$Run_Hours) * $GD_Hours;
												$BD_Hours=24-($GD_Hours+$Lull_Hours+$Run_Hours);
												$BD_Hours=$BD_Hours>0 && $BD_Hours<=25?$BD_Hours:'0';								
												//$Loss_Due_To_BD = ($Total_Gen/$Run_Hours) * $BD_Hours; 
												//$MA_Percent=(((24-$GD_Hours)-($BD_Hours)) / (24 - $GD_Hours)) *100;
												$MA_Percent=((24-$BD_Hours) / 24 ) *100;
												$Sum_Import += $Array_Import;
												$Sum_Gen += $Total_Gen;
												$Sum_Run += $Run_Hours;
												$Sum_GD += $GD_Hours;
												$Sum_BD += $BD_Hours;
												$Sum_Lull += $Lull_Hours;
												
										} else {		
												//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
												$Import_LCS = $val['Import_Max']- $val['Import_Min'];
												$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
												$Total_Gen = $val['Gen1_Max']-$val['Gen1_Min'];
												$Total_Gen = $Total_Gen>0 && $Total_Gen<=15000?$Total_Gen:'0';
												$Gen1 = (($val['Gen1H_Max']-$val['Gen1H_Min'])+($val['Gen2H_Max']-$val['Gen2H_Min']));
												$Gen1=$Gen1>'24' && $Gen1<'50'?'24':$Gen1;	
												$Run_Hours=$Gen1;
												$Run_Hours=$Run_Hours>0 && $Run_Hours<=25?$Run_Hours:'0';
												$GD_Hours = round(($val['Diff']/3600),1);
												$GD_Hours =($GD_Hours >=0 && $GD_Hours <=24)?$GD_Hours : '0';
												$BD_Hours = round(($val['Diff1']/3600),1);
												$BD_Hours =($BD_Hours >=0 && $BD_Hours <=24)?$BD_Hours : '0';
												$Lull_Hours= 24 - ($Run_Hours +$BD_Hours + $GD_Hours);
												if($Lull_Hours==(-1))
												$Lull_Hours=0;
												$Lull_Hours=$Lull_Hours>0 && $Lull_Hours<=25?$Lull_Hours:'0';									
												$GA_Percent=((24 - $GD_Hours) / 24) * 100 ;
												$MA_Percent=((24-$BD_Hours) / 24 ) *100;
												$Sum_Import += $Array_Import;
												$Sum_Gen += $Total_Gen;
												$Sum_Run += $Run_Hours;
												$Sum_GD += $GD_Hours;
												$Sum_BD += $BD_Hours;
												$Sum_Lull += $Lull_Hours;												
										}
                                        ?>
                                        <tr style="line-height: 3px;">
                                            <td style="text-align:center;width:110px;"> <?php echo $val['Date_S']; ?></td>
                                            <!--<td style="text-align:center;width:160px;"> <?php echo $dev_name; ?></td>-->
                                            <td style="text-align:center;width:100px;"><?=($Import_LCS >=0 && $Import_LCS <=500)? round($Import_LCS,1):'0'?></td>
											<td style="text-align:center;width:110px;"><?php echo round($Total_Gen,1); ?></td>
											<td style="text-align:center;width:100px;">24</td>
											<td style="text-align:center;width:100px;"><?php echo round($Run_Hours,1); ?></td>
											<td style="text-align:center;width:100px;"><?php echo round($GD_Hours,1); ?></td>
											<td style="text-align:center;width:100px;"><?php echo round($BD_Hours,1); ?></td>
											<td style="text-align:center;width:100px;"><?php echo round($Lull_Hours,1); ?></td>
											<td style="text-align:center;width:100px;" ><?php echo round($GA_Percent,1); ?></td>
											<td style="text-align:center;width:100px;" ><?php echo round($MA_Percent,1); ?></td>
                                        </tr>
                                        <?php
                                        $index++;										
                                    }
									
                                    ?>	
									<tr>
									<td style="text-align:center;width:110px;"><b>Total</b></td>
									<td style="text-align:center;width:100px;"><b><?php echo round($Sum_Import,2);?></b></td>
									<td style="text-align:center;width:100px;"><b><?php echo round($Sum_Gen,2);?></b></td>
									<td style="text-align:center;width:110px;"><b></td>
									<td style="text-align:center;width:100px;"><b><?php echo round($Sum_Run,2);?></b></td>
									<td style="text-align:center;width:100px;"><b><?php echo round($Sum_GD,2);?></b></td>
									<td style="text-align:center;width:100px;"><b><?php echo round($Sum_BD,2);?></b></td>
									<td style="text-align:center;width:100px;"><b><?php echo round($Sum_Lull,2);?></b></td>
									<td style="text-align:center;width:110px;"></td>
									<td style="text-align:center;width:110px;"></td>
									</tr>									
                                </tbody>
                            </table>

                        </div>
                   