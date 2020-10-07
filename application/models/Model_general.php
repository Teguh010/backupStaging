<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



class Model_general extends CI_Model {

	public function submit_feedback($feedback_email, $feedback_name, $feedback_type, $feedback_comment) {

		$data_ar = array(

			'feedback_sender_email'    => $feedback_email,

			'feedback_sender_name' => $feedback_name,

			'feedback_type' => $feedback_type,

			'feedback_comment' => $feedback_comment,

		);



		$query = $this->db->insert('sj_feedback', $data_ar); 



		return $query?true:false;

	}

	function getAllData($table,$order="id",$ad="desc",$where="",$a="*") {
		$data = array();
		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$Q = $this->db->query("select {$a} from {$table} {$where} order by {$order} {$ad}");
		if($Q->num_rows() > 0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[] = $row;
			}
		}
		$Q->free_result();
		return $data;
	}
	
	function getTotalData($table,$where="")
	{
		$Q = $this->db->query("select count(*) as tot from {$table} {$where}");
		$data = $Q->row();
		return $data->tot;
	}
	
	function getQuery($sql) {
		$data = array();
		$Q = $this->db->query("{$sql}");
		if($Q->num_rows() > 0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[] = $row;
			}
		}
		$Q->free_result();
		return $data;
	}
	
	
	function getAllDataPerpage($table, $num, $offset, $order="id", $ad="desc")
	{
		$data = array();
		$offset = ($offset=='') ? 0 : $offset;
		$Q = $this->db->query("select * from {$table} order by {$order} {$ad} limit {$offset}, {$num}");
		if($Q->num_rows() > 0)
		{
			foreach($Q->result_array() as $row)
			{
				$data[] = $row;
			}
		}
		$Q->free_result();
		return $data;
	}
	
	function getCountData($table,$where="")
	{
		$Q = $this->db->query("select count(*) as tot from {$table} {$where}");
		$data = $Q->row();
		return $data->tot;
	}
	
	function getSumData($table,$field,$where="") {
		$Q = $this->db->query("select sum({$field}) as tot from {$table} {$where}");
		$data = $Q->row();
		return $data->tot;
	}
		
	function getData($table, $field="id", $id, $sel="*")
	{
		$Q = $this->db->query("select {$sel} from {$table} where {$field}='{$id}'");
		$data = $Q->row();
		return $data;
	}
	
	function getDataWhere($table, $where="",$limit="limit 1",$sel="*")
	{
		$Q = $this->db->query("select {$sel} from {$table} {$where} {$limit}");
		$data = $Q->row();
		return $data;
	}

}