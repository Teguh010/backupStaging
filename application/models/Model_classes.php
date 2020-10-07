<?php

class Model_classes extends CI_Model {

	public function count_class($userId=0, $keyword=''){
		$keyword = $this->splitString($keyword);
		$where = ( $keyword=='') ? "" : " WHERE class_name like '%".$keyword."%' ";
		$sql = "SELECT COUNT(*) as total FROM `sj_class` ".$where;
		$sql = "SELECT COUNT(*) as total  FROM `sj_class_relation` r, `sj_class` c WHERE r.`class_id`=c.`class_id` AND  r.`user_id`=".$userId."";
		
		$result = $this->db->query($sql);
		
		return $result->row()->total;
	}

	public function get_classes($userId=0, $limit, $start, $keyword=''){
		$data = array();
		$keyword = $this->splitString($keyword);
		$where = ( $keyword=='') ? " WHERE 1 " : " WHERE class_name like '%".$keyword."%' ";
		$sql = "
			SELECT * FROM `sj_class` c
			INNER JOIN `sj_class_relation` r
			ON r.`class_id`=c.`class_id`
			INNER JOIN `sj_subject`
			ON c.`subject_id` = `sj_subject`.`id` 
			INNER JOIN `sj_levels`
			ON c.`level_id` = `sj_levels`.`id` 
			{$where} AND `branch` = ".BRANCH_ID." AND r.`user_id`=".$userId."
			ORDER BY `created_date` DESC 
			LIMIT " . $limit . " OFFSET " . $start . "
		";
		$query = $this->db->query($sql);
		
		$data['classes'] = $query->result();
		
		return $data;
	}

	public function get_class($class_id=0){
		$sql = "SELECT * FROM `sj_class` c, `sj_subject` s WHERE c.`subject_id`=s.`id` AND `class_id` = '{$class_id}' ";
		$result = $this->db->query($sql);
		return $result->row();
	}

	public function get_class_gc($google_classroom_id=0){
		$sql = "SELECT * FROM `sj_class` WHERE `google_classroom_id` = '{$google_classroom_id}' ";
		$result = $this->db->query($sql);
		return $result->row();
	}

	public function get_class_relation($class_id=0,$type='student'){
		$sql = "SELECT * FROM `sj_class_relation` r, `sj_class` c WHERE r.`class_id`=c.`class_id` AND r.`class_id` = '{$class_id}' AND type='{$type}' ";
		$result = $this->db->query($sql);
		return $result->result();
	}

	public function check_class_tag($class_id=0,$user_id=0){
		$sql = "SELECT COUNT(*) as total FROM `sj_class_relation` WHERE `class_id` = '{$class_id}' AND user_id='{$user_id}' ";
		$result = $this->db->query($sql);
		return $result->row()->total;
	}

	public function splitString($str)
	{
		$splitString = array();
		$splitString = explode("-",$str);
		$splitString = implode(" ",$splitString);
		return $splitString;
	}

	public function count_class_tutor($keyword=""){
		$keyword = $this->splitString($keyword);
		$where = ( $keyword=='') ? " " : " AND (sj_users.username like '%".$keyword."%' OR sj_users.email like '%".$keyword."%' OR sj_users.fullname like '%".$keyword."%') ";
		$sql = "
		SELECT COUNT(*) as total FROM `sj_branch_user`
		INNER JOIN `sj_users`
		ON `sj_branch_user`.`user_id` = `sj_users`.`id`
		WHERE `sj_branch_user`.`account_type` = 'tutor' AND `sj_branch_user`.`branch_id` = ".BRANCH_ID." {$where}
	";
		
		$result = $this->db->query($sql);
		
		return $result->row()->total;
	}

	public function get_class_tutor(){
		// $keyword = $this->splitString($keyword);
		// $where = ( $keyword=='') ? " " : " AND (sj_users.username like '%".$keyword."%' OR sj_users.email like '%".$keyword."%' OR sj_users.fullname like '%".$keyword."%') ";
		$data = array();
		$sql_user = "
			SELECT * FROM `sj_branch_user`
			INNER JOIN `sj_users`
			ON `sj_branch_user`.`user_id` = `sj_users`.`id`
			WHERE `sj_branch_user`.`account_type` = 'tutor' AND `sj_branch_user`.`branch_id` = ".BRANCH_ID." 
			ORDER BY `sj_branch_user`.`registered_date` DESC 			
		";
		
		$query_user = $this->db->query($sql_user);
		
		$data['user'] = $query_user->result();
		
		return $data;
	}

	public function count_class_student($keyword=""){
		$keyword = $this->splitString($keyword);
		$where = ( $keyword=='') ? " " : " AND (sj_users.username like '%".$keyword."%' OR sj_users.email like '%".$keyword."%' OR sj_users.fullname like '%".$keyword."%') ";
		$sql = "
			SELECT COUNT(*) as total FROM `sj_branch_user`
			INNER JOIN `sj_users`
			ON `sj_branch_user`.`user_id` = `sj_users`.`id`
			WHERE `sj_branch_user`.`account_type` = 'student' AND `sj_branch_user`.`branch_id` = ".BRANCH_ID." {$where}
		";
		
		$result = $this->db->query($sql);
		
		return $result->row()->total;
	}

