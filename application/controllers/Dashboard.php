<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public $sessionUsername;
    public $sessionDbname;
	public $sessionUserId;

    function __construct() {
        parent::__construct();


        $this->load->helper(array('url', 'language'));
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->sessionUsername = $this->session->userdata('username');
		$this->sessionUserId = $this->session->userdata('user_type_id');
        $this->sessionDbname = $this->session->userdata('db_name');
        if (empty($this->sessionUsername)) {
            redirect('');
        }
        $this->load->model('common/Common_model');
        $this->load->view('layout/header');
        //  $this->db2 = $this->load->database($this->Common_model->set_db_config(), TRUE);
    }

    function index() {
        $type_list = $this->Common_model->getDeviceList(); //get devic type list
       
        // echo "<pre>";print_r($type_list); exit;
        $data['green'] = $data['blue'] = $data['red'] = $data['gray'] = array();
        $total_device = count($type_list);
        $data['response'] = $data['devicelist_perf'] = array();
        $total_count = count($type_list);
        if (!empty($type_list)) {

            $green = $blue = $red = $gray = $green_status = $blue_status = $red_status = $gray_status = array();
          $green_array = array('green');
		   $blue_array = array('blue');
		   $gray_array = array('grey');
            $getImei = $currentImei = array();
			$color_val = $this->Common_model->colorCurrentDatavani($type_list);
				
            foreach ($color_val as $val) {
				
                if (in_array($val['color_val'], $green_array)) {

                    $green[] = $val;
                    $green_status[] = ['color_val' => $val['color_val'], 'imei' => $val['imei'], 'col' => $val['col']];
                } else if (in_array($val['color_val'], $blue_array)) {

                    $blue[] = $val;
                    $blue_status[] = ['color_val' => $val['color_val'], 'imei' => $val['imei'], 'col' => $val['col']];
                } else if (in_array($val['color_val'], $gray_array)) {

                    $gray[] = $val;
                    $gray_status[] = ['color_val' => $val['color_val'], 'imei' => $val['imei'], 'col' => $val['col']];
                } else {
                    $red[] = $val;
                    $red_status[] = ['color_val' => $val['color_val'], 'imei' => $val['imei'], 'col' => $val['col']];
                }
            }
             //print_r($IMEI);die;

            $data['response']['green'] = array('count' => count($green), 'name' => 'WTG RUN', 'total' => $total_count, 'status' => $green_status);
            $data['response']['red'] = array('count' => count($red), 'name' => 'WTG ERROR', 'total' => $total_count, 'status' => $red_status);
            $data['response']['blue'] = array('count' => count($blue), 'name' => 'WTG GRID DROP', 'total' => $total_count, 'status' => $blue_status);
            $data['response']['gray'] = array('count' => count($gray), 'name' => 'WTG SCADA OFF', 'total' => $total_count, 'status' => $gray_status);
        }
       // print_r($data['response']);die;
        if (!empty($_REQUEST['status'])) {
            $status_details = array();
            $status_det = array();
            $key_status = $_REQUEST['status'];
            $status_det = $data['response'][$key_status]['status'];
          // print_r($status_det);die;
            $status_details = $this->Common_model->getStatusDetailsvani($status_det);

            $val['color'] = $key_status;
            $val['status_det'] = $status_details;
           //print_r($val['status_det']);
          //die;
            $this->load->view('dashboard/status', $val);
        } else {
            $this->load->view('dashboard/index', $data);
        }
    }

   /* function device_view() {
        $device_info = $top_data = $footer_data = $footer = $device_data = array();
        $avg_speed = $event_log = $power_curve = $list = array();
        if (!empty($_REQUEST['d'])) {
            $list = $this->Common_model->get_device_list_by_given_imei($_REQUEST['d']);
            if (!empty($list)) {
                $date = date('Y-m-d'); //current date '2018-08-14'; //
                $search = array('order' => 'DESC', 'start_date' => $date, 'end_date' => $date);
                $search1 = array('order' => 'DESC', 'start_date' => $date, 'end_date' => $date, 'limit' => 5);
                $device_info = (array) $this->Common_model->get_device_data_details($list['Format_Type'], $list['IMEI'], $search);
                $error_info = (array) $this->Common_model->get_error_data_Info($list['Format_Type'], $list['IMEI'], $search1);

                if (!empty($device_info)) {
                    $device_info['Device_Name'] = $list['Device_Name'];
                    $device_info['LOC_No'] = $list['LOC_No'];
                    $device_info['capacity'] = $list['capacity'];
                    $device_info['Connect_Feeder'] = $list['Connect_Feeder'];
                }

                if (!empty($error_info)) {
                    foreach ($error_info as $info) {
                        $event_log[] = array(
                            'Date_S' => !empty($info['Date_S']) ? date('d-m-Y', strtotime($info['Date_S'])) : '---',
                            'Time_S' => $info['Time_S'],
                            'Device_Name' => $list['Device_Name'],
                            'Description' => $info['Status'],
                            'datetime' => $info['Date_S'] . ' ' . $info['Time_S']
                        );
                    }
                }

                $search_info = array('order' => 'ASC', 'start_date' => $date, 'end_date' => $date);
                $val = $this->Common_model->get_device_data_Info($list['Format_Type'], $list['IMEI'], $search_info);

                if (!empty($val)) {
                    $j = 0;
                    $power_curve[0] = array('seriesname' => 'windspeed');
                    $power_curve[1] = array('seriesname' => 'power');
                    foreach ($val as $val_list) {
                        $windspeed = isset($val_list['Windspeed']) ? $val_list['Windspeed'] : '';
                        $power = isset($val_list['Power']) ? $val_list['Power'] : '';
                        $power_curve[0]['data'][$j]['value'] = $windspeed;
                        $power_curve[1]['data'][$j]['value'] = $power;
                        $j++;
                    }
                }

                $sdate = date('Y-m-01'); //current month start date '2018-08-01'; //
                $edate = date('Y-m-d'); //current date '2018-08-31'; //
                $search_avg = array('order' => 'ASC', 'start_date' => $sdate, 'end_date' => $edate);
                $speed_list = $this->Common_model->get_date_wise_device_data_Info($list['Format_Type'], $list['IMEI'], $search_avg);

                if (!empty($speed_list)) {
                    $j = 0;
                    foreach ($speed_list as $speed) {
                        $windspeed = isset($speed['Windspeed']) ? $speed['Windspeed'] : '';
                        $date_list = !empty($speed['Date_S']) ? date('d-m-Y', strtotime($speed['Date_S'])) : '---';
                        $avg_speed[$date_list] = $windspeed;
                        $j++;
                    }
                }
            }
        }

        $data['regions'] = $list;
        $data['live_status'] = $device_info;
        $data['event_log'] = $event_log;
        $data['power_curve'] = $power_curve;
        $data['avg_speed'] = $avg_speed;
        echo '<pre>';
        print_r($data);
        exit;
        $this->load->view('dashboard/device_view', $data);
    }

    function temp_analysis() {
        $device_list = $this->Common_model->get_region_site_list();


        $common_typelist = $this->Common_model->getDeviceList('', 1);
        if (!empty($common_typelist)) {

            $export_temp = array();

            $export_temp = $this->Common_model->calculate_temp($common_typelist);

            $export_temp = json_encode($export_temp);
        }


        $data['export_temp'] = json_encode($export_temp);

        $data['tempAna']['deviceList'] = $device_list;
        $this->load->view('dashboard/temp_analysis', $data);
    }*/

    function powercurve_analysis() {
        $device_list = $this->Common_model->get_region_site_list();
		$data['powCurve']['deviceList'] = $device_list;
        $this->load->view('dashboard/powercurve_analysis', $data);
    }

    function performance_analysis() {
        $device_list = $this->Common_model->get_region_site_list();


        $common_typelist = $this->Common_model->getDeviceList('', 1);


        $data['perfCurve']['deviceList'] = $device_list;
        //$this->load->view('dashboard/powercurve_analysis', $data);
        $this->load->view('dashboard/performance_analysis', $data);
    }

    function reports() {
        $device_list = $this->Common_model->get_region_site_list();

        $data['reports']['deviceList'] = $device_list;
        $this->load->view('dashboard/reports', $data);
    }
	
    public function device_details() {

        $data = array();
        $limit_geo = $limit_ele = $limit_temp = $limit_pro = 10;

        if (!empty($_REQUEST['id']) && !empty($_REQUEST['type'])) {

            if (!empty($_REQUEST['limit'])) {
                if (isset($_POST["geo_but"])) {
                    $limit_geo = $_REQUEST['limit'];
                } else if (isset($_POST["ele_but"])) {
                    $limit_ele = $_REQUEST['limit'];
                } else if (isset($_POST["temp_but"])) {
                    $limit_temp = $_REQUEST['limit'];
                } else if (isset($_POST["pro_but"])) {
                    $limit_pro = $_REQUEST['limit'];
                }
            }


            $status_det = array();
            $imei = $_REQUEST['id'];
            $type = $_REQUEST['type'];
            $date = date("Y-m-d");
            //echo $imei." ".$type;die;

            $devicedetails = $this->Common_model->getdeviceDetailsvani($imei, $type, $limit_geo);
          //  $electrical = $this->Common_model->getElectricalDetails($imei, $type, $limit_ele);
           // $temperature = $this->Common_model->getTemperatureDetails($imei, $type, $limit_temp);
           // $generation = $this->Common_model->getGenerationDetails($imei, $type, $limit_pro);
           // $device_name = $this->Common_model->commonDataFetching($imei, 'Device_Name');
			$currentdata = $this->Common_model->getcurrentDatavani($imei);
			$kwhtoday = $this->Common_model->kwhactive($type,$imei,'today');
			$kwhmonth = $this->Common_model->kwhactive($type,$imei,'month');
			$kwhyear = $this->Common_model->kwhactive($type,$imei,'year');
           /* $data['geography'] = $geography;
            $data['electrical'] = $electrical;
            $data['temperature'] = $temperature;
            $data['generation'] = $generation;*/
            $data['limit_geo'] = $limit_geo;
            $data['limit_ele'] = $limit_ele;
            $data['limit_temp'] = $limit_temp;
            $data['limit_pro'] = $limit_pro;
            $data['type'] = $type;
            $data['imei'] = $imei;
          //  $data['device_name'] = $device_name;
			$data['devicedetails'] = $devicedetails;
            $data['date'] = $date;
			$data['currentdata'] = $currentdata;
			$data['kwhtoday'] = $kwhtoday;
			$data['kwhmonth'] = $kwhmonth;
			$data['kwhyear'] = $kwhyear;


            // echo $imei;die;
         // print_r($kwhtoday);
          // die;
            $this->load->view('dashboard/device_details', $data);
        }
    }

   /* function power_windspeed_report() {
        $device_list = $this->Common_model->get_region_site_list();

        $data['reports']['deviceList'] = $device_list;
        $this->load->view('dashboard/power_windspeed_report', $data);
    }

    function pw_reportData() {
        $this->load->library("pagination");
        $this->load->library('session');
        $data = array();
        //  print_r($_POST);die; 

        if (isset($_POST["submit"])) {
            $config = array();
//            $s_date = $_POST["s_date"];
//            $e_date = $_POST["e_date"];
            $d_name = $_POST["d_name"];
            $s_date = date("Y-m-d", strtotime($_POST["s_date"]));
            $e_date = date("Y-m-d", strtotime($_POST["e_date"]));
            $newdata = array(
                'd_name' => $d_name,
                's_date' => $s_date,
                'e_date' => $e_date
            );

            $this->session->set_userdata($newdata);
            $basic = $this->Common_model->getbasicInfo($d_name);
            $config["base_url"] = base_url() . "dashboard/pw_reportData";
            $config["total_rows"] = $this->Common_model->getCountpwReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $s_date, $e_date);
            $config["per_page"] = 500;
            $config["uri_segment"] = 3;


            $this->pagination->initialize($config);
            //  print_r($this->uri->segment());die;

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data["links"] = $this->pagination->create_links();
            $data["d_name"] = $d_name;
            $data["s_date"] = $s_date;
            $data["e_date"] = $e_date;

            $data['pwreport'] = $this->Common_model->getpwReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $s_date, $e_date, $config["per_page"], $page);
            // print_r($data);die;
            $this->load->view('dashboard/pw_reportData', $data);
        } else {
            // print_r($_SESSION);die;
            $s_date = $this->session->userdata('s_date');
            $d_name = $this->session->userdata('d_name');
            $e_date = $this->session->userdata('e_date');

            $basic = $this->Common_model->getbasicInfo($d_name);
            $config["base_url"] = base_url() . "dashboard/pw_reportData";
            $config["total_rows"] = $this->Common_model->getCountpwReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $s_date, $e_date);
            $config["per_page"] = 500;
            $config["uri_segment"] = 3;


            $this->pagination->initialize($config);
            //  print_r($this->uri->segment());die;

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data["links"] = $this->pagination->create_links();
            $data["d_name"] = $d_name;
            $data["s_date"] = $s_date;
            $data["e_date"] = $e_date;

            $data['pwreport'] = $this->Common_model->getpwReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $s_date, $e_date, $config["per_page"], $page);
            // print_r($data);die;
            $this->load->view('dashboard/pw_reportData', $data);
        }
    }*/

    function performance_report() {
        $device_list = $this->Common_model->get_region_site_list();

        $data['reports']['deviceList'] = $device_list;
        $this->load->view('dashboard/performance_report', $data);
    }

    function perfomance_chart() {
        $common_typelist = $this->Common_model->getDeviceList('', 1);
        if (!empty($common_typelist)) {
            // $perf_chart = array();
            $device_perfomances = array();
            $data['devicelist_perf'] = array();
            foreach ($common_typelist as $list) {
                if (count($this->Common_model->device_perfomance($list->Format_Type, $list->IMEI, $list->Device_Name))) {
                    $data['devicelist_perf'][trim($list->Device_Name)][] = $this->Common_model->device_perfomance($list->Format_Type, $list->IMEI, $list->Device_Name);
				}
            }
			$data['Device_Name'] = $this->Common_model->getDevnameAll();
            // print_r($data['devicelist_perf']);die;
            $device_perfomances = json_encode($data['devicelist_perf']);
			$Device_Names = json_encode($data['Device_Name']);
        }
        $data['device_perfomance'] = json_encode($device_perfomances);
		$data['Dev_Name'] = json_encode($Device_Names);
        $this->load->view('dashboard/device_perfomance', $data);
    }

    function pw_curve() {
        //$device_list = $this->Common_model->get_region_site_list();
		$data = array();
		$pwgraphvalues = array();
		$pwhourgraphvalues = array();
		$d_name = $_REQUEST['d_name'];
		$data['s_date'] = $_REQUEST['s_date'];
		$e_date = $_REQUEST['e_date'];
        $basic = $this->Common_model->getbasicInfoimei($d_name);
		$data['device_name'] = $basic[0]['Device_Name'];
		$data['pwgraphvalues'] = $this->Common_model->getPowerWindgraph($basic[0]['Format_Type'], $basic[0]['IMEI'], $data['s_date'], $e_date);
		$data['pwhourgraphvalues'] = $this->Common_model->getHourPowerWindgraph($basic[0]['Format_Type'], $basic[0]['IMEI'], $data['s_date'], $e_date);
		$namedev = $data['device_name'];
		$de_name = json_encode($data['device_name']);
		$st_date = json_encode($data['s_date']);
		$pwgraph = json_encode($data['pwgraphvalues']);
		$pwhourgraph = json_encode($data['pwhourgraphvalues']);
		$data['dev_name'] = json_encode($de_name);
		$data['sdate'] = json_encode($st_date);
		$data['pwgraphval'] = json_encode($pwgraph);
		$data['pwhourgraphval'] = json_encode($pwhourgraph);
        $this->load->view('dashboard/pw_curve',$data);
		//print_r($data);
    }

	/*function export_gad() {


        $this->load->view('dashboard/export_gad');
    }*/

    function park_view() {
        $data = array();
       $data['parkview'] = $this->Common_model->getParkviewDatavani();
       /* uasort($data['parkview'], function($a, $b) {
            return strcmp($a['device_order'], $b['device_order']);
        });*/

        //print_r($data);die;
        $this->load->view('dashboard/park_view', $data);
    }

    function tempAnalysis() {
        $device_list = $this->Common_model->get_region_site_list();


        $data['tempAna']['deviceList'] = $device_list;
        $this->load->view('dashboard/tempAnalysis', $data);
    }

}

?>
