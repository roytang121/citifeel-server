<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @author Ben Leung
 */

require_once (APPPATH. 'libraries/REST_Controller.php');

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('CORE_Controller');
		$this->core_controller->set_response_helper($this);
	
	}
	
	/**
	*  INPUT: email, firstname, lastname, password
	*  DESC: Can be very complex
	*/
	public function newsfeed_post()
	{
		/*$current_user = $this->core_controller->get_current_user();
		
		$this->load->model('post_model');
		$this->load->model('user_model');
		$posts = $this->post_model->get_newsfeed_posts($current_user[$this->user_model->KEY_user_id]);
		$i = 0;
		foreach($posts as $post){
			$this->core_controller->add_return_data($i, $post);
			$i++;
		}*/
		$this->core_controller->successfully_processed();
	}

	
	/**
	*  INPUT: 
	*/
	public function create_post()
	{
		/*$this->load->library('form_validation');
		$validation_config = array(
			array('field' => 'oid', 'label' => 'order id', 'rules' => 'trim|required|xss_clean|min_length[1]|numeric'), 
			array('field' => 'actual_price', 'label' => 'actual price', 'rules' => 'trim|required|xss_clean|min_length[1]|numeric'),
			);

		$this->form_validation->set_error_delimiters('', '')->set_rules($validation_config);

		if ($this->form_validation->run() === FALSE) {
			$this->core_controller->fail_response(2, validation_errors());
		}*/
		$this->core_controller->successfully_processed();
	}
	
	/**
	*  INPUT: 
	*/
	public function hide_post()
	{

		$this->core_controller->successfully_processed();
	}
	
	/**
	*  INPUT: 
	*/
	public function detail_post()
	{

		$this->core_controller->successfully_processed();
	}
	
	/**
	*  INPUT: 
	*/
	public function edit_post()
	{

		$this->core_controller->successfully_processed();
	}
	
	/**
	*  INPUT: tag_id, user_id, business_user_id
    *  DESC: All inputs are optional, and-relationship, ignore if missing
	*/
	public function search_post()
	{
		// Validation
		$this->load->library('form_validation');
		$validation_config = array(
			array('field' => 'tag_id', 'label' => 'Tag id', 'rules' => 'trim|xss_clean'), 
			array('field' => 'user_id', 'label' => 'User id', 'rules' => 'trim|xss_clean')
		);
		$this->form_validation->set_error_delimiters('', '')->set_rules($validation_config);
		if ($this->form_validation->run() === FALSE) {
			$this->core_controller->fail_response(2, validation_errors());
		}
		
		
		
		$this->core_controller->successfully_processed();
	}
	
	
	
	/****************
	 * helper function 
	 *****************/
	 
	
	


	

}

/* End of file post.php */
/* Location: application\controllers/post.php */