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
		$CI = & get_instance();
		$CI->config->load("facebook",TRUE);
		$config = $CI->config->item('facebook');
		$this->load->library('Facebook', $config);
	
	}

	var $user_type = '';

	public function index()
	{
		
	}

	public function login_post()
	{
		// (TODO) Validation
	
        $this->load->model('user_model');
		
		$user_data = $this->user_model->get_user_by_email($this->input->post('email'));
		if (count($user_data) == 0) {
			// email does not exist
			$this->core_controller->fail_response(3);
		}
		if ($user_data[$this->user_model->KEY_password] != $this->input->post('password')) {
			// wrong password
			$this->core_controller->fail_response(4);
		}
		
		$new_session_token = $this->get_valid_session_token_for_user($user_data[$this->user_model->KEY_user_id]);
		
		//$email = $this->input->post('email');
        //$password = $this->input->post('password');
		//function to $user_array=...
		
        $this->core_controller->add_return_data('user_login_data',$user_data);
		$this->core_controller->add_return_data('session_token', $new_session_token['session_token']); 
		$this->core_controller->successfully_processed();
		//(TODO:session token here^^)
	}

	public function loginfb_post()
	{
	
        // Try to get the user's id on Facebook
        $accessToken_fb=$this->input->post('access_token');
        $this->facebook->setAccessToken($accessToken_fb);
        $userId = $this->facebook->getUser();
 
        // If user is not yet authenticated, the id will be zero
        if($userId == 0){
            // Generate a login url
             $data['url'] = $this->facebook->getLoginUrl(array('scope'=>'email'));
             $this->core_controller->add_return_data('login_url', $data['url']); 
             $this->core_controller->fail_response(5);
  
        } else {
            // Get user's data and print it
            $user = $this->facebook->api('/me');
            $profile_pic= $this->facebook->api('/me/picture');
            $this->core_controller->add_return_data('user_fb_data', $user); 
            $this->core_controller->successfully_processed();
        }
		
		
	}

	/* helper function */
	private function get_valid_session_token_for_user($id) {
		$this->load->model('session_model');
		$result = $this->session_model->session_token_based_on_id($id, $this->user_type);
        if (!is_null($result) && is_array($result) && count($result) > 0) {
            // has session token, check
            
       		if (!$result['expired']) {
		    	return $this->session_model->get_session_by_id($id, $this->user_type);
		    }
        }
        $this->session_model->generate_new_session_token($id, $this->user_type);
        return $this->session_model->get_session_by_id($id, $this->user_type);
	}
	/*
	
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