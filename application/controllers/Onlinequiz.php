<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



defined('BASEPATH') OR exit('No direct script access allowed');



class Onlinequiz extends CI_Controller {



	public function index() {

		if ($this->session->userdata('is_logged_in') == 1) {

			$this->load->model('model_users');

			$this->load->model('model_worksheet');

			$this->load->model('model_quiz');

			$this->load->model('model_question');

			$userId = $this->session->userdata('user_id');

			$userRole = $this->session->userdata('user_role');

			$quizzes = array();

			if ($userRole == 1) { // tutor

				$quizzes = $this->model_users->get_tutor_quiz_list($userId);

				foreach ($quizzes as $quiz) {

					$quiz->studentName = $this->model_users->get_username_from_id($quiz->assignedTo);



					$this->updateQuizInfo($quiz);

				}

				$data['quizzes'] = $quizzes;


				$data['content'] = 'quiz/quiz_home_tutor';

			

			} else if ($userRole == 2) { // children

				$quizzes = $this->model_users->get_quiz_list_student($userId);
				foreach ($quizzes as $quiz) {

					$quiz->createdBy = $this->model_users->get_username_from_id($this->model_worksheet->get_ownerId_from_worksheetId($quiz->worksheetId));

					$this->updateQuizInfo($quiz);

				}

				$quizSubmitSuccess = $this->session->userdata('quizSubmitSuccess');

				if (isset($quizSubmitSuccess) && $quizSubmitSuccess) {

					$data['quizSubmitSuccess'] = true;

					$this->session->unset_userdata('quizSubmitSuccess');

				}

				$data['content'] = 'quiz/quiz_home_student';

			}

			

			$data['quizzes'] = $quizzes;



		} else { // if user is not logged in, show message to ask for login

			$data['quizError'] = true;

			$data['quizErrorMessage'] = 'Please <a href="' . base_url() . 'site/login">Login</a> to see available quiz / generate worksheet for quiz';

			$this->session->set_userdata('lastPage', base_url().'onlinequiz');

			$data['content'] = 'quiz/quiz_home_student';

		}



		$this->load->view('include/master_view', $data);

	}

	
	public function get_quiz_content_student($pageno = "", $status = "") {
		$this->load->model('model_users');

		$this->load->model('model_worksheet');

		$this->load->model('model_quiz');
		$this->load->model('model_question');
		$userId = $this->session->userdata('user_id');
		$this->load->library('pagination');
		
		$rowperpage = 10;
		$pageno = ($pageno - 1) * $rowperpage;
		$allcount = $this->model_users->count_get_quiz_list($userId, $status);

		$quizzes = $this->model_users->get_quiz_lists($userId, $status, $rowperpage, $pageno);

			foreach ($quizzes as $quiz) {


				$quiz->createdBy = $this->model_users->get_username_from_id($this->model_worksheet->get_ownerId_from_worksheetId($quiz->worksheetId));
				$this->updateQuizInfo($quiz);


			}
			
		$config = array();
		$config['base_url'] = base_url() . 'onlinequiz/get_quiz_content_student';

		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;
		$config['uri_segment'] = 3;

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

		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['quizzes'] = $quizzes;
		$data['row'] = $pageno;
		$this->load->view('quiz/quiz_listing_student', $data);
	}


	private function updateQuizInfo_old(&$quiz) {

		$quiz->name = $this->model_worksheet->get_worksheet_name_from_id($quiz->worksheetId);

		$quiz->numOfAttempt = $this->model_quiz->count_number_of_attempt($quiz->id);

		if ($quiz->numOfAttempt > 0) {

			$quiz->lastAttemptDate = $this->model_quiz->get_last_attempt_date($quiz->id);

			$attemptInfo = array();

			$attempts = $this->model_quiz->get_student_attempt($quiz->id);



			foreach ($attempts as $attempt) {

				$array = $this->get_student_attempt_score($attempt->id, $quiz->worksheetId);

				$attempt->score = $array['correctAnswer'] . " / " . $array['numOfQuestion'];

				$attemptInfo[] = $attempt;

			}

			$quiz->attempts = $attemptInfo;

		}

	}


	private function get_student_attempt_score_old($attemptId, $worksheetId) {

		$this->load->model('model_question');

		$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);

		$userAnswerArray = $this->model_quiz->get_attempt_answer($attemptId);



		$returnArray = array();

		$returnArray['numOfQuestion'] = 0;

		$returnArray['correctAnswer'] = 0;

		foreach ($questions->result() AS $question) {

			$returnArray['numOfQuestion']++;

			$correctAnswer = $this->model_question->get_correct_answer_from_question_id($question->question_id);

			$userAnswer = (isset($userAnswerArray[$question->question_number - 1]))?$userAnswerArray[$question->question_number - 1]:-1;



			if ($userAnswer == $correctAnswer) {

				$returnArray['correctAnswer']++;

			}

		}



		return $returnArray;



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

                //$attempt->score = $array['correctAnswer'] . " / " . $array['numOfQuestion'];

            //    $attempt->score = 0;

            //    foreach($array as $answerScore) {

            //        $attempt->score += $answerScore;

            //    }

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



    private function get_score_single_question($question, $userAnswer, $questionType, $subjectType = 2) {

		$this->load->model('model_automarking');

	    $returnScore = 0;

        $fullMark = $this->model_question->get_total_marks_from_question_id($question->question_id);



        // [WM]: value of $questionType might be:

        // "1" >> MCQ with 4 answer options

        // "2" >> Open ended with text based answer

        // "3" >> True/False answer (not implemented yet as of 15Jul17)

        //$questionType = $this->model_question->get_question_type_id_from_question_id($question->question_id);

		/*
        if($questionType == 1) // objective question

        {

            $correctAnswer = $this->model_question->get_correct_answer_from_question_id($question->question_id);

            //$userAnswer = (isset($userAnswerArray[$question->question_number - 1]))?$userAnswerArray[$question->question_number - 1]:-1;



            if ($userAnswer == $correctAnswer) {

                //$returnArray['correctAnswer']++;

                $returnScore = $fullMark;

                //$returnArray['finalScore'] += $fullMark;

            }

            else

            {

                $returnScore = 0;

            }

        }

        else if ($questionType == 2) // subjective question

        {
			*/

            // this gets the answerId

            $modelAnswerText = trim($this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question->question_id)));


            $userAnswerText = trim($userAnswer);



