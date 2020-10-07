<?php

/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */



defined('BASEPATH') OR exit('No direct script access allowed');



class Profile extends MY_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->helper('slug');

		$this->load->helper('userRoleHomePage');

		$this->load->library('pagination');

		$this->load->model('model_quiz');

		$this->load->model('model_question');

		$this->load->model('model_worksheet');

		$this->load->model('model_users');

		$this->load->model('M_lesson');

		if($this->uri->segment(2) != 'register_student'){    
			if ($this->session->userdata('is_logged_in') !== 1){ 
				redirect('site/login');
			}
		}
	}



	public function index() {
		
		$userId = $this->session->userdata('user_id');

		$userRole = $this->session->userdata('user_role');

		$userInfo = $this->model_users->get_user_info($userId);
		
		$userFullName = $userInfo->fullname;

		$userName   = $userInfo->username;

		$userEmail = $userInfo->email;

		$profilePic = $userInfo->profile_pic;

		$data['content'] = get_user_role_home_page($userRole);

		$data['userFullName'] = $userFullName;

		$data['userName'] = $userName;

		$data['profilePic'] = $profilePic;

		$data['userEmail'] = $userEmail;

		$data['userRole'] = $userRole;

		$data['userId'] = $userId;

		$subject_id = ($this->session->userdata('subject_id')=="") ? $this->model_users->getStudentSubjectType($userId) : $this->session->userdata('subject_id');

		if($subject_id == NULL){
			$data['analysis_structure'] = $this->model_question->get_strand_structure();
		}else{
			$data['analysis_structure'] = $this->model_question->get_strand_structure($subject_id);
		}

		$data['subject_id'] = $subject_id;

		$data['subject_list'] = $this->model_question->get_subject_list();

		// $sec_student = range(492, 512);

		// if(in_array($userId, $sec_student)) {
		// 	$data['analysis_structure'] = $this->model_question->get_strand_structure(5);
		// } else {
		// 	$data['analysis_structure'] = $this->model_question->get_strand_structure();
		// }

		$profile_message_success = $this->session->userdata('profileMessageSuccess');

		$profile_message = $this->session->userdata('profileMessage');

		if (isset($profile_message_success)) {

			$data['profile_message_success'] = ($profile_message_success == 0)?false:true;

			$data['profile_message'] = $profile_message;

			$this->session->unset_userdata('profileMessageSuccess');

			$this->session->unset_userdata('profileMessage');

		}



		// for tutor 

		if ($userRole == 1) {			

			$worksheets = $this->model_worksheet->get_worksheets($userId, $userRole);

			$mock_exams = $this->model_worksheet->get_mock_exam_worksheets($userId);

	        $data['pagination'] = $this->pagination->create_links();

			foreach ($worksheets AS $worksheet) {

				$worksheet->slug = create_slug($worksheet->worksheet_name);

				$worksheet->subject_name = $this->model_worksheet->get_subject_name($worksheet->subject_type);

			}

			$students = $this->model_users->get_student_list($userId);
			
			// $children = $this->model_users->get_children_list($userId);

			$data['worksheets'] = $worksheets;
			
			$data['mock_exams'] = $mock_exams;

			$data['students'] = $students;

			// $data['children'] = $children;

			$data['schools'] = $this->model_question->get_school_list();

			$data['levels'] = $this->model_question->get_level_list();

			$student_id_array = array();

			foreach ($students as $student) {

				$student_id_array[] = $student->student_id;

			}

			$data['student_performance'] = $this->model_users->get_performance($student_id_array, 2);

			$tutorArray = array(123, 453, 478, 490);

			$data['tutorArray'] = $tutorArray;

			$data['student_group'] = $this->model_users->get_group($userId);


		} elseif ($userRole == 2) { // if it's a student

			// $quizzes = $this->model_users->get_quiz_list($userId);

			$quizzes = $this->model_users->get_quiz_list_student($userId);
			
			foreach ($quizzes as $quiz) {

				$attempt_ids = $this->model_quiz->get_attempt_ids($quiz->id);

				$quiz->name = $this->model_worksheet->get_worksheet_name_from_id($quiz->worksheetId);

				$quiz->createdBy = $this->model_users->get_username_from_id($this->model_worksheet->get_ownerId_from_worksheetId($quiz->worksheetId));

				$quiz->createdById = $this->model_worksheet->get_ownerId_from_worksheetId($quiz->worksheetId);
				
				$quiz->archive = $this->model_worksheet->get_archive_worksheet($quiz->worksheetId);

				$quiz->numOfAttempt = $this->model_quiz->count_number_of_attempt($quiz->id);

				$quiz->subject = $this->model_worksheet->get_worksheet_subject($quiz->worksheetId);

				if ($quiz->numOfAttempt > 0) {

					$quiz->lastCompletedDate = $this->model_quiz->get_last_attempt_date($quiz->id);

				}

				$questionIdList = array();
				$questionDifficultyList = array();
				
				# Returns an object with all the question_ids 
				$questionIdObj = ($this->model_worksheet->get_questions_id_from_worksheets_id($quiz->worksheetId))->result();
				
				foreach($questionIdObj as $questionId) {
					$questionIdList[] = $questionId->question_id;
				}

				foreach($questionIdList as $questionId) {
					$questionDifficultyList[] = ($this->model_question->get_question_from_id($questionId))->difficulty;
					
				}	
				# Add in each qns difficulty in $data['quizzes']
				$quiz->questionDifficultyList = $questionDifficultyList;
			}

			// if(in_array($userId, $sec_student)) {
			// 	$data['student_performance'] = $this->model_users->get_performance(array($userId),5);
			// } else {
			// 	$data['student_performance'] = $this->model_users->get_performance(array($userId),2);
			// }

			$data['analysis_structure'] = $this->model_question->get_strand_structure($subject_id);

			if($subject_id == NULL){
				$data['student_performance'] = $this->model_users->get_performance(array($userId),2);
			}else{
				$data['student_performance'] = $this->model_users->get_performance(array($userId), $subject_id);
			}

			if($subject_id == 2 || $subject_id == 3) {
				$subject_id_arr = array(1,2,3);
			} else {
				$subject_id_arr = array($subject_id);
			}
			
			$data['subject_list'] = $this->model_question->get_subject_list($subject_id_arr);

			$data['subject'] = $this->model_users->getStudentStatusTag($userId);
			
			$data['quizzes'] = $quizzes;

			$data['lessons'] = $this->M_lesson->get_lesson_list_student($userId);


		} elseif ($userRole == 3) { // if it's a parent

			$children = $this->model_users->get_children_list($userId);

			$students = $this->model_users->get_student_list($userId);

			$worksheets = $this->model_worksheet->get_worksheets($userId, $userRole);

			foreach ($worksheets AS $worksheet) {

				$worksheet->slug = create_slug($worksheet->worksheet_name);

				$worksheet->subject_name = $this->model_worksheet->get_subject_name($worksheet->subject_type);

			}

			$data['schools'] = $this->model_question->get_school_list();

			$data['levels'] = $this->model_question->get_level_list(2);

			$data['students'] = $students;

			$data['children'] = $children;

			$data['worksheets'] = $worksheets;

			$data['pagination'] = $this->pagination->create_links();

		}
		
		$this->load->view('include/master_view', $data);

	}



	public function edit($student_id = NULL) {

		$user_role = $this->session->userdata('user_role');
		
		if (isset($student_id) && empty($student_id) === false) {

			if ($user_role == 2) {

				redirect('404');

			} elseif ($user_role == 1) {

				$this->tutor_edit_student($student_id);

			}

		} else {

			if ($user_role == 2) {

				$this->edit_student();

			} elseif ($user_role == 1) {

				$this->edit_tutor();

			} elseif ($user_role == 3) {
				$this->edit_parent();
			}

		}

	}



	private function edit_tutor($data = NULL) {

        $this->load->model('model_users');

        $userId = $this->session->userdata('user_id');

        $post = $this->input->post();


        if (isset($post) && empty($post) === false) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('profile_fullName', 'Fullname', 'required|trim');

            $this->form_validation->set_rules('profile_agency_link', 'Agency Link', 'valid_url|max_length[100]');



            if ($this->form_validation->run()) {

                if ($this->model_users->update_user_info($userId)) {

                    //$data['update_success'] = "Successfully updated profile info.";

					$this->session->set_flashdata('update_success', 'Successfully updated profile info.');
					redirect(base_url() . 'profile/edit');
                } else {

                    //$data['update_error'] = "Error in updating profile info. Please try again later or contact administrator at admin@smartjen.sg";

					$this->session->set_flashdata('update_error', 'Error in updating profile info. Please try again later or contact administrator at admin@smartjen.sg');
					redirect(base_url() . 'profile/edit');
                }



            } else {

                //$data['update_error'] = validation_errors();

				$this->session->set_flashdata('update_error', validation_errors());
				redirect(base_url() . 'profile/edit');
            }



        }



        $user_info = $this->model_users->get_user_info($userId);

        $data['profile_email']= $user_info->email;

        $data['profile_username'] = $user_info->username;

        $data['profile_fullName'] = $user_info->fullname;

        $data['profile_picture'] = $user_info->profile_pic;

        $data['profile_mobile'] = $user_info->contact_no;
        $data['profile_agency_link'] = $user_info->agency_link;

        $data['profile_profession'] = $this->model_users->get_profession();

        $data['profile_chosen_profession'] = $this->model_users->get_profession_name_from_id($user_info->profession);

        $data['profile_specialization'] = $this->model_users->get_specialization_list();

        $data['profile_chosen_specialization'] = $this->model_users->get_tutor_specialization($userId);

        $data['content'] = "profile/view_tutor_update_profile";

        $this->load->view("include/master_view.php", $data);

    }



    private function edit_student($data = NULL) {

		$this->load->model('model_users');

		$userId = $this->session->userdata('user_id');
		$post = $this->input->post();
		$postEmail = $this->input->post('profile_email');
		$user_info = $this->model_users->get_user_info($userId);
		$userEmail = $user_info->email;
		$userMobile = $user_info->contact_no;
		$studentInfo = $this->model_users->get_student_info($userId);
		$stuLevel = $studentInfo->level_id;
		$stuSchool = $studentInfo->school_id;
		$schools = $this->model_question->get_school_list();
		$levels = $this->model_question->get_level_list();
		
		if($postEmail == $userEmail){
			if (isset($post) && empty($post) === false) {
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('profile_fullName', 'Fullname', 'required|trim');
				$this->form_validation->set_rules('profile_email', 'Email', 'trim|valid_email');
				
				if ($this->form_validation->run()) {
					
					if ($this->model_users->update_user_info($userId)) {
						$this->session->set_flashdata('update_success', 'Successfully updated profile info.');
						redirect(base_url() . 'profile/edit');
					} else {
						$this->session->set_flashdata('update_error', 'Error in updating profile info. Please try again later or contact administrator at admin@smartjen.sg');
						redirect(base_url() . 'profile/edit');
					}
					
				} else {
					$this->session->set_flashdata('update_error', validation_errors());
					redirect(base_url() . 'profile/edit');
				}
			}
		}else {
			if (isset($post) && empty($post) === false) {
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('profile_fullName', 'Fullname', 'required|trim');
				$this->form_validation->set_rules('profile_email', 'Email', 'trim|valid_email');
				
				if ($this->form_validation->run()) {
					
					if ($this->model_users->update_user_info($userId)) {
						$this->session->set_flashdata('update_success', 'Successfully updated profile info.');
						redirect(base_url() . 'profile/edit');
					} else {
						$this->session->set_flashdata('update_error', 'Error in updating profile info. Please try again later or contact administrator at admin@smartjen.sg');
						redirect(base_url() . 'profile/edit');
					}
					
				} else {
					$this->session->set_flashdata('update_error', validation_errors());
					redirect(base_url() . 'profile/edit');
				}
			}
		}
		
		$data['schools'] = $schools;
		$data['levels'] = $levels;
		$data['stuLevel'] = $stuLevel;
		$data['stuSchool'] = $stuSchool;
		$data['profile_email']= $userEmail;
		$data['profile_mobile']= $userMobile;
		$data['profile_username'] = $user_info->username;
		$data['profile_fullName'] = $user_info->fullname;
		$data['profile_picture'] = $user_info->profile_pic;
		$data['content'] = "profile/view_student_update_profile";
		$this->load->view("include/master_view.php", $data);

	}

	private function edit_parent() {
		$this->load->model('model_users');

        $userId = $this->session->userdata('user_id');

        $post = $this->input->post();


        if (isset($post) && empty($post) === false) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('profile_fullName', 'Fullname', 'required|trim');

            // $this->form_validation->set_rules('profile_agency_link', 'Agency Link', 'valid_url|max_length[100]');



            if ($this->form_validation->run()) {

                if ($this->model_users->update_user_info($userId)) {

					$this->session->set_flashdata('update_success', 'Successfully updated profile info.');
					redirect(base_url() . 'profile/edit');
                } else {

					$this->session->set_flashdata('update_error', 'Error in updating profile info. Please try again later or contact administrator at admin@smartjen.sg');
					redirect(base_url() . 'profile/edit');
                }

            } else {

				$this->session->set_flashdata('update_error', validation_errors());
				redirect(base_url() . 'profile/edit');
            }

        }

        $user_info = $this->model_users->get_user_info($userId);

        $data['profile_email']= $user_info->email;

        $data['profile_username'] = $user_info->username;

        $data['profile_fullName'] = $user_info->fullname;

        $data['profile_picture'] = $user_info->profile_pic;

		$data['profile_mobile'] = $user_info->contact_no;
		
        // $data['profile_agency_link'] = $user_info->agency_link;

        // $data['profile_profession'] = $this->model_users->get_profession();

        // $data['profile_chosen_profession'] = $this->model_users->get_profession_name_from_id($user_info->profession);

        // $data['profile_specialization'] = $this->model_users->get_specialization_list();

        // $data['profile_chosen_specialization'] = $this->model_users->get_tutor_specialization($userId);

        $data['content'] = "profile/view_parent_update_profile";

        $this->load->view("include/master_view.php", $data);
	}



	private function tutor_edit_student($student_id) {

		$this->load->model('model_users');

		$userId = $this->session->userdata('user_id');



		

		if ($this->model_users->is_authorized_to_edit_student($student_id)) {

			$post = $this->input->post();

			

			if (isset($post) && empty($post) === false) {

				if ($this->model_users->update_user_info($userId)) {

					$data['update_success'] = "Successfully updated profile info.";

				} else {

					$data['update_error'] = "Error in updating profile info. Please try again later or contact administrator at admin@smartjen.sg";

				}

			}

	

			$user_info = $this->model_users->get_user_info($userId);

			$data['student_id'] = $student_id;

			$data['profile_email']= $user_info->email;

			$data['profile_username'] = $user_info->username;

			$data['profile_fullName'] = $user_info->fullname;

			$data['profile_picture'] = $user_info->profile_pic;

			$data['content'] = "profile/view_tutor_update_student_profile";

			$this->load->view("include/master_view.php", $data);

		} else {

			redirect('404');

		}

        

	}

	
	
	public function email_validation()
	{
		$this->load->model('model_users');
		$email = $this->input->post('profile_email');
		$emailCheck = $this->model_users->validate_emails($email);
		
		if ($emailCheck){
			return true;
		}else{
			$this->form_validation->set_message('email_validation', 'The email is already taken. Please register with another email.');
			return false;
		}
	}
	

    public function change_password() {

        $this->load->model('model_users');

        $userId = $this->session->userdata('user_id');

        $post = $this->input->post();



        if (isset($post) && empty($post) === false) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('profile_password', 'Password', 'required|trim|min_length[6]');

            $this->form_validation->set_rules('profile_cpassword', 'Confirm Password', 'required|trim|matches[profile_password]');



            if ($this->form_validation->run()) {

                if ($this->model_users->update_password($userId)) {

                    $data['update_success'] = "Successfully updated password.";

                } else {

                    $data['update_error'] = "Error in updating password. Please try again later or contact administrator at admin@smartjen.sg";

                }



            } else {

                $data['update_error'] = validation_errors();

            }

        }



        $data['content'] = "profile/view_change_password";

        $this->load->view("include/master_view.php", $data);

    }



	public function upload_profile_pic() {

		$userId = $this->session->userdata('user_id');

		$this->load->model('model_users');

		$config['upload_path'] = 'img/profile';

		$config['allowed_types'] = '*';

		$config['max_size']    = '300';

		// $config['max_width']  = '1024';

		// $config['max_height']  = '768';

		$config['overwrite'] = 'true';

		$config['file_name'] = 'profile_img_' . $userId;



		$this->load->library('upload');

        $this->upload->initialize($config);



        $allowed_types = array('jpg', 'png', 'gif', 'jpeg');



        if (isset($_FILES['userfile'])) {

        	$file_name = $_FILES['userfile']['name'];

        	$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));



        	if (in_array($ext, $allowed_types)) {

        		if (!$this->upload->do_upload())

				{

					$data['update_error'] = $this->upload->display_errors();

				} else {

					$upload_data = $this->upload->data();

					if ($this->model_users->update_profile_pic($userId, $upload_data['file_name'])) {

						$this->session->set_userdata('profile_pic', $upload_data['file_name']);

						$data['update_success'] = "Successfully updated profile picture.";

					} else {

						$data['update_error'] = "Error in uploading profile image. Please try again later or contact administrator at admin@smartjen.sg";

					}

					

				}

			} else {

				$data['update_error'] = 'Please upload only jpg, png, gif or jpeg file';

			}

		}

	

	$user_role = $this->session->userdata('user_role');

	
	if ($user_role == 2) {

		$this->edit_student($data);

	} elseif ($user_role == 1) {

		$this->edit_tutor($data);

	}

}



	public function worksheet($worksheetId) {

		$this->load->model('model_question');

		$userId = $this->session->userdata('user_id');

		$worksheetName = $this->model_worksheet->get_worksheet_name_from_id($worksheetId);

		$worksheetSubject = $this->model_worksheet->get_worksheet_subject_id($worksheetId);

		if ($this->model_worksheet->is_mock_exam($worksheetId)) {

			$requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheetId);

			$postData = array(

				'gen_tutor'     => $requirement->me_tutor,
				'gen_me'        => $requirement->me_num,
				'gen_year'      => $requirement->me_year,
				'gen_randomize' => $requirement->me_randomized,
				'gen_level'     => $requirement->me_level

			);



			$questionList = $this->model_question->get_mock_exam_question_list($postData);

			$answerList = $this->model_question->get_mock_answer_list($questionList);

			$data['content'] = 'profile/view_mock_exam_worksheet';

		} else {

			$requirementId = $this->model_worksheet->get_requirement_from_worksheetId($worksheetId)->requirement_id;

			$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);

			$questionList = array();
			$substrandList = array();
			$categoryList = array();
			$strategyList = array();
			$quetypeList = array();


			foreach ($questions->result() AS $question) {

				$questionDetail = $this->model_question->get_question_from_id($question->question_id);
				$questionList[$question->question_number] = $questionDetail;
				$substrandList[] = $this->model_question->get_substrand_from_question_id($question->question_id);
				$categoryList[] = $this->model_question->get_category_from_question_id($question->question_id, NULL, $requirementId);
				$strategyList[] = $this->model_question->get_strategy_from_question_id($question->question_id);
				$quetypeList[] = $question->question_type;
				$questionStatus[$question->question_number] = $this->model_question->get_question_status($question->question_id); 
			}



			// have to hack this to get the sequence correct

			$question_order = array();

			for ($i = 1; $i <= count($questionList); $i++) {

				$question_order[] = $questionList[$i];

			}

			$answerList = $this->model_question->get_answer_list($question_order);

			$data['content'] = 'profile/view_worksheet';

		}

		# Returns an object containing student_ids this worksheet is assigned to
		$assigned_to = $this->model_worksheet->get_student_id_from_worksheet_id($worksheetId);

		if(isset($assigned_to) && empty($assigned_to) == FALSE) {

			foreach ($assigned_to as $assigned) {
				$student_id[] = $assigned->assignedTo;
			}

			$data['student_id'] = $student_id;
		}

		$user_id = $this->session->userdata('user_id');
		$user_role = $this->model_users->get_user_role_from_user_id($user_id);

		$data['user_id'] = $user_id;
		$data['user_role'] = $user_role;
		$data['questionList'] = $questionList;
		$data['questionStatus'] = $questionStatus;
		$data['answerList'] = $answerList;
		$data['que_type'] = $this->model_worksheet->get_question_type_from_requirement($worksheetId);
		$data['worksheetName'] = $worksheetName;
		$data['worksheetId'] = $worksheetId;
		$data['substrandList'] = $substrandList;
		$data['categoryList'] = $categoryList;
		$data['strategyList'] = $strategyList;
		$data['tutor_id'] = $this->model_worksheet->get_tutor_id_from_worksheet_id($worksheetId)[0]->created_by;
		$data['quetypeList'] = $quetypeList;
		$data['user_id'] = $userId;
		$data['worksheetSubject'] = $worksheetSubject;
		$this->load->view('include/master_view', $data);

	}



	/*
		This function is to get the requirement, save in session and populate it in the smartgen homepage
	*/

	public function designWorksheet($worksheetId = NULL) {

		if (isset($worksheetId) && empty($worksheetId) === false) {

			$this->getRequirement($worksheetId);

			redirect(base_url().'smartgen/designWorksheet/'.$worksheetId);

		} else {

			redirect('404');

		}

		

	}



	public function designMEWorksheet($worksheetId = NULL) {

		if (isset($worksheetId) && empty($worksheetId) === false) {

			$this->getMERequirement($worksheetId);

			redirect(base_url().'smartgenkrtc/designWorksheetkrtc/'.$worksheetId);

		} else {

			redirect('404');

		}

	}



	/*
		This function is to get the requirement, question list , save in session and populate in customize page
	*/

	public function customizeWorksheet($worksheetId = NULL) {

		if (isset($worksheetId) && empty($worksheetId) === false) {

			$user_id = $this->session->userdata('user_id');
			
			$this->load->model('model_question');

			$this->getRequirement($worksheetId);

			$this->getQuestionList($worksheetId);

			redirect(base_url().'smartgen/generateWorksheet/'.$worksheetId);

		} else {

			redirect('404');

		}

		

	}



	/*
		This function is to get the requirement, save in session
	*/

	private function getRequirement($worksheetId) {

		$requirement = $this->model_worksheet->get_requirement_from_worksheetId($worksheetId);

		

		$sessionArray = array(

			'worksheetNumOfQuestion' => $requirement->num_question,

			'worksheetDifficulty'    => $requirement->difficulty,

			'worksheetLevel'         => $requirement->level_ids,

			'worksheetQueType'       => $requirement->question_type,
			'worksheetTopic'         => explode(",", $requirement->topic_ids),

			'worksheetSubstrand'     => explode(",", $requirement->substrand_ids),

			'requirementId'          => $requirement->requirement_id

		);



		$this->session->set_userdata($sessionArray);

	}



	private function getMERequirement($worksheetId) {

		$requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheetId);

		
