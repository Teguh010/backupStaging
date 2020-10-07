<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



defined('BASEPATH') OR exit('No direct script access allowed');



class Administrator extends CI_Controller {

	public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('is_admin_logged_in') !== 1) { 
	        redirect('site/admin');
	    }
	    $this->load->model('model_admin');
		$this->load->model('model_question');
		$this->load->model('M_question');
		$this->load->model('model_users');
		$this->load->model('model_classes');
		set_time_limit(500);
    }

    


	public function index() {		
		$this->load->library('pagination');
		
		$config = array ();
		$config['base_url'] = base_url() . 'administrator/index';
		$config['total_rows'] = $this->model_admin->counted_issue();
		$config['per_page'] =10;
		$config['uri_segment'] =3;
		//$config['use_page_numbers'] = TRUE;
		
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
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data['question_issues'] = $this->model_admin->get_question_issue($config['per_page'], $page);
		
		$data['links'] = $this->pagination->create_links();
		
		$feedbacks = $this->model_admin->get_feedback();

		$data['feedbacks'] = $feedbacks;

		$data['content'] = 'administrator/administrator_home';

		$this->load->view('include/master_view', $data);

	}

	public function question($subject_url) {

		switch ($subject_url) {
			case "primary-english":
				$subject_id = "1";
				break;
			case "primary-math":
				$subject_id = "2";
				break;	
			case "primary-science":
				$subject_id = "3";
				break;
			case "secondary-english":
				$subject_id = "4";
				break;
			case "secondary-math":
				$subject_id = "5";
				break;
			case "secondary-science":
				$subject_id = "6";
				break;
			}

		$question_counter = $this->uri->segment('4');

		$question_id = $this->model_question->get_question_id_from_counter($question_counter,$subject_id,1);

		$question_content = $this->model_question->get_question_content_from_id($question_id);

		if (isset($question_id) && empty($question_id) === false) {

			$post = $this->input->post();
			$question_detail = $this->model_question->get_question_from_id($question_id);
			$question_content = $this->model_question->get_question_content_from_id($question_id);
			
			if (isset($post) && empty($post) === false) {
				$response = $this->model_question->update_question($post, $question_id);
				if ($response['status'] === true) {
					
					$this->session->set_flashdata('update_success', 'Question created successfully');
					// $data['message'] = 'Question created successfully';
					// $data['message_status'] = 'alert-success';
					redirect(base_url().'administrator/question/' . $subject_url .'/' . $question_counter);
					

					$data['message_success'] = true;
					$data['message'] = 'Question successfully updated';
					redirect(base_url().'administrator/question/' . $subject_url .'/' . $question_counter);
				} else {

					$data['message_success'] = false;

					$data['message'] = 'Error updating question, please try again later';

					redirect(base_url().'administrator/question/' . $subject_url .'/' . $question_counter);
				}

			}
			
			if (!isset($question_detail) || empty($question_detail) === true) {

				redirect(base_url().'administrator');

			}

			$data['question_next'] = $this->model_admin->get_next_question_id($question_counter, $question_detail->subject_type, 1);

			$data['question_previous'] = $this->model_admin->get_previous_question_id($question_counter,  $question_detail->subject_type, 1);

			$categories = $this->model_admin->get_topic_list($question_detail->subject_type);

			$data['strategies'] = $this->model_question->get_strategy_list();

			$data['substrategies'] = $this->model_question->get_substrategy_list();

			$data['subjects'] = $this->model_question->get_subject_list();

			$levels = $this->model_question->get_level_list();

			$schools = $this->model_question->get_school_list($question_detail->school_id);

			$question_types = $this->model_question->get_all_question_type();

			$answer_types = $this->model_question->get_all_answer_type();

			$data['question_types'] = $question_types;

			$data['answer_types'] = $answer_types;

			$data['question_detail'] = $question_detail;
			
			$data['question_content'] = $question_content;

			$data['post_id'] = $question_id;

			$data['categories'] = $categories;

			$data['content'] = 'administrator/administrator_question';

			$data['levels'] = $levels;

			$data['schools'] = $schools;

			if ($question_detail->question_type_id == 1 || $question_detail->question_type_id == 4) {

				$data['answerOptions'] = $this->model_question->get_answer_option_from_question_id_no_randomize($question_id);
				
				$data['answerOptions_isImage'] = $this->model_question->get_answer_type_option_from_question_id($question_id);

				$data['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($question_id);
			} else {

				$data['open_ended_answer'] = $this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

				$data['open_ended_answer_type'] = $this->model_question->get_nmcq_answer_type_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));
			
				$data['open_ended_working'] = $this->model_question->get_working_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

				if($question_detail->question_content == 1){
					$data['open_ended_working_contents'] = $this->model_question->get_working_content_from_question_id($question_id);

					$data['working_index_count'] = $this->model_question->get_count_working_index($question_id);
				}

			}

			if (strlen($question_detail->sub_question) == 2) {
				$data['question_parent'] = $this->model_question->get_question_parent_id($question_id);
			} else {
				$data['question_parent'] = $data['question_detail'];
			}
			
			$data['question_status'] = $this->model_question->get_question_status($question_id); 

			$data['question_tags'] = $this->model_question->get_question_tags($question_id);

			$this->load->view('include/master_view', $data);
		} else {

			redirect('404');

		}

	}

	public function privateQuestion($subject_url) {

		switch ($subject_url) {
			case "primary-english":
				$subject_id = "1";
				break;
			case "primary-math":
				$subject_id = "2";
				break;	
			case "primary-science":
				$subject_id = "3";
				break;
			case "secondary-english":
				$subject_id = "4";
				break;
			case "secondary-math":
				$subject_id = "5";
				break;
			case "secondary-science":
				$subject_id = "6";
				break;
			}
		
		$question_counter = $this->uri->segment('4');
		
		// $question_counter =  $this->uri->segment('4');
		// Fetch question_id base on question_counter
		$question_id = $this->model_question->get_question_id_from_counter($question_counter,$subject_id,9);

		$question_content = $this->model_question->get_question_content_from_id($question_id);
		
		if (isset($question_id) && empty($question_id) === false) {
			
			$post = $this->input->post();

			$this->load->model('model_question');

			$question_detail = $this->model_question->get_question_from_id($question_id);

			// $question_detail = $this->model_question->get_question_detail_from_counter($question_counter);
			
			if (isset($post) && empty($post) === false) {

				$mcqNo = count(array_filter($post['mcq_answers'], function ($x){ return !empty($x); } ));
				// array_debug($mcqNo);
				// die();
				if($mcqNo != 4 && $question_detail->question_type_id == '1'){
					$data['message_success'] = false;

					$data['message'] = 'Missing fill in the MCQ answer option, please try again later';

				} else if(array_key_exists("mcq_correct_answer", $post) === false && $question_detail->question_type_id == '1') {
					$data['message_success'] = false;

					$data['message'] = 'Missing correct answer for MCQ answer option, please try again later';
				} else {

					$response = $this->model_question->update_question($post, $question_id);
					
					if ($response['status'] === true) {
						
						if ($_FILES['graphical']['size'] != 0) {
							$config = array();
							$config['upload_path'] = 'img/questionImage';
							$config['allowed_types'] = '*';
							$config['max_size']    = '300';
							$config['overwrite'] = 'true';
							$config['file_name'] = $question_detail->year . '-0-' . $question_detail->level_id . '-' . $question_id;
							$this->load->library('upload');
							$this->upload->initialize($config);

							$allowed_types = array('jpg', 'png', 'gif', 'jpeg');
							$file_name = $_FILES['graphical']['name'];
							$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

							if (in_array($ext, $allowed_types)) {
								if (!$this->upload->do_upload('graphical')){
									
									$data['message'] = $this->upload->display_errors();
									$data['message_status'] = 'alert-danger';
								} else {
									$upload_data = $this->upload->data();
									
									if ($this->model_question->update_question_image($upload_data['file_name'], $question_id)) {

										// $data['message'] = "Question created successfully and image uploaded successfully";
										// $data['message_status'] = 'alert-success';
										// $data['content'] = 'administrator/administrator_question';

										$this->session->set_flashdata('update_success', 'Question created successfully and image uploaded successfully');
										redirect(base_url() . 'administrator/privateQuestion/' . $subject_url .'/' . $question_counter);
									} else {
										// $data['message'] = "Error in uploading question image. Please try again later or contact administrator at admin@smartjen.sg";
										// $data['message_status'] = 'alert-danger';
										$this->session->set_flashdata('update_error', 'Error in uploading question image. Please try again later or contact administrator at hello@smartjen.com');
										redirect(base_url() . 'administrator/privateQuestion/' . $subject_url .'/' . $question_counter);
									}
								}
							} else {
								
								// $data['message'] = 'Please upload only jpg, png, gif or jpeg file';
								// $data['message_status'] = 'alert-danger';
								$this->session->set_flashdata('update_error', 'Please upload only jpg, png, gif or jpeg file');
								redirect(base_url() . 'administrator/privateQuestion/' . $subject_url .'/' . $question_counter);
							}
						} else {
							// $data['message'] = 'Question created successfully';
							// $data['message_status'] = 'alert-success';
							$this->session->set_flashdata('update_success', 'Question created successfully');
							redirect(base_url() . 'administrator/privateQuestion/' . $subject_url .'/' . $question_counter);
						}

						// $data['message_success'] = true;
						// $data['message'] = 'Question successfully updated';
						$this->session->set_flashdata('update_success', 'Question successfully updated');
						redirect(base_url() . 'administrator/privateQuestion/' . $subject_url .'/' . $question_counter);
					} else {
						// $data['message_success'] = false;
						// $data['message'] = 'Error updating question, please try again later';
						$this->session->set_flashdata('update_error', 'Error updating question, please try again later');
						redirect(base_url() . 'administrator/privateQuestion/' . $subject_url .'/' . $question_counter);
					}

				}

			}
			
			if (!isset($question_detail) || empty($question_detail) === true) {

				redirect(base_url().'administrator');

			}

			$data['subjects'] = $this->model_question->get_subject_list();

			$data['question_next'] = $this->model_admin->get_next_question_id($question_counter, $question_detail->subject_type, 9);

			$data['question_previous'] = $this->model_admin->get_previous_question_id($question_counter,  $question_detail->subject_type, 9);
			
			$categories = $this->model_admin->get_topic_list($question_detail->subject_type);

			$data['strategies'] = $this->model_question->get_strategy_list();

			$data['substrategies'] = $this->model_question->get_substrategy_list();

			$levels = $this->model_question->get_level_list();

			$schools = $this->model_question->get_school_list();

			$question_types = $this->model_question->get_all_question_type();

			$answer_types = $this->model_question->get_all_answer_type();

			$data['question_types'] = $question_types;

			$data['answer_types'] = $answer_types;

			$data['question_detail'] = $question_detail;

			$data['question_content'] = $question_content;

			// $data['post_id'] = $question_id;
			$data['post_id'] = $question_counter;

			$data['categories'] = $categories;

			$data['content'] = 'administrator/administrator_question';

			$data['levels'] = $levels;

			$data['schools'] = $schools;


			if ($question_detail->question_type_id == 1) {

				$data['answerOptions'] = $this->model_question->get_answer_option_from_question_id($question_id);

				$data['answerOptions_isImage'] = $this->model_question->get_answer_type_option_from_question_id($question_id);

				$data['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($question_id);
			} else if ($question_detail->question_type_id == 2) {

				$data['open_ended_answer'] = $this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

				$data['open_ended_answer_type'] = $this->model_question->get_nmcq_answer_type_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

				$data['open_ended_working'] = $this->model_question->get_working_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

				if($question_detail->question_content == 1){
					$data['open_ended_working_contents'] = $this->model_question->get_working_content_from_question_id($question_id);

					$data['working_index_count'] = $this->model_question->get_count_working_index($question_id);
				}

			}

			if (strlen($question_detail->sub_question) == 2) {
				$data['question_parent'] = $this->model_question->get_question_parent_id($question_id);
			} else {
				$data['question_parent'] = $data['question_detail'];
			}

			$data['question_status'] = $this->model_question->get_question_status($question_id);

			$data['question_tags'] = $this->model_question->get_question_tags($question_id);

			$this->load->view('include/master_view', $data);
		} else {

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

	public function get_level_list(){ 
		// POST data 
		$postData = $this->input->post();
		
		// get data 
		$data = $this->model_admin->get_level_list($postData);
		echo json_encode($data); 
	}

	public function get_topic_list(){ 
		// POST data 
		$postData = $this->input->post();
		
		// get data 
		$data = $this->model_admin->get_topic_name($postData);
		echo json_encode($data); 
	}

	public function get_substrategy_list(){ 
		// POST data 
		$postData = $this->input->post();

		$data = $this->model_admin->get_substrategy_list($postData);
		echo json_encode($data); 
	}

	
	public function update_question_category() {

		if ($this->input->is_ajax_request()) {

			$return_array = array();

			$this->load->model('model_question');

			$update_category = intval($this->input->post('category'));

			$question_id = intval($this->input->post('question_id'));

			if ($this->model_question->update_question_category($question_id, $update_category)) {

				$return_array['success'] = true;

				$return_array['message'] = 'Question category updated successfully.';

			} else {

				$return_array['success'] = false;

				$return_array['message'] = 'Error updating question category.';

			}



			echo json_encode($return_array);

		} else {

			redirect('404');

		}

	}


	public function mark_question_issue_resolved() {

		if ($this->input->is_ajax_request()) {

			$return_array = array();

			$this->load->model('model_admin');

			$issue_id = intval($this->input->post('issue_id'));

			if ($this->model_admin->mark_question_issue_resolved($issue_id, true)) {

				$return_array['success'] = true;

				$return_array['message'] = 'Issue marked as resolved';

			} else {

				$return_array['success'] = false;

				$return_array['message'] = 'Error updating issue status.';

			}



			echo json_encode($return_array);

		} else {

			redirect('404');

		}

	}


	public function mark_feedback_read() {

		if ($this->input->is_ajax_request()) {

			$return_array = array();

			$this->load->model('model_admin');

			$feedback_id = intval($this->input->post('feedback_id'));

			if ($this->model_admin->mark_feedback_read($feedback_id, true)) {

				$return_array['success'] = true;

				$return_array['message'] = 'Feedback marked as read';

			} else {

				$return_array['success'] = false;

				$return_array['message'] = 'Error updating feedback status.';

			}



			echo json_encode($return_array);

		} else {

			redirect('404');

		}

	}


	public function create_new_question() {

		$this->load->model('model_question');

		$post = $this->input->post();

		

		if (isset($post) && empty($post) === false) {

			$response = $this->model_question->create_new_question($post);

			if ($response['status']) {

				$data['message'] = 'Question created successfully';

				$data['message_status'] = 'alert-success';

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

		$data['question_level'] = 'public';

		$data['content'] = 'administrator/administrator_create_question';

		$data['subjects'] = $this->model_question->get_subject_list();

		$data['levels'] = $this->model_question->get_level_list();

		$data['categories'] = $this->model_question->get_topic_list();

		$data['schools'] = $this->model_question->get_school_list();

		$data['strategies'] = $this->model_question->get_strategy_list();

		$data['substrategies'] = $this->model_question->get_substrategy_list();

		$data['answer_types'] = $this->model_question->get_all_answer_type();

		$data['post_url'] = base_url() . 'administrator/administrator_create_question';

		$this->load->view('include/master_view', $data);

	}


	public function questions() {
		$data['page'] = 'questions';
		$data['content'] = 'administrator/list-question/main';		
		$this->load->view("include/master_view.php", $data);

	}


	public function getQuestions($page = 1){
		$start = (($page <= 0 ? 1 : $page) - 1) * 10;
		$getData = $this->M_question->read_question($start);
		
		foreach($getData['data'] as $row){
			$row->level_name = $this->M_question->get_level_by_id($row->level_id);
			$row->subject_name = $this->M_question->get_subject_by_id($row->subject_type);
			$row->topic_name = $this->M_question->get_topic_by_id($row->topic_id)['topic_name'];
			$row->substrand_name = $this->M_question->get_topic_by_id($row->topic_id)['substrand_name'];
			$row->strategy_name = $this->M_question->get_strategy_by_id($row->strategy_id);

			$row->has_instruction = $this->model_question->get_question_header_from_question_id($row->question_id, 'instruction');
			$row->has_article = $this->model_question->get_question_header_from_question_id($row->question_id, 'article');

			$row->total_question = $this->M_question->get_reference_by_id($row->question_id);
		}

		$answerList = $this->model_question->get_answer_list($getData['data']);
		$questionContents = $this->model_question->get_question_content_list($getData['data']);		
		$data['questionContents'] = $questionContents;
		$data['answerList'] = $answerList;

        $data['data'] = $getData['data'];
        if(isset($data['total_rows']) && !empty($data['total_rows'] == TRUE)) {
            $total_rows = $getData['total_rows'];
        } else {
            $total_rows = 0;
        }       
        // pagination settings      
        $this->load->library('pagination');
        
        $this->pgconfig = array(
            'first_link' => ' First ',
            'last_link' => ' Last ',
            'per_page' => 10,
            'first_url' => '1',
            'use_page_numbers' => true,
            'num_links' => 3
        );
        $data['total_rows'] = $getData['total_rows'];
        //$data['duration'] = $getData['duration'];
        $this->pgconfig['total_rows'] = $getData['total_rows'];
        $this->pgconfig['uri_segment'] = 3;
        $this->pgconfig['full_tag_open'] = '<ul class="pagination" id="pag-addMore">';
        $this->pgconfig['full_tag_close'] = '</ul>';
        $this->pgconfig['first_link'] = false;
        $this->pgconfig['last_link'] = false;
        $this->pgconfig['first_tag_open'] = '<li>';
        $this->pgconfig['first_tag_close'] = '</li>';
        $this->pgconfig['prev_link'] = '&laquo';
        $this->pgconfig['prev_tag_open'] = '<li class="prev">';
        $this->pgconfig['prev_tag_close'] = '</li>';
        $this->pgconfig['next_link'] = '&raquo';
        $this->pgconfig['next_tag_open'] = '<li>';
        $this->pgconfig['next_tag_close'] = '</li>';
        $this->pgconfig['last_tag_open'] = '<li>';
        $this->pgconfig['last_tag_close'] = '</li>';
        $this->pgconfig['cur_tag_open'] = '<li class="active"><a href="#">';
        $this->pgconfig['cur_tag_close'] = '</a></li>';
        $this->pgconfig['num_tag_open'] = '<li>';
        $this->pgconfig['num_tag_close'] = '</li>';
        $this->pagination->initialize($this->pgconfig);
		$data['pagination'] = $this->pagination->create_links();
		
        $this->load->view('administrator/list-question/data', $data); 
	}


	public function getQuestionDetail($question_id){
		$getData = $this->M_question->read_question_id($question_id);
		
		foreach($getData['data'] as $row){
			$row->level_name = $this->M_question->get_level_by_id($row->level_id);
			$row->subject_name = $this->M_question->get_subject_by_id($row->subject_type);
			$row->topic_name = $this->M_question->get_topic_by_id($row->topic_id)['topic_name'];
			$row->substrand_name = $this->M_question->get_topic_by_id($row->topic_id)['substrand_name'];
			$row->strategy_name = $this->M_question->get_strategy_by_id($row->strategy_id);

			$answerList = $this->model_question->get_answer_list_by_id($row->question_id);
		}
		
		$questionContents = $this->model_question->get_question_content_list($getData['data']);
		$questionInstructions = $this->model_question->get_question_header_list($getData['data'], 'instruction');
		$questionArticles = $this->model_question->get_question_header_list($getData['data'], 'article');
		$data['questionContents'] = $questionContents;
		$data['questionInstructions'] = $questionInstructions;
		$data['questionArticles'] = $questionArticles;
		$data['answerList'] = $answerList;

		$data['data'] = $getData['data'];
		
		echo json_encode($data);
	}


	public function create_new_question2() {		

		$data['schools'] = $this->model_question->get_school_list();
		$data['categories'] = $this->model_question->get_topic_list();
		$data['substrategies'] = $this->model_question->get_substrategy_list();

		$data['page'] = 'create-new-question';

		$this->load->view('administrator/create-new-question/main', $data);
	}


	public function create_question() {		
		if($this->M_question->create_question() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
	}

	public function update_question() {		
		if($this->M_question->update_question() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
	}


	// public function edit_question($question_id) {	
	// 	$data['page'] = 'edit-question';

	// 	$this->load->view('administrator/create-new-question/edit-question', $data);

	// }

	public function edit_question($subject_url) {	

		switch ($subject_url) {
			case "primary-english":
				$subject_id = "1";
				break;
			case "primary-math":
				$subject_id = "2";
				break;	
			case "primary-science":
				$subject_id = "3";
				break;
			case "secondary-english":
				$subject_id = "4";
				break;
			case "secondary-math":
				$subject_id = "5";
				break;
			case "secondary-science":
				$subject_id = "6";
				break;
			}

		$question_counter = $this->uri->segment('4');

		$question_id = $this->model_question->get_question_id_from_counter($question_counter,$subject_id,1);

		$question_detail = $this->model_question->get_question_from_id($question_id);

		$question_content = $this->model_question->get_question_content_from_id($question_id);

		$data['question_next'] = $this->model_admin->get_next_question_id($question_counter, $question_detail->subject_type, 1);

		$data['question_previous'] = $this->model_admin->get_previous_question_id($question_counter,  $question_detail->subject_type, 1);

		$categories = $this->model_admin->get_topic_list($question_detail->subject_type);

		$data['strategies'] = $this->model_question->get_strategy_list();

		$data['substrategies'] = $this->model_question->get_substrategy_list();

		$data['subjects'] = $this->model_question->get_subject_list();

		$levels = $this->model_question->get_level_list();

		$schools = $this->model_question->get_school_list($question_detail->school_id);

		$question_types = $this->model_question->get_all_question_type();

		$answer_types = $this->model_question->get_all_answer_type();

		$data['question_types'] = $question_types;

		$data['answer_types'] = $answer_types;

		$data['question_detail'] = $question_detail;
		
		$data['question_content'] = $question_content;

		$data['post_id'] = $question_id;

		$data['categories'] = $categories;

		$data['content'] = 'administrator/administrator_question';

		$data['levels'] = $levels;

		$data['schools'] = $schools;

		if ($question_detail->question_type_id == 1 || $question_detail->question_type_id == 4 || $question_detail->question_type_id == 6 ) {

			$data['answerOptions'] = $this->model_question->get_answer_option_from_question_id_no_randomize($question_id);

			$data['answerOptions_isImage'] = $this->model_question->get_answer_type_option_from_question_id($question_id);

			$data['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($question_id);

			$data['mcq_working'] = $this->model_question->get_working_text_from_answer_id($this->model_question->get_first_answer_id($question_id));

			if($question_detail->question_content == 1){
				$data['mcq_working_contents'] = $this->model_question->get_working_content_from_question_id($question_id);
			}

			$data['max_answer_group'] = $this->model_question->get_max_answer_group_from_question_id($question_id);
			
		} else {
			// open ended

			$data['open_ended_answer'] = $this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

			$data['open_ended_answer_type'] = $this->model_question->get_nmcq_answer_type_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));
		
			$data['open_ended_working'] = $this->model_question->get_working_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question_id));

			if($question_detail->question_content == 1){
				$data['open_ended_working_contents'] = $this->model_question->get_working_content_from_question_id($question_id);

				$data['working_index_count'] = $this->model_question->get_count_working_index($question_id);
			}

		}

		if (strlen($question_detail->sub_question) == 2) {
			$data['question_parent'] = $this->model_question->get_question_parent_id($question_id);
		} else {
			$data['question_parent'] = $data['question_detail'];
		}

		$data['question_instruction'] = $this->model_question->get_question_instruction_from_id($question_id);

		$data['question_article'] = $this->model_question->get_question_article_from_id($question_id);
		
		$data['question_status'] = $this->model_question->get_question_status($question_id); 

		$data['question_tags'] = $this->model_question->get_question_tags($question_id);

		$data['page'] = 'edit_question_main';

		$this->load->view('administrator/edit-question/edit_question_main', $data);

	}


	public function create_new_private_question() {

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

		$data['question_level'] = 'private';

		$data['content'] = 'administrator/administrator_create_question';

		$data['subjects'] = $this->model_question->get_subject_list();

		$data['levels'] = $this->model_question->get_level_list();

		$data['categories'] = $this->model_question->get_topic_list();

		$data['schools'] = $this->model_question->get_school_list();

		$data['strategies'] = $this->model_question->get_strategy_list();

		$data['substrategies'] = $this->model_question->get_substrategy_list();

		$data['answer_types'] = $this->model_question->get_all_answer_type();

		$data['post_url'] = base_url() . 'administrator/administrator_create_question';

		$this->load->view('include/master_view', $data);

	}


	public function latex()
	{
		$data['content'] = 'administrator/view_latex';
		$this->load->view('include/master_view', $data);
	}



	public function update_question_image() {
		if ($this->input->is_ajax_request()) {
			$return_array = array();
			$this->load->model('model_question');
			$update_image = intval($this->input->post('delete_image_value'));
			$question_id = intval($this->input->post('delete_question_id'));
			if ($this->model_question->update_question_image($update_image, $question_id)) {
				$return_array['success'] = true;
				$return_array['message'] = 'Question image delete successfully.';
			} else {
				$return_array['success'] = false;
				$return_array['message'] = 'Error deleting question image.';
			}
			echo json_encode($return_array);
		} else {
			redirect('404');
		}
	}
	
	/*
	*
	*
	* Function made to amend the question text
	* from sj_temp_question
	*
	*    ***Start***
	*
	*/
	
	public function amend(){
		$this->load->model('model_admin');
		$input_array = $this->model_admin->amend_question();
		$question = array();
		$ref_id = array();
		foreach ($input_array->result() as $row){
			$question[] = str_replace("\\","\\\\",$row->question_text_amendment);
			$ref_id[] = $row->reference_id;
		}
		$question = str_replace("'","\\'",$question);
		$update_array = $this->model_admin->update_question($question, $ref_id);
		
		if($update_array === TRUE){
			echo "Question update successfully.";
		}else {
			echo "Question update failure.";
		}
		
	}
	
	/*
	*
	*
	*
	*   ***End***
	*
	*
	*/

	public function list_private_question() {
		$this->load->library('pagination');
		$config = array ();
		$config['base_url'] = base_url() . 'administrator/list_private_question';
		$config['total_rows'] = $this->model_admin->count_private_question(NULL, NULL, NULL, NULL);
		$config['per_page'] =10;
		$config['uri_segment'] =3;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
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
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['question'] = $this->model_admin->get_private_question($config['per_page'], $page, NULL, NULL, NULL, NULL);
		$data['links'] = $this->pagination->create_links();
		$data['content'] = 'administrator/administrator_list_private_question';
		$data['subjects'] = $this->model_question->get_subject_list();
		// $data['levels'] = $this->model_question->get_level_list();
		$error_message = $this->session->flashdata('update_error');
		$data['message'] = $error_message;
		$this->load->view('include/master_view', $data);
	}
	
	public function list_public_question() {
		$this->load->model('model_admin');
		$this->load->library('pagination');
		$config = array ();
		$config['base_url'] = base_url() . 'administrator/list_public_question';
		$config['total_rows'] = $this->model_admin->count_public_question(NULL, NULL, NULL, NULL);
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
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
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['question'] = $this->model_admin->get_public_question($config['per_page'], $page, NULL, NULL, NULL, NULL);
		$data['links'] = $this->pagination->create_links();
		$data['content'] = 'administrator/administrator_list_public_question';
		$this->load->view('include/master_view', $data);
	}
	
	public function search_public_question() {
		
		$post = ($this->input->post('search'))? $this->input->post('search'): "NULL";
		$subject_id = ($this->input->post('subject_id'))? $this->input->post('subject_id'): "NULL";	
		$search_type = ($this->input->post('search_type'))? $this->input->post('search_type'): "NULL";
		$level_id = ($this->input->post('level_id'))? $this->input->post('level_id'): "NULL";

		$post = ($this->uri->segment(3)) ? urldecode($this->uri->segment(3)) : $post;	
		$search_type = ($this->uri->segment(4)) ? urldecode($this->uri->segment(4)) : $search_type;	
		$subject_id = ($this->uri->segment(5)) ? urldecode($this->uri->segment(5)) : $subject_id;
		$level_id = ($this->uri->segment(6)) ? urldecode($this->uri->segment(6)) : $level_id;		
		
		$this->load->library('pagination');	
		$config = array ();
		$config['base_url'] = base_url("administrator/search_public_question/$post/$search_type/$subject_id/$level_id");
		$config['total_rows'] = $this->model_admin->count_public_question($post, $search_type, $subject_id, $level_id);
		$config['per_page'] =10;
		$config['uri_segment'] =7;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
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
		$page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;
		$data['question'] = $this->model_admin->get_public_question($config['per_page'], $page, $post, $search_type, $subject_id, $level_id);
		$data['links'] = $this->pagination->create_links();
		$data['subjects'] = $this->model_question->get_subject_list();
		$data['content'] = 'administrator/administrator_list_public_question';
		$this->load->view('include/master_view', $data);
	}

	public function search_private_question() {
		
		$post = ($this->input->post('search'))? $this->input->post('search'): "NULL";
		$subject_id = ($this->input->post('subject_id'))? $this->input->post('subject_id'): "NULL";	
		$search_type = ($this->input->post('search_type'))? $this->input->post('search_type'): "NULL";
		$level_id = ($this->input->post('level_id'))? $this->input->post('level_id'): "NULL";

		$post = ($this->uri->segment(3)) ? urldecode($this->uri->segment(3)) : $post;	
		$search_type = ($this->uri->segment(4)) ? urldecode($this->uri->segment(4)) : $search_type;	
		$subject_id = ($this->uri->segment(5)) ? urldecode($this->uri->segment(5)) : $subject_id;
		$level_id = ($this->uri->segment(6)) ? urldecode($this->uri->segment(6)) : $level_id;

		$this->load->library('pagination');	
		$config = array ();
		$config['base_url'] = base_url("administrator/search_private_question/$post/$search_type/$subject_id/$level_id");
		$config['total_rows'] = $this->model_admin->count_private_question($post, $search_type, $subject_id, $level_id);
		$config['per_page'] =10;
		$config['uri_segment'] =7;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
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

		$data['subjects'] = $this->model_question->get_subject_list();
		$data['levels'] = $this->model_question->get_level_list();

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;
		$data['question'] = $this->model_admin->get_private_question($config['per_page'], $page, $post, $search_type, $subject_id, $level_id);
		$data['links'] = $this->pagination->create_links();
		$data['content'] = 'administrator/administrator_list_private_question';
		$this->load->view('include/master_view', $data);
	}
	
	public function upload_bulk(){
		$data['content'] = 'administrator/administrator_upload_public';
		$this->load->view('include/master_view', $data);
	}

	public function upload_public ()
	{
		$this->load->model('model_admin');
		$this->load->library('excel');
		$this->load->library('upload');
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path); 
			$sheet = $object->getSheet(0);
			$highestRow = $sheet->getHighestRow(); // highest row is 6
			$highestColumn = $sheet->getHighestColumn(); // highest column is E
			$col = 1; // start index is 0, which means this start at column B
			for($row=2; $row<=$highestRow; $row++)
			{
				$disabled = $sheet->getCellByColumnAndRow(1, $row)->getValue();
				$ref_id = $sheet->getCellByColumnAndRow(2, $row)->getValue();
				$sub_ques = $sheet->getCellByColumnAndRow(3, $row)->getValue();
				$customize = $sheet->getCellByColumnAndRow(4, $row)->getValue();
				$source = $sheet->getCellByColumnAndRow(5, $row)->getValue();
				$question_text = trim($sheet->getCellByColumnAndRow(6, $row)->getValue());
				// $question_text = addslashes($question_text); 
				$school_id = $sheet->getCellByColumnAndRow(7, $row)->getValue();
				$casa = $sheet->getCellByColumnAndRow(8, $row)->getValue();
				$level_id = $sheet->getCellByColumnAndRow(9, $row)->getValue();
				$topic_id = $sheet->getCellByColumnAndRow(10, $row)->getValue();
				$topic_id2 = $sheet->getCellByColumnAndRow(11, $row)->getValue();
				$topic_id3 = $sheet->getCellByColumnAndRow(12, $row)->getValue();
				$key_topic = $sheet->getCellByColumnAndRow(13, $row)->getValue();
				$key_strategy = $sheet->getCellByColumnAndRow(14, $row)->getValue();
				$substrategy_id = $sheet->getCellByColumnAndRow(15, $row)->getValue();
				$substrategy_id2 = $sheet->getCellByColumnAndRow(16, $row)->getValue();
				$substrategy_id3 = $sheet->getCellByColumnAndRow(17, $row)->getValue();
				$substrategy_id4 = $sheet->getCellByColumnAndRow(18, $row)->getValue();
				$strategy_id = $sheet->getCellByColumnAndRow(19, $row)->getValue();
				$strategy_id2 = $sheet->getCellByColumnAndRow(20, $row)->getValue();
				$strategy_id3 = $sheet->getCellByColumnAndRow(21, $row)->getValue();
				$strategy_id4 = $sheet->getCellByColumnAndRow(22, $row)->getValue();
				$ques_type_id = $sheet->getCellByColumnAndRow(23, $row)->getValue();
				$ques_type_ref_id = $sheet->getCellByColumnAndRow(24, $row)->getValue();
				$year = $sheet->getCellByColumnAndRow(25, $row)->getValue();
				$difficulty = $sheet->getCellByColumnAndRow(27, $row)->getValue();
				$graphical = $sheet->getCellByColumnAndRow(28, $row)->getValue();
				$ans_type_id = $sheet->getCellByColumnAndRow(29, $row)->getValue();
				$branch_name = $sheet->getCellByColumnAndRow(30, $row)->getValue();
				$subject_type = $sheet->getCellByColumnAndRow(32, $row)->getValue();
				$answer_text_one = trim($sheet->getCellByColumnAndRow(34, $row)->getValue());
				//$answer_text_one = addslashes($answer_text_one);
				$answer_text_two = trim($sheet->getCellByColumnAndRow(35, $row)->getValue());
				//$answer_text_two = addslashes($answer_text_two);
				$answer_text_three = trim($sheet->getCellByColumnAndRow(36, $row)->getValue());
				//$answer_text_three = addslashes($answer_text_three);
				$answer_text_four = trim($sheet->getCellByColumnAndRow(37, $row)->getValue());
				//$answer_text_four = addslashes($answer_text_four);
				$correct_answer = trim($sheet->getCellByColumnAndRow(38, $row)->getValue());
				//$correct_answer = addslashes($correct_answer); 
				$data [] = array(
					'disabled'  => $disabled,
					'reference_id'   => $ref_id,
					'sub_question'    => $sub_ques,
					'customization' => $customize,
					'source'  => 0,
					'question_text'   => $question_text,
					'school_id'   => $school_id,
					'CASA'   => $casa,
					'level_id'  => $level_id,
					'topic_id'  => $topic_id,
					'topic_id2'  => $topic_id2,
					'topic_id3'  => $topic_id3,
					'key_topic' => $key_topic,
					'key_strategy' => $key_strategy,
					'substrategy_id' => $substrategy_id,
					'substrategy_id2' => $substrategy_id2,
					'substrategy_id3' => $substrategy_id3,
					'substrategy_id4' => $substrategy_id4,
					'strategy_id' => $strategy_id,
					'strategy_id2' => $strategy_id2,
					'strategy_id3' => $strategy_id3,
					'strategy_id4' => $strategy_id4,
					'question_type_id'  => $ques_type_id,
					'question_type_reference_id'  => $ques_type_ref_id,
					'year'  => $year,
					'difficulty'  => $difficulty,
					'graphical'  => $graphical,
					'answer_type_id'  => $ans_type_id,
					'branch_name' => $branch_name,
					'subject_type' => $subject_type,
					'answer_text_one'  => $answer_text_one,
					'answer_text_two'   => $answer_text_two,
					'answer_text_three'    => $answer_text_three,
					'answer_text_four'  => $answer_text_four,
					'correct_answer'  => $correct_answer
				);
			}
			$this->model_admin->upload_question_bulk_public($data);
			$data['message'] = "Question uploaded successfully";
			$data['message_status'] = 'alert-success';
		}

		if(isset($_POST['text_box'])){
			$foldername = $_POST['text_box'];
			$structure = 'img/'.$foldername;
			
			if (!mkdir($structure, 0777, true)) {
				die('Failed to create folders...');
			}
		}
		
		$data['content'] = 'administrator/administrator_upload_public';
		$this->load->view('include/master_view', $data);
	}

	private function set_upload_options()
	{   
		//upload an image options
		$config = array();
		$config['upload_path'] = 'img/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;

		return $config;
	}
	
	public function list_temp_user() {
		$this->load->model('model_admin');
		$this->load->library('pagination');
		$config = array ();
		$config['base_url'] = base_url() . 'administrator/list_temp_user';
		$config['total_rows'] = $this->model_admin->count_temp_user();
		$config['per_page'] =10;
		$config['uri_segment'] =3;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
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
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['user'] = $this->model_admin->get_temp_user($config['per_page'], $page);
		$data['links'] = $this->pagination->create_links();
		$data['content'] = 'administrator/administrator_temp_user';
		$this->load->view('include/master_view', $data);
	}
	
	public function add_temp_user(){
		
		if ($this->input->is_ajax_request()) {
			$return_array = array();
			$this->load->model('model_admin');
			$user_key = $this->input->post('user_key');
			$user_pass = $this->input->post('user_pass');
			if ($this->model_admin->add_temp_user($user_key, $user_pass)) {
				$return_array['success'] = true;
				$return_array['message'] = 'User is approved and can be access to SmartJen.';
			} else {
				$return_array['success'] = false;
				$return_array['message'] = 'Error in approving the temporary user.';
			}
			echo json_encode($return_array);
		} else {
			redirect('404');
		}
	}
	
	public function add_user(){
		$this->load->model('model_question');
		$level = $this->model_question->get_level_list();
		$school = $this->model_question->get_school_list();
		$data['levels'] = $level;
		$data['schools'] = $school;
		$data['content'] = 'administrator/administrator_add_user';
		$this->load->view('include/master_view', $data);
	}

	// parent email template
	private function parent_template($email, $key) {
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "SmartJen");
		$this->email->subject('SmartJen Account Activation');
		$this->email->to($email);
		$message = "<p>Dear Parent,</p>";
		$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
		$encode_pemail = urlencode(base64_encode($email));
		if(isset($tutorId) && !empty($tutorId)) {
			
			$message .= "<p>Please <a href='".base_url()."site/register_temp_parent/$encode_pemail/$key'>click</a> here to activate your account.</p>";
		} else {

			$message .= "<p>Please <a href='".base_url()."site/register_temp_parent/$encode_pemail/$key'>click</a> here to activate your account.</p>";
		}
		$message .= "<p>Your Sincerely,";
		$message .= "<br>Powered by SmartJen</p>";
		$this->email->message($message);
		return $this->email;
	}

	// new parent email template
	private function new_parent_template($pemail, $parkey, $email = NULL, $school_id = NULL, $level_id = NULL, $key = NULL, $tutorId = NULL) {
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "SmartJen");
		
		$encode_pemail = urlencode(base64_encode($pemail));
		if(isset($key) && $key != "") {
			$this->email->subject('SmartJen Parent Account Activation');
			$this->email->to($pemail);
			$message = "<p>Dear Parent,</p>";
			$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
			$message .= "<p>Please <a href='".base_url()."site/register_new_parent/$encode_pemail/$parkey/$school_id/$level_id/$key'>click</a> here to activate your parent and student accounts.</p>";
		} else {
			$this->email->subject('SmartJen Parent Account Activation');
			$this->email->to($pemail);
			$message = "<p>Dear Parent,</p>";
			$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
			$message .= "<p>Please <a href='".base_url()."site/register_new_parent/$encode_pemail/$parkey'>click</a> here to activate your account.</p>";
		}
		
		$message .= "<p>Your Sincerely,";
		$message .= "<br>Powered by SmartJen</p>";
		$this->email->message($message);
		return $this->email;
	}

	// only parent template
	private function exist_parent_template($pemail, $parkey, $email = NULL, $school_id = NULL, $level_id = NULL, $key = NULL, $tutorId = NULL) {
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "SmartJen");
		
		if(isset($key) && !empty($key)) {
			$this->email->subject('SmartJen Parent Account Activation');
			$this->email->to($pemail);
			$message = "<p>Dear Parent,</p>";
			$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
			$encode_pemail = urlencode(base64_encode($pemail));
			if(isset($tutorId) && !empty($tutorId)) {
				$message .= "<p>Please <a href='".base_url()."site/register_exist_parent/$encode_pemail/$parkey/$school_id/$level_id/$key/$tutorId'>click</a> here to activate your parent and student accounts.</p>";
			} else {
				$message .= "<p>Please <a href='".base_url()."site/register_exist_parent/$encode_pemail/$parkey/$school_id/$level_id/$key'>click</a> here to activate your parent and student account.</p>";
			}
		} else {
			$this->email->subject('SmartJen Parent Account Activation');
			$this->email->to($pemail);
			$message = "<p>Dear Parent,</p>";
			$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";
			$encode_pemail = urlencode(base64_encode($pemail));
			$message .= "<p>Please <a href='".base_url()."site/register_exist_parent/$encode_pemail/$parkey'>click</a> here to activate your account.</p>";
		}
		$message .= "<p>Your Sincerely,";
		$message .= "<br>Powered by SmartJen</p>";

		$this->email->message($message);
		return $this->email;
	}

	// student email template
	private function student_template($pemail, $email, $tutorId = null, $school_id, $level_id, $key) {
		$pemail = urlencode(base64_encode($pemail));
		$this->load->library('email');
		$this->email->from('noreply@smartjen.com', "SmartJen");
		$this->email->subject('SmartJen Student Account Activation');
		$this->email->to($email);
		$message = "<p>Dear Student,</p>";

		$message .= "<p>Thank you for signing up! You are one step away to have full access of SmartJen features.</p>";

		if(isset($tutorId) && !empty($tutorId)) {
			
			$message .= "<p>Please <a href='".base_url()."site/register_temp_student/$pemail/$school_id/$level_id/$key/$tutorId'>click</a> here to activate your account.</p>";
		} else {

			$message .= "<p>Please <a href='".base_url()."site/register_temp_student/$pemail/$school_id/$level_id/$key'>click</a> here to activate your account.</p>";
		}
		
		$message .= "<p>Your Sincerely,";
		$message .= "<br>Powered by SmartJen</p>";

		$this->email->message($message);

		return $this->email;
	}

	public function add_user_process()
	{
		$this->load->model('model_admin');

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

		$this->session->set_userdata('admin_registration', $post);
		
		$email = $post['register_email'];
		
		$mobile = $post['register_mobile'];

		$username = $post['register_username'];

		$pemail = $post['register_parent_email'];
		
		$tutorId = isset($post['tutorId'])? urlencode(base64_encode($post['tutorId'])) : '';

		$account_type = $this->input->post('register_type_btn');
		
		$key = sha1(uniqid('jen'));

		$par_key = sha1(uniqid('jen'));

		if ($this->form_validation->run()) 
		{
			
			if($account_type == 'tutor') {
				
				//send and email to the user
				
				$this->email->from('noreply@smartjen.com', "SmartJen");
				
				$this->email->to($email);
				
				$this->email->subject('SmartJen Tutor Account Activation');
				
				$message = "<p>Dear " . $this->input->post('register_fullName') . ", </p>";
				
				$message .= "<p>Thank you for signing up! You are one step away to have full access of all features.</p>";

				$message .= "<p>Please <a href='".base_url()."site/register_user/$key'>click</a> here to activate your account</p>";

				$message .= "<p>Your Sincerely,";

				$message .= "<br>Powered by SmartJen</p>";

				$this->email->message($message);

				if ($this->model_admin->add_temp_users($key)) 
				{

					if ($this->email->send()) 
					{
						$message = $this->session->set_flashdata('register_success', 'An activation email has been sent to ' . $email . '. Please activate the account for verification purpose.');
						redirect(base_url() . 'administrator/add_user');

					} else {
						$message = $this->session->set_flashdata('register_error', 'Error in sending out activation email to ' . $email . '. Please try again later or contact administrator.');
						redirect(base_url() . 'administrator/add_user');

					}

				} else {
					
					$message = $this->session->set_flashdata('register_error', 'Error in registering a tutor account. Please try again later or contact administrator.');
					redirect(base_url() . 'administrator/add_user');

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
								$this->exist_parent_template($pemail, $par_key);

								$this->email->send();

								// return student email template
								$this->student_template($pemail, $email, $tutorId, $school_id, $level_id, $key, $tutorId);

								if($this->model_admin->add_temp_student($par_key, $key)) {

									if($this->email->send()) {

										$message = $this->session->set_flashdata('register_success', 'The activation emails have been sent to both ' . $pemail . ' and ' . $email . ' . Please activate the accounts for verification purpose.');
										
										redirect(base_url() . 'administrator/add_user');
									} else {
										$message = $this->session->set_flashdata('register_error', 'Failed to sent out the activation email to both ' . $pemail . ' and ' . $email . '.Please try again later or contact administrator.');
									
										redirect(base_url() . 'administrator/add_user');
									}
								} else {
									$message = $this->session->set_flashdata('register_error', 'Failed to add student.');

									redirect(base_url() . 'administrator/add_user');
								}
							} else {

								//register with only parent email

								// return parent email template
								$this->exist_parent_template($pemail, $par_key, $email, $school_id, $level_id, $key, $tutorId);

								if($this->model_admin->add_temp_student($par_key ,$key)) {

									if($this->email->send()) {

										$message = $this->session->set_flashdata('register_success', 'An activation email has been sent to '.$pemail.' . Please activate the account for verification purpose.');
										
										redirect(base_url() . 'administrator/add_user');
									} else {
										$message = $this->session->set_flashdata('register_error', 'Failed to sent out activation email to '.$pemail.' . Please contact hello@smartjen.com for support.');
									
										redirect(base_url() . 'administrator/add_user');
									}
								} else {
									$message = $this->session->set_flashdata('register_error', 'Failed to add student.');

									redirect(base_url() . 'administrator/add_user');
								}
							}

						} else {
							$message = $this->session->set_flashdata('register_error', 'Parent email ' . $pemail . ' has reached maximum children number of 3. Please contact hello@smartjen.com for support.');
								
							redirect(base_url() . 'administrator/add_user');
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
							if($this->model_admin->add_temp_student($par_key, $key)) {

								if($this->email->send()) {

									$message = $this->session->set_flashdata('register_success', 'The activation emails have been sent to both ' . $pemail . ' and ' . $email . ' . Please activate the accounts for verification purpose.');
									
									redirect(base_url() . 'administrator/add_user');
								} else {
									$message = $this->session->set_flashdata('register_error', 'Failed to sent out activation email to student. Please contact hello@smartjen.com for support.');
								
									redirect(base_url() . 'administrator/add_user');
								}
							} else {
								$message = $this->session->set_flashdata('register_error', 'Parent email ' . $pemail . ' has reached maximum children number of 3. Please contact hello@smartjen.com for support.');
									
								redirect(base_url() . 'administrator/add_user');
							}

						} else {

							// register with only parent email
							// return parent email template

							$this->new_parent_template($pemail, $par_key, $email, $school_id, $level_id, $key);

							if($this->model_admin->add_temp_student($par_key ,$key)) {

								if($this->email->send()) {

									$message = $this->session->set_flashdata('register_success', 'An activation email has been sent to '.$pemail.' . Please activate the account for verification purpose.');
									
									redirect(base_url() . 'administrator/add_user');
								} else {

									$message = $this->session->set_flashdata('register_error', 'Failed to sent out activation email to student. Please contact hello@smartjen.com for support.');
								
									redirect(base_url() . 'administrator/add_user');
								}
							} else {
								$message = $this->session->set_flashdata('register_error', 'Parent email ' . $pemail . ' has reached maximum children number of 3. Please contact hello@smartjen.com for support.');
									
								redirect(base_url() . 'administrator/add_user');
							}
						}

					}

				} else {

					$message = $this->session->set_flashdata('register_error', 'Parent email field is compulsory.');
					redirect(base_url() . 'administrator/add_user');
				}

			}
			$this->session->unset_userdata('admin_registration');
		}
		else
		{
			$message = $this->session->set_flashdata('register_error', validation_errors());
			
			// redirect($_SERVER['HTTP_REFERER']);
			redirect(base_url() . 'administrator/add_user');
			
		}
	}
	
	public function _validate_email()
	{
		$this->load->model('model_users');
		
		if ($this->model_users->validate_email()) {
			return true;
		} else {
			$this->form_validation->set_message('_validate_email', 'The email is already taken. Please register with another email.');
			return false;
		}
	}
	
	public function _validate_username()
	{
		$this->load->model('model_users');
		
		if ($this->model_users->validate_username()) {
			return true;
		} else {
			$this->form_validation->set_message('_validate_username', 'Existing username cannot be used to register.');
			return false;
		}
	}
	
	public function tutor_list($grid = NULL) {
		if(empty($grid) == true) {
			$profile_message_success = $this->session->userdata('profileMessageSuccess');
			$profile_message = $this->session->userdata('profileMessage');
			if (isset($profile_message_success)) {
				$data['profile_message_success'] = ($profile_message_success == 0)?false:true;
				$data['profile_message'] = $profile_message;
				$this->session->unset_userdata('profileMessageSuccess');
				$this->session->unset_userdata('profileMessage');
			}
	
			$data['user'] = $this->model_admin->get_branch_tutor();
			$data['page'] = 'student-list';
			$data['content'] = 'administrator/administrator_tutor_list';
		} else {
			$data['page'] = 'tutor_list';
			$data['content'] = 'administrator/administrator_tutor_grid';
		}
		$this->load->view('include/master_view', $data);
	}
	
	// public function tutor_status()
	// {
	// 	$inactive_user_id = trim($this->input->post('inactive_tutor_id'));
	// 	$inactive_user_status = trim($this->input->post('inactive_tutor_status'));
		
	// 	$active_user_id = trim($this->input->post('active_tutor_id'));
	// 	$active_user_status = trim($this->input->post('active_tutor_status'));
		
	// 	if(isset($inactive_user_id) && !empty($inactive_user_id)){
	// 		$success = $this->model_admin->update_tutor_status($inactive_user_id, $inactive_user_status);
	// 		if ($success) {
	// 			$this->session->set_userdata('profileMessageSuccess', true);
	// 			$this->session->set_userdata('profileMessage', 'You have successfully deactivate the tutor');
	// 		}  else {
	// 			$this->session->set_userdata('profileMessageSuccess', 0);
	// 			$this->session->set_userdata('profileMessage', 'Failure in deactivate the tutor.Please try again later.');
	// 		}
	// 	} else
	// 	if(isset($active_user_id) && !empty($active_user_id)){
	// 		$success = $this->model_admin->update_tutor_status($active_user_id, $active_user_status);
	// 		if ($success) {
	// 			$this->session->set_userdata('profileMessageSuccess', true);
	// 			$this->session->set_userdata('profileMessage', 'You have successfully reactivate the tutor');
	// 		}  else {
	// 			$this->session->set_userdata('profileMessageSuccess', 0);
	// 			$this->session->set_userdata('profileMessage', 'Failure in deactivate the tutor.Please try again later.');
	// 		}
	// 	} else {
	// 		$this->session->set_userdata('profileMessageSuccess', 0);
	// 		$this->session->set_userdata('profileMessage', 'The tutor is not registered in the system.');
	// 	}
		
	// 	redirect(base_url().'administrator/tutor_list');
		
	// }
	
	public function add_branch_tutor(){
		$email = $this->input->post('add_branch_tutor');
		$success = $this->model_admin->add_branch_tutor($email);
		if($success){
			$this->session->set_userdata('profileMessageSuccess', true);
			$this->session->set_userdata('profileMessage', 'You have successfully added the tutor in the listing.');
		} else {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Failure in adding the tutor in the listing.Please try again later.');
		}
		redirect(base_url().'administrator/tutor_list');
	}

	public function import_gc($view="list") {
		if(isset($_POST['import'])) {
			// array_debug($_POST['check_class']);
			$class_selected = (isset($_POST['check_class'])) ? $_POST['check_class'] : array();
			foreach($class_selected as $class) {
				$class_name = $_POST['class_name_'.$class];
				$class_code = $_POST['class_code_'.$class];
				$students = (isset($_POST['class_'.$class.'_student'])) ? $_POST['class_'.$class.'_student'] : array();
				$teachers = (isset($_POST['class_'.$class.'_tutor'])) ? $_POST['class_'.$class.'_tutor'] : array();
				$check_class = $this->model_classes->get_class_gc($class);
				if(!$check_class) {
					$dat = array(
						'class_name' => $class_name,
						'subject_id' => 1,
						'level_id' => 1,
						'created_date' => date('Y-m-d H:i:s'),
						'created_by' => $this->session->userdata('admin_id'),
						'branch' => 9,
						'class_code' => $class_code,
						'google_classroom_id' => $class
					);
					$this->db->insert('sj_class',$dat);
					$id_class_sj = $this->db->insert_id();
				} else $id_class_sj = $check_class->class_id;
				
				// array_debug($dat);
				$class_id = $id_class_sj;
				foreach($teachers as $teacher) {
					$teacher_email = $_POST['class_'.$class.'_tutor_email_'.$teacher];
					$teacher_name = $_POST['class_'.$class.'_tutor_name_'.$teacher];
					$check_tutor = $this->model_users->get_user_id_from_email_or_username($teacher_email);
					if($check_tutor!=0) { // If email already in SJ
						$userId = $check_tutor;
					} else {
						// Insert into sj_user
						$username = explode("@", $teacher_email);
						$userData = array(
							'email' => $teacher_email,
							'fullname' => $teacher_name,
							'password' => hash('sha256', "SJ1234"),
							'username' => $username[0],
							'last_login' => 'tutor',
							'registered_date' => date('Y-m-d H:i:s'),
							'status' => 1,
							'branch_code' => 9
						);
						$this->db->insert('sj_users',$userData);
						// get userID
						$userId = $this->db->insert_id();
						// Insert into sj_branch_user
						$userData = array(
							'email' => $teacher_email,
							'user_id' => $userId,
							'account_type' => 'tutor',
							'registered_date' => date('Y-m-d H:i:s'),
							'status' => 1,
							'branch_id' => 9
						);
						$this->db->insert('sj_branch_user',$userData);
						// Insert into sj_branch_user
						$userData = array(
							'user_id' => $userId,
							'branch_id' => 9
						);
						$this->db->insert('sj_user_branch',$userData);
						// Insert into sj_user_role
						$userData = array(
							'user_id' => $userId,
							'role_id' => 1,
							'branch_code' => 9
						);
						$this->db->insert('sj_user_role',$userData);
						// Insert into sj_user_level
						$userData = array(
							'user_id' => $userId,
							'status' => 3,
							'branch_code' => 9
						);
						$this->db->insert('sj_user_level',$userData);

					}
					$tutor_id = $userId;
					$type = 'tutor';
					$sql = "
						SELECT * FROM `sj_class_relation`
						WHERE `class_id` = " . $this->db->escape($class_id) . "
						AND `user_id` = '".$tutor_id."'
					";
					$query = $this->db->query($sql);
					if($query->num_rows() <= 0) {
						$data = array(
							'class_id' => $class_id,
							'user_id' => $tutor_id,
							'type' => $type,
							'status' => 1
						);
						$this->db->insert('sj_class_relation',$data);
					}
				}
				$class_id = $id_class_sj;
				foreach($students as $student) {
					$student_email = $_POST['class_'.$class.'_student_email_'.$student];
					$student_name = $_POST['class_'.$class.'_student_name_'.$student];
					$check_student = $this->model_users->get_user_id_from_email_or_username($student_email);
					if($check_student!=0) { // If email already in SJ
						$userId = $check_student;
					} else {
						// Insert into sj_user
						$username = explode("@", $student_email);
						$userData = array(
							'email' => $student_email,
							'fullname' => $student_name,
							'password' => hash('sha256', "SJ1234"),
							'username' => $username[0],
							'last_login' => 'student',
							'registered_date' => date('Y-m-d H:i:s'),
							'status' => 1,
							'branch_code' => 9

						);
						$this->db->insert('sj_users',$userData);
						// get userID
						$userId = $this->db->insert_id();
						// Insert into sj_branch_user
						$userData = array(
							'email' => $student_email,
							'user_id' => $userId,
							'account_type' => 'tutor',
							'registered_date' => date('Y-m-d H:i:s'),
							'status' => 1,
							'branch_id' => 9
						);
						$this->db->insert('sj_branch_user',$userData);
						// Insert into sj_user_branch
						$userData = array(
							'user_id' => $userId,
							'branch_id' => 9
						);
						$this->db->insert('sj_user_branch',$userData);
						// Insert into sj_student
						$userData = array(
							'student_id' => $userId,
							'school_id' => 17,
							'level_id' => 6
						);
						$this->db->insert('sj_student',$userData);
						// Insert into sj_user_role
						$userData = array(
							'user_id' => $userId,
							'role_id' => 2,
							'branch_code' => 9
						);
						$this->db->insert('sj_user_role',$userData);
						// Insert into sj_user_level
						$userData = array(
							'user_id' => $userId,
							'status' => 3,
							'branch_code' => 9
						);
						$this->db->insert('sj_user_level',$userData);
					}
					$student_id = $userId;
					$type = 'student';
					$sql = "
						SELECT * FROM `sj_class_relation`
						WHERE `class_id` = " . $this->db->escape($class_id) . "
						AND `user_id` = '".$student_id."'
					";
					$query = $this->db->query($sql);
					if($query->num_rows() <= 0) {
						$data = array(
							'class_id' => $class_id,
							'user_id' => $student_id,
							'type' => $type,
							'status' => 1
						);
						$this->db->insert('sj_class_relation',$data);
					}
					$sql = "
						SELECT * FROM `sj_class_relation`
						WHERE `class_id` = " . $this->db->escape($class_id) . "
						AND `type` = 'tutor'
					";
					$query = $this->db->query($sql);
					
					if($query->num_rows() > 0) {
						
						foreach($query->result() as $value) {
							$sql = "
								SELECT * FROM `sj_relationship`
								WHERE `adult_id` = " . $this->db->escape($value->user_id) . "
								AND `student_id` =" . $this->db->escape($student_id) . "
								AND `status` = 1
								AND `branch_tag` = 'Prototype'
							";
							$query = $this->db->query($sql);
							
							if($query->num_rows() < 1) {
								$sql = "
									INSERT INTO `sj_relationship`
									(`student_id`, `adult_id`, `status`, `branch_tag`)
									VALUES
									(" . $this->db->escape($student_id) . ", " . $this->db->escape($value->user_id) . ", 1, 'Prototype')
								";
								$query = $this->db->query($sql);	
							}
						}
					}

				}
			}
			redirect(base_url()."administrator/class_list/".$view);
		}
	}


	public function add_class($view='grid'){
		$this->load->model('model_question');
		if(isset($_POST['submit_new'])) {
			$class_name = $this->input->post('class_name');
			$data['post']['class_name'] = $class_name;
			$data['post']['subject'] = $this->input->post('subject_id');
			$data['post']['level'] = $this->input->post('level');
			$data['post']['id_value'] = $this->input->post('id_value');
			if($class_name=="" || $data['post']['subject']=="" || $data['post']['level']=="") {
				if($class_name=="") $data['error']['class_name'] = "Class name cannot be empty";
				if($data['post']['subject']=="") $data['error']['subject'] = "Subject cannot be empty";
				if($data['post']['level']=="") $data['error']['level'] = "Level cannot be empty";
			} else {
				$subject = intval($this->input->post('subject_id'));
				$level = intval($this->input->post('level'));
				$dat = array(
					'class_name' => $class_name,
					'subject_id' => $subject,
					'level_id' => $level,
					'created_date' => date('Y-m-d H:i:s'),
					'created_by' => $this->session->userdata('admin_id'),
					'branch' => 9
				);
				$this->db->insert('sj_class',$dat);
				redirect(base_url()."administrator/class_list/".$view);
			}
		}
		$subject = $this->model_question->get_subject_list();
		$data['page'] = "";
		$data['view'] = $view;
		$data['subject'] = $subject;
		$data['content'] = 'administrator/administrator_add_class';
		$this->load->view('include/master_view', $data);
	}

	public function edit_class($class_id=0,$page=0,$view='grid'){
		$this->load->model('model_question');
		$data['class'] = $this->model_admin->get_class($class_id);
		if(!$data['class']) redirect(base_url()."administrator/class_list");
		$data['post']['class_name'] = $data['class']->class_name;
		$data['post']['subject'] = $data['class']->subject_id;
		$data['post']['level'] = $data['class']->level_id;
		$data['level'] = $this->model_admin->get_level_by($data['class']->subject_id, $data['class']->level_id);
		$data['post']['id_value'] = ($data['level']) ? $data['level']->id : 0;
		if(isset($_POST['submit_new'])) {
			$class_name = $this->input->post('class_name');
			if($class_name=="") {
				$data['error']['class_name'] = "Class name cannot be empty";
			} else {
				$subject = intval($this->input->post('subject_id'));
				$level = intval($this->input->post('level'));
				$dat = array(
					'class_name' => $class_name,
					'subject_id' => $subject,
					'level_id' => $level
				);
				$this->db->where('class_id',$class_id);
				$this->db->update('sj_class',$dat);
				redirect(base_url()."administrator/class_list/".$view."/".$page);
			}
		}
		$subject = $this->model_question->get_subject_list();
		$data['subject'] = $subject;
		$data['class_id'] = $class_id;
		$data['view'] = $view;
		$data['page'] = $page;
		$data['content'] = 'administrator/administrator_add_class';
		$this->load->view('include/master_view', $data);
	}

	public function edit_status_class($class_id=0) {
		if ($this->input->is_ajax_request()) {
			$class_id = $this->input->post('class_id');
			$status_now = $this->input->post('status');
			$status_req = ($status_now==1) ? 0 : 1;
			$data = array(
				'status' => $status_req
			);
			$this->db->where('class_id',$class_id);
			$this->db->update('sj_class',$data);
			$returnArray['success'] = true;
			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}

	public function class_list($view="grid",$page=0,$keyword="")
	{
		$profile_message_success = $this->session->userdata('profileMessageSuccess');
		$profile_message = $this->session->userdata('profileMessage');
		if (isset($profile_message_success)) {
			$data['profile_message_success'] = ($profile_message_success == 0)?false:true;
			$data['profile_message'] = $profile_message;
			$this->session->unset_userdata('profileMessageSuccess');
			$this->session->unset_userdata('profileMessage');
		}

		if(isset($_POST['search'])) {
			$view = $this->input->post('view');
			$keyword = $this->input->post('keyword_class');
			$keyword = str_replace(" ", "-", $keyword);
			$keyword = str_replace('[^a-zA-Z0-9_]', "", $keyword) ;
			redirect(base_url()."administrator/class_list/".$view."/0/".$keyword);
		}

		if(isset($_POST['addnew'])) {
			$view = $this->input->post('view');
			redirect(base_url()."administrator/add_class/".$view);
		}

		$this->load->library('pagination');
		$config = array ();
		$config['base_url'] = base_url() . 'administrator/class_list/'.$view;
		$config['total_rows'] = $this->model_admin->count_class($keyword);
		$config['per_page'] =6;
		$config['uri_segment'] =4;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
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
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['classes'] = $this->model_admin->get_classes($config['per_page'], $page, $keyword);
		$data['links'] = $this->pagination->create_links();
		$data['page'] = $page;
		$data['view'] = $view;
		$data['keyword'] = $this->splitString($keyword);
		$data['content'] = 'administrator/administrator_class_list';
		$this->load->view('include/master_view', $data);
		
	}

	public function student_class($class_id=0,$page=0){		
		$data['class'] = $this->model_admin->get_class($class_id);
		if(!$data['class']) redirect(base_url()."administrator/class_list");
		$data['student_class'] = $this->model_admin->get_class_relation($class_id,'student');
		$data['tutor_class'] = $this->model_admin->get_class_relation($class_id,'tutor');		
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['user'] = $this->model_admin->get_class_student();		
		$data['class_id'] = $class_id;
		$data['page'] = $page;		
		$data['content'] = 'administrator/administrator_student_class';
		$this->load->view('include/master_view', $data);
		
	}


	public function tutor_class($class_id=0,$page=0) {		
		$data['class'] = $this->model_admin->get_class($class_id);
		if(!$data['class']) redirect(base_url()."administrator/class_list");
		$data['student_class'] = $this->model_admin->get_class_relation($class_id,'student');
		$data['tutor_class'] = $this->model_admin->get_class_relation($class_id,'tutor');

		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['user'] = $this->model_admin->get_class_tutor();		
		$data['class_id'] = $class_id;
		$data['page'] = $page;		
		$data['content'] = 'administrator/administrator_tutor_class';
		$this->load->view('include/master_view', $data);
		
	}

	public function splitString($str)
	{
		$splitString = array();
		$splitString = explode("-",$str);
		$splitString = implode(" ",$splitString);
		return $splitString;
	}

	public function tagStudent($type="student") {
		if($this->input->is_ajax_request()) {
			$class_id = $this->input->post('class_id');
			$student_id = $this->input->post('student_id');
			$data = array(
				'class_id' => $class_id,
				'user_id' => $student_id,
				'type' => $type,
				'status' => 1
			);
			$this->db->insert('sj_class_relation',$data);

			if($type == 'student') {
				$sql = "
					SELECT * FROM `sj_class_relation`
					WHERE `class_id` = " . $this->db->escape($class_id) . "
					AND `type` = 'tutor'
				";
				$query = $this->db->query($sql);
				
				if($query->num_rows() > 0) {
					
					foreach($query->result() as $value) {
						$sql = "
							SELECT * FROM `sj_relationship`
							WHERE `adult_id` = " . $this->db->escape($value->user_id) . "
							AND `student_id` =" . $this->db->escape($student_id) . "
							AND `status` = 1
							AND `branch_tag` = 'Prototype'
						";
						$query = $this->db->query($sql);
						
						if($query->num_rows() < 1) {
							$sql = "
								INSERT INTO `sj_relationship`
								(`student_id`, `adult_id`, `status`, `branch_tag`)
								VALUES
								(" . $this->db->escape($student_id) . ", " . $this->db->escape($value->user_id) . ", 1, 'Prototype')
							";
							$query = $this->db->query($sql);	
						}
					}
				}
			} else {
				$sql = "
					SELECT * FROM `sj_class_relation`
					WHERE `class_id` = " . $this->db->escape($class_id) . "
					AND `type` = 'student'
				";
				$query = $this->db->query($sql);
				
				if($query->num_rows() > 0) {
					
					foreach($query->result() as $value) {
						$sql = "
							SELECT * FROM `sj_relationship`
							WHERE `student_id` = " . $this->db->escape($value->user_id) . "
							AND `adult_id` =" . $this->db->escape($student_id) . "
							AND `status` = 1
							AND `branch_tag` = 'Prototype'
						";
						$query = $this->db->query($sql);
						
						if($query->num_rows() < 1) {
							$sql = "
								INSERT INTO `sj_relationship`
								(`student_id`, `adult_id`, `status`, `branch_tag`)
								VALUES
								(" . $this->db->escape($value->user_id) . ", " . $this->db->escape($student_id) . ", 1, 'Prototype')
							";
							$query = $this->db->query($sql);	
						}
					}
				}
			}
			
			$returnArray['success'] = true;
			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}


	public function removeTagStudent() {
		if ($this->input->is_ajax_request()) {
			$class_id = $this->input->post('class_id');
			$student_id = $this->input->post('student_id');			
			$this->db->where('class_id', $class_id);
			$this->db->where('user_id', $student_id);
			$this->db->delete('sj_class_relation');

			// sj_relationship
			$sql = "
					SELECT * FROM `sj_class_relation`
					WHERE `class_id` = " . $this->db->escape($class_id) . " 
					AND `type` = 'tutor'
				";
			$row = $this->db->query($sql)->row();
			if(isset($row)){
				$this->db->where('student_id', $student_id);
				$this->db->where('adult_id', $row->user_id);
				$this->db->where('status', 1);
				$this->db->where('branch_tag', 'Prototype');
				$this->db->delete('sj_relationship');
			}
			
			$returnArray['success'] = true;
			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}

	public function removeTagTutor() {
		if ($this->input->is_ajax_request()) {
			$class_id = $this->input->post('class_id');			
			$tutor_id = $this->input->post('tutor_id');
			$this->db->where('class_id', $class_id);
			$this->db->where('user_id', $tutor_id);
			$this->db->delete('sj_class_relation');

			// sj_relationship
			$sql = "
					SELECT * FROM `sj_class_relation`
					WHERE `class_id` = " . $this->db->escape($class_id) . " 
					AND `type` = 'student'
				";
			$query = $this->db->query($sql);
			foreach($query->result() as $row){
				$this->db->where('student_id', $row->user_id);
				$this->db->where('adult_id', $tutor_id);
				$this->db->where('status', 1);
				$this->db->where('branch_tag', 'Prototype');
				$this->db->delete('sj_relationship');
			}

			$returnArray['success'] = true;
			echo json_encode($returnArray);
		} else {
			redirect('404');
		}
	}
	
	public function student_list(){
		$profile_message_success = $this->session->userdata('profileMessageSuccess');
		$profile_message = $this->session->userdata('profileMessage');
		if (isset($profile_message_success)) {
			$data['profile_message_success'] = ($profile_message_success == 0)?false:true;
			$data['profile_message'] = $profile_message;
			$this->session->unset_userdata('profileMessageSuccess');
			$this->session->unset_userdata('profileMessage');
		}

		$data['user'] = $this->model_admin->get_branch_student();		
		$data['page'] = 'student-list';
		$data['content'] = 'administrator/administrator_student_list';
		$this->load->view('include/master_view', $data);
		
	}
	
	public function student_status(){

		$inactive_user_id = trim($this->input->post('inactive_student_id'));
		$inactive_user_status = trim($this->input->post('inactive_student_status'));
		
		$active_user_id = trim($this->input->post('active_student_id'));
		$active_user_status = trim($this->input->post('active_student_status'));
		
		if(isset($inactive_user_id) && !empty($inactive_user_id)){
			$success = $this->model_admin->update_student_status($inactive_user_id, $inactive_user_status);
			if ($success) {
				$this->session->set_userdata('profileMessageSuccess', true);
				$this->session->set_userdata('profileMessage', 'You have successfully deactivate the tutor');
			}  else {
				$this->session->set_userdata('profileMessageSuccess', 0);
				$this->session->set_userdata('profileMessage', 'Failure in deactivate the tutor.Please try again later.');
			}
		} else if(isset($active_user_id) && !empty($active_user_id)){
			$success = $this->model_admin->update_student_status($active_user_id, $active_user_status);
			if ($success) {
				$this->session->set_userdata('profileMessageSuccess', true);
				$this->session->set_userdata('profileMessage', 'You have successfully reactivate the tutor');
			}  else {
				$this->session->set_userdata('profileMessageSuccess', 0);
				$this->session->set_userdata('profileMessage', 'Failure in deactivate the tutor.Please try again later.');
			}
		} else {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'The tutor is not registered in the system.');
		}
		
		redirect(base_url().'administrator/student_list');
		
	}
	
	public function add_branch_student(){
		$email = $this->input->post('add_branch_student');
		$success = $this->model_admin->add_branch_student($email);
		if($success){
			$this->session->set_userdata('profileMessageSuccess', true);
			$this->session->set_userdata('profileMessage', 'You have successfully added the tutor in the listing.');
		} else {
			$this->session->set_userdata('profileMessageSuccess', 0);
			$this->session->set_userdata('profileMessage', 'Failure in adding the tutor in the listing.Please try again later.');
		}
		redirect(base_url().'administrator/student_list');
	}

	public function checkUserExist() {

		if ($this->input->is_ajax_request()) {

			$this->load->model('model_users');

			$userName = $this->input->post('userName');

			$email = $this->input->post('email');

			$usernameExist = $this->model_users->check_user_exist($userName);

			$emailExist = $this->model_users->check_email_exist($email);

			$returnArray['usernameExist'] = $usernameExist;

			$returnArray['emailExist'] = $emailExist;

			echo json_encode($returnArray);

		} else {

			redirect('404');

		}

	}

	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}

	// shukri start

	public function worksheet_generator() {
		$this->worksheetMode();
	}

	function worksheetMode() {
		$data['content'] = "administrator/administrator_mode";
		$this->load->view('include/master_view', $data);

		// $logged_in = $this->session->userdata('is_logged_in');
		// // check logged in user
		// if(isset($logged_in) && empty($logged_in) == false) {
		// 	$data['content'] = "smartgen/smartgen_mode";
		// 	$this->load->view('include/master_view', $data);
		// } else {
		// 	redirect('404');
		// }
	}

	// function designExam() {
	// 	// $logged_in = $this->session->userdata('is_logged_in');
	// 	$logged_in = $this->session->userdata('is_admin_logged_in');

	// 	$data['is_logged_in'] = $logged_in;	
	// 	$data['page'] = 'worksheet-exam-mode';	
	// 	$data['content'] = 'administrator/administrator_exam';
	// 	$data['levels']  = $this->model_question->get_level_list(2);
	// 	$data['substrands'] = $this->model_question->get_substrand_list(NULL, 2);
	// 	$data['topics']  = $this->model_question->get_topic_list(1);
	// 	$data['strategys'] = $this->model_question->get_strategy_list();

	// 	$subId = array();
	// 	$data['subjects'] = $this->model_question->get_subject_list($subId);

	// 	$selectedNumOfQuestion = $this->session->userData('worksheetNumOfQuestion');
	// 	$selectedDifficulty = $this->session->userData('worksheetDifficulty');
	// 	$selectedLevel = $this->session->userData('worksheetLevel');
	// 	$selectedTopic = $this->session->userData('worksheetTopic');
	// 	$selectedSubstrand = $this->session->userData('worksheetSubstrand');
	// 	$selectedStrategy = $this->session->userData('worksheetStrategy');
	// 	$selectedSubject = $this->session->userData('worksheetSubject');
		
	// 	if (isset($selectedNumOfQuestion)) {
	// 		$data['selectedNumOfQuestion'] = $selectedNumOfQuestion;
	// 	}

	// 	if (isset($selectedDifficulty)) {
	// 		$data['selectedDifficulty'] = $selectedDifficulty;
	// 		if ($selectedDifficulty < 30) {
	// 			$data['selectedDifficultyOutput'] = "Easy";
	// 		} else if ($selectedDifficulty >= 30 && $selectedDifficulty < 60) {
	// 			$data['selectedDifficultyOutput'] = "Normal";
	// 		} else if ($selectedDifficulty >= 60 && $selectedDifficulty < 80) {
	// 			$data['selectedDifficultyOutput'] = "Hard";
	// 		} else {
	// 			$data['selectedDifficultyOutput'] = "Genius";
	// 		}
	// 	}

	// 	if (isset($selectedLevel)) {
	// 		$data['selectedLevel'] = $selectedLevel;
	// 	}

	// 	if (isset($selectedTopic)) {
	// 		$topicArray = array();
	// 		$topicArray[] = $selectedTopic[0];
	// 		$data['selectedTopic'] = $topicArray;
	// 	}

	// 	if (isset($selectedSubstrand)) {
	// 		$substrandArray = array();
	// 		$substrandArray[] = $selectedSubstrand[0];
	// 		$data['selectedSubstrand'] = $substrandArray;
	// 		$topics = array();		
	// 		foreach ($selectedSubstrand as $selected) {
	// 			$topic_list = $this->model_question->get_topic_list($selected);
	// 			$topics[] = $topic_list;
	// 		}
	// 		$data['topics'] = $topics;
	// 	}

	// 	if(isset($selectedStrategy)) {
	// 		$data['selectedStrategy'] = $selectedStrategy;
	// 	}

	// 	if(isset($selectedSubject)) {
	// 		$data['selectedSubject'] = $selectedSubject;
	// 	}

	// 	$this->load->view('include/master_view', $data);
	// }

	// public function createGenerateExam($worksheetId = NULL, $more_question = false, $exclude = array(), $start = 0) {
	// 	if (isset($worksheetId) && empty($worksheetId) === false) {
	// 		$this->check_worksheet_owner($worksheetId, '404');
	// 		$data['worksheetId'] = $worksheetId;
	// 	}
	// 	$postData = $this->input->post();
	// 	$sessionData = $this->session->userdata('questionArray');
	// 	$target_id = array();
	// 	if(is_array($sessionData) && !empty($sessionData)){
	// 		if (array_key_exists('target_id', $sessionData)){
	// 			$target_id = $sessionData['target_id'];
	// 			unset($sessionData['target_id']);
	// 		}
	// 	}

	// 	$removeData = $this->session->userdata('removeSubQuestionArray');
	// 	$remove_id = array();
	// 	if(is_array($removeData) && !empty($removeData)){
	// 		$remove_id = $removeData;
	// 	}
	// 	$this->session->set_userdata('removeQuestionArray', $remove_id);
	// 	$this->session->set_userdata('questionArray', $sessionData);
	// 	$this->session->unset_userdata('parentQuestionArray');
	// 	$this->session->unset_userdata('removeSubQuestionArray');
		
		
	// 	if (isset($postData) && empty($postData) === false) {
	// 		$questionList = array();
	// 		$answerList = array();
	// 		$categoryList = array();
	// 		$substrandList = array();
	// 		$subquestionList = array();
	// 		$strategyList = array();

	// 		//submit from regenerate all button
	// 			if (isset($postData['regenerateWorksheet']) && empty($postData['regenerateWorksheet']) === false) {
	// 				$postData['gen_num_of_question'] = $this->session->userdata('ExamNumOfQuestion');
	// 				$postData['gen_difficulties']    = $this->session->userdata('ExamDifficulty');
	// 				$postData['gen_level']           = $this->session->userdata('ExamLevel');
	// 				$postData['gen_topic']           = $this->session->userdata('ExamTopic');
	// 				$postData['gen_substrand']       = $this->session->userdata('ExamSubstrand');
	// 				$postData['gen_subject']        = $this->session->userdata('ExamSubject');
	// 			} else {
	// 				//save requirement in session

	// 				$sessionArray = array(
	// 					'ExamNumOfQuestion' => $this->input->post('gen_num_of_question'),
	// 					'ExamDifficulty'    => $this->input->post('gen_difficulties'),
	// 					'ExamLevel'         => $this->input->post('gen_level'),
	// 					'ExamTopic'         => $this->input->post('gen_topic'),
	// 					'ExamSubstrand'     => $this->input->post('gen_substrand'),
	// 					'ExamSubject'  => $this->input->post('gen_subject')
	// 				);
	// 				$this->session->set_userdata($sessionArray);
	// 			}
				
	// 			if($more_question == false){
	// 				$this->load->model('model_worksheet');					
	// 				$this->model_worksheet->save_worksheet_requirement_exam($postData);					
	// 			}								
	// 	} else if (isset($sessionData)) {
	// 			foreach ($sessionData AS $questionId) {
	// 				$questionDetail = $this->model_question->get_question_from_id($questionId);
	// 				$questionList[] = $questionDetail;
	// 				$categoryList[] = $this->model_question->get_category_from_question_id($questionId);
	// 				$strategyList[] = $this->model_question->get_strategy_from_question_id($questionId);
	// 				$substrandList[] = $this->model_question->get_substrand_from_question_id($questionId);
	// 				$subquestionList[] = $this->model_question->sub_question($questionId);
	// 			}
	// 	} else {
	// 			// $data['content'] = 'smartgen/smartgen_home';
	// 			// $this->load->view('include/master_view', $data);
	// 			// return ;

	// 			redirect(base_url() . 'administrator/worksheet_generator');
	// 	}

	// 	redirect(base_url() . 'administrator/generateExam');
	// }

	// public function generateExam(){		
	// 	$questionList = $this->model_question->get_exam_list();		

	// 	foreach ($questionList as $question) {
	// 		$categoryList[] = $this->model_question->get_category_from_question_id($question->question_id);
	// 		$strategyList[] = $this->model_question->get_strategy_from_question_id($question->question_id);
	// 		$substrandList[] = $this->model_question->get_substrand_from_question_id($question->question_id);
	// 		$subquestionList[] = $this->model_question->sub_question($question->question_id);
	// 	}

	// 	// $data['isLoggedIn'] = $this->session->userdata('is_logged_in');
	// 	$data['isLoggedIn'] = $this->session->userdata('is_admin_logged_in');
	// 	if ($this->session->userdata('is_admin_logged_in') == 1) {
	// 		$data['user_id'] = $this->session->userdata('user_id');
	// 	} else {
	// 		$data['user_id'] = 0;   // stands for visitor
	// 	}

	// 	$data['page'] = 'generate-exam';
	// 	//get the answers from here
	// 	$answerList = $this->model_question->get_answer_list($questionList);
	// 	$data['answerList'] = $answerList;
	// 	$data['questionList'] = $questionList;
	// 	$data['categoryList'] = $categoryList;
	// 	$data['substrandList'] = $substrandList;
	// 	$data['subquestionList'] = $subquestionList;
	// 	$data['strategyList'] = $strategyList;
	// 	$requirement_id = $this->model_question->get_question_type_from_requirement_id($this->session->userdata('requirementId'));


	// 	//show worksheet's criteria in smartgen_addMore 
	// 	$sess_worksheet_topic = $this->session->userdata('ExamTopic');
	// 	$sess_worksheet_lvl = $this->session->userdata('ExamLevel');
	// 	$sess_worksheet_substr = $this->session->userdata('ExamSubstrand');

	// 	$sess_worksheet_str = $this->session->userdata('ExamStrategy');
	// 	$worksheet_lvl = $this->model_worksheet->get_worksheet_level($sess_worksheet_lvl);
	// 	$worksheet_substr = $this->model_worksheet->get_worksheet_substrands($sess_worksheet_substr);
	// 	$worksheet_topic = $this->model_worksheet->get_worksheet_topics($sess_worksheet_topic);
	// 	$worksheet_strategy = $this->model_worksheet->get_worksheet_strategys($sess_worksheet_str);
		
	// 	$data['worksheet_lvl'] = $worksheet_lvl;
	// 	$data['worksheet_substr'] = $worksheet_substr;
	// 	$data['worksheet_topic'] = $worksheet_topic;
	// 	$data['worksheet_strategy'] = $worksheet_strategy;
	// 	$data['que_type'] = $requirement_id;
				
	// 	$data['content'] = 'administrator/administrator_generateExam';
	// 	$this->load->view('include/master_view', $data);
	// }

	public function designWorksheet($worksheetId = NULL) {
		// $logged_in = $this->session->userdata('is_logged_in');
		$logged_in = $this->session->userdata('is_admin_logged_in');
		if (isset($worksheetId) && empty($worksheetId) === false) {
			//if user is logged in, check if the worksheetId is tie to the user
			if ($logged_in == 1) {
				$ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $this->session->userdata('user_id'));
				//if worksheet is not tie to user, redirect back without worksheetId
				if (!$ownedByUser) {
					redirect(base_url().'administrator/worksheet_generator');
				}
				$data['worksheetId'] = $worksheetId;
			} else { //else redirect back without worksheetId
				redirect(base_url().'administrator/worksheet_generator');
			}
		}

		$data['is_logged_in'] = $logged_in;
		$data['content'] = 'administrator/smartgen_home';
		$data['page'] = 'worksheet-quiz-mode';
		$data['levels']  = $this->model_question->get_level_list(2);
		$data['substrands'] = $this->model_question->get_substrand_list(NULL, 2);
		$data['topics']  = $this->model_question->get_topic_list(1);
		$data['strategys'] = $this->model_question->get_strategy_list();

		if($this->session->userdata('user_id') == 490 || $this->session->userdata('user_id') == 121) {
			$subId = array();
		} else {
			$subId = array(1,2,3,4,5,6);
		}
		
		$data['subjects'] = $this->model_question->get_subject_list($subId);
		$selectedNumOfQuestion = $this->session->userData('worksheetNumOfQuestion');
		$selectedDifficulty = $this->session->userData('worksheetDifficulty');
		$selectedLevel = $this->session->userData('worksheetLevel');
		$selectedTopic = $this->session->userData('worksheetTopic');
		$selectedSubstrand = $this->session->userData('worksheetSubstrand');
		$selectedStrategy = $this->session->userData('worksheetStrategy');
		$selectedSubject = $this->session->userData('worksheetSubject');
		
		if (isset($selectedNumOfQuestion)) {
			$data['selectedNumOfQuestion'] = $selectedNumOfQuestion;
		}

		if (isset($selectedDifficulty)) {
			$data['selectedDifficulty'] = $selectedDifficulty;
			if ($selectedDifficulty < 30) {
				$data['selectedDifficultyOutput'] = "Easy";
			} else if ($selectedDifficulty >= 30 && $selectedDifficulty < 60) {
				$data['selectedDifficultyOutput'] = "Normal";
			} else if ($selectedDifficulty >= 60 && $selectedDifficulty < 80) {
				$data['selectedDifficultyOutput'] = "Hard";
			} else {
				$data['selectedDifficultyOutput'] = "Genius";
			}
		}

		if (isset($selectedLevel)) {
			$data['selectedLevel'] = $selectedLevel;
		}

		if (isset($selectedTopic)) {
			$data['selectedTopic'] = $selectedTopic;
		}

		if (isset($selectedSubstrand)) {
			$data['selectedTopic'] = $selectedTopic;
		}

		if (isset($selectedSubstrand)) {
			$data['selectedSubstrand'] = $selectedSubstrand;
			$topics = array();
			foreach ($selectedSubstrand as $selected) {
				$topic_list = $this->model_question->get_topic_list($selected);
				$topics[] = $topic_list;
			}
			$data['topics'] = $topics;
		}

		if(isset($selectedStrategy)) {
			$data['selectedStrategy'] = $selectedStrategy;
		}

		if(isset($selectedSubject)) {
			$data['selectedSubject'] = $selectedSubject;
		}

		$this->load->view('include/master_view', $data);
	}

	public function generateWorksheet($worksheetId = NULL, $more_question = false, $exclude = array(), $start = 0) {
		
		if (isset($worksheetId) && empty($worksheetId) === false) {
			$this->check_worksheet_owner($worksheetId, '404');
			$data['worksheetId'] = $worksheetId;
		}
		$this->load->model('model_question');
		$postData = $this->input->post();
		
		$sessionData = $this->session->userdata('questionArray');
		$target_id = array();
		if(is_array($sessionData) && !empty($sessionData)){
			if (array_key_exists('target_id', $sessionData)){
				$target_id = $sessionData['target_id'];
				unset($sessionData['target_id']);
			}
		}

		$removeData = $this->session->userdata('removeSubQuestionArray');
		$remove_id = array();

		if(is_array($removeData) && !empty($removeData)){
			$remove_id = $removeData;
		}

		$this->session->set_userdata('removeQuestionArray', $remove_id);
		$this->session->set_userdata('questionArray', $sessionData);
		$this->session->unset_userdata('parentQuestionArray');
		$this->session->unset_userdata('removeSubQuestionArray');
				
		// $data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		$data['isLoggedIn'] = $this->session->userdata('is_admin_logged_in');
		
		if ($this->session->userdata('is_admin_logged_in') == 1) {
			$data['user_id'] = $this->session->userdata('admin_id');
		} else {
			$data['user_id'] = 0;   // stands for visitor
		}
		
		if (isset($postData) && empty($postData) === false) {
			$questionList = array();
			$answerList = array();
			$categoryList = array();
			$substrandList = array();
			$subquestionList = array();
			$strategyList = array();


			//submit from regenerate all button
				if (isset($postData['regenerateWorksheet']) && empty($postData['regenerateWorksheet']) === false) {
					$postData['gen_num_of_question'] = $this->session->userdata('worksheetNumOfQuestion');
					$postData['gen_difficulties']    = $this->session->userdata('worksheetDifficulty');
					$postData['gen_level']           = $this->session->userdata('worksheetLevel');
					$postData['gen_topic']           = $this->session->userdata('worksheetTopic');
					$postData['gen_que_type']        = $this->session->userdata('worksheetQueType');
					$postData['gen_substrand']       = $this->session->userdata('worksheetSubstrand');
					$postData['gen_strategy']        = $this->session->userdata('worksheetStrategy');
					$postData['gen_subject']        = $this->session->userdata('worksheetSubject');
				} else {
					//save requirement in session
					if($this->input->post('gen_que_bank') == 'public') {
						$sessionArray = array(
							'worksheetNumOfQuestion' => $this->input->post('gen_num_of_question'),
							'worksheetDifficulty'    => $this->input->post('gen_difficulties'),
							'worksheetLevel'         => $this->input->post('gen_level'),
							'worksheetTopic'         => $this->input->post('gen_topic'),
							'worksheetQueType'       => $this->input->post('gen_que_type'),
							'worksheetSubstrand'     => $this->input->post('gen_substrand'),
							'worksheetStrategy'  => $this->input->post('gen_strategy'),
							'worksheetSubject'  => $this->input->post('gen_subject'),
							'worksheetTags'          => 'all',
							'worksheetQuesBank'      => $this->input->post('gen_que_bank')
						);
					} else {
						if($this->input->post('gen_tag') == 'all') {
							$sessionArray = array(
								'worksheetNumOfQuestion' => $this->input->post('gen_num_of_question'),
								'worksheetDifficulty'    => $this->input->post('gen_difficulties'),
								'worksheetLevel'         => $this->input->post('gen_level'),
								'worksheetTopic'         => $this->input->post('gen_topic'),
								'worksheetQueType'       => $this->input->post('gen_que_type'),
								'worksheetSubstrand'     => $this->input->post('gen_substrand'),
								'worksheetStrategy'  => $this->input->post('gen_strategy'),
								'worksheetSubject'  => $this->input->post('gen_subject'),
								'worksheetTags'          => $this->input->post('gen_tag'),
								'worksheetQuesBank'      => $this->input->post('gen_que_bank')
							);
						} else {
							$sessionArray = array(
								'worksheetNumOfQuestion' => $this->input->post('gen_num_of_question'),
								'worksheetDifficulty'    => array(1, 2, 3, 4, 5),
								'worksheetLevel'         => $question_detail->level_id,
								'worksheetTopic'         => $question_detail->topic_id,
								'worksheetQueType'       => '',
								'worksheetSubstrand'     => array(58),
								'worksheetSubject'       => $this->input->post('gen_subject'),
								'worksheetTags'          => $this->input->post('gen_tag')
							);
						}
					}
					$this->session->set_userdata($sessionArray);
				}				

				if($this->input->post('gen_que_bank') == 'public') {
					
					$postData['gen_tag'] = 'all';
					$quesTag = $postData['gen_tag'];

				} else {
					if(empty($postData['gen_tag']) == TRUE) {
						$postData['gen_tag'] = 'all';
						$quesTag = $postData['gen_tag'];
					} else {
						$quesTag = $postData['gen_tag'];
					}
					
				}

				if($more_question == false){
					$this->load->model('model_worksheet');
					
					$this->model_worksheet->save_worksheet_requirement($postData);
					
				}
				
				if(isset($exclude) && empty($exclude) == true){	
					$questionList = $this->model_question->get_question_list($postData, $data['user_id']);
					
				}else{
					$result = $this->model_question->get_question_list($postData, $data['user_id'],$exclude, $start);
					$questionList = $result['result'];
					$data['total_rows'] = $result['total_rows'];
				}

				foreach ($questionList as $question) {
					$categoryList[] = $this->model_question->get_category_from_question_id($question->question_id);
					$strategyList[] = $this->model_question->get_strategy_from_question_id($question->question_id);
					$substrandList[] = $this->model_question->get_substrand_from_question_id($question->question_id);
					$subquestionList[] = $this->model_question->sub_question($question->question_id);
				}
		} else if (isset($sessionData)) {
				foreach ($sessionData AS $questionId) {
					$questionDetail = $this->model_question->get_question_from_id($questionId);
					$questionList[] = $questionDetail;
					$categoryList[] = $this->model_question->get_category_from_question_id($questionId);
					$strategyList[] = $this->model_question->get_strategy_from_question_id($questionId);
					$substrandList[] = $this->model_question->get_substrand_from_question_id($questionId);
					$subquestionList[] = $this->model_question->sub_question($questionId);
				}
		} else {
				// $data['content'] = 'smartgen/smartgen_home';
				// $this->load->view('include/master_view', $data);
				// return ;

				redirect(base_url() . 'smartgen');
		}

		//get the answers from here
		$answerList = $this->model_question->get_answer_list($questionList);
		$data['answerList'] = $answerList;
		$data['questionList'] = $questionList;
		$data['categoryList'] = $categoryList;
		$data['substrandList'] = $substrandList;
		$data['subquestionList'] = $subquestionList;
		$data['strategyList'] = $strategyList;
		$data['target_id'] = json_encode($target_id);
		$requirement_id = $this->model_question->get_question_type_from_requirement_id($this->session->userdata('requirementId'));

		// create new session for worksheet
		


		//show worksheet's criteria in smartgen_addMore 
 		if($this->session->userdata('worksheetSubject') == '7') {
			$sess_worksheet_topic = array(1);
			$sess_worksheet_lvl = 1;
			$sess_worksheet_substr = array(1);
			$sess_worksheet_subject = 1;
		} else {
			$sess_worksheet_topic = $this->session->userdata('worksheetTopic');
			$sess_worksheet_lvl = $this->session->userdata('worksheetLevel');
			$sess_worksheet_substr = $this->session->userdata('worksheetSubstrand');
			$sess_worksheet_tag = $this->session->userdata('worksheetTags');
			$sess_worksheet_subject = $this->session->userdata('worksheetSubject');
		}

		$sess_worksheet_str = $this->session->userdata('worksheetStrategy');
		$worksheet_lvl = $this->model_worksheet->get_worksheet_level($sess_worksheet_lvl, $sess_worksheet_subject);
		$worksheet_substr = $this->model_worksheet->get_worksheet_substrands($sess_worksheet_substr);
		$worksheet_topic = $this->model_worksheet->get_worksheet_topics($sess_worksheet_topic);
		$worksheet_strategy = $this->model_worksheet->get_worksheet_strategys($sess_worksheet_str);
		
		$data['worksheet_lvl'] = $worksheet_lvl;
		$data['worksheet_tag'] = $sess_worksheet_tag;
		$data['worksheet_substr'] = $worksheet_substr;
		$data['worksheet_topic'] = $worksheet_topic;
		$data['worksheet_strategy'] = $worksheet_strategy;
		$data['que_type'] = $requirement_id;
		
		if($more_question){
			return $data;
		}else{
			// To save generated questions to session for later use
			$this->save_gen_questions_list($data['questionList']);
		}

		if($sess_worksheet_tag == 'all') {
			if ($requirement_id == 1){
				$data['content'] = 'administrator/administrator_generateWorksheet';

			} else {
				$data['content'] = 'administrator/administrator_generateWorksheets';
			}
		} else {
			$data['content'] = 'administrator/administrator_generateWorksheets';
		}
		$this->load->view('include/master_view', $data);

	}

	function save_gen_questions_list($question_list = array()){
		$result = array();
		if(!empty($question_list)){
			foreach($question_list as $question){
				$result[] = $question->question_id;
				
				$this->session->set_userdata('generated_questions',$result);
			}
		} else {
			$this->session->set_userdata('generated_questions',$result);
		}
	}

	public function saveWorksheet() {
		$worksheetName = $this->input->post('worksheet_name');
		//proceed only if worksheetname is set
		if (isset($worksheetName) && empty($worksheetName) === false) {
			//actually login check is already done on the interface
			if ($this->session->userdata('is_admin_logged_in') == 1) {
				$worksheetId = $this->model_worksheet->save_worksheet($worksheetName);
				if ($worksheetId) {
					$this->session->set_userdata('save_worksheet_success', "Worksheet saved");
					redirect(base_url()."administrator/assignWorksheet/".$worksheetId);
				} else {
					//some error in saving worksheet, redirect for proper handling
					redirect('404');
				}			
			} 
		} else {
			redirect(base_url()."administrator/generateWorksheets");
		}
	}

	public function assignWorksheet($worksheetId) {
        $postData = $this->input->post();
        $this->load->model('model_users');
		$this->load->model('model_quiz');
        if (isset($postData) && empty($postData) === false) {
			
            $assigned_tutors = (isset($postData['assigned_students']) && empty($postData['assigned_students']) === false)?$postData['assigned_students']:array();

            if ($this->model_quiz->assign_tutors($worksheetId, $assigned_tutors)) {
				
                //send notification email to the student
                $this->load->library('email');

                $tutor_name = $this->model_users->get_username_from_id($this->session->userdata('user_id'));


                foreach ($assigned_tutors as $assigned_id) {

                    $this->email->from('noreply@smartjen.com', "SmartJen");

                    $this->email->Subject('SmartJen - Quiz Assignment Notification');

					$user_info = $this->model_users->get_user_info($assigned_id);
					
					// $receipient = array($user_info->email, 'mongkoklyit@gmail.com', 'mongkoklyit@gmail.com');

                    $this->email->to($user_info->email, $user_info->fullname);

                    $message = "<p>Dear " . $user_info->fullname . ", </p>";

                    $message .= "<p>A quiz has been assigned to you by " . $tutor_name . ". Please login to <a href='".base_url()."'>https://smartjen.com</a> to complete your quiz now!</p>";

                    $message .= "<br><br><p>SmartJen</p>";



                    $this->email->message($message);

                    $this->email->send();

                } 

                $this->session->set_userdata('profileMessageSuccess', true);

                $this->session->set_userdata('profileMessage', 'Worksheet successfully updated.');

            } else {

                $this->session->set_userdata('profileMessageSuccess', 0);

                $this->session->set_userdata('profileMessage', 'Error in assigning worksheet, please try again later');

            }


            redirect(base_url().'administrator/worksheet_list');

        } else {

            if (isset($worksheetId) && empty($worksheetId) === false) {

                $this->check_worksheet_owner($worksheetId, '404');

                $data['worksheet_id'] = $worksheetId;

			}
			

			// if($this->session->userdata('user_role') == 2) {

			// 	$studentId = array();

			// 	$studentId[] = $this->session->userdata('user_id');

			// 	if ($this->model_quiz->assign_student($worksheetId, $studentId)) {

			// 		foreach($studentId as $student_id) {

			// 			//send notification email to the student
		
			// 			$this->load->library('email');
		
			// 			$this->email->from('noreply@easylearn.website', "Smartjen");

			// 			$this->email->Subject('SmartJen - Quiz Assignment Notification');

			// 			$user_info = $this->model_users->get_user_info($student_id);

			// 			$this->email->to($user_info->email, $user_info->fullname);

			// 			$message = "<p>Dear " . $user_info->fullname . ", </p>";

			// 			$message .= "<p>A quiz has been assigned to you by " . $user_info->fullname . ". Please login to <a href='".base_url()."'>https://smartjen.com</a> to complete your quiz now!</p>";

			// 			$message .= "<br><br><p>SmartJen</p>";

			// 			$this->email->message($message);

			// 			$this->email->send();
			// 		}

			// 		$this->session->set_userdata('profileMessageSuccess', true);
	
			// 		$this->session->set_userdata('profileMessage', 'Worksheet successfully updated.');
	
			// 	} else {
	
			// 		$this->session->set_userdata('profileMessageSuccess', 0);
	
			// 		$this->session->set_userdata('profileMessage', 'Error in assigning worksheet, please try again later');
	
			// 	}
				
	
			// 	redirect(base_url().'profile');
			// }

            $assigned_tutors = $this->model_quiz->get_assigned_list($worksheetId);

			$my_tutors = array();
			// $my_tutors = array();

			// if($this->session->userdata('user_role') == '1') {

			// 	$my_students[] = $this->model_users->get_student_list($this->session->userdata('user_id'));
			// } else {

			// 	$my_students[] = $this->model_users->get_children_list($this->session->userdata('user_id'));
			// }

			$my_tutors[] = $this->model_users->get_tutor_list($this->session->userdata('admin_id'));

            $not_assigned_tutors = array();

            $assigned_tutors_ids = array();

            foreach ($assigned_tutors as $assigned) {

                $assigned_tutors_ids[] = $assigned->id;
            }



            foreach ($my_tutors as $temp) {
	            for ($i=0; $i<count($temp); $i++){
	                if (!in_array($temp[$i]->id, $assigned_tutors_ids)) {

	                    $not_assigned_tutors[] = $temp;

					}
				}
			}

            $data['my_students'] = $my_tutors;

			$data['not_assigned_students'] = $not_assigned_tutors;

			$data['assigned_students'] = $assigned_tutors;

            $data['content'] = 'administrator/administrator_assignWorksheet';

            $this->load->view('include/master_view', $data);

        }

	}
	
	private function check_worksheet_owner($worksheetId, $redirect_link) {
        //if user is logged in, check if the worksheetId is tie to the user
        if ($this->session->userdata('is_admin_logged_in') == 1) {
            $this->load->model('model_worksheet');
            $ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $this->session->userdata('admin_id'));
            //if worksheet is not tie to user, redirect
            if (!$ownedByUser) {
                redirect($redirect_link);
            }
        } else {
            redirect($redirect_link);
        }
	}
	
	public function pagination($rowno){
		$this->load->model('model_users');
		$this->load->library('pagination');

		// Row per page
		$rowperpage = 10;

		// Row position
		$rowno = ($rowno - 1) * $rowperpage;

		// Get the selected id
		$keywords = array();
		$keywords = $this->input->post('exclude');

		if (!$keywords){
			$keywords[] = 'NULL';
		} else {
			$keywords = $this->input->post('exclude');
		}
		// if($this->session->userdata('user_role') == '1'){
			// All records count
			$allcount = $this->model_users->get_tutor_list_count_all($this->session->userdata('admin_id'),$keywords);

			// Get records
			$users_record = $this->model_users->get_tutor_list_fetch_details($this->session->userdata('admin_id'),$keywords,$rowperpage,$rowno);
		// } else {
		// 	// All records count
		// 	$allcount = $this->model_users->get_children_list_count_all($this->session->userdata('user_id'),$keywords);

		// 	// Get records
		// 	$users_record = $this->model_users->get_children_list_fetch_details($this->session->userdata('user_id'),$keywords,$rowperpage,$rowno);
		// }
		

		// Pagination Configuration
		$config = array();
		$config['base_url'] = base_url() . 'administrator/pagination';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;

		// pagination config
		$config['full_tag_open']    = '<ul class="pagination">';
		$config['full_tag_close']   = '</ul>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item-active"><span class="page-link">';
		$config['cur_tag_close']    = '</span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close']  = '</span></li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close']  = '</span></li>';

		// Initialize
		$this->pagination->initialize($config);

		// Initialize $data Array
		$data['pagination'] = $this->pagination->create_links();
		$data['result'] = $users_record;
		$data['row'] = $rowno;
		echo json_encode($data);
	}

	public function paginations($rowno)
	{
		$this->load->model('model_users');
		$this->load->library('pagination');

		// Row per page
		$rowperpage = 10;

		// Row position
		$rowno = ($rowno - 1) * $rowperpage;

		// Get the selected id
		$keywords = array();
		$keywords[] = $this->input->post('exclude');

		// All records count
		$allcount = $this->model_users->get_tutor_list_count_all($this->session->userdata('user_id'),$keywords);

		// Get records
		$users_record = $this->model_users->get_tutor_list_fetch_details($this->session->userdata('user_id'),$keywords,$rowperpage,$rowno);

		// Get All Records
		$all_record = $this->model_users->get_tutor_list_fetch_details($this->session->userdata('user_id'),$keywords, NULL, NULL);

		// Pagination Configuration
		$config = array();
		$config['base_url'] = base_url() . 'administrator/paginations';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;

		// pagination config
		$config['full_tag_open']    = '<ul class="pagination">';
		$config['full_tag_close']   = '</ul>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item-active"><span class="page-link">';
		$config['cur_tag_close']    = '</span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close']  = '</span></li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close']  = '</span></li>';

		// Initialize
		$this->pagination->initialize($config);

		// Initialize $data Array
		$data['paginations'] = $this->pagination->create_links();
		$data['result'] = $users_record;
		$data['row'] = $rowno;
		$data['all_result'] = $all_record;
		echo json_encode($data);
	}

	public function worksheet_list(){

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


		if ($this->session->userdata('is_admin_logged_in') == 1) {

			$worksheets = $this->model_worksheet->get_worksheets($this->session->userdata('admin_id'), Null);

			$mock_exams = $this->model_worksheet->get_mock_exam_worksheets($this->session->userdata('admin_id'));

	        // $data['pagination'] = $this->pagination->create_links();


			foreach ($worksheets AS $worksheet) {

				// $worksheet->slug = create_slug($worksheet->worksheet_name);

				$worksheet->subject_name = $this->model_worksheet->get_subject_name($worksheet->admin_subject_type);

			}

			$data['worksheets'] = $worksheets;

			$data['mock_exams'] = $mock_exams;

		} 

		$data['content'] = 'administrator/administrator_worksheet_list';
		
		$this->load->view('include/master_view', $data);
	}

	public function regenerateQuestion_admin() {
		
		if ($this->input->is_ajax_request()) {
			
			// $this->load->model('model_question');

			$currentQuestionNumber = $this->input->post('quesNum');
			
			$newQuestion = $this->model_admin->get_new_unique_question($currentQuestionNumber); //to replace current question
			//need to put in question answers here
			
			$answerOptions = $this->model_admin->get_answer_option_from_question_id($newQuestion->question_id);

			$correctAnswer = $this->model_admin->get_answer_text_from_answer_id($this->model_admin->get_correct_answer_from_question_id($newQuestion->question_id));

			$category = $this->model_admin->get_category_from_question_id($newQuestion->question_id);

			$substrand = $this->model_admin->get_substrand_from_question_id($newQuestion->question_id);

			$sub_question = $this->model_admin->sub_question($newQuestion->question_id);

			$strategy = $this->model_admin->get_strategy_from_question_id($newQuestion->question_id);

			$correctAnswerOptionNum = 0;

			$i = 1;

			foreach ($answerOptions as $answer) {

				if (strcmp($answer->answer_text, $correctAnswer) == 0) {

					$correctAnswerOptionNum = $i;

					break;

				}

				$i++;

			}

			$question = array();

			$question['question'] = $newQuestion;

			$question['sub_question'] = $sub_question;

			$question['answerOption'] = $answerOptions;

			$question['strategy'] = $strategy;

			$question['que_type'] = $this->session->userdata('worksheetQueType');

			$question['correctAnswer'] = $correctAnswer;

			$question['category'] = $category;

			$question['substrand'] = $substrand;

			$question['correctAnswerOptionNum'] = $correctAnswerOptionNum;

			$question['imageUrl'] = $newQuestion->branch_image_url;
			
			echo json_encode($question);

		} else {

			redirect('404');

		}

	}

	public function worksheet($worksheetId) {

		$this->load->model('model_question');

		$adminId = $this->session->userdata('admin_id');

		$worksheetName = $this->model_worksheet->get_worksheet_name_from_id($worksheetId);
		
		$worksheetSubject = $this->model_worksheet->get_worksheet_subject_id($worksheetId);

		$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);
		
		$questionList = array();
		$substrandList = array();
		$categoryList = array();
		$strategyList = array();


		foreach ($questions->result() AS $question) {
			$questionDetail = $this->model_question->get_question_from_id($question->admin_question_id);
			$questionList[$question->admin_question_number] = $questionDetail;
			$substrandList[] = $this->model_question->get_substrand_from_question_id($question->admin_question_id);
			$categoryList[] = $this->model_question->get_category_from_question_id($question->admin_question_id);
			$strategyList[] = $this->model_question->get_strategy_from_question_id($question->admin_question_id);
		}

		// have to hack this to get the sequence correct

		$question_order = array();

		for ($i = 1; $i <= count($questionList); $i++) {

			$question_order[] = $questionList[$i];

		}

		$answerList = $this->model_question->get_answer_list($question_order);

		$data['content'] = 'administrator/administrator_view_worksheet';

		$data['questionList'] = $questionList;
		$data['answerList'] = $answerList;
		$data['que_type'] = $this->model_worksheet->get_question_type_from_requirement($worksheetId);
		$data['worksheetName'] = $worksheetName;
		$data['worksheetId'] = $worksheetId;
		$data['substrandList'] = $substrandList;
		$data['categoryList'] = $categoryList;
		$data['strategyList'] = $strategyList;
		$data['admin_id'] = $adminId;
		$data['worksheetSubject'] = $worksheetSubject;
		$this->load->view('include/master_view', $data);

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
		redirect(base_url().'administrator/worksheet_list');
	}


	// ANOM START
	public function student_profile($userId) {

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
		
		$data['user_id'] = $userId;
		$data['schools'] = $schools;
		$data['levels'] = $levels;
		$data['stuLevel'] = $stuLevel;
		$data['stuSchool'] = $stuSchool;
		$data['profile_email']= $userEmail;
		$data['profile_mobile']= $userMobile;
		$data['profile_username'] = $user_info->username;
		$data['profile_fullName'] = $user_info->fullname;
		$data['profile_picture'] = $user_info->profile_pic;

        $data['page'] = 'edit-student-profile';
        $data['content'] = 'administrator/administrator_student_update_profile';
        $this->load->view("include/master_view.php", $data);
	}

	function setActiveStudent(){
		if($this->model_admin->setActiveStudent() > 0){
            $data['msg'] = 'success';
        }else{
            $data['msg'] = 'failed';
        }
        echo json_encode($data);
	}

	function setActiveTutor(){
		if($this->model_admin->setActiveTutor() > 0){
            $data['msg'] = 'success';
        }else{
            $data['msg'] = 'failed';
        }
        echo json_encode($data);
	}

	function updateStudentProfile(){
		if($this->model_admin->updateStudentProfile() > 0){
            $data['msg'] = 'success';
        }else{
            $data['msg'] = 'failed';
        }
        echo json_encode($data);
	}

	public function change_student_password() {
		$userId = $this->uri->segment(3);
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

		$data['user_id'] = $userId;
		$data['page'] = 'change-student-password';
        $data['content'] = "administrator/administrator_student_update_profile";
        $this->load->view("include/master_view.php", $data);
	}

	public function getStudentClass($userId){
		$result['data'] = $this->model_admin->getStudentClass($userId);
		echo json_encode($result);
	}

	public function getTutorClass($userId){
		$result['data'] = $this->model_admin->getTutorClass($userId);
		echo json_encode($result);
	}


	// ANOM END


	public function tutor_profile() {

        $userId = $this->uri->segment(3);

        $post = $this->input->post();

        if (isset($post) && empty($post) === false) {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('profile_fullName', 'Fullname', 'required|trim');

            $this->form_validation->set_rules('profile_agency_link', 'Agency Link', 'valid_url|max_length[100]');

            if ($this->form_validation->run()) {

                if ($this->model_admin->update_tutor_info($userId)) {

					$this->session->set_flashdata('update_success', 'Successfully updated profile info.');

					redirect(base_url() . 'administrator/tutor_profile/'.$userId);

                } else {

					$this->session->set_flashdata('update_error', 'Error in updating profile info. Please try again later or contact administrator at admin@smartjen.sg');

					redirect(base_url() . 'administrator/tutor_profile/'.$userId);

				}
				
            } else {

				$this->session->set_flashdata('update_error', validation_errors());

				redirect(base_url() . 'profile/edit');

            }

        }

        $user_info = $this->model_users->get_user_info($userId);

        $data['tutor_details'] = $user_info;

        $data['profile_profession'] = $this->model_users->get_profession();

        $data['profile_chosen_profession'] = $this->model_users->get_profession_name_from_id($user_info->profession);

        $data['profile_specialization'] = $this->model_users->get_specialization_list();

        $data['profile_chosen_specialization'] = $this->model_users->get_tutor_specialization($userId);

        $data['content'] = 'administrator/administrator_tutor_update_profile';

        $this->load->view("include/master_view.php", $data);
	}	

	public function change_password() {

		$userId = $this->uri->segment(3);

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

		$data['user_id'] = $userId;

        $data['content'] = "administrator/administrator_change_password";

        $this->load->view("include/master_view.php", $data);
	}


	public function getSubjectList(){
		echo json_encode($this->M_question->get_subject());
	}


	public function getLevelBySubject($subject_name){
		echo json_encode($this->M_question->get_level_by_subject_name($subject_name));
	}


	public function getAnswerType($question_type){
		echo json_encode($this->M_question->get_answer_type_by_question_type($question_type));
	}


	public function uploadImageArticle(){
		// var_dump($_FILES['upload']['name']);
		if(isset($_FILES['upload']['name'])){
			$file = $_FILES['upload']['tmp_name'];
			$file_name = $_FILES['upload']['name'];
			$file_name_array = explode(".", $file_name);
			$extension = end($file_name_array);
			$new_image_name = rand() . '.' . $extension;
			chmod('img/articleImage', 0777);
			$allowed_extension = array("jpg", "gif", "png");
			if(in_array($extension, $allowed_extension)){
				move_uploaded_file($file, 'img/articleImage/' . $new_image_name);
				$function_number = $_GET['CKEditorFuncNum'];
				$url = 'img/articleImage/' . $new_image_name;
				// $message = '';
				echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, $url);</script>";
				// echo "<script type='text/javascript'>$('.cke_dialog_ui_input_text').val($url);</script>";
			}
		}
	}


	public function ck_upload_image($dir){
		// Define file upload path
		if($dir == 1){			
			$upload_dir = array( 
				'img'=> 'img/articleImage/', 
			);

			$target_dir = './img/articleImage/';
            if(!file_exists($target_dir)){
                mkdir($target_dir, 0777);
            }
		}else if($dir == 2){
			$upload_dir = array( 
				'img'=> 'img/questionImage/', 
			);
			$target_dir = './img/questionImage/';
            if(!file_exists($target_dir)){
                mkdir($target_dir, 0777);
            }
		}else if($dir == 3){
			$upload_dir = array( 
				'img'=> 'img/lessonsImage/', 
			);
			$target_dir = './img/lessonsImage/';
            if(!file_exists($target_dir)){
                mkdir($target_dir, 0777);
            }
		}
		
		// Allowed image properties  
		$imgset = array( 
			'maxsize' => 2000, 
			'maxwidth' => 1024, 
			'maxheight' => 800, 
			'minwidth' => 10, 
			'minheight' => 10, 
			'type' => array('bmp', 'gif', 'jpg', 'jpeg', 'png','BMP', 'GIF', 'JPG', 'JPEG', 'PNG'), 
		); 
		
		// If 0, will OVERWRITE the existing file 
		define('RENAME_F', 1); 
		
		/** 
		 * Set filename 
		 * If the file exists, and RENAME_F is 1, set "img_name_1" 
		 * 
		 * $p = dir-path, $fn=filename to check, $ex=extension $i=index to rename 
		 */ 
		function setFName($p, $fn, $ex, $i){ 
			if(RENAME_F ==1 && file_exists($p .$fn .$ex)){ 
				return setFName($p, F_NAME .'_'. ($i +1), $ex, ($i +1)); 
			}else{ 
				return $fn .$ex; 
			} 
		} 
		
		$re = ''; 
		if(isset($_FILES['upload']) && strlen($_FILES['upload']['name']) > 1) { 
		
			define('F_NAME', preg_replace('/\.(.+?)$/i', '', basename($_FILES['upload']['name'])));   
		
			// Get filename without extension 
			$sepext = explode('.', strtolower($_FILES['upload']['name'])); 
			$type = end($sepext);    /** gets extension **/ 
			
			// Upload directory 
			$upload_dir = in_array($type, $imgset['type']) ? $upload_dir['img'] : $upload_dir['audio']; 
			$upload_dir = trim($upload_dir, '/') .'/'; 
		
			// Validate file type 
			if(in_array($type, $imgset['type'])){ 
				// Image width and height 
				list($width, $height) = getimagesize($_FILES['upload']['tmp_name']); 
		
				if(isset($width) && isset($height)) { 
					if($width > $imgset['maxwidth'] || $height > $imgset['maxheight']){ 
						$re .= '\\n Width x Height = '. $width .' x '. $height .' \\n The maximum Width x Height must be: '. $imgset['maxwidth']. ' x '. $imgset['maxheight']; 
					} 
		
					if($width < $imgset['minwidth'] || $height < $imgset['minheight']){ 
						$re .= '\\n Width x Height = '. $width .' x '. $height .'\\n The minimum Width x Height must be: '. $imgset['minwidth']. ' x '. $imgset['minheight']; 
					} 
		
					if($_FILES['upload']['size'] > $imgset['maxsize']*1000){ 
						$re .= '\\n Maximum file size must be: '. $imgset['maxsize']. ' KB.'; 
					} 
				} 
			}else{ 
				$re .= 'The file: '. $_FILES['upload']['name']. ' has not the allowed extension type.'; 
			} 
			
			// File upload path 
			$file_name = microtime(true);
            $file_name = str_replace('.', '', $file_name);
            $file_name = date('Y-m-d').'-'.$file_name.'.'.$type;
			// $f_name = setFName($_SERVER['DOCUMENT_ROOT'] .'/'. $upload_dir, F_NAME, ".$type", 0); 
			$uploadpath = $upload_dir . $file_name; 
		
			// If no errors, upload the image, else, output the errors 
			if($re == ''){ 
				if(move_uploaded_file($_FILES['upload']['tmp_name'], $uploadpath)) { 
					$CKEditorFuncNum = $_GET['CKEditorFuncNum']; 
					$url = base_url() . $upload_dir . $file_name; 
					$msg = F_NAME .'.'. $type .' successfully uploaded: \\n- Size: '. number_format($_FILES['upload']['size']/1024, 2, '.', '') .' KB'; 
					$re = in_array($type, $imgset['type']) ? "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>":'<script>var cke_ob = window.parent.CKEDITOR; for(var ckid in cke_ob.instances) { if(cke_ob.instances[ckid].focusManager.hasFocus) break;} cke_ob.instances[ckid].insertHtml(\' \', \'unfiltered_html\'); alert("'. $msg .'"); var dialog = cke_ob.dialog.getCurrent();dialog.hide();</script>'; 
				}else{ 
					$re = '<script>alert("Unable to upload the file")</script>'; 
				} 
			}else{ 
				$re = '<script>alert("'. $re .'")</script>'; 
			} 
		} 
		
		// Render HTML output 
		@header('Content-type: text/html; charset=utf-8'); 
		echo $re;
	}	


	// user grid view
	public function getUser($user_type = "tutor_list", $page = 1){
		$start = (($page <= 0 ? 1 : $page) - 1) * 10;
		$getData = $this->model_admin->user_listing($start);
		// foreach($getData['data'] as $row){
		// 	$row->level_name = $this->M_question->get_level_by_id($row->level_id);
		// 	$row->subject_name = $this->M_question->get_subject_by_id($row->subject_type);
		// 	$row->topic_name = $this->M_question->get_topic_by_id($row->topic_id)['topic_name'];
		// 	$row->substrand_name = $this->M_question->get_topic_by_id($row->topic_id)['substrand_name'];
		// 	$row->strategy_name = $this->M_question->get_strategy_by_id($row->strategy_id);

		// 	$row->has_instruction = $this->model_question->get_question_header_from_question_id($row->question_id, 'instruction');
		// 	$row->has_article = $this->model_question->get_question_header_from_question_id($row->question_id, 'article');

		// 	$row->total_question = $this->M_question->get_reference_by_id($row->question_id);
		// }

		// $answerList = $this->model_question->get_answer_list($getData['data']);
		// $questionContents = $this->model_question->get_question_content_list($getData['data']);		
		// $data['questionContents'] = $questionContents;
		// $data['answerList'] = $answerList;

        $data['data'] = $getData['data'];
        if(isset($data['total_rows']) && !empty($data['total_rows'] == TRUE)) {
            $total_rows = $getData['total_rows'];
        } else {
            $total_rows = 0;
        }       
        // pagination settings      
        $this->load->library('pagination');
        
        $this->pgconfig = array(
            'first_link' => ' First ',
            'last_link' => ' Last ',
            'per_page' => 10,
            'first_url' => '1',
            'use_page_numbers' => true,
            'num_links' => 3
        );
        $data['total_rows'] = $getData['total_rows'];
        //$data['duration'] = $getData['duration'];
        $this->pgconfig['total_rows'] = $getData['total_rows'];
        $this->pgconfig['uri_segment'] = 3;
        $this->pgconfig['full_tag_open'] = '<ul class="pagination" id="pag-addMore">';
        $this->pgconfig['full_tag_close'] = '</ul>';
        $this->pgconfig['first_link'] = false;
        $this->pgconfig['last_link'] = false;
        $this->pgconfig['first_tag_open'] = '<li>';
        $this->pgconfig['first_tag_close'] = '</li>';
        $this->pgconfig['prev_link'] = '&laquo';
        $this->pgconfig['prev_tag_open'] = '<li class="prev">';
        $this->pgconfig['prev_tag_close'] = '</li>';
        $this->pgconfig['next_link'] = '&raquo';
        $this->pgconfig['next_tag_open'] = '<li>';
        $this->pgconfig['next_tag_close'] = '</li>';
        $this->pgconfig['last_tag_open'] = '<li>';
        $this->pgconfig['last_tag_close'] = '</li>';
        $this->pgconfig['cur_tag_open'] = '<li class="active"><a href="#">';
        $this->pgconfig['cur_tag_close'] = '</a></li>';
        $this->pgconfig['num_tag_open'] = '<li>';
        $this->pgconfig['num_tag_close'] = '</li>';
        $this->pagination->initialize($this->pgconfig);
		$data['pagination'] = $this->pagination->create_links();
		
        $this->load->view('administrator/list-question/user_list', $data); 
	}


}