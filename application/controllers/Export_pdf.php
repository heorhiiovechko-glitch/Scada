<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
ini_set('memory_limit', '512M');
class Export_pdf extends CI_Controller {
    function index() {
         $this->load->model('common/Common_model');
       //  $this->load->view("excel_export_view", $data);
    }
    public function pw_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
		$output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Windspeed VS Power Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$basic = $this->Common_model->getbasicInfoimei($dname);
        $content = '';
        $top = "Windspeed VS Power - ".$basic[0]['Device_Name']." , Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
        $content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th width="20%">Date</th>  
<th width="20%">Time</th>  
 
<th width="20%">Windspeed</th>  
<th width="20%">Power</th>  
</tr>  
';
        $pw_data = $this->Common_model->getpwReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
        foreach ($pw_data as $key => $row):

            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
 
<td>' . $row["Windspeed"] . '</td>  
<td>' . $row["Power"] . '</td>  
</tr>  
';
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Power_Windspeed.pdf', 'I');
		exit;
    }
	
	public function overview_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Overview Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$basic = $this->Common_model->getbasicInfoimei($dname);
		$FType = $basic[0]['Format_Type'];
		$content = '';
        $top = "Overview Report - ".$basic[0]['Device_Name']." , Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
		if ($FType == 1 || $FType == 6  || $FType == 10) {
		$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >GRPM</th>  
<th >RRPM</th>
<th >Windspeed</th> 
<th >Pitch</th>
<th >Power</th> 
<th >Status</th> 
</tr>  
';	
		} else {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >GRPM</th>  
<th >RRPM</th>
<th >Windspeed</th> 
<th >Power</th> 
<th >Status</th> 
</tr>  
';
		}
        
        $overview_data = $this->Common_model->getoverviewReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
        foreach ($overview_data as $key => $row):
			if ($FType == 1 || $FType == 6  || $FType == 10) {
            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["GRPM"] . '</td>  
<td>' . $row["RRPM"] . '</td>  
<td>' . $row["Windspeed"] . '</td>  
<td>' . $row["Pitch"] . '</td>
<td>' . $row["Power"] . '</td>
<td>' . $row["Status"] . '</td>   
</tr>  
';
			} else {
				$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["GRPM"] . '</td>  
<td>' . $row["RRPM"] . '</td>  
<td>' . $row["Windspeed"] . '</td>  
<td>' . $row["Power"] . '</td>
<td>' . $row["Status"] . '</td>   
</tr>  
';
			}
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Overview_Report.pdf', 'I');
		exit;	
    }

	public function prod_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Production Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$basic = $this->Common_model->getbasicInfoimei($dname);
		$FType = $basic[0]['Format_Type'];
		$content = '';
        $top = "Production Report - ".$basic[0]['Device_Name']." , Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
		if ($FType == 1 || $FType == 6) {
		$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >PAT Gen0</th>  
<th >PAT Gen1</th>
<th >Net Total</th> 
</tr>  
';	
		} elseif ($FType == 2 || $FType == 4) {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >PAT Gen1</th>  
<th >PAT Gen2</th>
<th >Import Kwh</th> 
</tr>  
';
		} elseif ($FType == 3 || $FType == 10) {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >PAT Gen1</th>  
<th >PAT Gen2</th>
<th >Production Total</th> 
</tr>  
';
		} else {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Kwh Positive</th>  
<th >Kwh Negative</th>
<th >KVar Positive</th> 
</tr>  
';
		}
         
        $prod_data = $this->Common_model->getprodReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
        foreach ($prod_data as $key => $row):
			if ($FType == 1 || $FType == 6) {
            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["PAT_Gen0"] . '</td>  
<td>' . $row["PAT_Gen1"] . '</td>  
<td>' . $row["PAT_Gen2"] . '</td>  
</tr>  
';
			} elseif ($FType == 2 || $FType == 4) {
				$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["PAT_Gen1"] . '</td>  
<td>' . $row["PAT_Gen2"] . '</td>  
<td>' . $row["Import_Kwh"] . '</td>  
</tr>  
';
			} elseif ($FType == 3 || $FType == 10) {
				$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["PAT_Gen1"] . '</td>  
<td>' . $row["PAT_Gen2"] . '</td>  
<td>' . $row["Production_Total"] . '</td>  
</tr>  
';
			} else {
				$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["Kwh_Positive"] . '</td>  
<td>' . $row["Kwh_Negative"] . '</td>  
<td>' . $row["KVar_Positive"] . '</td>  
</tr>  
';
			}
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Production_Report.pdf', 'I');
		exit;
    }

	 public function grid_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Grid Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$basic = $this->Common_model->getbasicInfoimei($dname);
        $content = '';
        $top = "Grid- ".$basic[0]['Device_Name']." , Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
        $content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th width="10%">Date</th>  