-
		
		$sessionArray = array(

			'MESelectedTutor' => $requirement->me_tutor,

			'MESelectedMe'    => $requirement->me_num,

			'MESelectedYear'  => $requirement->me_year,

			'MERandomize'     => $requirement->me_randomized,

			'MESelectedLevel' => $requirement->me_level

		);



		$this->session->set_userdata($sessionArray);

	}



	private function getQuestionList($worksheetId) {

		$this->load->model('model_question');

		$worksheetQuery = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);
		$questionIdArray = array();
		foreach ($worksheetQuery->result() AS $question) {

			$questionIdArray[$question->question_number-1] = $question->question_id;

		}

		ksort($questionIdArray);



		$sessionArray = array(

			'questionArray' => $questionIdArray

		);



		$post = array();

		$post['gen_level'] = $this->session->userdata('worksheetLevel');

		$post['gen_substrand'] = $this->session->userdata('worksheetSubstrand');

		$post['gen_topic'] = $this->session->userdata('worksheetTopic');

		$post['gen_difficulties'] = $this->session->userdata('worksheetDifficulty');

		$post['gen_strategy'] = $this->session->userdata('worksheetStrategy');


		$this->model_question->get_questions_id_from_requirement($post, $this->session->userdata('user_id'));



		$this->session->set_userdata($sessionArray);

	}


	public function deleteWorksheet() {

		$worksheetId = $this->input->post('worksheetId');

		if (!isset($worksheetId)) {

			redirect('404');

		}

		$success = $this->model_worksheet->delete_worksheet($worksheetId);

		if ($success) {

			$this->session->set_userdata('profileMessageSuccess', true);

			$this->session->set_userdata('profileMessage', 'You have successfully deleted the worksheet');

		}  else {

			$this->session->set_userdata('profileMessageSuccess', 0);

			$this->session->set_userdata('profileMessage', 'Failure in deleting worksheet. Please try again later or contact administrator.');

		}



		redirect(base_url().'profile');

	}



	public function saveWorksheetAsPDF($worksheetId, $questionType) {

		if (!isset($worksheetId) || !isset($questionType)) {

			redirect('404');

		}

		$this->load->library('m_pdf');

		$this->load->helper('shuffleassoc');

		$this->load->model('model_question');



		$worksheetName = $this->model_worksheet->get_worksheet_name_from_id($worksheetId);

		$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);



		$questionList = array();

		$correctAnswers = array();

		foreach ($questions->result() AS $question) {

			$questionDetail = $this->model_question->get_question_from_id($question->question_id);

			$questionList[$question->question_number] = $questionDetail;

		}

	

		$html = '
		<!DOCTYPE HTML>
		<html>
		<head>
		<style>
		@page {
		  size: auto;
		  odd-header-name: html_myHeader1;
		  odd-footer-name: html_myFooter1;
		  margin-top: 0.5cm;
		}
		@page answer {
		    odd-header-name: html_myHeader2;
		    odd-footer-name: html_myFooter1;
		}
		div.answer {
		    page-break-before: always;
		    page: answer;
		}
		body {
			font-family: "Lato", sans-serif;
		}
		</style>
		</head>
		<body>
		<htmlpageheader name="myHeader1" style="display:none">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
			    <td width="33%" align="left">'.$worksheetName.' - Questions</td>
			    <td width="33%"></td>
			    <td width="33%" align="right"><img src="'.base_url().'img/smartjen-logo-text.jpg" style="width: 5cm"></td>
			    </tr>
			</table>
		</htmlpageheader>
		<htmlpageheader name="myHeader2" style="display:none">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
			    <td width="33%" align="left">'.$worksheetName.' - Answers</td>
			    <td width="33%"></td>
			    <td width="33%" align="right"><img src="'.base_url().'img/smartjen-logo-text.jpg" style="width: 5cm"></td>
			    </tr>
			</table>
		</htmlpageheader>
		<htmlpagefooter name="myFooter1" style="display:none">
		<table width="100%" style="vertical-align: bottom; font-size: 10pt; 
		    color: #000000; border-top: 1px solid #333"><tr>
		    <td width="33%" align="left">Smartjen &copy; {DATE Y}</td>
		    <td width="33%"></td>
		    <td width="33%" align="right" style="font-style: italic;">Page {PAGENO}/{nbpg}</td>
		    </tr>
		</table>
		</htmlpagefooter>
		';



		switch ($questionType) {

			case "MCQ":

				for ($i = 1, $count = count($questionList) ; $i <= $count ; $i++) {

					//print question

					$html .= '<p>'. $i . ') ' . $questionList[$i]->question_text. '</p>';



					//print image

					if ($questionList[$i]->graphical == 1) {

						$imagePath = 'img/questionImage/' . $questionList[$i]->year . '-' . $questionList[$i]->school_id . '-' . $questionList[$i]->level_id . '-' . $questionList[$i]->question_id . '.jpg';

						$html .= '<div style="text-align: center"><img src="'.base_url().$imagePath.'"></div><br><br>';

					}



					//print answer 

					$answerOptions = $this->model_question->get_answer_option_from_question_id($questionList[$i]->question_id);

					$correctAnswerId = $this->model_question->get_correct_answer_from_question_id($questionList[$i]->question_id);



					//shuffling the ordering of answer options

					$answerOptions = shuffleAssoc($answerOptions);



					$MCQ = array(

						'1' => "A",

						'2' => "B",

						'3' => "C", 

						'4' => "D"

					);



					$mcqCount = 1;

					foreach ($answerOptions AS $option) {

						$html .= $MCQ[$mcqCount] . ') ' . $option->answer_text . '<br>';

						if ($option->answer_id == $correctAnswerId) {

							$correctAnswers[$i] = $MCQ[$mcqCount];

						}

						$mcqCount++;

					}





					if ($i !== $count) {

						$html .= "<br><br><br>";

					}

				}

				break;



			case "openEnded":

				for ($i = 1, $count = count($questionList) ; $i <= $count ; $i++) {

					//print question

					$html .= '<p>'. $i . ') ' . $questionList[$i]->question_text. '</p>';



					//print image

					if ($questionList[$i]->graphical == 1) {

						$imagePath = 'img/questionImage/' . $questionList[$i]->year . '-' . $questionList[$i]->school_id . '-' . $questionList[$i]->level_id . '-' . $questionList[$i]->question_id . '.jpg';

						$html .= '<div style="text-align: center"><img src="'.base_url().$imagePath.'"></div><br><br>';

					}



					$html .= "<p>Ans: _________________________</p>";



					$answerId = $this->model_question->get_correct_answer_from_question_id($questionList[$i]->question_id);

					$correctAnswers[$i] = $this->model_question->get_answer_text_from_answer_id($answerId);



					if ($i !== $count) {

						$html .= "<br><br>";

					}

				}

				break;



			case "customized":

				for ($i = 1, $count = count($questionList) ; $i <= $count ; $i++) {

					//print question

					$html .= '<p>'. $i . ') ' . $questionList[$i]->question_text. '</p>';



					//print image

					if ($questionList[$i]->graphical == 1) {

						$imagePath = 'img/questionImage/' . $questionList[$i]->year . '-' . $questionList[$i]->school_id . '-' . $questionList[$i]->level_id . '-' . $questionList[$i]->question_id . '.jpg';

						$html .= '<div style="text-align: center"><img src="'.base_url().$imagePath.'"></div><br><br>';

					}



					$type = 'question_type_' . $i;  

					if (strcmp($this->input->post($type), "MCQ") == 0) {

						//print answer 

						$answerOptions = $this->model_question->get_answer_option_from_question_id($questionList[$i]->question_id);

						$correctAnswerId = $this->model_question->get_correct_answer_from_question_id($questionList[$i]->question_id);



						//shuffling the ordering of answer options

						$answerOptions = shuffleAssoc($answerOptions);



						$MCQ = array(

							'1' => "A",

							'2' => "B",

							'3' => "C", 

							'4' => "D"

						);



						$mcqCount = 1;

						foreach ($answerOptions AS $option) {

							$html .= $MCQ[$mcqCount] . ') ' . $option->answer_text . '<br>';

							if ($option->answer_id == $correctAnswerId) {

								$correctAnswers[$i] = $MCQ[$mcqCount];

							}

							$mcqCount++;

						}

					} else if (strcmp($this->input->post($type), "openEnded") == 0) {

						$html .= "<p>Ans: _________________________</p>";



						$answerId = $this->model_question->get_correct_answer_from_question_id($questionList[$i]->question_id);

						$correctAnswers[$i] = $this->model_question->get_answer_text_from_answer_id($answerId);

					}



					

					if ($i !== $count) {

						$html .= "<br><br>";

					}

				}

				break;

		}



		$html .= '<div class="answer">';

		for ($i = 1, $count = count($correctAnswers) ; $i <= $count; $i++) {

			$html .= $i . ") " . $correctAnswers[$i] . "<br><br>";

		}



		$html .= '
			</div>
			</body></html>
		';



		$this->mpdf->WriteHTML($html);



		$this->mpdf->SetTitle($worksheetName);

		$this->mpdf->Output(trim($worksheetName).'.pdf','D'); 

	}



	public function checkUserExist() {

		if ($this->input->is_ajax_request()) {

			$this->load->model('model_users');

			$userName = $this->input->post('userName');

			$parEmail = $this->input->post('parEmail');

			$parId = $this->model_users->get_user_id_from_email_or_username($parEmail);

			if(isset($parId) && !empty($parId) == false) {
				$childNo = $this->model_users->check_children_no($parId);

				if(childNo > 3) {
					$returnArray['childNo'] = true;
				} else {
					$returnArray['childNo'] = false;
				}
			}

			$usernameExist = $this->model_users->check_user_exist($userName);

			$returnArray['usernameExist'] = $usernameExist;

			echo json_encode($returnArray);

		} else {

			redirect('404');

		}

	}

	public function checkGroupExist() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('model_users');
			$groupName = $this->input->post('groupName');
			$userId = $this->input->post('userId');
			$groupExist = $this->model_users->check_group_exist($groupName,$userId);
			$returnArray['groupExist'] = $groupExist;
			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}

	public function createGroup() {
		$userId = $this->session->userdata('user_id');
		$this->load->model('model_users');
		$checkGroupExist = $this->model_users->get_group($userId);
		$html = "";
		if(!$checkGroupExist) {
			$colors = array('#DA5250','#F1AB4C','#35BCAA','#3BB395','#D7E9E2');
			$i = 1;
			foreach ($colors as $color) {
				$data = array(
					'group_name' => 'Group '.$i,
					'color' => $color,
					'text_color' => '#FFFFFF',
					'created_by' => $userId,
					'created_date' => date('Y-m-d H:i:s')
				);
				$this->db->insert('sj_student_group',$data);
				$groupId = $this->db->insert_id();
				$html .= '<div class="group_label popup-label label-'.$groupId.'" id="label-'.$groupId.'" onclick=\'selectGroup("'.$groupId.'", "Group '.$i .'", "#FFFFFF", "'.$color.'")\' style="cursor:pointer;color:#fff;background-color: '.$color.'"><span class="icon-sm fa fa-check card-label-selectable-icon light hide group-'.$groupId.'"></span> Group '.$i .'<span onclick=\'editMode("'.$groupId.'", "Group '.$i .'", "#FFFFFF", "'.$color.'")\'  class="icon-edit fa fa-edit edit-mode card-label-selectable-icon edit-group-'.$groupId.' hide"></span></div>';
				$i++;
			}
		}
		echo $html;
	}

	function getGroup($student_id,$adult_id,$format="html") {
		$groupInfo = $this->model_users->get_student_group($adult_id,$student_id);
		if($format=="html") {
			if($groupInfo['group_id']!='0')
				echo '<div id="group_'.$groupInfo['group_id'].'" class="group_label" style="max-width: 100px !important;color:#FFFFFF;background-color:'.$groupInfo['color'].'">'.$groupInfo['group_name'].'</div>';
			else
				echo '-';
		} elseif($format=="id") {
			if($groupInfo['group_id']!='0')
				echo $groupInfo['group_id'];
			else
				echo '0';
		}
	}

	function newGroup() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('model_users');
			$postData = $this->input->post();
			$created_by = $postData['adult_id'];
			$group_name = $postData['group_name'];
			$color = $postData['color'];
			if ($this->model_users->validate_group($group_name)) {
				$data = array(
					'group_name' => $group_name,
					'color' => $color,
					'text_color' => '#FFFFFF',
					'created_by' => $created_by,
					'created_date' => date('Y-m-d H:i:s')
				);
				$this->db->insert('sj_student_group',$data);
				$returnArray['success'] = true;
				$groupId = $this->db->insert_id();
				$returnArray['group_id'] = $groupId;
			} else {
				$returnArray['success'] = false;
				$returnArray['group_id'] = '0';
			}
			echo json_encode($returnArray);
		} else 
			echo json_encode(array());
	}

	function editGroup() {
		if ($this->input->is_ajax_request()) {
			$postData = $this->input->post();
			$created_by = $postData['adult_id'];
			$group_name = $postData['group_name'];
			$color = $postData['color'];
			$group_id = $postData['group_id'];
			$data = array(
				'group_name' => $group_name,
				'color' => $color,
				'text_color' => '#FFFFFF',
				'created_by' => $created_by
			);
			$this->db->where('id',$group_id);
			$this->db->update('sj_student_group',$data);
			$returnArray['success'] = true;
			$returnArray['group_id'] = $group_id;
			echo json_encode($returnArray);
		} else 
			echo json_encode(array());
	}

	function deleteGroup() {
		if ($this->input->is_ajax_request()) {
			$postData = $this->input->post();
			$group_id = $postData['group_id'];
			$userId = $this->session->userdata('user_id');
			$this->db->where('id',$group_id);
			$this->db->where('created_by',$userId);
			$this->db->delete('sj_student_group');
			$this->db->where('group_id',$group_id);
			$this->db->delete('sj_student_group_relation');
			$returnArray['success'] = true;
			$returnArray['group_id'] = $group_id;
			echo json_encode($returnArray);
		} else 
			echo json_encode(array());
	}

	function save_student_group() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('model_users');
			$group_id = $this->input->post('group_id');
			$student_id = $this->input->post('student_id');
			$adult_id = $this->input->post('adult_id');
			$check = $this->model_users->get_student_group($adult_id, $student_id);
			if($check['group_id']=='0') {
				$del_qry = "Delete from sj_student_group_relation where `adult_id` ='". $adult_id . "' and student_id='". $student_id . "' ";
				$this->db->query($del_qry);
				$data = array(
					'student_id' => $student_id,
					'adult_id' => $adult_id,
					'group_id' => $group_id
				);
				$this->db->insert('sj_student_group_relation',$data);
			} else {
				$data = array(
					'group_id' => $group_id
				);
				$this->db->where('student_id',$student_id);
				$this->db->where('adult_id',$adult_id);
				$this->db->update('sj_student_group_relation',$data);
			}
			$returnArray['success'] = true;
			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}

	public function _validate_group()
	{
		$this->load->model('model_users');
		$group = $this->input->post('group_name');
		if ($this->model_users->validate_group($group)) {
			return true;
		} else {
			$this->form_validation->set_message('_validate_credentials', 'Invalid Group');
			return false;
		}
	}
	
	public function createStudent() {
		$this->load->model('model_users');
		$postData = $this->input->post();
		$userId = $this->session->userdata('user_id');
		$userInfo = $this->model_users->get_user_info($userId);
		if (isset($postData) && empty($postData) === false) {
			$this->load->library("form_validation");
			$this->form_validation->set_rules('create_student_username', 'Student Username', 'required|trim|max_length[30]|alpha_dash|callback__validate_username');
			$this->form_validation->set_rules('create_student_fullname', 'Student Fullname', 'required|trim');
			$this->form_validation->set_rules('create_parent_email', 'Parent Email', 'required|valid_email|trim');
			$this->form_validation->set_rules('create_student_email', 'Student Email', 'valid_email|trim');
			$this->form_validation->set_rules('create_student_password', 'Password', 'required|trim|min_length[5]');
			$this->form_validation->set_rules('create_student_cpassword', 'Confirm Password', 'required|trim|matches[create_student_password]');
			if ($this->form_validation->run()) {
				$key = sha1(uniqid('jen'));
				$email = $postData['create_student_email'];
				$parEmail = $postData['create_parent_email'];
				$mobile = $postData['create_student_mobile'];
				$school_id = urlencode(base64_encode($postData['create_student_school_id']));
				$level_id = urlencode(base64_encode($postData['create_student_level_id']));
				$mobile = $postData['create_student_mobile'];
				$encodeParEmail = urlencode(base64_encode($parEmail));
				$encodeUserId = urlencode(base64_encode($userId));
				$parName = $userInfo->fullname;
				$this->load->library('email');
				$this->email->from('noreply@smartjen.com', "SmartJen");
				$par_key = sha1(uniqid('jen'));

				$parId = $this->model_users->get_user_id_from_email_or_username($parEmail);

				if($parId !== null) {

					// existing parent account

					$childNo = $this->model_users->check_children_no($parId);

					if($childNo < 3) {
					
						if(isset($email) && empty($email) == TRUE){
							// parent email only
							$this->email->to($parEmail);
							$this->email->Subject('SmartJen Accounts Activation');
							
							$message = "<p>Dear Parent, </p>";
							$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
							$message .= "<p>Please <a href='".base_url()."site/register_exist_parent/$encodeParEmail/$par_key/$school_id/$level_id/$key/$encodeUserId'>click</a> here to activate your account.</p>";
							$message .= "<p>Your Sincerely,";
							$message .= "<br>Powered by SmartJen</p>";
							$this->email->message($message);
							
							if($this->model_users->create_temp_student($key, $par_key)){
								if($this->email->send()){
									$sessionArray = array(
										'profileMessageSuccess' => true,
										'profileMessage' => 'An activation email has been sent to '.$parEmail.'. Please activate the account for verification purpose.'
									);
									$this->session->set_userdata($sessionArray);
								} else {
									$sessionArray = array(
										'profileMessageSuccess' => false,
										'profileMessage' => 'Failed to sent out activation email to student.'
									);
									$this->session->set_userdata($sessionArray);
								}
							} else {
								$sessionArray = array(
									'profileMessageSuccess' => false,
									'profileMessage' => 'Failed to add student.'
								);
								$this->session->set_userdata($sessionArray);
							}
							redirect(base_url() . 'profile');
						}else {
							
							// with parent email and student email
							$this->email->to($parEmail);
							$this->email->Subject('SmartJen Parent Account Activation');
							
							$message = "<p>Dear Parent, </p>";
							$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
							$message .= "<p>Please <a href='".base_url()."site/register_exist_parent/$encodeParEmail/$par_key'>click</a> here to activate your account.</p>";
							$message .= "<p>Your Sincerely,";
							$message .= "<br>Powered by SmartJen</p>";
							$this->email->message($message);

							if($this->email->send()) {

								$this->email->to($email);
								$this->email->Subject('SmartJen Student Account Activation');
								
								$message = "<p>Dear Student, </p>";
								$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
								$message .= "<p>Please <a href='".base_url()."site/register_temp_student/$encodeParEmail/$school_id/$level_id/$key/$encodeUserId'>click</a> here to activate your account.</p>";
								$message .= "<p>Your Sincerely,";
								$message .= "<br>Powered by SmartJen</p>";
								$this->email->message($message);
							
								if($this->model_users->create_temp_student($key, $par_key)){
									if($this->email->send()){
										$sessionArray = array(
											'profileMessageSuccess' => true,
											'profileMessage' => 'The activation emails have been sent to both '.$parEmail.' and ' . $email . '. Please activate the accounts for verification purpose.'
										);
										$this->session->set_userdata($sessionArray);
									} else {
										$sessionArray = array(
											'profileMessageSuccess' => false,
											'profileMessage' => 'Failed to sent out notification to admin.'
										);
										$this->session->set_userdata($sessionArray);
									}
								} else {
									$sessionArray = array(
										'profileMessageSuccess' => false,
										'profileMessage' => 'Failed to add student.'
									);
									$this->session->set_userdata($sessionArray);
								}
							}
							redirect(base_url() . 'profile');
						}
					} else {
						$sessionArray = array(
							'profileMessageSuccess' => false,
							'profileMessage' => 'Parent email ' . $parEmail . ' has reached maximum children number of 3. Please contact hello@smartjen.com for support.'
						);
						$this->session->set_userdata($sessionArray);
					}
				} else {
					// new parent account
					// register with parent and student email
					if(isset($email) && empty($email) == TRUE){
						
						$this->email->to($parEmail);
						$this->email->Subject('SmartJen Accounts Activation');

						$message = "<p>Dear Parent, </p>";
						$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
						$message .= "<p>Please <a href='".base_url()."site/register_new_parent/$encodeParEmail/$par_key/$school_id/$level_id/$key/$encodeUserId'>click</a> here to activate your account.</p>";
						$message .= "<p>Your Sincerely,";
						$message .= "<br>Powered by SmartJen</p>";
						$this->email->message($message);
						
						if($this->model_users->create_temp_student($key, $par_key)){
							if($this->email->send()){
								$sessionArray = array(
									'profileMessageSuccess' => true,
									'profileMessage' => 'An activation email has been sent to '.$parEmail.'. Please activate the accounts for verification purposes.'
								);
								$this->session->set_userdata($sessionArray);
							} else {
								$sessionArray = array(
									'profileMessageSuccess' => false,
									'profileMessage' => 'Failed to sent out confirmation email to student.'
								);
								$this->session->set_userdata($sessionArray);
							}
						} else {
							$sessionArray = array(
								'profileMessageSuccess' => false,
								'profileMessage' => 'Failed to add student.'
							);
							$this->session->set_userdata($sessionArray);
						}
						redirect(base_url() . 'profile');
					} else {
						
						$this->email->to($parEmail);
						$this->email->Subject('SmartJen Parent Account Activation');
						
						$message = "<p>Dear Parent, </p>";
						$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
						$message .= "<p>Please <a href='".base_url()."site/register_new_parent/$encodeParEmail/$par_key'>click</a> here to activate your account.</p>";
						$message .= "<p>Your Sincerely,";
						$message .= "<br>Powered by SmartJen</p>";
						$this->email->message($message);
						if($this->email->send()){

							$this->email->to($email);
							$this->email->Subject('SmartJen Student Account Activation');
							
							$message = "<p>Dear Student, </p>";
							$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
							$message .= "<p>Please <a href='".base_url()."site/register_temp_student/$encodeParEmail/$school_id/$level_id/$key/$encodeUserId'>click</a> here to activate your account.</p>";
							$message .= "<p>Your Sincerely,";
							$message .= "<br>Powered by SmartJen</p>";
							$this->email->message($message);
							
							if($this->model_users->create_temp_student($key, $par_key)){
								if($this->email->send()){
									$sessionArray = array(
										'profileMessageSuccess' => true,
										'profileMessage' => 'The activation emails have been sent to ' . $parEmail . ' and ' . $email . ' . Please activate the accounts for verification purposes.'
									);
									$this->session->set_userdata($sessionArray);
								} else {
									$sessionArray = array(
										'profileMessageSuccess' => false,
										'profileMessage' => 'Failed to sent out notification to admin.'
									);
									$this->session->set_userdata($sessionArray);
								}
							} else {
								$sessionArray = array(
									'profileMessageSuccess' => false,
									'profileMessage' => 'Failed to add student.'
								);
								$this->session->set_userdata($sessionArray);
							}
						}
						redirect(base_url() . 'profile');
					}
				}
			} else {
				$sessionArray = array(
					'profileMessageSuccesss' => 0,
					'profileMessages' => validation_errors()
				);
				$this->session->set_userdata($sessionArray);
			}
			redirect(base_url() . 'profile');
		} else {
			redirect('404');
		}
		
	}
	
	
	public function createChild() {
		$this->load->model('model_users');
		$postData = $this->input->post();
		$userId = $this->session->userdata('user_id');
		$userInfo = $this->model_users->get_user_info($userId);
		if (isset($postData) && empty($postData) === false) {
			$this->load->library("form_validation");
			$this->form_validation->set_rules('create_student_username', 'Student Username', 'required|trim|max_length[30]|alpha_dash|callback__validate_username');
			$this->form_validation->set_rules('create_student_fullname', 'Student Fullname', 'required|trim');
			$this->form_validation->set_rules('create_parent_email', 'Parent Email', 'valid_email|trim');
			$this->form_validation->set_rules('create_student_email', 'Student Email', 'valid_email|trim');
			$this->form_validation->set_rules('create_student_password', 'Password', 'required|trim|min_length[5]');
			$this->form_validation->set_rules('create_student_cpassword', 'Confirm Password', 'required|trim|matches[create_student_password]');
			if ($this->form_validation->run()) {
				$key = sha1(uniqid('jen'));
				$email = $postData['create_student_email'];
				$parEmail = $postData['create_parent_email'];
				$mobile = $postData['create_student_mobile'];
				$school_id = urlencode(base64_encode($postData['create_student_school_id']));
				$level_id = urlencode(base64_encode($postData['create_student_level_id']));
				$encodeParEmail = urlencode(base64_encode($parEmail));
				$encodeUserId = urlencode(base64_encode($userId));
				$parName = $userInfo->fullname;
				$this->load->library('email');
				$this->email->from('noreply@smartjen.com', "Smartjen");

				$childNo = $this->model_users->check_children_no($userId);

				if($childNo < 3) {

					if(isset($email) && empty($email) == TRUE){
						$this->email->to($parEmail);
						$this->email->Subject('SmartJen - Confirm your account');

						$message = "<p>Dear Parent, </p>";
						$message .= "<p>You have been created a student account named " . $this->input->post('create_student_fullname') . " in SmartJen.</p>";
						$message .= "<p>In order to have full access of SmartJen features, please copy and send the confirmation link to student.</p>";
						$message .= "<p>The confirmation link is <a href='".base_url()."site/register_student/$encodeParEmail/$encodeUserId/$school_id/$level_id/$key'>".base_url()."site/register_student/".$encodeUserId."/".$school_id."/".$level_id."/".$key."</a> .</p>";
						$message .= "<p>Thank you.</p>";
						$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";
						$this->email->message($message);
						
						if($this->model_users->create_temp_student($key)){
							if($this->email->send()){
								$sessionArray = array(
									'profileMessageSuccess' => true,
									'profileMessage' => 'An activation email was sent to '.$parEmail.'. Please click on the link to access to the full features.'
								);
								$this->session->set_userdata($sessionArray);
							} else {
								$sessionArray = array(
									'profileMessageSuccess' => false,
									'profileMessage' => 'Failed to sent out confirmation email to student.'
								);
								$this->session->set_userdata($sessionArray);
							}
						} else {
							$sessionArray = array(
								'profileMessageSuccess' => false,
								'profileMessage' => 'Failed to add student.'
							);
							$this->session->set_userdata($sessionArray);
						}
						redirect(base_url() . 'profile');
					} else {
						$this->email->to($email);
						$this->email->Subject('SmartJen - Confirm student account');
						
						$message = "<p>Dear " . $parName . ", </p>";
						$message .= "<p>Thank you for signing up an account for you children! You are one step away to have full access of SmartJen features.</p>";
						$message .= "<p><a href='".base_url()."site/register_student/$encodeParEmail/$encodeUserId/$school_id/$level_id/$key'>Click here</a> to confirm your account</p>";
						$message .= "<p>Thank you.</p>";
						$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";
						$this->email->message($message);
						
						if($this->model_users->create_temp_student($key)){
							if($this->email->send()){
								$sessionArray = array(
									'profileMessageSuccess' => true,
									// 'profileMessage' => 'An activation message was sent to your mobile number '.$mobile.'. Please click on the link to access to the full features.'
									'profileMessage' => 'An activation email was sent to '.$email.'. Please click on the link to access to the full features.'
								);
								$this->session->set_userdata($sessionArray);
							} else {
								$sessionArray = array(
									'profileMessageSuccess' => false,
									'profileMessage' => 'Failed to sent out notification to admin.'
								);
								$this->session->set_userdata($sessionArray);
							}
						} else {
							$sessionArray = array(
								'profileMessageSuccess' => false,
								'profileMessage' => 'Failed to add student.'
							);
							$this->session->set_userdata($sessionArray);
						}
						redirect(base_url() . 'profile');
					}
				} else {
					$sessionArray = array(
						'profileMessageSuccess' => false,
						'profileMessage' => 'Exceed maximum number of children.'
					);
					$this->session->set_userdata($sessionArray);
				}
			} else {
				$sessionArray = array(
					'profileMessageSuccesss' => 0,
					'profileMessages' => validation_errors()
				);
				$this->session->set_userdata($sessionArray);
			}
			redirect(base_url() . 'profile');
		} else {
			redirect('404');
		}
		
	}
	
	public function register_student($userId,$key)
	{
		$this->load->model('model_users');
		if ($this->model_users->is_key_valid($key)) {
			if ($newemail = $this->model_users->add_student_user($key, $userId)) {
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
	
	public function _validate_username()
	{
		$this->load->model('model_users');
		
		$username = $this->input->post('create_student_username');
		
		if ($this->model_users->validate_usernames($username)) {
			return true;
		} else {
			$this->form_validation->set_message('_validate_credentials', 'Invalid username');
			return false;
		}
	}


	public function _validate_email()
	{
		$this->load->model('model_users');
		
		$email = $this->input->post('create_student_email');
		
		if ($this->model_users->validate_emails($email)) {
			return true;
		} else {
			$this->form_validation->set_message('_validate_credentials', 'Invalid email');
			return false;
		}
	}


	public function searchStudent() {

		if ($this->input->is_ajax_request()) {

			$this->load->model('model_users');

			$studentUsername = $this->input->post('studentUsername');

			$studentArray = $this->model_users->search_student_username($studentUsername);

			echo json_encode($studentArray);

		} else {

			redirect('404');

		}

	}



	public function create_question() {

		$user_role = $this->session->userdata('user_role');

		if ($user_role != 1) {

			redirect('404');

		}

		$this->load->model('model_question');

		$post = $this->input->post();

		

		if (isset($post) && empty($post) === false) {

			$response = $this->model_question->create_new_question($post);
			if ($response['status']) {

				if ($_FILES['graphical']['size'] != 0) {

					$config['upload_path'] = 'img/questionImage';

					$config['allowed_types'] = '*';

					$config['max_size']    = '300';

					$config['overwrite'] = 'true';

					$config['file_name'] = $post['year'] . '-0-' . $post['level_id'] . '-' . $response['question_id'];

					$this->load->library('upload');

					$this->upload->initialize($config);



					$allowed_types = array('jpg', 'png', 'gif', 'jpeg');

					$file_name = $_FILES['graphical']['name'];

					$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));



					if (in_array($ext, $allowed_types)) {

						if (!$this->upload->do_upload('graphical'))

						{

							$data['message'] = $this->upload->display_errors();

							$data['message_status'] = 'alert-danger';

						} else {

							$upload_data = $this->upload->data();

							if ($this->model_question->update_question_image($upload_data['file_name'], $response['question_id'])) {

								$data['message'] = "Question created successfully and image uploaded successfully";

								$data['message_status'] = 'alert-success';

							} else {

								$data['message'] = "Error in uploading question image. Please try again later or contact administrator at admin@smartjen.sg";

								$data['message_status'] = 'alert-danger';

							}

							

						}

					} else {

						$data['message'] = 'Please upload only jpg, png, gif or jpeg file';

						$data['message_status'] = 'alert-danger';

					}

				} else {

					$data['message'] = 'Question created successfully';

					$data['message_status'] = 'alert-success';

				}

				

			} else {

				$data['message'] = 'Error creating question';

				$data['message_status'] = 'alert-danger';

			}

			

			$data['selected_level'] = $post['level_id'];

			$data['selected_school'] = $post['school_id'];

			$data['selected_tags'] = $post['tagsinput'];

			$data['selected_difficulty'] = $post['difficulty'];

			$data['selected_year'] = $post['year'];

			$data['selected_question_type'] = $post['question_type_id'];

            $data['selected_answer_type'] = $post['answer_type_id'];

		} 



		$data['content'] = 'administrator/administrator_create_question';

		$data['levels'] = $this->model_question->get_level_list();

		$data['categories'] = $this->model_question->get_topic_list();

		$data['schools'] = $this->model_question->get_school_list();

		$data['post_url'] = base_url() . 'profile/create_question';

		$this->load->view('include/master_view', $data);

	}
	
	public function edit_question($question_id) {

		$this->load->model('model_question');

		if (isset($question_id) && empty($question_id) === false) {

			if ($this->model_question->can_edit_question($question_id)) {

				$post = $this->input->post();

				if (isset($post) && empty($post) === false) {

					$response = $this->model_question->update_question($post, $question_id);

					if ($response['status']) {

						$data['message_success'] = true;

						$data['message'] = 'Question successfully updated';

					} else {

						$data['message_success'] = false;

						$data['message'] = 'Error updating question, please try again later';

					}

				}

				$question_detail = $this->model_question->get_question_from_id($question_id);

				

				if (!isset($question_detail) || empty($question_detail) === true) {

					redirect(base_url().'profile');

				}

	

				$categories = $this->model_question->get_topic_list();

				$levels = $this->model_question->get_level_list();

				$schools = $this->model_question->get_school_list();

	

				$data['question_detail'] = $question_detail;

				$data['post_url'] = base_url() . 'profile/edit_question/'.$question_id;

				$data['categories'] = $categories;

				$data['content'] = 'administrator/administrator_question';

				$data['levels'] = $levels;

				$data['schools'] = $schools;

	

				if ($question_detail->question_type_id == 1) {

					$data['answerOptions'] = $this->model_question->get_answer_option_from_question_id($question_id);

					$data['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($question_id);

				} else if ($question_detail->question_type_id == 2) {

					$data['open_ended_answer'] = $this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

				}

	

				$data['question_tags'] = $this->model_question->get_question_tags($question_id);

				$this->load->view('include/master_view', $data);

			} else {

				redirect('404');

			}

			

		} else {

			redirect('404');

		}

	}



	public function question_list() {

		$user_role = $this->session->userdata('user_role');

		if ($user_role != 1) {

			redirect('404');

		}

		$user_id = $this->session->userdata('user_id');

		$this->load->model('model_users');

		$question_list = $this->model_users->get_tutor_uploaded_questions($user_id);

		$data['questions'] = $question_list;

		$data['content'] = 'profile/view_question_list';

		$this->load->view('include/master_view', $data);

	}

	
	public function addStudent() {
		$this->load->model('model_users');
		$this->load->library('form_validation');
		$post = $this->input->post();
		$userId = $this->session->userdata('user_id');
		
		if(isset($post) && empty($post) == FALSE) {
			$this->form_validation->set_rules('blast_student', 'Tag Student', 'required|trim');
			
			if($this->form_validation->run()) {
				$userInfo = $this->model_users->get_user_info($userId);
				$sender = $userInfo->fullname; // tutor fullname
				$sender_email = $userInfo->email; // tutor email
				$tag_type = $post['blast_radio_button'];
				$rec_tag = $post['blast_student']; // student username or student email
				$recInfo = $this->model_users->get_user_fullname_using_email_or_username($rec_tag); // student fullname
				$encode_userId = urlencode(base64_encode($userId)); //encode tutor ID
				$encode_rec_tag = urlencode(base64_encode($rec_tag)); // encode student email or username
				
				if($tag_type[0] == 'email') {
					$this->load->library('email');
					$this->email->from('noreply@smartjen.com', "SmartJen");

					$relationship = $this->model_users->student_check_parent_relationship($rec_tag);

					if($relationship) {
						$this->email->to($rec_tag);
						$this->email->Subject('SmartJen - Request To Tag Under '.$sender);
						$message = "<p>Dear Student, </p>";
						$message .= "<p>" . $sender . " has invited you " . $recInfo . " as student.</p>";
						$message .= "<p>Please <a href='".base_url()."site/tag_student/$encode_userId/$encode_rec_tag'>click</a> here to accept the invitation.</p>";
						$message .= "<p>Enjoy learning.</p>";
						$message .= "<br><br><p>Your Sincerely,";
						$message .= "<br>Powered by SmartJen</p>";
					} else {
						$this->email->to($rec_tag);
						$this->email->Subject('SmartJen - Request To Tag Under '.$sender);
						$message = "<p>Dear Student, </p>";
						$message .= "<p>" . $sender . " has invited you " . $recInfo . " as student.</p>";
						$message .= "<p>Please <a href='".base_url()."site/tag_student/$encode_userId/$encode_rec_tag'>click</a> here to accept the invitation.</p>";
						$message .= "<p>Enjoy learning.</p>";
						$message .= "<br><br><p>Your Sincerely,";
						$message .= "<br>Powered by SmartJen</p>";
					}

					$this->email->message($message);
					if($this->email->send()){
						$sessionArray = array(
							'profileMessageSuccess' => true,
							'profileMessage' => 'An invitation email for tagging has been sent to '. $rec_tag.'.'
						);
						$this->session->set_userdata($sessionArray);
					}else{
						$sessionArray = array(
							'profileMessageSuccess' => false,
							'profileMessage' => 'Failed to send the request for tagging to '.$rec_tag.' for confirmation.'
						);
					}
				} else if($tag_type[0] == 'username') {
					
					$rec_email = $this->model_users->get_email_from_username($rec_tag); //student email
					
					if($rec_email === false) {
						
						// dont have student email
						$relationship = $this->model_users->student_check_parent_relationship($rec_tag);
						
						$this->load->library('email');
						$this->email->from('noreply@smartjen.com', "SmartJen");
						
						if($relationship) {
							
							$this->email->to($relationship);
							$this->email->Subject('SmartJen - Request To Tag Under '.$sender);
							$message = "<p>Dear Student, </p>";
							$message .= "<p>" . $sender . " has invited you " . $recInfo . " as student.</p>";
							$message .= "<p>Please <a href='".base_url()."site/tag_student/$encode_userId/$encode_rec_tag'>click</a> here to accept the invitation.</p>";
							$message .= "<p>Enjoy learning.</p>";
							$message .= "<br><br><p>Your Sincerely,";
							$message .= "<br>Powered by SmartJen</p>";
							$this->email->message($message);
							if($this->email->send()){
								$sessionArray = array(
									'profileMessageSuccess' => true,
									'profileMessage' => 'An invitation email for tagging has been sent to '. $rec_tag . '.'
								);
								$this->session->set_userdata($sessionArray);
							}else{
								$sessionArray = array(
									'profileMessageSuccess' => false,
									'profileMessage' => 'The invited student does not have an email address. But notification is failed to send out to admin..'
								);
								$this->session->set_userdata($sessionArray);
							}
						} else {
							
							$this->email->to($sender_email);
							$this->email->Subject('SmartJen - Request To Tag Under '.$sender);
							$message = "<p>Dear Student, </p>";
							$message .= "<p>" . $sender . " has invited you " . $recInfo . " as student.</p>";
							$message .= "<p>Please <a href='".base_url()."site/tag_student/$encode_userId/$encode_rec_tag'>click</a> here to accept the invitation.</p>";
							$message .= "<p>Enjoy learning.</p>";
							$message .= "<br><br><p>Your Sincerely,";
							$message .= "<br>Powered by SmartJen</p>";
							$this->email->message($message);
							if($this->email->send()){
								$sessionArray = array(
									'profileMessageSuccess' => true,
									'profileMessage' => 'An invitation email for tagging has been sent to '. $rec_tag .'.'
								);
								$this->session->set_userdata($sessionArray);
							}else{
								$sessionArray = array(
									'profileMessageSuccess' => false,
									'profileMessage' => 'The invited student does not have an email address. But notification is failed to send out to admin..'
								);
								$this->session->set_userdata($sessionArray);
							}
						}

					} else {
						
						// have student email
						$relationship = $this->model_users->student_check_parent_relationship($rec_tag);

						$this->load->library('email');
						$this->email->from('noreply@smartjen.com', "SmartJen");
						$this->email->to($rec_email);
						$this->email->Subject('SmartJen - Request To Tag Under '.$sender);
						$message = "<p>Dear Student, </p>";
						$message .= "<p>" . $sender . " has invited you " . $recInfo . " as student.</p>";
						$message .= "<p>Please <a href='".base_url()."site/tag_student/$encode_userId/$encode_rec_tag'>click</a> here to accept the invitation.</p>";
						$message .= "<p>Enjoy learning.</p>";
						$message .= "<br><br><p>Your Sincerely,";
						$message .= "<br>Powered by SmartJen</p>";
						$this->email->message($message);
						if($this->email->send()){
							$sessionArray = array(
								'profileMessageSuccess' => true,
								'profileMessage' => 'An invitation email for tagging has been sent to ' . $rec_tag . '.'
							);
							$this->session->set_userdata($sessionArray);
						}else{
							$sessionArray = array(
								'profileMessageSuccess' => false,
								'profileMessage' => 'The invited student does not have an email address. But notification is failed to send out to admin.'
							);
							$this->session->set_userdata($sessionArray);
						}
					}
				}
			} else {
				$sessionArray = array(
					'profileMessageSuccesss' => 0,
					'profileMessage' => validation_errors()
				);
				$this->session->set_userdata($sessionArray);
			}
			redirect(base_url() . 'profile');
		}
		
	}
	
	public function checkNotTagStudent() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('model_users');
			$userId = $this->input->post('userId');
			$tag_student = $this->input->post('tag_student');
			$usernameExist = $this->model_users->check_user_exist_using_email_or_username($tag_student);
			$emailExist = $this->model_users->check_user_exist_using_email_or_username($tag_student);
			$validEmail = $this->model_users->check_valid_email($tag_student);
			$tutorExist = $this->model_users->check_tutor_exist_tag($tag_student);
			$parentExist = $this->model_users->check_parent_exist_tag($tag_student);
			//$studentExist = $this->model_users->check_student_exist_tag($tag_student);
			$ownStudent = $this->model_users->check_own_student_exist_tag($tag_student, $userId);
			$returnArray['usernameExist'] = $usernameExist;
			$returnArray['emailExist'] = $emailExist;
			$returnArray['validEmail'] = $validEmail;
			$returnArray['tutorExist'] = $tutorExist;
			$returnArray['parentExist'] = $parentExist;
			//$returnArray['studentExist'] = $studentExist;
			$returnArray['ownStudent'] = $ownStudent;
			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}
	
	public function archiveWorksheet() {
		$worksheetId = $this->input->post('worksheetId');
		if (!isset($worksheetId)) {
			redirect('404');
		}
		$success = $this->model_worksheet->archive_worksheet($worksheetId);
		if ($success) {
			$this->session->set_userdata('profileMessageSuccess', true);
			$this->session->set_userdata('profileMessage', 'You have successfully archive the worksheet');
		}  else {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Failure in archiving worksheet. Please try again later or contact administrator.');
		}
		redirect(base_url().'profile');
	}
	
	public function unarchiveWorksheet() {
		$worksheetId = $this->input->post('worksheetId');
		if (!isset($worksheetId)) {
			redirect('404');
		}
		$success = $this->model_worksheet->unarchive_worksheet($worksheetId);
		if ($success) {
			$this->session->set_userdata('profileMessageSuccess', true);
			$this->session->set_userdata('profileMessage', 'You have successfully unarchive the worksheet');
		}  else {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Failure in unarchiving worksheet. Please try again later or contact administrator.');
		}
		redirect(base_url().'profile');
	}
	
	public function inviteStudent() {
		$this->load->model('model_users');
		$tutorId = $this->session->userdata('user_id');
		$email = $this->input->post('invite_student_email');
		$parEmail = $this->input->post('invite_parent_email');
		$tutorInfo = $this->model_users->get_user_info($tutorId);
		$tutorFullName = $tutorInfo->fullname;
		$email_encode = urlencode(base64_encode($email));
		$tutorId_encode = urlencode(base64_encode($tutorId));
		$parEmail_encode = urlencode(base64_encode($parEmail));
		$key = urlencode(base64_encode('SmartJen'));

		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "SmartJen");
		if(isset($parEmail) && empty($parEmail) == FALSE) {

			if(isset($email) && empty($email) == TRUE) {
				$this->email->to($parEmail);
				$this->email->Subject('SmartJen - Invitation to Register As Student ');
				$message = "<p>Dear New User,</p>";
				$message .= "<p>".$tutorFullName." has sent you an invitation to register for a student account under his/her supervision.</p>";
				$message .= "<p>Please click on the <a href='".base_url()."site/logins/$parEmail_encode/$tutorId_encode/$key/$email_encode'>link</a> to register.</p>";
				$message .= "<p>Thank you.</p>";
				$message .= "<br><br><p>Your Sincerely,";
				$message .= "<br>Powered by SmartJen</p>";
				$this->email->message($message);
				if($this->email->send()){
					$sessionArray = array(
						'profileMessageSuccess' => true,
						'profileMessage' => 'An invitation email for tagging has been sent to '.$parEmail.'.'
					);
					$this->session->set_userdata($sessionArray);
				}else{
					$sessionArray = array(
						'profileMessageSuccess' => false,
						'profileMessage' => 'Failed to sent out invitation email to student.'
					);
					$this->session->set_userdata($sessionArray);
				}
			} else {
				$this->email->to($parEmail);
				$this->email->Subject('SmartJen - Invitation to Register As Student ');
				$message = "<p>Dear New User,</p>";
				$message .= "<p>".$tutorFullName." has sent you an invitation to register for a student account under his/her supervision.</p>";
				$message .= "<p>Please click on the <a href='".base_url()."site/logins/$parEmail_encode/$tutorId_encode/$key/$email_encode'>link</a> to register.</p>";
				$message .= "<p>Thank you.</p>";
				$message .= "<br><br><p>Your Sincerely,";
				$message .= "<br>Powered by SmartJen</p>";
				$this->email->message($message);
				if($this->email->send()){
					$sessionArray = array(
						'profileMessageSuccess' => true,
						'profileMessage' => 'An invitation email for tagging has been sent to '.$parEmail.'.'
					);
					$this->session->set_userdata($sessionArray);
				}else{
					$sessionArray = array(
						'profileMessageSuccess' => false,
						'profileMessage' => 'Failed to sent out invitation email to student.'
					);
					$this->session->set_userdata($sessionArray);
				}
			}
			
		} else {

			$sessionArray = array(
				'profileMessageSuccess' => false,
				'profileMessage' => 'Parent email is compulsory.'
			);
			$this->session->set_userdata($sessionArray);
		}
		
		
		redirect(base_url().'profile');
	}
	
	public function tutor_parent_view() {
		
		$userId = $this->session->userdata('user_id');
		
		$profile_message_success = $this->session->userdata('untagMessageSuccess');
		$profile_message = $this->session->userdata('untagMessage');
		if (isset($profile_message_success)) {
			$data['untag_message_success'] = ($profile_message_success == 0)?false:true;
			$data['untag_message'] = $profile_message;
			$this->session->unset_userdata('untagMessageSuccess');
			$this->session->unset_userdata('untagMessage');
		}
		
		$this->load->model('model_users');
		$this->load->library('pagination');
		
		$config = array ();
		$config['base_url'] = base_url() . 'profile/tutor_parent_view';
		$config['total_rows'] = $this->model_users->count_tutor_list($userId);
		$config['per_page'] =10;
		$config['uri_segment'] =3;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);

		$configs = array ();
		$configs['base_url'] = base_url() . 'profile/tutor_parent_view';
		$configs['total_rows'] = $this->model_users->count_parent_list($userId);
		$configs['per_page'] = 10;
		$configs['uri_segment'] = 3;
		$configs['full_tag_open'] = '<ul class="pagination">';
		$configs['full_tag_close'] = '</ul>';
		$configs['first_link'] = false;
		$configs['last_link'] = false;
		$configs['first_tag_open'] = '<li>';
		$configs['first_tag_close'] = '</li>';
		$configs['prev_link'] = '&laquo';
		$configs['prev_tag_open'] = '<li class="prev">';
		$configs['prev_tag_close'] = '</li>';
		$configs['next_link'] = '&raquo';
		$configs['next_tag_open'] = '<li>';
		$configs['next_tag_close'] = '</li>';
		$configs['last_tag_open'] = '<li>';
		$configs['last_tag_close'] = '</li>';
		$configs['cur_tag_open'] = '<li class="active"><a href="#">';
		$configs['cur_tag_close'] = '</a></li>';
		$configs['num_tag_open'] = '<li>';
		$configs['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($configs);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['tutor_list'] = $this->model_users->view_tutor_list($userId, $config['per_page'], $page);
		$data['parent_list'] = $this->model_users->view_parent_list($userId, $configs['per_page'], $page);
		$data['links'] = $this->pagination->create_links();
		$data['content'] = "profile/view_tutor_parent_list";
		$this->load->view('include/master_view', $data);
	}
	
	public function untagStudent($tutor_id = NULL) {
		$studentId = $this->input->post('student_id');
		$tutorId = $this->session->userdata('user_id');
		
		$check_student = $this->model_users->checkStudent($studentId, $tutorId);
		if($check_student){
			$success = $this->model_users->untag_student($studentId, $tutorId);
			if ($success) {
				$this->session->set_userdata('profileMessageSuccess', true);
				$this->session->set_userdata('profileMessage', 'You have successfully untag the student');
			}  else {
				$this->session->set_userdata('profileMessageSuccess', 0);
				$this->session->set_userdata('profileMessage', 'Failure in untag the student. Please try again later or contact administrator.');
			}
		} else {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'The student is not exist. Please try again later or contact administrator.');
		}
		
		redirect(base_url().'profile');
	}
	
	public function untagTutor() {
		$tutorId = $this->input->post('tutor_id');
		$studentId = $this->session->userdata('user_id');
		
		$tutorInfo = $this->model_users->get_user_info($tutorId);
		$tutorEmail = $tutorInfo->email;
		$tutorFullName = $tutorInfo->fullname;
		
		$studentInfo = $this->model_users->get_user_info($studentId);
		$studentFullName = $studentInfo->fullname;
		
		$check_student = $this->model_users->checkStudent($studentId, $tutorId);
		if($check_student){
			$tutorId_encode = urlencode(base64_encode($tutorId));
			$studentId_encode = urlencode(base64_encode($studentId));
			$this->load->library('email');
			$this->email->from('noreply@smartjen.com', "SmartJen");
			$this->email->to($tutorEmail);
			$this->email->subject('SmartJen Request To Untag');
			$message = "<p>Dear " . $tutorFullName . ", </p>";
			$message .= "<p>" . $studentFullName . " is requesting to untag the tutor-student relationship.</p>";
			$message .= "<p><a href='".base_url()."site/untagStudent/$tutorId_encode/$studentId_encode'>Click here</a> to confirm the request for untagging the relationship with " . $studentFullName . ".</p>";
			$message .= "<p>Your Sincerely,<br><br>SmartJen</p>";
			$this->email->message($message);
			
			if($this->email->send()) {
				$this->session->set_userdata('untagMessageSuccess', true);
				$this->session->set_userdata('untagMessage', 'A request email has been sent to your tutor for approval.');
			} else {
				$this->session->set_userdata('untagMessageSuccess', 0);
				$this->session->set_userdata('untagMessage', 'Error in sending out request to tutor. Please try again later or contact administrator.');
			}
			
		} else {
			$this->session->set_userdata('untagMessageSuccess', 0);
			$this->session->set_userdata('untagMessage', 'The student is not exist. Please try again later or contact administrator.');
		}
		
		redirect(base_url() . 'profile/tutor_parent_view');
		
	}

	public function get_quiz_attempt () {
		if($this->input->is_ajax_request()) {

			$worksheetId = $this->input->post('worksheetId');

			$quizzes = $this->model_worksheet->get_quiz_list_from_worksheet_id($worksheetId);

			foreach ($quizzes as $quiz) {
				$quiz->studentName = $this->model_users->get_username_from_id($quiz->assignedTo);

				$this->updateQuizInfo($quiz);
			}
			
			$data['quizzes'] = $quizzes;

			echo json_encode($data);

		} else {

			redirect('404');

		}
	}

	public function get_student_score() {
		if($this->input->is_ajax_request()) {
			$tutorId = $this->input->post('tutorId');

			$data = array();
			$subject = array();
			$student_performance = array();
			$analysis_structure = array();

			$students = $this->model_users->get_student_list($tutorId);

			$student_id_array = array();

			foreach ($students as $student) {

				$student_id_array[] = $student->student_id;

			}

			if($tutorId == 490) {
				$subId = array(2,3,5);
			} else {
				$subId = array(2,3);
			}

			foreach($subId as $sub) {
				$subArr = array($sub);
			
				$subject[] = $this->model_question->get_subject_list($subArr);

				$student_performance[] = $this->model_users->get_performance($student_id_array, $sub);

				$analysis_structure[] = $this->model_question->get_strand_structure($sub);

			}

			// $subject = $this->model_question->get_subject_list($subId);

			// $student_performance = $this->model_users->get_performance($student_id_array, 2);

			// $analysis_structure = $this->model_question->get_strand_structure();

			$data['analysis_structure'] = $analysis_structure;

			$data['students'] = $students;

			$data['student_performance'] = $student_performance;

			$data['subject'] = $subject;

			echo json_encode($data);
			
			
		}else {
			redirect('404');
		}
	}

	public function get_child_score() {
		if($this->input->is_ajax_request()) {
			$tutorId = $this->input->post('tutorId');

			$data = array();

			$students = $this->model_users->get_children_list($tutorId);

			$student_id_array = array();

			foreach ($students as $student) {

				$student_id_array[] = $student->student_id;

			}

			$student_performance = $this->model_users->get_performance($student_id_array, 2);

			$subId = array(2,3);

			$subject = $this->model_question->get_subject_list($subId);

			$analysis_structure = $this->model_question->get_strand_structure();

			$data['analysis_structure'] = $analysis_structure;

			$data['students'] = $students;

			$data['student_performance'] = $student_performance;

			$data['subject'] = $subject;

			echo json_encode($data);
			
			
		}else {
			redirect('404');
		}
	}

	public function get_school_list(){ 
		// POST data 
		$postData = $this->input->post();
		
		// get data 
		$data = $this->model_question->get_school_name($postData);
		echo json_encode($data); 
	}

	private function updateQuizInfo(&$quiz) {

        $quiz->name = $this->model_worksheet->get_worksheet_name_from_id($quiz->worksheetId);
        $quiz->archive = $this->model_worksheet->get_archive_worksheet($quiz->worksheetId);
        $quiz->numOfAttempt = $this->model_quiz->count_number_of_attempt($quiz->id);
        $worksheet_question = $this->model_quiz->get_worksheet_question($quiz->worksheetId);
        $total_mark = 0;
        foreach($worksheet_question->result() as $question_id){

        	$question_mark = $this->model_question->get_total_marks_from_question_id($question_id->question_id);
        	$total_mark += $question_mark;
        }


        if ($quiz->numOfAttempt > 0) {

			$quiz->lastAttemptDate = $this->model_quiz->get_last_attempt_date($quiz->id);

            $attemptInfo = array();

            $attempts = $this->model_quiz->get_student_attempt($quiz->id);



            foreach ($attempts as $attempt) {
	            $total_question_no = $this->model_quiz->get_total_no_attempt_question($attempt->id);
            	$question_no = $this->model_quiz->get_no_attempt_question($attempt->id);
            	$attempt->question_no = $question_no;
            	$attempt->total_question_no = $total_question_no;

                $attempt->score = $this->get_student_attempt_score($attempt->id, $quiz->worksheetId);

				if($total_mark == 0){
					$score_percentage = 0;
				} else {
					$score_percentage = ($attempt->score /$total_mark ) * 100;
				}
				$attempt->scorePercentage = round($score_percentage);

                $attemptInfo[] = $attempt;

            }

            $quiz->attempts = $attemptInfo;

        }

	}

	private function get_student_attempt_score($attemptId, $worksheetId) {

        $this->load->model('model_question');

        $this->load->model('model_automarking');



        $hasMarked = $this->model_quiz->get_attempt_has_marked($attemptId);

        $attemptScore = $this->model_quiz->get_attempt_score($attemptId);



        if($hasMarked == TRUE) {

            if($attemptScore > -1.0) {

                return $attemptScore;

            }

        }



        $questionsArray = array();

        $userAnswerArray = $this->model_quiz->get_attempt_answer($attemptId);

        $scoreArray = array();



        $isMockExam = $this->model_worksheet->is_mock_exam($worksheetId);

        if(!$isMockExam) {

            $questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);

            $questionsArray = $questions->result();



        } else {

            $requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheetId);

            $postData = array(

                'gen_tutor' => $requirement->me_tutor,

                'gen_me'    => $requirement->me_num,

                'gen_year'  => $requirement->me_year,

                'gen_level' => $requirement->me_level,

                'gen_randomize' => ''

            );

            $questionsArray = $this->model_question->get_mock_exam_question_list($postData);

        }



        $questionsArraySerial = array();

        for($i=0, $l = count($questionsArray); $i < $l; $i++) {

            if(is_array($questionsArray[$i])) {

                foreach($questionsArray[$i] AS $question) {

                    $questionsArraySerial[] = $question;

                }

            } else {

                $questionsArraySerial[] = $questionsArray[$i];

            }

        }



        $numUserAnswers = count($userAnswerArray);

        $numQuestions = count($questionsArraySerial);

        if($numUserAnswers == 0) {

            $scoreArray = array_fill(0,$numQuestions, 0);

        } else {

            foreach($questionsArraySerial AS $key => $curQuestion) {

                if(isset($curQuestion->question_number)) {

                    $index = $curQuestion->question_number - 1;

                    $userAnswer = (isset($userAnswerArray[$index])) ? $userAnswerArray[$index] : "";

                } else {

                    $userAnswer = $userAnswerArray[$key];

                }
                $score = $this->get_score_single_question($curQuestion, $userAnswer, $worksheetId);

                //

                $this->model_quiz->save_score_for_answer($attemptId, $i + 1, $score);

                $scoreArray[] = $score;

            }

        }



        $totalAttemptScore = 0;

        foreach($scoreArray as $tempScore) {

            $totalAttemptScore += $tempScore;

        }

        $this->model_quiz->save_attempt_score($attemptId, $totalAttemptScore);



        return $totalAttemptScore;



	}

	public function get_score_single_question($question, $userAnswer, $questionType, $subjectType = 2) {

		$this->load->model('model_automarking');

	    $returnScore = 0;

        $fullMark = $this->model_question->get_total_marks_from_question_id($question->question_id);



       

            $modelAnswerText = trim($this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question->question_id)));


            $userAnswerText = trim($userAnswer);

            // Call automarking class

            $correctAnswerObject = new Model_subjectiveanswer();

            $userAnswerObject = new Model_subjectiveanswer();
			
			$correctAnswerObject->configure(
				$modelAnswerText, //answer Text
				$this->model_question->get_answer_type_id_from_question_id($question->question_id), //answer Type
				$subjectType, //subject
				$questionType, //question Type
				$this->model_question->get_correct_answer_from_question_id($question->question_id) //answer Id 
			);
	
			$userAnswerObject->configure(
				$userAnswerText,
				$this->model_question->get_answer_type_id_from_question_id($question->question_id), //answer Type
				$subjectType, //subject
				$questionType //question Type
			); // use default answerTypeId


            $returnScore = $this->model_automarking->Mark($correctAnswerObject, $userAnswerObject, $fullMark);


        return $returnScore;

	}
	
	public function get_student_quiz() {
		if($this->input->is_ajax_request()) {
			$quizId = $this->input->post('quizId');

			$userId = $this->input->post('userId');

			$worksheetId = $this->model_worksheet->get_worksheet_id_from_quiz_id($quizId);

			$data = array();

			$worksheet_question = $this->model_quiz->get_worksheet_question($worksheetId);

			$total_mark = 0;
			foreach($worksheet_question->result() as $question_id){

				$question_mark = $this->model_question->get_total_marks_from_question_id($question_id->question_id);

				$total_mark += $question_mark;
			}

			$last_attempt = $this->model_quiz->get_last_attempt_date($quizId);

			if($last_attempt === FALSE) {
				$data['lastAttemptDate'] = 0;
				$data['question_no'] = 0;
				$data['total_question_no'] = 0;
				$data['scorePercentage'] = 0;
			} else {
				$data['lastAttemptDate'] = $last_attempt;

				$attemptInfo = array();

				$attempts = $this->model_quiz->get_student_attempt($quizId);

				foreach ($attempts as $attempt) {
					$total_question_no = $this->model_quiz->get_total_no_attempt_question($attempt->id);
					$question_no = $this->model_quiz->get_no_attempt_question($attempt->id);
					$attempt->question_no = $question_no;
					$attempt->total_question_no = $total_question_no;
					$score = $this->get_student_attempt_score($attempt->id, $worksheetId);
					$data['score'] = $score;

					if($total_mark == 0){
						$score_percentage = 0;
					} else {
						$score_percentage = ($score /$total_mark ) * 100;
					}
					$attempt->scorePercentage = round($score_percentage);

					$attemptInfo[] = $attempt;

				}

				$data['attempts'] = $attemptInfo;
			}
			
			echo json_encode($data);
			
		}else {
			redirect('404');
		}
	}

	public function switch_account() {
		$userRole = $this->session->userdata('user_role');
		$userId = $this->session->userdata('user_id');
		
		$this->model_users->update_last_login($userId, $userRole);

		if($userRole == 1) {
			$newRole = '3';
			$this->model_users->update_last_login($userId, $newRole);
			$this->session->set_userdata('profileMessageSuccess', true);
			$this->session->set_userdata('profileMessage', 'Switch to Parent Account!');
		} else {
			$newRole = '1';
			$this->model_users->update_last_login($userId, $newRole);
			$this->session->set_userdata('profileMessageSuccess', true);
			$this->session->set_userdata('profileMessage', 'Switch to Tutor Account!');
		}

		$this->session->set_userdata('user_role', $newRole);
		redirect(base_url() . 'profile');
	}


	function group_report(){
		$is_pdf = false;
		$select_view = "performance";
		$user_id = $this->session->userdata('user_id');
		$data['student_list'] = $this->model_users->get_student_list($user_id);
		if($this->input->post()){
			//array_debug($this->input->post());exit;

			$date_range = array(
				str_replace('-','',$this->input->post('dr_start')),
				str_replace('-','',$this->input->post('dr_end'))
			);

			$date_range2 = array(
				str_replace('-','',$this->input->post('dr2_start')),
				str_replace('-','',$this->input->post('dr2_end'))
			);

			$selected_student = $this->input->post('student_selected');
			$select_view = $this->input->post('select_view');
			$selected_worksheet = $this->input->post('worksheet_selected');
			$worksheet_name = $this->input->post('worksheet_name');
			// $selected_worksheet = (is_array($selected_worksheet)) ? $selected_worksheet : array();
			$is_pdf = $this->input->post('gen_pdf');

			$data['entered'] = array(	'date_range' => $date_range,
										'date_range2' => $date_range2,
										'selected_student' => $selected_student,
										'select_view' => $select_view,
										'selected_worksheet' => $selected_worksheet,
										'worksheet_name' => $worksheet_name
									);
			$selected_student_group = array();
			if(in_array("all_students", $selected_student)) {
				foreach($data['student_list'] as $student){
					$selected_student_group[] = $student->student_id;
				}
				$selected_student = $selected_student_group;
			} else {
				$i = 0;
				foreach ($selected_student as $val) {
					if( strpos( $val, "group_" ) !== false) {
						$id_group = str_replace("group_", "", $val);
						$students_from_group = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$id_group,"result");
						foreach ($students_from_group as $key => $student_group) {
							if(!in_array($student_group['student_id'], $selected_student))
								$selected_student_group[] = $student_group['student_id'];
						}
						unset($selected_student[$i]);
					}
					$i++;
				}
				$selected_student = array_merge($selected_student, $selected_student_group);
				$selected_student = array_values($selected_student);
			}
			$data['performance1'] = $this->model_users->get_performance($selected_student,null,$date_range); //,$selected_worksheet);
			$data['performance2'] = $this->model_users->get_performance($selected_student,null,$date_range2); //,$selected_worksheet);

			$data['analysis_structure'] = $this->model_question->get_strand_structure();

			$data['entered_new'] = array(	'date_range' => $date_range,
										'date_range2' => $date_range2,
										'selected_student' => $selected_student,
										'select_view' => $select_view,
										'selected_worksheet' => $selected_worksheet,
										'worksheet_name' => $worksheet_name
									);
			//array_debug($data['performance1']);exit;
		}else{
			$data['entered'] = array(	
										'selected_student' => array(),
										'select_view' => array(),
										'selected_worksheet' => array(),
										'worksheet_name' => array()
								);
		}
		$userRole = $this->session->userdata('user_role');
		$data['worksheet_list'] = $this->model_worksheet->get_worksheets($user_id, $userRole);
		//array_debug($data['student_list']);exit;
		//$is_pdf = false;
		if($is_pdf){
			if($select_view=="performance")
				$content = $this->load->view('profile/generate_group_report', $data,true);
			else
				$content = $this->load->view('profile/generate_group_report_worksheet', $data,true);
			$is_html = 0;
            if($is_html == '1'){
                //array_debug($data);exit;
                echo $content;
            }else{
				//echo $content;
                //Initial PDF lib
				$this->load->library('m_pdf');
				$this->mpdf->AddPage('L');
                $this->mpdf->WriteHTML($content);
				$this->mpdf->shrink_tables_to_fit=0;
				//$this->mpdf->forcePortraitHeaders = true;
                $this->mpdf->Output("group_report.pdf", 'I');
            }
		}else{

			$data['select_view'] = $select_view;
			$data['content'] = 'profile/view_group_report';

			$this->load->view('include/master_view', $data);
		}
	}

	public function analytics_worksheet() {
		if(true) { //$this->input->is_ajax_request()) {

			$worksheetId = $this->input->post('worksheet_id');
			$groupId = $this->input->post('group_id');
			$arrayStudents = $this->input->post('selected_student'); // 
			$selected_student = explode(",",$arrayStudents);
			if($groupId!="" && $groupId!="all_student") {
				$arrayStudents = ""; $selected_student = array();
				$student_group = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$groupId,"record");
				foreach ($student_group as $value) {
					$arrayStudents .= $value["student_id"].",";
				}
				$selected_student = explode(",",$arrayStudents);
			}
			// return array_debug($selected_student); exit;
			$ws = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);
			$students = $this->model_worksheet->get_quiz_list_from_worksheet_id($worksheetId);

			$questionsArray = $ws->result();
			$html = '<div class="table-responsive">';
			// $html .= '<select name="select_group" id="select_group" class="form-control" style="margin-bottom:10px;">
			// <option value="">Filter by Group</option>
			// <option value="all_student">All students</option>';
			// $get_tutor_group = $this->model_users->get_group($this->session->userdata('user_id'));
			// foreach ($get_tutor_group as $key => $value) {
			// 	$check = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$value['id'],"sum");
			// 	if($check > 0) {
			// 		$slt = ($value['id']==$groupId) ? "selected" : "";
			// 		$html .= '<option value="'.$value['id'].'" '.$slt.'>'.$value['group_name'].'</option>';
			// 	} 
			// }
			// $html .= '<select>';
			$html .= '<table class="table table-responsive table-bordered">';

			$html .= '<tr style="background-color: #DFF0D8;"><th></th>';
			$quesNum = 1; $totQ = array(); $minQ = array(); $maxQ = array();
			foreach ($questionsArray AS $question) { 
				$totQ[$quesNum] = 0;
				$totQMark[$quesNum] = 0;
				$minQ[$quesNum] = 0;
				$maxQ[$quesNum] = 0;
				$html .= '<td style="text-align: center;">Q'.$quesNum.'</td>';
				$quesNum++;
			}
			$html .= '<td style="text-align: center;">Tot</td><td style="text-align: center;">Tot<small>%</small></td><td style="text-align: center;font-size:18px;padding: 8px 8px 0 8px;"><i class="fa fa-clock-o"></i></td></tr>';

			if(count($students)==0) {
				$html .= '<tr><td colspan="'.($quesNum+3).'" style="text-align: center;">No students assigned</td></tr>';
			} else {
				$totStudent = 0; $i = 1; $all_score = 0; $minimum_score = 0; $maximum_score = 0; 
				$all_time_quiz = 0; $minimum_time = 0; $maximum_time = 0;
				$min_percent = 0; $max_percent = 0;
				foreach ($students as $student) {
					if(!in_array($student->assignedTo, $selected_student) && $arrayStudents!="all") continue;
					$studentName = $this->model_users->get_username_from_id($student->assignedTo);
					$studentUserName = $this->model_users->get_username_from_id($student->assignedTo,"username");
					$quizId = $this->model_quiz->get_quiz_id_from_studentId_worksheetId($student->assignedTo,$worksheetId);
					$timeTaken = "00:00";
					if($quizId) {
						$attemptId = $this->model_quiz->get_attempt_id_from_quiz($quizId);
						if($attemptId) {
							$timeTaken = $this->model_quiz->get_attempt_time_taken($attemptId);
							$timeTaken = str_replace(" minutes, ","':", $timeTaken);
							$timeTaken = str_replace(" seconds",'"', $timeTaken);
							$time = explode("':", $timeTaken);
							$minute = $time[0];
							$time2 = explode('"', $time[1]);
							$sec = $time2[0];
							$time_quiz = ($minute*60)+$sec;
							$timeTaken = gmdate('i:s', $time_quiz);
							$time_quiz = ($minute*60)+$sec;
							if($i==1) {
								$minimum_time = $time_quiz;
							} else {
								$minimum_time = ($time_quiz<=$minimum_time) ? $time_quiz : $minimum_time;
							}
							$maximum_time = ($time_quiz>=$maximum_time) ? $time_quiz : $maximum_time;
							$all_time_quiz += $time_quiz;
						}
					}
					$totStudent++;
					$tr_style = ($i%2==0) ? "style='background-color:#F5F5F5;'" : "";
					$html .= '<tr '.$tr_style.'><td><span data-id="student_'.$studentUserName.'" data-toggle="modal" data-target="#performanceStudentModal" id="student_performance_link" style="cursor:pointer;">'.$studentName.'</span></td>';
					$quesNum = 1; $total_score = 0; $total_questionMark = 0; $total_scorePercent = 0;
					foreach ($questionsArray AS $question) { 
						$question_id = $question->question_id;
						$questionDetail = $this->model_question->get_question_from_id($question_id);
						$questionMark = ($questionDetail) ? $questionDetail->difficulty : 2;
						$total_questionMark += $questionMark;
						$totQMark[$quesNum] += $questionMark;
						// array_debug($questionMark);
						$student_id = $student->assignedTo;
						$questionScore = $this->model_users->get_quiz_score($student_id,$worksheetId,$quesNum);
						$total_score += $questionScore;
						$totQ[$quesNum] += $questionScore;
						if($totStudent > 1)
							$minQ[$quesNum] = ($minQ[$quesNum]<=$questionScore) ? $minQ[$quesNum] : $questionScore;
						else
							$minQ[$quesNum] = $questionScore;
						$maxQ[$quesNum] = ($maxQ[$quesNum]>=$questionScore) ? $maxQ[$quesNum] : $questionScore;
						$scorePercent = ($questionScore/$questionMark)*100;
						$total_scorePercent += $scorePercent;
						$color = ($scorePercent<=30) ? "red" : "green";
						$color = ($scorePercent>30 && $scorePercent<=70) ? "orange" : $color;
						$show_score = ($questionScore>0) ? number_format($questionScore,1) : 0;
						$html .= '<td style="text-align: center;width: 45px;">
						<div class="c100 p100 small '.$color.'">
		                    <span>'.$show_score.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></td>';
						$quesNum++; 
					}
					$full_score = $total_questionMark;
					$full_score_percent = ceil(($total_score/$full_score)*100);
					$show_total_score_percent = ($total_score>0) ? number_format($full_score_percent) : 0;
					$percen_p = ($full_score_percent==0) ? "100" : number_format($full_score_percent);
					$color = ($full_score_percent<=30) ? "red" : "green";
					$color = ($full_score_percent>30 && $full_score_percent<=70) ? "orange" : $color;
					$show_total_score = ($total_score>0) ? ceil($total_score) : 0;
					$style_span = ($total_score>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';
					$html .= '<td style="text-align: center;width: 45px;">
							<div class="c100 p100 small '.$color.'"">
		                    <span '.$style_span.'>'.$show_total_score.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></td>
	                	<td style="text-align: center;width: 45px;"><div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.$show_total_score_percent.'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></td>
	                	<td style="text-align: center;width: 50px;"><small>'.$timeTaken.'</small></td></tr>';
					if($i==1) {
						$minimum_score = $total_score;
					} else {
						$minimum_score = ($total_score<=$minimum_score) ? $total_score : $minimum_score;
					}
					$maximum_score = ($total_score>=$maximum_score) ? $total_score : $maximum_score;
					$all_score += $total_score;
					if($i==1) {
						$min_percent = $full_score_percent;
					} else {
						$min_percent = ($full_score_percent<=$min_percent) ? $full_score_percent : $min_percent;
					}
					$max_percent = ($full_score_percent>=$max_percent) ? $full_score_percent : $max_percent;
					$i++; 
				}
				$html .= '<tr style="background-color: #EDEDED;"><th>Successful Attempts (%)</th>';
				$quesNum = 1; $totQuestion = count($questionsArray);
				$total_percen = 0; $avg_percent = 0; 
				foreach ($questionsArray AS $question) {
					$percen = ($totQ[$quesNum]>0) ? (($totQ[$quesNum]/$totQMark[$quesNum]))*100 : 0 ;
					$percen = ($percen>100) ? 100 : $percen;
					$total_percen += $percen;
					$color = ($percen<=30) ? "red" : "green";
					$color = ($percen>30 && $percen<=70) ? "orange" : $color;
					$percen_p = ($percen==0) ? "100" : ceil($percen);
					$html .= '<th style="text-align: center;">
					<div class="c100 p'.$percen_p.' small '.$color.'">
	                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
	                    <div class="slice">
	                        <div class="bar"></div>
	                        <div class="fill"></div>
	                   	</div>
	                </div></th>';
					$quesNum++;
				}
				$total_percen = ($total_percen>0) ? ($total_percen/$totQuestion) : 0;
				$color = ($total_percen<=30) ? "red" : "green";
				$color = ($total_percen>30 && $total_percen<=70) ? "orange" : $color;
				$percen_p = ($total_percen==0) ? "100" : ceil($total_percen);
				$html .= '<th style="text-align:center">
						<div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($total_percen).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div>
		                </th>
	                	<th style="text-align: center;"><div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($total_percen).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center">-</th></tr>';
				$html .= '<tr style="background-color: #EDEDED;"><th>Average</th>';
				$quesNum = 1;
				foreach ($questionsArray AS $question) {
					$avg = ($totQ[$quesNum]>0) ? (($totQ[$quesNum]) / $totStudent) : 0 ;
					$color = ($avg==0) ? "red" : "green";
					$color = ($avg>0 && $avg<2) ? "orange" : $color;
					$show = ($avg>0) ? number_format($avg,1) : 0;
					$html .= '<th style="text-align: center;">
							<div class="c100 p100 small '.$color.'">
		                    <span>'.$show.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>';
					$quesNum++;
				}
				$avg_tot_percent = ($total_percen);
				$color = ($avg_tot_percent<=30) ? "red" : "green";
				$color = ($avg_tot_percent>30 && $avg_tot_percent<=70) ? "orange" : $color;
				$percen_p = ($avg_tot_percent==0) ? "100" : ceil($avg_tot_percent);
				$style_span = ($avg_tot_percent>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';
				$show_tot_avg = ceil($avg_tot_percent);
				$html .= '<th style="text-align:center"><div class="c100 p100 small '.$color.'"">
		                    <span '.$style_span.'>'.number_format(($all_score/$totStudent),1).'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>
	                	<th style="text-align: center;"><div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.$show_tot_avg.'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center"><small>'.gmdate('i:s', number_format(($all_time_quiz/$totStudent))).'</small></th></tr>';
				$html .= '<tr style="background-color: #EDEDED;"><th>Minimum</th>';
				$quesNum = 1;
				foreach ($questionsArray AS $question) { 
					$show = ($minQ[$quesNum]>0) ? number_format($minQ[$quesNum],1) : 0;
					$color = ($minQ[$quesNum]==0) ? "red" : "green";
					$color = ($minQ[$quesNum]>0 && $minQ[$quesNum]<2) ? "orange" : $color;
					$html .= '<th style="text-align: center;">
							<div class="c100 p100 small '.$color.'">
		                    <span>'.$show.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>';
					$quesNum++;
				}
				$show_all_min_score = ($minimum_score>0) ? number_format($minimum_score,1) : 0;
				$show_all_min_time = gmdate('i:s', $minimum_time);	
				$color = ($min_percent<=30) ? "red" : "green";
				$color = ($min_percent>30 && $min_percent<=70) ? "orange" : $color;
				$percen_p = ($min_percent==0) ? "100" : ceil($min_percent);	
				$style_span = ($min_percent>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';		
				$html .= '<th style="text-align:center"><div class="c100 p100 small '.$color.'"">
		                    <span '.$style_span.'>'.$show_all_min_score.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>
	                	<th style="text-align: center;">
	                	<div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($min_percent).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center"><small>'.$show_all_min_time.'</small></th></tr>';
				$html .= '<tr style="background-color: #EDEDED;"><th>Maximum</th>';
				$quesNum = 1;
				foreach ($questionsArray AS $question) { 
					$show = ($maxQ[$quesNum]>0) ? number_format($maxQ[$quesNum],1) : 0;
					$color = ($maxQ[$quesNum]==0) ? "red" : "green";
					$color = ($maxQ[$quesNum]>0 && $maxQ[$quesNum]<2) ? "orange" : $color;
					$html .= '<th style="text-align: center;">
							<div class="c100 p100 small '.$color.'">
		                    <span>'.$show.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>';
					$quesNum++;
				}
				$show_all_max_score = ($maximum_score>0) ? number_format($maximum_score,1) : 0;
				$show_all_max_time = gmdate('i:s', $maximum_time);
				$color = ($max_percent<=30) ? "red" : "green";
				$color = ($max_percent>30 && $max_percent<=70) ? "orange" : $color;
				$percen_p = ($max_percent==0) ? "100" : intval($max_percent);
				$style_span = ($max_percent>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';	
				$html .= '<th style="text-align:center"><div class="c100 p100 small '.$color.'"">
		                    <span '.$style_span.'>'.$show_all_max_score.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>
	                	<th style="text-align: center;">
	                	<div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($max_percent).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center"><small>'.$show_all_max_time.'</small></th></tr>';
			}
	        $html .= '</table></div>';
			$returnArray['success'] = true;
			$returnArray['html'] = $html;

			echo json_encode($returnArray);

		} else {

			redirect('404');

		}
	}

	public function performance_by_worksheet($view="json",$getStudents="all",$getWs="all",$getDateStart="all",$getDateEnd="all") {
		if(true) { //$this->input->is_ajax_request()) {
			$this->load->model('model_general');
	        $userRole = $this->session->userdata('user_role');
	        $userId = $this->session->userdata('user_id');
	        if($view=="json") {
				$arrayStudents = $this->input->post('filterStudent');
				$selected_student = ($arrayStudents=="all") ? array() : explode(",",$arrayStudents);
				$arrayWS = $this->input->post('filterWorksheet');
				$selected_ws = ($arrayWS=="all") ? array() : explode(",",$arrayWS);
				$dateStart = $this->input->post('filterDateStart');
				$dateEnd = $this->input->post('filterDateEnd');
			} else {
				$arrayStudents = $getStudents;
				$selected_student = ($arrayStudents=="all") ? array() : explode("-",$arrayStudents);
				$arrayWS = $getWs;
				$selected_ws = ($arrayWS=="all") ? array() : explode("-",$arrayWS);
				$dateStart = $getDateStart;
				$dateEnd = $getDateEnd;
			}
	        $worksheets = $this->model_worksheet->get_worksheets($userId, NULL, NULL, $userRole);
	        $selected_ws_time = array();
	        $filterTime = "";
	        if($dateStart!="all") {
	        	$filterTime = ""; // AND (attemptDateTime>='".$dateStart."' AND attemptDateTime<='".$dateEnd."') ";
	        }
	        // Filter by Creation WS
	       	if($dateStart!="all") {
	        	foreach ($worksheets AS $worksheet) { 
	        		if(!in_array($worksheet->worksheet_id, $selected_ws) && $arrayWS!="all") continue;
	        		$dateStart = $dateStart." 00:00:00"; $dateEnd = $dateEnd." 23:59:59"; 
	        		if($worksheet->created_date<=$dateStart && $worksheet->created_date>=$dateEnd) {
	        			$selected_ws_time[] = $worksheet->worksheet_id;
	        		}
	        	}
	        }
	        $students = $this->model_users->get_student_list($userId);
	        $html = ($view=="json") ? '' : '<!DOCTYPE html><html><head><link rel="stylesheet" href="'.base_url().'/css/circle_pdf.css"><script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
				<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
					<script>
					    function pdfview(){
					        let pdf = new jsPDF(\'l\', \'mm\', [297, 210]);
					        let section = $(\'#report_student_ws_pdf\');
					        let page = function() {
					            pdf.save(\'pagename.pdf\');
					        };
					        pdf.addHTML(section,page);
					    }
					</script></head><body onload="pdfview()" id="report_student_ws_pdf" style="padding:10px 5px;">
					<img src="' . base_url() . 'img/'.BRANCH_LOGO.'" height="120" style="float:left;" /> 
					<div style="float:right"><h3 style="margin:10px 0 0 0 ;">Group Report</h3><h4 style="margin:0;">Performance By Worksheet</h4></div><br style="clear:both;" />';
	        $html .= '
	        <div class="scrollable-table">
	          <table class="table table-striped table-header-rotated">
	            <thead>
	              <tr><th style="border: 1px solid #ccc;width:200px !important;padding: 49px 0 49px 63px;" id="score_all_worksheet">
                    <TOT_AVG>
                </th>';
            $totalMarks = array(); $totScore = array();
            $min = array(); $max = array();
            foreach ($worksheets AS $worksheet) { 
            	if(!in_array($worksheet->worksheet_id, $selected_ws) && $arrayWS!="all") continue;
	        	if(!in_array($worksheet->worksheet_id, $selected_ws_time) && $dateStart!="all") continue;
                $sumMark = $this->model_question->get_total_marks_from_worksheet_id($worksheet->worksheet_id);
                $min[$worksheet->worksheet_id] = 0; $max[$worksheet->worksheet_id] = 0;
                $totalMarks[$worksheet->worksheet_id] = $sumMark;
                $totScore[$worksheet->worksheet_id] = 0;
                if(strlen($worksheet->worksheet_name)> 30) {
                    $substr = substr($worksheet->worksheet_name, 0, 30);
                    $wsName = $substr . '...';
                } else {
                    $wsName = $worksheet->worksheet_name;
                }
                $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." ".$filterTime,"qa.id");
                if($worksheetAttempt) {
                    $x = 1;
                    foreach ($worksheetAttempt as $key => $value) {
                        $totScore[$worksheet->worksheet_id."_".$x] = 0;
                        $min[$worksheet->worksheet_id."_".$x] = 0; $max[$worksheet->worksheet_id."_".$x] = 0;
                        $attemptX = (count($worksheetAttempt)>1) ? " (Attempt ".$x.")" : "";
                        $dataAttempt = (count($worksheetAttempt)>1) ? $x : "";
                        $html .= '<th class="rotate-45 worksheet_report" data-attempt="'.$dataAttempt.'" data-id="'.$worksheet->worksheet_id.'" title="'.$worksheet->worksheet_name.'"><div>'.$wsName.$attemptX.'</div></th>';
                		$x++;  
                    }
                } else {
                	$html .= '<th class="rotate-45 worksheet_report" data-attempt="" data-id="'.$worksheet->worksheet_id.'" title="'.$worksheet->worksheet_name.'"><div>'.$wsName.'</div></th>';
            	} 
            }
            $html .= '<th style="width:auto"></th></tr></thead>';
            $totStudent = count($students);
            foreach ($students AS $key => $student) {
            	if(!in_array($student->id, $selected_student) && $arrayStudents!="all") continue;
	            $html .= '<tr><th class="row-header"><i class="fa fa-user"></i> '.$student->fullname.'</th>'; 
                foreach ($worksheets AS $worksheet) { 
                	if(!in_array($worksheet->worksheet_id, $selected_ws) && $arrayWS!="all") continue;
	        		if(!in_array($worksheet->worksheet_id, $selected_ws_time) && $dateStart!="all") continue;
                    $getStudentWS = $this->model_general->getDataWhere('sj_quiz'," where worksheetId='".$worksheet->worksheet_id."' and assignedTo='".$student->id."' and status='1' and branch_code='".BRANCH_ID."' ");
                    if($getStudentWS) {
                        $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." and assignedTo='".$student->id."' ".$filterTime,"qa.id,attemptScore");
                        if($worksheetAttempt) {
                            $x = 1;
                            foreach ($worksheetAttempt as $k => $value) {
                                $id_quiz_attempt = $value['id'];
                                $allStudentScore = $value['attemptScore'];
                                $score = ($allStudentScore/$totalMarks[$worksheet->worksheet_id])*100;
                                $min[$worksheet->worksheet_id] = ($min[$worksheet->worksheet_id]>=$score) ? $score : $min[$worksheet->worksheet_id];
                                $max[$worksheet->worksheet_id] = ($max[$worksheet->worksheet_id]>=$score) ? $max[$worksheet->worksheet_id] : $score;
                                $totScore[$worksheet->worksheet_id] += $score;
                                $totScore[$worksheet->worksheet_id."_".$x] += $score;
                                $min[$worksheet->worksheet_id."_".$x] = ($min[$worksheet->worksheet_id."_".$x]>=$score) ? $score : $min[$worksheet->worksheet_id."_".$x];
                                $max[$worksheet->worksheet_id."_".$x] = ($max[$worksheet->worksheet_id."_".$x]>=$score) ? $max[$worksheet->worksheet_id."_".$x] : $score;
                        
                                $percen =  ceil($score);
                                $percen = ($percen>100) ? 100 : $percen;
                                $color = ($percen<=30) ? "red" : "green";
                                $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                                $percen_p = ($percen==0) ? "100" : ceil($percen);
                               
                                $html .= '<td>
		                                <div class="c100 p'.$percen_p.' small '.$color.'">
		                                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
		                                    <div class="slice">
		                                        <div class="bar"></div>
		                                        <div class="fill"></div>
		                                    </div>
		                                </div>
		                            </td>';
                        		$x++;
                            }
                        }  else {
                            $html .= '<td><small>NR</small></td>';
                            // $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','asc', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." ".$filterTime,"qa.id");
                            // $x = 1;
                            // foreach ($worksheetAttempt as $key => $value) {
                            //     if($x>1){ $html .= '<td><small>NR</small></td>'; } 
                            //     $x++;  
                            // }
                        }
                    } else {
                        $html .= '<td><small>NA</small></td>';
                        // $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','asc', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." ".$filterTime,"qa.id");
                        // $x = 1;
                        // foreach ($worksheetAttempt as $key => $value) {
                        //     if($x>1){ $html .= '<td><small>NA</small></td>'; } 
                        //     $x++;  
                        // }
                    }
        		}
                $html .= '<td style="width:auto"></td></tr>';
            }
            $html .= '<tr><td>Average</td>';
            $totAVG = 0;
            foreach ($worksheets AS $worksheet) {
            	if(!in_array($worksheet->worksheet_id, $selected_ws) && $arrayWS!="all") continue;
	        	if(!in_array($worksheet->worksheet_id, $selected_ws_time) && $dateStart!="all") continue;
                $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." ".$filterTime,"qa.id");
                if($worksheetAttempt) {
                    $x = 1;
                    foreach ($worksheetAttempt as $key => $value) {
                        $AVGscore = ($totScore[$worksheet->worksheet_id."_".$x]/ $totStudent );
                        $totAVG += $AVGscore;
                        $percen =  ceil($AVGscore);
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        $html .= '<td>
                            <div class="c100 p'.$percen_p.' small '.$color.'">
                                <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                        </td>';
                		$x++;  
                    } 
                } else {
                    $AVGscore = ($totScore[$worksheet->worksheet_id]/ $totStudent );
                    $totAVG += $AVGscore;
                    $percen =  ceil($AVGscore);
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                    $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            	}  
            }
            $totAVG = ($totAVG>0) ? ceil($totAVG / count($worksheets)) : 0;
            $percen =  ceil($totAVG);
            $percen = ($percen>100) ? 100 : $percen;
            $color = ($percen<=30) ? "red" : "green";
            $color = ($percen>30 && $percen<=70) ? "orange" : $color;
            $percen_p = ($percen==0) ? "100" : ceil($percen);
            $tot_avg = '<div class="c100 p'.$percen_p.' big  '.$color.'">
            			<em></em>
                            <span style="font-size: 30px;margin-top: 10px;">'.ceil($percen).'<small>%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>';
            $html = str_replace("<TOT_AVG>", $tot_avg, $html);

            $html .= '<td style="width:auto"></td></tr><tr><td>Minimum</td>';
            
            foreach ($worksheets AS $worksheet) { 
            	if(!in_array($worksheet->worksheet_id, $selected_ws) && $arrayWS!="all") continue;
	        	if(!in_array($worksheet->worksheet_id, $selected_ws_time) && $dateStart!="all") continue;
                $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." ".$filterTime,"qa.id");
                if($worksheetAttempt) {
                    $x = 1;
                    foreach ($worksheetAttempt as $key => $value) {
                        $percen =  $min[$worksheet->worksheet_id."_".$x];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        
                        $html .= '<td>
	                        <div class="c100 p'.$percen_p.' small '.$color.'">
	                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
	                            <div class="slice">
	                                <div class="bar"></div>
	                                <div class="fill"></div>
	                            </div>
	                        </div>
	                    </td>';
                		$x++;  
                    } 
                } else {
                    $percen =  $min[$worksheet->worksheet_id];
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                    $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            	} 
            }
            $html .= '<td style="width:auto"></td></tr><tr><td>Maximum</td>';
            
            foreach ($worksheets AS $worksheet) { 
            	if(!in_array($worksheet->worksheet_id, $selected_ws) && $arrayWS!="all") continue;
	        	if(!in_array($worksheet->worksheet_id, $selected_ws_time) && $dateStart!="all") continue;
                $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." ".$filterTime,"qa.id");
                if($worksheetAttempt) {
                    $x = 1;
                    foreach ($worksheetAttempt as $key => $value) {
                        $percen =  $max[$worksheet->worksheet_id."_".$x];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        
                        $html .= '<td>
	                        <div class="c100 p'.$percen_p.' small '.$color.'">
	                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
	                            <div class="slice">
	                                <div class="bar"></div>
	                                <div class="fill"></div>
	                            </div>
	                        </div>
	                    </td>';
                		$x++;  
                    } 
                } else {
                    $percen =  $max[$worksheet->worksheet_id];
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                    $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            	} 
            }
            $html .= '<td style="width:auto"></td></tr></tbody></table></div>';
			if($view=="json") {
				$returnArray['success'] = true;
				$returnArray['html'] = $html;
				echo json_encode($returnArray);
			} elseif($view=="pdf") {
				error_reporting(E_ERROR | E_PARSE);
				
				$html .= '</body></html>';
				$html = str_replace('<div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>', '', $html);
				$html = str_replace('padding: 49px 0 49px 63px;', 'padding: 80px 0 80px 63px;', $html);
				$html = str_replace('font-size: 11px', 'font-size: 14px', $html);
				$html = str_replace('font-size: 9px', 'font-size: 11px', $html);
				$html = str_replace('<em></em>', '<small style="font-size:18px;">Overall Progress</small>', $html);
				// $html .= '';
				echo $html;
				// $this->load->library('pdfgenerator');
				// $this->pdfgenerator->generate($html,'Testing_PerformanceByWS');
				// $this->load->library('m_pdf');
				// $this->mpdf->WriteHTML($html);
				// $this->mpdf->SetTitle("Performance By Worksheet");
				// $this->mpdf->Output('Testing_PerformanceByWS.pdf','D'); 
			}
		} else {
			redirect('404');
		}
	}

	public function performance_by_ability($view="json",$getStudents="all",$getDateStart="all",$getDateEnd="all") {
		if(true) { //$this->input->is_ajax_request()) {
			$this->load->model('model_general');
	        $userRole = $this->session->userdata('user_role');
	        $userId = $this->session->userdata('user_id');
	        if($view=="json") {
				$arrayStudents = $this->input->post('filterStudent');
				$selected_student = ($arrayStudents=="all") ? array() : explode(",",$arrayStudents);
				$dateStart = $this->input->post('filterDateStart');
				$dateEnd = $this->input->post('filterDateEnd');
			} else {
				$arrayStudents = $getStudents;
				$selected_student = ($arrayStudents=="all") ? array() : explode("-",$arrayStudents);
				$dateStart = $getDateStart;
				$dateEnd = $getDateEnd;
			}
	        $worksheets = $this->model_worksheet->get_worksheets($userId, NULL, NULL, $userRole);
	        $selected_ws_time = array();
	        $filterTime = "";
	        if($dateStart!="all") {
	        	$filterTime = " AND (attemptDateTime>='".$dateStart."' AND attemptDateTime<='".$dateEnd."') ";
	        	$date_range = array($dateEnd, $dateStart);
	        } else {
	        	$date_range = array();
	        }
	        $students = $this->model_users->get_student_list($userId);
	        $ability = $this->model_general->getAllData('sj_ability_tid','ability_id','asc'," where branch_id='".BRANCH_ID."' ");
	        $html = ($view=="json") ? '' : '<!DOCTYPE html><html><head><link rel="stylesheet" href="'.base_url().'/css/circle_pdf.css"><script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
				<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
					<script>
					    function pdfview(){
					        let pdf = new jsPDF(\'l\', \'mm\', [297, 210]);
					        let section = $(\'#report_student_ws_pdf\');
					        let page = function() {
					            pdf.save(\'pagename.pdf\');
					        };
					        pdf.addHTML(section,page);
					    }
					</script></head><body onload="pdfview()" id="report_student_ws_pdf" style="padding:10px 5px;">
					<img src="' . base_url() . 'img/'.BRANCH_LOGO.'" height="120" style="float:left;" /> 
					<div style="float:right"><h3 style="margin:10px 0 0 0 ;">Group Report</h3><h4 style="margin:0;">Performance By Worksheet</h4></div><br style="clear:both;" />';
	        $html .= '
	        <div class="scrollable-table">
	          <table class="table table-striped table-header-rotated">
	            <thead>
	              <tr><th style="border: 1px solid #ccc;width:200px !important;padding: 49px 0 49px 63px;" id="score_all_worksheet">
                    <TOT_AVG>
                </th>';
            $totScore = array();
            $min = array(); $max = array();
            foreach ($ability as $r => $ab) { 
                if(strlen($ab['ability_name'])> 40) {
                    $substr = substr($ab['ability_name'], 0, 40);
                    $wsName = $substr . '...';
                } else {
                    $wsName = $ab['ability_name'];
                }
                $wsName = str_replace("/", "/ ", $wsName );
                $totScore[$ab['ability_id']] = 0;
                $min[$ab['ability_id']] = 0;
                $max[$ab['ability_id']] = 0;
                $html .= '<th class="rotate-45" data-attempt="" data-id="'.$ab['ability_id'].'" title="'.$ab['ability_name'].'"><div>'.$wsName.'</div></th>';
            }
            $html .= '<th style="width:auto"></th></tr></thead>';
            $totStudent = count($students);
            foreach ($students AS $key => $student) {
            	if(!in_array($student->id, $selected_student) && $arrayStudents!="all") continue;
            	$performance = $this->model_users->get_ep_performance_ability(array($student->id), $date_range)[$student->id];
	            $html .= '<tr><th class="row-header"><i class="fa fa-user"></i> '.$student->fullname.'</th>'; 
	            foreach ($ability as $r => $ab) {
                    $percen = $performance[$ab['ability_name']]['percentage'] ;
                    $score = $percen;
                     $min[$ab['ability_id']] = ($min[$ab['ability_id']]>=$score) ? $score : $min[$ab['ability_id']];
                    $max[$ab['ability_id']] = ($max[$ab['ability_id']]>=$score) ? $max[$ab['ability_id']] : $score;
                    $totScore[$ab['ability_id']] += $score;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                    $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
                }
                $html .= '<td style="width:auto"></td></tr>';
            }
            $html .= '<tr><td>Average</td>';
            $totAVG = 0;
            foreach ($ability as $r => $ab) { 
                $AVGscore = ($totScore[$ab['ability_id']]>0) ? ($totScore[$ab['ability_id']]/ $totStudent ) : 0;
                $totAVG += $AVGscore;
                $percen = $AVGscore;
                $percen = ($percen>100) ? 100 : $percen;
                $color = ($percen<=30) ? "red" : "green";
                $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                $percen_p = ($percen==0) ? "100" : ceil($percen);  
                $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            } 
            $totAVG = ($totAVG>0) ? ceil($totAVG / count($ability)) : 0;
            $percen =  ceil($totAVG);
            $percen = ($percen>100) ? 100 : $percen;
            $color = ($percen<=30) ? "red" : "green";
            $color = ($percen>30 && $percen<=70) ? "orange" : $color;
            $percen_p = ($percen==0) ? "100" : ceil($percen);
            $tot_avg = '<div class="c100 p'.$percen_p.' big  '.$color.'">
            			<em></em>
                            <span style="font-size: 30px;margin-top: 10px;">'.ceil($percen).'<small>%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>';
            $html = str_replace("<TOT_AVG>", $tot_avg, $html);

            $html .= '<td style="width:auto"></td></tr><tr><td>Minimum</td>';
            foreach ($ability as $r => $ab) { 
            	$percen =  $min[$ab['ability_id']];
                $percen = ($percen>100) ? 100 : $percen;
                $color = ($percen<=30) ? "red" : "green";
                $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                $percen_p = ($percen==0) ? "100" : ceil($percen);
                $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            }
            $html .= '<td style="width:auto"></td></tr><tr><td>Maximum</td>';
            foreach ($ability as $r => $ab) { 
            	$percen =  $max[$ab['ability_id']];
                $percen = ($percen>100) ? 100 : $percen;
                $color = ($percen<=30) ? "red" : "green";
                $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                $percen_p = ($percen==0) ? "100" : ceil($percen);
                $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            }
            $html .= '<td style="width:auto"></td></tr></tbody></table></div>';
			if($view=="json") {
				$returnArray['success'] = true;
				$returnArray['html'] = $html;
				echo json_encode($returnArray);
			} elseif($view=="pdf") {
				error_reporting(E_ERROR | E_PARSE);
				
				$html .= '</body></html>';
				$html = str_replace('<div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>', '', $html);
				$html = str_replace('padding: 49px 0 49px 63px;', 'padding: 80px 0 80px 63px;', $html);
				$html = str_replace('font-size: 11px', 'font-size: 14px', $html);
				$html = str_replace('font-size: 9px', 'font-size: 11px', $html);
				$html = str_replace('<em></em>', '<small style="font-size:18px;">Overall Progress</small>', $html);
				echo $html;
			}
		} else {
			redirect('404');
		}
	}

	public function performance_by_topic_tid($view="json",$getStudents="all",$getDateStart="all",$getDateEnd="all") {
		if(true) { //$this->input->is_ajax_request()) {
			$this->load->model('model_general');
	        $userRole = $this->session->userdata('user_role');
	        $userId = $this->session->userdata('user_id');
	        if($view=="json") {
				$arrayStudents = $this->input->post('filterStudent');
				$selected_student = ($arrayStudents=="all") ? array() : explode(",",$arrayStudents);
				$dateStart = $this->input->post('filterDateStart');
				$dateEnd = $this->input->post('filterDateEnd');
			} else {
				$arrayStudents = $getStudents;
				$selected_student = ($arrayStudents=="all") ? array() : explode("-",$arrayStudents);
				$dateStart = $getDateStart;
				$dateEnd = $getDateEnd;
			}
	        $worksheets = $this->model_worksheet->get_worksheets($userId, NULL, NULL, $userRole);
	        $selected_ws_time = array();
	        $filterTime = "";
	        if($dateStart!="all") {
	        	$filterTime = " AND (attemptDateTime>='".$dateStart."' AND attemptDateTime<='".$dateEnd."') ";
	        	$date_range = array($dateStart, $dateEnd);
	        } else {
	        	$date_range = array();
	        }
	        $students = $this->model_users->get_student_list($userId);
	        $topics_tid = $this->model_question->get_topiclevel_list('junior');
	        $html = ($view=="json") ? '' : '<!DOCTYPE html><html><head><link rel="stylesheet" href="'.base_url().'/css/circle_pdf.css"><script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
				<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
					<script>
					    function pdfview(){
					        let pdf = new jsPDF(\'l\', \'mm\', [297, 210]);
					        let section = $(\'#report_student_ws_pdf\');
					        let page = function() {
					            pdf.save(\'pagename.pdf\');
					        };
					        pdf.addHTML(section,page);
					    }
					</script></head><body onload="pdfview()" id="report_student_ws_pdf" style="padding:10px 5px;">
					<img src="' . base_url() . 'img/'.BRANCH_LOGO.'" height="120" style="float:left;" /> 
					<div style="float:right"><h3 style="margin:10px 0 0 0 ;">Group Report</h3><h4 style="margin:0;">Performance By Worksheet</h4></div><br style="clear:both;" />';
	        $html .= '
	        <div class="scrollable-table">
	          <table class="table table-striped table-header-rotated">
	            <thead>
	              <tr><th style="border: 1px solid #ccc;width:200px !important;padding: 49px 0 49px 63px;" id="score_all_worksheet">
                    <TOT_AVG>
                </th>';
            $totScore = array();
            $min = array(); $max = array();
                foreach ($topics_tid as $topic) { 
                    if(strlen($topic['topic_name'])> 40) {
                        $substr = substr($topic['topic_name'], 0, 40);
                        $wsName = $substr . '...';
                    } else {
                        $wsName = $topic['topic_name'];
                    }
                    $wsName = str_replace("/", "/ ", $wsName );
                    $totScore[$topic['topic_id']] = 0;
                    $min[$topic['topic_id']] = 0;
                    $max[$topic['topic_id']] = 0;
                $html .= '<th class="rotate-45" data-attempt="" data-id="'.$topic['topic_id'].'" title="'.$topic['topic_name'].'"><div>'.$wsName.'</div></th>';
            }
            $html .= '<th style="width:auto"></th></tr></thead>';
            $totStudent = count($students);
            foreach ($students AS $key => $student) {
            	if(!in_array($student->id, $selected_student) && $arrayStudents!="all") continue;
            	$null = NULL;
            	$performance = $this->model_users->get_ep_performance(array($student->id),$null, $date_range)[$student->id];
	            $html .= '<tr><th class="row-header"><i class="fa fa-user"></i> '.$student->fullname.'</th>'; 
	            foreach ($topics_tid as $topic) {
                    $percen = $performance[$topic['topic_name']]['percentage'] ;
                    $score = $percen;
                    $min[$topic['topic_id']] = ($min[$topic['topic_id']]>=$score) ? $score : $min[$topic['topic_id']];
                    $max[$topic['topic_id']] = ($max[$topic['topic_id']]>=$score) ? $max[$topic['topic_id']] : $score;
                    $totScore[$topic['topic_id']] += $score;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                    $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
                }
                $html .= '<td style="width:auto"></td></tr>';
            }
            $html .= '<tr><td>Average</td>';
            $totAVG = 0;
            foreach ($topics_tid as $topic) {
                $AVGscore = ($totScore[$topic['topic_id']]>0) ? ($totScore[$topic['topic_id']]/ $totStudent ) : 0;
                $totAVG += $AVGscore;
                $percen = $AVGscore;
                $percen = ($percen>100) ? 100 : $percen;
                $color = ($percen<=30) ? "red" : "green";
                $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                $percen_p = ($percen==0) ? "100" : ceil($percen);  
                $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            } 
            $totAVG = ($totAVG>0) ? ceil($totAVG / count($topics_tid)) : 0;
            $percen =  ceil($totAVG);
            $percen = ($percen>100) ? 100 : $percen;
            $color = ($percen<=30) ? "red" : "green";
            $color = ($percen>30 && $percen<=70) ? "orange" : $color;
            $percen_p = ($percen==0) ? "100" : ceil($percen);
            $tot_avg = '<div class="c100 p'.$percen_p.' big  '.$color.'">
            			<em></em>
                            <span style="font-size: 30px;margin-top: 10px;">'.ceil($percen).'<small>%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>';
            $html = str_replace("<TOT_AVG>", $tot_avg, $html);

            $html .= '<td style="width:auto"></td></tr><tr><td>Minimum</td>';
            foreach ($topics_tid as $topic) {
            	$percen =  $min[$topic['topic_id']];
                $percen = ($percen>100) ? 100 : $percen;
                $color = ($percen<=30) ? "red" : "green";
                $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                $percen_p = ($percen==0) ? "100" : ceil($percen);
                $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            }
            $html .= '<td style="width:auto"></td></tr><tr><td>Maximum</td>';
            foreach ($topics_tid as $topic) {
            	$percen =  $max[$topic['topic_id']];
                $percen = ($percen>100) ? 100 : $percen;
                $color = ($percen<=30) ? "red" : "green";
                $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                $percen_p = ($percen==0) ? "100" : ceil($percen);
                $html .= '<td>
                        <div class="c100 p'.$percen_p.' small '.$color.'">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>';
            }
            $html .= '<td style="width:auto"></td></tr></tbody></table></div>';
			if($view=="json") {
				$returnArray['success'] = true;
				$returnArray['html'] = $html;
				echo json_encode($returnArray);
			} elseif($view=="pdf") {
				error_reporting(E_ERROR | E_PARSE);
				
				$html .= '</body></html>';
				$html = str_replace('<div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>', '', $html);
				$html = str_replace('padding: 49px 0 49px 63px;', 'padding: 80px 0 80px 63px;', $html);
				$html = str_replace('font-size: 11px', 'font-size: 14px', $html);
				$html = str_replace('font-size: 9px', 'font-size: 11px', $html);
				$html = str_replace('<em></em>', '<small style="font-size:18px;">Overall Progress</small>', $html);
				echo $html;
			}
		} else {
			redirect('404');
		}
	}

	public function analytics_by_worksheet() {
		if(true) { //$this->input->is_ajax_request()) {
			$this->load->model('model_general');
	        $userRole = $this->session->userdata('user_role');
	        $userId = $this->session->userdata('user_id');
			$worksheetId = $this->input->post('filterWsId');
			$worksheetTitle = $this->input->post('filterWsName');
			$WsAttemptId = $this->input->post('filterWsAttempt');
			$arrayStudents = $this->input->post('filterStudent'); // "123"; //
			$selected_student = ($arrayStudents=="all") ? array() : explode(",",$arrayStudents);
			$ws = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);
			$students = $this->model_worksheet->get_quiz_list_from_worksheet_id($worksheetId);
			$WsAttempt = ($WsAttemptId=="") ? "" : "(Attempt ".$WsAttemptId.")";
			$xAttempt = ($WsAttemptId=="") ? "1" : "X,1";
			$xLimit = ($WsAttemptId=="") ? "1" : (intval($WsAttemptId)-1);
			$xAttempt = str_replace("X", $xLimit, $xAttempt);
			$questionsArray = $ws->result();
			$html = '<div class="scrollable-table"><table class="table table-striped table-header-rotated">
            <thead>
              <tr>
                <td rowspan="2" style="border: 1px solid #ccc;width:205px !important;padding-top: 15px;">
                    <img src="'.base_url().'img/back.png" width="50" style="cursor: pointer;" class="back_to_ws" />
                </td>
                <td colspan="1000" style="border-right: 0 !important;" class="title_ws"><b><i class="fa fa-newspaper-o"></i> '.$worksheetTitle.' '.$WsAttempt.'</b></td>
            </tr>';

			$html .= '<tr>';
			$quesNum = 1; $totQ = array(); $minQ = array(); $maxQ = array();
			foreach ($questionsArray AS $question) { 
				$totQ[$quesNum] = 0;
				$totQMark[$quesNum] = 0;
				$minQ[$quesNum] = 0;
				$maxQ[$quesNum] = 0;
				$html .= '<td class="question_report" id="'.$quesNum.'"><b>Q'.$quesNum.'</b></td>';
				$quesNum++;
			}
			$html .= '<td style="text-align: center;">Tot</td><td style="text-align: center;">Tot<small>%</small></td><td style="text-align: center;font-size:18px;padding: 8px 8px 0 8px;"><i class="fa fa-clock-o"></i></td>
			<td style="width:auto"></td>
              </tr>
            </thead>
            <tbody>';

			if(count($students)==0) {
				$html .= '<tr><td colspan="'.($quesNum+4).'" style="text-align: center;">No students assigned</td></tr>';
			} else {
				$totStudent = 0; $i = 1; $all_score = 0; $minimum_score = 0; $maximum_score = 0; 
				$all_time_quiz = 0; $minimum_time = 0; $maximum_time = 0;
				$min_percent = 0; $max_percent = 0;
				foreach ($students as $student) {
					if(!in_array($student->assignedTo, $selected_student) && $arrayStudents!="all") continue;
					$studentName = $this->model_users->get_username_from_id($student->assignedTo);
					$studentUserName = $this->model_users->get_username_from_id($student->assignedTo,"username");
					$totStudent++;
					$tr_style = ($i%2==0) ? "style='background-color:#F5F5F5;'" : "";
					// $html .= '<tr '.$tr_style.'><td><span data-id="student_'.$studentUserName.'" data-toggle="modal" data-target="#performanceStudentModal" id="student_performance_link" style="cursor:pointer;">'.$studentName.'</span></td>';
					$worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','asc', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheetId." and qz.assignedTo='".$student->assignedTo."' and qa.branch_code=".BRANCH_ID,"qa.quizId");
                    if(count($worksheetAttempt)<=1) {
						$quizId = $this->model_quiz->get_quiz_id_from_studentId_worksheetId($student->assignedTo,$worksheetId);
						$timeTaken = "00:00";
						if($quizId) {
							$attemptId = $this->model_quiz->get_attempt_id_from_quiz($quizId,$xAttempt,"desc");
							if($attemptId) {
								$timeTaken = $this->model_quiz->get_attempt_time_taken($attemptId);
								$timeTaken = str_replace(" minutes, ","':", $timeTaken);
								$timeTaken = str_replace(" seconds",'"', $timeTaken);
								$time = explode("':", $timeTaken);
								$minute = $time[0];
								$time2 = explode('"', $time[1]);
								$sec = $time2[0];
								$time_quiz = ($minute*60)+$sec;
								$timeTaken = gmdate('i:s', $time_quiz);
								$time_quiz = ($minute*60)+$sec;
								if($i==1) {
									$minimum_time = $time_quiz;
								} else {
									$minimum_time = ($time_quiz<=$minimum_time) ? $time_quiz : $minimum_time;
								}
								$maximum_time = ($time_quiz>=$maximum_time) ? $time_quiz : $maximum_time;
								$all_time_quiz += $time_quiz;
							}
						}
						$html .= '<tr><th class="row-header"><i class="fa fa-user"></i> '.$studentName.'</th>';
						$quesNum = 1; $total_score = 0; $total_questionMark = 0; $total_scorePercent = 0;
						foreach ($questionsArray AS $question) { 
							$question_id = $question->question_id;
							$questionDetail = $this->model_question->get_question_from_id($question_id);
							$questionMark = ($questionDetail) ? $questionDetail->difficulty : 2;
							$total_questionMark += $questionMark;
							$totQMark[$quesNum] += $questionMark;
							// array_debug($questionMark);
							$student_id = $student->assignedTo;
							$questionScore = $this->model_users->get_quiz_score($student_id,$worksheetId,$quesNum,$attemptId);
							$total_score += $questionScore;
							$totQ[$quesNum] += $questionScore;
							if($totStudent > 1)
								$minQ[$quesNum] = ($minQ[$quesNum]<=$questionScore) ? $minQ[$quesNum] : $questionScore;
							else
								$minQ[$quesNum] = $questionScore;
							$maxQ[$quesNum] = ($maxQ[$quesNum]>=$questionScore) ? $maxQ[$quesNum] : $questionScore;
							$scorePercent = ($questionScore/$questionMark)*100;
							$total_scorePercent += $scorePercent;
							$color = ($scorePercent<=30) ? "red" : "green";
							$color = ($scorePercent>30 && $scorePercent<=70) ? "orange" : $color;
							$show_score = ($questionScore>0) ? number_format($questionScore,1) : 0;
							$html .= '<td>
							<div class="c100 p100 small '.$color.'">
			                    <span>'.$show_score.'</span>
			                    <div class="slice">
			                        <div class="bar"></div>
			                        <div class="fill"></div>
			                    </div>
		                	</div></td>';
							$quesNum++; 
						}
						$full_score = $total_questionMark;
						$full_score_percent = ceil(($total_score/$full_score)*100);
						$show_total_score_percent = ($total_score>0) ? number_format($full_score_percent) : 0;
						$percen_p = ($full_score_percent==0) ? "100" : number_format($full_score_percent);
						$color = ($full_score_percent<=30) ? "red" : "green";
						$color = ($full_score_percent>30 && $full_score_percent<=70) ? "orange" : $color;
						$show_total_score = ($total_score>0) ? ceil($total_score) : 0;
						$style_span = ($total_score>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';
						$html .= '<td>
								<div class="c100 p100 small '.$color.'"">
			                    <span '.$style_span.'>'.$show_total_score.'</span>
			                    <div class="slice">
			                        <div class="bar"></div>
			                        <div class="fill"></div>
			                    </div>
		                	</div></td>
		                	<td><div class="c100 p'.$percen_p.' small '.$color.'">
			                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.$show_total_score_percent.'<small style="font-size: 9px;">%</small></span>
			                    <div class="slice">
			                        <div class="bar"></div>
			                        <div class="fill"></div>
			                    </div>
		                	</div></td>
		                	<td style="text-align: center;width: 50px;"><small>'.$timeTaken.'</small></td><td style="width:auto"></td></tr>';
						if($i==1) {
							$minimum_score = $total_score;
						} else {
							$minimum_score = ($total_score<=$minimum_score) ? $total_score : $minimum_score;
						}
						$maximum_score = ($total_score>=$maximum_score) ? $total_score : $maximum_score;
						$all_score += $total_score;
						if($i==1) {
							$min_percent = $full_score_percent;
						} else {
							$min_percent = ($full_score_percent<=$min_percent) ? $full_score_percent : $min_percent;
						}
						$max_percent = ($full_score_percent>=$max_percent) ? $full_score_percent : $max_percent;
						
					} else {
						$totStudent--;
	                    $ax = 0;
	                    foreach ($worksheetAttempt as $key => $value) {
	                        if($ax==0){ 
	                        	$html .= '<tr><th class="row-header" colspan="100"><i class="fa fa-user"></i> '.$studentName.'</th></tr>';
	                        }
	                        $showAttempt = $ax+1;
	                        $html .= '<tr><th class="row-header">&nbsp;&nbsp;&nbsp;<i class="fa fa-edit"></i> Attempt '.$showAttempt.'</th>';
	                        $quizId = $value['quizId'];
	                        $xAttempt = $ax.",1";
	                        $attemptId = $this->model_quiz->get_attempt_id_from_quiz($quizId,$xAttempt,"asc");
							if($attemptId) {
								$timeTaken = $this->model_quiz->get_attempt_time_taken($attemptId);
								$timeTaken = str_replace(" minutes, ","':", $timeTaken);
								$timeTaken = str_replace(" seconds",'"', $timeTaken);
								$time = explode("':", $timeTaken);
								$minute = $time[0];
								$time2 = explode('"', $time[1]);
								$sec = $time2[0];
								$time_quiz = ($minute*60)+$sec;
								$timeTaken = gmdate('i:s', $time_quiz);
								$time_quiz = ($minute*60)+$sec;
								if($i==1) {
									$minimum_time = $time_quiz;
								} else {
									$minimum_time = ($time_quiz<=$minimum_time) ? $time_quiz : $minimum_time;
								}
								$maximum_time = ($time_quiz>=$maximum_time) ? $time_quiz : $maximum_time;
								$all_time_quiz += $time_quiz;

								$quesNum = 1; $total_score = 0; $total_questionMark = 0; $total_scorePercent = 0;
								foreach ($questionsArray AS $question) { 
									$question_id = $question->question_id;
									$questionDetail = $this->model_question->get_question_from_id($question_id);
									$questionMark = ($questionDetail) ? $questionDetail->difficulty : 2;
									$total_questionMark += $questionMark;
									$totQMark[$quesNum] += $questionMark;
									// array_debug($questionMark);
									$student_id = $student->assignedTo;
									$questionScore = $this->model_users->get_quiz_score($student_id,$worksheetId,$quesNum,$attemptId);
									$total_score += $questionScore;
									$totQ[$quesNum] += $questionScore;
									if($totStudent > 1)
										$minQ[$quesNum] = ($minQ[$quesNum]<=$questionScore) ? $minQ[$quesNum] : $questionScore;
									else
										$minQ[$quesNum] = $questionScore;
									$maxQ[$quesNum] = ($maxQ[$quesNum]>=$questionScore) ? $maxQ[$quesNum] : $questionScore;
									$scorePercent = ($questionScore/$questionMark)*100;
									$total_scorePercent += $scorePercent;
									$color = ($scorePercent<=30) ? "red" : "green";
									$color = ($scorePercent>30 && $scorePercent<=70) ? "orange" : $color;
									$show_score = ($questionScore>0) ? number_format($questionScore,1) : 0;
									$html .= '<td>
									<div class="c100 p100 small '.$color.'">
					                    <span>'.$show_score.'</span>
					                    <div class="slice">
					                        <div class="bar"></div>
					                        <div class="fill"></div>
					                    </div>
				                	</div></td>';
									$quesNum++; 
								}
								$full_score = $total_questionMark;
								$full_score_percent = ceil(($total_score/$full_score)*100);
								$show_total_score_percent = ($total_score>0) ? number_format($full_score_percent) : 0;
								$percen_p = ($full_score_percent==0) ? "100" : number_format($full_score_percent);
								$color = ($full_score_percent<=30) ? "red" : "green";
								$color = ($full_score_percent>30 && $full_score_percent<=70) ? "orange" : $color;
								$show_total_score = ($total_score>0) ? ceil($total_score) : 0;
								$style_span = ($total_score>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';
								$html .= '<td>
										<div class="c100 p100 small '.$color.'"">
					                    <span '.$style_span.'>'.$show_total_score.'</span>
					                    <div class="slice">
					                        <div class="bar"></div>
					                        <div class="fill"></div>
					                    </div>
				                	</div></td>
				                	<td><div class="c100 p'.$percen_p.' small '.$color.'">
					                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.$show_total_score_percent.'<small style="font-size: 9px;">%</small></span>
					                    <div class="slice">
					                        <div class="bar"></div>
					                        <div class="fill"></div>
					                    </div>
				                	</div></td>
				                	<td style="text-align: center;width: 50px;"><small>'.$timeTaken.'</small></td><td style="width:auto"></td></tr>';
								if($i==1) {
									$minimum_score = $total_score;
								} else {
									$minimum_score = ($total_score<=$minimum_score) ? $total_score : $minimum_score;
								}
								$maximum_score = ($total_score>=$maximum_score) ? $total_score : $maximum_score;
								$all_score += $total_score;
								if($i==1) {
									$min_percent = $full_score_percent;
								} else {
									$min_percent = ($full_score_percent<=$min_percent) ? $full_score_percent : $min_percent;
								}
								$max_percent = ($full_score_percent>=$max_percent) ? $full_score_percent : $max_percent;
								
							}
							$totStudent++;
	                        $ax++;  
	                    }
					}
					$i++; 

				}

				$html .= '<tr style="background-color: #EDEDED;"><th>Successful Attempts (%)</th>';
				$quesNum = 1; $totQuestion = count($questionsArray);
				$total_percen = 0; $avg_percent = 0; 
				foreach ($questionsArray AS $question) {
					$percen = ($totQ[$quesNum]>0) ? (($totQ[$quesNum]/$totQMark[$quesNum]))*100 : 0 ;
					$percen = ($percen>100) ? 100 : $percen;
					$total_percen += $percen;
					$color = ($percen<=30) ? "red" : "green";
					$color = ($percen>30 && $percen<=70) ? "orange" : $color;
					$percen_p = ($percen==0) ? "100" : ceil($percen);
					$html .= '<th>
					<div class="c100 p'.$percen_p.' small '.$color.'">
	                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($percen).'<small style="font-size: 9px;">%</small></span>
	                    <div class="slice">
	                        <div class="bar"></div>
	                        <div class="fill"></div>
	                   	</div>
	                </div></th>';
					$quesNum++;
				}
				$total_percen = ($total_percen>0) ? ($total_percen/$totQuestion) : 0;
				$color = ($total_percen<=30) ? "red" : "green";
				$color = ($total_percen>30 && $total_percen<=70) ? "orange" : $color;
				$percen_p = ($total_percen==0) ? "100" : ceil($total_percen);
				$html .= '<th>
						<div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($total_percen).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div>
		                </th>
	                	<th><div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($total_percen).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center">-</th><th style="width:auto"></th></tr>';
				$html .= '<tr style="background-color: #EDEDED;"><th>Average</th>';
				$quesNum = 1;
				foreach ($questionsArray AS $question) {
					$avg = ($totQ[$quesNum]>0) ? (($totQ[$quesNum]) / $totStudent) : 0 ;
					$color = ($avg==0) ? "red" : "green";
					$color = ($avg>0 && $avg<2) ? "orange" : $color;
					$show = ($avg>0) ? number_format($avg,1) : 0;
					$html .= '<th style="text-align: center;">
							<div class="c100 p100 small '.$color.'">
		                    <span>'.$show.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>';
					$quesNum++;
				}
				$avg_tot_percent = ($total_percen);
				$color = ($avg_tot_percent<=30) ? "red" : "green";
				$color = ($avg_tot_percent>30 && $avg_tot_percent<=70) ? "orange" : $color;
				$percen_p = ($avg_tot_percent==0) ? "100" : ceil($avg_tot_percent);
				$style_span = ($avg_tot_percent>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';
				$show_tot_avg = ceil($avg_tot_percent);
				$totScoreStudent = ($totStudent==0 || $all_score==0) ? 0 : ($all_score/$totStudent);
				$totTimeStudent = ($all_time_quiz==0 || $totStudent==0) ? 0 : ($all_time_quiz/$totStudent);
				$html .= '<th style="text-align:center"><div class="c100 p100 small '.$color.'"">
		                    <span '.$style_span.'>'.number_format($totScoreStudent,1).'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>
	                	<th style="text-align: center;"><div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.$show_tot_avg.'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center"><small>'.gmdate('i:s', number_format($totTimeStudent)).'</small></th><th style="width:auto"></th></tr>';
				$html .= '<tr style="background-color: #EDEDED;"><th>Minimum</th>';
				$quesNum = 1;
				foreach ($questionsArray AS $question) { 
					$show = ($minQ[$quesNum]>0) ? number_format($minQ[$quesNum],1) : 0;
					$color = ($minQ[$quesNum]==0) ? "red" : "green";
					$color = ($minQ[$quesNum]>0 && $minQ[$quesNum]<2) ? "orange" : $color;
					$html .= '<th style="text-align: center;">
							<div class="c100 p100 small '.$color.'">
		                    <span>'.$show.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>';
					$quesNum++;
				}
				$show_all_min_score = ($minimum_score>0) ? number_format($minimum_score,1) : 0;
				$show_all_min_time = gmdate('i:s', $minimum_time);	
				$color = ($min_percent<=30) ? "red" : "green";
				$color = ($min_percent>30 && $min_percent<=70) ? "orange" : $color;
				$percen_p = ($min_percent==0) ? "100" : ceil($min_percent);	
				$style_span = ($min_percent>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';		
				$html .= '<th style="text-align:center"><div class="c100 p100 small '.$color.'"">
		                    <span '.$style_span.'>'.$show_all_min_score.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>
	                	<th style="text-align: center;">
	                	<div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($min_percent).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center"><small>'.$show_all_min_time.'</small></th><th style="width:auto"></th></tr>';
				$html .= '<tr style="background-color: #EDEDED;"><th>Maximum</th>';
				$quesNum = 1;
				foreach ($questionsArray AS $question) { 
					$show = ($maxQ[$quesNum]>0) ? number_format($maxQ[$quesNum],1) : 0;
					$color = ($maxQ[$quesNum]==0) ? "red" : "green";
					$color = ($maxQ[$quesNum]>0 && $maxQ[$quesNum]<2) ? "orange" : $color;
					$html .= '<th style="text-align: center;">
							<div class="c100 p100 small '.$color.'">
		                    <span>'.$show.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>';
					$quesNum++;
				}
				$show_all_max_score = ($maximum_score>0) ? number_format($maximum_score,1) : 0;
				$show_all_max_time = gmdate('i:s', $maximum_time);
				$color = ($max_percent<=30) ? "red" : "green";
				$color = ($max_percent>30 && $max_percent<=70) ? "orange" : $color;
				$percen_p = ($max_percent==0) ? "100" : intval($max_percent);
				$style_span = ($max_percent>9) ? ' style="font-size: 13px !important;line-height: 2.2em;width: 2.3em;"' : '';	
				$html .= '<th style="text-align:center"><div class="c100 p100 small '.$color.'"">
		                    <span '.$style_span.'>'.$show_all_max_score.'</span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                    </div>
	                	</div></th>
	                	<th style="text-align: center;">
	                	<div class="c100 p'.$percen_p.' small '.$color.'">
		                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;">'.ceil($max_percent).'<small style="font-size: 9px;">%</small></span>
		                    <div class="slice">
		                        <div class="bar"></div>
		                        <div class="fill"></div>
		                   	</div>
		                </div></th>
	                	<th style="text-align:center"><small>'.$show_all_max_time.'</small></th><th style="width:auto"></th></tr>';
			}
	        $html .= '</table></div>';
			$returnArray['success'] = true;
			$returnArray['html'] = $html;

			echo json_encode($returnArray);

		} else {

			redirect('404');

		}
	}

	public function analytics_by_question() {
		if(true) {
			$this->load->model('model_general');
			$qsId = $this->input->post('question_id');
			$worksheetId = $this->input->post('worksheet_id');
			$worksheetTitle = $this->input->post('worksheet_title');
			$groupId = $this->input->post('group_id');
			$arrayStudents = $this->input->post('filterStudent'); // "123"; //
			$selected_student = ($arrayStudents=="all") ? array() : explode(",",$arrayStudents);
        	$userId = $this->session->userdata('user_id');
			$ws = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);
			$questionsArray = $ws->result();
			$ques_options = "";
			$i = 1;
            foreach ($questionsArray as $question) {
                $active_filter = ($i==$qsId) ? " fa-check" : " width14";
                $ques_options .= '<a href="#" onclick="chooseFilter(\''.$i.'\',\'quest_number\')"><i class="q'.$i.' q-filter fa '.$active_filter.'"></i> Q'.$i.'</a>';
            	$i++; 
            }
			$students = $this->model_worksheet->get_quiz_list_from_worksheet_id($worksheetId);
			$getWsQues = $this->model_general->getDataWhere('sj_worksheet_questions'," where worksheet_id='".$worksheetId."' and question_number='".$qsId."' and branch_code='".BRANCH_ID."'");
			$queType = ($getWsQues) ? $getWsQues->question_type : 1;
			$queId = ($getWsQues) ? $getWsQues->question_id : 1;
			$quesDetail = $this->model_question->get_question_from_id($queId);
			$getAnswers = $this->model_general->getAllData('sj_answers','answer_id','asc'," where question_id='".$queId."' ");
			$queQueAnswer = $this->model_general->getData('sj_correct_answer','question_id',$queId);
			$answer_id = ($queQueAnswer) ? $queQueAnswer->answer_id : 1;
			if($queQueAnswer) {
				$solutionAnswerText = $this->model_question->get_solution_answer_text_from_correct_id($answer_id);
				$solutionAnswerType = $this->model_question->get_solution_answer_type_from_correct_id($answer_id);
			} else {
				$solutionAnswerText = ""; $solutionAnswerType = "text";
			}
			$solution_value = ($solutionAnswerText=="") ? "No solution" : $solutionAnswerText;
			$solution_value = ($solutionAnswerType=="image") ? '<img src="'.$quesDetail->branch_image_url.'/workingImage/'.$solutionAnswerText.'" class="img-responsive">' : $solution_value;
			$button_solution = '<a class="btn btn-ocr solution-answer btn-selectedAnswer text-white" data-toggle="modal" data-target="#solutionAnswerModal" data-id="solution" style="margin-left: 0;"><span data-toggle="tooltip" data-placement="top" title="Solution"><i class="fa fa-search"></i> Solution</span></a>
			<textarea style="display:none;" id="solution_value">'.$solution_value.'</textarea>';
			$max_card_column = (count($students)<=1) ? 1 : 3;
			$button_solution .= '<style type="text/css"> .card-columns { column-count: '.$max_card_column.'; }';
			$html = "";
			if(count($students)==0) {
				$html .= '<center>No students assigned</center>';
			} else {
				foreach ($students as $student) {
					if(!in_array($student->assignedTo, $selected_student) && $arrayStudents!="all") continue;
					$studentUserName = $this->model_users->get_username_from_id($student->assignedTo,"fullname");
					$quiz = $this->model_general->getDataWhere('sj_quiz'," where worksheetId='".$worksheetId."' and assignedTo='".$student->assignedTo."' and status='1' and branch_code='".BRANCH_ID."' ");
					if($quiz) {
						$worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','asc', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheetId." and qa.branch_code=".BRANCH_ID." and assignedTo='".$student->assignedTo."' ","qa.id,attemptScore");
						$x = 1;
                        foreach ($worksheetAttempt as $k => $value) {
							$attemptStudentAnswer = $this->model_general->getDataWhere('sj_quiz_attempt_answer'," where attemptId='".$value['id']."'  AND `questionNo` = ".$qsId." ");
							$icon_nav = (count($worksheetAttempt)==1) ? "display:none;" : "";
							$icon_prev_grey = ($x==1) ? "img_grey" : "";
							$icon_next_grey = ($x==count($worksheetAttempt)) ? "img_grey" : "";
							$show_card = ($x>1) ? "display:none !important;" : "";
							$prev_attempt_class = ($x==1) ? "" : "prev_attempt";
							$next_attempt_class = ($x==count($worksheetAttempt)) ? "" : "next_attempt";
							$prev_x = ($x - 1);
							$next_x = ($x + 1);
							$html .= '
								<div class="card card_'.$student->assignedTo.'" style="'.$show_card.'" id="card_'.$x.'-'.$student->assignedTo.'-'.$worksheetId.'-'.$qsId.'">
					                <div class="card-header-small">
					                    <h5 class="card-title card_question_title fs14" id="card_question_title_1">
					                        <i class="fa fa-user" style="font-size: 33px;float: left;margin-right: 10px;"></i> 
					                        <b>'.$studentUserName.'</b><br />
					                        '.$attemptStudentAnswer->score.' / '.$quesDetail->difficulty.' Score
					                    </h5>  
					                    <div class="btnExpand" id="attempt_info_'.$student->assignedTo.'" style="font-size: 13px;position: absolute;top: 4px;right: 10px;'.$icon_nav.'">
					                    Attempt '.$x.'  <br />
					                    <img src="'.base_url().'img/prev.png" width="25" class="'.$prev_attempt_class.' '.$icon_prev_grey.'" data-id="'.$prev_x.'-'.$student->assignedTo.'-'.$worksheetId.'-'.$qsId.'" data-student="'.$student->assignedTo.'" /> 
					                    <img src="'.base_url().'img/next.png" width="25" class="'.$next_attempt_class.' '.$icon_next_grey.'" data-id="'.$next_x.'-'.$student->assignedTo.'-'.$worksheetId.'-'.$qsId.'" data-student="'.$student->assignedTo.'" />  </div>    
					                </div>          
					                <div class="card-body-small fs14 card_question_text">   
					                    <div class="card_question_body">
					                        <div class="row pt-10" style="min-height: 120px;"> ';
					                    if($queType==1) { // MCQ 
							                foreach ($getAnswers as $akey => $av) {
							                	$checked = ($av['answer_id']==$answer_id) ? "checked" : "";
							                	$font_color = ($av['answer_id']==$answer_id) ? "#2ABB9B" : "#666666"; 
							                	$icon_fa = ($av['answer_id']==$answer_id) ? ' <i class="fa fa-check icon_fa"></i>' : '';
							                	if($checked=="" && $av['answer_id']==$attemptStudentAnswer->answerId) {
							                		$icon_fa = ' <i class="fa fa-times icon_false"></i>';
							                		$font_color = "red";
							                	}
							                	$html .= '
							                	<div class="col-lg-12">
					                                <div class="form-inline" style="color:'.$font_color.'">
					                                    <div class="customUi-radio radioUi-success mr-1">
					                                        <input type="checkbox" readonly="" '.$checked.'>
					                                        <label>
					                                            <span class="label-radio"></span>                                               
					                                        </label>
					                                    </div>
					                                    <span class="fs14 font300">'.$av['answer_text'].' '.$icon_fa.'</span>
					                                </div>
					                            </div>'; 
							                }
							            } else {
							            	$queAnswer = $this->model_general->getData('sj_answers','answer_id',$answer_id,"answer_text");
							            	$studentAnswer = $this->model_general->getData('sj_answers','answer_id',$attemptStudentAnswer->answerId,"answer_text");
							            	$student_answer_text = ($studentAnswer) ? $studentAnswer->answer_text : "<em>No answer</em>";
							            	$color_answer_text = ($studentAnswer) ? "#666666" : "red";
							            	$html .= '
							                	<div class="col-lg-12">
					                                <div class="form-inline" style="color:'.$color_answer_text.'">

					                                    <span class="fs14 font300">Your answers: '.$student_answer_text.'</span>
					                                </div>
					                                <div class="form-inline" style="color:#2ABB9B;padding-bottom:10px;">
					                                    <span class="fs14 font300">Correct answers: '.$queAnswer->answer_text.' <i class="fa fa-check icon_fa"></i></span>
					                                </div>
					                            </div>'; 
							            }
				                $digital_working = base_url().'onlinequiz/showSVG/' .$value['id']. '/' .$qsId;
				                $uploaded_video = ($attemptStudentAnswer) ? $attemptStudentAnswer->uploaded_video_explanation : '';
				                $uploaded_video_group = ($attemptStudentAnswer) ? $attemptStudentAnswer->uploaded_video_explanation_group : '';
				                $video = '<a id="videoEx_'.$student->assignedTo.'_'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" data-toggle="dropdown" class="btn btn-img videoEx btn-selectedAnswer btn-work dropdown" style="float: left;"><i class="fa fa-play"></i> Video</a>
									<ul class="dropdown-menu" style="top: 58px !important;left: -173px !important;">
										<li><b style="padding-left: 15px;">Upload Video</b></li>
										<li>
											<a style="cursor: pointer;" class="uploadVideoExplanation" data-id="'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" data-group="no"><i class="fa fa-upload mr-2"></i> Individual</a>
										</li>
										<li>
											<a style="cursor: pointer;" class="uploadVideoExplanation" data-id="'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" data-group="yes"><i class="fa fa-upload mr-2"></i> Group</a>
										</li>														
										<li class="divider"></li>
										<li><b style="padding-left: 15px;">Play Video</b></li>
										<li>
											<a style="cursor: pointer;" id="play_video_'.$value['id'].'_'.$qsId.'" data-href="'.$uploaded_video.'" class="play-video" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" data-toggle="modal" data-target="#showWorkings" data-type="video" ><i class="fa fa-play mr-2"></i> Individual</a>
										</li>
										<li>	<a style="cursor: pointer;" id="play_video_group_'.$value['id'].'_'.$qsId.'" data-href="'.$uploaded_video_group.'" class="play-video play-video-group" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" data-toggle="modal" data-target="#showWorkings" data-type="video" ><i class="fa fa-play mr-2"></i> Group</a>
										</li>													
									</ul>';
								$blank = '<div class="dropdown" style="display: inline">
									<button class="btn btn-img blankBg btn-selectedAnswer btn-work dropdown-toggle" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" 
										data-toggle="dropdown" id="blankBg_'.$value['id'].'_'.$qsId.'" style="float: left;"><i class="fa fa-file"></i> Blank</button>
									<ul class="dropdown-menu blank-dropdown" style="top: 58px !important;left: -173px !important;">														
										<li>
											<a style="cursor: pointer;" id="blank_indv_'.$value['id'].'_'.$qsId.'" class="blank-marking" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" data-toggle="modal" data-target="#showWorkings" data-type="blank"><i class="fa fa-file mr-2"></i> Individual</a>
										</li>
										<li class="divider"></li>
										<li>
											<a style="cursor: pointer;" id="blank_group_'.$value['id'].'_'.$qsId.'" class="blank-marking" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" data-toggle="modal" data-target="#showWorkings" data-type="blank-group"><i class="fa fa-file mr-2"></i> Group</a>
										</li>															
									</ul>
									</div>';
				                $html .= '	
				                		</div></div>
						                    <a id="showDigital_'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'" class="btn btn-img showDigital btn-selectedAnswer btn-work btn-active" style="float: left;"><i class="fa fa-pencil"></i> Digital</a> 
						                    <a id="imgUpload_'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" class="btn btn-img imgUpload btn-selectedAnswer btn-work" style="float: left;"><i class="fa fa-image"></i> Image</a>
						                    '.$blank.'
						                    '.$video.'
						                    <div style="clear: both;"></div>
						                    <div id="show_working_'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" data-student="'.$student->assignedTo.'"  class="show_working" data-toggle="modal" data-target="#showWorkings" data-type="digital" style="cursor: pointer;"><span class="helper"></span>
						                        <img src="'.$digital_working.'" onerror="this.style.display=\'none\'" />
						                    </div>
						                    <input type="hidden" id="ocr_svg_tutor_'.$value['id'].'_'.$qsId.'" value=\''.$attemptStudentAnswer->ocr_svg_tutor.'\' />
						                    <input type="hidden" id="ocr_svg_tutor_bg_'.$value['id'].'_'.$qsId.'" value=\''.$attemptStudentAnswer->ocr_svg_tutor_bg.'\' />
						                    <input type="hidden" class="group_ocr_blank" id="ocr_svg_tutor_bg_group_'.$value['id'].'_'.$qsId.'" value=\''.$attemptStudentAnswer->ocr_svg_tutor_bg_group.'\' />
						                    <input type="hidden" id="ocr_svg_tutor_img_'.$value['id'].'_'.$qsId.'" value=\''.$attemptStudentAnswer->ocr_svg_tutor_img.'\' />
						                    <input type="hidden" id="video_'.$value['id'].'_'.$qsId.'" value=\''.$attemptStudentAnswer->uploaded_video_explanation.'\' />
					                	</div>
						                <div class="card-footer text-muted text-right">
						                </div>
			            		</div>';
			            		$old_video = '
						                    <a id="blankBg_'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" class="btn btn-img blankBg btn-selectedAnswer btn-work" style="float: left;"><i class="fa fa-file"></i> Blank</a>
						                    <a id="videoEx_'.$value['id'].'_'.$qsId.'" data-attempt="'.$value['id'].'" data-qnum="'.$qsId.'" class="btn btn-img videoEx btn-selectedAnswer btn-work" style="float: left;"><i class="fa fa-play"></i> Video</a>';
                        	$x++;
                    	}
		            	if(!$worksheetAttempt) {
		            		$html .= '
		            		<div class="card">
				                <div class="card-header-small">
				                    <h5 class="card-title card_question_title fs14" id="card_question_title_1">
				                        <i class="fa fa-user" style="font-size: 33px;float: left;margin-right: 10px;"></i> 
				                        <b>'.$studentUserName.'</b><br />
				                        NR Score
				                    </h5>  
				                    <div class="btnExpand" data-id="34" style="font-size: 13px;position: absolute;top: 4px;right: 10px;">
				                    	No Attempt  <br /> 
				                    </div>    
				                </div> 
				                <div class="card-body-small fs14 card_question_text">   
				                    <div class="card_question_body">
				                        No Result
				                    </div>
				                </div> 
				                <div class="card-footer text-muted text-right">
				                </div>
		            		</div> ';
		            	}
	            	}
				}
			}
			$returnArray['success'] = true;
			$returnArray['ques_options'] = $ques_options;
			$returnArray['html'] = $html;
			$returnArray['ques_content'] = $button_solution;

			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}

	public function saveVideoExplanation(){
		$this->load->model('model_quiz');
		$post = $this->input->post();
		if($post['typeSave']=="individual") {
			$this->model_quiz->saveVideoExplanation();
	        $response['msg'] = 'success';
		} else {
			$this->model_quiz->saveVideoExplanationGroup();
			$response['msg'] = 'success';
		}
		$response['type'] = $post['typeSave'];
		$response['attemptId'] = $post['attemptId'];
		$response['questionNo'] = $post['questionNo'];

        echo json_encode($response);
	}

	// for tutor
	public function my_lessons(){		
		$userId = $this->session->userdata('user_id');
		$userRole = $this->session->userdata('user_role');

		// for testing purpose
		// $this->session->set_userdata('lessonId', 1); 

		$my_students = $this->model_users->get_student_list($this->session->userdata('user_id'));
		
		$data['my_students'] = $my_students;
		$data['lessons'] = $this->M_lesson->read_lessons();
		$data['page'] = "lessons";
        $data['content'] = "profile/tutor/view_lesson";
        $this->load->view("include/master_view.php", $data);
	}


	public function lessons(){		
		$userId = $this->session->userdata('user_id');
		$userRole = $this->session->userdata('user_role');

		// for testing purpose
		// $this->session->set_userdata('lessonId', 1); 

		$my_students = $this->model_users->get_student_list($this->session->userdata('user_id'));
		
		$data['my_students'] = $my_students;
		$data['lessons'] = $this->M_lesson->read_lessons();
		$data['page'] = "lessons";
        $data['content'] = "profile/tutor/my_lesson";
        $this->load->view("include/master_view.php", $data);
	}


	// for student
	public function mylessons(){		
		$userId = $this->session->userdata('user_id');
		$userRole = $this->session->userdata('user_role');		
		
		$data['lessons'] = $this->M_lesson->get_lesson_list_student($userId);		
		$data['page'] = "mylessons";
        $data['content'] = "profile/student/view_student_lesson";
        $this->load->view("include/master_view.php", $data);
	}

	public function updateStatistic(){
		$newdata = array(
			'subject_id'  => $this->input->get('subject_id')
		);
		$this->session->set_userdata($newdata);

		$result['msg'] = 'success';
		echo json_encode($result);		
	}


}