<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Test
 */

require_once (APPPATH. 'libraries/REST_Controller.php');

class Test extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('CORE_Controller');
		$this->load->helper(array('form', 'url'));
		$this->core_controller->set_response_helper($this);
	
	}

	/**
	*  for testing only
	*/
	public function testDBConn_get()
	{

		

        $this->load->model('test_model');

        $user_array=$this->test_model->get_all_user();
        $user_array=$this->hide_user_data($user_array);
        
        $this->core_controller->add_return_data('number of users',count($user_array))->successfully_processed();		
		
	}

	

}

/* End of file test.php */
/* Location: ./application/controllers/test.php */