	public function get_class_student(){
		// $keyword = $this->splitString($keyword);
		// $where = ( $keyword=='') ? " " : " AND (sj_users.username like '%".$keyword."%' OR sj_users.email like '%".$keyword."%' OR sj_users.fullname like '%".$keyword."%') ";
		$data = array();		
		$sql_user = "
			SELECT * FROM `sj_branch_user`
			INNER JOIN `sj_users`
			ON `sj_branch_user`.`user_id` = `sj_users`.`id`
			WHERE `sj_branch_user`.`account_type` = 'student' AND `sj_branch_user`.`branch_id` = ".BRANCH_ID." 
			ORDER BY `sj_branch_user`.`registered_date` DESC 			
		";
		// $sql_user = "SELECT * FROM `sj_branch_user` a, sj_users b 
		// 				WHERE a.user_id=b.id AND a.account_type = 'student' AND a.branch_id = 9 
		// 				ORDER BY a.registered_date DESC 
		// 				LIMIT " . $limit . " OFFSET " . $start . "
		// ";
		
		$query_user = $this->db->query($sql_user);
		$data['user'] = $query_user->result();
		return $data;
	}

	public function getStudentClass($userId){
		$sql  = "SELECT a.*, b.class_name, b.subject_id, b.level_id, c.level_name, d.name AS subject_name 
					FROM sj_class_relation a 
					JOIN sj_class b ON a.class_id=b.class_id 
					JOIN sj_levels c ON b.level_id=c.level_id 
					JOIN sj_subject d ON b.subject_id=d.id 
					WHERE a.user_id='$userId' 
				";
		$query = $this->db->query($sql);
		$result['class_id'] = array();
		$result['class_name'] = array();
		$result['subject_name'] = array();
		$result['level_name'] = array();
		$result['tutor_name'] = array();
		$result['tutor_site'] = array();
        foreach($query->result() as $row){
            $result['class_id'][] = $row->class_id;
			$result['class_name'][] = $row->class_name;
			$result['subject_name'][] = $row->subject_name;
			$result['level_name'][] = $row->level_name;
			$sql_tutor = "SELECT a.user_id, b.fullname, b.agency_link FROM sj_class_relation a 
							JOIN sj_users b ON a.user_id=b.id 
							WHERE a.class_id='$row->class_id' AND a.type='tutor'
						";
			$query1 = $this->db->query($sql_tutor)->row();
            if(isset($query1)){
				$result['tutor_name'][] = $query1->fullname;
				if($query1->agency_link == NULL){
					$result['tutor_site'][] = '';
				}else{
					$result['tutor_site'][] = $query1->agency_link;
				}
			}else{
				$result['tutor_name'][] = '';
				$result['tutor_site'][] = '';
			}
		}

		return $result;
	}

	public function getTutorClass($userId){
		$sql  = "SELECT a.*, b.class_name, b.subject_id, d.level_name, e.name AS subject_name 
					FROM sj_class_relation a 
					JOIN sj_class b ON a.class_id=b.class_id 
					JOIN sj_levels d ON b.level_id=d.level_id 
					JOIN sj_subject e ON b.subject_id=e.id 
					WHERE a.user_id='$userId' 
				";
		$query = $this->db->query($sql);
		$result['class_id'] = array();
		$result['class_name'] = array();
		$result['subject_name'] = array();
		$result['level_name'] = array();
		$result['student_id'] = array();
		$result['student_name'] = array();
		$result['student_site'] = array();
		$result['class_id2'] = array();
		$result['class_name2'] = array();
		$result['subject_name2'] = array();
		$result['level_name2'] = array();
        foreach($query->result() as $row){
            $result['class_id'][] = $row->class_id;
			$result['class_name'][] = $row->class_name;
			$result['subject_name'][] = $row->subject_name;
			$result['level_name'][] = $row->level_name;
			$get_student = "SELECT a.*, b.fullname, b.agency_link FROM sj_class_relation a 
							JOIN sj_users b ON a.user_id=b.id 
							WHERE a.class_id='$row->class_id' AND a.type='student'
						";
			$query1 = $this->db->query($get_student);
            foreach($query1->result() as $row1){
				$result['class_id2'][] = $row->class_id;
				$result['class_name2'][] = $row->class_name;
				$result['subject_name2'][] = $row->subject_name;
				$result['level_name2'][] = $row->level_name;
				$result['student_id'][] = $row1->user_id;
				$result['student_name'][] = $row1->fullname;
				$result['student_site'][] = $row1->agency_link;
			}
		}

		return $result;
	}

	public function check_user_by_email_and_insert($email="") {
		$sql = "SELECT `id` FROM `sj_users` WHERE (`email` = ".$this->db->escape($email).")";
		$query = $this->db->query($sql);
        $data = array();
        if($query->num_rows() > 0) {
            foreach ($query->result() as $key=>$value){
                $data[] = $value->id;
                $user_id = $value->id;
            }
            
            $sql = "SELECT * FROM `sj_user_branch` WHERE `user_id` IN (".implode(",", $data).") and branch_id=".BRANCH_ID;
            $query = $this->db->query($sql);
            
            if($query) {
                $user_id = $data[0];
                foreach($query->result_array() as $keys=>$values){
                    $user_id = $values['user_id'];
                }
                return $user_id;
            } else {
            	$dat = array(
            		'user_id' => $user_id,
            		'branch_id' => BRANCH_ID
            	);
            	$this->db->insert('sj_user_branch',$dat);
            	return $user_id;
            }
        } else {
            return 0;
        }

	}
}