<?php

/**
 * API Access Controller
 * 
 */



defined('BASEPATH') OR exit('No direct script access allowed');



class Access extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		
		$this->load->model('model_users');
		$this->load->model('services/model_access');
		$this->load->library('jwt');
        
    }

    


	public function index() {		
		
		$this->login();

	}


	public function login(){

		$result = $this->validate_token();
		
		if($result === true){

			$token = $this->get_request('token');

			
			$key = SEAMLESS_LOGIN_SECRET_KEY;

			try{
				$payload = $this->jwt->decode($token,$key);

				$this->model_access->log(json_encode((array)$payload),$token);

			}catch(Exception $ex){


				$data = array(
					'status' => 401,
					'description' => 'Unauthorized - Invalid Signature'
				);

				$payload = $this->jwt->decode($token,$key,false);
				$this->model_access->log(json_encode((array)$payload),$token);

				$this->show_error($data);

			}

			if($payload == false){
				
				$data = array(
					'status' => 401,
					'description' => 'Unauthorized - Invalid Payload'
				);
			

				$this->show_error($data);
				exit;
			}
			

			//array_debug($payload);exit;
			
			$user = $this->model_access->get_user($payload->username);
			//$user=null;
			//array_debug($user);exit;
			

			// 1. Check User ID exist in system
			if(isset($payload->username) && $user !== null){
				//array_debug($user);exit;

				// Check optional field
				if(isset($payload->extra) ){

					$extra = $payload->extra;

					if(isset($extra->branch_id)){

						$branch_id = $extra->branch_id;

						$is_valid = $this->model_access->check_branch($branch_id);

						if(!$is_valid){

							$data = array(
								'status' => 401,
								'description' => 'Invalid Branch'
							);
						
				
							$this->show_error($data);
							exit;
						}
						
					}else{
						// Default branch ID
						$branch_id = 0;
					}

				}else{

					// Default branch ID
					$branch_id = 0;
				}

				//Check user role
				if(!in_array($payload->user_role,array('1','2','3'))){
				
					$data = array(
						'status' => 401,
						'description' => 'Unauthorized - Invalid Payload'
					);
				
		
					$this->show_error($data);
					exit;
				}


				// Check for user branch access
				$branch_check = $this->model_access->check_user_branch($user->id,$branch_id);

				if(!$branch_check){
					// Tag New Branch
					$this->model_access->tag_branch($user->id,$branch_id);

				}

				// Proceed Login
				//array_debug('here');exit;

				$this->model_access->do_login($user->username,$branch_id);

				redirect(base_url().'profile');
				
				

			}else if($payload->username !== ''){

				// 2. Register New User	
				$this->register_user($payload);

			}else{

				$data = array(
					'status' => 401,
					'description' => 'Unauthorized - Invalid Payload'
				);
			

				$this->show_error($data);
				exit;
			}


		}else{
			$data = array(
					'status' => 400,
					'description' => 'Bad Request'
			);
			

			$this->show_error($data);
		}

	}

	

	private function validate_token(){
		$request = $this->get_request();
		
		if($request){

			if(isset($request['token']) && $request['token'] !== ''){
				
				return true;
			}else{

				$data = array(
						'status' => 400,
						'description' => 'Bad Request'
					);

				$this->show_error($data);
				
			}
		}else{

			$data = array(
					'status' => 400,
					'description' => 'Bad Request'
			);
			

			$this->show_error($data);
		}

		$this->model_access->log('',$request);
	}

	private function get_request($key = ''){

		$json_request_body = file_get_contents('php://input');

		if($json_request_body){
			$temp = explode('&',$json_request_body);
			$result = array();

			foreach($temp as $item){
				$value = explode('=',$item);
				$result[$value[0]] = $value[1];
			}

			if($key !== '' && isset($result[$key])){
				return $result[$key];
			}

			return $result;
		}else{
			return false;
		}

	}

	public function show_error($data = array())

	{
		$data['content'] = 'view_error';

		echo $this->load->view('include/master_view', $data,true);

	}

	public function register_user($payload = ''){

		if($payload !== ''){
			//array_debug($payload);exit;

			if(!isset($payload->username) || empty($payload->username)){

				$data = array(
					'status' => 401,
					'description' => 'Unauthorized - Invalid Payload'
				);
			
	
				$this->show_error($data);
				exit;
			}

			if(!isset($payload->full_name) || empty($payload->full_name)){

				$data = array(
					'status' => 401,
					'description' => 'Unauthorized - Invalid Payload'
				);
			
	
				$this->show_error($data);
				exit;
			}

			if(!isset($payload->user_role) || empty($payload->user_role)){

				$data = array(
					'status' => 401,
					'description' => 'Unauthorized - Invalid Payload'
				);
			
	
				$this->show_error($data);
				exit;
			}

			if(!in_array($payload->user_role,array('1','2','3'))){

				$data = array(
					'status' => 401,
					'description' => 'Unauthorized - Invalid Payload'
				);
			
	
				$this->show_error($data);
				exit;
			}

			// Check optional field
			if(isset($payload->extra) ){

				$extra = $payload->extra;

				if(isset($extra->branch_id)){

					$branch_id = $extra->branch_id;

					$is_valid = $this->model_access->check_branch($branch_id);

					if(!$is_valid){

						$data = array(
							'status' => 401,
							'description' => 'Invalid Branch'
						);
					
			
						$this->show_error($data);
						exit;
					}
					
				}else{
					// Default branch ID
					$branch_id = 0;
				}

			}else{

				// Default branch ID
				$branch_id = 0;
			}


			$this->model_access->add_user($payload->username,$payload->full_name,$payload->user_role,$branch_id);
			
			// Login
			$this->model_access->do_login($payload->username,$branch_id);

			redirect(base_url().'profile');

		}else{

			$data = array(
				'status' => 401,
				'description' => 'Unauthorized - Invalid Payload'
			);
		

			$this->show_error($data);
			exit;
		}
	}

	
	
}