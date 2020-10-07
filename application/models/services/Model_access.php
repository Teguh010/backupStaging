<?php

class Model_access extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_users');
        
    }

    public function add_user($username = '', $name = '', $role = '', $branch = ''){

        $data = array(
			'username' 	        => $username,
			'fullname' 	        => trim($name),
			'profile_pic' 		=> ($role==2?'student_placeholder.jpg':'user_placeholder.jpg'),
			'status' 		    => 1,
			'last_login' 		=> ($role ==2)?'student':($role==1?'tutor':'parent'),
			'registered_date' 	=> date('Y-m-d H:i:s'),
			'branch_code' 		=> $branch
        );
        
        $insert = array();
        foreach($data as $k => $v) {
            $insert["`" . $k . "`"] = $this->db->escape($v);
        }
        
        // inserting new user
        $sql = "INSERT INTO `sj_users` (" . implode(", ", array_keys($insert)) . ") VALUES (" . implode(", ", array_values($insert)) . ")";
        //array_debug($sql);exit;
        $this->db->query($sql);

        $sql = "SELECT * FROM `sj_users` WHERE username = ". $this->db->escape($username);
        $query = $this->db->query($sql);

        //array_debug($query->result());

        if($query->num_rows()> 0){
            $result = $query->row();
            $user_id = $result->id;

            // Tag Branch

            $this->tag_branch($user_id,$branch);


            // User Role

            $data = array(
                'user_id' 	        => $user_id,
                'role_id' 	        => $role,
                'branch_code' 	    => $branch
            );

            $insert = array();
            foreach($data as $k => $v) {
                $insert["`" . $k . "`"] = $this->db->escape($v);
            }
            
            // inserting new user role
            $sql = "INSERT INTO `sj_user_role` (" . implode(", ", array_keys($insert)) . ") VALUES (" . implode(", ", array_values($insert)) . ")";
            
            //array_debug($sql);exit;
            $this->db->query($sql);


            // Branch User

            $data = array(
                'user_id' 	        => $user_id,
                'account_type' 	    => ($role ==2)?'student':($role==1?'tutor':'parent'),
                'status' 	        => 1,
                'branch_id' 	    => $branch
            );

            $insert = array();
            foreach($data as $k => $v) {
                $insert["`" . $k . "`"] = $this->db->escape($v);
            }
            
            // inserting new branch user
            $sql = "INSERT INTO `sj_branch_user` (" . implode(", ", array_keys($insert)) . ") VALUES (" . implode(", ", array_values($insert)) . ")";
            
            //array_debug($sql);exit;
            $this->db->query($sql);

            // For Student table
            if($role == 2){
                
                $data = array(
                    'student_id' 	    => $user_id,
                    'school_id' 	    => '343',
                    'level_id' 	        => '1'
                );

                $insert = array();
                foreach($data as $k => $v) {
                    $insert["`" . $k . "`"] = $this->db->escape($v);
                }
                
                // inserting new student
                $sql = "INSERT INTO `sj_student` (" . implode(", ", array_keys($insert)) . ") VALUES (" . implode(", ", array_values($insert)) . ")";
                
                //array_debug($sql);exit;
                $this->db->query($sql);
            }

        }else{
            return false;
        }


    }

    function check_branch($branch_id){

        $sql = "SELECT * FROM `sj_branch` WHERE branch_id = ". $this->db->escape($branch_id);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function check_user_branch($user_id,$branch_id){

        $sql = "SELECT * FROM `sj_user_branch` WHERE user_id = ".$this->db->escape($user_id)." AND branch_id = ". $this->db->escape($branch_id);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function get_user($username){
        $sql = "SELECT * FROM `sj_users` WHERE username = ". $this->db->escape($username);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return null;
        }
    }

    function tag_branch($user_id,$branch){
        // Tag Branch

        $data = array(
            'user_id' 	        => $user_id,
            'branch_id' 	    => $branch
        );

        $insert = array();
        foreach($data as $k => $v) {
            $insert["`" . $k . "`"] = $this->db->escape($v);
        }
        
        // inserting new user branch
        $sql = "INSERT INTO `sj_user_branch` (" . implode(", ", array_keys($insert)) . ") VALUES (" . implode(", ", array_values($insert)) . ")";
        
        //array_debug($sql);exit;
        $this->db->query($sql);

    }

    function do_login($username,$branch_id){
		$user_id = $this->model_access->get_user($username)->id;
        $user_role = $this->model_users->get_user_role_from_user_id($user_id);
        $multiple_role = $this->check_no_of_role($user_id);
        $profile = $this->get_user_profile_pic($user_id);
        $data_ar = array(
            'is_logged_in' => 1,
            'user_id' => $user_id,
            'user_role' => $user_role,
            'check_user_role' => $multiple_role,
            'profile_pic' => $profile,
            'branch_code'  => $branch_id
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

    private function get_user_profile_pic($user_id) {
        $sql = "SELECT * FROM `sj_users` WHERE `id` =" . $this->db->escape($user_id);

        $query = $this->db->query($sql); 

        if($query->num_rows() > 0) {
            $result = $query->row();

            $profile = $result->profile_pic;

            return $profile;
        }
    }


    function log($payload,$token){
        // logging

        $data = array(
            'payload' 	    => $payload,
            'token' 	    => $token,
            'create_date' 	=> date('Y-m-d H:i:s'),
        );

        $insert = array();
        foreach($data as $k => $v) {
            $insert["`" . $k . "`"] = $this->db->escape($v);
        }
        
        
        $sql = "INSERT INTO `sj_access_log` (" . implode(", ", array_keys($insert)) . ") VALUES (" . implode(", ", array_values($insert)) . ")";
        
        //array_debug($sql);exit;
        $this->db->query($sql);

    }

}