<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @author Ben Leung
 */

require_once (APPPATH. 'libraries/REST_Controller.php');

class Post extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('CORE_Controller');
		$this->load->helper(array('form', 'url'));
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
	*  INPUT: caption, company_id, rating, price, url (optional), (optional), region (optional)
	*/
	public function create_post()
	{
		$this->load->model('user_model');
		$current_user = $this->core_controller->get_current_user();
		$user_id = $current_user[$this->user_model->KEY_user_id];
	
		// Validation
		$this->load->library('form_validation');
		$validation_config = array(
			array('field' => 'caption', 'label' => 'caption', 'rules' => 'trim|xss_clean'),
			array('field' => 'company_id', 'label' => 'company id', 'rules' => 'trim|xss_clean'),
			array('field' => 'rating', 'label' => 'rating', 'rules' => 'trim|xss_clean|numeric'),
			array('field' => 'price', 'label' => 'Price', 'rules' => 'trim|xss_clean'),
			array('field' => 'url', 'label' => 'URL', 'rules' => 'trim|xss_clean'),
			array('field' => 'region', 'label' => 'Region', 'rules' => 'trim|xss_clean'),
			);
		$this->form_validation->set_error_delimiters('', '')->set_rules($validation_config);
		if ($this->form_validation->run() === FALSE) {
			$this->core_controller->fail_response(2, validation_errors());
		}
		
		// Create Post
		$this->load->model('post_model');
		$data = array(
			$this->post_model->KEY_caption => $this->input->post('caption'),
			$this->post_model->KEY_user_id => $user_id,
			$this->post_model->KEY_post_time => date('Y-m-d G:i:s')
        );
		
		$form_inputs = array('company_id','url','region','price','rating');
		
		foreach($form_inputs as $form_input){
			if(!is_null($this->input->post($form_input)))
				$data[$this->post_model->{"KEY_" . $form_input}] = $this->input->post($form_input);
		}
		
		$post_id = $this->post_model->create_post($data);
		
		// return post information
		/*foreach ($data as $key => $value) {
			$this->core_controller->add_return_data($key, $value);
		}
		$this->core_controller->add_return_data("user_id", $current_user[$this->user_model->KEY_user_id]);*/
		$this->core_controller->add_return_data("post_id", $post_id);
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