<th width="10%">Time</th>  
<th width="10%">R Volt</th>  
<th width="10%">Y Volt</th> 
<th width="10%">B Volt</th>   
<th width="10%">R Current</th>  
<th width="10%">Y Current</th>  
<th width="10%">B Current</th>  
<th width="10%">Power</th>  
<th width="10%">Power Factor</th>  
</tr>  
';
        $grid_data = $this->Common_model->getgridReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
        foreach ($grid_data as $key => $row):

            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>   
<td>' . $row["RPhase_Volt"] . '</td>  
<td>' . $row["YPhase_Volt"] . '</td>  
<td>' . $row["BPhase_Volt"] . '</td>  
<td>' . $row["RPhase_Current"] . '</td>  
<td>' . $row["YPhase_Current"] . '</td>  
<td>' . $row["BPhase_Current"] . '</td>  
<td>' . $row["Power"] . '</td>
<td>' . $row["Power_Factor"] . '</td>  
</tr>  
';
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Grid_Report.pdf', 'I');
		exit;
    }
	
	public function temp_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Temperature Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$basic = $this->Common_model->getbasicInfoimei($dname);
		$FType = $basic[0]['Format_Type'];
		$content = '';
        $top = "Temperature Report - ".$basic[0]['Device_Name']." , Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
		if ($FType == 1 || $FType == 6) {
		$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Ambient</th>  
<th >Hydraulic</th>
<th >Gear</th> 
<th >Gen1</th> 
<th >Nacel</th> 
<th >Control</th> 
<th >Bearing</th> 
</tr>  
';	
		} elseif ($FType == 2) {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Gen1</th>  
<th >Gear oil</th>  
<th >Gen2</th>
<th >Bearing</th> 
<th >Gear Box</th> 
<th >Main Bearing</th> 
</tr>  
';
		} elseif ($FType == 3) {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Thyristor</th>  
<th >Ambient</th>  
<th >Main Panel</th>
<th >Gen1</th> 
<th >Gen2</th> 
<th >Bearing</th> 
<th >Gear</th> 
<th >Nacel</th> 
<th >Temp10</th> 
</tr>  
';
		} elseif ($FType == 4) {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Nacel</th>  
<th >Gen1</th>  
<th >Gen2</th>
<th >Gen Bear1</th> 
<th >Gen Bear2</th> 
<th >Gear Oil</th> 
</tr>  
';
		} elseif ($FType == 7 || $FType == 8) {
		$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Nacel</th>  
<th >Cntl Panel</th>
<th >Gear Bearing1</th> 
<th >Gear Bearing2</th> 
<th >Gear Box Oil</th> 
<th >Gen Winding 1</th> 
<th >Gen Winding 2</th>
<th >Gen DE</th> 
<th >Gen DE NDE</th> 
</tr>  
';	
		} else {
			$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Ambient</th>  
<th >Hydraulic</th>
<th >Gear</th> 
<th >Gen1</th> 
<th >Gen2</th> 
<th >Nacel</th> 
<th >Control</th> 
<th >Bearing</th> 
</tr>  
';	
		}
        
        $temp_data = $this->Common_model->gettempReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
        foreach ($temp_data as $key => $row):
			if ($FType == 1 || $FType == 6) {
            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["Ambient_Temp"] . '</td>  
<td>' . $row["Hydraulic_Temp"] . '</td>  
<td>' . $row["Gear_Temp"] . '</td> 
<td>' . $row["Gen1_Temp"] . '</td>   
<td>' . $row["Nacel_Temp"] . '</td>  
<td>' . $row["Control_Temp"] . '</td>  
<td>' . $row["Bearing_Temp"] . '</td>  
</tr>  
';
			} elseif ($FType == 2) {
				$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["G1_Temp"] . '</td>  
<td>' . $row["G2_Temp"] . '</td>  
<td>' . $row["G3_Temp"] . '</td> 
<td>' . $row["G4_Temp"] . '</td>   
<td>' . $row["G5_Temp"] . '</td>  
<td>' . $row["G6_Temp"] . '</td>   
</tr>  
';
			} elseif ($FType == 3) {
				$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["Thyristor_Temp"] . '</td>  
<td>' . $row["Ambient_Temp"] . '</td>  
<td>' . $row["Main_Panel_Temp"] . '</td> 
<td>' . $row["Gen1_Temp"] . '</td>   
<td>' . $row["Gen2_Temp"] . '</td>  
<td>' . $row["Bearing_Temp"] . '</td>   
<td>' . $row["Gear_Temp"] . '</td>  
<td>' . $row["Nacel_Temp"] . '</td>  
<td>' . $row["Temp10"] . '</td>  
</tr>  
';
			} elseif ($FType == 4) {
				$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["Nacel_Temp"] . '</td>  
<td>' . $row["Gen1_Temp"] . '</td>  
<td>' . $row["Gen2_Temp"] . '</td> 
<td>' . $row["Gen_Bear1_Temp"] . '</td>   
<td>' . $row["Gen_Bear2_Temp"] . '</td>  
<td>' . $row["Gear_Oil_Temp"] . '</td>   
</tr>  
';
			} elseif ($FType == 7 || $FType == 8) {
            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["Ambient_Temp"] . '</td>  
<td>' . $row["Hydraulic_Temp"] . '</td>  
<td>' . $row["Gear_Temp"] . '</td> 
<td>' . $row["Gen1_Temp"] . '</td>   
<td>' . $row["Nacel_Temp"] . '</td>  
<td>' . $row["Control_Temp"] . '</td>  
<td>' . $row["Bearing_Temp"] . '</td>  
</tr>  
';
			} else {
			$output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . $row["Nacel_Temp"] . '</td>  
<td>' . $row["Control_Panel_Temp"] . '</td>  
<td>' . $row["Gear_Bearing1_Temp"] . '</td> 
<td>' . $row["Gear_Bearing2_Temp"] . '</td>  
<td>' . $row["Gear_Box_Oil_Temp"] . '</td>    
<td>' . $row["Gen_Winding1_Temp"] . '</td>  
<td>' . $row["Gen_Winding2_Temp"] . '</td>  
<td>' . $row["Gen_DE_Bearing_Temp"] . '</td>  
<td>' . $row["Gen_DE_NDE_Bearing_Temp"] . '</td>  
</tr>  
';
			}
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Temperature_Report.pdf', 'I');
		exit;
    }

 public function alarm_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Alarm Log Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$basic = $this->Common_model->getbasicInfoimei($dname);
        $content = '';
        $top = "Alarm Log - ".$basic[0]['Device_Name']." , Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
        $content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Status</th>  
