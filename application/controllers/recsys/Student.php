<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->library('api');
		$this->load->library('jwt');
    }
    
    function index() {
		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/student_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get student information 
		$status = $this->student_m->check($info);

		// get the session
		$sess = $this->session->userdata();

		// create jwt token and pass to header
		if(array_key_exists("user_id", $sess)) {
			$user_id = $this->session->userdata('user_id');
			$data = array(
				// 'user_id' => $user_id
				'username' => 'klho'
			);
			$key = "Sm@rtJen2@!9!@#";
			$token = $this->jwt->encode($data, $key);
			header("Authorization: Bearer " . $token);
		} else {
			header("HTTP/1.1 401 Unauthorized");
		}
		
		// return the api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);
	}
	
	// 11th August : Check if a student is promoted from last year
	// True if the student is new student without data
	function new_student() {

		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/student_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get student information 
		$status = $this->student_m->new_student_m($info);
		
		// get the session
		$sess = $this->session->userdata();

		// create jwt token and pass to header
		if(array_key_exists("user_id", $sess)) {
			$user_id = $this->session->userdata('user_id');
			$data = array(
				// 'user_id' => $user_id
				'username' => 'klho'
			);
			$key = "Sm@rtJen2@!9!@#";
			$token = $this->jwt->encode($data, $key);
			header("Authorization: Bearer " . $token);
		} else {
			header("HTTP/1.1 401 Unauthorized");
		}
		
		// return the api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);
		
	}

	// Check if a student is promoted from last year
	// return True if the student is promoted from last year
	function student_promoted_from_last_year() {

		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/student_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get student information 
		$status = $this->student_m->student_promoted_from_last_year_m($info);
		
		// get the session
		$sess = $this->session->userdata();

		// create jwt token and pass to header
		if(array_key_exists("user_id", $sess)) {
			$user_id = $this->session->userdata('user_id');
			$data = array(
				// 'user_id' => $user_id
				'username' => 'klho'
			);
			$key = "Sm@rtJen2@!9!@#";
			$token = $this->jwt->encode($data, $key);
			header("Authorization: Bearer " . $token);
		} else {
			header("HTTP/1.1 401 Unauthorized");
		}
		
		// return the api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);

	}

	// 11th August : Get the current LID of a student
	// return the current LID of the student
	function get_current_level_of_student() {

		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/student_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get student information 
		$status = $this->student_m->get_current_level_of_student_m($info);
		
		// get the session
		$sess = $this->session->userdata();

		// create jwt token and pass to header
		if(array_key_exists("user_id", $sess)) {
			$user_id = $this->session->userdata('user_id');
			$data = array(
				// 'user_id' => $user_id
				'username' => 'klho'
			);
			$key = "Sm@rtJen2@!9!@#";
			$token = $this->jwt->encode($data, $key);
			header("Authorization: Bearer " . $token);
		} else {
			header("HTTP/1.1 401 Unauthorized");
		}
		
		// return the api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);

	}


	// 11th August : Get the weakest KPs of a student
	// return the array of weakest KPs of the student
	function get_weakest_kps() {

		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/student_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get student information 
		$status = $this->student_m->get_weakest_kps_m($info);
		
		// get the session
		$sess = $this->session->userdata();

		// create jwt token and pass to header
		if(array_key_exists("user_id", $sess)) {
			$user_id = $this->session->userdata('user_id');
			$data = array(
				// 'user_id' => $user_id
				'username' => 'klho'
			);
			$key = "Sm@rtJen2@!9!@#";
			$token = $this->jwt->encode($data, $key);
			header("Authorization: Bearer " . $token);
		} else {
			header("HTTP/1.1 401 Unauthorized");
		}
		
		// return the api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);

	}
}