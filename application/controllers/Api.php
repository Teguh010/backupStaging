<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function index() {

		# test auto deploy

	}

	public function quiz($quizId = NULL) {

		if (isset($quizId) && empty($quizId) === false) {

			$post = $this->input->post();



			if (isset($post) && empty($post) === false) { // got post data

				$this->load->model('model_quiz');

				$quizAttemptDateTime = $this->input->post('quizAttemptDateTime');

				$quizID = $this->input->post('quizID');

				$userAnswer = array();

				$postAnswers = json_decode($this->input->post('Answers'));

				for ($i = 0 , $count = count($postAnswers); $i < $count; $i++) {

					$userAnswer[] = $postAnswers[$i];

				}



				$saveAttemptIds = $this->model_quiz->save_student_attempt($quizID, $userAnswer, $quizAttemptDateTime);



				$returnArray = array();

				$returnArray['quizAttemptId'] = $saveAttemptIds['quizAttemptID'];

				$returnArray['questionAttemptIDs'] = $saveAttemptIds['questionAttemptIDs'];



				echo json_encode($returnArray);





			} else {

				$this->load->model('model_quiz');

				$this->load->model('model_worksheet');

				$this->load->model('model_question');

				$this->load->model('model_users');



				//check if the user is authorized to attempt this quiz

				// $studentId = $this->session->userdata('user_id');

				$quiz = array();

				$worksheetId = $this->model_worksheet->get_worksheet_id_from_quiz_id($quizId);



				if (isset($worksheetId) && $worksheetId != NULL) {

					// get the questions, and answers

					$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);

					$questionList = array();

					foreach ($questions->result() AS $question) {

						$questionDetail = array();

						$detail = $this->model_question->get_question_from_id($question->question_id);

						$questionDetail['questionID'] = $question->question_id;

						$questionDetail['questionText'] = $detail->question_text;

						$questionDetail['answerOption'] = $this->model_question->get_answer_option_from_question_id($question->question_id);

						$questionDetail['questionImg'] = $detail->graphical;

						$questionList[$question->question_number - 1] = $questionDetail;

					}



					$quiz['quizQuestion'] = $questionList;

					$quiz['quizNumOfQuestion'] = count($questionList);

					$quiz['quizName'] = $this->model_worksheet->get_worksheet_name_from_id($worksheetId);

					$quiz['quizOwner'] = $this->model_users->get_username_from_id($this->model_worksheet->get_ownerId_from_worksheetId($worksheetId));

					$quiz['quizID'] = $quizId;

					$quiz['quizAttemptDateTime'] = date('Y-m-d H:i:s');



					// echo str_replace('\\\\', '\\', json_encode($quiz));

					echo json_encode($quiz);

				} else {

					$quiz['error'] = "Non existent quiz ID";

					echo json_encode($quiz);

				}

			}

			

			

		} 

		



	}



	public function submitImage() {

		$error = false;

		$returnArray = array();



		$config['upload_path'] = 'img/studentQuizWorking';

		$config['allowed_types'] = 'gif|jpg|png|jpeg';

		$config['max_size']    = '1024';

		$config['overwrite'] = 'true';



		$this->load->model('model_quiz');

        $this->load->library('upload');

        $this->upload->initialize($config);



        // print_r($_FILES);

        if (isset($_FILES['image'])) {

        	for ($i = 0 ; $i < count($_FILES['image']['size']); $i++) {

				$file_name = $_FILES['image']['name'][$i];

				$_FILES['userfile']['name']     = $file_name;

				$_FILES['userfile']['type']     = $_FILES['image']['type'][$i];

		        $_FILES['userfile']['tmp_name'] = $_FILES['image']['tmp_name'][$i];

		        $_FILES['userfile']['error']    = $_FILES['image']['error'][$i];

		        $_FILES['userfile']['size']     = $_FILES['image']['size'][$i];  



		        $explodedFileName = explode(".", $file_name);

		        $name = explode("_", $explodedFileName[0]);

		        $attemptId = $name[0];

		        $imageType = $name[1]; //snapshot or draft



		        $wrongImageType = false;

		        switch ($imageType) {

		        	case 'snapshot':

		        		$imageTypeIdx = 1;

		        		break;



		        	case 'draft':

		        		$imageTypeIdx = 2;

		        		break;



		        	default:

		        		$returnArray[$attemptId][$imageType] = 'Wrong image type';

		        		$wrongImageType = true;

		        }



		        if ($wrongImageType) {

		        	$error = true;

		        	continue;

		        }



		        if ($this->upload->do_upload()) {

		        	$returnArray[$attemptId][$imageType] = array();

		        	if (!$this->model_quiz->save_student_working_image($attemptId, $file_name, $imageTypeIdx)) {

						$error = true;

						$returnArray[$attemptId][$imageType]['response'] = 'Failure in inserting into database';

					} else {

						$data = $this->upload->data();

						$returnArray[$attemptId][$imageType]['response'] = 'Success in uploading file';

						$returnArray[$attemptId][$imageType]['uploaded_file'] = $data;



					}

		        } else {

		        	$returnArray[$attemptId][$imageType]['response'] = $this->upload->display_errors();

		        	$error = true;

		        }

			}

        } else {

        	$error = true;

        	$returnArray['response'] = 'image[] not set';

        }

		



		if ($error) {

			$returnArray['success'] = 'false';

		} else {

			$returnArray['success'] = 'true';

		}



		echo json_encode($returnArray);



	}



	public function newQuiz($quizId = NULL) {

		if (isset($quizId) && empty($quizId) === false) {

			$this->load->model('model_quiz');

			$this->load->model('model_worksheet');

			$this->load->model('model_question');

			$this->load->model('model_users');



			//check if the user is authorized to attempt this quiz

			// $studentId = $this->session->userdata('user_id');

			$quiz = array();

			$worksheetId = $this->model_worksheet->get_worksheet_id_from_quiz_id($quizId);



			if (isset($worksheetId) && $worksheetId != NULL) {

				// get the questions, and answers

				$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);

				$questionList = array();

				$questionID = array();

				foreach ($questions->result() AS $question) {

					$questionDetail = array();

					$detail = $this->model_question->get_question_from_id($question->question_id);

					$questionDetail['questionText'] = $detail->question_text;

					$questionDetail['answerOption'] = $this->model_question->get_answer_option_from_question_id($question->question_id);

					$questionDetail['questionImg'] = $detail->graphical;

					$questionList[$question->question_number - 1] = $questionDetail;

				}



				$newQuestionList = array();

				$quizQuestionID = array();

				for ($i = 0 ; $i < count($questionList) ; $i++) { 

					$newQuestionList[] = $questionList[$i];

				}



				$quiz['quizQuestion'] = $newQuestionList;

				$quiz['quizNumOfQuestion'] = count($questionList);

				$quiz['quizName'] = $this->model_worksheet->get_worksheet_name_from_id($worksheetId);

				$quiz['quizOwner'] = $this->model_users->get_username_from_id($this->model_worksheet->get_ownerId_from_worksheetId($worksheetId));

				$quiz['quizID'] = $quizId;

				$quiz['quizAttemptDateTime'] = date('Y-m-d H:i:s');

				$quiz['quizQuestionID'] = $quizQuestionID;



				echo str_replace('\\\\', '\\', json_encode($quiz));

			} else {

				$quiz['error'] = "Non existent quiz ID";

				echo json_encode($quiz);

			}

			

		} 

		



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



	public function login() {

		$post = $this->input->post();



		$returnData = array();

		//got post data

		if (isset($post) && empty($post) === false) {



			$this->load->model('model_users');

			$this->load->model('model_worksheet');

			$this->load->model('model_quiz');



			if ($this->model_users->can_login()) {

				$userId = $this->model_users->get_user_id_from_email_or_username($this->input->post('login_email'));

				$returnData['login'] = true;

				$returnData['userId'] = $userId;



				if ($this->model_users->get_user_role_from_user_id($userId) == 2) { // student

					$quizzes = $this->model_users->get_quiz_list($userId);

					$quizList = array();

					foreach ($quizzes as $quiz) {

						$quizArray = array();

						$quizArray['id'] = $quiz->id;

						$quizArray['quizLink'] = base_url() . 'api/quiz/' . $quiz->id;

						$quizArray['assignedDate'] = $quiz->assignedDate;

						$quizArray['name'] = $this->model_worksheet->get_worksheet_name_from_id($quiz->worksheetId);

						$quizArray['createdBy'] = $this->model_users->get_username_from_id($this->model_worksheet->get_ownerId_from_worksheetId($quiz->worksheetId));

						$quizArray['numOfAttempt']  = $this->model_quiz->count_number_of_attempt($quiz->id);

						if ($quizArray['numOfAttempt'] > 0) {

							$quizArray['lastCompletedDate'] = $this->model_quiz->get_last_attempt_date($quiz->id);

						} else {

							$quizArray['lastCompletedDate'] = -1;

						}

						$quizArray['attemptIds'] = $this->model_quiz->get_attempt_ids($quiz->id);



						$quizList[] = $quizArray;

					}

					

					$returnData['listOfQuiz'] = $quizList;

				}



			} else {

				$returnData['login'] = false;

			}

		} else {

			$returnData['login'] = false;

			

		}



		echo json_encode($returnData);

	}



	public function quizList($userId = NULL) {

		$returnArray = array();

		if (isset($userId) && empty($userId) === false) {

			$this->load->model('model_users');

			$this->load->model('model_worksheet');

			$this->load->model('model_quiz');

			$quizzes = $this->model_users->get_quiz_list($userId);

			foreach ($quizzes as $quiz) {

				$quizArray = array();

				$quizArray['id'] = $quiz->id;

				$quizArray['quizLink'] = base_url() . 'api/quiz/' . $quiz->id;

				$quizArray['name'] = $this->model_worksheet->get_worksheet_name_from_id($quiz->worksheetId);

				$quizArray['createdBy'] = $this->model_users->get_username_from_id($this->model_worksheet->get_ownerId_from_worksheetId($quiz->worksheetId));

				$quizArray['numOfAttempt']  = $this->model_quiz->count_number_of_attempt($quiz->id);

				if ($quizArray['numOfAttempt'] > 0) {

					$quizArray['lastCompletedDate'] = $this->model_quiz->get_last_attempt_date($quiz->id);

				} else {

					$quizArray['lastCompletedDate'] = -1;

				}

				$quizArray['attemptIds'] = $this->model_quiz->get_attempt_ids($quiz->id);



				$returnArray[] = $quizArray;

			}



			if (count($returnArray) > 0 ) {

				echo json_encode($returnArray);	



			} else {

				$returnArray['error'] = "No Quiz Available";

				echo json_encode($returnArray);

			}

			

		} else {

			echo json_encode(array("Invalid User Id"));

		}

		

	}



	public function basicQuizResult($attemptId) {

		$userId = $this->input->post('userId');

		$this->load->model('model_quiz');

		$this->load->model('model_worksheet');

		$this->load->model('model_question');



		if (isset($userId) && empty($userId) === false) {

			if ($this->model_quiz->is_authorized_to_view_result($attemptId, $userId)) {

				$quizId = $this->model_quiz->get_quiz_id_from_attempt_id($attemptId);

				$worksheetId = $this->model_worksheet->get_worksheet_id_from_quiz_id($quizId);



				$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($worksheetId);

				$userAnswer = $this->model_quiz->get_attempt_answer($attemptId);



				$timeTaken = $this->model_quiz->get_attempt_time_taken($attemptId);



				$questionList = array();

				$numOfCorrectAnswer = 0;

				$i = 0;

				foreach ($questions->result() AS $question) {

					$questionDetail = array();

					$detail = $this->model_question->get_question_from_id($question->question_id);

					$questionDetail['questionText'] = $detail->question_text;

					$questionDetail['answerOption'] = $this->model_question->get_answer_option_from_question_id($question->question_id);

					$questionDetail['questionImg'] = $detail->graphical;

					$questionDetail['correctAnswer'] = $this->model_question->get_correct_answer_from_question_id($question->question_id);

					$questionDetail['userAnswer'] = (isset($userAnswer[$question->question_number - 1]))?$userAnswer[$question->question_number - 1]:-1;



					if ($questionDetail['userAnswer'] == $questionDetail['correctAnswer']) {

						$numOfCorrectAnswer++;

					}



					$questionList[$question->question_number - 1] = $questionDetail;

					$i++;

				}



				$returnArray = array();



				$returnArray['questionList'] = $questionList;

				$returnArray['numOfQuestion'] = count($questionList);

				$returnArray['numOfCorrectAnswer'] = $numOfCorrectAnswer;

				$returnArray['timeTaken'] = $timeTaken;



				echo json_encode($returnArray);



			} else {

				echo json_encode(array("error" => "User is not authorized to view quiz results"));

			}

		

		} else {

			echo json_encode(array("error" => "User ID is required"));

		}

		

	}



	public function getBasicAnalysis($userId) {

		$this->load->model('model_users');

		$structure = $this->model_users->get_performance_api($userId);

		echo json_encode($structure);

	}



	public function _old_getBasicAnalysis($userId) {

		$this->load->model('model_users');

		$structure = $this->model_users->get_performance(array($userId));

		echo json_encode($structure);

	}



	public function getStrandStructure() {

		$this->load->model('model_question');

		echo json_encode($this->model_question->get_strand_structure());

	}



	public function testAnalysis() {

		$userId = array(5, 11);

		$this->load->model('model_users');

		$structure = $this->model_users->get_performance($userId);

		echo json_encode($structure);

	}



	public function getRandomQuestionByLevel($level) {

		$this->load->model('model_question');

		$question = $this->model_question->get_random_question_by_level($level);

		echo json_encode($question);

	}


}