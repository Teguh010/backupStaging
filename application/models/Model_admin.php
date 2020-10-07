<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



class Model_admin extends CI_Model {

	public function can_login() {



		$sql = "SELECT * FROM `sj_admin` WHERE (`username` = ? ) AND `password` = ? AND `branch_code` = ".BRANCH_ID;

		$query = $this->db->query($sql, array($this->input->post('login_username'), hash('sha256', $this->input->post('login_password'))));



		if ($query->num_rows() == 1) {

			return true;

		} else {

			return false;

		}



	}

	function get_admin($username) {
		$sql = "SELECT * FROM `sj_admin` WHERE `username` = " . $this->db->escape($username) . " AND `branch_code` = ".BRANCH_ID;
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}


	public function login($username) {

		$admin = $this->get_admin($username);

		$admin_id = $admin->id;

		$data_ar   = array(

			'is_admin_logged_in' => 1,

			'admin_username'     => $username,

			'admin_id' => $admin_id,

			'branch_code'   => BRANCH_ID
		);



		$this->session->set_userdata($data_ar);

	}



	/*public function get_question_issue() {

		$sql = "SELECT * FROM `sj_question_issue` WHERE `resolved` = 0 ORDER BY `created` DESC";

		$query = $this->db->query($sql);



		$question_issue = array();

		foreach ($query->result() as $row) {

			$question_issue[] = $row;

		}



		return $question_issue;

	}
*/


	public function get_feedback() {

		$sql = "SELECT * FROM `sj_feedback` WHERE `resolved` = 0 ORDER BY `feedback_date` DESC";

		$query = $this->db->query($sql);



		$feedback = array();

		foreach ($query->result() as $row) {

			$feedback[] = $row;

		}



		return $feedback;

	}



	public function mark_question_issue_resolved($issue_id, $resolved = true) {

		$resolve = $resolved?1:0;



		$data_ar = array(

           'resolved' => $resolve

        );



		$this->db->where('id', $issue_id);

		$query = $this->db->update('sj_question_issue', $data_ar); 



		return $query?true:false;

	}



	public function mark_feedback_read($feedback_id, $resolved = true) {

		$resolve = $resolved?1:0;



		$data_ar = array(

			'resolved' => $resolve

		);



		$this->db->where('id', $feedback_id);

		$query = $this->db->update('sj_feedback', $data_ar);



		return $query?true:false;

	}

	
	public function counted_issue()
	{
		$sql = "SELECT COUNT('*') AS issue FROM `sj_question_issue` WHERE `resolved` = 0";
		
		$query = $this->db->query($sql);
		
		return $query->row()->issue;
	}
	
	public function get_question_issue($limit, $offset) 
	{
		$this->db->select('*');
		$this->db->from('sj_question_issue');
		$this->db->where('resolved', 0);
		$this->db->order_by('created', 'DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
	
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		} else{
			return true;
		}
		return false;
	}
	
	public function amend_question(){
		$sql = "SELECT * FROM `sj_temp_question`";
		
		$result = $this->db->query($sql);
		
		return $result;
	}
	
	public function update_question($question, $ref_id){
		foreach ($question as $key => $que){
			$sql = "UPDATE `sj_questions` SET `question_text` = '".$que."' WHERE `question_id` = ".$ref_id[$key]."";
			
			$result = $this->db->query($sql);
		}
		if($result){
			return TRUE;
		}else {
			return FALSE;
		}
	}

	public function count_public_question($search, $search_type, $subject_id, $level_id){

		$sql = "SELECT COUNT(*) as pub FROM `sj_questions` WHERE ";
		
		// filter base on question text | topics | tags
		if ($search != "NULL" && $search_type != "NULL"){
			if($search_type == 'question_text'){
				$sql .= "`question_text` LIKE '%$search%' AND ";

			} else if ($search_type == 'topic'){
				
				$sql .= "`topic_id` IN (SELECT `id` FROM `sj_categories` WHERE `name` LIKE '%$search%') AND ";

			} else if ($search_type == 'tag') {
				$sql .= "`question_id` IN (SELECT `question_id` FROM `sj_question_tag` WHERE `tags` LIKE '%$search%') AND ";
			}
		}

		// filter base on subject_id
		if ($subject_id != "NULL" && $subject_id != 0){
			$sql .= "`subject_type` = '$subject_id' AND ";
		}

		// filter base on level_id
		if ($level_id != "NULL" && $level_id != 0){
			$sql .= "`level_id` = '$level_id' AND ";
		}

		$sql .= "`branch_name` = 'SmartJen'";

		if ($search == "NIL") $search = "";

		// if(is_numeric($this->input->post('subject_id')) == 1){
		// 	$subject_id = $this->input->post('subject_id');
		// }

		// if (isset($subject_id) && $search == 'NULL') {
		// 	$sql = "SELECT COUNT(*) as pub FROM `sj_questions` WHERE `subject_type` = " . $this->db->escape($subject_id) . " AND `branch_name` = 'SmartJen'";
		// } else if (isset($subject_id) && $search != 'NULL') {
		// 	$sql = "SELECT COUNT(*) as pub FROM `sj_questions` WHERE `question_text` LIKE " . $this->db->escape("%" . $search . "%") . " AND `branch_name` = 'SmartJen' AND `subject_type` = " . $this->db->escape($subject_id);
		// } else if (!isset($subject_id) && $search == 'NULL') {
		// 	$sql = "SELECT COUNT(*) as pub FROM `sj_questions` WHERE `branch_name` = 'SmartJen'";
		// } else {
		// 	$sql = "SELECT COUNT(*) as pub FROM `sj_questions` WHERE `question_text` LIKE " . $this->db->escape("%" . $search . "%") . " AND `branch_name` = 'SmartJen'";
		// }
		
		// $sql = "SELECT COUNT(*) as pub FROM `sj_questions` WHERE `question_level` = '0' AND `question_text` LIKE " . $this->db->escape("%" . $search . "%") . "";
		
		$result = $this->db->query($sql);
		
		return $result->row()->pub;
	}

