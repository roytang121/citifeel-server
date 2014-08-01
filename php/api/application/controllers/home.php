<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH. 'libraries/REST_Controller.php');

class Home extends REST_Controller {

	public function __construct() {
		parent::__construct();
		//$this->load->library('CORE_Controller');
		//$this->load->helper(array('form', 'url'));
		//$this->load->model("business_model");
		//$this->core_controller->set_response_helper($this);
	}

}