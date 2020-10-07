<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Questions extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->library('api');
		$this->load->library('jwt');
    }
    
    function index() {
		// use the get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/questions_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get Questions base on LID,  TID, SID (from smartJen server)
		$status = $this->questions_m->get_question($info);

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
		
		// return api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}
		
		// return api
		api_return($status);
	}
	
	// 14th August : Generate random questions according to input parameters
	// return the list of the question id
	function generate_questions_randomly() {
		// use the get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/questions_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get Questions base on LID,  TID, SID (from smartJen server)
		$status = $this->questions_m->generate_questions_randomly_m($info);

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

		// return api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}

		// return api
		api_return($status);
	}

	function generate_questions_randomly_from_lid() {
		// use the get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/questions_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get Questions base on LID,  TID, SID (from smartJen server)
		$status = $this->questions_m->generate_questions_randomly_from_lid_m($info);

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

		// return api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}

		// return api
		api_return($status);
	}

	function generate_questions_equally_from_sids() {
		// use the get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/questions_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get Questions base on LID,  TID, SID (from smartJen server)
		$status = $this->questions_m->generate_questions_equally_from_sids_m($info);

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
		// use the get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/questions_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get Questions base on LID,  TID, SID (from smartJen server)
		$status = $this->questions_m->get_diagnostic_test_questions_m($info);

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

		// return api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}

		// return api
		api_return($status);
	}

	// 14th August : Generate random questions according to input parameters
	// return the list of the question id
	function generate_questions_randomly_from_kps() {
		// use the get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/questions_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get Questions base on LID,  TID, SID (from smartJen server)
		$status = $this->questions_m->generate_questions_randomly_from_kps_m($info);

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

		// return api error message
		if($status === false) {
			api_return("Something wrong while getting the data.", 500);
		} else if(is_string($status)) {
			api_return($status, 400);
		}

		// return api
		api_return($status);
	}

	// 14th August : Generate random questions according to input parameters
	// return the list of the question id
	function get_weakest_kps() {
		// use the get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/questions_m");
		$this->load->model("model_users");
		$this->load->model("model_question");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];


		$student_id = $info['student_id'];

		// $performance = $this->model_users->get_performance(array($student_id), 2)[$student_id];

		// Get Questions base on LID,  TID, SID (from smartJen server)
		$status = $this->questions_m->get_weakest_kps_m($info);

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