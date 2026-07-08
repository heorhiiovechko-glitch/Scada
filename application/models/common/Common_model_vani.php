<?php

Class Common_model extends CI_Model {

    //public $db2;
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->db2 = $this->load->database($this->set_db_config(), TRUE);
//        if ($this->session->userdata('username') != '') {
//            $this->set_session_device_list();
//        }
    }

    function set_db_config() {
        $config['hostname'] = DB_HOST;
        $config['username'] = DB_USERNAME;
        $config['password'] = DB_PASSWORD;
        $config['database'] = $this->session->userdata('db_name');
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';
        return $config;
    }

    function set_session_device_list() {
        $type_list = $this->getDeviceList(); //get devic type list
        $data = array();
        $total_device = count($type_list);
        if (!empty($type_list)) {
            $total_count = 0;
            $count = 0;
            $i = 0;
            $green = $blue = $red = $gray = array();
            $avgWindSpeed = $powerSpeed = $pat_gen_list = $pat_gen_first = $pat_gen_last = array();
            foreach ($type_list as $list) {
                $date = date('Y-m-d'); //current date'2018-08-14'; //
                $search_info = array('order' => 'DESC', 'start_date' => $date, 'end_date' => $date);
                $device_list = $this->get_device_data_details($list->Format_Type, $list->IMEI, $search_info);

                if (!empty($device_list)) {
                    // echo "<pre>"; print_r($device_list); exit;
                    $powerSpeed[] = (float) $device_list->Power;
                    $avgWindSpeed[] = (float) $device_list->Windspeed;
                    $count = $count + 1;
                }

                $search = array('order' => 'ASC', 'start_date' => $date, 'end_date' => $date);
                $search1 = array('order' => 'DESC', 'start_date' => $date, 'end_date' => $date);
                $pat_gen_first = $this->get_device_data_details($list->Format_Type, '', $search);
                $pat_gen_last = $this->get_device_data_details($list->Format_Type, '', $search1);

                if (!empty($pat_gen_first) && !empty($pat_gen_last)) {
                    $pat_gen_list[] = $pat_gen_last->PAT_Gen1 - $pat_gen_first->PAT_Gen1;
                }
            }

            $data['avgWindSpeed'] = $avgWindSpeed;
            $data['powerSpeed'] = $powerSpeed;
            $data['patGen'] = $pat_gen_list;
            $sum_avg = array_sum($avgWindSpeed);
            $sum_power = array_sum($powerSpeed);
            $sum_gen = array_sum($pat_gen_list);
            $data['avgWindSpeedSum'] = !empty($sum_avg) ? number_format(($sum_avg / $count), 2) : 0;
            $data['powerSpeedSum'] = !empty($sum_power) ? number_format(($sum_power / 1000), 2) : 0;
            $data['patGenSum'] = !empty($sum_gen) ? number_format($sum_gen, 2) : 0;
            $this->session->set_userdata($data);
        }
        return $data;
    }

    function get_device_details($type, $imei) {
        $val = array();
        $device_data = $this->get_device_data_details($type, $imei);
        $error_data = $this->get_error_data_details($type, $imei);
        if (!empty($device_data) && !empty($error_data)) {
            $device_time = strtotime($device_data->Date_S . ' ' . $device_data->Time_S);
            $error_time = strtotime($error_data->Date_S . ' ' . $error_data->Time_S);

            $val = $error_data;
            if ($device_time > $error_time) {
                $val = $device_data;
            }
        }
        return $val;
    }

    function get_device_data_details($type, $imei, $search = array()) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $this->db2->select('*')->from('device_data' . $type);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        if (!empty($search['order'])) {
            $this->db2->order_by('Record_Index', $search['order']);
            //$limit = (!empty($search['limit'])?$search['limit']:1);
            $this->db2->limit(1);
        }

        if (!empty($search['start_date']) && !empty($search['end_date'])) {
            $this->db2->where("DATE_FORMAT(Date_S,'%y-%m-%d') BETWEEN DATE('" . $search['start_date'] . "') AND DATE('" . $search['end_date'] . "') ");
        }

        $query = $this->db2->get();
        // if(!empty($search['start_date']) && !empty($search['end_date']))
        // {
        // echo $this->db2->last_query();
        // }
        return $query->row();
    }

    function get_error_data_details($type, $imei) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $this->db2->select('*')->from('error_data' . $type);
        $this->db2->where('IMEI', $imei);
        $this->db2->order_by('Record_Index', 'DESC');
        //	$this->db2->limit(1);
        $query = $this->db2->get();
        //echo $this->db2->last_query();
        return $query->row();
    }

    function getDeviceList($device_name = '', $asc = '') {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');

        $this->db->select('IMEI, Device_Name, Format_Type , (SELECT  count(*) as cnt FROM `device_register` WHERE `Account_ID` = ' . $Account_ID . ') as cnt')
                ->where('Account_ID', $Account_ID);
        if (!empty($device_name)) {
            $this->db->where_in('Device_Name', $device_name);
        }
        if ($asc == 1) {
            $this->db->order_by('LENGTH(Device_Name)', 'ASC');
            $this->db->order_by('Device_Name', 'ASC');
        }

        $query = $this->db->get('device_register');
        // echo $this->db->last_query();die;
        return $query->result();
    }

    function get_region_site_list() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');

        $this->db->select('Account_ID,Site_Location,Region, Device_Name, Format_Type,IMEI, LOC_No, capacity, Connect_Feeder')
                ->where('Account_ID', $Account_ID)
                ->where("Region!=''");

        //	->group_by('Region,Site_Location');
        $query = $this->db->get('device_register');
        return $query->result_array();
    }

    function get_device_list_by_given_imei($imei = '') {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');

        $this->db->select('Site_Location,Region, Device_Name, Format_Type,IMEI, LOC_No, capacity, Connect_Feeder')
                ->where('Account_ID', $Account_ID)
                ->where("Region!=''");
        if (!empty($imei)) {
            $this->db->where_in('IMEI', $imei);
        }
        //	->group_by('Region,Site_Location');
        $query = $this->db->get('device_register');
        return $query->row_array();
    }

    function get_device_data_Info($type, $imei, $search = array()) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $this->db2->select('*')->from('device_data' . $type);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        if (!empty($search['order'])) {
            $this->db2->order_by('Record_Index', $search['order']);
        }

        if (!empty($search['start_date']) && !empty($search['end_date'])) {
            $this->db2->where("DATE_FORMAT(Date_S,'%y-%m-%d') BETWEEN DATE('" . $search['start_date'] . "') AND DATE('" . $search['end_date'] . "') ");
        }

        $query = $this->db2->get();
        // if(!empty($search['start_date']) && !empty($search['end_date']))
        // {
        // echo $this->db2->last_query(); exit;
        // }
        return $query->result_array();
    }

    function get_date_wise_device_data_Info($type, $imei, $search = array()) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $this->db2->select('Date_S, sum(Windspeed) as Windspeed')->from('device_data' . $type);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        if (!empty($search['order'])) {
            $this->db2->order_by('Record_Index', $search['order']);
        }

        if (!empty($search['start_date']) && !empty($search['end_date'])) {
            $this->db2->where("DATE_FORMAT(Date_S,'%y-%m-%d') BETWEEN DATE('" . $search['start_date'] . "') AND DATE('" . $search['end_date'] . "') ");
        }
        $this->db2->group_by('Date_S');
        $query = $this->db2->get();
        // if(!empty($search['start_date']) && !empty($search['end_date']))
        // {
        //  echo $this->db2->last_query(); exit;
        // }
        return $query->result_array();
    }

    function get_error_data_Info($type, $imei, $search = array()) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $this->db2->select('*')->from('error_data' . $type);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        if (!empty($search['order'])) {
            $this->db2->order_by('Record_Index', $search['order']);
        }

        if (!empty($search['limit'])) {
            $this->db2->limit($search['limit']);
        }

        if (!empty($search['start_date']) && !empty($search['end_date'])) {
            $this->db2->where("DATE_FORMAT(Date_S,'%y-%m-%d') BETWEEN DATE('" . $search['start_date'] . "') AND DATE('" . $search['end_date'] . "') ");
        }

        $query = $this->db2->get();
        // if(!empty($search['start_date']) && !empty($search['end_date']))
        // {
        //echo $this->db2->last_query();
        // }
        return $query->result_array();
    }

    function get_dashboard_device_list() {
        $type_list = $this->getDeviceList(); //get devic type list
        $data = array();
        $total_device = count($type_list);
        if (!empty($type_list)) {
            $total_count = 0;
            $count = 0;
            $i = 0;
            $green = $blue = $red = $gray = array();
            $avgWindSpeed = $powerSpeed = $pat_gen_list = $pat_gen_first = $pat_gen_last = array();
            foreach ($type_list as $list) {
                $date = '2018-08-14'; //date('Y-m-d');
                $search_info = array('start_date' => $date, 'end_date' => $date);
                $device_list = $this->get_device_data_Info($list->Format_Type, $list->IMEI, $search_info);

                if (!empty($device_list)) {
                    foreach ($device_list as $key => $value) {
                        $hour = date('H', strtotime($value['Time_S']));
                        $powerSpeed[$hour][] = $value['Power'];
                        $avgWindSpeed[$hour][] = $value['Windspeed'];
                    }
                }
                /* $date = '2018-08-14';//date('Y-m-d');
                  $search = array('start_date'=>$date,'end_date'=>$date);
                  $search1 = array('order' =>'DESC','start_date'=>$date,'end_date'=>$date);
                  $pat_gen_first	=	$this->get_device_data_Info( $list->Format_Type, '',$search);
                  $pat_gen_last	=	$this->get_device_data_Info( $list->Format_Type, '',$search1 );

                  if(!empty($pat_gen_first) && !empty($pat_gen_last) )
                  {
                  $pat_gen_list[] =	$pat_gen_last->PAT_Gen1-$pat_gen_first->PAT_Gen1;
                  } */
            }
            //	echo '<pre>';print_r($powerSpeed);exit;
            if (!empty($avgWindSpeed)) {
                foreach ($avgWindSpeed as $key => $value) {
                    $data['avgWindSpeedSum'][$key] = number_format((array_sum($value) / count($value)), 2);
                }
            }
            if (!empty($powerSpeed)) {
                foreach ($powerSpeed as $key1 => $value1) {
                    $data['powerSpeedSum'][$key1] = number_format((array_sum($value1) / 1000), 2);
                }
            }
        }
        //echo '<pre>';print_r($data);exit;
        return $data;
    }

    function build_sorter($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }

    public function sort_by_array($array) {

        //usort($array, function (int $a, int $b) { return -($a <=> $b); });
        usort($array, $this->build_sorter('datetime'));
        return $array;
    }

    function calculate_windspeed($type_list) {
        $full_device['avg_windspeed'] = array();
      //  $full_device['gen'] = array();
        $temp_device = array();
        $avg_windspeed = array();
       // $gen = array();

        foreach ($type_list as $list) {
            $val = $this->Common_model->get_windspeed($list->Format_Type, $list->IMEI, $list->Device_Name);
			// $gval = $this->Common_model->get_currentgad($list->Format_Type, $list->IMEI, $list->Device_Name);
			// if ($val) {
            $dev = ['device_name' => $list->Device_Name, 'avg_windspeed' => $val['avg_windspeed']];
			// }
			/* if ($gval) {
            $device = ['device_name' => $list->Device_Name, 'gen' => $gval['gen']];
			 }*/
            array_push($full_device['avg_windspeed'], $dev);
           // array_push($full_device['gen'], $device);
        }
       // print_r($full_device);die;
        return $full_device;
    }

    function get_windspeed($type, $imei, $device_name) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
        //$date = '2019-04-13';
        $this->db2->select('Date_S,AVG( `Windspeed` ) as avg_windspeed')->from('device_data' . $type);
        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
         //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $row['device_name'] = $device_name;
            if ($row['avg_windspeed'] == 'null' || $row['avg_windspeed'] == '') {
                $row['avg_windspeed'] = 0;
            } else {
                $row['avg_windspeed'] = round($row['avg_windspeed'], 2);
            }
           /* if ($row['avg_power'] == 'null' || $row['avg_power'] == '') {
                $row['avg_power'] = 0;
            } else {
                $row['avg_power'] = round($row['avg_power'], 2);
            }*/
            $data = $row;
        }
        return $data;
    }
	
	function calculate_currentgad($type_list) {
        
       $full_device['gen'] = array();
        $gen = array();

        foreach ($type_list as $list) {
           $gval = $this->Common_model->get_currentgad($list->Format_Type, $list->IMEI, $list->Device_Name);
			 // if ($gval) {
            $device = ['device_name' => $list->Device_Name, 'gen' => $gval['gen']];
			 //}
         array_push($full_device['gen'], $device);
        }
       // print_r($full_device);die;
        return $full_device;
    }

	
	function get_currentgad($type, $imei, $device_name) {       
        $datagad = array();
        $date = date("Y-m-d");
        $this->db->select('date_s,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max')->from('current_data');
        $this->db->where('date_s', $date);
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $query = $this->db->get();
       // echo $this->db->last_query();die;
        foreach ($query->result_array() as $row) {
            $row['device_name'] = $device_name;
			if ($type == 1 || $type == 6) {
				$gen = $row['Gen2_Max'] - $row['Gen2_Min'];
			} 
			elseif ($type == 3 || $type == 10 || $type == 7 || $type == 8) {
				$gen = $row['Gen1_Max'] - $row['Gen1_Min'];
			}
			else {
				$gen = (($row['Gen2_Max'] - $row['Gen2_Min']) + ($row['Gen1_Max'] - $row['Gen1_Min']));
			}
			
            if ($row['gen'] == 'null' || $gen == '' || $gen < '0'  ) {
                $gen = 0;
            } else {
                $gen = round($gen, 2);
            }
          $datagad = $gen;
        }
		//print_r($data); die;
        return $datagad;

    }


    function calculate_power($type_list) {
        $full_device = array();
        $temp_device = array();

        foreach ($type_list as $list) {
            $val = $this->Common_model->get_power($list->Format_Type, $list->IMEI, $list->Device_Name);
            array_push($full_device, $val);
        }
        //print_r($full_device);die;
        return $full_device;
    }

    function get_power($type, $imei, $device_name) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
        //$date = '2019-04-13';

        $this->db2->select('Date_S,AVG( `Power` ) as avg_power ,count(`Record_Index`) as count')->from('device_data' . $type);
        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
        foreach ($query->result_array() as $row) {
            $row['device_name'] = $device_name;
            if ($row['avg_power'] == 'null' || $row['avg_power'] == '') {
                $row['avg_power'] = 0;
            } else {
                $row['avg_power'] = round($row['avg_power'], 2);
            }
            $data = $row;
        }
        return $data;
    }

    function calculate_gad($gad_typelist) {

        $full_gad_device = array();
        $val = array();

        foreach ($gad_typelist as $list) {
            $initial_gad_val = $this->Common_model->get_gad($list->Format_Type, $list->IMEI, 1);
            $final_gad_val = $this->Common_model->get_gad($list->Format_Type, $list->IMEI, 2);

            if ($initial_gad_val > $final_gad_val) {
                $gad = $initial_gad_val - $final_gad_val;
            } else {
                $gad = $final_gad_val - $initial_gad_val;
            }

            $text = "";
            switch ($list->Format_Type) {
                case 1:
                    if ($gad > 15000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 2:
                    if ($gad > 6000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 3:
                    if ($gad > 15000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 4:
                    if ($gad > 15000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 6:
                    if ($gad > 15000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 7:
                    if ($gad > 15000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 8:
                    if ($gad > 6000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 10:
                    if ($gad > 6000) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
            }



            $val = ['gad' => $gad, 'device_name' => $list->Device_Name, 'text' => $text];

            array_push($full_gad_device, $val);
        }
//        print_r($full_gad_device);
//       die;
        return $full_gad_device;
    }

    public function get_gad($type, $imei, $no) {
        /*
         * calculation difference between time period between 00:00 to 23:59
         * Type 2 & 4 consider both gen1 & gen2 
         * Type 1 & 6 consider gen2
         * Type 3 & 10 consider production_total
         */
        $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);

        $data = array();
        $date = date("Y-m-d");
        // $date = '2019-04-13';
        $gad_gen = 0;

        if ($dev_type == 2 || $dev_type == 4) {
            $this->db2->select('PAT_Gen1 as gad_gen1,PAT_Gen2 as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else if ($dev_type == 6 || $dev_type == 1) {
            $this->db2->select('PAT_Gen2 as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else if ($dev_type == 8 || $dev_type == 7) {
            $this->db2->select('Kwh_Positive as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else {
            $this->db2->select('Production_Total as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        }
        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        if ($no == 1) {
            $this->db2->order_by('Time_S', 'ASC');
        } else {
            $this->db2->order_by('Time_S', 'DESC');
        }
        $this->db2->limit(1);
        $query = $this->db2->get();
//       echo $this->db2->last_query();
//        die;
        foreach ($query->result_array() as $row) {
            if ($dev_type == 2 || $dev_type == 4) {
                $row['gad_gen'] = $row['gad_gen1'] + $row['gad_gen'];
            }
            $data = $row;
        }

        if (isset($data['gad_gen'])) {
            $gad_gen = $data['gad_gen'];
        }

        //print_r($data);die;
        return $gad_gen;
    }

    function device_perfomance($type, $imei, $dname) {
        $error_device_perfomance = array();
        $data_device_perf = array();

        $error_device_perfomance = $this->Common_model->get_device_perfomance($type, $imei, $dname, '1');
        $data_device_perf = $this->Common_model->get_device_perfomance($type, $imei, $dname, '2');
        foreach ($data_device_perf as $data) {

            array_push($error_device_perfomance, $data);
        }
//        print_r($error_device_perfomance);
//        die;

        return $error_device_perfomance;
    }

    function get_device_perfomance($type, $imei, $dname, $no) {
        //skip for format type 1

        $green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running', 'Power Up', '');
        $blue_array = array('GRIDDROP', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop', 'GridD?ox', 'GridD?op', 'GvidDrop');
        $orange_array = array('FreeWheeling', 'Freewheeling', 'pause: checking wind duration', 'pause:Checking wind duration', 'pause: Checking wind duration', 'Pause  : Checking wind direction', 'FreewheelingG1', 'FreewheelingG2', 'FreeWheelingG1', 'FreeWheelingG2');

        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
        // $date = '2019-04-13';
        if ($no == 1) {
            $this->db2->select('Date_S,Time_S,Status')->from('error_data' . $type);
        } else {
            $this->db2->select('Date_S,Time_S,Status')->from('device_data' . $type);
        }
        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
        // echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $row['device_name'] = $dname;
            $row['y'] = 0;
            // $row['hr'] = round($row['Time_S']);
            if (in_array($row['Status'], $green_array)) {
                $row['colour'] = '#228B22';
            } elseif (in_array($row['Status'], $blue_array)) {
                $row['colour'] = '#0101DF';
            } elseif (in_array($row['Status'], $orange_array)) {
                $row['colour'] = '#FFA500';
            } else {
                $row['colour'] = '#FF0000';
            }
            $data[] = $row;
        }

        return $data;
    }

    public function get_status_machine($type = "", $imei = "", $dev_name = "") {


        $result = $val = $val1 = $data = array();

        date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d");
        // $date = '2019-04-13';
        $min = date("H:i");
        $min = substr_replace($min, "", -1);
        date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d");
        //echo $min;die;
        $val = $this->Common_model->get_device_status($type, $imei, $dev_name, $date, $min, 1);
        $val1 = $this->Common_model->get_device_status($type, $imei, $dev_name, $date, $min, 2);

        if (empty($val) && empty($val1)) {
            $date = date("Y-m-d");
            // $date = '2019-04-13';
            $min = date("H:i", strtotime("-15 minutes"));
            $min = substr_replace($min, "", -1);
            $val = $this->Common_model->get_device_status($type, $imei, $dev_name, $date, $min, 1);
            $val1 = $this->Common_model->get_device_status($type, $imei, $dev_name, $date, $min, 2);

            if ($val && $val1) {

                foreach ($val as $key => $value) {
                    //print_r($value['Date_S']);die;
                    $error_time = strtotime($value['Date_S'] . ' ' . $value['Time_S']);
                }


                foreach ($val1 as $key => $value) {

                    $device_time = strtotime($value['Date_S'] . ' ' . $value['Time_S']);
                }
                if ($error_time > $device_time) {
                    $result = $val;
                } else {
                    $result = $val1;
                }
            } else if ($val) {
                $result = $val;
            } else if ($val1) {
                $result = $val1;
            } else {
                $wind_speed = $this->Common_model->getCurrentData($type, $imei, 'Windspeed');

                $power = $this->Common_model->getCurrentData($type, $imei, 'Power');

                $state = $this->Common_model->commonDataFetching($imei, 'State');
                $site = $this->Common_model->commonDataFetching($imei, 'Site_Location');
                $htsc = $this->Common_model->commonDataFetching($imei, 'HTSC_No');
                $region = $this->Common_model->commonDataFetching($imei, 'Region');
                $initial_gad_val = $this->Common_model->get_gad($type, $imei, 1);
                $final_gad_val = $this->Common_model->get_gad($type, $imei, 2);
                $current_status = $this->Common_model->getCurrentData($type, $imei, 'Status');

                if ($initial_gad_val > $final_gad_val) {
                    $gad = $initial_gad_val - $final_gad_val;
                } else {
                    $gad = $final_gad_val - $initial_gad_val;
                }

                switch ($type) {
                    case 1:
                        if ($gad > 15000) {
                            $gad = 0;
                        }
                        break;
                    case 2:
                        if ($gad > 6000) {
                            $gad = 0;
                        }
                        break;
                    case 3:
                        if ($gad > 15000) {
                            $gad = 0;
                        }
                        break;
                    case 4:
                        if ($gad > 15000) {
                            $gad = 0;
                        }
                        break;
                    case 6:
                        if ($gad > 15000) {
                            $gad = 0;
                        }
                        break;
                    case 7:
                        if ($gad > 15000) {
                            $gad = 0;
                        }
                        break;
                    case 8:
                        if ($gad > 6000) {
                            $gad = 0;
                        }
                        break;
                    case 10:
                        if ($gad > 6000) {
                            $gad = 0;
                        }
                        break;
                }
                $date_gray = date("Y-m-d");
                $time_gray = date("H:i");


                $data[] = ['Date_S' => $date_gray, 'Time_S' => $time_gray, 'IMEI' => '', 'dev_name' => $dev_name, 'Status' => 'grey', 'wind_speed' => $wind_speed, 'power' => $power, 'state' => $state, 'site' => $site, 'htsc' => $htsc, 'region' => $region, 'gad' => $gad, 'imei' => $imei, 'type' => $type, 'current_status' => $current_status];
                $result = $data;
            }
        } else if ($val && $val1) {

            foreach ($val as $key => $value) {

                $error_time = strtotime($value['Date_S'] . ' ' . $value['Time_S']);
            }


            foreach ($val1 as $key => $value) {

                $device_time = strtotime($value['Date_S'] . ' ' . $value['Time_S']);
            }
            if ($error_time > $device_time) {
                $result = $val;
            } else {
                $result = $val1;
            }
        } else if ($val) {
            $result = $val;
        } else {
            $result = $val1;
        }

        return $result;
    }

    public function get_device_status($type, $imei, $dev_name, $date, $min, $no) {
        $copy_type = $type;

        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        if ($no == 1) {
            $this->db2->select('Date_S,Time_S,IMEI,Status')->from('error_data' . $type);
            $this->db2->where('Date_S', $date);
            $this->db2->like('Time_S', $min, 'after');
        } else {
            $this->db2->select('Date_S,Time_S,IMEI,Status')->from('device_data' . $type);
            $this->db2->where('Date_S', $date);
            $this->db2->like('Time_S', $min, 'after');
        }
        $this->db2->order_by('Time_S', 'DESC');
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $this->db2->limit(1);
        $query = $this->db2->get();


        //echo $this->db2->last_query();
        //die;
        foreach ($query->result_array() as $row) {
            $row['dev_name'] = $dev_name;

            $row['wind_speed'] = $this->Common_model->getCurrentData($copy_type, $imei, 'Windspeed');

            $row['power'] = $this->Common_model->getCurrentData($copy_type, $imei, 'Power');

            $row['state'] = $this->Common_model->commonDataFetching($imei, 'State');
            $row['site'] = $this->Common_model->commonDataFetching($imei, 'Site_Location');
            $row['htsc'] = $this->Common_model->commonDataFetching($imei, 'HTSC_No');
            $row['region'] = $this->Common_model->commonDataFetching($imei, 'Region');
            $row['imei'] = $imei;
            $row['type'] = $copy_type;
            $initial_gad_val = $this->Common_model->get_gad($copy_type, $imei, 1);
            $final_gad_val = $this->Common_model->get_gad($copy_type, $imei, 2);

            if ($initial_gad_val > $final_gad_val) {
                $gad = $initial_gad_val - $final_gad_val;
            } else {
                $gad = $final_gad_val - $initial_gad_val;
            }

            switch ($copy_type) {
                case 1:
                    if ($gad > 15000) {
                        $gad = 0;
                    }
                    break;
                case 2:
                    if ($gad > 6000) {
                        $gad = 0;
                    }
                    break;
                case 3:
                    if ($gad > 15000) {
                        $gad = 0;
                    }
                    break;
                case 4:
                    if ($gad > 15000) {
                        $gad = 0;
                    }
                    break;
                case 6:
                    if ($gad > 15000) {
                        $gad = 0;
                    }
                    break;
                case 7:
                    if ($gad > 15000) {
                        $gad = 0;
                    }
                    break;
                case 8:
                    if ($gad > 6000) {
                        $gad = 0;
                    }
                    break;
                case 10:
                    if ($gad > 6000) {
                        $gad = 0;
                    }
                    break;
            }
            $row['gad'] = $gad;
            $data[] = $row;
        }

//        print_r($data);
//        die;

        return $data;
    }

    function calculate_temp($common_typelist) {
        $full_temp = array();
        $val = array();
        foreach ($common_typelist as $list) {
            $val = $this->Common_model->get_temp($list->Format_Type, $list->IMEI, $list->Device_Name);
        }
        // print_r($val);die;
        return $full_temp;
    }

    function get_temp($type, $imei) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        //print_r($data);
        $date = date("Y-m-d");
        // $date = '2019-04-13';

        $this->db2->select('Date_S')->from('device_data' . $type);

        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
        //print_r($this->db2->last_query());die;
        foreach ($query->result_array() as $row) {

            $row['hr'] = round($row['Date_S']);
            $data = $row;
            //print_r($data);die;
        }
        return $data;
    }

    function calculate_powCurve($common_typelist) {
        $full_powCurve = array();
        $val = array();
        foreach ($common_typelist as $list) {
            $val = $this->Common_model->get_powCurve($list->Format_Type, $list->IMEI, $list->Device_Name);
        }
        // print_r($val);die;
        return $full_powCurve;
    }

    function get_powCurve($type, $imei) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        //print_r($data);
        $date = date("Y-m-d");
        // $date = '2019-04-13';

        $this->db2->select('Date_S')->from('device_data' . $type);

        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
        //print_r($this->db2->last_query());die;
        foreach ($query->result_array() as $row) {

            $row['hr'] = round($row['Date_S']);
            $data = $row;
            //print_r($data);die;
        }
        return $data;
    }

    public function commonDataFetching($imei, $field) {
        $data = 0;
        $this->db->select("$field")->from('device_register');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $data = $row["$field"];
        }
        return $data;
    }

    function getCurrentData($type, $imei, $field, $opt = "") {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = 0;
        $date = date("Y-m-d");
        //$date = '2019-04-13';

        $this->db2->select("$field")->from('device_data' . $type);
        if (!$opt) {
            $this->db2->where('Date_S', $date);
        }
        $this->db2->order_by('Time_S', 'DESC');
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $this->db2->limit(1);
        $query = $this->db2->get();
        // echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data = $row["$field"];
        }
        return $data;
    }

    public function getGeographicalDetails($imei = "", $type = "", $limit = "") {

        $data = array();

        if (!empty($imei) && !empty($type)) {

            $date = date("Y-m-d");
            //$date = '2019-03-04';

            $dev_type = $type;

            ($type == 1 ? $type = "" : $type = "_f" . $type);


            if ($dev_type == 1 || $dev_type == 6 || $dev_type == 10) {
                $this->db2->select('GRPM,RRPM,Windspeed,Pitch,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 7 || $dev_type == 8) {
                $this->db2->select('GRPM,RRPM,Windspeed,Nacelle_Position,Date_S,Time_S')->from('device_data' . $type);
            } else {
                $this->db2->select('GRPM,RRPM,Windspeed,Date_S,Time_S')->from('device_data' . $type);
            }

            $this->db2->where('Date_S', $date);
            if (!empty($imei)) {
                $this->db2->where('IMEI', $imei);
            }
            $this->db2->order_by('Time_S', 'DESC');
            if ($limit) {
                $this->db2->limit($limit);
            }
            $query = $this->db2->get();
            // echo $this->db2->last_query();die;
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getElectricalDetails($imei = "", $type = "", $limit = "") {

        $data = array();

        if (!empty($imei) && !empty($type)) {

            $date = date("Y-m-d");
            // $date = '2019-03-04';

            $dev_type = $type;

            ($type == 1 ? $type = "" : $type = "_f" . $type);


            if ($dev_type == 7 || $dev_type == 8) {
                $this->db2->select('L_N_Voltage_R as RPhase_Volt,L_N_Voltage_Y as YPhase_Volt,L_N_Voltage_B as BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Date_S,Time_S')->from('device_data' . $type);
            } else {
                $this->db2->select('RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Date_S,Time_S')->from('device_data' . $type);
            }

            $this->db2->where('Date_S', $date);
            if (!empty($imei)) {
                $this->db2->where('IMEI', $imei);
            }
            $this->db2->order_by('Time_S', 'DESC');

            if ($limit) {
                $this->db2->limit($limit);
            }
            $query = $this->db2->get();
            // echo $this->db2->last_query();die;
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getTemperatureDetails($imei = "", $type = "", $limit = "") {

        $data = array();

        if (!empty($imei) && !empty($type)) {

            $date = date("Y-m-d");
            // $date = '2019-03-04';

            $dev_type = $type;

            ($type == 1 ? $type = "" : $type = "_f" . $type);


            if ($dev_type == 1) {
                $this->db2->select('Ambient_Temp,Nacel_Temp,Gear_Temp,Gen1_Temp,Hydraulic_Temp,Control_Temp,Bearing_Temp,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 2) {
                $this->db2->select('G1_Temp,G2_Temp,G3_Temp,G4_Temp,G5_Temp,G6_Temp,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 3) {
                $this->db2->select('Thyristor_Temp,Ambient_Temp,Main_Panel_Temp,Gen1_Temp,Gen2_Temp,Nacel_Temp,Bearing_Temp,Gear_Temp,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 4) {
                $this->db2->select('Nacel_Temp,Gen1_Temp,Gen2_Temp,Gen_Bear1_Temp,Gen_Bear2_Temp,Gear_Oil_Temp,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 6) {
                $this->db2->select('Ambient_Temp,Nacel_Temp,Gear_Temp,Gen1_Temp,Control_Temp,Bearing_Temp,Hydraulic_Temp,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 7) {
                $this->db2->select('Control_Panel_Temp,Gear_Bearing1_Temp,Gear_Bearing2_Temp,Gear_Box_Oil_Temp,Gen_Winding1_Temp,Gen_Winding2_Temp,Gen_DE_Bearing_Temp,Gen_DE_NDE_Bearing_Temp,Nacelle_Temp,Main_Bearing_Temp,Transformer_Oil_Temp,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 8) {
                $this->db2->select('Control_Panel_Temp,Gear_Bearing1_Temp,Gear_Bearing2_Temp,Gear_Box_Oil_Temp,Gen_Winding1_Temp,Gen_Winding2_Temp,Gen_DE_Bearing_Temp,Gen_DE_NDE_Bearing_Temp,Nacelle_Temp,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 10) {
                $this->db2->select('Ambient_Temp,Hydraulic_Temp,Gear_Temp,Gen1_Temp,Gen2_Temp,Nacel_Temp,Control_Temp,Bearing_Temp,Date_S,Time_S')->from('device_data' . $type);
            }

            $this->db2->where('Date_S', $date);
            if (!empty($imei)) {
                $this->db2->where('IMEI', $imei);
            }
            $this->db2->order_by('Time_S', 'DESC');

            if ($limit) {
                $this->db2->limit($limit);
            }
            $query = $this->db2->get();
            // echo $this->db2->last_query();die;
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getGenerationDetails($imei = "", $type = "", $limit = "") {

        $data = array();

        if (!empty($imei) && !empty($type)) {

            $date = date("Y-m-d");
            // $date = '2019-03-04';

            $dev_type = $type;

            ($type == 1 ? $type = "" : $type = "_f" . $type);


            if ($dev_type == 1) {
                $this->db2->select('PAT_Gen0,PAT_Gen1,PAT_Gen2,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 2) {
                $this->db2->select('PAT_Gen1,PAT_Gen2,Import_Kwh,Gen1_Hours,Gen2_Hours,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 3) {
                $this->db2->select('Production_Total,Total_Hours,PAT_Gen1,PAT_Gen2,Gen1_Hours,Gen2_Hours,Import_Kwh,Import_Kvarh,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 4) {
                $this->db2->select('PAT_Gen1,PAT_Gen2,Import_Kwh,Gen1_Hours,Gen2_Hours,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 6) {
                $this->db2->select('PAT_Gen0,PAT_Gen1,PAT_Gen2,PAM_Gen0,PAM_Gen1,PAM_Gen2,PATP_Gen0,PATP_Gen1,PATP_Gen2,Total_Hours,Line_Ok,Turbine_Ok,Run_Hours,Gen1_Hours,Month_Total,Month_Line_Ok,Month_Turbine_Ok,Month_Run,Month_Gen1,Trip_Total,Trip_Line_Ok,Trip_Turbine_Ok,Trip_Run,Trip_Gen1,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 7) {
                $this->db2->select('Active_Total_Gen_Import,Active_Total_Gen_Export,Reactive_Total_Gen_Import,Reactive_Total_Gen_Export,Active_Gen1_Import,Active_Gen1_Export,Reactive_Gen1_Import,Reactive_Gen1_Export,Active_Gen2_Import,Active_Gen2_Export,Reactive_Gen2_Import,Reactive_Gen2_Export,G1_Connected_Counts,G2_Connected_Counts,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 8) {
                $this->db2->select('Kwh_Positive,Kwh_Negative,KVar_Positive,KVar_Negative,Total_Hours,Operate_Hours,Grid_failure_Hours,Stopped_Hours,Date_S,Time_S')->from('device_data' . $type);
            } else if ($dev_type == 10) {
                $this->db2->select('PAT_Gen0,PAT_Gen1,PAT_Gen2,Production_Total,Line_Hours,Line_Ok,Turbine_Ok,Run_Hours,Gen1_Hours,Gen2_Hours,Date_S,Time_S')->from('device_data' . $type);
            }

            $this->db2->where('Date_S', $date);
            if (!empty($imei)) {
                $this->db2->where('IMEI', $imei);
            }
            $this->db2->order_by('Time_S', 'DESC');

            if ($limit) {
                $this->db2->limit($limit);
            }
            $query = $this->db2->get();
            // echo $this->db2->last_query();die;
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getbasicInfo($dev_name) {

        $data = array();
        $this->db->select("IMEI,capacity,Format_Type")->from('device_register');
        if (!empty($dev_name)) {
            $this->db->where('Device_Name', $dev_name);
        }
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    function getPowerCurveData($type, $imei, $start_date, $end_date) {
        //skip for format type 1$type, $imei, $start_date, $end_date
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
        //$date = '2019-04-13';
        //$this->db2->distinct('Windspeed');
        $this->db2->select('Date_S,Windspeed,MAX( `Power` ) as Power')->from('device_data' . $type);
        //$this->db2->where('Date_S', $date);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        $this->db2->group_by('Windspeed');
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
        // echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    public function getPowerCurveCapacity($capacity) {
        $carray = array();
        switch ($capacity) {
            case 500:



                $carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 3.1),
                    '	6	     ' => array('Windspeed' => 4.1, 'Power' => 7.17),
                    '	7	     ' => array('Windspeed' => 4.2, 'Power' => 11.24),
                    '	8	     ' => array('Windspeed' => 4.3, 'Power' => 15.31),
                    '	9	     ' => array('Windspeed' => 4.4, 'Power' => 19.38),
                    '	10	     ' => array('Windspeed' => 4.5, 'Power' => 23.45),
                    '	11	     ' => array('Windspeed' => 4.6, 'Power' => 27.52),
                    '	12	     ' => array('Windspeed' => 4.7, 'Power' => 31.59),
                    '	13	     ' => array('Windspeed' => 4.8, 'Power' => 35.66),
                    '	14	     ' => array('Windspeed' => 4.9, 'Power' => 39.73),
                    '	15	     ' => array('Windspeed' => 5, 'Power' => 43.8),
                    '	16	     ' => array('Windspeed' => 5.1, 'Power' => 49.03),
                    '	17	     ' => array('Windspeed' => 5.2, 'Power' => 54.26),
                    '	18	     ' => array('Windspeed' => 5.3, 'Power' => 59.49),
                    '	19	     ' => array('Windspeed' => 5.4, 'Power' => 64.72),
                    '	20	     ' => array('Windspeed' => 5.5, 'Power' => 69.95),
                    '	21	     ' => array('Windspeed' => 5.6, 'Power' => 75.18),
                    '	22	     ' => array('Windspeed' => 5.7, 'Power' => 80.41),
                    '	23	     ' => array('Windspeed' => 5.8, 'Power' => 85.64),
                    '	24	     ' => array('Windspeed' => 5.9, 'Power' => 90.87),
                    '	25	     ' => array('Windspeed' => 6, 'Power' => 96.1),
                    '	26	     ' => array('Windspeed' => 6.1, 'Power' => 102.79),
                    '	27	     ' => array('Windspeed' => 6.2, 'Power' => 109.48),
                    '	28	     ' => array('Windspeed' => 6.3, 'Power' => 116.17),
                    '	29	     ' => array('Windspeed' => 6.4, 'Power' => 122.86),
                    '	30	     ' => array('Windspeed' => 6.5, 'Power' => 129.55),
                    '	31	     ' => array('Windspeed' => 6.6, 'Power' => 136.24),
                    '	32	     ' => array('Windspeed' => 6.7, 'Power' => 142.93),
                    '	33	     ' => array('Windspeed' => 6.8, 'Power' => 149.62),
                    '	34	     ' => array('Windspeed' => 6.9, 'Power' => 156.31),
                    '	35	     ' => array('Windspeed' => 7, 'Power' => 163),
                    '	36	     ' => array('Windspeed' => 7.1, 'Power' => 170.9),
                    '	37	     ' => array('Windspeed' => 7.2, 'Power' => 178.8),
                    '	38	     ' => array('Windspeed' => 7.3, 'Power' => 186.7),
                    '	39	     ' => array('Windspeed' => 7.4, 'Power' => 194.6),
                    '	40	     ' => array('Windspeed' => 7.5, 'Power' => 202.5),
                    '	41	     ' => array('Windspeed' => 7.6, 'Power' => 210.4),
                    '	42	     ' => array('Windspeed' => 7.7, 'Power' => 218.3),
                    '	43	     ' => array('Windspeed' => 7.8, 'Power' => 226.2),
                    '	44	     ' => array('Windspeed' => 7.9, 'Power' => 234.1),
                    '	45	     ' => array('Windspeed' => 8, 'Power' => 242),
                    '	46	     ' => array('Windspeed' => 8.1, 'Power' => 250.3),
                    '	47	     ' => array('Windspeed' => 8.2, 'Power' => 258.6),
                    '	48	     ' => array('Windspeed' => 8.3, 'Power' => 266.9),
                    '	49	     ' => array('Windspeed' => 8.4, 'Power' => 275.2),
                    '	50	     ' => array('Windspeed' => 8.5, 'Power' => 283.5),
                    '	51	     ' => array('Windspeed' => 8.6, 'Power' => 291.8),
                    '	52	     ' => array('Windspeed' => 8.7, 'Power' => 300.1),
                    '	53	     ' => array('Windspeed' => 8.8, 'Power' => 308.4),
                    '	54	     ' => array('Windspeed' => 8.9, 'Power' => 316.7),
                    '	55	     ' => array('Windspeed' => 9, 'Power' => 325),
                    '	56	     ' => array('Windspeed' => 9.1, 'Power' => 332.5),
                    '	57	     ' => array('Windspeed' => 9.2, 'Power' => 340),
                    '	58	     ' => array('Windspeed' => 9.3, 'Power' => 347.5),
                    '	59	     ' => array('Windspeed' => 9.4, 'Power' => 355),
                    '	60	     ' => array('Windspeed' => 9.5, 'Power' => 362.5),
                    '	61	     ' => array('Windspeed' => 9.6, 'Power' => 370),
                    '	62	     ' => array('Windspeed' => 9.7, 'Power' => 377.5),
                    '	63	     ' => array('Windspeed' => 9.8, 'Power' => 385),
                    '	64	     ' => array('Windspeed' => 9.9, 'Power' => 392.5),
                    '	65	     ' => array('Windspeed' => 10, 'Power' => 400),
                    '	66	     ' => array('Windspeed' => 10.1, 'Power' => 405.2),
                    '	67	     ' => array('Windspeed' => 10.2, 'Power' => 410.4),
                    '	68	     ' => array('Windspeed' => 10.3, 'Power' => 415.6),
                    '	69	     ' => array('Windspeed' => 10.4, 'Power' => 420.8),
                    '	70	     ' => array('Windspeed' => 10.5, 'Power' => 426),
                    '	71	     ' => array('Windspeed' => 10.6, 'Power' => 431.2),
                    '	72	     ' => array('Windspeed' => 10.7, 'Power' => 436.4),
                    '	73	     ' => array('Windspeed' => 10.8, 'Power' => 441.6),
                    '	74	     ' => array('Windspeed' => 10.9, 'Power' => 446.8),
                    '	75	     ' => array('Windspeed' => 11, 'Power' => 452),
                    '	76	     ' => array('Windspeed' => 11.1, 'Power' => 454.9),
                    '	77	     ' => array('Windspeed' => 11.2, 'Power' => 457.8),
                    '	78	     ' => array('Windspeed' => 11.3, 'Power' => 460.7),
                    '	79	     ' => array('Windspeed' => 11.4, 'Power' => 463.6),
                    '	80	     ' => array('Windspeed' => 11.5, 'Power' => 466.5),
                    '	81	     ' => array('Windspeed' => 11.6, 'Power' => 469.4),
                    '	82	     ' => array('Windspeed' => 11.7, 'Power' => 472.3),
                    '	83	     ' => array('Windspeed' => 11.8, 'Power' => 475.2),
                    '	84	     ' => array('Windspeed' => 11.9, 'Power' => 478.1),
                    '	85	     ' => array('Windspeed' => 12, 'Power' => 481),
                    '	86	     ' => array('Windspeed' => 12.1, 'Power' => 482.3),
                    '	87	     ' => array('Windspeed' => 12.2, 'Power' => 483.6),
                    '	88	     ' => array('Windspeed' => 12.3, 'Power' => 484.9),
                    '	89	     ' => array('Windspeed' => 12.4, 'Power' => 486.2),
                    '	90	     ' => array('Windspeed' => 12.5, 'Power' => 487.5),
                    '	91	     ' => array('Windspeed' => 12.6, 'Power' => 488.8),
                    '	92	     ' => array('Windspeed' => 12.7, 'Power' => 490.1),
                    '	93	     ' => array('Windspeed' => 12.8, 'Power' => 491.4),
                    '	94	     ' => array('Windspeed' => 12.9, 'Power' => 492.7),
                    '	95	     ' => array('Windspeed' => 13, 'Power' => 494),
                    '	96	     ' => array('Windspeed' => 13.1, 'Power' => 494.4),
                    '	97	     ' => array('Windspeed' => 13.2, 'Power' => 494.8),
                    '	98	     ' => array('Windspeed' => 13.3, 'Power' => 495.2),
                    '	99	     ' => array('Windspeed' => 13.4, 'Power' => 495.6),
                    '	100	     ' => array('Windspeed' => 13.5, 'Power' => 496),
                    '	101	     ' => array('Windspeed' => 13.6, 'Power' => 496.4),
                    '	102	     ' => array('Windspeed' => 13.7, 'Power' => 496.8),
                    '	103	     ' => array('Windspeed' => 13.8, 'Power' => 497.2),
                    '	104	     ' => array('Windspeed' => 13.9, 'Power' => 497.6),
                    '	105	     ' => array('Windspeed' => 14, 'Power' => 498),
                    '	106	     ' => array('Windspeed' => 14.1, 'Power' => 498.2),
                    '	107	     ' => array('Windspeed' => 14.2, 'Power' => 498.4),
                    '	108	     ' => array('Windspeed' => 14.3, 'Power' => 498.6),
                    '	109	     ' => array('Windspeed' => 14.4, 'Power' => 498.8),
                    '	110	     ' => array('Windspeed' => 14.5, 'Power' => 499),
                    '	111	     ' => array('Windspeed' => 14.6, 'Power' => 499.2),
                    '	112	     ' => array('Windspeed' => 14.7, 'Power' => 499.4),
                    '	113	     ' => array('Windspeed' => 14.8, 'Power' => 499.6),
                    '	114	     ' => array('Windspeed' => 14.9, 'Power' => 499.8),
                    '	115	     ' => array('Windspeed' => 15, 'Power' => 500),
                    '	116	     ' => array('Windspeed' => 15.1, 'Power' => 500),
                    '	117	     ' => array('Windspeed' => 15.2, 'Power' => 500),
                    '	118	     ' => array('Windspeed' => 15.3, 'Power' => 500),
                    '	119	     ' => array('Windspeed' => 15.4, 'Power' => 500),
                    '	120	     ' => array('Windspeed' => 15.5, 'Power' => 500),
                    '	121	     ' => array('Windspeed' => 15.6, 'Power' => 500),
                    '	122	     ' => array('Windspeed' => 15.7, 'Power' => 500),
                    '	123	     ' => array('Windspeed' => 15.8, 'Power' => 500),
                    '	124	     ' => array('Windspeed' => 15.9, 'Power' => 500),
                    '	125	     ' => array('Windspeed' => 16, 'Power' => 500),
                    '	126	     ' => array('Windspeed' => 16.1, 'Power' => 500),
                    '	127	     ' => array('Windspeed' => 16.2, 'Power' => 500),
                    '	128	     ' => array('Windspeed' => 16.3, 'Power' => 500),
                    '	129	     ' => array('Windspeed' => 16.4, 'Power' => 500),
                    '	130	     ' => array('Windspeed' => 16.5, 'Power' => 500),
                    '	131	     ' => array('Windspeed' => 16.6, 'Power' => 500),
                    '	132	     ' => array('Windspeed' => 16.7, 'Power' => 500),
                    '	133	     ' => array('Windspeed' => 16.8, 'Power' => 500),
                    '	134	     ' => array('Windspeed' => 16.9, 'Power' => 500),
                    '	135	     ' => array('Windspeed' => 17, 'Power' => 500),
                    '	136	     ' => array('Windspeed' => 17.1, 'Power' => 500),
                    '	137	     ' => array('Windspeed' => 17.2, 'Power' => 500),
                    '	138	     ' => array('Windspeed' => 17.3, 'Power' => 500),
                    '	139	     ' => array('Windspeed' => 17.4, 'Power' => 500),
                    '	140	     ' => array('Windspeed' => 17.5, 'Power' => 500),
                    '	141	     ' => array('Windspeed' => 17.6, 'Power' => 500),
                    '	142	     ' => array('Windspeed' => 17.7, 'Power' => 500),
                    '	143	     ' => array('Windspeed' => 17.8, 'Power' => 500),
                    '	144	     ' => array('Windspeed' => 17.9, 'Power' => 500),
                    '	145	     ' => array('Windspeed' => 18, 'Power' => 500),
                    '	146	     ' => array('Windspeed' => 18.1, 'Power' => 500),
                    '	147	     ' => array('Windspeed' => 18.2, 'Power' => 500),
                    '	148	     ' => array('Windspeed' => 18.3, 'Power' => 500),
                    '	149	     ' => array('Windspeed' => 18.4, 'Power' => 500),
                    '	150	     ' => array('Windspeed' => 18.5, 'Power' => 500),
                    '	151	     ' => array('Windspeed' => 18.6, 'Power' => 500),
                    '	152	     ' => array('Windspeed' => 18.7, 'Power' => 500),
                    '	153	     ' => array('Windspeed' => 18.8, 'Power' => 500),
                    '	154	     ' => array('Windspeed' => 18.9, 'Power' => 500),
                    '	155	     ' => array('Windspeed' => 19, 'Power' => 500),
                    '	156	     ' => array('Windspeed' => 19.1, 'Power' => 500),
                    '	157	     ' => array('Windspeed' => 19.2, 'Power' => 500),
                    '	158	     ' => array('Windspeed' => 19.3, 'Power' => 500),
                    '	159	     ' => array('Windspeed' => 19.4, 'Power' => 500),
                    '	160	     ' => array('Windspeed' => 19.5, 'Power' => 500),
                    '	161	     ' => array('Windspeed' => 19.6, 'Power' => 500),
                    '	162	     ' => array('Windspeed' => 19.7, 'Power' => 500),
                    '	163	     ' => array('Windspeed' => 19.8, 'Power' => 500),
                    '	164	     ' => array('Windspeed' => 19.9, 'Power' => 500),
                    '	165	     ' => array('Windspeed' => 20, 'Power' => 500),
                    '	166	     ' => array('Windspeed' => 20.1, 'Power' => 500),
                    '	167	     ' => array('Windspeed' => 20.2, 'Power' => 500),
                    '	168	     ' => array('Windspeed' => 20.3, 'Power' => 500),
                    '	169	     ' => array('Windspeed' => 20.4, 'Power' => 500),
                    '	170	     ' => array('Windspeed' => 20.5, 'Power' => 500),
                    '	171	     ' => array('Windspeed' => 20.6, 'Power' => 500),
                    '	172	     ' => array('Windspeed' => 20.7, 'Power' => 500),
                    '	173	     ' => array('Windspeed' => 20.8, 'Power' => 500),
                    '	174	     ' => array('Windspeed' => 20.9, 'Power' => 500),
                    '	175	     ' => array('Windspeed' => 21, 'Power' => 500),
                    '	176	     ' => array('Windspeed' => 21.1, 'Power' => 500),
                    '	177	     ' => array('Windspeed' => 21.2, 'Power' => 500),
                    '	178	     ' => array('Windspeed' => 21.3, 'Power' => 500),
                    '	179	     ' => array('Windspeed' => 21.4, 'Power' => 500),
                    '	180	     ' => array('Windspeed' => 21.5, 'Power' => 500),
                    '	181	     ' => array('Windspeed' => 21.6, 'Power' => 500),
                    '	182	     ' => array('Windspeed' => 21.7, 'Power' => 500),
                    '	183	     ' => array('Windspeed' => 21.8, 'Power' => 500),
                    '	184	     ' => array('Windspeed' => 21.9, 'Power' => 500),
                    '	185	     ' => array('Windspeed' => 22, 'Power' => 500),
                    '	186	     ' => array('Windspeed' => 22.1, 'Power' => 500),
                    '	187	     ' => array('Windspeed' => 22.2, 'Power' => 500),
                    '	188	     ' => array('Windspeed' => 22.3, 'Power' => 500),
                    '	189	     ' => array('Windspeed' => 22.4, 'Power' => 500),
                    '	190	     ' => array('Windspeed' => 22.5, 'Power' => 500),
                    '	191	     ' => array('Windspeed' => 22.6, 'Power' => 500),
                    '	192	     ' => array('Windspeed' => 22.7, 'Power' => 500),
                    '	193	     ' => array('Windspeed' => 22.8, 'Power' => 500),
                    '	194	     ' => array('Windspeed' => 22.9, 'Power' => 500),
                    '	195	     ' => array('Windspeed' => 23, 'Power' => 500),
                    '	196	     ' => array('Windspeed' => 23.1, 'Power' => 500),
                    '	197	     ' => array('Windspeed' => 23.2, 'Power' => 500),
                    '	198	     ' => array('Windspeed' => 23.3, 'Power' => 500),
                    '	199	     ' => array('Windspeed' => 23.4, 'Power' => 500),
                    '	200	     ' => array('Windspeed' => 23.5, 'Power' => 500),
                    '	201	     ' => array('Windspeed' => 23.6, 'Power' => 500),
                    '	202	     ' => array('Windspeed' => 23.7, 'Power' => 500),
                    '	203	     ' => array('Windspeed' => 23.8, 'Power' => 500),
                    '	204	     ' => array('Windspeed' => 23.9, 'Power' => 500),
                    '	205	     ' => array('Windspeed' => 24, 'Power' => 500),
                    '	206	     ' => array('Windspeed' => 24.1, 'Power' => 500),
                    '	207	     ' => array('Windspeed' => 24.2, 'Power' => 500),
                    '	208	     ' => array('Windspeed' => 24.3, 'Power' => 500),
                    '	209	     ' => array('Windspeed' => 24.4, 'Power' => 500),
                    '	210	     ' => array('Windspeed' => 24.5, 'Power' => 500),
                    '	211	     ' => array('Windspeed' => 24.6, 'Power' => 500),
                    '	212	     ' => array('Windspeed' => 24.7, 'Power' => 500),
                    '	213	     ' => array('Windspeed' => 24.8, 'Power' => 500),
                    '	214	     ' => array('Windspeed' => 24.9, 'Power' => 500),
                    '	215	     ' => array('Windspeed' => 25, 'Power' => 500),
                );

                break;
            case 600:
                $carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 21),
                    '	6	     ' => array('Windspeed' => 4.1, 'Power' => 23.1),
                    '	7	     ' => array('Windspeed' => 4.2, 'Power' => 25.2),
                    '	8	     ' => array('Windspeed' => 4.3, 'Power' => 27.3),
                    '	9	     ' => array('Windspeed' => 4.4, 'Power' => 29.4),
                    '	10	     ' => array('Windspeed' => 4.5, 'Power' => 31.5),
                    '	11	     ' => array('Windspeed' => 4.6, 'Power' => 33.6),
                    '	12	     ' => array('Windspeed' => 4.7, 'Power' => 35.7),
                    '	13	     ' => array('Windspeed' => 4.8, 'Power' => 37.8),
                    '	14	     ' => array('Windspeed' => 4.9, 'Power' => 39.9),
                    '	15	     ' => array('Windspeed' => 5, 'Power' => 42),
                    '	16	     ' => array('Windspeed' => 5.1, 'Power' => 45.8),
                    '	17	     ' => array('Windspeed' => 5.2, 'Power' => 49.6),
                    '	18	     ' => array('Windspeed' => 5.3, 'Power' => 53.4),
                    '	19	     ' => array('Windspeed' => 5.4, 'Power' => 57.2),
                    '	20	     ' => array('Windspeed' => 5.5, 'Power' => 61),
                    '	21	     ' => array('Windspeed' => 5.6, 'Power' => 64.8),
                    '	22	     ' => array('Windspeed' => 5.7, 'Power' => 68.6),
                    '	23	     ' => array('Windspeed' => 5.8, 'Power' => 72.4),
                    '	24	     ' => array('Windspeed' => 5.9, 'Power' => 76.2),
                    '	25	     ' => array('Windspeed' => 6, 'Power' => 80),
                    '	26	     ' => array('Windspeed' => 6.1, 'Power' => 86.2),
                    '	27	     ' => array('Windspeed' => 6.2, 'Power' => 92.4),
                    '	28	     ' => array('Windspeed' => 6.3, 'Power' => 98.6),
                    '	29	     ' => array('Windspeed' => 6.4, 'Power' => 104.8),
                    '	30	     ' => array('Windspeed' => 6.5, 'Power' => 111),
                    '	31	     ' => array('Windspeed' => 6.6, 'Power' => 117.2),
                    '	32	     ' => array('Windspeed' => 6.7, 'Power' => 123.4),
                    '	33	     ' => array('Windspeed' => 6.8, 'Power' => 129.6),
                    '	34	     ' => array('Windspeed' => 6.9, 'Power' => 135.8),
                    '	35	     ' => array('Windspeed' => 7, 'Power' => 142),
                    '	36	     ' => array('Windspeed' => 7.1, 'Power' => 149.6),
                    '	37	     ' => array('Windspeed' => 7.2, 'Power' => 157.2),
                    '	38	     ' => array('Windspeed' => 7.3, 'Power' => 164.8),
                    '	39	     ' => array('Windspeed' => 7.4, 'Power' => 172.4),
                    '	40	     ' => array('Windspeed' => 7.5, 'Power' => 180),
                    '	41	     ' => array('Windspeed' => 7.6, 'Power' => 187.6),
                    '	42	     ' => array('Windspeed' => 7.7, 'Power' => 195.2),
                    '	43	     ' => array('Windspeed' => 7.8, 'Power' => 202.8),
                    '	44	     ' => array('Windspeed' => 7.9, 'Power' => 210.4),
                    '	45	     ' => array('Windspeed' => 8, 'Power' => 218),
                    '	46	     ' => array('Windspeed' => 8.1, 'Power' => 229.2),
                    '	47	     ' => array('Windspeed' => 8.2, 'Power' => 240.4),
                    '	48	     ' => array('Windspeed' => 8.3, 'Power' => 251.6),
                    '	49	     ' => array('Windspeed' => 8.4, 'Power' => 262.8),
                    '	50	     ' => array('Windspeed' => 8.5, 'Power' => 274),
                    '	51	     ' => array('Windspeed' => 8.6, 'Power' => 285.2),
                    '	52	     ' => array('Windspeed' => 8.7, 'Power' => 296.4),
                    '	53	     ' => array('Windspeed' => 8.8, 'Power' => 307.6),
                    '	54	     ' => array('Windspeed' => 8.9, 'Power' => 318.8),
                    '	55	     ' => array('Windspeed' => 9, 'Power' => 330),
                    '	56	     ' => array('Windspeed' => 9.1, 'Power' => 338),
                    '	57	     ' => array('Windspeed' => 9.2, 'Power' => 346),
                    '	58	     ' => array('Windspeed' => 9.3, 'Power' => 354),
                    '	59	     ' => array('Windspeed' => 9.4, 'Power' => 362),
                    '	60	     ' => array('Windspeed' => 9.5, 'Power' => 370),
                    '	61	     ' => array('Windspeed' => 9.6, 'Power' => 378),
                    '	62	     ' => array('Windspeed' => 9.7, 'Power' => 386),
                    '	63	     ' => array('Windspeed' => 9.8, 'Power' => 394),
                    '	64	     ' => array('Windspeed' => 9.9, 'Power' => 402),
                    '	65	     ' => array('Windspeed' => 10, 'Power' => 410),
                    '	66	     ' => array('Windspeed' => 10.1, 'Power' => 416.3),
                    '	67	     ' => array('Windspeed' => 10.2, 'Power' => 422.6),
                    '	68	     ' => array('Windspeed' => 10.3, 'Power' => 428.9),
                    '	69	     ' => array('Windspeed' => 10.4, 'Power' => 435.2),
                    '	70	     ' => array('Windspeed' => 10.5, 'Power' => 441.5),
                    '	71	     ' => array('Windspeed' => 10.6, 'Power' => 447.8),
                    '	72	     ' => array('Windspeed' => 10.7, 'Power' => 454.1),
                    '	73	     ' => array('Windspeed' => 10.8, 'Power' => 460.4),
                    '	74	     ' => array('Windspeed' => 10.9, 'Power' => 466.7),
                    '	75	     ' => array('Windspeed' => 11, 'Power' => 473),
                    '	76	     ' => array('Windspeed' => 11.1, 'Power' => 478.9),
                    '	77	     ' => array('Windspeed' => 11.2, 'Power' => 484.8),
                    '	78	     ' => array('Windspeed' => 11.3, 'Power' => 490.7),
                    '	79	     ' => array('Windspeed' => 11.4, 'Power' => 496.6),
                    '	80	     ' => array('Windspeed' => 11.5, 'Power' => 502.5),
                    '	81	     ' => array('Windspeed' => 11.6, 'Power' => 508.4),
                    '	82	     ' => array('Windspeed' => 11.7, 'Power' => 514.3),
                    '	83	     ' => array('Windspeed' => 11.8, 'Power' => 520.2),
                    '	84	     ' => array('Windspeed' => 11.9, 'Power' => 526.1),
                    '	85	     ' => array('Windspeed' => 12, 'Power' => 532),
                    '	86	     ' => array('Windspeed' => 12.1, 'Power' => 535.2),
                    '	87	     ' => array('Windspeed' => 12.2, 'Power' => 538.4),
                    '	88	     ' => array('Windspeed' => 12.3, 'Power' => 541.6),
                    '	89	     ' => array('Windspeed' => 12.4, 'Power' => 544.8),
                    '	90	     ' => array('Windspeed' => 12.5, 'Power' => 548),
                    '	91	     ' => array('Windspeed' => 12.6, 'Power' => 551.2),
                    '	92	     ' => array('Windspeed' => 12.7, 'Power' => 554.4),
                    '	93	     ' => array('Windspeed' => 12.8, 'Power' => 557.6),
                    '	94	     ' => array('Windspeed' => 12.9, 'Power' => 560.8),
                    '	95	     ' => array('Windspeed' => 13, 'Power' => 564),
                    '	96	     ' => array('Windspeed' => 13.1, 'Power' => 565.8),
                    '	97	     ' => array('Windspeed' => 13.2, 'Power' => 567.6),
                    '	98	     ' => array('Windspeed' => 13.3, 'Power' => 569.4),
                    '	99	     ' => array('Windspeed' => 13.4, 'Power' => 571.2),
                    '	100	     ' => array('Windspeed' => 13.5, 'Power' => 573),
                    '	101	     ' => array('Windspeed' => 13.6, 'Power' => 574.8),
                    '	102	     ' => array('Windspeed' => 13.7, 'Power' => 576.6),
                    '	103	     ' => array('Windspeed' => 13.8, 'Power' => 578.4),
                    '	104	     ' => array('Windspeed' => 13.9, 'Power' => 580.2),
                    '	105	     ' => array('Windspeed' => 14, 'Power' => 582),
                    '	106	     ' => array('Windspeed' => 14.1, 'Power' => 583.5),
                    '	107	     ' => array('Windspeed' => 14.2, 'Power' => 585),
                    '	108	     ' => array('Windspeed' => 14.3, 'Power' => 586.5),
                    '	109	     ' => array('Windspeed' => 14.4, 'Power' => 588),
                    '	110	     ' => array('Windspeed' => 14.5, 'Power' => 589.5),
                    '	111	     ' => array('Windspeed' => 14.6, 'Power' => 591),
                    '	112	     ' => array('Windspeed' => 14.7, 'Power' => 592.5),
                    '	113	     ' => array('Windspeed' => 14.8, 'Power' => 594),
                    '	114	     ' => array('Windspeed' => 14.9, 'Power' => 595.5),
                    '	115	     ' => array('Windspeed' => 15, 'Power' => 597),
                    '	116	     ' => array('Windspeed' => 15.1, 'Power' => 597.3),
                    '	117	     ' => array('Windspeed' => 15.2, 'Power' => 597.6),
                    '	118	     ' => array('Windspeed' => 15.3, 'Power' => 597.9),
                    '	119	     ' => array('Windspeed' => 15.4, 'Power' => 598.2),
                    '	120	     ' => array('Windspeed' => 15.5, 'Power' => 598.5),
                    '	121	     ' => array('Windspeed' => 15.6, 'Power' => 598.8),
                    '	122	     ' => array('Windspeed' => 15.7, 'Power' => 599.1),
                    '	123	     ' => array('Windspeed' => 15.8, 'Power' => 599.4),
                    '	124	     ' => array('Windspeed' => 15.9, 'Power' => 599.7),
                    '	125	     ' => array('Windspeed' => 16, 'Power' => 600),
                    '	126	     ' => array('Windspeed' => 16.1, 'Power' => 600),
                    '	127	     ' => array('Windspeed' => 16.2, 'Power' => 600),
                    '	128	     ' => array('Windspeed' => 16.3, 'Power' => 600),
                    '	129	     ' => array('Windspeed' => 16.4, 'Power' => 600),
                    '	130	     ' => array('Windspeed' => 16.5, 'Power' => 600),
                    '	131	     ' => array('Windspeed' => 16.6, 'Power' => 600),
                    '	132	     ' => array('Windspeed' => 16.7, 'Power' => 600),
                    '	133	     ' => array('Windspeed' => 16.8, 'Power' => 600),
                    '	134	     ' => array('Windspeed' => 16.9, 'Power' => 600),
                    '	135	     ' => array('Windspeed' => 17, 'Power' => 600),
                    '	136	     ' => array('Windspeed' => 17.1, 'Power' => 600),
                    '	137	     ' => array('Windspeed' => 17.2, 'Power' => 600),
                    '	138	     ' => array('Windspeed' => 17.3, 'Power' => 600),
                    '	139	     ' => array('Windspeed' => 17.4, 'Power' => 600),
                    '	140	     ' => array('Windspeed' => 17.5, 'Power' => 600),
                    '	141	     ' => array('Windspeed' => 17.6, 'Power' => 600),
                    '	142	     ' => array('Windspeed' => 17.7, 'Power' => 600),
                    '	143	     ' => array('Windspeed' => 17.8, 'Power' => 600),
                    '	144	     ' => array('Windspeed' => 17.9, 'Power' => 600),
                    '	145	     ' => array('Windspeed' => 18, 'Power' => 600),
                    '	146	     ' => array('Windspeed' => 18.1, 'Power' => 600),
                    '	147	     ' => array('Windspeed' => 18.2, 'Power' => 600),
                    '	148	     ' => array('Windspeed' => 18.3, 'Power' => 600),
                    '	149	     ' => array('Windspeed' => 18.4, 'Power' => 600),
                    '	150	     ' => array('Windspeed' => 18.5, 'Power' => 600),
                    '	151	     ' => array('Windspeed' => 18.6, 'Power' => 600),
                    '	152	     ' => array('Windspeed' => 18.7, 'Power' => 600),
                    '	153	     ' => array('Windspeed' => 18.8, 'Power' => 600),
                    '	154	     ' => array('Windspeed' => 18.9, 'Power' => 600),
                    '	155	     ' => array('Windspeed' => 19, 'Power' => 600),
                    '	156	     ' => array('Windspeed' => 19.1, 'Power' => 600),
                    '	157	     ' => array('Windspeed' => 19.2, 'Power' => 600),
                    '	158	     ' => array('Windspeed' => 19.3, 'Power' => 600),
                    '	159	     ' => array('Windspeed' => 19.4, 'Power' => 600),
                    '	160	     ' => array('Windspeed' => 19.5, 'Power' => 600),
                    '	161	     ' => array('Windspeed' => 19.6, 'Power' => 600),
                    '	162	     ' => array('Windspeed' => 19.7, 'Power' => 600),
                    '	163	     ' => array('Windspeed' => 19.8, 'Power' => 600),
                    '	164	     ' => array('Windspeed' => 19.9, 'Power' => 600),
                    '	165	     ' => array('Windspeed' => 20, 'Power' => 600),
                    '	166	     ' => array('Windspeed' => 20.1, 'Power' => 600),
                    '	167	     ' => array('Windspeed' => 20.2, 'Power' => 600),
                    '	168	     ' => array('Windspeed' => 20.3, 'Power' => 600),
                    '	169	     ' => array('Windspeed' => 20.4, 'Power' => 600),
                    '	170	     ' => array('Windspeed' => 20.5, 'Power' => 600),
                    '	171	     ' => array('Windspeed' => 20.6, 'Power' => 600),
                    '	172	     ' => array('Windspeed' => 20.7, 'Power' => 600),
                    '	173	     ' => array('Windspeed' => 20.8, 'Power' => 600),
                    '	174	     ' => array('Windspeed' => 20.9, 'Power' => 600),
                    '	175	     ' => array('Windspeed' => 21, 'Power' => 600),
                    '	176	     ' => array('Windspeed' => 21.1, 'Power' => 600),
                    '	177	     ' => array('Windspeed' => 21.2, 'Power' => 600),
                    '	178	     ' => array('Windspeed' => 21.3, 'Power' => 600),
                    '	179	     ' => array('Windspeed' => 21.4, 'Power' => 600),
                    '	180	     ' => array('Windspeed' => 21.5, 'Power' => 600),
                    '	181	     ' => array('Windspeed' => 21.6, 'Power' => 600),
                    '	182	     ' => array('Windspeed' => 21.7, 'Power' => 600),
                    '	183	     ' => array('Windspeed' => 21.8, 'Power' => 600),
                    '	184	     ' => array('Windspeed' => 21.9, 'Power' => 600),
                    '	185	     ' => array('Windspeed' => 22, 'Power' => 600),
                    '	186	     ' => array('Windspeed' => 22.1, 'Power' => 600),
                    '	187	     ' => array('Windspeed' => 22.2, 'Power' => 600),
                    '	188	     ' => array('Windspeed' => 22.3, 'Power' => 600),
                    '	189	     ' => array('Windspeed' => 22.4, 'Power' => 600),
                    '	190	     ' => array('Windspeed' => 22.5, 'Power' => 600),
                    '	191	     ' => array('Windspeed' => 22.6, 'Power' => 600),
                    '	192	     ' => array('Windspeed' => 22.7, 'Power' => 600),
                    '	193	     ' => array('Windspeed' => 22.8, 'Power' => 600),
                    '	194	     ' => array('Windspeed' => 22.9, 'Power' => 600),
                    '	195	     ' => array('Windspeed' => 23, 'Power' => 600),
                    '	196	     ' => array('Windspeed' => 23.1, 'Power' => 600),
                    '	197	     ' => array('Windspeed' => 23.2, 'Power' => 600),
                    '	198	     ' => array('Windspeed' => 23.3, 'Power' => 600),
                    '	199	     ' => array('Windspeed' => 23.4, 'Power' => 600),
                    '	200	     ' => array('Windspeed' => 23.5, 'Power' => 600),
                    '	201	     ' => array('Windspeed' => 23.6, 'Power' => 600),
                    '	202	     ' => array('Windspeed' => 23.7, 'Power' => 600),
                    '	203	     ' => array('Windspeed' => 23.8, 'Power' => 600),
                    '	204	     ' => array('Windspeed' => 23.9, 'Power' => 600),
                    '	205	     ' => array('Windspeed' => 24, 'Power' => 600),
                    '	206	     ' => array('Windspeed' => 24.1, 'Power' => 600),
                    '	207	     ' => array('Windspeed' => 24.2, 'Power' => 600),
                    '	208	     ' => array('Windspeed' => 24.3, 'Power' => 600),
                    '	209	     ' => array('Windspeed' => 24.4, 'Power' => 600),
                    '	210	     ' => array('Windspeed' => 24.5, 'Power' => 600),
                    '	211	     ' => array('Windspeed' => 24.6, 'Power' => 600),
                    '	212	     ' => array('Windspeed' => 24.7, 'Power' => 600),
                    '	213	     ' => array('Windspeed' => 24.8, 'Power' => 600),
                    '	214	     ' => array('Windspeed' => 24.9, 'Power' => 600),
                    '	215	     ' => array('Windspeed' => 25, 'Power' => 600)
                );
                break;
            case 225:
                $carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 21),
                    '	6	     ' => array('Windspeed' => 4.1, 'Power' => 23.1),
                    '	7	     ' => array('Windspeed' => 4.2, 'Power' => 25.2),
                    '	8	     ' => array('Windspeed' => 4.3, 'Power' => 27.3),
                    '	9	     ' => array('Windspeed' => 4.4, 'Power' => 29.4),
                    '	10	     ' => array('Windspeed' => 4.5, 'Power' => 31.5),
                    '	11	     ' => array('Windspeed' => 4.6, 'Power' => 33.6),
                    '	12	     ' => array('Windspeed' => 4.7, 'Power' => 35.7),
                    '	13	     ' => array('Windspeed' => 4.8, 'Power' => 37.8),
                    '	14	     ' => array('Windspeed' => 4.9, 'Power' => 39.9),
                    '	15	     ' => array('Windspeed' => 5, 'Power' => 42),
                    '	16	     ' => array('Windspeed' => 5.1, 'Power' => 45.8),
                    '	17	     ' => array('Windspeed' => 5.2, 'Power' => 49.6),
                    '	18	     ' => array('Windspeed' => 5.3, 'Power' => 53.4),
                    '	19	     ' => array('Windspeed' => 5.4, 'Power' => 57.2),
                    '	20	     ' => array('Windspeed' => 5.5, 'Power' => 61),
                    '	21	     ' => array('Windspeed' => 5.6, 'Power' => 64.8),
                    '	22	     ' => array('Windspeed' => 5.7, 'Power' => 68.6),
                    '	23	     ' => array('Windspeed' => 5.8, 'Power' => 72.4),
                    '	24	     ' => array('Windspeed' => 5.9, 'Power' => 76.2),
                    '	25	     ' => array('Windspeed' => 6, 'Power' => 80),
                    '	26	     ' => array('Windspeed' => 6.1, 'Power' => 86.2),
                    '	27	     ' => array('Windspeed' => 6.2, 'Power' => 92.4),
                    '	28	     ' => array('Windspeed' => 6.3, 'Power' => 98.6),
                    '	29	     ' => array('Windspeed' => 6.4, 'Power' => 104.8),
                    '	30	     ' => array('Windspeed' => 6.5, 'Power' => 111),
                    '	31	     ' => array('Windspeed' => 6.6, 'Power' => 117.2),
                    '	32	     ' => array('Windspeed' => 6.7, 'Power' => 123.4),
                    '	33	     ' => array('Windspeed' => 6.8, 'Power' => 129.6),
                    '	34	     ' => array('Windspeed' => 6.9, 'Power' => 135.8),
                    '	35	     ' => array('Windspeed' => 7, 'Power' => 142),
                    '	36	     ' => array('Windspeed' => 7.1, 'Power' => 149.6),
                    '	37	     ' => array('Windspeed' => 7.2, 'Power' => 157.2),
                    '	38	     ' => array('Windspeed' => 7.3, 'Power' => 164.8),
                    '	39	     ' => array('Windspeed' => 7.4, 'Power' => 172.4),
                    '	40	     ' => array('Windspeed' => 7.5, 'Power' => 180),
                    '	41	     ' => array('Windspeed' => 7.6, 'Power' => 187.6),
                    '	42	     ' => array('Windspeed' => 7.7, 'Power' => 195.2),
                    '	43	     ' => array('Windspeed' => 7.8, 'Power' => 202.8),
                    '	44	     ' => array('Windspeed' => 7.9, 'Power' => 210.4),
                    '	45	     ' => array('Windspeed' => 8, 'Power' => 218),
                    '	46	     ' => array('Windspeed' => 8.1, 'Power' => 229.2),
                    '	47	     ' => array('Windspeed' => 8.2, 'Power' => 240.4),
                    '	48	     ' => array('Windspeed' => 8.3, 'Power' => 251.6),
                    '	49	     ' => array('Windspeed' => 8.4, 'Power' => 262.8),
                    '	50	     ' => array('Windspeed' => 8.5, 'Power' => 274),
                    '	51	     ' => array('Windspeed' => 8.6, 'Power' => 285.2),
                    '	52	     ' => array('Windspeed' => 8.7, 'Power' => 296.4),
                    '	53	     ' => array('Windspeed' => 8.8, 'Power' => 307.6),
                    '	54	     ' => array('Windspeed' => 8.9, 'Power' => 318.8),
                    '	55	     ' => array('Windspeed' => 9, 'Power' => 330),
                    '	56	     ' => array('Windspeed' => 9.1, 'Power' => 338),
                    '	57	     ' => array('Windspeed' => 9.2, 'Power' => 346),
                    '	58	     ' => array('Windspeed' => 9.3, 'Power' => 354),
                    '	59	     ' => array('Windspeed' => 9.4, 'Power' => 362),
                    '	60	     ' => array('Windspeed' => 9.5, 'Power' => 370),
                    '	61	     ' => array('Windspeed' => 9.6, 'Power' => 378),
                    '	62	     ' => array('Windspeed' => 9.7, 'Power' => 386),
                    '	63	     ' => array('Windspeed' => 9.8, 'Power' => 394),
                    '	64	     ' => array('Windspeed' => 9.9, 'Power' => 402),
                    '	65	     ' => array('Windspeed' => 10, 'Power' => 410),
                    '	66	     ' => array('Windspeed' => 10.1, 'Power' => 416.3),
                    '	67	     ' => array('Windspeed' => 10.2, 'Power' => 422.6),
                    '	68	     ' => array('Windspeed' => 10.3, 'Power' => 428.9),
                    '	69	     ' => array('Windspeed' => 10.4, 'Power' => 435.2),
                    '	70	     ' => array('Windspeed' => 10.5, 'Power' => 441.5),
                    '	71	     ' => array('Windspeed' => 10.6, 'Power' => 447.8),
                    '	72	     ' => array('Windspeed' => 10.7, 'Power' => 454.1),
                    '	73	     ' => array('Windspeed' => 10.8, 'Power' => 460.4),
                    '	74	     ' => array('Windspeed' => 10.9, 'Power' => 466.7),
                    '	75	     ' => array('Windspeed' => 11, 'Power' => 473),
                    '	76	     ' => array('Windspeed' => 11.1, 'Power' => 478.9),
                    '	77	     ' => array('Windspeed' => 11.2, 'Power' => 484.8),
                    '	78	     ' => array('Windspeed' => 11.3, 'Power' => 490.7),
                    '	79	     ' => array('Windspeed' => 11.4, 'Power' => 496.6),
                    '	80	     ' => array('Windspeed' => 11.5, 'Power' => 502.5),
                    '	81	     ' => array('Windspeed' => 11.6, 'Power' => 508.4),
                    '	82	     ' => array('Windspeed' => 11.7, 'Power' => 514.3),
                    '	83	     ' => array('Windspeed' => 11.8, 'Power' => 520.2),
                    '	84	     ' => array('Windspeed' => 11.9, 'Power' => 526.1),
                    '	85	     ' => array('Windspeed' => 12, 'Power' => 532),
                    '	86	     ' => array('Windspeed' => 12.1, 'Power' => 535.2),
                    '	87	     ' => array('Windspeed' => 12.2, 'Power' => 538.4),
                    '	88	     ' => array('Windspeed' => 12.3, 'Power' => 541.6),
                    '	89	     ' => array('Windspeed' => 12.4, 'Power' => 544.8),
                    '	90	     ' => array('Windspeed' => 12.5, 'Power' => 548),
                    '	91	     ' => array('Windspeed' => 12.6, 'Power' => 551.2),
                    '	92	     ' => array('Windspeed' => 12.7, 'Power' => 554.4),
                    '	93	     ' => array('Windspeed' => 12.8, 'Power' => 557.6),
                    '	94	     ' => array('Windspeed' => 12.9, 'Power' => 560.8),
                    '	95	     ' => array('Windspeed' => 13, 'Power' => 564),
                    '	96	     ' => array('Windspeed' => 13.1, 'Power' => 565.8),
                    '	97	     ' => array('Windspeed' => 13.2, 'Power' => 567.6),
                    '	98	     ' => array('Windspeed' => 13.3, 'Power' => 569.4),
                    '	99	     ' => array('Windspeed' => 13.4, 'Power' => 571.2),
                    '	100	     ' => array('Windspeed' => 13.5, 'Power' => 573),
                    '	101	     ' => array('Windspeed' => 13.6, 'Power' => 574.8),
                    '	102	     ' => array('Windspeed' => 13.7, 'Power' => 576.6),
                    '	103	     ' => array('Windspeed' => 13.8, 'Power' => 578.4),
                    '	104	     ' => array('Windspeed' => 13.9, 'Power' => 580.2),
                    '	105	     ' => array('Windspeed' => 14, 'Power' => 582),
                    '	106	     ' => array('Windspeed' => 14.1, 'Power' => 583.5),
                    '	107	     ' => array('Windspeed' => 14.2, 'Power' => 585),
                    '	108	     ' => array('Windspeed' => 14.3, 'Power' => 586.5),
                    '	109	     ' => array('Windspeed' => 14.4, 'Power' => 588),
                    '	110	     ' => array('Windspeed' => 14.5, 'Power' => 589.5),
                    '	111	     ' => array('Windspeed' => 14.6, 'Power' => 591),
                    '	112	     ' => array('Windspeed' => 14.7, 'Power' => 592.5),
                    '	113	     ' => array('Windspeed' => 14.8, 'Power' => 594),
                    '	114	     ' => array('Windspeed' => 14.9, 'Power' => 595.5),
                    '	115	     ' => array('Windspeed' => 15, 'Power' => 597),
                    '	116	     ' => array('Windspeed' => 15.1, 'Power' => 597.3),
                    '	117	     ' => array('Windspeed' => 15.2, 'Power' => 597.6),
                    '	118	     ' => array('Windspeed' => 15.3, 'Power' => 597.9),
                    '	119	     ' => array('Windspeed' => 15.4, 'Power' => 598.2),
                    '	120	     ' => array('Windspeed' => 15.5, 'Power' => 598.5),
                    '	121	     ' => array('Windspeed' => 15.6, 'Power' => 598.8),
                    '	122	     ' => array('Windspeed' => 15.7, 'Power' => 599.1),
                    '	123	     ' => array('Windspeed' => 15.8, 'Power' => 599.4),
                    '	124	     ' => array('Windspeed' => 15.9, 'Power' => 599.7),
                    '	125	     ' => array('Windspeed' => 16, 'Power' => 600),
                    '	126	     ' => array('Windspeed' => 16.1, 'Power' => 600),
                    '	127	     ' => array('Windspeed' => 16.2, 'Power' => 600),
                    '	128	     ' => array('Windspeed' => 16.3, 'Power' => 600),
                    '	129	     ' => array('Windspeed' => 16.4, 'Power' => 600),
                    '	130	     ' => array('Windspeed' => 16.5, 'Power' => 600),
                    '	131	     ' => array('Windspeed' => 16.6, 'Power' => 600),
                    '	132	     ' => array('Windspeed' => 16.7, 'Power' => 600),
                    '	133	     ' => array('Windspeed' => 16.8, 'Power' => 600),
                    '	134	     ' => array('Windspeed' => 16.9, 'Power' => 600),
                    '	135	     ' => array('Windspeed' => 17, 'Power' => 600),
                    '	136	     ' => array('Windspeed' => 17.1, 'Power' => 600),
                    '	137	     ' => array('Windspeed' => 17.2, 'Power' => 600),
                    '	138	     ' => array('Windspeed' => 17.3, 'Power' => 600),
                    '	139	     ' => array('Windspeed' => 17.4, 'Power' => 600),
                    '	140	     ' => array('Windspeed' => 17.5, 'Power' => 600),
                    '	141	     ' => array('Windspeed' => 17.6, 'Power' => 600),
                    '	142	     ' => array('Windspeed' => 17.7, 'Power' => 600),
                    '	143	     ' => array('Windspeed' => 17.8, 'Power' => 600),
                    '	144	     ' => array('Windspeed' => 17.9, 'Power' => 600),
                    '	145	     ' => array('Windspeed' => 18, 'Power' => 600),
                    '	146	     ' => array('Windspeed' => 18.1, 'Power' => 600),
                    '	147	     ' => array('Windspeed' => 18.2, 'Power' => 600),
                    '	148	     ' => array('Windspeed' => 18.3, 'Power' => 600),
                    '	149	     ' => array('Windspeed' => 18.4, 'Power' => 600),
                    '	150	     ' => array('Windspeed' => 18.5, 'Power' => 600),
                    '	151	     ' => array('Windspeed' => 18.6, 'Power' => 600),
                    '	152	     ' => array('Windspeed' => 18.7, 'Power' => 600),
                    '	153	     ' => array('Windspeed' => 18.8, 'Power' => 600),
                    '	154	     ' => array('Windspeed' => 18.9, 'Power' => 600),
                    '	155	     ' => array('Windspeed' => 19, 'Power' => 600),
                    '	156	     ' => array('Windspeed' => 19.1, 'Power' => 600),
                    '	157	     ' => array('Windspeed' => 19.2, 'Power' => 600),
                    '	158	     ' => array('Windspeed' => 19.3, 'Power' => 600),
                    '	159	     ' => array('Windspeed' => 19.4, 'Power' => 600),
                    '	160	     ' => array('Windspeed' => 19.5, 'Power' => 600),
                    '	161	     ' => array('Windspeed' => 19.6, 'Power' => 600),
                    '	162	     ' => array('Windspeed' => 19.7, 'Power' => 600),
                    '	163	     ' => array('Windspeed' => 19.8, 'Power' => 600),
                    '	164	     ' => array('Windspeed' => 19.9, 'Power' => 600),
                    '	165	     ' => array('Windspeed' => 20, 'Power' => 600),
                    '	166	     ' => array('Windspeed' => 20.1, 'Power' => 600),
                    '	167	     ' => array('Windspeed' => 20.2, 'Power' => 600),
                    '	168	     ' => array('Windspeed' => 20.3, 'Power' => 600),
                    '	169	     ' => array('Windspeed' => 20.4, 'Power' => 600),
                    '	170	     ' => array('Windspeed' => 20.5, 'Power' => 600),
                    '	171	     ' => array('Windspeed' => 20.6, 'Power' => 600),
                    '	172	     ' => array('Windspeed' => 20.7, 'Power' => 600),
                    '	173	     ' => array('Windspeed' => 20.8, 'Power' => 600),
                    '	174	     ' => array('Windspeed' => 20.9, 'Power' => 600),
                    '	175	     ' => array('Windspeed' => 21, 'Power' => 600),
                    '	176	     ' => array('Windspeed' => 21.1, 'Power' => 600),
                    '	177	     ' => array('Windspeed' => 21.2, 'Power' => 600),
                    '	178	     ' => array('Windspeed' => 21.3, 'Power' => 600),
                    '	179	     ' => array('Windspeed' => 21.4, 'Power' => 600),
                    '	180	     ' => array('Windspeed' => 21.5, 'Power' => 600),
                    '	181	     ' => array('Windspeed' => 21.6, 'Power' => 600),
                    '	182	     ' => array('Windspeed' => 21.7, 'Power' => 600),
                    '	183	     ' => array('Windspeed' => 21.8, 'Power' => 600),
                    '	184	     ' => array('Windspeed' => 21.9, 'Power' => 600),
                    '	185	     ' => array('Windspeed' => 22, 'Power' => 600),
                    '	186	     ' => array('Windspeed' => 22.1, 'Power' => 600),
                    '	187	     ' => array('Windspeed' => 22.2, 'Power' => 600),
                    '	188	     ' => array('Windspeed' => 22.3, 'Power' => 600),
                    '	189	     ' => array('Windspeed' => 22.4, 'Power' => 600),
                    '	190	     ' => array('Windspeed' => 22.5, 'Power' => 600),
                    '	191	     ' => array('Windspeed' => 22.6, 'Power' => 600),
                    '	192	     ' => array('Windspeed' => 22.7, 'Power' => 600),
                    '	193	     ' => array('Windspeed' => 22.8, 'Power' => 600),
                    '	194	     ' => array('Windspeed' => 22.9, 'Power' => 600),
                    '	195	     ' => array('Windspeed' => 23, 'Power' => 600),
                    '	196	     ' => array('Windspeed' => 23.1, 'Power' => 600),
                    '	197	     ' => array('Windspeed' => 23.2, 'Power' => 600),
                    '	198	     ' => array('Windspeed' => 23.3, 'Power' => 600),
                    '	199	     ' => array('Windspeed' => 23.4, 'Power' => 600),
                    '	200	     ' => array('Windspeed' => 23.5, 'Power' => 600),
                    '	201	     ' => array('Windspeed' => 23.6, 'Power' => 600),
                    '	202	     ' => array('Windspeed' => 23.7, 'Power' => 600),
                    '	203	     ' => array('Windspeed' => 23.8, 'Power' => 600),
                    '	204	     ' => array('Windspeed' => 23.9, 'Power' => 600),
                    '	205	     ' => array('Windspeed' => 24, 'Power' => 600),
                    '	206	     ' => array('Windspeed' => 24.1, 'Power' => 600),
                    '	207	     ' => array('Windspeed' => 24.2, 'Power' => 600),
                    '	208	     ' => array('Windspeed' => 24.3, 'Power' => 600),
                    '	209	     ' => array('Windspeed' => 24.4, 'Power' => 600),
                    '	210	     ' => array('Windspeed' => 24.5, 'Power' => 600),
                    '	211	     ' => array('Windspeed' => 24.6, 'Power' => 600),
                    '	212	     ' => array('Windspeed' => 24.7, 'Power' => 600),
                    '	213	     ' => array('Windspeed' => 24.8, 'Power' => 600),
                    '	214	     ' => array('Windspeed' => 24.9, 'Power' => 600),
                    '	215	     ' => array('Windspeed' => 25, 'Power' => 600),
                );
                break;
            case 250:
                $carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 21),
                    '	6	     ' => array('Windspeed' => 4.1, 'Power' => 23.1),
                    '	7	     ' => array('Windspeed' => 4.2, 'Power' => 25.2),
                    '	8	     ' => array('Windspeed' => 4.3, 'Power' => 27.3),
                    '	9	     ' => array('Windspeed' => 4.4, 'Power' => 29.4),
                    '	10	     ' => array('Windspeed' => 4.5, 'Power' => 31.5),
                    '	11	     ' => array('Windspeed' => 4.6, 'Power' => 33.6),
                    '	12	     ' => array('Windspeed' => 4.7, 'Power' => 35.7),
                    '	13	     ' => array('Windspeed' => 4.8, 'Power' => 37.8),
                    '	14	     ' => array('Windspeed' => 4.9, 'Power' => 39.9),
                    '	15	     ' => array('Windspeed' => 5, 'Power' => 42),
                    '	16	     ' => array('Windspeed' => 5.1, 'Power' => 45.8),
                    '	17	     ' => array('Windspeed' => 5.2, 'Power' => 49.6),
                    '	18	     ' => array('Windspeed' => 5.3, 'Power' => 53.4),
                    '	19	     ' => array('Windspeed' => 5.4, 'Power' => 57.2),
                    '	20	     ' => array('Windspeed' => 5.5, 'Power' => 61),
                    '	21	     ' => array('Windspeed' => 5.6, 'Power' => 64.8),
                    '	22	     ' => array('Windspeed' => 5.7, 'Power' => 68.6),
                    '	23	     ' => array('Windspeed' => 5.8, 'Power' => 72.4),
                    '	24	     ' => array('Windspeed' => 5.9, 'Power' => 76.2),
                    '	25	     ' => array('Windspeed' => 6, 'Power' => 80),
                    '	26	     ' => array('Windspeed' => 6.1, 'Power' => 86.2),
                    '	27	     ' => array('Windspeed' => 6.2, 'Power' => 92.4),
                    '	28	     ' => array('Windspeed' => 6.3, 'Power' => 98.6),
                    '	29	     ' => array('Windspeed' => 6.4, 'Power' => 104.8),
                    '	30	     ' => array('Windspeed' => 6.5, 'Power' => 111),
                    '	31	     ' => array('Windspeed' => 6.6, 'Power' => 117.2),
                    '	32	     ' => array('Windspeed' => 6.7, 'Power' => 123.4),
                    '	33	     ' => array('Windspeed' => 6.8, 'Power' => 129.6),
                    '	34	     ' => array('Windspeed' => 6.9, 'Power' => 135.8),
                    '	35	     ' => array('Windspeed' => 7, 'Power' => 142),
                    '	36	     ' => array('Windspeed' => 7.1, 'Power' => 149.6),
                    '	37	     ' => array('Windspeed' => 7.2, 'Power' => 157.2),
                    '	38	     ' => array('Windspeed' => 7.3, 'Power' => 164.8),
                    '	39	     ' => array('Windspeed' => 7.4, 'Power' => 172.4),
                    '	40	     ' => array('Windspeed' => 7.5, 'Power' => 180),
                    '	41	     ' => array('Windspeed' => 7.6, 'Power' => 187.6),
                    '	42	     ' => array('Windspeed' => 7.7, 'Power' => 195.2),
                    '	43	     ' => array('Windspeed' => 7.8, 'Power' => 202.8),
                    '	44	     ' => array('Windspeed' => 7.9, 'Power' => 210.4),
                    '	45	     ' => array('Windspeed' => 8, 'Power' => 218),
                    '	46	     ' => array('Windspeed' => 8.1, 'Power' => 229.2),
                    '	47	     ' => array('Windspeed' => 8.2, 'Power' => 240.4),
                    '	48	     ' => array('Windspeed' => 8.3, 'Power' => 251.6),
                    '	49	     ' => array('Windspeed' => 8.4, 'Power' => 262.8),
                    '	50	     ' => array('Windspeed' => 8.5, 'Power' => 274),
                    '	51	     ' => array('Windspeed' => 8.6, 'Power' => 285.2),
                    '	52	     ' => array('Windspeed' => 8.7, 'Power' => 296.4),
                    '	53	     ' => array('Windspeed' => 8.8, 'Power' => 307.6),
                    '	54	     ' => array('Windspeed' => 8.9, 'Power' => 318.8),
                    '	55	     ' => array('Windspeed' => 9, 'Power' => 330),
                    '	56	     ' => array('Windspeed' => 9.1, 'Power' => 338),
                    '	57	     ' => array('Windspeed' => 9.2, 'Power' => 346),
                    '	58	     ' => array('Windspeed' => 9.3, 'Power' => 354),
                    '	59	     ' => array('Windspeed' => 9.4, 'Power' => 362),
                    '	60	     ' => array('Windspeed' => 9.5, 'Power' => 370),
                    '	61	     ' => array('Windspeed' => 9.6, 'Power' => 378),
                    '	62	     ' => array('Windspeed' => 9.7, 'Power' => 386),
                    '	63	     ' => array('Windspeed' => 9.8, 'Power' => 394),
                    '	64	     ' => array('Windspeed' => 9.9, 'Power' => 402),
                    '	65	     ' => array('Windspeed' => 10, 'Power' => 410),
                    '	66	     ' => array('Windspeed' => 10.1, 'Power' => 416.3),
                    '	67	     ' => array('Windspeed' => 10.2, 'Power' => 422.6),
                    '	68	     ' => array('Windspeed' => 10.3, 'Power' => 428.9),
                    '	69	     ' => array('Windspeed' => 10.4, 'Power' => 435.2),
                    '	70	     ' => array('Windspeed' => 10.5, 'Power' => 441.5),
                    '	71	     ' => array('Windspeed' => 10.6, 'Power' => 447.8),
                    '	72	     ' => array('Windspeed' => 10.7, 'Power' => 454.1),
                    '	73	     ' => array('Windspeed' => 10.8, 'Power' => 460.4),
                    '	74	     ' => array('Windspeed' => 10.9, 'Power' => 466.7),
                    '	75	     ' => array('Windspeed' => 11, 'Power' => 473),
                    '	76	     ' => array('Windspeed' => 11.1, 'Power' => 478.9),
                    '	77	     ' => array('Windspeed' => 11.2, 'Power' => 484.8),
                    '	78	     ' => array('Windspeed' => 11.3, 'Power' => 490.7),
                    '	79	     ' => array('Windspeed' => 11.4, 'Power' => 496.6),
                    '	80	     ' => array('Windspeed' => 11.5, 'Power' => 502.5),
                    '	81	     ' => array('Windspeed' => 11.6, 'Power' => 508.4),
                    '	82	     ' => array('Windspeed' => 11.7, 'Power' => 514.3),
                    '	83	     ' => array('Windspeed' => 11.8, 'Power' => 520.2),
                    '	84	     ' => array('Windspeed' => 11.9, 'Power' => 526.1),
                    '	85	     ' => array('Windspeed' => 12, 'Power' => 532),
                    '	86	     ' => array('Windspeed' => 12.1, 'Power' => 535.2),
                    '	87	     ' => array('Windspeed' => 12.2, 'Power' => 538.4),
                    '	88	     ' => array('Windspeed' => 12.3, 'Power' => 541.6),
                    '	89	     ' => array('Windspeed' => 12.4, 'Power' => 544.8),
                    '	90	     ' => array('Windspeed' => 12.5, 'Power' => 548),
                    '	91	     ' => array('Windspeed' => 12.6, 'Power' => 551.2),
                    '	92	     ' => array('Windspeed' => 12.7, 'Power' => 554.4),
                    '	93	     ' => array('Windspeed' => 12.8, 'Power' => 557.6),
                    '	94	     ' => array('Windspeed' => 12.9, 'Power' => 560.8),
                    '	95	     ' => array('Windspeed' => 13, 'Power' => 564),
                    '	96	     ' => array('Windspeed' => 13.1, 'Power' => 565.8),
                    '	97	     ' => array('Windspeed' => 13.2, 'Power' => 567.6),
                    '	98	     ' => array('Windspeed' => 13.3, 'Power' => 569.4),
                    '	99	     ' => array('Windspeed' => 13.4, 'Power' => 571.2),
                    '	100	     ' => array('Windspeed' => 13.5, 'Power' => 573),
                    '	101	     ' => array('Windspeed' => 13.6, 'Power' => 574.8),
                    '	102	     ' => array('Windspeed' => 13.7, 'Power' => 576.6),
                    '	103	     ' => array('Windspeed' => 13.8, 'Power' => 578.4),
                    '	104	     ' => array('Windspeed' => 13.9, 'Power' => 580.2),
                    '	105	     ' => array('Windspeed' => 14, 'Power' => 582),
                    '	106	     ' => array('Windspeed' => 14.1, 'Power' => 583.5),
                    '	107	     ' => array('Windspeed' => 14.2, 'Power' => 585),
                    '	108	     ' => array('Windspeed' => 14.3, 'Power' => 586.5),
                    '	109	     ' => array('Windspeed' => 14.4, 'Power' => 588),
                    '	110	     ' => array('Windspeed' => 14.5, 'Power' => 589.5),
                    '	111	     ' => array('Windspeed' => 14.6, 'Power' => 591),
                    '	112	     ' => array('Windspeed' => 14.7, 'Power' => 592.5),
                    '	113	     ' => array('Windspeed' => 14.8, 'Power' => 594),
                    '	114	     ' => array('Windspeed' => 14.9, 'Power' => 595.5),
                    '	115	     ' => array('Windspeed' => 15, 'Power' => 597),
                    '	116	     ' => array('Windspeed' => 15.1, 'Power' => 597.3),
                    '	117	     ' => array('Windspeed' => 15.2, 'Power' => 597.6),
                    '	118	     ' => array('Windspeed' => 15.3, 'Power' => 597.9),
                    '	119	     ' => array('Windspeed' => 15.4, 'Power' => 598.2),
                    '	120	     ' => array('Windspeed' => 15.5, 'Power' => 598.5),
                    '	121	     ' => array('Windspeed' => 15.6, 'Power' => 598.8),
                    '	122	     ' => array('Windspeed' => 15.7, 'Power' => 599.1),
                    '	123	     ' => array('Windspeed' => 15.8, 'Power' => 599.4),
                    '	124	     ' => array('Windspeed' => 15.9, 'Power' => 599.7),
                    '	125	     ' => array('Windspeed' => 16, 'Power' => 600),
                    '	126	     ' => array('Windspeed' => 16.1, 'Power' => 600),
                    '	127	     ' => array('Windspeed' => 16.2, 'Power' => 600),
                    '	128	     ' => array('Windspeed' => 16.3, 'Power' => 600),
                    '	129	     ' => array('Windspeed' => 16.4, 'Power' => 600),
                    '	130	     ' => array('Windspeed' => 16.5, 'Power' => 600),
                    '	131	     ' => array('Windspeed' => 16.6, 'Power' => 600),
                    '	132	     ' => array('Windspeed' => 16.7, 'Power' => 600),
                    '	133	     ' => array('Windspeed' => 16.8, 'Power' => 600),
                    '	134	     ' => array('Windspeed' => 16.9, 'Power' => 600),
                    '	135	     ' => array('Windspeed' => 17, 'Power' => 600),
                    '	136	     ' => array('Windspeed' => 17.1, 'Power' => 600),
                    '	137	     ' => array('Windspeed' => 17.2, 'Power' => 600),
                    '	138	     ' => array('Windspeed' => 17.3, 'Power' => 600),
                    '	139	     ' => array('Windspeed' => 17.4, 'Power' => 600),
                    '	140	     ' => array('Windspeed' => 17.5, 'Power' => 600),
                    '	141	     ' => array('Windspeed' => 17.6, 'Power' => 600),
                    '	142	     ' => array('Windspeed' => 17.7, 'Power' => 600),
                    '	143	     ' => array('Windspeed' => 17.8, 'Power' => 600),
                    '	144	     ' => array('Windspeed' => 17.9, 'Power' => 600),
                    '	145	     ' => array('Windspeed' => 18, 'Power' => 600),
                    '	146	     ' => array('Windspeed' => 18.1, 'Power' => 600),
                    '	147	     ' => array('Windspeed' => 18.2, 'Power' => 600),
                    '	148	     ' => array('Windspeed' => 18.3, 'Power' => 600),
                    '	149	     ' => array('Windspeed' => 18.4, 'Power' => 600),
                    '	150	     ' => array('Windspeed' => 18.5, 'Power' => 600),
                    '	151	     ' => array('Windspeed' => 18.6, 'Power' => 600),
                    '	152	     ' => array('Windspeed' => 18.7, 'Power' => 600),
                    '	153	     ' => array('Windspeed' => 18.8, 'Power' => 600),
                    '	154	     ' => array('Windspeed' => 18.9, 'Power' => 600),
                    '	155	     ' => array('Windspeed' => 19, 'Power' => 600),
                    '	156	     ' => array('Windspeed' => 19.1, 'Power' => 600),
                    '	157	     ' => array('Windspeed' => 19.2, 'Power' => 600),
                    '	158	     ' => array('Windspeed' => 19.3, 'Power' => 600),
                    '	159	     ' => array('Windspeed' => 19.4, 'Power' => 600),
                    '	160	     ' => array('Windspeed' => 19.5, 'Power' => 600),
                    '	161	     ' => array('Windspeed' => 19.6, 'Power' => 600),
                    '	162	     ' => array('Windspeed' => 19.7, 'Power' => 600),
                    '	163	     ' => array('Windspeed' => 19.8, 'Power' => 600),
                    '	164	     ' => array('Windspeed' => 19.9, 'Power' => 600),
                    '	165	     ' => array('Windspeed' => 20, 'Power' => 600),
                    '	166	     ' => array('Windspeed' => 20.1, 'Power' => 600),
                    '	167	     ' => array('Windspeed' => 20.2, 'Power' => 600),
                    '	168	     ' => array('Windspeed' => 20.3, 'Power' => 600),
                    '	169	     ' => array('Windspeed' => 20.4, 'Power' => 600),
                    '	170	     ' => array('Windspeed' => 20.5, 'Power' => 600),
                    '	171	     ' => array('Windspeed' => 20.6, 'Power' => 600),
                    '	172	     ' => array('Windspeed' => 20.7, 'Power' => 600),
                    '	173	     ' => array('Windspeed' => 20.8, 'Power' => 600),
                    '	174	     ' => array('Windspeed' => 20.9, 'Power' => 600),
                    '	175	     ' => array('Windspeed' => 21, 'Power' => 600),
                    '	176	     ' => array('Windspeed' => 21.1, 'Power' => 600),
                    '	177	     ' => array('Windspeed' => 21.2, 'Power' => 600),
                    '	178	     ' => array('Windspeed' => 21.3, 'Power' => 600),
                    '	179	     ' => array('Windspeed' => 21.4, 'Power' => 600),
                    '	180	     ' => array('Windspeed' => 21.5, 'Power' => 600),
                    '	181	     ' => array('Windspeed' => 21.6, 'Power' => 600),
                    '	182	     ' => array('Windspeed' => 21.7, 'Power' => 600),
                    '	183	     ' => array('Windspeed' => 21.8, 'Power' => 600),
                    '	184	     ' => array('Windspeed' => 21.9, 'Power' => 600),
                    '	185	     ' => array('Windspeed' => 22, 'Power' => 600),
                    '	186	     ' => array('Windspeed' => 22.1, 'Power' => 600),
                    '	187	     ' => array('Windspeed' => 22.2, 'Power' => 600),
                    '	188	     ' => array('Windspeed' => 22.3, 'Power' => 600),
                    '	189	     ' => array('Windspeed' => 22.4, 'Power' => 600),
                    '	190	     ' => array('Windspeed' => 22.5, 'Power' => 600),
                    '	191	     ' => array('Windspeed' => 22.6, 'Power' => 600),
                    '	192	     ' => array('Windspeed' => 22.7, 'Power' => 600),
                    '	193	     ' => array('Windspeed' => 22.8, 'Power' => 600),
                    '	194	     ' => array('Windspeed' => 22.9, 'Power' => 600),
                    '	195	     ' => array('Windspeed' => 23, 'Power' => 600),
                    '	196	     ' => array('Windspeed' => 23.1, 'Power' => 600),
                    '	197	     ' => array('Windspeed' => 23.2, 'Power' => 600),
                    '	198	     ' => array('Windspeed' => 23.3, 'Power' => 600),
                    '	199	     ' => array('Windspeed' => 23.4, 'Power' => 600),
                    '	200	     ' => array('Windspeed' => 23.5, 'Power' => 600),
                    '	201	     ' => array('Windspeed' => 23.6, 'Power' => 600),
                    '	202	     ' => array('Windspeed' => 23.7, 'Power' => 600),
                    '	203	     ' => array('Windspeed' => 23.8, 'Power' => 600),
                    '	204	     ' => array('Windspeed' => 23.9, 'Power' => 600),
                    '	205	     ' => array('Windspeed' => 24, 'Power' => 600),
                    '	206	     ' => array('Windspeed' => 24.1, 'Power' => 600),
                    '	207	     ' => array('Windspeed' => 24.2, 'Power' => 600),
                    '	208	     ' => array('Windspeed' => 24.3, 'Power' => 600),
                    '	209	     ' => array('Windspeed' => 24.4, 'Power' => 600),
                    '	210	     ' => array('Windspeed' => 24.5, 'Power' => 600),
                    '	211	     ' => array('Windspeed' => 24.6, 'Power' => 600),
                    '	212	     ' => array('Windspeed' => 24.7, 'Power' => 600),
                    '	213	     ' => array('Windspeed' => 24.8, 'Power' => 600),
                    '	214	     ' => array('Windspeed' => 24.9, 'Power' => 600),
                    '	215	     ' => array('Windspeed' => 25, 'Power' => 600),
                );
                break;
            default:
                $carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 21),
                    '	6	     ' => array('Windspeed' => 4.1, 'Power' => 23.1),
                    '	7	     ' => array('Windspeed' => 4.2, 'Power' => 25.2),
                    '	8	     ' => array('Windspeed' => 4.3, 'Power' => 27.3),
                    '	9	     ' => array('Windspeed' => 4.4, 'Power' => 29.4),
                    '	10	     ' => array('Windspeed' => 4.5, 'Power' => 31.5),
                    '	11	     ' => array('Windspeed' => 4.6, 'Power' => 33.6),
                    '	12	     ' => array('Windspeed' => 4.7, 'Power' => 35.7),
                    '	13	     ' => array('Windspeed' => 4.8, 'Power' => 37.8),
                    '	14	     ' => array('Windspeed' => 4.9, 'Power' => 39.9),
                    '	15	     ' => array('Windspeed' => 5, 'Power' => 42),
                    '	16	     ' => array('Windspeed' => 5.1, 'Power' => 45.8),
                    '	17	     ' => array('Windspeed' => 5.2, 'Power' => 49.6),
                    '	18	     ' => array('Windspeed' => 5.3, 'Power' => 53.4),
                    '	19	     ' => array('Windspeed' => 5.4, 'Power' => 57.2),
                    '	20	     ' => array('Windspeed' => 5.5, 'Power' => 61),
                    '	21	     ' => array('Windspeed' => 5.6, 'Power' => 64.8),
                    '	22	     ' => array('Windspeed' => 5.7, 'Power' => 68.6),
                    '	23	     ' => array('Windspeed' => 5.8, 'Power' => 72.4),
                    '	24	     ' => array('Windspeed' => 5.9, 'Power' => 76.2),
                    '	25	     ' => array('Windspeed' => 6, 'Power' => 80),
                    '	26	     ' => array('Windspeed' => 6.1, 'Power' => 86.2),
                    '	27	     ' => array('Windspeed' => 6.2, 'Power' => 92.4),
                    '	28	     ' => array('Windspeed' => 6.3, 'Power' => 98.6),
                    '	29	     ' => array('Windspeed' => 6.4, 'Power' => 104.8),
                    '	30	     ' => array('Windspeed' => 6.5, 'Power' => 111),
                    '	31	     ' => array('Windspeed' => 6.6, 'Power' => 117.2),
                    '	32	     ' => array('Windspeed' => 6.7, 'Power' => 123.4),
                    '	33	     ' => array('Windspeed' => 6.8, 'Power' => 129.6),
                    '	34	     ' => array('Windspeed' => 6.9, 'Power' => 135.8),
                    '	35	     ' => array('Windspeed' => 7, 'Power' => 142),
                    '	36	     ' => array('Windspeed' => 7.1, 'Power' => 149.6),
                    '	37	     ' => array('Windspeed' => 7.2, 'Power' => 157.2),
                    '	38	     ' => array('Windspeed' => 7.3, 'Power' => 164.8),
                    '	39	     ' => array('Windspeed' => 7.4, 'Power' => 172.4),
                    '	40	     ' => array('Windspeed' => 7.5, 'Power' => 180),
                    '	41	     ' => array('Windspeed' => 7.6, 'Power' => 187.6),
                    '	42	     ' => array('Windspeed' => 7.7, 'Power' => 195.2),
                    '	43	     ' => array('Windspeed' => 7.8, 'Power' => 202.8),
                    '	44	     ' => array('Windspeed' => 7.9, 'Power' => 210.4),
                    '	45	     ' => array('Windspeed' => 8, 'Power' => 218),
                    '	46	     ' => array('Windspeed' => 8.1, 'Power' => 229.2),
                    '	47	     ' => array('Windspeed' => 8.2, 'Power' => 240.4),
                    '	48	     ' => array('Windspeed' => 8.3, 'Power' => 251.6),
                    '	49	     ' => array('Windspeed' => 8.4, 'Power' => 262.8),
                    '	50	     ' => array('Windspeed' => 8.5, 'Power' => 274),
                    '	51	     ' => array('Windspeed' => 8.6, 'Power' => 285.2),
                    '	52	     ' => array('Windspeed' => 8.7, 'Power' => 296.4),
                    '	53	     ' => array('Windspeed' => 8.8, 'Power' => 307.6),
                    '	54	     ' => array('Windspeed' => 8.9, 'Power' => 318.8),
                    '	55	     ' => array('Windspeed' => 9, 'Power' => 330),
                    '	56	     ' => array('Windspeed' => 9.1, 'Power' => 338),
                    '	57	     ' => array('Windspeed' => 9.2, 'Power' => 346),
                    '	58	     ' => array('Windspeed' => 9.3, 'Power' => 354),
                    '	59	     ' => array('Windspeed' => 9.4, 'Power' => 362),
                    '	60	     ' => array('Windspeed' => 9.5, 'Power' => 370),
                    '	61	     ' => array('Windspeed' => 9.6, 'Power' => 378),
                    '	62	     ' => array('Windspeed' => 9.7, 'Power' => 386),
                    '	63	     ' => array('Windspeed' => 9.8, 'Power' => 394),
                    '	64	     ' => array('Windspeed' => 9.9, 'Power' => 402),
                    '	65	     ' => array('Windspeed' => 10, 'Power' => 410),
                    '	66	     ' => array('Windspeed' => 10.1, 'Power' => 416.3),
                    '	67	     ' => array('Windspeed' => 10.2, 'Power' => 422.6),
                    '	68	     ' => array('Windspeed' => 10.3, 'Power' => 428.9),
                    '	69	     ' => array('Windspeed' => 10.4, 'Power' => 435.2),
                    '	70	     ' => array('Windspeed' => 10.5, 'Power' => 441.5),
                    '	71	     ' => array('Windspeed' => 10.6, 'Power' => 447.8),
                    '	72	     ' => array('Windspeed' => 10.7, 'Power' => 454.1),
                    '	73	     ' => array('Windspeed' => 10.8, 'Power' => 460.4),
                    '	74	     ' => array('Windspeed' => 10.9, 'Power' => 466.7),
                    '	75	     ' => array('Windspeed' => 11, 'Power' => 473),
                    '	76	     ' => array('Windspeed' => 11.1, 'Power' => 478.9),
                    '	77	     ' => array('Windspeed' => 11.2, 'Power' => 484.8),
                    '	78	     ' => array('Windspeed' => 11.3, 'Power' => 490.7),
                    '	79	     ' => array('Windspeed' => 11.4, 'Power' => 496.6),
                    '	80	     ' => array('Windspeed' => 11.5, 'Power' => 502.5),
                    '	81	     ' => array('Windspeed' => 11.6, 'Power' => 508.4),
                    '	82	     ' => array('Windspeed' => 11.7, 'Power' => 514.3),
                    '	83	     ' => array('Windspeed' => 11.8, 'Power' => 520.2),
                    '	84	     ' => array('Windspeed' => 11.9, 'Power' => 526.1),
                    '	85	     ' => array('Windspeed' => 12, 'Power' => 532),
                    '	86	     ' => array('Windspeed' => 12.1, 'Power' => 535.2),
                    '	87	     ' => array('Windspeed' => 12.2, 'Power' => 538.4),
                    '	88	     ' => array('Windspeed' => 12.3, 'Power' => 541.6),
                    '	89	     ' => array('Windspeed' => 12.4, 'Power' => 544.8),
                    '	90	     ' => array('Windspeed' => 12.5, 'Power' => 548),
                    '	91	     ' => array('Windspeed' => 12.6, 'Power' => 551.2),
                    '	92	     ' => array('Windspeed' => 12.7, 'Power' => 554.4),
                    '	93	     ' => array('Windspeed' => 12.8, 'Power' => 557.6),
                    '	94	     ' => array('Windspeed' => 12.9, 'Power' => 560.8),
                    '	95	     ' => array('Windspeed' => 13, 'Power' => 564),
                    '	96	     ' => array('Windspeed' => 13.1, 'Power' => 565.8),
                    '	97	     ' => array('Windspeed' => 13.2, 'Power' => 567.6),
                    '	98	     ' => array('Windspeed' => 13.3, 'Power' => 569.4),
                    '	99	     ' => array('Windspeed' => 13.4, 'Power' => 571.2),
                    '	100	     ' => array('Windspeed' => 13.5, 'Power' => 573),
                    '	101	     ' => array('Windspeed' => 13.6, 'Power' => 574.8),
                    '	102	     ' => array('Windspeed' => 13.7, 'Power' => 576.6),
                    '	103	     ' => array('Windspeed' => 13.8, 'Power' => 578.4),
                    '	104	     ' => array('Windspeed' => 13.9, 'Power' => 580.2),
                    '	105	     ' => array('Windspeed' => 14, 'Power' => 582),
                    '	106	     ' => array('Windspeed' => 14.1, 'Power' => 583.5),
                    '	107	     ' => array('Windspeed' => 14.2, 'Power' => 585),
                    '	108	     ' => array('Windspeed' => 14.3, 'Power' => 586.5),
                    '	109	     ' => array('Windspeed' => 14.4, 'Power' => 588),
                    '	110	     ' => array('Windspeed' => 14.5, 'Power' => 589.5),
                    '	111	     ' => array('Windspeed' => 14.6, 'Power' => 591),
                    '	112	     ' => array('Windspeed' => 14.7, 'Power' => 592.5),
                    '	113	     ' => array('Windspeed' => 14.8, 'Power' => 594),
                    '	114	     ' => array('Windspeed' => 14.9, 'Power' => 595.5),
                    '	115	     ' => array('Windspeed' => 15, 'Power' => 597),
                    '	116	     ' => array('Windspeed' => 15.1, 'Power' => 597.3),
                    '	117	     ' => array('Windspeed' => 15.2, 'Power' => 597.6),
                    '	118	     ' => array('Windspeed' => 15.3, 'Power' => 597.9),
                    '	119	     ' => array('Windspeed' => 15.4, 'Power' => 598.2),
                    '	120	     ' => array('Windspeed' => 15.5, 'Power' => 598.5),
                    '	121	     ' => array('Windspeed' => 15.6, 'Power' => 598.8),
                    '	122	     ' => array('Windspeed' => 15.7, 'Power' => 599.1),
                    '	123	     ' => array('Windspeed' => 15.8, 'Power' => 599.4),
                    '	124	     ' => array('Windspeed' => 15.9, 'Power' => 599.7),
                    '	125	     ' => array('Windspeed' => 16, 'Power' => 600),
                    '	126	     ' => array('Windspeed' => 16.1, 'Power' => 600),
                    '	127	     ' => array('Windspeed' => 16.2, 'Power' => 600),
                    '	128	     ' => array('Windspeed' => 16.3, 'Power' => 600),
                    '	129	     ' => array('Windspeed' => 16.4, 'Power' => 600),
                    '	130	     ' => array('Windspeed' => 16.5, 'Power' => 600),
                    '	131	     ' => array('Windspeed' => 16.6, 'Power' => 600),
                    '	132	     ' => array('Windspeed' => 16.7, 'Power' => 600),
                    '	133	     ' => array('Windspeed' => 16.8, 'Power' => 600),
                    '	134	     ' => array('Windspeed' => 16.9, 'Power' => 600),
                    '	135	     ' => array('Windspeed' => 17, 'Power' => 600),
                    '	136	     ' => array('Windspeed' => 17.1, 'Power' => 600),
                    '	137	     ' => array('Windspeed' => 17.2, 'Power' => 600),
                    '	138	     ' => array('Windspeed' => 17.3, 'Power' => 600),
                    '	139	     ' => array('Windspeed' => 17.4, 'Power' => 600),
                    '	140	     ' => array('Windspeed' => 17.5, 'Power' => 600),
                    '	141	     ' => array('Windspeed' => 17.6, 'Power' => 600),
                    '	142	     ' => array('Windspeed' => 17.7, 'Power' => 600),
                    '	143	     ' => array('Windspeed' => 17.8, 'Power' => 600),
                    '	144	     ' => array('Windspeed' => 17.9, 'Power' => 600),
                    '	145	     ' => array('Windspeed' => 18, 'Power' => 600),
                    '	146	     ' => array('Windspeed' => 18.1, 'Power' => 600),
                    '	147	     ' => array('Windspeed' => 18.2, 'Power' => 600),
                    '	148	     ' => array('Windspeed' => 18.3, 'Power' => 600),
                    '	149	     ' => array('Windspeed' => 18.4, 'Power' => 600),
                    '	150	     ' => array('Windspeed' => 18.5, 'Power' => 600),
                    '	151	     ' => array('Windspeed' => 18.6, 'Power' => 600),
                    '	152	     ' => array('Windspeed' => 18.7, 'Power' => 600),
                    '	153	     ' => array('Windspeed' => 18.8, 'Power' => 600),
                    '	154	     ' => array('Windspeed' => 18.9, 'Power' => 600),
                    '	155	     ' => array('Windspeed' => 19, 'Power' => 600),
                    '	156	     ' => array('Windspeed' => 19.1, 'Power' => 600),
                    '	157	     ' => array('Windspeed' => 19.2, 'Power' => 600),
                    '	158	     ' => array('Windspeed' => 19.3, 'Power' => 600),
                    '	159	     ' => array('Windspeed' => 19.4, 'Power' => 600),
                    '	160	     ' => array('Windspeed' => 19.5, 'Power' => 600),
                    '	161	     ' => array('Windspeed' => 19.6, 'Power' => 600),
                    '	162	     ' => array('Windspeed' => 19.7, 'Power' => 600),
                    '	163	     ' => array('Windspeed' => 19.8, 'Power' => 600),
                    '	164	     ' => array('Windspeed' => 19.9, 'Power' => 600),
                    '	165	     ' => array('Windspeed' => 20, 'Power' => 600),
                    '	166	     ' => array('Windspeed' => 20.1, 'Power' => 600),
                    '	167	     ' => array('Windspeed' => 20.2, 'Power' => 600),
                    '	168	     ' => array('Windspeed' => 20.3, 'Power' => 600),
                    '	169	     ' => array('Windspeed' => 20.4, 'Power' => 600),
                    '	170	     ' => array('Windspeed' => 20.5, 'Power' => 600),
                    '	171	     ' => array('Windspeed' => 20.6, 'Power' => 600),
                    '	172	     ' => array('Windspeed' => 20.7, 'Power' => 600),
                    '	173	     ' => array('Windspeed' => 20.8, 'Power' => 600),
                    '	174	     ' => array('Windspeed' => 20.9, 'Power' => 600),
                    '	175	     ' => array('Windspeed' => 21, 'Power' => 600),
                    '	176	     ' => array('Windspeed' => 21.1, 'Power' => 600),
                    '	177	     ' => array('Windspeed' => 21.2, 'Power' => 600),
                    '	178	     ' => array('Windspeed' => 21.3, 'Power' => 600),
                    '	179	     ' => array('Windspeed' => 21.4, 'Power' => 600),
                    '	180	     ' => array('Windspeed' => 21.5, 'Power' => 600),
                    '	181	     ' => array('Windspeed' => 21.6, 'Power' => 600),
                    '	182	     ' => array('Windspeed' => 21.7, 'Power' => 600),
                    '	183	     ' => array('Windspeed' => 21.8, 'Power' => 600),
                    '	184	     ' => array('Windspeed' => 21.9, 'Power' => 600),
                    '	185	     ' => array('Windspeed' => 22, 'Power' => 600),
                    '	186	     ' => array('Windspeed' => 22.1, 'Power' => 600),
                    '	187	     ' => array('Windspeed' => 22.2, 'Power' => 600),
                    '	188	     ' => array('Windspeed' => 22.3, 'Power' => 600),
                    '	189	     ' => array('Windspeed' => 22.4, 'Power' => 600),
                    '	190	     ' => array('Windspeed' => 22.5, 'Power' => 600),
                    '	191	     ' => array('Windspeed' => 22.6, 'Power' => 600),
                    '	192	     ' => array('Windspeed' => 22.7, 'Power' => 600),
                    '	193	     ' => array('Windspeed' => 22.8, 'Power' => 600),
                    '	194	     ' => array('Windspeed' => 22.9, 'Power' => 600),
                    '	195	     ' => array('Windspeed' => 23, 'Power' => 600),
                    '	196	     ' => array('Windspeed' => 23.1, 'Power' => 600),
                    '	197	     ' => array('Windspeed' => 23.2, 'Power' => 600),
                    '	198	     ' => array('Windspeed' => 23.3, 'Power' => 600),
                    '	199	     ' => array('Windspeed' => 23.4, 'Power' => 600),
                    '	200	     ' => array('Windspeed' => 23.5, 'Power' => 600),
                    '	201	     ' => array('Windspeed' => 23.6, 'Power' => 600),
                    '	202	     ' => array('Windspeed' => 23.7, 'Power' => 600),
                    '	203	     ' => array('Windspeed' => 23.8, 'Power' => 600),
                    '	204	     ' => array('Windspeed' => 23.9, 'Power' => 600),
                    '	205	     ' => array('Windspeed' => 24, 'Power' => 600),
                    '	206	     ' => array('Windspeed' => 24.1, 'Power' => 600),
                    '	207	     ' => array('Windspeed' => 24.2, 'Power' => 600),
                    '	208	     ' => array('Windspeed' => 24.3, 'Power' => 600),
                    '	209	     ' => array('Windspeed' => 24.4, 'Power' => 600),
                    '	210	     ' => array('Windspeed' => 24.5, 'Power' => 600),
                    '	211	     ' => array('Windspeed' => 24.6, 'Power' => 600),
                    '	212	     ' => array('Windspeed' => 24.7, 'Power' => 600),
                    '	213	     ' => array('Windspeed' => 24.8, 'Power' => 600),
                    '	214	     ' => array('Windspeed' => 24.9, 'Power' => 600),
                    '	215	     ' => array('Windspeed' => 25, 'Power' => 600),
                );
        }
        return $carray;
    }

    function calculate_gad_perf($selector, $date) {

        $gad_typelist = $this->Common_model->getDeviceList('', 1);



        $full_gad_device = array();
        $val = array();
        $value = "";
        foreach ($gad_typelist as $list) {


            if ($selector == "date") {
                $value = 1;
            } else if ($selector == "month") {
                $value = 30;
            } else if ($selector == "year") {
                $value = 365;
            }



            $initial_gad_val = $this->Common_model->get_gad_perf($list->Format_Type, $list->IMEI, $selector, $date, 1);
            $final_gad_val = $this->Common_model->get_gad_perf($list->Format_Type, $list->IMEI, $selector, $date, 2);

            if ($initial_gad_val > $final_gad_val) {
                $gad = $initial_gad_val - $final_gad_val;
            } else {
                $gad = $final_gad_val - $initial_gad_val;
            }

            $text = "";
            switch ($list->Format_Type) {
                case 1:
                    if ($gad > 15000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 2:
                    if ($gad > 6000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 3:
                    if ($gad > 15000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 4:
                    if ($gad > 15000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 6:
                    if ($gad > 15000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 7:
                    if ($gad > 15000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 8:
                    if ($gad > 6000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case 10:
                    if ($gad > 6000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
            }



            $val = ['gad' => $gad, 'device_name' => $list->Device_Name];
            array_push($full_gad_device, $val);
        }

//        print_r($full_gad_device);
//       die;
        return $full_gad_device;
    }

    public function get_gad_perf($type, $imei, $selector, $date, $no) {
        /*
         * calculation difference between time period between 00:00 to 23:59
         * Type 2 & 4 consider both gen1 & gen2 
         * Type 1 & 6 consider gen2
         * Type 3 & 10 consider production_total
         */
        $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);

        $data = array();
        //$date = date("Y-m-d");
        // $date = '2019-04-13';
        $gad_gen = 0;
        $year = "";

        if ($dev_type == 2 || $dev_type == 4) {
            $this->db2->select('PAT_Gen1 as gad_gen1,PAT_Gen2 as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else if ($dev_type == 6 || $dev_type == 1) {
            $this->db2->select('PAT_Gen2 as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else if ($dev_type == 8 || $dev_type == 7) {
            $this->db2->select('Kwh_Positive as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else {
            $this->db2->select('Production_Total as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        }

        if ($selector == "date") {
            $date = date("Y-m-d", strtotime($date));
            $this->db2->where('Date_S', $date);
        } else if ($selector == "month") {
            $this->db2->like('Date_S', $date, 'after');
        } else if ($selector == "year") {
            if ($no == 1) {
                $year = $date . "-03";
            } else {
                $date = $date + 1;
                $year = $date . "-04";
            }
            $this->db2->like('Date_S', $year, 'after');
        }


        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }

        if ($no == 1 && $selector != "date") {
            $this->db2->order_by('Date_S', 'ASC');
        } else if ($no == 2 && $selector != "date") {
            $this->db2->order_by('Date_S', 'DESC');
        }



        if ($no == 1) {
            $this->db2->order_by('Time_S', 'ASC');
        } else {
            $this->db2->order_by('Time_S', 'DESC');
        }
        $this->db2->limit(1);
        $query = $this->db2->get();
//       echo $this->db2->last_query();
//        die;
        foreach ($query->result_array() as $row) {
            if ($dev_type == 2 || $dev_type == 4) {
                $row['gad_gen'] = $row['gad_gen1'] + $row['gad_gen'];
            }
            $data = $row;
        }

        if (isset($data['gad_gen'])) {
            $gad_gen = $data['gad_gen'];
        }

        //print_r($data);die;
        return $gad_gen;
    }

    function getCountpwReport($type, $imei, $start_date, $end_date) {

        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $this->db2->select('Date_S,Time_S,Windspeed,Power')->from('device_data' . $type);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
        return $query->num_rows();
    }

    function getpwReport($type, $imei, $start_date, $end_date, $limit, $start) {
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();

        //$date = '2019-04-13';
        $this->db2->select('Date_S,Time_S,Windspeed,Power')->from('device_data' . $type);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        if ($limit) {
            $this->db2->limit($limit, $start);
        }
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    function getpwReportPdf($type, $imei, $start_date, $end_date, $limit, $start) {
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();

        //$date = '2019-04-13';
        $this->db2->select('Date_S,Time_S,Windspeed,Power')->from('device_data' . $type);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        if ($limit) {
            $this->db2->limit($limit, $start);
        }
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $row['Site_Location'] = $this->Common_model->commonDataFetching($imei, 'Site_Location');
            $row['HTSC_No'] = $this->Common_model->commonDataFetching($imei, 'HTSC_No');
            $data[] = $row;
        }
        return $data;
    }

    function get_region_site_list_new() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');

        $this->db->select('Account_ID,Site_Location,Region, Device_Name, Format_Type,IMEI, LOC_No, capacity, Connect_Feeder')
                ->where('Account_ID', $Account_ID)
                ->where("Region!=''");

//	->group_by('Region,Site_Location');
        $query = $this->db->get('device_register');

        foreach ($query->result_array() as $row) {
            $device_info = $this->Common_model->get_device_data_details_new($row['Format_Type'], $row['IMEI']);
            $error_info = $this->Common_model->get_error_data_Info_new($row['Format_Type'], $row['IMEI']);
            $device_info['Device_Name'] = $row['Device_Name'];
            $device_info['LOC_No'] = $row['LOC_No'];
            $device_info['capacity'] = $row['capacity'];
            $device_info['Connect_Feeder'] = $row['Connect_Feeder'];
            $device_info['Region'] = $row['Region'];
            $row['device_info'] = $device_info;
            $row['error_info'] = $error_info;
            $data[] = $row;
        }
        return $data;
    }

    function get_device_data_details_new($type, $imei, $search = array()) {
        //skip for format type 1
        $date = date('Y-m-d');
        $ftype = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        if ($ftype == 2) {
            $this->db2->select('Windspeed,RRPM,GRPM,RPhase_Volt,Power,Status')->from('device_data' . $type);
        } else if ($ftype == 3) {
            $this->db2->select('Windspeed,RRPM,GRPM,RPhase_Volt,Power,Status,Frequency,Gen1_Temp')->from('device_data' . $type);
        } else if ($ftype == 4) {
            $this->db2->select('Windspeed,RRPM,GRPM,RPhase_Volt,Power,Status,Frequency,Gen1_Temp')->from('device_data' . $type);
        } else if ($ftype == 6) {
            $this->db2->select('Windspeed,RRPM,GRPM,RPhase_Volt,Power,Status,Frequency,Gen1_Temp')->from('device_data' . $type);
        } else if ($ftype == 7) {
            $this->db2->select('Windspeed,RRPM,GRPM,Power,Status,Frequency,Gen1_Temp')->from('device_data' . $type);
        } else if ($ftype == 8) {
            $this->db2->select('Windspeed,RRPM,GRPM,Power,Status,Frequency')->from('device_data' . $type);
        } else {
            $this->db2->select('Windspeed,RRPM,GRPM,Pitch,Frequency,RPhase_Volt,Gen1_Temp,Power,Status')->from('device_data' . $type);
        }
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $this->db2->order_by('Record_Index', 'DESC');
        $this->db2->limit(1);
        $this->db2->where('Date_S', $date);
        // $this->db2->where('Date_S <=', $date);
        //   $this->db2->where("DATE_FORMAT(Date_S,'%y-%m-%d') BETWEEN DATE('" . $date . "') AND DATE('" . $date . "') ");

        $query = $this->db2->get();
        return $query->result_array();
    }

    function get_error_data_Info_new($type, $imei) {
        //skip for format type 1
        $date = date('Y-m-d');
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $this->db2->select('Date_S,Time_S,Status')->from('error_data' . $type);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $this->db2->order_by('Record_Index', 'DESC');
        $this->db2->limit(5);
        $this->db2->where('Date_S', $date);
        $query = $this->db2->get();
        return $query->result_array();
    }

    function colorCurrentDatavani($typelist) {
        $cval = array();
        foreach ($typelist as $list) {
           // echo $list->Format_Type;die;
            $color_val = $this->Common_model->get_color_machine_vani($list->Format_Type, $list->IMEI);

            if ($color_val) {
                $val = ['color_val' => $color_val, 'imei' => $list->IMEI, 'col' => "current"];
            } else {
                 $val = ['color_val' => "grey", 'imei' => $list->IMEI, 'col' => $color_val];
                }
            array_push($cval, $val);
        }
        return $cval;
    }
	
    function get_status_machine_new($type, $imei, $cond = "") {
        $data = "";
        date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d");

        if ($cond) {
            $min = date("H:i", strtotime("-30 minutes"));
            $min = substr_replace($min, "", -1);
        } else {
            $min = date("H:i");
            $min = substr_replace($min, "", -1);
        }
        $this->db->select('status')->from('current_data');
        //$this->db->where('date_s', $date);
        //$this->db->where('type', $type);
        $this->db->like('time_s', $min, 'after');

        $this->db->order_by('createdon', 'DESC');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        foreach ($query->result_array() as $row) {
            $data = $row['status'];
        }
        return $data;
    }
function get_time_stamp($Date, $Time){
  $day   = substr($Date,8,2);
  $month = substr($Date,5,2);
  $year  = substr($Date,0,4);

  $hour  = substr($Time,0,2);
  $mins  = substr($Time,3,2);
  $secs  = substr($Time,6,2);

  $timestamp = gmmktime($hour,$mins,$secs,$month,$day,$year);
  return $timestamp;
}
	
	function get_color_machine_vani($type, $imei) {
        $data = "";
		 $green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running','M/CRunning','Power Up','FreeWheeling','FreewheelingG1', 'FreewheelingG2', 'FreeWheelingG1', 'FreeWheelingG2');
         $blue_array = array('GRIDDROP', 'Grid Spike', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop');
            
        date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d");
        $this->db->select('status,Date_S,Time_S')->from('current_data');
        $this->db->order_by('createdon', 'DESC');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
		
        foreach ($query->result_array() as $row) {
            $datastat = $row['status'];	
			$datadate = $row['Date_S'];
			$datatime = $row['Time_S'];
			$epoch_time = $this->Common_model->get_time_stamp($datadate,$datatime);
			$epoch_diff = time() - $epoch_time;
			if ($epoch_diff >= 1800 && (in_array($datastat,$green_array) && !in_array($datastat,$blue_array))) {
				$data = "grey";
			}
			else
				{
					if(in_array($datastat,$green_array)){
						$data = "green";
					}
					elseif (in_array($datastat,$blue_array)) {
						$data = "blue";
					}
					else {
						$data = "red";
					}
				}
												
        }		
        return $data;
    }
		

    function statusCurrentDataCount() {
		$Account_ID = $this->session->userdata('account_id');
        $this->db->select('status')->from('current_data')
					->where('Account_ID', $Account_ID);
        $query = $this->db->get();
        return count($query->result_array());
    }

    function getImeiAll() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');

        $this->db->select('IMEI')
                ->where('Account_ID', $Account_ID);

        $query = $this->db->get('device_register');
        foreach ($query->result_array() as $row) {
            $result[] = $row['IMEI'];
        }
        return $result;
    }

    function getCurrentImeiAll() {
        $result = array();
		$Account_ID = $this->session->userdata('account_id');
        $this->db->select('IMEI')
					->where('Account_ID', $Account_ID);
        $query = $this->db->get('current_data');
        foreach ($query->result_array() as $row) {
            $result[] = $row['IMEI'];
        }
        return $result;
    }

    function getStatusDetailsGray($imeilist) {
        // print_r($imeilist);die;
        $sval = array();
        foreach ($imeilist as $list) {

            $val = $this->Common_model->get_status_details_new("", $list);
            array_push($sval, $val);
        }
        return $sval;
    }

    function getCurrentList() {
        $result = array();

        $this->db->select('IMEI,type');
        $query = $this->db->get('current_data');
        foreach ($query->result_array() as $row) {
            $result[] = $row;
        }
        return $result;
    }

    function getParkviewData() {
        $result = array();
        $date = date("Y-m-d");

        $green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running', 'Power Up');
        $blue_array = array('GRIDDROP', 'Grid Spike', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop', 'GridD?ox', 'GridD?op', 'GvidDrop', 'Grid In Gen.Brk Trip');
        $red_array = array('Parameter Crash Error', 'Circuits Braker Fault', 'Timeout Braking Error', 'Timeout Braking Error', 'Lightning Prot. Error', 'Hub Motor Error', 'Current Asymmet. Error', 'Yaw Sensor Def. Error', 'WP-2060 Error', 'Frequency Error', 'Host-Slave Commn. Error', 'Frequency Error', 'Over Voltage Error', 'Autorewind Error', 'Yaw Motor OLR Error', 'Bypass missing Error', 'R-S-T 120 Error', 'Cut.In G1>G2 Error', 'Calip Pressure High Error', 'PS Caliper Error', 'Gener. Overspeed Error', 'Parameter Crash Error', 'Calip Pressure High Error', 'Autorewind Error');

        //$date = '2019-09-28';
        $this->db->select('date_s,time_s,devicedata,type,IMEI,status');
        $this->db->where('date_s', $date);
        $query = $this->db->get('current_data');

        foreach ($query->result_array() as $row) {


            if (in_array($row['status'], $green_array)) {

                $row['çolor'] = "#008000";
            } else if (in_array($row['status'], $blue_array)) {
                $row['çolor'] = "#0000FF";
            } else {
                $row['çolor'] = "#FF0000";
            }
            $devicedata = explode(',', $row['devicedata']);
            $devicedataCount = count($devicedata);
            if ($devicedataCount < 20) {
                $devicedata = array();
            }
            $row['state'] = $this->Common_model->commonDataFetching($row['IMEI'], 'State');
            $row['site'] = $this->Common_model->commonDataFetching($row['IMEI'], 'Site_Location');
            $row['htsc'] = $this->Common_model->commonDataFetching($row['IMEI'], 'HTSC_No');
            $row['region'] = $this->Common_model->commonDataFetching($row['IMEI'], 'Region');
            $row['device_name'] = $this->Common_model->commonDataFetching($row['IMEI'], 'Device_Name');
            $row['device_order'] = $this->Common_model->commonDataFetching($row['IMEI'], 'Device_Order');
            switch ($row['type']) {
                case 1:
                    //what will do here
                    $row['power'] = isset($devicedata[13]) ? $devicedata[13] : 0;
                    $row['windpeed'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 2:
                    $row['power'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['windpeed'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    $row['grpm'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['rrpm'] = isset($devicedata[9]) ? $devicedata[9] : 0;
                    break;
                case 3:
                    $row['power'] = isset($devicedata[9]) ? $devicedata[9] : 0;
                    $row['windpeed'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 4:
                    $row['power'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['windpeed'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    $row['grpm'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['rrpm'] = isset($devicedata[9]) ? $devicedata[9] : 0;
                    break;
                case 6:
                    $row['power'] = isset($devicedata[13]) ? $devicedata[13] : 0;
                    $row['windpeed'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 7:
                    $row['power'] = isset($devicedata[11]) ? $devicedata[11] : 0;
                    $row['windpeed'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 8:
                    $row['power'] = isset($devicedata[11]) ? $devicedata[11] : 0;
                    $row['windpeed'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['grpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    $row['rrpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    break;
                case 10:
                    $row['power'] = isset($devicedata[13]) ? $devicedata[13] : 0;
                    $row['windpeed'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
            }


            $result[] = $row;
        }
        return $result;
    }

    function getCountDeviceList() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
        $this->db->select('IMEI')
                ->where('Account_ID', $Account_ID);
        $query = $this->db->get('device_register');
        return $this->db->count_all_results();
    }

    function getLeftParkviewData($left_imei) {
        $lval = array();
        foreach ($left_imei as $list) {
            $type = $this->Common_model->commonDataFetching($list, 'Format_Type');
            $val = $this->Common_model->getLeftParkview($type, $list);
            if ($val) {
                array_push($lval, $val);
            }
        }
        //print_r($lval);die;
        return $lval;
    }

    function getLeftParkview($type, $imei) {
        $data = array();
         $date = date("Y-m-d");
       // $date = '2019-09-28';

        $dev_type = $type;

        ($type == 1 ? $type = "" : $type = "_f" . $type);

        $this->db2->select('Windspeed as windpeed,Power as power,GRPM as grpm,RRPM as rrpm,Status as status,Date_S,Time_S')->from('device_data' . $type);

        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $this->db2->order_by('Time_S', 'DESC');

        $this->db2->limit(1);

        $query = $this->db2->get();
        //echo $this->db2->last_query();die;

        foreach ($query->result_array() as $row) {
            $row['state'] = $this->Common_model->commonDataFetching($imei, 'State');
            $row['site'] = $this->Common_model->commonDataFetching($imei, 'Site_Location');
            $row['htsc'] = $this->Common_model->commonDataFetching($imei, 'HTSC_No');
            $row['region'] = $this->Common_model->commonDataFetching($imei, 'Region');
            $row['device_name'] = $this->Common_model->commonDataFetching($imei, 'Device_Name');
            $row['device_order'] = $this->Common_model->commonDataFetching($imei, 'Device_Order');
            $row['çolor'] = "#808080";
            $data = $row;
        }
        // print_r($data);die;
        // usort($data, 'compareByName');
        return $data;
    }

    function getDevtotype() {
        $result = "";

        $this->db->select('IMEI')
                ->where('Account_ID', $Account_ID);

        $query = $this->db->get('device_register');
        foreach ($query->result_array() as $row) {
            $result[] = $row['IMEI'];
        }
        return $result;
    }

    function fetch_temp($devname = "", $type = "", $imei = "", $date = "", $temp = "") {

        $data = array();

        // $date = date("Y-m-d", strtotime($date) );
         $date = date("Y-m-d");
      //  $date = '2019-02-12';

        $dev_type = $type;

        ($type == 1 ? $type = "" : $type = "_f" . $type);

        $this->db2->select("$temp,Time_S")->from('device_data' . $type);

        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        // $this->db2->order_by('Time_S', 'DESC');
        // $this->db2->limit(1);

        $query = $this->db2->get();
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        // print_r($data);die;
        // usort($data, 'compareByName');
        return $data;
    }

    function getStatusDetails($imeilist) {
        // print_r($imeilist);die;
        $sval = array();
        foreach ($imeilist as $key => $list) {

            $val = $this->Common_model->get_status_details_new($list['status_val'], $list['imei'], $list['stat']);
            array_push($sval, $val);
        }
        return $sval;
    }

    function get_status_details_new($status_val, $imei, $stat) {
        $data = array();
        $this->db->select("IMEI as imei,Device_Name as device_name,State as state,Site_Location as site,HTSC_No as htsc,Region as region,Format_Type as type")->from('device_register');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            if ($status_val == "grey" && $stat != "novalue") {
                $row['status_val'] = $stat;
            } else {
                $row['status_val'] = $status_val;
            }


            if ($status_val == "grey" && $stat == "novalue") {
                $row['wind_speed'] = $row['power'] = $row['gad'] = 0;
                $row['Date_S'] = date("Y-m-d");
                $row['Time_S'] = date('H:i');
            } else {
                $row['status_val'] = $status_val;
                $row['wind_speed'] = $this->Common_model->getNewCurrentData($row['type'], $imei, 'windspeed');
                $row['power'] = $this->Common_model->getNewCurrentData($row['type'], $imei, 'power');
                $row['Date_S'] = date("Y-m-d");
                $row['Time_S'] = $this->Common_model->getNewCurrentData($row['type'], $imei, 'time_s');
//                $initial_gad_val = $this->Common_model->get_gad($row['type'], $imei, 1);
//                $final_gad_val = $this->Common_model->get_gad($row['type'], $imei, 2);
//                if ($initial_gad_val > $final_gad_val) {
//                    $gad = $initial_gad_val - $final_gad_val;
//                } else {
//                    $gad = $final_gad_val - $initial_gad_val;
//                }
//
//                switch ($row['type']) {
//                    case 1:
//                        if ($gad > 15000) {
//                            $gad = 0;
//                        }
//                        break;
//                    case 2:
//                        if ($gad > 6000) {
//                            $gad = 0;
//                        }
//                        break;
//                    case 3:
//                        if ($gad > 15000) {
//                            $gad = 0;
//                        }
//                        break;
//                    case 4:
//                        if ($gad > 15000) {
//                            $gad = 0;
//                        }
//                        break;
//                    case 6:
//                        if ($gad > 15000) {
//                            $gad = 0;
//                        }
//                        break;
//                    case 7:
//                        if ($gad > 15000) {
//                            $gad = 0;
//                        }
//                        break;
//                    case 8:
//                        if ($gad > 6000) {
//                            $gad = 0;
//                        }
//                        break;
//                    case 10:
//                        if ($gad > 6000) {
//                            $gad = 0;
//                        }
//                        break;
//                }
                $row['gad'] = 0;
            }




            $data = $row;
        }
        return $data;
    }

    public function getNewCurrentData($type, $imei, $field) {

        $data = 0;
        $date = date("Y-m-d");
        //$date = '2019-04-13';

        $this->db->select("$field")->from('current_data');

        $this->db->where('Date_S', $date);

        $this->db->order_by('Time_S', 'DESC');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        // echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data = $row["$field"];
        }
        return $data;
    }

}

?>