	public function get_public_question($limit, $start, $search, $search_type, $subject_id, $level_id){
		
		if ($search == "NIL") $search = "";
		
		$data = array();

		$sql = "SELECT * FROM `sj_questions` WHERE ";
		
		// filter base on question text | topics | tags
		if ($search != "NULL" && $search_type != "NULL"){
			if($search_type == 'question_text'){
				$sql .= "`question_text` LIKE '%$search%' AND ";

			} else if ($search_type == 'topic'){
				
				$sql .= "`topic_id` IN (SELECT `id` FROM `sj_categories` WHERE `name` LIKE '%$search%') AND ";

			} else if ($search_type == 'tag') {
				$sql .= "`question_id` IN (SELECT `question_id` FROM `sj_question_tag` WHERE `tags` LIKE '%$search%') AND ";
			}
		}

		// filter base on subject_id
		if ($subject_id != "NULL" && $subject_id != 0){
			$sql .= "`subject_type` = '$subject_id' AND ";
		}

		// filter base on level_id
		if ($level_id != "NULL" && $level_id != 0){
			$sql .= "`level_id` = '$level_id' AND ";
		}

		// set limit for pagination search
		$sql .= "`branch_name` = 'SmartJen' LIMIT $limit OFFSET $start ";

		// if($limit != "NULL" && $start != "NULL"){
		// 	$sql .= "`branch_name` = 'SmartJen' LIMIT '$limit' OFFSET '$start' ";
		// } else {
		// 	$sql .= "`branch_name` = 'SmartJen'";
		// }

		// if (isset($subject_id) && $search == 'NULL') {
		// 	$sql_questions = "SELECT * FROM `sj_questions` WHERE `subject_type` = " . $this->db->escape($subject_id) . " AND `branch_name` = 'SmartJen' LIMIT " . $limit . " OFFSET " . $start . "";
		// } else if (isset($subject_id) && $search != 'NULL') {
		// 	$sql_questions = "SELECT * FROM `sj_questions` WHERE `question_text` LIKE " . $this->db->escape("%" . $search . "%") . " AND `branch_name` = 'SmartJen' AND `subject_type` = " . $this->db->escape($subject_id) . " LIMIT " . $limit . " OFFSET " . $start . "";
		// } else if (!isset($subject_id) && $search == 'NULL') {
		// 	$sql_questions = "SELECT * FROM `sj_questions` WHERE `branch_name` = 'SmartJen' LIMIT " . $limit . " OFFSET " . $start . "";
		// } else {
		// 	$sql_questions = "SELECT * FROM `sj_questions` WHERE `question_text` LIKE " . $this->db->escape("%" . $search . "%") . " AND `branch_name` = 'SmartJen' LIMIT " . $limit . " OFFSET " . $start . "";
		// }
		
		// $sql_questions = "SELECT * FROM `sj_questions` WHERE `question_level` = '0' AND `question_text` LIKE " . $this->db->escape("%" . $search . "%") . " LIMIT " . $limit . " OFFSET " . $start . "";

		$query_question = $this->db->query($sql);
		
		$data['question'] = $query_question;

		$tags = array();
		foreach($query_question->result() as $question_row){
			$sql_question_tag = "SELECT * FROM `sj_question_tag` WHERE `question_id` = " . $this->db->escape($question_row->question_id);	
			$query_question_tag = $this->db->query($sql_question_tag);
		
			if($query_question_tag->num_rows() == 0){
				$tags[] = '-';
			} else if ($query_question_tag->num_rows() == 1){
				if($query_question_tag->row()->tags == ''){
					$tags[] = '-';
				} else {
					$tags[] =  $query_question_tag->row()->tags;
				}
			} else {
				$tag = '';
				foreach($query_question_tag->result() as $question_tag){
					if($tag == ''){
						$tag = $question_tag->tags;
					} else {
						$tag = $tag.', '.$question_tag->tags;
					}
				}

				$tags[] = $tag;
			}
		}	

		$data['question_tag'] = $tags;
		
		$sql_category = "SELECT `id`, `name` FROM `sj_categories`";
		
		$query_category = $this->db->query($sql_category);
		
		foreach($query_category->result() as $row_category){
			$data['category'][$row_category->id] = $row_category->name;
		}
		
		$sql_level = "SELECT `level_id`, `level_name` FROM `sj_levels`";
		
		$query_level = $this->db->query($sql_level);
		
		foreach($query_level->result() as $row_level){
			$data['level'][$row_level->level_id] = $row_level->level_name;
		}
		
		$sql_difficulty = "SELECT `difficulty_id`, `difficulty_name` FROM `sj_difficulty`";
		
		$query_difficulty = $this->db->query($sql_difficulty);
		
		foreach($query_difficulty->result() as $row_difficulty){
			$dif_id = $row_difficulty->difficulty_id;
			$dif_name = $row_difficulty->difficulty_name;
			
			if(isset($dif_id) && $dif_id == '5'){
				$dif_name = "Mad";
			}
			
			$data['difficulty'][$dif_id] = $dif_name;
		}
		
		return $data;
	}
	
