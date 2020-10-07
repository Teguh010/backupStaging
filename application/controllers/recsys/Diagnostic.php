<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnostic extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->library('api');
		$this->load->library('jwt');
		$this->load->model('model_users');
    }
    
    function index() {
		// use get method 
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/diagnostic_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// use diagnostic_m to get the diagnostic questions
		$status = $this->diagnostic_m->get_question($info);

		// get the session
		$sess = $this->session->userdata();
		// $sessId = $this->session->userdata('user_id');
		// $userInfo = $this->model_users->get_user_info($sessId);
		// $username = $userInfo->username;
		

		// create jwt token and pass to header
		if(array_key_exists("user_id", $sess)) {
			$user_id = $this->session->userdata('user_id');
			$data = array(
				// 'username' => $username
				'username' => 'klho'
			);
			$key = "Sm@rtJen2@!9!@#";
			$token = $this->jwt->encode($data, $key);
			header("Authorization: Bearer " . $token);
		} else {
			header("HTTP/1.1 401 Unauthorized");
		}
		
		// return api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);
	}
	

	function get_diagnostic_test_questions() {
		// use get method 
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/diagnostic_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// use diagnostic_m to get the diagnostic questions
		$status = $this->diagnostic_m->get_diagnostic_test_questions_m($info);

		// get the session
		$sess = $this->session->userdata();
		// $sessId = $this->session->userdata('user_id');
		// $userInfo = $this->model_users->get_user_info($sessId);
		// $username = $userInfo->username;
		

		// create jwt token and pass to header
		if(array_key_exists("user_id", $sess)) {
			$user_id = $this->session->userdata('user_id');
			$data = array(
				// 'username' => $username
				'username' => 'klho'
			);
			$key = "Sm@rtJen2@!9!@#";
			$token = $this->jwt->encode($data, $key);
			header("Authorization: Bearer " . $token);
		} else {
			header("HTTP/1.1 401 Unauthorized");
		}
		
		// return api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);
    }
}