<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neighbour_m extends CI_Model {
	
	// Check if TID is key for LID
	function get_lid($data=array()) {

        $data_array = array();
        
        $sql = "SELECT * FROM `sj_levels` WHERE `level_id` >" . $this->db->escape($data["lid"]) . " LIMIT 1";

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $next_lvl = $row->level_id < 7 ? $row->level_id : false;

            $data_array['neighbourLID'] = $next_lvl;

            
        }
        
		$o_model = "neighbour_o";
		$count = 0;
		do {
			$o_model = "neighbour_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/neighbour_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }
    
    // Check if TID is key for LID
	function get_sid($data=array()) {

        $data_array = array();

        $stu_lvl = 'primary_level_' . $data["lid"];
        
        $sql = "
        SELECT DISTINCT(`hr`.`strategy_id`), `str`.`name` FROM `sj_heuristic_relationship` hr 
        LEFT JOIN `sj_strategy` str 
        ON `str`.`id` = `hr`.`strategy_id` 
        WHERE `hr`.`topic_id` = " . $this->db->escape($data["tid"]) . "
        AND `hr`.`" . $stu_lvl . "` = 1
        LIMIT " . $data["num_SIDs"]
        ;

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $str_name = $row->strategy_id;

            $data_array[] = $str_name;
            
        }
        
		$o_model = "neighbour_o";
		$count = 0;
		do {
			$o_model = "neighbour_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/neighbour_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }
    

    function get_neighboring_sids_m($data=array()) {

        $data_array = array();

        $stu_lvl = 'primary_level_' . $data["lid"];
        
        $sql = "
            SELECT `strategy_id` FROM `sj_heuristics_relation`
            WHERE `topic_id` = " . $this->db->escape($data["tid"]) . "
            AND " . $stu_lvl . " = 1
        ";

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $str_name = $row->strategy_id;

            $data_array[] = $str_name;
            
        }
        
		$o_model = "neighbour_o";
		$count = 0;
		do {
			$o_model = "neighbour_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/neighbour_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
	}
}
?>