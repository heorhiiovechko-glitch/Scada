<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
                                          
class Logout extends CI_Controller {
 
	public $sessionUsername;
	function __construct(){	
		parent::__construct();
		$this->load->helper(array('url', 'language'));	
		$this->load->library('session');
		$this->sessionUsername = $this->session->userdata('username');
		if(empty($this->sessionUsername)){
			redirect('');
		}
	}
 
	function index() {
		$this->session->sess_destroy();
		redirect('');
	}
 
}
 
?>