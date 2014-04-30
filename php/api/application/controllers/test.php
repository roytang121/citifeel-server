<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Test
 */

require_once (APPPATH. 'libraries/REST_Controller.php');

class Test extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('CORE_Controller');
	
	}

	var $user_type = '';

	public function index()
	{
		
	}

	/**
	*  
	*/
	public function testDBConn_get()
	{

		

        $this->load->model('test_model');

        $user_array=$this->test_model->get_all_user();

        
        $this->core_controller->add_return_data('test_data',$user_array )->successfully_processed();		
		
	}

	

	

}

/* End of file test.php */
/* Location: ./application/controllers/test.php */