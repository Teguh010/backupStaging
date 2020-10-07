<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnostic_m extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->model('Model_users', 'm_user');
    }
	
	// Check if TID is key for LID
	function get_question($data=array()) {

        $data_array = array();

        $sql = "SELECT * FROM `sj_questions` WHERE `level_id` = " . $this->db->escape($data["lid"]) . " AND `topic_id` =" . $this->db->escape($data["tid"]) . " ORDER BY RAND() LIMIT 10";

        $query = $this->db->query($sql);

        $array2 = array();

        foreach($query->result() as $row) {
            $array2[] = $row->question_id;
        }

        // $array3 = array_intersect($array1, $array2);

        // if(isset($array3) && empty($array3) === true)
        //     $data_array[] = false;

        foreach($array2 as $k=>$v) {
            $data_array[] = $v;
        }
        
		$o_model = "diagnostic_o";
		$count = 0;
		do {
			$o_model = "diagnostic_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/diagnostic_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }

    // Check if TID is key for LID
	function get_diagnostic_test_questions_m($data=array()) {
        
        $current_week = date('W');

        $data_array = array();

        $student_id = $data['student_id'];

        $student_info = $this->m_user->get_student_info($student_id);

        $student_lvl = $student_info->level_id;

        $stu_lvl = 'tid_timeline_' . $student_lvl;

        if($current_week > 12) {

            $end_week = date('W');

            $start_week = $end_week - 12;

            $week_range = range($start_week, $end_week);

        } else {

            $end_week = $date('W');

            $week_range= range(1, $end_week);

        }

        $sql = "SELECT " . $stu_lvl . " FROM `sj_timeline_lid_tid` WHERE `week` IN (" . implode(",", $week_range) . ")";

        $query = $this->db->query($sql);

        $tid_range = array();

        foreach($query->result() as $row) {

            $tid_range[] = $row->$stu_lvl;

        }

        $sql = "SELECT * FROM `sj_questions` WHERE `topic_id` IN(" . implode(",", $tid_range) . ") ORDER BY RAND() LIMIT " . $data["num_questions"];

        $query = $this->db->query($sql);

        $array2 = array();

        foreach($query->result() as $row) {

            $array2[] = $row->question_id;
            
        }

        // $array3 = array_intersect($array1, $array2);

        // if(isset($array3) && empty($array3) === true)
        //     $data_array[] = false;

        foreach($array2 as $k=>$v) {
            $data_array[] = $v;
        }
        
		$o_model = "diagnostic_o";
		$count = 0;
		do {
			$o_model = "diagnostic_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/diagnostic_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }
}
?>