<?php

class Model_validation extends CI_Model
{
	
	public function validate_key($key) {
		$sql = "SELECT * FROM `sj_temp_users` WHERE `key` = '" . $key . "' AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			return true;
		} else {
			$this->session->set_flashdata('update_error', 'Invalid key. Please try again later or contact administrator.');
			redirect(base_url().'site/login');
		}
	}

	public function add_student($key, $tutorId, $school, $level, $pemail) {
		$sql = "SELECT * FROM `sj_temp_users` WHERE `key` = '" . $key . "' AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		$query_row = $query->row();
		
		$school = base64_decode(urldecode($school));
		$level = base64_decode(urldecode($level));
		
		$sql_user = "INSERT INTO `sj_users` (`email`, `fullname`, `password`, `username`, `contact_no`, `profile_pic`, `branch_code`, `status`, `last_login`) VALUES ('".$query_row->email."','".$query_row->fullname."','". $query_row->password ."','".$query_row->username."','".$query_row->contact_no."', 'student_placeholder.jpg','".BRANCH_ID."', '1', 'student')";
		$query_user = $this->db->query($sql_user);
		$userId = $this->db->insert_id();
		
		$sql_student = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES ('".$userId."','".$school."','".$level."')";
		$query_student = $this->db->query($sql_student);

		$sql = "SELECT * FROM `sj_users` WHERE `branch_code` = ".BRANCH_ID." AND `email` = " . $this->db->escape($pemail);
        $par_query = $this->db->query($sql);

		if($par_query){
            $parent = $par_query->row();

            $sql_add_parent = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES ('".$userId."','".$parent->id."',2, '".BRANCH_TAG."')";
			$query_add_parent = $this->db->query($sql_add_parent);
			
			if($tutorId) {
				$sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES ('".$userId."','".$tutorId."',1, '".BRANCH_TAG."')";
				$query_relationship = $this->db->query($sql_relationship);
			}
        } else {
			$sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES ('".$userId."','".$tutorId."',1, '".BRANCH_TAG."')";
			$query_relationship = $this->db->query($sql_relationship);
		}
		
		$sql_role = "INSERT INTO `sj_user_role` (`user_id`, `role_id`, `branch_code`) VALUES ('".$userId."',2,".BRANCH_ID.")";
		$query_role = $this->db->query($sql_role);
		
		$sql_user_branch = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES ('".$userId."', 1), ('".$userId."', ".BRANCH_ID.")";
		$query_user_branch = $this->db->query($sql_user_branch);
		
		$sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES ('" . $userId . "', '" . $query_row->email . "','student', '1')";
		$query_branch_user = $this->db->query($sql_branch_user);
		
		$sql_delete = "DELETE FROM `sj_temp_users` WHERE `key` = '" . $key . "'";
		$query_delete = $this->db->query($sql_delete);
		
		if($query_delete){
			return ($query_row->email == " ")? $query_row->username:$query_row->email;
		} else {
			$this->session->set_flashdata('update_error', 'Student account failed to activate. Please try again later or contact administrator.');
			redirect(base_url().'site/login');
		}
		
	}

	public function login($email) {
		$sql_id = "SELECT `id` FROM `sj_users` WHERE (`email` = '" . $email . "' OR `username` = '" . $email . "') AND `branch_code` = ".BRANCH_ID." ";
		$query_id = $this->db->query($sql_id);
		if($query_id->num_rows() == 1) {
			$userId = $query_id->row()->id;
		}
		
		$sql_role = "SELECT `role_id` FROM `sj_user_role` WHERE `user_id` = '" . $userId . "' AND `branch_code` = ".BRANCH_ID." ";
		$query_role = $this->db->query($sql_role);
		if($query_role->num_rows() == 1) {
			$userRole = $query_role->row()->role_id;
		}
		
		$data = array(
			'is_logged_in' => 1,
			'user_id' => $userId,
			'user_role' => $userRole,
			'branch_code' => BRANCH_ID
		);
		$this->session->set_userdata($data);
		
		if($this->session->userdata('is_logged_in') == 1) {
			$sessionArray = array(
				'profileMessageSuccess' => true,
				'profileMessage' => 'Your account has been activated!'
			);
			$this->session->set_userdata($sessionArray);
			redirect(base_url().'profile');
		} else {
			$this->session->set_flashdata('update_error', 'Student account has activated but failed to auto-login. Please try again later or contact administrator.');
			redirect(base_url().'site/login');
		}
	}

}
?>