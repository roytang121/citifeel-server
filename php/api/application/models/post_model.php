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

	/*public function create_post($data){
		$this->db->insert($this->Table_name, $data);	//(DEBUG: how it works)
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return -1;
		}
	}*/

	public function create_post($user_id, $caption, $company_id, $rating, $price, $url, $region, $upload_data) {
		$this->db->trans_start();

		$sql = "INSERT INTO post(user_id, caption, rating, post_time, url, company_id, price, region) VALUES(?, ?, ?, NOW(), ?, ?, ?, ?)";
		$query = $this->db->query($sql, array($user_id, $caption, $rating, $url, $company_id, $price, $region));
		if(!$query || !$this->db->affected_rows()) {
			return false;
		}

		$insert_id = $this->db->insert_id();


		foreach($upload_data as $data) {
			$sql = "INSERT INTO photo(name, path) VALUES(?, ?)";
			$query = $this->db->query($sql, array($data["orig_name"], $data["file_name"]));
			if(!$query || !$this->db->affected_rows()) {
				return false;
			}

			$photo_insert_id = $this->db->insert_id();
			$sql = "INSERT INTO post_photo(post_id, photo_id) VALUES(?, ?)";
			$query = $this->db->query($sql, array($insert_id, $photo_insert_id));
			if(!$query || !$this->db->affected_rows()) {
				return false;
			}
		}

		$this->db->trans_complete();

		return true;
	}

	public function getnewsfeed_posts($timestamp) {
		$sql = "SELECT * FROM post";
		if($timestamp) $sql .= " WHERE post_time < ?";
		$sql .= "ORDER BY post_time DESC LIMIT 20";

		if($timestamp) $query = $this->db->query($sql, $timestamp);
		else $query = $this->db->query($sql);

		return $query->result_array();
	}
	
}

/* End of file post_model.php */
/* Location: ./application/models/post_model.php */