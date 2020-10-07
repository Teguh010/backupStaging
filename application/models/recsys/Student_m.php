<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_m extends CI_Model {
	
	// Check if TID is key for LID
	function check($data=array()) {

        $data_array = array();
        
        $sql = "
        SELECT * FROM `sj_users` users LEFT JOIN `sj_student` stu 
        ON `stu`.`student_id` = `users`.`id` 
        WHERE `users`.`id` =" . $this->db->escape($data["student_id"]);

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $date = explode(" ",$row->registered_date);

            $start_date = new DateTime($date[0]);

            $end_date = new DateTime(date('Y-m-d'));

            $time_diff = date_diff($start_date, $end_date);

            $data_array['isNew'] = ($time_diff->y > 0 || $time_diff->m > 3) ? false : true;

            $start_year = explode("-", $date[0])[0];

            $end_year = date('Y');

            $year_diff = $end_year - $start_year;

            $data_array['isPromotedFromLastYear'] = $year_diff > 0 ? true : false;

            $lvl_id = $row->level_id;

            $data_array['currentLID'] = $lvl_id;

            if($lvl_id === '1'){

                $data_array['previousLIDs'] = " ";

            } else {

                for ($i = $lvl_id - 1; $i >= 1; $i--) {
                    $prev_lvl[] = $i;
    
                    $data_array['previousLIDs'] = $prev_lvl;
                }
            }

            
        }
        
		$o_model = "student_o";
		$count = 0;
		do {
			$o_model = "student_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/student_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
		return $this->$o_model;
    }
    

    // 11th August : Check if a student is promoted from last year
	// return true if the student is promoted from last year
    function new_student_m ($data=array()) {

        $data_array = array();
        
        $sql = "
        SELECT * FROM `sj_users` users LEFT JOIN `sj_student` stu 
        ON `stu`.`student_id` = `users`.`id` 
        WHERE `users`.`id` =" . $this->db->escape($data["student_id"]);

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $date = explode(" ",$row->registered_date);

            $start_date = new DateTime($date[0]);
            
            $end_date = new DateTime(date('Y-m-d'));

            $time_diff = date_diff($start_date, $end_date);
            
            $data_array = ($time_diff->y > 0 || $time_diff->m > 3) ? 'false' : 'true';
        }
        
		$o_model = "student_o";
		$count = 0;
		do {
			$o_model = "student_o" . $count++;
		} while (is_model_loaded($o_model));
        $this->load->model("recsys/object/student_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
        return $this->$o_model;
        
    }


    // 11th August : Check if a student is promoted from last year
	// return true if the student is promoted from last year
    function student_promoted_from_last_year_m ($data=array()) {

        $data_array = array();
        
        $sql = "
        SELECT * FROM `sj_users` users LEFT JOIN `sj_student` stu 
        ON `stu`.`student_id` = `users`.`id` 
        WHERE `users`.`id` =" . $this->db->escape($data["student_id"]);

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $date = explode(" ",$row->registered_date);

            $start_year = explode("-", $date[0])[0];

            $end_year = date('Y');

            $year_diff = $end_year - $start_year;

            $data_array = $year_diff > 0 ? 'true' : 'false';
        }
        
		$o_model = "student_o";
		$count = 0;
		do {
			$o_model = "student_o" . $count++;
		} while (is_model_loaded($o_model));
        $this->load->model("recsys/object/student_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
        return $this->$o_model;
        
    }

    // 11th August : Get the current LID of a student
	// return the current LID of the student
    function get_current_level_of_student_m ($data = array()) {

        $data_array = array();
        
        $sql = "
        SELECT * FROM `sj_users` users LEFT JOIN `sj_student` stu 
        ON `stu`.`student_id` = `users`.`id` 
        WHERE `users`.`id` =" . $this->db->escape($data["student_id"]);

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $lvl_id = $row->level_id;

            $data_array = $lvl_id;

            
        }
        
		$o_model = "student_o";
		$count = 0;
		do {
			$o_model = "student_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/student_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
        return $this->$o_model;
        
    }

    // 11th August : Get the weakest KPs of a student
	// return the array of weakest KPs of the student
    function get_weakest_kps_m ($data = array()) {

        $data_array = array();
        
        $sql = "
        SELECT * FROM `sj_users` users LEFT JOIN `sj_student` stu 
        ON `stu`.`student_id` = `users`.`id` 
        WHERE `users`.`id` =" . $this->db->escape($data["student_id"]);

        $query = $this->db->query($sql);

        foreach($query->result() as $row) {

            $lvl_id = $row->level_id;

            $data_array = $lvl_id;

            
        }
        
		$o_model = "student_o";
		$count = 0;
		do {
			$o_model = "student_o" . $count++;
		} while (is_model_loaded($o_model));
		$this->load->model("recsys/object/student_o_m", $o_model);
		$this->$o_model->set($data_array);
		
		if(isset($this->$o_model->fields))
			unset($this->$o_model->fields);
        return $this->$o_model;
        
    }
}
?>