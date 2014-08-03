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
	var $KEY_user_name = 'username';
	var $KEY_profile_pic = 'profile_pic';
	var $KEY_status = 'status';
	var $KEY_password = 'password';

	public function add_user($data) {
		$this->db->insert($this->Table_name_user, $data);	//(DEBUG: how it works)
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return -1;
		}
	}

	public function update_user($user_id,$data) {
		$this->db->where($this->KEY_user_id,$user_id);
		$this->db->update($this->Table_name_user, $data);	//(DEBUG: how it works)
		if ($this->db->affected_rows() > 0) {
			return $user_id;
		} else {
			return -1;
		}
	}
	
	public function check_if_user_exists($key, $value) {
		$number_of_result = $this->db->from($this->Table_name_user)
							->where($key, $value)
							->count_all_results();
		if ($number_of_result > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function get_user_by_email($email) {
		return $this->get_user_by_key($this->KEY_email, $email);
	}

	public function get_user_by_id($id) {
		return $this->get_user_by_key($this->KEY_user_id, $id);
	}

	public function get_user_by_username($username) {
		return $this->get_user_by_key($this->KEY_user_name, $username);
	}

	public function password_recovery($email) {
		$hash = hash('sha512', $this->config->item('encryption_key') . $email . time());
		$sql = "UPDATE user SET recovery_hash = ?, recovery_expire = NOW() + 86400 WHERE email = ?";
		$this->db->query($sql, array($hash, $email));

		return true;

		//send email
		require_once("application/third_party/PHPMailer/class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = '';

		$mail->SMTPAuth = true; 
		$mail->Username = '';
		$mail->Password = '';
		$mail->SMTPSecure = 'tls'; 
		$mail->CharSet = 'UTF-8'; 

		$mail->FromName = '';
		$mail->From = '';
		$mail->addReplyTo('', '');

		$mail->WordWrap = 50;
		$mail->isHTML(true);

		$mail->addAddress('', '');

		$mail->Subject = '';
		$mail->Body = '';

		//if($mail->send()) {
		//}
	}

	public function validate_recovery($email, $hash) {
		$sql = "SELECT 1 FROM user WHERE email = ? AND recovery_hash = ? AND recovery_expire > NOW()";
		$query = $this->db->query($sql, array($email, $hash));

		$validate = $query->num_rows() == 1;

		if($validate) {
			$sql = "UPDATE user SET recovery_hash = NULL, recovery_expire = NULL WHERE email = ?";
			$query = $this->db->query($sql, array($email));
		}
		
		return $validate;
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
	
	
}

/* End of file test_model.php */
/* Location: ./application/models/test_model.php */