<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api {
	protected $_ci;
	protected $rest_data = null;
	function __construct() {
		$this->_ci = & get_instance();
	}
	
	function __destruct(){
		foreach($_FILES as $k => $v) {
			if(isset($_FILES[$k]["tmp_name"]) && file_exists($_FILES[$k]["tmp_name"])) {
				@unlink($_FILES[$k]["tmp_name"]);
			}
		}
	}
	
	function get_rest_data() {
		if($this->rest_data != null) {
			return $this->rest_data;
		} 
		$headers = apache_request_headers();
		
		$show = array(
			"method" => "GET",
			"data" => array()
		);
		
		if(!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] == "GET") {
			
			$show["method"] = "GET";
			$show["data"] = $_GET;
		} else if($_SERVER['REQUEST_METHOD'] == "POST") {
			
			$show["method"] = "POST";
			$show["data"] = $_POST;
			
			$tmp_json = file_get_contents("php://input");
			if($this->isJson($tmp_json)) {
				$tmp_json = json_decode($tmp_json);
				if(is_object($tmp_json)) {
					$tmp_json = $this->object_to_array($tmp_json);
				}
				if(is_array($tmp_json))
					$show["data"] = array_merge($show["data"], $tmp_json);
			} 
			
		} else if($_SERVER['REQUEST_METHOD'] == "DELETE") {
			
			$show["method"] = "DELETE";
			if(isset($_GET))
				$show["data"] = array_merge($show["data"], $_GET);
			$tmp_json = file_get_contents("php://input");
			if(is_string($tmp_json) && $this->isJson($tmp_json)) {
				$tmp_json = json_decode($tmp_json);
				if(is_object($tmp_json)) {
					$tmp_json = $this->object_to_array($tmp_json);
				}
				if(is_array($tmp_json))
					$show["data"] = array_merge($show["data"], $tmp_json);
			} else {
				parse_str($tmp_json, $tmp_json);
				if(is_array($tmp_json)) {
					$show["data"] = array_merge($show["data"], $tmp_json);
				}
			}
		} else {
			$show["method"] = strtoupper($_SERVER['REQUEST_METHOD']);
			
			$tmp_json = file_get_contents("php://input");
			if(is_string($tmp_json) && $this->isJson($tmp_json)) {
				$tmp_json = json_decode($tmp_json);
				if(is_object($tmp_json)) {
					$tmp_json = $this->object_to_array($tmp_json);
				}
				if(is_array($tmp_json))
					$show["data"] = array_merge($show["data"], $tmp_json);
			} else {
				parse_str($tmp_json, $tmp_json);
				if(is_array($tmp_json)) {
					$show["data"] = array_merge($show["data"], $tmp_json);
				}
			}
		}
		
		$this->rest_data = $show;
		return $this->rest_data;
	}
	
	function method_required($method="GET") {
		$data = $this->get_rest_data();
		if($data["method"] != strtoupper($method)) {
			api_return("Page not found.", 404);
		}
	}
	
	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	
	function object_to_array($data) {
		if(is_object($data)) {
			$data = (array) $data;
		} 
		if(is_array($data)) {
			foreach($data as $k => $v) {
				if(is_object($data[$k])) {
					$data[$k] = $this->object_to_array($data[$k]);
				}
			}
		}
		return $data;
	}
}

?>