//            if (isset($userAnswerTextIdArray[$question->question_number - 1])) {

//                $this->db->select('answerText');

//                $this->db->from('sj_student_answers_text');

//                $this->db->where('answerTextId', $userAnswerTextIdArray[$question->question_number - 1]);

//                $query = $this->db->get();

//

//                $userAnswerText = $query->row()->answerText;

//            }



            // Call automarking class

            $correctAnswerObject = new Model_subjectiveanswer();

            $userAnswerObject = new Model_subjectiveanswer();



            //$correctAnswerObject->configure($modelAnswerText, $this->model_question->get_answer_type_id_from_question_id($question->question_id));

            //$userAnswerObject->configure($userAnswerText); // use default answerTypeId
			
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

        //}



        return $returnScore;

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

		// user Answer	   
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

//

//  [WM] 8Aug17: The following is obsolete, commenting out.

//	// =======================================

//    // =======  [WM]: For Automarking  =======

//    // ---------------------------------------

//

//    // Call this for worksheets with ALL subjective questions

//    private function get_student_attempt_score_subjective($attemptId, $worksheetId)

//    {

//        $scoreArray = $this->get_student_attempt_score_array_subjective($attemptId, $worksheetId);

//

//        return array_sum($scoreArray);

//    }

//

//    private function get_student_attempt_score_array_subjective($attemptId, $worksheetId)

//    {

//        $this->load->model('model_question');

//        $questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);

//

//        // User string input. This is an array of string.

//        // [WM]: need to revisit this part and check if need to change tables

//        $userAnswerTextIdArray = $this->model_quiz->get_attempt_answer_textId($attemptId);

//

//        $scoreArray = array();

//        //$score = 0;

//        foreach ($questions->result() AS $question) {

//            // this gets the answerId

//            $modelAnswerText = $this->model_question->get_answer_text_from_answer_id($this->model_question->get_correct_answer_from_question_id($question->question_id));

//

//            $userAnswerText = NULL;

//

//            if (isset($userAnswerTextIdArray[$question->question_number - 1])) {

//                $this->db->select('answerText');

//                $this->db->from('sj_student_answers_text');

//                $this->db->where('answerTextId', $userAnswerTextIdArray[$question->question_number - 1]);

//                $query = $this->db->get();

//

//                $userAnswerText = $query->row()->answerText;

//            }

//

//            // Call automarking class

//            $correctAnswerObject = new Model_subjectiveanswer();

//            $userAnswerObject = new Model_subjectiveanswer();

//

//            $correctAnswerObject->configure($modelAnswerText, $this->model_question->get_answer_type_id_from_question_id($question->question_id));

//            $userAnswerObject->configure($userAnswerText); // use default answerTypeId

//

//

//            $currentScore = $this->model_automarking->Mark($correctAnswerObject, $userAnswerObject);

//            $fullMark = $this->model_question->get_total_marks_from_question_id($question->question_id);

//            $scoreArray[] = $currentScore * $fullMark;

//            //$score += $currentScore;

//        }

//

//        return $scoreArray;

//

//    }

//

//    // [WM] This function determines the threshold for correct answer or determines the state of the answer correctness

//    // eg binary (Correct/Wrong) or some fuzzy logic

//    private function get_answer_correctness($userAnswer, $modelAnswer) {

//	    $threshold_correct = 0.8;

//	    $threshold_manual_check = 0.6;

//        $threshold_fail = 0.3;

//

//	    $score = $this->analyse_similarity($userAnswer, $modelAnswer);

//

//        if($score >= $threshold_correct) {

//            return "Correct";

//        } else if($score >= $threshold_manual_check) {

//            return "Check";

//        } else if($score >= $threshold_fail) {

//            return "Wrong";

//        } else {

//            return "Wrong";

//        }

//    }

//

//    private function analyse_similarity($userAnswer, $modelAnswer) {

//

//	    // empty answer directly exit lowest (negative) score

//	    if($userAnswer == '') {

//            return -1.0;

//        }

//

//        // check if answer and model are completely exact

//        if($userAnswer == $modelAnswer) {

//	        return 1.0;

//        }

//

//        // ====== more elaborate methods begin here ==============

//

//        // 1. Get numerical intersect

//        $excludedSpecialChars = array(" ", ",", "\\", PHP_EOL, "$", "{", "}", "/");

//        $modelAnswerSub = $this->multiexplode($excludedSpecialChars, $modelAnswer);

//        $modelAnswerNumericals = $this->getnumericals($modelAnswerSub);

//        $modelAnswerLiterals = array_diff($modelAnswerSub, $modelAnswerNumericals);

//        $userAnswerSub = $this->multiexplode($excludedSpecialChars, $userAnswer);

//        $userAnswerNumericals = $this->getnumericals($userAnswerSub);

//        $userAnswerLiterals = array_diff($userAnswerSub, $userAnswerNumericals);

//

//        $answerNumericalSimilarityCount = array_intersect($modelAnswerNumericals, $userAnswerNumericals);

//        $answerLiteralSimilarityCount = array_intersect($modelAnswerLiterals, $userAnswerLiterals);

//

//        $percentNumericalIntersect = 100.0 * ( count($answerNumericalSimilarityCount) / count($modelAnswerNumericals) );

//        $percentLiteralIntersect = 100.0 * ( count($answerLiteralSimilarityCount) / count($modelAnswerSub) );

