<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Neighbour extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->library('api');
		$this->load->library('jwt');
    }
    
    function LID() {
		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/neighbour_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// get the next level
		$status = $this->neighbour_m->get_lid($info);

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

    function SID() {
		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/neighbour_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get neighbour SIDs base on LID,  TID
		$status = $this->neighbour_m->get_sid($info);

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
	
	function get_neighboring_sids() {
		// use get method
		$this->api->method_required("GET");

		// posted data saved into variable
		$this->load->model("recsys/neighbour_m");
		$rest_data = $this->api->get_rest_data();
		$info = $rest_data["data"];

		// Get neighbour SIDs base on LID,  TID
		$status = $this->neighbour_m->get_neighboring_sids_m($info);

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