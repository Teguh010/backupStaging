<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



defined('BASEPATH') OR exit('No direct script access allowed');



class Site extends CI_Controller {

	public function index()

	{		
		$this->home();		

	}



	public function home() 
	{

		// $data['content'] = 'view_home';

		// $this->load->view('include/master_view', $data);

		$this->load->view('include/site_header');

		$this->load->view('view_home');

		$this->load->view('include/site_footer');

	}


	public function contactus()
	{

		$data['content'] = 'view_contactus';

		$this->load->view('include/master_view', $data);

	}

	public function login($tutorId = NULL, $key = NULL) 
	{
		$tutorId = base64_decode(urldecode($tutorId));
		$this->load->model('model_question');
		$level = $this->model_question->get_level_list(2);
		$school = $this->model_question->get_school_list();
		$data['levels'] = $level;
		$data['schools'] = $school;
		$data['content'] = 'view_login_register';
		
		if(isset($key) && !empty($key)) {
			
			$data['key'] = $key;
			
			$data['tutorId'] = $tutorId;
			
			$data['register_success'] = "Please register an account first before login into the system.";
			
		}
		$this->load->model('model_google_login');
		$google_client = new Google_Client();
		$google_client->setClientId('600974188866-d7473159cmo2ofoeu49881t0ctslnhps.apps.googleusercontent.com'); //Define your ClientID
		$google_client->setClientSecret('jVizn0O75dEbgXOgOvrunkgG'); //Define your Client Secret Key
		$google_client->setRedirectUri(base_url().'site/login'); //Define your Redirect Uri
	    // $google_client->setScopes(array(
	    // 		Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS
	    // ));
		$google_client->addScope('email');
		$google_client->addScope('profile');
		if(isset($_GET["code"])) {
			$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
			$this->session->set_userdata('code', $_GET["code"]);
			// echo print_r($token); // exit;
			if(!isset($token["error"])) {
				$this->load->model('model_users');
				$google_client->setAccessToken($token['access_token']);
				$this->session->set_userdata('access_token', $token['access_token']);
				$google_service = new Google_Service_Oauth2($google_client);
				$data = $google_service->userinfo->get();
				$user_data = array(
					'login_oauth_uid' => $data['id'],
					'first_name'  => $data['given_name'],
					'last_name'   => $data['family_name'],
					'email_address'  => $data['email'],
					'profile_picture' => $data['picture']
				);
				$this->session->set_userdata('google_user_data', $user_data);
				// Check available in sj_users
				$check_user = $this->model_users->get_user_id_from_email_or_username($data['email']);
				if($check_user) {
					// Successfully login with Google Account
					$this->model_users->login($data['email']);
					$lastPage = $this->session->userdata('lastPage');
					if (isset($lastPage)) {
						redirect($lastPage);
					} else {
						redirect(base_url().'profile');
					}
				} else {
					// Failed login with Google Account
					$login_error = 'Your Google account is not found in our system.';
					$this->session->set_userdata('login_error', $login_error);
					$google_client->revokeToken();
					redirect(base_url()."site/login");
				}
			}
		}
		$data['google_login_url'] = $google_client->createAuthUrl();
		
		$this->load->view('include/master_view', $data);
	}

	
	public function logins($email = NULL, $tutorId = NULL, $key = NULL, $parEmail = NULL) 
	{
		$email = base64_decode(urldecode($email));
		$tutorId = base64_decode(urldecode($tutorId));
		$this->load->model('model_question');
		$level = $this->model_question->get_level_list(2);
		$school = $this->model_question->get_school_list();
		$parEmail = base64_decode(urldecode($parEmail));
		$data['parEmail'] = $parEmail;
		$data['email'] = $email;
		$data['levels'] = $level;
		$data['schools'] = $school;
		
		if(isset($key) && !empty($key)) {
			
			$data['key'] = $key;
			
			$data['tutorId'] = $tutorId;
			
			$this->session->set_flashdata('register_success', 'Please register an account first before login into the system.');
		}
		
		$data['content'] = 'view_login_registers';
		
		$this->load->view('include/master_view', $data);
	}


	public function login_s($tutorId = NULL, $key = NULL) 
	{
		$tutorId = base64_decode(urldecode($tutorId));
		$this->load->model('model_question');
		$level = $this->model_question->get_level_list();
		$school = $this->model_question->get_school_list();
		$data['levels'] = $level;
		$data['schools'] = $school;
		
		if(isset($key) && !empty($key)) {
			
			$data['key'] = $key;
			
			$data['tutorId'] = $tutorId;
			
			$data['register_success'] = "Please register an account first before login into the system.";
			
		}
		
		$data['content'] = 'view_login_register_s';
		
		$this->load->view('include/master_view', $data);
	}
	



	// [WM] 2Jan18: For admin account with registration controls enabled

	public function loginadmin()

	{

		$data['content'] = 'view_login_register_admin';

		$this->load->view('include/master_view', $data);

	}

	public function google_login() {
		redirect(base_url()."google_login/login");
	}

