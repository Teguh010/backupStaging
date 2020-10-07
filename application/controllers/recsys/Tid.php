<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tid extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->library('api');
		$this->load->library('jwt');
    }
    
    function index() {
		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/tid_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Check if TID is key for LID
		$status = $this->tid_m->check($info);

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

	// 27th August : Get the parent KPs of a list of kps
	// return list of parent KPs of the input kps
	function get_parent_kps() {
		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/tid_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Check if TID is key for LID
		$status = $this->tid_m->get_parent_kps_m($info);

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