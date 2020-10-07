<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

class Model_users extends CI_Model{

    public function __construct(){
        parent::__construct();
        $this->load->model('model_worksheet');
        $this->load->model('model_quiz');
        $this->load->model('model_question');
    }

    public function get_user_info($user_id){
        $this->db->where('id', $user_id);
        $query = $this->db->get('sj_users');

        return $query->row();
    }

    public function get_student_info($id) {
        $sql = "SELECT * FROM `sj_student` WHERE `student_id` =" . $this->db->escape($id);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

    private function get_user_profile_pic($user_id) {
        $sql = "SELECT * FROM `sj_users` WHERE `id` =" . $this->db->escape($user_id);

        $query = $this->db->query($sql); 

        if($query->num_rows() > 0) {
            $result = $query->row();

            $profile = $result->profile_pic;

            return $profile;
        }
    }

    // ANOM START

    public function getTutorStatusTag($user_id){
        $sql = "SELECT DISTINCT(a.subject_id), a.level_id, b.* FROM sj_class a 
                JOIN sj_class_relation b ON a.class_id=b.class_id 
                WHERE b.user_id='$user_id' AND b.type='tutor' AND b.status=1  
        ";

        $query = $this->db->query($sql);
        $subject_id = array();
        foreach($query->result() as $row){
            $subject_id[] = $row->subject_id;
        }

        return $subject_id;
    }

    // ANOM END


    public function get_group($user_id)
    {
        $sql = "SELECT * FROM `sj_student_group` WHERE `created_by` ='". $user_id . "'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_class($user_id)
    {
        $sql = "
            SELECT `sj_class`.`class_id` ,`sj_class`.`class_name` FROM `sj_class_relation`
            JOIN `sj_class` ON `sj_class_relation`.`class_id` = `sj_class`.`class_id`
            WHERE `sj_class_relation`.`user_id` = ". $this->db->escape($user_id) . " 
            AND `sj_class_relation`.`type` = 'tutor' and `sj_class`.`branch`='".BRANCH_ID."'
        ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_student_group($user_id,$student_id)
    {
        $sql = "SELECT * FROM `sj_student_group_relation` WHERE `adult_id` ='". $user_id . "' and student_id='". $student_id . "' ";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $result = $query->row();
            $this->db->where('id', $result->group_id);
            $qry = $this->db->get('sj_student_group');
            if($qry->num_rows() > 0) {
                $rs = $qry->row();
                $data['group_id'] = $rs->id;
                $data['group_name'] = $rs->group_name;
                $data['color'] = $rs->color;
                $data['text_color'] = $rs->text_color;
                return $data;
            }
        } 
        $data['group_id'] = '0';
        $data['group_name'] = 'Select group';
        $data['color'] = "#DFF0D8";
        $data['text_color'] = "#333333";
        return $data;
    }

    function get_student_assign_group($user_id,$group_id,$type="sum") {
        $sql = "SELECT * FROM `sj_student_group_relation` WHERE `adult_id` ='". $user_id . "' and group_id='". $group_id . "' ";
        $query = $this->db->query($sql);
        if($type=="sum") { 
            return $query->num_rows();
        } else {
            return $query->result_array();
        }
    }

    public function validate_group($group_name="")
    {   
        $userId = $this->session->userdata('user_id');
        $sql = "SELECT * FROM `sj_student_group` WHERE `group_name` = ? and created_by = ? ";
        $query = $this->db->query($sql, array($group_name, $userId));
        if ($query->num_rows() == 0 && $group_name!="") {
            return true;
        } else {
            return false;
        }
    }

    public function check_group_exist($groupName,$userID)
    {  
        $sql = "SELECT * FROM `sj_student_group` WHERE `group_name` = '{$groupName}' AND created_by='{$userID}' ";
        $query = $this->db->query($sql);

        return ($query->num_rows() == 0) ? false : true;
    }

    public function get_assigned_user_info($user_id)
    {
        $sql = "SELECT `email`, `fullname` FROM `sj_users` WHERE `id` ='". $user_id . "'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function update_user_info($user_id)
    {
        $user_role = $this->session->userdata('user_role');
        $mobileReg = "/^$|[8|9]\d{3}\s\d{4}/";
        $mobileRegs = "/^$|[8|9]\d{7}/";
        if ($user_role == 1){
	        $data_ar = array(
				'fullname' => $this->input->post('profile_fullName'),
				'contact_no' => $this->input->post('profile_mobile'),
				'agency_link' => $this->input->post('profile_agency_link'),
				'profession' => $this->input->post('profile_profession')
			);
			if(empty($data_ar['agency_link'])){
				$data_ar['agency_link'] = NULL;
			}
			
			if(empty($data_ar['profession'])){
				$data_ar['profession'] = NULL;
			}
			
        } elseif($user_role == 2) {
			$data_ar = array(
				'fullname' => $this->input->post('profile_fullName'),
				'email' => $this->input->post('profile_email'),
				'contact_no' => $this->input->post('profile_mobile')
			);
			
			$sql = "SELECT `email` FROM `sj_users` WHERE id ='".$user_id."'";
			$query = $this->db->query($sql);
			$emailExist = $query->row()->email;
			
			$sql = "SELECT * FROM `sj_users` WHERE `email` = '" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID." AND `email` NOT IN ('". $emailExist ."')";
			$result = $this->db->query($sql);
			if($result->num_rows() > 0){
				$this->session->set_flashdata('update_error', 'Email has been taken by another user. Please use another email address.');
				redirect(base_url() . 'profile/edit');
			}
        } else {
            $data_ar = array(
				'fullname' => $this->input->post('profile_fullName'),
				'contact_no' => $this->input->post('profile_mobile'),
				'agency_link' => $this->input->post('profile_agency_link'),
				'profession' => $this->input->post('profile_profession')
			);
			if(empty($data_ar['agency_link'])){
				$data_ar['agency_link'] = NULL;
			}
			
			if(empty($data_ar['profession'])){
				$data_ar['profession'] = NULL;
			}
        }
        
        if(!preg_match($mobileReg, $data_ar['contact_no']) && !preg_match($mobileRegs, $data_ar['contact_no'])) {
			$this->session->set_flashdata('update_error', 'Please use Singapore registered mobile number starting with 8 or 9.');
			redirect(base_url() . 'profile/edit');
		}
		
        $this->db->where('id', $user_id);
        $query = $this->db->update('sj_users', $data_ar);

        if($user_role == 2) {
            $sql = "UPDATE `sj_student` SET `school_id` = " . $this->input->post('student_school') . ", `level_id` = " . $this->input->post('student_level') . " WHERE `student_id` = " . $user_id;
            $query = $this->db->query($sql);
        }  

        // delete from sj_user_specialization_mapping
        $delete_sql = "DELETE FROM `sj_user_specialization_mapping` WHERE `tutor_id` = ?";
        $delete_query = $this->db->query($delete_sql, array($user_id));

        $insert_query = false;
        if ($delete_query) {
            $specialization = $this->input->post('profile_specialization[]');
            $insert_ar = array();
            if (isset($specialization) && empty($specialization) === false) {
                foreach ($specialization as $specialize) {
                    $insert_ar[] = array(
                        'tutor_id' => $user_id,
                        'subject_id' => $specialize
                    );
                }
                $insert_query = $this->db->insert_batch('sj_user_specialization_mapping', $insert_ar);
            } else {
                $insert_query = true;
            }
        }

        return $query && $delete_query && $insert_query ? true : false;
    }

    public function update_password($user_id)
    {
        $data_ar = array(
            'password' => hash('sha256', $this->input->post('profile_password'))
        );

        $this->db->where('id', $user_id);
        $query = $this->db->update('sj_users', $data_ar);

        return $query ? true : false;
    }

    public function set_password($user_id, $new_password)
    {
        $data_ar = array(
            'password' => hash('sha256', $new_password)
        );

        $this->db->where('id', $user_id);
        $query = $this->db->update('sj_users', $data_ar);

        return $query ? true : false;
    }

    public function update_profile_pic($user_id, $image_path)
    {
        $data_ar = array(
            'profile_pic' => $image_path
        );

        $this->db->where('id', $user_id);
        $query = $this->db->update('sj_users', $data_ar);

        return $query ? true : false;
    }


    public function is_member($fb_user)
    {
        $this->db->where('email', $fb_user['email']);
        $query = $this->db->get('sj_users');
        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function sign_up_from_facebook($fb_user)
    {
        $query = false;
        $query2 = false;
        $add_role_query = false;
        $data_ar1 = array(
            'email' => $fb_user['email'],
            'fullname' => $fb_user['name'],
            'password' => hash('sha256', sha1(uniqid('smartjen'))),
            'username' => 'fb' . $fb_user['id']
        );

        $query = $this->db->insert('sj_users', $data_ar1);
        if ($query) {
            $user_id = $this->db->insert_id();
            $data_ar2 = array(
                'user_id' => $user_id,
                'facebook_id' => $fb_user['id']
            );


            $query2 = $this->db->insert('sj_fb_users', $data_ar2);

            $user_role_insert_ar = array(
                'user_id' => $user_id,
                'role_id' => 1
            );

            $add_role_query = $this->db->insert('sj_user_role', $user_role_insert_ar);
        }

        return $query && $query2 && $add_role_query;
    }

    public function can_login()
    {
        // $this->db->where('email', $this->input->post('email'));
        // $this->db->where('password', sha1($this->input->post('password')));
        // $query = $this->db->get('sj_users');

        $emailOrUsername = $this->input->post('login_email');
        $password = hash('sha256', $this->input->post('login_password'));
        
        $sql = "SELECT * FROM `sj_users` WHERE (`email` = '".$emailOrUsername."' OR `username` = '".$emailOrUsername."' ) AND `password` = '".$password."' ";
        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function validate_username()
    {
        $sql = "SELECT * FROM `sj_users` WHERE `username` = ?";
        $query = $this->db->query($sql, array($this->input->post('register_username')));
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function validate_email()
    {
        $email = $this->input->post('register_email');
        if($email = ' '){
	        $email = 'NULL';
        }
	    $sql = "SELECT * FROM `sj_users` WHERE `email` = ? AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql, array($email));
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function validate_usernames($username)
    {
        $sql = "SELECT * FROM `sj_users` WHERE `username` = ? AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql, array($username));
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function validate_emails($email)
    {
        if($email = ' '){
	        return true;
        }
	    
	    $sql = "SELECT * FROM `sj_users` WHERE `email` = '" . $email . "' AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function login($emailOrUsername)
    {
        // hardwiring the username for admin accounts
        switch ($emailOrUsername) {
            case 'admin_ryantay':
                $emailOrUsername = 'tayryan2005@gmail.com';
                break;
            case 'admin_xunuohan':
                $emailOrUsername = 'lesliexunh@gmail.com';
                break;
            case 'admin_gabriellelee':
                $emailOrUsername = 'lyahkwan@yahoo.com';
                break;
            case 'admin_xingwu':
                $emailOrUsername = 'pxingwu@yahoo.com';
                break;
            case 'admin_sharleen':
                $emailOrUsername = 'sharleeneozh@gmail.com';
                break;
            case 'admin_audric':
                $emailOrUsername = 'helen_kwek70@hotmail.com';
                break;
            case 'admin_erickaung':
                $emailOrUsername = 'eric';
                break;
            case 'admin_brandonng':
                $emailOrUsername = 'brandonng';
                break;
            case 'admin_joshua':
                $emailOrUsername = 'Joshua';
                break;
            case 'admin_winn':
                $emailOrUsername = 'Winn';
                break;
            case 'admin_jiaxin':
                $emailOrUsername = 'Jiaxin';
                break;
            case 'admin_ashton':
                $emailOrUsername = 'Ashton';
                break;
            case 'admin_fabiane':
                $emailOrUsername = 'Fabiane';
                break;
            case 'admin_junxi':
                $emailOrUsername = 'Junxi';
                break;
            case 'admin_qiaoyue':
                $emailOrUsername = 'Qiaoyue';
                break;
            case 'admin_minqi':
                $emailOrUsername = 'Minqi';
                break;
            case 'admin_thashwi':
                $emailOrUsername = 'Thashwi';
                break;
            case 'admin_winnlau':
                $emailOrUsername = 'Winn';
                break;
            case 'admin_winnaslau':
                $emailOrUsername = 'Winnas';
                break;
            case 'admin_winnielau':
                $emailOrUsername = 'Winnie_lau';
                break;
            default:
                break;
        }
        $user_id = $this->get_user_id_from_email_or_username($emailOrUsername);
        $user_role = $this->get_user_role_from_user_id($user_id);
        $multiple_role = $this->check_no_of_role($user_id);
        $profile = $this->get_user_profile_pic($user_id);
        $data_ar = array(
            'is_logged_in' => 1,
            'user_id' => $user_id,
            'user_role' => $user_role,
            'check_user_role' => $multiple_role,
            'profile_pic' => $profile,
            'branch_code'  => BRANCH_ID
        );
        $this->session->set_userdata($data_ar);
    }

    private function check_no_of_role($user_id) {
        $sql = "SELECT * FROM `sj_user_role` WHERE `user_id`=" . $this->db->escape($user_id);
        $query = $this->db->query($sql);

        if($query->num_rows() > 1) {
            return true;
        } else {
            return false;
        }
    }
    
    
	public function add_temp_user($key){
		$data_ar = array(            
			'email' => $this->input->post('register_email'),
			'password' => hash('sha256', $this->input->post('register_password')),
			'fullname' => $this->input->post('register_fullName'),
			'username' => $this->input->post('register_username'),
			'contact_no' => $this->input->post('register_mobile'),
			'registered_date' => date('Y-m-d H:i:s'),
			'key' => $key,
			'branch_code'  => BRANCH_ID   
		);
		
		$pattern = "/^$|[8|9]\d{3}\s\d{4}/";
		$patterns = "/^$|[8|9]\d{7}/";
		if(empty($data_ar['contact_no'])){
			$message = $this->session->set_flashdata('register_error', 'Mobile Number is a required field. Please fill in to register an account.');
			redirect(base_url() . 'site/login');
		}
		
		if(empty($data_ar['email']) && empty($data_ar['contact_no'])) {
			$message = $this->session->set_flashdata('register_error', 'User Email and User Mobile cannot be empty');
			redirect(base_url() . 'site/login');
		}
		
		if(!preg_match($pattern, $data_ar['contact_no']) && !preg_match($patterns, $data_ar['contact_no'])){
			$message = $this->session->set_flashdata('register_error', 'Please use Singapore registered mobile number starting with 8 or 9.');
			redirect(base_url() . 'site/login');
		}
		
		if(empty($data_ar['email'])){
			$message = $this->session->set_flashdata('register_error', 'Email address is a required field. Please fill in to register an account.');
			redirect(base_url() . 'site/login');
		}
		
		$sql = "SELECT * FROM `sj_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID."";        
		$query = $this->db->query($sql);        
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Email address has been taken. Please use another email address for registration.');
			redirect(base_url() . 'site/login');
		}
		$sql = "SELECT * FROM `sj_temp_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Email address has been taken. Please use another email address for registration.');
			redirect(base_url() . 'site/login');
		}
		
		$sql = "SELECT * FROM `sj_temp_users` WHERE `username` ='" . $data_ar['username'] . "' AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Username has been taken. Please use another username for registration.');
			redirect(base_url() . 'site/login');        
		}
		$insert_data = array();
		$insert_datas = array();
		foreach($data_ar as $k => $v) {
			$insert_data[] = "`" . $k . "`";
			$insert_datas[] = "'" . $v . "'";
		}
		$sql = "INSERT INTO `sj_temp_users` (" .implode(" ,", $insert_data) . ") VALUES (" . implode(" ,", $insert_datas) . ")";
        $query = $this->db->query($sql); 

		return $query ? true : false;    
	}
    
    public function add_temp_student($par_key, $key){
		if(empty($this->input->post('register_email'))) {
			$email = " ";
		} else {
			$email = $this->input->post('register_email');
		}

		$data_ar = array(
            'email' => $email,
			'password' => hash('sha256', $this->input->post('register_password')),
			'fullname' => $this->input->post('register_fullName'),
			'username' => $this->input->post('register_username'),
			'contact_no' => !empty($this->input->post('register_mobile'))?$this->input->post('register_mobile'):'-',
			'registered_date' => date('Y-m-d H:i:s'),
			'key' => $key,
			'branch_code'  => BRANCH_ID
		);
		
		$pattern = "/^$|[8|9]\d{3}\s\d{4}/";
		$patterns = "/^$|[8|9]\d{7}/";
		
		// if(!preg_match($pattern, $data_ar['contact_no']) && !preg_match($patterns, $data_ar['contact_no'])){
		// 	$message = $this->session->set_flashdata('register_error', 'Please use Singapore registered mobile number starting with 8 or 9.');
		// 	redirect($_SERVER['HTTP_REFERER']);
		// 	//redirect(base_url() . 'site/login');
		// }
		
		if(!empty($this->input->post('register_email'))) {
			$sql = "SELECT * FROM `sj_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID."";
			$query = $this->db->query($sql);
			
			if($query->num_rows() >0){
				$message = $this->session->set_flashdata('register_error', 'Email address has been taken. Please use another email address for registration.');
				redirect($_SERVER['HTTP_REFERER']);
				//redirect(base_url() . 'site/login');
			}
		}
		
		if(!empty($this->input->post('register_email'))) {
			$sql = "SELECT * FROM `sj_temp_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID."";
			$query = $this->db->query($sql);
			if($query->num_rows() >0){
				$message = $this->session->set_flashdata('register_error', 'Email address has been takens. Please use another email address for registration.');
				redirect($_SERVER['HTTP_REFERER']);
				//redirect(base_url() . 'site/login');
			}
        }  
        
        if(!empty($this->input->post('register_parent_email'))) {
            $par_email = $this->input->post('register_parent_email');
            $email = $this->input->post('register_email');

            if($par_email == $email) {
                $message = $this->session->set_flashdata('register_error', 'Parent\'s email and student\'s cannot be the same. Please use another email address for registration.');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
		
		$sql = "SELECT * FROM `sj_temp_users` WHERE `username` ='" . $data_ar['username'] . "' AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Username has been taken. Please use another username for registration.');
			redirect($_SERVER['HTTP_REFERER']);
			//redirect(base_url() . 'site/login');
		}
		
		$insert_data = array();
		$insert_datas = array();
		foreach($data_ar as $k => $v) {
			$insert_data[] = "`" . $k . "`";
			$insert_datas[] = "'" . $v . "'";
		}
		
		$sql = "INSERT INTO `sj_temp_users` (" .implode(" ,", $insert_data) . ") VALUES (" . implode(" ,", $insert_datas) . ")";
        $query = $this->db->query($sql);
        
        if($query) {
            $par_data = array(            
                'email' => $this->input->post('register_parent_email'),
                'password' => ' ',
                'fullname' => 'Temporary Parent Fullname',
                'username' => 'Temporary Parent Username',
                'contact_no' => $this->input->post('register_parent_mobile'),
                'registered_date' => date('Y-m-d H:i:s'),
                'key' => $par_key,
                'branch_code'  => BRANCH_ID      
            );

            if(!preg_match($pattern, $par_data['contact_no']) && !preg_match($patterns, $par_data['contact_no'])){
                $message = $this->session->set_flashdata('register_error', 'Please use Singapore registered mobile number starting with 8 or 9.');
                redirect($_SERVER['HTTP_REFERER']);
                //redirect(base_url() . 'site/login');
            }

            $insert_par_key = array();
            $insert_par_val = array();
            foreach($par_data as $k => $v) {
                $insert_par_key[] = "`" . $k . "`";
                $insert_par_val[] = "'" . $v . "'";
            }
            $sql = "INSERT INTO `sj_temp_users` (" .implode(" ,", $insert_par_key) . ") VALUES (" . implode(" ,", $insert_par_val) . ")";
            $query = $this->db->query($sql); 

            $sql = "INSERT INTO `sj_temp_relationship` (`student_key`, `parent_key`, `student_id`, `status`,  `branch_tag`) VALUES (" . $this->db->escape($key) . ", " . $this->db->escape($par_key) . ", 'NULL', 2, '".BRANCH_TAG."')";
            $this->db->query($sql);
        }

		return $query ? true : false;
	}
	
	public function create_temp_student($key, $par_key){
		if(empty($this->input->post('create_student_email'))) {
			$email = "";
		} else {
			$email = $this->input->post('create_student_email');
		}
		
		$data_ar = array(
			'email' => $email,
			'password' => hash('sha256', $this->input->post('create_student_password')),
			'fullname' => $this->input->post('create_student_fullname'),
			'username' => $this->input->post('create_student_username'),
			'contact_no' => $this->input->post('create_student_mobile'),
			'registered_date' => date('Y-m-d H:i:s'),
			'key' => $key,
			'branch_code'  => BRANCH_ID
		);
		
		$pattern = "/^$|[8|9]\d{3}\s\d{4}/";
		$patterns = "/^$|[8|9]\d{7}/";
		$insert_data = array();
		$insert_datas = array();
		foreach($data_ar as $k => $v) {
			$insert_data[] = "`" . $k . "`";
			$insert_datas[] = "'" . $v . "'";
        }
        
		$sql = "INSERT INTO `sj_temp_users` (" .implode(" ,", $insert_data) . ") VALUES (" . implode(" ,", $insert_datas) . ")";
        $query = $this->db->query($sql);
        
        if($query) {

            $par_data = array(            
                'email' => $this->input->post('create_parent_email'),
                'password' => ' ',
                'fullname' => 'Temporary Parent Fullname',
                'username' => 'Temporary Parent Username',
                'contact_no' => $this->input->post('register_parent_mobile'),
                'registered_date' => date('Y-m-d H:i:s'),
                'key' => $par_key,
                'branch_code'  => BRANCH_ID
            );

            $insert_par_key = array();
            $insert_par_val = array();
            foreach($par_data as $k => $v) {
                $insert_par_key[] = "`" . $k . "`";
                $insert_par_val[] = "'" . $v . "'";
            }
            $sql = "INSERT INTO `sj_temp_users` (" .implode(" ,", $insert_par_key) . ") VALUES (" . implode(" ,", $insert_par_val) . ")";
            $query = $this->db->query($sql); 
            
            $sql = "INSERT INTO `sj_temp_relationship` (`student_key`, `parent_key`, `student_id`, `status`,  `branch_tag`) VALUES (" . $this->db->escape($key) . ", " . $this->db->escape($par_key) . ", 'NULL', 2, '".BRANCH_TAG."')";
            $this->db->query($sql);
        }
		return $query ? true : false;
    }

    public function add_parent($parkey, $email, $school_id = NULL, $level_id = NULL, $key = NULL, $tutorId = NULL)
    {
        $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
        $temp_user = $this->db->query($sql, array($parkey));

        if ($temp_user) {
            $temp_user_row = $temp_user->row();
            $data_ar = array(
                'email' => $temp_user_row->email,
                'password' => $temp_user_row->password,
                'fullname' => $temp_user_row->fullname,
                'username' => $temp_user_row->username,
                'contact_no' => $temp_user_row->contact_no,
                'branch_code' => $temp_user_row->branch_code,
                'last_login' => 'parent',
                'status' => '1',
                'registered_date' => date('Y-m-d H:i:s')
            );
        }

        $sql = "SELECT * FROM `sj_users` WHERE `email`=" . $this->db->escape($data_ar['email']) . " AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            $userId = $query->row()->id;

            $sql = "UPDATE `sj_users` SET `last_login` =" . $this->db->escape('parent') . " WHERE `id`=" . $this->db->escape($userId) . " AND `branch_code` = ".BRANCH_ID."";
            $query = $this->db->query($sql);
            
            $user_role_insert_ar = array(
                'user_id' => $userId,
                'role_id' => 3,
                'branch_code' => BRANCH_ID
            );

            $add_role_query = $this->db->insert('sj_user_role', $user_role_insert_ar);

            if($add_role_query){
                $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($userId) . ", " . $this->db->escape($data_ar['email']) . ",'parent', 1)";
                $query_branch_user = $this->db->query($sql_branch_user);
            }

            if(isset($key) && !empty($key) == TRUE) {
                $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
                $temp_child_user = $this->db->query($sql, array($key));

                if ($temp_child_user) {
                    $temp_child_row = $temp_child_user->row();
                    $child_ar = array(
                        'email' => $temp_child_row->email,
                        'password' => $temp_child_row->password,
                        'fullname' => $temp_child_row->fullname,
                        'username' => $temp_child_row->username,
                        'contact_no' => $temp_child_row->contact_no,
                        'branch_code' => $temp_child_row->branch_code,
                        'last_login' => 'student',
                        'status' => '1',
                        'registered_date' => date('Y-m-d H:i:s')
                    );
                }

                $add_child_query = $this->db->insert('sj_users', $child_ar);

                if($add_child_query) {
                    $childId = $this->db->insert_id();
                    $child_role_insert_ar = array(
                        'user_id' => $childId,
                        'role_id' => 2,
                        'branch_code' => BRANCH_ID
                    );

                    $child_role_query = $this->db->insert('sj_user_role', $child_role_insert_ar);
                    if($child_role_query){
                        $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES (" . $this->db->escape($childId) . ", 1), (" . $this->db->escape($childId) . ", ".BRANCH_ID.")";
                        $query = $this->db->query($sql);
                        $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($child_ar['email']) . ",'student', 1)";
                        $query_branch_user = $this->db->query($sql_branch_user);
                        if(isset($tutorId) && !empty($tutorId) == TRUE) {
                            $sql_tutor = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($tutorId) . ", 1, '".BRANCH_TAG."')";
                            $query_tutor = $this->db->query($sql_tutor);
                        }
                        $sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($userId) . ", 1, '".BRANCH_TAG."')";
                        $query_relationship = $this->db->query($sql_relationship);
                        $sql_student = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($school_id) . ", " . $this->db->escape($level_id) . ")";
                        $query_student = $this->db->query($sql_student);

                    }
                    $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
                    $delete_temp_user = $this->db->query($delete_temp_user_sql, array($key));
                }
            } 

            $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
            $delete_temp_user = $this->db->query($delete_temp_user_sql, array($parkey));
            return $data_ar['email']; //return email to be set in session

        } else {
            
            $add_user_query = $this->db->insert('sj_users', $data_ar);

            if ($add_user_query) {
                $userId = $this->db->insert_id();
                $user_role_insert_ar = array(
                    'user_id' => $userId,
                    'role_id' => 3,
                    'branch_code' => BRANCH_ID
                );

                $add_role_query = $this->db->insert('sj_user_role', $user_role_insert_ar);
                if($add_role_query){
                    $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES (" . $this->db->escape($userId) . ", 1), (" . $this->db->escape($userId) . ", ".BRANCH_ID.")";
                    $query = $this->db->query($sql);
                    $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($userId) . ", " . $this->db->escape($data_ar['email']) . ",'parent', 1)";
                    $query_branch_user = $this->db->query($sql_branch_user);
                }

                if(isset($key) && !empty($key) == TRUE) {
                    $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
                    $temp_child_user = $this->db->query($sql, array($key));
    
                    if ($temp_child_user) {
                        $temp_child_row = $temp_child_user->row();
                        $child_ar = array(
                            'email' => $temp_child_row->email,
                            'password' => $temp_child_row->password,
                            'fullname' => $temp_child_row->fullname,
                            'username' => $temp_child_row->username,
                            'contact_no' => $temp_child_row->contact_no,
                            'branch_code' => $temp_child_row->branch_code,
                            'last_login' => 'student',
                            'status' => '1',
                            'registered_date' => date('Y-m-d H:i:s')
                        );
                    }
    
                    $add_child_query = $this->db->insert('sj_users', $child_ar);
    
                    if($add_child_query) {
                        $childId = $this->db->insert_id();
                        $child_role_insert_ar = array(
                            'user_id' => $childId,
                            'role_id' => 2,
                            'branch_code' => BRANCH_ID
                        );
    
                        $child_role_query = $this->db->insert('sj_user_role', $child_role_insert_ar);
                        if($child_role_query){
                            $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES (" . $this->db->escape($childId) . ", '1'), (" . $this->db->escape($childId) . ", '".BRANCH_ID."')";
                            $query = $this->db->query($sql);
                            $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($child_ar['email']) . ",'student', 1)";
                            $query_branch_user = $this->db->query($sql_branch_user);
                            if(isset($tutorId) && !empty($tutorId) == TRUE) {
                                $sql_tutor = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($tutorId) . ",  1, '".BRANCH_TAG."')";
                                $query_tutor = $this->db->query($sql_tutor);
                            }
                            $sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($userId) . ", 2, '".BRANCH_TAG."')";
                            $query_relationship = $this->db->query($sql_relationship);
                            $sql_student = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($school_id) . ", " . $this->db->escape($level_id) . ")";
                            $query_student = $this->db->query($sql_student);
    
                        }
                        $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
                        $delete_temp_user = $this->db->query($delete_temp_user_sql, array($key));
                    }
                } else {
                    $sql = "SELECT `student_id` FROM `sj_temp_relationship` WHERE `parent_key` = " . $this->db->escape($parkey) . " AND `branch_tag` = '".BRANCH_TAG."'";
                    $query = $this->db->query($sql);
                    $student_id = $query->row()->student_id;
                    $sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($student_id) . ", " . $this->db->escape($userId) . ", 1, '".BRANCH_TAG."')";
                    $query_relationship = $this->db->query($sql_relationship);
                    $delete_temp_relationship_sql = "DELETE FROM `sj_temp_users` WHERE `key` = " . $this->db->escape($parkey) . " AND `branch_tag` = '".BRANCH_TAG."'";
                    $delete_temp_relationship = $this->db->query($delete_temp_relationship_sql);    
                }
                
                $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
                $delete_temp_user = $this->db->query($delete_temp_user_sql, array($parkey));
                return $data_ar['email']; //return email to be set in session
            }
        }

        return false;

    }
    
    public function add_new_parent($parkey, $email, $school_id, $level_id, $key = NULL, $tutorId = NULL)
    {
        $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
        $temp_parent_user = $this->db->query($sql, array($parkey));
        if ($temp_parent_user) {
            $temp_parent_row = $temp_parent_user->row();
            $parent_ar = array(
                'email' => $temp_parent_row->email,
                'password' => hash('sha256', $this->input->post('register_password')),
                'fullname' => $this->input->post('register_fullName'),
                'username' => $this->input->post('register_username'),
                'contact_no' => $temp_parent_row->contact_no,
                'branch_code' => $temp_parent_row->branch_code,
                'last_login' => 'parent',
                'status' => '1',
                'registered_date' => date('Y-m-d H:i:s')
            );
        }

        $add_user_query = $this->db->insert('sj_users', $parent_ar);

        if ($add_user_query) {
            $userId = $this->db->insert_id();

            $user_role_insert_ar = array(
                'user_id' => $userId,
                'role_id' => 3,
                'branch_code' => BRANCH_ID
            );

            $add_role_query = $this->db->insert('sj_user_role', $user_role_insert_ar);
            
            if(isset($key) && $key != "") {
                
                $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
                $temp_child_user = $this->db->query($sql, array($key));
                if ($temp_child_user) {
                    $temp_child_row = $temp_child_user->row();
                    $child_ar = array(
                        'email' => $temp_child_row->email,
                        'password' => $temp_child_row->password,
                        'fullname' => $temp_child_row->fullname,
                        'username' => $temp_child_row->username,
                        'contact_no' => $temp_child_row->contact_no,
                        'branch_code' => $temp_child_row->branch_code,
                        'last_login' => 'student',
                        'status' => '1',
                        'registered_date' => date('Y-m-d H:i:s')
                    );

                    $add_child_query = $this->db->insert('sj_users', $child_ar);
                    $childId = $this->db->insert_id();
                }

                $student_role_insert_ar = array(
                    'user_id' => $childId,
                    'role_id' => 2,
                    'branch_code' => BRANCH_ID
                );
    
                $add_role_query = $this->db->insert('sj_user_role', $student_role_insert_ar);

                $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES (" . $this->db->escape($childId) . ", 1), (" . $this->db->escape($childId) . ", ".BRANCH_ID.")";
                $query = $this->db->query($sql);
                $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($child_ar['email']) . ",'student', 1)";
                $query_branch_user = $this->db->query($sql_branch_user);
                if(isset($tutorId) && !empty($tutorId) == TRUE) {
                    $sql_tutor = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($tutorId) . ", 1, '".BRANCH_TAG."')";
                    $query_tutor = $this->db->query($sql_tutor);
                }
                $sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($userId) . ", 2, '".BRANCH_TAG."')";
                $query_relationship = $this->db->query($sql_relationship);
                $sql_student = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($school_id) . "," . $this->db->escape($level_id) . ")";
                $query_student = $this->db->query($sql_student);
            } else {
                
                $sql = "SELECT `student_id` FROM `sj_temp_relationship` WHERE `parent_key` = " . $this->db->escape($parkey) . " AND `branch_tag` = '".BRANCH_TAG."'";
                $query = $this->db->query($sql);
                $student_id = $query->row()->student_id;

                if($student_id === 'NULL') {
                    $student_id = '';
                }
                
                if(isset($student_id) && !empty($student_id) == TRUE) {
                    $sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($student_id) . ", " . $this->db->escape($userId) . ", 2, '".BRANCH_TAG."')";
                    $query_relationship = $this->db->query($sql_relationship);
                    $delete_temp_relationship_sql = "DELETE FROM `sj_temp_relationship` WHERE `parent_key` = " . $this->db->escape($parkey) . " AND `branch_tag` = '".BRANCH_TAG."'";
                    $delete_temp_relationship = $this->db->query($delete_temp_relationship_sql);  
                }
            }

            if($add_role_query) {

                $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES (" . $this->db->escape($userId) . ", 1), (" . $this->db->escape($userId) . ", ".BRANCH_ID.")";
                $query = $this->db->query($sql);
                $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($userId) . ", " . $this->db->escape($parent_ar['email']) . ",'parent', 1)";
                $query_branch_user = $this->db->query($sql_branch_user);

            }
            $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
            $delete_temp_user = $this->db->query($delete_temp_user_sql, array($parkey));
            return $parent_ar['email']; //return email to be set in session
        }

        return false;

    }

    public function add_exist_parent($parkey, $school_id, $level_id, $key, $tutorId = NULL)
    {
        $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
        $temp_parent_user = $this->db->query($sql, array($parkey));
        if ($temp_parent_user) {
            $temp_parent_row = $temp_parent_user->row();
            $parEmail = $temp_parent_row->email;
        }

        $sql = "SELECT * FROM `sj_users` WHERE `email`=" . $this->db->escape($parEmail) . " AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {

            $parId = $query->row()->id;

            $sql = "SELECT * FROM `sj_branch_user` WHERE `user_id` = " . $this->db->escape($parId) . " AND `account_type` = 'parent'";
            $parent_query = $this->db->query($sql);

            if($parent_query->num_rows() > 0) {
                if(isset($key) && !empty($key) == TRUE) {

                    $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
                    $temp_child_user = $this->db->query($sql, array($key));
                    if ($temp_child_user) {
                        $temp_child_row = $temp_child_user->row();
                        $child_ar = array(
                            'email' => $temp_child_row->email,
                            'password' => $temp_child_row->password,
                            'fullname' => $temp_child_row->fullname,
                            'username' => $temp_child_row->username,
                            'contact_no' => $temp_child_row->contact_no,
                            'branch_code' => $temp_child_row->branch_code,
                            'last_login' => 'student',
                            'status' => '1',
                            'registered_date' => date('Y-m-d H:i:s')
                        );
                    }

                    $add_child_query = $this->db->insert('sj_users', $child_ar);
                    $childId = $this->db->insert_id();

                    $child_role_insert_ar = array(
                        'user_id' => $childId,
                        'role_id' => 2,
                        'branch_code' => BRANCH_ID
                    );
        
                    $add_child_role_query = $this->db->insert('sj_user_role', $child_role_insert_ar);

                    if($add_child_role_query) {
                        $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES (" . $this->db->escape($childId) . ", '1'), (" . $this->db->escape($childId) . ", '".BRANCH_ID."')";
                        $query = $this->db->query($sql);
                        $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($child_ar['email']) . ",'student', '1')";
                        $query_branch_user = $this->db->query($sql_branch_user);
                        $sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($parId) . ",'2', '".BRANCH_TAG."')";
                        $query_relationship = $this->db->query($sql_relationship);
                        if(isset($tutorId) && !empty($tutorId) == TRUE) {
                            $sql_tutor = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($tutorId) . ",'1', '".BRANCH_TAG."')";
                            $query_tutor = $this->db->query($sql_tutor);
                        }
                        $sql_student = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($school_id) . ", " . $this->db->escape($level_id) . ")";
                        $query_student = $this->db->query($sql_student);
                    }
                }
                $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
                $delete_temp_user = $this->db->query($delete_temp_user_sql, array($parkey));

            }else {
                $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($parId) . ", " . $this->db->escape($parEmail) . ",'parent', '1')";
                $query_branch_user = $this->db->query($sql_branch_user);

                $parent_role_insert_ar = array(
                    'user_id' => $parId,
                    'role_id' => 3,
                    'branch_code' => BRANCH_ID
                );
    
                $add_parent_role_query = $this->db->insert('sj_user_role', $parent_role_insert_ar);

                $add_child_query = $this->db->insert('sj_users', $child_ar);
                $childId = $this->db->insert_id();

                $child_role_insert_ar = array(
                    'user_id' => $childId,
                    'role_id' => 2,
                    'branch_code' => BRANCH_ID
                );
    
                $add_child_role_query = $this->db->insert('sj_user_role', $child_role_insert_ar);

                if($add_child_role_query) {
                    $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES (" . $this->db->escape($childId) . ", '1'), (" . $this->db->escape($childId) . ", '".BRANCH_ID."')";
                    $query = $this->db->query($sql);
                    $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($child_ar['email']) . ",'student', '1')";
                    $query_branch_user = $this->db->query($sql_branch_user);
                    $sql_relationship = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($parId) . ",'2', '".BRANCH_TAG."')";
                    $query_relationship = $this->db->query($sql_relationship);
                    if(isset($tutorId) && !empty($tutorId) == TRUE) {
                        $sql_tutor = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($tutorId) . ",'1', '".BRANCH_TAG."')";
                        $query_tutor = $this->db->query($sql_tutor);
                    }
                    $sql_student = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES (" . $this->db->escape($childId) . ", " . $this->db->escape($school_id) . ", " . $this->db->escape($level_id) . ")";
                    $query_student = $this->db->query($sql_student);
                }

                $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
                $delete_temp_user = $this->db->query($delete_temp_user_sql, array($parkey));
                
            }

            

            return $parEmail; //return email to be set in session
            
        }

        return false;

    }


    public function add_user($key, $tutorId = NULL)
    {
        $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
        $temp_user = $this->db->query($sql, array($key));

        if ($temp_user) {
            $temp_user_row = $temp_user->row();
            $data_ar = array(
                'email' => $temp_user_row->email,
                'password' => $temp_user_row->password,
                'fullname' => $temp_user_row->fullname,
                'username' => $temp_user_row->username,
                'contact_no' => $temp_user_row->contact_no,
                'branch_code' => $temp_user_row->branch_code,
                'last_login' => 'tutor',
                'status' => '1',
                'registered_date' => date('Y-m-d H:i:s')
            );
        }

        $add_user_query = $this->db->insert('sj_users', $data_ar);

        if ($add_user_query) {
            $userId = $this->db->insert_id();
            $user_role_insert_ar = array(
                'user_id' => $userId,
                'role_id' => 1,
                'branch_code' => BRANCH_ID
            );

            $add_role_query = $this->db->insert('sj_user_role', $user_role_insert_ar);
            if($add_role_query){
	            $sql = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES ('".$userId."', '1'), ('".$userId."', '".BRANCH_ID."')";
	            $query = $this->db->query($sql);
	            $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES ('" . $userId . "', '" . $data_ar['email'] . "','tutor', '1')";
				$query_branch_user = $this->db->query($sql_branch_user);
            }
            $delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ? ";
            $delete_temp_user = $this->db->query($delete_temp_user_sql, array($key));
            return $data_ar['email']; //return email to be set in session
        }

        return false;

    }
    
    public function add_student_user($key, $school_id, $level_id, $tutorId, $pemail)
    {
        
	    $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = '" . $key . "'";
        $query = $this->db->query($sql);
        if($query){
	        $query_row = $query->row();
        }

        $sql = "SELECT * FROM `sj_users` WHERE `branch_code` = ".BRANCH_ID." AND `email` = " . $this->db->escape($pemail);
        $par_query = $this->db->query($sql);
        
        $sql = "INSERT INTO `sj_users` (`email`, `fullname`, `password`, `username`,`contact_no`, `profile_pic`, `branch_code`, `status`, `last_login`) VALUES ('".$query_row->email."','".$query_row->fullname."','". $query_row->password ."','".$query_row->username."','".$query_row->contact_no."', 'student_placeholder.jpg','".BRANCH_ID."','1', 'student')";
        $query1 = $this->db->query($sql);
        $userId = $this->db->insert_id();
        
        $sql2 = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES ('".$userId."','".$school_id."','".$level_id."')";
        $query2 = $this->db->query($sql2);

        if(!empty($par_query->result())){
            $parent = $par_query->row();

            $sql3 = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES ('".$userId."','".$parent->id."',2, '".BRANCH_TAG."')";
            $query3 = $this->db->query($sql3);
        } else {
            $sql5 = "UPDATE `sj_temp_relationship` SET `student_id` = " . $this->db->escape($userId) . " WHERE `student_key` = " . $this->db->escape($key);
            $query5 = $this->db->query($sql5);
        }

        
        $sql4 = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES ('".$userId."','".$tutorId."',1, '".BRANCH_TAG."')";
        $query4 = $this->db->query($sql4);
        
        $sql5 = "INSERT INTO `sj_user_role` (`user_id`, `role_id`, `branch_code`) VALUES ('".$userId."',2,".BRANCH_ID.")";
        $query5 = $this->db->query($sql5);
        
        $sql6 = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES ('".$userId."', 1), ('".$userId."', ".BRANCH_ID.")";
        $query5 = $this->db->query($sql6);
        
        $sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES ('" . $userId . "', '" . $query_row->email . "','student', '1')";
		$query_branch_user = $this->db->query($sql_branch_user);
        
        
        return ($query_row->email == " ")? $query_row->username:$query_row->email;
    }
    
    public function add_student_without_tutor($key, $school_id, $level_id, $pemail) {
	    $date = date('Y-m-d H:i:s');
	    $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = '" . $key . "'";
	    $query = $this->db->query($sql);
	    if($query){
			$query_row = $query->row();
        }
        
        $sql = "SELECT * FROM `sj_users` WHERE `branch_code` = ".BRANCH_ID." AND `email` = " . $this->db->escape($pemail);
        $par_query = $this->db->query($sql);
		
		$sql = "INSERT INTO `sj_users` (`email`, `fullname`, `password`, `username`,`contact_no`, `profile_pic`,`registered_date`, `branch_code`, `status`, `last_login`) VALUES ('".$query_row->email."','".$query_row->fullname."','". $query_row->password ."','".$query_row->username."','".$query_row->contact_no."', 'student_placeholder.jpg','".$date."','".BRANCH_ID."', '1', 'student')";
		$query1 = $this->db->query($sql);
		$userId = $this->db->insert_id();
		
		$sql2 = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES ('".$userId."','".$school_id."','".$level_id."')";
		$query2 = $this->db->query($sql2);
		
		$sql3 = "INSERT INTO `sj_user_role` (`user_id`, `role_id`, `branch_code`) VALUES ('".$userId."',2,".BRANCH_ID.")";
		$query3 = $this->db->query($sql3);
		
		$sql4 = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES ('".$userId."', 1), ('".$userId."', ".BRANCH_ID.")";
        $query4 = $this->db->query($sql4);

        if(!empty($par_query->result())){
            $parent = $par_query->row();

            $sql5 = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES ('".$userId."','".$parent->id."',2, '".BRANCH_TAG."')";
            $query5 = $this->db->query($sql5);
        } else {
            $sql5 = "UPDATE `sj_temp_relationship` SET `student_id` = " . $this->db->escape($userId) . " WHERE `student_key` = " . $this->db->escape($key);
            $query5 = $this->db->query($sql5);
        }
		
		$sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES ('" . $userId . "', '" . $query_row->email . "','student', '1')";
		$query_branch_user = $this->db->query($sql_branch_user);
		
		return ($query_row->email == " ")? $query_row->username:$query_row->email;
	}
	
	public function delete_temp_user($key) {
		$sql1 = "DELETE FROM `sj_temp_users` WHERE `key` = '" . $key . "'";
		$query1 = $this->db->query($sql1);
		return true;
	}

    
	public function temp_add_user(){
		$data = array(
			'account_type' => $this->input->post('register_type_btn'),
			'username' => $this->input->post('register_username'),
			'fullname' => $this->input->post('register_fullName'),
			'email' => $this->input->post('register_email'),
			'contact_no' => $this->input->post('register_mobile'),
			'password' => $this->input->post('register_password')
		);
		
		$mobile = $data['contact_no'];
		$email = $data['email'];
		
		if($data['account_type'][0] === 'tutor'){
			
			if(empty($email)){
				$message = $this->session->set_flashdata('update_error', 'Email address cannot be empty. Please fill in the email address for registration.');
				redirect(base_url() . 'administrator/add_user');
			}
			
			if(empty($mobile)){
				$message = $this->session->set_flashdata('update_error', 'Mobile Number cannot be empty. Please fill in the mobile number for registration.');
				redirect(base_url() . 'administrator/add_user');
			}
			
			$sql = "SELECT * FROM `sj_users` WHERE `email` = '" . $email . "' AND `branch_code` = ".BRANCH_ID."";
			if($this->db->query($sql)->num_rows() > 0){
				$message = $this->session->set_flashdata('update_error', 'Email has been taken by other user..');
				redirect(base_url() . 'administrator/add_user');
			}
			
			$password = hash('sha256', $data['password']);
			$sql = "INSERT INTO `sj_users` (`username`,`fullname`,`email`,`contact_no`, `password`, `status`) VALUES ('" . $data['username'] . "','" . $data['fullname'] . "','" . $data['email'] . "','" . $mobile . "' ,'" . $password . "', '1')";
			$query = $this->db->query($sql);
			
			if($query){
				$userId = $this->db->insert_id();
				
				$sql_user_branch = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES ('" . $userId . "', '1'), ('" . $userId . "', '".BRANCH_ID."')";
				
				$query_user_branch = $this->db->query($sql_user_branch);
				
				$sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES ('" . $userId . "', '" . $data['email'] . "','tutor', '1')";
				
				$query_branch_user = $this->db->query($sql_branch_user);
				
				$sql_add_role = "INSERT INTO `sj_user_role` (`user_id`, `role_id`, `branch_code`) VALUES ('" . $userId . "', '1', '".BRANCH_ID."')";
				
				$query_add_role = $this->db->query($sql_add_role);
			}
			if($query_add_role){
				$message = $this->session->set_flashdata('update_success', 'Successfully register new tutor.');
				redirect(base_url() . 'administrator/add_user');
			} else{
				$message = $this->session->set_flashdata('update_error', 'Error in registering tutor. Please try again later or contact administrator at admin@smartjen.sg');
				redirect(base_url() . 'administrator/add_user');
			}
			
		}else if($data['account_type'][0] === 'student'){
			
			if(empty($email)){
				$email = 'NULL';
			}
			
			$sql = "SELECT * FROM `sj_users` WHERE `email` = '" . $email . "' AND `branch_code` = ".BRANCH_ID."";
			if($this->db->query($sql)->num_rows() > 0){
				$message = $this->session->set_flashdata('update_error', 'Email has been taken by other user..');
				redirect(base_url() . 'administrator/add_user');
			}
			
			$password = hash('sha256', $data['password']);
			$sql = "INSERT INTO `sj_users` (`username`,`fullname`,`email`,`contact_no`, `password`, `profile_pic`, `branch_code`, `status`) VALUES ('" . $data['username'] . "','" . $data['fullname'] . "','" . $data['email'] . "','" . $mobile . "' ,'" . $password . "', 'student_placeholder.jpg','".BRANCH_ID."','1')";
			$query = $this->db->query($sql);
			
			if($query){
				$userId = $this->db->insert_id();
				
				$sql_user_branch = "INSERT INTO `sj_user_branch` (`user_id`, `branch_id`) VALUES ('" . $userId . "', '1'), ('" . $userId . "', '".BRANCH_ID."')";
				
				$query_user_branch = $this->db->query($sql_user_branch);
				
				$sql_add_role = "INSERT INTO `sj_user_role` (`user_id`, `role_id`, `branch_code`) VALUES ('" . $userId . "', '2', '".BRANCH_ID."')";
				
				$query_add_role = $this->db->query($sql_add_role);
				
				$sql_branch_user = "INSERT INTO `sj_branch_user` (`user_id`, `email`,`account_type`, `status`) VALUES ('" . $userId . "', '" . $data['email'] . "','student', '1')";
				
				$query_branch_user = $this->db->query($sql_branch_user);
				
				$sql_add_student = "INSERT INTO `sj_student` (`student_id`, `school_id`, `level_id`) VALUES ('" . $userId . "','" . $this->input->post('register_school') . "', '" . $this->input->post('register_level') . "' )";
				
				$query_add_student = $this->db->query($sql_add_student);
			}
			if($query_add_role && $query_add_student){
				$message = $this->session->set_flashdata('update_success', 'Successfully register new student.');
				redirect(base_url() . 'administrator/add_user');
			} else{
				$message = $this->session->set_flashdata('update_error', 'Error in registering student. Please try again later or contact administrator at admin@smartjen.sg');
				redirect(base_url() . 'administrator/add_user');
			}
		}
	}

    public function is_key_valid($key)
    {
        $sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
        $query = $this->db->query($sql, array($key));

        return ($query->num_rows() == 1) ? true : false;
    }

    public function create_student($post)
    {
        $isStudentParent = (isset($post['is_student_parent'])) ? $post['is_student_parent'] : '';
        if ($isStudentParent == 'is_student_parent') {
            $parent_email = $post['parent_email'];
            $status = 2; //children <-> parent relationship
        } else {
            $parent_email = '';
            $status = 1; //student <-> tutor relationship
        }

        // student email is not required, therefore generate a unique UUID for them
        /*$student_email = uniqid("sj_") . "@smartjen.com";

        $this->db->trans_start();
        $data_ar1 = array(
            'email' => $post['create_student_email'],
            'fullname' => $post['create_student_fullname'],
            'password' => hash('sha256', $post['create_student_password']),
            'username' => $post['create_student_username'],
            'profile_pic' => 'student_placeholder.jpg'
        );

        $query = $this->db->insert('sj_users', $data_ar1);*/
        $sql = "INSERT INTO `sj_users` (`email`, `fullname`, `password`, `username`, `contact_no`, `profile_pic`, `branch_code`, `status`) VALUES ('".$post['create_student_email']."','".$post['create_student_fullname']."','".hash('sha256', $post['create_student_password'])."','".$post['create_student_username']."', '".$post['create_student_mobile']."', 'student_placeholder.jpg','".BRANCH_ID."', '1');";
        
        $query = $this->db->query($sql);
        $tutor_id = $this->session->userdata('user_id');
        $user_id = $this->db->insert_id();
//            $data_ar2 = array(
//                'student_id' => $user_id,
//                'created_by' => $tutor_id,
//                'parent_email' => $post['parent_email']
//            );
//            $query2 = $this->db->insert('sj_student', $data_ar2);

        $data_ar3 = array(
            'student_id' => $user_id,
            'adult_id' => $tutor_id,
            'status' => $status
        );

        $query3 = $this->db->insert('sj_relationship', $data_ar3);

        $data_ar4 = array(
            'user_id' => $user_id,
            'role_id' => 2,
            'branch_code' => BRANCH_ID
        );

        $query4 = $this->db->insert('sj_user_role', $data_ar4);

        $data_ar5 = array(
            'student_id' => $user_id,
            'school_id'  => $post['create_student_school_id'],
            'level_id'   => $post['create_student_level_id']
        );

        $query5 = $this->db->insert('sj_student', $data_ar5);

        $this->db->trans_complete();
        return ($this->db->trans_status()) ? true : false;
    }

    public function get_username_from_id($userId)
    {
        $this->db->where('id', $userId);
        $query = $this->db->get('sj_users');

        return $query->row()->fullname;
    }

    public function get_user_id_from_email_or_username($emailOrUsername)
    {
		$sql = "SELECT `id` FROM `sj_users` WHERE (`email` = ".$this->db->escape($emailOrUsername)." OR `username` = ".$this->db->escape($emailOrUsername).")";
        $query = $this->db->query($sql);
        $data = array();
        if($query->num_rows() > 0) {
            foreach ($query->result() as $key=>$value){
                $data[] = $value->id;
            }
            
            $sql = "SELECT * FROM `sj_user_branch` WHERE `user_id` IN (".implode(",", $data).") and branch_id=".BRANCH_ID;
            $query = $this->db->query($sql);
            
            if($query) {
                $user_id = $data[0];
                foreach($query->result_array() as $keys=>$values){
                    $user_id = $values['user_id'];
                }
                return $user_id;
            } else return null;

            // $data_array = array();

            // foreach($query->result_array() as $keys=>$values){
            //     $data_array[] = $values['branch_id'];
            //     $data_array[] = $values['user_id'];
            // }
            
            // $key = array_search("1", $data_array);

            // return (in_array("1", $data_array)) ? $data_array[$key+1] : null;
        } else {
            return null;
        }
    }

    public function get_user_role_from_user_id($userId)
    {
        $sql = "SELECT `last_login` FROM `sj_users` WHERE `id`=". $this->db->escape($userId);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            $lastLogin = $query->row()->last_login;

            if($lastLogin == 'tutor') {
                return '1';
            } elseif ($lastLogin == 'student') {
                return '2';
            } elseif($lastLogin == 'parent') {
                return '3';
            }
        }
    }

    public function check_parent_username($email, $username, $password) {
        $sql = "SELECT * FROM `sj_users` WHERE (`email` = " . $this->db->escape($email) . " OR `username` = " . $this->db->escape($username) . ") AND `password` = '".$password."' AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0 ) {
            $username = $query->row()->username;

            if(isset($username) && !empty($username) == FALSE) {
                return $query->row()->id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function update_parent_detail($userId, $parUsername, $parFullname) {
        $sql = "SELECT * FROM `sj_users` WHERE `id` = " . $this->db->escape($userId);
        
        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            $update_sql = "UPDATE `sj_users` SET `username` = " . $this->db->escape($parUsername) . " , `fullname` =" . $this->db->escape($parFullname) . " WHERE `id` =" . $this->db->escape($userId);
            $update_query = $this->db->query($update_sql);
            return $query->row()->email;
        } else {
            return false;
        }
    }

    public function check_user_exist($userName)
    {  
        $sql = "SELECT * FROM `sj_users` WHERE `username` = '" . $userName . "'";
        $query = $this->db->query($sql);

        return ($query->num_rows() == 0) ? false : true;
    }
    
    public function check_email_exist($email) {
		if(empty($email)){
			$email = 'NULL';
		}
		$sql = "SELECT * FROM `sj_users` WHERE `email` = '" . $email . "'";
		$query = $this->db->query($sql);
		return ($query->num_rows() >= 1) ? true : false;
	}

    public function check_user_exist_using_email_or_username($emailOrUsername)
    {
        if(empty($emailOrUsername)){
	        $emailOrUsername = 'NULL';
        }
        
        $sql = "SELECT * FROM `sj_users` WHERE (`email` = '" . $emailOrUsername . "' OR `username` = '" . $emailOrUsername . "')";
        $query = $this->db->query($sql);

        return ($query->num_rows() >= 1) ? false : true;
    }

    public function search_student_username($username)
    {
        $sql = "SELECT `id`, `username`, `profile_pic` 
				FROM `sj_users` user
				JOIN  (SELECT `user_id` FROM `sj_user_role` WHERE `role_id` = 2) role
				WHERE `user`.`id` = `role`.`user_id` AND `user`.`username` LIKE CONCAT('%',?,'%')
		";

        $query = $this->db->query($sql, array($username));

        $returnArray = array();
        if ($query) {
            foreach ($query->result() AS $student) {
                $returnArray[] = $student;
            }
        }

        return $returnArray;

    }

    public function get_student_list($tutorId)
    {
        $sql = "SELECT `id`, `username`, `fullname`, `profile_pic`, `school_level`.`student_id`, `school_name`, `level_name`
				FROM `sj_users` user 
				JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `adult_id`= ? AND `status` = 1 AND `branch_tag` = '".BRANCH_TAG."') relationship
                JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
                      JOIN `sj_schools`
                      JOIN `sj_levels`
                      ON `sj_schools`.`school_id` = `sj_student`.`school_id` and `sj_levels`.`id` = `sj_student`.`level_id`
                ) school_level
				WHERE `user`.`id` = `relationship`.`student_id` and `school_level`.`student_id` = `relationship`.`student_id`
				ORDER BY `user`.`id` DESC 
		";

        $query = $this->db->query($sql, array($tutorId));
        
        $returnArray = array();
        if ($query) {
            foreach ($query->result() As $student) {
                $returnArray[] = $student;
            }
        }

        return $returnArray;
    }

    public function get_children_list($parentId)
    {
        $sql = "SELECT `username`, `fullname`, `profile_pic`, `school_level`.`student_id`, `school_name`, `level_name`
        FROM `sj_users` user 
        JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `adult_id`= ? AND `status` = 2 AND `branch_tag` = '".BRANCH_TAG."') relationship
        JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
              JOIN `sj_schools`
              JOIN `sj_levels`
              ON `sj_schools`.`school_id` = `sj_student`.`school_id` and `sj_levels`.`level_id` = `sj_student`.`level_id`
        ) school_level
        WHERE `user`.`id` = `relationship`.`student_id` and `school_level`.`student_id` = `relationship`.`student_id`
        ORDER BY `user`.`id` DESC 
		";

        $query = $this->db->query($sql, array($parentId));

        $returnArray = array();
        if ($query) {
            foreach ($query->result() As $student) {
                $returnArray[] = $student;
            }
        }

        return $returnArray;
    }

    public function get_tutor_list($adminId)
    {
        $sql = "SELECT `id`, `username`, `fullname`, `profile_pic`
				FROM `sj_users`  
				WHERE `branch_code` = '".BRANCH_ID."'
				ORDER BY `id` DESC 
		";

        $query = $this->db->query($sql, array($adminId));
        
        $returnArray = array();
        if ($query) {
            foreach ($query->result() As $tutor) {
                $returnArray[] = $tutor;
            }
        }

        return $returnArray;
    }

    public function get_tutor_uploaded_questions($tutorId) {
        $sql = "SELECT * FROM 
                (SELECT * 
                FROM `sj_questions` 
                where `source` = ?) ques
                LEFT JOIN sj_question_tag tag on tag.question_id = ques.question_id
                LEFT JOIN sj_levels levels on levels.level_id = ques.level_id
                LEFT JOIN sj_categories cat on cat.id = ques.topic_id
                ORDER BY ques.`insert_time`  
        ";

        $query = $this->db->query($sql, array($tutorId));
        $returnArray = array();
        if ($query) {
            foreach ($query->result() as $row) {
                $returnArray[] = $row;
            }
        }

        return $returnArray;
    }

	public function get_quiz_list($studentId, $userId)
	{
		
		if($studentId == '123'){
			$sql = "SELECT * 
					FROM `sj_quiz`
					WHERE `assignedTo` = '".$studentId."'
					AND `branch_tag` = '".BRANCH_TAG."'
					AND `worksheetId` IN (248, 334, 335)
					ORDER BY `assignedDate` DESC
			";
		} else {
			$sql = "SELECT * 
					FROM `sj_quiz`
					WHERE `assignedTo` = '".$studentId."'
					AND `branch_tag` = '".BRANCH_TAG."'
					AND `worksheetId` IN (SELECT `worksheet_id` FROM `sj_worksheet` WHERE `created_by` = '".$userId."')
					ORDER BY `assignedDate` DESC
			";
		}
		
		$query = $this->db->query($sql);
		$returnArray = array();
		if ($query) {
			foreach ($query->result() as $quiz) {
				$returnArray[] = $quiz;
			}
		}
		
		return $returnArray;
	}
	
	public function get_quiz_list_student($studentId, $subject = NULL)
	{
		if(isset($subject) && empty($subject) == FALSE) {
            $sql = "SELECT * 
                        FROM `sj_quiz`
                        WHERE `assignedTo` = '".$studentId."'
                        AND `branch_tag` = '".BRANCH_TAG."' AND `subject_type` = " . $this->db->escape($subject) . "
                        ORDER BY `assignedDate` DESC
                ";
        } else {
            $sql = "SELECT * 
                        FROM `sj_quiz`
                        WHERE `assignedTo` = '".$studentId."'
                        AND `branch_tag` = '".BRANCH_TAG."'
                        ORDER BY `assignedDate` DESC
                ";
        }
		$query = $this->db->query($sql);
		$returnArray = array();
		if ($query) {
			foreach ($query->result() as $quiz) {
				$returnArray[] = $quiz;
			}
		}
		
		return $returnArray;
	}
	
	public function count_get_quiz_list($studentId, $status)
    {
		$userId = $this->session->userdata('user_id');
		
		if($userId == '123'){
			if($status == "outstanding") {
				$sql = "
					SELECT COUNT(*) as TOTAL
					FROM `sj_quiz` quiz
					WHERE NOT EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `worksheetId` IN (248, 334, 335)
					AND `assignedTo` = ".$studentId."
				";
			} else {
				$sql = "
					SELECT COUNT(*) as TOTAL
					FROM `sj_quiz` quiz
					WHERE EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `worksheetId` IN (248, 334, 335)
					AND `assignedTo` = ".$studentId."
				";
			}
		} else {
			if($status == "outstanding") {
				$sql = "
					SELECT COUNT(*) as TOTAL
					FROM `sj_quiz` quiz
					WHERE NOT EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `assignedTo` = ".$studentId."
					AND `branch_tag` = '".BRANCH_TAG."'
				";
			} else {
				$sql = "
					SELECT COUNT(*) as TOTAL
					FROM `sj_quiz` quiz
					WHERE EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `assignedTo` = ".$studentId."
					AND `branch_tag` = '".BRANCH_TAG."'
				";
			}
		}
		$query = $this->db->query($sql);
		return $query->row()->TOTAL;
    }
	
	public function get_quiz_lists($studentId, $status, $limit, $start)
	{
		$userId = $this->session->userdata('user_id');
		
		if($userId == '123'){
			if($status == "outstanding") {
				$sql = "
					SELECT *
					FROM `sj_quiz` quiz
					WHERE NOT EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `assignedTo` = ".$studentId."
					AND `worksheetId` IN (248, 334, 335)
					ORDER BY `assignedDate` DESC
					LIMIT ".$limit." OFFSET ".$start."
				";
			} else {
				$sql = "
					SELECT *
					FROM `sj_quiz` quiz
					WHERE EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `assignedTo` = ".$studentId."
					AND `worksheetId` IN (248, 334, 335)
					ORDER BY `assignedDate` DESC
					LIMIT ".$limit." OFFSET ".$start."
				";
			}
		} else {
			if($status == "outstanding") {
				$sql = "
					SELECT *
					FROM `sj_quiz` quiz
					WHERE NOT EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `assignedTo` = ".$studentId."
					AND `branch_tag` = '".BRANCH_TAG."'
					ORDER BY `assignedDate` DESC
					LIMIT ".$limit." OFFSET ".$start."
				";
			} else {
				$sql = "
					SELECT *
					FROM `sj_quiz` quiz
					WHERE EXISTS (SELECT * FROM `sj_quiz_attempt` WHERE `quiz`.`id` = `sj_quiz_attempt`.`quizId`)
					AND `assignedTo` = ".$studentId."
					AND `branch_tag` = '".BRANCH_TAG."'
					ORDER BY `assignedDate` DESC
					LIMIT ".$limit." OFFSET ".$start."
				";
			}
		}
		
		$query = $this->db->query($sql, array($studentId));
		$returnArray = array();
		if ($query) {
			foreach ($query->result() as $quiz) {
				$returnArray[] = $quiz;
			}
		}
		return $returnArray;
	}

	public function get_tutor_quiz_list($tutorId)
	{
		$userId = $this->session->userdata('user_id');
		
		if($userId == '121'){
			$sql = "
				SELECT * FROM `sj_quiz` quiz
				JOIN (SELECT `worksheet_id`, `created_by` FROM `sj_worksheet` WHERE `created_by`= ?) worksheet
				WHERE `quiz`.`worksheetId` = `worksheet`.`worksheet_id`
				AND `worksheetId` IN (248,334,335)
				AND `assignedTo` = '123'
				ORDER BY `assignedDate` DESC
			";
		}else {
			$sql = "
				SELECT * FROM `sj_quiz` quiz
				JOIN (SELECT `worksheet_id`, `created_by` FROM `sj_worksheet` WHERE `created_by`= ? AND `branch_tag` = '".BRANCH_TAG."') worksheet
				WHERE `quiz`.`worksheetId` = `worksheet`.`worksheet_id` AND `branch_tag` = '".BRANCH_TAG."' ORDER BY `assignedDate` DESC
			";
		}
		
		$query = $this->db->query($sql, array($tutorId));
		$returnArray = array();
		if ($query) {
			foreach ($query->result() as $quiz) {
				$returnArray[] = $quiz;
			}
		}
		
		return $returnArray;
	}

    public function get_quiz_score($student_id=0,$worksheet_id=0,$q_num=0,$attemptId="") { 
        if($attemptId=="") {
            $sql = "SELECT * FROM `sj_quiz` WHERE worksheetId={$worksheet_id} AND assignedTo={$student_id} AND `branch_tag` = '".BRANCH_TAG."' LIMIT 1";
            $query = $this->db->query($sql);
            $res = $query->row();
            if($res) {
                $quiz_id = $res->id;
                $sql = "SELECT * FROM `sj_quiz_attempt` WHERE quizId={$quiz_id} AND `branch_code` = ".BRANCH_ID." LIMIT 1";
                $query = $this->db->query($sql);
                $res = $query->row();
                if($res) {
                    $attempt_id = $res->id;
                    $sql = "SELECT * FROM `sj_quiz_attempt_answer` WHERE attemptId={$attempt_id} AND questionNo={$q_num} AND `branch_code` = ".BRANCH_ID." LIMIT 1";
                    $query = $this->db->query($sql);
                    $res = $query->row();
                    return $res->score;
                }
            }
        } else {
            $attempt_id = $attemptId;
            $sql = "SELECT * FROM `sj_quiz_attempt_answer` WHERE attemptId={$attempt_id} AND questionNo={$q_num} AND `branch_code` = ".BRANCH_ID." LIMIT 1";
            $query = $this->db->query($sql);
            $res = $query->row();
            return ($res) ? $res->score : 0;
        }
        return 0;
    }

    public function get_performance($studentIdArray, $subject = NULL, $date_range = array(), $worksheetIdArray = array())
    {
        
        $userId = $this->session->userdata('user_id');
        $sec_student = range(492, 512);
        
        if($subject == NULL) {
            if(!empty(array_intersect($sec_student, $studentIdArray))) {
                $subject = 5;
            } else {
                $subject = 2;
            }
        }
        $category_structure = $this->get_category_structure($subject);
        $analysis_structure = $this->get_analysis_structure($subject);
        $strategy_structure = $this->model_question->get_strategy_structure($subject);
        $strand_structure = $this->model_question->get_strand_structure($subject);
        $student_performance = array();
        foreach ($studentIdArray as $student_id) {
            $analysis_structure = $this->get_analysis_structure($subject);
            $analysis_structure['student_id'] = $student_id;
            $quizzes = $this->model_users->get_quiz_list_student($student_id, $subject);
            foreach ($quizzes as $quiz) {
                $attempt_ids = $this->model_quiz->get_attempt_ids($quiz->id, $date_range);
                // array_debug($quiz->id);exit;
                $worksheet_id = $this->model_worksheet->get_worksheet_id_from_quiz_id($quiz->id);
            //    if(count($worksheetIdArray)>0) { if(!in_array($worksheet_id, $worksheetIdArray)) break; }
                $isMockExam = $this->model_worksheet->is_mock_exam($worksheet_id);
                if(!$isMockExam) {
                    $questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheet_id);
                    
                    $questionsArray = $questions->result();
                    
                    $requirement = $this->model_worksheet->get_requirement_from_worksheetId($worksheet_id);
                    
                    foreach ($attempt_ids as $attempt_id) {
                        $userAnswers = $this->model_quiz->get_attempt_answer($attempt_id);

                        $userScores = $this->model_quiz->get_attempt_score_array($attempt_id);
   
                        foreach ($questionsArray as $question) {
	                        
                            $detail = $this->model_question->get_question_from_id($question->question_id);
                            
                            $question_category = $detail->topic_id;

                            $question_strategy = $detail->strategy_id;

                            $question_category_name = $category_structure[$question_category]['category_name'];

                            $question_substrand_name = $category_structure[$question_category]['substrand'];

                            $question_strand_name = $category_structure[$question_category]['strand'];
    
                            $correctAnswer = $this->model_question->get_correct_answer_from_question_id($question->question_id);
                            
                            $correctAnswerText = $this->model_question->get_correct_answer_text_from_correct_id($correctAnswer);
                            
                            $userAnswer = (isset($userAnswers[$question->question_number - 1])) ? $userAnswers[$question->question_number - 1] : -1;
                            
                            $userScore = (isset($userScores[$question->question_number - 1])) ? $userScores[$question->question_number - 1] : 0;

                            $generated_question_type = $this->model_worksheet->get_generated_question_type($requirement->requirement_id, $question->question_id);
                            
                            $analysis_structure[$question_strand_name]['total_attempt'] += $detail->difficulty;
                            
                            $analysis_structure[$question_strand_name][$question_substrand_name]['total_attempt'] += $detail->difficulty;
                            
                            $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_attempt'] += $detail->difficulty;

                            if(isset($analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy])){

                                $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy]['total_attempt'] += $detail->difficulty;

                            }

                            if($generated_question_type === '1'){
	                            if ($userAnswer == $correctAnswer) {
	                                $analysis_structure[$question_strand_name]['total_correct'] += $detail->difficulty;
	                                $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += $detail->difficulty;
                                    $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += $detail->difficulty;
                                    if(isset($analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy])){
                                        $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy]['total_correct'] += $detail->difficulty;
                                    }
	                            }
                            } else {
	                                $analysis_structure[$question_strand_name]['total_correct'] += $userScore;
	                                $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += $userScore;
                                    $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += $userScore;
                                    
                                    if(isset($analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy])){
                                        $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy]['total_correct'] += $userScore;
                                    }
	                            
                            }   
                        }
                    }
        
                } else {
                    $requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheet_id);
                    $postData = array(
                        'gen_tutor' => $requirement->me_tutor,
                        'gen_me'    => $requirement->me_num,
                        'gen_year'  => $requirement->me_year,
                        'gen_level' => $requirement->me_level,
                        'gen_randomize' => ''
                    );
                    $questionsArray = $this->model_question->get_mock_exam_question_list($postData);

                    foreach ($attempt_ids as $attempt_id) {
                        $userAnswers = $this->model_quiz->get_attempt_answer($attempt_id);
                        $userScore = $this->model_quiz->get_attempt_score_array($attempt_id);
                        $question_number = 1;
                        foreach ($questionsArray as $question) {
                            foreach ($question as $subquestion) {
                                $detail = $this->model_question->get_question_from_id($subquestion->question_id);
                                $question_category = $detail->topic_id;
                                $question_category_name = $category_structure[$question_category]['category_name'];
                                $question_substrand_name = $category_structure[$question_category]['substrand'];
                                $question_strand_name = $category_structure[$question_category]['strand'];

                                $analysis_structure[$question_strand_name]['total_attempt'] += $detail->difficulty;
                                $analysis_structure[$question_strand_name][$question_substrand_name]['total_attempt'] += $detail->difficulty;
                                $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_attempt'] += $detail->difficulty;
                                if ($detail->question_type_id == 1) {  // mcq
                                    $correctAnswer = $this->model_question->get_correct_answer_from_question_id($subquestion->question_id);
                                    $userAnswer = (isset($userAnswers[$question_number - 1])) ? $userAnswers[$question_number - 1] : -1;
                                    if ($userAnswer == $correctAnswer) {
                                        $analysis_structure[$question_strand_name]['total_correct'] += $detail->difficulty;
                                        $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += $detail->difficulty;
                                        $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += $detail->difficulty;
                                        
                                    }
                                } else if ($detail->question_type_id == 2) {  // open ended, we check if the score is equal to full score
                                    $analysis_structure[$question_strand_name]['total_correct'] += ($userScore[$question_number - 1] == -1)?0:$userScore[$question_number - 1];
                                    $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += ($userScore[$question_number - 1] == -1)?0:$userScore[$question_number - 1];
                                    $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += ($userScore[$question_number - 1] == -1)?0:$userScore[$question_number - 1];
                                }                          
                                $question_number++;
                            }
                        }
    
                    }
                }
            }
            
            foreach ($strand_structure as $strand) {
                $strand_name = $strand['name'];
                if ($analysis_structure[$strand_name]['total_attempt'] != 0) {
                    $percentage = round($analysis_structure[$strand_name]['total_correct'] / $analysis_structure[$strand_name]['total_attempt'], 2) * 100;
                    $analysis_structure[$strand_name]['percentage'] = $percentage;
                    $analysis_structure[$strand_name]['progress_bar_type'] = $this->get_progress_bar_type($percentage);
                } else {
                    $analysis_structure[$strand_name]['percentage'] = 0;
                    $analysis_structure[$strand_name]['progress_bar_type'] = 'progress-bar-danger';
                }

                foreach ($strand['substrand'] as $substrand) {
                    $substrand_name = $substrand['name'];
                    if ($analysis_structure[$strand_name][$substrand_name]['total_attempt'] != 0) {
                        $substrand_percentage = round($analysis_structure[$strand_name][$substrand_name]['total_correct'] / $analysis_structure[$strand_name][$substrand_name]['total_attempt'], 2) * 100;
                        $analysis_structure[$strand_name][$substrand_name]['percentage'] = $substrand_percentage;
                        $analysis_structure[$strand_name][$substrand_name]['progress_bar_type'] = $this->get_progress_bar_type($substrand_percentage);
                    } else {
                        $analysis_structure[$strand_name][$substrand_name]['percentage'] = 0;
                        $analysis_structure[$strand_name][$substrand_name]['progress_bar_type'] = 'progress-bar-danger';
                    }

                    foreach ($substrand['category'] as $category) {
                        $category_name = $category['name'];
                        if ($analysis_structure[$strand_name][$substrand_name][$category_name]['total_attempt'] != 0) {
                            $category_percentage = round($analysis_structure[$strand_name][$substrand_name][$category_name]['total_correct'] / $analysis_structure[$strand_name][$substrand_name][$category_name]['total_attempt'], 2) * 100;
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['percentage'] = $category_percentage;
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['progress_bar_type'] = $this->get_progress_bar_type($category_percentage);
                        } else {
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['percentage'] = 0;
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['progress_bar_type'] = 'progress-bar-danger';
                        }
                        
                        foreach($category['heuristic'] as $heuristic){
                            $strategy_id = $heuristic['strategy_id'];
                            if ($analysis_structure[$strand_name][$substrand_name][$category_name]['heuristic'][$strategy_id]['total_attempt'] != 0) {
                                $strategy_percentage = round($analysis_structure[$strand_name][$substrand_name][$category_name]['heuristic'][$strategy_id]['total_correct'] / $analysis_structure[$strand_name][$substrand_name][$category_name]['heuristic'][$strategy_id]['total_attempt'], 2) * 100;
                                $analysis_structure[$strand_name][$substrand_name][$category_name]['heuristic'][$strategy_id]['percentage'] = $strategy_percentage;
                                $analysis_structure[$strand_name][$substrand_name][$category_name]['heuristic'][$strategy_id]['progress_bar_type'] = $this->get_progress_bar_type($strategy_percentage);
                            } else {
                                $analysis_structure[$strand_name][$substrand_name][$category_name]['heuristic'][$strategy_id]['percentage'] = 0;
                                $analysis_structure[$strand_name][$substrand_name][$category_name]['heuristic'][$strategy_id]['progress_bar_type'] = 'progress-bar-danger';
                            }
                        }

                    }

                }

            }
            
            $student_performance[$student_id] = $analysis_structure;
        }

        return $student_performance;
    }

    public function get_heuristic_performance($studentIdArray)
    {
        $userId = $this->session->userdata('user_id');

        $strategy_structure = $this->get_strategy_structure();

        $student_performance = array();

        foreach ($studentIdArray as $student_id) 
        {
            echo 'Hello';
        }
        
    }

    public function get_ep_performance($studentIdArray, $level_code = NULL, $date_range = array())
    {
        $userId = $this->session->userdata('user_id');
        $subject = 5;
        $category_structure = $this->get_topic_structure('junior');
        $analysis_structure = $this->get_ep_analysis_structure();
        $student_performance = array();
        
        foreach ($studentIdArray as $student_id) {
            $analysis_structure = $this->get_ep_analysis_structure($level_code);
            $analysis_structure['student_id'] = $student_id;
            $quizzes = $this->model_users->get_quiz_list_student($student_id);
            $userLevel = $this->get_level_tid_by_student_id($student_id);
            $level_code = ($userLevel) ? $userLevel->level_code : 'junior';
            $category_structure = $this->get_topic_structure();
            foreach ($quizzes as $quiz) {
                $attempt_ids = $this->model_quiz->get_attempt_ids($quiz->id, $date_range);
                
                $worksheet_id = $this->model_worksheet->get_worksheet_id_from_quiz_id($quiz->id);

                $isMockExam = $this->model_worksheet->is_mock_exam($worksheet_id);
                if(!$isMockExam) {
                    $questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheet_id);
                    
                    $questionsArray = $questions->result();
                    
                    $requirement = $this->model_worksheet->get_requirement_from_worksheetId($worksheet_id);
                    
                    foreach ($attempt_ids as $attempt_id) {
                        $userAnswers = $this->model_quiz->get_attempt_answer($attempt_id);

                        $userScores = $this->model_quiz->get_attempt_score_array($attempt_id);
   
                        foreach ($questionsArray as $question) {
                            
                            $detail = $this->model_question->get_question_from_id($question->question_id);
                            
                            if(isset($detail->topic_id)) {
                                $question_category = $detail->tid_topic_id;

                                $question_strategy = $detail->strategy_id;

                                if(isset($category_structure[$question_category]['category_name'])) {
                                
                                    // $question_category_name = $category_structure[$question_category]['topic_name'];

                                    // $question_substrand_name = $category_structure[$question_category]['substrand'];

                                    $question_strand_name = $category_structure[$question_category]['category_name'];

                                    $correctAnswer = $this->model_question->get_correct_answer_from_question_id($question->question_id);
                                    
                                    $correctAnswerText = $this->model_question->get_correct_answer_text_from_correct_id($correctAnswer);
                                    
                                    $userAnswer = (isset($userAnswers[$question->question_number - 1])) ? $userAnswers[$question->question_number - 1] : -1;
                                    
                                    $userScore = (isset($userScores[$question->question_number - 1])) ? $userScores[$question->question_number - 1] : 0;
                                    
                                    $analysis_structure[$question_strand_name]['total_attempt'] += $detail->difficulty;
                                    // array_debug($analysis_structure[$question_strand_name]['total_attempt']);
                                    // $analysis_structure[$question_strand_name][$question_substrand_name]['total_attempt'] += $detail->difficulty;
                                    
                                    // $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_attempt'] += $detail->difficulty;

                                    // if(isset($analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy])){

                                    //     $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['heuristic'][$question_strategy]['total_attempt'] += $detail->difficulty;

                                    // }

                                    if($requirement->question_type === '1'){
                                        if ($userAnswer == $correctAnswer) {
                                            $analysis_structure[$question_strand_name]['total_correct'] += $detail->difficulty;
                                            // $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += $detail->difficulty;
                                            // $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += $detail->difficulty;
                                        }
                                    } else {
                                            $analysis_structure[$question_strand_name]['total_correct'] += $userScore;
                                            // $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += $userScore;
                                            // $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += $userScore;
                                        
                                    }
                                }
                            }   
                        }
                    }
                }
            }
            
            foreach ($category_structure as $category) {
                $category_name = $category['category_name'];
                if ($analysis_structure[$category_name]['total_attempt'] != 0) {
                    $category_percentage = round($analysis_structure[$category_name]['total_correct'] / $analysis_structure[$category_name]['total_attempt'], 2) * 100;
                    $analysis_structure[$category_name]['percentage'] = $category_percentage;
                    $analysis_structure[$category_name]['progress_bar_type'] = $this->get_progress_bar_type($category_percentage);
                } else {
                    $analysis_structure[$category_name]['percentage'] = 0;
                    $analysis_structure[$category_name]['progress_bar_type'] = 'progress-bar-danger';
                }

            }
            
            $student_performance[$student_id] = $analysis_structure;
        }

        return $student_performance;
    }

    public function get_ep_performance_ability($studentIdArray, $date_range = array())
    {
        $userId = $this->session->userdata('user_id');
        $subject = 5;
        $category_structure = $this->get_ability_structure();
        $analysis_structure = $this->get_ep_analysis_structure_ability();
        $student_performance = array();
        
        foreach ($studentIdArray as $student_id) {
            $analysis_structure['student_id'] = $student_id;
            $quizzes = $this->model_users->get_quiz_list_student($student_id);
            $userLevel = $this->get_level_tid_by_student_id($student_id);
            $level_code = ($userLevel) ? $userLevel->level_code : 'junior';
            // $category_structure = $this->get_ability_structure();
            foreach ($quizzes as $quiz) {
                $attempt_ids = $this->model_quiz->get_attempt_ids($quiz->id, $date_range);
                
                $worksheet_id = $this->model_worksheet->get_worksheet_id_from_quiz_id($quiz->id);

                $isMockExam = $this->model_worksheet->is_mock_exam($worksheet_id);
                if(!$isMockExam) {
                    $questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheet_id);
                    
                    $questionsArray = $questions->result();
                    
                    $requirement = $this->model_worksheet->get_requirement_from_worksheetId($worksheet_id);
                    
                    foreach ($attempt_ids as $attempt_id) {
                        $userAnswers = $this->model_quiz->get_attempt_answer($attempt_id);

                        $userScores = $this->model_quiz->get_attempt_score_array($attempt_id);
   
                        foreach ($questionsArray as $question) {
                            
                            $detail = $this->model_question->get_question_from_id($question->question_id);
                            
                            if(isset($detail->topic_id)) {
                                $question_category = $detail->ability;

                                $question_strategy = $detail->strategy_id;

                                if(isset($category_structure[$question_category]['category_name'])) {
                                
                                    $question_strand_name = $category_structure[$question_category]['category_name'];

                                    $correctAnswer = $this->model_question->get_correct_answer_from_question_id($question->question_id);
                                    
                                    $correctAnswerText = $this->model_question->get_correct_answer_text_from_correct_id($correctAnswer);
                                    
                                    $userAnswer = (isset($userAnswers[$question->question_number - 1])) ? $userAnswers[$question->question_number - 1] : -1;
                                    
                                    $userScore = (isset($userScores[$question->question_number - 1])) ? $userScores[$question->question_number - 1] : 0;
                                    
                                    $analysis_structure[$question_strand_name]['total_attempt'] += $detail->difficulty;

                                    if($requirement->question_type === '1'){
                                        if ($userAnswer == $correctAnswer) {
                                            $analysis_structure[$question_strand_name]['total_correct'] += $detail->difficulty;
                                        }
                                    } else {
                                            $analysis_structure[$question_strand_name]['total_correct'] += $userScore;
                                        
                                    }
                                }
                            }   
                        }
                    }
                }
            }
            
            foreach ($category_structure as $category) {
                $category_name = $category['category_name'];
                if ($analysis_structure[$category_name]['total_attempt'] != 0) {
                    $category_percentage = round($analysis_structure[$category_name]['total_correct'] / $analysis_structure[$category_name]['total_attempt'], 2) * 100;
                    $analysis_structure[$category_name]['percentage'] = $category_percentage;
                    $analysis_structure[$category_name]['progress_bar_type'] = $this->get_progress_bar_type($category_percentage);
                } else {
                    $analysis_structure[$category_name]['percentage'] = 0;
                    $analysis_structure[$category_name]['progress_bar_type'] = 'progress-bar-danger';
                }

            }
            
            $student_performance[$student_id] = $analysis_structure;
        }

        return $student_performance;
    }