	public function facebook_connect() {

		$this->load->library('facebook');

		redirect($this->facebook->login_url());

		// echo $this->facebook->get_user();

	}



	public function login_with_facebook() {

		$this->load->library('facebook');

		$this->load->model('model_users');

		if ($fb_user = $this->facebook->get_user()) {

			if ($this->model_users->is_member($fb_user)) {

				$this->model_users->login($fb_user['email']);

			} else {

				$this->model_users->sign_up_from_facebook($fb_user);

				$this->model_users->login($fb_user['email']);

			}



			redirect(base_url().'profile');

		} else {

			echo "Could not login with facebook at this time";

		}

	}



	public function js_facebook_login() {

		$this->load->model('model_users');

		if ($this->input->is_ajax_request()) {

			$fb_user = array();

			$fb_user['email'] = $this->input->post('fb_email');

			$fb_user['id'] = $this->input->post('fb_id');

			$fb_user['name'] = $this->input->post('full_name');



			if ($this->model_users->is_member($fb_user)) {

				$this->model_users->login($fb_user['email']);

				$this->session->set_userdata('profileMessageSuccess', true);

				$this->session->set_userdata('profileMessage', 'Successfully logged in from Facebook');

			} else {

				$this->model_users->sign_up_from_facebook($fb_user);

				$this->model_users->login($fb_user['email']);

				$this->session->set_userdata('profileMessageSuccess', true);

				$this->session->set_userdata('profileMessage', 'Successfully signed up from Facebook');

			}



			echo json_encode(array(

					"success" => true

				));



		} else {

			redirect('404');

		}

	}



	public function logout() 

	{
		// if($this->session->userdata('google_user_data')!=='') {
		// 	try {
		// 		$this->load->model('model_google_login');
		// 		$google_client = new Google_Client();
		// 		$google_client->setClientId('600974188866-d7473159cmo2ofoeu49881t0ctslnhps.apps.googleusercontent.com'); //Define your ClientID
		// 		$google_client->setClientSecret('jVizn0O75dEbgXOgOvrunkgG');
		// 		$google_client->revokeToken();
		// 	} catch (\Google_Service_Exception $e) {

		// 	}
		// }
		$this->session->sess_destroy();

		redirect(base_url());

	}


	public function login_validation() 
	{
		$this->load->library('form_validation');

		$this->load->model('model_users');

		$this->form_validation->set_rules('login_email', 'Email / Username', 'required|trim|callback__validate_credentials');

		$this->form_validation->set_rules('login_password', 'Password', 'required');

		if($this->session->has_userdata('is_admin_logged_in')) {
			$this->session->sess_destroy();
		}

		if ($this->form_validation->run()) {

			$this->model_users->login($this->input->post('login_email'));

			$lastPage = $this->session->userdata('lastPage');

			if (isset($lastPage)) {

				redirect($lastPage);

			} else {
				
				redirect(base_url().'profile');

			}

		} else {

			$login_error = validation_errors();
			$this->session->set_userdata('login_error', $login_error);
			redirect(base_url()."site/login");
			
			// $data['login_error'] = validation_errors();

			// $data['content'] = 'view_login_register';

			// $this->load->view('include/master_view', $data);

		}

	}

	// parent email template
	private function parent_template($email, $key) {
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "Smartjen");
		$this->email->subject('SmartJen Accounts Activation');
		$this->email->to($email);
		$message = "<p>Dear Parent,</p>";

		$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
		$encode_pemail = urlencode(base64_encode($email));
		if(isset($tutorId) && !empty($tutorId)) {
			
			$message .= "<p><a href='".base_url()."site/register_temp_parent/$encode_pemail/$key'>Click here</a> to confirm your account</p>";
		} else {

			$message .= "<p><a href='".base_url()."site/register_temp_parent/$encode_pemail/$key'>Click here</a> to confirm your account</p>";
		}
		