//        if($percentNumericalIntersect == 100.0) {

//            return 1.0;

//        }

//

//

//	    return 0.0;

//    }

//

//    private function multiexplode($delimiters, $string) {

//	    $strtemp = str_replace($delimiters, $delimiters[0], $string);

//	    return explode($delimiters[0], $strtemp);

//    }

//

//    private function getnumericals($stringArray) {

//	    $output = array();

//	    foreach($stringArray as $str) {

//            if(is_numeric($str)) {

//                $output[] = $str;

//            }

//        }

//        return $output;

//    }



    // ---------------------------------------

    // =======  [WM]: Automarking END  =======

    // =======================================

    public function quiz_eimaths($template=0) {
    	$data['template'] = $template;
    	$data['content'] = 'quiz/eimaths_quiz';
    	$this->load->view('include/master_view', $data);
    }

	public function quiz($quizId = NULL) {
		if ($this->session->userdata('is_logged_in') == 1) {
			if (isset($quizId) && empty($quizId) === false) {
				$this->load->model('model_quiz');
				$this->load->model('model_worksheet');
				$this->load->model('model_question');
				$this->load->model('m_question');
				$this->load->model('model_users');
				$question_no_count = 0;
				$a_z = range('A', 'Z');
				//check if the user is authorized to attempt this quiz
				$studentId = $this->session->userdata('user_id');
				$this->session->unset_userdata("quizQuestionList");
				if ($this->model_quiz->is_authorized_to_take_quiz($studentId, $quizId)) {
					$worksheetId = $this->model_worksheet->get_worksheet_id_from_quiz_id($quizId);
					// get the questions, and answers
					$isMockExam = $this->model_worksheet->is_mock_exam($worksheetId);
					$questionList = array();
					if (!$isMockExam) {
						$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);
						$count = count($questions->result()) - 1;
						$questions = $questions->result();
						$totalQuestion = 0;
						foreach ($questions AS $key=>$question) {							

							$questionDetail = array();
							$detail = $this->model_question->get_question_from_id($question->question_id);
							$questionStatus = $this->model_question->get_question_status($question->question_id);
							if($key != $count) {
								$nextDetail = $this->model_question->get_question_from_id($questions[$key+1]->question_id);

								if($questionStatus == TRUE && strtoupper($detail->sub_question) == 'A') {
									
									$questionDetail['showQuestionNoText'] = ++$question_no_count;
								} else {
									$tmp_index = array_search($detail->sub_question, $a_z);
									$show_alpha = '';
									if(strtoupper($detail->sub_question) == 'A') {
										$question_no_count = $question_no_count + 1;
									}
									$show_alpha = strtolower($detail->sub_question);
									$prevDetail = $this->model_question->get_question_from_id($questions[$key-1]->question_id);
									if($detail->reference_id != $nextDetail->reference_id) {
										if($detail->reference_id == $prevDetail->reference_id) {
											$show_alpha = strtolower($detail->sub_question);
										} else {
											$show_alpha = '';
										}
									}
									$questionDetail['showQuestionNoText'] = $question_no_count . $show_alpha;
								}
							} else {
								$prevDetail = $this->model_question->get_question_from_id($questions[$key-1]->question_id);
								if($prevDetail->reference_id != $detail->reference_id && strtoupper($detail->sub_question) == 'A') {
									$questionDetail['showQuestionNoText'] = ++$question_no_count;
								} else {
									$tmp_index = array_search($detail->sub_question, $a_z);
									$show_alpha = '';
									if(strtoupper($detail->sub_question) == 'A') {
										$question_no_count = $question_no_count + 1;
										$show_alpha = '';
									} else {
										$question_no_count = $question_no_count;
										$show_alpha = strtolower($detail->sub_question);
									}
									$questionDetail['showQuestionNoText'] = $question_no_count . $show_alpha;
								}
							}
							
							$totalQuestion++;											
							$firstGroupIdNum = $this->db->query("SELECT question_number FROM sj_worksheet_questions WHERE question_id='$question->question_id' AND group_id=1")->row();
							if($firstGroupIdNum !== NULL){
								$firstGroupIdNum = $firstGroupIdNum->question_number;
							}else{
								$firstGroupIdNum = 0;
							}
							$questionDetail['questionId'] = $detail->question_id;
							$questionDetail['groupId'] = $question->group_id;
							$questionDetail['firstGroupIdNum'] = $firstGroupIdNum;	
							$questionDetail['questionNumber'] = $question->question_number;						
							$questionDetail['questionInstruction'] = $this->model_question->get_question_header_from_question_id($question->question_id, 'instruction');
							$questionDetail['questionArticle'] = $this->model_question->get_question_header_from_question_id($question->question_id, 'article');
							$questionDetail['questionText'] = $detail->question_text;
							$questionDetail['subQuestion'] = $detail->sub_question;
							$questionDetail['question_type_id'] = $detail->question_type_id;
							$questionDetail['question_type'] = $question->question_type;
							$questionDetail['answerOption'] = $this->model_question->get_answer_option_from_question_id($question->question_id);
							$questionDetail['answerOptionFITB'] = $this->model_quiz->getAnswerList($question->question_id, $question->group_id);
							$questionDetail['questionImg'] = $detail->graphical;
							$questionDetail['questionImageUrl'] = $detail->branch_image_url;
							$questionDetail['question_content'] = $detail->question_content;
							$questionDetail['question_content_type'] = $detail->content_type;

							if($detail->question_content == 1){
								$questionDetail['questionContents'] = $this->model_question->get_question_contents_from_question_id($question->question_id);
							}

							$questionList[$question->question_number - 1] = $questionDetail;
							
						}

						// $data['content'] = 'quiz/take_quiz';
						$this->session->set_userdata("quizQuestionList", $questionList);
					} else {
						$requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheetId);
						$postData = array(
							'gen_tutor' => $requirement->me_tutor,
							'gen_me'    => $requirement->me_num,
							'gen_year'  => $requirement->me_year,
							'gen_level' => $requirement->me_level,
							'gen_randomize' => ''
						);
						$questionList = $this->model_question->get_mock_exam_question_list($postData);
						foreach ($questionList as $question) {
							foreach ($question as $subquestion) {
								$subquestion->answerOption = $this->model_question->get_answer_option_from_question_id($subquestion->question_id);
							}
						}
						$data['content'] = 'quiz/take_mock_quiz';
					}
					$output = array();					

					foreach($questionList as $item){
						$output[] = $item;						
					}

					$subject_id = $this->db->get_where('sj_quiz', ['id' => $quizId])->row()->subject_type;

					$data['subjectName'] = $this->m_question->get_subject_by_id($subject_id);
					$data['totalQuestion'] = $totalQuestion;
					$data['quizQuestion'] = $output;
					$data['quizQuestionText'] = json_encode($output);
					$data['quizNumOfQuestion'] = count($output);					
					$data['quizName'] = $this->model_worksheet->get_worksheet_name_from_id($worksheetId);
					$data['quizOwnerId'] = $this->model_worksheet->get_ownerId_from_worksheetId($worksheetId);
					$data['quizOwner'] = $this->model_users->get_username_from_id($data['quizOwnerId']);
					$data['quizID'] = $this->model_quiz->get_quiz_id_from_studentId_worksheetId($studentId, $worksheetId);

					$data['quizAttemptDateTime'] = date('Y-m-d H:i:s');
					$data['question_type'] = $this->model_worksheet->get_question_type_from_requirement($worksheetId);
					$data['quizTime'] = $this->model_worksheet->get_quiz_time_from_requirement($worksheetId);
				} else {
					$data['quizError'] = true;
					$data['quizErrorMessage'] = 'You are not authorized to take this quiz. Please check <a href="'.base_url().'onlinequiz">here</a> for list of available quiz for you';
				}
				$this->load->view('quiz/take_quiz', $data);
			} else { // if no worksheet ID specified, redirect back to home page
				redirect('onlinequiz');
			}
		} else {
			redirect('onlinequiz');
		}
	}


	public function submitQuiz() {

		$postData = $this->input->post();
		
		if (isset($postData) && empty($postData) === false) { //if submit from quiz attempt

			$this->load->model('model_quiz');

			$numOfQuestion = intval($this->input->post('quizNumOfQuestion'));

			$quizID = $this->input->post('quizID');

			$userAnswer = array();
			$userOCR = array();
			$userImg = array();
			$ocrMultiLine = array();
			$userSvg = array();
			for ($i = 0; $i < $numOfQuestion; $i++) {

				$quesNo = 'ques_' . $i;

				$userAnswer[] = $this->input->post($quesNo);

				$ocrRecord = 'ocr_'.$i;
				
				$userOCR[] = $this->input->post($ocrRecord);

				$imgRecord = 'img_'.$i;

				$ocrDigitize = 'ocr_digitize_'.$i;

				$userOCRDIGIT[] = $this->input->post($ocrDigitize);

				$ocrMultiLine = 'ocr_multiLine_'.$i;

				$userMultiLine[] = $this->input->post($ocrMultiLine);

				$svg = 'svg_'.$i;

				$userSvg[] = $this->input->post($svg);

				$ocrQuestion = 'ocr_question_'.$i;

				$userOcrQuestion[] = $this->input->post($ocrQuestion);

				$svgQuestion = 'svg_question_'.$i;

				$userSvgQuestion[] = $this->input->post($svgQuestion);
				
				//convert image as File
				if(!empty($this->input->post($imgRecord))){
					$imageData = base64_decode($this->input->post($imgRecord)); // <-- **Change is here for variable name only**
					$photo = imagecreatefromstring($imageData);
					$imageName = round(microtime(true) * 1000).$i.".jpg";
					//save image into 
					imagejpeg($photo,'./img/studentUpload/'.$imageName,100);

					$userImg[] = $imageName;
				}else{
					
					$userImg[] = "";
				}
			}

			$quizAttemptDateTime = $this->input->post('quizAttemptDateTime');

			$saveAttemptId = $this->model_quiz->save_student_attempt($quizID, $userAnswer, $quizAttemptDateTime, $userOCR, $userImg, $userOCRDIGIT, $userMultiLine, $userSvg, $userOcrQuestion, $userSvgQuestion);

			$this->session->unset_userdata("quizQuestionList");

			if ($saveAttemptId) {
				
				$this->session->set_userdata('quizAttemptID', $saveAttemptId);
				// $this->viewAttempt($saveAttemptId);
				redirect('onlinequiz/viewAttempt');

			}  else {

				log_message('error', 'Error saving student quiz attempt info');

				//error in saving quiz attempt

				redirect('404');

			}

		} else { //redirect back to home page

			redirect('profile');

		}

		

	}



	public function submitMockQuiz() {

		$postData = $this->input->post();

		if (isset($postData) && empty($postData) === false) { //if submit from quiz attempt

			$this->load->model('model_quiz');

			$this->load->model('model_worksheet');

			$this->load->model('model_question');

			$quizID = $this->input->post('quizID');

			$userAnswer = array();

			$worksheetId = $this->model_worksheet->get_worksheet_id_from_quiz_id($quizID);

			$requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheetId);

			$postData = array(

				'gen_tutor' => $requirement->me_tutor,

				'gen_me'    => $requirement->me_num,

				'gen_year'  => $requirement->me_year,

				'gen_level' => $requirement->me_level,

				'gen_randomize' => ''

			);

			$questionList = $this->model_question->get_mock_exam_question_list($postData);

			for ($i = 0, $l = count($questionList); $i < $l; $i++) {

				foreach ($questionList[$i] as $subquestion) {

					$quesNo = 'ques_' . $i . '_' . $subquestion->sub_question;

					$userAnswer[] = $this->applyMathJaxFormat($this->input->post($quesNo));

				}

			}



			$quizAttemptDateTime = $this->input->post('quizAttemptDateTime');

			$saveAttemptId = $this->model_quiz->save_student_attempt($quizID, $userAnswer, $quizAttemptDateTime);

			$this->session->unset_userdata("quizQuestionList");



			if ($saveAttemptId) {

				$this->session->set_userdata('quizSubmitSuccess', true);

				$userId = $this->session->userdata('user_id');

				$this->load->model('model_users');

				$this->load->library('email');

				$user_info = $this->model_users->get_user_info($userId);

				$tutor_info = $this->model_users->get_user_info($this->model_worksheet->get_ownerId_from_worksheetId($worksheetId));



				$this->email->from('noreply@smartjen.com', "SmartJen");

				$this->email->Subject('SmartJen - Quiz Completed by Student');

				$this->email->to($tutor_info->email, $tutor_info->fullname);

				$message = "<p>Dear " . $tutor_info->fullname . ", </p>";

				$message .= "<p>Your student - " . $user_info->fullname . " has completed the assigned quiz. Please login to <a href='".base_url()."'>http://smartjen.com</a> to start marking!</p>";

				$message .= "<br><br><p>SmartJen</p>";



				$this->email->message($message);

				$this->email->send();

				redirect(base_url().'onlinequiz');

			}  else {

				log_message('error', 'Error saving student quiz attempt info');

				//error in saving quiz attempt

				redirect('404');

			}



		} else { //redirect back to home page

			redirect('onlinequiz');

		}

	}



	private function applyMathJaxFormat($ans) {

		if (is_numeric($ans)) {  // is answer ID

			return $ans;

		} else {

			$explodedString = explode('\\ ', $ans);

			$appliedMathJaxFormatToken = array();

			foreach ($explodedString as $str) {

				// if the token starts with backslash, it's a math symbol!

				// or if the string contains ^ , we also need to add the \( and \)

				if (substr($str, 0, 1) == '\\' || strpos($str, '^') !== false) {

					$appliedMathJaxFormatToken[] = '\\(' . $str . '\\)';

				} else {

					$appliedMathJaxFormatToken[] = $str;

				}

			}

			return implode(' ', $appliedMathJaxFormatToken);

		}

	}



	public function viewAttempt($attemptId = NULL) {
		
        // ANOM START
        if($attemptId == NULL){
            $attemptId = $this->session->userdata('quizAttemptID');
        }       
        // END
		
		$this->load->model('model_quiz');

		$this->load->model('model_worksheet');

		$this->load->model('model_question');

		$this->load->model('model_users');

		$userId = $this->session->userdata('user_id');

		if (isset($attemptId) && empty($attemptId) === false) {



			if ($this->model_quiz->is_authorized_to_view_result($attemptId)) {

				$quizId = $this->model_quiz->get_quiz_id_from_attempt_id($attemptId);

				$worksheetId = $this->model_worksheet->get_worksheet_id_from_quiz_id($quizId);

				$isMockExam = $this->model_worksheet->is_mock_exam($worksheetId);

				$userAnswer = $this->model_quiz->get_attempt_answer($attemptId);

				$timeTaken = $this->model_quiz->get_attempt_time_taken($attemptId);

				$hasMarked = $this->model_quiz->get_attempt_has_marked($attemptId);

				$userData = $this->model_quiz->get_attempt_ocr_img($attemptId);

				$scoreArray = array();

				if($hasMarked == TRUE) {

				    $scoreArray = $this->model_quiz->get_attempt_score_array($attemptId);

				}

				$numOfCorrectAnswer = 0;

				$totalFullMarks = 0;

				$totalMarks = 0;

				$curAttemptId = $attemptId;

				$i = 0;

				$questionList = array();

				$question_no_count = 0;
				$a_z = range('A', 'Z');
				if (!$isMockExam) {

					$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);
					$requirement = $this->model_worksheet->get_requirement_from_worksheetId($worksheetId);
					$count = count($questions->result()) - 1;
					$questions = $questions->result();
					foreach ($questions AS $key=>$question) {

						$questionDetail = array();

						$detail = $this->model_question->get_question_from_id($question->question_id);

						$questionStatus = $this->model_question->get_question_status($question->question_id);

						if($key != $count) {
							$nextDetail = $this->model_question->get_question_from_id($questions[$key+1]->question_id);

							if($questionStatus == TRUE && strtoupper($detail->sub_question) == 'A') {
								
								$questionDetail['showQuestionNoText'] = ++$question_no_count;
							} else {
								$tmp_index = array_search($detail->sub_question, $a_z);
								$show_alpha = '';
								if(strtoupper($detail->sub_question) == 'A') {
									$question_no_count = $question_no_count + 1;
								}
								$show_alpha = strtolower($detail->sub_question);
								if($key != 0){
									$prevDetail = $this->model_question->get_question_from_id($questions[$key-1]->question_id);
								}
								if($detail->reference_id != $nextDetail->reference_id) {
									if($detail->reference_id == $prevDetail->reference_id) {
										$show_alpha = strtolower($detail->sub_question);
									} else {
										$show_alpha = '';
									}
								}
								$questionDetail['showQuestionNoText'] = $question_no_count . $show_alpha;
							}
						} else {
							$prevDetail = $this->model_question->get_question_from_id($questions[$key-1]->question_id);
							if($prevDetail->reference_id != $detail->reference_id && strtoupper($detail->sub_question) == 'A') {
								$questionDetail['showQuestionNoText'] = ++$question_no_count;
							} else {
								$tmp_index = array_search($detail->sub_question, $a_z);
								$show_alpha = '';
								if(strtoupper($detail->sub_question) == 'A') {
									$question_no_count = $question_no_count + 1;
									$show_alpha = '';
								} else {
									$question_no_count = $question_no_count;
									$show_alpha = strtolower($detail->sub_question);
								}
								$questionDetail['showQuestionNoText'] = $question_no_count . $show_alpha;
							}
						}

						$questionDetail['questionId'] = $question->question_id;

						$questionDetail['questionType'] = $detail->question_type_id;

						$questionDetail['question_type'] = $question->question_type;

						$questionDetail['questionText'] = $detail->question_text;

						$questionDetail['questionImageUrl'] = $detail->branch_image_url;

						$questionDetail['answerOption'] = $this->model_question->get_answer_option_from_question_id($question->question_id);

						$questionDetail['questionImg'] = $detail->graphical;

						$questionDetail['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($question->question_id);

						$questionDetail['userAnswer'] = (isset($userAnswer[$question->question_number - 1]))?$userAnswer[$question->question_number - 1]:"";

						$questionDetail['generated_question_type'] = $this->model_worksheet->get_generated_question_type($requirement->requirement_id, $question->question_id);

						$questionDetail['videoExplanation'] = $this->model_quiz->get_video_explanation_from_attempt_id($attemptId, $question->question_number);

						// $questionDetail['working'] = $this->model_quiz->get_working_from_attempt_id($attemptId, $question->question_number);
						
						if(isset($userData) && !empty($userData) == TRUE) {
							//ocr
							$questionDetail['ocr'] = (isset($userData[$question->question_number - 1]["ocr"]))?$userData[$question->question_number - 1]["ocr"]:"";

							//img
							$questionDetail['img'] = (isset($userData[$question->question_number - 1]["img"]))?$userData[$question->question_number - 1]["img"]:"";

							//svg
							$questionDetail['svg'] = (isset($userData[$question->question_number - 1]["svg"]))?$userData[$question->question_number - 1]["svg"]:"";

							//svg_tutor
							$questionDetail['svg_tutor'] = (isset($userData[$question->question_number - 1]["svg_tutor"]))?$userData[$question->question_number - 1]["svg_tutor"]:"";

							//svg_tutor_bg
							$questionDetail['svg_tutor_bg'] = (isset($userData[$question->question_number - 1]["svg_tutor_bg"]))?$userData[$question->question_number - 1]["svg_tutor_bg"]:"";

							//svg_tutor_img
							$questionDetail['svg_tutor_img'] = (isset($userData[$question->question_number - 1]["svg_tutor_img"]))?$userData[$question->question_number - 1]["svg_tutor_img"]:"";

							//svg_tutor
							$questionDetail['ocr_svg_tutor'] = (isset($userData[$question->question_number - 1]["ocr_svg_tutor"]))?$userData[$question->question_number - 1]["ocr_svg_tutor"]:"";

							//svg_tutor_bg
							$questionDetail['ocr_svg_tutor_bg'] = (isset($userData[$question->question_number - 1]["ocr_svg_tutor_bg"]))?$userData[$question->question_number - 1]["ocr_svg_tutor_bg"]:"";

							//svg_tutor_img
							$questionDetail['ocr_svg_tutor_img'] = (isset($userData[$question->question_number - 1]["ocr_svg_tutor_img"]))?$userData[$question->question_number - 1]["ocr_svg_tutor_img"]:"";

							//multiline
							$questionDetail['multiLine'] = (is_numeric($userData[$question->question_number - 1]["multiLine"]))?$userData[$question->question_number - 1]["multiLine"]: 0;
						}

						$questionDetail['questionTypes'] = $question->question_type; //$this->model_worksheet->get_question_type_from_requirement($worksheetId);

						$questionDetail['correctAnswerText'] = $this->model_question->get_correct_answer_text_from_correct_id($questionDetail['correctAnswer']);

						$questionDetail['solutionAnswerText'] = $this->model_question->get_solution_answer_text_from_correct_id($questionDetail['correctAnswer']);

						$questionDetail['solutionAnswerType'] = $this->model_question->get_solution_answer_type_from_correct_id($questionDetail['correctAnswer']);

						$questionDetail['question_content'] = $detail->question_content;

						$questionDetail['question_content_type'] = $detail->content_type;
						
						if($detail->question_content == 1){
							$questionDetail['solutionWorkingContents'] = $this->model_question->get_working_contents_from_question_id($question->question_id);

							$questionDetail['questionContents'] = $this->model_question->get_question_contents_from_question_id($question->question_id);
						} 

						$remark = $this->model_quiz->get_remarks_from_attempt_id($attemptId, $question->question_number);

						$questionDetail['remark'] = $remark;


                        $fullMarks = $this->model_question->get_total_marks_from_question_id($question->question_id);

                        $questionDetail['fullMarks'] = $fullMarks;

                        $totalFullMarks += $fullMarks;



						if($hasMarked == TRUE) {

                            $score = $scoreArray[$question->question_number - 1];

                            if($score < 0.0) { // this means score was not previously saved. Automark again.
                                $score = $this->get_score_single_question($question, $questionDetail['userAnswer'], $questionDetail['questionTypes']);

                                $this->model_quiz->save_score_for_answer($attemptId, $question->question_number, $score);

                            }

						} else { //  this means score was not obtained yet, do automarking and save it
							
                            $score = $this->get_score_single_question($question, $questionDetail['userAnswer'], $questionDetail['questionTypes']);

                            $this->model_quiz->save_score_for_answer($attemptId, $question->question_number, $score);

                        }

						$questionDetail['userScore'] = $score;

						$totalMarks += $score;



						$questionList[$question->question_number - 1] = $questionDetail;

						$i++;

					}

					$data['content'] = 'quiz/quiz_result';

				} else {

					$requirement = $this->model_worksheet->get_me_requirement_from_worksheetId($worksheetId);

					$postData = array(

						'gen_tutor' => $requirement->me_tutor,

						'gen_me'    => $requirement->me_num,

						'gen_year'  => $requirement->me_year,

						'gen_level' => $requirement->me_level,

						'gen_randomize' => ''

					);

					$questions = $this->model_question->get_mock_exam_question_list($postData);

					$questionNumber = 1;

					foreach ($questions as $question) {

						foreach ($question as $subquestion) {

							$questionDetail = array();

                            $questionDetail['questionId'] = $subquestion->question_id;

							$questionDetail['questionType'] = $subquestion->question_type_id;
							$questionDetail['questionText'] = $subquestion->question_text;

							$questionDetail['answerOption'] = $this->model_question->get_answer_option_from_question_id($subquestion->question_id);

							$questionDetail['questionImg'] = $subquestion->graphical;

							$questionDetail['questionImageUrl'] = $subquestion->branch_image_url;
							$questionDetail['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($subquestion->question_id);

							$questionDetail['userAnswer'] = (isset($userAnswer[$questionNumber - 1]))?$userAnswer[$questionNumber - 1]:"";

							$fullMarks = $this->model_question->get_total_marks_from_question_id($subquestion->question_id);

							$questionDetail['fullMarks'] = $fullMarks;
							$totalFullMarks += $fullMarks;



                            if($hasMarked == TRUE) {

                                $score = $scoreArray[$questionNumber - 1];

                                if($score < 0.0) { // this means score was not previously saved. Automark again.

                                    $score = $this->get_score_single_question($subquestion, $questionDetail['userAnswer'], $questionDetail['questionType']);

                                    $this->model_quiz->save_score_for_answer($attemptId, $questionNumber, $score);

                                }

                            } else { //  this means score was not obtained yet, do automarking and save it

                                $score = $this->get_score_single_question($subquestion, $questionDetail['userAnswer'], $questionDetail['questionType']);

                                $this->model_quiz->save_score_for_answer($attemptId, $questionNumber, $score);

                            }



                            $questionDetail['userScore'] = $score;

							$totalMarks += $score;



							$questionList[$questionNumber - 1] = $questionDetail;
							$questionNumber++;

						}

					}

					$data['content'] = 'quiz/mock_quiz_result';

				}

				$this->model_quiz->save_attempt_score($attemptId, $totalMarks);

				$data['isTutor'] = $this->model_users->get_user_role_from_user_id($this->session->userdata('user_id')) == 1;
				$data['questionList'] = $questionList;
				$data['questions'] = $questions;
				$data['totalMarks'] = $totalMarks;
				$data['totalFullMarks'] = $totalFullMarks;
				$data['timeTaken'] = $timeTaken;
				$data['attemptId'] = $attemptId;
				$data['user_id'] = $userId;
				
			} else { // not authorized

				$data['content'] = 'quiz/quiz_result';

				$data['viewError'] = true;

				$data['viewErrorMessage'] = 'You are not authorized to view the result. Click <a href="'.base_url().'onlinequiz">here</a> to back to quiz home page';

			}

			



		} else { // no attempt ID specified

			redirect('onlinequiz');

		}



		

		$this->load->view('include/master_view', $data);

	}



	public function getQuizQuestion($questionNum) {

		if ($this->input->is_ajax_request()) {

			$questionList = $this->session->userdata('quizQuestionList');

			$questionDetail = $questionList[$questionNum];
			echo json_encode($questionDetail);

		} else {

			redirect('404');

		}

	}



	public function modifyMarks(){

        $this->load->model('model_quiz');



        $postData = $this->input->post();

        $attemptId = $postData['attemptId'];

        $marks = $postData['selectedMarks'];

        $questionNo = $postData['questionNo'];



        $this->model_quiz->save_score_for_answer($attemptId, $questionNo, $marks);



        $totalMarks = $this->refreshTotalMarks($attemptId);



        echo json_encode($totalMarks);

    }



    public function refreshTotalMarks($attemptId) {

	    $this->load->model('model_quiz');

        $scoreArray = $this->model_quiz->get_attempt_score_array($attemptId);

        $totalscore = 0;

        foreach($scoreArray as $score) {

            $totalscore += $score;

        }

        $this->model_quiz->save_attempt_score($attemptId, $totalscore);



        return $totalscore;

	}
	
	public function showSVG($attemptId = "", $questionNo = "", $column = 'svg')
	{
		$this->load->model('model_quiz');
		// header('Content-Type: application/svg+xml');
		/* echo '<?xml version="1.0"?>'; */
		$svg = $this->model_quiz->get_attempt_ocr_svg($attemptId, $questionNo, $column);
		$svg_layer = explode('<svg data-layer="CAPTURE"',$svg);
		$svg = (isset($svg_layer[0])) ? $svg_layer[0] : $svg;
		$svg_file = str_replace('<div class="loader" style="display: none;"></div>', '', $svg);

		$svg_file = str_replace('<div class="error-msg" style="display: none;"></div>', '', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="568px" height="270px" viewBox="0 0 568, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1108px" height="270px" viewBox="0 0 1108, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1118px" height="270px" viewBox="0 0 1118, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1088px" height="270px" viewBox="0 0 1088, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1108px" height="570px" viewBox="0 0 1108, 570">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', '', $svg_file);

		$svg_file = str_replace('</svg>', '', $svg_file);
		//	$svg_file = str_replace('1138','598',$svg_file);
		$svg_file = str_replace('<svg data-layer="MODEL"', '<svg data-layer="MODEL" id="mainsvg" version="1.1" xmlns="http://www.w3.org/2000/svg" ', $svg_file);
		$svg_file = $svg_file . '</svg>';
		header('Content-type: image/svg+xml');

		echo '<?xml version="1.0" standalone="no"?>
		<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
		"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
		echo $svg_file;
	}

	public function showSVGQuestion($attemptId = "", $questionNo = "", $column = 'svg_question_image')
	{
		$this->load->model('model_quiz');
		// header('Content-Type: application/svg+xml');
		/* echo '<?xml version="1.0"?>'; */
		$svg = $this->model_quiz->get_attempt_ocr_svg($attemptId, $questionNo, $column);
		$svg_layer = explode('<svg data-layer="CAPTURE"',$svg);
		$svg = (isset($svg_layer[0])) ? $svg_layer[0] : $svg;
		$svg_file = str_replace('<div class="loader" style="display: none;"></div>', '', $svg);

		$svg_file = str_replace('<div class="error-msg" style="display: none;"></div>', '', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="568px" height="270px" viewBox="0 0 568, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1108px" height="270px" viewBox="0 0 1108, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1118px" height="270px" viewBox="0 0 1118, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1088px" height="270px" viewBox="0 0 1088, 270">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1108px" height="570px" viewBox="0 0 1108, 570">', '<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', $svg_file);
		$svg_file = str_replace('<svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">', '', $svg_file);

		$svg_file = str_replace('</svg>', '', $svg_file);
		//	$svg_file = str_replace('1138','598',$svg_file);
		$svg_file = str_replace('<svg data-layer="MODEL"', '<svg data-layer="MODEL" id="mainsvg" version="1.1" xmlns="http://www.w3.org/2000/svg" ', $svg_file);
		$svg_file = $svg_file . '</svg>';
		header('Content-type: image/svg+xml');

		echo '<?xml version="1.0" standalone="no"?>
		<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
		"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
		echo $svg_file;
	}

    public function showUploaded($attemptId="",$questionNo="") {
    	$column = 'img';
    	$this->load->model('model_quiz');
    	$img = $this->model_quiz->get_attempt_ocr_svg($attemptId,$questionNo,$column);
		$img = base_url().'img/studentUpload/'.$img;
		$image = file_get_contents($img);

		header('Content-type: image/jpeg;');
		header("Content-Length: " . strlen($image));
		echo $image;
	}

    public function showTutorSVG($attemptId="",$questionNo="") {
    	$this->load->model('model_quiz');
    	$content_show = base_url() ."Onlinequiz/showSVG/".$attemptId."/".$questionNo."/".'svg_tutor';
    	$bg_content = $this->model_quiz->get_attempt_ocr_svg($attemptId,$questionNo,'svg_tutor_bg');
    	$svg_file = '<style>.combine_svg {
			background-image: url(\''.$bg_content.'\');
				background-repeat: no-repeat;
				background-size: contain;
				height: 270px;
				width: 598px;
			}</style>';
    	$svg_file .= '<div class="combine_svg"><img src="'.$content_show.'?'.time().'" /></div>';
    	$svg_file .= '<input type="hidden" id="get_svg_tutor_bg" value="'. $bg_content .'" />';
		echo $svg_file;
    }

    public function saveWork(){
        $this->load->model('model_quiz');
        $post = $this->input->post();
        $attemptId = $post['attemptId'];
        $svg_tutor = $post['svg_tutor'];
        $ocr_tutor = $post['ocr_tutor'];
        $save_to = $post['save_to'];
        $questionNo = $post['questionNo'];
        $updated_working = $this->model_quiz->save_working($attemptId, $questionNo, $svg_tutor, $ocr_tutor, $save_to);
        echo json_encode($updated_working);
	}

  //   public function ProcessPicture() {
  //   	$this->load->model('model_quiz');
  //       $post = $this->input->post();
  //       $attemptId = $post['attemptId'];
  //       $img_tutor_bg = $post['img_tutor_bg'];
  //       $questionNo = $post['questionNo'];
  //   	//receive the posted data in php
		// $imageData = base64_decode($img_tutor_bg);
		// $photo = imagecreatefromstring($imageData);
		// $imageName = $attemptId."_".$questionNo.".jpg";
		// //save image into 
		// imagejpeg($photo,'./img/studentUpload/'.$imageName,100);

		// // echo '<img src="'.base_url().'/img/studentUpload/'.$imageName.'" />';
  //       // $updated_working = $this->model_quiz->save_tutor_bg($attemptId, $questionNo, $imageName);
  //       echo json_encode($imageName);
  //   }

    
    public function updateRemark(){

        $this->load->model('model_quiz');

        $post = $this->input->post();

        $attemptId = $post['attemptId'];

        $remark = $post['remark'];

        $questionNo = $post['questionNo'];

        $updated_remark = $this->model_quiz->update_remark($attemptId, $questionNo, $remark);

        echo json_encode($updated_remark);
	}
	
	public function upload_answerImg(){
		if ($this->input->is_ajax_request()) {
			$this->load->model('model_quiz');
			$this->load->model('sj_quiz_attempt_answer');
			//get normal data
			$quesId = $_POST['questionId'];
			$qId = $_POST['quizId'];
			$aId = $_POST['attempt_id'];
			$sId = $this->session->userdata('user_id');

			if(isset($_FILES['img']['name'])){
				$config['upload_path'] = 'img/studentUpload';
				$now = new DateTime();
				$dateTime = $now->getTimeStamp();
				$config['file_name'] = $dateTime;
				
				$this->load->library('upload',$config);

				$allowed_types = array('jpg', 'png', 'jpeg');
				$file_name = $_FILES['img']['name'];
				$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

				if (in_array($ext, $allowed_types)) {
					if (!$this->upload->do_upload('img')){
						//display error
						
					} else {
						//update db
						$data = array(
							'question_id' => $quesId,
							'quiz_id' => $qId,
							'user_id' => $sId,
							'attempt_id' => $aId,
							'img_path'=> $file_name
						);
						$this->$model_quiz->upload_student_photo($data);
						echo json_encode();
					}
				}
				
			}
			
		} else {
			redirect('404');
		}
	}

	public function saveVideoExplanation(){
		$this->load->model('model_quiz');
		if($this->model_quiz->saveVideoExplanation() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
	}
}

