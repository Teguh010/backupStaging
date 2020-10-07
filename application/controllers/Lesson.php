<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_lesson');
    }


    public function getSubjectLevelList(){
        echo json_encode($this->M_lesson->get_subjectlevel_list());
    }


    public function getWorksheetList($subject_id){
        $userId = $this->session->userdata('user_id');
        $userRole = $this->session->userdata('user_role');
        
        echo json_encode($this->M_lesson->get_worksheet_list($userId, $subject_id));
    }


    public function loadLesson(){
        $data['lessons'] = $this->M_lesson->read_lessons();
		$this->load->view('profile/tutor/table_lesson', $data);
    }
    
    
    public function loadLectureBySection($section_id){
        echo json_encode($this->M_lesson->read_lecture_by_section($section_id));
    }
    
    
    public function createLesson(){
        if($this->M_lesson->create_lesson() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function createSection(){
        if($this->M_lesson->create_section() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function createNewSection(){
        if($this->M_lesson->create_new_section() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function createNewLecture(){
        if($this->M_lesson->create_new_lecture() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function getLessonById($id){
        echo json_encode($this->M_lesson->read_lessons_id($id));
    }


	public function loadSectionLesson($id = NULL){
        $response['data'] = $this->M_lesson->read_section_lesson_id($id);
        echo json_encode($response);
    }    


    public function updateVideo(){
        if($this->M_lesson->update_video() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }
    

    public function uploadFile(){
        if($this->input->post('fileType') == 'doc'){

            $result = $this->M_lesson->update_module();
            if($result > 0){
                $response['msg'] = 'success';
            }else{
                $response['msg'] = $result;
            }
            
        }else{

            $result = $this->M_lesson->update_video();
            if($result > 0){
                $response['msg'] = 'success';
            }else{
                $response['msg'] = $result;
            }
            
        }

        echo json_encode($response);
    }


    public function updateAssessment(){
        if($this->M_lesson->update_worksheet() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }
    

    public function getSectionDetail($id){
        echo json_encode($this->M_lesson->read_section_detail_id($id));
    }


    public function updateLesson(){
        if($this->M_lesson->update_lesson() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }

	
	public function deleteLesson($id){
        if($this->M_lesson->delete_lesson($id) > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function updateSection(){
        if($this->M_lesson->update_section() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }

	
	public function deleteSection($id){
        if($this->M_lesson->delete_section($id) > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function updateLecture(){
        if($this->M_lesson->update_lecture() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }

	
	public function deleteLecture($id){
        if($this->M_lesson->delete_lecture($id) > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function createStudentAssignment(){
        if($this->M_lesson->create_student_assignment() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function updateStudentAssignment(){
        if($this->M_lesson->update_student_assignment() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function getStudentAssignment($lesson_id){
        echo json_encode($this->M_lesson->read_student_assignment($lesson_id));
    }


    public function searchLessonByStudent($page = 1){     
        $userId = $this->session->userdata('user_id');

        $start = (($page <= 0 ? 1 : $page) - 1) * 10;
        $getData = $this->M_lesson->search_lesson_by_student($start, $userId);
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
        $this->load->view('profile/student/data_lesson', $data);      
    }


    public function clickViewModue(){
        if($this->M_lesson->create_lecture_views() > 0 ){
            $response['msg'] = 'success';
        }else{
            $response['msg'] = 'failed';
        }

        echo json_encode($response);
    }


    public function getStudentViewed($lecture_id){
        echo json_encode($this->M_lesson->get_student_viewed($lecture_id));
    }


}

?>