</tr>  
';
        $alarm_data = $this->Common_model->getalarmReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
        foreach ($alarm_data as $key => $row):

            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td>  
<td>' . substr($row["Status"],0,61) . '</td>  
</tr>  
';
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Alarm_Log.pdf', 'I');
		exit;
    }
	
	public function alarmgrp_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Alarm Log Group Report ");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		/*$feeders = $this->Common_model->get_feeder_list();
				foreach ($feeders as $key => $value) {
					$State = $value['State'];
				}*/
		$typelist = $this->Common_model->getDeviceList('', 1);
        $content = '';
        $top = "Alarm Log Group Report ";
        $content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Time</th>  
<th >Device</th>  
<th >Status</th>  
</tr>  
';
        //$basic = $this->Common_model->getbasicInfo($dname);

        $alarmgrp_data = $this->Common_model->getalarmgrpReport($typelist,$sdate, $edate);
        foreach ($alarmgrp_data as $key => $row):
			$dev_name = $this->Common_model->commonDataFetching($row['IMEI'],'Device_Name');
            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $row["Time_S"] . '</td> 
<td>' . $dev_name . '</td>   
<td>' . substr($row["Status"],0,61) . '</td>  
</tr>  
';
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Alarm_Log_Group.pdf', 'I');
		exit;
    }
	
	public function stophrs_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        //$dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Stop Hours Group Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$typelist = $this->Common_model->getDeviceList('', 1);
        /*foreach ($typelist as $list) {
			$Region = $list->Region;
		}*/
		$content = '';		
			$top = "Stop Hours Group Report";		
        $content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Device</th>  
