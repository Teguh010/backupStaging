<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tid_m extends CI_Model {
	
	// Check if TID is key for LID
	function check($data=array()) {

		$data_array = array();

        $stu_lvl = 'primary_level_' . $data["lid"];

        $sql = "SELECT * FROM `sj_categories` WHERE `id` =" . $this->db->escape($data["tid"]);

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {
            if(array_key_exists($stu_lvl, $row)) {
                $data = $row->$stu_lvl == '1' ? TRUE : FALSE;
            }else {
                $data = FALSE;
            }

		}
		
		$data_array['isKeyLID'] = $data;
        
		$o_model = "tid_o";
		$count = 0;
		do {
			$o_model = "tid_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/tid_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
	}


	// return list of parent KPs of the input kps
	function get_parent_kps_m($data=array()) {

		$data_array = array();
        
        $tid = $data["tid"];

        $sql = "SELECT * FROM `sj_parent_kps` WHERE `topic_id` = " . $this->db->escape($tid);

        $query = $this->db->query($sql);
		
        if($query->num_rows() > 0) {
			$result = array();

			if(empty($query->row()->topic_parent_1) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_1;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_2) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_2;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_3) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_3;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_4) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_4;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_5) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_5;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_6) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_6;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_7) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_7;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_8) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_8;

				$data_array[] = $result;
			}
			
			if(empty($query->row()->topic_parent_9) == FALSE) {
				$result['sid'] = $data['sid'];

				$result['lid'] = $data['lid'];
	
				$result['tid'] = $query->row()->topic_parent_9;

				$data_array[] = $result;
			}
			
			$data_array[] = $result;

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
}
?>