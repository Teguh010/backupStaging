<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_o_m extends CI_Model {
	
	public $ErrorCode = 401;
	public $ErrorMessage = "Unauthorized";
	public $ErrorDescription  = "Unauthorized";
	
	function __construct() {
		parent::__construct();
	}
	
	function template() {
		return $this;
	}
	
	function set($data = array()) {
		
		foreach($data as $k => $v) {
			if(isset($this->{$k}))
				$this->{$k} = $v;
		}
		
		return true;
	}
	
	
	
	
	
}
?>