<th >Stop Hours</th>  
</tr>  
';
        //$basic = $this->Common_model->getbasicInfo($dname);
		$stophrs_data = $this->Common_model->getstophrsReport($typelist,$sdate, $edate);
        foreach ($stophrs_data as $key => $row):
			$dev_name = $this->Common_model->commonDataFetching($row['IMEI'],'Device_Name');
			$stophrs = 24 - $row['Run'];
			$stophrs = $stophrs > 24 || $stophrs < 0 ? '000':$stophrs;
                                        
            $output .= '<tr>  
<td>' . $row["Date_S"] . '</td>  
<td>' . $dev_name . '</td>   
<td>' . $stophrs . '</td>  
</tr>  
';
        endforeach;
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Stop_Hours_Group.pdf', 'I');
		exit;
    }

	public function dgrindv_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("DGR Individual Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		$basic = $this->Common_model->getbasicInfoimei($dname);
		$FType = $basic[0]['Format_Type'];
		$content = '';
        $top = "DGR Individual Report - ".$basic[0]['Device_Name']." , Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
		$content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Date</th>  
<th >Import</th>  
<th >Export</th>  
<th >Total Hours</th>
<th >Run Hours</th> 
<th >GD Hours</th> 
<th >BD Hours</th> 
<th >Lull Hours</th> 
<th >GA%</th> 
<th >MA%</th> 
</tr>  
';	
		$dgrindv_data = $this->Common_model->getdgrindividualReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
		$Sum_Import = 0;
		$Sum_Gen = 0;
		$Sum_Run = 0;
		$Sum_GD = 0;
		$Sum_BD = 0;
		$Sum_Lull = 0;           
        foreach ($dgrindv_data as $key => $val):
			if ($FType == 1 || $FType == 6) {
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
            $output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 2) {
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 3) {
					$Import_LCS = $val['Import_Max']- $val['Import_Min'];
					$Import_LCS = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 4) {
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 7 || $FType == 8) {
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
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
												
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			}
        endforeach;
		$output .= '<tr>  
<td>Total</td>  
<td>' . round($Sum_Import,1) . '</td>  
<td>' . round($Sum_Gen,1) . '</td>  
<td>' . '' . '</td>  
<td>' . round($Sum_Run,1) . '</td> 
<td>' . round($Sum_GD,1) . '</td>   
<td>' . round($Sum_BD,1) . '</td>  
<td>' . round($Sum_Lull,1) . '</td>  
<td>' . '' . '</td> 
<td>' . '' . '</td>   
</tr>  
';
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('DGR_Individual_Report.pdf', 'I');
		exit;
    }

	
	public function dgrgrp_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        //$dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('L', PDF_UNIT, array(400, 200), true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("DGR Grouping Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
		/*$feeders = $this->Common_model->get_feeder_list();
		foreach ($feeders as $key => $value) {
			$State = $value['State'];
		}*/
		$typelist = $this->Common_model->getDeviceList('', 1);
			foreach ($typelist as $list) {
				$FType = $list->Format_Type;
			}
					
        $content = '';
        $top = "DGR Grouping Report ";
        $content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>   
<th >Date</th> 
<th >Device Name</th> 
<th >Import</th>  
<th >Export</th>  
<th >Total Hours</th>
<th >Run Hours</th> 
<th >GD Hours</th> 
<th >BD Hours</th> 
<th >Lull Hours</th> 
<th >GA%</th> 
<th >MA%</th></tr>

';
       $dgrgrp_data = $this->Common_model->getdgrgrpReport($typelist,$sdate, $edate);
		$Sum_Import = 0;
		$Sum_Gen = 0;
		$Sum_Run = 0;
		$Sum_GD = 0;
		$Sum_BD = 0;
		$Sum_Lull = 0;           
        foreach ($dgrgrp_data as $key => $val):
			$dev_name = $this->Common_model->commonDataFetching($val['IMEI'],'Device_Name');								
			if ($FType == 1 || $FType == 6) {
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
            $output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . $dev_name . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 2) {
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . $dev_name . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 3) {
					$Import_LCS = $val['Import_Max']- $val['Import_Min'];
					$Import_LCS = $Import_LCS>0 && $Import_LCS<=500?$Import_LCS:'0';
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . $dev_name . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 4) {
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . $dev_name . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			} elseif ($FType == 7 || $FType == 8) {
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
				
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . $dev_name . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
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
												
				$output .= '<tr>  
<td>' . $val["Date_S"] . '</td>  
<td>' . $dev_name . '</td>  
<td>' . round($Import_LCS,1) . '</td>  
<td>' . round($Total_Gen,1) . '</td>  
<td>' . 24 . '</td>  
<td>' . round($Run_Hours,1) . '</td> 
<td>' . round($GD_Hours,1) . '</td>   
<td>' . round($BD_Hours,1) . '</td>  
<td>' . round($Lull_Hours,1) . '</td>  
<td>' . round($GA_Percent,1) . '</td> 
<td>' . round($MA_Percent,1) . '</td>   
</tr>  
';
			}
        endforeach;
		$output .= '<tr>  
