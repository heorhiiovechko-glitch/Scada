<?php
Class Common_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->db2 = $this->load->database($this->set_db_config(), TRUE);
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
	
	public function getbasicInfo($dev_name) {

        $data = array();
        $this->db->select("IMEI,capacity,Format_Type,Device_Name,Region,Site_Location,State,Connect_Feeder")->from('device_register');
        if (!empty($dev_name)) {
            $this->db->where('Device_Name', $dev_name);
        }
        $query = $this->db->get();
		//echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
			
            $data[] = $row;
        }
		
				
        return $data;
    }
	
	public function getbasicInfoimei($imei) {

        $data = array();
        $this->db->select("IMEI,capacity,Format_Type,Device_Name,Region,Site_Location,State,Connect_Feeder")->from('device_register');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $query = $this->db->get();
		//echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
			
            $data[] = $row;
        }
		
				
        return $data;
    }
	
	function getImeiAll() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');

		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('IMEI')
                ->where('Parent_ID', $Parent_ID);
		} else {
		$this->db->select('IMEI')
                ->where('Account_ID', $Account_ID);
		}
		$this->db->order_by('Device_Order', 'ASC');
        $query = $this->db->get('device_register');
        foreach ($query->result_array() as $row) {
            $result[] = $row['IMEI'];
        }
        return $result;
    }
	
	function getDevnameAll() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');
		
		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('Device_Name')
                ->where('Parent_ID', $Parent_ID);
		} else {
			$this->db->select('Device_Name')
                ->where('Account_ID', $Account_ID);
		}
		$this->db->order_by('Device_Order', 'ASC');
        $query = $this->db->get('device_register');
        foreach ($query->result_array() as $row) {
            $result[] = $row['Device_Name'];
        }
        return $result;
    }

    function getCurrentImeiAll() {
        $result = array();
		$Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');
		
        if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('IMEI')
                ->where('Parent_ID', $Parent_ID);
		} else {
		$this->db->select('IMEI')
                ->where('Account_ID', $Account_ID);
		}
		$this->db->order_by('Device_Order', 'ASC');
		$query = $this->db->get('device_register');
        foreach ($query->result_array() as $row) {
            $result[] = $row['IMEI'];
        }
        return $result;
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
    
    function getCountDeviceList() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id'); 
        if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('IMEI')
                ->where('Parent_ID', $Parent_ID);
		} else {
		$this->db->select('IMEI')
                ->where('Account_ID', $Account_ID);
		}
		$query = $this->db->get('device_register');
        return $this->db->count_all_results();
    }

    function getDevtotype() {
        $result = "";

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id'); 
        if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('IMEI')
                ->where('Parent_ID', $Parent_ID);
		} else {
		$this->db->select('IMEI')
                ->where('Account_ID', $Account_ID);
		}
		$this->db->order_by('Device_Order', 'ASC');
        $query = $this->db->get('device_register');
        foreach ($query->result_array() as $row) {
            $result[] = $row['IMEI'];
        }
        return $result;
    }

	function get_region_site_list() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');

		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('Account_ID,Site_Location,Region, Device_Name, Format_Type,IMEI, LOC_No, capacity, Connect_Feeder')
                ->where('Parent_ID', $Parent_ID)
				->where("Region!=''");
		} else { 
        $this->db->select('Account_ID,Site_Location,Region, Device_Name, Format_Type,IMEI, LOC_No, capacity, Connect_Feeder')
                ->where('Account_ID', $Account_ID)
				->where("Region!=''");
		}
				

				$this->db->order_by('Device_Order', 'ASC');

        //	->group_by('Region,Site_Location');
        $query = $this->db->get('device_register');
        return $query->result_array();
    }
	
	function get_feeder_list() {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');

		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('distinct (Connect_Feeder),State')
                ->where('Parent_ID', $Parent_ID)
				->where("Region!=''");
		} else { 
		$this->db->select('distinct (Connect_Feeder),State')
                ->where('Account_ID', $Account_ID)
				->where("Region!=''");
		}
                
				$this->db->order_by('Connect_Feeder', 'ASC');

        //	->group_by('Region,Site_Location');
        $query = $this->db->get('device_register');
        return $query->result_array();
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

    function get_device_list_by_given_imei($imei = '') {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');

		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('Site_Location,Region, Device_Name, Format_Type,IMEI, LOC_No, capacity, Connect_Feeder')
                ->where('Parent_ID', $Parent_ID)
				->where("Region!=''");
		} else { 
		$this->db->select('Site_Location,Region, Device_Name, Format_Type,IMEI, LOC_No, capacity, Connect_Feeder')
                ->where('Account_ID', $Account_ID)
				->where("Region!=''");
		}
                
        if (!empty($imei)) {
            $this->db->where_in('IMEI', $imei);
        }
        //	->group_by('Region,Site_Location');
        $query = $this->db->get('device_register');
        return $query->row_array();
    }
	
	function getDeviceList($device_name = '', $asc = '') {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');
		
		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('IMEI, Device_Name, State, Region, Format_Type , (SELECT  count(*) as cnt FROM `device_register` WHERE `Parent_ID` = ' . $Parent_ID . ') as cnt')
                ->where('Parent_ID', $Parent_ID);
		} else {
		 $this->db->select('IMEI, Device_Name, State, Region, Format_Type , (SELECT  count(*) as cnt FROM `device_register` WHERE `Account_ID` = ' . $Account_ID . ') as cnt')
                ->where('Account_ID', $Account_ID);
		}	
        if (!empty($device_name)) {
            $this->db->where_in('Device_Name', $device_name);
        }
        if ($asc == 1) {
            $this->db->order_by('LENGTH(Device_Name)', 'ASC');
            $this->db->order_by('Device_Name', 'ASC');
        } else {
			$this->db->order_by('Device_Order', 'ASC');
		}

        $query = $this->db->get('device_register');
         //echo $this->db->last_query();die;
        return $query->result();
    }

	function getDeviceFeederList($connect_feeder = '', $state = '', $asc = '') {
        $result = array();

        $Account_ID = $this->session->userdata('account_id');
		$Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');

		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('IMEI, Device_Name, Format_Type , Connect_Feeder, State, Region, (SELECT  count(*) as cnt FROM `device_register` WHERE `Parent_ID` = ' . $Parent_ID . ') as cnt')
                ->where('Parent_ID', $Parent_ID);
		} else {
		$this->db->select('IMEI, Device_Name, Format_Type , Connect_Feeder, State, Region, (SELECT  count(*) as cnt FROM `device_register` WHERE `Account_ID` = ' . $Account_ID . ') as cnt')
                ->where('Account_ID', $Account_ID);
        }
        $this->db->where_in('State', $state);
		$this->db->where_in('Connect_Feeder', $connect_feeder);
        
        if ($asc == 1) {
           $this->db->order_by('Device_Order', 'ASC');
        }

        $query = $this->db->get('device_register');
         //echo $this->db->last_query();die;
        return $query->result();
    }

	function calculate_windspeed($type_list) {
        $full_device = array();
        //$full_device['avg_power'] = array();
        $temp_device = array();
        $avg_windspeed = array();
       // $avg_power = array();

        foreach ($type_list as $list) {
            $val = $this->Common_model->get_windspeed($list->Format_Type, $list->IMEI, $list->Device_Name);
            array_push($full_device, $val);
            //array_push($full_device['avg_power'], $device);
        }
        //print_r($full_device);die;
        return $full_device;
    }

    function get_windspeed($type, $imei, $device_name) {
        //skip for format type 1
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
		$idnum = "";
        $this->db2->select('Date_S,AVG( `Windspeed` ) as avg_windspeed, /*AVG( `Power` ) as avg_power ,*/count(`Record_Index`) as count')->from('device_data' . $type);
        $this->db2->where('Date_S', $date);
		$this->db2->where('ID_Number!=', $idnum);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $query = $this->db2->get();
         
        foreach ($query->result_array() as $row) {
            $row['device_name'] = $device_name;
            if ($row['avg_windspeed'] == 'null' || $row['avg_windspeed'] == '') {
                $row['avg_windspeed'] = 0;
            } else {
                $row['avg_windspeed'] = round($row['avg_windspeed'], 2);
            }
            $data = $row;
        }
		//echo $this->db2->last_query();
		 //die;
        return $data;
    }

	function calculate_currentgad($type_list) {
        
       $full_device = array();
        $gad= array();

        foreach ($type_list as $list) {
           $gval = $this->Common_model->get_currentgad($list->Format_Type, $list->IMEI, $list->Device_Name);
         array_push($full_device, $gval);
        }
       // print_r($full_device);die;
        return $full_device;
    }

	function get_currentgad($type, $imei, $device_name) {       
        $data = array();
        $date = date("Y-m-d");
		if ($type == 1 || $type == 6) {
        $this->db->select('date_s,(Gen2_Max - Gen2_Min) as gad')->from('device_register');
        } elseif ($type == 3 || $type == 10 || $type == 7 || $type == 8) {
        $this->db->select('date_s,(Gen1_Max - Gen1_Min) as gad')->from('device_register');
        } else {
		 $this->db->select('date_s,((Gen1_Max-Gen1_Min) + (Gen2_Max - Gen2_Min) )as gad')->from('device_register');
		}
		$this->db->where('date_s', $date);
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $query = $this->db->get();
       // echo $this->db->last_query();die;
        foreach ($query->result_array() as $row) {
            $row['device_name'] = $device_name;
			
            if ($row['gad'] == 'null' || $row['gad'] == '' || $row['gad'] < '0' || $row['gad'] > '15000' ) {
                $row['gad'] = 0;
            } else {
                $row['gad'] = round($row['gad'], 2);
            }
          $data = $row;
        }
		//print_r($data); die;
        return $data;

    }
	
	function getStatusDetailsvani($imeilist) {
        //print_r($imeilist);die;
        $sval = array();
        foreach ($imeilist as $key => $list) {

            $val = $this->Common_model->get_status_details_vani($list['color_val'], $list['imei']);
            array_push($sval, $val);
        }
        return $sval;
    }

	function get_status_details_vani($status_val, $imei) {
        $data = array();
	  $this->db->select("IMEI as imei,Device_Name as device_name,Site_Location as site,Device_Order as devorder,HTSC_No as htsc,Region as region,Format_Type as type,windspeed,power,status,date_s,time_s,Gen1_Max,Gen1_Min,Gen2_Max,Gen2_Min")->from('device_register');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
		$this->db->order_by('Device_Order', 'ASC');
        $query = $this->db->get();
		//echo $this->db->last_query();die;
        foreach ($query->result_array() as $row) {
                $row['status_val'] = $row['status'];
                $row['wind_speed'] = $row['windspeed'];
                $row['power'] = $row['power'];
                $row['Date_S'] = $row['date_s'];
                $row['Time_S'] = $row['time_s'];
				$row['device'] = $row['device_name'];
				$row['siteloc'] = $row['site'];
				$row['htscno'] = $row['htsc'];
				$row['region'] = $row['region'];
				if ($row['type'] == 1 || $row['type'] == 6) {
					$row['gad'] = $row['Gen2_Max'] - $row['Gen2_Min'];
				} elseif ($row['type'] == 3 || $row['type'] == 10 || $row['type'] == 7 || $row['type'] == 8) {
					$row['gad'] = $row['Gen1_Max'] - $row['Gen1_Min'];
				} else {
					$row['gad'] = (($row['Gen1_Max'] - $row['Gen1_Min']) + ($row['Gen2_Max'] - $row['Gen2_Min']));
				}
			if ($row['status_val'] == 'null' || $row['status_val'] == '') {
                $row['status_val'] = 'Nil';
            } else {
                $row['status'] = $row['status'];
            }
			if ($row['wind_speed'] == 'null' || $row['wind_speed'] == '') {
                $row['wind_speed'] = 'Nil';
            } else {
                $row['wind_speed'] = $row['wind_speed'];
            }
			if ($row['power'] == 'null' || $row['power'] == '') {
                $row['power'] = 'Nil';
            } else {
                $row['power'] = $row['power'];
            }
			if ($row['gad'] == 'null' || $row['gad'] == '' || $row['gad'] < '0' || $row['gad'] > '15000') {
                $row['gad'] = '000';
            } else {
                $row['gad'] = round($row['gad'],2);
            }
           $data = $row;
        }
        return $data;
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
				//print_r($color_val);
				
            array_push($cval, $val);
        }
		//print_r($cval);
		//die;
        return $cval;
		
    }
	
	function get_color_machine_vani($type, $imei) {
        $data = "";
		$green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running','M/CRunning','Power Up','FreeWheeling','FreewheelingG1', 'FreewheelingG2', 'FreeWheelingG1', 'FreeWheelingG2', 'OperateG1', 'OperateG2', 'Operate G1', 'Operate G2', 'Running G1');
        $blue_array = array('GRIDDROP', 'Grid Spike', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop');
       // date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d H:i:s");
        $this->db->select('status,date_S,time_S')->from('device_register');
        $this->db->order_by('Device_Order', 'ASC');
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        //echo $this->db->last_query();die;	
//echo $date;die;		
        foreach ($query->result_array() as $row) {
            $datastat = $row['status'];	
			$datadate = $row['date_S'];
			$datatime = $row['time_S'];
			$epoch_time = $this->Common_model->get_time_stamp($datadate,$datatime);
			
			$epoch_diff = (time()+(60*60*5.5))- $epoch_time;
			//print_r($datastat);print_r($datatime);
			//print_r($epoch_diff);
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
		//print_r($data);die;
		
       return $data;
		
    }
	
	function getcurrentDatavani($imei = "") {
        $result = array();
		$green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running','M/CRunning','Power Up','FreeWheeling','FreewheelingG1', 'FreewheelingG2', 'FreeWheelingG1', 'FreeWheelingG2', 'OperateG1', 'OperateG2', 'Operate G1', 'Operate G2', 'Running G1');
        $blue_array = array('GRIDDROP', 'Grid Spike', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop');
        $this->db->select('Device_Name,date_s,time_s,windspeed,power,HTSC_No,Site_Location,Region,Format_Type as type,IMEI,status,Parent_ID');
       if (!empty($imei)) {
                $this->db->where('IMEI', $imei);
            }
          $this->db->limit(1);
             $query = $this->db->get('device_register');
		//echo $this->db->last_query();die;
        foreach ($query->result_array() as $row) {
			$datadate = $row['date_s'];
			$datatime = $row['time_s'];
			$epoch_time = $this->Common_model->get_time_stamp($datadate,$datatime);			
			$epoch_diff = (time()+(60*60*5.5))- $epoch_time;
			//print_r($epoch_diff);
			if ($epoch_diff >= 1800 && (in_array($row['status'],$green_array) && !in_array($row['status'],$blue_array))) {
				$row['Parent_ID'] = "gray";
			}
			else
				{
					if(in_array($row['status'],$green_array)){
						$row['Parent_ID'] = "green";
					}
					elseif (in_array($row['status'],$blue_array)) {
						$row['Parent_ID'] = "blue";
					}
					else {
						$row['Parent_ID'] = "red";
					}
				}
          $result[] = $row;
        }
        return $result;
    }
	
	function getParkviewDatavani() {
        $result = array();
        $date = date("Y-m-d");

		$green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running','M/CRunning','Power Up','FreeWheeling','FreewheelingG1', 'FreewheelingG2', 'FreeWheelingG1', 'FreeWheelingG2', 'OperateG1', 'OperateG2', 'Operate G1', 'Operate G2', 'Running G1');
        $blue_array = array('GRIDDROP', 'Grid Spike', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop');
		
       $Account_ID = $this->session->userdata('account_id');
	   $Parent_ID = $this->session->userdata('parent_id');
		$User_ID = $this->session->userdata('user_type_id');
		
        //$date = '2019-09-28';
		if ($User_ID == 2 || $User_ID == 3) {
        $this->db->select('Device_Name,date_s,time_s,windspeed,power,HTSC_No,Site_Location,Region,Format_Type as type,IMEI,status,device_order,devicedata,Parent_ID');
        $this->db->where('Parent_ID', $Parent_ID);
		} else {
		$this->db->select('Device_Name,date_s,time_s,windspeed,power,HTSC_No,Site_Location,Region,Format_Type as type,IMEI,status,device_order,devicedata,Parent_ID');
        $this->db->where('Account_ID', $Account_ID);
		}
		$this->db->order_by('Device_Order', 'ASC');
        $query = $this->db->get('device_register');
		//echo $this->db->last_query();die;
        foreach ($query->result_array() as $row) {
			$datadate = $row['date_s'];
			$datatime = $row['time_s'];
			$epoch_time = $this->Common_model->get_time_stamp($datadate,$datatime);			
			$epoch_diff = (time()+(60*60*5.5))- $epoch_time;
			//print_r($epoch_diff);
			if ($epoch_diff >= 1800 && (in_array($row['status'],$green_array) && !in_array($row['status'],$blue_array))) {
				$row['Parent_ID'] = "gray";
			}
			else
				{
					if(in_array($row['status'],$green_array)){
						$row['Parent_ID'] = "#008000";
					}
					elseif (in_array($row['status'],$blue_array)) {
						$row['Parent_ID'] = "#0000FF";
					}
					else {
						$row['Parent_ID'] = "#FF0000";
					}
				}
            $devicedata = explode(',', $row['devicedata']);
            $devicedataCount = count($devicedata);
            if ($devicedataCount < 20) {
                $devicedata = array();
            }
            switch ($row['type']) {
                case 1:
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 2:
                    $row['grpm'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['rrpm'] = isset($devicedata[9]) ? $devicedata[9] : 0;
                    break;
                case 3:
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 4:
                    $row['grpm'] = isset($devicedata[8]) ? $devicedata[8] : 0;
                    $row['rrpm'] = isset($devicedata[9]) ? $devicedata[9] : 0;
                    break;
                case 6:
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 7:
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
                case 8:
                    $row['grpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    $row['rrpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    break;
                case 10:
                    $row['grpm'] = isset($devicedata[6]) ? $devicedata[6] : 0;
                    $row['rrpm'] = isset($devicedata[7]) ? $devicedata[7] : 0;
                    break;
            }


            $result[] = $row;
        }
        return $result;
    }

	public function getdeviceDetailsvani($imei = "", $type = "", $limit = "") {
        $data = array();
        if (!empty($imei) && !empty($type)) {
           $idnum = "";
            $dev_type = $type;
            ($type == 1 ? $type = "" : $type = "_f" . $type);
			 if ($dev_type == 1) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,Frequency,Pitch,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,PAT_Gen0,PAT_Gen1,PAT_Gen2,Total_Hours,Run_Hours,Gen1_Hours,Line_Ok,Ambient_Temp,Nacel_Temp,Gear_Temp,Gen1_Temp,Hydraulic_Temp,Control_Temp,Bearing_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            } else if ($dev_type == 2) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,PAT_Gen1,PAT_Gen2,Import_Kwh,Gen1_Hours,Gen2_Hours,G1_Temp,G2_Temp,G3_Temp,G4_Temp,G5_Temp,G6_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            } else if ($dev_type == 3) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,Frequency,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Production_Total,Total_Hours,PAT_Gen1,PAT_Gen2,Gen1_Hours,Gen2_Hours,Import_Kwh,Import_Kvarh,Thyristor_Temp,Ambient_Temp,Main_Panel_Temp,Gen1_Temp,Gen2_Temp,Nacel_Temp,Bearing_Temp,Gear_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            } else if ($dev_type == 4) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,PAT_Gen1,PAT_Gen2,Import_Kwh,Gen1_Hours,Gen2_Hours,Nacel_Temp,Gen1_Temp,Gen2_Temp,Gen_Bear1_Temp,Gen_Bear2_Temp,Gear_Oil_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            } else if ($dev_type == 6) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,Frequency,Pitch,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,PAT_Gen0,PAT_Gen1,PAT_Gen2,Total_Hours,Run_Hours,Gen1_Hours,Line_Ok,Ambient_Temp,Nacel_Temp,Gear_Temp,Gen1_Temp,Control_Temp,Bearing_Temp,Hydraulic_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            } else if ($dev_type == 7) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,Frequency,Nacelle_Position,L_N_Voltage_R as RPhase_Volt,L_N_Voltage_Y as YPhase_Volt,L_N_Voltage_B as BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Kwh_Positive,Kwh_Negative,KVar_Positive,KVar_Negative,Operate_Hours,Stopped_Hours,Grid_failure_Hours,Total_Hours,Active_Total_Gen_Import,Active_Total_Gen_Export,Reactive_Total_Gen_Import,Reactive_Total_Gen_Export,Active_Gen1_Import,Active_Gen1_Export,Reactive_Gen1_Import,Reactive_Gen1_Export,Active_Gen2_Import,Active_Gen2_Export,Reactive_Gen2_Import,Reactive_Gen2_Export,G1_Connected_Counts,G2_Connected_Counts,Control_Panel_Temp,Gear_Bearing1_Temp,Gear_Bearing2_Temp,Gear_Box_Oil_Temp,Gen_Winding1_Temp,Gen_Winding2_Temp,Gen_DE_Bearing_Temp,Gen_DE_NDE_Bearing_Temp,Nacelle_Temp,Main_Bearing_Temp,Transformer_Oil_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            } else if ($dev_type == 8) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,Frequency,Nacelle_Position,L_N_Voltage_R as RPhase_Volt,L_N_Voltage_Y as YPhase_Volt,L_N_Voltage_B as BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Kwh_Positive,Kwh_Negative,KVar_Positive,KVar_Negative,Operate_Hours,Stopped_Hours,Grid_failure_Hours,Total_Hours,Control_Panel_Temp,Gear_Bearing1_Temp,Gear_Bearing2_Temp,Gear_Box_Oil_Temp,Gen_Winding1_Temp,Gen_Winding2_Temp,Gen_DE_Bearing_Temp,Gen_DE_NDE_Bearing_Temp,Nacelle_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            } else if ($dev_type == 10) {
                $this->db2->select('GRPM,RRPM,Windspeed,Status,Power,Frequency,Pitch,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,PAT_Gen0,PAT_Gen1,PAT_Gen2,Production_Total,Total_Hours,Run_Hours,Line_Hours,Gen1_Hours,Gen2_Hours,Ambient_Temp,Hydraulic_Temp,Gear_Temp,Gen1_Temp,Gen2_Temp,Nacel_Temp,Control_Temp,Bearing_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
            }
		    //$this->db2->where('Date_S', $date);
			//$this->db2->where('ID_Number!=' , $idnum);
            if (!empty($imei)) {
                $this->db2->where('IMEI', $imei);
            }
            $this->db2->order_by('Record_Index', 'DESC');
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

	public function get_avgpowerwind($type, $imei, $selector) {
        $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
		$month = date('m', strtotime($date));
        $avgpower = 0;
		$avgwind = 0;
       
		$this->db2->select('AVG(Power) as avgpower,AVG(Windspeed) as avgwind')->from('device_data' . $type);
       
        if ($selector == "today") {
            $date = date("Y-m-d", strtotime($date));
            $this->db2->where('Date_S', $date);
        } 
		else if ($selector == "month") {
            $this->db2->where('month(Date_S)', $month);
        } else {
            $date = date("Y-m-d", strtotime($date));
            $this->db2->where('Date_S', $date);
        } 

        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
       
        $this->db2->limit(1);
        $query = $this->db2->get();
     // echo $this->db2->last_query();
      // die;
        foreach ($query->result_array() as $row) {
                            
            $data = $row;
        }

        //print_r($data);die;
        return $data;
    }

	function kwhactive($type,$imei,$selector) {
		$full_gad_device = array();
        $val = array();
        $value = "";
            if ($selector == "today") {
                $value = 1;
            } else if ($selector == "month") {
                $value = 30;
            } else if ($selector == "year") {
                $value = 365;
            }
			//if ($selector != "year") {
			$avgpowerwind = $this->Common_model->get_avgpowerwind($type, $imei, $selector);
			//}
		    $initial_gad_val = $this->Common_model->get_kwhactive($type, $imei, $selector, 1);
           // $final_gad_val = $this->Common_model->get_kwhactive($type, $imei, $selector, 2);
           /* if ($initial_gad_val > $final_gad_val) {
                $gad = $initial_gad_val - $final_gad_val;
            } else {
                $gad = $final_gad_val - $initial_gad_val;
            }*/
            $text = "";
			$gad = $initial_gad_val;
            switch ($selector) {
                case "today":
                    if ($gad > 15000 || $gad < 0) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case "month":
                    if ($gad > 450000 || $gad < 0) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case "year":
                    if ($gad > 5400000 || $gad < 0) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
               /* case 4:
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
                case 10:                    if ($gad > 6000 * $value) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;*/
            }
            $val = ['gad' => $gad, 'avgwind' => $avgpowerwind['avgwind'], 'avgpower' => $avgpowerwind['avgpower']];
            array_push($full_gad_device, $val);
        

//        print_r($full_gad_device);
//       die;
        return $full_gad_device;
    }

    public function get_kwhactive($type, $imei, $selector, $no) {
        $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
		$month = date('m', strtotime($date));
        $gad_gen = 0;
		$year = date('Y', strtotime($date));
        /*if ($dev_type == 2 || $dev_type == 4) {
            $this->db2->select('PAT_Gen1 as gad_gen1,PAT_Gen2 as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else if ($dev_type == 6 || $dev_type == 1) {
            $this->db2->select('PAT_Gen2 as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else if ($dev_type == 8 || $dev_type == 7) {
            $this->db2->select('Kwh_Positive as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        } else {
            $this->db2->select('Production_Total as gad_gen,Date_S,Time_S')->from('device_data' . $type);
        }*/
		 if ($selector == "today") {
			if($dev_type == 2 || $dev_type == 4) {
            $this->db->select('((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as gad_gen')->from('device_register');
			} elseif($dev_type == 1 || $dev_type == 6) {
			$this->db->select('(Gen2_Max-Gen2_Min) as gad_gen')->from('device_register');
			} else {
			$this->db->select('(Gen1_Max-Gen1_Min) as gad_gen')->from('device_register');
			}			
            $this->db->where('Date_S', $date);
        } else if ($selector == "month") {
			if($dev_type == 2 || $dev_type == 4) {
            $this->db->select('sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as gad_gen')->from('daily_data');
			} else {
			$this->db->select('sum(Gen1_Max-Gen1_Min) as gad_gen')->from('daily_data');
			}
			$this->db->where('month(Date_S)', $month);
        } else if ($selector == "year") {
            if($dev_type == 2 || $dev_type == 4) {
            $this->db->select('sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as gad_gen')->from('daily_data');
			} else {
			$this->db->select('sum(Gen1_Max-Gen1_Min) as gad_gen')->from('daily_data');
			}
			/*if ($no == 1) {
                //$year = $date . "-03";
				$this->db->where('year(Date_S)', $year);
				$this->db->where('month(Date_S) >', '03');
			   
            } else {
				$this->db->where('year(Date_S)', $year);
               // $date = $date + 1;
               // $year = $date . "-04";
			 
            }*/
			$this->db->where('year(Date_S)', $year);
        }
		
		if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        } 
       /* if ($selector == "today") {
            $date = date("Y-m-d", strtotime($date));
            $this->db2->where('Date_S', $date);
        } else if ($selector == "month") {
           // $this->db2->like('Date_S', $date, 'after');
		    $this->db2->where('month(Date_S)', $month);
        } else if ($selector == "year") {
            if ($no == 1) {
                //$year = $date . "-03";
				$this->db2->where('year(Date_S)', $year);
				$this->db2->where('month(Date_S) >', '03');
			   
            } else {
				$this->db2->where('year(Date_S)', $year);
               // $date = $date + 1;
               // $year = $date . "-04";
			 
            }
           // $this->db2->like('Date_S', $year, 'after');
		     
        }        
        if ($no == 1 && $selector != "date") {
            $this->db->order_by('Date_S', 'ASC');
			//$this->db->order_by('Record_Index', 'ASC');
        } else if ($no == 2 && $selector != "date") {
            $this->db->order_by('Date_S', 'DESC');
			
        }
        if ($no == 1) {
            $this->db->order_by('Record_Index', 'ASC');
        } else {
            $this->db->order_by('Record_Index', 'DESC');
        }*/
        $this->db->limit(1);
        $query = $this->db->get();
      //echo $this->db->last_query();
       // die;
        foreach ($query->result_array() as $row) {
           /* if ($dev_type == 2 || $dev_type == 4) {
                $row['gad_gen'] = $row['gad_gen1'] + $row['gad_gen'];
            }*/
            $data = $row;
        }

        if (isset($data['gad_gen'])) {
            $gad_gen = $data['gad_gen'];
        }

        //print_r($data);die;
        return $gad_gen;
    }
	
	function fetch_tempvani($devname = "", $type = "", $imei = "", $date = "") {
//print_r($imei);die;
        $data = array();
		$dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
		
		 if ($dev_type == 1) {
             $this->db2->select('Ambient_Temp, Nacel_Temp,Gear_Temp,Gen1_Temp,Control_Temp,Bearing_Temp,Hydraulic_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
         } else if ($dev_type == 2) {
             $this->db2->select('G1_Temp,G2_Temp,G3_Temp,G4_Temp,G5_Temp,G6_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
         } else if ($dev_type == 3) {
            $this->db2->select('Ambient_Temp,  Nacel_Temp, Gear_Temp, Gen1_Temp, Gen2_Temp,Bearing_Temp, Thyristor_Temp,Main_Panel_Temp, Date_S, Time_S, Record_Index')->from('device_data' . $type);
         } else if ($dev_type == 4) {
            $this->db2->select('Nacel_Temp,Gen1_Temp,Gen2_Temp,Gen_Bear1_Temp,Gen_Bear2_Temp,Gear_Oil_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
         } else if ($dev_type == 6) {
            $this->db2->select('Ambient_Temp,Nacel_Temp,Gear_Temp,Gen1_Temp,Control_Temp,Bearing_Temp,Hydraulic_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
         } else if ($dev_type == 7) {
			$this->db2->select('Nacelle_Temp, Control_Panel_Temp,Gear_Bearing1_Temp, Gear_Bearing2_Temp,Gear_Box_Oil_Temp,Gen_Winding1_Temp, Gen_Winding2_Temp, Gen_DE_Bearing_Temp,Gen_DE_NDE_Bearing_Temp,Main_Bearing_Temp,Transformer_Oil_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
		 } else if ($dev_type == 8) {
            $this->db2->select('Nacelle_Temp  as Nacel_Temp, Control_Panel_Temp,Gear_Bearing1_Temp,Gear_Bearing2_Temp, Gear_Box_Oil_Temp, Gen_Winding1_Temp, Gen_Winding2_Temp,Gen_DE_Bearing_Temp,Gen_DE_NDE_Bearing_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
         } else if ($dev_type == 10) {
            $this->db2->select('Ambient_Temp,Nacel_Temp,Gear_Temp,Gen1_Temp,Gen2_Temp,Control_Temp,Bearing_Temp,Hydraulic_Temp,Date_S,Time_S,Record_Index')->from('device_data' . $type);
         }
     
        $this->db2->where('Date_S', $date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $this->db2->order_by('Time_S', 'ASC');
        // $this->db2->limit(1);

        $query = $this->db2->get();
		//$str = $this->db2->last_query();
		//print_r($str);
      // die;
        foreach ($query->result_array() as $row) {
			
            $data[] = $row;
        }
        //print_r($data);die;
        // usort($data, 'compareByName');
        return $data;
    }

	function calculate_gad_perf($selector, $sdate) {

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
            $gad_val = $this->Common_model->get_gad_perf($list->Format_Type, $list->IMEI, $selector, $sdate, 1);
          
			$gad = $gad_val;

            $text = "";
            switch ($selector) {
                case "date":
                    if ($gad > 15000 || $gad < 0) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case "month":
                    if ($gad > 450000 || $gad < 0) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
                case "year":
                    if ($gad > 5400000 || $gad < 0) {
                        $gad = 0;
                        $text = "Error";
                    }
                    break;
               /* case 4:
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
                    break;*/
            }
            $val = ['gad' => $gad, 'device_name' => $list->Device_Name];
            array_push($full_gad_device, $val);
        }

//        print_r($full_gad_device);
//       die;
        return $full_gad_device;
    }

    public function get_gad_perf($type, $imei, $selector, $sdate, $no) {
        
        $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        //$date = date("Y-m-d");
        // $date = '2019-04-13';
        $gad_gen = 0;
        $year = "";
		$my1="04-01";
		$my2="03-31";
		$num = 1;
		//$date_next = $date+$num;
		//$sdate="2019-04-01";
		//$edate="2020-03-31";
		if ($selector == "date") {
			$sdate = date("Y-m-d", strtotime($sdate));
			if($dev_type == 2 || $dev_type == 4) {
            $this->db->select('((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as gad_gen')->from('daily_data');
			} else {
			$this->db->select('(Gen1_Max-Gen1_Min) as gad_gen')->from('daily_data');
			}
            $this->db->where('Date_S', $sdate);
        } else if ($selector == "month") {
			if($dev_type == 2 || $dev_type == 4) {
            $this->db->select('sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as gad_gen')->from('daily_data');
			} else {
			$this->db->select('sum(Gen1_Max-Gen1_Min) as gad_gen')->from('daily_data');
			}
			$this->db->like('Date_S', $sdate);
        } else if ($selector == "year") {
			$startdate = '.$sdate."-".$my1.';
			$stdate = date("Y-m-d", strtotime($startdate));
			$eydate = strtotime(date("Y-m-d", strtotime($startdate)) . " +1 year");
			$eyydate = date("Y",$eydate);
			$edate = '.$eyydate."-".$my2.';
			//$edate="2020-03-31";
            if($dev_type == 2 || $dev_type == 4) {
            $this->db->select('sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as gad_gen')->from('daily_data');
			} else {
			$this->db->select('sum(Gen1_Max-Gen1_Min) as gad_gen')->from('daily_data');
			}
			$this->db->where('Date_S >=', $stdate);
			$this->db->where('Date_S <', $edate);
        }
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
        $this->db->limit(1);
        $query = $this->db->get();
      //echo $this->db->last_query();
      //die;
        foreach ($query->result_array() as $row) {
          
            $data = $row;
        }

        if (isset($data['gad_gen'])) {
            $gad_gen = $data['gad_gen'];
        }

        //print_r($data);die;
        return $gad_gen;
    }

	function device_perfomance($type, $imei, $dname) {
        $device_perfomance = array();
        $data_device_perf = array();

        $device_perfomance = $this->Common_model->get_device_perfomance($type, $imei, $dname);
       // $data_device_perf = $this->Common_model->get_device_perfomance($type, $imei, $dname, '2');
        foreach ($device_perfomance as $data) {

            array_push($data_device_perf, $data);
        }
//        print_r($error_device_perfomance);
//        die;

        return $data_device_perf;
    }

    function get_device_perfomance($type, $imei, $dname) {
        //skip for format type 1
		$green_array = array('Run', 'RUN', 'M/C Running', 'M/C Running','M/CRunning','Power Up','FreeWheeling','FreewheelingG1', 'FreewheelingG2', 'FreeWheelingG1', 'FreeWheelingG2', 'OperateG1', 'OperateG2', 'Operate G1', 'Operate G2', 'Running G1');
        $blue_array = array('GRIDDROP', 'Grid Spike', 'griddrop', 'Grid Drop', 'Grid Drop', 'GridDrop');
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
        // $date = '2019-04-13';
        
            $this->db2->select('Time_S,Status')->from('device_data' . $type);
		 if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
        $this->db2->where('Date_S', $date);
        
        $query = $this->db2->get();
        // echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $row['device_name'] = $dname;
            $row['y'] = 0;
			
					if(in_array($row['Status'],$green_array)){
						$row['colour'] = "#228B22";
					}
					elseif (in_array($row['Status'],$blue_array)) {
						$row['colour'] = "#0101DF";
					}
					else {
						$row['colour'] = "#FF0000";
					}
				//}
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
		//echo $date;
		//die;
        //$this->db2->distinct('Windspeed');
        $this->db2->select('Date_S,floor(Windspeed) as Windspeed_fl,MAX( `Power` ) as Power,Windspeed')->from('device_data' . $type);
        //$this->db2->where('Date_S', $date);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
		 $this->db2->where('Power >', '0');
        $this->db2->group_by('floor(Windspeed)');
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		$this->db2->order_by('floor(Windspeed)', 'Asc');
        $query = $this->db2->get();
        //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	function getPowerCurveDatadot($type, $imei, $start_date, $end_date) {
        //skip for format type 1$type, $imei, $start_date, $end_date
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        $date = date("Y-m-d");
        //$date = '2019-04-13';
		//echo $date;
		//die;
        //$this->db2->distinct('Windspeed');
        $this->db2->select('Date_S,Windspeed,MAX( `Power` ) as Power')->from('device_data' . $type);
        //$this->db2->where('Date_S', $date);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
		 $this->db2->where('Power >', '0');
        $this->db2->group_by('Windspeed');
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		$this->db2->order_by('Windspeed', 'Asc');
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
			/*$this->db->select('floor(Wind) as Windspeed,MAX( `Power` ) as Power')->from('powercurveref_500kw');
			$this->db->group_by('floor(Wind)');
			$this->db->order_by('floor(Wind)', 'Asc');
        $query = $this->db->get();
        //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $carray[] = $row;
        }
		break;
            case 600:
			$this->db->select('floor(Wind) as Windspeed,MAX( `Power` ) as Power')->from('powercurveref_600kw');
			$this->db->group_by('floor(Wind)');
			$this->db->order_by('floor(Wind)', 'Asc');
        $query = $this->db->get();
        //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $carray[] = $row;
        }
		break;
            case 250:
			$this->db->select('floor(Wind) as Windspeed,MAX( `Power` ) as Power')->from('powercurveref_250kw');
			$this->db->group_by('floor(Wind)');
			$this->db->order_by('floor(Wind)', 'Asc');
        $query = $this->db->get();
        //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $carray[] = $row;
        }
		break;*/
            
                /*$carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 39.73),
                    '	6	     ' => array('Windspeed' => 5, 'Power' => 90.87),
                    '	7	     ' => array('Windspeed' => 6, 'Power' => 156.31),
                    '	8	     ' => array('Windspeed' => 7, 'Power' => 234.1),
                    '	9	     ' => array('Windspeed' => 8, 'Power' => 316.7),
                    '	10	     ' => array('Windspeed' => 9, 'Power' => 392.5),
                    '	11	     ' => array('Windspeed' => 10, 'Power' => 446.8),
                    '	12	     ' => array('Windspeed' => 11, 'Power' => 478.1),
                    '	13	     ' => array('Windspeed' => 12, 'Power' => 492.7),
                    '	14	     ' => array('Windspeed' => 13, 'Power' => 497.6),
                    '	15	     ' => array('Windspeed' => 14, 'Power' => 499.8),
                    '	16	     ' => array('Windspeed' => 15, 'Power' => 500),
                    '	17	     ' => array('Windspeed' => 16, 'Power' => 500),
                    '	18	     ' => array('Windspeed' => 17, 'Power' => 500),
                    '	19	     ' => array('Windspeed' => 18, 'Power' => 500),
                    '	20	     ' => array('Windspeed' => 19, 'Power' => 500),
                    '	21	     ' => array('Windspeed' => 20, 'Power' => 500),
                    '	22	     ' => array('Windspeed' => 21, 'Power' => 500),
                    '	23       ' => array('Windspeed' => 22, 'Power' => 500),
                    '	24	     ' => array('Windspeed' => 23, 'Power' => 500),
                    '	25	     ' => array('Windspeed' => 24, 'Power' => 500),
                    '	26	     ' => array('Windspeed' => 25, 'Power' => 500),
                );

                break;
            case 600:
			  $carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 39.9),
                    '	6	     ' => array('Windspeed' => 5, 'Power' => 76.2),
                    '	7	     ' => array('Windspeed' => 6, 'Power' => 135.8),
                    '	8	     ' => array('Windspeed' => 7, 'Power' => 210.4),
                    '	9	     ' => array('Windspeed' => 8, 'Power' => 318.8),
                    '	10	     ' => array('Windspeed' => 9, 'Power' => 402),
                    '	11	     ' => array('Windspeed' => 10, 'Power' => 466.7),
                    '	12	     ' => array('Windspeed' => 11, 'Power' => 526.1),
                    '	13	     ' => array('Windspeed' => 12, 'Power' => 560.8),
                    '	14	     ' => array('Windspeed' => 13, 'Power' => 580.2),
                    '	15	     ' => array('Windspeed' => 14, 'Power' => 595.5),
                    '	16	     ' => array('Windspeed' => 15, 'Power' => 599.7),
                    '	17	     ' => array('Windspeed' => 16, 'Power' => 600),
                    '	18	     ' => array('Windspeed' => 17, 'Power' => 600),
                    '	19	     ' => array('Windspeed' => 18, 'Power' => 600),
                    '	20	     ' => array('Windspeed' => 19, 'Power' => 600),
                    '	21	     ' => array('Windspeed' => 20, 'Power' => 600),
                    '	22	     ' => array('Windspeed' => 21, 'Power' => 600),
                    '	23	     ' => array('Windspeed' => 22, 'Power' => 600),
                    '	24	     ' => array('Windspeed' => 23, 'Power' => 600),
                    '	25	     ' => array('Windspeed' => 24, 'Power' => 600),
                    '	26	     ' => array('Windspeed' => 25, 'Power' => 600)
                   
                );              
                break;
				*/
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
           /* default:
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
                );*/
        }
        return $carray;
    }

    
	public function getPowerCurveCapacitydot($capacity) {
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
			  /*$carray = array(
                    '	1	     ' => array('Windspeed' => 0, 'Power' => 0),
                    '	2	     ' => array('Windspeed' => 1, 'Power' => 0),
                    '	3	     ' => array('Windspeed' => 2, 'Power' => 0),
                    '	4	     ' => array('Windspeed' => 3, 'Power' => 0),
                    '	5	     ' => array('Windspeed' => 4, 'Power' => 39.9),
                    '	6	     ' => array('Windspeed' => 5, 'Power' => 76.2),
                    '	7	     ' => array('Windspeed' => 6, 'Power' => 135.8),
                    '	8	     ' => array('Windspeed' => 7, 'Power' => 210.4),
                    '	9	     ' => array('Windspeed' => 8, 'Power' => 318.8),
                    '	10	     ' => array('Windspeed' => 9, 'Power' => 402),
                    '	11	     ' => array('Windspeed' => 10, 'Power' => 466.7),
                    '	12	     ' => array('Windspeed' => 11, 'Power' => 526.1),
                    '	13	     ' => array('Windspeed' => 12, 'Power' => 560.8),
                    '	14	     ' => array('Windspeed' => 13, 'Power' => 580.2),
                    '	15	     ' => array('Windspeed' => 14, 'Power' => 595.5),
                    '	16	     ' => array('Windspeed' => 15, 'Power' => 599.7),
                    '	17	     ' => array('Windspeed' => 16, 'Power' => 600),
                    '	18	     ' => array('Windspeed' => 17, 'Power' => 600),
                    '	19	     ' => array('Windspeed' => 18, 'Power' => 600),
                    '	20	     ' => array('Windspeed' => 19, 'Power' => 600),
                    '	21	     ' => array('Windspeed' => 20, 'Power' => 600),
                    '	22	     ' => array('Windspeed' => 21, 'Power' => 600),
                    '	23	     ' => array('Windspeed' => 22, 'Power' => 600),
                    '	24	     ' => array('Windspeed' => 23, 'Power' => 600),
                    '	25	     ' => array('Windspeed' => 24, 'Power' => 600),
                    '	26	     ' => array('Windspeed' => 25, 'Power' => 600)
                   
                );*/
              
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
           /* default:
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
                );*/
        }
        return $carray;
    }
	
    function getpwReport($type, $imei, $start_date, $end_date) {
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();

        //$date = '2019-04-13';
        $this->db2->select('Date_S,Time_S,Windspeed,Power')->from('device_data' . $type);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		$this->db2->where('ID_Number !=', '');
		$this->db2->order_by('Record_Index', 'DESC');
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	function getoverviewReport($type, $imei, $start_date, $end_date) {
		$dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        //$date = '2019-04-13';
		if($dev_type == 1 || $dev_type == 6 || $dev_type == 10) {
        $this->db2->select('Date_S,Time_S,Windspeed,Power,GRPM,RRPM,Pitch,Status')->from('device_data' . $type);
		} else {
		$this->db2->select('Date_S,Time_S,Windspeed,Power,GRPM,RRPM,Status')->from('device_data' . $type);
		}
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		//$this->db2->where('ID_Number !=', '');
		$this->db2->order_by('Record_Index', 'DESC');
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	function getprodReport($type, $imei, $start_date, $end_date) {
		$dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        //$date = '2019-04-13';
		if($dev_type == 1 || $dev_type == 6) {
        $this->db2->select('Date_S,Time_S,PAT_Gen0,PAT_Gen1,PAT_Gen2')->from('device_data' . $type);
		} elseif($dev_type == 2 || $dev_type == 4) {
		$this->db2->select('Date_S,Time_S,PAT_Gen1,PAT_Gen2,Import_Kwh')->from('device_data' . $type);
		} elseif($dev_type == 3 || $dev_type == 10) {
		$this->db2->select('Date_S,Time_S,PAT_Gen1,PAT_Gen2,Production_Total')->from('device_data' . $type);
		} else {
		$this->db2->select('Date_S,Time_S,Kwh_Positive, Kwh_Negative, KVar_Positive')->from('device_data' . $type);
		}
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		$this->db2->where('ID_Number !=', '');
		$this->db2->order_by('Record_Index', 'DESC');
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

	function getgridReport($type, $imei, $start_date, $end_date) {
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();

        //$date = '2019-04-13';
        $this->db2->select('Date_S,Time_S,RPhase_Volt,YPhase_Volt,BPhase_Volt,RPhase_Current,YPhase_Current,BPhase_Current,Power,Power_Factor')->from('device_data' . $type);
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		$this->db2->where('ID_Number !=', '');
		$this->db2->order_by('Record_Index', 'DESC');
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	function gettempReport($type, $imei, $start_date, $end_date) {
		$dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
        //$date = '2019-04-13';
		if($dev_type == 1 || $dev_type == 6) {
        $this->db2->select('Date_S,Time_S,Ambient_Temp,Hydraulic_Temp,Gear_Temp,Gen1_Temp,Nacel_Temp,Control_Temp,Bearing_Temp')->from('device_data' . $type);
		} 
		elseif($dev_type == 2) {
        $this->db2->select('Date_S,Time_S,G1_Temp,G2_Temp,G3_Temp,G4_Temp,G5_Temp,G6_Temp')->from('device_data' . $type);
		}
		elseif($dev_type == 3) {
		$this->db2->select('Date_S,Time_S,Thyristor_Temp,Ambient_Temp,Main_Panel_Temp,Gear_Temp,Gen1_Temp,Gen2_Temp,Nacel_Temp,Bearing_Temp,Temp10')->from('device_data' . $type);
		}
		elseif($dev_type == 4) {
        $this->db2->select('Date_S,Time_S,Nacel_Temp,Gen1_Temp,Gen2_Temp,Gen_Bear1_Temp,Gen_Bear2_Temp,Gear_Oil_Temp')->from('device_data' . $type);
		} 
		elseif($dev_type == 7 || $dev_type == 8) {
        $this->db2->select('Date_S,Time_S,Nacelle_Temp  as Nacel_Temp, Control_Panel_Temp,Gear_Bearing1_Temp,Gear_Bearing2_Temp, Gear_Box_Oil_Temp, Gen_Winding1_Temp, Gen_Winding2_Temp,Gen_DE_Bearing_Temp,Gen_DE_NDE_Bearing_Temp')->from('device_data' . $type);
		}
		elseif($dev_type == 10) {
        $this->db2->select('Date_S,Time_S,Ambient_Temp,Hydraulic_Temp,Gear_Temp,Gen1_Temp,Gen2_Temp,Nacel_Temp,Control_Temp,Bearing_Temp')->from('device_data' . $type);
		}
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		$this->db2->where('ID_Number !=', '');
		$this->db2->order_by('Record_Index', 'DESC');
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

	function getalarmReport($type, $imei, $start_date, $end_date) {
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();

        //$date = '2019-04-13';
        $this->db2->select('Date_S,Time_S,Status')->from('current_status');
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
        if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }
		//$this->db2->where('ID_Number !=', '');
		$this->db2->order_by('Date_S', 'DESC');
		$this->db2->order_by('Time_S', 'DESC');
        $query = $this->db2->get();
        //  echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	      
	function getalarmgrpReport($typelist,$sdate, $edate) {
		$data = array();
		foreach ($typelist as $list) {
			$imei = $list->IMEI;
        $this->db2->select('Date_S,Time_S,IMEI,Status')->from('current_status');
        $this->db2->where('Date_S >=', $sdate);
        $this->db2->where('Date_S <=', $edate);
        //if (!empty($imei)) {
        $this->db2->where('IMEI', $imei);
        //}
		//$this->db2->where('ID_Number !=', '');
		$this->db2->order_by('Date_S', 'DESC');
		$this->db2->order_by('Time_S', 'DESC');
		$this->db2->order_by('IMEI', 'DESC');
		$query = $this->db2->get();
        //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
			//$imei = $row['IMEI'];
			//$dev_name = $this->Common_model->commonDataFetching($imei,'Device_Name');
			//print_r($dev_name); //die;
			//$data['dev_name'] = $dev_name;
            $data[] = $row;
			//print_r($data); //die;
        }
		}
        return $data;
    }
	function getstophrsReport($typelist,$sdate, $edate) {
		$data = array();
		foreach ($typelist as $list) {
           // echo $list->Format_Type;die;
		   $FType = $list->Format_Type;
		   $imei = $list->IMEI;
		   if ($FType == 1 || $FType == 6  || $FType == 10 || $FType == 7 || $FType == 8) {
			  $this->db->select('Date_S,IMEI,(Run_Max-Run_Min) as Run')->from('daily_data');
		   } else {
			   $this->db->select('Date_S,IMEI,((Gen1H_Max-Gen1H_Min)+(Gen2H_Max-Gen2H_Min)) as Run')->from('daily_data');			   
		   }
		      $this->db->where('Date_S >=', $sdate);
			  $this->db->where('Date_S <=', $edate);
			  if (!empty($imei)) {
				  $this->db->where('IMEI', $imei);
			  }
			  $this->db->order_by('Date_S', 'DESC');
			  //$this->db->order_by('IMEI', 'DESC');
		 
		$query = $this->db->get();
        //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
			$data[] = $row;
			//print_r($data); //die;
        }
		}
        return $data;
    }
    
	function getdgrindividualReport($type, $imei, $start_date, $end_date) {
		$dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
		$result1  = $result2 = $result3 = null;
		if($dev_type == 1 || $dev_type == 6) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by Date_S");
		} elseif ($dev_type == 2) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by Date_S");
			$query2 = $this->db2->query("select Error_Type as ET_GD, sum(Time_Diff) as Diff  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'GD Hours' group by Date_S,Error_Type order by Date_S");
			$query3 = $this->db2->query("select Error_Type as ET_BD, sum(Time_Diff) as Diff1  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'BD Hours' group by Date_S,Error_Type order by Date_S");
		} elseif($dev_type == 3) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by Date_S");
			$query2 = $this->db2->query("select Error_Type as ET_GD, sum(Time_Diff) as Diff  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'GD Hours' group by Date_S,Error_Type order by Date_S");
			$query3 = $this->db2->query("select Error_Type as ET_BD, sum(Time_Diff) as Diff1  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'BD Hours' group by Date_S,Error_Type order by Date_S");
		} elseif ($dev_type == 4) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by Date_S");
		} elseif ($dev_type == 7 || $dev_type == 8) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen1H_Min,Gen1H_Max,Run_Min,Run_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by Date_S");
		} elseif ($dev_type == 10) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by Date_S");			
		}  
      //echo $this->db2->last_query(); die;
		
		if($dev_type == 3 || $dev_type == 2) {
			foreach ($query1->result_array() as $row) {
			$result1[] = $row;
			}
			foreach ($query2->result_array() as $row) {				
			$result2[] = $row;
			}
			foreach ($query3->result_array() as $row) {				
			$result3[] = $row;
			}			
			//$data[] = array_push($result1, $result3);
			/*$result1 = $query1->result();
			$result2 = $query2->result();*/
			foreach ($result1 as $key => $value) {
				$data[] =  array_merge((array) $result2[$key], (array) $value,(array) $result3[$key], (array) $value);				
			}
		} else {
			foreach ($query1->result_array() as $row) {
			$data[] = $row;
			}
		}
        return $data;
    }
	
	function getdgrgrpReport($typelist,$start_date, $end_date) {
		$data = array();
		foreach ($typelist as $list) {
           // echo $list->Format_Type;die;
		   $FType = $list->Format_Type;
		   $imei = $list->IMEI;
		if($FType == 1 || $FType == 6) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by IMEI,Date_S");
		} elseif ($FType == 2) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by IMEI,Date_S");
			$query2 = $this->db2->query("select sum(Time_Diff) as Diff  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'GD Hours' group by Date_S,IMEI,Error_Type order by IMEI,Date_S");
			$query3 = $this->db2->query("select sum(Time_Diff) as Diff1  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'BD Hours' group by Date_S,IMEI,Error_Type order by IMEI,Date_S");
		} elseif($FType == 3) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by IMEI,Date_S");
			$query2 = $this->db2->query("select sum(Time_Diff) as Diff  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'GD Hours' group by Date_S,IMEI,Error_Type order by IMEI,Date_S");
			$query3 = $this->db2->query("select sum(Time_Diff) as Diff1  from pocket_time_calc where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and Error_Type = 'BD Hours' group by Date_S,IMEI,Error_Type order by IMEI,Date_S");
		} elseif ($FType == 4) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen2_Min,Gen2_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by IMEI,Date_S");
		} elseif ($FType == 7 || $FType == 8) {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Run_Min,Run_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by IMEI,Date_S");
		} else {
			$query1 = $this->db->query("select IMEI,Date_S,Gen1_Min,Gen1_Max,Run_Min,Run_Max,Gen1H_Min,Gen1H_Max,Gen2H_Min,Gen2H_Max,Line_Min,Line_Max,abs(Import_Min) as Import_Min,abs(Import_Max) as Import_Max from daily_data where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' group by Date_S,IMEI order by IMEI,Date_S");			
		}
		if($FType == 3 || $FType == 2) {
			foreach ($query1->result_array() as $row) {
			$result1[] = $row;
			}
			foreach ($query2->result_array() as $row) {				
			$result2[] = $row;
			}
			foreach ($query3->result_array() as $row) {				
			$result3[] = $row;
			}			
			//$data[] = array_push($result1, $result3);
			/*$result1 = $query1->result();
			$result2 = $query2->result();*/
			foreach ($result1 as $key => $value) {
			//	if($result2!= NULL && $result3!=NULL) {
					$data[] =  array_merge((array) $result2[$key], (array) $value,(array) $result3[$key], (array) $value);		
				//} 
				/*if ($result2==NULL && $result3!=NULL) {
					$data[] =  array_merge((array) $result3[$key], (array) $value);		
				}
				if ($result2!=NULL && $result3==NULL) {
					$data[] =  array_merge((array) $result2[$key], (array) $value);		
				}
				if ($result2==NULL && $result3==NULL) {
					$data[] =  $value;		
				}*/
			}
		} else {
			foreach ($query1->result_array() as $row) {
			$data[] = $row;
			}
		}
		}
		//$data[$FType] = $FType;
        return $data;    
	}
	
	function getPowerWindgraph($type, $imei, $start_date, $end_date) {
       $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
		if($dev_type == 1 || $dev_type == 6) {
			$this->db2->select('HOUR(Time_S) as Hour ,(MAX(GREATEST(PAT_GEN2,0)) - MIN(GREATEST(PAT_GEN2,0))) as GAD ,ROUND(AVG(GREATEST(POWER,0)),2) as Power, ROUND(AVG(WINDSPEED),2) as WindSpeed')->from('device_data' . $type);
		} elseif($dev_type == 2 || $dev_type == 4) {
			$this->db2->select('HOUR(Time_S) as Hour ,((MAX(GREATEST(PAT_GEN1,0))-MIN(GREATEST(PAT_GEN1,0)))+(MAX(GREATEST(PAT_GEN2,0))-MIN(GREATEST(PAT_GEN2,0)))) as GAD ,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed')->from('device_data' . $type);		
		} elseif($dev_type == 7 || $dev_type == 8) {
			$this->db2->select('HOUR(Time_S) as Hour ,Kwh_Positive as GAD ,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed')->from('device_data' . $type);		
		} elseif($dev_type == 3 || $dev_type == 10) {
			$this->db2->select('HOUR(Time_S) as Hour ,(MAX(GREATEST(Production_Total,0)) - MIN(GREATEST(Production_Total,0))) as GAD ,ROUND(AVG(GREATEST(Power,0)),2) as Power, ROUND(AVG(Windspeed),2) as WindSpeed')->from('device_data' . $type);		
		}
		 if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }		
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
		 $this->db2->where('ID_Number!=', '');
		$this->db2->group_by('Date_S');
		$this->db2->group_by('Hour(Time_S)');
		$this->db2->order_by('Record_Index', 'Asc');
        $query = $this->db2->get();
        //echo $this->db2->last_query();die;
        foreach ($query->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	function getHourPowerWindgraph($type, $imei, $start_date, $end_date) {
       $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
		$query1 = $this->db2->query("SELECT s.Windspeed, s.Power FROM device_data".$type."  AS f JOIN (SELECT floor(Avg(Windspeed)) as Windspeed, ROUND(AVG(GREATEST(POWER, 0)), 2) as Power FROM device_data".$type." where IMEI ='".$imei."' and Date_S >= '".$start_date."' and Date_S <='".$end_date."' and ID_Number!='' group by HOUR(Time_S)) AS s ON floor(f.Windspeed) = s.Windspeed group by s.Windspeed");
		
		/* if (!empty($imei)) {
            $this->db2->where('IMEI', $imei);
        }		
        $this->db2->where('Date_S >=', $start_date);
        $this->db2->where('Date_S <=', $end_date);
		 $this->db2->where('ID_Number!=', '');
		$this->db2->group_by('Date_S');
		$this->db2->group_by('Hour(Time_S)');
		$this->db2->group_by('floor(Windspeed)');
		$this->db2->order_by('floor(Windspeed)', 'Asc');
        $query = $this->db2->get();*/
        //echo $this->db2->last_query();die;
        foreach ($query1->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }
	
	public function getFinyearReport($type, $imei, $sdate, $edate) {        
        $dev_type = $type;
        ($type == 1 ? $type = "" : $type = "_f" . $type);
        $data = array();
		$gad_gen = 0;
		if($dev_type == 1 || $dev_type == 6 || $dev_type == 3 || $dev_type == 10 || $dev_type == 7 || $dev_type == 8) {
			$this->db->select('Month(Date_S) as Month, Year(Date_S) as Year, sum(Gen1_Max-Gen1_Min) as gad_gen')->from('daily_data');
		} else {
			$this->db->select('Month(Date_S) as Month, Year(Date_S) as Year, sum((Gen1_Max-Gen1_Min)+(Gen2_Max-Gen2_Min)) as gad_gen')->from('daily_data');
		}
			//$this->db->like('Date_S', $date);
			$this->db->where('year(Date_S) >=', $sdate);
			$this->db->where('year(Date_S) <=', $edate);
        if (!empty($imei)) {
            $this->db->where('IMEI', $imei);
        }
		$this->db->group_by('month(Date_S)');
        //$this->db->limit(1);
        $query = $this->db->get();
      //echo $this->db->last_query();die;
      //die;
        foreach ($query->result_array() as $row) {
          
            $data[] = $row;
        }
       //print_r($data);die;
        return $data;
    }

	
}

?>
