<?php

/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

class Model_worksheet extends CI_Model {

	public function save_worksheet($worksheetName) {
		$questionArray = $this->session->userdata('questionArray');
		
		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$userID = $this->session->userdata('admin_id');
		} else {
			$userID = $this->session->userdata('user_id');
		}
		
		$requirementId = $this->session->userdata('requirementId');

		if (!isset($questionArray) && empty($questionArray) === true) {
			redirect(base_url().'profile');
		}
		
		$req = $this->get_requirement($requirementId);

		foreach($req as $requirement) {
			if ($this->session->userdata('is_admin_logged_in') == 1) {
				$subject = $requirement->admin_subject_type;
			} else {
				$subject = $requirement->subject_type;
			}
		}

		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$insertWorksheetArray = array(
				'admin_worksheet_name' => $worksheetName,
				'admin_created_by'     => $userID,
				'admin_requirement_id' => $requirementId,
				'admin_is_parent' => 0,
				'admin_branch_tag' => BRANCH_TAG,
				'admin_subject_type' => $subject,
				'admin_branch_code' => BRANCH_ID
			);
			$query1 = $this->db->insert('sj_admin_worksheets', $insertWorksheetArray); 

		} else if ($this->session->userdata('user_role') == '3') {
			$insertWorksheetArray = array(
				'worksheet_name' => $worksheetName,
				'created_by'     => $userID,
				'requirement_id' => $requirementId,
				'is_parent' => 1,
				'branch_tag' => BRANCH_TAG,
				'subject_type' => $subject,
				'branch_code' => BRANCH_ID
			);
			$query1 = $this->db->insert('sj_worksheet', $insertWorksheetArray); 
			
		} else {
			$insertWorksheetArray = array(
				'worksheet_name' => $worksheetName,
				'created_by'     => $userID,
				'requirement_id' => $requirementId,
				'is_parent' => 0,
				'branch_tag' => BRANCH_TAG,
				'subject_type' => $subject,
				'branch_code' => BRANCH_ID
			);
			$query1 = $this->db->insert('sj_worksheet', $insertWorksheetArray); 
		} 

		
		$worksheetId = 0;

		if ($query1) {
			$worksheetId = $this->db->insert_id();
			$insertWorksheetQuestionArray = array();
			$questionNum = 1;

			if ($this->session->userdata('is_admin_logged_in') == 1) {
				foreach ($questionArray AS $questionID) {
					$question = array();
					$question['admin_worksheet_id']    = $worksheetId;
					$question['admin_question_id']     = $questionID;
					$question['admin_question_number'] = $questionNum; 
					$question['admin_branch_code'] = BRANCH_ID;
					$insertWorksheetQuestionArray[] = $question;
					$questionNum++;
				}
	
				$query2 = $this->db->insert_batch('sj_admin_worksheet_questions', $insertWorksheetQuestionArray);
			} else {
				foreach ($questionArray AS $questionID) {
				// Get Question Type by Cahyono
					$question_type = $this->get_question_type($questionID, $requirementId);
					$question = array();
					$question['worksheet_id']    = $worksheetId;
					$question['question_id']     = $questionID;
					$question['question_number'] = $questionNum; 
					$question['question_type']     = $question_type;
					$question['branch_code'] = BRANCH_ID;
					$insertWorksheetQuestionArray[] = $question;
					$questionNum++;
				}
	
				$query2 = $this->db->insert_batch('sj_worksheet_questions', $insertWorksheetQuestionArray);
			}
			
			$this->update_worksheet_requirement($requirementId, $userID);

		} else {
			return false;
		}

		$this->session->unset_userdata('requirementId');
		$this->session->unset_userdata('tempTableId');
		$this->session->unset_userdata('questionArray');
		$this->session->unset_userdata('worksheetNumOfQuestion');
		$this->session->unset_userdata('worksheetDifficulty');
		$this->session->unset_userdata('worksheetLevel');
		$this->session->unset_userdata('worksheetTopic');

		return ($query1 && $query2)?$worksheetId:false;
	}

	public function save_worksheet_requirement_tid($post) {		
		$levels = $post['gen_level'];
		$operator = implode(",", $post['gen_operator']);
		$que_type = implode(",", $post['gen_que_type']);
		$topics = implode(",", $post['gen_topic']);
		$abilities = implode(",", $post['gen_ability']);
		$quizTime = $post['gen_quiz_time'];
		$noOfQues = $post['gen_num_of_question'];
		$startQuestion = implode(",", $post["gen_start_of_question"]);
		$endQuestion = implode(",", $post["gen_end_of_question"]);
		$user_id = '0';	
		$subject = '5';
		$difficulties = '';
		for($i=0 ; $i<count($post['gen_topic']) ; $i++){
			$difficulties .= implode(",", $post['gen_difficulties_'.$i]);
		}						
		$user_id = 0;
		if ($this->session->userdata('is_logged_in') == 1) {
			$user_id = $this->session->userdata('user_id');
		}
		$insertArray = array(
			'level_ids'     => $levels,
			'topic_ids'     => $topics,
			'abilities'    => $abilities,
			'difficulty'    => $difficulties,
			'quiz_time'		=> $quizTime,
			'num_question'  => $noOfQues,
			'start_question' => $startQuestion,
			'end_question' => $endQuestion,
			'user_id'       => $user_id,
			'question_type' => $que_type,
			'operator_ability_difficulty' => $operator,
			'subject_type' => $subject,
			'branch_code' => BRANCH_ID
 		);

 		$query = $this->db->insert('sj_worksheet_requirement', $insertArray);
 		
 		if ($query) {
			$this->session->set_userdata('requirementId', $this->db->insert_id());

			$level = $post['gen_level'];			
			//$query = $this->db->get_where('sj_levels', ['level_id' => $level, 'subject_type' => $subject])->row_array();
			$level_name = $level; //$query['level_name'];
			$queType = explode(",",$que_type);
			$topic = $post['gen_topic'];
			$abilities = $post['gen_ability'];		
			$startQuestion = $post["gen_start_of_question"];
			$endQuestion = $post["gen_end_of_question"];
			$noOfQuestion = $post['gen_num_of_question'];
			$countSection = count($startQuestion);
			$requirementId = $this->session->userdata('requirementId');	
			$date_created = date('Y-m-d H:i:s');
			$this->load->model('Model_question', 'm_question');
			
			$i=1;
			for($j = 0 ; $j<count($startQuestion) ; $j++){
				$sql  = "SELECT question_id FROM sj_questions ";
				$sql .= "WHERE  tid_level='$level' ";						
				if($topic[$j] !== 'all' && $topic[$j] !== ''){
					$sql .= "AND tid_topic_id='$topic[$j]' ";							
				}else{
					$sql .= "AND topic_id IN (SELECT topic_id FROM sj_topics_tid WHERE level='$level' ORDER BY RAND()) ";											
				}
				$sql .= " AND `sj_questions`.`branch_name` = '" .BRANCH_TITLE . "' ";
															
				$sql .= "AND `disabled`=0 AND `sub_question`='A' ";
				if($queType[$j] == 1){
					$quesType = array(1, 4);
					$sql .= "AND question_type_id IN (" . implode(",", $quesType) . ") ";
				}else if($queType[$j] == 2){
					$quesType = array(1, 2);
					$sql .= "AND question_type_id IN (" . implode(",", $quesType) . ") ";
				}
				if($abilities[$j] != 'all') {
					$sql .= "AND (`ability` = '$abilities[$j]' ";
				}else $sql .= "AND (`ability` <> '' ";
				$operator_ab = ($operator[$j]==1) ? "AND" : "OR";
				$this->session->set_userdata('gen_difficulties_'.$j, $post['gen_difficulties_'.$j]);
				$sql .= $operator_ab." difficulty IN (" . implode(",", $post['gen_difficulties_'.$j]) . ")) ORDER BY RAND() LIMIT ".($endQuestion[$j]-$startQuestion[$j]+1);
				$query = $this->db->query($sql);  										
				foreach($query->result() as $row){
					$data = [
						'requirement_id' => $this->session->userdata('requirementId'),
						'question_number' => $i++,
						'question_id' => $row->question_id,								
						'question_type' => $queType[$j],
						'topic_req' => $topic[$j],
						'ability_req' => $abilities[$j],
						'operator_req' => $operator[$j],
						'difficulty_req' => implode(",", $post['gen_difficulties_'.$j]),
						'date_created' => $date_created
					];
					$this->db->insert('sj_generate_questions', $data);							
				}
			}
 		}
	}

	function get_question_type($que_id=0,$requirementId=0) {
		$this->db->select('question_type');
        $this->db->from('sj_generate_questions');
        $this->db->where('question_id', $que_id);
        $this->db->where('requirement_id', $requirementId);
        $query = $this->db->get()->row();
        if($query !== NULL) {
        	return $query->question_type;
        } else {
            return "0";
        }
	}
	
	private function get_requirement($req_id) {
		
		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$sql = "SELECT * FROM `sj_admin_worksheet_requirements` WHERE `admin_requirement_id` =" . $this->db->escape($req_id);
		} else {
			$sql = "SELECT * FROM `sj_worksheet_requirement` WHERE `requirement_id` =" . $this->db->escape($req_id);
		}
		
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}

	public function save_worksheetTID($worksheetName) {
		$questionArray = $this->session->userdata('questionArray');
		// sort($questionArray);
		$userID        = $this->session->userdata('user_id');
		$requirementId = $this->session->userdata('requirementId');
		$subject = 5; // $this->session->userdata('ExamSubject');

		if (!isset($questionArray) && empty($questionArray) === true) {
			redirect(base_url().'profile');
		}
		if($this->session->userdata('user_role') == '3') {
			$insertWorksheetArray = array(
				'worksheet_name' => $worksheetName,
				'created_by'     => $userID,
				'requirement_id' => $requirementId,
				'is_parent' => 1,
				'branch_tag' => BRANCH_TAG,
				'subject_type' => $subject,
				'branch_code' => BRANCH_ID
			);
		} else {
			$insertWorksheetArray = array(
				'worksheet_name' => $worksheetName,
				'created_by'     => $userID,
				'requirement_id' => $requirementId,
				'is_parent' => 0,
				'branch_tag' => BRANCH_TAG,
				'subject_type' => $subject,
				'branch_code' => BRANCH_ID
			);
		} 
		$query1 = $this->db->insert('sj_worksheet', $insertWorksheetArray); 
		$worksheetId = 0;
		if ($query1) {
			$worksheetId = $this->db->insert_id();
			$insertWorksheetQuestionArray = array();
			// $questionNum = 1;
			$lastQuestionTypeGenerate = '';
			foreach ($questionArray AS $key=>$questionID) {
				$question = array();
				$question['worksheet_id']    = $worksheetId;
				$question['question_id']     = $questionID;
				$questionTypeGenerate = $this->db->get_where('sj_generate_questions', ['question_id' => $questionID, 'requirement_id' => $requirementId])->row();
				$questionTypeGenerate = ($questionTypeGenerate) ? $questionTypeGenerate->question_type : $questionTypeGenerate;

			//	if($questionTypeGenerate !== NULL){
					$question['question_type'] = $questionTypeGenerate;
					$lastQuestionTypeGenerate = $questionTypeGenerate;
				// }else{
				// 	$question['question_type'] = $lastQuestionTypeGenerate;
				// }
				$question['question_number'] = $key + 1; 
				$question['branch_code'] = BRANCH_ID;
				$insertWorksheetQuestionArray[] = $question;
				$questionNum++;
			}
			$query2 = $this->db->insert_batch('sj_worksheet_questions', $insertWorksheetQuestionArray);
			$this->update_worksheet_requirement($requirementId, $userID);
		} else {
			return false;
		}
		$this->session->unset_userdata('requirementId');
		$this->session->unset_userdata('tempTableId');
		$this->session->unset_userdata('questionArray');
		$this->session->unset_userdata('worksheetNumOfQuestion');
		$this->session->unset_userdata('worksheetDifficulty');
		$this->session->unset_userdata('worksheetLevel');
		$this->session->unset_userdata('worksheetTopic');
		return ($query1 && $query2)?$worksheetId:false;
	}

	public function save_mock_exam_worksheet($worksheetName) {	
		$userID = $this->session->userdata('user_id');
		$requirementId = $this->session->userdata('meRequirementId');
		if (!isset($requirementId) && empty($requirementId) === true) {
			redirect(base_url().'profile');
		}

		$insertWorksheetArray = array(
			'worksheet_name' => $worksheetName,
			'created_by'     => $userID,
			'requirement_id' => $requirementId,
			'is_mock_exam'   => 1,		
			'branch_code' => BRANCH_ID
		);

		$query1 = $this->db->insert('sj_worksheet', $insertWorksheetArray); 
		$worksheetId = $this->db->insert_id();

		$this->session->unset_userdata('meRequirementId');
		$this->session->unset_userdata('MESelectedTutor');
		$this->session->unset_userdata('MESelectedMe');
		$this->session->unset_userdata('MESelectedYear');
		$this->session->unset_userdata('MERandomize');
		$this->session->unset_userdata('MESelectedLevel');

		return ($query1)?$worksheetId:false;
	}


	private function update_worksheet_requirement($requirementId, $userId) {
	
		if ($this->session->userdata('is_admin_logged_in') == 1) {

			$updateArray = array(
				'admin_user_id' => $userId
			);

			$this->db->where('admin_requirement_id', $requirementId);
			$this->db->where('admin_branch_code', BRANCH_ID);
			$this->db->update('sj_admin_worksheet_requirements', $updateArray);
		} else {

			$updateArray = array(
				'user_id' => $userId
			);

			$this->db->where('requirement_id', $requirementId);
			$this->db->where('branch_code', BRANCH_ID);
			$this->db->update('sj_worksheet_requirement', $updateArray);
		}
	}


	public function save_worksheet_requirement($post) {
		if($post['gen_subject'] == 7){
			$levels = 'all';
			$que_type = $post['gen_que_type'];
			$substrands = '1';
			$topics = 'all';
			$noOfQues = implode(",", $post['gen_num_of_question']);
			$user_id = '0';
			$subject = $post['gen_subject'];
			if(!isset($post['gen_strategy']) && empty($post['gen_strategy']) === true){
				$post['gen_strategy'] = "all";
			}
			$strategys = $post['gen_strategy'];
			$difficulties = 50;
		} else {
			$levels = $post['gen_level'];
			if(empty($post['gen_que_type'])) {
				$que_type = 1;
			} else {
				$que_type = $post['gen_que_type'];
			}
			
			$substrands = implode(",", $post['gen_substrand']);
			$topics = implode(",", $post['gen_topic']);
			if(is_array($post['gen_num_of_question']) == 1) {
				$noOfQues = implode(",", $post['gen_num_of_question']);
			} else {
				$noOfQues = $post['gen_num_of_question'];
			}

			$user_id = '0';	
			$subject = $post['gen_subject'];
			if(!isset($post['gen_strategy']) && empty($post['gen_strategy']) === true){
				$strategys = "all";
			}else{
				$strategys = $post['gen_strategy'];
			}
			
			$difficulties = 50;
		}

		if ($this->session->userdata('is_logged_in') == 1) {
			$user_id = $this->session->userdata('user_id');

			$insertArray = array(
				'level_ids'     => $levels,
				'topic_ids'     => $topics,
				'strategy_ids'  => $strategys,
				'substrand_ids' => $substrands,
				'difficulty'    => $difficulties,			
				'num_question'  => $noOfQues,
				'start_question' => '1',
				'end_question' => $noOfQues,
				'user_id'       => $user_id,
				'question_type' => $que_type,
				'subject_type' => $subject,
				'branch_code' => BRANCH_ID
			 );
		} else if ($this->session->userdata('is_admin_logged_in') == 1) {
			$admin_id = $this->session->userdata('admin_id');

			$insertArray = array(
				'admin_level_ids'     => $levels,
				'admin_topic_ids'     => $topics,
				'admin_strategy_ids'  => $strategys,
				'admin_substrand_ids' => $substrands,
				'admin_difficulty'    => $difficulties,			
				'admin_num_question'  => $noOfQues,
				'admin_start_question' => '1',
				'admin_end_question' => $noOfQues,
				'admin_user_id'       => $admin_id,
				'admin_question_type' => $que_type,
				'admin_subject_type' => $subject,
				'admin_branch_code' => BRANCH_ID
			 );

		} else {
			$insertArray = array(
				'level_ids'     => $levels,
				'topic_ids'     => $topics,
				'strategy_ids'  => $strategys,
				'substrand_ids' => $substrands,
				'difficulty'    => $difficulties,			
				'num_question'  => $noOfQues,
				'start_question' => '1',
				'end_question' => $noOfQues,
				'user_id'       => $user_id,
				'question_type' => $que_type,
				'subject_type' => $subject,
				'branch_code' => BRANCH_ID
			 );
		}
		
		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$query = $this->db->insert('sj_admin_worksheet_requirements', $insertArray);
		} else {
			$query = $this->db->insert('sj_worksheet_requirement', $insertArray);
		}
		
 		if ($query) {
			$this->session->unset_userdata('requirementId');
			$this->session->set_userdata('requirementId', $this->db->insert_id());
		}
		
	}

	public function save_worksheet_requirement_exam($post) {		
		if($post['gen_subject'] == 7){
			$levels = 'all';
			$que_type = implode(",", $post['gen_que_type']);
			$substrands = '1';
			$topics = 'all';
			$noOfQues = implode(",", $post['gen_num_of_question']);
			$user_id = '0';
			$subject = $post['gen_subject'];
			if(!isset($post['gen_strategy']) && empty($post['gen_strategy']) === true){
				$post['gen_strategy'] = "all";
			}
			$strategys = $post['gen_strategy'];
			$difficulties = '';
			for($i=0 ; $i<count($post['gen_substrand']) ; $i++){
				$difficulties .= implode(",", $post['gen_difficulties_'.$i]);
			}
		} else {
			$levels = $post['gen_level'];
			$que_type = implode(",", $post['gen_que_type']);					
			$substrands = implode(",", $post['gen_substrand']);
			$topics = implode(",", $post['gen_topic']);
			$quizTime = $post['gen_quiz_time'];
			$noOfQues = $post['gen_num_of_question'];
			$startQuestion = implode(",", $post["gen_start_of_question"]);
			$endQuestion = implode(",", $post["gen_end_of_question"]);
			$user_id = '0';	
			$subject = $post['gen_subject'];
			if(!isset($post['gen_heuristic']) || empty($post['gen_heuristic']) === true){
				$heuristics = "all";
			}else{
				$heuristics = implode(",", $post['gen_heuristic']);
			}
			if(!isset($post['gen_strategy']) || empty($post['gen_strategy']) === true){
				$strategys = "all";
			}else{
				$strategys = implode(",", $post['gen_strategy']);
			}
			$difficulties = '';
			for($i=0 ; $i<count($post['gen_substrand']) ; $i++){
				// $difficulties .= implode(",", $post['gen_difficulties_'.$i]);
				if($heuristics == 'all') {
					$difficulties = $post['gen_difficulties_0'];
				} else {
					$difficulties = "all";
				}
			}						
		}


		if ($this->session->userdata('is_logged_in') == 1) {
			$user_id = $this->session->userdata('user_id');
		}
		$insertArray = array(
			'level_ids'     => $levels,
			'topic_ids'     => $topics,
			'heuristic_ids'     => $heuristics,
			'strategy_ids'  => $strategys,
			'substrand_ids' => $substrands,
			'difficulty'    => $difficulties,
			'quiz_time'		=> $quizTime,
			'num_question'  => $noOfQues,
			'start_question' => $startQuestion,
			'end_question' => $endQuestion,
			'user_id'       => $user_id,
			'question_type' => $que_type,
			'subject_type' => $subject
 		);

 		$query = $this->db->insert('sj_worksheet_requirement', $insertArray);

 		if ($query) {
			$this->session->set_userdata('requirementId', $this->db->insert_id());

			$genQueBank = $post['gen_que_bank'];
			if($genQueBank == 'public'){
				$genQueBank = 0;
			}else{
				$genQueBank = 1;
			}
			$subject = $post['gen_subject'];
			$level = $post['gen_level'];			
			$query = $this->db->get_where('sj_levels', ['level_id' => $level, 'subject_type' => $subject])->row_array();
			$level_name = $query['level_name'];
			$queType = explode(",",$post['gen_que_type'][0]);			
			$substrand = $post['gen_substrand'];
			$topic = $post['gen_topic'];
			$heuristic = $post['gen_heuristic'];
			$strategy = $post['gen_strategy'];			
			$startQuestion = $post["gen_start_of_question"];
			$endQuestion = $post["gen_end_of_question"];
			$noOfQuestion = $post['gen_num_of_question'];
			$countSection = count($startQuestion);
			$countSubstrand = count($substrand);
			$requirementId = $this->session->userdata('requirementId');	
			$date_created = date('Y-m-d H:i:s');
			$this->load->model('Model_question', 'm_question');
			
				$i=1;
				for($j = 0 ; $j<count($startQuestion) ; $j++){
						$sql  = "SELECT question_id FROM sj_questions ";
						$sql .= "WHERE level_id='$level' ";						
						if($topic[$j] !== 'all' && $topic[$j] !== ''){
							$sql .= "AND (topic_id='$topic[$j]' OR topic_id2='$topic[$j]' OR topic_id3='$topic[$j]' OR topic_id4='$topic[$j]') ";							
						}else{
							if($substrand[$j] !== 'all' && $substrand[$j] !== ''){
								$sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.substrand_id='$substrand[$j]' ORDER BY RAND()) ";
							}else{
								$sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.subject_type='$subject' ORDER BY RAND()) ";
							}												
						}

						if($subject == 2){
							if($strategy[$j] !== 'all' && $strategy[$j] !== ''){							
								$sql .= "AND strategy_id='$strategy[$j]' ";
							}else{
								if($heuristic[$j] !== 'all' && $heuristic[$j] !== ''){								
									$strategy_list = $this->m_question->get_worksheet_strategy('', '', $topic[$j], $heuristic[$j], $level_name);
								}else{
									$this->db->select('a.id as strategy_id')->from('sj_strategy a')->where('a.subject_type='.$this->db->escape($subject))->order_by('RAND()');
									$strategy_list = $this->db->get()->result_array();
								}
								$strategy_id = array();
								foreach($strategy_list as $strategy_item) {
									$strategy_id[] = $strategy_item['strategy_id'];
								}
								$sql .= "AND strategy_id IN (0,".implode(",", $strategy_id).") ";
							}	
						}											
						$sql .= "AND `disabled`=0 AND `sub_question`='A' AND question_level='$genQueBank' ";
						if($queType[$j] == 1){
							$quesType = array(1, 4);
							$sql .= "AND question_type_id IN (" . implode(",", $quesType) . ") ";
						}else if($queType[$j] == 2){
							$quesType = array(1, 2);
							$sql .= "AND question_type_id IN (" . implode(",", $quesType) . ") ";
						}
						$this->session->set_userdata('gen_difficulties_'.$j, $post['gen_difficulties']);
						if($subject == 2) {
							if($strategy[$j] !== 'all' && $strategy[$j] !== ''){
								$difficulties = array(1,2,3,4,5);
							} else {
								if($post['gen_difficulties_' . $j] >= 0 && $post['gen_difficulties_' . $j] < 30) {
									$difficulties = array(1,2);
								} else if($post['gen_difficulties_' . $j] >= 30 && $post['gen_difficulties_' . $j] < 60) {
									$difficulties = array(1,2,3,4,5);
								} else {
									$difficulties = array(4,5);
								}
							}
						} else if($subject == 1) {

							if($queType[$j] == 1){
								$difficulties = array(1,2,3,4,5);
							}else {
								$difficulties = array(1,2,3,4,5);
							}

						} else if($subject == 3) {
							if($queType[$j] == 1){
								$difficulties = array(1,2,3,4,5);
							}else if($queType[$j] == 2){
								if($post['gen_difficulties'] >= 0 && $post['gen_difficulties'] < 30) {
									$difficulties = array(1,2);
								} else if($post['gen_difficulties'] >= 30 && $post['gen_difficulties'] < 60) {
									$difficulties = array(1,2,3,4,5);
								} else {
									$difficulties = array(4,5);
								}
							}
						} else {
							if($post['gen_difficulties'] >= 0 && $post['gen_difficulties'] < 30) {
								$difficulties = array(1,2);
							} else if($post['gen_difficulties'] >= 30 && $post['gen_difficulties'] < 60) {
								$difficulties = array(1,2,3,4,5);
							} else {
								$difficulties = array(4,5);
							}
						}
						
						$sql .= "AND difficulty IN (" . implode(",", $difficulties) . ") ORDER BY RAND() LIMIT ".($endQuestion[$j]-$startQuestion[$j]+1);
						$query = $this->db->query($sql);												
						foreach($query->result() as $row){
							$data = [
								'requirement_id' => $this->session->userdata('requirementId'),
								'question_number' => $i++,
								'question_id' => $row->question_id,								
								'question_type' => $queType[$j],
								'substrand_req' => $substrand[$j],
								'topic_req' => $topic[$j],
								'heuristic_req' => $heuristic[$j],
								'strategy_req' => $strategy[$j],
								'difficulty_req' => implode(",", $difficulties),
								'date_created' => $date_created
							];
							$this->db->insert('sj_generate_questions', $data);							
						}
 		}
	}
}


	public function save_mock_exam_worksheet_requirement($post) {
		$insertArray = array(
			'me_tutor'      => $post['gen_tutor'],
			'me_num'        => $post['gen_me'],
			'me_year'       => $post['gen_year'],
			'me_level'      => $post['gen_level'],
			'me_randomized' => (isset($post['gen_randomize']) && $post['gen_randomize'] == 1)?1:0,
			'user_id'       => $this->session->userdata('user_id'),
			'branch_code' => BRANCH_ID
 		);

 		$query = $this->db->insert('sj_mock_exam_worksheet_requirement', $insertArray);
 		if ($query) {
 			$this->session->set_userdata('meRequirementId', $this->db->insert_id());
 		}
	}



	public function save_existing_worksheet($worksheetId) {
		//update created time 
		$updateData = array(
			'created_date' => date('Y-m-d H:i:s')
		);
		$this->db->where('worksheet_id', $worksheetId);
		$this->db->update('sj_worksheet', $updateData);

		//delete old worksheet and question mapping , and insert new questions
		$this->db->where('worksheet_id', $worksheetId);
		$query1 = $this->db->delete('sj_worksheet_questions');

		$questionArray = $this->session->userdata('questionArray');
		$insertWorksheetQuestionArray = array();

		$questionNum = 1;

		foreach ($questionArray AS $questionID) {
			$question = array();
			$question['worksheet_id']    = $worksheetId;
			$question['question_id']     = $questionID;
			$question['question_number'] = $questionNum; 
			$question['branch_code']  = BRANCH_ID;
			$insertWorksheetQuestionArray[] = $question;
			$questionNum++;
		}

		$query2 = $this->db->insert_batch('sj_worksheet_questions', $insertWorksheetQuestionArray);

		return ($query1 && $query2)?$worksheetId:false;
	}



	public function delete_worksheet($worksheetId) {
		$delete_quiz_sql = "DELETE FROM `sj_quiz` WHERE `worksheetId` = '" . $worksheetId . "'";
		$delete_quiz_query = $this->db->query($delete_quiz_sql);
		
		if($delete_quiz_query){
			$delete_sql = "DELETE FROM `sj_worksheet_questions` WHERE `worksheet_id` = '" . $worksheetId . "'";
			$delete_query = $this->db->query($delete_sql);
			
			if($delete_query){
				$sql = "DELETE FROM `sj_worksheet` WHERE `worksheet_id` = '" . $worksheetId . "'";
				$query = $this->db->query($sql);
				return true;
			}	
		}
	}


	public function count_worksheet($userId) {
		$this->db->select('worksheet_id');
		$this->db->from('sj_worksheet');
		$this->db->where('created_by', $userId);
		$query = $this->db->get();

		return $query->num_rows();
	}


	public function get_worksheets($userId, $userRole = null) {
		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$sql = "SELECT * FROM `sj_admin_worksheets` WHERE `admin_created_by` = ".$userId." AND `admin_is_mock_exam` = 0 AND `admin_branch_code` = ".BRANCH_ID." AND `admin_branch_tag` = '".BRANCH_TAG."' AND `admin_is_parent` = 0 ORDER BY `admin_archived` ASC, `admin_created_date` DESC";
		} else if (isset($userRole) && $userRole == 3) {
			$sql = "SELECT * FROM `sj_worksheet` WHERE `created_by` = ".$userId." AND `is_mock_exam` = 0 AND `branch_code` = ".BRANCH_ID." AND `branch_tag` = '".BRANCH_TAG."' AND `is_parent` = 1 ORDER BY `archived` ASC, `created_date` DESC";
		} else {
			$sql = "SELECT * FROM `sj_worksheet` WHERE `created_by` = ".$userId." AND `is_mock_exam` = 0 AND `branch_code` = ".BRANCH_ID." AND `branch_tag` = '".BRANCH_TAG."' AND `is_parent` = 0 ORDER BY `archived` ASC, `created_date` DESC";
		}
		$query = $this->db->query($sql);
		$worksheetList = array();
		foreach ($query->result() AS $worksheet) {
			$worksheetList[] = $worksheet;
		}
		return $worksheetList;
	}


	public function get_mock_exam_worksheets($userId, $limit=null, $startFrom=null) {
	    if (isset($limit) && isset($startFrom)) {
			
			if ($this->session->userdata('is_admin_logged_in') == 1) {
				$sql = "SELECT * FROM `sj_admin_worksheets` WHERE `admin_created_by` = ? AND `admin_is_mock_exam` = 1 ORDER BY `admin_created_date` DESC LIMIT $startFrom, $limit";
			} else {
				$sql = "SELECT * FROM `sj_worksheet` WHERE `created_by` = ? AND `is_mock_exam` = 1 ORDER BY `created_date` DESC LIMIT $startFrom, $limit";
			}
            
        } else {

			if ($this->session->userdata('is_admin_logged_in') == 1) {
				$sql = "SELECT * FROM `sj_admin_worksheets` WHERE `admin_created_by` = ? AND `admin_is_mock_exam` = 1 ORDER BY `admin_created_date` DESC";
			} else {
				$sql = "SELECT * FROM `sj_worksheet` WHERE `created_by` = ? AND `is_mock_exam` = 1 ORDER BY `created_date` DESC";
			}
            
        }

		$query = $this->db->query($sql, array($userId));
		$worksheetList = array();

		foreach ($query->result() AS $worksheet) {
			$worksheetList[] = $worksheet;
		}

		return $worksheetList;
	}


	public function get_worksheet_name_from_id($worksheet_id) {	
		
		if($this->session->userdata('is_admin_logged_in') == 1) {

			$this->db->where('admin_worksheet_id', $worksheet_id);
			$query = $this->db->get('sj_admin_worksheets');
			return $query->row()->admin_worksheet_name;

		} else {

			$this->db->where('worksheet_id', $worksheet_id);
			$query = $this->db->get('sj_worksheet');
			return $query->row()->worksheet_name;
		}
		
	}
	
	public function get_archive_worksheet($worksheet_id) {
		$sql = "SELECT * FROM `sj_worksheet` WHERE `worksheet_id` = '" . $worksheet_id . "' AND `branch_code` = ".BRANCH_ID."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			return $query->row()->archived;
		}
	}

	public function get_subject_type_base_on_worksheetId($worksheet_id){
		$sql = "SELECT `subject_type` FROM `sj_worksheet` WHERE `worksheet_id` = '". $worksheet_id ."'";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			return $query->row()->subject_type;
		}
	}

	public function get_quiz_time_from_requirement($worksheet_id){
		if($this->session->userdata('is_admin_logged_in') == 1) {

			//only require for student, admin return 0
			return 0;
		} else {

			$this->db->select('requirement_id');
			$this->db->from('sj_worksheet');
			$this->db->where('worksheet_id', $worksheet_id);
			$this->db->where('branch_code', BRANCH_ID);
			$query = $this->db->get();

			if ($query) {
				$this->db->where('requirement_id', $query->row()->requirement_id);
				$requirementQuery = $this->db->get('sj_worksheet_requirement');
				return $requirementQuery->row()->quiz_time;
			}

		}
	}

	public function get_question_type_from_requirement($worksheet_id) {

		if($this->session->userdata('is_admin_logged_in') == 1) {

			$this->db->select('admin_requirement_id');
			$this->db->from('sj_admin_worksheets');
			$this->db->where('admin_worksheet_id', $worksheet_id);
			$this->db->where('admin_branch_code', BRANCH_ID);
			$query = $this->db->get();

			if ($query) {
				$this->db->where('admin_requirement_id', $query->row()->admin_requirement_id);
				$requirementQuery = $this->db->get('sj_admin_worksheet_requirements');
				return $requirementQuery->row()->admin_question_type;
			}

		} else {

			$this->db->select('requirement_id');
			$this->db->from('sj_worksheet');
			$this->db->where('worksheet_id', $worksheet_id);
			$this->db->where('branch_code', BRANCH_ID);
			$query = $this->db->get();
	
			if ($query) {
				$this->db->where('requirement_id', $query->row()->requirement_id);
				$requirementQuery = $this->db->get('sj_worksheet_requirement');
				return $requirementQuery->row()->question_type;
			}

		}
		
	}

	public function get_subject_type_from_requirement($worksheet_id) {

		$this->db->select('requirement_id');
		$this->db->from('sj_worksheet');
		$this->db->where('worksheet_id', $worksheet_id);
		$this->db->where('branch_code', BRANCH_ID);
		$query = $this->db->get();

		if ($query) {
			$this->db->where('requirement_id', $query->row()->requirement_id);
			$requirementQuery = $this->db->get('sj_worksheet_requirement');
			return $requirementQuery->row()->subject_type;
		}
	}


	public function get_worksheet_id_from_quiz_id($quizId) {
		$this->db->where('id', $quizId);
		$this->db->where('branch_code', BRANCH_ID);
		$query = $this->db->get('sj_quiz');

		return ($query->num_rows() == 1)?$query->row()->worksheetId:0;
	}


	public function get_questions_id_from_worksheets_id($worksheetId, $is_admin = NULL) {

		if (isset($is_admin) && $is_admin == TRUE) {

			$this->db->select('admin_question_id, admin_question_number');
			$this->db->from('sj_admin_worksheet_questions');
			$this->db->where('admin_worksheet_id', $worksheetId);
			$this->db->where('admin_branch_code', BRANCH_ID);
			$this->db->order_by('admin_question_number', 'ASC');
			$query = $this->db->get();

			return $query;

		} else {

			if($this->session->userdata('is_admin_logged_in') == 1) {

				$this->db->select('admin_question_id, admin_question_number');
				$this->db->from('sj_admin_worksheet_questions');
				$this->db->where('admin_worksheet_id', $worksheetId);
				$this->db->where('admin_branch_code', BRANCH_ID);
				$this->db->order_by('admin_question_number', 'ASC');
				$query = $this->db->get();
	
				return $query;
	
			} else {
	
				$this->db->select('question_id, group_id, question_number, question_type');
				$this->db->from('sj_worksheet_questions');
				$this->db->where('worksheet_id', $worksheetId);
				$this->db->where('branch_code', BRANCH_ID);
				$this->db->order_by('question_number', 'ASC');
				$query = $this->db->get();
	
				return $query;
	
			}

		}

	}


	public function get_requirement_from_worksheetId($worksheetId) {
		$this->db->select('requirement_id');
		$this->db->from('sj_worksheet');
		$this->db->where('worksheet_id', $worksheetId);
		$query = $this->db->get();

		if ($query->num_rows() >0) {
			$this->db->where('requirement_id', $query->row()->requirement_id);
			$requirementQuery = $this->db->get('sj_worksheet_requirement');
			return $requirementQuery->row();
		} else {
			return false;
		}
	}

	public function get_admin_requirement_from_worksheetId($worksheetId) {
		$this->db->select('admin_requirement_id');
		$this->db->from('sj_admin_worksheets');
		$this->db->where('admin_worksheet_id', $worksheetId);
		$query = $this->db->get();

		if ($query->num_rows() >0) {
			$this->db->where('admin_requirement_id', $query->row()->admin_requirement_id);
			$requirementQuery = $this->db->get('sj_admin_worksheet_requirements');
			return $requirementQuery->row();
		} else {
			return false;
		}
	}


	public function get_me_requirement_from_worksheetId($worksheetId) {
		$this->db->select('requirement_id');
		$this->db->from('sj_worksheet');
		$this->db->where('worksheet_id', $worksheetId);
		$this->db->where('branch_code', BRANCH_ID);
		$query = $this->db->get();

		if ($query) {
			$this->db->where('requirement_id', $query->row()->requirement_id);
			$requirementQuery = $this->db->get('sj_mock_exam_worksheet_requirement');
			return $requirementQuery->row();
		}

	}


	public function get_ownerId_from_worksheetId($worksheetId) {
		$this->db->select('created_by');
		$this->db->from('sj_worksheet');
		$this->db->where('worksheet_id', $worksheetId);
		$query = $this->db->get();
		if ($query) {
			return $query->row()->created_by;
		}

	}


	public function check_worksheet_owner($worksheetId, $userId) {
		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$dbWhere = array(
				'admin_worksheet_id' => $worksheetId,
				'admin_created_by'	   => $userId
			);
	
			$this->db->where($dbWhere);
			$query = $this->db->get('sj_admin_worksheets');
	
			return ($query->num_rows() == 1)?true:false;

		} else {
			$dbWhere = array(
				'worksheet_id' => $worksheetId,
				'created_by'	   => $userId
			);
	
			$this->db->where($dbWhere);
			$query = $this->db->get('sj_worksheet');
	
			return ($query->num_rows() == 1)?true:false;
		}
	}



	public function is_mock_exam($worksheetId) {
		$dbWhere = array(
			'worksheet_id' => $worksheetId,
			'branch_code'  => BRANCH_ID
		);

		$this->db->where($dbWhere);
		$query = $this->db->get('sj_worksheet');

		if($query->num_rows() == 0){
			return false;
		}
		return ($query->row()->is_mock_exam == 1)?true:false;
	}

	
	public function archive_worksheet($worksheetId) {
		$date = date('Y-m-d H:i:s');

		if($this->session->userdata('is_admin_logged_in') == 1) {

			$archive_quiz_sql = "UPDATE `sj_admin_worksheets` SET `admin_archived` = 1, `admin_archived_date` = '".$date."' WHERE `admin_worksheet_id` = " . $this->db->escape($worksheetId);
			$archive_quiz_query = $this->db->query($archive_quiz_sql);

		} else {

			$archive_quiz_sql = "UPDATE `sj_worksheet` SET `archived` = 1, `archived_date` = '".$date."' WHERE `worksheet_id` = " . $this->db->escape($worksheetId);
			$archive_quiz_query = $this->db->query($archive_quiz_sql);

		}
		
		
		if($archive_quiz_query){
			return true;
		} else {
			return false;
		}
		
	}
	
	public function unarchive_worksheet($worksheetId) {
		$archive_quiz_sql = "UPDATE `sj_worksheet` SET `archived` = 0 WHERE `worksheet_id` = '" . $worksheetId . "'";
		$archive_quiz_query = $this->db->query($archive_quiz_sql);
		if($archive_quiz_query){
			return true;
		} else {
			return false;
		}
	}
	
	public function count_archiveList($userId) {
		$sql = "SELECT COUNT(*) AS total FROM `sj_worksheet` WHERE `archived` = 1 AND `created_by` = " . $userId;
		$query = $this->db->query($sql);
		
		return $query->row()->total;
	}
	
	public function get_archiveList($userId, $start, $limit) {
		$data = array();
		$sql = "SELECT * FROM `sj_worksheet` WHERE `archived` = 1 AND `created_by` = " . $userId . " ORDER BY `worksheet_id` ASC LIMIT " . $start . " OFFSET " .$limit ;
		$query = $this->db->query($sql);
		
		$data['archive'] = $query;
		
		$sql_user = "SELECT `id`, `fullname` FROM `sj_users`";
		$query_user = $this->db->query($sql_user);
		
		foreach($query_user->result() as $row_user){
			$data['user'][$row_user->id] = $row_user->fullname;
		}
		
		return $data;
	}
	
	public function get_worksheet_level($lvl_id, $subject_id) {
		$sql = "SELECT `level_name` FROM `sj_levels` WHERE `level_id`=".$this->db->escape($lvl_id) . " AND `subject_type` = " . $this->db->escape($subject_id);
		$query = $this->db->query($sql);		
		if($query->result() > 0) {
			return $query->row()->level_name;
		}
	}
	
	public function get_worksheet_substrands($substr_id) {
		$sql = "SELECT `name` FROM `sj_substrands` WHERE `id`IN (".implode(",",$this->db->escape($substr_id)).")";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) {
			return $query->row()->name;
		}
	}
	
	public function get_worksheet_topics($topic_id) {
		if(is_array($topic_id) && !in_array('all', $topic_id)){
			$sql = "SELECT `name` FROM `sj_categories` WHERE `id` IN (".implode(",",$this->db->escape($topic_id)). ")";
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0){
				$result = $query->row()->name;
			}
		} else {
			$result = "Any Topic";
		}
		
		return $result;
	}

	public function get_worksheet_topics_tid($topic_id) {
		if(is_array($topic_id) && !in_array('all', $topic_id)){
			$sql = "SELECT `topic_name` FROM `sj_topics_tid` WHERE `topic_id` IN (".implode(",",$this->db->escape($topic_id)). ")";
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0){
				$result = $query->row()->topic_name;
			}
		} else {
			$result = "Any Topic";
		}
		
		return $result;
	}

	public function get_worksheet_ability_tid($ability_id='all') {
		if(is_array($ability_id) && !in_array('all', $ability_id)){
			$sql = "SELECT `ability_name` FROM `sj_ability_tid` WHERE `ability_id` IN (".implode(",",$this->db->escape($ability_id)). ")";
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0){
				$result = $query->row()->ability_name;
			} else {
				$result = "Any Ability";
			}
		} else {
			$result = "Any Ability";
		}
		
		return $result;
	}

	function getHeaderEdit($id){
        $row = $this->db->get_where('sj_generate_questions', ['id' => $id])->row_array();
        $question_id = $row['question_id'];
        $reqAbility = $row['ability_req'];
        $reqTopic = $row['topic_req'];
        $sql = "SELECT a.subject_type, b.name FROM sj_questions a, sj_subject b WHERE a.subject_type=b.id AND a.question_id='$question_id' ";
        $row = $this->db->query($sql)->row();
        
        if($reqTopic !== 'all' && $reqTopic !== ''){
            $row = $this->db->get_where('sj_topics_tid', ['topic_id' => $reqTopic])->row_array();
            $result['reqTopic'] = $row['topic_name'];
        }else{                
            $result['reqTopic'] = 'Any topic';
        }

        if($reqAbility !== 'all' && $reqAbility !== ''){
            $row = $this->db->get_where('sj_ability_tid', ['ability_id' => $row['ability']])->row_array();
            $result['reqAbility'] = ($row) ? $row['ability_name'] : 'Any ability';
        }else{                
            $result['reqAbility'] = 'Any ability';
        }

        $row = $this->db->get_where('sj_questions', ['question_id' => $question_id])->row_array();            
        if($row['difficulty'] == 1) $result['reqDifficulty'] = '1 Mark, Easy';
        if($row['difficulty'] == 2) $result['reqDifficulty'] = '2 Marks, Normal';
        if($row['difficulty'] == 3) $result['reqDifficulty'] = '3 Marks, Hard';
        if($row['difficulty'] == 4 || $row['difficulty'] == 5) $result['reqDifficulty'] = '4 Marks, Genius';            

        return $result;
    }
	
	public function get_worksheet_strategys($str_id) {
		if(isset($str_id) && $str_id != 'all' && $str_id != ''){
			$sql = "SELECT `name` FROM `sj_strategy` WHERE `id` =".$this->db->escape($str_id);
			$query = $this->db->query($sql);
			if($query->num_rows() > 0){
				$result = $query->row()->name;
			}
		} else {
			$result = "Any Strategy";
		}
		return $result;
	}

	public function get_worksheet_subject($worksheetId) {
		$sql = "SELECT `subject_type` FROM `sj_worksheet` WHERE `worksheet_id` =" . $this->db->escape($worksheetId);
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			$subId = $query->row()->subject_type;
			$sql = "SELECT * FROM `sj_subject` WHERE `id` =". $this->db->escape($subId);
			$query = $this->db->query($sql);
			$return = $query->row()->name;
		}else {
			$return = "-";
		}
		return $return;
	}

	public function get_worksheet_subject_id($worksheetId) {

		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$sql = "SELECT `admin_subject_type` FROM `sj_admin_worksheets` WHERE `admin_worksheet_id` =" . $this->db->escape($worksheetId);
			$query = $this->db->query($sql);
			if($query->num_rows() > 0) {
				$return = $query->row()->admin_subject_type;
			}else {
				$return = "-";
			}
			return $return;

		} else {
			$sql = "SELECT `subject_type` FROM `sj_worksheet` WHERE `worksheet_id` =" . $this->db->escape($worksheetId);
			$query = $this->db->query($sql);
			if($query->num_rows() > 0) {
				$return = $query->row()->subject_type;
			}else {
				$return = "-";
			}
			return $return;
		}
	}

	public function get_subject_name ($subjectId) {
		$sql = "SELECT * FROM `sj_subject` WHERE `id` =" . $this->db->escape($subjectId);
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			$result = $query->row_array();
			return $result['name'];
		}
	}

	/**
	 * Returns the tutor_id from a specific worksheet_id
	 * 
	 * @param	string	$worksheet_Id
	 */
	public function get_tutor_id_from_worksheet_id ($worksheet_Id) {
		$sql = "
			SELECT `created_by` FROM `sj_worksheet` 
			WHERE `worksheet_id`=".$worksheet_Id."
		";

		$query = $this->db->query($sql);

		if ($query) {
			return $query->result();
		}

		return NULL;
	}


	/**
	 * Returns the array of student_id from a specific worksheet_id
	 * 
	 * @param	string	$worksheet_Id
	 */
	public function get_student_id_from_worksheet_id ($worksheet_Id) {
		$sql = "
			SELECT * FROM `sj_quiz` 
			WHERE `worksheetId`=".$worksheet_Id."
		";

		$query = $this->db->query($sql);

		$returnArray = array();

		if ($query) {

			foreach ($query->result() as $student_id) {

				$returnArray[] = $student_id;
				
			}
		}
		
		return $returnArray;
	}

	

	public function get_quiz_list_from_worksheet_id ($worksheetId) {
		$sql = "
			SELECT * FROM `sj_quiz` quiz
			JOIN (SELECT `worksheet_id`, `created_by` FROM `sj_worksheet` WHERE `worksheet_id`= " . $this->db->escape($worksheetId) . " AND `branch_tag` = '".BRANCH_TAG."') worksheet
			WHERE `quiz`.`worksheetId` = `worksheet`.`worksheet_id` 
			AND `branch_tag` = '".BRANCH_TAG."' 
			ORDER BY `assignedDate` DESC
		";
		$query = $this->db->query($sql);
		$returnArray = array();

		if ($query) {
			foreach ($query->result() as $quiz) {
				$returnArray[] = $quiz;
			}
		}
		return $returnArray;
	}

	public function get_recsys_question_list($question) {
		if(is_array($question)) {
			$sql = "
				SELECT * FROM `sj_questions` 
				LEFT JOIN `sj_branch` 
				ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name`  
				WHERE `question_id` IN (" . implode(",", $question) . ")
			";
			$query = $this->db->query($sql);
			if($query->num_rows() > 0) {
				$result = $query->result();
				return $result;
			}
		}
	}

	public function get_generated_question_type($requirementId, $questionId) {

		$sql = "SELECT `question_type` FROM `sj_generate_questions` WHERE `requirement_id` = " . $this->db->escape($requirementId) . " AND `question_id` = " . $this->db->escape($questionId);
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) {
			return $query->row()->question_type;
		} else {
			return false;
		}
	}

	function regenerateAllQuestionTID($requirement_id){
            
        $row = $this->db->get_where('sj_worksheet_requirement', ['requirement_id' => $requirement_id])->row_array();
        $levelID = $row['level_ids'];
        $subjectID = $row['subject_type'];
        $query = $this->db->get_where('sj_levels_tid', ['level_code' => $levelID, 'branch_id' => BRANCH_ID])->row_array();
        $level_name = $query['level_name'];
        $substrandID = explode(",", $row['substrand_ids']);
        $topicID = explode(",", $row['topic_ids']);
        $operatorAB = explode(",", $row['operator_ability_difficulty']);
        $abilities = explode(",", $row['abilities']);            
        $difficulties = explode(",", $row['difficulty']);            
        $quizTime = $row['quiz_time'];
        $numOfQuestion = $row['num_question'];
        $startQuestion = explode(",", $row['start_question']);
        $endQuestion = explode(",", $row['end_question']);
        $questionType = explode(",", $row['question_type']);
        $date_created = date('Y-m-d H:i:s');
        $genQueBank = $this->session->userdata('TIDQueBank');
            
        $i=1;
        for($j = 0 ; $j<count($abilities) ; $j++){
            $sql  = "SELECT question_id FROM sj_questions ";
            $sql .= "WHERE tid_level='$levelID' ";
            if($topicID[$j] !== 'all' && $topicID[$j] !== ''){
                $sql .= "AND tid_topic_id='$topicID[$j]' ";
            }else{
            	$sql .= "AND tid_topic_id>=0 ";
			}
            $sql .= " AND `sj_questions`.`is_tid` = 1 AND `sj_questions`.`branch_name` = '" .BRANCH_TITLE . "'";
                                                      
            $sql .= "AND `disabled`=0 AND `sub_question`='A' ";
            if($questionType[$j] == 1){
                $sql .= "AND question_type_id IN (1,4) ";
            }else if($questionType[$j] == 2){
                $sql .= "AND question_type_id IN (1,2) ";
            }
            if($abilities[$j] !== 'all' && $abilities[$j] !== ''){
                $sql .= "AND (ability='$abilities[$j]' ";
            }else{
            	$sql .= "AND (ability>=0 ";
			}
			$operator = ($operatorAB[$j]==1 || $operatorAB[$j]==0) ? "AND" : "OR";
            $sql .= $operator." difficulty IN (" . implode(",", $this->session->userdata('gen_difficulties_'.$j)) . ") ) ORDER BY RAND() LIMIT ".($endQuestion[$j]-$startQuestion[$j]+1);
            $query = $this->db->query($sql);
            foreach($query->result() as $row){
                $data = [
                    'question_id' => $row->question_id
                ];
                $this->db->where('requirement_id', $requirement_id);
                $this->db->where('question_number', $i++);
                $this->db->update('sj_generate_questions', $data);
            }
        }
        
        return $this->db->affected_rows();

    }


}