<td>Total</td> 
<td>' . '' . '</td>   
<td>' . round($Sum_Import,1) . '</td>  
<td>' . round($Sum_Gen,1) . '</td>  
<td>' . '' . '</td>  
<td>' . round($Sum_Run,1) . '</td> 
<td>' . round($Sum_GD,1) . '</td>   
<td>' . round($Sum_BD,1) . '</td>  
<td>' . round($Sum_Lull,1) . '</td>  
<td>' . '' . '</td> 
<td>' . '' . '</td>   
</tr>  
';
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('DGR_Grouping_Report.pdf', 'I');
		exit;
    }

	 public function finyear_pdf() {
        //load pdf library
        $this->load->library('Pdf');
        $this->load->model('common/Common_model');
        $dname = urldecode($_REQUEST['dname']);
        $sdate = $_REQUEST['sdate'];
        $edate = $_REQUEST['edate'];
        $output = '';  
        $obj_pdf = new TCPDF('L', PDF_UNIT, array(400, 200), true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("Financial Year Report");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->AddPage();
        $content = '';
		$basic = $this->Common_model->getbasicInfoimei($dname);
        $top = "Financial Year Report - ".$basic[0]['Device_Name']." from ".$sdate." to ".$edate;
		$top2 = "Feeder - ".$basic[0]['Connect_Feeder']." , Loc - ".$basic[0]['Site_Location']." , ".$basic[0]['State'];
        $content .= '  
<h4 align="left"> ' . $top . '</h4><br /> 
<h4 align="left"> ' . $top2 . '</h4><br /> 
<table border="1" cellspacing="0" cellpadding="3">  
<tr>  
<th >Apr-' . $sdate . '</th>  
<th >May-' . $sdate . '</th>
<th >Jun-' . $sdate . '</th>  
<th >Jul-' . $sdate . '</th>
<th >Aug-' . $sdate . '</th>  
<th >Sep-' . $sdate . '</th>  
<th >Oct-' . $sdate . '</th>  
<th >Nov-' . $sdate . '</th>
<th >Dec-' . $sdate . '</th>  
<th >Jan-' . $edate . '</th>  
<th >Feb-' . $edate . '</th>  
<th >Mar-' . $edate . '</th>  
<th >Total</th>  
</tr>  
';
        $finyear_data = $this->Common_model->getFinyearReport($basic[0]['Format_Type'], $basic[0]['IMEI'], $sdate, $edate);
		$Sum_Gen = 0;
        foreach ($finyear_data as $key => $val):
					$gad_gen[$val['Month']] = $val['gad_gen'] > 0 && $val['gad_gen'] < 250000?$val['gad_gen']:'000';	
					$Sum_Gen += $gad_gen[$val['Month']];
		endforeach;
            $output .= '<tr>  
<td>' . $gad_gen["4"] . '</td>  
<td>' . $gad_gen["5"] . '</td> 
<td>' . $gad_gen["6"] . '</td>  
<td>' . $gad_gen["7"] . '</td>  
<td>' . $gad_gen["8"] . '</td>  
<td>' . $gad_gen["9"] . '</td> 
<td>' . $gad_gen["10"] . '</td>  
<td>' . $gad_gen["11"] . '</td>  
<td>' . $gad_gen["12"] . '</td>  
<td>' . $gad_gen["1"] . '</td> 
<td>' . $gad_gen["2"] . '</td>  
<td>' . $gad_gen["3"] . '</td>  
<td>' . $Sum_Gen . '</td>  
</tr>  
';
        
    //    print_r($output);die;
        $content .= $output;
        $content .= '</table>';
        $obj_pdf->writeHTML($content);
          ob_end_clean();
        $obj_pdf->Output('Financial_Year_Report.pdf', 'I');
		exit;
    }

}
