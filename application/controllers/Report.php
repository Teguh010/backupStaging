<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



defined('BASEPATH') OR exit('No direct script access allowed');



class Report extends CI_Controller {

    public function __construct()

    {

        parent::__construct();

        $this->load->model('model_users');

        $this->load->helper('slug');

        $this->load->model('model_question');

        $this->load->model('model_quiz');

        $this->load->model('model_worksheet');

        $this->load->model('model_general');

    }



    public function index()

    {

        redirect('404');

    }



    public function user($username, $subject_id = NULL) {
        if(BRANCH_TID==1) redirect(base_url()."report/user_tid/".$username);
        if ($this->session->userdata('is_logged_in') == 1) {

            // check if the user is authorized to view report

            $report_user_id = $this->model_users->get_user_id_from_email_or_username($username);

            $user_id = $this->session->userdata('user_id');



            $student_list = $this->model_users->get_student_list($user_id);

            $student_ids = array();

            foreach ($student_list as $student) {

                $student_ids[] = $student->student_id;

            }


            $children_list = $this->model_users->get_children_list($user_id);
            $children_ids = array();

            foreach ($children_list as $child) {

                $children_ids[] = $child->student_id;

            }



            if ($report_user_id == $user_id || in_array($report_user_id, $student_ids) || in_array($report_user_id, $children_ids)) {

                $userInfo = $this->model_users->get_user_info($report_user_id);

                $profilePic = $userInfo->profile_pic;

                $userFullName   = $userInfo->fullname;

                $sec_student = range(492, 512);

                if(in_array($report_user_id, $sec_student)) {

                    $data['analysis_structure'] = $this->model_question->get_strand_structure(5);

                } else {

                    $data['analysis_structure'] = $this->model_question->get_strand_structure($subject_id);

                }

                $data['strategy_structure'] = $this->model_question->get_strategy_structure();

                $data['userFullName'] = $userFullName;

                $data['profilePic'] = $profilePic;
                
                if(in_array($report_user_id, $sec_student)) {
                    
                    $data['performance'] = $this->model_users->get_performance(array($report_user_id), 5)[$report_user_id];

                } else {
                    
                    $data['performance'] = $this->model_users->get_performance(array($report_user_id), $subject_id)[$report_user_id];

                }

                //$data['heuristic_performance'] = $this->model_users->get_heuristic_performance(array($report_user_id))[$report_user_id];

                $data['student_id'] = $report_user_id;

            } else {

                $data['report_error'] = true;

                $data['error_message'] = 'You are not authorized to view this report';

            }

            $data['content'] = 'report/view_report';

            $this->load->view('include/master_view', $data);

        } else {

            redirect('404');

        }

    }

    public function user_tid($username) {
        $subject_id = NULL;
        if ($this->session->userdata('is_logged_in') == 1) {
            $post = $this->input->post();
            $date_range = array();
            if (isset($post) && empty($post) === false) {
                $date_range = array(
                    str_replace('-','',$post['dr_start']),
                    str_replace('-','',$post['dr_end'])
                );
                $data_ar = array(
                    'dr_start' => $post['dr_start'],
                    'dr_end' => $post['dr_end'],
                    'level_id' => $post['level_id']
                );
                $this->session->set_userdata($data_ar);
            }

            // check if the user is authorized to view report
            $report_user_id = $this->model_users->get_user_id_from_email_or_username($username);
            $user_id = $this->session->userdata('user_id');
            // if($subject_id == NULL) {
            //     $subject_id = $this->model_users->getStudentSubjectType($user_id);
            // }
            $student_list = $this->model_users->get_student_list($user_id);
            $student_ids = array();
            foreach ($student_list as $student) {
                $student_ids[] = $student->student_id;
            }

            $children_list = $this->model_users->get_children_list($user_id);
            $children_ids = array();

            foreach ($children_list as $child) {
                $children_ids[] = $child->student_id;
            }
            
            if ($report_user_id == $user_id || in_array($report_user_id, $student_ids) || in_array($report_user_id, $children_ids)) {
                $userInfo = $this->model_users->get_user_info($report_user_id);
                $userLevel = $this->model_users->get_level_tid_by_student_id($report_user_id);
                $level_code = ($userLevel) ? $userLevel->level_code : 'junior';
                $data['level_name'] = ($this->session->userdata('level_id')=="") ? $this->model_question->get_level_tid_by_id($level_code) : $this->model_question->get_level_tid_by_id($this->session->userdata('level_id'));
                $profilePic = $userInfo->profile_pic;
                $userFullName   = $userInfo->fullname;
                $data['analysis_structure'] = $this->model_question->get_ep_analysis_structure();
                $level_code = ($this->session->userdata('level_id')=="") ? $level_code : $this->session->userdata('level_id');
                $data['topics_tid'] = $this->model_question->get_topiclevel_list($level_code);
                $data['userFullName'] = $userFullName;
                $data['profilePic'] = $profilePic;
                $data['performance'] = $this->model_users->get_ep_performance(array($report_user_id), $date_range)[$report_user_id];
                $data['student_id'] = $report_user_id;
            } else {
                $data['report_error'] = true;
                $data['error_message'] = 'You are not authorized to view this report';
            }
            $studentInfo = $this->model_users->get_student_info($report_user_id);

            $data['levels'] = $this->model_question->get_level_list_tid(); 

            $userName   = $userInfo->username;
            $data['userName'] = $userName;
            $data['content'] = 'report/view_report_tid';
            $this->load->view('include/master_view', $data);
        } else {
            redirect('404');
        }

    }

