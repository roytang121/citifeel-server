<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH. 'libraries/REST_Controller.php');

class Business extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('CORE_Controller');
		$this->load->helper(array('form', 'url'));
		$this->load->model("business_model");
		$this->core_controller->set_response_helper($this);
	}

	public function test_get() {
		$result = $this->business_model->company_ratings(10);
		$this->core_controller->add_return_data("result", $result);
		$this->core_controller->successfully_processed();
	}

}