	public function upload_question_bulk_public($data)
	{
		foreach($data as $row){
			$prefix = time();
			
			$ref_id = $prefix . '_' . $row['reference_id'];
			
			$sql = "
			INSERT INTO `sj_questions` (`disabled`, `reference_id`, `sub_question`, `customization`, `source`, `question_text`, `school_id`, `CASA`, `level_id`, `topic_id`, `topic_id2`, `topic_id3`, `key_topic`, `key_strategy`, `substrategy_id`, `substrategy_id2`, `substrategy_id3`, `substrategy_id4`, `strategy_id`, `strategy_id2`, `strategy_id3`, `strategy_id4`, `question_type_id`, `question_type_reference_id`, `year`, `difficulty`, `graphical`, `answer_type_id`, `branch_name`, `subject_type`) 
			VALUES (".$this->db->escape($row['disabled']).",'".$ref_id."',".$this->db->escape($row['sub_question']).", ".$this->db->escape($row['customization']).", ".$this->db->escape($row['source']).",".$this->db->escape($row['question_text']).",".$this->db->escape($row['school_id']).",".$this->db->escape($row['CASA']).",".$this->db->escape($row['level_id']).",".$this->db->escape($row['topic_id']).", ".$this->db->escape($row['topic_id2']).", ".$this->db->escape($row['topic_id3']).", ".$this->db->escape($row['key_topic']).", ".$this->db->escape($row['key_strategy']).", ".$this->db->escape($row['substrategy_id']).", ".$this->db->escape($row['substrategy_id2']).", ".$this->db->escape($row['substrategy_id3']).", ".$this->db->escape($row['substrategy_id4']).", ".$this->db->escape($row['strategy_id']).",".$this->db->escape($row['strategy_id2']).",".$this->db->escape($row['strategy_id3']).",".$this->db->escape($row['strategy_id4']).",".$this->db->escape($row['question_type_id']).",".$this->db->escape($row['question_type_reference_id']).",".$this->db->escape($row['year']).",".$this->db->escape($row['difficulty']).",".$this->db->escape($row['graphical']).",".$this->db->escape($row['answer_type_id']).",".$this->db->escape($row['branch_name']).",".$this->db->escape($row['subject_type']).")
			";
			
			$query = $this->db->query($sql);
			
			$ref_ids = $this->db->insert_id();
			
			$ques_id [] = array(
				'ref_id'  => $ref_ids,
				'temp_id'  =>  $ref_id,
				'year'  => $row['year'],
				'level_id'  => $row['level_id'],
				'graphical'  => $row['graphical'],
				'tags' => $row['CASA']
			);
			
			$insert_answer = array(
				array(
					'question_id'  =>$ref_ids,
					'answer_text'  => $row['answer_text_one']
				),
				array(
					'question_id'  =>$ref_ids,
					'answer_text'  => $row['answer_text_two']
				),
				array(
					'question_id'  =>$ref_ids,
					'answer_text'  => $row['answer_text_three']
				),
				array(
					'question_id'  =>$ref_ids,
					'answer_text'  => $row['answer_text_four']
				)
			);
			
			$this->db->insert_batch('sj_answers', $insert_answer);
			
			$first_answer_id = $this->db->insert_id();
			
			$correct_answer_id = $first_answer_id + $row['correct_answer'] - 1;
			
			$this->db->insert('sj_correct_answer', array(
				
				'question_id' => $ref_ids,
				
				'answer_id'   => $correct_answer_id
				
			));
			
			$newgraphical = $row['year'] . '-0-' . $row['level_id'] . '-' . $ref_ids .'.png';
			
			$sql_update = "UPDATE `sj_questions` SET `graphical` = CASE WHEN `graphical` = '0' THEN '0' ELSE ? END WHERE `question_id` = ?";
			
			$query_update = $this->db->query($sql_update, array($newgraphical, $ref_ids));

		}
		foreach($ques_id as $value){
			
			$sql_update = "UPDATE `sj_questions` SET `reference_id` = ? WHERE `reference_id` = ?";
			
			$query_update = $this->db->query($sql_update, array($value['ref_id'],$value['temp_id']));
			
			$tag_sql = "INSERT INTO `sj_question_tag` (`tags`, `question_id`) VALUES ('" . $value['tags'] . "', '" . $value['ref_id'] . "')";
			
			$tag_query = $this->db->query($tag_sql);
		}
		
	}
	
	public function count_temp_user(){
		$sql = "SELECT COUNT(*) as pub FROM `sj_temp_users` WHERE `branch_code` = ".BRANCH_ID;
		
		$result = $this->db->query($sql);
		
		return $result->row()->pub;
	}
	
	public function get_temp_user($limit, $start){
		$data = array();
		
		$sql_temp_user = "SELECT * FROM `sj_temp_users` WHERE `branch_code` = ".BRANCH_ID." ORDER BY `id` DESC LIMIT " . $limit . " OFFSET " . $start . "";
		
		$query_temp_user = $this->db->query($sql_temp_user);
		
		$data['question'] = $query_temp_user->result();
		
		return $data;
	}
	