    public function user_ability_tid($username) {
        $subject_id = NULL;
        if ($this->session->userdata('is_logged_in') == 1) {
            $post = $this->input->post();
            $date_range = array();
            if (isset($post) && empty($post) === false) {
                $date_range = array(
                    str_replace('-','',$post['dr_start']),
                    str_replace('-','',$post['dr_end'])
                );
                $data_ar = array(
                    'dr_start' => $post['dr_start'],
                    'dr_end' => $post['dr_end'],
                    'level_id' => $post['level_id']
                );
                $this->session->set_userdata($data_ar);
            }

            // check if the user is authorized to view report
            $report_user_id = $this->model_users->get_user_id_from_email_or_username($username);
            $user_id = $this->session->userdata('user_id');
            // if($subject_id == NULL) {
            //     $subject_id = $this->model_users->getStudentSubjectType($user_id);
            // }
            $student_list = $this->model_users->get_student_list($user_id);
            $student_ids = array();
            foreach ($student_list as $student) {
                $student_ids[] = $student->student_id;
            }

            $children_list = $this->model_users->get_children_list($user_id);
            $children_ids = array();

            foreach ($children_list as $child) {
                $children_ids[] = $child->student_id;
            }
            
            if ($report_user_id == $user_id || in_array($report_user_id, $student_ids) || in_array($report_user_id, $children_ids)) {
                $userInfo = $this->model_users->get_user_info($report_user_id);
                $userLevel = $this->model_users->get_level_tid_by_student_id($report_user_id);
                $level_code = ($userLevel) ? $userLevel->level_code : 'junior';
                $data['level_name'] = ($this->session->userdata('level_id')=="") ? $this->model_question->get_level_tid_by_id($level_code) : $this->model_question->get_level_tid_by_id($this->session->userdata('level_id'));
                $profilePic = $userInfo->profile_pic;
                $userFullName   = $userInfo->fullname;
                $data['analysis_structure'] = $this->model_question->get_ep_analysis_structure();
                $level_code = ($this->session->userdata('level_id')=="") ? $level_code : $this->session->userdata('level_id');
                $data['abilities_tid'] = $this->model_question->get_ability_list();
                $data['userFullName'] = $userFullName;
                $data['profilePic'] = $profilePic;
                $data['performance'] = $this->model_users->get_ep_performance_ability(array($report_user_id), $date_range)[$report_user_id];
                $data['student_id'] = $report_user_id;
            } else {
                $data['report_error'] = true;
                $data['error_message'] = 'You are not authorized to view this report';
            }
            $studentInfo = $this->model_users->get_student_info($report_user_id);

            $data['levels'] = $this->model_question->get_level_list_tid(); 

            $userName   = $userInfo->username;
            $data['userName'] = $userName;
            $data['content'] = 'report/view_report_ability_tid';
            $this->load->view('include/master_view', $data);
        } else {
            redirect('404');
        }

    }

