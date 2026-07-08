<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
                                          
class Login extends CI_Controller {
 
	public $sessionUsername;
	function __construct(){	
		parent::__construct();
		$this->load->helper(array('url', 'language'));	
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		//$this->db1 = $this->load->database('db1', TRUE);
		$this->sessionUsername = $this->session->userdata('username');
		/*if(!empty($this->sessionUsername)){
			redirect('dashboard');
		}*/
	}
 
	function index() 
	{
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->load->view('login/index', $this->data);
	}
	
	public function loginprocess()
	{
		$this->load->model("login/Login_model");
		$message	='';
		if( $this->input->post() ) {
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if ($this->form_validation->run() == TRUE)
			{
				$formvalues	=	$this->input->post();
				$userdata	=	$this->Login_model->loginValidation( $formvalues );

				if ( !empty( $userdata ) )	{
					/** check email count is one then redirect to crossponding user domain*/
					if( count($userdata) == 1 ) {/** User has one role */
						/** Get user name  */
						$userName	= ucfirst($userdata['0']['username']);
						
						/** Set session value  */ 
						$data['username']	= $userName;
						$data['parent_id']	= $userdata['0']['Parent_ID'];
						$data['db_name']	= $userdata['0']['Db_Name'];
						$data['user_type_id']	= $userdata['0']['User_Type_ID'];
						$data['account_id']	= $userdata['0']['Account_ID'];
 
						$this->session->set_userdata($data);
						redirect('dashboard');
					}
				}else{
					$message	=	'Username or Password you entered is incorrect';
				}
			}else{
				$message	=	validation_errors();
			}
		}
		
		$this->session->set_flashdata('message', $message); 	        
		redirect();
	}
 
}


 
?>