/*  For secondary reporting system   */


	public function get_sc_performance($studentIdArray)
    {
        $category_structure = $this->get_sc_category_structure();
        $analysis_structure = $this->get_sc_analysis_structure();
        $strand_structure = $this->model_question->get_sc_strand_structure();
        $student_performance = array();
        foreach ($studentIdArray as $student_id) {
            $analysis_structure = $this->get_sc_analysis_structure();
            $analysis_structure['student_id'] = $student_id;
            $quizzes = $this->model_users->get_quiz_list($student_id);
            foreach ($quizzes as $quiz) {
                $attempt_ids = $this->model_quiz->get_attempt_ids($quiz->id);
                $worksheet_id = $this->model_worksheet->get_worksheet_id_from_quiz_id($quiz->id);
                $isMockExam = $this->model_worksheet->is_mock_exam($worksheet_id);
                if(!$isMockExam) {
                    $questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheet_id);
                    $questionsArray = $questions->result();
                    foreach ($attempt_ids as $attempt_id) {
                        $userAnswers = $this->model_quiz->get_attempt_answer($attempt_id);
                        foreach ($questionsArray as $question) {
                            $detail = $this->model_question->get_question_from_id($question->question_id);
                            $question_category = $detail->topic_id;
                            $question_category_name = $category_structure[$question_category]['category_name'];
                            $question_substrand_name = $category_structure[$question_category]['substrand'];
                            $question_strand_name = $category_structure[$question_category]['strand'];
                            $correctAnswer = $this->model_question->get_correct_answer_from_question_id($question->question_id);
                            $userAnswer = (isset($userAnswers[$question->question_number - 1])) ? $userAnswers[$question->question_number - 1] : -1;
                            $analysis_structure[$question_strand_name]['total_attempt'] += $detail->difficulty;
                            $analysis_structure[$question_strand_name][$question_substrand_name]['total_attempt'] += $detail->difficulty;
                            $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_attempt'] += $detail->difficulty;
                            if ($userAnswer == $correctAnswer) {
                                $analysis_structure[$question_strand_name]['total_correct'] += $detail->difficulty;
                                $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += $detail->difficulty;
                                $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += $detail->difficulty;
                            }
                        }
                    }
                } else {
                    $requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheet_id);
                    $postData = array(
                        'gen_tutor' => $requirement->me_tutor,
                        'gen_me'    => $requirement->me_num,
                        'gen_year'  => $requirement->me_year,
                        'gen_level' => $requirement->me_level,
                        'gen_randomize' => ''
                    );
                    $questionsArray = $this->model_question->get_mock_exam_question_list($postData);
                    foreach ($attempt_ids as $attempt_id) {
                        $userAnswers = $this->model_quiz->get_attempt_answer($attempt_id);
                        $userScore = $this->model_quiz->get_attempt_score_array($attempt_id);
                        $question_number = 1;
                        foreach ($questionsArray as $question) {
                            foreach ($question as $subquestion) {
                                $detail = $this->model_question->get_question_from_id($subquestion->question_id);
                                $question_category = $detail->topic_id;
                                $question_category_name = $category_structure[$question_category]['category_name'];
                                $question_substrand_name = $category_structure[$question_category]['substrand'];
                                $question_strand_name = $category_structure[$question_category]['strand'];
                                $analysis_structure[$question_strand_name]['total_attempt'] += $detail->difficulty;
                                $analysis_structure[$question_strand_name][$question_substrand_name]['total_attempt'] += $detail->difficulty;
                                $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_attempt'] += $detail->difficulty;
                                if ($detail->question_type_id == 1) {  // mcq
                                    $correctAnswer = $this->model_question->get_correct_answer_from_question_id($subquestion->question_id);
                                    $userAnswer = (isset($userAnswers[$question_number - 1])) ? $userAnswers[$question_number - 1] : -1;
                                    if ($userAnswer == $correctAnswer) {
                                        $analysis_structure[$question_strand_name]['total_correct'] += $detail->difficulty;
                                        $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += $detail->difficulty;
                                        $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += $detail->difficulty;
                                    }
                                } else if ($detail->question_type_id == 2) {  // open ended, we check if the score is equal to full score
                                    $analysis_structure[$question_strand_name]['total_correct'] += ($userScore[$question_number - 1] == -1)?0:$userScore[$question_number - 1];
                                    $analysis_structure[$question_strand_name][$question_substrand_name]['total_correct'] += ($userScore[$question_number - 1] == -1)?0:$userScore[$question_number - 1];
                                    $analysis_structure[$question_strand_name][$question_substrand_name][$question_category_name]['total_correct'] += ($userScore[$question_number - 1] == -1)?0:$userScore[$question_number - 1];
                                }                          
                                $question_number++;
                            }
                        }
                    }
                }   
            }
                
            foreach ($strand_structure as $strand) {
                $strand_name = $strand['name'];
                $rand_attempt = rand(50,100);
                $rand_correct = rand(1,50);
                $analysis_structure[$strand_name]['total_attempt'] = $rand_attempt;
                $analysis_structure[$strand_name]['total_correct'] = $rand_correct;
                if ($analysis_structure[$strand_name]['total_attempt'] != 0) {
                    $percentage = round($analysis_structure[$strand_name]['total_correct'] / $analysis_structure[$strand_name]['total_attempt'], 2) * 100;
                    $analysis_structure[$strand_name]['percentage'] = $percentage;
                    $analysis_structure[$strand_name]['progress_bar_type'] = $this->get_progress_bar_type($percentage);
                } else {
                    $analysis_structure[$strand_name]['percentage'] = 0;
                    $analysis_structure[$strand_name]['progress_bar_type'] = '';
                }
                foreach ($strand['substrand'] as $substrand) {
                    $substrand_name = $substrand['name'];
                    $rand_attempt = rand(50,100);
                    $rand_correct = rand(1,50);
                    $analysis_structure[$strand_name][$substrand_name]['total_attempt'] = $rand_attempt;
                    $analysis_structure[$strand_name][$substrand_name]['total_correct'] = $rand_correct;
                    if ($analysis_structure[$strand_name][$substrand_name]['total_attempt'] != 0) {
                        $substrand_percentage = round($analysis_structure[$strand_name][$substrand_name]['total_correct'] / $analysis_structure[$strand_name][$substrand_name]['total_attempt'], 2) * 100;
                        $analysis_structure[$strand_name][$substrand_name]['percentage'] = $substrand_percentage;
                        $analysis_structure[$strand_name][$substrand_name]['progress_bar_type'] = $this->get_progress_bar_type($substrand_percentage);
                    } else {
                        $analysis_structure[$strand_name][$substrand_name]['percentage'] = 0;
                        $analysis_structure[$strand_name][$substrand_name]['progress_bar_type'] = '';
                    }
                    foreach ($substrand['category'] as $category) {
                        $category_name = $category['name'];
                        $analysis_structure[$strand_name][$substrand_name][$category_name]['total_attempt'] = $rand_attempt;
                        $analysis_structure[$strand_name][$substrand_name][$category_name]['total_correct'] = $rand_correct;
                        if ($analysis_structure[$strand_name][$substrand_name][$category_name]['total_correct'] != 0) {
                            $category_percentage = round($analysis_structure[$strand_name][$substrand_name][$category_name]['total_correct'] / $analysis_structure[$strand_name][$substrand_name][$category_name]['total_attempt'], 2) * 100;
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['percentage'] = $category_percentage;
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['progress_bar_type'] = $this->get_progress_bar_type($category_percentage);
                        } else {
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['percentage'] = 0;
                            $analysis_structure[$strand_name][$substrand_name][$category_name]['progress_bar_type'] = '';
                        }
                    }
                }
            }
            $student_performance[$student_id] = $analysis_structure;
        }
        return $student_performance;
    }
    
    
    private function get_sc_analysis_structure()
    {
        $strands = array();
        $strand_sql = "SELECT * FROM sj_strands WHERE `type` = 'Secondary' ORDER BY `id` ASC";
        $strand_sql_query = $this->db->query($strand_sql);
        foreach ($strand_sql_query->result() as $row) {
            $strand_object = array();
            $strand_name = $row->name;
            $strand_id = $row->id;
            $substrand_sql = "SELECT `id`, `name` FROM `sj_substrands` WHERE `strand_id` = ? AND `type` = 'Secondary'";
            $substrand_sql_query = $this->db->query($substrand_sql, array($strand_id));
            foreach ($substrand_sql_query->result() as $substrand_row) {
                $substrand_object = array();
                $substrand_name = $substrand_row->name;
                $substrand_id = $substrand_row->id;
                $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `substrand_id` = ? AND `type` = 'Secondary'";
                $categories_sql_query = $this->db->query($categories_sql, array($substrand_id));
                foreach ($categories_sql_query->result() as $category_row) {
                    $category_object = array();
                    $category_name = $category_row->name;
                    $category_id = $category_row->id;
                    $category_object['category_id'] = $category_id;
                    $category_object['total_attempt'] = 0;
                    $category_object['total_correct'] = 0;
                    $substrand_object[$category_name] = $category_object;
                }
                $substrand_object['substrand_id'] = $substrand_id;
                $substrand_object['total_attempt'] = 0;
                $substrand_object['total_correct'] = 0;
                $strand_object[$substrand_name] = $substrand_object;
            }
            $strand_object['strand_id'] = $strand_id;
            $strand_object['total_attempt'] = 0;
            $strand_object['total_correct'] = 0;
            $strands[$strand_name] = $strand_object;
        }
        return $strands;
    }

    private function get_sc_category_structure()
    {
        $sql = "SELECT a.name AS strand_name, substrand_name, category_name, category_id 
				FROM `sj_strands` a 
				JOIN 
			    	(select b.name AS substrand_name, c.name AS category_name, c.id as category_id, b.strand_id 
			    	from `sj_substrands` b 
			    	JOIN `sj_categories` c 
			    	WHERE b.id = c.substrand_id) x 
				where a.id = x.strand_id AND `type` = 'Secondary'";
        $sql_query = $this->db->query($sql);
        $structure = array();
        foreach ($sql_query->result() as $row) {
            $columns = array();
            $columns['strand'] = $row->strand_name;
            $columns['substrand'] = $row->substrand_name;
            $columns['category_name'] = $row->category_name;
            $structure[$row->category_id] = $columns;
        }
        return $structure;
    }
    
    
    /*  For secondary reporting system   */


    // reformat the performance structure just for returning to API
    public function get_performance_api($user_id)
    {
        $student_performance = $this->get_performance(array($user_id))[$user_id];
        unset($student_performance['student_id']);
        $api_student_performance = array();
        $strand_structure = $this->model_question->get_strand_structure();


        foreach ($strand_structure as $strand) {
            $strand_name = $strand['name'];
            $strand_array = array();
            $strand_array['title'] = $strand_name;
            $strand_array['strand_id'] = $student_performance[$strand_name]['strand_id'];
            $strand_array['total_attempt'] = $student_performance[$strand_name]['total_attempt'];
            $strand_array['total_correct'] = $student_performance[$strand_name]['total_correct'];
            $strand_array['percentage'] = $student_performance[$strand_name]['percentage'];
            $strand_array['progress_bar_type'] = $student_performance[$strand_name]['progress_bar_type'];

            $strand_array['substrand'] = array();

            //substrand
            foreach ($strand['substrand'] as $substrand) {
                $substrand_array = array();
                $substrand_name = $substrand['name'];
                $substrand_array['title'] = $substrand_name;
                $substrand_array['substrand_id'] = $student_performance[$strand_name][$substrand_name]['substrand_id'];
                $substrand_array['total_attempt'] = $student_performance[$strand_name][$substrand_name]['total_attempt'];
                $substrand_array['total_correct'] = $student_performance[$strand_name][$substrand_name]['total_correct'];
                $substrand_array['percentage'] = $student_performance[$strand_name][$substrand_name]['percentage'];
                $substrand_array['progress_bar_type'] = $student_performance[$strand_name][$substrand_name]['progress_bar_type'];


                //categories
                $substrand_array['category'] = array();

                foreach ($substrand['category'] as $category) {
                    $category_array = array();
                    $category_name = $category['name'];
                    $category_array['title'] = $category_name;
                    $category_array['category_id'] = $student_performance[$strand_name][$substrand_name][$category_name]['category_id'];
                    $category_array['total_attempt'] = $student_performance[$strand_name][$substrand_name][$category_name]['total_attempt'];
                    $category_array['total_correct'] = $student_performance[$strand_name][$substrand_name][$category_name]['total_correct'];
                    $category_array['percentage'] = $student_performance[$strand_name][$substrand_name][$category_name]['percentage'];
                    $category_array['progress_bar_type'] = $student_performance[$strand_name][$substrand_name][$category_name]['progress_bar_type'];

                    $substrand_array['category'][] = $category_array;
                }

                $strand_array['substrand'][] = $substrand_array;
            }

            $api_student_performance[] = $strand_array;

        }
        return $api_student_performance;


    }

    private function get_progress_bar_type($percentage)
    {
        if ($percentage <= 30) {
            return 'progress-bar-danger';
        } elseif ($percentage >= 30 && $percentage < 70) {
            return 'progress-bar-warning';
        } elseif ($percentage >= 70) {
            return 'progress-bar-success';
        }


    }

    private function get_analysis_structure($subject = NULL)
    {
        $strands = array();
        if(isset($subject) && empty($subject) == FALSE) {
            $strand_sql = "SELECT * FROM sj_strands WHERE `subject_type` = " . $this->db->escape($subject) . " ORDER BY `id` ASC";
        } else {
            $strand_sql = "SELECT * FROM sj_strands ORDER BY `id` ASC";
        }
        $strand_sql_query = $this->db->query($strand_sql);
        
        foreach ($strand_sql_query->result() as $row) {
            $strand_object = array();
            $strand_name = $row->name;
            $strand_id = $row->id;

            
            if(isset($subject) && empty($subject) == FALSE) {
                $substrand_sql = "SELECT `id`, `name` FROM `sj_substrands` WHERE `subject_type` = " . $this->db->escape($subject) . " AND `strand_id` =" . $this->db->escape($strand_id);
            } else {
                $substrand_sql = "SELECT `id`, `name` FROM `sj_substrands` WHERE `strand_id` =" . $this->db->escape($strand_id);
            }
            $substrand_sql_query = $this->db->query($substrand_sql);

            foreach ($substrand_sql_query->result() as $substrand_row) {
                $substrand_object = array();
                $substrand_name = $substrand_row->name;
                $substrand_id = $substrand_row->id;

                if(isset($subject) && empty($subject) == FALSE) {
                    if($subject == 5) {
                        $categories_sql = "SELECT `id`, `name` FROM `sj_sec_categories` WHERE `subject_type` = " . $this->db->escape($subject) . " AND `substrand_id` = ?";                        
                    } else {
                        $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `subject_type` = " . $this->db->escape($subject) . " AND `substrand_id` = ?";
                    }
                } else {
                    $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `substrand_id` = ?";
                }
                
                $categories_sql_query = $this->db->query($categories_sql, array($substrand_id));

                foreach ($categories_sql_query->result() as $category_row) {
                    $category_object = array();
                    $category_name = $category_row->name;
                    $category_id = $category_row->id;

                    $sql="
                        SELECT DISTINCT hr.strategy_id, hr.topic_id, str.name FROM `sj_heuristic_relationship` hr
                        JOIN 
                        (SELECT `id`,`name` FROM `sj_strategy`) str
                        WHERE hr.strategy_id = str.id
                        AND hr.topic_id = " . $this->db->escape($category_id) . "
                        ORDER BY hr.strategy_id ASC
                    ";

                    $query = $this->db->query($sql);

                    $heuristic = array();
                    $heu = array();
                    foreach($query->result() as $value){
                        $heu['strategy_id'] = $value->strategy_id;
                        $heu['strategy_name'] = $value->name;
                        $heu['total_attempt'] = 0;
                        $heu['total_correct'] = 0;
                        $heuristic[$value->strategy_id] = $heu;
                        
                    }

                    $category_object['category_id'] = $category_id;
                    $category_object['total_attempt'] = 0;
                    $category_object['total_correct'] = 0;
                    $category_object['heuristic'] = $heuristic;
                    $substrand_object[$category_name] = $category_object;
                }

                $substrand_object['substrand_id'] = $substrand_id;
                $substrand_object['total_attempt'] = 0;
                $substrand_object['total_correct'] = 0;
                $strand_object[$substrand_name] = $substrand_object;
            }

            $strand_object['strand_id'] = $strand_id;
            $strand_object['total_attempt'] = 0;
            $strand_object['total_correct'] = 0;
            $strands[$strand_name] = $strand_object;
        }

        return $strands;

    }


    private function get_ep_analysis_structure($subject = NULL)
    {
        $ep_tid_array = array();

        $sql = "SELECT * FROM `sj_topics_tid` WHERE branch_id=".BRANCH_ID;

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $ep_tid_object = array();

            $ep_tid_name = $row->topic_name;

            $ep_tid_object['topic_id'] = $row->topic_id; 

            $ep_tid_object['total_attempt'] = 0;

            $ep_tid_object['total_correct'] = 0;

            $ep_tid_array[$ep_tid_name] = $ep_tid_object;
        }

        return $ep_tid_array;

    }

    private function get_ep_analysis_structure_ability($subject = NULL)
    {
        $ep_tid_array = array();

        $sql = "SELECT * FROM `sj_ability_tid` WHERE branch_id=".BRANCH_ID;

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $ep_tid_object = array();

            $ep_tid_name = $row->ability_name;

            $ep_tid_object['ability_id'] = $row->ability_id; 

            $ep_tid_object['total_attempt'] = 0;

            $ep_tid_object['total_correct'] = 0;

            $ep_tid_array[$ep_tid_name] = $ep_tid_object;
        }

        return $ep_tid_array;

    }

    private function get_category_structure($subject = NULL)
    {
        if(isset($subject) && empty($subject) == FALSE){

            $sql = "SELECT a.name AS strand_name, substrand_name, category_name, category_id 
                        FROM `sj_strands` a 
                        JOIN 
                            (select b.name AS substrand_name, c.name AS category_name, c.id as category_id, b.strand_id 
                            from `sj_substrands` b 
                            JOIN `sj_categories` c 
                            WHERE b.id = c.substrand_id AND b.`subject_type` = " . $this->db->escape($subject) . " AND c.`subject_type` = " . $this->db->escape($subject) . ") x 
                        where a.id = x.strand_id AND a.`subject_type` = " . $this->db->escape($subject);
        } else {
            $sql = "SELECT a.name AS strand_name, substrand_name, category_name, category_id 
                    FROM `sj_strands` a 
                    JOIN 
                        (select b.name AS substrand_name, c.name AS category_name, c.id as category_id, b.strand_id 
                        from `sj_substrands` b 
                        JOIN `sj_categories` c 
                        WHERE b.id = c.substrand_id) x 
                    where a.id = x.strand_id";
        }

        $sql_query = $this->db->query($sql);

        $structure = array();

        foreach ($sql_query->result() as $row) {
            
            $columns = array();

            $columns['strand'] = $row->strand_name;
            $columns['substrand'] = $row->substrand_name;
            $columns['category_name'] = $row->category_name;

            $structure[$row->category_id] = $columns;

        }
        return $structure;
    }

    private function get_strategy_structure($subject = NULL) 
    {
        if(isset($subject) && empty($subject) == FALSE){
            $sql = "SELECT * FROM `sj_categories` WHERE `subject_type` = " . $this->db->escape($subject) . " ORDER BY `id` ASC";
        } else {
            $sql = "SELECT * FROM `sj_categories` ORDER BY `id` ASC";
        }

        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $cat_str = array();

            foreach($query->result() as $row)
            {
                $cat_id = $row->id;
                
                $sql = "
                    SELECT DISTINCT hr.strategy_id, hr.topic_id, str.name FROM `sj_heuristic_relationship` hr
                    JOIN 
                    (SELECT `id`,`name` FROM `sj_strategy`) str
                    WHERE hr.strategy_id = str.id
                    AND hr.topic_id = " . $this->db->escape($cat_id) . "
                    ORDER BY hr.strategy_id ASC
                ";
    
                $query = $this->db->query($sql);

                if($query->num_rows() >0)
                {
                    $str = array();
                    foreach($query->result() as $value)
                    {
                        $str[$value->strategy_id] = $value->name;
                    }
                    $cat_str[$value->topic_id] = $str;
                }   
            }
        }
        return $cat_str;
    }

    private function get_topic_structure($level = NULL)
    {
        if(isset($level) && empty($level) == false) {

            $sql = "SELECT *
                FROM `sj_topics_tid` where level='".$level."' ";

        } else {

            $sql = "SELECT * FROM `sj_topics_tid`";

        }
        
        $sql_query = $this->db->query($sql);
        $structure = array();
        
        foreach ($sql_query->result() as $row) {
            $columns = array();
            $columns['category_name'] = $row->topic_name;
            $structure[$row->topic_id] = $columns;
        }

        return $structure;
    }

    private function get_ability_structure()
    {
        $sql = "SELECT * FROM `sj_ability_tid` WHERE branch_id=".BRANCH_ID;
        
        $sql_query = $this->db->query($sql);
        $structure = array();
        
        foreach ($sql_query->result() as $row) {
            $columns = array();
            $columns['category_name'] = $row->ability_name;
            $structure[$row->ability_id] = $columns;
        }

        return $structure;
    }

    public function get_profession()
    {
        $sql = "SELECT `name` FROM `sj_profession`";
        $sql_query = $this->db->query($sql);

        $profession = array();
        foreach ($sql_query->result() as $row) {
            $profession[] = $row->name;
        }

        return $profession;
    }

    public function get_profession_name_from_id($profession_id)
    {
        if ($profession_id == '' || $profession_id == 0) {
            return '';
        } else {
            $sql = "SELECT `name` FROM `sj_profession` WHERE `id` = ?";
            $sql_query = $this->db->query($sql, array($profession_id));

            return $sql_query->row()->name;
        }
    }

    public function get_specialization_name_from_id($subject_id)
    {
        if ($subject_id == '') {
            return '';
        } else {
            $sql = "SELECT `subject` FROM `sj_specialization_subject` WHERE `id` = ?";
            $sql_query = $this->db->query($sql, array($subject_id));

            return $sql_query->row()->subject;
        }
    }

    public function get_specialization_list()
    {
        $sql = "SELECT `subject` FROM `sj_specialization_subject`";
        $sql_query = $this->db->query($sql);

        $subject = array();
        foreach ($sql_query->result() as $row) {
            $subject[] = $row->subject;
        }

        return $subject;
    }

    public function get_tutor_specialization($user_id)
    {
        $sql = "SELECT `subject_id` FROM `sj_user_specialization_mapping` WHERE `tutor_id` = ?";
        $query = $this->db->query($sql, array($user_id));

        $tutor_specializatiion = array();
        foreach ($query->result() as $row) {
            $tutor_specializatiion[] = $row->subject_id;
        }

        return $tutor_specializatiion;
    }

    public function get_comments_from_user_id($user_id, $start = 0, $limit = 10)
    {
        // return 10 comments per call
        $sql = "SELECT `question_id`, `comment`, `comment_date` FROM `sj_askjen_comments` WHERE `user_id` = ? GROUP BY `question_id` ORDER BY `comment_date` DESC LIMIT ?, ?";
        $query = $this->db->query($sql, array($user_id, $start, $limit));

        $comments = array();
        foreach ($query->result() as $row) {
            $comment_array = array();
            $comment_array['question_id'] = $row->question_id;
            $comment_array['comment'] = $row->comment;
            $comment_array['comment_date'] = $row->comment_date;
            $comments[] = $comment_array;
        }

        return $comments;
    }

    public function is_authorized_to_edit_student($student_id) {
        $tutor_id = $this->session->userdata('user_id');
        $sql = "SELECT count(1) AS count 
                FROM `sj_relationship`
                WHERE (`status` = 1 OR `status` = 2) AND `student_id` = ? AND `adult_id` = ?
               ";

        $query = $this->db->query($sql, array($student_id, $tutor_id));

        return ($query->row()->count == 1) ? true : false;
    }
    
    public function get_student_list_count_all($tutorId,$keywords){  
        $sql = "
	    SELECT COUNT(DISTINCT `user`.`id`,`user`.`username`, `user`.`fullname`, `user`.`profile_pic`, `relationship`.`student_id`, `school_level`.`school_name`, `school_level`.`level_name`) AS total
        FROM `sj_users` user 
        JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `adult_id`= '" . $tutorId . "' AND `status` = 1 AND `branch_tag`= '".BRANCH_TAG."') relationship 
        JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
        JOIN `sj_schools` 
        JOIN `sj_levels` 
        ON `sj_schools`.`school_id` = `sj_student`.`school_id` and `sj_levels`.`id` = `sj_student`.`level_id`) school_level 
        ON `relationship`.`student_id` = `user`.`id` AND `school_level`.`student_id` = `user`.`id`
        WHERE `user`.`id` NOT IN ('" . implode("','" , $keywords) . "')
        ";
        
        $query = $this->db->query($sql);
        
        return $query->row()->total;
        
    }
    
	public function get_student_list_fetch_details($tutorId,$keywords,$start = NULL,$limit = NULL){
        if($start == NULL && $limit == NULL) {
            $sql ="
                SELECT `user`.`id`, `user`.`username`, `user`.`fullname`, `user`.`profile_pic`, `relationship`.`student_id`, `school_level`.`school_name`, `school_level`.`level_name`
                FROM `sj_users` user 
                JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `adult_id`= '" . $tutorId . "' AND `status` = 1 AND `branch_tag`= '".BRANCH_TAG."') relationship
                JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
                JOIN `sj_schools` 
                JOIN `sj_levels` 
                ON `sj_schools`.`school_id` = `sj_student`.`school_id` and `sj_levels`.`id` = `sj_student`.`level_id`) school_level
                ON `relationship`.`student_id` = `user`.`id` AND `school_level`.`student_id` = `user`.`id` 
                WHERE  `user`.`id` NOT IN ('" . implode("','", $keywords) . "')
                ORDER BY `school_level`.`level_name` DESC, `user`.`username` ASC
            "; 
        } else {
            $sql ="
                SELECT `user`.`id`, `user`.`username`, `user`.`fullname`, `user`.`profile_pic`, `relationship`.`student_id`, `school_level`.`school_name`, `school_level`.`level_name`
                FROM `sj_users` user 
                JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `adult_id`= '" . $tutorId . "' AND `status` = 1 AND `branch_tag`= '".BRANCH_TAG."') relationship
                JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
                JOIN `sj_schools` 
                JOIN `sj_levels` 
                ON `sj_schools`.`school_id` = `sj_student`.`school_id` and `sj_levels`.`id` = `sj_student`.`level_id`) school_level
                ON `relationship`.`student_id` = `user`.`id` AND `school_level`.`student_id` = `user`.`id` 
                WHERE  `user`.`id` NOT IN ('" . implode("','", $keywords) . "')
                ORDER BY `school_level`.`level_name` DESC, `user`.`username` ASC
                LIMIT " . $start . " OFFSET " . $limit . "
            ";
        }

        $query = $this->db->query($sql);

        return $query->result_array();

	}
    
    public function get_children_list_count_all($parentId,$keywords){  
        $sql = "
	    SELECT COUNT(DISTINCT `user`.`id`,`user`.`username`, `user`.`fullname`, `user`.`profile_pic`, `relationship`.`student_id`, `school_level`.`school_name`, `school_level`.`level_name`) AS total
        FROM `sj_users` user 
        JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `adult_id`= '" . $parentId . "' AND `status` = 2 AND `branch_tag`= '".BRANCH_TAG."') relationship 
        JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
        JOIN `sj_schools` 
        JOIN `sj_levels` 
        ON `sj_schools`.`school_id` = `sj_student`.`school_id` and `sj_levels`.`level_id` = `sj_student`.`level_id`) school_level 
        ON `relationship`.`student_id` = `user`.`id` AND `school_level`.`student_id` = `user`.`id`
        WHERE `user`.`id` NOT IN ('" . implode("','" , $keywords) . "')
        ";
        
        $query = $this->db->query($sql);
        
        return $query->row()->total;
        
    }
    
	public function get_children_list_fetch_details($parentId,$keywords,$start,$limit){
		
	$sql ="
		SELECT `user`.`id`, `user`.`username`, `user`.`fullname`, `user`.`profile_pic`, `relationship`.`student_id`, `school_level`.`school_name`, `school_level`.`level_name`
		FROM `sj_users` user 
		JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `adult_id`= '" . $parentId . "' AND `status` = 2 AND `branch_tag`= '".BRANCH_TAG."') relationship
		JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
		JOIN `sj_schools` 
		JOIN `sj_levels` 
		ON `sj_schools`.`school_id` = `sj_student`.`school_id` and `sj_levels`.`level_id` = `sj_student`.`level_id`) school_level
		ON `relationship`.`student_id` = `user`.`id` AND `school_level`.`student_id` = `user`.`id` 
		WHERE  `user`.`id` NOT IN ('" . implode("','", $keywords) . "')
		ORDER BY `school_level`.`level_name` DESC, `user`.`username` ASC
		LIMIT " . $start . " OFFSET " . $limit . "
	";

	$query = $this->db->query($sql);

	return $query->result_array();


    }
    
    public function get_tutor_list_count_all($tutorId,$keywords){  
        $sql = "
	    SELECT COUNT(DISTINCT `id`,`username`, `fullname`, `profile_pic`) AS total
        FROM `sj_users`
        WHERE `branch_code` = '".BRANCH_ID."' AND `last_login` = 'tutor'
        AND `id` NOT IN ('" . implode("','" , $keywords) . "')
        ";
        
        $query = $this->db->query($sql);
        return $query->row()->total;
        
    }
    
	public function get_tutor_list_fetch_details($tutorId,$keywords,$start = NULL,$limit = NULL){
        if($start == NULL && $limit == NULL) {
            $sql ="
                SELECT `id`, `username`, `fullname`, `profile_pic`
                FROM `sj_users`
                WHERE `branch_code` = '".BRANCH_ID."' AND `last_login` = 'tutor'
                AND  `id` NOT IN ('" . implode("','", $keywords) . "')
                ORDER BY `username` ASC
            "; 
        } else {
            $sql ="
                SELECT `id`, `username`, `fullname`, `profile_pic`
                FROM `sj_users` 
                WHERE `branch_code` = '".BRANCH_ID."' AND `last_login` = 'tutor'
                AND  `id` NOT IN ('" . implode("','", $keywords) . "')
                ORDER BY `username` ASC
                LIMIT " . $start . " OFFSET " . $limit . "
            ";
        }

        $query = $this->db->query($sql);

        return $query->result_array();

	}
	
	public function check_not_tag_student($emailOrUsername)
	{
		$sql = "
			SELECT * FROM `sj_users` user
			
			WHERE EXISTS (SELECT `student_id` FROM `sj_relationship` WHERE `user`.`id` = `sj_relationship`.`student_id`)
			
			AND `branch_code` = ".BRANCH_ID."
			
			AND (`email` = '" . $emailOrUsername . "' OR `username`='".$emailOrUsername."')
		";
		$query = $this->db->query($sql);
		
		return ($query->num_rows() == 1) ? true : false;
	}
	
	
	public function check_tutor_exist_tag($emailOrUsername){
		$sql = "
			SELECT * FROM `sj_users` user
			WHERE EXISTS (SELECT * FROM `sj_user_role` WHERE `user`.`id` = `sj_user_role`.`user_id` AND `role_id` = 1)
 			AND (`email` = '" . $emailOrUsername . "' OR `username` = '".$emailOrUsername."')
		";
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }	
    
    public function check_parent_exist_tag($emailOrUsername){
		$sql = "
			SELECT * FROM `sj_users` user
			WHERE EXISTS (SELECT * FROM `sj_user_role` WHERE `user`.`id` = `sj_user_role`.`user_id` AND `role_id` = 3)
 			AND (`email` = '" . $emailOrUsername . "' OR `username` = '".$emailOrUsername."')
		";
        $query = $this->db->query($sql);
		return ($query->num_rows() == 1)? true : false;
	}
	
	public function check_valid_email ($email) {
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
	}
	
	public function check_student_exist_tag($emailOrUsername){
		$sql = "
			SELECT * FROM `sj_users` user
			WHERE EXISTS (SELECT * FROM `sj_relationship` WHERE `user`.`id` = `sj_relationship`.`student_id`) 
			AND (`email` = '" . $emailOrUsername . "' OR `username` = '".$emailOrUsername."')
		";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			$studentId = $query->row()->id;
		} else {
			return false;
		}
		$sql = "SELECT * FROM `sj_user_branch` WHERE `user_id` = '".$studentId."'";
		$query = $this->db->query($sql);
		$data = array();
		foreach($query->result() as $v){
			$data[] = $v;
		}
		$sql = "SELECT * FROM `sj_relationship` WHERE `student_id` = '".$studentId."' AND `branch_tag` = '".BRANCH_TAG."'";
		$query = $this->db->query($sql);
		
		return ($query->num_rows() >= 1)? true : false;
	}
	
	public function check_own_student_exist_tag($emailOrUsername, $userId){
		$sql = "
			SELECT * FROM `sj_users` user
			WHERE EXISTS (SELECT * FROM `sj_relationship` WHERE `user`.`id` = `sj_relationship`.`student_id` AND `adult_id` = '".$userId."' AND `branch_tag` = '".BRANCH_TAG."')
			AND (`email` = '" . $emailOrUsername . "' OR `username` = '".$emailOrUsername."')
		";
		$query = $this->db->query($sql);
		
		return ($query->num_rows() == 1)? true : false;
	}
	
	public function tag_student($tutorId, $studentId) {
		
		$sql1 = "SELECT * FROM `sj_users` WHERE `id` = '" . $studentId . "'";
		$query1 = $this->db->query($sql1);
		$data = array();
		foreach ($query1->result() as $key=>$value){
			$data[] = $value->id;
		}
		$sql = "SELECT * FROM `sj_user_branch` WHERE `user_id` IN (".implode(",", $data).")";
		$query = $this->db->query($sql);
		$data_array = array();
		foreach($query->result_array() as $keys=>$values){
			$data_array[] = $values['branch_id'];
			$data_array[] = $values['user_id'];
		}
		
		if($query->num_rows() > 0){
			$sql = "INSERT INTO `sj_relationship` (`student_id`, `adult_id`, `status`, `branch_tag`) VALUES ('".$studentId."','".$tutorId."',1, '".BRANCH_TAG."')";
			$query = $this->db->query($sql);
		}
		
		
        $key = array_search("1", $data_array);
        
        $email = $value->email;
        
        $email = $query1->row()->email;
        if(empty($email) == TRUE) {
            $return = $query1->row()->username;
        } else {
            $return = $email;
        }
        
		return (in_array("9", $data_array)) ? $return : NULL;
	}
	
	public function get_user_fullname_using_email_or_username($emailOrUsername) {
		$sql = "SELECT * FROM `sj_users` WHERE (`email` = " . $this->db->escape($emailOrUsername) . " OR `username` = " . $this->db->escape($emailOrUsername) . ") AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() === 1) {
			return $query->row()->fullname;
			}
	}
	
	public function get_email_from_username($username) {
		$sql = "SELECT * FROM `sj_users` WHERE `username` = '" . $username . "' AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() === 1) {

            $result = $query->row();
            if ($result->email != '') {
                return $result->email;
            } else {
                return false;
            }
		} else {
			return false;
		}
    }
    
    public function count_parent_list($studentId)
	{
		$sql = "SELECT COUNT(*) as TOTAL FROM `sj_relationship` WHERE `student_id` = '".$studentId."' AND `branch_tag` = '".BRANCH_TAG."' AND `status` = 2";
		$query = $this->db->query($sql);
		return $query->row()->TOTAL;
	}
	
	public function view_parent_list($studentId, $limit, $start)
	{
		$returnArray = array();
		$sql_tutor = "
			SELECT *
			FROM `sj_users`
			WHERE `id` IN (SELECT `adult_id` FROM `sj_relationship` WHERE `student_id` = '".$studentId."' AND `branch_tag` = '".BRANCH_TAG."' AND `status` = 2)
			ORDER BY `id` ASC
			LIMIT ".$limit." OFFSET ".$start."
		";
        $query_tutor = $this->db->query($sql_tutor);
		$returnArray = $query_tutor;
		
		return $returnArray;
	}
	
	public function count_tutor_list($studentId)
	{
		$sql = "SELECT COUNT(*) as TOTAL FROM `sj_relationship` WHERE `student_id` = '".$studentId."' AND `branch_tag` = '".BRANCH_TAG."'";
		$query = $this->db->query($sql);
		return $query->row()->TOTAL;
	}
	
	public function view_tutor_list($studentId, $limit, $start)
	{
		$returnArray = array();
		$sql_tutor = "
			SELECT *
			FROM `sj_users`
			WHERE `id` IN (SELECT `adult_id` FROM `sj_relationship` WHERE `student_id` = '".$studentId."' AND `branch_tag` = '".BRANCH_TAG."' AND `status` = 1)
			ORDER BY `id` ASC
			LIMIT ".$limit." OFFSET ".$start."
		";
		$query_tutor = $this->db->query($sql_tutor);
		$returnArray['tutor'] = $query_tutor;
		
		$sql_profession = "SELECT `id`, `name` FROM `sj_profession`";
		$query_profession = $this->db->query($sql_profession);
		
		foreach($query_profession->result() as $row_profession){
			$returnArray['profession'][$row_profession->id] = $row_profession->name;
		}
		
		return $returnArray;
	}
	
	public function checkStudent($student_id, $tutor_id){
		
		$sql = "SELECT * FROM `sj_relationship` WHERE `student_id` = '".$student_id."' AND `adult_id` = '".$tutor_id."' AND `branch_tag` = '".BRANCH_TAG."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows() === 1){
			return true;
		} else {
			return false;
		}
	}
	
	public function untag_student($student_id, $tutor_id){
		
		$sql = "DELETE FROM `sj_relationship` WHERE `student_id` = '".$student_id."' AND `adult_id` = '".$tutor_id."' ";
		$query = $this->db->query($sql);
		
		if($query){
			return true;
		} else {
			return false;
		}
    }
    
    public function check_children_no($parId) {
        $sql = "SELECT COUNT(*) as TOTAL FROM `sj_relationship` WHERE `status` = 2 AND `adult_id` =" . $this->db->escape($parId);

        $query = $this->db->query($sql);

        if($query) {
            return $query->row()->TOTAL;
        } else {
            return false;
        }
        
    }

    function get_level_tid_by_student_id($student_id){

        $sql = "SELECT *
                FROM `sj_levels_tid` l LEFT JOIN `sj_level_users_tid` s ON s.level_code = l.level_code
                WHERE s.user_id = ".$this->db->escape($student_id);
        
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0){
            return $query->row();
        } else {
            return false;
        }

    }

    public function update_last_login($userId, $newRole) {
        $sql = "SELECT * FROM `sj_user_role` WHERE `user_id`=" . $this->db->escape($userId);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            if($newRole == '1'){
                $sql = "UPDATE `sj_users` SET `last_login` = 'tutor' WHERE `id`=" . $this->db->escape($userId);
                $query = $this->db->query($sql);
            } else {
                $sql = "UPDATE `sj_users` SET `last_login` = 'parent' WHERE `id`=" . $this->db->escape($userId);
                $query = $this->db->query($sql);
            }
        } else {
            return false;
        }

        if($query) {
            return true;
        } else {
            return false;
        }
    }

    public function student_check_parent_relationship($stuEmail) {
        $sql = "SELECT `id` FROM `sj_users` WHERE `email` =" . $this->db->escape($stuEmail) . " OR `username` =" . $this->db->escape($stuEmail);
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0) {
            $sql = "SELECT `adult_id` FROM `sj_relationship` WHERE `student_id` = " . $this->db->escape($query->row()->id) . " AND `status` = 2";
            $query = $this->db->query($sql);

            if($query->num_rows() > 0) {
                $sql = "SELECT `email` FROM `sj_users` WHERE `id` = " . $this->db->escape($query->row()->adult_id) . " AND `branch_code` = ".BRANCH_ID."";
                $query = $this->db->query($sql);
                return $query->row()->email;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    

    function get_level_by_student_id($student_id){

        $sql = "SELECT l.level_name,l.level_id 
                FROM `sj_levels` l LEFT JOIN `sj_student` s ON s.level_id = l.level_id
                WHERE s.student_id = ".$this->db->escape($student_id);
        
        $query = $this->db->query($sql);
		
		if($query->num_rows() > 0){
			return $query->row();
		} else {
			return false;
		}

    }

    public function getStudentSubjectType($user_id){
        $sql = "SELECT `level_id` FROM `sj_student` WHERE `student_id` = " . $this->db->escape($user_id);
        $query = $this->db->query($sql);

        $subject_type = 0;
        if($query->num_rows() > 0){
            $level_id = $query->row()->level_id;
            $sql = "SELECT `subject_type` FROM `sj_levels` WHERE `level_id` = " . $this->db->escape($level_id);
            $query = $this->db->query($sql);
            if($query->num_rows() > 0){
                $subject_type = $query->row()->subject_type;
            }
        }
        return $subject_type;
    }

    public function getStudentStatusTag($user_id){
        
        $sql = "SELECT `level_id` FROM `sj_student` WHERE `student_id` = " . $this->db->escape($user_id);
        $query = $this->db->query($sql);

        $subject_type = 0;
        if($query->num_rows() > 0){
			$level_id = $query->row()->level_id;
            $sql = "SELECT `subject_type` FROM `sj_levels` WHERE `level_id` = " . $this->db->escape($level_id);
            $query = $this->db->query($sql);
            if($query->num_rows() > 0){
                $subject_type = $query->row()->subject_type;
            }
		}

        if($subject_type >= 5) {
            $sql2 = "SELECT `id` FROM `sj_subject` WHERE `id` IN (5)";
        } elseif($subject_type > 1 && $subject_type <= 3 ) {
            $sql2 = "SELECT `id` FROM `sj_subject` WHERE `id` IN (1, 2,3)";
        } else {
            $sql2 = "SELECT `id` FROM `sj_subject` WHERE 1 ";
        }

        $query2 = $this->db->query($sql2);
        $subject_id = array();
        foreach($query2->result() as $row){
            $subject_id[] = $row->id;
        }

        return $subject_id;
    }
    

}