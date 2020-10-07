<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imath_m extends CI_Model {
	
	// Check if TID is key for LID
	function get_question($data=array()) {
        $this->load->model('model_question');

        $data_array = array();
        
        $sql1 = "SELECT * FROM  `sj_questions` WHERE `question_id` = ".$this->db->escape($data['Question_id'])." LIMIT 1";
        $query1 = $this->db->query($sql1);
        
        if($query1->num_rows()>0){
            foreach($query1->result() as $row1) {
                $t_id = $row1->topic_id;
                $l_id = $row1->level_id;
            }

            if(!isset($data["num_questions"])){
                $data["num_questions"] = 10;
            }

            $sql = "
                SELECT *  FROM `sj_questions` 
                WHERE `level_id` = " . $l_id . "
                AND `topic_id` = " . $t_id . "
                AND `question_id` != " . $this->db->escape($data['Question_id']) . "
                ORDER BY RAND() LIMIT " . $data["num_questions"]
            ;

            $query = $this->db->query($sql);

            if($query->num_rows() > 0) {
                foreach($query->result() as $row) {
                    $row_data['questionType'] = $row->question_type_id;
					$row_data['questionText'] = $row->question_text;
                    $row_data['answerOption'] = $this->model_question->get_answer_option_from_question_id($row->question_id);
                    $row_data['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($row->question_id);
                    // $ques_text = $row->question_text;
                    $data_array[] = $row_data;
                }
            } 
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
}
?>