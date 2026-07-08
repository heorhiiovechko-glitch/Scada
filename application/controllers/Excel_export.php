<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Excel_export extends CI_Controller {
    function index() {
        $this->load->model('common/Common_model');
	}
    function pwaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
			$basic = $this->Common_model->getbasicInfoimei($dname);
			$object->getActiveSheet()->setCellValue('A1', 'Power Vs Windspeed Report');
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);            
            $object->getActiveSheet()->setCellValue('A4', 'Date');
            $object->getActiveSheet()->setCellValue('B4', 'Time');
            $object->getActiveSheet()->setCellValue('C4', 'Windspeed');
            $object->getActiveSheet()->setCellValue('D4', 'Power');            
            $pw_data = $this->Common_model->getpwReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
            foreach ($pw_data as $key => $val) {
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $val['Time_S']);
                $object->getActiveSheet()->setCellValue('C' . $excel_row, $val['Windspeed']);
                $object->getActiveSheet()->setCellValue('D' . $excel_row, $val['Power']);
                $excel_row++;
            }
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Power Vs Wind speed");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }
	
	function overviewaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
			$basic = $this->Common_model->getbasicInfoimei($dname);
			$object->getActiveSheet()->setCellValue('A1', 'Overview Report');
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);            
			$object->getActiveSheet()->setCellValue('A4', 'Date');
            $object->getActiveSheet()->setCellValue('B4', 'Time');
            $object->getActiveSheet()->setCellValue('C4', 'GRPM');
            $object->getActiveSheet()->setCellValue('D4', 'RRPM');
            $object->getActiveSheet()->setCellValue('E4', 'Windspeed');
			if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6  || $basic[0]['Format_Type'] == 10) {
				$object->getActiveSheet()->setCellValue('F4', 'Pitch');
				$object->getActiveSheet()->setCellValue('G4', 'Power');
				$object->getActiveSheet()->setCellValue('H4', 'Status');
			} else {
				$object->getActiveSheet()->setCellValue('F4', 'Power');
				$object->getActiveSheet()->setCellValue('G4', 'Status');
			}
            $overview_data = $this->Common_model->getoverviewReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
            foreach ($overview_data as $key => $val) {
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $val['Time_S']);
				$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['GRPM']);
                $object->getActiveSheet()->setCellValue('D' . $excel_row, $val['RRPM']);
                $object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Windspeed']);
                if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6 || $basic[0]['Format_Type'] == 10) {
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['Pitch']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['Power']);
					$object->getActiveSheet()->setCellValue('H' . $excel_row, substr($val['Status'],0,55));
				} else {
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['Power']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['Status']);
				}
                $excel_row++;
            }
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Overview Report");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }

	function prodaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
			$basic = $this->Common_model->getbasicInfoimei($dname);
			$object->getActiveSheet()->setCellValue('A1', 'Production Report');
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);            
            $object->getActiveSheet()->setCellValue('A4', 'Date');
            $object->getActiveSheet()->setCellValue('B4', 'Time');
           if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
				$object->getActiveSheet()->setCellValue('C4', 'PAT Gen0');
				$object->getActiveSheet()->setCellValue('D4', 'PAT Gen1');
				$object->getActiveSheet()->setCellValue('E4', 'Net Total');
			} elseif ($basic[0]['Format_Type'] == 2 || $basic[0]['Format_Type'] == 4) {
				$object->getActiveSheet()->setCellValue('C4', 'PAT Gen1');
				$object->getActiveSheet()->setCellValue('D4', 'PAT Gen2');
				$object->getActiveSheet()->setCellValue('E4', 'Import Kwh');
			} elseif ($basic[0]['Format_Type'] == 3 || $basic[0]['Format_Type'] == 10) {
				$object->getActiveSheet()->setCellValue('C4', 'PAT Gen1');
				$object->getActiveSheet()->setCellValue('D4', 'PAT Gen2');
				$object->getActiveSheet()->setCellValue('E4', 'Production Total');
			} else {
				$object->getActiveSheet()->setCellValue('C4', 'Kwh Positive');
				$object->getActiveSheet()->setCellValue('D4', 'Kwh Negative');
				$object->getActiveSheet()->setCellValue('E4', 'KVar Positive');
			} 
            $prod_data = $this->Common_model->getprodReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
            foreach ($prod_data as $key => $val) {
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $val['Time_S']);
				if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['PAT_Gen0']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['PAT_Gen1']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['PAT_Gen2']);
				} elseif ($basic[0]['Format_Type'] == 2 || $basic[0]['Format_Type'] == 4) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['PAT_Gen1']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['PAT_Gen2']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Import_Kwh']);
				} elseif ($basic[0]['Format_Type'] == 3 || $basic[0]['Format_Type'] == 10) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['PAT_Gen1']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['PAT_Gen2']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Production_Total']);
				} else {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['Kwh_Positive']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['Kwh_Negative']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['KVar_Positive']);
				} 
                $excel_row++;
            }
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Production Report");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }

	function gridaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
            $basic = $this->Common_model->getbasicInfoimei($dname);
			$object->getActiveSheet()->setCellValue('A1', 'Grid Report');
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);            
            $object->getActiveSheet()->setCellValue('A4', 'Date');
            $object->getActiveSheet()->setCellValue('B4', 'Time');
            $object->getActiveSheet()->setCellValue('C4', 'R Volt');
            $object->getActiveSheet()->setCellValue('D4', 'Y Volt');
			$object->getActiveSheet()->setCellValue('E4', 'B Volt');
			$object->getActiveSheet()->setCellValue('F4', 'R Current');
			$object->getActiveSheet()->setCellValue('G4', 'Y Current');
			$object->getActiveSheet()->setCellValue('H4', 'R Current');
			$object->getActiveSheet()->setCellValue('I4', 'Power');
			$object->getActiveSheet()->setCellValue('J4', 'Power Factor');
           
            $grid_data = $this->Common_model->getgridReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
            foreach ($grid_data as $key => $val) {
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $val['Time_S']);
                $object->getActiveSheet()->setCellValue('C' . $excel_row, $val['RPhase_Volt']);
                $object->getActiveSheet()->setCellValue('D' . $excel_row, $val['YPhase_Volt']);
				$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['BPhase_Volt']);
				$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['RPhase_Current']);
				$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['YPhase_Current']);
				$object->getActiveSheet()->setCellValue('H' . $excel_row, $val['BPhase_Current']);
				$object->getActiveSheet()->setCellValue('I' . $excel_row, $val['Power']);
				$object->getActiveSheet()->setCellValue('J' . $excel_row, $val['Power_Factor']);
                $excel_row++;
            }
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Grid Report");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }
	
	function tempaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
			$basic = $this->Common_model->getbasicInfoimei($dname);
			$object->getActiveSheet()->setCellValue('A1', 'Temperature Report');
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);            
            $object->getActiveSheet()->setCellValue('A4', 'Date');
            $object->getActiveSheet()->setCellValue('B4', 'Time');
           if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
				$object->getActiveSheet()->setCellValue('C4', 'Ambient');
				$object->getActiveSheet()->setCellValue('D4', 'Hydraulic');
				$object->getActiveSheet()->setCellValue('E4', 'Gear');
				$object->getActiveSheet()->setCellValue('F4', 'Gen1');
				$object->getActiveSheet()->setCellValue('G4', 'Nacel');
				$object->getActiveSheet()->setCellValue('H4', 'Control');
				$object->getActiveSheet()->setCellValue('I4', 'Bearing');
			} elseif ($basic[0]['Format_Type'] == 2) {
				$object->getActiveSheet()->setCellValue('C4', 'Gen1');
				$object->getActiveSheet()->setCellValue('D4', 'Gear Oil');
				$object->getActiveSheet()->setCellValue('E4', 'Gen2');
				$object->getActiveSheet()->setCellValue('F4', 'Bearing');
				$object->getActiveSheet()->setCellValue('G4', 'Gear Box');
				$object->getActiveSheet()->setCellValue('H4', 'Main Bearing');
			} elseif ($basic[0]['Format_Type'] == 3) {
				$object->getActiveSheet()->setCellValue('C4', 'Thyristor');
				$object->getActiveSheet()->setCellValue('D4', 'Ambient');
				$object->getActiveSheet()->setCellValue('E4', 'Main Panel');
				$object->getActiveSheet()->setCellValue('F4', 'Gen1');
				$object->getActiveSheet()->setCellValue('G4', 'Gen2');
				$object->getActiveSheet()->setCellValue('H4', 'Bearing');
				$object->getActiveSheet()->setCellValue('I4', 'Gear');
				$object->getActiveSheet()->setCellValue('J4', 'Nacel');
				$object->getActiveSheet()->setCellValue('K4', 'Temp10');
			} elseif ($basic[0]['Format_Type'] == 4) {
				$object->getActiveSheet()->setCellValue('C4', 'Nacel');
				$object->getActiveSheet()->setCellValue('D4', 'Gen1');
				$object->getActiveSheet()->setCellValue('E4', 'Gen2');
				$object->getActiveSheet()->setCellValue('F4', 'Gen Bear1');
				$object->getActiveSheet()->setCellValue('G4', 'Gen Bear2');
				$object->getActiveSheet()->setCellValue('H4', 'Gear Oil');
			} elseif ($basic[0]['Format_Type'] == 7 || $basic[0]['Format_Type'] == 8) {
				$object->getActiveSheet()->setCellValue('C4', 'Nacel');
				$object->getActiveSheet()->setCellValue('D4', 'Cntl Panel');
				$object->getActiveSheet()->setCellValue('E4', 'Gear Bearing1');
				$object->getActiveSheet()->setCellValue('F4', 'Gear Bearing2');
				$object->getActiveSheet()->setCellValue('G4', 'Gear Box Oil');
				$object->getActiveSheet()->setCellValue('H4', 'Gen Winding 1');
				$object->getActiveSheet()->setCellValue('I4', 'Gen Winding 2');
				$object->getActiveSheet()->setCellValue('J4', 'Gen DE');
				$object->getActiveSheet()->setCellValue('K4', 'Gen DE NDE');
			} else {
				$object->getActiveSheet()->setCellValue('C4', 'Ambient');
				$object->getActiveSheet()->setCellValue('D4', 'Hydraulic');
				$object->getActiveSheet()->setCellValue('E4', 'Gear');
				$object->getActiveSheet()->setCellValue('F4', 'Gen1');
				$object->getActiveSheet()->setCellValue('G4', 'Gen2');
				$object->getActiveSheet()->setCellValue('H4', 'Nacel');
				$object->getActiveSheet()->setCellValue('I4', 'Control');
				$object->getActiveSheet()->setCellValue('J4', 'Bearing');
			}
            $temp_data = $this->Common_model->gettempReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
            foreach ($temp_data as $key => $val) {
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $val['Time_S']);
				if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['Ambient_Temp']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['Hydraulic_Temp']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Gear_Temp']);
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['Gen1_Temp']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['Nacel_Temp']);
					$object->getActiveSheet()->setCellValue('H' . $excel_row, $val['Control_Temp']);
					$object->getActiveSheet()->setCellValue('I' . $excel_row, $val['Bearing_Temp']);
				} elseif ($basic[0]['Format_Type'] == 2) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['G1_Temp']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['G2_Temp']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['G3_Temp']);
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['G4_Temp']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['G5_Temp']);
					$object->getActiveSheet()->setCellValue('H' . $excel_row, $val['G6_Temp']);					
				} elseif ($basic[0]['Format_Type'] == 3) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['Thyristor_Temp']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['Ambient_Temp']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Main_Panel_Temp']);
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['Gen1_Temp']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['Gen2_Temp']);
					$object->getActiveSheet()->setCellValue('H' . $excel_row, $val['Bearing_Temp']);
					$object->getActiveSheet()->setCellValue('I' . $excel_row, $val['Gear_Temp']);
					$object->getActiveSheet()->setCellValue('J' . $excel_row, $val['Nacel_Temp']);
					$object->getActiveSheet()->setCellValue('K' . $excel_row, $val['Temp10']);
				} elseif ($basic[0]['Format_Type'] == 4) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['Nacel_Temp']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['Gen1_Temp']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Gen2_Temp']);
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['Gen_Bear1_Temp']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['Gen_Bear2_Temp']);
					$object->getActiveSheet()->setCellValue('H' . $excel_row, $val['Gear_Oil_Temp']);					
				} elseif ($basic[0]['Format_Type'] == 7 || $basic[0]['Format_Type'] == 8) {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['Nacel_Temp']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['Control_Panel_Temp']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Gear_Bearing1_Temp']);
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['Gear_Bearing2_Temp']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['Gear_Box_Oil_Temp']);
					$object->getActiveSheet()->setCellValue('H' . $excel_row, $val['Gen_Winding1_Temp']);
					$object->getActiveSheet()->setCellValue('I' . $excel_row, $val['Gen_Winding2_Temp']);
					$object->getActiveSheet()->setCellValue('J' . $excel_row, $val['Gen_DE_Bearing_Temp']);
					$object->getActiveSheet()->setCellValue('K' . $excel_row, $val['Gen_DE_NDE_Bearing_Temp']);
				} else {
					$object->getActiveSheet()->setCellValue('C' . $excel_row, $val['Ambient_Temp']);
					$object->getActiveSheet()->setCellValue('D' . $excel_row, $val['Hydraulic_Temp']);
					$object->getActiveSheet()->setCellValue('E' . $excel_row, $val['Gear_Temp']);
					$object->getActiveSheet()->setCellValue('F' . $excel_row, $val['Gen1_Temp']);
					$object->getActiveSheet()->setCellValue('G' . $excel_row, $val['Gen2_Temp']);
					$object->getActiveSheet()->setCellValue('H' . $excel_row, $val['Nacel_Temp']);
					$object->getActiveSheet()->setCellValue('I' . $excel_row, $val['Control_Temp']);
					$object->getActiveSheet()->setCellValue('J' . $excel_row, $val['Bearing_Temp']);
				}
                $excel_row++;
            }
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Temperature Report");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }

	function alarmaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
            $basic = $this->Common_model->getbasicInfoimei($dname);
            $object->getActiveSheet()->setCellValue('A1', 'Alarm Log Report');
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);            
            $object->getActiveSheet()->setCellValue('A4', 'Date');
            $object->getActiveSheet()->setCellValue('B4', 'Time');
            $object->getActiveSheet()->setCellValue('C4', 'Error Status');
            $alarm_data = $this->Common_model->getalarmReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
            foreach ($alarm_data as $key => $val) {
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $val['Time_S']);
                $object->getActiveSheet()->setCellValue('C' . $excel_row, substr($val['Status'],0,61));
                $excel_row++;
            }
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Alarm Log");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }
	
	function alarmgrpaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
			//$fname = "Alarm-Log-Group";
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
			/*$feeders = $this->Common_model->get_feeder_list();
				foreach ($feeders as $key => $value) {
					$State = $value['State'];
				}*/
			$typelist = $this->Common_model->getDeviceList('', 1);
			$fname = "Alarm-Log-Group";
			$object->getActiveSheet()->setCellValue('A1', 'Alarm-Log-Group Report');
			//$object->getActiveSheet()->setCellValue('B1', $dname);
			//$object->getActiveSheet()->setCellValue('C1', $State);
            $object->getActiveSheet()->setCellValue('A2', 'Date');
            $object->getActiveSheet()->setCellValue('B2', 'Time');
			$object->getActiveSheet()->setCellValue('C2', 'Device');
            $object->getActiveSheet()->setCellValue('D2', 'Error Status');
           // $basic = $this->Common_model->getbasicInfo($dname);
            $alarmgrp_data = $this->Common_model->getalarmgrpReport($typelist,$sdate, $edate);
            $excel_row = 3;
            foreach ($alarmgrp_data as $key => $val) {
				$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $val['Time_S']);
				$object->getActiveSheet()->setCellValue('C' . $excel_row, $dev_name);
                $object->getActiveSheet()->setCellValue('D' . $excel_row, substr($val['Status'],0,61));
                $excel_row++;
            }
            $filename = $fname ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Alarm Log Group");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }
	
	function stophrsaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['sdate'])) {
			//$dname = urldecode($_REQUEST['dname']);
			$sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
		 $typelist = $this->Common_model->getDeviceList('', 1);           
			/*foreach ($typelist as $list) {
				$Region = $list->Region;
			}*/
			$object->getActiveSheet()->setCellValue('A1', 'Stop Hours Group Report');
			//$object->getActiveSheet()->setCellValue('B1', $Region);
			$fname = "Stop-Hours-Group ";			
            $object->getActiveSheet()->setCellValue('A2', 'Date');
            $object->getActiveSheet()->setCellValue('B2', 'Device');
            $object->getActiveSheet()->setCellValue('C2', 'Stop Hours');
           // $basic = $this->Common_model->getbasicInfo($dname);
		    $stophrvalues_data = $this->Common_model->getstophrsReport($typelist,$sdate, $edate);
            $excel_row = 3;			
			//$fname = "Stop-Hours-Group";
            foreach ($stophrvalues_data as $key => $val) {
				$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
				$stophrs = 24 - $val['Run'];
				$stophrs = $stophrs > 24 || $stophrs < 0 ? '000':$stophrs;                                        
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $dev_name);
                $object->getActiveSheet()->setCellValue('C' . $excel_row, $stophrs);
                $excel_row++;
            }
            $filename = $fname ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Stop Hours Group");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }

	function dgrindvaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
			$fname = "DGR-Individual";
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
            $basic = $this->Common_model->getbasicInfoimei($dname);
            $object->getActiveSheet()->setCellValue('A1', 'DGR Individual Report');
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);
			$object->getActiveSheet()->setCellValue('A4', 'Date');
            $object->getActiveSheet()->setCellValue('B4', 'Import');
            $object->getActiveSheet()->setCellValue('C4', 'Export');
            $object->getActiveSheet()->setCellValue('D4', 'Total Hours');
            $object->getActiveSheet()->setCellValue('E4', 'Run Hours');
            $object->getActiveSheet()->setCellValue('F4', 'GD Hours');
            $object->getActiveSheet()->setCellValue('G4', 'BD Hours');
            $object->getActiveSheet()->setCellValue('H4', 'Lull Hours');
            $object->getActiveSheet()->setCellValue('I4', 'GA%');
			$object->getActiveSheet()->setCellValue('J4', 'MA%');
           
		    $dgrindv_data = $this->Common_model->getdgrindividualReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
			$Sum_Import = 0;
			$Sum_Gen = 0;
			$Sum_Run = 0;
			$Sum_GD = 0;
			$Sum_BD = 0;
			$Sum_Lull = 0;
            foreach ($dgrindv_data as $key => $val) {
				if ($basic[0]['Format_Type'] == 1 || $basic[0]['Format_Type'] == 6) {
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
					$Loss_Due_To_BD = ($Total_Gen/$Run_Hours) * $BD_Hours; 
					$MA_Percent=((24-$BD_Hours) / 24 ) *100;
					$Sum_Import += $Array_Import;
					$Sum_Gen += $Total_Gen;
					$Sum_Run += $Run_Hours;
					$Sum_GD += $GD_Hours;
					$Sum_BD += $BD_Hours;
					$Sum_Lull += $Lull_Hours;
				} elseif ($basic[0]['Format_Type'] == 2) {		
						//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
					$Import_LCS = $val['Import_Max']- $val['Import_Min'];
					$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
					$Total_Gen = (($val['Gen1_Max']-$val['Gen1_Min'])+($val['Gen2_Max']-$val['Gen2_Min']));
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
				}	elseif ($basic[0]['Format_Type'] == 3) {		
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
				} elseif ($basic[0]['Format_Type'] == 4) {		
					//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
					$Import_LCS = $val['Import_Max']- $val['Import_Min'];
					$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
					$Total_Gen = (($val['Gen1_Max']-$val['Gen1_Min'])+($val['Gen2_Max']-$val['Gen2_Min']));
					$Total_Gen = $Total_Gen>0 && $Total_Gen<=15000?$Total_Gen:'0';
					$Gen1 = (($val['Gen1H_Max']-$val['Gen1H_Min'])+($val['Gen2H_Max']-$val['Gen2H_Min']));
					$Gen1=$Gen1>'24' && $Gen1<'50'?'24':$Gen1;	
					$Run_Hours=$Gen1;
					$Run_Hours=$Run_Hours>0 && $Run_Hours<=25?$Run_Hours:'0';
					$GD_Hours = 0;
					//$GD_Hours = round(($val['Diff']/3600),1);
					$GD_Hours =($GD_Hours >=0 && $GD_Hours <=24)?$GD_Hours : '0';
					$BD_Hours = 0; 
					//$BD_Hours = round(($val['Diff1']/3600),1);
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
				} elseif ($basic[0]['Format_Type'] == 7 || $basic[0]['Format_Type'] == 8) {
					//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
					$Import_LCS = $val['Import_Max']- $val['Import_Min'];
					$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
					$Total_Gen = $val['Gen1_Max']-$val['Gen1_Min'];
					$Total_Gen = $Total_Gen>0 && $Total_Gen<=15000?$Total_Gen:'0';
					$Run = $val['Run_Max']-$val['Run_Min'];
					$Gen1 = $val['Gen1H_Max']-$val['Gen1H_Min'];
					$Gen1=$Gen1>'24' && $Gen1<'50'?'24':$Gen1;	
					$Lull_Hours=24-$Run;
					if($Lull_Hours==(-1))
					$Lull_Hours=0;
					$Run_Hours=$Run;
					$Run_Hours=$Run_Hours>0 && $Run_Hours<=25?$Run_Hours:'0';
					$GD_Hours = 24-($val['Line_Max']-$val['Line_Min']);
					$GD_Hours=$GD_Hours>0 && $GD_Hours<=25?$GD_Hours:'0';
					$GA_Percent=((24 - $GD_Hours) / 24) * 100 ;
					$Lull_Hours=$Lull_Hours>0 && $Lull_Hours<=25?$Lull_Hours:'0';
					$Loss_Due_To_GD = ($Total_Gen/$Run_Hours) * $GD_Hours;
					$BD_Hours=24-($Gen1);
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
					$Run = $val['Run_Max']-$val['Run_Min'];
					$Gen1 = (($val['Gen1H_Max']-$val['Gen1H_Min'])+($val['Gen2H_Max']-$val['Gen2H_Min']));
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
				}
				$object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, round($Import_LCS,1));
                $object->getActiveSheet()->setCellValue('C' . $excel_row, round($Total_Gen,1));
				$object->getActiveSheet()->setCellValue('D' . $excel_row, 24);
				$object->getActiveSheet()->setCellValue('E' . $excel_row, round($Run_Hours,1));
				$object->getActiveSheet()->setCellValue('F' . $excel_row, round($GD_Hours,1));
				$object->getActiveSheet()->setCellValue('G' . $excel_row, round($BD_Hours,1));
				$object->getActiveSheet()->setCellValue('H' . $excel_row, round($Lull_Hours,1));
				$object->getActiveSheet()->setCellValue('I' . $excel_row, round($GA_Percent,1));
				$object->getActiveSheet()->setCellValue('J' . $excel_row, round($MA_Percent,1));
                $excel_row++;
            }
			$total_row = $excel_row+1;
			$object->getActiveSheet()->setCellValue('A' . $total_row, 'Total');
            $object->getActiveSheet()->setCellValue('B' . $total_row, round($Sum_Import,2));
            $object->getActiveSheet()->setCellValue('C' . $total_row, round($Sum_Gen,2));
            $object->getActiveSheet()->setCellValue('D' . $total_row, '');
            $object->getActiveSheet()->setCellValue('E' . $total_row, round($Sum_Run,2));
            $object->getActiveSheet()->setCellValue('F' . $total_row, round($Sum_GD,2));
            $object->getActiveSheet()->setCellValue('G' . $total_row, round($Sum_BD,2));
            $object->getActiveSheet()->setCellValue('H' . $total_row, round($Sum_Lull,2));
            $object->getActiveSheet()->setCellValue('I' . $total_row, '');
			$object->getActiveSheet()->setCellValue('J' . $total_row, '');
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("DGR Individual Report");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }
	
	function dgrgrpaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['sdate'])) {
			//$dname = urldecode($_REQUEST['dname']);
			$fname = "DGR-Grouping";
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
		/*$feeders = $this->Common_model->get_feeder_list();
		foreach ($feeders as $key => $value) {
			$State = $value['State'];
		}*/
			$typelist = $this->Common_model->getDeviceList('', 1);
			foreach ($typelist as $list) {
				$FType = $list->Format_Type;
			}
			$object->getActiveSheet()->setCellValue('A1', 'DGR Grouping Report');
			//$object->getActiveSheet()->setCellValue('B1', $dname);
			//$object->getActiveSheet()->setCellValue('C1', $State);
			$object->getActiveSheet()->setCellValue('A2', 'Date');
			$object->getActiveSheet()->setCellValue('B2', 'Device Name');
            $object->getActiveSheet()->setCellValue('C2', 'Import');
            $object->getActiveSheet()->setCellValue('D2', 'Export');
            $object->getActiveSheet()->setCellValue('E2', 'Total Hours');
            $object->getActiveSheet()->setCellValue('F2', 'Run Hours');
            $object->getActiveSheet()->setCellValue('G2', 'GD Hours');
            $object->getActiveSheet()->setCellValue('H2', 'BD Hours');
            $object->getActiveSheet()->setCellValue('I2', 'Lull Hours');
            $object->getActiveSheet()->setCellValue('J2', 'GA%');
			$object->getActiveSheet()->setCellValue('K2', 'MA%');
           
			$dgrgrpvalues_data = $this->Common_model->getdgrgrpReport($typelist,$sdate, $edate);
			//print_r($typelist);die;
            $excel_row = 3;
			$Sum_Import = 0;
			$Sum_Gen = 0;
			$Sum_Run = 0;
			$Sum_GD = 0;
			$Sum_BD = 0;
			$Sum_Lull = 0;
            foreach ($dgrgrpvalues_data as $key => $val) {
				$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');								
				if ($FType == 1 || $FType == 6) {
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
						
				} elseif ($FType == 2) {		
						//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
						$Import_LCS = $val['Import_Max']- $val['Import_Min'];
						$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
						$Total_Gen = (($val['Gen1_Max']-$val['Gen1_Min'])+($val['Gen2_Max']-$val['Gen2_Min']));
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
				}	elseif ($FType == 3) {		
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
				} elseif ($FType == 4) {		
						//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
						$Import_LCS = $val['Import_Max']- $val['Import_Min'];
						$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
						$Total_Gen = (($val['Gen1_Max']-$val['Gen1_Min'])+($val['Gen2_Max']-$val['Gen2_Min']));
						$Total_Gen = $Total_Gen>0 && $Total_Gen<=15000?$Total_Gen:'0';
						$Gen1 = (($val['Gen1H_Max']-$val['Gen1H_Min'])+($val['Gen2H_Max']-$val['Gen2H_Min']));
						$Gen1=$Gen1>'24' && $Gen1<'50'?'24':$Gen1;	
						$Run_Hours=$Gen1;
						$Run_Hours=$Run_Hours>0 && $Run_Hours<=25?$Run_Hours:'0';
						$GD_Hours = 0;
						//$GD_Hours = round(($val['Diff']/3600),1);
						$GD_Hours =($GD_Hours >=0 && $GD_Hours <=24)?$GD_Hours : '0';
						$BD_Hours = 0; 
						//$BD_Hours = round(($val['Diff1']/3600),1);
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
				} elseif ($FType == 7 || $FType == 8) {
						//$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');
						$Import_LCS = $val['Import_Max']- $val['Import_Min'];
						$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
						$Total_Gen = $val['Gen1_Max']-$val['Gen1_Min'];
						$Total_Gen = $Total_Gen>0 && $Total_Gen<=15000?$Total_Gen:'0';
						$Run = $val['Run_Max']-$val['Run_Min'];
						$Gen1 = $val['Gen1H_Max']-$val['Gen1H_Min'];
						$Gen1=$Gen1>'24' && $Gen1<'50'?'24':$Gen1;	
						$Lull_Hours=24-$Run;
						if($Lull_Hours==(-1))
						$Lull_Hours=0;
						$Run_Hours=$Run;
						$Run_Hours=$Run_Hours>0 && $Run_Hours<=25?$Run_Hours:'0';
						$GD_Hours = 24-($val['Line_Max']-$val['Line_Min']);
						$GD_Hours=$GD_Hours>0 && $GD_Hours<=25?$GD_Hours:'0';
						$GA_Percent=((24 - $GD_Hours) / 24) * 100 ;
						$Lull_Hours=$Lull_Hours>0 && $Lull_Hours<=25?$Lull_Hours:'0';
						$Loss_Due_To_GD = ($Total_Gen/$Run_Hours) * $GD_Hours;
						$BD_Hours=24-($Gen1);
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
						$Import_LCS = $val['Import_Max']- $val['Import_Min'];
						$Array_Import = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
						$Total_Gen = $val['Gen1_Max']-$val['Gen1_Min'];
						$Total_Gen = $Total_Gen>0 && $Total_Gen<=15000?$Total_Gen:'0';
						$Run = $val['Run_Max']-$val['Run_Min'];
						$Gen1 = (($val['Gen1H_Max']-$val['Gen1H_Min'])+($val['Gen2H_Max']-$val['Gen2H_Min']));
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
				}
                                        
				$object->getActiveSheet()->setCellValue('A' . $excel_row, $val['Date_S']);
				$object->getActiveSheet()->setCellValue('B' . $excel_row, $dev_name);
                $object->getActiveSheet()->setCellValue('C' . $excel_row, round($Import_LCS,1));
                $object->getActiveSheet()->setCellValue('D' . $excel_row, round($Total_Gen,1));
				$object->getActiveSheet()->setCellValue('E' . $excel_row, 24);
				$object->getActiveSheet()->setCellValue('F' . $excel_row, round($Run_Hours,1));
				$object->getActiveSheet()->setCellValue('G' . $excel_row, round($GD_Hours,1));
				$object->getActiveSheet()->setCellValue('H' . $excel_row, round($BD_Hours,1));
				$object->getActiveSheet()->setCellValue('I' . $excel_row, round($Lull_Hours,1));
				$object->getActiveSheet()->setCellValue('J' . $excel_row, round($GA_Percent,1));
				$object->getActiveSheet()->setCellValue('K' . $excel_row, round($MA_Percent,1));
                $excel_row++;
            }
			$total_row = $excel_row+1;
			$object->getActiveSheet()->setCellValue('A' . $total_row, 'Total');
			$object->getActiveSheet()->setCellValue('B' . $total_row, '');
            $object->getActiveSheet()->setCellValue('C' . $total_row, round($Sum_Import,2));
            $object->getActiveSheet()->setCellValue('D' . $total_row, round($Sum_Gen,2));
            $object->getActiveSheet()->setCellValue('E' . $total_row, '');
            $object->getActiveSheet()->setCellValue('F' . $total_row, round($Sum_Run,2));
            $object->getActiveSheet()->setCellValue('G' . $total_row, round($Sum_GD,2));
            $object->getActiveSheet()->setCellValue('H' . $total_row, round($Sum_BD,2));
            $object->getActiveSheet()->setCellValue('I' . $total_row, round($Sum_Lull,2));
            $object->getActiveSheet()->setCellValue('J' . $total_row, '');
			$object->getActiveSheet()->setCellValue('K' . $total_row, '');
			
            $filename = $fname ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("DGR Grouping");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }
	
	function finyearaction() {
         $this->load->model('common/Common_model');
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel.php";
        require_once APPPATH . "third_party\PHPExcel-1.8\Classes\PHPExcel\Writer\Excel2007.php";
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        if (!empty($_REQUEST['dname']) && !empty($_REQUEST['sdate'])) {
			$dname = urldecode($_REQUEST['dname']);
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
			$Months=array("4"=>"Apr-".$sdate."", "5"=>"May-".$sdate."","6"=> "Jun-".$sdate."","7"=>"Jul-".$sdate."" ,"8" => "Aug-".$sdate."","9"=> "Sep-".$sdate."", "10"=> "Oct-".$sdate."", "11"=>"Nov-".$sdate."", "12"=>"Dec-".$sdate."","13"=>"Jan-".$edate."","14"=>"Feb-".$edate."","15"=>"Mar-".$edate."");
			$Months_arr1=array("4","5","6","7","8","9","10","11","12","1","2","3");
//            echo $dname . " " . $sdate . " " . $edate;
//            die;
			//for($Count=3;$Count<=14;$Count++){
			$basic = $this->Common_model->getbasicInfoimei($dname);
            $object->getActiveSheet()->setCellValue('A1', 'Financial Year Report');
			$object->getActiveSheet()->setCellValue('B1', 'from '.$sdate);
			$object->getActiveSheet()->setCellValue('C1', 'to '.$edate);
			$object->getActiveSheet()->setCellValue('A2', 'Device Name');
			$object->getActiveSheet()->setCellValue('B2', $basic[0]['Device_Name']);
			$object->getActiveSheet()->setCellValue('C2', 'State');
			$object->getActiveSheet()->setCellValue('D2', $basic[0]['State']);
            $object->getActiveSheet()->setCellValue('A3', 'Feeder');
			$object->getActiveSheet()->setCellValue('B3', $basic[0]['Connect_Feeder']);
			$object->getActiveSheet()->setCellValue('C3', 'Location');
			$object->getActiveSheet()->setCellValue('D3', $basic[0]['Site_Location']);
			$object->getActiveSheet()->setCellValue('A4', 'Apr-'.$sdate);
            $object->getActiveSheet()->setCellValue('B4', 'May-'.$sdate);
            $object->getActiveSheet()->setCellValue('C4', 'Jun-'.$sdate);
            $object->getActiveSheet()->setCellValue('D4', 'Jul-'.$sdate);
			$object->getActiveSheet()->setCellValue('E4', 'Aug-'.$sdate);
			$object->getActiveSheet()->setCellValue('F4', 'Sep-'.$sdate);
			$object->getActiveSheet()->setCellValue('G4', 'Oct-'.$sdate);
			$object->getActiveSheet()->setCellValue('H4', 'Nov-'.$sdate);
			$object->getActiveSheet()->setCellValue('I4', 'Dec-'.$sdate);
			$object->getActiveSheet()->setCellValue('J4', 'Jan-'.$edate);
			$object->getActiveSheet()->setCellValue('K4', 'Feb-'.$edate);
			$object->getActiveSheet()->setCellValue('L4', 'Mar-'.$edate);
			$object->getActiveSheet()->setCellValue('M4', 'Total');
           
            $finyear_data = $this->Common_model->getFinyearReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
            $excel_row = 5;
			$Sum_Gen = 0;
            foreach ($finyear_data as $key => $val) {
						$gad_gen[$val['Month']] = $val['gad_gen'] > 0 && $val['gad_gen'] < 250000?$val['gad_gen']:'000';	
						$Sum_Gen += $gad_gen[$val['Month']];
			}
			//foreach($Months_arr1 as $Month_val){
                $object->getActiveSheet()->setCellValue('A' . $excel_row, $gad_gen["4"]);
                $object->getActiveSheet()->setCellValue('B' . $excel_row, $gad_gen["5"]);
                $object->getActiveSheet()->setCellValue('C' . $excel_row, $gad_gen["6"]);
                $object->getActiveSheet()->setCellValue('D' . $excel_row, $gad_gen["7"]);
				$object->getActiveSheet()->setCellValue('E' . $excel_row, $gad_gen["8"]);
				$object->getActiveSheet()->setCellValue('F' . $excel_row, $gad_gen["9"]);
				$object->getActiveSheet()->setCellValue('G' . $excel_row, $gad_gen["10"]);
				$object->getActiveSheet()->setCellValue('H' . $excel_row, $gad_gen["11"]);
				$object->getActiveSheet()->setCellValue('I' . $excel_row, $gad_gen["12"]);
				$object->getActiveSheet()->setCellValue('J' . $excel_row, $gad_gen["1"]);
				$object->getActiveSheet()->setCellValue('K' . $excel_row, $gad_gen["2"]);
				$object->getActiveSheet()->setCellValue('L' . $excel_row, $gad_gen["3"]);
				$object->getActiveSheet()->setCellValue('M' . $excel_row, $Sum_Gen);
               // $excel_row++;
           // }
            $filename = $basic[0]['Device_Name'] ." Report-on-" . date("Y-m-d-H-i-s") . '.xlsx';
            $object->getActiveSheet()->setTitle("Financial Year Report");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            ob_end_clean();
            $writer->save('php://output');
            exit;
        }
    }


}
