<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questions_m extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->model('Model_users', 'm_user');
        $this->load->model('model_question');
    }
	
	// Check if TID is key for LID
	function get_question($data=array()) {

        $data_array = array();
        
        $sql = "
            SELECT *  FROM `sj_questions` 
            WHERE `level_id` = " . $this->db->escape($data["lid"]) . "
            AND `topic_id` = " . $this->db->escape($data["tid"]) . "
            AND `strategy_id` = " . $this->db->escape($data["sid"]) . "
            AND `disabled` = 0 
            AND `sub_question` = 'A'
            ORDER BY RAND() LIMIT " . $data["number_question_per_SID"]
        ;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            foreach($query->result() as $row) {

                $ques_id = $row->question_id;
    
                $data_array[] = $ques_id;
    
                
            }
        } else {
            //$data_array[] = false;
        }

        
        
		$o_model = "questions_o";
		$count = 0;
		do {
			$o_model = "questions_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/questions_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }
    

    function generate_questions_randomly_m($data=array()) {
        $data_array = array();
        
        $sql = "
            SELECT *  FROM `sj_questions` 
            WHERE `level_id` = " . $this->db->escape($data["lid"]) . "
            AND `topic_id` = " . $this->db->escape($data["tid"]) . "
            AND `strategy_id` = " . $this->db->escape($data["sid"]) . "
            AND `disabled` = 0 
            AND `sub_question` = 'A'
            ORDER BY RAND() LIMIT " . $data["num_questions"]
        ;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            foreach($query->result() as $row) {

                $ques_id = $row->question_id;
    
                $data_array[] = $ques_id;
    
                
            }
        } else {
            //$data_array[] = false;
        }

        
        
		$o_model = "questions_o";
		$count = 0;
		do {
			$o_model = "questions_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/questions_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }

    function generate_questions_randomly_from_lid_m($data=array()) {
        $data_array = array();
        
        $sql = "
            SELECT *  FROM `sj_questions` 
            WHERE `level_id` = " . $this->db->escape($data["lid"]) . "
            AND `disabled` = 0 
            AND `sub_question` = 'A'
            ORDER BY RAND() LIMIT " . $data["num_questions"]
        ;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            foreach($query->result() as $row) {

                $ques_id = $row->question_id;
    
                $data_array[] = $ques_id;
    
                
            }
        } else {
            //$data_array[] = false;
        }

        
        
		$o_model = "questions_o";
		$count = 0;
		do {
			$o_model = "questions_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/questions_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }

    function generate_questions_equally_from_sids_m($data=array()) {
        $data_array = array();
        
        if(is_array($data["sid"])) {
            $sid = explode(',', $data["sid"]);
        } else {
            $sid = $data["sid"];
        }

        $sql = "
            SELECT *  FROM `sj_questions` 
            WHERE `strategy_id` IN (" . $this->db->escape($sid) . ")
            AND `disabled` = 0 
            AND `sub_question` = 'A'
            ORDER BY RAND() LIMIT " . $data["num_questions"]
        ;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            foreach($query->result() as $row) {

                $ques_id = $row->question_id;
    
                $data_array[] = $ques_id;
    
                
            }
        } else {
            //$data_array[] = false;
        }

        
        
		$o_model = "questions_o";
		$count = 0;
		do {
			$o_model = "questions_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/questions_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }

    function get_diagnostic_test_questions_m($data=array()) {
        $data_array = array();
        
        $sql = "
            SELECT *  FROM `sj_questions`
            WHERE `disabled` = 0 
            AND `sub_question` = 'A'
            ORDER BY RAND() LIMIT " . $data["num_questions"]
        ;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            foreach($query->result() as $row) {

                $ques_id = $row->question_id;
    
                $data_array[] = $ques_id;
    
                
            }
        } else {
            //$data_array[] = false;
        }

        
        
		$o_model = "questions_o";
		$count = 0;
		do {
			$o_model = "questions_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/questions_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }

    function generate_questions_randomly_from_kps_m($data=array()) {
        $data_array = array();
        
        $sql = "
            SELECT *  FROM `sj_questions` 
            WHERE `level_id` = " . $this->db->escape($data["lid"]) . "
            AND `topic_id` = " . $this->db->escape($data["tid"]) . "
            AND `strategy_id` = " . $this->db->escape($data["sid"]) . "
            AND `disabled` = 0 
            AND `sub_question` = 'A'
            ORDER BY RAND() LIMIT " . $data["num_questions"]
        ;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            foreach($query->result() as $row) {

                $ques_id = $row->question_id;
    
                $data_array[] = $ques_id;
    
                
            }
        } else {
            //$data_array[] = false;
        }

        
        
		$o_model = "questions_o";
		$count = 0;
		do {
			$o_model = "questions_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/questions_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }

    function get_weakest_kps_m($data=array()) {
        $data_array = array();

        $performance = $this->get_performance(array($data["student_id"]), 2);

        $result = array();
        
        $student_info = $this->m_user->get_student_info($data["student_id"]);

        $student_lvl = $student_info->level_id;

        foreach($performance as $key=>$first_lvl){
            
            $heuristic = $first_lvl['heuristic'];
            $data_result = array();
            foreach($heuristic as $second_lvl) {

                if(is_array($second_lvl)) {
                    $percentage = $second_lvl['percentage'];
                    
                    if($percentage < 30) {
                        $data_result['SID'] = $second_lvl['strategy_id'];
                        $data_result['LID'] = $student_lvl;
                        $data_result['TID'] = $first_lvl['category_id'];
                    } else {
                        $data_result['SID'] = "";
                        $data_result['LID'] = $student_lvl;
                        $data_result['TID'] = "";    
                    }
                } else {
                    $data_result['SID'] = "";
                    $data_result['LID'] = $student_lvl;
                    $data_result['TID'] = "";
                }
                
            }

            $result[] = $data_result;
            
        }

        $result_array = array();
        for($i=0; $i < $data['num_kp']; $i++) {

            $result_array["SID"] = $result[$i]["SID"];
            $result_array["LID"] = $result[$i]["LID"];
            $result_array["TID"] = $result[$i]["LID"];

            $data_array[] = $result_array;
        }
        
		$o_model = "questions_o";
		$count = 0;
		do {
			$o_model = "questions_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/questions_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }


    private function get_analysis_structure($subject = NULL)
    {
        $strands = array();
        
        $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `subject_type` = 2";

        $categories_sql_query = $this->db->query($categories_sql);

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
                $heuristic[$value->name] = $heu;
                
            }

            $category_object['category_id'] = $category_id;
            $category_object['total_attempt'] = 0;
            $category_object['total_correct'] = 0;
            $category_object['heuristic'] = $heuristic;
            $strands[$category_name] = $category_object;
        }

        return $strands;

    }

    public function get_performance($studentIdArray, $subject = NULL, $date_range = array(), $worksheetIdArray = array())
    {
      
        $category_structure = $this->get_category_structure($subject);
        $analysis_structure = $this->get_analysis_structure($subject);
        $strategy_structure = $this->model_question->get_strategy_structure($subject);
        $strand_structure = $this->get_strand_structure($subject);
        $student_performance = array();
        foreach ($studentIdArray as $student_id) {
            $analysis_structure = $this->get_analysis_structure($subject);
            // $analysis_structure['student_id'] = $student_id;
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
                            
                            $analysis_structure[$question_category_name]['total_attempt'] += $detail->difficulty;

                            if(isset($analysis_structure[$question_category_name]['heuristic'][$question_strategy])){

                                $analysis_structure[$question_category_name]['heuristic'][$question_strategy]['total_attempt'] += $detail->difficulty;

                            }

                            if($generated_question_type === '1'){
	                            if ($userAnswer == $correctAnswer) {
                                    $analysis_structure[$question_category_name]['total_correct'] += $detail->difficulty;
                                    if(isset($analysis_structure[$question_category_name]['heuristic'][$question_strategy])){
                                        $analysis_structure[$question_category_name]['heuristic'][$question_strategy]['total_correct'] += $detail->difficulty;
                                    }
	                            }
                            } else {
                                    $analysis_structure[$question_category_name]['total_correct'] += $userScore;
                                    
                                    if(isset($analysis_structure[$question_category_name]['heuristic'][$question_strategy])){
                                        $analysis_structure[$question_category_name]['heuristic'][$question_strategy]['total_correct'] += $userScore;
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
            
            foreach ($strand_structure['category'] as $strand) {
                $category_name = $strand['name'];
                if ($analysis_structure[$category_name]['total_attempt'] != 0) {
                    $category_percentage = round($analysis_structure[$category_name]['total_correct'] / $analysis_structure[$category_name]['total_attempt'], 2) * 100;
                    $analysis_structure[$category_name]['percentage'] = $category_percentage;
                } else {
                    $analysis_structure[$category_name]['percentage'] = 0;
                }
                
                foreach($strand['heuristic'] as $heuristic){
                    $strategy_id = $heuristic['strategy_name'];
                    if ($analysis_structure[$category_name]['heuristic'][$strategy_id]['total_attempt'] != 0) {
                        $strategy_percentage = round($analysis_structure[$category_name]['heuristic'][$strategy_id]['total_correct'] / $analysis_structure[$category_name]['heuristic'][$strategy_id]['total_attempt'], 2) * 100;
                        $analysis_structure[$category_name]['heuristic'][$strategy_id]['percentage'] = $strategy_percentage;
                    } else {
                        $analysis_structure[$category_name]['heuristic'][$strategy_id]['percentage'] = 0;
                    }
                }

            }
            
            $student_performance = $analysis_structure;
        }

        return $student_performance;
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

    function get_strand_structure($subject = NULL)
    {
        $strands = array();

        $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `subject_type` = 2";

        $categories_sql_query = $this->db->query($categories_sql);

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
                $heuristic[$value->strategy_id] = $heu;
                
            }

            $category_object['id'] = $category_id;

            $category_object['name'] = $category_name;

            $category_object['heuristic'] = $heuristic;

            $strands['category'][] = $category_object;

        }

        return $strands;

    }
}
?>