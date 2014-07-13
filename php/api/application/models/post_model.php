<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * User Model
 * @author benleung
 */

class Post_model extends CI_Model {
	var $Table_name = 'post';
	
	var $KEY_post_id = 'post_id';
	var $KEY_user_id = 'user_id';
	var $KEY_caption = 'caption';
	var $KEY_company_id = 'company_id';
	var $KEY_rating = 'rating';
	var $KEY_post_time = 'post_time';
	var $KEY_price = 'price';
	var $KEY_url = 'url';
	var $KEY_region = 'region';

	public function create_post($data){
		$this->db->insert($this->Table_name, $data);	//(DEBUG: how it works)
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return -1;
		}
	}
	
}

/* End of file post_model.php */
/* Location: ./application/models/post_model.php */