    public function performance($wsId="") {
        $subject_id = NULL;
        if ($this->session->userdata('is_logged_in') == 1) {
            $post = $this->input->post();
            $date_range = array();
            if (isset($post) && empty($post) === false) {
                $date_range = array(
                    str_replace('-','',$post['dr_start']),
                    str_replace('-','',$post['dr_end'])
                );
                $data_ar = array(
                    'dr_start' => $post['dr_start'],
                    'dr_end' => $post['dr_end'],
                    'level_id' => $post['level_id']
                );
                $this->session->set_userdata($data_ar);
            }
            $data['origin_ws'] = $wsId;
            if($wsId=="") { 
                $data['origin_ws_name'] = "";
            } else {
                $data['origin_ws_name'] = $this->model_worksheet->get_worksheet_name_from_id($wsId);
            }
            $data['content'] = 'report/view_report_performance';
            $this->load->view('include/master_view', $data);
        } else {
            redirect('404');
        }

    }

    public function basic_report_pdf($username,$is_html = '0') {

        if ($this->session->userdata('is_logged_in') == 1) {

            // check if the user is authorized to view report

            $report_user_id = $this->model_users->get_user_id_from_email_or_username($username);

            $user_id = $this->session->userdata('user_id');


            
            $student_list = $this->model_users->get_student_list($user_id);

            $student_ids = array();
            
            foreach ($student_list as $student) {

                $student_ids[] = $student->student_id;

            }


            $children_list = $this->model_users->get_children_list($user_id);
            $children_ids = array();
            
            foreach ($children_list as $child) {

                $children_ids[] = $child->student_id;

            }



            if ($report_user_id == $user_id || in_array($report_user_id, $student_ids) || in_array($report_user_id, $children_ids)) {

                $userInfo = $this->model_users->get_user_info($report_user_id);
                
                $profilePic = $userInfo->profile_pic;

                $userFullName   = $userInfo->fullname;

                $data['analysis_structure'] = $this->model_question->get_strand_structure();

                $data['strategy_structure'] = $this->model_question->get_strategy_structure();

                $data['userFullName'] = $userFullName;

                $data['profilePic'] = $profilePic;

                $data['performance'] = $this->model_users->get_performance(array($report_user_id))[$report_user_id];

                //$data['heuristic_performance'] = $this->model_users->get_heuristic_performance(array($report_user_id))[$report_user_id];

                $data['student_id'] = $report_user_id;
                $data['student_level'] = $this->model_users->get_level_by_student_id($report_user_id);
                
            } else {

                $data['report_error'] = true;

                $data['error_message'] = 'You are not authorized to view this report';

            }



            //$data['content'] = 'report/basic_report';

            $content = $this->load->view('report/basic_report', $data,true);
            if($is_html == '1'){
                //array_debug($data);exit;
                echo $content;
            }else{
                //Initial PDF lib
                $this->load->library('m_pdf');
                $this->mpdf->WriteHTML($content);
               
                $this->mpdf->Output($username.".pdf", 'I');
            }

        } else {

            redirect('404');

        }

    }

    
    /***Secondary Math Prototype***/
    
    public function sc_user($username) {
        if ($this->session->userdata('is_logged_in') == 1) {
            // check if the user is authorized to view report
            $report_user_id = $this->model_users->get_user_id_from_email_or_username($username);
            $user_id = $this->session->userdata('user_id');
            $student_list = $this->model_users->get_student_list($user_id);
            $student_ids = array();
            foreach ($student_list as $student) {
                $student_ids[] = $student->student_id;
            }
            $children_list = $this->model_users->get_children_list($user_id);
            $children_ids = array();
            foreach ($children_list as $child) {
                $children_ids[] = $child->student_id;
            }
            if ($report_user_id == $user_id || in_array($report_user_id, $student_ids) || in_array($report_user_id, $children_ids)) {
                $userInfo = $this->model_users->get_user_info($report_user_id);
                $profilePic = $userInfo->profile_pic;
                $userFullName   = $userInfo->fullname;
                $data['analysis_structure'] = $this->model_question->get_sc_strand_structure();
                $data['userFullName'] = $userFullName;
                $data['profilePic'] = $profilePic;
                $data['performance'] = $this->model_users->get_sc_performance(array($report_user_id))[$report_user_id];
                $data['student_id'] = $report_user_id;
            } else {
                $data['report_error'] = true;
                $data['error_message'] = 'You are not authorized to view this report';
            }
            $data['content'] = 'report/view_report_sc';
            $this->load->view('include/master_view', $data);
        } else {
            redirect('404');
        }
    }
    
    
    /***Secondary Math Prototype***/
}