<?php

    class M_lesson extends CI_Model {   
        
        private $user_id;
        private $user_role;

        function __construct(){
            parent::__construct();
            $this->user_id = $this->session->userdata('user_id');
            $this->user_role = $this->session->userdata('user_role');
        }


        public function get_subjectlevel_list(){                        
            $this->db->select('b.id, a.id as subject_id, a.name as subject_name, b.level_id, b.level_name');
            $this->db->from('sj_subject a');
            $this->db->join('sj_levels b', 'b.subject_type=a.id');
            $this->db->where('b.level_id NOT IN (7,8)');            
            $this->db->order_by('a.name', 'ASC');
            $this->db->order_by('b.level_name', 'ASC');
            return $this->db->get()->result_array();
        }


        public function get_worksheet_list($userId, $subject_id){                        
            
            $sql = "SELECT * FROM `sj_worksheet` 
                    WHERE `created_by` = '$userId' 
                    AND `subject_type` = '$subject_id' 
                    AND `is_mock_exam` = 0 
                    AND `branch_code` = ".BRANCH_ID." 
                    AND `branch_tag` = '".BRANCH_TAG."' 
                    AND `is_parent` = 0 
                    AND `archived` = 0 
                    ORDER BY `created_date` DESC";
            
            $query = $this->db->query($sql);
            $worksheetList = array();
            foreach ($query->result() AS $worksheet) {
                $worksheetList[] = $worksheet;
            }
            return $worksheetList;
        }

        // LESSONS
        public function read_lessons(){                    	
            $sql = "SELECT a.*, b.level_name, c.name AS subject_name FROM sj_lessons a 
                    JOIN sj_levels b ON a.level_id=b.id 
                    JOIN sj_subject c ON b.subject_type=c.id 
                    WHERE a.user_id='".$this->user_id."' ORDER BY a.date_created DESC
            ";
            
            $result = $this->db->query($sql)->result();
            return $result;
        }


        public function read_lessons_id($id){            
            return $this->db->get_where('sj_lessons', ['lesson_id' => $id])->row_array();
        }


        public function read_section_lesson_id($id = NULL){

            if($id == NULL){
                $id = $this->session->userdata('lessonId');
            }

            $sql = "SELECT a.*, b.level_name, b.subject_type, c.name AS subject_name FROM sj_lessons a 
                    JOIN sj_levels b ON a.level_id=b.id 
                    JOIN sj_subject c ON b.subject_type=c.id 
                    WHERE a.lesson_id =".$id
            ;
            
            $result['data_lesson'] = $this->db->query($sql)->row_array();

            $sql = "SELECT * FROM sj_lesson_sections a                                    
                WHERE a.lesson_id = ".$id." 
                ORDER BY a.number
            ";

            $result['data_section'] = $this->db->query($sql)->result();

            $sql = "SELECT b.* FROM sj_lesson_sections a 
                    JOIN sj_lesson_section_detail b ON a.section_id=b.section_id                     
                    WHERE a.lesson_id = ".$id." 
                    ORDER BY a.number, b.number ASC
            ";

            $lectures = $this->db->query($sql)->result();

            foreach($lectures as $lecture){
                $lecture->total_student_viewed = $this->count_student_viewed($lecture->id);
            }

            $result['data_lecture'] = $lectures;

            return $result;
        }


        public function read_section_detail_id($id){   

            $sql = "SELECT * FROM sj_lesson_section_detail WHERE id='$id' ";
            $result['data_lecture'] = $this->db->query($sql)->row_array();
            $result['data_lecture']['total_student_viewed'] = $this->count_student_viewed($id);

            return $result;
        }


        public function read_lecture_by_section($section_id){   

            $sql = "SELECT * FROM sj_lesson_section_detail WHERE section_id='$section_id' ";
            $lectures = $this->db->query($sql)->result();

            foreach($lectures as $row){
                $row->total_student_viewed = $this->count_student_viewed($row->id);
            }

            return $lectures;
        }


        public function create_lesson(){
            $date_created = date('Y-m-d H:i:s');
            $postData = $this->input->post();

            $data = [
                'level_id' => $postData['level_id'],
                'title' => $postData['title'],
                'tags' => $postData['tags'],
                'description' => $postData['description'],                
                'user_id' => $this->session->userdata('user_id'),
                'date_created' => $date_created
            ];

            $this->db->insert('sj_lessons', $data);     
            
            $this->session->set_userdata('lessonId', $this->db->insert_id());

            return $this->db->affected_rows();
        }


        public function create_section(){
            $date_created = date('Y-m-d H:i:s');
            $postData = $this->input->post();
            
            for($i=0 ; $i<count($postData['section_title']) ; $i++){
                $data = [
                    'lesson_id' => $this->session->userdata('lessonId'),
                    'number' => $i+1,
                    'section_title' => $postData['section_title'][$i],
                    'date_created' => $date_created
                ];
    
                $this->db->insert('sj_lesson_sections', $data);
                $this->session->set_userdata('sectionId', $this->db->insert_id());

                for($j=0 ; $j<count($postData['subsection'.($i+1)]) ; $j++){
                    $data = [
                        'section_id' => $this->session->userdata('sectionId'),
                        'number' => $j+1,
                        'lecture_title' => $postData['subsection'.($i+1)][$j],
                        'date_created' => $date_created
                    ];
        
                    $this->db->insert('sj_lesson_section_detail', $data);
                }

            }

            return $this->db->affected_rows();

        }


        public function create_new_section(){
            $date_created = date('Y-m-d H:i:s');
            $postData = $this->input->post();

            $sql = "SELECT MAX(number) as last_number FROM sj_lesson_sections WHERE lesson_id='$postData[lesson_id]'";
            $number = $this->db->query($sql)->row();
            
            if(isset($number)){
                $number = $number->last_number + 1;
            }else{
                $number = 1;
            }

            $data = [
                'lesson_id' => $postData['lesson_id'],
                'number' => $number,
                'section_title' => $postData['section_title'],
                'date_created' => $date_created
            ];

            $this->db->insert('sj_lesson_sections', $data);

            return $this->db->affected_rows();
        }


        public function create_new_lecture(){
            $date_created = date('Y-m-d H:i:s');
            $postData = $this->input->post();

            $sql = "SELECT MAX(number) as last_number FROM sj_lesson_section_detail WHERE section_id='$postData[section_id]'";
            $number = $this->db->query($sql)->row();
            
            if(isset($number)){
                $number = $number->last_number + 1;
            }else{
                $number = 1;
            }

            $data = [
                'section_id' => $postData['section_id'],
                'number' => $number,
                'lecture_title' => $postData['lecture_title'],
                'date_created' => $date_created
            ];

            $this->db->insert('sj_lesson_section_detail', $data);

            return $this->db->affected_rows();
        }


        function do_upload($id, $fileType){
            $file_name = microtime(true);
            $file_name = str_replace('.', '', $file_name);
            $file_name = date('Y-m-d').'-'.$file_name;
            
            $path = $_FILES['file_upload'.$id]['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);            

            if($fileType == 'doc'){
                $config['upload_path']          = './uploaded_file/doc/';
                if($ext == 'doc' || $ext == 'docx'){
                    $config['allowed_types']        = '*';
                }else{
                    $config['allowed_types']        = 'pdf|doc|docx|dot|dotx|word|ppt|pptx|xls|xlsx|txt';
                }
                
                $config['max_size']             = 100000;
            }else if($fileType == 'video'){
                $config['upload_path']          = './uploaded_file/video/';
                $config['allowed_types']        = 'mpeg|mpg|mp4|mpe|qt|avi|flv|mkv';
                $config['max_size']             = 250000;
            }

            $config['file_name']            = $file_name;
            $config['file_ext_tolower']     = TRUE;
            $config['overwrite']            = TRUE;            

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file_upload'.$id)) {
                $result['msg'] = 'success';
                $result['file_name'] = $this->upload->data('file_name');
                return $result;
            }else{
                $data['error_upload'] = array('error' => $this->upload->display_errors());
                $result['msg'] = $data['error_upload']['error'];
                return $result;
            }            
        }


        public function update_video(){
            $postData = $this->input->post();

            if($postData['type'] == 'embed' || $postData['type'] == 'gdrive'){

                $this->delete_old_file($postData['id'], 'video');
                
                $data = [
                    'uploaded_video' => $postData['uploaded_video'],
                    'uploaded_video_type' => $postData['type'],                
                ];

                $this->db->where('id', $postData['id']);
                $this->db->update('sj_lesson_section_detail', $data);            
    
                return $this->db->affected_rows();

            }else{

                $uploadStatus = $this->do_upload($postData['id'], $postData['fileType']);
                
                if($uploadStatus['msg'] == 'success'){

                    $this->delete_old_file($postData['id'], 'video');

                    $data = [
                        'uploaded_video' => $uploadStatus['file_name'],
                        'uploaded_video_type' => 'local',                
                    ];
    
                    $this->db->where('id', $postData['id']);
                    $this->db->update('sj_lesson_section_detail', $data);            
    
                    return $this->db->affected_rows();

                }else{

                    return $uploadStatus['msg'];

                }

            }            

            
        }


        public function update_module(){
            $postData = $this->input->post();

            if($postData['type'] == 'gdrive'){

                $this->delete_old_file($postData['id'], 'doc');
                
                $data = [
                    'uploaded_doc' => $postData['uploaded_doc'],
                    'uploaded_doc_type' => $postData['type']                
                ];

                $this->db->where('id', $postData['id']);
                $this->db->update('sj_lesson_section_detail', $data);            
    
                return $this->db->affected_rows();

            }else{

                $uploadStatus = $this->do_upload($postData['id'], $postData['fileType']);

                if($uploadStatus['msg'] == 'success'){

                    $this->delete_old_file($postData['id'], 'doc');

                    $data = [
                        'uploaded_doc' => $uploadStatus['file_name'],
                        'uploaded_doc_type' => 'local'
                    ];

                    $this->db->where('id', $postData['id']);
                    $this->db->update('sj_lesson_section_detail', $data);            

                    return $this->db->affected_rows();

                }else{

                    return $uploadStatus['msg'];

                }

            }
            
        }


        public function update_worksheet(){

            $postData = $this->input->post();
            
            $data = [
                'worksheet_id' => $postData['worksheet_id']                               
            ];

            $this->db->where('id', $postData['id']);
            $this->db->update('sj_lesson_section_detail', $data);   
            
            if($postData['worksheet_status'] !== 'new'){

                $sql = "SELECT a.lesson_id FROM sj_lessons a 
                        JOIN sj_lesson_sections b ON a.lesson_id=b.lesson_id 
                        JOIN sj_lesson_section_detail c ON b.section_id=c.section_id 
                        WHERE c.id=".$postData['id'];
                $lesson_id = $this->db->query($sql)->row()->lesson_id;

                $sql = "SELECT * FROM sj_student_lesson WHERE lesson_id='$lesson_id'";

                $students = $this->db->query($sql)->result();

                foreach($students as $row){
                    $student_id = $row->student_id;

                    $sql = "SELECT id FROM sj_quiz WHERE worksheetId='$worksheet_id' AND assignedTo='$student_id'";
                    $result = $this->db->query($sql);

                    if($result->num_rows() == 0){

                        $data = [
                            'worksheetId' => $worksheet_id,
                            'assignedTo' => $student_id,
                            'assignedDate' => $date_created,
                            'status' => 1,
                            'subject_type' => $subject_id,
                            'branch_tag' => BRANCH_TAG,
                            'branch_code' => BRANCH_ID 
                        ];

                        $this->db->insert('sj_quiz', $data);

                    }
                }

            }

            return $this->db->affected_rows();
            
        }


        public function update_lesson(){
            $postData = $this->input->post();

            $data = [
                'level_id' => $postData['level_id'],
                'title' => $postData['title'],
                'description' => $postData['description'],
                'tags' => $postData['tags'],
            ];

            $this->db->where('lesson_id', $postData['lesson_id']);
            $this->db->update('sj_lessons', $data);            
    
            return $this->db->affected_rows();       

        }


        public function delete_lesson($id){

            $this->db->where('lesson_id', $id);
            $this->db->delete('sj_lessons');            
    
            return $this->db->affected_rows();       
            
        }


        public function update_section(){
            $postData = $this->input->post();

            $data = [
                'section_title' => $postData['section_title']
            ];

            $this->db->where('section_id', $postData['section_id']);
            $this->db->update('sj_lesson_sections', $data);            
    
            return $this->db->affected_rows();       

        }


        public function delete_section($id){            

            $this->db->where('section_id', $id);
            $this->db->delete('sj_lesson_sections');            
    
            return $this->db->affected_rows();       
            
        }


        public function update_lecture(){
            $postData = $this->input->post();

            $data = [
                'lecture_title' => $postData['lecture_title']
            ];

            $this->db->where('id', $postData['lecture_id']);
            $this->db->update('sj_lesson_section_detail', $data);            
    
            return $this->db->affected_rows();       

        }


        public function delete_lecture($id){

            $this->delete_old_file($id, 'video');
            $this->delete_old_file($id, 'doc');

            $this->db->where('id', $id);
            $this->db->delete('sj_lesson_section_detail');            
    
            return $this->db->affected_rows();       
            
        }


        public function delete_old_file($lecture_id, $type){
            
            $this->load->helper("file");

            $result = $this->db->get_where('sj_lesson_section_detail', ['id' => $lecture_id])->row_array();            

            if($type == 'video'){
                if($result['uploaded_video'] != '' && $result['uploaded_video_type'] == 'local'){
                    $exists = file_exists('./uploaded_file/video/'.$result['uploaded_video']);
                    $path_to_file = './uploaded_file/video/'.$result['uploaded_video'];
                    if ($exists){
                        if(unlink($path_to_file)) {}   
                    }
                }                
            }else if($type == 'doc'){
                if($result['uploaded_doc'] != ''){
                    $exists = file_exists('./uploaded_file/doc/'.$result['uploaded_doc']);
                    $path_to_file = './uploaded_file/doc/'.$result['uploaded_doc'];
                    if ($exists){
                        if(unlink($path_to_file)) {}   
                    }
                }                
            }            

        }


        public function create_student_assignment(){

            $lesson_id = $this->session->userdata('lessonId');
            $postData = $this->input->post();
            $date_created = date('Y-m-d H:i:s');
            $data = [];

            for($i=0 ; $i<count($postData['assigned_students']) ; $i++){

                if(!empty($postData['assigned_students'][$i])){

                    $insertArray = [
                        'lesson_id' => $lesson_id,
                        'student_id' => $postData['assigned_students'][$i],
                        'date_created' => $date_created
                    ];

                    array_push($data, $insertArray);                    
                }

            }
             
            $this->db->insert_batch('sj_student_lesson', $data);


            $sql = "SELECT c.worksheet_id FROM sj_lessons a 
                    JOIN sj_lesson_sections b ON a.lesson_id=b.lesson_id 
                    JOIN sj_lesson_section_detail c ON b.section_id=c.section_id 
                    WHERE a.lesson_id='$lesson_id' AND c.worksheet_id!=0
            ";

            $worksheets = $this->db->query($sql)->result();

            foreach($worksheets as $worksheet){

                $worksheet_id = $worksheet->worksheet_id;

                $subject_id = $this->db->get_where('sj_worksheet', ['worksheet_id' => $worksheet_id])->row_array();
                $subject_id = $subject_id['subject_type'];                
                
                for($i=0 ; $i<count($postData['assigned_students']) ; $i++){

                    $student_id = $postData['assigned_students'][$i];

                    if(!empty($postData['assigned_students'][$i])){
    
                        $sql = "SELECT id FROM sj_quiz WHERE worksheetId='$worksheet_id' AND assignedTo='$student_id'";

                        $result = $this->db->query($sql);

                        if($result->num_rows() == 0){

                            $data = [
                                'worksheetId' => $worksheet_id,
                                'assignedTo' => $student_id,
                                'assignedDate' => $date_created,
                                'status' => 1,
                                'subject_type' => $subject_id,
                                'branch_tag' => BRANCH_TAG,
                                'branch_code' => BRANCH_ID 
                            ];

                            $this->db->insert('sj_quiz', $data);

                        }
                          
                    }
    
                }

            }

            return $this->db->affected_rows();
        }
    
    
        public function update_student_assignment(){
            $postData = $this->input->post();            
            $date_created = date('Y-m-d H:i:s');

            if(count($postData['assigned_students']) > 1){

                $this->db->where('lesson_id', $postData['lesson_id']);
                $this->db->delete('sj_student_lesson');
                $data = [];

                for($i=0 ; $i<count($postData['assigned_students']) ; $i++){

                    if(!empty($postData['assigned_students'][$i])){

                        $insertArray = [
                            'lesson_id' => $postData['lesson_id'],
                            'student_id' => $postData['assigned_students'][$i],
                            'date_created' => $date_created
                        ];

                        array_push($data, $insertArray);                    
                    }

                }
                
                $this->db->insert_batch('sj_student_lesson', $data);

                $sql = "SELECT c.worksheet_id FROM sj_lessons a 
                        JOIN sj_lesson_sections b ON a.lesson_id=b.lesson_id 
                        JOIN sj_lesson_section_detail c ON b.section_id=c.section_id 
                        WHERE a.lesson_id='$lesson_id' AND c.worksheet_id!=0";
                
                $worksheets = $this->db->query($sql)->result();

                foreach($worksheets as $worksheet){

                    $worksheet_id = $worksheet->worksheet_id;

                    $subject_id = $this->db->get_where('sj_worksheet', ['worksheet_id' => $worksheet_id])->row_array();
                    $subject_id = $subject_id['subject_type'];                
                    
                    for($i=0 ; $i<count($postData['assigned_students']) ; $i++){

                        $student_id = $postData['assigned_students'][$i];

                        if(!empty($postData['assigned_students'][$i])){
        
                            $sql = "SELECT id FROM sj_quiz WHERE worksheetId='$worksheet_id' AND assignedTo='$student_id'";

                            $result = $this->db->query($sql);

                            if($result->num_rows() == 0){

                                $data = [
                                    'worksheetId' => $worksheet_id,
                                    'assignedTo' => $student_id,
                                    'assignedDate' => $date_created,
                                    'status' => 1,
                                    'subject_type' => $subject_id,
                                    'branch_tag' => BRANCH_TAG,
                                    'branch_code' => BRANCH_ID 
                                ];

                                $this->db->insert('sj_quiz', $data);

                            }
                            
                        }
        
                    }

                }

                return $this->db->affected_rows();

            }else{

                $this->db->where('lesson_id', $postData['lesson_id']);
                $this->db->delete('sj_student_lesson');
                return $this->db->affected_rows();
                
            }

            

        }


        public function read_student_assignment($lesson_id){
            
            $sql = "SELECT DISTINCT(`id`), `username`, `fullname`, `profile_pic`, `school_level`.`student_id`, `school_name`, `level_name` 
                    FROM `sj_users` user 
                    JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `status` = 1 AND `branch_tag` = '".BRANCH_TAG."') relationship 
                    JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
                    JOIN `sj_schools` 
                    JOIN `sj_levels` 
                    ON `sj_schools`.`school_id` = `sj_student`.`school_id` AND `sj_levels`.`id` = `sj_student`.`level_id`) school_level 
                    WHERE `user`.`id` = `relationship`.`student_id` AND `school_level`.`student_id` = `relationship`.`student_id` 
                    AND `user`.`id` NOT IN (SELECT student_id FROM sj_student_lesson WHERE lesson_id='$lesson_id') 
                    ORDER BY `user`.`id` DESC
            ";
            $result['list_student'] = $this->db->query($sql)->result();

            $sql = "SELECT DISTINCT(`id`), `username`, `fullname`, `profile_pic`, `school_level`.`student_id`, `school_name`, `level_name` 
                    FROM `sj_users` user 
                    JOIN (SELECT `student_id`, `status` FROM `sj_relationship` WHERE `status` = 1 AND `branch_tag` = '".BRANCH_TAG."') relationship 
                    JOIN (SELECT `school_name`, `level_name`, `student_id` from `sj_student` 
                    JOIN `sj_schools` 
                    JOIN `sj_levels` 
                    ON `sj_schools`.`school_id` = `sj_student`.`school_id` AND `sj_levels`.`id` = `sj_student`.`level_id`) school_level 
                    WHERE `user`.`id` = `relationship`.`student_id` AND `school_level`.`student_id` = `relationship`.`student_id` 
                    AND `user`.`id` IN (SELECT student_id FROM sj_student_lesson WHERE lesson_id='$lesson_id') 
                    ORDER BY `user`.`id` DESC
            ";
            $result['list_student_assigned'] = $this->db->query($sql)->result();

            return $result;

        }


        public function count_student_viewed($lecture_id){
            $sql = "SELECT DISTINCT(student_id) FROM sj_lecture_views WHERE lecture_id='$lecture_id'";

            $result = $this->db->query($sql)->num_rows();

            return $result;
        }


        public function get_student_viewed($lecture_id){
            $userId = $this->session->userdata('user_id');

            $sql = "SELECT a.student_id, MAX(a.date_viewed) AS date_viewed, b.fullname, b.profile_pic FROM sj_lecture_views a 
                    JOIN sj_users b ON a.student_id=b.id 
                    WHERE a.lecture_id='$lecture_id' 
                    GROUP BY a.student_id ORDER BY a.date_viewed DESC
            ";

            $result = $this->db->query($sql)->result_array();

            return $result;

        }
        

        // for student account
        public function get_lesson_list_student($student_id = NULL){

            $sql = "SELECT a.*, b.*, c.fullname, d.level_name, d.subject_type, e.name AS subject_name 
                    FROM sj_student_lesson a 
                    JOIN sj_lessons b ON a.lesson_id=b.lesson_id 
                    JOIN sj_users c ON b.user_id=c.id 
                    JOIN sj_levels d ON b.level_id=d.id 
                    JOIN sj_subject e ON d.subject_type=e.id 
                    WHERE a.student_id='$student_id' 
                    ORDER BY b.date_created DESC
            ";

            $result = $this->db->query($sql)->result_array();

            return $result;

        }


        public function get_section($lesson_id){

            $sql = "SELECT * FROM sj_lesson_sections a 
                    WHERE a.lesson_id = '$lesson_id' ORDER BY a.number
            ";

            $result = $this->db->query($sql)->result_array();
            
            return $result;

        }


        public function get_lecture($section_id){
            
            $sql = "SELECT * FROM sj_lesson_section_detail a 
                    WHERE a.section_id = '$section_id' ORDER BY a.number ASC
            ";
            $result = $this->db->query($sql)->result_array();

            return $result;

        }


        public function get_quiz_by_worksheet($worksheet_id, $student_id){
            $sql = "SELECT id FROM sj_quiz WHERE worksheetId='$worksheet_id' AND assignedTo='$student_id'";

            $result = $this->db->query($sql)->row();

            if(isset($result)){
                return $result->id;
            }else{
                return 0;
            }

        }


        public function search_lesson_by_student($start, $student_id = NULL){
            $getData = $this->input->get();
            $searchKeyword  = $getData['searchKeyword'];
            $sql  = "SELECT a.*, b.* 
                    FROM sj_student_lesson a 
                    JOIN sj_lessons b ON a.lesson_id=b.lesson_id                     
                    JOIN sj_users e ON b.user_id=e.id 
                    WHERE a.student_id='$student_id' AND concat('.', b.title, '.') LIKE '%$searchKeyword%' 
                    ORDER BY b.date_created ";
            
            $result['total_rows'] = $this->db->query($sql)->num_rows();
            $sql .= "LIMIT 10 OFFSET $start";
            $result['data'] = $this->db->query($sql)->result_array();   
            
            return $result;
        }


        public function create_lecture_views(){
            $postData = $this->input->post();
            $date_viewed = date('Y-m-d H:i:s');

            $userId = $this->session->userdata('user_id');

            $data = [
                'lecture_id' => $postData['lecture_id'],
                'student_id' => $userId,
                'module_type' => $postData['module_type'],
                'date_viewed' => $date_viewed
            ];

            $this->db->insert('sj_lecture_views', $data);            
    
            return $this->db->affected_rows();
        }


       
        
    }
    
?>