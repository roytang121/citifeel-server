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
		date_default_timezone_set('Asia/Hong_Kong');
	}
	
	/**
	*  INPUT: timestamp, null value to retrieve the latest post
	*  DESC: Can be very complex
	*/
	public function newsfeed_get()
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

		$timestamp = $this->input->get("timestamp");
		if($timestamp) intval($timestamp);

		//load post
		$this->load->model('post_model');

		$posts = $this->post_model->getnewsfeed_posts($timestamp);

		$this->core_controller->add_return_data('posts', $posts);
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
			array('field' => 'company', 'label' => 'company', 'rules' => 'trim|xss_clean'),
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

		$config = array();
		$config['upload_path'] = $this->config->item('openshift_data_dir').'uploads/post_pic';
		$config['allowed_types'] = 'png|jpg|jpeg';
		$config['max_size']	= '16384';
		$config['encrypt_name'] = TRUE;
		$config['overwrite'] = FALSE;

		$this->load->library('upload', $config);
		
		//batch upload post pic
		$upload_success = TRUE;
		$files = $_FILES;
		$upload_data = array();
		$count = count($_FILES['post_pic']['name']);
		for($i = 0; $i < $count; $i++) {
	        $_FILES['post_pic']['name']= $files['post_pic']['name'][$i];
	        $_FILES['post_pic']['type']= $files['post_pic']['type'][$i];
	        $_FILES['post_pic']['tmp_name']= $files['post_pic']['tmp_name'][$i];
	        $_FILES['post_pic']['error']= $files['post_pic']['error'][$i];
	        $_FILES['post_pic']['size']= $files['post_pic']['size'][$i]; 
	        if($upload_success = $upload_success && $this->upload->do_upload('post_pic')) {
	        	$upload_data[] = $this->upload->data();
	        	$upload_success = $upload_success && $upload_data[count($upload_data) - 1]["is_image"] == "1";
	        }
	        if(!$upload_success) break;
		}

		if($upload_success === FALSE) {
			//remove all the upload post pic if upload fail
			foreach($upload_data as $data)
				if(file_exists($this->config->item('openshift_data_dir').'uploads/post_pic/' . $data['file_name']))
					unlink($this->config->item('openshift_data_dir').'uploads/post_pic/' . $data['file_name']);
			$this->core_controller->fail_response(2, "Upload Images Failed!");
		}


		// Create Post
		$this->load->model('post_model');

		$result = $this->post_model->create_post(
			$user_id,
			$this->input->post("caption"),
			$this->input->post("company"),
			$this->input->post("company_id"),
			$this->input->post("rating"),
			$this->input->post("price"),
			$this->input->post("url"),
			$this->input->post("region"),
			$upload_data
		);

		if($result === FALSE) {
			//database insertion fail, delete photo
			foreach($upload_data as $data)
				if(file_exists($this->config->item('openshift_data_dir').'uploads/post_pic/' . $data['file_name']))
					unlink($this->config->item('openshift_data_dir').'uploads/post_pic/' . $data['file_name']);
			$this->core_controller->fail_response(2, "Database Insertion Failed!");
		}
		/*
		$data = array(
			$this->post_model->KEY_caption => $this->input->post('caption'),
			$this->post_model->KEY_user_id => $user_id,
			$this->post_model->KEY_post_time => date('Y-m-d G:i:s')
        );
		
		
		$form_inputs = array('company_id');	//,'url','region','price','rating'
		
		foreach($form_inputs as $form_input){
			if( $this->input->post($form_input) )
				$data[$this->post_model->{"KEY_" . $form_input}] = $this->input->post($form_input);
		}
		
		$post_id = $this->post_model->create_post($data);
		*/

		// return post information
		/*foreach ($data as $key => $value) {
			$this->core_controller->add_return_data($key, $value);
		}
		$this->core_controller->add_return_data("user_id", $current_user[$this->user_model->KEY_user_id]);*/
		//$this->core_controller->add_return_data("post_id", $post_id);
		
		if($result)
			$this->core_controller->successfully_processed();
		else
			$this->core_controller->fail_response(2);
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
	public function search_get()
	{
		// Validation
		$this->load->library('form_validation');
		$validation_config = array(
			array('field' => 'tag[]', 'label' => 'Tag id', 'rules' => 'trim|xss_clean'), 
			//array('field' => 'user_id', 'label' => 'User id', 'rules' => 'trim|xss_clean')
		);
		$this->form_validation->set_error_delimiters('', '')->set_rules($validation_config);
		if ($this->form_validation->run() === FALSE) {
			$this->core_controller->fail_response(2, validation_errors());
		}
		
		$this->load->model('post_model');
		$posts = $this->post_model->search($this->input->get('tag'));

		$this->core_controller->add_return_data('posts', $posts);
		$this->core_controller->successfully_processed();
	}
	
	public function tags() {
		$tags = $this->tag_model->get_all_tag();

		$this->core_controller->add_return_data('tags', $tags);
		$this->core_controller->successfully_processed();		
	}
	
	
	/****************
	 * helper function 
	 *****************/
	  
	
	


	

}

/* End of file post.php */
/* Location: application\controllers/post.php */