<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
                                          
class Parkview extends CI_Controller {
 
	public $sessionUsername;
	public $sessionDbname;
	function __construct(){	
		parent::__construct();
		$this->load->helper(array('url', 'language'));	
		$this->load->helper('form');
		$this->load->library('session');
		$this->sessionUsername = $this->session->userdata('username');
		$this->sessionDbname = $this->session->userdata('db_name');
		if(empty($this->sessionUsername)){
			redirect('');
		}
		$this->load->view('layout/header');
		$this->db2 = $this->load->database($this->Common_model->set_db_config(), TRUE);
	}
 
	function index() {
		$this->load->view('parkview/index');
	}
}
?>
