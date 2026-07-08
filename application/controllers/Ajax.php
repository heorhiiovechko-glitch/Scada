<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//$this->output->enable_profiler(TRUE);
class Ajax extends CI_Controller {

    public $sessionUsername;
    public $sessionDbname;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'language'));
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->sessionUsername = $this->session->userdata('username');
        $this->sessionDbname = $this->session->userdata('db_name');
        $this->load->model('common/Common_model');
        if (empty($this->sessionUsername)) {
            echo json_encode(array('session' => 'expired'));
            exit;
        }
    }

    /*function ajax_temp_analysis() {
        $this->form_validation->set_rules('device_name[]', 'device name', 'required');
        $this->form_validation->set_rules('temp_name', 'Temp name', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $data = array();
        if ($this->form_validation->run() == TRUE) {
            $formvalues = $this->input->post();
            $device_list = $this->Common_model->getDeviceList($formvalues['device_name']);
            $j = 0;
            $listInfo['device'] = array();
            foreach ($device_list as $list) {
                $date = date('Y-m-d', strtotime($formvalues['date']));
                $search = array('order' => 'ASC', 'start_date' => $date, 'end_date' => $date);
                $val = $this->Common_model->get_device_data_Info($list->Format_Type, $list->IMEI, $search);
                // echo "<pre>"; print_r($val); exit;
                if (!empty($val)) {
                    $i = 0;
                    //$random_color = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
                    //$color = '#'.$random_color.$random_color.$random_color;

                    $listInfo['dataValue'][$j] = array('seriesname' => $list->Device_Name);
                    foreach ($val as $val_list) {
                        $status = $val_list['Status'];
                        $temp_value = isset($val_list[$formvalues['temp_name']]) ? $val_list[$formvalues['temp_name']] : '';

                        // $listInfo['dataLabel'][$i]['label'] =$val_list['Time_S'];
                        $listInfo['dataValue'][$j]['data'][$i]['value'] = $temp_value;
                        // echo $temp_value;
                        $i++;
                    }
                    //$data[$list->Device_Name]['color'][] = $color;
                    // echo '<pre>'; print_r($listInfo);  exit;
                    $j++;
                }
            }

            if (!empty($listInfo['dataValue'])) {
                $message = array('dataValue' => $listInfo['dataValue']);
            } else {
                $message = array('invalid' => validation_errors());
            }
        } else {
            $message = array('invalid' => validation_errors());
        }

        echo json_encode($message);
        die;
    }

    function ajax_power_curve() {
        $this->form_validation->set_rules('device_name[]', 'device name', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $data = array();
        if ($this->form_validation->run() == TRUE) {
            $formvalues = $this->input->post();
            $device_list = $this->Common_model->getDeviceList($formvalues['device_name']);
            $i = 0;

            foreach ($device_list as $list) {
                $date = date('Y-m-d', strtotime($formvalues['date']));
                $search = array('order' => 'ASC', 'start_date' => $date, 'end_date' => $date);
                $val = $this->Common_model->get_device_data_Info($list->Format_Type, $list->IMEI, $search);

                if (!empty($val)) {
                    $j = 0;
                    $random_color = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
                    $data[$list->Device_Name][0] = array('seriesname' => 'windspeed');
                    $data[$list->Device_Name][1] = array('seriesname' => 'power');
                    $color = '#' . $random_color . $random_color . $random_color;
                    foreach ($val as $val_list) {
                        $windspeed = isset($val_list['Windspeed']) ? $val_list['Windspeed'] : '';

                        //$data[$list->Device_Name]['windSpeed'][] = $windspeed;
                        $power = isset($val_list['Power']) ? $val_list['Power'] : '';
                        // $data[$list->Device_Name]['power'][] = $power;

                        $data[$list->Device_Name][0]['data'][$j]['value'] = $windspeed;
                        $data[$list->Device_Name][1]['data'][$j]['value'] = $power;
                        $j++;
                    }
                }
                $i++;
            }
            $message = $data;
        } else {
            $message = array('invalid' => validation_errors());
        }

        echo json_encode($message);
        die;
    }

    function ajax_perform_analysis() {
        $this->form_validation->set_rules('device_name[]', 'device name', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $data = array();
        if ($this->form_validation->run() == TRUE) {
            $formvalues = $this->input->post();
            $device_list = $this->Common_model->getDeviceList($formvalues['device_name']);
            foreach ($device_list as $list) {
                $date = date('Y-m-d', strtotime($formvalues['date']));
                $search = array('order' => 'ASC', 'start_date' => $date, 'end_date' => $date);
                $val = $this->Common_model->get_device_data_details($list->Format_Type, $list->IMEI, $search);

                $search1 = array('order' => 'DESC', 'start_date' => $date, 'end_date' => $date);
                $val = $this->Common_model->get_device_data_details($list->Format_Type, $list->IMEI, $search1);
                if (!empty($val)) {
                    $random_color = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
                    $color = '#' . $random_color . $random_color . $random_color;
                    $pat_gen2 = isset($val->PAT_Gen2) ? $val->PAT_Gen2 : '';
                    $data[$list->Device_Name]['value'][] = $pat_gen2;
                    $data[$list->Device_Name]['color'][] = $color;
                }
            }
            $message = array('valid' => $data);
        } else {
            $message = array('invalid' => validation_errors());
        }

        echo json_encode($message);
        die;
    }*/

    function ajax_power_curve_deviceone() {
        //$this->form_validation->set_rules('device_name[]', 'device name', 'required');
		$this->form_validation->set_rules('dev', 'dev', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('end_date', 'Date', 'required');
        $data = array();
        if ($this->form_validation->run() == TRUE) {			
            $formvalues = $this->input->post();
			 header('Content-Type: application/json');
			 
           //$dev = $formvalues['dev'];
			$data['dev'] = $formvalues['dev'];
			$data['dev'] = explode("x",$data['dev']);
            $data['start_date'] = date("Y-m-d", strtotime($formvalues['date']));
            $data['end_date'] = date("Y-m-d", strtotime($formvalues['end_date']));
				//echo $data['end_date'];
				//die;
            $basic = $this->Common_model->getbasicInfoimei($data['dev'][1]);
			// $basictwo = $this->Common_model->getbasicInfo($data['device_nametwo']);
           		/*foreach ($basic as $key => $value) {
                    print_r($value['Device_Name']);die;
                   
                }*/
				
            $deviceone = $this->Common_model->getPowerCurveData($basic[0]['Format_Type'], $basic[0]['IMEI'], $data['start_date'], $data['end_date']);
            $deviceoneCapacity = $this->Common_model->getPowerCurveCapacity($basic[0]['capacity']);
			$deviceonedot = $this->Common_model->getPowerCurveDatadot($basic[0]['Format_Type'], $basic[0]['IMEI'], $data['start_date'], $data['end_date']);
			$deviceoneCapacitydot = $this->Common_model->getPowerCurveCapacitydot($basic[0]['capacity']);
			/*foreach ($deviceone as $key => $value) {
                    print_r($value['Date_S']);die;
                   
                }*/
				//echo $deviceone; die;
           //  $devicetwo = $this->Common_model->getPowerCurveData($basictwo[0]['Format_Type'], $basictwo[0]['IMEI'], $data['start_date'], $data['end_date']);
          //  $devicetwoCapacity = $this->Common_model->getPowerCurveCapacity($basictwo[0]['capacity']);
			//$data['trm'] =$data['end_date'];
            $data['deviceone'] = json_encode($deviceone);
            $data['deviceonecapacity'] = json_encode($deviceoneCapacity);
			$data['deviceonedot'] = json_encode($deviceonedot);
			 $data['deviceonecapacitydot'] = json_encode($deviceoneCapacitydot);
          //  $data['devicetwo'] = json_encode($devicetwo);
          //  $data['devicetwoCapacity'] = json_encode($devicetwoCapacity);
            // $data['dataone'] = json_encode($deviceone);


            //$i = 0;


            $message = $data;
        } else {
//            $formvalues = $this->input->post();
//            $data['device_name'] = $formvalues['device_name'];
//            $data['start_date'] = $formvalues['date'];
//            $data['end_date'] = $formvalues['end_date'];
//            $i = 0;
//
//
//            $message = $data;
            $message = array('invalid' => validation_errors());
        }

        echo json_encode($message);
        //die;
    }

    function ajax_perfomance_curve() {
        //  $this->form_validation->set_rules('device_name[]', 'device name', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('selector', 'selector', 'required');
        $data = array();
        if ($this->form_validation->run() == TRUE) {
            $formvalues = $this->input->post();

            $data['date_val'] = $formvalues['date'];
            $data['selector'] = $formvalues['selector'];


            header('Content-Type: application/json');
            $perf_gad = $this->Common_model->calculate_gad_perf($data['selector'], $data['date_val']);
            // $perf_gad_two = $this->Common_model->calculate_gad_perf($data['device_nametwo'],$basictwo[0]['Format_Type'], $basictwo[0]['IMEI'],$data['selector'],$data['date_val']);
            $data['perf_gad'] = json_encode($perf_gad);


            $i = 0;


            $message = $data;
        } else {
            $message = array('invalid' => validation_errors());
        }

        echo json_encode($message);
        //die;
    }

    function ajax_windspeed_power() {

        header('Content-Type: application/json');

        $common_typelist = $this->Common_model->getDeviceList('', 1);
        $avgWindSpeed = $calcWindSpeed = $val = $gval = array();

        $avg_windspeed_value = $this->Common_model->calculate_windspeed($common_typelist);
		$gad_value = $this->Common_model->calculate_currentgad($common_typelist);
        //$avg_windspeed_value = json_encode($avg_windspeed_value);
        // $perf_gad_two = $this->Common_model->calculate_gad_perf($data['device_nametwo'],$basictwo[0]['Format_Type'], $basictwo[0]['IMEI'],$data['selector'],$data['date_val']);
        $data['avg_windspeed_value'] = json_encode($avg_windspeed_value);
		$data['gad_value'] = json_encode($gad_value);
       // $data['gen_value'] = json_encode($gen_value['gen']);


        $i = 0;

	//print_r($data);
        $message = $data;
        echo json_encode($message);
    }

    /*function ajax_gad() {
        header('Content-Type: application/json');

        $common_typelist = $this->Common_model->getDeviceList('', 1);

        $export_gad = $this->Common_model->calculate_gad($common_typelist);
        $data['export_gad'] = json_encode($export_gad);
        $message = $data;
        echo json_encode($message);
    }

    public function ajax_templist() {
        $this->form_validation->set_rules('devicetype', 'devicetype', 'required');
        $data = array();
        if ($this->form_validation->run() == TRUE) {
            $formvalues = $this->input->post();

            header('Content-Type: application/json');
            $devicetype = $formvalues['devicetype'];

            switch ($devicetype) {
                case 1:
                    $templist = array(
                        "0" => array('id' => "Ambient_Temp", 'name' => "Ambient"),
                        "1" => array('id' => "Nacel_Temp", 'name' => "Nacel"),
                        "2" => array('id' => "Gear_Temp", 'name' => "Gear"),
                        "3" => array('id' => "Gen1_Temp", 'name' => "Gen1"),
                        "4" => array('id' => "Hydraulic_Temp", 'name' => "Hydraulic"),
                        "5" => array('id' => "Control_Temp", 'name' => "Control"),
                        "6" => array('id' => "Bearing_Temp", 'name' => "Bearing")
                    );
                    break;
                case 2:
                    $templist = array(
                        "0" => array('id' => "Ambient_Temp", 'name' => "Ambient"),
                        "1" => array('id' => "Nacel_Temp", 'name' => "Nacel"),
                        "2" => array('id' => "Gear_Temp", 'name' => "Gear"),
                        "3" => array('id' => "Gen1_Temp", 'name' => "Gen1"),
                        "4" => array('id' => "Hydraulic_Temp", 'name' => "Hydraulic"),
                        "5" => array('id' => "Control_Temp", 'name' => "Control"),
                        "6" => array('id' => "Bearing_Temp", 'name' => "Bearing")
                    );
                    break;
                case 3:
                    $templist = array(
                        "0" => array('id' => "Ambient_Temp", 'name' => "Ambient"),
                        "1" => array('id' => "Nacel_Temp", 'name' => "Nacel"),
                        "2" => array('id' => "Gear_Temp", 'name' => "Gear"),
                        "3" => array('id' => "Gen1_Temp", 'name' => "Gen1"),
                        "4" => array('id' => "Gen2_Temp", 'name' => "Gen2"),
                        "5" => array('id' => "Hydraulic_Temp", 'name' => "Hydraulic"),
                        "6" => array('id' => "Main_Panel_Temp", 'name' => "MainPanel"),
                        "7" => array('id' => "Bearing_Temp", 'name' => "Bearing")
                        
                    );
                    break;
                case 4:
                     $templist = array(
                        "0" => array('id' => "Ambient_Temp", 'name' => "Ambient"),
                        "1" => array('id' => "Nacel_Temp", 'name' => "Nacel"),
                        "2" => array('id' => "Gear_Temp", 'name' => "Gear"),
                        "3" => array('id' => "Gen1_Temp", 'name' => "Gen1"),
                        "4" => array('id' => "Control_Temp", 'name' => "Control"),
                        "5" => array('id' => "Hydraulic_Temp", 'name' => "Hydraulic"),
                        "6" => array('id' => "Bearing_Temp", 'name' => "Bearing")
                        
                    );
                    break;
                case 6:
                     $templist = array(
                        "0" => array('id' => "Ambient_Temp", 'name' => "Ambient"),
                        "1" => array('id' => "Nacel_Temp", 'name' => "Nacel"),
                        "2" => array('id' => "Gear_Temp", 'name' => "Gear"),
                        "3" => array('id' => "Gen1_Temp", 'name' => "Gen1"),
                        "4" => array('id' => "Control_Temp", 'name' => "Control"),
                        "5" => array('id' => "Hydraulic_Temp", 'name' => "Hydraulic"),
                        "6" => array('id' => "Bearing_Temp", 'name' => "Bearing")
                        
                    );
                    break;
                case 7:
                    $templist = array(
                        "0" => array('id' => "Control_Panel_Temp", 'name' => "Control Panel"),
                        "1" => array('id' => "Gear_Bearing1_Temp", 'name' => "Gear Bearing1"),
                        "2" => array('id' => "Gear_Bearing2_Temp", 'name' => "Gear Bearing2"),
                        "3" => array('id' => "Gear_Box_Oil_Temp", 'name' => "Gear Box Oil"),
                        "4" => array('id' => "Gen_Winding1_Temp", 'name' => "Gen Winding1"),
                        "5" => array('id' => "Gen_Winding2_Temp", 'name' => "Gen Winding2"),
                        "6" => array('id' => "Gen_DE_Bearing_Temp", 'name' => "Gen DE Bearing"),
                        "7" => array('id' => "Gen_DE_NDE_Bearing_Temp", 'name' => "Gen DE NDE Bearing "),
                        "8" => array('id' => "Nacelle_Temp", 'name' => "Nacelle"),
                        "9" => array('id' => "Main_Bearing_Temp", 'name' => "Main Bearing"),
                        "10" => array('id' => "Transformer_Oil_Temp", 'name' => "Transformer Oil"),
                        
                    );

                    break;
                case 8:
                    $templist = array(
                        "0" => array('id' => "Control_Panel_Temp", 'name' => "Control Panel"),
                        "1" => array('id' => "Gear_Bearing1_Temp", 'name' => "Gear Bearing1"),
                        "2" => array('id' => "Gear_Bearing2_Temp", 'name' => "Gear Bearing2"),
                        "3" => array('id' => "Gear_Box_Oil_Temp", 'name' => "Gear Box Oil"),
                        "4" => array('id' => "Gen_Winding1_Temp", 'name' => "Gen Winding1"),
                        "5" => array('id' => "Gen_Winding2_Temp", 'name' => "Gen Winding2"),
                        
                        
                    );
                    break;
                case 10:
                      $templist = array(
                        "0" => array('id' => "Ambient_Temp", 'name' => "Ambient"),
                        "1" => array('id' => "Hydraulic_Temp", 'name' => "Hydraulic"),
                        "2" => array('id' => "Gear_Temp", 'name' => "Gear"),
                        "3" => array('id' => "Gen1_Temp", 'name' => "Gen1"),
                        "4" => array('id' => "Gen2_Temp", 'name' => "Gen2")
                    );
                    break;
            }






            $message = $templist;
        } else {
            $message = array('invalid' => validation_errors());
        }

        echo json_encode($message);
    }*/
    
    
    function ajax_tempgraph() {
        $this->form_validation->set_rules('start_Date', 'startDate', 'required');
        $this->form_validation->set_rules('dev', 'dev', 'required');
      //  $this->form_validation->set_rules('temp', 'temp', 'required');
        $data = array();
        if ($this->form_validation->run() == TRUE) {
			//echo "hi";
            $formvalues = $this->input->post();
            header('Content-Type: application/json');
             $date = $formvalues['start_Date'];
            $dev = $formvalues['dev'];
          $dev_array = explode("x",$dev);
          //print_r($dev_array);
           $temp_graph  = $this->Common_model->fetch_tempvani($dev_array[1], $dev_array[0],$dev_array[2],$date);   
/*foreach ($temp_graph as $key => $value) {
                    print_r($value['Ambient_Temp']);//die;
                   
                }*/
				$data['FType'] = $dev_array[0];
             $data['temp_graph'] = json_encode($temp_graph);
           // $temp_graph = json_encode($data);
           //$i = 0;
            $message = $data;
        } else {
			
            $message = array('invalid' => validation_errors());
        }

        echo json_encode($message);
    }
	
	
}

?>
