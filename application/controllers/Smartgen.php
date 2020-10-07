<?php

/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */


defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

class Smartgen extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('model_worksheet');
		$this->load->model('model_question');
		$this->load->model('model_users');
	}
	
	public function index() {
		// $this->designExam();
		if(BRANCH_TID==1)
			$this->designTID();
		else
			$this->worksheetMode();
	}

	// Justin Start

	// choose exam mode or quiz mode or practice mode
	function worksheetMode() {
		$data['content'] = "smartgen/smartgen_mode";
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

	
	function designExam() {
		$logged_in = $this->session->userdata('is_logged_in');

		$data['is_logged_in'] = $logged_in;	
		$data['page'] = 'worksheet-exam-mode';	
		$data['content'] = 'smartgen/smartgen_exam';
		$data['levels']  = $this->model_question->get_level_list(2);
		$data['substrands'] = $this->model_question->get_substrand_list(NULL, 2);
		$data['topics']  = $this->model_question->get_topic_list(1);
		$data['strategys'] = $this->model_question->get_strategy_list();

		$subId = array();
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
			$topicArray = array();
			$topicArray[] = $selectedTopic[0];
			$data['selectedTopic'] = $topicArray;
		}

		if (isset($selectedSubstrand)) {
			$substrandArray = array();
			$substrandArray[] = $selectedSubstrand[0];
			$data['selectedSubstrand'] = $substrandArray;
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


	public function createGenerateExam($worksheetId = NULL, $more_question = false, $exclude = array(), $start = 0) {
		if (isset($worksheetId) && empty($worksheetId) === false) {
			$this->check_worksheet_owner($worksheetId, '404');
			$data['worksheetId'] = $worksheetId;
		}
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
		
		
		if (isset($postData) && empty($postData) === false) {
			$questionList = array();
			$answerList = array();
			$categoryList = array();
			$substrandList = array();
			$subquestionList = array();
			$strategyList = array();

			//submit from regenerate all button
				if (isset($postData['regenerateWorksheet']) && empty($postData['regenerateWorksheet']) === false) {
					$postData['gen_num_of_question'] = $this->session->userdata('ExamNumOfQuestion');
					$postData['gen_difficulties']    = $this->session->userdata('ExamDifficulty');
					$postData['gen_level']           = $this->session->userdata('ExamLevel');
					$postData['gen_topic']           = $this->session->userdata('ExamTopic');
					$postData['gen_substrand']       = $this->session->userdata('ExamSubstrand');
					$postData['gen_subject']        = $this->session->userdata('ExamSubject');
				} else {
					//save requirement in session

					$sessionArray = array(
						'ExamNumOfQuestion' => $this->input->post('gen_num_of_question'),
						'ExamDifficulty'    => $this->input->post('gen_difficulties'),
						'ExamLevel'         => $this->input->post('gen_level'),
						'ExamTopic'         => $this->input->post('gen_topic'),
						'ExamSubstrand'     => $this->input->post('gen_substrand'),
						'ExamSubject'  => $this->input->post('gen_subject')
					);
					$this->session->set_userdata($sessionArray);
				}
				
				if($more_question == false){
					$this->load->model('model_worksheet');					
					$this->model_worksheet->save_worksheet_requirement_exam($postData);					
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

		redirect(base_url() . 'smartgen/generateExam');
	}

	public function generateExam(){		
		$questionList = $this->model_question->get_exam_list();		

		if(isset($questionList) && empty($questionList) == FALSE) {
			foreach ($questionList as $question) {
				$categoryList[] = $this->model_question->get_category_from_question_id($question->question_id, TRUE);
				$strategyList[] = $this->model_question->get_strategy_from_question_id($question->question_id);
				$substrandList[] = $this->model_question->get_substrand_from_question_id($question->question_id);
				$subquestionList[] = $this->model_question->sub_question($question->question_id);
			}
		} else {
			$categoryList[] = '';
			$strategyList[] = '';
			$substrandList[] = '';
			$subquestionList[] = '';
		}

		$data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		if ($this->session->userdata('is_logged_in') == 1) {
			$data['user_id'] = $this->session->userdata('user_id');
		} else {
			$data['user_id'] = 0;   // stands for visitor
		}

		$data['page'] = 'generate-exam';
		//get the answers from here
		$answerList = $this->model_question->get_answer_list($questionList);
		$questionContents = $this->model_question->get_question_content_list($questionList);
		$data['questionContents'] = $questionContents;
		$data['answerList'] = $answerList;
		$data['questionList'] = $questionList;
		$data['categoryList'] = $categoryList;
		$data['substrandList'] = $substrandList;
		$data['subquestionList'] = $subquestionList;
		$data['strategyList'] = $strategyList;
		$requirement_id = $this->model_question->get_question_type_from_requirement_id($this->session->userdata('requirementId'));


		//show worksheet's criteria in smartgen_addMore 
		$sess_worksheet_topic = $this->session->userdata('ExamTopic');
		$sess_worksheet_lvl = $this->session->userdata('ExamLevel');
		$sess_worksheet_substr = $this->session->userdata('ExamSubstrand');
		$sess_worksheet_subject = $this->session->userdata('ExamSubject');
		
		$sess_worksheet_str = $this->session->userdata('ExamStrategy');
		$worksheet_lvl = $this->model_worksheet->get_worksheet_level($sess_worksheet_lvl, $sess_worksheet_subject);
		$worksheet_substr = $this->model_worksheet->get_worksheet_substrands($sess_worksheet_substr);
		$worksheet_topic = $this->model_worksheet->get_worksheet_topics($sess_worksheet_topic);
		$worksheet_strategy = $this->model_worksheet->get_worksheet_strategys($sess_worksheet_str);
		
		$data['worksheet_lvl'] = $worksheet_lvl;
		$data['worksheet_substr'] = $worksheet_substr;
		$data['worksheet_topic'] = $worksheet_topic;
		$data['worksheet_strategy'] = $worksheet_strategy;
		$data['que_type'] = $requirement_id;
				
		$data['content'] = 'smartgen/smartgen_generateExam';
		$this->load->view('include/master_view', $data);
	}

	// Justin End



	function designTID() {
		$logged_in = $this->session->userdata('is_logged_in');

		$data['is_logged_in'] = $logged_in;	
		$data['page'] = 'worksheet-tid-mode';	
		$data['content'] = 'smartgen/smartgen_tid';
		if(BRANCH_ID == 13) {
			$data['levels'] = $this->model_question->get_level_list_tid();
		} else {
			$data['levels']  = $this->model_question->get_level_list(2);
		}
		$data['substrands'] = $this->model_question->get_substrand_list(NULL, 2);
		$data['topics']  = $this->model_question->get_topic_list(1);
		$data['strategys'] = $this->model_question->get_strategy_list();
		$data['sess_user_level'] = $this->session->userdata('user_level');
		$subId = array();
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
			$topicArray = array();
			$topicArray[] = $selectedTopic[0];
			$data['selectedTopic'] = $topicArray;
		}

		if (isset($selectedSubstrand)) {
			$substrandArray = array();
			$substrandArray[] = $selectedSubstrand[0];
			$data['selectedSubstrand'] = $substrandArray;
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

	public function createGenerateTID($worksheetId = NULL, $more_question = false, $exclude = array(), $start = 0) {
		if (isset($worksheetId) && empty($worksheetId) === false) {
			$this->check_worksheet_owner($worksheetId, '404');
			$data['worksheetId'] = $worksheetId;
		}
		$postData = $this->input->post();
		// array_debug($postData); exit;
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
		
		
		if (isset($postData) && empty($postData) === false) {
			$questionList = array();
			$answerList = array();
			$categoryList = array();
			$substrandList = array();
			$subquestionList = array();
			$strategyList = array();

			//submit from regenerate all button
			if (isset($postData['regenerateWorksheet']) && empty($postData['regenerateWorksheet']) === false) {
				$postData['gen_num_of_question'] = $this->session->userdata('TIDNumOfQuestion');
				$postData['gen_difficulties']    = $this->session->userdata('TIDDifficulty');
				$postData['gen_level']           = $this->session->userdata('TIDLevel');
				$postData['gen_topic']           = $this->session->userdata('TIDTopic');
				$postData['gen_ability']       	 = $this->session->userdata('TIDAbility');
				$postData['gen_que_type']        = $this->session->userdata('TIDQueType');
				$postData['gen_operator']      	 = $this->session->userdata('TIDOperator');
				$postData['gen_que_bank']        = $this->session->userdata('TIDQueBank');
			} else {
				//save requirement in session
				$sessionArray = array(
					'TIDNumOfQuestion' => $this->input->post('gen_num_of_question'),
					'TIDDifficulty'    => $this->input->post('gen_difficulties'),
					'TIDAbility'       => $this->input->post('gen_ability'),
					'TIDLevel'         => $this->input->post('gen_level'),
					'TIDTopic'         => $this->input->post('gen_topic'),
					'TIDQueType'       => $this->input->post('gen_que_type'),
					'TIDOperator'       => $this->input->post('gen_operator'),
					'TIDQueBank'  	   => 'TID'
				);
				$this->session->set_userdata($sessionArray);
			}
			// array_debug($sessionArray); exit;
			if($more_question == false){
				$this->load->model('model_worksheet');					
				$this->model_worksheet->save_worksheet_requirement_tid($postData);					
			}								
		} else {
			redirect(base_url() . 'smartgen');
		}
		redirect(base_url() . 'smartgen/generateTID');
	}

	public function generateTID(){		
		$questionList = $this->model_question->get_tid_list();		

		if(isset($questionList) && empty($questionList) == FALSE) {
			foreach ($questionList as $question) {
				$subquestionList[] = $this->model_question->sub_question($question->question_id);
			}
		} else {
			$subquestionList[] = '';
		}

		$data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		if ($this->session->userdata('is_logged_in') == 1) {
			$data['user_id'] = $this->session->userdata('user_id');
		} else {
			$data['user_id'] = 0;   // stands for visitor
		}

		$data['page'] = 'generate-tid';
		//get the answers from here
		$answerList = $this->model_question->get_answer_list($questionList);
		$questionContents = $this->model_question->get_question_content_list($questionList);
		$data['questionContents'] = $questionContents;
		$data['answerList'] = $answerList;
		$data['questionList'] = $questionList;
		$data['subquestionList'] = $subquestionList;
		$requirement_id = $this->model_question->get_question_type_from_requirement_id($this->session->userdata('requirementId'));

		//show worksheet's criteria in smartgen_addMore 
		$sess_worksheet_topic = $this->session->userdata('TIDTopic');
		$sess_worksheet_lvl = $this->session->userdata('TIDLevel');
		$worksheet_lvl = $sess_worksheet_lvl; //$this->model_worksheet->get_worksheet_level($sess_worksheet_lvl, $sess_worksheet_subject);
		$worksheet_topic = $this->model_worksheet->get_worksheet_topics_tid($sess_worksheet_topic);
		
		$data['worksheet_lvl'] = $worksheet_lvl;
		$data['worksheet_topic'] = $worksheet_topic;
		$data['que_type'] = $requirement_id;
				
		$data['content'] = 'smartgen/smartgen_generateTID';
		$this->load->view('include/master_view', $data);
	}

	public function regenerateAllQuestionTID($requirement_id){
        $this->model_worksheet->regenerateAllQuestionTID($requirement_id);
        redirect(base_url() . 'smartgen/generateTID');
        
    }

    public function regenerateQuestionTID($id, $questionType){
        $question_id = $this->model_question->regenerateQuestionTID($id, $questionType);
        if($question_id=="") {
        	$data['error_gen'] = "There is no corresponding question data"; 
        } else {
        	$data['error_gen'] = "";
	        $data['question'] = $this->model_question->getDetailQuestion($question_id);
	        $data['answer'] = $this->model_question->getAnswers($question_id);
	        $data['subquestion'] = $this->model_question->getSubquestion($question_id);
    	}
        echo json_encode($data);
    }

     public function getHeaderEdit($id){
        $row = $this->model_worksheet->getHeaderEdit($id);
        $result['reqTopic'] = $row['reqTopic'];
        $result['reqAbility'] = $row['reqAbility'];
        $result['reqDifficulty'] = $row['reqDifficulty'];
        echo json_encode($result);
    }

    function getMoreQuestionsTID($page = 1){        
        $start = (($page <= 0 ? 1 : $page) - 1) * 10;
        $getData = $this->model_question->getMoreQuestions($start);
        // array_debug($getData); exit;
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
        $this->load->view('smartgen/smartgen_tidAddMore', $data);      
    }

    public function updateQuestion(){
        if($this->model_question->updateQuestion() > 0 ){
            $questionArray = $this->session->userdata('questionArray');
            $questionArray[$this->input->post('quesNum') - 1] = $this->input->post('question_id');
            $this->session->set_userdata('questionArray', $questionArray);
            $data['msg'] = 'success';
        }else{
            $data['msg'] = 'failed';
        }
        $data['question'] = $this->model_question->getDetailQuestion($this->input->post('question_id'));
        $data['answer'] = $this->model_question->getAnswers($this->input->post('question_id'));
        $data['subquestion'] = $this->model_question->getSubquestion($this->input->post('question_id'));
        echo json_encode($data);
    }

	public function designWorksheet($worksheetId = NULL) {
		$logged_in = $this->session->userdata('is_logged_in');
		$userId = $this->session->userdata('user_id');
		if (isset($worksheetId) && empty($worksheetId) === false) {
			//if user is logged in, check if the worksheetId is tie to the user
			if ($logged_in == 1) {
				$ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $userId);
				//if worksheet is not tie to user, redirect back without worksheetId
				if (!$ownedByUser) {
					redirect(base_url().'smartgen');
				}
				$data['worksheetId'] = $worksheetId;
			} else { //else redirect back without worksheetId
				redirect(base_url().'smartgen');
			}
		}

		$data['is_logged_in'] = $logged_in;
		$data['content'] = 'smartgen/smartgen_home';
		$data['page'] = 'worksheet-quiz-mode';
		$data['levels']  = $this->model_question->get_level_list(2);
		$data['substrands'] = $this->model_question->get_substrand_list(NULL, 2);
		$data['topics']  = $this->model_question->get_topic_list(1);
		$data['strategys'] = $this->model_question->get_strategy_list();
		$data['question_tags'] = $this->model_question->get_worksheetName_from_admin($userId);

		if($userId == 490 || $userId == 121) {
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


    private function check_worksheet_owner($worksheetId, $redirect_link) {
        //if user is logged in, check if the worksheetId is tie to the user
        if ($this->session->userdata('is_logged_in') == 1) {
            $this->load->model('model_worksheet');
            $ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $this->session->userdata('user_id'));
            //if worksheet is not tie to user, redirect
            if (!$ownedByUser) {
                redirect($redirect_link);
            }
        } else {
            redirect($redirect_link);
        }
    }


	public function recsysApi($username, $data = array()) {
		$this->load->library('jwt');
		$this->load->library('api');
		$data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		if ($this->session->userdata('is_logged_in') == 1) {
			$data['user_id'] = $this->session->userdata('user_id');
		} else {
			$data['user_id'] = 0;   // stands for visitor
		}

		$stu_id = $this->model_users->get_user_id_from_email_or_username($username);
		$stu_info = $this->model_users->get_student_info($stu_id);
		$stu_lvl = $stu_info->level_id;

		$url = "http://recsys.smartjen.com/question/recsys/?f&format=json&num_questions=10&student_id=" . $stu_id . "&num_weakest_kp=4";
		// $url = "http://recsys.smartjen.com/question/recsys/?f&format=json&lid=" . $stu_lvl . "&student_id=" . $stu_id . "&tid=" . $cat_id;
		// $url = "http://recsys.smartjen.com/question/recsys/?f&format=json&lid=6&student_id=123&tid=4";
		
		$user_id = $this->session->userdata('user_id');

		$user_array = array(
			// 'username' => $username,
			'username' => 'klho'
		);

		$key = "Sm@rtJen2@!9!@#";
		$logged_user_token = $this->jwt->encode($user_array, $key);

		$headers = array(
			"Content-Type: application/json; charset=utf-8",
			"Authorization: Bearer ".$logged_user_token
		);

		$ch = curl_init();

		//Set the URL that you want to GET by using the CURLOPT_URL option.
		curl_setopt($ch, CURLOPT_URL, $url);
		
		//Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		//Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		// Set an array of HTTP header fields
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		//Execute the request.
		$response = curl_exec($ch);

		//Get the http code from cURL
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//Close the cURL handle.
		curl_close($ch);

		if($httpcode == 200){
			// $response = json_decode($response);

			$response = $this->isJson($response);

			if($response == null) {
				$questionArray = array();
			} else {
				$response = array_filter($response);
				$questionArray = $this->model_worksheet->get_recsys_question_list($response);
			}

			$answerList = array();
			$categoryList = array();
			$substrandList = array();
			$subquestionList = array();
			$strategyList = array();
			foreach ($questionArray as $question) {
				$categoryList[] = $this->model_question->get_category_from_question_id($question->question_id);
				$strategyList[] = $this->model_question->get_strategy_from_question_id($question->question_id);
				$substrandList[] = $this->model_question->get_substrand_from_question_id($question->question_id);
				$subquestionList[] = $this->model_question->sub_question($question->question_id);
			}
			
			$answerList = $this->model_question->get_answer_list($questionArray);

			$data['answerList'] = $answerList;
			$data['questionList'] = $questionArray;
			$data['categoryList'] = $categoryList;
			$data['substrandList'] = $substrandList;
			$data['subquestionList'] = $subquestionList;
			$data['strategyList'] = $strategyList;
			// array_debug($data);exit;
			$data['content'] = 'smartgen/smartgen_generateWorksheet';
			$this->load->view('include/master_view', $data);

		}else {
			$matches = array();
			if (preg_match('/<title>(.*?)<\/title>/', $response, $matches)) {
				$responses = $matches[1];
			} else {
				api_return('404 Not Found', 404);
			}
			api_return($responses, 502);
		}
	}

	private function isJson($string) {
		//decode the json string
		$result = json_decode($string);

		// use switch and check with the possible json error
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred
				break;
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
				break;
			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
				break;
			default:
				$error = 'Unknown JSON error occured.';
				break;
		}
	
		if ($error !== '') {
			// throw the Exception or exit // or whatever :)
			exit($error);
		}

		return $result;
	}

	public function generateWorksheet($worksheetId = NULL, $more_question = false, $exclude = array(), $start = 0, $is_admin = NULL) {
		
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

		if(in_array('is_admin', $this->session->userdata())) {

			$is_admin = $this->session->userdata('is_admin');

			$data['is_admin'] = $is_admin;

		} else {

			$data['is_admin'] = FALSE;

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
		$data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		
		if ($this->session->userdata('is_logged_in') == 1) {
			$data['user_id'] = $this->session->userdata('user_id');
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
				
				if($more_question == false)  {

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
		$sess_worksheet_subject = $this->session->userdata('worksheetSubject');
 		if($sess_worksheet_subject == '7') {
			$sess_worksheet_topic = array(1);
			$sess_worksheet_lvl = 1;
			$sess_worksheet_substr = array(1);
		} else {
			$sess_worksheet_topic = $this->session->userdata('worksheetTopic');
			$sess_worksheet_lvl = $this->session->userdata('worksheetLevel');
			$sess_worksheet_substr = $this->session->userdata('worksheetSubstrand');
			$sess_worksheet_tag = $this->session->userdata('worksheetTags');
		}

		$sess_worksheet_str = $this->session->userdata('worksheetStrategy');
		$worksheet_lvl = $this->model_worksheet->get_worksheet_level($sess_worksheet_lvl,$sess_worksheet_subject);
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
		
		if ($requirement_id == 1){
			$data['content'] = 'smartgen/smartgen_generateWorksheet';

		} else {
			$data['content'] = 'smartgen/smartgen_generateWorksheets';
		}
		
		// if($sess_worksheet_tag == 'all') {
		// 	if ($requirement_id == 1){
		// 		$data['content'] = 'smartgen/smartgen_generateWorksheet';

		// 	} else {
		// 		$data['content'] = 'smartgen/smartgen_generateWorksheets';
		// 	}
		// } else {
		// 	$data['content'] = 'smartgen/smartgen_generateWorksheets';
		// }
		$this->load->view('include/master_view', $data);

	}



	public function regenerateQuestion() {

		if ($this->input->is_ajax_request()) {

			$this->load->model('model_question');

			$currentQuestionNumber = $this->input->post('quesNum');

			$newQuestion = $this->model_question->get_new_unique_question($currentQuestionNumber); //to replace current question
			//need to put in question answers here
			
			$answerOptions = $this->model_question->get_answer_option_from_question_id($newQuestion->question_id);

			$correctAnswer = $this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($newQuestion->question_id));

			$category = $this->model_question->get_category_from_question_id($newQuestion->question_id);

			$substrand = $this->model_question->get_substrand_from_question_id($newQuestion->question_id);

			$sub_question = $this->model_question->sub_question($newQuestion->question_id);

			$strategy = $this->model_question->get_strategy_from_question_id($newQuestion->question_id);

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

	public function saveWorksheetTID() {
		$this->load->model('model_worksheet');
		$worksheetName = $this->input->post('worksheet_name');
		//proceed only if worksheetname is set
		if (isset($worksheetName) && empty($worksheetName) === false) {
			//actually login check is already done on the interface
			if ($this->session->userdata('is_logged_in') == 1) {
				$worksheetId = $this->model_worksheet->save_worksheetTID($worksheetName);
				if ($worksheetId) {
					$this->session->set_userdata('save_worksheet_success', "Worksheet saved");
					redirect(base_url()."smartgen/assignWorksheet/".$worksheetId);
				} else {
					//some error in saving worksheet, redirect for proper handling
					redirect('404');
				}
			} 
		} else {
			redirect(base_url()."smartgen/generateWorksheets");
		}
	}

	// WS TID
	public function getTIDList() {
		$postData = $this->input->post();
		$level = $postData['level'];
		echo json_encode($this->model_question->get_topiclevel_list($level));
	}

	public function getTIDAbilityList() {
		echo json_encode($this->model_question->get_ability_list());
	}

	public function getSubjectList() {
		echo json_encode($this->model_question->get_subject_list());
	}

	public function getLevelList() {
		echo json_encode($this->model_question->get_level_list());
	}


	public function getTopicList($substrand_id = NULL) {
		echo json_encode($this->model_question->get_topic_list($substrand_id));
	}


	public function getSubstrandList($strand_id = NULL) {
		echo json_encode($this->model_question->get_substrand_list($strand_id, 2));
	}


	// Worksheet Generator Design Start
	public function getSubjectLevelList() {
		echo json_encode($this->model_question->get_subjectlevel_list());
	}


	public function getHeuristicsList() {
		echo json_encode($this->model_question->get_heuristics_list());
	}


	public function getStrategyList() {
		echo json_encode($this->model_question->get_strategy_list($this->input->get('subject_id')));
	}


	public function worksheetGetSubstr() {
		echo json_encode($this->model_question->get_worksheet_substr($this->input->get('subject_id'), $this->input->get('level_name', FALSE)));
	}


	public function worksheetGetTopic() {
		echo json_encode($this->model_question->get_worksheet_topic($this->input->get('substrand_id', FALSE), $this->input->get('level_name', FALSE)));
	}


	public function worksheetGetHeuristic() {
		echo json_encode($this->model_question->get_worksheet_heuristic($this->input->get('subject_id'), $this->input->get('substrand_id', FALSE), $this->input->get('topic_id'), $this->input->get('level_name', FALSE)));
	}


	public function worksheetGetStrategy() {
		echo json_encode($this->model_question->get_worksheet_strategy($this->input->get('subject_id'), $this->input->get('substrand_id', FALSE), $this->input->get('topic_id'), $this->input->get('heuristic_id'), $this->input->get('level_name', FALSE)));
	}


	public function saveWorksheet() {
		$this->session->unset_userdata('is_admin');	
		$worksheetName = $this->input->post('worksheet_name');
		// $questionArray = $this->session->userdata('questionArray');
		// print_r($questionArray); exit;
		//proceed only if worksheetname is set
		if (isset($worksheetName) && empty($worksheetName) === false) {
			//actually login check is already done on the interface
			if ($this->session->userdata('is_logged_in') == 1) {
				$worksheetId = $this->model_worksheet->save_worksheet($worksheetName);
				if ($worksheetId) {
					$this->session->set_userdata('save_worksheet_success', "Worksheet saved");
					redirect(base_url()."smartgen/assignWorksheet/".$worksheetId);
				} else {
					//some error in saving worksheet, redirect for proper handling
					redirect('404');
				}			
			} 
		} else {
			redirect(base_url()."smartgen/generateWorksheets");
		}
	}


	public function saveExistingWorksheet($worksheetId) {
		$saveSuccess = $this->model_worksheet->save_existing_worksheet($worksheetId);
		if ($saveSuccess) {
			redirect(base_url().'smartgen/assignWorksheet/'.$worksheetId);
		} else {
            redirect('404');
		}
	}


    public function assignWorksheet($worksheetId) {
        $postData = $this->input->post();
        $this->load->model('model_users');
		$this->load->model('model_quiz');
		// array_debug($this->input->post());exit;
        if (isset($postData) && empty($postData) === false) {
            $assigned_students = (isset($postData['assigned_students']) && empty($postData['assigned_students']) === false)?$postData['assigned_students']:array();

            if ($this->model_quiz->assign_student($worksheetId, $assigned_students)) {



                //send notification email to the student

                $this->load->library('email');

                $tutor_name = $this->model_users->get_username_from_id($this->session->userdata('user_id'));


                foreach ($assigned_students as $assigned_id) {

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


            redirect(base_url().'profile');

        } else {



            if (isset($worksheetId) && empty($worksheetId) === false) {

                $this->check_worksheet_owner($worksheetId, '404');

                $data['worksheet_id'] = $worksheetId;

			}
			

			if($this->session->userdata('user_role') == 2) {

				$studentId = array();

				$studentId[] = $this->session->userdata('user_id');

				if ($this->model_quiz->assign_student($worksheetId, $studentId)) {

					foreach($studentId as $student_id) {

						//send notification email to the student
		
						$this->load->library('email');
		
						$this->email->from('noreply@smartjen.com', "SmartJen");

						$this->email->Subject('SmartJen - Quiz Assignment Notification');

						$user_info = $this->model_users->get_user_info($student_id);

						$this->email->to($user_info->email, $user_info->fullname);

						$message = "<p>Dear " . $user_info->fullname . ", </p>";

						$message .= "<p>A quiz has been assigned to you by " . $tutor_name . ". Please login to <a href='" . BRANCH_URL . "'>" . BRANCH_URL . "</a> to complete your quiz now!</p>";

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
				
	
				redirect(base_url().'profile');
			}

            $assigned_students = $this->model_quiz->get_assigned_list($worksheetId);

			$my_students = array();

			if($this->session->userdata('user_role') == '1') {

				$my_students[] = $this->model_users->get_student_list($this->session->userdata('user_id'));
			} else {

				$my_students[] = $this->model_users->get_children_list($this->session->userdata('user_id'));
			}

            $not_assigned_students = array();

            $assigned_students_ids = array();

            foreach ($assigned_students as $assigned) {

                $assigned_students_ids[] = $assigned->id;

            }



            foreach ($my_students as $temp) {
	            for ($i=0; $i<count($temp); $i++){

	                if (!in_array($temp[$i]->student_id, $assigned_students_ids)) {

	                    $not_assigned_students[] = $temp;

	                }

            	}
            }

            $data['my_students'] = $my_students;

			$data['not_assigned_students'] = $not_assigned_students;

			$data['assigned_students'] = $assigned_students;

            $data['content'] = 'smartgen/smartgen_assignWorksheet';

            $this->load->view('include/master_view', $data);

        }

	}

		/**
	 * Outputs the PDF version of the worksheet
	 * 
	 * @param string 	$pdfOutputString 	HTML string of the worksheet
	 * @param string 	$worksheetName 		Worksheet name
	 */
	public function generatePDF($pdfOutputString, $worksheetName) {
		
		$this->load->library('m_pdf');

		$mpdf = new m_pdf();
		ini_set("pcre.backtrack_limit", "1000000000");
		$this->mpdf->WriteHTML($pdfOutputString);
		
		if(BRANCH_ID == 9) {
			
			$this->mpdf->Output($worksheetName,'F');

		} else {
			
			$this->mpdf->Output($worksheetName,'I');

		}
		
		return;
	}

	public function outputPdf() {
		$output_worksheet_name = $this->input->post('pdfWorksheetName');
		if (!isset($output_worksheet_name) or empty($output_worksheet_name) !== false) {
			$output_worksheet_name = 'SmartGen Worksheet.pdf';
		} else {
			$output_worksheet_name .= '.pdf';
		}
		$this->load->library('m_pdf');

		$worksheet_id = $this->input->post('worksheet_id');
		
		$tutor_id = $this->input->post('tutor_id');
		
		$student_id = $this->input->post('student_id[]');

		$checknoQR = (int) $this->input->post('noQR');

		$checknoQR2 = (int) $this->input->post('noQR2');

		if($checknoQR || $checknoQR2) {
			$noQR = 1;
		} else {
			$noQR = 0;
		}

		$html = urldecode(base64_decode($this->input->post('pdfOutputString')));
		
		if(preg_match('/<svg[^>]*>\s*(<defs.*?>.*?<\/defs>)\s*<\/svg>/',$html,$m)) {
			preg_match('/<svg[^>]*>\s*(<defs.*?>.*?<\/defs>)\s*<\/svg>/', $html, $m); 
			$defs = $m[1];
			$html = preg_replace('/<svg[^>]*>\s*<defs.*?<\/defs>\s*<\/svg>/', '', $html);
			$html = preg_replace('/(<svg[^>]*>)/', "\\1".$defs, $html);
			preg_match_all('/<svg([^>]*)style="(.*?)"/', $html, $m);
			$html = preg_replace('/<span class="MJX_Assistive_MathML" role="presentation">(.*?)<\/span>/', ' ', $html);
			// $html = str_replace('display: inline-block;','',$html);
			$html = str_replace('position: relative;','',$html);
			$html = str_replace('<span class="MathJax_Preview" style="color: inherit; display: none;"></span>','',$html);
			// $html = str_replace('<br>','',$html);
		
		// preg_match_all('/<svg([^>]*)(width=".*?") (height=".*?") viewBox="(.*?)"/',$html,$m);
			
			// for ($i=0;$i<count($m[0]);$i++) {
			// 	$width = $m[2][$i];
			// 	$height = $m[3][$i];
			// 	$viewBox = $m[4][$i];

			// 	preg_match('/width="(.*?)"/',$width, $wr);
			// 	$w = $this->mpdf->sizeConverter->convert($wr[1],0,$this->mpdf->FontSize) * $this->mpdf->dpi/25.5;
			// 	preg_match('/height="(.*?)"/',$height, $hr);
			// 	$h = $this->mpdf->sizeConverter->convert(round($hr[1]),0,$this->mpdf->FontSize) * $this->mpdf->dpi/25.4;
			// 	$replace = '<svg'.$m[1][$i].' width="'.$w.'" height="'.$h.'" viewBox="'.$viewBox.'"';
			// 	$html = str_replace($m[0][$i],$replace,$html);
			// }
		
		
		// $html = str_replace('currentColor', '#333', $html);
			$width_replaced = False;
			$height_replaced = False;
			$viewBox_replaced = False;
			$vertical_replaced = False;

			for ($i = 0; $i < count($m[0]); $i++) {
				$style = $m[1][$i];

				$vertical_align = $m[2][$i];
		
				if(preg_match('/width=\"(\S*)\"/',$style, $wr)){
					// $w = $mpdf->ConvertSize($wr[1], 0, $mpdf->FontSize) * $mpdf->dpi/25.4;
					$w = round($this->mpdf->sizeConverter->convert($wr[1],0,$this->mpdf->FontSize) * $this->mpdf->dpi/25.4);
					$width_replaced = True;
				};

				if(preg_match('/vertical-align.{2}(\S*);/',$vertical_align, $va)){
					// $w = $mpdf->ConvertSize($wr[1], 0, $mpdf->FontSize) * $mpdf->dpi/25.4;
					$v = round($this->mpdf->sizeConverter->convert($va[1],0,$this->mpdf->FontSize) * $this->mpdf->dpi/25.4);
					$vertical_replaced = True;
				};
				
				if(preg_match('/height=\"(\S*)\"/',$style, $hr)){
					// $h = $mpdf->ConvertSize($hr[1], 0, $mpdf->FontSize) * $mpdf->dpi/25.4;
					$h = round($this->mpdf->sizeConverter->convert(round($hr[1]),0,$this->mpdf->FontSize) * $this->mpdf->dpi/25.4);
					$height_replaced = True;
				};

				if(preg_match('/viewBox="([^"]+)"/',$style, $vb)){
					$viewBox = $vb[1];
					$viewBox_replaced = True;	
				};
				
				if($width_replaced and $height_replaced and $viewBox_replaced) {
					$replace = '<svg width="'.$w.'" height="'.$h.'" viewBox="'.$viewBox.'"' ;
					$html = str_replace($m[0][$i], $replace, $html);
				}

				$width_replaced = False;
				$height_replaced = False;
				$viewBox_replaced = False;
				$vertical_replaced = True;		
			}
		}

		$html = str_replace('currentColor', '#000', $html);

		$pdfOutputString = '';
		
		if ($noQR == 1) {
			$pdfOutputString = '
			<!DOCTYPE HTML>   
			<html>
			<head>
			
			</head>
			<body>
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

			.answer_field {
				float:right;
				padding:6px;
			}
			
			div.question {
				page-break-inside: avoid;
				position: relative;
				margin: 20px;
				text-align: justify;
				vertical-align: middle;
				line-height: 2.5em;
				font-weight: bold;
			}

			div.answer {
				page-break-before: always;
				page: answer;
				text-align: justify;
				vertical-align: middle;
				font-weight: bold;
			}

			.question_image {
				max-width: 350px;
			}

			.MathJax_Element {
				position: absolute;
				top: 10px;
			}

			.question p { 
				display: inline-block;
			}
			img{
				vertical-align: middle;
				line-height: 5em;
			}
			body {
				font-family: "Lato", sans-serif;
			}
			</style>
			'.BRANCH_HEADER_PDF.'
			<htmlpagefooter name="myFooter1" style="display:none">
			<table width="100%" style="vertical-align: bottom; font-size: 10pt; 
				color: #000000; border-top: 1px solid #333"><tr>
				<td width="50%" align="left">Smartjen &copy; {DATE Y}</td>
				<td width="50%" align="right" >Page {PAGENO}/{nbpg}</td>
				</tr>
			</table>
			</htmlpagefooter>

			' . $html . 
			
			'</body></html>';

			$worksheetName = './pdf/SmartJen_worksheet.pdf';
			// array_debug($pdfOutputString);exit;
			$this->generatePDF($pdfOutputString, $worksheetName);
		} else {
			for( $i = 0; $i < sizeof($student_id); $i++) {
				$pdfOutputString = '
				<!DOCTYPE HTML>   
				<html>
				<head>
				
				</head>
				<body>
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
				.answer_field {
					float:right;
					padding:6px;
				}
				
				div.question {
					page-break-inside: avoid;
					position: relative;
					margin: 20px;
					text-align: justify;
					vertical-align: middle;
					line-height: 2.5em;
					font-weight: bold;
				}
				div.answer {
					page-break-before: always;
					page: answer;
					text-align: justify;
					vertical-align: middle;
					font-weight: bold;
				}
				.question_image {
					max-width: 350px;
				}
				.MathJax_Element {
					position: absolute;
					top: 10px;
				}
				.question p { 
					display: inline-block;
				}
				img{
					vertical-align: middle;
				}
				body {
					font-family: "Lato", sans-serif;
				}
				</style>
				'.BRANCH_HEADER_PDF.'
				<htmlpagefooter name="myFooter1" style="display:none">
				<table width="100%" style="vertical-align: bottom; font-size: 10pt; 
					color: #000000; border-top: 1px solid #333"><tr>
					<td width="50%" align="left">Smartjen &copy; {DATE Y}</td>
					<td width="50%" class="barcodecell" align="right" ><barcode code="footer/{PAGENO}/{nbpg}/'.$student_id[$i].'/'.$tutor_id.'/'.$worksheet_id.'" type="QR" class="barcode" size="0.50" error="M" disableborder="1" /><br>Page {PAGENO}/{nbpg}</td>
					</tr>
				</table>
				</htmlpagefooter>
				' . $html . 

				'</body></html>';

				$worksheetName = './pdf/SmartJen_worksheet_stu_' . $student_id[$i] . '.pdf';
				// array_debug($pdfOutputString);exit;
				$this->generatePDF($pdfOutputString, $worksheetName);
			}
		}

		if(BRANCH_ID == 9) {

			# Creates an empty zip folder and zip all PDFs created in the folder
			$zip = new ZipArchive();

			$zip_file = './pdf/SmartJen_worksheets.zip';		

			if($zip->open($zip_file, ZipArchive::CREATE) == True) {
				if ($noQR == 1) {
					$content = file_get_contents('./pdf/SmartJen_worksheet.pdf');
					$add_file = $zip->addFromString(pathinfo ( './pdf/SmartJen_worksheet.pdf', PATHINFO_BASENAME), $content);				
				} else {
					for( $i = 0; $i < sizeof($student_id); $i++) {
						$add_file = $zip->addFile('./pdf/SmartJen_worksheet_stu_' . $student_id[$i] . '.pdf', 'SmartJen_worksheet_stu_' . $student_id[$i] . '.pdf');
					}
				}
			} else {
				############# Add in customised error handling
				echo '<h1>Unable to download .zip file!</h1>';
				exit();
			}

			$zip->close();
		
			if (headers_sent()) {
				echo 'HTTP header already sent';
			} else {
				if (!is_file($zip_file)) {
					header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
					echo 'File not found';
				} else if (!is_readable($zip_file)) {
					header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
					echo 'File not readable';
				} else {
					while (ob_get_level()) {
						ob_end_clean();
					}
				ob_start();
				header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
				header("Content-Type: application/zip");
				header("Content-Transfer-Encoding: Binary");
				header("Content-Length: ".filesize($zip_file));
				header('Pragma: no-cache');
				header("Content-Disposition: attachment; filename=\"".basename($zip_file)."\"");
				ob_flush();
				ob_clean();
				readfile($zip_file);
			}
			}

			$files = glob('./pdf/*'); // get all file names
			
			foreach($files as $file){ // iterate files
				if(is_file($file))
					unlink($file); // delete file
			}

			return;
		}
	}


	public function saveWorksheetAsPDF($worksheetId = NULL) {
		if (isset($worksheetId) && empty($worksheetId) === false) {
			//if user is logged in, check if the worksheetId is tie to the user
			if ($this->session->userdata('is_logged_in') == 1) {
				$this->load->model('model_worksheet');
                $ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $this->session->userdata('user_id'));
				//if worksheet is not tie to user, redirect back without worksheetId
				if (!$ownedByUser) {
					redirect(base_url().'smartgen/saveWorksheetAsPDF');
				}
				$worksheetName = $this->model_worksheet->get_worksheet_name_from_id($worksheetId);
			} else { //else redirect back without worksheetId
				redirect(base_url().'smartgen/saveWorksheetAsPDF');
			}
		} else {
			$worksheetName = "SmartGen Worksheet";
		}

		$this->load->library('m_pdf');
		$this->load->helper('shuffleassoc');
		$this->load->model('model_question');

		$questionArray = $this->session->userdata('questionArray');

		$questionList = array();
		$correctAnswers = array();

		$count = 1;
		foreach ($questionArray AS $question) {
			$questionDetail = $this->model_question->get_question_from_id($question);
			$questionList[$count++] = $questionDetail[0];
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

		
		'.BRANCH_HEADER_PDF.'

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


		for ($i = 1, $count = count($questionList) ; $i <= $count ; $i++) {
			//print question
			$html .= '<p>'. $i . ') ' . $questionList[$i]->question_text. '</p>';

			//print image
			if ($questionList[$i]->graphical != "0") {
				// $imagePath = 'img/questionImage/' . $questionList[$i]->year . '-' . $questionList[$i]->school_id . '-' . $questionList[$i]->level_id . '-' . $questionList[$i]->question_id . '.jpg';
				$imagePath = 'img/questionImage/' . $questionList[$i]->graphical;
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



//		if ($questionType === "MCQ") {

//

//		} else if ($questionType === "openEnded") {

//

//		}

		$html .= '<div class="answer">';

		for ($i = 1, $count = count($correctAnswers) ; $i <= $count; $i++) {
			$html .= $i . ") " . $correctAnswers[$i] . "<br><br>";
		}

		$html .= '
			</div>
			</body></html>
		';

		$this->mpdf->WriteHTML($html);
		// $this->mpdf->SetTitle($worksheetName);
		$this->mpdf->Output($worksheetName. '.pdf','D'); 
	}


	public function login() {
		$this->session->set_userdata('lastPage', base_url().'smartgen/generateWorksheet');
		redirect('site/login');
	}


	public function flagQuestion() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('model_question');
			$this->load->library('email');
			$postData = $this->input->post();
			if (isset($postData) && empty($postData) === false) {
				$user_id = $postData['user_id'];
				$error_type = $postData['error_type'];
				$error_comment = $postData['error_comment'];
				$question_id = $postData['flagged_question_id'];

				$this->email->from('noreply@smartjen.com', "Smartjen - No Reply");			
				$this->email->to('hello@smartjen.com');
				$this->email->cc('lowcg2@hotmail.com');
				$this->email->Subject('Question flagged: ID '. $question_id);
				$message = "<p> Error type: " . $error_type ."</p>";
				$message .= "<p> Additional comments: " . $error_comment . "</p>";

				$this->email->message($message);
				$this->email->send();
				$return_array = array();

				if ($this->model_question->flag_question($user_id, $question_id, $error_type, $error_comment)) {
					$return_array['success'] = true;
				} else {
					$return_array['success'] = false;
				}

				$return_array = array(
					"success" => true
				);

				echo json_encode($return_array);
			}
		} else {
			redirect('404');
		}

	}

	public function subQuestion(){
		if($this->input->is_ajax_request()){
			$this->load->model('model_question');
			$postData = $this->input->post();
			$parent_id = $postData['sub_question_id'];
			$questionList = $this->session->userdata('questionArray');
			$sessionArray = $this->session->userdata();
			$sub_array = array();
			
			if(array_key_exists('removeQuestionArray', $sessionArray)){
				$subQuestionArray = $this->session->userdata('removeQuestionArray');
				if(isset($subQuestionArray) && empty($subQuestionArray) === false) {
					$sub_array = $subQuestionArray[$parent_id];	
				} else {
					$sub_array = NULL;	
				}
			} else {
				$sub_array = NULL;
			}
			
			$this->session->unset_userdata('removeQuestionArray');

			if(isset($postData) && empty($postData) === false){
				$data['questionList'] = $this->model_question->list_sub_question($parent_id, $sub_array);
				$data['answerList'] = $this->model_question->get_answer_list($data['questionList']);
				$data['subQuestionArray'] = $questionList;
				$key = array_search($parent_id, $questionList);
				
				$id = array();
				
				// store the parent id as key and sub question as index
				$parentQuestionArray = array();
				
				// use the session array to add in parentQuestionArray
				if($this->session->userdata('parentQuestionArray') !== null)
					$parentQuestionArray = $this->session->userdata('parentQuestionArray');
					
				foreach($data['questionList'] as $value){
					$id[] = $value->question_id;
					
					if(isset($parent_id) && empty($parent_id) === false){
						$parentQuestionArray[$parent_id][] = $value->question_id;
					}
				}
				
				$test = array_splice($data['subQuestionArray'], $key+1, 0, $id);
				$this->session->set_userdata('questionArray', $data['subQuestionArray']);
				echo json_encode($data);
				
			}
			$this->session->set_userdata('parentQuestionArray', $parentQuestionArray);
		}else {
			redirect('404');
		}
	}


	public function removeSubQuestion(){
		
		if($this->input->is_ajax_request()){
			$postData = $this->input->post();
			$sub_question = $postData['sub_question_id'];
			$parent_question = $postData['parent_question_id'];
			$sess_array = $this->session->userdata('parentQuestionArray');
			$ques_array = $this->session->userdata('questionArray');
			$array = array();
			if($this->session->userdata('removeSubQuestionArray') !== null)
					$array = $this->session->userdata('removeSubQuestionArray');

			foreach($sess_array as $row){
				if (($key = array_search($sub_question, $row)) !== false) {
					$array[$parent_question][] = $sub_question;
					unset($sess_array[$parent_question][$key]);
				}
			}

			if (($key = array_search($sub_question, $ques_array)) !== false) {
				unset($ques_array[$key]);
			}

			if(empty($sess_array[$parent_question])){
				unset($sess_array[$parent_question]);
				unset($array[$parent_question]);
			}
			$this->session->set_userdata('questionArray', $ques_array);
			$this->session->set_userdata('removeSubQuestionArray', $array);
			$this->session->set_userdata('parentQuestionArray', $sess_array);
			echo json_encode($sess_array);
		}else {
			redirect('404');
		}
	}

	public function pagination($rowno=1){
		$this->load->model('model_users');
		$this->load->library('pagination');

		// Row per page
		$rowperpage = 1000;

		// Row position
		$rowno = ($rowno - 1) * $rowperpage;

		// Get the selected id
		$keywords = array();
		$keywords = $this->input->post('exclude');

		// Get selected group
		$group = $this->input->post('group');

		if (!$keywords){
			$keywords[] = 'NULL';
		} else {
			$keywords = $this->input->post('exclude');
		}
		if($this->session->userdata('user_role') == '1'){
			// All records count
			$allcount = $this->model_users->get_student_list_count_all($this->session->userdata('user_id'),$keywords);

			// Get records
			$users_record = $this->model_users->get_student_list_fetch_details($this->session->userdata('user_id'),$keywords,$rowperpage,$rowno);
		} else {
			// All records count
			$allcount = $this->model_users->get_children_list_count_all($this->session->userdata('user_id'),$keywords);

			// Get records
			$users_record = $this->model_users->get_children_list_fetch_details($this->session->userdata('user_id'),$keywords,$rowperpage,$rowno);
		}
		
		$data['result'] = $users_record;
		$r = 0;
		foreach($data['result'] as $i => &$row) {
			$student_id = $row['student_id'];
			$groupInfo = $this->model_users->get_student_group($this->session->userdata('user_id'),$student_id);
			if($groupInfo['group_id']!='0')
				$data['result'][$r]['group_name'] = $groupInfo['group_name'];
			else $data['result'][$r]['group_name'] = 'no-group';
			$r++;
		}
		$r = 0; $min = 0;
		foreach($data['result'] as $i => &$row) {
			if($data['result'][$r]['group_name'] != $group && $group != 'all-student') {
				unset($data['result'][$r]);
				$min++;
			}
			$r++;
		}

		// Pagination Configuration
		$config = array();
		$config['base_url'] = base_url() . 'smartgen/pagination';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount - $min;
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
		$data['row'] = $rowno;
		echo json_encode($data);
	}

	public function paginations($rowno=1)
	{
		$this->load->model('model_users');
		$this->load->library('pagination');

		// Row per page
		$rowperpage = 1000;

		// Row position
		$rowno = ($rowno - 1) * $rowperpage;

		// Get the selected id
		// $keywords = array();
		// $keywords[] = $this->input->post('exclude');

		// Get selected group
		$group = $this->input->post('group');

		$keywords[] = '';
		$exclude = (is_array(($this->input->post('exclude')))) ? $this->input->post('exclude') : array();
		// $keywords = $exclude;
		// All records count
		$allcount = $this->model_users->get_student_list_count_all($this->session->userdata('user_id'),$keywords);

		// Get records
		$users_record = $this->model_users->get_student_list_fetch_details($this->session->userdata('user_id'),$keywords,$rowperpage,$rowno);

		// Get All Records
		$all_record = $this->model_users->get_student_list_fetch_details($this->session->userdata('user_id'),$keywords, NULL, NULL);

		$data['result'] = $users_record;
		$r = 0;
		foreach($data['result'] as $i => &$row) {
			$student_id = $row['student_id'];
			$groupInfo = $this->model_users->get_student_group($this->session->userdata('user_id'),$student_id);
			if($groupInfo['group_id']!='0')
				$data['result'][$r]['group_name'] = $groupInfo['group_name'];
			else $data['result'][$r]['group_name'] = 'no-group';
			$r++;
		}
		$r = 0; $min = 0;
		foreach($data['result'] as $i => &$row) {
			if($data['result'][$r]['group_name'] != $group && $group != 'all-student' and !in_array($row['student_id'], $exclude)) {
				unset($data['result'][$r]);
				$min++;
			}
			$r++;
		}
		$data['row'] = $rowno;
		$data['all_result'] = $all_record;
		$r = 0;
		foreach($data['all_result'] as $i => &$row) {
			$student_id = $row['student_id'];
			$groupInfo = $this->model_users->get_student_group($this->session->userdata('user_id'),$student_id);
			if($groupInfo['group_id']!='0')
				$data['all_result'][$r]['group_name'] = $groupInfo['group_name'];
			else $data['all_result'][$r]['group_name'] = 'no-group';
			$r++;
		}
		$r = 0; $min = 0;
		foreach($data['all_result'] as $i => &$row) {
			if($data['all_result'][$r]['group_name'] != $group && $group != 'all-student' and !in_array($row['student_id'], $exclude)) {
				unset($data['all_result'][$r]);
				$min++;
			}
			$r++;
		}

		// Pagination Configuration
		$config = array();
		$config['base_url'] = base_url() . 'smartgen/paginations';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] =  count($data['all_result']); //$allcount;
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
		echo json_encode($data);
	}
	
	public function archiveWorksheetList() {
		$this->load->model('model_worksheet');
		$userId = $this->session->userdata('user_id');
		//pagination
		$this->load->library('pagination');
		$config = array();
		$config['base_url'] = base_url() . 'smartgen/archiveWorksheetList';
		$config['total_rows'] = $this->model_worksheet->count_archiveList($userId);
		$config['per_page'] = 5;
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
		$data['archiveList'] = $this->model_worksheet->get_archiveList($userId,$config['per_page'], $page);
		$data['link'] = $this->pagination->create_links();
		$data['content'] = 'smartgen/smartgen_archiveWorksheet';
		$this->load->view('include/master_view', $data);
	}
	
	public function student_log(){
		if ($this->input->is_ajax_request()) {
			$this->load->model('model_question');
			$data = array(
				'question_id' => $this->input->post('question_id'),
				'quiz_id' => $this->input->post('quiz_id'),
				'user_id' => $this->session->userdata('user_id'),
				'answer' => $this->input->post('answer_id'),
				'tutor_id' => $this->input->post('tutor_id')
			);
			$student_log = $this->model_question->insert_student_log($data);
			echo json_encode($student_log);
		} else {
			redirect('404');
		}
	}

	
	
	/*** Secondary Math Prototype *****/
	
	/*public function sc_designWorksheet($worksheetId = NULL) {
		$logged_in = $this->session->userdata('is_logged_in');
		if (isset($worksheetId) && empty($worksheetId) === false) {
			//if user is logged in, check if the worksheetId is tie to the user
			if ($logged_in == 1) {
				$this->load->model('model_worksheet');
				$ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $this->session->userdata('user_id'));
				//if worksheet is not tie to user, redirect back without worksheetId
				if (!$ownedByUser) {
					redirect(base_url().'smartgen');
				}
				$data['worksheetId'] = $worksheetId;
			} else { //else redirect back without worksheetId
				redirect(base_url().'smartgen');
			}
		}
		$data['is_logged_in'] = $logged_in;
		$this->load->model('model_question');
		$data['content'] = 'smartgen/sc_smartgen_home';
		$data['levels']  = $this->model_question->get_sc_level_list();
		$data['substrands'] = $this->model_question->get_sc_substrand_list();
		$data['topics']  = $this->model_question->get_topic_list(1);
		$selectedNumOfQuestion = $this->session->userData('worksheetNumOfQuestion');
		$selectedDifficulty = $this->session->userData('worksheetDifficulty');
		$selectedLevel = $this->session->userData('worksheetLevel');
		$selectedTopic = $this->session->userData('worksheetTopic');
		$selectedSubstrand = $this->session->userData('worksheetSubstrand');
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
			$data['selectedSubstrand'] = $selectedSubstrand;
			$topics = array();
			foreach ($selectedSubstrand as $selected) {
				$topic_list = $this->model_question->get_topic_list($selected);
				$topics[] = $topic_list;
			}
			$data['topics'] = $topics;
		}
		$this->load->view('include/master_view', $data);
	}
	
	public function sc_generateWorksheet($worksheetId = NULL) {
		if (isset($worksheetId) && empty($worksheetId) === false) {
			$this->check_worksheet_owner($worksheetId, '404');
            $data['worksheetId'] = $worksheetId;
		}
		$this->load->model('model_question');
		$postData = $this->input->post();
		$sessionData = $this->session->userdata('questionArray');
		$data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		if ($this->session->userdata('is_logged_in') == 1) {
			$data['user_id'] = $this->session->userdata('user_id');
		} else {
			$data['user_id'] = 0;   // stands for visitor
		}
		if (isset($postData) && empty($postData) === false) {
			$questionList = array();
			$answerList = array();
			$categoryList = array();
            $substrandList = array();
            $subquestionList = array();
			//submit from regenerate all button
			if (isset($postData['regenerateWorksheet']) && empty($postData['regenerateWorksheet']) === false) {
				$postData['gen_num_of_question'] = $this->session->userdata('worksheetNumOfQuestion');
				$postData['gen_difficulties']    = $this->session->userdata('worksheetDifficulty');
				$postData['gen_level']           = $this->session->userdata('worksheetLevel');
				$postData['gen_topic']           = $this->session->userdata('worksheetTopic');
				$postData['gen_que_type']        = $this->session->userdata('worksheetQueType');
				$postData['gen_substrand']       = $this->session->userdata('worksheetSubstrand');
			} else {
				//save requirement in session
				$sessionArray = array(
					'worksheetNumOfQuestion' => $this->input->post('gen_num_of_question'),
					'worksheetDifficulty'    => $this->input->post('gen_difficulties'),
					'worksheetLevel'         => $this->input->post('gen_level'),
					'worksheetTopic'         => $this->input->post('gen_topic'),
					'worksheetQueType'       => $this->input->post('gen_que_type'),
					'worksheetSubstrand'     => $this->input->post('gen_substrand')
				);
				$this->session->set_userdata($sessionArray);
			}
			$this->load->model('model_worksheet');
			$this->model_worksheet->save_worksheet_requirement($postData);
			$questionList = $this->model_question->get_sc_question_list($postData, $data['user_id']);
			foreach ($questionList as $question) {
				$categoryList[] = $this->model_question->get_category_from_question_id($question->question_id);
				$substrandList[] = $this->model_question->get_substrand_from_question_id($question->question_id);
				$subquestionList[] = $this->model_question->sub_question($question->question_id);
			}
		} else if (isset($sessionData)) {
			foreach ($sessionData AS $questionId) {
				$questionDetail = $this->model_question->get_question_from_id($questionId);
				$questionList[] = $questionDetail;
				$categoryList[] = $this->model_question->get_category_from_question_id($questionId);
				$substrandList[] = $this->model_question->get_substrand_from_question_id($questionId);
				$subquestionList[] = $this->model_question->sub_question($questionId);
			}
		} else {
			$data['content'] = 'smartgen/sc_smartgen_home';
			$this->load->view('include/master_view', $data);
			return ;
		}
		
		//get the answers from here
		$answerList = $this->model_question->get_answer_list($questionList);
		$data['answerList'] = $answerList;
		$data['questionList'] = $questionList;
		$data['categoryList'] = $categoryList;
		$data['substrandList'] = $substrandList;
		$data['subquestionList'] = $subquestionList;
		$requirement_id = $this->model_question->get_question_type_from_requirement_id($this->session->userdata('requirementId'));
		if ($requirement_id == 1){
			$data['content'] = 'smartgen/sc_smartgen_generateWorksheet';
		} else {
			$data['content'] = 'smartgen/sc_smartgen_generateWorksheets';
		}
		$this->load->view('include/master_view', $data);
	}


	public function getScTopicList($substrand_id = NULL) {
		$this->load->model('model_question');
		echo json_encode($this->model_question->get_sc_topic_list($substrand_id));
	}

	public function getScSubstrandList($strand_id = NULL) {
		$this->load->model('model_question');
		echo json_encode($this->model_question->get_sc_substrand_list($strand_id));
	} */

	/*** Secondary Math Prototype *****/
	
	
	/***********************************************************/
	
	
	/*** Primary Science Prototype ****/
	
	public function sc_designWorksheet($worksheetId = NULL) {
		$logged_in = $this->session->userdata('is_logged_in');
		if (isset($worksheetId) && empty($worksheetId) === false) {
			//if user is logged in, check if the worksheetId is tie to the user
			if ($logged_in == 1) {
				$this->load->model('model_worksheet');
				$ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $this->session->userdata('user_id'));
				//if worksheet is not tie to user, redirect back without worksheetId
				if (!$ownedByUser) {
					redirect(base_url().'smartgen');
				}
				$data['worksheetId'] = $worksheetId;
			} else { //else redirect back without worksheetId
				redirect(base_url().'smartgen');
			}
		}
		$data['is_logged_in'] = $logged_in;
		$this->load->model('model_question');
		$data['content'] = 'smartgen/sc_smartgen_home';
		$data['levels']  = $this->model_question->get_sc_level_list();
		$data['substrands'] = $this->model_question->get_sc_substrand_list();
		$data['topics']  = $this->model_question->get_topic_list(1);
		$selectedNumOfQuestion = $this->session->userData('worksheetNumOfQuestion');
		$selectedDifficulty = $this->session->userData('worksheetDifficulty');
		$selectedLevel = $this->session->userData('worksheetLevel');
		$selectedTopic = $this->session->userData('worksheetTopic');
		$selectedSubstrand = $this->session->userData('worksheetSubstrand');
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
			$data['selectedSubstrand'] = $selectedSubstrand;
			$topics = array();
			foreach ($selectedSubstrand as $selected) {
				$topic_list = $this->model_question->get_topic_list($selected);
				$topics[] = $topic_list;
			}
			$data['topics'] = $topics;
		}
		$this->load->view('include/master_view', $data);
	}
	
	public function sc_generateWorksheet($worksheetId = NULL) {
		if (isset($worksheetId) && empty($worksheetId) === false) {
			$this->check_worksheet_owner($worksheetId, '404');
            $data['worksheetId'] = $worksheetId;
		}
		$this->load->model('model_question');
		$postData = $this->input->post();
		$sessionData = $this->session->userdata('questionArray');
		$data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		if ($this->session->userdata('is_logged_in') == 1) {
			$data['user_id'] = $this->session->userdata('user_id');
		} else {
			$data['user_id'] = 0;   // stands for visitor
		}
		if (isset($postData) && empty($postData) === false) {
			$questionList = array();
			$answerList = array();
			$categoryList = array();
            $substrandList = array();
            $subquestionList = array();
			//submit from regenerate all button
			if (isset($postData['regenerateWorksheet']) && empty($postData['regenerateWorksheet']) === false) {
				$postData['gen_num_of_question'] = $this->session->userdata('worksheetNumOfQuestion');
				$postData['gen_difficulties']    = $this->session->userdata('worksheetDifficulty');
				$postData['gen_level']           = $this->session->userdata('worksheetLevel');
				$postData['gen_topic']           = $this->session->userdata('worksheetTopic');
				$postData['gen_que_type']        = $this->session->userdata('worksheetQueType');
				$postData['gen_substrand']       = $this->session->userdata('worksheetSubstrand');
			} else {
				//save requirement in session
				$sessionArray = array(
					'worksheetNumOfQuestion' => $this->input->post('gen_num_of_question'),
					'worksheetDifficulty'    => $this->input->post('gen_difficulties'),
					'worksheetLevel'         => $this->input->post('gen_level'),
					'worksheetTopic'         => $this->input->post('gen_topic'),
					'worksheetQueType'       => $this->input->post('gen_que_type'),
					'worksheetSubstrand'     => $this->input->post('gen_substrand')
				);
				$this->session->set_userdata($sessionArray);
			}
			$this->load->model('model_worksheet');
			$this->model_worksheet->save_worksheet_requirement($postData);
			$questionList = $this->model_question->get_sc_question_list($postData, $data['user_id']);
			foreach ($questionList as $question) {
				$categoryList[] = $this->model_question->get_category_from_question_id($question->question_id);
				$substrandList[] = $this->model_question->get_substrand_from_question_id($question->question_id);
				$subquestionList[] = $this->model_question->sub_question($question->question_id);
			}
		} else if (isset($sessionData)) {
			foreach ($sessionData AS $questionId) {
				$questionDetail = $this->model_question->get_question_from_id($questionId);
				$questionList[] = $questionDetail;
				$categoryList[] = $this->model_question->get_category_from_question_id($questionId);
				$substrandList[] = $this->model_question->get_substrand_from_question_id($questionId);
				$subquestionList[] = $this->model_question->sub_question($questionId);
			}
		} else {
			$data['content'] = 'smartgen/sc_smartgen_home';
			$this->load->view('include/master_view', $data);
			return ;
		}
		
		//get the answers from here
		$answerList = $this->model_question->get_answer_list($questionList);
		$data['answerList'] = $answerList;
		$data['questionList'] = $questionList;
		$data['categoryList'] = $categoryList;
		$data['substrandList'] = $substrandList;
		$data['subquestionList'] = $subquestionList;
		$requirement_id = $this->model_question->get_question_type_from_requirement_id($this->session->userdata('requirementId'));
		$data['que_type'] = $requirement_id;
		if ($requirement_id == 1){
			$data['content'] = 'smartgen/sc_smartgen_generateWorksheet';
		} else {
			$data['content'] = 'smartgen/sc_smartgen_generateWorksheets';
		}
		$this->load->view('include/master_view', $data);
	}


	public function getScTopicList($substrand_id = NULL) {
		$this->load->model('model_question');
		echo json_encode($this->model_question->get_sc_topic_list($substrand_id));
	}

	public function getScSubstrandList($strand_id = NULL) {
		$this->load->model('model_question');
		echo json_encode($this->model_question->get_sc_substrand_list($strand_id));
	} 
	
	/*** Primary Science Prototype ****/

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

	
	function ajax_order_question (){
		if($this->session->has_userdata('questionArray') && $this->input->post()){
			$question_id = $this->input->post('question_id');
			$position = $this->input->post('position');
			$subQuestion = $this->input->post('sub_questions');
			$sessionData = $this->session->userdata('questionArray');
			$count = count($sessionData);
			if (($key = array_search($question_id, $sessionData)) !== false) {
				unset($sessionData[$key]);
			}

			if(isset($subQuestion) && empty($subQuestion) === false){
				foreach($subQuestion as $subId) {
					if (($key = array_search($subId, $sessionData)) !== false) {
						unset($sessionData[$key]);
					}
				}
				$count = $count - count($subQuestion);
			}
			
			$result = array();
			$currPos = 1;
			foreach($sessionData as $item){
				if($currPos == $position){
					$result[] = $question_id;
					$result[] = $item;
				}else{
					$result[] = $item;
				}
				$currPos++;
			}

			if($position == $count){
				$result[] = $question_id;
			}
			
			$sess_parent_array = $this->session->userdata('parentQuestionArray');
			if(isset($sess_parent_array) && empty($sess_parent_array) === false){
				$parent_id = array();
				foreach($sess_parent_array as $parent_key=>$sub_question_array){
					$sub_que = array();
					foreach($sub_question_array as $subKey => $subQue){
						if(($key = array_search($subQue, $result)) !== false) {
							unset($result[$key]);
						}
						$sub_que[] = $subQue;
					}
					$parent_id[$parent_key] = $sub_que;
				}
				$result['target_id'] = $parent_id;
			}
			$this->session->set_userdata('questionArray',$result);
			echo true;
		}else{
			echo false;
		}
	}
	
	function get_more_questions($page = 1){
		$question_list = $this->session->userdata('generated_questions');
		$postData = $this->input->post();
		$start = (($page <= 0 ? 1 : $page) - 1) * 10;
		$data = $this->generateWorksheet(NULL, true, $question_list,$start );
		
		if($this->session->has_userdata('selected_questions')){
			$data['selected_questions'] = $this->session->userdata('selected_questions');
		}

		if(isset($data['total_rows']) && !empty($data['total_rows'] == TRUE)) {
			$total_rows = $data['total_rows'];
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
		
		$this->pgconfig['total_rows'] = $total_rows;
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
		$this->load->view('smartgen/smartgen_addMore', $data);
	}
	
	// To keep selected question in session
	
	function ajax_selected_question(){
		if($this->input->post()){
			$questions = array();
			$question_id = $this->input->post('question_id');
			
			if($this->session->has_userdata('selected_questions')){
				$questions = $this->session->userdata('selected_questions');
			}
			
			if(!in_array($question_id,$questions)){
				$questions[] = $question_id;
			}else{
				// remove question from session				
				if (($key = array_search($question_id, $questions)) !== false) {
					unset($questions[$key]);
				}
			}			
			$this->session->set_userdata('selected_questions',$questions);
		}else{
			echo "Invalid Request";
		}
	}
	

	function submit_add_more(){		
		if($this->session->has_userdata('questionArray')){			
			$sessionData = $this->session->userdata('questionArray');			
			$add_more = $this->session->userdata('selected_questions');			
			if(is_array($add_more) == false){
				$add_more = array();
			}			
			$sessionData = array_merge($sessionData,$add_more);			
			$this->session->set_userdata('questionArray',$sessionData);			
			$this->session->unset_userdata('selected_questions');
		}		
		redirect('smartgen/generateWorksheet');
	}
	

	function ajax_remove_question(){	
		if($this->session->has_userdata('questionArray') && $this->input->post()){
			$question_id = $this->input->post('question_id');			
			$sessionData = $this->session->userdata('questionArray');			
			if (($key = array_search($question_id, $sessionData)) !== false) {				
				unset($sessionData[$key]);					
				$this->session->set_userdata('questionArray',$sessionData);				
				echo true;			
			}else{				
				echo false;
			}			
		}else{			
			echo false;
		}
	}


	/*

		This function is to get the requirement, question list , save in session and populate in customize page

	*/

	function customizeAdminWorksheet($worksheetId = NULL) {

		if (isset($worksheetId) && empty($worksheetId) === false) {

			$user_id = $this->session->userdata('user_id');
			
			$this->load->model('model_question');

			$this->getAdminRequirement($worksheetId);
			
			$this->getAdminQuestionList($worksheetId);

			$this->session->set_userdata('is_admin', TRUE);
			
			redirect(base_url().'smartgen/generateWorksheet/');

		} else {

			redirect('404');

		}

		

	}



	/*

		This function is to get the requirement, save in session

	*/

	private function getAdminRequirement($worksheetId) {

		$requirement = $this->model_worksheet->get_admin_requirement_from_worksheetId($worksheetId);
		
		$sessionArray = array(

			'worksheetNumOfQuestion' => $requirement->admin_num_question,

			'worksheetDifficulty'    => $requirement->admin_difficulty,

			'worksheetLevel'         => $requirement->admin_level_ids,

			'worksheetQueType'       => $requirement->admin_question_type,

			'worksheetTopic'         => explode(",", $requirement->admin_topic_ids),

			'worksheetSubstrand'     => explode(",", $requirement->admin_substrand_ids),

			'requirementId'          => $requirement->admin_requirement_id

		);

		$gen_substrand = array();

		$gen_substrand[] = $requirement->admin_substrand_ids;

		$gen_topic = array();

		$gen_topic[] = $requirement->admin_topic_ids;

		$gen_difficulties = array();

		$gen_difficulties[] = $requirement->admin_difficulty;

		$postData = array(

			'gen_subject' => $requirement->admin_subject_type,
			
			'gen_level' => $requirement->admin_level_ids,

			'gen_num_of_question' => $requirement->admin_num_question,

			'gen_substrand' => $gen_substrand,

			'gen_topic' => $gen_topic,

			'gen_strategy' => $requirement->admin_strategy_ids,

			'gen_difficulties' => $gen_difficulties,

			'gen_que_type' => $requirement->admin_question_type

		);
		$this->session->set_userdata($sessionArray);

		$this->model_worksheet->save_worksheet_requirement($postData);
		
	}



	private function getAdminQuestionList($worksheetId) {

		$this->load->model('model_question');

		$worksheetQuery = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId, true);
		$questionIdArray = array();
		foreach ($worksheetQuery->result() AS $question) {

			$questionIdArray[$question->admin_question_number-1] = $question->admin_question_id;

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

	
}
