<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questions_o_m extends CI_Model {
	
	private $fields = array(
		"output" => "Output",
	);
	
	function __construct() {
        parent::__construct();
        
		foreach($this->fields as $k => $v) {
			switch($k) {
				default:
					$this->{$v} = "";
					break;
			}
		}
	}
	
	function set($rows = false) {
        $this->Output = array();
		if(empty($rows) == true) {
			return false;
		}
		$this->Output = $rows;
		return true;
	}
	
}
?>