	public function add_temp_user($key, $user_pass){
		$sql = "SELECT * FROM `sj_temp_users` WHERE `key` = ?";
		$temp_user = $this->db->query($sql, array($key));
		if ($temp_user) {
			$temp_user_row = $temp_user->row();
			$data_ar = array(
				'email' => $temp_user_row->email,
				'password' => hash('sha256', $user_pass),
				'fullname' => $temp_user_row->fullname,
				'username' => $temp_user_row->username,
				'branch_code' => $temp_user_row->branch_code
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
			$delete_temp_user_sql = "DELETE FROM `sj_temp_users` WHERE `key` = ?";
			$delete_temp_user = $this->db->query($delete_temp_user_sql, array($key));
			return ($delete_temp_user) ? true : false;
		}
	}

	public function count_class($keyword=''){
		$keyword = $this->splitString($keyword);
		$where = ( $keyword=='') ? "" : " WHERE class_name like '%".$keyword."%' ";
		$sql = "SELECT COUNT(*) as total FROM `sj_class` ".$where;
		
		$result = $this->db->query($sql);
		
		return $result->row()->total;
	}

	public function get_classes($limit, $start, $keyword=''){
		$data = array();
		$keyword = $this->splitString($keyword);
		$where = ( $keyword=='') ? " WHERE 1 " : " WHERE class_name like '%".$keyword."%' ";
		$sql = "
			SELECT * FROM `sj_class`
			INNER JOIN `sj_subject`
			ON `sj_class`.`subject_id` = `sj_subject`.`id` 
			INNER JOIN `sj_levels`
			ON `sj_class`.`level_id` = `sj_levels`.`id` 
			{$where} AND `branch` = ".BRANCH_ID."
			
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

	public function get_level_by($subject_id=0,$level_id=0){
		$sql = "SELECT * FROM `sj_levels` WHERE `level_id` = '{$level_id}' AND subject_type='{$subject_id}' ";
		$result = $this->db->query($sql);
		return $result->row();
	}
	
	public function count_branch_tutor(){
		$sql = "
		SELECT COUNT(*) as total FROM `sj_branch_user`
		INNER JOIN `sj_users`
		ON `sj_branch_user`.`user_id` = `sj_users`.`id`
		WHERE `sj_branch_user`.`account_type` = 'tutor' AND `sj_branch_user`.`branch_id` =
	".BRANCH_ID;
		
		$result = $this->db->query($sql);
		
		return $result->row()->total;
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
	
	public function get_branch_tutor(){
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
	
	// public function update_tutor_status($user_id, $user_status){
		
	// 	$sql_user = "UPDATE `sj_users` SET `status` = ".$this->db->escape($user_status)." WHERE `id` = ".$this->db->escape($user_id)."";
		
	// 	$query_user = $this->db->query($sql_user);
		
	// 	$sql_branch_user = "UPDATE `sj_branch_user` SET `status` = ".$this->db->escape($user_status)." WHERE `user_id` = ".$this->db->escape($user_id)."";
		
	// 	$query_branch_user = $this->db->query($sql_branch_user);
		
	// 	return $query_user && $query_branch_user ? true : false;
	// }
	
	public function add_branch_tutor($email){
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Email address is not valid. Please enter a valid email.');
			redirect(base_url().'administrator/tutor_list');
		}
		
		$sql = "SELECT * FROM `sj_branch_user` WHERE `email` = ".$this->db->escape($email)."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() === 1){
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Tutor has been added in the listing. Please enter another tutor\'s email.');
			redirect(base_url().'administrator/tutor_list');
		}
		
		$sql = "INSERT INTO `sj_branch_user` (`user_id`, `email`, `account_type`, `status`) VALUES ('NULL', ".$this->db->escape($email).", 'tutor', '0')";
		$query = $this->db->query($sql);
		
		return $query ? true : false;
	}
	
	public function count_branch_student(){
		$sql = "
			SELECT COUNT(*) as total FROM `sj_branch_user`
			INNER JOIN `sj_users`
			ON `sj_branch_user`.`user_id` = `sj_users`.`id`
			WHERE `sj_branch_user`.`account_type` = 'student' AND `sj_branch_user`.`branch_id` =
		".BRANCH_ID;
		
		$result = $this->db->query($sql);
		
		return $result->row()->total;
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
	
	public function get_branch_student(){
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

	// ANOM START
	public function setActiveStudent(){
		$post = $this->input->post();
		$data = [
			'status' => $post['active']
		];
		$this->db->where('id', $post['user_id']);
		$this->db->update('sj_users', $data);

		$data = [
			'status' => $post['active']
		];
		$this->db->where('user_id', $post['user_id']);
		$this->db->update('sj_branch_user', $data);

		return $this->db->affected_rows();
	}

	public function setActiveTutor(){
		$post = $this->input->post();
		$data = [
			'status' => $post['active']
		];
		$this->db->where('id', $post['user_id']);
		$this->db->update('sj_users', $data);

		$data = [
			'status' => $post['active']
		];
		$this->db->where('user_id', $post['user_id']);
		$this->db->update('sj_branch_user', $data);

		return $this->db->affected_rows();
	}

	function uploadStudentPhoto(){		
		$file_name = date('YmdHis');
		$config['upload_path']          = './img/profile/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['file_name']            = $file_name;
		$config['file_ext_tolower']     = TRUE;
		$config['overwrite']            = TRUE;
		$config['max_size']             = 500;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload('userfile')) {
			return $this->upload->data('file_name');
		}else{
			return "user_placeholder.jpg";
		}            
	}

	public function updateStudentProfile(){
		$post = $this->input->post();
        if(!empty($_FILES["userfile"]["name"])){
            $update_photo = $this->uploadStudentPhoto();
        }else{
            $update_photo = $post['changed_picture'];
		}
		
        $data = [
            'email' => $post['profile_email'],
			'fullname' => $post['profile_fullName'],
			'profile_pic' => $update_photo,           
        ];
        $this->db->where('id', $post['user_id']);
		$this->db->update('sj_users', $data);
		
		$data = [
            'email' => $post['profile_email']          
        ];
        $this->db->where('user_id', $post['user_id']);
		$this->db->update('sj_branch_user', $data);
		
		$data = [
			'school_id' => $post['student_school'],
			'level_id' => $post['student_level']
        ];
        $this->db->where('student_id', $post['user_id']);
		$this->db->update('sj_student', $data);
		
        return $this->db->affected_rows();
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

	// ANOM END
	
	public function update_student_status($user_id, $user_status){	
		$sql_user = "UPDATE `sj_users` SET `status` = ".$this->db->escape($user_status)." WHERE `id` = ".$this->db->escape($user_id)."";
		$query_user = $this->db->query($sql_user);
		$sql_branch_user = "UPDATE `sj_branch_user` SET `status` = ".$this->db->escape($user_status)." WHERE `user_id` = ".$this->db->escape($user_id)."";
		$query_branch_user = $this->db->query($sql_branch_user);

		return $query_user && $query_branch_user ? true : false;
	}
	
	public function add_branch_student($email){
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Email address is not valid. Please enter a valid email.');
			redirect(base_url().'administrator/student_list');
		}
		
		$sql = "SELECT * FROM `sj_branch_user` WHERE `email` = ".$this->db->escape($email)."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() === 1){
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Student has been added in the listing. Please enter another student\'s email.');
			redirect(base_url().'administrator/tutor_list');
		}
		
		$sql = "INSERT INTO `sj_branch_user` (`user_id`, `email`, `account_type`, `status`) VALUES ('NULL', ".$this->db->escape($email).", 'student', '0')";
		$query = $this->db->query($sql);
		
		return $query ? true : false;
	}

