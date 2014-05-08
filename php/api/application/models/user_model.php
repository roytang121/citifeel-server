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
	
	// http://www.citifeel.com/api/user/login
	function get_all_user() {
	    $result= $this->db->get($this->Table_name_user);
	    //echo 'db conn';
	    return $result->result_array();
	}
	
	function get_user($email,$password) {
	    $result= $this->db->get($this->Table_name_user);
	    //echo 'db conn';
	    return $result->result_array();
	}

}

/* End of file test_model.php */
/* Location: ./application/models/test_model.php */