		$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";
		$this->email->message($message);
		return $this->email;
	}

	// new parent email template
	private function new_parent_template($pemail, $parkey, $email = NULL, $school_id = NULL, $level_id = NULL, $key = NULL, $tutorId = NULL) {
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "Smartjen");
		$this->email->subject('SmartJen Accounts Activation');
		$this->email->to($pemail);

		$message = "<p>Dear Parent,</p>";

		$message .= "<p>Thank you for signing up! You and your child are one step away to have full access of SmartJen features.</p>";
		$message .= "<p>Please click here to activate both accounts.</p>";
		$encode_pemail = urlencode(base64_encode($pemail));
		if(isset($key) && $key != "") {
			
			$message .= "<p><a href='".base_url()."site/register_new_parent/$encode_pemail/$parkey/$school_id/$level_id/$key'>Click here</a> to confirm your account</p>";
		} else {

			$message .= "<p><a href='".base_url()."site/register_new_parent/$encode_pemail/$parkey'>Click here</a> to confirm your account</p>";
		}
		
		$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";
		$this->email->message($message);
		return $this->email;
	}

	// only parent template
	private function exist_parent_template($pemail, $parkey, $email = NULL, $school_id = NULL, $level_id = NULL, $key = NULL, $tutorId = NULL) {
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "Smartjen");
		$this->email->subject('SmartJen Accounts Activation');
		$this->email->to($pemail);
		$message = "<p>Dear Parent,</p>";

		$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
		$encode_pemail = urlencode(base64_encode($pemail));
		if(isset($tutorId) && !empty($tutorId)) {
			
			$message .= "<p><a href='".base_url()."site/register_exist_parent/$encode_pemail/$parkey/$school_id/$level_id/$key/$tutorId'>Click here</a> to confirm your account</p>";
		} else {

			$message .= "<p><a href='".base_url()."site/register_exist_parent/$encode_pemail/$parkey/$school_id/$level_id/$key'>Click here</a> to confirm your account</p>";
		}
		
		$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";

		$this->email->message($message);

		return $this->email;
	}

	// student email template
	private function student_template($pemail, $email, $tutorId = null, $school_id, $level_id, $key) {
		$pemail = urlencode(base64_encode($pemail));
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "Smartjen");
		$this->email->subject('SmartJen Accounts Activation');
		$this->email->to($email);
		$message = "<p>Dear Student,</p>";

		$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";

		if(isset($tutorId) && !empty($tutorId)) {
			
			$message .= "<p><a href='".base_url()."site/register_temp_student/$pemail/$school_id/$level_id/$key/$tutorId'>Click here</a> to confirm your account</p>";
		} else {

			$message .= "<p><a href='".base_url()."site/register_temp_student/$pemail/$school_id/$level_id/$key'>Click here</a> to confirm your account</p>";
		}
		
		$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";

		$this->email->message($message);

		return $this->email;
	}

	public function register_validation()
	{
		$this->load->model('model_users');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->form_validation->set_rules('register_fullName', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('register_username', 'Username', 'required|trim|max_length[30]|callback__validate_username');
		$this->form_validation->set_rules('register_email', 'Email', 'valid_email|trim');
		$this->form_validation->set_rules('register_parent_email', 'Email', 'valid_email|trim');
		$this->form_validation->set_rules('register_password', 'Password', 'required|trim|min_length[6]');
		$this->form_validation->set_rules('register_cpassword', 'Confirm Password', 'required|trim|matches[register_password]');
		$post = $this->input->post();
		$email = $post['register_email'];
		$mobile = $post['register_mobile'];
		$pemail = $post['register_parent_email'];
		$account_type = $this->input->post('role');
		$key = sha1(uniqid('jen'));
		$par_key = sha1(uniqid('jen'));
		if ($this->form_validation->run()) {

			if($account_type == 'tutor') 
			{
				//send and email to the user
				$this->email->from('noreply@smartjen.com', "SmartJen");
				$this->email->to($email);
				$this->email->subject('SmartJen Accounts Activation');
				$message = "<p>Dear " . $this->input->post('register_fullName') . ", </p>";
				$message .= "<p>Thank you for signing up! You are one step away to have full access of all features.</p>";
				$message .= "<p><a href='".base_url()."site/register_user/$key'>Click here</a> to confirm your account</p>";
				$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";
				$this->email->message($message);
				if ($this->model_users->add_temp_user($key)) 
				{
					if ($this->email->send()) 
					{
						$message = $this->session->set_flashdata('register_success', 'A confirmation email has been sent to your inbox. Please verify your account status.');
						redirect(base_url() . 'site/login');
					} else {
						$message = $this->session->set_flashdata('register_error', 'Error in sending out confirmation email to user. Please try again later or contact administrator.');
						redirect(base_url() . 'site/login');
					}
				} else {
					
					$message = $this->session->set_flashdata('register_error', 'Error in registering tutor account. Please try again later or contact administrator.');
					redirect(base_url() . 'site/login');
				}
			}
			else
			{
				$school_id = urlencode(base64_encode($post['register_school']));
				$level_id = urlencode(base64_encode($post['register_level']));
				$tutorId = isset($post['tutorId'])? urlencode(base64_encode($post['tutorId'])) : '';

				// check for parent email field
				if(isset($pemail) && empty($pemail) == false) {
					
					// check whether parent has existing account in system
					$parId = $this->model_users->get_user_id_from_email_or_username($pemail);
					
					// if $parId return parent ID
					if($parId !== null) {
						
						// check for total number of children
						$childNo = $this->model_users->check_children_no($parId);

						if($childNo < 3) {

							// register with parent and student email
							if(isset($email) && empty($email) === false) {

								// return parent email template
								$this->parent_template($pemail, $par_key);

								$this->email->send();

								// return student email template
								$this->student_template($pemail, $email, $tutorId, $school_id, $level_id, $key, $tutorId);

								if($this->model_users->add_temp_student($par_key, $key)) {

									if($this->email->send()) {

										$message = $this->session->set_flashdata('register_success', 'An confirmation email was sent to both ' . $pemail . ' and ' . $email . ' . Please activate the accounts for verification purposes.');
										
										redirect(base_url() . 'site/login');
									} else {
										$message = $this->session->set_flashdata('register_error', 'Failed to sent out confirmation email to student.');
									
										redirect(base_url() . 'site/login');
									}
								} else {
									$message = $this->session->set_flashdata('register_error', 'Failed to add student.');

									redirect(base_url() . 'site/login');
								}
							} else {

								//register with only parent email

								// return parent email template
								$this->exist_parent_template($pemail, $par_key, $email, $school_id, $level_id, $key, $tutorId);

								if($this->model_users->add_temp_student($par_key ,$key)) {

									if($this->email->send()) {

										$message = $this->session->set_flashdata('register_success', 'An confirmation email was sent to '.$pemail.' . Please verify your account status.');
										
										redirect(base_url() . 'site/login');
									} else {
										$message = $this->session->set_flashdata('register_error', 'Failed to sent out confirmation email to student.');
									
										redirect(base_url() . 'site/login');
									}
								} else {
									$message = $this->session->set_flashdata('register_error', 'Failed to add student.');

									redirect(base_url() . 'site/login');
								}
							}

						} else {
							$message = $this->session->set_flashdata('register_error', 'Parent email ' . $pemail . ' has reached maximum children number of 3. Please contact hello@smartjen.com for support.');
								
							redirect(base_url() . 'site/login');
						}

					} else {
						// new parent registered

						// register with parent and student email
						if(isset($email) && empty($email) === false) {

							// return parent email template
							$this->new_parent_template($pemail, $par_key);
							$this->email->send();

							// return student email template
							$this->student_template($pemail, $email, $tutorId, $school_id, $level_id, $key, $tutorId);
							if($this->model_users->add_temp_student($par_key, $key)) {

								if($this->email->send()) {

									$message = $this->session->set_flashdata('register_success', 'An confirmation email was sent to both ' . $pemail . ' and ' . $email . ' . Please activate the accounts for verification purposes.');
									
									redirect(base_url() . 'site/login');
								} else {
									$message = $this->session->set_flashdata('register_error', 'Failed to sent out confirmation email to student.');
								
									redirect(base_url() . 'site/login');
								}
							} else {
								$message = $this->session->set_flashdata('register_error', 'Number of children has max out');
									
								redirect(base_url() . 'site/login');
							}

						} else {

							// register with only parent email
							// return parent email template

							$this->new_parent_template($pemail, $par_key, $email, $school_id, $level_id, $key);

							if($this->model_users->add_temp_student($par_key ,$key)) {

								if($this->email->send()) {

									$message = $this->session->set_flashdata('register_success', 'An confirmation email was sent to '.$pemail.' . Please verify your account status.');
									
									redirect(base_url() . 'site/login');
								} else {

									$message = $this->session->set_flashdata('register_error', 'Failed to sent out confirmation email to student.');
								
									redirect(base_url() . 'site/login');
								}
							} else {
								$message = $this->session->set_flashdata('register_error', 'Number of children has max out');
									
								redirect(base_url() . 'site/login');
							}
						}

					}

				} else {

					$message = $this->session->set_flashdata('register_error', 'Parent email field is compulsory.');
					redirect(base_url() . 'site/login');
				}
				
			}
		}
		else
		{
			$message = $this->session->set_flashdata('register_error', validation_errors());
			redirect($_SERVER['HTTP_REFERER']);
		}
	}



	public function register_user($key, $level = NULL, $school = NULL, $tutorId = NULL)
	{
		$this->load->model('model_users');
		$tutorId = base64_decode(urldecode($tutorId));
		$level = base64_decode(urldecode($level));
		$school = base64_decode(urldecode($school));
		if ($this->model_users->is_key_valid($key)) {
			if ($newemail = $this->model_users->add_user($key, $tutorId)) {
				$this->model_users->login($newemail);
				$this->session->set_userdata('profileMessageSuccess', true);
				$this->session->set_userdata('profileMessage', 'Your account has been activated!');	
				redirect(base_url().'profile');
			} else {
				$data['register_error'] = "Unable to validate your account";
			}
		} else {
			$data['register_error'] = "Invalid Key";
		}
		$data['content'] = "view_login_register";
		$this->load->view("include/master_view.php", $data);
	}


	public function register_new_parent($encode_pemail, $parkey, $school_id = NULL, $level_id = NULL, $key = NULL, $tutorId = NULL) {
		$this->load->model('model_users');
		$pemail = base64_decode(urldecode($encode_pemail));
		$school_id = base64_decode(urldecode($school_id));
		$level_id = base64_decode(urldecode($level_id));
		$tutorId = base64_decode(urldecode($tutorId));

		$parId = $this->model_users->get_user_id_from_email_or_username($pemail);
		if($parId) {
			if ($this->model_users->is_key_valid($parkey)) {
				if ($newemail = $this->model_users->add_parent($parkey, $school_id, $level_id, $key, $tutorId)) {
					$this->model_users->delete_temp_user($key);
					$user_id = $this->model_users->get_user_id_from_email_or_username($newemail);
					$userInfo = $this->model_users->get_user_info($user_id);
					$profile = $userInfo->profile_pic;
					$data_ar = array(
						'is_logged_in' => 1,
						'user_id' => $user_id,
						'user_role' => 3,
						'profile_pic' => $profile,
						'branch_code'  => 9
					);
					$this->session->set_userdata($data_ar);
					$this->session->set_userdata('profileMessageSuccess', true);
					$this->session->set_userdata('profileMessage', 'Your account has been activated!');
					redirect(base_url().'profile');
				} else {
					// $data['register_error'] = "Unable to validate your account";
					$message = $this->session->set_flashdata('register_error', 'Unable to validate your account!');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				// $data['register_error'] = "Invalid Key";
				$message = $this->session->set_flashdata('register_error', 'Invalid Key!');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {

			$data['email'] = $pemail;
			$data['parkey'] = $parkey;
			$data['school_id'] = $school_id;
			$data['level_id'] = $level_id;
			$data['key'] = $key;
			$data['tutorId'] = $tutorId;
			$data['content'] = "view_update_user";
			$this->load->view("include/master_view.php", $data);

		}

		

	}

	public function register_exist_parent($encode_pemail, $parkey, $school_id = NULL, $level_id = NULL, $key = NULL, $tutorId = NULL) {
		$this->load->model('model_users');
		$pemail = base64_decode(urldecode($encode_pemail));
		$school_id = base64_decode(urldecode($school_id));
		$level_id = base64_decode(urldecode($level_id));
		$tutorId = base64_decode(urldecode($tutorId));

		$parId = $this->model_users->get_user_id_from_email_or_username($pemail);
		
		if($parId) {
			if ($this->model_users->is_key_valid($parkey)) {
				if ($newemail = $this->model_users->add_exist_parent($parkey, $school_id, $level_id, $key, $tutorId)) {
					$this->model_users->delete_temp_user($key);
					$user_id = $this->model_users->get_user_id_from_email_or_username($newemail);
					$userInfo = $this->model_users->get_user_info($user_id);
					$tutorExist = $this->model_users->check_tutor_exist_tag($newemail);
					$profile = $userInfo->profile_pic;

					if($tutorExist == TRUE){
						$check_user_role = 1;
					} else {
						$check_user_role = 0;
					}

					$data_ar = array(
						'is_logged_in' => 1,
						'user_id' => $user_id,
						'user_role' => 3,
						'check_user_role' => $check_user_role,
						'profile_pic' => $profile,
						'branch_code'  => 9
					);
					$this->session->set_userdata($data_ar);
					$this->session->set_userdata('profileMessageSuccess', true);
					$this->session->set_userdata('profileMessage', 'Your account has been activated!');
					redirect(base_url().'profile');
				} else {
					// $data['register_error'] = "Unable to validate your account";
					$message = $this->session->set_flashdata('register_error', 'Unable to validate your account!');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				// $data['register_error'] = "Invalid Key";
				$message = $this->session->set_flashdata('register_error', 'Invalid Key!');
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {

			$data['email'] = $pemail;
			$data['parkey'] = $parkey;
			$data['school_id'] = $school_id;
			$data['level_id'] = $level_id;
			$data['key'] = $key;
			$data['tutorId'] = $tutorId;
			$data['content'] = "view_update_user";
			$this->load->view("include/master_view.php", $data);
		}

	}

	public function update_parent_user() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('register_fullName', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('register_username', 'Username', 'required|trim|max_length[30]|callback__validate_username');
		$this->form_validation->set_rules('register_password', 'Password', 'required|trim|min_length[6]');
		$this->form_validation->set_rules('register_cpassword', 'Confirm Password', 'required|trim|matches[register_password]');
		if ($this->form_validation->run()) {
			$parkey = $this->input->post('parkey');
			$email = $this->input->post('pemail');
			$key = $this->input->post('key');
			$school_id = $this->input->post('school_id');
			$level_id = $this->input->post('level_id');
			$tutorId = $this->input->post('tutor_id');
			if ($this->model_users->is_key_valid($parkey)) {
				if ($newemail = $this->model_users->add_new_parent($parkey, $email, $school_id, $level_id, $key, $tutorId)) {
					$this->model_users->delete_temp_user($key);
					$user_id = $this->model_users->get_user_id_from_email_or_username($newemail);
					$userInfo = $this->model_users->get_user_info($user_id);
					$profile = $userInfo->profile_pic;
					$data_ar = array(
						'is_logged_in' => 1,
						'user_id' => $user_id,
						'user_role' => 3,
						'profile_pic' => $profile,
						'branch_code'  => 9
					);
					$this->session->set_userdata($data_ar);
					$this->session->set_userdata('profileMessageSuccess', true);
					$this->session->set_userdata('profileMessage', 'Your account has been activated!');
					redirect(base_url().'profile');
				} else {
					// $data['register_error'] = "Unable to validate your account";
					$message = $this->session->set_flashdata('register_error', 'Unable to validate your account!');
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				// $data['register_error'] = "Invalid Key";
				$message = $this->session->set_flashdata('register_error', 'Invalid Key!');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
		else
		{
			$message = $this->session->set_flashdata('register_error', validation_errors());
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function register_temp_parent($encode_email, $key) {
		$this->load->model('model_users');

		$email = base64_decode(urldecode($encode_email));

		if ($this->model_users->is_key_valid($key)) {
			if ($newemail = $this->model_users->add_parent($key, $email)) {
				$this->model_users->delete_temp_user($key);
				// $this->model_users->login($newemail);
				$user_id = $this->model_users->get_user_id_from_email_or_username($newemail);
				$userInfo = $this->model_users->get_user_info($user_id);
				$profile = $userInfo->profile_pic;
				// $profile = $this->model_users->get_user_profile_pic($user_id);
				$data_ar = array(
					'is_logged_in' => 1,
					'user_id' => $user_id,
					'user_role' => 3,
					'profile_pic' => $profile,
					'branch_code'  => 9
				);
				$this->session->set_userdata($data_ar);
				$this->session->set_userdata('profileMessageSuccess', true);
				$this->session->set_userdata('profileMessage', 'Your account has been activated!');
				redirect(base_url().'profile');
			} else {
				$data['register_error'] = "Unable to validate your account";
			}
		} else {
			$data['register_error'] = "Invalid Key";
		}
		$data['content'] = "view_login_register";
		$this->load->view("include/master_view.php", $data);
	}


	public function register_temp_student($pemail, $school_id, $level_id, $key, $tutorId = NULL) 
	{
		$this->load->model('model_users');
		
		$level = base64_decode(urldecode($level_id));
		$school = base64_decode(urldecode($school_id));
		$pemail = base64_decode(urldecode($pemail));
		$tutorId = base64_decode(urldecode($tutorId));
		if ($this->model_users->is_key_valid($key)) {
			if(empty($tutorId)) {
				if ($newemail = $this->model_users->add_student_without_tutor($key, $school, $level, $pemail)) {
					$this->model_users->delete_temp_user($key);
					$this->model_users->login($newemail);
					$this->session->set_userdata('profileMessageSuccess', true);
					$this->session->set_userdata('profileMessage', 'Your account has been activated!');
					redirect(base_url().'profile');
				} else {
					$data['register_error'] = "Unable to validate your account";
				}
			} else {
				if ($newemail = $this->model_users->add_student_user($key, $school, $level, $tutorId, $pemail)) {
					$this->model_users->delete_temp_user($key);
					$this->model_users->login($newemail);
					$this->session->set_userdata('profileMessageSuccess', true);
					$this->session->set_userdata('profileMessage', 'Your account has been activated!');
					redirect(base_url().'profile');
				} else {
					$data['register_error'] = "Unable to validate your account";
				}
			}
		} else {
			$data['register_error'] = "Invalid Key";
		}
		$data['content'] = "view_login_register";
		$this->load->view("include/master_view.php", $data);
	}
	
	
	public function _validate_credentials()

	{

		$this->load->model('model_users');

		// do some hardwiring here, as master account to login as student behalf to submit test

		if ($this->input->post('login_email') == 'admin_ryantay' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_xunuohan' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_gabriellelee' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_xingwu' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_sharleen' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_audric' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_erickaung' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_brandonng' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_joshua' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_winn' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_jiaxin' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_ashton' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_fabiane' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_junxi' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_qiaoyue' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_minqi' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_thashwi' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_winnlau' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_winnaslau' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->input->post('login_email') == 'admin_winnielau' && $this->input->post('login_password') == 'P455w0rd!') {

			return true;

		}



		if ($this->model_users->can_login()) {

			return true;

		} else {

			$this->form_validation->set_message('_validate_credentials', 'Invalid username/password');

			return false;

		}

	} 
	
	public function _validate_username()
	{
		$this->load->model('model_users');
		
		if ($this->model_users->validate_username()) {
			return true;
		} else {
			$this->form_validation->set_message('_validate_username', 'Invalid username');
			return false;
		}
	}

	
	public function _validate_email()
	{
		$this->load->model('model_users');
		
		if ($this->model_users->validate_email()) {
			return true;
		} else {
			$this->form_validation->set_message('_validate_credentials', 'Invalid username/password');
			return false;
		}
	}



	public function forgot_password() {

		if ($this->input->is_ajax_request()) {

			$return_array = array();

			$this->load->model('model_users');

			$this->load->library('form_validation');



			$this->form_validation->set_rules('reset_password_email', 'Email', 'required|valid_email|trim');



			if ($this->form_validation->run()) {

				$new_temp_password = $key = substr(sha1(uniqid('smartjenpassword')), 0, 8);



				$user_id = $this->model_users->get_user_id_from_email_or_username($this->input->post('reset_password_email'));


				if (isset($user_id) && empty($user_id) === false) {

					if ($this->model_users->set_password($user_id, $new_temp_password)) {

						$this->load->library('email');

						$this->email->from('noreply@smartjen.com', "Smartjen");

						$this->email->to($this->input->post('reset_password_email'));

						$this->email->subject("Smartjen - New Temporary Password");



						$message = "<p> We have received a request to reset the password tagged with this email. </p>";

						$message .= "<p> Please find the new password generated for you: " . $new_temp_password . "</p>";

						$message .= "<p> You are advised to login using the new password and reset it in your profile page</p>";

						$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";

						$this->email->message($message);



						if ($this->email->send()) {

							$return_array['success'] = true;

							$return_array['message'] = "A new temporary password has been sent to your email. Please login and change your password in profile page. Thanks! ";

						} else {

							$return_array['success'] = false;

							$return_array['message'] = 'Unable to send password to the email. Please try again later or contact hello@smartjen.com';

						}

					} else {

						$return_array['success'] = false;

						$return_array['message'] = 'Cannot reset password at the moment. Please try again later or contact hello@smartjen.com';

						// $return_array['message'] = $this->email->print_debugger();

					}







				} else {

					$return_array['success'] = false;

					$return_array['message'] = 'The email is not registered in our system. Please try again later or contact hello@smartjen.com';

				}

				



			} else {

				$return_array['success'] = false;

				$return_array['message'] = validation_errors();

			}



		} else {

			redirect('404');

		}



		echo json_encode($return_array);

	}



	public function submit_feedback() {

		if ($this->input->is_ajax_request()) {

			$return_array = array();

			$this->load->library('form_validation');



			$this->form_validation->set_rules('feedback_sender_email', 'your email', 'required|valid_email|trim');

			$this->form_validation->set_rules('feedback_sender_name', 'your name', 'required|trim');

			$this->form_validation->set_rules('feedback_comment', 'your comment', 'required|trim');



			$this->form_validation->set_message('required', 'Please enter %s');

			$this->form_validation->set_message('valid_email', 'Please enter a valid email');



			if ($this->form_validation->run()) {

				$this->load->model('model_general');

				$feedback_email = $this->input->post('feedback_sender_email');

				$feedback_name = $this->input->post('feedback_sender_name');

				$feedback_type = $this->input->post('feedback_type');

				$feedback_comment = $this->input->post('feedback_comment');



				if ($this->model_general->submit_feedback($feedback_email, $feedback_name, $feedback_type, $feedback_comment)) {

					$this->load->library('email');

					$this->email->from('noreply@smartjen.com', "Smartjen - No Reply");

					$this->email->to('hello@smartjen.com');

					$this->email->cc('lowcg2@hotmail.com');

					$this->email->subject("Smartjen - Feedback");



					$message = "<p> From: ". $feedback_name . "(" . $feedback_email . ")</p>";

					$message .= "<p> Feedback Type: " . $feedback_type . "</p>";

					$message .= "<p> Feedback Comments: " . $feedback_comment . "</p>";



					$this->email->message($message);



					if ($this->email->send()) {

						$return_array['success'] = true;

						$return_array['message'] = "Your feedback has been submitted. We appreciate it very much!";

					} else {

						$return_array['success'] = false;

						$return_array['message'] = 'Error submitting feedback... please try again later or contact hello@smartjen.com'; // email failure

					}



				} else {

					$return_array['success'] = false;

					$return_array['message'] = 'Error submitting feedback... please try again later or contact hello@smartjen.com'; // database error 

				}

				



			} else {

				$return_array['success'] = false;

				$return_array['message'] = validation_errors();

			}



		} else {

			redirect('404');

		}



		echo json_encode($return_array);

	}



	public function admin() {

		$data['content'] = 'administrator/login';

		$this->load->view('include/master_view', $data);

	}

	
	public function admin_loginValidation() {
		$this->load->library('form_validation');
		$this->load->model('model_admin');
		$this->form_validation->set_rules('login_username', 'Username', 'required|trim|callback__validate_admin_credentials');
		$this->form_validation->set_rules('login_password', 'Password', 'required|md5');
		if($this->session->has_userdata('is_logged_in')) {
			$this->session->sess_destroy();
		}
		if ($this->form_validation->run()) {
			$user = $this->session->userdata('is_logged_in');
			if(isset($user) && !empty($user)){
				$this->session->sess_destroy();
			}
			$this->model_admin->login($this->input->post('login_username'));

			$username = $this->session->userdata('admin_username');
			if ($username == 'admindemo') {
				redirect(base_url().'administrator/list_private_question');
			} else if ($username == 'smartjen_xm') {
				redirect(base_url().'administrator/list_public_question');
			} else {
				redirect(base_url().'administrator');
			}
		} else {
			$data['login_error'] = validation_errors();
			$data['content'] = 'administrator/login';
			$this->load->view('include/master_view', $data);
		}
	}
	
// 	public function admin_loginValidation() {

// 		$this->load->library('form_validation');

// 		$this->load->model('model_admin');

// 		$this->form_validation->set_rules('login_username', 'Username', 'required|trim|callback__validate_admin_credentials');

// 		$this->form_validation->set_rules('login_password', 'Password', 'required|md5');

// 

// 		if ($this->form_validation->run()) {

// 			$this->model_admin->login($this->input->post('login_email'));

// 			// $lastPage = $this->session->userdata('lastPage');

// 			// if (isset($lastPage)) {

// 			// 	redirect($lastPage);

// 			// } else {

// 			// 	redirect(base_url().'profile');

// 			// }

// 			redirect(base_url().'administrator');

// 		} else {

// 			$data['login_error'] = validation_errors();

// 			$data['content'] = 'administrator/login';

// 			$this->load->view('include/master_view', $data);

// 		}

// 	}



	public function _validate_admin_credentials()

	{

		$this->load->model('model_admin');



		if ($this->model_admin->can_login()) {

			return true;

		} else {

			$this->form_validation->set_message('_validate_admin_credentials', 'Invalid username/password');

			return false;

		}

	} 
	
	public function tag_student($tutorId, $tagStudent) {
		$this->load->model('model_users');
		$tutorId = base64_decode(urldecode($tutorId));
		$tagStudent = base64_decode(urldecode($tagStudent));
		$userInfo = $this->model_users->get_user_info($tutorId);
		$tutorName = $userInfo->fullname;
		$studentId = $this->model_users->get_user_id_from_email_or_username($tagStudent);
		
		$tag_student = $this->model_users->tag_student($tutorId, $studentId);
		if(isset($tag_student) && empty($tag_student) == FALSE) {
			$this->model_users->login($tag_student);
			$sessionArray = array(
				'profileMessageSuccess' => true,
				'profileMessage' => 'Your account has been successfully tagged under '.$tutorName.' !'
			);
			$this->session->set_userdata($sessionArray);
			redirect(base_url().'profile');
		} else {
			$data['register_error'] = "Failed tagging of " . $tagStudent . " under " . $tutorName . " in SmartJen." ;
		}
		$data['email'] = '';
		$data['tutorId'] = $tutorId;
		$data['content'] = "view_login_registers";
		$this->load->view("include/master_view.php", $data);
	}
	
	public function send_email()
    {
		$sqls = "SELECT * FROM `sj_answers` WHERE `answer_text` LIKE '%░%' LIMIT 10";
		$query = $this->db->query($sqls);
		foreach($query->result() as $row) {
			$answer_text = $row->answer_text;
			$answer_id = $row->answer_id;
			$answer_text = str_replace('░', '', $answer_text);
			$answer_text = str_replace($answer_text, '\\(\\' . $answer_text . '^\\circ\\)', $answer_text);
			echo $answer_text;
		}
		// $this->parent_template('mongkoklyit@gmail.com');

		// $this->student_template('justinmkl094@gmail.com');
		
		// if($this->email->send())
		// {
		// 	$this->email->subject('SmartJen-GLH 2');
		// 	$this->email->to('justinmkl094@gmail.com');
		// 	$messages = "<p>Thank you for signing up! You are one step away to have full access of all features.</p>";
		// 	$messages .= "CDE";
		// 	$this->email->message($messages);
		// 	$this->email->send();
		// 	echo 'Email send.';
		// }
		// else
		// {
		// 	show_error($this->email->print_debugger());
		// }
	}
    
    public function register_student($pemail,$userId,$school_id,$level_id,$key)
	{
		$this->load->model('model_validation');
		$pemail = base64_decode(urldecode($pemail));
		$validate_key = $this->model_validation->validate_key($key);
		if($validate_key == TRUE){
			$emailorusername = $this->model_validation->add_student($key, $userId, $school_id, $level_id, $pemail);
			if(isset($emailorusername)){
				$this->model_validation->login($emailorusername);
			}
		}
		$data['content'] = "view_login_register";
		$this->load->view("include/master_view.php", $data);
	}
	
	public function untagStudent($tutorId_encode, $studentId_encode) 
	{
		$this->load->model('model_users');
		
		$tutorId = base64_decode(urldecode($tutorId_encode));
		$studentId = base64_decode(urldecode($studentId_encode));
		$check_student = $this->model_users->checkStudent($studentId, $tutorId);
		$tutorInfo = $this->model_users->get_user_info($tutorId);
		$tutorEmail = $tutorInfo->email;
		$studentInfo = $this->model_users->get_user_info($studentId);
		$studentFullName = $studentInfo->fullname;
		if($check_student){
			$success = $this->model_users->untag_student($studentId, $tutorId);
			if($success){
				$this->model_users->login($tutorEmail);
				$this->session->set_userdata('profileMessageSuccess', true);
				$this->session->set_userdata('profileMessage', 'Your student '.$studentFullName.' has been untag!');
			}else {
				$this->model_users->login($tutorEmail);
				$this->session->set_userdata('profileMessageSuccess', 0);
				$this->session->set_userdata('profileMessage', 'Your student '.$studentFullName.' has been failed to untag!');
			}
			redirect(base_url().'profile');
		} else {
			redirect('404');
		}
	}

}

