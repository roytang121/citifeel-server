<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * User Model
 * @author benleung
 */

class User_model extends CI_Model {
	var $Table_name_user = 'user';
	
	var $KEY_collection_privacy = 'collection_privacy';
	var $KEY_user_id = 'user_id';
	var $KEY_user_info = 'user_info';
	var $KEY_email = 'email';
	var $KEY_first_name = 'firstname';
	var $KEY_last_name = 'lastname';
	var $KEY_profile_pic = 'profile_pic';
	var $KEY_status = 'status';
	var $KEY_password = 'password';

	function add_user($data) {
		$this->db->insert($this->Table_name_user, $data);	//(DEBUG: how it works)
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return -1;
		}
	}
	
	function is_user_exists_by_email($email) {
		return $this->is_user_exists($this->KEY_email, $email);
	}
	
	
	private function check_if_user_exists($key, $value) {
		$number_of_result = $this->db->from($this->Table_name_user)
							->where($key, $value)
							->count_all_results();
		if ($number_of_result > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function get_user_by_email($email) {
		return $this->get_user_by_key($this->KEY_email, $email);
	}
	
	// helper
	// (TODO: hide password) 
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
	
	private function is_user_exists($key, $value) {
		$number_of_result = $this->db->from($this->Table_name_user)
							->where($key, $value)
							->count_all_results();
		if ($number_of_result > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

/* End of file test_model.php */
/* Location: ./application/models/test_model.php */