	public function add_temp_users($key){
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
        
        $session = $this->session->userdata('admin_registration');
		
		if(empty($data_ar['email'])){
			$message = $this->session->set_flashdata('register_error', 'Email address is a required field. Please fill in to register an account.');
			$session['email'] = "Email address is a required field. Please fill in to register an account.";
			$this->session->set_userdata('admin_registration', $session);
			redirect(base_url() . 'administrator/add_user');
		}
		
		$sql = "SELECT * FROM `sj_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID;        
		$query = $this->db->query($sql);        
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Email address has been taken. Please use another email address for registration.');
			$session['email'] = "Email address has been taken. Please use another email address for registration.";
			$this->session->set_userdata('admin_registration', $session);
			redirect(base_url() . 'administrator/add_user');
		}
		$sql = "SELECT * FROM `sj_temp_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID;
		$query = $this->db->query($sql);
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Email address has been taken. Please use another email address for registration.');
			$session['email'] = "Email address has been taken. Please use another email address for registration.";
			$this->session->set_userdata('admin_registration', $session);
			redirect(base_url() . 'administrator/add_user');
		}
		
		$sql = "SELECT * FROM `sj_temp_users` WHERE `username` ='" . $data_ar['username'] . "' AND `branch_code` = ".BRANCH_ID;
		$query = $this->db->query($sql);
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Username has been taken. Please use another username for registration.');
			$session['username'] = "Username has been taken. Please use another username for registration.";
			$this->session->set_userdata('admin_registration', $session);
			redirect(base_url() . 'administrator/add_user');        
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

	public function add_temp_student($par_key, $key) {
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
		$session = $this->session->userdata('admin_registration');
		
		if(!preg_match($pattern, $data_ar['contact_no']) && !preg_match($patterns, $data_ar['contact_no'])){
			$message = $this->session->set_flashdata('register_error', 'Please use Singapore registered mobile number starting with 8 or 9.');
			$session['contact_no'] = "Please use Singapore registered mobile number starting with 8 or 9.";
			$this->session->set_userdata('admin_registration', $session);
			redirect($_SERVER['HTTP_REFERER']);
		}
		
		if(!empty($this->input->post('register_email'))) {
			$sql = "SELECT * FROM `sj_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID;
			$query = $this->db->query($sql);
			
			if($query->num_rows() >0){
				$message = $this->session->set_flashdata('register_error', 'Email address has been taken. Please use another email address for registration.');
				$session['email'] = "Email address has been taken. Please use another email address for registration.";
				$this->session->set_userdata('admin_registration', $session);
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
		
		if(!empty($this->input->post('register_email'))) {
			$sql = "SELECT * FROM `sj_temp_users` WHERE `email` ='" . $data_ar['email'] . "' AND `branch_code` = ".BRANCH_ID;
			$query = $this->db->query($sql);
			if($query->num_rows() >0){
				$message = $this->session->set_flashdata('register_error', 'Email address has been takens. Please use another email address for registration.');
				$session['email'] = "Email address has been taken. Please use another email address for registration.";
				$this->session->set_userdata('admin_registration', $session);
				redirect($_SERVER['HTTP_REFERER']);
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
		
		$sql = "SELECT * FROM `sj_temp_users` WHERE `username` ='" . $data_ar['username'] . "' AND `branch_code` = ".BRANCH_ID;
		$query = $this->db->query($sql);
		
		if($query->num_rows() >0){
			$message = $this->session->set_flashdata('register_error', 'Username has been taken. Please use another username for registration.');
			$session['parent_email'] = "Parent\'s email and student\'s cannot be the same. Please use another email address for registration.";
			$this->session->set_userdata('admin_registration', $session);
			redirect($_SERVER['HTTP_REFERER']);
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

            $insert_par_key = array();
            $insert_par_val = array();
            foreach($par_data as $k => $v) {
                $insert_par_key[] = "`" . $k . "`";
                $insert_par_val[] = "'" . $v . "'";
            }
            $sql = "INSERT INTO `sj_temp_users` (" .implode(" ,", $insert_par_key) . ") VALUES (" . implode(" ,", $insert_par_val) . ")";
            $query = $this->db->query($sql); 

            $sql = "INSERT INTO `sj_temp_relationship` (`student_key`, `parent_key`, `student_id`, `status`,  `branch_tag`) VALUES (" . $this->db->escape($key) . ", " . $this->db->escape($par_key) . ", 'NULL', 1, '".BRANCH_TAG."')";
            $this->db->query($sql);
        }

		return $query ? true : false;
	}

	public function get_next_question_id($question_counter, $subject_id, $branch_id){

		$question_counter = $question_counter + 1;
		$status = true;

		if($branch_id == 1){
            $branch_name = 'SmartJen';
        } else {
            $branch_name = BRANCH_NAME;
		}
		
		$sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `sj_questions`.`branch_name` = ". $this->db->escape($branch_name) ." AND `sj_questions`.`subject_type` = ". $this->db->escape($subject_id) ." AND `question_counter` = ?";
		$sqlQuery = $this->db->query($sql, array($question_counter));

		$result = $sqlQuery->row();

		if($sqlQuery->num_rows() > 0) {
			return $sqlQuery->row();
		} else {
			$status = false;
			return $status;
		}

		// while($status == true) {

		// 	$sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `sj_questions`.`branch_name` = ". $this->db->escape($branch_name) ." AND `sj_questions`.`subject_type` = ". $this->db->escape($subject_id) ." AND `question_counter` = ?";
		// 	// if($question_level == 1){
		// 	// 	$sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `sj_questions`.`branch_name` = 'Prototype' AND `sj_questions`.`question_level` = '1' AND `question_counter` = ?";
		// 	// } else {
		// 	// 	$sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `question_id` = ?";
		// 	// }

		// 	$sqlQuery = $this->db->query($sql, array($question_counter));

		// 	$result = $sqlQuery->row();

		// 	if($sqlQuery->num_rows() > 0) {
		// 		return $sqlQuery->row();
		// 	} else {
		// 		$status = false;
		// 		return $status;
		// 	}
		// }
	}
	
	public function get_previous_question_id($question_counter, $subject_id, $branch_id){
		
		$question_counter = $question_counter - 1;
		$status = true;

		if($branch_id == 1){
            $branch_name = 'SmartJen';
        } else {
            $branch_name = BRANCH_NAME;
		}
		
		$sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `sj_questions`.`branch_name` = ". $this->db->escape($branch_name) ." AND `sj_questions`.`subject_type` = ". $this->db->escape($subject_id) ." AND `question_counter` = ?";
		$sqlQuery = $this->db->query($sql, array($question_counter));

		$result = $sqlQuery->row();

		if($sqlQuery->num_rows() > 0) {
			return $sqlQuery->row();
		} else {
			$status = false;
			return $status;
		}

		// while($status == true) {

		// 	if($question_level == 1){
		// 		$sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `sj_questions`.`branch_name` = 'Prototype' AND `sj_questions`.`question_level` = '1' AND `question_counter` = ?";
		// 	} else {
		// 		$sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `question_id` = ?";
		// 	}

		// 	$sqlQuery = $this->db->query($sql, array($question_id));

		// 	$result = $sqlQuery->row();
			
		// 	if($sqlQuery->num_rows() > 0) {
		// 		$status = false;
		// 		return $sqlQuery->row();
		// 	} else {
		// 		$status = false;
		// 		return $status;
		// 	}
			
		// }
	}
	
	public function get_topic_list($subject_type = NULL)
	{
		if (isset($subject_type) && empty($subject_type) === false) {

			$topic_sql = "SELECT * FROM `sj_categories` WHERE `subject_type` =".$this->db->escape($subject_type)." ORDER BY `id` ";
			$topic_sql_query = $this->db->query($topic_sql);

		} else {

			$topic_sql = "SELECT * FROM `sj_categories` ORDER BY `id`";
			$topic_sql_query = $this->db->query($topic_sql);

		}

		$topic_array = array();

		

		foreach ($topic_sql_query->result() as $row) {

			$topic_array[] = $row;

		}

		

		return $topic_array;

	}
	
	public function get_level_list($postData){
    
        $sql = "SELECT * FROM `sj_subject` WHERE `id` = " . $this->db->escape($postData['subject']); 
        $sqlQuery = $this->db->query($sql);

        $row = $sqlQuery->row();

		// set school level base on subject type inside table levels
        if (strpos($row->name, 'Primary') !== false) {
            $school_level = '2';
        } else {
            $school_level = '5';
		}

        $response = array();

		$this->db->select('level_id,level_name');
		$condition ="subject_type = '". $school_level ."' AND `level_id` NOT IN ('7','8')";
		$this->db->where($condition, NULL, FALSE);  
        $this->db->order_by('level_name','ASC');
        $query = $this->db->get('sj_levels');
        $response = $query->result_array();

        return $response;
	}
	
	public function get_topic_name($postData){

        $response = array();

		$this->db->select('id,name');
		$this->db->where('subject_type', $postData['subject']);
        $this->db->order_by('name','ASC');
        $query = $this->db->get('sj_categories');
        $response = $query->result_array();

        return $response;
	}
	
	public function get_substrategy_list($postData){
		
        $response = array();

		$this->db->select('id,name');
		$this->db->where('subject_type', $postData['subject']);
        $this->db->order_by('name','ASC');
        $query = $this->db->get('sj_substrategy');
        $response = $query->result_array();

        return $response;
	}
	
	public function count_private_question($search, $search_type, $subject_id, $level_id) {

		$sql = "SELECT COUNT(*) as pub FROM `sj_questions` WHERE ";
		
		// filter base on question text | topics | tags
		if ($search != "NULL" && $search_type != "NULL"){
			if($search_type == 'question_text'){
				$sql .= "`question_text` LIKE '%$search%' AND ";

			} else if ($search_type == 'topic'){
				
				$sql .= "`topic_id` IN (SELECT `id` FROM `sj_categories` WHERE `name` LIKE '%$search%') AND ";

			} else if ($search_type == 'tag') {
				$sql .= "`question_id` IN (SELECT `question_id` FROM `sj_question_tag` WHERE `tags` LIKE '%$search%') AND ";
			}
		}

		// filter base on subject_id
		if ($subject_id != "NULL" && $subject_id != 0){
			$sql .= "`subject_type` = '$subject_id' AND ";
		}

		// filter base on level_id
		if ($level_id != "NULL" && $level_id != 0){
			$sql .= "`level_id` = '$level_id' AND ";
		}

		$sql .= "`branch_name` = '".BRANCH_NAME."'";

		if ($search == "NIL") $search = "";

		$result = $this->db->query($sql);
		
		return $result->row()->pub;
	}

	public function get_private_question($limit, $start, $search, $search_type, $subject_id, $level_id){

		if ($search == "NIL") $search = "";
		
		$data = array();

		$sql = "SELECT * FROM `sj_questions` WHERE ";
		
		// filter base on question text | topics | tags
		if ($search != "NULL" && $search_type != "NULL"){
			if($search_type == 'question_text'){
				$sql .= "`question_text` LIKE '%$search%' AND ";

			} else if ($search_type == 'topic'){
				
				$sql .= "`topic_id` IN (SELECT `id` FROM `sj_categories` WHERE `name` LIKE '%$search%') AND ";

			} else if ($search_type == 'tag') {
				$sql .= "`question_id` IN (SELECT `question_id` FROM `sj_question_tag` WHERE `tags` LIKE '%$search%') AND ";
			}
		}

		// filter base on subject_id
		if ($subject_id != "NULL" && $subject_id != 0){
			$sql .= "`subject_type` = '$subject_id' AND ";
		}

		// filter base on level_id
		if ($level_id != "NULL" && $level_id != 0){
			$sql .= "`level_id` = '$level_id' AND ";
		}

		// set limit for pagination search
		$sql .= "`branch_name` = '".BRANCH_NAME."' LIMIT $limit OFFSET $start ";

		$query_question = $this->db->query($sql);
		
		$data['question'] = $query_question;

		$tags = array();
		foreach($query_question->result() as $question_row){
			$sql_question_tag = "SELECT * FROM `sj_question_tag` WHERE `question_id` = " . $this->db->escape($question_row->question_id);	
			$query_question_tag = $this->db->query($sql_question_tag);
		
			if($query_question_tag->num_rows() == 0){
				$tags[] = '-';
			} else if ($query_question_tag->num_rows() == 1){
				if($query_question_tag->row()->tags == ''){
					$tags[] = '-';
				} else {
					$tags[] =  $query_question_tag->row()->tags;
				}
			} else {
				$tag = '';
				foreach($query_question_tag->result() as $question_tag){
					if($tag == ''){
						$tag = $question_tag->tags;
					} else {
						$tag = $tag.', '.$question_tag->tags;
					}
				}

				$tags[] = $tag;
			}
		}	

		$data['question_tag'] = $tags;
		
		$sql_category = "SELECT `id`, `name` FROM `sj_categories`";
		
		$query_category = $this->db->query($sql_category);
		
		foreach($query_category->result() as $row_category){
			$data['category'][$row_category->id] = $row_category->name;
		}
		
		$sql_level = "SELECT `level_id`, `level_name` FROM `sj_levels`";
		
		$query_level = $this->db->query($sql_level);
		
		foreach($query_level->result() as $row_level){
			$data['level'][$row_level->level_id] = $row_level->level_name;
		}
		
		$sql_difficulty = "SELECT `difficulty_id`, `difficulty_name` FROM `sj_difficulty`";
		
		$query_difficulty = $this->db->query($sql_difficulty);
		
		foreach($query_difficulty->result() as $row_difficulty){
			$dif_id = $row_difficulty->difficulty_id;
			$dif_name = $row_difficulty->difficulty_name;
			
			if(isset($dif_id) && $dif_id == '5'){
				$dif_name = "Mad";
			}
			
			$data['difficulty'][$dif_id] = $dif_name;
		}
		
		return $data;
	}

	public function get_new_unique_question($currentQuestionNumber)

    {

        $questionArrayId = $this->session->userdata('questionArray');

        $tempTableId = $this->session->userdata('tempTableId');

        $totalNumOfQuestion = count($tempTableId);

		$randomNum = mt_rand(0, $totalNumOfQuestion - 1);
		
		set_time_limit(300);

        while (in_array($tempTableId[$randomNum], $questionArrayId) === true) {

            $randomNum = mt_rand(0, $totalNumOfQuestion - 1);

        }

		

        $questionArrayId[$currentQuestionNumber - 1] = $tempTableId[$randomNum];

        $this->session->set_userdata('questionArray', $questionArrayId);

        return $this->get_question_from_id($tempTableId[$randomNum]);

	}

	public function get_question_from_id($id)

    {

        if(is_array($id)){
	        $id = implode(",", $id);
        }
        $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `question_id` IN (" . $this->db->escape($id) . ")";

        $sqlQuery = $this->db->query($sql);

        return $sqlQuery->row();

    }
	
	public function get_answer_option_from_question_id($questionId)
    {
        $this->load->model('model_automarking');
        $this->db->where('question_id', $questionId);

        $query = $this->db->get('sj_answers');

       
        foreach ($query->result() AS $row) {
            

            $hasSIUNIT = false;
            $hasNONSIUNIT = false;
            $hasNumber = false;
            $hasChracter = false;
            $nonSIUNITTxt = "";

            $siUnit = new Model_automarking();
            $UNIT =  $siUnit->UNIT_TYPES;
            $NOT_CONSIDER_UNIT = array (
                'st','nd','rd',"th"
            );


            //added by KL
            $answerText = $row->answer_text;
            $answerText = str_replace(" :",":", $answerText);
            $answerText = str_replace(": ",":", $answerText);
            $answerText = str_replace(":"," : ", $answerText);
            $answerText = preg_replace('/(?<=[a-z|%|$])(?=\d)|(?<=\d)(?=[a-z|%])/i',' ', $answerText);


            //to split from SI UNIT
            for($i = 1 ; $i <= strlen($answerText); $i++){
                if(preg_match('/[a-z|A-Z|0-9]/',substr($answerText,-$i,1))){
                    //is a number or alphabet
                    //check if it is SI UNIT;
                    foreach($UNIT as $unitindex => $unitarray) {
                        foreach($unitarray as $unit2index => $singleUnit){
                            $strLen = strlen($answerText) - $i;
                            $unitLen = strlen($singleUnit);

                            if($strLen > $unitLen && 
                                $singleUnit == substr($answerText, -$unitLen-$i+1,$unitLen) &&
                                !preg_match('/[a-z|A-Z]/',substr($answerText,-$unitLen-$i, 1))
                                ){
                                //unit match
                                //split string & unit
                                $hasSIUNIT = true;
                            }
                        }
                    }

                    break;
                }
            }
            //check has number
            if(!$hasSIUNIT){
                $shouldStop = false;
                for($i = 1 ; $i <= strlen($answerText); $i++){
                    if(preg_match('/[0-9]/',substr($answerText,-$i,1))){
                        $hasNumber = true;
                        break;
                    }else if(preg_match('/[a-z|A-Z]/',substr($answerText,-$i,1)) && !$shouldStop){
                        $nonSIUNITTxt = substr($answerText,-$i,1).$nonSIUNITTxt;
                    }else if(preg_match('/\S/',substr($answerText,-$i,1))){
                        $shouldStop = true;
                    }
                }
                if(strlen($nonSIUNITTxt) > 0 && $hasNumber){
                    $hasNONSIUNIT = true;
                    //filter nonSIUNIT
                    if(!in_array($nonSIUNITTxt,$NOT_CONSIDER_UNIT)){
                        $answerText = preg_replace('/'.$nonSIUNITTxt.'/',"",$answerText);
                        $answerText = trim($answerText);
                    }
                }
            }
            

            $row->answer_text =  $answerText;
            $answerOptions[] = $row;
        }


        return $answerOptions;

	}
	
	public function get_correct_answer_from_question_id($questionId)

    {

        $this->db->where('question_id', $questionId);

        $query = $this->db->get('sj_correct_answer');



        $result = $query->row();

        return isset($result) ?$result->answer_id:NULL;

	}
	
	public function get_answer_text_from_answer_id($answerId)
    {
        $this->load->model('model_automarking');
        $this->db->select('answer_text');

        $this->db->from('sj_answers');

        $this->db->where('answer_id', $answerId);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->row();

            $hasSIUNIT = false;
            $hasNONSIUNIT = false;
            $hasNumber = false;
            $hasChracter = false;
            $nonSIUNITTxt = "";

            $siUnit = new Model_automarking();
            $UNIT =  $siUnit->UNIT_TYPES;
            $NOT_CONSIDER_UNIT = array (
                'st','nd','rd',"th"
            );


            //added by KL
            $answerText = $result->answer_text;
            $answerText = str_replace(" :",":", $answerText);
            $answerText = str_replace(": ",":", $answerText);
            $answerText = str_replace(":"," : ", $answerText);
            $answerText = preg_replace('/(?<=[a-z|%|$])(?=\d)|(?<=\d)(?=[a-z|%])/i',' ', $answerText);


            //to split from SI UNIT
            for($i = 1 ; $i <= strlen($answerText); $i++){
                if(preg_match('/[a-z|A-Z|0-9]/',substr($answerText,-$i,1))){
                    //is a number or alphabet
                    //check if it is SI UNIT;
                    foreach($UNIT as $unitindex => $unitarray) {
                        foreach($unitarray as $unit2index => $singleUnit){
                            $strLen = strlen($answerText) - $i;
                            $unitLen = strlen($singleUnit);

                            if($strLen > $unitLen && 
                                $singleUnit == substr($answerText, -$unitLen-$i+1,$unitLen) &&
                                !preg_match('/[a-z|A-Z]/',substr($answerText,-$unitLen-$i, 1))
                                ){
                                //unit match
                                //split string & unit
                                $hasSIUNIT = true;
                            }
                        }
                    }

                    break;
                }
            }
            //check has number
            if(!$hasSIUNIT){
                $shouldStop = false;
                for($i = 1 ; $i <= strlen($answerText); $i++){
                    if(preg_match('/[0-9]/',substr($answerText,-$i,1))){
                        $hasNumber = true;
                        break;
                    }else if(preg_match('/[a-z|A-Z]/',substr($answerText,-$i,1)) && !$shouldStop){
                        $nonSIUNITTxt = substr($answerText,-$i,1).$nonSIUNITTxt;
                    }else if(preg_match('/\S/',substr($answerText,-$i,1))){
                        $shouldStop = true;
                    }
                }
                if(strlen($nonSIUNITTxt) > 0 && $hasNumber){
                    $hasNONSIUNIT = true;
                    //filter nonSIUNIT
                    if(!in_array($nonSIUNITTxt,$NOT_CONSIDER_UNIT)){
                        $answerText = preg_replace('/'.$nonSIUNITTxt.'/',"",$answerText);
                        $answerText = trim($answerText);
                    }
                }
            }


            return $answerText;
        } else {
            return false;
        }

	}
	
	public function get_category_from_question_id($questionId)
    {

        if(is_array($questionId)){
	        $questionId = implode(",", $this->db->escape($questionId));
        }
        $sql = "SELECT `topic_id` FROM `sj_questions` WHERE `question_id` IN (".$questionId.")";

        $query = $this->db->query($sql);


        $categoryId = $query->row()->topic_id;



        if (isset($categoryId) && empty($categoryId) === false) {

            $this->db->select('name');

            $this->db->from('sj_categories');

            $this->db->where('id', $categoryId);

            $query = $this->db->get();



            return $query->row()->name;

        } else {

            return "";

        }

	}
	
	public function get_substrand_from_question_id($questionId)

    {
	    if(is_array($questionId)){
	        $questionId = implode(",", $this->db->escape($questionId));
        }
        $sql = "SELECT `topic_id` FROM `sj_questions` WHERE `question_id` IN (".$questionId.")";
        $query = $this->db->query($sql);
        

//         $this->db->select('topic_id');

//         $this->db->from('sj_questions');

//         $this->db->where('question_id', $questionId);

//         $query = $this->db->get();



        $categoryId = $query->row()->topic_id;



        if (isset($categoryId) && empty($categoryId) === false) {

            $this->db->select('substrand_id');

            $this->db->from('sj_categories');

            $this->db->where('id', $categoryId);

            $query = $this->db->get();

            $substrandId = $query->row()->substrand_id;



            if (isset($substrandId) && empty($substrandId) === false) {

                $this->db->select('name');

                $this->db->from('sj_substrands');

                $this->db->where('id', $substrandId);

                $query = $this->db->get();

                return $query->row()->name;



            } else {

                return "";

            }

        } else {

            return "";

        }

	}
	
	public function sub_question($ref_id) {
	    if(is_array($ref_id)){
		    $ref_id = implode(",", $ref_id);
	    }
        $sql = "SELECT * FROM `sj_questions` WHERE `reference_id` IN (" . $ref_id . ")";
        $query = $this->db->query($sql);
        $result = $query->result();
        if(!isset($result[1])){
	        return null;
        }
        return $result[1]->sub_question;
	}
	
	public function get_strategy_from_question_id($questionId)
    {
	    if(is_array($questionId)){
	        $questionId = implode(",", $this->db->escape($questionId));
        }
        $sql = "SELECT `strategy_id` FROM `sj_questions` WHERE `question_id` IN (".$questionId.")";
        $query = $this->db->query($sql);

        $strategyId = $query->row()->strategy_id;

        if (isset($strategyId) && empty($strategyId) === false) {
            $this->db->select('name');
            $this->db->from('sj_strategy');
            $this->db->where('id', $strategyId);
            $query = $this->db->get();

            return $query->row()->name;
        } else {
            return "";
        }
	}
	
	public function get_tutor_info($tutorId)
    {
        $sql = "SELECT *
				FROM `sj_users`  
				WHERE `branch_code` = '".BRANCH_ID."' AND `id` = " . $this->db->escape($tutorId) . "
				ORDER BY `id` DESC 
		";

        $query = $this->db->query($sql);

		if($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	
	public function update_tutor_info($user_id)
    {
        $mobileReg = "/^$|[8|9]\d{3}\s\d{4}/";
        $mobileRegs = "/^$|[8|9]\d{7}/";
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
        
        // if(!preg_match($mobileReg, $data_ar['contact_no']) && !preg_match($mobileRegs, $data_ar['contact_no'])) {
		// 	$this->session->set_flashdata('update_error', 'Please use Singapore registered mobile number starting with 8 or 9.');
		// 	redirect(base_url() . 'profile/edit');
		// }
		
        $this->db->where('id', $user_id);
        $query = $this->db->update('sj_users', $data_ar);

        // if($user_role == 2) {
        //     $sql = "UPDATE `sj_student` SET `school_id` = " . $this->input->post('student_school') . ", `level_id` = " . $this->input->post('student_level') . " WHERE `student_id` = " . $user_id;
        //     $query = $this->db->query($sql);
        // }  

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
	
	// user grid view
	public function user_listing($start) {

		$getData = $this->input->get();
		$filter         = $getData['filter'];
		$search         = $getData['search'];

		$sql_filter = '';

		$sql = "
			SELECT * FROM `sj_branch_user`
			INNER JOIN `sj_users`
			ON `sj_branch_user`.`user_id` = `sj_users`.`id`
			WHERE `sj_branch_user`.`account_type` = 'tutor' AND `sj_branch_user`.`branch_id` = ".BRANCH_ID."
			ORDER BY `sj_branch_user`.`registered_date` DESC 
		";

		if($filter == 1){
			
			if($subject_type !== ''){
				$sql_filter .= " AND a.subject_type = '$subject_type' ";
			}

			if($topic_id !== ''){
				$sql_filter .= " AND a.topic_id = '$topic_id' ";
			}

			if($strategy_id !== ''){
				$sql_filter .= " AND a.strategy_id = '$strategy_id' ";
			}

			if($substrategy_id !== ''){
				$sql_filter .= " AND a.substrategy_id = '$substrategy_id' ";
			}
			
			if(isset($getData['marks'])){                
				$sql .= " AND a.difficulty IN (".implode(",", $marks).") ";                
			}  
		}

		if($search !== ''){ 
			$sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.level_id, a.subject_type, a.question_type_id, a.difficulty, 
					a.graphical, a.question_content, a.content_type, a.topic_id, a.substrategy_id, a.strategy_id, b.branch_image_url 
					FROM sj_questions a 
					JOIN sj_branch b ON a.branch_name = b.branch_name 
					JOIN sj_categories c ON a.topic_id=c.id 
					JOIN sj_question_tag d ON a.question_id=d.question_id 
					JOIN sj_strategy e ON a.strategy_id=e.id 
					JOIN sj_substrategy f ON a.substrategy_id=f.id                       
					WHERE a.disabled=0 AND a.sub_question='A' $sql_filter                        
					AND concat('.', a.question_text, '.', c.name, '.', d.tags, '.', e.name, '.', f.name, '.') LIKE '%$search%' ";                
		}else{
			$sql = $sql.$sql_filter;
		}
		

		$result['total_rows'] = $this->db->query($sql." LIMIT 5000")->num_rows();

		if($result['total_rows'] < 1){
			$sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.level_id, a.subject_type, a.question_type_id, a.difficulty, 
			a.graphical, a.question_content, a.content_type, a.topic_id, a.substrategy_id, a.strategy_id, b.branch_image_url 
			FROM sj_questions a 
			JOIN sj_branch b ON a.branch_name = b.branch_name 
			JOIN sj_categories c ON a.topic_id=c.id 
			JOIN sj_question_tag d ON a.question_id=d.question_id                       
			WHERE a.disabled=0 AND a.sub_question='A' $sql_filter                        
			AND concat('.', a.question_text, '.', c.name, '.', d.tags, '.') LIKE '%$search%' ";

			$result['total_rows'] = $this->db->query($sql." LIMIT 5000")->num_rows();
		}

		$result['data'] = $this->db->query($sql)->result();
		return $result;

	}
}