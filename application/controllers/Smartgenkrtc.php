<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Smartgenkrtc extends MY_Controller {
    public function __construct()
    {
            parent::__construct();
    }

	public function index() {
		$this->designWorksheetkrtc();
	}

	public function designWorksheetkrtc($worksheetId = NULL) {
        if (isset($worksheetId) && empty($worksheetId) === false) {
			//if user is logged in, check if the worksheetId is tie to the user
			if ($this->session->userdata('is_logged_in') == 1) {
				$this->load->model('model_worksheet');
				$ownedByUser = $this->model_worksheet->check_worksheet_owner($worksheetId, $this->session->userdata('user_id'));
				//if worksheet is not tie to user, redirect back without worksheetId
				if (!$ownedByUser) {
					redirect(base_url().'smartgenkrtc');
				}
				$data['worksheetId'] = $worksheetId;
			} else { //else redirect back without worksheetId
				redirect(base_url().'smartgen');
			}
		}
		$this->load->model('model_question');
        $this->load->model('model_users');
		$data['content'] = 'smartgenkrtc/smartgen_home';

		// TODO: to be made into dynamic
		$data['mock_exams']  = array("ME1" => "ME1", "ME2" => "ME2", "ME4" => "ME4", "ME5" => "ME5");
		$tutors = array();
        $tutors[] = $this->model_users->get_user_info(20);  // hardcode to oliver first
		$tutors[] = $this->model_users->get_user_info(47);  // hardcode to wanzhen as well
        $data['tutors'] = $tutors;
		$data['years'] = array("2017" => "2017");
		$data['levels'] = $this->model_question->get_mock_exam_level_list();

        $selectedTutor = $this->session->userData('MESelectedTutor');
		$selectedMe = $this->session->userData('MESelectedMe');
		$selectedYear = $this->session->userData('MESelectedYear');
		$selectedRandomize = $this->session->userData('MERandomize');
		$selectedLevel = $this->session->userData('MESelectedLevel');

        if (isset($selectedTutor)) {
            $data['selectedTutor'] = $selectedTutor;
        }

        if (isset($selectedMe)) {
            $data['selectedMe'] = $selectedMe;
        }

		if (isset($selectedYear)) {
			$data['selectedYear'] = $selectedYear;
		}

		if (isset($selectedRandomize)) {
			$data['selectedRandomize'] = $selectedRandomize;
		}

		if (isset($selectedLevel)) {
			$data['selectedLevel'] = $selectedLevel;
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

	public function generateWorksheet($worksheetId = NULL) {
		if (isset($worksheetId) && empty($worksheetId) === false) {
			$this->check_worksheet_owner($worksheetId, '404');
            $data['worksheetId'] = $worksheetId;
		}
		$this->load->model('model_question');
		$postData = $this->input->post();
		// $sessionData = $this->session->userdata('questionArray');
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

			//submit from regenerate all button
			if (isset($postData['regenerateWorksheet']) && empty($postData['regenerateWorksheet']) === false) {
				$postData['gen_tutor']     = $this->session->userdata('MESelectedTutor');
				$postData['gen_me']        = $this->session->userdata('MESelectedMe');
				$postData['gen_year']      = $this->session->userdata('MESelectedYear');
				$postData['gen_randomize'] = $this->session->userdata('MERandomize');
				$postData['gen_level']     = $this->session->userdata('MESelectedLevel');
			} else {
				$randomize = $this->input->post('gen_randomize');
				//save requirement in session
				$sessionArray = array(
					'MESelectedTutor'      => $this->input->post('gen_tutor'),
					'MESelectedMe'         => $this->input->post('gen_me'),
					'MESelectedYear'       => $this->input->post('gen_year'),
					'MERandomize'          => (isset($randomize) && $randomize == 1)?1:0,
					'MESelectedLevel'      => $this->input->post('gen_level'),
				);
				
				$this->session->set_userdata($sessionArray);
			}
			$this->load->model('model_worksheet');
			$this->model_worksheet->save_mock_exam_worksheet_requirement($postData);
			$questionList = $this->model_question->get_mock_exam_question_list($postData);
			foreach ($questionList as $question) {
				$categorySubList = array();
				$substrandSubList = array();
				foreach ($question as $subquestion) {
					$categorySubList[] = $this->model_question->get_category_from_question_id($subquestion->question_id);
                	$substrandSubList[] = $this->model_question->get_substrand_from_question_id($subquestion->question_id);
				}
				$categoryList[] = $categorySubList;
				$substrandList[] = $substrandSubList;
			}
			
		} else if (isset($sessionData)) {
			// foreach ($sessionData AS $questionId) {
			// 	$questionDetail = $this->model_question->get_question_from_id($questionId);
			// 	$questionList[] = $questionDetail;
			// 	$categoryList[] = $this->model_question->get_category_from_question_id($questionId);
            //     $substrandList[] = $this->model_question->get_substrand_from_question_id($questionId);
			// }

		} else {
			redirect(base_url().'smartgenkrtc');
		}
		
		//get the answers from here
		$answerList = $this->model_question->get_mock_answer_list($questionList);
		$data['answerList'] = $answerList;
		$data['questionList'] = $questionList;
		$data['categoryList'] = $categoryList;
        $data['substrandList'] = $substrandList;
		$data['content'] = 'smartgenkrtc/smartgen_generateWorksheet';
		$this->load->view('include/master_view', $data);
	}


	public function saveWorksheet() {
		$this->load->model('model_worksheet');
		$worksheetName = $this->input->post('worksheet_name');
		//proceed only if worksheetname is set
		if (isset($worksheetName) && empty($worksheetName) === false) {
			//actually login check is already done on the interface
			if ($this->session->userdata('is_logged_in') == 1) {
				$worksheetId = $this->model_worksheet->save_mock_exam_worksheet($worksheetName);
				if ($worksheetId) {
					$this->session->set_userdata('save_worksheet_success', "Worksheet saved");
					redirect(base_url()."smartgenkrtc/assignWorksheet/".$worksheetId);
				} else {
					//some error in saving worksheet, redirect for proper handling
                    redirect('404');
				}
				
			} 
		} else {
			redirect(base_url()."smartgenkrtc/generateWorksheet");
		}
	}

	public function saveExistingWorksheet($worksheetId) {
		$this->load->model('model_worksheet');
		$saveSuccess = $this->model_worksheet->save_existing_worksheet($worksheetId);
		if ($saveSuccess) {
			redirect(base_url().'smartgenkrtc/assignWorksheet/'.$worksheetId);
		} else {
            redirect('404');
		}
	}

    public function assignWorksheet($worksheetId) {
        $postData = $this->input->post();
        $this->load->model('model_users');
        $this->load->model('model_quiz');
        if (isset($postData) && empty($postData) === false) {
            $assigned_students = (isset($postData['assigned_students']) && empty($postData['assigned_students']) === false)?$postData['assigned_students']:array();
            if ($this->model_quiz->assign_student($worksheetId, $assigned_students)) {

                //send notification email to the student
                $this->load->library('email');
                $tutor_name = $this->model_users->get_username_from_id($this->session->userdata('user_id'));

                foreach ($assigned_students as $assigned_id) {
                    $this->email->from('hello@smartjen.com', "Smartjen");
                    $this->email->Subject('SmartJen - Quiz Assignment Notification');
                    $user_info = $this->model_users->get_user_info($assigned_id);
                    $this->email->to($user_info->email, $user_info->fullname);
                    $message = "<p>Dear " . $user_info->fullname . ", </p>";
                    $message .= "<p>A quiz has been assigned to you by " . $tutor_name . ". Please login to <a href='".base_url()."'>http://smartjen.com</a> to complete your quiz now!</p>";
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
            $assigned_students = $this->model_quiz->get_assigned_list($worksheetId);
            $my_students = $this->model_users->get_student_list($this->session->userdata('user_id'));

            $not_assigned_students = array();
            $assigned_students_ids = array();
            foreach ($assigned_students as $assigned) {
                $assigned_students_ids[] = $assigned->id;
            }

            foreach ($my_students as $temp) {
                if (!in_array($temp->student_id, $assigned_students_ids)) {
                    $not_assigned_students[] = $temp;
                }
            }

            $data['my_students'] = $my_students;
            $data['not_assigned_students'] = $not_assigned_students;
            $data['assigned_students'] = $assigned_students;
            $data['content'] = 'smartgenkrtc/smartgen_assignWorksheet';
            $this->load->view('include/master_view', $data);
        }

    }

	public function outputPdf() {
	    $output_worksheet_name = $this->input->post('pdfWorksheetName');
        if (!isset($output_worksheet_name) or empty($output_worksheet_name) !== false) {
            $output_worksheet_name = 'SmartGen Worksheet.pdf';
        } else {
            $output_worksheet_name .= '.pdf';
        }
		$this->load->library('m_pdf');
		$html = urldecode(base64_decode($this->input->post('pdfOutputString')));
        preg_match('/<svg[^>]*>\s*(<defs.*?>.*?<\/defs>)\s*<\/svg>/',$html,$m);
		$defs = $m[1];
		$html = preg_replace('/<svg[^>]*>\s*<defs.*?<\/defs>\s*<\/svg>/','',$html);
		$html = preg_replace('/(<svg[^>]*>)/',"\\1".$defs,$html);
        $html = preg_replace('/<span class="MJX_Assistive_MathML" role="presentation">(.*?)<\/span>/', '', $html);
		preg_match_all('/<svg([^>]*)(width=".*?") (height=".*?") viewBox="(.*?)"/',$html,$m);
		for ($i=0;$i<count($m[0]);$i++) {
			$width = $m[2][$i];
			$height = $m[3][$i];
			$viewBox = $m[4][$i];
			preg_match('/width="(.*?)"/',$width, $wr);
			$w = $this->mpdf->ConvertSize($wr[1],0,$this->mpdf->FontSize) * $this->mpdf->dpi/25.4;
			preg_match('/height="(.*?)"/',$height, $hr);
			$h = $this->mpdf->ConvertSize($hr[1],0,$this->mpdf->FontSize) * $this->mpdf->dpi/25.4;
			$replace = '<svg'.$m[1][$i].' width="'.$w.'" height="'.$h.'" viewBox="'.$m[4][$i].'"';
			$html = str_replace($m[0][$i],$replace,$html);
		}

		$html = str_replace('currentColor', '#333', $html);
		$pdfOutputString = '
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

		div.question {
			page-break-inside: avoid;
			position: relative;
		}

		div.pull-right {
			text-align: right;
		}

		body {
			font-family: "Lato", sans-serif;
		}

		img { vertical-align: middle; }

		.MathJax_SVG_Display { padding: 1em 0; }

		</style>
		</head>
		<body>
		<htmlpageheader name="myHeader1" style="display:none">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
			    <td width="40%" align="left">SmartGen for KRTC - Questions</td>
			    <td width="30%"></td>
			    <td width="30%" align="right"><img src="'.base_url().'img/smartjen-logo-text.jpg" style="width: 5cm"></td>
			    </tr>
			</table>
		</htmlpageheader>

		<htmlpageheader name="myHeader2" style="display:none">
			<table width="100%" style="vertical-align: bottom; font-size: 12pt; 
			    color: #000000; border-bottom: 1px solid #333"><tr>
			    <td width="40%" align="left">SmartGen for KRTC- Answers</td>
			    <td width="30%"></td>
			    <td width="30%" align="right"><img src="'.base_url().'img/smartjen-logo-text.jpg" style="width: 5cm"></td>
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
		' . $html . '</body></html>';

		// echo $pdfOutputString;
		// exit(0);

		$this->mpdf->WriteHTML($pdfOutputString);
		// $this->mpdf->SetTitle($worksheetName);
		$this->mpdf->Output($output_worksheet_name, 'D');
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

				$this->email->from('hello@smartjen.com', "Smartjen - No Reply");
				$this->email->to("bklim5@hotmail.com", "Smartjen Admin");
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
}
