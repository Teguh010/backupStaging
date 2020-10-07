<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExamMode extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('M_ExamMode', 'exam_mode');
    }
    
    public function readQuestion(){
        $id             = $this->input->get('id');
        $requirement_id = $this->input->get('requirement_id');
        $level          = $this->input->get('level');
        $subject        = $this->input->get('subject');
        $queType        = $this->input->get('queType');
        $response['data'] = $this->exam_mode->readQuestion($id, $requirement_id, $level, $subject, $queType);          
        echo json_encode($response);
    }
    
    public function getHeaderEdit($id){
        $row = $this->exam_mode->getHeaderEdit($id);
        $result['reqSubject'] = $row['reqSubject'];
        $result['reqSubstrand'] = $row['reqSubstrand'];
        $result['reqTopic'] = $row['reqTopic'];
        $result['reqStrategy'] = $row['reqStrategy'];
        $result['reqDifficulty'] = $row['reqDifficulty'];
        echo json_encode($result);
    }

    public function getInformation($id){
        echo json_encode($this->exam_mode->getInformation($id));
    }

    public function getLevel($level_id){
        $result['data'] = $this->exam_mode->getLevel($level_id);
        echo json_encode($result);
    }

    function getMoreQuestions($page = 1){        
        $start = (($page <= 0 ? 1 : $page) - 1) * 10;
        $getData = $this->exam_mode->getMoreQuestions($start);
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
        $this->load->view('smartgen/smartgen_examAddMore', $data);      
    }

    public function updateQuestion(){
        if($this->exam_mode->updateQuestion() > 0 ){
            $data['msg'] = 'success';
        }else{
            $data['msg'] = 'failed';
        }
        $data['question'] = $this->exam_mode->getDetailQuestion($this->input->post('question_id'));
        $data['answer'] = $this->exam_mode->getAnswers($this->input->post('question_id'));
        echo json_encode($data);
    }
    
    public function updateNumberOfQuestion(){
        if($this->exam_mode->updateNumberOfQuestion() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }
    
    public function deleteQuestion(){
        if($this->exam_mode->deleteQuestion() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }

    public function regenerateAllQuestion($requirement_id){
        // if($this->exam_mode->regenerateAllQuestion($this->session->userdata('requirementId')) > 0 ){
        //  $response['msg'] = 'success';
        // }else{
        //  $response['msg'] = 'failed';
        // }
        
        // echo json_encode($response);
        $this->exam_mode->regenerateAllQuestion($requirement_id);
        redirect(base_url() . 'smartgen/generateExam');
    }

    public function regenerateQuestionID($id, $questionType){
        $question_id = $this->exam_mode->regenerateQuestionID($id, $questionType);
        $data['question'] = $this->exam_mode->getDetailQuestion($question_id);
        $data['answer'] = $this->exam_mode->getAnswers($question_id);
        echo json_encode($data);
    }

}

?>