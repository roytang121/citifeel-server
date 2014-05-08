<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * User Model
 * @author benleung
 */

class User_model extends CI_Model {
	var $Table_name_user = 'user';
	
	var $KEY_collection_privacy = 'collection_privacy';
	var $KEY_user_info = 'user_info';
	var $KEY_email = 'email';
	var $KEY_first_name = 'firstname';
	var $KEY_last_name = 'lastname';
	var $KEY_profile_pic = 'profile_pic';
	var $KEY_fb_id = 'fb_id';
	var $KEY_status = 'status';
	var $KEY_password = 'password';
	
	// (TOFIX) for testing, remove later
	function get_all_user() {
	    $result= $this->db->get($this->Table_name_user);
	    //echo 'db conn';
	    return $result->result_array();
	}
	
	function get_user_by_email($email) {
		return $this->get_user_by_key($this->KEY_email, $email);
	}
	
	// helper
	private function get_user_by_key($key, $value) {
		$result = $this->db->from($this->Table_name_user)
							->where($key, $value)
							->get();

		if ($result->num_rows() > 0) {
			return $result->row_array(1);
		} else {
			return array();
		}

	}

}

/* End of file test_model.php */
/* Location: ./application/models/test_model.php */