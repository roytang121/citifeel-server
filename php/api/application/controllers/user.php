<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * User
 */

require_once (APPPATH. 'libraries/REST_Controller.php');

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('CORE_Controller');
		$this->load->helper(array('form', 'url'));
		$this->core_controller->set_response_helper($this);
	
	}

	var $user_type = '';

	public function index()
	{
		
	}

	public function login_post()
	{
        $this->load->model('user_model');
		
		//$user_data = $this->user_model->get_user_by_email($this->input->post('email'));
		
        $user_array=$this->user_model->get_all_user();
		
		//$email = $this->input->post('email');
        //$password = $this->input->post('password');
		//function to $user_array=...
		
        $this->core_controller->add_return_data('user_login_data',$user_array)->successfully_processed();
		
	}

	/* helper function */

	/*private function get_valid_session_token_for_user($did) {
		$this->load->model('session_model');
		$result = $this->session_model->session_token_based_on_id($did, $this->user_type);
        if (!is_null($result) && is_array($result) && count($result) > 0) {
            // has session token, check
            
       		if (!$result['expired']) {
		    	return $this->session_model->get_session_by_id($did, $this->user_type);
		    }
        }
        $this->session_model->generate_new_session_token($did, $this->user_type);
        return $this->session_model->get_session_by_id($did, $this->user_type);
	}

	private function hide_user_data($driver_data_array) {
		$this->load->model('user_model');
		if (array_key_exists($this->driver_model->KEY_password, $driver_data_array)) {
			unset($driver_data_array[$this->driver_model->KEY_password]);
		}

		return $driver_data_array;
	}*/

	

}

/* End of file test.php */
/* Location: ./application/controllers/test.php */